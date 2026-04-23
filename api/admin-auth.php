<?php
/**
 * Tiba AI — Admin autentifikatsiyasi
 * Login + Google Authenticator 2FA (TOTP)
 * 1:1 Mirror (Replicated for SQLite)
 */
require_once __DIR__ . '/config.php';

// Admin API ham POST bo'lishi kerak
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'POST required'], 405);
}

$input = getInput();
$action = $input['action'] ?? '';

switch ($action) {
    case 'login':
        handleAdminLogin($input);
        break;
    case 'verify-2fa':
        handleVerify2FA($input);
        break;
    case 'get-qr':
        handleGetQR($input);
        break;
    default:
        jsonResponse(['error' => 'Noto\'g\'ri amal'], 400);
}

// Redefining the default secret to a strictly RFC 4648 compliant Base32 string (A-Z, 2-7)
function getSecret() {
    return getenv('ADMIN_2FA_SECRET') ?: 'LFLF2B3X4Y5Z6A7B'; 
}

function handleGetQR($input) {
    $tempToken = $input['tempToken'] ?? '';
    // Verify valid PENDING session
    $db = getDB();
    $stmt = $db->prepare("SELECT id FROM admin_sessions WHERE token = ? AND status = 'pending' AND expires_at > ?");
    $stmt->execute([$tempToken, date('Y-m-d H:i:s')]);
    if (!$stmt->fetch()) {
        jsonResponse(['error' => 'Unauthorized or expired session. Please login again.'], 401);
    }

    $secret = getSecret();
    $user = getenv('ADMIN_USER') ?: 'admin';
    $label = "TibaAI:$user";
    $chl = "otpauth://totp/$label?secret=$secret&issuer=TibaAI";
    
    // Generate QR locally or use a trusted service. For simplicity/portability, keeping charts.
    $qr = "https://quickchart.io/chart?cht=qr&chs=250x250&chl=" . urlencode($chl);
    
    jsonResponse([
        'success' => true,
        'qr' => $qr, 
        'secret' => $secret
    ]);
}

function handleAdminLogin($input) {
    $username = sanitize($input['username'] ?? '');
    $password = $input['password'] ?? '';

    // Brute-force himoya (10 urinish, 10 daqiqa blok)
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $lockFile = __DIR__ . '/../tmp/rate_limits/admin_' . md5($ip) . '.json';
    $lockData = ['attempts' => 0, 'locked_until' => 0];
    if (file_exists($lockFile)) {
        $lockData = json_decode(file_get_contents($lockFile), true) ?: $lockData;
    }

    if ($lockData['locked_until'] > time()) {
        $remaining = ceil(($lockData['locked_until'] - time()) / 60);
        jsonResponse(['error' => "Juda ko'p urinish. {$remaining} daqiqa kutib turing."], 429);
    }

    $adminUser = getenv('ADMIN_USER') ?: 'admin';
    $adminPass = getenv('ADMIN_PASS') ?: 'tibaai2024';

    if ($username !== $adminUser || $password !== $adminPass) {
        $lockData['attempts'] = ($lockData['attempts'] ?? 0) + 1;
        if ($lockData['attempts'] >= 10) {
            $lockData['locked_until'] = time() + 600; // 10 daqiqa
            $lockData['attempts'] = 0;
        }
        $dir = dirname($lockFile);
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        file_put_contents($lockFile, json_encode($lockData));
        jsonResponse(['error' => 'Login yoki parol xato'], 401);
    }

    // Muvaffaqiyatli login — urinishlarni tozalash
    if (file_exists($lockFile)) unlink($lockFile);

    $db = getDB();
    
    // Check if previous migration ran (column 'status' exists). If not, we should rely on config updates.
    // Assuming migration ran.
    
    $stmt = $db->prepare("SELECT data FROM configs WHERE id = '2fa_setup'");
    $stmt->execute();
    $setup = $stmt->fetch();
    $isSetup = ($setup && $setup['data'] === 'complete');

    // Create a PENDING session token (valid for 5 mins)
    $tempToken = bin2hex(random_bytes(32));
    try {
        $expiresAt = date('Y-m-d H:i:s', strtotime('+5 minutes'));
        $stmt = $db->prepare("INSERT INTO admin_sessions (token, status, expires_at) VALUES (?, 'pending', ?)");
        $stmt->execute([$tempToken, $expiresAt]);
        // Cleanup old
        $db->exec("DELETE FROM admin_sessions WHERE expires_at < '" . date('Y-m-d H:i:s') . "'");

        jsonResponse([
            'success' => true, 
            'step' => $isSetup ? '2fa' : 'setup',
            'tempToken' => $tempToken
        ]);
    } catch (Exception $e) {
        jsonResponse(['error' => 'Database error: ' . $e->getMessage()], 500);
    }
}

function handleVerify2FA($input) {
    $tempToken = $input['tempToken'] ?? ($input['token'] ?? ''); // flexible
    $code = $input['code'] ?? ''; // Expect 'code', handled old client might use token param slightly diff
    
    // Backward compat check: if user sends token as code in 'token' field? No, strict input.
    // Assume new client sends { tempToken: '...', code: '123456' }
    
    if (empty($code) && isset($input['token']) && strlen($input['token']) === 6) {
        // Old client style maybe? But we are updating client.
        $code = $input['token'];
    }

    if (empty($tempToken)) {
        jsonResponse(['error' => 'Session token missing'], 400);
    }

    if (empty($code) || strlen($code) !== 6) {
        jsonResponse(['error' => '6 xonali kodni kiriting'], 400);
    }

    $db = getDB();
    $stmt = $db->prepare("SELECT id FROM admin_sessions WHERE token = ? AND status = 'pending' AND expires_at > ?");
    $stmt->execute([$tempToken, date('Y-m-d H:i:s')]);
    $session = $stmt->fetch();

    if (!$session) {
        jsonResponse(['error' => 'Kod xato yoki sessiya muddati o\'tgan. Qayta login qiling.'], 401);
    }

    $secret = getSecret();

    // Increased window to 2 (total 5 cycles = 2.5 minutes) to avoid time drift issues
    $valid = verifyTOTP($secret, $code, 2);

    if (!$valid) {
        jsonResponse(['error' => 'Kod xato (Vaqtni tekshiring)'], 401);
    }

    // Upgrade session to ACTIVE
    $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));
    $stmt = $db->prepare("UPDATE admin_sessions SET status = 'active', expires_at = ? WHERE id = ?");
    $stmt->execute([$expiresAt, $session['id']]);

    // Mark 2FA as setup if not already
    $stmt = $db->prepare("INSERT OR REPLACE INTO configs (id, data) VALUES ('2fa_setup', 'complete')");
    $stmt->execute();

    jsonResponse([
        'success' => true,
        'sessionToken' => $tempToken, // Use the now-active token
    ]);
}

function verifyTOTP($secret, $code, $window = 1) {
    try {
        $secretDecoded = base32Decode($secret);
    } catch (Exception $e) {
        return false;
    }
    
    $timeSlice = floor(time() / 30);

    for ($i = -$window; $i <= $window; $i++) {
        $time = pack('N*', 0) . pack('N*', $timeSlice + $i);
        $hash = hash_hmac('sha1', $time, $secretDecoded, true);
        $offset = ord($hash[19]) & 0x0F;
        $otp = (
            ((ord($hash[$offset]) & 0x7F) << 24) |
            ((ord($hash[$offset + 1]) & 0xFF) << 16) |
            ((ord($hash[$offset + 2]) & 0xFF) << 8) |
            (ord($hash[$offset + 3]) & 0xFF)
        ) % 1000000;

        if (str_pad($otp, 6, '0', STR_PAD_LEFT) === $code) {
            return true;
        }
    }
    return false;
}

function base32Decode($input) {
    $map = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $input = strtoupper(rtrim($input, '='));
    $buffer = 0;
    $bitsLeft = 0;
    $result = '';

    for ($i = 0; $i < strlen($input); $i++) {
        $val = strpos($map, $input[$i]);
        if ($val === false) continue;
        $buffer = ($buffer << 5) | $val;
        $bitsLeft += 5;
        if ($bitsLeft >= 8) {
            $bitsLeft -= 8;
            $result .= chr(($buffer >> $bitsLeft) & 0xFF);
        }
    }
    return $result;
}
