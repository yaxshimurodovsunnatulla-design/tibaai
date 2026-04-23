<?php $pageTitle = 'Admin Panel – Tiba AI'; ?>
<?php include __DIR__ . '/../components/header.php'; ?>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.15); }
.activity-filter, .pay-filter { background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.08); color: #9ca3af; }
.activity-filter:hover, .pay-filter:hover { background: rgba(255,255,255,0.06); color: #d1d5db; }
.activity-filter.active, .pay-filter.active { background: rgba(99,102,241,0.15); border-color: rgba(99,102,241,0.3); color: #818cf8; }
</style>

<div class="py-8 sm:py-12 min-h-screen">
    <div id="auth-section" class="max-w-md mx-auto px-4">
        <!-- Login Form -->
        <div id="login-container" class="glass-card p-8 sm:p-10 space-y-8 animate-fade-in-up">
            <div class="text-center">
                <span class="text-4xl">🔐</span>
                <h2 class="mt-4 text-2xl font-extrabold text-white">Admin <span class="gradient-text">Kirish</span></h2>
                <p class="mt-2 text-sm text-gray-500 italic">Faqat vakolatli xodimlar uchun</p>
            </div>

            <div id="auth-error" class="hidden bg-red-500/10 border border-red-500/20 text-red-400 p-3 rounded-lg text-xs text-center"></div>

            <form id="login-form" class="space-y-6">
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1.5 block">Login</label>
                        <input type="text" id="username" required class="input-field" placeholder="Admin username" autocomplete="username">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1.5 block">Parol</label>
                        <input type="password" id="password" required class="input-field" placeholder="••••••••" autocomplete="current-password">
                    </div>
                </div>
                <button type="submit" class="btn-primary w-full py-3.5 font-bold shadow-indigo-500/20 shadow-lg">Davom etish</button>
            </form>

            <form id="2fa-form" class="hidden space-y-8 text-center">
                <div class="space-y-4">
                    <span class="text-4xl">📱</span>
                    <p class="text-gray-300 text-sm">Authenticator kodi (6 xona)</p>
                    <input type="text" id="2fa-token" required maxlength="6" class="input-field text-center text-2xl tracking-[0.8em] font-mono h-16" placeholder="000000">
                </div>
                <button type="submit" class="btn-primary w-full py-3.5 font-bold">Tizimga kirish</button>
                <p class="text-[10px] text-gray-600">Google Authenticator ilovasidagi kodni kiriting</p>
            </form>
        </div>
    </div>

    <div id="admin-section" class="hidden max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-white">Admin <span class="gradient-text">Panel</span></h1>
                <p class="text-gray-500 text-sm mt-1" id="admin-time"></p>
            </div>
            <div class="flex gap-3 items-center">
                <button id="logout-btn" class="text-gray-500 hover:text-red-400 transition-colors text-xs font-bold uppercase tracking-widest">Chiqish</button>
            </div>
        </div>

        <!-- Toast -->
        <div id="status-toast" class="hidden fixed top-24 right-8 z-50 animate-slide-up">
            <div class="bg-indigo-600 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center gap-3 border border-white/10 backdrop-blur-md">
                <span id="toast-icon">✅</span>
                <span id="toast-msg">Muvaffaqiyatli!</span>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex flex-wrap gap-2 mb-8 bg-white/5 p-1.5 rounded-2xl border border-white/5">
            <button data-tab="dashboard" class="tab-btn px-5 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all bg-indigo-600 text-white shadow-lg">📊 Dashboard</button>
            <button data-tab="prompts" class="tab-btn px-5 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all text-gray-400 hover:text-white">📝 Promptlar</button>
            <button data-tab="packages" class="tab-btn px-5 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all text-gray-400 hover:text-white">💰 Paketlar</button>
            <button data-tab="payments" class="tab-btn px-5 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all text-gray-400 hover:text-white">💳 To'lovlar <span id="pending-payments-badge" class="hidden ml-1 px-1.5 py-0.5 text-[9px] bg-amber-500 text-black rounded-full font-bold"></span></button>
            <button data-tab="sections" class="tab-btn px-5 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all text-gray-400 hover:text-white">🗂️ Bo'limlar</button>
            <button data-tab="gallery" class="tab-btn px-5 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all text-gray-400 hover:text-white">🖼️ Galeriya</button>
            <button data-tab="users" class="tab-btn px-5 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all text-gray-400 hover:text-white">👥 Foydalanuvchilar</button>
            <button data-tab="logs" class="tab-btn px-5 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all text-gray-400 hover:text-white">📜 Loglar</button>
            <button data-tab="settings" class="tab-btn px-5 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all text-gray-400 hover:text-white">⚙️ Sozlamalar</button>
        </div>

        <!-- Tab Content -->
        <div class="glass-card p-6 sm:p-8 min-h-[600px] border border-white/10 relative overflow-hidden">
            <!-- Dashboard -->
            <div id="tab-dashboard" class="admin-tab animate-fade-in">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">📊 Dashboard</h2>
                        <p class="text-xs text-gray-500 mt-1" id="dash-server-time">Toshkent vaqti: yuklanmoqda...</p>
                    </div>
                    <button onclick="loadDashboard()" class="btn-secondary px-4 py-2 text-xs font-bold">🔄 Yangilash</button>
                </div>

                <!-- Row 1: Primary Stats (4 cards) -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                    <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-4 group hover:border-indigo-500/20 transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] text-gray-500 uppercase tracking-widest">Jami rasmlar</span>
                            <span class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-400 text-sm"><i class="fa-solid fa-images"></i></span>
                        </div>
                        <div class="text-2xl font-black text-white" id="stat-total-images">—</div>
                        <div class="text-[10px] text-emerald-400 mt-1" id="stat-today-images-label">+0 bugun</div>
                    </div>
                    <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-4 group hover:border-purple-500/20 transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] text-gray-500 uppercase tracking-widest">Generatsiyalar</span>
                            <span class="w-8 h-8 rounded-lg bg-purple-500/10 flex items-center justify-center text-purple-400 text-sm"><i class="fa-solid fa-wand-magic-sparkles"></i></span>
                        </div>
                        <div class="text-2xl font-black text-white" id="stat-total-gens">—</div>
                        <div class="text-[10px] text-emerald-400 mt-1" id="stat-today-gens-label">+0 bugun</div>
                    </div>
                    <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-4 group hover:border-sky-500/20 transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] text-gray-500 uppercase tracking-widest">Foydalanuvchilar</span>
                            <span class="w-8 h-8 rounded-lg bg-sky-500/10 flex items-center justify-center text-sky-400 text-sm"><i class="fa-solid fa-users"></i></span>
                        </div>
                        <div class="text-2xl font-black text-white" id="stat-total-users">—</div>
                        <div class="text-[10px] text-emerald-400 mt-1" id="stat-today-users-label">+0 bugun</div>
                    </div>
                    <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-4 group hover:border-emerald-500/20 transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] text-gray-500 uppercase tracking-widest">Jami daromad</span>
                            <span class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400 text-sm"><i class="fa-solid fa-coins"></i></span>
                        </div>
                        <div class="text-2xl font-black text-emerald-400" id="stat-total-revenue">—</div>
                        <div class="text-[10px] text-emerald-400 mt-1" id="stat-today-revenue-label">+0 bugun</div>
                    </div>
                </div>

                <!-- Row 2: Secondary Stats (4 cards) -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                    <div class="bg-white/[0.03] border border-white/5 rounded-xl p-3 flex items-center gap-3">
                        <span class="w-9 h-9 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-400 text-sm flex-shrink-0"><i class="fa-solid fa-clock"></i></span>
                        <div>
                            <div class="text-lg font-extrabold text-amber-400" id="stat-pending">0</div>
                            <div class="text-[9px] text-gray-600 uppercase tracking-widest">Kutilmoqda</div>
                        </div>
                    </div>
                    <div class="bg-white/[0.03] border border-white/5 rounded-xl p-3 flex items-center gap-3">
                        <span class="w-9 h-9 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400 text-sm flex-shrink-0"><i class="fa-solid fa-bolt"></i></span>
                        <div>
                            <div class="text-lg font-extrabold text-blue-400" id="stat-today-requests">0</div>
                            <div class="text-[9px] text-gray-600 uppercase tracking-widest">Bugun so'rov</div>
                        </div>
                    </div>
                    <div class="bg-white/[0.03] border border-white/5 rounded-xl p-3 flex items-center gap-3">
                        <span class="w-9 h-9 rounded-lg bg-orange-500/10 flex items-center justify-center text-orange-400 text-sm flex-shrink-0"><i class="fa-solid fa-coins"></i></span>
                        <div>
                            <div class="text-lg font-extrabold text-orange-400" id="stat-total-balance">0</div>
                            <div class="text-[9px] text-gray-600 uppercase tracking-widest">Jami balans</div>
                        </div>
                    </div>
                    <div class="bg-white/[0.03] border border-white/5 rounded-xl p-3 flex items-center gap-3">
                        <span class="w-9 h-9 rounded-lg bg-cyan-500/10 flex items-center justify-center text-cyan-400 text-sm flex-shrink-0"><i class="fa-brands fa-telegram"></i></span>
                        <div>
                            <div class="text-lg font-extrabold text-cyan-400" id="stat-tg-users">0</div>
                            <div class="text-[9px] text-gray-600 uppercase tracking-widest">Telegram</div>
                        </div>
                    </div>
                </div>

                <!-- Row 3: Chart + Activity -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                    <!-- Weekly Chart -->
                    <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-5">
                        <h3 class="text-sm font-bold text-gray-300 mb-4 flex items-center gap-2">📈 Haftalik ko'rsatkichlar</h3>
                        <div id="weekly-chart" class="flex items-end gap-1.5 h-32"></div>
                        <div id="weekly-labels" class="flex gap-1.5 mt-2"></div>
                    </div>
                    
                    <!-- Recent Activity -->
                    <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-bold text-gray-300 flex items-center gap-2">🕐 So'nggi faoliyat</h3>
                            <button onclick="showAllActivity()" class="text-[10px] text-indigo-400 hover:text-indigo-300 font-bold transition-colors cursor-pointer">Barchasini ko'rish →</button>
                        </div>
                        <div id="recent-activity" class="space-y-2 max-h-[240px] overflow-y-auto pr-1 custom-scrollbar"></div>
                    </div>
                </div>

                <!-- Row 4: Server + Moliya -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-5">
                        <h3 class="text-sm font-bold text-gray-300 mb-3 flex items-center gap-2">💾 Server</h3>
                        <div class="space-y-2.5 text-xs">
                            <div class="flex justify-between"><span class="text-gray-500">Rasmlar hajmi</span><span class="text-white font-mono" id="stat-size">—</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Baza hajmi</span><span class="text-white font-mono" id="stat-db-size">—</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Bo'sh disk</span><span class="text-white font-mono" id="stat-disk">—</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">PHP</span><span class="text-white font-mono" id="stat-php">—</span></div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Holat</span>
                                <span class="flex items-center gap-1.5"><span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span><span class="text-green-400 font-bold">Online</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-5">
                        <h3 class="text-sm font-bold text-gray-300 mb-3 flex items-center gap-2">💰 Moliya</h3>
                        <div class="space-y-2.5 text-xs">
                            <div class="flex justify-between"><span class="text-gray-500">Jami to'lovlar</span><span class="text-white font-bold" id="stat-total-payments">—</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Sotilgan tangalar</span><span class="text-amber-400 font-bold" id="stat-credits-sold">—</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Kutilayotgan summa</span><span class="text-amber-400 font-mono" id="stat-pending-amount">—</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Bugun tasdiqlangan</span><span class="text-emerald-400 font-bold" id="stat-approved-today">—</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Faol sessiyalar</span><span class="text-white font-mono" id="stat-sessions">—</span></div>
                        </div>
                    </div>
                    <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-5">
                        <h3 class="text-sm font-bold text-gray-300 mb-3 flex items-center gap-2">🎯 Haftalik xulosa</h3>
                        <div class="space-y-2.5 text-xs">
                            <div class="flex justify-between"><span class="text-gray-500">Haftalik rasmlar</span><span class="text-indigo-400 font-bold" id="stat-week-images">—</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Faol foydalanuvchi</span><span class="text-purple-400 font-bold" id="stat-active-users">—</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Bugun rasmlar</span><span class="text-sky-400 font-bold" id="stat-today-images">—</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Bugun generatsiya</span><span class="text-green-400 font-bold" id="stat-today-gens">—</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Bugun foydalanuvchi</span><span class="text-cyan-400 font-bold" id="stat-today-users">—</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prompts -->
            <div id="tab-prompts" class="admin-tab hidden animate-fade-in">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">📝 Promptlar va Sozlamalar</h2>
                        <p class="text-xs text-gray-500 mt-1">AI modellari uchun promptlar va tizim sozlamalari</p>
                    </div>
                    <div class="flex gap-2">
                        <button id="refresh-prompts-btn" class="btn-secondary px-4 py-2 text-xs font-bold">🔄 Yangilash</button>
                        <button id="save-prompts-btn" class="btn-primary px-6 py-2 text-sm font-bold shadow-lg shadow-indigo-500/20">💾 Saqlash</button>
                    </div>
                </div>

                <!-- Prompt Tabs -->
                <div class="flex gap-1 bg-white/5 p-1 rounded-xl mb-6 overflow-x-auto no-scrollbar">
                    <button onclick="switchPromptTab('infografika')" class="prompt-tab-btn flex-1 px-4 py-2 rounded-lg text-[10px] font-bold uppercase transition-all whitespace-nowrap" data-tab="infografika">Infografika</button>
                    <button onclick="switchPromptTab('paket')" class="prompt-tab-btn flex-1 px-4 py-2 rounded-lg text-[10px] font-bold uppercase transition-all whitespace-nowrap" data-tab="paket">Paket</button>
                    <button onclick="switchPromptTab('smart-matn')" class="prompt-tab-btn flex-1 px-4 py-2 rounded-lg text-[10px] font-bold uppercase transition-all whitespace-nowrap" data-tab="smart-matn">Smart Matn</button>
                    <button onclick="switchPromptTab('foto-tahrir')" class="prompt-tab-btn flex-1 px-4 py-2 rounded-lg text-[10px] font-bold uppercase transition-all whitespace-nowrap" data-tab="foto-tahrir">Tahrir</button>
                    <button onclick="switchPromptTab('uslub-nusxalash')" class="prompt-tab-btn flex-1 px-4 py-2 rounded-lg text-[10px] font-bold uppercase transition-all whitespace-nowrap" data-tab="uslub-nusxalash">Uslub</button>
                    <button onclick="switchPromptTab('fashion-ai')" class="prompt-tab-btn flex-1 px-4 py-2 rounded-lg text-[10px] font-bold uppercase transition-all whitespace-nowrap" data-tab="fashion-ai">Fashion</button>
                    <button onclick="switchPromptTab('noldan-yaratish')" class="prompt-tab-btn flex-1 px-4 py-2 rounded-lg text-[10px] font-bold uppercase transition-all whitespace-nowrap" data-tab="noldan-yaratish">Noldan</button>
                </div>

                <div id="prompts-list" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- Loaded via JS -->
                </div>
            </div>

            <!-- Sections Management -->
            <div id="tab-sections" class="admin-tab hidden animate-fade-in">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">🗂️ Bo'limlar boshqaruvi</h2>
                    <div class="flex gap-2">
                        <button id="refresh-sections-btn" class="btn-secondary px-4 py-2 text-xs font-bold">🔄 Yangilash</button>
                        <button id="save-sections-order-btn" class="btn-primary px-6 py-2 text-sm font-bold shadow-lg shadow-indigo-500/20">💾 Tartibni saqlash</button>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mb-6 italic">Bo'limlar tartibini o'zgartirish uchun ularni sudrab o'tkazing (Drag & Drop).</p>
                <div id="sections-list" class="space-y-3">
                    <!-- Loaded via JS -->
                </div>
            </div>

            <!-- Image Modal -->
            <div id="image-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#0a0a0f]/90 backdrop-blur-xl animate-fade-in">
                <div class="max-w-4xl w-full bg-white/[0.03] border border-white/10 rounded-3xl overflow-hidden shadow-2xl">
                    <div class="relative group">
                        <img id="modal-image" src="" alt="Full view" class="w-full h-auto max-h-[70vh] object-contain block mx-auto">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-4">
                            <a id="modal-download" href="" download class="w-12 h-12 bg-white text-black rounded-full flex items-center justify-center hover:bg-gray-200 transition-transform hover:scale-110 shadow-xl" title="Yuklab olish">
                                <i class="fa-solid fa-download"></i>
                            </a>
                        </div>
                    </div>
                    <div class="p-6 flex items-center justify-between border-t border-white/5">
                        <button onclick="document.getElementById('image-modal').classList.add('hidden')" class="btn-secondary px-6 py-2.5 text-xs font-bold">Yopish</button>
                        <button id="modal-delete" class="bg-red-500/10 border border-red-500/30 text-red-400 px-6 py-2.5 rounded-xl text-xs font-bold hover:bg-red-500/20 transition-all flex items-center gap-2">
                            <i class="fa-solid fa-trash"></i> O'chirish
                        </button>
                    </div>
                </div>
            </div>

            <!-- Payments -->
            <div id="tab-payments" class="admin-tab hidden animate-fade-in">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">💳 To'lovlar</h2>
                        <p class="text-xs text-gray-500 mt-1" id="payments-subtitle">Yuklanmoqda...</p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="cleanupReceipts()" class="btn-secondary px-4 py-2 text-xs font-bold" title="2 kundan eski cheklarni tozalash">🧹 Tozalash</button>
                        <button onclick="loadPayments()" class="btn-secondary px-4 py-2 text-xs font-bold">🔄 Yangilash</button>
                    </div>
                </div>
                
                <!-- Payment Stats -->
                <div class="grid grid-cols-3 gap-3 mb-5">
                    <div class="bg-amber-500/5 border border-amber-500/10 rounded-xl p-3 text-center">
                        <div class="text-lg font-extrabold text-amber-400" id="pay-stat-pending">0</div>
                        <div class="text-[9px] text-gray-500 uppercase">Kutilmoqda</div>
                        <div class="text-[10px] text-amber-400/60 mt-0.5" id="pay-stat-pending-sum">0 so'm</div>
                    </div>
                    <div class="bg-emerald-500/5 border border-emerald-500/10 rounded-xl p-3 text-center">
                        <div class="text-lg font-extrabold text-emerald-400" id="pay-stat-approved">0</div>
                        <div class="text-[9px] text-gray-500 uppercase">Tasdiqlangan</div>
                        <div class="text-[10px] text-emerald-400/60 mt-0.5" id="pay-stat-approved-sum">0 so'm</div>
                    </div>
                    <div class="bg-red-500/5 border border-red-500/10 rounded-xl p-3 text-center">
                        <div class="text-lg font-extrabold text-red-400" id="pay-stat-rejected">0</div>
                        <div class="text-[9px] text-gray-500 uppercase">Rad etilgan</div>
                    </div>
                </div>
                
                <!-- Filter -->
                <div class="flex gap-2 mb-4 flex-wrap">
                    <button onclick="filterPayments('all')" class="pay-filter active px-3 py-1.5 rounded-lg text-[10px] font-bold border transition-all" data-filter="all">Barchasi</button>
                    <button onclick="filterPayments('pending')" class="pay-filter px-3 py-1.5 rounded-lg text-[10px] font-bold border transition-all" data-filter="pending">⏳ Kutilmoqda</button>
                    <button onclick="filterPayments('approved')" class="pay-filter px-3 py-1.5 rounded-lg text-[10px] font-bold border transition-all" data-filter="approved">✅ Tasdiqlangan</button>
                    <button onclick="filterPayments('rejected')" class="pay-filter px-3 py-1.5 rounded-lg text-[10px] font-bold border transition-all" data-filter="rejected">❌ Rad etilgan</button>
                </div>
                
                <!-- Payments list -->
                <div id="payments-list" class="space-y-3"></div>
            </div>


            <!-- Gallery -->
            <div id="tab-gallery" class="admin-tab hidden animate-fade-in">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">🖼️ Yaratilgan rasmlar</h2>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-500" id="gallery-count">0 ta rasm</span>
                        <button id="refresh-gallery-btn" class="btn-secondary px-4 py-2 text-xs font-bold">🔄</button>
                    </div>
                </div>
                <div id="gallery-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3"></div>
                <div id="gallery-pagination" class="flex justify-center gap-2 mt-6"></div>
                <div id="gallery-empty" class="hidden text-center py-20">
                    <span class="text-5xl mb-4 block">🖼️</span>
                    <p class="text-gray-500">Hali rasmlar yaratilmagan</p>
                </div>
            </div>

            <!-- Users -->
            <div id="tab-users" class="admin-tab hidden animate-fade-in">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">👥 Foydalanuvchilar</h2>
                        <p class="text-xs text-gray-500 mt-1" id="users-subtitle">Jami: yuklanmoqda...</p>
                    </div>
                    <button id="refresh-users-btn" class="btn-secondary px-4 py-2 text-xs font-bold">🔄 Yangilash</button>
                </div>

                <!-- Users Stats -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6" id="users-stats-grid">
                    <div class="bg-white/[0.03] border border-white/5 rounded-xl p-4 text-center">
                        <div class="text-2xl font-extrabold text-white" id="us-total">—</div>
                        <div class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">Jami foydalanuvchilar</div>
                    </div>
                    <div class="bg-white/[0.03] border border-white/5 rounded-xl p-4 text-center">
                        <div class="text-2xl font-extrabold text-amber-400" id="us-balance">—</div>
                        <div class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">Jami balans (tanga)</div>
                    </div>
                    <div class="bg-white/[0.03] border border-white/5 rounded-xl p-4 text-center">
                        <div class="text-2xl font-extrabold text-indigo-400" id="us-generations">—</div>
                        <div class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">Jami generatsiyalar</div>
                    </div>
                    <div class="bg-white/[0.03] border border-white/5 rounded-xl p-4 text-center">
                        <div class="text-2xl font-extrabold text-emerald-400" id="us-revenue">—</div>
                        <div class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">Jami daromad</div>
                    </div>
                </div>

                <!-- Search / Filter -->
                <div class="flex flex-col sm:flex-row gap-3 mb-5">
                    <div class="relative flex-1">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-600 text-xs"></i>
                        <input type="text" id="users-search" class="input-field text-sm pl-9 w-full" placeholder="Ism, email yoki ID bo'yicha qidirish..." oninput="filterUsers()">
                    </div>
                    <select id="users-sort" class="input-field text-sm w-auto" onchange="filterUsers()">
                        <option value="newest">Yangi → Eski</option>
                        <option value="oldest">Eski → Yangi</option>
                        <option value="balance-high">Balans ↓</option>
                        <option value="balance-low">Balans ↑</option>
                        <option value="generations">Generatsiyalar ↓</option>
                        <option value="spent">Sarflagan ↓</option>
                    </select>
                </div>

                <!-- Users List -->
                <div id="users-list" class="space-y-2"></div>
            </div>

            <!-- User Detail Modal -->
            <div id="user-detail-modal" class="hidden fixed inset-0 z-[60] bg-black/80 flex items-center justify-center p-4" style="position:fixed;">
                <div class="glass-card max-w-md w-full p-0 border border-white/10 shadow-2xl max-h-[90vh] overflow-y-auto">
                    <!-- User header -->
                    <div class="p-6 pb-4 border-b border-white/5">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white text-lg font-bold" id="ud-avatar">?</div>
                                <div>
                                    <h3 class="text-lg font-bold text-white" id="ud-name">—</h3>
                                    <p class="text-xs text-gray-500" id="ud-email">—</p>
                                </div>
                            </div>
                            <button onclick="closeUserModal()" class="text-gray-500 hover:text-white text-xl leading-none">✕</button>
                        </div>
                        <!-- Linked accounts -->
                        <div class="flex items-center gap-2 mt-3" id="ud-links"></div>
                    </div>

                    <!-- Stats grid -->
                    <div class="grid grid-cols-3 gap-0 border-b border-white/5">
                        <div class="p-4 text-center border-r border-white/5">
                            <div class="text-xl font-extrabold text-amber-400" id="ud-balance">—</div>
                            <div class="text-[9px] text-gray-600 uppercase tracking-widest mt-0.5">Balans</div>
                        </div>
                        <div class="p-4 text-center border-r border-white/5">
                            <div class="text-xl font-extrabold text-indigo-400" id="ud-gens">—</div>
                            <div class="text-[9px] text-gray-600 uppercase tracking-widest mt-0.5">Generatsiya</div>
                        </div>
                        <div class="p-4 text-center">
                            <div class="text-xl font-extrabold text-emerald-400" id="ud-spent">—</div>
                            <div class="text-[9px] text-gray-600 uppercase tracking-widest mt-0.5">Sarflagan</div>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="p-6 space-y-3">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">ID</span>
                            <span class="text-white font-mono" id="ud-id">—</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">To'lovlar soni</span>
                            <span class="text-white" id="ud-payments">—</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Sotib olingan tangalar</span>
                            <span class="text-amber-400 font-bold" id="ud-bought">—</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Ishlatilgan tangalar</span>
                            <span class="text-red-400" id="ud-used">—</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Sessiyalar</span>
                            <span class="text-white" id="ud-sessions">—</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Ro'yxatdan o'tgan</span>
                            <span class="text-white" id="ud-created">—</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Oxirgi faollik</span>
                            <span class="text-white" id="ud-last-active">—</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="p-6 pt-0 flex gap-3">
                        <button onclick="closeUserModal()" class="flex-1 py-2.5 rounded-xl border border-white/10 text-gray-400 hover:text-white hover:bg-white/5 transition-all text-sm font-medium">Yopish</button>
                        <button id="ud-delete-btn" class="flex-1 py-2.5 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 hover:bg-red-500/20 transition-all text-sm font-bold">
                            <i class="fa-solid fa-trash-can mr-1"></i> O'chirish
                        </button>
                    </div>
                </div>
            </div>

            <!-- Logs -->
            <div id="tab-logs" class="admin-tab hidden animate-fade-in">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">📜 Tizim Loglari</h2>
                    <button id="refresh-logs-btn" class="btn-secondary px-4 py-2 text-xs font-bold">🔄 Yangilash</button>
                </div>
                <div class="bg-black/20 rounded-xl border border-white/5 p-4 h-[600px] overflow-y-auto font-mono text-[10px] leading-relaxed" id="logs-container">
                    <div class="text-center text-gray-500 py-10">Loglar yuklanmoqda...</div>
                </div>
            </div>

            <!-- Packages -->
            <div id="tab-packages" class="admin-tab hidden animate-fade-in">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">💰 Tanga Paketlari</h2>
                        <p class="text-xs text-gray-500 mt-1">Pricing sahifadagi narx paketlarini boshqaring</p>
                    </div>
                    <button onclick="showPackageModal()" class="btn-primary px-5 py-2.5 text-sm font-bold shadow-lg shadow-indigo-500/20">
                        <i class="fa-solid fa-plus mr-1"></i> Yangi paket
                    </button>
                </div>
                <div id="packages-list" class="space-y-3"></div>
            </div>

            <!-- Package Edit Modal -->
            <div id="package-edit-modal" class="hidden fixed inset-0 z-[60] bg-black/80 flex items-center justify-center p-4" style="position:fixed;">
                <div class="glass-card max-w-lg w-full p-6 space-y-4 border border-white/10 shadow-2xl max-h-[90vh] overflow-y-auto">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-lg font-bold text-white" id="pkg-modal-title">Paketni tahrirlash</h3>
                        <button onclick="closePackageModal()" class="text-gray-500 hover:text-white text-xl">✕</button>
                    </div>
                    <input type="hidden" id="pkg-edit-old-id">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 block">Paket ID (slug)</label>
                            <input type="text" id="pkg-edit-id" class="input-field text-sm font-mono" placeholder="starter">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 block">Nomi</label>
                            <input type="text" id="pkg-edit-name" class="input-field text-sm" placeholder="Boshlang'ich">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 block">Tangalar soni</label>
                            <input type="number" id="pkg-edit-credits" class="input-field text-sm" placeholder="50">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 block">Narxi (so'm)</label>
                            <input type="number" id="pkg-edit-price" class="input-field text-sm" placeholder="69000">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 block">Ikonka (FA class)</label>
                            <div class="flex items-center gap-2">
                                <input type="text" id="pkg-edit-icon" class="input-field text-sm font-mono flex-1" placeholder="fa-seedling">
                                <div id="pkg-icon-preview" class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center text-white"></div>
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 block">Gradient</label>
                            <input type="text" id="pkg-edit-gradient" class="input-field text-sm font-mono" placeholder="from-gray-600 to-gray-500">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 block">Badge (bo'sh = yoq)</label>
                            <input type="text" id="pkg-edit-badge" class="input-field text-sm" placeholder="Mashhur">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 block">Badge gradient</label>
                            <input type="text" id="pkg-edit-badge-gradient" class="input-field text-sm font-mono" placeholder="from-indigo-600 to-purple-600">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 block">Xususiyatlar (har bir qator = 1 ta)</label>
                        <textarea id="pkg-edit-features" class="input-field text-sm h-24" placeholder="~10 ta infografika&#10;~5 ta fotosesiya&#10;Muddati cheksiz"></textarea>
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="text-xs text-gray-400 flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="pkg-edit-active" class="w-4 h-4 rounded accent-indigo-500" checked>
                            Faol
                        </label>
                    </div>
                    <div id="pkg-edit-error" class="hidden bg-red-500/10 border border-red-500/20 text-red-400 p-3 rounded-xl text-xs text-center"></div>
                    <div class="flex gap-3 pt-2">
                        <button onclick="closePackageModal()" class="flex-1 py-3 rounded-xl border border-white/10 text-gray-400 hover:text-white hover:bg-white/5 transition-all text-sm font-medium">Bekor qilish</button>
                        <button onclick="savePackage()" id="pkg-save-btn" class="flex-[2] btn-primary py-3 text-sm font-bold">
                            <i class="fa-solid fa-floppy-disk mr-1"></i> Saqlash
                        </button>
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div id="tab-settings" class="admin-tab hidden animate-fade-in">
                <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">⚙️ Tizim sozlamalari</h2>
                <div class="space-y-6">
                    <!-- Cleanup -->
                    <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-6">
                        <h3 class="text-sm font-bold text-white mb-2">🧹 Rasmlarni tozalash</h3>
                        <p class="text-xs text-gray-500 mb-4">Eski yaratilgan rasmlarni o'chirish orqali diskda joy bo'shatish</p>
                        <div class="flex items-center gap-3">
                            <select id="cleanup-days" class="input-field w-auto text-sm">
                                <option value="1">1 kundan eski</option>
                                <option value="3">3 kundan eski</option>
                                <option value="7" selected>7 kundan eski</option>
                                <option value="14">14 kundan eski</option>
                                <option value="30">30 kundan eski</option>
                            </select>
                            <button id="cleanup-btn" class="btn-primary px-6 py-2.5 text-sm font-bold">🗑️ Tozalash</button>
                        </div>
                        <div id="cleanup-result" class="hidden mt-4 bg-green-500/10 border border-green-500/20 text-green-400 p-3 rounded-lg text-xs"></div>
                    </div>

                    <!-- To'lov sozlamalari -->
                    <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-6">
                        <h3 class="text-sm font-bold text-white mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-credit-card text-emerald-400"></i> To'lov sozlamalari
                        </h3>
                        <p class="text-xs text-gray-500 mb-4">Pricing sahifada ko'rsatiladigan karta ma'lumotlari</p>
                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 block">Karta raqami</label>
                                <input type="text" id="settings-card-number" class="input-field text-sm font-mono" placeholder="8600 0000 0000 0000" maxlength="19">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 block">Karta egasi</label>
                                <input type="text" id="settings-card-holder" class="input-field text-sm uppercase" placeholder="ISM FAMILIYA">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 block">Admin Telegram Chat ID</label>
                                <input type="text" id="settings-admin-chat-id" class="input-field text-sm font-mono" placeholder="8496157812">
                                <p class="text-[9px] text-gray-500 mt-1">Yangi to'lovlar haqida bildirishnoma boradigan chat ID</p>
                            </div>

                            <!-- Click/Payme Toggle -->
                            <style>
                                .toggle-switch { position:relative; width:44px; height:24px; }
                                .toggle-switch input { opacity:0; width:0; height:0; }
                                .toggle-slider { position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background:rgba(255,255,255,0.1); border-radius:24px; transition:.3s; }
                                .toggle-slider:before { position:absolute; content:""; height:20px; width:20px; left:2px; bottom:2px; background:#9ca3af; border-radius:50%; transition:.3s; }
                                .toggle-switch input:checked + .toggle-slider { background:#2563eb; }
                                .toggle-switch input:checked + .toggle-slider:before { transform:translateX(20px); background:#fff; }
                                .toggle-switch.cyan input:checked + .toggle-slider { background:#0891b2; }
                            </style>
                            <div class="border-t border-white/5 pt-4 mt-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 block">To'lov usullari</label>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between p-3 rounded-xl bg-white/[0.03] border border-white/5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-lg bg-blue-500/10 flex items-center justify-center">
                                                <i class="fa-solid fa-mobile-screen text-blue-400 text-sm"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-white">Click</div>
                                                <div class="text-[10px] text-gray-500">Online to'lov ilovasi</div>
                                            </div>
                                        </div>
                                        <label class="toggle-switch">
                                            <input type="checkbox" id="settings-click-enabled" checked>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <div class="flex items-center justify-between p-3 rounded-xl bg-white/[0.03] border border-white/5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-lg bg-cyan-500/10 flex items-center justify-center">
                                                <i class="fa-solid fa-wallet text-cyan-400 text-sm"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-white">Payme</div>
                                                <div class="text-[10px] text-gray-500">Online to'lov ilovasi</div>
                                            </div>
                                        </div>
                                        <label class="toggle-switch cyan">
                                            <input type="checkbox" id="settings-payme-enabled" checked>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-2">
                                <button onclick="savePaymentSettings()" id="save-payment-settings-btn" class="w-full btn-primary py-2.5 text-xs font-bold">
                                    💾 To'lov sozlamalarini saqlash
                                </button>
                                <div id="payment-settings-status" class="hidden mt-2 text-center text-[10px] text-emerald-400">✅ Saqlandi</div>
                            </div>
                        </div>
                    </div>

                    <!-- Google Auth Warning/Info -->
                    <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-6">
                        <h3 class="text-sm font-bold text-white mb-2 flex items-center gap-2">
                            <i class="fa-brands fa-google text-red-400"></i> Google Sign-In Sozlamalari
                        </h3>
                        <p class="text-xs text-gray-400 mb-4">Google orqali kirish ishlashi uchun quyidagi originlar Google Console'da ruxsat etilgan bo'lishi kerak:</p>
                        <div class="space-y-3">
                            <div class="bg-black/40 p-3 rounded-xl border border-white/5">
                                <code class="text-[10px] text-indigo-400 block break-all" id="current-origin-display">http://localhost:8000</code>
                                <p class="text-[9px] text-gray-600 mt-1">Authorized JavaScript origins segmentiga qo'shing</p>
                            </div>
                            <p class="text-[10px] text-amber-500/80 leading-relaxed italic">
                                <i class="fa-solid fa-circle-info mr-1"></i> Agar "Origin not allowed" xatosini ko'rsangiz, brauzer manzili Google Console'dagi "Authorized JavaScript origins" ro'yxatida yo'qligini tekshiring.
                            </p>
                        </div>
                    </div>

                    <!-- System Info -->
                    <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-6">
                        <h3 class="text-sm font-bold text-white mb-4">🖥️ Server ma'lumotlari</h3>
                        <div id="system-info" class="space-y-2 text-xs"></div>
                    </div>

                    <!-- Active Extensions -->
                    <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-6">
                        <h3 class="text-sm font-bold text-white mb-4">🔌 PHP kengaytmalari</h3>
                        <div id="extensions-list" class="flex flex-wrap gap-2"></div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="bg-red-500/5 border border-red-500/20 rounded-2xl p-6">
                        <h3 class="text-sm font-bold text-red-400 mb-2">⚠️ Xavfli zona</h3>
                        <p class="text-xs text-gray-500 mb-4">Bu amallar qaytarilmaydi</p>
                        <div class="flex gap-3">
                            <button id="clear-all-images-btn" class="px-4 py-2 bg-red-500/10 border border-red-500/30 text-red-400 rounded-lg text-xs font-bold hover:bg-red-500/20 transition-all">BARCHA rasmlarni o'chirish</button>
                            <button id="clear-rate-limits-btn" class="px-4 py-2 bg-orange-500/10 border border-orange-500/30 text-orange-400 rounded-lg text-xs font-bold hover:bg-orange-500/20 transition-all">Rate limitlarni tozalash</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading overlay -->
            <div id="tab-loading" class="hidden absolute inset-0 bg-[#0a0a0f]/80 backdrop-blur-sm flex items-center justify-center z-20">
                <div class="loader w-12 h-12 border-4 border-indigo-500/20 border-t-indigo-500"></div>
            </div>
        </div>
    </div>
</div>

<!-- All Activity Modal -->
<div id="activity-modal" class="hidden fixed inset-0 z-[60] bg-black/80 flex items-center justify-center p-4" style="position:fixed;">
    <div class="glass-card max-w-2xl w-full p-6 border border-white/10 shadow-2xl max-h-[85vh] flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="text-lg font-bold text-white">🕐 Barcha faoliyatlar</h3>
                <p class="text-[10px] text-gray-500 mt-0.5" id="activity-count">Yuklanmoqda...</p>
            </div>
            <button onclick="closeActivityModal()" class="text-gray-500 hover:text-white text-xl">✕</button>
        </div>
        <!-- Filter buttons -->
        <div class="flex gap-2 mb-4 flex-wrap">
            <button onclick="filterActivity('all')" class="activity-filter active px-3 py-1.5 rounded-lg text-[10px] font-bold border transition-all" data-filter="all">Barchasi</button>
            <button onclick="filterActivity('generation')" class="activity-filter px-3 py-1.5 rounded-lg text-[10px] font-bold border transition-all" data-filter="generation">🎨 Generatsiyalar</button>
            <button onclick="filterActivity('payment')" class="activity-filter px-3 py-1.5 rounded-lg text-[10px] font-bold border transition-all" data-filter="payment">💰 To'lovlar</button>
            <button onclick="filterActivity('user')" class="activity-filter px-3 py-1.5 rounded-lg text-[10px] font-bold border transition-all" data-filter="user">👤 Yangi foydalanuvchilar</button>
        </div>
        <!-- Activity list -->
        <div id="all-activity-list" class="space-y-2 overflow-y-auto flex-1 pr-1 custom-scrollbar"></div>
    </div>
</div>

<!-- Section Edit Modal -->
<div id="section-edit-modal" class="hidden fixed inset-0 z-[60] bg-black/80 flex items-center justify-center p-4">
    <div class="glass-card max-w-lg w-full p-6 space-y-4 border border-white/10 shadow-2xl">
        <div class="flex justify-between items-center mb-2">
            <h3 class="text-lg font-bold text-white">Bo'limni tahrirlash</h3>
            <button onclick="document.getElementById('section-edit-modal').classList.add('hidden')" class="text-gray-500 hover:text-white">✕</button>
        </div>
        <input type="hidden" id="edit-section-id">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 block">Nomi</label>
                <input type="text" id="edit-section-name" class="input-field text-sm">
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 block">Badge (Top/Yangi)</label>
                <input type="text" id="edit-section-badge" class="input-field text-sm">
            </div>
        </div>
        <div>
            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 block">Tavsif</label>
            <textarea id="edit-section-desc" class="input-field text-sm h-20"></textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 block">Icon (FA class)</label>
                <input type="text" id="edit-section-icon" class="input-field text-sm font-mono" placeholder="fa-solid fa-wand-magic-sparkles">
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 block">Holati</label>
                <select id="edit-section-active" class="input-field text-sm">
                    <option value="1">Faol</option>
                    <option value="0">O'chirilgan</option>
                </select>
            </div>
        </div>
        <div>
            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1 block">Gradient (Tailwind classes)</label>
            <input type="text" id="edit-section-gradient" class="input-field text-sm font-mono" placeholder="from-blue-500 to-indigo-600">
        </div>
        <div class="flex gap-3 pt-2">
            <button onclick="saveServiceData()" class="btn-primary flex-1 py-3 font-bold">Saqlash</button>
            <button onclick="document.getElementById('section-edit-modal').classList.add('hidden')" class="btn-secondary px-6">Bekor qilish</button>
        </div>
    </div>
</div>

<!-- Receipt Modal (top-level for proper centering) -->
<div id="receipt-modal" class="hidden fixed inset-0 z-[70] bg-black/90 flex items-center justify-center p-3" onclick="if(event.target===this)closeReceiptModal()">
    <div class="max-w-2xl w-full bg-[#13131a]/95 backdrop-blur-xl p-5 border border-white/10 rounded-2xl shadow-2xl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-3">
            <div>
                <h3 class="text-sm font-bold text-white flex items-center gap-2">📎 To'lov cheki</h3>
                <p id="receipt-info" class="text-[11px] text-gray-500 mt-0.5"></p>
            </div>
            <button onclick="closeReceiptModal()" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center text-gray-400 hover:text-white transition-all">✕</button>
        </div>
        
        <!-- Image -->
        <div class="bg-black/40 rounded-xl border border-white/5 flex items-center justify-center overflow-hidden" style="max-height:70vh;">
            <p id="receipt-loading-text" class="text-gray-400 text-sm py-16">📎 Yuklanmoqda...</p>
            <img id="receipt-img" src="" alt="Chek" class="w-full h-auto max-h-[70vh] object-contain hidden">
        </div>
        
        <!-- Actions -->
        <div id="receipt-actions" class="hidden mt-3 flex gap-2">
            <button id="receipt-approve-btn" onclick="approveFromReceipt()" class="flex-1 py-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold hover:bg-emerald-500/20 transition-all flex items-center justify-center gap-1.5">✅ Tasdiqlash</button>
            <button id="receipt-reject-btn" onclick="rejectFromReceipt()" class="flex-1 py-2.5 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-bold hover:bg-red-500/20 transition-all flex items-center justify-center gap-1.5">❌ Rad etish</button>
        </div>
        <div id="receipt-status-info" class="hidden mt-3 text-center text-[11px] text-gray-500 py-2"></div>
    </div>
</div>

<script src="/assets/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const authSect = document.getElementById('auth-section');
    const adminSect = document.getElementById('admin-section');
    const loginCont = document.getElementById('login-container');
    const loginForm = document.getElementById('login-form');
    const tfaForm = document.getElementById('2fa-form');
    const authError = document.getElementById('auth-error');
    
    let currentPrompts = null;
    let currentPromptTab = 'infografika';
    let galleryPage = 1;
    let sessionToken = sessionStorage.getItem('admin_session');
    let currentTempToken = null; // Changed: For 2FA flow

    // Session check is deferred until after tab handlers are set up

    // ========== AUTH ==========
    loginForm.onsubmit = async (e) => {
        e.preventDefault();
        const user = document.getElementById('username').value;
        const pass = document.getElementById('password').value;
        
        try {
            const res = await apiCall('/api/admin-auth.php', { action: 'login', username: user, password: pass }, false);
            
            if (res.step === 'setup' || res.step === '2fa') {
                currentTempToken = res.tempToken; // Store pending token
                loginForm.classList.add('hidden');
                
                if (res.step === 'setup') {
                    // Pass tempToken to get-qr
                    const qrData = await apiCall('/api/admin-auth.php', { action: 'get-qr', tempToken: currentTempToken }, false);
                    loginCont.innerHTML = `
                        <div class="text-center space-y-6 animate-fade-in flex flex-col items-center">
                            <span class="text-4xl">📱</span>
                            <h3 class="text-xl font-bold text-white">2FA Sozlash</h3>
                            <div class="bg-white p-4 rounded-2xl inline-block shadow-2xl mx-auto border-4 border-white/10">
                                <img src="${qrData.qr}" class="w-56 h-56 block mx-auto" alt="QR Code">
                            </div>
                            <div class="space-y-2">
                                <p class="text-[11px] text-gray-400 leading-relaxed px-4">QR kodni skanerlang yoki maxfiy kalitni kiriting:</p>
                                <code class="block bg-white/5 border border-white/10 py-2 px-3 rounded-lg text-indigo-400 font-mono text-sm tracking-wider select-all">${qrData.secret}</code>
                            </div>
                            <button id="goto-2fa-btn" class="btn-primary w-full py-3.5 font-bold shadow-lg shadow-indigo-500/20">Davom etish</button>
                        </div>`;
                    document.getElementById('goto-2fa-btn').onclick = () => {
                        show2FAInput();
                    };
                } else {
                    tfaForm.classList.remove('hidden');
                }
            }
        } catch (err) {
            authError.innerText = err.message;
            authError.classList.remove('hidden');
        }
    };

    function show2FAInput() {
        loginCont.innerHTML = `
            <div class="text-center space-y-8 animate-fade-in">
                <div class="space-y-4">
                    <span class="text-4xl text-center block">📱</span>
                    <p class="text-gray-300 text-sm">Authenticator kodi (6 xona)</p>
                    <input type="text" id="manual-2fa-token" required maxlength="6" class="input-field text-center text-2xl tracking-[0.8em] font-mono h-16" placeholder="000000">
                </div>
                <button id="manual-submit-btn" class="btn-primary w-full py-3.5 font-bold">Tizimga kirish</button>
            </div>`;
        document.getElementById('manual-submit-btn').onclick = () => {
            const t = document.getElementById('manual-2fa-token').value;
            if (t.length === 6) submit2FA(t);
            else alert('6 xonali kodni kiriting');
        };
    }

    tfaForm.onsubmit = async (e) => { e.preventDefault(); submit2FA(document.getElementById('2fa-token').value); };

    async function submit2FA(code) {
        try {
            const data = await apiCall('/api/admin-auth.php', { action: 'verify-2fa', code, tempToken: currentTempToken }, false);
            sessionToken = data.sessionToken;
            sessionStorage.setItem('admin_session', sessionToken);
            showAdmin();
        } catch (err) { 
            alert(err.message); 
            // If session expired or unauthorized, reload
            if (err.message.includes('login')) window.location.reload();
        }
    }

    async function apiCall(url, body, auth = true) {
        const headers = { 'Content-Type': 'application/json' };
        if (auth && sessionToken) headers['X-Admin-Session'] = sessionToken;
        try {
            const res = await fetch(url, { method: 'POST', headers, body: JSON.stringify(body) });
            
            // Handle HTTP errors manually before parsing JSON to avoid crashes on 401/404/500
            if (!res.ok) {
                if (res.status === 401 && auth) { 
                    sessionStorage.removeItem('admin_session'); 
                    // Prevent infinite reload loop
                    if (!window.location.search.includes('error=session_expired')) {
                        window.location.href = window.location.pathname + '?error=session_expired';
                    }
                }
                const data = await res.json().catch(() => ({ error: 'Server xatosi' }));
                throw new Error(data.error || 'Xatolik');
            }
            
            return await res.json();
        } catch (err) {
            console.warn('API Call Error:', err.message);
            throw err;
        }
    }

    function showAdmin() {
        authSect.classList.add('hidden');
        adminSect.classList.remove('hidden');
        // Hashdan tabni tiklash
        const hash = window.location.hash.replace('#', '');
        const validTabs = ['dashboard','prompts','packages','payments','sections','gallery','users','logs','settings'];
        const savedTab = validTabs.includes(hash) ? hash : 'dashboard';
        const tabBtn = document.querySelector(`.tab-btn[data-tab="${savedTab}"]`);
        if (tabBtn) tabBtn.click();
        else loadDashboard();
    }

    document.getElementById('logout-btn').onclick = () => { sessionStorage.removeItem('admin_session'); window.location.reload(); };

    // ========== TAB SWITCHING ==========
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.onclick = () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.className = 'tab-btn px-5 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all text-gray-400 hover:text-white');
            btn.className = 'tab-btn px-5 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all bg-indigo-600 text-white shadow-lg';
            document.querySelectorAll('.admin-tab').forEach(t => t.classList.add('hidden'));
            document.getElementById('tab-' + btn.dataset.tab).classList.remove('hidden');
            
            const tab = btn.dataset.tab;
            window.history.replaceState(null, '', '#' + tab);
            if (tab === 'dashboard') loadDashboard();
            if (tab === 'prompts' && !currentPrompts) loadPrompts();
            if (tab === 'sections') loadSections();
            if (tab === 'gallery') loadGallery(1);
            if (tab === 'packages') loadPackages();
            if (tab === 'settings') { loadSystemInfo(); loadPaymentSettings(); }
            if (tab === 'users') loadUsers();
            if (tab === 'payments') loadPayments();
            if (tab === 'logs') loadLogs();
        };
    });

    // showAdmin() is called at the END of script after all functions are defined

    // ========== SECTIONS MANAGEMENT ==========
    let currentSections = [];
    async function loadSections() {
        showLoading(true);
        try {
            const res = await apiCall('/api/admin-services.php', { action: 'get' });
            currentSections = res.services;
            renderSections();
        } catch (err) { showToast('❌ ' + err.message, 'error'); }
        showLoading(false);
    }

    function renderSections() {
        const container = document.getElementById('sections-list');
        container.innerHTML = currentSections.map(s => `
            <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-4 flex items-center gap-4 group hover:border-indigo-500/30 transition-all cursor-move" data-id="${s.id}">
                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br ${s.gradient} flex items-center justify-center text-xl shadow-lg">
                    <i class="${s.icon} text-white"></i>
                </div>
                <div class="flex-grow">
                    <div class="flex items-center gap-2">
                        <h4 class="text-sm font-bold text-white">${s.name}</h4>
                        ${s.badge ? `<span class="text-[8px] font-bold px-1.5 py-0.5 rounded bg-indigo-500/20 text-indigo-300 border border-indigo-500/20">${s.badge}</span>` : ''}
                        ${!parseInt(s.is_active) ? `<span class="text-[8px] font-bold px-1.5 py-0.5 rounded bg-red-500/20 text-red-400 border border-red-500/20">O'chirilgan</span>` : ''}
                    </div>
                    <p class="text-[10px] text-gray-500 mt-0.5 truncate max-w-md">${s.description}</p>
                </div>
                <div class="flex-shrink-0 flex items-center gap-2">
                    <button onclick="editSection(${s.id})" class="p-2 text-gray-500 hover:text-white transition-colors"><i class="fa-solid fa-pen-to-square text-sm"></i></button>
                    <div class="p-2 text-gray-700"><i class="fa-solid fa-bars-staggered cursor-grab active:cursor-grabbing"></i></div>
                </div>
            </div>
        `).join('');

        // Initialize Sortable
        if (typeof Sortable !== 'undefined') {
            if (window.sectionsSortable) window.sectionsSortable.destroy();
            window.sectionsSortable = new Sortable(container, {
                animation: 150,
                ghostClass: 'opacity-50',
                onEnd: () => {
                    const ids = Array.from(container.children).map(child => parseInt(child.dataset.id));
                    ids.forEach((id, index) => {
                        const sec = currentSections.find(s => s.id === id);
                        if (sec) sec.sort_order = index + 1;
                    });
                }
            });
        } else {
            console.warn('SortableJS kutubxonasi yuklanmadi. Drag-and-drop ishlamaydi.');
        }
    }

    window.editSection = (id) => {
        const s = currentSections.find(sec => sec.id === id);
        if (!s) return;
        document.getElementById('edit-section-id').value = s.id;
        document.getElementById('edit-section-name').value = s.name;
        document.getElementById('edit-section-desc').value = s.description;
        document.getElementById('edit-section-badge').value = s.badge || '';
        document.getElementById('edit-section-icon').value = s.icon;
        document.getElementById('edit-section-gradient').value = s.gradient;
        document.getElementById('edit-section-active').value = s.is_active;
        document.getElementById('section-edit-modal').classList.remove('hidden');
    };

    window.saveServiceData = async () => {
        const id = document.getElementById('edit-section-id').value;
        const data = {
            name: document.getElementById('edit-section-name').value,
            description: document.getElementById('edit-section-desc').value,
            badge: document.getElementById('edit-section-badge').value,
            icon: document.getElementById('edit-section-icon').value,
            gradient: document.getElementById('edit-section-gradient').value,
            is_active: parseInt(document.getElementById('edit-section-active').value)
        };

        try {
            await apiCall('/api/admin-services.php', { action: 'update_service', id, data });
            document.getElementById('section-edit-modal').classList.add('hidden');
            showToast('✅ Bo\'lim muvaffaqiyatli yangilandi');
            loadSections();
        } catch (err) { showToast('❌ ' + err.message, 'error'); }
    };

    document.getElementById('save-sections-order-btn').onclick = async () => {
        const orders = currentSections.map(s => ({ id: s.id, sort_order: s.sort_order }));
        try {
            await apiCall('/api/admin-services.php', { action: 'update_order', orders });
            showToast('✅ Tartib muvaffaqiyatli saqlandi');
            loadSections();
        } catch (err) { showToast('❌ ' + err.message, 'error'); }
    };

    document.getElementById('refresh-sections-btn').onclick = loadSections;

    // ========== PROMPTS ==========
    async function loadPrompts() {
        showLoading(true);
        try {
            currentPrompts = await apiCall('/api/admin-prompts.php', { action: 'get' });
            renderPromptTab(currentPromptTab);
        } catch (err) { showToast('❌ ' + err.message, 'error'); }
        showLoading(false);
    }

    window.switchPromptTab = (tab) => {
        currentPromptTab = tab;
        renderPromptTab(tab);
    };

    function renderPromptTab(tab) {
        document.querySelectorAll('.prompt-tab-btn').forEach(b => {
            if (b.dataset.tab === tab) b.className = 'prompt-tab-btn flex-1 px-4 py-2 rounded-lg text-[10px] font-bold uppercase transition-all whitespace-nowrap bg-indigo-600 text-white shadow-lg';
            else b.className = 'prompt-tab-btn flex-1 px-4 py-2 rounded-lg text-[10px] font-bold uppercase transition-all whitespace-nowrap text-gray-400 hover:text-white hover:bg-white/5';
        });

        const container = document.getElementById('prompts-list');
        container.innerHTML = '';
        
        const tabData = currentPrompts[tab] || {};
        
        Object.entries(tabData).forEach(([key, val]) => {
            const div = document.createElement('div');
            div.className = 'bg-white/[0.03] border border-white/5 rounded-2xl p-5 hover:border-white/10 transition-all';
            
            if (Array.isArray(val)) {
                // For paket, it's an array of 5 prompts
                div.innerHTML = `
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.5)]"></span>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">${key} (5 ta variant)</span>
                        </div>
                        <button class="text-[10px] text-red-400/50 hover:text-red-400 transition-colors font-bold uppercase" onclick="deletePromptKey('${tab}','${key}')">O'chirish</button>
                    </div>
                    <div class="space-y-3">
                        ${val.map((v, i) => `<textarea class="input-field h-24 text-xs font-mono bg-black/20 border-white/5" data-tab="${tab}" data-key="${key}" data-idx="${i}" placeholder="Variant ${i+1}">${v}</textarea>`).join('')}
                    </div>`;
            } else {
                div.innerHTML = `
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.5)]"></span>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">${key}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-[9px] text-gray-600">${(val || '').length} belgi</span>
                            <button class="text-[10px] text-red-400/50 hover:text-red-400 transition-colors font-bold uppercase" onclick="deletePromptKey('${tab}','${key}')">O'chirish</button>
                        </div>
                    </div>
                    <textarea class="input-field h-36 text-xs font-mono leading-relaxed bg-black/20 border-white/10 hover:border-white/20 transition-all" data-tab="${tab}" data-key="${key}">${val || ''}</textarea>`;
            }
            container.appendChild(div);
            
            div.querySelectorAll('textarea').forEach(ta => {
                ta.oninput = (e) => {
                    const t = e.target.dataset.tab;
                    const k = e.target.dataset.key;
                    const i = e.target.dataset.idx;
                    if (i !== undefined) {
                        currentPrompts[t][k][parseInt(i)] = e.target.value;
                    } else {
                        currentPrompts[t][k] = e.target.value;
                    }
                };
            });
        });

        const addBtn = document.createElement('button');
        addBtn.className = 'w-full py-4 border-2 border-dashed border-white/10 hover:border-indigo-500/50 rounded-2xl text-xs font-bold text-gray-500 hover:text-indigo-400 transition-all uppercase tracking-widest mt-4';
        addBtn.innerHTML = '➕ Yangi stil qo\'shish';
        addBtn.onclick = () => {
            const id = prompt('Yangi stil ID (inglizcha, kichik harflarda):');
            if (!id) return;
            if (currentPrompts[tab] && currentPrompts[tab][id]) return alert('Bu ID allaqachon mavjud!');
            if (!currentPrompts[tab]) currentPrompts[tab] = {};
            currentPrompts[tab][id] = tab === 'paket' ? ["","","","",""] : "";
            renderPromptTab(tab);
        };
        container.appendChild(addBtn);
    }

    window.deletePromptKey = (tab, key) => {
        if (!confirm(`"${key}" stilni o'chirishni xohlaysizmi?`)) return;
        delete currentPrompts[tab][key];
        renderPromptTab(currentPromptTab);
        showToast('🗑️ Stil o\'chirildi (saqlashni unutmang!)');
    };

    document.getElementById('save-prompts-btn').onclick = async () => {
        const btn = document.getElementById('save-prompts-btn');
        btn.disabled = true; btn.innerText = '💾 Saqlanmoqda...';
        try {
            await apiCall('/api/admin-prompts.php', { action: 'update', data: currentPrompts });
            showToast('✅ Promptlar muvaffaqiyatli saqlandi!');
        } catch (err) { showToast('❌ ' + err.message, 'error'); }
        btn.disabled = false; btn.innerText = '💾 Saqlash';
    };

    document.getElementById('refresh-prompts-btn').onclick = loadPrompts;

    // ========== DASHBOARD ==========
    async function loadDashboard() {
        try {
            const d = await apiCall('/api/admin-stats.php', { action: 'dashboard' });
            
            // Server time (Toshkent)
            document.getElementById('dash-server-time').textContent = '🕐 Toshkent vaqti: ' + d.serverTime;
            document.getElementById('admin-time').textContent = 'Toshkent: ' + d.serverTime;

            // Primary stats
            document.getElementById('stat-total-images').textContent = d.totalImages;
            document.getElementById('stat-today-images-label').textContent = '+' + d.todayImages + ' bugun';
            
            document.getElementById('stat-total-gens').textContent = d.totalGens || 0;
            document.getElementById('stat-today-gens-label').textContent = '+' + (d.todayGens || 0) + ' bugun';
            
            document.getElementById('stat-total-users').textContent = d.totalUsers || 0;
            document.getElementById('stat-today-users-label').textContent = '+' + (d.todayUsers || 0) + ' bugun';
            
            document.getElementById('stat-total-revenue').textContent = formatMoney(d.totalRevenue || 0);
            document.getElementById('stat-today-revenue-label').textContent = '+' + formatMoney(d.todayRevenue || 0) + ' bugun';

            // Secondary stats
            document.getElementById('stat-pending').textContent = d.pendingPayments || 0;
            document.getElementById('stat-today-requests').textContent = d.todayRequests || 0;
            document.getElementById('stat-total-balance').textContent = (d.totalBalance || 0).toLocaleString('uz-UZ');
            document.getElementById('stat-tg-users').textContent = d.telegramUsers || 0;

            // Server info
            document.getElementById('stat-size').textContent = d.totalSizeMB + ' MB';
            document.getElementById('stat-db-size').textContent = d.dbSizeKB + ' KB';
            document.getElementById('stat-disk').textContent = d.diskFreeGB + ' GB';
            document.getElementById('stat-php').textContent = 'PHP ' + d.phpVersion;
            document.getElementById('stat-sessions').textContent = d.activeSessions;

            // Finance
            document.getElementById('stat-total-payments').textContent = (d.totalPayments || 0) + ' ta';
            document.getElementById('stat-credits-sold').textContent = (d.totalCreditsSold || 0).toLocaleString('uz-UZ') + ' tanga';
            document.getElementById('stat-pending-amount').textContent = formatMoney(d.pendingAmount || 0);
            document.getElementById('stat-approved-today').textContent = (d.approvedToday || 0) + ' ta';

            // Weekly summary
            document.getElementById('stat-week-images').textContent = d.weekImages || 0;
            document.getElementById('stat-active-users').textContent = d.activeUsers || 0;
            document.getElementById('stat-today-images').textContent = d.todayImages || 0;
            document.getElementById('stat-today-gens').textContent = d.todayGens || 0;
            document.getElementById('stat-today-users').textContent = d.todayUsers || 0;

            // ========== WEEKLY CHART ==========
            const chart = d.weeklyChart || [];
            const maxVal = Math.max(1, ...chart.map(c => c.generations));
            const chartEl = document.getElementById('weekly-chart');
            const labelsEl = document.getElementById('weekly-labels');
            
            chartEl.innerHTML = chart.map((c, i) => {
                const h = Math.max(4, (c.generations / maxVal) * 100);
                const isToday = i === chart.length - 1;
                const color = isToday ? 'bg-indigo-500' : 'bg-indigo-500/40';
                return `
                    <div class="flex-1 flex flex-col items-center justify-end gap-1 group cursor-default" title="${c.date}: ${c.generations} gen, ${c.users} user, ${formatMoney(c.revenue)}">
                        <span class="text-[9px] text-gray-500 opacity-0 group-hover:opacity-100 transition-opacity">${c.generations}</span>
                        <div class="${color} rounded-t-md w-full transition-all hover:bg-indigo-400" style="height:${h}%"></div>
                    </div>`;
            }).join('');
            
            labelsEl.innerHTML = chart.map((c, i) => {
                const isToday = i === chart.length - 1;
                return `<div class="flex-1 text-center text-[9px] ${isToday ? 'text-indigo-400 font-bold' : 'text-gray-600'}">${isToday ? 'Bugun' : c.day}</div>`;
            }).join('');

            // ========== RECENT ACTIVITY ==========
            const actEl = document.getElementById('recent-activity');
            if (d.recentActivity && d.recentActivity.length > 0) {
                actEl.innerHTML = d.recentActivity.map(a => {
                    const colorMap = {
                        'generation': 'border-l-indigo-500',
                        'payment': a.status === 'approved' ? 'border-l-emerald-500' : (a.status === 'pending' ? 'border-l-amber-500' : 'border-l-red-500'),
                        'user': 'border-l-sky-500',
                    };
                    const borderColor = colorMap[a.type] || 'border-l-gray-500';
                    const timeStr = a.time ? a.time.split(' ')[1] || a.time : '';
                    return `
                        <div class="flex items-center gap-3 py-2 px-3 rounded-lg bg-white/[0.02] border-l-2 ${borderColor} hover:bg-white/[0.04] transition-colors">
                            <span class="text-sm flex-shrink-0">${a.icon}</span>
                            <div class="flex-1 min-w-0">
                                <div class="text-[11px] text-gray-300 truncate">${a.text}</div>
                            </div>
                            <span class="text-[9px] text-gray-600 font-mono flex-shrink-0">${timeStr}</span>
                        </div>`;
                }).join('');
            } else {
                actEl.innerHTML = '<div class="text-center text-gray-600 py-6 text-xs">Faoliyat topilmadi</div>';
            }

        } catch (err) { showToast('❌ ' + err.message, 'error'); }
    }

    function formatMoney(n) {
        if (!n || n === 0) return "0 so'm";
        if (n >= 1000000) return (n / 1000000).toFixed(1) + "M so'm";
        if (n >= 1000) return Math.round(n / 1000).toLocaleString('uz-UZ') + "K so'm";
        return n.toLocaleString('uz-UZ') + " so'm";
    }
    
    window.loadDashboard = loadDashboard;

    // ========== ALL ACTIVITY MODAL ==========
    let allActivityData = [];
    let activityFilter = 'all';

    window.showAllActivity = async () => {
        document.getElementById('activity-modal').classList.remove('hidden');
        document.getElementById('all-activity-list').innerHTML = '<div class="text-center text-gray-500 py-10"><div class="loader w-8 h-8 border-4 border-indigo-500/20 border-t-indigo-500 mx-auto mb-3"></div>Yuklanmoqda...</div>';
        document.getElementById('activity-count').textContent = 'Yuklanmoqda...';
        activityFilter = 'all';
        // Reset filter buttons
        document.querySelectorAll('.activity-filter').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.filter === 'all');
        });

        try {
            const d = await apiCall('/api/admin-stats.php', { action: 'all-activity' });
            allActivityData = d.activity || [];
            document.getElementById('activity-count').textContent = `Jami: ${allActivityData.length} ta faoliyat`;
            renderActivityList(allActivityData);
        } catch (err) {
            document.getElementById('all-activity-list').innerHTML = `<div class="text-center text-red-400 py-10">${err.message}</div>`;
        }
    };

    window.filterActivity = (type) => {
        activityFilter = type;
        document.querySelectorAll('.activity-filter').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.filter === type);
        });
        const filtered = type === 'all' ? allActivityData : allActivityData.filter(a => a.type === type);
        document.getElementById('activity-count').textContent = `Ko'rsatilmoqda: ${filtered.length} ta / Jami: ${allActivityData.length} ta`;
        renderActivityList(filtered);
    };

    window.closeActivityModal = () => {
        document.getElementById('activity-modal').classList.add('hidden');
    };

    function renderActivityList(items) {
        const el = document.getElementById('all-activity-list');
        if (!items.length) {
            el.innerHTML = '<div class="text-center text-gray-500 py-10">Faoliyat topilmadi</div>';
            return;
        }

        el.innerHTML = items.map(a => {
            const colorMap = {
                'generation': 'border-l-indigo-500',
                'payment': a.status === 'approved' ? 'border-l-emerald-500' : (a.status === 'pending' ? 'border-l-amber-500' : 'border-l-red-500'),
                'user': 'border-l-sky-500',
            };
            const borderColor = colorMap[a.type] || 'border-l-gray-500';
            const timeStr = a.time || '';
            const dateStr = timeStr.split(' ')[0] || '';
            const clockStr = timeStr.split(' ')[1] || '';

            return `
                <div class="flex items-center gap-3 py-2.5 px-3 rounded-lg bg-white/[0.02] border-l-2 ${borderColor} hover:bg-white/[0.04] transition-colors">
                    <span class="text-sm flex-shrink-0">${a.icon}</span>
                    <div class="flex-1 min-w-0">
                        <div class="text-[11px] text-gray-300">${a.text}</div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <div class="text-[9px] text-gray-600 font-mono">${clockStr}</div>
                        <div class="text-[8px] text-gray-700 font-mono">${dateStr}</div>
                    </div>
                </div>`;
        }).join('');
    }
    // ========== PAYMENTS ==========
    let allPaymentsData = [];
    let paymentsFilter = 'all';

    window.loadPayments = async () => {
        showLoading(true);
        try {
            const d = await apiCall('/api/admin-stats.php', { action: 'payments' });
            allPaymentsData = d.payments || [];
            const s = d.stats || {};
            
            // Update stats
            document.getElementById('pay-stat-pending').textContent = s.pending || 0;
            document.getElementById('pay-stat-approved').textContent = s.approved || 0;
            document.getElementById('pay-stat-rejected').textContent = s.rejected || 0;
            document.getElementById('pay-stat-pending-sum').textContent = formatMoney(s.pending_sum || 0);
            document.getElementById('pay-stat-approved-sum').textContent = formatMoney(s.approved_sum || 0);
            document.getElementById('payments-subtitle').textContent = `Jami: ${allPaymentsData.length} ta to'lov`;
            
            // Badge
            const badge = document.getElementById('pending-payments-badge');
            if (s.pending > 0) {
                badge.textContent = s.pending;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
            
            paymentsFilter = 'all';
            document.querySelectorAll('.pay-filter').forEach(b => b.classList.toggle('active', b.dataset.filter === 'all'));
            renderPayments(allPaymentsData);
        } catch (err) { showToast('❌ ' + err.message, 'error'); }
        showLoading(false);
    };

    window.filterPayments = (status) => {
        paymentsFilter = status;
        document.querySelectorAll('.pay-filter').forEach(b => b.classList.toggle('active', b.dataset.filter === status));
        const filtered = status === 'all' ? allPaymentsData : allPaymentsData.filter(p => p.status === status);
        renderPayments(filtered);
    };

    function renderPayments(payments) {
        const el = document.getElementById('payments-list');
        if (!payments.length) {
            el.innerHTML = '<div class="text-center text-gray-500 py-10">To\'lovlar topilmadi</div>';
            return;
        }

        el.innerHTML = payments.map(p => {
            const amount = parseInt(p.amount) || 0;
            const credits = parseInt(p.credits) || 0;
            const isPending = p.status === 'pending';
            const isApproved = p.status === 'approved';
            
            const statusBadge = isPending 
                ? '<span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-amber-500/15 text-amber-400 border border-amber-500/20">⏳ Kutilmoqda</span>'
                : isApproved 
                    ? '<span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-emerald-500/15 text-emerald-400 border border-emerald-500/20">✅ Tasdiqlangan</span>'
                    : '<span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-red-500/15 text-red-400 border border-red-500/20">❌ Rad etilgan</span>';

            const borderColor = isPending ? 'border-l-amber-500' : (isApproved ? 'border-l-emerald-500' : 'border-l-red-500');
            const bgHighlight = isPending ? 'bg-amber-500/[0.02]' : 'bg-white/[0.02]';
            
            const dateStr = (p.created_at || '').split(' ')[0] || '';
            const timeStr = (p.created_at || '').split(' ')[1] || '';

            const receiptBtn = p.receipt_path 
                ? `<button onclick="event.stopPropagation(); showReceipt('${p.receipt_path}', ${p.id}, '${p.status}', '${(p.user_name || '').replace(/'/g,"\\'")}', ${amount}, ${credits})" class="text-[10px] text-sky-400 hover:text-sky-300 font-bold transition-colors">📎 Chek</button>` 
                : '';

            const actions = isPending 
                ? `<div class="flex gap-2 mt-3 pt-3 border-t border-white/5">
                    <button onclick="event.stopPropagation(); approvePayment(${p.id})" class="flex-1 py-2 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold hover:bg-emerald-500/20 transition-all flex items-center justify-center gap-1">✅ Tasdiqlash</button>
                    <button onclick="event.stopPropagation(); rejectPayment(${p.id})" class="flex-1 py-2 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-[10px] font-bold hover:bg-red-500/20 transition-all flex items-center justify-center gap-1">❌ Rad etish</button>
                   </div>`
                : (p.admin_note ? `<div class="mt-2 text-[9px] text-gray-600">📝 ${p.admin_note}</div>` : '');

            return `
            <div class="border-l-2 ${borderColor} ${bgHighlight} border border-white/5 rounded-xl p-4 hover:bg-white/[0.04] transition-all">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-600/60 to-purple-600/60 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        ${(p.user_name || '?')[0].toUpperCase()}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm font-bold text-white">${p.user_name || 'Noma\'lum'}</span>
                            <span class="text-[9px] text-gray-600 font-mono">#${p.id}</span>
                            ${statusBadge}
                        </div>
                        <div class="text-[10px] text-gray-500 mt-0.5">${p.user_email || ''}</div>
                        <div class="flex items-center gap-3 mt-2 flex-wrap">
                            <span class="text-xs font-extrabold text-white">${amount.toLocaleString('uz-UZ')} so'm</span>
                            <span class="text-[10px] text-indigo-400 font-bold">${credits} tanga</span>
                            <span class="text-[10px] text-gray-600">${p.package_name || ''}</span>
                            ${receiptBtn}
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <div class="text-[9px] text-gray-600 font-mono">${timeStr}</div>
                        <div class="text-[8px] text-gray-700 font-mono">${dateStr}</div>
                    </div>
                </div>
                ${actions}
            </div>`;
        }).join('');
    }

    window.approvePayment = async (id) => {
        if (!confirm(`To'lov #${id} ni tasdiqlaysizmi? Foydalanuvchiga tangalar qo'shiladi.`)) return;
        try {
            const d = await apiCall('/api/admin-stats.php', { action: 'approve-payment', id });
            showToast('✅ ' + d.message);
            loadPayments();
        } catch (err) { showToast('❌ ' + err.message, 'error'); }
    };

    window.rejectPayment = async (id) => {
        const reason = prompt("Rad etish sababini kiriting (ixtiyoriy):");
        if (reason === null) return; // Cancelled
        try {
            const d = await apiCall('/api/admin-stats.php', { action: 'reject-payment', id, reason });
            showToast('✅ ' + d.message);
            loadPayments();
        } catch (err) { showToast('❌ ' + err.message, 'error'); }
    };

    let currentReceiptPaymentId = null;

    window.showReceipt = async (filename, paymentId, status, userName, amount, credits) => {
        currentReceiptPaymentId = paymentId;
        const modal = document.getElementById('receipt-modal');
        const img = document.getElementById('receipt-img');
        const loadingText = document.getElementById('receipt-loading-text');
        const actions = document.getElementById('receipt-actions');
        const statusInfo = document.getElementById('receipt-status-info');
        const info = document.getElementById('receipt-info');
        
        // Reset
        img.classList.add('hidden');
        img.src = '';
        loadingText.textContent = '📎 Yuklanmoqda...';
        loadingText.classList.remove('hidden');
        modal.classList.remove('hidden');
        
        // Payment info
        info.innerHTML = `<span class="text-white font-bold">${userName || 'Noma\'lum'}</span> — ${Number(amount || 0).toLocaleString('uz-UZ')} so'm (${credits || 0} tanga) · <span class="font-mono">#${paymentId}</span>`;
        
        // Show/hide action buttons
        if (status === 'pending') {
            actions.classList.remove('hidden');
            statusInfo.classList.add('hidden');
        } else {
            actions.classList.add('hidden');
            statusInfo.classList.remove('hidden');
            statusInfo.innerHTML = status === 'approved' 
                ? '<span class="text-emerald-400">✅ Bu to\'lov allaqachon tasdiqlangan</span>' 
                : '<span class="text-red-400">❌ Bu to\'lov rad etilgan</span>';
        }
        
        try {
            const d = await apiCall('/api/admin-stats.php', { action: 'view-receipt', filename });
            img.src = d.image;
            img.classList.remove('hidden');
            loadingText.classList.add('hidden');
        } catch (err) {
            loadingText.textContent = '❌ ' + err.message;
        }
    };

    window.approveFromReceipt = async () => {
        if (!currentReceiptPaymentId) return;
        if (!confirm(`To'lov #${currentReceiptPaymentId} ni tasdiqlaysizmi?`)) return;
        try {
            const d = await apiCall('/api/admin-stats.php', { action: 'approve-payment', id: currentReceiptPaymentId });
            showToast('✅ ' + d.message);
            closeReceiptModal();
            loadPayments();
        } catch (err) { showToast('❌ ' + err.message, 'error'); }
    };

    window.rejectFromReceipt = async () => {
        if (!currentReceiptPaymentId) return;
        const reason = prompt("Rad etish sababini kiriting (ixtiyoriy):");
        if (reason === null) return;
        try {
            const d = await apiCall('/api/admin-stats.php', { action: 'reject-payment', id: currentReceiptPaymentId, reason });
            showToast('✅ ' + d.message);
            closeReceiptModal();
            loadPayments();
        } catch (err) { showToast('❌ ' + err.message, 'error'); }
    };

    window.closeReceiptModal = () => {
        document.getElementById('receipt-modal').classList.add('hidden');
        document.getElementById('receipt-loading-text').classList.remove('hidden');
        const img = document.getElementById('receipt-img');
        img.src = '';
        img.classList.add('hidden');
        currentReceiptPaymentId = null;
    };

    window.cleanupReceipts = async () => {
        if (!confirm('2 kundan eski cheklarni o\'chirasizmi?')) return;
        try {
            const d = await apiCall('/api/admin-stats.php', { action: 'cleanup-receipts' });
            showToast('✅ ' + d.message);
            if (d.deleted > 0) loadPayments();
        } catch (err) { showToast('❌ ' + err.message, 'error'); }
    };

    let allUsers = [];


    async function loadUsers() {
        showLoading(true);
        try {
            const d = await apiCall('/api/admin-stats.php', { action: 'users' });
            allUsers = d.users || [];
            
            // Stats
            const st = d.stats || {};
            document.getElementById('us-total').textContent = st.total_users || 0;
            document.getElementById('us-balance').textContent = (st.total_balance || 0).toLocaleString('uz-UZ');
            document.getElementById('us-generations').textContent = (st.total_generations || 0).toLocaleString('uz-UZ');
            document.getElementById('us-revenue').textContent = (st.total_revenue || 0).toLocaleString('uz-UZ') + " so'm";
            document.getElementById('users-subtitle').textContent = `Jami: ${allUsers.length} ta foydalanuvchi`;
            
            filterUsers();
        } catch (err) { showToast('❌ ' + err.message, 'error'); }
        showLoading(false);
    }

    window.filterUsers = () => {
        const q = (document.getElementById('users-search').value || '').toLowerCase().trim();
        const sort = document.getElementById('users-sort').value;

        let filtered = allUsers.filter(u => {
            if (!q) return true;
            return (u.name || '').toLowerCase().includes(q)
                || (u.email || '').toLowerCase().includes(q)
                || String(u.id).includes(q)
                || (u.telegram_id || '').includes(q);
        });

        // Sort
        switch (sort) {
            case 'oldest': filtered.sort((a, b) => a.id - b.id); break;
            case 'balance-high': filtered.sort((a, b) => (b.balance || 0) - (a.balance || 0)); break;
            case 'balance-low': filtered.sort((a, b) => (a.balance || 0) - (b.balance || 0)); break;
            case 'generations': filtered.sort((a, b) => (b.generation_count || 0) - (a.generation_count || 0)); break;
            case 'spent': filtered.sort((a, b) => (b.total_spent || 0) - (a.total_spent || 0)); break;
            default: filtered.sort((a, b) => b.id - a.id);
        }

        renderUsers(filtered);
    };

    function renderUsers(users) {
        const container = document.getElementById('users-list');
        if (!users.length) {
            container.innerHTML = '<div class="text-center text-gray-500 py-10">Foydalanuvchilar topilmadi</div>';
            return;
        }

        container.innerHTML = users.map(u => {
            const initial = (u.name || '?')[0].toUpperCase();
            const balance = parseInt(u.balance) || 0;
            const gens = parseInt(u.generation_count) || 0;
            const spent = parseInt(u.total_spent) || 0;
            const bought = parseInt(u.total_credits_bought) || 0;
            const used = Math.max(0, bought + 10 - balance); // 10 = boshlang'ich tanga
            const sessions = parseInt(u.session_count) || 0;
            const hasTg = !!u.telegram_id;
            const hasGoogle = !!u.google_id;
            const lastActive = u.last_active ? timeAgo(u.last_active) : 'Noma\'lum';

            // Balance color
            const balColor = balance >= 50 ? 'text-emerald-400' : (balance > 0 ? 'text-amber-400' : 'text-red-400');

            return `
            <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-4 hover:bg-white/[0.05] transition-all group cursor-pointer" onclick="showUserDetail(${u.id})">
                <div class="flex items-center gap-4">
                    <!-- Avatar -->
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600/80 to-purple-600/80 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">${initial}</div>

                    <!-- Main info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm font-bold text-white">${u.name}</span>
                            <span class="text-[10px] text-gray-600 font-mono">#${u.id}</span>
                            ${hasTg ? '<i class="fa-brands fa-telegram text-[11px] text-sky-400" title="Telegram"></i>' : ''}
                            ${hasGoogle ? '<i class="fa-brands fa-google text-[11px] text-red-400" title="Google"></i>' : ''}
                        </div>
                        <div class="text-[11px] text-gray-500 truncate">${u.email || 'Email yo\'q'}</div>
                    </div>

                    <!-- Stats pills -->
                    <div class="hidden sm:flex items-center gap-2 flex-shrink-0">
                        <div class="text-center px-1.5 py-1 rounded-lg bg-white/[0.03]" title="Hozirgi balans">
                            <div class="${balColor} text-sm font-extrabold">${balance}</div>
                            <div class="text-[7px] text-gray-600 uppercase">balans</div>
                        </div>
                        <div class="text-center px-1.5 py-1 rounded-lg bg-white/[0.03]" title="Sotib olingan tangalar">
                            <div class="text-sky-400 text-sm font-extrabold">${bought}</div>
                            <div class="text-[7px] text-gray-600 uppercase">olgan</div>
                        </div>
                        <div class="text-center px-1.5 py-1 rounded-lg bg-white/[0.03]" title="Ishlatilgan tangalar">
                            <div class="${used > 0 ? 'text-orange-400' : 'text-gray-600'} text-sm font-extrabold">${used}</div>
                            <div class="text-[7px] text-gray-600 uppercase">ishlatgan</div>
                        </div>
                        <div class="text-center px-1.5 py-1 rounded-lg bg-white/[0.03]" title="Yaratilgan rasmlar">
                            <div class="text-indigo-400 text-sm font-extrabold">${gens}</div>
                            <div class="text-[7px] text-gray-600 uppercase">rasm</div>
                        </div>
                    </div>

                    <!-- Last active -->
                    <div class="hidden lg:block text-right flex-shrink-0">
                        <div class="text-[10px] text-gray-600">${lastActive}</div>
                        <div class="text-[9px] text-gray-700">${sessions} sessiya</div>
                    </div>

                    <!-- Arrow -->
                    <div class="text-gray-700 group-hover:text-gray-400 transition-colors flex-shrink-0">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </div>
                </div>
            </div>`;
        }).join('');
    }

    function timeAgo(dateStr) {
        if (!dateStr) return 'Noma\'lum';
        const now = new Date();
        const date = new Date(dateStr.replace(' ', 'T') + 'Z');
        const diffMs = now - date;
        const mins = Math.floor(diffMs / 60000);
        if (mins < 1) return 'Hozir';
        if (mins < 60) return mins + ' daqiqa oldin';
        const hours = Math.floor(mins / 60);
        if (hours < 24) return hours + ' soat oldin';
        const days = Math.floor(hours / 24);
        if (days < 30) return days + ' kun oldin';
        const months = Math.floor(days / 30);
        return months + ' oy oldin';
    }

    window.showUserDetail = (id) => {
        const u = allUsers.find(x => x.id == id);
        if (!u) return;

        const balance = parseInt(u.balance) || 0;
        const gens = parseInt(u.generation_count) || 0;
        const bought = parseInt(u.total_credits_bought) || 0;
        const used = Math.max(0, bought + 10 - balance); // 10 = initial balance
        const spent = parseInt(u.total_spent) || 0;

        document.getElementById('ud-avatar').textContent = (u.name || '?')[0].toUpperCase();
        document.getElementById('ud-name').textContent = u.name;
        document.getElementById('ud-email').textContent = u.email || 'Email yo\'q';
        document.getElementById('ud-balance').textContent = balance;
        document.getElementById('ud-gens').textContent = gens;
        document.getElementById('ud-spent').textContent = spent > 0 ? spent.toLocaleString('uz-UZ') : '0';
        document.getElementById('ud-id').textContent = '#' + u.id;
        document.getElementById('ud-payments').textContent = (parseInt(u.payment_count) || 0) + ' ta';
        document.getElementById('ud-bought').textContent = bought + ' tanga';
        document.getElementById('ud-used').textContent = used + ' tanga';
        document.getElementById('ud-sessions').textContent = (parseInt(u.session_count) || 0) + ' ta';
        document.getElementById('ud-created').textContent = u.created_at || '—';
        document.getElementById('ud-last-active').textContent = u.last_active ? timeAgo(u.last_active) + ' (' + u.last_active + ')' : 'Noma\'lum';

        // Linked accounts
        let links = '';
        if (u.telegram_id) links += `<span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-sky-500/10 border border-sky-500/20 text-sky-400 text-[10px] font-bold"><i class="fa-brands fa-telegram"></i> ${u.telegram_id}</span>`;
        if (u.google_id) links += `<span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-[10px] font-bold"><i class="fa-brands fa-google"></i> Google</span>`;
        if (!u.telegram_id && !u.google_id) links += '<span class="text-[10px] text-gray-600">Email orqali ro\'yxatdan o\'tgan</span>';
        document.getElementById('ud-links').innerHTML = links;

        // Delete button
        document.getElementById('ud-delete-btn').onclick = async () => {
            if (!confirm(`"${u.name}" (#${u.id}) foydalanuvchisini o'chirishni xohlaysizmi?`)) return;
            try {
                await apiCall('/api/admin-stats.php', { action: 'delete-user', id: u.id });
                showToast('✅ Foydalanuvchi o\'chirildi');
                closeUserModal();
                loadUsers();
            } catch (err) { showToast('❌ ' + err.message, 'error'); }
        };

        document.getElementById('user-detail-modal').classList.remove('hidden');
    };

    window.closeUserModal = () => {
        document.getElementById('user-detail-modal').classList.add('hidden');
    };

    window.deleteUser = async (id) => {
        if (!confirm(`Foydalanuvchini (#${id}) o'chirishni xohlaysizmi?`)) return;
        try {
            await apiCall('/api/admin-stats.php', { action: 'delete-user', id });
            showToast('✅ Foydalanuvchi o\'chirildi');
            loadUsers();
        } catch (err) { showToast('❌ ' + err.message, 'error'); }
    };
    
    document.getElementById('refresh-users-btn').onclick = loadUsers;

    // ========== LOGS ==========
    async function loadLogs() {
        try {
            const d = await apiCall('/api/admin-stats.php', { action: 'logs' });
            const container = document.getElementById('logs-container');
            if (d.logs.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-500 py-10">Loglar bo\'sh</div>';
                return;
            }
            
            container.innerHTML = d.logs.map(log => {
                if (log.ip) {
                    const statusColor = log.status >= 500 ? 'text-red-400' : (log.status >= 400 ? 'text-orange-400' : 'text-green-400');
                    return `
                        <div class="hover:bg-white/5 p-2 rounded transition-colors border-b border-white/5 last:border-0">
                            <span class="text-gray-500 mr-2">[${log.time}]</span>
                            <span class="font-bold text-indigo-400 w-12 inline-block">${log.method}</span>
                            <span class="${statusColor} font-bold mr-3">${log.status}</span>
                            <span class="text-gray-300 mr-3">${log.uri}</span>
                            <span class="text-gray-600 text-[9px] float-right">${log.ip}</span>
                        </div>`;
                } else {
                    return `<div class="text-gray-500 p-1 border-b border-white/5 last:border-0">${log.raw}</div>`;
                }
            }).join('');
        } catch (err) { showToast('❌ ' + err.message, 'error'); }
    }
    document.getElementById('refresh-logs-btn').onclick = loadLogs;

    // ========== GALLERY ==========
    async function loadGallery(page) {
        galleryPage = page;
        try {
            const d = await apiCall('/api/admin-stats.php', { action: 'gallery', page });
            if (!d.images) d.images = [];
            
            const grid = document.getElementById('gallery-grid');
            const empty = document.getElementById('gallery-empty');
            const pagination = document.getElementById('gallery-pagination');
            
            document.getElementById('gallery-count').textContent = d.total + ' ta rasm';
            
            if (d.images.length === 0) {
                grid.innerHTML = '';
                empty.classList.remove('hidden');
                pagination.innerHTML = '';
                return;
            }
            empty.classList.add('hidden');
            
            grid.innerHTML = d.images.map(img => `
                <div class="group relative cursor-pointer" onclick="openImageModal('${img.url}', '${img.name}')">
                    <div class="aspect-[3/4] rounded-xl overflow-hidden border border-white/5 hover:border-indigo-500/30 transition-all bg-black/20">
                        <img src="${img.url}" alt="${img.name}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                    </div>
                    <div class="absolute inset-0 rounded-xl bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-3">
                        <div class="text-[10px] text-white/90 w-full">
                            <div class="font-bold truncate">${img.name}</div>
                            <div class="flex justify-between mt-1 text-white/50">
                                <span>${img.size}</span>
                                <span>${img.ago}</span>
                            </div>
                        </div>
                    </div>
                </div>`).join('');

            // Pagination
            if (d.pages > 1) {
                let phtml = '';
                for (let i = 1; i <= d.pages; i++) {
                    const active = i === d.currentPage ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white';
                    phtml += `<button onclick="loadGalleryPage(${i})" class="${active} w-8 h-8 rounded-lg text-xs font-bold transition-all">${i}</button>`;
                }
                pagination.innerHTML = phtml;
            } else {
                pagination.innerHTML = '';
            }
        } catch (err) { showToast('❌ ' + err.message, 'error'); }
    }
    window.loadGalleryPage = (p) => loadGallery(p);

    window.openImageModal = (url, name) => {
        document.getElementById('modal-image').src = url;
        document.getElementById('modal-download').href = url;
        document.getElementById('modal-delete').onclick = async () => {
            if (!confirm(`"${name}" rasmini o'chirishni xohlaysizmi?`)) return;
            try {
                await apiCall('/api/admin-stats.php', { action: 'delete-image', name });
                document.getElementById('image-modal').classList.add('hidden');
                showToast('🗑️ Rasm o\'chirildi');
                loadGallery(galleryPage);
                loadDashboard();
            } catch (err) { showToast('❌ ' + err.message, 'error'); }
        };
        document.getElementById('image-modal').classList.remove('hidden');
    };

    document.getElementById('refresh-gallery-btn').onclick = () => loadGallery(1);

    // ========== SETTINGS ==========
    async function loadSystemInfo() {
        try {
            const info = await apiCall('/api/admin-stats.php', { action: 'system-info' });
            const container = document.getElementById('system-info');
            container.innerHTML = Object.entries(info).filter(([k]) => k !== 'extensions').map(([k, v]) =>
                `<div class="flex justify-between py-1.5 border-b border-white/5 last:border-0">
                    <span class="text-gray-500">${k.replace(/_/g, ' ')}</span>
                    <span class="text-white font-mono text-right">${v}</span>
                </div>`
            ).join('');
            
            const extList = document.getElementById('extensions-list');
            extList.innerHTML = Object.entries(info.extensions || {}).map(([name, loaded]) =>
                `<span class="px-3 py-1.5 rounded-lg text-[10px] font-bold ${loaded ? 'bg-green-500/10 text-green-400 border border-green-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20'}">${loaded ? '✅' : '❌'} ${name}</span>`
            ).join('');

            // Update origin display
            document.getElementById('current-origin-display').textContent = window.location.origin;
        } catch (err) { showToast('❌ ' + err.message, 'error'); }
    }

    document.getElementById('cleanup-btn').onclick = async () => {
        const days = document.getElementById('cleanup-days').value;
        if (!confirm(`${days} kundan eski rasmlar o'chiriladi. Davom etilsinmi?`)) return;
        try {
            const r = await apiCall('/api/admin-stats.php', { action: 'cleanup', days: parseInt(days) });
            document.getElementById('cleanup-result').textContent = r.message;
            document.getElementById('cleanup-result').classList.remove('hidden');
            loadDashboard();
            showToast('🧹 ' + r.message);
        } catch (err) { showToast('❌ ' + err.message, 'error'); }
    };

    document.getElementById('clear-all-images-btn').onclick = async () => {
        if (!confirm('BARCHA rasmlar QAYTARILIB BO\'LMAYDIGAN tarzda o\'chiriladi! Davom etilsinmi?')) return;
        if (!confirm('ROSTDAN HAM barcha rasmlarni o\'chirmoqchimisiz?')) return;
        try {
            const r = await apiCall('/api/admin-stats.php', { action: 'cleanup', days: 0 });
            showToast('🗑️ ' + r.message);
            loadDashboard();
        } catch (err) { showToast('❌ ' + err.message, 'error'); }
    };

    document.getElementById('clear-rate-limits-btn').onclick = async () => {
        if (!confirm('Barcha rate limitlar tozalanadi. Davom etilsinmi?')) return;
        try {
            await apiCall('/api/admin-stats.php', { action: 'cleanup', days: 0 });
            showToast('✅ Rate limitlar tozalandi');
        } catch (err) { showToast('❌ ' + err.message, 'error'); }
    };

    // ========== PAYMENT SETTINGS ==========
    async function loadPaymentSettings() {
        try {
            const res = await apiCall('/api/admin-stats.php', { action: 'get-payment-settings' });
            if (res.settings) {
                document.getElementById('settings-card-number').value = res.settings.card_number || '';
                document.getElementById('settings-card-holder').value = res.settings.card_holder || '';
                document.getElementById('settings-admin-chat-id').value = res.settings.admin_chat_id || '';
                document.getElementById('settings-click-enabled').checked = res.settings.click_enabled !== '0';
                document.getElementById('settings-payme-enabled').checked = res.settings.payme_enabled !== '0';
            }
        } catch (err) { console.warn('Payment settings load error:', err); }
    }

    window.savePaymentSettings = async () => {
        const btn = document.getElementById('save-payment-settings-btn');
        const cardNumber = document.getElementById('settings-card-number').value.trim();
        const cardHolder = document.getElementById('settings-card-holder').value.trim();
        const adminChatId = document.getElementById('settings-admin-chat-id').value.trim();

        if (!cardNumber) { showToast('❌ Karta raqamini kiriting', 'error'); return; }

        btn.disabled = true;
        const oldText = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-circle-notch animate-spin mr-1"></i> Saqlanmoqda...';

        try {
            console.log('Saving payment settings...', { cardNumber, cardHolder, adminChatId });
            const res = await apiCall('/api/admin-stats.php', {
                action: 'save-payment-settings',
                card_number: cardNumber,
                card_holder: cardHolder,
                admin_chat_id: adminChatId,
                click_enabled: document.getElementById('settings-click-enabled').checked ? 1 : 0,
                payme_enabled: document.getElementById('settings-payme-enabled').checked ? 1 : 0,
            });
            const status = document.getElementById('payment-settings-status');
            status.classList.remove('hidden');
            setTimeout(() => status.classList.add('hidden'), 3000);
            showToast('✅ ' + res.message);
            // Refresh local settings display
            loadPaymentSettings();
        } catch (err) { 
            console.error('Save error:', err);
            showToast('❌ ' + err.message, 'error'); 
        } finally {
            btn.disabled = false;
            btn.innerHTML = oldText;
        }
    };

    // ========== PACKAGES ==========
    let currentPackages = [];

    async function loadPackages() {
        showLoading(true);
        try {
            const data = await apiCall('/api/admin-packages.php', { action: 'get' });
            currentPackages = data.packages || [];
            renderPackages();
        } catch (err) {
            showToast('❌ ' + err.message, 'error');
        } finally {
            showLoading(false);
        }
    }

    function renderPackages() {
        const container = document.getElementById('packages-list');
        if (!currentPackages.length) {
            container.innerHTML = '<div class="text-center text-gray-500 py-10">Paketlar topilmadi</div>';
            return;
        }

        container.innerHTML = currentPackages.map((pkg, i) => {
            const perCoin = pkg.credits > 0 ? Math.round(pkg.price / pkg.credits) : 0;
            const features = (pkg.features || []).join(', ');
            return `
            <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-5 flex items-center gap-5 hover:bg-white/[0.05] transition-all group ${!pkg.is_active ? 'opacity-50' : ''}">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br ${pkg.gradient} flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid ${pkg.icon} text-xl text-white"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-white">${pkg.name}</span>
                        ${pkg.badge ? `<span class="text-[9px] px-2 py-0.5 rounded-full bg-gradient-to-r ${pkg.badge_gradient || pkg.gradient} text-white font-bold">${pkg.badge}</span>` : ''}
                        ${!pkg.is_active ? '<span class="text-[9px] px-2 py-0.5 rounded-full bg-red-500/20 text-red-400 font-bold">O\'chirilgan</span>' : ''}
                        <span class="text-[10px] text-gray-600 font-mono">${pkg.id}</span>
                    </div>
                    <div class="flex items-center gap-4 mt-1 text-xs text-gray-400">
                        <span><i class="fa-solid fa-coins text-amber-400 text-[10px] mr-1"></i>${pkg.credits} tanga</span>
                        <span><i class="fa-solid fa-tag text-emerald-400 text-[10px] mr-1"></i>${Number(pkg.price).toLocaleString('uz-UZ')} so'm</span>
                        <span class="text-gray-600">1 tanga = ${perCoin.toLocaleString('uz-UZ')} so'm</span>
                    </div>
                    ${features ? `<div class="text-[10px] text-gray-600 mt-1 truncate">${features}</div>` : ''}
                </div>
                <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button onclick="editPackage('${pkg.id}')" class="w-9 h-9 rounded-lg bg-indigo-500/10 hover:bg-indigo-500/20 flex items-center justify-center text-indigo-400 transition-all" title="Tahrirlash">
                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                    </button>
                    <button onclick="deletePackage('${pkg.id}', '${pkg.name}')" class="w-9 h-9 rounded-lg bg-red-500/10 hover:bg-red-500/20 flex items-center justify-center text-red-400 transition-all" title="O'chirish">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                    </button>
                </div>
            </div>`;
        }).join('');
    }

    function showPackageModal(pkg = null) {
        const isEdit = !!pkg;
        document.getElementById('pkg-modal-title').textContent = isEdit ? 'Paketni tahrirlash' : 'Yangi paket qo\'shish';
        document.getElementById('pkg-edit-old-id').value = isEdit ? pkg.id : '';
        document.getElementById('pkg-edit-id').value = pkg?.id || '';
        document.getElementById('pkg-edit-id').disabled = isEdit;
        document.getElementById('pkg-edit-name').value = pkg?.name || '';
        document.getElementById('pkg-edit-credits').value = pkg?.credits || '';
        document.getElementById('pkg-edit-price').value = pkg?.price || '';
        document.getElementById('pkg-edit-icon').value = pkg?.icon || 'fa-coins';
        document.getElementById('pkg-edit-gradient').value = pkg?.gradient || 'from-gray-600 to-gray-500';
        document.getElementById('pkg-edit-badge').value = pkg?.badge || '';
        document.getElementById('pkg-edit-badge-gradient').value = pkg?.badge_gradient || '';
        document.getElementById('pkg-edit-features').value = (pkg?.features || []).join('\n');
        document.getElementById('pkg-edit-active').checked = pkg ? !!pkg.is_active : true;
        document.getElementById('pkg-edit-error').classList.add('hidden');
        updateIconPreview();
        document.getElementById('package-edit-modal').classList.remove('hidden');
    }

    function closePackageModal() {
        document.getElementById('package-edit-modal').classList.add('hidden');
    }

    function editPackage(id) {
        const pkg = currentPackages.find(p => p.id === id);
        if (pkg) showPackageModal(pkg);
    }

    async function deletePackage(id, name) {
        if (!confirm(`"${name}" paketini o'chirishni xohlaysizmi?`)) return;
        try {
            await apiCall('/api/admin-packages.php', { action: 'delete', id });
            showToast('✅ Paket o\'chirildi');
            loadPackages();
        } catch (err) {
            showToast('❌ ' + err.message, 'error');
        }
    }

    async function savePackage() {
        const errEl = document.getElementById('pkg-edit-error');
        errEl.classList.add('hidden');

        const oldId = document.getElementById('pkg-edit-old-id').value;
        const isEdit = !!oldId;

        const id = document.getElementById('pkg-edit-id').value.trim();
        const name = document.getElementById('pkg-edit-name').value.trim();
        const credits = parseInt(document.getElementById('pkg-edit-credits').value) || 0;
        const price = parseInt(document.getElementById('pkg-edit-price').value) || 0;
        const icon = document.getElementById('pkg-edit-icon').value.trim() || 'fa-coins';
        const gradient = document.getElementById('pkg-edit-gradient').value.trim() || 'from-gray-600 to-gray-500';
        const badge = document.getElementById('pkg-edit-badge').value.trim();
        const badgeGradient = document.getElementById('pkg-edit-badge-gradient').value.trim();
        const featuresText = document.getElementById('pkg-edit-features').value.trim();
        const features = featuresText ? featuresText.split('\n').map(f => f.trim()).filter(f => f) : [];
        const is_active = document.getElementById('pkg-edit-active').checked ? 1 : 0;

        if (!id || !name) {
            errEl.textContent = 'ID va nom majburiy';
            errEl.classList.remove('hidden');
            return;
        }
        if (credits <= 0 || price <= 0) {
            errEl.textContent = 'Tangalar va narx 0 dan katta bo\'lishi kerak';
            errEl.classList.remove('hidden');
            return;
        }

        const btn = document.getElementById('pkg-save-btn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Saqlanmoqda...';

        try {
            const data = { id, name, credits, price, icon, gradient, badge, badge_gradient: badgeGradient, features, is_active };
            if (isEdit) {
                await apiCall('/api/admin-packages.php', { action: 'update', id: oldId, data });
            } else {
                await apiCall('/api/admin-packages.php', { action: 'create', data });
            }
            showToast('✅ Paket saqlandi');
            closePackageModal();
            loadPackages();
        } catch (err) {
            errEl.textContent = err.message;
            errEl.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk mr-1"></i> Saqlash';
        }
    }

    function updateIconPreview() {
        const icon = document.getElementById('pkg-edit-icon').value.trim() || 'fa-coins';
        document.getElementById('pkg-icon-preview').innerHTML = `<i class="fa-solid ${icon}"></i>`;
    }
    document.getElementById('pkg-edit-icon')?.addEventListener('input', updateIconPreview);

    // ========== UTILS ==========
    function showLoading(show) {
        document.getElementById('tab-loading').classList.toggle('hidden', !show);
    }

    function showToast(msg, type = 'success') {
        const toast = document.getElementById('status-toast');
        const toastDiv = toast.querySelector('div');
        document.getElementById('toast-msg').innerText = msg;
        toastDiv.className = type === 'error' 
            ? 'bg-red-600 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center gap-3 border border-white/10 backdrop-blur-md'
            : 'bg-indigo-600 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center gap-3 border border-white/10 backdrop-blur-md';
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 4000);
    }
    // ========== EXPOSE TO GLOBAL SCOPE (for onclick handlers) ==========
    window.showPackageModal = showPackageModal;
    window.closePackageModal = closePackageModal;
    window.editPackage = editPackage;
    window.deletePackage = deletePackage;
    window.savePackage = savePackage;

    // ========== INIT: Show admin if session exists ==========
    // Must be at the very end, after ALL function definitions
    if (sessionToken) showAdmin();
});
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
