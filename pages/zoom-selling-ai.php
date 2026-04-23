<?php
require_once __DIR__ . '/../api/config.php';
$pageTitle = 'Zoom Selling AI – Bozor Tahlili – Tiba AI';
$pageDescription = 'Uzum Market kategoriyalar, bo\'limlar va har bir tovar uchun chuqur AI tahlil. Raqobat, narx, talab va trend analizi.';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="py-12 sm:py-20 relative overflow-hidden">
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-cyan-600/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative">
        <!-- Header -->
        <div class="mb-10">
            <a href="/instrumentlar" class="text-gray-500 text-xs hover:text-white transition-colors inline-flex items-center gap-1 mb-4">
                <i class="fa-solid fa-arrow-left"></i> Instrumentlarga qaytish
            </a>
            <h1 class="text-3xl sm:text-4xl font-black text-white mb-3">
                <i class="fa-solid fa-magnifying-glass-chart text-cyan-400 mr-2"></i> Zoom Selling <span class="bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">AI Tahlil</span>
            </h1>
            <p class="text-gray-400 text-lg">Kategoriyalar, bo'limlar va har bir tovar uchun mukammal AI tahlil. Bozorni chuqur o'rganing.</p>
        </div>

        <!-- Qidiruv -->
        <div class="glass-card p-6 sm:p-8 border border-white/5 mb-8">
            <div class="flex flex-col sm:flex-row gap-3 mb-4">
                <div class="relative flex-1">
                    <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                    <input type="text" id="search-input" placeholder="Tovar yoki kategoriya nomini yozing: masalan, sumka, telefon aksessuarlari..."
                        class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-cyan-500/50 transition-all">
                </div>
                <select id="sort-select" class="bg-white/5 border border-white/10 rounded-xl px-4 py-3.5 text-sm text-white focus:outline-none focus:border-cyan-500/50 w-full sm:w-48">
                    <option value="POPULAR">Mashhurlik</option>
                    <option value="BY_PRICE_ASC">Arzondan qimmatga</option>
                    <option value="BY_PRICE_DESC">Qimmatdan arzonqa</option>
                    <option value="BY_REVIEWS_AMOUNT_DESC">Ko'p sharhlar</option>
                    <option value="BY_ORDERS_AMOUNT_DESC">Ko'p sotilgan</option>
                </select>
                <button onclick="searchProducts()" id="search-btn" class="bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white px-6 py-3.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-cyan-600/20 active:scale-95 whitespace-nowrap flex items-center gap-2">
                    <i class="fa-solid fa-rocket" id="search-btn-icon"></i> Tahlil qilish
                </button>
            </div>
            <p class="text-[11px] text-gray-500"><i class="fa-solid fa-lightbulb text-amber-500/50 mr-1"></i> Tovar nomi, brend yoki kategoriya bo'yicha qidiring. AI har bir tovar uchun chuqur tahlil beradi.</p>
        </div>

        <!-- Natijalar -->
        <div id="results-container">
            <!-- Default holat -->
            <div class="text-center py-20">
                <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-cyan-500/10 to-blue-500/10 border border-cyan-500/20 flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-magnifying-glass-chart text-5xl text-cyan-400/60"></i>
                </div>
                <h4 class="text-white font-bold text-xl mb-3">Bozorni AI bilan tahlil qiling</h4>
                <p class="text-gray-500 text-sm max-w-md mx-auto mb-8">Tovar yoki kategoriya nomini kiritib "Tahlil qilish" tugmasini bosing. Tizim Uzum Market'dan ma'lumotlarni yig'ib, chuqur AI tahlil chiqaradi.</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-2xl mx-auto">
                    <div class="glass-card p-4 border border-cyan-500/10">
                        <i class="fa-solid fa-layer-group text-cyan-400 text-xl mb-2 block"></i>
                        <div class="text-white text-sm font-bold">Kategoriya tahlili</div>
                        <div class="text-gray-500 text-[10px] mt-1">Narx oralig'i, raqobat darajasi</div>
                    </div>
                    <div class="glass-card p-4 border border-blue-500/10">
                        <i class="fa-solid fa-ranking-star text-blue-400 text-xl mb-2 block"></i>
                        <div class="text-white text-sm font-bold">Tovar reytingi</div>
                        <div class="text-gray-500 text-[10px] mt-1">Sharhlar, buyurtmalar soni</div>
                    </div>
                    <div class="glass-card p-4 border border-violet-500/10">
                        <i class="fa-solid fa-brain text-violet-400 text-xl mb-2 block"></i>
                        <div class="text-white text-sm font-bold">AI maslahatlar</div>
                        <div class="text-gray-500 text-[10px] mt-1">Narx, raqobat strategiyasi</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tovar Modal -->
        <div id="product-modal" class="hidden fixed inset-0 z-[80] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/90 backdrop-blur-sm" onclick="closeProductModal()"></div>
            <div class="relative w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-3xl border border-white/10 shadow-2xl" style="background:linear-gradient(135deg,#0d0d15,#131325)">
                <button onclick="closeProductModal()" class="sticky top-4 float-right mr-4 z-10 w-10 h-10 flex items-center justify-center rounded-xl bg-white/10 text-gray-400 hover:text-white hover:bg-white/20 transition-all">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
                <div id="product-modal-content" class="p-6 sm:p-8">
                    <div class="text-center py-10"><i class="fa-solid fa-spinner fa-spin text-3xl text-cyan-400"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const fmt = (v) => new Intl.NumberFormat('uz-UZ').format(Math.round(v));
const fmtFull = (v) => fmt(v) + " so'm";

// ===== QIDIRUV =====
async function searchProducts() {
    const query = document.getElementById('search-input').value.trim();
    const sort = document.getElementById('sort-select').value;
    const btn = document.getElementById('search-btn');
    const box = document.getElementById('results-container');

    if (!query) { alert('Tovar nomini kiriting'); return; }

    try {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Tahlil...';
        box.innerHTML = `<div class="text-center py-16"><i class="fa-solid fa-satellite-dish text-5xl text-cyan-400 animate-pulse block mb-4"></i><p class="text-white font-medium">Uzum Market'dan "${query}" bo'yicha tovarlar tahlil qilinmoqda...</p><p class="text-gray-600 text-xs mt-2">Bu 5-15 soniya vaqt olishi mumkin</p></div>`;

        const resp = await fetch(`/api/zoom-selling-ai.php?action=search&q=${encodeURIComponent(query)}&sort=${sort}`);
        const data = await resp.json();

        if (!data.success || !data.products || data.products.length === 0) {
            box.innerHTML = `<div class="text-center py-16 text-gray-500"><i class="fa-solid fa-face-meh text-5xl text-gray-700 mb-4 block"></i><h4 class="text-white font-bold text-lg mb-2">Tovarlar topilmadi</h4><p class="text-sm">Boshqa kalit so'z bilan qidirib ko'ring</p></div>`;
            return;
        }

        renderResults(data, query);
    } catch (err) {
        box.innerHTML = `<div class="text-center py-10 text-red-400"><i class="fa-solid fa-circle-exclamation text-3xl mb-3 block"></i>Xatolik: ${err.message}</div>`;
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-rocket"></i> Tahlil qilish';
    }
}

function renderResults(data, query) {
    const products = data.products;
    const prices = products.map(p => p.price).filter(p => p > 0);
    const avgPrice = prices.length > 0 ? prices.reduce((a,b) => a+b, 0) / prices.length : 0;
    const minPrice = prices.length > 0 ? Math.min(...prices) : 0;
    const maxPrice = prices.length > 0 ? Math.max(...prices) : 0;
    const medianPrice = prices.length > 0 ? prices.sort((a,b) => a-b)[Math.floor(prices.length/2)] : 0;
    const avgRating = products.reduce((s,p) => s + (p.rating||0), 0) / products.length;
    const totalOrders = products.reduce((s,p) => s + (p.orders||0), 0);
    const totalReviews = products.reduce((s,p) => s + (p.reviews||0), 0);
    const withDiscount = products.filter(p => p.discount > 0).length;
    const discountPct = Math.round(withDiscount / products.length * 100);

    // Raqobat darajasi
    let competLevel, competColor, competIcon;
    const priceSpread = maxPrice > 0 ? (maxPrice - minPrice) / avgPrice * 100 : 0;
    if (products.length > 15 && priceSpread < 50) {
        competLevel = 'YUQORI'; competColor = 'text-red-400'; competIcon = 'fa-fire';
    } else if (products.length > 8) {
        competLevel = 'O\'RTA'; competColor = 'text-amber-400'; competIcon = 'fa-bolt';
    } else {
        competLevel = 'PAST'; competColor = 'text-emerald-400'; competIcon = 'fa-leaf';
    }

    // Sellers tahlili
    const sellerMap = {};
    products.forEach(p => {
        if (p.seller) {
            if (!sellerMap[p.seller]) sellerMap[p.seller] = {count:0, totalOrders:0};
            sellerMap[p.seller].count++;
            sellerMap[p.seller].totalOrders += (p.orders||0);
        }
    });
    const topSellers = Object.entries(sellerMap).sort((a,b) => b[1].totalOrders - a[1].totalOrders).slice(0, 5);

    let html = `
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-white font-bold text-lg">"${query}" — ${data.totalCount || products.length} ta tovar topildi</h3>
            <span class="text-[10px] text-gray-600"><i class="fa-solid fa-clock mr-1"></i>${data.timestamp}</span>
        </div>

        <!-- Bozor statistikasi -->
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3 mb-8">
            <div class="glass-card p-3 border border-cyan-500/10 text-center">
                <div class="text-[9px] text-gray-500 uppercase tracking-widest mb-1">Eng arzon</div>
                <div class="text-lg font-black text-emerald-400">${fmt(minPrice)}</div>
                <div class="text-[9px] text-gray-600">so'm</div>
            </div>
            <div class="glass-card p-3 border border-blue-500/10 text-center">
                <div class="text-[9px] text-gray-500 uppercase tracking-widest mb-1">O'rtacha</div>
                <div class="text-lg font-black text-white">${fmt(avgPrice)}</div>
                <div class="text-[9px] text-gray-600">so'm</div>
            </div>
            <div class="glass-card p-3 border border-violet-500/10 text-center">
                <div class="text-[9px] text-gray-500 uppercase tracking-widest mb-1">Median</div>
                <div class="text-lg font-black text-violet-400">${fmt(medianPrice)}</div>
                <div class="text-[9px] text-gray-600">so'm</div>
            </div>
            <div class="glass-card p-3 border border-red-500/10 text-center">
                <div class="text-[9px] text-gray-500 uppercase tracking-widest mb-1">Eng qimmat</div>
                <div class="text-lg font-black text-red-400">${fmt(maxPrice)}</div>
                <div class="text-[9px] text-gray-600">so'm</div>
            </div>
            <div class="glass-card p-3 border border-amber-500/10 text-center">
                <div class="text-[9px] text-gray-500 uppercase tracking-widest mb-1">O'rt. reyting</div>
                <div class="text-lg font-black text-amber-400">${avgRating.toFixed(1)}</div>
                <div class="text-[9px] text-gray-600"><i class="fa-solid fa-star text-amber-500"></i></div>
            </div>
            <div class="glass-card p-3 border border-cyan-500/10 text-center">
                <div class="text-[9px] text-gray-500 uppercase tracking-widest mb-1">Jami sotilgan</div>
                <div class="text-lg font-black text-cyan-400">${fmt(totalOrders)}</div>
                <div class="text-[9px] text-gray-600">ta</div>
            </div>
            <div class="glass-card p-3 border border-emerald-500/10 text-center">
                <div class="text-[9px] text-gray-500 uppercase tracking-widest mb-1">Sharhlar</div>
                <div class="text-lg font-black text-emerald-400">${fmt(totalReviews)}</div>
                <div class="text-[9px] text-gray-600">ta</div>
            </div>
            <div class="glass-card p-3 border border-white/5 text-center">
                <div class="text-[9px] text-gray-500 uppercase tracking-widest mb-1">Raqobat</div>
                <div class="text-lg font-black ${competColor}"><i class="fa-solid ${competIcon} mr-1"></i>${competLevel}</div>
            </div>
        </div>

        <!-- AI Bozor Tahlili -->
        <div class="glass-card p-6 border border-cyan-500/10 bg-cyan-500/[0.03] mb-8">
            <div class="flex items-start gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-cyan-500/20 to-blue-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-brain text-cyan-400 text-xl"></i>
                </div>
                <div>
                    <h4 class="text-cyan-400 font-bold mb-2 flex items-center gap-2">Zoom AI Bozor Tahlili <span class="text-[9px] px-1.5 py-0.5 bg-cyan-500/20 rounded-full">AI</span></h4>
                    <div class="text-[12px] text-gray-300 leading-relaxed space-y-2">
                        ${generateMarketAIAdvice(products, avgPrice, minPrice, maxPrice, competLevel, discountPct, topSellers)}
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Sotuvchilar -->
        ${topSellers.length > 0 ? `
        <div class="glass-card p-5 border border-white/5 mb-8">
            <h4 class="text-white font-bold text-sm mb-4 flex items-center gap-2"><i class="fa-solid fa-store text-blue-400"></i> Top Sotuvchilar</h4>
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                ${topSellers.map(([name, d], i) => `
                <div class="bg-white/[0.03] p-3 rounded-xl border border-white/5 text-center">
                    <div class="text-[9px] text-gray-500 mb-1">#${i+1}</div>
                    <div class="text-white text-xs font-bold truncate">${name}</div>
                    <div class="text-[10px] text-gray-500 mt-1">${d.count} tovar · ${fmt(d.totalOrders)} sotuv</div>
                </div>`).join('')}
            </div>
        </div>` : ''}

        <!-- Tovarlar jadvali -->
        <div class="glass-card p-5 border border-white/5">
            <div class="flex items-center justify-between mb-4">
                <div class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Tovarlar ro'yxati va AI tahlil</div>
                <div class="text-[10px] text-gray-600">${products.length} ta tovar</div>
            </div>
            <div class="space-y-2">
                ${products.filter(p => p.price > 0).map((p, i) => renderProductCard(p, i, avgPrice)).join('')}
            </div>
        </div>

        <!-- Optimal narx oralig'i -->
        <div class="glass-card p-6 border border-emerald-500/10 bg-emerald-500/[0.03] mt-6">
            <h4 class="text-emerald-400 font-bold mb-3 flex items-center gap-2"><i class="fa-solid fa-bullseye"></i> Tavsiya etilgan narx oralig'i</h4>
            <div class="flex items-center justify-center gap-4 text-center">
                <div><div class="text-2xl font-black text-emerald-400">${fmtFull(medianPrice * 0.9)}</div><div class="text-[10px] text-gray-500">Minimal (arzon segment)</div></div>
                <div class="text-gray-600 text-2xl">—</div>
                <div><div class="text-2xl font-black text-emerald-400">${fmtFull(medianPrice * 1.15)}</div><div class="text-[10px] text-gray-500">Maksimal (premium)</div></div>
            </div>
            <p class="text-[11px] text-gray-500 text-center mt-3">Median narx asosida hisoblangan. Bu oraliqda narx belgilasangiz bozorda raqobatbardosh bo'lasiz.</p>
        </div>
    `;

    document.getElementById('results-container').innerHTML = html;
}

function renderProductCard(p, i, avgPrice) {
    const diffPct = avgPrice > 0 ? ((p.price - avgPrice) / avgPrice * 100).toFixed(0) : 0;
    const isBelow = diffPct < 0;
    const diffColor = isBelow ? 'text-emerald-400' : (diffPct > 15 ? 'text-red-400' : 'text-amber-400');
    const photoHtml = p.photo ? `<img src="${p.photo}" class="w-12 h-12 rounded-lg object-cover bg-white/5" onerror="this.style.display='none'">` :
        `<div class="w-12 h-12 rounded-lg bg-white/5 flex items-center justify-center text-gray-600"><i class="fa-solid fa-image"></i></div>`;

    return `
    <div class="flex items-center justify-between bg-white/[0.03] p-3 rounded-xl border border-white/5 hover:border-cyan-500/20 transition-all cursor-pointer group" onclick="openProductModal(${p.productId}, '${p.title.replace(/'/g, "\\'")}')">
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <div class="w-8 h-8 rounded-lg ${isBelow ? 'bg-emerald-500/10 text-emerald-400' : 'bg-white/5 text-gray-500'} flex items-center justify-center text-xs font-bold flex-shrink-0">#${i+1}</div>
            ${photoHtml}
            <div class="min-w-0 flex-1">
                <div class="text-white text-sm font-medium truncate group-hover:text-cyan-400 transition-colors">${p.title}</div>
                <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                    ${p.rating > 0 ? `<span class="text-amber-400 text-[10px]"><i class="fa-solid fa-star"></i> ${p.rating.toFixed(1)}</span>` : ''}
                    ${p.reviews > 0 ? `<span class="text-gray-500 text-[10px]">${fmt(p.reviews)} sharh</span>` : ''}
                    ${p.orders > 0 ? `<span class="text-gray-500 text-[10px]"><i class="fa-solid fa-bag-shopping"></i> ${fmt(p.orders)}+ sotilgan</span>` : ''}
                    ${p.seller ? `<span class="text-gray-600 text-[10px]"><i class="fa-solid fa-store"></i> ${p.seller}</span>` : ''}
                    ${p.discount > 0 ? `<span class="text-rose-400 text-[10px] font-bold">-${p.discount}%</span>` : ''}
                </div>
            </div>
        </div>
        <div class="text-right flex-shrink-0 ml-3">
            <div class="font-bold text-lg ${isBelow ? 'text-emerald-400' : 'text-white'}">${fmt(p.price)}</div>
            <div class="text-[9px] ${diffColor} font-bold">${diffPct > 0 ? '+' : ''}${diffPct}% o'rtachadan</div>
        </div>
        <i class="fa-solid fa-chevron-right text-gray-700 ml-2 group-hover:text-cyan-400 transition-colors"></i>
    </div>`;
}

function generateMarketAIAdvice(products, avg, min, max, competLevel, discountPct, topSellers) {
    const range = max - min;
    const concentration = range > 0 ? (range / avg * 100).toFixed(0) : 0;
    const topSeller = topSellers[0] ? topSellers[0][0] : 'noma\'lum';
    const avgOrders = products.reduce((s,p) => s+(p.orders||0),0) / products.length;

    let advice = `<p><b>📊 Bozor umumiy ko'rinishi:</b> "${products[0]?.title?.split(' ')[0] || 'Bu'}" segmentida narxlar <b>${fmtFull(min)}</b> dan <b>${fmtFull(max)}</b> gacha tarqalgan. O'rtacha narx — <b>${fmtFull(avg)}</b>. Narx tarqalishi <b>${concentration}%</b>.</p>`;

    if (competLevel === 'YUQORI') {
        advice += `<p><b>🔥 Raqobat yuqori!</b> Bu segmentda juda ko'p sotuvchilar faol. Narx bilan raqobat qilish qiyin — <b>vizual sifat</b> (infografika, professional fotolar) va <b>xizmat tezligi</b> bilan ajralib turish muhim.</p>`;
    } else if (competLevel === "O'RTA") {
        advice += `<p><b>⚡ Raqobat o'rtacha.</b> Bu segment hali to'liq to'yinmagan. O'rtacha sifatdagi kartochka bilan ham yaxshi natija chiqarish mumkin. Lekin <b>tezroq kirish</b> muhim!</p>`;
    } else {
        advice += `<p><b>🌿 Raqobat past!</b> Bu ajoyib imkoniyat! Segment hali bo'sh, tezda kirib, <b>birinchi bo'lib</b> yaxshi reyting va sharhlar to'plang.</p>`;
    }

    if (discountPct > 40) {
        advice += `<p><b>🏷️ Chegirma trendi:</b> Tovarlarning <b>${discountPct}%</b> ida chegirma mavjud. Bu bozorda chegirma strategiyasi keng tarqalgan — narx belgilashda buni hisobga oling.</p>`;
    }

    if (avgOrders > 100) {
        advice += `<p><b>📦 Talab yuqori:</b> O'rtacha tovar <b>${fmt(avgOrders)}</b> marta sotilgan. Bu sohada doimiy talab bor — to'g'ri narx va sifatli kartochka bilan barqaror sotuv qilish mumkin.</p>`;
    }

    if (topSellers.length > 0) {
        advice += `<p><b>🏆 Segmentdagi lider:</b> "${topSeller}" eng ko'p sotuvchi hisoblanadi. Ularning kartochkalaridan o'rganib, kamchiliklarini toping va o'zingizni farqlang.</p>`;
    }

    return advice;
}

// ===== TOVAR MODAL =====
async function openProductModal(productId, title) {
    if (!productId) { alert('Bu tovar haqida batafsil ma\'lumot mavjud emas'); return; }
    const modal = document.getElementById('product-modal');
    const content = document.getElementById('product-modal-content');
    modal.classList.remove('hidden');
    content.innerHTML = `<div class="text-center py-14"><i class="fa-solid fa-atom fa-spin text-4xl text-cyan-400 mb-4 block"></i><p class="text-white font-medium">"${title}" uchun chuqur tahlil tayyorlanmoqda...</p><p class="text-gray-600 text-xs mt-2">Tovar ma'lumotlari yuklanmoqda</p></div>`;

    try {
        const resp = await fetch(`/api/zoom-selling-ai.php?action=product-analysis&productId=${productId}`);
        const data = await resp.json();

        if (!data.success || !data.analysis) {
            content.innerHTML = `<div class="text-center py-10"><i class="fa-solid fa-circle-exclamation text-3xl text-amber-400 mb-3 block"></i><p class="text-gray-400">Tovar ma'lumotlarini olishda xatolik. Uzum sahifasida ko'ring:</p><a href="https://uzum.uz/uz/product/-${productId}" target="_blank" class="inline-flex items-center gap-2 mt-4 bg-cyan-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm"><i class="fa-solid fa-external-link"></i> Uzum'da ochish</a></div>`;
            return;
        }

        renderProductModal(data.analysis);
    } catch (err) {
        content.innerHTML = `<div class="text-center py-10 text-red-400"><i class="fa-solid fa-circle-exclamation text-3xl mb-3 block"></i>Xatolik: ${err.message}</div>`;
    }
}

function renderProductModal(p) {
    const content = document.getElementById('product-modal-content');

    // Kuchli/zaif tomonlar
    let strengths = [], weaknesses = [];
    if (p.rating >= 4.5) strengths.push('Yuqori reyting (' + p.rating.toFixed(1) + ')');
    else if (p.rating > 0 && p.rating < 3.5) weaknesses.push('Past reyting (' + p.rating.toFixed(1) + ')');
    if (p.reviews > 50) strengths.push(fmt(p.reviews) + ' ta sharh — ishonch yuqori');
    else if (p.reviews < 5) weaknesses.push('Kam sharhlar — yangi tovar');
    if (p.orders > 100) strengths.push(fmt(p.orders) + '+ sotuv — mashhur tovar');
    else if (p.orders < 10) weaknesses.push('Kam sotilgan');
    if (p.discount > 15) strengths.push(p.discount + '% chegirma — sotuvni oshiradi');
    if (p.photos && p.photos.length > 3) strengths.push(p.photos.length + ' ta rasm — yaxshi vizual');
    else if (!p.photos || p.photos.length < 2) weaknesses.push('Kam rasmlar');
    if (p.description && p.description.length > 200) strengths.push('Batafsil tavsif');
    else weaknesses.push('Qisqa yoki bo\'sh tavsif');

    // Raqobat maslahat
    let advice = '';
    if (p.orders > 500 && p.rating > 4) {
        advice = 'Bu tovar bozorda kuchli o\'rinni egallagan. Raqobat qilish uchun <b>narxni biroz pastroq</b> qo\'yib, <b>sifatliroq infografika</b> bilan kirishingiz kerak. Tezda sharhlar to\'plash muhim.';
    } else if (p.orders > 100) {
        advice = 'Bu tovar o\'rtacha darajada mashhur. <b>Yaxshiroq rasmlar</b> va <b>batafsil tavsif</b> bilan osongina oldinga chiqish mumkin. Narxni o\'rtacha darajada saqlang.';
    } else if (p.orders < 20) {
        advice = 'Bu tovar hali mashhur emas — bu sizga imkoniyat! Agar sifatliroq kartochka, arzonroq narx va tez yetkazib berish bilan kirsangiz, <b>tez orada bozorni egallashingiz mumkin</b>.';
    } else {
        advice = 'Oddiy raqobat darajasi. Narxni raqiblardan 5-10% past qo\'yib, <b>aksiya</b> yoqing va <b>professional fotolar</b> bilan kartochkangizni kuchaytirib boring.';
    }

    content.innerHTML = `
        <div class="space-y-6">
            <div class="flex items-start gap-4">
                ${p.photos && p.photos[0] ? `<img src="${p.photos[0]}" class="w-24 h-24 rounded-2xl object-cover border border-white/10 flex-shrink-0">` : ''}
                <div class="flex-1 min-w-0">
                    <h3 class="text-xl font-bold text-white mb-1">${p.title}</h3>
                    <div class="flex flex-wrap gap-2 text-xs">
                        ${p.category ? `<span class="px-2 py-0.5 rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20">${p.category}</span>` : ''}
                        ${p.seller ? `<span class="px-2 py-0.5 rounded-full bg-white/5 text-gray-400"><i class="fa-solid fa-store mr-1"></i>${p.seller}</span>` : ''}
                    </div>
                </div>
            </div>

            <!-- Narx va statistika -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="glass-card p-3 border border-cyan-500/10 text-center">
                    <div class="text-[9px] text-gray-500 uppercase tracking-widest mb-1">Narxi</div>
                    <div class="text-xl font-black text-white">${fmt(p.price)}</div>
                    ${p.discount > 0 ? `<div class="text-[9px] text-rose-400 font-bold line-through">${fmt(p.fullPrice)}</div>` : '<div class="text-[9px] text-gray-600">so\'m</div>'}
                </div>
                <div class="glass-card p-3 border border-amber-500/10 text-center">
                    <div class="text-[9px] text-gray-500 uppercase tracking-widest mb-1">Reyting</div>
                    <div class="text-xl font-black text-amber-400">${p.rating ? p.rating.toFixed(1) : '—'}</div>
                    <div class="text-[9px] text-gray-600"><i class="fa-solid fa-star text-amber-500"></i></div>
                </div>
                <div class="glass-card p-3 border border-emerald-500/10 text-center">
                    <div class="text-[9px] text-gray-500 uppercase tracking-widest mb-1">Sharhlar</div>
                    <div class="text-xl font-black text-emerald-400">${fmt(p.reviews || 0)}</div>
                    <div class="text-[9px] text-gray-600">ta</div>
                </div>
                <div class="glass-card p-3 border border-blue-500/10 text-center">
                    <div class="text-[9px] text-gray-500 uppercase tracking-widest mb-1">Sotilgan</div>
                    <div class="text-xl font-black text-blue-400">${fmt(p.orders || 0)}+</div>
                    <div class="text-[9px] text-gray-600">ta</div>
                </div>
            </div>

            ${p.discount > 0 ? `<div class="bg-rose-500/5 border border-rose-500/20 rounded-xl p-3 text-center"><span class="text-rose-400 font-bold text-sm"><i class="fa-solid fa-tag mr-1"></i> ${p.discount}% chegirma mavjud</span></div>` : ''}

            <!-- SWOT tahlil -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-emerald-500/5 border border-emerald-500/10 rounded-xl p-4">
                    <h5 class="text-emerald-400 font-bold text-sm mb-2"><i class="fa-solid fa-check-circle mr-1"></i> Kuchli tomonlari</h5>
                    <ul class="text-xs text-gray-300 space-y-1">${strengths.length > 0 ? strengths.map(s => `<li class="flex items-center gap-1.5"><i class="fa-solid fa-plus text-emerald-500 text-[8px]"></i> ${s}</li>`).join('') : '<li class="text-gray-500">Ma\'lumot yetarli emas</li>'}</ul>
                </div>
                <div class="bg-amber-500/5 border border-amber-500/10 rounded-xl p-4">
                    <h5 class="text-amber-400 font-bold text-sm mb-2"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Zaif tomonlari</h5>
                    <ul class="text-xs text-gray-300 space-y-1">${weaknesses.length > 0 ? weaknesses.map(w => `<li class="flex items-center gap-1.5"><i class="fa-solid fa-minus text-amber-500 text-[8px]"></i> ${w}</li>`).join('') : '<li class="text-gray-500">Ma\'lumot yetarli emas</li>'}</ul>
                </div>
            </div>

            <!-- AI strategiya -->
            <div class="bg-gradient-to-br from-cyan-500/5 to-blue-500/5 border border-cyan-500/20 rounded-xl p-5">
                <h5 class="text-cyan-400 font-bold text-sm mb-2 flex items-center gap-2"><i class="fa-solid fa-brain"></i> AI Raqobat Strategiyasi <span class="text-[8px] px-1.5 py-0.5 bg-cyan-500/20 rounded-full">AI</span></h5>
                <p class="text-xs text-gray-300 leading-relaxed">${advice}</p>
            </div>

            ${p.characteristics && p.characteristics.length > 0 ? `
            <div class="glass-card p-4 border border-white/5">
                <h5 class="text-white font-bold text-sm mb-3"><i class="fa-solid fa-list-check text-gray-500 mr-1"></i> Xususiyatlari</h5>
                <div class="grid grid-cols-2 gap-1 text-xs">
                    ${p.characteristics.slice(0, 10).map(c => `<div class="flex justify-between py-1 border-b border-white/5"><span class="text-gray-500">${c.name}</span><span class="text-white font-medium">${c.value}</span></div>`).join('')}
                </div>
            </div>` : ''}

            <div class="flex gap-3 pt-2">
                <a href="https://uzum.uz/uz/product/-${p.productId}" target="_blank" class="flex-1 flex items-center justify-center gap-2 bg-cyan-600 hover:bg-cyan-500 text-white px-4 py-3 rounded-xl font-bold text-sm transition-all">
                    <i class="fa-solid fa-external-link"></i> Uzum'da ochish
                </a>
                <button onclick="closeProductModal()" class="px-6 py-3 rounded-xl border border-white/10 text-gray-400 hover:text-white hover:bg-white/5 text-sm font-bold transition-all">Yopish</button>
            </div>
        </div>`;
}

function closeProductModal() {
    document.getElementById('product-modal').classList.add('hidden');
}

// Enter bilan qidirish
document.getElementById('search-input').addEventListener('keydown', (e) => { if (e.key === 'Enter') searchProducts(); });
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
