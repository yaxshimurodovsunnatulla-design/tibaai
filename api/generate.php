<?php
/**
 * Tiba AI — Infografika yaratish (Gemini API)
 */

// Stray output uchun early buffer (config.php dan oldin)
if (!ob_get_level()) ob_start();
ini_set('memory_limit', '512M');

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
    
    $suffix = $config['system']['infografika_suffix'] ?? "\n\n🔒 FINAL CHECKS:\n1. Product IDENTICAL to uploaded photo.\n2. ZERO text touches the product.\n3. No forbidden words.\n4. Only provided features shown.\n5. All text in {targetLang}.\n6. Aspect ratio: {aspectRatio}.";
    $suffix = str_replace(['{targetLang}', '{aspectRatio}', '{imageSize}'], [$targetLang, $aspectRatio, $imageSize], $suffix);

    // Prompt juda uzun bo'lsa qisqartirish (token limit xatosini oldini olish)
    if (strlen($prompt) > 4800) {
        $prompt = mb_substr($prompt, 0, 4800, 'UTF-8');
    }
    $parts[] = ['text' => $prompt . $suffix];
} else {
    if (strlen($prompt) > 4500) {
        $prompt = mb_substr($prompt, 0, 4500, 'UTF-8');
    }
    $parts[] = ['text' => $prompt];
}


// Gemini'ga so'rov
$result = callGeminiAPI($parts, $aspectRatio);
$usedModel = $result['model'] ?? 'unknown';
// Model qisqa nomi (pro / flash)
$modelLabel = str_contains($usedModel, 'pro') ? '🟣 Pro' : '⚡ Flash';

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
        $errMsg .= "🤖 *Model:* {$modelLabel} (`{$usedModel}`)\n";
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

// Rasm saqlash — xavfsiz, to'g'ridan yozish usuli
$genDir = __DIR__ . '/../generated';
if (!is_dir($genDir)) @mkdir($genDir, 0755, true);

$imgBase64  = $result['imageBase64'];
$imgMime    = $result['mimeType'] ?? 'image/png';
$imgDecoded = base64_decode($imgBase64);
unset($imgBase64); // xotiradan bo'shatish

// mimeType → extension
$mimeToExt = ['image/png'=>'png','image/jpeg'=>'jpg','image/webp'=>'webp','image/gif'=>'gif'];
$srcExt    = $mimeToExt[strtolower($imgMime)] ?? 'png';

$uniqueId  = bin2hex(random_bytes(6));
$imageUrl  = null;

if (!empty($imgDecoded)) {
    $pngSaved = false;

    // Har doim PNG formatiga o'tkazib saqlaymiz (Yuqori sifat va transparentlik uchun)
    if (extension_loaded('gd') && function_exists('imagecreatefromstring') && function_exists('imagepng')) {
        $gdImg = @imagecreatefromstring($imgDecoded);
        if ($gdImg) {
            $pngFile = $genDir . '/infographic_' . $uniqueId . '.png';
            imagealphablending($gdImg, false);
            imagesavealpha($gdImg, true);
            if (@imagepng($gdImg, $pngFile, 6)) {
                $imageUrl = '/generated/' . basename($pngFile);
                $pngSaved = true;
            }
            imagedestroy($gdImg);
        }
    }

    // GD bo'lmasa yoki xato yuz bersa, asl formatida yozamiz (fallback)
    if (!$pngSaved) {
        $rawFile = $genDir . '/infographic_' . $uniqueId . '.' . $srcExt;
        if (file_put_contents($rawFile, $imgDecoded) !== false) {
            $imageUrl = '/generated/' . basename($rawFile);
        }
    }
    unset($imgDecoded);
}

// Rasm saqlash muvaffaqiyatsiz
if (empty($imageUrl)) {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    error_log("generate.php: imageUrl null — mime={$imgMime} genDir={$genDir} writable=" . (is_writable($genDir)?'yes':'no'));
    jsonResponse(['error' => 'Rasm saqlanmadi. Qayta urinib ko\'ring.'], 500);
}

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
$msg .= "🤖 *Model:* {$modelLabel} (`{$usedModel}`)\n";
$msg .= "💰 *Balans:* {$prevBalance} → -{$cost} → " . (int)$newBalance . "\n";
$msg .= "\n🤖 _Tiba AI_";

$telegramMsgId = null;
try {
    // Fayl stat keshini tozalash — yangi yaratilgan faylni PHP ko'rmasligi mumkin
    clearstatcache(true);

    $imagePaths = [];
    if ($originalPath) {
        $origReal = realpath(__DIR__ . '/../' . ltrim($originalPath, '/'));
        if ($origReal && is_file($origReal) && filesize($origReal) > 0) {
            $imagePaths[] = $originalPath;
        } else {
            error_log("generate.php: originalPath NOT FOUND on disk: $originalPath");
        }
    }

    // Natija rasmini tekshirish
    $resultReal = realpath(__DIR__ . '/../' . ltrim($imageUrl, '/'));
    if ($resultReal && is_file($resultReal) && filesize($resultReal) > 0) {
        $imagePaths[] = $imageUrl;
    } else {
        error_log("generate.php: imageUrl NOT FOUND on disk: $imageUrl | realpath=" . ($resultReal ?: 'false'));
    }

    if (!empty($imagePaths)) {
        $tgResponseRaw = sendMediaGroupToTelegram($msg, $imagePaths, true);
        $tgResponse = json_decode($tgResponseRaw, true);
        if (isset($tgResponse['ok']) && $tgResponse['ok'] && isset($tgResponse['result'][0]['message_id'])) {
            $telegramMsgId = $tgResponse['result'][0]['message_id'];
        } elseif (isset($tgResponse['result']['message_id'])) {
            $telegramMsgId = $tgResponse['result']['message_id'];
        }
    } else {
        error_log("generate.php: No valid image files found to send to Telegram!");
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

