<?php
require __DIR__ . '/api/config.php';
$db = getDB();
$stmt = $db->prepare("UPDATE services SET description = ? WHERE slug = ?");
$stmt->execute(['Matn yoki rasmdan professional video yaratish (Tiba AI)', 'video-ai']);
echo "Updated rows: " . $stmt->rowCount() . "\n";
$row = $db->query("SELECT name, description FROM services WHERE slug='video-ai'")->fetch();
echo $row['name'] . ': ' . $row['description'] . "\n";
