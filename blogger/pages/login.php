<?php
$pageTitle = 'Bloger Panel – Kirish';
require_once __DIR__ . '/../components/blogger-header.php';
?>

<div class="flex-1 flex flex-col items-center justify-center p-4 min-h-[80vh]">
    
    <div class="text-center mb-8 animate-fade-in-up">
        <div class="w-16 h-16 mx-auto bg-gradient-to-br from-orange-500 to-rose-500 rounded-2xl flex items-center justify-center shadow-xl shadow-orange-500/20 mb-4 transform rotate-12">
            <i class="fa-solid fa-camera-retro text-3xl text-white -rotate-12"></i>
        </div>
        <h1 class="text-3xl font-extrabold text-white">Tiba <span class="gradient-text">Blogger</span></h1>
        <p class="text-gray-400 mt-2 text-sm">Blogerlar uchun maxsus boshqaruv paneli</p>
    </div>

    <!-- Auth Card -->
    <div class="glass-card w-full max-w-md p-8 animate-fade-in-up" style="animation-delay: 0.1s;">
        
        <!-- Tabs -->
        <div class="flex bg-white/5 p-1 rounded-xl mb-6">
            <button id="tab-login" class="flex-1 py-2 text-sm font-bold rounded-lg transition-all text-white bg-orange-500/20 shadow-sm" onclick="switchAuthTab('login')">Kirish</button>
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
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Ism-Familiya / Taxallus</label>
                <div class="relative">
                    <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                    <input type="text" id="reg-name" required class="input-field pl-10" placeholder="Ali Valiyev">
                </div>
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Telefon raqam</label>
                <div class="relative">
                    <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                    <input type="tel" id="reg-phone" required class="input-field pl-10" placeholder="+998 90 123 45 67">
                </div>
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Parol o'ylab toping</label>
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                    <input type="password" id="reg-password" required minlength="6" class="input-field pl-10" placeholder="Kamida 6 belgi">
                </div>
            </div>
            <div class="text-[10px] text-gray-500 mt-2 mb-4 leading-relaxed">
                Ro'yxatdan o'tganingizdan so'ng profilingiz <span class="text-orange-400 font-bold">moderatsiyadan</span> o'tadi. Tasdiqlangandan so'ng tizimga kira olasiz.
            </div>
            <button type="submit" class="w-full btn-primary py-3.5 mt-2">
                <span class="btn-text">Ariza qoldirish</span>
                <div class="loader hidden w-5 h-5 border-2 border-white/20 border-t-white mx-auto"></div>
            </button>
        </form>

    </div>
</div>

<script>
    // Redirect if already logged in
    document.addEventListener('DOMContentLoaded', () => {
        if (localStorage.getItem('blogger_token')) {
            window.location.href = '/blogger';
        }
    });

    function switchAuthTab(tab) {
        const err = document.getElementById('auth-error');
        const succ = document.getElementById('auth-success');
        err.classList.add('hidden');
        succ.classList.add('hidden');

        if (tab === 'login') {
            document.getElementById('login-form').classList.remove('hidden');
            document.getElementById('register-form').classList.add('hidden');
            document.getElementById('tab-login').className = 'flex-1 py-2 text-sm font-bold rounded-lg transition-all text-white bg-orange-500/20 shadow-sm';
            document.getElementById('tab-register').className = 'flex-1 py-2 text-sm font-bold rounded-lg transition-all text-gray-500 hover:text-white';
        } else {
            document.getElementById('login-form').classList.add('hidden');
            document.getElementById('register-form').classList.remove('hidden');
            document.getElementById('tab-register').className = 'flex-1 py-2 text-sm font-bold rounded-lg transition-all text-white bg-orange-500/20 shadow-sm';
            document.getElementById('tab-login').className = 'flex-1 py-2 text-sm font-bold rounded-lg transition-all text-gray-500 hover:text-white';
        }
    }

    function showMsg(id, msg) {
        const el = document.getElementById(id);
        el.textContent = msg;
        el.classList.remove('hidden');
    }

    function hideMsgs() {
        document.getElementById('auth-error').classList.add('hidden');
        document.getElementById('auth-success').classList.add('hidden');
    }

    function toggleBtnLoader(formId, loading) {
        const btn = document.querySelector(`#${formId} button[type="submit"]`);
        if (!btn) return;
        const txt = btn.querySelector('.btn-text');
        const ldr = btn.querySelector('.loader');
        if (loading) {
            btn.disabled = true;
            btn.style.opacity = '0.7';
            if(txt) txt.classList.add('hidden');
            if(ldr) ldr.classList.remove('hidden');
        } else {
            btn.disabled = false;
            btn.style.opacity = '1';
            if(txt) txt.classList.remove('hidden');
            if(ldr) ldr.classList.add('hidden');
        }
    }

    document.getElementById('login-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        hideMsgs();
        const phone = document.getElementById('login-phone').value;
        const pass = document.getElementById('login-password').value;
        
        toggleBtnLoader('login-form', true);
        try {
            const res = await fetch('/api/blogger-auth.php', {
                method: 'POST',
                headers: {'Content-Type':'application/json'},
                body: JSON.stringify({ action: 'login', phone: phone, password: pass })
            });
            const data = await res.json();
            if (!res.ok) {
                showMsg('auth-error', data.error || 'Xatolik yuz berdi');
            } else {
                if (data.blogger.status === 'pending') {
                    showMsg('auth-error', 'Profilingiz hali tasdiqlanmagan. Iltimos, kuting.');
                } else {
                    localStorage.setItem('blogger_token', data.token);
                    window.location.href = '/blogger';
                }
            }
        } catch (err) {
            showMsg('auth-error', 'Tarmoq xatosi');
        } finally {
            toggleBtnLoader('login-form', false);
        }
    });

    document.getElementById('register-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        hideMsgs();
        const name = document.getElementById('reg-name').value;
        const phone = document.getElementById('reg-phone').value;
        const pass = document.getElementById('reg-password').value;
        
        toggleBtnLoader('register-form', true);
        try {
            const res = await fetch('/api/blogger-auth.php', {
                method: 'POST',
                headers: {'Content-Type':'application/json'},
                body: JSON.stringify({ action: 'register', name: name, phone: phone, password: pass })
            });
            const data = await res.json();
            if (!res.ok) {
                showMsg('auth-error', data.error || 'Xatolik yuz berdi');
            } else {
                showMsg('auth-success', 'Arizangiz qabul qilindi. Moderatsiyadan so\'ng sizga xabar beramiz.');
                document.getElementById('register-form').reset();
            }
        } catch (err) {
            showMsg('auth-error', 'Tarmoq xatosi');
        } finally {
            toggleBtnLoader('register-form', false);
        }
    });
</script>

<?php require_once __DIR__ . '/../components/blogger-footer.php'; ?>
