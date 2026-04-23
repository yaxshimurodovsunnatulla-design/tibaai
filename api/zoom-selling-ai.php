<?php
/**
 * Zoom Selling AI — Uzum Market Kategoriya va Tovar Tahlili
 * Kategoriyalar, bo'limlar va har bir tovar uchun chuqur AI tahlil
 */
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$action = $_GET['action'] ?? $_POST['action'] ?? '';

// ============ KATEGORIYALAR RO'YXATI ============
if ($action === 'categories') {
    // Uzum kategoriyalar API
    $categories = fetchUzumCategories();
    echo json_encode([
        'success' => !empty($categories),
        'categories' => $categories,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// ============ KATEGORIYA TOVARLARINI OLISH ============
if ($action === 'category-products') {
    $categoryId = $_GET['categoryId'] ?? '';
    $slug = $_GET['slug'] ?? '';
    $sort = $_GET['sort'] ?? 'POPULAR';
    $offset = (int)($_GET['offset'] ?? 0);
    $limit = (int)($_GET['limit'] ?? 24);
    
    if (!$categoryId && !$slug) {
        echo json_encode(['error' => 'categoryId yoki slug kerak']);
        exit;
    }
    
    $products = fetchCategoryProducts($categoryId, $slug, $sort, $offset, $limit);
    echo json_encode([
        'success' => !empty($products),
        'products' => $products,
        'count' => count($products),
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// ============ TOVAR CHUQUR TAHLILI ============
if ($action === 'product-analysis') {
    $productId = $_GET['productId'] ?? '';
    if (!$productId) {
        echo json_encode(['error' => 'productId kerak']);
        exit;
    }
    
    $analysis = fetchProductDetails($productId);
    echo json_encode([
        'success' => !empty($analysis),
        'analysis' => $analysis,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// ============ QIDIRUV ============
if ($action === 'search') {
    $query = $_GET['q'] ?? '';
    $sort = $_GET['sort'] ?? 'POPULAR';
    $offset = (int)($_GET['offset'] ?? 0);
    
    if (!$query) {
        echo json_encode(['error' => 'Qidiruv so\'zi kerak']);
        exit;
    }
    
    $results = searchUzumProducts($query, $sort, $offset);
    echo json_encode([
        'success' => !empty($results['products']),
        'products' => $results['products'] ?? [],
        'totalCount' => $results['totalCount'] ?? 0,
        'categoryAnalysis' => $results['categoryAnalysis'] ?? null,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode(['error' => 'Noto\'g\'ri action parametri. Mavjudlar: categories, category-products, product-analysis, search']);

// ========================================================================
// FUNKSIYALAR
// ========================================================================

function curlFetch($url, $headers = [], $timeout = 15) {
    $ch = curl_init($url);
    $defaultHeaders = [
        'Accept: application/json, text/plain, */*',
        'Accept-Language: uz-UZ,uz;q=0.9',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Origin: https://uzum.uz',
        'Referer: https://uzum.uz/',
    ];
    
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_TIMEOUT => $timeout,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        CURLOPT_ENCODING => '',
        CURLOPT_HTTPHEADER => array_merge($defaultHeaders, $headers),
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    return ['body' => $response, 'code' => $httpCode, 'error' => $error];
}

function graphqlQuery($query) {
    $ch = curl_init('https://graphql.uzum.uz/graphql');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        CURLOPT_ENCODING => '',
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
            'apollographql-client-name: web-customers',
            'x-iid: random_uuid',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'Origin: https://uzum.uz',
            'Referer: https://uzum.uz/',
        ],
        CURLOPT_POSTFIELDS => json_encode(['query' => $query]),
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200 && $response) {
        return json_decode($response, true);
    }
    return null;
}

function fetchUzumCategories() {
    // Uzum katalog API orqali kategoriyalarni olish
    $result = curlFetch('https://api.uzum.uz/api/main/root-categories?eco=false');
    
    if ($result['code'] === 200 && $result['body']) {
        $data = json_decode($result['body'], true);
        if (isset($data['payload'])) {
            return formatCategories($data['payload']);
        }
    }
    
    // GraphQL orqali fallback
    $gql = graphqlQuery('{
        makeRootCategories {
            category {
                id
                title
                icon
                children {
                    category {
                        id
                        title
                        children {
                            category {
                                id
                                title
                            }
                        }
                    }
                }
            }
        }
    }');
    
    if ($gql && isset($gql['data']['makeRootCategories'])) {
        return formatGqlCategories($gql['data']['makeRootCategories']);
    }
    
    // API orqali fallback
    $endpoints = [
        'https://api.uzum.uz/api/main/root-categories',
        'https://api.uzum.uz/api/category/v2/root-categories',
    ];
    
    foreach ($endpoints as $url) {
        $result = curlFetch($url);
        if ($result['code'] === 200 && $result['body']) {
            $data = json_decode($result['body'], true);
            $items = $data['payload'] ?? $data['categories'] ?? $data['data'] ?? $data;
            if (is_array($items) && !empty($items)) {
                return formatCategories($items);
            }
        }
    }
    
    return [];
}

function formatCategories($items) {
    $result = [];
    foreach ($items as $item) {
        $cat = [
            'id' => $item['id'] ?? $item['categoryId'] ?? 0,
            'title' => $item['title'] ?? $item['name'] ?? 'Nomsiz',
            'icon' => $item['icon'] ?? '',
            'productCount' => $item['productAmount'] ?? $item['productCount'] ?? 0,
            'children' => [],
        ];
        
        if (!empty($item['children'])) {
            foreach ($item['children'] as $child) {
                $subcat = [
                    'id' => $child['id'] ?? $child['categoryId'] ?? 0,
                    'title' => $child['title'] ?? $child['name'] ?? '',
                    'productCount' => $child['productAmount'] ?? $child['productCount'] ?? 0,
                    'children' => [],
                ];
                
                if (!empty($child['children'])) {
                    foreach ($child['children'] as $sub) {
                        $subcat['children'][] = [
                            'id' => $sub['id'] ?? $sub['categoryId'] ?? 0,
                            'title' => $sub['title'] ?? $sub['name'] ?? '',
                            'productCount' => $sub['productAmount'] ?? $sub['productCount'] ?? 0,
                        ];
                    }
                }
                $cat['children'][] = $subcat;
            }
        }
        $result[] = $cat;
    }
    return $result;
}

function formatGqlCategories($items) {
    $result = [];
    foreach ($items as $item) {
        $c = $item['category'] ?? $item;
        $cat = [
            'id' => $c['id'] ?? 0,
            'title' => $c['title'] ?? '',
            'icon' => $c['icon'] ?? '',
            'productCount' => 0,
            'children' => [],
        ];
        
        if (!empty($c['children'])) {
            foreach ($c['children'] as $child) {
                $ch = $child['category'] ?? $child;
                $subcat = [
                    'id' => $ch['id'] ?? 0,
                    'title' => $ch['title'] ?? '',
                    'productCount' => 0,
                    'children' => [],
                ];
                if (!empty($ch['children'])) {
                    foreach ($ch['children'] as $sub) {
                        $s = $sub['category'] ?? $sub;
                        $subcat['children'][] = [
                            'id' => $s['id'] ?? 0,
                            'title' => $s['title'] ?? '',
                            'productCount' => 0,
                        ];
                    }
                }
                $cat['children'][] = $subcat;
            }
        }
        $result[] = $cat;
    }
    return $result;
}

function searchUzumProducts($query, $sort = 'POPULAR', $offset = 0) {
    $products = [];
    $totalCount = 0;
    $categoryAnalysis = null;
    
    // 1. GraphQL search
    $safeQuery = str_replace('"', '', $query);
    $gql = graphqlQuery("{
        makeSearch(queryInput:{text:\"$safeQuery\", sort:$sort, showAdultContent:\"TRUE\", filters:[]}, pagination:{offset:$offset, limit:24}) {
            total
            items {
                catalogCard {
                    productId
                    title
                    minSellPrice
                    minFullPrice
                    reviewsAmount
                    ordersAmount
                    rating
                    photos {
                        photo {
                            _240
                        }
                    }
                    category {
                        id
                        title
                    }
                    seller {
                        title
                        id
                    }
                }
            }
            categoryAnalytics {
                category {
                    id
                    title
                }
                minPrice
                maxPrice
                avgPrice
                count
            }
        }
    }");
    
    if ($gql && isset($gql['data']['makeSearch'])) {
        $searchData = $gql['data']['makeSearch'];
        $totalCount = $searchData['total'] ?? 0;
        
        foreach (($searchData['items'] ?? []) as $it) {
            $c = $it['catalogCard'] ?? null;
            if (!$c) continue;
            
            $price = $c['minSellPrice'] ?? 0;
            if ($price > 1000000) $price /= 100;
            $fullPrice = $c['minFullPrice'] ?? 0;
            if ($fullPrice > 1000000) $fullPrice /= 100;
            
            $photo = '';
            if (!empty($c['photos'][0]['photo']['_240'])) {
                $photo = $c['photos'][0]['photo']['_240'];
            }
            
            $products[] = [
                'productId' => $c['productId'] ?? 0,
                'title' => $c['title'] ?? '',
                'price' => $price,
                'fullPrice' => $fullPrice,
                'discount' => ($fullPrice > 0 && $fullPrice > $price) ? round((1 - $price / $fullPrice) * 100) : 0,
                'rating' => $c['rating'] ?? 0,
                'reviews' => $c['reviewsAmount'] ?? 0,
                'orders' => $c['ordersAmount'] ?? 0,
                'photo' => $photo,
                'category' => $c['category']['title'] ?? '',
                'categoryId' => $c['category']['id'] ?? 0,
                'seller' => $c['seller']['title'] ?? '',
                'sellerId' => $c['seller']['id'] ?? 0,
            ];
        }
        
        if (isset($searchData['categoryAnalytics'])) {
            $categoryAnalysis = $searchData['categoryAnalytics'];
        }
    }
    
    // 2. AllOrigins fallback
    if (empty($products)) {
        $uzumUrl = 'https://uzum.uz/uz/search?query=' . urlencode($query);
        $proxyUrl = 'https://api.allorigins.win/raw?url=' . urlencode($uzumUrl);
        $result = curlFetch($proxyUrl);
        
        if ($result['code'] === 200 && $result['body']) {
            $html = $result['body'];
            $priceRegex = '/"title"\s*:\s*"([^"]{5,80})"[\s\S]{0,200}?"(?:minSellPrice|sellPrice|price)"\s*:\s*(\d+)/';
            if (preg_match_all($priceRegex, $html, $matches, PREG_SET_ORDER)) {
                foreach (array_slice($matches, 0, 24) as $m) {
                    $price = (int)$m[2];
                    if ($price > 1000000) $price /= 100;
                    if ($price > 100 && $price < 100000000) {
                        $products[] = [
                            'productId' => 0,
                            'title' => $m[1],
                            'price' => $price,
                            'fullPrice' => 0,
                            'discount' => 0,
                            'rating' => 0,
                            'reviews' => 0,
                            'orders' => 0,
                            'photo' => '',
                            'category' => '',
                            'categoryId' => 0,
                            'seller' => '',
                            'sellerId' => 0,
                        ];
                    }
                }
            }
        }
    }
    
    return [
        'products' => $products,
        'totalCount' => $totalCount ?: count($products),
        'categoryAnalysis' => $categoryAnalysis,
    ];
}

function fetchCategoryProducts($categoryId, $slug, $sort = 'POPULAR', $offset = 0, $limit = 24) {
    $products = [];
    
    // GraphQL bilan
    $catFilter = $categoryId ? "categoryId:$categoryId" : "";
    $gql = graphqlQuery("{
        makeSearch(queryInput:{text:\"\", sort:$sort, showAdultContent:\"TRUE\", categoryId:$categoryId, filters:[]}, pagination:{offset:$offset, limit:$limit}) {
            total
            items {
                catalogCard {
                    productId
                    title
                    minSellPrice
                    minFullPrice
                    reviewsAmount
                    ordersAmount
                    rating
                    photos {
                        photo {
                            _240
                        }
                    }
                    seller {
                        title
                        id
                    }
                }
            }
        }
    }");
    
    if ($gql && isset($gql['data']['makeSearch']['items'])) {
        foreach ($gql['data']['makeSearch']['items'] as $it) {
            $c = $it['catalogCard'] ?? null;
            if (!$c) continue;
            
            $price = $c['minSellPrice'] ?? 0;
            if ($price > 1000000) $price /= 100;
            $fullPrice = $c['minFullPrice'] ?? 0;
            if ($fullPrice > 1000000) $fullPrice /= 100;
            
            $photo = '';
            if (!empty($c['photos'][0]['photo']['_240'])) {
                $photo = $c['photos'][0]['photo']['_240'];
            }
            
            $products[] = [
                'productId' => $c['productId'] ?? 0,
                'title' => $c['title'] ?? '',
                'price' => $price,
                'fullPrice' => $fullPrice,
                'discount' => ($fullPrice > 0 && $fullPrice > $price) ? round((1 - $price / $fullPrice) * 100) : 0,
                'rating' => $c['rating'] ?? 0,
                'reviews' => $c['reviewsAmount'] ?? 0,
                'orders' => $c['ordersAmount'] ?? 0,
                'photo' => $photo,
                'seller' => $c['seller']['title'] ?? '',
                'sellerId' => $c['seller']['id'] ?? 0,
            ];
        }
    }
    
    return $products;
}

function fetchProductDetails($productId) {
    // Tovar sahifasini Uzum API dan olish
    $result = curlFetch("https://api.uzum.uz/api/v2/product/$productId");
    
    $product = null;
    if ($result['code'] === 200 && $result['body']) {
        $data = json_decode($result['body'], true);
        $payload = $data['payload'] ?? $data;
        
        if ($payload) {
            $price = $payload['minSellPrice'] ?? $payload['sellPrice'] ?? 0;
            if ($price > 1000000) $price /= 100;
            $fullPrice = $payload['minFullPrice'] ?? $payload['fullPrice'] ?? 0;
            if ($fullPrice > 1000000) $fullPrice /= 100;
            
            $photos = [];
            if (!empty($payload['photos'])) {
                foreach ($payload['photos'] as $ph) {
                    $photos[] = $ph['photo']['_240'] ?? $ph['photo']['240']['high'] ?? '';
                }
            }
            
            $charList = [];
            if (!empty($payload['characteristics'])) {
                foreach ($payload['characteristics'] as $ch) {
                    foreach (($ch['charValues'] ?? []) as $cv) {
                        $charList[] = [
                            'name' => $ch['title'] ?? $cv['title'] ?? '',
                            'value' => $cv['value'] ?? $cv['title'] ?? '',
                        ];
                    }
                }
            }
            
            $product = [
                'productId' => $payload['id'] ?? $productId,
                'title' => $payload['title'] ?? '',
                'description' => $payload['description'] ?? '',
                'price' => $price,
                'fullPrice' => $fullPrice,
                'discount' => ($fullPrice > 0 && $fullPrice > $price) ? round((1 - $price / $fullPrice) * 100) : 0,
                'rating' => $payload['rating'] ?? 0,
                'reviews' => $payload['reviewsAmount'] ?? 0,
                'orders' => $payload['ordersAmount'] ?? 0,
                'photos' => $photos,
                'category' => $payload['category']['title'] ?? '',
                'categoryId' => $payload['category']['id'] ?? 0,
                'seller' => $payload['seller']['title'] ?? '',
                'sellerId' => $payload['seller']['id'] ?? 0,
                'sellerRating' => $payload['seller']['rating'] ?? 0,
                'sellerReviews' => $payload['seller']['reviewsCount'] ?? 0,
                'characteristics' => $charList,
                'badges' => $payload['badges'] ?? [],
            ];
        }
    }
    
    return $product;
}
