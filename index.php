<?php
/**
 * Tiba AI – Root Entry Point
 * Router orqali boshqariladi
 */

// ========== TEXNIK ISHLAR TEKSHIRUVI ==========
$maintenanceFlag = __DIR__ . '/data/maintenance.flag';
if (file_exists($maintenanceFlag)) {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $bypassPaths = ['/secret', '/api/'];
    $isBypass = false;
    foreach ($bypassPaths as $bp) {
        if (strpos($uri, $bp) === 0 || $uri === $bp) { $isBypass = true; break; }
    }
    // Statik fayllarni o'tkazish
    $ext = strtolower(pathinfo($uri, PATHINFO_EXTENSION));
    if (in_array($ext, ['css','js','png','jpg','jpeg','gif','ico','svg','webp','woff','woff2'])) {
        $isBypass = true;
    }
    if (!$isBypass) {
        // Admin cookie tekshiruvi
        $isAdmin = false;
        $adminToken = $_COOKIE['admin_bypass'] ?? '';
        if (!empty($adminToken)) {
            try {
                require_once __DIR__ . '/api/config.php';
                $db = getDB();
                $stmt = $db->prepare("SELECT id FROM admin_sessions WHERE token = ? AND status = 'active' AND expires_at > datetime('now')");
                $stmt->execute([$adminToken]);
                if ($stmt->fetch()) $isAdmin = true;
            } catch (Exception $e) {}
        }
        if (!$isAdmin) {
            http_response_code(503);
            include __DIR__ . '/pages/maintenance.php';
            exit;
        }
    }
}

// Bosh sahifani router orqali yuklash
require_once __DIR__ . '/router.php';
exit;
