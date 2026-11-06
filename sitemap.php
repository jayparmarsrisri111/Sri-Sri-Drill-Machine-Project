<?php
/**
 * Dynamic XML Sitemap Generator
 * Shree Varsha Engineering Works - Varsha Brand
 * Website: https://varsha-drill-machines.onrender.com/
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

header('Content-Type: application/xml; charset=utf-8');

$domain = 'https://varsha-drill-machines.onrender.com';
$today = date('Y-m-d');

$products = [];
$categories = [];

try {
    $pdo = Database::getConnection();
    $pStmt = $pdo->query("SELECT id, name, model_number, image_url, updated_at FROM products WHERE is_active = 1 ORDER BY is_featured DESC, id ASC");
    $products = $pStmt->fetchAll();

    $cStmt = $pdo->query("SELECT slug FROM categories ORDER BY display_order ASC");
    $categories = $cStmt->fetchAll();
} catch (Exception $e) {
    //
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
  <!-- Homepage -->
  <url>
    <loc><?= $domain ?>/</loc>
    <lastmod><?= $today ?></lastmod>
    <changefreq>daily</changefreq>
    <priority>1.00</priority>
    <image:image>
      <image:loc><?= $domain ?>/assets/images/machinery/radial-drill-40-real.jpg</image:loc>
      <image:title>Shree Varsha Engineering Works Radial Drill Machines</image:title>
      <image:caption>Heavy Duty Industrial Radial Drill Machine Bhavnagar Gujarat</image:caption>
    </image:image>
    <image:image>
      <image:loc><?= $domain ?>/assets/images/varsha-logo.png</image:loc>
      <image:title>Varsha Brand Emblem</image:title>
    </image:image>
  </url>

  <!-- Machines Catalog (All Models) -->
  <url>
    <loc><?= $domain ?>/machines.php</loc>
    <lastmod><?= $today ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.95</priority>
  </url>

  <!-- Categories -->
  <?php foreach ($categories as $cat): ?>
  <url>
    <loc><?= $domain ?>/machines.php?category=<?= urlencode($cat['slug']) ?></loc>
    <lastmod><?= $today ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.90</priority>
  </url>
  <?php endforeach; ?>

  <!-- Technical Catalog -->
  <url>
    <loc><?= $domain ?>/catalog.php</loc>
    <lastmod><?= $today ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.90</priority>
  </url>

  <!-- Products / Machines -->
  <?php foreach ($products as $p): 
      $pDate = !empty($p['updated_at']) ? substr($p['updated_at'], 0, 10) : $today;
      $imgUrl = !empty($p['image_url']) ? $domain . '/' . ltrim($p['image_url'], '/') : $domain . '/assets/images/machinery/radial-drill-40-real.jpg';
  ?>
  <url>
    <loc><?= $domain ?>/machine-detail.php?id=<?= $p['id'] ?></loc>
    <lastmod><?= $pDate ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.90</priority>
    <image:image>
      <image:loc><?= htmlspecialchars($imgUrl, ENT_XML1, 'UTF-8') ?></image:loc>
      <image:title><?= htmlspecialchars($p['name'] . ' (' . $p['model_number'] . ')', ENT_XML1, 'UTF-8') ?></image:title>
    </image:image>
  </url>
  <?php endforeach; ?>

  <!-- 360° Interactive 12D Simulator -->
  <url>
    <loc><?= $domain ?>/simulator-6d.php</loc>
    <lastmod><?= $today ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.85</priority>
  </url>

  <!-- About Us -->
  <url>
    <loc><?= $domain ?>/about.php</loc>
    <lastmod><?= $today ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.85</priority>
  </url>

  <!-- Contact Us -->
  <url>
    <loc><?= $domain ?>/contact.php</loc>
    <lastmod><?= $today ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.85</priority>
  </url>

  <!-- Engineering Blog -->
  <url>
    <loc><?= $domain ?>/blog.php</loc>
    <lastmod><?= $today ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.80</priority>
  </url>

  <!-- FAQs -->
  <url>
    <loc><?= $domain ?>/faqs.php</loc>
    <lastmod><?= $today ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.80</priority>
  </url>

  <!-- Online Machine Booking -->
  <url>
    <loc><?= $domain ?>/checkout.php</loc>
    <lastmod><?= $today ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.75</priority>
  </url>

  <!-- Live Order Tracking -->
  <url>
    <loc><?= $domain ?>/track-order.php</loc>
    <lastmod><?= $today ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.75</priority>
  </url>
</urlset>
