<?php $pageTitle = 'Video AI – Tiba AI'; ?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="py-12 sm:py-20 relative overflow-hidden">
    <!-- Background elements -->
    <div class="absolute top-10 left-1/4 w-96 h-96 bg-violet-600/10 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-10 right-1/4 w-80 h-80 bg-fuchsia-600/10 rounded-full blur-3xl animate-float" style="animation-delay: -3s"></div>

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
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-violet-500/10 border border-violet-500/20 mb-4 animate-fade-in">
                <i class="fa-solid fa-video text-violet-400 text-sm"></i>
                <span class="text-xs font-bold text-violet-300 uppercase tracking-widest">Video Generatsiya</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3">
                AI bilan <span class="bg-clip-text text-transparent bg-gradient-to-r from-violet-400 to-fuchsia-400">Video Yaratish</span>
            </h1>
            <p class="text-gray-400 max-w-xl mx-auto">
                Matnli buyruq yozing yoki rasm yuklang — AI sizga professional video yaratsin. Tiba AI kuchi bilan.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Panel — Form -->
            <div class="space-y-6">
                <div class="glass-card p-6 sm:p-8 space-y-6">
                    <!-- Mode: Text-to-Video / Image-to-Video -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-toggle-on text-violet-400"></i> Video turi
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" id="mode-image" class="vid-mode-btn p-3 rounded-xl border text-center transition-all duration-200 border-violet-500 bg-violet-500/15 text-white shadow-lg shadow-violet-500/10 flex items-center justify-center gap-2 text-sm font-bold">
                                <i class="fa-solid fa-image"></i> Rasmdan Video
                            </button>
                            <button type="button" id="mode-text" class="vid-mode-btn p-3 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20 flex items-center justify-center gap-2 text-sm font-bold">
                                <i class="fa-solid fa-keyboard"></i> Matndan Video
                            </button>
                        </div>
                    </div>

                    <!-- Image Upload (hidden by default) -->
                    <div id="image-upload-area">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-cloud-arrow-up text-violet-400"></i> Rasm yuklash
                        </label>
                        <label class="flex flex-col items-center justify-center h-40 border-2 border-dashed border-white/10 rounded-2xl bg-white/[0.02] hover:bg-white/[0.06] hover:border-violet-500/40 transition-all cursor-pointer group relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-violet-500/0 to-fuchsia-500/0 group-hover:from-violet-500/5 group-hover:to-fuchsia-500/5 transition-all duration-500"></div>
                            <div class="relative z-10 flex flex-col items-center" id="upload-content">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-500 group-hover:text-violet-400 transition-all mb-2"></i>
                                <span class="text-xs font-bold text-gray-500 uppercase group-hover:text-violet-400">Rasm tanlang</span>
                                <span class="text-[10px] text-gray-600 mt-1">JPG, PNG, WebP — maks. 10MB</span>
                            </div>
                            <img id="image-preview" src="" class="hidden absolute inset-0 w-full h-full object-cover rounded-2xl" />
                            <input type="file" class="hidden" accept="image/*" id="vid-image-input" />
                        </label>
                        <button type="button" id="clear-image-btn" class="hidden mt-2 text-xs text-red-400 hover:text-red-300 font-bold">
                            <i class="fa-solid fa-xmark"></i> Rasmni olib tashlash
                        </button>
                    </div>


                    <!-- Tayyor namunalar — Ixcham tugmalar -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-lightbulb text-amber-400"></i> Tayyor namunalar
                        </label>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" class="vid-example-btn px-3 py-2 rounded-xl border border-white/10 bg-white/[0.03] hover:bg-violet-500/15 hover:border-violet-500/40 transition-all duration-200 flex items-center gap-2 text-xs group" data-prompt="Product slowly rotating 360 degrees on a white turntable, soft studio lighting, clean white background, professional commercial footage, smooth cinematic camera movement, high-end advertising quality">
                                <i class="fa-solid fa-rotate text-violet-400 group-hover:scale-110 transition-transform"></i>
                                <span class="text-gray-400 group-hover:text-white transition-colors font-medium">360° Aylantirish</span>
                            </button>
                            <button type="button" class="vid-example-btn px-3 py-2 rounded-xl border border-white/10 bg-white/[0.03] hover:bg-amber-500/15 hover:border-amber-500/40 transition-all duration-200 flex items-center gap-2 text-xs group" data-prompt="Cinematic product reveal with dramatic lighting, volumetric fog effects, slow motion, premium luxury feel, dark background with golden highlights, epic music atmosphere">
                                <i class="fa-solid fa-film text-amber-400 group-hover:scale-110 transition-transform"></i>
                                <span class="text-gray-400 group-hover:text-white transition-colors font-medium">Kinematik</span>
                            </button>
                            <button type="button" class="vid-example-btn px-3 py-2 rounded-xl border border-white/10 bg-white/[0.03] hover:bg-emerald-500/15 hover:border-emerald-500/40 transition-all duration-200 flex items-center gap-2 text-xs group" data-prompt="Smooth zoom into product details, macro photography style, showing texture and quality, soft bokeh background, warm lighting, extreme close-up revealing material quality">
                                <i class="fa-solid fa-magnifying-glass text-emerald-400 group-hover:scale-110 transition-transform"></i>
                                <span class="text-gray-400 group-hover:text-white transition-colors font-medium">Makro detal</span>
                            </button>
                            <button type="button" class="vid-example-btn px-3 py-2 rounded-xl border border-white/10 bg-white/[0.03] hover:bg-orange-500/15 hover:border-orange-500/40 transition-all duration-200 flex items-center gap-2 text-xs group" data-prompt="Unboxing video, hands carefully opening a premium product box, revealing the product inside with excitement, soft overhead lighting, ASMR style, satisfying unwrapping">
                                <i class="fa-solid fa-box-open text-orange-400 group-hover:scale-110 transition-transform"></i>
                                <span class="text-gray-400 group-hover:text-white transition-colors font-medium">Unboxing</span>
                            </button>
                            <button type="button" class="vid-example-btn px-3 py-2 rounded-xl border border-white/10 bg-white/[0.03] hover:bg-cyan-500/15 hover:border-cyan-500/40 transition-all duration-200 flex items-center gap-2 text-xs group" data-prompt="Product in lifestyle setting, person using the product in a modern stylish home, natural sunlight through large windows, cozy atmosphere, warm tones, cinematic depth of field">
                                <i class="fa-solid fa-house text-cyan-400 group-hover:scale-110 transition-transform"></i>
                                <span class="text-gray-400 group-hover:text-white transition-colors font-medium">Lifestyle</span>
                            </button>
                            <button type="button" class="vid-example-btn px-3 py-2 rounded-xl border border-white/10 bg-white/[0.03] hover:bg-red-500/15 hover:border-red-500/40 transition-all duration-200 flex items-center gap-2 text-xs group" data-prompt="Delicious food product showcase, steaming hot, appetizing close-up, ingredients falling in slow motion, professional food photography lighting, vibrant colors, making the viewer hungry">
                                <i class="fa-solid fa-utensils text-red-400 group-hover:scale-110 transition-transform"></i>
                                <span class="text-gray-400 group-hover:text-white transition-colors font-medium">Oziq-ovqat</span>
                            </button>
                            <button type="button" class="vid-example-btn px-3 py-2 rounded-xl border border-white/10 bg-white/[0.03] hover:bg-pink-500/15 hover:border-pink-500/40 transition-all duration-200 flex items-center gap-2 text-xs group" data-prompt="Fashion model walking on a runway wearing the product, confident walk, high-end fashion show atmosphere, dramatic spotlights, slow motion capture, professional fashion video">
                                <i class="fa-solid fa-shirt text-pink-400 group-hover:scale-110 transition-transform"></i>
                                <span class="text-gray-400 group-hover:text-white transition-colors font-medium">Fashion</span>
                            </button>
                            <button type="button" class="vid-example-btn px-3 py-2 rounded-xl border border-white/10 bg-white/[0.03] hover:bg-blue-500/15 hover:border-blue-500/40 transition-all duration-200 flex items-center gap-2 text-xs group" data-prompt="Futuristic tech product floating in zero gravity, holographic interface elements around it, neon blue and purple glowing lights, sci-fi atmosphere, particle effects, high-tech commercial">
                                <i class="fa-solid fa-microchip text-blue-400 group-hover:scale-110 transition-transform"></i>
                                <span class="text-gray-400 group-hover:text-white transition-colors font-medium">Texnologik</span>
                            </button>
                            <button type="button" class="vid-example-btn px-3 py-2 rounded-xl border border-white/10 bg-white/[0.03] hover:bg-green-500/15 hover:border-green-500/40 transition-all duration-200 flex items-center gap-2 text-xs group" data-prompt="Product placed in beautiful nature setting, morning dew drops, sunlight rays through forest trees, birds flying, fresh green leaves moving in gentle wind, peaceful and organic atmosphere">
                                <i class="fa-solid fa-leaf text-green-400 group-hover:scale-110 transition-transform"></i>
                                <span class="text-gray-400 group-hover:text-white transition-colors font-medium">Tabiat</span>
                            </button>
                            <button type="button" class="vid-example-btn px-3 py-2 rounded-xl border border-white/10 bg-white/[0.03] hover:bg-fuchsia-500/15 hover:border-fuchsia-500/40 transition-all duration-200 flex items-center gap-2 text-xs group" data-prompt="Dynamic social media ad, fast-paced cuts, trendy transitions, bold text overlays appearing with motion, energetic music vibe, vertical format optimized for Instagram Reels and TikTok, eye-catching colors">
                                <i class="fa-brands fa-instagram text-fuchsia-400 group-hover:scale-110 transition-transform"></i>
                                <span class="text-gray-400 group-hover:text-white transition-colors font-medium">Reels / TikTok</span>
                            </button>
                            <button type="button" class="vid-example-btn px-3 py-2 rounded-xl border border-white/10 bg-white/[0.03] hover:bg-rose-500/15 hover:border-rose-500/40 transition-all duration-200 flex items-center gap-2 text-xs group" data-prompt="Luxury perfume bottle with golden liquid inside, elegant slow motion pour, mist and sparkle particles floating around, dark moody background, glass reflections, premium fragrance commercial, cinematic close-up of the bottle design">
                                <i class="fa-solid fa-spray-can-sparkles text-rose-400 group-hover:scale-110 transition-transform"></i>
                                <span class="text-gray-400 group-hover:text-white transition-colors font-medium">Parfumeriya</span>
                            </button>
                        </div>
                    </div>

                    <!-- Prompt (tavsif) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-pen-nib text-violet-400"></i> Video tavsifi (prompt)
                        </label>
                        <textarea id="vid-prompt" placeholder="Tugmalardan birini tanlang yoki o'zingiz yozing..." rows="4" class="input-field resize-none text-sm" required></textarea>
                        <div class="flex justify-between items-center mt-1.5">
                            <span class="text-[11px] text-gray-500">Batafsil yozsangiz, natija shunchalik yaxshi bo'ladi</span>
                            <span id="vid-char-count" class="text-[11px] text-gray-500">0 ta belgi</span>
                        </div>
                    </div>

                    <!-- Duration -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-clock text-violet-400"></i> Davomiyligi
                        </label>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" data-dur="5" class="vid-dur-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-violet-500 bg-violet-500/15 text-white shadow-lg shadow-violet-500/10">
                                <div class="text-sm font-bold">5s</div>
                                <div class="text-[9px] opacity-60">Qisqa</div>
                            </button>
                            <button type="button" data-dur="10" class="vid-dur-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-sm font-bold">10s</div>
                                <div class="text-[9px] opacity-60">O'rta</div>
                            </button>
                            <button type="button" data-dur="15" class="vid-dur-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-sm font-bold">15s</div>
                                <div class="text-[9px] opacity-60">Uzun</div>
                            </button>
                        </div>
                    </div>

                    <!-- Aspect Ratio -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex justify-between items-center">
                            <span>📐 Format</span>
                            <span class="text-[10px] text-violet-400">Tavsiya: Auto</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            <button type="button" data-ratio="auto" class="vid-ratio-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-violet-500 bg-violet-500/15 text-white shadow-lg shadow-violet-500/10">
                                <div class="text-[10px] font-bold">Auto</div>
                                <div class="text-[8px] opacity-60">Rasmga mos</div>
                            </button>
                            <button type="button" data-ratio="9:16" class="vid-ratio-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold">Story / Reels</div>
                                <div class="text-[8px] opacity-60">9:16</div>
                            </button>
                            <button type="button" data-ratio="16:9" class="vid-ratio-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold">Keng</div>
                                <div class="text-[8px] opacity-60">16:9</div>
                            </button>
                            <button type="button" data-ratio="3:4" class="vid-ratio-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold">Portret</div>
                                <div class="text-[8px] opacity-60">3:4</div>
                            </button>
                            <button type="button" data-ratio="1:1" class="vid-ratio-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-[10px] font-bold">Kvadrat</div>
                                <div class="text-[8px] opacity-60">1:1</div>
                            </button>
                        </div>
                    </div>

                    <!-- Resolution -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">
                            🖥️ Sifat
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" data-res="720p" class="vid-res-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-violet-500 bg-violet-500/15 text-white shadow-lg shadow-violet-500/10">
                                <div class="text-sm font-bold">720p HD</div>
                                <div class="text-[9px] opacity-60">Tavsiya etiladi</div>
                            </button>
                            <button type="button" data-res="480p" class="vid-res-btn p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20">
                                <div class="text-sm font-bold">480p</div>
                                <div class="text-[9px] opacity-60">Tez yaratish</div>
                            </button>
                        </div>
                    </div>

                    <!-- Generate Button -->
                    <button id="vid-generate-btn" disabled class="btn-primary w-full h-16 text-lg font-extrabold disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-3">
                        <span id="vid-btn-text" class="flex items-center gap-3">
                            <i class="fa-solid fa-video"></i>
                            Video yaratish
                        </span>
                    </button>

                    <!-- Progress bar -->
                    <div id="vid-progress-container" class="hidden space-y-2">
                        <div class="h-3 w-full bg-white/5 rounded-full overflow-hidden">
                            <div id="vid-progress-bar" class="h-full bg-gradient-to-r from-violet-600 via-fuchsia-500 to-pink-500 rounded-full transition-all duration-500 shadow-[0_0_15px_rgba(139,92,246,0.5)]" style="width: 0%"></div>
                        </div>
                        <p class="text-xs text-gray-500 text-center italic animate-pulse">
                            Video yaratilmoqda. Bu 1-5 daqiqa davom etishi mumkin...
                        </p>
                    </div>

                    <p id="vid-error" class="text-red-400 text-xs text-center hidden"></p>
                </div>
            </div>

            <!-- Right Panel — Result -->
            <div>
                <div class="glass-card p-6 sm:p-8 min-h-[500px] flex flex-col items-center justify-center sticky top-24">
                    <!-- Loading state -->
                    <div id="vid-loading" class="text-center animate-fade-in hidden">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-violet-600/20 to-fuchsia-600/20 border border-violet-500/20 flex items-center justify-center mx-auto mb-6">
                            <i class="fa-solid fa-circle-notch fa-spin text-3xl text-violet-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">AI video yaratmoqda...</h3>
                        <p class="text-sm text-gray-400">Bu jarayon 1-5 daqiqa vaqt olishi mumkin. Sahifani yopmang.</p>
                        <div class="mt-4 text-xs text-violet-400 font-bold" id="vid-status-text">Kutilmoqda...</div>
                        <div class="mt-6 flex justify-center gap-1">
                            <div class="w-2 h-2 rounded-full bg-violet-500 animate-pulse"></div>
                            <div class="w-2 h-2 rounded-full bg-violet-500 animate-pulse" style="animation-delay: 0.15s"></div>
                            <div class="w-2 h-2 rounded-full bg-violet-500 animate-pulse" style="animation-delay: 0.3s"></div>
                        </div>
                    </div>

                    <!-- Result state -->
                    <div id="vid-result" class="w-full animate-fade-in space-y-4 hidden">
                        <div class="relative group rounded-2xl overflow-hidden bg-black">
                            <video id="vid-player" controls class="w-full rounded-2xl shadow-2xl shadow-violet-500/10" playsinline>
                                <source id="vid-source" src="" type="video/mp4">
                            </video>
                        </div>

                        <div class="glass-card p-3 border border-white/5">
                            <div class="flex items-center gap-2 mb-1.5">
                                <i class="fa-solid fa-comment-dots text-violet-400 text-xs"></i>
                                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Ishlatilgan prompt</span>
                            </div>
                            <p id="vid-used-prompt" class="text-xs text-gray-500 line-clamp-3"></p>
                        </div>

                        <div class="flex gap-3">
                            <a id="vid-download-btn" href="" download class="btn-primary flex-1 text-center flex items-center justify-center gap-2">
                                <i class="fa-solid fa-download"></i>
                                Videoni yuklash
                            </a>
                            <button id="vid-regenerate-btn" class="btn-secondary px-6" title="Qayta yaratish">
                                <i class="fa-solid fa-rotate-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Empty state -->
                    <div id="vid-empty" class="text-center">
                        <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-violet-600/20 to-fuchsia-600/20 border border-violet-500/20 flex items-center justify-center mx-auto mb-6 shadow-xl shadow-violet-500/10">
                            <i class="fa-solid fa-video text-4xl text-violet-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Video shu yerda ko'rinadi</h3>
                        <p class="text-sm text-gray-500 max-w-xs mx-auto">
                            Tavsif yozing va "Video yaratish" tugmasini bosing. AI sizga professional video yaratadi.
                        </p>
                        <div class="mt-6 grid grid-cols-3 gap-3 max-w-xs mx-auto">
                            <div class="h-20 rounded-xl bg-gradient-to-br from-violet-600/10 to-fuchsia-600/10 border border-violet-500/10 flex items-center justify-center text-2xl animate-pulse text-violet-400/50"><i class="fa-solid fa-film"></i></div>
                            <div class="h-20 rounded-xl bg-gradient-to-br from-fuchsia-600/10 to-pink-600/10 border border-fuchsia-500/10 flex items-center justify-center text-2xl animate-pulse text-fuchsia-400/50" style="animation-delay: 0.3s"><i class="fa-solid fa-clapperboard"></i></div>
                            <div class="h-20 rounded-xl bg-gradient-to-br from-pink-600/10 to-rose-600/10 border border-pink-500/10 flex items-center justify-center text-2xl animate-pulse text-pink-400/50" style="animation-delay: 0.6s"><i class="fa-solid fa-camera-movie"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    let selectedMode = 'image';
    let selectedDuration = 5;
    let selectedRatio = 'auto';
    let selectedRes = '720p';
    let imageBase64 = null;
    let currentRequestId = null;
    let pollTimer = null;

    const promptInput = document.getElementById('vid-prompt');
    const charCount = document.getElementById('vid-char-count');
    const generateBtn = document.getElementById('vid-generate-btn');
    const btnText = document.getElementById('vid-btn-text');
    const progressContainer = document.getElementById('vid-progress-container');
    const progressBar = document.getElementById('vid-progress-bar');
    const errorEl = document.getElementById('vid-error');

    const loadingState = document.getElementById('vid-loading');
    const resultState = document.getElementById('vid-result');
    const emptyState = document.getElementById('vid-empty');
    const statusText = document.getElementById('vid-status-text');

    const imageUploadArea = document.getElementById('image-upload-area');
    const imageInput = document.getElementById('vid-image-input');
    const imagePreview = document.getElementById('image-preview');
    const uploadContent = document.getElementById('upload-content');
    const clearImageBtn = document.getElementById('clear-image-btn');

    // Character count
    promptInput.addEventListener('input', function() {
        charCount.textContent = this.value.length + ' ta belgi';
        generateBtn.disabled = this.value.trim().length < 5;
    });

    // Mode selector
    document.getElementById('mode-text').addEventListener('click', function() {
        selectedMode = 'text';
        this.className = 'vid-mode-btn p-3 rounded-xl border text-center transition-all duration-200 border-violet-500 bg-violet-500/15 text-white shadow-lg shadow-violet-500/10 flex items-center justify-center gap-2 text-sm font-bold';
        document.getElementById('mode-image').className = 'vid-mode-btn p-3 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20 flex items-center justify-center gap-2 text-sm font-bold';
        imageUploadArea.classList.add('hidden');
    });

    document.getElementById('mode-image').addEventListener('click', function() {
        selectedMode = 'image';
        this.className = 'vid-mode-btn p-3 rounded-xl border text-center transition-all duration-200 border-violet-500 bg-violet-500/15 text-white shadow-lg shadow-violet-500/10 flex items-center justify-center gap-2 text-sm font-bold';
        document.getElementById('mode-text').className = 'vid-mode-btn p-3 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20 flex items-center justify-center gap-2 text-sm font-bold';
        imageUploadArea.classList.remove('hidden');
    });

    // Image upload
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function() {
            imageBase64 = reader.result;
            imagePreview.src = imageBase64;
            imagePreview.classList.remove('hidden');
            uploadContent.classList.add('hidden');
            clearImageBtn.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    });

    clearImageBtn.addEventListener('click', function() {
        imageBase64 = null;
        imagePreview.classList.add('hidden');
        uploadContent.classList.remove('hidden');
        clearImageBtn.classList.add('hidden');
        imageInput.value = '';
    });

    // Example prompts
    document.querySelectorAll('.vid-example-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Barchadan aktiv holatni olib tashlash
            document.querySelectorAll('.vid-example-btn').forEach(b => {
                b.classList.remove('border-violet-500', 'bg-violet-500/15', 'text-white', 'shadow-lg', 'shadow-violet-500/10');
                b.classList.add('border-white/10', 'bg-white/[0.03]');
                b.querySelector('span').classList.remove('text-white');
                b.querySelector('span').classList.add('text-gray-400');
            });
            // Bu tugmani aktiv qilish
            this.classList.remove('border-white/10', 'bg-white/[0.03]');
            this.classList.add('border-violet-500', 'bg-violet-500/15', 'text-white', 'shadow-lg', 'shadow-violet-500/10');
            this.querySelector('span').classList.remove('text-gray-400');
            this.querySelector('span').classList.add('text-white');

            // Prompt ni textarea ga yozish
            promptInput.value = this.dataset.prompt;
            promptInput.dispatchEvent(new Event('input'));
            promptInput.focus();
        });
    });

    // Selector helpers
    function setupSelector(className, dataAttr, callback) {
        document.querySelectorAll('.' + className).forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.' + className).forEach(b => {
                    b.className = className + ' p-2.5 rounded-xl border text-center transition-all duration-200 border-white/10 bg-white/5 text-gray-400 hover:border-white/20';
                });
                this.className = className + ' p-2.5 rounded-xl border text-center transition-all duration-200 border-violet-500 bg-violet-500/15 text-white shadow-lg shadow-violet-500/10';
                callback(this.dataset[dataAttr]);
            });
        });
    }

    setupSelector('vid-dur-btn', 'dur', (v) => selectedDuration = parseInt(v));
    setupSelector('vid-ratio-btn', 'ratio', (v) => selectedRatio = v);
    setupSelector('vid-res-btn', 'res', (v) => selectedRes = v);

    function showState(state) {
        loadingState.classList.add('hidden');
        resultState.classList.add('hidden');
        emptyState.classList.add('hidden');
        if (state === 'loading') loadingState.classList.remove('hidden');
        if (state === 'result') resultState.classList.remove('hidden');
        if (state === 'empty') emptyState.classList.remove('hidden');
    }

    function showError(msg) {
        errorEl.textContent = msg;
        errorEl.classList.remove('hidden');
    }
    function hideError() {
        errorEl.classList.add('hidden');
    }

    // ========== GENERATE ==========
    async function handleGenerate() {
        const prompt = promptInput.value.trim();
        if (prompt.length < 5) return;

        hideError();
        generateBtn.disabled = true;
        btnText.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Yuborilmoqda...';
        progressContainer.classList.remove('hidden');
        progressBar.style.width = '5%';
        showState('loading');
        statusText.textContent = 'So\'rov yuborilmoqda...';

        const body = {
            action: 'generate',
            prompt: prompt,
            duration: selectedDuration,
            aspectRatio: selectedRatio,
            resolution: selectedRes,
        };

        if (selectedMode === 'image' && imageBase64) {
            body.productImage = imageBase64;
        }

        try {
            const response = await fetch('/api/video-ai.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-User-Token': TibaAuth.getToken()
                },
                body: JSON.stringify(body)
            });

            const data = await response.json();

            if (!response.ok) {
                if (data.auth_required) { TibaAuth.showModal(); throw new Error('Avval tizimga kiring'); }
                if (data.insufficient_balance) { showNoBalance(data.cost, data.balance); throw new Error('__nobalance__'); }
                throw new Error(data.error || 'Xatolik yuz berdi');
            }

            if (data.balance !== undefined) TibaAuth.updateBalance(data.balance);

            currentRequestId = data.requestId;
            statusText.textContent = 'Video generatsiya boshlandi...';
            progressBar.style.width = '15%';

            // Polling boshlash
            startPolling();

        } catch (err) {
            if (err.message !== '__nobalance__') {
                showError(err.message);
                showState('empty');
            }
            generateBtn.disabled = false;
            btnText.innerHTML = '<i class="fa-solid fa-video"></i> Video yaratish';
            progressContainer.classList.add('hidden');
        }
    }

    generateBtn.addEventListener('click', () => TibaAuth.requireAuth(handleGenerate));

    // ========== POLLING ==========
    let pollCount = 0;
    const MAX_POLLS = 120; // 120 * 5s = 10 daqiqa maks
    const pollMessages = [
        'AI video yaratmoqda...',
        'Sahnalar qurilmoqda...',
        'Animatsiya qo\'shilmoqda...',
        'Ranglar sozlanmoqda...',
        'Sifat tekshirilmoqda...',
        'Yakuniy touch qo\'shilmoqda...',
        'Deyarli tayyor...',
    ];

    function startPolling() {
        pollCount = 0;
        // setTimeout ishlatamiz toki so'rovlar bir-birini ustiga tushib ketmasin
        setTimeout(pollStatus, 6000);
    }

    async function pollStatus() {
        if (!currentRequestId) return;
        pollCount++;

        // Progress bar ni sekin oshirish
        const pct = Math.min(15 + (pollCount / MAX_POLLS) * 85, 98);
        progressBar.style.width = pct + '%';

        // Status matnini almashtirish
        const msgIdx = Math.min(Math.floor(pollCount / 6), pollMessages.length - 1);
        statusText.textContent = pollMessages[msgIdx];

        if (pollCount >= MAX_POLLS) {
            showError('Video yaratish juda uzoq davom etdi. Qayta urinib ko\'ring.');
            resetUI();
            return;
        }

        try {
            const response = await fetch('/api/video-ai.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-User-Token': TibaAuth.getToken()
                },
                body: JSON.stringify({
                    action: 'poll',
                    requestId: currentRequestId,
                })
            });

            const data = await response.json();

            if (data.status === 'done') {
                progressBar.style.width = '100%';
                
                const vidPlayer = document.getElementById('vid-player');
                const vidSource = document.getElementById('vid-source');
                const downloadBtn = document.getElementById('vid-download-btn');
                
                vidSource.src = data.videoUrl;
                vidPlayer.src = data.videoUrl; // Direct src often works better
                vidPlayer.load();
                vidPlayer.play().catch(e => console.warn('Autoplay prevented'));

                downloadBtn.href = data.videoUrl;
                downloadBtn.setAttribute('download', 'tiba_ai_video_' + Date.now() + '.mp4');

                document.getElementById('vid-used-prompt').textContent = promptInput.value.trim();
                showState('result');
                resetUI();
                currentRequestId = null; // To'xtatish
                return;
            }

            if (data.status === 'expired' || data.error) {
                showError(data.error || 'Xatolik yuz berdi');
                showState('empty');
                resetUI();
                currentRequestId = null; // To'xtatish
                return;
            }

            // Hali tayyor emas — keyingi pollingni rejalashtirish
            setTimeout(pollStatus, 6000);

        } catch (err) {
            console.error('Poll error:', err);
            // Network xatosi bo'lsa ham qayta urinib ko'rish
            setTimeout(pollStatus, 8000);
        }
    }

    function resetUI() {
        generateBtn.disabled = promptInput.value.trim().length < 5;
        btnText.innerHTML = '<i class="fa-solid fa-video"></i> Video yaratish';
        progressContainer.classList.add('hidden');
    }

    // Regenerate
    document.getElementById('vid-regenerate-btn').addEventListener('click', function() {
        if (promptInput.value.trim().length >= 5) {
            TibaAuth.requireAuth(handleGenerate);
        }
    });
})();
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
