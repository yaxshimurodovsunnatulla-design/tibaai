<?php
/**
 * Tiba AI — Public namunalar API
 * GET ?type=carousel — carousel rasmlar
 * GET ?type=before-after — oldin/keyin rasmlar
 * GET (hech narsasiz) — barcha aktiv namunalar
 */
require_once __DIR__ . '/config.php';

$db = getDB();
$type = $_GET['type'] ?? '';

if ($type) {
    $stmt = $db->prepare("SELECT id, title, image_path, before_image_path, after_image_path, type FROM showcase_samples WHERE is_active = 1 AND type = ? ORDER BY sort_order ASC, id DESC");
    $stmt->execute([$type]);
} else {
    $stmt = $db->query("SELECT id, title, image_path, before_image_path, after_image_path, type FROM showcase_samples WHERE is_active = 1 ORDER BY sort_order ASC, id DESC");
}

$samples = $stmt->fetchAll();
jsonResponse(['samples' => $samples]);
