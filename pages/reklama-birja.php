<?php
require_once __DIR__ . '/../api/config.php';
require_once __DIR__ . '/../lang/i18n.php';

$pageTitle = 'Reklama Birjasi – Tiba AI';
$pageDescription = 'Tiba AI platformasida faol va tasdiqlangan blogerlar bilan reklama yoki barter shartnomalarini tuzing.';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<!-- Marketplace Wrapper -->
<div class="py-10 sm:py-16 min-h-screen" style="background:radial-gradient(circle at top right, rgba(249, 115, 22, 0.05), transparent 400px);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Hero Section -->
        <div class="mb-12 relative overflow-hidden rounded-3xl p-8 sm:p-12 border border-white/5 bg-gradient-to-br from-white/[0.03] to-white/[0.01] backdrop-blur-xl">
            <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-gradient-to-br from-orange-500/10 to-rose-500/10 blur-3xl"></div>
            <div class="relative z-10 max-w-2xl">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-orange-500/10 text-orange-400 border border-orange-500/20 mb-4 uppercase tracking-wider">
                    <i class="fa-solid fa-fire text-[10px]"></i> Reklama Birjasi
                </span>
                <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-4 leading-none">
                    Blogerlar bilan <span class="bg-gradient-to-r from-orange-500 to-rose-500 bg-clip-text text-transparent">reklama yoki barter</span> shartnomalari
                </h1>
                <p class="text-base text-gray-400 leading-relaxed mb-6">
                    Mahsulotingizni reklama qilish uchun eng mos keladigan blogerlarni toping. O'zaro kelishilgan narx yoki barter asosida hamkorlik taklifini yuboring.
                </p>
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center gap-2 text-xs font-semibold text-gray-300 bg-white/5 border border-white/10 px-3.5 py-2 rounded-xl">
                        <i class="fa-solid fa-circle-check text-emerald-400"></i> Tasdiqlangan blogerlar
                    </div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-gray-300 bg-white/5 border border-white/10 px-3.5 py-2 rounded-xl">
                        <i class="fa-solid fa-handshake text-orange-400"></i> Barter / Pullik hamkorlik
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Grid & Search -->
        <div class="mb-8 space-y-4">
            <div class="flex flex-col lg:flex-row gap-4 items-stretch lg:items-center justify-between">
                <!-- Search Input -->
                <div class="relative flex-1 max-w-lg">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                    <input type="text" id="search-input" placeholder="Ism yoki bio bo'yicha qidirish..." 
                        class="w-full pl-11 pr-4 py-3 bg-white/5 border border-white/10 rounded-2xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-orange-500/50 focus:bg-white/[0.07] transition-all">
                </div>
                
                <!-- Filters Controls -->
                <div class="flex flex-wrap gap-3 items-center">
                    <!-- Deal Type Filter -->
                    <select id="filter-deal-type" 
                        class="px-4 py-3 bg-white/5 border border-white/10 rounded-2xl text-white text-sm focus:outline-none focus:border-orange-500/50 transition-all cursor-pointer">
                        <option value="" class="bg-[#12121a]">Hamkorlik turi (Barchasi)</option>
                        <option value="paid" class="bg-[#12121a]">Faqat Pullik</option>
                        <option value="barter" class="bg-[#12121a]">Barter qabul qiladiganlar</option>
                    </select>

                    <!-- Sort Filter -->
                    <select id="filter-sort" 
                        class="px-4 py-3 bg-white/5 border border-white/10 rounded-2xl text-white text-sm focus:outline-none focus:border-orange-500/50 transition-all cursor-pointer">
                        <option value="newest" class="bg-[#12121a]">Saralash: Yangilar birinchi</option>
                        <option value="followers_desc" class="bg-[#12121a]">Obunachilar soni bo'yicha</option>
                        <option value="rating_desc" class="bg-[#12121a]">Reyting bo'yicha</option>
                        <option value="price_asc" class="bg-[#12121a]">Narx: Arzonroqlar birinchi</option>
                    </select>
                </div>
            </div>

            <!-- Categories Horizontal sliding track -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Kategoriyalar</span>
                    <button onclick="clearCategoryFilter()" id="clear-cat-btn" class="hidden text-xs font-semibold text-orange-400 hover:text-orange-300 transition-colors">Barchasini ko'rish</button>
                </div>
                <div class="flex gap-2 overflow-x-auto pb-3 custom-scrollbar scroll-smooth" id="categories-track">
                    <!-- Dynamic categories will be loaded here -->
                    <div class="h-9 w-24 bg-white/5 border border-white/5 animate-pulse rounded-full"></div>
                    <div class="h-9 w-28 bg-white/5 border border-white/5 animate-pulse rounded-full"></div>
                    <div class="h-9 w-20 bg-white/5 border border-white/5 animate-pulse rounded-full"></div>
                    <div class="h-9 w-32 bg-white/5 border border-white/5 animate-pulse rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Bloggers List & Grid -->
        <div id="bloggers-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Loading Skeleton -->
            <div class="glass-card p-6 border border-white/5 animate-pulse rounded-2xl h-[340px] flex flex-col justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl"></div>
                    <div class="flex-1 space-y-2">
                        <div class="h-4 bg-white/10 rounded w-1/2"></div>
                        <div class="h-3 bg-white/10 rounded w-3/4"></div>
                    </div>
                </div>
                <div class="space-y-2 py-4">
                    <div class="h-3 bg-white/10 rounded w-full"></div>
                    <div class="h-3 bg-white/10 rounded w-5/6"></div>
                </div>
                <div class="h-10 bg-white/10 rounded-xl w-full"></div>
            </div>
            <div class="glass-card p-6 border border-white/5 animate-pulse rounded-2xl h-[340px] flex flex-col justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl"></div>
                    <div class="flex-1 space-y-2">
                        <div class="h-4 bg-white/10 rounded w-2/3"></div>
                        <div class="h-3 bg-white/10 rounded w-1/2"></div>
                    </div>
                </div>
                <div class="space-y-2 py-4">
                    <div class="h-3 bg-white/10 rounded w-full"></div>
                    <div class="h-3 bg-white/10 rounded w-4/5"></div>
                </div>
                <div class="h-10 bg-white/10 rounded-xl w-full"></div>
            </div>
            <div class="glass-card p-6 border border-white/5 animate-pulse rounded-2xl h-[340px] flex flex-col justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl"></div>
                    <div class="flex-1 space-y-2">
                        <div class="h-4 bg-white/10 rounded w-1/2"></div>
                        <div class="h-3 bg-white/10 rounded w-3/4"></div>
                    </div>
                </div>
                <div class="space-y-2 py-4">
                    <div class="h-3 bg-white/10 rounded w-full"></div>
                    <div class="h-3 bg-white/10 rounded w-2/3"></div>
                </div>
                <div class="h-10 bg-white/10 rounded-xl w-full"></div>
            </div>
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="hidden flex-col items-center justify-center py-20 text-center">
            <div class="w-20 h-20 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-4xl text-gray-500 mb-4 shadow-inner">
                <i class="fa-solid fa-users-slash"></i>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Hech qanday bloger topilmadi</h3>
            <p class="text-gray-400 text-sm max-w-sm">
                Siz tanlagan filtrlar yoki qidiruv so'rovi bo'yicha hech kim topilmadi. Qidiruv parametrlarini o'zgartirib ko'ring.
            </p>
        </div>

    </div>
</div>

<!-- ================== ORDER MODAL (BUYURTMA BERISH OYNASI) ================== -->
<div id="order-modal" class="hidden fixed inset-0 z-[150] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/90 backdrop-blur-md" onclick="closeOrderModal()"></div>
    <div class="relative w-full max-w-xl animate-fade-in-up">
        <div class="rounded-3xl border border-white/10 shadow-2xl overflow-hidden bg-[#0d0d15] p-6 max-h-[90vh] overflow-y-auto custom-scrollbar">
            
            <!-- Close Button -->
            <button onclick="closeOrderModal()" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:text-white hover:bg-white/10 transition-all">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <!-- Modal Header -->
            <div class="mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-rose-500 flex items-center justify-center text-white">
                        <i class="fa-solid fa-paper-plane"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold text-white">Hamkorlik taklifi yuborish</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Bloger: <span id="modal-blogger-name" class="font-bold text-orange-400">...</span></p>
                    </div>
                </div>
            </div>

            <!-- Error Banner -->
            <div id="order-error" class="hidden bg-red-500/10 border border-red-500/20 text-red-400 p-3.5 rounded-xl text-xs text-center mb-4"></div>

            <!-- Order Form -->
            <form id="order-form" onsubmit="submitOrder(event)" class="space-y-4">
                <input type="hidden" id="order-blogger-id">

                <!-- Deal Type Tabs -->
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Kelishuv turi</label>
                    <div class="grid grid-cols-2 gap-2 bg-white/5 p-1 rounded-xl border border-white/5">
                        <button type="button" onclick="selectDealType('paid')" id="tab-deal-paid" 
                            class="py-2.5 rounded-lg text-xs font-bold transition-all text-white bg-orange-500 shadow-md">
                            <i class="fa-solid fa-money-bill-wave mr-1.5"></i> Pullik (Kelishilgan)
                        </button>
                        <button type="button" onclick="selectDealType('barter')" id="tab-deal-barter" 
                            class="py-2.5 rounded-lg text-xs font-bold transition-all text-gray-400 hover:text-white">
                            <i class="fa-solid fa-box-open mr-1.5"></i> Barter asosida
                        </button>
                    </div>
                </div>

                <!-- Ad Format Select -->
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Reklama formati</label>
                    <select id="order-ad-format" required
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-orange-500/50 transition-all cursor-pointer">
                        <option value="story" class="bg-[#12121a]">Story (Rasm/Video)</option>
                        <option value="reels" class="bg-[#12121a]">Reels (Instagram Short Video)</option>
                        <option value="post" class="bg-[#12121a]">Post (Lentadagi rasm)</option>
                        <option value="video" class="bg-[#12121a]">Video integratsiya (Telegram/YouTube)</option>
                        <option value="unboxing" class="bg-[#12121a]">Unboxing (Qutini ochib ko'rsatish)</option>
                        <option value="live" class="bg-[#12121a]">Live (Jonli efirda tavsiya)</option>
                    </select>
                </div>

                <!-- Price Section (Paid only) -->
                <div id="price-fields">
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Taklif qilayotgan narxingiz (so'mda)</label>
                    <div class="relative">
                        <i class="fa-solid fa-coins absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                        <input type="number" id="order-price" min="1000" placeholder="Masalan: 500000"
                            class="w-full pl-10 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-orange-500/50 transition-all">
                    </div>
                </div>

                <!-- Barter Fields (Barter only, hidden by default) -->
                <div id="barter-fields" class="hidden space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Barter qilinadigan mahsulotlar/xizmatlar</label>
                        <textarea id="order-barter-items" placeholder="Bloggerga yuboradigan mahsulotingiz nomi, o'lchami va boshqa tafsilotlari..." rows="2"
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-orange-500/50 transition-all resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Barter mahsulotlarining taxminiy qiymati (so'mda)</label>
                        <div class="relative">
                            <i class="fa-solid fa-tags absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                            <input type="number" id="order-barter-value" min="1000" placeholder="Masalan: 300000"
                                class="w-full pl-10 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-orange-500/50 transition-all">
                        </div>
                    </div>
                </div>

                <!-- Product General Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Mahsulot nomi</label>
                        <input type="text" id="order-product-name" required placeholder="Masalan: Ayollar sumkasi"
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-orange-500/50 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Mahsulot toifasi (Kategoriya)</label>
                        <input type="text" id="order-product-category" required placeholder="Masalan: Moda / Kiyim-kechak"
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-orange-500/50 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Mahsulot havolasi (Do'kon / Instagram link)</label>
                    <input type="url" id="order-product-link" placeholder="https://uzum.uz/product/..."
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-orange-500/50 transition-all">
                </div>

                <!-- TZ / Instructions -->
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Texnik topshiriq (Blogger nima qilishi kerak?)</label>
                    <textarea id="order-description" required placeholder="Reklama jarayoni uchun batafsil qo'llanma: qaysi jihatlariga e'tibor qaratish kerak, qanday gaplarni aytish shart..." rows="3"
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-orange-500/50 transition-all resize-none"></textarea>
                </div>

                <!-- Deadline -->
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Reklama topshirilishi kerak bo'lgan muddat (Deadline)</label>
                    <input type="date" id="order-deadline" required
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-orange-500/50 transition-all cursor-pointer">
                </div>

                <!-- Submit Button -->
                <button type="submit" id="order-submit-btn" 
                    class="w-full py-4 rounded-2xl bg-gradient-to-r from-orange-500 to-rose-500 hover:from-orange-400 hover:to-rose-400 text-white font-bold text-sm transition-all duration-300 shadow-lg shadow-orange-500/25 hover:shadow-orange-500/40 hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span id="order-submit-text">Taklifni yuborish</span>
                </button>

            </form>
        </div>
    </div>
</div>

<!-- Extra styling -->
<style>
/* Categories Horizonal Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    height: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.2);
}
</style>

<!-- Marketplace Logic Script -->
<script>
let selectedCategory = '';
let selectedDealType = 'paid';
let bloggersCache = [];

document.addEventListener('DOMContentLoaded', () => {
    // 1. Kategoriyalarni yuklash
    loadCategories();
    // 2. Blogerlarni yuklash
    loadBloggers();

    // Event Listeners for Filters
    document.getElementById('search-input').addEventListener('input', debounce(() => {
        loadBloggers();
    }, 400));

    document.getElementById('filter-deal-type').addEventListener('change', () => {
        loadBloggers();
    });

    document.getElementById('filter-sort').addEventListener('change', () => {
        loadBloggers();
    });

    // Set minimum date for deadline to tomorrow
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const yyyy = tomorrow.getFullYear();
    const mm = String(tomorrow.getMonth() + 1).padStart(2, '0');
    const dd = String(tomorrow.getDate()).padStart(2, '0');
    document.getElementById('order-deadline').min = `${yyyy}-${mm}-${dd}`;
});

// Debounce helper
function debounce(func, timeout = 300) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => { func.apply(this, args); }, timeout);
    };
}

// 1. Load Categories
async function loadCategories() {
    try {
        const res = await fetch('/api/marketplace.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'get_categories' })
        });
        const data = await res.json();
        
        if (data.success && data.categories) {
            const track = document.getElementById('categories-track');
            track.innerHTML = '';
            
            // "Barchasi" Button
            track.innerHTML += `
                <button onclick="selectCategory('')" id="cat-all" 
                    class="px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-all border border-orange-500/30 bg-orange-500/10 text-orange-400">
                    Barchasi
                </button>
            `;
            
            data.categories.forEach(cat => {
                track.innerHTML += `
                    <button onclick="selectCategory('${cat.slug}')" id="cat-${cat.slug}" 
                        class="px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-all border border-white/10 bg-white/5 text-gray-400 hover:text-white hover:bg-white/10 flex items-center gap-1.5">
                        <i class="fa-solid ${cat.icon} text-[10px] opacity-60"></i>
                        <span>${cat.name_uz}</span>
                    </button>
                `;
            });
        }
    } catch (e) {
        console.error('Kategoriyalarni yuklashda xatolik:', e);
    }
}

// 2. Select Category Filter
function selectCategory(slug) {
    selectedCategory = slug;
    
    // UI update
    const track = document.getElementById('categories-track');
    const buttons = track.getElementsByTagName('button');
    for (let btn of buttons) {
        btn.className = "px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-all border border-white/10 bg-white/5 text-gray-400 hover:text-white hover:bg-white/10 flex items-center gap-1.5";
    }
    
    const activeBtn = slug === '' ? document.getElementById('cat-all') : document.getElementById(`cat-${slug}`);
    if (activeBtn) {
        activeBtn.className = "px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-all border border-orange-500/30 bg-orange-500/10 text-orange-400 flex items-center gap-1.5";
    }
    
    const clearBtn = document.getElementById('clear-cat-btn');
    if (slug === '') {
        clearBtn.classList.add('hidden');
    } else {
        clearBtn.classList.remove('hidden');
    }

    loadBloggers();
}

function clearCategoryFilter() {
    selectCategory('');
}

// 3. Fetch Bloggers
async function loadBloggers() {
    const grid = document.getElementById('bloggers-grid');
    const emptyState = document.getElementById('empty-state');
    
    // Show Loading
    grid.innerHTML = `
        <div class="glass-card p-6 border border-white/5 animate-pulse rounded-2xl h-[340px] flex flex-col justify-between">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-white/10 rounded-2xl"></div>
                <div class="flex-1 space-y-2">
                    <div class="h-4 bg-white/10 rounded w-1/2"></div>
                    <div class="h-3 bg-white/10 rounded w-3/4"></div>
                </div>
            </div>
            <div class="space-y-2 py-4">
                <div class="h-3 bg-white/10 rounded w-full"></div>
                <div class="h-3 bg-white/10 rounded w-5/6"></div>
            </div>
            <div class="h-10 bg-white/10 rounded-xl w-full"></div>
        </div>
    `;
    emptyState.classList.add('hidden');

    const search = document.getElementById('search-input').value;
    const dealType = document.getElementById('filter-deal-type').value;
    const sort = document.getElementById('filter-sort').value;

    try {
        const res = await fetch('/api/marketplace.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'get_bloggers',
                search: search,
                category: selectedCategory,
                deal_type: dealType,
                sort: sort
            })
        });
        const data = await res.json();
        
        if (data.success && data.bloggers) {
            bloggersCache = data.bloggers;
            grid.innerHTML = '';
            
            if (data.bloggers.length === 0) {
                emptyState.classList.remove('hidden');
                emptyState.classList.add('flex');
                return;
            }

            data.bloggers.forEach(blogger => {
                // Followers Calculation
                const totalFollowers = (blogger.instagram_followers || 0) + (blogger.tiktok_followers || 0) + (blogger.youtube_subscribers || 0) + (blogger.telegram_subscribers || 0);
                const followersStr = totalFollowers >= 1000000 
                    ? (totalFollowers / 1000000).toFixed(1) + 'M' 
                    : totalFollowers >= 1000 
                        ? (totalFollowers / 1000).toFixed(0) + 'K' 
                        : totalFollowers;

                // Price display
                const minPrice = Math.min(...[blogger.price_story, blogger.price_reels, blogger.price_post].filter(p => p > 0));
                const priceStr = minPrice && minPrice !== Infinity
                    ? 'Boshlang\'ich: ' + new Intl.NumberFormat('uz-UZ').format(minPrice) + ' so\'m'
                    : 'Narxi kelishiladi';

                // Rating stars
                const rating = parseFloat(blogger.rating || 0).toFixed(1);
                
                grid.innerHTML += `
                    <div class="glass-card p-6 border border-white/5 rounded-2xl flex flex-col justify-between hover:border-orange-500/20 transition-all duration-300 relative group">
                        ${blogger.is_featured ? `
                            <div class="absolute top-4 right-4 bg-gradient-to-r from-amber-500 to-orange-500 text-[10px] font-bold text-white px-2 py-0.5 rounded-full shadow-lg shadow-orange-500/20">
                                <i class="fa-solid fa-star"></i> TOP
                            </div>
                        ` : ''}
                        
                        <!-- Blogger Header -->
                        <div>
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex-shrink-0 relative overflow-hidden flex items-center justify-center">
                                    ${blogger.avatar_path 
                                        ? `<img src="${blogger.avatar_path}" class="w-full h-full object-cover">`
                                        : `<i class="fa-solid fa-user text-2xl text-gray-500"></i>`
                                    }
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-base font-bold text-white truncate flex items-center gap-1.5">
                                        <span>${blogger.display_name}</span>
                                        <i class="fa-solid fa-circle-check text-blue-400 text-xs" title="Tasdiqlangan"></i>
                                    </h3>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <div class="flex items-center text-amber-400 text-[11px] font-bold">
                                            <i class="fa-solid fa-star mr-1"></i> ${rating}
                                        </div>
                                        <span class="text-gray-600 text-xs">|</span>
                                        <span class="text-xs text-gray-400 font-semibold">${followersStr} obunachilar</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Bio -->
                            <p class="text-xs text-gray-400 line-clamp-3 mb-4 min-h-[48px] leading-relaxed">
                                ${blogger.bio || 'Bloger haqida ma\'lumot kiritilmagan.'}
                            </p>

                            <!-- Stats / Badges -->
                            <div class="flex flex-wrap gap-1.5 mb-6">
                                ${blogger.price_story > 0 ? `<span class="px-2 py-0.5 rounded bg-white/5 border border-white/5 text-[10px] font-semibold text-gray-400">Story: ~${new Intl.NumberFormat('uz-UZ').format(blogger.price_story)}</span>` : ''}
                                ${blogger.price_reels > 0 ? `<span class="px-2 py-0.5 rounded bg-white/5 border border-white/5 text-[10px] font-semibold text-gray-400">Reels: ~${new Intl.NumberFormat('uz-UZ').format(blogger.price_reels)}</span>` : ''}
                                ${blogger.accepts_barter ? `<span class="px-2 py-0.5 rounded bg-purple-500/10 border border-purple-500/20 text-[10px] font-bold text-purple-400 uppercase"><i class="fa-solid fa-box-open mr-1"></i> Barter bor</span>` : ''}
                            </div>
                        </div>

                        <!-- Footer CTA -->
                        <div class="pt-4 border-t border-white/5 flex items-center justify-between gap-4">
                            <div class="min-w-0">
                                <div class="text-[10px] text-gray-500 uppercase tracking-wider">Reklama Narxi</div>
                                <div class="text-sm font-bold text-white truncate">${priceStr}</div>
                            </div>
                            <button onclick="requestOrderBlogger(${blogger.id}, '${blogger.display_name.replace(/'/g, "\\'")}', ${blogger.accepts_barter})" 
                                class="px-5 py-2.5 rounded-xl bg-orange-500/10 hover:bg-orange-500 border border-orange-500/20 hover:border-orange-500 text-orange-400 hover:text-white font-bold text-xs transition-all duration-300 flex-shrink-0 group-hover:scale-[1.03] active:scale-[0.98]">
                                Buyurtma berish
                            </button>
                        </div>
                    </div>
                `;
            });
        }
    } catch (e) {
        console.error('Blogerlarni yuklashda xatolik:', e);
        grid.innerHTML = `<div class="col-span-full p-4 bg-red-500/10 text-red-400 rounded-xl text-center text-sm border border-red-500/20">Blogerlarni yuklashda tarmoq xatosi yuz berdi.</div>`;
    }
}

// 4. Request Order Blogger (Auth Check)
function requestOrderBlogger(id, name, acceptsBarter) {
    // Check if user is logged in
    const token = typeof TibaAuth !== 'undefined' ? TibaAuth.getToken() : localStorage.getItem('auth_token');
    
    if (!token) {
        // Show auth modal and pass callback
        if (typeof TibaAuth !== 'undefined') {
            TibaAuth.showModal(() => {
                // Callback once user logged in successfully
                openOrderModal(id, name, acceptsBarter);
            });
        } else {
            alert('Iltimos, avval platformaga kiring.');
        }
    } else {
        openOrderModal(id, name, acceptsBarter);
    }
}

// 5. Open Order Modal
function openOrderModal(id, name, acceptsBarter) {
    document.getElementById('order-blogger-id').value = id;
    document.getElementById('modal-blogger-name').textContent = name;
    
    // Reset fields
    document.getElementById('order-form').reset();
    document.getElementById('order-error').classList.add('hidden');
    
    // Barter options handling
    const tabBarter = document.getElementById('tab-deal-barter');
    if (acceptsBarter) {
        tabBarter.disabled = false;
        tabBarter.classList.remove('opacity-40', 'cursor-not-allowed');
        tabBarter.setAttribute('title', '');
    } else {
        tabBarter.disabled = true;
        tabBarter.classList.add('opacity-40', 'cursor-not-allowed');
        tabBarter.setAttribute('title', 'Ushbu bloger barter qabul qilmaydi');
    }

    // Default to paid
    selectDealType('paid');

    document.getElementById('order-modal').classList.remove('hidden');
}

function closeOrderModal() {
    document.getElementById('order-modal').classList.add('hidden');
}

// 6. Select Deal Type Tab
function selectDealType(type) {
    selectedDealType = type;
    
    const tabPaid = document.getElementById('tab-deal-paid');
    const tabBarter = document.getElementById('tab-deal-barter');
    const priceFields = document.getElementById('price-fields');
    const barterFields = document.getElementById('barter-fields');
    const priceInput = document.getElementById('order-price');
    const barterItems = document.getElementById('order-barter-items');
    const barterValue = document.getElementById('order-barter-value');

    if (type === 'paid') {
        tabPaid.className = "py-2.5 rounded-lg text-xs font-bold transition-all text-white bg-orange-500 shadow-md";
        tabBarter.className = "py-2.5 rounded-lg text-xs font-bold transition-all text-gray-400 hover:text-white";
        priceFields.classList.remove('hidden');
        barterFields.classList.add('hidden');
        priceInput.required = true;
        barterItems.required = false;
        barterValue.required = false;
    } else {
        tabPaid.className = "py-2.5 rounded-lg text-xs font-bold transition-all text-gray-400 hover:text-white";
        tabBarter.className = "py-2.5 rounded-lg text-xs font-bold transition-all text-white bg-orange-500 shadow-md";
        priceFields.classList.add('hidden');
        barterFields.classList.remove('hidden');
        priceInput.required = false;
        barterItems.required = true;
        barterValue.required = true;
    }
}

// 7. Submit Order Deal
async function submitOrder(e) {
    e.preventDefault();
    document.getElementById('order-error').classList.add('hidden');
    
    const token = typeof TibaAuth !== 'undefined' ? TibaAuth.getToken() : localStorage.getItem('auth_token');
    if (!token) {
        alert('Tizimdan chiqib ketgansiz. Iltimos, qayta tizimga kiring.');
        return;
    }

    const submitBtn = document.getElementById('order-submit-btn');
    const submitText = document.getElementById('order-submit-text');
    
    // Set loading
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-70', 'pointer-events-none');
    submitText.textContent = "Yuborilmoqda...";

    const bloggerId = document.getElementById('order-blogger-id').value;
    const adFormat = document.getElementById('order-ad-format').value;
    const offeredPrice = document.getElementById('order-price').value;
    const barterItems = document.getElementById('order-barter-items').value;
    const barterValue = document.getElementById('order-barter-value').value;
    const productName = document.getElementById('order-product-name').value;
    const productCategory = document.getElementById('order-product-category').value;
    const productLink = document.getElementById('order-product-link').value;
    const description = document.getElementById('order-description').value;
    const deadline = document.getElementById('order-deadline').value;

    try {
        const res = await fetch('/api/marketplace.php', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-User-Token': token 
            },
            body: JSON.stringify({
                action: 'create_deal',
                blogger_id: bloggerId,
                deal_type: selectedDealType,
                ad_format: adFormat,
                offered_price: offeredPrice,
                barter_items: barterItems,
                barter_value: barterValue,
                product_name: productName,
                product_category: productCategory,
                product_link: productLink,
                description: description,
                deadline: deadline
            })
        });
        const data = await res.json();
        
        if (data.success) {
            closeOrderModal();
            // Show toast or alert
            alert(data.message || 'Kelishuv muvaffaqiyatli yuborildi!');
            
            // Redirect or refresh
            window.location.reload();
        } else {
            showOrderError(data.error || 'Xatolik yuz berdi');
        }
    } catch(err) {
        showOrderError('Tarmoq xatosi. Iltimos, qayta urinib ko\'ring.');
    } finally {
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-70', 'pointer-events-none');
        submitText.textContent = "Taklifni yuborish";
    }
}

function showOrderError(msg) {
    const el = document.getElementById('order-error');
    el.textContent = msg;
    el.classList.remove('hidden');
}
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
