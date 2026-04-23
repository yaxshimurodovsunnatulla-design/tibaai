<?php
$apiKey = trim(file_get_contents('../uzum-api.txt'));

function callUzumAPITest($endpoint, $apiKey, $params = []) {
    $url = 'https://api-seller.uzum.uz/api/seller-openapi/v1/' . ltrim($endpoint, '/');
    if ($params) {
        $url .= '?' . http_build_query($params);
    }
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => [
            'Authorization: ' . trim($apiKey),
            'Accept: application/json'
        ]
    ]);
    $res = curl_exec($ch);
    if ($err = curl_error($ch)) { echo "CURL Error: $err\n"; }
    curl_close($ch);
    return json_decode($res, true);
}

$shops = callUzumAPITest('shops', $apiKey);
if (isset($shops['error']) || !$shops) {
    echo "Shops fetch failed: " . json_encode($shops) . "\n";
    exit;
}
echo "Found shops: " . count($shops) . "\n";
if (!empty($shops[0]['id'])) {
    $shopId = $shops[0]['id'];
    $data = callUzumAPITest("product/shop/$shopId", $apiKey, ['size' => 10, 'sortBy' => 'LEFTOVERS', 'order' => 'DESC']);
    echo "Data from product endpoint:\n";
    print_r(array_keys($data));
    if (isset($data['skuList'])) {
       echo "Has skuList\n";
       print_r($data['skuList'][0] ?? null);
    } else if (isset($data['items'])) {
       echo "Has items\n";
       print_r(array_keys($data['items'][0] ?? []));
    } else {
       echo "What does it have?\n";
       print_r($data[0] ?? null);
    }
}
