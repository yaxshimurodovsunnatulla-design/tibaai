<?php
/**
 * Tiba AI — Batch Image Compression API
 * Barcha mavjud PNG/JPG rasmlarni WebP ga aylantiradi
 * DB da saqlangan yo'llarni ham yangilaydi
 *
 * POST { action: 'stats' }
 * POST { action: 'compress', batch: 20, offset: 0, target: 'generated'|'samples'|'all' }
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

$input  = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $input['action'] ?? 'stats';
$root   = realpath(__DIR__ . '/..'); // loyiha root

// -----------------------------------------------------------------------
// Yordamchi: glob GLOB_BRACE ishlamasa alternativ qo'llaydi (Windows mos)
// -----------------------------------------------------------------------
function globImages(string $dir): array {
    if (!is_dir($dir)) return [];
    // GLOB_BRACE Windows da ham ishlaydi, lekin ba'zi PHP build larda yo'q
    if (defined('GLOB_BRACE')) {
        $res = glob($dir . '/*.{png,jpg,jpeg,PNG,JPG,JPEG}', GLOB_BRACE);
        if ($res !== false && count($res) > 0) return $res;
    }
    return array_merge(
        glob($dir . '/*.png')  ?: [],
        glob($dir . '/*.jpg')  ?: [],
        glob($dir . '/*.jpeg') ?: [],
        glob($dir . '/*.PNG')  ?: [],
        glob($dir . '/*.JPG')  ?: [],
        glob($dir . '/*.JPEG') ?: []
    );
}

// -----------------------------------------------------------------------
// Samples uchun DB dan haqiqiy fayl yo'llarini topamiz
// Bu usul fayllar qaysi papkada bo'lishidan qat'iy nazar ishlaydi
// -----------------------------------------------------------------------
function getSampleJobsFromDB(PDO $db, string $root): array {
    $rows = $db->query("SELECT id, image_path, before_image_path, after_image_path FROM showcase_samples")->fetchAll();
    $jobs = [];
    foreach ($rows as $row) {
        $fields = ['image_path', 'before_image_path', 'after_image_path'];
        foreach ($fields as $field) {
            $url = $row[$field] ?? '';
            if (empty($url)) continue;
            $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
            if (!in_array($ext, ['png', 'jpg', 'jpeg'])) continue; // faqat compress kerak bo'lganlar
            $absPath = $root . $url; // masalan: /var/www/assets/xxx.jpg
            // Windows: backslash tozalash
            $absPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $absPath);
            if (!file_exists($absPath)) continue;
            $jobs[] = [
                'src'   => $absPath,
                'type'  => 'samples',
                'id'    => $row['id'],
                'field' => $field,
                'old_url' => $url,
            ];
        }
    }
    return $jobs;
}

// -----------------------------------------------------------------------
// Generated uchun DB dan yo'llarni olamiz
// -----------------------------------------------------------------------
function getGeneratedJobsFromDB(PDO $db, string $root): array {
    $rows = $db->query("SELECT id, image_path FROM generations WHERE image_path IS NOT NULL")->fetchAll();
    $jobs = [];
    foreach ($rows as $row) {
        $url = $row['image_path'] ?? '';
        if (empty($url)) continue;
        $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
        if (!in_array($ext, ['png', 'jpg', 'jpeg'])) continue;
        $absPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $root . $url);
        if (!file_exists($absPath)) continue;
        $jobs[] = [
            'src'     => $absPath,
            'type'    => 'generated',
            'id'      => $row['id'],
            'field'   => 'image_path',
            'old_url' => $url,
        ];
    }
    return $jobs;
}

// -----------------------------------------------------------------------
// Samples statistika — DB dan haqiqiy joy topib hisoblaydi
// -----------------------------------------------------------------------
function getSamplesStats(PDO $db, string $root): array {
    $rows   = $db->query("SELECT image_path, before_image_path, after_image_path FROM showcase_samples")->fetchAll();
    $need   = 0; $needBytes = 0; $done = 0; $doneBytes = 0;
    $seenPaths = [];
    foreach ($rows as $row) {
        foreach (['image_path', 'before_image_path', 'after_image_path'] as $field) {
            $url = $row[$field] ?? '';
            if (empty($url)) continue;
            if (isset($seenPaths[$url])) continue;
            $seenPaths[$url] = true;
            $absPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $root . $url);
            if (!file_exists($absPath)) continue;
            $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
            $sz  = filesize($absPath);
            if (in_array($ext, ['png', 'jpg', 'jpeg'])) { $need++; $needBytes += $sz; }
            elseif ($ext === 'webp')                     { $done++; $doneBytes += $sz; }
        }
    }
    return [
        'need'    => $need,
        'done'    => $done,
        'need_mb' => round($needBytes / 1024 / 1024, 2),
        'done_mb' => round($doneBytes / 1024 / 1024, 2),
    ];
}

// -----------------------------------------------------------------------
// Generated statistika
// -----------------------------------------------------------------------
function getGeneratedStats(PDO $db, string $root): array {
    $rows  = $db->query("SELECT image_path FROM generations WHERE image_path IS NOT NULL")->fetchAll();
    $need  = 0; $needBytes = 0; $done = 0; $doneBytes = 0;
    foreach ($rows as $row) {
        $url = $row['image_path'] ?? '';
        if (empty($url)) continue;
        $absPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $root . $url);
        if (!file_exists($absPath)) continue;
        $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
        $sz  = filesize($absPath);
        if (in_array($ext, ['png', 'jpg', 'jpeg'])) { $need++; $needBytes += $sz; }
        elseif ($ext === 'webp')                     { $done++; $doneBytes += $sz; }
    }
    return [
        'need'    => $need,
        'done'    => $done,
        'need_mb' => round($needBytes / 1024 / 1024, 2),
        'done_mb' => round($doneBytes / 1024 / 1024, 2),
    ];
}

// ========== STATS ==========
if ($action === 'stats') {
    $gen = getGeneratedStats($db, $root);
    $smp = getSamplesStats($db, $root);
    jsonResponse([
        'success' => true,
        'stats'   => [
            'generated'  => $gen,
            'samples'    => $smp,
            'total_need' => $gen['need'] + $smp['need'],
            'total_mb'   => round($gen['need_mb'] + $smp['need_mb'], 2),
        ],
    ]);
}

// ========== COMPRESS ==========
if ($action === 'compress') {
    $target    = $input['target'] ?? 'all';
    $batchSize = max(1, min(50, (int)($input['batch']  ?? 20)));
    $offset    = max(0, (int)($input['offset'] ?? 0));

    // Barcha kerakli joblarni yig'amiz
    $jobs = [];
    if ($target === 'generated' || $target === 'all') {
        $jobs = array_merge($jobs, getGeneratedJobsFromDB($db, $root));
    }
    if ($target === 'samples' || $target === 'all') {
        $jobs = array_merge($jobs, getSampleJobsFromDB($db, $root));
    }

    $totalAll = count($jobs);
    $batch    = array_slice($jobs, $offset, $batchSize);

    $converted = 0; $failed = 0; $savedBytes = 0; $errors = [];

    foreach ($batch as $job) {
        $src      = $job['src'];
        $dir      = dirname($src);
        $basename = pathinfo($src, PATHINFO_FILENAME);
        $webpPath = $dir . DIRECTORY_SEPARATOR . $basename . '.webp';

        // WebP allaqachon mavjud bo'lsa — aslini o'chirib o'tamiz
        if (file_exists($webpPath) && filesize($webpPath) > 0) {
            @unlink($src);
            $newUrl = preg_replace('/\.[^.]+$/', '.webp', $job['old_url']);
            updateDB($db, $job, $newUrl);
            $converted++;
            continue;
        }

        $origSize = filesize($src);
        $ok = compressImage($src, $webpPath, 85, 1600);

        if ($ok && file_exists($webpPath) && filesize($webpPath) > 0) {
            $savedBytes += max(0, $origSize - filesize($webpPath));
            // DB yo'lini yangilash
            $newUrl = preg_replace('/\.[^.]+$/', '.webp', $job['old_url']);
            updateDB($db, $job, $newUrl);
            @unlink($src);
            $converted++;
        } else {
            @unlink($webpPath);
            $failed++;
            $errors[] = basename($src);
        }
    }

    $remaining  = max(0, $totalAll - $offset - count($batch));
    $nextOffset = $offset + $batchSize;

    jsonResponse([
        'success'     => true,
        'converted'   => $converted,
        'failed'      => $failed,
        'saved_mb'    => round($savedBytes / 1024 / 1024, 2),
        'remaining'   => $remaining,
        'next_offset' => $nextOffset,
        'done'        => $remaining === 0,
        'errors'      => array_slice($errors, 0, 5),
    ]);
}

// DB ni yangilash yordamchi funksiyasi
function updateDB(PDO $db, array $job, string $newUrl): void {
    if ($job['type'] === 'generated') {
        $db->prepare("UPDATE generations SET image_path = ? WHERE id = ?")
           ->execute([$newUrl, $job['id']]);
    } else {
        $field = $job['field']; // image_path | before_image_path | after_image_path
        $db->prepare("UPDATE showcase_samples SET $field = ? WHERE id = ?")
           ->execute([$newUrl, $job['id']]);
    }
}

jsonResponse(['error' => "Noma'lum action"], 400);
