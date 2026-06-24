<?php
$pageTitle = 'Tiba Optom – Profil';
require_once __DIR__ . '/../components/optom-header.php';
?>

<div class="p-4 md:p-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-white">Kompaniya Profili</h1>
        <p class="text-gray-400 text-sm mt-1">Kompaniya ma'lumotlaringizni tahrirlang</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="glass-card p-6 text-center">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center text-white text-3xl font-black mx-auto mb-4 shadow-xl shadow-teal-500/20" id="profile-avatar">O</div>
                <h3 class="text-lg font-extrabold text-white" id="profile-company">Yuklanmoqda...</h3>
                <p class="text-sm text-gray-400 mt-1" id="profile-owner">...</p>
                <div class="flex items-center justify-center gap-2 mt-3">
                    <span class="badge badge-teal" id="profile-status">—</span>
                    <span class="badge badge-amber" id="profile-verified" style="display:none"><i class="fa-solid fa-check text-[8px] mr-0.5"></i> Tasdiqlangan</span>
                </div>
                <div class="mt-5 space-y-2 text-left">
                    <div class="flex items-center gap-2 text-xs text-gray-400">
                        <i class="fa-solid fa-phone w-4 text-center"></i> <span id="profile-phone">...</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-400">
                        <i class="fa-solid fa-envelope w-4 text-center"></i> <span id="profile-email">...</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-400">
                        <i class="fa-solid fa-location-dot w-4 text-center"></i> <span id="profile-city">...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="lg:col-span-2">
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-white mb-6">Ma'lumotlarni tahrirlash</h3>
                <form id="profile-form" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Kompaniya nomi</label>
                            <input type="text" id="edit-company" class="input-field" placeholder="Kompaniya nomi">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Egasi ismi</label>
                            <input type="text" id="edit-owner" class="input-field" placeholder="Egasi ismi">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Email</label>
                            <input type="email" id="edit-email" class="input-field" placeholder="info@company.uz">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">INN / STIR</label>
                            <input type="text" id="edit-inn" class="input-field" placeholder="123456789">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Shahar</label>
                            <select id="edit-city" class="select-field">
                                <option value="Toshkent">Toshkent</option><option value="Samarqand">Samarqand</option>
                                <option value="Buxoro">Buxoro</option><option value="Andijon">Andijon</option>
                                <option value="Namangan">Namangan</option><option value="Farg'ona">Farg'ona</option>
                                <option value="Qashqadaryo">Qashqadaryo</option><option value="Surxondaryo">Surxondaryo</option>
                                <option value="Xorazm">Xorazm</option><option value="Navoiy">Navoiy</option>
                                <option value="Jizzax">Jizzax</option><option value="Sirdaryo">Sirdaryo</option>
                                <option value="Qoraqalpog'iston">Qoraqalpog'iston</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Min. buyurtma (so'm)</label>
                            <input type="number" id="edit-min-order" class="input-field" placeholder="100000" min="0">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Manzil</label>
                        <input type="text" id="edit-address" class="input-field" placeholder="Toshkent sh., Chilonzor tumani...">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Kompaniya tavsifi</label>
                        <textarea id="edit-description" class="textarea-field" rows="4" placeholder="Kompaniyangiz haqida..."></textarea>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Kategoriyalar</label>
                        <div class="flex flex-wrap gap-2" id="edit-cats">
                            <?php
                            $categories = ['Kiyimlar', 'Elektronika', 'Oziq-ovqat', 'Kosmetika', 'Uy jihozlari', 'Qurilish', 'Avtomobil', 'Bolalar', 'Sport', 'Maishiy texnika', 'Aksessuarlar', 'Boshqa'];
                            foreach ($categories as $cat): ?>
                            <button type="button" onclick="toggleEditCat(this)" data-cat="<?= $cat ?>"
                                class="px-3 py-1.5 rounded-full text-xs font-semibold border border-white/10 text-gray-400 hover:border-teal-500/50 hover:text-teal-400 transition-all cat-chip">
                                <?= $cat ?>
                            </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary py-3 px-6">
                        <i class="fa-solid fa-check mr-2"></i>
                        <span class="btn-text">Saqlash</span>
                        <div class="loader hidden w-5 h-5 border-2 border-white/20 border-t-white"></div>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let editCats = [];

function toggleEditCat(btn) {
    const cat = btn.dataset.cat;
    if (editCats.includes(cat)) {
        editCats = editCats.filter(c => c !== cat);
        btn.classList.remove('bg-teal-500/20', 'border-teal-500/50', 'text-teal-400');
        btn.classList.add('border-white/10', 'text-gray-400');
    } else {
        editCats.push(cat);
        btn.classList.add('bg-teal-500/20', 'border-teal-500/50', 'text-teal-400');
        btn.classList.remove('border-white/10', 'text-gray-400');
    }
}

document.addEventListener('DOMContentLoaded', async () => {
    const token = localStorage.getItem('optom_token');
    if (!token) return;
    
    try {
        const res = await fetch('/api/optom-auth.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Optom-Token': token },
            body: JSON.stringify({ action: 'check' })
        });
        const data = await res.json();
        if (!data.authenticated) return;
        
        const s = data.seller;
        
        // Profile card
        document.getElementById('profile-avatar').textContent = s.company_name.charAt(0).toUpperCase();
        document.getElementById('profile-company').textContent = s.company_name;
        document.getElementById('profile-owner').textContent = s.owner_name;
        document.getElementById('profile-phone').textContent = s.phone;
        document.getElementById('profile-email').textContent = s.email || '—';
        document.getElementById('profile-city').textContent = s.city || '—';
        
        const statusMap = { active: 'Faol', pending: 'Kutilmoqda', blocked: 'Bloklangan' };
        document.getElementById('profile-status').textContent = statusMap[s.status] || s.status;
        
        if (s.verified) {
            document.getElementById('profile-verified').style.display = '';
        }
        
        // Edit form
        document.getElementById('edit-company').value = s.company_name;
        document.getElementById('edit-owner').value = s.owner_name;
        document.getElementById('edit-email').value = s.email || '';
        document.getElementById('edit-inn').value = s.inn || '';
        document.getElementById('edit-city').value = s.city || 'Toshkent';
        document.getElementById('edit-address').value = s.address || '';
        document.getElementById('edit-description').value = s.description || '';
        document.getElementById('edit-min-order').value = s.min_order_amount || '';
        
        // Categories
        const cats = JSON.parse(s.categories || '[]');
        editCats = [...cats];
        document.querySelectorAll('#edit-cats .cat-chip').forEach(btn => {
            if (cats.includes(btn.dataset.cat)) {
                btn.classList.add('bg-teal-500/20', 'border-teal-500/50', 'text-teal-400');
                btn.classList.remove('border-white/10', 'text-gray-400');
            }
        });
    } catch (e) { console.error(e); }
});

document.getElementById('profile-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = e.target.querySelector('button[type="submit"]');
    btn.disabled = true;
    
    try {
        const data = await optomAPI('update_profile', {
            company_name: document.getElementById('edit-company').value,
            owner_name: document.getElementById('edit-owner').value,
            email: document.getElementById('edit-email').value,
            inn: document.getElementById('edit-inn').value,
            city: document.getElementById('edit-city').value,
            address: document.getElementById('edit-address').value,
            description: document.getElementById('edit-description').value,
            min_order_amount: parseInt(document.getElementById('edit-min-order').value) || 0,
            categories: editCats,
        });
        
        if (data.success) {
            showToast('Profil yangilandi', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.error || 'Xatolik', 'error');
        }
    } catch (e) {
        showToast('Tarmoq xatosi', 'error');
    } finally {
        btn.disabled = false;
    }
});
</script>

<?php require_once __DIR__ . '/../components/optom-footer.php'; ?>
