</main> <!-- /Main Content Wrapper -->

<!-- Overlay for mobile menu -->
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

    window.logoutBlogger = async function() {
        if (!confirm('Haqiqatan ham chiqmoqchimisiz?')) return;
        try {
            const token = localStorage.getItem('blogger_token');
            if (token) {
                await fetch('/api/blogger-auth.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-Blogger-Token': token },
                    body: JSON.stringify({ action: 'logout' })
                });
                localStorage.removeItem('blogger_token');
            }
            window.location.href = '/blogger/login';
        } catch (e) {
            console.error(e);
            localStorage.removeItem('blogger_token');
            window.location.href = '/blogger/login';
        }
    };

    // Check auth on load for protected pages
    document.addEventListener('DOMContentLoaded', async () => {
        const isLoginPage = window.location.pathname.includes('/login');
        const token = localStorage.getItem('blogger_token');

        if (!token && !isLoginPage) {
            window.location.href = '/blogger/login';
            return;
        }

        if (token && !isLoginPage) {
            try {
                const res = await fetch('/api/blogger-auth.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-Blogger-Token': token },
                    body: JSON.stringify({ action: 'check' })
                });
                const data = await res.json();
                if (!data.authenticated) {
                    localStorage.removeItem('blogger_token');
                    window.location.href = '/blogger/login';
                } else {
                    // Update user info in sidebar
                    const container = document.getElementById('sidebar-user-container');
                    const avatar = document.getElementById('sidebar-avatar');
                    const name = document.getElementById('sidebar-name');
                    const phone = document.getElementById('sidebar-phone');
                    
                    if (container) container.style.display = 'block';
                    if (name) name.textContent = data.blogger.display_name;
                    if (phone) phone.textContent = data.blogger.phone;
                    if (avatar) avatar.textContent = data.blogger.display_name.charAt(0).toUpperCase();

                    // Load badge for takliflar
                    loadTakliflarBadge(token);
                }
            } catch (e) {
                console.error('Auth check error:', e);
            }
        }
    });

    async function loadTakliflarBadge(token) {
        // We will fetch unread offers count
        try {
            const res = await fetch('/api/blogger-deals.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-Blogger-Token': token },
                body: JSON.stringify({ action: 'get_pending_count' })
            });
            const data = await res.json();
            if (data.success && data.count > 0) {
                const badge = document.getElementById('nav-badge-takliflar');
                if (badge) {
                    badge.textContent = data.count;
                    badge.classList.remove('hidden');
                }
            }
        } catch (e) {}
    }
</script>
</body>
</html>
