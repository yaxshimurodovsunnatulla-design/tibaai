<?php
require_once __DIR__ . '/config.php';

$user = getAuthUser();
if (!$user) jsonResponse(['error' => 'Tizimga kiring'], 401);

$db  = getDB();
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// DELETE single record
if ($method === 'DELETE') {
    $input = getInput();
    $id = (int)($input['id'] ?? 0);
    if ($id) {
        $db->prepare("DELETE FROM analytics_history WHERE id = ? AND user_id = ?")->execute([$id, $user['id']]);
    }
    jsonResponse(['ok' => true]);
}

// GET list
$stmt = $db->prepare("SELECT id, period, total_sales, order_count, total_expenses, net_profit, cost, created_at FROM analytics_history WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
$stmt->execute([$user['id']]);
$rows = $stmt->fetchAll();

jsonResponse(['items' => $rows]);
