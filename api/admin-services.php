<?php
/**
 * Tiba AI — Admin Sections/Services Management API
 */
require_once __DIR__ . '/config.php';

// Auth check
$sessionToken = $_SERVER['HTTP_X_ADMIN_SESSION'] ?? '';
$db = getDB();
$stmt = $db->prepare("SELECT * FROM admin_sessions WHERE token = ? AND expires_at > ? AND status = 'active'");
$stmt->execute([$sessionToken, date('Y-m-d H:i:s')]);
if (!$stmt->fetch()) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

$input = getInput();
$action = $input['action'] ?? '';

switch ($action) {
    case 'get':
        $stmt = $db->query("SELECT * FROM services ORDER BY sort_order ASC");
        $services = $stmt->fetchAll();
        jsonResponse(['success' => true, 'services' => $services]);
        break;

    case 'update_order':
        $orders = $input['orders'] ?? []; // Array of {id, sort_order}
        if (empty($orders)) jsonResponse(['error' => 'No order data'], 400);

        $db->beginTransaction();
        try {
            $stmt = $db->prepare("UPDATE services SET sort_order = ? WHERE id = ?");
            foreach ($orders as $item) {
                $stmt->execute([$item['sort_order'], $item['id']]);
            }
            $db->commit();
            jsonResponse(['success' => true, 'message' => 'Tartib saqlandi']);
        } catch (Exception $e) {
            $db->rollBack();
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    case 'update_service':
        $id = $input['id'] ?? null;
        $data = $input['data'] ?? [];
        if (!$id || empty($data)) jsonResponse(['error' => 'Missing data'], 400);

        try {
            $stmt = $db->prepare("UPDATE services SET name = ?, description = ?, badge = ?, icon = ?, gradient = ?, is_active = ? WHERE id = ?");
            $stmt->execute([
                $data['name'],
                $data['description'],
                $data['badge'],
                $data['icon'],
                $data['gradient'],
                $data['is_active'],
                $id
            ]);
            jsonResponse(['success' => true, 'message' => 'Ma\'lumotlar saqlandi']);
        } catch (Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    default:
        jsonResponse(['error' => 'Invalid action'], 400);
}
