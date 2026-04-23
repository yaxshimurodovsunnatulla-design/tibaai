<?php
/**
 * Tiba AI — UzbWallet Payment Callback
 * POST /api/payment-callback.php
 *
 * UzbWallet muvaffaqiyatli to'lovdan keyin shu endpointga quyidagini yuboradi:
 * {
 *   "status": "confirmed",
 *   "payment_id": "12345",
 *   "amount": "5000.00",
 *   "external_id": "tiba_1_starter_1713260000",
 *   "provider": "Click" | "Payme"
 * }
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/config.php';

$raw   = file_get_contents('php://input');
$data  = json_decode($raw, true);

error_log("[payment-callback] received: $raw");

// Lazimli maydonlar tekshirish
$status      = $data['status']      ?? '';
$externalId  = $data['external_id'] ?? '';
$gatewayId   = $data['payment_id']  ?? '';
$amount      = $data['amount']      ?? 0;
$provider    = $data['provider']    ?? '';

if ($status !== 'confirmed' || !$externalId) {
    http_response_code(200); // UzbWallet 200 kutadi
    echo json_encode(['received' => true]);
    exit;
}

$db = getDB();

// ── external_id orqali to'lovni topish ───────────────────────────────────────
try {
    $stmt = $db->prepare("SELECT * FROM payments WHERE external_id = ? LIMIT 1");
    $stmt->execute([$externalId]);
    $payment = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // external_id ustuni yo'q bo'lsa — gateway_payment_id orqali
    try {
        $stmt = $db->prepare("SELECT * FROM payments WHERE gateway_payment_id = ? LIMIT 1");
        $stmt->execute([$gatewayId]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e2) {
        $payment = null;
    }
}

if (!$payment) {
    error_log("[payment-callback] payment not found: external_id=$externalId");
    http_response_code(200);
    echo json_encode(['received' => true, 'note' => 'payment_not_found']);
    exit;
}

if ($payment['status'] === 'approved') {
    // Allaqachon tasdiqlangan — idempotent
    http_response_code(200);
    echo json_encode(['received' => true, 'note' => 'already_approved']);
    exit;
}

// ── To'lovni tasdiqlash va kredit qo'shish ────────────────────────────────────
try {
    $db->beginTransaction();

    // payments jadvalini yangilash
    try {
        $stmt = $db->prepare("UPDATE payments SET status = 'approved', provider = ? WHERE id = ?");
        $stmt->execute([$provider, $payment['id']]);
    } catch (Exception $e) {
        $stmt = $db->prepare("UPDATE payments SET status = 'approved' WHERE id = ?");
        $stmt->execute([$payment['id']]);
    }

    // Foydalanuvchiga kredit qo'shish
    $stmt = $db->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
    $stmt->execute([$payment['credits'], $payment['user_id']]);

    $db->commit();

    error_log("[payment-callback] approved: payment_id={$payment['id']} user_id={$payment['user_id']} credits={$payment['credits']}");

    // ── Admin Telegramga xabar ────────────────────────────────────────────────
    notifyAdmin($payment, $provider, $gatewayId);

} catch (Exception $e) {
    $db->rollBack();
    error_log("[payment-callback] DB error: " . $e->getMessage());
}

http_response_code(200);
echo json_encode(['received' => true, 'status' => 'approved']);

// ── Telegram xabar ───────────────────────────────────────────────────────────
function notifyAdmin($payment, $provider, $gatewayId) {
    $envFile = __DIR__ . '/../.env';
    $env = [];
    if (file_exists($envFile)) {
        foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
            [$k, $v] = explode('=', $line, 2);
            $env[trim($k)] = trim(trim($v), "\"'");
        }
    }
    $botToken = $env['TELEGRAM_BOT_TOKEN'] ?? '';
    $chatId   = $env['PAYMENT_ADMIN_CHAT_ID'] ?? '';
    if (!$botToken || !$chatId) return;

    $text = "✅ <b>To'lov avtomatik tasdiqlandi</b>\n\n"
          . "💳 <b>Provider:</b> $provider\n"
          . "🔢 <b>Gateway ID:</b> $gatewayId\n"
          . "🆔 <b>Local ID:</b> #{$payment['id']}\n"
          . "👤 <b>User ID:</b> {$payment['user_id']}\n"
          . "📦 <b>Paket:</b> {$payment['package_name']}\n"
          . "🪙 <b>Kredit:</b> +{$payment['credits']} tanga\n"
          . "💵 <b>Summa:</b> " . number_format($payment['amount'], 0, '', ',') . " so'm\n"
          . "🕐 <b>Vaqt:</b> " . date('d.m.Y H:i');

    @file_get_contents("https://api.telegram.org/bot{$botToken}/sendMessage?" . http_build_query([
        'chat_id'    => $chatId,
        'text'       => $text,
        'parse_mode' => 'HTML',
    ]));
}
