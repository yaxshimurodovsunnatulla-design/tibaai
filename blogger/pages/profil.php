<?php
$pageTitle = 'Profil Sozlamalari – Bloger Panel';
require_once __DIR__ . '/../components/blogger-header.php';
?>

<div class="p-4 md:p-8 flex-1 flex flex-col h-full max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-white">Profilim</h1>
        <p class="text-gray-400 text-sm mt-1">Sotuvchilar sizni qanday ko'rishini sozlang.</p>
    </div>

    <div class="glass-card p-6 space-y-8">
        
        <!-- Asosiy -->
        <section>
            <h2 class="text-lg font-bold text-white mb-4 border-b border-white/10 pb-2">Asosiy ma'lumotlar</h2>
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Avatar -->
                <div class="flex flex-col items-center justify-center gap-3">
                    <div class="w-24 h-24 rounded-full bg-white/5 border-2 border-dashed border-white/20 flex flex-col items-center justify-center text-gray-400 hover:text-white hover:border-orange-500/50 cursor-pointer transition-all relative overflow-hidden group">
                        <i class="fa-solid fa-camera text-2xl group-hover:scale-110 transition-transform"></i>
                        <span class="text-[10px] mt-1">Rasm yuklash</span>
                        <input type="file" id="avatar-input" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                        <img id="avatar-preview" src="" class="absolute inset-0 w-full h-full object-cover hidden">
                    </div>
                </div>
                
                <div class="flex-1 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1.5">Ism / Taxallus</label>
                            <input type="text" id="prof-name" class="input-field" placeholder="Mascot / Ali">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1.5">Telefon</label>
                            <input type="text" id="prof-phone" class="input-field bg-white/5" disabled>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1.5">O'zingiz haqingizda qisqacha (Bio)</label>
                            <textarea id="prof-bio" class="input-field resize-none h-20" placeholder="Men life-style va tech yo'nalishida blog yuritaman..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tarmoqlar -->
        <section>
            <h2 class="text-lg font-bold text-white mb-4 border-b border-white/10 pb-2">Ijtimoiy Tarmoqlar</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1.5"><i class="fa-brands fa-instagram text-pink-500 mr-1"></i> Instagram URL</label>
                    <input type="url" id="prof-inst-url" class="input-field" placeholder="https://instagram.com/username">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1.5">Instagram Obunachilar</label>
                    <input type="number" id="prof-inst-fol" class="input-field" placeholder="Masalan: 15000">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1.5"><i class="fa-brands fa-tiktok text-white mr-1"></i> TikTok URL</label>
                    <input type="url" id="prof-tik-url" class="input-field" placeholder="https://tiktok.com/@username">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1.5">TikTok Obunachilar</label>
                    <input type="number" id="prof-tik-fol" class="input-field" placeholder="Masalan: 50000">
                </div>
            </div>
        </section>

        <!-- Xizmat narxlari -->
        <section>
            <h2 class="text-lg font-bold text-white mb-4 border-b border-white/10 pb-2">Reklama Narxlari (So'mda)</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1.5">Story (1x)</label>
                    <input type="number" id="price-story" class="input-field" placeholder="0">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1.5">Reels / TikTok</label>
                    <input type="number" id="price-reels" class="input-field" placeholder="0">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1.5">Post (Lenta)</label>
                    <input type="number" id="price-post" class="input-field" placeholder="0">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1.5">Unboxing / Obzor</label>
                    <input type="number" id="price-unbox" class="input-field" placeholder="0">
                </div>
            </div>

            <div class="mt-6 bg-white/5 p-4 rounded-xl border border-white/10 flex items-start gap-3">
                <input type="checkbox" id="accept-barter" class="mt-1 w-4 h-4 rounded border-gray-600 bg-gray-700 text-orange-500 focus:ring-orange-500">
                <div>
                    <label for="accept-barter" class="text-sm font-bold text-white cursor-pointer">Barterga ishlashga roziman</label>
                    <p class="text-[10px] text-gray-400 mt-1">Sotuvchilar sizga pul o'rniga o'z mahsulotlarini yuborib hamkorlik taklif qilishlari mumkin.</p>
                </div>
            </div>
        </section>

        <div class="pt-4 flex justify-end">
            <button id="save-btn" class="btn-primary w-full sm:w-auto">
                <span class="btn-text">Saqlash</span>
                <div class="loader hidden w-5 h-5 border-2 border-white/20 border-t-white mx-auto"></div>
            </button>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const token = localStorage.getItem('blogger_token');
    if (!token) return;

    // Load data
    try {
        const res = await fetch('/api/blogger-profile.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-Blogger-Token': token},
            body: JSON.stringify({action: 'get'})
        });
        const data = await res.json();
        
        if (data.success && data.blogger) {
            const b = data.blogger;
            document.getElementById('prof-name').value = b.display_name || '';
            document.getElementById('prof-phone').value = b.phone || '';
            document.getElementById('prof-bio').value = b.bio || '';
            document.getElementById('prof-inst-url').value = b.instagram_url || '';
            document.getElementById('prof-inst-fol').value = b.instagram_followers || '';
            document.getElementById('prof-tik-url').value = b.tiktok_url || '';
            document.getElementById('prof-tik-fol').value = b.tiktok_followers || '';
            
            document.getElementById('price-story').value = b.price_story || '';
            document.getElementById('price-reels').value = b.price_reels || '';
            document.getElementById('price-post').value = b.price_post || '';
            document.getElementById('price-unbox').value = b.price_unboxing || '';
            
            document.getElementById('accept-barter').checked = b.accepts_barter == 1;

            if (b.avatar_path) {
                const img = document.getElementById('avatar-preview');
                img.src = b.avatar_path;
                img.classList.remove('hidden');
            }
        }
    } catch(e) {}

    // Save
    document.getElementById('save-btn').addEventListener('click', async () => {
        const btn = document.getElementById('save-btn');
        const txt = btn.querySelector('.btn-text');
        const ldr = btn.querySelector('.loader');

        btn.disabled = true;
        txt.classList.add('hidden');
        ldr.classList.remove('hidden');

        const payload = {
            action: 'update',
            name: document.getElementById('prof-name').value,
            bio: document.getElementById('prof-bio').value,
            inst_url: document.getElementById('prof-inst-url').value,
            inst_fol: document.getElementById('prof-inst-fol').value,
            tik_url: document.getElementById('prof-tik-url').value,
            tik_fol: document.getElementById('prof-tik-fol').value,
            price_story: document.getElementById('price-story').value,
            price_reels: document.getElementById('price-reels').value,
            price_post: document.getElementById('price-post').value,
            price_unbox: document.getElementById('price-unbox').value,
            barter: document.getElementById('accept-barter').checked ? 1 : 0
        };

        try {
            const res = await fetch('/api/blogger-profile.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-Blogger-Token': token},
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if(data.success) {
                alert('Muvaffaqiyatli saqlandi!');
            } else {
                alert(data.error || 'Xatolik');
            }
        } catch(e) { alert('Tarmoq xatosi'); }
        
        btn.disabled = false;
        txt.classList.remove('hidden');
        ldr.classList.add('hidden');
    });

    // Avatar upload
    document.getElementById('avatar-input').addEventListener('change', async (e) => {
        const file = e.target.files[0];
        if(!file) return;

        const fd = new FormData();
        fd.append('action', 'upload_avatar');
        fd.append('image', file);

        try {
            const res = await fetch('/api/blogger-profile.php', {
                method: 'POST',
                headers: {'X-Blogger-Token': token},
                body: fd
            });
            const data = await res.json();
            if(data.success) {
                const img = document.getElementById('avatar-preview');
                img.src = data.url;
                img.classList.remove('hidden');
            }
        } catch(e) {}
    });
});
</script>

<?php require_once __DIR__ . '/../components/blogger-footer.php'; ?>
