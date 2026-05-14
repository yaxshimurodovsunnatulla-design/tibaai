<?php
/**
 * Tiba AI — Category design prompts
 * categoryModifier comes FIRST so AI follows exact colors/composition
 */

function getCategoryDesignPrompt($category, $prompt) {

    // Pick random category variant FIRST
    $variants = [

        'electronics' => [
            // Reference: Air 31 earphone — white bg, stat panel, vertical stripes
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: CLEAN WHITE (#FFFFFF) with 3 thick vertical color stripes (brand red or teal) along the RIGHT edge only. NOT blue — white.\nCOMPOSITION: Product (earphones/device) in RIGHT zone (55%), large, studio-lit, floating, text-free. LEFT zone: one large WARM BEIGE (#F5ECD7) rounded rectangle card (soft shadow, border-radius 20px). Inside card: STAT 1 — giant number (e.g. '7') in bold dark, small italic label below ('saatlik ishlash'). Thin divider. STAT 2 — giant number ('180') + small label. Below card: blue circular checkmark badge + horizontal pill showing platform icons. SHORT bold tagline top-right corner small. Zero text on product.",

            // Reference: Dark pheromone women — black+red dramatic, lifestyle photo
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: DEEP BLACK (#0A0A0A) with vivid CRIMSON RED (#C62828) radial spotlight glow emanating from behind product center. NOT blue — black and red.\nCOMPOSITION: Product center-right, large, dramatic lighting, text-free. TOP-LEFT text zone: product CATEGORY small caps, product name in ENORMOUS bold white italic (fills 80% zone width). MIDDLE-LEFT: dark rounded rectangle badge (#1A1A1A border #C62828) with key BENEFIT text bold white. BELOW badge: large bold white text (secondary benefit). BOTTOM-LEFT: lifestyle photo of person using product (small, realistic). BOTTOM STRIP: 2 icon+text feature pills side by side (⏱ feature | icon feature).",

            // Reference: PUBG gaming sleeve — purple-black, neon, gaming
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: DEEP PURPLE-BLACK gradient (#0A0008 → #3D0060). Swirling VIOLET/MAGENTA smoke fog wisps. Faint gaming crosshair or skull watermark top-right. NOT blue — purple-black.\nCOMPOSITION: Product center-right LARGE, angled dramatically, NEON PURPLE ring glow beneath — completely text-free. TOP full width: product name in ENORMOUS ultra-bold italic metallic-white (fills canvas width, 3D extrude effect), tagline bold below. LEFT vertical list: 3 features — [small icon] + ALL-CAPS BOLD white title + thin gray description beneath. BOTTOM-RIGHT: quantity badge (e.g. '4 ta') on thick YELLOW (#FFD600) paint-brush stroke. BOTTOM STRIP: small packaging image + tags row (game/platform names).",

            // Reference: Pheromone for Men — dark navy stars, ingredient grid
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: DARK NAVY (#0D1B3E) → DEEP PURPLE (#2D1B69) gradient with subtle starfield/galaxy texture. NOT blue — deep navy-purple space.\nCOMPOSITION: Product bottle/device CENTER, large, premium studio lit, text-free. TOP: product name ENORMOUS bold white + italic script sub-tagline. LEFT ZONE: 2x3 grid of ingredient/feature tiles — each tile: small square image/icon + bold name below (e.g. Vanilla tile, Cardamom tile). RIGHT: large circle badge with key spec or age rating. BOTTOM-LEFT: ⏱ icon + duration bold + description small.",
        ],

        'beauty' => [
            // Reference: Pheromone Women — dark seductive, dramatic red glow
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: DEEP BLACK (#080808) with CRIMSON-RED (#B71C1C) radial glow behind product. NOT blue — black+red.\nCOMPOSITION: Product bottle center-right, large, dramatic backlit glow, text-free. TOP-LEFT: gender symbol icons + product category in large bold white italic (fills width). MIDDLE-LEFT: dark rounded badge with key CLAIM text (2-3 words bold white). BELOW: secondary bold white text (another claim). BOTTOM-LEFT: small lifestyle/model photo circle. BOTTOM ROW: 2 pills — [⏱ icon + feature] [🧴 icon + type].",

            // Reference: Creed Aventus — clean gray, ingredient photos stacked right
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: LIGHT NEUTRAL GRAY (#EBEBEB) → soft white. NOT blue — light gray/white.\nCOMPOSITION: Product bottle LEFT-CENTER, large, perfect studio lighting, text-free. TOP-LEFT: product name in LARGE bold dark sans-serif. TOP-RIGHT: diagonal dark banner (chevron shape) with 2 bold text lines (key specs). RIGHT COLUMN: 4-5 ingredient rows stacked — each row: square ingredient photo (50x50px) + ingredient name bold beside it. BOTTOM-LEFT: volume 'Xxml' in LARGE bold dark. Style: Creed/niche fragrance editorial.",

            // Reference: Pheromone Men — navy stars, ingredient grid tiles
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: DARK NAVY-INDIGO (#0D1B3E → #1A237E) with soft starfield. NOT blue — dark indigo.\nCOMPOSITION: Product center, large, text-free. TOP: category name HUGE bold white + italic sub-text. LEFT: 2x3 grid tiles (ingredient image + name). RIGHT: large circle badge (age/type). BOTTOM: duration stat — ⏱ icon + GIANT number bold + 'soat' small.",

            // Clean ingredient science
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: PURE WHITE (#FFFFFF) with scattered real botanical elements (rose petals, citrus slices, herb leaves matching product). NOT blue — white+natural.\nCOMPOSITION: Product LEFT angled, large, studio lit, text-free. RIGHT zone: product category LARGE bold with color accent badge, PRIMARY INGREDIENT name in GIANT bold (e.g. 'VITAMIN C'), sub-ingredients list small below, volume bold. Floating mist/drop effect. Ingredient-matched accent color (orange/pink/green).",
        ],

        'home' => [
            // Reference: Kukmara — diagonal red+yellow, callout arrows
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: DIAGONAL SPLIT — upper-left 65% DEEP CRIMSON (#C62828), lower-right 35% GOLDEN YELLOW (#FFD600). NOT blue — red+yellow.\nCOMPOSITION: Product (cookware/kitchen item) LARGE, slightly angled, straddling the diagonal split — center text-free. Brand logo top-center small white. TOP-LEFT text zone: category label small white, product name in ENORMOUS bold italic white (e.g. 'TOVA' / 'NABORI'). CALLOUT ARROWS: 2-3 thin white lines from product parts → rounded pill labels outside product body (lid → 'shisha qopqoq', handle → 'qizib ketmaydi'). BOTTOM FULL WIDTH: size GIANT bold white ('24/26 SM'), cookware compatibility icon row (🔥⚡🍳), flag + origin badge.",

            // IKEA lifestyle — warm interior
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: Warm blurred INTERIOR PHOTO (modern kitchen, window light, wood/marble surfaces). NOT blue — warm amber/wood tones.\nCOMPOSITION: Product on real marble/wood surface RIGHT, large, naturally lit, text-free. LEFT zone: product name dark bold, primary DIMENSION ('25×15 SM') in GIANT teal numerals, floating white feature cards (shadow, rounded): heat-resistant / dishwasher-safe / eco / set count. Cert badge bottom.",

            // Clean spec grid — sage green
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: SAGE GREEN (#E8F5E9) → white gradient. NOT blue — green/white.\nCOMPOSITION: Product RIGHT center, perfectly lit, text-free. LEFT: brand + name with green underline, 2-column spec grid [icon → name → bold value], primary spec LARGE teal numerals, checkmark list (easy-clean / food-safe / durable). Warranty badge corner.",

            // Bold red kitchen hero
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: DEEP RED gradient (#D32F2F → #B71C1C). NOT blue — bold red.\nCOMPOSITION: Product shown in USE context (pan with food, kettle pouring) — center text-free. TOP: product name ENORMOUS bold white fills full width. BOTTOM: size GIANT white numerals + compatibility icons row + flag badge.",
        ],

        'clothing' => [
            // Military hat style — clean white, olive accents, dot callout lines
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: CLEAN WHITE (#FAFAFA) or very light cream. NOT blue — white.\nCOMPOSITION: Product (garment/hat/clothing) CENTER-RIGHT, large, perfect studio lighting, text-free. TOP: star symbol small + product name in LARGE bold OLIVE/DARK BROWN (#4E342E or #33691E) sans-serif. LEFT COLUMN: 4 feature rows — each row: rounded olive badge [icon] + BOLD FEATURE TITLE + thin description + small dot-line connecting to product callout. BOTTOM: size table in olive rounded pills ('54 / 56 / 58'). BOTTOM-RIGHT: small detail inset circle (texture/badge closeup).",

            // Contrast lookbook — charcoal + white split
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: LEFT 45% DEEP CHARCOAL (#2D2D2D), RIGHT 55% PURE WHITE (#FFFFFF). NOT blue — charcoal+white.\nCOMPOSITION: Product in white RIGHT zone, text-free. LEFT dark zone: name LARGE elegant white, material 'PAXTA 100%' bold, size range, slim gold diagonal divider line. Bottom: material badge + care icons. Color swatch dots.",

            // Bold street drop
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: SOLID BOLD COLOR matching dominant product color (navy for navy jacket, burgundy for red hoodie, forest for green). NOT blue unless product is blue.\nCOMPOSITION: Product angled RIGHT, dramatic 3D shadows, text-free. LEFT: name EXTRA LARGE bold white (fills width), material stat enormous contrasting text, sticker-badges slightly rotated (size range / wash / season / colors).",
        ],

        'food' => [
            // Organic — white + scattered ingredients
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: PURE WHITE (#FFFFFF) with real ingredient elements scattered (green leaves, vegetables, herbs matching product). NOT blue — white+natural.\nCOMPOSITION: Package RIGHT angled, text-free. LEFT: name large dark bold, '100% TABIIY' GIANT green bold, HALAL badge prominent, icon features (🌿 GMO-free / weight / shelf life / flag). Nutrition mini-circle.",

            // Appetite golden
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: WARM AMBER-GOLD gradient (#FF8F00 → #FFD740) — kitchen warmth. NOT blue — amber/gold.\nCOMPOSITION: Package + prepared dish inset circle RIGHT, text-free. LEFT: name warm rustic serif, weight GIANT numerals, Halal/Natural icons row. Origin badge.",

            // Craft paper
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: KRAFT PAPER texture (#C8A882 — natural light brown). NOT blue — craft brown.\nCOMPOSITION: Product center, natural daylight style, text-free. LEFT: name ecological font, ingredient list 🌿 per item, HALAL ✅ cert badge large, weight bold, storage temp small.",
        ],

        'kids' => [
            // Synergetic bubble promo — sky blue + big spheres
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: SKY BLUE gradient (#64B5F6 → #E3F2FD) with 12-15 large floating GLOSSY SPHERE bubbles in various sizes. NOT navy/dark blue — light sky blue with transparent bubbles.\nCOMPOSITION: Product bottle/toy RIGHT large, bright studio lit, text-free. LEFT TOP: product CATEGORY in GIANT ultra-bold dark navy condensed text (e.g. 'GEL' / 'KREM' / 'O\'YINCHOQ'), purpose descriptor large bold below, composition/tag italic small. LEFT BOTTOM: two large rounded badges — volume ('1L' / '500ML') ENORMOUS in white rounded badge + count/uses ('33+') bold rounded badge. Lifestyle prop corner (plush toy/duck). Brand seal top-right small.",

            // Rainbow playful
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: PASTEL RAINBOW gradient (yellow → pink → sky → mint). NOT dark blue — bright pastels.\nCOMPOSITION: Product center, soft safety lighting, text-free. OUTER ZONES: name LARGE playful bold rounded, AGE '3-7 YIL' in STAR-SHAPED giant badge, feature badges different colors each slightly rotated (CE✅ / non-toxic / washable / developmental). Safety cert bottom.",

            // Baby pastel
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: POWDER PINK (#FCE4EC) or lavender gradient. NOT blue — soft pink/lavender.\nCOMPOSITION: Product on soft fabric/blanket, center text-free. OUTER: name gentle rounded, '0+ OY' safety badge soft circle (PRIMARY stat), thin badges (from-birth / hypoallergenic / skin-safe). Medical badge bottom.",
        ],

        'sport' => [
            // Posture corrector style — dark navy + person wearing product
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: DARK NAVY (#0D1B3E) with subtle radial BLUE-CYAN glow (#1565C0) from center. NOT generic blue — dark navy + glowing.\nCOMPOSITION: LIFESTYLE PHOTO — person wearing/using the product fills right 60% (from behind or side, showing product in use) — this IS the product zone, text-free over person. TOP-LEFT: product name in LARGE bold white (2 lines, fills left width). LEFT COLUMN (middle): 3 features — [icon] + bold feature title white + thin description gray. BOTTOM-LEFT: large MAGENTA (#E91E63) or PINK rounded circle badge with bold claim text ('21 kun natijalari'). BOTTOM: fine print small.",

            // Nike/UA dark aggressive
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: PURE BLACK (#0A0A0A) with NEON ORANGE (#FF6D00) diagonal energy slashes. NOT blue — black+orange.\nCOMPOSITION: Product from dramatic angle, starburst RIGHT, text-free. LEFT: name ULTRA LARGE condensed uppercase fills width, angular hexagon feature badges (shock / waterproof / weight / anti-slip).",

            // Outdoor adventure
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: Blurred MOUNTAIN/TRAIL terrain photo. NOT blue studio — nature.\nCOMPOSITION: Product center bold text-free. OUTER: name BOLD white dark shadow, stamp-style features rotated. KEY STAT weight GIANT. Warranty badge.",
        ],

        'health' => [
            // Clinical clean — white mint
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: PURE WHITE (#FFFFFF) with faint mint-green edges. DNA helix subtle watermark. NOT blue — clinical white.\nCOMPOSITION: Product upright RIGHT, clinical studio lighting, text-free. LEFT: brand+name dark navy, '100% TABIIY' GIANT bold GREEN (#2E7D32) 3x body, structured medical grid [🔬 Klinik tasdiqlangan / 💊 Kunlik doza / 🌿 Tabiiy formula / ✅ GMP]. Dosage table. Bottom: '14 KUNDA NATIJA ✅' badge.",

            // Warm vitamin yellow
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: SUNNY YELLOW (#FFC107 → #FFF8E1) gradient — vitamin energy. NOT blue — warm yellow.\nCOMPOSITION: Product RIGHT text-free. LEFT: brand tiny, name+DOSAGE GIANT bold black ('VITAMIN C 550mg'), quantity badge RED circle ('30 TAB' white), two ORANGE (#FF9800) rounded benefit cards. Flavor badge + fruit icon. Usage small.",

            // Botanical wellness
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: FOREST GREEN (#1B5E20) → white gradient, botanical leaf watermark. NOT blue — green/white.\nCOMPOSITION: Product surrounded by real ingredient visuals center, text-free. OUTER: name dark green elegant, 'SERTIFIKATLANGAN ✅' badge top, PRIMARY INGREDIENT GIANT bold (dominant), nature icon benefit badges.",
        ],

        'tools' => [
            // Industrial dark — yellow+charcoal
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: DARK CHARCOAL (#1C1C1C) concrete texture with SAFETY YELLOW (#FFD600) accent lines. NOT blue — dark gray+yellow.\nCOMPOSITION: Tool on metal/concrete surface slightly angled, dramatic spotlight, text-free. OUTER: name LARGE bold YELLOW/WHITE, PRIMARY MATERIAL ('CHROME VANADIUM CrV') GIANT bold (dominant), stamp spec tags (dimensions / weight / hardness / warranty). Scale compare. Bottom: 'PROFESSIONAL 🔧' banner.",

            // Engineering blueprint — deep blue
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: ENGINEERING BLUE (#0D47A1) with white technical grid lines. NOT generic blue — blueprint blue with grid.\nCOMPOSITION: Tool with callout arrow annotations to specific parts. OUTER: name bold white technical font, spec data engineering format, PRIMARY STAT amber/yellow. 'PROFESSIONAL GRADE' badge.",

            // Bold orange promo
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: BOLD ORANGE gradient (#FF6F00 → #E65100). NOT blue — vibrant orange.\nCOMPOSITION: Tool dramatic lighting, crisp shadows, text-free. OUTER: name ENORMOUS bold white fills top, material/dimensions/warranty badges, KEY STAT GIANT on orange. Bottom: 'PROFESSIONALS 🔧' badge.",
        ],

        'auto' => [
            // Carbon fiber dark
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: BLACK carbon-fiber texture with CHROME lines and subtle RED (#C62828) glow bottom edge. NOT blue — black+carbon.\nCOMPOSITION: Product on reflective dark surface (showroom reflection) RIGHT text-free. LEFT: name chrome-silver gradient bold, 'UNIVERSAL' badge, metal-bordered spec blocks (installation time / temp range / warranty). Bottom: car brand list. ISO badge.",

            // Industrial yellow
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: DARK INDUSTRIAL (#1A1A1A) concrete/pegboard texture, spot lighting. NOT blue — dark industrial.\nCOMPOSITION: Product dramatic shadows text-free. OUTER: name SAFETY YELLOW on dark (primary), size GIANT white numerals, rough tag-label badges. Warranty+cert.",

            // Deep navy speed
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: DEEP NAVY METALLIC gradient with ELECTRIC BLUE (#1565C0) accent geometric shapes. Different from plain blue — metallic navy.\nCOMPOSITION: Product + auto context inset text-free. OUTER: name sleek italic, card specs [dot+bold+desc], 3-step install visual (1→2→3).",
        ],

        'accessories' => [
            // Luxury noir — black+gold
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: VELVET BLACK (#0A0A0A) with warm spotlight above, floating GOLD dust particles. NOT blue — black+gold.\nCOMPOSITION: Product on marble/velvet pedestal center, artistic shadows, text-free. OUTER: name LARGE GOLD (#D4AF37) metallic bold, slim gold feature badges (genuine leather / dimensions / gift box). Stitching inset circle. Cert badge.",

            // Marble editorial — white+gold
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: WHITE CARRARA MARBLE texture, natural window light. NOT blue — marble white.\nCOMPOSITION: Product flat-lay RIGHT text-free. LEFT: name dark serif underline, material bold accent, minimal pill features, dimensions. Style inset circle. Cert badge.",

            // Gift burgundy
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: RICH BURGUNDY-PURPLE gradient (#4A148C → #880E4F) with soft bokeh orbs. NOT blue — deep burgundy.\nCOMPOSITION: Product emerging from gift box with ribbon, text-free. OUTER: 'SOVG\'A UCHUN 🎁' LARGE white, name gold serif, glassmorphism cards. Occasion badges.",
        ],

        'pet' => [
            // Warm cream
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: WARM CREAM (#FFF8E1 → #FFECB3) with subtle paw-print watermark. NOT blue — warm cream.\nCOMPOSITION: Product center text-free. Inset: happy pet circle. OUTER: name warm brown bold rounded, 'SIZNING UYG\'OTINGIZ ❤️' banner, pastel rounded badges (breed/non-toxic/easy-clean/vet✅/sizes). MOOD: Royal Canin.",

            // Vet clinical green
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: WHITE (#FFFFFF) with mint-green (#E8F5E9) edges, green cross watermark. NOT blue — medical white+green.\nCOMPOSITION: 'VETERINAR TAVSIYA ETADI ✅' badge VERY LARGE prominent. Product text-free. OUTER: name dark green bold, clinical ingredient/nutrition list, breed chart, vet seal LARGE.",

            // Sky blue playful
            "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: BRIGHT SKY BLUE (#87CEEB) with white clouds and paw-print trail. NOT dark blue — bright sky blue.\nCOMPOSITION: Happy pet with product, text-free center. OUTER: name LARGE playful colorful, 6-8 sticker-badges (durable/sizes/interactive). Fun energy.",
        ],
    ];

    // Get random variant for category — this is the PRIMARY visual instruction
    $categoryModifier = '';
    if (!empty($category) && isset($variants[$category])) {
        $categoryModifier = $variants[$category][array_rand($variants[$category])];
    } else {
        // Default fallback if no category match
        $categoryModifier = "=== VISUAL DESIGN TEMPLATE ===\nCANVAS: 1080x1440px. Background: Choose ONE of these — NOT plain blue: (a) Deep black with colorful spotlight, (b) Bold red/crimson gradient, (c) Warm white with natural elements, (d) Golden amber gradient, (e) Rich burgundy. Each request must have a DIFFERENT color scheme.\nCOMPOSITION: Product RIGHT large text-free. LEFT: product name GIANT bold, key spec ENORMOUS (dominant), 3-4 feature badges with icons.";
    }

    // Forbidden words enforcement — appended last as a hard constraint
    $salesRules = "\n=== FORBIDDEN WORD CHECK (scan entire image before output) ===\nREMOVE all instances of: Premium, Original, Hit, TOP, N1, Best Seller, star ratings (★), Kafolat, Garantiya, Chegirma, Aksiya, Bepul, Uzum, Ozon, WB, Wildberries, Yandex, 'Sotib oling', 'Xarid qiling', any call-to-action.\nZERO text touches the product. Minimum 30px gap between any text/badge and product edges.\nOnly features from the provided list — do NOT invent specs.";

    // ORDER: category template FIRST (dominant), then base prompt, then rules
    $finalPrompt  = $categoryModifier . "\n\n";
    $finalPrompt .= $prompt;
    $finalPrompt .= $salesRules;

    return $finalPrompt;
}
