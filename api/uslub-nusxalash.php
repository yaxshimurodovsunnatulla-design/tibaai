<?php
/**
 * Tiba AI — Uslub Nusxalash (Style Transfer) API
 * Namuna rasm uslubini mahsulotga o'tkazish
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Faqat POST so\'rovlar qabul qilinadi'], 405);
}
checkRateLimit(10);

// Tanga tekshirish
$balanceInfo = requireBalance('uslub-nusxalash');

$input = getInput();

$productImage = $input['productImage'] ?? null;
$styleImage = $input['styleImage'] ?? null;
$aspectRatio = sanitize($input['aspectRatio'] ?? '3:4');
$strength = sanitize($input['strength'] ?? '2');
$customPrompt = sanitize($input['customPrompt'] ?? '');

validateImageSize($productImage);
validateImageSize($styleImage);

if (empty($productImage)) {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Mahsulot rasmini yuklang'], 400);
}
if (empty($styleImage)) {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Uslub namunasi rasmini yuklang'], 400);
}

// Strength descriptions
$strengthDescs = [
    '1' => 'Subtly apply the style — keep most of the product\'s original look, only lightly influenced by the reference style.',
    '2' => 'Apply a balanced style transfer — clearly adopt the reference style while preserving the product\'s key features and identity.',
    '3' => 'Strongly apply the style — heavily transform the product image to match the reference style\'s color palette, texture, lighting, and overall mood.',
];
$strengthDesc = $strengthDescs[$strength] ?? $strengthDescs['2'];

// Build the prompt
$prompt = "STYLE TRANSFER TASK:\n";
$prompt .= "I am providing TWO images:\n";
$prompt .= "1. FIRST IMAGE: The PRODUCT image — this is the subject that must be preserved.\n";
$prompt .= "2. SECOND IMAGE: The STYLE REFERENCE — copy the visual style, color palette, lighting, composition, background treatment, and artistic mood from this image.\n\n";
$prompt .= "INSTRUCTIONS:\n";
$prompt .= "- Recreate the product from Image 1, but apply the visual style, aesthetic, and feel of Image 2.\n";
$prompt .= "- {$strengthDesc}\n";
$prompt .= "- The product's shape, form, and identifying features must remain recognizable.\n";
$prompt .= "- Match the reference image's: color grading, lighting direction, background style, texture treatment, and overall mood.\n";

if (!empty($customPrompt)) {
    $prompt .= "- Additional instructions: {$customPrompt}\n";
}

$prompt .= "\nCRITICAL RULES:\n";
$prompt .= "1. The PRODUCT from Image 1 must be the main subject — preserve its identity.\n";
$prompt .= "2. The STYLE from Image 2 must be clearly applied — the result should look like it belongs in the same visual series as Image 2.\n";
$prompt .= "3. Output must be ultra high quality, 8K resolution, professional commercial photography.\n";
$prompt .= "4. Aspect Ratio: {$aspectRatio}.\n";

// Build parts — send both images, then prompt text
$parts = [];

// Product image (first)
$processedProduct = processImageInput($productImage);
if ($processedProduct) {
    $parts[] = ['inline_data' => $processedProduct];
} else {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Mahsulot rasmi yuklanmagan yoki havolasi noto\'g\'ri'], 400);
}

// Style reference image (second)
$processedStyle = processImageInput($styleImage);
if ($processedStyle) {
    $parts[] = ['inline_data' => $processedStyle];
} else {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Uslub namunasi rasmi yuklanmagan yoki havolasi noto\'g\'ri'], 400);
}

// Prompt text (last)
$parts[] = ['text' => $prompt];

// Call Gemini API
$result = callGeminiAPI($parts, $aspectRatio);

if (empty($result['imageBase64'])) {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Rasm yaratilmadi. Qayta urinib ko\'ring.'], 500);
}

// Save the result
$imageUrl = saveImage($result['imageBase64'], $result['mimeType'], 'style-transfer');

// Manbalarni ham saqlash (Telegram uchun)
$originalProductPath = null;
if ($processedProduct) {
    $originalProductPath = saveImage($processedProduct['data'], $processedProduct['mime_type'], 'original_source');
}
$originalStylePath = null;
if ($processedStyle) {
    $originalStylePath = saveImage($processedStyle['data'], $processedStyle['mime_type'], 'original_style');
}

// Telegram
$telegramMsgId = null;
$msg = "🎭 *Uslub Nusxalash*\n\n";
$msg .= "💪 *Kuch:* {$strength}\n";
if ($customPrompt) $msg .= "📝 *Qo'shimcha:* " . sanitize($customPrompt) . "\n";
$msg .= "\n🤖 _Tiba AI_";

try {
    $imagePaths = [];
    if ($originalProductPath) $imagePaths[] = $originalProductPath;
    if ($originalStylePath) $imagePaths[] = $originalStylePath;
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
            'product' => 'Uslub Nusxalash',
            'strength' => $strength,
            'prompt' => $customPrompt,
            'type' => 'style-transfer'
        ], JSON_UNESCAPED_UNICODE);
        $stmt->execute([$user['id'], $imageUrl, $promptData, $telegramMsgId]);
    } catch (Exception $e) { error_log($e->getMessage()); }
}

// Tangalarni ayirish
$newBalance = deductBalance($balanceInfo['user']['id'], $balanceInfo['cost'], 'uslub-nusxalash');

jsonResponse(['success' => true, 'imageUrl' => $imageUrl, 'balance' => $newBalance]);
