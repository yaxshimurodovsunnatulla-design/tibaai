<?php
/**
 * Tiba AI — Seller Marketplace API
 */
require_once __DIR__ . '/config.php';

if (!function_exists('getUserByToken')) {
    function getUserByToken($db, $token) {
        if (empty($token) || strlen($token) < 32) return null;
        $now = date('Y-m-d H:i:s');
        $stmt = $db->prepare("SELECT u.id, u.name, u.email, u.balance, u.telegram_id FROM users u JOIN user_sessions s ON u.id = s.user_id WHERE s.token = ? AND s.expires_at > ?");
        $stmt->execute([$token, $now]);
        return $stmt->fetch();
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Faqat POST so\'rovlari qabul qilinadi'], 405);
}

$input = getInput();
$action = $input['action'] ?? '';
$db = getDB();

// 1. Get Categories
if ($action === 'get_categories') {
    try {
        $stmt = $db->query("SELECT * FROM blogger_categories ORDER BY sort_order ASC");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        jsonResponse(['success' => true, 'categories' => $categories]);
    } catch (Exception $e) {
        jsonResponse(['error' => 'Kategoriyalarni yuklashda xatolik: ' . $e->getMessage()], 500);
    }
}

// 2. Get Approved Bloggers
if ($action === 'get_bloggers') {
    try {
        $search = sanitize($input['search'] ?? '');
        $category = sanitize($input['category'] ?? '');
        $dealType = sanitize($input['deal_type'] ?? ''); // 'barter' yoki 'paid'
        $sort = sanitize($input['sort'] ?? 'newest'); // 'newest', 'followers_desc', 'rating_desc', 'price_asc'
        
        $params = [];
        $sql = "SELECT id, display_name, bio, avatar_path, city, instagram_followers, 
                       tiktok_followers, youtube_subscribers, telegram_subscribers, 
                       categories, price_story, price_reels, price_post, price_video, 
                       price_unboxing, price_live, accepts_barter, barter_min_value, 
                       rating, total_reviews, is_featured 
                FROM bloggers 
                WHERE status = 'approved'";
        
        if (!empty($search)) {
            $sql .= " AND (display_name LIKE ? OR bio LIKE ?)";
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }
        
        if (!empty($category)) {
            // categories LIKE '%"category"%' (JSON format) yoki categories LIKE '%category%'
            $sql .= " AND (categories LIKE ? OR categories LIKE ?)";
            $params[] = '%' . $category . '%';
            $params[] = '%"' . $category . '"%';
        }
        
        if ($dealType === 'barter') {
            $sql .= " AND accepts_barter = 1";
        }
        
        // Saralash
        if ($sort === 'followers_desc') {
            $sql .= " ORDER BY is_featured DESC, (instagram_followers + tiktok_followers + youtube_subscribers + telegram_subscribers) DESC";
        } elseif ($sort === 'rating_desc') {
            $sql .= " ORDER BY is_featured DESC, rating DESC, total_reviews DESC";
        } elseif ($sort === 'price_asc') {
            // Price story bo'yicha eng arzonlari birinchi
            $sql .= " ORDER BY is_featured DESC, price_story ASC";
        } else {
            // newest
            $sql .= " ORDER BY is_featured DESC, created_at DESC";
        }
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $bloggers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        jsonResponse(['success' => true, 'bloggers' => $bloggers]);
    } catch (Exception $e) {
        jsonResponse(['error' => 'Blogerlarni yuklashda xatolik: ' . $e->getMessage()], 500);
    }
}

// 3. Create New Deal (Order Blogger)
if ($action === 'create_deal') {
    // Check User Authentication
    $token = $_SERVER['HTTP_X_USER_TOKEN'] ?? '';
    if (empty($token)) {
        jsonResponse(['error' => 'Tizimga kirmagansiz. Iltimos, avval tizimga kiring.'], 401);
    }
    
    $user = getUserByToken($db, $token);
    if (!$user) {
        jsonResponse(['error' => 'Sessiya muddati tugagan. Iltimos, qayta tizimga kiring.'], 401);
    }
    
    $bloggerId = (int)($input['blogger_id'] ?? 0);
    $dealType = sanitize($input['deal_type'] ?? 'paid'); // 'paid' yoki 'barter'
    $adFormat = sanitize($input['ad_format'] ?? '');
    $productName = sanitize($input['product_name'] ?? '');
    $productLink = sanitize($input['product_link'] ?? '');
    $productCategory = sanitize($input['product_category'] ?? '');
    $description = sanitize($input['description'] ?? '');
    $offeredPrice = (int)($input['offered_price'] ?? 0);
    $barterItems = sanitize($input['barter_items'] ?? '');
    $barterValue = (int)($input['barter_value'] ?? 0);
    $deadline = sanitize($input['deadline'] ?? '');
    
    // Validatsiya
    if ($bloggerId <= 0) {
        jsonResponse(['error' => 'Bloger tanlanmagan'], 400);
    }
    
    // Blogerni tekshirish
    $stmt = $db->prepare("SELECT id, display_name, status, accepts_barter FROM bloggers WHERE id = ?");
    $stmt->execute([$bloggerId]);
    $blogger = $stmt->fetch();
    if (!$blogger || $blogger['status'] !== 'approved') {
        jsonResponse(['error' => 'Ushbu bloger mavjud emas yoki faol holatda emas'], 400);
    }
    
    if (!in_array($dealType, ['paid', 'barter'])) {
        jsonResponse(['error' => 'Kelishuv turi noto\'g\'ri'], 400);
    }
    
    if ($dealType === 'barter' && $blogger['accepts_barter'] == 0) {
        jsonResponse(['error' => 'Ushbu bloger barter asosida ishlamaydi'], 400);
    }
    
    $allowedFormats = ['story', 'reels', 'post', 'video', 'unboxing', 'live'];
    if (!in_array(strtolower($adFormat), $allowedFormats)) {
        jsonResponse(['error' => 'Reklama formati noto\'g\'ri. Tanlang: ' . implode(', ', $allowedFormats)], 400);
    }
    
    if (empty($productName)) {
        jsonResponse(['error' => 'Mahsulot nomi kiritilishi shart'], 400);
    }
    
    if (empty($description) || mb_strlen($description) < 10) {
        jsonResponse(['error' => 'Tavsif (texnik topshiriq) juda qisqa (kamida 10 ta belgi bo\'lishi kerak)'], 400);
    }
    
    if (empty($deadline)) {
        jsonResponse(['error' => 'Muddat (deadline) kiritilishi shart'], 400);
    }
    
    if (strtotime($deadline) < strtotime(date('Y-m-d'))) {
        jsonResponse(['error' => 'Muddat o\'tib ketgan sana bo\'lishi mumkin emas'], 400);
    }
    
    if ($dealType === 'paid') {
        if ($offeredPrice <= 0) {
            jsonResponse(['error' => 'Taklif etilayotgan narx musbat son bo\'lishi kerak'], 400);
        }
        $barterItems = null;
        $barterValue = 0;
    } else {
        if (empty($barterItems)) {
            jsonResponse(['error' => 'Barter qilinadigan tovarlar/xizmatlar tavsifi majburiy'], 400);
        }
        if ($barterValue <= 0) {
            jsonResponse(['error' => 'Barter tovarlarining taxminiy qiymati kiritilishi shart'], 400);
        }
        $offeredPrice = 0;
    }
    
    // Ma'lumotlarni saqlash
    try {
        $sql = "INSERT INTO ad_deals (
                    seller_user_id, blogger_id, deal_type, ad_format, product_name, 
                    product_link, product_category, description, offered_price, 
                    barter_items, barter_value, deadline, status, created_at, updated_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', datetime('now'), datetime('now'))";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $user['id'],
            $bloggerId,
            $dealType,
            strtolower($adFormat),
            $productName,
            $productLink,
            $productCategory,
            $description,
            $offeredPrice,
            $barterItems,
            $barterValue,
            $deadline
        ]);
        
        jsonResponse([
            'success' => true, 
            'message' => 'Sizning taklifingiz blogerga muvaffaqiyatli yuborildi. Bloger javobini kuting.'
        ]);
    } catch (Exception $e) {
        jsonResponse(['error' => 'Taklifni saqlashda xatolik yuz berdi: ' . $e->getMessage()], 500);
    }
}

jsonResponse(['error' => 'Noto\'g\'ri amal'], 400);
