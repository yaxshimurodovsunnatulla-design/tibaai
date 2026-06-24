<?php
$pageTitle = 'Tiba Optom – Mahsulotlar';
require_once __DIR__ . '/../components/optom-header.php';
?>

<div class="p-4 md:p-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white">Mahsulotlar</h1>
            <p class="text-gray-400 text-sm mt-1">Ulgurji mahsulotlaringizni boshqaring</p>
        </div>
        <button onclick="openAddModal()" class="btn-primary py-2.5 px-5 rounded-xl text-sm">
            <i class="fa-solid fa-plus mr-2"></i> Yangi mahsulot
        </button>
    </div>

    <!-- Products List -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="optom-table">
                <thead>
                    <tr>
                        <th>Mahsulot</th>
                        <th>Kategoriya</th>
                        <th>Chakana narx</th>
                        <th>Ulgurji narx</th>
                        <th>Min. buyurtma</th>
                        <th>Holat</th>
                        <th class="text-right">Amallar</th>
                    </tr>
                </thead>
                <tbody id="products-list">
                    <tr><td colspan="7" class="text-center py-12 text-gray-500"><div class="loader w-6 h-6 border-2 border-teal-500/20 border-t-teal-500 mx-auto mb-3"></div>Yuklanmoqda...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Product Modal -->
<div id="product-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/85 backdrop-blur-md" onclick="closeProductModal()"></div>
    <div class="relative w-full max-w-lg animate-fade-in-up">
        <div class="glass-card p-6 border border-white/10 shadow-2xl max-h-[85vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 id="modal-title" class="text-lg font-extrabold text-white">Yangi mahsulot</h3>
                <button onclick="closeProductModal()" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:text-white hover:bg-white/10 transition-all">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form id="product-form" class="space-y-4">
                <input type="hidden" id="edit-product-id" value="">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Mahsulot nomi *</label>
                    <input type="text" id="prod-name" required class="input-field" placeholder="Masalan: Erkaklar ko'ylagi">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Tavsif</label>
                    <textarea id="prod-desc" class="textarea-field" rows="3" placeholder="Mahsulot haqida qisqacha..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Kategoriya</label>
                        <select id="prod-category" class="select-field">
                            <option>Kiyimlar</option><option>Elektronika</option><option>Oziq-ovqat</option>
                            <option>Kosmetika</option><option>Uy jihozlari</option><option>Qurilish</option>
                            <option>Avtomobil</option><option>Bolalar</option><option>Sport</option>
                            <option>Maishiy texnika</option><option>Aksessuarlar</option><option>Boshqa</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">O'lchov birligi</label>
                        <select id="prod-unit" class="select-field">
                            <option value="dona">Dona</option><option value="kg">Kilogramm</option>
                            <option value="metr">Metr</option><option value="litr">Litr</option>
                            <option value="komplekt">Komplekt</option><option value="pachka">Pachka</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Chakana narx</label>
                        <input type="number" id="prod-price-retail" class="input-field" placeholder="50000" min="0">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Ulgurji narx *</label>
                        <input type="number" id="prod-price-wholesale" required class="input-field" placeholder="35000" min="1">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Min. miqdor</label>
                        <input type="number" id="prod-min-qty" class="input-field" placeholder="10" min="1" value="1">
                    </div>
                </div>
                <button type="submit" class="w-full btn-primary py-3 mt-2">
                    <span class="btn-text" id="modal-btn-text">Qo'shish</span>
                    <div class="loader hidden w-5 h-5 border-2 border-white/20 border-t-white mx-auto"></div>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
let allProducts = [];

document.addEventListener('DOMContentLoaded', loadProducts);

async function loadProducts() {
    try {
        const data = await optomAPI('get_my_products');
        if (!data.success) return;
        allProducts = data.products;
        renderProducts();
    } catch (e) { console.error(e); }
}

function renderProducts() {
    const list = document.getElementById('products-list');
    if (allProducts.length === 0) {
        list.innerHTML = `<tr><td colspan="7"><div class="empty-state py-16">
            <i class="fa-solid fa-boxes-stacked text-gray-700 text-5xl mb-4"></i>
            <p class="text-gray-400 font-bold mb-1">Hali mahsulot yo'q</p>
            <p class="text-gray-600 text-xs mb-4">Birinchi mahsulotingizni qo'shing</p>
            <button onclick="openAddModal()" class="btn-primary py-2 px-4 text-xs"><i class="fa-solid fa-plus mr-1"></i> Qo'shish</button>
        </div></td></tr>`;
        return;
    }

    list.innerHTML = allProducts.map(p => `
    <tr>
        <td>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-teal-500/10 border border-teal-500/10 flex items-center justify-center text-teal-400 flex-shrink-0">
                    <i class="fa-solid fa-box text-sm"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-sm font-bold text-white truncate max-w-[200px]">${p.name}</div>
                    <div class="text-[10px] text-gray-500 truncate max-w-[200px]">${p.description || '—'}</div>
                </div>
            </div>
        </td>
        <td><span class="badge badge-teal">${p.category}</span></td>
        <td>${p.price_retail ? formatNum(p.price_retail) + ' so\'m' : '—'}</td>
        <td class="font-bold text-teal-400">${formatNum(p.price_wholesale)} so'm</td>
        <td>${p.min_quantity} ${p.unit}</td>
        <td>${p.in_stock ? '<span class="badge badge-emerald">Bor</span>' : '<span class="badge badge-red">Tugagan</span>'}</td>
        <td class="text-right">
            <div class="flex items-center justify-end gap-2">
                <button onclick="editProduct(${p.id})" class="w-8 h-8 rounded-lg bg-white/5 text-gray-400 hover:text-white hover:bg-white/10 flex items-center justify-center transition-all" title="Tahrirlash">
                    <i class="fa-solid fa-pen text-xs"></i>
                </button>
                <button onclick="toggleStock(${p.id}, ${p.in_stock})" class="w-8 h-8 rounded-lg bg-white/5 text-gray-400 hover:text-amber-400 hover:bg-amber-500/10 flex items-center justify-center transition-all" title="${p.in_stock ? 'Tugagan deb belgilash' : 'Bor deb belgilash'}">
                    <i class="fa-solid fa-${p.in_stock ? 'eye-slash' : 'eye'} text-xs"></i>
                </button>
                <button onclick="deleteProduct(${p.id})" class="w-8 h-8 rounded-lg bg-white/5 text-gray-400 hover:text-red-400 hover:bg-red-500/10 flex items-center justify-center transition-all" title="O'chirish">
                    <i class="fa-solid fa-trash text-xs"></i>
                </button>
            </div>
        </td>
    </tr>`).join('');
}

function openAddModal() {
    document.getElementById('edit-product-id').value = '';
    document.getElementById('modal-title').textContent = 'Yangi mahsulot';
    document.getElementById('modal-btn-text').textContent = 'Qo\'shish';
    document.getElementById('product-form').reset();
    document.getElementById('product-modal').classList.remove('hidden');
}

function editProduct(id) {
    const p = allProducts.find(x => x.id == id);
    if (!p) return;
    document.getElementById('edit-product-id').value = p.id;
    document.getElementById('modal-title').textContent = 'Mahsulotni tahrirlash';
    document.getElementById('modal-btn-text').textContent = 'Saqlash';
    document.getElementById('prod-name').value = p.name;
    document.getElementById('prod-desc').value = p.description || '';
    document.getElementById('prod-category').value = p.category;
    document.getElementById('prod-unit').value = p.unit;
    document.getElementById('prod-price-retail').value = p.price_retail || '';
    document.getElementById('prod-price-wholesale').value = p.price_wholesale;
    document.getElementById('prod-min-qty').value = p.min_quantity;
    document.getElementById('product-modal').classList.remove('hidden');
}

function closeProductModal() {
    document.getElementById('product-modal').classList.add('hidden');
}

document.getElementById('product-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const editId = document.getElementById('edit-product-id').value;
    const payload = {
        name: document.getElementById('prod-name').value,
        description: document.getElementById('prod-desc').value,
        category: document.getElementById('prod-category').value,
        unit: document.getElementById('prod-unit').value,
        price_retail: parseInt(document.getElementById('prod-price-retail').value) || 0,
        price_wholesale: parseInt(document.getElementById('prod-price-wholesale').value) || 0,
        min_quantity: parseInt(document.getElementById('prod-min-qty').value) || 1,
    };

    try {
        let data;
        if (editId) {
            data = await optomAPI('update_product', { product_id: parseInt(editId), ...payload });
        } else {
            data = await optomAPI('add_product', payload);
        }
        if (data.success) {
            showToast(data.message, 'success');
            closeProductModal();
            loadProducts();
        } else {
            showToast(data.error || 'Xatolik', 'error');
        }
    } catch (e) {
        showToast('Tarmoq xatosi', 'error');
    }
});

async function toggleStock(id, current) {
    const data = await optomAPI('update_product', { product_id: id, in_stock: current ? 0 : 1 });
    if (data.success) { showToast('Holat yangilandi', 'success'); loadProducts(); }
}

async function deleteProduct(id) {
    if (!confirm('Mahsulotni o\'chirmoqchimisiz?')) return;
    const data = await optomAPI('delete_product', { product_id: id });
    if (data.success) { showToast('O\'chirildi', 'success'); loadProducts(); }
}
</script>

<?php require_once __DIR__ . '/../components/optom-footer.php'; ?>
