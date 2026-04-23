<?php
/**
 * Uzum Market Seller API Integration
 * Based on provided uzum-api.txt (Swagger Spec)
 */
require_once __DIR__ . '/config.php';

$input = getInput();
$apiKey = $input['api_key'] ?? '';
$period = (int)($input['period'] ?? 30);

if (!$apiKey) {
    jsonResponse(['error' => 'API kalit kiritilmadi'], 400);
}

// Ensure user is logged in
$user = getAuthUser();
if (!$user) {
    jsonResponse(['error' => 'Tizimga kiring'], 401);
}

const UZUM_BASE_URL = 'https://api-seller.uzum.uz/api/seller-openapi/v1/';

function callUzumAPI($endpoint, $apiKey, $params = []) {
    $url = UZUM_BASE_URL . ltrim($endpoint, '/');
    
    if ($params) {
        $parts = [];
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    $parts[] = urlencode($key) . '=' . urlencode($v);
                }
            } elseif (is_bool($value)) {
                $parts[] = urlencode($key) . '=' . ($value ? 'true' : 'false');
            } else {
                $parts[] = urlencode($key) . '=' . urlencode($value);
            }
        }
        $url .= (strpos($url, '?') === false ? '?' : '&') . implode('&', $parts);
    }

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: ' . trim($apiKey),
            'Accept: application/json',
            'Content-Type: application/json'
        ],
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode($response, true);

    if ($httpCode !== 200) {
        error_log("Uzum API Debug ($endpoint): Code $httpCode, Response: " . $response);
        $errorMsg = "Uzum API xatosi ($httpCode)";
        // Check for error in response
        if (isset($data['error']) && is_string($data['error'])) {
            $errorMsg .= ": " . $data['error'];
        } elseif (isset($data['errors'][0]['message'])) {
            $errorMsg .= ": " . $data['errors'][0]['message'];
        }
        return ['error' => $errorMsg, 'http_code' => $httpCode];
    }

    return $data;
}

// 1. Get Shops
$shopsData = callUzumAPI('shops', $apiKey);
if (isset($shopsData['error'])) jsonResponse($shopsData, 400);

// OrganizationDto[]
$shops = is_array($shopsData) ? $shopsData : [];
$shopIds = array_column($shops, 'id');

if (empty($shopIds)) {
    jsonResponse(['error' => 'Do\'konlar topilmadi'], 404);
}

// 2. Get Finance Orders (Sales) with Pagination
$orderItems = [];
$orderFetchError = null;
$page = 0;
$hasMore = true;

// Switched to Seconds based on successful debug tests
$dateFrom = strtotime("-$period days");
$dateTo = time() + 86400; // Include today fully

while ($hasMore && $page < 10) { // Limit to 10 pages (~10,000 items) for safety
    $ordersData = callUzumAPI('finance/orders', $apiKey, [
        'dateFrom' => $dateFrom,
        'dateTo' => $dateTo,
        'size' => 1000,
        'page' => $page,
        'shopIds' => $shopIds,
        'group' => false
    ]);

    if (!isset($ordersData['error'])) {
        $items = $ordersData['orderItems'] ?? [];
        if (empty($items)) {
            $hasMore = false;
        } else {
            $orderItems = array_merge($orderItems, $items);
            $page++;
            // If we got fewer items than requested, it's the last page
            if (count($items) < 1000) $hasMore = false;
        }
    } else {
        $orderFetchError = $ordersData['error'];
        $hasMore = false;
    }
}
error_log("Uzum Analytics: Found " . count($orderItems) . " total order items.");

// 3. Get Expenses with Pagination
$expenseList = [];
$page = 0;
$hasMore = true;

while ($hasMore && $page < 5) {
    $expensesData = callUzumAPI('finance/expenses', $apiKey, [
        'dateFrom' => $dateFrom,
        'dateTo' => $dateTo,
        'shopIds' => $shopIds,
        'size' => 1000,
        'page' => $page
    ]);

    if (!isset($expensesData['error'])) {
        $items = $expensesData['payload']['payments'] ?? [];
        if (empty($items)) {
            $hasMore = false;
        } else {
            $expenseList = array_merge($expenseList, $items);
            $page++;
            if (count($items) < 1000) $hasMore = false;
        }
    } else {
        $hasMore = false;
    }
}

// 4. Get All Products (to find slow-movers and check storage)
$allProducts = [];
foreach ($shopIds as $sid) {
    $prodData = callUzumAPI("product/shop/$sid", $apiKey, [
        'size' => 500, // Increased to scan more items
        'sortBy' => 'LEFTOVERS',
        'order' => 'DESC'
    ]);
    if (!isset($prodData['error']) && isset($prodData['productList'])) {
        foreach ($prodData['productList'] as $product) {
            if (isset($product['skuList'])) {
                foreach ($product['skuList'] as $sku) {
                    $allProducts[] = [
                        'productId' => $sku['skuId'] ?? $product['productId'] ?? 0,
                        'productTitle' => $sku['productTitle'] ?? $product['title'] ?? 'Noma\'lum',
                        'skuTitle' => $sku['skuTitle'] ?? '',
                        'leftovers' => $sku['quantityActive'] ?? 0,
                        'sellerPrice' => $sku['price'] ?? 0,
                        'roi' => $sku['roi'] ?? 0,
                        'conversion' => $sku['conversion'] ?? 0
                    ];
                }
            }
        }
    } else {
        error_log("Uzum Stats: Failed to fetch products for shop $sid");
    }
}
error_log("Uzum Stats: Total products fetched for audit: " . count($allProducts));

// --- ANALYZE DATA ---
$totalSales = 0; 
$orderCount = 0;
$totalExpenses = 0;
$storageExpenses = 0;
$dailySales = [];
$topProducts = [];
$returnCauses = [];
$statusBreakdown = [
    'PROCESSING' => 0,
    'TO_WITHDRAW' => 0,
    'CANCELED' => 0,
    'PARTIALLY_CANCELLED' => 0
];
$totalReturnedValue = 0;
$totalReturnedQty = 0;

// Track product sales for slow-mover identification
$productSalesMap = []; 


// Initialize daily sales
for ($i = 0; $i <= $period; $i++) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $dailySales[$d] = ['sales' => 0, 'orders' => 0];
}

foreach ($orderItems as $item) {
    $status = $item['status'] ?? 'UNKNOWN';
    if (isset($statusBreakdown[$status])) {
        $statusBreakdown[$status]++;
    }

    $price = (float)($item['sellerProfit'] ?? $item['sellerPrice'] ?? 0);
    $displayPrice = (float)($item['sellerPrice'] ?? $item['purchasePrice'] ?? 0);
    $qty = (int)($item['amount'] ?? 1);
    $returns = (int)($item['amountReturns'] ?? 0);
    
    // Returns Analysis - MUST be before any continue
    if ($returns > 0) {
        $totalReturnedQty += $returns;
        $totalReturnedValue += ($displayPrice * $returns);
        $cause = $item['returnCause'] ?? 'Noma\'lum';
        $returnCauses[$cause] = ($returnCauses[$cause] ?? 0) + $returns;
    }

    if ($status === 'CANCELED') continue;

    $itemTotal = $price * $qty;
    
    // Accumulate stats
    $totalSales += $itemTotal;
    $orderCount++;
    
    // Map for inventory analysis
    $prodId = $item['productId'] ?? 0;
    $productSalesMap[$prodId] = ($productSalesMap[$prodId] ?? 0) + $qty;

    // Top Products aggregation
    $prodTitle = $item['productTitle'] ?? $item['skuTitle'] ?? 'Noma\'lum mahsulot';
    if (!isset($topProducts[$prodId])) {
        $topProducts[$prodId] = [
            'id' => $prodId,
            'title' => $prodTitle,
            'image' => $item['productImage']['photo']['240']['low'] ?? '',
            'sales' => 0,
            'qty' => 0,
            'returns' => 0
        ];
    }
    $topProducts[$prodId]['sales'] += $itemTotal;
    $topProducts[$prodId]['qty'] += $qty;
    $topProducts[$prodId]['returns'] += $returns;
    
    // Chart data
    $rawDate = $item['date'] ?? time();
    $timestamp = ($rawDate > 10000000000) ? $rawDate / 1000 : $rawDate;
    $date = date('Y-m-d', $timestamp);
    
    if (isset($dailySales[$date])) {
        $dailySales[$date]['sales'] += $itemTotal;
        $dailySales[$date]['orders']++;
    }
}

// Sort and slice top products
uasort($topProducts, function($a, $b) { return $b['sales'] <=> $a['sales']; });
$topProducts = array_slice($topProducts, 0, 10);

// Sort return causes
arsort($returnCauses);

foreach ($expenseList as $exp) {
    $amount = (float)($exp['paymentPrice'] ?? 0);
    $comment = mb_strtolower($exp['comment'] ?? '');
    $source = mb_strtolower($exp['source'] ?? '');

    // Log the source and comment for debugging
    file_put_contents(__DIR__ . '/debug_expenses.txt', "source=[$source], comment=[$comment], amount=$amount\n", FILE_APPEND);

    if ($exp['type'] === 'OUTCOME') {
        $totalExpenses += $amount;
        
        // Identify Storage fees (Saqlash xizmati) - Broadened keywords
        $isStorage = (
            strpos($comment, 'хранен') !== false || 
            strpos($comment, 'склад') !== false || 
            strpos($comment, 'saqlash') !== false || 
            strpos($comment, 'ombor') !== false ||
            strpos($source, 'storage') !== false ||
            strpos($source, 'warehouse') !== false
        );

        if ($isStorage) {
            $storageExpenses += $amount;
        }
    }
}
error_log("Uzum Stats: Total detected storage expenses: $storageExpenses");

// Identify slow movers with Danger Scoring
$slowMovers = [];
foreach ($allProducts as $p) {
    $pid = $p['productId'] ?? 0;
    $stock = $p['leftovers'] ?? 0;
    
    if (!isset($productSalesMap[$pid]) && $stock > 0) {
        // Danger Score Calculation:
        // Assume avg storage is 100 sum per item per day
        $estMonthlyLoss = $stock * 100 * 30; 
        
        // Define Solution based on metrics - Natural AI speaking
        $solution = "Xususiy yechim aniqlanmoqda...";
        if ($stock > 100) {
            $solution = "Do'stim, bu tovardan omborda juda ko'p yig'ilib qolibdi (${stock} ta). Saqlash puli kundan-kunga yeyapti. Mening qat'iy maslahatim — darhol 'Likvidatsiya' aksiyasiga qo'shing yoki narxini pastroq qilib bo'lsa ham sotib yuboring! Muzlab yotgan pulni qayta aylanishga kiritish kerak.";
        } elseif ($stock > 30) {
            $solution = "Bu tovaringiz ancha vaqtdan beri harakatsiz yotibdi. Omborda ${stock} ta qoldi. Uzum aksiyalariga qo'shish yoki narxini biroz (5-10%) tushirish vaqti kelgan. Saqlash xarajatlari foydani yeb bitirmasidan oldin qandaydur aksiya o'ylab toping.";
        } elseif ($p['conversion'] > 0 && $p['conversion'] < 1.0) {
            $solution = "Bilasizmi, mijozlar bu tovaringiz ustiga bosib kiryapti, lekin xarid qilmayapti (konversiya atigi ${p['conversion']}%). Sifatliroq rasmlar qo'yish, nomini jozibadorroq yozish yoki tavsifiga ko'proq foydali tomonlarini yozib chiqishni qattiq tavsiya qilaman.";
        } else {
            $solution = "Raqobatchilar narxini bir tekshirib ko'ring, arzonroq sotayotgan bo'lishi ehtimoli juda yuqori. Yoki o'zimizning top-tovarlaringiz bilan qo'shib 'To'plam' (sovga) formatida sotib yuborish yaxshi fikr. Muhimi shu o'lik zaxiradan tezroq qutulish.";
        }

        $slowMovers[] = [
            'id' => $pid,
            'title' => $p['productTitle'] ?? $p['skuTitle'] ?? 'Noma\'lum',
            'stock' => $stock,
            'price' => $p['sellerPrice'] ?? 0,
            'roi' => $p['roi'] ?? 0,
            'conversion' => $p['conversion'] ?? 0,
            'est_monthly_loss' => $estMonthlyLoss,
            'danger_level' => ($estMonthlyLoss > 100000) ? 'CRITICAL' : (($estMonthlyLoss > 50000) ? 'HIGH' : 'MEDIUM'),
            'solution' => $solution
        ];
    }
}
// Sort by projected loss (descending)
usort($slowMovers, function($a, $b) { return $b['est_monthly_loss'] <=> $a['est_monthly_loss']; });
$slowMovers = array_slice($slowMovers, 0, 10);


ksort($dailySales);

$stats = [
    'total_sales' => $totalSales,
    'order_count' => $orderCount,
    'total_expenses' => $totalExpenses,
    'net_profit' => $totalSales - $totalExpenses,
    'shops' => array_map(function($s) {
        return [
            'id' => $s['id'] ?? '?',
            'title' => $s['name'] ?? 'Noma\'lum do\'kon'
        ];
    }, $shops),
    'chart_data' => [
        'labels' => array_keys($dailySales),
        'sales' => array_column($dailySales, 'sales'),
        'orders' => array_column($dailySales, 'orders')
    ],
    'order_fetch_error' => $orderFetchError,
    'debug_info' => [
        'order_items_count' => count($orderItems),
        'expenses_count' => count($expenseList),
        'period_days' => $period,
        'all_products_count' => count($allProducts),
        'raw_prod_data_keys' => isset($prodData) ? array_keys($prodData) : null,
        'sample_scanned_prod' => $allProducts[0] ?? null,
        'sample_product_list' => isset($prodData['productList']) ? ($prodData['productList'][0] ?? null) : null
    ],
    'top_products' => array_values($topProducts),
    'returns' => [
        'qty' => $totalReturnedQty,
        'value' => $totalReturnedValue,
        'causes' => $returnCauses
    ],
    'status_breakdown' => $statusBreakdown,
    'storage_info' => [
        'total' => $storageExpenses,
        'slow_movers' => $slowMovers
    ]
];

jsonResponse($stats);
