<?php
require_once __DIR__ . '/../api/config.php';
$pageTitle = 'Instrumentlar va Yordamchi Vositalar – Tiba AI';
$pageDescription = 'Tiba AI yordamchi instrumentlari: STUV kalkulyatori, foyda hisoblash, hisobotlar va boshqa biznes vositalari.';

$db = getDB();
$allInstruments = $db->query("SELECT * FROM instruments WHERE status != 'hidden' ORDER BY sort_order ASC")->fetchAll();

$tools = array_filter($allInstruments, fn($i) => $i['category'] === 'tools');
$reports = array_filter($allInstruments, fn($i) => $i['category'] === 'reports');
$banks = array_filter($allInstruments, fn($i) => $i['category'] === 'banks');

// Helper: instrument card HTML (faol holat uchun wrapper div bilan)
function renderInstrumentCard(array $item, string $cta = 'Boshlash'): string {
    $isComingSoon = $item['status'] === 'coming_soon';
    $hasVideo     = !empty($item['video_url']);
    $link         = $isComingSoon ? '#' : ($item['link'] ?: '#');
    $isExt        = !empty($item['is_external']);
    $extAttr      = ($isExt && !$isComingSoon) ? ' target="_blank" rel="noopener"' : '';
    $col          = $item['color'] ?? 'indigo';
    $grad         = $isComingSoon ? 'from-gray-600 to-gray-700' : $item['gradient'];

    $badgeHtml = '';
    if ($isComingSoon) {
        $badgeHtml = '<div class="absolute top-2 right-2 px-2.5 py-1 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-400 text-[9px] font-bold uppercase tracking-wider flex items-center gap-1"><i class="fa-solid fa-clock text-[8px]"></i> Tez kunda</div>';
    } elseif (!empty($item['badge'])) {
        $badgeHtml = '<div class="absolute top-2 right-2 px-2.5 py-1 rounded-full bg-' . $col . '-500/20 border border-' . $col . '-500/30 text-' . $col . '-400 text-[9px] font-bold uppercase tracking-wider flex items-center gap-1"><i class="fa-solid fa-bolt text-[8px]"></i> ' . htmlspecialchars($item['badge']) . '</div>';
    }

    $iconCls = $isComingSoon ? 'text-gray-400' : 'text-white';
    $nameCls = $isComingSoon ? 'text-gray-400' : 'text-white';
    $descCls = $isComingSoon ? 'text-gray-600' : 'text-gray-400';
    $hoverBorder = $isComingSoon ? '' : "hover:border-{$col}-500/30 hover:-translate-y-2";
    $groupCls = $isComingSoon ? '' : 'group ';
    $disabledCls = $isComingSoon ? ' opacity-50 cursor-not-allowed select-none' : '';

    $ctaHtml = $isComingSoon
        ? '<div class="mt-auto inline-flex items-center gap-2 text-gray-600 font-bold"><i class="fa-solid fa-lock text-sm"></i> Tez kunda ishga tushadi</div>'
        : '<div class="mt-auto inline-flex items-center gap-2 text-' . $col . '-400 font-bold group-hover:gap-3 transition-all">' . htmlspecialchars($cta) . ' <i class="fa-solid fa-arrow-right"></i></div>';

    $videoBtn = '';
    if ($hasVideo && !$isComingSoon) {
        $urlJson = htmlspecialchars(json_encode($item['video_url']), ENT_QUOTES);
        $nameJson = htmlspecialchars(json_encode($item['name']), ENT_QUOTES);
        $videoBtn = <<<HTML
    <div class="px-4 pb-4 pt-0">
        <button onclick="openInstrumentVideo({$urlJson}, {$nameJson}); event.preventDefault(); event.stopPropagation();"
            class="w-full flex items-center justify-center gap-2.5 py-2 rounded-xl border transition-all duration-200 text-xs font-semibold"
            style="color:#f87171;background:rgba(239,68,68,0.06);border-color:rgba(239,68,68,0.2)"
            onmouseover="this.style.background='rgba(239,68,68,0.12)';this.style.borderColor='rgba(239,68,68,0.45)'"
            onmouseout="this.style.background='rgba(239,68,68,0.06)';this.style.borderColor='rgba(239,68,68,0.2)'">
            <span class="w-5 h-5 rounded-full bg-red-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-2.5 h-2.5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
            </span>
            Video qo'llanma
        </button>
    </div>
HTML;
    }

    if ($isComingSoon) {
        // coming_soon: div wrapper, no link, no video
        return <<<HTML
<div class="{$groupCls}glass-card border border-white/5 {$hoverBorder}{$disabledCls} transition-all duration-300 flex flex-col items-center text-center relative overflow-hidden rounded-3xl">
    {$badgeHtml}
    <div class="p-8 flex flex-col items-center text-center flex-1 w-full">
        <div class="w-20 h-20 rounded-3xl bg-gradient-to-br {$grad} flex items-center justify-center text-4xl mb-6 shadow-xl shadow-gray-500/10 transition-transform">
            <i class="{$item['icon']} {$iconCls}"></i>
        </div>
        <h3 class="text-2xl font-bold {$nameCls} mb-3">{$item['name']}</h3>
        <p class="{$descCls} text-sm leading-relaxed mb-6">{$item['description']}</p>
        {$ctaHtml}
    </div>
</div>
HTML;
    }

    // Faol: a link + optional video button
    return <<<HTML
<div class="{$groupCls}glass-card border border-white/5 {$hoverBorder} transition-all duration-300 flex flex-col relative overflow-hidden rounded-3xl">
    <a href="{$link}"{$extAttr} class="flex flex-col items-center text-center p-8 flex-1 relative">
        {$badgeHtml}
        <div class="w-20 h-20 rounded-3xl bg-gradient-to-br {$grad} flex items-center justify-center text-4xl mb-6 shadow-xl shadow-{$col}-500/20 group-hover:scale-110 transition-transform">
            <i class="{$item['icon']} {$iconCls}"></i>
        </div>
        <h3 class="text-2xl font-bold {$nameCls} mb-3">{$item['name']}</h3>
        <p class="{$descCls} text-sm leading-relaxed mb-6">{$item['description']}</p>
        {$ctaHtml}
    </a>
    {$videoBtn}
</div>
HTML;
}
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="py-12 sm:py-20 relative overflow-hidden">
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-600/10 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-16">
            <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-4">
                Biznes uchun <span class="gradient-text">Instrumentlar</span>
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg">
                Sotuvlaringizni tahlil qilish va samaradorlikni oshirish uchun foydali vositalar.
            </p>
        </div>

        <!-- Main Tools Grid -->
        <?php if (!empty($tools)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
            <?php foreach ($tools as $t): ?>
            <?= renderInstrumentCard($t, 'Boshlash') ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Hisobotlar Section -->
        <?php if (!empty($reports)): ?>
        <div class="border-t border-white/10 pt-20 mb-20">
            <h2 class="text-2xl font-bold text-white mb-3 flex items-center gap-3">
                <i class="fa-solid fa-chart-pie text-amber-400"></i> Hisobotlar
            </h2>
            <p class="text-gray-400 mb-10 max-w-2xl">
                Do'koningiz holatini chuqur tahlil qilish va yo'qotishlarning oldini olish uchun hisobotlar.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($reports as $r): ?>
                <?= renderInstrumentCard($r, 'Hisobotni ochish') ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Bank Xizmatlari -->
        <?php if (!empty($banks)): ?>
        <div class="border-t border-white/10 pt-20 mb-20">
            <h2 class="text-2xl font-bold text-white mb-3 flex items-center gap-3">
                <i class="fa-solid fa-building-columns text-green-400"></i> Bank Xizmatlari
            </h2>
            <p class="text-gray-400 mb-10 max-w-2xl">
                Biznes hisobingizni ochish va boshqarish uchun qulay yo'riqnomalar.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($banks as $b): ?>
                <?= renderInstrumentCard($b, $b['is_external'] ? "Saytga o'tish" : "Batafsil ko'rish") ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- AI Tools Section -->
        <div class="border-t border-white/10 pt-20">
            <h2 class="text-2xl font-bold text-white mb-10 flex items-center gap-3">
                <i class="fa-solid fa-wand-magic-sparkles text-indigo-400"></i> AI Instrumentlar
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <?php
                try {
                    $stmt = $db->query("SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order ASC");
                    $services = $stmt->fetchAll();
                    foreach ($services as $s): ?>
                        <a href="/<?= $s['slug'] ?>" class="glass-card p-4 border border-white/5 hover:border-white/10 transition-all flex items-center gap-3 group">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br <?= $s['gradient'] ?> flex items-center justify-center text-white text-sm shadow-lg group-hover:scale-110 transition-transform">
                                <i class="<?= $s['icon'] ?>"></i>
                            </div>
                            <span class="text-sm font-semibold text-gray-300 group-hover:text-white"><?= $s['name'] ?></span>
                        </a>
                    <?php endforeach;
                } catch (Exception $e) {}
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Shared YouTube Modal for instrument video tutorials -->
<div id="instrument-yt-modal" class="hidden fixed inset-0 z-[95] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/85 backdrop-blur-md" onclick="closeInstrumentVideo()"></div>
    <div class="relative w-full max-w-3xl animate-fade-in-up">
        <button onclick="closeInstrumentVideo()" class="absolute -top-4 -right-4 z-10 w-10 h-10 rounded-full flex items-center justify-center shadow-xl text-white hover:scale-110 transition-transform" style="background:rgba(0,0,0,0.7);border:1px solid rgba(255,255,255,0.2)">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="flex items-center gap-3 mb-4 px-1">
            <span class="w-8 h-8 rounded-full bg-red-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
            </span>
            <h3 id="instrument-yt-title" class="text-white font-bold text-lg">Video qo'llanma</h3>
        </div>
        <div class="rounded-2xl overflow-hidden shadow-2xl border" style="border-color:var(--border-color);aspect-ratio:16/9">
            <iframe id="instrument-yt-iframe" src="" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full"></iframe>
        </div>
        <p class="text-center text-xs mt-3" id="instrument-yt-desc" style="color:var(--text-muted)">Ushbu instrumentdan qanday foydalanish haqida video qo'llanma</p>
    </div>
</div>

<script>
function _ytEmbedInst(url) {
    let id = '';
    const m1 = url.match(/youtu\.be\/([\w-]+)/);
    const m2 = url.match(/[?&]v=([\w-]+)/);
    const m3 = url.match(/\/embed\/([\w-]+)/);
    if (m1) id = m1[1];
    else if (m2) id = m2[1];
    else if (m3) id = m3[1];
    return id ? `https://www.youtube.com/embed/${id}?autoplay=1&rel=0` : url;
}
function openInstrumentVideo(url, name) {
    document.getElementById('instrument-yt-iframe').src = _ytEmbedInst(url);
    document.getElementById('instrument-yt-title').textContent = name + " — Video qo'llanma";
    document.getElementById('instrument-yt-desc').textContent = name + " instrumentidan qanday foydalanish haqida batafsil video";
    document.getElementById('instrument-yt-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeInstrumentVideo() {
    document.getElementById('instrument-yt-modal').classList.add('hidden');
    document.getElementById('instrument-yt-iframe').src = '';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeInstrumentVideo(); });
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
