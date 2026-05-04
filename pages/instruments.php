<?php
require_once __DIR__ . '/../api/config.php';
$pageTitle = 'Instrumentlar va Yordamchi Vositalar – Tiba AI';
$pageDescription = 'Tiba AI yordamchi instrumentlari: STUV kalkulyatori, foyda hisoblash, hisobotlar va boshqa biznes vositalari.';

$db = getDB();
$allInstruments = $db->query("SELECT * FROM instruments WHERE status != 'hidden' ORDER BY sort_order ASC")->fetchAll();

$tools = array_filter($allInstruments, fn($i) => $i['category'] === 'tools');
$reports = array_filter($allInstruments, fn($i) => $i['category'] === 'reports');
$banks = array_filter($allInstruments, fn($i) => $i['category'] === 'banks');
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
            <?php foreach ($tools as $t):
                $isComingSoon = $t['status'] === 'coming_soon';
                $link = $isComingSoon ? '#' : ($t['link'] ?: '#');
                $tag = $isComingSoon ? 'div' : 'a';
                $href = $isComingSoon ? '' : " href=\"{$link}\"" . ($t['is_external'] ? ' target="_blank" rel="noopener"' : '');
            ?>
            <<?= $tag ?><?= $href ?> class="<?= $isComingSoon ? '' : 'group' ?> glass-card p-8 border border-white/5 <?= $isComingSoon ? 'opacity-50 cursor-not-allowed select-none' : "hover:border-{$t['color']}-500/30 hover:-translate-y-2" ?> transition-all duration-300 flex flex-col items-center text-center relative overflow-hidden">
                <?php if ($isComingSoon): ?>
                <div class="absolute top-2 right-2 px-2.5 py-1 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-400 text-[9px] font-bold uppercase tracking-wider flex items-center gap-1">
                    <i class="fa-solid fa-clock text-[8px]"></i> Tez kunda
                </div>
                <?php elseif ($t['badge']): ?>
                <div class="absolute top-2 right-2 px-2.5 py-1 rounded-full bg-<?= $t['color'] ?>-500/20 border border-<?= $t['color'] ?>-500/30 text-<?= $t['color'] ?>-400 text-[9px] font-bold uppercase tracking-wider flex items-center gap-1">
                    <i class="fa-solid fa-bolt text-[8px]"></i> <?= htmlspecialchars($t['badge']) ?>
                </div>
                <?php endif; ?>
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-br <?= $isComingSoon ? 'from-gray-600 to-gray-700' : $t['gradient'] ?> flex items-center justify-center text-4xl mb-6 shadow-xl <?= $isComingSoon ? 'shadow-gray-500/10' : "shadow-{$t['color']}-500/20 group-hover:scale-110" ?> transition-transform">
                    <i class="<?= $t['icon'] ?> <?= $isComingSoon ? 'text-gray-400' : 'text-white' ?>"></i>
                </div>
                <h3 class="text-2xl font-bold <?= $isComingSoon ? 'text-gray-400' : 'text-white' ?> mb-3"><?= htmlspecialchars($t['name']) ?></h3>
                <p class="<?= $isComingSoon ? 'text-gray-600' : 'text-gray-400' ?> text-sm leading-relaxed mb-6">
                    <?= htmlspecialchars($t['description']) ?>
                </p>
                <?php if ($isComingSoon): ?>
                <div class="mt-auto inline-flex items-center gap-2 text-gray-600 font-bold">
                    <i class="fa-solid fa-lock text-sm"></i> Tez kunda ishga tushadi
                </div>
                <?php else: ?>
                <div class="mt-auto inline-flex items-center gap-2 text-<?= $t['color'] ?>-400 font-bold group-hover:gap-3 transition-all">
                    Boshlash <i class="fa-solid fa-arrow-right"></i>
                </div>
                <?php endif; ?>
            </<?= $tag ?>>
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
                <?php foreach ($reports as $r):
                    $isComingSoon = $r['status'] === 'coming_soon';
                    $link = $isComingSoon ? '#' : ($r['link'] ?: '#');
                    $tag = $isComingSoon ? 'div' : 'a';
                    $href = $isComingSoon ? '' : " href=\"{$link}\"";
                ?>
                <<?= $tag ?><?= $href ?> class="<?= $isComingSoon ? '' : 'group' ?> glass-card p-8 border border-white/5 <?= $isComingSoon ? 'opacity-50 cursor-not-allowed select-none' : "hover:border-{$r['color']}-500/30 hover:-translate-y-2" ?> transition-all duration-300 flex flex-col items-center text-center relative overflow-hidden">
                    <?php if ($isComingSoon): ?>
                    <div class="absolute top-2 right-2 px-2.5 py-1 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-400 text-[9px] font-bold uppercase tracking-wider flex items-center gap-1">
                        <i class="fa-solid fa-clock text-[8px]"></i> Tez kunda
                    </div>
                    <?php elseif ($r['badge']): ?>
                    <div class="absolute top-2 right-2 px-2.5 py-1 rounded-full bg-red-500/20 border border-red-500/30 text-red-400 text-[9px] font-bold uppercase tracking-wider flex items-center gap-1">
                        <i class="fa-solid fa-fire text-[8px]"></i> <?= htmlspecialchars($r['badge']) ?>
                    </div>
                    <?php endif; ?>
                    <div class="w-20 h-20 rounded-3xl bg-gradient-to-br <?= $isComingSoon ? 'from-gray-600 to-gray-700' : $r['gradient'] ?> flex items-center justify-center text-4xl mb-6 shadow-xl <?= $isComingSoon ? 'shadow-gray-500/10' : "shadow-{$r['color']}-500/20 group-hover:scale-110" ?> transition-transform">
                        <i class="<?= $r['icon'] ?> <?= $isComingSoon ? 'text-gray-400' : 'text-white' ?>"></i>
                    </div>
                    <h3 class="text-2xl font-bold <?= $isComingSoon ? 'text-gray-400' : 'text-white' ?> mb-3"><?= htmlspecialchars($r['name']) ?></h3>
                    <p class="<?= $isComingSoon ? 'text-gray-600' : 'text-gray-400' ?> text-sm leading-relaxed mb-6">
                        <?= htmlspecialchars($r['description']) ?>
                    </p>
                    <?php if ($isComingSoon): ?>
                    <div class="mt-auto inline-flex items-center gap-2 text-gray-600 font-bold">
                        <i class="fa-solid fa-lock text-sm"></i> Tez kunda ishga tushadi
                    </div>
                    <?php else: ?>
                    <div class="mt-auto inline-flex items-center gap-2 text-<?= $r['color'] ?>-400 font-bold group-hover:gap-3 transition-all">
                        Hisobotni ochish <i class="fa-solid fa-arrow-right"></i>
                    </div>
                    <?php endif; ?>
                </<?= $tag ?>>
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
                <?php foreach ($banks as $b):
                    $isComingSoon = $b['status'] === 'coming_soon';
                    $link = $isComingSoon ? '#' : ($b['link'] ?: '#');
                    $tag = $isComingSoon ? 'div' : 'a';
                    $href = $isComingSoon ? '' : " href=\"{$link}\"" . ($b['is_external'] ? ' target="_blank" rel="noopener"' : '');
                ?>
                <<?= $tag ?><?= $href ?> class="<?= $isComingSoon ? '' : 'group' ?> glass-card p-8 border border-white/5 <?= $isComingSoon ? 'opacity-50 cursor-not-allowed select-none' : "hover:border-{$b['color']}-500/30 hover:-translate-y-2" ?> transition-all duration-300 flex flex-col items-center text-center relative overflow-hidden">
                    <?php if ($isComingSoon): ?>
                    <div class="absolute top-2 right-2 px-2.5 py-1 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-400 text-[9px] font-bold uppercase tracking-wider flex items-center gap-1">
                        <i class="fa-solid fa-clock text-[8px]"></i> Tez kunda
                    </div>
                    <?php endif; ?>
                    <div class="w-20 h-20 rounded-3xl bg-gradient-to-br <?= $isComingSoon ? 'from-gray-600 to-gray-700' : $b['gradient'] ?> flex items-center justify-center text-4xl mb-6 shadow-xl <?= $isComingSoon ? 'shadow-gray-500/10' : "shadow-{$b['color']}-500/20 group-hover:scale-110" ?> transition-transform">
                        <i class="<?= $b['icon'] ?> <?= $isComingSoon ? 'text-gray-400' : 'text-white' ?>"></i>
                    </div>
                    <h3 class="text-2xl font-bold <?= $isComingSoon ? 'text-gray-400' : 'text-white' ?> mb-3"><?= htmlspecialchars($b['name']) ?></h3>
                    <p class="<?= $isComingSoon ? 'text-gray-600' : 'text-gray-400' ?> text-sm leading-relaxed mb-6">
                        <?= htmlspecialchars($b['description']) ?>
                    </p>
                    <?php if ($isComingSoon): ?>
                    <div class="mt-auto inline-flex items-center gap-2 text-gray-600 font-bold">
                        <i class="fa-solid fa-lock text-sm"></i> Tez kunda ishga tushadi
                    </div>
                    <?php else: ?>
                    <div class="mt-auto inline-flex items-center gap-2 text-<?= $b['color'] ?>-400 font-bold group-hover:gap-3 transition-all">
                        <?= $b['is_external'] ? 'Saytga o\'tish' : 'Batafsil ko\'rish' ?> <i class="fa-solid fa-<?= $b['is_external'] ? 'arrow-up-right-from-square' : 'arrow-right' ?>"></i>
                    </div>
                    <?php endif; ?>
                </<?= $tag ?>>
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
                    $db = getDB();
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

<?php include __DIR__ . '/../components/footer.php'; ?>
