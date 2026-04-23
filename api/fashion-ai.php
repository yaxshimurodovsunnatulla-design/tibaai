<?php
/**
 * Tiba AI — Fashion AI (Virtual Try-On) API
 * Kiyimni virtual modelga kiygizish
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Faqat POST so\'rovlar qabul qilinadi'], 405);
}
checkRateLimit(10);

// Tanga tekshirish
$balanceInfo = requireBalance('fashion-ai');

$input = getInput();

// Inputs
$clothingImages = $input['clothingImages'] ?? [];
$modelImageInput = $input['modelImage'] ?? null;
$modelType = sanitize($input['modelType'] ?? 'woman');
$style = sanitize($input['style'] ?? 'editorial');
$pose = sanitize($input['pose'] ?? 'standing');
$aspectRatio = sanitize($input['aspectRatio'] ?? '3:4');
$customPrompt = sanitize($input['customPrompt'] ?? '');

// Validate
if ($modelImageInput) validateImageSize($modelImageInput);
foreach ($clothingImages as $img) validateImageSize($img);

if (empty($clothingImages)) {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Kamida bitta kiyim rasmini yuklang'], 400);
}

// Build the prompt
$prompt = "VIRTUAL TRY-ON / FASHION PHOTOGRAPHY TASK:\n\n";

if ($modelType === 'custom' && $modelImageInput) {
    $prompt .= "I am providing an image of a SPECIFIC MODEL and image(s) of CLOTHING items.\n";
    $prompt .= "Your task is to put the provided clothing item(s) ON THE PROVIDED MODEL.\n";
    $prompt .= "Maintain the model's identity, face, and features perfectly.\n\n";
} else {
    $modelDescs = [
        'woman' => 'a beautiful young woman (age 22-28), professional fashion model, attractive face, slim figure, natural makeup, confident look',
        'man' => 'a handsome young man (age 24-30), professional fashion model, well-groomed, athletic build, confident pose, clean look',
    ];
    $modelDesc = $modelDescs[$modelType] ?? $modelDescs['woman'];
    $prompt .= "MODEL: {$modelDesc}.\n\n";
}

// Style and Pose
$styleDescs = [
    'editorial' => 'high-fashion editorial photography, Vogue magazine style, dramatic lighting, artistic composition, luxury fashion brand campaign mood, soft bokeh background',
    'street' => 'modern street fashion photography, urban cityscape background, natural daylight, candid lifestyle look, fashion blogger aesthetic, vibrant atmosphere',
    'studio' => 'professional studio photography, clean white/light gray seamless background, perfect studio lighting, e-commerce product photography standard, crisp and sharp',
];
$styleDesc = $styleDescs[$style] ?? $styleDescs['editorial'];

$poseDescs = [
    'standing' => 'standing upright in a confident model pose, full-body visible, slight angle to camera, one hand relaxed',
    'walking' => 'walking naturally towards camera, mid-stride, dynamic movement, hair slightly flowing, natural motion',
    'sitting' => 'sitting elegantly on a modern chair or ledge, relaxed but fashionable pose, legs crossed or angled',
    'dynamic' => 'dynamic action pose, turning or looking over shoulder, wind effect on clothing, energy and movement',
];
$poseDesc = $poseDescs[$pose] ?? $poseDescs['standing'];

$prompt .= "POSE: {$poseDesc}.\n\n";
$prompt .= "PHOTOGRAPHY STYLE: {$styleDesc}.\n\n";

if (!empty($customPrompt)) {
    $prompt .= "ADDITIONAL INSTRUCTIONS: {$customPrompt}\n\n";
}

$prompt .= "CRITICAL RULES:\n";
$prompt .= "1. The clothing item from the provided image MUST be perfectly replicated on the model.\n";
$prompt .= "2. The garment must FIT the model naturally.\n";
$prompt .= "3. Professional fashion photography quality — 8K ultra high resolution, perfect lighting.\n";
$prompt .= "4. Aspect Ratio: {$aspectRatio}.\n";

// Build parts
$parts = [];

// Model image (if custom)
if ($modelType === 'custom' && $modelImageInput) {
    $processedModel = processImageInput($modelImageInput);
    if ($processedModel) {
        $parts[] = ['inline_data' => $processedModel];
    }
}

// Clothing images
foreach ($clothingImages as $img) {
    $processedImage = processImageInput($img);
    if ($processedImage) {
        $parts[] = ['inline_data' => $processedImage];
    }
}

if (empty($parts)) {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Rasmlar yuklanmagan yoki noto\'g\'ri formatda'], 400);
}

// Prompt
$parts[] = ['text' => $prompt];

// Call Gemini API
$result = callGeminiAPI($parts, $aspectRatio);

if (empty($result['imageBase64'])) {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Rasm yaratilmadi. Qayta urinib ko\'ring.'], 500);
}

// Save
$imageUrl = saveImage($result['imageBase64'], $result['mimeType'], 'fashion');

// Original kiyim rasmini saqlash (Telegram uchun)
$originalClothingPath = null;
if (!empty($clothingImages[0])) {
    $proc = processImageInput($clothingImages[0]);
    if ($proc) {
        $originalClothingPath = saveImage($proc['data'], $proc['mime_type'], 'original_clothing');
    }
}

// Telegram
$telegramMsgId = null;
$msg = "👗 *Fashion AI*\n\n";
$msg .= "👤 *Model:* " . ucfirst($modelType) . "\n";
$msg .= "📸 *Stil:* " . ucfirst($style) . "\n";
$msg .= "\n🤖 _Tiba AI_";

try {
    $imagePaths = [];
    if ($originalClothingPath) $imagePaths[] = $originalClothingPath;
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
            'product' => 'Fashion AI (' . ucfirst($modelType) . ')',
            'style' => $style,
            'pose' => $pose,
            'type' => 'fashion-ai'
        ], JSON_UNESCAPED_UNICODE);
        $stmt->execute([$user['id'], $imageUrl, $promptData, $telegramMsgId]);
    } catch (Exception $e) { error_log($e->getMessage()); }
}

// Tangalarni ayirish
$newBalance = deductBalance($balanceInfo['user']['id'], $balanceInfo['cost'], 'fashion-ai');

jsonResponse(['success' => true, 'imageUrl' => $imageUrl, 'balance' => $newBalance]);
