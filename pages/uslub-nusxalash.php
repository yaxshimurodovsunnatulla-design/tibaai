<?php $pageTitle = 'Uslub Nusxalash – Tiba AI'; ?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="py-12 sm:py-20 relative overflow-hidden">
    <!-- Background elements -->
    <div class="absolute top-10 left-1/3 w-96 h-96 bg-cyan-600/10 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-10 right-1/3 w-80 h-80 bg-blue-600/10 rounded-full blur-3xl animate-float" style="animation-delay: -3s"></div>

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
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-cyan-500/10 border border-cyan-500/20 mb-4 animate-fade-in">
                <i class="fa-solid fa-masks-theater text-cyan-400 text-sm"></i>
                <span class="text-xs font-bold text-cyan-300 uppercase tracking-widest">Still Nusxalash</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3">
                Uslub <span class="bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-blue-400">Nusxalash</span>
            </h1>
            <p class="text-gray-400 max-w-xl mx-auto">
                Namuna rasmning uslubini mahsulot rasmingizga o'tkazing. AI 2 ta rasmni birlashtirib, professional natija beradi.
            </p>
        </div>

        <!-- How it works mini-banner -->
        <div class="glass-card p-4 sm:p-5 mb-8 max-w-3xl mx-auto border border-cyan-500/10">
            <div class="flex items-center gap-2 mb-3">
                <i class="fa-solid fa-lightbulb text-amber-400 text-sm"></i>
                <span class="text-xs font-bold text-cyan-300 uppercase tracking-wider">Qanday ishlaydi?</span>
            </div>
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center mx-auto mb-2 text-cyan-400">
                        <i class="fa-solid fa-camera text-lg"></i>
                    </div>
                    <div class="text-xs text-gray-400"><span class="text-white font-semibold">1.</span> Mahsulot rasmini yuklang</div>
                </div>
                <div>
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center mx-auto mb-2 text-blue-400">
                        <i class="fa-solid fa-palette text-lg"></i>
                    </div>
                    <div class="text-xs text-gray-400"><span class="text-white font-semibold">2.</span> Namuna uslub rasmini yuklang</div>
                </div>
                <div>
                    <div class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center mx-auto mb-2 text-purple-400">
                        <i class="fa-solid fa-wand-magic-sparkles text-lg"></i>
                    </div>
                    <div class="text-xs text-gray-400"><span class="text-white font-semibold">3.</span> AI natijani yaratadi</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Panel — Controls -->
            <div class="lg:col-span-5 space-y-6 order-1 lg:order-1">
                <div class="glass-card p-6 sm:p-8 space-y-6">
                    <!-- Images Selection Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Style Reference Section (Swapped to first) -->
                        <div class="order-1">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex justify-between items-center">
                                <span class="flex items-center gap-2"><i class="fa-solid fa-palette text-blue-400"></i> Namuna <span class="text-red-400">*</span></span>
                                <div class="flex p-0.5 bg-white/5 rounded-lg border border-white/5 text-[9px]">
                                    <button type="button" onclick="switchImageMethod('style', 'file')" id="style-method-file-btn" class="px-2 py-1 rounded-md transition-all bg-blue-500/20 text-blue-400">FAYL</button>
                                    <button type="button" onclick="switchImageMethod('style', 'url')" id="style-method-url-btn" class="px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300">HAVOLA</button>
                                </div>
                            </label>

                            <!-- Style File -->
                            <div id="style-method-file-area">
                                <label id="style-upload-label" class="flex flex-col items-center justify-center w-full h-44 border-2 border-dashed border-white/5 rounded-3xl bg-white/[0.03] hover:bg-white/[0.08] hover:border-blue-500/40 transition-all duration-300 cursor-pointer group relative overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/0 to-indigo-500/0 group-hover:from-blue-500/5 group-hover:to-indigo-500/5 transition-all duration-500"></div>
                                    <div class="relative z-10 flex flex-col items-center">
                                        <div class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-blue-500/10 transition-all duration-500 border border-white/5 group-hover:border-blue-500/20">
                                            <i class="fa-solid fa-palette text-2xl text-gray-500 group-hover:text-blue-400"></i>
                                        </div>
                                        <span class="text-sm font-bold text-gray-300 group-hover:text-white transition-colors text-center px-2">Uslub yuklash</span>
                                        <span class="text-[10px] text-gray-500 mt-1 uppercase tracking-tighter">ART / DIZAYN</span>
                                    </div>
                                    <input id="style-file-input" type="file" accept="image/*" class="hidden" />
                                </label>
                            </div>

                            <div id="style-method-url-area" class="hidden">
                                <div class="relative">
                                    <input type="url" id="style-url-input" placeholder="https://..." class="input-field pl-10 h-12 text-sm" />
                                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500"><i class="fa-solid fa-link text-xs"></i></div>
                                </div>
                            </div>

                            <div id="style-preview-container" class="relative group hidden mt-2">
                                <img id="style-preview-image" src="" alt="Style Preview" class="w-full rounded-2xl bg-black/30 border border-blue-500/20" />
                                <button id="style-reset-btn" class="absolute top-2 right-2 w-7 h-7 bg-black/60 backdrop-blur text-white rounded-full flex items-center justify-center text-xs hover:bg-red-500 transition-colors shadow-lg"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        </div>

                        <!-- Product Image Section (Swapped to second) -->
                        <div class="order-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex justify-between items-center">
                                <span class="flex items-center gap-2"><i class="fa-solid fa-camera text-cyan-400"></i> Mahsulot rasmi <span class="text-red-400">*</span></span>
                                <div class="flex p-0.5 bg-white/5 rounded-lg border border-white/5 text-[9px]">
                                    <button type="button" onclick="switchImageMethod('product', 'file')" id="product-method-file-btn" class="px-2 py-1 rounded-md transition-all bg-cyan-500/20 text-cyan-400">FAYL</button>
                                    <button type="button" onclick="switchImageMethod('product', 'url')" id="product-method-url-btn" class="px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300">HAVOLA</button>
                                </div>
                            </label>
                            
                            <!-- Product File -->
                            <div id="product-method-file-area">
                                <label id="product-upload-label" class="flex flex-col items-center justify-center w-full h-44 border-2 border-dashed border-white/5 rounded-3xl bg-white/[0.03] hover:bg-white/[0.08] hover:border-cyan-500/40 transition-all duration-300 cursor-pointer group relative overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/0 to-blue-500/0 group-hover:from-cyan-500/5 group-hover:to-blue-500/5 transition-all duration-500"></div>
                                    <div class="relative z-10 flex flex-col items-center">
                                        <div class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-cyan-500/10 transition-all duration-500 border border-white/5 group-hover:border-cyan-500/20">
                                            <i class="fa-solid fa-cloud-arrow-up text-2xl text-gray-500 group-hover:text-cyan-400"></i>
                                        </div>
                                        <span class="text-sm font-bold text-gray-300 group-hover:text-white transition-colors text-center px-2">Rasm yuklash</span>
                                        <span class="text-[10px] text-gray-500 mt-1 uppercase tracking-tighter">JPG, PNG • 10 MB</span>
                                    </div>
                                    <input id="product-file-input" type="file" accept="image/*" class="hidden" />
                                </label>
                            </div>

                            <div id="product-method-url-area" class="hidden">
                                <div class="relative">
                                    <input type="url" id="product-url-input" placeholder="https://..." class="input-field pl-10 h-12 text-sm" />
                                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500"><i class="fa-solid fa-link text-xs"></i></div>
                                </div>
                            </div>

                            <div id="product-preview-container" class="relative group hidden mt-2">
                                <img id="product-preview-image" src="" alt="Preview" class="w-full rounded-2xl bg-black/30 border border-cyan-500/20" />
                                <button id="product-reset-btn" class="absolute top-2 right-2 w-7 h-7 bg-black/60 backdrop-blur text-white rounded-full flex items-center justify-center text-xs hover:bg-red-500 transition-colors shadow-lg"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Format -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">
                            📐 Format (o'lcham)
                        </label>
                        <div class="grid grid-cols-3 sm:grid-cols-5 gap-2" id="template-selector">
                            <button data-ratio="3:4" data-id="uzum" class="template-btn active p-2.5 rounded-xl border text-center transition-all duration-200 border-cyan-500 bg-cyan-500/15 text-white shadow-lg shadow-cyan-500/10">
                                <div class="text-[10px] font-bold uppercase tracking-tighter">Uzum</div>
                                <div class="text-[8px] opacity-60 font-bold">3:4</div>
                            </button>
                            <button data-ratio="1:1" data-id="square" class="template-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold uppercase tracking-tighter">Kvadrat</div>
                                <div class="text-[8px] opacity-60 font-bold">1:1</div>
                            </button>
                            <button data-ratio="4:3" data-id="landscape" class="template-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold uppercase tracking-tighter">Landscape</div>
                                <div class="text-[8px] opacity-60 font-bold">4:3</div>
                            </button>
                            <button data-ratio="9:16" data-id="story" class="template-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold uppercase tracking-tighter">Story</div>
                                <div class="text-[8px] opacity-60 font-bold">9:16</div>
                            </button>
                            <button data-ratio="16:9" data-id="wide" class="template-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold uppercase tracking-tighter">Keng</div>
                                <div class="text-[8px] opacity-60 font-bold">16:9</div>
                            </button>
                        </div>
                    </div>

                    <!-- Transfer Strength -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">
                            ⚡ Uslub kuchi
                        </label>
                        <div class="flex items-center gap-4">
                            <input type="range" id="strength-slider" min="1" max="3" value="2" step="1" class="flex-1 h-2 bg-white/10 rounded-full appearance-none cursor-pointer accent-cyan-500 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-5 [&::-webkit-slider-thumb]:h-5 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-cyan-500 [&::-webkit-slider-thumb]:shadow-lg [&::-webkit-slider-thumb]:shadow-cyan-500/30 [&::-webkit-slider-thumb]:cursor-pointer">
                            <div id="strength-label" class="text-sm font-bold text-cyan-300 w-20 text-right">O'rtacha</div>
                        </div>
                        <div class="flex justify-between text-[10px] text-gray-500 mt-1.5 px-0.5">
                            <span>Yengil</span>
                            <span>O'rtacha</span>
                            <span>Kuchli</span>
                        </div>
                    </div>

                    <!-- Custom Prompt -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-pen-nib text-cyan-400"></i> Qo'shimcha istaklar (ixtiyoriy)
                        </label>
                        <textarea id="custom-prompt" placeholder="Masalan: Ranglarni yorqinroq qib ber, mahsulotni kattaroq ko'rsat..." rows="2" class="input-field resize-none text-sm"></textarea>
                    </div>

                    <!-- Generate Button -->
                    <button id="generate-btn" disabled class="btn-primary w-full h-16 text-lg font-extrabold disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 flex items-center justify-center gap-3">
                        <span id="btn-text" class="flex items-center gap-3">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            Uslub nusxalash
                        </span>
                    </button>

                    <!-- Progress bar -->
                    <div id="progress-bar-container" class="hidden">
                        <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden">
                            <div id="progress-bar" class="h-full bg-gradient-to-r from-cyan-600 via-blue-500 to-purple-500 rounded-full transition-all duration-300 ease-out shadow-[0_0_15px_rgba(6,182,212,0.5)]" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel — Result -->
            <div class="lg:col-span-7 order-2 lg:order-2">
                <div class="glass-card p-6 sm:p-8 min-h-[500px] flex flex-col items-center justify-center sticky top-24">
                    <!-- Loading state -->
                    <div id="loading-state" class="text-center animate-fade-in hidden">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-cyan-600/20 to-blue-600/20 border border-cyan-500/20 flex items-center justify-center mx-auto mb-6 animate-pulse">
                            <i class="fa-solid fa-masks-theater text-3xl text-cyan-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">AI uslub o'tkazmoqda...</h3>
                        <p class="text-sm text-gray-400">Namuna rasmning uslubi mahsulotga qo'llanmoqda. 20-60 soniya kutish.</p>
                        <div class="mt-6 flex justify-center gap-1">
                            <div class="w-2 h-2 rounded-full bg-cyan-500 animate-pulse"></div>
                            <div class="w-2 h-2 rounded-full bg-cyan-500 animate-pulse" style="animation-delay: 0.15s"></div>
                            <div class="w-2 h-2 rounded-full bg-cyan-500 animate-pulse" style="animation-delay: 0.3s"></div>
                            <div class="w-2 h-2 rounded-full bg-cyan-500 animate-pulse" style="animation-delay: 0.45s"></div>
                            <div class="w-2 h-2 rounded-full bg-cyan-500 animate-pulse" style="animation-delay: 0.6s"></div>
                        </div>
                    </div>

                    <!-- Error state -->
                    <div id="error-state" class="text-center animate-fade-in hidden">
                        <div class="w-16 h-16 rounded-2xl bg-red-500/10 border border-red-500/20 flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-circle-xmark text-3xl text-red-500"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-red-400 mb-2">Xatolik</h3>
                        <p id="error-text" class="text-sm text-gray-400 mb-4"></p>
                        <button id="retry-btn" class="btn-secondary text-sm px-6 py-2">Qayta urinish</button>
                    </div>

                    <!-- Result state -->
                    <div id="result-state" class="w-full animate-fade-in space-y-4 hidden">
                        <!-- Source images row -->
                        <div class="grid grid-cols-3 gap-3">
                            <div class="relative">
                                <img id="source-product-img" src="" alt="Mahsulot" class="w-full rounded-xl object-contain bg-black/30 border border-white/10 aspect-square" />
                                <span class="absolute top-1.5 left-1.5 bg-cyan-500/80 backdrop-blur px-2 py-0.5 rounded-md text-[9px] font-bold text-white uppercase tracking-wider">Mahsulot</span>
                            </div>
                            <div class="flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-cyan-500/20 to-blue-500/20 border border-cyan-500/20 flex items-center justify-center">
                                    <i class="fa-solid fa-plus text-cyan-400 text-lg"></i>
                                </div>
                            </div>
                            <div class="relative">
                                <img id="source-style-img" src="" alt="Uslub" class="w-full rounded-xl object-contain bg-black/30 border border-white/10 aspect-square" />
                                <span class="absolute top-1.5 left-1.5 bg-blue-500/80 backdrop-blur px-2 py-0.5 rounded-md text-[9px] font-bold text-white uppercase tracking-wider">Uslub</span>
                            </div>
                        </div>

                        <!-- Arrow -->
                        <div class="flex items-center justify-center py-1">
                            <i class="fa-solid fa-arrow-down-long text-gray-600 text-xl"></i>
                        </div>

                        <!-- Full Result -->
                        <div class="relative group">
                            <img id="full-result-img" src="" alt="Natija" class="w-full rounded-xl shadow-2xl shadow-cyan-500/10 border border-cyan-500/10" />
                            <span class="absolute top-2 left-2 bg-gradient-to-r from-cyan-500 to-blue-500 backdrop-blur px-3 py-1 rounded-lg text-[10px] font-bold text-white uppercase tracking-wider shadow-lg flex items-center gap-1.5">
                                <i class="fa-solid fa-wand-magic-sparkles"></i> Natija
                            </span>
                            <div class="absolute inset-0 bg-black/50 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-4">
                                <button id="download-hover-btn" class="btn-primary px-6 py-3">
                                    <i class="fa-solid fa-download mr-2"></i>
                                    Yuklab olish
                                </button>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button id="download-btn" class="btn-primary flex-1">
                                <i class="fa-solid fa-download mr-2"></i>
                                Rasmni yuklab olish
                            </button>
                            <button id="reset-result-btn" class="btn-secondary px-6" title="Qaytadan boshlash">
                                <i class="fa-solid fa-rotate-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Empty state -->
                    <div id="empty-state" class="text-center">
                        <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-cyan-600/20 to-blue-600/20 border border-cyan-500/20 flex items-center justify-center mx-auto mb-6 shadow-xl shadow-cyan-500/10">
                            <i class="fa-solid fa-masks-theater text-4xl text-cyan-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Natija shu yerda ko'rinadi</h3>
                        <p class="text-sm text-gray-500 max-w-sm mx-auto">
                            Mahsulot rasmini va xohlagan uslubingizning namuna rasmini yuklang. AI ikkisini birlashtirib, ajoyib natija yaratadi.
                        </p>
                        <div class="mt-6 flex items-center justify-center gap-3 max-w-xs mx-auto">
                            <div class="h-20 w-20 rounded-xl bg-gradient-to-br from-cyan-600/10 to-cyan-600/5 border border-cyan-500/10 flex items-center justify-center text-2xl shrink-0 text-cyan-400/50"><i class="fa-solid fa-camera"></i></div>
                            <div class="text-xl text-gray-700"><i class="fa-solid fa-plus"></i></div>
                            <div class="h-20 w-20 rounded-xl bg-gradient-to-br from-blue-600/10 to-blue-600/5 border border-blue-500/10 flex items-center justify-center text-2xl shrink-0 text-blue-400/50"><i class="fa-solid fa-palette"></i></div>
                            <div class="text-xl text-gray-700"><i class="fa-solid fa-equals"></i></div>
                            <div class="h-20 w-20 rounded-xl bg-gradient-to-br from-purple-600/10 to-purple-600/5 border border-purple-500/10 flex items-center justify-center text-2xl shrink-0 text-purple-400/50"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    let productImageUrl = null;
    let styleImageUrl = null;
    let selectedRatio = '3:4';
    let resultUrl = null;
    let progress = 0;
    let progressInterval = null;

    // DOM
    const productFileInput = document.getElementById('product-file-input');
    const productUrlInput = document.getElementById('product-url-input');
    const productUploadLabel = document.getElementById('product-upload-label');
    const productMethodFileArea = document.getElementById('product-method-file-area');
    const productMethodUrlArea = document.getElementById('product-method-url-area');
    const productMethodFileBtn = document.getElementById('product-method-file-btn');
    const productMethodUrlBtn = document.getElementById('product-method-url-btn');
    const productPreviewContainer = document.getElementById('product-preview-container');
    const productPreviewImage = document.getElementById('product-preview-image');
    const productResetBtn = document.getElementById('product-reset-btn');

    const styleFileInput = document.getElementById('style-file-input');
    const styleUrlInput = document.getElementById('style-url-input');
    const styleUploadLabel = document.getElementById('style-upload-label');
    const styleMethodFileArea = document.getElementById('style-method-file-area');
    const styleMethodUrlArea = document.getElementById('style-method-url-area');
    const styleMethodFileBtn = document.getElementById('style-method-file-btn');
    const styleMethodUrlBtn = document.getElementById('style-method-url-btn');
    const stylePreviewContainer = document.getElementById('style-preview-container');
    const stylePreviewImage = document.getElementById('style-preview-image');
    const styleResetBtn = document.getElementById('style-reset-btn');

    const generateBtn = document.getElementById('generate-btn');
    const btnText = document.getElementById('btn-text');
    const customPrompt = document.getElementById('custom-prompt');
    const strengthSlider = document.getElementById('strength-slider');
    const strengthLabel = document.getElementById('strength-label');
    const progressBarContainer = document.getElementById('progress-bar-container');
    const progressBar = document.getElementById('progress-bar');
    const retryBtn = document.getElementById('retry-btn');
    const loadingState = document.getElementById('loading-state');
    const errorState = document.getElementById('error-state');
    const errorText = document.getElementById('error-text');
    const resultState = document.getElementById('result-state');
    const emptyState = document.getElementById('empty-state');

    let productMethod = 'file';
    let styleMethod = 'file';

    // Switch methods
    window.switchImageMethod = function(target, method) {
        if (target === 'product') {
            productMethod = method;
            if (method === 'file') {
                productMethodFileArea.classList.remove('hidden');
                productMethodUrlArea.classList.add('hidden');
                productMethodFileBtn.className = 'px-2 py-1 rounded-md transition-all bg-cyan-500/20 text-cyan-400';
                productMethodUrlBtn.className = 'px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300';
                if (productImageUrl && productImageUrl.startsWith('http')) resetProduct();
            } else {
                productMethodFileArea.classList.add('hidden');
                productMethodUrlArea.classList.remove('hidden');
                productMethodUrlBtn.className = 'px-2 py-1 rounded-md transition-all bg-cyan-500/20 text-cyan-400';
                productMethodFileBtn.className = 'px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300';
                if (productImageUrl && productImageUrl.startsWith('data:')) resetProduct();
            }
        } else {
            styleMethod = method;
            if (method === 'file') {
                styleMethodFileArea.classList.remove('hidden');
                styleMethodUrlArea.classList.add('hidden');
                styleMethodFileBtn.className = 'px-2 py-1 rounded-md transition-all bg-blue-500/20 text-blue-400';
                styleMethodUrlBtn.className = 'px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300';
                if (styleImageUrl && styleImageUrl.startsWith('http')) resetStyle();
            } else {
                styleMethodFileArea.classList.add('hidden');
                styleMethodUrlArea.classList.remove('hidden');
                styleMethodUrlBtn.className = 'px-2 py-1 rounded-md transition-all bg-blue-500/20 text-blue-400';
                styleMethodFileBtn.className = 'px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300';
                if (styleImageUrl && styleImageUrl.startsWith('data:')) resetStyle();
            }
        }
    };

    // Strength labels
    const strengthLabels = { '1': 'Yengil', '2': "O'rtacha", '3': 'Kuchli' };
    strengthSlider.addEventListener('input', function() {
        strengthLabel.textContent = strengthLabels[this.value];
    });

    // Check if both images uploaded
    function checkReady() {
        generateBtn.disabled = !(productImageUrl && styleImageUrl);
    }

    // Product URL
    productUrlInput.addEventListener('input', function() {
        const url = this.value.trim();
        if (url && (url.startsWith('http://') || url.startsWith('https://'))) {
            productImageUrl = url;
            productPreviewImage.src = url;
            productMethodUrlArea.classList.add('hidden');
            productPreviewContainer.classList.remove('hidden');
            checkReady();
            showState('empty');
        }
    });

    // Product upload
    productFileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function() {
                productImageUrl = reader.result;
                productPreviewImage.src = productImageUrl;
                productMethodFileArea.classList.add('hidden');
                productPreviewContainer.classList.remove('hidden');
                checkReady();
                resultUrl = null;
                showState('empty');
            };
            reader.readAsDataURL(file);
        }
    });

    function resetProduct() {
        productImageUrl = null;
        productFileInput.value = '';
        productUrlInput.value = '';
        productPreviewContainer.classList.add('hidden');
        if (productMethod === 'file') productMethodFileArea.classList.remove('hidden');
        else productMethodUrlArea.classList.remove('hidden');
        checkReady();
    }
    productResetBtn.addEventListener('click', resetProduct);

    // Style URL
    styleUrlInput.addEventListener('input', function() {
        const url = this.value.trim();
        if (url && (url.startsWith('http://') || url.startsWith('https://'))) {
            styleImageUrl = url;
            stylePreviewImage.src = url;
            styleMethodUrlArea.classList.add('hidden');
            stylePreviewContainer.classList.remove('hidden');
            checkReady();
            showState('empty');
        }
    });

    // Style upload
    styleFileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function() {
                styleImageUrl = reader.result;
                stylePreviewImage.src = styleImageUrl;
                styleMethodFileArea.classList.add('hidden');
                stylePreviewContainer.classList.remove('hidden');
                checkReady();
                resultUrl = null;
                showState('empty');
            };
            reader.readAsDataURL(file);
        }
    });

    function resetStyle() {
        styleImageUrl = null;
        styleFileInput.value = '';
        styleUrlInput.value = '';
        stylePreviewContainer.classList.add('hidden');
        if (styleMethod === 'file') styleMethodFileArea.classList.remove('hidden');
        else styleMethodUrlArea.classList.remove('hidden');
        checkReady();
    }
    styleResetBtn.addEventListener('click', resetStyle);

    // Template selector
    document.querySelectorAll('.template-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.template-btn').forEach(b => {
                b.className = 'template-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20';
            });
            this.className = 'template-btn active p-2.5 rounded-xl border text-center transition-all duration-200 border-cyan-500 bg-cyan-500/15 text-white shadow-lg shadow-cyan-500/10';
            selectedRatio = this.dataset.ratio;
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
        if (state === 'result') resultState.classList.remove('hidden');
        if (state === 'empty') emptyState.classList.remove('hidden');
    }

    // Full reset
    function handleFullReset() {
        productImageUrl = null;
        styleImageUrl = null;
        resultUrl = null;
        productFileInput.value = '';
        styleFileInput.value = '';
        productUploadLabel.classList.remove('hidden');
        productPreviewContainer.classList.add('hidden');
        styleUploadLabel.classList.remove('hidden');
        stylePreviewContainer.classList.add('hidden');
        customPrompt.value = '';
        strengthSlider.value = '2';
        strengthLabel.textContent = "O'rtacha";
        generateBtn.disabled = true;
        showState('empty');
    }

    document.getElementById('reset-result-btn').addEventListener('click', handleFullReset);

    // Generate
    generateBtn.addEventListener('click', function() {
        TibaAuth.requireAuth(async function() {
        if (!productImageUrl || !styleImageUrl) return;

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
            const response = await fetch('/api/uslub-nusxalash.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-User-Token': TibaAuth.getToken()
                },
                body: JSON.stringify({
                    productImage: productImageUrl,
                    styleImage: styleImageUrl,
                    aspectRatio: selectedRatio,
                    strength: strengthSlider.value,
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

            document.getElementById('source-product-img').src = productImageUrl;
            document.getElementById('source-style-img').src = styleImageUrl;
            document.getElementById('full-result-img').src = resultUrl;
            showState('result');
        } catch (err) {
            if (err.message !== '__nobalance__') {
                errorText.textContent = err.message;
                showState('error');
            }
        } finally {
            clearInterval(progressInterval);
            checkReady();
            btnText.innerHTML = '<i class="fa-solid fa-masks-theater"></i> Uslub nusxalash';
            progressBarContainer.classList.add('hidden');
        }
        });
    });

    // Download
    function handleDownload() {
        if (!resultUrl) return;
        const a = document.createElement('a');
        a.href = resultUrl;
        a.download = 'style-transfer-' + Date.now() + '.png';
        a.target = '_blank';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }
    document.getElementById('download-btn').addEventListener('click', handleDownload);
    document.getElementById('download-hover-btn').addEventListener('click', handleDownload);

    // Retry
    retryBtn.addEventListener('click', () => showState('empty'));
})();
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
