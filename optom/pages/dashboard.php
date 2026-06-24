<?php
$pageTitle = 'Tiba Optom – Dashboard';
require_once __DIR__ . '/../components/optom-header.php';
?>

<div class="p-4 md:p-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white">Xush kelibsiz, <span id="dash-name" class="gradient-text">Sotuvchi</span>! 📦</h1>
            <p class="text-gray-400 text-sm mt-1">Bugungi statistikangiz va so'nggi yangiliklar.</p>
        </div>
        <div class="flex gap-3">
            <a href="/optom/mahsulotlar" class="btn-primary py-2 px-4 rounded-xl text-sm whitespace-nowrap">
                <i class="fa-solid fa-plus mr-2"></i> Mahsulot qo'shish
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
        <div class="glass-card p-5 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fa-solid fa-boxes-stacked text-6xl text-teal-500"></i>
            </div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-teal-500/20 text-teal-400 flex items-center justify-center text-xl shadow-inner border border-teal-500/20">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Mahsulotlar</div>
                    <div class="text-2xl font-black text-white mt-1" id="stat-products">0</div>
                </div>
            </div>
        </div>
        
        <div class="glass-card p-5 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fa-solid fa-bell text-6xl text-blue-500"></i>
            </div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center text-xl shadow-inner border border-blue-500/20">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Yangi So'rovlar</div>
                    <div class="text-2xl font-black text-white mt-1" id="stat-new-orders">0</div>
                </div>
            </div>
        </div>

        <div class="glass-card p-5 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fa-solid fa-eye text-6xl text-purple-500"></i>
            </div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-xl shadow-inner border border-purple-500/20">
                    <i class="fa-solid fa-eye"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Ko'rishlar</div>
                    <div class="text-2xl font-black text-white mt-1" id="stat-views">0</div>
                </div>
            </div>
        </div>

        <div class="glass-card p-5 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fa-solid fa-star text-6xl text-amber-500"></i>
            </div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-xl shadow-inner border border-amber-500/20">
                    <i class="fa-solid fa-star"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Reyting</div>
                    <div class="text-2xl font-black text-white mt-1 flex items-baseline gap-1">
                        <span id="stat-rating">0.0</span> <span class="text-sm font-medium text-gray-400" id="stat-reviews">(0)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="glass-card p-6 h-full flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold text-white">So'nggi Buyurtmalar</h2>
                    <a href="/optom/buyurtmalar" class="text-xs text-teal-400 hover:text-teal-300 font-medium">Barchasi <i class="fa-solid fa-arrow-right ml-1"></i></a>
                </div>
                
                <div id="recent-orders-list" class="flex-1 space-y-3">
                    <div class="animate-pulse flex items-center gap-4 p-4 rounded-xl bg-white/5 border border-white/5">
                        <div class="w-10 h-10 rounded-full bg-white/10"></div>
                        <div class="flex-1 space-y-2"><div class="h-3 bg-white/10 rounded w-1/3"></div><div class="h-2 bg-white/5 rounded w-1/2"></div></div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="glass-card p-6 h-full">
                <h2 class="text-lg font-bold text-white mb-6">Tezkor Amallar</h2>
                <div class="space-y-3">
                    <a href="/optom/mahsulotlar" class="flex items-center gap-3 p-3 rounded-xl bg-teal-500/5 border border-teal-500/10 hover:bg-teal-500/10 transition-all group">
                        <div class="w-10 h-10 rounded-lg bg-teal-500/20 text-teal-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-white">Mahsulot qo'shish</div>
                            <div class="text-[10px] text-gray-500">Yangi mahsulot joylang</div>
                        </div>
                    </a>
                    <a href="/optom/profil" class="flex items-center gap-3 p-3 rounded-xl bg-purple-500/5 border border-purple-500/10 hover:bg-purple-500/10 transition-all group">
                        <div class="w-10 h-10 rounded-lg bg-purple-500/20 text-purple-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-pen"></i>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-white">Profilni tahrirlash</div>
                            <div class="text-[10px] text-gray-500">Kompaniya ma'lumotlari</div>
                        </div>
                    </a>
                    <a href="/optom" target="_blank" class="flex items-center gap-3 p-3 rounded-xl bg-blue-500/5 border border-blue-500/10 hover:bg-blue-500/10 transition-all group">
                        <div class="w-10 h-10 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-store"></i>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-white">Vitrinni ko'rish</div>
                            <div class="text-[10px] text-gray-500">Profilingiz qanday ko'rinishini tekshiring</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const token = localStorage.getItem('optom_token');
    if (!token) return;

    try {
        const data = await optomAPI('get_dashboard');
        if (!data.success) return;

        const s = data.stats;
        document.getElementById('stat-products').textContent = s.products;
        document.getElementById('stat-new-orders').textContent = s.new_orders;
        document.getElementById('stat-views').textContent = formatNum(s.views);
        document.getElementById('stat-rating').textContent = s.rating.toFixed(1);
        document.getElementById('stat-reviews').textContent = `(${s.review_count})`;

        // Dashboard name
        const res2 = await fetch('/api/optom-auth.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Optom-Token': token },
            body: JSON.stringify({ action: 'check' })
        });
        const d2 = await res2.json();
        if (d2.authenticated) {
            document.getElementById('dash-name').textContent = d2.seller.company_name;
        }

        // Recent orders
        const list = document.getElementById('recent-orders-list');
        if (data.recent_orders && data.recent_orders.length > 0) {
            list.innerHTML = data.recent_orders.map(o => {
                const statusMap = { new: ['Yangi', 'badge-blue'], accepted: ['Qabul qilindi', 'badge-emerald'], shipped: ['Yuborildi', 'badge-amber'], completed: ['Yakunlandi', 'badge-teal'], cancelled: ['Bekor qilindi', 'badge-red'] };
                const [statusText, statusClass] = statusMap[o.status] || ['Noma\'lum', 'badge-gray'];
                return `
                <div class="flex items-center gap-4 p-4 rounded-xl bg-white/[0.02] border border-white/5 hover:bg-white/[0.04] transition-all">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-500/20 to-emerald-500/20 flex items-center justify-center text-teal-400 font-bold text-sm flex-shrink-0">
                        ${o.customer_name.charAt(0).toUpperCase()}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-bold text-white truncate">${o.customer_name}</div>
                        <div class="text-xs text-gray-500 truncate">${o.product_name || 'Umumiy so\'rov'} · ${o.quantity} dona</div>
                    </div>
                    <span class="badge ${statusClass}">${statusText}</span>
                </div>`;
            }).join('');
        } else {
            list.innerHTML = '<div class="empty-state"><i class="fa-solid fa-inbox text-gray-600"></i><p class="text-sm text-gray-500">Hozircha buyurtmalar yo\'q</p></div>';
        }
    } catch (e) {
        console.error('Dashboard load error:', e);
    }
});
</script>

<?php require_once __DIR__ . '/../components/optom-footer.php'; ?>
