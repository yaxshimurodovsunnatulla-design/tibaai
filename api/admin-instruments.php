<?php
/**
 * Tiba AI — Admin Instruments API
 * GET  — barcha instrumentlar
 * POST — status o'zgartirish (toggle), video_url saqlash
 */
require_once __DIR__ . '/config.php';

$sessionToken = $_SERVER['HTTP_X_ADMIN_SESSION'] ?? '';
$db = getDB();
$stmt = $db->prepare("SELECT id FROM admin_sessions WHERE token = ? AND status = 'active' AND expires_at > datetime('now')");
$stmt->execute([$sessionToken]);
if (!$stmt->fetch()) jsonResponse(['error' => 'Unauthorized'], 401);

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $instruments = $db->query("SELECT * FROM instruments ORDER BY category ASC, sort_order ASC")->fetchAll();
    jsonResponse(['instruments' => $instruments]);
}

if ($method === 'POST') {
    $input = getInput();
    $action = $input['action'] ?? '';

    if ($action === 'toggle') {
        $id = $input['id'] ?? 0;
        $status = $input['status'] ?? 'active'; // active, coming_soon, hidden
        if (!in_array($status, ['active', 'coming_soon', 'hidden'])) {
            jsonResponse(['error' => 'Invalid status'], 400);
        }
        $db->prepare("UPDATE instruments SET status = ? WHERE id = ?")->execute([$status, $id]);
        jsonResponse(['success' => true, 'message' => "Status o'zgartirildi"]);
    }

    if ($action === 'save_video_url') {
        $id = (int)($input['id'] ?? 0);
        $videoUrl = trim($input['video_url'] ?? '');

        if ($id <= 0) jsonResponse(['error' => 'ID kerak'], 400);

        // YouTube URL validatsiya (bo'sh yoki to'g'ri YouTube link)
        if (!empty($videoUrl)) {
            if (!preg_match('#^https?://(www\.)?(youtube\.com|youtu\.be)/#', $videoUrl)) {
                jsonResponse(['error' => "Faqat YouTube havolalarini qo'yish mumkin"], 400);
            }
        }

        $db->prepare("UPDATE instruments SET video_url = ? WHERE id = ?")->execute([$videoUrl, $id]);
        jsonResponse(['success' => true, 'message' => 'Video URL saqlandi']);
    }

    jsonResponse(['error' => 'Invalid action'], 400);
}

jsonResponse(['error' => 'Method not allowed'], 405);
