<?php $pageTitle = 'Infografika Paketi – Tiba AI'; ?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="min-h-screen py-6 sm:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <a href="/create" class="inline-flex items-center gap-2 text-gray-500 hover:text-white transition-colors text-sm font-medium group">
                <i class="fa-solid fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                Orqaga
            </a>
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <i class="fa-solid fa-coins"></i>
                <span>1 ta paket = 5 tanga</span>
            </div>
        </div>

        <!-- Page Title -->
        <div class="text-center mb-10">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3">
                5 ta slaydni <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">birda yarating</span>
            </h1>
            <p class="text-gray-500 max-w-lg mx-auto text-sm">
                Mahsulotingiz uchun barcha kerakli infografikalarni bir marta yaratib oling.
            </p>
        </div>

        <!-- Package Steps Bar -->
        <div class="rounded-2xl bg-white/[0.03] border border-white/[0.06] p-4 mb-6 hidden lg:block">
            <div class="flex items-center justify-around gap-4">
                <div class="flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-lg bg-indigo-500/15 text-indigo-400 text-[10px] font-bold flex items-center justify-center border border-indigo-500/15">1</span>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-gray-300 tracking-wider">HERO POSTER</span>
                        <span class="text-[9px] text-gray-600">Asosiy slayd</span>
                    </div>
                </div>
                <div class="w-px h-8 bg-white/[0.04]"></div>
                <div class="flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-lg bg-indigo-500/15 text-indigo-400 text-[10px] font-bold flex items-center justify-center border border-indigo-500/15">2</span>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-gray-300 tracking-wider">AFZALLIKLAR</span>
                        <span class="text-[9px] text-gray-600">Ustun jihatlari</span>
                    </div>
                </div>
                <div class="w-px h-8 bg-white/[0.04]"></div>
                <div class="flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-lg bg-indigo-500/15 text-indigo-400 text-[10px] font-bold flex items-center justify-center border border-indigo-500/15">3</span>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-gray-300 tracking-wider">DETALLAR</span>
                        <span class="text-[9px] text-gray-600">Materiallar</span>
                    </div>
                </div>
                <div class="w-px h-8 bg-white/[0.04]"></div>
                <div class="flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-lg bg-indigo-500/15 text-indigo-400 text-[10px] font-bold flex items-center justify-center border border-indigo-500/15">4</span>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-gray-300 tracking-wider">LIFESTYLE</span>
                        <span class="text-[9px] text-gray-600">Hayotda ishlatilishi</span>
                    </div>
                </div>
                <div class="w-px h-8 bg-white/[0.04]"></div>
                <div class="flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-lg bg-indigo-500/15 text-indigo-400 text-[10px] font-bold flex items-center justify-center border border-indigo-500/15">5</span>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-gray-300 tracking-wider">DINAMIK CTA</span>
                        <span class="text-[9px] text-gray-600">Sotib olishga undash</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- ============ LEFT PANEL — FORM ============ -->
            <div class="lg:col-span-4 space-y-4">

                <!-- CARD 1: Mahsulot rasmlari -->
                <div class="rounded-2xl bg-white/[0.03] border border-white/[0.06] p-5 hover:border-white/10 transition-colors">
                    <label class="flex items-center gap-2.5 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center">
                            <i class="fa-solid fa-images text-indigo-400 text-sm"></i>
                        </div>
                        <span class="text-sm font-semibold text-white">Mahsulot rasmlari <span class="text-gray-600 font-normal text-xs">(maks. 5)</span></span>
                    </label>
                    <div class="grid grid-cols-3 gap-2 mb-3">
                        <div id="pkg-previews-container" class="col-span-2 grid grid-cols-2 gap-2"></div>
                        <div class="flex flex-col gap-2">
                            <label id="pkg-add-image-label" class="flex flex-col items-center justify-center aspect-square border-2 border-dashed border-white/[0.06] rounded-2xl bg-white/[0.02] hover:bg-indigo-500/[0.05] hover:border-indigo-500/30 transition-all duration-300 cursor-pointer group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/0 to-purple-500/0 group-hover:from-indigo-500/5 group-hover:to-purple-500/5 transition-all duration-500"></div>
                                <div class="relative z-10 flex flex-col items-center">
                                    <i class="fa-solid fa-cloud-arrow-up text-lg text-gray-500 group-hover:text-indigo-400 transition-colors"></i>
                                    <span class="text-[9px] font-bold text-gray-500 uppercase mt-1 group-hover:text-indigo-400">Yuklash</span>
                                </div>
                                <input type="file" class="hidden" accept="image/*" multiple id="pkg-image-input" />
                            </label>
                            <button type="button" onclick="toggleUrlInput()" class="flex items-center justify-center gap-1.5 text-[9px] font-bold text-gray-500 hover:text-indigo-400 uppercase tracking-tighter bg-white/[0.03] py-1.5 rounded-lg border border-white/[0.06] transition-all">
                                <i class="fa-solid fa-link text-[10px]"></i> Havola
                            </button>
                        </div>
                    </div>

                    <!-- URL Input -->
                    <div id="pkg-url-area" class="hidden space-y-2 mb-4">
                        <div class="relative">
                            <input type="url" id="pkg-url-input" placeholder="https://..." class="input-field pl-8 h-9 text-xs" />
                            <div class="absolute left-2.5 top-1/2 -translate-y-1/2 text-[10px] text-gray-500"><i class="fa-solid fa-link"></i></div>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" onclick="addUrlImage()" class="flex-1 bg-indigo-500/15 text-indigo-400 text-[10px] font-bold py-1.5 rounded-lg border border-indigo-500/15 hover:bg-indigo-500/25 transition-all flex items-center justify-center gap-2">
                                <i class="fa-solid fa-plus text-[9px]"></i> QO'SHISH
                            </button>
                            <button type="button" onclick="toggleUrlInput()" class="px-3 text-gray-500 text-[10px] font-bold py-1.5 hover:text-gray-300">BEKOR</button>
                        </div>
                    </div>
                    <p id="pkg-image-hint" class="text-[10px] text-gray-600">Kamida 1 ta rasm yuklang yoki havola bering.</p>
                </div>

                <!-- CARD 2: Mahsulot nomi + Xususiyatlar -->
                <div class="rounded-2xl bg-white/[0.03] border border-white/[0.06] p-5 space-y-4 hover:border-white/10 transition-colors">
                    <div>
                        <label class="flex items-center gap-2.5 mb-2.5">
                            <div class="w-8 h-8 rounded-lg bg-purple-500/10 flex items-center justify-center">
                                <i class="fa-solid fa-tag text-purple-400 text-sm"></i>
                            </div>
                            <span class="text-sm font-semibold text-white">Mahsulot nomi <span class="text-gray-600 font-normal text-xs">(ixtiyoriy)</span></span>
                        </label>
                        <input type="text" id="pkg-product-name" class="input-field text-sm" placeholder="Masalan: Dyson V15 Vacuum" />
                    </div>

                    <div class="border-t border-white/[0.04]"></div>

                    <div>
                        <label class="flex items-center gap-2.5 mb-2.5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                                <i class="fa-solid fa-list-check text-emerald-400 text-sm"></i>
                            </div>
                            <span class="text-sm font-semibold text-white">Asosiy xususiyatlar</span>
                        </label>
                        <div class="flex gap-1.5 mb-3 p-1 bg-white/[0.03] rounded-lg w-fit border border-white/5">
                            <button type="button" id="pkg-features-ai-btn" onclick="switchPkgFeaturesMode('ai')" class="px-3 py-1.5 rounded-md transition-all text-xs font-semibold bg-indigo-500/15 text-indigo-400 flex items-center gap-1.5">
                                <i class="fa-solid fa-wand-magic-sparkles text-[10px]"></i>
                                AI tanlovi
                            </button>
                            <button type="button" id="pkg-features-manual-btn" onclick="switchPkgFeaturesMode('manual')" class="px-3 py-1.5 rounded-md transition-all text-xs font-semibold text-gray-500 hover:text-gray-300 flex items-center gap-1.5">
                                <i class="fa-solid fa-pencil text-[10px]"></i>
                                Qo'lda kiritish
                            </button>
                        </div>
                        <div id="pkg-features-ai-hint" class="flex items-center gap-2.5 p-3 rounded-xl bg-indigo-500/[0.04] border border-indigo-500/10">
                            <i class="fa-solid fa-microchip text-indigo-400/50 text-base"></i>
                            <p class="text-xs text-gray-400">AI mahsulot rasmi va nomiga qarab xususiyatlarni <span class="text-indigo-400 font-medium">avtomatik tanlaydi</span></p>
                        </div>
                        <div id="pkg-features-manual-area" class="hidden">
                            <textarea id="pkg-features" class="input-field h-28 resize-none text-sm" placeholder="Suv o'tmaydigan IP68&#10;Batareya 7 kun&#10;Sog'liq monitoring&#10;GPS tracking"></textarea>
                        </div>
                    </div>
                </div>

                <!-- CARD 3: Stil + Til -->
                <div class="rounded-2xl bg-white/[0.03] border border-white/[0.06] p-5 space-y-5 hover:border-white/10 transition-colors">
                    <div>
                        <label class="flex items-center gap-2.5 mb-3">
                            <div class="w-8 h-8 rounded-lg bg-pink-500/10 flex items-center justify-center">
                                <i class="fa-solid fa-palette text-pink-400 text-sm"></i>
                            </div>
                            <span class="text-sm font-semibold text-white">Infografika stili</span>
                        </label>
                        <div class="grid grid-cols-3 gap-1.5">
                            <button type="button" data-style="marketplace" class="pkg-style-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-indigo-500/50 bg-indigo-500/10 text-white flex flex-col items-center gap-1">
                                <i class="fa-solid fa-store text-emerald-400 text-sm"></i>
                                <span class="text-[9px] font-bold uppercase tracking-wider">Marketpleys</span>
                            </button>
                            <button type="button" data-style="instagram" class="pkg-style-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04] flex flex-col items-center gap-1">
                                <i class="fa-brands fa-instagram text-pink-400 text-sm"></i>
                                <span class="text-[9px] font-bold uppercase tracking-wider">Instagram</span>
                            </button>
                            <button type="button" data-style="minimal" class="pkg-style-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04] flex flex-col items-center gap-1">
                                <i class="fa-regular fa-square text-gray-300 text-sm"></i>
                                <span class="text-[9px] font-bold uppercase tracking-wider">Minimal</span>
                            </button>
                        </div>
                    </div>

                    <div class="border-t border-white/[0.04]"></div>

                    <!-- Mahsulot Kategoriyasi -->
                    <div>
                        <label class="flex items-center gap-2.5 mb-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-500/10 flex items-center justify-center">
                                <i class="fa-solid fa-boxes-stacked text-amber-400 text-sm"></i>
                            </div>
                            <span class="text-sm font-semibold text-white">Mahsulot kategoriyasi</span>
                        </label>
                        <div class="grid grid-cols-4 gap-1.5" id="pkg-category-container">
                            <button type="button" data-category="electronics" class="pkg-category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-mobile-screen-button text-sky-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Elektronika</div>
                            </button>
                            <button type="button" data-category="clothing" class="pkg-category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-shirt text-violet-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Kiyim</div>
                            </button>
                            <button type="button" data-category="beauty" class="pkg-category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-spray-can-sparkles text-pink-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Go'zallik</div>
                            </button>
                            <button type="button" data-category="home" class="pkg-category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-house-chimney text-amber-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Uy-ro'zg'or</div>
                            </button>
                            <button type="button" data-category="food" class="pkg-category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-apple-whole text-green-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Oziq-ovqat</div>
                            </button>
                            <button type="button" data-category="kids" class="pkg-category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-children text-yellow-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Bolalar</div>
                            </button>
                            <button type="button" data-category="sport" class="pkg-category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-dumbbell text-orange-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Sport</div>
                            </button>
                            <button type="button" data-category="auto" class="pkg-category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-car text-red-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Avto</div>
                            </button>
                            <button type="button" data-category="health" class="pkg-category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-capsules text-emerald-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Sog'liq</div>
                            </button>
                            <button type="button" data-category="tools" class="pkg-category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-screwdriver-wrench text-slate-300 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Asboblar</div>
                            </button>
                            <button type="button" data-category="accessories" class="pkg-category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-bag-shopping text-rose-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Aksesuar</div>
                            </button>
                            <button type="button" data-category="pet" class="pkg-category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-paw text-teal-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Hayvonlar</div>
                            </button>
                        </div>
                    </div>

                    <div class="border-t border-white/[0.04]"></div>

                    <div>
                        <label class="flex items-center gap-2.5 mb-3">
                            <div class="w-8 h-8 rounded-lg bg-cyan-500/10 flex items-center justify-center">
                                <i class="fa-solid fa-language text-cyan-400 text-sm"></i>
                            </div>
                            <span class="text-sm font-semibold text-white">Slaydlar tili</span>
                        </label>
                        <div class="grid grid-cols-3 gap-1.5">
                            <button type="button" data-lang="uz" class="pkg-lang-btn py-2 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15">
                                <div class="text-[10px] font-bold">UZB</div>
                            </button>
                            <button type="button" data-lang="ru" class="pkg-lang-btn py-2 rounded-xl border text-center transition-all duration-200 border-indigo-500/50 bg-indigo-500/10 text-white">
                                <div class="text-[10px] font-bold">RUS</div>
                            </button>
                            <button type="button" data-lang="en" class="pkg-lang-btn py-2 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15">
                                <div class="text-[10px] font-bold">ENG</div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button id="pkg-generate-btn" disabled class="w-full h-14 rounded-2xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-500 hover:via-purple-500 hover:to-pink-500 text-white font-bold text-base flex items-center justify-center gap-3 transition-all duration-300 disabled:opacity-40 disabled:cursor-not-allowed shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 hover:scale-[1.01] active:scale-[0.99]">
                    <i class="fa-solid fa-wand-magic-sparkles text-lg"></i>
                    Paket yaratish
                </button>

                <p id="pkg-error" class="text-red-400 text-xs text-center hidden"></p>
            </div>

            <!-- ============ RIGHT PANEL — OUTPUT ============ -->
            <div class="lg:col-span-8 space-y-4">
                <!-- Progress -->
                <div id="pkg-progress" class="rounded-2xl bg-white/[0.03] border border-white/[0.06] p-5 hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-bold text-white">UMUMIY JARAYON</span>
                        <span id="pkg-progress-pct" class="text-sm font-bold text-indigo-400">0%</span>
                    </div>
                    <div class="h-1.5 w-full bg-white/5 rounded-full overflow-hidden">
                        <div id="pkg-progress-bar" class="h-full bg-gradient-to-r from-indigo-600 via-purple-500 to-pink-500 transition-all duration-500 rounded-full" style="width: 0%"></div>
                    </div>
                    <p class="text-[11px] text-gray-600 mt-2 text-center">
                        Professional slaydlar tayyorlanmoqda. AI xato qilishi mumkin.
                    </p>
                </div>

                <!-- Download All -->
                <div id="pkg-download-all" class="flex justify-between items-center hidden">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-check-double text-emerald-500 text-sm"></i> Sizning paketingiz:
                    </h3>
                    <button id="pkg-download-all-btn" class="px-5 h-10 rounded-xl bg-white/5 border border-white/10 text-sm font-semibold text-white hover:bg-white/10 transition-all flex items-center gap-2 hover:scale-105 active:scale-95">
                        <i class="fa-solid fa-download text-xs"></i> Barchasini yuklash
                    </button>
                </div>

                <!-- 5 Slide Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3" id="pkg-slides-grid">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                    <div class="relative aspect-[3/4] rounded-2xl bg-white/[0.02] border border-white/[0.06] overflow-hidden group" id="pkg-slide-<?= $i ?>">
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-700 gap-2 slide-empty">
                            <span class="text-4xl opacity-10 italic font-black"><?= $i + 1 ?></span>
                            <i class="fa-solid fa-hourglass-start text-lg opacity-15"></i>
                            <span class="text-[9px] uppercase font-bold tracking-[0.2em] opacity-25">Kutilmoqda</span>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>

                <!-- Empty State -->
                <div id="pkg-empty-state" class="text-center py-12">
                    <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-indigo-500/10 to-purple-500/10 border border-indigo-500/10 flex items-center justify-center mx-auto mb-5">
                        <i class="fa-solid fa-box-open text-3xl text-indigo-400/30"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-300 mb-1.5">Ma'lumotlarni to'ldiring</h3>
                    <p class="text-xs text-gray-600 max-w-[220px] mx-auto">
                        Rasmni yuklang va "Paket yaratish" tugmasini bosing.
                    </p>
                    <p class="text-[11px] text-gray-600 mt-3">AI xato qilishi mumkin. Natijani qayta tekshiruvdan o'tkazing.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lightbox Modal -->
<div id="pkg-lightbox-modal" class="fixed inset-0 z-[200] hidden flex items-center justify-center p-4 sm:p-8">
    <div class="absolute inset-0 bg-black/95 backdrop-blur-md" onclick="closePkgLightbox()"></div>
    <div class="relative max-w-4xl max-h-[90vh] w-full flex flex-col items-center justify-center">
        <button onclick="closePkgLightbox()" class="absolute top-0 right-0 m-4 p-3 bg-white/10 hover:bg-red-500 text-white rounded-full transition-all z-50 shadow-xl">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <img id="pkg-lightbox-img" src="" alt="Slayd" class="max-w-full max-h-[85vh] rounded-xl shadow-2xl object-contain border border-white/10" />
        <button id="pkg-lightbox-download" class="absolute bottom-4 sm:bottom-8 px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-2xl font-bold flex items-center gap-2 shadow-2xl transition-all z-50 hover:scale-105 active:scale-95">
            <i class="fa-solid fa-download"></i>
            Yuklab olish
        </button>
    </div>
</div>

<script>
(function(){
    let images = [];
    let results = [null, null, null, null, null];
    let selectedStyle = 'marketplace';
    let selectedLang = 'ru';
    let selectedCategory = '';
    let pkgFeaturesMode = 'ai';

    const activeClasses = ['border-indigo-500/50', 'bg-indigo-500/10', 'text-white'];
    const inactiveClasses = ['border-white/[0.06]', 'bg-white/[0.02]', 'text-gray-400'];

    const productName = document.getElementById('pkg-product-name');
    const features = document.getElementById('pkg-features');
    const generateBtn = document.getElementById('pkg-generate-btn');
    const imageInput = document.getElementById('pkg-image-input');
    const urlInput = document.getElementById('pkg-url-input');
    const urlArea = document.getElementById('pkg-url-area');
    const previewsContainer = document.getElementById('pkg-previews-container');
    const addImageLabel = document.getElementById('pkg-add-image-label');
    const imageHint = document.getElementById('pkg-image-hint');
    const errorEl = document.getElementById('pkg-error');
    const progressEl = document.getElementById('pkg-progress');
    const progressPct = document.getElementById('pkg-progress-pct');
    const progressBar = document.getElementById('pkg-progress-bar');
    const downloadAllSection = document.getElementById('pkg-download-all');
    const emptyState = document.getElementById('pkg-empty-state');

    window.toggleUrlInput = function() {
        urlArea.classList.toggle('hidden');
        if (!urlArea.classList.contains('hidden')) urlInput.focus();
    };

    // Features mode toggle (AI / Qo'lda)
    window.switchPkgFeaturesMode = function(mode) {
        pkgFeaturesMode = mode;
        const aiBtn = document.getElementById('pkg-features-ai-btn');
        const manualBtn = document.getElementById('pkg-features-manual-btn');
        const aiHint = document.getElementById('pkg-features-ai-hint');
        const manualArea = document.getElementById('pkg-features-manual-area');
        if (mode === 'ai') {
            aiBtn.className = 'px-3 py-1.5 rounded-md transition-all text-xs font-semibold bg-indigo-500/15 text-indigo-400 flex items-center gap-1.5';
            manualBtn.className = 'px-3 py-1.5 rounded-md transition-all text-xs font-semibold text-gray-500 hover:text-gray-300 flex items-center gap-1.5';
            aiHint.classList.remove('hidden');
            manualArea.classList.add('hidden');
        } else {
            manualBtn.className = 'px-3 py-1.5 rounded-md transition-all text-xs font-semibold bg-indigo-500/15 text-indigo-400 flex items-center gap-1.5';
            aiBtn.className = 'px-3 py-1.5 rounded-md transition-all text-xs font-semibold text-gray-500 hover:text-gray-300 flex items-center gap-1.5';
            aiHint.classList.add('hidden');
            manualArea.classList.remove('hidden');
            document.getElementById('pkg-features').focus();
        }
    };

    // Style selector
    document.querySelectorAll('.pkg-style-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.pkg-style-btn').forEach(b => {
                b.classList.remove(...activeClasses);
                b.classList.add(...inactiveClasses);
            });
            this.classList.remove(...inactiveClasses);
            this.classList.add(...activeClasses);
            selectedStyle = this.dataset.style;
        });
    });

    // Lang selector
    document.querySelectorAll('.pkg-lang-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.pkg-lang-btn').forEach(b => {
                b.classList.remove(...activeClasses);
                b.classList.add(...inactiveClasses);
            });
            this.classList.remove(...inactiveClasses);
            this.classList.add(...activeClasses);
            selectedLang = this.dataset.lang;
        });
    });

    // Category selector
    document.querySelectorAll('.pkg-category-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.pkg-category-btn').forEach(b => {
                b.classList.remove(...activeClasses);
                b.classList.add(...inactiveClasses);
            });
            this.classList.remove(...inactiveClasses);
            this.classList.add(...activeClasses);
            selectedCategory = this.dataset.category;
        });
    });

    window.addUrlImage = function() {
        const url = urlInput.value.trim();
        if (!url || (!url.startsWith('http://') && !url.startsWith('https://'))) {
            showError("To'g'ri havola kiring.");
            return;
        }
        if (images.length >= 5) {
            showError("Maksimal 5 ta rasm ruxsat etilgan.");
            return;
        }
        images.push(url);
        urlInput.value = '';
        toggleUrlInput();
        renderImages();
        checkForm();
        hideError();
    };

    function checkForm() {
        generateBtn.disabled = images.length === 0;
    }
    productName.addEventListener('input', checkForm);
    features.addEventListener('input', checkForm);

    // Image upload
    imageInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        if (files.length + images.length > 5) {
            showError("Maksimal 5 ta rasm yuklash mumkin.");
            return;
        }
        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = function() {
                if (images.length < 5) {
                    images.push(reader.result);
                    renderImages();
                    checkForm();
                }
            };
            reader.readAsDataURL(file);
        });
    });

    function renderImages() {
        previewsContainer.innerHTML = '';
        images.forEach((img, idx) => {
            const div = document.createElement('div');
            div.className = 'relative group pkg-img-preview';
            div.innerHTML = `
                <img src="${img}" class="w-full h-auto rounded-xl bg-black/20 border border-white/[0.06]" />
                <button data-idx="${idx}" class="pkg-remove-img absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-[8px] text-white opacity-0 group-hover:opacity-100 transition-all hover:scale-110 shadow-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            `;
            previewsContainer.appendChild(div);
        });

        addImageLabel.classList.toggle('hidden', images.length >= 5);
        imageHint.classList.toggle('hidden', images.length > 0);

        document.querySelectorAll('.pkg-remove-img').forEach(btn => {
            btn.addEventListener('click', function() {
                images.splice(parseInt(this.dataset.idx), 1);
                renderImages();
                checkForm();
            });
        });
    }

    function showError(msg) {
        errorEl.textContent = msg;
        errorEl.classList.remove('hidden');
    }
    function hideError() { errorEl.classList.add('hidden'); }

    // Generate
    generateBtn.addEventListener('click', function() {
        TibaAuth.requireAuth(async function() {
        if (images.length === 0) {
            showError("Kamida 1 ta rasm yuklang.");
            return;
        }

        hideError();
        generateBtn.disabled = true;
        results = [null, null, null, null, null];
        progressEl.classList.remove('hidden');
        emptyState.classList.add('hidden');
        downloadAllSection.classList.add('hidden');

        // Reset slides
        for (let i = 0; i < 5; i++) {
            const slide = document.getElementById('pkg-slide-' + i);
            slide.innerHTML = `<div class="w-full h-full flex flex-col items-center justify-center text-gray-700 gap-2 slide-empty">
                <span class="text-4xl opacity-10 italic font-black">${i + 1}</span>
                <span class="text-[9px] uppercase font-bold tracking-widest opacity-25">Kutilmoqda</span>
            </div>`;
        }

        for (let i = 1; i <= 5; i++) {
            generateBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin text-lg"></i> Slayd ${i}/5 yaratilmoqda...`;
            const pct = Math.round((i / 5) * 100);

            const currentSlide = document.getElementById('pkg-slide-' + (i - 1));
            currentSlide.innerHTML = `<div class="w-full h-full flex flex-col items-center justify-center text-indigo-400/50 gap-3">
                <i class="fa-solid fa-circle-notch fa-spin text-2xl"></i>
                <span class="text-[9px] uppercase font-bold tracking-[0.2em]">YARATILMOQDA...</span>
            </div>`;

            if (i > 1) await new Promise(r => setTimeout(r, 3000));
            const currentImage = images[i - 1] || images[0];

            try {
                const response = await fetch('/api/infografika-paketi.php', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-User-Token': TibaAuth.getToken()
                    },
                    body: JSON.stringify({
                        productName: productName.value,
                        features: pkgFeaturesMode === 'manual' ? features.value : '',
                        style: selectedStyle,
                        language: selectedLang,
                        productImage: currentImage,
                        slideIndex: i,
                        category: selectedCategory
                    })
                });

                const text = await response.text();
                let data;
                try { data = JSON.parse(text); } catch(e) { throw new Error("Serverdan noto'g'ri javob keldi."); }

                if (!response.ok) {
                    if (data.auth_required) { TibaAuth.showModal(); throw new Error('Avval tizimga kiring'); }
                    if (data.insufficient_balance) { showNoBalance(data.cost, data.balance); throw new Error('__nobalance__'); }
                    throw new Error(data.error || `Slayd ${i} yaratishda xatolik (${response.status})`);
                }

                if (data.balance !== undefined) TibaAuth.updateBalance(data.balance);
                results[i - 1] = data.imageUrl;

                currentSlide.innerHTML = `
                    <div class="relative w-full h-full cursor-pointer" onclick="openPkgLightbox('${data.imageUrl}', 'Slayd ${i}')">
                        <img src="${data.imageUrl}" class="w-full h-full object-cover" />
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                            <button class="w-10 h-10 bg-white text-black rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors shadow-xl">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <a href="${data.imageUrl}" download="slide-${i}.png" target="_blank" onclick="event.stopPropagation()" class="w-10 h-10 bg-indigo-500 text-white rounded-full flex items-center justify-center hover:bg-indigo-600 transition-colors shadow-xl">
                                <i class="fa-solid fa-download"></i>
                            </a>
                        </div>
                        <span class="absolute bottom-2 left-2 px-2 py-0.5 rounded-lg bg-black/60 backdrop-blur-sm text-[9px] font-bold text-white uppercase tracking-widest border border-white/5 flex items-center gap-1.5 pointer-events-none">
                            <i class="fa-solid fa-layer-group text-[8px] opacity-70"></i> Slayd ${i}
                        </span>
                    </div>
                `;

                progressPct.textContent = pct + '%';
                progressBar.style.width = pct + '%';
            } catch (err) {
                if (err.message !== '__nobalance__') showError(err.message);
                generateBtn.disabled = false;
                generateBtn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles text-lg"></i> Paket yaratish';
                progressEl.classList.add('hidden');
                return;
            }
        }

        generateBtn.disabled = false;
        generateBtn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles text-lg"></i> Paket yaratish';
        progressEl.classList.add('hidden');
        downloadAllSection.classList.remove('hidden');
        checkForm();
        });
    });

    // Download all
    document.getElementById('pkg-download-all-btn').addEventListener('click', async function() {
        const name = productName.value.replace(/\s+/g, '-').toLowerCase() || 'paket';
        for (let i = 0; i < results.length; i++) {
            if (results[i]) {
                const a = document.createElement('a');
                a.href = results[i];
                a.download = `${name}-slide-${i + 1}.png`;
                a.target = '_blank';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                await new Promise(r => setTimeout(r, 500));
            }
        }
    });

    // Lightbox Logic
    const lightboxModal = document.getElementById('pkg-lightbox-modal');
    const lightboxImg = document.getElementById('pkg-lightbox-img');
    const lightboxDownload = document.getElementById('pkg-lightbox-download');
    let currentLightboxUrl = '';

    window.openPkgLightbox = function(url, title) {
        currentLightboxUrl = url;
        lightboxImg.src = url;
        lightboxImg.alt = title;
        lightboxModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    window.closePkgLightbox = function() {
        lightboxModal.classList.add('hidden');
        document.body.style.overflow = '';
    };

    lightboxModal.addEventListener('click', (e) => {
        if (e.target === lightboxModal) closePkgLightbox();
    });

    lightboxDownload.addEventListener('click', () => {
        if (!currentLightboxUrl) return;
        const a = document.createElement('a');
        a.href = currentLightboxUrl;
        a.download = 'tiba-ai-slide-' + Date.now() + '.png';
        a.target = '_blank';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    });
})();
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
