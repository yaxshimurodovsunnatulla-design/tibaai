<?php
/**
 * Uzum Market Proxy — CORS cheklovini chetlab o'tish uchun
 * Frontend JavaScript -> bu proxy -> Uzum API
 */
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$query = $_GET['q'] ?? '';
if (empty($query)) {
    echo json_encode(['error' => 'Query parametri kerak', 'products' => []]);
    exit;
}

$query = mb_substr(trim($query), 0, 80);

// Uzum'ning ichki API endpointlarini sinab ko'rish
$endpoints = [
    'https://search.uzum.uz/api/v1/search/suggest?query=' . urlencode($query) . '&size=12',
    'https://search.uzum.uz/api/product?text=' . urlencode($query) . '&size=12',
    'https://search.uzum.uz/product/search?query=' . urlencode($query) . '&size=12',
    'https://api.uzum.uz/api/main/search?text=' . urlencode($query) . '&size=12',
];

$products = [];
$debugInfo = [];

foreach ($endpoints as $url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_TIMEOUT => 12,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        CURLOPT_ENCODING => '',
        CURLOPT_HTTPHEADER => [
            'Accept: application/json, text/plain, */*',
            'Accept-Language: uz-UZ,uz;q=0.9',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Origin: https://uzum.uz',
            'Referer: https://uzum.uz/uz/search?query=' . urlencode($query),
        ]
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    $debugInfo[] = ['url' => $url, 'http' => $httpCode, 'error' => $error, 'len' => strlen($response ?: '')];
    
    if ($httpCode == 200 && $response) {
        $data = json_decode($response, true);
        if ($data) {
            // Turli javob formatlarini tekshirish
            $items = $data['payload']['products'] ?? 
                     $data['products'] ?? 
                     $data['items'] ?? 
                     $data['payload']['items'] ?? 
                     $data['data'] ?? 
                     $data['result'] ?? [];
            
            if (!empty($items) && is_array($items)) {
                foreach ($items as $item) {
                    $price = 0;
                    // Turli narx maydonlarini tekshirish
                    foreach (['minSellPrice', 'sellPrice', 'price', 'minPrice', 'salePrice'] as $f) {
                        if (isset($item[$f]) && $item[$f] > 0) {
                            $price = (float)$item[$f];
                            if ($price > 100000) $price = $price / 100; // Tiyin -> So'm
                            break;
                        }
                    }
                    
                    $products[] = [
                        'title' => $item['title'] ?? $item['name'] ?? $item['productName'] ?? 'Nomsiz',
                        'price' => $price,
                        'rating' => (float)($item['rating'] ?? $item['reviewsAvgRate'] ?? 0),
                        'reviews' => (int)($item['reviewsAmount'] ?? $item['reviewsCount'] ?? 0),
                        'orders' => (int)($item['ordersAmount'] ?? $item['orders'] ?? $item['soldCount'] ?? 0),
                        'productId' => $item['productId'] ?? $item['id'] ?? 0,
                    ];
                }
                break; // Ishlaydigan endpoint topildi
            }
        }
    }
}

// Agar hech qanday API ishlamasa — Uzum HTML sahifasini Googlebot sifatida o'qishga harakat
if (empty($products)) {
    $searchUrl = 'https://uzum.uz/uz/search?query=' . urlencode($query);
    $ch = curl_init($searchUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        CURLOPT_ENCODING => '',
        CURLOPT_HTTPHEADER => [
            'Accept: text/html',
            'Accept-Language: uz-UZ',
            'User-Agent: Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
        ]
    ]);
    $html = curl_exec($ch);
    $htmlCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $debugInfo[] = ['url' => $searchUrl, 'http' => $htmlCode, 'len' => strlen($html ?: ''), 'method' => 'html_scrape'];
    
    if ($html && $htmlCode == 200) {
        // JSON-LD yoki script ichidan tovar ma'lumotlarini qidirish
        if (preg_match_all('/"productId"\s*:\s*(\d+).*?"title"\s*:\s*"([^"]+)".*?"(?:minSellPrice|sellPrice|price)"\s*:\s*(\d+)/s', $html, $m, PREG_SET_ORDER)) {
            foreach (array_slice($m, 0, 10) as $match) {
                $price = (float)$match[3];
                if ($price > 100000) $price /= 100;
                $products[] = [
                    'title' => $match[2],
                    'price' => $price,
                    'rating' => 0,
                    'reviews' => 0,
                    'orders' => 0,
                    'productId' => (int)$match[1],
                ];
            }
        }
    }
}

echo json_encode([
    'success' => !empty($products),
    'products' => $products,
    'count' => count($products),
    'query' => $query,
    'debug' => $debugInfo,
    'timestamp' => date('Y-m-d H:i:s')
], JSON_UNESCAPED_UNICODE);
