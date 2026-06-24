<?php
$pageTitle = 'Yangi Takliflar – Bloger Panel';
require_once __DIR__ . '/../components/blogger-header.php';
?>

<div class="p-4 md:p-8 flex-1 flex flex-col h-full">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-white">Yangi Takliflar <span class="bg-orange-500 text-white text-xs px-2 py-1 rounded-full font-bold ml-2 align-middle" id="offers-count">0</span></h1>
        <p class="text-gray-400 text-sm mt-1">Sotuvchilar tomonidan yuborilgan reklama va barter bo'yicha yangi so'rovlar.</p>
    </div>

    <!-- Filters/Tabs -->
    <div class="flex gap-2 mb-6 overflow-x-auto custom-scrollbar pb-2">
        <button class="px-4 py-2 rounded-xl text-xs font-bold transition-all bg-orange-500 text-white shadow-lg shadow-orange-500/20">Barchasi</button>
        <button class="px-4 py-2 rounded-xl text-xs font-bold transition-all bg-white/5 text-gray-400 hover:text-white hover:bg-white/10">Barter</button>
        <button class="px-4 py-2 rounded-xl text-xs font-bold transition-all bg-white/5 text-gray-400 hover:text-white hover:bg-white/10">Pullik</button>
    </div>

    <!-- Offers List -->
    <div class="glass-card p-6 flex-1 min-h-[400px]">
        <div id="offers-list" class="space-y-4">
            <!-- Skeleton Loader -->
            <div class="animate-pulse flex flex-col sm:flex-row items-start sm:items-center gap-4 p-4 rounded-xl bg-white/5 border border-white/5">
                <div class="w-16 h-16 bg-white/10 rounded-xl"></div>
                <div class="flex-1 space-y-2 w-full">
                    <div class="h-5 bg-white/10 rounded w-1/3"></div>
                    <div class="h-3 bg-white/10 rounded w-1/2"></div>
                </div>
                <div class="w-full sm:w-auto flex gap-2 sm:mt-0 mt-4">
                    <div class="h-10 bg-white/10 rounded-lg w-24"></div>
                    <div class="h-10 bg-white/10 rounded-lg w-24"></div>
                </div>
            </div>
            <div class="animate-pulse flex flex-col sm:flex-row items-start sm:items-center gap-4 p-4 rounded-xl bg-white/5 border border-white/5">
                <div class="w-16 h-16 bg-white/10 rounded-xl"></div>
                <div class="flex-1 space-y-2 w-full">
                    <div class="h-5 bg-white/10 rounded w-1/4"></div>
                    <div class="h-3 bg-white/10 rounded w-2/3"></div>
                </div>
                <div class="w-full sm:w-auto flex gap-2 sm:mt-0 mt-4">
                    <div class="h-10 bg-white/10 rounded-lg w-24"></div>
                    <div class="h-10 bg-white/10 rounded-lg w-24"></div>
                </div>
            </div>
        </div>

        <!-- Empty State (Hidden initially) -->
        <div id="no-offers-state" class="hidden h-full flex flex-col items-center justify-center py-20 text-center">
            <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center text-4xl text-gray-600 mb-4 shadow-inner">
                <i class="fa-solid fa-inbox"></i>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Hozircha takliflar yo'q</h3>
            <p class="text-gray-400 text-sm max-w-sm">
                Sizga yuborilgan barcha yangi hamkorlik so'rovlari shu yerda paydo bo'ladi.
            </p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const token = localStorage.getItem('blogger_token');
    if (!token) return;

    try {
        const res = await fetch('/api/blogger-deals.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Blogger-Token': token },
            body: JSON.stringify({ action: 'get_pending' })
        });
        const data = await res.json();
        
        const listContainer = document.getElementById('offers-list');
        const emptyState = document.getElementById('no-offers-state');
        const countBadge = document.getElementById('offers-count');

        if (data.success) {
            listContainer.innerHTML = '';
            countBadge.textContent = data.offers.length;

            if (data.offers.length === 0) {
                emptyState.classList.remove('hidden');
                emptyState.classList.add('flex');
            } else {
                emptyState.classList.add('hidden');
                emptyState.classList.remove('flex');

                data.offers.forEach(offer => {
                    const priceDisplay = offer.deal_type === 'barter' 
                        ? `<div class="text-lg font-bold text-white">Barter</div><div class="text-[10px] text-gray-400">Qiymati: ~${new Intl.NumberFormat('uz-UZ').format(offer.barter_value)} so'm</div>`
                        : `<div class="text-lg font-bold text-white">${new Intl.NumberFormat('uz-UZ').format(offer.offered_price)} <span class="text-xs text-gray-400">so'm</span></div>`;

                    listContainer.innerHTML += `
                        <div class="group flex flex-col sm:flex-row items-start sm:items-center gap-4 p-5 rounded-xl bg-white/5 border border-white/5 hover:border-orange-500/30 transition-all">
                            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-white/10 to-white/5 border border-white/10 flex flex-col items-center justify-center flex-shrink-0 relative overflow-hidden">
                                ${offer.product_image ? `<img src="${offer.product_image}" class="absolute inset-0 w-full h-full object-cover">` : `<i class="fa-solid fa-box text-2xl text-gray-500"></i>`}
                            </div>
                            <div class="flex-1 min-w-0 w-full">
                                <div class="flex items-center justify-between mb-1">
                                    <h3 class="text-base font-bold text-white truncate pr-4">${offer.product_name}</h3>
                                    <div class="text-[10px] text-gray-500 whitespace-nowrap">${offer.created_at}</div>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="px-2 py-0.5 rounded bg-white/5 border border-white/10 text-[10px] font-bold text-gray-300 uppercase"><i class="fa-solid fa-tag mr-1 text-gray-500"></i> ${offer.product_category}</span>
                                    <span class="px-2 py-0.5 rounded ${offer.deal_type === 'barter' ? 'bg-purple-500/20 text-purple-400 border-purple-500/20' : 'bg-emerald-500/20 text-emerald-400 border-emerald-500/20'} border text-[10px] font-bold uppercase"><i class="fa-solid ${offer.deal_type === 'barter' ? 'fa-box-open' : 'fa-money-bill-wave'} mr-1"></i> ${offer.deal_type === 'barter' ? 'Barter' : 'Pullik'}</span>
                                    <span class="px-2 py-0.5 rounded bg-blue-500/20 border border-blue-500/20 text-[10px] font-bold text-blue-400 uppercase"><i class="fa-solid fa-camera mr-1"></i> ${offer.ad_format}</span>
                                </div>
                                <p class="text-xs text-gray-400 line-clamp-2">${offer.description}</p>
                            </div>
                            <div class="w-full sm:w-auto flex flex-col items-start sm:items-end flex-shrink-0 mt-4 sm:mt-0 pt-4 sm:pt-0 border-t sm:border-0 border-white/5">
                                ${priceDisplay}
                                <div class="flex gap-2 mt-3 w-full sm:w-auto">
                                    <button onclick="respondDeal(${offer.id}, 'reject')" class="flex-1 sm:flex-none px-4 py-2 rounded-lg bg-white/5 hover:bg-red-500/20 text-gray-400 hover:text-red-400 text-xs font-bold transition-all border border-transparent hover:border-red-500/30">Rad etish</button>
                                    <button onclick="respondDeal(${offer.id}, 'accept')" class="flex-1 sm:flex-none px-4 py-2 rounded-lg bg-orange-500/20 hover:bg-orange-500 text-orange-400 hover:text-white text-xs font-bold transition-all shadow-lg hover:shadow-orange-500/30">Qabul qilish</button>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
        }
    } catch(e) {
        document.getElementById('offers-list').innerHTML = `<div class="p-4 bg-red-500/10 text-red-400 rounded-xl text-center text-sm">Ma'lumotlarni yuklashda xatolik.</div>`;
    }
});

window.respondDeal = async function(id, actionStr) {
    if (!confirm(actionStr === 'accept' ? 'Ushbu taklifni qabul qilasizmi?' : 'Ushbu taklifni rad etasizmi?')) return;
    const token = localStorage.getItem('blogger_token');
    try {
        const res = await fetch('/api/blogger-deals.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Blogger-Token': token },
            body: JSON.stringify({ action: 'respond', id: id, response: actionStr })
        });
        const data = await res.json();
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.error || 'Xatolik yuz berdi');
        }
    } catch (e) { alert('Tarmoq xatosi'); }
};
</script>

<?php require_once __DIR__ . '/../components/blogger-footer.php'; ?>
