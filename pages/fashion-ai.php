<?php $pageTitle = 'Fashion AI – Tiba AI'; ?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="py-12 sm:py-20 relative overflow-hidden">
    <!-- Background -->
    <div class="absolute top-10 left-1/4 w-96 h-96 bg-rose-600/10 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-10 right-1/4 w-80 h-80 bg-pink-600/10 rounded-full blur-3xl animate-float" style="animation-delay: -3s"></div>

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
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-500/10 border border-rose-500/20 mb-4">
                <span class="text-lg"><i class="fa-solid fa-shirt"></i></span>
                <span class="text-xs font-medium text-rose-300">Virtual Try-On AI</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3">
                Fashion <span class="bg-clip-text text-transparent bg-gradient-to-r from-rose-400 to-pink-400">AI</span>
            </h1>
            <p class="text-gray-400 max-w-xl mx-auto">
                Kiyim rasmini yuklang — AI uni professional modelga kiygizib ko'rsatadi. Virtual fotosesiya!
            </p>
        </div>

        <!-- How it works -->
        <div class="glass-card p-4 sm:p-5 mb-8 max-w-3xl mx-auto border border-rose-500/10">
            <div class="flex items-center gap-2 mb-3">
                <span class="text-sm"><i class="fa-solid fa-circle-info text-rose-400"></i></span>
                <span class="text-xs font-bold text-rose-300 uppercase tracking-wider">Qanday ishlaydi?</span>
            </div>
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center mx-auto mb-2">
                        <span class="text-lg"><i class="fa-solid fa-image-portrait"></i></span>
                    </div>
                    <div class="text-xs text-gray-400"><span class="text-white font-semibold">1.</span> Kiyim rasmini yuklang</div>
                </div>
                <div>
                    <div class="w-10 h-10 rounded-xl bg-pink-500/10 border border-pink-500/20 flex items-center justify-center mx-auto mb-2">
                        <span class="text-lg"><i class="fa-solid fa-user-tie"></i></span>
                    </div>
                    <div class="text-xs text-gray-400"><span class="text-white font-semibold">2.</span> Model turini tanlang</div>
                </div>
                <div>
                    <div class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center mx-auto mb-2">
                        <span class="text-lg"><i class="fa-solid fa-wand-magic-sparkles"></i></span>
                    </div>
                    <div class="text-xs text-gray-400"><span class="text-white font-semibold">3.</span> AI natijani yaratadi</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Panel — Controls -->
            <div class="lg:col-span-5 space-y-6 order-1 lg:order-1">
                <div class="glass-card p-6 sm:p-8 space-y-6">
                    <!-- Clothing Image Section -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex justify-between items-center">
                            <span><i class="fa-solid fa-shirt mr-1"></i> Kiyim rasmi <span class="text-red-400">*</span></span>
                            <div class="flex p-0.5 bg-white/5 rounded-lg border border-white/5 text-[9px]">
                                <button type="button" onclick="switchImageMethod('file')" id="method-file-btn" class="px-2 py-1 rounded-md transition-all bg-rose-500/20 text-rose-400">FAYL</button>
                                <button type="button" onclick="switchImageMethod('url')" id="method-url-btn" class="px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300">HAVOLA</button>
                            </div>
                        </label>
                        
                        <div id="clothing-input-container" class="space-y-4">
                            <!-- File area -->
                            <div id="method-file-area">
                                <label id="clothing-upload-label" class="flex flex-col items-center justify-center w-full h-44 border-2 border-dashed border-white/5 rounded-3xl bg-white/[0.03] hover:bg-white/[0.08] hover:border-rose-500/40 transition-all duration-300 cursor-pointer group relative overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-br from-rose-500/0 to-pink-500/0 group-hover:from-rose-500/5 group-hover:to-pink-500/5 transition-all duration-500"></div>
                                    <div class="relative z-10 flex flex-col items-center">
                                        <div class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-rose-500/10 transition-all duration-500 border border-white/5 group-hover:border-rose-500/20">
                                            <i class="fa-solid fa-cloud-arrow-up text-2xl text-gray-500 group-hover:text-rose-400"></i>
                                        </div>
                                        <span class="text-sm font-bold text-gray-300 group-hover:text-white transition-colors">Kiyim rasmini yuklash</span>
                                        <span class="text-[10px] text-gray-500 mt-1 uppercase tracking-tighter">JPG, PNG • 3tagacha rasm</span>
                                    </div>
                                    <input id="clothing-file-input" type="file" accept="image/*" multiple class="hidden" />
                                </label>
                            </div>

                            <!-- URL area -->
                            <div id="method-url-area" class="hidden">
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <input type="url" id="clothing-url-input" placeholder="https://example.com/clothing.jpg" class="input-field pl-10 h-12 text-sm" />
                                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500"><i class="fa-solid fa-link"></i></div>
                                    </div>
                                    <button type="button" onclick="addUrlImage()" class="px-4 h-12 bg-rose-500 text-white rounded-xl font-bold text-xs hover:bg-rose-600 transition-colors">QO'SHISH</button>
                                </div>
                                <p class="text-[10px] text-gray-500 mt-2">Kiyim havolasini kiriting va Qo'shishni bosing.</p>
                            </div>

                            <!-- Multi-image Preview Grid -->
                            <div id="clothing-preview-grid" class="grid grid-cols-3 gap-2 hidden">
                                <!-- Previews will be injected here -->
                            </div>
                        </div>
                    </div>

                    <!-- Model Selection & Image -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex justify-between items-center">
                            <span><i class="fa-solid fa-user-tie mr-1"></i> Model <span class="text-red-400">*</span></span>
                            <div class="flex p-0.5 bg-white/5 rounded-lg border border-white/5 text-[9px]">
                                <button type="button" onclick="switchModelMethod('preset')" id="model-method-preset-btn" class="px-2 py-1 rounded-md transition-all bg-rose-500/20 text-rose-400">TANLASH</button>
                                <button type="button" onclick="switchModelMethod('upload')" id="model-method-upload-btn" class="px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300">YUKLASH</button>
                            </div>
                        </label>
                        
                        <!-- Preset Models -->
                        <div id="model-preset-area" class="grid grid-cols-2 gap-2">
                            <button data-model="woman" class="model-btn active relative overflow-hidden rounded-xl border p-3 h-20 flex flex-col items-center justify-center gap-1.5 transition-all border-rose-500 bg-rose-500/15 text-white ring-2 ring-rose-500/30">
                                <div class="text-2xl"><i class="fa-solid fa-person-dress"></i></div>
                                <div class="font-bold text-[10px] uppercase tracking-tight">Ayol model</div>
                            </button>
                            <button data-model="man" class="model-btn relative overflow-hidden rounded-xl border p-3 h-20 flex flex-col items-center justify-center gap-1.5 transition-all border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-2xl"><i class="fa-solid fa-person"></i></div>
                                <div class="font-bold text-[10px] uppercase tracking-tight">Erkak model</div>
                            </button>
                        </div>

                        <!-- Model Image Upload -->
                        <div id="model-upload-area" class="space-y-4 hidden">
                            <div class="flex p-0.5 bg-white/5 rounded-lg border border-white/5 text-[9px] w-fit mb-2">
                                <button type="button" onclick="switchModelSubMethod('file')" id="model-sub-file-btn" class="px-2 py-1 rounded-md transition-all bg-rose-500/20 text-rose-400">FAYL</button>
                                <button type="button" onclick="switchModelSubMethod('url')" id="model-sub-url-btn" class="px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300">HAVOLA</button>
                            </div>

                            <div id="model-file-area">
                                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-white/5 rounded-2xl bg-white/[0.03] hover:bg-white/[0.08] hover:border-rose-500/40 transition-all cursor-pointer group relative overflow-hidden">
                                    <div class="relative z-10 flex flex-col items-center">
                                        <i class="fa-solid fa-user-plus text-xl text-gray-500 group-hover:text-rose-400 mb-2"></i>
                                        <span class="text-xs font-bold text-gray-300 group-hover:text-white">Model rasmini yuklash</span>
                                    </div>
                                    <input id="model-file-input" type="file" accept="image/*" class="hidden" />
                                </label>
                            </div>

                            <div id="model-url-area" class="hidden">
                                <div class="flex gap-2">
                                    <input type="url" id="model-url-input" placeholder="https://..." class="input-field flex-1 h-10 text-xs" />
                                    <button type="button" onclick="addModelUrlImage()" class="px-3 h-10 bg-rose-500 text-white rounded-lg font-bold text-[10px]">OK</button>
                                </div>
                            </div>

                            <div id="model-preview-container" class="hidden">
                                <div class="relative w-24 aspect-[3/4] mx-auto group">
                                    <img id="model-preview-img" src="" class="w-full h-full object-cover rounded-xl border border-rose-500/20 bg-black/30" />
                                    <button onclick="removeModelImage()" class="absolute -top-1.5 -right-1.5 w-6 h-6 bg-rose-500 text-white rounded-full flex items-center justify-center text-[10px] shadow-lg">✕</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Model Style -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">
                            <i class="fa-solid fa-wand-magic-sparkles mr-1"></i> Model ko'rinishi
                        </label>
                        <div class="grid grid-cols-3 gap-2" id="style-selector">
                            <button data-style="editorial" class="style-btn active relative overflow-hidden rounded-xl border p-2.5 h-[70px] flex flex-col items-center justify-center gap-1 transition-all border-rose-500 bg-rose-500/15 text-white ring-2 ring-rose-500/30">
                                <div class="text-lg"><i class="fa-solid fa-camera-retro"></i></div>
                                <div class="font-bold text-[9px] uppercase tracking-tighter text-center">Editorial</div>
                                <div class="text-[7px] opacity-60">Jurnal uslubi</div>
                            </button>
                            <button data-style="street" class="style-btn relative overflow-hidden rounded-xl border p-2.5 h-[70px] flex flex-col items-center justify-center gap-1 transition-all border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-lg"><i class="fa-solid fa-city"></i></div>
                                <div class="font-bold text-[9px] uppercase tracking-tighter text-center">Street</div>
                                <div class="text-[7px] opacity-60">Ko'cha uslubi</div>
                            </button>
                            <button data-style="studio" class="style-btn relative overflow-hidden rounded-xl border p-2.5 h-[70px] flex flex-col items-center justify-center gap-1 transition-all border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-lg"><i class="fa-solid fa-film"></i></div>
                                <div class="font-bold text-[9px] uppercase tracking-tighter text-center">Studiya</div>
                                <div class="text-[7px] opacity-60">Oq fon</div>
                            </button>
                        </div>
                    </div>

                    <!-- Pose -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">
                            <i class="fa-solid fa-person-walking-arrow-right mr-1"></i> Poza
                        </label>
                        <div class="grid grid-cols-4 gap-2" id="pose-selector">
                            <button data-pose="standing" class="pose-btn active flex flex-col items-center justify-center h-14 rounded-xl border transition-all border-rose-500 bg-rose-500/15 text-white ring-1 ring-rose-500/30">
                                <div class="text-base"><i class="fa-solid fa-person"></i></div>
                                <div class="text-[8px] font-bold mt-0.5">Turgan</div>
                            </button>
                            <button data-pose="walking" class="pose-btn flex flex-col items-center justify-center h-14 rounded-xl border transition-all border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-base"><i class="fa-solid fa-person-walking"></i></div>
                                <div class="text-[8px] font-bold mt-0.5">Yurgan</div>
                            </button>
                            <button data-pose="sitting" class="pose-btn flex flex-col items-center justify-center h-14 rounded-xl border transition-all border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-base"><i class="fa-solid fa-chair"></i></div>
                                <div class="text-[8px] font-bold mt-0.5">O'tirgan</div>
                            </button>
                            <button data-pose="dynamic" class="pose-btn flex flex-col items-center justify-center h-14 rounded-xl border transition-all border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-base"><i class="fa-solid fa-person-running"></i></div>
                                <div class="text-[8px] font-bold mt-0.5">Dinamik</div>
                            </button>
                        </div>
                    </div>

                    <!-- Format -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3"><i class="fa-solid fa-vector-square mr-1"></i> Format</label>
                        <div class="grid grid-cols-3 sm:grid-cols-5 gap-2" id="ratio-selector">
                            <button data-ratio="3:4" class="ratio-btn active p-2 rounded-xl border text-center transition-all duration-200 border-rose-500 bg-rose-500/15 text-white shadow-lg shadow-rose-500/10">
                                <div class="text-[10px] font-bold uppercase">Portrait</div>
                                <div class="text-[8px] opacity-60 font-bold">3:4</div>
                            </button>
                            <button data-ratio="9:16" class="ratio-btn p-2 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold uppercase">Story</div>
                                <div class="text-[8px] opacity-60 font-bold">9:16</div>
                            </button>
                            <button data-ratio="1:1" class="ratio-btn p-2 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold uppercase">Kvadrat</div>
                                <div class="text-[8px] opacity-60 font-bold">1:1</div>
                            </button>
                            <button data-ratio="4:3" class="ratio-btn p-2 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold uppercase">Landscape</div>
                                <div class="text-[8px] opacity-60 font-bold">4:3</div>
                            </button>
                            <button data-ratio="16:9" class="ratio-btn p-2 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold uppercase">Keng</div>
                                <div class="text-[8px] opacity-60 font-bold">16:9</div>
                            </button>
                        </div>
                    </div>

                    <!-- Custom -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
                            <i class="fa-solid fa-pen-to-square mr-1"></i> Qo'shimcha ko'rsatmalar (ixtiyoriy)
                        </label>
                        <textarea id="custom-prompt" placeholder="Masalan: Yoz fasli uchun, quyoshli kundalik look..." rows="2" class="input-field resize-none text-sm"></textarea>
                    </div>

                    <!-- Generate -->
                    <button id="generate-btn" disabled class="btn-primary w-full h-16 text-lg font-extrabold disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                        <i class="fa-solid fa-wand-magic-sparkles mr-2 text-xl"></i>
                        <span id="btn-text">Modelga kiygizish</span>
                    </button>

                    <!-- Progress -->
                    <div id="progress-bar-container" class="hidden">
                        <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden">
                            <div id="progress-bar" class="h-full bg-gradient-to-r from-rose-600 via-pink-500 to-purple-500 rounded-full transition-all duration-300 ease-out shadow-[0_0_15px_rgba(244,63,94,0.5)]" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel — Result -->
            <div class="lg:col-span-7 order-2 lg:order-2">
                <div class="glass-card p-6 sm:p-8 min-h-[500px] flex flex-col items-center justify-center sticky top-24">
                    <!-- Loading -->
                    <div id="loading-state" class="text-center animate-fade-in hidden">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-rose-600/20 to-pink-600/20 border border-rose-500/20 flex items-center justify-center mx-auto mb-6 animate-pulse">
                            <span class="text-3xl text-rose-500"><i class="fa-solid fa-shirt"></i></span>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">AI kiyimni kiygizmoqda...</h3>
                        <p class="text-sm text-gray-400">Virtual try-on jarayoni. 20-60 soniya kutish.</p>
                        <div class="mt-6 flex justify-center gap-1">
                            <div class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></div>
                            <div class="w-2 h-2 rounded-full bg-rose-500 animate-pulse" style="animation-delay: 0.15s"></div>
                            <div class="w-2 h-2 rounded-full bg-rose-500 animate-pulse" style="animation-delay: 0.3s"></div>
                            <div class="w-2 h-2 rounded-full bg-rose-500 animate-pulse" style="animation-delay: 0.45s"></div>
                            <div class="w-2 h-2 rounded-full bg-rose-500 animate-pulse" style="animation-delay: 0.6s"></div>
                        </div>
                    </div>

                    <!-- Error -->
                    <div id="error-state" class="text-center animate-fade-in hidden">
                        <div class="w-16 h-16 rounded-2xl bg-red-500/10 flex items-center justify-center mx-auto mb-4">
                            <span class="text-3xl text-red-500"><i class="fa-solid fa-circle-xmark"></i></span>
                        </div>
                        <h3 class="text-lg font-semibold text-red-400 mb-2">Xatolik</h3>
                        <p id="error-text" class="text-sm text-gray-400 mb-4"></p>
                        <button id="retry-btn" class="btn-secondary text-sm px-6 py-2">Qayta urinish</button>
                    </div>

                    <!-- Result -->
                    <div id="result-state" class="w-full animate-fade-in space-y-4 hidden">
                        <div class="flex items-center gap-3 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-white/10" id="source-images-row">
                            <!-- Source images will be injected here -->
                        </div>

                        <!-- Full Result -->
                        <div class="relative group">
                            <img id="full-result-img" src="" alt="Natija" class="w-full rounded-xl shadow-2xl shadow-rose-500/10 border border-rose-500/10" />
                            <div class="absolute inset-0 bg-black/60 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-3">
                                <button id="view-hover-btn" class="w-48 py-3 bg-white text-black rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-gray-200 transition-colors">
                                    <i class="fa-solid fa-eye"></i>
                                    Ko'rish
                                </button>
                                <button id="download-hover-btn" class="w-48 py-3 bg-rose-500 text-white rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-rose-600 transition-colors">
                                    <i class="fa-solid fa-download"></i>
                                    Yuklab olish
                                </button>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <button id="view-btn" class="flex-1 min-w-[120px] h-12 bg-white/10 hover:bg-white/20 text-white rounded-xl font-bold flex items-center justify-center gap-2 border border-white/10 transition-all">
                                <i class="fa-solid fa-eye"></i>
                                Ko'rish
                            </button>
                            <button id="download-btn" class="flex-[2] min-w-[180px] h-12 btn-primary rounded-xl font-bold flex items-center justify-center gap-2 transition-all">
                                <i class="fa-solid fa-download"></i>
                                Yuklab olish
                            </button>
                            <button id="regenerate-btn" class="w-12 h-12 bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white rounded-xl flex items-center justify-center border border-white/5 transition-all" title="Qaytadan yaratish">
                                <i class="fa-solid fa-rotate-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Empty -->
                    <div id="empty-state" class="text-center">
                        <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-rose-600/20 to-pink-600/20 border border-rose-500/20 flex items-center justify-center mx-auto mb-6">
                            <span class="text-4xl text-rose-500"><i class="fa-solid fa-shirt"></i></span>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Virtual try-on natijasi</h3>
                        <p class="text-sm text-gray-500 max-w-sm mx-auto">
                            Kiyim rasmini yuklang, model turini tanlang va AI kiyimni modelga kiygizib ko'rsatadi.
                        </p>
                        <!-- Model examples -->
                        <div class="mt-6 grid grid-cols-3 gap-3 max-w-xs mx-auto">
                            <div class="h-24 rounded-xl bg-gradient-to-br from-rose-600/10 to-rose-600/5 border border-rose-500/10 flex flex-col items-center justify-center text-center p-2">
                                <span class="text-2xl mb-1 text-rose-400"><i class="fa-solid fa-person-dress"></i></span>
                                <span class="text-[9px] text-gray-500">Ayol model</span>
                            </div>
                            <div class="h-24 rounded-xl bg-gradient-to-br from-pink-600/10 to-pink-600/5 border border-pink-500/10 flex flex-col items-center justify-center text-center p-2">
                                <span class="text-2xl mb-1 text-blue-400"><i class="fa-solid fa-person"></i></span>
                                <span class="text-[9px] text-gray-500">Erkak model</span>
                            </div>
                            <div class="h-24 rounded-xl bg-gradient-to-br from-purple-600/10 to-purple-600/5 border border-purple-500/10 flex flex-col items-center justify-center text-center p-2">
                                <span class="text-2xl mb-1 text-purple-400"><i class="fa-solid fa-camera-retro"></i></span>
                                <span class="text-[9px] text-gray-500">Professional</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="preview-modal" class="fixed inset-0 z-[60] bg-black/95 backdrop-blur-sm hidden flex-col items-center justify-center p-4">
    <div class="absolute top-4 right-4 flex gap-3">
        <button id="modal-download-btn" class="w-12 h-12 bg-rose-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-rose-600 transition-all">
            <i class="fa-solid fa-download"></i>
        </button>
        <button id="close-modal-btn" class="w-12 h-12 bg-white/10 text-white rounded-full flex items-center justify-center hover:bg-white/20 transition-all">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
    </div>
    
    <div class="relative max-w-full max-h-[85vh] group">
        <img id="modal-img" src="" class="max-w-full max-h-[85vh] object-contain rounded-xl shadow-2xl" />
    </div>
    
    <div class="mt-6 flex gap-4">
        <button onclick="handleModalDownload()" class="btn-primary px-8 py-3 rounded-full font-bold flex items-center gap-2">
            <i class="fa-solid fa-download"></i>
            Yuklab olish
        </button>
    </div>
</div>

<script>
(function(){
    let clothingImages = []; 
    let selectedModel = 'woman';
    let modelImage = null; // Custom model image
    let selectedStyle = 'editorial';
    let selectedPose = 'standing';
    let selectedRatio = '3:4';
    let resultUrl = null;
    let progress = 0;
    let progressInterval = null;

    let currentClothingMethod = 'file';
    let currentModelMethod = 'preset'; // 'preset' or 'upload'
    let currentModelSubMethod = 'file';

    // Clothing elements
    const clothingFileInput = document.getElementById('clothing-file-input');
    const clothingUrlInput = document.getElementById('clothing-url-input');
    const methodFileArea = document.getElementById('method-file-area');
    const methodUrlArea = document.getElementById('method-url-area');
    const methodFileBtn = document.getElementById('method-file-btn');
    const methodUrlBtn = document.getElementById('method-url-btn');
    const clothingPreviewGrid = document.getElementById('clothing-preview-grid');

    // Model elements
    const modelPresetArea = document.getElementById('model-preset-area');
    const modelUploadArea = document.getElementById('model-upload-area');
    const modelMethodPresetBtn = document.getElementById('model-method-preset-btn');
    const modelMethodUploadBtn = document.getElementById('model-method-upload-btn');
    const modelSubFileBtn = document.getElementById('model-sub-file-btn');
    const modelSubUrlBtn = document.getElementById('model-sub-url-btn');
    const modelFileArea = document.getElementById('model-file-area');
    const modelUrlArea = document.getElementById('model-url-area');
    const modelFileInput = document.getElementById('model-file-input');
    const modelUrlInput = document.getElementById('model-url-input');
    const modelPreviewContainer = document.getElementById('model-preview-container');
    const modelPreviewImg = document.getElementById('model-preview-img');

    const generateBtn = document.getElementById('generate-btn');
    const btnText = document.getElementById('btn-text');
    const customPrompt = document.getElementById('custom-prompt');
    const progressBarContainer = document.getElementById('progress-bar-container');
    const progressBar = document.getElementById('progress-bar');

    // --- Switch Methods ---

    window.switchImageMethod = function(method) {
        currentClothingMethod = method;
        if (method === 'file') {
            methodFileArea.classList.remove('hidden');
            methodUrlArea.classList.add('hidden');
            methodFileBtn.className = 'px-2 py-1 rounded-md transition-all bg-rose-500/20 text-rose-400';
            methodUrlBtn.className = 'px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300';
        } else {
            methodFileArea.classList.add('hidden');
            methodUrlArea.classList.remove('hidden');
            methodUrlBtn.className = 'px-2 py-1 rounded-md transition-all bg-rose-500/20 text-rose-400';
            methodFileBtn.className = 'px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300';
        }
        updateClothingPreview();
    };

    window.switchModelMethod = function(method) {
        currentModelMethod = method;
        if (method === 'preset') {
            modelPresetArea.classList.remove('hidden');
            modelUploadArea.classList.add('hidden');
            modelMethodPresetBtn.className = 'px-2 py-1 rounded-md transition-all bg-rose-500/20 text-rose-400';
            modelMethodUploadBtn.className = 'px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300';
        } else {
            modelPresetArea.classList.add('hidden');
            modelUploadArea.classList.remove('hidden');
            modelMethodUploadBtn.className = 'px-2 py-1 rounded-md transition-all bg-rose-500/20 text-rose-400';
            modelMethodPresetBtn.className = 'px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300';
        }
        checkReady();
    };

    window.switchModelSubMethod = function(method) {
        currentModelSubMethod = method;
        if (method === 'file') {
            modelFileArea.classList.remove('hidden');
            modelUrlArea.classList.add('hidden');
            modelSubFileBtn.className = 'px-2 py-1 rounded-md transition-all bg-rose-500/20 text-rose-400';
            modelSubUrlBtn.className = 'px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300';
        } else {
            modelFileArea.classList.add('hidden');
            modelUrlArea.classList.remove('hidden');
            modelSubUrlBtn.className = 'px-2 py-1 rounded-md transition-all bg-rose-500/20 text-rose-400';
            modelSubFileBtn.className = 'px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300';
        }
    };

    // --- Helpers ---

    function checkReady() {
        const hasClothing = clothingImages.length > 0;
        const hasModel = (currentModelMethod === 'preset') || (currentModelMethod === 'upload' && modelImage !== null);
        generateBtn.disabled = !(hasClothing && hasModel);
    }

    // --- Clothing Logic ---

    function updateClothingPreview() {
        clothingPreviewGrid.innerHTML = '';
        if (clothingImages.length > 0) {
            clothingPreviewGrid.classList.remove('hidden');
            clothingImages.forEach((img, idx) => {
                const div = document.createElement('div');
                div.className = 'relative group aspect-[3/4]';
                div.innerHTML = `
                    <img src="${img}" class="w-full h-full object-cover rounded-xl border border-rose-500/20 bg-black/30" />
                    <button onclick="removeClothingImage(${idx})" class="absolute -top-1.5 -right-1.5 w-6 h-6 bg-rose-500 text-white rounded-full flex items-center justify-center text-[10px] shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">✕</button>
                `;
                clothingPreviewGrid.appendChild(div);
            });
            
            if (clothingImages.length < 3) {
                if (currentClothingMethod === 'file') {
                    methodFileArea.classList.remove('hidden');
                    methodUrlArea.classList.add('hidden');
                } else {
                    methodFileArea.classList.add('hidden');
                    methodUrlArea.classList.remove('hidden');
                }
            } else {
                methodFileArea.classList.add('hidden');
                methodUrlArea.classList.add('hidden');
            }
        } else {
            clothingPreviewGrid.classList.add('hidden');
            if (currentClothingMethod === 'file') {
                methodFileArea.classList.remove('hidden');
                methodUrlArea.classList.add('hidden');
            } else {
                methodFileArea.classList.add('hidden');
                methodUrlArea.classList.remove('hidden');
            }
        }
        checkReady();
    }

    window.removeClothingImage = function(idx) {
        clothingImages.splice(idx, 1);
        updateClothingPreview();
    };

    window.addUrlImage = function() {
        const url = clothingUrlInput.value.trim();
        if (url && (url.startsWith('http')) && clothingImages.length < 3) {
            clothingImages.push(url);
            clothingUrlInput.value = '';
            updateClothingPreview();
        }
    };

    clothingUrlInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); addUrlImage(); }
    });

    clothingFileInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        let processed = 0;
        files.forEach(file => {
            if (clothingImages.length >= 3) return;
            const reader = new FileReader();
            reader.onload = function() {
                clothingImages.push(reader.result);
                processed++;
                if (processed === Math.min(files.length, 3 - (clothingImages.length - processed))) {
                    updateClothingPreview();
                }
            };
            reader.readAsDataURL(file);
        });
        this.value = '';
    });

    // --- Model Upload Logic ---

    window.addModelUrlImage = function() {
        const url = modelUrlInput.value.trim();
        if (url && (url.startsWith('http'))) {
            modelImage = url;
            modelUrlInput.value = '';
            updateModelPreview();
        }
    };

    window.removeModelImage = function() {
        modelImage = null;
        updateModelPreview();
    };

    function updateModelPreview() {
        if (modelImage) {
            modelPreviewImg.src = modelImage;
            modelPreviewContainer.classList.remove('hidden');
            modelFileArea.classList.add('hidden');
            modelUrlArea.classList.add('hidden');
        } else {
            modelPreviewContainer.classList.add('hidden');
            if (currentModelSubMethod === 'file') {
                modelFileArea.classList.remove('hidden');
                modelUrlArea.classList.add('hidden');
            } else {
                modelFileArea.classList.add('hidden');
                modelUrlArea.classList.remove('hidden');
            }
        }
        checkReady();
    }

    modelFileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function() {
            modelImage = reader.result;
            updateModelPreview();
        };
        reader.readAsDataURL(file);
        this.value = '';
    });

    // --- UI Selectors ---

    function setupSelector(className, variable, setter) {
        document.querySelectorAll('.' + className).forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.' + className).forEach(b => {
                    b.classList.remove('border-rose-500', 'bg-rose-500/15', 'text-white', 'ring-2', 'ring-1', 'ring-rose-500/30');
                    b.classList.add('border-white/10', 'bg-white/5', 'text-gray-400');
                });
                this.classList.remove('border-white/10', 'bg-white/5', 'text-gray-400');
                this.classList.add('border-rose-500', 'bg-rose-500/15', 'text-white', 'ring-2', 'ring-rose-500/30');
                setter(this.dataset[variable]);
            });
        });
    }

    setupSelector('model-btn', 'model', v => selectedModel = v);
    setupSelector('style-btn', 'style', v => selectedStyle = v);
    setupSelector('pose-btn', 'pose', v => selectedPose = v);

    document.querySelectorAll('.ratio-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.ratio-btn').forEach(b => {
                b.className = 'ratio-btn p-2 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20';
            });
            this.className = 'ratio-btn active p-2 rounded-xl border text-center transition-all duration-200 border-rose-500 bg-rose-500/15 text-white shadow-lg shadow-rose-500/10';
            selectedRatio = this.dataset.ratio;
        });
    });

    function showState(state) {
        ['loading-state','error-state','result-state','empty-state'].forEach(id => {
            document.getElementById(id).classList.add('hidden');
        });
        const el = document.getElementById(state + '-state');
        if (el) el.classList.remove('hidden');
    }

    // --- Generate ---

    async function handleGenerate() {
        if (clothingImages.length === 0) return;

        generateBtn.disabled = true;
        btnText.textContent = 'Yaratilmoqda... 0%';
        progressBarContainer.classList.remove('hidden');
        progress = 0;
        progressBar.style.width = '0%';
        showState('loading');

        progressInterval = setInterval(() => {
            if (progress < 80) progress += Math.random() * 2.5;
            else if (progress < 95) progress += 0.12;
            progressBar.style.width = progress + '%';
            btnText.textContent = 'Yaratilmoqda... ' + Math.floor(progress) + '%';
        }, 400);

        try {
            const response = await fetch('/api/fashion-ai.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-User-Token': TibaAuth.getToken()
                },
                body: JSON.stringify({
                    clothingImages: clothingImages,
                    modelType: currentModelMethod === 'preset' ? selectedModel : 'custom',
                    modelImage: currentModelMethod === 'upload' ? modelImage : null,
                    style: selectedStyle,
                    pose: selectedPose,
                    aspectRatio: selectedRatio,
                    customPrompt: customPrompt.value,
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

            const sourceRow = document.getElementById('source-images-row');
            sourceRow.innerHTML = '';
            
            // Show model image if custom
            if (currentModelMethod === 'upload' && modelImage) {
                sourceRow.innerHTML += `
                    <div class="relative w-16 h-20 flex-shrink-0 group">
                        <img src="${modelImage}" class="w-full h-full object-cover rounded-lg border border-white/20" />
                        <span class="absolute bottom-0 right-0 bg-rose-500 text-white text-[7px] px-1 font-bold rounded-tl-md">MODEL</span>
                    </div>
                `;
            }

            clothingImages.forEach(img => {
                sourceRow.innerHTML += `
                    <div class="relative w-16 h-20 flex-shrink-0">
                        <img src="${img}" class="w-full h-full object-cover rounded-lg border border-white/10" />
                    </div>
                `;
            });
            sourceRow.innerHTML += `
                <div class="flex items-center text-gray-500 px-1">→</div>
                <div class="relative w-20 h-24 flex-shrink-0">
                    <img src="${resultUrl}" class="w-full h-full object-cover rounded-lg border border-rose-500/20" />
                </div>
            `;

            document.getElementById('full-result-img').src = resultUrl;
            showState('result');
        } catch (err) {
            if (err.message !== '__nobalance__') {
                document.getElementById('error-text').textContent = err.message;
                showState('error');
            }
        } finally {
            clearInterval(progressInterval);
            checkReady();
            btnText.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles mr-2"></i>Modelga kiygizish';
            progressBarContainer.classList.add('hidden');
        }
    }

    generateBtn.addEventListener('click', () => TibaAuth.requireAuth(handleGenerate));
    document.getElementById('regenerate-btn').addEventListener('click', () => TibaAuth.requireAuth(handleGenerate));

    function handleDownload() {
        if (!resultUrl) return;
        const a = document.createElement('a'); a.href = resultUrl;
        a.download = 'fashion-ai-' + Date.now() + '.png'; a.target = '_blank';
        document.body.appendChild(a); a.click(); document.body.removeChild(a);
    }

    const previewModal = document.getElementById('preview-modal');
    const modalImg = document.getElementById('modal-img');

    function openModal() {
        if (!resultUrl) return;
        modalImg.src = resultUrl;
        previewModal.classList.remove('hidden');
        previewModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        previewModal.classList.add('hidden');
        previewModal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    window.handleModalDownload = handleDownload;

    document.getElementById('view-btn').addEventListener('click', openModal);
    document.getElementById('view-hover-btn').addEventListener('click', openModal);
    document.getElementById('close-modal-btn').addEventListener('click', closeModal);
    document.getElementById('modal-download-btn').addEventListener('click', handleDownload);
    previewModal.addEventListener('click', (e) => { if (e.target === previewModal) closeModal(); });

    document.getElementById('download-btn').addEventListener('click', handleDownload);
    document.getElementById('download-hover-btn').addEventListener('click', handleDownload);
    document.getElementById('retry-btn').addEventListener('click', () => showState('empty'));
})();
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
