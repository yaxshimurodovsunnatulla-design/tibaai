<?php
require_once __DIR__ . '/../api/config.php';
$pageTitle = 'Kapital Bank – Biznes Hisob Ochish | Tiba AI';
$pageDescription = 'Kapital Bank orqali biznes hisob varag\'ini onlayn oching. YaTT va MChJ uchun qadamba-qadam yo\'riqnoma, narxlar va afzalliklar.';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<style>
    .kb-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        color: #9ca3af;
        margin-bottom: 1.5rem;
    }
    .kb-breadcrumb a { color: #9ca3af; text-decoration: none; transition: color 0.2s; }
    .kb-breadcrumb a:hover { color: #fff; }
    .kb-breadcrumb .sep { color: #4b5563; }
    .kb-breadcrumb .cur { color: #4ade80; font-weight: 600; }

    .kb-header-icon {
        width: 52px; height: 52px;
        border-radius: 16px;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; color: #fff;
        box-shadow: 0 4px 20px rgba(34,197,94,0.35);
        flex-shrink: 0;
    }
    .kb-header h1 {
        font-size: 1.9rem; font-weight: 800; color: #fff;
    }
    .kb-subtitle {
        color: #9ca3af; font-size: 0.9rem;
        line-height: 1.65; max-width: 680px;
        margin-top: 0.5rem;
    }

    .kb-badges {
        display: flex; flex-wrap: wrap; gap: 0.5rem;
        margin-top: 0.9rem;
    }
    .kb-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: 999px;
        font-size: 0.75rem; font-weight: 600;
    }
    .kb-badge.green { background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.25); color: #4ade80; }
    .kb-badge.blue  { background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.25); color: #60a5fa; }
    .kb-badge.amber { background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.25); color: #fbbf24; }

    /* Layout */
    .kb-layout {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 1.75rem;
        margin-top: 2rem;
    }
    @media (max-width: 900px) {
        .kb-layout { grid-template-columns: 1fr; }
    }

    /* Card base */
    .kb-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 1.25rem;
        padding: 2rem;
        backdrop-filter: blur(10px);
    }
    .kb-card.green-border {
        border-color: rgba(34,197,94,0.18);
    }
    .kb-card-title {
        font-size: 1.05rem; font-weight: 700; color: #fff;
        margin-bottom: 1.5rem;
        display: flex; align-items: center; gap: 0.6rem;
    }
    .kb-card-title i { font-size: 1rem; }

    /* Stepper */
    .kb-stepper { display: flex; flex-direction: column; gap: 0; }
    .kb-step {
        display: flex; align-items: flex-start; gap: 1rem;
        position: relative;
    }
    .kb-step:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 18px; top: 42px;
        width: 2px; height: calc(100% - 10px);
        background: rgba(255,255,255,0.07);
    }
    .kb-step-icon-wrap {
        flex-shrink: 0; width: 38px; height: 38px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; color: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        position: relative; z-index: 1;
    }
    .kb-step-body { padding-bottom: 1.75rem; flex: 1; }
    .kb-step-num {
        font-size: 0.68rem; font-weight: 700; color: #6b7280;
        text-transform: uppercase; letter-spacing: 0.08em;
        margin-bottom: 2px;
    }
    .kb-step-title { font-size: 0.95rem; font-weight: 700; color: #fff; margin-bottom: 4px; }
    .kb-step-desc { font-size: 0.82rem; color: #9ca3af; line-height: 1.6; }
    .kb-step-tip {
        display: inline-flex; align-items: center; gap: 5px;
        margin-top: 8px; padding: 4px 10px; border-radius: 8px;
        background: rgba(255,255,255,0.05);
        font-size: 0.75rem; color: #6b7280;
    }

    /* CTA buttons */
    .kb-cta-group { display: flex; flex-direction: column; gap: 0.75rem; margin-top: 1.5rem; }
    .kb-btn-primary {
        display: flex; align-items: center; justify-content: center; gap: 0.6rem;
        padding: 0.9rem 1.5rem; border-radius: 0.875rem;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #fff; font-weight: 700; font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.2s;
        box-shadow: 0 4px 20px rgba(34,197,94,0.25);
    }
    .kb-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(34,197,94,0.35);
        background: linear-gradient(135deg, #4ade80, #22c55e);
    }
    .kb-btn-secondary {
        display: flex; align-items: center; justify-content: center; gap: 0.6rem;
        padding: 0.9rem 1.5rem; border-radius: 0.875rem;
        border: 1px solid rgba(255,255,255,0.1);
        color: #d1d5db; font-weight: 600; font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.2s;
    }
    .kb-btn-secondary:hover {
        border-color: rgba(34,197,94,0.35);
        color: #fff;
        background: rgba(34,197,94,0.05);
    }

    /* RIGHT sidebar */
    .kb-sidebar { display: flex; flex-direction: column; gap: 1.25rem; }

    /* Price table */
    .kb-price-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.65rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .kb-price-row:last-child { border-bottom: none; }
    .kb-price-label { font-size: 0.82rem; color: #9ca3af; }
    .kb-price-val { font-size: 0.88rem; font-weight: 700; }
    .kb-price-val.free { color: #4ade80; }
    .kb-price-val.paid { color: #fff; }

    /* Benefits */
    .kb-benefit {
        display: flex; align-items: center; gap: 0.75rem;
        padding: 0.55rem 0;
    }
    .kb-benefit i { width: 16px; text-align: center; flex-shrink: 0; font-size: 0.9rem; }
    .kb-benefit span { font-size: 0.83rem; color: #d1d5db; }

    /* FAQ */
    .kb-faq-item {
        border-bottom: 1px solid rgba(255,255,255,0.06);
        padding: 1rem 0;
        cursor: pointer;
    }
    .kb-faq-item:first-child { padding-top: 0; }
    .kb-faq-item:last-child { border-bottom: none; padding-bottom: 0; }
    .kb-faq-q {
        display: flex; align-items: center; justify-content: space-between;
        gap: 1rem;
    }
    .kb-faq-q-text { font-size: 0.88rem; font-weight: 600; color: #e5e7eb; }
    .kb-faq-icon { color: #6b7280; font-size: 0.8rem; transition: transform 0.25s; flex-shrink: 0; }
    .kb-faq-icon.open { transform: rotate(180deg); color: #4ade80; }
    .kb-faq-a {
        font-size: 0.82rem; color: #9ca3af; line-height: 1.65;
        max-height: 0; overflow: hidden;
        transition: max-height 0.3s ease, margin-top 0.3s ease;
    }
    .kb-faq-a.open { max-height: 200px; margin-top: 0.65rem; }

    /* Notice */
    .kb-notice {
        display: flex; align-items: flex-start; gap: 0.75rem;
        padding: 1rem 1.25rem; border-radius: 1rem;
        background: rgba(34,197,94,0.05);
        border: 1px solid rgba(34,197,94,0.12);
        margin-top: 1.5rem;
        font-size: 0.8rem; color: #9ca3af; line-height: 1.6;
    }
    .kb-notice i { color: #4ade80; margin-top: 2px; flex-shrink: 0; }

    /* Lead form */
    .kb-form-group { margin-bottom: 1rem; }
    .kb-form-label {
        display: block; font-size: 0.78rem; font-weight: 600;
        color: #9ca3af; margin-bottom: 0.4rem;
        text-transform: uppercase; letter-spacing: 0.05em;
    }
    .kb-form-input {
        width: 100%; padding: 0.75rem 1rem;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 0.75rem;
        color: #fff; font-size: 0.9rem; font-weight: 500;
        outline: none; transition: border-color 0.25s, box-shadow 0.25s;
        box-sizing: border-box;
    }
    .kb-form-input::placeholder { color: #4b5563; }
    .kb-form-input:focus {
        border-color: rgba(34,197,94,0.5);
        box-shadow: 0 0 0 3px rgba(34,197,94,0.1);
    }
    .kb-form-input.error {
        border-color: rgba(239,68,68,0.5);
        box-shadow: 0 0 0 3px rgba(239,68,68,0.08);
    }
    .kb-form-err {
        font-size: 0.73rem; color: #f87171;
        margin-top: 0.3rem; display: none;
    }
    .kb-form-submit {
        width: 100%; padding: 0.85rem;
        border-radius: 0.875rem;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #fff; font-weight: 700; font-size: 0.9rem;
        border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        transition: all 0.2s;
        box-shadow: 0 4px 15px rgba(34,197,94,0.2);
    }
    .kb-form-submit:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 6px 22px rgba(34,197,94,0.3);
    }
    .kb-form-submit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
    .kb-form-success {
        display: none; text-align: center; padding: 1.5rem 1rem;
    }
    .kb-form-success-icon {
        width: 52px; height: 52px; border-radius: 50%;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; color: #fff; margin: 0 auto 0.75rem;
        box-shadow: 0 0 0 8px rgba(34,197,94,0.12);
    }
    .kb-form-success h4 { font-size: 0.95rem; font-weight: 700; color: #fff; margin-bottom: 0.35rem; }
    .kb-form-success p { font-size: 0.8rem; color: #9ca3af; line-height: 1.55; }
</style>

<div class="py-8 sm:py-14 relative overflow-hidden">
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-green-600/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-emerald-600/8 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative">

        <!-- Breadcrumb -->
        <div class="kb-breadcrumb">
            <a href="/">Bosh sahifa</a>
            <span class="sep">›</span>
            <a href="/instrumentlar">Instrumentlar</a>
            <span class="sep">›</span>
            <span class="cur">Kapital Bank – Hisob Ochish</span>
        </div>

        <!-- Header -->
        <div style="display:flex; align-items:flex-start; gap:1rem;" class="kb-header">
            <div class="kb-header-icon">
                <i class="fa-solid fa-landmark"></i>
            </div>
            <div>
                <h1>Kapital Bank — Biznes Hisob Ochish</h1>
                <p class="kb-subtitle">
                    O'zbekistonning yetakchi banklaridan biri orqali biznes hisob varag'ingizni onlayn oching.
                    YaTT va MChJ egalariga mo'ljallangan tezkor, hujjatsiz jarayon.
                </p>
                <div class="kb-badges">
                    <span class="kb-badge green"><i class="fa-solid fa-shield-halved"></i> Rasmiy bank</span>
                    <span class="kb-badge blue"><i class="fa-solid fa-wifi"></i> To'liq onlayn</span>
                    <span class="kb-badge amber"><i class="fa-solid fa-bolt"></i> 1-2 ish kuni</span>
                </div>
            </div>
        </div>

        <!-- Main layout -->
        <div class="kb-layout">

            <!-- LEFT: Steps + CTA -->
            <div style="display:flex; flex-direction:column; gap:1.5rem;">

                <!-- Steps Card -->
                <div class="kb-card kb-card green-border">
                    <div class="kb-card-title">
                        <i class="fa-solid fa-list-check" style="color:#4ade80;"></i>
                        Hisob ochish qadamlari
                    </div>
                    <div class="kb-stepper">
                        <?php
                        $steps = [
                            [
                                'num'   => 'Qadam 1',
                                'icon'  => 'fa-solid fa-user-plus',
                                'grad'  => 'linear-gradient(135deg,#22c55e,#16a34a)',
                                'shadow'=> 'rgba(34,197,94,0.3)',
                                'title' => 'Kapital Business saytiga o\'ting',
                                'desc'  => 'kapitalbank.uz/business yoki maxsus mobil ilovani yuklab oling. Telefon raqamingiz va elektron pochtangizni kiritib, tezkor ro\'yxatdan o\'ting.',
                                'tip'   => 'App Store yoki Google Play\'dan "Kapital Business" ilovasini yuklab oling',
                            ],
                            [
                                'num'   => 'Qadam 2',
                                'icon'  => 'fa-solid fa-id-card',
                                'grad'  => 'linear-gradient(135deg,#3b82f6,#2563eb)',
                                'shadow'=> 'rgba(59,130,246,0.3)',
                                'title' => 'Shaxsiy ma\'lumotlarni to\'ldiring',
                                'desc'  => 'Passport seriya va raqami, PINFL (Shaxsiy identifikatsiya raqami) va kontakt ma\'lumotlaringizni kiriting. Hujjatlarning rasmini yuklash talab etiladi.',
                                'tip'   => 'PINFL raqamingizni pasportingizdan yoki My Gov ilovasidan topishingiz mumkin',
                            ],
                            [
                                'num'   => 'Qadam 3',
                                'icon'  => 'fa-solid fa-building',
                                'grad'  => 'linear-gradient(135deg,#8b5cf6,#7c3aed)',
                                'shadow'=> 'rgba(139,92,246,0.3)',
                                'title' => 'Korxona ma\'lumotlarini kiriting',
                                'desc'  => 'YaTT yoki MChJ turini tanlang. STIR raqami, davlat ro\'yxatidan o\'tish guvohnomasi (patentnameringiz) va faoliyat yo\'nalishini ko\'rsating.',
                                'tip'   => 'STIR raqamini Soliq qo\'mitasi portalidan (my.soliq.uz) olishingiz mumkin',
                            ],
                            [
                                'num'   => 'Qadam 4',
                                'icon'  => 'fa-solid fa-file-signature',
                                'grad'  => 'linear-gradient(135deg,#f59e0b,#d97706)',
                                'shadow'=> 'rgba(245,158,11,0.3)',
                                'title' => 'Shartnomani imzolang',
                                'desc'  => 'Hisob ochish shartnomasini onlayn ko\'rib chiqing va elektron imzo bilan tasdiqlang. Bank xodimi telefon orqali qo\'shimcha tekshirishi mumkin.',
                                'tip'   => 'E-imzo (ERI) bo\'lmasa, oddiy SMS-tasdiqlash ham qabul qilinadi',
                            ],
                            [
                                'num'   => 'Qadam 5',
                                'icon'  => 'fa-solid fa-circle-check',
                                'grad'  => 'linear-gradient(135deg,#10b981,#059669)',
                                'shadow'=> 'rgba(16,185,129,0.3)',
                                'title' => 'Hisobni aktivlashtiring',
                                'desc'  => '1-2 ish kuni ichida ariza ko\'rib chiqiladi. SMS va email orqali tasdiqlash xabari keladi. Shundan so\'ng hisob to\'liq faol bo\'ladi.',
                                'tip'   => null,
                            ],
                        ];
                        foreach ($steps as $i => $s): ?>
                        <div class="kb-step">
                            <div class="kb-step-icon-wrap" style="background:<?= $s['grad'] ?>;box-shadow:0 4px 12px <?= $s['shadow'] ?>;">
                                <i class="<?= $s['icon'] ?>"></i>
                            </div>
                            <div class="kb-step-body">
                                <div class="kb-step-num"><?= $s['num'] ?></div>
                                <div class="kb-step-title"><?= $s['title'] ?></div>
                                <div class="kb-step-desc"><?= $s['desc'] ?></div>
                                <?php if ($s['tip']): ?>
                                <div class="kb-step-tip">
                                    <i class="fa-solid fa-lightbulb" style="color:#fbbf24;font-size:0.7rem;"></i>
                                    <?= $s['tip'] ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="kb-cta-group">
                        <a href="https://kabinet.kapitalbank.uz/business" target="_blank" rel="noopener noreferrer" class="kb-btn-primary">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            Kapital Business — Hisob ochish
                        </a>
                        <a href="https://kapitalbank.uz" target="_blank" rel="noopener noreferrer" class="kb-btn-secondary">
                            <i class="fa-solid fa-globe"></i>
                            Rasmiy veb-saytga o'tish
                        </a>
                    </div>
                </div>

                <!-- FAQ -->
                <div class="kb-card">
                    <div class="kb-card-title">
                        <i class="fa-solid fa-circle-question" style="color:#60a5fa;"></i>
                        Tez-tez beriladigan savollar
                    </div>
                    <div id="kb-faq">
                        <?php
                        $faqs = [
                            ['q'=>'Hisob ochish uchun ofisga borish kerakmi?', 'a'=>'Yo\'q, butun jarayon 100% onlayn amalga oshiriladi. Hisob ochishdan tortib kartani olishgacha hammasi ilovadan yoki saytdan bajariladi.'],
                            ['q'=>'Qanday hujjatlar kerak?', 'a'=>'Asosan passport va STIR raqami yetarli. MChJ uchun qo\'shimcha ravishda davlat ro\'yxatidan o\'tish guvohnomasi (PDF) talab qilinishi mumkin.'],
                            ['q'=>'Minimal depozit bormi?', 'a'=>'Hisob ochish uchun minimal depozit talab etilmaydi. Hisob ochilgandan keyin istalgan miqdorni kiritishingiz mumkin.'],
                            ['q'=>'Internet-banking qanday ishlaydi?', 'a'=>'Kapital Business mobil ilovasi va veb-kabineti orqali barcha operatsiyalarni bajarishingiz, hisobotlar olishingiz va to\'lovlar amalga oshirishingiz mumkin.'],
                            ['q'=>'Bir nechta hisob ochsa bo\'ladimi?', 'a'=>'Ha, bir korxona uchun bir nechta hisob varog\'i (so\'mda va valutada) ochish imkoniyati mavjud.'],
                        ];
                        foreach ($faqs as $fi => $faq): ?>
                        <div class="kb-faq-item" onclick="toggleFaq(<?= $fi ?>)">
                            <div class="kb-faq-q">
                                <span class="kb-faq-q-text"><?= $faq['q'] ?></span>
                                <i class="fa-solid fa-chevron-down kb-faq-icon" id="kb-faq-icon-<?= $fi ?>"></i>
                            </div>
                            <div class="kb-faq-a" id="kb-faq-a-<?= $fi ?>"><?= $faq['a'] ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

            <!-- RIGHT: Sidebar -->
            <div class="kb-sidebar">

                <!-- Lead Form -->
                <div class="kb-card" style="border-color:rgba(34,197,94,0.2);">
                    <div class="kb-card-title">
                        <i class="fa-solid fa-paper-plane" style="color:#4ade80;"></i>
                        Murojaat qoldiring
                    </div>
                    <p style="font-size:0.8rem;color:#9ca3af;margin-bottom:1.25rem;line-height:1.55;">Hisob ochishda yordam kerakmi? Ma'lumotlaringizni qoldiring, mutaxassis aloqaga chiqadi.</p>

                    <!-- Form -->
                    <form id="kb-lead-form" onsubmit="submitKbForm(event)" novalidate>
                        <div class="kb-form-group">
                            <label class="kb-form-label" for="kb-company">Korxona nomi</label>
                            <input type="text" id="kb-company" name="company" class="kb-form-input"
                                   placeholder="Masalan: Yulduz Savdo MChJ" autocomplete="organization">
                            <div class="kb-form-err" id="kb-company-err">Korxona nomini kiriting</div>
                        </div>
                        <div class="kb-form-group">
                            <label class="kb-form-label" for="kb-phone">Telefon raqam</label>
                            <input type="tel" id="kb-phone" name="phone" class="kb-form-input"
                                   placeholder="+998 xx xxx xx xx" autocomplete="tel" maxlength="17">
                            <div class="kb-form-err" id="kb-phone-err">To'g'ri telefon raqam kiriting</div>
                        </div>
                        <button type="submit" class="kb-form-submit" id="kb-submit-btn">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span id="kb-btn-text">Yuborish</span>
                        </button>
                    </form>

                    <!-- Success state -->
                    <div class="kb-form-success" id="kb-form-success">
                        <div class="kb-form-success-icon">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <h4>Murojaatingiz qabul qilindi!</h4>
                        <p>Tez orada mutaxassis siz bilan bog'lanadi. Rahmat!</p>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="kb-card">
                    <div class="kb-card-title">
                        <i class="fa-solid fa-tag" style="color:#4ade80;"></i>
                        Xizmat tariflari
                    </div>
                    <?php
                    $prices = [
                        ['label'=>'Hisob ochish',           'val'=>'Bepul',          'free'=>true],
                        ['label'=>'Oylik xizmat to\'lovi',  'val'=>'30 000 so\'m',   'free'=>false],
                        ['label'=>'Ichki o\'tkazmalar',     'val'=>'Bepul',          'free'=>true],
                        ['label'=>'Tashqi o\'tkazmalar',    'val'=>'0.1% (min 1 000 so\'m)', 'free'=>false],
                        ['label'=>'Biznes karta chiqarish', 'val'=>'Bepul',          'free'=>true],
                        ['label'=>'Karta yillik xizmati',   'val'=>'50 000 so\'m',   'free'=>false],
                        ['label'=>'SMS xabarnoma',          'val'=>'5 000 so\'m/oy', 'free'=>false],
                    ];
                    foreach ($prices as $p): ?>
                    <div class="kb-price-row">
                        <span class="kb-price-label"><?= $p['label'] ?></span>
                        <span class="kb-price-val <?= $p['free'] ? 'free' : 'paid' ?>"><?= $p['val'] ?></span>
                    </div>
                    <?php endforeach; ?>
                    <div class="kb-notice" style="margin-top:1rem;">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>Narxlar o'zgarishi mumkin. Aniq ma'lumot uchun rasmiy saytni tekshiring.</span>
                    </div>
                </div>

                <!-- Benefits -->
                <div class="kb-card">
                    <div class="kb-card-title">
                        <i class="fa-solid fa-star" style="color:#fbbf24;"></i>
                        Asosiy afzalliklar
                    </div>
                    <?php
                    $benefits = [
                        ['icon'=>'fa-solid fa-bolt',            'color'=>'#fbbf24', 'text'=>'1-2 ish kunida hisob ochiladi'],
                        ['icon'=>'fa-solid fa-mobile-screen',   'color'=>'#60a5fa', 'text'=>'Kapital Business mobil ilovasi'],
                        ['icon'=>'fa-solid fa-globe',           'color'=>'#4ade80', 'text'=>'24/7 onlayn bank xizmatlari'],
                        ['icon'=>'fa-solid fa-credit-card',     'color'=>'#c084fc', 'text'=>'Biznes kartalar chiqarish'],
                        ['icon'=>'fa-solid fa-chart-bar',       'color'=>'#f472b6', 'text'=>'Kengaytirilgan moliyaviy hisobot'],
                        ['icon'=>'fa-solid fa-handshake',       'color'=>'#34d399', 'text'=>'Kreditlash imkoniyatlari'],
                        ['icon'=>'fa-solid fa-shield-halved',   'color'=>'#a78bfa', 'text'=>'Markaziy bank kafolatlangan xavfsizlik'],
                        ['icon'=>'fa-solid fa-headset',         'color'=>'#22d3ee', 'text'=>'24/7 qo\'llab-quvvatlash xizmati'],
                    ];
                    foreach ($benefits as $b): ?>
                    <div class="kb-benefit">
                        <i class="<?= $b['icon'] ?>" style="color:<?= $b['color'] ?>;"></i>
                        <span><?= $b['text'] ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Quick contact -->
                <div class="kb-card" style="border-color:rgba(34,197,94,0.15);">
                    <div class="kb-card-title">
                        <i class="fa-solid fa-phone" style="color:#4ade80;"></i>
                        Aloqa
                    </div>
                    <div style="display:flex;flex-direction:column;gap:0.85rem;">
                        <a href="tel:1309" style="display:flex;align-items:center;gap:0.65rem;text-decoration:none;padding:0.7rem 1rem;border-radius:0.75rem;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.06);transition:all 0.2s;" onmouseover="this.style.borderColor='rgba(34,197,94,0.3)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'">
                            <i class="fa-solid fa-phone" style="color:#4ade80;"></i>
                            <div>
                                <div style="font-size:0.78rem;color:#9ca3af;">Call markaz</div>
                                <div style="font-size:0.95rem;font-weight:700;color:#fff;">1309</div>
                            </div>
                        </a>
                        <a href="https://t.me/kapitalbank_uz" target="_blank" rel="noopener noreferrer" style="display:flex;align-items:center;gap:0.65rem;text-decoration:none;padding:0.7rem 1rem;border-radius:0.75rem;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.06);transition:all 0.2s;" onmouseover="this.style.borderColor='rgba(34,197,94,0.3)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'">
                            <i class="fa-brands fa-telegram" style="color:#60a5fa;"></i>
                            <div>
                                <div style="font-size:0.78rem;color:#9ca3af;">Telegram</div>
                                <div style="font-size:0.9rem;font-weight:700;color:#fff;">@kapitalbank_uz</div>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- Bottom notice -->
        <div class="kb-notice" style="margin-top:2rem;">
            <i class="fa-solid fa-circle-info"></i>
            <span>
                Tiba AI platformasi Kapital Bank bilan rasmiy hamkorlikda emas. Ushbu sahifadagi ma'lumotlar ommaviq manbalar asosida tayyorlangan va faqat yo'riqnoma sifatida taqdim etiladi.
                Aniq va eng yangi ma'lumotlar uchun <a href="https://kapitalbank.uz" target="_blank" style="color:#4ade80;">kapitalbank.uz</a> saytiga murojaat qiling.
            </span>
        </div>

    </div>
</div>

<script>
/* ── FAQ toggle ── */
function toggleFaq(i) {
    const ans  = document.getElementById('kb-faq-a-' + i);
    const icon = document.getElementById('kb-faq-icon-' + i);
    const isOpen = ans.classList.contains('open');
    document.querySelectorAll('.kb-faq-a').forEach(el => el.classList.remove('open'));
    document.querySelectorAll('.kb-faq-icon').forEach(el => el.classList.remove('open'));
    if (!isOpen) { ans.classList.add('open'); icon.classList.add('open'); }
}

/* ── Phone auto-format: +998 xx xxx xx xx ── */
document.getElementById('kb-phone').addEventListener('input', function() {
    let v = this.value.replace(/\D/g, '');
    if (v.startsWith('998')) v = v.slice(3);
    if (v.length > 9) v = v.slice(0, 9);
    let fmt = '+998';
    if (v.length > 0) fmt += ' ' + v.slice(0, 2);
    if (v.length > 2) fmt += ' ' + v.slice(2, 5);
    if (v.length > 5) fmt += ' ' + v.slice(5, 7);
    if (v.length > 7) fmt += ' ' + v.slice(7, 9);
    this.value = fmt;
    this.classList.remove('error');
    document.getElementById('kb-phone-err').style.display = 'none';
});

/* ── Form submit ── */
function submitKbForm(e) {
    e.preventDefault();
    const company = document.getElementById('kb-company').value.trim();
    const phone   = document.getElementById('kb-phone').value.trim();
    const digits  = phone.replace(/\D/g, '');
    let valid = true;

    // Validate company
    const compEl  = document.getElementById('kb-company');
    const compErr = document.getElementById('kb-company-err');
    if (!company) {
        compEl.classList.add('error');
        compErr.style.display = 'block';
        valid = false;
    } else {
        compEl.classList.remove('error');
        compErr.style.display = 'none';
    }

    // Validate phone (998 + 9 digits = 12)
    const phoneEl  = document.getElementById('kb-phone');
    const phoneErr = document.getElementById('kb-phone-err');
    if (digits.length < 12) {
        phoneEl.classList.add('error');
        phoneErr.style.display = 'block';
        valid = false;
    } else {
        phoneEl.classList.remove('error');
        phoneErr.style.display = 'none';
    }

    if (!valid) return;

    // Submit
    const btn  = document.getElementById('kb-submit-btn');
    const txt  = document.getElementById('kb-btn-text');
    btn.disabled = true;
    txt.textContent = 'Yuborilmoqda...';

    fetch('/api/kapital-bank-lead.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ company, phone })
    })
    .then(r => r.json().catch(() => ({ ok: true })))
    .finally(() => {
        document.getElementById('kb-lead-form').style.display = 'none';
        document.getElementById('kb-form-success').style.display = 'block';
    });
}
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
