<?php
// ========== TEXNIK ISHLAR TEKSHIRUVI ==========
$_mFlag = __DIR__ . '/../data/maintenance.flag';
if (file_exists($_mFlag)) {
    $_mUri = $_SERVER['REQUEST_URI'] ?? '';
    if (strpos($_mUri, '/secret') !== 0 && !isset($_COOKIE['admin_bypass'])) {
        http_response_code(503);
        include __DIR__ . '/../pages/maintenance.php';
        exit;
    }
}
require_once __DIR__ . '/../api/config.php';
$currentPage = $_SERVER['REQUEST_URI'] ?? '/';
$currentPage = parse_url($currentPage, PHP_URL_PATH);
$currentPage = rtrim($currentPage, '/') ?: '/';
$botUsername = getenv('TELEGRAM_BOT_USERNAME') ?: 'tibaai_bot';
$googleClientId = getenv('GOOGLE_CLIENT_ID') ?: '';

// Fetch services for Instruments dropdown
try {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order ASC");
    $headerServices = $stmt->fetchAll();
} catch (Exception $e) {
    $headerServices = [];
}
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Tiba AI – Sun\'iy intellekt yordamida professional infografika' ?></title>
    <meta name="description" content="<?= $pageDescription ?? 'Tiba AI – Marketplace (Uzum, Wildberries) va Instagram uchun professional infografikalarni sun\'iy intellekt yordamida soniyalar ichida yarating.' ?>">
    <meta name="keywords" content="tiba ai, sun'iy intellekt infografika, uzum infografika, marketplace dizayn, ai rasm yaratish, uzbekistan ai, infografika yaratish bot, professional dizayn ai">
    <link rel="canonical" href="https://tibaai.uz<?= $currentPage ?>">
    
    <meta name="theme-color" content="#0a0a0f">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://tibaai.uz<?= $currentPage ?>">
    <meta property="og:title" content="<?= $pageTitle ?? 'Tiba AI – Professional AI Dizayn' ?>">
    <meta property="og:description" content="Marketplace va Instagram uchun professional infografikalarni AI yordamida yarating.">
    <meta property="og:image" content="https://tibaai.uz/generated/og-image.jpg">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://tibaai.uz<?= $currentPage ?>">
    <meta property="twitter:title" content="<?= $pageTitle ?? 'Tiba AI – Professional AI Dizayn' ?>">
    <meta property="twitter:description" content="Marketplace va Instagram uchun professional infografikalarni AI yordamida yarating.">
    <meta property="twitter:image" content="https://tibaai.uz/generated/og-image.jpg">

    <!-- Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Tiba AI",
      "url": "https://tibaai.uz",
      "logo": "https://tibaai.uz/assets/logo.png",
      "description": "Sun'iy intellekt yordamida professional infografika va dizayn yaratish platformasi.",
      "sameAs": [
        "https://t.me/tibaai_bot",
        "https://instagram.com/tibaai"
      ]
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "url": "https://tibaai.uz",
      "name": "Tiba AI",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://tibaai.uz/create?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>

    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Cdefs%3E%3ClinearGradient id='g' x1='0' y1='0' x2='1' y2='1'%3E%3Cstop offset='0%25' stop-color='%234f46e5'/%3E%3Cstop offset='100%25' stop-color='%237c3aed'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='32' height='32' rx='8' fill='url(%23g)'/%3E%3Ctext x='16' y='23' font-family='Inter,sans-serif' font-size='18' font-weight='900' fill='white' text-anchor='middle'%3ET%3C/text%3E%3C/svg%3E">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>var _tw=console.warn;console.warn=function(){if(arguments[0]&&String(arguments[0]).includes('tailwindcss.com'))return;_tw.apply(console,arguments)}</script>
    <script src="/assets/tailwind.js"></script>
    <script>
    console.warn=_tw;
    tailwind.config = {
        theme: { extend: { fontFamily: { sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'] } } }
    }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/assets/style.css">
    <!-- Theme: FOUC prevention (apply saved theme before paint) -->
    <script>
    (function(){
        var t = localStorage.getItem('tiba-theme');
        if (!t) t = window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', t);
    })();
    </script>
    <!-- Google Identity Services -->
    <?php if ($googleClientId && $googleClientId !== 'YOUR_GOOGLE_CLIENT_ID_HERE'): ?>
    <script src="https://accounts.google.com/gsi/client" async defer onload="if(typeof TibaAuth!=='undefined')TibaAuth.initGoogle()"></script>
    <?php endif; ?>
</head>
<body class="min-h-screen flex flex-col">

<!-- Navbar -->
<nav class="sticky top-0 z-50 glass-card border-t-0 border-x-0 rounded-none">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2.5 group flex-shrink-0">
                <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 transition-shadow">
                    <span class="text-white font-extrabold text-sm">T</span>
                </div>
                <span class="text-xl font-bold gradient-text">Tiba AI</span>
            </a>

            <div class="hidden md:flex items-center gap-1">
                <?php
                $navLinks = [
                    ['path' => '/', 'label' => 'Bosh sahifa'],
                    ['path' => '#', 'label' => '<i class="fa-solid fa-wand-magic-sparkles mr-1.5 text-indigo-400"></i> Yordamchi', 'dropdown' => true, 'patterns' => ['/create', '/infografika', '/infografika-paketi', '/foto-tahrir', '/noldan-yaratish', '/uslub-nusxalash', '/fashion-ai', '/fotosesiya-pro', '/kartochka-ai', '/video-ai', '/smart-matn', '/instrumentlar', '/stuv-kalkulyatori', '/sotuvlar-analitikasi', '/raqiblar-monitori', '/zoom-selling-ai', '/qqs-kalkulyatori', '/hisobotlar']],
                    ['path' => '/pricing', 'label' => 'Narxlar'],
                    ['path' => '/contact', 'label' => 'Aloqa'],
                    ['path' => '/kurslar', 'label' => 'Kurslar'],
                ];
                foreach ($navLinks as $link):
                    $isActive = ($currentPage === $link['path']);
                    
                    // Patterns orqali aktivlikni tekshirish
                    if (!$isActive && isset($link['patterns'])) {
                        foreach ($link['patterns'] as $p) { if ($currentPage === $p) { $isActive = true; break; } }
                    }

                    // Dropdown aktivligini tekshirish (faqat Yaratish uchun qoldi)
                    if (isset($link['dropdown']) && $link['dropdown']) {
                        foreach ($headerServices as $s) {
                            if ($currentPage === '/' . $s['slug']) { $isActive = true; break; }
                        }
                    }

                    if (isset($link['dropdown']) && $link['dropdown']): ?>
                    <div class="relative" id="yordamchi-wrapper">
                        <button type="button" onclick="toggleYordamchi()" class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 <?= $isActive ? 'text-white bg-white/10' : 'text-gray-400 hover:text-white hover:bg-white/5' ?> flex items-center gap-1.5">
                            <?= $link['label'] ?>
                            <i class="fa-solid fa-chevron-down text-[10px] opacity-50 transition-transform" id="yordamchi-chevron"></i>
                        </button>
                        <div id="yordamchi-dropdown" class="absolute left-0 top-full pt-2 w-[280px] hidden animate-fade-in-up z-[60]">
                            <div class="yordamchi-dropdown-panel p-3 border shadow-2xl rounded-2xl">
                                <a href="/create" class="flex items-center gap-3.5 p-3 rounded-2xl hover:bg-white/5 transition-all group/item border border-transparent hover:border-white/5">
                                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-xl shadow-lg ring-1 ring-white/10 group-hover/item:scale-110 transition-transform duration-300">
                                        <i class="fa-solid fa-wand-magic-sparkles text-white"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <span class="text-sm font-bold text-white group-hover/item:text-indigo-400 transition-colors">Infografika yaratish</span>
                                        <div class="text-[10px] text-gray-500 line-clamp-1">AI yordamida dizayn yarating</div>
                                    </div>
                                </a>
                                <a href="/instrumentlar" class="flex items-center gap-3.5 p-3 rounded-2xl hover:bg-white/5 transition-all group/item border border-transparent hover:border-white/5">
                                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-violet-600 to-fuchsia-600 flex items-center justify-center text-xl shadow-lg ring-1 ring-white/10 group-hover/item:scale-110 transition-transform duration-300">
                                        <i class="fa-solid fa-toolbox text-white"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <span class="text-sm font-bold text-white group-hover/item:text-violet-400 transition-colors">Instrumentlar</span>
                                        <div class="text-[10px] text-gray-500 line-clamp-1">Yordamchi vositalar</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <a href="<?= $link['path'] ?>" class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 <?= $isActive ? 'text-white bg-white/10' : 'text-gray-400 hover:text-white hover:bg-white/5' ?>"><?= $link['label'] ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="flex items-center gap-3">
                <!-- Guest -->
                <button id="nav-login-btn" onclick="TibaAuth.showModal()" class="hidden items-center gap-2 px-4 py-2 text-sm font-medium text-gray-400 hover:text-white rounded-xl border border-white/10 hover:border-white/20 hover:bg-white/5 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    <span class="hidden sm:inline">Kirish</span>
                </button>

                <!-- User Profile -->
                <div id="nav-user-profile" class="hidden relative">
                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <button id="nav-balance-btn" class="balance-btn flex items-center gap-1.5 px-3 py-1.5 rounded-xl border transition-all cursor-pointer">
                                <i class="fa-solid fa-coins text-sm"></i>
                                <span id="nav-user-balance" class="font-bold text-sm">0</span>
                                <i class="fa-solid fa-chevron-down text-[9px] ml-0.5"></i>
                            </button>
                            <div id="nav-balance-dropdown" class="hidden absolute right-0 top-full mt-2 w-52 yordamchi-dropdown-panel border border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-fade-in z-50">
                                <div class="px-4 py-3 border-b border-white/5 text-center">
                                    <div class="text-xs text-gray-500">Joriy balans</div>
                                    <div class="text-lg font-bold text-amber-400 mt-0.5"><i class="fa-solid fa-coins text-amber-500 mr-1"></i> <span id="dd-balance-value">0</span> <span class="text-xs font-normal text-gray-500">tanga</span></div>
                                </div>
                                <div class="py-1.5">
                                    <a href="/pricing" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-400 hover:text-white hover:bg-white/5 transition-colors">
                                        <i class="fa-solid fa-cart-plus text-emerald-400 w-4 text-center"></i> Tanga olish
                                    </a>
                                    <a href="/pricing#history" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-400 hover:text-white hover:bg-white/5 transition-colors">
                                        <i class="fa-solid fa-receipt text-blue-400 w-4 text-center"></i> To'lovlar tarixi
                                    </a>
                                </div>
                            </div>
                        </div>
                        <button id="nav-user-btn" class="hidden sm:flex items-center gap-2.5 px-3 py-1.5 rounded-xl hover:bg-white/5 transition-all group">
                            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-md">
                                <span id="nav-user-initial" class="text-white font-bold text-xs"></span>
                            </div>
                            <div class="hidden lg:block text-left">
                                <div id="nav-user-name" class="text-sm font-semibold text-white leading-tight"></div>
                                <div id="nav-user-email" class="text-[10px] text-gray-500 leading-tight"></div>
                            </div>
                        </button>
                    </div>
                    <div id="nav-user-dropdown" class="hidden absolute right-0 top-full mt-2 w-56 yordamchi-dropdown-panel border border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-fade-in z-50">
                        <div class="px-4 py-3 border-b border-white/5">
                            <div id="dd-user-name" class="text-sm font-semibold text-white"></div>
                            <div id="dd-user-email" class="text-xs text-gray-500 mt-0.5"></div>
                        </div>
                        <div class="py-1.5">
                            <a href="/create" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-400 hover:text-white hover:bg-white/5 transition-colors"><i class="fa-solid fa-wand-magic-sparkles text-indigo-400 w-4 text-center"></i> Yaratish</a>
                            <a href="/tarix" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-400 hover:text-white hover:bg-white/5 transition-colors"><i class="fa-solid fa-clock-rotate-left text-blue-400 w-4 text-center"></i> Tarix</a>
                        </div>
                        <div class="border-t border-white/5 py-1.5">
                            <button onclick="TibaAuth.logout()" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/5"><i class="fa-solid fa-right-from-bracket"></i> Chiqish</button>
                        </div>
                    </div>
                </div>

                <!-- Boshlash: faqat guest uchun ko'rinadi -->
                <a href="/create" id="nav-start-btn" class="hidden md:inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold text-white rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 hover:scale-105 transition-all duration-300">
                    <i class="fa-solid fa-bolt"></i> Boshlash
                </a>

                <!-- Hamburger -->
                <button id="mobile-menu-btn" onclick="toggleMobileMenu()" class="md:hidden w-10 h-10 flex items-center justify-center rounded-xl hover:bg-white/10 transition-colors">
                    <i class="fa-solid fa-bars text-lg text-gray-400" id="mobile-menu-icon"></i>
                </button>

                <!-- Theme Toggle (desktop) -->
                <button id="theme-toggle-btn" class="theme-toggle hidden md:flex" onclick="TibaTheme.toggle()" title="Rejimni o'zgartirish">
                    <i class="fa-solid fa-sun icon-sun"></i>
                    <i class="fa-solid fa-moon icon-moon"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- ===== MOBILE MENU ===== -->
    <div id="mobile-menu" class="md:hidden hidden">
        <div class="border-t border-white/10"></div>
        <div class="px-4 py-4 space-y-3 mobile-menu-inner">

            <!-- User Card (logged in) -->
            <div id="mobile-user-section" class="hidden">
                <div class="mob-user-card flex items-center gap-3 p-3 rounded-2xl border">
                    <div class="mob-icon-box-indigo w-10 h-10 rounded-xl flex items-center justify-center shadow-md">
                        <span id="mobile-user-initial" class="font-bold text-sm" style="color:#fff"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div id="mobile-user-name" class="text-sm font-bold truncate" style="color:var(--text-heading)"></div>
                        <div id="mobile-user-email" class="text-[11px] truncate" style="color:var(--text-muted)"></div>
                        <div class="balance-btn inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-bold mt-1 border">
                            <i class="fa-solid fa-coins text-[10px]"></i>
                            <span id="mobile-user-balance">0</span> tanga
                        </div>
                    </div>
                    <button onclick="TibaAuth.logout()" class="mob-logout-btn w-9 h-9 flex items-center justify-center rounded-xl transition-colors" title="Chiqish">
                        <i class="fa-solid fa-right-from-bracket text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Yordamchi: Infografika & Instrumentlar -->
            <div class="grid grid-cols-2 gap-2">
                <a href="/create" class="mob-card-infografika flex flex-col items-center gap-2 p-3.5 rounded-2xl border active:scale-[0.97] transition-all">
                    <div class="mob-icon-box-indigo w-10 h-10 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fa-solid fa-wand-magic-sparkles" style="color:#fff"></i>
                    </div>
                    <span class="mob-card-label-indigo text-xs font-bold">Infografika</span>
                </a>
                <a href="/instrumentlar" class="mob-card-instrumentlar flex flex-col items-center gap-2 p-3.5 rounded-2xl border active:scale-[0.97] transition-all">
                    <div class="mob-icon-box-violet w-10 h-10 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fa-solid fa-toolbox" style="color:#fff"></i>
                    </div>
                    <span class="mob-card-label-violet text-xs font-bold">Instrumentlar</span>
                </a>
            </div>

            <!-- Nav Links -->
            <div class="space-y-0.5">
                <a href="/" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors <?= $currentPage === '/' ? 'text-white bg-white/10' : 'text-gray-400 hover:text-white hover:bg-white/5' ?>">
                    <i class="fa-solid fa-home w-5 text-center text-indigo-400 text-xs"></i> Bosh sahifa
                </a>
                <a href="/pricing" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors <?= $currentPage === '/pricing' ? 'text-white bg-white/10' : 'text-gray-400 hover:text-white hover:bg-white/5' ?>">
                    <i class="fa-solid fa-tag w-5 text-center text-emerald-400 text-xs"></i> Narxlar
                </a>
                <a href="/contact" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors <?= $currentPage === '/contact' ? 'text-white bg-white/10' : 'text-gray-400 hover:text-white hover:bg-white/5' ?>">
                    <i class="fa-solid fa-envelope w-5 text-center text-blue-400 text-xs"></i> Aloqa
                </a>
                <a href="/kurslar" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors <?= $currentPage === '/kurslar' ? 'text-white bg-white/10' : 'text-gray-400 hover:text-white hover:bg-white/5' ?>">
                    <i class="fa-solid fa-graduation-cap w-5 text-center text-amber-400 text-xs"></i> Kurslar
                </a>
            </div>

            <!-- Theme + Guest -->
            <div class="pt-2 border-t border-white/10 flex items-center justify-between">
                <button onclick="TibaTheme.toggle()" class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-400 hover:text-white hover:bg-white/5 transition-colors">
                    <i class="fa-solid fa-circle-half-stroke w-5 text-center text-gray-500 text-xs"></i> Rejim
                </button>
                <div id="mobile-guest-section">
                    <button onclick="TibaAuth.showModal()" class="flex items-center gap-2 px-5 py-2 text-sm font-semibold text-white rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg shadow-indigo-500/25 active:scale-95 transition-all">
                        <i class="fa-solid fa-user text-xs"></i> Kirish
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- ========== AUTH MODAL ========== -->
<div id="auth-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/95 backdrop-blur-md" onclick="TibaAuth.hideModal()"></div>
    <div class="relative w-full max-w-sm animate-fade-in-up">
        <div class="glass-card p-8 border border-white/10 shadow-2xl shadow-indigo-500/10">
            <button onclick="TibaAuth.hideModal()" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:text-white hover:bg-white/10 transition-all">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <!-- Header -->
            <div class="text-center mb-6">
                <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-indigo-500/30">
                    <span class="text-white font-extrabold text-lg">T</span>
                </div>
                <h2 id="auth-title" class="text-xl font-extrabold text-white">Tizimga kirish</h2>
                <p id="auth-subtitle" class="text-sm text-gray-500 mt-1">Hisobingizga kiring</p>
            </div>

            <!-- Error -->
            <div id="auth-error" class="hidden bg-red-500/10 border border-red-500/20 text-red-400 p-3 rounded-xl text-xs text-center mb-4"></div>

            <!-- Step 1: Login / Register Form -->
            <div id="auth-step1">
                <form id="auth-form" onsubmit="TibaAuth.handleSubmit(event)" class="space-y-3">
                    <!-- Name (register only) -->
                    <div id="auth-name-wrap" class="hidden">
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">Ismingiz</label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="text" id="auth-name" placeholder="To'liq ismingiz" autocomplete="name"
                                class="w-full pl-10 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-indigo-500/50 focus:bg-white/[0.07] transition-all">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">Email</label>
                        <div class="relative">
                            <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="email" id="auth-email" placeholder="email@example.com" autocomplete="email"
                                class="w-full pl-10 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-indigo-500/50 focus:bg-white/[0.07] transition-all">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">Parol</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="password" id="auth-password" placeholder="Parolingiz" autocomplete="current-password"
                                class="w-full pl-10 pr-12 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-indigo-500/50 focus:bg-white/[0.07] transition-all">
                            <button type="button" onclick="TibaAuth.togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300 transition-colors p-1">
                                <i id="auth-eye-icon" class="fa-solid fa-eye text-sm"></i>
                            </button>
                        </div>
                        <p id="auth-pass-hint" class="hidden text-[11px] text-gray-600 mt-1.5">Kamida 8 belgi, harf va raqam</p>
                    </div>

                    <!-- Submit -->
                    <button type="submit" id="auth-submit-btn" class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-semibold text-sm transition-all duration-200 shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 active:scale-[0.98] mt-1">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span id="auth-submit-text">Kirish</span>
                    </button>
                </form>

                <!-- Toggle -->
                <div class="text-center mt-4">
                    <span id="auth-toggle-text" class="text-xs text-gray-500">Hisobingiz yo'qmi? </span>
                    <button type="button" onclick="TibaAuth.toggleMode()" id="auth-toggle-btn" class="text-xs text-indigo-400 font-semibold hover:text-indigo-300 transition-colors">Ro'yxatdan o'tish</button>
                </div>

                <!-- Divider -->
                <div class="flex items-center gap-3 my-4">
                    <div class="flex-1 h-px bg-white/10"></div>
                    <span class="text-xs text-gray-600 font-medium">yoki</span>
                    <div class="flex-1 h-px bg-white/10"></div>
                </div>

                <!-- Google Sign-In -->
                <div id="google-signin-btn" class="flex justify-center"></div>
            </div>

            <!-- Step 2: OTP Verification -->
            <div id="auth-step2" class="hidden">
                <div class="text-center mb-5">
                    <div class="w-16 h-16 bg-indigo-500/10 border border-indigo-500/20 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-envelope-open-text text-indigo-400 text-2xl"></i>
                    </div>
                    <p class="text-sm text-gray-400">Tasdiqlash kodi yuborildi:</p>
                    <p id="otp-email-display" class="text-sm text-white font-semibold mt-1"></p>
                </div>

                <form onsubmit="TibaAuth.verifyOtp(event)" class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">6 xonali kod</label>
                        <input type="text" id="auth-otp" maxlength="6" placeholder="000000" inputmode="numeric" autocomplete="one-time-code"
                            class="w-full text-center text-2xl font-bold tracking-[0.5em] py-4 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-700 focus:outline-none focus:border-indigo-500/50 focus:bg-white/[0.07] transition-all"
                            oninput="this.value = this.value.replace(/\D/g, '').slice(0, 6)">
                    </div>

                    <button type="submit" id="otp-submit-btn" class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-semibold text-sm transition-all shadow-lg shadow-indigo-500/20 active:scale-[0.98]">
                        <i class="fa-solid fa-check-circle"></i>
                        <span id="otp-submit-text">Tasdiqlash</span>
                    </button>
                </form>

                <div class="flex items-center justify-between mt-4">
                    <button type="button" onclick="TibaAuth.backToStep1()" class="text-xs text-gray-500 hover:text-gray-300 flex items-center gap-1 transition-colors">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i> Orqaga
                    </button>
                    <button type="button" onclick="TibaAuth.resendOtp()" id="otp-resend-btn" class="text-xs text-indigo-400 font-semibold hover:text-indigo-300 transition-colors disabled:opacity-30 disabled:pointer-events-none">
                        Qayta yuborish
                    </button>
                </div>

                <p class="text-[11px] text-gray-600 text-center mt-4">Kod 5 daqiqa ichida amal qiladi</p>
            </div>

            <p class="text-[11px] text-gray-600 text-center mt-5 leading-relaxed">
                Davom etish orqali siz <a href="#" class="text-indigo-400 hover:underline">Foydalanish shartlari</a>ga rozilik bildirasiz
            </p>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="auth-toast" class="fixed bottom-8 left-1/2 -translate-x-1/2 z-[200] hidden animate-fade-in-up">
    <div class="bg-gray-900/90 backdrop-blur-md text-white px-6 py-3 rounded-2xl shadow-2xl flex items-center gap-3 border border-white/10">
        <i id="auth-toast-icon" class="fa-solid fa-circle-exclamation text-red-400"></i>
        <span id="auth-toast-msg" class="text-sm font-medium"></span>
    </div>
</div>

<!-- ========== NO BALANCE MODAL ========== -->
<div id="no-balance-modal" class="hidden fixed inset-0 z-[150] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeNoBalance()"></div>
    <div class="relative w-full max-w-sm animate-fade-in-up">
        <div class="rounded-3xl border border-white/10 shadow-2xl overflow-hidden" style="background: linear-gradient(135deg, #0d0d15 0%, #131325 100%);">
            <!-- Animated top glow -->
            <div class="relative h-40 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-600/20 via-orange-600/10 to-red-600/20"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                    <div class="relative">
                        <div class="w-24 h-24 rounded-full bg-amber-500/10 border-2 border-amber-500/20 flex items-center justify-center nobal-pulse">
                            <i class="fa-solid fa-coins text-amber-400 text-4xl nobal-bounce"></i>
                        </div>
                        <div class="absolute -top-1 -right-1 w-7 h-7 rounded-full bg-red-500 border-2 border-[#131325] flex items-center justify-center nobal-shake">
                            <i class="fa-solid fa-exclamation text-white text-xs font-bold"></i>
                        </div>
                    </div>
                </div>
                <!-- Sparkle particles -->
                <div class="absolute top-6 left-8 w-1.5 h-1.5 bg-amber-400/40 rounded-full nobal-float1"></div>
                <div class="absolute top-12 right-12 w-1 h-1 bg-amber-300/30 rounded-full nobal-float2"></div>
                <div class="absolute bottom-8 left-16 w-1 h-1 bg-orange-400/30 rounded-full nobal-float3"></div>
                <div class="absolute bottom-10 right-8 w-1.5 h-1.5 bg-amber-500/20 rounded-full nobal-float1"></div>
            </div>

            <div class="px-6 pb-7 -mt-2">
                <h3 class="text-xl font-extrabold text-white text-center mb-1">Tanga yetarli emas!</h3>
                <p class="text-sm text-gray-400 text-center mb-5">Yaratish uchun tanga sotib oling</p>

                <!-- Balance info -->
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex-1 rounded-xl bg-red-500/5 border border-red-500/15 p-3 text-center">
                        <div class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Balans</div>
                        <div class="text-lg font-extrabold text-red-400" id="nobal-current">0</div>
                    </div>
                    <div class="text-gray-600"><i class="fa-solid fa-arrow-right"></i></div>
                    <div class="flex-1 rounded-xl bg-amber-500/5 border border-amber-500/15 p-3 text-center">
                        <div class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Kerak</div>
                        <div class="text-lg font-extrabold text-amber-400" id="nobal-required">5</div>
                    </div>
                </div>

                <!-- CTA Button -->
                <a href="/pricing" class="group relative flex items-center justify-center gap-2.5 w-full py-3.5 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white font-bold text-sm transition-all duration-300 shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 hover:scale-[1.02] active:scale-[0.98] overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                    <i class="fa-solid fa-cart-plus relative z-10"></i>
                    <span class="relative z-10">Tanga sotib olish</span>
                    <i class="fa-solid fa-arrow-right relative z-10 group-hover:translate-x-1 transition-transform"></i>
                </a>

                <button onclick="closeNoBalance()" class="w-full mt-3 py-2.5 rounded-xl text-xs text-gray-500 hover:text-gray-300 hover:bg-white/5 transition-all">
                    Yopish
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes nobal-bounce { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
@keyframes nobal-pulse { 0%,100%{transform:scale(1);opacity:1} 50%{transform:scale(1.05);opacity:0.9} }
@keyframes nobal-shake { 0%,100%{transform:rotate(0)} 20%{transform:rotate(12deg)} 40%{transform:rotate(-12deg)} 60%{transform:rotate(8deg)} 80%{transform:rotate(-4deg)} }
@keyframes nobal-float { 0%,100%{transform:translateY(0) scale(1);opacity:0.3} 50%{transform:translateY(-12px) scale(1.5);opacity:0.7} }
.nobal-bounce{animation:nobal-bounce 2s ease-in-out infinite}
.nobal-pulse{animation:nobal-pulse 2s ease-in-out infinite}
.nobal-shake{animation:nobal-shake 0.6s ease-in-out 0.3s}
.nobal-float1{animation:nobal-float 3s ease-in-out infinite}
.nobal-float2{animation:nobal-float 4s ease-in-out infinite 0.5s}
.nobal-float3{animation:nobal-float 3.5s ease-in-out infinite 1s}
</style>

<script>
function showNoBalance(cost, balance) {
    document.getElementById('nobal-current').textContent = balance ?? 0;
    document.getElementById('nobal-required').textContent = cost ?? '?';
    document.getElementById('no-balance-modal').classList.remove('hidden');
}
function closeNoBalance() {
    document.getElementById('no-balance-modal').classList.add('hidden');
}
</script>

<!-- ========== GLOBAL AUTH JS ========== -->
<script>
const GOOGLE_CLIENT_ID = '<?= $googleClientId ?>';

const TibaAuth = (() => {
    let currentUser = null;
    let authCallback = null;
    let isRegisterMode = false;
    let pendingRegData = null; // {name, email, password}
    let resendTimer = null;

    const $ = id => document.getElementById(id);

    // === MODAL ===
    function showModal(cb) {
        authCallback = cb || null;
        $('auth-modal').classList.remove('hidden');
        showStep1();
    }
    function hideModal() {
        $('auth-modal').classList.add('hidden');
        authCallback = null;
    }

    // === MODE TOGGLE ===
    function toggleMode() {
        isRegisterMode = !isRegisterMode;
        $('auth-error').classList.add('hidden');
        if (isRegisterMode) {
            $('auth-title').textContent = "Ro'yxatdan o'tish";
            $('auth-subtitle').textContent = "Yangi hisob yaratish";
            $('auth-name-wrap').classList.remove('hidden');
            $('auth-pass-hint').classList.remove('hidden');
            $('auth-submit-text').textContent = "Davom etish";
            $('auth-submit-btn').querySelector('i').className = 'fa-solid fa-arrow-right';
            $('auth-toggle-text').textContent = "Hisobingiz bormi? ";
            $('auth-toggle-btn').textContent = "Kirish";
            $('auth-password').setAttribute('autocomplete', 'new-password');
        } else {
            $('auth-title').textContent = "Tizimga kirish";
            $('auth-subtitle').textContent = "Hisobingizga kiring";
            $('auth-name-wrap').classList.add('hidden');
            $('auth-pass-hint').classList.add('hidden');
            $('auth-submit-text').textContent = "Kirish";
            $('auth-submit-btn').querySelector('i').className = 'fa-solid fa-right-to-bracket';
            $('auth-toggle-text').textContent = "Hisobingiz yo'qmi? ";
            $('auth-toggle-btn').textContent = "Ro'yxatdan o'tish";
            $('auth-password').setAttribute('autocomplete', 'current-password');
        }
    }

    function togglePassword() {
        const p = $('auth-password'); const icon = $('auth-eye-icon');
        if (p.type === 'password') { p.type = 'text'; icon.className = 'fa-solid fa-eye-slash text-sm'; }
        else { p.type = 'password'; icon.className = 'fa-solid fa-eye text-sm'; }
    }

    function showStep1() {
        $('auth-step1').classList.remove('hidden');
        $('auth-step2').classList.add('hidden');
        $('auth-error').classList.add('hidden');
    }

    function backToStep1() {
        showStep1();
        if (resendTimer) { clearInterval(resendTimer); resendTimer = null; }
    }

    function showError(msg) {
        const el = $('auth-error');
        el.textContent = msg;
        el.classList.remove('hidden');
    }

    function setLoading(btn, textEl, loading, text) {
        if (loading) {
            btn.disabled = true;
            btn.classList.add('opacity-70', 'pointer-events-none');
            textEl.textContent = 'Kutib turing...';
        } else {
            btn.disabled = false;
            btn.classList.remove('opacity-70', 'pointer-events-none');
            textEl.textContent = text;
        }
    }

    // === FORM SUBMIT ===
    async function handleSubmit(e) {
        e.preventDefault();
        $('auth-error').classList.add('hidden');

        const email = ($('auth-email').value || '').trim().toLowerCase();
        const password = $('auth-password').value;

        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showError("To'g'ri email kiriting"); return; }
        if (!password || password.length < 6) { showError("Parol kamida 6 belgi bo'lishi kerak"); return; }

        if (isRegisterMode) {
            const name = ($('auth-name').value || '').trim();
            if (!name || name.length < 2) { showError("Ismingizni kiriting"); return; }
            if (password.length < 8) { showError("Parol kamida 8 belgi bo'lishi kerak"); return; }
            if (!/[A-Za-z]/.test(password) || !/[0-9]/.test(password)) { showError("Parolda harf va raqam bo'lishi kerak"); return; }

            // OTP yuborish
            pendingRegData = { name, email, password };
            setLoading($('auth-submit-btn'), $('auth-submit-text'), true);
            try {
                const resp = await apiCall('send_otp', { email });
                if (resp.error) { showError(resp.error); return; }
                // Step 2 ga o'tish
                $('auth-step1').classList.add('hidden');
                $('auth-step2').classList.remove('hidden');
                $('otp-email-display').textContent = email;
                $('auth-otp').value = '';
                $('auth-otp').focus();
                $('auth-error').classList.add('hidden');
                startResendTimer();
            } catch (err) {
                showError('Xatolik yuz berdi');
            } finally {
                setLoading($('auth-submit-btn'), $('auth-submit-text'), false, "Davom etish");
            }
        } else {
            // Login
            setLoading($('auth-submit-btn'), $('auth-submit-text'), true);
            try {
                const resp = await apiCall('login', { email, password });
                if (resp.error) { showError(resp.error); return; }
                onAuthSuccess(resp);
            } catch (err) {
                showError('Xatolik yuz berdi');
            } finally {
                setLoading($('auth-submit-btn'), $('auth-submit-text'), false, "Kirish");
            }
        }
    }

    // === OTP VERIFY ===
    async function verifyOtp(e) {
        e.preventDefault();
        $('auth-error').classList.add('hidden');

        const code = ($('auth-otp').value || '').trim();
        if (!code || code.length !== 6) { showError("6 xonali kodni kiriting"); return; }
        if (!pendingRegData) { showError("Ma'lumotlar topilmadi. Qaytadan urinib ko'ring."); backToStep1(); return; }

        setLoading($('otp-submit-btn'), $('otp-submit-text'), true);
        try {
            const resp = await apiCall('register', {
                name: pendingRegData.name,
                email: pendingRegData.email,
                password: pendingRegData.password,
                otp_code: code,
            });
            if (resp.otp_expired) { showError(resp.error); backToStep1(); return; }
            if (resp.error) { showError(resp.error); return; }
            pendingRegData = null;
            onAuthSuccess(resp);
        } catch (err) {
            showError('Xatolik yuz berdi');
        } finally {
            setLoading($('otp-submit-btn'), $('otp-submit-text'), false, "Tasdiqlash");
        }
    }

    // === RESEND OTP ===
    function startResendTimer() {
        let sec = 120;
        const btn = $('otp-resend-btn');
        btn.disabled = true;
        btn.textContent = `Qayta yuborish (${sec}s)`;
        resendTimer = setInterval(() => {
            sec--;
            if (sec <= 0) {
                clearInterval(resendTimer); resendTimer = null;
                btn.disabled = false;
                btn.textContent = 'Qayta yuborish';
            } else {
                btn.textContent = `Qayta yuborish (${sec}s)`;
            }
        }, 1000);
    }

    async function resendOtp() {
        if (!pendingRegData) return;
        $('auth-error').classList.add('hidden');
        try {
            const resp = await apiCall('send_otp', { email: pendingRegData.email });
            if (resp.error) { showError(resp.error); return; }
            showToast('Yangi kod yuborildi!', 'success');
            startResendTimer();
        } catch (err) {
            showError('Xatolik yuz berdi');
        }
    }

    // === API ===
    async function apiCall(action, data = {}) {
        const resp = await fetch('/api/auth.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', ...(getToken() ? { 'X-User-Token': getToken() } : {}) },
            body: JSON.stringify({ action, ...data }),
        });
        return resp.json();
    }

    // === AUTH SUCCESS ===
    function onAuthSuccess(data) {
        if (!data.success || !data.user) return;
        currentUser = data.user;
        if (data.token) localStorage.setItem('auth_token', data.token);
        updateUI();
        hideModal();
        showToast(`Xush kelibsiz, ${data.user.name}!`, 'success');
        if (authCallback) { const cb = authCallback; authCallback = null; cb(); }
    }

    // === UI UPDATE ===
    function updateUI() {
        const loginBtn = $('nav-login-btn');
        const profile = $('nav-user-profile');
        const mobileUser = $('mobile-user-section');
        const mobileGuest = $('mobile-guest-section');

        if (currentUser) {
            loginBtn && (loginBtn.style.display = 'none');
            profile && (profile.classList.remove('hidden'));
            // Boshlash tugmasini yashirish
            const startBtn = $('nav-start-btn');
            if (startBtn) startBtn.style.display = 'none';
            const initial = (currentUser.name || '?')[0].toUpperCase();
            ['nav-user-initial', 'mobile-user-initial'].forEach(id => { const el = $(id); if (el) el.textContent = initial; });
            ['nav-user-name', 'dd-user-name', 'mobile-user-name'].forEach(id => { const el = $(id); if (el) el.textContent = currentUser.name; });
            ['nav-user-email', 'dd-user-email', 'mobile-user-email'].forEach(id => { const el = $(id); if (el) el.textContent = currentUser.email; });
            ['nav-user-balance', 'mobile-user-balance'].forEach(id => { const el = $(id); if (el) el.textContent = currentUser.balance ?? 0; });
            mobileUser && mobileUser.classList.remove('hidden');
            mobileGuest && mobileGuest.classList.add('hidden');
        } else {
            loginBtn && (loginBtn.style.display = 'flex');
            profile && (profile.classList.add('hidden'));
            // Boshlash tugmasini ko'rsatish
            const startBtn = $('nav-start-btn');
            if (startBtn) startBtn.style.display = '';
            mobileUser && mobileUser.classList.add('hidden');
            mobileGuest && mobileGuest.classList.remove('hidden');
        }
    }

    // === GOOGLE ===
    function initGoogle() {
        if (!GOOGLE_CLIENT_ID || GOOGLE_CLIENT_ID === 'YOUR_GOOGLE_CLIENT_ID_HERE' || typeof google === 'undefined') return;
        try {
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            const uxMode = isMobile ? 'redirect' : 'popup';
            
            console.log(`Initializing Google Login (Mode: ${uxMode})...`);
            
            const googleOptions = {
                client_id: GOOGLE_CLIENT_ID,
                callback: handleGoogleResponse,
                auto_select: false,
                ux_mode: uxMode,
                cancel_on_tap_outside: false,
                itp_support: true
            };
            
            if (uxMode === 'redirect') {
                // Redirect rejimida login_uri majburiy
                googleOptions.login_uri = window.location.origin + '/';
            }
            
            google.accounts.id.initialize(googleOptions);
            
            const el = $('google-signin-btn');
            if (el) {
                google.accounts.id.renderButton(el, { 
                    type: 'standard', 
                    theme: 'filled_black', 
                    size: 'large', 
                    text: 'continue_with', 
                    width: 300, 
                    shape: 'pill',
                    ux_mode: uxMode
                });
            }

            // Agar Google redirect orqali xabar yuborgan bo'lsa (PHP orqali ushlab olingan)
            <?php if (isset($_POST['credential'])): ?>
            console.log('Detected Google credential in POST (Redirect mode)');
            setTimeout(() => {
                handleGoogleResponse({ credential: '<?= addslashes($_POST['credential']) ?>' });
            }, 500);
            <?php endif; ?>

        } catch (e) { 
            console.error('Google Login Init Error:', e);
        }
    }

    async function handleGoogleResponse(response) {
        if (!response || !response.credential) return;
        try {
            console.log('Processing Google credential...');
            const resp = await apiCall('google_login', { 
                token: response.credential, 
                token_type: 'id_token' 
            });
            
            if (resp.error) { 
                console.error('Auth Error:', resp.error);
                showToast(resp.error, 'error'); 
                return; 
            }
            onAuthSuccess(resp);
        } catch (err) { 
            console.error('Network Error:', err);
            showToast('Google bilan kirishda texnik xatolik', 'error'); 
        }
    }

    // === SESSION CHECK ===
    async function checkSession() {
        const token = getToken();
        if (!token) { updateUI(); return; }
        try {
            const resp = await apiCall('check');
            if (resp.authenticated && resp.user) { currentUser = resp.user; }
            else { localStorage.removeItem('auth_token'); currentUser = null; }
        } catch { localStorage.removeItem('auth_token'); currentUser = null; }
        updateUI();
    }

    // === COMMON ===
    function getToken() { return localStorage.getItem('auth_token'); }

    async function logout() {
        try { await apiCall('logout'); } catch {}
        localStorage.removeItem('auth_token');
        currentUser = null;
        updateUI();
        showToast("Tizimdan chiqdingiz", "info");
    }

    function showToast(msg, type = 'error') {
        const toast = $('auth-toast');
        const icon = $('auth-toast-icon');
        const msgEl = $('auth-toast-msg');
        if (!toast) return;
        msgEl.textContent = msg;
        icon.className = type === 'success' ? 'fa-solid fa-circle-check text-emerald-400' : type === 'info' ? 'fa-solid fa-circle-info text-blue-400' : 'fa-solid fa-circle-exclamation text-red-400';
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 4000);
    }

    function requireAuth(callback) {
        if (currentUser) callback();
        else showModal(callback);
    }

    function updateBalance(newBalance) {
        if (currentUser) currentUser.balance = newBalance;
        ['nav-user-balance', 'mobile-user-balance'].forEach(id => {
            const el = $(id);
            if (el) {
                el.textContent = newBalance;
                // Animatsiya
                el.classList.add('animate-pulse');
                setTimeout(() => el.classList.remove('animate-pulse'), 1000);
            }
        });
    }

    // === INIT ===
    document.addEventListener('DOMContentLoaded', () => {
        initGoogle();
        checkSession();

        // Dropdown toggle — User profile
        const btn = $('nav-user-btn');
        const dd = $('nav-user-dropdown');
        // Dropdown toggle — Balance
        const balBtn = $('nav-balance-btn');
        const balDd = $('nav-balance-dropdown');

        if (btn && dd) {
            btn.addEventListener('click', () => {
                dd.classList.toggle('hidden');
                if (balDd) balDd.classList.add('hidden'); // Close balance dropdown
            });
        }
        if (balBtn && balDd) {
            balBtn.addEventListener('click', () => {
                balDd.classList.toggle('hidden');
                if (dd) dd.classList.add('hidden'); // Close user dropdown
                // Sync balance value
                const balEl = $('dd-balance-value');
                const navBal = $('nav-user-balance');
                if (balEl && navBal) balEl.textContent = navBal.textContent;
            });
        }
        document.addEventListener('click', (e) => {
            if (btn && dd && !btn.contains(e.target) && !dd.contains(e.target)) dd.classList.add('hidden');
            if (balBtn && balDd && !balBtn.contains(e.target) && !balDd.contains(e.target)) balDd.classList.add('hidden');
            // Yordamchi dropdown
            const yw = document.getElementById('yordamchi-wrapper');
            const yd = document.getElementById('yordamchi-dropdown');
            if (yw && yd && !yw.contains(e.target)) { yd.classList.add('hidden'); const ch = document.getElementById('yordamchi-chevron'); if(ch) ch.style.transform=''; }
        });
    });

    return { showModal, hideModal, toggleMode, togglePassword, handleSubmit, verifyOtp, resendOtp, backToStep1, logout, requireAuth, isLoggedIn: () => !!currentUser, getUser: () => currentUser, getToken, checkSession, updateBalance, initGoogle };
})();
</script>

<!-- ========== THEME TOGGLE JS ========== -->
<script>
const TibaTheme = (() => {
    function get() {
        return document.documentElement.getAttribute('data-theme') || 'dark';
    }
    function set(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('tiba-theme', theme);
    }
    function toggle() {
        set(get() === 'dark' ? 'light' : 'dark');
    }
    return { get, set, toggle };
})();
</script>

<!-- ========== YORDAMCHI DROPDOWN JS ========== -->
<script>
function toggleYordamchi() {
    const dd = document.getElementById('yordamchi-dropdown');
    const ch = document.getElementById('yordamchi-chevron');
    if (!dd) return;
    const isHidden = dd.classList.contains('hidden');
    dd.classList.toggle('hidden');
    if (ch) ch.style.transform = isHidden ? 'rotate(180deg)' : '';
    // Close other dropdowns
    const userDd = document.getElementById('nav-user-dropdown');
    const balDd = document.getElementById('nav-balance-dropdown');
    if (userDd) userDd.classList.add('hidden');
    if (balDd) balDd.classList.add('hidden');
}

function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    const icon = document.getElementById('mobile-menu-icon');
    if (!menu) return;
    const isHidden = menu.classList.contains('hidden');
    menu.classList.toggle('hidden');
    if (icon) {
        icon.className = isHidden 
            ? 'fa-solid fa-xmark text-lg text-white' 
            : 'fa-solid fa-bars text-lg text-gray-400';
    }
}
</script>

<main class="flex-1">
