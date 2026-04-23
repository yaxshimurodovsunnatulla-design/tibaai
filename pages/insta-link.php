<?php
require_once __DIR__ . '/../api/config.php';
$pageTitle = 'InstaLink AI – Instagram Komment Avtomatizatsiyasi';
$pageDescription = 'Instagram videolaringizga izoh qoldirganlaringizga avtomatik Direct xabar va linklar yuboring. Sotuvlarni avtomatlashtiring.';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.8s ease-out forwards;
    }
</style>

<div class="py-12 sm:py-20 relative overflow-hidden">
    <!-- Background Glows -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-pink-600/10 rounded-full blur-[120px] -z-10"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-orange-600/10 rounded-full blur-[120px] -z-10"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if ($user && !empty($user['insta_account_id'])): ?>
            <!-- STATE: CONNECTED (Builder View) -->
            <div class="flex flex-col lg:flex-row items-center gap-12 mb-20 animate-fade-in">
                <div class="lg:w-1/2 text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-wider mb-6">
                        <i class="fa-solid fa-circle-check"></i> Instagram Ulangan
                    </div>
                    <h1 class="text-4xl sm:text-6xl font-black text-white mb-6 leading-tight">
                        Izohlarni <span class="gradient-text">Xaridorga</span> Aylantiring
                    </h1>
                    <p class="text-gray-400 text-lg mb-8 leading-relaxed">
                        Sizning Instagram hisobingiz muvaffaqiyatli ulangan. Quyida o'z avtomatizatsiya qoidalaringizni boshqarishingiz mumkin.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <button onclick="toggleConnectModal(true)" class="px-6 py-3 rounded-2xl bg-white/5 border border-white/10 text-gray-400 text-sm hover:text-white transition-all">
                            Hisobni almashtirish
                        </button>
                    </div>
                </div>
                <div class="lg:w-1/2 relative">
                    <div class="relative z-10 rounded-[3rem] overflow-hidden border border-white/10 shadow-2xl shadow-pink-500/10">
                        <img src="/instagram_automation_mockup_1773319234671.png" alt="InstaLink AI Mockup" class="w-full">
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- STATE: NOT CONNECTED (Login View - ChatPlace Style) -->
            <div class="min-h-[70vh] flex flex-col items-center justify-center py-20 animate-fade-in">
                <div class="w-full max-w-5xl flex flex-col md:flex-row items-center bg-white/5 border border-white/10 rounded-[3rem] overflow-hidden backdrop-blur-xl">
                    <!-- Left: Illustration -->
                    <div class="w-full md:w-1/2 p-12 lg:p-20 flex flex-col items-center text-center bg-gradient-to-br from-purple-600/10 via-pink-500/10 to-transparent">
                        <div class="w-64 h-64 mb-10 relative">
                            <img src="/chatplace_style_illustrations_1773320672745.png" alt="Instagram Automation" class="w-full h-full object-contain">
                        </div>
                        <h2 class="text-3xl font-black text-white mb-4">Instagramga kiring</h2>
                        <p class="text-gray-400">InstaLink AI uchun barcha ruxsatnomalarni taqdim eting</p>
                    </div>

                    <!-- Right: Actions -->
                    <?php
                        // OAuth URL yaratish
                        $insta_auth_url = "https://www.instagram.com/accounts/login/?force_authentication&platform_app_id=" . INSTA_APP_ID . "&next=" . urlencode("/oauth/authorize/third_party/?redirect_uri=" . urlencode(INSTA_REDIRECT_URI) . "&response_type=code&scope=instagram_business_basic,instagram_business_manage_comments,instagram_business_manage_messages&client_id=" . INSTA_APP_ID);
                    ?>
                    <div class="w-full md:w-1/2 p-12 lg:p-20 bg-white/5">
                        <h3 class="text-2xl font-bold text-white mb-4">Instagram’ga kiring va InstaLink AI uchun ruxsat bering</h3>
                        <p class="text-gray-400 text-sm mb-12">Bu Instagram uchun avtomatlashtirish yaratish imkonini beradi. Siz har doim ma'lumotlaringizni nazorat qilasiz, biz esa sizning ruxsatingizsiz ularni boshqara olmaymiz.</p>
                        
                        <div class="space-y-4">
                            <a href="<?php echo $insta_auth_url; ?>" class="w-full py-4 rounded-xl bg-white text-black font-bold flex items-center justify-center gap-3 hover:bg-gray-100 transition-all shadow-xl">
                                <i class="fa-brands fa-instagram text-xl text-pink-500"></i> Instagram bilan davom etish
                            </a>
                            <div class="text-center">
                                <span class="text-gray-500 text-xs">yoki</span>
                            </div>
                            <a href="<?php echo $insta_auth_url; ?>" class="w-full py-4 rounded-xl bg-transparent border border-white/10 text-white font-semibold flex items-center justify-center gap-3 hover:bg-white/5 transition-all text-sm">
                                <i class="fa-brands fa-facebook text-xl text-blue-500"></i> Facebook orqali ulash
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($user && !empty($user['insta_account_id'])): ?>
            <!-- Automation Builder Section -->
            <div class="mb-20 animate-fade-in">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-white mb-4">Avtomatizatsiya Qoidasi</h2>
                    <p class="text-gray-400">Birinchi qoidangizni hoziroq yarating</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Trigger Side -->
                    <div class="glass-card p-8 border border-white/5 space-y-8">
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center text-indigo-400">
                                    <i class="fa-solid fa-bolt"></i>
                                </div>
                                <h3 class="text-xl font-bold text-white">Trigger</h3>
                            </div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Qaysi so'z yozilganda?</label>
                            <input type="text" id="trigger-word" placeholder="Masalan: ai" value="ai" class="input-field mb-4">
                            <p class="text-xs text-gray-500 italic">Mijoz videoyingizga ushbu so'zni yozishi bilan xabar yuboriladi.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Qaysi postlarda?</label>
                            <select class="input-field">
                                <option>Barcha videolar (Reels)</option>
                                <option>Ma'lum bir video tanlash</option>
                                <option>Oxirgi 5 ta video</option>
                            </select>
                        </div>
                    </div>

                    <!-- Action Side -->
                    <div class="lg:col-span-2 glass-card p-8 border border-white/5">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-pink-500/20 flex items-center justify-center text-pink-400">
                                <i class="fa-solid fa-paper-plane"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white">Xabar (Direct)</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Xabar matni</label>
                                    <textarea id="dm-text" rows="4" class="input-field h-32" placeholder="Salom! Mana siz so'ragan link...">Salom! InstaLink AI haqida ma'lumot so'raganingiz uchun rahmat. 🚀

Pastdagi tugmani bosib batafsil tanishishingiz mumkin:</textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Tugma nomi</label>
                                    <input type="text" id="btn-text" placeholder="Kirish" value="Batafsil ma'lumot" class="input-field mb-4">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Tugma Linki (URL)</label>
                                    <input type="text" id="btn-url" placeholder="https://..." value="https://tiba-ai.uz" class="input-field">
                                </div>
                            </div>

                            <!-- Instagram Preview -->
                            <div class="bg-black/40 rounded-[2.5rem] p-4 border border-white/10 relative overflow-hidden flex flex-col items-center">
                                <div class="w-20 h-1 bg-white/20 rounded-full mb-6"></div>
                                <div class="w-full space-y-4">
                                    <!-- Profile header -->
                                    <div class="flex items-center gap-3 border-b border-white/5 pb-4 mb-4">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-yellow-400 to-purple-600 p-[1.5px]">
                                            <div class="w-full h-full rounded-full bg-black border border-black"></div>
                                        </div>
                                        <span class="text-xs font-bold text-white">tiba_ai</span>
                                    </div>
                                    
                                    <!-- Message Bubble -->
                                    <div class="flex flex-col items-end gap-2 pr-4">
                                        <div class="max-w-[85%] bg-zinc-800 rounded-3xl rounded-tr-lg p-4 text-xs text-white leading-relaxed text-right">
                                            <span id="preview-text">Salom! InstaLink AI haqida ma'lumot so'raganingiz uchun rahmat. 🚀 Pastdagi tugmani bosib batafsil tanishishingiz mumkin:</span>
                                        </div>
                                        <div class="w-[85%] bg-blue-600 py-3 rounded-2xl text-center text-xs font-bold text-white shadow-lg shadow-blue-500/20" id="preview-btn">
                                            Batafsil ma'lumot
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex justify-end">
                            <button onclick="saveRule()" id="save-btn" class="px-8 py-3 rounded-xl bg-white text-black font-bold hover:bg-gray-200 transition-all flex items-center gap-2">
                                <span id="save-btn-text">Qoidani saqlash</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rules List Section -->
            <div class="mb-20 animate-fade-in">
                <h2 class="text-2xl font-bold text-white mb-8">Mening Qoidalarim</h2>
                <div id="rules-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Rules will be loaded here -->
                    <div class="col-span-full py-12 text-center glass-card border-dashed border-white/10">
                        <p class="text-gray-500">Hozircha qoidalar yo'q. Birinchi qoidani yuqorida yarating.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Steps Section -->
        <div id="how-it-works" class="pt-20 border-t border-white/5">
            <h2 class="text-3xl font-bold text-white text-center mb-16">Qanday sozlanadi?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="text-center group">
                    <div class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-2xl text-pink-500 mx-auto mb-6 group-hover:bg-pink-500 group-hover:text-white transition-all">1</div>
                    <h4 class="text-xl font-bold text-white mb-4">Instagramni ulaylang</h4>
                    <p class="text-gray-400 text-sm">Professional (Business) Instagram hisobingizni bizning xavfsiz tizimimizga biriktiring.</p>
                </div>
                <div class="text-center group">
                    <div class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-2xl text-pink-500 mx-auto mb-6 group-hover:bg-pink-500 group-hover:text-white transition-all">2</div>
                    <h4 class="text-xl font-bold text-white mb-4">Shartni belgilang</h4>
                    <p class="text-gray-400 text-sm">Trigger so'zni tanlang (masalan: "kurs", "narxi", "link") va videolaringizni belgilang.</p>
                </div>
                <div class="text-center group">
                    <div class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-2xl text-pink-500 mx-auto mb-6 group-hover:bg-pink-500 group-hover:text-white transition-all">3</div>
                    <h4 class="text-xl font-bold text-white mb-4">Avtomatlashtiring</h4>
                    <p class="text-gray-400 text-sm">Yuboriladigan xabar va tugmani sozlang. Tiba AI qolgan ishni o'zi bajaradi.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Connect Modal -->
<div id="connect-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] flex items-center justify-center hidden">
    <div class="glass-card p-8 border border-white/10 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-white">Instagramni ulash</h3>
            <button onclick="toggleConnectModal(false)" class="text-gray-500 hover:text-white transition-all"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <p class="text-sm text-gray-400 mb-6">Tizimni sinab ko'rish uchun quyidagi ma'lumotlarni to'ldiring. Aslida bu yerda Facebook Login tugmasi bo'ladi.</p>
        
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Instagram Account ID</label>
                <input type="text" id="mock-account-id" placeholder="Masalan: 178414..." class="input-field">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Access Token</label>
                <input type="password" id="mock-token" placeholder="EAAW..." class="input-field">
            </div>
            <button onclick="mockConnect()" class="w-full py-4 rounded-xl bg-white text-black font-bold hover:bg-gray-200 transition-all mt-4">
                Ulash va Saqlash
            </button>
        </div>
    </div>
</div>

<script>
    const dmInput = document.getElementById('dm-text');
    const btnInput = document.getElementById('btn-text');
    const triggerInput = document.getElementById('trigger-word');
    const urlInput = document.getElementById('btn-url');
    const previewText = document.getElementById('preview-text');
    const previewBtn = document.getElementById('preview-btn');
    const saveBtn = document.getElementById('save-btn');
    const saveBtnText = document.getElementById('save-btn-text');

    function updatePreview() {
        previewText.textContent = dmInput.value || 'Xabar matni...';
        previewBtn.textContent = btnInput.value || 'Tugma';
    }

    dmInput.addEventListener('input', updatePreview);
    btnInput.addEventListener('input', updatePreview);

    async function loadRules() {
        try {
            const res = await fetch('/api/insta-rules.php');
            const data = await res.json();
            
            const container = document.getElementById('rules-list');
            if (data.rules && data.rules.length > 0) {
                container.innerHTML = data.rules.map(rule => `
                    <div class="glass-card p-6 border border-white/5 relative group">
                        <div class="absolute top-4 right-4 flex gap-2">
                             <button onclick="toggleRule(${rule.id}, ${rule.is_active ? 0 : 1})" class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-xs hover:bg-white/10 transition-all ${rule.is_active ? 'text-emerald-400' : 'text-gray-500'}">
                                <i class="fa-solid fa-power-off"></i>
                            </button>
                            <button onclick="deleteRule(${rule.id})" class="w-8 h-8 rounded-lg bg-red-500/10 text-red-400 flex items-center justify-center text-xs hover:bg-red-500/20 transition-all">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-xs font-bold text-gray-500 uppercase">Trigger:</span>
                            <span class="px-2 py-0.5 rounded bg-pink-500/10 text-pink-400 text-xs font-bold font-mono">"${rule.trigger_word}"</span>
                        </div>
                        <p class="text-sm text-gray-300 line-clamp-2 mb-4">
                            ${rule.dm_text}
                        </p>
                        <div class="mt-auto pt-4 border-t border-white/5 flex justify-between items-center">
                            <span class="text-[10px] text-gray-600">${new Date(rule.created_at).toLocaleDateString()}</span>
                            ${rule.button_text ? `<span class="text-xs text-blue-400 font-bold">${rule.button_text}</span>` : ''}
                        </div>
                    </div>
                `).join('');
            } else {
                container.innerHTML = `
                    <div class="col-span-full py-12 text-center glass-card border-dashed border-white/10">
                        <p class="text-gray-500">Hozircha qoidalar yo'q. Birinchi qoidani yuqorida yarating.</p>
                    </div>
                `;
            }
        } catch (e) {
            console.error('Rules load error:', e);
        }
    }

    async function saveRule() {
        const trigger = triggerInput.value.trim();
        const dmText = dmInput.value.trim();
        const btnText = btnInput.value.trim();
        const btnUrl = urlInput.value.trim();

        if (!trigger || !dmText) {
            alert('Trigger va xabar matni bo\'sh bo\'lishi mumkin emas');
            return;
        }

        saveBtn.disabled = true;
        saveBtnText.textContent = 'Saqlanmoqda...';

        try {
            const res = await fetch('/api/insta-rules.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'save',
                    trigger_word: trigger,
                    dm_text: dmText,
                    button_text: btnText,
                    button_url: btnUrl
                })
            });
            const data = await res.json();
            if (data.success) {
                // Clear fields
                triggerInput.value = '';
                dmInput.value = '';
                btnInput.value = '';
                urlInput.value = '';
                updatePreview();
                loadRules();
            } else {
                alert(data.error || 'Xatolik yuz berdi');
            }
        } catch (e) {
            alert('Server bilan aloqa uzildi');
        } finally {
            saveBtn.disabled = false;
            saveBtnText.textContent = 'Qoidani saqlash';
        }
    }

    async function deleteRule(id) {
        if (!confirm('Ushbu qoidani o\'chirib tashlamoqchimisiz?')) return;
        try {
            await fetch('/api/insta-rules.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'delete', id })
            });
            loadRules();
        } catch (e) {}
    }

    async function toggleRule(id, status) {
        try {
            await fetch('/api/insta-rules.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'toggle', id, status })
            });
            loadRules();
        } catch (e) {}
    }

    function toggleConnectModal(show) {
        document.getElementById('connect-modal').classList.toggle('hidden', !show);
    }

    async function mockConnect() {
        const accountId = document.getElementById('mock-account-id').value.trim();
        const token = document.getElementById('mock-token').value.trim();

        if (!accountId || !token) {
            alert('Iltimos ma\'lumotlarni kiriting');
            return;
        }

        try {
            const res = await fetch('/api/insta-rules.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'connect',
                    account_id: accountId,
                    access_token: token
                })
            });
            const data = await res.json();
            if (data.success) {
                location.reload();
            } else {
                alert(data.error || 'Xatolik');
            }
        } catch (e) {
            alert('Xatolik yuz berdi');
        }
    }

    // Initial load
    loadRules();
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
