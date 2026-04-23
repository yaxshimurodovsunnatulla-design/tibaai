<?php
/**
 * Tiba AI — Infografika yaratish (Gemini API)
 * 1:1 Mirror from tibaai.uz
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Faqat POST so\'rovlar qabul qilinadi'], 405);
}
checkRateLimit(10);

// Tanga tekshirish
$balanceInfo = requireBalance('infografika');

$input = getInput();

$productName = sanitize($input['productName'] ?? '');
$features = $input['features'] ?? '';
$style = sanitize($input['style'] ?? 'marketplace');
$language = sanitize($input['language'] ?? 'ru');
$productImage = $input['productImage'] ?? null;
$category = sanitize($input['category'] ?? '');

validateImageSize($productImage);

// Agar xususiyatlar massiv bo'lib kelsa (bizning PHP frontenddan), uni stringga o'tkazamiz
if (is_array($features)) {
    $features = implode("\n", $features);
}

if (empty($productName) && empty($productImage)) {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Mahsulot nomi kiriting yoki rasm yuklang'], 400);
}

// Xususiyatlarni tayyorlash
$featuresList = implode(', ', array_filter(array_map('trim', explode("\n", $features))));

if (empty($productName)) {
    $productName = "this product";
}
if (empty($featuresList)) {
    $featuresList = "its premium features and high quality";
}

$langNames = [
    'ru' => 'Russian',
    'uz' => 'Uzbek',
    'en' => 'English',
    'tr' => 'Turkish',
    'kz' => 'Kazakh',
];
$targetLang = $langNames[$language] ?? 'Russian';

// Promptlarni bazadan olish
$config = getPrompts();
$infoConfig = $config['infografika'] ?? [];
$styleTemplate = $infoConfig[strtolower($style)] ?? ($infoConfig['marketplace'] ?? '');

if (empty($styleTemplate)) {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Prompt topilmadi: ' . $style], 500);
}

// Template placeholder'larni almashtirish
$prompt = str_replace(
    ['{productName}', '{featuresList}', '{targetLang}'],
    [$productName, $featuresList, $targetLang],
    $styleTemplate
);

// Kategoriyaga mos random dizayn promptini qo'shish
require_once __DIR__ . '/category-prompts.php';
$prompt = getCategoryDesignPrompt($category, $prompt);

$aspectRatios = [
    'ozon' => '3:4',
    'instagram' => '1:1',
    'minimal' => '3:4',
    'uzum' => '3:4',
    'yandex' => '3:4',
    'wb' => '3:4',
];
$aspectRatio = $aspectRatios[strtolower($style)] ?? '3:4';

$imageSizes = [
    'uzum' => '1080x1440',
    'wb' => '900x1200',
    'ozon' => '900x1200',
    'yandex' => '900x1200',
    'instagram' => '1080x1080',
    'minimal' => '1080x1440',
    'marketplace' => '1080x1440',
];
$imageSize = $imageSizes[strtolower($style)] ?? '1080x1440';

// Gemini API uchun parts tayyorlash
$parts = [];
$processedImage = processImageInput($productImage);

if ($processedImage) {
    $parts[] = ['inline_data' => $processedImage];
    
    $suffix = $config['system']['infografika_suffix'] ?? "\n\n🚨🚨🚨 ABSOLUTE #1 PRIORITY — PRODUCT FAITHFULNESS (VIOLATION = TOTAL FAILURE):
1. PIXEL-PERFECT COPY: The product in the uploaded image is the REAL product. You MUST reproduce it EXACTLY — same shape, same color, same label text, same logo, same proportions, same cap/lid/packaging design. NOT SIMILAR — IDENTICAL. Copy it pixel-by-pixel.
2. ZERO MODIFICATIONS: Do NOT change the bottle shape. Do NOT change packaging color. Do NOT rewrite, translate, move, or remove ANY text on the product label. Do NOT add shadows, glows, or effects that alter how the product looks.
3. NO REIMAGINING: Do NOT create a 'better version' or 'artistic interpretation' of the product. Do NOT add elements (ribbons, stickers, badges) ON TOP of the product itself. The product is sacred — touch NOTHING.
4. PHOTO-REALISTIC: The product must appear as a HIGH-RESOLUTION STUDIO PHOTOGRAPH of the EXACT SAME physical item. Sharp edges, correct lighting, no distortion.
5. PLACEMENT ONLY: Place the product into the infographic design layout, but the product rendering itself MUST be 100% unchanged and identical to the uploaded image.
6. LANGUAGE RULE: ALL text on badges, descriptions, and titles MUST be in {targetLang}. But text ON the product label stays EXACTLY as-is from the original image.
7. QUALITY: Ultra high resolution, 8K, crisp readable text. Image dimensions: {imageSize}. Aspect Ratio: {aspectRatio}.
🚨 IF THE PRODUCT LOOKS EVEN SLIGHTLY DIFFERENT FROM THE UPLOADED PHOTO, THE ENTIRE DESIGN IS REJECTED.";
    $suffix = str_replace(['{targetLang}', '{aspectRatio}', '{imageSize}'], [$targetLang, $aspectRatio, $imageSize], $suffix);
    
    $parts[] = ['text' => $prompt . $suffix];
} else {
    $parts[] = ['text' => $prompt];
}

// Gemini'ga so'rov
$result = callGeminiAPI($parts, $aspectRatio);

// Foydalanuvchi ma'lumotlarini olish (Telegram uchun)
$authUser = $balanceInfo['user'];
$userName = ($authUser['name'] ?? 'Noma\'lum');
$userEmail = ($authUser['email'] ?? 'Noma\'lum');
$prevBalance = (int)($authUser['balance'] ?? 0) + (int)$balanceInfo['cost'];
$cost = (int)$balanceInfo['cost'];

if (empty($result['imageBase64'])) {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    
    // Telegram ga xatolik xabari yuborish
    try {
        $errMsg = "❌ *Infografika XATOLIK!*\n\n";
        $errMsg .= "👤 *Foydalanuvchi:* " . sanitize($userName) . "\n";
        $errMsg .= "📧 *Email:* " . sanitize($userEmail) . "\n";
        $errMsg .= "🛍 *Mahsulot:* " . sanitize($productName) . "\n";
        $errMsg .= "🎨 *Stil:* " . ucfirst($style) . " | 🌍 *Til:* " . $targetLang . "\n";
        $errMsg .= "⚠️ *Xatolik:* Rasm yaratilmadi (AI javob bermadi)\n";
        $errMsg .= "💰 *Balans:* {$prevBalance} → refund +{$cost} → {$prevBalance}\n";
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

// Rasmni aniq o'lchamga resize qilish (GD)
$targetW = (int) explode('x', $imageSize)[0]; // 1080
$targetH = (int) explode('x', $imageSize)[1]; // 1440
$rawImage = base64_decode($result['imageBase64']);
$srcImg = @imagecreatefromstring($rawImage);

if ($srcImg) {
    $srcW = imagesx($srcImg);
    $srcH = imagesy($srcImg);
    
    // Agar o'lcham mos kelmasa — resize qilish
    if ($srcW !== $targetW || $srcH !== $targetH) {
        $dstImg = imagecreatetruecolor($targetW, $targetH);
        
        // Shaffoflikni saqlash (PNG uchun)
        imagealphablending($dstImg, false);
        imagesavealpha($dstImg, true);
        
        // Sifatli resize (aspect ratio saqlangan holda crop)
        $srcRatio = $srcW / $srcH;
        $dstRatio = $targetW / $targetH;
        
        if ($srcRatio > $dstRatio) {
            // Kengroq — chapdan-o'ngdan qirqish
            $cropW = (int)($srcH * $dstRatio);
            $cropH = $srcH;
            $cropX = (int)(($srcW - $cropW) / 2);
            $cropY = 0;
        } else {
            // Balandroq — tepadan-pastdan qirqish
            $cropW = $srcW;
            $cropH = (int)($srcW / $dstRatio);
            $cropX = 0;
            $cropY = (int)(($srcH - $cropH) / 2);
        }
        
        imagecopyresampled($dstImg, $srcImg, 0, 0, $cropX, $cropY, $targetW, $targetH, $cropW, $cropH);
        
        // Yangi rasmni base64 ga aylantirish
        ob_start();
        imagepng($dstImg, null, 6); // sifat 6 (0-9, 9 eng kichik)
        $resizedData = ob_get_clean();
        
        $result['imageBase64'] = base64_encode($resizedData);
        $result['mimeType'] = 'image/png';
        
        imagedestroy($dstImg);
    }
    imagedestroy($srcImg);
}

// Rasmni saqlash
$imageUrl = saveImage($result['imageBase64'], $result['mimeType'], 'infographic');

// Originalni saqlash (Telegram uchun)
$originalPath = null;
if ($processedImage) {
    $originalPath = saveImage($processedImage['data'], $processedImage['mime_type'], 'original');
}

// Tangalarni ayirish
$newBalance = deductBalance($balanceInfo['user']['id'], $balanceInfo['cost'], 'infografika');

// Telegramga yuborish — to'liq ma'lumotlar bilan
$msg = "✅ *Yangi Infografika Yaratildi!*\n\n";
$msg .= "👤 *Foydalanuvchi:* " . sanitize($userName) . "\n";
$msg .= "📧 *Email:* " . sanitize($userEmail) . "\n";
$msg .= "🛍 *Mahsulot:* " . sanitize($productName) . "\n";
$msg .= "🎨 *Stil:* " . ucfirst($style) . " | 🌍 *Til:* " . $targetLang . "\n";
$msg .= "💰 *Balans:* {$prevBalance} → -{$cost} → " . (int)$newBalance . "\n";
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

// Baza (Tarix) ga saqlash
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
            'type' => 'infographic'
        ], JSON_UNESCAPED_UNICODE);
        $stmt->execute([$user['id'], $imageUrl, $promptData, $telegramMsgId]);
    } catch (Exception $e) {
        error_log("DB History Save Error: " . $e->getMessage());
    }
}

jsonResponse(['success' => true, 'imageUrl' => $imageUrl, 'balance' => $newBalance]);

