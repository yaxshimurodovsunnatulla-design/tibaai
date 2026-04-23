<?php $pageTitle = 'Kartochka AI – Tiba AI'; ?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="py-12 sm:py-20 relative overflow-hidden">
    <!-- Background -->
    <div class="absolute top-10 left-1/4 w-96 h-96 bg-amber-600/10 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-10 right-1/4 w-80 h-80 bg-orange-600/10 rounded-full blur-3xl animate-float" style="animation-delay: -3s"></div>

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
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/20 mb-4">
                <i class="fa-solid fa-brain text-amber-400"></i>
                <span class="text-xs font-medium text-amber-300">AI Product Card Generator</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3">
                Kartochka <span class="bg-clip-text text-transparent bg-gradient-to-r from-amber-400 to-orange-400">AI</span>
            </h1>
            <p class="text-gray-400 max-w-xl mx-auto">
                Mahsulot rasmini yuklang — AI kartochka uchun barcha ma'lumotlarni <span class="text-amber-400 font-semibold">O'zbekcha</span> va <span class="text-blue-400 font-semibold">Ruscha</span> tillarida tayyorlab beradi.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Panel — Controls -->
            <div class="lg:col-span-4 space-y-6 order-1 lg:order-1">
                <div class="glass-card p-6 sm:p-8 space-y-6">
                    <!-- Image Input Section -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex justify-between items-center">
                            <span><i class="fa-solid fa-camera mr-1"></i> Mahsulot rasmi <span class="text-red-400">*</span></span>
                            <div class="flex p-0.5 bg-white/5 rounded-lg border border-white/5 text-[9px]">
                                <button type="button" onclick="switchImageMethod('file')" id="method-file-btn" class="px-2 py-1 rounded-md transition-all bg-amber-500/20 text-amber-400">FAYL</button>
                                <button type="button" onclick="switchImageMethod('url')" id="method-url-btn" class="px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300">HAVOLA</button>
                            </div>
                        </label>
                        
                        <div id="image-input-container">
                            <!-- File area -->
                            <div id="method-file-area">
                                <label id="upload-label" class="flex flex-col items-center justify-center w-full h-44 border-2 border-dashed border-white/5 rounded-3xl bg-white/[0.03] hover:bg-white/[0.08] hover:border-amber-500/40 transition-all duration-300 cursor-pointer group relative overflow-hidden">
                                    <!-- Background Glow -->
                                    <div class="absolute inset-0 bg-gradient-to-br from-amber-500/0 to-orange-500/0 group-hover:from-amber-500/5 group-hover:to-orange-500/5 transition-all duration-500"></div>
                                    
                                    <div class="relative z-10 flex flex-col items-center">
                                        <div class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-amber-500/10 transition-all duration-500 border border-white/5 group-hover:border-amber-500/20">
                                            <i class="fa-solid fa-brain text-2xl text-gray-500 group-hover:text-amber-400"></i>
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
                                    <input type="url" id="image-url-input" placeholder="https://example.com/item.jpg" class="input-field pl-10 h-12 text-sm" />
                                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500"><i class="fa-solid fa-link"></i></div>
                                </div>
                                <p class="text-[10px] text-gray-500 mt-2">Mahsulot havolasini buni ustiga nusxalang.</p>
                            </div>

                            <!-- Preview area -->
                            <div id="preview-container" class="relative group hidden mt-2">
                                <img id="preview-image" src="" alt="Preview" class="w-full rounded-2xl bg-black/30 border border-amber-500/20" />
                                <button id="reset-btn" class="absolute top-2 right-2 w-7 h-7 bg-black/60 backdrop-blur text-white rounded-full flex items-center justify-center text-xs hover:bg-red-500 transition-colors shadow-lg">✕</button>
                            </div>
                        </div>
                    </div>

                    <!-- Marketplace -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3"><i class="fa-solid fa-shop mr-1"></i> Marketplace</label>
                        <div class="grid grid-cols-3 gap-2" id="marketplace-selector">
                            <button data-mp="uzum" class="mp-btn active p-2.5 rounded-xl border text-center transition-all border-amber-500 bg-amber-500/15 text-white ring-2 ring-amber-500/30">
                                <div class="text-lg mb-0.5 text-indigo-500"><i class="fa-solid fa-circle"></i></div>
                                <div class="text-[10px] font-bold">Uzum</div>
                            </button>
                            <button data-mp="wildberries" class="mp-btn p-2.5 rounded-xl border text-center transition-all border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-lg mb-0.5 text-purple-500"><i class="fa-solid fa-circle"></i></div>
                                <div class="text-[10px] font-bold">Wildberries</div>
                            </button>
                            <button data-mp="universal" class="mp-btn p-2.5 rounded-xl border text-center transition-all border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-lg mb-0.5 text-gray-400"><i class="fa-solid fa-globe"></i></div>
                                <div class="text-[10px] font-bold">Universal</div>
                            </button>
                        </div>
                    </div>

                    <!-- Language Info -->
                    <div class="glass-card p-3 border border-indigo-500/10">
                        <div class="flex items-center gap-3 text-xs">
                            <span class="text-lg text-indigo-400"><i class="fa-solid fa-language"></i></span>
                            <div class="text-gray-400">
                                AI bir vaqtda <span class="text-amber-400 font-bold">🇺🇿 O'zbekcha</span> va <span class="text-blue-400 font-bold">🇷🇺 Ruscha</span> tillarida tayyorlaydi
                            </div>
                        </div>
                    </div>

                    <!-- Generate -->
                    <button id="generate-btn" disabled class="btn-primary w-full h-16 text-lg font-extrabold disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                        <i class="fa-solid fa-brain mr-2"></i>
                        <span id="btn-text">Tahlil qilish</span>
                    </button>

                    <!-- Progress -->
                    <div id="progress-bar-container" class="hidden">
                        <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden">
                            <div id="progress-bar" class="h-full bg-gradient-to-r from-amber-600 via-orange-500 to-red-500 rounded-full transition-all duration-300 ease-out shadow-[0_0_15px_rgba(245,158,11,0.5)]" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel — Results -->
            <div class="lg:col-span-8 order-2 lg:order-2">
                <div class="glass-card p-6 sm:p-8 min-h-[500px] sticky top-24">
                    <!-- Loading -->
                    <div id="loading-state" class="text-center animate-fade-in hidden flex flex-col items-center justify-center min-h-[400px]">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-amber-600/20 to-orange-600/20 border border-amber-500/20 flex items-center justify-center mx-auto mb-6 animate-pulse">
                            <i class="fa-solid fa-brain text-3xl text-amber-500"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">AI mahsulotni tahlil qilmoqda...</h3>
                        <p class="text-sm text-gray-400">O'zbekcha va Ruscha ma'lumotlarni tayyorlamoqda. 15-30 soniya.</p>
                        <div class="mt-6 flex justify-center gap-1">
                            <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div>
                            <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse" style="animation-delay: 0.15s"></div>
                            <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse" style="animation-delay: 0.3s"></div>
                        </div>
                    </div>

                    <!-- Error -->
                    <div id="error-state" class="text-center animate-fade-in hidden flex flex-col items-center justify-center min-h-[400px]">
                        <div class="w-16 h-16 rounded-2xl bg-red-500/10 flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-triangle-exclamation text-3xl text-red-500"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-red-400 mb-2">Xatolik</h3>
                        <p id="error-text" class="text-sm text-gray-400 mb-4"></p>
                        <button id="retry-btn" class="btn-secondary text-sm px-6 py-2">Qayta urinish</button>
                    </div>

                    <!-- Result -->
                    <div id="result-state" class="animate-fade-in hidden">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center">
                                    <i class="fa-solid fa-check text-white text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">Kartochka tayyor!</h3>
                                    <p class="text-xs text-gray-500">2 tilda — O'zbekcha va Ruscha</p>
                                </div>
                            </div>
                        </div>

                        <!-- Language Tabs -->
                        <div class="flex gap-1 mb-5 p-1 bg-white/5 rounded-xl border border-white/5">
                            <button id="tab-uz" class="lang-tab active flex-1 flex items-center justify-center gap-2 py-3 px-4 rounded-lg text-sm font-bold transition-all bg-amber-500/20 text-amber-300 border border-amber-500/20">
                                <span>🇺🇿</span> O'zbekcha
                            </button>
                            <button id="tab-ru" class="lang-tab flex-1 flex items-center justify-center gap-2 py-3 px-4 rounded-lg text-sm font-bold transition-all text-gray-500 hover:text-gray-300 hover:bg-white/5">
                                <span>🇷🇺</span> Ruscha
                            </button>
                        </div>

                        <!-- Copy All (per language) -->
                        <div class="flex gap-2 mb-4">
                            <button id="copy-all-btn" class="btn-primary text-xs px-4 py-2 flex-1">
                                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184" />
                                </svg>
                                <span id="copy-all-label">O'zbekcha nusxalash</span>
                            </button>
                            <button id="copy-both-btn" class="btn-secondary text-xs px-4 py-2">
                                <i class="fa-solid fa-copy mr-1.5"></i> Ikkalasini nusxalash
                            </button>
                        </div>

                        <!-- Content blocks -->
                        <div id="result-content-uz" class="space-y-4"></div>
                        <div id="result-content-ru" class="space-y-4 hidden"></div>
                    </div>

                    <!-- Empty -->
                    <div id="empty-state" class="text-center flex flex-col items-center justify-center min-h-[400px]">
                        <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-amber-600/20 to-orange-600/20 border border-amber-500/20 flex items-center justify-center mx-auto mb-6">
                            <i class="fa-solid fa-brain text-4xl text-amber-500"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Kartochka ma'lumotlari shu yerda chiqadi</h3>
                        <p class="text-sm text-gray-500 max-w-md mx-auto mb-6">
                            Mahsulot rasmini yuklang va AI quyidagi ma'lumotlarni <strong class="text-amber-400">O'zbekcha</strong> va <strong class="text-blue-400">Ruscha</strong> tillarida tayyorlaydi:
                        </p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 max-w-md mx-auto text-left">
                            <div class="glass-card p-3 border border-amber-500/10">
                                <div class="text-xs font-bold text-amber-400 mb-1"><i class="fa-solid fa-pen-to-square mr-1"></i> Nom</div>
                                <div class="text-[10px] text-gray-500">SEO sarlavha</div>
                            </div>
                            <div class="glass-card p-3 border border-orange-500/10">
                                <div class="text-xs font-bold text-orange-400 mb-1"><i class="fa-solid fa-list-check mr-1"></i> Tavsif</div>
                                <div class="text-[10px] text-gray-500">Qisqa + To'liq</div>
                            </div>
                            <div class="glass-card p-3 border border-yellow-500/10">
                                <div class="text-xs font-bold text-yellow-400 mb-1"><i class="fa-solid fa-star mr-1"></i> Xususiyatlar</div>
                                <div class="text-[10px] text-gray-500">5-8 ta xususiyat</div>
                            </div>
                            <div class="glass-card p-3 border border-cyan-500/10">
                                <div class="text-xs font-bold text-cyan-400 mb-1"><i class="fa-solid fa-ruler-combined mr-1"></i> Texnik</div>
                                <div class="text-[10px] text-gray-500">Spetsifikatsiya</div>
                            </div>
                            <div class="glass-card p-3 border border-red-500/10">
                                <div class="text-xs font-bold text-red-400 mb-1"><i class="fa-solid fa-key mr-1"></i> SEO</div>
                                <div class="text-[10px] text-gray-500">Kalit so'zlar</div>
                            </div>
                            <div class="glass-card p-3 border border-emerald-500/10">
                                <div class="text-xs font-bold text-emerald-400 mb-1"><i class="fa-solid fa-tag mr-1"></i> Narx</div>
                                <div class="text-[10px] text-gray-500">Taxminiy narx</div>
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
    let selectedMarketplace = 'uzum';
    let resultData = null;
    let activeLang = 'uz';
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

    let currentMethod = 'file';

    // Switch methods
    window.switchImageMethod = function(method) {
        currentMethod = method;
        if (method === 'file') {
            methodFileArea.classList.remove('hidden');
            methodUrlArea.classList.add('hidden');
            methodFileBtn.className = 'px-2 py-1 rounded-md transition-all bg-amber-500/20 text-amber-400';
            methodUrlBtn.className = 'px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300';
            if (previewUrl && previewUrl.startsWith('http')) resetImage();
        } else {
            methodFileArea.classList.add('hidden');
            methodUrlArea.classList.remove('hidden');
            methodUrlBtn.className = 'px-2 py-1 rounded-md transition-all bg-amber-500/20 text-amber-400';
            methodFileBtn.className = 'px-2 py-1 rounded-md transition-all text-gray-500 hover:text-gray-300';
            if (previewUrl && previewUrl.startsWith('data:')) resetImage();
        }
    };

    function checkReady() { generateBtn.disabled = !previewUrl; }

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

    // Upload
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
                showState('empty');
            };
            reader.readAsDataURL(file);
        }
    });

    function resetImage() {
        previewUrl = null;
        fileInput.value = '';
        imageUrlInput.value = '';
        previewContainer.classList.add('hidden');
        if (currentMethod === 'file') methodFileArea.classList.remove('hidden');
        else methodUrlArea.classList.remove('hidden');
        checkReady();
        showState('empty');
    }
    resetBtn.addEventListener('click', resetImage);

    // Marketplace
    document.querySelectorAll('.mp-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.mp-btn').forEach(b => {
                b.classList.remove('border-amber-500', 'bg-amber-500/15', 'text-white', 'ring-2', 'ring-amber-500/30');
                b.classList.add('border-white/10', 'bg-white/5', 'text-gray-400');
            });
            this.classList.remove('border-white/10', 'bg-white/5', 'text-gray-400');
            this.classList.add('border-amber-500', 'bg-amber-500/15', 'text-white', 'ring-2', 'ring-amber-500/30');
            selectedMarketplace = this.dataset.mp;
        });
    });

    // Language tabs
    const tabUz = document.getElementById('tab-uz');
    const tabRu = document.getElementById('tab-ru');
    const contentUz = document.getElementById('result-content-uz');
    const contentRu = document.getElementById('result-content-ru');
    const copyAllLabel = document.getElementById('copy-all-label');

    function switchTab(lang) {
        activeLang = lang;
        if (lang === 'uz') {
            tabUz.className = 'lang-tab active flex-1 flex items-center justify-center gap-2 py-3 px-4 rounded-lg text-sm font-bold transition-all bg-amber-500/20 text-amber-300 border border-amber-500/20';
            tabRu.className = 'lang-tab flex-1 flex items-center justify-center gap-2 py-3 px-4 rounded-lg text-sm font-bold transition-all text-gray-500 hover:text-gray-300 hover:bg-white/5';
            contentUz.classList.remove('hidden');
            contentRu.classList.add('hidden');
            copyAllLabel.textContent = "O'zbekcha nusxalash";
        } else {
            tabRu.className = 'lang-tab active flex-1 flex items-center justify-center gap-2 py-3 px-4 rounded-lg text-sm font-bold transition-all bg-blue-500/20 text-blue-300 border border-blue-500/20';
            tabUz.className = 'lang-tab flex-1 flex items-center justify-center gap-2 py-3 px-4 rounded-lg text-sm font-bold transition-all text-gray-500 hover:text-gray-300 hover:bg-white/5';
            contentRu.classList.remove('hidden');
            contentUz.classList.add('hidden');
            copyAllLabel.textContent = 'Ruscha nusxalash';
        }
    }

    tabUz.addEventListener('click', () => switchTab('uz'));
    tabRu.addEventListener('click', () => switchTab('ru'));

    function showState(state) {
        ['loading-state','error-state','result-state','empty-state'].forEach(id => {
            document.getElementById(id).classList.add('hidden');
        });
        const el = document.getElementById(state + '-state');
        if (el) el.classList.remove('hidden');
    }

    // Copy helper
    function copyToClipboard(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const orig = btn.innerHTML;
            btn.innerHTML = '<span class="text-emerald-400">✅ Nusxalandi!</span>';
            setTimeout(() => { btn.innerHTML = orig; }, 1500);
        });
    }

    // Generate
    generateBtn.addEventListener('click', function() {
        TibaAuth.requireAuth(async function() {
        if (!previewUrl) return;

        generateBtn.disabled = true;
        btnText.textContent = 'Tahlil qilinmoqda...';
        progressBarContainer.classList.remove('hidden');
        progress = 0;
        progressBar.style.width = '0%';
        showState('loading');

        progressInterval = setInterval(() => {
            if (progress < 75) progress += Math.random() * 3;
            else if (progress < 95) progress += 0.15;
            progressBar.style.width = progress + '%';
        }, 400);

        try {
            const response = await fetch('/api/kartochka-ai.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-User-Token': TibaAuth.getToken()
                },
                body: JSON.stringify({
                    productImage: previewUrl,
                    marketplace: selectedMarketplace,
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
            resultData = data.result;
            progress = 100;
            progressBar.style.width = '100%';

            renderResult(resultData);
            switchTab('uz');
            showState('result');
        } catch (err) {
            if (err.message !== '__nobalance__') {
                document.getElementById('error-text').textContent = err.message;
                showState('error');
            }
        } finally {
            clearInterval(progressInterval);
            checkReady();
            btnText.innerHTML = '<span class="text-xl mr-2">🧠</span>Tahlil qilish';
            progressBarContainer.classList.add('hidden');
        }
        });
    });

    const sections = [
        { key: 'title', label: '<i class="fa-solid fa-pen-to-square mr-1"></i> Mahsulot nomi', labelRu: '<i class="fa-solid fa-pen-to-square mr-1"></i> Название товара', color: 'amber', type: 'text' },
        { key: 'shortDescription', label: '<i class="fa-solid fa-list-check mr-1"></i> Qisqa tavsif', labelRu: '<i class="fa-solid fa-list-check mr-1"></i> Краткое описание', color: 'orange', type: 'text' },
        { key: 'fullDescription', label: "<i class=\"fa-solid fa-file-lines mr-1\"></i> To'liq tavsif", labelRu: '<i class="fa-solid fa-file-lines mr-1"></i> Полное описание', color: 'yellow', type: 'textarea' },
        { key: 'features', label: '<i class="fa-solid fa-star mr-1"></i> Asosiy xususiyatlar', labelRu: '<i class="fa-solid fa-star mr-1"></i> Основные характеристики', color: 'emerald', type: 'list' },
        { key: 'specifications', label: '<i class="fa-solid fa-ruler-combined mr-1"></i> Texnik xususiyatlar', labelRu: '<i class="fa-solid fa-ruler-combined mr-1"></i> Характеристики', color: 'cyan', type: 'table' },
        { key: 'category', label: '<i class="fa-solid fa-box mr-1"></i> Kategoriya', labelRu: '<i class="fa-solid fa-box mr-1"></i> Категория', color: 'violet', type: 'text' },
        { key: 'brand', label: '<i class="fa-solid fa-tag mr-1"></i> Brend', labelRu: '<i class="fa-solid fa-tag mr-1"></i> Бренд', color: 'pink', type: 'text' },
        { key: 'targetAudience', label: '<i class="fa-solid fa-users mr-1"></i> Maqsadli auditoriya', labelRu: '<i class="fa-solid fa-users mr-1"></i> Целевая аудитория', color: 'blue', type: 'text' },
        { key: 'seoKeywords', label: "<i class=\"fa-solid fa-key mr-1\"></i> SEO kalit so'zlar", labelRu: '<i class="fa-solid fa-key mr-1"></i> SEO ключевые слова', color: 'red', type: 'tags' },
        { key: 'searchTags', label: '<i class="fa-solid fa-hashtag mr-1"></i> Qidiruv teglari', labelRu: '<i class="fa-solid fa-hashtag mr-1"></i> Поисковые теги', color: 'purple', type: 'tags' },
        { key: 'priceRange', label: '<i class="fa-solid fa-coins mr-1"></i> Taxminiy narx', labelRu: '<i class="fa-solid fa-coins mr-1"></i> Примерная цена', color: 'green', type: 'text' },
        { key: 'sellingPoints', label: '<i class="fa-solid fa-bullseye mr-1"></i> Sotish xulosalari', labelRu: '<i class="fa-solid fa-bullseye mr-1"></i> Точки продажи', color: 'rose', type: 'list' },
    ];

    function renderResult(data) {
        renderLangBlock(data.uz || {}, 'uz');
        renderLangBlock(data.ru || {}, 'ru');
    }

    function renderLangBlock(langData, lang) {
        const container = document.getElementById('result-content-' + lang);
        container.innerHTML = '';

        sections.forEach(sec => {
            const val = langData[sec.key];
            if (!val || (Array.isArray(val) && val.length === 0)) return;

            const block = document.createElement('div');
            block.className = 'glass-card p-4 border border-white/5 group hover:border-white/10 transition-all';

            let content = '';
            let copyText = '';
            const label = lang === 'ru' ? sec.labelRu : sec.label;

            if (sec.type === 'text') {
                content = `<div class="text-sm text-gray-200 leading-relaxed">${escHtml(String(val))}</div>`;
                copyText = String(val);
            } else if (sec.type === 'textarea') {
                content = `<div class="text-sm text-gray-200 leading-relaxed whitespace-pre-line">${escHtml(String(val))}</div>`;
                copyText = String(val);
            } else if (sec.type === 'list') {
                const items = Array.isArray(val) ? val : [val];
                content = '<ul class="space-y-1.5">' + items.map(item =>
                    `<li class="flex items-start gap-2 text-sm text-gray-200"><span class="text-${sec.color}-400 mt-0.5 shrink-0">•</span><span>${escHtml(String(item))}</span></li>`
                ).join('') + '</ul>';
                copyText = items.join('\n');
            } else if (sec.type === 'tags') {
                const tags = Array.isArray(val) ? val : String(val).split(',').map(s => s.trim());
                content = '<div class="flex flex-wrap gap-1.5">' + tags.map(tag =>
                    `<span class="px-2.5 py-1 rounded-lg bg-${sec.color}-500/10 text-${sec.color}-300 text-[11px] font-medium border border-${sec.color}-500/20">${escHtml(String(tag))}</span>`
                ).join('') + '</div>';
                copyText = tags.join(', ');
            } else if (sec.type === 'table') {
                if (typeof val === 'object' && !Array.isArray(val)) {
                    content = '<div class="space-y-0.5">' + Object.entries(val).map(([k, v]) =>
                        `<div class="flex items-center justify-between text-sm py-1.5 border-b border-white/5 last:border-0"><span class="text-gray-400 text-xs">${escHtml(k)}</span><span class="text-white font-medium text-xs">${escHtml(String(v))}</span></div>`
                    ).join('') + '</div>';
                    copyText = Object.entries(val).map(([k, v]) => `${k}: ${v}`).join('\n');
                } else if (Array.isArray(val)) {
                    content = '<div class="space-y-0.5">' + val.map(item => {
                        if (typeof item === 'object') {
                            return Object.entries(item).map(([k, v]) =>
                                `<div class="flex items-center justify-between text-sm py-1.5 border-b border-white/5"><span class="text-gray-400 text-xs">${escHtml(k)}</span><span class="text-white font-medium text-xs">${escHtml(String(v))}</span></div>`
                            ).join('');
                        }
                        return `<div class="text-sm text-gray-200">${escHtml(String(item))}</div>`;
                    }).join('') + '</div>';
                    copyText = JSON.stringify(val, null, 2);
                } else {
                    content = `<div class="text-sm text-gray-200">${escHtml(String(val))}</div>`;
                    copyText = String(val);
                }
            }

            block.innerHTML = `
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-${sec.color}-400 uppercase tracking-wider">${label}</span>
                    <button class="copy-single-btn opacity-0 group-hover:opacity-100 transition-opacity text-[10px] font-bold text-gray-500 hover:text-white px-2 py-1 rounded-lg bg-white/5 hover:bg-white/10 cursor-pointer">
                        📋 Nusxalash
                    </button>
                </div>
                ${content}
            `;

            const copyBtn = block.querySelector('.copy-single-btn');
            copyBtn.addEventListener('click', () => copyToClipboard(copyText, copyBtn));

            container.appendChild(block);
        });
    }

    function escHtml(s) {
        const d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    // Build all text for a language
    function buildAllText(langData) {
        let allText = '';
        sections.forEach(sec => {
            const val = langData[sec.key];
            if (!val) return;
            if (Array.isArray(val)) {
                allText += `${sec.label}:\n${val.join('\n')}\n\n`;
            } else if (typeof val === 'object') {
                allText += `${sec.label}:\n${Object.entries(val).map(([k,v]) => `${k}: ${v}`).join('\n')}\n\n`;
            } else {
                allText += `${sec.label}:\n${val}\n\n`;
            }
        });
        return allText.trim();
    }

    // Copy current lang
    document.getElementById('copy-all-btn').addEventListener('click', function() {
        if (!resultData) return;
        const langData = activeLang === 'uz' ? resultData.uz : resultData.ru;
        if (!langData) return;
        copyToClipboard(buildAllText(langData), this);
    });

    // Copy both
    document.getElementById('copy-both-btn').addEventListener('click', function() {
        if (!resultData) return;
        let text = '';
        if (resultData.uz) text += "=== 🇺🇿 O'ZBEKCHA ===\n\n" + buildAllText(resultData.uz) + '\n\n';
        if (resultData.ru) text += "=== 🇷🇺 RUSCHA ===\n\n" + buildAllText(resultData.ru);
        copyToClipboard(text.trim(), this);
    });

    // Retry
    document.getElementById('retry-btn').addEventListener('click', () => showState('empty'));
})();
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
