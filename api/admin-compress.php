<?php
/**
 * Tiba AI — Batch Image Compression API
 * generated/   → PNG/JPG → WebP
 * assets/samples/ → PNG/JPG → WebP
 * DB yo'llari ham avtomatik yangilanadi
 */
require_once __DIR__ . '/config.php';

// Admin auth
$sessionToken = $_SERVER['HTTP_X_ADMIN_SESSION'] ?? $_COOKIE['admin_session'] ?? '';
if (empty($sessionToken)) jsonResponse(['error' => 'Admin session required'], 401);
$db   = getDB();
$stmt = $db->prepare("SELECT id FROM admin_sessions WHERE token = ? AND status = 'active' AND expires_at > datetime('now')");
$stmt->execute([$sessionToken]);
if (!$stmt->fetch()) jsonResponse(['error' => 'Invalid or expired admin session'], 401);

$input  = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $input['action'] ?? 'stats';
$root   = realpath(__DIR__ . '/..');          // D:\887779999\tiba-ai

$genDir = $root . DIRECTORY_SEPARATOR . 'generated';
$smpDir = $root . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'samples';

// ----------------------------------------------------------------
// Yordamchi: PNG/JPG fayllarni topish (GLOB_BRACE muammolarisiz)
// ----------------------------------------------------------------
function findImages(string $dir): array {
    if (!is_dir($dir)) return [];
    $files = [];
    foreach (['*.png','*.jpg','*.jpeg','*.PNG','*.JPG','*.JPEG'] as $pat) {
        $files = array_merge($files, glob($dir . DIRECTORY_SEPARATOR . $pat) ?: []);
    }
    return array_unique($files);
}

function findWebp(string $dir): array {
    if (!is_dir($dir)) return [];
    return glob($dir . DIRECTORY_SEPARATOR . '*.webp') ?: [];
}

// ----------------------------------------------------------------
// DB yo'lini yangilash
// ----------------------------------------------------------------
function updateDbPath(PDO $db, string $type, string $oldUrl, string $newUrl): void {
    if ($type === 'generated') {
        $db->prepare("UPDATE generations SET image_path = ? WHERE image_path = ?")
           ->execute([$newUrl, $oldUrl]);
    } else {
        foreach (['image_path', 'before_image_path', 'after_image_path'] as $field) {
            $db->prepare("UPDATE showcase_samples SET {$field} = ? WHERE {$field} = ?")
               ->execute([$newUrl, $oldUrl]);
        }
    }
}

// ----------------------------------------------------------------
// URL yo'lini hisoblash (root dan keyin qolgan qism)
// ----------------------------------------------------------------
function toUrl(string $absPath, string $root): string {
    $rel = substr($absPath, strlen($root));
    return str_replace('\\', '/', $rel); // Windows backslash → slash
}

// ========== STATS ==========
if ($action === 'stats') {
    $genNeed  = findImages($genDir);
    $genDone  = findWebp($genDir);
    $smpNeed  = findImages($smpDir);
    $smpDone  = findWebp($smpDir);

    $genNeedMb = array_sum(array_map('filesize', $genNeed)) / 1024 / 1024;
    $smpNeedMb = array_sum(array_map('filesize', $smpNeed)) / 1024 / 1024;

    jsonResponse([
        'success' => true,
        'stats'   => [
            'generated'  => [
                'need'    => count($genNeed),
                'done'    => count($genDone),
                'need_mb' => round($genNeedMb, 2),
                'done_mb' => 0,
            ],
            'samples'    => [
                'need'    => count($smpNeed),
                'done'    => count($smpDone),
                'need_mb' => round($smpNeedMb, 2),
                'done_mb' => 0,
            ],
            'total_need' => count($genNeed) + count($smpNeed),
            'total_mb'   => round($genNeedMb + $smpNeedMb, 2),
        ],
    ]);
}

// ========== COMPRESS ==========
if ($action === 'compress') {
    $target    = $input['target'] ?? 'all';
    $batchSize = max(1, min(50, (int)($input['batch']  ?? 20)));
    $offset    = max(0, (int)($input['offset'] ?? 0));

    // Joblar ro'yxati: [['src'=>abs,'type'=>generated|samples], ...]
    $jobs = [];

    if ($target === 'generated' || $target === 'all') {
        foreach (findImages($genDir) as $f) {
            $jobs[] = ['src' => $f, 'type' => 'generated'];
        }
    }
    if ($target === 'samples' || $target === 'all') {
        foreach (findImages($smpDir) as $f) {
            $jobs[] = ['src' => $f, 'type' => 'samples'];
        }
    }

    $totalAll  = count($jobs);
    $batch     = array_slice($jobs, $offset, $batchSize);

    $converted = 0; $failed = 0; $savedBytes = 0; $errors = [];

    foreach ($batch as $job) {
        $src      = $job['src'];
        $webpPath = preg_replace('/\.[^.]+$/', '.webp', $src);

        $origSize = file_exists($src) ? filesize($src) : 0;

        // WebP allaqachon bor — aslini o'chirib, DB yangilash
        if (file_exists($webpPath) && filesize($webpPath) > 0) {
            @unlink($src);
            $oldUrl = toUrl($src, $root);
            $newUrl = toUrl($webpPath, $root);
            updateDbPath($db, $job['type'], $oldUrl, $newUrl);
            $converted++;
            continue;
        }

        $ok = compressImage($src, $webpPath, 85, 1600);

        if ($ok && file_exists($webpPath) && filesize($webpPath) > 0) {
            $savedBytes += max(0, $origSize - filesize($webpPath));
            $oldUrl = toUrl($src, $root);
            $newUrl = toUrl($webpPath, $root);
            updateDbPath($db, $job['type'], $oldUrl, $newUrl);
            @unlink($src);
            $converted++;
        } else {
            @unlink($webpPath); // yaroqsiz faylni o'chirish
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

jsonResponse(['error' => "Noma'lum action"], 400);
