<?php
/**
 * Tiba AI — i18n (Internationalization) System
 * 
 * Tillar: uz (O'zbekcha), ru (Русский), en (English), zh (简体中文)
 * Standart til: uz
 * Saqlash: Cookie-based (lang=uz|ru|en|zh)
 */

// Qo'llab-quvvatlanadigan tillar
define('SUPPORTED_LANGS', ['uz', 'ru', 'en', 'zh']);
define('DEFAULT_LANG', 'uz');

// Til nomlari (o'z tilida)
define('LANG_NAMES', [
    'uz' => "O'zbekcha",
    'ru' => 'Русский',
    'en' => 'English',
    'zh' => '中文',
]);

// Til bayroqlari (emoji)
define('LANG_FLAGS', [
    'uz' => '🇺🇿',
    'ru' => '🇷🇺',
    'en' => '🇬🇧',
    'zh' => '🇨🇳',
]);

/**
 * Joriy tilni aniqlash
 * Ustuvorlik: GET ?lang= > Cookie > Default
 */
function getCurrentLang(): string {
    // 1. GET parametr orqali til almashtirish
    if (isset($_GET['lang']) && in_array($_GET['lang'], SUPPORTED_LANGS)) {
        $lang = $_GET['lang'];
        // Cookie ga saqlash (1 yil)
        setcookie('lang', $lang, time() + 365 * 24 * 3600, '/', '', false, false);
        return $lang;
    }
    
    // 2. Cookie dan o'qish
    if (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], SUPPORTED_LANGS)) {
        return $_COOKIE['lang'];
    }
    
    // 3. Standart til
    return DEFAULT_LANG;
}

/**
 * Til faylini yuklash
 */
function loadLangFile(string $lang): array {
    static $cache = [];
    
    if (isset($cache[$lang])) {
        return $cache[$lang];
    }
    
    $file = __DIR__ . '/' . $lang . '.json';
    if (!file_exists($file)) {
        $file = __DIR__ . '/' . DEFAULT_LANG . '.json';
    }
    
    $content = file_get_contents($file);
    $data = json_decode($content, true);
    
    if (!is_array($data)) {
        $data = [];
    }
    
    $cache[$lang] = $data;
    return $data;
}

/**
 * Tarjima funksiyasi
 * 
 * @param string $key Tarjima kaliti (masalan: 'nav.home')
 * @param array $params O'zgaruvchilar (masalan: ['count' => 5])
 * @return string Tarjima qilingan matn
 * 
 * Ishlatish: t('nav.home') yoki t('welcome', ['name' => 'Ali'])
 */
function t(string $key, array $params = []): string {
    global $_TIBA_LANG, $_TIBA_LANG_DATA;
    
    if (!isset($_TIBA_LANG_DATA)) {
        $_TIBA_LANG = getCurrentLang();
        $_TIBA_LANG_DATA = loadLangFile($_TIBA_LANG);
    }
    
    // Kalit mavjud bo'lmasa, kalitning o'zini qaytarish
    $text = $_TIBA_LANG_DATA[$key] ?? $key;
    
    // Parametrlarni almashtirish (:name → qiymat)
    foreach ($params as $k => $v) {
        $text = str_replace(':' . $k, (string)$v, $text);
    }
    
    return $text;
}

/**
 * Joriy til kodini olish
 */
function lang(): string {
    global $_TIBA_LANG;
    if (!isset($_TIBA_LANG)) {
        $_TIBA_LANG = getCurrentLang();
    }
    return $_TIBA_LANG;
}

/**
 * JS uchun tarjima ob'ektini tayyorlash
 * Faqat 'js.' prefiksli kalitlarni qaytaradi
 */
function getJsTranslations(): string {
    global $_TIBA_LANG_DATA;
    if (!isset($_TIBA_LANG_DATA)) {
        $lang = getCurrentLang();
        $_TIBA_LANG_DATA = loadLangFile($lang);
    }
    
    $jsData = [];
    foreach ($_TIBA_LANG_DATA as $key => $value) {
        if (strpos($key, 'js.') === 0) {
            // 'js.' prefiksini olib tashlash
            $jsKey = substr($key, 3);
            $jsData[$jsKey] = $value;
        }
    }
    
    return json_encode($jsData, JSON_UNESCAPED_UNICODE);
}

/**
 * DB kontenti uchun til ustunini olish
 * Masalan: dbCol('name') → 'name_ru' (agar ru tili bo'lsa) yoki 'name' (uz uchun)
 */
function dbCol(string $column): string {
    $lang = lang();
    if ($lang === DEFAULT_LANG) {
        return $column;
    }
    return $column . '_' . $lang;
}

/**
 * DB dan til bo'yicha qiymat olish (fallback bilan)
 * Agar tilga mos ustun bo'sh bo'lsa, standart tilga qaytadi
 */
function dbVal(array $row, string $column): string {
    $lang = lang();
    if ($lang !== DEFAULT_LANG) {
        $langCol = $column . '_' . $lang;
        if (!empty($row[$langCol])) {
            return $row[$langCol];
        }
    }
    return $row[$column] ?? '';
}
