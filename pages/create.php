<?php
require_once __DIR__ . '/../api/config.php';
$pageTitle = 'Infografika Yaratish – Tiba AI';
$pageDescription = 'Mahsulotingiz uchun professional dizayn va infografikalarni AI yordamida tanlang va yarating. Uzum va Wildberries uchun maxsus uslublar.';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="py-12 sm:py-20 relative overflow-hidden">
    <!-- Background elements -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-600/10 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-16">
            <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-4">
                Nimani <span class="gradient-text">yaratamiz?</span>
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg">
                Tiba AI bilan professional marketplace vizuallarini yaratish hech qachon bunchalik oson bo'lmagan.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            try {
                $db = getDB();
                $stmt = $db->query("SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order ASC");
                $services = $stmt->fetchAll();

                foreach ($services as $s):
                    $videoUrl = !empty($s['video_url']) ? $s['video_url'] : '';
                ?>
                <!-- <?= $s['name'] ?> -->
                <div class="group relative glass-card border border-white/5 hover:border-white/20 transition-all duration-300 hover:-translate-y-2 flex flex-col h-full overflow-hidden rounded-3xl">
                    <!-- Gradient overlay on hover -->
                    <div class="absolute inset-0 bg-gradient-to-br <?= $s['gradient'] ?> opacity-0 group-hover:opacity-5 transition-opacity duration-300"></div>

                    <!-- Card link area -->
                    <a href="/<?= $s['slug'] ?>" class="relative z-10 flex flex-col p-8 flex-1">
                        <div class="mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br <?= $s['gradient'] ?> flex items-center justify-center text-2xl shadow-lg shadow-black/20 group-hover:scale-110 transition-transform duration-300">
                                <i class="<?= $s['icon'] ?> text-white"></i>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 mb-3">
                            <h3 class="text-xl font-bold text-white uppercase tracking-tight"><?= $s['name'] ?></h3>
                            <?php if ($s['badge']): ?>
                                <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-white/5 text-gray-400 border border-white/5 group-hover:border-white/20 transition-colors uppercase tracking-wider"><?= $s['badge'] ?></span>
                            <?php endif; ?>
                        </div>
                        <p class="text-gray-400 text-sm leading-relaxed mb-6 group-hover:text-gray-300 transition-colors">
                            <?= $s['description'] ?>
                        </p>
                        <div class="mt-auto">
                            <div class="w-full py-3 rounded-xl bg-white/5 border border-white/10 text-gray-400 text-xs font-bold uppercase tracking-widest flex items-center justify-center gap-2 group-hover:bg-gradient-to-br group-hover:<?= $s['gradient'] ?> group-hover:text-white group-hover:border-transparent transition-all duration-300 shadow-lg group-hover:shadow-indigo-500/25">
                                Boshlash
                                <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </div>
                    </a>

                    <?php if (!empty($videoUrl)): ?>
                    <!-- Video qo'llanma tugmasi -->
                    <div class="relative z-20 px-5 pb-5">
                        <button
                            onclick="openServiceVideo(<?= htmlspecialchars(json_encode($videoUrl), ENT_QUOTES) ?>, <?= htmlspecialchars(json_encode($s['name']), ENT_QUOTES) ?>)"
                            class="w-full flex items-center justify-center gap-2.5 py-2.5 rounded-xl border transition-all duration-200 text-xs font-semibold"
                            style="color:#f87171;background:rgba(239,68,68,0.06);border-color:rgba(239,68,68,0.2)"
                            onmouseover="this.style.background='rgba(239,68,68,0.12)';this.style.borderColor='rgba(239,68,68,0.45)'"
                            onmouseout="this.style.background='rgba(239,68,68,0.06)';this.style.borderColor='rgba(239,68,68,0.2)'">
                            <span class="w-5 h-5 rounded-full bg-red-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </span>
                            Video qo'llanma
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endforeach;
            } catch (Exception $e) {
                echo '<p class="text-red-400">Xizmatlarni yuklashda xatolik yuz berdi.</p>';
            }
            ?>
        </div>
    </div>
</div>

<!-- Shared YouTube Modal for service video tutorials -->
<div id="service-yt-modal" class="hidden fixed inset-0 z-[95] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/85 backdrop-blur-md" onclick="closeServiceVideo()"></div>
    <div class="relative w-full max-w-3xl animate-fade-in-up">
        <button onclick="closeServiceVideo()" class="absolute -top-4 -right-4 z-10 w-10 h-10 rounded-full flex items-center justify-center shadow-xl text-white hover:scale-110 transition-transform" style="background:rgba(0,0,0,0.7);border:1px solid rgba(255,255,255,0.2)">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="flex items-center gap-3 mb-4 px-1">
            <span class="w-8 h-8 rounded-full bg-red-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
            </span>
            <h3 id="service-yt-title" class="text-white font-bold text-lg">Video qo'llanma</h3>
        </div>
        <div class="rounded-2xl overflow-hidden shadow-2xl border" style="border-color:var(--border-color);aspect-ratio:16/9">
            <iframe id="service-yt-iframe" src="" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full"></iframe>
        </div>
        <p class="text-center text-xs mt-3" id="service-yt-desc" style="color:var(--text-muted)">Ushbu xizmatdan qanday foydalanish haqida video qo'llanma</p>
    </div>
</div>

<script>
function _ytEmbed(url) {
    let id = '';
    const m1 = url.match(/youtu\.be\/([\w-]+)/);
    const m2 = url.match(/[?&]v=([\w-]+)/);
    const m3 = url.match(/\/embed\/([\w-]+)/);
    if (m1) id = m1[1];
    else if (m2) id = m2[1];
    else if (m3) id = m3[1];
    return id ? `https://www.youtube.com/embed/${id}?autoplay=1&rel=0` : url;
}
function openServiceVideo(url, name) {
    document.getElementById('service-yt-iframe').src = _ytEmbed(url);
    document.getElementById('service-yt-title').textContent = name + " — Video qo'llanma";
    document.getElementById('service-yt-desc').textContent = name + " xizmatidan qanday foydalanish haqida batafsil video";
    document.getElementById('service-yt-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeServiceVideo() {
    document.getElementById('service-yt-modal').classList.add('hidden');
    document.getElementById('service-yt-iframe').src = '';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeServiceVideo(); });
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
