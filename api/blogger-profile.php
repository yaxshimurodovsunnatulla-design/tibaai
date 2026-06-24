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

// Rasm yuklash (FormData orqali keladi)
if (isset($_POST['action']) && $_POST['action'] === 'upload_avatar') {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        jsonResponse(['error' => 'Rasm yuklashda xatolik'], 400);
    }
    $file = $_FILES['image'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
        jsonResponse(['error' => 'Faqat rasm formatlari ruxsat etilgan'], 400);
    }
    
    $uploadDir = __DIR__ . '/../uploads/avatars/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    
    $filename = 'b_' . $blogger['id'] . '_' . time() . '.' . $ext;
    if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
        $url = '/uploads/avatars/' . $filename;
        $db->prepare("UPDATE bloggers SET avatar_path = ? WHERE id = ?")->execute([$url, $blogger['id']]);
        jsonResponse(['success' => true, 'url' => $url]);
    }
    jsonResponse(['error' => 'Faylni saqlashda xatolik'], 500);
}

$input = getInput();
$action = $input['action'] ?? '';

if ($action === 'get') {
    jsonResponse([
        'success' => true,
        'blogger' => [
            'display_name' => $blogger['display_name'],
            'phone' => $blogger['phone'],
            'bio' => $blogger['bio'],
            'avatar_path' => $blogger['avatar_path'],
            'instagram_url' => $blogger['instagram_url'],
            'instagram_followers' => $blogger['instagram_followers'],
            'tiktok_url' => $blogger['tiktok_url'],
            'tiktok_followers' => $blogger['tiktok_followers'],
            'price_story' => $blogger['price_story'],
            'price_reels' => $blogger['price_reels'],
            'price_post' => $blogger['price_post'],
            'price_unboxing' => $blogger['price_unboxing'],
            'accepts_barter' => $blogger['accepts_barter']
        ]
    ]);
}

if ($action === 'update') {
    $name = sanitize($input['name'] ?? '');
    $bio = sanitize($input['bio'] ?? '');
    $instUrl = sanitize($input['inst_url'] ?? '');
    $instFol = (int)($input['inst_fol'] ?? 0);
    $tikUrl = sanitize($input['tik_url'] ?? '');
    $tikFol = (int)($input['tik_fol'] ?? 0);
    $story = (int)($input['price_story'] ?? 0);
    $reels = (int)($input['price_reels'] ?? 0);
    $post = (int)($input['price_post'] ?? 0);
    $unbox = (int)($input['price_unbox'] ?? 0);
    $barter = (int)($input['barter'] ?? 0);

    if (empty($name)) {
        jsonResponse(['error' => 'Ism kiritilishi shart'], 400);
    }

    $sql = "UPDATE bloggers SET 
        display_name = ?, bio = ?, 
        instagram_url = ?, instagram_followers = ?, 
        tiktok_url = ?, tiktok_followers = ?,
        price_story = ?, price_reels = ?, price_post = ?, price_unboxing = ?,
        accepts_barter = ?, updated_at = datetime('now')
        WHERE id = ?";

    $db->prepare($sql)->execute([
        $name, $bio, $instUrl, $instFol, $tikUrl, $tikFol,
        $story, $reels, $post, $unbox, $barter, $blogger['id']
    ]);

    jsonResponse(['success' => true]);
}

jsonResponse(['error' => 'Noto\'g\'ri amal'], 400);
