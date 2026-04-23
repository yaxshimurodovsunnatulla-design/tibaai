<?php
/**
 * Video AI xizmatini bazaga qo'shish
 * 
 * Bu faylni serverga yuklab, brauzerdan 1 marta oching:
 * https://yourdomain.uz/add_video_service.php
 * 
 * Ishlagandan keyin faylni o'chirib tashlang!
 */
require_once __DIR__ . '/api/config.php';

try {
    $db = getDB();
    
    // Tekshirish: allaqachon bormi?
    $stmt = $db->prepare('SELECT id FROM services WHERE slug = ?');
    $stmt->execute(['video-ai']);
    
    if ($stmt->fetch()) {
        echo "<h2 style='color:orange'>⚠️ Video AI xizmati allaqachon bazada mavjud!</h2>";
    } else {
        // Bazaga qo'shish
        $db->prepare('INSERT INTO services (name, slug, icon, description, badge, gradient, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, 1)')
           ->execute([
               'Video AI',
               'video-ai', 
               'fa-solid fa-video',
               'Matn yoki rasmdan professional video yaratish (Tiba AI)',
               'Yangi',
               'from-violet-600 to-fuchsia-600',
               9
           ]);
        
        echo "<h2 style='color:lime'>✅ Video AI xizmati muvaffaqiyatli qo'shildi!</h2>";
    }
    
    // Hozirgi barcha xizmatlarni ko'rsatish
    echo "<h3>Bazadagi barcha xizmatlar:</h3>";
    echo "<table border='1' cellpadding='8' style='border-collapse:collapse; font-family:monospace'>";
    echo "<tr><th>ID</th><th>Nomi</th><th>Slug</th><th>Aktiv</th></tr>";
    
    $services = $db->query("SELECT * FROM services ORDER BY sort_order");
    foreach ($services as $s) {
        $color = $s['is_active'] ? 'green' : 'red';
        echo "<tr>";
        echo "<td>{$s['id']}</td>";
        echo "<td>{$s['name']}</td>";
        echo "<td>{$s['slug']}</td>";
        echo "<td style='color:{$color}'>" . ($s['is_active'] ? 'Ha' : 'Yo\'q') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<br><p style='color:red; font-weight:bold'>⚠️ MUHIM: Bu faylni serverdan O'CHIRIB TASHLANG!</p>";
    
} catch (Exception $e) {
    echo "<h2 style='color:red'>❌ Xato: " . htmlspecialchars($e->getMessage()) . "</h2>";
}
