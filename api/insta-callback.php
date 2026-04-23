<?php
require_once __DIR__ . '/config.php';

/**
 * Instagram OAuth Callback Handler
 * 
 * Bu sahifa Instagram'dan login qilib qaytgan foydalanuvchini qabul qiladi.
 * U yerdan 'code' keladi, biz uni Facebook API orqali 'token'ga almashtiramiz.
 */

$code = $_GET['code'] ?? null;
$state = $_GET['state'] ?? null;

if (!$code) {
    die("Xatolik: Instagramdan ruxsat kodi kelmadi.");
}

// 1. Foydalanuvchini aniqlaymiz (sessiya orqali)
$user = getAuthUser();
if (!$user) {
    die("Xatolik: Avtorizatsiyadan o'tilmagan.");
}

// 2. Haqiqiy token olish uchun so'rov yuborish (bu qism uchun Client Secret kerak)
// Hozirda biz Client Secret'siz faqat simulyatsiya qilamiz yoki 
// foydalanuvchiga muvaffaqiyatli xabarini ko'rsatamiz.

/*
// Haqiqiy hayotda bu qism ishlatiladi:
$token_url = "https://graph.facebook.com/v19.0/oauth/access_token?" . http_build_query([
    'client_id' => INSTA_APP_ID,
    'redirect_uri' => INSTA_REDIRECT_URI,
    'client_secret' => 'YOUR_APP_SECRET',
    'code' => $code
]);
// So'rov yuboriladi va olingan token bazaga saqlanadi
*/

// Hozirgi test versiyasi uchun:
// Biz shunchaki foydalanuvchini ulandi deb belgilaymiz (agar simulyatsiya bo'lsa)
try {
    $db = getDB();
    // Simulyatsiya uchun vaqtinchalik 'stub' ma'lumotlar saqlaymiz
    $stmt = $db->prepare("UPDATE users SET insta_account_id = ?, insta_access_token = ? WHERE id = ?");
    $stmt->execute(['178414' . time(), 'EAAC_STUB_' . bin2hex(random_bytes(10)), $user['id']]);
    
    // Asosiy sahifaga qaytaramiz
    header("Location: /insta-link?success=connected");
    exit;
} catch (Exception $e) {
    die("Bazaga yozishda xatolik: " . $e->getMessage());
}
