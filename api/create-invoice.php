<?php
/**
 * Tiba AI — UzbWallet Invoice yaratish
 * POST /api/create-invoice.php
 * Body: { package_id, provider }  provider = 'click' | 'payme'
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// ── Auth ──────────────────────────────────────────────────────────────────────
$token = $_SERVER['HTTP_X_USER_TOKEN'] ?? null;
$currentUser = null;
if ($token) {
    $db = getDB();
    $stmt = $db->prepare("SELECT u.* FROM users u JOIN user_sessions s ON u.id = s.user_id WHERE s.token = ? AND s.expires_at > ?");
    $stmt->execute([$token, date('Y-m-d H:i:s')]);
    $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);
}
if (!$currentUser) {
    http_response_code(401);
    echo json_encode(['error' => 'Tizimga kiring']);
    exit;
}

$input      = json_decode(file_get_contents('php://input'), true);
$packageId  = $input['package_id'] ?? '';
$provider   = strtolower($input['provider'] ?? ''); // 'click' | 'payme'

// ── Paket ma'lumotlari ────────────────────────────────────────────────────────
$db = getDB();
try {
    $stmt = $db->prepare("SELECT * FROM packages WHERE id = ? AND is_active = 1 LIMIT 1");
    $stmt->execute([$packageId]);
    $pkg = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $pkg = null;
}

// Fallback static paketlar
if (!$pkg) {
    $static = [
        'starter'      => ['name' => "Boshlang'ich", 'credits' => 50,   'price' => 69000],
        'professional' => ['name' => 'Professional', 'credits' => 150,  'price' => 189000],
        'business'     => ['name' => 'Biznes',       'credits' => 500,  'price' => 549000],
        'enterprise'   => ['name' => 'Enterprise',   'credits' => 1500, 'price' => 1449000],
    ];
    $pkg = $static[$packageId] ?? null;
    if ($pkg) { $pkg['id'] = $packageId; }
}

if (!$pkg) {
    http_response_code(422);
    echo json_encode(['error' => "Noto'g'ri paket"]);
    exit;
}

if (!in_array($provider, ['click', 'payme'])) {
    http_response_code(422);
    echo json_encode(['error' => "Provider noto'g'ri. click yoki payme bo'lishi kerak"]);
    exit;
}

// ── UzbWallet: invoice yaratish ───────────────────────────────────────────────
$UZBWALLET_API_KEY = 'bc72e4f185';
$externalId = 'tiba_' . $currentUser['id'] . '_' . $packageId . '_' . time();

$postData = [
    'api_key'     => $UZBWALLET_API_KEY,
    'amount'      => (float) $pkg['price'],
    'external_id' => $externalId,
];

$ch = curl_init('https://uzbwallet.uz/bot/api/create_invoice.php');
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $postData,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_SSL_VERIFYPEER => false,
]);
$raw = curl_exec($ch);
$curlErr = curl_error($ch);
curl_close($ch);

if ($curlErr) {
    error_log("UzbWallet cURL error: $curlErr");
    http_response_code(502);
    echo json_encode(['error' => "To'lov tizimiga ulanishda xato. Qaytadan urinib ko'ring."]);
    exit;
}

$result = json_decode($raw, true);

if (!$result || empty($result['success'])) {
    error_log("UzbWallet error response: $raw");
    http_response_code(502);
    echo json_encode(['error' => "To'lov tizimidan xato javob keldi. Keyinroq urinib ko'ring."]);
    exit;
}

// ── Payments jadvaliga pending yozuv qo'shish ─────────────────────────────────
$paymentUrl  = $provider === 'click' ? ($result['click_url'] ?? '') : ($result['payme_url'] ?? '');
$paymentGwId = $result['payment_id'] ?? null;

try {
    $stmt = $db->prepare("INSERT INTO payments
        (user_id, package_id, package_name, credits, amount, status, receipt_path, provider, gateway_payment_id, external_id)
        VALUES (?, ?, ?, ?, ?, 'pending', '', ?, ?, ?)");
    $stmt->execute([
        $currentUser['id'],
        $packageId,
        $pkg['name'],
        $pkg['credits'],
        $pkg['price'],
        $provider,
        $paymentGwId,
        $externalId,
    ]);
    $localPaymentId = $db->lastInsertId();
} catch (Exception $e) {
    // Eski sxemada yangi ustunlar yo'q bo'lsa — oddiy yozuv
    try {
        $stmt = $db->prepare("INSERT INTO payments (user_id, package_id, package_name, credits, amount, status, receipt_path) VALUES (?, ?, ?, ?, ?, 'pending', '')");
        $stmt->execute([$currentUser['id'], $packageId, $pkg['name'], $pkg['credits'], $pkg['price']]);
        $localPaymentId = $db->lastInsertId();
    } catch (Exception $e2) {
        $localPaymentId = 0;
    }
}

echo json_encode([
    'success'      => true,
    'payment_url'  => $paymentUrl,
    'payment_id'   => $localPaymentId,
    'gateway_id'   => $paymentGwId,
    'external_id'  => $externalId,
    'provider'     => $provider,
]);
