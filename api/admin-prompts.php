<?php
/**
 * Tiba AI — Admin Promptlar boshqaruvi (SQLite)
 * 1:1 Mirror
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'POST required'], 405);
}

$input = getInput();
$action = $input['action'] ?? '';

// Check session
$sessionToken = $_SERVER['HTTP_X_ADMIN_SESSION'] ?? '';
if (empty($sessionToken)) {
    // Also check standard AUTH header common in some fetch setups
    $headers = getallheaders();
    $sessionToken = $headers['X-Admin-Session'] ?? '';
}

if (empty($sessionToken)) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

$db = getDB();
$stmt = $db->prepare("SELECT id FROM admin_sessions WHERE token = ? AND expires_at > ?");
$stmt->execute([$sessionToken, date('Y-m-d H:i:s')]);
if (!$stmt->fetch()) {
    jsonResponse(['error' => 'Session expired or invalid'], 401);
}

switch ($action) {
    case 'get':
        $prompts = getPrompts();
        if (!$prompts) {
            jsonResponse(['error' => 'Promptlar topilmadi'], 404);
        }
        jsonResponse($prompts);
        break;

    case 'update':
        $data = $input['data'] ?? null;
        if (!$data) {
            jsonResponse(['error' => 'Ma\'lumot yo\'q'], 400);
        }
        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $stmt = $db->prepare("INSERT OR REPLACE INTO configs (id, data) VALUES ('prompts', ?)");
        $stmt->execute([$jsonData]);
        jsonResponse(['message' => 'Promptlar muvaffaqiyatli saqlandi']);
        break;

    default:
        jsonResponse(['error' => 'Noto\'g\'ri amal'], 400);
}
