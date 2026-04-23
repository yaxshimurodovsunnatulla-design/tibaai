<?php 
$pageTitle = 'Narxlar va Paketlar – Tiba AI';
$pageDescription = 'Tiba AI xizmatlaridan foydalanish uchun hamyonbop narxlar va bonusli paketlar bilan tanishing.';
?>
<?php
require_once __DIR__ . '/../api/config.php';
$db = getDB();
try {
    $stmtPkg = $db->query("SELECT * FROM packages WHERE is_active = 1 ORDER BY sort_order ASC");
    $dbPackages = $stmtPkg->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $dbPackages = [];
}
// Per-tanga narxni hisoblash uchun eng birinchi paketni bazis qilamiz
$basePerCoin = 0;
if (!empty($dbPackages)) {
    $first = $dbPackages[0];
    $basePerCoin = $first['credits'] > 0 ? $first['price'] / $first['credits'] : 0;
}

// To'lov usullari holati
$clickEnabled = getSetting('click_enabled', '1') !== '0';
$paymeEnabled = getSetting('payme_enabled', '1') !== '0';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="py-12 sm:py-20 relative overflow-hidden">
    <!-- Background -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-600/10 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 mb-4">
                <i class="fa-solid fa-coins text-sm text-amber-400"></i>
                <span class="text-xs font-medium text-indigo-300">Tanga tizimi</span>
            </div>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-white mb-4">
                Tanga sotib oling, <span class="gradient-text">xohlagancha ishlating</span>
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg">
                Oylik obuna yo'q. Faqat kerakli miqdorda tanga sotib oling — har bir AI asbob ma'lum tanga sarflaydi. Muddati cheksiz.
            </p>
        </div>

        <!-- Tanga Packages -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-16">
            <?php foreach ($dbPackages as $idx => $pkg):
                $perCoin = $pkg['credits'] > 0 ? $pkg['price'] / $pkg['credits'] : 0;
                $discount = ($basePerCoin > 0 && $idx > 0) ? round((1 - $perCoin / $basePerCoin) * 100) : 0;
                $features = json_decode($pkg['features'] ?? '[]', true) ?: [];
                $hasBadge = !empty($pkg['badge']);
                $badgeGradient = $pkg['badge_gradient'] ?: $pkg['gradient'];
                $borderClass = $hasBadge ? 'border-white/20' : '';
            ?>
            <div class="relative glass-card p-6 flex flex-col transition-all duration-300 hover:-translate-y-1 <?= $borderClass ?>">
                <?php if ($hasBadge): ?>
                <div class="absolute -top-2.5 left-1/2 -translate-x-1/2">
                    <span class="px-3 py-0.5 text-[10px] font-bold text-white bg-gradient-to-r <?= htmlspecialchars($badgeGradient) ?> rounded-full shadow-lg">
                        <?= htmlspecialchars($pkg['badge']) ?>
                    </span>
                </div>
                <?php endif; ?>
                <div class="mb-5">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br <?= htmlspecialchars($pkg['gradient']) ?> mb-3">
                        <i class="fa-solid <?= htmlspecialchars($pkg['icon']) ?> text-xl text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white"><?= htmlspecialchars($pkg['name']) ?></h3>
                </div>
                <div class="mb-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-3xl font-extrabold text-white"><?= number_format($pkg['credits']) ?></span>
                        <span class="text-sm text-gray-400"><i class="fa-solid fa-coins text-amber-400 text-xs"></i> tanga</span>
                    </div>
                </div>
                <div class="mb-5">
                    <div class="flex items-baseline gap-1">
                        <span class="text-2xl font-extrabold text-white"><?= number_format($pkg['price'], 0, '', ',') ?></span>
                        <span class="text-sm text-gray-500">so'm</span>
                    </div>
                    <div class="flex items-center gap-2 mt-1">
                        <p class="text-[11px] text-gray-600">1 tanga = <?= number_format(round($perCoin), 0, '', ',') ?> so'm</p>
                        <?php if ($discount > 0): ?>
                        <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-emerald-500/15 text-emerald-400 font-bold">-<?= $discount ?>%</span>
                        <?php endif; ?>
                    </div>
                </div>
                <ul class="space-y-2 mb-6 flex-1 text-xs">
                    <?php foreach ($features as $feat): ?>
                    <li class="flex items-center gap-2 text-gray-400">
                        <i class="fa-solid fa-circle-check text-green-400 text-[11px]"></i> <?= htmlspecialchars($feat) ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <button onclick="PaymentModal.open('<?= htmlspecialchars($pkg['id']) ?>')" class="<?= ($hasBadge && $idx === 1) ? 'btn-primary' : 'btn-secondary' ?> w-full text-center text-sm py-3 cursor-pointer">Sotib olish</button>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- ========== TO'LOVLAR TARIXI ========== -->
        <div id="history" class="mb-16 scroll-mt-24">
            <div id="payment-history-section" class="hidden">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 mb-4">
                        <i class="fa-solid fa-receipt text-sm text-blue-400"></i>
                        <span class="text-xs font-medium text-blue-300">To'lovlar tarixi</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white mb-2">
                        <i class="fa-solid fa-clock-rotate-left text-blue-400 mr-2"></i>Sizning to'lovlaringiz
                    </h2>
                    <p class="text-gray-400 text-sm">Barcha to'lov amaliyotlaringiz shu yerda ko'rinadi</p>
                </div>

                <div class="glass-card border border-white/10 overflow-hidden">
                    <!-- Table Header -->
                    <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-list text-indigo-400 text-sm"></i>
                            <span class="text-sm font-semibold text-white">So'nggi to'lovlar</span>
                        </div>
                        <button onclick="loadPaymentHistory()" class="text-xs text-gray-500 hover:text-indigo-400 transition-colors flex items-center gap-1">
                            <i class="fa-solid fa-arrows-rotate text-[10px]"></i> Yangilash
                        </button>
                    </div>

                    <!-- Loading -->
                    <div id="history-loading" class="hidden py-12 text-center">
                        <i class="fa-solid fa-circle-notch fa-spin text-indigo-400 text-2xl mb-3"></i>
                        <p class="text-sm text-gray-500">Yuklanmoqda...</p>
                    </div>

                    <!-- Empty State -->
                    <div id="history-empty" class="hidden py-12 text-center">
                        <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-inbox text-gray-600 text-2xl"></i>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Hali to'lov mavjud emas</p>
                        <p class="text-xs text-gray-600">Birinchi tangalaringizni sotib oling!</p>
                    </div>

                    <!-- Payment List -->
                    <div id="history-list" class="divide-y divide-white/5"></div>
                </div>
            </div>
        </div>

        <!-- Tanga Cost Table -->
        <div class="glass-card p-6 sm:p-8 mb-16">
            <h2 class="text-2xl font-bold text-white text-center mb-3"><i class="fa-solid fa-coins text-amber-400"></i> Har bir asbob nechta tanga sarflaydi?</h2>
            <p class="text-sm text-gray-500 text-center mb-8">Har bir generatsiya quyidagi miqdorda tanga sarflaydi</p>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-white/10">
                            <th class="text-left text-gray-400 py-3 px-4 font-medium">AI Asbob</th>
                            <th class="text-center text-gray-400 py-3 px-4 font-medium"><i class="fa-solid fa-coins text-amber-400 text-xs"></i> Tanga</th>
                            <th class="text-center text-gray-400 py-3 px-4 font-medium">Natija</th>
                            <th class="text-right text-gray-400 py-3 px-4 font-medium">50 tangada ≈</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center"><i class="fa-solid fa-wand-magic-sparkles text-indigo-400"></i></span>
                                    <div>
                                        <div class="text-white font-medium">Foto Tahrir</div>
                                        <div class="text-[10px] text-gray-600">Fon almashtirish</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="text-lg font-bold text-indigo-400">5</span>
                                <span class="text-[10px] text-gray-600 ml-1"><i class="fa-solid fa-coins text-amber-500/60"></i></span>
                            </td>
                            <td class="py-3.5 px-4 text-center text-gray-400 text-xs">1 ta rasm</td>
                            <td class="py-3.5 px-4 text-right text-gray-400 text-xs">10 ta rasm</td>
                        </tr>
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-purple-500/10 flex items-center justify-center"><i class="fa-solid fa-palette text-purple-400"></i></span>
                                    <div>
                                        <div class="text-white font-medium">Infografika</div>
                                        <div class="text-[10px] text-gray-600">Marketplace dizayn</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="text-lg font-bold text-indigo-400">5</span>
                                <span class="text-[10px] text-gray-600 ml-1"><i class="fa-solid fa-coins text-amber-500/60"></i></span>
                            </td>
                            <td class="py-3.5 px-4 text-center text-gray-400 text-xs">1 ta rasm</td>
                            <td class="py-3.5 px-4 text-right text-gray-400 text-xs">10 ta rasm</td>
                        </tr>
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-violet-500/10 flex items-center justify-center"><i class="fa-solid fa-boxes-stacked text-violet-400"></i></span>
                                    <div>
                                        <div class="text-white font-medium">Infografika Paketi</div>
                                        <div class="text-[10px] text-gray-600">5 ta slayd</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="text-lg font-bold text-purple-400">20</span>
                                <span class="text-[10px] text-gray-600 ml-1"><i class="fa-solid fa-coins text-amber-500/60"></i></span>
                            </td>
                            <td class="py-3.5 px-4 text-center text-gray-400 text-xs">5 ta slayd</td>
                            <td class="py-3.5 px-4 text-right text-gray-400 text-xs">2 paket</td>
                        </tr>
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-sky-500/10 flex items-center justify-center"><i class="fa-solid fa-rocket text-sky-400"></i></span>
                                    <div>
                                        <div class="text-white font-medium">Noldan Yaratish</div>
                                        <div class="text-[10px] text-gray-600">Text-to-Image</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="text-lg font-bold text-indigo-400">5</span>
                                <span class="text-[10px] text-gray-600 ml-1"><i class="fa-solid fa-coins text-amber-500/60"></i></span>
                            </td>
                            <td class="py-3.5 px-4 text-center text-gray-400 text-xs">1 ta rasm</td>
                            <td class="py-3.5 px-4 text-right text-gray-400 text-xs">10 ta rasm</td>
                        </tr>
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-fuchsia-500/10 flex items-center justify-center"><i class="fa-solid fa-masks-theater text-fuchsia-400"></i></span>
                                    <div>
                                        <div class="text-white font-medium">Uslub Nusxalash</div>
                                        <div class="text-[10px] text-gray-600">Style Transfer</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="text-lg font-bold text-indigo-400">5</span>
                                <span class="text-[10px] text-gray-600 ml-1"><i class="fa-solid fa-coins text-amber-500/60"></i></span>
                            </td>
                            <td class="py-3.5 px-4 text-center text-gray-400 text-xs">1 ta rasm</td>
                            <td class="py-3.5 px-4 text-right text-gray-400 text-xs">10 ta rasm</td>
                        </tr>
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-cyan-500/10 flex items-center justify-center"><i class="fa-solid fa-font text-cyan-400"></i></span>
                                    <div>
                                        <div class="text-white font-medium">Smart Matn</div>
                                        <div class="text-[10px] text-gray-600">Matnli dizayn</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="text-lg font-bold text-indigo-400">5</span>
                                <span class="text-[10px] text-gray-600 ml-1"><i class="fa-solid fa-coins text-amber-500/60"></i></span>
                            </td>
                            <td class="py-3.5 px-4 text-center text-gray-400 text-xs">1 ta rasm</td>
                            <td class="py-3.5 px-4 text-right text-gray-400 text-xs">10 ta rasm</td>
                        </tr>
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-pink-500/10 flex items-center justify-center"><i class="fa-solid fa-shirt text-pink-400"></i></span>
                                    <div>
                                        <div class="text-white font-medium">Fashion AI</div>
                                        <div class="text-[10px] text-gray-600">Virtual try-on</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="text-lg font-bold text-orange-400">8</span>
                                <span class="text-[10px] text-gray-600 ml-1"><i class="fa-solid fa-coins text-amber-500/60"></i></span>
                            </td>
                            <td class="py-3.5 px-4 text-center text-gray-400 text-xs">1 ta rasm</td>
                            <td class="py-3.5 px-4 text-right text-gray-400 text-xs">6 ta rasm</td>
                        </tr>
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-rose-500/10 flex items-center justify-center"><i class="fa-solid fa-camera-retro text-rose-400"></i></span>
                                    <div>
                                        <div class="text-white font-medium">Fotosesiya PRO</div>
                                        <div class="text-[10px] text-gray-600">8 ta professional kadr</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="text-lg font-bold text-rose-400">30</span>
                                <span class="text-[10px] text-gray-600 ml-1"><i class="fa-solid fa-coins text-amber-500/60"></i></span>
                            </td>
                            <td class="py-3.5 px-4 text-center text-gray-400 text-xs">8 ta rasm</td>
                            <td class="py-3.5 px-4 text-right text-gray-400 text-xs">1 sesiya</td>
                        </tr>
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center"><i class="fa-solid fa-brain text-emerald-400"></i></span>
                                    <div>
                                        <div class="text-white font-medium">Kartochka AI</div>
                                        <div class="text-[10px] text-gray-600">Mahsulot ma'lumotlari</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="text-lg font-bold text-emerald-400">3</span>
                                <span class="text-[10px] text-gray-600 ml-1"><i class="fa-solid fa-coins text-amber-500/60"></i></span>
                            </td>
                            <td class="py-3.5 px-4 text-center text-gray-400 text-xs">2 tilda</td>
                            <td class="py-3.5 px-4 text-right text-gray-400 text-xs">16 ta tahlil</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Calculator -->
        <div class="glass-card p-6 sm:p-8 mb-16 border border-indigo-500/10">
            <h2 class="text-2xl font-bold text-white text-center mb-2"><i class="fa-solid fa-calculator text-indigo-400"></i> Tanga kalkulyator</h2>
            <p class="text-sm text-gray-500 text-center mb-8">Qancha tanga kerakligini hisoblang</p>

            <div class="max-w-2xl mx-auto space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="glass-card p-4 border border-white/5">
                        <label class="text-xs text-gray-500 mb-2 block"><i class="fa-solid fa-wand-magic-sparkles text-indigo-400 mr-1"></i> Foto Tahrir (5 <i class="fa-solid fa-coins text-amber-400 text-[10px]"></i>)</label>
                        <input type="number" id="calc-foto" value="0" min="0" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-indigo-500/50 calc-input" />
                    </div>
                    <div class="glass-card p-4 border border-white/5">
                        <label class="text-xs text-gray-500 mb-2 block"><i class="fa-solid fa-palette text-purple-400 mr-1"></i> Infografika (5 <i class="fa-solid fa-coins text-amber-400 text-[10px]"></i>)</label>
                        <input type="number" id="calc-info" value="0" min="0" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-indigo-500/50 calc-input" />
                    </div>
                    <div class="glass-card p-4 border border-white/5">
                        <label class="text-xs text-gray-500 mb-2 block"><i class="fa-solid fa-boxes-stacked text-violet-400 mr-1"></i> Infografika Paketi (20 <i class="fa-solid fa-coins text-amber-400 text-[10px]"></i>)</label>
                        <input type="number" id="calc-paket" value="0" min="0" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-indigo-500/50 calc-input" />
                    </div>
                    <div class="glass-card p-4 border border-white/5">
                        <label class="text-xs text-gray-500 mb-2 block"><i class="fa-solid fa-shirt text-pink-400 mr-1"></i> Fashion AI (8 <i class="fa-solid fa-coins text-amber-400 text-[10px]"></i>)</label>
                        <input type="number" id="calc-fashion" value="0" min="0" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-indigo-500/50 calc-input" />
                    </div>
                    <div class="glass-card p-4 border border-white/5">
                        <label class="text-xs text-gray-500 mb-2 block"><i class="fa-solid fa-camera-retro text-rose-400 mr-1"></i> Fotosesiya PRO (30 <i class="fa-solid fa-coins text-amber-400 text-[10px]"></i>)</label>
                        <input type="number" id="calc-fotosesiya" value="0" min="0" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-indigo-500/50 calc-input" />
                    </div>
                    <div class="glass-card p-4 border border-white/5">
                        <label class="text-xs text-gray-500 mb-2 block"><i class="fa-solid fa-brain text-emerald-400 mr-1"></i> Kartochka AI (3 <i class="fa-solid fa-coins text-amber-400 text-[10px]"></i>)</label>
                        <input type="number" id="calc-kartochka" value="0" min="0" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-indigo-500/50 calc-input" />
                    </div>
                </div>

                <!-- Result -->
                <div class="glass-card p-5 border border-indigo-500/20 bg-gradient-to-r from-indigo-900/10 to-purple-900/10">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-center sm:text-left">
                            <div class="text-xs text-gray-500 mb-1">Sizga kerak:</div>
                            <div class="flex items-baseline gap-2">
                                <span id="calc-total" class="text-3xl font-extrabold text-white">0</span>
                                <span class="text-sm text-gray-400"><i class="fa-solid fa-coins text-amber-400 text-xs"></i> tanga</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-xs text-gray-500 mb-1">Tavsiya etiladi:</div>
                            <div id="calc-recommend" class="text-lg font-bold text-indigo-400">—</div>
                        </div>
                        <div class="text-center sm:text-right">
                            <div class="text-xs text-gray-500 mb-1">Narxi:</div>
                            <div class="flex items-baseline gap-1 justify-center sm:justify-end">
                                <span id="calc-price" class="text-2xl font-extrabold text-emerald-400">0</span>
                                <span class="text-sm text-gray-500">so'm</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="max-w-3xl mx-auto mb-16">
            <h2 class="text-2xl font-bold text-white text-center mb-8"><i class="fa-solid fa-circle-question text-indigo-400"></i> Ko'p so'raladigan savollar</h2>
            <div class="space-y-3" id="faq-list">
                <div class="faq-item glass-card border border-white/5 overflow-hidden">
                    <button class="faq-toggle w-full text-left p-5 flex items-center justify-between" onclick="toggleFaq(this)">
                        <span class="text-sm font-medium text-white">Tanga muddati bormi?</span>
                        <svg class="w-5 h-5 text-gray-500 faq-arrow transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                    <div class="faq-answer hidden px-5 pb-5">
                        <p class="text-sm text-gray-400 leading-relaxed"><strong class="text-white">Yo'q!</strong> Sotib olingan tangalarning muddati cheksiz. Xohlaganingizda ishlating — tanga yo'qolmaydi.</p>
                    </div>
                </div>
                <div class="faq-item glass-card border border-white/5 overflow-hidden">
                    <button class="faq-toggle w-full text-left p-5 flex items-center justify-between" onclick="toggleFaq(this)">
                        <span class="text-sm font-medium text-white">To'lov qanday amalga oshiriladi?</span>
                        <svg class="w-5 h-5 text-gray-500 faq-arrow transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                    <div class="faq-answer hidden px-5 pb-5">
                        <p class="text-sm text-gray-400 leading-relaxed">To'lovlar <strong class="text-white">Click</strong>, <strong class="text-white">Payme</strong>, <strong class="text-white">Uzum Bank</strong> va bank kartasi orqali amalga oshiriladi. To'lov darhol hisobga tushadi.</p>
                    </div>
                </div>
                <div class="faq-item glass-card border border-white/5 overflow-hidden">
                    <button class="faq-toggle w-full text-left p-5 flex items-center justify-between" onclick="toggleFaq(this)">
                        <span class="text-sm font-medium text-white">Tanga yetmasa nima bo'ladi?</span>
                        <svg class="w-5 h-5 text-gray-500 faq-arrow transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                    <div class="faq-answer hidden px-5 pb-5">
                        <p class="text-sm text-gray-400 leading-relaxed">Xavotir olmang — tangalar tugaganda istalgan paketni qo'shimcha sotib olasiz. Yangi tangalar avvalgisiga qo'shiladi. Hech nimani yo'qotmaysiz.</p>
                    </div>
                </div>
                <div class="faq-item glass-card border border-white/5 overflow-hidden">
                    <button class="faq-toggle w-full text-left p-5 flex items-center justify-between" onclick="toggleFaq(this)">
                        <span class="text-sm font-medium text-white">Yaratilgan rasmlar meniki bo'ladimi?</span>
                        <svg class="w-5 h-5 text-gray-500 faq-arrow transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                    <div class="faq-answer hidden px-5 pb-5">
                        <p class="text-sm text-gray-400 leading-relaxed">Ha! Barcha yaratilgan rasmlar to'liq sizniki. Tijorat maqsadlarda, marketplace'larda, reklama uchun — hech qanday cheklovsiz foydalaning.</p>
                    </div>
                </div>
                <div class="faq-item glass-card border border-white/5 overflow-hidden">
                    <button class="faq-toggle w-full text-left p-5 flex items-center justify-between" onclick="toggleFaq(this)">
                        <span class="text-sm font-medium text-white">Qaytarish mumkinmi?</span>
                        <svg class="w-5 h-5 text-gray-500 faq-arrow transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                    <div class="faq-answer hidden px-5 pb-5">
                        <p class="text-sm text-gray-400 leading-relaxed">Agar birorta ham tanga ishlatmagan bo'lsangiz, 24 soat ichida to'liq qaytarish mumkin. Tanga ishlatilgan bo'lsa, qolgan tangalar saqlanadi.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="glass-card p-8 sm:p-12 text-center border border-indigo-500/10 bg-gradient-to-br from-indigo-900/10 to-purple-900/10">
            <div class="text-3xl mb-3"><i class="fa-solid fa-gift text-indigo-400"></i></div>
            <h2 class="text-2xl sm:text-3xl font-bold text-white mb-3">
                Birinchi marta ro'yxatdan o'ting
            </h2>
            <p class="text-gray-400 max-w-lg mx-auto mb-6">
                Hoziroq ro'yxatdan o'ting va <strong class="text-emerald-400">10 ta bepul tanga</strong> oling. Karta ma'lumotlarini kiritish shart emas.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="/create" class="btn-primary px-8 py-3 text-lg font-bold">
                    <i class="fa-solid fa-sparkles mr-2"></i> Bepul sinash
                </a>
                <a href="/contact" class="btn-secondary px-8 py-3 text-lg">
                    <i class="fa-solid fa-comment-dots mr-2"></i> Savollar?
                </a>
            </div>
        </div>
    </div>

    </div>
</div>

<script>
// Calculator
const prices = {
    'calc-foto': 5,
    'calc-info': 5,
    'calc-paket': 20,
    'calc-fashion': 8,
    'calc-fotosesiya': 30,
    'calc-kartochka': 3,
};

const packages = [
    { name: 'Boshlang\'ich (50)', credits: 50, price: 69000 },
    { name: 'Professional (150)', credits: 150, price: 189000 },
    { name: 'Biznes (500)', credits: 500, price: 549000 },
    { name: 'Enterprise (1500)', credits: 1500, price: 1449000 },
];

function updateCalc() {
    let total = 0;
    for (const [id, cost] of Object.entries(prices)) {
        const val = parseInt(document.getElementById(id).value) || 0;
        total += val * cost;
    }

    document.getElementById('calc-total').textContent = total.toLocaleString('uz-UZ');

    if (total === 0) {
        document.getElementById('calc-recommend').textContent = '—';
        document.getElementById('calc-price').textContent = '0';
        return;
    }

    let best = packages[packages.length - 1];
    for (const pkg of packages) {
        if (pkg.credits >= total) {
            best = pkg;
            break;
        }
    }

    if (total > 1500) {
        const count = Math.ceil(total / 1500);
        document.getElementById('calc-recommend').textContent = count + 'x Enterprise (1500)';
        document.getElementById('calc-price').textContent = (count * 1449000).toLocaleString('uz-UZ');
    } else {
        document.getElementById('calc-recommend').textContent = best.name;
        document.getElementById('calc-price').textContent = best.price.toLocaleString('uz-UZ');
    }
}

document.querySelectorAll('.calc-input').forEach(input => {
    input.addEventListener('input', updateCalc);
});

// FAQ toggle
function toggleFaq(btn) {
    const item = btn.closest('.faq-item');
    const answer = item.querySelector('.faq-answer');
    const arrow = item.querySelector('.faq-arrow');

    if (answer.classList.contains('hidden')) {
        document.querySelectorAll('.faq-answer').forEach(a => a.classList.add('hidden'));
        document.querySelectorAll('.faq-arrow').forEach(a => a.style.transform = '');
        answer.classList.remove('hidden');
        arrow.style.transform = 'rotate(180deg)';
    } else {
        answer.classList.add('hidden');
        arrow.style.transform = '';
    }
}

// ========== TO'LOVLAR TARIXI ==========

async function loadPaymentHistory() {
    const token = typeof TibaAuth !== 'undefined' ? TibaAuth.getToken() : localStorage.getItem('auth_token');
    if (!token) return;

    const section = document.getElementById('payment-history-section');
    const loading = document.getElementById('history-loading');
    const empty = document.getElementById('history-empty');
    const list = document.getElementById('history-list');

    section.classList.remove('hidden');
    loading.classList.remove('hidden');
    empty.classList.add('hidden');
    list.innerHTML = '';

    try {
        const resp = await fetch('/api/payment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-User-Token': token,
            },
            body: JSON.stringify({ action: 'check_payment' }),
        });
        const data = await resp.json();

        loading.classList.add('hidden');

        if (!data.payments || data.payments.length === 0) {
            empty.classList.remove('hidden');
            return;
        }

        data.payments.forEach(p => {
            const statusMap = {
                pending:  { icon: 'fa-clock',        color: 'amber',   text: 'Kutilmoqda' },
                approved: { icon: 'fa-circle-check',  color: 'emerald', text: 'Tasdiqlangan' },
                rejected: { icon: 'fa-circle-xmark',  color: 'red',     text: 'Rad etilgan' },
            };
            const s = statusMap[p.status] || statusMap.pending;
            const date = new Date(p.created_at).toLocaleDateString('uz-UZ', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
            const amount = Number(p.amount).toLocaleString('uz-UZ');

            list.innerHTML += `
                <div class="px-5 sm:px-6 py-4 hover:bg-white/[0.02] transition-colors border-b border-white/[0.03] last:border-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-${s.color}-500/10 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid ${s.icon} text-${s.color}-400"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-semibold text-white truncate">${p.package_name}</div>
                                <div class="text-[11px] text-gray-500 flex items-center gap-2 mt-0.5">
                                    <span>#${p.id}</span>
                                    <span class="text-gray-700">•</span>
                                    <span>${date}</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 ml-3">
                            <div class="text-sm font-bold text-white">${amount} <span class="text-[10px] text-gray-500 font-normal">so'm</span></div>
                            <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-${s.color}-500/10 border border-${s.color}-500/20 mt-1">
                                <i class="fa-solid ${s.icon} text-${s.color}-400 text-[9px]"></i>
                                <span class="text-[10px] font-semibold text-${s.color}-400">${s.text}</span>
                            </div>
                        </div>
                    </div>
                    ${p.status === 'rejected' && p.admin_note ? `<div class="mt-2 ml-13 pl-14 text-[11px] text-red-400/80 flex items-center gap-1.5"><i class="fa-solid fa-comment-dots text-[9px]"></i> <span>Sabab: ${p.admin_note}</span></div>` : ''}
                </div>
            `;
        });
    } catch (err) {
        loading.classList.add('hidden');
        list.innerHTML = `<div class="py-8 text-center text-sm text-red-400"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Yuklab bo'lmadi</div>`;
    }
}

// Sahifa yuklanganda
document.addEventListener('DOMContentLoaded', () => {
    // Foydalanuvchi kirgandan keyin tarixi yuklansin
    const checkAndLoad = () => {
        if (typeof TibaAuth !== 'undefined' && TibaAuth.isLoggedIn()) {
            loadPaymentHistory();
            // Hash bo'lsa scroll qil
            if (window.location.hash === '#history') {
                setTimeout(() => {
                    document.getElementById('history')?.scrollIntoView({ behavior: 'smooth' });
                }, 500);
            }
        }
    };

    // Biroz kutish — TibaAuth session tekshirishi tugasin
    setTimeout(checkAndLoad, 1500);
});
</script>

<!-- ========== TO'LOV MODAL ========== -->
<div id="payment-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/90 backdrop-blur-md" onclick="PaymentModal.close()"></div>
    <div class="relative w-full max-w-md animate-fade-in-up max-h-[90vh] overflow-y-auto">
        <div class="p-6 sm:p-8 border border-white/10 shadow-2xl shadow-indigo-500/10 rounded-2xl" style="background:#0d0d15;">
            <button onclick="PaymentModal.close()" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:text-white hover:bg-white/10 transition-all z-10">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <!-- Step 1: To'lov usulini tanlash -->
            <div id="pay-step1">
                <div class="text-center mb-6">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-indigo-500/30">
                        <i class="fa-solid fa-cart-shopping text-white text-xl"></i>
                    </div>
                    <h2 class="text-xl font-extrabold text-white">To'lov</h2>
                    <p class="text-sm text-gray-500 mt-1">To'lov usulini tanlang</p>
                </div>

                <!-- Tanlangan paket -->
                <div id="pay-selected-pkg" class="glass-card p-4 border border-indigo-500/20 bg-gradient-to-r from-indigo-900/10 to-purple-900/10 mb-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div id="pay-pkg-icon" class="w-10 h-10 rounded-xl flex items-center justify-center bg-gradient-to-br from-indigo-600 to-purple-600">
                                <i class="fa-solid fa-rocket text-white"></i>
                            </div>
                            <div>
                                <div id="pay-pkg-name" class="text-white font-bold text-sm">Professional</div>
                                <div id="pay-pkg-credits" class="text-xs text-gray-400"><i class="fa-solid fa-coins text-amber-400 text-[10px]"></i> 150 tanga</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div id="pay-pkg-price" class="text-lg font-extrabold text-white">189,000</div>
                            <div class="text-[10px] text-gray-500">so'm</div>
                        </div>
                    </div>
                </div>

                <!-- To'lov usullari -->
                <div class="space-y-2.5 mb-5">
                    <button onclick="PaymentModal.selectMethod('card')" class="pay-method-btn w-full flex items-center gap-4 p-4 rounded-xl border border-white/10 bg-white/[0.03] hover:bg-white/[0.06] hover:border-indigo-500/30 transition-all text-left group" data-method="card">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                            <i class="fa-solid fa-credit-card text-emerald-400"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-white font-semibold text-sm">Karta orqali</div>
                            <div class="text-[11px] text-gray-500">Bank kartasiga o'tkazma</div>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-600 group-hover:text-indigo-400 transition-colors text-xs"></i>
                    </button>

                    <button onclick="PaymentModal.selectMethod('click')" class="pay-method-btn w-full flex items-center gap-4 p-4 rounded-xl border border-white/10 bg-white/[0.03] hover:bg-white/[0.06] hover:border-blue-500/30 transition-all text-left group" style="<?= $clickEnabled ? '' : 'display:none' ?>">
                        <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center">
                            <i class="fa-solid fa-mobile-screen text-blue-400"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-white font-semibold text-sm">Click</div>
                            <div class="text-[11px] text-gray-500">Online to'lov — Click ilovasi</div>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-600 group-hover:text-blue-400 transition-colors text-xs"></i>
                    </button>

                    <button onclick="PaymentModal.selectMethod('payme')" class="pay-method-btn w-full flex items-center gap-4 p-4 rounded-xl border border-white/10 bg-white/[0.03] hover:bg-white/[0.06] hover:border-cyan-500/30 transition-all text-left group" style="<?= $paymeEnabled ? '' : 'display:none' ?>">
                        <div class="w-10 h-10 rounded-xl bg-cyan-500/10 flex items-center justify-center">
                            <i class="fa-solid fa-wallet text-cyan-400"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-white font-semibold text-sm">Payme</div>
                            <div class="text-[11px] text-gray-500">Online to'lov — Payme ilovasi</div>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-600 group-hover:text-cyan-400 transition-colors text-xs"></i>
                    </button>
                </div>
            </div>

            <!-- Step 2: Karta ma'lumotlari + Chek yuborish -->
            <div id="pay-step2" class="hidden">
                <div class="text-center mb-5">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-emerald-500/30">
                        <i class="fa-solid fa-credit-card text-white text-xl"></i>
                    </div>
                    <h2 class="text-xl font-extrabold text-white">Karta orqali to'lov</h2>
                    <p class="text-sm text-gray-500 mt-1">Quyidagi kartaga to'lovni amalga oshiring</p>
                </div>

                <!-- Karta raqami -->
                <div class="glass-card p-5 border border-emerald-500/20 bg-gradient-to-br from-emerald-900/10 to-teal-900/10 mb-4 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                    <div class="relative">
                        <div class="text-[11px] text-gray-500 mb-2 font-medium uppercase tracking-wider">Karta raqami</div>
                        <div class="flex items-center justify-between">
                            <span id="pay-card-number" class="text-xl sm:text-2xl font-bold text-white tracking-wider font-mono">8600 0000 0000 0000</span>
                            <button onclick="PaymentModal.copyCard()" id="pay-copy-btn" class="w-9 h-9 rounded-lg bg-white/10 hover:bg-emerald-500/20 flex items-center justify-center transition-all" title="Nusxalash">
                                <i id="pay-copy-icon" class="fa-solid fa-copy text-gray-400 text-sm"></i>
                            </button>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <span id="pay-card-holder" class="text-xs text-gray-400 uppercase tracking-wider">TIBA AI</span>
                            <div class="flex items-center gap-1 text-emerald-400 text-xs font-medium">
                                <i class="fa-solid fa-shield-halved text-[10px]"></i> Xavfsiz
                            </div>
                        </div>
                    </div>
                </div>

                <!-- To'lov summasi -->
                <div class="flex items-center justify-between bg-white/[0.03] border border-white/5 rounded-xl p-4 mb-4">
                    <span class="text-sm text-gray-400">To'lov summasi:</span>
                    <span class="text-lg font-extrabold text-white"><span id="pay-amount">189,000</span> so'm</span>
                </div>

                <!-- Chek yuborish -->
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-400 mb-2">
                        <i class="fa-solid fa-receipt mr-1"></i> To'lov chekini yuklang
                    </label>
                    <div id="pay-upload-area" class="relative border-2 border-dashed border-white/10 rounded-xl p-6 text-center cursor-pointer hover:border-indigo-500/40 hover:bg-white/[0.02] transition-all"
                         onclick="document.getElementById('pay-file-input').click()">
                        <input type="file" id="pay-file-input" accept="image/*" class="hidden" onchange="PaymentModal.handleFile(event)">
                        
                        <div id="pay-upload-placeholder">
                            <div class="w-12 h-12 bg-indigo-500/10 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-cloud-arrow-up text-indigo-400 text-xl"></i>
                            </div>
                            <p class="text-sm text-gray-400 mb-1">Chek rasmini yuklang</p>
                            <p class="text-[11px] text-gray-600">PNG, JPG, WEBP (max 10MB)</p>
                        </div>

                        <div id="pay-upload-preview" class="hidden">
                            <img id="pay-preview-img" class="max-h-40 mx-auto rounded-lg mb-2" alt="Chek">
                            <p class="text-xs text-emerald-400"><i class="fa-solid fa-circle-check mr-1"></i> Chek yuklandi</p>
                            <button type="button" onclick="event.stopPropagation(); PaymentModal.removeFile()" class="text-[11px] text-red-400 hover:text-red-300 mt-1">
                                <i class="fa-solid fa-trash-can mr-1"></i> O'chirish
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Error -->
                <div id="pay-error" class="hidden bg-red-500/10 border border-red-500/20 text-red-400 p-3 rounded-xl text-xs text-center mb-4"></div>

                <!-- Buttons -->
                <div class="flex gap-3">
                    <button onclick="PaymentModal.backToStep1()" class="flex-1 py-3 rounded-xl border border-white/10 text-gray-400 hover:text-white hover:bg-white/5 transition-all text-sm font-medium">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Orqaga
                    </button>
                    <button onclick="PaymentModal.submit()" id="pay-submit-btn" class="flex-[2] py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-semibold text-sm transition-all shadow-lg shadow-emerald-500/20 active:scale-[0.98] flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span id="pay-submit-text">Chekni yuborish</span>
                    </button>
                </div>
            </div>

            <!-- Step 3: Muvaffaqiyat -->
            <div id="pay-step3" class="hidden">
                <div class="text-center py-4">
                    <div class="w-20 h-20 bg-emerald-500/10 border border-emerald-500/20 rounded-full flex items-center justify-center mx-auto mb-5">
                        <i class="fa-solid fa-circle-check text-emerald-400 text-4xl"></i>
                    </div>
                    <h2 class="text-xl font-extrabold text-white mb-2">Chek yuborildi!</h2>
                    <p class="text-sm text-gray-400 mb-1">To'lovingiz tekshirilmoqda</p>
                    <p class="text-xs text-gray-600 mb-6">Odatda 5–30 daqiqada tasdiqlanadi</p>

                    <div id="pay-success-details" class="glass-card p-4 border border-white/5 text-left mb-6 space-y-2">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">To'lov ID:</span>
                            <span id="pay-result-id" class="text-white font-mono">#—</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Paket:</span>
                            <span id="pay-result-pkg" class="text-white">—</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Summa:</span>
                            <span id="pay-result-amount" class="text-white">—</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Holat:</span>
                            <span class="text-amber-400 font-medium"><i class="fa-solid fa-clock mr-1"></i> Kutilmoqda</span>
                        </div>
                    </div>

                    <button onclick="PaymentModal.close()" class="w-full py-3 rounded-xl bg-white/5 border border-white/10 text-white font-semibold text-sm hover:bg-white/10 transition-all">
                        Yopish
                    </button>
                </div>
            </div>

            <!-- Step 4: Click / Payme yo'naltirish -->
            <div id="pay-step4" class="hidden">
                <div class="text-center py-6">
                    <div id="pay-redirect-icon" class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i id="pay-redirect-provider-icon" class="fa-solid fa-mobile-screen text-white text-2xl"></i>
                    </div>
                    <h2 class="text-xl font-extrabold text-white mb-2" id="pay-redirect-title">To'lov yaratilmoqda...</h2>
                    <p class="text-sm text-gray-400 mb-6" id="pay-redirect-desc">Iltimos, biroz kuting</p>

                    <!-- Spinner: invoice yaratilayotganda -->
                    <div id="pay-redirect-loader" class="flex justify-center mb-6">
                        <i class="fa-solid fa-circle-notch fa-spin text-3xl text-blue-400"></i>
                    </div>

                    <!-- Invoice tayyor: to'lov havolasi + tekshirish -->
                    <div id="pay-redirect-btn-wrap" class="hidden space-y-2.5">
                        <a id="pay-redirect-link" href="#" target="_blank"
                           class="inline-flex items-center justify-center gap-2 w-full py-3.5 rounded-xl font-bold text-sm text-white shadow-lg transition-all hover:opacity-90"
                           style="background:linear-gradient(135deg,#1a73e8,#0056b3)">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            <span id="pay-redirect-btn-text">To'lovni amalga oshirish</span>
                        </a>

                        <!-- To'lov qildim tekshirish -->
                        <button id="pay-check-btn" onclick="PaymentModal.checkPaymentStatus()" 
                                class="inline-flex items-center justify-center gap-2 w-full py-3 rounded-xl border border-emerald-500/30 text-emerald-400 font-semibold text-sm hover:bg-emerald-500/10 transition-all">
                            <i class="fa-solid fa-rotate id-spin-check"></i>
                            <span id="pay-check-text">To'lov qildim — Tekshirish</span>
                        </button>

                        <!-- Auto-polling holati -->
                        <div id="pay-polling-status" class="text-[11px] text-gray-600 flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-circle-notch fa-spin text-[10px]"></i>
                            Avtomatik tekshirilmoqda...
                        </div>

                        <button onclick="PaymentModal.backToStep1()" class="w-full py-2.5 rounded-xl border border-white/10 text-gray-500 hover:text-white text-sm transition-all">
                            <i class="fa-solid fa-arrow-left mr-1"></i> Boshqa usulni tanlash
                        </button>
                    </div>

                    <div id="pay-redirect-error" class="hidden bg-red-500/10 border border-red-500/20 text-red-400 p-3 rounded-xl text-xs text-center mb-3"></div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ========== TO'LOV JS ========== -->
<script>
const PaymentModal = (() => {
    const $ = id => document.getElementById(id);

    const packages = <?= json_encode(
        array_combine(
            array_column($dbPackages, 'id'),
            array_map(function($p) {
                return [
                    'name' => $p['name'],
                    'credits' => intval($p['credits']),
                    'price' => intval($p['price']),
                    'icon' => $p['icon'],
                    'gradient' => $p['gradient'],
                ];
            }, $dbPackages)
        ),
        JSON_UNESCAPED_UNICODE
    ) ?>;

    let selectedPackage = null;
    let receiptBase64 = null;
    let currentPaymentId = null;
    let pollingInterval = null;

    function open(packageId) {
        // Auth tekshirish
        if (typeof TibaAuth !== 'undefined' && !TibaAuth.isLoggedIn()) {
            TibaAuth.requireAuth(() => open(packageId));
            return;
        }

        selectedPackage = packageId;
        const pkg = packages[packageId];
        if (!pkg) return;

        // Paket ma'lumotlarini ko'rsatish
        $('pay-pkg-icon').className = `w-10 h-10 rounded-xl flex items-center justify-center bg-gradient-to-br ${pkg.gradient}`;
        $('pay-pkg-icon').innerHTML = `<i class="fa-solid ${pkg.icon} text-white"></i>`;
        $('pay-pkg-name').textContent = pkg.name;
        $('pay-pkg-credits').innerHTML = `<i class="fa-solid fa-coins text-amber-400 text-[10px]"></i> ${pkg.credits} tanga`;
        $('pay-pkg-price').textContent = pkg.price.toLocaleString('uz-UZ');
        $('pay-amount').textContent = pkg.price.toLocaleString('uz-UZ');

        // Reset
        receiptBase64 = null;
        currentPaymentId = null;
        stopPolling();
        showStep(1);
        $('pay-error').classList.add('hidden');
        $('payment-modal').classList.remove('hidden');

        // Karta ma'lumotlarini API'dan olish
        loadCardInfo();
    }

    async function loadCardInfo() {
        try {
            const resp = await fetch('/api/payment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'get_packages' }),
            });
            const data = await resp.json();
            if (data.card) {
                $('pay-card-number').textContent = data.card.number;
                $('pay-card-holder').textContent = data.card.holder;
            }
        } catch (e) { /* silently fail, defaults already shown */ }
    }

    function close() {
        $('payment-modal').classList.add('hidden');
        selectedPackage = null;
        receiptBase64 = null;
        currentPaymentId = null;
        stopPolling();
        removeFile();
    }

    function showStep(n) {
        $('pay-step1').classList.toggle('hidden', n !== 1);
        $('pay-step2').classList.toggle('hidden', n !== 2);
        $('pay-step3').classList.toggle('hidden', n !== 3);
        $('pay-step4').classList.toggle('hidden', n !== 4);
    }

    async function selectMethod(method) {
        if (method === 'card') {
            showStep(2);
            removeFile();
            return;
        }
        // Click yoki Payme
        if (method === 'click' || method === 'payme') {
            await createInvoice(method);
        }
    }

    async function createInvoice(provider) {
        showStep(4);

        // Provider ikonkasi
        const isClick = provider === 'click';
        const icon    = $('pay-redirect-provider-icon');
        const iconWrap = $('pay-redirect-icon');
        icon.className = isClick ? 'fa-solid fa-mobile-screen text-white text-2xl' : 'fa-solid fa-wallet text-white text-2xl';
        iconWrap.style.background = isClick
            ? 'linear-gradient(135deg,#1a73e8,#0056b3)'
            : 'linear-gradient(135deg,#00bcd4,#0097a7)';
        $('pay-redirect-title').textContent = (isClick ? 'Click' : 'Payme') + " orqali to'lov";
        $('pay-redirect-desc').textContent  = "To'lov havolasi tayyorlanmoqda...";
        $('pay-redirect-loader').classList.remove('hidden');
        $('pay-redirect-btn-wrap').classList.add('hidden');
        $('pay-redirect-error').classList.add('hidden');

        const token = typeof TibaAuth !== 'undefined' ? TibaAuth.getToken() : localStorage.getItem('auth_token');
        if (!token) {
            showRedirectError("Tizimga kiring");
            return;
        }

        try {
            const resp = await fetch('/api/create-invoice.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-User-Token': token,
                },
                body: JSON.stringify({ package_id: selectedPackage, provider }),
            });
            const data = await resp.json();

            if (data.error) { showRedirectError(data.error); return; }

            if (data.success && data.payment_url) {
                currentPaymentId = data.payment_id;

                $('pay-redirect-loader').classList.add('hidden');
                $('pay-redirect-desc').textContent = "To'lovni amalga oshirish uchun quyidagi tugmani bosing";

                const link = $('pay-redirect-link');
                link.href = data.payment_url;
                link.classList.remove('hidden');
                link.style.background = isClick
                    ? 'linear-gradient(135deg,#1a73e8,#0056b3)'
                    : 'linear-gradient(135deg,#00bcd4,#0097a7)';
                $('pay-redirect-btn-text').textContent = (isClick ? 'Click' : 'Payme') + " orqali to'lash";
                $('pay-redirect-btn-wrap').classList.remove('hidden');

                // Reset check button state
                $('pay-check-btn').disabled = false;
                $('pay-check-text').textContent = "To'lov qildim — Tekshirish";
                $('pay-polling-status').innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-[10px]"></i> Avtomatik tekshirilmoqda...';

                // Avtomatik yangi tabda ochish
                window.open(data.payment_url, '_blank');

                // Auto-polling boshlash (har 5 sekundda)
                startPolling();
            } else {
                showRedirectError("To'lov havolasini olishda xato");
            }
        } catch (err) {
            showRedirectError("Tarmoq xatosi. Qaytadan urinib ko'ring.");
        }
    }

    function startPolling() {
        stopPolling();
        const startTime = Date.now();
        const maxDuration = 5 * 60 * 1000; // 5 daqiqa

        pollingInterval = setInterval(async () => {
            // 5 daqiqadan keyin to'xtatish
            if (Date.now() - startTime > maxDuration) {
                stopPolling();
                const statusEl = $('pay-polling-status');
                if (statusEl) statusEl.innerHTML = '<i class="fa-solid fa-clock text-[10px] text-amber-400"></i> <span class="text-amber-400">Avtomatik tekshirish tugadi. Qo\'lda tekshiring.</span>';
                return;
            }
            await doCheckPayment(true);
        }, 5000);
    }

    function stopPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
    }

    async function checkPaymentStatus() {
        await doCheckPayment(false);
    }

    async function doCheckPayment(silent) {
        if (!currentPaymentId) return;

        const token = typeof TibaAuth !== 'undefined' ? TibaAuth.getToken() : localStorage.getItem('auth_token');
        if (!token) return;

        // UI loading (faqat manual tekshirishda)
        if (!silent) {
            const btn = $('pay-check-btn');
            const txt = $('pay-check-text');
            btn.disabled = true;
            txt.textContent = "Tekshirilmoqda...";
        }

        try {
            const resp = await fetch('/api/payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-User-Token': token,
                },
                body: JSON.stringify({ action: 'check_payment', payment_id: currentPaymentId }),
            });
            const data = await resp.json();

            if (data.success && data.payment) {
                const p = data.payment;

                if (p.status === 'approved') {
                    // ✅ Tasdiqlandi!
                    stopPolling();
                    showStep(3);
                    const pkg = packages[selectedPackage];
                    $('pay-result-id').textContent = '#' + p.id;
                    $('pay-result-pkg').textContent = (pkg ? pkg.name : p.package_name) + ' (' + p.credits + ' tanga)';
                    $('pay-result-amount').textContent = Number(p.amount).toLocaleString('uz-UZ') + " so'm";

                    // Muvaffaqiyat step3 ni yangilash
                    const statusRow = document.querySelector('#pay-success-details .text-amber-400');
                    if (statusRow) {
                        statusRow.className = 'text-emerald-400 font-medium';
                        statusRow.innerHTML = '<i class="fa-solid fa-circle-check mr-1"></i> Tasdiqlangan';
                    }

                    // Balansni yangilash (agar TibaAuth mavjud bo'lsa)
                    if (typeof TibaAuth !== 'undefined' && TibaAuth.refreshUser) {
                        TibaAuth.refreshUser();
                    }
                    return;
                }

                if (p.status === 'rejected') {
                    // ❌ Rad etildi
                    stopPolling();
                    $('pay-redirect-title').textContent = "To'lov rad etildi";
                    $('pay-redirect-desc').textContent = p.admin_note || "To'lovingiz rad etildi.";
                    $('pay-redirect-icon').style.background = 'linear-gradient(135deg,#ef4444,#dc2626)';
                    $('pay-redirect-provider-icon').className = 'fa-solid fa-circle-xmark text-white text-2xl';
                    $('pay-check-btn').classList.add('hidden');
                    $('pay-polling-status').innerHTML = '';
                    return;
                }

                // Hali pending
                if (!silent) {
                    const statusEl = $('pay-polling-status');
                    if (statusEl) statusEl.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-[10px]"></i> Hali kutilmoqda... Avtomatik tekshirilmoqda.';
                }
            }
        } catch (err) {
            // Tarmoq xatosi — sessiyani to'xtatmaslik
        } finally {
            if (!silent) {
                const btn = $('pay-check-btn');
                const txt = $('pay-check-text');
                if (btn) btn.disabled = false;
                if (txt) txt.textContent = "To'lov qildim — Tekshirish";
            }
        }
    }

    function showRedirectError(msg) {
        $('pay-redirect-loader').classList.add('hidden');
        $('pay-redirect-btn-wrap').classList.add('hidden');
        const errEl = $('pay-redirect-error');
        errEl.textContent = msg;
        errEl.classList.remove('hidden');
        // Orqaga tugma ko'rsatish
        $('pay-redirect-btn-wrap').classList.remove('hidden');
        $('pay-redirect-link').classList.add('hidden');
    }

    function backToStep1() {
        stopPolling();
        showStep(1);
        $('pay-error').classList.add('hidden');
    }

    function copyCard() {
        const num = $('pay-card-number').textContent.replace(/\s/g, '');
        navigator.clipboard.writeText(num).then(() => {
            const icon = $('pay-copy-icon');
            icon.className = 'fa-solid fa-check text-emerald-400 text-sm';
            $('pay-copy-btn').classList.add('bg-emerald-500/20');
            setTimeout(() => {
                icon.className = 'fa-solid fa-copy text-gray-400 text-sm';
                $('pay-copy-btn').classList.remove('bg-emerald-500/20');
            }, 2000);
        });
    }

    function handleFile(event) {
        const file = event.target.files[0];
        if (!file) return;

        if (!file.type.startsWith('image/')) {
            showError("Faqat rasm fayllarini yuklang");
            return;
        }
        if (file.size > 10 * 1024 * 1024) {
            showError("Rasm hajmi 10MB dan oshmasligi kerak");
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            receiptBase64 = e.target.result;
            $('pay-preview-img').src = receiptBase64;
            $('pay-upload-placeholder').classList.add('hidden');
            $('pay-upload-preview').classList.remove('hidden');
            $('pay-error').classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }

    function removeFile() {
        receiptBase64 = null;
        $('pay-file-input').value = '';
        $('pay-upload-placeholder').classList.remove('hidden');
        $('pay-upload-preview').classList.add('hidden');
    }

    function showError(msg) {
        const el = $('pay-error');
        el.textContent = msg;
        el.classList.remove('hidden');
    }

    async function submit() {
        $('pay-error').classList.add('hidden');

        if (!receiptBase64) {
            showError("To'lov chekini yuklang");
            return;
        }

        if (!selectedPackage) {
            showError("Paket tanlanmagan");
            return;
        }

        const token = typeof TibaAuth !== 'undefined' ? TibaAuth.getToken() : localStorage.getItem('auth_token');
        if (!token) {
            showError("Tizimga kiring");
            return;
        }

        // Loading
        const btn = $('pay-submit-btn');
        const txt = $('pay-submit-text');
        btn.disabled = true;
        btn.classList.add('opacity-70');
        txt.textContent = 'Yuborilmoqda...';

        try {
            const resp = await fetch('/api/payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-User-Token': token,
                },
                body: JSON.stringify({
                    action: 'submit_payment',
                    package_id: selectedPackage,
                    receipt_image: receiptBase64,
                }),
            });
            const data = await resp.json();

            if (data.error) {
                showError(data.error);
                return;
            }

            if (data.success) {
                const pkg = packages[selectedPackage];
                $('pay-result-id').textContent = '#' + data.payment_id;
                $('pay-result-pkg').textContent = pkg.name + ' (' + pkg.credits + ' tanga)';
                $('pay-result-amount').textContent = pkg.price.toLocaleString('uz-UZ') + " so'm";
                showStep(3);
            }
        } catch (err) {
            showError("Xatolik yuz berdi. Qaytadan urinib ko'ring.");
        } finally {
            btn.disabled = false;
            btn.classList.remove('opacity-70');
            txt.textContent = 'Chekni yuborish';
        }
    }

    return { open, close, selectMethod, backToStep1, copyCard, handleFile, removeFile, submit, createInvoice, checkPaymentStatus };
})();
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
