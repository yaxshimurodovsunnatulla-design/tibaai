<?php $pageTitle = 'Foto Tahrir – Tiba AI'; ?>
<?php include __DIR__ . '/../components/header.php'; ?>

<style>
.before-after-slider {
    touch-action: none;
    user-select: none;
    -webkit-user-select: none;
}
#after-img-container {
    will-change: clip-path;
}
#slider-bar {
    will-change: left;
}
.slider-handle {
    box-shadow: 0 0 15px rgba(0,0,0,0.3), 0 0 0 4px rgba(255,255,255,0.2);
}
</style>

<div class="py-12 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-8 animate-fade-in">
            <a href="/create" class="inline-flex items-center gap-2 text-gray-500 hover:text-white transition-colors text-sm font-bold uppercase tracking-widest group">
                <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                Orqaga
            </a>
        </div>
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3">
                Foto <span class="gradient-text">Tahrir</span>
            </h1>
            <p class="text-gray-400 max-w-xl mx-auto">
                Mahsulot rasmi fonini professional uslubda almashtiring. 12 ta tayyor stil mavjud.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Panel — Controls -->
            <div class="lg:col-span-5 space-y-6 order-1">
                <div class="glass-card p-6 sm:p-8 space-y-6">
                    <!-- Upload -->
                    <!-- Image Input Section -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex justify-between items-center">
                            <i class="fa-solid fa-image text-indigo-400/70"></i>
                            <span>Mahsulot rasmi</span>
                            <div class="flex p-0.5 bg-white/5 rounded-lg border border-white/5 text-[9px]">
                                <button type="button" onclick="switchImageMethod('file')" id="method-file-btn" class="px-2 py-1 rounded-md transition-all bg-indigo-500/20 text-indigo-400">FAYL</button>
                                <button type="button" onclick="switchImageMethod('url')" id="method-url-btn" class="px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300">HAVOLA</button>
                            </div>
                        </label>

                        <div id="image-input-container">
                            <!-- File area -->
                            <div id="method-file-area">
                                <label id="upload-label" class="flex flex-col items-center justify-center w-full h-44 border-2 border-dashed border-white/5 rounded-3xl bg-white/[0.03] hover:bg-white/[0.08] hover:border-indigo-500/40 transition-all duration-300 cursor-pointer group relative overflow-hidden">
                                    <!-- Background Glow -->
                                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/0 to-purple-500/0 group-hover:from-indigo-500/5 group-hover:to-purple-500/5 transition-all duration-500"></div>
                                    
                                    <div class="relative z-10 flex flex-col items-center">
                                        <div class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-indigo-500/10 transition-all duration-500 border border-white/5 group-hover:border-indigo-500/20">
                                            <i class="fa-solid fa-image text-2xl text-gray-500 group-hover:text-indigo-400"></i>
                                        </div>
                                        <span class="text-sm font-bold text-gray-300 group-hover:text-white transition-colors">Mahsulot rasmini yuklash</span>
                                        <span class="text-[10px] text-gray-500 mt-1 uppercase tracking-tighter">JPG, PNG • MAKS. 10 MB</span>
                                    </div>
                                    <input id="file-input" type="file" accept="image/*" class="hidden" />
                                </label>
                            </div>

                            <!-- URL area -->
                            <div id="method-url-area" class="hidden">
                                <div class="relative">
                                    <input type="url" id="image-url-input" placeholder="https://example.com/image.jpg" class="input-field pl-10" />
                                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500">🔗</div>
                                </div>
                                <p class="text-[10px] text-gray-500 mt-2">Rasm havolasini (URL) buni ustiga nusxalang.</p>
                            </div>

                            <!-- Preview area -->
                            <div id="preview-container" class="relative group hidden mx-auto w-fit">
                                <img id="preview-image" src="" alt="Preview" class="h-auto max-h-80 w-auto max-w-full rounded-2xl border border-white/10 shadow-2xl transition-all" />
                                <button id="reset-btn" class="absolute top-2 right-2 w-8 h-8 bg-black/60 backdrop-blur text-white rounded-full flex items-center justify-center text-sm hover:bg-red-500 transition-colors shadow-lg z-10">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Format -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-maximize text-indigo-400/70"></i>
                            <span>Format (o'lcham)</span>
                        </label>
                        <div class="grid grid-cols-3 sm:grid-cols-5 gap-2" id="template-selector">
                            <button data-ratio="3:4" data-id="uzum" class="template-btn active p-2.5 rounded-xl border text-center transition-all duration-200 border-indigo-500 bg-indigo-500/15 text-white shadow-lg shadow-indigo-500/10">
                                <div class="text-[10px] font-bold uppercase tracking-tighter">Uzum</div>
                                <div class="text-[8px] opacity-60 font-bold">1080x1440</div>
                            </button>
                            <button data-ratio="3:4" data-id="wildberries" class="template-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold uppercase tracking-tighter">Wildberries</div>
                                <div class="text-[8px] opacity-60 font-bold">900x1200</div>
                            </button>
                            <button data-ratio="3:4" data-id="yandexmarket" class="template-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold uppercase tracking-tighter">Yandex Market</div>
                                <div class="text-[8px] opacity-60 font-bold">1080x1440</div>
                            </button>
                            <button data-ratio="1:1" data-id="square" class="template-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold uppercase tracking-tighter">Kvadrat</div>
                                <div class="text-[8px] opacity-60 font-bold">1080x1080</div>
                            </button>
                            <button data-ratio="9:16" data-id="story" class="template-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold uppercase tracking-tighter">Story</div>
                                <div class="text-[8px] opacity-60 font-bold">1080x1920</div>
                            </button>
                        </div>
                    </div>

                    <!-- Style Selector -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-palette text-indigo-400/70"></i>
                            <span>Vizual uslub</span>
                        </label>
                        <div class="grid grid-cols-4 gap-2" id="style-selector">
                            <button data-style="minimalist" class="style-btn active relative overflow-hidden rounded-lg border p-1.5 h-16 flex flex-col items-center justify-center gap-1 transition-all border-indigo-500 ring-2 ring-indigo-500/30">
                                <div class="absolute inset-0 bg-gradient-to-br from-gray-200 to-white opacity-10"></div>
                                <div class="relative z-10">
                                    <i class="fa-solid fa-wand-magic-sparkles text-gray-400 text-lg"></i>
                                </div>
                                <div class="relative z-10 font-bold text-[8px] uppercase tracking-tighter text-center text-gray-300">Minimalist</div>
                            </button>
                            <button data-style="bright" class="style-btn relative overflow-hidden rounded-lg border p-1.5 h-16 flex flex-col items-center justify-center gap-1 transition-all border-white/10 hover:border-white/20">
                                <div class="absolute inset-0 bg-gradient-to-br from-orange-400 to-yellow-300 opacity-10"></div>
                                <div class="relative z-10">
                                    <i class="fa-solid fa-sun text-yellow-500 text-lg"></i>
                                </div>
                                <div class="relative z-10 font-bold text-[8px] uppercase tracking-tighter text-center text-gray-300">Yorqin</div>
                            </button>
                            <button data-style="premium" class="style-btn relative overflow-hidden rounded-lg border p-1.5 h-16 flex flex-col items-center justify-center gap-1 transition-all border-white/10 hover:border-white/20">
                                <div class="absolute inset-0 bg-gradient-to-br from-purple-600 to-indigo-600 opacity-10"></div>
                                <div class="relative z-10">
                                    <i class="fa-solid fa-crown text-indigo-400 text-lg"></i>
                                </div>
                                <div class="relative z-10 font-bold text-[8px] uppercase tracking-tighter text-center text-gray-300">Premium</div>
                            </button>
                            <button data-style="studio" class="style-btn relative overflow-hidden rounded-lg border p-1.5 h-16 flex flex-col items-center justify-center gap-1 transition-all border-white/10 hover:border-white/20">
                                <div class="absolute inset-0 bg-gradient-to-br from-gray-400 to-gray-600 opacity-10"></div>
                                <div class="relative z-10">
                                    <i class="fa-solid fa-camera text-gray-300 text-lg"></i>
                                </div>
                                <div class="relative z-10 font-bold text-[8px] uppercase tracking-tighter text-center text-gray-300">Studio</div>
                            </button>
                            <button data-style="nature" class="style-btn relative overflow-hidden rounded-lg border p-1.5 h-16 flex flex-col items-center justify-center gap-1 transition-all border-white/10 hover:border-white/20">
                                <div class="absolute inset-0 bg-gradient-to-br from-green-500 to-emerald-600 opacity-10"></div>
                                <div class="relative z-10">
                                    <i class="fa-solid fa-leaf text-emerald-400 text-lg"></i>
                                </div>
                                <div class="relative z-10 font-bold text-[8px] uppercase tracking-tighter text-center text-gray-300">Tabiat</div>
                            </button>
                            <button data-style="neon" class="style-btn relative overflow-hidden rounded-lg border p-1.5 h-16 flex flex-col items-center justify-center gap-1 transition-all border-white/10 hover:border-white/20">
                                <div class="absolute inset-0 bg-gradient-to-br from-pink-500 to-purple-600 opacity-10"></div>
                                <div class="relative z-10">
                                    <i class="fa-solid fa-bolt-lightning text-pink-400 text-lg"></i>
                                </div>
                                <div class="relative z-10 font-bold text-[8px] uppercase tracking-tighter text-center text-gray-300">Neon</div>
                            </button>
                            <button data-style="vintage" class="style-btn relative overflow-hidden rounded-lg border p-1.5 h-16 flex flex-col items-center justify-center gap-1 transition-all border-white/10 hover:border-white/20">
                                <div class="absolute inset-0 bg-gradient-to-br from-amber-700 to-orange-900 opacity-10"></div>
                                <div class="relative z-10">
                                    <i class="fa-solid fa-clock-rotate-left text-amber-500 text-lg"></i>
                                </div>
                                <div class="relative z-10 font-bold text-[8px] uppercase tracking-tighter text-center text-gray-300">Vintage</div>
                            </button>
                            <button data-style="loft" class="style-btn relative overflow-hidden rounded-lg border p-1.5 h-16 flex flex-col items-center justify-center gap-1 transition-all border-white/10 hover:border-white/20">
                                <div class="absolute inset-0 bg-gradient-to-br from-stone-500 to-stone-700 opacity-10"></div>
                                <div class="relative z-10">
                                    <i class="fa-solid fa-warehouse text-stone-400 text-lg"></i>
                                </div>
                                <div class="relative z-10 font-bold text-[8px] uppercase tracking-tighter text-center text-gray-300">Loft</div>
                            </button>
                            <button data-style="water" class="style-btn relative overflow-hidden rounded-lg border p-1.5 h-16 flex flex-col items-center justify-center gap-1 transition-all border-white/10 hover:border-white/20">
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-cyan-500 opacity-10"></div>
                                <div class="relative z-10">
                                    <i class="fa-solid fa-droplet text-cyan-400 text-lg"></i>
                                </div>
                                <div class="relative z-10 font-bold text-[8px] uppercase tracking-tighter text-center text-gray-300">Suvli</div>
                            </button>
                            <button data-style="abstract" class="style-btn relative overflow-hidden rounded-lg border p-1.5 h-16 flex flex-col items-center justify-center gap-1 transition-all border-white/10 hover:border-white/20">
                                <div class="absolute inset-0 bg-gradient-to-br from-violet-500 to-fuchsia-600 opacity-10"></div>
                                <div class="relative z-10">
                                    <i class="fa-solid fa-palette text-fuchsia-400 text-lg"></i>
                                </div>
                                <div class="relative z-10 font-bold text-[8px] uppercase tracking-tighter text-center text-gray-300">Abstrakt</div>
                            </button>
                            <button data-style="home" class="style-btn relative overflow-hidden rounded-lg border p-1.5 h-16 flex flex-col items-center justify-center gap-1 transition-all border-white/10 hover:border-white/20">
                                <div class="absolute inset-0 bg-gradient-to-br from-amber-500 to-orange-600 opacity-10"></div>
                                <div class="relative z-10">
                                    <i class="fa-solid fa-house-chimney-window text-orange-400 text-lg"></i>
                                </div>
                                <div class="relative z-10 font-bold text-[8px] uppercase tracking-tighter text-center text-gray-300">Uy sharoiti</div>
                            </button>
                            <button data-style="tech" class="style-btn relative overflow-hidden rounded-lg border p-1.5 h-16 flex flex-col items-center justify-center gap-1 transition-all border-white/10 hover:border-white/20">
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-blue-800 opacity-10"></div>
                                <div class="relative z-10">
                                    <i class="fa-solid fa-microchip text-blue-400 text-lg"></i>
                                </div>
                                <div class="relative z-10 font-bold text-[8px] uppercase tracking-tighter text-center text-gray-300">Texno</div>
                            </button>
                        </div>
                    </div>

                    <!-- Custom Prompt -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-pen-nib text-indigo-400/70"></i>
                            <span>Qo'shimcha istaklar (ixtiyoriy)</span>
                        </label>
                        <textarea id="custom-prompt" placeholder="Masalan: Orqa fonni neonli tungi shahar qilib ber..." rows="2" class="input-field resize-none text-sm"></textarea>
                    </div>

                    <!-- Generate Button -->
                    <button id="generate-btn" disabled class="btn-primary w-full h-16 text-lg font-extrabold disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 flex items-center justify-center gap-3">
                        <i class="fa-solid fa-wand-magic-sparkles text-xl" id="btn-icon"></i>
                        <span id="btn-text">Fonni almashtirish</span>
                    </button>

                    <!-- Progress bar -->
                    <div id="progress-bar-container" class="hidden">
                        <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden">
                            <div id="progress-bar" class="h-full bg-gradient-to-r from-indigo-600 via-purple-500 to-pink-500 rounded-full transition-all duration-300 ease-out shadow-[0_0_15px_rgba(99,102,241,0.5)]" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel — Result -->
            <div class="lg:col-span-7 order-2">
                <div class="glass-card p-6 sm:p-8 min-h-[500px] flex flex-col items-center justify-center">
                    <!-- Loading state -->
                    <div id="loading-state" class="text-center animate-fade-in hidden">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-600/20 to-purple-600/20 border border-indigo-500/20 flex items-center justify-center mx-auto mb-6 animate-pulse">
                            <i class="fa-solid fa-palette text-3xl text-indigo-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">AI ishlayapti...</h3>
                        <p class="text-sm text-gray-400">Fon almashtirilmoqda. Bu 15-40 soniya davom etishi mumkin.</p>
                        <div class="mt-6 flex justify-center gap-1">
                            <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                            <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse" style="animation-delay: 0.15s"></div>
                            <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse" style="animation-delay: 0.3s"></div>
                            <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse" style="animation-delay: 0.45s"></div>
                            <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse" style="animation-delay: 0.6s"></div>
                        </div>
                    </div>

                    <!-- Error state -->
                    <div id="error-state" class="text-center animate-fade-in hidden">
                        <div class="w-16 h-16 rounded-2xl bg-red-500/10 flex items-center justify-center mx-auto mb-4">
                            <span class="text-3xl">❌</span>
                        </div>
                        <h3 class="text-lg font-semibold text-red-400 mb-2">Xatolik</h3>
                        <p id="error-text" class="text-sm text-gray-400 mb-4"></p>
                        <button id="retry-btn" class="btn-secondary text-sm px-6 py-2">Qayta urinish</button>
                    </div>

                    <!-- Result state -->
                    <div id="result-state" class="w-full animate-fade-in space-y-6 hidden">
                        <!-- Action Buttons (Top) -->
                        <div class="flex flex-wrap items-center justify-center gap-4">
                            <button id="view-full-btn" class="flex-1 sm:flex-none btn-secondary px-6 h-12 rounded-xl flex items-center justify-center gap-2 font-bold min-w-[160px] text-sm transform transition-transform hover:scale-105">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                </svg>
                                To'liq ko'rish
                            </button>
                            <button id="download-btn-main" class="flex-1 sm:flex-none btn-primary px-8 h-12 rounded-xl flex items-center justify-center gap-2 font-bold min-w-[180px] text-sm shadow-lg shadow-indigo-500/20 transform transition-transform hover:scale-105">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Yuklab olish
                            </button>
                            <button id="reset-result-btn" class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 hover:border-white/20 transition-all group" title="Tozalash">
                                <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" />
                                </svg>
                            </button>
                        </div>

                        <!-- Before / After comparison slider -->
                        <div id="slider-wrapper" class="w-full max-w-[500px] mx-auto">
                            <div id="slider-container" class="before-after-slider relative w-full overflow-hidden rounded-2xl border border-white/10 group cursor-ew-resize bg-black/5 shadow-2xl">
                                <!-- Before Image (Bottom) -->
                                <img id="before-img" src="" alt="Oldin" class="absolute inset-0 w-full h-full object-cover pointer-events-none" />
                                <span class="absolute top-4 left-4 z-20 bg-black/60 backdrop-blur px-3 py-1.5 rounded-lg text-[10px] font-bold text-white uppercase tracking-wider select-none">Oldin</span>
                                
                                <!-- After Image (Top, Clipped) -->
                                <div id="after-img-container" class="absolute inset-0 w-full h-full z-10 pointer-events-none" style="clip-path: inset(0 0 0 50%);">
                                    <img id="after-img" src="" alt="Keyin" class="absolute inset-0 w-full h-full object-cover" />
                                    <span class="absolute top-4 right-4 z-20 bg-indigo-500/80 backdrop-blur px-3 py-1.5 rounded-lg text-[10px] font-bold text-white uppercase tracking-wider select-none">Keyin</span>
                                </div>

                                <!-- Slider Bar -->
                                <div id="slider-bar" class="absolute inset-y-0 left-1/2 w-0.5 bg-white z-30 pointer-events-none shadow-[0_0_10px_rgba(255,255,255,0.5)]">
                                    <div class="slider-handle absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-10 h-10 bg-white rounded-full flex items-center justify-center pointer-events-auto transition-all hover:scale-110 active:scale-90 shadow-2xl border-4 border-white/20">
                                        <div class="flex gap-0.5 items-center">
                                            <svg class="w-2.5 h-2.5 text-indigo-600 rotate-180" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4.555 5.168A1 1 0 003 6v8a1 1 0 001.555.832L10 11.202V14a1 1 0 001.555.832l7-4a1 1 0 000-1.664l-7-4A1 1 0 0010 6v2.798L4.555 5.168z" />
                                            </svg>
                                            <svg class="w-2.5 h-2.5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4.555 5.168A1 1 0 003 6v8a1 1 0 001.555.832L10 11.202V14a1 1 0 001.555.832l7-4a1 1 0 000-1.664l-7-4A1 1 0 0010 6v2.798L4.555 5.168z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty state -->
                    <div id="empty-state" class="text-center">
                        <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-indigo-600/20 to-purple-600/20 border border-indigo-500/20 flex items-center justify-center mx-auto mb-6">
                            <i class="fa-solid fa-image text-4xl text-indigo-400/50"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Natija shu yerda ko'rinadi</h3>
                        <p class="text-sm text-gray-500 max-w-xs mx-auto">
                            Rasmni yuklang, stilni tanlang va "Fonni almashtirish" tugmasini bosing.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Full Image Modal -->
<div id="image-full-modal" class="fixed inset-0 z-[200] hidden flex items-center justify-center p-4 sm:p-8">
    <div class="absolute inset-0 bg-black/95 backdrop-blur-md" onclick="closeFullModal()"></div>
    <div class="relative max-w-7xl w-full h-full flex flex-col items-center justify-center animate-zoom-in">
        <button onclick="closeFullModal()" class="absolute top-0 right-0 m-4 p-2 bg-white/10 hover:bg-white/20 rounded-full text-white transition-all z-50">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <img id="modal-image-el" src="" alt="Full Result" class="max-w-full max-h-full object-contain rounded-xl shadow-2xl border border-white/10" />
        <button id="modal-download-btn" class="absolute bottom-4 sm:bottom-10 px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl font-bold flex items-center gap-2 shadow-2xl transition-all z-50 hover:scale-105 active:scale-95">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Rasmni yuklab olish
        </button>
    </div>
</div>

<script>
(function(){
    let previewUrl = null;
    let selectedStyle = 'minimalist';
    let selectedRatio = '3:4';
    let resultUrl = null;
    let progress = 0;
    let progressInterval = null;

    const fileInput = document.getElementById('file-input');
    const imageUrlInput = document.getElementById('image-url-input');
    const uploadLabel = document.getElementById('upload-label');
    const methodFileArea = document.getElementById('method-file-area');
    const methodUrlArea = document.getElementById('method-url-area');
    const methodFileBtn = document.getElementById('method-file-btn');
    const methodUrlBtn = document.getElementById('method-url-btn');

    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');
    const resetBtn = document.getElementById('reset-btn');
    const generateBtn = document.getElementById('generate-btn');
    const btnText = document.getElementById('btn-text');
    const progressBarContainer = document.getElementById('progress-bar-container');
    const progressBar = document.getElementById('progress-bar');
    const customPrompt = document.getElementById('custom-prompt');

    const loadingState = document.getElementById('loading-state');
    const errorState = document.getElementById('error-state');
    const resultState = document.getElementById('result-state');
    const emptyState = document.getElementById('empty-state');
    const beforeImg = document.getElementById('before-img');
    const afterImg = document.getElementById('after-img');
    const imageModal = document.getElementById('image-full-modal');
    const modalImage = document.getElementById('modal-image-el');
    const errorText = document.getElementById('error-text');
    const retryBtn = document.getElementById('retry-btn');
    const sliderContainer = document.getElementById('slider-container');
    const afterImgContainer = document.getElementById('after-img-container');
    const sliderBar = document.getElementById('slider-bar');

    let currentMethod = 'file'; // 'file' or 'url'

    // Switch methods
    window.switchImageMethod = function(method) {
        currentMethod = method;
        if (method === 'file') {
            methodFileArea.classList.remove('hidden');
            methodUrlArea.classList.add('hidden');
            methodFileBtn.className = 'px-2 py-1 rounded-md transition-all bg-indigo-500/20 text-indigo-400';
            methodUrlBtn.className = 'px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300';
            if (previewUrl && previewUrl.startsWith('http')) handleReset();
        } else {
            methodFileArea.classList.add('hidden');
            methodUrlArea.classList.remove('hidden');
            methodUrlBtn.className = 'px-2 py-1 rounded-md transition-all bg-indigo-500/20 text-indigo-400';
            methodFileBtn.className = 'px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300';
            if (previewUrl && previewUrl.startsWith('data:')) handleReset();
        }
    };

    // URL input listener
    imageUrlInput.addEventListener('input', function() {
        const url = this.value.trim();
        if (url && (url.startsWith('http://') || url.startsWith('https://'))) {
            previewUrl = url;
            previewImage.src = url;
            methodUrlArea.classList.add('hidden');
            previewContainer.classList.remove('hidden');
            generateBtn.disabled = false;
        }
    });

    // File upload
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function() {
                previewUrl = reader.result;
                previewImage.src = previewUrl;
                methodFileArea.classList.add('hidden');
                previewContainer.classList.remove('hidden');
                generateBtn.disabled = false;
                resultUrl = null;
                showState('empty');
            };
            reader.readAsDataURL(file);
        }
    });

    // Reset
    function handleReset() {
        previewUrl = null;
        resultUrl = null;
        fileInput.value = '';
        imageUrlInput.value = '';
        previewContainer.classList.add('hidden');
        if (currentMethod === 'file') {
            methodFileArea.classList.remove('hidden');
        } else {
            methodUrlArea.classList.remove('hidden');
        }
        generateBtn.disabled = true;
        customPrompt.value = '';
        showState('empty');
    }
    resetBtn.addEventListener('click', handleReset);
    if(document.getElementById('reset-result-btn')) document.getElementById('reset-result-btn').addEventListener('click', handleReset);

    // Template selector
    document.querySelectorAll('.template-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.template-btn').forEach(b => {
                b.className = 'template-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20';
            });
            this.className = 'template-btn active p-2.5 rounded-xl border text-center transition-all duration-200 border-indigo-500 bg-indigo-500/15 text-white shadow-lg shadow-indigo-500/10';
            selectedRatio = this.dataset.ratio;
            updateSliderAspect();
        });
    });

    function updateSliderAspect() {
        const parts = selectedRatio.split(':');
        const ratio = parts[1] / parts[0];
        sliderContainer.style.aspectRatio = selectedRatio.replace(':', '/');
    }
    updateSliderAspect();

    // Style selector
    document.querySelectorAll('.style-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.style-btn').forEach(b => {
                b.classList.remove('border-indigo-500', 'ring-2', 'ring-indigo-500/30');
                b.classList.add('border-white/10');
            });
            this.classList.remove('border-white/10');
            this.classList.add('border-indigo-500', 'ring-2', 'ring-indigo-500/30');
            selectedStyle = this.dataset.style;
        });
    });

    // Show state
    function showState(state) {
        loadingState.classList.add('hidden');
        errorState.classList.add('hidden');
        resultState.classList.add('hidden');
        emptyState.classList.add('hidden');
        if (state === 'loading') loadingState.classList.remove('hidden');
        if (state === 'error') errorState.classList.remove('hidden');
        if (state === 'result') {
            resultState.classList.remove('hidden');
            // Wait for image to load to avoid animation jitters
            if (afterImg.complete) initSliderReveal();
            else afterImg.onload = initSliderReveal;
        }
        if (state === 'empty') emptyState.classList.remove('hidden');
    }

    // Slider Logic
    let isDragging = false;
    function moveSlider(e) {
        if (!isDragging) return;
        // Kill ongoing transition if user starts dragging
        sliderBar.style.transition = '';
        afterImgContainer.style.transition = '';
        
        const rect = sliderContainer.getBoundingClientRect();
        const x = (e.pageX || e.touches[0].pageX) - rect.left - window.scrollX;
        let percent = (x / rect.width) * 100;
        if (percent < 0) percent = 0;
        if (percent > 100) percent = 100;
        
        sliderBar.style.left = percent + '%';
        afterImgContainer.style.clipPath = `inset(0 0 0 ${percent}%)`;
    }

    sliderContainer.addEventListener('mousedown', () => isDragging = true);
    window.addEventListener('mouseup', () => isDragging = false);
    sliderContainer.addEventListener('mousemove', moveSlider);
    sliderContainer.addEventListener('touchstart', () => isDragging = true);
    window.addEventListener('touchend', () => isDragging = false);
    sliderContainer.addEventListener('touchmove', moveSlider);

    function initSliderReveal() {
        // Smooth reveal animation
        sliderBar.style.left = '100%';
        afterImgContainer.style.clipPath = 'inset(0 0 0 100%)';
        
        setTimeout(() => {
            sliderBar.style.transition = 'left 1.5s cubic-bezier(0.19, 1, 0.22, 1)';
            afterImgContainer.style.transition = 'clip-path 1.5s cubic-bezier(0.19, 1, 0.22, 1)';
            sliderBar.style.left = '50%';
            afterImgContainer.style.clipPath = 'inset(0 0 0 50%)';
            
            setTimeout(() => {
                sliderBar.style.transition = '';
                afterImgContainer.style.transition = '';
            }, 1550);
        }, 100);
    }

    // Generate
    generateBtn.addEventListener('click', function() {
        TibaAuth.requireAuth(async function() {
        if (!previewUrl) return;
        generateBtn.disabled = true;
        btnText.textContent = 'Yaratilmoqda... 0%';
        progressBarContainer.classList.remove('hidden');
        progress = 0;
        progressBar.style.width = '0%';
        showState('loading');

        progressInterval = setInterval(() => {
            if (progress < 85) progress += Math.random() * 3;
            else if (progress < 98) progress += 0.1;
            progressBar.style.width = progress + '%';
            btnText.textContent = 'Yaratilmoqda... ' + Math.floor(progress) + '%';
            document.getElementById('btn-icon').className = 'fa-solid fa-spinner fa-spin text-xl';
        }, 400);

        try {
            const response = await fetch('/api/foto-tahrir.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-User-Token': TibaAuth.getToken()
                },
                body: JSON.stringify({
                    productImage: previewUrl,
                    styleId: selectedStyle,
                    customPrompt: customPrompt.value,
                    aspectRatio: selectedRatio,
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
            progress = 100;
            progressBar.style.width = '100%';
            beforeImg.src = previewUrl;
            afterImg.src = resultUrl;
            showState('result');
        } catch (err) {
            if (err.message !== '__nobalance__') {
                errorText.textContent = err.message;
                showState('error');
            }
        } finally {
            clearInterval(progressInterval);
            generateBtn.disabled = !previewUrl;
            btnText.textContent = 'Fonni almashtirish';
            document.getElementById('btn-icon').className = 'fa-solid fa-wand-magic-sparkles text-xl';
            progressBarContainer.classList.add('hidden');
        }
        });
    });

    // Download
    function handleDownload() {
        if (!resultUrl) return;
        const a = document.createElement('a');
        a.href = resultUrl;
        a.download = 'foto-tahrir-' + Date.now() + '.png';
        a.target = '_blank';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }
    document.getElementById('download-btn-main').addEventListener('click', handleDownload);
    document.getElementById('modal-download-btn').addEventListener('click', handleDownload);

    // Modal Logic
    const viewFullBtn = document.getElementById('view-full-btn');
    viewFullBtn.addEventListener('click', () => {
        if (!resultUrl) return;
        modalImage.src = resultUrl;
        imageModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    window.closeFullModal = function() {
        imageModal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Retry
    retryBtn.addEventListener('click', () => showState('empty'));
})();
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
