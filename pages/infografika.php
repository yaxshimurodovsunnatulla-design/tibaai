<?php 
$pageTitle = 'Professional Infografika – Tiba AI';
$pageDescription = 'AI yordamida marketplace mahsulotlari uchun yuqori sifatli infografika yaratish bo\'limi.';
?>
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
                <span>1 ta infografika = 5 tanga</span>
            </div>
        </div>

        <!-- Page Title -->
        <div class="text-center mb-10">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3">
                Marketplace <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">Infografika</span>
            </h1>
            <p class="text-gray-500 max-w-lg mx-auto text-sm">
                Mahsulot rasmini yuklang, AI sizga professional sotuvchi infografikasini yaratsin.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- ============ LEFT PANEL — FORM ============ -->
            <div class="lg:col-span-5 space-y-4">
                <form id="infografika-form" class="space-y-4">

                <!-- CARD 1: Mahsulot rasmi -->
                <div class="rounded-2xl bg-white/[0.03] border border-white/[0.06] p-5 hover:border-white/10 transition-colors">
                    <label class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center">
                                <i class="fa-solid fa-camera text-indigo-400 text-sm"></i>
                            </div>
                            <span class="text-sm font-semibold text-white">Mahsulot rasmi</span>
                        </div>
                        <div class="flex p-0.5 bg-white/5 rounded-lg border border-white/5 text-[9px]">
                            <button type="button" onclick="switchImageMethod('file')" id="method-file-btn" class="px-2.5 py-1 rounded-md transition-all bg-indigo-500/20 text-indigo-400 font-semibold">FAYL</button>
                            <button type="button" onclick="switchImageMethod('url')" id="method-url-btn" class="px-2.5 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300 font-semibold">HAVOLA</button>
                        </div>
                    </label>

                    <!-- File Upload -->
                    <div id="method-file-area">
                        <label for="infografika-file-input" id="img-upload-label" class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-white/[0.06] rounded-2xl bg-white/[0.02] hover:bg-indigo-500/[0.05] hover:border-indigo-500/30 transition-all duration-300 cursor-pointer group relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/0 to-purple-500/0 group-hover:from-indigo-500/5 group-hover:to-purple-500/5 transition-all duration-500"></div>
                            <div class="relative z-10 flex flex-col items-center">
                                <div class="w-11 h-11 rounded-xl bg-indigo-500/10 flex items-center justify-center mb-2 group-hover:bg-indigo-500/20 transition-colors">
                                    <i class="fa-solid fa-cloud-arrow-up text-indigo-400 text-lg"></i>
                                </div>
                                <p class="text-sm font-semibold text-gray-300 group-hover:text-white transition-colors">Rasmni yuklash</p>
                                <p class="text-[10px] text-gray-600 mt-0.5">JPG, PNG • Maks. 10 MB</p>
                            </div>
                        </label>
                        <input type="file" id="infografika-file-input" class="hidden" accept="image/*" />
                    </div>

                    <!-- URL Input -->
                    <div id="method-url-area" class="hidden">
                        <div class="relative">
                            <input type="url" id="image-url-input" placeholder="https://example.com/image.jpg" class="input-field pl-10 text-sm" />
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500"><i class="fa-solid fa-link text-xs"></i></div>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div id="img-preview-wrap" class="relative group hidden mx-auto w-fit mt-4">
                        <img id="img-preview" src="" alt="Preview" class="h-auto max-h-64 w-auto max-w-full rounded-xl border border-white/10 shadow-xl" />
                        <button type="button" id="img-remove-btn" class="absolute top-2 right-2 w-7 h-7 bg-black/70 backdrop-blur text-white rounded-full flex items-center justify-center text-xs hover:bg-red-500 transition-colors shadow-lg z-10">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
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
                        <input type="text" id="product-name-input" placeholder="Masalan: NIVEA krem, Nokia 6300" class="input-field text-sm" />
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
                            <button type="button" id="features-ai-btn" onclick="switchFeaturesMode('ai')" class="px-3 py-1.5 rounded-md transition-all text-xs font-semibold bg-indigo-500/15 text-indigo-400 flex items-center gap-1.5">
                                <i class="fa-solid fa-wand-magic-sparkles text-[10px]"></i>
                                AI tanlovi
                            </button>
                            <button type="button" id="features-manual-btn" onclick="switchFeaturesMode('manual')" class="px-3 py-1.5 rounded-md transition-all text-xs font-semibold text-gray-500 hover:text-gray-300 flex items-center gap-1.5">
                                <i class="fa-solid fa-pencil text-[10px]"></i>
                                Qo'lda kiritish
                            </button>
                        </div>
                        <div id="features-ai-hint" class="flex items-center gap-2.5 p-3 rounded-xl bg-indigo-500/[0.04] border border-indigo-500/10">
                            <i class="fa-solid fa-microchip text-indigo-400/50 text-base"></i>
                            <p class="text-xs text-gray-400">AI mahsulot rasmi va nomiga qarab xususiyatlarni <span class="text-indigo-400 font-medium">avtomatik tanlaydi</span></p>
                        </div>
                        <div id="features-manual-area" class="hidden">
                            <textarea id="features-input" placeholder="Suv o'tmaydigan IP68&#10;Batareya 7 kun&#10;Sog'liq monitoring&#10;GPS tracking" rows="4" class="input-field resize-none text-sm"></textarea>
                        </div>
                    </div>
                </div>

                <!-- CARD 3: Stil + Kategoriya + Til -->
                <div class="rounded-2xl bg-white/[0.03] border border-white/[0.06] p-5 space-y-5 hover:border-white/10 transition-colors">
                    
                    <!-- Infografika Stili -->
                    <div>
                        <label class="flex items-center gap-2.5 mb-3">
                            <div class="w-8 h-8 rounded-lg bg-pink-500/10 flex items-center justify-center">
                                <i class="fa-solid fa-palette text-pink-400 text-sm"></i>
                            </div>
                            <span class="text-sm font-semibold text-white">Infografika stili</span>
                        </label>
                        <div class="grid grid-cols-4 gap-1.5">
                            <button type="button" data-style="uzum" class="infostyle-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-indigo-500/50 bg-indigo-500/10 text-white" id="style-uzum">
                                <i class="fa-solid fa-circle text-purple-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Uzum</div>
                            </button>
                            <button type="button" data-style="wb" class="infostyle-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]" id="style-wb">
                                <i class="fa-solid fa-square text-fuchsia-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">WB</div>
                            </button>
                            <button type="button" data-style="ozon" class="infostyle-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]" id="style-ozon">
                                <i class="fa-solid fa-circle text-blue-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Ozon</div>
                            </button>
                            <button type="button" data-style="yandex" class="infostyle-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]" id="style-yandex">
                                <i class="fa-solid fa-circle text-yellow-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Yandex</div>
                            </button>
                            <button type="button" data-style="instagram" class="infostyle-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]" id="style-instagram">
                                <i class="fa-brands fa-instagram text-pink-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Instagram</div>
                            </button>
                            <button type="button" data-style="minimal" class="infostyle-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]" id="style-minimal">
                                <i class="fa-regular fa-square text-gray-300 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Minimal</div>
                            </button>
                            <button type="button" data-style="marketplace" class="infostyle-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]" id="style-marketplace">
                                <i class="fa-solid fa-store text-emerald-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Umumiy</div>
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
                        <div class="grid grid-cols-4 gap-1.5" id="category-container">
                            <button type="button" data-category="electronics" class="category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-mobile-screen-button text-sky-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Elektronika</div>
                            </button>
                            <button type="button" data-category="clothing" class="category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-shirt text-violet-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Kiyim</div>
                            </button>
                            <button type="button" data-category="beauty" class="category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-spray-can-sparkles text-pink-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Go'zallik</div>
                            </button>
                            <button type="button" data-category="home" class="category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-house-chimney text-amber-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Uy-ro'zg'or</div>
                            </button>
                            <button type="button" data-category="food" class="category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-apple-whole text-green-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Oziq-ovqat</div>
                            </button>
                            <button type="button" data-category="kids" class="category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-children text-yellow-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Bolalar</div>
                            </button>
                            <button type="button" data-category="sport" class="category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-dumbbell text-orange-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Sport</div>
                            </button>
                            <button type="button" data-category="auto" class="category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-car text-red-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Avto</div>
                            </button>
                            <button type="button" data-category="health" class="category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-capsules text-emerald-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Sog'liq</div>
                            </button>
                            <button type="button" data-category="tools" class="category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-screwdriver-wrench text-slate-300 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Asboblar</div>
                            </button>
                            <button type="button" data-category="accessories" class="category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-bag-shopping text-rose-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Aksesuar</div>
                            </button>
                            <button type="button" data-category="pet" class="category-btn py-2.5 px-1 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15 hover:bg-white/[0.04]">
                                <i class="fa-solid fa-paw text-teal-400 text-sm mb-1 block"></i>
                                <div class="text-[9px] font-bold uppercase tracking-wider">Hayvonlar</div>
                            </button>
                        </div>
                    </div>

                    <div class="border-t border-white/[0.04]"></div>

                    <!-- Til -->
                    <div>
                        <label class="flex items-center gap-2.5 mb-3">
                            <div class="w-8 h-8 rounded-lg bg-cyan-500/10 flex items-center justify-center">
                                <i class="fa-solid fa-language text-cyan-400 text-sm"></i>
                            </div>
                            <span class="text-sm font-semibold text-white">Infografika tili</span>
                        </label>
                        <div class="grid grid-cols-5 gap-1.5">
                            <button type="button" data-lang="ru" class="lang-btn py-2 rounded-xl border text-center transition-all duration-200 border-indigo-500/50 bg-indigo-500/10 text-white" id="lang-ru">
                                <div class="text-[10px] font-bold">RUS</div>
                            </button>
                            <button type="button" data-lang="uz" class="lang-btn py-2 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15" id="lang-uz">
                                <div class="text-[10px] font-bold">UZB</div>
                            </button>
                            <button type="button" data-lang="en" class="lang-btn py-2 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15" id="lang-en">
                                <div class="text-[10px] font-bold">ENG</div>
                            </button>
                            <button type="button" data-lang="tr" class="lang-btn py-2 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15" id="lang-tr">
                                <div class="text-[10px] font-bold">TUR</div>
                            </button>
                            <button type="button" data-lang="kz" class="lang-btn py-2 rounded-xl border text-center transition-all duration-200 border-white/[0.06] bg-white/[0.02] text-gray-400 hover:border-white/15" id="lang-kz">
                                <div class="text-[10px] font-bold">KAZ</div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="generate-button" disabled class="w-full h-14 rounded-2xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-500 hover:via-purple-500 hover:to-pink-500 text-white font-bold text-base flex items-center justify-center gap-3 transition-all duration-300 disabled:opacity-40 disabled:cursor-not-allowed shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 hover:scale-[1.01] active:scale-[0.99]">
                    <i class="fa-solid fa-wand-magic-sparkles text-lg" id="btn-icon"></i>
                    <span id="gen-btn-text">Infografika yaratish</span>
                </button>
                </form>
            </div>

            <!-- ============ RIGHT PANEL — RESULT ============ -->
            <div class="lg:col-span-7 lg:sticky lg:top-24 lg:self-start">
                <div class="rounded-2xl bg-white/[0.02] border border-white/[0.06] p-6 sm:p-8 min-h-[550px] flex flex-col items-center justify-center">
                
                <!-- Loading -->
                <div id="info-loading" class="text-center hidden w-full">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500/15 to-purple-500/15 border border-indigo-500/20 flex items-center justify-center mx-auto mb-5">
                        <i class="fa-solid fa-palette text-2xl text-indigo-400 animate-pulse"></i>
                    </div>
                    <h3 class="text-base font-bold text-white mb-1.5">AI ishlayapti...</h3>
                    <p class="text-xs text-gray-500 mb-5">Infografika yaratilmoqda. 15-30 soniya kutib turing.</p>
                    <div class="flex justify-center gap-1.5 mb-5">
                        <div class="w-2 h-2 rounded-full bg-indigo-500 animate-bounce" style="animation-delay: 0s"></div>
                        <div class="w-2 h-2 rounded-full bg-purple-500 animate-bounce" style="animation-delay: 0.15s"></div>
                        <div class="w-2 h-2 rounded-full bg-pink-500 animate-bounce" style="animation-delay: 0.3s"></div>
                    </div>
                    <p class="text-[11px] text-gray-600">AI xato qilishi mumkin. Natijani qayta tekshiruvdan o'tkazing.</p>
                </div>

                <!-- Error -->
                <div id="info-error" class="text-center hidden w-full">
                    <div class="w-16 h-16 rounded-2xl bg-red-500/10 border border-red-500/20 flex items-center justify-center mx-auto mb-5">
                        <i class="fa-solid fa-triangle-exclamation text-2xl text-red-400"></i>
                    </div>
                    <h3 class="text-base font-bold text-red-400 mb-1.5">Xatolik yuz berdi</h3>
                    <p id="info-error-text" class="text-xs text-gray-500 mb-5 max-w-xs mx-auto"></p>
                    <button id="info-retry-btn" class="px-6 py-2.5 rounded-xl bg-white/5 border border-white/10 text-sm font-semibold text-white hover:bg-white/10 transition-colors flex items-center gap-2 mx-auto">
                        <i class="fa-solid fa-arrow-rotate-right text-xs"></i>
                        Qayta urinish
                    </button>
                </div>

                <!-- Result -->
                <div id="info-result" class="w-full space-y-5 hidden">
                    <div class="flex items-center justify-center gap-3">
                        <button id="view-full-btn" class="px-5 h-10 rounded-xl bg-white/5 border border-white/10 text-sm font-semibold text-white hover:bg-white/10 transition-all flex items-center gap-2 hover:scale-105 active:scale-95">
                            <i class="fa-solid fa-expand text-indigo-400 text-xs"></i>
                            To'liq ko'rish
                        </button>
                        <button id="download-btn-main" class="px-6 h-10 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-sm font-bold text-white hover:from-indigo-500 hover:to-purple-500 transition-all flex items-center gap-2 shadow-lg shadow-indigo-500/20 hover:scale-105 active:scale-95">
                            <i class="fa-solid fa-download text-xs"></i>
                            Yuklab olish
                        </button>
                        <button id="reset-result-btn" class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 transition-all group" title="Tozalash">
                            <i class="fa-solid fa-rotate-left text-sm group-hover:rotate-180 transition-transform duration-500"></i>
                        </button>
                    </div>

                    <div id="slider-wrapper" class="w-full max-w-[480px] mx-auto">
                        <div id="slider-container" class="before-after-slider relative w-full overflow-hidden rounded-2xl border border-white/10 group cursor-ew-resize bg-black/10 shadow-2xl aspect-[3/4]">
                            <img id="before-img" src="" alt="Oldin" class="absolute inset-0 w-full h-full object-cover pointer-events-none" />
                            <span class="absolute top-3 left-3 z-20 bg-black/60 backdrop-blur-sm px-2.5 py-1 rounded-lg text-[9px] font-bold text-white uppercase tracking-wider select-none pointer-events-none">Oldin</span>
                            <div id="after-img-container" class="absolute inset-0 w-full h-full z-10 pointer-events-none" style="clip-path: inset(0 0 0 50%);">
                                <img id="after-img" src="" alt="Keyin" class="absolute inset-0 w-full h-full object-cover" />
                                <span class="absolute top-3 right-3 z-20 bg-indigo-500/80 backdrop-blur-sm px-2.5 py-1 rounded-lg text-[9px] font-bold text-white uppercase tracking-wider select-none pointer-events-none">Keyin</span>
                            </div>
                            <div id="slider-bar" class="absolute inset-y-0 left-1/2 w-0.5 bg-white z-30 shadow-[0_0_10px_rgba(255,255,255,0.4)]">
                                <div class="slider-handle absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-9 h-9 bg-white rounded-full flex items-center justify-center transition-all hover:scale-110 active:scale-90 shadow-xl border-2 border-white/30">
                                    <div class="flex gap-0.5 items-center">
                                        <i class="fa-solid fa-chevron-left text-[8px] text-indigo-600"></i>
                                        <i class="fa-solid fa-chevron-right text-[8px] text-indigo-600"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-600 text-center">AI xato qilishi mumkin. Natijani qayta tekshiruvdan o'tkazing.</p>
                </div>

                <!-- Empty -->
                <div id="info-empty" class="text-center">
                    <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-indigo-500/10 to-purple-500/10 border border-indigo-500/10 flex items-center justify-center mx-auto mb-5">
                        <i class="fa-solid fa-image text-3xl text-indigo-400/30"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-300 mb-1.5">Natija shu yerda ko'rinadi</h3>
                    <p class="text-xs text-gray-600 max-w-[220px] mx-auto">
                        Rasmni yuklang va "Infografika yaratish" tugmasini bosing.
                    </p>
                    <p class="text-[11px] text-gray-600 mt-3">AI xato qilishi mumkin. Natijani qayta tekshiruvdan o'tkazing.</p>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<!-- Full Image Modal -->
<div id="image-full-modal" class="fixed inset-0 z-[200] hidden flex items-center justify-center p-4 sm:p-8">
    <div class="absolute inset-0 bg-black/95 backdrop-blur-md" onclick="window.closeFullModal()"></div>
    <div class="relative max-w-7xl w-full h-full flex flex-col items-center justify-center">
        <button onclick="window.closeFullModal()" class="absolute top-0 right-0 m-4 p-3 bg-white/10 hover:bg-red-500 text-white rounded-full transition-all z-50 shadow-xl">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <img id="modal-image-el" src="" alt="Full Result" class="max-w-full max-h-full object-contain rounded-xl shadow-2xl border border-white/10" />
        <button id="modal-download-btn" class="absolute bottom-4 sm:bottom-8 px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-2xl font-bold flex items-center gap-2 shadow-2xl transition-all z-50 hover:scale-105 active:scale-95">
            <i class="fa-solid fa-download"></i>
            Rasmni yuklab olish
        </button>
    </div>
</div>

<style>
#progress-bar-container { margin-top: 1rem; }
.before-after-slider {
    touch-action: none;
    user-select: none;
    -webkit-user-select: none;
}
#after-img-container { will-change: clip-path; }
#slider-bar { will-change: left; }
.slider-handle {
    box-shadow: 0 0 15px rgba(0,0,0,0.3), 0 0 0 4px rgba(255,255,255,0.2);
}
</style>

<script>
(function(){
    let selectedStyle = 'uzum';
    let selectedLang = 'ru';
    let selectedCategory = '';
    let featuresMode = 'ai';
    let imagePreview = null;
    let resultUrl = null;

    // Features mode toggle (AI / Qo'lda)
    window.switchFeaturesMode = function(mode) {
        featuresMode = mode;
        const aiBtn = document.getElementById('features-ai-btn');
        const manualBtn = document.getElementById('features-manual-btn');
        const aiHint = document.getElementById('features-ai-hint');
        const manualArea = document.getElementById('features-manual-area');
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
            document.getElementById('features-input').focus();
        }
    };

    // Kategoriya tanlash
    const activeClasses = ['border-indigo-500/50', 'bg-indigo-500/10', 'text-white'];
    const inactiveClasses = ['border-white/[0.06]', 'bg-white/[0.02]', 'text-gray-400'];

    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.category-btn').forEach(b => {
                b.classList.remove(...activeClasses);
                b.classList.add(...inactiveClasses);
            });
            this.classList.remove(...inactiveClasses);
            this.classList.add(...activeClasses);
            selectedCategory = this.dataset.category;
        });
    });

    const productName = document.getElementById('product-name-input');
    const features = document.getElementById('features-input');
    const genBtn = document.getElementById('generate-button');
    const genBtnText = document.getElementById('gen-btn-text');
    const btnIcon = document.getElementById('btn-icon');
    const imageUpload = document.getElementById('infografika-file-input');
    const imageUrlInput = document.getElementById('image-url-input');
    const imgUploadLabel = document.getElementById('img-upload-label');
    const methodFileArea = document.getElementById('method-file-area');
    const methodUrlArea = document.getElementById('method-url-area');
    const methodFileBtn = document.getElementById('method-file-btn');
    const methodUrlBtn = document.getElementById('method-url-btn');

    const beforeImg = document.getElementById('before-img');
    const afterImg = document.getElementById('after-img');
    const sliderContainer = document.getElementById('slider-container');
    const sliderBar = document.getElementById('slider-bar');
    const afterImgContainer = document.getElementById('after-img-container');
    
    let progressBarContainer = document.createElement('div');
    progressBarContainer.id = 'progress-bar-container';
    progressBarContainer.className = 'hidden';
    progressBarContainer.innerHTML = `
        <div class="h-1.5 w-full bg-white/5 rounded-full overflow-hidden mt-3">
            <div id="progress-bar" class="h-full bg-gradient-to-r from-indigo-600 via-purple-500 to-pink-500 rounded-full transition-all duration-300 ease-out" style="width: 0%"></div>
        </div>
    `;
    genBtn.parentNode.insertBefore(progressBarContainer, genBtn.nextSibling);

    const progressBar = document.getElementById('progress-bar');
    let progress = 0;
    let progressInterval = null;

    const imgPreviewWrap = document.getElementById('img-preview-wrap');
    const imgPreview = document.getElementById('img-preview');
    const imgRemoveBtn = document.getElementById('img-remove-btn');

    let currentMethod = 'file';

    window.switchImageMethod = function(method) {
        currentMethod = method;
        if (method === 'file') {
            methodFileArea.classList.remove('hidden');
            methodUrlArea.classList.add('hidden');
            methodFileBtn.className = 'px-2.5 py-1 rounded-md transition-all bg-indigo-500/20 text-indigo-400 font-semibold';
            methodUrlBtn.className = 'px-2.5 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300 font-semibold';
            if (imagePreview && imagePreview.startsWith('http')) clearImage();
        } else {
            methodFileArea.classList.add('hidden');
            methodUrlArea.classList.remove('hidden');
            methodUrlBtn.className = 'px-2.5 py-1 rounded-md transition-all bg-indigo-500/20 text-indigo-400 font-semibold';
            methodFileBtn.className = 'px-2.5 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300 font-semibold';
            if (imagePreview && imagePreview.startsWith('data:')) clearImage();
        }
    };

    imageUrlInput.addEventListener('input', function() {
        const url = this.value.trim();
        if (url && (url.startsWith('http://') || url.startsWith('https://'))) {
            imagePreview = url;
            imgPreview.src = url;
            imgPreviewWrap.classList.remove('hidden');
            methodUrlArea.classList.add('hidden');
        }
    });

    function checkForm() {
        genBtn.disabled = !imagePreview;
    }
    productName.addEventListener('input', checkForm);
    features.addEventListener('input', checkForm);

    // Style selector
    document.querySelectorAll('.infostyle-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.infostyle-btn').forEach(b => {
                b.classList.remove(...activeClasses);
                b.classList.add(...inactiveClasses);
            });
            this.classList.remove(...inactiveClasses);
            this.classList.add(...activeClasses);
            selectedStyle = this.dataset.style;
        });
    });

    // Lang selector
    document.querySelectorAll('.lang-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.lang-btn').forEach(b => {
                b.classList.remove(...activeClasses);
                b.classList.add(...inactiveClasses);
            });
            this.classList.remove(...inactiveClasses);
            this.classList.add(...activeClasses);
            selectedLang = this.dataset.lang;
        });
    });

    if (imageUpload) {
        imageUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function() {
                    imagePreview = reader.result;
                    imgPreview.src = imagePreview;
                    methodFileArea.classList.add('hidden');
                    imgPreviewWrap.classList.remove('hidden');
                    checkForm();
                };
                reader.readAsDataURL(file);
            }
        });
    }

    function clearImage() {
        imagePreview = null;
        imageUpload.value = '';
        imageUrlInput.value = '';
        imgPreviewWrap.classList.add('hidden');
        if (currentMethod === 'file') {
            methodFileArea.classList.remove('hidden');
        } else {
            methodUrlArea.classList.remove('hidden');
        }
        checkForm();
    }

    imgRemoveBtn.addEventListener('click', clearImage);

    function showInfoState(state) {
        document.getElementById('info-loading').classList.add('hidden');
        document.getElementById('info-error').classList.add('hidden');
        document.getElementById('info-result').classList.add('hidden');
        document.getElementById('info-empty').classList.add('hidden');
        if (state === 'loading') document.getElementById('info-loading').classList.remove('hidden');
        if (state === 'error') document.getElementById('info-error').classList.remove('hidden');
        if (state === 'result') {
            document.getElementById('info-result').classList.remove('hidden');
            if (afterImg.complete) initSliderReveal();
            else afterImg.onload = initSliderReveal;
        }
        if (state === 'empty') document.getElementById('info-empty').classList.remove('hidden');
    }

    // Submit
    document.getElementById('infografika-form').addEventListener('submit', function(e) {
        e.preventDefault();
        TibaAuth.requireAuth(async function() {
        genBtn.disabled = true;
        progressBarContainer.classList.remove('hidden');
        progress = 0;
        progressBar.style.width = '0%';
        showInfoState('loading');

        progressInterval = setInterval(() => {
            if (progress < 85) progress += Math.random() * 2;
            else if (progress < 98) progress += 0.1;
            progressBar.style.width = progress + '%';
            genBtnText.textContent = 'Yaratilmoqda... ' + Math.floor(progress) + '%';
            btnIcon.className = 'fa-solid fa-spinner fa-spin text-lg';
        }, 400);

        try {
            const response = await fetch('/api/generate.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-User-Token': TibaAuth.getToken()
                },
                body: JSON.stringify({
                    productName: productName.value,
                    features: featuresMode === 'manual' ? features.value : '',
                    style: selectedStyle,
                    language: selectedLang,
                    productImage: imagePreview || null,
                    category: selectedCategory,
                })
            });
            const text = await response.text();
            let data;
            try { data = JSON.parse(text); } catch { throw new Error("Serverdan noto'g'ri javob keldi."); }
            if (!response.ok) {
                if (data.auth_required) { TibaAuth.showModal(); throw new Error('Avval tizimga kiring'); }
                if (data.insufficient_balance) { showNoBalance(data.cost, data.balance); throw new Error('__nobalance__'); }
                throw new Error(data.error || 'Xatolik yuz berdi');
            }
            
            if (data.balance !== undefined) TibaAuth.updateBalance(data.balance);
            resultUrl = data.imageUrl;
            beforeImg.src = imagePreview || ''; 
            afterImg.src = resultUrl;
            progress = 100;
            progressBar.style.width = '100%';
            showInfoState('result');
        } catch (err) {
            if (err.message !== '__nobalance__') {
                document.getElementById('info-error-text').textContent = err.message;
                showInfoState('error');
            }
        } finally {
            clearInterval(progressInterval);
            genBtn.disabled = false;
            genBtnText.textContent = 'Infografika yaratish';
            btnIcon.className = 'fa-solid fa-wand-magic-sparkles text-lg';
            progressBarContainer.classList.add('hidden');
        }
        });
    });

    function handleDownload() {
        if (!resultUrl) return;
        const name = productName.value ? productName.value.replace(/\s+/g, '-').toLowerCase() : 'infografika';
        const a = document.createElement('a');
        a.href = resultUrl;
        a.download = name + '-' + Date.now() + '.png';
        a.target = '_blank';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    const imageModal = document.getElementById('image-full-modal');
    const modalImage = document.getElementById('modal-image-el');
    const viewFullBtn = document.getElementById('view-full-btn');

    if (viewFullBtn) {
        viewFullBtn.addEventListener('click', () => {
            if (!resultUrl) return;
            modalImage.src = resultUrl;
            imageModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    }

    window.closeFullModal = function() {
        if (imageModal) imageModal.classList.add('hidden');
        document.body.style.overflow = '';
    };

    document.getElementById('download-btn-main').addEventListener('click', handleDownload);
    document.getElementById('modal-download-btn').addEventListener('click', handleDownload);
    document.getElementById('reset-result-btn').addEventListener('click', handleResetFromResult);
    document.getElementById('info-retry-btn').addEventListener('click', () => showInfoState('empty'));

    function handleResetFromResult() {
        clearImage();
        resultUrl = null;
        beforeImg.src = '';
        afterImg.src = '';
        showInfoState('empty');
    }

    // Slider Logic
    let isDragging = false;
    
    function moveSlider(e) {
        if (!isDragging) return;
        if (e.cancelable) e.preventDefault();
        
        sliderBar.style.transition = 'none';
        afterImgContainer.style.transition = 'none';
        
        const rect = sliderContainer.getBoundingClientRect();
        const clientX = (e.clientX !== undefined) ? e.clientX : (e.touches && e.touches[0] ? e.touches[0].clientX : 0);
        
        let x = clientX - rect.left;
        let percent = (x / rect.width) * 100;
        if (percent < 0) percent = 0;
        if (percent > 100) percent = 100;
        
        window.requestAnimationFrame(() => {
            sliderBar.style.left = percent + '%';
            afterImgContainer.style.clipPath = `inset(0 0 0 ${percent}%)`;
        });
    }

    const startDrag = (e) => { 
        isDragging = true; 
        sliderBar.style.transition = 'none';
        afterImgContainer.style.transition = 'none';
        moveSlider(e); 
    };
    const stopDrag = () => { isDragging = false; };

    if (sliderContainer) {
        sliderContainer.addEventListener('mousedown', startDrag);
        window.addEventListener('mouseup', stopDrag);
        window.addEventListener('mousemove', moveSlider);
        sliderContainer.addEventListener('touchstart', startDrag, { passive: false });
        window.addEventListener('touchend', stopDrag);
        window.addEventListener('touchmove', moveSlider, { passive: false });
        window.addEventListener('touchcancel', stopDrag);
    }

    function initSliderReveal() {
        sliderBar.style.transition = 'none';
        afterImgContainer.style.transition = 'none';
        sliderBar.style.left = '100%';
        afterImgContainer.style.clipPath = 'inset(0 0 0 100%)';
        void sliderBar.offsetWidth;
        
        setTimeout(() => {
            sliderBar.style.transition = 'left 1.5s cubic-bezier(0.19, 1, 0.22, 1)';
            afterImgContainer.style.transition = 'clip-path 1.5s cubic-bezier(0.19, 1, 0.22, 1)';
            sliderBar.style.left = '50%';
            afterImgContainer.style.clipPath = 'inset(0 0 0 50%)';
            setTimeout(() => {
                if (!isDragging) {
                    sliderBar.style.transition = '';
                    afterImgContainer.style.transition = '';
                }
            }, 1600);
        }, 150);
    }

    checkForm();
})();
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
