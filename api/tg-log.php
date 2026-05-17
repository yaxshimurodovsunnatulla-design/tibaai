<?php
// Telegram debug log o'qish (vaqtinchalik — ishlatgach o'chiring!)
header('Content-Type: text/plain; charset=utf-8');
$log = __DIR__ . '/../tmp/telegram_debug.log';
if (file_exists($log)) {
    // So'nggi 5000 belgini ko'rsatish
    $content = file_get_contents($log);
    $len = strlen($content);
    echo "=== Telegram Debug Log ($len bytes) ===\n\n";
    echo $len > 5000 ? '...' . substr($content, -5000) : $content;
} else {
    echo "Log fayl topilmadi: $log\n";
    echo "tmp/ exists: " . (is_dir(__DIR__ . '/../tmp') ? 'YES' : 'NO') . "\n";
}
