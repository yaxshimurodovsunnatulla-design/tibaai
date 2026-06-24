<?php
/**
 * Tiba Optom — Auth API
 * register, login, check, logout
 */
require_once __DIR__ . '/config.php';

if (basename($_SERVER['SCRIPT_FILENAME']) === 'optom-auth.php') {
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
}

function createOptomSession($db, $sellerId) {
    $token = bin2hex(random_bytes(64));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));
    $now = date('Y-m-d H:i:s');
    
    $db->prepare("INSERT INTO optom_sessions (seller_id, token, expires_at) VALUES (?, ?, ?)")
       ->execute([$sellerId, $token, $expiresAt]);
       
    // Eski sessiyalarni tozalash
    $db->exec("DELETE FROM optom_sessions WHERE expires_at < '$now'");
    $db->prepare("DELETE FROM optom_sessions WHERE seller_id = ? AND id NOT IN (SELECT id FROM optom_sessions WHERE seller_id = ? ORDER BY created_at DESC LIMIT 5)")
       ->execute([$sellerId, $sellerId]);
       
    return $token;
}

function getOptomSeller() {
    $token = $_SERVER['HTTP_X_OPTOM_TOKEN'] ?? '';
    if (empty($token)) return null;
    
    $db = getDB();
    $stmt = $db->prepare("SELECT s.* FROM optom_sellers s INNER JOIN optom_sessions ss ON s.id = ss.seller_id WHERE ss.token = ? AND ss.expires_at > datetime('now')");
    $stmt->execute([$token]);
    return $stmt->fetch();
}

function handleRegister($input) {
    $companyName = sanitize(trim($input['company_name'] ?? ''));
    $ownerName = sanitize(trim($input['owner_name'] ?? ''));
    $phone = sanitize(trim($input['phone'] ?? ''));
    $email = sanitize(trim($input['email'] ?? ''));
    $password = $input['password'] ?? '';
    $inn = sanitize(trim($input['inn'] ?? ''));
    $city = sanitize(trim($input['city'] ?? 'Toshkent'));
    $categories = $input['categories'] ?? [];
    $description = sanitize(trim($input['description'] ?? ''));
    $address = sanitize(trim($input['address'] ?? ''));
    $minOrder = (int)($input['min_order_amount'] ?? 0);
    
    // Validatsiya
    if (empty($companyName) || empty($ownerName) || empty($phone) || empty($password)) {
        jsonResponse(['error' => 'Kompaniya nomi, egasi ismi, telefon va parol majburiy'], 400);
    }
    
    if (strlen($password) < 6) {
        jsonResponse(['error' => 'Parol kamida 6 belgi bo\'lishi kerak'], 400);
    }
    
    if (mb_strlen($companyName) < 2) {
        jsonResponse(['error' => 'Kompaniya nomi kamida 2 belgidan iborat bo\'lishi kerak'], 400);
    }

    $db = getDB();
    
    // Telefon mavjudligini tekshirish
    $stmt = $db->prepare("SELECT id FROM optom_sellers WHERE phone = ?");
    $stmt->execute([$phone]);
    if ($stmt->fetch()) {
        jsonResponse(['error' => 'Bu telefon raqam allaqachon ro\'yxatdan o\'tgan'], 409);
    }

    $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    $categoriesJson = json_encode(is_array($categories) ? $categories : [], JSON_UNESCAPED_UNICODE);
    
    $db->prepare("INSERT INTO optom_sellers (company_name, owner_name, phone, email, password_hash, inn, city, categories, description, address, min_order_amount, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')")
       ->execute([$companyName, $ownerName, $phone, $email, $hash, $inn, $city, $categoriesJson, $description, $address, $minOrder]);
       
    $sellerId = $db->lastInsertId();
    $token = createOptomSession($db, $sellerId);
    
    // Telegramga xabar yuborish
    try {
        $adminChatId = getenv('TELEGRAM_CHANNEL_ID');
        $botToken = getenv('TELEGRAM_BOT_TOKEN');
        if ($adminChatId && $botToken) {
            $msg = "📦 *Yangi Ulgurji Sotuvchi Arizasi!*\n\n🏢 *Kompaniya:* {$companyName}\n👤 *Egasi:* {$ownerName}\n📞 *Telefon:* {$phone}\n🏙 *Shahar:* {$city}\n\n_Iltimos, admin panel orqali tasdiqlang._";
            $ch = curl_init("https://api.telegram.org/bot{$botToken}/sendMessage");
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode([
                    'chat_id' => $adminChatId,
                    'text' => $msg,
                    'parse_mode' => 'Markdown',
                ]),
                CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 5,
            ]);
            curl_exec($ch);
            curl_close($ch);
        }
    } catch (Exception $e) {}
    
    jsonResponse([
        'success' => true,
        'token' => $token,
        'seller' => ['id' => $sellerId, 'company_name' => $companyName, 'status' => 'pending'],
        'message' => 'Arizangiz qabul qilindi. Moderatsiyadan so\'ng tizimga kira olasiz.'
    ]);
}

function handleLogin($input) {
    $phone = sanitize(trim($input['phone'] ?? ''));
    $password = $input['password'] ?? '';
    
    if (empty($phone) || empty($password)) {
        jsonResponse(['error' => 'Telefon va parol kiritilishi shart'], 400);
    }

    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM optom_sellers WHERE phone = ?");
    $stmt->execute([$phone]);
    $seller = $stmt->fetch();
    
    if (!$seller || !password_verify($password, $seller['password_hash'])) {
        jsonResponse(['error' => 'Telefon yoki parol noto\'g\'ri'], 401);
    }
    
    if ($seller['status'] === 'blocked') {
        jsonResponse(['error' => 'Hisobingiz bloklangan. Qo\'llab-quvvatlash xizmatiga murojaat qiling.'], 403);
    }
    
    $token = createOptomSession($db, $seller['id']);
    
    unset($seller['password_hash']);
    
    jsonResponse([
        'success' => true,
        'token' => $token,
        'seller' => $seller
    ]);
}

function handleCheck() {
    $seller = getOptomSeller();
    
    if (!$seller) {
        jsonResponse(['authenticated' => false], 401);
    }
    
    unset($seller['password_hash']);
    
    // Statistikani olish
    $db = getDB();
    $productCount = $db->prepare("SELECT COUNT(*) FROM optom_products WHERE seller_id = ?");
    $productCount->execute([$seller['id']]);
    
    $orderCount = $db->prepare("SELECT COUNT(*) FROM optom_orders WHERE seller_id = ? AND status = 'new'");
    $orderCount->execute([$seller['id']]);
    
    jsonResponse([
        'authenticated' => true,
        'seller' => $seller,
        'stats' => [
            'products' => (int)$productCount->fetchColumn(),
            'new_orders' => (int)$orderCount->fetchColumn(),
        ]
    ]);
}

function handleLogout() {
    $token = $_SERVER['HTTP_X_OPTOM_TOKEN'] ?? '';
    if (!empty($token)) {
        $db = getDB();
        $db->prepare("DELETE FROM optom_sessions WHERE token = ?")->execute([$token]);
    }
    jsonResponse(['success' => true]);
}
