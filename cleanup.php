<?php
/**
 * Tiba AI – Eski generatsiya rasmlarini tozalash
 * 7 kundan eski rasmlarni avtomatik o'chiradi
 * 
 * Ishlatish: php cleanup.php
 * Cron: 0 3 * * * php /path/to/cleanup.php
 */

$dir = __DIR__ . '/generated';
$maxAgeDays = 7;
$maxAgeSeconds = $maxAgeDays * 86400;
$now = time();
$deleted = 0;
$kept = 0;
$totalSize = 0;

if (!is_dir($dir)) {
    echo "Generated papka topilmadi.\n";
    exit;
}

echo "=== Tiba AI – Rasmlarni tozalash ===\n";
echo "Papka: $dir\n";
echo "Eski: {$maxAgeDays}+ kun\n\n";

$files = glob($dir . '/*.{png,jpg,jpeg,webp}', GLOB_BRACE);

foreach ($files as $file) {
    $age = $now - filemtime($file);
    $size = filesize($file);
    
    if ($age > $maxAgeSeconds) {
        $totalSize += $size;
        unlink($file);
        $deleted++;
    } else {
        $kept++;
    }
}

$totalMB = round($totalSize / 1024 / 1024, 2);
echo "✅ Natija:\n";
echo "   O'chirildi: {$deleted} ta fayl ({$totalMB} MB)\n";
echo "   Qoldi: {$kept} ta fayl\n";
