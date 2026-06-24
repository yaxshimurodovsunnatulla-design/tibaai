<?php
/**
 * Tiba Optom — Data API
 * Sellers, Products, Orders management
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Faqat POST'], 405);
}

$input = getInput();
$action = $input['action'] ?? '';

// Public actions (auth talab qilmaydi)
$publicActions = ['get_sellers', 'get_seller_detail', 'get_categories', 'send_order'];

if (!in_array($action, $publicActions)) {
    // Auth talab qiluvchi amallar
    require_once __DIR__ . '/optom-auth.php'; // getOptomSeller funksiyasini import qilish uchun
}

switch ($action) {
    // === PUBLIC ===
    case 'get_sellers':       getSellers($input); break;
    case 'get_seller_detail': getSellerDetail($input); break;
    case 'get_categories':    getCategories(); break;
    case 'send_order':        sendOrder($input); break;
    
    // === PANEL (Auth kerak) ===
    case 'get_my_products':   getMyProducts(); break;
    case 'add_product':       addProduct($input); break;
    case 'update_product':    updateProduct($input); break;
    case 'delete_product':    deleteProduct($input); break;
    case 'get_my_orders':     getMyOrders(); break;
    case 'update_order':      updateOrder($input); break;
    case 'update_profile':    updateProfile($input); break;
    case 'get_dashboard':     getDashboard(); break;
    
    default: jsonResponse(['error' => 'Noto\'g\'ri amal'], 400);
}

// ========== PUBLIC FUNCTIONS ==========

function getSellers($input) {
    $db = getDB();
    $where = ["s.status = 'active'"];
    $params = [];
    
    // Qidiruv
    $search = trim($input['search'] ?? '');
    if ($search) {
        $where[] = "(s.company_name LIKE ? OR s.description LIKE ? OR s.city LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    // Kategoriya filtri
    $category = trim($input['category'] ?? '');
    if ($category) {
        $where[] = "s.categories LIKE ?";
        $params[] = "%\"$category\"%";
    }
    
    // Shahar filtri
    $city = trim($input['city'] ?? '');
    if ($city) {
        $where[] = "s.city = ?";
        $params[] = $city;
    }
    
    $whereStr = implode(' AND ', $where);
    
    // Saralash
    $sort = $input['sort'] ?? 'newest';
    $orderBy = match($sort) {
        'rating_desc' => 's.rating DESC, s.review_count DESC',
        'name_asc' => 's.company_name ASC',
        'views_desc' => 's.views DESC',
        default => 's.created_at DESC',
    };
    
    $page = max(1, (int)($input['page'] ?? 1));
    $limit = 12;
    $offset = ($page - 1) * $limit;
    
    // Count
    $countStmt = $db->prepare("SELECT COUNT(*) FROM optom_sellers s WHERE $whereStr");
    $countStmt->execute($params);
    $total = (int)$countStmt->fetchColumn();
    
    // Data
    $stmt = $db->prepare("SELECT s.id, s.company_name, s.owner_name, s.city, s.categories, s.description, s.logo_path, s.min_order_amount, s.rating, s.review_count, s.verified, s.views, s.created_at,
        (SELECT COUNT(*) FROM optom_products p WHERE p.seller_id = s.id AND p.in_stock = 1) as product_count
        FROM optom_sellers s WHERE $whereStr ORDER BY $orderBy LIMIT $limit OFFSET $offset");
    $stmt->execute($params);
    $sellers = $stmt->fetchAll();
    
    // JSON field'larni parse qilish
    foreach ($sellers as &$s) {
        $s['categories'] = json_decode($s['categories'] ?? '[]', true) ?: [];
    }
    
    jsonResponse([
        'success' => true,
        'sellers' => $sellers,
        'total' => $total,
        'page' => $page,
        'pages' => ceil($total / $limit),
    ]);
}

function getSellerDetail($input) {
    $id = (int)($input['seller_id'] ?? 0);
    if (!$id) jsonResponse(['error' => 'seller_id kerak'], 400);
    
    $db = getDB();
    $stmt = $db->prepare("SELECT id, company_name, owner_name, phone, email, city, categories, description, address, logo_path, min_order_amount, rating, review_count, verified, views, created_at FROM optom_sellers WHERE id = ? AND status = 'active'");
    $stmt->execute([$id]);
    $seller = $stmt->fetch();
    
    if (!$seller) jsonResponse(['error' => 'Sotuvchi topilmadi'], 404);
    
    // Ko'rishlar sonini oshirish
    $db->prepare("UPDATE optom_sellers SET views = views + 1 WHERE id = ?")->execute([$id]);
    
    // Mahsulotlar
    $products = $db->prepare("SELECT * FROM optom_products WHERE seller_id = ? AND in_stock = 1 ORDER BY created_at DESC");
    $products->execute([$id]);
    
    $seller['categories'] = json_decode($seller['categories'] ?? '[]', true) ?: [];
    
    jsonResponse([
        'success' => true,
        'seller' => $seller,
        'products' => $products->fetchAll(),
    ]);
}

function getCategories() {
    $db = getDB();
    $stmt = $db->query("SELECT categories FROM optom_sellers WHERE status = 'active'");
    $all = [];
    while ($row = $stmt->fetch()) {
        $cats = json_decode($row['categories'] ?? '[]', true) ?: [];
        foreach ($cats as $c) {
            $c = trim($c);
            if ($c) $all[$c] = ($all[$c] ?? 0) + 1;
        }
    }
    arsort($all);
    
    $result = [];
    foreach ($all as $name => $count) {
        $result[] = ['name' => $name, 'count' => $count];
    }
    
    jsonResponse(['success' => true, 'categories' => $result]);
}

function sendOrder($input) {
    $sellerId = (int)($input['seller_id'] ?? 0);
    $name = sanitize(trim($input['customer_name'] ?? ''));
    $phone = sanitize(trim($input['customer_phone'] ?? ''));
    $productId = (int)($input['product_id'] ?? 0);
    $quantity = max(1, (int)($input['quantity'] ?? 1));
    $message = sanitize(trim($input['message'] ?? ''));
    
    if (!$sellerId || !$name || !$phone) {
        jsonResponse(['error' => 'Ism, telefon va sotuvchi majburiy'], 400);
    }
    
    $db = getDB();
    
    // Sotuvchi mavjudligini tekshirish
    $seller = $db->prepare("SELECT id, company_name FROM optom_sellers WHERE id = ? AND status = 'active'");
    $seller->execute([$sellerId]);
    if (!$seller->fetch()) {
        jsonResponse(['error' => 'Sotuvchi topilmadi'], 404);
    }
    
    $db->prepare("INSERT INTO optom_orders (seller_id, customer_name, customer_phone, product_id, quantity, message) VALUES (?, ?, ?, ?, ?, ?)")
       ->execute([$sellerId, $name, $phone, $productId ?: null, $quantity, $message]);
    
    jsonResponse(['success' => true, 'message' => 'So\'rovingiz yuborildi! Sotuvchi tez orada siz bilan bog\'lanadi.']);
}

// ========== PANEL FUNCTIONS (Auth required) ==========

function requireAuth() {
    $token = $_SERVER['HTTP_X_OPTOM_TOKEN'] ?? '';
    if (empty($token)) jsonResponse(['error' => 'Avtorizatsiya talab qilinadi'], 401);
    
    $db = getDB();
    $stmt = $db->prepare("SELECT s.* FROM optom_sellers s INNER JOIN optom_sessions ss ON s.id = ss.seller_id WHERE ss.token = ? AND ss.expires_at > datetime('now')");
    $stmt->execute([$token]);
    $seller = $stmt->fetch();
    
    if (!$seller) jsonResponse(['error' => 'Sessiya muddati o\'tgan'], 401);
    return $seller;
}

function getDashboard() {
    $seller = requireAuth();
    $db = getDB();
    
    $products = $db->prepare("SELECT COUNT(*) FROM optom_products WHERE seller_id = ?");
    $products->execute([$seller['id']]);
    
    $newOrders = $db->prepare("SELECT COUNT(*) FROM optom_orders WHERE seller_id = ? AND status = 'new'");
    $newOrders->execute([$seller['id']]);
    
    $totalOrders = $db->prepare("SELECT COUNT(*) FROM optom_orders WHERE seller_id = ?");
    $totalOrders->execute([$seller['id']]);
    
    // Oxirgi 5 ta buyurtma
    $recentOrders = $db->prepare("SELECT o.*, p.name as product_name FROM optom_orders o LEFT JOIN optom_products p ON o.product_id = p.id WHERE o.seller_id = ? ORDER BY o.created_at DESC LIMIT 5");
    $recentOrders->execute([$seller['id']]);
    
    jsonResponse([
        'success' => true,
        'stats' => [
            'products' => (int)$products->fetchColumn(),
            'new_orders' => (int)$newOrders->fetchColumn(),
            'total_orders' => (int)$totalOrders->fetchColumn(),
            'views' => (int)$seller['views'],
            'rating' => (float)$seller['rating'],
            'review_count' => (int)$seller['review_count'],
        ],
        'recent_orders' => $recentOrders->fetchAll(),
    ]);
}

function getMyProducts() {
    $seller = requireAuth();
    $db = getDB();
    
    $stmt = $db->prepare("SELECT * FROM optom_products WHERE seller_id = ? ORDER BY created_at DESC");
    $stmt->execute([$seller['id']]);
    
    jsonResponse(['success' => true, 'products' => $stmt->fetchAll()]);
}

function addProduct($input) {
    $seller = requireAuth();
    $db = getDB();
    
    $name = sanitize(trim($input['name'] ?? ''));
    $description = sanitize(trim($input['description'] ?? ''));
    $category = sanitize(trim($input['category'] ?? 'Boshqa'));
    $priceRetail = (int)($input['price_retail'] ?? 0);
    $priceWholesale = (int)($input['price_wholesale'] ?? 0);
    $minQty = max(1, (int)($input['min_quantity'] ?? 1));
    $unit = sanitize(trim($input['unit'] ?? 'dona'));
    $imagePath = sanitize(trim($input['image_path'] ?? ''));
    
    if (empty($name)) jsonResponse(['error' => 'Mahsulot nomi kerak'], 400);
    if ($priceWholesale <= 0) jsonResponse(['error' => 'Ulgurji narx kiritilishi shart'], 400);
    
    $db->prepare("INSERT INTO optom_products (seller_id, name, description, category, price_retail, price_wholesale, min_quantity, unit, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)")
       ->execute([$seller['id'], $name, $description, $category, $priceRetail, $priceWholesale, $minQty, $unit, $imagePath]);
    
    jsonResponse(['success' => true, 'id' => $db->lastInsertId(), 'message' => 'Mahsulot qo\'shildi']);
}

function updateProduct($input) {
    $seller = requireAuth();
    $db = getDB();
    
    $id = (int)($input['product_id'] ?? 0);
    if (!$id) jsonResponse(['error' => 'product_id kerak'], 400);
    
    // O'zinikimi tekshirish
    $check = $db->prepare("SELECT id FROM optom_products WHERE id = ? AND seller_id = ?");
    $check->execute([$id, $seller['id']]);
    if (!$check->fetch()) jsonResponse(['error' => 'Mahsulot topilmadi'], 404);
    
    $fields = [];
    $params = [];
    
    foreach (['name', 'description', 'category', 'unit', 'image_path'] as $f) {
        if (isset($input[$f])) { $fields[] = "$f = ?"; $params[] = sanitize(trim($input[$f])); }
    }
    foreach (['price_retail', 'price_wholesale', 'min_quantity'] as $f) {
        if (isset($input[$f])) { $fields[] = "$f = ?"; $params[] = (int)$input[$f]; }
    }
    if (isset($input['in_stock'])) { $fields[] = "in_stock = ?"; $params[] = (int)$input['in_stock']; }
    
    if (empty($fields)) jsonResponse(['error' => 'O\'zgartirish uchun ma\'lumot yo\'q'], 400);
    
    $params[] = $id;
    $db->prepare("UPDATE optom_products SET " . implode(', ', $fields) . " WHERE id = ?")->execute($params);
    
    jsonResponse(['success' => true, 'message' => 'Mahsulot yangilandi']);
}

function deleteProduct($input) {
    $seller = requireAuth();
    $db = getDB();
    
    $id = (int)($input['product_id'] ?? 0);
    if (!$id) jsonResponse(['error' => 'product_id kerak'], 400);
    
    $db->prepare("DELETE FROM optom_products WHERE id = ? AND seller_id = ?")->execute([$id, $seller['id']]);
    
    jsonResponse(['success' => true, 'message' => 'Mahsulot o\'chirildi']);
}

function getMyOrders() {
    $seller = requireAuth();
    $db = getDB();
    
    $stmt = $db->prepare("SELECT o.*, p.name as product_name FROM optom_orders o LEFT JOIN optom_products p ON o.product_id = p.id WHERE o.seller_id = ? ORDER BY o.created_at DESC");
    $stmt->execute([$seller['id']]);
    
    jsonResponse(['success' => true, 'orders' => $stmt->fetchAll()]);
}

function updateOrder($input) {
    $seller = requireAuth();
    $db = getDB();
    
    $id = (int)($input['order_id'] ?? 0);
    $status = sanitize(trim($input['status'] ?? ''));
    
    if (!$id || !$status) jsonResponse(['error' => 'order_id va status kerak'], 400);
    if (!in_array($status, ['new', 'accepted', 'shipped', 'completed', 'cancelled'])) {
        jsonResponse(['error' => 'Noto\'g\'ri status'], 400);
    }
    
    $db->prepare("UPDATE optom_orders SET status = ? WHERE id = ? AND seller_id = ?")->execute([$status, $id, $seller['id']]);
    
    jsonResponse(['success' => true, 'message' => 'Buyurtma statusi yangilandi']);
}

function updateProfile($input) {
    $seller = requireAuth();
    $db = getDB();
    
    $fields = [];
    $params = [];
    
    foreach (['company_name', 'owner_name', 'email', 'inn', 'description', 'address', 'city'] as $f) {
        if (isset($input[$f])) { $fields[] = "$f = ?"; $params[] = sanitize(trim($input[$f])); }
    }
    if (isset($input['min_order_amount'])) { $fields[] = "min_order_amount = ?"; $params[] = (int)$input['min_order_amount']; }
    if (isset($input['categories'])) { 
        $fields[] = "categories = ?"; 
        $params[] = json_encode($input['categories'], JSON_UNESCAPED_UNICODE); 
    }
    if (isset($input['logo_path'])) { $fields[] = "logo_path = ?"; $params[] = sanitize(trim($input['logo_path'])); }
    
    if (empty($fields)) jsonResponse(['error' => 'O\'zgartirish uchun ma\'lumot yo\'q'], 400);
    
    $params[] = $seller['id'];
    $db->prepare("UPDATE optom_sellers SET " . implode(', ', $fields) . " WHERE id = ?")->execute($params);
    
    jsonResponse(['success' => true, 'message' => 'Profil yangilandi']);
}
