<?php
/**
 * Tiba AI — Admin Packages Management API
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
        $stmt = $db->query("SELECT * FROM packages ORDER BY sort_order ASC");
        $packages = $stmt->fetchAll();
        // Decode features JSON
        foreach ($packages as &$p) {
            $p['features'] = json_decode($p['features'] ?? '[]', true) ?: [];
        }
        jsonResponse(['success' => true, 'packages' => $packages]);
        break;

    case 'update':
        $id = $input['id'] ?? null;
        $data = $input['data'] ?? [];
        if (!$id || empty($data)) jsonResponse(['error' => 'Missing data'], 400);

        try {
            $features = json_encode($data['features'] ?? [], JSON_UNESCAPED_UNICODE);
            $stmt = $db->prepare("UPDATE packages SET name = ?, credits = ?, price = ?, icon = ?, gradient = ?, badge = ?, badge_gradient = ?, features = ?, is_active = ? WHERE id = ?");
            $stmt->execute([
                $data['name'],
                intval($data['credits']),
                intval($data['price']),
                $data['icon'] ?? 'fa-coins',
                $data['gradient'] ?? 'from-gray-600 to-gray-500',
                $data['badge'] ?: null,
                $data['badge_gradient'] ?: null,
                $features,
                intval($data['is_active'] ?? 1),
                $id,
            ]);
            jsonResponse(['success' => true, 'message' => 'Paket yangilandi']);
        } catch (Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    case 'create':
        $data = $input['data'] ?? [];
        if (empty($data['id']) || empty($data['name'])) jsonResponse(['error' => 'ID va nom kerak'], 400);

        try {
            // Check if ID exists
            $check = $db->prepare("SELECT id FROM packages WHERE id = ?");
            $check->execute([$data['id']]);
            if ($check->fetch()) jsonResponse(['error' => 'Bu ID allaqachon mavjud'], 400);

            $features = json_encode($data['features'] ?? [], JSON_UNESCAPED_UNICODE);
            $maxOrder = $db->query("SELECT COALESCE(MAX(sort_order), 0) FROM packages")->fetchColumn();

            $stmt = $db->prepare("INSERT INTO packages (id, name, credits, price, icon, gradient, badge, badge_gradient, features, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['id'],
                $data['name'],
                intval($data['credits'] ?? 0),
                intval($data['price'] ?? 0),
                $data['icon'] ?? 'fa-coins',
                $data['gradient'] ?? 'from-gray-600 to-gray-500',
                $data['badge'] ?: null,
                $data['badge_gradient'] ?: null,
                $features,
                $maxOrder + 1,
                intval($data['is_active'] ?? 1),
            ]);
            jsonResponse(['success' => true, 'message' => 'Yangi paket qo\'shildi']);
        } catch (Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    case 'delete':
        $id = $input['id'] ?? null;
        if (!$id) jsonResponse(['error' => 'ID kerak'], 400);

        try {
            $stmt = $db->prepare("DELETE FROM packages WHERE id = ?");
            $stmt->execute([$id]);
            jsonResponse(['success' => true, 'message' => 'Paket o\'chirildi']);
        } catch (Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    case 'reorder':
        $orders = $input['orders'] ?? [];
        if (empty($orders)) jsonResponse(['error' => 'No order data'], 400);

        $db->beginTransaction();
        try {
            $stmt = $db->prepare("UPDATE packages SET sort_order = ? WHERE id = ?");
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

    default:
        jsonResponse(['error' => 'Invalid action'], 400);
}
