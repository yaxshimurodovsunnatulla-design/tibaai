<?php
// ========== i18n TIZIMI ==========
require_once __DIR__ . '/../lang/i18n.php';
$_currentLang = lang();

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
<html lang="<?= $_currentLang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? t('meta.default_title') ?></title>
    <meta name="description" content="<?= $pageDescription ?? t('meta.default_desc') ?>">
    <meta name="keywords" content="tiba ai, sun'iy intellekt infografika, uzum infografika, marketplace dizayn, ai rasm yaratish, uzbekistan ai, infografika yaratish bot, professional dizayn ai">
    <link rel="canonical" href="https://tibaai.uz<?= $currentPage ?>">
    
    <meta name="theme-color" content="#0a0a0f">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://tibaai.uz<?= $currentPage ?>">
    <meta property="og:title" content="<?= $pageTitle ?? 'Tiba AI – Professional AI Dizayn' ?>">
    <meta property="og:description" content="<?= t('meta.og_desc') ?>">
    <meta property="og:image" content="https://tibaai.uz/generated/og-image.jpg">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://tibaai.uz<?= $currentPage ?>">
    <meta property="twitter:title" content="<?= $pageTitle ?? 'Tiba AI – Professional AI Dizayn' ?>">
    <meta property="twitter:description" content="<?= t('meta.og_desc') ?>">
    <meta property="twitter:image" content="https://tibaai.uz/generated/og-image.jpg">

    <!-- Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Tiba AI",
      "url": "https://tibaai.uz",
      "logo": "https://tibaai.uz/assets/logo.png",
      "description": "<?= t('meta.schema_desc') ?>",
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
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        darkMode: ['selector', '[data-theme="dark"]'],
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
<script>
window.LANG = <?= getJsTranslations() ?>;
window.CURRENT_LANG = '<?= $_currentLang ?>';
function _t(key) { return (window.LANG && window.LANG[key]) ? window.LANG[key] : key; }
function toggleLangDropdown() {
    const dd = document.getElementById('lang-dropdown');
    if (dd) dd.classList.toggle('hidden');
}
document.addEventListener('click', function(e) {
    const w = document.getElementById('lang-switcher-wrapper');
    const dd = document.getElementById('lang-dropdown');
    if (w && dd && !w.contains(e.target)) dd.classList.add('hidden');
});
</script>
</head>
<body class="min-h-screen">

<!-- ========== SIDEBAR ========== -->
<aside id="sidebar" class="sidebar-nav fixed top-0 left-0 bottom-0 w-[260px] z-50 flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
    <div class="flex flex-col h-full overflow-hidden" style="background:var(--sidebar-bg);border-right:1px solid var(--border-color);">
        <!-- Logo -->
        <div class="flex items-center gap-2.5 px-6 h-16 flex-shrink-0 border-b" style="border-color:var(--border-color);">
            <a href="/" class="flex items-center gap-2.5 group">
                <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 transition-shadow">
                    <span class="text-white font-extrabold text-sm">T</span>
                </div>
                <span class="text-xl font-bold gradient-text">Tiba AI</span>
            </a>
        </div>
        <!-- Nav Links -->
        <nav class="flex-1 px-3 py-4 overflow-y-auto sidebar-scroll">
            <div class="px-3 mb-3">
                <span class="text-[10px] font-semibold uppercase tracking-widest" style="color:var(--text-muted);"><?= t('nav.menu') ?></span>
            </div>
            <?php
            $sidebarLinks = [
                ['path' => '/',              'label' => t('nav.home'),          'icon' => 'fa-home text-blue-400'],
                ['path' => '/create',        'label' => t('nav.create'), 'icon' => 'fa-wand-magic-sparkles text-indigo-400'],
                ['path' => '/instrumentlar', 'label' => t('nav.instruments'),        'icon' => 'fa-toolbox text-violet-400'],
                ['path' => '/analitika', 'label' => t('nav.analytics'), 'icon' => 'fa-chart-line text-emerald-400'],
                ['path' => '/pricing',       'label' => t('nav.pricing'),              'icon' => 'fa-tag text-emerald-400'],
                ['path' => '/kurslar',       'label' => t('nav.courses'),              'icon' => 'fa-graduation-cap text-amber-400'],
            ];
            foreach ($sidebarLinks as $link):
                $isActive = ($currentPage === $link['path']);
            ?>
                <a href="<?= $link['path'] ?>" class="sidebar-link mb-1 <?= $isActive ? 'active' : '' ?>">
                    <i class="fa-solid <?= $link['icon'] ?> sidebar-link-icon"></i>
                    <span><?= $link['label'] ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
        <!-- Bottom section -->
        <div class="flex-shrink-0 px-3 py-4 space-y-2" style="border-top:1px solid var(--border-color);">
            <a href="/create" id="nav-start-btn" class="sidebar-cta"><?= t('nav.start') ?> <i class="fa-solid fa-bolt"></i></a>
            <button id="nav-login-btn" onclick="TibaAuth.showModal()" class="hidden sidebar-link w-full">
                <i class="fa-solid fa-right-to-bracket sidebar-link-icon text-indigo-400"></i><?= t('nav.login') ?>
            </button>
            <div id="nav-user-profile" class="hidden space-y-2">
                <div class="sidebar-user-card">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-md flex-shrink-0">
                        <span id="nav-user-initial" class="text-white font-bold text-xs"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div id="nav-user-name" class="text-sm font-semibold truncate" style="color:var(--text-heading)"></div>
                        <div id="nav-user-email" class="text-[10px] truncate" style="color:var(--text-muted)"></div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <a href="/pricing" class="sidebar-badge flex-1">
                        <i class="fa-solid fa-coins text-amber-400 text-[10px]"></i>
                        <span id="nav-user-balance" class="font-bold text-xs">0</span>
                        <span class="text-[10px] opacity-60"><?= t('nav.coins') ?></span>
                    </a>
                    <a href="/tarix" class="sidebar-action-sm" title="<?= t('sidebar.history') ?>"><i class="fa-solid fa-clock-rotate-left text-blue-400"></i></a>
                    <button onclick="TibaAuth.logout()" class="sidebar-action-sm" title="<?= t('sidebar.logout') ?>"><i class="fa-solid fa-right-from-bracket text-red-400"></i></button>
                </div>
            </div>
            <!-- Til tanlash -->
            <div class="sidebar-link w-full relative" id="lang-switcher-wrapper">
                <button onclick="toggleLangDropdown()" class="sidebar-link w-full" type="button">
                    <span class="sidebar-link-icon text-base"><?= LANG_FLAGS[$_currentLang] ?></span>
                    <span><?= LANG_NAMES[$_currentLang] ?></span>
                    <i class="fa-solid fa-chevron-down text-[10px] opacity-50 ml-auto"></i>
                </button>
                <div id="lang-dropdown" class="hidden absolute bottom-full left-0 right-0 mb-1 rounded-xl overflow-hidden shadow-2xl" style="background:var(--dropdown-bg);border:1px solid var(--border-color);z-index:100;">
                    <?php foreach (SUPPORTED_LANGS as $lc): if ($lc === $_currentLang) continue; ?>
                    <a href="?lang=<?= $lc ?>" class="flex items-center gap-2.5 px-3 py-2.5 text-sm transition-colors" style="color:var(--text-secondary);" onmouseover="this.style.background='var(--glass-bg-hover)'" onmouseout="this.style.background='transparent'">
                        <span class="text-base"><?= LANG_FLAGS[$lc] ?></span>
                        <span><?= LANG_NAMES[$lc] ?></span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <button id="theme-toggle-btn" class="sidebar-link w-full" onclick="TibaTheme.toggle()" title="<?= t('sidebar.theme_toggle') ?>">
                <i class="fa-solid fa-circle-half-stroke sidebar-link-icon text-gray-500"></i>
                <span><?= t('sidebar.theme') ?></span>
                <div class="ml-auto flex"><i class="fa-solid fa-sun icon-sun text-amber-400 text-xs"></i><i class="fa-solid fa-moon icon-moon text-indigo-400 text-xs"></i></div>
            </button>
        </div>
    </div>
</aside>
<!-- Mobile Top Bar -->
<div id="mobile-topbar" class="lg:hidden fixed top-0 left-0 right-0 z-40 glass-card border-t-0 border-x-0 rounded-none">
    <div class="flex items-center justify-between h-14 px-4">
        <button onclick="toggleSidebar()" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-white/10 transition-colors">
            <i class="fa-solid fa-bars text-lg text-gray-400" id="mobile-menu-icon"></i>
        </button>
        <a href="/" class="flex items-center gap-2 group">
            <div class="w-7 h-7 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-[10px]">T</span>
            </div>
            <span class="text-lg font-bold gradient-text">Tiba AI</span>
        </a>
        <div class="w-10"></div>
    </div>
</div>
<!-- Sidebar Overlay -->
<div id="sidebar-overlay" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden" onclick="toggleSidebar()"></div>
<!-- Hidden JS compat elements -->
<div class="hidden"><span id="dd-user-name"></span><span id="dd-user-email"></span><span id="dd-balance-value"></span><span id="mobile-user-initial"></span><span id="mobile-user-name"></span><span id="mobile-user-email"></span><span id="mobile-user-balance">0</span><div id="mobile-user-section"></div><div id="mobile-guest-section"></div><div id="nav-balance-dropdown"></div><div id="nav-user-dropdown"></div></div>


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
                <h2 id="auth-title" class="text-xl font-extrabold text-white"><?= t('auth.login_title') ?></h2>
                <p id="auth-subtitle" class="text-sm text-gray-500 mt-1"><?= t('auth.login_subtitle') ?></p>
            </div>

            <!-- Error -->
            <div id="auth-error" class="hidden bg-red-500/10 border border-red-500/20 text-red-400 p-3 rounded-xl text-xs text-center mb-4"></div>

            <!-- Step 1: Login / Register Form -->
            <div id="auth-step1">
                <form id="auth-form" onsubmit="TibaAuth.handleSubmit(event)" class="space-y-3">
                    <!-- Name (register only) -->
                    <div id="auth-name-wrap" class="hidden">
                        <label class="block text-xs font-medium text-gray-400 mb-1.5"><?= t('auth.name_label') ?></label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="text" id="auth-name" placeholder="<?= t('auth.name_placeholder') ?>" autocomplete="name"
                                class="w-full pl-10 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-indigo-500/50 focus:bg-white/[0.07] transition-all">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5"><?= t('auth.email_label') ?></label>
                        <div class="relative">
                            <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="email" id="auth-email" placeholder="email@example.com" autocomplete="email"
                                class="w-full pl-10 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-indigo-500/50 focus:bg-white/[0.07] transition-all">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5"><?= t('auth.password_label') ?></label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="password" id="auth-password" placeholder="<?= t('auth.password_placeholder') ?>" autocomplete="current-password"
                                class="w-full pl-10 pr-12 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-indigo-500/50 focus:bg-white/[0.07] transition-all">
                            <button type="button" onclick="TibaAuth.togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300 transition-colors p-1">
                                <i id="auth-eye-icon" class="fa-solid fa-eye text-sm"></i>
                            </button>
                        </div>
                        <p id="auth-pass-hint" class="hidden text-[11px] text-gray-600 mt-1.5"><?= t('auth.password_hint') ?></p>
                    </div>

                    <!-- Submit -->
                    <button type="submit" id="auth-submit-btn" class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-semibold text-sm transition-all duration-200 shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 active:scale-[0.98] mt-1">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span id="auth-submit-text"><?= t('auth.login_btn') ?></span>
                    </button>
                </form>

                <!-- Toggle -->
                <div class="text-center mt-4">
                    <span id="auth-toggle-text" class="text-xs text-gray-500"><?= t('auth.no_account') ?> </span>
                    <button type="button" onclick="TibaAuth.toggleMode()" id="auth-toggle-btn" class="text-xs text-indigo-400 font-semibold hover:text-indigo-300 transition-colors"><?= t('auth.register_title') ?></button>
                </div>

                <!-- Divider -->
                <div class="flex items-center gap-3 my-4">
                    <div class="flex-1 h-px bg-white/10"></div>
                    <span class="text-xs text-gray-600 font-medium"><?= t('auth.or') ?></span>
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
                    <p class="text-sm text-gray-400"><?= t('auth.otp_sent') ?></p>
                    <p id="otp-email-display" class="text-sm text-white font-semibold mt-1"></p>
                </div>

                <form onsubmit="TibaAuth.verifyOtp(event)" class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5"><?= t('auth.otp_label') ?></label>
                        <input type="text" id="auth-otp" maxlength="6" placeholder="000000" inputmode="numeric" autocomplete="one-time-code"
                            class="w-full text-center text-2xl font-bold tracking-[0.5em] py-4 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-700 focus:outline-none focus:border-indigo-500/50 focus:bg-white/[0.07] transition-all"
                            oninput="this.value = this.value.replace(/\D/g, '').slice(0, 6)">
                    </div>

                    <button type="submit" id="otp-submit-btn" class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-semibold text-sm transition-all shadow-lg shadow-indigo-500/20 active:scale-[0.98]">
                        <i class="fa-solid fa-check-circle"></i>
                        <span id="otp-submit-text"><?= t('auth.otp_verify') ?></span>
                    </button>
                </form>

                <div class="flex items-center justify-between mt-4">
                    <button type="button" onclick="TibaAuth.backToStep1()" class="text-xs text-gray-500 hover:text-gray-300 flex items-center gap-1 transition-colors">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i> <?= t('common.back') ?>
                    </button>
                    <button type="button" onclick="TibaAuth.resendOtp()" id="otp-resend-btn" class="text-xs text-indigo-400 font-semibold hover:text-indigo-300 transition-colors disabled:opacity-30 disabled:pointer-events-none">
                        <?= t('auth.otp_resend') ?>
                    </button>
                </div>

                <p class="text-[11px] text-gray-600 text-center mt-4"><?= t('auth.otp_expires') ?></p>
            </div>

            <p class="text-[11px] text-gray-600 text-center mt-5 leading-relaxed">
                <?= t('auth.terms') ?>
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
                <h3 class="text-xl font-extrabold text-white text-center mb-1"><?= t('nobal.title') ?></h3>
                <p class="text-sm text-gray-400 text-center mb-5"><?= t('nobal.subtitle') ?></p>

                <!-- Balance info -->
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex-1 rounded-xl bg-red-500/5 border border-red-500/15 p-3 text-center">
                        <div class="text-[10px] text-gray-500 uppercase tracking-wider mb-1"><?= t('nobal.balance') ?></div>
                        <div class="text-lg font-extrabold text-red-400" id="nobal-current">0</div>
                    </div>
                    <div class="text-gray-600"><i class="fa-solid fa-arrow-right"></i></div>
                    <div class="flex-1 rounded-xl bg-amber-500/5 border border-amber-500/15 p-3 text-center">
                        <div class="text-[10px] text-gray-500 uppercase tracking-wider mb-1"><?= t('nobal.required') ?></div>
                        <div class="text-lg font-extrabold text-amber-400" id="nobal-required">5</div>
                    </div>
                </div>

                <!-- CTA Button -->
                <a href="/pricing" class="group relative flex items-center justify-center gap-2.5 w-full py-3.5 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white font-bold text-sm transition-all duration-300 shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 hover:scale-[1.02] active:scale-[0.98] overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                    <i class="fa-solid fa-cart-plus relative z-10"></i>
                    <span class="relative z-10"><?= t('nobal.buy_btn') ?></span>
                    <i class="fa-solid fa-arrow-right relative z-10 group-hover:translate-x-1 transition-transform"></i>
                </a>

                <button onclick="closeNoBalance()" class="w-full mt-3 py-2.5 rounded-xl text-xs text-gray-500 hover:text-gray-300 hover:bg-white/5 transition-all">
                    <?= t('common.close') ?>
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
            $('auth-title').textContent = _t('auth_register_title');
            $('auth-subtitle').textContent = _t('auth_register_subtitle');
            $('auth-name-wrap').classList.remove('hidden');
            $('auth-pass-hint').classList.remove('hidden');
            $('auth-submit-text').textContent = _t('auth_continue_btn');
            $('auth-submit-btn').querySelector('i').className = 'fa-solid fa-arrow-right';
            $('auth-toggle-text').textContent = _t('auth_has_account');
            $('auth-toggle-btn').textContent = _t('auth_login_btn');
            $('auth-password').setAttribute('autocomplete', 'new-password');
        } else {
            $('auth-title').textContent = _t('auth_login_title');
            $('auth-subtitle').textContent = _t('auth_login_subtitle');
            $('auth-name-wrap').classList.add('hidden');
            $('auth-pass-hint').classList.add('hidden');
            $('auth-submit-text').textContent = _t('auth_login_btn');
            $('auth-submit-btn').querySelector('i').className = 'fa-solid fa-right-to-bracket';
            $('auth-toggle-text').textContent = _t('auth_no_account');
            $('auth-toggle-btn').textContent = _t('auth_register_title');
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
            textEl.textContent = _t('auth_waiting');
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

        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showError(_t('auth_err_email')); return; }
        if (!password || password.length < 6) { showError(_t('auth_err_pass_short')); return; }

        if (isRegisterMode) {
            const name = ($('auth-name').value || '').trim();
            if (!name || name.length < 2) { showError(_t('auth_err_name')); return; }
            if (password.length < 8) { showError(_t('auth_err_pass_8')); return; }
            if (!/[A-Za-z]/.test(password) || !/[0-9]/.test(password)) { showError(_t('auth_err_pass_format')); return; }

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
                showError(_t('auth_err_generic'));
            } finally {
                setLoading($('auth-submit-btn'), $('auth-submit-text'), false, _t('auth_continue_btn'));
            }
        } else {
            // Login
            setLoading($('auth-submit-btn'), $('auth-submit-text'), true);
            try {
                const resp = await apiCall('login', { email, password });
                if (resp.error) { showError(resp.error); return; }
                onAuthSuccess(resp);
            } catch (err) {
                showError(_t('auth_err_generic'));
            } finally {
                setLoading($('auth-submit-btn'), $('auth-submit-text'), false, _t('auth_login_btn'));
            }
        }
    }

    // === OTP VERIFY ===
    async function verifyOtp(e) {
        e.preventDefault();
        $('auth-error').classList.add('hidden');

        const code = ($('auth-otp').value || '').trim();
        if (!code || code.length !== 6) { showError(_t('auth_err_otp')); return; }
        if (!pendingRegData) { showError(_t('auth_err_data')); backToStep1(); return; }

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
            showError(_t('auth_err_generic'));
        } finally {
            setLoading($('otp-submit-btn'), $('otp-submit-text'), false, "Tasdiqlash");
        }
    }

    // === RESEND OTP ===
    function startResendTimer() {
        let sec = 120;
        const btn = $('otp-resend-btn');
        btn.disabled = true;
        btn.textContent = `${_t("auth_otp_resend")} (${sec}s)`;
        resendTimer = setInterval(() => {
            sec--;
            if (sec <= 0) {
                clearInterval(resendTimer); resendTimer = null;
                btn.disabled = false;
                btn.textContent = _t('auth_otp_resend');
            } else {
                btn.textContent = `${_t("auth_otp_resend")} (${sec}s)`;
            }
        }, 1000);
    }

    async function resendOtp() {
        if (!pendingRegData) return;
        $('auth-error').classList.add('hidden');
        try {
            const resp = await apiCall('send_otp', { email: pendingRegData.email });
            if (resp.error) { showError(resp.error); return; }
            showToast(_t('auth_otp_new_sent'), 'success');
            startResendTimer();
        } catch (err) {
            showError(_t('auth_err_generic'));
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
        showToast(`${_t("auth_welcome")}, ${data.user.name}!`, 'success');
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
            // Telegram holat badge'ini yangilash
            updateTgBadge(currentUser.telegram_id);
        } else {
            // Foydalanuvchi chiqsa badge'ni yashirish
            updateTgBadge(null, true);
            loginBtn && (loginBtn.style.display = 'flex');
            profile && (profile.classList.add('hidden'));
            // Boshlash tugmasini ko'rsatish
            const startBtn = $('nav-start-btn');
            if (startBtn) startBtn.style.display = '';
            mobileUser && mobileUser.classList.add('hidden');
            mobileGuest && mobileGuest.classList.remove('hidden');
        }
    }

    // === TELEGRAM HOLAT BADGE ===
    function updateTgBadge(telegramId, forceHide) {
        const statusBadge = document.getElementById('tg-status-badge');
        const connectedBadge = document.getElementById('tg-connected-badge');
        const connectBadge = document.getElementById('tg-connect-badge');
        const tgBanner = document.getElementById('tg-bind-banner');

        if (forceHide) {
            if (statusBadge) statusBadge.classList.add('hidden');
            if (tgBanner) tgBanner.classList.add('hidden');
            return;
        }

        // statusBadge optional — banner har doim ishlaydi
        if (statusBadge) {
            statusBadge.classList.remove('hidden');
            if (telegramId) {
                if (connectedBadge) { connectedBadge.classList.remove('hidden'); connectedBadge.style.display = 'flex'; }
                if (connectBadge) { connectBadge.classList.add('hidden'); connectBadge.style.display = 'none'; }
            } else {
                if (connectedBadge) { connectedBadge.classList.add('hidden'); connectedBadge.style.display = 'none'; }
                if (connectBadge) { connectBadge.classList.remove('hidden'); connectBadge.style.display = 'flex'; }
            }
        }

        // Banner: Telegram ulanmagan bo'lsa ko'rsatish
        if (tgBanner) {
            if (telegramId) {
                tgBanner.classList.add('hidden');
            } else {
                tgBanner.classList.remove('hidden');
            }
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
            showToast(_t('auth_google_error'), 'error'); 
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
        showToast(_t('auth_logged_out'), "info");
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

    return { showModal, hideModal, toggleMode, togglePassword, handleSubmit, verifyOtp, resendOtp, backToStep1, logout, requireAuth, isLoggedIn: () => !!currentUser, getUser: () => currentUser, getToken, checkSession, updateBalance, initGoogle, updateTgBadge };
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

// ========== MOBILE SIDEBAR TOGGLE ==========
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const icon = document.getElementById('mobile-menu-icon');
    if (!sidebar) return;

    const isOpen = !sidebar.classList.contains('-translate-x-full');
    if (isOpen) {
        sidebar.classList.add('-translate-x-full');
        if (overlay) overlay.classList.add('hidden');
        if (icon) icon.className = 'fa-solid fa-bars text-lg text-gray-400';
        document.body.style.overflow = '';
    } else {
        sidebar.classList.remove('-translate-x-full');
        if (overlay) overlay.classList.remove('hidden');
        if (icon) icon.className = 'fa-solid fa-xmark text-lg text-white';
        document.body.style.overflow = 'hidden';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Sidebar havolasi bosilganda mobilda yopilsin (anchor/SPA holatlar uchun)
    document.querySelectorAll('#sidebar a[href]').forEach(a => {
        a.addEventListener('click', () => {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth < 1024 && sidebar && !sidebar.classList.contains('-translate-x-full')) {
                toggleSidebar();
            }
        });
    });
    // Escape bilan yopish
    document.addEventListener('keydown', (e) => {
        const sidebar = document.getElementById('sidebar');
        if (e.key === 'Escape' && sidebar && window.innerWidth < 1024 && !sidebar.classList.contains('-translate-x-full')) {
            toggleSidebar();
        }
    });
    // Desktopga o'tilganda holatni tiklash
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const icon = document.getElementById('mobile-menu-icon');
            if (sidebar) sidebar.classList.add('-translate-x-full');
            if (overlay) overlay.classList.add('hidden');
            if (icon) icon.className = 'fa-solid fa-bars text-lg text-gray-400';
            document.body.style.overflow = '';
        }
    });
});

// ========== GLOBAL UTILS ==========
function showToast(msg, type = 'success') {
    const toast = document.getElementById('status-toast');
    if (!toast) return;
    const toastDiv = toast.querySelector('div');
    const toastMsg = document.getElementById('toast-msg');
    const toastIcon = document.getElementById('toast-icon');
    
    if (toastMsg) toastMsg.innerText = msg;
    if (toastIcon) toastIcon.innerText = type === 'error' ? '❌' : '✅';
    
    if (toastDiv) {
        toastDiv.className = type === 'error' 
            ? 'bg-red-600 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center gap-3 border border-white/10 backdrop-blur-md'
            : 'bg-indigo-600 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center gap-3 border border-white/10 backdrop-blur-md';
    }
    
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 4000);
}
</script>

<!-- ========== TG BIND MODAL ========== -->
<div id="tg-bind-modal" class="hidden fixed inset-0 z-[110] flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-xl" style="z-index:0;" onclick="closeTgBindModal()"></div>

    <!-- Wrapper: X tugma + card birgalikda -->
    <div class="relative w-full sm:max-w-[400px]" style="z-index:2;">

        <!-- X close button — card tashqarida, overflow:hidden ta'sir qilmaydi -->
        <button onclick="closeTgBindModal()"
            style="position:absolute;top:-14px;right:-14px;z-index:30;width:36px;height:36px;display:flex;align-items:center;justify-content:center;border-radius:50%;background:rgba(20,20,35,0.95);border:1.5px solid rgba(255,255,255,0.18);color:rgba(255,255,255,0.7);cursor:pointer;transition:all .2s;box-shadow:0 4px 16px rgba(0,0,0,0.5);"
            onmouseover="this.style.background='rgba(239,68,68,0.9)';this.style.borderColor='rgba(239,68,68,0.5)';this.style.color='#fff'"
            onmouseout="this.style.background='rgba(20,20,35,0.95)';this.style.borderColor='rgba(255,255,255,0.18)';this.style.color='rgba(255,255,255,0.7)'">
            <i class="fa-solid fa-xmark" style="font-size:13px;"></i>
        </button>

        <div class="tg-modal-card relative w-full overflow-hidden"
            style="background:linear-gradient(160deg,#0f1117 0%,#080b13 100%);border:1px solid rgba(255,255,255,0.07);border-radius:28px;box-shadow:0 40px 100px rgba(0,0,0,0.8),0 0 0 1px rgba(41,167,225,0.08),inset 0 1px 0 rgba(255,255,255,0.05);">

            <!-- Ambient glow -->
            <div style="position:absolute;top:-60px;left:50%;transform:translateX(-50%);width:280px;height:140px;background:radial-gradient(ellipse,rgba(41,167,225,0.15) 0%,transparent 70%);pointer-events:none;z-index:0;"></div>
            <div style="position:absolute;bottom:-40px;right:-40px;width:180px;height:180px;background:radial-gradient(circle,rgba(99,102,241,0.07) 0%,transparent 70%);pointer-events:none;z-index:0;"></div>

        <!-- Top section: icon + title -->
        <div class="relative z-10 px-7 pt-7 pb-0 text-center">
            <!-- Big TG icon with glow ring -->
            <div class="relative inline-flex mb-4">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background:linear-gradient(135deg,rgba(41,167,225,0.2),rgba(29,139,196,0.1));border:1px solid rgba(41,167,225,0.3);box-shadow:0 0 32px rgba(41,167,225,0.15),inset 0 1px 0 rgba(255,255,255,0.1);">
                    <i class="fa-brands fa-telegram text-3xl" style="color:#29A7E1;filter:drop-shadow(0 0 8px rgba(41,167,225,0.5));"></i>
                </div>
                <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full flex items-center justify-center" style="background:linear-gradient(135deg,#10b981,#059669);border:2px solid #0f1117;box-shadow:0 0 12px rgba(16,185,129,0.5);">
                    <i class="fa-solid fa-link text-[8px] text-white"></i>
                </div>
            </div>
            <h3 class="text-lg font-bold text-white mb-1"><?= t('tg.bind_title') ?></h3>
            <p class="text-xs text-gray-500 mb-5"><?= t('tg.bind_desc') ?></p>
        </div>

        <!-- Progress bar steps -->
        <div class="relative z-10 px-7 mb-5">
            <div class="flex items-center gap-0">
                <div class="flex items-center gap-2">
                    <div id="tg-step-dot-1" class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-bold transition-all duration-300" style="background:linear-gradient(135deg,rgba(41,167,225,0.3),rgba(41,167,225,0.15));border:1.5px solid #29A7E1;color:#29A7E1;box-shadow:0 0 12px rgba(41,167,225,0.3);">1</div>
                    <span id="tg-step-lbl-1" class="text-[10px] font-semibold transition-colors duration-300" style="color:#29A7E1;"><?= t('tg.step1_label') ?></span>
                </div>
                <div class="flex-1 mx-3 h-px" style="background:linear-gradient(90deg,rgba(41,167,225,0.4),rgba(255,255,255,0.06));"></div>
                <div class="flex items-center gap-2">
                    <span id="tg-step-lbl-2" class="text-[10px] font-semibold transition-colors duration-300" style="color:rgba(255,255,255,0.25);"><?= t('tg.step2_label') ?></span>
                    <div id="tg-step-dot-2" class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-bold transition-all duration-300" style="background:rgba(255,255,255,0.04);border:1.5px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.25);">2</div>
                </div>
            </div>
        </div>

        <!-- STEP 1 -->
        <div id="tg-modal-step1" class="relative z-10 px-7 pb-7">
            <!-- Phone input -->
            <div class="mb-4">
                <label class="flex items-center gap-1.5 text-[10px] font-semibold uppercase tracking-widest mb-2" style="color:rgba(255,255,255,0.35);">
                    <?= t('tg.phone_label') ?> <span style="color:rgba(255,255,255,0.18);font-weight:400;text-transform:none;letter-spacing:0;"><?= t('tg.phone_optional') ?></span>
                </label>
                <div class="flex items-stretch rounded-2xl overflow-hidden transition-all duration-200" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);" id="tg-phone-wrap">
                    <div class="flex items-center px-3.5 gap-1.5 flex-shrink-0" style="border-right:1px solid rgba(255,255,255,0.07);">
                        <span class="text-sm font-bold font-mono" style="color:#29A7E1;">+998</span>
                    </div>
                    <input type="tel" id="tg-bind-phone" maxlength="12" placeholder="90 123 45 67" inputmode="numeric"
                        class="flex-1 bg-transparent px-3 py-3 text-sm text-white placeholder-gray-600 outline-none font-mono"
                        oninput="formatTgPhone(this)"
                        onfocus="document.getElementById('tg-phone-wrap').style.borderColor='rgba(41,167,225,0.45)'"
                        onblur="document.getElementById('tg-phone-wrap').style.borderColor='rgba(255,255,255,0.08)'">
                </div>
            </div>

            <!-- Instruction steps -->
            <div class="rounded-2xl p-4 mb-5 space-y-2.5" style="background:rgba(41,167,225,0.05);border:1px solid rgba(41,167,225,0.1);">
                <div class="flex items-start gap-3">
                    <span class="w-5 h-5 rounded-lg flex-shrink-0 flex items-center justify-center text-[9px] font-black" style="background:rgba(41,167,225,0.2);color:#29A7E1;margin-top:1px;">1</span>
                    <p class="text-[11px] leading-relaxed" style="color:rgba(255,255,255,0.5);"><?= t('tg.instruction1') ?> <span class="font-semibold text-white">@<?= htmlspecialchars($botUsername) ?></span></p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-5 h-5 rounded-lg flex-shrink-0 flex items-center justify-center text-[9px] font-black" style="background:rgba(41,167,225,0.2);color:#29A7E1;margin-top:1px;">2</span>
                    <p class="text-[11px] leading-relaxed" style="color:rgba(255,255,255,0.5);"><?= t('tg.instruction2') ?></p>
                </div>
            </div>

            <!-- CTA button -->
            <a href="https://t.me/<?= htmlspecialchars($botUsername) ?>" target="_blank"
                onclick="setTimeout(()=>tgGoToStep2(),1000)"
                class="group relative w-full flex items-center justify-center gap-2.5 py-3.5 rounded-2xl text-sm font-bold overflow-hidden transition-all duration-300 mb-3"
                style="background:linear-gradient(135deg,#1a7db8,#29A7E1);color:#fff;box-shadow:0 4px 24px rgba(41,167,225,0.3),inset 0 1px 0 rgba(255,255,255,0.2);"
                onmouseover="this.style.boxShadow='0 8px 32px rgba(41,167,225,0.45),inset 0 1px 0 rgba(255,255,255,0.2)';this.style.transform='translateY(-1px)'"
                onmouseout="this.style.boxShadow='0 4px 24px rgba(41,167,225,0.3),inset 0 1px 0 rgba(255,255,255,0.2)';this.style.transform=''">
                <div class="absolute inset-0 bg-white/10 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700 skew-x-12"></div>
                <i class="fa-brands fa-telegram text-lg relative z-10"></i>
                <span class="relative z-10"><?= t('tg.go_to_bot') ?></span>
                <i class="fa-solid fa-arrow-up-right-from-square text-xs opacity-70 relative z-10"></i>
            </a>
            <button onclick="tgGoToStep2()" class="w-full py-2.5 rounded-xl text-xs transition-all duration-200" style="color:rgba(255,255,255,0.3);" onmouseover="this.style.background='rgba(255,255,255,0.05)';this.style.color='rgba(255,255,255,0.6)'" onmouseout="this.style.background='';this.style.color='rgba(255,255,255,0.3)'">
                <?= t('tg.got_code') ?> <i class="fa-solid fa-arrow-right text-[10px] ml-1"></i>
            </button>
        </div>

        <!-- STEP 2 -->
        <div id="tg-modal-step2" class="hidden relative z-10 px-7 pb-7">
            <p class="text-xs text-center mb-4" style="color:rgba(255,255,255,0.4);"><?= t('tg.enter_code') ?></p>

            <!-- 6 separate OTP boxes -->
            <div class="flex items-center justify-center gap-2 mb-2" id="tg-otp-boxes">
                <?php for($i=0;$i<6;$i++): ?>
                <input type="text" maxlength="1" inputmode="numeric"
                    class="tg-otp-box w-10 h-12 text-center text-lg font-black font-mono rounded-xl outline-none transition-all duration-200"
                    style="background:rgba(255,255,255,0.05);border:1.5px solid rgba(255,255,255,0.1);color:#fff;"
                    oninput="tgOtpInput(this,<?= $i ?>)"
                    onkeydown="tgOtpKey(event,<?= $i ?>)"
                    onfocus="this.style.borderColor='rgba(41,167,225,0.6)';this.style.background='rgba(41,167,225,0.08)';this.style.boxShadow='0 0 0 3px rgba(41,167,225,0.12)'"
                    onblur="this.style.borderColor=this.value?'rgba(41,167,225,0.4)':'rgba(255,255,255,0.1)';this.style.background='rgba(255,255,255,0.05)';this.style.boxShadow=''">
                <?php endfor; ?>
            </div>
            <!-- Hidden real OTP value -->
            <input type="hidden" id="tg-bind-otp">
            <p class="text-[10px] text-center mb-5" style="color:rgba(255,255,255,0.2);"><?= t('tg.code_expires') ?></p>

            <button onclick="submitTgBind()" id="tg-bind-submit-btn"
                class="group relative w-full py-3.5 rounded-2xl text-sm font-bold overflow-hidden transition-all duration-300 mb-3"
                style="background:linear-gradient(135deg,#1a7db8,#29A7E1);color:#fff;box-shadow:0 4px 24px rgba(41,167,225,0.3),inset 0 1px 0 rgba(255,255,255,0.2);"
                onmouseover="this.style.boxShadow='0 8px 32px rgba(41,167,225,0.45),inset 0 1px 0 rgba(255,255,255,0.2)';this.style.transform='translateY(-1px)'"
                onmouseout="this.style.boxShadow='0 4px 24px rgba(41,167,225,0.3),inset 0 1px 0 rgba(255,255,255,0.2)';this.style.transform=''">
                <div class="absolute inset-0 bg-white/10 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700 skew-x-12"></div>
                <i class="fa-solid fa-check-circle mr-2 relative z-10"></i>
                <span class="relative z-10"><?= t('tg.verify_btn') ?></span>
            </button>
            <button onclick="tgGoToStep1()" class="w-full py-2.5 rounded-xl text-xs transition-all duration-200" style="color:rgba(255,255,255,0.3);" onmouseover="this.style.background='rgba(255,255,255,0.05)';this.style.color='rgba(255,255,255,0.6)'" onmouseout="this.style.background='';this.style.color='rgba(255,255,255,0.3)'">
                <i class="fa-solid fa-arrow-left text-[10px] mr-1"></i> <?= t('common.back') ?>
            </button>
        </div>
        </div>
    </div>
</div>

<script>
// ===== OTP BOX LOGIC =====
function tgOtpInput(el, idx) {
    el.value = el.value.replace(/\D/g, '').slice(0,1);
    // Update hidden input
    const boxes = document.querySelectorAll('.tg-otp-box');
    let val = '';
    boxes.forEach(b => val += b.value);
    document.getElementById('tg-bind-otp').value = val;
    // Auto-advance
    if (el.value && idx < 5) boxes[idx + 1].focus();
}
function tgOtpKey(e, idx) {
    const boxes = document.querySelectorAll('.tg-otp-box');
    if (e.key === 'Backspace' && !boxes[idx].value && idx > 0) boxes[idx - 1].focus();
    if (e.key === 'ArrowLeft' && idx > 0) boxes[idx - 1].focus();
    if (e.key === 'ArrowRight' && idx < 5) boxes[idx + 1].focus();
}

function openTgBindModal() {
    document.getElementById('tg-bind-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    tgGoToStep1();
    setTimeout(() => { const ph = document.getElementById('tg-bind-phone'); if(ph) ph.focus(); }, 300);
}
function closeTgBindModal() {
    document.getElementById('tg-bind-modal').classList.add('hidden');
    document.body.style.overflow = '';
}

function tgGoToStep1() {
    document.getElementById('tg-modal-step1').classList.remove('hidden');
    document.getElementById('tg-modal-step2').classList.add('hidden');
    const d1=document.getElementById('tg-step-dot-1'), d2=document.getElementById('tg-step-dot-2');
    const l1=document.getElementById('tg-step-lbl-1'), l2=document.getElementById('tg-step-lbl-2');
    d1.style.cssText='width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;transition:all .3s;background:linear-gradient(135deg,rgba(41,167,225,0.3),rgba(41,167,225,0.15));border:1.5px solid #29A7E1;color:#29A7E1;box-shadow:0 0 12px rgba(41,167,225,0.3);';
    d2.style.cssText='width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;transition:all .3s;background:rgba(255,255,255,0.04);border:1.5px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.25);';
    if(l1) l1.style.color='#29A7E1';
    if(l2) l2.style.color='rgba(255,255,255,0.25)';
}
function tgGoToStep2() {
    document.getElementById('tg-modal-step1').classList.add('hidden');
    document.getElementById('tg-modal-step2').classList.remove('hidden');
    const d1=document.getElementById('tg-step-dot-1'), d2=document.getElementById('tg-step-dot-2');
    const l1=document.getElementById('tg-step-lbl-1'), l2=document.getElementById('tg-step-lbl-2');
    d1.style.cssText='width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;transition:all .3s;background:rgba(16,185,129,0.15);border:1.5px solid rgba(16,185,129,0.5);color:#10b981;';
    d2.style.cssText='width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;transition:all .3s;background:linear-gradient(135deg,rgba(41,167,225,0.3),rgba(41,167,225,0.15));border:1.5px solid #29A7E1;color:#29A7E1;box-shadow:0 0 12px rgba(41,167,225,0.3);';
    if(l1) l1.style.color='rgba(255,255,255,0.3)';
    if(l2) l2.style.color='#29A7E1';
    // clear & focus first box
    setTimeout(() => {
        const boxes = document.querySelectorAll('.tg-otp-box');
        boxes.forEach(b => { b.value=''; b.style.borderColor='rgba(255,255,255,0.1)'; b.style.background='rgba(255,255,255,0.05)'; b.style.boxShadow=''; });
        document.getElementById('tg-bind-otp').value = '';
        if(boxes[0]) boxes[0].focus();
    }, 100);
}

function formatTgPhone(input) {
    let v = input.value.replace(/\D/g,'').slice(0,9);
    let out = '';
    if(v.length>0) out=v.slice(0,2);
    if(v.length>2) out+=' '+v.slice(2,5);
    if(v.length>5) out+=' '+v.slice(5,7);
    if(v.length>7) out+=' '+v.slice(7,9);
    input.value=out;
}

async function submitTgBind() {
    const btn = document.getElementById('tg-bind-submit-btn');
    const otp = document.getElementById('tg-bind-otp').value.replace(/\D/g,'').trim();
    const phoneRaw = document.getElementById('tg-bind-phone').value.replace(/\D/g,'').trim();
    const phone = phoneRaw ? '+998'+phoneRaw : '';

    if (otp.length !== 6) {
        // Shake OTP boxes
        const boxes = document.querySelectorAll('.tg-otp-box');
        boxes.forEach(b => { b.style.borderColor='rgba(239,68,68,0.6)'; b.style.boxShadow='0 0 0 3px rgba(239,68,68,0.15)'; });
        setTimeout(() => boxes.forEach(b => { b.style.borderColor='rgba(255,255,255,0.1)'; b.style.boxShadow=''; }), 1200);
        const wrap = document.getElementById('tg-otp-boxes');
        if(wrap){ wrap.classList.add('tg-otp-shake'); setTimeout(()=>wrap.classList.remove('tg-otp-shake'),400); }
        return showToast(_t('tg_err_otp'), "error");
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i>Tekshirilmoqda...';

    try {
        const token = TibaAuth.getToken();
        const res = await fetch('/api/auth.php', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-User-Token':token},
            body: JSON.stringify({action:'link-telegram-otp', otp_code:otp, phone:phone})
        });
        const data = await res.json();
        if (data.error) throw new Error(data.error);

        // Muvaffaqiyat: yashil holat
        const boxes = document.querySelectorAll('.tg-otp-box');
        boxes.forEach(b => { b.style.borderColor='rgba(16,185,129,0.6)'; b.style.background='rgba(16,185,129,0.08)'; });
        showToast(_t('tg_success'), "success");
        document.getElementById('tg-bind-banner').classList.add('hidden');
        setTimeout(() => closeTgBindModal(), 800);
        const user = TibaAuth.getUser();
        if (user) { user.telegram_id='linked'; TibaAuth.updateTgBadge('linked'); }
    } catch(e) {
        const boxes = document.querySelectorAll('.tg-otp-box');
        boxes.forEach(b => {
            b.style.borderColor='rgba(239,68,68,0.6)';
            b.style.background='rgba(239,68,68,0.05)';
            b.style.boxShadow='0 0 0 3px rgba(239,68,68,0.12)';
        });
        setTimeout(() => boxes.forEach(b => {
            b.style.borderColor='rgba(255,255,255,0.1)';
            b.style.background='rgba(255,255,255,0.05)';
            b.style.boxShadow='';
        }), 2000);
        const wrap = document.getElementById('tg-otp-boxes');
        if(wrap){ wrap.classList.add('tg-otp-shake'); setTimeout(()=>wrap.classList.remove('tg-otp-shake'),400); }

        // Xato xabarini aniqlashtirish
        let errMsg = e.message;
        if (errMsg.includes('xato yoki muddati')) errMsg = _t('tg_err_wrong_code');
        else if (errMsg.includes('allaqachon boshqa')) errMsg = _t('tg_err_already_linked');
        else if (errMsg.includes('Sessiya')) errMsg = _t('tg_err_session');
        showToast(errMsg, 'error');
    } finally {
        btn.disabled=false;
        btn.innerHTML='<i class="fa-solid fa-check-circle mr-2 relative z-10"></i><span class="relative z-10">' + _t('tg_verify_btn') + '</span>';
    }
}

// Paste support for OTP
document.addEventListener('paste', function(e) {
    if (!document.getElementById('tg-modal-step2') || document.getElementById('tg-modal-step2').classList.contains('hidden')) return;
    const txt = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g,'').slice(0,6);
    if (!txt) return;
    const boxes = document.querySelectorAll('.tg-otp-box');
    txt.split('').forEach((ch,i) => { if(boxes[i]) boxes[i].value=ch; });
    document.getElementById('tg-bind-otp').value = txt;
    if(boxes[Math.min(txt.length,5)]) boxes[Math.min(txt.length,5)].focus();
});

// Show banner if logged in but no telegram linked
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        if (typeof TibaAuth !== 'undefined' && TibaAuth.isLoggedIn()) {
            var user = TibaAuth.getUser();
            if (user && !user.telegram_id) {
                var banner = document.getElementById('tg-bind-banner');
                if (banner) banner.classList.remove('hidden');
            }
        }
    }, 1500);
});
</script>


<div id="content-wrapper" class="lg:ml-[260px] pt-14 lg:pt-0 min-h-screen flex flex-col">
<!-- ========== TELEGRAM BIND NOTIFICATION ========== -->
<style>
@keyframes tg-shimmer{0%{transform:translateX(-100%)}100%{transform:translateX(200%)}}
@keyframes tg-pulse-dot{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(.8)}}
@keyframes tg-modal-in{0%{opacity:0;transform:translateY(20px) scale(.97)}100%{opacity:1;transform:translateY(0) scale(1)}}
@keyframes tg-otp-shake{0%,100%{transform:translateX(0)}20%,60%{transform:translateX(-6px)}40%,80%{transform:translateX(6px)}}
.tg-modal-card{animation:tg-modal-in .35s cubic-bezier(.34,1.56,.64,1) forwards}
.tg-otp-shake{animation:tg-otp-shake .4s ease}
.tg-banner-shimmer{position:absolute;top:0;left:0;width:40%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,0.06),transparent);animation:tg-shimmer 3s infinite;pointer-events:none}
</style>
<div id="tg-bind-banner" class="hidden relative z-30 overflow-hidden" style="background:linear-gradient(90deg,#0e0b2e 0%,#0a1628 60%,#061523 100%);border-bottom:1px solid rgba(41,167,225,0.1);">
    <div class="tg-banner-shimmer"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-3 py-2">
            <div class="flex items-center gap-3 min-w-0">
                <div class="relative flex-shrink-0">
                    <div class="w-1.5 h-1.5 rounded-full" style="background:#29A7E1;animation:tg-pulse-dot 2s ease-in-out infinite;"></div>
                </div>
                <i class="fa-brands fa-telegram flex-shrink-0 text-[13px]" style="color:#29A7E1;"></i>
                <span class="text-white/80 text-xs font-medium truncate"><?= t('tg.banner_text') ?></span>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <button onclick="openTgBindModal()"
                    class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all duration-200"
                    style="background:rgba(41,167,225,0.15);border:1px solid rgba(41,167,225,0.4);color:#29A7E1;"
                    onmouseover="this.style.background='rgba(41,167,225,0.28)';this.style.borderColor='rgba(41,167,225,0.7)'"
                    onmouseout="this.style.background='rgba(41,167,225,0.15)';this.style.borderColor='rgba(41,167,225,0.4)'">
                    <?= t('tg.banner_btn') ?> <i class="fa-solid fa-arrow-right" style="font-size:9px;"></i>
                </button>
                <button onclick="document.getElementById('tg-bind-banner').classList.add('hidden')"
                    class="w-6 h-6 flex items-center justify-center rounded-md transition-all"
                    style="color:rgba(255,255,255,0.3);"
                    onmouseover="this.style.background='rgba(255,255,255,0.08)';this.style.color='rgba(255,255,255,0.7)'"
                    onmouseout="this.style.background='';this.style.color='rgba(255,255,255,0.3)'">
                    <i class="fa-solid fa-xmark" style="font-size:11px;"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ========== GLOBAL TOAST ========== -->
<div id="status-toast" class="hidden fixed top-24 right-8 z-[100] animate-slide-up">
    <div class="bg-indigo-600 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center gap-3 border border-white/10 backdrop-blur-md">
        <span id="toast-icon">✅</span>
        <span id="toast-msg"><?= t('toast.success') ?></span>
    </div>
</div>


<main class="flex-1">
