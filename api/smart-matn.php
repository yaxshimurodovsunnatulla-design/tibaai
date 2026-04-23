<?php
/**
 * Tiba AI — Smart Matn (AI Text Overlay) API
 * Rasmga professional sotuvchi matnlarni qo'shish
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Faqat POST so\'rovlar qabul qilinadi'], 405);
}
checkRateLimit(10);

// Tanga tekshirish
$balanceInfo = requireBalance('smart-matn');

$input = getInput();

$productImage = $input['productImage'] ?? null;
$productName = sanitize($input['productName'] ?? '');
$features = $input['features'] ?? '';
$layout = sanitize($input['layout'] ?? 'overlay');
$colorTheme = sanitize($input['colorTheme'] ?? 'auto');
$language = sanitize($input['language'] ?? 'ru');
$aspectRatio = sanitize($input['aspectRatio'] ?? '3:4');
$customPrompt = sanitize($input['customPrompt'] ?? '');

validateImageSize($productImage);

if (empty($productImage)) {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Mahsulot rasmini yuklang'], 400);
}
if (empty($productName) || empty($features)) {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Mahsulot nomi va xususiyatlarini kiriting'], 400);
}

// Prepare features
if (is_array($features)) {
    $features = implode("\n", $features);
}
$featuresList = array_filter(array_map('trim', explode("\n", $features)));
$featuresFormatted = implode(', ', $featuresList);

// Language names
$langNames = [
    'ru' => 'Russian',
    'uz' => 'Uzbek',
    'en' => 'English',
    'tr' => 'Turkish',
    'kz' => 'Kazakh',
];
$targetLang = $langNames[$language] ?? 'Russian';

// Layout descriptions
$layoutDescs = [
    'overlay' => "TEXT OVERLAY layout: Place all text elements DIRECTLY ON TOP of the product image. Use semi-transparent text panels, floating labels, and badges that overlay the image. The product should remain visible through/behind the text elements. Modern e-commerce infographic style.",
    'side' => "SIDE-BY-SIDE layout: Place the product image on the LEFT side (about 55% width) and all text elements on the RIGHT side on a clean panel. Use professional typography hierarchy. Features listed vertically with icons. Clean separation between image and text areas.",
    'banner' => "PROMOTIONAL BANNER layout: Create an advertising banner composition with the product prominently featured. Bold headline text, eye-catching badges (discounts, quality seals), feature highlights in modern badge/pill style. Energetic, sales-driven commercial design.",
];
$layoutDesc = $layoutDescs[$layout] ?? $layoutDescs['overlay'];

// Color theme descriptions
$colorDescs = [
    'auto' => "Automatically choose the best color palette that harmonizes with the product's colors.",
    'dark' => "Dark/black theme — dark background panels, white/light text, subtle glow accents. Premium luxury feel.",
    'light' => "Light/white theme — clean white panels, dark text, subtle shadows. Fresh, minimal professional look.",
    'gradient' => "Vibrant gradient theme — use bold gradients (purple-pink, blue-cyan, etc.) for text panels and badges. Modern, trendy, eye-catching.",
];
$colorDesc = $colorDescs[$colorTheme] ?? $colorDescs['auto'];

// Build the prompt
$prompt = "SMART TEXT OVERLAY TASK for product: \"{$productName}\"\n\n";
$prompt .= "I am providing a product image. Your job is to add professional, commercial-grade text elements to this image to create a marketplace-ready infographic.\n\n";
$prompt .= "LAYOUT: {$layoutDesc}\n\n";
$prompt .= "COLOR THEME: {$colorDesc}\n\n";
$prompt .= "TEXT ELEMENTS TO ADD:\n";
$prompt .= "1. PRODUCT TITLE: \"{$productName}\" — Bold, prominent, professional font.\n";
$prompt .= "2. FEATURE HIGHLIGHTS: Display these features with matching icons:\n";

foreach ($featuresList as $i => $feature) {
    $num = $i + 1;
    $prompt .= "   {$num}. {$feature}\n";
}

$prompt .= "3. QUALITY BADGES: Add 1-2 professional trust seals (e.g., ✓ Premium Quality, ★ Best Seller, etc.)\n";
$prompt .= "4. VISUAL ICONS: Use clean, modern icons next to each feature.\n\n";

if (!empty($customPrompt)) {
    $prompt .= "ADDITIONAL INSTRUCTIONS: {$customPrompt}\n\n";
}

$prompt .= "CRITICAL RULES:\n";
$prompt .= "1. ALL text MUST be in {$targetLang} language ONLY.\n";
$prompt .= "2. PRESERVE the product from the image EXACTLY — do NOT alter the product itself.\n";
$prompt .= "3. Text must be SHARP, READABLE, and professionally typeset.\n";
$prompt .= "4. Use premium modern fonts (sans-serif for features, bold for titles).\n";
$prompt .= "5. Output: Ultra high quality, 8K resolution, commercial marketplace standard.\n";
$prompt .= "6. Aspect Ratio: {$aspectRatio}.\n";
$prompt .= "7. The result should look like it was designed by a professional graphic designer.\n";

// Build parts
$parts = [];

// Product image
$processedImage = processImageInput($productImage);
if ($processedImage) {
    $parts[] = ['inline_data' => $processedImage];
} else {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Mahsulot rasmi yuklanmagan yoki havolasi noto\'g\'ri'], 400);
}

// Prompt text
$parts[] = ['text' => $prompt];

// Call Gemini API
$result = callGeminiAPI($parts, $aspectRatio);

if (empty($result['imageBase64'])) {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Rasm yaratilmadi. Qayta urinib ko\'ring.'], 500);
}

// Save the result
$imageUrl = saveImage($result['imageBase64'], $result['mimeType'], 'smart-matn');

// Telegram
$telegramMsgId = null;
$msg = "✍️ *Smart Matn*\n\n";
$msg .= "🛍 *Mahsulot:* " . sanitize($productName) . "\n";
$msg .= "🎨 *Layout:* " . ucfirst($layout) . "\n";
$msg .= "\n🤖 _Tiba AI_";

try {
    $tgResponseRaw = sendToTelegram($msg, $imageUrl, true);
    $tgResponse = json_decode($tgResponseRaw, true);
    if (isset($tgResponse['ok']) && $tgResponse['ok'] && isset($tgResponse['result']['message_id'])) {
        $telegramMsgId = $tgResponse['result']['message_id'];
    }
} catch (Exception $e) { error_log($e->getMessage()); }

// History
$user = getAuthUser();
if ($user) {
    try {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO generations (user_id, image_path, prompt_data, telegram_msg_id) VALUES (?, ?, ?, ?)");
        $promptData = json_encode([
            'product' => $productName,
            'features' => $featuresFormatted,
            'style' => $layout,
            'color' => $colorTheme,
            'type' => 'smart-text'
        ], JSON_UNESCAPED_UNICODE);
        $stmt->execute([$user['id'], $imageUrl, $promptData, $telegramMsgId]);
    } catch (Exception $e) { error_log($e->getMessage()); }
}

// Tangalarni ayirish
$newBalance = deductBalance($balanceInfo['user']['id'], $balanceInfo['cost'], 'smart-matn');

jsonResponse(['success' => true, 'imageUrl' => $imageUrl, 'balance' => $newBalance]);
