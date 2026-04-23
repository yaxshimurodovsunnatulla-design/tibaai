<?php
require_once __DIR__ . '/../api/config.php';
$pageTitle = 'Raqiblar Narxi Monitori – Tiba AI';
$pageDescription = 'Uzum Market\'dagi raqobatchilar narxini avtomatik tahlil qiling va optimal narx strategiyangizni toping.';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="py-12 sm:py-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <!-- Header -->
        <div class="mb-10">
            <a href="/instrumentlar" class="text-gray-500 text-xs hover:text-white transition-colors inline-flex items-center gap-1 mb-4">
                <i class="fa-solid fa-arrow-left"></i> Instrumentlarga qaytish
            </a>
            <h1 class="text-3xl sm:text-4xl font-black text-white mb-3">
                <i class="fa-solid fa-binoculars text-violet-400 mr-2"></i> Raqiblar Narxi <span class="gradient-text">Monitori</span>
            </h1>
            <p class="text-gray-400 text-lg">Tovar nomini yozing — biz Uzum Market'dan avtomatik raqiblarni topib, narxni taqqoslab, AI tavsiya beramiz.</p>
        </div>

        <!-- Qidiruv -->
        <div class="glass-card p-6 sm:p-8 border border-white/5 mb-8">
            <div class="flex flex-col sm:flex-row gap-3 mb-4">
                <div class="relative flex-1">
                    <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                    <input type="text" id="search-input" placeholder="Tovar nomini yozing: masalan, ayol sumka, iPhone qop, bolalar kiyimi..." 
                        class="w-full bg-white/5 border border-white/10 rounded-xl pl-11 pr-4 py-3.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-violet-500/50 transition-all">
                </div>
                <div class="flex gap-2">
                    <input type="number" id="my-price-input" placeholder="Sizning narxingiz (ixtiyoriy)" 
                        class="w-48 bg-emerald-500/[0.05] border border-emerald-500/20 rounded-xl px-4 py-3.5 text-sm text-emerald-400 font-bold placeholder-emerald-500/30 focus:outline-none focus:border-emerald-500/50 transition-all">
                    <button onclick="autoSearchCompetitors()" id="search-btn" class="bg-violet-600 hover:bg-violet-500 text-white px-6 py-3.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-violet-600/20 active:scale-95 whitespace-nowrap flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass-dollar" id="search-btn-icon"></i> Qidirish
                    </button>
                </div>
            </div>
            <p class="text-[11px] text-gray-500"><i class="fa-solid fa-lightbulb text-amber-500/50 mr-1"></i> Qanchalik aniq yozsangiz, shunchalik to'g'ri raqiblar topiladi. O'z narxingizni kiritsangiz — taqqoslash aniqroq chiqadi.</p>
        </div>

        <!-- Natijalar -->
        <div id="results-container" class="space-y-6">
            <div class="text-center py-16 text-gray-500">
                <i class="fa-solid fa-binoculars text-5xl text-gray-700 mb-4 block"></i>
                <h4 class="text-white font-bold text-lg mb-2">Raqiblarni avtomatik qidiring</h4>
                <p class="text-sm max-w-md mx-auto">Tovar nomini kiritib "Qidirish" tugmasini bosing. Tizim Uzum Market'dan o'xshash tovarlarni topib, narx tahlilini chiqaradi.</p>
            </div>
        </div>
    </div>
</div>

<script>
async function autoSearchCompetitors() {
    const query = document.getElementById('search-input').value.trim();
    const myPrice = parseFloat(document.getElementById('my-price-input').value) || 0;
    const btn = document.getElementById('search-btn');
    const resultsDiv = document.getElementById('results-container');
    const format = (v) => new Intl.NumberFormat('uz-UZ').format(Math.round(v));
    const formatFull = (v) => format(v) + " so'm";

    if (!query) { alert('Tovar nomini kiriting'); return; }

    try {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Qidirilmoqda...';
        resultsDiv.innerHTML = `
            <div class="text-center py-14">
                <i class="fa-solid fa-satellite-dish text-5xl text-violet-400 animate-pulse block mb-4"></i>
                <p class="text-white font-medium">Uzum Market'dan "${query}" bo'yicha raqiblar qidirilmoqda...</p>
                <p class="text-gray-600 text-xs mt-2">Bu 5-15 soniya vaqt olishi mumkin</p>
            </div>`;

        // 1-usul: AllOrigins CORS proxy orqali Uzum katalog sahifasini o'qish
        let products = [];
        try {
            const uzumUrl = 'https://uzum.uz/uz/search?query=' + encodeURIComponent(query);
            const proxyUrl = 'https://api.allorigins.win/raw?url=' + encodeURIComponent(uzumUrl);
            const resp = await fetch(proxyUrl, { headers: { 'Accept': '*/*' } });
            const html = await resp.text();
            
            // Uzum sahifasidan tovarlar ma'lumotini ajratish
            // 1. __NEXT_DATA__ yoki __NUXT__ global o'zgaruvchi
            const nuxtMatch = html.match(/window\.__NUXT__\s*=\s*(\{[\s\S]*?\});?\s*<\/script>/);
            const nextMatch = html.match(/<script[^>]*id="__NEXT_DATA__"[^>]*>([\s\S]*?)<\/script>/);
            
            if (nuxtMatch || nextMatch) {
                try {
                    const jsonStr = nuxtMatch ? nuxtMatch[1] : nextMatch[1];
                    const pageData = JSON.parse(jsonStr);
                    // Uzum NUXT data ichidan tovarlarni topish
                    const extractProducts = (obj) => {
                        if (!obj || typeof obj !== 'object') return;
                        if (Array.isArray(obj)) {
                            obj.forEach(item => {
                                if (item && item.title && (item.minSellPrice || item.sellPrice || item.price)) {
                                    let price = item.minSellPrice || item.sellPrice || item.price || 0;
                                    if (price > 1000000) price /= 100;
                                    products.push({ title: item.title, price, rating: item.rating || 0, reviews: item.reviewsAmount || 0, orders: item.ordersAmount || 0, productId: item.productId || 0 });
                                }
                                extractProducts(item);
                            });
                        } else {
                            Object.values(obj).forEach(v => extractProducts(v));
                        }
                    };
                    extractProducts(pageData);
                } catch(e) { console.warn('JSON parse error:', e); }
            }
            
            // 2. JSON-LD structured data
            if (products.length === 0) {
                const ldMatches = html.match(/<script type="application\/ld\+json">([\s\S]*?)<\/script>/g);
                if (ldMatches) {
                    ldMatches.forEach(m => {
                        try {
                            const ld = JSON.parse(m.replace(/<\/?script[^>]*>/g, ''));
                            const items = ld.itemListElement || ld.offers || [];
                            items.forEach(it => {
                                const item = it.item || it;
                                if (item.name && (item.price || item.offers?.price)) {
                                    products.push({ title: item.name, price: parseFloat(item.price || item.offers?.price || 0), rating: 0, reviews: 0, orders: 0 });
                                }
                            });
                        } catch(e) {}
                    });
                }
            }
            
            // 3. Oddiy regex bilan narxlarni topish
            if (products.length === 0) {
                const priceRegex = /"title"\s*:\s*"([^"]{5,80})"[\s\S]{0,200}?"(?:minSellPrice|sellPrice|price)"\s*:\s*(\d+)/g;
                let m;
                while ((m = priceRegex.exec(html)) !== null && products.length < 12) {
                    let price = parseInt(m[2]);
                    if (price > 1000000) price /= 100;
                    if (price > 100 && price < 100000000) {
                        products.push({ title: m[1], price, rating: 0, reviews: 0, orders: 0 });
                    }
                }
            }
        } catch(e) { console.warn('AllOrigins proxy failed:', e); }

        // 2-usul: GraphQL fallback
        if (products.length === 0) {
            try {
                const gqlQuery = `{makeSearch(queryInput:{text:"${query.replace(/"/g, '')}", sort:POPULAR, showAdultContent:"TRUE", filters:[]}, pagination:{offset:0, limit:12}){items{catalogCard{productId title minSellPrice minFullPrice reviewsAmount ordersAmount rating}}}}`;
                const gqlResp = await fetch('https://graphql.uzum.uz/graphql', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'apollographql-client-name': 'web-customers' },
                    body: JSON.stringify({ query: gqlQuery }),
                    credentials: 'include'
                });
                const gqlData = await gqlResp.json();
                (gqlData?.data?.makeSearch?.items || []).forEach(it => {
                    const c = it.catalogCard;
                    if (c) {
                        let price = c.minSellPrice || 0;
                        if (price > 1000000) price /= 100;
                        products.push({ title: c.title, price, rating: c.rating || 0, reviews: c.reviewsAmount || 0, orders: c.ordersAmount || 0, productId: c.productId });
                    }
                });
            } catch(e) { console.warn('GraphQL failed:', e); }
        }

        // 3-usul: PHP proxy fallback
        if (products.length === 0) {
            try {
                const proxyResp = await fetch('/api/uzum-search-proxy.php?q=' + encodeURIComponent(query));
                const proxyData = await proxyResp.json();
                if (proxyData.success && proxyData.products) products = proxyData.products;
            } catch(e) { console.warn('PHP proxy failed:', e); }
        }

        // 3-usul: Agar hech narsa ishlamasa - Uzum sahifasini ochish taklifi
        if (products.length === 0) {
            resultsDiv.innerHTML = `
                <div class="glass-card p-8 border border-amber-500/10 bg-amber-500/[0.03] text-center">
                    <i class="fa-solid fa-triangle-exclamation text-4xl text-amber-400 mb-4 block"></i>
                    <h4 class="text-white font-bold text-lg mb-2">Avtomatik qidiruv hozircha ishlamadi</h4>
                    <p class="text-gray-400 text-sm mb-6 max-w-lg mx-auto">Uzum API serverdan ulanishni bloklagan. Quyidagi "Uzum'da ochish" tugmasini bosing va raqiblar narxlarini pastdagi maydonga kiriting.</p>
                    <button onclick="window.open('https://uzum.uz/uz/search?query=${encodeURIComponent(query)}', '_blank')" class="bg-violet-600 hover:bg-violet-500 text-white px-6 py-3 rounded-xl font-bold text-sm transition-all mb-6 inline-flex items-center gap-2">
                        <i class="fa-solid fa-external-link"></i> Uzum'da ochish
                    </button>
                    <div class="max-w-2xl mx-auto mt-4 text-left">
                        <p class="text-gray-400 text-xs mb-3 font-bold">Raqiblar narxlarini qo'lda kiriting:</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2" id="manual-inputs">
                            ${[1,2,3,4,5].map(i => `
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500 text-xs w-16">#${i}</span>
                                <input type="number" placeholder="Narxi" class="manual-price flex-1 bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-violet-500/50">
                            </div>`).join('')}
                        </div>
                        <button onclick="analyzeManualPrices()" class="mt-4 w-full bg-gradient-to-r from-violet-600 to-indigo-600 text-white px-4 py-2.5 rounded-xl font-bold text-sm">
                            <i class="fa-solid fa-chart-bar mr-1"></i> Tahlil qilish
                        </button>
                    </div>
                </div>`;
            return;
        }

        // ====== NATIJALAR KO'RSATISH ======
        const prices = products.map(p => p.price).filter(p => p > 0);
        const avgPrice = prices.length > 0 ? prices.reduce((a,b) => a+b, 0) / prices.length : 0;
        const minPrice = prices.length > 0 ? Math.min(...prices) : 0;
        const maxPrice = prices.length > 0 ? Math.max(...prices) : 0;

        // Sizning pozitsiyangiz (agar narx kiritilgan bo'lsa)
        let positionHtml = '';
        if (myPrice > 0 && avgPrice > 0) {
            const diff = ((myPrice - avgPrice) / avgPrice * 100).toFixed(1);
            let pos, posColor, posIcon, posBg;
            if (diff > 15) { pos = 'QIMMATROQ'; posColor = 'text-red-400'; posIcon = 'fa-arrow-trend-up'; posBg = 'bg-red-500/10 border-red-500/20'; }
            else if (diff < -15) { pos = 'ARZONROQ'; posColor = 'text-amber-400'; posIcon = 'fa-arrow-trend-down'; posBg = 'bg-amber-500/10 border-amber-500/20'; }
            else { pos = 'RAQOBATBARDOSH'; posColor = 'text-emerald-400'; posIcon = 'fa-check-circle'; posBg = 'bg-emerald-500/10 border-emerald-500/20'; }
            
            let myPct = maxPrice > minPrice ? ((myPrice - minPrice) / (maxPrice - minPrice)) * 100 : 50;
            myPct = Math.max(2, Math.min(98, myPct));

            // AI Tavsiya
            let advice = '';
            if (diff > 15) {
                advice = `Sizning narxingiz bozor o'rtachasidan <b>${Math.abs(diff)}%</b> qimmatroq. Yechim: Infografika sifatini oshiring yoki narxni <b>${formatFull(avgPrice * 1.05)}</b> atrofiga tushiring. "Premium" imij yarating — professional video, 5+ rasm. Yoki narxni saqlab "To'plam" strategiyasini qo'llang.`;
            } else if (diff < -15) {
                advice = `Siz bozor o'rtachasidan <b>${Math.abs(diff)}%</b> arzonroqsiz. Narxni asta-sekin <b>${formatFull(avgPrice * 0.92)}</b> gacha ko'tarish mumkin. Har hafta 2-3% ga oshiring. Juda arzon narx "sifatsiz" taassurot berishi mumkin.`;
            } else {
                advice = `Narxingiz raqobatbardosh (<b>${diff > 0 ? '+' : ''}${diff}%</b>). Pozitsiyani saqlab qolish uchun raqiblarni har hafta tekshiring. Kartochka vizualini kuchaytirib, konversiyani oshiring. Narxni 3-5% tushirmasdan "Chegirma: -10%" aksiyasini yoqing.`;
            }

            positionHtml = `
            <div class="glass-card p-6 border border-white/5 mb-6">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
                    <div><div class="text-white font-black text-2xl">${formatFull(myPrice)}</div><div class="text-xs text-gray-500">Sizning narxingiz</div></div>
                    <div class="px-4 py-2 rounded-xl ${posBg} border flex items-center gap-2">
                        <i class="fa-solid ${posIcon} ${posColor}"></i>
                        <span class="${posColor} font-bold">${pos}</span>
                        <span class="text-gray-500 text-xs">(${diff > 0 ? '+' : ''}${diff}%)</span>
                    </div>
                    <div class="text-right"><div class="text-white font-bold text-lg">${formatFull(avgPrice)}</div><div class="text-xs text-gray-500">Bozor o'rtachasi</div></div>
                </div>
                <div class="relative h-3 bg-gradient-to-r from-emerald-500 via-yellow-500 to-red-500 rounded-full mb-1">
                    <div class="absolute top-1/2 -translate-y-1/2 w-5 h-5 bg-white rounded-full border-2 border-violet-500 shadow-lg shadow-violet-500/50" style="left:${myPct}%"></div>
                </div>
                <div class="flex justify-between text-[9px] text-gray-500"><span>Arzon</span><span class="text-violet-400 font-bold">SIZ</span><span>Qimmat</span></div>
            </div>
            <div class="glass-card p-6 border border-violet-500/10 bg-violet-500/[0.03] mb-6">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-violet-500/20 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-brain text-violet-400"></i></div>
                    <div><h4 class="text-violet-400 font-bold text-sm mb-2">Tiba AI Narx Strategiyasi <span class="text-[9px] px-1.5 py-0.5 bg-violet-500/20 rounded-full ml-1">AI</span></h4>
                    <div class="text-[12px] text-gray-300 leading-relaxed">${advice}</div></div>
                </div>
            </div>`;
        }

        let html = `
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-white font-bold">"${query}" — ${products.length} ta o'xshash tovar topildi</h3>
                <span class="text-[10px] text-gray-600"><i class="fa-solid fa-clock mr-1"></i>Yangilandi</span>
            </div>

            <!-- Narx statistikasi -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="glass-card p-4 border border-emerald-500/10 text-center">
                    <div class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">Eng arzon</div>
                    <div class="text-xl font-black text-emerald-400">${format(minPrice)}</div>
                    <div class="text-[10px] text-gray-500">so'm</div>
                </div>
                <div class="glass-card p-4 border border-violet-500/10 text-center">
                    <div class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">O'rtacha</div>
                    <div class="text-xl font-black text-white">${format(avgPrice)}</div>
                    <div class="text-[10px] text-gray-500">so'm</div>
                </div>
                <div class="glass-card p-4 border border-red-500/10 text-center">
                    <div class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">Eng qimmat</div>
                    <div class="text-xl font-black text-red-400">${format(maxPrice)}</div>
                    <div class="text-[10px] text-gray-500">so'm</div>
                </div>
            </div>

            ${positionHtml}

            <!-- Tovarlar ro'yxati -->
            <div class="glass-card p-6 border border-white/5">
                <div class="text-[10px] text-gray-500 uppercase tracking-widest font-bold mb-4">Topilgan raqiblar</div>
                <div class="space-y-2">
                    ${products.filter(p => p.price > 0).sort((a,b) => a.price - b.price).map((c, i) => `
                        <div class="flex items-center justify-between bg-white/[0.03] p-3 rounded-xl border border-white/5 hover:border-violet-500/10 transition-all">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <div class="w-8 h-8 rounded-lg ${c.price <= avgPrice ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400'} flex items-center justify-center text-xs font-bold flex-shrink-0">#${i+1}</div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-white text-sm font-medium truncate">${c.title}</div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        ${c.rating > 0 ? `<span class="text-amber-400 text-[10px]"><i class="fa-solid fa-star"></i> ${c.rating.toFixed(1)}</span>` : ''}
                                        ${c.reviews > 0 ? `<span class="text-gray-500 text-[10px]">${c.reviews} sharh</span>` : ''}
                                        ${c.orders > 0 ? `<span class="text-gray-500 text-[10px]"><i class="fa-solid fa-bag-shopping"></i> ${c.orders}+ sotilgan</span>` : ''}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0 ml-3">
                                <div class="font-bold text-lg ${c.price <= avgPrice ? 'text-emerald-400' : 'text-white'}">${format(c.price)}</div>
                                <div class="text-[9px] text-gray-500">so'm</div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>

            ${!myPrice ? `
            <div class="glass-card p-5 border border-emerald-500/10 bg-emerald-500/[0.03] mt-6">
                <p class="text-emerald-400 text-sm text-center">
                    <i class="fa-solid fa-lightbulb mr-1"></i> <b>Maslahat:</b> Yuqoridagi "Sizning narxingiz" maydoniga o'z narxingizni kiritib qayta qidirsangiz — bozordagi pozitsiyangiz va AI strategiya ham chiqadi!
                </p>
            </div>` : ''}

            <!-- Optimal narx oralig'i -->
            <div class="glass-card p-6 border border-emerald-500/10 bg-emerald-500/[0.03] mt-6">
                <h4 class="text-emerald-400 font-bold mb-3 flex items-center gap-2"><i class="fa-solid fa-bullseye"></i> Optimal narx oralig'i</h4>
                <div class="flex items-center justify-center gap-4 text-center">
                    <div><div class="text-2xl font-black text-emerald-400">${formatFull(avgPrice * 0.92)}</div><div class="text-[10px] text-gray-500">Minimal</div></div>
                    <div class="text-gray-600 text-2xl">—</div>
                    <div><div class="text-2xl font-black text-emerald-400">${formatFull(avgPrice * 1.05)}</div><div class="text-[10px] text-gray-500">Maksimal</div></div>
                </div>
                <p class="text-[11px] text-gray-500 text-center mt-3">Bu oraliqda narx belgilasangiz, raqobatbardosh bo'lasiz va sof foydangiz saqlanadi.</p>
            </div>
        `;

        resultsDiv.innerHTML = html;

    } catch (error) {
        console.error('Search error:', error);
        resultsDiv.innerHTML = `
            <div class="text-center py-10 text-red-400">
                <i class="fa-solid fa-circle-exclamation text-3xl mb-3 block"></i>
                Xatolik: ${error.message}
            </div>`;
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-magnifying-glass-dollar"></i> Qidirish';
    }
}

// Qo'lda kiritilgan narxlarni tahlil (fallback)
function analyzeManualPrices() {
    const myPrice = parseFloat(document.getElementById('my-price-input').value) || 0;
    const manualInputs = document.querySelectorAll('.manual-price');
    const prices = [];
    manualInputs.forEach(inp => { const v = parseFloat(inp.value); if (v > 0) prices.push(v); });
    if (prices.length < 2) { alert('Kamida 2 ta narx kiriting'); return; }
    // O'z narxini kiritmagan bo'lsa, birinchi narxni ishlatamiz
    document.getElementById('my-price-input').value = myPrice || prices[0];
    // Natijani ko'rsatish uchun qidiruv funksiyasini chaqiramiz
    const query = document.getElementById('search-input').value.trim() || 'Tovar';
    document.getElementById('search-input').value = query;
    // Dummy products yaratish
    const dummyProducts = prices.map((p, i) => ({ title: 'Raqib #' + (i+1), price: p, rating: 0, reviews: 0, orders: 0 }));
    // products ga ega bo'lgach - natijalarni ko'rsatish
    showManualResults(dummyProducts, myPrice || prices[0], query);
}

function showManualResults(products, myPrice, query) {
    // autoSearchCompetitors funksiyasidagi natija ko'rsatish kodini qayta ishlatish
    document.getElementById('my-price-input').value = myPrice;
    document.getElementById('search-input').value = query;
    autoSearchCompetitors(); // Qayta qidirish
}

// Enter bilan qidirish
document.getElementById('search-input').addEventListener('keydown', (e) => { if (e.key === 'Enter') autoSearchCompetitors(); });
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
