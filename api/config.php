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

// Fatal error ham JSON sifatida qaytsin (barcha API fayllar uchun)
register_shutdown_function(function() {
    $err = error_get_last();
    if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_RECOVERABLE_ERROR])) {
        while (ob_get_level()) ob_end_clean();
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'error' => 'Server xatosi yuz berdi. Qayta urinib ko\'ring.',
            '_err'  => basename($err['file']) . ':' . $err['line'] . ' ' . substr($err['message'], 0, 120),
        ]);
    }
});

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

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
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

// ========== REFERRAL TRACKING ==========
if (isset($_GET['ref']) && is_numeric($_GET['ref'])) {
    setcookie('ref_id', (int)$_GET['ref'], time() + (30 * 24 * 60 * 60), '/');
    $_COOKIE['ref_id'] = (int)$_GET['ref'];
}

function runMigrations($pdo) {
    // Migratsiya versiyasini tekshirish (keraksiz ishlarni oldini olish)
    $currentVersion = 0;
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS migration_version (id INTEGER PRIMARY KEY, version INTEGER DEFAULT 0)");
        $row = $pdo->query("SELECT version FROM migration_version WHERE id = 1")->fetch();
        if ($row) {
            $currentVersion = (int)$row['version'];
        } else {
            $pdo->exec("INSERT INTO migration_version (id, version) VALUES (1, 0)");
        }
    } catch (Exception $e) {}

    $targetVersion = 8; // Har yangi migratsiya qo'shganda +1 qiling
    if ($currentVersion >= $targetVersion) return; // Allaqachon yangilangan

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

    // Add referral columns
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN referred_by INTEGER DEFAULT NULL");
    } catch (Exception $e) { /* Ignore */ }
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN phone TEXT");
    } catch (Exception $e) { /* Ignore */ }

    // Telegram OTP table
    $pdo->exec("CREATE TABLE IF NOT EXISTS telegram_otps (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        telegram_id TEXT NOT NULL,
        otp_code TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

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
    // Migration: Add promo columns to payments
    try { $pdo->exec("ALTER TABLE payments ADD COLUMN promo_code TEXT"); } catch (Exception $e) {}
    try { $pdo->exec("ALTER TABLE payments ADD COLUMN discount_amount INTEGER DEFAULT 0"); } catch (Exception $e) {}

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
    try { $pdo->exec("ALTER TABLE users ADD COLUMN insta_access_token TEXT"); } catch (Exception $e) {}
    try { $pdo->exec("ALTER TABLE users ADD COLUMN insta_account_id TEXT"); } catch (Exception $e) {}

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

    // Analytics Tarix jadvali
    $pdo->exec("CREATE TABLE IF NOT EXISTS analytics_history (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        period INTEGER NOT NULL,
        total_sales REAL DEFAULT 0,
        order_count INTEGER DEFAULT 0,
        total_expenses REAL DEFAULT 0,
        net_profit REAL DEFAULT 0,
        stats_json TEXT,
        pdf_path TEXT,
        cost INTEGER DEFAULT 30,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(user_id) REFERENCES users(id)
    )");

    // 11. Promo Codes table
    $pdo->exec("CREATE TABLE IF NOT EXISTS promo_codes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        code TEXT NOT NULL UNIQUE,
        discount_type TEXT NOT NULL DEFAULT 'percentage', -- percentage | fixed
        discount_value INTEGER NOT NULL DEFAULT 0,
        max_uses INTEGER NOT NULL DEFAULT 1,
        used_count INTEGER NOT NULL DEFAULT 0,
        status TEXT NOT NULL DEFAULT 'active', -- active | disabled
        expires_at DATETIME NOT NULL DEFAULT (datetime('now', '+1 year')),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // 12. Packages: original_price ustunini qo'shish (eski bazalar uchun)
    try { $pdo->exec("ALTER TABLE packages ADD COLUMN original_price INTEGER DEFAULT 0"); } catch (Exception $e) {}

    // 13. Telegram Contacts (bot orqali yig'ilgan kontaktlar)
    $pdo->exec("CREATE TABLE IF NOT EXISTS telegram_contacts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        telegram_id TEXT NOT NULL UNIQUE,
        username TEXT,
        first_name TEXT,
        last_name TEXT,
        phone TEXT,
        user_id INTEGER,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(user_id) REFERENCES users(id)
    )");

    // 14. Migratsiya versiyasini yangilash
    $pdo->exec("UPDATE migration_version SET version = $targetVersion WHERE id = 1");
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
            'uzum' => "MARKETPLACE INFOGRAPHIC — \"{productName}\" — Uzum style.

⛔ RULE 1 — FORBIDDEN WORDS (zero tolerance — scan and eliminate ALL of these):
NEVER write any of these on the image in any language or script:
Premium, Original, Hit, Xit, Yangi, NEW, TOP, N1, Best Seller, Bestseller, Reyting, any star rating (★4.8 etc), Kafolat, Garantiya, Sifatli, Bepul yetkazish, Uzum, Ozon, WB, Wildberries, Eng yaxshi, Tavsiya, Chegirma, Aksiya, 'Sotib oling', 'Xarid qiling', any call-to-action, any trust buzzword.

📐 RULE 2 — TWO-ZONE LAYOUT (mandatory):
ZONE A — PRODUCT ZONE (right 50%): Product only. COMPLETELY TEXT-FREE. 30px minimum gap. No badge, label, or overlay touches the product.
ZONE B — INFO ZONE (left 50%): ALL text, badges, features placed here ONLY.

📋 RULE 3 — ACCURACY: Show ONLY features from: {featuresList}. Do NOT invent specs.
Feature format: [icon] + [BOLD VALUE large] + [short description small]

🎨 DESIGN: Background color and style are determined by the CATEGORY DESIGN TEMPLATE below — follow it exactly. Do NOT default to blue or generic gradient. Product name \"{productName}\": large bold at top of Zone B. Key spec: GIANT text, most dominant.

🌍 LANGUAGE: {targetLang} only. 📐 Aspect Ratio: 3:4.",

            'wb' => "MARKETPLACE INFOGRAPHIC — \"{productName}\" — bold high-energy style.

⛔ RULE 1 — FORBIDDEN WORDS (zero tolerance):
NEVER render: Premium, Original, Hit, Xit, Yangi, NEW, TOP, N1, Best Seller, star ratings (★4.8 etc), Kafolat, Garantiya, Sifatli, Chegirma, Aksiya, Bepul, Wildberries, WB, Uzum, Ozon, any marketplace name, any call-to-action phrase.

📐 RULE 2 — TWO-ZONE LAYOUT:
ZONE A — PRODUCT ZONE (center-right, 50%): Product only. ZERO text touches the product. 30px gap minimum.
ZONE B — TEXT ZONE (left panel + top/bottom strips): ALL text and badges here exclusively.

📋 RULE 3 — ACCURACY: Show ONLY features from: {featuresList}. Do NOT add invented specs.
Each badge: bold key value + small descriptive label + icon.

🎨 DESIGN: Background color and composition are determined by the CATEGORY DESIGN TEMPLATE below — follow it exactly, do NOT default to blue. Product name \"{productName}\": EXTRA LARGE bold at top of Zone B. Key spec: GIANT — most eye-catching. Typography: Extra-bold heavy sans-serif.

🌍 LANGUAGE: {targetLang} only. 📐 Aspect Ratio: 3:4.",

            'ozon' => "MARKETPLACE INFOGRAPHIC — \"{productName}\" — clean professional style.

⛔ RULE 1 — FORBIDDEN WORDS (zero tolerance):
NEVER write: Premium, Original, Hit, TOP, N1, star ratings, Kafolat, Garantiya, Chegirma, Aksiya, Ozon, Uzum, WB, any marketplace name, any call-to-action phrase.

📐 RULE 2 — TWO-ZONE LAYOUT:
ZONE A — PRODUCT ZONE (55% center/right): Product only. COMPLETELY TEXT-FREE. Clear 30px margin.
ZONE B — INFO ZONE (45% left or top/bottom): Product name and all feature badges here only.

📋 RULE 3 — ACCURACY: Show ONLY features from: {featuresList}. Do not invent specs.
Each feature: clean icon + bold value + small label below.

🎨 DESIGN: Background color and visual style are determined by the CATEGORY DESIGN TEMPLATE below — follow it exactly. Do NOT use a plain blue background. Product name bold large at top of Zone B. Feature badges: clean minimal rounded, high readability. Key spec: LARGEST dominant text element.

🌍 LANGUAGE: {targetLang} only. 📐 Aspect Ratio: 3:4.",

            'yandex' => "MARKETPLACE INFOGRAPHIC — \"{productName}\" — Yandex Market technical style.

⛔ RULE 1 — FORBIDDEN WORDS (zero tolerance):
NEVER write: Premium, Original, Hit, TOP, N1, star ratings (★4.8 etc), Kafolat, Garantiya, Chegirma, Aksiya, Yandex, any marketplace name, call-to-action phrases.

📐 RULE 2 — TWO-ZONE LAYOUT:
ZONE A — PRODUCT ZONE (50% right or center): Product only. ZERO text overlaps product. Strict 30px text-free margin around product edges.
ZONE B — SPECS ZONE (50% left): Product name + technical specifications listed here only.

📋 RULE 3 — ACCURACY:
Show ONLY features from: {featuresList}. Technical, factual, no invented specs.
Format: specification name left | bold value right — technical table style.

🎨 DESIGN:
Background: Light neutral gray (#F5F5F5) or clean white with yellow (#FFCC00) accents.
Product name: Clean bold black/dark text at top of Zone B.
Key spec: Large yellow-highlighted number — most prominent element.
Feature layout: Technical specification rows — clean, information-dense, Yandex aesthetic.
Typography: Clear readable sans-serif. Data-first presentation.
Quality: 8K sharp, clinical precision.

🌍 LANGUAGE: {targetLang} only.
📐 Aspect Ratio: 3:4.",

            'instagram' => "INSTAGRAM AD CREATIVE — \"{productName}\".

⛔ RULE 1 — FORBIDDEN WORDS (zero tolerance):
NEVER write: Premium, Original, Hit, TOP, N1, star ratings, Kafolat, Garantiya, Chegirma, Aksiya, any marketplace name, 'Buy now', 'Order now', any call-to-action phrase.

📐 RULE 2 — LAYOUT (square format):
PRODUCT ZONE (center 55%): Product large and centered. NO text, NO sticker, NO badge touches or overlaps the product itself. Min 30px clear gap.
TEXT ZONES: Top strip + bottom strip + side margins only for text and badges.

📋 RULE 3 — ACCURACY:
Show ONLY features from: {featuresList}. Maximum 3-4 badges. Clean and punchy.
Do not invent specs or claims.

🎨 DESIGN:
Background: Vibrant gradient or dynamic lifestyle scene — eye-catching, scroll-stopping social media aesthetic.
Product name \"{productName}\": Bold headline in top text zone.
Feature badges: Floating pill stickers or story-sticker style — placed in text zones, NOT on the product.
Typography: Bold modern, high contrast, Instagram-native feel.
Quality: 8K sharp, saturated, viral creative quality.

🌍 LANGUAGE: {targetLang} only.
📐 Aspect Ratio: 1:1.",

            'minimal' => "MINIMALIST PRODUCT PRESENTATION — \"{productName}\".

⛔ RULE 1 — FORBIDDEN WORDS (zero tolerance):
NEVER write: Premium, Original, Hit, TOP, N1, star ratings, Kafolat, Garantiya, Chegirma, Aksiya, any marketplace name, any marketing cliché.

📐 RULE 2 — LAYOUT:
PRODUCT ZONE (center, large): Product prominent with generous white space around it. ZERO text overlaps product. 40px minimum text-free margin.
TEXT ZONES: Elegant text placement above, below, or to the sides — never on the product.

📋 RULE 3 — ACCURACY:
Show ONLY features from: {featuresList}. Maximum 4 features. Presented as minimal clean text lines or micro-labels.
No invented specs.

🎨 DESIGN:
Background: Pure white or soft neutral (warm gray, off-white, cream). No complex gradients.
Product: Studio-lit, perfect shadows, museum-quality rendering. Center stage.
Typography: Thin elegant serif or light sans-serif. Dark text on light. Refined hierarchy.
Features: Simple horizontal text lines or minimal tags — no flashy badges.
Overall: Apple Store / Muji / Aesop aesthetic — silent luxury.
Quality: 8K photorealistic, print-quality sharpness.

🌍 LANGUAGE: {targetLang} only.
📐 Aspect Ratio: 3:4.",

            'marketplace' => "UNIVERSAL MARKETPLACE INFOGRAPHIC — \"{productName}\".

⛔ RULE 1 — FORBIDDEN WORDS (zero tolerance, checked twice):
NEVER render ANY of these: Premium, Original, Hit, Xit, Yangi, NEW, TOP, N1, Best Seller, Bestseller, Reyting, star ratings (★4.8 etc), Kafolat, Garantiya, Chegirma, Aksiya, Bepul yetkazish, Uzum, Ozon, Wildberries, WB, Eng yaxshi, Tavsiya etamiz, 'Sotib oling', 'Xarid qiling', any call-to-action or trust buzzword.

📐 RULE 2 — TWO-ZONE LAYOUT (mandatory):
ZONE A — PRODUCT ZONE (right 50-55%): Product large and prominent. COMPLETELY TEXT-FREE. No badge within 30px of product edges.
ZONE B — INFO ZONE (left 45-50%): ALL text and badges placed here ONLY.

📋 RULE 3 — ACCURACY:
Display ONLY features from: {featuresList}. DO NOT invent specs, ratings, or percentages.
If empty — pick 3-4 physical specs from product image only.
Feature format: [icon] + [BOLD VALUE] + [short description small]

🎨 RULE 4 — DESIGN: Background color, textures, and visual style are determined ENTIRELY by the CATEGORY DESIGN TEMPLATE specified below — follow it precisely. Do NOT default to generic blue or plain gradient. Match the exact colors, mood, and composition described.
Product name \"{productName}\": Large bold at top of Zone B. Key spec: EXTRA LARGE 3x — most dominant. Typography: bold modern sans-serif. Quality: 8K, commercially publishable.

🌍 RULE 5 — LANGUAGE: ALL text in {targetLang}. 📐 Aspect Ratio: 3:4.",
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
            'infografika_suffix' => "\n\n🔒 FINAL MANDATORY CHECKS:\n1. PRODUCT FAITHFULNESS: Product in output = pixel-perfect copy of input image. Same shape, color, label, proportions.\n2. TEXT-FREE PRODUCT ZONE: No text, badge, or element touches/overlaps the product. Minimum 30px clear gap around product.\n3. FORBIDDEN WORDS: None of these on image: Premium, Original, Hit, TOP, N1, Best Seller, star ratings, Kafolat, Garantiya, Chegirma, Aksiya, marketplace names, call-to-action.\n4. ACCURACY: Only provided features shown. No invented specs.\n5. LANGUAGE: 100% in {targetLang}.\n6. QUALITY: {aspectRatio}, 8K resolution.",
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
    // Kod ichidagi defaults DOIM asosiy manba — DB faqat system va paket konfiglarini override qila oladi.
    // Bu stale/eski DB promptlarining sifatni buzishini oldini oladi.
    $defaults = getDefaultPrompts();
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT data FROM configs WHERE id = 'prompts'");
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row) {
            $saved = json_decode($row['data'], true);
            if (is_array($saved)) {
                // Faqat system va paket sozlamalarini DB dan olish (admin o'zgartira oladigan qism)
                // infografika, foto-tahrir promptlari DOIM kod ichidan keladi
                $merged = $defaults;
                if (isset($saved['system'])) {
                    $merged['system'] = array_replace($defaults['system'] ?? [], $saved['system']);
                }
                if (isset($saved['paket'])) {
                    $merged['paket'] = array_replace_recursive($defaults['paket'] ?? [], $saved['paket']);
                }
                return $merged;
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

    // Modellar: navbatma-navbat (round-robin) — Pro va Flash almashib ishlaydi
    $imageModels = [
        'gemini-3-pro-image-preview',      // 0 → toq so'rovlar (1, 3, 5...)
        'gemini-3.1-flash-image-preview',  // 1 → juft so'rovlar (2, 4, 6...)
    ];

    // Atomik counter — har so'rovda Pro/Flash navbatlashadi
    $counterFile = __DIR__ . '/../tmp/model_counter.txt';
    $fp = fopen($counterFile, 'c+');
    flock($fp, LOCK_EX);
    $counter = (int) fread($fp, 20);
    $selectedModel = $imageModels[$counter % count($imageModels)];
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, $counter + 1);
    flock($fp, LOCK_UN);
    fclose($fp);
    
    // Fallback model: agar tanlangan model fail bo'lsa, boshqasiga o'tish
    $fallbackModel = $imageModels[($counter + 1) % count($imageModels)];

    $url = "https://generativelanguage.googleapis.com/v1beta/models/$selectedModel:generateContent?key=$apiKey";

    $payload = json_encode([
        'contents' => [['parts' => $parts]],
        'generationConfig' => [
            'responseModalities' => ['IMAGE', 'TEXT'],
            'maxOutputTokens' => 8192,
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

        // HTTP 0 = ulanmadi (connection reset) — darhol fallback modelga o'tish
        if ($httpCode === 0 || $curlError) {
            if ($retryCount < $maxRetries) {
                $retryCount++;
                // Ishlamagan modeldan fallback modelga o'tish (Pro↔Flash)
                $selectedModel = $fallbackModel;
                $apiKey = $apiKeys[array_rand($apiKeys)];
                $url = "https://generativelanguage.googleapis.com/v1beta/models/$selectedModel:generateContent?key=$apiKey";
                $logMsg = date('[Y-m-d H:i:s] ') . "Connection error (HTTP 0 / cURL: $curlError) — switching to fallback: $selectedModel, retry #{$retryCount}\n";
                file_put_contents(__DIR__ . '/../tmp/gemini_error.log', $logMsg, FILE_APPEND);
                sleep(2);
                continue;
            }
            jsonResponse(['error' => 'Ulanish xatosi. Qayta urinib ko\'ring.'], 500);
        }

        $data = json_decode($response, true);
        
        if ($httpCode === 200) break;

        // Rate limit (429) yoki server xatosi — model + key rotatsiyasi bilan qayta urinish
        if (in_array($httpCode, [500, 503, 429]) && $retryCount < $maxRetries) {
            $retryCount++;
            
            // Modellar ichida navbatlash
            $nextModelIndex = $retryCount % count($imageModels);
            $selectedModel = $imageModels[$nextModelIndex];
            
            // API key rotatsiya (agar bir nechta bo'lsa)
            $apiKey = $apiKeys[array_rand($apiKeys)];
            $url = "https://generativelanguage.googleapis.com/v1beta/models/$selectedModel:generateContent?key=$apiKey";
            
            // 429 uchun — exponential backoff + jitter
            if ($httpCode === 429) {
                $baseDelay = min(pow(2, $retryCount), 20);
                $jitter = random_int(0, 2);
                $delay = $baseDelay + $jitter;
                $logMsg = date('[Y-m-d H:i:s] ') . "Rate limit 429 — retry #{$retryCount}, model={$selectedModel}, waiting {$delay}s\n";
                file_put_contents(__DIR__ . '/../tmp/gemini_error.log', $logMsg, FILE_APPEND);
                sleep($delay);
            } else {
                $delay = 2 + $retryCount;
                $logMsg = date('[Y-m-d H:i:s] ') . "Server error {$httpCode} — retry #{$retryCount}, model={$selectedModel}\n";
                file_put_contents(__DIR__ . '/../tmp/gemini_error.log', $logMsg, FILE_APPEND);
                sleep($delay);
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

    return ['imageBase64' => $imageBase64, 'mimeType' => $mimeType, 'model' => $selectedModel];
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

/**
 * Rasmni siqish — sifat yo'qotmasdan hajmini kamaytirish
 * PNG/JPEG/WEBP → WebP (85% sifat, 60-80% kichik)
 * GD kengaytmasi bo'lmasa asl faylni saqlaydi
 *
 * @param string $sourcePath  Asl rasm fayl yo'li
 * @param string $destPath    Siqilgan rasm saqlanadigan yo'l (.webp)
 * @param int    $quality     WebP sifati: 0-100 (default 85)
 * @param int    $maxWidth    Maksimal kenglik px (0 = cheklovsiz)
 * @return bool
 */
function compressImage(string $sourcePath, string $destPath, int $quality = 85, int $maxWidth = 1600): bool {
    if (!extension_loaded('gd') || !function_exists('imagewebp')) {
        return copy($sourcePath, $destPath);
    }

    // MIME aniqlash — extension asosida (mime_content_type() barcha serverlarda yo'q)
    $extMap = [
        'jpg'  => 'image/jpeg', 'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'webp' => 'image/webp',
        'gif'  => 'image/gif',
    ];
    $ext  = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
    $mime = $extMap[$ext] ?? null;

    // Extension yo'q bo'lsa — magic bytes orqali aniqlash (fileinfo siz)
    if (!$mime) {
        $fh = @fopen($sourcePath, 'rb');
        if ($fh) {
            $bytes = fread($fh, 12);
            fclose($fh);
            if (substr($bytes, 0, 8) === "\x89PNG\r\n\x1a\n")         $mime = 'image/png';
            elseif (substr($bytes, 0, 2) === "\xff\xd8")               $mime = 'image/jpeg';
            elseif (substr($bytes, 0, 4) === 'RIFF' &&
                    substr($bytes, 8, 4) === 'WEBP')                   $mime = 'image/webp';
            elseif (substr($bytes, 0, 6) === 'GIF87a' ||
                    substr($bytes, 0, 6) === 'GIF89a')                 $mime = 'image/gif';
        }
    }

    if (!$mime) return copy($sourcePath, $destPath); // mime aniqlanmasa — aslini ko'chiramiz

    $img = null;
    switch ($mime) {
        case 'image/jpeg': $img = @imagecreatefromjpeg($sourcePath); break;
        case 'image/png':  $img = @imagecreatefrompng($sourcePath);  break;
        case 'image/webp': $img = @imagecreatefromwebp($sourcePath); break;
        case 'image/gif':  $img = @imagecreatefromgif($sourcePath);  break;
        default:           return copy($sourcePath, $destPath);
    }

    if (!$img) return copy($sourcePath, $destPath);

    // Hajmni kamaytirish (proportional)
    $origW = imagesx($img);
    $origH = imagesy($img);
    if ($maxWidth > 0 && $origW > $maxWidth) {
        $newW = $maxWidth;
        $newH = (int)round($origH * $maxWidth / $origW);
        $resized = imagecreatetruecolor($newW, $newH);
        // Transparency saqlash
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagecopyresampled($resized, $img, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
        imagedestroy($img);
        $img = $resized;
    }

    $result = imagewebp($img, $destPath, $quality);
    imagedestroy($img);

    // Agar WebP kichikroq bo'lsa uni ishlatamiz, aks holda aslini ko'chiramiz
    if ($result && file_exists($destPath) && filesize($destPath) > 0) {
        return true;
    }
    return copy($sourcePath, $destPath);
}

function saveImage($base64, $mimeType, $prefix = 'img') {
    $dir = __DIR__ . '/../generated';
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    // mimeType dan extension aniqlash
    $mimeMap = [
        'image/png'  => 'png',
        'image/jpeg' => 'jpg',
        'image/jpg'  => 'jpg',
        'image/webp' => 'png', // WebP kelsa ham PNG sifatida saqlaymiz
        'image/gif'  => 'gif',
    ];
    $ext = $mimeMap[strtolower((string)$mimeType)] ?? 'png';

    $decoded = base64_decode($base64, true);
    if ($decoded === false || strlen($decoded) < 50) {
        error_log("saveImage: invalid base64 for prefix=$prefix");
        return null;
    }

    // To'g'ridan faylga yozish — hech qanday konvertatsiya yo'q
    $filename = $prefix . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
    $filepath = $dir . '/' . $filename;
    if (file_put_contents($filepath, $decoded) === false) {
        error_log("saveImage: failed to write file $filepath");
        return null;
    }
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
 * Foydalanuvchiga yuboriladigan xabardan texnik ma'lumotlarni olib tashlash
 * (🤖 Model: va 💰 Balans: qatorlari faqat admin kanalga ko'rinadi)
 */
function filterUserTelegramMsg($message) {
    // Har bir qatorni tekshirib, texnik qatorlarni olib tashlaymiz
    $lines = explode("\n", $message);
    $filtered = [];
    foreach ($lines as $line) {
        // 🤖 Model: va 💰 Balans: qatorlarini o'tkazib yuborish
        if (mb_strpos($line, '🤖 *Model:') !== false) continue;
        if (mb_strpos($line, '🤖 *AI Model:') !== false) continue;
        if (mb_strpos($line, '💰 *Balans:') !== false) continue;
        $filtered[] = $line;
    }
    $result = implode("\n", $filtered);

    // Foydalanuvchiga AI disclaimer qo'shish
    $disclaimer = "\n\n⚠️ _AI xato qilishi mumkin. Natijani qayta tekshiruvdan o'tkazing._";
    $result = rtrim($result) . $disclaimer;

    return $result;
}

/**
 * Telegramga xabar/rasm yuborish
 */
function sendToTelegram($message, $imagePath = null, $asDocument = true, $targetChatId = null) {
    $token = getenv('TELEGRAM_BOT_TOKEN');
    $chatId = $targetChatId ?: getenv('TELEGRAM_CHANNEL_ID');

    if (!$token || !$chatId) {
        error_log("Telegram Skip: Token/ID missing. BotToken: " . ($token ? 'OK' : 'MISSING') . ", ChatID: " . ($chatId ?: 'MISSING'));
        return false;
    }

    $message = (string)$message;

    // Fayl yo'lini aniqlash
    $realPath = null;
    if ($imagePath) {
        // Yangi yaratilgan fayllar uchun stat keshini tozalash
        clearstatcache(true);
        // Birinchi to'g'ridan yo'l quramiz (realpath() yangi faylni topa olmashi mumkin)
        $directPath = __DIR__ . '/../' . ltrim($imagePath, '/');
        if (file_exists($directPath) && filesize($directPath) > 0) {
            $realPath = $directPath;
        } else {
            $realPath = realpath($directPath) ?: null;
        }
        if (!$realPath || !file_exists($realPath) || filesize($realPath) === 0) {
            error_log("Telegram Send: File not found or empty: $imagePath | direct=$directPath");
            $imagePath = null;
            $realPath  = null;
        }
    }

    $mimeMap = [
        'jpg'  => 'image/jpeg', 'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
    ];

    $url  = "https://api.telegram.org/bot$token/";
    $data = ['chat_id' => $chatId, 'parse_mode' => 'Markdown'];

    if ($imagePath && $realPath) {
        $ext      = strtolower(pathinfo($realPath, PATHINFO_EXTENSION));
        $mime     = $mimeMap[$ext] ?? 'image/png';
        $curlFile = new CURLFile($realPath, $mime, basename($realPath));

        $data['caption'] = substr($message, 0, 1000);
        if ($asDocument) {
            $url .= "sendDocument";
            $data['document'] = $curlFile;
        } else {
            $url .= "sendPhoto";
            $data['photo'] = $curlFile;
        }
    } else {
        $url .= "sendMessage";
        $data['text'] = substr($message, 0, 4000);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Proxy va SSL sozlamalari (getTelegramCurlOpts ichida SSL_VERIFYPEER=false)
    foreach (getTelegramCurlOpts() as $opt => $val) curl_setopt($ch, $opt, $val);
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

    // Vaqtinchalik PNG faylni o'chirish
    if (!empty($tmpPng) && file_exists($tmpPng)) @unlink($tmpPng);

    // Foydalanuvchiga nusxasini yuborish (texnik ma'lumotlarsiz)
    if (!$targetChatId) {
        $u = getAuthUser();
        if ($u && !empty($u['telegram_id']) && $u['telegram_id'] != getenv('TELEGRAM_CHANNEL_ID')) {
            $userMsg = filterUserTelegramMsg($message);
            sendToTelegram($userMsg, $imagePath, $asDocument, $u['telegram_id']);
        }
    }

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

function sendMediaGroupToTelegram($message, $imagePaths = [], $asDocument = true, $targetChatId = null) {
    $token  = getenv('TELEGRAM_BOT_TOKEN');
    $chatId = $targetChatId ?: getenv('TELEGRAM_CHANNEL_ID');

    // tmp/ papkasini avtomatik yaratish
    $tmpDir   = __DIR__ . '/../tmp';
    if (!is_dir($tmpDir)) @mkdir($tmpDir, 0755, true);
    $debugLog = $tmpDir . '/telegram_debug.log';

    $logEntry  = date('[Y-m-d H:i:s] ') . "sendMediaGroupToTelegram called\n";
    $logEntry .= "  Token: "   . ($token  ? 'SET (' . strlen($token) . ' chars)' : 'MISSING') . "\n";
    $logEntry .= "  ChatID: "  . ($chatId ?: 'MISSING') . "\n";
    $logEntry .= "  Paths: "   . json_encode($imagePaths) . "\n";
    $logEntry .= "  __DIR__: " . __DIR__ . "\n";

    if (!$token || !$chatId || empty($imagePaths)) {
        $reason = !$token ? 'No token' : (!$chatId ? 'No chatId' : 'No imagePaths');
        $logEntry .= "  SKIPPED: $reason\n\n";
        @file_put_contents($debugLog, $logEntry, FILE_APPEND);
        error_log("sendMediaGroupToTelegram SKIPPED: $reason");
        return false;
    }

    $message = (string)$message;

    // Mavjud fayllarni aniqlash (yangi fayllar uchun stat keshini tozalash)
    clearstatcache(true);
    $validFiles = [];
    foreach ($imagePaths as $i => $path) {
        $directPath = __DIR__ . '/../' . ltrim($path, '/');
        // to'g'ridan yo'l (realpath() yangi faylni topa olmashi mumkin)
        $resolved = (file_exists($directPath) && filesize($directPath) > 0)
            ? $directPath
            : (realpath($directPath) ?: null);

        $logEntry .= "  File[$i]: path='$path' → resolved='" . ($resolved ?: 'FAILED') . "'";
        if ($resolved && file_exists($resolved) && filesize($resolved) > 0) {
            $logEntry .= " (" . filesize($resolved) . " bytes) ✅\n";
            $validFiles[] = $resolved;
        } else {
            $logEntry .= " ❌ NOT FOUND\n";
        }
    }

    if (empty($validFiles)) {
        $logEntry .= "  SKIPPED: No valid files\n\n";
        @file_put_contents($debugLog, $logEntry, FILE_APPEND);
        return false;
    }

    $curlOpts = getTelegramCurlOpts();

    // === 1 ta fayl → sendDocument / sendPhoto (ishonchliroq) ===
    if (count($validFiles) === 1) {
        $realPath  = $validFiles[0];
        $ext       = strtolower(pathinfo($realPath, PATHINFO_EXTENSION));
        $mimeMap   = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif'];
        $mime      = $mimeMap[$ext] ?? 'image/png';
        $endpoint  = $asDocument ? 'sendDocument' : 'sendPhoto';
        $fieldName = $asDocument ? 'document' : 'photo';
        $url       = "https://api.telegram.org/bot$token/$endpoint";

        $curlFile = new CURLFile($realPath, $mime, basename($realPath));
        $postData = [
            'chat_id'    => $chatId,
            'caption'    => substr($message, 0, 1000),
            'parse_mode' => 'Markdown',
            $fieldName   => $curlFile,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        foreach ($curlOpts as $opt => $val) curl_setopt($ch, $opt, $val);

        $res      = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        $logEntry .= "  [Single file] $endpoint API Response ($httpCode): " . substr($res, 0, 500) . "\n";
        if ($curlErr) $logEntry .= "  CURL Error: $curlErr\n";
        $logEntry .= "\n";
        @file_put_contents($debugLog, $logEntry, FILE_APPEND);

        if ($curlErr)   error_log("Telegram Single Send Curl Error: $curlErr");
        if ($httpCode !== 200) error_log("Telegram Single Send API Error ($httpCode): $res");

    } else {
        // === Ko'p fayl → sendMediaGroup ===
        $postData = ['chat_id' => $chatId];
        $media    = [];
        $mimeMap  = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif'];

        foreach ($validFiles as $idx => $realPath) {
            $ext  = strtolower(pathinfo($realPath, PATHINFO_EXTENSION));
            $mime = $mimeMap[$ext] ?? 'image/png';
            $key  = 'file_' . $idx;
            $postData[$key] = new CURLFile($realPath, $mime, basename($realPath));
            $media[] = [
                'type'       => $asDocument ? 'document' : 'photo',
                'media'      => "attach://$key",
                'caption'    => ($idx === 0) ? substr($message, 0, 1000) : '',
                'parse_mode' => 'Markdown',
            ];
        }

        $postData['media'] = json_encode($media, JSON_UNESCAPED_UNICODE);
        $url = "https://api.telegram.org/bot$token/sendMediaGroup";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        foreach ($curlOpts as $opt => $val) curl_setopt($ch, $opt, $val);

        $res      = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        $logEntry .= "  [MediaGroup] API Response ($httpCode): " . substr($res, 0, 500) . "\n";
        if ($curlErr) $logEntry .= "  CURL Error: $curlErr\n";

        // Agar MediaGroup ham muvaffaqiyatsiz bo'lsa — birinchi faylni alohida yuborish
        if ($httpCode !== 200) {
            $logEntry .= "  MediaGroup failed, falling back to single send...\n";
            @file_put_contents($debugLog, $logEntry, FILE_APPEND);
            return sendToTelegram($message, $imagePaths[0], $asDocument, $targetChatId);
        }
        $logEntry .= "\n";
        @file_put_contents($debugLog, $logEntry, FILE_APPEND);

        if ($curlErr)   error_log("Telegram MediaGroup Curl Error: $curlErr");
        if ($httpCode !== 200) error_log("Telegram MediaGroup API Error ($httpCode): $res");
    }

    // Foydalanuvchiga nusxasini yuborish (texnik ma'lumotlarsiz)
    if (!$targetChatId) {
        $u = getAuthUser();
        if ($u && !empty($u['telegram_id']) && $u['telegram_id'] != getenv('TELEGRAM_CHANNEL_ID')) {
            $userMsg = filterUserTelegramMsg($message);
            sendMediaGroupToTelegram($userMsg, $imagePaths, $asDocument, $u['telegram_id']);
        }
    }

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
