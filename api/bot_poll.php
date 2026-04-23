<?php
/**
 * Tiba AI — Polling Script
 * SSL muammosi bo'lganda to'lovlarni qo'lda qayta ishlash uchun.
 * 
 * Ishlatish: php api/bot_poll.php
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/payment_bot.php'; // processPaymentAction funksiyasini ishlatish uchun

$botToken = $_ENV['TELEGRAM_BOT_TOKEN'] ?? $_SERVER['TELEGRAM_BOT_TOKEN'] ?? getenv('TELEGRAM_BOT_TOKEN');

if (!$botToken) {
    die("Error: Bot token topilmadi.\n");
}

echo "Local Polling ishga tushdi... (Xabarlar tekshirilmoqda)\n";

$offset = 0;
while (true) {
    echo "."; // Activity indicator
    $url = "https://api.telegram.org/bot{$botToken}/getUpdates?offset={$offset}&timeout=30";
    $res = @file_get_contents($url);
    if ($res === false) {
        echo "\n[ERROR] Telegram'ga ulanib bo'lmadi. Internetni tekshiring.\n";
        sleep(5);
        continue;
    }
    
    $data = json_decode($res, true);
    if (isset($data['result']) && !empty($data['result'])) {
        echo "\n" . count($data['result']) . " ta yangi xabar keldi.\n";
        foreach ($data['result'] as $update) {
            $offset = $update['update_id'] + 1;
            handleBotUpdate($update);
        }
    }
}
