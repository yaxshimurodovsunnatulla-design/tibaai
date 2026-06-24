<?php
// ========== i18n TIZIMI ==========
require_once __DIR__ . '/../../lang/i18n.php';
$_currentLang = lang();

require_once __DIR__ . '/../../api/config.php';
$currentPage = $_SERVER['REQUEST_URI'] ?? '/blogger';
$currentPage = parse_url($currentPage, PHP_URL_PATH);
$currentPage = rtrim($currentPage, '/') ?: '/blogger';
?>
<!DOCTYPE html>
<html lang="<?= $_currentLang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Blogger Panel' ?> – Tiba AI</title>
    
    <meta name="theme-color" content="#0f0a07">
    
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Cdefs%3E%3ClinearGradient id='g' x1='0' y1='0' x2='1' y2='1'%3E%3Cstop offset='0%25' stop-color='%23f97316'/%3E%3Cstop offset='100%25' stop-color='%23f43f5e'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='32' height='32' rx='8' fill='url(%23g)'/%3E%3Ctext x='16' y='23' font-family='Inter,sans-serif' font-size='18' font-weight='900' fill='white' text-anchor='middle'%3EB%3C/text%3E%3C/svg%3E">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: { extend: { fontFamily: { sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'] } } }
    }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Blogger CSS -->
    <link rel="stylesheet" href="/blogger/assets/blogger-style.css?v=<?= time() ?>">
</head>
<body class="bg-[#0f0a07] text-[#f8fafc] flex flex-col md:flex-row min-h-screen">

<!-- Mobile Header -->
<div class="md:hidden flex items-center justify-between p-4 bg-[#1a1008] border-b border-white/10 sticky top-0 z-50">
    <a href="/blogger" class="flex items-center gap-2">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-500 to-rose-500 flex items-center justify-center text-white font-black">B</div>
        <span class="font-bold text-lg text-white">Tiba <span class="text-orange-500">Blogger</span></span>
    </a>
    <button id="mobile-menu-btn" class="w-10 h-10 flex items-center justify-center bg-white/5 rounded-lg text-white">
        <i class="fa-solid fa-bars"></i>
    </button>
</div>

<!-- Mobile Menu Overlay -->
<div id="mobile-menu" class="fixed inset-0 bg-black/90 z-[60] flex-col hidden backdrop-blur-sm">
    <div class="p-4 flex justify-between items-center border-b border-white/10 bg-[#1a1008]">
        <span class="font-bold text-white">Menu</span>
        <button id="mobile-close-btn" class="w-10 h-10 flex items-center justify-center bg-white/5 rounded-lg text-white">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="flex-1 overflow-y-auto p-4 space-y-2">
        <a href="/blogger" class="flex items-center gap-3 p-3 rounded-xl <?= $currentPage == '/blogger' ? 'bg-orange-500/20 text-orange-400' : 'text-gray-400 hover:bg-white/5 hover:text-white' ?>">
            <i class="fa-solid fa-chart-pie w-5"></i> Dashboard
        </a>
        <a href="/blogger/takliflar" class="flex items-center gap-3 p-3 rounded-xl <?= $currentPage == '/blogger/takliflar' ? 'bg-orange-500/20 text-orange-400' : 'text-gray-400 hover:bg-white/5 hover:text-white' ?>">
            <i class="fa-solid fa-inbox w-5"></i> Takliflar
        </a>
        <a href="/blogger/bitimlar" class="flex items-center gap-3 p-3 rounded-xl <?= $currentPage == '/blogger/bitimlar' ? 'bg-orange-500/20 text-orange-400' : 'text-gray-400 hover:bg-white/5 hover:text-white' ?>">
            <i class="fa-solid fa-handshake w-5"></i> Bitimlar
        </a>
        <a href="/blogger/daromad" class="flex items-center gap-3 p-3 rounded-xl <?= $currentPage == '/blogger/daromad' ? 'bg-orange-500/20 text-orange-400' : 'text-gray-400 hover:bg-white/5 hover:text-white' ?>">
            <i class="fa-solid fa-wallet w-5"></i> Daromadlarim
        </a>
        <a href="/blogger/portfolio" class="flex items-center gap-3 p-3 rounded-xl <?= $currentPage == '/blogger/portfolio' ? 'bg-orange-500/20 text-orange-400' : 'text-gray-400 hover:bg-white/5 hover:text-white' ?>">
            <i class="fa-solid fa-images w-5"></i> Portfoliom
        </a>
        <div class="h-px bg-white/10 my-2"></div>
        <a href="/blogger/profil" class="flex items-center gap-3 p-3 rounded-xl <?= $currentPage == '/blogger/profil' ? 'bg-orange-500/20 text-orange-400' : 'text-gray-400 hover:bg-white/5 hover:text-white' ?>">
            <i class="fa-solid fa-user w-5"></i> Profilim
        </a>
        <a href="/blogger/sozlamalar" class="flex items-center gap-3 p-3 rounded-xl <?= $currentPage == '/blogger/sozlamalar' ? 'bg-orange-500/20 text-orange-400' : 'text-gray-400 hover:bg-white/5 hover:text-white' ?>">
            <i class="fa-solid fa-gear w-5"></i> Sozlamalar
        </a>
        <button onclick="logoutBlogger()" class="w-full flex items-center gap-3 p-3 rounded-xl text-red-400 hover:bg-red-500/10 text-left">
            <i class="fa-solid fa-arrow-right-from-bracket w-5"></i> Chiqish
        </button>
    </div>
</div>

<!-- Desktop Sidebar -->
<aside class="hidden md:flex flex-col w-64 bg-[#1a1008] border-r border-white/10 h-screen sticky top-0 flex-shrink-0">
    <div class="p-6">
        <a href="/blogger" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-rose-500 flex items-center justify-center text-white text-xl font-black shadow-lg shadow-orange-500/20">B</div>
            <div>
                <div class="font-bold text-xl text-white tracking-tight">Tiba <span class="gradient-text">Blogger</span></div>
                <div class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Creator Panel</div>
            </div>
        </a>
    </div>

    <div class="flex-1 overflow-y-auto sidebar-scroll px-4 space-y-1 pb-6">
        <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 mt-4 px-2">Asosiy</div>
        <a href="/blogger" class="sidebar-link <?= $currentPage == '/blogger' ? 'active' : '' ?>">
            <div class="sidebar-link-icon"><i class="fa-solid fa-chart-pie"></i></div>
            Dashboard
        </a>
        
        <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 mt-6 px-2">Ish jarayoni</div>
        <a href="/blogger/takliflar" class="sidebar-link <?= $currentPage == '/blogger/takliflar' ? 'active' : '' ?> flex justify-between">
            <div class="flex items-center gap-3">
                <div class="sidebar-link-icon"><i class="fa-solid fa-inbox"></i></div>
                Takliflar
            </div>
            <span class="bg-orange-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold hidden" id="nav-badge-takliflar">0</span>
        </a>
        <a href="/blogger/bitimlar" class="sidebar-link <?= $currentPage == '/blogger/bitimlar' ? 'active' : '' ?>">
            <div class="sidebar-link-icon"><i class="fa-solid fa-handshake"></i></div>
            Faol Bitimlar
        </a>
        <a href="/blogger/daromad" class="sidebar-link <?= $currentPage == '/blogger/daromad' ? 'active' : '' ?>">
            <div class="sidebar-link-icon"><i class="fa-solid fa-wallet"></i></div>
            Daromadlarim
        </a>
        <a href="/blogger/portfolio" class="sidebar-link <?= $currentPage == '/blogger/portfolio' ? 'active' : '' ?>">
            <div class="sidebar-link-icon"><i class="fa-solid fa-images"></i></div>
            Portfoliom
        </a>

        <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 mt-6 px-2">Hisob</div>
        <a href="/blogger/profil" class="sidebar-link <?= $currentPage == '/blogger/profil' ? 'active' : '' ?>">
            <div class="sidebar-link-icon"><i class="fa-solid fa-user"></i></div>
            Profilim
        </a>
        <a href="/blogger/sozlamalar" class="sidebar-link <?= $currentPage == '/blogger/sozlamalar' ? 'active' : '' ?>">
            <div class="sidebar-link-icon"><i class="fa-solid fa-gear"></i></div>
            Sozlamalar
        </a>
    </div>

    <!-- User Card -->
    <div class="p-4 border-t border-white/10" id="sidebar-user-container" style="display:none;">
        <div class="glass-card p-3 flex flex-col gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-rose-500 flex items-center justify-center text-white font-bold text-lg" id="sidebar-avatar">?</div>
                <div class="flex-1 overflow-hidden">
                    <div class="text-sm font-bold text-white truncate" id="sidebar-name">Yuklanmoqda...</div>
                    <div class="text-xs text-gray-400 truncate" id="sidebar-phone">...</div>
                </div>
            </div>
            <button onclick="logoutBlogger()" class="w-full text-center py-2 text-xs font-bold text-red-400 bg-red-500/10 hover:bg-red-500/20 rounded-lg transition-colors border border-red-500/20">
                <i class="fa-solid fa-arrow-right-from-bracket mr-1"></i> Chiqish
            </button>
        </div>
    </div>
</aside>

<!-- Main Content Wrapper -->
<main class="flex-1 flex flex-col min-w-0 bg-[#0f0a07]">
