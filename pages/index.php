<?php
$pageTitle = 'Tiba AI – Uzum va Wildberries uchun AI Infografika yaratish';
$pageDescription = 'Marketplace uchun professional infografikalarni sun\'iy intellekt yordamida bir necha soniyada yarating. Uzum, Wildberries va Instagram uchun eng yaxshi dizayn yechimi.';

// Real statistikani bazadan olish
try {
    require_once __DIR__ . '/../api/config.php';
    $db = getDB();
    $genCount = (int)$db->query("SELECT COUNT(*) FROM generations")->fetchColumn();
    $userCount = (int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn();
} catch (Exception $e) {
    $genCount = 0;
    $userCount = 0;
}

// Raqamlarni chiroyli formatlash
function formatStat($num) {
    if ($num >= 1000000) return round($num / 1000000, 1) . 'M+';
    if ($num >= 1000) return round($num / 1000, 1) . 'K+';
    if ($num >= 100) return $num . '+';
    if ($num > 0) return $num;
    return '0';
}
$genDisplay = formatStat($genCount);
$userDisplay = formatStat($userCount);
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="relative overflow-hidden">
    <!-- Hero Section -->
    <section class="relative py-20 sm:py-32 dot-pattern">
        <!-- Floating orbs -->
        <div class="absolute top-20 left-10 w-72 h-72 bg-indigo-600/20 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-purple-600/15 rounded-full blur-3xl animate-float" style="animation-delay: -3s"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 mb-8 animate-fade-in">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                <span class="text-xs font-medium text-indigo-300">AI bilan ishlaydigan infografika generatori</span>
            </div>

            <!-- Title -->
            <h1 class="text-4xl sm:text-5xl md:text-7xl font-extrabold leading-tight mb-6 animate-fade-in-up">
                <span class="text-white">Tiba AI –</span>
                <br />
                <span class="gradient-text">Infografika yaratadigan</span>
                <br />
                <span class="text-white">sun'iy intellekt</span>
            </h1>

            <!-- Subtitle -->
            <p class="max-w-2xl mx-auto text-lg sm:text-xl text-gray-400 mb-10 animate-fade-in-up" style="animation-delay: 0.2s">
                Marketplace va Instagram uchun professional infografikalarni bir necha soniyada yarating. AI sizning mahsulotingiz uchun eng yaxshi dizaynni tanlaydi.
            </p>

            <!-- CTAs -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in-up" style="animation-delay: 0.4s">
                <a href="/create" class="btn-primary text-lg px-10 py-4 animate-pulse-glow" id="hero-cta">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Boshlash
                </a>
                <a href="/pricing" class="btn-secondary text-lg">
                    Narxlarni ko'rish
                </a>
            </div>

            <!-- Stats -->
            <div class="mt-16 grid grid-cols-3 gap-8 max-w-lg mx-auto animate-fade-in-up" style="animation-delay: 0.6s">
                <div>
                    <div class="text-2xl sm:text-3xl font-bold gradient-text" data-count="<?= $genCount ?>"><?= htmlspecialchars($genDisplay) ?></div>
                    <div class="text-xs sm:text-sm text-gray-500 mt-1">Yaratilgan infografikalar</div>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold gradient-text" data-count="<?= $userCount ?>"><?= htmlspecialchars($userDisplay) ?></div>
                    <div class="text-xs sm:text-sm text-gray-500 mt-1">Faol foydalanuvchilar</div>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold gradient-text">10x</div>
                    <div class="text-xs sm:text-sm text-gray-500 mt-1">Tezroq dizayn</div>
                </div>
            </div>
        </div>
    </section>

<script>
// Counter animation
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-count]').forEach(el => {
        const target = parseInt(el.dataset.count);
        if (!target || target <= 0) return;
        const duration = 2000;
        const startTime = performance.now();
        const suffix = target >= 1000000 ? 'M+' : target >= 1000 ? 'K+' : target >= 100 ? '+' : '';
        const divisor = target >= 1000000 ? 1000000 : target >= 1000 ? 1000 : 1;
        
        function update(now) {
            const elapsed = now - startTime;
            const progress = Math.min(elapsed / duration, 1);
            // Ease out cubic
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = Math.round(eased * target);
            
            if (divisor > 1) {
                const display = (current / divisor).toFixed(current / divisor >= 10 ? 0 : 1);
                el.textContent = display + suffix;
            } else {
                el.textContent = current + suffix;
            }
            
            if (progress < 1) requestAnimationFrame(update);
        }
        
        // IntersectionObserver — faqat ko'ringanda animatsiya boshlash
        const observer = new IntersectionObserver(entries => {
            if (entries[0].isIntersecting) {
                observer.disconnect();
                requestAnimationFrame(update);
            }
        }, { threshold: 0.5 });
        observer.observe(el);
    });
});
</script>

    <!-- Features Section -->
    <section class="py-20 sm:py-28 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
                    AI bilan <span class="gradient-text">nima qila olasiz?</span>
                </h2>
                <p class="text-gray-400 max-w-xl mx-auto">
                    Tiba AI turli xil infografika turlarini yaratishda yordam beradi.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="glass-card-hover p-8 group animate-slide-up">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center mb-6 text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016A3.001 3.001 0 0021 9.349m-18 0a2.999 2.999 0 00.92-2.169L4.5 3.75h15l.58 3.43A2.998 2.998 0 0021 9.35" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Marketplace dizayn</h3>
                    <p class="text-gray-400 leading-relaxed">Uzum, Wildberries va boshqa marketplace'lar uchun professional infografika yarating.</p>
                </div>

                <div class="glass-card-hover p-8 group animate-slide-up" style="animation-delay: 0.15s">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-400 flex items-center justify-center mb-6 text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Instagram reklama post</h3>
                    <p class="text-gray-400 leading-relaxed">Ko'zni qamashtiradigan Instagram reklama postlarini AI bilan yarating.</p>
                </div>

                <div class="glass-card-hover p-8 group animate-slide-up" style="animation-delay: 0.3s">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-400 flex items-center justify-center mb-6 text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">AI avtomatik matn + ikonka</h3>
                    <p class="text-gray-400 leading-relaxed">AI mahsulot tavsiflarini yozadi va mos ikonkalarni tanlaydi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section class="py-20 sm:py-28 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
                    Qanday <span class="gradient-text">ishlaydi?</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center group">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600/20 to-purple-600/20 border border-indigo-500/20 mb-6 group-hover:border-indigo-500/40 transition-colors">
                        <span class="text-2xl font-bold gradient-text">01</span>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Ma'lumot kiriting</h3>
                    <p class="text-gray-400 text-sm">Mahsulot nomi, xususiyatlari va stilni tanlang.</p>
                </div>
                <div class="text-center group">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600/20 to-purple-600/20 border border-indigo-500/20 mb-6 group-hover:border-indigo-500/40 transition-colors">
                        <span class="text-2xl font-bold gradient-text">02</span>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">AI yaratadi</h3>
                    <p class="text-gray-400 text-sm">Sun'iy intellekt sizning malumotlaringiz asosida infografika yaratadi.</p>
                </div>
                <div class="text-center group">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600/20 to-purple-600/20 border border-indigo-500/20 mb-6 group-hover:border-indigo-500/40 transition-colors">
                        <span class="text-2xl font-bold gradient-text">03</span>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Yuklab oling</h3>
                    <p class="text-gray-400 text-sm">Tayyor infografikani yuklab oling va foydalaning.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 sm:py-28">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-card p-10 sm:p-16 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/10 to-purple-600/10"></div>
                <div class="relative">
                    <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
                        Hoziroq boshlang!
                    </h2>
                    <p class="text-gray-400 mb-8 max-w-lg mx-auto">
                        Birinchi infografikangizni bepul yarating. Hech qanday kredit karta talab qilinmaydi.
                    </p>
                    <a href="/create" class="btn-primary text-lg px-10 py-4">
                        Bepul boshlash
                        <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
