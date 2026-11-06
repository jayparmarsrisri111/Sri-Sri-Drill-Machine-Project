<?php
$pageTitle = 'Radial Drill Machine Manufacturer in Bhavnagar, Gujarat | Shree Varsha Engineering Works';
$pageDesc = 'Shree Varsha Engineering Works (Varsha Brand) is Gujarat\'s premier manufacturer of Heavy-Duty Radial Drill Machines (40mm, 50mm, 65mm), Pillar Drills & Bench Drills in Bhavnagar. 25+ Years Experience. Call +91 9824913555.';
$pageKeywords = 'radial drill machine manufacturer in Bhavnagar, radial drill machine Gujarat, 40mm radial drill machine, 50mm radial drill, 65mm geared radial drill machine, pillar drill machine, bench drill, industrial drilling machinery India, Shree Varsha Engineering Works, Varsha Brand drill machine';
$canonicalUrl = 'https://varsha-drill-machines.onrender.com/';
$currentNav = 'home';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Fetch categories and featured products from database
$categories = [];
$products = [];

try {
    $pdo = Database::getConnection();
    
    // Fetch categories
    $catStmt = $pdo->query("SELECT * FROM categories ORDER BY display_order ASC");
    $categories = $catStmt->fetchAll();

    // Fetch active products
    $prodStmt = $pdo->query("
        SELECT p.*, c.name as category_name, c.slug as category_slug 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        WHERE p.is_active = 1 
        ORDER BY p.is_featured DESC, p.id ASC
    ");
    $products = $prodStmt->fetchAll();
} catch (Exception $e) {
    // Graceful fallback if database issue
}
?>

<!-- ==========================================================================
     HERO SECTION
     ========================================================================== -->
<section class="hero-section">
    <div class="container hero-grid">
        <!-- Hero Text Content -->
        <div class="hero-content">
            <div class="pill-badge">
                ISO 9001:2015 CERTIFIED • BHAVNAGAR, GUJARAT
            </div>
            <h1 class="hero-title">
                Industrial Heavy-Duty
                <span class="gradient-text">Radial & Pillar Drill Machines</span>
                Engineered for Absolute Precision
            </h1>
            <p class="hero-desc">
                Welcome to <strong><?= APP_NAME ?> (<?= BRAND_NAME ?>)</strong>. For over 25 years, we have designed and manufactured rugged, high-torque industrial drilling machinery. Featuring hardened & ground alloy steel gears, heavy ribbed cast iron bases, and vibration-free drilling accuracy from 20mm to 65mm.
            </p>
            
            <div class="hero-buttons">
                <a href="<?= BASE_URL ?>/catalog.php" class="btn btn-primary" style="background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color: #060911; font-weight: 800; border-color: #00e5ff;">
                    📖 2026 Machine Catalog
                </a>
                <a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399;">
                    💳 Book Online (Razorpay)
                </a>
                <a href="<?= BASE_URL ?>/simulator-6d.php" class="btn btn-outline" style="border-color: rgba(0, 229, 255, 0.4); display: flex; align-items: center; gap: 5px;">
                    <span style="font-size: 11px; background: rgba(0, 229, 255, 0.2); border: 1px solid #00e5ff; padding: 1px 6px; border-radius: 9999px; font-weight: 800;">360°</span>
                    <span>12D View &amp; AI</span>
                </a>
                <a href="<?= getWhatsAppLink(COMPANY_WHATSAPP, 'Hello Shree Varsha Engineering Works, I would like to inquire about your Radial Drill Machines.') ?>" target="_blank" class="btn btn-whatsapp">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/></svg>
                    WhatsApp Chat
                </a>
            </div>

            <!-- Stats Ticker -->
            <div class="hero-stats">
                <div class="stat-item">
                    <h3>25<span>+</span></h3>
                    <p>Years of Engineering</p>
                </div>
                <div class="stat-item">
                    <h3>1800<span>+</span></h3>
                    <p>Machines Installed</p>
                </div>
                <div class="stat-item">
                    <h3>100<span>%</span></h3>
                    <p>Quality Tested</p>
                </div>
            </div>
        </div>

        <!-- Hero Machine Visual: 12D 360-Degree Interactive Twin & Real Photo Showcase -->
        <div class="hero-visual" id="heroVisualWrapper">
            <div class="hero-visual-glow"></div>
            
            <div class="hero-360-container" style="position: relative; z-index: 2; width: 100%; max-width: 580px; background: rgba(15, 23, 42, 0.9); border: 1.5px solid rgba(0, 229, 255, 0.4); border-radius: 16px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.8), 0 0 35px rgba(0,229,255,0.18); backdrop-filter: blur(14px);">
                
                <!-- Top Toolbar with 360 Controls & View Mode -->
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 18px; background: rgba(6, 9, 17, 0.95); border-bottom: 1px solid rgba(0, 229, 255, 0.25);">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="display: inline-block; width: 9px; height: 9px; border-radius: 50%; background: #00e5ff; box-shadow: 0 0 10px #00e5ff;"></span>
                        <span style="font-family: 'Space Grotesk', sans-serif; font-size: 12px; font-weight: 800; color: #fff; letter-spacing: 0.8px;">
                            12D DIGITAL TWIN & 360° TURNTABLE
                        </span>
                    </div>
                    
                    <div style="display: flex; gap: 6px;">
                        <button type="button" id="btnHeroView3D" class="hero-toggle-btn active" title="3D Interactive 360 Degree Model">
                            ↺ 360° 3D
                        </button>
                        <button type="button" id="btnHeroViewPhoto" class="hero-toggle-btn" title="Real Industrial Machine Photo">
                            📷 Real Photo
                        </button>
                    </div>
                </div>

                <!-- 3D Canvas Viewport -->
                <div id="hero3DViewport" style="width: 100%; height: 380px; position: relative; cursor: grab;">
                    <!-- Interactive 3D Canvas -->
                    <div id="heroCanvasContainer" style="width: 100%; height: 100%;"></div>

                    <!-- Compass Overlay -->
                    <div style="position: absolute; top: 12px; left: 14px; pointer-events: none; background: rgba(6,9,17,0.8); border: 1px solid rgba(0,229,255,0.35); border-radius: 20px; padding: 4px 12px; font-size: 11px; color: #00e5ff; display: flex; align-items: center; gap: 6px;">
                        <span>↺ Touch / Drag to Rotate 360°</span>
                    </div>

                    <!-- Live Telemetry Badges -->
                    <div style="position: absolute; bottom: 12px; left: 14px; pointer-events: none; display: flex; gap: 8px;">
                        <span class="badge-tag" style="background: rgba(6,9,17,0.85); border-color: rgba(0,229,255,0.4); font-size: 10.5px; color: #fff;">
                            RPM: <strong style="color: #00e5ff;" id="heroRpmDisplay">650</strong>
                        </span>
                        <span class="badge-tag" style="background: rgba(6,9,17,0.85); border-color: #10b981; font-size: 10.5px; color: #fff;">
                            Torque: <strong style="color: #34d399;" id="heroTorqueDisplay">142 Nm</strong>
                        </span>
                    </div>
                </div>

                <!-- Real Photo Viewport (Togglable) -->
                <div id="heroPhotoViewport" style="display: none; width: 100%; height: 380px; position: relative; background: #060911; align-items: center; justify-content: center; padding: 20px;">
                    <img src="<?= assetUrl('assets/images/machinery/radial-drill-real.jpg') ?>" alt="Real Radial Drill Machine" style="max-height: 330px; max-width: 90%; object-fit: contain; filter: drop-shadow(0 15px 30px rgba(0,0,0,0.8));">
                    <div style="position: absolute; bottom: 12px; right: 14px; background: rgba(0,0,0,0.85); border: 1px solid #10b981; border-radius: 6px; padding: 5px 12px; font-size: 11.5px; color: #34d399; font-weight: 600;">
                        ✔ Real Factory Machine: SVEW-RD-40 (Varsha Brand)
                    </div>
                </div>

                <!-- 12D Kinetic Control Bar -->
                <div style="padding: 12px 16px; background: rgba(11, 17, 32, 0.95); border-top: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
                    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                        <button type="button" id="btnHeroAutoSpin" class="hero-tool-btn active">
                            ↺ Auto-Spin 360°
                        </button>
                        <button type="button" id="btnHeroCutDrill" class="hero-tool-btn" style="border-color: #f59e0b; color: #fbbf24;">
                            ⚡ 12D Cutting Sparks
                        </button>
                        <button type="button" id="btnHeroAudio" class="hero-tool-btn" style="border-color: #a855f7; color: #c084fc;">
                            🔊 Sound Drone
                        </button>
                    </div>
                    <div>
                        <a href="<?= BASE_URL ?>/simulator-6d.php" style="font-size: 12px; color: var(--accent-cyan); font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                            Full 360° View Lab →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     TECH TICKER BAR
     ========================================================================== -->
<div class="tech-ticker-bar">
    <div class="container">
        <div class="tech-ticker-list">
            <div class="tech-ticker-item">
                <svg viewBox="0 0 24 24"><path d="M12 2L1 21h22L12 2zm0 3.99L19.53 19H4.47L12 5.99zM11 16h2v2h-2zm0-6h2v4h-2z"/></svg>
                <span>Hardened & Ground Alloy Gears</span>
            </div>
            <div class="tech-ticker-item">
                <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                <span>Grade-25 High Tensile Heavy Casting</span>
            </div>
            <div class="tech-ticker-item">
                <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
                <span>ISO 9001:2015 Precision Certified</span>
            </div>
            <div class="tech-ticker-item">
                <svg viewBox="0 0 24 24"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96z"/></svg>
                <span>Pan-India Factory Dispatch & Support</span>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================================================
     OFFICIAL 2026 DRILL MACHINE CATALOG & SPECIFICATIONS BROCHURE
     ========================================================================== -->
<div class="container" style="margin-top: 40px; margin-bottom: 25px;">
    <div class="catalog-cta-card" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(11, 17, 32, 0.9) 100%); border: 1.5px solid var(--border-cyan); border-radius: 16px; padding: 26px 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.6), 0 0 25px rgba(0,229,255,0.1);">
        <div class="catalog-cta-info" style="display: flex; align-items: center; gap: 18px;">
            <div class="catalog-cta-icon" style="width: 58px; height: 58px; background: rgba(0, 229, 255, 0.1); border: 2px solid var(--accent-cyan); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--accent-cyan); flex-shrink: 0; box-shadow: 0 0 15px rgba(0, 229, 255, 0.3);">
                <svg viewBox="0 0 24 24" width="30" height="30" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
            </div>
            <div>
                <span class="pill-badge" style="background: rgba(0, 229, 255, 0.15); border-color: var(--accent-cyan); color: var(--accent-cyan); margin-bottom: 4px;">
                    ★ OFFICIAL 2026 DRILL MACHINE CATALOG
                </span>
                <h3 style="font-family: 'Space Grotesk', sans-serif; font-size: 21px; font-weight: 800; color: #fff; margin: 4px 0;">
                    Download Complete Drill Machine Catalog &amp; Technical Specifications
                </h3>
                <p style="font-size: 13.5px; color: var(--text-secondary); max-width: 620px; margin: 0;">
                    Get full engineering dimensions, gear ratios, spindle speeds, motor ratings, and factory price list for all 8 <strong>VARSHA BRAND</strong> drill machines in high-resolution printable format.
                </p>
            </div>
        </div>

        <div class="catalog-cta-actions" style="display: flex; gap: 12px; flex-wrap: wrap;">
            <a href="<?= BASE_URL ?>/catalog.php" class="btn btn-primary" style="background: linear-gradient(135deg, #00f0ff 0%, #2563eb 100%); color: #060911; font-weight: 800; padding: 11px 18px; font-size: 13.5px;">
                📖 View &amp; Print Catalog (PDF)
            </a>
            <a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399; padding: 11px 16px; font-size: 13.5px;">
                💳 Book Machine (Online)
            </a>
            <a href="<?= BASE_URL ?>/invoice.php" class="btn btn-outline" style="padding: 11px 16px; font-size: 13px; color: #facc15; border-color: rgba(250, 204, 21, 0.4);">
                📄 View My Bill
            </a>
        </div>
    </div>
</div>

<!-- ==========================================================================
     MACHINE CATALOG & PRODUCTS SHOWCASE
     ========================================================================== -->
<section class="section" id="machines-catalog">
    <div class="container">
        <div class="section-title-wrap">
            <div class="pill-badge">DRILL MACHINE PORTFOLIO</div>
            <h2 class="section-title">Industrial Grade <span>Drilling Machinery</span></h2>
            <p class="section-desc">
                From high-speed precision bench drills to heavy-duty 65mm all-geared radial drill machines, explore our full product range engineered for fabrication, die casting, tool rooms, and automotive workshops.
            </p>
        </div>

        <!-- Category Filter Tabs -->
        <div class="filter-tabs">
            <button class="filter-btn active" data-filter="all">All Drill Machines</button>
            <?php foreach ($categories as $cat): ?>
                <button class="filter-btn" data-filter="<?= e($cat['slug']) ?>">
                    <?= e($cat['name']) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Machines Grid -->
        <div class="machines-grid">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $p): ?>
                    <div class="machine-card" data-category="<?= e($p['category_slug']) ?>">
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
                                <?php $iStock = intval($p['stock_quantity'] ?? 0); ?>
                                <?php if ($iStock > 0): ?>
                                    <span style="font-size: 11.5px; font-weight: 700; color: #34d399; display: flex; align-items: center; gap: 5px;">
                                        <span style="display:inline-block; width:7px; height:7px; border-radius:50%; background:#10b981;"></span>
                                        <?= $iStock ?> In Stock
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

                            <!-- Key Specifications Table -->
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
                                <?php if (!empty($p['spindle_travel'])): ?>
                                <div class="spec-row">
                                    <span class="spec-label">Spindle Travel:</span>
                                    <span class="spec-value"><?= e($p['spindle_travel']) ?></span>
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
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; grid-column: 1 / -1; color: var(--text-muted);">
                    No drill machines found in catalog. Please check database configuration.
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ==========================================================================
     INNOVATION: 6D DIGITAL TWIN & AI SMARTDRILL SHOWCASE BANNER
     ========================================================================== -->
<section class="section" style="background: linear-gradient(180deg, rgba(6, 9, 17, 0.9) 0%, rgba(15, 23, 42, 0.7) 100%); border-top: 1px solid var(--border-cyan); border-bottom: 1px solid var(--border-cyan);">
    <div class="container">
        <div class="responsive-two-col" style="display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 40px; align-items: center;">
            <div>
                <div class="pill-badge" style="border-color: var(--accent-amber); color: #fbbf24;">
                    ★ WORLD-FIRST DRILL MACHINE INNOVATION
                </div>
                <h2 class="section-title">
                    Interactive <span>360° Virtual Machine Turntable</span> &amp; AI Parameter Calculator
                </h2>
                <p class="section-desc" style="margin-bottom: 24px;">
                    Experience our drill machines like never before in full 360° interactive rotation with 12D visual fidelity. Rotate 360°, inspect the hardened alloy gear train in X-Ray mode, and let our metallurgical AI calculate your exact RPM, Feed, Torque, and Cycle Time before you machine a single part.
                </p>

                <div style="display: flex; gap: 14px; flex-wrap: wrap;">
                    <a href="<?= BASE_URL ?>/simulator-6d.php" class="btn btn-primary" style="background: linear-gradient(135deg, #00f0ff 0%, #3b82f6 100%);">
                        ⚡ Launch 360° Interactive 12D View &amp; AI
                    </a>
                    <button type="button" class="btn btn-outline open-quote-modal" data-machine-name="AI Drill Custom Requirement">
                        Request Factory Quote
                    </button>
                </div>
            </div>

            <div style="background: #060911; border: 1px solid var(--border-cyan); border-radius: 14px; padding: 24px; box-shadow: var(--shadow-glow);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                    <span style="font-family: var(--font-heading); font-size: 13px; color: var(--accent-cyan); font-weight: 700;">
                        ● SVEW-RD-50 360° TURNTABLE TELEMETRY
                    </span>
                    <span class="badge-tag">360° WEBGL</span>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                    <div style="background: rgba(11, 17, 32, 0.8); padding: 12px; border-radius: 6px; border: 1px solid var(--border-subtle);">
                        <div style="font-size: 10px; color: var(--text-muted); text-transform: uppercase;">Spindle RPM</div>
                        <div style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; color: #fff;">650 <span style="font-size: 11px; color: var(--accent-cyan);">RPM</span></div>
                    </div>
                    <div style="background: rgba(11, 17, 32, 0.8); padding: 12px; border-radius: 6px; border: 1px solid var(--border-subtle);">
                        <div style="font-size: 10px; color: var(--text-muted); text-transform: uppercase;">Spindle Torque</div>
                        <div style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; color: #34d399;">38.5 <span style="font-size: 11px; color: var(--accent-cyan);">Nm</span></div>
                    </div>
                </div>
                <div style="font-size: 12.5px; color: #cbd5e1; line-height: 1.5; margin-bottom: 14px;">
                    ✔ 360° Precision Stepping • X-Ray Alloy Gears • Real-Time Audio Synthesis • Dynamic Incandescent Cutting Sparks
                </div>
                <a href="<?= BASE_URL ?>/simulator-6d.php" style="font-size: 13px; color: var(--accent-cyan); font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                    Open Fullscreen 360° Viewer →
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     ENGINEERING SUPERIORITY / WHY SHREE VARSHA
     ========================================================================== -->
<section class="section" style="background: rgba(11, 17, 32, 0.4);">
    <div class="container">
        <div class="section-title-wrap">
            <div class="pill-badge">CORE ENGINEERING ADVANTAGES</div>
            <h2 class="section-title">Built with <span>Uncompromising Rigidity</span></h2>
            <p class="section-desc">
                Every drill machine manufactured by Shree Varsha Engineering Works is subjected to rigorous dimensional, vibration, and thermal testing before dispatch.
            </p>
        </div>

        <div class="features-grid">
            <div class="feature-box" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                <div class="feature-icon" style="color: #00e5ff; margin: 0 auto 20px auto;">
                    <!-- Authentic Mechanical Industrial Gear -->
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"/>
                    </svg>
                </div>
                <h3 class="feature-title">Hardened &amp; Ground 20MnCr5 Gears</h3>
                <p class="feature-desc">
                    Made from high grade nickel-chrome alloy steel, case hardened to 58-62 HRC and tooth-ground on German gear grinders for whisper-quiet, zero-backlash power transmission.
                </p>
            </div>

            <div class="feature-box" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                <div class="feature-icon" style="color: #38bdf8; margin: 0 auto 20px auto;">
                    <!-- Heavy Industrial Base & Anvil Casting -->
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 18h20v4H2zM4 18V8a2 2 0 012-2h12a2 2 0 012 2v10M7 6V4a1 1 0 011-1h8a1 1 0 011 1v2"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                </div>
                <h3 class="feature-title">Grade-25 Heavy Castings</h3>
                <p class="feature-desc">
                    Columns, arms, heads, and base plates are cast using seasoned Grade-25 close-grain cast iron with heavy internal ribbing to absorb peak drilling vibrations and torsional deflection.
                </p>
            </div>

            <div class="feature-box" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                <div class="feature-icon" style="color: #34d399; margin: 0 auto 20px auto;">
                    <!-- Precision Micrometer / Spindle Taper Tool -->
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15 8 12 14 9 8 12 2"/>
                        <line x1="12" y1="14" x2="12" y2="22"/>
                        <circle cx="12" cy="18" r="3"/>
                    </svg>
                </div>
                <h3 class="feature-title">Micro-Tolerance Spindles (< 0.01mm)</h3>
                <p class="feature-desc">
                    Spindles are forged from EN-series alloy steel, induction hardened, and supported by heavy-duty precision taper roller and thrust bearings for high radial and axial thrust loads.
                </p>
            </div>

            <div class="feature-box" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                <div class="feature-icon" style="color: #fbbf24; margin: 0 auto 20px auto;">
                    <!-- Centralized Pressure Oil Bath Lubrication -->
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2.69l5.66 5.66a8 8 0 11-11.31 0z"/>
                        <path d="M12 12a3 3 0 00-3 3"/>
                    </svg>
                </div>
                <h3 class="feature-title">Centralized Pressure Oil Bath</h3>
                <p class="feature-desc">
                    Continuous splash and forced pump lubrication systems ensure all gear assemblies and bearings are constantly flushed with cool filtered lubricant, extending machine lifespan by decades.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     TECHNICAL SPECIFICATIONS COMPARISON TABLE
     ========================================================================== -->
<section class="section" id="specifications">
    <div class="container">
        <div class="section-title-wrap">
            <div class="pill-badge">QUICK COMPARISON MATRIX</div>
            <h2 class="section-title">Technical <span>Specifications Table</span></h2>
            <p class="section-desc">
                Compare models by drilling capacity, spindle travel, column diameter, and motor specifications to pick the ideal match for your factory.
            </p>
        </div>

        <div class="table-responsive">
            <table class="tech-table">
                <thead>
                    <tr>
                        <th>Model</th>
                        <th>Machine Type</th>
                        <th>Capacity (Steel/C.I.)</th>
                        <th>Spindle Speeds</th>
                        <th>Spindle Travel</th>
                        <th>Column Dia</th>
                        <th>Motor Power</th>
                        <th>Weight</th>
                        <th>Quote</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td><strong style="color: var(--accent-cyan);"><?= e($p['model_number']) ?></strong></td>
                                <td><?= e($p['category_name']) ?></td>
                                <td><strong><?= e($p['drilling_capacity']) ?></strong></td>
                                <td><?= e($p['spindle_speeds']) ?></td>
                                <td><?= e($p['spindle_travel']) ?></td>
                                <td><?= e($p['column_diameter']) ?></td>
                                <td><?= e($p['motor_power']) ?></td>
                                <td><?= e($p['weight_approx']) ?></td>
                                <td>
                                    <button type="button" class="btn btn-primary open-quote-modal" 
                                            style="padding: 6px 12px; font-size: 11px;"
                                            data-machine-id="<?= $p['id'] ?>"
                                            data-machine-name="<?= e($p['name']) ?> (<?= e($p['model_number']) ?>)">
                                        Quote
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- ==========================================================================
     CUSTOMER FEEDBACK & VERIFIED FACTORY REVIEWS SECTION
     ========================================================================== -->
<?php
$approvedFeedbacks = [];
try {
    $fbStmt = $pdo->query("SELECT * FROM feedbacks WHERE is_approved = 1 ORDER BY is_featured DESC, id DESC LIMIT 6");
    $approvedFeedbacks = $fbStmt->fetchAll();
} catch (Exception $e) {}
?>
<section class="section" id="testimonialsSection" style="background: linear-gradient(180deg, rgba(11, 17, 32, 0.9) 0%, rgba(6, 9, 17, 0.95) 100%); border-top: 1px solid var(--border-subtle); border-bottom: 1px solid var(--border-subtle);">
    <div class="container">
        <div class="section-title-wrap">
            <div class="pill-badge" style="border-color: #f59e0b; color: #fbbf24;">★ 100% VERIFIED CUSTOMER REVIEWS</div>
            <h2 class="section-title">What Industrial Workshop <span>Owners Say</span></h2>
            <p class="section-desc">
                Over 1,800+ high precision drill machines delivered across Gujarat, Maharashtra, Tamil Nadu, and pan-India. Read authentic feedback from machine shop owners and tool rooms.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 280px), 1fr)); gap: 24px; margin-bottom: 35px;">
            <?php if (!empty($approvedFeedbacks)): ?>
                <?php foreach ($approvedFeedbacks as $af): ?>
                    <div style="background: rgba(15, 23, 42, 0.85); border: 1px solid rgba(251, 191, 36, 0.25); border-radius: 14px; padding: 24px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 10px 30px rgba(0,0,0,0.4); transition: transform 0.25s ease;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <div style="color: #fbbf24; font-size: 16px; letter-spacing: 2px;">
                                    <?= str_repeat('★', intval($af['rating'])) . str_repeat('☆', 5 - intval($af['rating'])) ?>
                                </div>
                                <span style="font-size: 11px; background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid #10b981; padding: 2px 7px; border-radius: 4px; font-weight: 700;">
                                    ✔ Verified Purchase
                                </span>
                            </div>

                            <?php if (!empty($af['feedback_title'])): ?>
                                <h4 style="font-size: 15px; font-weight: 700; color: #fff; margin-bottom: 8px; line-height: 1.3;">
                                    <?= e($af['feedback_title']) ?>
                                </h4>
                            <?php endif; ?>

                            <p style="font-size: 13px; color: #cbd5e1; line-height: 1.55; margin-bottom: 16px;">
                                "<?= nl2br(e($af['comments'])) ?>"
                            </p>
                        </div>

                        <div style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 12px; display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <strong style="color: #fff; font-size: 13.5px;"><?= e($af['customer_name']) ?></strong>
                                <?php if (!empty($af['company_name'])): ?>
                                    <div style="font-size: 11.5px; color: #38bdf8;"><?= e($af['company_name']) ?></div>
                                <?php endif; ?>
                                <?php if (!empty($af['city'])): ?>
                                    <div style="font-size: 11px; color: #94a3b8;">📍 <?= e($af['city']) ?></div>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($af['product_name'])): ?>
                                <div style="text-align: right;">
                                    <span class="badge-tag" style="border-color: rgba(255,255,255,0.15); color: #94a3b8; font-size: 10.5px;">
                                        <?= e($af['product_name']) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Give Feedback Action Banner -->
        <div style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.12) 0%, rgba(217, 119, 6, 0.08) 100%); border: 1.5px dashed rgba(245, 158, 11, 0.4); border-radius: 14px; padding: 24px 30px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
            <div>
                <h3 style="font-family: var(--font-heading); font-size: 18px; color: #fff; margin-bottom: 4px;">
                    Are You an Existing Customer of Shree Varsha Engineering Works?
                </h3>
                <p style="font-size: 13px; color: #cbd5e1; margin: 0;">
                    Your feedback helps us continuously elevate our precision manufacturing standards. Share your experience with our machines!
                </p>
            </div>
            <button type="button" onclick="openFeedbackModal()" class="btn btn-primary" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-color: #fbbf24; font-weight: 800; font-size: 13.5px; padding: 12px 24px; box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);">
                ⭐ Write a Customer Review / Feedback
            </button>
        </div>
    </div>
</section>

<!-- ==========================================================================
     CONTACT & FACTORY INQUIRY SECTION
     ========================================================================== -->
<section class="section" id="contact" style="background: rgba(11, 17, 32, 0.5);">
    <div class="container">
        <div class="section-title-wrap">
            <div class="pill-badge">GET IN TOUCH</div>
            <h2 class="section-title">Contact Our <span>Engineering Works</span></h2>
            <p class="section-desc">
                Speak directly with Jitendrabhai Parmar or our senior machining specialists for factory visit, custom machine requirements, technical consultation, or instant quotes.
            </p>
        </div>

        <div class="contact-grid">
            <!-- Contact Information Panel -->
            <div class="contact-info-panel">
                <h3 style="font-family: var(--font-heading); font-size: 20px; color: #fff; margin-bottom: 8px;">
                    <?= APP_NAME ?>
                </h3>
                <p style="font-size: 14px; color: var(--text-muted); line-height: 1.6;">
                    Precision Drill Machine Manufacturers & Industrial Specialists. Bhavnagar, Gujarat.
                </p>

                <div class="contact-card-list">
                    <div class="contact-channel">
                        <div class="channel-icon">
                            <svg viewBox="0 0 24 24"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                        </div>
                        <div class="channel-content">
                            <h4>Direct Phone Lines</h4>
                            <p><a href="tel:<?= COMPANY_PHONE_1_CLEAN ?>"><?= COMPANY_PHONE_1 ?> (Jitendrabhai Parmar)</a></p>
                            <p><a href="tel:<?= COMPANY_PHONE_2_CLEAN ?>"><?= COMPANY_PHONE_2 ?> (Jay Parmar)</a></p>
                        </div>
                    </div>

                    <div class="contact-channel">
                        <div class="channel-icon">
                            <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        </div>
                        <div class="channel-content">
                            <h4>Official Email</h4>
                            <p><a href="mailto:<?= COMPANY_EMAIL ?>"><?= COMPANY_EMAIL ?></a></p>
                        </div>
                    </div>

                    <div class="contact-channel">
                        <div class="channel-icon">
                            <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        </div>
                        <div class="channel-content">
                            <h4>Factory Location</h4>
                            <p><?= COMPANY_ADDRESS ?></p>
                        </div>
                    </div>

                    <div class="contact-channel">
                        <div class="channel-icon">
                            <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                        </div>
                        <div class="channel-content">
                            <h4>Working Hours</h4>
                            <p>Monday - Saturday: 8:30 AM to 7:30 PM (Sunday Closed)</p>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 28px;">
                    <a href="<?= getWhatsAppLink(COMPANY_WHATSAPP, 'Hello Shree Varsha Engineering Works, I would like to get a quotation for your Drill Machines.') ?>" target="_blank" class="btn btn-whatsapp" style="width: 100%;">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/></svg>
                        Direct WhatsApp with Jitendrabhai Parmar
                    </a>
                </div>
            </div>

            <!-- Inquiry Form Card -->
            <div class="inquiry-form-card">
                <h3 style="font-family: var(--font-heading); font-size: 20px; color: #fff; margin-bottom: 6px;">
                    Direct Factory Quotation Form
                </h3>
                <p style="font-size: 13.5px; color: var(--text-muted); margin-bottom: 22px;">
                    Send us your drilling capacity requirement and receive detailed technical specifications and quotation.
                </p>

                <form action="<?= BASE_URL ?>/get-quote.php" method="POST" class="inquiry-form">
                    <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="customer_name" class="form-control" placeholder="e.g. Ramesh Bhai" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Company / Workshop Name</label>
                            <input type="text" name="company_name" class="form-control" placeholder="e.g. Varsha Auto Components">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Mobile Number *</label>
                            <input type="tel" name="phone" class="form-control" placeholder="e.g. +91 9824913555" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control" placeholder="e.g. jitubhaivarsh@gmail.com" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Select Drill Machine Model (Optional)</label>
                        <select name="machine_name" class="form-control" style="background: rgba(11, 17, 32, 0.9);">
                            <option value="General Drill Machine Inquiry">General Drill Machine Inquiry</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= e($p['name']) ?> (<?= e($p['model_number']) ?>)">
                                    <?= e($p['name']) ?> - Capacity: <?= e($p['drilling_capacity']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Your Drilling Requirement & Questions</label>
                        <textarea name="message" class="form-control" rows="3" placeholder="Please mention your required drilling capacity, work piece material, number of machines, and delivery destination..."></textarea>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #cbd5e1; cursor: pointer;">
                            <input type="checkbox" name="open_whatsapp" value="1" checked style="accent-color: var(--accent-cyan);">
                            <span>Connect me directly on WhatsApp for instant quote</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        ⚡ Submit Factory Quotation Request
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Three.js & Hero 12D 360-Degree Interactive Machine Digital Twin Scripts -->
<script src="<?= assetUrl('assets/js/three.min.js') ?>"></script>
<script src="<?= assetUrl('assets/js/machine-6d-viewer.js') ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const heroCanvas = document.getElementById('heroCanvasContainer');
        if (!heroCanvas || typeof Machine6DViewer === 'undefined') return;

        // Initialize 360 Interactive Digital Twin
        let heroViewer = null;
        try {
            heroViewer = new Machine6DViewer('heroCanvasContainer', {
                autoRotate: true,
                enableSparks: true,
                theme: 'realistic',
                showTelemetry: false
            });
        } catch(e) {
            console.error("Hero 3D Twin Init Error:", e);
        }

        const btnHeroView3D = document.getElementById('btnHeroView3D');
        const btnHeroViewPhoto = document.getElementById('btnHeroViewPhoto');
        const hero3DViewport = document.getElementById('hero3DViewport');
        const heroPhotoViewport = document.getElementById('heroPhotoViewport');

        const btnHeroAutoSpin = document.getElementById('btnHeroAutoSpin');
        const btnHeroCutDrill = document.getElementById('btnHeroCutDrill');
        const btnHeroAudio = document.getElementById('btnHeroAudio');

        const heroRpmDisplay = document.getElementById('heroRpmDisplay');
        const heroTorqueDisplay = document.getElementById('heroTorqueDisplay');

        // Toggle 3D vs Real Photo
        if (btnHeroView3D && btnHeroViewPhoto) {
            btnHeroView3D.addEventListener('click', function() {
                btnHeroView3D.classList.add('active');
                btnHeroViewPhoto.classList.remove('active');
                hero3DViewport.style.display = 'block';
                heroPhotoViewport.style.display = 'none';
            });
            btnHeroViewPhoto.addEventListener('click', function() {
                btnHeroViewPhoto.classList.add('active');
                btnHeroView3D.classList.remove('active');
                hero3DViewport.style.display = 'none';
                heroPhotoViewport.style.display = 'flex';
            });
        }

        // Auto-Spin 360° Toggle
        if (btnHeroAutoSpin && heroViewer) {
            btnHeroAutoSpin.addEventListener('click', function() {
                heroViewer.options.autoRotate = !heroViewer.options.autoRotate;
                if (heroViewer.options.autoRotate) {
                    btnHeroAutoSpin.classList.add('active');
                    btnHeroAutoSpin.textContent = "↺ Auto-Spin 360°";
                } else {
                    btnHeroAutoSpin.classList.remove('active');
                    btnHeroAutoSpin.textContent = "⏸ Manual Drag";
                }
            });
        }

        // 12D Cutting Sparks Toggle
        let cuttingActive = false;
        if (btnHeroCutDrill && heroViewer) {
            btnHeroCutDrill.addEventListener('click', function() {
                cuttingActive = !cuttingActive;
                heroViewer.setDrilling(cuttingActive);
                if (cuttingActive) {
                    btnHeroCutDrill.classList.add('active');
                    btnHeroCutDrill.style.background = 'rgba(245, 158, 11, 0.3)';
                    if (heroRpmDisplay) heroRpmDisplay.textContent = "850 RPM";
                    if (heroTorqueDisplay) heroTorqueDisplay.textContent = "215 Nm";
                } else {
                    btnHeroCutDrill.classList.remove('active');
                    btnHeroCutDrill.style.background = '';
                    if (heroRpmDisplay) heroRpmDisplay.textContent = "650 RPM";
                    if (heroTorqueDisplay) heroTorqueDisplay.textContent = "142 Nm";
                }
            });
        }

        // Audio Drone Toggle
        if (btnHeroAudio && heroViewer) {
            btnHeroAudio.addEventListener('click', function() {
                heroViewer.soundEnabled = !heroViewer.soundEnabled;
                if (heroViewer.soundEnabled) {
                    btnHeroAudio.classList.add('active');
                    btnHeroAudio.style.background = 'rgba(168, 85, 247, 0.3)';
                    btnHeroAudio.textContent = "🔊 Drone ON";
                } else {
                    btnHeroAudio.classList.remove('active');
                    btnHeroAudio.style.background = '';
                    btnHeroAudio.textContent = "🔇 Sound Muted";
                }
            });
        }
    });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
