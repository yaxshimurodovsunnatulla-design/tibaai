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
    $stmt = $db->query("SELECT id, phone, display_name, status, created_at, instagram_url FROM bloggers ORDER BY created_at DESC");
    $bloggers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    jsonResponse(['success' => true, 'bloggers' => $bloggers]);
}

if ($action === 'approve') {
    $id = (int)($input['id'] ?? 0);
    $db->prepare("UPDATE bloggers SET status = 'approved', verified_at = datetime('now') WHERE id = ?")->execute([$id]);
    jsonResponse(['success' => true]);
}

if ($action === 'reject') {
    $id = (int)($input['id'] ?? 0);
    $reason = sanitize($input['reason'] ?? '');
    $db->prepare("UPDATE bloggers SET status = 'rejected', rejection_reason = ? WHERE id = ?")->execute([$reason, $id]);
    jsonResponse(['success' => true]);
}

if ($action === 'block') {
    $id = (int)($input['id'] ?? 0);
    $db->prepare("UPDATE bloggers SET status = 'blocked' WHERE id = ?")->execute([$id]);
    jsonResponse(['success' => true]);
}

jsonResponse(['error' => 'Noto\'g\'ri amal'], 400);
