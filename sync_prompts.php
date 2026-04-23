<?php
/**
 * Bazadagi promptlarni yangi ultra-sifatli versiyaga yangilash
 * Bir marta ishlatiladi: php sync_prompts.php
 */
require_once __DIR__ . '/api/config.php';

echo "=== Promptlarni yangilash ===\n";

$db = getDB();
$prompts = json_encode(getDefaultPrompts(), JSON_UNESCAPED_UNICODE);
$stmt = $db->prepare("INSERT OR REPLACE INTO configs (id, data, updated_at) VALUES ('prompts', ?, datetime('now'))");
$stmt->execute([$prompts]);

echo "✅ Promptlar bazada muvaffaqiyatli yangilandi!\n";

// Tekshirish
$stmt = $db->prepare("SELECT data FROM configs WHERE id = 'prompts'");
$stmt->execute();
$row = $stmt->fetch();
$saved = json_decode($row['data'], true);

echo "\n📋 Yangilangan promptlar:\n";
echo "  - Infografika stillar: " . count($saved['infografika'] ?? []) . " ta\n";
echo "  - Foto tahrir stillar: " . count($saved['foto-tahrir'] ?? []) . " ta\n";
echo "  - Paket slidlar: " . count($saved['paket']['marketplace'] ?? []) . " ta\n";

// Marketplace promptni test qilish
$mp = $saved['infografika']['marketplace'] ?? '';
echo "\n🔍 Marketplace prompt boshlanishi: " . substr($mp, 0, 100) . "...\n";
echo "   8K mavjudmi: " . (strpos($mp, '8K') !== false ? '✅ Ha' : '❌ Yo\'q') . "\n";
