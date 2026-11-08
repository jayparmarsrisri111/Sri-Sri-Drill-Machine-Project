<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$selectedCategorySlug = $_GET['category'] ?? 'all';

$categoryTitles = [
    'radial-drill-machines' => 'Radial Drill Machines (40mm, 50mm, 65mm Heavy Duty) | Shree Varsha Engineering Works',
    'pillar-drill-machines' => 'Pillar Drill Machines (25mm, 32mm, 40mm Column Drills) | Shree Varsha Engineering Works',
    'bench-drill-machines' => 'High Speed Precision Bench Drill Machines (20mm) | Shree Varsha Engineering Works',
    'tapping-special-purpose' => 'Auto-Reversing Drilling & Tapping Machines | Shree Varsha Engineering Works',
    'spindles-quills' => 'Morse Taper Drill Spindles & Quills | Varsha Brand Bhavnagar',
    'geared-head-gears' => 'Hardened Alloy Headstock Gear Sets | Varsha Brand Drill Parts',
    'cast-iron-tables' => 'Cast Iron T-Slot Machine Box Tables | Shree Varsha Engineering Works',
    'motors-electricals' => 'Flange Induction Motors & Electricals | Varsha Brand Machinery',
    'chucks-sleeves' => 'Keyless Precision Drill Chucks & Sleeves | Industrial Tooling',
    'coolant-lubrication' => 'High Flow Submerged Coolant Pumps & Kits | Varsha Machinery'
];

$categoryDescs = [
    'radial-drill-machines' => 'Explore heavy-duty geared radial drill machines (40mm to 65mm) with hardened alloy gears, power elevation, and zero vibration casting in Bhavnagar, Gujarat.',
    'pillar-drill-machines' => 'Industrial upright column pillar drill machines built with precision ground columns and multi-speed gearboxes for repetitive accurate workshop drilling.',
    'bench-drill-machines' => 'Compact high-speed bench drill machines for precision tool rooms, fabrication shops, and educational institutes. Manufactured in Bhavnagar, Gujarat.',
    'tapping-special-purpose' => 'Dual purpose drilling and tapping machines with instant automatic spindle reverse upon preset depth for clean, accurate thread cutting.'
];

$pageTitle = $categoryTitles[$selectedCategorySlug] ?? 'Industrial Drill Machines & Machinery Catalog | Shree Varsha Engineering Works';
$pageDesc = $categoryDescs[$selectedCategorySlug] ?? 'Explore our complete range of Radial Drill Machines, Pillar Drills, Bench Drills, and Auto-Reversing Tapping Machinery manufactured in Bhavnagar, Gujarat.';
$pageKeywords = 'radial drill machine catalog, pillar drill machine, bench drill, geared radial drill, drill machine manufacturer Bhavnagar, Gujarat machine tools, Shree Varsha Engineering Works';
$currentNav = 'machines';

if ($selectedCategorySlug !== 'all') {
    $canonicalUrl = 'https://varsha-drill-machines.onrender.com/machines.php?category=' . urlencode($selectedCategorySlug);
} else {
    $canonicalUrl = 'https://varsha-drill-machines.onrender.com/machines.php';
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

try {
    $pdo = Database::getConnection();
    
    // Fetch all categories
    $catStmt = $pdo->query("SELECT * FROM categories ORDER BY display_order ASC");
    $categories = $catStmt->fetchAll();

    // Fetch all active products
    $stmt = $pdo->query("
        SELECT p.*, c.name as category_name, c.slug as category_slug 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        WHERE p.is_active = 1 
        ORDER BY p.is_featured DESC, p.id ASC
    ");
    $allProducts = $stmt->fetchAll();
} catch (Exception $e) {
    $allProducts = [];
    $categories = [];
}
?>

<!-- Page Header Banner -->
<section style="padding: 55px 0 35px; background: radial-gradient(circle at 50% 20%, rgba(0, 229, 255, 0.1) 0%, rgba(6, 9, 17, 0.98) 75%); border-bottom: 1px solid var(--border-subtle);">
    <div class="container" style="text-align: center;">
        <div style="display: inline-flex; align-items: center; gap: 12px; margin-bottom: 14px;">
            <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand" style="height: 60px; width: 60px; border-radius: 50%; border: 2px solid var(--accent-cyan); box-shadow: 0 0 20px rgba(0, 229, 255, 0.4);">
        </div>
        <div class="pill-badge">INDUSTRIAL MACHINERY CATALOG</div>
        <h1 class="section-title">Drill Machine <span>Product Range</span></h1>
        <p class="section-desc" style="max-width: 680px; margin: 0 auto;">
            Engineered for high torque, zero-vibration drilling, reaming, and tapping. View complete technical specifications or request a custom quote.
        </p>

        <!-- Live Instant Search Bar -->
        <div style="max-width: 520px; margin: 24px auto 0; position: relative;">
            <input type="text" id="liveMachineSearch" class="form-control" 
                   placeholder="🔍 Type model or capacity (e.g. 40mm, Radial, SVEW-RD-50)..." 
                   style="padding: 14px 20px; font-size: 15px; border-radius: 9999px; background: rgba(15, 23, 42, 0.85); border-color: var(--border-cyan); box-shadow: 0 4px 20px rgba(0, 229, 255, 0.15);">
        </div>
    </div>
</section>

<!-- Main Catalog Section -->
<section class="section">
    <div class="container">
        <!-- Category Filter Tabs -->
        <div class="filter-tabs">
            <button class="filter-btn <?= ($selectedCategorySlug === 'all') ? 'active' : '' ?>" data-filter="all">
                All Machines (<span id="totalMachineCount"><?= count($allProducts) ?></span>)
            </button>
            <?php foreach ($categories as $cat): ?>
                <button class="filter-btn <?= ($selectedCategorySlug === $cat['slug']) ? 'active' : '' ?>" data-filter="<?= e($cat['slug']) ?>">
                    <?= e($cat['name']) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Machines Grid -->
        <div class="machines-grid" id="catalogGrid">
            <?php if (!empty($allProducts)): ?>
                <?php foreach ($allProducts as $p): ?>
                    <div class="machine-card" 
                         data-category="<?= e($p['category_slug']) ?>"
                         data-search-text="<?= strtolower(e($p['name'] . ' ' . $p['model_number'] . ' ' . $p['drilling_capacity'] . ' ' . $p['category_name'])) ?>">
                        
                        <!-- Real Machine Photo Showcase (Clean, Unobstructed Real Photo) -->
                        <div class="machine-card-image" style="position: relative; overflow: hidden; background: #060911; border-bottom: 1px solid rgba(255,255,255,0.08); height: 230px;">
                            <img src="<?= assetUrl($p['image_url']) ?>" alt="<?= e($p['name']) ?>" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; object-position: center; transition: transform 0.4s ease;">
                        </div>

                        <div class="machine-card-body">
                            <!-- Clean Model Badge & Factory Stock Status -->
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                <span class="badge-tag" style="border-color: var(--accent-cyan); color: var(--accent-cyan); font-weight: 700; font-size: 11px;">
                                    <?= e($p['model_number']) ?>
                                </span>
                                <?php $mStock = intval($p['stock_quantity'] ?? 0); ?>
                                <?php if ($mStock > 0): ?>
                                    <span style="font-size: 11.5px; font-weight: 700; color: #34d399; display: flex; align-items: center; gap: 5px;">
                                        <span style="display:inline-block; width:7px; height:7px; border-radius:50%; background:#10b981;"></span>
                                        <?= $mStock ?> In Stock
                                    </span>
                                <?php else: ?>
                                    <span style="font-size: 11px; font-weight: 700; color: #fbbf24; display: flex; align-items: center; gap: 5px;">
                                        <span style="display:inline-block; width:7px; height:7px; border-radius:50%; background:#f59e0b;"></span>
                                        Made to Order
                                    </span>
                                <?php endif; ?>
                            </div>

                            <span class="machine-category" style="font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 600; letter-spacing: 0.5px;"><?= e($p['category_name']) ?></span>
                            <h3 class="machine-title" style="font-size: 17px; margin: 4px 0 10px;"><?= e($p['name']) ?></h3>

                            <!-- Prominent Product Price Badge -->
                            <div style="margin: 8px 0 14px; display: flex; align-items: baseline; justify-content: space-between; background: rgba(11, 17, 32, 0.75); border: 1px solid rgba(0, 229, 255, 0.25); border-radius: 8px; padding: 8px 12px;">
                                <div>
                                    <div style="font-size: 10px; text-transform: uppercase; color: #94a3b8; font-weight: 700;">Factory Ex-Price</div>
                                    <div style="font-family: 'Space Grotesk', sans-serif; font-size: 19px; font-weight: 900; color: #34d399;">
                                        ₹ <?= number_format($p['price'] ?? getMachinePrice($p['id']), 2) ?>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <span style="font-size: 11px; color: #38bdf8; font-weight: 600;">+ 18% GST</span>
                                    <div style="font-size: 10px; color: #94a3b8;">Advance Token: ₹25k</div>
                                </div>
                            </div>

                            <p class="machine-desc"><?= e($p['short_desc']) ?></p>

                            <!-- Specifications Table -->
                            <div class="specs-list">
                                <div class="spec-row">
                                    <span class="spec-label">Drilling Capacity:</span>
                                    <span class="spec-value"><?= e($p['drilling_capacity']) ?></span>
                                </div>
                                <?php if (!empty($p['spindle_speeds'])): ?>
                                <div class="spec-row">
                                    <span class="spec-label">Spindle Speeds:</span>
                                    <span class="spec-value"><?= e($p['spindle_speeds']) ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($p['column_diameter'])): ?>
                                <div class="spec-row">
                                    <span class="spec-label">Column Diameter:</span>
                                    <span class="spec-value"><?= e($p['column_diameter']) ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($p['motor_power'])): ?>
                                <div class="spec-row">
                                    <span class="spec-label">Motor Power:</span>
                                    <span class="spec-value"><?= e($p['motor_power']) ?></span>
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Clean, Uniform, Professional Card Action Buttons -->
                            <div class="machine-card-footer" style="padding-top: 14px; border-top: 1px solid rgba(255,255,255,0.08);">
                                <button type="button" class="btn-360-turntable open-360-modal" 
                                        data-machine-name="<?= e($p['name']) ?> (<?= e($p['model_number']) ?>)" 
                                        data-machine-img="<?= assetUrl($p['image_url']) ?>"
                                        title="View 360° Interactive 12D Turntable">
                                    <span class="turntable-spin-icon">🔄</span>
                                    <span>360° Machine View</span>
                                    <span class="turntable-step-pill">30° STEP</span>
                                </button>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; width: 100%; margin-top: 8px;">
                                    <a href="<?= BASE_URL ?>/machine-detail.php?id=<?= $p['id'] ?>" class="btn btn-outline" style="height: 38px; padding: 0 10px; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                        View Specs
                                    </a>
                                    <a href="<?= BASE_URL ?>/checkout.php?machine_id=<?= $p['id'] ?>" class="btn btn-primary" style="height: 38px; padding: 0 10px; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399; border-radius: 8px;">
                                        💳 Book Online
                                    </a>
                                </div>
                                <button type="button" class="btn btn-cart-quick" onclick="window.addToCart(<?= $p['id'] ?>, 1, this)" style="width: 100%; margin-top: 8px; height: 38px; background: rgba(245, 158, 11, 0.12); border: 1.5px solid #f59e0b; color: #fbbf24; font-size: 12.5px; font-weight: 800; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; transition: all 0.2s ease;">
                                    <span>🛒 Add to Cart (કાર્ટમાં ઉમેરો)</span>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- No Results Fallback -->
        <div id="noResultsMsg" style="display: none; text-align: center; padding: 60px 0;">
            <p style="color: var(--text-muted); font-size: 17px;">No drill machines match your search criteria.</p>
            <button type="button" id="btnResetFilters" class="btn btn-primary" style="margin-top: 14px;">
                Show All Machines
            </button>
        </div>
    </div>
</section>

<!-- Dynamic Live Filtering Script for Catalog -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('liveMachineSearch');
    const filterBtns = document.querySelectorAll('.filter-tabs .filter-btn');
    const cards = document.querySelectorAll('#catalogGrid .machine-card');
    const noResults = document.getElementById('noResultsMsg');
    const resetBtn = document.getElementById('btnResetFilters');

    let currentCategory = '<?= e($selectedCategorySlug) ?>';
    let searchQuery = '';

    function applyFilters() {
        let visibleCount = 0;
        cards.forEach(card => {
            const cat = card.getAttribute('data-category');
            const searchText = card.getAttribute('data-search-text') || '';

            const matchesCategory = (currentCategory === 'all' || cat === currentCategory);
            const matchesSearch = (!searchQuery || searchText.includes(searchQuery));

            if (matchesCategory && matchesSearch) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (noResults) {
            noResults.style.display = (visibleCount === 0) ? 'block' : 'none';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            searchQuery = this.value.toLowerCase().trim();
            applyFilters();
        });
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentCategory = this.getAttribute('data-filter') || 'all';
            applyFilters();
        });
    });

    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            if (searchInput) searchInput.value = '';
            searchQuery = '';
            currentCategory = 'all';
            filterBtns.forEach(b => b.classList.remove('active'));
            document.querySelector('.filter-tabs .filter-btn[data-filter="all"]')?.classList.add('active');
            applyFilters();
        });
    }

    // Run initial filter if loaded with query param
    if (currentCategory !== 'all') {
        applyFilters();
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
