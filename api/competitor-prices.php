<?php
/**
 * Raqiblar Narxi Monitori API
 * Uzum Market'ning ochiq qidiruv API'sidan foydalanib,
 * foydalanuvchining tovarlariga o'xshash raqobatchi tovarlarni topadi
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'POST method required']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$products = $input['products'] ?? []; // [{title, price, productId}]

if (empty($products)) {
    echo json_encode(['error' => 'Tovarlar ro\'yxati bo\'sh', 'results' => []]);
    exit;
}

/**
 * Uzum public katalogdan qidiruv
 */
function searchUzumCatalog($query, $limit = 6) {
    // Qisqa va aniq qidiruv uchun so'zlarni tozalash
    $cleanQuery = mb_substr(trim($query), 0, 60);
    
    $url = 'https://search.uzum.uz/api/product/search?' . http_build_query([
        'query' => $cleanQuery,
        'size' => $limit,
        'page' => 0
    ]);
    
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Accept-Language: uz-UZ',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        ]
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200 || !$response) {
        return [];
    }
    
    $data = json_decode($response, true);
    
    $results = [];
    $items = $data['payload']['products'] ?? $data['products'] ?? $data['items'] ?? [];
    
    foreach ($items as $item) {
        // Narxni olish (tiyin -> so'm)
        $price = 0;
        if (isset($item['minSellPrice'])) {
            $price = (float)$item['minSellPrice'] / 100;
        } elseif (isset($item['sellPrice'])) {
            $price = (float)$item['sellPrice'] / 100; 
        } elseif (isset($item['price'])) {
            $price = (float)$item['price'];
        }
        
        $originalPrice = 0;
        if (isset($item['minFullPrice'])) {
            $originalPrice = (float)$item['minFullPrice'] / 100;
        } elseif (isset($item['fullPrice'])) {
            $originalPrice = (float)$item['fullPrice'] / 100;
        }
        
        $rating = (float)($item['rating'] ?? $item['reviewsAvgRate'] ?? 0);
        $reviews = (int)($item['reviewsAmount'] ?? $item['reviewsCount'] ?? 0);
        $orders = (int)($item['ordersAmount'] ?? $item['orders'] ?? 0);
        
        $results[] = [
            'title' => $item['title'] ?? $item['name'] ?? 'Noma\'lum',
            'price' => $price,
            'original_price' => $originalPrice,
            'rating' => $rating,
            'reviews' => $reviews,
            'orders' => $orders,
            'image' => $item['image'] ?? $item['photo'] ?? '',
            'productId' => $item['productId'] ?? $item['id'] ?? 0
        ];
    }
    
    return $results;
}

$results = [];

// Har bir tovar uchun raqiblarni qidirish (top 5 tovar)
$productsToCheck = array_slice($products, 0, 5);

foreach ($productsToCheck as $product) {
    $title = $product['title'] ?? '';
    $myPrice = (float)($product['price'] ?? 0);
    $myProductId = $product['productId'] ?? 0;
    
    if (empty($title)) continue;
    
    // Kalit so'zlarni ajratish (3+ harfli so'zlar)
    $words = preg_split('/[\s,\-\/\(\)]+/u', $title);
    $keywords = array_filter($words, function($w) {
        return mb_strlen(trim($w)) >= 3;
    });
    $searchQuery = implode(' ', array_slice(array_values($keywords), 0, 4));
    
    if (empty($searchQuery)) $searchQuery = $title;
    
    $competitors = searchUzumCatalog($searchQuery, 6);
    
    // O'z tovarini chiqarib tashlash
    $competitors = array_filter($competitors, function($c) use ($myProductId) {
        return $c['productId'] != $myProductId;
    });
    $competitors = array_values($competitors);
    
    // Raqiblar narxini taqqoslash
    if (!empty($competitors)) {
        $competitorPrices = array_column($competitors, 'price');
        $competitorPrices = array_filter($competitorPrices, function($p) { return $p > 0; });
        
        $avgCompPrice = !empty($competitorPrices) ? array_sum($competitorPrices) / count($competitorPrices) : 0;
        $minCompPrice = !empty($competitorPrices) ? min($competitorPrices) : 0;
        $maxCompPrice = !empty($competitorPrices) ? max($competitorPrices) : 0;
        
        // Narx pozitsiyasi
        $pricePosition = 'UNKNOWN';
        $priceDiffPercent = 0;
        if ($myPrice > 0 && $avgCompPrice > 0) {
            $priceDiffPercent = round((($myPrice - $avgCompPrice) / $avgCompPrice) * 100, 1);
            if ($priceDiffPercent > 15) {
                $pricePosition = 'EXPENSIVE'; // Sizniki qimmatroq
            } elseif ($priceDiffPercent < -15) {
                $pricePosition = 'CHEAP'; // Sizniki arzonroq
            } else {
                $pricePosition = 'COMPETITIVE'; // Bozor narxida
            }
        }
        
        // AI Tavsiya
        $advice = '';
        if ($pricePosition === 'EXPENSIVE') {
            $advice = "Sizning narxingiz bozor o'rtachasidan " . abs($priceDiffPercent) . "% qimmatroq. Agar kartochkangiz sifati (rasm, tavsif) raqiblardan ustun bo'lmasa, mijozlar arzonroq variantni tanlashi tabiiy. Yechim: Infografika sifatini oshiring yoki narxni " . number_format($avgCompPrice, 0, '.', ' ') . " so'm atrofiga tushiring.";
        } elseif ($pricePosition === 'CHEAP') {
            $advice = "Siz raqiblardan " . abs($priceDiffPercent) . "% arzonroqsiz — bu yaxshi, lekin juda arzon bo'lish ortiqcha foyda yo'qotish! Narxni asta-sekin " . number_format($avgCompPrice * 0.92, 0, '.', ' ') . " so'm gacha ko'tarish mumkin.";
        } else {
            $advice = "Narxingiz raqobatbardosh holatda. O'z pozitsiyangizni saqlab qolish uchun mahsulot kartochkangiz vizual sifatini kuchaytirishga e'tibor qarating — bu konversiyani oshiradi.";
        }
        
        $results[] = [
            'my_product' => [
                'title' => $title,
                'price' => $myPrice,
                'productId' => $myProductId
            ],
            'competitors' => array_slice($competitors, 0, 4),
            'analysis' => [
                'avg_price' => round($avgCompPrice),
                'min_price' => round($minCompPrice),
                'max_price' => round($maxCompPrice),
                'price_position' => $pricePosition,
                'price_diff_percent' => $priceDiffPercent,
                'advice' => $advice
            ]
        ];
    }
    
    // API cheklovi uchun biroz kutish
    usleep(300000); // 300ms
}

echo json_encode([
    'success' => true,
    'results' => $results,
    'checked_count' => count($results),
    'timestamp' => date('Y-m-d H:i:s')
]);
