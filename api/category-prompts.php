<?php
function getCategoryDesignPrompt($category, $prompt) {

    $variants = [

        'electronics' => [
            "=== DESIGN TEMPLATE A1: AIR-SERIES STAT PANEL ===\nBG: CLEAN WHITE (#FFFFFF), 3 thick vertical RED stripes right edge. NOT blue.\nProduct RIGHT 55%, studio-lit, text-free. LEFT: large warm-beige rounded card — STAT1 GIANT number + italic label, divider, STAT2 GIANT number + label. Below card: verified checkmark + platform icons pill. Tagline small top-right.",
            "=== DESIGN TEMPLATE A2: DARK GAMING PURPLE ===\nBG: BLACK-PURPLE gradient (#0A0008→#3D0060), violet smoke fog, crosshair watermark. NOT blue — purple-black.\nProduct center-right LARGE angled, NEON PURPLE ring glow below — text-free. TOP full width: name ENORMOUS bold italic metallic-white. LEFT: 3 features [icon+CAPS TITLE white+gray desc]. BOTTOM-RIGHT: qty badge on YELLOW paint-brush. BOTTOM: package image + game tags.",
            "=== DESIGN TEMPLATE A3: DARK SEDUCTIVE RED ===\nBG: DEEP BLACK (#080808), CRIMSON RED (#C62828) radial glow from product center. NOT blue — black+red.\nProduct center-right, backlit glow, text-free. TOP-LEFT: category small, name ENORMOUS bold italic white. MIDDLE-LEFT: dark rounded badge with key claim bold white. BOTTOM-LEFT: lifestyle person photo small circle. BOTTOM: 2 feature pills [icon+text].",
            "=== DESIGN TEMPLATE A4: NAVY STAR INGREDIENT GRID ===\nBG: DARK NAVY (#0D1B3E) → DEEP INDIGO (#1A237E), soft starfield texture. NOT plain blue — deep navy-purple.\nProduct center large, premium lit, text-free. TOP: name HUGE bold white + italic script tagline. LEFT: 2×3 ingredient/feature tile grid — each tile: small square image + bold name. RIGHT: large circle badge (spec/rating). BOTTOM: ⏱ icon + GIANT hours number + label.",
            "=== DESIGN TEMPLATE A5: CARBON FIBER TECH ===\nBG: CARBON BLACK texture with subtle CYAN (#00E5FF) circuit lines. NOT blue — carbon black.\nProduct dramatic angle RIGHT, neon cyan glow beneath, text-free. LEFT: name bold white Inter Black, key spec GIANT CYAN numerals 3x, feature stack [cyan icon+bold value+gray desc]. Compatibility icons row bottom.",
            "=== DESIGN TEMPLATE A6: CLEAN EDITORIAL GRAY ===\nBG: LIGHT COOL GRAY (#F0F2F5), subtle tech grid watermark. Professional, NOT blue.\nProduct RIGHT text-free, three-point studio lighting. LEFT: name dark bold sans, key spec CORPORATE BLUE GIANT, feature rows [circle icon → BOLD value → desc]. Certification badges bottom.",
            "=== DESIGN TEMPLATE A7: NEON GLASSMORPHISM ===\nBG: NAVY-INDIGO gradient (#0A0A2E→#1A1A4E), faint circuit watermark, product center-right with neon CYAN/PURPLE halo, text-free. LEFT: name futuristic glow font, 4 glassmorphism cards [icon+BOLD SPEC+label]. Platform icons bottom.",
            "=== DESIGN TEMPLATE A8: BOLD ORANGE PROMO ===\nBG: VIVID ORANGE gradient (#FF6F00→#E65100). High energy, NOT blue.\nProduct center dramatic lighting, text-free. TOP: name ENORMOUS bold white fills width. LEFT: feature badges dark-overlay. KEY STAT GIANT dark on orange. Bottom quantity badge.",
        ],

        'beauty' => [
            "=== DESIGN TEMPLATE B1: BLACK RED SEDUCTIVE ===\nBG: DEEP BLACK (#080808), CRIMSON-RED (#B71C1C) radial glow. NOT blue — black+red.\nProduct bottle center-right, dramatic backlit, text-free. TOP-LEFT: gender icons + category LARGE bold italic white. MIDDLE: dark badge with claim bold. BOTTOM-LEFT: lifestyle model small circle. BOTTOM: 2 feature pills.",
            "=== DESIGN TEMPLATE B2: EDITORIAL GRAY INGREDIENT ===\nBG: LIGHT NEUTRAL GRAY (#EBEBEB)→white. NOT blue — light gray.\nProduct LEFT large studio lit, text-free. TOP-RIGHT: diagonal dark chevron banner with 2 spec lines. RIGHT COLUMN: 4-5 ingredient rows — square ingredient photo + name bold. BOTTOM-LEFT: volume LARGE bold. Style: Creed/niche fragrance.",
            "=== DESIGN TEMPLATE B3: NAVY STARS INGREDIENT GRID ===\nBG: DARK NAVY (#0D1B3E)→DEEP PURPLE (#2D1B69), starfield. NOT plain blue.\nProduct center text-free. TOP: name HUGE bold white. LEFT: 2×3 ingredient tile grid (image+name). RIGHT: circle age/type badge. BOTTOM: ⏱ duration GIANT number.",
            "=== DESIGN TEMPLATE B4: WHITE BOTANICAL ===\nBG: PURE WHITE (#FFFFFF), real botanical elements scattered (petals/citrus/herbs matching product). NOT blue — white+natural.\nProduct LEFT angled text-free. RIGHT: PRIMARY INGREDIENT GIANT bold (VITAMIN C / RETINOL), sub-ingredients small, volume bold. Mist effect. Ingredient-matched accent color.",
            "=== DESIGN TEMPLATE B5: ROSE GOLD GLAMOUR ===\nBG: CHAMPAGNE→ROSE GOLD gradient (#F5CBA7→#E8A87C), gold dust particles. NOT blue — champagne.\nProduct on velvet text-free. OUTER: name thin gold serif, benefit LARGE elegant, glassmorphism pills (ingredients/cruelty-free/volume). 1-2-3 application icons.",
            "=== DESIGN TEMPLATE B6: CLINICAL RED PROOF ===\nBG: BOLD RED (#D32F2F), WHITE diagonal geometric shapes. NOT blue — red+white.\nProduct RIGHT angled text-free. Clinical seal prominent. KEY STAT '+47%' or '3X' GIANT RED on white panel (dominant element). Category headline. Volume bold.",
            "=== DESIGN TEMPLATE B7: TROPICAL MAGENTA ===\nBG: MAGENTA-PINK gradient (#E91E63→#880E4F), tropical flower silhouettes. NOT blue — vibrant pink.\nProduct bottom text-free. TOP: name EXTRA LARGE dark bold. LEFT: 2×3 ingredient grid (illustration+name). RIGHT: volume GIANT.",
            "=== DESIGN TEMPLATE B8: SOFT LAVENDER LUXURY ===\nBG: SOFT LAVENDER (#EDE7F6)→white, gold accent lines. NOT blue — lavender.\nProduct center pedestal, soft lighting, text-free. OUTER: name elegant serif, benefit LARGE, minimal feature pills. Sophisticated quiet luxury.",
        ],

        'home' => [
            "=== DESIGN TEMPLATE H1: KUKMARA DIAGONAL RED-YELLOW ===\nBG: DIAGONAL SPLIT — upper-left 65% CRIMSON (#C62828), lower-right 35% GOLDEN YELLOW (#FFD600). NOT blue — red+yellow.\nProduct large angled straddling diagonal, text-free. TOP-LEFT: category small, name ENORMOUS bold italic white. CALLOUT ARROWS: thin white lines → pill labels outside product. BOTTOM: size GIANT white + compatibility icons + flag.",
            "=== DESIGN TEMPLATE H2: WARM INTERIOR LIFESTYLE ===\nBG: Blurred WARM KITCHEN INTERIOR photo (wood/marble, window light). NOT blue — warm amber tones.\nProduct on marble surface RIGHT, naturally lit, text-free. LEFT: name dark bold, dimension GIANT teal, floating white feature cards (rounded). Cert badge.",
            "=== DESIGN TEMPLATE H3: BOLD RED KITCHEN HERO ===\nBG: DEEP RED gradient (#D32F2F→#B71C1C), appetite-stimulating. NOT blue — bold red.\nProduct in USE context (food inside pan), text-free. TOP: name ENORMOUS bold white full width. BOTTOM: size GIANT white + compatibility icons + flag.",
            "=== DESIGN TEMPLATE H4: SAGE GREEN SPEC GRID ===\nBG: SAGE GREEN (#E8F5E9)→white gradient, fresh. NOT blue — sage green.\nProduct RIGHT text-free. LEFT: brand+name green underline, 2-col spec grid [icon→name→value], primary stat LARGE teal, checkmark list. Warranty badge.",
            "=== DESIGN TEMPLATE H5: CREAM CALLOUT MAP ===\nBG: WARM CREAM (#FFF8E1), clean professional. NOT blue — cream.\nProduct center with CALLOUT ARROWS to parts → pill labels outside body. TOP: name large bold. BOTTOM: size GIANT + compatibility icon row.",
            "=== DESIGN TEMPLATE H6: DARK PREMIUM COOKWARE ===\nBG: DARK CHARCOAL (#1C1C1C)→BLACK, premium mood. NOT blue — dark charcoal.\nProduct center dramatic side-shadows, text-free. OUTER: name bold white, material LARGE, spec tags (dimensions/weight/warranty). Gold accent details.",
        ],

        'clothing' => [
            "=== DESIGN TEMPLATE C1: WHITE MILITARY EDITORIAL ===\nBG: CLEAN WHITE (#FAFAFA). NOT blue — white.\nProduct CENTER-RIGHT large studio lit, text-free. TOP: star + name LARGE bold OLIVE/DARK BROWN. LEFT: 4 feature rows — olive badge [icon]+BOLD TITLE+desc+dot-line callout. BOTTOM: size table olive rounded pills. Detail inset circle bottom-right.",
            "=== DESIGN TEMPLATE C2: CHARCOAL WHITE SPLIT ===\nBG: LEFT 45% CHARCOAL (#2D2D2D), RIGHT 55% WHITE (#FFFFFF). NOT blue.\nProduct white zone RIGHT text-free. LEFT dark: name LARGE white, material bold, gold diagonal divider. Bottom: material badge + care icons. Color swatches.",
            "=== DESIGN TEMPLATE C3: BOLD COLOR BLOCK ===\nBG: SOLID color matching product (navy for navy jacket, burgundy for red, forest for green). NOT blue unless product is blue.\nProduct angled RIGHT, 3D shadows, text-free. LEFT: name EXTRA LARGE bold white fills width, material stat enormous, sticker-badges rotated (size/wash/season/colors).",
            "=== DESIGN TEMPLATE C4: MINIMAL LUXURY WHITE ===\nBG: PURE WHITE, generous negative space. NOT blue — white.\nProduct RIGHT 60% text-free. LEFT: brand thin serif, name medium-weight, material details very small refined. 2-3 micro labels. Color option tiny circles. No badges. Silent luxury.",
            "=== DESIGN TEMPLATE C5: WARM LINEN EDITORIAL ===\nBG: WARM OFF-WHITE (#F8F4EE), subtle linen texture. NOT blue — warm linen.\nProduct flat-lay RIGHT text-free. LEFT: brand thin serif, material '100% PAXTA' GIANT bold (dominant), size range, wash icons, color dots, fabric macro inset circle. Gold underline accent.",
            "=== DESIGN TEMPLATE C6: DARK STREET POSTER ===\nBG: VERY DARK GRAY (#111111), dramatic top spotlight. NOT blue — near black.\nProduct angled RIGHT, sharp shadows, text-free. TOP: name ULTRA BOLD white fills canvas width. Feature badges as bold yellow tags rotated. SUPREME / OFF-WHITE drop energy.",
        ],

        'food' => [
            "=== DESIGN TEMPLATE F1: WHITE BOTANICAL SCATTER ===\nBG: PURE WHITE (#FFFFFF), real ingredient elements scattered (green leaves/vegetables/herbs). NOT blue — white+natural.\nPackage RIGHT angled text-free. LEFT: name large dark, '100% TABIIY' GIANT green bold, HALAL badge, icon features. Nutrition mini-circle.",
            "=== DESIGN TEMPLATE F2: GOLDEN APPETITE ===\nBG: WARM AMBER-GOLD gradient (#FF8F00→#FFD740). NOT blue — amber/gold.\nPackage + prepared dish inset circle RIGHT text-free. LEFT: name warm rustic serif, weight GIANT numerals, Halal/Natural icons. Origin badge.",
            "=== DESIGN TEMPLATE F3: KRAFT PAPER CRAFT ===\nBG: KRAFT PAPER texture (#C8A882 — natural brown). NOT blue — craft brown.\nProduct center natural daylight style, text-free. LEFT: name ecological font, ingredient list 🌿 per item, HALAL ✅ cert badge, weight bold, storage temp.",
            "=== DESIGN TEMPLATE F4: FRESH GREEN ORGANIC ===\nBG: FRESH GREEN (#4CAF50)→LIME (#8BC34A) gradient. NOT blue — vibrant green.\nProduct RIGHT text-free. LEFT: name bold white, weight GIANT white, feature badges light green pills. HALAL badge prominent. Leaf watermark.",
            "=== DESIGN TEMPLATE F5: DARK PREMIUM FOOD ===\nBG: DEEP CHARCOAL (#1C1C1C), warm amber spotlight. NOT blue — dark charcoal.\nProduct center dramatic, text-free. OUTER: name gold (#D4AF37) bold, weight GIANT amber, premium feel ingredient list white.",
        ],

        'kids' => [
            "=== DESIGN TEMPLATE K1: SKY BLUE BUBBLES PROMO ===\nBG: SKY BLUE gradient (#64B5F6→#E3F2FD), 12-15 large GLOSSY BUBBLE spheres floating. NOT dark navy — light sky blue.\nProduct RIGHT large bright studio lit, text-free. LEFT TOP: category GIANT ultra-bold dark navy (e.g. 'GEL'). LEFT BOTTOM: volume badge ENORMOUS white rounded + count badge bold. Lifestyle prop corner (toy/duck).",
            "=== DESIGN TEMPLATE K2: RAINBOW PASTEL PLAYFUL ===\nBG: PASTEL RAINBOW gradient (yellow→pink→sky→mint). NOT dark blue — bright pastels.\nProduct center soft lighting, text-free. OUTER: name LARGE playful bold rounded, AGE badge STAR-SHAPED giant, feature badges different colors rotated. Safety cert bottom.",
            "=== DESIGN TEMPLATE K3: POWDER PINK BABY ===\nBG: POWDER PINK (#FCE4EC) or LAVENDER gradient. NOT blue — soft pink.\nProduct on soft fabric text-free. OUTER: name gentle rounded, '0+ OY' safety badge soft circle (PRIMARY), thin badges (hypoallergenic/skin-safe). Medical badge.",
            "=== DESIGN TEMPLATE K4: SUNNY YELLOW ENERGETIC ===\nBG: BRIGHT YELLOW (#FDD835)→ORANGE (#FF9800) gradient. NOT blue — sunny yellow.\nProduct center text-free. OUTER: name LARGE playful dark, age badge in circle, fun sticker-badges scattered. 'XAVFSIZ ✅' prominent.",
            "=== DESIGN TEMPLATE K5: MINT GREEN SAFE ===\nBG: SOFT MINT GREEN (#E8F5E9)→white. NOT blue — mint green.\nProduct center text-free. OUTER: name clean dark rounded, safety features as green checkmark list, age badge green circle. Clean and trusted.",
            "=== DESIGN TEMPLATE K6: COSMIC PURPLE ADVENTURE ===\nBG: DEEP PURPLE (#4A148C)→VIOLET (#7B1FA2), playful stars and planets. NOT navy blue — bright purple.\nProduct center text-free. OUTER: name bold white rounded, feature badges in bright colors. Fun adventure energy.",
        ],

        'sport' => [
            "=== DESIGN TEMPLATE S1: DARK NAVY LIFESTYLE POSTURE ===\nBG: DARK NAVY (#0D1B3E), radial CYAN glow from center. NOT generic blue — dark navy+glow.\nLIFESTYLE: person wearing product fills RIGHT 60% (from behind/side), product visible in use — text-free over person. TOP-LEFT: name LARGE bold white 2 lines. LEFT: 3 features [icon+bold white title+gray desc]. BOTTOM-LEFT: MAGENTA circle badge with bold claim. Fine print bottom.",
            "=== DESIGN TEMPLATE S2: BLACK ORANGE AGGRESSIVE ===\nBG: PURE BLACK (#0A0A0A), NEON ORANGE (#FF6D00) diagonal energy slashes. NOT blue — black+orange.\nProduct dramatic angle starburst RIGHT text-free. LEFT: name ULTRA LARGE condensed uppercase fills width, hexagon feature badges.",
            "=== DESIGN TEMPLATE S3: DARK CHARCOAL GYM ===\nBG: DARK CHARCOAL (#1C1C1C), gym-light atmosphere. NOT blue — dark gray.\nProduct in USE context text-free. OUTER: brand bold white top, specs as GIANT % stats ('99%'/'5X'/'360°') — HUGE color number + small desc. Electric blue or neon green accents.",
            "=== DESIGN TEMPLATE S4: NATURE OUTDOOR ===\nBG: Blurred MOUNTAIN/TRAIL terrain photo, motion blur. NOT blue studio — real nature.\nProduct center text-free. OUTER: name BOLD white dark shadow, stamp-style features rotated. KEY STAT GIANT. Warranty badge.",
            "=== DESIGN TEMPLATE S5: PURPLE GAMING SPORT ===\nBG: BLACK-PURPLE (#0A0008→#3D0060), violet smoke, crosshair watermark. NOT navy — purple-black.\nProduct LARGE center-right angled, neon glow text-free. TOP: name ENORMOUS bold italic metallic. LEFT: 3 features bold. BOTTOM: qty badge yellow paint-stroke + compatibility tags.",
            "=== DESIGN TEMPLATE S6: RED POWER SPORT ===\nBG: DEEP RED (#C62828)→BLACK gradient, energy. NOT blue — red+black.\nProduct dramatic angle text-free. TOP: name HUGE bold white. LEFT: feature list with red accent badges. Key stat GIANT white. Performance energy.",
        ],

        'health' => [
            "=== DESIGN TEMPLATE HE1: CLINICAL WHITE MINT ===\nBG: WHITE (#FFFFFF), faint mint edges, DNA helix watermark. NOT blue — clinical white.\nProduct upright RIGHT clinical lit, text-free. LEFT: name dark navy, '100% TABIIY' GIANT GREEN (#2E7D32) 3x, medical grid [🔬Klinik/💊Doza/🌿Tabiiy/✅GMP]. BOTTOM: '14 KUNDA NATIJA ✅' badge.",
            "=== DESIGN TEMPLATE HE2: SUNNY VITAMIN YELLOW ===\nBG: SUNNY YELLOW (#FFC107→#FFF8E1). NOT blue — warm yellow.\nProduct RIGHT text-free. LEFT: name+DOSAGE GIANT bold black, qty RED circle badge, two ORANGE benefit cards. Flavor badge+fruit icon.",
            "=== DESIGN TEMPLATE HE3: BOTANICAL GREEN ===\nBG: FOREST GREEN (#1B5E20)→white, botanical leaf watermark. NOT blue — green.\nProduct surrounded by ingredient visuals, text-free. OUTER: name dark green elegant, 'SERTIFIKATLANGAN ✅' top, PRIMARY INGREDIENT GIANT, nature icon badges.",
            "=== DESIGN TEMPLATE HE4: CLINICAL BLUE AUTHORITY ===\nBG: LIGHT BLUE (#E3F2FD), molecular structure watermark. Different from plain blue — light medical blue.\nProduct RIGHT text-free. LEFT: 'GMP ✅ ISO ✅ HALAL ✅' cert row, 2-col ingredient table, 3-step visual, daily dosage GIANT. 'MUTAXASSISLAR TAVSIYASI 👨‍⚕️' banner.",
            "=== DESIGN TEMPLATE HE5: DARK PREMIUM SUPPLEMENT ===\nBG: DARK CHARCOAL (#1C1C1C)→black, gold accents. NOT blue — dark+gold.\nProduct center dramatic, text-free. OUTER: name gold bold, dosage GIANT amber, ingredient list white, premium feel. Gold shield badge.",
            "=== DESIGN TEMPLATE HE6: WARM ORANGE ENERGY ===\nBG: VIBRANT ORANGE (#FF6F00)→AMBER (#FF8F00). NOT blue — energetic orange.\nProduct RIGHT text-free. LEFT: name bold white, dosage HUGE, feature badges bright white. Energy and vitality mood.",
        ],

        'tools' => [
            "=== DESIGN TEMPLATE T1: DARK CHARCOAL INDUSTRIAL ===\nBG: DARK CHARCOAL (#1C1C1C), concrete texture, YELLOW (#FFD600) accent lines, spotlight. NOT blue — dark+yellow.\nTool on metal surface angled, text-free. OUTER: name bold YELLOW/WHITE, MATERIAL GIANT (dominant), stamp spec tags. 'PROFESSIONAL 🔧'.",
            "=== DESIGN TEMPLATE T2: ENGINEERING BLUEPRINT ===\nBG: ENGINEERING BLUE (#0D47A1), white grid lines, dimension annotations. Blueprint aesthetic — specific blue, not generic.\nTool with callout arrows to parts. OUTER: name bold white technical, spec data engineering format, PRIMARY STAT amber.",
            "=== DESIGN TEMPLATE T3: BOLD ORANGE ENERGY ===\nBG: BOLD ORANGE (#FF6F00→#E65100). NOT blue — vibrant orange.\nTool dramatic lighting text-free. TOP: name ENORMOUS bold white fills width. Badges material/dimensions/warranty. KEY STAT GIANT.",
            "=== DESIGN TEMPLATE T4: SAFETY YELLOW IMPACT ===\nBG: SAFETY YELLOW (#FFD600)→AMBER (#FF8F00). High visibility. NOT blue — yellow.\nTool center dramatic, text-free. OUTER: name bold black large, spec badges dark on yellow. Professional industrial energy.",
            "=== DESIGN TEMPLATE T5: BLACK CHROME PREMIUM ===\nBG: NEAR BLACK (#0D0D0D), chrome metallic lines, red glow edge. NOT blue — black+chrome.\nTool on reflective surface, studio lit, text-free. OUTER: name chrome-silver gradient, specs metal-bordered blocks. ISO badge.",
        ],

        'auto' => [
            "=== DESIGN TEMPLATE AU1: CARBON BLACK ===\nBG: BLACK carbon-fiber texture, CHROME lines, RED (#C62828) glow bottom. NOT blue — black+carbon.\nProduct on showroom floor reflection RIGHT text-free. LEFT: name chrome-silver bold, 'UNIVERSAL' badge, metal-bordered specs. Car brand list. ISO.",
            "=== DESIGN TEMPLATE AU2: INDUSTRIAL DARK ===\nBG: DARK CONCRETE (#1A1A1A) texture, spot lighting. NOT blue — dark industrial.\nProduct dramatic shadows text-free. OUTER: name SAFETY YELLOW, size GIANT white, rough tag badges. Warranty cert.",
            "=== DESIGN TEMPLATE AU3: DEEP NAVY METALLIC ===\nBG: DEEP NAVY METALLIC with ELECTRIC BLUE geometric shapes. Metallic navy — different from plain blue.\nProduct + auto inset text-free. OUTER: name sleek italic, card specs, 3-step install (1→2→3).",
            "=== DESIGN TEMPLATE AU4: RED POWER ===\nBG: DEEP RED (#B71C1C)→BLACK gradient. NOT blue — red+black.\nProduct center dramatic, text-free. OUTER: name bold white, specs red accent cards. Fitment badge. Power aesthetic.",
            "=== DESIGN TEMPLATE AU5: CLEAN GRAY OFFICIAL ===\nBG: LIGHT GRAY (#F5F5F5), clean professional. NOT blue — light gray.\nProduct center perfect studio lit, text-free. OUTER: name dark bold, spec rows clean table format, yellow highlighted key value. Official brand feel.",
        ],

        'accessories' => [
            "=== DESIGN TEMPLATE AC1: VELVET BLACK GOLD ===\nBG: VELVET BLACK (#0A0A0A), warm spotlight, GOLD dust particles. NOT blue — black+gold.\nProduct on marble/velvet pedestal text-free. OUTER: name LARGE GOLD (#D4AF37) metallic bold, slim gold badges. Stitching inset circle. Cert badge.",
            "=== DESIGN TEMPLATE AC2: MARBLE WHITE ===\nBG: CARRARA MARBLE texture, natural window light. NOT blue — marble white.\nProduct flat-lay RIGHT text-free. LEFT: name dark serif underline, material bold accent, minimal pill features. Style inset circle.",
            "=== DESIGN TEMPLATE AC3: BURGUNDY GIFT ===\nBG: RICH BURGUNDY-PURPLE (#4A148C→#880E4F), bokeh orbs. NOT blue — deep burgundy.\nProduct from gift box ribbon, text-free. OUTER: 'SOVG\'A UCHUN 🎁' LARGE white, name gold serif, glassmorphism cards. Occasion badges.",
            "=== DESIGN TEMPLATE AC4: ROSE GOLD LUXURY ===\nBG: ROSE GOLD gradient (#B76E79→#E8A87C), soft and glamorous. NOT blue — rose gold.\nProduct center pedestal, soft lit, text-free. OUTER: name rose-gold metallic, feature pills elegant. Gift-ready mood.",
            "=== DESIGN TEMPLATE AC5: CREAM EDITORIAL ===\nBG: WARM CREAM (#FFF8E1), natural light, generous space. NOT blue — cream.\nProduct large center, minimal text zones. Name thin serif, micro-detail labels. Aesop/luxury editorial quiet.",
        ],

        'pet' => [
            "=== DESIGN TEMPLATE P1: WARM CREAM PAW ===\nBG: WARM CREAM (#FFF8E1→#FFECB3), paw-print watermark. NOT blue — warm cream.\nProduct center text-free. Inset: happy pet circle. OUTER: name warm brown bold rounded, banner, pastel rounded badges (breed/non-toxic/vet✅/sizes).",
            "=== DESIGN TEMPLATE P2: MEDICAL GREEN VET ===\nBG: WHITE (#FFFFFF), mint-green edges (#E8F5E9), green cross watermark. NOT blue — medical white+green.\n'VETERINAR TAVSIYA ✅' badge VERY LARGE. Product text-free. OUTER: name dark green, clinical list, vet seal LARGE.",
            "=== DESIGN TEMPLATE P3: SKY BLUE PLAYFUL ===\nBG: BRIGHT SKY BLUE (#87CEEB), white clouds, paw-print trail. NOT dark blue — bright sky blue.\nHappy pet with product, text-free center. OUTER: name LARGE playful colorful, sticker-badges (durable/sizes/interactive).",
            "=== DESIGN TEMPLATE P4: ORANGE HAPPY ENERGY ===\nBG: WARM ORANGE (#FF9800→#FFE0B2) gradient. NOT blue — happy orange.\nProduct center text-free. Small pet photo inset. OUTER: name bold dark, feature badges white rounded. Fun energetic mood.",
            "=== DESIGN TEMPLATE P5: DARK PREMIUM PET ===\nBG: DARK CHARCOAL (#1C1C1C), warm spotlight. NOT blue — dark charcoal.\nProduct on soft surface, premium lit, text-free. OUTER: name bold white, feature badges warm color. Premium trusted feel.",
        ],
    ];

    $categoryModifier = '';
    if (!empty($category) && isset($variants[$category])) {
        $arr = $variants[$category];
        $categoryModifier = $arr[array_rand($arr)];
    } else {
        $bgs = [
            "BG: DEEP BLACK (#080808), CRIMSON RED (#C62828) radial glow. NOT blue.",
            "BG: WARM AMBER-GOLD gradient (#FF8F00→#FFD740). NOT blue.",
            "BG: DARK CHARCOAL (#1C1C1C) concrete, YELLOW accent lines. NOT blue.",
            "BG: DIAGONAL SPLIT — CRIMSON (#C62828) + GOLDEN (#FFD600). NOT blue.",
            "BG: CLEAN WHITE (#FFFFFF), natural elements scattered. NOT blue.",
            "BG: BOLD ORANGE (#FF6F00→#E65100). NOT blue.",
            "BG: FOREST GREEN (#1B5E20)→white. NOT blue.",
            "BG: VELVET BLACK (#0A0A0A), gold dust. NOT blue.",
        ];
        $categoryModifier = "=== DESIGN TEMPLATE: UNIVERSAL ===\n" . $bgs[array_rand($bgs)] . "\nProduct RIGHT 55% large, text-free. LEFT: name GIANT bold, key spec ENORMOUS 3x (dominant), 3-4 feature badges with icons. Zone rule: ZERO text on product.";
    }

    $salesRules = "\n=== MANDATORY RULES ===\nFORBIDDEN WORDS — remove all: Premium, Original, Hit, TOP, N1, Best Seller, star ratings(★), Kafolat, Garantiya, Chegirma, Aksiya, Bepul, Uzum, Ozon, WB, Wildberries, 'Sotib oling', any call-to-action.\nZERO text on product — 30px minimum gap around product edges.\nOnly features from the provided list — do NOT invent specs.";

    // Category template FIRST → base prompt → rules
    return $categoryModifier . "\n\n" . $prompt . $salesRules;
}
