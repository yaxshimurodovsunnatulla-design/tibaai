<?php $pageTitle = 'Marketplace Infografika – Tiba AI'; ?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="py-12 sm:py-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12 animate-fade-in-up">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 mb-4">
                <span class="text-lg">🎨</span>
                <span class="text-xs font-medium text-indigo-300">Premium AI generator</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3">
                Marketplace <span class="gradient-text">Infografika</span>
            </h1>
            <p class="text-gray-400 max-w-xl mx-auto">
                Mahsulot ma'lumotlarini kiriting va AI sizga professional sotuvchi infografika yaratsin.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Form Card -->
            <div class="glass-card p-6 sm:p-8 animate-fade-in-up" style="animation-delay: 0.1s">
                <form id="infographic-form" class="space-y-6">
                    <!-- Product Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Mahsulot nomi
                        </label>
                        <input
                            type="text"
                            id="product-name"
                            placeholder="Masalan: Smart soat X100"
                            class="input-field"
                            required
                        />
                    </div>

                    <!-- Features -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Asosiy xususiyatlar (har bir qator – 1 xususiyat)
                        </label>
                        <textarea
                            id="features"
                            placeholder="Suv o'tmaydigan IP68&#10;Batareya 7 kun&#10;Sog'liq monitoring&#10;GPS tracking&#10;Bluetooth 5.3"
                            rows="5"
                            class="input-field resize-none"
                            required
                        ></textarea>
                    </div>

                    <!-- Style Selector -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-3">
                            Infografika stili
                        </label>
                        <div class="grid grid-cols-3 gap-3" id="style-selector">
                            <button type="button" data-style="marketplace" class="style-btn p-3 rounded-xl border text-center transition-all duration-200 border-indigo-500 bg-indigo-500/15 text-white">
                                <div class="text-sm font-semibold">Marketplace</div>
                                <div class="text-xs mt-0.5 opacity-70">Sotuvchi dizayn</div>
                            </button>
                            <button type="button" data-style="instagram" class="style-btn p-3 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20 hover:bg-white/10">
                                <div class="text-sm font-semibold">Instagram</div>
                                <div class="text-xs mt-0.5 opacity-70">Reklama post</div>
                            </button>
                            <button type="button" data-style="minimal" class="style-btn p-3 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20 hover:bg-white/10">
                                <div class="text-sm font-semibold">Minimal</div>
                                <div class="text-xs mt-0.5 opacity-70">Toza dizayn</div>
                            </button>
                        </div>
                    </div>

                    <!-- Language Selector -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-3">
                            Infografika tili
                        </label>
                        <div class="grid grid-cols-5 gap-2" id="lang-selector">
                            <button type="button" data-lang="ru" class="lang-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-indigo-500 bg-indigo-500/15 text-white">
                                <div class="text-sm font-semibold">🇷🇺 RU</div>
                            </button>
                            <button type="button" data-lang="uz" class="lang-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20 hover:bg-white/10">
                                <div class="text-sm font-semibold">🇺🇿 UZ</div>
                            </button>
                            <button type="button" data-lang="en" class="lang-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20 hover:bg-white/10">
                                <div class="text-sm font-semibold">🇬🇧 EN</div>
                            </button>
                            <button type="button" data-lang="tr" class="lang-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20 hover:bg-white/10">
                                <div class="text-sm font-semibold">🇹🇷 TR</div>
                            </button>
                            <button type="button" data-lang="kz" class="lang-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20 hover:bg-white/10">
                                <div class="text-sm font-semibold">🇰🇿 KZ</div>
                            </button>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Mahsulot rasmi (ixtiyoriy)
                        </label>
                    <!-- Image Input Section -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-3 flex justify-between items-center">
                            <span>Mahsulot rasmi (ixtiyoriy)</span>
                            <div class="flex p-0.5 bg-white/5 rounded-lg border border-white/5 text-[9px]">
                                <button type="button" onclick="switchImageMethod('file')" id="method-file-btn" class="px-2 py-1 rounded-md transition-all bg-indigo-500/20 text-indigo-400">FAYL</button>
                                <button type="button" onclick="switchImageMethod('url')" id="method-url-btn" class="px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300">HAVOLA</button>
                            </div>
                        </label>
                        
                        <div id="image-input-container">
                            <!-- File area -->
                            <div id="method-file-area">
                                <label id="drop-area" class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-white/10 rounded-xl bg-white/5 hover:bg-white/10 hover:border-indigo-500/40 transition-all cursor-pointer group">
                                    <svg class="w-8 h-8 text-gray-500 mb-2 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                    </svg>
                                    <span class="text-xs text-gray-500">Mahsulot rasmini yuklang</span>
                                    <input id="image-upload" type="file" accept="image/*" class="hidden" />
                                </label>
                            </div>

                            <!-- URL area -->
                            <div id="method-url-area" class="hidden">
                                <div class="relative">
                                    <input type="url" id="image-url-input" placeholder="https://example.com/item.jpg" class="input-field pl-8 h-12 text-sm" />
                                    <div class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-500">🔗</div>
                                </div>
                                <p class="text-[10px] text-gray-500 mt-2 italic">Mahsulot havolasini buni ustiga nusxalang.</p>
                            </div>

                            <!-- Preview area -->
                            <div id="image-preview-container" class="relative group hidden mt-2">
                                <img id="image-preview" src="" alt="Preview" class="h-36 rounded-xl object-contain bg-black/30 border border-indigo-500/20 mx-auto" />
                                <button type="button" id="remove-image" class="absolute top-2 right-2 w-7 h-7 bg-black/60 backdrop-blur text-white rounded-full flex items-center justify-center text-xs hover:bg-red-500 transition-all shadow-lg">✕</button>
                            </div>
                        </div>
                    </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submit-btn" class="btn-primary w-full shadow-lg shadow-indigo-500/20">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                        </svg>
                        Infografika yaratish
                    </button>
                </form>
            </div>

            <!-- Result Card -->
            <div id="result-container" class="glass-card p-6 sm:p-8 flex flex-col items-center justify-center min-h-[400px] animate-fade-in-up" style="animation-delay: 0.2s">
                <!-- Initial State -->
                <div id="state-initial" class="text-center">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-600/20 to-purple-600/20 border border-indigo-500/20 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a2.25 2.25 0 002.25-2.25V5.25a2.25 2.25 0 00-2.25-2.25H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Natija shu yerda ko'rinadi</h3>
                    <p class="text-sm text-gray-500">Formani to'ldiring va "Infografika yaratish" tugmasini bosing.</p>
                </div>

                <!-- Loading State -->
                <div id="state-loading" class="hidden text-center animate-fade-in">
                    <div class="loader mx-auto mb-6"></div>
                    <h3 class="text-lg font-semibold text-white mb-2">AI ishlayapti...</h3>
                    <p class="text-sm text-gray-400">Infografika yaratilmoqda. Bu 15-30 soniya davom etishi mumkin.</p>
                    <div class="mt-6 flex justify-center gap-1">
                        <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                        <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse" style="animation-delay: 0.15s"></div>
                        <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse" style="animation-delay: 0.3s"></div>
                        <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse" style="animation-delay: 0.45s"></div>
                    </div>
                </div>

                <!-- Result State -->
                <div id="state-result" class="hidden w-full animate-fade-in">
                    <div class="relative group">
                        <img id="result-img" src="" alt="Dizayn" class="w-full rounded-xl shadow-2xl shadow-indigo-500/10">
                        <div class="absolute inset-0 bg-black/50 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <a id="download-btn-overlay" href="#" download class="btn-primary">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                Yuklab olish
                            </a>
                        </div>
                    </div>
                    <div class="flex flex-col gap-3 mt-4">
                        <a id="download-btn" href="#" download class="btn-primary w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Rasmni yuklab olish
                        </a>
                        <button id="reset-btn" class="btn-secondary w-full">
                            Yangi yaratish
                        </button>
                    </div>
                </div>

                <!-- Error State -->
                <div id="state-error" class="hidden text-center animate-fade-in">
                    <div class="w-16 h-16 rounded-2xl bg-red-500/10 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-red-400 mb-2">Xatolik yuz berdi</h3>
                    <p id="error-message" class="text-sm text-gray-400 mb-6"></p>
                    <button id="retry-btn" class="btn-secondary px-8">Qayta urinish</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let selectedStyle = 'marketplace';
    let selectedLang = 'ru';
    let imagePreview = null;
    let currentMethod = 'file';

    const form = document.getElementById('infographic-form');
    const styleBtns = document.querySelectorAll('.style-btn');
    const langBtns = document.querySelectorAll('.lang-btn');
    const imageUpload = document.getElementById('image-upload');
    const imageUrlInput = document.getElementById('image-url-input');
    const imagePreviewContainer = document.getElementById('image-preview-container');
    const imagePreviewImg = document.getElementById('image-preview');
    const removeImageBtn = document.getElementById('remove-image');
    
    const methodFileArea = document.getElementById('method-file-area');
    const methodUrlArea = document.getElementById('method-url-area');
    const methodFileBtn = document.getElementById('method-file-btn');
    const methodUrlBtn = document.getElementById('method-url-btn');

    const stateInitial = document.getElementById('state-initial');
    const stateLoading = document.getElementById('state-loading');
    const stateResult = document.getElementById('state-result');
    const stateError = document.getElementById('state-error');
    const resultImg = document.getElementById('result-img');
    const downloadBtn = document.getElementById('download-btn');
    const downloadBtnOverlay = document.getElementById('download-btn-overlay');
    const resetBtn = document.getElementById('reset-btn');
    const retryBtn = document.getElementById('retry-btn');
    const errorMessage = document.getElementById('error-message');
    const submitBtn = document.getElementById('submit-btn');

    // Method Switcher
    window.switchImageMethod = function(method) {
        currentMethod = method;
        if (method === 'file') {
            methodFileArea.classList.remove('hidden');
            methodUrlArea.classList.add('hidden');
            methodFileBtn.className = 'px-2 py-1 rounded-md transition-all bg-indigo-500/20 text-indigo-400';
            methodUrlBtn.className = 'px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300';
            if (imagePreview && imagePreview.startsWith('http')) clearImage();
        } else {
            methodFileArea.classList.add('hidden');
            methodUrlArea.classList.remove('hidden');
            methodUrlBtn.className = 'px-2 py-1 rounded-md transition-all bg-indigo-500/20 text-indigo-400';
            methodFileBtn.className = 'px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300';
            if (imagePreview && imagePreview.startsWith('data:')) clearImage();
        }
    };

    // Style Selection
    styleBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            selectedStyle = btn.dataset.style;
            styleBtns.forEach(b => {
                b.className = 'style-btn p-3 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20 hover:bg-white/10';
            });
            btn.className = 'style-btn p-3 rounded-xl border text-center transition-all duration-200 border-indigo-500 bg-indigo-500/15 text-white';
        });
    });

    // Language Selection
    langBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            selectedLang = btn.dataset.lang;
            langBtns.forEach(b => {
                b.className = 'lang-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20 hover:bg-white/10';
            });
            btn.className = 'lang-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-indigo-500 bg-indigo-500/15 text-white';
        });
    });

    // URL input logic
    imageUrlInput.addEventListener('input', function() {
        const url = this.value.trim();
        if (url && (url.startsWith('http://') || url.startsWith('https://'))) {
            imagePreview = url;
            imagePreviewImg.src = url;
            imagePreviewContainer.classList.remove('hidden');
            methodUrlArea.classList.add('hidden');
        }
    });

    // Image Upload Logic
    imageUpload.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                imagePreview = event.target.result;
                imagePreviewImg.src = imagePreview;
                imagePreviewContainer.classList.remove('hidden');
                methodFileArea.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    function clearImage() {
        imagePreview = null;
        imageUpload.value = '';
        imageUrlInput.value = '';
        imagePreviewContainer.classList.add('hidden');
        if (currentMethod === 'file') methodFileArea.classList.remove('hidden');
        else methodUrlArea.classList.remove('hidden');
    }

    removeImageBtn.addEventListener('click', clearImage);

    // Form Submit
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const productName = document.getElementById('product-name').value;
        const featuresText = document.getElementById('features').value;
        const features = featuresText.split('\n').filter(f => f.trim() !== '');

        if (!productName || features.length === 0) return;

        // Show Loading
        [stateInitial, stateResult, stateError].forEach(s => s.classList.add('hidden'));
        stateLoading.classList.remove('hidden');
        submitBtn.disabled = true;

        try {
            const response = await fetch('/api/generate.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    productName,
                    features,
                    style: selectedStyle,
                    language: selectedLang,
                    productImage: imagePreview
                })
            });

            const data = await response.json();

            if (data.success) {
                resultImg.src = data.imageUrl + '?v=' + Date.now();
                downloadBtn.href = data.imageUrl;
                downloadBtnOverlay.href = data.imageUrl;
                stateLoading.classList.add('hidden');
                stateResult.classList.remove('hidden');
            } else {
                throw new Error(data.error || 'Server xatosi yuz berdi');
            }
        } catch (err) {
            errorMessage.innerText = err.message;
            stateLoading.classList.add('hidden');
            stateError.classList.remove('hidden');
        } finally {
            submitBtn.disabled = false;
        }
    });

    resetBtn.addEventListener('click', () => {
        form.reset();
        clearImage();
        stateResult.classList.add('hidden');
        stateInitial.classList.remove('hidden');
    });

    retryBtn.addEventListener('click', () => {
        stateError.classList.add('hidden');
        stateInitial.classList.remove('hidden');
    });
});
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
