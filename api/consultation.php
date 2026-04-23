<?php
/**
 * Konsultatsiya so'rovi - Telegram botga yuborish
 */
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Faqat POST so\'rov qabul qilinadi']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$name = trim($input['name'] ?? '');
$phone = trim($input['phone'] ?? '');

// Validatsiya
if (empty($name) || mb_strlen($name) < 2) {
    echo json_encode(['error' => 'Ismingizni to\'g\'ri kiriting (kamida 2 belgi)']);
    exit;
}

if (empty($phone) || strlen(preg_replace('/[^0-9+]/', '', $phone)) < 9) {
    echo json_encode(['error' => 'Telefon raqamni to\'g\'ri kiriting']);
    exit;
}

// .env dan o'qish
$envFile = __DIR__ . '/../.env';
$botToken = '';
$chatId = '';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($key, $val) = explode('=', $line, 2);
        $key = trim($key);
        $val = trim($val);
        if ($key === 'TELEGRAM_BOT_TOKEN') $botToken = $val;
        if ($key === 'PAYMENT_ADMIN_CHAT_ID') $chatId = $val;
    }
}

if (empty($botToken) || empty($chatId)) {
    echo json_encode(['error' => 'Tizim sozlamalari topilmadi']);
    exit;
}

// Telegram xabar matni
$date = date('d.m.Y H:i');
$message = "📚 *Yangi Konsultatsiya So'rovi!*\n\n"
    . "👤 *Ism:* {$name}\n"
    . "📞 *Telefon:* {$phone}\n"
    . "📅 *Sana:* {$date}\n\n"
    . "🏷 *Kurs:* Marketpleyslarda Savdo\n"
    . "💬 *Turi:* Tekin Konsultatsiya\n\n"
    . "━━━━━━━━━━━━━━━━━━━━\n"
    . "🤖 _Tiba AI Kurslar bo'limi orqali_";

// Telegram API ga yuborish
$telegramUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";
$postData = [
    'chat_id' => $chatId,
    'text' => $message,
    'parse_mode' => 'Markdown',
];

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $telegramUrl,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($postData),
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_SSL_VERIFYPEER => false,
]);

$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$response = json_decode($result, true);

if ($httpCode === 200 && isset($response['ok']) && $response['ok']) {
    echo json_encode(['success' => true, 'message' => 'So\'rovingiz muvaffaqiyatli yuborildi!']);
} else {
    echo json_encode(['error' => 'Xabar yuborishda xatolik yuz berdi. Qaytadan urinib ko\'ring.']);
}
