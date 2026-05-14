<?php
/**
 * Tiba AI — Batch Image Compression API
 * Barcha mavjud PNG/JPG rasmlarni WebP ga aylantiradi
 * DB da saqlangan yo'llarni ham yangilaydi
 *
 * POST { action: 'stats' }          — statistika
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

$input = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $input['action'] ?? 'stats';

// ========== STATS ==========
if ($action === 'stats') {
    $genDir = __DIR__ . '/../generated';
    $smpDir = __DIR__ . '/../assets/samples';

    $result = [];
    foreach ([
        'generated' => $genDir,
        'samples'   => $smpDir,
    ] as $key => $dir) {
        $needCompress = glob($dir . '/*.{png,jpg,jpeg}', GLOB_BRACE) ?: [];
        $alreadyWebp  = glob($dir . '/*.webp') ?: [];
        $needBytes    = array_sum(array_map('filesize', $needCompress));
        $webpBytes    = array_sum(array_map('filesize', $alreadyWebp));
        $result[$key] = [
            'need'      => count($needCompress),
            'done'      => count($alreadyWebp),
            'need_mb'   => round($needBytes / 1024 / 1024, 2),
            'done_mb'   => round($webpBytes / 1024 / 1024, 2),
        ];
    }
    $result['total_need'] = $result['generated']['need'] + $result['samples']['need'];
    $result['total_mb']   = $result['generated']['need_mb'] + $result['samples']['need_mb'];
    jsonResponse(['success' => true, 'stats' => $result]);
}

// ========== COMPRESS ==========
if ($action === 'compress') {
    $target   = $input['target']   ?? 'all';   // generated | samples | all
    $batchSize= max(1, min(50, (int)($input['batch']  ?? 20)));
    $offset   = max(0, (int)($input['offset'] ?? 0));

    $genDir   = __DIR__ . '/../generated';
    $smpDir   = __DIR__ . '/../assets/samples';

    // Compress qilinadigan fayllarni yig'amiz
    $jobs = []; // [['src'=>path,'type'=>generated|samples,'id'=>null,'field'=>null],...]

    if ($target === 'generated' || $target === 'all') {
        $files = glob($genDir . '/*.{png,jpg,jpeg}', GLOB_BRACE) ?: [];
        foreach ($files as $f) {
            $jobs[] = ['src' => $f, 'type' => 'generated', 'id' => null, 'field' => null];
        }
    }
    if ($target === 'samples' || $target === 'all') {
        $files = glob($smpDir . '/*.{png,jpg,jpeg}', GLOB_BRACE) ?: [];
        foreach ($files as $f) {
            $jobs[] = ['src' => $f, 'type' => 'samples', 'id' => null, 'field' => null];
        }
    }

    $totalAll  = count($jobs);
    $batch     = array_slice($jobs, $offset, $batchSize);

    $converted  = 0;
    $failed     = 0;
    $savedBytes = 0;
    $errors     = [];

    foreach ($batch as $job) {
        $src      = $job['src'];
        $dir      = dirname($src);
        $basename = pathinfo($src, PATHINFO_FILENAME);
        $webpPath = $dir . '/' . $basename . '.webp';

        // Agar WebP allaqachon mavjud bo'lsa — o'tkazib yuboramiz
        if (file_exists($webpPath)) {
            @unlink($src);
            $converted++;
            continue;
        }

        $origSize = filesize($src);
        $ok = compressImage($src, $webpPath, 85, 1600);

        if ($ok && file_exists($webpPath) && filesize($webpPath) > 0) {
            $newSize    = filesize($webpPath);
            $savedBytes += max(0, $origSize - $newSize);

            // DB yo'lini yangilash
            $oldUrl = ($job['type'] === 'generated' ? '/generated/' : '/assets/samples/') . basename($src);
            $newUrl = ($job['type'] === 'generated' ? '/generated/' : '/assets/samples/') . $basename . '.webp';

            if ($job['type'] === 'generated') {
                // generations.image_path
                $upd = $db->prepare("UPDATE generations SET image_path = ? WHERE image_path = ?");
                $upd->execute([$newUrl, $oldUrl]);
            } else {
                // showcase_samples — image_path, before_image_path, after_image_path
                foreach (['image_path', 'before_image_path', 'after_image_path'] as $field) {
                    $upd = $db->prepare("UPDATE showcase_samples SET $field = ? WHERE $field = ?");
                    $upd->execute([$newUrl, $oldUrl]);
                }
            }

            // Asl faylni o'chirish
            @unlink($src);
            $converted++;
        } else {
            @unlink($webpPath); // yaroqsiz webp ni tozalash
            $failed++;
            $errors[] = basename($src);
        }
    }

    $remaining = max(0, $totalAll - $offset - count($batch));
    $nextOffset = $offset + $batchSize;

    jsonResponse([
        'success'    => true,
        'converted'  => $converted,
        'failed'     => $failed,
        'saved_mb'   => round($savedBytes / 1024 / 1024, 2),
        'remaining'  => $remaining,
        'next_offset'=> $nextOffset,
        'done'       => $remaining === 0,
        'errors'     => array_slice($errors, 0, 5),
    ]);
}

jsonResponse(['error' => "Noma'lum action"], 400);
