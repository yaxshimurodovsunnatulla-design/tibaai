<?php
/**
 * Tiba AI — Infografika Paketi (5 slayd)
 * 1:1 Mirror from tibaai.uz
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/category-prompts.php';

$logMsg = date('[Y-m-d H:i:s] ') . $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI'] . " | IP: " . ($_SERVER['REMOTE_ADDR']??'unk') . "\n";
file_put_contents(__DIR__ . '/../tmp/package_debug.log', $logMsg, FILE_APPEND);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit; // Should already be handled in config.php
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'POST required (Method: ' . $_SERVER['REQUEST_METHOD'] . ')'], 405);
}
checkRateLimit(15);

$input = getInput();
$productName = sanitize($input['productName'] ?? '');
$features = $input['features'] ?? '';
$style = sanitize($input['style'] ?? 'marketplace');
$language = sanitize($input['language'] ?? 'uz');
$productImage = $input['productImage'] ?? null;
$slideIndex = (int)($input['slideIndex'] ?? 1);
$category = sanitize($input['category'] ?? '');

// Tanga tekshirish (faqat birinchi slaydda)
if ($slideIndex === 1) {
    $balanceInfo = requireBalance('infografika-paketi');
} else {
    // Keyingi slaydlar uchun faqat auth tekshirish
    $user = getAuthUser();
    if (!$user) {
        jsonResponse(['error' => 'Tizimga kiring', 'auth_required' => true], 401);
    }
    // Muhim: Balans yechilmasa ham, generatsiya lockini (limitni) tekshirish va qo'yish kerak
    checkGenerationLimit($user['id']);
    $balanceInfo = ['user' => $user, 'cost' => 0];
}

validateImageSize($productImage);

$featuresList = '';
if (!empty($features)) {
    $featuresList = implode(', ', array_filter(array_map('trim', explode("\n", (is_array($features) ? implode("\n", $features) : $features)))));
}
$langNames = ['ru' => 'Russian', 'uz' => 'Uzbek', 'en' => 'English'];
$targetLang = $langNames[$language] ?? 'Russian';

$config = getPrompts();
$slidePrompt = null;
$styleKey = strtolower($style);
if ($config && isset($config['paket'][$styleKey])) {
    $slidePrompts = $config['paket'][$styleKey];
    if (is_array($slidePrompts) && isset($slidePrompts[$slideIndex - 1])) {
        $slidePrompt = $slidePrompts[$slideIndex - 1];
    }
}

if (!$slidePrompt) {
    $fallbacks = [
        "MAIN PRODUCT CARD: Focus on visual appeal. Branding at top, product center, main feature highlighted.",
        "FEATURES SHOWCASE: Focus on 3-4 feature badges with icons. Clear descriptions: $featuresList.",
        "LIFESTYLE SCENE: Show the product in use/lifestyle setting. Emotional appeal.",
        "SPECIFICATIONS: Focus on dimensions or technical details. Professional layout.",
        "PRODUCT OVERVIEW: Show product from different angle with key specs — dimensions, weight, material composition, certifications. Clean professional layout.",
    ];
    $slidePrompt = $fallbacks[$slideIndex - 1] ?? $fallbacks[0];
}

// Replace placeholders in slide prompt
$featuresArray = array_filter(array_map('trim', explode(',', $featuresList)));
$firstFeature = $featuresArray[0] ?? '';

$slidePrompt = str_replace(
    ['{productName}', '{featuresList}', '{targetLang}', '{firstFeature}'],
    [$productName, $featuresList, $targetLang, $firstFeature],
    $slidePrompt
);

$suffix = $config['system']['paket_suffix'] ?? "\nProduct: {productName}.\nFeatures: {featuresList}.\nTranslate ALL text to {targetLang}.\nStyle: {style}.\nQUALITY: Ultra high resolution, 8K, crisp readable text, professional commercial design, consistent style across all slides. Aspect Ratio: {aspectRatio}.";
$suffix .= "\n\nSTOP WORDS — ABSOLUTELY FORBIDDEN (never render these on the image): 'buyurtma bering', 'xarid qiling', 'hoziroq oling', 'top tovar', 'yangi', 'NEW', 'hit', 'bestseller', 'Uzum marketdan', 'bepul yetkazish', star ratings (4.8, 4.9), 'X+ sotilgan', 'kafolat', 'sifat kafolati', 'yuqori sifat', 'premium', 'haqiqiy', 'original', '100% original', 'eng yaxshi', 'tavsiya etamiz', 'ishonch', 'sifatli', 'garantiya', 'chegirma', 'aksiya', any marketplace name (Uzum, Ozon, WB), any trust/quality buzzword. ONLY show REAL product specifications.";
$suffix = str_replace(
    ['{productName}', '{featuresList}', '{targetLang}', '{style}', '{aspectRatio}'],
    [$productName, $featuresList, $targetLang, $style, '3:4'],
    $suffix
);

$prompt = $slidePrompt . $suffix;

// Kategoriya bo'yicha dizayn qo'llash (salesRules + layout + bg DOIMO, kategoriya variant agar tanlansa)
$prompt = getCategoryDesignPrompt($category, $prompt);

$parts = [];
$processedImage = processImageInput($productImage);
if ($processedImage) {
    $parts[] = ['inline_data' => $processedImage];
    
    // Mahsulot o'zgartirmaslik qoidalari — KUCHAYTIRILGAN
    $faithfulness = "\n\n🚨🚨🚨 ABSOLUTE #1 PRIORITY — PRODUCT FAITHFULNESS (VIOLATION = TOTAL FAILURE):
1. PIXEL-PERFECT COPY: The product in the uploaded image is the REAL product. You MUST reproduce it EXACTLY — same shape, same color, same label text, same logo, same proportions, same cap/lid/packaging design. NOT SIMILAR — IDENTICAL. Copy it pixel-by-pixel.
2. ZERO MODIFICATIONS: Do NOT change the bottle shape. Do NOT change packaging color. Do NOT rewrite, translate, move, or remove ANY text on the product label. Do NOT add shadows, glows, or effects that alter how the product looks.
3. NO REIMAGINING: Do NOT create a 'better version' or 'artistic interpretation' of the product. Do NOT add elements (ribbons, stickers, badges) ON TOP of the product itself. The product is sacred — touch NOTHING.
4. PHOTO-REALISTIC: The product must appear as a HIGH-RESOLUTION STUDIO PHOTOGRAPH of the EXACT SAME physical item. Sharp edges, correct lighting, no distortion.
5. PLACEMENT ONLY: You may place the product into different compositions and backgrounds for each slide, but the product rendering itself MUST be 100% identical across all 5 slides and match the uploaded photo.
6. LANGUAGE RULE: ALL text on badges, descriptions, and titles MUST be in {$targetLang}. But text ON the product label stays EXACTLY as-is from the original image.
7. QUALITY: Ultra high resolution, 8K, crisp readable text. Aspect Ratio: 3:4.
🚨 IF THE PRODUCT LOOKS EVEN SLIGHTLY DIFFERENT FROM THE UPLOADED PHOTO, THE ENTIRE DESIGN IS REJECTED.";
    
    $parts[] = ['text' => $prompt . $faithfulness];
} else {
    $parts[] = ['text' => $prompt];
}

$result = callGeminiAPI($parts, '3:4');

// Foydalanuvchi ma'lumotlarini olish (Telegram uchun)
$authUser = $balanceInfo['user'];
$userName = ($authUser['name'] ?? 'Noma\'lum');
$userEmail = ($authUser['email'] ?? 'Noma\'lum');
$prevBalance = (int)($authUser['balance'] ?? 0) + (int)$balanceInfo['cost'];
$cost = (int)$balanceInfo['cost'];

if (empty($result['imageBase64'])) {
    if ($balanceInfo['cost'] > 0) {
        refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    }
    
    // Telegram ga xatolik xabari yuborish
    try {
        $errMsg = "❌ *Infografika Paketi XATOLIK!*\n";
        $errMsg .= "📦 *Slayd:* {$slideIndex}/5\n\n";
        $errMsg .= "👤 *Foydalanuvchi:* " . sanitize($userName) . "\n";
        $errMsg .= "📧 *Email:* " . sanitize($userEmail) . "\n";
        $errMsg .= "🛍 *Mahsulot:* " . sanitize($productName) . "\n";
        $errMsg .= "🎨 *Stil:* " . ucfirst($style) . " | 🌍 *Til:* " . $targetLang . "\n";
        $errMsg .= "⚠️ *Xatolik:* Rasm yaratilmadi (AI javob bermadi)\n";
        if ($cost > 0) {
            $errMsg .= "💰 *Balans:* {$prevBalance} → refund +{$cost} → {$prevBalance}\n";
        }
        $errMsg .= "\n🤖 _Tiba AI_";
        
        $token = getenv('TELEGRAM_BOT_TOKEN');
        $chatId = getenv('TELEGRAM_CHANNEL_ID');
        if ($token && $chatId) {
            $tgUrl = "https://api.telegram.org/bot{$token}/sendMessage";
            $ch = curl_init($tgUrl);
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query([
                    'chat_id' => $chatId,
                    'text' => $errMsg,
                    'parse_mode' => 'Markdown'
                ]),
                CURLOPT_RETURNTRANSFER => true,
            ] + getTelegramCurlOpts());
            curl_exec($ch);
            curl_close($ch);
        }
    } catch (Exception $e) {
        error_log("Telegram Error Notification: " . $e->getMessage());
    }
    
    jsonResponse(['error' => 'Rasm yaratilmadi. Qayta urinib ko\'ring.'], 500);
}

$imageUrl = saveImage($result['imageBase64'], $result['mimeType'], "paket-s$slideIndex");

// Original saqlash (Telegram uchun)
$originalPath = null;
if ($processedImage) {
    $originalPath = saveImage($processedImage['data'], $processedImage['mime_type'], 'original_paket');
}

// Tangalarni ayirish (faqat agar yangi rezervatsiya bo'lgan bo'lsa)
if ($balanceInfo['cost'] > 0) {
    $newBalance = deductBalance($balanceInfo['user']['id'], $balanceInfo['cost'], 'infografika-paketi');
} else {
    releaseGenerationLock($balanceInfo['user']['id']);
    $newBalance = (int)($balanceInfo['user']['balance'] ?? 0);
}

// Telegramga yuborish — to'liq ma'lumotlar bilan
$msg = "📦 *Infografika Paketi - Slayd {$slideIndex}/5*\n\n";
$msg .= "👤 *Foydalanuvchi:* " . sanitize($userName) . "\n";
$msg .= "📧 *Email:* " . sanitize($userEmail) . "\n";
$msg .= "🛍 *Mahsulot:* " . sanitize($productName) . "\n";
$msg .= "🎨 *Stil:* " . ucfirst($style) . " | 🌍 *Til:* " . $targetLang . "\n";
if ($cost > 0) {
    $msg .= "💰 *Balans:* {$prevBalance} → -{$cost} → " . (int)$newBalance . "\n";
}
$msg .= "\n🤖 _Tiba AI_";

$telegramMsgId = null;
try {
    $imagePaths = [];
    if ($originalPath) $imagePaths[] = $originalPath;
    $imagePaths[] = $imageUrl;

    $tgResponseRaw = sendMediaGroupToTelegram($msg, $imagePaths, true);
    $tgResponse = json_decode($tgResponseRaw, true);
    if (isset($tgResponse['ok']) && $tgResponse['ok'] && isset($tgResponse['result'][0]['message_id'])) {
        $telegramMsgId = $tgResponse['result'][0]['message_id'];
    }
} catch (Exception $e) {
    error_log("Telegram Error: " . $e->getMessage());
}

// Save to History
$user = getAuthUser();
if ($user) {
    try {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO generations (user_id, image_path, prompt_data, telegram_msg_id) VALUES (?, ?, ?, ?)");
        $promptData = json_encode([
            'product' => $productName,
            'features' => $features,
            'style' => $style,
            'lang' => $language,
            'type' => 'paket_slide_' . $slideIndex
        ], JSON_UNESCAPED_UNICODE);
        $stmt->execute([$user['id'], $imageUrl, $promptData, $telegramMsgId]);
    } catch (Exception $e) {
        error_log("DB History Save Error: " . $e->getMessage());
    }
}

jsonResponse(['success' => true, 'imageUrl' => $imageUrl, 'balance' => $newBalance]);

