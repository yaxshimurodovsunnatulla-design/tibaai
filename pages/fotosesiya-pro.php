<?php $pageTitle = 'Fotosesiya PRO – Tiba AI'; ?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="py-12 sm:py-20 relative overflow-hidden">
    <!-- Background -->
    <div class="absolute top-10 left-1/4 w-96 h-96 bg-gray-500/10 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-10 right-1/4 w-80 h-80 bg-indigo-600/10 rounded-full blur-3xl animate-float" style="animation-delay: -3s"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <!-- Back Button -->
        <div class="mb-8 animate-fade-in">
            <a href="/create" class="inline-flex items-center gap-2 text-gray-500 hover:text-white transition-colors text-sm font-bold uppercase tracking-widest group">
                <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                Orqaga
            </a>
        </div>
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/10 mb-4">
                <span class="text-lg"><i class="fa-solid fa-camera"></i></span>
                <span class="text-xs font-medium text-gray-300">AI Photo Session</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3">
                Fotosesiya <span class="bg-clip-text text-transparent bg-gradient-to-r from-gray-300 to-indigo-400">PRO</span>
            </h1>
            <p class="text-gray-400 max-w-xl mx-auto">
                Bitta mahsulot rasmidan AI 8 xil professional reklama suratini yaratadi. Turli burchaklar, fonlar va kompozitsiyalar!
            </p>
        </div>

        <!-- How it works -->
        <div class="glass-card p-4 sm:p-5 mb-8 max-w-3xl mx-auto border border-white/10">
            <div class="flex items-center gap-2 mb-3">
                <span class="text-sm text-indigo-400"><i class="fa-solid fa-crosshairs"></i></span>
                <span class="text-xs font-bold text-indigo-300 uppercase tracking-wider">1 ta rasm = 8 ta professional surat</span>
            </div>
            <div class="grid grid-cols-4 gap-3 text-center">
                <div>
                    <div class="w-9 h-9 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center mx-auto mb-1.5 text-indigo-400">
                        <i class="fa-solid fa-house-chimney"></i>
                    </div>
                    <div class="text-[10px] text-gray-400">Uy interyer</div>
                </div>
                <div>
                    <div class="w-9 h-9 rounded-lg bg-violet-500/10 border border-violet-500/20 flex items-center justify-center mx-auto mb-1.5 text-violet-400">
                        <i class="fa-solid fa-leaf"></i>
                    </div>
                    <div class="text-[10px] text-gray-400">Tabiat foni</div>
                </div>
                <div>
                    <div class="w-9 h-9 rounded-lg bg-amber-500/10 border border-amber-500/20 flex items-center justify-center mx-auto mb-1.5 text-amber-400">
                        <i class="fa-solid fa-clapperboard"></i>
                    </div>
                    <div class="text-[10px] text-gray-400">Studiya surat</div>
                </div>
                <div>
                    <div class="w-9 h-9 rounded-lg bg-rose-500/10 border border-rose-500/20 flex items-center justify-center mx-auto mb-1.5 text-rose-400">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>
                    <div class="text-[10px] text-gray-400">Premium reklama</div>
                </div>
            </div>
        </div>

        <style>
            @keyframes fade-in-up {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in-up {
                animation: fade-in-up 0.3s ease-out forwards;
            }
            #category-options {
                scrollbar-width: thin;
                scrollbar-color: rgba(255,255,255,0.1) transparent;
            }
        </style>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Panel — Controls -->
            <div class="lg:col-span-4 space-y-6 order-1 lg:order-1">
                <div class="glass-card p-6 sm:p-8 space-y-6">
                    <!-- Image Input Section -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex justify-between items-center">
                            <span><i class="fa-solid fa-camera mr-1"></i> Mahsulot rasmi <span class="text-red-400">*</span></span>
                            <div class="flex p-0.5 bg-white/5 rounded-lg border border-white/5 text-[9px]">
                                <button type="button" onclick="switchImageMethod('file')" id="method-file-btn" class="px-2 py-1 rounded-md transition-all bg-indigo-500/20 text-indigo-400">FAYL</button>
                                <button type="button" onclick="switchImageMethod('url')" id="method-url-btn" class="px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300">HAVOLA</button>
                            </div>
                        </label>
                        
                        <div id="image-input-container" class="space-y-4">
                            <!-- File area -->
                            <div id="method-file-area">
                                <label id="upload-label" class="flex flex-col items-center justify-center w-full h-44 border-2 border-dashed border-white/5 rounded-3xl bg-white/[0.03] hover:bg-white/[0.08] hover:border-indigo-500/40 transition-all duration-300 cursor-pointer group relative overflow-hidden">
                                    <!-- Background Glow -->
                                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/0 to-violet-500/0 group-hover:from-indigo-500/5 group-hover:to-violet-500/5 transition-all duration-500"></div>
                                    
                                    <div class="relative z-10 flex flex-col items-center">
                                        <div class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-indigo-500/10 transition-all duration-500 border border-white/5 group-hover:border-indigo-500/20">
                                            <i class="fa-solid fa-cloud-arrow-up text-2xl text-gray-500 group-hover:text-indigo-400"></i>
                                        </div>
                                        <span class="text-sm font-bold text-gray-300 group-hover:text-white transition-colors">Mahsulot rasmini yuklash</span>
                                        <span class="text-[10px] text-gray-500 mt-1 uppercase tracking-tighter">JPG, PNG • MAKS. 6TA RASM</span>
                                    </div>
                                    <input id="file-input" type="file" accept="image/*" multiple class="hidden" />
                                </label>
                            </div>

                            <!-- URL area -->
                            <div id="method-url-area" class="hidden">
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <input type="url" id="image-url-input" placeholder="https://example.com/item.jpg" class="input-field pl-10 h-12 text-sm" />
                                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500"><i class="fa-solid fa-link"></i></div>
                                    </div>
                                    <button type="button" onclick="addUrlImage()" class="px-4 h-12 bg-indigo-500 text-white rounded-xl font-bold text-xs hover:bg-indigo-600 transition-colors">QO'SHISH</button>
                                </div>
                                <p class="text-[10px] text-gray-500 mt-2">Mahsulot havolasini kiriting va Qo'shishni bosing.</p>
                            </div>

                            <!-- Multi-image Preview Grid -->
                            <div id="preview-grid" class="grid grid-cols-3 gap-2 hidden">
                                <!-- Previews will be injected here -->
                            </div>
                        </div>
                    </div>

                    <!-- Product Category -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">
                            <i class="fa-solid fa-box mr-1 text-indigo-400"></i> Mahsulot turi
                        </label>
                        <div class="relative" id="custom-category-dropdown">
                            <div class="w-full input-field flex items-center justify-between px-4 py-3.5 text-sm text-left group transition-all border-white/10 hover:border-white/20 bg-white/5 rounded-2xl cursor-pointer" id="category-trigger">
                                <div class="flex items-center gap-3" id="category-selected-label">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-400 shadow-sm shadow-indigo-500/5">
                                        <i class="fa-solid fa-gem"></i>
                                    </div>
                                    <span class="font-medium text-gray-300">Umumiy mahsulot</span>
                                </div>
                                <i class="fa-solid fa-chevron-down text-gray-500 group-hover:text-white transition-transform duration-300 mr-1" id="category-arrow"></i>
                            </div>
                            
                            <div class="absolute z-50 mt-2 w-full glass-card border border-white/20 hidden animate-fade-in-up shadow-2xl overflow-hidden" id="category-options">
                                <div class="p-1.5 max-h-72 overflow-y-auto scrollbar-thin scrollbar-thumb-white/10 bg-[#0a0a0f] backdrop-blur-2xl">
                                    <div class="category-option flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 cursor-pointer transition-all duration-200 group" data-value="general" data-icon="fa-gem" data-color="text-indigo-400" data-bg="bg-indigo-500/10" data-label="Umumiy mahsulot">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-400 group-hover:scale-110 transition-transform"><i class="fa-solid fa-gem"></i></div>
                                        <span class="text-sm font-medium text-gray-400 group-hover:text-white">Umumiy mahsulot</span>
                                    </div>
                                    <div class="category-option flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 cursor-pointer transition-all duration-200 group" data-value="electronics" data-icon="fa-laptop" data-color="text-blue-400" data-bg="bg-blue-500/10" data-label="Elektronika">
                                        <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400 group-hover:scale-110 transition-transform"><i class="fa-solid fa-laptop"></i></div>
                                        <span class="text-sm font-medium text-gray-400 group-hover:text-white">Elektronika</span>
                                    </div>
                                    <div class="category-option flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 cursor-pointer transition-all duration-200 group" data-value="fashion" data-icon="fa-shirt" data-color="text-rose-400" data-bg="bg-rose-500/10" data-label="Kiyim / Moda">
                                        <div class="w-8 h-8 rounded-lg bg-rose-500/10 flex items-center justify-center text-rose-400 group-hover:scale-110 transition-transform"><i class="fa-solid fa-shirt"></i></div>
                                        <span class="text-sm font-medium text-gray-400 group-hover:text-white">Kiyim / Moda</span>
                                    </div>
                                    <div class="category-option flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 cursor-pointer transition-all duration-200 group" data-value="beauty" data-icon="fa-wand-magic-sparkles" data-color="text-pink-400" data-bg="bg-pink-500/10" data-label="Go'zallik / Kosmetika">
                                        <div class="w-8 h-8 rounded-lg bg-pink-500/10 flex items-center justify-center text-pink-400 group-hover:scale-110 transition-transform"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                                        <span class="text-sm font-medium text-gray-400 group-hover:text-white">Go'zallik / Kosmetika</span>
                                    </div>
                                    <div class="category-option flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 cursor-pointer transition-all duration-200 group" data-value="food" data-icon="fa-utensils" data-color="text-orange-400" data-bg="bg-orange-500/10" data-label="Oziq-ovqat">
                                        <div class="w-8 h-8 rounded-lg bg-orange-500/10 flex items-center justify-center text-orange-400 group-hover:scale-110 transition-transform"><i class="fa-solid fa-utensils"></i></div>
                                        <span class="text-sm font-medium text-gray-400 group-hover:text-white">Oziq-ovqat</span>
                                    </div>
                                    <div class="category-option flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 cursor-pointer transition-all duration-200 group" data-value="furniture" data-icon="fa-chair" data-color="text-amber-400" data-bg="bg-amber-500/10" data-label="Mebel / Uy buyumlari">
                                        <div class="w-8 h-8 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-400 group-hover:scale-110 transition-transform"><i class="fa-solid fa-chair"></i></div>
                                        <span class="text-sm font-medium text-gray-400 group-hover:text-white">Mebel / Uy buyumlari</span>
                                    </div>
                                    <div class="category-option flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 cursor-pointer transition-all duration-200 group" data-value="sports" data-icon="fa-dumbbell" data-color="text-emerald-400" data-bg="bg-emerald-500/10" data-label="Sport / Fitnes">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400 group-hover:scale-110 transition-transform"><i class="fa-solid fa-dumbbell"></i></div>
                                        <span class="text-sm font-medium text-gray-400 group-hover:text-white">Sport / Fitnes</span>
                                    </div>
                                    <div class="category-option flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 cursor-pointer transition-all duration-200 group" data-value="toys" data-icon="fa-gamepad" data-color="text-cyan-400" data-bg="bg-cyan-500/10" data-label="O'yinchoqlar / Bolalar">
                                        <div class="w-8 h-8 rounded-lg bg-cyan-500/10 flex items-center justify-center text-cyan-400 group-hover:scale-110 transition-transform"><i class="fa-solid fa-gamepad"></i></div>
                                        <span class="text-sm font-medium text-gray-400 group-hover:text-white">O'yinchoqlar / Bolalar</span>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="product-category" value="general">
                        </div>
                    </div>

                    <!-- Format -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">📐 Format</label>
                        <div class="grid grid-cols-3 gap-2" id="ratio-selector">
                            <button data-ratio="3:4" class="ratio-btn active p-2 rounded-xl border text-center transition-all duration-200 border-indigo-500 bg-indigo-500/15 text-white shadow-lg shadow-indigo-500/10">
                                <div class="text-[10px] font-bold uppercase">Uzum</div>
                                <div class="text-[8px] opacity-60 font-bold">3:4</div>
                            </button>
                            <button data-ratio="1:1" class="ratio-btn p-2 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold uppercase">Kvadrat</div>
                                <div class="text-[8px] opacity-60 font-bold">1:1</div>
                            </button>
                            <button data-ratio="4:3" class="ratio-btn p-2 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold uppercase">Landscape</div>
                                <div class="text-[8px] opacity-60 font-bold">4:3</div>
                            </button>
                        </div>
                    </div>

                    <!-- Generate -->
                    <button id="generate-btn" disabled class="btn-primary w-full h-16 text-lg font-extrabold disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                        <span id="btn-text"><i class="fa-solid fa-camera mr-2"></i>8 ta surat yaratish</span>
                    </button>

                    <!-- Progress -->
                    <div id="progress-bar-container" class="hidden">
                        <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden">
                            <div id="progress-bar" class="h-full bg-gradient-to-r from-gray-500 via-indigo-500 to-violet-500 rounded-full transition-all duration-300 ease-out shadow-[0_0_15px_rgba(99,102,241,0.5)]" style="width: 0%"></div>
                        </div>
                        <div class="flex justify-between mt-1.5">
                            <span id="progress-label" class="text-[10px] text-gray-500">Boshlanmoqda...</span>
                            <span id="progress-count" class="text-[10px] text-indigo-400 font-bold">0 / 8</span>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="glass-card p-3 border border-amber-500/10">
                        <div class="flex items-start gap-2">
                            <span class="text-sm text-amber-500"><i class="fa-solid fa-bolt"></i></span>
                            <div class="text-[11px] text-gray-400 leading-relaxed">
                                8 ta turli xil rasm ketma-ket yaratiladi. Jarayon <span class="text-white font-semibold">2-5 daqiqa</span> davom etishi mumkin. Sahifani yopmang!
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel — Gallery -->
            <div class="lg:col-span-8 order-2 lg:order-2">
                <div class="glass-card p-6 sm:p-8 min-h-[500px] sticky top-24">
                    <!-- Loading -->
                    <div id="loading-state" class="animate-fade-in hidden">
                        <div class="text-center mb-6">
                            <h3 class="text-lg font-bold text-white mb-2"><i class="fa-solid fa-camera mr-2"></i> Fotosesiya jarayoni...</h3>
                            <p class="text-sm text-gray-400">AI har bir kadr uchun noyob kompozitsiya yaratmoqda</p>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3" id="loading-grid">
                            <div class="aspect-[3/4] rounded-xl bg-white/5 border border-white/10 flex flex-col items-center justify-center animate-pulse" data-slot="0">
                                <span class="text-xl mb-1 text-gray-500"><i class="fa-solid fa-image"></i></span>
                                <span class="text-[9px] text-gray-500 font-bold">Kadr 1</span>
                            </div>
                            <div class="aspect-[3/4] rounded-xl bg-white/5 border border-white/10 flex flex-col items-center justify-center opacity-40" data-slot="1">
                                <span class="text-xl mb-1 text-gray-500"><i class="fa-solid fa-image"></i></span>
                                <span class="text-[9px] text-gray-500 font-bold">Kadr 2</span>
                            </div>
                            <div class="aspect-[3/4] rounded-xl bg-white/5 border border-white/10 flex flex-col items-center justify-center opacity-40" data-slot="2">
                                <span class="text-xl mb-1 text-gray-500"><i class="fa-solid fa-image"></i></span>
                                <span class="text-[9px] text-gray-500 font-bold">Kadr 3</span>
                            </div>
                            <div class="aspect-[3/4] rounded-xl bg-white/5 border border-white/10 flex flex-col items-center justify-center opacity-40" data-slot="3">
                                <span class="text-xl mb-1 text-gray-500"><i class="fa-solid fa-image"></i></span>
                                <span class="text-[9px] text-gray-500 font-bold">Kadr 4</span>
                            </div>
                            <div class="aspect-[3/4] rounded-xl bg-white/5 border border-white/10 flex flex-col items-center justify-center opacity-40" data-slot="4">
                                <span class="text-xl mb-1 text-gray-500"><i class="fa-solid fa-image"></i></span>
                                <span class="text-[9px] text-gray-500 font-bold">Kadr 5</span>
                            </div>
                            <div class="aspect-[3/4] rounded-xl bg-white/5 border border-white/10 flex flex-col items-center justify-center opacity-40" data-slot="5">
                                <span class="text-xl mb-1 text-gray-500"><i class="fa-solid fa-image"></i></span>
                                <span class="text-[9px] text-gray-500 font-bold">Kadr 6</span>
                            </div>
                            <div class="aspect-[3/4] rounded-xl bg-white/5 border border-white/10 flex flex-col items-center justify-center opacity-40" data-slot="6">
                                <span class="text-xl mb-1 text-gray-500"><i class="fa-solid fa-image"></i></span>
                                <span class="text-[9px] text-gray-500 font-bold">Kadr 7</span>
                            </div>
                            <div class="aspect-[3/4] rounded-xl bg-white/5 border border-white/10 flex flex-col items-center justify-center opacity-40" data-slot="7">
                                <span class="text-xl mb-1 text-gray-500"><i class="fa-solid fa-image"></i></span>
                                <span class="text-[9px] text-gray-500 font-bold">Kadr 8</span>
                            </div>
                        </div>
                    </div>

                    <!-- Error -->
                    <div id="error-state" class="text-center animate-fade-in hidden flex flex-col items-center justify-center min-h-[400px]">
                        <div class="w-16 h-16 rounded-2xl bg-red-500/10 flex items-center justify-center mx-auto mb-4">
                            <span class="text-3xl text-red-500"><i class="fa-solid fa-circle-xmark"></i></span>
                        </div>
                        <h3 class="text-lg font-semibold text-red-400 mb-2">Xatolik</h3>
                        <p id="error-text" class="text-sm text-gray-400 mb-4"></p>
                        <button id="retry-btn" class="btn-secondary text-sm px-6 py-2">Qayta urinish</button>
                    </div>

                    <!-- Result Gallery -->
                    <div id="result-state" class="animate-fade-in hidden">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-white"><i class="fa-solid fa-camera mr-2"></i> Fotosesiya natijasi</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Rasmni bosib kattalashtiring yoki yuklab oling</p>
                            </div>
                            <div class="flex gap-2">
                                <button id="download-all-btn" class="btn-primary text-xs px-4 py-2">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                    Hammasini yuklab olish
                                </button>
                                <button id="reset-result-btn" class="btn-secondary text-xs px-4 py-2" title="Qayatadan">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3" id="result-grid"></div>
                    </div>

                    <!-- Empty -->
                    <div id="empty-state" class="text-center flex flex-col items-center justify-center min-h-[400px]">
                        <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-gray-700/30 to-indigo-600/20 border border-white/10 flex items-center justify-center mx-auto mb-6">
                            <span class="text-4xl text-indigo-400"><i class="fa-solid fa-camera"></i></span>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">8 ta professional reklama surati</h3>
                        <p class="text-sm text-gray-500 max-w-md mx-auto mb-6">
                            Bitta mahsulot rasmidan AI 8 xil professional reklama suratini yaratadi: turli fonlar, burchaklar va kompozitsiyalar.
                        </p>
                        <!-- Sample grid -->
                        <div class="grid grid-cols-4 gap-2 max-w-sm mx-auto">
                            <div class="aspect-[3/4] rounded-lg bg-gradient-to-br from-indigo-600/10 to-indigo-600/5 border border-indigo-500/10 flex items-center justify-center text-lg text-indigo-400"><i class="fa-solid fa-house-chimney"></i></div>
                            <div class="aspect-[3/4] rounded-lg bg-gradient-to-br from-emerald-600/10 to-emerald-600/5 border border-emerald-500/10 flex items-center justify-center text-lg text-emerald-400"><i class="fa-solid fa-leaf"></i></div>
                            <div class="aspect-[3/4] rounded-lg bg-gradient-to-br from-amber-600/10 to-amber-600/5 border border-amber-500/10 flex items-center justify-center text-lg text-amber-400"><i class="fa-solid fa-clapperboard"></i></div>
                            <div class="aspect-[3/4] rounded-lg bg-gradient-to-br from-rose-600/10 to-rose-600/5 border border-rose-500/10 flex items-center justify-center text-lg text-rose-400"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                            <div class="aspect-[3/4] rounded-lg bg-gradient-to-br from-cyan-600/10 to-cyan-600/5 border border-cyan-500/10 flex items-center justify-center text-lg text-cyan-400"><i class="fa-solid fa-sun"></i></div>
                            <div class="aspect-[3/4] rounded-lg bg-gradient-to-br from-purple-600/10 to-purple-600/5 border border-purple-500/10 flex items-center justify-center text-lg text-purple-400"><i class="fa-solid fa-moon"></i></div>
                            <div class="aspect-[3/4] rounded-lg bg-gradient-to-br from-pink-600/10 to-pink-600/5 border border-pink-500/10 flex items-center justify-center text-lg text-pink-400"><i class="fa-solid fa-gem"></i></div>
                            <div class="aspect-[3/4] rounded-lg bg-gradient-to-br from-orange-600/10 to-orange-600/5 border border-orange-500/10 flex items-center justify-center text-lg text-orange-400"><i class="fa-solid fa-fire"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lightbox Modal -->
<div id="lightbox-modal" class="fixed inset-0 bg-black/90 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <button id="lightbox-close" class="absolute top-4 right-4 w-10 h-10 bg-white/10 backdrop-blur rounded-full flex items-center justify-center text-white hover:bg-white/20 transition-colors text-xl z-10">✕</button>
    <div class="relative max-w-4xl max-h-[90vh] w-full flex items-center justify-center">
        <img id="lightbox-img" src="" alt="Rasm" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain" />
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-3">
            <button id="lightbox-download" class="btn-primary px-5 py-2.5 text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Yuklab olish
            </button>
        </div>
    </div>
</div>

<script>
(function(){
    let productImages = [];
    let selectedRatio = '3:4';
    let generatedImages = [];
    let progress = 0;
    let currentShotIndex = 0;

    const fileInput = document.getElementById('file-input');
    const imageUrlInput = document.getElementById('image-url-input');
    const uploadLabel = document.getElementById('upload-label');
    const methodFileArea = document.getElementById('method-file-area');
    const methodUrlArea = document.getElementById('method-url-area');
    const methodFileBtn = document.getElementById('method-file-btn');
    const methodUrlBtn = document.getElementById('method-url-btn');

    const previewGrid = document.getElementById('preview-grid');
    const generateBtn = document.getElementById('generate-btn');
    const btnText = document.getElementById('btn-text');
    const progressBarContainer = document.getElementById('progress-bar-container');
    const progressBar = document.getElementById('progress-bar');
    const progressLabel = document.getElementById('progress-label');
    const progressCount = document.getElementById('progress-count');
    const productCategoryInput = document.getElementById('product-category');

    // Custom Category Dropdown Logic
    const categoryTrigger = document.getElementById('category-trigger');
    const categoryOptions = document.getElementById('category-options');
    const categorySelectedLabel = document.getElementById('category-selected-label');
    const categoryArrow = document.getElementById('category-arrow');

    categoryTrigger.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        const isHidden = categoryOptions.classList.contains('hidden');
        
        // Close all other dropdowns if any (safety)
        categoryOptions.classList.toggle('hidden');
        categoryArrow.style.transform = isHidden ? 'rotate(180deg)' : '';
    });

    document.querySelectorAll('.category-option').forEach(option => {
        option.addEventListener('click', function() {
            const val = this.dataset.value;
            const icon = this.dataset.icon;
            const label = this.dataset.label;
            const color = this.dataset.color;
            const bg = this.dataset.bg;

            productCategoryInput.value = val;
            categorySelectedLabel.innerHTML = `
                <div class="w-8 h-8 rounded-lg ${bg} flex items-center justify-center ${color} shadow-sm">
                    <i class="fa-solid ${icon}"></i>
                </div>
                <span class="font-medium text-gray-300">${label}</span>
            `;

            categoryOptions.classList.add('hidden');
            categoryArrow.style.transform = '';
        });
    });

    document.addEventListener('click', () => {
        categoryOptions.classList.add('hidden');
        categoryArrow.style.transform = '';
    });

    let currentMethod = 'file';

    // Switch methods
    window.switchImageMethod = function(method) {
        currentMethod = method;
        if (method === 'file') {
            methodFileArea.classList.remove('hidden');
            methodUrlArea.classList.add('hidden');
            methodFileBtn.className = 'px-2 py-1 rounded-md transition-all bg-indigo-500/20 text-indigo-400';
            methodUrlBtn.className = 'px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300';
        } else {
            methodFileArea.classList.add('hidden');
            methodUrlArea.classList.remove('hidden');
            methodUrlBtn.className = 'px-2 py-1 rounded-md transition-all bg-indigo-500/20 text-indigo-400';
            methodFileBtn.className = 'px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300';
        }
    };

    function checkReady() {
        generateBtn.disabled = productImages.length === 0;
    }

    // URL input logic
    window.addUrlImage = function() {
        const url = imageUrlInput.value.trim();
        if (url && (url.startsWith('http://') || url.startsWith('https://'))) {
            if (productImages.length >= 6) {
                alert('Maksimal 6ta rasm yuklash mumkin');
                return;
            }
            productImages.push(url);
            imageUrlInput.value = '';
            updatePreviewGrid();
            checkReady();
        }
    };

    // Upload logic
    fileInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        let remaining = 6 - productImages.length;
        
        files.slice(0, remaining).forEach(file => {
            const reader = new FileReader();
            reader.onload = function() {
                productImages.push(reader.result);
                updatePreviewGrid();
                checkReady();
            };
            reader.readAsDataURL(file);
        });
        fileInput.value = '';
    });

    window.removeImage = function(index) {
        productImages.splice(index, 1);
        updatePreviewGrid();
        checkReady();
    };

    function updatePreviewGrid() {
        if (productImages.length === 0) {
            previewGrid.classList.add('hidden');
            return;
        }
        
        previewGrid.classList.remove('hidden');
        previewGrid.innerHTML = productImages.map((img, idx) => `
            <div class="relative group aspect-square rounded-xl overflow-hidden border border-white/10 bg-black/20">
                <img src="${img}" class="w-full h-full object-cover" />
                <button onclick="removeImage(${idx})" class="absolute top-1 right-1 w-5 h-5 bg-black/60 backdrop-blur text-white rounded-full flex items-center justify-center text-[10px] hover:bg-red-500 transition-colors shadow-lg">✕</button>
            </div>
        `).join('');
    }

    // Ratio selector
    document.querySelectorAll('.ratio-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.ratio-btn').forEach(b => {
                b.className = 'ratio-btn p-2 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20';
            });
            this.className = 'ratio-btn active p-2 rounded-xl border text-center transition-all duration-200 border-indigo-500 bg-indigo-500/15 text-white shadow-lg shadow-indigo-500/10';
            selectedRatio = this.dataset.ratio;
        });
    });

    // States
    function showState(state) {
        ['loading-state','error-state','result-state','empty-state'].forEach(id => {
            document.getElementById(id).classList.add('hidden');
        });
        const el = document.getElementById(state + '-state');
        if (el) el.classList.remove('hidden');
    }

    // The 8 shot scenarios
    const shots = [
        { name: 'Studiya — Oq fon', icon: '<i class="fa-solid fa-clapperboard"></i>', prompt: 'Clean white studio background with perfect professional lighting. Product centered, hero shot composition. Minimalist, e-commerce standard.' },
        { name: 'Lifestyle — Uy ichida', icon: '<i class="fa-solid fa-house-chimney"></i>', prompt: 'Modern home interior setting — stylish living room or kitchen. Product placed naturally in a lifestyle context. Warm, inviting atmosphere with soft natural light from windows.' },
        { name: 'Tabiat — Tabiiy fon', icon: '<i class="fa-solid fa-leaf"></i>', prompt: 'Beautiful natural outdoor setting — green plants, flowers, or garden. Product placed on natural surface (wood, stone). Soft golden hour sunlight. Fresh, organic, eco-friendly feeling.' },
        { name: 'Premium — Qora fon', icon: '<i class="fa-solid fa-moon"></i>', prompt: 'Dark dramatic premium background with moody lighting. Product spotlight illuminated. Luxury brand aesthetic with subtle reflections. Rich shadows and cinematic feel.' },
        { name: 'Rangdor — Gradient fon', icon: '<i class="fa-solid fa-palette"></i>', prompt: 'Vibrant colorful gradient background (purple to blue, or orange to pink). Modern, trendy, Instagram-worthy composition. Product floating or on reflective surface. Clean and eye-catching.' },
        { name: 'Tekstura — Material fon', icon: '<i class="fa-solid fa-gem"></i>', prompt: 'Textured surface background — marble, concrete, fabric, or metal. Product artistically placed at an angle. Close-up with shallow depth of field. Magazine advertisement quality.' },
        { name: 'Mavsumiy — Reklama', icon: '<i class="fa-solid fa-sun"></i>', prompt: 'Seasonal/holiday themed promotional photo. Festive decorations, seasonal elements. Product as the hero with complementary seasonal props. Commercial advertisement style.' },
        { name: 'Flat Lay — Yuqoridan', icon: '<i class="fa-solid fa-vector-square"></i>', prompt: 'Top-down flat lay composition. Product centered with complementary props arranged around it (plants, accessories, tools relevant to the product). Clean organized overhead view. Social media optimized.' },
    ];

    // Generate all 8 photos one by one
    generateBtn.addEventListener('click', function() {
        TibaAuth.requireAuth(async function() {
        if (productImages.length === 0) return;

        generateBtn.disabled = true;
        generatedImages = [];
        currentShotIndex = 0;
        showState('loading');
        progressBarContainer.classList.remove('hidden');

        // Reset loading grid
        document.querySelectorAll('#loading-grid > div').forEach((slot, i) => {
            slot.classList.remove('border-green-500/30', 'border-indigo-500/30');
            slot.classList.add('border-white/10');
            slot.innerHTML = `<span class="text-xl mb-1 text-gray-500"><i class="fa-solid fa-image"></i></span><span class="text-[9px] text-gray-500 font-bold">Kadr ${i+1}</span>`;
            if (i > 0) slot.classList.add('opacity-40');
            else { slot.classList.remove('opacity-40'); slot.classList.add('animate-pulse'); }
        });

        const category = productCategoryInput.value;

        for (let i = 0; i < shots.length; i++) {
            currentShotIndex = i;
            const shot = shots[i];
            progress = ((i) / 8) * 100;
            progressBar.style.width = progress + '%';
            progressLabel.textContent = `${shot.icon} ${shot.name}`;
            progressCount.textContent = `${i} / 8`;
            btnText.innerHTML = `<i class="fa-solid fa-camera mr-2"></i>Yaratilmoqda... ${i}/8`;

            // Highlight current slot
            const slot = document.querySelector(`#loading-grid > div[data-slot="${i}"]`);
            if (slot) {
                slot.classList.remove('opacity-40');
                slot.classList.add('animate-pulse', 'border-indigo-500/30');
                slot.innerHTML = `<span class="text-xl mb-1">${shot.icon}</span><span class="text-[9px] text-indigo-400 font-bold">${shot.name}</span>`;
            }

            try {
                const response = await fetch('/api/fotosesiya-pro.php', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-User-Token': TibaAuth.getToken()
                    },
                    body: JSON.stringify({
                        productImages: productImages,
                        shotPrompt: shot.prompt,
                        shotName: shot.name,
                        category: category,
                        aspectRatio: selectedRatio,
                        shotIndex: i - 1,
                    })
                });

                const text = await response.text();
                let data;
                try { data = JSON.parse(text); } catch { data = { error: 'Parse error' }; }

                if (data.auth_required) {
                    TibaAuth.showModal();
                    showState('error');
                    errorText.textContent = 'Avval tizimga kiring';
                    return;
                }
                if (data.insufficient_balance) {
                    showNoBalance(data.cost, data.balance);
                    return;
                }

                if (data.success && data.imageUrl) {
                    if (data.balance !== undefined) TibaAuth.updateBalance(data.balance);
                    generatedImages.push({ url: data.imageUrl, name: shot.name, icon: shot.icon });
                    // Update slot to show completed
                    if (slot) {
                        slot.classList.remove('animate-pulse', 'border-indigo-500/30');
                        slot.classList.add('border-green-500/30');
                        slot.innerHTML = `<img src="${data.imageUrl}" alt="${shot.name}" class="w-full h-full object-cover rounded-xl" />`;
                    }
                } else {
                    generatedImages.push({ url: null, name: shot.name, icon: shot.icon, error: data.error || 'Xatolik' });
                    if (slot) {
                        slot.classList.remove('animate-pulse', 'border-indigo-500/30');
                        slot.classList.add('border-red-500/30');
                        slot.innerHTML = `<span class="text-xl mb-1">❌</span><span class="text-[9px] text-red-400 font-bold">Xatolik</span>`;
                    }
                }
            } catch (err) {
                generatedImages.push({ url: null, name: shot.name, icon: shot.icon, error: err.message });
            }
        }

        // Complete
        progress = 100;
        progressBar.style.width = '100%';
        progressLabel.textContent = 'Tayyor!';
        progressCount.textContent = `${generatedImages.filter(i => i.url).length} / 8 muvaffaqiyatli`;
        btnText.innerHTML = '<i class="fa-solid fa-camera mr-2"></i>8 ta surat yaratish';
        progressBarContainer.classList.add('hidden');
        checkReady();

        // Build result gallery
        buildResultGallery();
        showState('result');
        });
    });

    function buildResultGallery() {
        const grid = document.getElementById('result-grid');
        grid.innerHTML = '';
        generatedImages.forEach((img, i) => {
            const div = document.createElement('div');
            if (img.url) {
                div.className = 'relative group cursor-pointer';
                div.innerHTML = `
                    <img src="${img.url}" alt="${img.name}" class="w-full aspect-[3/4] object-cover rounded-xl border border-white/10 hover:border-indigo-500/30 transition-all" />
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent rounded-b-xl p-2.5">
                        <div class="text-[10px] font-bold text-white">${img.icon} ${img.name}</div>
                    </div>
                    <div class="absolute inset-0 bg-black/50 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <div class="text-white text-sm font-bold">🔍 Kattalashtirish</div>
                    </div>
                `;
                div.addEventListener('click', () => openLightbox(img.url, img.name));
            } else {
                div.className = 'aspect-[3/4] rounded-xl bg-red-500/5 border border-red-500/20 flex flex-col items-center justify-center p-2';
                div.innerHTML = `<span class="text-xl mb-1">❌</span><span class="text-[9px] text-red-400 text-center">${img.name}<br>${img.error || ''}</span>`;
            }
            grid.appendChild(div);
        });
    }

    // Lightbox
    const lightboxModal = document.getElementById('lightbox-modal');
    const lightboxImg = document.getElementById('lightbox-img');
    let currentLightboxUrl = null;

    function openLightbox(url, name) {
        currentLightboxUrl = url;
        lightboxImg.src = url;
        lightboxImg.alt = name;
        lightboxModal.classList.remove('hidden');
    }

    document.getElementById('lightbox-close').addEventListener('click', () => lightboxModal.classList.add('hidden'));
    lightboxModal.addEventListener('click', (e) => { if (e.target === lightboxModal) lightboxModal.classList.add('hidden'); });

    document.getElementById('lightbox-download').addEventListener('click', () => {
        if (!currentLightboxUrl) return;
        downloadImage(currentLightboxUrl);
    });

    // Download helpers
    function downloadImage(url) {
        const a = document.createElement('a');
        a.href = url;
        a.download = 'fotosesiya-' + Date.now() + '.png';
        a.target = '_blank';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    document.getElementById('download-all-btn').addEventListener('click', () => {
        generatedImages.filter(i => i.url).forEach((img, idx) => {
            setTimeout(() => downloadImage(img.url), idx * 300);
        });
    });

    document.getElementById('reset-result-btn').addEventListener('click', () => {
        generatedImages = [];
        showState('empty');
    });

    // Retry
    document.getElementById('retry-btn').addEventListener('click', () => showState('empty'));
})();
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
