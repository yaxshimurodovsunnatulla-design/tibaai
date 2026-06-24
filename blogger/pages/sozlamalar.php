<?php
$pageTitle = 'Sozlamalar – Bloger Panel';
require_once __DIR__ . '/../components/blogger-header.php';
?>

<div class="p-4 md:p-8 flex-1 flex flex-col h-full max-w-3xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-white">Sozlamalar</h1>
        <p class="text-gray-400 text-sm mt-1">Platformadagi profilingiz xavfsizligi va qo'shimcha sozlamalari.</p>
    </div>

    <div class="glass-card p-6 space-y-8">
        <!-- Parolni o'zgartirish -->
        <section>
            <h2 class="text-lg font-bold text-white mb-4 border-b border-white/10 pb-2">Xavfsizlik</h2>
            <div class="space-y-4 max-w-sm">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1.5">Yangi Parol</label>
                    <input type="password" id="new-pass" class="input-field" placeholder="••••••••">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1.5">Parolni tasdiqlang</label>
                    <input type="password" id="new-pass-confirm" class="input-field" placeholder="••••••••">
                </div>
                <button class="px-6 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-white text-sm font-bold transition-all border border-white/10">Saqlash</button>
            </div>
        </section>

        <!-- Xabarnomalar -->
        <section>
            <h2 class="text-lg font-bold text-white mb-4 border-b border-white/10 pb-2">Xabarnomalar</h2>
            <div class="space-y-3">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" checked class="w-4 h-4 rounded border-gray-600 bg-gray-700 text-orange-500 focus:ring-orange-500">
                    <span class="text-sm font-medium text-gray-300">Yangi takliflar haqida Telegram orqali xabar olish</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" checked class="w-4 h-4 rounded border-gray-600 bg-gray-700 text-orange-500 focus:ring-orange-500">
                    <span class="text-sm font-medium text-gray-300">Sotuvchi javobi haqida xabar</span>
                </label>
            </div>
        </section>
    </div>
</div>

<?php require_once __DIR__ . '/../components/blogger-footer.php'; ?>
