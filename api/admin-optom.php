<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Faqat POST'], 405);
}

// Check admin session
$sessionToken = $_SERVER['HTTP_X_ADMIN_SESSION'] ?? '';
if (empty($sessionToken)) {
    jsonResponse(['error' => 'Ruxsat etilmagan'], 401);
}
$db = getDB();
$stmt = $db->prepare("SELECT COUNT(*) FROM admin_sessions WHERE token = ? AND expires_at > datetime('now')");
$stmt->execute([$sessionToken]);
if ($stmt->fetchColumn() == 0) {
    jsonResponse(['error' => 'Sessiya muddati tugagan'], 401);
}

$input = getInput();
$action = $input['action'] ?? '';

if ($action === 'list') {
    $stmt = $db->query("SELECT id, company_name, owner_name, phone, email, inn, city, categories, status, verified, created_at FROM optom_sellers ORDER BY created_at DESC");
    $sellers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // JSON parse categories
    foreach ($sellers as &$s) {
        $s['categories'] = json_decode($s['categories'] ?? '[]', true);
    }
    
    jsonResponse(['success' => true, 'sellers' => $sellers]);
}

if ($action === 'approve') {
    $id = (int)($input['id'] ?? 0);
    $db->prepare("UPDATE optom_sellers SET status = 'active' WHERE id = ?")->execute([$id]);
    jsonResponse(['success' => true]);
}

if ($action === 'reject') {
    $id = (int)($input['id'] ?? 0);
    $db->prepare("UPDATE optom_sellers SET status = 'rejected' WHERE id = ?")->execute([$id]);
    jsonResponse(['success' => true]);
}

if ($action === 'block') {
    $id = (int)($input['id'] ?? 0);
    $db->prepare("UPDATE optom_sellers SET status = 'blocked' WHERE id = ?")->execute([$id]);
    jsonResponse(['success' => true]);
}

if ($action === 'toggle_verify') {
    $id = (int)($input['id'] ?? 0);
    $db->prepare("UPDATE optom_sellers SET verified = 1 - verified WHERE id = ?")->execute([$id]);
    jsonResponse(['success' => true]);
}

jsonResponse(['error' => 'Noto\'g\'ri amal'], 400);
