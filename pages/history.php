<?php
$pageTitle = 'Mening Tarixim – Tiba AI';
include __DIR__ . '/../components/header.php';
?>

<div class="py-12 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
            <div>
                <h1 class="text-3xl font-extrabold text-white">Mening <span class="gradient-text">Tarixim</span></h1>
                <p class="text-gray-400 text-sm mt-1">Siz yaratgan barcha infografikalar tarixi</p>
                <div class="inline-flex items-center gap-2 mt-3 px-3 py-1 rounded-lg bg-yellow-500/10 border border-yellow-500/20">
                    <span class="text-xs text-yellow-500/80">⚠️ Tarix admin belgilagan vaqtda (standart: 7 kun) avtomatik tozalanadi.</span>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="history-loading" class="py-20 text-center">
            <div class="loader w-12 h-12 border-4 border-indigo-500/20 border-t-indigo-500 mx-auto mb-4"></div>
            <p class="text-gray-500 text-sm animate-pulse">Tarix yuklanmoqda...</p>
        </div>

        <!-- Empty State -->
        <div id="history-empty" class="hidden py-20 text-center">
            <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl">📜</div>
            <h3 class="text-xl font-bold text-white mb-2">Hali hech narsa yaratmadingiz</h3>
            <p class="text-gray-500 max-w-sm mx-auto mb-8">Birinchi infografikangizni hoziroq yarating va u shu yerda paydo bo'ladi.</p>
            <a href="/create" class="btn-primary px-8 py-3 font-bold inline-flex items-center gap-2">
                <span>✨</span> Yaratishni boshlash
            </a>
        </div>

        <!-- Grid -->
        <div id="history-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 hidden">
            <!-- Items injected via JS -->
        </div>

        <!-- Pagination -->
        <div id="history-pagination" class="mt-12 flex justify-center gap-2 hidden"></div>

    </div>
</div>

<!-- Image Modal -->
<div id="image-modal" class="hidden fixed inset-0 z-50 bg-black/95 backdrop-blur-md flex items-center justify-center p-4 animate-fade-in" onclick="if(event.target===this)closeModal()">
    <button onclick="closeModal()" class="absolute top-4 right-4 text-white/50 hover:text-white p-2">✕</button>
    <div class="max-w-5xl w-full flex flex-col md:flex-row gap-6 glass-card p-1 rounded-2xl overflow-hidden" onclick="event.stopPropagation()">
        <!-- Image -->
        <div class="flex-1 bg-black/50 flex items-center justify-center">
            <img id="modal-img" src="" class="max-h-[80vh] object-contain rounded-lg shadow-2xl">
        </div>
        <!-- Sidebar -->
        <div class="w-full md:w-80 bg-[#12121a] p-6 flex flex-col gap-4 border-l border-white/5 overflow-y-auto max-h-[80vh]">
            <div>
                <div id="modal-type" class="mb-4"></div>
                <h3 id="modal-title" class="text-xl font-bold text-white leading-tight mb-2"></h3>
                <p id="modal-date" class="text-xs text-gray-500 uppercase tracking-widest"></p>
            </div>

            <div id="modal-details" class="space-y-3 mt-2">
                <!-- Dynamic details injected here -->
            </div>

            <div class="mt-auto pt-4 space-y-3">
                <a id="modal-download" href="#" download class="btn-primary w-full py-3 font-bold text-center block">
                    📥 Yuklab olish
                </a>
            </div>
        </div>
    </div>
</div>

<script>
const typeLabels = {
    'infographic': 'Infografika',
    'paket_slide': 'Paket Slayd',
    'foto-tahrir': 'Foto Tahrir',
    'text-to-image': 'Noldan Yaratish',
    'style-transfer': 'Uslub Nusxalash',
    'smart-text': 'Smart Matn',
    'fashion-ai': 'Fashion AI',
    'photoshoot-pro': 'Fotosesiya PRO',
    'kartochka-ai': 'Kartochka AI'
};

document.addEventListener('DOMContentLoaded', () => {
    // Check token first
    if (!TibaAuth.getToken()) {
        window.location.href = '/';
        return;
    }
    
    loadHistory(1);
});

window.__historyCache = {};

async function loadHistory(page) {
    const grid = document.getElementById('history-grid');
    const footer = document.getElementById('history-pagination');
    const loading = document.getElementById('history-loading');
    const empty = document.getElementById('history-empty');
    
    loading.classList.remove('hidden');
    grid.classList.add('hidden');
    empty.classList.add('hidden');
    
    try {
        const token = TibaAuth.getToken();
        const res = await fetch(`/api/history.php?page=${page}`, {
            headers: { 'X-User-Token': token }
        });

        if (res.status === 401) {
            TibaAuth.logout(); // Clear invalid token
            window.location.href = '/';
            return;
        }

        const data = await res.json();
        
        loading.classList.add('hidden');
        
        if (!data.success || data.history.length === 0) {
            empty.classList.remove('hidden');
            return;
        }

        grid.classList.remove('hidden');
        grid.innerHTML = data.history.map(item => {
            // Cache item for modal
            window.__historyCache[item.id] = item;

            const typeKey = item.type.startsWith('paket_slide') ? 'paket_slide' : item.type;
            const typeName = typeLabels[typeKey] || item.type;
            
            return `
                <div class="group relative aspect-[3/4] rounded-2xl overflow-hidden bg-white/5 border border-white/5 hover:border-indigo-500/50 cursor-pointer transition-all hover:-translate-y-1 hover:shadow-2xl hover:shadow-indigo-500/20" onclick="openModalById(${item.id})">
                    <img src="${item.url}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                    
                    <div class="absolute top-2 left-2 z-10">
                        <span class="px-2 py-0.5 rounded-lg bg-black/60 backdrop-blur-md border border-white/10 text-[9px] font-bold text-indigo-300 uppercase tracking-wider">${typeName}</span>
                    </div>

                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-4">
                        <h3 class="text-white font-bold text-sm line-clamp-2 leading-tight">${item.product}</h3>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-[10px] text-gray-400 font-mono">${item.time}</span>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
        
        // Paginatsiya
        if (data.pagination.pages > 1) {
            footer.classList.remove('hidden');
            let phtml = '';
            for (let i = 1; i <= data.pagination.pages; i++) {
                const active = i === data.pagination.page ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/25' : 'bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white';
                phtml += `<button onclick="loadHistory(${i})" class="${active} w-10 h-10 rounded-xl text-sm font-bold transition-all">${i}</button>`;
            }
            footer.innerHTML = phtml;
        } else {
            footer.classList.add('hidden');
        }

    } catch (err) {
        loading.innerHTML = `<p class="text-red-400">Xatolik yuz berdi: ${err.message}</p>`;
    }
}

let currentModalItem = null;
let currentModalLang = 'uz';

function openModalById(id) {
    const item = window.__historyCache[id];
    if (item) openModal(item);
}

function openModal(item) {
    currentModalItem = item;
    const m = document.getElementById('image-modal');
    document.getElementById('modal-img').src = item.url;
    document.getElementById('modal-title').textContent = item.product;
    document.getElementById('modal-date').textContent = item.time;
    document.getElementById('modal-download').href = item.url;
    
    // Dynamic Details
    const detailsContainer = document.getElementById('modal-details');
    let detailsHtml = '';

    const labels = {
        'type': 'Xizmat turi',
        'style': 'Stil / Uslub',
        'lang': 'Til',
        'aspectRatio': 'Format (Nisbat)',
        'strength': 'Kuchlanish',
        'layout': 'Joylashuv',
        'colorTheme': 'Rang mavzusi',
        'marketplace': 'Marketpleys',
        'modelType': 'Model turi',
        'pose': 'Poza',
        'category': 'Kategoriya',
        'shotName': 'Kadr nomi',
        'slide_index': 'Slayd raqami',
        'total_slides': 'Jami slaydlar'
    };

    // Add Type Badge to the TOP
    const typeKey = item.type.startsWith('paket_slide') ? 'paket_slide' : item.type;
    const typeName = typeLabels[typeKey] || item.type;
    document.getElementById('modal-type').innerHTML = `
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-500/20 border border-indigo-500/30 text-xs font-black text-indigo-400 uppercase tracking-widest shadow-lg shadow-indigo-500/10">
            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
            ${typeName}
        </span>
    `;

    // Process details (excluding product and type)
    const d = item.details || {};
    
    // SPECIAL HANDLING: Kartochka AI
    if (item.type === 'kartochka-ai' && d.result) {
        renderKartochkaInModal(d.result);
        m.classList.remove('hidden');
        return;
    }

    for (const [key, value] of Object.entries(d)) {
        if (key === 'product' || key === 'type' || key === 'features' || key === 'shotPrompt' || key === 'shotName' || key === 'result') continue;
        if (!value) continue;
        
        const label = labels[key] || key;
        detailsHtml += `
            <div class="p-3 bg-white/5 rounded-xl border border-white/5 group-hover:border-white/10 transition-colors">
                <span class="text-[10px] text-gray-500 uppercase tracking-widest block mb-1">${label}</span>
                <span class="text-sm font-bold text-gray-200">${value}</span>
            </div>
        `;
    }

    // Special handling for Shot Name (Fotosesiya PRO)
    if (d.shotName) {
        detailsHtml = `
            <div class="p-3 bg-purple-500/10 rounded-xl border border-purple-500/20 mb-2">
                <span class="text-[10px] text-purple-400 uppercase tracking-widest block mb-1">Kadr / Kompozitsiya</span>
                <span class="text-sm font-bold text-white">${d.shotName}</span>
            </div>
        ` + detailsHtml;
    }

    // Special handling for Features (Smart Matn)
    if (d.features) {
        detailsHtml += `
            <div class="p-3 bg-white/5 rounded-xl border border-white/5">
                <span class="text-[10px] text-gray-500 uppercase tracking-widest block mb-1">Xususiyatlar</span>
                <div class="text-xs text-gray-400 whitespace-pre-line leading-relaxed">${d.features.substring(0, 300)}${d.features.length > 300 ? '...' : ''}</div>
            </div>
        `;
    }

    detailsContainer.innerHTML = detailsHtml;
    
    m.classList.remove('hidden');
}

function renderKartochkaInModal(result) {
    const container = document.getElementById('modal-details');
    currentModalLang = 'uz'; // Default
    
    const langData = result[currentModalLang] || {};
    let html = `
        <div class="flex gap-1 p-1 bg-white/5 rounded-xl border border-white/5 mb-4">
            <button onclick="switchModalTab('uz')" id="modal-tab-uz" class="flex-1 py-2 rounded-lg text-[10px] font-bold transition-all bg-amber-500/20 text-amber-400 border border-amber-500/20">🇺🇿 UZ</button>
            <button onclick="switchModalTab('ru')" id="modal-tab-ru" class="flex-1 py-2 rounded-lg text-[10px] font-bold transition-all text-gray-500 hover:bg-white/5">🇷🇺 RU</button>
        </div>
        <div id="modal-kartochka-content" class="space-y-3">
            ${buildKartochkaHtml(langData, 'uz')}
        </div>
    `;
    container.innerHTML = html;
}

function switchModalTab(lang) {
    currentModalLang = lang;
    const result = currentModalItem.details.result;
    const langData = result[lang] || {};
    
    // Update tabs
    const tabUz = document.getElementById('modal-tab-uz');
    const tabRu = document.getElementById('modal-tab-ru');
    
    if (lang === 'uz') {
        tabUz.className = 'flex-1 py-2 rounded-lg text-[10px] font-bold transition-all bg-amber-500/20 text-amber-400 border border-amber-500/20';
        tabRu.className = 'flex-1 py-2 rounded-lg text-[10px] font-bold transition-all text-gray-500 hover:bg-white/5';
    } else {
        tabRu.className = 'flex-1 py-2 rounded-lg text-[10px] font-bold transition-all bg-blue-500/20 text-blue-400 border border-blue-500/20';
        tabUz.className = 'flex-1 py-2 rounded-lg text-[10px] font-bold transition-all text-gray-500 hover:bg-white/5';
    }
    
    document.getElementById('modal-kartochka-content').innerHTML = buildKartochkaHtml(langData, lang);
}

function buildKartochkaHtml(data, lang) {
    const labels = {
        uz: { title: 'Mahsulot nomi', desc: 'Tavsif', features: 'Xususiyatlar', specs: 'Texnik specs' },
        ru: { title: 'Название товара', desc: 'Описание', features: 'Характеристики', specs: 'Технические данные' }
    };
    const l = labels[lang] || labels.uz;

    const sections = [
        { key: 'title', label: l.title, color: 'amber' },
        { key: 'fullDescription', label: l.desc, color: 'orange' },
        { key: 'features', label: l.features, color: 'emerald' },
        { key: 'specifications', label: l.specs, color: 'cyan' },
    ];
    
    let html = '';
    sections.forEach(sec => {
        let val = data[sec.key];
        if (!val) return;
        
        let displayVal = '';
        let copyVal = '';
        
        if (Array.isArray(val)) {
            displayVal = `<ul class="space-y-1">${val.map(v => `<li class="text-[11px] text-gray-300">• ${v}</li>`).join('')}</ul>`;
            copyVal = val.join('\n');
        } else if (typeof val === 'object') {
            displayVal = `<div class="space-y-1">${Object.entries(val).map(([k,v]) => `<div class="flex justify-between text-[11px] border-b border-white/5 pb-1"><span class="text-gray-500">${k}:</span><span class="text-white">${v}</span></div>`).join('')}</div>`;
            copyVal = Object.entries(val).map(([k,v]) => `${k}: ${v}`).join('\n');
        } else {
            displayVal = `<div class="text-[11px] text-gray-300 leading-relaxed">${val}</div>`;
            copyVal = val;
        }
        
        // Escape redundant backticks for onclick
        const safeText = JSON.stringify(copyVal);
        
        html += `
            <div class="p-3 bg-white/5 rounded-xl border border-white/5 group">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-[9px] font-bold text-${sec.color}-400 uppercase tracking-widest">${sec.label}</span>
                    <button onclick='copyModalField(this, ${safeText})' class="opacity-0 group-hover:opacity-100 p-1 text-[9px] text-gray-500 hover:text-white transition-all">📋 Nusxa</button>
                </div>
                ${displayVal}
            </div>
        `;
    });
    return html;
}

function copyModalField(btn, text) {
    navigator.clipboard.writeText(text).then(() => {
        const orig = btn.textContent;
        btn.textContent = '✅';
        setTimeout(() => btn.textContent = orig, 1000);
    });
}

function closeModal() {
    document.getElementById('image-modal');
    document.getElementById('image-modal').classList.add('hidden');
}
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
