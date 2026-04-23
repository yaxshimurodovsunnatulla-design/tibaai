<?php
/**
 * Tiba AI — Noldan Yaratish (Text-to-Image) API
 * Matnli buyruq orqali rasm yaratish
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Faqat POST so\'rovlar qabul qilinadi'], 405);
}
checkRateLimit(10);

// Tanga tekshirish
$balanceInfo = requireBalance('noldan-yaratish');

$input = getInput();

$prompt = sanitize($input['prompt'] ?? '');
$style = sanitize($input['style'] ?? 'photorealistic');
$aspectRatio = sanitize($input['aspectRatio'] ?? '1:1');
$language = sanitize($input['language'] ?? 'uz');

if (empty($prompt) || strlen($prompt) < 5) {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Rasm tavsifini kiriting (kamida 5 ta belgi)'], 400);
}

$langNames = [
    'ru' => 'Russian',
    'uz' => 'Uzbek',
    'en' => 'English',
    'tr' => 'Turkish',
    'kz' => 'Kazakh',
];
$targetLang = $langNames[$language] ?? 'Uzbek';

// Build the complete prompt for Gemini
$finalPrompt = $prompt;
$finalPrompt .= "\n\nCRITICAL RULES:";
$finalPrompt .= "\n1. Generate this image with ultra-high quality, 8K resolution, professional commercial output.";
$finalPrompt .= "\n2. All text in the image MUST be in {$targetLang} language.";
$finalPrompt .= "\n3. Aspect Ratio: {$aspectRatio}.";
$finalPrompt .= "\n4. The result must be visually stunning, magazine-quality, with perfect composition.";
$finalPrompt .= "\n5. Pay attention to lighting, shadows, reflections, and fine details.";

// Gemini API uchun parts tayyorlash
$parts = [];
$parts[] = ['text' => $finalPrompt];

// Gemini'ga so'rov
$result = callGeminiAPI($parts, $aspectRatio);

if (empty($result['imageBase64'])) {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Rasm yaratilmadi. Qayta urinib ko\'ring.'], 500);
}

// Rasmni saqlash
$imageUrl = saveImage($result['imageBase64'], $result['mimeType'], 'noldan');

// Telegram
$telegramMsgId = null;
$msg = "🎨 *Noldan Yaratish*\n\n";
$msg .= "📝 *Prompt:* " . sanitize($prompt) . "\n";
$msg .= "🖼️ *Stil:* {$style}\n";
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
            'product' => 'Noldan rasm',
            'style' => $style,
            'prompt' => $prompt,
            'lang' => $language,
            'type' => 'text-to-image'
        ], JSON_UNESCAPED_UNICODE);
        $stmt->execute([$user['id'], $imageUrl, $promptData, $telegramMsgId]);
    } catch (Exception $e) { error_log($e->getMessage()); }
}

// Tangalarni ayirish
$newBalance = deductBalance($balanceInfo['user']['id'], $balanceInfo['cost'], 'noldan-yaratish');

jsonResponse(['success' => true, 'imageUrl' => $imageUrl, 'balance' => $newBalance]);
