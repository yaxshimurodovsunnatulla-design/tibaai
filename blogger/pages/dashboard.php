<?php
$pageTitle = 'Bloger Panel – Dashboard';
require_once __DIR__ . '/../components/blogger-header.php';
?>

<div class="p-4 md:p-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white">Xush kelibsiz, <span id="dash-name" class="gradient-text">Bloger</span>! 👋</h1>
            <p class="text-gray-400 text-sm mt-1">Bugungi statistikangiz va so'nggi yangiliklar.</p>
        </div>
        <div class="flex gap-3">
            <a href="/blogger/takliflar" class="btn-primary py-2 px-4 rounded-xl text-sm whitespace-nowrap shadow-orange-500/20 shadow-lg hover:shadow-orange-500/40">
                <i class="fa-solid fa-bell mr-2"></i> Yangi Takliflar
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
        <!-- Stat Card 1 -->
        <div class="glass-card p-5 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fa-solid fa-wallet text-6xl text-orange-500"></i>
            </div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-orange-500/20 text-orange-400 flex items-center justify-center text-xl shadow-inner border border-orange-500/20">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Umumiy Daromad</div>
                    <div class="text-2xl font-black text-white mt-1" id="stat-earned">0 <span class="text-sm font-medium text-gray-400">so'm</span></div>
                </div>
            </div>
        </div>
        
        <!-- Stat Card 2 -->
        <div class="glass-card p-5 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fa-solid fa-handshake text-6xl text-blue-500"></i>
            </div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center text-xl shadow-inner border border-blue-500/20">
                    <i class="fa-solid fa-handshake"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Faol Bitimlar</div>
                    <div class="text-2xl font-black text-white mt-1" id="stat-active">0</div>
                </div>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="glass-card p-5 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fa-solid fa-check-circle text-6xl text-emerald-500"></i>
            </div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl shadow-inner border border-emerald-500/20">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Bajarilgan</div>
                    <div class="text-2xl font-black text-white mt-1" id="stat-completed">0</div>
                </div>
            </div>
        </div>

        <!-- Stat Card 4 -->
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

    <!-- Recent Deals / Offers -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2">
            <div class="glass-card p-6 h-full flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold text-white">So'nggi Takliflar</h2>
                    <a href="/blogger/takliflar" class="text-xs text-orange-400 hover:text-orange-300 font-medium">Barchasi <i class="fa-solid fa-arrow-right ml-1"></i></a>
                </div>
                
                <div id="recent-offers-list" class="flex-1 space-y-3">
                    <!-- Placeholder -->
                    <div class="animate-pulse flex items-center gap-4 p-4 rounded-xl bg-white/5 border border-white/5">
                        <div class="w-12 h-12 bg-white/10 rounded-xl"></div>
                        <div class="flex-1 space-y-2">
                            <div class="h-4 bg-white/10 rounded w-1/3"></div>
                            <div class="h-3 bg-white/10 rounded w-1/4"></div>
                        </div>
                        <div class="h-6 bg-white/10 rounded w-20"></div>
                    </div>
                </div>
                <div id="no-offers" class="hidden flex-1 flex flex-col items-center justify-center py-10 text-center">
                    <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center text-2xl text-gray-600 mb-3"><i class="fa-solid fa-inbox"></i></div>
                    <p class="text-gray-400 text-sm">Hozircha yangi takliflar yo'q</p>
                </div>
            </div>
        </div>

        <div>
            <div class="glass-card p-6 h-full">
                <h2 class="text-lg font-bold text-white mb-6">Profil Holati</h2>
                
                <div class="space-y-4">
                    <div class="p-4 rounded-xl bg-gradient-to-br from-orange-500/10 to-rose-500/10 border border-orange-500/20">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-info text-orange-400 mt-0.5"></i>
                            <div>
                                <h3 class="text-sm font-bold text-white mb-1">To'liq Profil</h3>
                                <p class="text-xs text-gray-400 leading-relaxed">Ko'proq takliflar olish uchun profilingizni (ijtimoiy tarmoqlar, narxlar va portfolio) to'liq to'ldiring.</p>
                                <a href="/blogger/profil" class="inline-block mt-3 text-xs font-bold text-orange-400 hover:text-orange-300">Profilni sozlash <i class="fa-solid fa-arrow-right ml-1"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Progress bars example -->
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-gray-400">Profil to'ldirilganligi</span>
                            <span class="text-white font-bold" id="profile-progress-text">--%</span>
                        </div>
                        <div class="h-2 w-full bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-orange-500 to-rose-500 rounded-full transition-all duration-1000" style="width: 0%;" id="profile-progress-bar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const token = localStorage.getItem('blogger_token');
    if (!token) return;

    try {
        const res = await fetch('/api/blogger-auth.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-Blogger-Token': token},
            body: JSON.stringify({action: 'check'})
        });
        const data = await res.json();
        if (data.authenticated) {
            document.getElementById('dash-name').textContent = data.blogger.display_name;
        }
    } catch(e) {}

    // Dashboard stats call
    try {
        const res = await fetch('/api/blogger-dashboard.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-Blogger-Token': token},
            body: JSON.stringify({action: 'get_stats'})
        });
        const data = await res.json();
        
        if (data.success) {
            document.getElementById('stat-earned').innerHTML = new Intl.NumberFormat('uz-UZ').format(data.stats.total_earned) + " <span class='text-sm font-medium text-gray-400'>so'm</span>";
            document.getElementById('stat-active').textContent = data.stats.active_deals;
            document.getElementById('stat-completed').textContent = data.stats.completed_deals;
            document.getElementById('stat-rating').textContent = Number(data.stats.rating).toFixed(1);
            document.getElementById('stat-reviews').textContent = `(${data.stats.total_reviews})`;
            
            // Progress
            const progress = data.stats.profile_completion || 0;
            document.getElementById('profile-progress-text').textContent = progress + '%';
            document.getElementById('profile-progress-bar').style.width = progress + '%';

            // Offers
            const offersList = document.getElementById('recent-offers-list');
            offersList.innerHTML = '';
            if (data.recent_offers && data.recent_offers.length > 0) {
                data.recent_offers.forEach(offer => {
                    offersList.innerHTML += `
                        <a href="/blogger/takliflar?id=${offer.id}" class="flex items-center justify-between p-4 rounded-xl bg-white/5 border border-white/5 hover:border-orange-500/30 hover:bg-white/10 transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center text-gray-400 group-hover:text-white group-hover:bg-orange-500/20 transition-all">
                                    <i class="fa-solid ${offer.deal_type === 'barter' ? 'fa-box-open' : 'fa-money-bill-wave'} text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-white">${offer.product_name}</h4>
                                    <div class="text-xs text-gray-400 mt-0.5">Format: <span class="text-gray-300">${offer.ad_format}</span></div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-bold text-white">${offer.deal_type === 'barter' ? 'Barter' : new Intl.NumberFormat('uz-UZ').format(offer.offered_price) + " so'm"}</div>
                                <div class="text-[10px] text-gray-500 mt-1">${offer.created_at}</div>
                            </div>
                        </a>
                    `;
                });
            } else {
                document.getElementById('no-offers').classList.remove('hidden');
            }
        }
    } catch(e) {}
});
</script>

<?php require_once __DIR__ . '/../components/blogger-footer.php'; ?>
