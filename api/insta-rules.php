<?php
require_once __DIR__ . '/config.php';

$user = getAuthUser();
if (!$user) {
    jsonResponse(['error' => 'Tizimga kiring'], 401);
}

$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // List rules
    $stmt = $db->prepare("SELECT * FROM insta_rules WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user['id']]);
    $rules = $stmt->fetchAll();
    jsonResponse(['rules' => $rules]);
}

if ($method === 'POST') {
    $input = getInput();
    $action = $input['action'] ?? 'save';

    if ($action === 'save') {
        $trigger = sanitize($input['trigger_word'] ?? '');
        $dmText = sanitize($input['dm_text'] ?? '');
        $btnText = sanitize($input['button_text'] ?? '');
        $btnUrl = sanitize($input['button_url'] ?? '');

        if (empty($trigger) || empty($dmText)) {
            jsonResponse(['error' => 'Trigger va xabar matni bo\'sh bo\'lmasligi kerak'], 400);
        }

        $stmt = $db->prepare("INSERT INTO insta_rules (user_id, trigger_word, dm_text, button_text, button_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user['id'], $trigger, $dmText, $btnText, $btnUrl]);

        jsonResponse(['success' => true, 'id' => $db->lastInsertId()]);
    }

    if ($action === 'delete') {
        $id = (int)($input['id'] ?? 0);
        $stmt = $db->prepare("DELETE FROM insta_rules WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user['id']]);
        jsonResponse(['success' => true]);
    }

    if ($action === 'toggle') {
        $id = (int)($input['id'] ?? 0);
        $status = (int)($input['status'] ?? 0);
        $stmt = $db->prepare("UPDATE insta_rules SET is_active = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$status, $id, $user['id']]);
        jsonResponse(['success' => true]);
    }

    if ($action === 'connect') {
        $accId = sanitize($input['account_id'] ?? '');
        $token = sanitize($input['access_token'] ?? '');
        
        if (empty($accId) || empty($token)) {
            jsonResponse(['error' => 'Ma\'lumotlar to\'liq emas'], 400);
        }

        $stmt = $db->prepare("UPDATE users SET insta_account_id = ?, insta_access_token = ? WHERE id = ?");
        $stmt->execute([$accId, $token, $user['id']]);
        jsonResponse(['success' => true]);
    }
}
