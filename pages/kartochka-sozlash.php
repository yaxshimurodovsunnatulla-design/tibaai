<?php
require_once __DIR__ . '/../api/config.php';
$pageTitle = 'Kartochkani to\'liq sozlash – Tiba AI';
$pageDescription = 'Marketplace uchun tovar kartochkangizni professional tarzda to\'ldiring. AI barcha maydonlarni avtomatik to\'ldirishga yordam beradi.';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="py-12 sm:py-20 relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-transparent via-indigo-900/5 to-transparent pointer-events-none"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        
        <!-- Back link -->
        <a href="/create" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-400 transition-colors mb-8">
            <i class="fa-solid fa-arrow-left text-xs"></i> Yaratish sahifasiga qaytish
        </a>

        <!-- Section Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-wider mb-4">
                <i class="fa-solid fa-sliders"></i> Kartochka sozlash
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3">
                Kartochkani to'liq <span class="gradient-text">sozlash</span>
            </h1>
            <p class="text-gray-400 max-w-xl mx-auto">Marketplace uchun tovar kartochkangizni professional tarzda to'ldiring. AI barcha maydonlarni avtomatik to'ldirishga yordam beradi.</p>
        </div>

        <!-- Stepper -->
        <div class="flex items-center justify-center gap-0 mb-10">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white text-sm font-bold flex items-center justify-center shadow-lg shadow-indigo-500/30">1</div>
                <span class="text-sm font-semibold text-white hidden sm:inline">Tovar kartochkasi</span>
            </div>
            <div class="w-12 sm:w-20 h-px bg-white/10 mx-2"></div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-white/10 text-gray-500 text-sm font-bold flex items-center justify-center">2</div>
                <span class="text-sm font-medium text-gray-500 hidden sm:inline">Yo'l-yo'riqlarni berish</span>
            </div>
            <div class="w-12 sm:w-20 h-px bg-white/10 mx-2"></div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-white/10 text-gray-500 text-sm font-bold flex items-center justify-center">3</div>
                <span class="text-sm font-medium text-gray-500 hidden sm:inline">Saqlash</span>
            </div>
        </div>

        <!-- Required note -->
        <div class="mb-6 text-sm text-gray-500">
            <span class="text-red-400 font-bold">*</span> – To'ldirishi shart bo'lgan maydonlar
        </div>

        <!-- FORM CARD -->
        <div class="space-y-0">

            <!-- ===== 1. TOVAR TOIFASI ===== -->
            <div class="glass-card p-6 sm:p-8 border border-white/5 rounded-b-none">
                <h3 class="text-lg font-bold text-white mb-1">Tovar toifasi <span class="text-red-400">*</span></h3>
                <a href="#" class="text-indigo-400 text-xs font-medium hover:underline">Yo'riqnomada batafsil</a>
                <div class="mt-4 flex flex-col sm:flex-row gap-4 items-start">
                    <div class="flex-1">
                        <select id="kc-toifasi" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-indigo-500/50 appearance-none cursor-pointer" style="background-image:url(&quot;data:image/svg+xml;utf8,<svg fill='%236b7280' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'><path fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/></svg>&quot;);background-repeat:no-repeat;background-position:right 12px center;background-size:20px;">
                            <option value="" class="bg-[#12121a]">Toifani tanlang</option>
                            <option value="kiyim" class="bg-[#12121a]">Kiyim</option>
                            <option value="elektronika" class="bg-[#12121a]">Elektronika</option>
                            <option value="oziq-ovqat" class="bg-[#12121a]">Oziq-ovqat</option>
                            <option value="go'zallik" class="bg-[#12121a]">Go'zallik va parvarish</option>
                            <option value="uy-bog'" class="bg-[#12121a]">Uy va bog'</option>
                            <option value="bolalar" class="bg-[#12121a]">Bolalar tovarlari</option>
                            <option value="sport" class="bg-[#12121a]">Sport va dam olish</option>
                        </select>
                    </div>
                    <div class="glass-card p-3 rounded-xl border border-amber-500/20 bg-amber-500/5 text-xs text-amber-300/80 max-w-xs">
                        <i class="fa-solid fa-triangle-exclamation text-amber-400 mr-1"></i>
                        Agar tovar toifasini o'zgartirsangiz — avvalgi xususiyatlar saqlanmaydi
                    </div>
                </div>
                <button class="mt-4 px-5 py-2 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-sm font-semibold hover:bg-indigo-500/20 transition-all">Qabul qilish</button>
            </div>

            <!-- ===== 2. TOVAR NOMI ===== -->
            <div class="glass-card p-6 sm:p-8 border border-white/5 rounded-none border-t-0">
                <h3 class="text-lg font-bold text-white mb-1">Tovar nomi <span class="text-red-400">*</span></h3>
                <div class="glass-card p-4 rounded-xl border border-white/5 mt-3 mb-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                        <div><div class="font-bold text-white mb-1">Nomlash sxemasi</div><div class="text-gray-500">Tovarning turi + brend + model muhim tavsif</div></div>
                        <div><div class="font-bold text-amber-400 mb-1">Hajmi 90 ta belgi</div><div class="text-gray-500">Qisqaroq nomlar qidiruv tizimlarida yaxshiroq ishlaydi</div></div>
                        <div><div class="font-bold text-white mb-1">Bitta nomdagi variantlar</div><div class="text-gray-500">90 ta belgidan ko'p emas bo'lishi kerak</div></div>
                    </div>
                </div>
                <a href="#" class="text-indigo-400 text-xs font-medium hover:underline">Yo'riqnomada batafsil</a>
                <div class="space-y-4 mt-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">Tovar nomi o'zbek tilida <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <input type="text" id="kc-nom-uz" maxlength="90" placeholder="Tovarning aniq nomi" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-indigo-500/50 pr-16 transition-all">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-600"><span id="kc-nom-uz-count">0</span>/90</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">Tovar nomi rus tilida <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <input type="text" id="kc-nom-ru" maxlength="90" placeholder="Tovarning aniq nomi" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-indigo-500/50 pr-16 transition-all">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-600"><span id="kc-nom-ru-count">0</span>/90</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== 3. QISQACHA TAVSIF ===== -->
            <div class="glass-card p-6 sm:p-8 border border-white/5 rounded-none border-t-0">
                <h3 class="text-lg font-bold text-white mb-1">Tovar qisqacha tavsifi <span class="text-red-400">*</span></h3>
                <div class="space-y-4 mt-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">Tovar qisqacha tavsifi o'zbek tilida <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <textarea id="kc-qisqa-uz" maxlength="390" rows="3" placeholder="Potentsial xaridorni bir nechta taklif bilan qiziqtiring" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-indigo-500/50 resize-none transition-all"></textarea>
                            <span class="absolute right-4 bottom-3 text-xs text-gray-600"><span id="kc-qisqa-uz-count">0</span>/390</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">Tovar qisqacha tavsifi rus tilida <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <textarea id="kc-qisqa-ru" maxlength="390" rows="3" placeholder="Potentsial xaridorni bir nechta taklif bilan qiziqtiring" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-indigo-500/50 resize-none transition-all"></textarea>
                            <span class="absolute right-4 bottom-3 text-xs text-gray-600"><span id="kc-qisqa-ru-count">0</span>/390</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== 4. TOVAR TAVSIFI (Rich Text) ===== -->
            <div class="glass-card p-6 sm:p-8 border border-white/5 rounded-none border-t-0">
                <h3 class="text-lg font-bold text-white mb-1">Tovar tavsifi <span class="text-red-400">*</span></h3>
                <div class="glass-card p-4 rounded-xl border border-white/5 mt-3 mb-4">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                        <div><div class="font-bold text-indigo-400 mb-1">Imkon qadar batafsil</div><div class="text-gray-500">Bu xaridorlarga yordam beradi va SEO ni oshiradi</div></div>
                        <div><div class="font-bold text-white mb-1">Betakror</div><div class="text-gray-500">O'z so'zlaringiz bilan yozing, nusxa ko'chirish mumkin emas</div></div>
                        <div><div class="font-bold text-amber-400 mb-1">Matn qo'shing</div><div class="text-gray-500">Rasmli kontent o'rniga matn qidiruvda yaxshiroq ishlaydi</div></div>
                        <div><div class="font-bold text-white mb-1">Yengil fayllar</div><div class="text-gray-500">JPEG, JPG, WebP, 3 Mb dan katta emas</div></div>
                    </div>
                </div>
                <a href="#" class="text-indigo-400 text-xs font-medium hover:underline">Yo'riqnomada batafsil</a>
                <!-- UZ -->
                <div class="mt-4">
                    <label class="block text-xs font-medium text-gray-400 mb-1.5">Tovar tavsifi o'zbek tilida <span class="text-red-400">*</span></label>
                    <div class="border border-white/10 rounded-xl overflow-hidden">
                        <div class="flex items-center gap-1 px-3 py-2 border-b border-white/10 bg-white/[0.03]">
                            <button type="button" class="w-8 h-8 rounded-lg hover:bg-white/10 text-gray-400 hover:text-white transition-colors flex items-center justify-center" title="Undo"><i class="fa-solid fa-rotate-left text-xs"></i></button>
                            <button type="button" class="w-8 h-8 rounded-lg hover:bg-white/10 text-gray-400 hover:text-white transition-colors flex items-center justify-center" title="Redo"><i class="fa-solid fa-rotate-right text-xs"></i></button>
                            <select class="px-2 py-1 bg-white/5 border border-white/10 rounded-lg text-xs text-gray-300 focus:outline-none ml-1"><option>Paragraph</option><option>Heading 1</option><option>Heading 2</option></select>
                            <button type="button" class="w-8 h-8 rounded-lg hover:bg-white/10 text-gray-400 hover:text-white transition-colors flex items-center justify-center ml-1" title="Bold"><i class="fa-solid fa-bold text-xs"></i></button>
                            <button type="button" class="w-8 h-8 rounded-lg hover:bg-white/10 text-gray-400 hover:text-white transition-colors flex items-center justify-center" title="Italic"><i class="fa-solid fa-italic text-xs"></i></button>
                            <button type="button" class="w-8 h-8 rounded-lg hover:bg-white/10 text-gray-400 hover:text-white transition-colors flex items-center justify-center" title="Image"><i class="fa-solid fa-image text-xs"></i></button>
                        </div>
                        <textarea id="kc-tavsif-uz" rows="6" placeholder="Tovaringiz, uning xususiyatlari va afzalliklari haqida aytib bering" class="w-full px-4 py-3 bg-transparent text-white text-sm placeholder-gray-600 focus:outline-none resize-none"></textarea>
                    </div>
                </div>
                <!-- RU -->
                <div class="mt-4">
                    <label class="block text-xs font-medium text-gray-400 mb-1.5">Tovar tavsifi rus tilida <span class="text-red-400">*</span></label>
                    <div class="border border-white/10 rounded-xl overflow-hidden">
                        <div class="flex items-center gap-1 px-3 py-2 border-b border-white/10 bg-white/[0.03]">
                            <button type="button" class="w-8 h-8 rounded-lg hover:bg-white/10 text-gray-400 hover:text-white transition-colors flex items-center justify-center"><i class="fa-solid fa-rotate-left text-xs"></i></button>
                            <button type="button" class="w-8 h-8 rounded-lg hover:bg-white/10 text-gray-400 hover:text-white transition-colors flex items-center justify-center"><i class="fa-solid fa-rotate-right text-xs"></i></button>
                            <select class="px-2 py-1 bg-white/5 border border-white/10 rounded-lg text-xs text-gray-300 focus:outline-none ml-1"><option>Paragraph</option><option>Heading 1</option><option>Heading 2</option></select>
                            <button type="button" class="w-8 h-8 rounded-lg hover:bg-white/10 text-gray-400 hover:text-white transition-colors flex items-center justify-center ml-1"><i class="fa-solid fa-bold text-xs"></i></button>
                            <button type="button" class="w-8 h-8 rounded-lg hover:bg-white/10 text-gray-400 hover:text-white transition-colors flex items-center justify-center"><i class="fa-solid fa-italic text-xs"></i></button>
                            <button type="button" class="w-8 h-8 rounded-lg hover:bg-white/10 text-gray-400 hover:text-white transition-colors flex items-center justify-center"><i class="fa-solid fa-image text-xs"></i></button>
                        </div>
                        <textarea id="kc-tavsif-ru" rows="6" placeholder="Tovaringiz, uning xususiyatlari va afzalliklari haqida aytib bering" class="w-full px-4 py-3 bg-transparent text-white text-sm placeholder-gray-600 focus:outline-none resize-none"></textarea>
                    </div>
                </div>
            </div>

            <!-- ===== 5. VIDEO ===== -->
            <div class="glass-card p-6 sm:p-8 border border-white/5 rounded-none border-t-0">
                <h3 class="text-lg font-bold text-white mb-3">Video</h3>
                <div class="glass-card p-4 rounded-xl border border-white/5 mb-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div><div class="font-bold text-indigo-400 mb-1">Format</div><ul class="text-gray-500 list-disc list-inside"><li>1080×1440</li><li>3 Mb dan katta emas</li></ul></div>
                        <div><div class="font-bold text-white mb-1">Video sotishga yordam beradi</div><div class="text-gray-500">Sifatli video tovarni namoyish qiladi va xaridorga ko'proq ishonch beradi</div></div>
                    </div>
                </div>
                <label class="group flex flex-col items-center justify-center w-32 h-32 rounded-2xl border-2 border-dashed border-white/10 hover:border-indigo-500/40 bg-white/[0.02] hover:bg-indigo-500/5 cursor-pointer transition-all">
                    <i class="fa-solid fa-plus text-gray-500 group-hover:text-indigo-400 text-lg mb-1 transition-colors"></i>
                    <span class="text-xs text-indigo-400 font-semibold">Video qo'shish</span>
                    <input type="file" accept="video/*" class="hidden" id="kc-video">
                </label>
            </div>

            <!-- ===== 6. XUSUSIYATLARNI TANLASH ===== -->
            <div class="glass-card p-6 sm:p-8 border border-white/5 rounded-none border-t-0">
                <h3 class="text-lg font-bold text-white mb-1">Xususiyatlarni tanlash <span class="text-sm font-normal text-gray-500">(Majburiy emas, maksimal 5 ta)</span></h3>
                <div class="glass-card p-4 rounded-xl border border-white/5 mt-3 mb-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                        <div><div class="font-bold text-white mb-1">Tovarlar qanday guruhlandi</div><div class="text-gray-500">Masalan, O'lcham tavsifi futbolka — XS, S, M, L, XL, 2XL</div></div>
                        <div><div class="font-bold text-white mb-1">Har xil turdagi tovarlar bo'lsa</div><div class="text-gray-500">Masalan, telefon uchun g'ilof va telefonni guruhlash mumkin emas</div></div>
                        <div><div class="font-bold text-white mb-1">Kerakli tavsif mavjud bo'lmasa</div><div class="text-gray-500">O'zingiz xohlagan tavsifni yaratish mumkin</div></div>
                    </div>
                </div>
                <a href="#" class="text-indigo-400 text-xs font-medium hover:underline">Yo'riqnomada batafsil</a>
                <div class="mt-4">
                    <select id="kc-xususiyat" class="w-full sm:w-80 px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-indigo-500/50 appearance-none cursor-pointer" style="background-image:url(&quot;data:image/svg+xml;utf8,<svg fill='%236b7280' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'><path fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/></svg>&quot;);background-repeat:no-repeat;background-position:right 12px center;background-size:20px;">
                        <option value="" class="bg-[#12121a]">Xususiyat qo'shish</option>
                        <option value="rang" class="bg-[#12121a]">Rang</option>
                        <option value="olcham" class="bg-[#12121a]">O'lcham</option>
                        <option value="material" class="bg-[#12121a]">Material</option>
                    </select>
                </div>
                <div class="mt-4 glass-card p-3 rounded-xl border border-amber-500/20 bg-amber-500/5 text-xs text-amber-300/80 inline-flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-amber-400"></i>
                    Tavsifni faqat toifa tanlangdan keyin qo'shish mumkin. <a href="#kc-toifasi" class="text-indigo-400 font-semibold hover:underline">Tovarning toifasini tanlash</a>
                </div>
            </div>

            <!-- ===== 7. TOVAR XUSUSIYATLARI ===== -->
            <div class="glass-card p-6 sm:p-8 border border-white/5 rounded-none border-t-0">
                <h3 class="text-lg font-bold text-white mb-3">Tovar xususiyatlari</h3>
                <div class="glass-card p-4 rounded-xl border border-white/5 mb-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                        <div><div class="font-bold text-white mb-1">Xususiyatlar nimaga muhim</div><div class="text-gray-500">Bu xaridorlarga yordam beradi va qaytarishlarning sonini kamaytiradi</div></div>
                        <div><div class="font-bold text-white mb-1">Xususiyatlarning misollari</div><div class="text-gray-500">Material, ishlab chiqaruvchi mamlakat, o'lchamlar, rang</div></div>
                        <div><div class="font-bold text-amber-400 mb-1">Qancha batafsil bo'lsa, shuncha yaxshi</div><div class="text-gray-500">Bu kartochkaning Google va Yandeksda chiqishini yaxshilaydi</div></div>
                    </div>
                </div>
                <a href="#" class="text-indigo-400 text-xs font-medium hover:underline mb-4 inline-block">Yo'riqnomada batafsil</a>
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1 relative">
                        <input type="text" id="kc-xus-uz" maxlength="255" placeholder="O'zbek tilida asosiy xususiyat" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-indigo-500/50 pr-16 transition-all">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-600"><span id="kc-xus-uz-count">0</span>/255</span>
                    </div>
                    <div class="flex-1 relative">
                        <input type="text" id="kc-xus-ru" maxlength="255" placeholder="Rus tilida asosiy xususiyat" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-indigo-500/50 pr-16 transition-all">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-600"><span id="kc-xus-ru-count">0</span>/255</span>
                    </div>
                </div>
            </div>

            <!-- ===== 8. O'LCHAMLI SETKA ===== -->
            <div class="glass-card p-6 sm:p-8 border border-white/5 rounded-none border-t-0">
                <h3 class="text-lg font-bold text-white mb-3">O'lchamli setka</h3>
                <div class="glass-card p-4 rounded-xl border border-white/5 mb-4">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                        <div><div class="font-bold text-indigo-400 mb-1">O'lchamlar nimaga muhim</div><div class="text-gray-500">Xaridorlarga to'g'ri o'lcham tanlashga yordam beradi</div></div>
                        <div><div class="font-bold text-white mb-1">Ayniqsa kiyimlarda</div><div class="text-gray-500">60% xaridorlar o'lcham ko'rsatilmagan tovarni sotib olmaydi</div></div>
                        <div><div class="font-bold text-amber-400 mb-1">Imkon qadar batafsil</div><div class="text-gray-500">Masalan: bel aylanasi 80-90sm, sonlar aylanasi 90-98sm</div></div>
                        <div><div class="font-bold text-white mb-1">Yana nima ko'rsatish mumkin</div><div class="text-gray-500">Tovarning katta-kichikligi va vazni</div></div>
                    </div>
                </div>
                <a href="#" class="text-indigo-400 text-xs font-medium hover:underline">Yo'riqnomada batafsil</a>
                <div class="mt-4 flex flex-col sm:flex-row gap-4">
                    <div class="flex-1 relative">
                        <input type="text" id="kc-olcham-uz" maxlength="255" placeholder="O'zbek tilida o'lcham ma'lumoti" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-indigo-500/50 pr-16 transition-all">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-600"><span id="kc-olcham-uz-count">0</span>/255</span>
                    </div>
                    <div class="flex-1 relative">
                        <input type="text" id="kc-olcham-ru" maxlength="255" placeholder="Rus tilida o'lcham ma'lumoti" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm placeholder-gray-600 focus:outline-none focus:border-indigo-500/50 pr-16 transition-all">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-600"><span id="kc-olcham-ru-count">0</span>/255</span>
                    </div>
                </div>
            </div>

            <!-- ===== 9. TARKIB ===== -->
            <div class="glass-card p-6 sm:p-8 border border-white/5 rounded-none border-t-0">
                <h3 class="text-lg font-bold text-white mb-1">Tarkib</h3>
                <a href="#" class="text-indigo-400 text-xs font-medium hover:underline">Yo'riqnomada batafsil</a>
                <div class="mt-4">
                    <button type="button" class="px-5 py-2.5 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-sm font-semibold hover:bg-indigo-500/20 transition-all"><i class="fa-solid fa-plus mr-1"></i> Qo'shish</button>
                </div>
            </div>

            <!-- ===== 10. FOYDALANISH YO'RIQNOMASI ===== -->
            <div class="glass-card p-6 sm:p-8 border border-white/5 rounded-none border-t-0">
                <h3 class="text-lg font-bold text-white mb-3">Foydalanish bo'yicha yo'riqnoma</h3>
                <div class="glass-card p-4 rounded-xl border border-white/5 mb-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div><div class="font-bold text-white mb-1">Tovardan qanday foydalanishni aytib bering</div><div class="text-gray-500">Bu murakkab tovarlarni sotishda yordam beradi</div></div>
                        <div><div class="font-bold text-white mb-1">Mijozlarga yordam bering</div><div class="text-gray-500">Yo'l-yo'riqlar xizmat muddatini uzaytiradi va buzilishning oldini oladi</div></div>
                    </div>
                </div>
                <a href="#" class="text-indigo-400 text-xs font-medium hover:underline">Yo'riqnomada batafsil</a>
                <div class="mt-4">
                    <button type="button" class="px-5 py-2.5 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-sm font-semibold hover:bg-indigo-500/20 transition-all"><i class="fa-solid fa-plus mr-1"></i> Qo'shish</button>
                </div>
            </div>

            <!-- ===== 11. SERTIFIKATLAR ===== -->
            <div class="glass-card p-6 sm:p-8 border border-white/5 rounded-t-none border-t-0">
                <h3 class="text-lg font-bold text-white mb-3">Sertifikatlar</h3>
                <div class="glass-card p-4 rounded-xl border border-white/5 mb-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                        <div><div class="font-bold text-indigo-400 mb-1">Qayerdan olinadi</div><div class="text-gray-500">Davlat akkreditatsiya markazi (DAM) beradi</div></div>
                        <div><div class="font-bold text-white mb-1">Format</div><ul class="text-gray-500 list-disc list-inside"><li>JPEG, JPG, PNG</li><li>5 Mb dan katta emas</li></ul></div>
                        <div><div class="font-bold text-white mb-1">Agar faqat PDF bo'lsa</div><div class="text-indigo-400 text-xs">Har bir sahifani skrinshot qilib rasmini qo'shing</div></div>
                    </div>
                </div>
                <a href="#" class="text-indigo-400 text-xs font-medium hover:underline">Yo'riqnomada batafsil</a>
                <div class="mt-4">
                    <label class="group inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-sm font-semibold hover:bg-indigo-500/20 transition-all cursor-pointer">
                        <i class="fa-solid fa-plus"></i> Qo'shish
                        <input type="file" accept="image/*" multiple class="hidden" id="kc-sertifikat">
                    </label>
                </div>
            </div>

        </div>

        <!-- AI GENERATE BUTTON -->
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-gray-500"><i class="fa-solid fa-wand-magic-sparkles text-indigo-400 mr-1"></i> AI barcha maydonlarni avtomatik to'ldirishi mumkin</p>
            <div class="flex gap-3">
                <button type="button" onclick="kcGenerateAI()" class="group relative flex items-center gap-2.5 px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold text-sm transition-all shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 hover:scale-[1.02] active:scale-[0.98] overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                    <i class="fa-solid fa-wand-magic-sparkles relative z-10"></i>
                    <span class="relative z-10">AI bilan to'ldirish</span>
                </button>
                <button type="button" class="px-6 py-3 rounded-xl bg-white/5 border border-white/10 text-gray-400 hover:text-white hover:bg-white/10 font-semibold text-sm transition-all">
                    Saqlash va davom etish <i class="fa-solid fa-arrow-right ml-1"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('[id^="kc-nom-"], [id^="kc-qisqa-"], [id^="kc-xus-"], [id^="kc-olcham-"]').forEach(el => {
    const countEl = document.getElementById(el.id + '-count');
    if (countEl) el.addEventListener('input', () => { countEl.textContent = el.value.length; });
});

function kcGenerateAI() {
    const btn = event.currentTarget;
    const origHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Generatsiya qilinmoqda...';
    btn.disabled = true; btn.classList.add('opacity-70');
    setTimeout(() => {
        const fills = {
            'kc-nom-uz': 'Erkaklar uchun klassik futbolka – Premium paxta, S-3XL',
            'kc-nom-ru': 'Мужская классическая футболка – Премиум хлопок, S-3XL',
            'kc-qisqa-uz': 'Yuqori sifatli 100% organik paxtadan tayyorlangan erkaklar futbolkasi. Yoqimli va nafis dizayn, har qanday mavsumga mos.',
            'kc-qisqa-ru': 'Мужская футболка из 100% органического хлопка высшего качества. Приятный и элегантный дизайн, подходит для любого сезона.',
            'kc-tavsif-uz': 'Bu futbolka 100% organik paxtadan tayyorlangan.\n\nXususiyatlari:\n• Material: 100% organik paxta\n• Og\'irligi: 180 g/m²\n• O\'lchamlar: S, M, L, XL, 2XL, 3XL\n• Ranglar: Qora, Oq, Ko\'k\n• Ishlab chiqaruvchi: O\'zbekiston',
            'kc-tavsif-ru': 'Эта футболка изготовлена из 100% органического хлопка.\n\nХарактеристики:\n• Материал: 100% органический хлопок\n• Плотность: 180 г/м²\n• Размеры: S, M, L, XL, 2XL, 3XL\n• Цвета: Черный, Белый, Синий\n• Производство: Узбекистан',
            'kc-xus-uz': 'Material: 100% paxta, Rang: Qora, O\'lcham: S-3XL',
            'kc-xus-ru': 'Материал: 100% хлопок, Цвет: Чёрный, Размер: S-3XL',
        };
        Object.entries(fills).forEach(([id, val]) => {
            const el = document.getElementById(id);
            if (el) { el.value = val; el.dispatchEvent(new Event('input')); el.style.borderColor = 'rgba(99,102,241,0.5)'; setTimeout(() => { el.style.borderColor = ''; }, 2000); }
        });
        const cat = document.getElementById('kc-toifasi');
        if (cat) cat.value = 'kiyim';
        btn.innerHTML = origHTML; btn.disabled = false; btn.classList.remove('opacity-70');
    }, 2000);
}
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
