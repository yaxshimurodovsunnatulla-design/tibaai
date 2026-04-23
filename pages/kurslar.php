<?php
require_once __DIR__ . '/../api/config.php';
$pageTitle = 'Kurslar – Marketpleyslarda Savdo | Tiba AI';
$pageDescription = 'Marketpleyslarda savdo qilishni o\'rganing. Tekin konsultatsiya oling va professional savdo strategiyalarini kashf eting.';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<style>
    .kurs-hero {
        position: relative;
        padding: 4rem 0 3rem;
        overflow: hidden;
    }
    .kurs-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -20%;
        width: 80%;
        height: 200%;
        background: radial-gradient(ellipse, rgba(99, 102, 241, 0.08) 0%, transparent 70%);
        pointer-events: none;
    }
    .kurs-hero::after {
        content: '';
        position: absolute;
        bottom: -50%;
        right: -20%;
        width: 80%;
        height: 200%;
        background: radial-gradient(ellipse, rgba(168, 85, 247, 0.06) 0%, transparent 70%);
        pointer-events: none;
    }

    /* Hero layout */
    .kurs-hero-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        align-items: center;
    }
    @media (max-width: 900px) {
        .kurs-hero-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Marketplace Logos */
    .mp-logos-section {
        position: relative;
    }
    .mp-logos-section-title {
        text-align: center;
        color: #6b7280;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        margin-bottom: 1.25rem;
    }
    .mp-logos-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.85rem;
    }
    .mp-logo-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 1.1rem;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: default;
        position: relative;
    }
    .mp-logo-card:hover {
        border-color: rgba(255,255,255,0.15);
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.3);
    }
    .mp-logo-card img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        display: block;
        filter: brightness(0.95);
        transition: filter 0.3s, transform 0.3s;
    }
    .mp-logo-card:hover img {
        filter: brightness(1.05);
        transform: scale(1.03);
    }
    .mp-logo-card-name {
        padding: 0.65rem 0.85rem;
        text-align: center;
        font-size: 0.78rem;
        font-weight: 700;
        color: #d1d5db;
        background: rgba(255,255,255,0.02);
        border-top: 1px solid rgba(255,255,255,0.04);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    .mp-logo-card-name .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    @media (max-width: 500px) {
        .mp-logos-grid {
            grid-template-columns: 1fr;
        }
        .mp-logo-card img {
            height: 100px;
        }
    }

    .kurs-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 999px;
        background: rgba(99, 102, 241, 0.1);
        border: 1px solid rgba(99, 102, 241, 0.2);
        color: #818cf8;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1.25rem;
    }

    .kurs-title {
        font-size: 2.75rem;
        font-weight: 900;
        color: #fff;
        line-height: 1.15;
        margin-bottom: 1rem;
    }
    .kurs-title .accent {
        background: linear-gradient(135deg, #6366f1, #a855f7, #ec4899);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    @media (max-width: 640px) {
        .kurs-title { font-size: 2rem; }
    }

    .kurs-desc {
        color: #9ca3af;
        font-size: 1.05rem;
        line-height: 1.75;
        max-width: 600px;
    }

    /* Course Card */
    .kurs-card {
        background: rgba(255,255,255,0.025);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 1.5rem;
        overflow: hidden;
        transition: transform 0.3s, border-color 0.3s;
    }
    .kurs-card:hover {
        border-color: rgba(99, 102, 241, 0.2);
        transform: translateY(-4px);
    }
    .kurs-card-banner {
        position: relative;
        height: 220px;
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4c1d95 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .kurs-card-banner::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .kurs-card-banner-content {
        position: relative;
        z-index: 1;
        text-align: center;
    }
    .kurs-card-banner-icon {
        width: 80px;
        height: 80px;
        border-radius: 24px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        box-shadow: 0 8px 32px rgba(99, 102, 241, 0.4);
    }
    .kurs-card-banner-icon i {
        font-size: 2rem;
        color: #fff;
    }
    .kurs-card-banner-label {
        color: rgba(255,255,255,0.9);
        font-size: 1.3rem;
        font-weight: 800;
    }

    .kurs-card-body {
        padding: 2rem;
    }
    .kurs-card-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .kurs-card-tag.popular {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.2);
        color: #fbbf24;
    }
    .kurs-card-tag.free {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.2);
        color: #4ade80;
    }

    .kurs-card h3 {
        font-size: 1.35rem;
        font-weight: 800;
        color: #fff;
        margin: 1rem 0 0.75rem;
    }
    .kurs-card-desc {
        color: #9ca3af;
        font-size: 0.88rem;
        line-height: 1.7;
        margin-bottom: 1.5rem;
    }

    .kurs-features {
        list-style: none;
        padding: 0;
        margin: 0 0 1.75rem;
    }
    .kurs-features li {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
        color: #d1d5db;
        font-size: 0.88rem;
        border-bottom: 1px solid rgba(255,255,255,0.04);
    }
    .kurs-features li:last-child {
        border-bottom: none;
    }
    .kurs-features li i {
        color: #6366f1;
        font-size: 0.75rem;
        width: 20px;
        text-align: center;
        flex-shrink: 0;
    }

    /* Consultation Form */
    .consult-card {
        background: rgba(255,255,255,0.025);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 1.5rem;
        padding: 2.5rem;
        position: sticky;
        top: 5rem;
    }
    .consult-card-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .consult-card-icon {
        width: 64px;
        height: 64px;
        border-radius: 20px;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        box-shadow: 0 6px 24px rgba(34, 197, 94, 0.3);
    }
    .consult-card-icon i {
        font-size: 1.5rem;
        color: #fff;
    }
    .consult-card h2 {
        font-size: 1.3rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 0.5rem;
    }
    .consult-card-subtitle {
        color: #9ca3af;
        font-size: 0.85rem;
    }

    .consult-free-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        border-radius: 999px;
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.2);
        color: #4ade80;
        font-size: 0.78rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
    }

    .consult-form-group {
        margin-bottom: 1.25rem;
    }
    .consult-form-label {
        display: block;
        color: #9ca3af;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .consult-input {
        width: 100%;
        padding: 0.85rem 1rem;
        padding-left: 2.75rem;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 0.85rem;
        color: #fff;
        font-size: 0.95rem;
        font-weight: 500;
        outline: none;
        transition: border-color 0.3s, box-shadow 0.3s;
    }
    .consult-input:focus {
        border-color: rgba(34, 197, 94, 0.5);
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    }
    .consult-input::placeholder {
        color: #4b5563;
    }
    .consult-input-wrapper {
        position: relative;
    }
    .consult-input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        font-size: 0.9rem;
    }

    .consult-submit-btn {
        width: 100%;
        padding: 1rem;
        border-radius: 0.85rem;
        border: none;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #fff;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 20px rgba(34, 197, 94, 0.3);
        margin-top: 0.5rem;
    }
    .consult-submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 30px rgba(34, 197, 94, 0.4);
    }
    .consult-submit-btn:active {
        transform: translateY(0);
    }
    .consult-submit-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .consult-privacy {
        text-align: center;
        color: #6b7280;
        font-size: 0.72rem;
        margin-top: 1rem;
        line-height: 1.5;
    }
    .consult-privacy i {
        color: #4b5563;
        margin-right: 3px;
    }

    /* Success state */
    .consult-success {
        text-align: center;
        padding: 2rem 1rem;
    }
    .consult-success-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: rgba(34, 197, 94, 0.1);
        border: 2px solid rgba(34, 197, 94, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
    }
    .consult-success-icon i {
        font-size: 2rem;
        color: #22c55e;
    }
    .consult-success h3 {
        color: #fff;
        font-size: 1.2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }
    .consult-success p {
        color: #9ca3af;
        font-size: 0.85rem;
        line-height: 1.6;
    }

    /* Error message */
    .consult-error {
        background: rgba(239, 68, 68, 0.08);
        border: 1px solid rgba(239, 68, 68, 0.15);
        color: #fca5a5;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        font-size: 0.82rem;
        margin-bottom: 1rem;
        display: none;
    }

    /* Main grid */
    .kurs-main-grid {
        display: grid;
        grid-template-columns: 1.3fr 1fr;
        gap: 2.5rem;
        align-items: start;
    }
    @media (max-width: 900px) {
        .kurs-main-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Stats bar */
    .kurs-stats {
        display: flex;
        gap: 2rem;
        margin-top: 2.5rem;
        flex-wrap: wrap;
    }
    .kurs-stat {
        text-align: center;
    }
    .kurs-stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: #fff;
    }
    .kurs-stat-label {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 2px;
    }
    .kurs-stat-value .accent {
        background: linear-gradient(135deg, #6366f1, #a855f7);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Advantages section */
    .kurs-advantages {
        margin-top: 3rem;
    }
    .kurs-advantages h2 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 1.5rem;
    }
    .advantage-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
    @media (max-width: 768px) {
        .advantage-grid {
            grid-template-columns: 1fr;
        }
    }
    .advantage-card {
        background: rgba(255,255,255,0.025);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 1rem;
        padding: 1.5rem;
        transition: border-color 0.3s;
    }
    .advantage-card:hover {
        border-color: rgba(99, 102, 241, 0.15);
    }
    .advantage-card-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }
    .advantage-card h4 {
        color: #fff;
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    .advantage-card p {
        color: #6b7280;
        font-size: 0.8rem;
        line-height: 1.6;
    }
</style>

<div class="kurs-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="kurs-hero-grid">
            <!-- Left: Text Content -->
            <div>
                <div class="kurs-badge">
                    <i class="fa-solid fa-graduation-cap"></i> Ta'lim platformasi
                </div>
                <h1 class="kurs-title">
                    Professional <span class="accent">Kurslar</span>
                </h1>
                <p class="kurs-desc">
                    Marketplace savdosi bo'yicha bilim va tajribangizni oshiring. Mutaxassis mentorlar bilan tekin konsultatsiya oling va biznesingizni keyingi bosqichga olib chiqing.
                </p>

                <div class="kurs-stats">
                    <div class="kurs-stat">
                        <div class="kurs-stat-value"><span class="accent">500+</span></div>
                        <div class="kurs-stat-label">O'quvchilar</div>
                    </div>
                    <div class="kurs-stat">
                        <div class="kurs-stat-value"><span class="accent">98%</span></div>
                        <div class="kurs-stat-label">Mamnuniyat</div>
                    </div>
                    <div class="kurs-stat">
                        <div class="kurs-stat-value"><span class="accent">24/7</span></div>
                        <div class="kurs-stat-label">Qo'llab-quvvatlash</div>
                    </div>
                </div>
            </div>

            <!-- Right: Marketplace Logos -->
            <div class="mp-logos-section">
                <div class="mp-logos-section-title">
                    <i class="fa-solid fa-handshake" style="margin-right: 4px;"></i> Qo'llab-quvvatlanadigan platformalar
                </div>
                <div class="mp-logos-grid">
                    <div class="mp-logo-card">
                        <img src="/assets/logos/uzum-market.png?v=1" alt="Uzum Market" loading="lazy">
                        <div class="mp-logo-card-name">
                            <span class="dot" style="background: #7c3aed;"></span> Uzum Market
                        </div>
                    </div>
                    <div class="mp-logo-card">
                        <img src="/assets/logos/yandex-market.png" alt="Yandex Market" loading="lazy">
                        <div class="mp-logo-card-name">
                            <span class="dot" style="background: #f97316;"></span> Yandex Market
                        </div>
                    </div>
                    <div class="mp-logo-card">
                        <img src="/assets/logos/wildberries.png" alt="Wildberries" loading="lazy">
                        <div class="mp-logo-card-name">
                            <span class="dot" style="background: #ec4899;"></span> Wildberries
                        </div>
                    </div>
                    <div class="mp-logo-card">
                        <img src="/assets/logos/ozon.png" alt="OZON" loading="lazy">
                        <div class="mp-logo-card-name">
                            <span class="dot" style="background: #3b82f6;"></span> OZON
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
    <div class="kurs-main-grid">
        <!-- LEFT: Course Card -->
        <div>
            <div class="kurs-card">
                <div class="kurs-card-banner">
                    <div class="kurs-card-banner-content">
                        <div class="kurs-card-banner-icon">
                            <i class="fa-solid fa-store"></i>
                        </div>
                        <div class="kurs-card-banner-label">Marketpleyslarda Savdo</div>
                    </div>
                </div>
                <div class="kurs-card-body">
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <span class="kurs-card-tag popular"><i class="fa-solid fa-fire"></i> Eng Mashhur</span>
                        <span class="kurs-card-tag free"><i class="fa-solid fa-gift"></i> Tekin Konsultatsiya</span>
                    </div>
                    <h3>Marketpleyslarda Savdo Qilish Kursi</h3>
                    <p class="kurs-card-desc">
                        Uzum Market, Wildberries va boshqa marketplace platformalarida muvaffaqiyatli savdo boshlash va rivojlantirishni o'rganing. 
                        Real tajribalar va amaliy strategiyalar asosida tuzilgan to'liq kurs.
                    </p>

                    <ul class="kurs-features">
                        <li><i class="fa-solid fa-check-circle"></i> Marketplace'da hisob ochish va sozlash</li>
                        <li><i class="fa-solid fa-check-circle"></i> Mahsulot kartochkasini professional tarzda tayyorlash</li>
                        <li><i class="fa-solid fa-check-circle"></i> Raqobatbardosh narxlash strategiyalari</li>
                        <li><i class="fa-solid fa-check-circle"></i> SEO va qidiruv optimizatsiyasi</li>
                        <li><i class="fa-solid fa-check-circle"></i> Reklama va marketing usullari</li>
                        <li><i class="fa-solid fa-check-circle"></i> Logistika va FBS/FBO tizimini tushunish</li>
                        <li><i class="fa-solid fa-check-circle"></i> Soliq va hujjatlar bilan ishlash</li>
                        <li><i class="fa-solid fa-check-circle"></i> Analitika va sotuvlarni tahlil qilish</li>
                        <li><i class="fa-solid fa-check-circle"></i> Mentor bilan shaxsiy maslahatlar</li>
                    </ul>
                </div>
            </div>

            <!-- Advantages -->
            <div class="kurs-advantages">
                <h2><i class="fa-solid fa-star" style="color: #fbbf24; margin-right: 8px;"></i> Nima uchun biz?</h2>
                <div class="advantage-grid">
                    <div class="advantage-card">
                        <div class="advantage-card-icon" style="background: rgba(99, 102, 241, 0.1); color: #818cf8;">
                            <i class="fa-solid fa-chalkboard-user"></i>
                        </div>
                        <h4>Amaliy ta'lim</h4>
                        <p>Nazariy bilimlar emas, real platformalarda amaliy tajriba orqali o'rgatamiz.</p>
                    </div>
                    <div class="advantage-card">
                        <div class="advantage-card-icon" style="background: rgba(34, 197, 94, 0.1); color: #4ade80;">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <h4>24/7 Qo'llab-quvvatlash</h4>
                        <p>Savollaringizga tez va professional javoblar. Mentor har doim aloqada.</p>
                    </div>
                    <div class="advantage-card">
                        <div class="advantage-card-icon" style="background: rgba(245, 158, 11, 0.1); color: #fbbf24;">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <h4>Natijaga yo'naltirilgan</h4>
                        <p>O'quvchilarimizning 90% birinchi oyda daromad olishni boshlaydi.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: Consultation Form -->
        <div>
            <div class="consult-card" id="consult-card">
                <div id="consult-form-section">
                    <div class="consult-card-header">
                        <div class="consult-card-icon">
                            <i class="fa-solid fa-phone-volume"></i>
                        </div>
                        <h2>Tekin Konsultatsiya Olish</h2>
                        <p class="consult-card-subtitle">Mutaxassis bilan bog'lanish uchun ma'lumotlaringizni qoldiring</p>
                    </div>

                    <div style="text-align: center;">
                        <span class="consult-free-tag">
                            <i class="fa-solid fa-gift"></i> 100% Bepul
                        </span>
                    </div>

                    <div class="consult-error" id="consult-error"></div>

                    <form id="consult-form" onsubmit="submitConsultation(event)">
                        <div class="consult-form-group">
                            <label class="consult-form-label">Ismingiz</label>
                            <div class="consult-input-wrapper">
                                <i class="fa-solid fa-user consult-input-icon"></i>
                                <input type="text" id="consult-name" class="consult-input" placeholder="To'liq ismingiz" required minlength="2" autocomplete="name">
                            </div>
                        </div>

                        <div class="consult-form-group">
                            <label class="consult-form-label">Telefon raqamingiz</label>
                            <div class="consult-input-wrapper">
                                <i class="fa-solid fa-phone consult-input-icon"></i>
                                <input type="tel" id="consult-phone" class="consult-input" placeholder="+998 90 123 45 67" required autocomplete="tel">
                            </div>
                        </div>

                        <button type="submit" class="consult-submit-btn" id="consult-submit-btn">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span id="consult-submit-text">Konsultatsiya olish</span>
                        </button>
                    </form>

                    <p class="consult-privacy">
                        <i class="fa-solid fa-shield-halved"></i> Ma'lumotlaringiz himoyalangan. Biz ularni uchinchi shaxslarga bermayimiz.
                    </p>
                </div>

                <!-- Success State -->
                <div id="consult-success-section" style="display: none;">
                    <div class="consult-success">
                        <div class="consult-success-icon">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <h3>So'rovingiz yuborildi!</h3>
                        <p>Mutaxassisimiz tez orada siz bilan bog'lanadi. Qo'ng'iroqni kuting!</p>
                        <button onclick="resetConsultForm()" style="margin-top: 1.5rem; padding: 0.7rem 1.5rem; border-radius: 0.75rem; border: 1px solid rgba(255,255,255,0.1); background: transparent; color: #9ca3af; font-size: 0.85rem; cursor: pointer; transition: all 0.3s;">
                            <i class="fa-solid fa-arrow-left" style="margin-right: 6px;"></i> Yangi so'rov yuborish
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function submitConsultation(e) {
    e.preventDefault();

    const name = document.getElementById('consult-name').value.trim();
    const phone = document.getElementById('consult-phone').value.trim();
    const errorEl = document.getElementById('consult-error');
    const btn = document.getElementById('consult-submit-btn');
    const btnText = document.getElementById('consult-submit-text');

    // Validatsiya
    errorEl.style.display = 'none';

    if (!name || name.length < 2) {
        errorEl.textContent = 'Ismingizni to\'g\'ri kiriting (kamida 2 belgi)';
        errorEl.style.display = 'block';
        return;
    }
    if (!phone || phone.replace(/[^0-9+]/g, '').length < 9) {
        errorEl.textContent = 'Telefon raqamni to\'g\'ri kiriting';
        errorEl.style.display = 'block';
        return;
    }

    // Loading
    btn.disabled = true;
    btnText.textContent = 'Yuborilmoqda...';

    try {
        const resp = await fetch('/api/consultation.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, phone })
        });

        const data = await resp.json();

        if (data.success) {
            document.getElementById('consult-form-section').style.display = 'none';
            document.getElementById('consult-success-section').style.display = 'block';
        } else {
            errorEl.textContent = data.error || 'Xatolik yuz berdi';
            errorEl.style.display = 'block';
        }
    } catch (err) {
        errorEl.textContent = 'Tarmoq xatoligi. Qaytadan urinib ko\'ring.';
        errorEl.style.display = 'block';
    } finally {
        btn.disabled = false;
        btnText.textContent = 'Konsultatsiya olish';
    }
}

function resetConsultForm() {
    document.getElementById('consult-form').reset();
    document.getElementById('consult-error').style.display = 'none';
    document.getElementById('consult-form-section').style.display = 'block';
    document.getElementById('consult-success-section').style.display = 'none';
}
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
