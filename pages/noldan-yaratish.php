<?php $pageTitle = 'Noldan Yaratish – Tiba AI'; ?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="py-12 sm:py-20 relative overflow-hidden">
    <!-- Background elements -->
    <div class="absolute top-10 left-1/4 w-96 h-96 bg-emerald-600/10 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-10 right-1/4 w-80 h-80 bg-teal-600/10 rounded-full blur-3xl animate-float" style="animation-delay: -3s"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <!-- Back Button -->
        <div class="mb-8 animate-fade-in">
            <a href="/create" class="inline-flex items-center gap-2 text-gray-500 hover:text-white transition-colors text-sm font-bold uppercase tracking-widest group">
                <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                Orqaga
            </a>
        </div>
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 mb-4 animate-fade-in">
                <i class="fa-solid fa-rocket text-emerald-400 text-sm"></i>
                <span class="text-xs font-bold text-emerald-300 uppercase tracking-widest">So'z bilan rasm yaratish</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3">
                Noldan <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-400 to-teal-400">Yaratish</span>
            </h1>
            <p class="text-gray-400 max-w-xl mx-auto">
                Matnli buyruq yozing va AI sizga professional rasm yaratsin. Hech qanday rasm kerak emas — faqat tasavvuringiz!
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Panel — Form -->
            <div class="space-y-6">
                <div class="glass-card p-6 sm:p-8 space-y-6">
                    <!-- Prompt -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-pen-nib text-emerald-400"></i> Rasm tavsifi (prompt)
                        </label>
                        <textarea id="prompt-input" placeholder="Masalan: Oq rangli zarif smart soat, qora zanglamasdan yasalgan, metall qo'l bog'ichi, zamonaviy dizayn, professional studio yoritish..." rows="5" class="input-field resize-none text-sm" required></textarea>
                        <div class="flex justify-between items-center mt-1.5">
                            <span class="text-[11px] text-gray-500">Batafsil yozsangiz, natija shunchalik yaxshi bo'ladi</span>
                            <span id="char-count" class="text-[11px] text-gray-500">0 ta belgi</span>
                        </div>
                    </div>

                    <!-- Example Prompts -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-lightbulb text-amber-400"></i> Tayyor namunalar
                        </label>
                        <div class="space-y-2" id="example-prompts">
                            <button type="button" class="example-prompt-btn w-full text-left p-3 rounded-xl border border-white/5 bg-white/[0.02] hover:bg-white/[0.06] hover:border-white/15 transition-all duration-200 group" data-prompt="Professional studio rasmda zamonaviy simsiz quloqchin, oq rang, premium packaging qutisi bilan, yumshoq yoritish, minimalist orqa fon, 8K sifat">
                                <div class="flex items-start gap-3">
                                    <i class="fa-solid fa-headphones text-lg mt-0.5 shrink-0 text-indigo-400"></i>
                                    <div>
                                        <div class="text-sm text-gray-300 font-medium group-hover:text-white transition-colors">Simsiz quloqchin studio fotosi</div>
                                        <div class="text-xs text-gray-500 mt-0.5">Premium studio rasm</div>
                                    </div>
                                </div>
                            </button>
                            <button type="button" class="example-prompt-btn w-full text-left p-3 rounded-xl border border-white/5 bg-white/[0.02] hover:bg-white/[0.06] hover:border-white/15 transition-all duration-200 group" data-prompt="Zamonaviy uy interyeri, ochiq rang devorlari, katta derazadan tabiiy yorug'lik, minimalist mebel, skandinaviya uslubida, professional arxitektura fotosuratlari sifatida">
                                <div class="flex items-start gap-3">
                                    <i class="fa-solid fa-house-chimney text-lg mt-0.5 shrink-0 text-emerald-400"></i>
                                    <div>
                                        <div class="text-sm text-gray-300 font-medium group-hover:text-white transition-colors">Zamonaviy uy interyeri</div>
                                        <div class="text-xs text-gray-500 mt-0.5">Interyerlar uchun ideal</div>
                                    </div>
                                </div>
                            </button>
                            <button type="button" class="example-prompt-btn w-full text-left p-3 rounded-xl border border-white/5 bg-white/[0.02] hover:bg-white/[0.06] hover:border-white/15 transition-all duration-200 group" data-prompt="Kiyim do'koni uchun reklama banneri, qizil va oq ranglarda, yozgi aksiya -50% chegirma, zamonaviy tipografiya, trendy moda uslubida, dinamik kompozitsiya, professional grafik dizayn">
                                <div class="flex items-start gap-3">
                                    <i class="fa-solid fa-shirt text-lg mt-0.5 shrink-0 text-rose-400"></i>
                                    <div>
                                        <div class="text-sm text-gray-300 font-medium group-hover:text-white transition-colors">Kiyim do'koni aksiya banneri</div>
                                        <div class="text-xs text-gray-500 mt-0.5">Marketing materiali</div>
                                    </div>
                                </div>
                            </button>
                            <button type="button" class="example-prompt-btn w-full text-left p-3 rounded-xl border border-white/5 bg-white/[0.02] hover:bg-white/[0.06] hover:border-white/15 transition-all duration-200 group" data-prompt="Tabiiy kosmetika brendining logotipi, yashil va oltin ranglar, botanik elementlar, zarif shrift, minimalist va zamonaviy dizayn, oq fon, vektor sifatida">
                                <div class="flex items-start gap-3">
                                    <i class="fa-solid fa-leaf text-lg mt-0.5 shrink-0 text-teal-400"></i>
                                    <div>
                                        <div class="text-sm text-gray-300 font-medium group-hover:text-white transition-colors">Kosmetika brendining logotipi</div>
                                        <div class="text-xs text-gray-500 mt-0.5">Brending uchun</div>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Aspect Ratio Selector -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">
                            📐 Format (o'lcham)
                        </label>
                        <div class="grid grid-cols-5 gap-2" id="ratio-selector">
                            <button type="button" data-ratio="1:1" class="ratio-btn active p-2.5 rounded-xl border text-center transition-all duration-200 border-emerald-500 bg-emerald-500/15 text-white shadow-lg shadow-emerald-500/10">
                                <div class="text-[10px] font-bold uppercase tracking-tighter">Kvadrat</div>
                                <div class="text-[8px] opacity-60 font-bold">1:1</div>
                            </button>
                            <button type="button" data-ratio="3:4" class="ratio-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold uppercase tracking-tighter">Portrait</div>
                                <div class="text-[8px] opacity-60 font-bold">3:4</div>
                            </button>
                            <button type="button" data-ratio="4:3" class="ratio-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold uppercase tracking-tighter">Landscape</div>
                                <div class="text-[8px] opacity-60 font-bold">4:3</div>
                            </button>
                            <button type="button" data-ratio="16:9" class="ratio-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold uppercase tracking-tighter">Keng</div>
                                <div class="text-[8px] opacity-60 font-bold">16:9</div>
                            </button>
                            <button type="button" data-ratio="9:16" class="ratio-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold uppercase tracking-tighter">Story</div>
                                <div class="text-[8px] opacity-60 font-bold">9:16</div>
                            </button>
                        </div>
                    </div>

                    <!-- Style Presets -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">
                            🎨 Vizual uslub
                        </label>
                        <div class="grid grid-cols-4 gap-2" id="style-selector">
                            <button type="button" data-style="photorealistic" class="nstyle-btn active relative overflow-hidden rounded-lg border p-1.5 h-14 flex flex-col items-center justify-center gap-0.5 transition-all border-emerald-500 ring-2 ring-emerald-500/30">
                                <div class="absolute inset-0 bg-gradient-to-br from-gray-200 to-white opacity-80"></div>
                                <i class="fa-solid fa-camera relative z-10 text-black/70 text-sm"></i>
                                <div class="relative z-10 font-bold text-[8px] uppercase tracking-tighter text-center text-black">Foto real</div>
                            </button>
                            <button type="button" data-style="illustration" class="nstyle-btn relative overflow-hidden rounded-lg border p-1.5 h-14 flex flex-col items-center justify-center gap-0.5 transition-all border-white/10 hover:border-white/20">
                                <div class="absolute inset-0 bg-gradient-to-br from-purple-500 to-pink-600 opacity-80"></div>
                                <i class="fa-solid fa-palette relative z-10 text-white text-sm"></i>
                                <div class="relative z-10 font-bold text-[8px] uppercase tracking-tighter text-center text-white">Illustratsiya</div>
                            </button>
                            <button type="button" data-style="3d-render" class="nstyle-btn relative overflow-hidden rounded-lg border p-1.5 h-14 flex flex-col items-center justify-center gap-0.5 transition-all border-white/10 hover:border-white/20">
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-cyan-600 opacity-80"></div>
                                <i class="fa-solid fa-cube relative z-10 text-white text-sm"></i>
                                <div class="relative z-10 font-bold text-[8px] uppercase tracking-tighter text-center text-white">3D Render</div>
                            </button>
                            <button type="button" data-style="flat-design" class="nstyle-btn relative overflow-hidden rounded-lg border p-1.5 h-14 flex flex-col items-center justify-center gap-0.5 transition-all border-white/10 hover:border-white/20">
                                <div class="absolute inset-0 bg-gradient-to-br from-amber-400 to-orange-500 opacity-80"></div>
                                <i class="fa-solid fa-pen-ruler relative z-10 text-black/70 text-sm"></i>
                                <div class="relative z-10 font-bold text-[8px] uppercase tracking-tighter text-center text-black">Flat dizayn</div>
                            </button>
                            <button type="button" data-style="anime" class="nstyle-btn relative overflow-hidden rounded-lg border p-1.5 h-14 flex flex-col items-center justify-center gap-0.5 transition-all border-white/10 hover:border-white/20">
                                <div class="absolute inset-0 bg-gradient-to-br from-rose-500 to-pink-600 opacity-80"></div>
                                <i class="fa-solid fa-wand-sparkles relative z-10 text-white text-sm"></i>
                                <div class="relative z-10 font-bold text-[8px] uppercase tracking-tighter text-center text-white">Anime</div>
                            </button>
                            <button type="button" data-style="watercolor" class="nstyle-btn relative overflow-hidden rounded-lg border p-1.5 h-14 flex flex-col items-center justify-center gap-0.5 transition-all border-white/10 hover:border-white/20">
                                <div class="absolute inset-0 bg-gradient-to-br from-sky-300 to-blue-400 opacity-80"></div>
                                <i class="fa-solid fa-paintbrush relative z-10 text-black/70 text-sm"></i>
                                <div class="relative z-10 font-bold text-[8px] uppercase tracking-tighter text-center text-black">Akvarel</div>
                            </button>
                            <button type="button" data-style="cinematic" class="nstyle-btn relative overflow-hidden rounded-lg border p-1.5 h-14 flex flex-col items-center justify-center gap-0.5 transition-all border-white/10 hover:border-white/20">
                                <div class="absolute inset-0 bg-gradient-to-br from-zinc-800 to-zinc-900 opacity-80"></div>
                                <i class="fa-solid fa-clapperboard relative z-10 text-white text-sm"></i>
                                <div class="relative z-10 font-bold text-[8px] uppercase tracking-tighter text-center text-white">Kinematik</div>
                            </button>
                            <button type="button" data-style="neon" class="nstyle-btn relative overflow-hidden rounded-lg border p-1.5 h-14 flex flex-col items-center justify-center gap-0.5 transition-all border-white/10 hover:border-white/20">
                                <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 to-purple-900 opacity-80"></div>
                                <i class="fa-solid fa-bolt-lightning relative z-10 text-white text-sm"></i>
                                <div class="relative z-10 font-bold text-[8px] uppercase tracking-tighter text-center text-white">Neon</div>
                            </button>
                        </div>
                    </div>

                    <!-- Language -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">
                            🌐 Rasm tili
                        </label>
                        <div class="grid grid-cols-5 gap-2" id="lang-selector">
                            <button type="button" data-lang="uz" class="nlang-btn active p-2.5 rounded-xl border text-center transition-all duration-200 border-emerald-500 bg-emerald-500/15 text-white">
                                <div class="text-sm font-semibold">🇺🇿 O'zbek</div>
                            </button>
                            <button type="button" data-lang="ru" class="nlang-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20 hover:bg-white/10">
                                <div class="text-sm font-semibold">🇷🇺 Rus</div>
                            </button>
                            <button type="button" data-lang="en" class="nlang-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20 hover:bg-white/10">
                                <div class="text-sm font-semibold">🇬🇧 Eng</div>
                            </button>
                            <button type="button" data-lang="tr" class="nlang-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20 hover:bg-white/10">
                                <div class="text-sm font-semibold">🇹🇷 Turk</div>
                            </button>
                            <button type="button" data-lang="kz" class="nlang-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20 hover:bg-white/10">
                                <div class="text-sm font-semibold">🇰🇿 Qozoq</div>
                            </button>
                        </div>
                    </div>

                    <!-- Generate Button -->
                    <button id="generate-btn" disabled class="btn-primary w-full h-16 text-lg font-extrabold disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 flex items-center justify-center gap-3">
                        <span id="btn-text" class="flex items-center gap-3">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            Rasm yaratish
                        </span>
                    </button>

                    <!-- Progress bar -->
                    <div id="progress-bar-container" class="hidden">
                        <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden">
                            <div id="progress-bar" class="h-full bg-gradient-to-r from-emerald-600 via-teal-500 to-cyan-500 rounded-full transition-all duration-300 ease-out shadow-[0_0_15px_rgba(16,185,129,0.5)]" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel — Result -->
            <div>
                <div class="glass-card p-6 sm:p-8 min-h-[500px] flex flex-col items-center justify-center sticky top-24">
                    <!-- Loading state -->
                    <div id="loading-state" class="text-center animate-fade-in hidden">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-emerald-600/20 to-teal-600/20 border border-emerald-500/20 flex items-center justify-center mx-auto mb-6 animate-pulse">
                            <i class="fa-solid fa-rocket text-3xl text-emerald-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">AI ishlayapti...</h3>
                        <p class="text-sm text-gray-400">Rasm yaratilmoqda. Bu 15-60 soniya davom etishi mumkin.</p>
                        <div class="mt-6 flex justify-center gap-1">
                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" style="animation-delay: 0.15s"></div>
                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" style="animation-delay: 0.3s"></div>
                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" style="animation-delay: 0.45s"></div>
                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" style="animation-delay: 0.6s"></div>
                        </div>
                    </div>

                    <!-- Error state -->
                    <div id="error-state" class="text-center animate-fade-in hidden">
                        <div class="w-16 h-16 rounded-2xl bg-red-500/10 flex items-center justify-center mx-auto mb-4 border border-red-500/20">
                            <i class="fa-solid fa-circle-xmark text-3xl text-red-500"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-red-400 mb-2">Xatolik</h3>
                        <p id="error-text" class="text-sm text-gray-400 mb-4"></p>
                        <button id="retry-btn" class="btn-secondary text-sm px-6 py-2">Qayta urinish</button>
                    </div>

                    <!-- Result state -->
                    <div id="result-state" class="w-full animate-fade-in space-y-4 hidden">
                        <div class="relative group">
                            <img id="result-image" src="" alt="Generated image" class="w-full rounded-xl shadow-2xl shadow-emerald-500/10" />
                            <div class="absolute inset-0 bg-black/50 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-4">
                                <button id="download-hover-btn" class="btn-primary px-6 py-3">
                                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                    Yuklab olish
                                </button>
                            </div>
                        </div>

                        <!-- Used prompt info -->
                        <div class="glass-card p-3 border border-white/5">
                            <div class="flex items-center gap-2 mb-1.5">
                                <i class="fa-solid fa-comment-dots text-emerald-400 text-xs"></i>
                                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Ishlatilgan prompt</span>
                            </div>
                            <p id="used-prompt-text" class="text-xs text-gray-500 line-clamp-3"></p>
                        </div>

                        <div class="flex gap-3">
                            <button id="download-btn" class="btn-primary flex-1">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                Rasmni yuklab olish
                            </button>
                            <button id="regenerate-btn" class="btn-secondary px-6" title="Qayta yaratish">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Empty state -->
                    <div id="empty-state" class="text-center">
                        <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-emerald-600/20 to-teal-600/20 border border-emerald-500/20 flex items-center justify-center mx-auto mb-6 shadow-xl shadow-emerald-500/10">
                            <i class="fa-solid fa-wand-magic-sparkles text-4xl text-emerald-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Natija shu yerda ko'rinadi</h3>
                        <p class="text-sm text-gray-500 max-w-xs mx-auto">
                            Rasm tavsifini yozing va "Rasm yaratish" tugmasini bosing. AI sizning xayolingizdagi rasmni yaratadi.
                        </p>
                        <div class="mt-6 grid grid-cols-3 gap-3 max-w-xs mx-auto">
                            <div class="h-20 rounded-xl bg-gradient-to-br from-emerald-600/10 to-teal-600/10 border border-emerald-500/10 flex items-center justify-center text-2xl animate-pulse text-emerald-400/50"><i class="fa-solid fa-camera"></i></div>
                            <div class="h-20 rounded-xl bg-gradient-to-br from-purple-600/10 to-pink-600/10 border border-purple-500/10 flex items-center justify-center text-2xl animate-pulse text-purple-400/50" style="animation-delay: 0.3s"><i class="fa-solid fa-palette"></i></div>
                            <div class="h-20 rounded-xl bg-gradient-to-br from-blue-600/10 to-cyan-600/10 border border-blue-500/10 flex items-center justify-center text-2xl animate-pulse text-blue-400/50" style="animation-delay: 0.6s"><i class="fa-solid fa-cube"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    let selectedStyle = 'photorealistic';
    let selectedRatio = '1:1';
    let selectedLang = 'uz';
    let resultUrl = null;
    let progress = 0;
    let progressInterval = null;

    const promptInput = document.getElementById('prompt-input');
    const charCount = document.getElementById('char-count');
    const generateBtn = document.getElementById('generate-btn');
    const btnText = document.getElementById('btn-text');
    const progressBarContainer = document.getElementById('progress-bar-container');
    const progressBar = document.getElementById('progress-bar');

    const loadingState = document.getElementById('loading-state');
    const errorState = document.getElementById('error-state');
    const errorText = document.getElementById('error-text');
    const retryBtn = document.getElementById('retry-btn');
    const resultState = document.getElementById('result-state');
    const emptyState = document.getElementById('empty-state');
    const resultImage = document.getElementById('result-image');
    const usedPromptText = document.getElementById('used-prompt-text');

    // Character count & enable button
    promptInput.addEventListener('input', function() {
        charCount.textContent = this.value.length + ' ta belgi';
        generateBtn.disabled = this.value.trim().length < 5;
    });

    // Example prompt buttons
    document.querySelectorAll('.example-prompt-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            promptInput.value = this.dataset.prompt;
            promptInput.dispatchEvent(new Event('input'));
            promptInput.focus();
            // Flash animation
            promptInput.style.borderColor = 'rgba(16, 185, 129, 0.5)';
            setTimeout(() => { promptInput.style.borderColor = ''; }, 600);
        });
    });

    // Ratio selector
    document.querySelectorAll('.ratio-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.ratio-btn').forEach(b => {
                b.className = 'ratio-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20';
            });
            this.className = 'ratio-btn active p-2.5 rounded-xl border text-center transition-all duration-200 border-emerald-500 bg-emerald-500/15 text-white shadow-lg shadow-emerald-500/10';
            selectedRatio = this.dataset.ratio;
        });
    });

    // Style selector
    document.querySelectorAll('.nstyle-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.nstyle-btn').forEach(b => {
                b.classList.remove('border-emerald-500', 'ring-2', 'ring-emerald-500/30');
                b.classList.add('border-white/10');
            });
            this.classList.remove('border-white/10');
            this.classList.add('border-emerald-500', 'ring-2', 'ring-emerald-500/30');
            selectedStyle = this.dataset.style;
        });
    });

    // Language selector
    document.querySelectorAll('.nlang-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.nlang-btn').forEach(b => {
                b.className = 'nlang-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20 hover:bg-white/10';
            });
            this.className = 'nlang-btn active p-2.5 rounded-xl border text-center transition-all duration-200 border-emerald-500 bg-emerald-500/15 text-white';
            selectedLang = this.dataset.lang;
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

    // Style name map
    const styleNames = {
        'photorealistic': 'Photorealistic photography, ultra-realistic, studio quality, natural lighting, DSLR camera, sharp focus, 8K resolution',
        'illustration': 'Digital illustration, vibrant colors, detailed character design, hand-drawn feel, trendy modern art style',
        '3d-render': '3D rendered scene, Octane render, volumetric lighting, ray tracing, Blender style, realistic materials and textures',
        'flat-design': 'Flat design, geometric shapes, bold colors, minimalist composition, clean vector art, modern graphic design',
        'anime': 'Anime art style, Japanese animation, detailed eyes, vibrant colors, dynamic composition, Studio Ghibli aesthetic',
        'watercolor': 'Watercolor painting, soft washes, paper texture, organic shapes, artistic brushstrokes, fine art gallery quality',
        'cinematic': 'Cinematic scene, dramatic volumetric lighting, depth of field, anamorphic lens flare, movie poster quality, epic composition',
        'neon': 'Neon-lit cyberpunk aesthetic, dark environment, glowing neon signs, futuristic city, rain-soaked streets, blade runner style'
    };

    const langNames = {
        'uz': 'Uzbek',
        'ru': 'Russian',
        'en': 'English',
        'tr': 'Turkish',
        'kz': 'Kazakh'
    };

    // Generate
    async function handleGenerate() {
        const userPrompt = promptInput.value.trim();
        if (userPrompt.length < 5) return;

        generateBtn.disabled = true;
        btnText.textContent = 'Yaratilmoqda... 0%';
        progressBarContainer.classList.remove('hidden');
        progress = 0;
        progressBar.style.width = '0%';
        showState('loading');

        progressInterval = setInterval(() => {
            if (progress < 80) progress += Math.random() * 3;
            else if (progress < 95) progress += 0.15;
            progressBar.style.width = progress + '%';
            btnText.textContent = 'Yaratilmoqda... ' + Math.floor(progress) + '%';
        }, 400);

        // Build full prompt
        const styleDesc = styleNames[selectedStyle] || '';
        const langName = langNames[selectedLang] || 'Uzbek';
        const fullPrompt = `${userPrompt}\n\nStyle: ${styleDesc}\nAspect Ratio: ${selectedRatio}\nAll text in the image must be in ${langName} language.\nUltra high quality, 8K resolution, professional commercial output.`;

        try {
            const response = await fetch('/api/noldan-yaratish.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-User-Token': TibaAuth.getToken()
                },
                body: JSON.stringify({
                    prompt: fullPrompt,
                    style: selectedStyle,
                    aspectRatio: selectedRatio,
                    language: selectedLang,
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
            resultImage.src = resultUrl;
            usedPromptText.textContent = userPrompt;
            showState('result');
        } catch (err) {
            if (err.message !== '__nobalance__') {
                errorText.textContent = err.message;
                showState('error');
            }
        } finally {
            clearInterval(progressInterval);
            generateBtn.disabled = promptInput.value.trim().length < 5;
            btnText.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles"></i> Rasm yaratish';
            progressBarContainer.classList.add('hidden');
        }
    }

    generateBtn.addEventListener('click', () => TibaAuth.requireAuth(handleGenerate));

    // Download
    function handleDownload() {
        if (!resultUrl) return;
        const a = document.createElement('a');
        a.href = resultUrl;
        a.download = 'tiba-ai-' + Date.now() + '.png';
        a.target = '_blank';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }
    document.getElementById('download-btn').addEventListener('click', handleDownload);
    document.getElementById('download-hover-btn').addEventListener('click', handleDownload);

    // Regenerate
    document.getElementById('regenerate-btn').addEventListener('click', function() {
        if (promptInput.value.trim().length >= 5) handleGenerate();
    });

    // Retry
    retryBtn.addEventListener('click', () => showState('empty'));
})();
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
