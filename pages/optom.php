<?php
require_once __DIR__ . '/../api/config.php';
require_once __DIR__ . '/../lang/i18n.php';

$pageTitle = 'Tiba Optom – Ulgurji Savdo Platformasi';
$pageDescription = 'O\'zbekistondagi ishonchli ulgurji sotuvchilarni toping. Arzon narxlarda, to\'g\'ridan-to\'g\'ri ishlab chiqaruvchilardan xarid qiling.';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<!-- Optom Wrapper -->
<div class="py-10 sm:py-16 min-h-screen" style="background:radial-gradient(circle at top right, rgba(20, 184, 166, 0.05), transparent 400px);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Hero Section -->
        <div class="mb-12 relative overflow-hidden rounded-3xl p-8 sm:p-12 border border-white/5 bg-gradient-to-br from-white/[0.03] to-white/[0.01] backdrop-blur-xl">
            <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-gradient-to-br from-teal-500/10 to-emerald-500/10 blur-3xl"></div>
            <div class="absolute -left-10 bottom-0 w-40 h-40 rounded-full bg-gradient-to-tr from-cyan-500/5 to-teal-500/5 blur-2xl"></div>
            <div class="relative z-10 max-w-2xl">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-500/10 text-teal-400 border border-teal-500/20 mb-4 uppercase tracking-wider">
                    <i class="fa-solid fa-boxes-stacked text-[10px]"></i> Tiba Optom
                </span>
                <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-4 leading-none">
                    O'zbekistonning <span class="bg-gradient-to-r from-teal-400 to-emerald-400 bg-clip-text text-transparent">ulgurji savdo</span> platformasi
                </h1>
                <p class="text-base text-gray-400 leading-relaxed mb-6">
                    Ishonchli ulgurji sotuvchilarni toping, to'g'ridan-to'g'ri ishlab chiqaruvchilardan arzon narxlarda xarid qiling. Barter va hamkorlik imkoniyatlari.
                </p>
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center gap-2 text-xs font-semibold text-gray-300 bg-white/5 border border-white/10 px-3.5 py-2 rounded-xl">
                        <i class="fa-solid fa-shield-check text-teal-400"></i> Tasdiqlangan sotuvchilar
                    </div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-gray-300 bg-white/5 border border-white/10 px-3.5 py-2 rounded-xl">
                        <i class="fa-solid fa-truck-fast text-emerald-400"></i> Butun O'zbekistonga yetkazish
                    </div>
                    <a href="/optom/login" class="flex items-center gap-2 text-xs font-bold text-teal-400 bg-teal-500/10 border border-teal-500/20 px-3.5 py-2 rounded-xl hover:bg-teal-500/20 transition-all">
                        <i class="fa-solid fa-store"></i> Sotuvchi bo'lish
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-10" id="stats-row">
            <div class="glass-card p-4 text-center"><div class="text-2xl font-black text-teal-400" id="total-sellers">0</div><div class="text-[10px] text-gray-500 uppercase tracking-wider font-bold mt-1">Sotuvchilar</div></div>
            <div class="glass-card p-4 text-center"><div class="text-2xl font-black text-emerald-400" id="total-products">0</div><div class="text-[10px] text-gray-500 uppercase tracking-wider font-bold mt-1">Mahsulotlar</div></div>
            <div class="glass-card p-4 text-center"><div class="text-2xl font-black text-cyan-400" id="total-categories">0</div><div class="text-[10px] text-gray-500 uppercase tracking-wider font-bold mt-1">Kategoriyalar</div></div>
            <div class="glass-card p-4 text-center"><div class="text-2xl font-black text-purple-400" id="total-cities">0</div><div class="text-[10px] text-gray-500 uppercase tracking-wider font-bold mt-1">Shaharlar</div></div>
        </div>

        <!-- Filter & Search -->
        <div class="mb-8 space-y-4">
            <div class="flex flex-col lg:flex-row gap-4 items-stretch lg:items-center justify-between">
                <div class="relative flex-1 max-w-lg">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                    <input type="text" id="search-input" placeholder="Kompaniya yoki mahsulot qidirish..." 
                        class="w-full pl-11 pr-4 py-3 bg-white/5 border border-white/10 rounded-2xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-teal-500/50 focus:bg-white/[0.07] transition-all">
                </div>
                <div class="flex flex-wrap gap-3 items-center">
                    <select id="filter-city" class="px-4 py-3 bg-white/5 border border-white/10 rounded-2xl text-white text-sm focus:outline-none focus:border-teal-500/50 transition-all cursor-pointer">
                        <option value="" class="bg-[#12121a]">Barcha shaharlar</option>
                        <option value="Toshkent" class="bg-[#12121a]">Toshkent</option>
                        <option value="Samarqand" class="bg-[#12121a]">Samarqand</option>
                        <option value="Buxoro" class="bg-[#12121a]">Buxoro</option>
                        <option value="Andijon" class="bg-[#12121a]">Andijon</option>
                        <option value="Namangan" class="bg-[#12121a]">Namangan</option>
                        <option value="Farg'ona" class="bg-[#12121a]">Farg'ona</option>
                    </select>
                    <select id="filter-sort" class="px-4 py-3 bg-white/5 border border-white/10 rounded-2xl text-white text-sm focus:outline-none focus:border-teal-500/50 transition-all cursor-pointer">
                        <option value="newest" class="bg-[#12121a]">Yangilar birinchi</option>
                        <option value="rating_desc" class="bg-[#12121a]">Reyting bo'yicha</option>
                        <option value="views_desc" class="bg-[#12121a]">Ko'rishlar bo'yicha</option>
                        <option value="name_asc" class="bg-[#12121a]">Nomi bo'yicha</option>
                    </select>
                </div>
            </div>

            <!-- Categories -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Kategoriyalar</span>
                    <button onclick="clearCategoryFilter()" id="clear-cat-btn" class="hidden text-xs font-semibold text-teal-400 hover:text-teal-300 transition-colors">Barchasini ko'rish</button>
                </div>
                <div class="flex gap-2 overflow-x-auto pb-3" id="categories-track" style="-webkit-overflow-scrolling: touch;">
                    <div class="h-9 w-24 bg-white/5 border border-white/5 animate-pulse rounded-full"></div>
                    <div class="h-9 w-28 bg-white/5 border border-white/5 animate-pulse rounded-full"></div>
                    <div class="h-9 w-20 bg-white/5 border border-white/5 animate-pulse rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Sellers Grid -->
        <div id="sellers-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <!-- Skeleton -->
            <?php for ($i = 0; $i < 6; $i++): ?>
            <div class="animate-pulse rounded-2xl p-6 bg-white/[0.02] border border-white/5">
                <div class="flex items-center gap-4 mb-4"><div class="w-14 h-14 rounded-xl bg-white/10"></div><div class="flex-1 space-y-2"><div class="h-4 bg-white/10 rounded w-2/3"></div><div class="h-3 bg-white/5 rounded w-1/2"></div></div></div>
                <div class="space-y-2"><div class="h-3 bg-white/5 rounded w-full"></div><div class="h-3 bg-white/5 rounded w-4/5"></div></div>
            </div>
            <?php endfor; ?>
        </div>

        <!-- Pagination -->
        <div id="pagination" class="flex justify-center mt-10 hidden">
            <div class="flex items-center gap-2" id="pagination-btns"></div>
        </div>

        <!-- CTA -->
        <div class="mt-16 text-center">
            <div class="glass-card inline-block p-8 sm:p-12 max-w-lg mx-auto border border-teal-500/10">
                <div class="w-14 h-14 mx-auto bg-gradient-to-br from-teal-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-xl shadow-teal-500/20 mb-4">
                    <i class="fa-solid fa-store text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-extrabold text-white mb-2">Ulgurji sotuvchi bo'ling</h3>
                <p class="text-sm text-gray-400 mb-6">Mahsulotlaringizni platformamizda joylashtiring va yangi mijozlarni toping.</p>
                <a href="/optom/login" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-teal-500 to-emerald-600 text-white font-bold text-sm rounded-xl hover:from-teal-400 hover:to-emerald-500 transition-all shadow-lg shadow-teal-500/25 hover:shadow-teal-500/40 active:scale-[0.98]">
                    <i class="fa-solid fa-rocket"></i> Ro'yxatdan o'tish
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Seller Detail Modal -->
<div id="seller-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/90 backdrop-blur-md" onclick="closeSellerModal()"></div>
    <div class="relative w-full max-w-2xl animate-fade-in-up max-h-[90vh] overflow-y-auto">
        <div class="glass-card p-6 sm:p-8 border border-white/10 shadow-2xl">
            <button onclick="closeSellerModal()" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:text-white hover:bg-white/10 transition-all z-10">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div id="seller-detail-content">
                <div class="text-center py-12"><div class="loader w-8 h-8 border-2 border-teal-500/20 border-t-teal-500 mx-auto mb-3 rounded-full"></div><p class="text-gray-500 text-sm">Yuklanmoqda...</p></div>
            </div>
        </div>
    </div>
</div>

<!-- Order Modal -->
<div id="order-modal" class="hidden fixed inset-0 z-[110] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/90 backdrop-blur-md" onclick="closeOrderModal()"></div>
    <div class="relative w-full max-w-md animate-fade-in-up">
        <div class="glass-card p-6 border border-white/10 shadow-2xl">
            <h3 class="text-lg font-extrabold text-white mb-5"><i class="fa-solid fa-paper-plane text-teal-400 mr-2"></i> Buyurtma so'rovi</h3>
            <form id="order-form" class="space-y-4">
                <input type="hidden" id="order-seller-id">
                <input type="hidden" id="order-product-id">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Ismingiz *</label>
                    <input type="text" id="order-name" required class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-teal-500/50 transition-all" placeholder="Ismingiz">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Telefon raqam *</label>
                    <input type="tel" id="order-phone" required class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-teal-500/50 transition-all" placeholder="+998 90 123 45 67">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Miqdor</label>
                    <input type="number" id="order-qty" min="1" value="1" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-teal-500/50 transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Xabar</label>
                    <textarea id="order-message" rows="2" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-teal-500/50 transition-all resize-none" placeholder="Qo'shimcha xabar..."></textarea>
                </div>
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-teal-500 to-emerald-600 text-white font-bold text-sm rounded-xl hover:from-teal-400 hover:to-emerald-500 transition-all shadow-lg shadow-teal-500/25 active:scale-[0.98]">
                    <span class="btn-text">Yuborish</span>
                    <div class="loader hidden w-5 h-5 border-2 border-white/20 border-t-white mx-auto rounded-full"></div>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
.animate-fade-in-up { animation: fadeInUp 0.35s ease-out forwards; }
@keyframes fadeInUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
.loader { border-radius: 50%; animation: spin 0.8s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
.star-filled { color: #f59e0b; }
.star-empty { color: #374151; }
</style>

<script>
let currentPage = 1;
let currentCategory = '';
let searchTimeout;

document.addEventListener('DOMContentLoaded', () => {
    loadCategories();
    loadSellers();

    document.getElementById('search-input').addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => { currentPage = 1; loadSellers(); }, 400);
    });
    document.getElementById('filter-city').addEventListener('change', () => { currentPage = 1; loadSellers(); });
    document.getElementById('filter-sort').addEventListener('change', () => { currentPage = 1; loadSellers(); });
});

async function loadCategories() {
    try {
        const res = await fetch('/api/optom-api.php', {
            method: 'POST', headers: {'Content-Type':'application/json'},
            body: JSON.stringify({ action: 'get_categories' })
        });
        const data = await res.json();
        if (!data.success) return;

        const track = document.getElementById('categories-track');
        track.innerHTML = data.categories.map(c => `
            <button onclick="filterByCategory('${c.name}')" class="cat-btn flex-shrink-0 px-4 py-2 rounded-full text-xs font-semibold border border-white/10 text-gray-400 hover:border-teal-500/40 hover:text-teal-400 hover:bg-teal-500/5 transition-all whitespace-nowrap">
                ${c.name} <span class="text-gray-600 ml-1">${c.count}</span>
            </button>
        `).join('');

        document.getElementById('total-categories').textContent = data.categories.length;
    } catch (e) {}
}

async function loadSellers() {
    const search = document.getElementById('search-input').value;
    const city = document.getElementById('filter-city').value;
    const sort = document.getElementById('filter-sort').value;

    try {
        const res = await fetch('/api/optom-api.php', {
            method: 'POST', headers: {'Content-Type':'application/json'},
            body: JSON.stringify({ action: 'get_sellers', search, city, category: currentCategory, sort, page: currentPage })
        });
        const data = await res.json();
        if (!data.success) return;

        document.getElementById('total-sellers').textContent = data.total;
        
        // Count unique cities
        const cities = new Set(data.sellers.map(s => s.city).filter(Boolean));
        document.getElementById('total-cities').textContent = cities.size || '—';
        
        // Count products
        const totalProds = data.sellers.reduce((sum, s) => sum + (s.product_count || 0), 0);
        document.getElementById('total-products').textContent = totalProds;

        renderSellers(data.sellers);
        renderPagination(data.page, data.pages);
    } catch (e) { console.error(e); }
}

function renderSellers(sellers) {
    const grid = document.getElementById('sellers-grid');
    
    if (sellers.length === 0) {
        grid.innerHTML = `<div class="col-span-full text-center py-16">
            <i class="fa-solid fa-store-slash text-5xl text-gray-700 mb-4"></i>
            <p class="text-gray-400 font-bold">Sotuvchilar topilmadi</p>
            <p class="text-gray-600 text-xs mt-1">Boshqa qidiruv yoki filtrlarni sinab ko'ring</p>
        </div>`;
        return;
    }

    grid.innerHTML = sellers.map((s, i) => {
        const cats = (s.categories || []).slice(0, 3);
        const stars = renderStars(s.rating);
        return `
        <div class="group rounded-2xl p-6 bg-white/[0.02] border border-white/5 hover:border-teal-500/20 hover:bg-white/[0.04] transition-all duration-300 cursor-pointer animate-fade-in-up" style="animation-delay:${i * 0.05}s" onclick="showSellerDetail(${s.id})">
            <div class="flex items-start gap-4 mb-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-teal-500/20 to-emerald-500/20 border border-teal-500/10 flex items-center justify-center text-teal-400 font-black text-xl flex-shrink-0 group-hover:shadow-lg group-hover:shadow-teal-500/10 transition-all">
                    ${s.company_name.charAt(0).toUpperCase()}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm font-extrabold text-white truncate">${s.company_name}</h3>
                        ${s.verified ? '<i class="fa-solid fa-circle-check text-teal-400 text-xs flex-shrink-0" title="Tasdiqlangan"></i>' : ''}
                    </div>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="flex gap-0.5">${stars}</div>
                        <span class="text-[10px] text-gray-500">(${s.review_count})</span>
                    </div>
                </div>
            </div>
            <p class="text-xs text-gray-400 mb-4 line-clamp-2 leading-relaxed">${s.description || 'Ulgurji savdo kompaniyasi'}</p>
            <div class="flex flex-wrap gap-1.5 mb-4">
                ${cats.map(c => `<span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-teal-500/10 text-teal-400 border border-teal-500/15">${c}</span>`).join('')}
                ${(s.categories || []).length > 3 ? `<span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-white/5 text-gray-500">+${s.categories.length - 3}</span>` : ''}
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-white/5">
                <div class="flex items-center gap-3 text-[10px] text-gray-500">
                    <span><i class="fa-solid fa-location-dot mr-1"></i>${s.city || '—'}</span>
                    <span><i class="fa-solid fa-boxes-stacked mr-1"></i>${s.product_count} mahsulot</span>
                </div>
                ${s.min_order_amount > 0 ? `<span class="text-[10px] font-bold text-amber-400">min. ${formatNum(s.min_order_amount)} so'm</span>` : ''}
            </div>
        </div>`;
    }).join('');
}

function renderStars(rating) {
    let html = '';
    for (let i = 1; i <= 5; i++) {
        html += `<i class="fa-solid fa-star text-[10px] ${i <= Math.round(rating) ? 'star-filled' : 'star-empty'}"></i>`;
    }
    return html;
}

function filterByCategory(cat) {
    currentCategory = currentCategory === cat ? '' : cat;
    currentPage = 1;
    
    document.querySelectorAll('.cat-btn').forEach(btn => {
        if (btn.textContent.includes(cat) && currentCategory) {
            btn.classList.add('bg-teal-500/10', 'border-teal-500/40', 'text-teal-400');
        } else {
            btn.classList.remove('bg-teal-500/10', 'border-teal-500/40', 'text-teal-400');
        }
    });

    const clearBtn = document.getElementById('clear-cat-btn');
    if (currentCategory) clearBtn.classList.remove('hidden');
    else clearBtn.classList.add('hidden');

    loadSellers();
}

function clearCategoryFilter() {
    currentCategory = '';
    currentPage = 1;
    document.querySelectorAll('.cat-btn').forEach(btn => {
        btn.classList.remove('bg-teal-500/10', 'border-teal-500/40', 'text-teal-400');
    });
    document.getElementById('clear-cat-btn').classList.add('hidden');
    loadSellers();
}

function renderPagination(page, pages) {
    const container = document.getElementById('pagination');
    const btns = document.getElementById('pagination-btns');
    if (pages <= 1) { container.classList.add('hidden'); return; }
    container.classList.remove('hidden');
    
    let html = '';
    if (page > 1) html += `<button onclick="goPage(${page-1})" class="w-9 h-9 rounded-lg bg-white/5 text-gray-400 hover:text-white hover:bg-white/10 flex items-center justify-center"><i class="fa-solid fa-chevron-left text-xs"></i></button>`;
    for (let i = 1; i <= pages; i++) {
        if (i === page) {
            html += `<button class="w-9 h-9 rounded-lg bg-teal-500/20 text-teal-400 font-bold flex items-center justify-center text-sm">${i}</button>`;
        } else if (Math.abs(i - page) < 3 || i === 1 || i === pages) {
            html += `<button onclick="goPage(${i})" class="w-9 h-9 rounded-lg bg-white/5 text-gray-400 hover:text-white hover:bg-white/10 flex items-center justify-center text-sm">${i}</button>`;
        }
    }
    if (page < pages) html += `<button onclick="goPage(${page+1})" class="w-9 h-9 rounded-lg bg-white/5 text-gray-400 hover:text-white hover:bg-white/10 flex items-center justify-center"><i class="fa-solid fa-chevron-right text-xs"></i></button>`;
    btns.innerHTML = html;
}

function goPage(p) { currentPage = p; loadSellers(); window.scrollTo({ top: 400, behavior: 'smooth' }); }

// Seller Detail
async function showSellerDetail(id) {
    document.getElementById('seller-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    const content = document.getElementById('seller-detail-content');
    content.innerHTML = '<div class="text-center py-12"><div class="loader w-8 h-8 border-2 border-teal-500/20 border-t-teal-500 mx-auto mb-3 rounded-full"></div></div>';

    try {
        const res = await fetch('/api/optom-api.php', {
            method: 'POST', headers: {'Content-Type':'application/json'},
            body: JSON.stringify({ action: 'get_seller_detail', seller_id: id })
        });
        const data = await res.json();
        if (!data.success) { content.innerHTML = '<p class="text-red-400 text-center py-8">Xatolik yuz berdi</p>'; return; }
        
        const s = data.seller;
        const products = data.products || [];
        const cats = s.categories || [];

        content.innerHTML = `
        <div class="flex items-start gap-4 mb-6">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center text-white text-2xl font-black flex-shrink-0 shadow-xl shadow-teal-500/20">${s.company_name.charAt(0).toUpperCase()}</div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-xl font-extrabold text-white">${s.company_name}</h2>
                    ${s.verified ? '<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-500/15 text-teal-400 border border-teal-500/20"><i class="fa-solid fa-check mr-0.5"></i> Tasdiqlangan</span>' : ''}
                </div>
                <p class="text-sm text-gray-400">${s.owner_name}</p>
                <div class="flex gap-0.5 mt-1">${renderStars(s.rating)} <span class="text-xs text-gray-500 ml-1">(${s.review_count} baho)</span></div>
            </div>
        </div>
        ${s.description ? `<p class="text-sm text-gray-300 mb-5 leading-relaxed">${s.description}</p>` : ''}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
            <div class="p-3 rounded-xl bg-white/[0.03] border border-white/5 text-center">
                <i class="fa-solid fa-location-dot text-teal-400 text-sm mb-1"></i>
                <div class="text-xs font-bold text-white">${s.city || '—'}</div>
                <div class="text-[10px] text-gray-500">Shahar</div>
            </div>
            <div class="p-3 rounded-xl bg-white/[0.03] border border-white/5 text-center">
                <i class="fa-solid fa-boxes-stacked text-emerald-400 text-sm mb-1"></i>
                <div class="text-xs font-bold text-white">${products.length}</div>
                <div class="text-[10px] text-gray-500">Mahsulotlar</div>
            </div>
            <div class="p-3 rounded-xl bg-white/[0.03] border border-white/5 text-center">
                <i class="fa-solid fa-eye text-purple-400 text-sm mb-1"></i>
                <div class="text-xs font-bold text-white">${formatNum(s.views)}</div>
                <div class="text-[10px] text-gray-500">Ko'rishlar</div>
            </div>
            <div class="p-3 rounded-xl bg-white/[0.03] border border-white/5 text-center">
                <i class="fa-solid fa-coins text-amber-400 text-sm mb-1"></i>
                <div class="text-xs font-bold text-white">${s.min_order_amount ? formatNum(s.min_order_amount) : 'Yo\'q'}</div>
                <div class="text-[10px] text-gray-500">Min. buyurtma</div>
            </div>
        </div>
        ${cats.length ? `<div class="flex flex-wrap gap-1.5 mb-5">${cats.map(c => `<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-teal-500/10 text-teal-400 border border-teal-500/15">${c}</span>`).join('')}</div>` : ''}
        <div class="flex flex-wrap gap-3 mb-6 text-xs text-gray-400">
            ${s.phone ? `<span><i class="fa-solid fa-phone mr-1 text-teal-400"></i>${s.phone}</span>` : ''}
            ${s.email ? `<span><i class="fa-solid fa-envelope mr-1 text-teal-400"></i>${s.email}</span>` : ''}
            ${s.address ? `<span><i class="fa-solid fa-map-marker-alt mr-1 text-teal-400"></i>${s.address}</span>` : ''}
        </div>
        ${products.length > 0 ? `
        <h4 class="text-sm font-bold text-white mb-3">Mahsulotlar</h4>
        <div class="space-y-2 mb-6 max-h-[300px] overflow-y-auto pr-1">
            ${products.map(p => `
            <div class="flex items-center justify-between p-3 rounded-xl bg-white/[0.02] border border-white/5 hover:bg-white/[0.04] transition-all">
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-bold text-white truncate">${p.name}</div>
                    <div class="text-[10px] text-gray-500">${p.category} · min. ${p.min_quantity} ${p.unit}</div>
                </div>
                <div class="text-right flex-shrink-0 ml-3">
                    ${p.price_retail ? `<div class="text-[10px] text-gray-500 line-through">${formatNum(p.price_retail)} so'm</div>` : ''}
                    <div class="text-sm font-bold text-teal-400">${formatNum(p.price_wholesale)} so'm</div>
                </div>
                <button onclick="event.stopPropagation(); openOrderModal(${s.id}, ${p.id})" class="ml-3 w-8 h-8 rounded-lg bg-teal-500/10 text-teal-400 hover:bg-teal-500/20 flex items-center justify-center transition-all flex-shrink-0" title="Buyurtma berish">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                </button>
            </div>`).join('')}
        </div>` : ''}
        <button onclick="openOrderModal(${s.id}, 0)" class="w-full py-3 bg-gradient-to-r from-teal-500 to-emerald-600 text-white font-bold text-sm rounded-xl hover:from-teal-400 hover:to-emerald-500 transition-all shadow-lg shadow-teal-500/25 active:scale-[0.98]">
            <i class="fa-solid fa-paper-plane mr-2"></i> So'rov yuborish
        </button>`;
    } catch (e) {
        content.innerHTML = '<p class="text-red-400 text-center py-8">Tarmoq xatosi</p>';
    }
}

function closeSellerModal() {
    document.getElementById('seller-modal').classList.add('hidden');
    document.body.style.overflow = '';
}

function openOrderModal(sellerId, productId) {
    document.getElementById('order-seller-id').value = sellerId;
    document.getElementById('order-product-id').value = productId || '';
    document.getElementById('order-modal').classList.remove('hidden');
}

function closeOrderModal() {
    document.getElementById('order-modal').classList.add('hidden');
}

document.getElementById('order-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = e.target.querySelector('button[type="submit"]');
    btn.disabled = true;
    
    try {
        const res = await fetch('/api/optom-api.php', {
            method: 'POST', headers: {'Content-Type':'application/json'},
            body: JSON.stringify({
                action: 'send_order',
                seller_id: parseInt(document.getElementById('order-seller-id').value),
                product_id: parseInt(document.getElementById('order-product-id').value) || 0,
                customer_name: document.getElementById('order-name').value,
                customer_phone: document.getElementById('order-phone').value,
                quantity: parseInt(document.getElementById('order-qty').value) || 1,
                message: document.getElementById('order-message').value,
            })
        });
        const data = await res.json();
        if (data.success) {
            closeOrderModal();
            closeSellerModal();
            // Toast
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 z-[200] px-5 py-3 rounded-2xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 flex items-center gap-2 text-sm font-medium shadow-2xl animate-fade-in-up';
            toast.innerHTML = '<i class="fa-solid fa-circle-check"></i> ' + data.message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 4000);
            document.getElementById('order-form').reset();
        } else {
            alert(data.error || 'Xatolik');
        }
    } catch (e) { alert('Tarmoq xatosi'); }
    finally { btn.disabled = false; }
});

function formatNum(n) { return new Intl.NumberFormat('uz-UZ').format(n); }
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
