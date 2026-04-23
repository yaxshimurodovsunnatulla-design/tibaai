<?php
// Pure PHP debug script without inclusion of complex project files
date_default_timezone_set('Asia/Tashkent');

$apiKey = "eP/1k3BJbWnfgvYSxE21TYRs06tgbnQegiPVkQlXxXg=";

function callUzum($url, $apiKey) {
    echo "Calling: $url\n";
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: $apiKey",
            "Accept: application/json"
        ],
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false
    ]);
    $res = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['code' => $http, 'res' => $res];
}

echo "--- START DEBUG ---\n";

// 1. Get Shops
$r = callUzum("https://api-seller.uzum.uz/api/seller-openapi/v1/shops", $apiKey);
echo "Shops Result (" . $r['code'] . "): " . $r['res'] . "\n\n";

$shops = json_decode($r['res'], true);
if (empty($shops)) {
    die("No shops found. Stopping.\n");
}

$shopIds = array_column($shops, 'id');

// 2. Try Finance Orders with NO dates (Raw)
$shopQuery = "";
foreach ($shopIds as $id) $shopQuery .= "&shopIds=$id";
$shopQuery = ltrim($shopQuery, "&");

$r = callUzum("https://api-seller.uzum.uz/api/seller-openapi/v1/finance/orders?$shopQuery&size=5", $apiKey);
echo "Finance Orders (No Dates) Result (" . $r['code'] . "): " . $r['res'] . "\n\n";

// 3. Try Finance Orders with 1 year millisecond range
$dFrom = (time() - (365 * 24 * 3600)) * 1000;
$dTo = (time() + (24 * 3600)) * 1000; // include tomorrow to be safe
$r = callUzum("https://api-seller.uzum.uz/api/seller-openapi/v1/finance/orders?$shopQuery&dateFrom=$dFrom&dateTo=$dTo&size=5", $apiKey);
echo "Finance Orders (1 Year ms) Result (" . $r['code'] . "): " . $r['res'] . "\n\n";

// 4. Try FBS Orders
$r = callUzum("https://api-seller.uzum.uz/api/seller-openapi/v2/fbs/orders?page=0&size=5&$shopQuery", $apiKey);
echo "FBS Orders Result (" . $r['code'] . "): " . $r['res'] . "\n\n";

// 5. Try Products for first shop
$firstShop = $shopIds[0];
$r = callUzum("https://api-seller.uzum.uz/api/seller-openapi/v1/product/shop/$firstShop?size=5&page=0", $apiKey);
echo "Products for Shop $firstShop Result (" . $r['code'] . "): " . substr($r['res'], 0, 500) . "...\n\n";

echo "--- END DEBUG ---\n";
?>
