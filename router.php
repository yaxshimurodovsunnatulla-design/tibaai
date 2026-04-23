<?php
/**
 * Tiba AI – Advanced Router for Pretty URLs
 * 
 * Usage: php -S localhost:8000 router.php
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$filePath = __DIR__ . $uri;

// ========== TEXNIK ISHLAR REJIMI ==========
// Admin sessiyasi bor foydalanuvchilar o'tib ketadi
// API endpoint va statik fayllar ham ishlaydi
$maintenanceBypassPaths = ['/secret', '/api/admin-auth.php', '/api/admin-stats.php', '/api/admin-services.php'];
$isApiCall = (strpos($uri, '/api/') === 0);
$isStaticFile = false;
$staticExts = ['css','js','png','jpg','jpeg','gif','ico','svg','webp','woff','woff2','ttf','eot'];
if ($uri !== '/' && file_exists($filePath) && is_file($filePath)) {
    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    if (in_array($ext, $staticExts)) $isStaticFile = true;
}

$isBypass = in_array($uri, $maintenanceBypassPaths) || $isStaticFile;

if (!$isBypass) {
    require_once __DIR__ . '/api/config.php';
    $maintenanceOn = getSetting('maintenance_mode', '0') === '1';
    
    if ($maintenanceOn) {
        // Admin sessiyasi tekshiruvi — faqat cookie/session orqali
        $isAdmin = false;
        $adminToken = $_COOKIE['admin_bypass'] ?? '';
        if (!empty($adminToken)) {
            try {
                $db = getDB();
                $stmt = $db->prepare("SELECT id FROM admin_sessions WHERE token = ? AND status = 'active' AND expires_at > datetime('now')");
                $stmt->execute([$adminToken]);
                if ($stmt->fetch()) $isAdmin = true;
            } catch (Exception $e) { /* ignore */ }
        }
        
        if (!$isAdmin && !$isApiCall) {
            include __DIR__ . '/pages/maintenance.php';
            return true;
        }
    }
}

// ========== XAVFSIZLIK: Bloklangan yo'llar ==========
$blocked = ['/.env', '/.git', '/.htaccess', '/.gitignore'];
$blockedPrefixes = ['/data/', '/tmp/', '/config/'];
$blockedFiles = ['/sync_prompts.php', '/init.php', '/sync_prompts', '/init', '/cleanup.php', '/cleanup'];

foreach ($blocked as $b) {
    if ($uri === $b) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        return true;
    }
}
foreach ($blockedPrefixes as $bp) {
    if (stripos($uri, $bp) === 0) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        return true;
    }
}
foreach ($blockedFiles as $bf) {
    if ($uri === $bf) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        return true;
    }
}

// ========== STATIK FAYLLAR ==========
// Faqat ruxsat etilgan kengaytmalar
$allowedStaticExts = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'ico', 'svg', 'webp', 'woff', 'woff2', 'ttf', 'eot', 'txt', 'xml', 'mp4'];
if ($uri !== '/' && file_exists($filePath) && is_file($filePath)) {
    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    // PHP fayllar va API yo'llarni statik fayl sifatida serve qilmaymiz — ular quyida route/API handler orqali ishlaydi
    if ($ext === 'php' || strpos($uri, '/api/') === 0) {
        // Skip — PHP fayllar route yoki API handler orqali xizmat qilinadi
    } elseif (in_array($ext, $allowedStaticExts)) {
        return false; // PHP built-in server to'g'ridan-to'g'ri serve qiladi
    } else {
        // Noma'lum fayl kengaytmasi — serve qilmaymiz
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        return true;
    }
}


// Map Pretty URLs to PHP files
$routes = [
    '/' => '/pages/index.php',
    '/create' => '/pages/create.php',
    '/infografika' => '/pages/infografika.php',
    '/infografika-paketi' => '/pages/infografika-paketi.php',
    '/foto-tahrir' => '/pages/foto-tahrir.php',
    '/pricing' => '/pages/pricing.php',
    '/contact' => '/pages/contact.php',
    '/secret' => '/pages/secret.php',
    '/noldan-yaratish' => '/pages/noldan-yaratish.php',
    '/uslub-nusxalash' => '/pages/uslub-nusxalash.php',
    '/smart-matn' => '/pages/smart-matn.php',
    '/fashion-ai' => '/pages/fashion-ai.php',
    '/fotosesiya-pro' => '/pages/fotosesiya-pro.php',
    '/kartochka-ai' => '/pages/kartochka-ai.php',
    '/video-ai' => '/pages/video-ai.php',
    '/instrumentlar' => '/pages/instruments.php',
    '/stuv-kalkulyatori' => '/pages/stuv-calculator.php',
    '/sotuvlar-analitikasi' => '/pages/sales-analytics.php',
    '/raqiblar-monitori' => '/pages/competitor-monitor.php',
    '/zoom-selling-ai' => '/pages/zoom-selling-ai.php',
    '/insta-link' => '/pages/insta-link.php',
    '/tarix' => '/pages/history.php',
    '/history' => '/pages/history.php',
    '/kartochka-sozlash' => '/pages/kartochka-sozlash.php',
    '/qqs-kalkulyatori' => '/pages/qqs-calculator.php',
    '/kurslar' => '/pages/kurslar.php',
    '/kapital-bank' => '/pages/kapital-bank.php',
    '/hisobotlar' => '/pages/hisobotlar.php',
    '/didox-etty' => '/pages/didox-etty.php',
];

// Check if URI matches a route
if (isset($routes[$uri])) {
    include __DIR__ . $routes[$uri];
    return true;
}

// API routing
if (strpos($uri, '/api/') === 0) {
    $apiFile = __DIR__ . $uri;
    // Faqat api/ papkasidagi PHP fayllar
    $realPath = realpath($apiFile);
    $apiDir = realpath(__DIR__ . '/api');
    if ($realPath && strpos($realPath, $apiDir) === 0 && pathinfo($realPath, PATHINFO_EXTENSION) === 'php') {
        include $realPath;
        return true;
    }
}

// 404
http_response_code(404);
header("Content-Type: text/html");
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 | Sahifa topilmadi – Tiba AI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #0a0a0f; color: #fff; display: flex; align-items: center; justify-content: center; min-height: 100vh; font-family: 'Inter', sans-serif; }
        .card { text-align: center; padding: 3rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 1.5rem; backdrop-filter: blur(20px); max-width: 480px; }
        h1 { font-size: 8rem; font-weight: 900; background: linear-gradient(135deg, #6366f1, #a855f7, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1; }
        p { color: #9ca3af; font-size: 1.1rem; margin: 1.5rem 0 2rem; }
        a { display: inline-flex; align-items: center; padding: 0.875rem 2rem; background: linear-gradient(135deg, #4f46e5, #7c3aed); color: #fff; border-radius: 0.75rem; font-weight: 700; text-decoration: none; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 4px 20px rgba(79,70,229,0.3); }
        a:hover { transform: scale(1.05); box-shadow: 0 6px 30px rgba(79,70,229,0.5); }
    </style>
</head>
<body>
    <div class="card">
        <h1>404</h1>
        <p>Voy! Siz qidirayotgan sahifa koinotda adashib qoldi.</p>
        <a href="/">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="margin-right:8px"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Bosh sahifaga qaytish
        </a>
    </div>
</body>
</html>
