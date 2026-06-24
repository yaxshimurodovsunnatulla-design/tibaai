<?php
$pageTitle = 'Faol Bitimlar – Bloger Panel';
require_once __DIR__ . '/../components/blogger-header.php';
?>

<div class="p-4 md:p-8 flex-1 flex flex-col h-full">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-white">Faol va Bajarilgan Bitimlar</h1>
        <p class="text-gray-400 text-sm mt-1">Siz qabul qilgan hamkorlik ishlari va ularning joriy holati.</p>
    </div>

    <!-- Tabs -->
    <div class="flex gap-2 mb-6 overflow-x-auto custom-scrollbar pb-2">
        <button class="px-4 py-2 rounded-xl text-xs font-bold transition-all bg-blue-500 text-white shadow-lg shadow-blue-500/20">Jarayonda</button>
        <button class="px-4 py-2 rounded-xl text-xs font-bold transition-all bg-white/5 text-gray-400 hover:text-white hover:bg-white/10">Bajarilgan</button>
        <button class="px-4 py-2 rounded-xl text-xs font-bold transition-all bg-white/5 text-gray-400 hover:text-white hover:bg-white/10">Rad etilgan</button>
    </div>

    <div class="glass-card p-6 flex-1">
        <div id="deals-list" class="space-y-4">
            <!-- Loading... -->
            <div class="p-4 text-center text-gray-500 text-sm"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Yuklanmoqda...</div>
        </div>
        <div id="no-deals-state" class="hidden h-full flex flex-col items-center justify-center py-20 text-center">
            <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center text-4xl text-gray-600 mb-4 shadow-inner">
                <i class="fa-solid fa-handshake-slash"></i>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Hech qanday bitim topilmadi</h3>
            <p class="text-gray-400 text-sm max-w-sm">Hozircha sizda qabul qilingan yoki joriy faol bitimlar yo'q.</p>
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
            body: JSON.stringify({ action: 'get_active' }) // We need to add this to api
        });
        const data = await res.json();
        
        const listContainer = document.getElementById('deals-list');
        const emptyState = document.getElementById('no-deals-state');

        listContainer.innerHTML = '';

        if (data.success && data.deals && data.deals.length > 0) {
            data.deals.forEach(deal => {
                let statusBadge = '';
                if(deal.status === 'accepted') statusBadge = '<span class="px-2 py-1 rounded bg-blue-500/20 text-blue-400 text-[10px] font-bold uppercase"><i class="fa-solid fa-spinner fa-spin mr-1"></i> Jarayonda</span>';
                else if(deal.status === 'delivered') statusBadge = '<span class="px-2 py-1 rounded bg-amber-500/20 text-amber-400 text-[10px] font-bold uppercase"><i class="fa-solid fa-clock mr-1"></i> Tekshiruvda</span>';
                else if(deal.status === 'completed') statusBadge = '<span class="px-2 py-1 rounded bg-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase"><i class="fa-solid fa-check-double mr-1"></i> Bajarildi</span>';
                
                listContainer.innerHTML += `
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 rounded-xl bg-white/5 border border-white/5 hover:border-blue-500/30 transition-all gap-4">
                        <div class="flex items-center gap-4 w-full sm:w-auto">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-white/10 to-white/5 border border-white/10 flex items-center justify-center flex-shrink-0 relative overflow-hidden">
                                ${deal.product_image ? `<img src="${deal.product_image}" class="absolute inset-0 w-full h-full object-cover">` : `<i class="fa-solid fa-box text-gray-500"></i>`}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-bold text-white truncate">${deal.product_name}</h3>
                                <div class="text-[10px] text-gray-400 mt-1">Qabul qilingan: ${deal.accepted_at || '-'}</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between w-full sm:w-auto sm:gap-6 border-t sm:border-0 border-white/5 pt-3 sm:pt-0">
                            <div>${statusBadge}</div>
                            <div class="text-right">
                                <div class="text-sm font-bold text-white">${deal.deal_type === 'barter' ? 'Barter' : new Intl.NumberFormat('uz-UZ').format(deal.offered_price) + " so'm"}</div>
                            </div>
                            <button class="w-8 h-8 rounded-lg bg-white/5 hover:bg-blue-500/20 text-gray-400 hover:text-blue-400 flex items-center justify-center transition-all"><i class="fa-solid fa-chevron-right"></i></button>
                        </div>
                    </div>
                `;
            });
        } else {
            emptyState.classList.remove('hidden');
        }
    } catch(e) {
        document.getElementById('deals-list').innerHTML = `<div class="text-center text-red-400 text-sm">Xatolik</div>`;
    }
});
</script>

<?php require_once __DIR__ . '/../components/blogger-footer.php'; ?>
