<?php
/**
 * Tiba AI — Foto Tahrir (Fon almashtirish)
 * 1:1 Mirror from tibaai.uz
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'POST required'], 405);
}
checkRateLimit(10);

// Tanga tekshirish
$balanceInfo = requireBalance('foto-tahrir');

$input = getInput();
$productImage = $input['productImage'] ?? null;
$styleId = sanitize($input['styleId'] ?? 'minimalist');
$customPrompt = sanitize($input['customPrompt'] ?? '');
$aspectRatio = sanitize($input['aspectRatio'] ?? '3:4');

validateImageSize($productImage);

if (empty($productImage)) {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Rasm yuklang'], 400);
}

// Promptlarni olish
$config = getPrompts();
$fotoConfig = $config['foto-tahrir'] ?? [];
$basePrompt = $fotoConfig[strtolower($styleId)] ?? "Create a professional product photograph with studio lighting.";

if (!empty($customPrompt)) {
    $basePrompt .= " Also follow these instructions: $customPrompt";
}

$parts = [];
$processedImage = processImageInput($productImage);

if ($processedImage) {
    $parts[] = ['inline_data' => $processedImage];
    $suffix = $config['system']['foto_tahrir_suffix'] ?? "\n\nIMPORTANT: Recreate the product from the image EXACTLY — preserve its shape, labels, text, colors, and every detail. ONLY change the background and environment to match the style description above. Output must be ultra high resolution, 8K quality, photorealistic with razor-sharp product details, perfect studio lighting, and realistic shadows. Aspect Ratio: {aspectRatio}.";
    $suffix = str_replace('{aspectRatio}', $aspectRatio, $suffix);
    $parts[] = ['text' => $basePrompt . $suffix];
} else {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Rasm yuklanmagan yoki havolasi noto\'g\'ri'], 400);
}

// Call AI
$result = callGeminiAPI($parts, $aspectRatio);

if (empty($result['imageBase64'])) {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'AI failed to generate image'], 500);
}

// Save
$imageUrl = saveImage($result['imageBase64'], $result['mimeType'], 'foto');

// Originalni ham saqlash (Telegram uchun)
$originalPath = null;
if ($processedImage) {
    $originalPath = saveImage($processedImage['data'], $processedImage['mime_type'], 'original');
}

// Telegramga yuborish
$telegramMsgId = null;
$msg = "✨ *Foto Tahrir*\n\n";
$msg .= "🎨 *Stil:* " . ucfirst($styleId) . "\n";
if ($customPrompt) $msg .= "📝 *Prompt:* " . sanitize($customPrompt) . "\n";
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
} catch (Exception $e) {
    error_log("Telegram Error: " . $e->getMessage());
}

// History
$user = getAuthUser();
if ($user) {
    try {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO generations (user_id, image_path, prompt_data, telegram_msg_id) VALUES (?, ?, ?, ?)");
        $promptData = json_encode([
            'product' => 'Foto Tahrir',
            'style' => $styleId,
            'prompt' => $customPrompt,
            'type' => 'foto-tahrir'
        ], JSON_UNESCAPED_UNICODE);
        $stmt->execute([$user['id'], $imageUrl, $promptData, $telegramMsgId]);
    } catch (Exception $e) {
        error_log("DB History Save Error: " . $e->getMessage());
    }
}

// Tangalarni ayirish
$newBalance = deductBalance($balanceInfo['user']['id'], $balanceInfo['cost'], 'foto-tahrir');

jsonResponse(['success' => true, 'imageUrl' => $imageUrl, 'balance' => $newBalance]);
