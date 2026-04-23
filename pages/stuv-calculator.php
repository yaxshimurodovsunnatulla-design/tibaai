<?php
require_once __DIR__ . '/../api/config.php';
$pageTitle = 'STUV Kalkulyatori – Tiba AI';
$pageDescription = 'Marketplacelar uchun professionall STUV (Sotuv, Tannarx, Foyda) kalkulyatori. Foydangizni aniq hisoblang.';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="py-12 sm:py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <div class="mb-10 text-center">
            <h1 class="text-3xl sm:text-4xl font-black text-white mb-3">
                <i class="fa-solid fa-calculator text-violet-500 mr-2"></i> STUV <span class="gradient-text">Kalkulyatori</span>
            </h1>
            <p class="text-gray-400">Mahsulotingizning tannarxi, komissiya va soliqlarini hisobga olib, aniq foydani aniqlang.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Input Side -->
            <div class="glass-card p-6 sm:p-8 space-y-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Sotuv narxi (so'm)</label>
                    <input type="number" id="calc-price" placeholder="Masalan: 150000" class="input-field text-lg font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Asl tannarxi (so'm)</label>
                    <input type="number" id="calc-cost" placeholder="Masalan: 80000" class="input-field text-lg font-bold">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Komissiya (%)</label>
                        <input type="number" id="calc-comm" value="15" class="input-field">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Soliq (%)</label>
                        <input type="number" id="calc-tax" value="4" class="input-field">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Logistika va boshqa (so'm)</label>
                    <input type="number" id="calc-other" value="5000" class="input-field">
                </div>
                
                <button onclick="calculateSTUV()" class="w-full py-4 rounded-xl bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white font-bold shadow-lg shadow-violet-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Hisoblash
                </button>
            </div>

            <!-- Results Side -->
            <div class="space-y-6">
                <div class="glass-card p-8 border border-white/5 bg-white/[0.02]">
                    <div class="text-center mb-8">
                        <div class="text-xs text-gray-500 uppercase tracking-widest mb-2 font-bold">Sof Foyda</div>
                        <div id="res-profit" class="text-5xl font-black text-white">0 <span class="text-xl font-normal text-gray-500">so'm</span></div>
                    </div>

                    <div class="space-y-4 pt-6 border-t border-white/5">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 text-sm">Rentabellik (ROI)</span>
                            <span id="res-roi" class="text-emerald-400 font-bold">0%</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 text-sm">Marginal daraja</span>
                            <span id="res-margin" class="text-blue-400 font-bold">0%</span>
                        </div>
                    </div>
                </div>

                <div class="glass-card p-6 border border-white/5 space-y-4">
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Xarajatlar strukturasi</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Marketpleys xizmati:</span>
                            <span id="res-comm-sum" class="text-white">0 so'm</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Soliq:</span>
                            <span id="res-tax-sum" class="text-white">0 so'm</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Boshqa xarajatlar:</span>
                            <span id="res-other-sum" class="text-white">0 so'm</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12 glass-card p-8 text-center bg-indigo-500/5 border-indigo-500/10">
            <h3 class="text-lg font-bold text-white mb-2">STUV nima?</h3>
            <p class="text-gray-400 text-sm">
                <b>S</b>otuv narxi, <b>T</b>annarx, <b>U</b>zum (yoki boshqa xizmat haq) va <b>V</b>azn (logistika). 
                Bu formula orqali marketplace sellerlari o'z foydalarini tez va aniq hisoblab boradilar.
            </p>
        </div>
    </div>
</div>

<script>
function calculateSTUV() {
    const price = parseFloat(document.getElementById('calc-price').value) || 0;
    const cost = parseFloat(document.getElementById('calc-cost').value) || 0;
    const commPerc = parseFloat(document.getElementById('calc-comm').value) || 0;
    const taxPerc = parseFloat(document.getElementById('calc-tax').value) || 0;
    const other = parseFloat(document.getElementById('calc-other').value) || 0;

    const commSum = price * (commPerc / 100);
    const taxSum = price * (taxPerc / 100);
    const totalExpenses = cost + commSum + taxSum + other;
    
    const profit = price - totalExpenses;
    const roi = cost > 0 ? (profit / cost) * 100 : 0;
    const margin = price > 0 ? (profit / price) * 100 : 0;

    const format = (v) => new Intl.NumberFormat('uz-UZ').format(Math.round(v));

    document.getElementById('res-profit').innerHTML = format(profit) + ' <span class="text-xl font-normal text-gray-500">so\'m</span>';
    document.getElementById('res-roi').textContent = roi.toFixed(1) + '%';
    document.getElementById('res-margin').textContent = margin.toFixed(1) + '%';
    document.getElementById('res-comm-sum').textContent = format(commSum) + ' so\'m';
    document.getElementById('res-tax-sum').textContent = format(taxSum) + ' so\'m';
    document.getElementById('res-other-sum').textContent = format(other) + ' so\'m';

    // Color feedback
    const profitEl = document.getElementById('res-profit');
    if (profit < 0) profitEl.className = 'text-5xl font-black text-red-400';
    else if (profit > 0) profitEl.className = 'text-5xl font-black text-emerald-400';
    else profitEl.className = 'text-5xl font-black text-white';
}
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
