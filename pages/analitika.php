<?php
require_once __DIR__ . '/../api/config.php';
require_once __DIR__ . '/../lang/i18n.php';
$pageTitle = t('nav.analytics') . ' – Tiba AI';
$pageDescription = 'AI yordamida ichki va tashqi tahlil qiling.';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="py-10 sm:py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-10 sm:mb-14">
            <h1 class="text-3xl sm:text-4xl font-extrabold mb-3" style="color:var(--text-heading);">
                AI <span class="gradient-text"><?= t('nav.analytics') ?></span>
            </h1>
            <p class="text-base max-w-xl" style="color:var(--text-muted);">
                Do'koningizni ichkaridan yoki bozorni tashqaridan — AI yordamida chuqur tahlil qiling.
            </p>
        </div>

        <!-- 2 Options -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <!-- ICHKI ANALIZ -->
            <a href="/sotuvlar-analitikasi" class="anl-option group">
                <div class="anl-option__accent" style="background:linear-gradient(135deg, #10b981, #059669);"></div>
                <div class="anl-option__body">
                    <div class="anl-option__icon" style="background:linear-gradient(135deg, #10b981, #059669);">
                        <i class="fa-solid fa-store text-white text-xl"></i>
                    </div>
                    <h2 class="anl-option__title">Ichki analiz</h2>
                    <p class="anl-option__desc">
                        O'z do'koningizni AI yordamida chuqur tahlil qiling. Qayerda kamchilik bor, qaysi tovarlar yaxshi sotilayapti, qaysilari zarar keltirmoqda — barchasini aniqlang.
                    </p>
                    <ul class="anl-option__features">
                        <li><i class="fa-solid fa-chart-line text-emerald-400"></i> Sotuvlar dinamikasi va trendlar</li>
                        <li><i class="fa-solid fa-coins text-emerald-400"></i> Xarajatlar va sof foyda tahlili</li>
                        <li><i class="fa-solid fa-crown text-emerald-400"></i> TOP mahsulotlar reytingi</li>
                        <li><i class="fa-solid fa-rotate-left text-emerald-400"></i> Qaytarishlar sabablari</li>
                        <li><i class="fa-solid fa-robot text-emerald-400"></i> AI maslahatlar va strategiya</li>
                    </ul>
                    <div class="anl-option__cta">
                        <span>Tahlil qilish</span>
                        <i class="fa-solid fa-arrow-right text-sm group-hover:translate-x-1.5 transition-transform duration-200"></i>
                    </div>
                </div>
            </a>

            <!-- TASHQI ANALIZ -->
            <a href="/zoom-selling-ai" class="anl-option group">
                <div class="anl-option__accent" style="background:linear-gradient(135deg, #6366f1, #8b5cf6);"></div>
                <div class="anl-option__body">
                    <div class="anl-option__icon" style="background:linear-gradient(135deg, #6366f1, #8b5cf6);">
                        <i class="fa-solid fa-magnifying-glass-chart text-white text-xl"></i>
                    </div>
                    <h2 class="anl-option__title">Tashqi analiz</h2>
                    <p class="anl-option__desc">
                        Bozordagi tovarlar, raqobatchilar va trendlarni AI yordamida tahlil qiling. Qaysi tovarlar yaxshi sotilayapti, narxlar qanday — barchasini bilib oling.
                    </p>
                    <ul class="anl-option__features">
                        <li><i class="fa-solid fa-magnifying-glass text-indigo-400"></i> Tovarlar va kategoriya tahlili</li>
                        <li><i class="fa-solid fa-users text-indigo-400"></i> Raqobatchilar narxi va reytingi</li>
                        <li><i class="fa-solid fa-fire text-indigo-400"></i> Eng ko'p sotilayotgan tovarlar</li>
                        <li><i class="fa-solid fa-tags text-indigo-400"></i> Narx oralig'i va strategiyasi</li>
                        <li><i class="fa-solid fa-brain text-indigo-400"></i> AI raqobat strategiyasi</li>
                    </ul>
                    <div class="anl-option__cta">
                        <span>Tahlil qilish</span>
                        <i class="fa-solid fa-arrow-right text-sm group-hover:translate-x-1.5 transition-transform duration-200"></i>
                    </div>
                </div>
            </a>

        </div>
    </div>
</div>

<style>
.anl-option {
    display: flex;
    flex-direction: column;
    border-radius: 1.25rem;
    overflow: hidden;
    text-decoration: none;
    background: var(--glass-bg);
    border: 1px solid var(--border-color);
    transition: border-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
}
.anl-option:hover {
    border-color: var(--border-hover);
    box-shadow: 0 16px 48px rgba(99,102,241,0.1);
    transform: translateY(-4px);
}

.anl-option__accent {
    height: 4px;
    width: 100%;
    opacity: 0.7;
    transition: opacity 0.3s ease;
}
.anl-option:hover .anl-option__accent { opacity: 1; }

.anl-option__body {
    padding: 2rem;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.anl-option__icon {
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    margin-bottom: 1.5rem;
    transition: transform 0.3s ease;
}
.anl-option:hover .anl-option__icon {
    transform: scale(1.08);
}

.anl-option__title {
    font-size: 1.375rem;
    font-weight: 800;
    color: var(--text-heading);
    margin-bottom: 0.625rem;
    line-height: 1.2;
}

.anl-option__desc {
    font-size: 0.875rem;
    line-height: 1.65;
    color: var(--text-muted);
    margin-bottom: 1.5rem;
}

.anl-option__features {
    list-style: none;
    padding: 0;
    margin: 0 0 1.5rem 0;
    display: flex;
    flex-direction: column;
    gap: 0.625rem;
}
.anl-option__features li {
    display: flex;
    align-items: center;
    gap: 0.625rem;
    font-size: 0.8125rem;
    font-weight: 500;
    color: var(--text-secondary);
}
.anl-option__features li i {
    width: 1rem;
    text-align: center;
    font-size: 0.75rem;
    flex-shrink: 0;
}

.anl-option__cta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--text-muted);
    margin-top: auto;
    transition: color 0.2s ease;
}
.anl-option:hover .anl-option__cta {
    color: var(--text-heading);
}

/* Light mode */
html[data-theme="light"] .anl-option {
    background: #ffffff !important;
    border-color: #e5e7eb !important;
}
html[data-theme="light"] .anl-option:hover {
    border-color: #c7d2fe !important;
    box-shadow: 0 16px 48px rgba(99,102,241,0.1) !important;
}
html[data-theme="light"] .anl-option__cta {
    border-top-color: #f3f4f6 !important;
}
</style>

<?php include __DIR__ . '/../components/footer.php'; ?>
