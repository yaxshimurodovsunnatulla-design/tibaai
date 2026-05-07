<?php
/**
 * Tiba AI — Admin Promo Codes Management API
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
        $stmt = $db->query("SELECT * FROM promo_codes ORDER BY id DESC");
        $promos = $stmt->fetchAll();
        jsonResponse(['success' => true, 'promos' => $promos]);
        break;

    case 'create':
        $code = strtoupper(trim($input['code'] ?? ''));
        $type = $input['discount_type'] ?? 'percentage';
        $value = intval($input['discount_value'] ?? 0);
        $maxUses = intval($input['max_uses'] ?? 1);
        $expiresAt = $input['expires_at'] ?? '';

        if (empty($code) || $value <= 0 || empty($expiresAt)) {
            jsonResponse(['error' => 'Barcha maydonlarni to\'ldiring'], 400);
        }

        try {
            $stmt = $db->prepare("INSERT INTO promo_codes (code, discount_type, discount_value, max_uses, expires_at) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$code, $type, $value, $maxUses, $expiresAt]);
            jsonResponse(['success' => true, 'message' => 'Promokod yaratildi']);
        } catch (Exception $e) {
            jsonResponse(['error' => 'Bu kod allaqachon mavjud yoki xato: ' . $e->getMessage()], 500);
        }
        break;

    case 'delete':
        $id = $input['id'] ?? null;
        if (!$id) jsonResponse(['error' => 'ID kerak'], 400);
        try {
            $db->prepare("DELETE FROM promo_codes WHERE id = ?")->execute([$id]);
            jsonResponse(['success' => true, 'message' => 'Promokod o\'chirildi']);
        } catch (Exception $e) {
            jsonResponse(['error' => $e->getMessage()], 500);
        }
        break;

    default:
        jsonResponse(['error' => 'Invalid action'], 400);
}
