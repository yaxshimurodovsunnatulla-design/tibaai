<?php
/**
 * Tiba AI — Server Diagnostics
 * MUHIM: Ishlatib bo'lgach o'chiring!
 */
header('Content-Type: application/json; charset=utf-8');

$genDir  = __DIR__ . '/../generated';
$results = [];

// 1. PHP versiyasi
$results['php_version'] = PHP_VERSION;

// 2. Memory limit
$results['memory_limit']      = ini_get('memory_limit');
$results['memory_limit_set']  = ini_set('memory_limit', '512M') !== false ? '512M' : 'FAILED';
$results['memory_current_mb'] = round(memory_get_usage(true) / 1024 / 1024, 1) . 'MB';

// 3. GD tekshirish
$results['gd_loaded']         = extension_loaded('gd');
$results['imagewebp']         = function_exists('imagewebp');
$results['imagecreatefromstring'] = function_exists('imagecreatefromstring');
$results['imagecreatefrompng']= function_exists('imagecreatefrompng');
if (function_exists('gd_info')) {
    $gd = gd_info();
    $results['gd_webp_support'] = $gd['WebP Support'] ?? false;
    $results['gd_png_support']  = $gd['PNG Support'] ?? false;
    $results['gd_jpeg_support'] = $gd['JPEG Support'] ?? false;
}

// 4. generated/ papkasi
$results['gen_dir_exists']    = is_dir($genDir);
$results['gen_dir_writable']  = is_writable($genDir);
if (!is_dir($genDir)) {
    $results['gen_dir_mkdir'] = @mkdir($genDir, 0755, true) ? 'OK' : 'FAILED';
    $results['gen_dir_exists_now'] = is_dir($genDir);
}

// 5. File write test
$testFile = $genDir . '/test_write_' . time() . '.txt';
$results['file_write_test'] = @file_put_contents($testFile, 'test') !== false ? 'OK' : 'FAILED';
if (file_exists($testFile)) @unlink($testFile);

// 6. imagecreatefromstring test (1x1 PNG)
$png1x1 = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
$testImg = @imagecreatefromstring($png1x1);
$results['imagecreatefromstring_test'] = $testImg ? 'OK' : 'FAILED';
if ($testImg) {
    // imagewebp test
    $webpOut = $genDir . '/test_webp_' . time() . '.webp';
    $webpOk = @imagewebp($testImg, $webpOut, 85);
    $results['imagewebp_write_test'] = ($webpOk && file_exists($webpOut)) ? 'OK (' . filesize($webpOut) . ' bytes)' : 'FAILED';
    if (file_exists($webpOut)) @unlink($webpOut);
    imagedestroy($testImg);
}

// 7. Error log path
$results['error_log'] = ini_get('error_log') ?: 'not set';

// 8. disable_functions
$disabled = ini_get('disable_functions');
$results['disable_functions'] = $disabled ?: 'none';

// 9. compressImage test (config.php dan)
require_once __DIR__ . '/config.php';
if (function_exists('compressImage')) {
    $tmpPng = $genDir . '/test_compress_' . time() . '.png';
    file_put_contents($tmpPng, $png1x1);
    $tmpWebp = $genDir . '/test_compress_out_' . time() . '.webp';
    $compOk = compressImage($tmpPng, $tmpWebp, 85, 100);
    $results['compressImage_test'] = $compOk ? 'OK' : 'FAILED';
    @unlink($tmpPng);
    @unlink($tmpWebp);
} else {
    $results['compressImage_test'] = 'function not found';
}

echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
