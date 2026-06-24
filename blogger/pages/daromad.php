<?php
$pageTitle = 'Daromadlarim – Bloger Panel';
require_once __DIR__ . '/../components/blogger-header.php';
?>

<div class="p-4 md:p-8 flex-1 flex flex-col h-full">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-white">Daromadlarim</h1>
        <p class="text-gray-400 text-sm mt-1">Platforma orqali ishlangan mablag'lar tarixi.</p>
    </div>

    <!-- Balance Card -->
    <div class="glass-card p-8 mb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-8 opacity-10">
            <i class="fa-solid fa-coins text-8xl text-orange-500"></i>
        </div>
        <div class="relative z-10">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Hozirgi Balans</div>
            <div class="text-4xl font-black text-white flex items-end gap-2">
                0 <span class="text-lg font-bold text-gray-400 mb-1">so'm</span>
            </div>
            <div class="mt-6 flex gap-3">
                <button class="btn-primary py-2.5 px-6 rounded-xl text-sm whitespace-nowrap shadow-orange-500/20 shadow-lg"><i class="fa-solid fa-money-bill-transfer mr-2"></i> Yechib olish</button>
                <button class="px-6 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-white text-sm font-bold transition-all border border-white/10">Tarix</button>
            </div>
        </div>
    </div>

    <div class="glass-card p-6 flex-1">
        <h2 class="text-lg font-bold text-white mb-6 border-b border-white/10 pb-4">So'nggi O'tkazmalar</h2>
        <div class="flex flex-col items-center justify-center py-10 text-center">
            <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center text-2xl text-gray-600 mb-4 shadow-inner">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <p class="text-gray-400 text-sm">Hozircha hech qanday to'lov tarixi yo'q.</p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../components/blogger-footer.php'; ?>
