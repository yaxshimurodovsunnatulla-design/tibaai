<?php
/**
 * Tiba AI — Namunalar (Showcase Samples) Admin API
 * GET    — barcha namunalar
 * POST   — yangi namuna qo'shish (multipart/form-data)
 * DELETE — namuna o'chirish (?id=X)
 */
require_once __DIR__ . '/config.php';

// Admin autentifikatsiya
$sessionToken = $_SERVER['HTTP_X_ADMIN_SESSION'] ?? $_COOKIE['admin_session'] ?? '';
if (empty($sessionToken)) {
    jsonResponse(['error' => 'Admin session required'], 401);
}
$db = getDB();
$stmt = $db->prepare("SELECT id FROM admin_sessions WHERE token = ? AND status = 'active' AND expires_at > datetime('now')");
$stmt->execute([$sessionToken]);
if (!$stmt->fetch()) {
    jsonResponse(['error' => 'Invalid or expired admin session'], 401);
}

$method = $_SERVER['REQUEST_METHOD'];
$uploadDir = __DIR__ . '/../assets/samples';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

// ========== GET — Barcha namunalar ==========
if ($method === 'GET') {
    $samples = $db->query("SELECT * FROM showcase_samples ORDER BY sort_order ASC, id DESC")->fetchAll();
    jsonResponse(['samples' => $samples]);
}

// ========== POST — Yangi namuna qo'shish ==========
if ($method === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $type = $_POST['type'] ?? 'carousel'; // carousel yoki before-after
    $sortOrder = (int)($_POST['sort_order'] ?? 0);

    if (in_array($type, ['carousel', 'carousel-top', 'carousel-bottom'])) {
        if (empty($_FILES['image']['tmp_name'])) {
            jsonResponse(['error' => 'Rasm yuklang'], 400);
        }
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            jsonResponse(['error' => 'Faqat JPG, PNG, WEBP'], 400);
        }

        // WebP sifatida compress qilib saqlash
        $tmpPath  = $uploadDir . '/tmp_' . time() . '_' . mt_rand(1000, 9999);
        move_uploaded_file($_FILES['image']['tmp_name'], $tmpPath);
        $filename = 'sample_' . time() . '_' . mt_rand(1000, 9999) . '.webp';
        $destPath = $uploadDir . '/' . $filename;
        if (!compressImage($tmpPath, $destPath, 85, 1400)) {
            // fallback: asl ext bilan saqlash
            $filename = 'sample_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
            rename($tmpPath, $uploadDir . '/' . $filename);
        } else {
            @unlink($tmpPath);
        }
        $imagePath = '/assets/samples/' . $filename;

        $stmt = $db->prepare("INSERT INTO showcase_samples (title, image_path, type, sort_order) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $imagePath, $type, $sortOrder]);
    } else {
        // Before/After
        if (empty($_FILES['before_image']['tmp_name']) || empty($_FILES['after_image']['tmp_name'])) {
            jsonResponse(['error' => 'Oldingi va keyingi rasmlarni yuklang'], 400);
        }
        $ts = time() . '_' . mt_rand(1000, 9999);

        // Before rasm
        $tmpB     = $uploadDir . '/tmp_b_' . $ts;
        $extB     = strtolower(pathinfo($_FILES['before_image']['name'], PATHINFO_EXTENSION));
        move_uploaded_file($_FILES['before_image']['tmp_name'], $tmpB);
        $beforeFile = 'before_' . $ts . '.webp';
        if (!compressImage($tmpB, $uploadDir . '/' . $beforeFile, 85, 1400)) {
            $beforeFile = 'before_' . $ts . '.' . $extB;
            rename($tmpB, $uploadDir . '/' . $beforeFile);
        } else {
            @unlink($tmpB);
        }

        // After rasm
        $tmpA     = $uploadDir . '/tmp_a_' . $ts;
        $extA     = strtolower(pathinfo($_FILES['after_image']['name'], PATHINFO_EXTENSION));
        move_uploaded_file($_FILES['after_image']['tmp_name'], $tmpA);
        $afterFile = 'after_' . $ts . '.webp';
        if (!compressImage($tmpA, $uploadDir . '/' . $afterFile, 85, 1400)) {
            $afterFile = 'after_' . $ts . '.' . $extA;
            rename($tmpA, $uploadDir . '/' . $afterFile);
        } else {
            @unlink($tmpA);
        }

        $stmt = $db->prepare("INSERT INTO showcase_samples (title, before_image_path, after_image_path, type, sort_order) VALUES (?, ?, ?, 'before-after', ?)");
        $stmt->execute([$title, '/assets/samples/' . $beforeFile, '/assets/samples/' . $afterFile, $sortOrder]);
    }

    jsonResponse(['success' => true, 'id' => $db->lastInsertId()]);
}

// ========== DELETE — Namuna o'chirish ==========
if ($method === 'DELETE') {
    $id = $_GET['id'] ?? 0;
    if (!$id) jsonResponse(['error' => 'ID kerak'], 400);

    // Fayllarni o'chirish
    $stmt = $db->prepare("SELECT * FROM showcase_samples WHERE id = ?");
    $stmt->execute([$id]);
    $sample = $stmt->fetch();
    if ($sample) {
        foreach (['image_path', 'before_image_path', 'after_image_path'] as $field) {
            if (!empty($sample[$field])) {
                $filePath = __DIR__ . '/..' . $sample[$field];
                if (file_exists($filePath)) unlink($filePath);
            }
        }
        $db->prepare("DELETE FROM showcase_samples WHERE id = ?")->execute([$id]);
    }
    jsonResponse(['success' => true]);
}

jsonResponse(['error' => 'Method not allowed'], 405);
