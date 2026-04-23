<?php
$dbFile = 'd:/887779999/tiba-ai/data/tibaai.db';
$db = new PDO("sqlite:$dbFile");
$stmt = $db->query("PRAGMA table_info(users)");
$cols = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $cols[] = $row['name'];
}
echo "Users columns: " . implode(', ', $cols) . "\n";

$stmt = $db->query("PRAGMA table_info(payments)");
$cols = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $cols[] = $row['name'];
}
echo "Payments columns: " . implode(', ', $cols) . "\n";
