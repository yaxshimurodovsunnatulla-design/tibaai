<?php
$pageTitle = 'Tiba Optom – Buyurtmalar';
require_once __DIR__ . '/../components/optom-header.php';
?>

<div class="p-4 md:p-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white">Buyurtmalar</h1>
            <p class="text-gray-400 text-sm mt-1">Mijozlardan kelgan so'rovlar va buyurtmalar</p>
        </div>
        <div class="flex gap-3">
            <select id="filter-status" onchange="filterOrders()" class="select-field py-2 text-xs w-auto">
                <option value="">Barchasi</option>
                <option value="new">Yangi</option>
                <option value="accepted">Qabul qilingan</option>
                <option value="shipped">Yuborilgan</option>
                <option value="completed">Yakunlangan</option>
                <option value="cancelled">Bekor qilingan</option>
            </select>
        </div>
    </div>

    <div id="orders-container" class="space-y-4">
        <div class="glass-card p-12 text-center">
            <div class="loader w-6 h-6 border-2 border-teal-500/20 border-t-teal-500 mx-auto mb-3"></div>
            <p class="text-gray-500 text-sm">Yuklanmoqda...</p>
        </div>
    </div>
</div>

<script>
let allOrders = [];

document.addEventListener('DOMContentLoaded', loadOrders);

async function loadOrders() {
    try {
        const data = await optomAPI('get_my_orders');
        if (!data.success) return;
        allOrders = data.orders;
        filterOrders();
    } catch (e) { console.error(e); }
}

function filterOrders() {
    const status = document.getElementById('filter-status').value;
    const filtered = status ? allOrders.filter(o => o.status === status) : allOrders;
    renderOrders(filtered);
}

function renderOrders(orders) {
    const container = document.getElementById('orders-container');
    
    if (orders.length === 0) {
        container.innerHTML = `<div class="glass-card p-12"><div class="empty-state">
            <i class="fa-solid fa-truck text-gray-700 text-5xl mb-4"></i>
            <p class="text-gray-400 font-bold mb-1">Buyurtmalar topilmadi</p>
            <p class="text-gray-600 text-xs">Yangi buyurtmalar bu yerda ko'rinadi</p>
        </div></div>`;
        return;
    }

    const statusMap = {
        new: { text: 'Yangi', class: 'badge-blue', icon: 'fa-bell' },
        accepted: { text: 'Qabul qilindi', class: 'badge-emerald', icon: 'fa-check' },
        shipped: { text: 'Yuborildi', class: 'badge-amber', icon: 'fa-truck' },
        completed: { text: 'Yakunlandi', class: 'badge-teal', icon: 'fa-check-double' },
        cancelled: { text: 'Bekor', class: 'badge-red', icon: 'fa-xmark' }
    };

    container.innerHTML = orders.map(o => {
        const s = statusMap[o.status] || { text: 'Noma\'lum', class: 'badge-gray', icon: 'fa-question' };
        const date = new Date(o.created_at).toLocaleDateString('uz-UZ', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        
        return `
        <div class="glass-card p-5 hover:bg-white/[0.04] transition-all animate-fade-in-up">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex items-start gap-4 flex-1 min-w-0">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500/20 to-emerald-500/20 flex items-center justify-center text-teal-400 font-bold text-lg flex-shrink-0">
                        ${o.customer_name.charAt(0).toUpperCase()}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm font-bold text-white">${o.customer_name}</span>
                            <span class="badge ${s.class}"><i class="fa-solid ${s.icon} text-[8px] mr-0.5"></i> ${s.text}</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            <i class="fa-solid fa-phone mr-1"></i> ${o.customer_phone}
                        </div>
                        <div class="text-xs text-gray-400 mt-2">
                            <span class="font-medium text-gray-300">${o.product_name || 'Umumiy so\'rov'}</span>
                            ${o.quantity > 1 ? ' · <span class="text-teal-400 font-bold">' + o.quantity + ' dona</span>' : ''}
                        </div>
                        ${o.message ? `<div class="mt-2 p-2.5 rounded-lg bg-white/[0.03] border border-white/5 text-xs text-gray-400 italic">"${o.message}"</div>` : ''}
                        <div class="text-[10px] text-gray-600 mt-2"><i class="fa-regular fa-clock mr-1"></i> ${date}</div>
                    </div>
                </div>
                <div class="flex sm:flex-col gap-2 sm:items-end flex-shrink-0">
                    ${o.status === 'new' ? `
                    <button onclick="updateOrderStatus(${o.id}, 'accepted')" class="px-3 py-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold hover:bg-emerald-500/20 transition-all">
                        <i class="fa-solid fa-check mr-1"></i> Qabul
                    </button>
                    <button onclick="updateOrderStatus(${o.id}, 'cancelled')" class="px-3 py-1.5 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-bold hover:bg-red-500/20 transition-all">
                        <i class="fa-solid fa-xmark mr-1"></i> Rad
                    </button>` : ''}
                    ${o.status === 'accepted' ? `
                    <button onclick="updateOrderStatus(${o.id}, 'shipped')" class="px-3 py-1.5 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-bold hover:bg-amber-500/20 transition-all">
                        <i class="fa-solid fa-truck mr-1"></i> Yuborildi
                    </button>` : ''}
                    ${o.status === 'shipped' ? `
                    <button onclick="updateOrderStatus(${o.id}, 'completed')" class="px-3 py-1.5 rounded-lg bg-teal-500/10 border border-teal-500/20 text-teal-400 text-xs font-bold hover:bg-teal-500/20 transition-all">
                        <i class="fa-solid fa-check-double mr-1"></i> Yakunlandi
                    </button>` : ''}
                </div>
            </div>
        </div>`;
    }).join('');
}

async function updateOrderStatus(id, status) {
    try {
        const data = await optomAPI('update_order', { order_id: id, status });
        if (data.success) {
            showToast(data.message, 'success');
            loadOrders();
        } else {
            showToast(data.error || 'Xatolik', 'error');
        }
    } catch (e) {
        showToast('Tarmoq xatosi', 'error');
    }
}
</script>

<?php require_once __DIR__ . '/../components/optom-footer.php'; ?>
