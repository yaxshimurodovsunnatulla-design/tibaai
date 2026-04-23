<?php
require_once __DIR__ . '/../api/config.php';
$pageTitle = "Hisobotlar – Yo'qolgan Tovarlar | Tiba AI";
$pageDescription = "Uzum Market do'koningizdagi yo'qolgan, harakatsiz va zarar keltiruvchi tovarlarni aniqlang. AI tahlili bilan samarali qarorlar qabul qiling.";
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="py-12 sm:py-20 relative overflow-hidden">
    <!-- Background -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-red-600/8 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-amber-600/8 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative">
        <!-- Header -->
        <div class="mb-10">
            <a href="/instrumentlar" class="text-gray-500 text-xs hover:text-white transition-colors inline-flex items-center gap-1 mb-4">
                <i class="fa-solid fa-arrow-left"></i> Instrumentlarga qaytish
            </a>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-black text-white mb-3">
                        <i class="fa-solid fa-triangle-exclamation text-amber-500 mr-2"></i> Yo'qolgan <span class="gradient-text">Tovarlar</span>
                    </h1>
                    <p class="text-gray-400 text-lg">Omborda yotib, zarar keltiruvchi tovarlarni AI orqali aniqlang.</p>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                    <div class="relative w-full sm:w-80">
                        <input type="password" id="uzum-api-key" placeholder="Uzum Seller API Key"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-amber-500/50 transition-all pr-10">
                        <button onclick="toggleApiKey()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-white">
                            <i id="eye-icon" class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    <select id="report-period" class="bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-amber-500/50 transition-all w-full sm:w-auto">
                        <option value="7">So'nggi 7 kun</option>
                        <option value="30" selected>So'nggi 30 kun</option>
                        <option value="90">So'nggi 3 oy</option>
                    </select>
                    <button onclick="analyzeProducts()" id="analyze-btn" class="bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-amber-600/20 active:scale-95 whitespace-nowrap w-full sm:w-auto">
                        <i class="fa-solid fa-magnifying-glass-chart mr-2"></i> Tahlil qilish
                    </button>
                </div>
            </div>
        </div>

        <!-- Initial State -->
        <div id="initial-state" class="glass-card p-12 sm:p-16 border border-white/5 text-center">
            <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-amber-500/10 to-red-500/10 border border-amber-500/20 flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-box-open text-amber-400 text-4xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-3">Ombordagi muammolarni aniqlang</h2>
            <p class="text-gray-400 max-w-lg mx-auto mb-8 leading-relaxed">
                Uzum Seller API kalitingizni kiriting va "Tahlil qilish" tugmasini bosing. Tizim sizning ombordagi barcha
                tovarlarni tekshirib, <strong class="text-amber-400">sotilmayotgan</strong>,
                <strong class="text-red-400">zarar keltiruvchi</strong> va
                <strong class="text-white">e'tiborsiz qoldirilgan</strong> tovarlarni aniqlaydi.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-2xl mx-auto">
                <div class="glass-card p-4 border border-red-500/10 bg-red-500/5">
                    <div class="text-red-400 text-2xl mb-2"><i class="fa-solid fa-skull-crossbones"></i></div>
                    <div class="text-xs font-bold text-white">Kritik Xavfli</div>
                    <div class="text-[10px] text-gray-500 mt-1">100+ ta zaxira, 0 ta sotuv</div>
                </div>
                <div class="glass-card p-4 border border-amber-500/10 bg-amber-500/5">
                    <div class="text-amber-400 text-2xl mb-2"><i class="fa-solid fa-exclamation-triangle"></i></div>
                    <div class="text-xs font-bold text-white">Yuqori Xavfli</div>
                    <div class="text-[10px] text-gray-500 mt-1">30+ ta zaxira, past konversiya</div>
                </div>
                <div class="glass-card p-4 border border-blue-500/10 bg-blue-500/5">
                    <div class="text-blue-400 text-2xl mb-2"><i class="fa-solid fa-info-circle"></i></div>
                    <div class="text-xs font-bold text-white">O'rta Xavfli</div>
                    <div class="text-[10px] text-gray-500 mt-1">Kam sotiluvchi, kuzatish kerak</div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loading-state" class="hidden glass-card p-16 border border-white/5 text-center">
            <div class="w-20 h-20 rounded-3xl bg-amber-500/10 flex items-center justify-center mx-auto mb-6 animate-pulse">
                <i class="fa-solid fa-radar fa-spin text-amber-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Tovarlar tahlil qilinmoqda...</h3>
            <p class="text-gray-500 text-sm">Barcha tovarlar va sotuvlar ma'lumotlari tekshirilmoqda</p>
            <div class="mt-6 max-w-xs mx-auto">
                <div class="h-1.5 bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-amber-500 to-orange-500 rounded-full animate-loading-bar"></div>
                </div>
            </div>
        </div>

        <!-- Results -->
        <div id="results-section" class="hidden space-y-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="glass-card p-6 border border-white/5 bg-gradient-to-br from-white/[0.02] to-transparent">
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-widest mb-1">Jami Tovarlar</div>
                    <div id="stat-total" class="text-3xl font-black text-white">0 <span class="text-xs font-normal text-gray-500">ta</span></div>
                </div>
                <div class="glass-card p-6 border border-red-500/10 bg-gradient-to-br from-red-500/[0.03] to-transparent">
                    <div class="text-xs text-red-400 uppercase font-bold tracking-widest mb-1"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Yo'qolgan Tovarlar</div>
                    <div id="stat-lost" class="text-3xl font-black text-red-400">0 <span class="text-xs font-normal text-gray-500">ta</span></div>
                </div>
                <div class="glass-card p-6 border border-amber-500/10 bg-gradient-to-br from-amber-500/[0.03] to-transparent">
                    <div class="text-xs text-amber-400 uppercase font-bold tracking-widest mb-1"><i class="fa-solid fa-coins mr-1"></i> Muzlatilgan Kapital</div>
                    <div id="stat-frozen" class="text-3xl font-black text-amber-400">0 <span class="text-xs font-normal text-gray-500">so'm</span></div>
                </div>
                <div class="glass-card p-6 border border-rose-500/10 bg-gradient-to-br from-rose-500/[0.03] to-transparent">
                    <div class="text-xs text-rose-400 uppercase font-bold tracking-widest mb-1"><i class="fa-solid fa-chart-line-down mr-1"></i> Oylik Zarar (taxm.)</div>
                    <div id="stat-loss" class="text-3xl font-black text-rose-400">0 <span class="text-xs font-normal text-gray-500">so'm</span></div>
                </div>
            </div>

            <!-- Danger Distribution -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div id="danger-critical" class="glass-card p-4 border border-red-500/20 bg-red-500/5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-500/20 flex items-center justify-center">
                            <i class="fa-solid fa-skull-crossbones text-red-400"></i>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-red-400">KRITIK</div>
                            <div class="text-[10px] text-gray-500">Darhol chora ko'rish kerak</div>
                        </div>
                    </div>
                    <span id="count-critical" class="text-2xl font-black text-red-400">0</span>
                </div>
                <div id="danger-high" class="glass-card p-4 border border-amber-500/20 bg-amber-500/5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center">
                            <i class="fa-solid fa-exclamation-triangle text-amber-400"></i>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-amber-400">YUQORI</div>
                            <div class="text-[10px] text-gray-500">Tez orada harakat qiling</div>
                        </div>
                    </div>
                    <span id="count-high" class="text-2xl font-black text-amber-400">0</span>
                </div>
                <div id="danger-medium" class="glass-card p-4 border border-blue-500/20 bg-blue-500/5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
                            <i class="fa-solid fa-info-circle text-blue-400"></i>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-blue-400">O'RTA</div>
                            <div class="text-[10px] text-gray-500">Kuzatib boring</div>
                        </div>
                    </div>
                    <span id="count-medium" class="text-2xl font-black text-blue-400">0</span>
                </div>
            </div>

            <!-- Products List -->
            <div class="glass-card border border-white/5 overflow-hidden">
                <div class="px-6 py-5 border-b border-white/5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-amber-400"></i> Yo'qolgan tovarlar ro'yxati
                    </h3>
                    <div class="flex items-center gap-2">
                        <button onclick="filterProducts('all')" class="filter-btn active px-3 py-1.5 rounded-lg text-xs font-semibold border transition-all" data-filter="all">Hammasi</button>
                        <button onclick="filterProducts('CRITICAL')" class="filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold border transition-all" data-filter="CRITICAL">
                            <i class="fa-solid fa-skull-crossbones text-red-400 mr-1"></i> Kritik
                        </button>
                        <button onclick="filterProducts('HIGH')" class="filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold border transition-all" data-filter="HIGH">
                            <i class="fa-solid fa-exclamation-triangle text-amber-400 mr-1"></i> Yuqori
                        </button>
                        <button onclick="filterProducts('MEDIUM')" class="filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold border transition-all" data-filter="MEDIUM">
                            <i class="fa-solid fa-info-circle text-blue-400 mr-1"></i> O'rta
                        </button>
                    </div>
                </div>
                <div id="products-list" class="divide-y divide-white/[0.03]">
                    <!-- Populated by JS -->
                </div>
                <div id="empty-list" class="hidden py-16 text-center">
                    <i class="fa-solid fa-party-horn text-4xl text-emerald-400 mb-4"></i>
                    <h4 class="text-white font-bold mb-1">Ajoyib! Yo'qolgan tovarlar topilmadi</h4>
                    <p class="text-gray-500 text-sm">Barcha tovarlaringiz sotilmoqda</p>
                </div>
            </div>
        </div>

        <!-- Error State -->
        <div id="error-state" class="hidden glass-card p-12 border border-red-500/20 bg-red-500/5 text-center">
            <i class="fa-solid fa-circle-exclamation text-red-400 text-4xl mb-4"></i>
            <h3 class="text-xl font-bold text-white mb-2">Xatolik yuz berdi</h3>
            <p id="error-message" class="text-gray-400 text-sm mb-6"></p>
            <button onclick="analyzeProducts()" class="px-6 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white text-sm font-semibold hover:bg-white/10 transition-all">
                <i class="fa-solid fa-rotate mr-2"></i> Qayta urinish
            </button>
        </div>
    </div>
</div>

<style>
@keyframes loading-bar {
    0% { width: 0%; margin-left: 0; }
    50% { width: 60%; margin-left: 20%; }
    100% { width: 0%; margin-left: 100%; }
}
.animate-loading-bar { animation: loading-bar 2s ease-in-out infinite; }

.filter-btn {
    background: rgba(255,255,255,0.03);
    border-color: rgba(255,255,255,0.1);
    color: #9ca3af;
}
.filter-btn:hover {
    background: rgba(255,255,255,0.06);
    border-color: rgba(255,255,255,0.2);
    color: white;
}
.filter-btn.active {
    background: rgba(245,158,11,0.1);
    border-color: rgba(245,158,11,0.3);
    color: #f59e0b;
}

.product-card {
    transition: all 0.3s ease;
}
.product-card:hover {
    background: rgba(255,255,255,0.02);
}

.danger-badge-CRITICAL { background: rgba(239,68,68,0.15); color: #f87171; border-color: rgba(239,68,68,0.3); }
.danger-badge-HIGH { background: rgba(245,158,11,0.15); color: #fbbf24; border-color: rgba(245,158,11,0.3); }
.danger-badge-MEDIUM { background: rgba(59,130,246,0.15); color: #60a5fa; border-color: rgba(59,130,246,0.3); }

.solution-card {
    animation: fadeSlideIn 0.3s ease-out;
}
@keyframes fadeSlideIn {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
let allSlowMovers = [];

function toggleApiKey() {
    const input = document.getElementById('uzum-api-key');
    const icon = document.getElementById('eye-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

async function analyzeProducts() {
    const apiKey = document.getElementById('uzum-api-key').value;
    const period = document.getElementById('report-period').value;
    const btn = document.getElementById('analyze-btn');

    if (!apiKey) {
        alert('Iltimos, Uzum Seller API kalitni kiriting');
        return;
    }

    const token = typeof TibaAuth !== 'undefined' ? TibaAuth.getToken() : localStorage.getItem('auth_token');
    if (!token) {
        if (typeof TibaAuth !== 'undefined') TibaAuth.showModal(() => analyzeProducts());
        return;
    }

    // Show loading
    document.getElementById('initial-state').classList.add('hidden');
    document.getElementById('results-section').classList.add('hidden');
    document.getElementById('error-state').classList.add('hidden');
    document.getElementById('loading-state').classList.remove('hidden');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Tahlil...';

    try {
        const response = await fetch('/api/uzum-stats.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-User-Token': token,
            },
            body: JSON.stringify({ api_key: apiKey, period: period }),
        });

        const data = await response.json();

        if (data.error && !data.storage_info) {
            showError(data.error);
            return;
        }

        renderResults(data);
    } catch (err) {
        showError("Tarmoq xatosi. Qaytadan urinib ko'ring.");
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-magnifying-glass-chart mr-2"></i> Tahlil qilish';
    }
}

function showError(msg) {
    document.getElementById('loading-state').classList.add('hidden');
    document.getElementById('error-state').classList.remove('hidden');
    document.getElementById('error-message').textContent = msg;
}

function renderResults(data) {
    document.getElementById('loading-state').classList.add('hidden');
    document.getElementById('results-section').classList.remove('hidden');

    const slowMovers = data.storage_info?.slow_movers || [];
    allSlowMovers = slowMovers;

    const totalProducts = data.debug_info?.all_products_count || 0;
    const frozenCapital = slowMovers.reduce((sum, p) => sum + (p.stock * (p.price || 0)), 0);
    const monthlyLoss = slowMovers.reduce((sum, p) => sum + (p.est_monthly_loss || 0), 0);

    const fmt = (v) => new Intl.NumberFormat('uz-UZ').format(Math.round(v));

    // Stats
    document.getElementById('stat-total').innerHTML = `${fmt(totalProducts)} <span class="text-xs font-normal text-gray-500">ta</span>`;
    document.getElementById('stat-lost').innerHTML = `${fmt(slowMovers.length)} <span class="text-xs font-normal text-gray-500">ta</span>`;
    document.getElementById('stat-frozen').innerHTML = `${fmt(frozenCapital)} <span class="text-xs font-normal text-gray-500">so'm</span>`;
    document.getElementById('stat-loss').innerHTML = `${fmt(monthlyLoss)} <span class="text-xs font-normal text-gray-500">so'm</span>`;

    // Danger counts
    const critical = slowMovers.filter(p => p.danger_level === 'CRITICAL').length;
    const high = slowMovers.filter(p => p.danger_level === 'HIGH').length;
    const medium = slowMovers.filter(p => p.danger_level === 'MEDIUM').length;
    document.getElementById('count-critical').textContent = critical;
    document.getElementById('count-high').textContent = high;
    document.getElementById('count-medium').textContent = medium;

    // Render products
    renderProductList(slowMovers);
}

function renderProductList(products) {
    const list = document.getElementById('products-list');
    const empty = document.getElementById('empty-list');
    const fmt = (v) => new Intl.NumberFormat('uz-UZ').format(Math.round(v));

    if (products.length === 0) {
        list.innerHTML = '';
        empty.classList.remove('hidden');
        return;
    }

    empty.classList.add('hidden');

    list.innerHTML = products.map((p, i) => {
        const dangerIcons = {
            'CRITICAL': '<i class="fa-solid fa-skull-crossbones text-red-400"></i>',
            'HIGH': '<i class="fa-solid fa-exclamation-triangle text-amber-400"></i>',
            'MEDIUM': '<i class="fa-solid fa-info-circle text-blue-400"></i>',
        };
        const dangerLabels = {
            'CRITICAL': 'Kritik',
            'HIGH': 'Yuqori',
            'MEDIUM': "O'rta",
        };
        const dangerColors = {
            'CRITICAL': 'red',
            'HIGH': 'amber',
            'MEDIUM': 'blue',
        };
        const color = dangerColors[p.danger_level] || 'gray';

        return `
            <div class="product-card px-6 py-5" data-danger="${p.danger_level}">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-start gap-4 flex-1 min-w-0">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-${color}-500/10 border border-${color}-500/20 flex items-center justify-center text-lg">
                            ${dangerIcons[p.danger_level]}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm font-bold text-white truncate max-w-[300px] block">${escapeHtml(p.title)}</span>
                                <span class="danger-badge-${p.danger_level} px-2 py-0.5 rounded-full text-[9px] font-bold uppercase border flex-shrink-0">
                                    ${dangerLabels[p.danger_level]}
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1.5 text-xs text-gray-500">
                                <span><i class="fa-solid fa-box text-gray-600 mr-1"></i> Zaxira: <b class="text-white">${fmt(p.stock)}</b> ta</span>
                                <span><i class="fa-solid fa-tag text-gray-600 mr-1"></i> Narx: <b class="text-white">${fmt(p.price)}</b> so'm</span>
                                ${p.conversion > 0 ? `<span><i class="fa-solid fa-chart-simple text-gray-600 mr-1"></i> Konversiya: <b class="text-${p.conversion < 1 ? 'red' : 'emerald'}-400">${p.conversion}%</b></span>` : ''}
                                <span><i class="fa-solid fa-fire text-gray-600 mr-1"></i> Oylik zarar: <b class="text-${color}-400">${fmt(p.est_monthly_loss)}</b> so'm</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <button onclick="toggleSolution(this, ${i})" class="px-4 py-2 rounded-xl bg-${color}-500/10 border border-${color}-500/20 text-${color}-400 text-xs font-bold hover:bg-${color}-500/20 transition-all flex items-center gap-2">
                            <i class="fa-solid fa-lightbulb"></i>
                            <span>AI Maslahat</span>
                            <i class="fa-solid fa-chevron-down text-[8px] transition-transform solution-arrow"></i>
                        </button>
                    </div>
                </div>
                <div id="solution-${i}" class="hidden mt-4 ml-0 sm:ml-16">
                    <div class="solution-card glass-card p-4 border border-${color}-500/10 bg-${color}-500/[0.03]">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-${color}-500/10 flex items-center justify-center text-${color}-400 flex-shrink-0 mt-0.5">
                                <i class="fa-solid fa-robot"></i>
                            </div>
                            <div class="text-sm text-gray-300 leading-relaxed">
                                ${escapeHtml(p.solution)}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function toggleSolution(btn, index) {
    const el = document.getElementById(`solution-${index}`);
    const arrow = btn.querySelector('.solution-arrow');
    if (el.classList.contains('hidden')) {
        el.classList.remove('hidden');
        arrow.style.transform = 'rotate(180deg)';
    } else {
        el.classList.add('hidden');
        arrow.style.transform = '';
    }
}

function filterProducts(level) {
    // Update active button
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.filter === level);
    });

    if (level === 'all') {
        renderProductList(allSlowMovers);
    } else {
        renderProductList(allSlowMovers.filter(p => p.danger_level === level));
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Check for saved API key
document.addEventListener('DOMContentLoaded', () => {
    // Check if coming from sales-analytics (shared key)
    const savedKey = sessionStorage.getItem('uzum_api_key');
    if (savedKey) {
        document.getElementById('uzum-api-key').value = savedKey;
    }
});
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
