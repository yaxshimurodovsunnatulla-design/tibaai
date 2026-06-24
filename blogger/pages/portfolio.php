<?php
$pageTitle = 'Portfolio – Bloger Panel';
require_once __DIR__ . '/../components/blogger-header.php';
?>

<div class="p-4 md:p-8 flex-1 flex flex-col h-full">
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white">Portfoliom</h1>
            <p class="text-gray-400 text-sm mt-1">Sizning ishlaringiz va natijalaringizni sotuvchilarga namoyish eting.</p>
        </div>
        <button class="btn-primary py-2 px-4 rounded-xl text-sm whitespace-nowrap"><i class="fa-solid fa-plus mr-2"></i> Ish Qo'shish</button>
    </div>

    <div class="glass-card p-10 flex-1 flex flex-col items-center justify-center text-center">
        <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center text-4xl text-gray-600 mb-4 shadow-inner">
            <i class="fa-solid fa-images"></i>
        </div>
        <h3 class="text-lg font-bold text-white mb-2">Hozircha portfolio bo'sh</h3>
        <p class="text-gray-400 text-sm max-w-sm mb-6">Sotuvchilarning ishonchini qozonish uchun muvaffaqiyatli reklamalaringizni va ularning statistikalarini shu yerga joylang.</p>
        <button class="px-6 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-white text-sm font-bold transition-all border border-white/10">Birinchi ishni qo'shish</button>
    </div>
</div>

<?php require_once __DIR__ . '/../components/blogger-footer.php'; ?>
