</main> <!-- /Main Content Wrapper -->

<script>
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileCloseBtn = document.getElementById('mobile-close-btn');

    if (mobileMenuBtn && mobileMenu && mobileCloseBtn) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
            mobileMenu.classList.add('flex');
            document.body.style.overflow = 'hidden';
        });
        mobileCloseBtn.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            mobileMenu.classList.remove('flex');
            document.body.style.overflow = 'auto';
        });
    }

    window.logoutOptom = async function() {
        if (!confirm('Haqiqatan ham chiqmoqchimisiz?')) return;
        try {
            const token = localStorage.getItem('optom_token');
            if (token) {
                await fetch('/api/optom-auth.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-Optom-Token': token },
                    body: JSON.stringify({ action: 'logout' })
                });
                localStorage.removeItem('optom_token');
            }
            window.location.href = '/optom/login';
        } catch (e) {
            console.error(e);
            localStorage.removeItem('optom_token');
            window.location.href = '/optom/login';
        }
    };

    // Auth tekshiruvi
    document.addEventListener('DOMContentLoaded', async () => {
        const isLoginPage = window.location.pathname.includes('/login');
        const token = localStorage.getItem('optom_token');

        if (!token && !isLoginPage) {
            window.location.href = '/optom/login';
            return;
        }

        if (token && !isLoginPage) {
            try {
                const res = await fetch('/api/optom-auth.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-Optom-Token': token },
                    body: JSON.stringify({ action: 'check' })
                });
                const data = await res.json();
                if (!data.authenticated) {
                    localStorage.removeItem('optom_token');
                    window.location.href = '/optom/login';
                } else {
                    const container = document.getElementById('sidebar-user-container');
                    const avatar = document.getElementById('sidebar-avatar');
                    const name = document.getElementById('sidebar-name');
                    const phone = document.getElementById('sidebar-phone');
                    
                    if (container) container.style.display = 'block';
                    if (name) name.textContent = data.seller.company_name;
                    if (phone) phone.textContent = data.seller.phone;
                    if (avatar) avatar.textContent = data.seller.company_name.charAt(0).toUpperCase();

                    // Buyurtmalar badge
                    if (data.stats && data.stats.new_orders > 0) {
                        const badge = document.getElementById('nav-badge-orders');
                        if (badge) {
                            badge.textContent = data.stats.new_orders;
                            badge.classList.remove('hidden');
                        }
                    }

                    // Pending status xabari
                    if (data.seller.status === 'pending') {
                        const main = document.querySelector('main');
                        if (main) {
                            const banner = document.createElement('div');
                            banner.className = 'mx-4 mt-4 p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center gap-3';
                            banner.innerHTML = '<i class="fa-solid fa-clock text-amber-400 text-xl"></i><div><div class="text-sm font-bold text-amber-400">Arizangiz ko\'rib chiqilmoqda</div><div class="text-xs text-gray-400">Admin tasdiqlashini kuting. Shu vaqt ichida profilingizni to\'ldiring.</div></div>';
                            main.prepend(banner);
                        }
                    }
                }
            } catch (e) {
                console.error('Auth check error:', e);
            }
        }
    });

    // Helper: Optom API call
    window.optomAPI = async function(action, data = {}) {
        const token = localStorage.getItem('optom_token');
        const res = await fetch('/api/optom-api.php', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                ...(token ? { 'X-Optom-Token': token } : {})
            },
            body: JSON.stringify({ action, ...data })
        });
        return res.json();
    };

    // Format number
    window.formatNum = function(n) {
        return new Intl.NumberFormat('uz-UZ').format(n);
    };

    // Toast
    window.showToast = function(msg, type = 'info') {
        const colors = { success: 'bg-emerald-500/20 border-emerald-500/30 text-emerald-400', error: 'bg-red-500/20 border-red-500/30 text-red-400', info: 'bg-teal-500/20 border-teal-500/30 text-teal-400' };
        const icons = { success: 'fa-circle-check', error: 'fa-circle-exclamation', info: 'fa-circle-info' };
        const toast = document.createElement('div');
        toast.className = `fixed bottom-6 left-1/2 -translate-x-1/2 z-[200] px-5 py-3 rounded-2xl border ${colors[type] || colors.info} flex items-center gap-2 text-sm font-medium shadow-2xl animate-fade-in-up`;
        toast.innerHTML = `<i class="fa-solid ${icons[type] || icons.info}"></i> ${msg}`;
        document.body.appendChild(toast);
        setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s'; setTimeout(() => toast.remove(), 300); }, 3000);
    };
</script>
</body>
</html>
