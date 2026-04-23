<?php
/**
 * Tiba AI — Maintenance auto-check
 * Bu fayl BARCHA PHP fayllardan oldin avtomatik yuklanadi
 * (.htaccess dagi auto_prepend_file orqali)
 */
$_mFlag = __DIR__ . '/data/maintenance.flag';
if (file_exists($_mFlag)) {
    $_mUri = $_SERVER['REQUEST_URI'] ?? '';
    $_mBypass = (
        strpos($_mUri, '/secret') === 0 ||
        strpos($_mUri, '/api/') === 0 ||
        strpos($_mUri, '/test') === 0 ||
        isset($_COOKIE['admin_bypass'])
    );
    if (!$_mBypass) {
        http_response_code(503);
        header('Retry-After: 3600');
        include __DIR__ . '/pages/maintenance.php';
        exit;
    }
}
