<?php
/**
 * Kapital Bank Lead — murojaat qabul qilish
 * POST /api/kapital-bank-lead.php
 * Body: { company, phone }
 */
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../api/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$body    = json_decode(file_get_contents('php://input'), true);
$company = trim($body['company'] ?? '');
$phone   = trim($body['phone']   ?? '');

if (!$company || !$phone) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => 'company va phone majburiy']);
    exit;
}

// ── 1. Ma'lumotlar bazasiga saqlash ──────────────────────────────────────────
try {
    $db = getDB();
    $db->exec("CREATE TABLE IF NOT EXISTS kapital_bank_leads (
        id        INTEGER PRIMARY KEY AUTOINCREMENT,
        company   TEXT NOT NULL,
        phone     TEXT NOT NULL,
        ip        TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    $stmt = $db->prepare("INSERT INTO kapital_bank_leads (company, phone, ip) VALUES (?, ?, ?)");
    $stmt->execute([$company, $phone, $_SERVER['REMOTE_ADDR'] ?? '']);
} catch (Exception $e) {
    // DB xatosi bo'lsa ham davom etamiz — Telegram xabari yuborilsin
}

// ── 2. Telegram orqali admin xabardor qilish ─────────────────────────────────
$botToken = $_ENV['TELEGRAM_BOT_TOKEN'] ?? getenv('TELEGRAM_BOT_TOKEN');
$adminId  = $_ENV['PAYMENT_ADMIN_CHAT_ID'] ?? getenv('PAYMENT_ADMIN_CHAT_ID');

if ($botToken && $adminId) {
    $text = "🏦 <b>Kapital Bank — Yangi murojaat</b>\n\n"
          . "🏢 <b>Korxona:</b> " . htmlspecialchars($company) . "\n"
          . "📞 <b>Telefon:</b> " . htmlspecialchars($phone) . "\n"
          . "🕐 <b>Vaqt:</b> " . date('d.m.Y H:i') . " (Toshkent)\n"
          . "🌐 <b>IP:</b> " . ($_SERVER['REMOTE_ADDR'] ?? 'nomaʼlum');

    @file_get_contents(
        "https://api.telegram.org/bot{$botToken}/sendMessage?" . http_build_query([
            'chat_id'    => $adminId,
            'text'       => $text,
            'parse_mode' => 'HTML',
        ])
    );
}

echo json_encode(['ok' => true, 'message' => 'Murojaatingiz qabul qilindi']);
