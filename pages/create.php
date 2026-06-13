<?php
require_once __DIR__ . '/../api/config.php';
require_once __DIR__ . '/../lang/i18n.php';
$pageTitle = t('create.title') . ' ' . t('create.title_highlight') . ' – Tiba AI';
$pageDescription = t('create.subtitle');
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="py-10 sm:py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-10 sm:mb-14">
            <h1 class="text-3xl sm:text-4xl font-extrabold mb-3" style="color:var(--text-heading);">
                <?= t('create.title') ?> <span class="gradient-text"><?= t('create.title_highlight') ?></span>
            </h1>
            <p class="text-base max-w-xl" style="color:var(--text-muted);">
                <?= t('create.subtitle') ?>
            </p>
        </div>

        <!-- Services -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            <?php
            try {
                $db = getDB();
                $stmt = $db->query("SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order ASC");
                $services = $stmt->fetchAll();

                foreach ($services as $s):
                    $videoUrl = !empty($s['video_url']) ? $s['video_url'] : '';
                    $sName = dbVal($s, 'name');
                    $sDesc = dbVal($s, 'description');
                    $sBadge = dbVal($s, 'badge');
            ?>
            <div class="svc-card-wrap">
                <a href="/<?= $s['slug'] ?>" class="svc-card group">
                    <div class="svc-card__top">
                        <div class="svc-card__icon bg-gradient-to-br <?= $s['gradient'] ?>">
                            <i class="<?= $s['icon'] ?> text-white"></i>
                        </div>
                        <div class="svc-card__info">
                            <div class="flex items-center gap-2">
                                <h3 class="svc-card__name"><?= $sName ?></h3>
                                <?php if ($sBadge): ?>
                                <span class="svc-card__badge"><?= $sBadge ?></span>
                                <?php endif; ?>
                            </div>
                            <p class="svc-card__desc"><?= $sDesc ?></p>
                        </div>
                    </div>
                    <div class="svc-card__go">
                        <span><?= t('create.btn_start') ?></span>
                        <i class="fa-solid fa-arrow-right text-[11px] group-hover:translate-x-1 transition-transform duration-200"></i>
                    </div>
                </a>

                <?php if (!empty($videoUrl)): ?>
                <button
                    onclick="openServiceVideo(<?= htmlspecialchars(json_encode($videoUrl), ENT_QUOTES) ?>, <?= htmlspecialchars(json_encode($sName), ENT_QUOTES) ?>)"
                    class="svc-card__video">
                    <span class="svc-card__play">
                        <svg class="w-2.5 h-2.5 text-white ml-px" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </span>
                    <?= t('create.video_guide') ?>
                </button>
                <?php endif; ?>
            </div>
            <?php endforeach;
            } catch (Exception $e) {
                echo '<p class="text-red-400 col-span-full text-center py-8">' . t('create.error') . '</p>';
            }
            ?>
        </div>
    </div>
</div>

<style>
/* ===== Card Wrapper ===== */
.svc-card-wrap {
    display: flex;
    flex-direction: column;
    border-radius: 1rem;
    overflow: hidden;
    background: var(--glass-bg);
    border: 1px solid var(--border-color);
    transition: border-color 0.25s ease, box-shadow 0.25s ease;
}
.svc-card-wrap:hover {
    border-color: var(--border-hover);
    box-shadow: 0 8px 32px rgba(99,102,241,0.08);
}

/* ===== Card Link ===== */
.svc-card {
    display: flex;
    flex-direction: column;
    flex: 1;
    padding: 1.25rem;
    text-decoration: none;
    gap: 1rem;
}
@media (min-width: 640px) {
    .svc-card { padding: 1.5rem; }
}

/* Top section: icon + text */
.svc-card__top {
    display: flex;
    gap: 0.875rem;
    align-items: flex-start;
}

/* Icon */
.svc-card__icon {
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
.svc-card:hover .svc-card__icon {
    transform: scale(1.08);
}

/* Info block */
.svc-card__info {
    flex: 1;
    min-width: 0;
}
.svc-card__name {
    font-size: 0.9375rem;
    font-weight: 700;
    color: var(--text-heading);
    line-height: 1.3;
}
.svc-card__desc {
    font-size: 0.8125rem;
    line-height: 1.55;
    color: var(--text-muted);
    margin-top: 0.25rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Badge */
.svc-card__badge {
    display: inline-block;
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

/* Go / CTA row */
.svc-card__go {
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
.svc-card:hover .svc-card__go {
    color: var(--text-heading);
}

/* ===== Video Button ===== */
.svc-card__video {
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
.svc-card__video:hover {
    background: rgba(239,68,68,0.08);
}
.svc-card__play {
    width: 1.125rem;
    height: 1.125rem;
    border-radius: 50%;
    background: #dc2626;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* ===== Light mode ===== */
html[data-theme="light"] .svc-card-wrap {
    background: #ffffff !important;
    border-color: #e5e7eb !important;
}
html[data-theme="light"] .svc-card-wrap:hover {
    border-color: #c7d2fe !important;
    box-shadow: 0 8px 32px rgba(99,102,241,0.1) !important;
}
html[data-theme="light"] .svc-card__badge {
    background: #eef2ff !important;
    color: #4f46e5 !important;
    border-color: #c7d2fe !important;
}
html[data-theme="light"] .svc-card__go {
    border-top-color: #f3f4f6 !important;
}
html[data-theme="light"] .svc-card__video {
    background: rgba(239,68,68,0.03) !important;
    border-top-color: rgba(239,68,68,0.05) !important;
}
</style>

<!-- YouTube Modal -->
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
            <h3 id="service-yt-title" class="text-white font-bold text-lg"><?= t('create.video_guide') ?></h3>
        </div>
        <div class="rounded-2xl overflow-hidden shadow-2xl border" style="border-color:var(--border-color);aspect-ratio:16/9">
            <iframe id="service-yt-iframe" src="" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full"></iframe>
        </div>
        <p class="text-center text-xs mt-3" id="service-yt-desc" style="color:var(--text-muted)"><?= t('js.video_desc') ?></p>
    </div>
</div>

<script>
function _ytEmbed(url) {
    let id = '';
    const m1 = url.match(/youtu\.be\/([\w-]+)/);
    const m2 = url.match(/[?&]v=([\w-]+)/);
    const m3 = url.match(/\/embed\/([\w-]+)/);
    if (m1) id = m1[1]; else if (m2) id = m2[1]; else if (m3) id = m3[1];
    return id ? `https://www.youtube.com/embed/${id}?autoplay=1&rel=0` : url;
}
function openServiceVideo(url, name) {
    document.getElementById('service-yt-iframe').src = _ytEmbed(url);
    document.getElementById('service-yt-title').textContent = name + " — " + _t('video_guide');
    document.getElementById('service-yt-desc').textContent = _t('video_desc');
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
