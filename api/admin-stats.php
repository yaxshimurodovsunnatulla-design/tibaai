<?php
/**
 * Tiba AI — Admin Statistics & Management API
 * Dashboard, galeriya, loglar va tozalash uchun
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'POST required'], 405);
}

$input = getInput();
$action = $input['action'] ?? '';

// Admin sessiya tekshiruvi
$sessionToken = $_SERVER['HTTP_X_ADMIN_SESSION'] ?? '';
if (empty($sessionToken)) {
    jsonResponse(['error' => 'Sessiya topilmadi'], 401);
}

$db = getDB();
// Strict session check: MUST be active (Toshkent vaqti bilan)
$stmt = $db->prepare("SELECT * FROM admin_sessions WHERE token = ? AND status = 'active' AND expires_at > ?");
$stmt->execute([$sessionToken, date('Y-m-d H:i:s')]);
if (!$stmt->fetch()) {
    jsonResponse(['error' => 'Sessiya muddati tugagan yoki tasdiqlanmagan'], 401);
}

switch ($action) {
    case 'dashboard':
        handleDashboard($db);
        break;
    case 'gallery':
        handleGallery($input);
        break;
    case 'delete-image':
        handleDeleteImage($input);
        break;
    case 'cleanup':
        handleCleanup($input);
        break;
    case 'logs':
        handleLogs($input);
        break;
    case 'system-info':
        handleSystemInfo($db);
        break;
    case 'users':
        handleUsers($db);
        break;
    case 'delete-user':
        handleDeleteUser($db, $input);
        break;
    case 'get-payment-settings':
        handleGetPaymentSettings();
        break;
    case 'save-payment-settings':
        handleSavePaymentSettings($input);
        break;
    case 'maintenance-toggle':
        handleMaintenanceToggle($input, $sessionToken);
        break;
    case 'maintenance-status':
        handleMaintenanceStatus();
        break;
    case 'all-activity':
        handleAllActivity($db);
        break;
    case 'payments':
        handlePayments($db);
        break;
    case 'approve-payment':
        handleApprovePayment($db, $input);
        break;
    case 'reject-payment':
        handleRejectPayment($db, $input);
        break;
    case 'view-receipt':
        handleViewReceipt($input);
        break;
    case 'cleanup-receipts':
        handleCleanupReceipts();
        break;
    default:
        jsonResponse(['error' => 'Noma\'lum action'], 400);
}

function handleUsers($db) {
    $stmt = $db->prepare("
        SELECT 
            u.id, u.name, u.email, u.balance, u.telegram_id, u.google_id, u.created_at,
            COUNT(DISTINCT s.id) as session_count,
            (SELECT COUNT(*) FROM generations g WHERE g.user_id = u.id) as generation_count,
            (SELECT COUNT(*) FROM payments p WHERE p.user_id = u.id AND p.status = 'approved') as payment_count,
            (SELECT COALESCE(SUM(p2.amount), 0) FROM payments p2 WHERE p2.user_id = u.id AND p2.status = 'approved') as total_spent,
            (SELECT COALESCE(SUM(p3.credits), 0) FROM payments p3 WHERE p3.user_id = u.id AND p3.status = 'approved') as total_credits_bought,
            (SELECT MAX(s2.created_at) FROM user_sessions s2 WHERE s2.user_id = u.id) as last_active
        FROM users u 
        LEFT JOIN user_sessions s ON u.id = s.user_id 
        GROUP BY u.id 
        ORDER BY u.created_at DESC
    ");
    $stmt->execute();
    $users = $stmt->fetchAll();
    
    // Get total stats
    $totalUsers = count($users);
    $totalBalance = array_sum(array_column($users, 'balance'));
    $totalGenerations = array_sum(array_column($users, 'generation_count'));
    $totalRevenue = array_sum(array_column($users, 'total_spent'));
    
    jsonResponse([
        'users' => $users,
        'stats' => [
            'total_users' => $totalUsers,
            'total_balance' => $totalBalance,
            'total_generations' => $totalGenerations,
            'total_revenue' => $totalRevenue,
        ]
    ]);
}

function handleDeleteUser($db, $input) {
    $id = (int)($input['id'] ?? 0);
    if ($id <= 0) jsonResponse(['error' => 'ID topilmadi'], 400);
    
    // Delete sessions first
    $stmt = $db->prepare("DELETE FROM user_sessions WHERE user_id = ?");
    $stmt->execute([$id]);
    
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    
    jsonResponse(['success' => true, 'message' => 'Foydalanuvchi o\'chirildi']);
}

// ========== PAYMENT SETTINGS ==========

function handleGetPaymentSettings() {
    $envFile = __DIR__ . '/../.env';
    $settings = [
        'card_number' => '',
        'card_holder' => '',
        'admin_chat_id' => '',
        'click_enabled' => getSetting('click_enabled', '1'),
        'payme_enabled' => getSetting('payme_enabled', '1'),
    ];

    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || $line[0] === '#') continue;
            if (strpos($line, '=') === false) continue;
            [$key, $val] = explode('=', $line, 2);
            $key = trim($key);
            $val = trim($val);
            if ($key === 'PAYMENT_CARD_NUMBER') $settings['card_number'] = $val;
            if ($key === 'PAYMENT_CARD_HOLDER') $settings['card_holder'] = $val;
            if ($key === 'PAYMENT_ADMIN_CHAT_ID') $settings['admin_chat_id'] = $val;
        }
    }

    jsonResponse(['success' => true, 'settings' => $settings]);
}

function handleSavePaymentSettings($input) {
    $envFile = __DIR__ . '/../.env';
    $cardNumber = trim($input['card_number'] ?? '');
    $cardHolder = trim($input['card_holder'] ?? '');
    $adminChatId = trim($input['admin_chat_id'] ?? '');

    // Click/Payme toggle
    if (isset($input['click_enabled'])) {
        setSetting('click_enabled', $input['click_enabled'] ? '1' : '0');
    }
    if (isset($input['payme_enabled'])) {
        setSetting('payme_enabled', $input['payme_enabled'] ? '1' : '0');
    }

    if (empty($cardNumber)) {
        jsonResponse(['error' => 'Karta raqamini kiriting'], 400);
    }

    if (!file_exists($envFile)) {
        jsonResponse(['error' => '.env fayl topilmadi'], 500);
    }

    // Line-by-line yondashuv (Windows \r\n muammolarini hal qiladi)
    $lines = file($envFile, FILE_IGNORE_NEW_LINES);
    $updates = [
        'PAYMENT_CARD_NUMBER' => $cardNumber,
        'PAYMENT_CARD_HOLDER' => $cardHolder,
        'PAYMENT_ADMIN_CHAT_ID' => $adminChatId,
    ];
    $found = [];

    foreach ($lines as &$line) {
        $trimmed = trim($line);
        if (empty($trimmed) || $trimmed[0] === '#') continue;
        if (strpos($trimmed, '=') === false) continue;
        [$key] = explode('=', $trimmed, 2);
        $key = trim($key);
        if (isset($updates[$key])) {
            $line = "$key=" . $updates[$key];
            $found[$key] = true;
        }
    }
    unset($line);

    foreach ($updates as $key => $val) {
        if (!isset($found[$key])) {
            $lines[] = "$key=$val";
        }
    }

    $result = file_put_contents($envFile, implode("\n", $lines) . "\n");
    if ($result === false) {
        error_log("Failed to write to .env file at $envFile");
        jsonResponse(['error' => '.env fayliga yozib bo\'lmadi. Ruxsatlarni tekshiring.'], 500);
    }

    // Reload env
    $_ENV['PAYMENT_CARD_NUMBER'] = $cardNumber;
    $_ENV['PAYMENT_CARD_HOLDER'] = $cardHolder;
    $_ENV['PAYMENT_ADMIN_CHAT_ID'] = $adminChatId;

    jsonResponse(['success' => true, 'message' => "To'lov sozlamalari saqlandi"]);
}

/**
 * Dashboard — umumiy statistika
 */
function handleDashboard($db) {
    $genDir = __DIR__ . '/../generated';
    $rateLimitDir = __DIR__ . '/../tmp/rate_limits';
    $today = date('Y-m-d');
    $now = date('Y-m-d H:i:s');
    
    // ============ RASMLAR STATISTIKASI ============
    $totalImages = 0;
    $totalSize = 0;
    $todayImages = 0;
    $weekImages = 0;
    $weekStart = date('Y-m-d', strtotime('-7 days'));
    
    if (is_dir($genDir)) {
        $files = glob($genDir . '/*.{png,jpg,jpeg,webp}', GLOB_BRACE);
        if ($files) {
            $totalImages = count($files);
            foreach ($files as $f) {
                $totalSize += filesize($f);
                $fDate = date('Y-m-d', filemtime($f));
                if ($fDate === $today) $todayImages++;
                if ($fDate >= $weekStart) $weekImages++;
            }
        }
    }
    
    // ============ FOYDALANUVCHILAR ============
    $totalUsers = (int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $todayUsers = (int)$db->query("SELECT COUNT(*) FROM users WHERE date(created_at) = '$today'")->fetchColumn();
    $totalBalance = (int)$db->query("SELECT COALESCE(SUM(balance), 0) FROM users")->fetchColumn();
    $telegramUsers = (int)$db->query("SELECT COUNT(*) FROM users WHERE telegram_id IS NOT NULL AND telegram_id != ''")->fetchColumn();
    
    // ============ TO'LOVLAR / MOLIYA ============
    $totalRevenue = (int)$db->query("SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'approved'")->fetchColumn();
    $todayRevenue = (int)$db->query("SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'approved' AND date(created_at) = '$today'")->fetchColumn();
    $pendingPayments = (int)$db->query("SELECT COUNT(*) FROM payments WHERE status = 'pending'")->fetchColumn();
    $pendingAmount = (int)$db->query("SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'pending'")->fetchColumn();
    $approvedToday = (int)$db->query("SELECT COUNT(*) FROM payments WHERE status = 'approved' AND date(created_at) = '$today'")->fetchColumn();
    $totalPayments = (int)$db->query("SELECT COUNT(*) FROM payments WHERE status = 'approved'")->fetchColumn();
    $totalCreditsSold = (int)$db->query("SELECT COALESCE(SUM(credits), 0) FROM payments WHERE status = 'approved'")->fetchColumn();
    
    // ============ GENERATSIYALAR (DB) ============
    $totalGens = (int)$db->query("SELECT COUNT(*) FROM generations")->fetchColumn();
    $todayGens = (int)$db->query("SELECT COUNT(*) FROM generations WHERE date(created_at) = '$today'")->fetchColumn();
    
    // ============ SO'ROVLAR (rate limits) ============
    $activeUsers = 0;
    $todayRequests = 0;
    if (is_dir($rateLimitDir)) {
        $rlFiles = glob($rateLimitDir . '/*.json');
        if ($rlFiles) {
            foreach ($rlFiles as $rlf) {
                if (strpos(basename($rlf), 'admin_') === 0) continue;
                $data = json_decode(file_get_contents($rlf), true);
                if ($data) {
                    $activeUsers++;
                    $todayCount = count(array_filter($data['daily'] ?? [], fn($d) => $d === $today));
                    $todayRequests += $todayCount;
                }
            }
        }
    }
    
    // ============ HAFTALIK CHART DATA (oxirgi 7 kun) ============
    $weeklyChart = [];
    for ($i = 6; $i >= 0; $i--) {
        $d = date('Y-m-d', strtotime("-$i days"));
        $dayLabel = date('D', strtotime($d)); // Mon, Tue...
        $dayGens = (int)$db->query("SELECT COUNT(*) FROM generations WHERE date(created_at) = '$d'")->fetchColumn();
        $dayUsers = (int)$db->query("SELECT COUNT(*) FROM users WHERE date(created_at) = '$d'")->fetchColumn();
        $dayRevenue = (int)$db->query("SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'approved' AND date(created_at) = '$d'")->fetchColumn();
        
        $weeklyChart[] = [
            'date' => $d,
            'day' => $dayLabel,
            'generations' => $dayGens,
            'users' => $dayUsers,
            'revenue' => $dayRevenue,
        ];
    }
    
    // ============ SO'NGGI FAOLIYATLAR ============
    $recentActivity = [];
    
    // Oxirgi 5 ta generatsiya
    $stmt = $db->query("SELECT g.id, g.created_at, g.prompt_data, u.name as user_name FROM generations g LEFT JOIN users u ON g.user_id = u.id ORDER BY g.created_at DESC LIMIT 5");
    while ($row = $stmt->fetch()) {
        $pData = json_decode($row['prompt_data'] ?? '{}', true);
        $recentActivity[] = [
            'type' => 'generation',
            'icon' => '🎨',
            'text' => ($row['user_name'] ?? 'Noma\'lum') . ' rasm yaratdi' . (isset($pData['style']) ? ' (' . $pData['style'] . ')' : ''),
            'time' => $row['created_at'],
        ];
    }
    
    // Oxirgi 5 ta to'lov
    $stmt = $db->query("SELECT p.*, u.name as user_name FROM payments p LEFT JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC LIMIT 5");
    while ($row = $stmt->fetch()) {
        $statusIcon = $row['status'] === 'approved' ? '✅' : ($row['status'] === 'pending' ? '⏳' : '❌');
        $recentActivity[] = [
            'type' => 'payment',
            'icon' => $statusIcon,
            'text' => ($row['user_name'] ?? 'Noma\'lum') . ' — ' . number_format($row['amount'], 0, '.', ' ') . " so'm (" . $row['credits'] . " tanga)",
            'time' => $row['created_at'],
            'status' => $row['status'],
        ];
    }
    
    // Oxirgi 3 ta yangi foydalanuvchi
    $stmt = $db->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 3");
    while ($row = $stmt->fetch()) {
        $recentActivity[] = [
            'type' => 'user',
            'icon' => '👤',
            'text' => $row['name'] . ' ro\'yxatdan o\'tdi',
            'time' => $row['created_at'],
        ];
    }
    
    // Vaqt bo'yicha saralash
    usort($recentActivity, fn($a, $b) => strtotime($b['time']) - strtotime($a['time']));
    $recentActivity = array_slice($recentActivity, 0, 10);
    
    // ============ TOP XIZMATLAR ============
    $topServices = [];
    try {
        $stmt = $db->query("SELECT s.name, s.icon, s.gradient, COUNT(g.id) as gen_count FROM services s LEFT JOIN generations g ON json_extract(g.prompt_data, '$.service') = s.slug GROUP BY s.id ORDER BY gen_count DESC LIMIT 5");
        while ($row = $stmt->fetch()) {
            $topServices[] = $row;
        }
    } catch (Exception $e) { /* skip if json_extract fails */ }
    
    // ============ SERVER INFO ============
    $dbFile = __DIR__ . '/../data/tibaai.db';
    $dbSize = file_exists($dbFile) ? filesize($dbFile) : 0;
    
    $stmt = $db->prepare("SELECT COUNT(*) as cnt FROM admin_sessions WHERE expires_at > ? AND status='active'");
    $stmt->execute([$now]);
    $activeSessions = $stmt->fetch()['cnt'] ?? 0;
    
    jsonResponse([
        // Asosiy ko'rsatkichlar
        'totalImages' => $totalImages,
        'todayImages' => $todayImages,
        'weekImages' => $weekImages,
        'todayRequests' => $todayRequests,
        'activeUsers' => $activeUsers,
        
        // Foydalanuvchilar
        'totalUsers' => $totalUsers,
        'todayUsers' => $todayUsers,
        'totalBalance' => $totalBalance,
        'telegramUsers' => $telegramUsers,
        
        // Moliya
        'totalRevenue' => $totalRevenue,
        'todayRevenue' => $todayRevenue,
        'pendingPayments' => $pendingPayments,
        'pendingAmount' => $pendingAmount,
        'approvedToday' => $approvedToday,
        'totalPayments' => $totalPayments,
        'totalCreditsSold' => $totalCreditsSold,
        
        // Generatsiyalar
        'totalGens' => $totalGens,
        'todayGens' => $todayGens,
        
        // Server
        'totalSizeMB' => round($totalSize / 1024 / 1024, 2),
        'dbSizeKB' => round($dbSize / 1024, 1),
        'activeSessions' => $activeSessions,
        'serverTime' => $now,
        'phpVersion' => phpversion(),
        'diskFreeGB' => round(disk_free_space(__DIR__) / 1024 / 1024 / 1024, 2),
        'uptime' => @file_get_contents('/proc/uptime') ? explode(' ', file_get_contents('/proc/uptime'))[0] : null,
        
        // Grafik
        'weeklyChart' => $weeklyChart,
        
        // Faoliyat
        'recentActivity' => $recentActivity,
        
        // Top xizmatlar
        'topServices' => $topServices,
    ]);
}

/**
 * Galeriya — yaratilgan rasmlar ro'yxati
 */
function handleGallery($input) {
    $genDir = __DIR__ . '/../generated';
    $page = max(1, (int)($input['page'] ?? 1));
    $perPage = 20;
    
    if (!is_dir($genDir)) {
        jsonResponse(['images' => [], 'total' => 0, 'pages' => 0]);
    }
    
    $files = glob($genDir . '/*.{png,jpg,jpeg,webp}', GLOB_BRACE);
    if (!$files) $files = [];
    
    // Eng yangilarini birinchi ko'rsatish
    usort($files, fn($a, $b) => filemtime($b) - filemtime($a));
    
    $total = count($files);
    $pages = ceil($total / $perPage);
    $offset = ($page - 1) * $perPage;
    $slice = array_slice($files, $offset, $perPage);
    
    $images = [];
    foreach ($slice as $f) {
        $images[] = [
            'name' => basename($f),
            'url' => '/generated/' . basename($f),
            'size' => round(filesize($f) / 1024, 1),
            'date' => date('Y-m-d H:i', filemtime($f)),
            'ago' => timeAgo(filemtime($f)),
        ];
    }
    
    jsonResponse([
        'images' => $images,
        'total' => $total,
        'pages' => $pages,
        'currentPage' => $page
    ]);
}

/**
 * Rasmni o'chirish
 */
function handleDeleteImage($input) {
    $name = $input['name'] ?? '';
    if (empty($name)) {
        jsonResponse(['error' => 'Fayl nomi kerak'], 400);
    }
    
    // Path traversal himoya
    $safeName = basename($name);
    $path = __DIR__ . '/../generated/' . $safeName;
    
    if (!file_exists($path)) {
        jsonResponse(['error' => 'Fayl topilmadi'], 404);
    }
    
    unlink($path);
    jsonResponse(['success' => true, 'message' => "$safeName o'chirildi"]);
}

/**
 * Eski rasmlarni tozalash
 */
function handleCleanup($input) {
    $days = max(1, (int)($input['days'] ?? 7));
    $genDir = __DIR__ . '/../generated';
    $maxAge = $days * 86400;
    $now = time();
    $deleted = 0;
    $freedBytes = 0;
    
    if (!is_dir($genDir)) {
        jsonResponse(['deleted' => 0, 'freedMB' => 0]);
    }
    
    $files = glob($genDir . '/*.{png,jpg,jpeg,webp}', GLOB_BRACE);
    if ($files) {
        foreach ($files as $f) {
            if (($now - filemtime($f)) > $maxAge) {
                $freedBytes += filesize($f);
                unlink($f);
                $deleted++;
            }
        }
    }
    
    // Rate limit fayllarni ham tozalash (1 kunlik)
    $rlDir = __DIR__ . '/../tmp/rate_limits';
    if (is_dir($rlDir)) {
        $rlFiles = glob($rlDir . '/*.json');
        if ($rlFiles) {
            foreach ($rlFiles as $rlf) {
                if (($now - filemtime($rlf)) > 86400) {
                    unlink($rlf);
                }
            }
        }
    }
    
    jsonResponse([
        'deleted' => $deleted,
        'freedMB' => round($freedBytes / 1024 / 1024, 2),
        'message' => "{$deleted} ta rasm o'chirildi (" . round($freedBytes / 1024 / 1024, 2) . " MB bo'shatildi)"
    ]);
}

/**
 * So'nggi loglar — API so'rovlar
 */
function handleLogs($input) {
    $logFile = __DIR__ . '/../tmp/api_requests.log';
    if (!file_exists($logFile)) {
        jsonResponse(['logs' => [], 'message' => 'Log fayl topilmadi']);
    }
    
    $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $lines = array_reverse($lines); // Eng yangilarini birinchi
    $logs = array_slice($lines, 0, 200); // Oxirgi 200 ta
    
    $parsedLogs = [];
    foreach ($logs as $line) {
        // Format: [$time] $method $uri | $statusCode | IP: $ip
        if (preg_match('/^\[(.*?)\] (\w+) (.*?) \| (\d+) \| IP: (.*)$/', $line, $m)) {
            $parsedLogs[] = [
                'time' => $m[1],
                'method' => $m[2],
                'uri' => $m[3],
                'status' => $m[4],
                'ip' => $m[5],
                'raw' => $line
            ];
        } else {
            $parsedLogs[] = ['raw' => $line, 'time' => '', 'method' => '', 'uri' => '', 'status' => '', 'ip' => ''];
        }
    }
    
    jsonResponse(['logs' => $parsedLogs, 'total' => count($lines)]);
}

/**
 * Tizim ma'lumotlari
 */
function handleSystemInfo($db) {
    $info = [
        'php_version' => phpversion(),
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
        'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? __DIR__,
        'memory_limit' => ini_get('memory_limit'),
        'max_upload' => ini_get('upload_max_filesize'),
        'post_max' => ini_get('post_max_size'),
        'max_execution' => ini_get('max_execution_time'),
        'sqlite_version' => $db->query('SELECT sqlite_version()')->fetchColumn(),
        'disk_free' => round(disk_free_space(__DIR__) / 1024 / 1024 / 1024, 2) . ' GB',
        'extensions' => [
            'curl' => extension_loaded('curl'),
            'pdo_sqlite' => extension_loaded('pdo_sqlite'),
            'json' => extension_loaded('json'),
            'mbstring' => extension_loaded('mbstring'),
            'gd' => extension_loaded('gd'),
        ],
    ];
    
    jsonResponse($info);
}

/**
 * Vaqt farqi hisoblash
 */
function timeAgo($timestamp) {
    $diff = time() - $timestamp;
    if ($diff < 60) return $diff . ' soniya oldin';
    if ($diff < 3600) return floor($diff / 60) . ' daqiqa oldin';
    if ($diff < 86400) return floor($diff / 3600) . ' soat oldin';
    if ($diff < 604800) return floor($diff / 86400) . ' kun oldin';
    return date('d.m.Y', $timestamp);
}

function handleAllActivity($db) {
    $activity = [];

    // Oxirgi 20 ta generatsiya
    $stmt = $db->query("SELECT g.id, g.created_at, g.prompt_data, u.name as user_name FROM generations g LEFT JOIN users u ON g.user_id = u.id ORDER BY g.created_at DESC LIMIT 20");
    while ($row = $stmt->fetch()) {
        $pData = json_decode($row['prompt_data'] ?? '{}', true);
        $activity[] = [
            'type' => 'generation',
            'icon' => '🎨',
            'text' => ($row['user_name'] ?? 'Noma\'lum') . ' rasm yaratdi' . (isset($pData['style']) ? ' (' . $pData['style'] . ')' : ''),
            'time' => $row['created_at'],
        ];
    }

    // Oxirgi 20 ta to'lov
    $stmt = $db->query("SELECT p.*, u.name as user_name FROM payments p LEFT JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC LIMIT 20");
    while ($row = $stmt->fetch()) {
        $statusIcon = $row['status'] === 'approved' ? '✅' : ($row['status'] === 'pending' ? '⏳' : '❌');
        $activity[] = [
            'type' => 'payment',
            'icon' => $statusIcon,
            'text' => ($row['user_name'] ?? 'Noma\'lum') . ' — ' . number_format($row['amount'], 0, '.', ' ') . " so'm (" . $row['credits'] . " tanga)",
            'time' => $row['created_at'],
            'status' => $row['status'],
        ];
    }

    // Oxirgi 10 ta yangi foydalanuvchi
    $stmt = $db->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 10");
    while ($row = $stmt->fetch()) {
        $activity[] = [
            'type' => 'user',
            'icon' => '👤',
            'text' => $row['name'] . ' ro\'yxatdan o\'tdi',
            'time' => $row['created_at'],
        ];
    }

    usort($activity, fn($a, $b) => strtotime($b['time']) - strtotime($a['time']));

    jsonResponse(['success' => true, 'activity' => $activity]);
}

function handlePayments($db) {
    $stmt = $db->query("
        SELECT p.*, u.name as user_name, u.email as user_email, u.balance as user_balance
        FROM payments p 
        LEFT JOIN users u ON p.user_id = u.id
        ORDER BY 
            CASE WHEN p.status = 'pending' THEN 0 ELSE 1 END,
            p.created_at DESC
        LIMIT 200
    ");
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $pending = 0; $approved = 0; $rejected = 0;
    $pendingSum = 0; $approvedSum = 0;
    foreach ($payments as $p) {
        if ($p['status'] === 'pending') { $pending++; $pendingSum += $p['amount']; }
        elseif ($p['status'] === 'approved') { $approved++; $approvedSum += $p['amount']; }
        else { $rejected++; }
    }
    
    jsonResponse([
        'success' => true,
        'payments' => $payments,
        'stats' => [
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
            'pending_sum' => $pendingSum,
            'approved_sum' => $approvedSum,
        ]
    ]);
}

function handleApprovePayment($db, $input) {
    $id = (int)($input['id'] ?? 0);
    if ($id <= 0) jsonResponse(['error' => 'ID topilmadi'], 400);
    
    $stmt = $db->prepare("SELECT * FROM payments WHERE id = ?");
    $stmt->execute([$id]);
    $payment = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$payment) jsonResponse(['error' => 'To\'lov topilmadi'], 404);
    if ($payment['status'] !== 'pending') jsonResponse(['error' => 'Bu to\'lov allaqachon ' . ($payment['status'] === 'approved' ? 'tasdiqlangan' : 'rad etilgan')], 400);
    
    $now = date('Y-m-d H:i:s');
    $stmt = $db->prepare("UPDATE payments SET status = 'approved', admin_note = 'Admin panel orqali tasdiqlandi', updated_at = ? WHERE id = ?");
    $stmt->execute([$now, $id]);
    
    $stmt = $db->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
    $stmt->execute([$payment['credits'], $payment['user_id']]);
    
    $stmt = $db->prepare("SELECT balance FROM users WHERE id = ?");
    $stmt->execute([$payment['user_id']]);
    $newBalance = $stmt->fetchColumn();
    
    jsonResponse([
        'success' => true, 
        'message' => "To'lov #{$id} tasdiqlandi. {$payment['credits']} tanga qo'shildi. Yangi balans: {$newBalance}",
        'new_balance' => $newBalance
    ]);
}

function handleRejectPayment($db, $input) {
    $id = (int)($input['id'] ?? 0);
    $reason = trim($input['reason'] ?? '');
    if ($id <= 0) jsonResponse(['error' => 'ID topilmadi'], 400);
    
    $stmt = $db->prepare("SELECT * FROM payments WHERE id = ?");
    $stmt->execute([$id]);
    $payment = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$payment) jsonResponse(['error' => 'To\'lov topilmadi'], 404);
    if ($payment['status'] !== 'pending') jsonResponse(['error' => 'Bu to\'lov allaqachon ' . ($payment['status'] === 'approved' ? 'tasdiqlangan' : 'rad etilgan')], 400);
    
    $now = date('Y-m-d H:i:s');
    $note = $reason ?: 'Admin panel orqali rad etildi';
    $stmt = $db->prepare("UPDATE payments SET status = 'rejected', admin_note = ?, updated_at = ? WHERE id = ?");
    $stmt->execute([$note, $now, $id]);
    
    jsonResponse([
        'success' => true,
        'message' => "To'lov #{$id} rad etildi"
    ]);
}

function handleViewReceipt($input) {
    $filename = basename($input['filename'] ?? '');
    if (empty($filename)) jsonResponse(['error' => 'Fayl nomi kerak'], 400);
    
    if (!preg_match('/^receipt_\d+_\d+_[a-f0-9]+\.(jpg|jpeg|png|webp)$/i', $filename)) {
        jsonResponse(['error' => 'Noto\'g\'ri fayl nomi'], 400);
    }
    
    $filePath = __DIR__ . '/../data/receipts/' . $filename;
    if (!file_exists($filePath)) {
        jsonResponse(['error' => 'Fayl topilmadi. Chek muddati o\'tgan bo\'lishi mumkin (2 kun).'], 404);
    }
    
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $mimeTypes = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];
    $mime = $mimeTypes[$ext] ?? 'image/jpeg';
    
    $imageData = file_get_contents($filePath);
    $base64 = base64_encode($imageData);
    
    jsonResponse([
        'success' => true,
        'image' => "data:{$mime};base64,{$base64}",
        'filename' => $filename
    ]);
}

function handleCleanupReceipts() {
    $receiptDir = __DIR__ . '/../data/receipts';
    if (!is_dir($receiptDir)) {
        jsonResponse(['success' => true, 'message' => 'Receipts papkasi mavjud emas', 'deleted' => 0]);
    }
    
    $twoDaysAgo = time() - (2 * 24 * 60 * 60);
    $deleted = 0;
    $deletedFiles = [];
    
    $files = glob($receiptDir . '/receipt_*');
    foreach ($files as $file) {
        if (is_file($file) && filemtime($file) < $twoDaysAgo) {
            $deletedFiles[] = basename($file);
            unlink($file);
            $deleted++;
        }
    }
    
    if ($deleted > 0) {
        $db = getDB();
        foreach ($deletedFiles as $fname) {
            $stmt = $db->prepare("UPDATE payments SET receipt_path = NULL WHERE receipt_path = ?");
            $stmt->execute([$fname]);
        }
    }
    
    jsonResponse([
        'success' => true,
        'message' => "{$deleted} ta eski chek o'chirildi",
        'deleted' => $deleted
    ]);
}

// ========== MAINTENANCE MODE ==========

function handleMaintenanceToggle($input, $sessionToken) {
    $enabled = !empty($input['enabled']);
    
    setSetting('maintenance_mode', $enabled ? '1' : '0');
    
    if ($enabled) {
        // Admin uchun bypass cookie o'rnatish (24 soat)
        setcookie('admin_bypass', $sessionToken, [
            'expires' => time() + 86400,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax',
            'secure' => isset($_SERVER['HTTPS']),
        ]);
    } else {
        // Maintenance o'chirilganda cookie ni tozalash
        setcookie('admin_bypass', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
    
    $statusText = $enabled 
        ? "🔧 Texnik ishlar rejimi YOQILDI. Foydalanuvchilar saytga kira olmaydi." 
        : "✅ Texnik ishlar rejimi O'CHIRILDI. Sayt barcha uchun ochiq.";
    
    jsonResponse([
        'success' => true,
        'enabled' => $enabled,
        'message' => $statusText,
    ]);
}

function handleMaintenanceStatus() {
    $enabled = getSetting('maintenance_mode', '0') === '1';
    jsonResponse([
        'success' => true,
        'enabled' => $enabled,
    ]);
}
