<?php
require_once __DIR__ . '/../api/config.php';
$pageTitle = 'Instrumentlar va Yordamchi Vositalar – Tiba AI';
$pageDescription = 'Tiba AI yordamchi instrumentlari: STUV kalkulyatori, foyda hisoblash, hisobotlar va boshqa biznes vositalari.';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="py-12 sm:py-20 relative overflow-hidden">
    <!-- Background elements -->
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
            <!-- STUV Kalkulyatori -->
            <a href="/stuv-kalkulyatori" class="group glass-card p-8 border border-white/5 hover:border-violet-500/30 transition-all duration-300 hover:-translate-y-2 flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-violet-600 to-fuchsia-600 flex items-center justify-center text-4xl mb-6 shadow-xl shadow-violet-500/20 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-calculator text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-white mb-3">STUV Kalkulyatori</h3>
                <p class="text-gray-400 text-sm leading-relaxed mb-6">
                    Soliq, Tannarx, Usta (foyda) va Vazn asosida mahsulotning yakuniy narxini va foydasini hisoblang. Uzum va boshqa marketplacerlar uchun qulay.
                </p>
                <div class="mt-auto inline-flex items-center gap-2 text-violet-400 font-bold group-hover:gap-3 transition-all">
                    Hisoblashni boshlash <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>

            <!-- QQS Kalkulyatori -->
            <a href="/qqs-kalkulyatori" class="group glass-card p-8 border border-white/5 hover:border-amber-500/30 transition-all duration-300 hover:-translate-y-2 flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-4xl mb-6 shadow-xl shadow-amber-500/20 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-receipt text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-white mb-3">QQS Kalkulyatori</h3>
                <p class="text-gray-400 text-sm leading-relaxed mb-6">
                    Qachon majburiy ravishda QQSga o'tishingizni bilib oling. O'zR Soliq Kodeksi 462-moddasiga binoan limitni kuzatib boring.
                </p>
                <div class="mt-auto inline-flex items-center gap-2 text-amber-400 font-bold group-hover:gap-3 transition-all">
                    Limitni tekshirish <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>

            <!-- Sotuvlar Analitikasi -->
            <a href="/sotuvlar-analitikasi" class="group glass-card p-8 border border-white/5 hover:border-emerald-500/30 transition-all duration-300 hover:-translate-y-2 flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-4xl mb-6 shadow-xl shadow-emerald-500/20 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-chart-line text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-white mb-3">Sotuvlar Analitikasi</h3>
                <p class="text-gray-400 text-sm leading-relaxed mb-6">
                    Haftalik va oylik sotuvlaringizni tahlil qiling. O'sish sur'ati va trendlarni grafiklar orqali kuzating.
                </p>
                <div class="mt-auto inline-flex items-center gap-2 text-emerald-400 font-bold group-hover:gap-3 transition-all">
                    Tahlilni ochish <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>

            <!-- InstaLink AI -->
            <a href="/insta-link" class="group glass-card p-8 border border-white/5 hover:border-pink-500/30 transition-all duration-300 hover:-translate-y-2 flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-purple-600 via-pink-500 to-orange-500 flex items-center justify-center text-4xl mb-6 shadow-xl shadow-pink-500/20 group-hover:scale-110 transition-transform">
                    <i class="fa-brands fa-instagram text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-white mb-3">InstaLink AI</h3>
                <p class="text-gray-400 text-sm leading-relaxed mb-6">
                    Instagram videolaringizga izoh qoldirganlarga avtomatik Direct xabar va linklar yuboring. Chatplace kabi samarali marketing vositasi.
                </p>
                <div class="mt-auto inline-flex items-center gap-2 text-pink-400 font-bold group-hover:gap-3 transition-all">
                    Avtomatlashtirishni sozlash <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>

            <!-- Didox ETTY -->
            <a href="/didox-etty" class="group glass-card p-8 border border-white/5 hover:border-cyan-500/30 transition-all duration-300 hover:-translate-y-2 flex flex-col items-center text-center relative overflow-hidden">
                <div class="absolute top-2 right-2 px-2.5 py-1 rounded-full bg-cyan-500/20 border border-cyan-500/30 text-cyan-400 text-[9px] font-bold uppercase tracking-wider flex items-center gap-1">
                    <i class="fa-solid fa-bolt text-[8px]"></i> Yangi
                </div>
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-4xl mb-6 shadow-xl shadow-cyan-500/20 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-file-invoice text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-white mb-3">Didox ETTY</h3>
                <p class="text-gray-400 text-sm leading-relaxed mb-6">
                    Didox orqali elektron tovar-transport yukxatini (ETTY) avtomatik yaratish. Uzum buyurtmalaringiz uchun bir tugma bilan rasmiylashtiring.
                </p>
                <div class="mt-auto inline-flex items-center gap-2 text-cyan-400 font-bold group-hover:gap-3 transition-all">
                    ETTY yaratish <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>

            <div class="glass-card p-8 border border-white/5 flex flex-col items-center text-center relative overflow-hidden opacity-50 cursor-not-allowed select-none">
                <div class="absolute top-2 right-2 px-2.5 py-1 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-400 text-[9px] font-bold uppercase tracking-wider flex items-center gap-1">
                    <i class="fa-solid fa-clock text-[8px]"></i> Tez kunda
                </div>
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-gray-600 to-gray-700 flex items-center justify-center text-4xl mb-6 shadow-xl shadow-gray-500/10">
                    <i class="fa-solid fa-binoculars text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-400 mb-3">Raqiblar Narxi Monitori</h3>
                <p class="text-gray-600 text-sm leading-relaxed mb-6">
                    Uzum Market'dagi raqobatchilar narxini real vaqtda tekshiring. Bozor o'rtachasini bilib, optimal narx strategiyasini tanlang.
                </p>
                <div class="mt-auto inline-flex items-center gap-2 text-gray-600 font-bold">
                    <i class="fa-solid fa-lock text-sm"></i> Tez kunda ishga tushadi
                </div>
            </div>

            <!-- Zoom Selling AI (Tez kunda) -->
            <div class="glass-card p-8 border border-white/5 flex flex-col items-center text-center relative overflow-hidden opacity-50 cursor-not-allowed select-none">
                <div class="absolute top-2 right-2 px-2.5 py-1 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-400 text-[9px] font-bold uppercase tracking-wider flex items-center gap-1">
                    <i class="fa-solid fa-clock text-[8px]"></i> Tez kunda
                </div>
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-gray-600 to-gray-700 flex items-center justify-center text-4xl mb-6 shadow-xl shadow-gray-500/10">
                    <i class="fa-solid fa-magnifying-glass-chart text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-400 mb-3">Zoom Selling AI</h3>
                <p class="text-gray-600 text-sm leading-relaxed mb-6">
                    Kategoriyalar, bo'limlar va har bir tovar uchun mukammal AI tahlil. Raqobat, narx, talab va trend analizi.
                </p>
                <div class="mt-auto inline-flex items-center gap-2 text-gray-600 font-bold">
                    <i class="fa-solid fa-lock text-sm"></i> Tez kunda ishga tushadi
                </div>
            </div>
        </div>

        <!-- Hisobotlar Section -->
        <div class="border-t border-white/10 pt-20 mb-20">
            <h2 class="text-2xl font-bold text-white mb-3 flex items-center gap-3">
                <i class="fa-solid fa-chart-pie text-amber-400"></i> Hisobotlar
            </h2>
            <p class="text-gray-400 mb-10 max-w-2xl">
                Do'koningiz holatini chuqur tahlil qilish va yo'qotishlarning oldini olish uchun hisobotlar.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Yo'qolgan Tovarlar -->
                <a href="/hisobotlar" class="group glass-card p-8 border border-white/5 hover:border-amber-500/30 transition-all duration-300 hover:-translate-y-2 flex flex-col items-center text-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-amber-600/5 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute top-2 right-2 px-2.5 py-1 rounded-full bg-red-500/20 border border-red-500/30 text-red-400 text-[9px] font-bold uppercase tracking-wider flex items-center gap-1">
                        <i class="fa-solid fa-fire text-[8px]"></i> Muhim
                    </div>
                    <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-amber-500 to-red-600 flex items-center justify-center text-4xl mb-6 shadow-xl shadow-amber-500/20 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-box-open text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Yo'qolgan Tovarlar</h3>
                    <p class="text-gray-400 text-sm leading-relaxed mb-4">
                        Omborda yotib, zarar keltiruvchi tovarlarni aniqlang. AIning maxsus maslahatlarini oling va muzlatilgan kapitalni qayta aylanishga kiriting.
                    </p>
                    <div class="flex flex-wrap justify-center gap-1.5 mb-6">
                        <span class="px-2 py-0.5 rounded-full bg-red-500/15 border border-red-500/25 text-red-400 text-xs font-semibold">⚠️ Xavf darajasi</span>
                        <span class="px-2 py-0.5 rounded-full bg-amber-500/15 border border-amber-500/25 text-amber-400 text-xs font-semibold">🤖 AI maslahat</span>
                        <span class="px-2 py-0.5 rounded-full bg-blue-500/15 border border-blue-500/25 text-blue-400 text-xs font-semibold">📊 Tahlil</span>
                    </div>
                    <div class="mt-auto inline-flex items-center gap-2 text-amber-400 font-bold group-hover:gap-3 transition-all">
                        Hisobotni ochish <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </a>

                <!-- Tez kunda: Boshqa hisobotlar -->
                <div class="glass-card p-8 border border-white/5 flex flex-col items-center text-center relative overflow-hidden opacity-50 cursor-not-allowed select-none">
                    <div class="absolute top-2 right-2 px-2.5 py-1 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-400 text-[9px] font-bold uppercase tracking-wider flex items-center gap-1">
                        <i class="fa-solid fa-clock text-[8px]"></i> Tez kunda
                    </div>
                    <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-gray-600 to-gray-700 flex items-center justify-center text-4xl mb-6 shadow-xl shadow-gray-500/10">
                        <i class="fa-solid fa-file-invoice text-gray-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-400 mb-3">Foyda Hisoboti</h3>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6">
                        Har bir tovar bo'yicha sof foydani hisoblash. Komissiya, yetkazish va saqlash xarajatlarini inobatga olgan holda.
                    </p>
                    <div class="mt-auto inline-flex items-center gap-2 text-gray-600 font-bold">
                        <i class="fa-solid fa-lock text-sm"></i> Tez kunda ishga tushadi
                    </div>
                </div>

                <div class="glass-card p-8 border border-white/5 flex flex-col items-center text-center relative overflow-hidden opacity-50 cursor-not-allowed select-none">
                    <div class="absolute top-2 right-2 px-2.5 py-1 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-400 text-[9px] font-bold uppercase tracking-wider flex items-center gap-1">
                        <i class="fa-solid fa-clock text-[8px]"></i> Tez kunda
                    </div>
                    <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-gray-600 to-gray-700 flex items-center justify-center text-4xl mb-6 shadow-xl shadow-gray-500/10">
                        <i class="fa-solid fa-chart-bar text-gray-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-400 mb-3">Oylik Taqqoslash</h3>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6">
                        Oyma-oy sotuvlar, xarajatlar va foyda ko'rsatkichlarini taqqoslash. O'sish trendlarini grafiklar bilan ko'rish.
                    </p>
                    <div class="mt-auto inline-flex items-center gap-2 text-gray-600 font-bold">
                        <i class="fa-solid fa-lock text-sm"></i> Tez kunda ishga tushadi
                    </div>
                </div>
            </div>
        </div>

        <!-- Kapital Bank Hisob Ochish Section -->
        <div class="border-t border-white/10 pt-20 mb-20">
            <h2 class="text-2xl font-bold text-white mb-3 flex items-center gap-3">
                <i class="fa-solid fa-building-columns text-green-400"></i> Bank Xizmatlari
            </h2>
            <p class="text-gray-400 mb-10 max-w-2xl">
                Biznes hisobingizni ochish va boshqarish uchun qulay yo'riqnomalar.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Kapital Bank karta -->
                <a href="/kapital-bank" class="group glass-card p-8 border border-white/5 hover:border-green-500/30 transition-all duration-300 hover:-translate-y-2 flex flex-col items-center text-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-green-600/5 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-4xl mb-6 shadow-xl shadow-green-500/20 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-landmark text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Kapital Bank</h3>
                    <p class="text-gray-400 text-sm leading-relaxed mb-4">
                        Biznes hisob varag'ingizni onlayn oching. YaTT va MChJ uchun qadamba-qadam yo'riqnoma, narxlar va FAQ.
                    </p>
                    <div class="flex flex-wrap justify-center gap-1.5 mb-6">
                        <span class="px-2 py-0.5 rounded-full bg-green-500/15 border border-green-500/25 text-green-400 text-xs font-semibold">✓ Rasmiy</span>
                        <span class="px-2 py-0.5 rounded-full bg-blue-500/15 border border-blue-500/25 text-blue-400 text-xs font-semibold">✓ Onlayn</span>
                        <span class="px-2 py-0.5 rounded-full bg-amber-500/15 border border-amber-500/25 text-amber-400 text-xs font-semibold">✓ 1-2 kun</span>
                    </div>
                    <div class="mt-auto inline-flex items-center gap-2 text-green-400 font-bold group-hover:gap-3 transition-all">
                        Batafsil ko'rish <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </a>

                <!-- TBC Bank karta -->
                <a href="https://www.tbcbank.uz/uz/business" target="_blank" rel="noopener" class="group glass-card p-8 border border-white/5 hover:border-blue-500/30 transition-all duration-300 hover:-translate-y-2 flex flex-col items-center text-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-blue-600/5 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center text-4xl mb-6 shadow-xl shadow-blue-500/20 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-building-columns text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">TBC Bank</h3>
                    <p class="text-gray-400 text-sm leading-relaxed mb-4">
                        Gruziyaning eng yirik banki O'zbekistonda. Zamonaviy mobil ilova, qulay tariflar va tezkor xizmat ko'rsatish.
                    </p>
                    <div class="flex flex-wrap justify-center gap-1.5 mb-6">
                        <span class="px-2 py-0.5 rounded-full bg-blue-500/15 border border-blue-500/25 text-blue-400 text-xs font-semibold">✓ Mobil bank</span>
                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/15 border border-emerald-500/25 text-emerald-400 text-xs font-semibold">✓ Biznes hisob</span>
                        <span class="px-2 py-0.5 rounded-full bg-violet-500/15 border border-violet-500/25 text-violet-400 text-xs font-semibold">✓ Xalqaro</span>
                    </div>
                    <div class="mt-auto inline-flex items-center gap-2 text-blue-400 font-bold group-hover:gap-3 transition-all">
                        Saytga o'tish <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </div>
                </a>

                <!-- Tenge Bank karta -->
                <a href="https://tengebank.uz/uz/business" target="_blank" rel="noopener" class="group glass-card p-8 border border-white/5 hover:border-violet-500/30 transition-all duration-300 hover:-translate-y-2 flex flex-col items-center text-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-violet-600/5 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-violet-600 to-purple-700 flex items-center justify-center text-4xl mb-6 shadow-xl shadow-violet-500/20 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-coins text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Tenge Bank</h3>
                    <p class="text-gray-400 text-sm leading-relaxed mb-4">
                        Qozog'istonlik bank O'zbekistonda. Innovatsion raqamli banking, qulay kreditlar va biznes uchun maxsus takliflar.
                    </p>
                    <div class="flex flex-wrap justify-center gap-1.5 mb-6">
                        <span class="px-2 py-0.5 rounded-full bg-violet-500/15 border border-violet-500/25 text-violet-400 text-xs font-semibold">✓ Raqamli bank</span>
                        <span class="px-2 py-0.5 rounded-full bg-pink-500/15 border border-pink-500/25 text-pink-400 text-xs font-semibold">✓ Qulay kredit</span>
                        <span class="px-2 py-0.5 rounded-full bg-amber-500/15 border border-amber-500/25 text-amber-400 text-xs font-semibold">✓ Tez ochilinadi</span>
                    </div>
                    <div class="mt-auto inline-flex items-center gap-2 text-violet-400 font-bold group-hover:gap-3 transition-all">
                        Saytga o'tish <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </div>
                </a>
            </div>
        </div>

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
