<?php
require_once __DIR__ . '/../api/config.php';
$pageTitle = 'Sotuv Kalkulyatori – Tiba AI';
$pageDescription = 'Marketplacelar uchun professional Sotuv va Tannarx kalkulyatori. Foydangizni aniq hisoblang va saqlang.';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="py-12 sm:py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <div class="mb-10 text-center">
            <h1 class="text-3xl sm:text-4xl font-black mb-3" style="color:var(--text-heading)">
                <i class="fa-solid fa-calculator text-violet-500 mr-2"></i> Sotuv <span class="gradient-text">Kalkulyatori</span>
            </h1>
            <p style="color:var(--text-muted)">Mahsulotingizning tannarxi, komissiya va soliqlarini hisobga olib, aniq foydani aniqlang.</p>
        </div>

        <!-- Tab switcher -->
        <div class="flex justify-center gap-2 mb-10 bg-white/5 p-1.5 rounded-2xl border border-white/5 max-w-lg mx-auto">
            <button id="tab-tannarx" onclick="switchCalcTab('tannarx')" class="calc-tab-btn flex-1 px-5 py-3 rounded-xl text-sm font-bold transition-all bg-indigo-600 text-white shadow-lg">
                <i class="fa-solid fa-tag mr-1"></i> Unit iqtisod kalkulyatori
            </button>
            <button id="tab-sotuv" onclick="switchCalcTab('sotuv')" class="calc-tab-btn flex-1 px-5 py-3 rounded-xl text-sm font-bold transition-all text-gray-400 hover:text-white">
                <i class="fa-solid fa-calculator mr-1"></i> Xarid narx kalkulyatori
            </button>
        </div>

        <!-- ===== TANNARX KALKULYATORI ===== -->
        <div id="calc-tannarx" class="calc-panel">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="glass-card p-6 sm:p-8 space-y-5">
                    <h3 class="text-sm font-bold uppercase tracking-widest mb-4" style="color:var(--text-heading)">Ma'lumotlarni kiriting</h3>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--text-muted)">Marketpleysdagi sotuv narxi (UZS) <span class="text-red-400">*</span></label>
                        <div class="relative"><input type="number" id="t-price" placeholder="300 000" class="input-field text-lg font-bold pr-16"><span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold" style="color:var(--text-muted)">UZS</span></div>
                        <p class="text-[10px] mt-1" style="color:var(--text-muted)">Marketplasdagi haqiqiy sotuv narxi</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--text-muted)">Xarid narxi (UZS) <span class="text-red-400">*</span></label>
                        <div class="relative"><input type="number" id="t-cost" placeholder="100 000" class="input-field text-lg font-bold pr-16"><span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold" style="color:var(--text-muted)">UZS</span></div>
                        <p class="text-[10px] mt-1" style="color:var(--text-muted)">Tovarni sotib olish narxi</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--text-muted)">Yetkazib berish turi <span class="text-red-400">*</span></label>
                        <div class="flex gap-2">
                            <button type="button" onclick="setDelivery(this,'FBO')" class="delivery-btn px-5 py-2 rounded-xl text-xs font-bold border transition-all bg-indigo-600 text-white border-indigo-600" data-val="FBO">FBO</button>
                            <button type="button" onclick="setDelivery(this,'FBS')" class="delivery-btn px-5 py-2 rounded-xl text-xs font-bold border transition-all" style="border-color:var(--border-color);color:var(--text-muted)" data-val="FBS">FBS</button>
                            <button type="button" onclick="setDelivery(this,'DBS')" class="delivery-btn px-5 py-2 rounded-xl text-xs font-bold border transition-all" style="border-color:var(--border-color);color:var(--text-muted)" data-val="DBS">DBS</button>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--text-muted)">Kargo (logistika) <span class="text-[9px] font-normal">(ixtiyoriy)</span></label>
                            <div class="relative"><input type="number" id="t-kargo" value="50000" class="input-field pr-16"><span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold" style="color:var(--text-muted)">UZS</span></div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--text-muted)">Fulfillment <span class="text-[9px] font-normal">(ixtiyoriy)</span></label>
                            <div class="relative"><input type="number" id="t-fulfill" value="3000" class="input-field pr-16"><span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold" style="color:var(--text-muted)">UZS</span></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--text-muted)">Komissiya (%) <span class="text-[9px] font-normal">(ixtiyoriy)</span></label>
                            <div class="relative"><input type="number" id="t-comm" value="20" class="input-field pr-10"><span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold" style="color:var(--text-muted)">%</span></div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--text-muted)">MP logistika <span class="text-[9px] font-normal">(ixtiyoriy)</span></label>
                            <div class="relative"><input type="number" id="t-mplog" value="5000" class="input-field pr-16"><span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold" style="color:var(--text-muted)">UZS</span></div>
                        </div>
                    </div>
                    <!-- Foiz xarajatlar -->
                    <div class="glass-card p-4 border border-white/5 space-y-3">
                        <h4 class="text-xs font-bold" style="color:var(--text-heading)">Foiz xarajatlar</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div><label class="text-[10px] font-bold" style="color:var(--text-muted)">Bank foizi (%)</label><div class="relative"><input type="number" id="t-bank" value="1" class="input-field text-sm pr-8"><span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px]" style="color:var(--text-muted)">%</span></div></div>
                            <div><label class="text-[10px] font-bold" style="color:var(--text-muted)">Pul yechish (%)</label><div class="relative"><input type="number" id="t-withdraw" value="1" class="input-field text-sm pr-8"><span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px]" style="color:var(--text-muted)">%</span></div></div>
                            <div><label class="text-[10px] font-bold" style="color:var(--text-muted)">Brak/yo'qotish (%)</label><div class="relative"><input type="number" id="t-defect" value="3" class="input-field text-sm pr-8"><span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px]" style="color:var(--text-muted)">%</span></div></div>
                            <div><label class="text-[10px] font-bold" style="color:var(--text-muted)">Qo'shimcha (%)</label><div class="relative"><input type="number" id="t-extra" value="5" class="input-field text-sm pr-8"><span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px]" style="color:var(--text-muted)">%</span></div></div>
                        </div>
                        <div class="w-1/2"><label class="text-[10px] font-bold" style="color:var(--text-muted)">Soliq (%)</label><div class="relative"><input type="number" id="t-tax" value="1" class="input-field text-sm pr-8"><span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px]" style="color:var(--text-muted)">%</span></div></div>
                    </div>
                    <!-- Maqsadli foyda - sariq highlight -->
                    <div class="rounded-2xl border-2 border-amber-400/30 bg-amber-500/5 p-4 space-y-2">
                        <p class="text-xs font-bold text-amber-400">25% Maqsadli foyda olish uchun</p>
                        <div>
                            <label class="text-[10px] font-bold" style="color:var(--text-muted)">Maqsadli foyda (%) <span class="text-[9px] font-normal">(ixtiyoriy)</span></label>
                            <div class="relative"><input type="number" id="t-target" value="25" class="input-field text-sm pr-8"><span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px]" style="color:var(--text-muted)">%</span></div>
                            <p class="text-[9px] mt-1 text-amber-400/60">Tavsiya narxini hisoblash uchun maqsadli foyda foizi</p>
                        </div>
                    </div>
                    <!-- Ixtiyoriy -->
                    <details class="group">
                        <summary class="text-xs font-bold cursor-pointer select-none" style="color:var(--text-muted)">
                            <i class="fa-solid fa-chevron-right text-[8px] mr-1 group-open:rotate-90 transition-transform"></i> Ixtiyoriy ma'lumotlar
                        </summary>
                        <div class="mt-3 space-y-3">
                            <div><label class="text-[10px] font-bold" style="color:var(--text-muted)">Mahsulot nomi <span class="text-[8px] font-normal">(ixtiyoriy)</span></label><input type="text" id="t-name" placeholder="Mahsulotning nomi (saqlash uchun)" class="input-field text-sm"></div>
                            <div>
                                <label class="text-[10px] font-bold" style="color:var(--text-muted)">Mahsulot rasmi <span class="text-[8px] font-normal">(ixtiyoriy)</span></label>
                                <div class="mt-1 border-2 border-dashed rounded-xl p-6 text-center cursor-pointer hover:border-indigo-400/50 transition-colors" style="border-color:var(--border-color)" onclick="document.getElementById('t-image').click()">
                                    <i class="fa-regular fa-image text-2xl mb-2 block opacity-30" style="color:var(--text-muted)"></i>
                                    <p class="text-xs font-bold" style="color:var(--text-heading)">Rasm tanlash</p>
                                    <p class="text-[9px]" style="color:var(--text-muted)">Maksimal fayl hajmi: 10MB</p>
                                    <input type="file" id="t-image" accept="image/*" class="hidden">
                                </div>
                            </div>
                            <div><label class="text-[10px] font-bold" style="color:var(--text-muted)">Mahsulot havolasi <span class="text-[8px] font-normal">(ixtiyoriy)</span></label><input type="url" id="t-link" placeholder="https://uzum.uz/product/..." class="input-field text-sm"><p class="text-[9px] mt-1" style="color:var(--text-muted)">Marketplace'dagi mahsulot havolasi</p></div>
                            <div><label class="text-[10px] font-bold" style="color:var(--text-muted)">Izohlar <span class="text-[8px] font-normal">(ixtiyoriy)</span></label><textarea id="t-notes" rows="2" placeholder="Qo'shimcha ma'lumotlar" class="input-field text-sm"></textarea></div>
                        </div>
                    </details>
                    <div class="flex gap-3">
                        <button onclick="calcTannarx()" class="flex-1 py-4 rounded-xl bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white font-bold shadow-lg shadow-violet-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-calculator"></i> Hisoblash
                        </button>
                        <button onclick="clearForm('tannarx')" class="px-6 py-4 rounded-xl border font-bold transition-all hover:scale-[1.02]" style="border-color:var(--border-color);color:var(--text-muted)">Tozalash</button>
                    </div>
                </div>

                <div class="space-y-6">
                    <div id="t-results-placeholder" class="glass-card p-10 border border-dashed border-white/10 text-center">
                        <i class="fa-solid fa-clipboard-list text-4xl mb-4 block opacity-20" style="color:var(--text-muted)"></i>
                        <h4 class="text-lg font-bold mb-1" style="color:var(--text-heading)">Natijalar shu yerda ko'rinadi</h4>
                        <p class="text-sm" style="color:var(--text-muted)">Ma'lumotlarni kiriting va "Hisoblash" tugmasini bosing</p>
                    </div>
                    <div id="t-results-data" class="hidden space-y-4">
                        <div class="glass-card p-8 border border-white/5">
                            <div class="text-center mb-8">
                                <div class="text-xs uppercase tracking-widest mb-2 font-bold" style="color:var(--text-muted)">Sof Foyda</div>
                                <div id="t-profit" class="text-5xl font-black" style="color:var(--text-heading)">0 <span class="text-xl font-normal" style="color:var(--text-muted)">so'm</span></div>
                            </div>
                            <div class="space-y-3 pt-6 border-t border-white/5">
                                <div class="flex justify-between"><span class="text-sm" style="color:var(--text-muted)">Rentabellik (ROI)</span><span id="t-roi" class="text-emerald-400 font-bold">0%</span></div>
                                <div class="flex justify-between"><span class="text-sm" style="color:var(--text-muted)">Marginal daraja</span><span id="t-margin" class="text-blue-400 font-bold">0%</span></div>
                                <div class="flex justify-between"><span class="text-sm" style="color:var(--text-muted)">Yetkazish turi</span><span id="t-delivery-s" class="font-bold text-indigo-400">FBO</span></div>
                            </div>
                        </div>
                        <div class="glass-card p-6 border border-white/5 space-y-2">
                            <h4 class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--text-muted)">Xarajatlar strukturasi</h4>
                            <div class="flex justify-between text-sm"><span style="color:var(--text-muted)">Xarid narxi:</span><span id="t-cost-s" style="color:var(--text-heading)">0</span></div>
                            <div class="flex justify-between text-sm"><span style="color:var(--text-muted)">MP komissiya:</span><span id="t-comm-s" style="color:var(--text-heading)">0</span></div>
                            <div class="flex justify-between text-sm"><span style="color:var(--text-muted)">Fulfillment:</span><span id="t-fulfill-s" style="color:var(--text-heading)">0</span></div>
                            <div class="flex justify-between text-sm"><span style="color:var(--text-muted)">Kargo:</span><span id="t-kargo-s" style="color:var(--text-heading)">0</span></div>
                            <div class="flex justify-between text-sm"><span style="color:var(--text-muted)">MP logistika:</span><span id="t-mplog-s" style="color:var(--text-heading)">0</span></div>
                            <div class="flex justify-between text-sm"><span style="color:var(--text-muted)">Bank foizi:</span><span id="t-bank-s" style="color:var(--text-heading)">0</span></div>
                            <div class="flex justify-between text-sm"><span style="color:var(--text-muted)">Pul yechish:</span><span id="t-withdraw-s" style="color:var(--text-heading)">0</span></div>
                            <div class="flex justify-between text-sm"><span style="color:var(--text-muted)">Brak/yo'qotish:</span><span id="t-defect-s" style="color:var(--text-heading)">0</span></div>
                            <div class="flex justify-between text-sm"><span style="color:var(--text-muted)">Qo'shimcha:</span><span id="t-extra-s" style="color:var(--text-heading)">0</span></div>
                            <div class="flex justify-between text-sm"><span style="color:var(--text-muted)">Soliq:</span><span id="t-tax-s" style="color:var(--text-heading)">0</span></div>
                            <div class="flex justify-between text-sm font-bold border-t border-white/5 pt-2 mt-2"><span style="color:var(--text-muted)">Jami xarajat:</span><span id="t-total-s" class="text-red-400">0</span></div>
                        </div>
                        <!-- Tavsiya narx -->
                        <div id="t-tavsiya-box" class="hidden rounded-2xl border-2 border-amber-400/30 bg-amber-500/5 p-5 text-center">
                            <p class="text-xs font-bold text-amber-400 mb-1">Maqsadli foydaga erishish uchun</p>
                            <div class="text-2xl font-black text-amber-400" id="t-tavsiya-narx">0 so'm</div>
                            <p class="text-[10px] mt-1" style="color:var(--text-muted)">Tavsiya etilgan sotuv narxi</p>
                        </div>
                    </div>
                    <!-- Saqlash/Yuklab olish tugmalari -->
                    <div id="t-actions" class="hidden flex gap-3">
                        <button onclick="saveCalc('tannarx')" class="flex-1 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-bookmark"></i> Saqlash
                        </button>
                        <button onclick="downloadCalc('tannarx')" class="flex-1 py-3 rounded-xl border font-bold transition-all flex items-center justify-center gap-2" style="border-color:var(--border-color);color:var(--text-primary)">
                            <i class="fa-solid fa-download"></i> Yuklab olish
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== SOTUV NARX KALKULYATORI ===== -->
        <div id="calc-sotuv" class="calc-panel hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="glass-card p-6 sm:p-8 space-y-5">
                    <h3 class="text-sm font-bold uppercase tracking-widest mb-4" style="color:var(--text-heading)">Ma'lumotlarni kiriting</h3>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--text-muted)">Marketpleysdagi sotuv narxi (UZS) <span class="text-red-400">*</span></label>
                        <div class="relative"><input type="number" id="s-price-input" placeholder="100 000" class="input-field text-lg font-bold pr-16"><span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold" style="color:var(--text-muted)">UZS</span></div>
                        <p class="text-[10px] mt-1" style="color:var(--text-muted)">Tovarni sotmoqchi bo'lgan narx</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--text-muted)">Yetkazib berish turi <span class="text-red-400">*</span></label>
                        <div class="flex gap-2">
                            <button type="button" onclick="setDelivery2(this,'FBO')" class="delivery2-btn px-5 py-2 rounded-xl text-xs font-bold border transition-all bg-indigo-600 text-white border-indigo-600">FBO</button>
                            <button type="button" onclick="setDelivery2(this,'FBS')" class="delivery2-btn px-5 py-2 rounded-xl text-xs font-bold border transition-all" style="border-color:var(--border-color);color:var(--text-muted)">FBS</button>
                            <button type="button" onclick="setDelivery2(this,'DBS')" class="delivery2-btn px-5 py-2 rounded-xl text-xs font-bold border transition-all" style="border-color:var(--border-color);color:var(--text-muted)">DBS</button>
                        </div>
                    </div>
                    <!-- Kategoriya -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--text-muted)">Kategoriya <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <input type="text" id="s-category" list="category-list" placeholder="Kategoriya nomini kiriting..." class="input-field text-sm">
                            <datalist id="category-list">
                                <option value="Kiyim-kechak" data-comm="20">
                                <option value="Elektronika" data-comm="15">
                                <option value="Maishiy texnika" data-comm="18">
                                <option value="Go'zallik va parvarishlash" data-comm="20">
                                <option value="Salomatlik" data-comm="18">
                                <option value="Oziq-ovqat" data-comm="15">
                                <option value="Bolalar uchun" data-comm="20">
                                <option value="Uy-ro'zg'or" data-comm="18">
                                <option value="Sport va dam olish" data-comm="18">
                                <option value="Avtomobil" data-comm="15">
                                <option value="Kitoblar" data-comm="12">
                                <option value="Aksessuarlar" data-comm="22">
                            </datalist>
                        </div>
                        <p class="text-[10px] mt-1" style="color:var(--text-muted)">Kategoriyani tanlang — komissiya avtomatik o'rnatiladi</p>
                    </div>
                    <!-- Marketpleys logistika - MM/CM + o'lchamlar -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--text-muted)">Marketpleys logistika (UZS) <span class="text-red-400">*</span></label>
                        <div class="flex gap-2 mb-3">
                            <button type="button" onclick="setUnit(this,'mm')" class="unit-btn px-4 py-1.5 rounded-lg text-[10px] font-bold border transition-all" style="border-color:var(--border-color);color:var(--text-muted)">MM</button>
                            <button type="button" onclick="setUnit(this,'cm')" class="unit-btn px-4 py-1.5 rounded-lg text-[10px] font-bold border transition-all bg-indigo-600 text-white border-indigo-600">CM</button>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="text-[9px] font-bold" style="color:var(--text-muted)">Uzunlik</label>
                                <div class="relative"><input type="number" id="s-dim-l" value="0" class="input-field text-sm pr-10"><span class="absolute right-3 top-1/2 -translate-y-1/2 text-[9px]" style="color:var(--text-muted)" id="s-unit-l">cm</span></div>
                            </div>
                            <div>
                                <label class="text-[9px] font-bold" style="color:var(--text-muted)">Eni</label>
                                <div class="relative"><input type="number" id="s-dim-w" value="0" class="input-field text-sm pr-10"><span class="absolute right-3 top-1/2 -translate-y-1/2 text-[9px]" style="color:var(--text-muted)" id="s-unit-w">cm</span></div>
                            </div>
                            <div>
                                <label class="text-[9px] font-bold" style="color:var(--text-muted)">Balandlik</label>
                                <div class="relative"><input type="number" id="s-dim-h" value="0" class="input-field text-sm pr-10"><span class="absolute right-3 top-1/2 -translate-y-1/2 text-[9px]" style="color:var(--text-muted)" id="s-unit-h">cm</span></div>
                            </div>
                        </div>
                        <p class="text-[10px] mt-1" style="color:var(--text-muted)">O'lchamlarni kiriting — narx avtomatik hisoblanadi</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--text-muted)">Fulfillment (UZS) <span class="text-[9px] font-normal">(ixtiyoriy)</span></label>
                            <div class="relative"><input type="number" id="s-fulfill" value="3000" class="input-field pr-16"><span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold" style="color:var(--text-muted)">UZS</span></div>
                            <p class="text-[9px] mt-1" style="color:var(--text-muted)">Fulfillment xizmati xarajati</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--text-muted)">Logistika (kargo) <span class="text-[9px] font-normal">(ixtiyoriy)</span></label>
                            <div class="relative"><input type="number" id="s-other" value="10000" class="input-field pr-16"><span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold" style="color:var(--text-muted)">UZS</span></div>
                            <p class="text-[9px] mt-1" style="color:var(--text-muted)">Tovarni olib kelish xarajati</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--text-muted)">Kerakli yalpi rentabellik (%) <span class="text-red-400">*</span></label>
                        <div class="relative"><input type="number" id="s-margin" value="35" class="input-field pr-10"><span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold" style="color:var(--text-muted)">%</span></div>
                        <p class="text-[10px] mt-1" style="color:var(--text-muted)">Kutilayotgan foyda foizi</p>
                    </div>
                    <details class="group">
                        <summary class="text-xs font-bold cursor-pointer select-none" style="color:var(--text-muted)">
                            <i class="fa-solid fa-chevron-right text-[8px] mr-1 group-open:rotate-90 transition-transform"></i> Ixtiyoriy ma'lumotlar
                        </summary>
                        <div class="mt-3 space-y-3">
                            <div><label class="text-[10px] font-bold" style="color:var(--text-muted)">Mahsulot nomi <span class="text-[8px] font-normal">(ixtiyoriy)</span></label><input type="text" id="s-name" placeholder="Mahsulotning nomi (saqlash uchun)" class="input-field text-sm"><p class="text-[9px] mt-1" style="color:var(--text-muted)">Mahsulotning nomi (saqlash uchun)</p></div>
                            <div>
                                <label class="text-[10px] font-bold" style="color:var(--text-muted)">Mahsulot rasmi <span class="text-[8px] font-normal">(ixtiyoriy)</span></label>
                                <div class="mt-1 border-2 border-dashed rounded-xl p-6 text-center cursor-pointer hover:border-indigo-400/50 transition-colors" style="border-color:var(--border-color)" onclick="document.getElementById('s-image').click()">
                                    <i class="fa-regular fa-image text-2xl mb-2 block opacity-30" style="color:var(--text-muted)"></i>
                                    <p class="text-xs font-bold" style="color:var(--text-heading)">Rasm tanlash</p>
                                    <p class="text-[9px]" style="color:var(--text-muted)">Maksimal fayl hajmi: 10MB</p>
                                    <input type="file" id="s-image" accept="image/*" class="hidden">
                                </div>
                            </div>
                            <div><label class="text-[10px] font-bold" style="color:var(--text-muted)">Mahsulot havolasi <span class="text-[8px] font-normal">(ixtiyoriy)</span></label><input type="url" id="s-link" placeholder="https://uzum.uz/product/..." class="input-field text-sm"><p class="text-[9px] mt-1" style="color:var(--text-muted)">Marketplace'dagi mahsulot havolasi</p></div>
                            <div><label class="text-[10px] font-bold" style="color:var(--text-muted)">Izohlar <span class="text-[8px] font-normal">(ixtiyoriy)</span></label><textarea id="s-notes" rows="3" placeholder="Qo'shimcha ma'lumotlar" class="input-field text-sm"></textarea></div>
                        </div>
                    </details>
                    <div class="flex gap-3">
                        <button onclick="calcSotuv()" class="flex-1 py-4 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-bold shadow-lg shadow-indigo-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-calculator"></i> Hisoblash
                        </button>
                        <button onclick="clearForm('sotuv')" class="px-6 py-4 rounded-xl border font-bold transition-all hover:scale-[1.02]" style="border-color:var(--border-color);color:var(--text-muted)">Tozalash</button>
                    </div>
                </div>

                <div class="space-y-6">
                <div class="space-y-6">
                    <div id="s-results-placeholder" class="glass-card p-10 border border-dashed border-white/10 text-center">
                        <i class="fa-solid fa-tag text-4xl mb-4 block opacity-20" style="color:var(--text-muted)"></i>
                        <h4 class="text-lg font-bold mb-1" style="color:var(--text-heading)">Natijalar shu yerda ko'rinadi</h4>
                        <p class="text-sm" style="color:var(--text-muted)">Ma'lumotlarni kiriting va "Hisoblash" tugmasini bosing</p>
                    </div>
                    <div id="s-results-data" class="hidden space-y-4">
                        <div class="glass-card p-8 border border-white/5">
                            <div class="text-center mb-8">
                                <div class="text-xs uppercase tracking-widest mb-2 font-bold" style="color:var(--text-muted)">Natija</div>
                                <div id="s-price" class="text-5xl font-black text-indigo-400">0 <span class="text-xl font-normal" style="color:var(--text-muted)">so'm</span></div>
                            </div>
                            <div class="space-y-3 pt-6 border-t border-white/5">
                                <div class="flex justify-between"><span class="text-sm" style="color:var(--text-muted)">Kutilayotgan foyda</span><span id="s-profit" class="text-emerald-400 font-bold">0</span></div>
                                <div class="flex justify-between"><span class="text-sm" style="color:var(--text-muted)">ROI</span><span id="s-roi" class="text-emerald-400 font-bold">0%</span></div>
                            </div>
                        </div>
                        <div class="glass-card p-6 border border-white/5 space-y-2">
                            <h4 class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--text-muted)">Narx tarkibi</h4>
                            <div class="flex justify-between text-sm"><span style="color:var(--text-muted)">Sotuv narxi:</span><span id="s-sell-s" style="color:var(--text-heading)">0</span></div>
                            <div class="flex justify-between text-sm"><span style="color:var(--text-muted)">Fulfillment:</span><span id="s-fulfill-s" style="color:var(--text-heading)">0</span></div>
                            <div class="flex justify-between text-sm"><span style="color:var(--text-muted)">Logistika:</span><span id="s-other-s" style="color:var(--text-heading)">0</span></div>
                            <div class="flex justify-between text-sm"><span style="color:var(--text-muted)">Rentabellik:</span><span id="s-rent-s" style="color:var(--text-heading)">0</span></div>
                            <div class="flex justify-between text-sm font-bold border-t border-white/5 pt-2 mt-2"><span style="color:var(--text-muted)">Sof foyda:</span><span id="s-profit-s" class="text-emerald-400">0</span></div>
                        </div>
                    </div>
                    <div id="s-actions" class="hidden flex gap-3">
                        <button onclick="saveCalc('sotuv')" class="flex-1 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-bookmark"></i> Saqlash
                        </button>
                        <button onclick="downloadCalc('sotuv')" class="flex-1 py-3 rounded-xl border font-bold transition-all flex items-center justify-center gap-2" style="border-color:var(--border-color);color:var(--text-primary)">
                            <i class="fa-solid fa-download"></i> Yuklab olish
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hisob kitoblarim -->
        <div class="mt-16 border-t border-white/5 pt-10">
            <h2 class="text-xl font-bold mb-6 flex items-center gap-3" style="color:var(--text-heading)">
                <i class="fa-solid fa-book-bookmark text-amber-400"></i> Hisob kitoblarim
                <span id="saved-count" class="text-xs font-bold px-2 py-0.5 rounded-full bg-white/5" style="color:var(--text-muted)">0</span>
            </h2>
            <div id="saved-list" class="space-y-3">
                <div id="saved-empty" class="text-center py-10" style="color:var(--text-muted)">
                    <i class="fa-solid fa-bookmark text-4xl mb-3 block opacity-20"></i>
                    <p class="text-sm">Hali saqlangan hisob-kitob yo'q</p>
                    <p class="text-xs mt-1" style="color:var(--text-muted)">Kalkulyatorda hisob-kitob qilib "Saqlash" tugmasini bosing</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const fmt = v => new Intl.NumberFormat('uz-UZ').format(Math.round(v));
let lastCalc = {};

function switchCalcTab(tab) {
    document.querySelectorAll('.calc-panel').forEach(p => p.classList.add('hidden'));
    document.getElementById('calc-' + tab).classList.remove('hidden');
    document.querySelectorAll('.calc-tab-btn').forEach(b => {
        b.className = 'calc-tab-btn flex-1 px-5 py-3 rounded-xl text-sm font-bold transition-all text-gray-400 hover:text-white';
    });
    document.getElementById('tab-' + tab).className = 'calc-tab-btn flex-1 px-5 py-3 rounded-xl text-sm font-bold transition-all bg-indigo-600 text-white shadow-lg';
}

// MM/CM toggle
let selectedUnit = 'cm';
function setUnit(btn, unit) {
    selectedUnit = unit;
    document.querySelectorAll('.unit-btn').forEach(b => {
        b.className = 'unit-btn px-4 py-1.5 rounded-lg text-[10px] font-bold border transition-all';
        b.style.borderColor = 'var(--border-color)'; b.style.color = 'var(--text-muted)'; b.style.background = '';
    });
    btn.className = 'unit-btn px-4 py-1.5 rounded-lg text-[10px] font-bold border transition-all bg-indigo-600 text-white border-indigo-600';
    btn.style.borderColor = ''; btn.style.color = '';
    ['s-unit-l','s-unit-w','s-unit-h'].forEach(id => document.getElementById(id).textContent = unit);
}

// Maqsadli foyda dinamik label
document.addEventListener('DOMContentLoaded', () => {
    const tgt = document.getElementById('t-target');
    if (tgt) tgt.addEventListener('input', () => {
        const v = tgt.value || '0';
        tgt.closest('.rounded-2xl').querySelector('p.text-xs').textContent = v + '% Maqsadli foyda olish uchun';
    });
});

// Tannarx kalkulyatori
let selectedDelivery = 'FBO';
function setDelivery(btn, val) {
    selectedDelivery = val;
    document.querySelectorAll('.delivery-btn').forEach(b => {
        b.className = 'delivery-btn px-5 py-2 rounded-xl text-xs font-bold border transition-all';
        b.style.borderColor = 'var(--border-color)';
        b.style.color = 'var(--text-muted)';
        b.style.background = '';
    });
    btn.className = 'delivery-btn px-5 py-2 rounded-xl text-xs font-bold border transition-all bg-indigo-600 text-white border-indigo-600';
    btn.style.borderColor = '';
    btn.style.color = '';
}
function clearForm(type) {
    if (type==='tannarx') {
        ['t-price','t-cost','t-name','t-link'].forEach(id=>{const e=document.getElementById(id);if(e)e.value='';});
        const n=document.getElementById('t-notes');if(n)n.value='';
        document.getElementById('t-results-placeholder').classList.remove('hidden');
        document.getElementById('t-results-data').classList.add('hidden');
        document.getElementById('t-actions').classList.add('hidden');
        document.getElementById('t-tavsiya-box').classList.add('hidden');
    }
    if (type==='sotuv') {
        ['s-price-input','s-name','s-link'].forEach(id=>{const e=document.getElementById(id);if(e)e.value='';});
        const n=document.getElementById('s-notes');if(n)n.value='';
        ['s-dim-l','s-dim-w','s-dim-h'].forEach(id=>{const e=document.getElementById(id);if(e)e.value='0';});
        document.getElementById('s-results-placeholder').classList.remove('hidden');
        document.getElementById('s-results-data').classList.add('hidden');
        document.getElementById('s-actions').classList.add('hidden');
    }
}

function calcTannarx() {
    const price = parseFloat(document.getElementById('t-price').value) || 0;
    const cost = parseFloat(document.getElementById('t-cost').value) || 0;
    const commP = parseFloat(document.getElementById('t-comm').value) || 0;
    const kargo = parseFloat(document.getElementById('t-kargo').value) || 0;
    const fulfill = parseFloat(document.getElementById('t-fulfill').value) || 0;
    const mplog = parseFloat(document.getElementById('t-mplog').value) || 0;
    const bankP = parseFloat(document.getElementById('t-bank').value) || 0;
    const withdrawP = parseFloat(document.getElementById('t-withdraw').value) || 0;
    const defectP = parseFloat(document.getElementById('t-defect').value) || 0;
    const extraP = parseFloat(document.getElementById('t-extra').value) || 0;
    const taxP = parseFloat(document.getElementById('t-tax').value) || 0;

    const commS = price * commP / 100;
    const bankS = price * bankP / 100;
    const withdrawS = price * withdrawP / 100;
    const defectS = price * defectP / 100;
    const extraS = price * extraP / 100;
    const taxS = price * taxP / 100;
    const totalExp = cost + commS + kargo + fulfill + mplog + bankS + withdrawS + defectS + extraS + taxS;
    const profit = price - totalExp;
    const roi = cost > 0 ? (profit / cost) * 100 : 0;
    const margin = price > 0 ? (profit / price) * 100 : 0;

    document.getElementById('t-results-placeholder').classList.add('hidden');
    document.getElementById('t-results-data').classList.remove('hidden');
    document.getElementById('t-profit').innerHTML = fmt(profit) + ` <span class="text-xl font-normal" style="color:var(--text-muted)">so'm</span>`;
    document.getElementById('t-profit').className = `text-5xl font-black ${profit < 0 ? 'text-red-400' : profit > 0 ? 'text-emerald-400' : ''}`;
    document.getElementById('t-roi').textContent = roi.toFixed(1) + '%';
    document.getElementById('t-margin').textContent = margin.toFixed(1) + '%';
    document.getElementById('t-delivery-s').textContent = selectedDelivery;
    document.getElementById('t-cost-s').textContent = fmt(cost) + " so'm";
    document.getElementById('t-comm-s').textContent = fmt(commS) + " so'm";
    document.getElementById('t-fulfill-s').textContent = fmt(fulfill) + " so'm";
    document.getElementById('t-kargo-s').textContent = fmt(kargo) + " so'm";
    document.getElementById('t-mplog-s').textContent = fmt(mplog) + " so'm";
    document.getElementById('t-bank-s').textContent = fmt(bankS) + " so'm";
    document.getElementById('t-withdraw-s').textContent = fmt(withdrawS) + " so'm";
    document.getElementById('t-defect-s').textContent = fmt(defectS) + " so'm";
    document.getElementById('t-extra-s').textContent = fmt(extraS) + " so'm";
    document.getElementById('t-tax-s').textContent = fmt(taxS) + " so'm";
    document.getElementById('t-total-s').textContent = fmt(totalExp) + " so'm";
    document.getElementById('t-actions').classList.remove('hidden');

    // Maqsadli foyda - tavsiya narx
    const targetP = parseFloat(document.getElementById('t-target').value) || 0;
    const tavsiyaBox = document.getElementById('t-tavsiya-box');
    if (targetP > 0) {
        const percTotal = commP + bankP + withdrawP + defectP + extraP + taxP + targetP;
        const div = 1 - percTotal / 100;
        const tavsiyaNarx = div > 0 ? (cost + kargo + fulfill + mplog) / div : 0;
        document.getElementById('t-tavsiya-narx').textContent = fmt(tavsiyaNarx) + " so'm";
        tavsiyaBox.classList.remove('hidden');
    } else {
        tavsiyaBox.classList.add('hidden');
    }

    const name = document.getElementById('t-name').value || '';
    lastCalc = { type:'tannarx', name, delivery:selectedDelivery, price, cost, commP, kargo, fulfill, mplog, bankP, withdrawP, defectP, extraP, taxP, targetP, commS, bankS, withdrawS, defectS, extraS, taxS, totalExp, profit, roi, margin, date:new Date().toISOString() };
}

// Sotuv narx kalkulyatori
let selectedDelivery2 = 'FBO';
function setDelivery2(btn, val) {
    selectedDelivery2 = val;
    document.querySelectorAll('.delivery2-btn').forEach(b => {
        b.className = 'delivery2-btn px-5 py-2 rounded-xl text-xs font-bold border transition-all';
        b.style.borderColor = 'var(--border-color)'; b.style.color = 'var(--text-muted)'; b.style.background = '';
    });
    btn.className = 'delivery2-btn px-5 py-2 rounded-xl text-xs font-bold border transition-all bg-indigo-600 text-white border-indigo-600';
    btn.style.borderColor = ''; btn.style.color = '';
}

function calcSotuv() {
    const price = parseFloat(document.getElementById('s-price-input').value) || 0;
    const fulfill = parseFloat(document.getElementById('s-fulfill').value) || 0;
    const other = parseFloat(document.getElementById('s-other').value) || 0;
    const marginP = parseFloat(document.getElementById('s-margin').value) || 0;

    const rentS = price * marginP / 100;
    const profit = price - fulfill - other - rentS;
    const roi = (fulfill + other) > 0 ? (profit / (fulfill + other)) * 100 : 0;

    document.getElementById('s-results-placeholder').classList.add('hidden');
    document.getElementById('s-results-data').classList.remove('hidden');
    document.getElementById('s-price').innerHTML = fmt(profit) + ` <span class="text-xl font-normal" style="color:var(--text-muted)">so'm foyda</span>`;
    document.getElementById('s-profit').textContent = fmt(profit) + " so'm";
    document.getElementById('s-roi').textContent = roi.toFixed(1) + '%';
    document.getElementById('s-sell-s').textContent = fmt(price) + " so'm";
    document.getElementById('s-fulfill-s').textContent = fmt(fulfill) + " so'm";
    document.getElementById('s-other-s').textContent = fmt(other) + " so'm";
    document.getElementById('s-rent-s').textContent = marginP + '% = ' + fmt(rentS) + " so'm";
    document.getElementById('s-profit-s').textContent = fmt(profit) + " so'm";
    document.getElementById('s-actions').classList.remove('hidden');

    const name = document.getElementById('s-name').value || '';
    lastCalc = { type:'sotuv', name, delivery:selectedDelivery2, price, fulfill, other, marginP, rentS, profit, roi, date:new Date().toISOString() };
}

// Saqlash (localStorage)
function saveCalc(type) {
    if (!lastCalc.type) return;
    const saved = JSON.parse(localStorage.getItem('tiba_calcs') || '[]');
    lastCalc.id = Date.now();
    saved.unshift(lastCalc);
    if (saved.length > 50) saved.length = 50;
    localStorage.setItem('tiba_calcs', JSON.stringify(saved));
    renderSaved();
    // Toast
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 z-50 px-6 py-3 rounded-xl bg-emerald-600 text-white font-bold text-sm shadow-2xl animate-fade-in-up';
    toast.innerHTML = '<i class="fa-solid fa-check mr-2"></i>Hisob-kitob saqlandi!';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Yuklab olish (txt)
function downloadCalc(type) {
    if (!lastCalc.type) return;
    const c = lastCalc;
    const label = c.type === 'tannarx' ? 'Unit Iqtisod Kalkulyatori' : 'Xarid Narx Kalkulyatori';
    let text = `${label} — Tiba AI\n${'='.repeat(40)}\nSana: ${new Date(c.date).toLocaleString('uz-UZ')}\n`;
    if (c.name) text += `Mahsulot: ${c.name}\n`;
    text += `Yetkazish: ${c.delivery}\n\n`;
    if (c.type === 'tannarx') {
        text += `Sotuv narxi:     ${fmt(c.price)} so'm\nXarid narxi:     ${fmt(c.cost)} so'm\nKomissiya:       ${c.commP}% = ${fmt(c.commS)} so'm\nKargo:           ${fmt(c.kargo)} so'm\nFulfillment:     ${fmt(c.fulfill)} so'm\nMP logistika:    ${fmt(c.mplog)} so'm\nBank foizi:      ${c.bankP}% = ${fmt(c.bankS)} so'm\nPul yechish:     ${c.withdrawP}% = ${fmt(c.withdrawS)} so'm\nBrak:            ${c.defectP}% = ${fmt(c.defectS)} so'm\nQo'shimcha:      ${c.extraP}% = ${fmt(c.extraS)} so'm\nSoliq:           ${c.taxP}% = ${fmt(c.taxS)} so'm\n${'─'.repeat(30)}\nJami xarajat:    ${fmt(c.totalExp)} so'm\nSof foyda:       ${fmt(c.profit)} so'm\nROI:             ${c.roi.toFixed(1)}%\nMarja:           ${c.margin.toFixed(1)}%`;
        if (c.targetP > 0) {
            const pT = c.commP+c.bankP+c.withdrawP+c.defectP+c.extraP+c.taxP+c.targetP;
            const d = 1-pT/100;
            const tv = d>0?(c.cost+c.kargo+c.fulfill+c.mplog)/d:0;
            text += `\nMaqsadli foyda:  ${c.targetP}%\nTavsiya narx:    ${fmt(tv)} so'm`;
        }
    } else {
        text += `Sotuv narxi:     ${fmt(c.price)} so'm\nFulfillment:     ${fmt(c.fulfill)} so'm\nLogistika:       ${fmt(c.other)} so'm\nRentabellik:     ${c.marginP}% = ${fmt(c.rentS)} so'm\n${'─'.repeat(30)}\nSof foyda:       ${fmt(c.profit)} so'm\nROI:             ${c.roi.toFixed(1)}%`;
    }
    const blob = new Blob([text], { type: 'text/plain;charset=utf-8' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = `hisob_${c.type}_${Date.now()}.txt`;
    a.click();
}

// Saqlangan hisob-kitoblarni ko'rsatish
function renderSaved() {
    const saved = JSON.parse(localStorage.getItem('tiba_calcs') || '[]');
    const list = document.getElementById('saved-list');
    const empty = document.getElementById('saved-empty');
    document.getElementById('saved-count').textContent = saved.length;
    if (!saved.length) { empty.classList.remove('hidden'); list.innerHTML = ''; list.appendChild(empty); return; }
    empty.classList.add('hidden');
    list.innerHTML = saved.map(c => {
        const label = c.type === 'tannarx' ? 'Unit iqtisod' : 'Xarid narx';
        const color = c.type === 'tannarx' ? 'violet' : 'indigo';
        const profitColor = (c.profit||0) >= 0 ? 'text-emerald-400' : 'text-red-400';
        const dateStr = new Date(c.date).toLocaleString('uz-UZ', { day:'2-digit', month:'short', hour:'2-digit', minute:'2-digit' });
        const nameStr = c.name ? `<span class="text-xs truncate max-w-[120px] inline-block align-middle" style="color:var(--text-heading)">${c.name}</span> · ` : '';
        const roiVal = typeof c.roi === 'number' ? c.roi.toFixed(0) : '0';
        return `
        <div class="glass-card p-4 flex items-center gap-4 border border-white/5 hover:border-white/10 transition-all group">
            <div class="w-10 h-10 rounded-xl bg-${color}-500/10 flex items-center justify-center text-${color}-400 flex-shrink-0">
                <i class="fa-solid fa-${c.type === 'tannarx' ? 'tag' : 'calculator'}"></i>
            </div>
            <div class="flex-grow min-w-0">
                <div class="flex items-center gap-2 mb-0.5">
                    <span class="text-xs font-bold px-1.5 py-0.5 rounded bg-${color}-500/10 text-${color}-400 border border-${color}-500/20">${label}</span>
                    ${nameStr}
                    <span class="text-[10px]" style="color:var(--text-muted)">${dateStr}</span>
                </div>
                <div class="flex gap-4 text-sm">
                    <span style="color:var(--text-muted)">Narx: <b style="color:var(--text-heading)">${fmt(c.price||0)}</b></span>
                    <span style="color:var(--text-muted)">Foyda: <b class="${profitColor}">${fmt(c.profit||0)}</b></span>
                    <span style="color:var(--text-muted)">ROI: <b class="text-blue-400">${roiVal}%</b></span>
                </div>
            </div>
            <button onclick="deleteCalc(${c.id})" class="flex-shrink-0 p-2 text-gray-600 hover:text-red-400 opacity-0 group-hover:opacity-100 transition-all" title="O'chirish">
                <i class="fa-solid fa-trash-can text-xs"></i>
            </button>
        </div>`;
    }).join('');
}

function deleteCalc(id) {
    let saved = JSON.parse(localStorage.getItem('tiba_calcs') || '[]');
    saved = saved.filter(c => c.id !== id);
    localStorage.setItem('tiba_calcs', JSON.stringify(saved));
    renderSaved();
}

document.addEventListener('DOMContentLoaded', renderSaved);
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
