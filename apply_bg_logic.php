<?php
require_once __DIR__ . '/api/config.php';
try {
    $db = getDB();
    $prompts = json_encode(getDefaultPrompts(), JSON_UNESCAPED_UNICODE);
    $stmt = $db->prepare("INSERT OR REPLACE INTO configs (id, data) VALUES ('prompts', ?)");
    $stmt->execute([$prompts]);
    echo "Prompts updated with Background Match logic.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
unlink(__FILE__);
