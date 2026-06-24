<?php
/**
 * Tiba AI — Blogger Auth API
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Faqat POST'], 405);
}

$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (stripos($contentType, 'application/json') === false) {
    jsonResponse(['error' => 'Content-Type application/json bo\'lishi kerak'], 415);
}

$input = getInput();
$action = $input['action'] ?? '';

switch ($action) {
    case 'register': handleRegister($input); break;
    case 'login':    handleLogin($input); break;
    case 'check':    handleCheck(); break;
    case 'logout':   handleLogout(); break;
    default: jsonResponse(['error' => 'Noto\'g\'ri amal'], 400);
}

function createBloggerSession($db, $bloggerId) {
    $token = bin2hex(random_bytes(64));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));
    $now = date('Y-m-d H:i:s');
    
    $db->prepare("INSERT INTO blogger_sessions (blogger_id, token, expires_at) VALUES (?, ?, ?)")
       ->execute([$bloggerId, $token, $expiresAt]);
       
    // Clean old sessions
    $db->exec("DELETE FROM blogger_sessions WHERE expires_at < '$now'");
    $db->prepare("DELETE FROM blogger_sessions WHERE blogger_id = ? AND id NOT IN (SELECT id FROM blogger_sessions WHERE blogger_id = ? ORDER BY created_at DESC LIMIT 5)")
       ->execute([$bloggerId, $bloggerId]);
       
    return $token;
}

function handleRegister($input) {
    $name = sanitize(trim($input['name'] ?? ''));
    $phone = sanitize(trim($input['phone'] ?? ''));
    $password = $input['password'] ?? '';
    
    if (empty($name) || empty($phone) || empty($password)) {
        jsonResponse(['error' => 'Barcha maydonlarni to\'ldiring'], 400);
    }
    
    if (strlen($password) < 6) {
        jsonResponse(['error' => 'Parol kamida 6 belgi bo\'lishi kerak'], 400);
    }

    $db = getDB();
    
    $stmt = $db->prepare("SELECT id FROM bloggers WHERE phone = ?");
    $stmt->execute([$phone]);
    if ($stmt->fetch()) {
        jsonResponse(['error' => 'Bu telefon raqam allaqachon ro\'yxatdan o\'tgan'], 409);
    }

    $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    
    $db->prepare("INSERT INTO bloggers (phone, password_hash, display_name, status) VALUES (?, ?, ?, 'pending')")
       ->execute([$phone, $hash, $name]);
       
    $bloggerId = $db->lastInsertId();
    $token = createBloggerSession($db, $bloggerId);
    
    // Yuborilganini adminga aytish (ixtiyoriy)
    try {
        $adminChatId = getenv('TELEGRAM_CHANNEL_ID');
        $botToken = getenv('TELEGRAM_BOT_TOKEN');
        if ($adminChatId && $botToken) {
            $msg = "🆕 *Yangi Bloger Arizasi!*\n\n👤 *Ism:* {$name}\n📞 *Telefon:* {$phone}\n\n_Iltimos, admin panel orqali tasdiqlang._";
            $ch = curl_init("https://api.telegram.org/bot{$botToken}/sendMessage");
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query(['chat_id' => $adminChatId, 'text' => $msg, 'parse_mode' => 'Markdown']),
                CURLOPT_RETURNTRANSFER => true, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_TIMEOUT => 5,
            ]);
            curl_exec($ch); curl_close($ch);
        }
    } catch (Exception $e) {}

    jsonResponse([
        'success' => true,
        'message' => 'Ariza qabul qilindi',
        'token' => $token,
        'status' => 'pending'
    ]);
}

function handleLogin($input) {
    $phone = sanitize(trim($input['phone'] ?? ''));
    $password = $input['password'] ?? '';

    if (empty($phone) || empty($password)) {
        jsonResponse(['error' => 'Telefon va parolni kiriting'], 400);
    }

    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM bloggers WHERE phone = ?");
    $stmt->execute([$phone]);
    $blogger = $stmt->fetch();

    if (!$blogger) {
        password_verify($password, '$2y$12$x0000000000000000000000000000000000000000000000000000');
        jsonResponse(['error' => 'Telefon raqam yoki parol xato'], 401);
    }

    if (!password_verify($password, $blogger['password_hash'])) {
        jsonResponse(['error' => 'Telefon raqam yoki parol xato'], 401);
    }

    // Bloklangan bo'lsa
    if ($blogger['status'] === 'blocked') {
        jsonResponse(['error' => 'Profilingiz bloklangan.'], 403);
    }

    $token = createBloggerSession($db, $blogger['id']);
    
    // Update last_active
    $db->prepare("UPDATE bloggers SET last_active = datetime('now') WHERE id = ?")->execute([$blogger['id']]);

    jsonResponse([
        'success' => true,
        'blogger' => [
            'id' => (int)$blogger['id'],
            'display_name' => $blogger['display_name'],
            'phone' => $blogger['phone'],
            'status' => $blogger['status']
        ],
        'token' => $token,
    ]);
}

function handleCheck() {
    $token = $_SERVER['HTTP_X_BLOGGER_TOKEN'] ?? '';
    if (empty($token)) { jsonResponse(['authenticated' => false]); }
    
    $db = getDB();
    $now = date('Y-m-d H:i:s');
    $stmt = $db->prepare("SELECT b.* FROM bloggers b JOIN blogger_sessions s ON b.id = s.blogger_id WHERE s.token = ? AND s.expires_at > ?");
    $stmt->execute([$token, $now]);
    $blogger = $stmt->fetch();
    
    if (!$blogger) { jsonResponse(['authenticated' => false]); }
    
    jsonResponse([
        'authenticated' => true,
        'blogger' => [
            'id' => (int)$blogger['id'],
            'display_name' => $blogger['display_name'],
            'phone' => $blogger['phone'],
            'status' => $blogger['status']
        ]
    ]);
}

function handleLogout() {
    $token = $_SERVER['HTTP_X_BLOGGER_TOKEN'] ?? '';
    if ($token) {
        $db = getDB();
        $db->prepare("DELETE FROM blogger_sessions WHERE token = ?")->execute([$token]);
    }
    jsonResponse(['success' => true]);
}
