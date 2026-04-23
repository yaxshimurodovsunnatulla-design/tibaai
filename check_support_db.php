<?php
require_once __DIR__ . '/api/config.php';
$db = getDB();
$rows = $db->query("SELECT * FROM support_sessions")->fetchAll();
echo "Support Sessions Count: " . count($rows) . "\n";
print_r($rows);

$messages = $db->query("SELECT * FROM support_messages")->fetchAll();
echo "Support Messages Count: " . count($messages) . "\n";
print_r($messages);
