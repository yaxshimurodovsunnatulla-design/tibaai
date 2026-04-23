<?php
/**
 * Tiba AI — Kartochka AI API
 * Mahsulot rasmidan kartochka ma'lumotlarini chiqarish (O'zbekcha + Ruscha)
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'Faqat POST so\'rovlar qabul qilinadi'], 405);
}
checkRateLimit(10);

// Tanga tekshirish
$balanceInfo = requireBalance('kartochka-ai');

$input = getInput();

$productImage = $input['productImage'] ?? null;
$marketplace = sanitize($input['marketplace'] ?? 'uzum');

validateImageSize($productImage);

if (empty($productImage)) {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Mahsulot rasmini yuklang'], 400);
}

// Marketplace context
$mpDescs = [
    'uzum' => 'Uzum Market (Uzbekistan\'s largest marketplace, similar to Wildberries). Product cards should follow Uzum format: concise titles with key specs, detailed descriptions with features and benefits, specifications as key-value pairs.',
    'wildberries' => 'Wildberries (Russia\'s largest marketplace). Product cards should follow WB format: SEO-optimized titles with all relevant keywords, detailed marketing descriptions, characteristics in table format.',
    'universal' => 'Universal e-commerce marketplace. Product cards should follow standard marketplace format with SEO-friendly content.',
];
$mpDesc = $mpDescs[$marketplace] ?? $mpDescs['universal'];

// Build the prompt — request BOTH languages
$prompt = "PRODUCT CARD GENERATOR — MARKETPLACE LISTING EXPERT (BILINGUAL)\n\n";
$prompt .= "You are a professional marketplace product listing specialist. Analyze the provided product image carefully and generate ALL necessary information for a complete product card.\n\n";
$prompt .= "TARGET MARKETPLACE: {$mpDesc}\n\n";
$prompt .= "⚠️ IMPORTANT: Generate the SAME product card data in TWO languages — Uzbek (uz) and Russian (ru).\n\n";
$prompt .= "Analyze the product image and return a JSON object with the following structure:\n\n";
$prompt .= "{\n";
$prompt .= "  \"uz\": {\n";
$prompt .= "    \"title\": \"SEO-optimized product title in UZBEK (60-120 chars). Include product type, key material/feature, brand if visible\",\n";
$prompt .= "    \"shortDescription\": \"Short description in UZBEK (150-200 chars)\",\n";
$prompt .= "    \"fullDescription\": \"Extremely detailed and rich description in UZBEK (1000-2000 chars). Use emotional and descriptive language. Include: 1) Captivating introduction, 2) Key advantages and technical highlights, 3) Material quality and durability, 4) Detailed use cases and lifestyle benefits, 5) Emotional conclusion. High sales-oriented tone with rich vocabulary.\",\n";
$prompt .= "    \"features\": [\"Feature 1\", \"Feature 2\", ...] (5-8 key features in UZBEK),\n";
$prompt .= "    \"specifications\": {\"Key\": \"Value\", ...} (specs in UZBEK, 6-10 items),\n";
$prompt .= "    \"category\": \"Category path in UZBEK\",\n";
$prompt .= "    \"brand\": \"Brand name\",\n";
$prompt .= "    \"targetAudience\": \"Target audience in UZBEK\",\n";
$prompt .= "    \"seoKeywords\": [\"keyword1\", ...] (10-15 keywords in UZBEK),\n";
$prompt .= "    \"searchTags\": [\"tag1\", ...] (8-12 tags in UZBEK),\n";
$prompt .= "    \"priceRange\": \"Price range in UZS\",\n";
$prompt .= "    \"sellingPoints\": [\"Point 1\", ...] (3-5 selling points in UZBEK)\n";
$prompt .= "  },\n";
$prompt .= "  \"ru\": {\n";
$prompt .= "    \"title\": \"SEO-optimized product title in RUSSIAN (60-120 chars)\",\n";
$prompt .= "    \"shortDescription\": \"Short description in RUSSIAN (150-200 chars)\",\n";
$prompt .= "    \"fullDescription\": \"Extremely detailed and rich description in RUSSIAN (1000-2000 chars). Structure the same as Uzbek version: intro, tech highlights, quality, use cases, conclusion.\",\n";
$prompt .= "    \"features\": [\"Feature 1\", \"Feature 2\", ...] (5-8 key features in RUSSIAN),\n";
$prompt .= "    \"specifications\": {\"Key\": \"Value\", ...} (specs in RUSSIAN, 6-10 items),\n";
$prompt .= "    \"category\": \"Category path in RUSSIAN\",\n";
$prompt .= "    \"brand\": \"Brand name\",\n";
$prompt .= "    \"targetAudience\": \"Target audience in RUSSIAN\",\n";
$prompt .= "    \"seoKeywords\": [\"keyword1\", ...] (10-15 keywords in RUSSIAN),\n";
$prompt .= "    \"searchTags\": [\"tag1\", ...] (8-12 tags in RUSSIAN),\n";
$prompt .= "    \"priceRange\": \"Price range in UZS\",\n";
$prompt .= "    \"sellingPoints\": [\"Point 1\", ...] (3-5 selling points in RUSSIAN)\n";
$prompt .= "  }\n";
$prompt .= "}\n\n";
$prompt .= "IMPORTANT RULES:\n";
$prompt .= "1. Analyze the IMAGE thoroughly — look at labels, text, brand marks, packaging, material texture, size clues.\n";
$prompt .= "2. Uzbek text must be in proper O'zbek tili (latin script, not cyrillic).\n";
$prompt .= "3. Russian text must be in proper Русский язык.\n";
$prompt .= "4. Be SPECIFIC, not generic. Tailor to the exact product you see.\n";
$prompt .= "5. Both languages must contain the SAME information, just translated.\n";
$prompt .= "6. SEO keywords should include common search terms buyers would use.\n";
$prompt .= "7. Return ONLY valid JSON. No markdown, no code blocks, no extra text.\n";

// Build parts
$parts = [];
$processedImage = processImageInput($productImage);

if ($processedImage) {
    $parts[] = ['inline_data' => $processedImage];
} else {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'Mahsulot rasmi yuklanmagan yoki havolasi noto\'g\'ri'], 400);
}

$parts[] = ['text' => $prompt];

// Call Gemini Text API
$textResult = callGeminiTextAPI($parts);

if (empty($textResult)) {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'AI javob bermadi. Qayta urinib ko\'ring.'], 500);
}

// Parse JSON response
$parsed = json_decode($textResult, true);

if (!$parsed || !is_array($parsed)) {
    if (preg_match('/\{[\s\S]+\}/', $textResult, $jsonMatch)) {
        $parsed = json_decode($jsonMatch[0], true);
    }
}

if (!$parsed || !is_array($parsed)) {
    refundBalance($balanceInfo['user']['id'], $balanceInfo['cost']);
    jsonResponse(['error' => 'AI javobini tahlil qilib bo\'lmadi. Qayta urinib ko\'ring.', 'raw' => $textResult], 500);
}

// Ensure both languages exist
if (!isset($parsed['uz']) && !isset($parsed['ru'])) {
    // Maybe the response is a single-lang object, wrap it
    $parsed = ['uz' => $parsed, 'ru' => $parsed];
}

// Save input image for history/telegram
$imageUrl = '';
if ($processedImage) {
    $imageUrl = saveImage($processedImage['data'], $processedImage['mime_type'], 'kartochka-input');
}

// Telegram
$telegramMsgId = null;
$productTitle = $parsed['uz']['title'] ?? ($parsed['ru']['title'] ?? 'Mahsulot');
$msg = "🧠 *Kartochka AI*\n\n";
$msg .= "📦 *Mahsulot:* " . sanitize(mb_substr($productTitle, 0, 50)) . "...\n";
$msg .= "✅ *Status:* Tahlil qilindi\n";
$msg .= "\n🤖 _Tiba AI_";

try {
    if ($imageUrl) {
        $tgResponseRaw = sendToTelegram($msg, $imageUrl, true);
        $tgResponse = json_decode($tgResponseRaw, true);
        if (isset($tgResponse['ok']) && $tgResponse['ok'] && isset($tgResponse['result']['message_id'])) {
            $telegramMsgId = $tgResponse['result']['message_id'];
        }
    }
} catch (Exception $e) { error_log($e->getMessage()); }

// History
$user = getAuthUser();
if ($user && $imageUrl) {
    try {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO generations (user_id, image_path, prompt_data, telegram_msg_id) VALUES (?, ?, ?, ?)");
        $promptData = json_encode([
            'product' => $productTitle,
            'marketplace' => $marketplace,
            'result' => $parsed, // Save the full text content
            'type' => 'kartochka-ai'
        ], JSON_UNESCAPED_UNICODE);
        $stmt->execute([$user['id'], $imageUrl, $promptData, $telegramMsgId]);
    } catch (Exception $e) { error_log($e->getMessage()); }
}

// Tangalarni ayirish
$newBalance = deductBalance($balanceInfo['user']['id'], $balanceInfo['cost'], 'kartochka-ai');

jsonResponse(['success' => true, 'result' => $parsed, 'balance' => $newBalance]);
