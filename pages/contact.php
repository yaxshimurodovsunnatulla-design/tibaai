<?php 
$pageTitle = 'Biz bilan bog\'lanish – Tiba AI Texnik ko\'mak';
$pageDescription = 'Tiba AI xizmati bo\'yicha savollaringiz bo\'lsa bizga murojaat qiling. Biz sizga yordam berishga tayyormiz.';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="py-12 sm:py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl sm:text-4xl font-bold text-white mb-3">
                Biz bilan <span class="gradient-text">bog'laning</span>
            </h1>
            <p class="text-gray-400 max-w-xl mx-auto">
                Savollaringiz bormi? Biz sizga yordam berishdan mamnunmiz.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Telegram -->
            <a href="https://t.me/tibaai" target="_blank" rel="noopener noreferrer" class="glass-card-hover p-8 group block" id="telegram-link">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center mb-6 text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Telegram</h3>
                <p class="text-gray-400 text-sm mb-4">
                    Tez javob olish uchun bizning Telegram kanalimizga yozing.
                </p>
                <div class="flex items-center gap-2 text-indigo-400 text-sm font-medium">
                    @tibaai
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </div>
            </a>

            <!-- Email -->
            <a href="mailto:support@tibaai.uz" class="glass-card-hover p-8 group block" id="email-link">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-400 flex items-center justify-center mb-6 text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Email</h3>
                <p class="text-gray-400 text-sm mb-4">
                    Batafsil so'rovlar uchun elektron pochta orqali yozing.
                </p>
                <div class="flex items-center gap-2 text-indigo-400 text-sm font-medium">
                    support@tibaai.uz
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </div>
            </a>
        </div>

        <!-- Additional Info -->
        <div class="mt-12 glass-card p-8 text-center">
            <h3 class="text-lg font-semibold text-white mb-3">Ish vaqti</h3>
            <p class="text-gray-400 text-sm">
                Dushanba – Juma: 09:00 – 18:00 (Toshkent vaqti)
            </p>
            <p class="text-gray-500 text-xs mt-2">
                Odatda 1 soat ichida javob beramiz.
            </p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
