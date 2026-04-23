<?php $pageTitle = 'Smart Matn – Tiba AI'; ?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="py-12 sm:py-20 relative overflow-hidden">
    <!-- Background elements -->
    <div class="absolute top-10 left-1/4 w-96 h-96 bg-violet-600/10 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-10 right-1/4 w-80 h-80 bg-purple-600/10 rounded-full blur-3xl animate-float" style="animation-delay: -3s"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-violet-500/10 border border-violet-500/20 mb-4">
                <i class="fa-solid fa-pen-nib text-violet-400"></i>
                <span class="text-xs font-medium text-violet-300 uppercase tracking-wider">AI Text Overlay</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3">
                Smart <span class="bg-clip-text text-transparent bg-gradient-to-r from-violet-400 to-purple-400">Matn</span>
            </h1>
            <p class="text-gray-400 max-w-xl mx-auto">
                Mahsulot rasmingizga AI professional matn, sarlavha, xususiyatlar va sotuvchi badge'larni qo'shadi. Tayyor infografika!
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Panel — Controls -->
            <div class="lg:col-span-5 space-y-6 order-1 lg:order-1">
                <div class="glass-card p-6 sm:p-8 space-y-6">
                    <!-- Image Upload -->
                    <!-- Image Input Section -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex justify-between items-center">
                            <span class="flex items-center gap-2 text-violet-400/80"><i class="fa-solid fa-camera"></i> Mahsulot rasmi <span class="text-red-400">*</span></span>
                            <div class="flex p-0.5 bg-white/5 rounded-lg border border-white/5 text-[9px]">
                                <button type="button" onclick="switchImageMethod('file')" id="method-file-btn" class="px-2 py-1 rounded-md transition-all bg-violet-500/20 text-violet-400">FAYL</button>
                                <button type="button" onclick="switchImageMethod('url')" id="method-url-btn" class="px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300">HAVOLA</button>
                            </div>
                        </label>

                        <div id="image-input-container">
                            <!-- File area -->
                            <div id="method-file-area">
                                <label id="upload-label" class="flex flex-col items-center justify-center w-full h-44 border-2 border-dashed border-white/5 rounded-3xl bg-white/[0.03] hover:bg-white/[0.08] hover:border-violet-500/40 transition-all duration-300 cursor-pointer group relative overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-br from-violet-500/0 to-purple-500/0 group-hover:from-violet-500/5 group-hover:to-purple-500/5 transition-all duration-500"></div>
                                    <div class="relative z-10 flex flex-col items-center">
                                        <div class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-violet-500/10 transition-all duration-500 border border-white/5 group-hover:border-violet-500/20">
                                            <i class="fa-solid fa-cloud-arrow-up text-2xl text-gray-500 group-hover:text-violet-400"></i>
                                        </div>
                                        <span class="text-sm font-bold text-gray-300 group-hover:text-white transition-colors">Mahsulot rasmini yuklash</span>
                                        <span class="text-[10px] text-gray-500 mt-1 uppercase tracking-tighter">JPG, PNG • 10 MB</span>
                                    </div>
                                    <input id="file-input" type="file" accept="image/*" class="hidden" />
                                </label>
                            </div>

                            <!-- URL area -->
                            <div id="method-url-area" class="hidden">
                                <div class="relative">
                                    <input type="url" id="image-url-input" placeholder="https://example.com/item.jpg" class="input-field pl-10 h-12 text-sm" />
                                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500">🔗</div>
                                </div>
                                <p class="text-[10px] text-gray-500 mt-2">Rasm havolasini (URL) buni ustiga nusxalang.</p>
                            </div>

                            <!-- Preview area -->
                            <div id="preview-container" class="relative group hidden mt-2">
                                <img id="preview-image" src="" alt="Preview" class="w-full rounded-2xl bg-black/30 border border-violet-500/20" />
                                <button id="reset-btn" class="absolute top-2 right-2 w-7 h-7 bg-black/60 backdrop-blur text-white rounded-full flex items-center justify-center text-xs hover:bg-red-500 transition-colors shadow-lg"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Product Name -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-box text-violet-400/80"></i> Mahsulot nomi <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="product-name" placeholder="Masalan: Smart Soat X200 Pro" class="input-field" required />
                    </div>

                    <!-- Features -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-star text-violet-400/80"></i> Asosiy xususiyatlar <span class="text-red-400">*</span>
                        </label>
                        <textarea id="features-input" placeholder="Suv o'tmaydigan IP68
Batareya 7 kun
Sog'liq monitoring
GPS tracking
AMOLED ekran" rows="5" class="input-field resize-none text-sm" required></textarea>
                        <span class="text-[10px] text-gray-500 mt-1 block">Har bir qator — bitta xususiyat (3-6 ta tavsiya etiladi)</span>
                    </div>

                    <!-- Text Layout Style -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">
                            🎯 Matn joylashuvi
                        </label>
                        <div class="grid grid-cols-3 gap-2" id="layout-selector">
                            <button data-layout="overlay" class="layout-btn active relative overflow-hidden rounded-xl border p-3 h-20 flex flex-col items-center justify-center gap-1 transition-all border-violet-500 bg-violet-500/15 text-white ring-2 ring-violet-500/30">
                                <i class="fa-solid fa-file-lines text-lg mb-0.5"></i>
                                <div class="font-bold text-[9px] uppercase tracking-tighter text-center">Ustiga</div>
                                <div class="text-[8px] opacity-60 text-center">Rasm ustida matn</div>
                            </button>
                            <button data-layout="side" class="layout-btn relative overflow-hidden rounded-xl border p-3 h-20 flex flex-col items-center justify-center gap-1 transition-all border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <i class="fa-solid fa-ruler-combined text-lg mb-0.5 text-gray-500 group-hover:text-violet-400"></i>
                                <div class="font-bold text-[9px] uppercase tracking-tighter text-center">Yoniga</div>
                                <div class="text-[8px] opacity-60 text-center">Chapda rasm, o'ngda matn</div>
                            </button>
                            <button data-layout="banner" class="layout-btn relative overflow-hidden rounded-xl border p-3 h-20 flex flex-col items-center justify-center gap-1 transition-all border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <i class="fa-solid fa-tag text-lg mb-0.5 text-gray-500 group-hover:text-violet-400"></i>
                                <div class="font-bold text-[9px] uppercase tracking-tighter text-center">Banner</div>
                                <div class="text-[8px] opacity-60 text-center">Reklama banneri</div>
                            </button>
                        </div>
                    </div>

                    <!-- Color Theme -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-palette text-violet-400/80"></i> Rang sxemasi
                        </label>
                        <div class="grid grid-cols-4 gap-2" id="color-selector">
                            <button data-color="auto" class="color-btn active flex items-center justify-center h-10 rounded-xl border transition-all border-violet-500 bg-violet-500/15 ring-2 ring-violet-500/30">
                                <div class="w-5 h-5 rounded-full bg-gradient-to-br from-violet-500 via-blue-500 to-emerald-500"></div>
                                <span class="ml-1.5 text-[9px] font-bold text-white">Avtomatik</span>
                            </button>
                            <button data-color="dark" class="color-btn flex items-center justify-center h-10 rounded-xl border transition-all border-white/10 bg-white/5 hover:border-white/20">
                                <div class="w-5 h-5 rounded-full bg-gradient-to-br from-gray-800 to-gray-950 border border-white/20"></div>
                                <span class="ml-1.5 text-[9px] font-bold text-gray-400">Qora</span>
                            </button>
                            <button data-color="light" class="color-btn flex items-center justify-center h-10 rounded-xl border transition-all border-white/10 bg-white/5 hover:border-white/20">
                                <div class="w-5 h-5 rounded-full bg-gradient-to-br from-gray-100 to-white border border-gray-300"></div>
                                <span class="ml-1.5 text-[9px] font-bold text-gray-400">Oq</span>
                            </button>
                            <button data-color="gradient" class="color-btn flex items-center justify-center h-10 rounded-xl border transition-all border-white/10 bg-white/5 hover:border-white/20">
                                <div class="w-5 h-5 rounded-full bg-gradient-to-br from-indigo-500 to-pink-500"></div>
                                <span class="ml-1.5 text-[9px] font-bold text-gray-400">Gradient</span>
                            </button>
                        </div>
                    </div>

                    <!-- Language -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-language text-violet-400/80"></i> Matn tili
                        </label>
                        <div class="grid grid-cols-5 gap-2" id="lang-selector">
                            <button type="button" data-lang="ru" class="slang-btn active p-2 rounded-xl border text-center transition-all duration-200 border-violet-500 bg-violet-500/15 text-white">
                                <div class="text-[10px] font-bold">RUS</div>
                            </button>
                            <button type="button" data-lang="uz" class="slang-btn p-2 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20 hover:bg-white/10">
                                <div class="text-[10px] font-bold">UZB</div>
                            </button>
                            <button type="button" data-lang="en" class="slang-btn p-2 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20 hover:bg-white/10">
                                <div class="text-[10px] font-bold">ENG</div>
                            </button>
                            <button type="button" data-lang="tr" class="slang-btn p-2 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20 hover:bg-white/10">
                                <div class="text-[10px] font-bold">TUR</div>
                            </button>
                            <button type="button" data-lang="kz" class="slang-btn p-2 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20 hover:bg-white/10">
                                <div class="text-[10px] font-bold">KAZ</div>
                            </button>
                        </div>
                    </div>

                    <!-- Format -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-vector-square text-violet-400/80"></i> Format
                        </label>
                        <div class="grid grid-cols-3 sm:grid-cols-5 gap-2" id="ratio-selector">
                            <button data-ratio="3:4" class="ratio-btn active p-2 rounded-xl border text-center transition-all duration-200 border-violet-500 bg-violet-500/15 text-white shadow-lg shadow-violet-500/10">
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
                            <button data-ratio="9:16" class="ratio-btn p-2 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold uppercase">Story</div>
                                <div class="text-[8px] opacity-60 font-bold">9:16</div>
                            </button>
                            <button data-ratio="16:9" class="ratio-btn p-2 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold uppercase">Keng</div>
                                <div class="text-[8px] opacity-60 font-bold">16:9</div>
                            </button>
                        </div>
                    </div>

                    <!-- Custom Instructions -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-pen text-violet-400/80"></i> Qo'shimcha ko'rsatmalar (ixtiyoriy)
                        </label>
                        <textarea id="custom-prompt" placeholder="Masalan: Chegirma -30% yozuvini qo'sh, narxni ko'rsat..." rows="2" class="input-field resize-none text-sm"></textarea>
                    </div>

                    <!-- Generate Button -->
                    <button id="generate-btn" disabled class="btn-primary w-full h-16 text-lg font-extrabold disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 flex items-center justify-center gap-3">
                        <i class="fa-solid fa-wand-magic-sparkles text-xl"></i>
                        <span id="btn-text">Matn qo'shish</span>
                    </button>

                    <!-- Progress bar -->
                    <div id="progress-bar-container" class="hidden">
                        <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden">
                            <div id="progress-bar" class="h-full bg-gradient-to-r from-violet-600 via-purple-500 to-pink-500 rounded-full transition-all duration-300 ease-out shadow-[0_0_15px_rgba(139,92,246,0.5)]" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel — Result -->
            <div class="lg:col-span-7 order-2 lg:order-2">
                <div class="glass-card p-6 sm:p-8 min-h-[500px] flex flex-col items-center justify-center sticky top-24">
                    <!-- Loading state -->
                    <div id="loading-state" class="text-center animate-fade-in hidden">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-violet-600/20 to-purple-600/20 border border-violet-500/20 flex items-center justify-center mx-auto mb-6 animate-pulse">
                            <i class="fa-solid fa-pen-nib text-3xl text-violet-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">AI matn qo'shmoqda...</h3>
                        <p class="text-sm text-gray-400">Professional matnlar va badge'lar qo'shilmoqda. 15-45 soniya kutish.</p>
                        <div class="mt-6 flex justify-center gap-1">
                            <div class="w-2 h-2 rounded-full bg-violet-500 animate-pulse"></div>
                            <div class="w-2 h-2 rounded-full bg-violet-500 animate-pulse" style="animation-delay: 0.15s"></div>
                            <div class="w-2 h-2 rounded-full bg-violet-500 animate-pulse" style="animation-delay: 0.3s"></div>
                            <div class="w-2 h-2 rounded-full bg-violet-500 animate-pulse" style="animation-delay: 0.45s"></div>
                            <div class="w-2 h-2 rounded-full bg-violet-500 animate-pulse" style="animation-delay: 0.6s"></div>
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
                        <!-- Before / After -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="relative">
                                <img id="before-img" src="" alt="Oldin" class="w-full rounded-xl object-contain bg-black/30 border border-white/10" />
                                <span class="absolute top-2 left-2 bg-black/60 backdrop-blur px-2.5 py-1 rounded-lg text-[10px] font-bold text-white uppercase tracking-wider">Oldin</span>
                            </div>
                            <div class="relative">
                                <img id="after-img" src="" alt="Keyin" class="w-full rounded-xl object-contain bg-black/30 border border-violet-500/30 shadow-xl shadow-violet-500/10" />
                                <span class="absolute top-2 left-2 bg-violet-500/80 backdrop-blur px-2.5 py-1 rounded-lg text-[10px] font-bold text-white uppercase tracking-wider">Keyin</span>
                            </div>
                        </div>

                        <!-- Full Result -->
                        <div class="relative group">
                            <img id="full-result-img" src="" alt="Natija" class="w-full rounded-xl shadow-2xl shadow-violet-500/10" />
                            <div class="absolute inset-0 bg-black/50 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-4">
                                <button id="download-hover-btn" class="btn-primary px-6 py-3 flex items-center gap-2">
                                    <i class="fa-solid fa-download"></i>
                                    Yuklab olish
                                </button>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button id="download-btn" class="btn-primary flex-1 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-download"></i>
                                Rasmni yuklab olish
                            </button>
                            <button id="reset-result-btn" class="btn-secondary px-6" title="Qaytadan boshlash">
                                <i class="fa-solid fa-rotate-left"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Empty state -->
                    <div id="empty-state" class="text-center">
                        <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-violet-600/20 to-purple-600/20 border border-violet-500/20 flex items-center justify-center mx-auto mb-6">
                            <i class="fa-solid fa-pen-nib text-4xl text-violet-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Natija shu yerda ko'rinadi</h3>
                        <p class="text-sm text-gray-500 max-w-sm mx-auto">
                            Rasmni yuklang, mahsulot ma'lumotlarini kiriting va AI professional matnlarni qo'shadi.
                        </p>
                        <!-- Example preview -->
                        <div class="mt-6 glass-card p-4 max-w-xs mx-auto text-left border border-violet-500/10">
                            <div class="text-[10px] text-violet-400 font-bold uppercase tracking-wider mb-2">AI qo'shadigan elementlar:</div>
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-violet-500/20 flex items-center justify-center">
                                        <i class="fa-solid fa-file-signature text-[10px] text-violet-400"></i>
                                    </div>
                                    <span class="text-[11px] text-gray-400">Mahsulot sarlavhasi</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-purple-500/20 flex items-center justify-center">
                                        <i class="fa-solid fa-star text-[10px] text-purple-400"></i>
                                    </div>
                                    <span class="text-[11px] text-gray-400">Xususiyat ikonkalari + matn</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-pink-500/20 flex items-center justify-center">
                                        <i class="fa-solid fa-tag text-[10px] text-pink-400"></i>
                                    </div>
                                    <span class="text-[11px] text-gray-400">Narx / chegirma badge'lari</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-indigo-500/20 flex items-center justify-center">
                                        <i class="fa-solid fa-check text-[10px] text-indigo-400"></i>
                                    </div>
                                    <span class="text-[11px] text-gray-400">Kafolat / sifat tamg'asi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    let previewUrl = null;
    let selectedLayout = 'overlay';
    let selectedColor = 'auto';
    let selectedLang = 'ru';
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
    const productName = document.getElementById('product-name');
    const featuresInput = document.getElementById('features-input');
    const customPrompt = document.getElementById('custom-prompt');
    const generateBtn = document.getElementById('generate-btn');
    const btnText = document.getElementById('btn-text');
    const progressBarContainer = document.getElementById('progress-bar-container');
    const progressBar = document.getElementById('progress-bar');

    let currentMethod = 'file';

    // Switch methods
    window.switchImageMethod = function(method) {
        currentMethod = method;
        if (method === 'file') {
            methodFileArea.classList.remove('hidden');
            methodUrlArea.classList.add('hidden');
            methodFileBtn.className = 'px-2 py-1 rounded-md transition-all bg-violet-500/20 text-violet-400';
            methodUrlBtn.className = 'px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300';
            if (previewUrl && previewUrl.startsWith('http')) handleReset();
        } else {
            methodFileArea.classList.add('hidden');
            methodUrlArea.classList.remove('hidden');
            methodUrlBtn.className = 'px-2 py-1 rounded-md transition-all bg-violet-500/20 text-violet-400';
            methodFileBtn.className = 'px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300';
            if (previewUrl && previewUrl.startsWith('data:')) handleReset();
        }
    };

    // Check form
    function checkReady() {
        generateBtn.disabled = !(previewUrl && productName.value.trim() && featuresInput.value.trim());
    }
    productName.addEventListener('input', checkReady);
    featuresInput.addEventListener('input', checkReady);

    // URL input
    imageUrlInput.addEventListener('input', function() {
        const url = this.value.trim();
        if (url && (url.startsWith('http://') || url.startsWith('https://'))) {
            previewUrl = url;
            previewImage.src = url;
            methodUrlArea.classList.add('hidden');
            previewContainer.classList.remove('hidden');
            checkReady();
            showState('empty');
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
                checkReady();
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
        if (currentMethod === 'file') methodFileArea.classList.remove('hidden');
        else methodUrlArea.classList.remove('hidden');

        productName.value = '';
        featuresInput.value = '';
        customPrompt.value = '';
        generateBtn.disabled = true;
        showState('empty');
    }
    resetBtn.addEventListener('click', handleReset);
    document.getElementById('reset-result-btn').addEventListener('click', handleReset);

    // Layout selector
    document.querySelectorAll('.layout-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.layout-btn').forEach(b => {
                b.classList.remove('border-violet-500', 'bg-violet-500/15', 'text-white', 'ring-2', 'ring-violet-500/30');
                b.classList.add('border-white/10', 'bg-white/5', 'text-gray-400');
            });
            this.classList.remove('border-white/10', 'bg-white/5', 'text-gray-400');
            this.classList.add('border-violet-500', 'bg-violet-500/15', 'text-white', 'ring-2', 'ring-violet-500/30');
            selectedLayout = this.dataset.layout;
        });
    });

    // Color selector
    document.querySelectorAll('.color-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.color-btn').forEach(b => {
                b.classList.remove('border-violet-500', 'bg-violet-500/15', 'ring-2', 'ring-violet-500/30');
                b.classList.add('border-white/10', 'bg-white/5');
                b.querySelector('span').classList.remove('text-white');
                b.querySelector('span').classList.add('text-gray-400');
            });
            this.classList.remove('border-white/10', 'bg-white/5');
            this.classList.add('border-violet-500', 'bg-violet-500/15', 'ring-2', 'ring-violet-500/30');
            this.querySelector('span').classList.remove('text-gray-400');
            this.querySelector('span').classList.add('text-white');
            selectedColor = this.dataset.color;
        });
    });

    // Language selector
    document.querySelectorAll('.slang-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.slang-btn').forEach(b => {
                b.className = 'slang-btn p-2 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20 hover:bg-white/10';
            });
            this.className = 'slang-btn active p-2 rounded-xl border text-center transition-all duration-200 border-violet-500 bg-violet-500/15 text-white';
            selectedLang = this.dataset.lang;
        });
    });

    // Ratio selector
    document.querySelectorAll('.ratio-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.ratio-btn').forEach(b => {
                b.className = 'ratio-btn p-2 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20';
            });
            this.className = 'ratio-btn active p-2 rounded-xl border text-center transition-all duration-200 border-violet-500 bg-violet-500/15 text-white shadow-lg shadow-violet-500/10';
            selectedRatio = this.dataset.ratio;
        });
    });

    // Show state
    function showState(state) {
        document.getElementById('loading-state').classList.add('hidden');
        document.getElementById('error-state').classList.add('hidden');
        document.getElementById('result-state').classList.add('hidden');
        document.getElementById('empty-state').classList.add('hidden');
        if (state === 'loading') document.getElementById('loading-state').classList.remove('hidden');
        if (state === 'error') document.getElementById('error-state').classList.remove('hidden');
        if (state === 'result') document.getElementById('result-state').classList.remove('hidden');
        if (state === 'empty') document.getElementById('empty-state').classList.remove('hidden');
    }

    // Generate
    generateBtn.addEventListener('click', function() {
        TibaAuth.requireAuth(async function() {
        if (!previewUrl || !productName.value.trim() || !featuresInput.value.trim()) return;

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
            const response = await fetch('/api/smart-matn.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-User-Token': TibaAuth.getToken()
                },
                body: JSON.stringify({
                    productImage: previewUrl,
                    productName: productName.value,
                    features: featuresInput.value,
                    layout: selectedLayout,
                    colorTheme: selectedColor,
                    language: selectedLang,
                    aspectRatio: selectedRatio,
                    customPrompt: customPrompt.value,
                })
            });

            const text = await response.text();
            let data;
            try { data = JSON.parse(text); } catch { throw new Error("Serverdan noto'g'ri javob keldi."); }
            if (!response.ok) throw new Error(data.error || 'Xatolik yuz berdi');

            resultUrl = data.imageUrl;
            progress = 100;
            progressBar.style.width = '100%';

            document.getElementById('before-img').src = previewUrl;
            document.getElementById('after-img').src = resultUrl;
            document.getElementById('full-result-img').src = resultUrl;
            showState('result');
        } catch (err) {
            document.getElementById('error-text').textContent = err.message;
            showState('error');
        } finally {
            clearInterval(progressInterval);
            checkReady();
            btnText.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles mr-2"></i>Matn qo\'shish';
            progressBarContainer.classList.add('hidden');
        }
        });
    });

    // Download
    function handleDownload() {
        if (!resultUrl) return;
        const a = document.createElement('a');
        a.href = resultUrl;
        a.download = 'smart-matn-' + Date.now() + '.png';
        a.target = '_blank';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }
    document.getElementById('download-btn').addEventListener('click', handleDownload);
    document.getElementById('download-hover-btn').addEventListener('click', handleDownload);

    // Retry
    document.getElementById('retry-btn').addEventListener('click', () => showState('empty'));
})();
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
