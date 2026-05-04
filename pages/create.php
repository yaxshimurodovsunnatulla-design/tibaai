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

                foreach ($services as $s): ?>
                <!-- <?= $s['name'] ?> -->
                <a href="/<?= $s['slug'] ?>" class="group relative glass-card p-8 border border-white/5 hover:border-white/20 transition-all duration-300 hover:-translate-y-2 flex flex-col h-full">
                    <div class="absolute inset-0 bg-gradient-to-br <?= $s['gradient'] ?> opacity-0 group-hover:opacity-5 transition-opacity duration-300 rounded-3xl"></div>
                    <div class="relative z-10 flex flex-col h-full">
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
                        <p class="text-gray-400 text-sm leading-relaxed mb-8 group-hover:text-gray-300 transition-colors">
                            <?= $s['description'] ?>
                        </p>
                        <div class="mt-auto">
                            <div class="w-full py-3 rounded-xl bg-white/5 border border-white/10 text-gray-400 text-xs font-bold uppercase tracking-widest flex items-center justify-center gap-2 group-hover:bg-gradient-to-br group-hover:<?= $s['gradient'] ?> group-hover:text-white group-hover:border-transparent transition-all duration-300 shadow-lg group-hover:shadow-indigo-500/25">
                                Boshlash
                                <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach;
            } catch (Exception $e) {
                echo '<p class="text-red-400">Xizmatlarni yuklashda xatolik yuz berdi.</p>';
            }
            ?>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
