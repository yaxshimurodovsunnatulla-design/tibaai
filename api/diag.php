<?php
/**
 * Tiba AI — Server Diagnostika
 * WebP va Telegram funksiyalarini tekshirish
 * Ishlatgandan keyin O'CHIRING!
 */

// Xavfsizlik: faqat admin token bilan kirish
$adminToken = $_GET['token'] ?? '';
if ($adminToken !== 'tiba_diag_2026') {
    http_response_code(403);
    die('403 Forbidden');
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="uz">
<head>
<meta charset="UTF-8">
<title>Tiba AI Diagnostika</title>
<style>
body { font-family: monospace; background: #0f172a; color: #e2e8f0; padding: 20px; }
h2 { color: #60a5fa; border-bottom: 1px solid #334155; padding-bottom: 8px; }
.ok   { color: #4ade80; }
.fail { color: #f87171; }
.warn { color: #fbbf24; }
pre { background: #1e293b; padding: 12px; border-radius: 6px; overflow-x: auto; font-size: 13px; }
table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
td, th { border: 1px solid #334155; padding: 8px 12px; }
th { background: #1e293b; color: #93c5fd; text-align: left; }
</style>
</head>
<body>
<?php

require_once __DIR__ . '/config.php';

// ============================================================
// 1. PHP & Extension tekshirish
// ============================================================
echo "<h2>1. PHP & Kengaytmalar</h2><table>";
echo "<tr><th>Tekshiruv</th><th>Natija</th></tr>";

$checks = [
    'PHP versiyasi'            => PHP_VERSION,
    'GD kengaytmasi'           => extension_loaded('gd') ? '✅ BOR' : '❌ YO\'Q',
    'GD imagewebp() funksiyasi'=> function_exists('imagewebp') ? '✅ BORD' : '❌ YO\'Q',
    'GD imagecreatefromjpeg()' => function_exists('imagecreatefromjpeg') ? '✅ BORD' : '❌ YO\'Q',
    'GD imagecreatefrompng()'  => function_exists('imagecreatefrompng') ? '✅ BORD' : '❌ YO\'Q',
    'GD imagecreatefromwebp()' => function_exists('imagecreatefromwebp') ? '✅ BORD' : '❌ YO\'Q',
    'cURL kengaytmasi'         => extension_loaded('curl') ? '✅ BORD' : '❌ YO\'Q',
    'curl_exec()'              => function_exists('curl_exec') ? '✅ BORD' : '❌ YO\'Q',
    'memory_limit'             => ini_get('memory_limit'),
    'max_execution_time'       => ini_get('max_execution_time') . 's',
    'upload_max_filesize'      => ini_get('upload_max_filesize'),
    'disable_functions'        => ini_get('disable_functions') ?: '(hech narsa o\'chirilmagan)',
];

foreach ($checks as $name => $val) {
    $cls = (strpos($val, '❌') !== false) ? 'fail' : ((strpos($val, '✅') !== false) ? 'ok' : '');
    echo "<tr><td>$name</td><td class='$cls'>$val</td></tr>";
}
echo "</table>";

// GD Info
if (extension_loaded('gd')) {
    $info = gd_info();
    echo "<h2>GD Tafsilotlar</h2><pre>";
    echo "WebP Support: " . ($info['WebP Support'] ? '✅ HA' : '❌ YO\'Q') . "\n";
    echo "JPEG Support: " . ($info['JPEG Support'] ? '✅ HA' : '❌ YO\'Q') . "\n";
    echo "PNG Support:  " . ($info['PNG Support'] ? '✅ HA' : '❌ YO\'Q') . "\n";
    echo "GD Version:   " . ($info['GD Version'] ?? 'N/A') . "\n";
    echo "</pre>";
}

// ============================================================
// 2. .env sozlamalari
// ============================================================
echo "<h2>2. .env Sozlamalari</h2><table>";
echo "<tr><th>O'zgaruvchi</th><th>Holat</th></tr>";

$envVars = [
    'TELEGRAM_BOT_TOKEN'   => getenv('TELEGRAM_BOT_TOKEN'),
    'TELEGRAM_CHANNEL_ID'  => getenv('TELEGRAM_CHANNEL_ID'),
    'TELEGRAM_PROXY'       => getenv('TELEGRAM_PROXY'),
    'GEMINI_API_KEY'       => getenv('GEMINI_API_KEY'),
];

foreach ($envVars as $key => $val) {
    if ($val) {
        $display = ($key === 'TELEGRAM_BOT_TOKEN') 
            ? substr($val, 0, 10) . '...' . substr($val, -5)
            : $val;
        echo "<tr><td>$key</td><td class='ok'>✅ SET: $display</td></tr>";
    } else {
        echo "<tr><td>$key</td><td class='fail'>❌ YO'Q / BO'SH</td></tr>";
    }
}
echo "</table>";

// ============================================================
// 3. WebP funksiyasi testi
// ============================================================
echo "<h2>3. WebP Siqish Testi</h2>";

$tmpDir = __DIR__ . '/../tmp';
if (!is_dir($tmpDir)) @mkdir($tmpDir, 0755, true);

// 50x50 qizil rasm yaratib WebP ga o'zgartirish
$testResult = '❌ MUVAFFAQIYATSIZ';
$testDetails = '';
if (extension_loaded('gd') && function_exists('imagewebp')) {
    $img = imagecreatetruecolor(50, 50);
    $red = imagecolorallocate($img, 255, 50, 50);
    imagefill($img, 0, 0, $red);
    
    // PNG ga yozish
    $pngPath  = $tmpDir . '/diag_test.png';
    $webpPath = $tmpDir . '/diag_test.webp';
    imagepng($img, $pngPath);
    imagedestroy($img);
    
    if (file_exists($pngPath) && filesize($pngPath) > 0) {
        $testDetails .= "PNG yaratildi: " . filesize($pngPath) . " bytes\n";
        
        // compressImage funksiyasi testi
        $ok = compressImage($pngPath, $webpPath, 85, 1600);
        if ($ok && file_exists($webpPath) && filesize($webpPath) > 0) {
            $testResult = '✅ MUVAFFAQIYATLI';
            $testDetails .= "WebP yaratildi: " . filesize($webpPath) . " bytes\n";
            $testDetails .= "Tejamliligi: " . round((1 - filesize($webpPath)/filesize($pngPath)) * 100) . "% kichraydi\n";
        } else {
            $testDetails .= "compressImage() FALSE qaytardi\n";
        }
        @unlink($pngPath);
        @unlink($webpPath);
    } else {
        $testDetails .= "PNG yaratishda xato\n";
    }
} else {
    $testDetails = "GD yoki imagewebp() mavjud emas";
}

$cls = strpos($testResult, '✅') !== false ? 'ok' : 'fail';
echo "<p class='$cls'>$testResult</p>";
echo "<pre>$testDetails</pre>";

// ============================================================
// 4. Papkalar yozuv huquqi
// ============================================================
echo "<h2>4. Papkalar Huquqlari</h2><table>";
echo "<tr><th>Papka</th><th>Mavjud</th><th>Yozuv</th></tr>";

$dirs = [
    __DIR__ . '/../tmp'       => '/tmp',
    __DIR__ . '/../generated' => '/generated',
    __DIR__ . '/../data'      => '/data',
];

foreach ($dirs as $path => $label) {
    $exists   = is_dir($path) ? '✅' : '❌';
    $writable = is_writable($path) ? '✅ YOZILADI' : '❌ YOZILMAYDI';
    $cls = is_writable($path) ? 'ok' : 'fail';
    echo "<tr><td>$label</td><td>$exists</td><td class='$cls'>$writable</td></tr>";
}
echo "</table>";

// ============================================================
// 5. Telegram ulanish testi
// ============================================================
echo "<h2>5. Telegram API Ulanish Testi</h2>";

$botToken = getenv('TELEGRAM_BOT_TOKEN');
if (!$botToken) {
    echo "<p class='fail'>❌ TELEGRAM_BOT_TOKEN yo'q — test o'tkazilmadi</p>";
} elseif (!function_exists('curl_exec')) {
    echo "<p class='fail'>❌ curl_exec() o'chirilgan — Telegram ishlamaydi!</p>";
} else {
    // getMe testi
    $ch = curl_init("https://api.telegram.org/bot$botToken/getMe");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    // Proxy
    $proxy = getenv('TELEGRAM_PROXY');
    if ($proxy) {
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
        if (strpos($proxy, 'socks5') !== false) {
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        }
    }
    
    $res = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr  = curl_error($ch);
    curl_close($ch);
    
    if ($curlErr) {
        echo "<p class='fail'>❌ cURL xatosi: $curlErr</p>";
        if (strpos($curlErr, 'Connection refused') !== false || 
            strpos($curlErr, 'timed out') !== false) {
            echo "<p class='warn'>⚠️ Hosting Telegram API ga kirish imkoniga ega emas.<br>Proxy kerak bo'lishi mumkin: TELEGRAM_PROXY=socks5://...</p>";
        }
    } elseif ($httpCode === 200) {
        $data = json_decode($res, true);
        $botName = $data['result']['username'] ?? 'N/A';
        echo "<p class='ok'>✅ Telegram API ishlamoqda — Bot: @$botName</p>";
    } else {
        echo "<p class='fail'>❌ HTTP $httpCode: " . htmlspecialchars(substr($res, 0, 200)) . "</p>";
    }
    
    // Proxy tekshirish
    if ($proxy) {
        echo "<p class='warn'>⚠️ Proxy ishlatilmoqda: $proxy</p>";
    } else {
        echo "<p>ℹ️ Proxy sozlanmagan (to'g'ridan ulanish)</p>";
    }
}

// ============================================================
// 6. SSL/TLS tekshirish (Telegram uchun muhim)
// ============================================================
echo "<h2>6. SSL Tekshirish</h2>";
if (function_exists('curl_exec')) {
    $ch = curl_init("https://api.telegram.org");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // SSL verify ON
    curl_setopt($ch, CURLOPT_NOBODY, true);
    $res = curl_exec($ch);
    $sslErr = curl_error($ch);
    curl_close($ch);
    
    if ($sslErr) {
        echo "<p class='warn'>⚠️ SSL verify=ON xatosi: $sslErr</p>";
        echo "<p>SSL_VERIFYPEER=false ishlatilmoqda (hozircha ok)</p>";
    } else {
        echo "<p class='ok'>✅ SSL tekshiruvi muvaffaqiyatli</p>";
    }
}

// ============================================================
// 7. Yaqinda yozilgan error loglar
// ============================================================
echo "<h2>7. Server Error Loglari (oxirgi 30 qator)</h2>";
$logFiles = [
    __DIR__ . '/../tmp/telegram_debug.log',
    __DIR__ . '/../tmp/gemini_error.log',
];

foreach ($logFiles as $logFile) {
    if (file_exists($logFile)) {
        $lines = file($logFile);
        $last30 = array_slice($lines, -30);
        echo "<b>" . basename($logFile) . "</b><pre>" . htmlspecialchars(implode('', $last30)) . "</pre>";
    } else {
        echo "<p>📄 " . basename($logFile) . " — mavjud emas</p>";
    }
}

echo "<hr><p style='color:#64748b;font-size:12px'>⚠️ Bu faylni ishlatgandan keyin o'chiring: <code>api/diag.php</code></p>";
echo "</body></html>";
