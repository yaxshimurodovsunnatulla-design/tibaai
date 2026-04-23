<?php
date_default_timezone_set('Asia/Tashkent');
$apiKey = "eP/1k3BJbWnfgvYSxE21TYRs06tgbnQegiPVkQlXxXg=";

function callUzum($url, $apiKey) {
    echo "Calling: $url\n";
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["Authorization: $apiKey", "Accept: application/json"],
        CURLOPT_SSL_VERIFYPEER => false
    ]);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}

$shopIds = [80778, 81221, 86291];
$shopQuery = "shopIds=80778&shopIds=81221&shopIds=86291";

// Test 1: Milliseconds (Current approach)
$dFromMs = (time() - 86400 * 30) * 1000;
$dToMs = (time() + 86400) * 1000;
echo "--- TEST 1: Milliseconds ---\n";
echo callUzum("https://api-seller.uzum.uz/api/seller-openapi/v1/finance/orders?$shopQuery&dateFrom=$dFromMs&dateTo=$dToMs&size=1", $apiKey) . "\n\n";

// Test 2: Seconds
$dFromSec = (time() - 86400 * 30);
$dToSec = (time() + 86400);
echo "--- TEST 2: Seconds ---\n";
echo callUzum("https://api-seller.uzum.uz/api/seller-openapi/v1/finance/orders?$shopQuery&dateFrom=$dFromSec&dateTo=$dToSec&size=1", $apiKey) . "\n\n";

// Test 3: ISO Strings (just in case schema is misleading)
$dFromIso = date('Y-m-d\TH:i:s.v\Z', time() - 86400 * 30);
$dToIso = date('Y-m-d\TH:i:s.v\Z', time() + 86400);
echo "--- TEST 3: ISO Strings ---\n";
echo callUzum("https://api-seller.uzum.uz/api/seller-openapi/v1/finance/orders?$shopQuery&dateFrom=$dFromIso&dateTo=$dToIso&size=1", $apiKey) . "\n\n";
?>
