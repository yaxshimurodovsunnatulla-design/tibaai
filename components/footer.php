</main>

<!-- Footer -->
<footer class="border-t border-white/10 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Brand -->
            <div>
                <a href="/" class="flex items-center gap-2.5 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <span class="text-white font-extrabold text-xs">T</span>
                    </div>
                    <span class="text-lg font-bold gradient-text">Tiba AI</span>
                </a>
                <p class="text-sm text-gray-500 leading-relaxed">
                    <?= t('footer.desc') ?>
                </p>
            </div>

            <!-- Links -->
            <div>
                <h4 class="text-sm font-semibold text-white mb-4"><?= t('footer.pages') ?></h4>
                <ul class="space-y-2.5">
                    <li><a href="/" class="text-sm text-gray-500 hover:text-indigo-400 transition-colors"><?= t('nav.home') ?></a></li>
                    <li><a href="/create" class="text-sm text-gray-500 hover:text-indigo-400 transition-colors"><?= t('footer.create') ?></a></li>
                    <li><a href="/pricing" class="text-sm text-gray-500 hover:text-indigo-400 transition-colors"><?= t('nav.pricing') ?></a></li>
                </ul>
            </div>

            <!-- Social -->
            <div>
                <h4 class="text-sm font-semibold text-white mb-4"><?= t('footer.contact_title') ?></h4>
                <div class="space-y-2.5">
                    <a href="https://t.me/tibaaibot" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-400 transition-colors">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
                        </svg>
                        Telegram
                    </a>
                    <a href="mailto:support@tibaai.uz" class="flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        support@tibaai.uz
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-10 pt-6 border-t border-white/5 text-center">
            <p class="text-xs text-gray-600">
                &copy; <?= date('Y') ?> Tiba AI. <?= t('footer.rights') ?>
            </p>
        </div>
    </div>
</footer>

</div><!-- /content-wrapper -->


<!-- ========== FLOATING CONTACT BUTTON ========== -->
<div id="floating-contact" class="fixed bottom-6 right-6 z-[90]" style="pointer-events:auto;">
    <!-- Expanded Panel -->
    <div id="contact-panel" class="hidden absolute bottom-[72px] right-0 w-[280px] animate-fade-in-up" style="pointer-events:auto;">
        <div class="rounded-2xl border shadow-2xl overflow-hidden" style="background:var(--sidebar-bg, rgba(15,15,25,0.95)); border-color:var(--border-color, rgba(255,255,255,0.08)); backdrop-filter:blur(24px);">
            <!-- Header -->
            <div class="px-5 pt-5 pb-3">
                <div class="flex items-center gap-2.5 mb-1">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center shadow-lg shadow-cyan-500/20">
                        <i class="fa-solid fa-headset text-white text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold" style="color:var(--text-heading, #fff);"><?= t('contact.title') ?></h4>
                        <p class="text-[10px]" style="color:var(--text-muted, #6b7280);"><?= t('contact.response') ?></p>
                    </div>
                </div>
            </div>
            <!-- Links -->
            <div class="px-3 pb-3 space-y-1.5">
                <a href="https://t.me/tibaaibot" target="_blank" rel="noopener noreferrer"
                   class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 group"
                   style="background:rgba(255,255,255,0.03);"
                   onmouseover="this.style.background='rgba(59,130,246,0.1)'" onmouseout="this.style.background='rgba(255,255,255,0.03)'">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center shadow-md group-hover:scale-110 transition-transform flex-shrink-0">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold" style="color:var(--text-heading, #fff);">Telegram</div>
                        <div class="text-[11px]" style="color:var(--text-muted, #6b7280);">@tibaaibot</div>
                    </div>
                    <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform" style="color:var(--text-muted, #6b7280);"></i>
                </a>
                <a href="mailto:support@tibaai.uz"
                   class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 group"
                   style="background:rgba(255,255,255,0.03);"
                   onmouseover="this.style.background='rgba(139,92,246,0.1)'" onmouseout="this.style.background='rgba(255,255,255,0.03)'">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-purple-500 flex items-center justify-center shadow-md group-hover:scale-110 transition-transform flex-shrink-0">
                        <i class="fa-solid fa-envelope text-white text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold" style="color:var(--text-heading, #fff);">Email</div>
                        <div class="text-[11px]" style="color:var(--text-muted, #6b7280);">support@tibaai.uz</div>
                    </div>
                    <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform" style="color:var(--text-muted, #6b7280);"></i>
                </a>
            </div>
            <!-- Footer hint -->
            <div class="px-5 py-2.5 text-center" style="border-top:1px solid var(--border-color, rgba(255,255,255,0.06));">
                <span class="text-[10px]" style="color:var(--text-muted, #6b7280);"><?= t('contact.hours') ?></span>
            </div>
        </div>
    </div>

    <!-- Main FAB Button -->
    <button id="contact-fab" onclick="toggleContactPanel()" aria-label="Contact"
        class="relative w-14 h-14 rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center shadow-xl shadow-cyan-500/30 hover:shadow-cyan-500/50 hover:scale-110 active:scale-95 transition-all duration-300 group">
        <!-- Pulse rings -->
        <span class="absolute inset-0 rounded-2xl bg-cyan-500/20 animate-[fab-ping_2s_ease-out_infinite]"></span>
        <span class="absolute inset-0 rounded-2xl bg-cyan-500/10 animate-[fab-ping_2s_ease-out_infinite_0.5s]"></span>
        <!-- Icons -->
        <i id="fab-icon-chat" class="fa-solid fa-headset text-white text-xl transition-all duration-300"></i>
        <i id="fab-icon-close" class="fa-solid fa-xmark text-white text-xl absolute opacity-0 scale-50 transition-all duration-300"></i>
        <!-- Notification dot -->
        <span id="fab-dot" class="absolute -top-0.5 -right-0.5 w-3.5 h-3.5 bg-emerald-400 rounded-full border-2 shadow-lg shadow-emerald-400/50 animate-pulse" style="border-color:var(--sidebar-bg, #0a0a0f);"></span>
    </button>
</div>

<style>
@keyframes fab-ping {
    0% { transform: scale(1); opacity: 0.6; }
    100% { transform: scale(1.8); opacity: 0; }
}
#contact-panel.animate-fade-in-up {
    animation: contactSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
#contact-panel.animate-fade-out-down {
    animation: contactSlideDown 0.2s cubic-bezier(0.55, 0, 1, 0.45) forwards;
}
@keyframes contactSlideUp {
    from { opacity: 0; transform: translateY(12px) scale(0.95); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes contactSlideDown {
    from { opacity: 1; transform: translateY(0) scale(1); }
    to   { opacity: 0; transform: translateY(12px) scale(0.95); }
}
/* Hide FAB on /contact page */
body.page-contact #floating-contact { display: none; }
</style>

<script>
let contactOpen = false;
function toggleContactPanel() {
    const panel = document.getElementById('contact-panel');
    const iconChat = document.getElementById('fab-icon-chat');
    const iconClose = document.getElementById('fab-icon-close');
    const dot = document.getElementById('fab-dot');

    contactOpen = !contactOpen;

    if (contactOpen) {
        panel.classList.remove('hidden', 'animate-fade-out-down');
        panel.classList.add('animate-fade-in-up');
        iconChat.style.opacity = '0';
        iconChat.style.transform = 'scale(0.5) rotate(90deg)';
        iconClose.style.opacity = '1';
        iconClose.style.transform = 'scale(1) rotate(0deg)';
        if (dot) dot.style.display = 'none';
    } else {
        panel.classList.remove('animate-fade-in-up');
        panel.classList.add('animate-fade-out-down');
        iconChat.style.opacity = '1';
        iconChat.style.transform = 'scale(1) rotate(0deg)';
        iconClose.style.opacity = '0';
        iconClose.style.transform = 'scale(0.5) rotate(-90deg)';
        setTimeout(() => { panel.classList.add('hidden'); }, 200);
    }
}
// Close on outside click
document.addEventListener('click', function(e) {
    const fc = document.getElementById('floating-contact');
    if (contactOpen && fc && !fc.contains(e.target)) {
        toggleContactPanel();
    }
});
// Close on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && contactOpen) toggleContactPanel();
});
</script>

</body>
</html>
