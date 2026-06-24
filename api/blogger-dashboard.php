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

if ($action === 'get_stats') {
    // 1. Hisoblash: Profil to'ldirilganligi (oddiy tekshiruv)
    $completion = 20; // Ro'yxatdan o'tgan
    if (!empty($blogger['avatar_path'])) $completion += 10;
    if (!empty($blogger['bio'])) $completion += 10;
    if (!empty($blogger['instagram_url']) || !empty($blogger['telegram_url'])) $completion += 20;
    if (!empty($blogger['categories'])) $completion += 20;
    if ($blogger['price_story'] > 0 || $blogger['price_post'] > 0 || $blogger['price_reels'] > 0) $completion += 20;

    // 2. So'nggi takliflar (5 ta, 'pending' status)
    $stmt = $db->prepare("SELECT id, deal_type, product_name, ad_format, offered_price, created_at FROM ad_deals WHERE blogger_id = ? AND status = 'pending' ORDER BY created_at DESC LIMIT 5");
    $stmt->execute([$blogger['id']]);
    $recentOffers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format dates
    foreach($recentOffers as &$o) {
        $o['created_at'] = date('d.m.Y H:i', strtotime($o['created_at']));
    }

    jsonResponse([
        'success' => true,
        'stats' => [
            'total_earned' => (int)$blogger['total_earned'],
            'active_deals' => (int)$db->query("SELECT COUNT(*) FROM ad_deals WHERE blogger_id = {$blogger['id']} AND status IN ('accepted', 'delivered')")->fetchColumn(),
            'completed_deals' => (int)$blogger['completed_deals'],
            'rating' => (float)$blogger['rating'],
            'total_reviews' => (int)$blogger['total_reviews'],
            'profile_completion' => $completion > 100 ? 100 : $completion
        ],
        'recent_offers' => $recentOffers
    ]);
}

jsonResponse(['error' => 'Noto\'g\'ri amal'], 400);
