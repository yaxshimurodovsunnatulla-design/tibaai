<?php
require_once __DIR__ . '/api/config.php';
try {
    $db = getDB();
    echo "Database initialized successfully at: " . realpath(__DIR__ . '/data/tibaai.db') . "\n";
    
    // Check prompts
    $prompts = getPrompts();
    if ($prompts) {
        echo "Prompts loaded: " . count($prompts) . " categories.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
