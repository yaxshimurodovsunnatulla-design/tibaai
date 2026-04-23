<?php
require_once __DIR__ . '/../api/config.php';
$pageTitle = 'Sotuvlar Analitikasi – Tiba AI';
$pageDescription = 'Sotuvlaringizni tahlil qiling va biznesingiz o\'sishini kuzatib boring.';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
@media print {
    html, body { 
        background: white !important; 
        color: black !important; 
        display: block !important; 
        height: auto !important; 
        min-height: auto !important;
        overflow: visible !important;
    }
    /* Hide top nav if any, sidebar, api inputs, and buttons */
    nav, header, .print-hide, aside, button, footer { display: none !important; }
    
    /* Convert glass cards to clean printable borders */
    .glass-card {
        background: white !important;
        border: 1px solid #ccc !important;
        box-shadow: none !important;
        color: black !important;
        break-inside: avoid;
    }
    
    /* Re-color text gradients or white elements to printable dark tones */
    .text-white, .text-gray-300, .text-gray-400 { color: #333 !important; }
    .text-emerald-400 { color: #059669 !important; font-weight: bold; }
    .text-rose-400 { color: #e11d48 !important; font-weight: bold; }
    .text-amber-400, .text-amber-500 { color: #d97706 !important; font-weight: bold; }
    .text-blue-400 { color: #2563eb !important; font-weight: bold; }
    
    /* Borders and transparent backgrounds */
    .bg-black\/20, .bg-white\/5, .bg-[rgba(255,255,255,0.05)], .bg-emerald-500\/5, .bg-blue-500\/[0.02] {
        background-color: #f9fafb !important;
        border: 1px solid #e5e7eb !important;
    }
    
    .border-white\/5, .border-white\/10 { border-color: #e5e7eb !important; }
    
    /* Ensure charts fit */
    canvas { max-width: 100% !important; height: auto !important; }
}
</style>

<div class="py-12 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6 print-hide">
            <div>
                <h1 class="text-3xl sm:text-4xl font-black text-white mb-3">
                    <i class="fa-solid fa-chart-line text-emerald-500 mr-2"></i> Sotuvlar <span class="gradient-text">Analitikasi</span>
                </h1>
                <p class="text-gray-400 text-lg">Uzum Market do'koningizni API orqali tahlil qiling.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                <div class="relative w-full sm:w-80">
                    <input type="password" id="uzum-api-key" value="eP/1k3BJbWnfgvYSxE21TYRs06tgbnQegiPVkQlXxXg=" placeholder="Uzum Seller API Key" 
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500/50 transition-all pr-10">
                    <button onclick="toggleApiKey()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-white">
                        <i id="eye-icon" class="fa-solid fa-eye"></i>
                    </button>
                </div>
                <select id="analytics-period" class="bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-emerald-500/50 transition-all w-full sm:w-auto">
                    <option value="7">So'nggi 7 kun</option>
                    <option value="30" selected>So'nggi 30 kun</option>
                    <option value="90">So'nggi 3 oy</option>
                </select>
                <button onclick="syncUzumData()" id="sync-btn" class="bg-emerald-600 hover:bg-emerald-500 text-white px-6 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-emerald-600/20 active:scale-95 whitespace-nowrap w-full sm:w-auto">
                    <i class="fa-solid fa-sync-alt mr-2"></i> Tahlil qilish
                </button>
            </div>
        </div>

        <div id="analytics-content" class="opacity-100 transition-opacity duration-500">
            <!-- Stats Cards Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-10">
                <div class="glass-card p-6 border border-white/5 bg-gradient-to-br from-white/[0.02] to-transparent">
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-widest mb-1">Umumiy Sotuv</div>
                    <div id="stat-total-sales" class="text-3xl font-black text-white">0 <span class="text-xs font-normal text-gray-500">so'm</span></div>
                </div>
                
                <div class="glass-card p-6 border border-white/5 bg-gradient-to-br from-white/[0.02] to-transparent">
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-widest mb-1">Buyurtmalar</div>
                    <div id="stat-orders" class="text-3xl font-black text-white">0 <span class="text-xs font-normal text-gray-500">ta</span></div>
                </div>

                <div class="glass-card p-6 border border-white/5 bg-gradient-to-br from-white/[0.02] to-transparent">
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-widest mb-1">Xarajatlar</div>
                    <div id="stat-expenses" class="text-3xl font-black text-white">0 <span class="text-xs font-normal text-gray-500">so'm</span></div>
                </div>

                <div class="glass-card p-6 border border-white/5 bg-gradient-to-br from-white/[0.02] to-transparent">
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-widest mb-1">Qaytarilgan</div>
                    <div id="stat-returns" class="text-3xl font-black text-red-400">0 <span class="text-xs font-normal text-gray-500">so'm</span></div>
                </div>

                <div class="glass-card p-6 border border-white/5 bg-gradient-to-br from-white/[0.02] to-transparent">
                    <div class="text-xs text-gray-500 uppercase font-bold tracking-widest mb-1">Sof Foyda</div>
                    <div id="stat-profit" class="text-3xl font-black text-emerald-400">0 <span class="text-xs font-normal text-gray-500">so'm</span></div>
                </div>
            </div>

            <!-- Deep Analysis Row (Secondary Stats) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="glass-card p-4 border border-white/5 flex items-center justify-between">
                    <div class="text-sm text-gray-400">O'rtacha chek</div>
                    <div id="stat-avg-check" class="text-lg font-bold text-white">0</div>
                </div>
                <div class="glass-card p-4 border border-white/5 flex items-center justify-between">
                    <div class="text-sm text-gray-400">Xarajat ulushi</div>
                    <div id="stat-expense-ratio" class="text-lg font-bold text-amber-500">0%</div>
                </div>
                <div class="glass-card p-4 border border-white/5 flex items-center justify-between">
                    <div class="text-sm text-gray-400">Qaytarish ko'rsatkichi</div>
                    <div id="stat-return-rate" class="text-lg font-bold text-red-500">0%</div>
                </div>
            </div>

            <!-- Main Analytics Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                <!-- Main Chart Area -->
                <div class="lg:col-span-2 glass-card p-6 sm:p-8 border border-white/5">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-bold text-white">Sotuv dinamikasi</h3>
                        <div id="chart-legend" class="flex items-center gap-4 text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-1 rounded-full bg-emerald-500"></span>
                                <span class="text-gray-400">Daromad</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-1 rounded-full bg-indigo-500"></span>
                                <span class="text-gray-400">Buyurtmalar</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="relative h-[350px]">
                        <canvas id="salesChart"></canvas>
                        <div id="chart-loader" class="absolute inset-0 flex items-center justify-center bg-black/20 backdrop-blur-sm hidden">
                             <div class="flex flex-col items-center gap-3">
                                <i class="fa-solid fa-circle-notch fa-spin text-3xl text-emerald-500"></i>
                                <span class="text-xs font-medium text-gray-400">Ma'lumotlar yuklanmoqda...</span>
                             </div>
                        </div>
                    </div>
                </div>

                <!-- Side Cards -->
                <div class="space-y-6">
                    <!-- Status Breakdown -->
                    <div class="glass-card p-6 border border-white/5">
                        <h3 class="text-lg font-bold text-white mb-4">Holatlar tahlili</h3>
                        <div id="status-container" class="space-y-3">
                            <!-- Populated by JS -->
                        </div>
                    </div>

                    <!-- Shops List -->
                    <div class="glass-card p-6 border border-white/5">
                        <h3 class="text-lg font-bold text-white mb-4">Do'konlar</h3>
                        <div id="shops-list" class="space-y-3">
                            <div class="text-center py-4 text-xs text-gray-500">API kalit kiritilmadi</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Products & Returns Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                <!-- Top Products -->
                <div class="glass-card p-6 border border-white/5">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-white"><i class="fa-solid fa-crown text-amber-500 mr-2"></i> TOP 10 Mahsulot</h3>
                        <span class="text-xs text-gray-500">Daromad bo'yicha</span>
                    </div>
                    <div id="top-products-list" class="space-y-4">
                        <!-- Populated by JS -->
                    </div>
                </div>

                <!-- Returns & Refunds -->
                <div class="glass-card p-6 border border-white/5">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-white"><i class="fa-solid fa-undo text-red-500 mr-2"></i> Qaytarish sabablari</h3>
                        <span id="total-return-count" class="text-xs text-gray-500">Jami: 0 ta</span>
                    </div>
                    <div id="return-causes-list" class="space-y-4">
                        <!-- Populated by JS -->
                    </div>
                </div>
            </div>
            </div>

        </div>
        
        
        <div id="ai-advisor" class="glass-card p-8 border border-white/5 bg-emerald-500/5 border-emerald-500/10 hidden animate-fade-in">
            <div class="flex flex-col md:flex-row items-center gap-6">
                <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-3xl text-emerald-400 flex-shrink-0">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white mb-2">Tiba AI Maslahatchi</h3>
                    <div id="ai-recommendation" class="text-gray-400 text-sm leading-relaxed">
                        Sotuvlar tahlil qilinmoqda...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let salesChart = null;

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

async function syncUzumData() {
    const apiKey = document.getElementById('uzum-api-key').value;
    const period = document.getElementById('analytics-period').value;
    const btn = document.getElementById('sync-btn');
    const loader = document.getElementById('chart-loader');
    
    if (!apiKey) {
        alert('Iltimos, API kalitni kiriting');
        return;
    }

    try {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Yuklanmoqda...';
        loader.classList.remove('hidden');

        const response = await fetch('/api/uzum-stats.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-User-Token': TibaAuth.getToken()
            },
            body: JSON.stringify({ api_key: apiKey, period: period })
        });

        const data = await response.json();

        if (data.error && !data.shops) {
            alert(data.error);
        } else {
            if (data.order_fetch_error) {
                console.warn('Orders error:', data.order_fetch_error);
                // Optionally show a subtler notification
            }
            updateUI(data);
        }
    } catch (error) {
        console.error('Sync error:', error);
        alert('Ma\'lumotlarni olishda xatolik yuz berdi');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-sync-alt mr-2"></i> Tahlil qilish';
        loader.classList.add('hidden');
    }
}

function updateUI(data) {
    const format = (v) => new Intl.NumberFormat('uz-UZ').format(Math.round(v));
    const formatFull = (v) => new Intl.NumberFormat('uz-UZ').format(Math.round(v)) + " so'm";
    
    
    // Update main stats
    document.getElementById('stat-total-sales').innerHTML = `${format(data.total_sales)} <span class="text-xs font-normal text-gray-500">so'm</span>`;
    document.getElementById('stat-orders').innerHTML = `${format(data.order_count)} <span class="text-xs font-normal text-gray-500">ta</span>`;
    document.getElementById('stat-expenses').innerHTML = `${format(data.total_expenses)} <span class="text-xs font-normal text-gray-500">so'm</span>`;
    document.getElementById('stat-returns').innerHTML = `${format(data.returns.value)} <span class="text-xs font-normal text-gray-500">so'm</span>`;
    
    const profitEl = document.getElementById('stat-profit');
    profitEl.innerHTML = `${format(data.net_profit)} <span class="text-xs font-normal text-gray-500">so'm</span>`;
    profitEl.className = data.net_profit >= 0 ? 'text-3xl font-black text-emerald-400' : 'text-3xl font-black text-red-400';

    // Update secondary stats
    const avgCheck = data.order_count > 0 ? data.total_sales / data.order_count : 0;
    const expRatio = data.total_sales > 0 ? (data.total_expenses / data.total_sales) * 100 : 0;
    const returnRate = data.order_count > 0 ? (data.returns.qty / (data.order_count + data.returns.qty)) * 100 : 0;
    
    document.getElementById('stat-avg-check').innerText = formatFull(avgCheck);
    document.getElementById('stat-expense-ratio').innerText = expRatio.toFixed(1) + '%';
    document.getElementById('stat-return-rate').innerText = returnRate.toFixed(1) + '%';

    // Status Breakdown
    const statusIcons = {
        'PROCESSING': 'fa-hourglass-half text-amber-400',
        'TO_WITHDRAW': 'fa-money-bill-wave text-emerald-400',
        'CANCELED': 'fa-times-circle text-red-500',
        'PARTIALLY_CANCELLED': 'fa-minus-circle text-amber-500'
    };
    const statusLabels = {
        'PROCESSING': 'Jarayonda',
        'TO_WITHDRAW': 'To\'lovga tayyor',
        'CANCELED': 'Bekor qilindi',
        'PARTIALLY_CANCELLED': 'Qisman qaytarildi'
    };

    document.getElementById('status-container').innerHTML = Object.entries(data.status_breakdown).map(([status, count]) => {
        if (count === 0) return '';
        const icon = statusIcons[status] || 'fa-info-circle';
        const label = statusLabels[status] || status;
        return `
            <div class="flex items-center justify-between text-sm py-1 border-b border-white/5 last:border-0">
                <div class="flex items-center gap-3">
                    <i class="fa-solid ${icon} w-4 text-center"></i>
                    <span class="text-gray-400 text-xs">${label}</span>
                </div>
                <span class="text-white font-bold">${count}</span>
            </div>
        `;
    }).join('');

    // Update Shops List
    document.getElementById('shops-list').innerHTML = data.shops.map(shop => `
        <div class="flex items-center gap-3 p-2 rounded-xl bg-white/5 border border-white/5">
            <div class="w-6 h-6 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-400 font-bold text-[10px]">
                ${shop.title.charAt(0)}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-[11px] font-bold text-white truncate">${shop.title}</div>
            </div>
            <i class="fa-solid fa-check-circle text-emerald-500 text-[10px]"></i>
        </div>
    `).join('');

    // Top Products
    const maxSales = Math.max(...data.top_products.map(p => p.sales), 1);
    document.getElementById('top-products-list').innerHTML = data.top_products.map(p => {
        const percent = (p.sales / maxSales) * 100;
        return `
            <div class="space-y-2">
                <div class="flex items-center gap-4">
                    <img src="${p.image}" class="w-10 h-10 rounded-lg object-cover bg-white/5" onerror="this.src='/assets/img/placeholder.png'">
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold text-white truncate">${p.title}</div>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-[10px] text-gray-500">${format(p.qty)} ta sotildi</span>
                            <span class="text-[10px] text-emerald-400 font-bold">${format(p.sales)} so'm</span>
                        </div>
                    </div>
                </div>
                <div class="h-1 w-full bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full" style="width: ${percent}%"></div>
                </div>
            </div>
        `;
    }).join('');

    // Returns Analysis
    document.getElementById('total-return-count').innerText = `Jami: ${data.returns.qty} ta`;
    const causes = Object.entries(data.returns.causes);
    if (causes.length === 0) {
        document.getElementById('return-causes-list').innerHTML = `<div class="text-center py-10 text-gray-500 text-sm">Hozircha qaytarishlar yo'q ✨</div>`;
    } else {
        document.getElementById('return-causes-list').innerHTML = causes.map(([cause, count]) => {
            const causePercent = (count / data.returns.qty) * 100;
            return `
                <div class="space-y-1">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-400 truncate pr-4">${cause}</span>
                        <span class="text-white font-bold">${count} ta</span>
                    </div>
                    <div class="h-1.5 w-full bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-500 rounded-full" style="width: ${causePercent}%"></div>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Update Chart
    updateChart(data.chart_data);

    // AI Advisor - Expanded Deep Analysis
    const advisor = document.getElementById('ai-advisor');
    const recText = document.getElementById('ai-recommendation');
    advisor.classList.remove('hidden');
    
    if (data.order_fetch_error) {
        recText.innerHTML = `
            <div class="flex items-center gap-3 text-amber-400 mb-4">
                <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                <h4 class="font-bold">Ma'lumotlarni yuklashda cheklov</h4>
            </div>
            <p class="text-gray-400 text-sm">Savdo ma'lumotlarini to'liq tahlil qilib bo'lmadi: <b>${data.order_fetch_error}</b>. 
            Bu odatda API kalitda "Finance" huquqi yo'qligi sababli yuz beradi. Iltimos, Uzum Seller panelida ruxsatlarni tekshiring.</p>
        `;
        return;
    }

    if (data.total_sales > 0 || data.storage_info.slow_movers.length > 0) {
        const avgCheck = data.total_sales / data.order_count;
        const expRatio = (data.total_expenses / data.total_sales) * 100;
        const returnRate = (data.returns.qty / (data.order_count + data.returns.qty)) * 100;
        const topProd = data.top_products[0] || null;
        const slowMovers = data.storage_info.slow_movers || [];

        let pairsHtml = '';
        if (data.top_products && data.top_products.length > 0 && slowMovers && slowMovers.length > 0) {
            const limit = Math.min(3, data.top_products.length, slowMovers.length);
            for(let i = 0; i < limit; i++) {
                pairsHtml += `
                    <div class="flex items-center gap-2 bg-white/5 p-2 rounded border border-white/5">
                        <div class="flex-1 truncate text-emerald-400 font-bold text-[10px]" title="${data.top_products[i].title}">${data.top_products[i].title} <span class="text-gray-500 font-normal">(Top)</span></div>
                        <i class="fa-solid fa-plus text-gray-600 text-[8px]"></i>
                        <div class="flex-1 truncate text-amber-500/80 font-bold text-[10px]" title="${slowMovers[i].title}">${slowMovers[i].title} <span class="text-gray-500 font-normal">(Stokda)</span></div>
                    </div>
                `;
            }
        } else {
            pairsHtml = `<div class="text-[10px] italic text-gray-500 bg-white/5 p-2 rounded">Buning uchun kamida bitta yaxshi sotilayotgan va bitta qolib ketgan tovar bo'lishi kerak.</div>`;
        }

        let reportHtml = `
            <div class="space-y-10 mt-4">
                <!-- 1. Strategik Xulosa -->
                <div class="bg-white/5 rounded-2xl p-6 border border-white/10">
                    <h4 class="text-white font-bold mb-4 flex items-center gap-2 text-lg">
                        <i class="fa-solid fa-handshake-angle text-emerald-400"></i> Assalomu alaykum! Men sizning maxsus AI tahlilchingizman.
                    </h4>
                    <div class="text-sm text-gray-300 leading-relaxed space-y-4">
                        <p>
                            Keling, do'koningizdagi holatni birgalikda muhokama qilamiz. Hozirda o'rtacha bitta mijoz do'koningizda <b>${formatFull(avgCheck)}</b> miqdorida pul tashlab ketyapti va davr mobaynida jami <b>${data.order_count} ta</b> xarid amalga oshgan. Asosiy maqsadimiz — shu o'rtacha chekni va umumiy savdo hajmini izchil ravishda yuqoriga ko'tarish!
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- 2. Har bir mahsulot bo'yicha chuqur tahlil -->
                    <div class="space-y-6">
                        <h4 class="text-emerald-400 font-bold flex items-center gap-2 uppercase text-xs tracking-widest border-b border-emerald-500/20 pb-2">
                            <i class="fa-solid fa-rocket"></i> Lokomotiv tovarlar (Sizning asosiy kuchingiz)
                        </h4>
                        <div class="space-y-4">
                            ${data.top_products.slice(0, 3).map((p, index) => {
                                // Generate individual, specific advice based on rank and data
                                let aiAdvice = "";
                                if (index === 0) {
                                    aiAdvice = `<b>Shubhasiz Lider:</b> Bu "Oltin g'oz" jami daromadingizning eng katta qismini olib kelyapti. Mijozlar e'tibori to'liq shu tovardaligidan foydalanib, buning sahifasiga zudlik bilan boshqa arzonroq tovaringizni "Tavsiya" ko'rinishida yoki "Birga olinadi" deya sozlang!`;
                                } else if (index === 1) {
                                    aiAdvice = `<b>Potentsial VIP:</b> Bu tovar tez ommalashmoqda. Sotuvlar zo'r, ammo konversiyani yana oshirish uchun qisqa videodemonstratsiya qo'shish yoki narxni raqobatchilardan 5% farq bilan (yoki aksiyaga qo'yib) yashirib qo'yish uning hajmini 2 barobar oshirishi aniq.`;
                                } else {
                                    aiAdvice = `<b>Barqaror Qahramon:</b> Mijozlar bu tovarga doimiy ishonch bildirgan ishchi otingiz. Uning zaxirasi umuman 0 ga tushishiga ruxsat bermang. Omborda doim +50 ta zapas turishi lozim deb tasdiqladim, shunday qiling!`;
                                }
                                
                                return `
                                <div class="bg-gradient-to-br from-emerald-500/5 to-transparent p-4 rounded-xl border border-emerald-500/10 space-y-3">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1 pr-2">
                                            <span class="text-white font-bold text-xs truncate max-w-[200px] block">${p.title}</span>
                                            <span class="text-[9px] text-gray-400 block mt-1">Daromad ulushi: <b>${formatFull(p.revenue)}</b> (${p.qty} ta sotilgan)</span>
                                        </div>
                                        <span class="text-[10px] px-2 py-0.5 bg-emerald-500/20 text-emerald-400 rounded-full font-bold">#${index+1} O'rinda</span>
                                    </div>
                                    <div class="text-[11px] text-gray-300 bg-black/20 p-2 rounded-lg leading-relaxed border border-emerald-500/20">
                                        <i class="fa-solid fa-lightbulb text-emerald-400 mr-1"></i> ${aiAdvice}
                                    </div>
                                </div>
                                `;
                            }).join('')}
                        </div>
                    </div>

                    <!-- Umumiy Xarajatlar va Bekor bo'lishlar -->
                    <div class="space-y-6">
                        <h4 class="text-rose-400 font-bold flex items-center gap-2 uppercase text-xs tracking-widest border-b border-rose-500/20 pb-2">
                            <i class="fa-solid fa-chart-pie"></i> Xarajatlar va Yo'qotishlar Tahlili
                        </h4>
                        <div class="space-y-4">
                            <!-- Xarajatlar block -->
                            <div class="bg-white/[0.03] p-4 rounded-xl border border-white/5 space-y-3">
                                <div class="flex justify-between items-start">
                                    <span class="text-white font-bold text-xs">Jami tasdiqlangan xarajatlar</span>
                                    <span class="text-[10px] px-2 py-0.5 bg-rose-500/10 text-rose-400 rounded-full font-bold">${expRatio.toFixed(1)}% daromaddan</span>
                                </div>
                                <div class="text-[20px] font-black text-rose-400">
                                    ${formatFull(data.total_expenses)}
                                </div>
                                <div class="text-[11px] text-gray-400 leading-relaxed">
                                    Ushbu summaga Uzum tomonidan ayirib qolingan komissiya tushumlari, logistika (FBO/FBS) va rasmiylashtirilgan xarajatlar to'plami kiradi. Narx belgilayotganingizda, doim ana shu tahminiy yechib olish foizdan tashqari marjani ushlab qolishingiz shart.
                                </div>
                            </div>
                            
                            <div class="bg-white/[0.03] p-4 rounded-xl border border-white/5 space-y-3">
                                <div class="flex justify-between items-start">
                                    <span class="text-white font-bold text-xs">Bekor qilingan buyurtmalar (Otmenlar)</span>
                                    <span class="text-[10px] px-2 py-0.5 bg-amber-500/10 text-amber-500 rounded-full font-bold">${((data.status_breakdown.CANCELED || 0) / (data.order_count || 1) * 100).toFixed(1)}% umumiy miqdordan</span>
                                </div>
                                <div class="text-[11px] text-gray-400 leading-relaxed">
                                    Buyurtmalarning ayni shu qismi haqiqiy potentsial xarid bo'la turib, yetib bormasdan yoki yetib borgach qaytarilmoqda. Aynan shu yo'qotishlarni pasaytirish - orqaga tortayotgan ulkan keraksiz "quruq" logistika tollovlarini to'xtatish demakdir.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. AI Chuqur Strategiya -->
                <div class="glass-card p-8 border-blue-500/20 bg-blue-500/[0.02] shadow-xl relative overflow-hidden">
                    <!-- Decor -->
                    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
                    
                    <h4 class="text-white text-lg font-bold mb-6 flex items-center gap-3 border-b border-blue-500/20 pb-4">
                        <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center">
                            <i class="fa-solid fa-brain text-blue-400"></i>
                        </div>
                        Tiba AI: Savdoni Oshirish Rejasi
                    </h4>
                    
                    <div class="space-y-6 text-[13px] text-gray-300 leading-relaxed relative z-10">
                        <p>
                            Sizning tahlil qilingan ko'rsatkichlaringiz (O'rtacha chekingiz <b>${formatFull(avgCheck)}</b>, sof foyda marjasi va kundalik buyurtmalar soni) asosida shuni aytishim mumkinki, sizning biznes strukturangizda faollashish potentsiali birmuncha yuqori. Men o'z ishimning mutaxassisi sifatida, sizga asosan xarajat qismiga emas, "Kirish" oqimini kattalashtirishga alohida e'tibor qaratishingizni tavsiya qilaman.
                            Quyida do'kon aylanmasini kengaytirish bo'yicha eng kuchli usullarim:
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div class="bg-black/20 p-5 rounded-xl border border-white/5 space-y-3 relative overflow-hidden group hover:border-blue-500/30 transition-colors">
                                <h5 class="text-blue-400 font-bold text-sm flex items-center gap-2"><i class="fa-solid fa-arrows-to-circle"></i> 1. Kross-Selling siri (Aralash savdo)</h5>
                                <p class="text-xs text-gray-400 leading-relaxed">
                                    Sotilayotgan buyurtmalarga diqqat qiling: Sizda alohida ko'p o'tadigan mahsulotlaringiz bor (masalan, ${topProd ? topProd.title.substring(0, 30)+'...' : 'eng xariorgir tovaringiz'}). 
                                    Do'koningiz sahifasida ushbu tovarlarning mashhurligidan foydalaning. Yangi, alohida "To'plangan (Nabor)" tovar kartochkasini yarating va ichiga ko'p sotilgan tovaringiz bilan yana narxi pastroq / omborda o'tirishni boshlagan mahsulotlaringizdan birini chiroyli ko'rinishda birlashtiring. Bu mijozga qiymat (aksiya illyuziyasini) taqdim etadi va o'rtacha chekingizni kamida 30-40% ga keskin ko'taradi.
                                </p>
                            </div>

                            <div class="bg-black/20 p-5 rounded-xl border border-white/5 space-y-3 relative overflow-hidden group hover:border-emerald-500/30 transition-colors">
                                <h5 class="text-emerald-400 font-bold text-sm flex items-center gap-2"><i class="fa-solid fa-image"></i> 2. Konversiya vizuali — Eng katta o'sish nuqtasi</h5>
                                <p class="text-xs text-gray-400 leading-relaxed">
                                    Bekor qilinishlar va qaytarilishlar (vozvrat) faqatgina reytingni emas, sizning qimmatli foydangizni ham yeydi. Uzum'da 95% holatda mijoz "Rasmga qarab xarid qilgan, lekin reallikka mos kelmagani uchun qaytargan" ekanligi sir emas.
                                    Top-tovarlaringiz suratini hozirdanoq sifatliroq dizaynli Infografikaga (yozuvlar tushirilgan rasm) aynantirishni o'ylab ko'ring. Barcha xarid xususiyatlarini rasmlarda taqdim qilsangiz, bekor bo'lishlar qisqaradi va odamlar mahsulotingizga aynan professionallik tufayli to'lov qilishga kelisha boshlaydi.
                                </p>
                            </div>

                            <div class="bg-black/20 p-5 rounded-xl border border-white/5 space-y-3 md:col-span-2 relative hover:border-amber-500/30 transition-colors">
                                <h5 class="text-amber-400 font-bold text-sm flex items-center gap-2"><i class="fa-solid fa-bullhorn"></i> 3. Narx poygasida qatnashmang - Siz Brend soting!</h5>
                                <p class="text-xs text-gray-400 leading-relaxed">
                                    Agar raqobatchilar narxni pasaytirayotganini sezsangiz, hech qachon ularga ergashib, narxni o'zlashtirmang. Bu yo'l qachondur umuman foydasiz holatga tushirishi aniq. Buning o'rniga "Sifatni sotish" usuliga o'ting: tovar kartochkasiga qisqa (videolar), emotsional tasvirlar, haqiqiy kadrlar va "Mustahkam qadoq" sifatini taqdim etuvchi so'zlarni kiriting. Xaridorlar ishonch uchun ko'proq pul to'lashga doimo tayyor tushishadi. Narxni pasaytirishni emas, Mahsulotning "Taqdimoti"ni qimmatlashtirishni tanlang!
                                </p>
                            </div>

                            <div class="bg-black/20 p-5 rounded-xl border border-white/5 space-y-3 md:col-span-2 relative hover:border-violet-500/30 transition-colors">
                                <h5 class="text-violet-400 font-bold text-sm flex items-center gap-2"><i class="fa-solid fa-people-arrows"></i> 4. Aql Bilan Kross-Selling: O'lik tovarlarni tiriltirish</h5>
                                <div class="text-xs text-gray-400 leading-relaxed space-y-2">
                                    <p>Do'konda shunday strategiya borki, u orqali biz ham ko'p daromad qilamiz, ham ombordagi "o'lik" tovarlardan osongina qutulamiz. Sir nimada? Aynan <b>Zo'r sotilayotgan tovarizni, hech kim olmayotgan tovarga</b> sherik qilish!</p>
                                    <p>Siz uchun tayyor maxsus takliflarim (ularni "To'plam / Sovg'a" ko'rinishida qo'shib reklamani yoqing):</p>
                                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                                        ${pairsHtml}
                                    </div>
                                    <p class="mt-2 text-[10px] text-gray-500 italic">*Mijoz bu narxga rozi bo'ladi, chunki siz unga kerakli tovar yonida ikkinchisini ham chegirma narxda yoxud bepul (ozgincha pul qo'shib) taklif qilyapsiz.*</p>
                                </div>
                            </div>
                        </div>
                    </div>
            
            <div class="mt-8 pt-6 border-t border-white/5 text-center flex flex-col sm:flex-row justify-center items-center gap-4 print-hide">
                <button onclick="window.print()" class="text-xs text-gray-500 hover:text-white transition-colors bg-white/5 px-4 py-2.5 rounded-lg border border-white/10 flex items-center gap-2">
                    <i class="fa-solid fa-file-pdf"></i> PDF qilib saqlash (Print)
                </button>
                <div class="text-[10px] text-gray-500 max-w-[200px] text-left hidden sm:block">
                    *Yuklash oynasi ochilganda maqsaddan "Save as PDF" ni tanlang.
                </div>
                <button onclick="syncUzumData()" class="text-xs text-emerald-400 hover:text-emerald-300 transition-colors bg-emerald-500/5 px-4 py-2.5 rounded-lg border border-emerald-500/10 flex items-center gap-2">
                    <i class="fa-solid fa-rotate mr-1"></i> Qayta tahlil qilish
                </button>
            </div>
        `;
        recText.innerHTML = reportHtml;
    } else {
        recText.innerHTML = `
            <div class="flex flex-col items-center py-6 text-center">
                <i class="fa-solid fa-chart-pie text-4xl text-gray-600 mb-4"></i>
                <h4 class="text-white font-bold mb-2">Hozircha tahlil uchun ma'lumot yetarli emas</h4>
                <p class="text-gray-400 text-sm max-w-md">Do'koningizda sotuvlar amalga oshirilgach, biz ularni tahlil qilib, sizga biznesingizni o'stirish bo'yicha batafsil tavsiyalar beramiz.</p>
            </div>
        `;
    }
}

function updateChart(data) {
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    if (salesChart) {
        salesChart.destroy();
    }

    const gradientEmerald = ctx.createLinearGradient(0, 0, 0, 350);
    gradientEmerald.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
    gradientEmerald.addColorStop(1, 'rgba(16, 185, 129, 0)');
    
    const gradientIndigo = ctx.createLinearGradient(0, 0, 0, 350);
    gradientIndigo.addColorStop(0, 'rgba(79, 70, 229, 0.1)');
    gradientIndigo.addColorStop(1, 'rgba(79, 70, 229, 0)');

    salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels.map(l => l.split('-').slice(1).reverse().join('.')), // MM-DD
            datasets: [
                {
                    label: 'Sotuv (so\'m)',
                    data: data.sales,
                    borderColor: '#10b981',
                    borderWidth: 3,
                    backgroundColor: gradientEmerald,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10b981',
                    pointRadius: 3,
                    yAxisID: 'y'
                },
                {
                    label: 'Buyurtmalar',
                    data: data.orders,
                    borderColor: '#6366f1',
                    borderWidth: 2,
                    backgroundColor: gradientIndigo,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    borderDash: [5, 5],
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    grid: { color: 'rgba(255,255,255,0.05)', drawBorder: false },
                    ticks: { color: '#6b7280', font: { size: 10 } }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    ticks: { color: '#6b7280', font: { size: 10 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#6b7280', font: { size: 10 } }
                }
            }
        }
    });
}



document.addEventListener('DOMContentLoaded', () => {
    // Initial empty chart or mock data can be here
    // For now we wait for user to click Sync
});
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
