<?php
/**
 * Tiba AI — Fotosesiya PRO API
 * Har bir kadr uchun alohida chaqiriladi (8 marta)
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Faqat POST so\'rovlar qabul qilinadi'], 405);
}
checkRateLimit(10);

$input = getInput();
$shotIndex = (int)($input['shotIndex'] ?? 0);

// Tanga tekshirish (faqat birinchi kadrda)
if ($shotIndex === 0) {
    $balanceInfo = requireBalance('fotosesiya-pro');
} else {
    // Keyingi kadrlar uchun faqat auth tekshirish
    $user = getAuthUser();
    if (!$user) {
        jsonResponse(['error' => 'Tizimga kiring', 'auth_required' => true], 401);
    }
    $balanceInfo = ['user' => $user, 'cost' => 0];
}

$productImages = $input['productImages'] ?? [];
if (!is_array($productImages) && !empty($input['productImage'])) {
    $productImages = [$input['productImage']];
}
$shotPrompt = sanitize($input['shotPrompt'] ?? '');
$shotName = sanitize($input['shotName'] ?? 'Photo');
$category = sanitize($input['category'] ?? 'general');
$aspectRatio = sanitize($input['aspectRatio'] ?? '3:4');

if (empty($productImages)) {
    if ($balanceInfo['cost'] > 0) refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Kamida bitta mahsulot rasmini yuklang'], 400);
}

foreach ($productImages as $img) {
    validateImageSize($img);
}
if (empty($shotPrompt)) {
    if ($balanceInfo['cost'] > 0) refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Shot tavsifi kerak'], 400);
}

// Category context
$categoryDescs = [
    'general' => 'a product',
    'electronics' => 'an electronics/tech product — emphasize sleek design, LED indicators, modern technology feel',
    'fashion' => 'a fashion/clothing item — emphasize texture, fabric quality, style, and wearability',
    'beauty' => 'a beauty/cosmetics product — emphasize elegance, skin-care luxury, soft feminine feel',
    'food' => 'a food/beverage product — emphasize freshness, appetite appeal, delicious look',
    'furniture' => 'a furniture/home item — emphasize comfort, quality materials, room integration',
    'sports' => 'a sports/fitness product — emphasize energy, action, performance, durability',
    'toys' => 'a children\'s/toy product — emphasize fun, bright colors, playfulness, safety',
];
$categoryDesc = $categoryDescs[$category] ?? $categoryDescs['general'];

// Build the prompt
$prompt = "PROFESSIONAL PRODUCT PHOTOGRAPHY TASK:\n\n";
$prompt .= "I am providing an image of {$categoryDesc}. Create a professional commercial photograph with the following specific scene/setting:\n\n";
$prompt .= "SCENE: {$shotPrompt}\n\n";
$prompt .= "SHOT NAME: {$shotName}\n\n";
$prompt .= "CRITICAL RULES:\n";
$prompt .= "1. PRESERVE the product EXACTLY as shown — same shape, colors, labels, text, logos, and every detail.\n";
$prompt .= "2. ONLY change the background, environment, lighting, and composition.\n";
$prompt .= "3. The product must be the HERO/FOCUS of the photograph — prominently placed, well-lit, sharp.\n";
$prompt .= "4. Ultra high quality: 8K resolution, professional commercial photography standard.\n";
$prompt .= "5. Realistic lighting with proper shadows, reflections, and depth of field.\n";
$prompt .= "6. Aspect Ratio: {$aspectRatio}.\n";
$prompt .= "7. This is for marketplace/e-commerce advertising — it must look like a real professional photoshoot.\n";
$prompt .= "8. Make the composition unique and visually striking — this is a premium service.\n";

// Build parts
$parts = [];

// Product images
$hasImage = false;
foreach ($productImages as $img) {
    $processedImage = processImageInput($img);
    if ($processedImage) {
        $parts[] = ['inline_data' => $processedImage];
        $hasImage = true;
    }
}

if (!$hasImage) {
    if ($balanceInfo['cost'] > 0) refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Mahsulot rasmlari yuklanmagan yoki havolalar noto\'g\'ri'], 400);
}

// Prompt
$parts[] = ['text' => $prompt];

// Call Gemini API
$result = callGeminiAPI($parts, $aspectRatio);

if (empty($result['imageBase64'])) {
    if ($balanceInfo['cost'] > 0) refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Rasm yaratilmadi. Qayta urinib ko\'ring.'], 500);
}

// Save
$imageUrl = saveImage($result['imageBase64'], $result['mimeType'], 'photoshoot');

// Original rasm (Telegram uchun) - Birinchisini olamiz
$originalPath = null;
if (!empty($productImages[0])) {
    $proc = processImageInput($productImages[0]);
    if ($proc) {
        $originalPath = saveImage($proc['data'], $proc['mime_type'], 'original_shot');
    }
}

// Telegram
$telegramMsgId = null;
$msg = "📸 *Fotosesiya PRO*\n\n";
$msg .= "📂 *Kategoriya:* " . ucfirst($category) . "\n";
$msg .= "🎬 *Shot:* " . sanitize($shotName) . "\n";
$msg .= "\n🤖 _Tiba AI_";

try {
    $imagePaths = [];
    if ($originalPath) $imagePaths[] = $originalPath;
    $imagePaths[] = $imageUrl;
    
    $tgResponseRaw = sendMediaGroupToTelegram($msg, $imagePaths, true);
    $tgResponse = json_decode($tgResponseRaw, true);
    if (isset($tgResponse['ok']) && $tgResponse['ok'] && isset($tgResponse['result'][0]['message_id'])) {
        $telegramMsgId = $tgResponse['result'][0]['message_id'];
    }
} catch (Exception $e) { error_log($e->getMessage()); }

// History
$user = getAuthUser();
if ($user) {
    try {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO generations (user_id, image_path, prompt_data, telegram_msg_id) VALUES (?, ?, ?, ?)");
        $promptData = json_encode([
            'product' => 'Fotosesiya (' . ucfirst($category) . ')',
            'style' => $shotName,
            'details' => $shotPrompt,
            'type' => 'photoshoot-pro'
        ], JSON_UNESCAPED_UNICODE);
        $stmt->execute([$user['id'], $imageUrl, $promptData, $telegramMsgId]);
    } catch (Exception $e) { error_log($e->getMessage()); }
}

// Tangalarni ayirish (faqat birinchi kadrda)
if ($balanceInfo['cost'] > 0) {
    $newBalance = deductBalance($balanceInfo['user']['id'], $balanceInfo['cost'], 'fotosesiya-pro');
} else {
    $newBalance = (int)($balanceInfo['user']['balance'] ?? 0);
}

jsonResponse(['success' => true, 'imageUrl' => $imageUrl, 'balance' => $newBalance]);
