<?php
require_once __DIR__ . '/config/settings.php';
require_once __DIR__ . '/config/database.php';

header('Content-Type: application/xml; charset=utf-8');

$database = new Database();
$db = $database->getConnection();

// Base URL (ensure it doesn't end with slash)
$base_url = rtrim(SITE_URL, '/');

// Static pages
$pages = [
    '',
    'services',
    'pricing',
    'about',
    'contact',
    'booking'
];

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach ($pages as $page): ?>
    <url>
        <loc><?php echo $base_url . '/' . $page; ?></loc>
        <changefreq>weekly</changefreq>
        <priority><?php echo ($page === '') ? '1.0' : '0.8'; ?></priority>
    </url>
    <?php endforeach; ?>

    <?php
    // Dynamic Services
    try {
        $query = "SELECT id, updated_at FROM services WHERE active = 1";
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $updated = $row['updated_at'] ? date('c', strtotime($row['updated_at'])) : date('c');
    ?>
    <url>
        <loc><?php echo $base_url . '/booking?service=' . $row['id']; ?></loc>
        <lastmod><?php echo $updated; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <?php
        }
    } catch (PDOException $e) {
        // Silently fail or log error
    }
    ?>
</urlset>
