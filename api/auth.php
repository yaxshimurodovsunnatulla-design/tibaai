<?php
/**
 * Tiba AI — Xavfsiz Autentifikatsiya
 * 
 * KIRISH USULLARI:
 * 1. Email + Parol (kirish)
 * 2. Email + Parol + OTP (ro'yxatdan o'tish, email tasdiqlash)
 * 3. Google Sign-In (JWT)
 * 
 * HIMOYALAR:
 * - Rate Limiting (IP + email)
 * - bcrypt (cost=12)
 * - Timing-safe comparison
 * - Crypto-random session tokens
 * - OTP brute-force himoyasi
 * - Security headers
 */
require_once __DIR__ . '/config.php';

// === MIDDLEWARE ===
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Faqat POST'], 405);
}

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (stripos($contentType, 'application/json') === false) {
    jsonResponse(['error' => 'Content-Type application/json bo\'lishi kerak'], 415);
}

$input = getInput();
$action = $input['action'] ?? '';
$clientIp = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
if (strpos($clientIp, ',') !== false) $clientIp = trim(explode(',', $clientIp)[0]);

checkAuthRateLimit($clientIp, $action);

switch ($action) {
    case 'send_otp':    handleSendOtp($input); break;
    case 'register':    handleRegister($input); break;
    case 'login':       handleLogin($input); break;
    case 'check': case 'me': handleCheck(); break;
    case 'logout':      handleLogout(); break;
    case 'google_login': handleGoogleLogin($input); break;
    case 'webapp_login': handleWebAppLogin($input); break;
    case 'telegram_login': handleTelegramLogin($input); break;
    default: jsonResponse(['error' => 'Noto\'g\'ri amal'], 400);
}

// ========== XAVFSIZLIK ==========

function checkAuthRateLimit($ip, $action) {
    $dir = __DIR__ . '/../tmp/rate_limits';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    
    $isAuth = in_array($action, ['login', 'register', 'send_otp', 'google_login']);
    $file = $dir . '/' . ($isAuth ? 'auth_' : 'api_') . md5($ip) . '.json';
    $limits = $isAuth ? ['max' => 10, 'window' => 900, 'block' => 1800] : ['max' => 120, 'window' => 60, 'block' => 300];
    
    $data = ['attempts' => [], 'blocked_until' => 0];
    if (file_exists($file)) $data = json_decode(@file_get_contents($file), true) ?: $data;
    
    if ($data['blocked_until'] > time()) {
        $min = ceil(($data['blocked_until'] - time()) / 60);
        jsonResponse(['error' => "Juda ko'p urinish. {$min} daqiqa kutib turing."], 429);
    }
    
    $now = time();
    $data['attempts'] = array_values(array_filter($data['attempts'], fn($t) => ($now - $t) < $limits['window']));
    
    if (count($data['attempts']) >= $limits['max']) {
        $data['blocked_until'] = $now + $limits['block'];
        $data['attempts'] = [];
        file_put_contents($file, json_encode($data));
        jsonResponse(['error' => "Juda ko'p so'rov. Biroz kutib turing."], 429);
    }
    
    $data['attempts'][] = $now;
    file_put_contents($file, json_encode($data));
}

/**
 * Email asosida login urinishlarni tekshirish
 * Brute-force himoyasi: 5 ta xato → 15 daqiqa bloklash
 */
function checkEmailLoginLimit($email) {
    $dir = __DIR__ . '/../tmp/rate_limits';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    
    $file = $dir . '/login_' . md5(strtolower($email)) . '.json';
    $data = ['failures' => 0, 'last_fail' => 0, 'blocked_until' => 0];
    if (file_exists($file)) $data = json_decode(@file_get_contents($file), true) ?: $data;
    
    // Bloklash muddati o'tganmi
    if ($data['blocked_until'] > time()) {
        $min = ceil(($data['blocked_until'] - time()) / 60);
        jsonResponse(['error' => "Akkaunt vaqtincha bloklangan. {$min} daqiqa kutib turing."], 429);
    }
    
    // 30 daqiqadan eski urinishlarni o'chirish
    if ($data['last_fail'] > 0 && (time() - $data['last_fail']) > 1800) {
        $data = ['failures' => 0, 'last_fail' => 0, 'blocked_until' => 0];
        file_put_contents($file, json_encode($data));
    }
}

function recordLoginFailure($email) {
    $dir = __DIR__ . '/../tmp/rate_limits';
    $file = $dir . '/login_' . md5(strtolower($email)) . '.json';
    $data = ['failures' => 0, 'last_fail' => 0, 'blocked_until' => 0];
    if (file_exists($file)) $data = json_decode(@file_get_contents($file), true) ?: $data;
    
    $data['failures']++;
    $data['last_fail'] = time();
    
    // 5 ta xato → 15 daqiqa bloklash
    if ($data['failures'] >= 5) {
        $data['blocked_until'] = time() + 900;
        $data['failures'] = 0;
    }
    
    file_put_contents($file, json_encode($data));
}

function clearLoginFailures($email) {
    $dir = __DIR__ . '/../tmp/rate_limits';
    $file = $dir . '/login_' . md5(strtolower($email)) . '.json';
    if (file_exists($file)) @unlink($file);
}

function createUserSession($db, $userId) {
    $token = bin2hex(random_bytes(64));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));
    $now = date('Y-m-d H:i:s');
    $db->prepare("INSERT INTO user_sessions (user_id, token, expires_at) VALUES (?, ?, ?)")->execute([$userId, $token, $expiresAt]);
    $db->exec("DELETE FROM user_sessions WHERE expires_at < '$now'");
    $db->prepare("DELETE FROM user_sessions WHERE user_id = ? AND id NOT IN (SELECT id FROM user_sessions WHERE user_id = ? ORDER BY created_at DESC LIMIT 5)")->execute([$userId, $userId]);
    return $token;
}

function getUserByToken($db, $token) {
    if (empty($token) || strlen($token) < 32) return null;
    $now = date('Y-m-d H:i:s');
    $stmt = $db->prepare("SELECT u.id, u.name, u.email, u.balance FROM users u JOIN user_sessions s ON u.id = s.user_id WHERE s.token = ? AND s.expires_at > ?");
    $stmt->execute([$token, $now]);
    return $stmt->fetch();
}

// ========== EMAIL YUBORISH ==========

function sendOtpEmail($email, $code) {
    $host = getenv('SMTP_HOST');
    $port = (int)(getenv('SMTP_PORT') ?: 465);
    $user = getenv('SMTP_USER');
    $pass = getenv('SMTP_PASS');
    $from = getenv('SMTP_FROM') ?: $user;
    $fromName = getenv('SMTP_FROM_NAME') ?: 'Tiba AI';
    
    // SMTP sozlanmagan bo'lsa PHP mail() ishlatish
    if (!$host || !$user || !$pass) {
        $subject = "Tiba AI — Tasdiqlash kodi: $code";
        $body = getOtpEmailHtml($code);
        $headers = "From: {$fromName} <{$from}>\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        return @mail($email, $subject, $body, $headers);
    }
    
    // SMTP orqali yuborish
    return sendSmtpEmail($host, $port, $user, $pass, $from, $fromName, $email, "Tiba AI — Tasdiqlash kodi", getOtpEmailHtml($code));
}

function getOtpEmailHtml($code) {
    return '<!DOCTYPE html><html><body style="font-family:Inter,Arial,sans-serif;background:#0a0a0f;color:#fff;padding:40px 20px;">
    <div style="max-width:420px;margin:0 auto;background:linear-gradient(135deg,#12121a,#1a1a2e);border:1px solid rgba(255,255,255,0.1);border-radius:20px;padding:40px;text-align:center;">
        <div style="width:56px;height:56px;background:linear-gradient(135deg,#4f46e5,#7c3aed);border-radius:16px;margin:0 auto 20px;display:flex;align-items:center;justify-content:center;">
            <span style="color:#fff;font-weight:900;font-size:20px;">T</span>
        </div>
        <h2 style="color:#fff;margin:0 0 8px;font-size:22px;">Tasdiqlash kodi</h2>
        <p style="color:#9ca3af;margin:0 0 24px;font-size:14px;">Ro\'yxatdan o\'tishni yakunlash uchun quyidagi kodni kiriting</p>
        <div style="background:rgba(79,70,229,0.15);border:2px solid rgba(79,70,229,0.3);border-radius:16px;padding:20px;margin:0 0 24px;">
            <span style="font-size:36px;font-weight:900;letter-spacing:8px;color:#818cf8;">' . $code . '</span>
        </div>
        <p style="color:#6b7280;font-size:12px;margin:0;">Kod 5 daqiqa ichida amal qiladi.<br>Agar siz bu kodni so\'ramagan bo\'lsangiz, xabarni e\'tiborsiz qoldiring.</p>
    </div></body></html>';
}

function sendSmtpEmail($host, $port, $user, $pass, $from, $fromName, $to, $subject, $htmlBody) {
    $ssl = ($port == 465) ? 'ssl://' : '';
    $socket = @fsockopen($ssl . $host, $port, $errno, $errstr, 10);
    if (!$socket) return false;
    
    $boundary = md5(time());
    
    $commands = [
        null, // read greeting
        "EHLO " . gethostname(),
        "AUTH LOGIN",
        base64_encode($user),
        base64_encode($pass),
        "MAIL FROM:<{$from}>",
        "RCPT TO:<{$to}>",
        "DATA",
    ];
    
    $headers = "From: {$fromName} <{$from}>\r\n";
    $headers .= "To: {$to}\r\n";
    $headers .= "Subject: {$subject}\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "Date: " . date('r') . "\r\n";
    
    foreach ($commands as $cmd) {
        if ($cmd === null) {
            fgets($socket, 512);
        } else {
            fwrite($socket, $cmd . "\r\n");
            $resp = '';
            while ($line = fgets($socket, 512)) {
                $resp .= $line;
                if (substr($line, 3, 1) == ' ' || strlen($line) < 4) break;
            }
        }
    }
    
    fwrite($socket, $headers . "\r\n" . $htmlBody . "\r\n.\r\n");
    fgets($socket, 512);
    fwrite($socket, "QUIT\r\n");
    fclose($socket);
    return true;
}

// ========== HANDLERLAR ==========

/**
 * OTP kod yuborish (email ga)
 */
function handleSendOtp($input) {
    $email = strtolower(trim($input['email'] ?? ''));
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        jsonResponse(['error' => 'To\'g\'ri email manzil kiriting'], 400);
    }
    
    $db = getDB();
    ensureUserTables($db);
    
    // Allaqachon ro'yxatdan o'tganmi
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        jsonResponse(['error' => 'Bu email allaqachon ro\'yxatdan o\'tgan'], 409);
    }
    
    // 2 daqiqa ichida yuborilganmi (spam himoyasi)
    $stmt = $db->prepare("SELECT id FROM otp_codes WHERE phone = ? AND used = 0 AND created_at > datetime('now', '-2 minutes')");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        jsonResponse(['error' => 'Kod allaqachon yuborilgan. 2 daqiqa kutib turing.'], 429);
    }
    
    // 6 xonali kod
    $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    
    // Saqlash (5 daqiqa)
    $expiresAt = date('Y-m-d H:i:s', strtotime('+5 minutes'));
    $now = date('Y-m-d H:i:s');
    $db->prepare("INSERT INTO otp_codes (phone, code, expires_at) VALUES (?, ?, ?)")->execute([$email, $code, $expiresAt]);
    // Eski OTP larni tozalash (prepared statement)
    $db->prepare("DELETE FROM otp_codes WHERE expires_at < ?")->execute([$now]);
    
    // Email yuborish
    $sent = sendOtpEmail($email, $code);
    
    jsonResponse([
        'success' => true,
        'message' => 'Tasdiqlash kodi emailga yuborildi',
    ]);
}

/**
 * Ro'yxatdan o'tish (email + parol + OTP)
 */
function handleRegister($input) {
    $name = sanitize(trim($input['name'] ?? ''));
    $email = strtolower(trim($input['email'] ?? ''));
    $password = $input['password'] ?? '';
    $otpCode = trim($input['otp_code'] ?? '');

    if (empty($name) || mb_strlen($name) < 2 || mb_strlen($name) > 100) {
        jsonResponse(['error' => 'Ism 2-100 belgi bo\'lishi kerak'], 400);
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        jsonResponse(['error' => 'To\'g\'ri email kiriting'], 400);
    }
    if (strlen($password) < 8) {
        jsonResponse(['error' => 'Parol kamida 8 belgi bo\'lishi kerak'], 400);
    }
    if (strlen($password) > 128) {
        jsonResponse(['error' => 'Parol juda uzun (maks 128 belgi)'], 400);
    }
    if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        jsonResponse(['error' => 'Parolda harf va raqam bo\'lishi kerak'], 400);
    }
    if (!preg_match('/^\d{6}$/', $otpCode)) {
        jsonResponse(['error' => 'Tasdiqlash kodi 6 xonali raqam bo\'lishi kerak'], 400);
    }

    $db = getDB();
    ensureUserTables($db);

    // OTP tekshirish
    $now = date('Y-m-d H:i:s');
    $stmt = $db->prepare("SELECT * FROM otp_codes WHERE phone = ? AND code = ? AND used = 0 AND expires_at > ? ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$email, $otpCode, $now]);
    $otp = $stmt->fetch();

    if (!$otp) {
        $db->prepare("UPDATE otp_codes SET attempts = attempts + 1 WHERE phone = ? AND used = 0 AND expires_at > ?")->execute([$email, $now]);
        $stmt = $db->prepare("SELECT attempts FROM otp_codes WHERE phone = ? AND used = 0 ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$email]);
        $check = $stmt->fetch();
        if ($check && $check['attempts'] >= 5) {
            $db->prepare("UPDATE otp_codes SET used = 1 WHERE phone = ? AND used = 0")->execute([$email]);
            jsonResponse(['error' => 'Kod juda ko\'p xato kiritildi. Yangi kod so\'rang.', 'otp_expired' => true], 401);
        }
        jsonResponse(['error' => 'Noto\'g\'ri tasdiqlash kodi'], 401);
    }

    $db->prepare("UPDATE otp_codes SET used = 1 WHERE id = ?")->execute([$otp['id']]);

    // Email band emasligini tekshirish
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        jsonResponse(['error' => 'Bu email allaqachon ro\'yxatdan o\'tgan'], 409);
    }

    $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    $db->prepare("INSERT INTO users (name, email, password_hash, balance) VALUES (?, ?, ?, 10)")->execute([sanitize($name), $email, $hash]);
    $userId = $db->lastInsertId();
    $token = createUserSession($db, $userId);

    jsonResponse([
        'success' => true,
        'user' => ['id' => (int)$userId, 'name' => $name, 'email' => $email, 'balance' => 10],
        'token' => $token,
    ]);
}

/**
 * Email + Parol bilan kirish
 * Himoyalar: email-level brute-force, timing-safe, parol uzunlik limiti
 */
function handleLogin($input) {
    $email = strtolower(trim($input['email'] ?? ''));
    $password = $input['password'] ?? '';

    if (empty($email) || empty($password)) {
        jsonResponse(['error' => 'Email va parolni kiriting'], 400);
    }

    // Parol uzunligi limiti (hash DoS himoyasi)
    if (strlen($password) > 128) {
        jsonResponse(['error' => 'Parol juda uzun'], 400);
    }

    // Email-level brute-force tekshirish
    checkEmailLoginLimit($email);

    $db = getDB();
    ensureUserTables($db);

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        // Timing attack himoyasi — xato bo'lsa ham hash tekshirish
        password_verify($password, '$2y$12$x0000000000000000000000000000000000000000000000000000');
        recordLoginFailure($email);
        jsonResponse(['error' => 'Email yoki parol xato'], 401);
    }

    if (!$user['password_hash'] || !password_verify($password, $user['password_hash'])) {
        recordLoginFailure($email);
        jsonResponse(['error' => 'Email yoki parol xato'], 401);
    }

    // Muvaffaqiyatli — xatolar hisoblagichini tozalash
    clearLoginFailures($email);

    $token = createUserSession($db, $user['id']);

    jsonResponse([
        'success' => true,
        'user' => [
            'id' => (int)$user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'balance' => (int)($user['balance'] ?? 0),
        ],
        'token' => $token,
    ]);
}

/**
 * Sessiya tekshirish
 */
function handleCheck() {
    $token = $_SERVER['HTTP_X_USER_TOKEN'] ?? '';
    if (empty($token)) { jsonResponse(['authenticated' => false]); }
    $db = getDB();
    ensureUserTables($db);
    $user = getUserByToken($db, $token);
    if (!$user) { jsonResponse(['authenticated' => false]); }
    jsonResponse([
        'authenticated' => true,
        'user' => ['id' => (int)$user['id'], 'name' => $user['name'], 'email' => $user['email'], 'balance' => (int)($user['balance'] ?? 0)],
    ]);
}

/**
 * Chiqish
 */
function handleLogout() {
    $token = $_SERVER['HTTP_X_USER_TOKEN'] ?? '';
    if ($token) {
        $db = getDB();
        ensureUserTables($db);
        $db->prepare("DELETE FROM user_sessions WHERE token = ?")->execute([$token]);
    }
    jsonResponse(['success' => true]);
}

// ========== GOOGLE LOGIN ==========

function handleGoogleLogin($input) {
    try {
        $token = $input['token'] ?? '';
        $tokenType = $input['token_type'] ?? 'id_token';
        if (empty($token) || strlen($token) > 4096) {
            jsonResponse(['error' => 'Token topilmadi'], 400);
        }

        $googleClientId = getenv('GOOGLE_CLIENT_ID');

        if ($tokenType === 'id_token') {
            $parts = explode('.', $token);
            if (count($parts) !== 3) jsonResponse(['error' => 'Token noto\'g\'ri'], 401);
            $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);
            if (!$payload || !isset($payload['sub'])) jsonResponse(['error' => 'Token o\'qilmadi'], 401);
            
            $validIssuers = ['accounts.google.com', 'https://accounts.google.com'];
            if (isset($payload['iss']) && !in_array($payload['iss'], $validIssuers)) jsonResponse(['error' => 'Token manba noto\'g\'ri'], 401);
            if ($googleClientId && $googleClientId !== 'YOUR_GOOGLE_CLIENT_ID_HERE' && ($payload['aud'] ?? '') !== $googleClientId) jsonResponse(['error' => 'Token bu sayt uchun emas'], 401);
            if (isset($payload['exp']) && $payload['exp'] < (time() - 300)) jsonResponse(['error' => 'Token muddati tugagan'], 401);

            $googleId = $payload['sub'];
            $email = sanitize($payload['email'] ?? '');
            $name = sanitize(mb_substr($payload['name'] ?? 'Google User', 0, 100));
        } else {
            $ch = curl_init('https://www.googleapis.com/oauth2/v2/userinfo?access_token=' . urlencode($token));
            curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10, CURLOPT_SSL_VERIFYPEER => false]);
            $response = curl_exec($ch); $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
            if (!$response || $httpCode !== 200) jsonResponse(['error' => 'Google API xato'], 401);
            $payload = json_decode($response, true);
            if (!$payload || !isset($payload['id'])) jsonResponse(['error' => 'Google ma\'lumotlar xato'], 401);
            $googleId = $payload['id']; $email = sanitize($payload['email'] ?? ''); $name = sanitize($payload['name'] ?? 'Google User');
        }

        $db = getDB(); ensureUserTables($db);
        $stmt = $db->prepare("SELECT * FROM users WHERE google_id = ?"); $stmt->execute([$googleId]); $user = $stmt->fetch();
        if (!$user && $email) {
            $stmt = $db->prepare("SELECT * FROM users WHERE email = ?"); $stmt->execute([$email]); $user = $stmt->fetch();
            if ($user) $db->prepare("UPDATE users SET google_id = ? WHERE id = ?")->execute([$googleId, $user['id']]);
        }
        if (!$user) {
            $db->prepare("INSERT INTO users (name, email, google_id, balance) VALUES (?, ?, ?, 10)")->execute([$name, $email, $googleId]);
            $userId = $db->lastInsertId();
            $stmt = $db->prepare("SELECT * FROM users WHERE id = ?"); $stmt->execute([$userId]); $user = $stmt->fetch();
        }

        $sessionToken = createUserSession($db, $user['id']);
        jsonResponse([
            'success' => true,
            'user' => ['id' => (int)$user['id'], 'name' => $user['name'], 'email' => $user['email'] ?? $email, 'balance' => (int)($user['balance'] ?? 0)],
            'token' => $sessionToken,
        ]);
    } catch (Exception $e) {
        jsonResponse(['error' => 'Google kirish xatosi'], 500);
    }
}

// ========== WEBAPP / TELEGRAM (eskisi, mos bo'lish uchun) ==========

function handleWebAppLogin($input) {
    try {
        $initData = $input['initData'] ?? '';
        if (!$initData) jsonResponse(['error' => 'initData topilmadi'], 400);
        $bot_token = getenv('TELEGRAM_BOT_TOKEN');
        parse_str($initData, $data);
        $check_hash = $data['hash'] ?? ''; unset($data['hash']); ksort($data);
        $pairs = []; foreach ($data as $k => $v) $pairs[] = "$k=$v";
        $secret = hash_hmac('sha256', $bot_token, "WebAppData", true);
        $hash = hash_hmac('sha256', implode("\n", $pairs), $secret);
        if (!hash_equals($hash, $check_hash)) jsonResponse(['error' => 'Ma\'lumotlar tasdiqlanmadi'], 403);
        $userData = json_decode($data['user'] ?? '{}', true);
        if (!$userData || !isset($userData['id'])) jsonResponse(['error' => 'Foydalanuvchi xato'], 400);
        $db = getDB(); ensureUserTables($db);
        $tgId = (string)$userData['id'];
        $name = sanitize(mb_substr(($userData['first_name'] ?? '') . ' ' . ($userData['last_name'] ?? ''), 0, 100));
        $stmt = $db->prepare("SELECT * FROM users WHERE telegram_id = ?"); $stmt->execute([$tgId]); $user = $stmt->fetch();
        if (!$user) {
            $db->prepare("INSERT INTO users (name, telegram_id, balance) VALUES (?, ?, 10)")->execute([$name, $tgId]);
            $stmt = $db->prepare("SELECT * FROM users WHERE id = ?"); $stmt->execute([$db->lastInsertId()]); $user = $stmt->fetch();
        }
        $token = createUserSession($db, $user['id']);
        jsonResponse(['success' => true, 'user' => ['id' => (int)$user['id'], 'name' => $user['name'], 'email' => $user['email'] ?? 'TG:' . $tgId, 'balance' => (int)($user['balance'] ?? 0)], 'token' => $token]);
    } catch (Exception $e) { jsonResponse(['error' => 'WebApp xatosi'], 500); }
}

function handleTelegramLogin($input) {
    try {
        $auth = $input['auth_data'] ?? null;
        if (!$auth || !isset($auth['id'], $auth['hash'])) jsonResponse(['error' => 'Ma\'lumotlar yetarli emas'], 400);
        $bot_token = getenv('TELEGRAM_BOT_TOKEN');
        if (!$bot_token) jsonResponse(['error' => 'Server xatosi'], 500);
        $check_hash = $auth['hash']; $check = $auth; unset($check['hash']);
        $arr = []; foreach ($check as $k => $v) { if (is_scalar($v)) $arr[] = "$k=$v"; } sort($arr);
        $secret = hash('sha256', $bot_token, true);
        $hash = hash_hmac('sha256', implode("\n", $arr), $secret);
        if (!hash_equals($hash, $check_hash)) jsonResponse(['error' => 'Ma\'lumotlar tasdiqlanmadi'], 403);
        if ((time() - (int)($auth['auth_date'] ?? 0)) > 3600) jsonResponse(['error' => 'Sessiya tugagan'], 403);
        $db = getDB(); ensureUserTables($db);
        $tgId = (string)$auth['id'];
        $name = sanitize(mb_substr(($auth['first_name'] ?? '') . ' ' . ($auth['last_name'] ?? ''), 0, 100));
        $stmt = $db->prepare("SELECT * FROM users WHERE telegram_id = ?"); $stmt->execute([$tgId]); $user = $stmt->fetch();
        if (!$user) {
            $db->prepare("INSERT INTO users (name, telegram_id, balance) VALUES (?, ?, 10)")->execute([$name, $tgId]);
            $stmt = $db->prepare("SELECT * FROM users WHERE id = ?"); $stmt->execute([$db->lastInsertId()]); $user = $stmt->fetch();
        }
        $token = createUserSession($db, $user['id']);
        jsonResponse(['success' => true, 'user' => ['id' => (int)$user['id'], 'name' => $user['name'], 'email' => $user['email'] ?? 'TG:' . $tgId, 'balance' => (int)($user['balance'] ?? 0)], 'token' => $token]);
    } catch (Exception $e) { jsonResponse(['error' => 'Telegram xatosi'], 500); }
}
