<?php
$pageTitle = 'Tiba AI – Onlayn Savdo uchun AI Yordamchi | 90% ishni avtomatlashtiring';
$pageDescription = 'Uzum, Wildberries va boshqa marketplace sotuvchilari uchun AI yordamchi. Infografika, analitika, hisobotlar, ETTY va boshqa vositalar bilan ishingizni 90% gacha yengillashtiring.';

try {
    require_once __DIR__ . '/../api/config.php';
    $db = getDB();
    $genCount = (int)$db->query("SELECT COUNT(*) FROM generations")->fetchColumn();
    $userCount = (int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $serviceCount = (int)$db->query("SELECT COUNT(*) FROM services WHERE is_active = 1")->fetchColumn();
} catch (Exception $e) {
    $genCount = 0; $userCount = 0; $serviceCount = 0;
}

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

<style>
@keyframes gradient-shift { 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
@keyframes counter-glow { 0%,100%{text-shadow:0 0 20px rgba(99,102,241,0.3)} 50%{text-shadow:0 0 40px rgba(99,102,241,0.6)} }
@keyframes marquee { 0%{transform:translateX(0)} 100%{transform:translateX(-50%)} }
.gradient-shift { background-size:200% 200%; animation:gradient-shift 8s ease infinite }
.stat-glow { animation:counter-glow 3s ease-in-out infinite }
.category-card { transition:all 0.4s cubic-bezier(0.4,0,0.2,1) }
.category-card:hover { transform:translateY(-8px) scale(1.02) }
.marquee-track { animation:marquee 30s linear infinite }
.hero-badge { background:linear-gradient(135deg,rgba(99,102,241,0.15),rgba(168,85,247,0.15)); border:1px solid rgba(99,102,241,0.25) }
</style>

<div class="relative overflow-hidden">

    <!-- ===== HERO ===== -->
    <section class="relative py-20 sm:py-32 dot-pattern">
        <div class="absolute top-20 left-10 w-72 h-72 bg-indigo-600/20 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-purple-600/15 rounded-full blur-3xl animate-float" style="animation-delay:-3s"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-gradient-to-br from-indigo-600/5 via-purple-600/5 to-pink-600/5 rounded-full blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full hero-badge mb-8 animate-fade-in">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                <span class="text-xs font-semibold text-indigo-300">Marketplace sotuvchilar uchun #1 AI yordamchi</span>
            </div>

            <h1 class="text-4xl sm:text-5xl md:text-7xl font-extrabold leading-tight mb-6 animate-fade-in-up">
                <span class="text-white">Onlayn savdo ishlaringizni</span><br/>
                <span class="gradient-text">90% gacha</span>
                <span class="text-white"> yengillashtiring</span>
            </h1>

            <p class="max-w-2xl mx-auto text-lg sm:text-xl text-gray-400 mb-10 animate-fade-in-up" style="animation-delay:0.2s">
                Infografika, kartochka sozlash, analitika, hisobotlar, ETTY va boshqa vositalar — barchasi bitta platformada. Uzum va Wildberries sotuvchilari uchun yaratilgan.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in-up" style="animation-delay:0.4s">
                <button onclick="toggleStartPopup(this)" class="btn-primary text-lg px-10 py-4 animate-pulse-glow relative" id="hero-cta">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Boshlash
                </button>
                <a href="/pricing" class="btn-secondary text-lg">Narxlarni ko'rish</a>
            </div>

            <!-- Stats -->
            <div class="mt-16 grid grid-cols-2 sm:grid-cols-4 gap-6 max-w-2xl mx-auto animate-fade-in-up" style="animation-delay:0.6s">
                <div>
                    <div class="text-2xl sm:text-3xl font-bold gradient-text stat-glow" data-count="<?= $genCount ?>"><?= htmlspecialchars($genDisplay) ?></div>
                    <div class="text-xs sm:text-sm text-gray-500 mt-1">Ishlar bajarildi</div>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold gradient-text stat-glow" data-count="<?= $userCount ?>"><?= htmlspecialchars($userDisplay) ?></div>
                    <div class="text-xs sm:text-sm text-gray-500 mt-1">Faol sotuvchilar</div>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold gradient-text"><?= $serviceCount + 5 ?>+</div>
                    <div class="text-xs sm:text-sm text-gray-500 mt-1">AI vositalar</div>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold gradient-text">90%</div>
                    <div class="text-xs sm:text-sm text-gray-500 mt-1">Vaqt tejash</div>
                </div>
            </div>
        </div>
    </section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-count]').forEach(el => {
        const target = parseInt(el.dataset.count);
        if (!target || target <= 0) return;
        const duration = 2000, startTime = performance.now();
        const suffix = target >= 1000000 ? 'M+' : target >= 1000 ? 'K+' : target >= 100 ? '+' : '';
        const divisor = target >= 1000000 ? 1000000 : target >= 1000 ? 1000 : 1;
        function update(now) {
            const progress = Math.min((now - startTime) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = Math.round(eased * target);
            el.textContent = divisor > 1 ? (current / divisor).toFixed(current / divisor >= 10 ? 0 : 1) + suffix : current + suffix;
            if (progress < 1) requestAnimationFrame(update);
        }
        const obs = new IntersectionObserver(e => { if (e[0].isIntersecting) { obs.disconnect(); requestAnimationFrame(update); } }, {threshold:0.5});
        obs.observe(el);
    });
});
</script>

    <!-- ===== NAMUNALAR CAROUSEL ===== -->
    <?php
    try {
        $sDb = getDB();
        $row1 = $sDb->query("SELECT id, title, image_path FROM showcase_samples WHERE is_active = 1 AND type IN ('carousel-top','carousel') AND image_path IS NOT NULL ORDER BY sort_order ASC, id DESC LIMIT 20")->fetchAll();
        $row2 = $sDb->query("SELECT id, title, image_path FROM showcase_samples WHERE is_active = 1 AND type = 'carousel-bottom' AND image_path IS NOT NULL ORDER BY sort_order ASC, id DESC LIMIT 20")->fetchAll();
    } catch (Exception $e) { $row1 = []; $row2 = []; }
    if (empty($row2) && !empty($row1)) $row2 = $row1;
    if (empty($row1) && !empty($row2)) $row1 = $row2;
    $hasCarousel = !empty($row1) || !empty($row2);
    ?>
    <?php if ($hasCarousel): ?>
    <style>
    .marquee-row { overflow: hidden; position: relative; }
    .marquee-row::before, .marquee-row::after { content:''; position:absolute; top:0; bottom:0; width:60px; z-index:2; pointer-events:none; }
    .marquee-row::before { left:0; background:linear-gradient(to right, var(--bg-primary), transparent); }
    .marquee-row::after { right:0; background:linear-gradient(to left, var(--bg-primary), transparent); }
    .marquee-track { display:flex; gap:16px; width:max-content; }
    .marquee-track.right { animation: marquee-right 18s linear infinite; }
    .marquee-track.left { animation: marquee-left 18s linear infinite; }
    .marquee-row:hover .marquee-track { animation-play-state: paused; }
    @keyframes marquee-right { 0%{transform:translateX(0)} 100%{transform:translateX(calc(-100% / 3))} }
    @keyframes marquee-left { 0%{transform:translateX(calc(-100% / 3))} 100%{transform:translateX(0)} }
    .sample-card { flex-shrink:0; width:200px; cursor:pointer; transition:transform 0.3s, box-shadow 0.3s; }
    .sample-card:hover { transform:scale(1.05); z-index:3; box-shadow:0 12px 40px rgba(99,102,241,0.2); }
    @media(min-width:640px) { .sample-card { width:240px; } }
    </style>

    <section class="py-16 sm:py-24 border-t border-white/5 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold mb-4" style="color:var(--text-heading)">
                    AI yaratgan <span class="gradient-text">namunalar</span>
                </h2>
                <p style="color:var(--text-muted)" class="max-w-xl mx-auto">Tiba AI yordamida yaratilgan infografikalar va dizaynlarni ko'ring</p>
            </div>
        </div>

        <!-- Row 1: → o'ngga aylanadi -->
        <div class="marquee-row mb-4">
            <div class="marquee-track right" oncontextmenu="return false;">
                <?php for ($dup = 0; $dup < 6; $dup++): ?>
                <?php foreach ($row1 as $s): ?>
                <div class="sample-card" onclick="openSampleLightbox('<?= htmlspecialchars($s['image_path']) ?>', '<?= htmlspecialchars(addslashes($s['title'] ?: '')) ?>')">
                    <div class="relative rounded-2xl overflow-hidden border shadow-lg aspect-[3/4]" style="border-color:var(--border-color)">
                        <img src="<?= htmlspecialchars($s['image_path']) ?>" 
                             alt="<?= htmlspecialchars($s['title'] ?: 'Namuna') ?>" 
                             class="w-full h-full object-cover select-none pointer-events-none"
                             draggable="false" loading="lazy">
                        <div class="absolute inset-0" style="user-select:none;"></div>
                        <?php if ($s['title']): ?>
                        <div class="absolute bottom-0 inset-x-0 p-3 bg-gradient-to-t from-black/80 to-transparent">
                            <p class="text-white text-xs font-semibold truncate"><?= htmlspecialchars($s['title']) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Row 2: ← chapga aylanadi -->
        <div class="marquee-row">
            <div class="marquee-track left" oncontextmenu="return false;">
                <?php for ($dup = 0; $dup < 6; $dup++): ?>
                <?php foreach ($row2 as $s): ?>
                <div class="sample-card" onclick="openSampleLightbox('<?= htmlspecialchars($s['image_path']) ?>', '<?= htmlspecialchars(addslashes($s['title'] ?: '')) ?>')">
                    <div class="relative rounded-2xl overflow-hidden border shadow-lg aspect-[3/4]" style="border-color:var(--border-color)">
                        <img src="<?= htmlspecialchars($s['image_path']) ?>" 
                             alt="<?= htmlspecialchars($s['title'] ?: 'Namuna') ?>" 
                             class="w-full h-full object-cover select-none pointer-events-none"
                             draggable="false" loading="lazy">
                        <div class="absolute inset-0" style="user-select:none;"></div>
                        <?php if ($s['title']): ?>
                        <div class="absolute bottom-0 inset-x-0 p-3 bg-gradient-to-t from-black/80 to-transparent">
                            <p class="text-white text-xs font-semibold truncate"><?= htmlspecialchars($s['title']) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endfor; ?>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-10">
            <a href="/namunalar" class="btn-secondary inline-flex items-center gap-2 px-8 py-3 text-base">
                <i class="fa-solid fa-images"></i>
                Ko'proq namunalar ko'rish
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
            <a href="/create" class="btn-primary inline-flex items-center gap-2 px-8 py-3 text-base">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                Hoziroq infografika yaratish
            </a>
        </div>
    </section>

    <!-- Sample Lightbox -->
    <div id="sample-lightbox" class="hidden fixed inset-0 z-[80] flex items-center justify-center p-4" oncontextmenu="return false;">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" onclick="closeSampleLightbox()"></div>
        <div class="relative max-w-lg w-full animate-fade-in-up">
            <button onclick="closeSampleLightbox()" class="absolute -top-3 -right-3 z-10 w-10 h-10 rounded-full flex items-center justify-center shadow-xl text-white hover:scale-110 transition-transform" style="background:rgba(0,0,0,0.6);border:1px solid rgba(255,255,255,0.15)">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="rounded-3xl overflow-hidden shadow-2xl border" style="border-color:var(--border-color)">
                <img id="lightbox-img" src="" alt="" class="w-full h-auto select-none pointer-events-none" draggable="false">
                <div class="absolute inset-0" style="user-select:none;"></div>
            </div>
            <p id="lightbox-title" class="text-center text-sm font-semibold mt-3" style="color:var(--text-muted)"></p>
        </div>
    </div>

    <script>
    function openSampleLightbox(src, title) {
        const lb = document.getElementById('sample-lightbox');
        document.getElementById('lightbox-img').src = src;
        document.getElementById('lightbox-title').textContent = title || '';
        lb.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeSampleLightbox() {
        document.getElementById('sample-lightbox').classList.add('hidden');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSampleLightbox(); });
    </script>
    <?php endif; ?>

    <!-- ===== CATEGORIES: Nima qila olasiz? ===== -->
    <section class="py-20 sm:py-28 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
                    Tiba AI bilan <span class="gradient-text">nima qila olasiz?</span>
                </h2>
                <p class="text-gray-400 max-w-xl mx-auto">Marketplace sotuvchisining deyarli barcha ishlarini AI yordamida bajarish mumkin.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- 1. Dizayn & Vizual -->
                <a href="/create" class="category-card glass-card-hover p-8 group">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-palette text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Dizayn & Vizual</h3>
                    <p class="text-gray-400 leading-relaxed text-sm mb-4">Infografika, kartochka dizayni, fotosesiya, fashion AI — barcha vizual ishlarni AI bajaradi.</p>
                    <div class="flex flex-wrap gap-1.5">
                        <span class="px-2 py-0.5 rounded-full bg-blue-500/10 text-blue-400 text-[10px] font-semibold border border-blue-500/20">Infografika</span>
                        <span class="px-2 py-0.5 rounded-full bg-cyan-500/10 text-cyan-400 text-[10px] font-semibold border border-cyan-500/20">Fotosesiya</span>
                        <span class="px-2 py-0.5 rounded-full bg-indigo-500/10 text-indigo-400 text-[10px] font-semibold border border-indigo-500/20">Kartochka</span>
                    </div>
                </a>

                <!-- 2. Analitika -->
                <a href="/sotuvlar-analitikasi" class="category-card glass-card-hover p-8 group">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-400 flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-chart-line text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Analitika & Tahlil</h3>
                    <p class="text-gray-400 leading-relaxed text-sm mb-4">Sotuvlaringizni tahlil qiling, trendlarni kuzating, yo'qolgan tovarlarni aniqlang.</p>
                    <div class="flex flex-wrap gap-1.5">
                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 text-[10px] font-semibold border border-emerald-500/20">Sotuvlar</span>
                        <span class="px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-400 text-[10px] font-semibold border border-amber-500/20">Hisobotlar</span>
                    </div>
                </a>

                <!-- 3. Kalkulyatorlar -->
                <a href="/instrumentlar" class="category-card glass-card-hover p-8 group">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-400 flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-calculator text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Kalkulyatorlar</h3>
                    <p class="text-gray-400 leading-relaxed text-sm mb-4">STUV, QQS limiti va boshqa hisob-kitoblarni tez va aniq bajaring.</p>
                    <div class="flex flex-wrap gap-1.5">
                        <span class="px-2 py-0.5 rounded-full bg-violet-500/10 text-violet-400 text-[10px] font-semibold border border-violet-500/20">STUV</span>
                        <span class="px-2 py-0.5 rounded-full bg-fuchsia-500/10 text-fuchsia-400 text-[10px] font-semibold border border-fuchsia-500/20">QQS</span>
                    </div>
                </a>

                <!-- 4. Hujjatlar -->
                <a href="/didox-etty" class="category-card glass-card-hover p-8 group">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-file-invoice text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Hujjatlar & ETTY</h3>
                    <p class="text-gray-400 leading-relaxed text-sm mb-4">Didox orqali ETTY yukxatlarini avtomatik yarating. Buyurtmalarga bir tugma bilan rasmiylashtiring.</p>
                    <div class="flex flex-wrap gap-1.5">
                        <span class="px-2 py-0.5 rounded-full bg-cyan-500/10 text-cyan-400 text-[10px] font-semibold border border-cyan-500/20">ETTY</span>
                        <span class="px-2 py-0.5 rounded-full bg-blue-500/10 text-blue-400 text-[10px] font-semibold border border-blue-500/20">Didox</span>
                    </div>
                </a>

                <!-- 5. Smart Matn -->
                <a href="/smart-matn" class="category-card glass-card-hover p-8 group">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-400 flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-pen-fancy text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Smart Matn & SEO</h3>
                    <p class="text-gray-400 leading-relaxed text-sm mb-4">Tovar tavsiflarini AI yozadi. SEO-optimallashtirilgan sarlavha va kalit so'zlar.</p>
                    <div class="flex flex-wrap gap-1.5">
                        <span class="px-2 py-0.5 rounded-full bg-pink-500/10 text-pink-400 text-[10px] font-semibold border border-pink-500/20">Tavsif</span>
                        <span class="px-2 py-0.5 rounded-full bg-rose-500/10 text-rose-400 text-[10px] font-semibold border border-rose-500/20">SEO</span>
                    </div>
                </a>

                <!-- 6. Marketing -->
                <a href="/insta-link" class="category-card glass-card-hover p-8 group">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-600 via-pink-500 to-orange-400 flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-brands fa-instagram text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Marketing & Reklama</h3>
                    <p class="text-gray-400 leading-relaxed text-sm mb-4">InstaLink AI, video yaratish va Instagram reklama postlarini avtomatlashitring.</p>
                    <div class="flex flex-wrap gap-1.5">
                        <span class="px-2 py-0.5 rounded-full bg-purple-500/10 text-purple-400 text-[10px] font-semibold border border-purple-500/20">InstaLink</span>
                        <span class="px-2 py-0.5 rounded-full bg-pink-500/10 text-pink-400 text-[10px] font-semibold border border-pink-500/20">Video AI</span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- ===== HOW IT WORKS ===== -->
    <section class="py-20 sm:py-28 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
                    Qanday <span class="gradient-text">ishlaydi?</span>
                </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center group">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600/20 to-purple-600/20 border border-indigo-500/20 mb-6 group-hover:border-indigo-500/40 transition-colors">
                        <span class="text-2xl font-bold gradient-text">01</span>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Vositani tanlang</h3>
                    <p class="text-gray-400 text-sm">Dizayn, analitika, kalkulyator yoki boshqa kerakli instrumentni tanlang.</p>
                </div>
                <div class="text-center group">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600/20 to-purple-600/20 border border-indigo-500/20 mb-6 group-hover:border-indigo-500/40 transition-colors">
                        <span class="text-2xl font-bold gradient-text">02</span>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Ma'lumot kiriting</h3>
                    <p class="text-gray-400 text-sm">Mahsulot ma'lumotlari, do'kon API kaliti yoki kerakli parametrlarni kiriting.</p>
                </div>
                <div class="text-center group">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600/20 to-purple-600/20 border border-indigo-500/20 mb-6 group-hover:border-indigo-500/40 transition-colors">
                        <span class="text-2xl font-bold gradient-text">03</span>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">AI ishlaydi</h3>
                    <p class="text-gray-400 text-sm">Sun'iy intellekt bir necha soniyada natijani tayyorlaydi.</p>
                </div>
                <div class="text-center group">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600/20 to-purple-600/20 border border-indigo-500/20 mb-6 group-hover:border-indigo-500/40 transition-colors">
                        <span class="text-2xl font-bold gradient-text">04</span>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Foydalaning</h3>
                    <p class="text-gray-400 text-sm">Tayyor natijani yuklab oling va biznesingizda foydalaning.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== WHY TIBA AI ===== -->
    <section class="py-20 sm:py-28 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
                    Nega aynan <span class="gradient-text">Tiba AI?</span>
                </h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="glass-card p-6 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center mb-4 mx-auto shadow-lg">
                        <i class="fa-solid fa-bolt text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Tezkorlik</h3>
                    <p class="text-gray-400 text-sm">Soatlab qiladigan ishlarni soniyalarda bajaring</p>
                </div>
                <div class="glass-card p-6 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center mb-4 mx-auto shadow-lg">
                        <i class="fa-solid fa-bullseye text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Marketplace uchun</h3>
                    <p class="text-gray-400 text-sm">Uzum va Wildberries sotuvchilari uchun maxsus yaratilgan</p>
                </div>
                <div class="glass-card p-6 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center mb-4 mx-auto shadow-lg">
                        <i class="fa-solid fa-robot text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">AI quvvati</h3>
                    <p class="text-gray-400 text-sm">Eng zamonaviy AI texnologiyalari asosida ishlaydi</p>
                </div>
                <div class="glass-card p-6 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-yellow-500 to-amber-500 flex items-center justify-center mb-4 mx-auto shadow-lg">
                        <i class="fa-solid fa-coins text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Tejamkor</h3>
                    <p class="text-gray-400 text-sm">Dizayner, yozuvchi va tahlilchi yollashdan ko'ra arzon</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CTA ===== -->
    <section class="py-20 sm:py-28">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-card p-10 sm:p-16 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/10 to-purple-600/10"></div>
                <div class="relative">
                    <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">Hoziroq boshlang!</h2>
                    <p class="text-gray-400 mb-8 max-w-lg mx-auto">Hozir ro'yxatdan o'ting — <span class="text-amber-400 font-bold">5 ta tangani mutlaqo tekinga</span> qo'lga kiriting va AI yordamchingizni sinab ko'ring!</p>
                    <button onclick="toggleStartPopup(this)" class="btn-primary text-lg px-10 py-4 relative">
                        Boshlash
                        <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Start Popup -->
<div id="start-popup" class="hidden fixed inset-0 z-[90] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeStartPopup()"></div>
    <div class="relative w-full max-w-sm yordamchi-dropdown-panel border rounded-3xl shadow-2xl p-6 animate-fade-in-up">
        <button onclick="closeStartPopup()" class="absolute top-3 right-3 w-8 h-8 flex items-center justify-center rounded-xl text-gray-400 hover:text-white hover:bg-white/10 transition-colors">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="text-center mb-5">
            <h3 class="text-lg font-bold" style="color:var(--text-heading)">Nimadan boshlaysiz?</h3>
            <p class="text-sm mt-1" style="color:var(--text-muted)">Kerakli yo'nalishni tanlang</p>
        </div>
        <div class="grid grid-cols-1 gap-3">
            <a href="/create" class="mob-card-infografika flex items-center gap-4 p-4 rounded-2xl border active:scale-[0.97] transition-all">
                <div class="mob-icon-box-indigo w-12 h-12 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                    <i class="fa-solid fa-wand-magic-sparkles text-lg" style="color:#fff"></i>
                </div>
                <div>
                    <div class="text-sm font-bold" style="color:var(--text-heading)">Infografika yaratish</div>
                    <div class="text-xs" style="color:var(--text-muted)">AI yordamida dizayn yarating</div>
                </div>
            </a>
            <a href="/instrumentlar" class="mob-card-instrumentlar flex items-center gap-4 p-4 rounded-2xl border active:scale-[0.97] transition-all">
                <div class="mob-icon-box-violet w-12 h-12 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                    <i class="fa-solid fa-toolbox text-lg" style="color:#fff"></i>
                </div>
                <div>
                    <div class="text-sm font-bold" style="color:var(--text-heading)">Instrumentlar</div>
                    <div class="text-xs" style="color:var(--text-muted)">Yordamchi vositalar</div>
                </div>
            </a>
        </div>
    </div>
</div>

<script>
function toggleStartPopup(btn) {
    document.getElementById('start-popup').classList.remove('hidden');
}
function closeStartPopup() {
    document.getElementById('start-popup').classList.add('hidden');
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeStartPopup(); });
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
