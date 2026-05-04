<?php
$pageTitle = "AI namunalari — Oldin va Keyin | Tiba AI";
$pageDescription = "Tiba AI yordamida yaratilgan infografika namunalarini ko'ring. Oldingi va keyingi holatini taqqoslang.";

require_once __DIR__ . '/../api/config.php';
$db = getDB();

$row1 = $db->query("SELECT * FROM showcase_samples WHERE is_active = 1 AND type IN ('carousel-top','carousel') AND image_path IS NOT NULL ORDER BY sort_order ASC, id DESC LIMIT 20")->fetchAll();
$row2 = $db->query("SELECT * FROM showcase_samples WHERE is_active = 1 AND type = 'carousel-bottom' AND image_path IS NOT NULL ORDER BY sort_order ASC, id DESC LIMIT 20")->fetchAll();
if (empty($row2) && !empty($row1)) $row2 = $row1;
if (empty($row1) && !empty($row2)) $row1 = $row2;
$hasCarousel = !empty($row1) || !empty($row2);

$basamples = $db->query("SELECT * FROM showcase_samples WHERE is_active = 1 AND type = 'before-after' AND before_image_path IS NOT NULL AND after_image_path IS NOT NULL ORDER BY sort_order ASC, id DESC")->fetchAll();
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<style>
/* Marquee */
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

/* Before/After slider */
.ba-slider { position:relative; overflow:hidden; border-radius:1rem; user-select:none; -webkit-user-select:none; cursor:ew-resize; }
.ba-slider img { display:block; width:100%; height:100%; object-fit:cover; pointer-events:none; user-select:none; -webkit-user-drag:none; }
.ba-before { position:absolute; inset:0; clip-path:inset(0 50% 0 0); }
.ba-before img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; }
.ba-handle { position:absolute; top:0; bottom:0; width:4px; background:#fff; cursor:ew-resize; z-index:10; left:50%; transform:translateX(-50%); }
.ba-handle::before { content:''; position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:36px; height:36px; border-radius:50%; background:#fff; box-shadow:0 2px 12px rgba(0,0,0,0.3); }
.ba-handle::after { content:'⇔'; position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); font-size:14px; color:#4f46e5; font-weight:bold; z-index:1; }
.ba-label { position:absolute; bottom:10px; padding:3px 10px; border-radius:6px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:1px; z-index:5; }
.ba-label-before { left:10px; background:rgba(239,68,68,0.85); color:#fff; }
.ba-label-after { right:10px; background:rgba(34,197,94,0.85); color:#fff; }

/* Fixed CTA */
.fixed-cta { position:fixed; bottom:0; left:0; right:0; z-index:80; padding:12px 16px; transform:translateY(100%); animation:slide-up-cta 0.5s ease-out 1s forwards; }
.fixed-cta-inner { max-width:600px; margin:0 auto; }
@keyframes slide-up-cta { to { transform:translateY(0); } }
@keyframes pulse-shadow { 0%,100%{box-shadow:0 0 20px rgba(99,102,241,0.4)} 50%{box-shadow:0 0 40px rgba(99,102,241,0.7),0 0 60px rgba(99,102,241,0.3)} }
.cta-pulse { animation:pulse-shadow 2s ease-in-out infinite; }
</style>

<div class="relative overflow-hidden">
    <!-- Hero -->
    <section class="py-12 sm:py-20 dot-pattern">
        <div class="absolute top-10 left-1/4 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-purple-600/10 rounded-full blur-3xl"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl sm:text-5xl font-extrabold mb-4" style="color:var(--text-heading)">
                AI <span class="gradient-text">namunalari</span>
            </h1>
            <p class="text-lg max-w-2xl mx-auto" style="color:var(--text-muted)">
                Tiba AI yordamida yaratilgan professional infografikalar. Oldingi va keyingi holatini taqqoslang.
            </p>
        </div>
    </section>

    <!-- ===== MARQUEE CAROUSEL ===== -->
    <?php if ($hasCarousel): ?>
    <section class="py-12 border-t border-white/5 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <h2 class="text-2xl font-bold" style="color:var(--text-heading)">
                <i class="fa-solid fa-images text-indigo-400 mr-2"></i>Infografika namunalari
            </h2>
        </div>

        <!-- Row 1: → o'ngga -->
        <div class="marquee-row mb-4">
            <div class="marquee-track right" oncontextmenu="return false;">
                <?php for ($dup = 0; $dup < 6; $dup++): ?>
                <?php foreach ($row1 as $s): ?>
                <div class="sample-card" onclick="openSampleLightbox('<?= htmlspecialchars($s['image_path']) ?>', '<?= htmlspecialchars(addslashes($s['title'] ?: '')) ?>')">
                    <div class="relative rounded-2xl overflow-hidden border shadow-lg aspect-[3/4]" style="border-color:var(--border-color)">
                        <img src="<?= htmlspecialchars($s['image_path']) ?>" alt="<?= htmlspecialchars($s['title'] ?: 'Namuna') ?>" class="w-full h-full object-cover select-none pointer-events-none" draggable="false" loading="lazy">
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

        <!-- Row 2: ← chapga -->
        <div class="marquee-row">
            <div class="marquee-track left" oncontextmenu="return false;">
                <?php for ($dup = 0; $dup < 6; $dup++): ?>
                <?php foreach ($row2 as $s): ?>
                <div class="sample-card" onclick="openSampleLightbox('<?= htmlspecialchars($s['image_path']) ?>', '<?= htmlspecialchars(addslashes($s['title'] ?: '')) ?>')">
                    <div class="relative rounded-2xl overflow-hidden border shadow-lg aspect-[3/4]" style="border-color:var(--border-color)">
                        <img src="<?= htmlspecialchars($s['image_path']) ?>" alt="<?= htmlspecialchars($s['title'] ?: 'Namuna') ?>" class="w-full h-full object-cover select-none pointer-events-none" draggable="false" loading="lazy">
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
    </section>
    <?php endif; ?>

    <!-- ===== BEFORE / AFTER ===== -->
    <?php if (!empty($basamples)): ?>
    <section class="py-12 sm:py-20 border-t border-white/5">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-10 text-center" style="color:var(--text-heading)">
                <i class="fa-solid fa-arrows-left-right text-purple-400 mr-2"></i>Oldin va Keyin
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($basamples as $idx => $ba): ?>
                <div class="max-w-sm mx-auto w-full">
                    <?php if ($ba['title']): ?>
                    <p class="font-bold text-xs mb-2 text-center" style="color:var(--text-heading)"><?= htmlspecialchars($ba['title']) ?></p>
                    <?php endif; ?>
                    <div class="ba-slider aspect-[3/4] rounded-2xl border shadow-lg" style="border-color:var(--border-color)" oncontextmenu="return false;" data-ba-id="<?= $idx ?>">
                        <img src="<?= htmlspecialchars($ba['after_image_path']) ?>" alt="Keyin" draggable="false">
                        <div class="ba-before">
                            <img src="<?= htmlspecialchars($ba['before_image_path']) ?>" alt="Oldin" draggable="false">
                        </div>
                        <div class="ba-handle"></div>
                        <span class="ba-label ba-label-before">Oldin</span>
                        <span class="ba-label ba-label-after">Keyin</span>
                    </div>
                    <div class="text-center mt-3">
                        <button onclick="openBALightbox('<?= htmlspecialchars($ba['before_image_path']) ?>', '<?= htmlspecialchars($ba['after_image_path']) ?>', '<?= htmlspecialchars(addslashes($ba['title'] ?: '')) ?>')" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all hover:scale-105" style="background:var(--bg-secondary);border:1px solid var(--border-color);color:var(--text-primary)">
                            <i class="fa-solid fa-eye"></i> Kattaroq ko'rish
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!$hasCarousel && empty($basamples)): ?>
    <section class="py-20">
        <div class="max-w-md mx-auto text-center glass-card p-10">
            <div class="text-5xl mb-4">🎨</div>
            <h3 class="text-xl font-bold mb-2" style="color:var(--text-heading)">Namunalar tez orada!</h3>
            <p style="color:var(--text-muted)">AI yordamida yaratilgan namunalar shu yerda paydo bo'ladi.</p>
        </div>
    </section>
    <?php endif; ?>
</div>

<!-- Lightbox -->
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

<!-- Fixed CTA -->
<div class="fixed-cta">
    <div class="fixed-cta-inner">
        <a href="/create" class="btn-primary w-full py-4 text-base font-bold cta-pulse flex items-center justify-center gap-2 rounded-2xl shadow-2xl" style="display:flex;">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
            Hoziroq infografika yaratish
            <i class="fa-solid fa-arrow-right text-sm"></i>
        </a>
    </div>
</div>

<script>
// Lightbox
function openSampleLightbox(src, title) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox-title').textContent = title || '';
    document.getElementById('sample-lightbox').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeSampleLightbox() {
    document.getElementById('sample-lightbox').classList.add('hidden');
    document.body.style.overflow = '';
}

// BA Lightbox — katta oynada before/after
function openBALightbox(beforeSrc, afterSrc, title) {
    let lb = document.getElementById('ba-lightbox');
    if (!lb) {
        lb = document.createElement('div');
        lb.id = 'ba-lightbox';
        lb.className = 'fixed inset-0 z-[90] flex items-center justify-center p-4';
        lb.style.cssText = 'background:rgba(0,0,0,0.85);backdrop-filter:blur(12px);';
        lb.innerHTML = `
            <div class="absolute inset-0" onclick="closeBALightbox()"></div>
            <div class="relative w-full max-w-xl animate-fade-in-up">
                <button onclick="closeBALightbox()" class="absolute -top-3 -right-3 z-20 w-10 h-10 rounded-full flex items-center justify-center text-white shadow-xl hover:scale-110 transition-transform" style="background:rgba(0,0,0,0.6);border:1px solid rgba(255,255,255,0.15)"><i class="fa-solid fa-xmark"></i></button>
                <p id="ba-lb-title" class="text-center text-sm font-semibold mb-3" style="color:rgba(255,255,255,0.7)"></p>
                <div class="ba-slider aspect-[3/4] rounded-2xl overflow-hidden shadow-2xl border" style="border-color:rgba(255,255,255,0.1)" oncontextmenu="return false;">
                    <img id="ba-lb-after" src="" alt="Keyin" draggable="false">
                    <div class="ba-before"><img id="ba-lb-before" src="" alt="Oldin" draggable="false"></div>
                    <div class="ba-handle"></div>
                    <span class="ba-label ba-label-before">Oldin</span>
                    <span class="ba-label ba-label-after">Keyin</span>
                </div>
            </div>`;
        document.body.appendChild(lb);
        initBASlider(lb.querySelector('.ba-slider'));
    }
    document.getElementById('ba-lb-before').src = beforeSrc;
    document.getElementById('ba-lb-after').src = afterSrc;
    document.getElementById('ba-lb-title').textContent = title || '';
    lb.classList.remove('hidden');
    lb.style.display = '';
    document.body.style.overflow = 'hidden';
}
function closeBALightbox() {
    const lb = document.getElementById('ba-lightbox');
    if (lb) { lb.style.display = 'none'; document.body.style.overflow = ''; }
}

// clip-path slider init
function initBASlider(slider) {
    const handle = slider.querySelector('.ba-handle');
    const before = slider.querySelector('.ba-before');
    if (!handle || !before) return;
    let isDragging = false;
    function update(clientX) {
        const rect = slider.getBoundingClientRect();
        let p = ((clientX - rect.left) / rect.width) * 100;
        p = Math.max(2, Math.min(98, p));
        before.style.clipPath = `inset(0 ${100 - p}% 0 0)`;
        handle.style.left = p + '%';
    }
    slider.addEventListener('mousedown', e => { isDragging = true; update(e.clientX); e.preventDefault(); });
    document.addEventListener('mousemove', e => { if (isDragging) update(e.clientX); });
    document.addEventListener('mouseup', () => isDragging = false);
    slider.addEventListener('touchstart', e => { isDragging = true; update(e.touches[0].clientX); }, { passive: true });
    document.addEventListener('touchmove', e => { if (isDragging) update(e.touches[0].clientX); }, { passive: true });
    document.addEventListener('touchend', () => isDragging = false);
}

// Init all BA sliders
document.querySelectorAll('.ba-slider').forEach(initBASlider);

// Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeSampleLightbox(); closeBALightbox(); }
});

// Right-click bloklash
document.addEventListener('contextmenu', e => {
    if (e.target.closest('.ba-slider') || e.target.closest('.marquee-row')) e.preventDefault();
});
</script>

<!-- Pastki CTA uchun footer ustidan padding -->
<div style="height: 80px;"></div>

<?php include __DIR__ . '/../components/footer.php'; ?>
