<?php
/**
 * Tiba AI — Umumiy sozlamalar va yordamchi funksiyalar
 */

// Xatolarni ko'rsatmaslik (JSON body'ga tushib qolmasligi uchun)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('memory_limit', '512M');

// Toshkent vaqt zonasi (UTC+5)
date_default_timezone_set('Asia/Tashkent');

// Output buffering boshlash (stray output'larni ushlab qolish uchun)
if (!ob_get_level()) ob_start();

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Instagram OAuth Settings
define('INSTA_APP_ID', '3673518819645581'); // ChatPlace'niki kabi yoki o'zidan olingan
define('INSTA_REDIRECT_URI', 'http://localhost:8001/api/insta-callback.php');

// CORS
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowedOrigins = [
    'http://localhost:8000',
    'http://localhost:8001',
    'https://tibaai.uz',
    'https://www.tibaai.uz',
];

if (in_array($origin, $allowedOrigins) || (strpos($origin, 'http://localhost:') === 0)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, X-Admin-Session, X-User-Token');
    header('Access-Control-Allow-Credentials: true');
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// .env faylni o'qish
function loadEnv() {
    $envFile = __DIR__ . '/../.env';
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || $line[0] === '#') continue;
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                // Remove trailing comments from the value
                if (($hashPos = strpos($value, '#')) !== false) {
                    $value = trim(substr($value, 0, $hashPos));
                }
                
                // Trim quotes
                $value = trim($value, "\"'");

                putenv("$key=$value");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}
loadEnv();

// SQLite ulanish
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        $dbFile = __DIR__ . '/../data/tibaai.db';
        $dbDir = dirname($dbFile);
        if (!is_dir($dbDir)) mkdir($dbDir, 0755, true);

        $pdo = new PDO("sqlite:$dbFile", null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $pdo->exec("PRAGMA journal_mode=WAL");
        $pdo->exec("PRAGMA busy_timeout = 5000"); // 5 soniya kutish lock bo'lganda

        // Har doim migratsiyalarni tekshirish
        runMigrations($pdo);
    }
    return $pdo;
}

function runMigrations($pdo) {
    // 1. Users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT,
        password_hash TEXT,
        telegram_id TEXT UNIQUE,
        google_id TEXT UNIQUE,
        balance INTEGER DEFAULT 10,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Migration: Add columns if missing
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN balance INTEGER DEFAULT 10");
    } catch (Exception $e) { /* Ignore */ }
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN telegram_id TEXT");
    } catch (Exception $e) { /* Ignore */ }
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN google_id TEXT");
    } catch (Exception $e) { /* Ignore */ }
    // Create unique indexes (safe to call multiple times with IF NOT EXISTS)
    try {
        $pdo->exec("CREATE UNIQUE INDEX IF NOT EXISTS idx_users_telegram_id ON users(telegram_id)");
        $pdo->exec("CREATE UNIQUE INDEX IF NOT EXISTS idx_users_google_id ON users(google_id)");
    } catch (Exception $e) { /* Ignore */ }


    // 2. Configs table
    $pdo->exec("CREATE TABLE IF NOT EXISTS configs (
        id TEXT PRIMARY KEY,
        data TEXT NOT NULL,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // 3. User Sessions
    $pdo->exec("CREATE TABLE IF NOT EXISTS user_sessions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        token TEXT NOT NULL UNIQUE,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        expires_at DATETIME NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )");

    // 4. Admin Sessions (Migration included)
    $pdo->exec("CREATE TABLE IF NOT EXISTS admin_sessions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        token TEXT NOT NULL UNIQUE,
        status TEXT DEFAULT 'active', -- active, pending
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        expires_at DATETIME NOT NULL
    )");

    // Migration: Add status column if missing (for existing dbs)
    try {
        $pdo->exec("ALTER TABLE admin_sessions ADD COLUMN status TEXT DEFAULT 'active'");
    } catch (Exception $e) {
        // Column likely exists, ignore
    }

    // 5. Generations table (History)
    $pdo->exec("CREATE TABLE IF NOT EXISTS generations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        image_path TEXT NOT NULL,
        prompt_data TEXT, -- JSON: product, style, lang, features
        telegram_msg_id INTEGER,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(user_id) REFERENCES users(id)
    )");

    // 5.5. OTP Codes table (email verification)
    $pdo->exec("CREATE TABLE IF NOT EXISTS otp_codes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        phone TEXT NOT NULL,
        code TEXT NOT NULL,
        attempts INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        expires_at DATETIME NOT NULL,
        used INTEGER DEFAULT 0
    )");


    // 6. Services table (for Create page)
    $pdo->exec("CREATE TABLE IF NOT EXISTS services (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        slug TEXT NOT NULL UNIQUE,
        icon TEXT NOT NULL,
        description TEXT NOT NULL,
        badge TEXT,
        gradient TEXT NOT NULL,
        sort_order INTEGER DEFAULT 0,
        is_active INTEGER DEFAULT 1
    )");

    // Seed services if empty
    $count = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
    if ($count == 0) {
        $initialServices = [
            ['Foto Tahrir', 'foto-tahrir', 'fa-solid fa-wand-magic-sparkles', 'Mahsulot rasmiga professional fon qo\'yish (12 xil uslub)', 'Top', 'from-blue-500 to-indigo-600', 1],
            ['Marketplace Infografika', 'infografika', 'fa-solid fa-palette', 'Sotuvchi infografikalar yaratish (Uzum, WB, Ozon)', 'Yangi', 'from-purple-500 to-pink-600', 2],
            ['Infografika Paketi', 'infografika-paketi', 'fa-solid fa-box-open', '5 ta tayyor slayd paketi (4K sifatda)', 'Yangi', 'from-orange-500 to-red-600', 3],
            ['Noldan Yaratish', 'noldan-yaratish', 'fa-solid fa-rocket', 'Matnli buyruq orqali rasm yaratish (Text-to-Image)', 'Yangi', 'from-emerald-500 to-teal-600', 4],
            ['Uslub Nusxalash', 'uslub-nusxalash', 'fa-solid fa-masks-theater', 'Namuna rasm uslubini mahsulotingizga o\'tkazish', 'Yangi', 'from-cyan-500 to-blue-600', 5],
            ['Smart Matn', 'kartochka-ai', 'fa-solid fa-pen-nib', 'Rasmdan mahsulot kartochkasi ma\'lumotlarini chiqarish', 'Yangi', 'from-amber-500 to-orange-600', 6],
            ['Fashion AI', 'fashion-ai', 'fa-solid fa-shirt', 'Kiyimni virtual modelga kiygizib ko\'rish', 'Yangi', 'from-rose-500 to-pink-600', 7],
            ['Fotosesiya PRO', 'fotosesiya-pro', 'fa-solid fa-camera-retro', 'Mahsulot uchun 8 ta professional reklama surati', 'Yangi', 'from-gray-700 to-gray-900', 8],
            ['Video AI', 'video-ai', 'fa-solid fa-video', 'Matn yoki rasmdan professional video yaratish (Tiba AI)', 'Yangi', 'from-violet-600 to-fuchsia-600', 9],
        ];

        $stmt = $pdo->prepare("INSERT INTO services (name, slug, icon, description, badge, gradient, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($initialServices as $s) {
            $stmt->execute($s);
        }
    }

    // Migration: Add 'Kartochkani to'liq sozlash' if not in services
    $exists = $pdo->query("SELECT COUNT(*) FROM services WHERE slug = 'kartochka-sozlash'")->fetchColumn();
    if ($exists == 0) {
        $maxSort = (int)$pdo->query("SELECT MAX(sort_order) FROM services")->fetchColumn();
        $pdo->prepare("INSERT INTO services (name, slug, icon, description, badge, gradient, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)")
            ->execute(['Kartochkani to\'liq sozlash', 'kartochka-sozlash', 'fa-solid fa-sliders', 'Marketplace uchun tovar kartochkangizni to\'liq sozlang. AI barcha maydonlarni avtomatik to\'ldiradi.', 'YANGI', 'from-emerald-600 to-teal-600', $maxSort + 1]);
    }

    // Instruments table
    $pdo->exec("CREATE TABLE IF NOT EXISTS instruments (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        slug TEXT NOT NULL,
        description TEXT NOT NULL,
        icon TEXT NOT NULL,
        gradient TEXT NOT NULL,
        color TEXT DEFAULT 'indigo',
        category TEXT DEFAULT 'tools',
        link TEXT,
        is_external INTEGER DEFAULT 0,
        badge TEXT,
        status TEXT DEFAULT 'active',
        sort_order INTEGER DEFAULT 0
    )");

    // Seed instruments
    $icount = $pdo->query("SELECT COUNT(*) FROM instruments")->fetchColumn();
    if ($icount == 0) {
        $instruments = [
            ['STUV Kalkulyatori', 'stuv-kalkulyatori', 'Soliq, Tannarx, Usta (foyda) va Vazn asosida mahsulotning yakuniy narxini va foydasini hisoblang.', 'fa-solid fa-calculator', 'from-violet-600 to-fuchsia-600', 'violet', 'tools', '/stuv-kalkulyatori', 0, '', 'active', 1],
            ['QQS Kalkulyatori', 'qqs-kalkulyatori', 'Qachon majburiy ravishda QQSga o\'tishingizni bilib oling. O\'zR Soliq Kodeksi 462-moddasiga binoan.', 'fa-solid fa-receipt', 'from-amber-500 to-orange-600', 'amber', 'tools', '/qqs-kalkulyatori', 0, '', 'active', 2],
            ['Sotuvlar Analitikasi', 'sotuvlar-analitikasi', 'Haftalik va oylik sotuvlaringizni tahlil qiling. O\'sish sur\'ati va trendlarni kuzating.', 'fa-solid fa-chart-line', 'from-emerald-500 to-teal-600', 'emerald', 'tools', '/sotuvlar-analitikasi', 0, '', 'active', 3],
            ['InstaLink AI', 'insta-link', 'Instagram videolaringizga izoh qoldirganlarga avtomatik Direct xabar va linklar yuboring.', 'fa-brands fa-instagram', 'from-purple-600 via-pink-500 to-orange-500', 'pink', 'tools', '/insta-link', 0, '', 'active', 4],
            ['Didox ETTY', 'didox-etty', 'Didox orqali ETTY yukxatini avtomatik yaratish. Uzum buyurtmalari uchun bir tugma bilan rasmiylashtiring.', 'fa-solid fa-file-invoice', 'from-cyan-500 to-blue-600', 'cyan', 'tools', '/didox-etty', 0, 'Yangi', 'active', 5],
            ['Raqiblar Narxi Monitori', 'raqiblar-narxi', 'Uzum Market\'dagi raqobatchilar narxini real vaqtda tekshiring.', 'fa-solid fa-binoculars', 'from-gray-600 to-gray-700', 'gray', 'tools', '#', 0, '', 'coming_soon', 6],
            ['Zoom Selling AI', 'zoom-selling', 'Kategoriyalar va har bir tovar uchun mukammal AI tahlil. Raqobat, narx, talab va trend analizi.', 'fa-solid fa-magnifying-glass-chart', 'from-gray-600 to-gray-700', 'gray', 'tools', '#', 0, '', 'coming_soon', 7],
            ['Yo\'qolgan Tovarlar', 'yoqolgan-tovarlar', 'Omborda yotib zarar keltiruvchi tovarlarni aniqlang. AI maslahatlarini oling.', 'fa-solid fa-box-open', 'from-amber-500 to-red-600', 'amber', 'reports', '/hisobotlar', 0, 'Muhim', 'active', 1],
            ['Foyda Hisoboti', 'foyda-hisoboti', 'Har bir tovar bo\'yicha sof foydani hisoblash. Komissiya va xarajatlarni inobatga olgan holda.', 'fa-solid fa-file-invoice', 'from-gray-600 to-gray-700', 'gray', 'reports', '#', 0, '', 'coming_soon', 2],
            ['Oylik Taqqoslash', 'oylik-taqqoslash', 'Oyma-oy sotuvlar, xarajatlar va foyda ko\'rsatkichlarini taqqoslash.', 'fa-solid fa-chart-bar', 'from-gray-600 to-gray-700', 'gray', 'reports', '#', 0, '', 'coming_soon', 3],
            ['Kapital Bank', 'kapital-bank', 'Biznes hisob varag\'ingizni onlayn oching. YaTT va MChJ uchun qadamba-qadam yo\'riqnoma.', 'fa-solid fa-landmark', 'from-green-500 to-emerald-600', 'green', 'banks', '/kapital-bank', 0, '', 'active', 1],
            ['TBC Bank', 'tbc-bank', 'Gruziyaning eng yirik banki O\'zbekistonda. Zamonaviy mobil ilova va qulay tariflar.', 'fa-solid fa-building-columns', 'from-blue-600 to-indigo-700', 'blue', 'banks', 'https://www.tbcbank.uz/uz/business', 1, '', 'active', 2],
            ['Tenge Bank', 'tenge-bank', 'Qozog\'istonlik bank O\'zbekistonda. Innovatsion raqamli banking va qulay kreditlar.', 'fa-solid fa-coins', 'from-violet-600 to-purple-700', 'violet', 'banks', 'https://tengebank.uz/uz/business', 1, '', 'active', 3],
        ];
        $istmt = $pdo->prepare("INSERT INTO instruments (name, slug, description, icon, gradient, color, category, link, is_external, badge, status, sort_order) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        foreach ($instruments as $i) { $istmt->execute($i); }
    }

    // Migration: rename STUV -> Sotuv Kalkulyatori
    $pdo->exec("UPDATE instruments SET name = 'Sotuv Kalkulyatori', description = 'Tannarx va sotuv narx kalkulyatori. Foydangizni aniq hisoblang va saqlang.' WHERE slug = 'stuv-kalkulyatori'");
    $pdo->exec("UPDATE services SET name = 'Sotuv Kalkulyatori' WHERE slug = 'stuv-kalkulyatori' OR name = 'STUV Kalkulyatori'");

    // 7. Payments table
    $pdo->exec("CREATE TABLE IF NOT EXISTS payments (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        package_id TEXT NOT NULL,
        package_name TEXT NOT NULL,
        credits INTEGER NOT NULL,
        amount INTEGER NOT NULL,
        status TEXT DEFAULT 'pending', -- pending, approved, rejected
        receipt_path TEXT,
        admin_note TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(user_id) REFERENCES users(id)
    )");

    // Migration: Add Click/Payme payment columns if missing
    try { $pdo->exec("ALTER TABLE payments ADD COLUMN provider TEXT"); } catch (Exception $e) {}
    try { $pdo->exec("ALTER TABLE payments ADD COLUMN gateway_payment_id TEXT"); } catch (Exception $e) {}
    try { $pdo->exec("ALTER TABLE payments ADD COLUMN external_id TEXT"); } catch (Exception $e) {}

    // 8. Support tables
    $pdo->exec("CREATE TABLE IF NOT EXISTS support_sessions (
        chat_id TEXT PRIMARY KEY,
        step TEXT DEFAULT 'start',
        account_info TEXT,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    $pdo->exec("CREATE TABLE IF NOT EXISTS support_messages (
        admin_msg_id INTEGER PRIMARY KEY,
        user_chat_id TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // 8.5. Instagram Automation Rules
    $pdo->exec("CREATE TABLE IF NOT EXISTS insta_rules (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        trigger_word TEXT NOT NULL,
        dm_text TEXT NOT NULL,
        button_text TEXT,
        button_url TEXT,
        is_active INTEGER DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(user_id) REFERENCES users(id)
    )");

    // Migration: Add insta_credentials for users
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN insta_access_token TEXT");
        $pdo->exec("ALTER TABLE users ADD COLUMN insta_account_id TEXT");
    } catch (Exception $e) { /* Ignore if exists */ }

    // 9. Packages table (pricing packages)
    $pdo->exec("CREATE TABLE IF NOT EXISTS packages (
        id TEXT PRIMARY KEY,
        name TEXT NOT NULL,
        credits INTEGER NOT NULL,
        price INTEGER NOT NULL,
        icon TEXT NOT NULL DEFAULT 'fa-coins',
        gradient TEXT NOT NULL DEFAULT 'from-gray-600 to-gray-500',
        badge TEXT,
        badge_gradient TEXT,
        features TEXT,
        sort_order INTEGER DEFAULT 0,
        is_active INTEGER DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Seed packages if empty
    $pkgCount = $pdo->query("SELECT COUNT(*) FROM packages")->fetchColumn();
    if ($pkgCount == 0) {
        $stmtPkg = $pdo->prepare("INSERT INTO packages (id, name, credits, price, icon, gradient, badge, badge_gradient, features, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $defaultPkgs = [
            ['starter', "Boshlang'ich", 50, 69000, 'fa-seedling', 'from-gray-600 to-gray-500', null, null, json_encode(['~10 ta infografika', '~5 ta fotosesiya', 'Muddati cheksiz']), 1],
            ['professional', 'Professional', 150, 189000, 'fa-rocket', 'from-indigo-600 to-purple-600', 'Mashhur', 'from-indigo-600 to-purple-600', json_encode(['~30 ta infografika', '~15 ta fotosesiya', 'Muddati cheksiz']), 2],
            ['business', 'Biznes', 500, 549000, 'fa-gem', 'from-emerald-600 to-teal-600', 'Tejamkor', 'from-emerald-600 to-teal-600', json_encode(['~100 ta infografika', '~50 ta fotosesiya', 'Muddati cheksiz']), 3],
            ['enterprise', 'Enterprise', 1500, 1449000, 'fa-crown', 'from-amber-600 to-orange-600', 'Eng foydali', 'from-amber-600 to-orange-600', json_encode(['~300 ta infografika', '~150 ta fotosesiya', 'Muddati cheksiz']), 4],
        ];
        foreach ($defaultPkgs as $p) {
            $stmtPkg->execute($p);
        }
    }

    // 10. Showcase Samples (namunalar carousel + before/after)
    $pdo->exec("CREATE TABLE IF NOT EXISTS showcase_samples (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT,
        image_path TEXT,
        before_image_path TEXT,
        after_image_path TEXT,
        type TEXT DEFAULT 'carousel',
        sort_order INTEGER DEFAULT 0,
        is_active INTEGER DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Default prompts
    $stmt = $pdo->prepare("SELECT id FROM configs WHERE id = 'prompts'");
    $stmt->execute();
    if (!$stmt->fetch()) {
        $prompts = json_encode(getDefaultPrompts(), JSON_UNESCAPED_UNICODE);
        $stmt = $pdo->prepare("INSERT OR REPLACE INTO configs (id, data) VALUES ('prompts', ?)");
        $stmt->execute([$prompts]);
    }
}

/**
 * Tizim sozlamasini olish (configs jadvalidan)
 */
function getSetting($key, $default = null) {
    $db = getDB();
    $stmt = $db->prepare("SELECT data FROM configs WHERE id = ?");
    $stmt->execute(["setting_$key"]);
    $row = $stmt->fetch();
    return $row ? $row['data'] : $default;
}

/**
 * Tizim sozlamasini saqlash
 */
function setSetting($key, $value) {
    $db = getDB();
    $stmt = $db->prepare("INSERT OR REPLACE INTO configs (id, data, updated_at) VALUES (?, ?, datetime('now'))");
    $stmt->execute(["setting_$key", $value]);
}

// Backward compatibility helper (kept effectively empty or alias)
function ensureUserTables($pdo) {
    runMigrations($pdo);
}

// Alias for initDB if used elsewhere
function initDB($pdo) {
    runMigrations($pdo);
}

function getDefaultPrompts() {
    return [
        'infografika' => [
            'ozon' => "HIGH-QUALITY Marketplace creative for \"{productName}\".
STYLE: Modern, professional, and reliable. Professional ecommerce aesthetic.
LAYOUT: Balanced composition with rounded badges.
FEATURES: List {featuresList} using clear, friendly yet professional icons.
COLOR: Blue used as the primary accent color.
QUALITY: Photorealistic 8K quality, perfect studio lighting, razor-sharp textures.
LANGUAGE: STRICT - All text MUST be in {targetLang} only. TRANSLATE perfectly if input is different.
STRICT RULE: DO NOT use restricted words like 'Premium', 'Original', 'Hit', 'Xit', 'Best Seller', or 'N1' in any language.
Aspect Ratio: 3:4.",
            'instagram' => "SENSATIONAL, high-end Instagram ad creative for \"{productName}\".
CONCEPT: Viral social media aesthetic. High-energy, luxury lifestyle advertisement.
VISUALS:
- Product in dynamic cinematic motion with volumetric lighting and lens flares.
- Background: Trendy abstract elements, glowing neon accents, and high-fashion textures.
- Typography: Bold, experimental headline text that commands attention.
- Features: {featuresList} presented as interactive 'story stickers' or floating glassmorphism UI elements.
- Quality: Saturated, rich colors, high contrast, 8K razor-sharp details.
- Language: ALWAYS in {targetLang} only. Translate everything perfectly.
STRICT RULE: NO restricted words like 'Premium', 'Original', 'Hit', 'Xit', 'Best Seller', or 'N1'.
Format: Square (1:1).",
            'minimal' => "HIGH-QUALITY minimalist presentation for \"{productName}\".
CONCEPT: Silent elegance, high-end lifestyle aesthetic.
VISUALS:
- Palette: Soft neutrals, silk-like textures, or pure white matte studio.
- Composition: Large negative space to emphasize product's form and high-quality materials.
- Lighting: Soft natural window light with gentle transitions and soft bokeh.
- Typography: Elegant, thin serif fonts for a sophisticated brand feel.
- Features: {featuresList} displayed as tiny, precise micro-details or elegant subtext.
- Quality: Razor-sharp focus, 8K photorealistic, museum-grade display.
- Language: MUST be {targetLang}. Translate all features to {targetLang}.
STRICT RULE: DO NOT use restricted words like 'Premium', 'Original', 'Hit', 'Xit', 'Best Seller', or 'N1' in any language.
Aspect Ratio: 3:4.",
            'uzum' => "ULTRA-ELITE E-commerce Infographic for product \"{productName}\".
DESIGN PHILOSOPHY: Create a world-class marketplace card for Uzum (mirroring Apple or high-end tech brands). 
SCENE: Professional high-end studio hero shot. Use cinematic 3-point lighting to emphasize every detail and texture of the product.
COMPOSITION:
- Product is the STAR: Recreate it faithfully, centered and perfectly sharp.
- Layout: Balanced, spacious, and highly professional layout with elegant margins.
- Features: List {featuresList} using ultra-modern, custom-designed glassmorphism badges and premium icons.
- Accents: Use subtle glows and soft dynamic shadows for a 3D depth effect.
VISUAL STYLE:
- Typography: Use bold, premium Sans-Serif fonts (Inter, Montserrat) with perfect kerning.
- Background: Luxury studio gradient with subtle atmospheric haze or professional depth-of-field.
QUALITY: Absolute photorealism, 8K ultra-sharp rendering, ray-traced shadows, zero artifacts.
LANGUAGE: MANDATORY - Every single word MUST be perfectly translated into {targetLang}. 
STRICT RULE: NO restricted words like 'Premium', 'Original', 'Hit', 'Xit', 'Best Seller', or 'N1'.
Aspect Ratio: 3:4.",
            'marketplace' => "ULTRA-PROFESSIONAL E-commerce Infographic for product \"{productName}\".
DESIGN PHILOSOPHY: Create a high-converting, high-quality marketplace card suitable for Uzum, Ozon, and Wildberries.
SCENE: Professional studio hero shot. High-end lighting (Key, Fill, and Rim lighting) to emphasize product textures and depth.
COMPOSITION: 
- Dominant product visual, perfectly sharp and centered.
- List {featuresList} as a clean hierarchy of professional icons and short, bold titles.
- Use elegant marketing badges (e.g., 'High Quality', 'Top Choice') and technical callouts.
VISUAL STYLE: 
- Elegant, modern, and trust-inspiring. 
- Background: Professional studio gradient with subtle atmospheric depth.
- Typography: Use professional, easy-to-read sans-serif fonts (Inter or Montserrat).
QUALITY: 8K resolution, photorealistic, cinematic rendering, razor-sharp edges.
LANGUAGE: ABSOLUTE REQUIREMENT - Every single word on the infographic MUST be in {targetLang} only. You MUST translate all product names and features accurately into {targetLang}.
STRICT RULE: DO NOT use restricted words like 'Premium', 'Original', 'Hit', 'Xit', 'Best Seller', or 'N1' in any language.
Aspect Ratio: 3:4.",
            'yandex' => "HIGH-FIDELITY Yandex Market commercial graphic for \"{productName}\".
STYLE: Clean, technical, and minimalist. Information-dense but highly organized.
COMPOSITION: Product as the hero, centered or slightly offset to allow technical badges.
BADGES: Display {featuresList} as clear technical specifications using Yandex-style minimalist badges.
COLOR: Neutral studio background with subtle Yandex Yellow (#FFCC00) highlights.
QUALITY: Sharp 8K rendering, studio lighting, professional e-commerce post-processing.
LANGUAGE: MANDATORY - Output only {targetLang} text. All specifications and headings must be TRANSLATED to {targetLang}. No other language permitted.
STRICT RULE: DO NOT use restricted words like 'Premium', 'Original', 'Hit', 'Xit', 'Best Seller', or 'N1' in any language.
Aspect Ratio: 3:4.",
            'wb' => "SENSATIONAL Wildberries (WB) high-converting sales card for \"{productName}\".
CONCEPT: Viral, attention-grabbing, and bold. Scroll-stopping marketing design.
GRAPHICS: Large, punchy headings. Use {featuresList} in bright, high-contrast stickers, round badges, and ribbons (e.g., 'New Arrival', 'High Quality').
COLOR: Dynamic use of WB corporate colors (Purple/Pink gradient: #CB11AB to #481173).
QUALITY: Ultra-crisp, 8K, high contrast, vivid colors, photorealistic product details.
LANGUAGE: URGENT - All text in {targetLang} only. Bold headlines and sales labels must be in {targetLang}. If the input is in another language, TRANSLATE it to {targetLang}.
STRICT RULE: DO NOT use restricted words like 'Premium', 'Original', 'Hit', 'Xit', 'Best Seller', or 'N1' in any language.
Aspect Ratio: 3:4.",
        ],
        'foto-tahrir' => [
            'minimalist' => "Elite studio product photography. Pure white seamless cyclorama wall, softbox lighting, 100% sharp product details, professional catalog style.",
            'bright' => "High-end commercial photography. Vibrant multi-color gradient background, energetic key lighting, rim light for product separation, crisp and saturated.",
            'premium' => "Luxury dark-themed photography. Deep slate or black marble background, dramatic chiaroscuro lighting, elegant reflections, golden-hour highlights.",
            'studio' => "Professional photography studio setup. Neutral grey backdrop, cinematic three-point lighting (Key, Fill, Back), perfectly balanced exposure.",
            'nature' => "Product in organic outdoor setting. Soft sunlight through leaves, natural wooden or stone surface, cinematic depth-of-field (bokeh background).",
            'neon' => "Cyberpunk high-tech aesthetic. Dark environment with bold blue and pink neon lights, futuristic reflections, atmospheric fog/haze.",
            'vintage' => "Retro film aesthetic. Warm tones, antique wooden table, nostalgic 35mm film grain, soft-focus background, classic commercial look.",
            'loft' => "Modern industrial lifestyle setting. Exposed brick wall, large studio window light, concrete flooring, authentic urban atmosphere.",
            'water' => "Dynamic aquatic scene. Crystal clear water splashes, refreshing droplets on product, cool blue tones, high-speed photography style.",
            'abstract' => "Artistic 3D geometric background. Soft pastel shapes, creative shadows, modern museum installation vibe, clean and avant-garde.",
            'home' => "Cozy premium home interior. Marble kitchen counter or wooden shelf, warm ambient interior light, soft out-of-focus living room background.",
            'tech' => "Futuristic engineering workspace. Blue circuit-board patterns, holographic elements, laser-precise lighting, high-tech innovative vibe."
        ],
        'paket' => [
            'marketplace' => [
                "SLIDE 1 (HERO): THE DEFINITIVE E-COMMERCE HERO POSTER for \"{productName}\". 
CONCEPT: Visual excellence that creates immediate desire. Focus on high-end commercial aesthetics (Apple/Dyson style).
LAYOUT: The product is the undisputed masterpiece—large, centered, and rendered with extreme detail.
VISUALS: Elite-level cinematic lighting (rim highlights, sophisticated depth of field). The background must be a pristine, high-end minimalist environment (e.g., luxury studio, brushed metal textures, or elegant marble) that perfectly frames the product.
TEXT: Elegant, authority-driven typography. Show the product name only. Highlight \"{firstFeature}\" with a subtle, ultra-modern glassmorphism badge.
STRICT RULES: 1. DO NOT USE words like 'Sotib oling', 'Buy', 'Premium', 'Hit', 'Original', 'Best'. 2. NEVER show prices. 3. 100% PRODUCT FIDELITY: Maintain every pixel of the original product design. 4. ALL text in {targetLang}." ,
                "SLIDE 2 (BENEFITS): Infographic of {featuresList}. Use professional icons and clear headers. Focus on problem-solving benefits. Use high-end commercial fonts. Language: {targetLang} only.",
                "SLIDE 3 (DETAIL): Close-up texture/macro view of the product. Focus on high-quality materials and craftsmanship. Technical callouts in {targetLang}.",
                "SLIDE 4 (LIFESTYLE): Product in emotional use-case scenario. High-end cinematic lifestyle photography showing real-world application. Text in {targetLang}.",
                "SLIDE 5 (DYNAMIC CONCLUSION): Choose the MOST APPROPRIATE format for this specific product from these 4 options:
1) DIMENSIONS & SPECS: Focus on technical measurements and size labels.
2) WHAT'S IN THE BOX: Visual layout of the product package contents.
3) COMPARISON: A table showing \"Our Product\" vs \"Typical others\".
4) USAGE GUIDE: Simple 3-4 step visual instructions on how to use.
INSTRUCTIONS: Select ONLY ONE format that fits best. ALL text in {targetLang}. 
STRICT WORD RULE: NEVER use 'Premium', 'Original', 'Hit', 'Xit', 'Best Seller', or 'N1'. Focus on technical or practical utility." 
            ]
        ],
        'system' => [
            'infografika_suffix' => "\n\nCRITICAL DESIGN RULES:
1. PRESERVE the product's original details, shape, and labels exactly from the image.
2. ALL text MUST be in {targetLang}. Use professional, modern fonts with perfect readability.
3. VISUALS: Use glassmorphism, soft shadows, and high-end studio lighting for elite quality.
4. QUALITY: 8K resolution, ray-traced rendering, razor-sharp edges, museum-grade aesthetic.
5. COMPOSITION: Balanced commercial layout with professional spacing.
6. ASPECT RATIO: {aspectRatio}.",
            'foto_tahrir_suffix' => "\n\nCRITICAL PHOTOGRAPHY RULES:
1. DO NOT ALTER THE PRODUCT. Preserve every pixel of the original item's labels, text, and form.
2. ONLY RE-RENDER THE BACKGROUND according to the style chosen.
3. LIGHTING: Ensure the new background's lighting casts realistic shadows and reflections on the product.
4. QUALITY: Photorealistic 8K, commercial studio grade, razor-sharp focus.
5. ASPECT RATIO: {aspectRatio}.",
            'paket_suffix' => "\n\nCRITICAL PRODUCT FIDELITY RULES:
- PRODUCT IDENTITY: You are STRICTLY PROHIBITED from altering, modifying, or simplifying the product. The product in the output must be an EXACT 1:1 REPLICATION of the product in the input image.
- LABELS & TEXT: Every single label, logo, brand name, serial number, and piece of text ON THE PRODUCT must be preserved perfectly as it appears in the original image. DO NOT smudge, blur, or change fonts of the text on the product.
- FORM & SHAPE: Maintain the exact physical geometry and dimensions. Do not round edges that are sharp or vice versa.
- COLORS: Use the exact color palette of the product. No color shifts are allowed on the product itself.
- NO ADDITIONS: Do not add any new buttons, cables, or design elements to the product that aren't in the original image.

PREMIUM DESIGN & AESTHETIC:
- STYLE: Create a world-class, ultra-premium e-commerce presentation (Apple/High-end tech brand aesthetic).
- BACKGROUND: Choose a sophisticated background that matches the product category. The background should be professionally rendered but the PRODUCT must remain the main focus.
- LIGHTING: Use high-end cinematic studio lighting (Rim lighting, Fill lighting). Lighting must follow the background but should not distort the product's natural appearance.
- CONSISTENCY: Ensure identical styling, fonts (Inter/Montserrat), and premium color scheme across all 5 slides.
- LANGUAGE: ALL text on the infographics MUST BE in {targetLang} only.
- ASPECT RATIO: {aspectRatio}.
- NO FORBIDDEN WORDS: ABSOLUTELY NEVER use 'Premium', 'Original', 'Hit', 'Xit', 'Best Seller', or 'N1'. Use descriptive, high-quality language instead."
        ]
    ];
}

function jsonResponse($data, $code = 200) {
    // Oldingi barcha outputlarni tozalash
    while (ob_get_level()) ob_end_clean();
    ob_start();

    http_response_code($code);
    // Debug ma'lumotlarni production da olib tashlash
    if ($code >= 400) {
        unset($data['debug']);
    }
    // API so'rovni logga yozish
    logApiRequest($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    ob_end_flush();
    exit;
}

// API so'rovlarini loglash
function logApiRequest($statusCode = 200) {
    $logDir = __DIR__ . '/../tmp';
    if (!is_dir($logDir)) mkdir($logDir, 0755, true);
    
    $logFile = $logDir . '/api_requests.log';
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $time = date('Y-m-d H:i:s');
    
    $line = "[$time] $method $uri | $statusCode | IP: $ip\n";
    
    // Fayl hajmi 5MB dan oshsa tozalash
    if (file_exists($logFile) && filesize($logFile) > 5 * 1024 * 1024) {
        $lines = file($logFile);
        $lines = array_slice($lines, -500); // Oxirgi 500 qatorni saqlash
        file_put_contents($logFile, implode('', $lines));
    }
    
    file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
}

function getInput() {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true) ?: [];
    return $data;
}

/**
 * Matn inputni tozalash (XSS himoya)
 */
function sanitize($str) {
    if (!is_string($str)) return $str;
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}

/**
 * Rate Limiting — IP asosida
 */
function checkRateLimit($maxPerMinute = 10) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $dir = __DIR__ . '/../tmp/rate_limits';
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $file = $dir . '/' . md5($ip) . '.json';
    $now = time();
    $data = ['requests' => [], 'daily' => []];

    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true) ?: $data;
    }

    // So'nggi 60 soniyada yuborilgan so'rovlar
    if (isset($data['requests'])) {
        $data['requests'] = array_filter($data['requests'], fn($t) => ($now - $t) < 60);
    } else {
        $data['requests'] = [];
    }

    if (count($data['requests']) >= $maxPerMinute) {
        jsonResponse(['error' => 'Juda ko\'p so\'rov. 1 daqiqa kutib turing.'], 429);
    }

    // Kunlik limit (Olib tashlandi)
    /*
    $today = date('Y-m-d');
    if (isset($data['daily'])) {
        $data['daily'] = array_filter($data['daily'], fn($d) => $d === $today);
    } else {
        $data['daily'] = [];
    }

    if (count($data['daily']) >= 100) {
        jsonResponse(['error' => 'Kunlik limit tugadi (100 so\'rov). Ertaga qayta urinib ko\'ring.'], 429);
    }
    */

    $data['requests'][] = $now;
    $data['daily'][] = $today;
    file_put_contents($file, json_encode($data));
}

/**
 * Base64 rasm hajmini tekshirish (max 10MB)
 */
function validateImageSize($base64Data, $maxMB = 10) {
    if (empty($base64Data)) return;
    // Base64 hajmi ~ original * 1.37
    $sizeBytes = strlen($base64Data) * 0.73;
    $maxBytes = $maxMB * 1024 * 1024;
    if ($sizeBytes > $maxBytes) {
        jsonResponse(['error' => "Rasm hajmi juda katta ({$maxMB}MB dan oshmasligi kerak)"], 400);
    }
}

// ========== TANGA (COIN) TIZIMI ==========

/**
 * Har bir AI asbobning narxi (tanga)
 */
function getToolCost($toolSlug) {
    $costs = [
        'foto-tahrir'       => 5,
        'infografika'       => 5,   // generate.php
        'infografika-paketi'=> 20,
        'noldan-yaratish'   => 5,
        'uslub-nusxalash'   => 5,
        'smart-matn'        => 5,
        'kartochka-ai'      => 3,
        'fashion-ai'        => 8,
        'fotosesiya-pro'    => 30,
        'video-ai'          => 15,
    ];
    return $costs[$toolSlug] ?? 5;
}

/**
 * Balansni atomik tekshirish va DARHOL ayirish (reserve).
 * Race condition himoyasi: oldin tekshirish, keyin ayirish emas,
 * balki bir SQL da tekshirish VA ayirish bir vaqtda.
 * 
 * Xato bo'lsa refundBalance() bilan qaytarish kerak.
 */
function requireBalance($toolSlug) {
    $user = getAuthUser();

    if (!$user) {
        jsonResponse([
            'error' => 'Tizimga kiring',
            'auth_required' => true,
        ], 401);
    }

    // Foydalanuvchi uchun concurrent request limiti
    checkGenerationLimit($user['id']);

    $cost = getToolCost($toolSlug);
    $db = getDB();
    
    // ATOMIK: balansni tekshirish VA ayirish bir SQL da
    // Agar balance < cost bo'lsa, 0 ta qator o'zgaradi
    $stmt = $db->prepare("UPDATE users SET balance = balance - ? WHERE id = ? AND balance >= ?");
    $stmt->execute([$cost, $user['id'], $cost]);
    
    if ($stmt->rowCount() === 0) {
        // Hozirgi balansni olish (xato xabari uchun)
        $stmt2 = $db->prepare("SELECT balance FROM users WHERE id = ?");
        $stmt2->execute([$user['id']]);
        $balance = (int)$stmt2->fetchColumn();
        
        releaseGenerationLock($user['id']);
        jsonResponse([
            'error' => "Tangalar yetarli emas. Kerak: {$cost}, Balans: {$balance}",
            'insufficient_balance' => true,
            'cost' => $cost,
            'balance' => $balance,
        ], 402);
    }

    // Yangi balansni olish
    $stmt3 = $db->prepare("SELECT balance FROM users WHERE id = ?");
    $stmt3->execute([$user['id']]);
    $user['balance'] = (int)$stmt3->fetchColumn();

    return ['user' => $user, 'cost' => $cost];
}

/**
 * Tangalar allaqachon requireBalance() da ayirilgan.
 * Bu funksiya endi faqat yangi balansni qaytaradi.
 * (Backward compatibility uchun saqlab qo'yildi)
 */
function deductBalance($userId, $cost, $toolSlug = '') {
    // Tangalar allaqachon requireBalance() da ayirilgan
    // Faqat hozirgi balansni qaytarish
    releaseGenerationLock($userId);
    
    $db = getDB();
    $stmt = $db->prepare("SELECT balance FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    return (int)$stmt->fetchColumn();
}

/**
 * Xato bo'lganda tangalarni qaytarish (refund)
 */
function refundBalance($userId, $cost) {
    $db = getDB();
    $stmt = $db->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
    $stmt->execute([$cost, $userId]);
    
    releaseGenerationLock($userId);
    
    $stmt2 = $db->prepare("SELECT balance FROM users WHERE id = ?");
    $stmt2->execute([$userId]);
    return (int)$stmt2->fetchColumn();
}

/**
 * Foydalanuvchi uchun bir vaqtda generatsiya limitini tekshirish
 * Bitta foydalanuvchi bir vaqtda faqat 2 ta generatsiya qilishi mumkin
 */
function checkGenerationLimit($userId) {
    $dir = __DIR__ . '/../tmp/gen_locks';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    
    $file = $dir . '/user_' . $userId . '.json';
    $data = ['active' => 0, 'last_request' => 0];
    if (file_exists($file)) {
        $data = json_decode(@file_get_contents($file), true) ?: $data;
        // 2 daqiqadan eski locklar tozalansin (server crash himoyasi)
        if ($data['last_request'] > 0 && (time() - $data['last_request']) > 120) {
            $data = ['active' => 0, 'last_request' => 0];
        }
    }
    
    if ($data['active'] >= 2) {
        jsonResponse([
            'error' => 'Avvalgi so\'rov tugashini kuting. Bir vaqtda faqat 2 ta generatsiya mumkin.',
        ], 429);
    }
    
    $data['active']++;
    $data['last_request'] = time();
    file_put_contents($file, json_encode($data));
}

function releaseGenerationLock($userId) {
    $dir = __DIR__ . '/../tmp/gen_locks';
    $file = $dir . '/user_' . $userId . '.json';
    if (!file_exists($file)) return;
    
    $data = json_decode(@file_get_contents($file), true) ?: ['active' => 0, 'last_request' => 0];
    $data['active'] = max(0, $data['active'] - 1);
    file_put_contents($file, json_encode($data));
}

function getPrompts() {
    $defaults = getDefaultPrompts();
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT data FROM configs WHERE id = 'prompts'");
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row) {
            $saved = json_decode($row['data'], true);
            if (is_array($saved)) {
                // Bazadagi promptlarni standartlar bilan birlashtiramiz (yangi stillar yo'qolib qolmasligi uchun)
                return array_replace_recursive($defaults, $saved);
            }
        }
    } catch (Exception $e) {}
    return $defaults;
}

function callGeminiAPI($parts, $aspectRatio = '3:4') {
    // API kalitlarini yig'ish (rotatsiya uchun)
    $apiKeys = [];
    $mainKey = getenv('GEMINI_API_KEY');
    if ($mainKey) $apiKeys[] = $mainKey;
    // Qo'shimcha kalitlar: GEMINI_API_KEY_2, GEMINI_API_KEY_3, ...
    for ($k = 2; $k <= 10; $k++) {
        $extraKey = getenv("GEMINI_API_KEY_$k");
        if ($extraKey) $apiKeys[] = $extraKey;
    }
    if (empty($apiKeys)) jsonResponse(['error' => 'API Key missing'], 500);

    // Random kalit tanlash (load balancing)
    $apiKey = $apiKeys[array_rand($apiKeys)];

    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3-pro-image-preview:generateContent?key=$apiKey";

    $payload = json_encode([
        'contents' => [['parts' => $parts]],
        'generationConfig' => [
            'responseModalities' => ['IMAGE', 'TEXT'],
        ],
    ]);

    $data = null;
    $maxRetries = 5;
    $retryCount = 0;

    // Bir vaqtdagi so'rovlarni tarqatish uchun random delay (0-2 soniya)
    usleep(random_int(0, 2000000));

    do {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            if ($retryCount < $maxRetries) {
                $retryCount++;
                sleep(2);
                continue;
            }
            jsonResponse(['error' => "cURL xatosi: $curlError"], 500);
        }

        $data = json_decode($response, true);
        
        if ($httpCode === 200) break;

        // Rate limit (429) yoki server xatosi — exponential backoff bilan qayta urinish
        if (in_array($httpCode, [500, 503, 429]) && $retryCount < $maxRetries) {
            $retryCount++;
            
            // 429 uchun — exponential backoff + jitter
            if ($httpCode === 429) {
                $baseDelay = min(pow(2, $retryCount), 30); // 2, 4, 8, 16, 30 soniya
                $jitter = random_int(0, 3);
                $delay = $baseDelay + $jitter;
                
                // Boshqa API key bilan qayta urinish
                if (count($apiKeys) > 1) {
                    $apiKey = $apiKeys[array_rand($apiKeys)];
                    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3-pro-image-preview:generateContent?key=$apiKey";
                    $delay = max(2, $delay / 2); // Boshqa key bilan tezroq
                }
                
                $logMsg = date('[Y-m-d H:i:s] ') . "Rate limit 429 — retry #{$retryCount}, waiting {$delay}s\n";
                file_put_contents(__DIR__ . '/../tmp/gemini_error.log', $logMsg, FILE_APPEND);
                sleep($delay);
            } else {
                sleep(3 + $retryCount); // 500/503 uchun oddiy delay
            }
            continue;
        }

        $msg = $data['error']['message'] ?? 'AI xizmati vaqtincha ishlamayapti';
        $logMsg = date('[Y-m-d H:i:s] ') . "Gemini API Error ($httpCode): " . $response . "\n";
        file_put_contents(__DIR__ . '/../tmp/gemini_error.log', $logMsg, FILE_APPEND);
        jsonResponse(['error' => $msg], $httpCode);

    } while ($retryCount <= $maxRetries);

    $imageBase64 = null;
    $mimeType = 'image/png';

    if (isset($data['candidates'][0]['content']['parts'])) {
        foreach ($data['candidates'][0]['content']['parts'] as $part) {
            if (isset($part['inlineData'])) {
                $imageBase64 = $part['inlineData']['data'];
                $mimeType = $part['inlineData']['mimeType'] ?? 'image/png';
            }
        }
    }

    return ['imageBase64' => $imageBase64, 'mimeType' => $mimeType];
}

function callGeminiTextAPI($parts) {
    $apiKey = getenv('GEMINI_API_KEY');
    if (!$apiKey) jsonResponse(['error' => 'API Key missing'], 500);

    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=$apiKey";

    $payload = json_encode([
        'contents' => [['parts' => $parts]],
        'generationConfig' => [
            'responseMimeType' => 'application/json',
        ],
    ]);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        jsonResponse(['error' => "cURL xatosi: $curlError"], 500);
    }

    $data = json_decode($response, true);
    if ($httpCode !== 200) {
        $msg = $data['error']['message'] ?? 'Gemini API Error';
        error_log("Gemini Text API Error ($httpCode): " . $response);
        jsonResponse(['error' => $msg], $httpCode);
    }

    $textResult = '';
    if (isset($data['candidates'][0]['content']['parts'])) {
        foreach ($data['candidates'][0]['content']['parts'] as $part) {
            if (isset($part['text'])) {
                $textResult .= $part['text'];
            }
        }
    }

    return $textResult;
}

function saveImage($base64, $mimeType, $prefix = 'img') {
    $dir = __DIR__ . '/../generated';
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $filename = $prefix . '_' . bin2hex(random_bytes(6)) . '.png';
    $filepath = $dir . '/' . $filename;
    file_put_contents($filepath, base64_decode($base64));

    return '/generated/' . $filename;
}

/**
 * Havola (URL) orqali rasm yuklash
 */
function fetchImageFromUrl($url) {
    if (empty($url)) return null;
    
    // Xavfsiz URL ekanligini tekshirish
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        jsonResponse(['error' => 'Rasm havolasi noto\'g\'ri'], 400);
    }

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
    ]);

    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $mimeType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);

    if ($httpCode !== 200 || empty($data)) {
        jsonResponse(['error' => 'Rasmni yuklab bo\'lmadi. Havolani tekshiring.'], 400);
    }

    // MIME type tekshirish (faqat rasm)
    if (strpos($mimeType, 'image/') !== 0) {
        jsonResponse(['error' => 'Havola rasmga tegishli emas'], 400);
    }

    // Hajm (max 10MB)
    if (strlen($data) > 10 * 1024 * 1024) {
        jsonResponse(['error' => 'Rasm hajmi juda katta (max 10MB)'], 400);
    }

    return [
        'base64' => base64_encode($data),
        'mimeType' => $mimeType
    ];
}

/**
 * Rasm inputni qayta ishlash (Base64 yoki URL)
 */
function processImageInput($input) {
    if (empty($input)) return null;

    if (strpos($input, 'data:image/') === 0) {
        // Base64 formatda
        if (preg_match('/^data:([^;]+);base64,(.+)$/', $input, $matches)) {
            return [
                'mime_type' => $matches[1],
                'data' => $matches[2]
            ];
        }
    } elseif (strpos($input, 'http') === 0) {
        // URL formatda
        $fetched = fetchImageFromUrl($input);
        if ($fetched) {
            return [
                'mime_type' => $fetched['mimeType'],
                'data' => $fetched['base64']
            ];
        }
    }
    return null;
}

/**
 * Telegramga xabar/rasm yuborish
 */
function sendToTelegram($message, $imagePath = null, $asDocument = true) {
    $token = getenv('TELEGRAM_BOT_TOKEN');
    $chatId = getenv('TELEGRAM_CHANNEL_ID');

    if (!$token || !$chatId) {
        error_log("Telegram Skip: Token/ID missing. BotToken: " . ($token ? 'OK' : 'MISSING') . ", ChatID: " . ($chatId ?: 'MISSING'));
        return false;
    }

    $message = (string)$message;
    
    // Resolve file path
    $realPath = null;
    if ($imagePath) {
        $realPath = realpath(__DIR__ . '/../' . ltrim($imagePath, '/'));
        if (!$realPath || !is_file($realPath)) {
            error_log("Telegram Send: File not found: " . $imagePath);
            $imagePath = null;
        }
    }

    $url = "https://api.telegram.org/bot$token/";
    $data = ['chat_id' => $chatId, 'parse_mode' => 'Markdown'];
    
    if ($imagePath && $realPath) {
        $data['caption'] = substr($message, 0, 1000); // Max 1024
        if ($asDocument) {
            $url .= "sendDocument";
            $data['document'] = new CURLFile($realPath);
        } else {
            $url .= "sendPhoto";
            $data['photo'] = new CURLFile($realPath);
        }
    } else {
        $url .= "sendMessage";
        $data['text'] = substr($message, 0, 4000); // Max 4096
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $res = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if(curl_errno($ch)){
        error_log('Telegram Curl Error: ' . curl_error($ch));
    }
    if ($httpCode !== 200) {
        error_log("Telegram API Error ($httpCode): " . $res);
    }
    curl_close($ch);
    
    return $res;
}

/**
 * Telegramga bir nechta rasm/faylni guruhlab yuborish
 */
/**
 * Telegram API uchun CURL parametrlarini qaytarish (proxy bilan)
 */
function getTelegramCurlOpts() {
    $opts = [
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 60,
    ];
    $proxy = getenv('TELEGRAM_PROXY');
    if ($proxy) {
        $opts[CURLOPT_PROXY] = $proxy;
        // SOCKS5 uchun
        if (strpos($proxy, 'socks5') !== false) {
            $opts[CURLOPT_PROXYTYPE] = CURLPROXY_SOCKS5;
        }
    }
    return $opts;
}

function sendMediaGroupToTelegram($message, $imagePaths = [], $asDocument = true) {
    $token = getenv('TELEGRAM_BOT_TOKEN');
    $chatId = getenv('TELEGRAM_CHANNEL_ID');
    $debugLog = __DIR__ . '/../tmp/telegram_debug.log';

    $logEntry = date('[Y-m-d H:i:s] ') . "sendMediaGroupToTelegram called\n";
    $logEntry .= "  Token: " . ($token ? 'SET (' . strlen($token) . ' chars)' : 'MISSING') . "\n";
    $logEntry .= "  ChatID: " . ($chatId ?: 'MISSING') . "\n";
    $logEntry .= "  ImagePaths: " . json_encode($imagePaths) . "\n";

    if (!$token || !$chatId || empty($imagePaths)) {
        $logEntry .= "  SKIPPED: Data missing\n\n";
        file_put_contents($debugLog, $logEntry, FILE_APPEND);
        return false;
    }

    $message = (string)$message;
    $media = [];
    $postData = ['chat_id' => $chatId];
    
    $fileIndex = 0;
    foreach ($imagePaths as $path) {
        $realPath = realpath(__DIR__ . '/../' . ltrim($path, '/'));
        $logEntry .= "  File[$fileIndex]: path='$path' → realpath='" . ($realPath ?: 'FAILED') . "'";
        if ($realPath && is_file($realPath)) {
            $logEntry .= " (" . filesize($realPath) . " bytes) ✅\n";
            $key = "file_" . $fileIndex;
            $postData[$key] = new CURLFile($realPath);
            $media[] = [
                'type' => $asDocument ? 'document' : 'photo',
                'media' => "attach://$key",
                'caption' => ($fileIndex === 0) ? substr($message, 0, 1000) : '',
                'parse_mode' => 'Markdown'
            ];
            $fileIndex++;
        } else {
            $logEntry .= " ❌ NOT FOUND\n";
        }
    }

    if (empty($media)) {
        $logEntry .= "  SKIPPED: No valid files\n\n";
        file_put_contents($debugLog, $logEntry, FILE_APPEND);
        return false;
    }

    $postData['media'] = json_encode($media);
    $url = "https://api.telegram.org/bot$token/sendMediaGroup";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Proxy va SSL sozlamalari
    foreach (getTelegramCurlOpts() as $opt => $val) {
        curl_setopt($ch, $opt, $val);
    }
    
    $res = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr = curl_error($ch);
    curl_close($ch);

    $logEntry .= "  API Response ($httpCode): " . substr($res, 0, 500) . "\n";
    if ($curlErr) $logEntry .= "  CURL Error: $curlErr\n";
    $logEntry .= "\n";
    file_put_contents($debugLog, $logEntry, FILE_APPEND);
    
    if ($curlErr) error_log('Telegram MediaGroup Curl Error: ' . $curlErr);
    if ($httpCode !== 200) error_log("Telegram MediaGroup API Error ($httpCode): " . $res);
    
    return $res;
}

function getAuthUser() {
    $token = null;
    
    // 1. Try Apache headers
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
        $token = $headers['X-User-Token'] ?? null;
    }
    
    // 2. Try $_SERVER (Standard PHP/Nginx/Built-in server)
    if (!$token) {
        $token = $_SERVER['HTTP_X_USER_TOKEN'] ?? null;
    }

    // 3. Try Cookie
    if (!$token) {
        $token = $_COOKIE['tiba_token'] ?? null;
    }

    if (!$token) return null;

    $db = getDB();
    $now = date('Y-m-d H:i:s');
    $stmt = $db->prepare("
        SELECT u.* 
        FROM users u 
        JOIN user_sessions s ON u.id = s.user_id 
        WHERE s.token = ? AND s.expires_at > ?
    ");
    $stmt->execute([$token, $now]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
