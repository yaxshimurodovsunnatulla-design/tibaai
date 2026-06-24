<?php
$pageTitle = 'Tiba Optom – Kirish';
require_once __DIR__ . '/../components/optom-header.php';
?>

<div class="flex-1 flex flex-col items-center justify-center p-4 min-h-[80vh]">
    
    <div class="text-center mb-8 animate-fade-in-up">
        <div class="w-16 h-16 mx-auto bg-gradient-to-br from-teal-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-xl shadow-teal-500/20 mb-4 transform rotate-12">
            <i class="fa-solid fa-boxes-stacked text-3xl text-white -rotate-12"></i>
        </div>
        <h1 class="text-3xl font-extrabold text-white">Tiba <span class="gradient-text">Optom</span></h1>
        <p class="text-gray-400 mt-2 text-sm">Ulgurji sotuvchilar uchun boshqaruv paneli</p>
    </div>

    <!-- Auth Card -->
    <div class="glass-card w-full max-w-md p-8 animate-fade-in-up" style="animation-delay: 0.1s;">
        
        <!-- Tabs -->
        <div class="flex bg-white/5 p-1 rounded-xl mb-6">
            <button id="tab-login" class="flex-1 py-2 text-sm font-bold rounded-lg transition-all text-white bg-teal-500/20 shadow-sm" onclick="switchAuthTab('login')">Kirish</button>
            <button id="tab-register" class="flex-1 py-2 text-sm font-bold rounded-lg transition-all text-gray-500 hover:text-white" onclick="switchAuthTab('register')">Ro'yxatdan o'tish</button>
        </div>

        <div id="auth-error" class="hidden mb-4 p-3 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-xs text-center font-medium"></div>
        <div id="auth-success" class="hidden mb-4 p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs text-center font-medium"></div>

        <!-- Login Form -->
        <form id="login-form" class="space-y-4">
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Telefon raqam</label>
                <div class="relative">
                    <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                    <input type="tel" id="login-phone" required class="input-field pl-10" placeholder="+998 90 123 45 67">
                </div>
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Parol</label>
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                    <input type="password" id="login-password" required class="input-field pl-10" placeholder="••••••••">
                </div>
            </div>
            <button type="submit" class="w-full btn-primary py-3.5 mt-2">
                <span class="btn-text">Tizimga kirish</span>
                <div class="loader hidden w-5 h-5 border-2 border-white/20 border-t-white mx-auto"></div>
            </button>
        </form>

        <!-- Register Form -->
        <form id="register-form" class="space-y-4 hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Kompaniya nomi *</label>
                    <div class="relative">
                        <i class="fa-solid fa-building absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                        <input type="text" id="reg-company" required class="input-field pl-10" placeholder="Mega Textile">
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Egasi ismi *</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                        <input type="text" id="reg-owner" required class="input-field pl-10" placeholder="Ali Valiyev">
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Telefon raqam *</label>
                    <div class="relative">
                        <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                        <input type="tel" id="reg-phone" required class="input-field pl-10" placeholder="+998 90 123 45 67">
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Email</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                        <input type="email" id="reg-email" class="input-field pl-10" placeholder="info@company.uz">
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">INN / STIR</label>
                    <div class="relative">
                        <i class="fa-solid fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                        <input type="text" id="reg-inn" class="input-field pl-10" placeholder="123456789">
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Shahar *</label>
                    <select id="reg-city" class="select-field">
                        <option value="Toshkent">Toshkent</option>
                        <option value="Samarqand">Samarqand</option>
                        <option value="Buxoro">Buxoro</option>
                        <option value="Andijon">Andijon</option>
                        <option value="Namangan">Namangan</option>
                        <option value="Farg'ona">Farg'ona</option>
                        <option value="Qashqadaryo">Qashqadaryo</option>
                        <option value="Surxondaryo">Surxondaryo</option>
                        <option value="Xorazm">Xorazm</option>
                        <option value="Navoiy">Navoiy</option>
                        <option value="Jizzax">Jizzax</option>
                        <option value="Sirdaryo">Sirdaryo</option>
                        <option value="Qoraqalpog'iston">Qoraqalpog'iston</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Kategoriyalar</label>
                <div class="flex flex-wrap gap-2" id="cat-chips">
                    <?php
                    $categories = ['Kiyimlar', 'Elektronika', 'Oziq-ovqat', 'Kosmetika', 'Uy jihozlari', 'Qurilish', 'Avtomobil', 'Bolalar', 'Sport', 'Maishiy texnika', 'Aksessuarlar', 'Boshqa'];
                    foreach ($categories as $cat): ?>
                    <button type="button" onclick="toggleCat(this)" data-cat="<?= $cat ?>"
                        class="px-3 py-1.5 rounded-full text-xs font-semibold border border-white/10 text-gray-400 hover:border-teal-500/50 hover:text-teal-400 transition-all">
                        <?= $cat ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Parol *</label>
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                    <input type="password" id="reg-password" required minlength="6" class="input-field pl-10" placeholder="Kamida 6 belgi">
                </div>
            </div>
            <div class="text-[10px] text-gray-500 mt-2 mb-4 leading-relaxed">
                Ro'yxatdan o'tganingizdan so'ng arizangiz <span class="text-teal-400 font-bold">moderatsiyadan</span> o'tadi. Tasdiqlangandan so'ng tizimga kira olasiz.
            </div>
            <button type="submit" class="w-full btn-primary py-3.5 mt-2">
                <span class="btn-text">Ariza qoldirish</span>
                <div class="loader hidden w-5 h-5 border-2 border-white/20 border-t-white mx-auto"></div>
            </button>
        </form>

    </div>

    <!-- Asosiy saytga qaytish -->
    <a href="/optom" class="mt-6 text-xs text-gray-500 hover:text-teal-400 transition-colors flex items-center gap-1.5">
        <i class="fa-solid fa-arrow-left text-[10px]"></i> Ulgurji katalogga qaytish
    </a>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (localStorage.getItem('optom_token')) {
            window.location.href = '/optom/dashboard';
        }
    });

    let selectedCats = [];

    function toggleCat(btn) {
        const cat = btn.dataset.cat;
        if (selectedCats.includes(cat)) {
            selectedCats = selectedCats.filter(c => c !== cat);
            btn.classList.remove('bg-teal-500/20', 'border-teal-500/50', 'text-teal-400');
            btn.classList.add('border-white/10', 'text-gray-400');
        } else {
            selectedCats.push(cat);
            btn.classList.add('bg-teal-500/20', 'border-teal-500/50', 'text-teal-400');
            btn.classList.remove('border-white/10', 'text-gray-400');
        }
    }

    function switchAuthTab(tab) {
        document.getElementById('auth-error').classList.add('hidden');
        document.getElementById('auth-success').classList.add('hidden');

        if (tab === 'login') {
            document.getElementById('login-form').classList.remove('hidden');
            document.getElementById('register-form').classList.add('hidden');
            document.getElementById('tab-login').className = 'flex-1 py-2 text-sm font-bold rounded-lg transition-all text-white bg-teal-500/20 shadow-sm';
            document.getElementById('tab-register').className = 'flex-1 py-2 text-sm font-bold rounded-lg transition-all text-gray-500 hover:text-white';
        } else {
            document.getElementById('login-form').classList.add('hidden');
            document.getElementById('register-form').classList.remove('hidden');
            document.getElementById('tab-register').className = 'flex-1 py-2 text-sm font-bold rounded-lg transition-all text-white bg-teal-500/20 shadow-sm';
            document.getElementById('tab-login').className = 'flex-1 py-2 text-sm font-bold rounded-lg transition-all text-gray-500 hover:text-white';
        }
    }

    function showMsg(id, msg) {
        const el = document.getElementById(id);
        el.textContent = msg;
        el.classList.remove('hidden');
    }

    function toggleBtnLoader(formId, loading) {
        const btn = document.querySelector(`#${formId} button[type="submit"]`);
        if (!btn) return;
        const txt = btn.querySelector('.btn-text');
        const ldr = btn.querySelector('.loader');
        if (loading) {
            btn.disabled = true; btn.style.opacity = '0.7';
            if(txt) txt.classList.add('hidden');
            if(ldr) ldr.classList.remove('hidden');
        } else {
            btn.disabled = false; btn.style.opacity = '1';
            if(txt) txt.classList.remove('hidden');
            if(ldr) ldr.classList.add('hidden');
        }
    }

    // Login
    document.getElementById('login-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        document.getElementById('auth-error').classList.add('hidden');
        toggleBtnLoader('login-form', true);
        try {
            const res = await fetch('/api/optom-auth.php', {
                method: 'POST',
                headers: {'Content-Type':'application/json'},
                body: JSON.stringify({ action: 'login', phone: document.getElementById('login-phone').value, password: document.getElementById('login-password').value })
            });
            const data = await res.json();
            if (!res.ok || data.error) {
                showMsg('auth-error', data.error || 'Xatolik yuz berdi');
            } else {
                if (data.seller.status === 'pending') {
                    showMsg('auth-error', 'Arizangiz hali tasdiqlanmagan. Iltimos, kuting.');
                } else {
                    localStorage.setItem('optom_token', data.token);
                    window.location.href = '/optom/dashboard';
                }
            }
        } catch (err) {
            showMsg('auth-error', 'Tarmoq xatosi');
        } finally {
            toggleBtnLoader('login-form', false);
        }
    });

    // Register
    document.getElementById('register-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        document.getElementById('auth-error').classList.add('hidden');
        document.getElementById('auth-success').classList.add('hidden');
        toggleBtnLoader('register-form', true);
        try {
            const res = await fetch('/api/optom-auth.php', {
                method: 'POST',
                headers: {'Content-Type':'application/json'},
                body: JSON.stringify({
                    action: 'register',
                    company_name: document.getElementById('reg-company').value,
                    owner_name: document.getElementById('reg-owner').value,
                    phone: document.getElementById('reg-phone').value,
                    email: document.getElementById('reg-email').value,
                    password: document.getElementById('reg-password').value,
                    inn: document.getElementById('reg-inn').value,
                    city: document.getElementById('reg-city').value,
                    categories: selectedCats
                })
            });
            const data = await res.json();
            if (!res.ok || data.error) {
                showMsg('auth-error', data.error || 'Xatolik yuz berdi');
            } else {
                showMsg('auth-success', 'Arizangiz qabul qilindi! Moderatsiyadan so\'ng tizimga kira olasiz.');
                document.getElementById('register-form').reset();
                selectedCats = [];
                document.querySelectorAll('#cat-chips button').forEach(b => {
                    b.classList.remove('bg-teal-500/20', 'border-teal-500/50', 'text-teal-400');
                    b.classList.add('border-white/10', 'text-gray-400');
                });
            }
        } catch (err) {
            showMsg('auth-error', 'Tarmoq xatosi');
        } finally {
            toggleBtnLoader('register-form', false);
        }
    });
</script>

<?php require_once __DIR__ . '/../components/optom-footer.php'; ?>
