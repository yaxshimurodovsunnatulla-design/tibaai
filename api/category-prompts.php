<?php
/**
 * Tiba AI — Kategoriya bo'yicha random dizayn promptlari
 * Elektronika: 7 | Go'zallik: 6 | Sog'liq: 5 | Uy: 4 | Boshqalar: 3
 * Har safar: random variant + random layout + random BG
 * Jami: 2000+ xilma-xil kombinatsiya
 */

function getCategoryDesignPrompt($category, $prompt) {

    // Bazaviy dizayn qoidalari — BARCHA dizaynlarga qo'shiladi
    $salesRules = "CRITICAL DESIGN RULES (MANDATORY):
1. ⚠️ PRODUCT MUST BE IDENTICAL TO ORIGINAL — This is the #1 most important rule. The product in the final image MUST look EXACTLY like the uploaded photo. Do NOT change the product's shape, color, proportions, label, logo, text on packaging, bottle shape, cap design, or any visual detail. Copy the product PIXEL-PERFECT. If you change even one detail of the product appearance, the entire design is FAILED and REJECTED.
2. PRODUCT IS THE HERO — product must occupy at least 40-50% of image, perfectly sharp, ultra high resolution, faithful to original photo.
3. KEY SPEC IN GIANT TEXT — The single most important feature (volume '250мл', camera '108MP', material '100% paxta', size '24/26 sm') MUST be in EXTREMELY LARGE bold text, at least 3x bigger than body text. This creates instant recognition.
4. READABLE IN 2 SECONDS — A customer must understand the product value in 2 seconds. Maximum 5-6 words per badge. Short. Punchy. Clear.
5. BOLD CONTRAST — Always use high-contrast text: white/light text on dark backgrounds OR dark text on light backgrounds. Never blurry or low-contrast.
6. SHOW REAL SPECS ONLY — Only display real product specifications: material, size, weight, capacity, certifications, warranty period. NEVER add fake ratings, fake review counts, fake 'bestseller' labels, or marketplace names.
7. NO FAKE MARKETING — ABSOLUTELY FORBIDDEN WORDS (never render on image): 'buyurtma bering', 'xarid qiling', 'hoziroq oling', 'top tovar', 'yangi', 'NEW', 'hit', 'bestseller', 'TOP', 'Uzum marketdan', 'Uzum', 'Ozon', 'Wildberries', 'bepul yetkazish', star ratings (4.8, 4.9 etc), 'X+ sotilgan', 'chegirma', 'aksiya', 'eng arzon', 'kafolat', 'sifat kafolati', 'yuqori sifat', 'premium sifat', 'premium', 'haqiqiy', 'original', '100% original', 'eng yaxshi', 'tavsiya etamiz', 'ishonch', 'sifatli', 'garantiya', any marketplace name, any call-to-action phrase, any trust/quality buzzword. ONLY show real product specifications (material, size, weight, capacity).
8. FEATURE ICONS — Each feature MUST have a matching small icon on the left side. Icon + Bold value + thin description below.
9. CLEAN LAYOUT — No visual clutter. Strategic white space. Every element has purpose.";

    // 6 ta random layout varianti
    $layouts = [
        'LAYOUT: Product centered. Specs listed on LEFT with icons vertically. Key giant stat on RIGHT. Product name in BOLD at very top.',
        'LAYOUT: Product slightly RIGHT (55%). Left side: product name + features stacked vertically. Volume/size in large circle at bottom-right corner.',
        'LAYOUT: Product at top-center hero. Below: 2-column grid of icon+text badges. Bottom banner with primary selling point in giant text.',
        'LAYOUT: Editorial split — product RIGHT 55%, info LEFT 45% with product name, giant key spec, stacked features. Gold or colored divider line.',
        'LAYOUT: Product at dramatic angle filling center. Feature badges as floating glassmorphism cards around product. Giant spec text overlapping slightly.',
        'LAYOUT: Product at bottom-center. Features radiate upward. Name at top bold. Trust badges in corners. Size in bold strip at very bottom.',
    ];

    // 6 ta random fon varianti
    $backgrounds = [
        'BACKGROUND: Solid gradient matching product brand color — darker at bottom, lighter at top. Professional product photography feel.',
        'BACKGROUND: Dark dramatic — charcoal/black with cinematic spotlight on product from above. Premium moody feel.',
        'BACKGROUND: Clean studio white/light-gray with soft realistic shadows. Commercial catalog aesthetic.',
        'BACKGROUND: Dynamic effects — water splash, particle burst, or smoke matching product theme radiating behind product.',
        'BACKGROUND: Textured surface — product placed on real surface (marble, wood, concrete, fabric) matching category. Lifestyle shot.',
        'BACKGROUND: Bold geometric shapes and circles in brand colors behind product. Modern trendy marketplace card.',
    ];

    $selectedLayout = $layouts[array_rand($layouts)];
    $selectedBg = $backgrounds[array_rand($backgrounds)];

    // Kategoriyaga xos 3 variant
    $variants = [

        'electronics' => [
'ELECTRONICS — DARK TECH FLAGSHIP (referens: Honor H400 Lite 5G poster):
Mahsulot qorong\'u tosh/suv sachratgan fonida dramatik burchakda ko\'rsatiladi.
TEPADA: Brend + model nomi JUDA KATTA oq qalin shriftda (masalan "HONOR H400 Lite 5G").
CHAP TOMONDA: Har bir spek vertikal qatorlarda — kichik ikonka chap tomonda:
  📷 "108MP KAMERA" (qalin raqam, tagida ingichka tushuntirish)
  🔋 "5230 mAh BATAREYA"
  ⚡ "35W TEZKOR ZARYADLASH"  
  📺 "AMOLED EKRAN"
  🔄 "120Hz YANGILANISH"
O\'NG YUQORI BURCHAK: Kafolat/sertifikat badge ("IMEI ✓ | 1 yil kafolat").
Mahsulotni ikkinchi burchakdan ham ko\'rsatish (orqa kamera ko\'rinishi).
Ranglar: Qora/kul rang fon, oq matn, oltin/kumush metallik aksent.
Kayfiyat: Samsung/Honor rasmiy reliz posteri — texnik lekin vizual ajoyib.',

'ELECTRONICS — NEON TEXNOLOGIYA:
Chuqur qorong\'u navy-ko\'k fon, elektr rangli (#00BFFF) neon chiziqlar va subtle circuit-board naqsh.
Mahsulot markazda levitatsiya effektida (tagida cyan porlash).
MAHSULOT NOMI: Katta futuristik qalin shrift, porlash effekti bilan.
XUSUSIYATLAR: Mahsulot atrofida kavisli arkada glassmorphism kartochkalar (muzli shisha, blur, ingichka cyan chegara):
Har bir kartochka: [ikonka] + [QALIN raqam] + [tagida ingichka tushuntirish]
  • "108MP" + "Asosiy kamera"  
  • "7 KUN" + "Batareya quvvati"
  • "5G" + "Tezkor internet"
  • "120Hz" + "Silliq ekran"
PASTDA: "⚡ TEXNOLOGIK YECHIM" badge. Kafolat muhri burchakda.
Ranglar: Navy/qora baza, elektrik ko\'k/cyan aksent, oq matn. Hech qanday issiq rang YO\'Q.
Kayfiyat: Apple keynote presentatsiyasi — futuristik, premium, eng ilg\'or texnologiya.',

'ELECTRONICS — TOZA SPEK KARTA:
Och kulrang toza gradient fon, texnik chiziq naqsh (blueprint hissi).
Mahsulot markazda mukammal studio yoritish va o\'tkir akslar bilan.
CHAP USTUN: Xususiyat ikonkalari bilan speklar:
  Har bir qator: [rangli doira ikonka] → [QALIN qiymat] → [tushuntirish]
MAHSULOT NOMI tepada toza qora qalin sans-serif, rangli aksent ostida chiziq.
ASOSIY SPEK (batareya yoki kamera): O\'NG tomonda JUDA KATTA matn (3x katta).
PASTDA: "✅ Rasmiy kafolat | 🔧 Ishlab chiqaruvchi xizmati" toza qator.
Ranglar: Och kulrang/oq baza, korporativ ko\'k aksent, qorong\'u matn.
Kayfiyat: Rasmiy brend ma\'lumot varag\'i — toza, ishonchli, professional spesifikatsiya.',

'ELECTRONICS — OLTIN SOVG\'A HASHAMAT (referens: Pods Dubai 3 AirPods poster):
FON: Chuqur navy-ko\'k (#0A1628) fon O\'NG tomonda oltin/gold hashamatli naqshlar (arabiy ornament) va yumshoq oltin yorug\'lik. Chapda toza navy. Hashamatli va premium his.
MAHSULOT: Markazda katta ko\'rsatilgan, oldin va orqadan. CHAP YUQORIDA: aksessuar (chexol, kabel) qizil sovg\'a lentasi 🎀 bilan "CHEXOL SOVG\'A" yoki "AKSESSUAR SOVG\'AGA" oltin stilizatsiya matnda.
MAHSULOT NOMI: JUDA KATTA qalin matnda aralash ranglar — masalan "PODS" oq, "3" katta to\'q sariq, "DUBAI" oq. Raqam boshqa elementlardan 2x katta.
XUSUSIYATLAR glassmorphism/muzli shisha to\'rtburchak badgelar:
  🔋 Batareya ikonka + "12 SOATGACHA SIFATLI OVOZ" — yashil batareya ikonka, oq muzli badge
  🛡️ "12 OY KAFOLAT" — qorong\'u badge to\'q sariq qalin matn
  📶 Bluetooth ikonka + "TEZKOR QO\'SHILISH" — muzli badge Bluetooth ikonkalar
PASTDA: Platforma moslik ikonkalari: Apple 🍎 + Android 🤖 + Windows 💻 kichik ikonkalar qatorda.
Ranglar: Navy ko\'k, oltin (#D4AF37), to\'q sariq (#FF6D00), oq. Hashamatli va premium.
Kayfiyat: Dubai luxury tech — sovg\'a qutisi effekti, hashamatli, premium aksessuar hissi.',

'ELECTRONICS — TOZA SPEK STACK (referens: Besprovodniye naushniki 3v1):
FON: Toza oq yoki och kulrang gradient fon. HECH QANDAY tekstura — sof toza studio.
MAHSULOT: Markazda KATTA, mahsulot rasmning 50% ini egallaydi. Mukammal studio yoritish.
TEPADA: Mahsulot nomi JUDA KATTA qalin qora matnda to\'liq kenglikda. Yonida rangli pill badge — "3в1" yashil (#4CAF50) yoki "2в1" yoki asosiy xususiyat.
CHAP TOMONDA xususiyatlar vertikal stak — har biri rangli pill badge:
  🔊 Qora pill: "aktivnoe shumopodavlenie" uslubida — ikonka + qalin matn
  🎵 Yashil matn: "Hi-Fi zvuk" uslubi
  🎤 Qizil pill: sariq ikonka + "HD mikrofon"
  🛡️ Qizil pill: "2 yil kafolat"
O\'NG TOMONDA: Asosiy raqamli stat JUDA KATTA — masalan "400" qora qalin, tagida "SOAT ISHLASH" yashil matnda. Raqam JUDA KATTA — rasmning 20% ini egallaydi.
PASTDA: Platforma ikonkalari (Android, Apple, Windows) kichik.
Ranglar: Oq fon, qora mahsulot, YASHIL (#4CAF50) + QIZIL (#F44336) aksent badgelar. Yuqori kontrast.
Kayfiyat: Toza spetsifikatsiya dizayni — raqamlar gapiradi, texnik xususiyatlar aniq ko\'rinadi.',

'ELECTRONICS — GAMING NEON (referens: Igrovye napalchiki PUBG poster):
FON: Yorqin binafsha/purple (#7B1FA2) dan ko\'k-purplega gradient. Sparkle effektlar ✨, geometrik uchburchaklar, kristall/gem elementlar tarqalib. Neon porlash chiziqlar. Energiya hissi.
MAHSULOT: Markazda KATTA ko\'rsatilgan, dramatik burchakda. Orqasida neon halqa porlash.
Geymer kontekst: O\'ng pastda yoki fonda gaming xarakter rasmi — PUBG/Free Fire uslubidagi jangchi. Bu mahsulotni KIM ishlatishi ko\'rsatadi.
TEPADA: Mahsulot nomi JUDA KATTA qalin oq+sariq matnda — "IGROVYE" oq, "NAPALCHIKI" sariq qalin.
MIQDOR badge: "4 SHT" yoki miqdor sariq doira badge ichida KATTA — YAQQOL ko\'rinadi.
"Super otklik" yoki asosiy xususiyat sariq pill badge "+" ikonka bilan.
Xususiyatlar CHAP tomonda sariq ikonkalar bilan:
  ⚡ "Mgnovennaya reaktsiya" — momaqaldiroq ikonka
  🔥 "Otlichno skolzyat" — olov ikonka
  📱 "Sovmestimost s lyubimi ekranami" — telefon ikonka
Ranglar: Binafsha/purple (#7B1FA2), sariq (#FFEB3B), oq, pushti neon. GAMING estetikasi.
Kayfiyat: Mobile gaming aksessuar — geymerlar uchun energetik, hayajonli dizayn.',

'ELECTRONICS — QALQON BADGE USLUB (referens: Nokia 6300 poster):
FON: Qorong\'u kulrang/charcoal (#2D2D2D) gradient, subtle oltin chiziqlar va dekorativ naqshlar. Premium qorong\'u his.
MAHSULOT: Markazda-o\'ngda 2 burchakdan ko\'rsatilgan (old ko\'rinish + orqa ko\'rinish) — har ikki tomoni ko\'rinadi. Mahsulot rasmning 50% ini egallaydi.
TEPADA: Brend + model raqami JUDA KATTA oltin (#D4AF37) 3D effektli qalin matnda — masalan "NOKIA 6300" yoki mahsulot nomi. To\'liq kenglikni egallaydi. Oltin metallik porlash effekti.
CHAP TOMONDA 3 ta xususiyat — har biri QALQON (shield) shaklidagi badge ichida:
  🛡️ Birinchi qalqon: Teal/cyan (#009688) rangli qalqon shakli, ichida ikonka + QALIN matn — masalan SIM-karta ikonka + "2 TA SIM-KARTA"
  🛡️ Ikkinchi qalqon: Batareya ikonka + "1500 mAh BATAREYA" — raqam KATTA oltin rangda
  🛡️ Uchinchi qalqon: Fonar/qo\'shimcha ikonka + asosiy xususiyat
Har bir qalqon badge: Metallik teal (#009688) rangli qalqon shakli, ichida oq matn, raqamlar oltin/sariq rangda KATTA.
Ranglar: Qorong\'u kulrang fon, oltin (#D4AF37) matn, teal (#009688) qalqon badgelar, oq.
Kayfiyat: Klassik premium gadget — ishonchli, mustahkam, oddiy lekin kuchli texnik dizayn.',
        ],

        'clothing' => [
'CLOTHING — MODA EDITORIAL (referens: Zara/H&M katalog):
Toza iliq beige (#F5F0E8) fon, zagro to\'qima tekstura.
Mahsulot markazda — flat-lay yoki ko\'rinmas manekenda chiroyli ko\'rsatilgan.
MAHSULOT NOMI: Nafis serif shriftda, ingichka oltin ostida chiziq bilan.
ASOSIY STAT: "100% PAXTA" JUDA KATTA qalin matnda — bosh xususiyat.
Tagida: material tarkibi tafsiloti.
O\'NG TOMONDA: Minimal chiziq-ikonka teglari:
  📏 "S — 3XL o\'lcham"
  🔄 "Mashinkada yuviladi"
  🌿 "Tabiiy material"
  🎨 "5 ta rangda"
PASTDA: Mavjud rang nuqtalari (4-5 doira turli rangda).
Kichik yumaloq INSET: Mato teksturasi macro yaqin ko\'rinish.
Kafolat va sifat badge: Ishlab chiqaruvchi kafolati belgisi.
Ranglar: Krem/beige, oltin aksent, ko\'mir rang matn.
Kayfiyat: ZARA onlayn katalog — oson nafis, istakviy, darhol kiyishni hohlayman.',

'CLOTHING — KONTRAST LOOKBOOK:
Bo\'lingan dizayn: CHAP 45% quyuq ko\'mir rang (#2D2D2D), O\'NG 55% oq fonda mahsulot.
CHAP TOMON (qorong\'u): Mahsulot nomi katta oq nafis matnda. Tagida xususiyatlar:
  "Mavsumiy kolleksiya"
  "Nafis dizayn"
  O\'lcham diapazoni qalin
O\'NG TOMON (yorug\'): Mahsulot chiroyli soyalar bilan ko\'rsatilgan.
PASTKI BANNER: To\'liq kenglikda kafolat + material sifati haqida qisqa matn.
AKSENT: Ingichka oltin (#C9A96E) diagonal chiziq chap/o\'ngni ajratib.
Rang namunalari pastda doiralar sifatida.
Ranglar: Ko\'mir + oq kontrast, oltin aksent, minimal iliq tonlar.
Kayfiyat: COS/H&M premium lookbook — zamonaviy, toza, yuqori kontrastli editorial.',

'CLOTHING — YORQIN KO\'CHA USLUBI:
Qalin solid rang foni mahsulot dominantli rangiga MOS (masalan: navy ko\'ylak = navy fon).
Mahsulot burchakda 3D chuqurlik effektli dinamik soyalar bilan.
MAHSULOT NOMI: JUDA KATTA qalin sans-serif tepada, oq subtle soya bilan.
MATERIAL: "100% PAXTA" yoki material KATTA matnda mahsulotga biroz overlapping qilib.
Xususiyatlar qalin stiker uslubidagi badgelar sifatida tarqalib:
  🧵 "Premium material"
  📏 "S-3XL o\'lcham"
  🌡️ "Bahor-yoz uchun"
  🔄 "Mashinada yuviladi"
PASTDA: Katta o\'lcham jadvali mini-vizual.
Ranglar: Mahsulotga mos qalin rang fon, oq matn, aksent stiker ranglari.
Kayfiyat: Yorqin marketplace karta — e\'tiborni tortuvchi, skrollovni to\'xtatuvchi.',
        ],

        'beauty' => [
'BEAUTY — TOZA BREND (referens: NIVEA Care kremi):
Och ko\'k/oq toza gradient fon (#E3F2FD dan oqgacha).
Mahsulot markazda yumshoq professional yoritish bilan.
MAHSULOT NOMI tepada toza qalin sans-serif. Brend nomi yuqorida.
ASOSIY SOTUV QATORI: "7 в 1" yoki "24 SOAT NAMLASH" — JUDA KATTA qalin matn (3x katta).
Mahsulot foydasi badge: "Multifunktsional" rangli pill badge.
PASTDA: Hajm yaqqol: "100мл" KATTA qalin matnda doira badge ichida.
YON xususiyatlar: Kichik toza ikonkalar matni bilan:
  💧 "48 soat namlash"
  🌿 "Tabiiy tarkib"
  ✅ "Dermatolog tasdiqlagan"
Ranglar: Yumshoq ko\'k (#64B5F6), oq, mahsulot brend rangi. Toza klinik his.
Kayfiyat: NIVEA rasmiy kartasi — toza, ishonchli, ommaviy premium go\'zallik.',

'BEAUTY — ORGANIC SERUM (referens: Floresan Vitamin C):
Oq fon bilan dekorativ mahsulotga aloqador elementlar (sitrus tilmalari Vitamin C uchun, gul barglari gul kremi uchun, suv pufaklari namlantiruvchi uchun).
Mahsulot CHAP tomonda biroz burchakda chiroyli yoritish bilan.
O\'NG TOMON: Mahsulot kategoriyasi QALIN rangli matnda aksent ostida chiziq badge bilan ("СЫВОРОТКА-ЭЛИКСИР" uslubida).
Tagida: "ANTIOKSIDANT" yoki asosiy foyda rangli pill badge.
ASOSIY INGREDIENT: "VITAMIN C" yoki bosh ingredient JUDA KATTA qalin matnda.
Sub-ingredientlar tagida toza formatda ro\'yxatlangan.
PASTKI-O\'NG: "30 мл" hajm KATTA qalin matnda.
Suzib yuruvchi dekorativ elementlar: Suv tomchilari, ingredient meva/giyohlar mahsulot atrofida.
Ranglar: Oq baza + mahsulot aksent rangi (Vitamin C uchun to\'q sariq, choy daraxti uchun yashil, atirgul uchun pushti).
Kayfiyat: Premium organik go\'zallik brendi — ingredientlarga yo\'naltirilgan, ilmiy lekin chiroyli.',

'BEAUTY — ROSE GOLD HASHAMAT:
Hashamatli rose-gold dan champagne gradientgacha oltin porloq zarrachalar suzib yuradi.
Mahsulot marmar/baxmal yuzada premium yoritish bilan markazda.
MAHSULOT NOMI: Nafis ingichka serif oltin/quyuq burgundy rangda tepada.
"PREMIUM SIFAT" muhri oltin burchakda.
BOSh MATN: Asosiy foyda KATTA nafis matnda ("24 SOAT GO\'ZALLIK").
Xususiyatlar oltin chegarali pill shaklidagi badgelar:
  🌸 "Tabiiy ingredientlar"
  🐰 "Hayvonlarda sinovdan o\'tmagan"
  💎 "Lyuks formula"
  ⏰ "Tezkor natija — 14 kunda"
PASTDA: Hajm katta matnda + kafolat muddati badge.
Ranglar: Rose gold (#B76E79), champagne, oltin, krem. Quyuq burgundy matn.
Kayfiyat: Charlotte Tilbury/Lancôme hashamatli reklama — qaytarib bo\'lmaydigan, jozibali.',

'BEAUTY — TROPIK AYOLONA (referens: Feromon parfyum posteri):
FON: Boy pushti/magenta (#E91E63) tropik gradient — palmalar silueti, gibiskus va plumeriya gullari tarqalib. Tropik jannat hissi. Iliq, jozibali, ayolona.
MAHSULOT: Markazda-pastda chiroyli yoritish bilan. Atrofida haqiqiy tropik gullar dekorativ element sifatida.
TEPADA: Mahsulot nomi/turi JUDA KATTA qalin qorong\'u matnda to\'liq kenglikda — masalan "FEROMON" yoki mahsulot turi. Tagida nafis script/qo\'lyozma shrift: "for women" yoki tavsif.
BREND logosi chap yuqorida.
TEPA O\'NGDA: Xususiyat pill badgelari qator: "ayollar uchun", "bardoshli", "jozibali" — qorong\'u pill badgelar.
INGREDIENT/NOTA GRIDI: Chap tomonda 2x3 grid — har bir katakda kichik ingredient rasmi + nomi:
  🌿 "Vanil", "Kardamon", "Lavanda"
  🌺 "Iris", "Sharqiy notalar", "Yog\'och notalari"
Har bir ingredient yonida haqiqiy ingredient rasmi (gul, ziravor, barg).
PASTKI-CHAP: Soat ikonka + "48 SOATGACHA bardoshlilik" katta matnda.
PASTKI-O\'NG: "50 мл" hajm JUDA KATTA qalin matnda.
Ranglar: Boy pushti (#E91E63), magenta, oltin aksent, oq matn, tropik yashil.
Kayfiyat: Pushti tropik jannat — ayollar bu parfyumni ko\'rib DARHOL xohlaydi. Jozibali, issiq, romantik.',

'BEAUTY — BOTANIK YUMSHOQ (referens: Creed Aventus parfyum posteri):
FON: Yumshoq pushti/lavanda (#FCE4EC) gradient. Atrofida chiroyli botanik elementlar tarqalib: gul barglari, orchideya, laym tilimi, meva, yog\'och bo\'laklari — parfyum notalarini aks ettiruvchi.
MAHSULOT: Markazda KATTA — asosiy shisha chiroyli ko\'rsatilgan. Yonida kichik sayohat spreyi yoki probnik.
TEPADA: "PO MOTIVAM" yoki "INSPIRATSIYA" yumshoq pushti matnda. Tagida brend/hid nomi JUDA KATTA qalin qora matnda — "AVENTUS" yoki mahsulot nomi.
CHAP TOMONDA xususiyatlar yulduz ⭐ ikonkalar bilan:
  ⭐ "Zich aromat" — qalin aksent rang matnda
  ⭐ "Bardoshlilik — 2 kungacha" — qalin matnda
O\'NG TOMONDA: "50 мл" pushti doira badge ichida KATTA matnda.
"1000 PSHIKOV" yoki "1000 MARTA" qorong\'u doira badge — ishlatish umri ko\'rsatib.
PASTKI-O\'NG: "Aromat sovg\'aga 🎁" nafis script/qo\'lyozma shriftda, sovg\'a qutisi ikonka bilan.
DEKORATIV: Gullar, mevalar, ingredient elementlari chiroyli tarqalib — parfyum notalarini vizuallashtirib.
Ranglar: Yumshoq pushti (#FCE4EC), lavanda, qora matn, oltin aksent, botanik yashil.
Kayfiyat: Parfyumeriya butigi — nafis, tabiiy, ingredient hikoyasi.',

'BEAUTY — TOZA MATN STAK (referens: NIVEA MEN dush geli):
FON: Och ko\'k (#E3F2FD) suv teksturasi fon — suv tomchilari tarqalib, muz kristallari yoki suv to\'lqinlari effekti. Yangilovchi, toza, aquatik his.
MAHSULOT: CHAP tomonda katta ko\'rsatilgan (~45% maydonda). Mahsulot shishasi aniq va yorqin.
O\'NG TOMONDA matn VERTIKAL STAK uslubida (har biri alohida qatorda, pastga tushib boradi):
  1-qator: Mahsulot turi kichik kulrang matnda (masalan "Dush geli" yoki "Krem")
  2-qator: Maqsad qalin qorong\'u matnda (masalan "Erkaklar uchun" yoki "Terini namlash")
  3-qator: ASOSIY XUSUSIYAT JUDA KATTA qalin ko\'k matnda — "2 в1" yoki "7 в1" yoki "24 SOAT" (bosh spek!)
  4-qator: Qo\'shimcha tavsif kichikroq matnda
  5-qator: Mahsulot xususiyati qalin matnda
  6-qator: HAJM JUDA KATTA qalin ko\'k matnda — "250мл" yoki "100мл" yoki "500мл" (pastda)
HECH QANDAY badge yoki kartochka YO\'Q — faqat TOZA MATN. Minimalistik.
Ranglar: Och ko\'k (#64B5F6), oq, qorong\'u ko\'k (#1565C0) matn. Suv ranglari palitrasi.
Kayfiyat: NIVEA rasmiy reklama uslubi — toza, minimalistik, faqat mahsulot va asosiy ma\'lumot. Ortiqcha hech narsa yo\'q.',

'BEAUTY — DIAGONAL ILMIY (referens: Vichy Dercos shampun):
FON: Qalin QIZIL (#D32F2F) fon, ustidan OQ diagonal geometrik chiziqlar va shakllar kesib o\'tadi — dinamik kompozitsiya. Qizil va oq maydonlar diagonal bo\'lingan.
MAHSULOT: O\'NG tomonda biroz qiyshaytirilgan (~45% maydon). Mahsulot shishasi aniq ko\'rinadi.
CHAP YUQORIDA: Tibbiy krest ✠ ikonka + "DERMATOLOGLAR TAVSIYA QILADI" doira muhr/seal — professional tasdiqlash!
TEPADA OQ MAYDONDA: Mahsulot turi JUDA KATTA qalin qora matnda — "SHAMPUN SOCH TO\'KILISHIGA QARSHI" yoki mahsulot tavsifi. 2-3 qator, juda katta.
ASOS STATISTIKA (eng kuchli element!): "+47%" yoki "+63%" yoki "3X" JUDA KATTA QIZIL qalin matnda — foizli yoki multiplikator natija! Tagida kichikroq matnda tushuntirish: "soch tolasi mustahkamroq" yoki natija tavsifi.
PASTDA: Hajm "200 мл" KATTA qalin matnda.
Brendi logosi pastki-o\'ngda.
Ranglar: Qalin qizil (#D32F2F), oq, qora matn. Yuqori kontrast diagonal.
Kayfiyat: Vichy/La Roche-Posay dermatologik reklama — ilmiy dalil, foizli natija ko\'rsatadi. "+47% mustahkamroq" — bu raqam sotadi!',

'BEAUTY — MONOXROM PUFAK (referens: Garnier Fructis balzam):
FON: Iliq shaftoli/coral (#FF8A65) monoxromatik gradient. Suzuvchi shaffof pufakchalar (bubbles/spheres) dekorativ tarqalib — turli o\'lchamda, ba\'zilari katta, ba\'zilari kichik. Organik, iliq, yangilovchi his.
MAHSULOT: MARKAZDA katta (~40%), biroz qiyshangan. Mahsulot ko\'p ranglardan emas, fon rangiga uyg\'un.
TEPADA: Mahsulot turi KATTA qalin qorong\'u matnda — "BALZAM" yoki "KREM" yoki "SHAMPUN".
Tagida: Maqsad kichikroq matnda — "shikastlangan sochlar uchun" yoki kontekst.
HAJM: "200 МЛ" KATTA qalin matnda tepada-o\'ngda.
ASOS STATISTIKA: "10X" yoki "5X" yoki "3X" — MULTIPLIKATOR raqam JUDA KATTA qalin qorong\'u matnda. Tagida: "kamroq shikastlanish" yoki natija matni. Bu raqam rasmning 15% ini egallaydi!
Ingredient badge: "100% o\'simlik keratini" yoki asosiy ingredient mahsulotda ko\'rinadi.
DEKORATIV: Suzuvchi shaffof pufakchalar, meva/ingredient elementlari (marula yog\'i, keratin).
Ranglar: Shaftoli/coral (#FF8A65) BITTA rang oilasi — och va to\'q versiyalari. Qorong\'u matn.
Kayfiyat: Garnier/L\'Oreal organik liniya — iliq, tabiiy, multiplikator raqam bilan kuchli da\'vo.',

'BEAUTY — NATIJA KO\'RSATUVCHI (referens: Floresan skrab-maska):
FON: Och ko\'k (#BBDEFB) toza fon. Subtle denim/mato tekstura naqsh qo\'shilishi mumkin.
MAHSULOT: O\'NG tomonda katta (~40%). Krem/skrab tyubkasi.
TEPADA: Mahsulot turi JUDA KATTA qalin qora matnda — "SKRAB-MASKA" yoki "KREM" (to\'liq kenglik).
Tagida: Ishlatish vaqti KATTA matnda — "KUNDALIK 1 DAQIQA" yoki foydalanish osonligi.
TEPA-O\'NGDA: Faol ingredient pill badgelar — "SALITSIL KISLOTA + SINK" yoki ingredient kombinatsiyasi ko\'k pill badge ichida.
CHAP TOMONDA: ✓ CHECKMARK RO\'YXAT — foydalar vertikal ro\'yxati:
  ✓ "Samarali tozalaydi"
  ✓ "Qora nuqtalarni yo\'qotadi"
  ✓ "Yog\'li porlashni olib tashlaydi"
Har bir foyda oldida ✓ checkmark belgisi — IShONCH beradi.
INSON YUZI: Chap pastda yosh ayol/erkak sog\'lom toza teri bilan tabassumda — NATIJANI ko\'rsatadi! (yumaloq yoki oval ramkada kesilgan).
PASTKI-CHAP: "TERI MUAMMOSIZ ✓" yakuniy va\'da KATTA qalin matnda.
Brendi logosi pastda.
Ranglar: Och ko\'k (#BBDEFB), oq, qora matn, qizil aksent.
Kayfiyat: Farmatsevtik kosmetika — checkmark natijalar, inson yuzi isboti, "1 daqiqada natija" tezkor va\'da.',
        ],

        'home' => [
'HOME — OSHXONA QAHRAMONI (referens: Kukmara TOVA tova):
Qalin QIZIL gradient fon (#D32F2F dan #B71C1C gacha) — e\'tiborni tortadi.
Mahsulot burchakda qopqog\'i biroz ochiq ichini ko\'rsatib. Qizil sovg\'a lentasi 🎀 qopqoqda.
"TOVA" yoki MAHSULOT NOMI JUDA KATTA qalin oq matnda tepada (deyarli to\'liq kenglikni egallaydi).
Tagida nafis tavsif: "yopishmadigan qoplama" uslubidagi tagline.
Mamlakat bayrog\'i + "Rossiya" / "O\'zbekiston" kelib chiqish badge.
PASTKI QATORDA xususiyat IKONKALARI: [Keramik] [Gaz] [Elektr] [Induksion] moslik ikonkalari.
O\'LCHAM: "24/26 sm" JUDA KATTA qalin matnda pastki-o\'ngda.
SOTUV: "Sovg\'aga — shisha qopqoq 🎁" bonus taklifi. "Uzoq yillar xizmat qiladi" mustahkamlik.
Ranglar: Qalin qizil, oq matn, oltin aksent.
Kayfiyat: Kukmara rasmiy posteri — qalin, ishtaha ocharli, premium oshxona buyumlari.',

'HOME — SHINAM LIFESTYLE:
Iliq foto-asosli fon: xira zamonaviy oshxona/mehmonxona, deraza tariq tabiiy yorug\'lik.
Mahsulot marmar peshtaxta/yog\'och javonda real muhitda ko\'rsatilgan.
MAHSULOT NOMI toza qorong\'u qalin matnda tepada.
ASOSIY SPEK: O\'lchamlar "25×15×10 sm" yoki sig\'imi JUDA KATTA matnda.
Xususiyatlar toza oq suzib yuruvchi kartochkalar subtle soyalar bilan:
  🌡️ "250°C chidamli"
  🍽️ "Mashinada yuviladi"
  ♻️ "Ekologik material"
  📦 "To\'plam: 3 ta"
  ⚖️ "850 gr"
ISHONCH: Mahsulot sertifikati yoki kafolat badge.
Ranglar: Iliq oqlar, yog\'och rangi (#8B6914), adaqliq yashil, terrakot aksent.
Kayfiyat: IKEA katalog — iliq, haqiqiy, O\'Z oshxonangizda tasavvur qilasiz.',

'HOME — TOZA SPEK KARTASI:
Toza och adaqliq yashil (#E8F5E9) dan oqgacha gradient fon.
Mahsulot markazda mukammal mahsulot fotosuratlari yoritishi bilan.
MAHSULOT NOMI + brend tepada qalin qorong\'u matnda yashil ostida chiziq bilan.
CHAP USTUN: Xususiyat ikonkalari bilan speklar:
  📐 Aniq o\'lchamlar
  ⚖️ Og\'irlik
  🔧 Material turi
O\'NG USTUN: Foydalanish foydalari:
  ✅ "Oson tozalash"
  🔥 "Pishirish uchun ideal"
  💪 "Mustahkam material"
PASTDA: "HAJM: 2 LITR" yoki asosiy o\'lchov JUDA KATTA qalin matnda.
BURCHAK: Kafolat muddati + material sifati badge.
Ranglar: Toza yashil, oq, qorong\'u ko\'mir rang matn.',

'HOME — DETALLI MAHSULOT (referens: Kukmara TOVA batafsil):
FON: Qalin rang gradient — mahsulot rangiga mos (qizil oshxona buyumlari uchun qizil, yashil eco uchun yashil, ko\'k texnika uchun ko\'k). Qalin va e\'tiborni tortuvchi.
MAHSULOT: Markazda real kontekstda ko\'rsatilgan — masalan tova ichida tuxum pishirilayotgan, idish ichida ovqat, choynak ichida choy. Mahsulot ISHLAYOTGAN holatda.
TEPADA: Brend logosi kichik oq matnda. Tagida mahsulot nomi JUDA KATTA qalin oq matnda ("TOVA", "CHOYNAK" kabi qisqa so\'z).
Tagida tagline: "yopishmadigan qoplama" yoki "zanglamaydigan" kichik oq matn.
STRELKALAR: Mahsulot qismlariga strelkalar bilan ishora qilib qisqa izohlar:
  → Tutqichga: "Qizib ketmaydi" 
  → Qopqoqqa: "Shisha qopqoq" 
  → Ichiga: "Marmar qoplama"
SOVG\'A ELEMENTI: Agar sovg\'a bo\'lsa — mahsulotda qizil lenta 🎀 va "Sovg\'aga: shisha qopqoq" yozuvi.
MOSLIK IKONKALARI: Pastda qatorda kichik ikonkalar: [CERAMIC] [GAS] [ELECTRIC] [INDUCTION] — qaysi plitada ishlashini ko\'rsatib.
🇷🇺Kelib chiqish bayrog\'i + mamlakat nomi.
O\'LCHAM: "24/26 sm" JUDA KATTA qalin matnda pastki-o\'ngda.
"Uzoq yillar xizmat qiladi" chidamlilik matni.
Ranglar: Mahsulotga mos qalin rang fon, oq matn, oltin aksent.
Kayfiyat: Oshxona buyumlari posteri — aniq speklar, real holatda ko\'rsatish, har bir detal ko\'rinib turadi.',
        ],

        'food' => [
'FOOD — ORGANIK PORTLASH:
Yorqin toza oq fon, tabiiy ingredient elementlari tarqalib (yashil barglar, yog\'och, meva) mahsulot qadoqlanishi atrofida.
Mahsulot paketi markazda biroz burchakda ishtaha ocharli yoritish bilan.
MAHSULOT NOMI KATTA qalin quyuq-yashil matnda tepada.
"100% TABIIY" JUDA KATTA qalin matnda — bosh stat.
HALOL badge ko\'zga tashlanib hilol ikonka bilan.
Xususiyatlar:
  🌿 "GMO siz"
  ⚖️ "500 gr" (og\'irlik katta matnda)
  📅 "Yaroqlilik: 12 oy"
  🇺🇿 "O\'zbek mahsuloti"
Oziq-tuzar doira diagrammasi (oqsil/uglevod/yog\') kichik vizual.
PASTDA: "BUTUN OILA UCHUN! 👨‍👩‍👧‍👦" tagline qalin.
Mahsulotda yangilik hissi uchun suv tomchilari.
Ranglar: Toza yashil (#4CAF50), tabiiy qizil, iliq to\'q sariq, toza oq.
Kayfiyat: Whole Foods/organik premium — och, sifatga ishonch, sog\'lom tanlov.',

'FOOD — ISHTAHA QO\'ZG\'ATUVCHI:
Iliq ambar/oltin gradient fon oshxona atmosfera yoritishi bilan.
Mahsulot chiroyli tayyorlangan taom bilan birga ko\'rsatilgan (agar ingredient bo\'lsa tayyorlangan taomni ko\'rsat).
MAHSULOT NOMI iliq rustik qalin shriftda tepada.
"MAZALI VA FOYDALI" yoki shunga o\'xshash tagline nafis yozuvda.
BOSh STAT: Og\'irlik "1 KG" yoki porsiya soni JUDA KATTA matnda.
Pastki qator: Halol, Tabiiy, GMO-siz, Oilaviy ikonkalar.
Kichik yumaloq INSET: ichki ko\'rinish yoki tayyorlash taklifi.
Ranglar: Iliq ambar (#FF8F00), boy jigarrang, krem, qorong\'u matn.
Kayfiyat: Premium oziq-ovqat qadoqlash — ekran orqali TATIB ko\'rganday bo\'lasiz.',

'FOOD — EKO TOZA YORLIQ:
Tabiiy kraft qog\'oz tekstura yoki och zig\'ir mato foni — organik his.
Mahsulot markazda tabiiy kun yorug\'i fotosurat uslubida.
MAHSULOT NOMI tepada qorong\'u qalin ekologik shriftda.
Tarkib tafsiloti toza formatda har bir tabiiy ingredientning yonida barg ikonkasi.
"HALOL ✅" katta sertifikat badge.
Oziqlanish faktlari mini infografik formatda (ustun diagramma).
"500 GR" og\'irlik KATTA qalin matnda pastda.
Yon: "Saqlash: +2...+8°C" amaliy ma\'lumot.
SOTUV: "O\'zbek fermerlardan 🌾" kelib chiqish hikoyasi. "100% tabiiy" muhrli.
Ranglar: Kraft jigarrang, o\'rmon yashil, krem oq. Tabiiy, tuproq palitrasi.
Kayfiyat: Fermerdan stolga hunarmand brendi — har bir ingredientga ishonasiz.',
        ],

        'kids' => [
'KIDS — O\'YNOQI KAMALAK:
Quvnoq pastel kamalak gradient (sariq → pushti → ko\'k → yalpiz) suzib yuruvchi yulduzchalar ⭐ va bulutlar ☁️.
Mahsulot markazda yumshoq va xavfsiz yoritish bilan.
MAHSULOT NOMI KATTA qalin rangli o\'ynoqi shriftda.
"3-7 YOSH" yosh badge JUDA KATTA matnda yulduz shakli ichida — bosh stat.
Xususiyatlar rangli qalin badgelar (har biri turli rang), 3° biroz burilgan:
  ✅ "CE sertifikati" (xavfsizlik badge YAQQOL)
  🧸 "Zahrarsiz material"
  🛁 "Yuviladi"
  🎓 "Rivojlantiruvchi"
  ❤️ "Bolalar uchun xavfsiz"
PASTDA: Xavfsizlik sertifikati badge yaqqol ko\'rsatiladi.
Ranglar: Yumshoq sariq, bola pushti, osmon ko\'k, yalpiz yashil. Do\'stona qorong\'u ko\'k matn.
Kayfiyat: Fisher-Price/LEGO — ota-onalar XAVFSIZ his qiladi. Bolalar HAYAJONLANADI.',

'KIDS — QIZIQARLI SARGUZASHT:
Yorqin osmon ko\'k fon oq momiq bulutlar va o\'ynoqi geometrik shakllar.
Mahsulot bilan baxtli bola o\'zaro ta\'sir ko\'rsatayotgan rasm kichik inset.
MAHSULOT NOMI qalin yumaloq to\'liq shriftda rangli soyalar bilan.
"XAVFSIZ + QIZIQARLI!" qalin qiziqarli matnda.
YOSH DIAPAZONI: "0-3 YOSH" JUDA KATTA doira badge.
Xususiyatlar qiziqarli ikonkalar bilan pufaksimon pill badgelar:
  🌈 Hayajonli tavsiflar
  🧪 "Zahrarsiz bo\'yoqlar"
  💪 "Chidamli — buzilmaydi"
  📦 "Kafolat: 1 yil"
Ranglar: Yorqin birlamchi ko\'k, sariq, qizil aksent. Oq baza. Quvnoq maksimum.
Kayfiyat: O\'yinchoq do\'koni vitrinalari — har bir bola XOHLAYDI, har bir ota-ona ISHONADI.',

'KIDS — NOZIK CHAQALOQ:
Yumshoq nozik — och lavanda yoki bola kukunli pushti (#FCE4EC) silliq gradient.
Mahsulot yumshoq mato/ko\'rpada xavfsizlik hissi uchun.
MAHSULOT NOMI nozik yumaloq serif, iliq rangda.
"0+ OY" yosh xavfsizlik badge yumshoq doirada — YAQQOL.
Xususiyatlar nozik ingichka badgelarda (qattiq qirralar yo\'q, keskin burchaklar yo\'q):
  🍼 "Tug\'ilganidan boshlab"
  ✅ "Pediatr tasdiqlagan"
  🌿 "Gipoallergen"
  💕 "Teri uchun xavfsiz"
PASTDA: "PARHEZ VA XAVFSIZLIK" nozik banner.
ISHONCH: Tibbiy/pediatrik tasdiqlash badge yaqqol.
Ranglar: Kukunli pushti, yumshoq lavanda, bola ko\'k, iliq krem. Barcha YUMSHOQ va XAVFSIZ.
Kayfiyat: Johnson\'s Baby brendi — sof ishonch, tibbiy darajada xavfsizlik.',
        ],

        'sport' => [
'SPORT — AGRESSIV UNUMDORLIK:
QOP QORA (#0A0A0A) fon neon to\'q sariq (#FF6D00) diagonal energiya kesmalari.
Mahsulot dramatik past burchakda portlash/starburst orqasida.
MAHSULOT NOMI JUDA KATTA ultra-qalin siqiq BOSH HARFLAR matnda — to\'liq kenglikni to\'ldiradi.
"PROFESSIONAL DARAJA 💪" qalin matnda.
SPEKLAR angular olti burchak badgelar CHAPDA (agressiv, yumaloq shakllar YO\'Q):
  ⚡ "ZARBA YUTUVCHI"
  💪 yuk sig\'imi QALIN
  💧 "SUV O\'TKAZMAYDI"
  🏃 "YENGIL — 320 gr"
  ⛓️ "ANTI-SLIP TAGLIK"
Unumdorlik hisoblagich: "PRO ████████░░" vizual intensivlik.
PASTDA: Kafolat muddati badge + material sifati belgisi.
Ranglar: Sof qora, neon to\'q sariq, neon yashil aksent, oq qalin matn. YUQORI KONTRAST.
Kayfiyat: NIKE/Under Armour kampaniyasi — adrenalin, kuch. "Menga mashq uchun SHART kerak".',

'SPORT — ZALDAGI JANGCHI:
Qorong\'u ko\'mir fon ter tomchilari va zal atmosfera yoritishi bilan.
Mahsulot mashq vaqtida ishlatilayotgan kontekstda ko\'rsatilgan.
BREND/MAHSULOT NOMI toza oq qalin tepada.
"PROFESSIONAL" kategoriya badge rangli aksentda.
Xususiyatlar vertikal qalin raqamlar bilan:
  "99%" + "Nafas oluvchi"
  "5X" + "Chidamlilik"
  "360°" + "Erkin harakat"
  "-30°C" + "Sovuqga chidamli"
Har bir raqam JUDA KATTA rangli matnda, tavsif kichik oq pastda.
Ranglar: Qorong\'u kulrang, elektrik ko\'k yoki neon yashil aksent, oq matn.
Kayfiyat: Adidas Performance — atletik, funktsional, jiddiy sportchilar uchun jiddiy jihozlar.',

'SPORT — TASHQI EXTREM:
Tabiat/tashqi dinamik fon — tog\' yo\'li, zal yoki yugurish yo\'lagi (xira).
Mahsulot tezlik/harakatni bildiruvchi motion blur izlari bilan yaqqol.
MAHSULOT NOMI QALIN oq ustiga qorong\'u soya bilan o\'qish uchun.
"SPORT & ACTIVE" kategoriya badge.
Xususiyatlar qalin muhr uslubidagi elementlar dinamik ravishda tarqalib (5-10° burilgan):
  🏋️ Unumdorlik speklari
  🌊 Ob-havo qarshiligi
BOSh STAT: Og\'irlik yoki yuk sig\'imi JUDA KATTA qalin matnda.
PASTDA: Kafolat muddati + material chidamlilik badge.
Ranglar: Qorong\'u/tabiat palitrasi BITTA qalin aksent rangi bilan (to\'q sariq yoki yashil).
Kayfiyat: North Face/Columbia ekspeditsiya jihozlari — sarguzasht chaqiradi, elit uskuna.',
        ],

        'auto' => [
'AUTO — CARBON FIBER PREMIUM:
Qora carbon fiber tekstura fon metallik xrom akslar va subtle qizil pastki porlash.
Mahsulot akslanuvchi qorong\'u yuzada (showroom polini eslatadi).
MAHSULOT NOMI xrom kumush gradient matnda — katta qalin.
"UNIVERSAL O\'LCHAM" moslik badge katta matnda.
SPEKLAR sanoat metall chegarali to\'rtburchaklar:
  🔧 "Oson o\'rnatish — 5 daqiqada"
  🌡️ "-30° dan +80°C gacha"
  🛡️ "2 YIL KAFOLAT"
  📐 Material + o\'lchamlar
  🚗 "BARCHA AVTO UCHUN"
PASTDA: Avto brend moslik ro\'yxati: "Toyota • Chevrolet • Hyundai • Kia • Lacetti"
ISHONCH: "ISO 9001" sertifikat badge. "Professional daraja" belgisi.
Ranglar: Carbon qora, xrom/kumush, chuqur qizil aksent, oq matn.
Kayfiyat: BOSCH AVTO rasmiy — aniqlik bilan ishlab chiqilgan, professional daraja, ishonchli.',

'AUTO — GARAJ USTAXONASI:
Qorong\'u sanoat fon — ustaxona/garaj muhiti asboblar devori, beton pol.
Mahsulot dramatik yo\'naltiruvchi yoritish kuchli soyalar bilan.
MAHSULOT NOMI qalin sanoat sariq matnda qorong\'u fonda.
BOSh STAT: Asosiy o\'lcham yoki moslik JUDA KATTA oq matnda.
Xususiyatlar qo\'pol yorliq uslubidagi teglar (bosma asbob yorlig\'i kabi):
  🔩 Material speklari
  📐 Aniq o\'lchamlar
  💪 Og\'irlik/chidamlilik
  ⚡ "Tez o\'rnatish"
  ✅ "Sifat nazorati"
PASTDA: Kafolat muddati + sifat sertifikati badge.
Ranglar: Qorong\'u kulrang, xavfsizlik sariq (#FFD600), oq. Sanoat ustaxona palitrasi.
Kayfiyat: DeWalt/3M professional — haqiqiy ustalar ishonadi. O\'yinchoq emas, arzon emas.',

'AUTO — TEZLIK VA USLUB:
Qorong\'u metallik gradient geometrik naqshlar va ko\'k aksent yoritish bilan.
Mahsulot avto fragmenti (rul, panel yoki eshik) bilan kontekst ko\'rsatib.
MAHSULOT NOMI nafis italic poyga uslubi shriftda.
"AVTO AKSESSUAR" sarlavha badge.
Xususiyatlar toza zamonaviy kartochka tartibida:
  Har bir kartochka: [rangli nuqta] + [qalin spek] + [kichik tavsif]
BOSh STAT: Asosiy xususiyat KATTA qalin matnda checkmark ikonka bilan.
O\'RNATISH: "3 QADAM — O\'RNATISH" mini bosqich vizuali (1→2→3).
ISHONCH: Kafolat muddati + material sertifikati badge.
Ranglar: Chuqur navy, elektrik ko\'k aksent, kumush/xrom, oq matn.
Kayfiyat: Premium avto aksessuarlar — avtomobilingiz uchun sifatli jihozlar. Uslub + funksiya.',
        ],

        'health' => [
'HEALTH — FARMATSEVTIK TOZA:
Sof oq (#FFFFFF) fon chekkalarida juda subtle yalpiz-yashil gradient. DNK spirali watermark.
Mahsulot markazda klinik yoritish bilan tik turgan.
MAHSULOT NOMI + BREND toza qorong\'u navy matnda tepada.
"100% TABIIY" yoki asosiy da\'vo JUDA KATTA qalin yashil matnda.
Xususiyatlar tartibli tibbiy uslub gridda:
  🔬 "Klinik tasdiqlangan"
  💊 "Kuniga 2 kapsula"
  🌿 "Tabiiy tarkib"
  ✅ "GMP sertifikati"
  👨‍⚕️ "Shifokor tavsiyasi"
INGREDIENT TAFSILOTI: Faol tarkibiy qismlar + dozaj ko\'rsatuvchi toza jadval.
PASTDA: "NATIJA 14 KUNDA! ✅" va\'da badge. "100,000+ kishi ishonadi" ijtimoiy isboti.
Ranglar: Sof oq, klinik yashil (#2E7D32), navy (#0D47A1). Hech qanday yorqin effekt.
Kayfiyat: Nature Made/Solgar rasmiy — tibbiy ishonch, toza ilm, xavfsiz iste\'mol.',

'HEALTH — TABIIY SALOMATLIK:
Yumshoq adaqliq yashil dan oqgacha gradient botanik barg illyustratsiyalari watermark sifatida.
Mahsulot tabiiy ingredientlar (giyohlar, vitaminlar, mevalar) atrofida badiiy tarqalib.
MAHSULOT NOMI nafis quyuq yashil matnda.
"SERTIFIKATLANGAN ✅" katta ishonch badge tepada.
BOSh: Asosiy ingredient "VITAMIN D3 — 2000 IU" JUDA KATTA qalin matnda.
Foydalar toza yumaloq badgelar barg ikonkalari bilan:
  Har biri: [tabiat ikonka] + [foyda matni]
Dozaj ma\'lumoti toza formatda.
ISHONCH: Shifokor/mutaxassis tasdiqlash ikonkasi. Laboratoriyada sinovdan o\'tgan badge.
SOTUV: "Immunitetni 3X mustahkamlaydi!" qalin da\'vo.
Ranglar: Adaqliq yashil, iliq tuproq tonlari, toza oq. Tabiiy va tinchlantiruvchi.
Kayfiyat: Organik qo\'shimcha brendi — tabiat + ilm birlashgan. Faqat qarab sog\'lom his qilasiz.',

'HEALTH — ILMIY OBRO\':
Och ko\'k (#E3F2FD) toza gradient fon molekulyar tuzilma naqsh watermark.
Mahsulot klinik mahsulot fotosurat uslubida — mukammal yoritilgan, soyalarsiz.
BREND professional shriftda tepada. Mahsulot nomi qalin navy.
"GMP ✅ ISO ✅ HALOL ✅" sertifikat qatori yaqqol ko\'rsatilgan.
XUSUSIYATLAR toza ikki ustunli spesifikatsiya jadval formatida:
  CHAP: Ingredient nomi → O\'NG: Dozaj
FOYDALANISH KO\'RSATMALARI: "Iste\'mol tartibi" toza 3 bosqichli format.
BOSh STAT: Kunlik dozaj yoki asosiy o\'lchov JUDA KATTA matnda.
PASTDA: "👨‍⚕️ MUTAXASSISLAR TAVSIYA QILADI" obro\' banneri.
Ranglar: Och ko\'k, professional navy, oq. Toza tibbiy palitra.
Kayfiyat: Retseptli darajadagi qo\'shimcha — jiddiy, obro\'li, maksimal ishonch.',

'HEALTH — KLINIK KO\'K (referens: Teymurova sprey ko\'k fon):
FON: Och ko\'k (#E3F2FD) gradient fon, yashil barglar (yalpiz, choy daraxti, evkalipt) dekorativ tarqalib. Suv tomchilari va muz kristallari effekti. Yangilovchi, toza his.
MAHSULOT: Markazda-o\'ngda katta ko\'rsatilgan (~40%). Mahsulot shishasi aniq va professional yoritilgan.
TEPADA: Brend nomi qalin qorong\'u matnda.
Tagida: Mahsulot kategoriyasi JUDA KATTA qalin qora matnda — masalan "SPREY OYOQLAR UCHUN" yoki "KREM QOLLAR UCHUN" (3 qator, to\'liq kenglik).
Asosiy foyda qalin matnda: "HID VA TERGA QARSHI" yoki "NAMLASH VA HIMOYA".
Ingredient: "Salitsil kislotasi bilan" yoki asosiy ingredient kichik italic matnda.
CHAP TOMONDA xususiyatlar ODDIY MATN sifatida (badge emas!):
  • Birinchi xususiyat: muammo + yechim haqida 1-2 qator matn
  • Ikkinchi xususiyat: ingredient/formula haqida qisqa matn
PASTDA: Hajm "150ml" yoki "250ml" KATTA qalin matnda.
DEKORATIV: Ko\'k suv elementlari va yashil barglar pastda.
Ranglar: Ko\'k (#2196F3), oq, qora matn, yashil barglar. Klinik va yangilovchi.
Kayfiyat: Farmatsevtik brend posteri — oddiy, toza, ishonchli, tibbiy hissi bor.',

'HEALTH — FARMATSEVTIK PUSHTI (referens: Teymurova sprey pushti fon):
FON: Pushti/magenta (#E91E63 dan #F8BBD0 gacha) gradient fon. Yashil barglar va choy daraxti elementlari chiroyli tarqalib. Muz/kristall elementlari. Zamonaviy farmatsevtik his.
MAHSULOT: Markazda-o\'ngda katta ko\'rsatilgan. Brend logosi mahsulotda aniq ko\'rinadi.
TEPADA: Brend nomi qalin matnda.
Tagida: Mahsulot turi JUDA KATTA qalin matnda — "SPREY" yoki "KREM" yoki "GEL".
Maqsad qalin matnda: "OYOQLAR UCHUN" yoki "QOLLAR UCHUN".
CHAP TOMONDA xususiyatlar IKONKALAR bilan:
  💧 Suv ikonka + "Hid va terni yo\'q qiladi" — qalin matn
  🦠 Bakteriya ikonka + "Antibakterial kompleks" — qalin matn
  ⏰ Soat ikonka + "24 SOATGACHA ta\'sir qiladi" — raqam KATTA
PASTDA: "150ml" hajm KATTA qalin matnda.
DEKORATIV: Yashil barglar, suv elementlari chiroyli tarqalib.
Ranglar: Pushti (#E91E63), magenta, oq, yashil barglar aksent, qorong\'u matn.
Kayfiyat: Zamonaviy farmatsevtik brend — ayollar uchun ham mos, professional lekin zamonaviy dizayn.',

'HEALTH — VITAMIN ILIQ (referens: Dr.Frei Vitamin C 550mg):
FON: Iliq SARIQ (#FFC107) dan och sariqqa gradient. Toza, energetik, vitaminli his. Hech qanday murakkab naqsh yo\'q — sof rang.
MAHSULOT: O\'NG tomonda katta (~35%). Vitamin tyubkasi yoki qutichasi tik turgan.
TEPADA: Brend nomi kichik matnda.
Tagida: Vitamin/mahsulot nomi + DOZAJ BIRGALIKDA JUDA KATTA qalin qora matnda — masalan "VITAMIN C 550mg" yoki "OMEGA-3 1000mg" yoki "D3 2000IU". Dozaj raqami mahsulot nomining bir qismi sifatida KATTA.
MIQDOR: Qizil doira badge ichida "20 tab." yoki "30 kaps." yoki miqdor — qalin oq matn qizil fonda.
CHAP TOMONDA 2 ta to\'q sariq (#FF9800) yumaloq burchakli to\'rtburchak badge:
  Badge 1: "Energiya va kuchli immunitet" yoki asosiy foyda — qalin oq matn to\'q sariq fonda
  Badge 2: "Jismoniy va aqliy faollik" yoki ikkinchi foyda — qalin oq matn to\'q sariq fonda
TA\'M: Meva ikonka + "ta\'mi — apelsin 🍊" yoki meva nomi — kichik matn ikonka bilan.
PASTDA: Qabul tartibi kichik matnda: "Kuniga 1 marta, ovqat paytida" — foydalanish ko\'rsatmasi.
Ranglar: Sariq (#FFC107), to\'q sariq (#FF9800), qora matn, qizil aksent, oq.
Kayfiyat: Vitamin/qo\'shimcha brendi — iliq, energetik, sog\'lom hayot hissi. Dozaj va qabul tartibi aniq ko\'rinadi.',
        ],

        'tools' => [
'TOOLS — USTAXONA SANOAT:
Qorong\'u beton devor tekstura fon dramatik yon yoritish. Sariq xavfsizlik chiziq aksent.
Asbob metall/beton yuzaga burchakda qo\'yilgan, o\'lchov chizig\'i mahsulotdan cho\'zilgan.
MAHSULOT NOMI KATTA qalin sariq/oq matnda qorong\'u fonda.
MATERIAL: "CHROME VANADIUM CrV" JUDA KATTA qalin matnda — bosh stat.
SPEKLAR qo\'pol muhrli yorliqlar:
  📐 "250mm × 45mm" o\'lchamlar
  ⚖️ "380 gr" og\'irlik
  🔩 "HRC 58-62" qattiqlik
  🛡️ "5 YIL KAFOLAT"
  🇩🇪 "Germaniya texnologiyasi"
O\'LCHAM SOLISHTIRUVI: Mahsulot qo\'l yoki chizg\'ich yonida ko\'rsatilgan.
SOTUV: "🔧 PROFESSIONAL ASBOB" banneri. "Bir umrga yetarli!" chidamlilik da\'vosi.
Ranglar: Beton kulrang, xavfsizlik sariq, asbob qora, oq matn.
Kayfiyat: DeWalt/Knipex professional katalog — hunarmandlar bu asbobni hurmat qiladi.',

'TOOLS — ANIQLIK CHIZMASI:
Texnik chizma quyuq-ko\'k (#0D47A1) fon oq gridlar va o\'lcham chaqiruv chiziqlari.
Mahsulot texnik chizmadagidek — o\'lchov nuqtalariga strelkalar ko\'rsatib.
MAHSULOT NOMI qalin oq texnik shriftda.
Speklar muhandislik ma\'lumoti kabi:
  "Material: CrV → Qattiq: HRC 60"
  "Uzunlik: 250mm → Og\'irlik: 380g"
  "Maqsad: Professional montaj"
CHIZMA USLUBIDAGI STRELKALAR mahsulot qismlariga yorliqlar bilan ishora qilib.
PASTDA: "PROFESSIONAL DARAJA" badge. Kafolat yaqqol.
SOTUV: "Rossiya/Germaniya standarti 🇩🇪" obro\'yi.
Ranglar: Chizma ko\'k, oq chiziqlar/matn, sariq aksent asosiy o\'lchamlar uchun.
Kayfiyat: Muhandislik aniqlik hujjati — bu asbob loyihalangan, arzon ishlab chiqarilgan emas.',

'TOOLS — KUCHLI ASBOB PROMO:
Qalin to\'q sariq (#FF6F00) dan qizilgacha gradient fon — e\'tiborni tortadi.
Mahsulot yaqqol dramatik yoritish va keskin soyalar bilan.
MAHSULOT NOMI JUDA KATTA oq qalin matnda tepada — kenglikni to\'ldiradi.
"PROFESSIONAL" kategoriya badge tagida.
Xususiyatlar qorong\'u ustki qatlamdagi qalin oq badgelar:
  💪 Kuch/material speki
  📐 O\'lchamlar
  ⚡ "Tez ishlash"
  🛡️ Kafolat
BOSh STAT: Asosiy o\'lcham yoki sig\'im JUDA KATTA qora matnda to\'q sariq fonda.
PASTDA: "USTALAR UCHUN 🔧" maqsadli badge.
Ranglar: Qalin to\'q sariq, oq, qora. Maksimal ko\'rinuvchanlik.
Kayfiyat: Milwaukee/STIHL kuchli promo — skrollovni to\'xtatadi, "Menga HOZIR kerak" impulsi.',
        ],

        'accessories' => [
'ACCESSORIES — HASHAMAT NOIR:
Chuqur baxmal qora (#0A0A0A) tepadan iliq spotlight. Oltin chang zarrachalari suzib yuradi.
Mahsulot marmar/baxmal poydevor badiiy soyalar va akslar bilan.
MAHSULOT NOMI KATTA oltin (#D4AF37) gradient matnda metallik aks effekti.
"PREMIUM SIFAT ✨" oltin muhri badge.
Xususiyatlar nafis ingichka chiziqli badgelar oltin konturlar bilan:
  👜 "100% Tabiiy charm"
  📐 "32×24×12 sm"
  🎁 "Sovg\'a qutisida"
  👑 "Eksklyuziv dizayn"
  ✋ "Qo\'lda ishlov berilgan"
Yaqin INSET: Tikuv/tekstura tafsiloti hunarmandchilikni ko\'rsatib.
Kafolat badge + material sertifikati.
Ranglar: Boy qora, 24K oltin, iliq oq, champagne.
Kayfiyat: Louis Vuitton vitrinalari — EKSKLYUZIV, istakviy, "o\'zingizga hadiya qiling" impulsi.',

'ACCESSORIES — EDITORIAL MARMAR:
Oq marmar tekstura fon yumshoq tabiiy yoritish bilan.
Mahsulot nafis uslubda — flat-lay yoki minimal displeyda osilgan.
MAHSULOT NOMI nafis qorong\'u serif shriftda ingichka ostida chiziq.
"AKSESSUAR KOLLEKSIYASI" subtle sarlavha.
Material badge: "GENUINE LEATHER" yoki material qalin aksent rangda.
Xususiyatlar toza minimal pilllar:
  Har biri: ingichka chegara + nafis matn
O\'LCHAM + OG\'IRLIK yaqqol ko\'rsatilgan.
USLUB INSET: Mahsulot kiyim bilan stilizatsiya qilinib kichik fotoda ko\'rsatilgan.
Material sertifikati + kafolat badge.
Ranglar: Oq marmar, iliq beige, qorong\'u matn, subtle oltin aksent.
Kayfiyat: Michael Kors onlayn do\'kon — toza hashamat, qulay premium, kundalik nafislik.',

'ACCESSORIES — SOVG\'AGA TAYYOR:
Boy burgundy/sharob (#4A148C dan #880E4F gacha) gradient fon yumshoq bokeh chiroqlari.
Mahsulot chiroyli o\'ralgan yoki sovg\'a qutisidan chiqayotgan lenta bilan.
"IDEAL SOVG\'A 🎁" JUDA KATTA nafis oq matnda.
MAHSULOT NOMI tagida oltin serif shriftda.
Xususiyatlar suzib yuruvchi kartochkalar:
  ✨ Sifat badge
  📦 "Chiroyli qadoqlash"
  💕 "Sevimli insonga"
  📐 O\'lchamlar
MUNOSABAT badgelari: "Tug\'ilgan kun", "8 Mart", "Yangi yil" — sovg\'a munosabatlari.
PASTDA: "Sovg\'a qutisida yetkazamiz!" qisqa matn.
Ranglar: Boy burgundy, sharob, oltin, iliq oq.
Kayfiyat: Premium sovg\'a dizayni — chiroyli qadoqlash, nafis taqdimot.',
        ],

        'pet' => [
'PET — BAXTLI HAYVON:
Iliq krem (#FFF8E1) dan shaftolaga gradient subtle panja 🐾 naqsh ustki qatlam.
Mahsulot old-markazda yoqimtoy baxtli hayvon rasmi (it/mushuk) yumaloq insetda.
MAHSULOT NOMI iliq jigarrang qalin yumaloq shriftda.
"HAYVON DO\'STLARI UCHUN 🐾" iliq banner tepada.
Xususiyatlar yumaloq do\'stona badgelar (har biri turli iliq pastel rang):
  🐕 "Katta itlar uchun"
  🛡️ "Zahrarsiz material"
  🧼 "Oson tozalanadi"
  ✅ "Veterinar tasdiqlagan"
  ❤️ "Hayvonlar sevadi!"
O\'LCHAM JADVALI: Kichik/O\'rta/Katta hayvon moslik vizuali.
SOTUV: "10,000+ hayvon egasi tanladi ❤️" ijtimoiy isboti. "💝 Ularni sevamiz!" hissiy.
Ranglar: Iliq krem, o\'ynoqi marjon, do\'stona teal, panja jigarrang.
Kayfiyat: Royal Canin/KONG brendi — iliq, g\'amxo\'r, hayvon egalari yillar davomida ishonadi.',

'PET — VETERINAR TASDIQLANGAN:
Toza oq/yalpiz fon yashil tibbiy krest subtle naqsh.
Mahsulot "Veterinar tasdiqlagan ✅" KATTA yaqqol badge bilan.
MAHSULOT NOMI toza qalin quyuq yashil matnda.
Ilmiy/sog\'liq ma\'lumot formatlash:
  📋 Tarkib ro\'yxati
  📊 Oziqlanish tafsiloti agar oziq-ovqat
  🐕 Zot/o\'lcham mosligi
  ⚖️ Og\'irlik/porsiya tavsiyasi
"SIFAT VA XAVFSIZLIK" sarlavha badge.
ISHONCH: Veterinar tasdiqlash muhri KATTA. Lab-sinovdan o\'tgan badge.
PASTDA: "Sog\'lom hayvon — baxtli egasi! 🐾" tagline.
Ranglar: Toza oq, tibbiy yashil, tinch teal, qorong\'u matn.
Kayfiyat: Veterinar tasdiqlangan professional hayvon parvarishi — sog\'liqqa yo\'naltirilgan ishonch.',

'PET — YOQIMTOY O\'YNOQI:
Yorqin quvnoq osmon ko\'k oq bulutlar va rangli panja izlari tarqalib.
Mahsulot bilan xursand hayvon mahsulot bilan o\'zaro ta\'sir ko\'rsatayotgan.
MAHSULOT NOMI KATTA rangli o\'ynoqi yumaloq shriftda.
Xususiyatlar sakkizlab, qiziqarli stiker uslubidagi badgelar:
  🎾 "O\'yinchoq + Mashg\'ulot"
  💪 "Buzilmaydi — super chidamli"
  🧹 "Bir tortish — toza"
  🐾 "S/M/L o\'lchamlar"
PASTDA: Kafolat va xavfsizlik badge.
Mahsulotning chidamliligi va o\'lchamlari haqida qisqa matn.
Ranglar: Yorqin osmon ko\'k, marjon, sariq, yashil. Maksimal quvnoq energiya.
Kayfiyat: Qiziqarli hayvon do\'koni reklamasi — hayvon HAM egasi HAM hayajonlanadi.',
        ],
    ];

    // Kategoriyaga mos RANDOM variant tanlash
    $categoryModifier = '';
    if (!empty($category) && isset($variants[$category])) {
        $catVariants = $variants[$category];
        $categoryModifier = $catVariants[array_rand($catVariants)];
    }

    // Randomni tanlash
    $selectedLayout = $layouts[array_rand($layouts)];
    $selectedBg = $backgrounds[array_rand($backgrounds)];

    // To'liq promptni yig'ish
    $finalPrompt = $prompt;
    $finalPrompt .= "\n\n" . $selectedLayout;
    $finalPrompt .= "\n\n" . $selectedBg;
    $finalPrompt .= "\n\n" . $salesRules;

    if (!empty($categoryModifier)) {
        $finalPrompt .= "\n\n" . $categoryModifier;
    }

    return $finalPrompt;
}
