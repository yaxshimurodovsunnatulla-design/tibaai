<?php
/**
 * Tiba AI — To'lov tizimi API
 * 
 * Endpointlar:
 *   POST /api/payment.php
 *     action=get_packages   → Paketlar va karta ma'lumotlarini qaytarish
 *     action=submit_payment → Chek yuborish (rasm + ma'lumotlar)
 *     action=check_payment  → To'lov holatini tekshirish
 */

require_once __DIR__ . '/config.php';

// CORS + headers
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Method not allowed'], 405);
}

$input = getInput();
$action = $input['action'] ?? '';

// Auth tekshirish (faqat submit va check uchun)
$token = $_SERVER['HTTP_X_USER_TOKEN'] ?? $input['token'] ?? null;
$currentUser = null;
if ($token) {
    $db = getDB();
    $stmt = $db->prepare("SELECT u.* FROM users u JOIN user_sessions s ON u.id = s.user_id WHERE s.token = ? AND s.expires_at > ?");
    $stmt->execute([$token, date('Y-m-d H:i:s')]);
    $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);
}

switch ($action) {
    case 'get_packages':
        handleGetPackages();
        break;
    case 'submit_payment':
        if (!$currentUser) jsonResponse(['error' => 'Tizimga kiring'], 401);
        handleSubmitPayment($input, $currentUser);
        break;
    case 'check_payment':
        if (!$currentUser) jsonResponse(['error' => 'Tizimga kiring'], 401);
        handleCheckPayment($input, $currentUser);
        break;
    default:
        jsonResponse(['error' => "Noto'g'ri amal"], 400);
}

// ========== HANDLERS ==========

function handleGetPackages() {
    $db = getDB();
    
    try {
        $stmt = $db->query("SELECT * FROM packages WHERE is_active = 1 ORDER BY sort_order ASC");
        $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($packages as &$p) {
            $p['credits'] = intval($p['credits']);
            $p['price'] = intval($p['price']);
            $p['features'] = json_decode($p['features'] ?? '[]', true) ?: [];
        }
    } catch (Exception $e) {
        // Fallback if table doesn't exist
        $packages = [
            ['id' => 'starter',      'name' => "Boshlang'ich",  'credits' => 50,   'price' => 69000,   'icon' => 'fa-seedling', 'gradient' => 'from-gray-600 to-gray-500', 'features' => []],
            ['id' => 'professional', 'name' => 'Professional',  'credits' => 150,  'price' => 189000,  'icon' => 'fa-rocket',   'gradient' => 'from-indigo-600 to-purple-600', 'features' => []],
            ['id' => 'business',     'name' => 'Biznes',        'credits' => 500,  'price' => 549000,  'icon' => 'fa-gem',      'gradient' => 'from-emerald-600 to-teal-600', 'features' => []],
            ['id' => 'enterprise',   'name' => 'Enterprise',    'credits' => 1500, 'price' => 1449000, 'icon' => 'fa-crown',    'gradient' => 'from-amber-600 to-orange-600', 'features' => []],
        ];
    }

    // .env dan to'g'ridan-to'g'ri o'qish
    $envVars = readEnvFile();
    $cardNumber = $envVars['PAYMENT_CARD_NUMBER'] ?? '8600 0000 0000 0000';
    $cardHolder = $envVars['PAYMENT_CARD_HOLDER'] ?? 'TIBA AI';

    jsonResponse([
        'success' => true,
        'packages' => $packages,
        'card' => [
            'number' => $cardNumber,
            'holder' => $cardHolder,
        ],
        'methods' => [
            ['id' => 'card', 'name' => 'Karta orqali', 'icon' => 'fa-credit-card', 'active' => true],
            ['id' => 'click', 'name' => 'Click', 'icon' => 'fa-mobile-screen', 'active' => false, 'badge' => 'Tez kunda'],
            ['id' => 'payme', 'name' => 'PayMe', 'icon' => 'fa-wallet', 'active' => false, 'badge' => 'Tez kunda'],
        ],
    ]);
}

/**
 * .env faylni har safar to'g'ridan-to'g'ri o'qish
 * (getenv() kesh muammosini hal qiladi)
 */
function readEnvFile() {
    $envFile = __DIR__ . '/../.env';
    $vars = [];
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || $line[0] === '#') continue;
            if (strpos($line, '=') === false) continue;
            [$key, $val] = explode('=', $line, 2);
            $vars[trim($key)] = trim(trim($val), "\"'");
        }
    }
    return $vars;
}

function handleSubmitPayment($input, $user) {
    $packageId = $input['package_id'] ?? '';
    $receiptImage = $input['receipt_image'] ?? ''; // base64

    // Paket validatsiya
    $packages = [
        'starter'      => ['name' => "Boshlang'ich", 'credits' => 50,   'price' => 69000],
        'professional' => ['name' => 'Professional', 'credits' => 150,  'price' => 189000],
        'business'     => ['name' => 'Biznes',       'credits' => 500,  'price' => 549000],
        'enterprise'   => ['name' => 'Enterprise',   'credits' => 1500, 'price' => 1449000],
    ];

    if (!isset($packages[$packageId])) {
        jsonResponse(['error' => "Noto'g'ri paket"], 400);
    }

    if (empty($receiptImage)) {
        jsonResponse(['error' => "To'lov chekini yuklang"], 400);
    }

    // Base64 rasm tekshirish
    if (preg_match('/^data:image\/(png|jpg|jpeg|webp);base64,/', $receiptImage)) {
        $receiptImage = preg_replace('/^data:image\/\w+;base64,/', '', $receiptImage);
    }

    $imageData = base64_decode($receiptImage);
    if ($imageData === false || strlen($imageData) < 100) {
        jsonResponse(['error' => "Noto'g'ri rasm formati"], 400);
    }

    if (strlen($imageData) > 10 * 1024 * 1024) {
        jsonResponse(['error' => 'Rasm hajmi 10MB dan oshmasligi kerak'], 400);
    }

    $pkg = $packages[$packageId];
    $db = getDB();

    // payments jadvali yaratish


    // Chekni saqlash
    $receiptDir = __DIR__ . '/../data/receipts';
    if (!is_dir($receiptDir)) mkdir($receiptDir, 0755, true);

    $fileName = 'receipt_' . $user['id'] . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.jpg';
    $filePath = $receiptDir . '/' . $fileName;
    file_put_contents($filePath, $imageData);

    // DB ga yozish
    $stmt = $db->prepare("INSERT INTO payments (user_id, package_id, package_name, credits, amount, status, receipt_path) VALUES (?, ?, ?, ?, ?, 'pending', ?)");
    $stmt->execute([$user['id'], $packageId, $pkg['name'], $pkg['credits'], $pkg['price'], $fileName]);
    $paymentId = $db->lastInsertId();

    // Telegram'ga yuborish
    sendPaymentToTelegram($user, $pkg, $paymentId, $filePath);

    jsonResponse([
        'success' => true,
        'payment_id' => $paymentId,
        'message' => "To'lov cheki yuborildi! Tez orada tasdiqlanadi.",
    ]);
}

function handleCheckPayment($input, $user) {
    $paymentId = intval($input['payment_id'] ?? 0);
    $db = getDB();



    if ($paymentId > 0) {
        $stmt = $db->prepare("SELECT id, package_name, credits, amount, status, admin_note, created_at FROM payments WHERE id = ? AND user_id = ?");
        $stmt->execute([$paymentId, $user['id']]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($payment) {
            jsonResponse(['success' => true, 'payment' => $payment]);
        }
    }

    // Oxirgi to'lovlarni ko'rsatish
    $stmt = $db->prepare("SELECT id, package_name, credits, amount, status, admin_note, created_at FROM payments WHERE user_id = ? ORDER BY id DESC LIMIT 10");
    $stmt->execute([$user['id']]);
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    jsonResponse(['success' => true, 'payments' => $payments]);
}

// ========== TELEGRAM ==========

function sendPaymentToTelegram($user, $pkg, $paymentId, $imagePath) {
    $botToken = getenv('TELEGRAM_BOT_TOKEN');
    $envVars = readEnvFile();
    $chatId = $envVars['PAYMENT_ADMIN_CHAT_ID'] ?? getenv('TELEGRAM_CHANNEL_ID');

    if (!$botToken || !$chatId) return;

    $priceFormatted = number_format($pkg['price'], 0, '', ',');

    $caption = "💰 <b>YANGI TO'LOV #{$paymentId}</b>\n\n"
        . "👤 <b>Foydalanuvchi:</b> {$user['name']}\n"
        . "📧 <b>Email:</b> {$user['email']}\n"
        . "🆔 <b>User ID:</b> {$user['id']}\n\n"
        . "📦 <b>Paket:</b> {$pkg['name']}\n"
        . "🪙 <b>Tanga:</b> {$pkg['credits']}\n"
        . "💵 <b>Summa:</b> {$priceFormatted} so'm\n\n"
        . "⏰ <b>Vaqt:</b> " . date('d.m.Y H:i') . "\n"
        . "📋 <b>Status:</b> ⏳ Kutilmoqda\n\n"
        . "✅ Tasdiqlash: <code>/approve {$paymentId}</code>\n"
        . "❌ Rad etish: <code>/reject {$paymentId} [sabab]</code>";

    // Rasmni Telegram'ga yuborish
    $url = "https://api.telegram.org/bot{$botToken}/sendPhoto";

    $postData = [
        'chat_id' => $chatId,
        'caption' => $caption,
        'parse_mode' => 'HTML',
        'photo' => new CURLFile($imagePath, 'image/jpeg', 'receipt.jpg'),
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        error_log("Telegram payment error: $err");
    }

    // Inline buttons bilan xabar (approve/reject)
    $messageUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";
    $keyboard = json_encode([
        'inline_keyboard' => [
            [
                ['text' => '✅ Tasdiqlash', 'callback_data' => "approve_{$paymentId}"],
                ['text' => '❌ Rad etish', 'callback_data' => "reject_{$paymentId}"],
            ]
        ]
    ]);

    $msgData = [
        'chat_id' => $chatId,
        'text' => "🔔 To'lov #{$paymentId} uchun amal tanlang:",
        'reply_markup' => $keyboard,
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $messageUrl,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($msgData),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    curl_exec($ch);
    curl_close($ch);
}
