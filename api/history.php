<?php
/**
 * Tiba AI — Foydalanuvchi Tarixi
 */
require_once __DIR__ . '/config.php';

$user = getAuthUser();
if (!$user) {
    jsonResponse(['error' => 'Avtorizatsiyadan o\'ting'], 401);
}

try {
    $db = getDB();
    
    // Pagination
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = 20;
    $offset = ($page - 1) * $limit;
    
    // Total Count
    $countStmt = $db->prepare("SELECT COUNT(*) FROM generations WHERE user_id = ?");
    $countStmt->execute([$user['id']]);
    $total = $countStmt->fetchColumn();
    
    // Items
    $stmt = $db->prepare("
        SELECT id, image_path, prompt_data, telegram_msg_id, created_at 
        FROM generations 
        WHERE user_id = ? 
        ORDER BY created_at DESC 
        LIMIT $limit OFFSET $offset
    ");
    $stmt->execute([$user['id']]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format response
    $formatted = array_map(function($item) {
        $data = json_decode($item['prompt_data'], true);
        
        // Telegram link construction (Check if user is member functionality is not possible here, just give channel link)
        // Channel ID provided: -1003867669758 -> 13867669758
        $channelId = '13867669758'; 
        
        return [
            'id' => $item['id'],
            'url' => $item['image_path'],
            'type' => $data['type'] ?? 'infographic',
            'product' => $data['product'] ?? 'Nomalum',
            'style' => $data['style'] ?? '',
            'lang' => $data['lang'] ?? '',
            'details' => $data, // Full details for the modal
            'time' => date('d.m.Y H:i', strtotime($item['created_at']))
        ];
    }, $items);
    
    jsonResponse([
        'success' => true,
        'history' => $formatted,
        'pagination' => [
            'total' => $total,
            'page' => $page,
            'pages' => ceil($total / $limit)
        ]
    ]);
    
} catch (Exception $e) {
    jsonResponse(['error' => $e->getMessage()], 500);
}
