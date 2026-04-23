<?php
require_once __DIR__ . '/../api/config.php';
$pageTitle = 'QQS Kalkulyatori – Tiba AI';
$pageDescription = 'Qachon majburiy ravishda QQSga o\'tishingizni O\'zR Soliq Kodeksi 462-moddasiga binoan bilib oling.';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<style>
    .qqs-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        color: #9ca3af;
        margin-bottom: 1.5rem;
    }
    .qqs-breadcrumb a {
        color: #9ca3af;
        text-decoration: none;
        transition: color 0.2s;
    }
    .qqs-breadcrumb a:hover {
        color: #fff;
    }
    .qqs-breadcrumb .separator {
        color: #4b5563;
    }
    .qqs-breadcrumb .current {
        color: #c4b5fd;
        font-weight: 600;
    }

    .qqs-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.75rem;
    }
    .qqs-header-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: #fff;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    }
    .qqs-header h1 {
        font-size: 1.75rem;
        font-weight: 800;
        color: #fff;
    }
    .qqs-subtitle {
        color: #9ca3af;
        font-size: 0.9rem;
        line-height: 1.6;
        max-width: 700px;
    }

    .qqs-grid {
        display: grid;
        grid-template-columns: 1fr 1.4fr;
        gap: 2rem;
        margin-top: 2rem;
    }
    @media (max-width: 768px) {
        .qqs-grid {
            grid-template-columns: 1fr;
        }
    }

    .qqs-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 1.25rem;
        padding: 2rem;
        backdrop-filter: blur(10px);
    }

    .qqs-card-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 1.5rem;
    }

    .qqs-form-group {
        margin-bottom: 1.25rem;
    }
    .qqs-form-label {
        display: block;
        color: #9ca3af;
        font-size: 0.8rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    .qqs-form-sublabel {
        color: #6b7280;
        font-size: 0.72rem;
        margin-top: 0.4rem;
    }

    .qqs-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }
    .qqs-input {
        width: 100%;
        padding: 0.8rem 1rem;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 0.75rem;
        color: #fff;
        font-size: 1rem;
        font-weight: 600;
        outline: none;
        transition: border-color 0.3s, box-shadow 0.3s;
    }
    .qqs-input:focus {
        border-color: rgba(245, 158, 11, 0.5);
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }
    .qqs-input-suffix {
        position: absolute;
        right: 1rem;
        color: #6b7280;
        font-size: 0.85rem;
        font-weight: 500;
        pointer-events: none;
    }
    .qqs-input-icon {
        position: absolute;
        right: 1rem;
        color: #6b7280;
        font-size: 1rem;
        cursor: pointer;
        transition: color 0.2s;
    }
    .qqs-input-icon:hover {
        color: #9ca3af;
    }

    .qqs-clear-btn {
        width: 100%;
        padding: 0.9rem;
        border-radius: 0.75rem;
        border: 2px solid #f59e0b;
        background: transparent;
        color: #f59e0b;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 0.5rem;
    }
    .qqs-clear-btn:hover {
        background: rgba(245, 158, 11, 0.1);
    }

    /* Right panel */
    .qqs-status-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }
    .qqs-status-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .qqs-status-arrow {
        color: #6b7280;
        font-size: 1rem;
        cursor: pointer;
        transition: color 0.2s;
    }
    .qqs-status-arrow:hover {
        color: #fff;
    }

    /* Progress Section */
    .qqs-progress-section {
        margin-bottom: 2rem;
    }
    .qqs-progress-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }
    .qqs-progress-label-left {
        color: #9ca3af;
        font-size: 0.8rem;
    }
    .qqs-progress-label-right {
        text-align: right;
    }
    .qqs-progress-label-right .label {
        color: #9ca3af;
        font-size: 0.72rem;
        display: block;
    }
    .qqs-progress-label-right .value {
        color: #fff;
        font-size: 0.95rem;
        font-weight: 700;
    }

    .qqs-progress-percentage {
        display: flex;
        align-items: baseline;
        gap: 0.4rem;
        margin-bottom: 0.5rem;
    }
    .qqs-progress-percentage .number {
        font-size: 2.5rem;
        font-weight: 800;
        color: #fff;
    }
    .qqs-progress-percentage .unit {
        font-size: 0.9rem;
        color: #9ca3af;
    }

    .qqs-progress-bar-bg {
        width: 100%;
        height: 14px;
        background: rgba(255,255,255,0.06);
        border-radius: 999px;
        overflow: hidden;
        position: relative;
    }
    .qqs-progress-bar-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, #f59e0b, #d97706);
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    .qqs-progress-bar-fill.danger {
        background: linear-gradient(90deg, #ef4444, #dc2626);
    }
    .qqs-progress-bar-fill.warning {
        background: linear-gradient(90deg, #f59e0b, #d97706);
    }
    .qqs-progress-bar-fill.safe {
        background: linear-gradient(90deg, #22c55e, #16a34a);
    }

    /* Warning Card */
    .qqs-warning-card {
        padding: 1.25rem 1.5rem;
        border-radius: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }
    .qqs-warning-card.danger {
        background: rgba(239, 68, 68, 0.08);
        border: 1px solid rgba(239, 68, 68, 0.15);
    }
    .qqs-warning-card.warning {
        background: rgba(245, 158, 11, 0.08);
        border: 1px solid rgba(245, 158, 11, 0.15);
    }
    .qqs-warning-card.safe {
        background: rgba(34, 197, 94, 0.08);
        border: 1px solid rgba(34, 197, 94, 0.15);
    }
    .qqs-warning-icon {
        font-size: 1.3rem;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .qqs-warning-icon.danger { color: #ef4444; }
    .qqs-warning-icon.warning { color: #f59e0b; }
    .qqs-warning-icon.safe { color: #22c55e; }
    .qqs-warning-title {
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 0.3rem;
    }
    .qqs-warning-title.danger { color: #fca5a5; }
    .qqs-warning-title.warning { color: #fcd34d; }
    .qqs-warning-title.safe { color: #86efac; }
    .qqs-warning-text {
        font-size: 0.82rem;
        color: #9ca3af;
        line-height: 1.55;
    }

    /* Bottom Info Cards */
    .qqs-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    @media (max-width: 500px) {
        .qqs-info-grid {
            grid-template-columns: 1fr;
        }
    }
    .qqs-info-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
    }
    .qqs-info-card-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #9ca3af;
        font-size: 0.78rem;
        margin-bottom: 0.5rem;
    }
    .qqs-info-card-header i {
        font-size: 0.85rem;
    }
    .qqs-info-card-value {
        font-size: 0.95rem;
        font-weight: 700;
        color: #fff;
        line-height: 1.4;
    }
    .qqs-info-card-value .big {
        font-size: 2.25rem;
        font-weight: 800;
    }
    .qqs-info-card-value .sub {
        color: #9ca3af;
        font-size: 0.8rem;
        font-weight: 500;
    }

    /* Info Section at Bottom */
    .qqs-info-section {
        margin-top: 2.5rem;
        background: rgba(245, 158, 11, 0.04);
        border: 1px solid rgba(245, 158, 11, 0.1);
        border-radius: 1.25rem;
        padding: 2rem;
    }
    .qqs-info-section h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.75rem;
        text-align: center;
    }
    .qqs-info-section p {
        color: #9ca3af;
        font-size: 0.85rem;
        line-height: 1.7;
        text-align: center;
    }
    .qqs-info-section .highlight {
        color: #fbbf24;
        font-weight: 600;
    }
</style>

<div class="py-8 sm:py-14 relative overflow-hidden">
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-amber-600/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-orange-600/10 rounded-full blur-3xl"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <!-- Breadcrumb -->
        <div class="qqs-breadcrumb">
            <a href="/">Bosh sahifa</a>
            <span class="separator">›</span>
            <span class="current">QQS Limit Kalkulyatori</span>
        </div>

        <!-- Header -->
        <div class="qqs-header">
            <div class="qqs-header-icon">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <h1>QQS Kalkulyatori</h1>
        </div>
        <p class="qqs-subtitle">
            Qachon majburiy ravishda Aylanma soliqdan QQSga o'tishingizni O'zR Soliq Kodeksi 462-moddasiga binoan bilib oling.
        </p>

        <!-- Main Grid -->
        <div class="qqs-grid">
            <!-- LEFT: Input Card -->
            <div class="qqs-card">
                <h2 class="qqs-card-title">Soliq Tarixini Kiriting</h2>

                <div class="qqs-form-group">
                    <label class="qqs-form-label">Korxona ro'yxatdan o'tgan sana</label>
                    <div class="qqs-input-wrapper">
                        <input type="date" id="qqs-reg-date" class="qqs-input" value="2026-01-01">
                        <!-- <span class="qqs-input-icon"><i class="fa-regular fa-calendar"></i></span> -->
                    </div>
                    <p class="qqs-form-sublabel">Guvohnoma (Guvohnoma) berilgan sana</p>
                </div>

                <div class="qqs-form-group">
                    <label class="qqs-form-label">Joriy aylanma (UZS)</label>
                    <div class="qqs-input-wrapper">
                        <input type="text" id="qqs-revenue" class="qqs-input" placeholder="Masalan: 900 000 000" oninput="formatRevenueInput(this)">
                        <span class="qqs-input-suffix">UZS</span>
                    </div>
                    <p class="qqs-form-sublabel">Yil boshidan beri jami kirim summasi</p>
                </div>

                <button onclick="clearQQSForm()" class="qqs-clear-btn">
                    Ma'lumotlarni tozalash
                </button>
            </div>

            <!-- RIGHT: Results Panel -->
            <div>
                <div class="qqs-card">
                    <div class="qqs-status-header">
                        <h2 class="qqs-status-title">Huquqiy Status va Xavfsizlik Indikatori</h2>
                        <span class="qqs-status-arrow" title="Batafsil"><i class="fa-solid fa-arrow-right"></i></span>
                    </div>

                    <!-- Progress -->
                    <div class="qqs-progress-section">
                        <div class="qqs-progress-label">
                            <span class="qqs-progress-label-left">Limitga yaqinlik (Progress)</span>
                            <div class="qqs-progress-label-right">
                                <span class="label">Maxsus Sizning Limitingiz</span>
                                <span class="value" id="qqs-limit-display">1 000 000 000 UZS</span>
                            </div>
                        </div>
                        <div class="qqs-progress-percentage">
                            <span class="number" id="qqs-percent-value">90.0</span>
                            <span class="unit" id="qqs-percent-unit">% to'ldi</span>
                        </div>
                        <div class="qqs-progress-bar-bg">
                            <div class="qqs-progress-bar-fill warning" id="qqs-progress-bar" style="width: 90%"></div>
                        </div>
                    </div>

                    <!-- Warning Card -->
                    <div class="qqs-warning-card warning" id="qqs-warning-card">
                        <div class="qqs-warning-icon warning" id="qqs-warning-icon">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <div class="qqs-warning-title warning" id="qqs-warning-title">Diqqat qiling: Limitga juda yaqin qoldingiz!</div>
                            <div class="qqs-warning-text" id="qqs-warning-text">
                                Sizda yil oxirigacha bor yo'g'i <span id="qqs-remaining-sum">100 000 000</span> sum aylanma qilish limiti qoldi.
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Info Cards -->
                    <div class="qqs-info-grid">
                        <div class="qqs-info-card">
                            <div class="qqs-info-card-header">
                                <i class="fa-regular fa-calendar"></i>
                                Qachon QQSga o'tasiz?
                            </div>
                            <div class="qqs-info-card-value" id="qqs-transition-info">
                                <strong>Xavfsiz.</strong> Bu yilda QQS ga o'tmaysiz.
                            </div>
                        </div>
                        <div class="qqs-info-card">
                            <div class="qqs-info-card-header">
                                <i class="fa-regular fa-clock"></i>
                                Tugallanguncha hisoblangan kunlar
                            </div>
                            <div class="qqs-info-card-value">
                                <span class="big" id="qqs-days-left">365</span> <span class="sub">kun proporsiyasi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Section -->
        <div class="qqs-info-section">
            <h3><i class="fa-solid fa-circle-info" style="color: #f59e0b; margin-right: 6px;"></i> QQS limiti haqida</h3>
            <p>
                O'zbekiston Respublikasi Soliq Kodeksining <span class="highlight">462-moddasiga</span> binoan, 
                agar korxonaning yillik aylanma daromadi <span class="highlight">1 000 000 000 so'm</span>dan oshsa, 
                korxona <span class="highlight">majburiy ravishda QQS to'lovchisi</span> sifatida ro'yxatdan o'tishi kerak. 
                Bu kalkulyator yordamida siz yil oxirigacha qancha muddatingiz qolganini va limitga qanchalik yaqinlashganingizni bilishingiz mumkin.
            </p>
        </div>
    </div>
</div>

<script>
const QQS_LIMIT = 1000000000; // 1 milliard so'm

function formatNumber(num) {
    return new Intl.NumberFormat('uz-UZ').format(Math.round(num)).replace(/,/g, ' ');
}

function formatRevenueInput(input) {
    let raw = input.value.replace(/[^0-9]/g, '');
    if (raw) {
        input.value = formatNumber(parseInt(raw));
    }
}

function getRawRevenue() {
    const val = document.getElementById('qqs-revenue').value;
    return parseInt(val.replace(/[^0-9]/g, '')) || 0;
}

function getDaysLeftInYear() {
    const now = new Date();
    const endOfYear = new Date(now.getFullYear(), 11, 31);
    const diffTime = endOfYear - now;
    return Math.max(0, Math.ceil(diffTime / (1000 * 60 * 60 * 24)));
}

function getDaysInYear() {
    const now = new Date();
    const year = now.getFullYear();
    const start = new Date(year, 0, 1);
    const end = new Date(year, 11, 31);
    return Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
}

function calculateQQS() {
    const revenue = getRawRevenue();
    const regDateStr = document.getElementById('qqs-reg-date').value;
    
    const daysLeft = getDaysLeftInYear();
    const totalDays = getDaysInYear();
    
    // Percentage
    const percent = QQS_LIMIT > 0 ? (revenue / QQS_LIMIT) * 100 : 0;
    const percentClamped = Math.min(percent, 100);
    const remaining = Math.max(0, QQS_LIMIT - revenue);
    
    // Update percent display
    document.getElementById('qqs-percent-value').textContent = percent.toFixed(1);
    
    // Update progress bar
    const progressBar = document.getElementById('qqs-progress-bar');
    progressBar.style.width = percentClamped + '%';
    
    // Remove old classes, add new
    progressBar.classList.remove('safe', 'warning', 'danger');
    
    const warningCard = document.getElementById('qqs-warning-card');
    warningCard.classList.remove('safe', 'warning', 'danger');
    
    const warningIcon = document.getElementById('qqs-warning-icon');
    warningIcon.classList.remove('safe', 'warning', 'danger');
    
    const warningTitle = document.getElementById('qqs-warning-title');
    warningTitle.classList.remove('safe', 'warning', 'danger');
    
    // Update remaining sum
    document.getElementById('qqs-remaining-sum').textContent = formatNumber(remaining);
    
    // Limit display
    document.getElementById('qqs-limit-display').textContent = formatNumber(QQS_LIMIT) + ' UZS';
    
    // Days left
    document.getElementById('qqs-days-left').textContent = daysLeft;
    
    // Status logic
    if (percent >= 100) {
        // EXCEEDED
        progressBar.classList.add('danger');
        warningCard.classList.add('danger');
        warningIcon.classList.add('danger');
        warningTitle.classList.add('danger');
        
        document.getElementById('qqs-warning-icon').innerHTML = '<i class="fa-solid fa-circle-exclamation"></i>';
        warningTitle.textContent = 'Limit oshdi! QQSga o\'tish majburiy!';
        document.getElementById('qqs-warning-text').innerHTML = 
            'Sizning aylanmangiz <strong>' + formatNumber(revenue) + ' so\'m</strong>ga yetdi. 1 milliard so\'m limitdan o\'tib ketdingiz. Darhol QQSga ro\'yxatdan o\'ting.';
        
        document.getElementById('qqs-transition-info').innerHTML = 
            '<strong style="color: #fca5a5;">Hoziroq!</strong> QQS ga darhol o\'tishingiz kerak.';
    } else if (percent >= 80) {
        // WARNING
        progressBar.classList.add('warning');
        warningCard.classList.add('warning');
        warningIcon.classList.add('warning');
        warningTitle.classList.add('warning');
        
        document.getElementById('qqs-warning-icon').innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i>';
        warningTitle.textContent = 'Diqqat qiling: Limitga juda yaqin qoldingiz!';
        document.getElementById('qqs-warning-text').innerHTML = 
            'Sizda yil oxirigacha bor yo\'g\'i <strong>' + formatNumber(remaining) + '</strong> sum aylanma qilish limiti qoldi.';
        
        document.getElementById('qqs-transition-info').innerHTML = 
            '<strong>Xavfsiz.</strong> Bu yilda QQS ga o\'tmaysiz.';
    } else if (percent >= 50) {
        // MODERATE
        progressBar.classList.add('warning');
        warningCard.classList.add('warning');
        warningIcon.classList.add('warning');
        warningTitle.classList.add('warning');
        
        document.getElementById('qqs-warning-icon').innerHTML = '<i class="fa-solid fa-info-circle"></i>';
        warningTitle.textContent = 'O\'rtacha holat: Ehtiyot bo\'ling';
        document.getElementById('qqs-warning-text').innerHTML = 
            'Sizda hali <strong>' + formatNumber(remaining) + '</strong> sum aylanma qilish imkoniyati bor. Lekin ehtiyot bo\'ling.';
        
        document.getElementById('qqs-transition-info').innerHTML = 
            '<strong>Xavfsiz.</strong> Bu yilda QQS ga o\'tmaysiz.';
    } else {
        // SAFE
        progressBar.classList.add('safe');
        warningCard.classList.add('safe');
        warningIcon.classList.add('safe');
        warningTitle.classList.add('safe');
        
        document.getElementById('qqs-warning-icon').innerHTML = '<i class="fa-solid fa-circle-check"></i>';
        warningTitle.textContent = 'Xavfsiz! Limitdan ancha uzoqdasiz.';
        document.getElementById('qqs-warning-text').innerHTML = 
            'Sizda hali <strong>' + formatNumber(remaining) + '</strong> sum aylanma qilish imkoniyati bor. Hech qanday tashvish yo\'q.';
        
        document.getElementById('qqs-transition-info').innerHTML = 
            '<strong>Xavfsiz.</strong> Bu yilda QQS ga o\'tmaysiz.';
    }
}

function clearQQSForm() {
    document.getElementById('qqs-reg-date').value = '';
    document.getElementById('qqs-revenue').value = '';
    calculateQQS();
}

// Auto-calculate on input
document.getElementById('qqs-revenue').addEventListener('input', function() {
    calculateQQS();
});
document.getElementById('qqs-reg-date').addEventListener('change', function() {
    calculateQQS();
});

// Initial calculation
document.addEventListener('DOMContentLoaded', function() {
    calculateQQS();
});
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
