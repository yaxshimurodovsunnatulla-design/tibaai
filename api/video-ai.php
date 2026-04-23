<?php
/**
 * Tiba AI — Video AI (Grok xAI Video Generation)
 * 
 * Grok API asinxron ishlaydi:
 * 1. POST /v1/videos/generations → request_id qaytaradi
 * 2. GET /v1/videos/{request_id} → status tekshirish (polling)
 * 
 * Shu sababli frontend 2 ta action yuboradi:
 * - action=generate → video yaratishni boshlash
 * - action=poll → status tekshirish
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'POST required'], 405);
}
checkRateLimit(10);

$input = getInput();
$action = $input['action'] ?? 'generate';

// ========== ACTION: GENERATE ==========
if ($action === 'generate') {
    // Tanga tekshirish
    $balanceInfo = requireBalance('video-ai');

    $prompt = sanitize($input['prompt'] ?? '');
    $duration = (int)($input['duration'] ?? 5);
    $aspectRatio = sanitize($input['aspectRatio'] ?? '16:9');
    $resolution = sanitize($input['resolution'] ?? '720p');
    $productImage = $input['productImage'] ?? null;

    // Validatsiya
    if (empty($prompt) || strlen($prompt) < 5) {
        refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
        jsonResponse(['error' => 'Video tavsifini kiriting (kamida 5 ta belgi)'], 400);
    }

    // Duration chegaralash (1-15 soniya)
    $duration = max(1, min(15, $duration));

    // Aspect ratio tekshirish
    $allowedRatios = ['auto', '1:1', '16:9', '9:16', '4:3', '3:4', '3:2', '2:3'];
    if (!in_array($aspectRatio, $allowedRatios)) {
        $aspectRatio = '16:9';
    }

    // Resolution tekshirish
    $allowedResolutions = ['480p', '720p'];
    if (!in_array($resolution, $allowedResolutions)) {
        $resolution = '720p';
    }

    // Grok API ga so'rov
    $xaiApiKey = getenv('XAI_API_KEY');
    if (!$xaiApiKey || $xaiApiKey === 'YOUR_XAI_API_KEY_HERE') {
        refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
        jsonResponse(['error' => 'Grok API kaliti sozlanmagan. Admin bilan bog\'laning.'], 500);
    }

    $payload = [
        'model' => 'grok-imagine-video',
        'prompt' => $prompt,
        'duration' => $duration,
        'aspect_ratio' => $aspectRatio,
        'resolution' => $resolution,
    ];

    // Agar rasm berilgan bo'lsa — image-to-video
    if (!empty($productImage)) {
        $processedImage = processImageInput($productImage);
        if ($processedImage) {
            // "auto" bo'lsa rasmning o'zidan aniqlaymiz
            if ($aspectRatio === 'auto') {
                $aspectRatio = detectClosestRatio($processedImage['data']);
            }

            // MUHIM: Rasmni target aspect_ratio ga moslab qrqish (Center Crop)
            // Bu rasm cho'zilib ketishini oldini oladi.
            $croppedData = cropImageToRatio($processedImage['data'], $aspectRatio);
            
            $payload['image'] = [
                'url' => 'data:' . $processedImage['mime_type'] . ';base64,' . ($croppedData ?: $processedImage['data'])
            ];
            // API ga yuboriladigan payload dagi aspect_ratio ni yangilash
            $payload['aspect_ratio'] = $aspectRatio;
        }
    }

    $ch = curl_init('https://api.x.ai/v1/videos/generations');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $xaiApiKey,
        ],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
        jsonResponse(['error' => "Tarmoq xatosi: $curlError"], 500);
    }

    $data = json_decode($response, true);

    if (!in_array($httpCode, [200, 201]) || empty($data['request_id'])) {
        refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
        $msg = $data['error']['message'] ?? $data['error'] ?? 'Grok API xatosi';
        error_log("Grok Video API Error ($httpCode): " . $response);
        jsonResponse(['error' => $msg], $httpCode ?: 500);
    }

    $requestId = $data['request_id'];

    // request_id ni foydalanuvchi bilan bog'lab saqlash (xavfsizlik uchun)
    $lockDir = __DIR__ . '/../tmp/video_requests';
    if (!is_dir($lockDir)) mkdir($lockDir, 0755, true);
    file_put_contents($lockDir . '/' . $requestId . '.json', json_encode([
        'user_id' => $balanceInfo['user']['id'],
        'cost' => $balanceInfo['cost'],
        'prompt' => $prompt,
        'duration' => $duration,
        'created_at' => time(),
    ]));

    // Tangalarni hozircha ayirmagan — faqat reserve qilgan (requireBalance ichida)
    // deductBalance ni poll=done bo'lgandagina chaqiramiz
    // Ammo requireBalance allaqachon ayirib bo'lgan, shuning uchun deduct qilamiz
    $newBalance = deductBalance($balanceInfo['user']['id'], $balanceInfo['cost'], 'video-ai');

    jsonResponse([
        'success' => true,
        'requestId' => $requestId,
        'balance' => $newBalance,
        'message' => 'Video yaratish boshlandi. Natijani tekshirib turing...',
    ]);
}

// ========== ACTION: POLL ==========
if ($action === 'poll') {
    $user = getAuthUser();
    if (!$user) {
        jsonResponse(['error' => 'Tizimga kiring', 'auth_required' => true], 401);
    }

    $requestId = sanitize($input['requestId'] ?? '');
    if (empty($requestId)) {
        jsonResponse(['error' => 'request_id kerak'], 400);
    }

    // Xavfsizlik: faqat shu foydalanuvchining request_id sini ruxsat berish
    $lockFile = __DIR__ . '/../tmp/video_requests/' . $requestId . '.json';
    if (file_exists($lockFile)) {
        $lockData = json_decode(file_get_contents($lockFile), true);
        if ($lockData && (int)$lockData['user_id'] !== (int)$user['id']) {
            jsonResponse(['error' => 'Ruxsat yo\'q'], 403);
        }
    }

    $xaiApiKey = getenv('XAI_API_KEY');
    if (!$xaiApiKey) {
        jsonResponse(['error' => 'API kalit topilmadi'], 500);
    }

    $ch = curl_init('https://api.x.ai/v1/videos/' . urlencode($requestId));
    curl_setopt_array($ch, [
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $xaiApiKey,
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode($response, true);

    // Debug log
    error_log("Grok Video Poll ($httpCode): " . substr($response, 0, 500));

    // Grok API 200 (done) yoki 202 (pending) qaytaradi — ikkalasi ham OK
    if ($httpCode !== 200 && $httpCode !== 202) {
        $msg = $data['error']['message'] ?? $data['error'] ?? 'Status tekshirishda xatolik';
        jsonResponse(['error' => $msg], $httpCode ?: 500);
    }

    $status = $data['status'] ?? 'pending';

    if ($status === 'done') {
        $videoUrl = $data['video']['url'] ?? null;
        $videoDuration = $data['video']['duration'] ?? 0;

        if ($videoUrl) {
            // MUHIM: Race condition dan himoya. 
            // Video tayyor bo'lishi bilan lock faylni o'chirib tashlaymiz 
            // toki keyingi parallel so'rovlar bu blokga kirmasin.
            if (file_exists($lockFile)) {
                @unlink($lockFile);
            } else {
                // Agar fayl allaqachon o'chirilgan bo'lsa, demak boshqa jarayon buni qilyapti
                jsonResponse(['status' => 'pending']); 
            }

            // Videoni serverga yuklab olish
            $savedUrl = downloadAndSaveVideo($videoUrl);

            // Telegramga yuborish
            $telegramMsgId = null;
            $msg = "🎬 *Video AI*\n\n";
            $msg .= "📝 *Prompt:* " . ($lockData['prompt'] ?? 'N/A') . "\n";
            $msg .= "⏱ *Davomiyligi:* {$videoDuration}s\n";
            $msg .= "\n🤖 _Tiba AI_";

            try {
                $tgResponseRaw = sendToTelegram($msg, $savedUrl, true);
                $tgResponse = json_decode($tgResponseRaw, true);
                if (isset($tgResponse['ok']) && $tgResponse['ok'] && isset($tgResponse['result']['message_id'])) {
                    $telegramMsgId = $tgResponse['result']['message_id'];
                }
            } catch (Exception $e) {
                error_log("Telegram Video Error: " . $e->getMessage());
            }

            // Tarixga saqlash
            try {
                $db = getDB();
                $stmt = $db->prepare("INSERT INTO generations (user_id, image_path, prompt_data, telegram_msg_id) VALUES (?, ?, ?, ?)");
                $promptData = json_encode([
                    'product' => 'Video AI',
                    'prompt' => $lockData['prompt'] ?? '',
                    'duration' => $videoDuration,
                    'type' => 'video-ai'
                ], JSON_UNESCAPED_UNICODE);
                $stmt->execute([$user['id'], $savedUrl, $promptData, $telegramMsgId]);
            } catch (Exception $e) {
                error_log("DB Video Save Error: " . $e->getMessage());
            }

            jsonResponse([
                'status' => 'done',
                'videoUrl' => $savedUrl,
                'duration' => $videoDuration,
            ]);
        }
    }

    if ($status === 'expired') {
        // Xato — refund qilish mumkin (lekin tanga allaqachon ayirilgan)
        if (file_exists($lockFile)) @unlink($lockFile);
        jsonResponse(['status' => 'expired', 'error' => 'Video generatsiyasi vaqti tugadi. Qayta urinib ko\'ring.']);
    }

    // Hali tugamagan
    jsonResponse(['status' => 'pending']);
}

jsonResponse(['error' => 'Noma\'lum action'], 400);

// ========== YORDAMCHI FUNKSIYALAR ==========

/**
 * Grok URL dan videoni yuklab olib serverga saqlash
 */
function downloadAndSaveVideo($url) {
    $dir = __DIR__ . '/../generated';
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $filename = 'video_' . bin2hex(random_bytes(6)) . '.mp4';
    $filepath = $dir . '/' . $filename;

    $ch = curl_init($url);
    $fp = fopen($filepath, 'wb');
    curl_setopt_array($ch, [
        CURLOPT_FILE => $fp,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    fclose($fp);

    if ($httpCode !== 200 || !file_exists($filepath) || filesize($filepath) < 1000) {
        if (file_exists($filepath)) @unlink($filepath);
        return $url; // Fallback: asl URL ni qaytarish
    }

    return '/generated/' . $filename;
}

/**
 * Rasmni tanlangan nisbat (ratio) ga moslab markazdan qrqish (Center Crop)
 */
function cropImageToRatio($base64Data, $ratioStr) {
    $imgData = base64_decode($base64Data);
    $src = imagecreatefromstring($imgData);
    if (!$src) return false;

    $width = imagesx($src);
    $height = imagesy($src);

    // Nisbatni ritsional son ko'rinishiga keltirish
    $parts = explode(':', $ratioStr);
    if (count($parts) !== 2) return false;
    $targetRatio = (float)$parts[0] / (float)$parts[1];

    $currentRatio = $width / $height;

    $newWidth = $width;
    $newHeight = $height;
    $srcX = 0;
    $srcY = 0;

    if ($currentRatio > $targetRatio) {
        // Rasm juda keng — yonlarini qirqamiz
        $newWidth = $height * $targetRatio;
        $srcX = ($width - $newWidth) / 2;
    } else {
        // Rasm juda baland — tepa-pastini qirqamiz
        $newHeight = $width / $targetRatio;
        $srcY = ($height - $newHeight) / 2;
    }

    $dest = imagecreatetruecolor($newWidth, $newHeight);
    
    // PNG/WebP transparency saqlash (ixtiyoriy, lekin yaxshi)
    imagealphablending($dest, false);
    imagesavealpha($dest, true);
    $transparent = imagecolorallocatealpha($dest, 255, 255, 255, 127);
    imagefilledrectangle($dest, 0, 0, $newWidth, $newHeight, $transparent);

    imagecopyresampled($dest, $src, 0, 0, $srcX, $srcY, $newWidth, $newHeight, $newWidth, $newHeight);

    // Quality va yuklash
    ob_start();
    imagepng($dest); // Eng xavfsiz format
    $finalData = ob_get_clean();

    imagedestroy($src);
    imagedestroy($dest);

    return base64_encode($finalData);
}

/**
 * Rasm o'lchamidan eng yaqin Grok ratio sini aniqlash
 */
function detectClosestRatio($base64Data) {
    $imgData = base64_decode($base64Data);
    $img = imagecreatefromstring($imgData);
    if (!$img) return '16:9';

    $w = imagesx($img);
    $h = imagesy($img);
    $currentRatio = $w / $h;
    imagedestroy($img);

    // Grok qo'llaydigan asosiy ratiolar
    $supportedRatios = [
        '16:9' => 16/9,
        '9:16' => 9/16,
        '1:1'  => 1/1,
        '4:3'  => 4/3,
        '3:4'  => 3/4,
        '3:2'  => 3/2,
        '2:3'  => 2/3
    ];

    $bestRatio = '16:9';
    $minDiff = 999;

    foreach ($supportedRatios as $slug => $val) {
        $diff = abs($currentRatio - $val);
        if ($diff < $minDiff) {
            $minDiff = $diff;
            $bestRatio = $slug;
        }
    }

    return $bestRatio;
}


