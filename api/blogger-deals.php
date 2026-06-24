<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Faqat POST'], 405);
}

$token = $_SERVER['HTTP_X_BLOGGER_TOKEN'] ?? '';
if (empty($token)) { jsonResponse(['error' => 'Ruxsat etilmagan'], 401); }

$db = getDB();
$now = date('Y-m-d H:i:s');
$stmt = $db->prepare("SELECT b.* FROM bloggers b JOIN blogger_sessions s ON b.id = s.blogger_id WHERE s.token = ? AND s.expires_at > ?");
$stmt->execute([$token, $now]);
$blogger = $stmt->fetch();

if (!$blogger) { jsonResponse(['error' => 'Ruxsat etilmagan'], 401); }

$input = getInput();
$action = $input['action'] ?? '';

if ($action === 'get_pending_count') {
    $stmt = $db->prepare("SELECT COUNT(*) FROM ad_deals WHERE blogger_id = ? AND status = 'pending'");
    $stmt->execute([$blogger['id']]);
    $count = $stmt->fetchColumn();
    jsonResponse(['success' => true, 'count' => (int)$count]);
}

if ($action === 'get_pending') {
    $stmt = $db->prepare("SELECT * FROM ad_deals WHERE blogger_id = ? AND status = 'pending' ORDER BY created_at DESC");
    $stmt->execute([$blogger['id']]);
    $offers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($offers as &$o) {
        $o['created_at'] = date('d.m.Y H:i', strtotime($o['created_at']));
    }
    
    jsonResponse(['success' => true, 'offers' => $offers]);
}

if ($action === 'respond') {
    $id = (int)($input['id'] ?? 0);
    $response = $input['response'] ?? '';
    
    if (!in_array($response, ['accept', 'reject'])) {
        jsonResponse(['error' => 'Noto\'g\'ri response'], 400);
    }
    
    $status = $response === 'accept' ? 'accepted' : 'rejected';
    $timeCol = $response === 'accept' ? 'accepted_at' : 'updated_at'; // Rad etilganda ham updated_at ishlaydi ozi default
    
    $stmt = $db->prepare("UPDATE ad_deals SET status = ?, $timeCol = datetime('now') WHERE id = ? AND blogger_id = ? AND status = 'pending'");
    $stmt->execute([$status, $id, $blogger['id']]);
    
    if ($stmt->rowCount() > 0) {
        jsonResponse(['success' => true]);
    } else {
        jsonResponse(['error' => 'Bitim topilmadi yoki allaqachon javob berilgan'], 404);
    }
}

if ($action === 'get_active') {
    $stmt = $db->prepare("SELECT * FROM ad_deals WHERE blogger_id = ? AND status IN ('accepted', 'delivered', 'completed', 'rejected') ORDER BY updated_at DESC");
    $stmt->execute([$blogger['id']]);
    $deals = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($deals as &$o) {
        $o['accepted_at'] = $o['accepted_at'] ? date('d.m.Y H:i', strtotime($o['accepted_at'])) : null;
    }
    jsonResponse(['success' => true, 'deals' => $deals]);
}

jsonResponse(['error' => 'Noto\'g\'ri amal'], 400);
