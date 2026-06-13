<?php
require_once __DIR__ . '/../api/config.php';
require_once __DIR__ . '/../lang/i18n.php';
$pageTitle = t('instruments.title') . ' ' . t('instruments.title_highlight') . ' – Tiba AI';
$pageDescription = t('instruments.subtitle');

$db = getDB();
$allInstruments = $db->query("SELECT * FROM instruments WHERE status != 'hidden' ORDER BY sort_order ASC")->fetchAll();

$tools   = array_values(array_filter($allInstruments, fn($i) => $i['category'] === 'tools'));
$reports = array_values(array_filter($allInstruments, fn($i) => $i['category'] === 'reports'));
$banks   = array_values(array_filter($allInstruments, fn($i) => $i['category'] === 'banks'));
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="py-10 sm:py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-10 sm:mb-14">
            <h1 class="text-3xl sm:text-4xl font-extrabold mb-3" style="color:var(--text-heading);">
                <?= t('instruments.title') ?> <span class="gradient-text"><?= t('instruments.title_highlight') ?></span>
            </h1>
            <p class="text-base max-w-xl" style="color:var(--text-muted);">
                <?= t('instruments.subtitle') ?>
            </p>
        </div>

        <!-- ===== TOOLS ===== -->
        <?php if (!empty($tools)): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 mb-14">
            <?php foreach ($tools as $item):
                $isComingSoon = $item['status'] === 'coming_soon';
                $hasVideo = !empty($item['video_url']);
                $link = $isComingSoon ? '#' : ($item['link'] ?: '#');
                $isExt = !empty($item['is_external']);
                $extAttr = ($isExt && !$isComingSoon) ? ' target="_blank" rel="noopener"' : '';
                $grad = $isComingSoon ? 'from-gray-500 to-gray-600' : $item['gradient'];
                $itemName = dbVal($item, 'name');
                $itemDesc = dbVal($item, 'description');
                $itemBadge = dbVal($item, 'badge');
            ?>
            <div class="inst-card-wrap <?= $isComingSoon ? 'inst-card-wrap--disabled' : '' ?>">
                <a href="<?= $link ?>"<?= $extAttr ?> class="inst-card group <?= $isComingSoon ? 'pointer-events-none' : '' ?>">
                    <div class="inst-card__top">
                        <div class="inst-card__icon bg-gradient-to-br <?= $grad ?>">
                            <i class="<?= $item['icon'] ?> text-white"></i>
                        </div>
                        <div class="inst-card__info">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="inst-card__name"><?= $itemName ?></h3>
                                <?php if ($isComingSoon): ?>
                                <span class="inst-card__badge inst-card__badge--soon">
                                    <i class="fa-solid fa-clock text-[7px]"></i>
                                    <?= t('instruments.coming_soon') ?>
                                </span>
                                <?php elseif ($itemBadge): ?>
                                <span class="inst-card__badge"><?= $itemBadge ?></span>
                                <?php endif; ?>
                            </div>
                            <p class="inst-card__desc"><?= $itemDesc ?></p>
                        </div>
                    </div>
                    <div class="inst-card__go">
                        <?php if ($isComingSoon): ?>
                        <span class="inst-card__go-locked">
                            <i class="fa-solid fa-lock text-[10px]"></i>
                            <?= t('instruments.coming_soon_desc') ?>
                        </span>
                        <?php else: ?>
                        <span><?= $isExt ? t('instruments.banks_ext_btn') : t('nav.start') ?></span>
                        <i class="fa-solid fa-arrow-right text-[11px] group-hover:translate-x-1 transition-transform duration-200"></i>
                        <?php endif; ?>
                    </div>
                </a>
                <?php if ($hasVideo && !$isComingSoon): ?>
                <button
                    onclick="openInstrumentVideo(<?= htmlspecialchars(json_encode($item['video_url']), ENT_QUOTES) ?>, <?= htmlspecialchars(json_encode($itemName), ENT_QUOTES) ?>)"
                    class="inst-card__video">
                    <span class="inst-card__play">
                        <svg class="w-2.5 h-2.5 text-white ml-px" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </span>
                    <?= t('create.video_guide') ?>
                </button>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- ===== REPORTS ===== -->
        <?php if (!empty($reports)): ?>
        <div class="inst-section">
            <div class="inst-section__head">
                <div class="inst-section__icon" style="background:rgba(245,158,11,0.1);color:#f59e0b;">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <div>
                    <h2 class="inst-section__title"><?= t('instruments.reports') ?></h2>
                    <p class="inst-section__desc"><?= t('instruments.reports_desc') ?></p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                <?php foreach ($reports as $item):
                    $isComingSoon = $item['status'] === 'coming_soon';
                    $hasVideo = !empty($item['video_url']);
                    $link = $isComingSoon ? '#' : ($item['link'] ?: '#');
                    $grad = $isComingSoon ? 'from-gray-500 to-gray-600' : $item['gradient'];
                    $itemName = dbVal($item, 'name');
                    $itemDesc = dbVal($item, 'description');
                    $itemBadge = dbVal($item, 'badge');
                ?>
                <div class="inst-card-wrap <?= $isComingSoon ? 'inst-card-wrap--disabled' : '' ?>">
                    <a href="<?= $link ?>" class="inst-card group <?= $isComingSoon ? 'pointer-events-none' : '' ?>">
                        <div class="inst-card__top">
                            <div class="inst-card__icon bg-gradient-to-br <?= $grad ?>">
                                <i class="<?= $item['icon'] ?> text-white"></i>
                            </div>
                            <div class="inst-card__info">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="inst-card__name"><?= $itemName ?></h3>
                                    <?php if ($isComingSoon): ?>
                                    <span class="inst-card__badge inst-card__badge--soon"><i class="fa-solid fa-clock text-[7px]"></i> <?= t('instruments.coming_soon') ?></span>
                                    <?php elseif ($itemBadge): ?>
                                    <span class="inst-card__badge"><?= $itemBadge ?></span>
                                    <?php endif; ?>
                                </div>
                                <p class="inst-card__desc"><?= $itemDesc ?></p>
                            </div>
                        </div>
                        <div class="inst-card__go">
                            <?php if ($isComingSoon): ?>
                            <span class="inst-card__go-locked"><i class="fa-solid fa-lock text-[10px]"></i> <?= t('instruments.coming_soon_desc') ?></span>
                            <?php else: ?>
                            <span><?= t('instruments.reports_btn') ?></span>
                            <i class="fa-solid fa-arrow-right text-[11px] group-hover:translate-x-1 transition-transform duration-200"></i>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- ===== BANKS ===== -->
        <?php if (!empty($banks)): ?>
        <div class="inst-section">
            <div class="inst-section__head">
                <div class="inst-section__icon" style="background:rgba(34,197,94,0.1);color:#22c55e;">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
                <div>
                    <h2 class="inst-section__title"><?= t('instruments.banks') ?></h2>
                    <p class="inst-section__desc"><?= t('instruments.banks_desc') ?></p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                <?php foreach ($banks as $item):
                    $isComingSoon = $item['status'] === 'coming_soon';
                    $link = $isComingSoon ? '#' : ($item['link'] ?: '#');
                    $isExt = !empty($item['is_external']);
                    $extAttr = ($isExt && !$isComingSoon) ? ' target="_blank" rel="noopener"' : '';
                    $grad = $isComingSoon ? 'from-gray-500 to-gray-600' : $item['gradient'];
                    $itemName = dbVal($item, 'name');
                    $itemDesc = dbVal($item, 'description');
                ?>
                <div class="inst-card-wrap <?= $isComingSoon ? 'inst-card-wrap--disabled' : '' ?>">
                    <a href="<?= $link ?>"<?= $extAttr ?> class="inst-card group <?= $isComingSoon ? 'pointer-events-none' : '' ?>">
                        <div class="inst-card__top">
                            <div class="inst-card__icon bg-gradient-to-br <?= $grad ?>">
                                <i class="<?= $item['icon'] ?> text-white"></i>
                            </div>
                            <div class="inst-card__info">
                                <h3 class="inst-card__name"><?= $itemName ?></h3>
                                <p class="inst-card__desc"><?= $itemDesc ?></p>
                            </div>
                        </div>
                        <div class="inst-card__go">
                            <?php if ($isComingSoon): ?>
                            <span class="inst-card__go-locked"><i class="fa-solid fa-lock text-[10px]"></i> <?= t('instruments.coming_soon_desc') ?></span>
                            <?php else: ?>
                            <span><?= $isExt ? t('instruments.banks_ext_btn') : t('instruments.banks_int_btn') ?></span>
                            <i class="fa-solid <?= $isExt ? 'fa-external-link' : 'fa-arrow-right' ?> text-[11px] group-hover:translate-x-1 transition-transform duration-200"></i>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- ===== AI SERVICES QUICK LINKS ===== -->
        <div class="inst-section">
            <div class="inst-section__head">
                <div class="inst-section__icon" style="background:rgba(99,102,241,0.1);color:#6366f1;">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <div>
                    <h2 class="inst-section__title"><?= t('instruments.ai_tools') ?></h2>
                </div>
            </div>
            <div class="inst-ai-grid">
                <?php
                try {
                    $stmt = $db->query("SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order ASC");
                    $services = $stmt->fetchAll();
                    foreach ($services as $s):
                ?>
                <a href="/<?= $s['slug'] ?>" class="inst-ai-link group">
                    <div class="inst-ai-link__icon bg-gradient-to-br <?= $s['gradient'] ?>">
                        <i class="<?= $s['icon'] ?> text-white"></i>
                    </div>
                    <span class="inst-ai-link__name"><?= dbVal($s, 'name') ?></span>
                    <i class="fa-solid fa-chevron-right text-[9px] ml-auto opacity-0 group-hover:opacity-60 transition-opacity" style="color:var(--text-muted);"></i>
                </a>
                <?php endforeach;
                } catch (Exception $e) {} ?>
            </div>
        </div>
    </div>
</div>

<style>
/* ===== Section divider ===== */
.inst-section {
    padding-top: 2rem;
    margin-top: 2rem;
    border-top: 1px solid var(--border-color);
    margin-bottom: 2rem;
}
.inst-section:last-child { margin-bottom: 0; }
.inst-section__head {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.25rem;
}
.inst-section__icon {
    width: 2.25rem;
    height: 2.25rem;
    min-width: 2.25rem;
    border-radius: 0.625rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
}
.inst-section__title {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text-heading);
    line-height: 1.3;
}
.inst-section__desc {
    font-size: 0.8125rem;
    color: var(--text-muted);
    margin-top: 0.125rem;
}

/* ===== Card (same style as /create) ===== */
.inst-card-wrap {
    display: flex;
    flex-direction: column;
    border-radius: 1rem;
    overflow: hidden;
    background: var(--glass-bg);
    border: 1px solid var(--border-color);
    transition: border-color 0.25s ease, box-shadow 0.25s ease;
}
.inst-card-wrap:hover {
    border-color: var(--border-hover);
    box-shadow: 0 8px 32px rgba(99,102,241,0.08);
}
.inst-card-wrap--disabled {
    opacity: 0.55;
}
.inst-card-wrap--disabled:hover {
    border-color: var(--border-color);
    box-shadow: none;
}

.inst-card {
    display: flex;
    flex-direction: column;
    flex: 1;
    padding: 1.25rem;
    text-decoration: none;
    gap: 1rem;
}
@media (min-width: 640px) {
    .inst-card { padding: 1.5rem; }
}

.inst-card__top {
    display: flex;
    gap: 0.875rem;
    align-items: flex-start;
}

.inst-card__icon {
    width: 2.75rem;
    height: 2.75rem;
    min-width: 2.75rem;
    border-radius: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    transition: transform 0.3s ease;
}
.inst-card:hover .inst-card__icon { transform: scale(1.08); }

.inst-card__info { flex: 1; min-width: 0; }

.inst-card__name {
    font-size: 0.9375rem;
    font-weight: 700;
    color: var(--text-heading);
    line-height: 1.3;
}

.inst-card__desc {
    font-size: 0.8125rem;
    line-height: 1.55;
    color: var(--text-muted);
    margin-top: 0.25rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.inst-card__badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.125rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.5625rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    background: rgba(99,102,241,0.1);
    color: #818cf8;
    border: 1px solid rgba(99,102,241,0.15);
    white-space: nowrap;
    flex-shrink: 0;
}
.inst-card__badge--soon {
    background: rgba(245,158,11,0.1);
    color: #f59e0b;
    border-color: rgba(245,158,11,0.2);
}

.inst-card__go {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 0.875rem;
    border-top: 1px solid var(--border-color);
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-muted);
    margin-top: auto;
    transition: color 0.2s ease;
}
.inst-card:hover .inst-card__go { color: var(--text-heading); }
.inst-card__go-locked {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    color: var(--text-muted);
}

/* ===== Video button ===== */
.inst-card__video {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    padding: 0.5rem;
    font-size: 0.6875rem;
    font-weight: 600;
    color: #f87171;
    background: rgba(239,68,68,0.04);
    border: none;
    border-top: 1px solid rgba(239,68,68,0.06);
    cursor: pointer;
    transition: background 0.2s ease;
}
.inst-card__video:hover { background: rgba(239,68,68,0.08); }
.inst-card__play {
    width: 1.125rem; height: 1.125rem;
    border-radius: 50%; background: #dc2626;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}

/* ===== AI quick-links grid ===== */
.inst-ai-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5rem;
}
@media (min-width: 640px) {
    .inst-ai-grid { grid-template-columns: repeat(3, 1fr); gap: 0.625rem; }
}
@media (min-width: 1024px) {
    .inst-ai-grid { grid-template-columns: repeat(4, 1fr); }
}
.inst-ai-link {
    display: flex;
    align-items: center;
    gap: 0.625rem;
    padding: 0.625rem 0.75rem;
    border-radius: 0.75rem;
    text-decoration: none;
    background: var(--glass-bg);
    border: 1px solid var(--border-color);
    transition: border-color 0.2s ease, background 0.2s ease;
}
.inst-ai-link:hover {
    border-color: var(--border-hover);
    background: var(--glass-bg-hover);
}
.inst-ai-link__icon {
    width: 2rem; height: 2rem; min-width: 2rem;
    border-radius: 0.5rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.75rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}
.inst-ai-link:hover .inst-ai-link__icon { transform: scale(1.08); }
.inst-ai-link__name {
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--text-secondary);
    transition: color 0.2s ease;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.inst-ai-link:hover .inst-ai-link__name { color: var(--text-heading); }

/* ===== Light mode ===== */
html[data-theme="light"] .inst-card-wrap {
    background: #ffffff !important;
    border-color: #e5e7eb !important;
}
html[data-theme="light"] .inst-card-wrap:hover {
    border-color: #c7d2fe !important;
    box-shadow: 0 8px 32px rgba(99,102,241,0.1) !important;
}
html[data-theme="light"] .inst-card-wrap--disabled:hover {
    border-color: #e5e7eb !important;
    box-shadow: none !important;
}
html[data-theme="light"] .inst-card__badge {
    background: #eef2ff !important;
    color: #4f46e5 !important;
    border-color: #c7d2fe !important;
}
html[data-theme="light"] .inst-card__badge--soon {
    background: #fef3c7 !important;
    color: #d97706 !important;
    border-color: #fbbf24 !important;
}
html[data-theme="light"] .inst-card__go { border-top-color: #f3f4f6 !important; }
html[data-theme="light"] .inst-card__video {
    background: rgba(239,68,68,0.03) !important;
    border-top-color: rgba(239,68,68,0.05) !important;
}
html[data-theme="light"] .inst-section { border-top-color: #e5e7eb !important; }
html[data-theme="light"] .inst-ai-link {
    background: #ffffff !important;
    border-color: #e5e7eb !important;
}
html[data-theme="light"] .inst-ai-link:hover {
    border-color: #c7d2fe !important;
    background: #f8fafc !important;
}
</style>

<!-- YouTube Modal -->
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
            <h3 id="instrument-yt-title" class="text-white font-bold text-lg"><?= t('create.video_guide') ?></h3>
        </div>
        <div class="rounded-2xl overflow-hidden shadow-2xl border" style="border-color:var(--border-color);aspect-ratio:16/9">
            <iframe id="instrument-yt-iframe" src="" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full"></iframe>
        </div>
        <p class="text-center text-xs mt-3" id="instrument-yt-desc" style="color:var(--text-muted)"><?= t('js.video_desc') ?></p>
    </div>
</div>

<script>
function _ytEmbedInst(url) {
    let id = '';
    const m1 = url.match(/youtu\.be\/([\w-]+)/);
    const m2 = url.match(/[?&]v=([\w-]+)/);
    const m3 = url.match(/\/embed\/([\w-]+)/);
    if (m1) id = m1[1]; else if (m2) id = m2[1]; else if (m3) id = m3[1];
    return id ? `https://www.youtube.com/embed/${id}?autoplay=1&rel=0` : url;
}
function openInstrumentVideo(url, name) {
    document.getElementById('instrument-yt-iframe').src = _ytEmbedInst(url);
    document.getElementById('instrument-yt-title').textContent = name + " — " + _t('video_guide');
    document.getElementById('instrument-yt-desc').textContent = _t('video_desc');
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
