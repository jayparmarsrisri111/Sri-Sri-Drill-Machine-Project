<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/cart-helper.php';

$machineId = intval($_GET['id'] ?? 1);

try {
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare("
        SELECT p.*, c.name as category_name, c.slug as category_slug 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        WHERE p.id = :id AND p.is_active = 1
    ");
    $stmt->execute([':id' => $machineId]);
    $machine = $stmt->fetch();

    if (!$machine) {
        header("Location: machines.php");
        exit;
    }
} catch (Exception $e) {
    die("Error loading machine: " . $e->getMessage());
}

$isSparePart = (!empty($machine['product_type']) && $machine['product_type'] === 'part') || ($machine['category_id'] >= 5) || ($machine['id'] >= 100);

$mStock = intval($machine['stock_quantity'] ?? 5);
$mBasePrice = floatval($machine['price'] ?? getMachinePrice($machine['id']));

// Fetch Amazon-style Frequently Bought Together bundle and smart recommendations
$fbtData = getFrequentlyBoughtTogether($pdo, $machine['id']);
$smartRecs = getSmartRecommendations($pdo, $machine['id'], $mBasePrice, 4);

// Fetch compatible spare parts & tooling accessories
$compatParts = [];
try {
    $cpStmt = $pdo->query("
        SELECT p.*, c.name as category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        WHERE (p.product_type = 'part' OR p.id >= 100) AND p.is_active = 1 
        ORDER BY p.id ASC LIMIT 6
    ");
    $compatParts = $cpStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

$pageTitle = e($machine['name']) . ' (' . e($machine['model_number']) . ') | ' . BRAND_NAME . ' Bhavnagar';
$pageDesc = !empty($machine['short_desc']) ? e($machine['short_desc']) : 'Heavy-duty ' . e($machine['name']) . ' manufactured in Bhavnagar, Gujarat by Shree Varsha Engineering Works.';
$pageKeywords = e($machine['name']) . ', ' . e($machine['model_number']) . ', ' . e($machine['drilling_capacity']) . ', radial drill machine, pillar drill, bench drill, industrial machine tools, Shree Varsha Engineering Works';
$currentNav = 'machines';
$ogType = 'product';
$canonicalUrl = 'https://varsha-drill-machines.onrender.com/machine-detail.php?id=' . $machine['id'];
$ogImage = !empty($machine['image_url']) ? assetUrl($machine['image_url']) : 'https://varsha-drill-machines.onrender.com/assets/images/machinery/radial-drill-40-real.jpg';

// Rich Google Product Schema (JSON-LD)
$pageSchema = [
    '@type' => 'Product',
    'name' => $machine['name'] . ' (' . $machine['model_number'] . ')',
    'image' => [$ogImage],
    'description' => !empty($machine['short_desc']) ? $machine['short_desc'] : $machine['full_desc'],
    'sku' => $machine['model_number'],
    'mpn' => $machine['model_number'],
    'brand' => [
        '@type' => 'Brand',
        'name' => 'VARSHA BRAND'
    ],
    'manufacturer' => [
        '@type' => 'Organization',
        'name' => 'Shree Varsha Engineering Works',
        'url' => 'https://varsha-drill-machines.onrender.com/'
    ],
    'offers' => [
        '@type' => 'Offer',
        'url' => $canonicalUrl,
        'priceCurrency' => 'INR',
        'price' => $mBasePrice,
        'priceValidUntil' => '2027-12-31',
        'itemCondition' => 'https://schema.org/NewCondition',
        'availability' => ($mStock > 0) ? 'https://schema.org/InStock' : 'https://schema.org/PreOrder',
        'seller' => [
            '@type' => 'Organization',
            'name' => 'Shree Varsha Engineering Works'
        ]
    ],
    'aggregateRating' => [
        '@type' => 'AggregateRating',
        'ratingValue' => '4.9',
        'reviewCount' => '42',
        'bestRating' => '5',
        'worstRating' => '1'
    ]
];

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Parse key features
$featuresList = !empty($machine['key_features']) ? explode(';', $machine['key_features']) : [];

// Determine authentic working video demonstration ID for each machine/part
$realVideoId = '3fuIpyX8mKQ'; // Default heavy industrial radial drill operation
$realVideoTitle = 'Heavy-Duty Geared Radial Drill Machine Factory Workshop Demonstration';

if (stripos($machine['name'], 'Pillar') !== false || stripos($machine['model_number'], 'PD') !== false) {
    $realVideoId = 'kS5Fbgtayhg';
    $realVideoTitle = '40mm Gear-Type Heavy Duty Pillar Drill Machine Operational Demonstration';
} elseif (stripos($machine['name'], 'Bench') !== false || stripos($machine['model_number'], 'BD') !== false) {
    $realVideoId = 'IcJc2JmNeV8';
    $realVideoTitle = 'Precision High-Speed Bench Drilling Workshop Demonstration';
} elseif (stripos($machine['name'], 'Tapping') !== false || stripos($machine['model_number'], 'TP') !== false) {
    $realVideoId = '3fuIpyX8mKQ';
    $realVideoTitle = 'Auto-Reversing Drilling & Tapping Machine Live Operation';
} elseif (!empty($machine['product_type']) && $machine['product_type'] === 'part') {
    $realVideoId = '3fuIpyX8mKQ';
    $realVideoTitle = 'Precision Machine Part Assembly & Live Spindle Headstock Test Run';
}

$mStock = intval($machine['stock_quantity'] ?? 5);
$mBasePrice = floatval($machine['price'] ?? getMachinePrice($machine['id']));

// Multi-angle real images gallery configuration
if ($isSparePart) {
    $galleryImages = [
        [
            'title' => 'Angle 1: Real Component Showcase',
            'desc' => 'High-precision manufactured part with induction heat treatment',
            'url' => assetUrl($machine['image_url'])
        ],
        [
            'title' => 'Angle 2: Hardened Gear Train & Spindle Fitment',
            'desc' => 'Precision 20MnCr5 alloy gears assembly view',
            'url' => assetUrl('assets/images/machinery/part-gear-set.jpg')
        ],
        [
            'title' => 'Angle 3: Internal Ground Spindle & Quill Assembly',
            'desc' => 'Internal MT taper with SKF/FAG needle roller bearings',
            'url' => assetUrl('assets/images/machinery/part-spindle-quill.jpg')
        ],
        [
            'title' => 'Angle 4: Heavy Ribbed Clamping Box Table',
            'desc' => 'Machined T-slots for rigid workpiece holddown under heavy cuts',
            'url' => assetUrl('assets/images/machinery/part-box-table.jpg')
        ],
        [
            'title' => 'Angle 5: Mounted in SVEW Radial Drill Headstock',
            'desc' => 'Installed and operating inside industrial radial drill headstock',
            'url' => assetUrl('assets/images/machinery/radial-drill-40-real.jpg')
        ]
    ];
} else {
    $galleryImages = [
        [
            'title' => 'Angle 1: Full Front Elevation & Assembly Profile',
            'desc' => 'Complete industrial drill machine with heavy Grade-25 casting',
            'url' => assetUrl($machine['image_url'])
        ],
        [
            'title' => 'Angle 2: Hardened & Ground Headstock Gearbox',
            'desc' => 'Case-hardened alloy steel gears with oil bath splash lubrication',
            'url' => assetUrl('assets/images/machinery/part-gear-set.jpg')
        ],
        [
            'title' => 'Angle 3: Precision Spindle Quill & Power Feed Hub',
            'desc' => 'Morse taper spindle with ground spline & automatic power feed',
            'url' => assetUrl('assets/images/machinery/part-spindle-quill.jpg')
        ],
        [
            'title' => 'Angle 4: Heavy Ribbed Work Table with T-Slots',
            'desc' => 'Machined T-slots for rigid workpiece clamping under heavy torque',
            'url' => assetUrl('assets/images/machinery/part-box-table.jpg')
        ],
        [
            'title' => 'Angle 5: High-Torque 3-Phase Induction Flange Motor',
            'desc' => '415V continuous duty copper wound motor & electrical controls',
            'url' => assetUrl('assets/images/machinery/part-induction-motor.jpg')
        ],
        [
            'title' => 'Angle 6: Submerged Coolant Pump & Chip Wash Kit',
            'desc' => 'High-flow coolant circulation with flexible metallic nozzle',
            'url' => assetUrl('assets/images/machinery/part-coolant-pump.jpg')
        ]
    ];
}
?>

<!-- Breadcrumb -->
<div style="background: rgba(11, 17, 32, 0.4); padding: 14px 0; border-bottom: 1px solid var(--border-subtle); font-size: 13px;">
    <div class="container">
        <a href="<?= BASE_URL ?>/index.php" style="color: var(--text-muted);">Home</a>
        <span style="color: var(--text-muted); margin: 0 8px;">/</span>
        <a href="<?= BASE_URL ?>/machines.php" style="color: var(--text-muted);">Machines</a>
        <span style="color: var(--text-muted); margin: 0 8px;">/</span>
        <span style="color: var(--accent-cyan); font-weight: 600;"><?= e($machine['model_number']) ?></span>
    </div>
</div>

<style>
    .machine-detail-grid {
        display: grid;
        grid-template-columns: 1.05fr 0.95fr;
        gap: 40px;
        align-items: flex-start;
    }
    .machine-visual-box {
        background: var(--bg-card);
        border: 1.5px solid var(--border-cyan);
        border-radius: var(--radius-lg);
        padding: 24px 20px;
        box-shadow: var(--shadow-card);
        position: sticky;
        top: 95px;
    }
    .main-gallery-view {
        position: relative;
        background: radial-gradient(circle at 50% 50%, rgba(15, 23, 42, 0.8) 0%, rgba(6, 9, 17, 0.98) 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        min-height: 380px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        overflow: hidden;
    }
    .main-gallery-view img {
        max-height: 360px;
        width: 100%;
        object-fit: contain;
        filter: drop-shadow(0 15px 30px rgba(0,0,0,0.6));
        transition: transform 0.3s ease, opacity 0.25s ease;
    }
    .gallery-caption-bar {
        margin-top: 12px;
        background: rgba(15, 23, 42, 0.85);
        border: 1px solid rgba(0, 229, 255, 0.25);
        border-radius: 8px;
        padding: 8px 14px;
        text-align: left;
        width: 100%;
    }
    .gallery-caption-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 13px;
        font-weight: 700;
        color: #38bdf8;
    }
    .gallery-caption-desc {
        font-size: 11.5px;
        color: #94a3b8;
        margin-top: 2px;
    }
    .gallery-thumbs-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(70px, 1fr));
        gap: 8px;
        margin-top: 14px;
    }
    .gallery-thumb-btn {
        background: rgba(15, 23, 42, 0.9);
        border: 1.5px solid rgba(255, 255, 255, 0.12);
        border-radius: 8px;
        padding: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .gallery-thumb-btn:hover {
        border-color: var(--accent-cyan);
        transform: translateY(-2px);
    }
    .gallery-thumb-btn.active {
        border-color: #00e5ff;
        background: rgba(0, 229, 255, 0.12);
        box-shadow: 0 0 12px rgba(0, 229, 255, 0.35);
    }
    .gallery-thumb-btn img {
        width: 100%;
        height: 52px;
        object-fit: cover;
        border-radius: 4px;
    }
    .gallery-thumb-label {
        font-size: 10px;
        font-weight: 700;
        color: #cbd5e1;
        margin-top: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 65px;
    }
    @media (max-width: 992px) {
        .machine-detail-grid {
            grid-template-columns: 1fr;
            gap: 28px;
        }
        .machine-visual-box {
            position: static;
        }
        .main-gallery-view {
            min-height: 280px;
        }
        .main-gallery-view img {
            max-height: 260px;
        }
        #videoContainerGrid {
            grid-template-columns: 1fr !important;
        }
        #videoContainerGrid > div:last-child {
            border-left: none !important;
            border-top: 1px solid rgba(0, 229, 255, 0.2) !important;
        }
    }
</style>

<!-- Machine Detail Overview -->
<section class="section">
    <div class="container">
        <div class="machine-detail-grid">
            <!-- Left: Multi-Angle Real Photos Showcase -->
            <div class="machine-visual-box">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                    <span class="pill-badge"><?= e($machine['category_name']) ?></span>
                    <?php if ($mStock > 3): ?>
                        <span class="badge-tag" style="background: rgba(16, 185, 129, 0.15); border-color: #10b981; color: #34d399; font-weight: 700;">
                            READY FOR DISPATCH • <?= $mStock ?> UNITS IN STOCK
                        </span>
                    <?php elseif ($mStock > 0): ?>
                        <span class="badge-tag" style="background: rgba(245, 158, 11, 0.15); border-color: #f59e0b; color: #fbbf24; font-weight: 800;">
                            ⚡ LIMITED STOCK • ONLY <?= $mStock ?> UNITS LEFT IN FACTORY!
                        </span>
                    <?php else: ?>
                        <span class="badge-tag" style="background: rgba(239, 68, 68, 0.15); border-color: #ef4444; color: #f87171; font-weight: 700;">
                            ⏳ OUT OF STOCK • 5-7 DAYS MANUFACTURING DISPATCH
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Main Active Image -->
                <div class="main-gallery-view">
                    <img id="mainProductImg" src="<?= $galleryImages[0]['url'] ?>" alt="<?= e($machine['name']) ?>">
                    <div class="gallery-caption-bar">
                        <div id="galleryCaptionTitle" class="gallery-caption-title"><?= e($galleryImages[0]['title']) ?></div>
                        <div id="galleryCaptionDesc" class="gallery-caption-desc"><?= e($galleryImages[0]['desc']) ?></div>
                    </div>
                </div>

                <!-- Multi-Angle Thumbnail Switchers -->
                <div style="margin-top: 12px;">
                    <div style="font-size: 11.5px; font-weight: 800; color: #38bdf8; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 6px; display: flex; align-items: center; justify-content: space-between;">
                        <span>📷 Real Multi-Angle Photos (ક્લિક કરીને જુઓ)</span>
                        <span style="font-size: 11px; color: #94a3b8;"><?= count($galleryImages) ?> Angles Available</span>
                    </div>
                    <div class="gallery-thumbs-row">
                        <?php foreach ($galleryImages as $idx => $gImg): ?>
                            <button type="button" class="gallery-thumb-btn <?= ($idx === 0) ? 'active' : '' ?>" 
                                    onclick="switchGalleryAngle(<?= $idx ?>)"
                                    data-url="<?= $gImg['url'] ?>"
                                    data-title="<?= e($gImg['title']) ?>"
                                    data-desc="<?= e($gImg['desc']) ?>">
                                <img src="<?= $gImg['url'] ?>" alt="Angle <?= $idx + 1 ?>">
                                <span class="gallery-thumb-label">Angle <?= $idx + 1 ?></span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Action Buttons Strip -->
                <div style="margin-top: 22px; display: flex; gap: 10px; flex-wrap: wrap;">
                    <button type="button" class="btn-360-turntable open-360-modal" data-machine-name="<?= e($machine['name']) ?> (<?= e($machine['model_number']) ?>)" data-machine-img="<?= assetUrl($machine['image_url']) ?>" style="flex: 1 1 100%; height: 44px; font-size: 13.5px;">
                        <span class="turntable-spin-icon">🔄</span>
                        <span>360° Interactive 12D Machine View</span>
                        <span class="turntable-step-pill">30° STEP</span>
                    </button>
                    <a id="btnBookOnlineTop" href="<?= BASE_URL ?>/checkout.php?machine_id=<?= $machine['id'] ?>&qty=1" class="btn btn-primary" style="flex: 1 1 100%; text-align: center; background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399; font-weight: 700; padding: 13px; font-size: 15px;">
                        💳 Book Machine Online (Razorpay)
                    </a>
                    <button type="button" class="btn btn-cart-detail" onclick="window.addToCart(<?= $machine['id'] ?>, parseInt(document.getElementById('detailQtyInput').value) || 1, this)" style="flex: 1 1 100%; text-align: center; background: rgba(245, 158, 11, 0.16); border: 1.5px solid #f59e0b; color: #fbbf24; font-weight: 800; padding: 13px; font-size: 15px; border-radius: 8px; cursor: pointer; transition: all 0.25s;">
                        <span>🛒 Add to Cart (કાર્ટમાં ઉમેરો)</span>
                    </button>
                    <button type="button" class="btn btn-outline open-quote-modal" 
                            style="flex: 1 1 170px;"
                            data-machine-id="<?= $machine['id'] ?>"
                            data-machine-name="<?= e($machine['name']) ?> (<?= e($machine['model_number']) ?>)">
                        ⚡ Factory Quotation
                    </button>
                    <a href="<?= getWhatsAppLink(COMPANY_WHATSAPP, 'Hello ' . APP_NAME . ', I am looking for details on ' . $machine['name'] . ' (' . $machine['model_number'] . '). Please share price and catalog.') ?>" target="_blank" class="btn btn-whatsapp" style="flex: 1 1 170px;">
                        WhatsApp Inquiry
                    </a>
                </div>
            </div>

            <!-- Right: 100% Sachi Details (Technical Specifications) -->
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                    <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand" style="height: 32px; width: 32px; border-radius: 50%; border: 1.5px solid var(--accent-cyan); object-fit: cover;">
                    <span style="font-family: var(--font-heading); font-size: 13px; color: var(--accent-cyan); font-weight: 700; letter-spacing: 1px; text-transform: uppercase;">
                        VARSHA BRAND • MODEL: <?= e($machine['model_number']) ?>
                    </span>
                </div>
                <h1 style="font-family: var(--font-heading); font-size: 30px; font-weight: 800; color: #fff; margin: 8px 0 16px; line-height: 1.25;">
                    <?= e($machine['name']) ?>
                </h1>

                <!-- Prominent Price Breakdown Banner with Token Advance vs Full Payment -->
                <div style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(6, 10, 20, 0.98) 100%); border: 1.5px solid var(--accent-cyan); border-radius: 12px; padding: 18px 22px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; box-shadow: 0 10px 25px rgba(0, 229, 255, 0.15);">
                    <div>
                        <div style="font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 700; letter-spacing: 0.8px;">Direct Factory Ex-Price</div>
                        <div style="font-family: 'Space Grotesk', sans-serif; font-size: 28px; font-weight: 900; color: #34d399; line-height: 1.1; margin-top: 2px;">
                            ₹ <?= number_format($machine['price'] ?? getMachinePrice($machine['id']), 2) ?>
                        </div>
                        <div style="font-size: 11.5px; color: #38bdf8; margin-top: 4px;">
                            + 18% GST (CGST 9% + SGST 9%) • HSN Code 8459
                        </div>
                    </div>
                    <div style="text-align: right; border-left: 1px solid rgba(255,255,255,0.1); padding-left: 18px;">
                        <div style="font-size: 11px; color: #94a3b8;">Online Booking Option</div>
                        <div style="font-size: 14px; font-weight: 800; color: #facc15;">
                            Token Advance: ₹ 25,000
                        </div>
                        <div style="font-size: 11px; color: #34d399; margin-top: 2px;">
                            ✔ Balance on factory dispatch
                        </div>
                    </div>
                </div>

                <!-- Interactive Dynamic Quantity & Stock Selector -->
                <div style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(6, 10, 20, 0.95) 100%); border: 1.5px solid rgba(0, 229, 255, 0.35); border-radius: 10px; padding: 14px 18px; margin-bottom: 22px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                        <div>
                            <div style="font-family: var(--font-heading); font-size: 14px; font-weight: 800; color: #fff; display: flex; align-items: center; gap: 8px;">
                                <span>📦 Order Quantity (જથ્થો / સંખ્યા):</span>
                                <?php if ($mStock > 0): ?>
                                    <span id="detailStockBadge" style="background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; color: #34d399; font-size: 11px; font-weight: 800; padding: 2px 8px; border-radius: 4px;">
                                        <?= $mStock ?> Units In Factory Stock
                                    </span>
                                <?php else: ?>
                                    <span id="detailStockBadge" style="background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #f87171; font-size: 11px; font-weight: 800; padding: 2px 8px; border-radius: 4px;">
                                        Made to Order (5-7 Days)
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size: 11.5px; color: #94a3b8; margin-top: 3px;" id="detailStockHelper">
                                <?php if ($mStock > 0): ?>
                                    Direct inventory allocation: Buying reduces factory inventory immediately in real-time.
                                <?php else: ?>
                                    Custom manufacturing queue allocation with fast dispatch.
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Stepper Controls -->
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <button type="button" onclick="adjustDetailQty(-1)" style="width: 36px; height: 36px; border-radius: 6px; background: #060911; border: 1.5px solid var(--accent-cyan); color: #fff; font-size: 18px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center;">−</button>
                            <input type="number" id="detailQtyInput" value="1" min="1" max="<?= max(1, $mStock) ?>" style="width: 60px; height: 36px; text-align: center; font-size: 17px; font-weight: 800; background: #060911; border: 1.5px solid #0284c7; color: #34d399; border-radius: 6px;" onchange="onDetailQtyChange()">
                            <button type="button" onclick="adjustDetailQty(1)" style="width: 36px; height: 36px; border-radius: 6px; background: #060911; border: 1.5px solid var(--accent-cyan); color: #fff; font-size: 18px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center;">+</button>
                            <button type="button" onclick="window.addToCart(<?= $machine['id'] ?>, parseInt(document.getElementById('detailQtyInput').value) || 1, this)" style="height: 36px; padding: 0 14px; background: rgba(245, 158, 11, 0.18); border: 1.5px solid #f59e0b; color: #fbbf24; font-size: 12.5px; font-weight: 800; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 5px; transition: all 0.2s;">
                                <span>🛒 Add to Cart</span>
                            </button>
                        </div>
                    </div>

                    <!-- Calculated Total Price for Selected Quantity -->
                    <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; font-size: 13px;">
                        <span style="color: #cbd5e1;">Billed Value (<span id="lblSelectedQty" style="color: var(--accent-cyan); font-weight: 700;">1 Unit</span> + 18% GST):</span>
                        <strong id="lblCalculatedTotal" style="font-size: 16px; color: #34d399;">₹ <?= number_format($mBasePrice * 1.18, 2) ?></strong>
                    </div>
                </div>
                
                <p style="font-size: 15px; color: var(--text-secondary); line-height: 1.7; margin-bottom: 22px;">
                    <?= e($machine['full_desc'] ?? $machine['short_desc']) ?>
                </p>

                <!-- Key Advantages / Features -->
                <?php if (!empty($featuresList)): ?>
                <div style="background: rgba(11, 17, 32, 0.6); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 18px; margin-bottom: 26px;">
                    <h3 style="font-family: var(--font-heading); font-size: 15px; font-weight: 700; color: #fff; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                        <span style="color: var(--accent-cyan);">✦</span> Key Engineering Highlights
                    </h3>
                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 8px; margin: 0; padding: 0;">
                        <?php foreach ($featuresList as $feat): ?>
                            <?php if (trim($feat)): ?>
                            <li style="display: flex; align-items: flex-start; gap: 8px; font-size: 13.5px; color: #cbd5e1;">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="var(--accent-cyan)" style="flex-shrink: 0; margin-top: 2px;"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                <span><?= e(trim($feat)) ?></span>
                            </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Complete 100% Accurate Technical Specifications Table -->
                <h3 style="font-family: var(--font-heading); font-size: 18px; font-weight: 800; color: #fff; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between;">
                    <span>⚙️ 100% Technical Specifications</span>
                    <span style="font-size: 11px; color: #34d399; font-weight: 700; background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; padding: 3px 8px; border-radius: 9999px;">
                        ISO 9001:2015 TESTED
                    </span>
                </h3>

                <div class="table-responsive" style="margin-bottom: 28px;">
                    <table class="tech-table">
                        <tbody>
                            <tr>
                                <th style="width: 42%;">Engineering Parameter</th>
                                <th>100% Verified Specification</th>
                            </tr>
                            <?php if ($isSparePart): ?>
                                <tr>
                                    <td><strong>Part Classification & Fitment</strong></td>
                                    <td style="color: var(--accent-cyan); font-weight: 700;"><?= e($machine['drilling_capacity']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Material Specification</strong></td>
                                    <td>Grade 20MnCr5 Alloy Steel / EN-8 / Heavy Casting</td>
                                </tr>
                                <tr>
                                    <td><strong>Heat Treatment & Hardness</strong></td>
                                    <td>Induction Case Hardened & Nitrided (58 - 62 HRC)</td>
                                </tr>
                                <?php if (!empty($machine['spindle_travel'])): ?>
                                <tr>
                                    <td>Spindle Travel / Stroke Compatibility</td>
                                    <td><?= e($machine['spindle_travel']) ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($machine['spindle_speeds'])): ?>
                                <tr>
                                    <td>Speed & Dynamic Tolerance</td>
                                    <td><?= e($machine['spindle_speeds']) ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td>Dynamic Run-Out Tolerance</td>
                                    <td>Under 0.005 mm (Laser Calibrated)</td>
                                </tr>
                                <tr>
                                    <td>Component Weight Approx.</td>
                                    <td><?= e($machine['weight_approx'] ?? '15 kg') ?></td>
                                </tr>
                                <tr>
                                    <td>Manufacturing Origin</td>
                                    <td>100% In-House Manufactured, Bhavnagar Works, Gujarat</td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td><strong>Drilling Capacity</strong></td>
                                    <td style="color: var(--accent-cyan); font-weight: 700;"><?= e($machine['drilling_capacity']) ?></td>
                                </tr>
                                <?php if (!empty($machine['spindle_speeds'])): ?>
                                <tr>
                                    <td>Spindle Speeds & RPM Range</td>
                                    <td><?= e($machine['spindle_speeds']) ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($machine['spindle_travel'])): ?>
                                <tr>
                                    <td>Spindle Quill Travel (Feed Stroke)</td>
                                    <td><?= e($machine['spindle_travel']) ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($machine['column_diameter'])): ?>
                                <tr>
                                    <td>Column Diameter</td>
                                    <td><?= e($machine['column_diameter']) ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($machine['motor_power'])): ?>
                                <tr>
                                    <td>Electric Motor Power</td>
                                    <td><?= e($machine['motor_power']) ?> (Heavy Duty 3-Phase 415V)</td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($machine['working_table_size'])): ?>
                                <tr>
                                    <td>Working Box Table Size</td>
                                    <td><?= e($machine['working_table_size']) ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($machine['drilling_radius'])): ?>
                                <tr>
                                    <td>Drilling Radius (Max/Min)</td>
                                    <td><?= e($machine['drilling_radius']) ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($machine['weight_approx'])): ?>
                                <tr>
                                    <td>Approximate Machine Weight</td>
                                    <td><?= e($machine['weight_approx']) ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td>Gear Material & Quality</td>
                                    <td>20MnCr5 Alloy Steel, Case Hardened & Precision Ground</td>
                                </tr>
                                <tr>
                                    <td>Testing Standard & Runout</td>
                                    <td>Within 0.015 mm as per IS:2199 Testing Standards</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Direct Inquiry Form on Detail Page -->
                <div class="inquiry-form-card" style="margin-top: 20px;">
                    <h3 style="font-family: var(--font-heading); font-size: 18px; color: #fff; margin-bottom: 6px;">
                        Quick Factory Quotation for <?= e($machine['model_number']) ?>
                    </h3>
                    <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">
                        Enter your contact details to receive direct factory pricing and delivery timeline from Bhavnagar.
                    </p>

                    <form action="<?= BASE_URL ?>/get-quote.php" method="POST" class="inquiry-form">
                        <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                        <input type="hidden" name="machine_id" value="<?= $machine['id'] ?>">
                        <input type="hidden" name="machine_name" value="<?= e($machine['name']) ?> (<?= e($machine['model_number']) ?>)">

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Name *</label>
                                <input type="text" name="customer_name" class="form-control" required placeholder="e.g. Ramesh Patel">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone Number *</label>
                                <input type="tel" name="phone" class="form-control" required placeholder="e.g. +91 9824913555">
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Company Name</label>
                                <input type="text" name="company_name" class="form-control" placeholder="e.g. Precision Tools">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email Address *</label>
                                <input type="email" name="email" class="form-control" required placeholder="e.g. contact@domain.com">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Delivery Location & Requirement</label>
                            <textarea name="message" class="form-control" rows="2" placeholder="Mention destination city, quantity, power rating required..."></textarea>
                        </div>

                        <div style="margin-bottom: 14px;">
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #cbd5e1; cursor: pointer;">
                                <input type="checkbox" name="open_whatsapp" value="1" checked style="accent-color: var(--accent-cyan);">
                                <span>Connect directly on WhatsApp for faster response</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            ⚡ Send Inquiry for <?= e($machine['model_number']) ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- =========================================================================
             FACTORY WORKING MACHINERY VIDEO DEMONSTRATION SECTION
             ========================================================================= -->
        <div style="margin-top: 55px; background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(6, 10, 20, 0.98) 100%); border: 1.5px solid var(--border-cyan); border-radius: var(--radius-lg); padding: 30px 26px; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.7);">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; margin-bottom: 20px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                        <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #ef4444; box-shadow: 0 0 12px #ef4444; animation: pulse 1.5s infinite;"></span>
                        <span class="pill-badge" style="background: rgba(239, 68, 68, 0.15); border-color: #ef4444; color: #f87171; font-weight: 800;">
                            LIVE FACTORY WORKSHOP DEMONSTRATION
                        </span>
                    </div>
                    <h2 style="font-family: var(--font-heading); font-size: 24px; font-weight: 800; color: #fff; margin: 4px 0;">
                        Working Machinery Video &amp; Drilling Performance <span style="font-size: 16px; color: var(--accent-cyan); font-weight: 600;">(લાઈવ મશીન વર્કિંગ વિડીયો)</span>
                    </h2>
                    <p style="font-size: 13.5px; color: #94a3b8; margin: 0;">
                        Live test run of <?= e($machine['name']) ?> (<?= e($machine['model_number']) ?>) at our Bhavnagar plant: Spindle feed, heavy torque cutting, and zero-vibration stability.
                    </p>
                </div>
                <div>
                    <a href="<?= getWhatsAppLink(COMPANY_WHATSAPP, 'Hello Jitendrabhai Parmar, I want to see a live video call demonstration of ' . $machine['name'] . ' (' . $machine['model_number'] . ') from your Bhavnagar workshop.') ?>" target="_blank" class="btn btn-whatsapp" style="font-size: 13px; padding: 10px 18px; font-weight: 700;">
                        📹 Request Live WhatsApp Video Call
                    </a>
                </div>
            </div>

            <!-- Video Player Display with Machining Telemetry HUD -->
            <div style="position: relative; border-radius: 12px; overflow: hidden; background: #020617; border: 1px solid rgba(0, 229, 255, 0.35); box-shadow: 0 10px 30px rgba(0,0,0,0.8);">
                <div style="display: grid; grid-template-columns: 1fr 320px; min-height: 420px;" id="videoContainerGrid">
                    <!-- Real Video Player Viewport -->
                    <div id="videoPlayerViewport" style="position: relative; background: #000; display: flex; flex-direction: column; justify-content: center; align-items: center; overflow: hidden; min-height: 400px;">
                        <!-- Working YouTube Embed Player with Autoplay and Fullscreen Controls -->
                        <iframe id="mainMachineIframe" 
                                src="about:blank" 
                                data-src="https://www.youtube-nocookie.com/embed/<?= $realVideoId ?>?autoplay=1&rel=0&modestbranding=1&enablejsapi=1" 
                                title="<?= e($realVideoTitle) ?>" 
                                style="display: none; width: 100%; height: 100%; min-height: 420px; border: none;" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                allowfullscreen>
                        </iframe>

                        <!-- Video Preview Poster with Real Machine Image and 1-Click Play Overlay -->
                        <div id="videoPosterOverlay" style="position: absolute; inset: 0; display: flex; flex-direction: column; justify-content: space-between; padding: 20px; background: linear-gradient(180deg, rgba(2,6,23,0.3) 0%, rgba(2,6,23,0.92) 90%);">
                            <img src="<?= assetUrl($machine['image_url']) ?>" alt="<?= e($machine['name']) ?>" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: contain; opacity: 0.45; filter: drop-shadow(0 10px 30px rgba(0,229,255,0.2)); pointer-events: none;">

                            <div style="position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
                                <span style="background: rgba(15, 23, 42, 0.9); border: 1.5px solid #10b981; color: #34d399; font-size: 11.5px; font-weight: 800; padding: 5px 12px; border-radius: 6px; box-shadow: 0 0 15px rgba(16,185,129,0.3);">
                                    ● 4K FACTORY WORKSHOP DEMO
                                </span>
                                <span style="background: rgba(15, 23, 42, 0.9); border: 1.5px solid rgba(0, 229, 255, 0.5); color: #38bdf8; font-size: 11.5px; font-weight: 700; padding: 5px 12px; border-radius: 6px;">
                                    MODEL: <?= e($machine['model_number']) ?>
                                </span>
                            </div>

                            <!-- Center Glowing Play Trigger Button -->
                            <div style="position: relative; z-index: 2; text-align: center; margin: 25px 0;">
                                <button type="button" id="btnPlayRealVideo" onclick="startRealMachineVideo()" style="background: linear-gradient(135deg, #00e5ff 0%, #2563eb 100%); border: none; width: 80px; height: 80px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 0 40px rgba(0, 229, 255, 0.7); transition: transform 0.25s, box-shadow 0.25s;">
                                    <svg viewBox="0 0 24 24" width="36" height="36" fill="#060911" style="margin-left: 4px;"><path d="M8 5v14l11-7z"/></svg>
                                </button>
                                <div style="color: #fff; font-size: 14.5px; font-weight: 800; margin-top: 14px; text-shadow: 0 2px 6px rgba(0,0,0,0.9);">
                                    Click to Start Real Machine Video Demonstration
                                </div>
                                <div style="color: #94a3b8; font-size: 12px; margin-top: 4px;">
                                    <?= e($realVideoTitle) ?>
                                </div>
                            </div>

                            <!-- Bottom Telemetry HUD Status -->
                            <div style="position: relative; z-index: 2;">
                                <div style="display: flex; justify-content: space-between; font-size: 11px; color: #cbd5e1; font-family: monospace; background: rgba(15,23,42,0.85); padding: 7px 12px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.08);">
                                    <span>SPINDLE: <strong style="color: #10b981;">650 RPM</strong></span>
                                    <span>FEED: <strong style="color: #38bdf8;">0.22 mm/rev</strong></span>
                                    <span>TORQUE: <strong style="color: #facc15;">145 Nm</strong></span>
                                    <span>AUDIO: <strong style="color: #34d399;">Factory Sound</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Telemetry & Performance Side Panel -->
                    <div style="background: rgba(11, 17, 32, 0.95); border-left: 1px solid rgba(0, 229, 255, 0.2); padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <div style="font-family: 'Space Grotesk', sans-serif; font-size: 14px; font-weight: 800; color: #fff; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                                <span style="color: var(--accent-cyan);">📊</span> Live Machine Metrics
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 10px;">
                                <div style="background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 10px 12px;">
                                    <div style="font-size: 10.5px; color: #94a3b8; text-transform: uppercase;">Runout Accuracy</div>
                                    <div style="font-size: 14px; font-weight: 800; color: #34d399; margin-top: 2px;">&lt; 0.012 mm (Laser Dial Gauge)</div>
                                </div>

                                <div style="background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 10px 12px;">
                                    <div style="font-size: 10.5px; color: #94a3b8; text-transform: uppercase;">Coolant Circulation</div>
                                    <div style="font-size: 14px; font-weight: 800; color: #38bdf8; margin-top: 2px;">25 L/min High-Flow Active</div>
                                </div>

                                <div style="background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 10px 12px;">
                                    <div style="font-size: 10.5px; color: #94a3b8; text-transform: uppercase;">Vibration Index</div>
                                    <div style="font-size: 14px; font-weight: 800; color: #facc15; margin-top: 2px;">0.003 mm (Zero Resonance)</div>
                                </div>

                                <div style="background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 10px 12px;">
                                    <div style="font-size: 10.5px; color: #94a3b8; text-transform: uppercase;">Dynamic Factory Inventory</div>
                                    <div style="font-size: 14px; font-weight: 800; color: #38bdf8; margin-top: 2px;" id="sidePanelStock">
                                        <?= ($mStock > 0) ? ($mStock . ' Units Ready in Stock') : '0 Units (Made to Order)' ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 18px; display: flex; flex-direction: column; gap: 8px;">
                            <button type="button" onclick="startRealMachineVideo()" class="btn btn-primary" style="width: 100%; font-size: 12.5px; text-align: center; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color: #060911; font-weight: 800; padding: 10px 12px;">
                                ▶ Play Live Workshop Video
                            </button>
                            <a href="<?= getWhatsAppLink(COMPANY_WHATSAPP, 'Hello Jitendrabhai Parmar, please show me a live WhatsApp video call demonstration of ' . $machine['name'] . ' (' . $machine['model_number'] . ') in Bhavnagar workshop.') ?>" target="_blank" class="btn btn-whatsapp" style="width: 100%; font-size: 12px; text-align: center; padding: 9px 12px;">
                                📹 Live Factory Video Call
                            </a>
                            <a href="<?= BASE_URL ?>/simulator-6d.php" class="btn btn-outline" style="width: 100%; font-size: 12px; text-align: center; border-color: var(--accent-cyan); color: var(--accent-cyan);">
                                🔄 Open 360° 12D Lab View
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     1. AMAZON-STYLE FREQUENTLY BOUGHT TOGETHER (વારંવાર સાથે ખરીદાતા સ્પેર પાર્ટ્સ)
     ========================================================================= -->
<?php if (!empty($fbtData['parts'])): ?>
<section class="section" style="padding: 40px 0; background: linear-gradient(180deg, rgba(6, 9, 17, 0.98) 0%, rgba(11, 17, 32, 0.95) 100%); border-top: 1px solid rgba(0, 229, 255, 0.2); border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
    <div class="container">
        <div style="background: rgba(15, 23, 42, 0.85); border: 1.5px solid rgba(0, 229, 255, 0.35); border-radius: 16px; padding: 26px 28px; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.6), 0 0 25px rgba(0, 229, 255, 0.06);">
            
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 22px;">
                <div>
                    <div style="display: inline-flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 800; color: #fbbf24; text-transform: uppercase; letter-spacing: 1px;">
                        <span>⚡ AMAZON-STYLE COMBO BUNDLE</span>
                        <span style="background: rgba(245, 158, 11, 0.2); border: 1px solid #f59e0b; padding: 2px 8px; border-radius: 9999px; font-size: 10px;">COMBO 5% SAVINGS</span>
                    </div>
                    <h2 style="font-family: 'Space Grotesk', sans-serif; font-size: 23px; font-weight: 800; color: #fff; margin: 4px 0 0;">
                        Frequently Bought Together (વારંવાર સાથે ખરીદાતી વસ્તુઓ)
                    </h2>
                    <p style="color: #94a3b8; font-size: 13.5px; margin: 4px 0 0;">
                        આ ડ્રિલ મશીન ખરીદતા ગ્રાહકો સાથે નીચે આપેલા ઓરિજિનલ સ્પેર પાર્ટ્સ અને ટૂલિંગ એક્સેસરીઝ પણ ઓર્ડર કરે છે:
                    </p>
                </div>
            </div>

            <!-- FBT Grid -->
            <div style="display: grid; grid-template-columns: 1.25fr 0.75fr; gap: 28px; align-items: center;" id="fbtGridWrapper">
                
                <!-- Left: Product Photos with Plus (+) connectors -->
                <div>
                    <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap; margin-bottom: 20px;">
                        <!-- Main Machine Thumbnail -->
                        <div style="width: 110px; height: 100px; border-radius: 10px; background: #060911; border: 2px solid var(--accent-cyan); overflow: hidden; position: relative; flex-shrink: 0;" title="<?= e($machine['name']) ?>">
                            <img src="<?= assetUrl($machine['image_url']) ?>" alt="<?= e($machine['name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <span style="position: absolute; bottom: 3px; left: 3px; font-size: 9px; font-weight: 800; background: rgba(0,0,0,0.8); color: var(--accent-cyan); padding: 1px 4px; border-radius: 3px;">MAIN</span>
                        </div>

                        <?php foreach ($fbtData['parts'] as $idx => $part): ?>
                            <span style="font-size: 22px; font-weight: 900; color: var(--accent-cyan);">+</span>
                            <div style="width: 110px; height: 100px; border-radius: 10px; background: #060911; border: 1.5px solid rgba(255,255,255,0.15); overflow: hidden; position: relative; flex-shrink: 0;" title="<?= e($part['name']) ?>" id="fbtThumb_<?= $part['id'] ?>">
                                <img src="<?= assetUrl($part['image_url']) ?>" alt="<?= e($part['name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <span style="position: absolute; bottom: 3px; left: 3px; font-size: 9px; font-weight: 800; background: rgba(0,0,0,0.8); color: #34d399; padding: 1px 4px; border-radius: 3px;">PART</span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Checkbox List -->
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <!-- Main Item Checkbox (Locked Checked) -->
                        <label style="display: flex; align-items: flex-start; gap: 10px; font-size: 13.5px; color: #cbd5e1; cursor: pointer;">
                            <input type="checkbox" id="fbtCheck_main" checked disabled style="margin-top: 3px; accent-color: #00e5ff;">
                            <div>
                                <strong style="color: #ffffff;">This Item:</strong> <?= e($machine['name']) ?> (<?= e($machine['model_number']) ?>)
                                <span style="font-family: 'Space Grotesk', sans-serif; font-weight: 800; color: #34d399; margin-left: 6px;">₹ <?= number_format($mBasePrice, 2) ?></span>
                            </div>
                        </label>

                        <!-- Parts Checkboxes -->
                        <?php foreach ($fbtData['parts'] as $part): ?>
                            <label style="display: flex; align-items: flex-start; gap: 10px; font-size: 13.5px; color: #cbd5e1; cursor: pointer;">
                                <input type="checkbox" class="fbt-part-checkbox" id="fbtCheck_<?= $part['id'] ?>" value="<?= $part['id'] ?>" data-price="<?= floatval($part['price']) ?>" checked onchange="recalculateFbtBundle()">
                                <div>
                                    <strong style="color: #38bdf8;"><?= e($part['category_name']) ?>:</strong> <?= e($part['name']) ?>
                                    <span style="font-family: 'Space Grotesk', sans-serif; font-weight: 800; color: #34d399; margin-left: 6px;">₹ <?= number_format($part['price'], 2) ?></span>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Right: Bundle Summary & 1-Click Add All to Cart -->
                <div style="background: rgba(6, 9, 17, 0.95); border: 1.5px solid rgba(0, 229, 255, 0.3); border-radius: 12px; padding: 22px;">
                    <div style="font-size: 11px; text-transform: uppercase; font-weight: 800; color: #94a3b8; letter-spacing: 0.8px;">
                        TOTAL COMBO BUNDLE PRICE:
                    </div>
                    
                    <div style="margin: 6px 0 12px;">
                        <div style="display: flex; align-items: baseline; gap: 10px;">
                            <span id="fbtBundlePriceDisplay" style="font-family: 'Space Grotesk', sans-serif; font-size: 26px; font-weight: 900; color: #34d399;">
                                ₹ <?= number_format($fbtData['bundle_price'], 2) ?>
                            </span>
                            <span id="fbtOriginalPriceDisplay" style="font-size: 14px; color: #94a3b8; text-decoration: line-through;">
                                ₹ <?= number_format($fbtData['total_original'], 2) ?>
                            </span>
                        </div>
                        <div style="font-size: 12px; color: #38bdf8; margin-top: 3px;">
                            + 18% GST • Includes Machine + <span id="fbtCheckedPartsCount"><?= count($fbtData['parts']) ?></span> Genuine Spare Parts
                        </div>
                    </div>

                    <div style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); border-radius: 8px; padding: 8px 12px; margin-bottom: 16px; font-size: 12px; color: #34d399; font-weight: 700;">
                        ✔ Save ₹ <span id="fbtSavingsDisplay"><?= number_format($fbtData['bundle_discount'], 2) ?></span> with Combo Purchase!
                    </div>

                    <button type="button" id="btnFbtAddToCart" onclick="addFbtBundleToCart(this)" class="btn btn-primary" style="width: 100%; padding: 13px; font-size: 14.5px; font-weight: 800; background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 20px rgba(16, 185, 129, 0.35);">
                        <span>🛒 Add All Selected to Cart (તમામ કાર્ટમાં ઉમેરો)</span>
                    </button>
                    
                    <div style="font-size: 11px; color: #94a3b8; text-align: center; margin-top: 10px;">
                        Instant 1-Click addition to cart with full warranty coverage.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- =========================================================================
     2. COMPATIBLE GENUINE SPARE PARTS FOR THIS MACHINE (ઓરિજિનલ સ્પેર પાર્ટ્સ)
     ========================================================================= -->
<?php if (!empty($compatParts)): ?>
<section class="section" style="padding: 45px 0 35px; border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
            <div>
                <div style="font-size: 11px; font-weight: 800; color: var(--accent-cyan); text-transform: uppercase; letter-spacing: 1px;">
                    GENUINE SVEW SPARE PARTS &amp; TOOLING
                </div>
                <h2 style="font-family: 'Space Grotesk', sans-serif; font-size: 24px; font-weight: 800; color: #fff; margin: 4px 0 0;">
                    આ મશીન સાથે વપરાતા ઓરિજિનલ સ્પેર પાર્ટ્સ (Compatible Parts)
                </h2>
                <p style="color: #94a3b8; font-size: 13.5px; margin: 4px 0 0;">
                    હાઈ-ગ્રેડ એલોય સ્ટીલ ગિયર્સ, મોર્સ ટેપર સ્પિન્ડલ, કી-લેસ ચક અને કૂલન્ટ પંપ કીટ - ફેક્ટરી ભાવે સીધા ઉપલબ્ધ.
                </p>
            </div>
            <a href="<?= BASE_URL ?>/catalog.php" style="color: #38bdf8; font-size: 13px; font-weight: 700; text-decoration: none;">
                View Spare Parts Catalog →
            </a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px;">
            <?php foreach ($compatParts as $cp): ?>
                <div style="background: rgba(11, 17, 32, 0.9); border: 1px solid rgba(0, 229, 255, 0.2); border-radius: 12px; padding: 16px; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.25s;" onmouseover="this.style.borderColor='var(--accent-cyan)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='rgba(0, 229, 255, 0.2)'; this.style.transform='none';">
                    <div>
                        <div style="height: 150px; border-radius: 8px; overflow: hidden; background: #060911; margin-bottom: 12px; position: relative;">
                            <a href="<?= BASE_URL ?>/machine-detail.php?id=<?= $cp['id'] ?>">
                                <img src="<?= assetUrl($cp['image_url']) ?>" alt="<?= e($cp['name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            </a>
                            <span style="position: absolute; top: 8px; left: 8px; background: rgba(0, 229, 255, 0.2); border: 1px solid var(--accent-cyan); color: var(--accent-cyan); font-size: 10px; font-weight: 800; padding: 2px 7px; border-radius: 4px;">
                                <?= e($cp['model_number']) ?>
                            </span>
                        </div>
                        <div style="font-size: 10.5px; font-weight: 700; color: #38bdf8; text-transform: uppercase;">
                            <?= e($cp['category_name']) ?>
                        </div>
                        <a href="<?= BASE_URL ?>/machine-detail.php?id=<?= $cp['id'] ?>" style="font-family: 'Space Grotesk', sans-serif; font-size: 15px; font-weight: 700; color: #ffffff; text-decoration: none; display: block; margin: 4px 0 8px; line-height: 1.35;">
                            <?= e($cp['name']) ?>
                        </a>
                        <p style="font-size: 12px; color: #94a3b8; line-height: 1.4; margin-bottom: 12px;">
                            <?= e($cp['short_desc']) ?>
                        </p>
                    </div>

                    <div>
                        <div style="display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 12px; background: rgba(6, 9, 17, 0.7); padding: 8px 10px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.06);">
                            <span style="font-size: 11px; color: #94a3b8;">Factory Price:</span>
                            <span style="font-family: 'Space Grotesk', sans-serif; font-size: 17px; font-weight: 900; color: #34d399;">
                                ₹ <?= number_format($cp['price'], 2) ?>
                            </span>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                            <button type="button" class="btn btn-outline" onclick="window.addToCart(<?= $cp['id'] ?>, 1, this)" style="padding: 8px; font-size: 11.5px; font-weight: 700; border-color: #f59e0b; color: #fbbf24; background: rgba(245, 158, 11, 0.1);">
                                🛒 Add to Cart
                            </button>
                            <a href="<?= BASE_URL ?>/checkout.php?product_id=<?= $cp['id'] ?>" class="btn btn-primary" style="padding: 8px; font-size: 11.5px; font-weight: 700; text-align: center; background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399;">
                                💳 Buy Now
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- =========================================================================
     3. SMART RECOMMENDATIONS IN THIS CATEGORY & PRICE BRACKET
     ========================================================================= -->
<?php if (!empty($smartRecs)): ?>
<section class="section" style="padding: 45px 0 55px; background: radial-gradient(circle at 50% 100%, rgba(0, 229, 255, 0.05) 0%, rgba(6, 9, 17, 0.98) 70%);">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
            <div>
                <div style="font-size: 11px; font-weight: 800; color: #34d399; text-transform: uppercase; letter-spacing: 1px;">
                    PRODUCT &amp; PRICE RECOMMENDATIONS
                </div>
                <h2 style="font-family: 'Space Grotesk', sans-serif; font-size: 24px; font-weight: 800; color: #fff; margin: 4px 0 0;">
                    આ બજેટ અને કેપેસિટીમાં અન્ય ભલામણ કરેલ ડ્રિલ મશીનો
                </h2>
                <p style="color: #94a3b8; font-size: 13.5px; margin: 4px 0 0;">
                    આ મશીનની સમકક્ષ ક્ષમતા અને કિંમતમાં અન્ય ઔદ્યોગિક મોડલ્સ:
                </p>
            </div>
            <a href="<?= BASE_URL ?>/machines.php" style="color: #38bdf8; font-size: 13px; font-weight: 700; text-decoration: none;">
                View All Range →
            </a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px;">
            <?php foreach ($smartRecs as $srec): ?>
                <div style="background: rgba(11, 17, 32, 0.9); border: 1px solid rgba(0, 229, 255, 0.2); border-radius: 12px; padding: 16px; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.25s;" onmouseover="this.style.borderColor='var(--accent-cyan)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='rgba(0, 229, 255, 0.2)'; this.style.transform='none';">
                    <div>
                        <div style="height: 160px; border-radius: 8px; overflow: hidden; background: #060911; margin-bottom: 12px; position: relative;">
                            <a href="<?= BASE_URL ?>/machine-detail.php?id=<?= $srec['id'] ?>">
                                <img src="<?= assetUrl($srec['image_url']) ?>" alt="<?= e($srec['name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            </a>
                            <span style="position: absolute; top: 8px; right: 8px; background: rgba(6, 9, 17, 0.85); border: 1px solid var(--accent-cyan); color: var(--accent-cyan); font-size: 10.5px; font-weight: 800; padding: 2px 7px; border-radius: 4px;">
                                <?= e($srec['model_number']) ?>
                            </span>
                        </div>
                        <div style="font-size: 10.5px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">
                            <?= e($srec['category_name']) ?>
                        </div>
                        <a href="<?= BASE_URL ?>/machine-detail.php?id=<?= $srec['id'] ?>" style="font-family: 'Space Grotesk', sans-serif; font-size: 16px; font-weight: 700; color: #ffffff; text-decoration: none; display: block; margin: 4px 0 6px; line-height: 1.3;">
                            <?= e($srec['name']) ?>
                        </a>
                        <div style="font-size: 12px; color: #cbd5e1; margin-bottom: 10px;">
                            Capacity: <strong style="color: #38bdf8;"><?= e($srec['drilling_capacity']) ?></strong>
                        </div>
                    </div>

                    <div>
                        <div style="font-family: 'Space Grotesk', sans-serif; font-size: 18px; font-weight: 900; color: #34d399; margin-bottom: 12px;">
                            ₹ <?= number_format($srec['price'], 2) ?>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                            <button type="button" class="btn btn-outline" onclick="window.addToCart(<?= $srec['id'] ?>, 1, this)" style="padding: 8px; font-size: 11.5px; font-weight: 700; border-color: #f59e0b; color: #fbbf24; background: rgba(245, 158, 11, 0.1);">
                                🛒 Add to Cart
                            </button>
                            <a href="<?= BASE_URL ?>/machine-detail.php?id=<?= $srec['id'] ?>" class="btn btn-primary" style="padding: 8px; font-size: 11.5px; font-weight: 700; text-align: center; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color: #060911;">
                                View Specs
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
// Multi-Angle Real Image Switcher
const galleryAngles = <?= json_encode($galleryImages) ?>;

function switchGalleryAngle(index) {
    if (!galleryAngles[index]) return;
    const item = galleryAngles[index];
    const mainImg = document.getElementById('mainProductImg');
    const titleEl = document.getElementById('galleryCaptionTitle');
    const descEl = document.getElementById('galleryCaptionDesc');

    if (mainImg) {
        mainImg.style.opacity = '0.3';
        mainImg.style.transform = 'scale(0.97)';
        setTimeout(() => {
            mainImg.src = item.url;
            mainImg.style.opacity = '1';
            mainImg.style.transform = 'scale(1)';
        }, 120);
    }

    if (titleEl) titleEl.textContent = item.title;
    if (descEl) descEl.textContent = item.desc;

    document.querySelectorAll('.gallery-thumb-btn').forEach((btn, i) => {
        if (i === index) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
}

// Start Real Machine Video Demonstration Function
function startRealMachineVideo() {
    const iframe = document.getElementById('mainMachineIframe');
    const poster = document.getElementById('videoPosterOverlay');
    if (iframe && poster) {
        const targetSrc = iframe.getAttribute('data-src');
        if (!iframe.src || iframe.src === 'about:blank' || iframe.src !== targetSrc) {
            iframe.src = targetSrc;
        }
        poster.style.display = 'none';
        iframe.style.display = 'block';
    }
}

// Dynamic Quantity Stepper & Price Calculation
const unitBasePrice = <?= json_encode($mBasePrice) ?>;
const availableStock = <?= json_encode($mStock) ?>;
const machineId = <?= json_encode($machine['id']) ?>;

function adjustDetailQty(delta) {
    const input = document.getElementById('detailQtyInput');
    if (!input) return;
    let val = parseInt(input.value) || 1;
    val += delta;
    if (val < 1) val = 1;
    if (availableStock > 0 && val > availableStock) val = availableStock;
    input.value = val;
    onDetailQtyChange();
}

function onDetailQtyChange() {
    const input = document.getElementById('detailQtyInput');
    if (!input) return;
    let qty = parseInt(input.value) || 1;
    if (qty < 1) qty = 1;
    if (availableStock > 0 && qty > availableStock) qty = availableStock;
    input.value = qty;

    const lblQty = document.getElementById('lblSelectedQty');
    const lblTotal = document.getElementById('lblCalculatedTotal');
    const btnBook = document.getElementById('btnBookOnlineTop');

    if (lblQty) lblQty.textContent = qty + (qty > 1 ? ' Units' : ' Unit');
    const totalAmount = unitBasePrice * qty * 1.18;
    if (lblTotal) lblTotal.textContent = '₹ ' + totalAmount.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    if (btnBook) {
        btnBook.href = '<?= BASE_URL ?>/checkout.php?machine_id=' + machineId + '&qty=' + qty;
    }
}

// Amazon-Style Frequently Bought Together Handlers
function recalculateFbtBundle() {
    let originalTotal = unitBasePrice;
    let checkedCount = 0;

    document.querySelectorAll('.fbt-part-checkbox').forEach(cb => {
        const partPrice = parseFloat(cb.getAttribute('data-price')) || 0;
        const thumb = document.getElementById('fbtThumb_' + cb.value);
        if (cb.checked) {
            checkedCount++;
            originalTotal += partPrice;
            if (thumb) thumb.style.opacity = '1';
        } else {
            if (thumb) thumb.style.opacity = '0.35';
        }
    });

    const comboSavings = Math.round((originalTotal * 0.05) / 10) * 10;
    const bundlePrice = originalTotal - comboSavings;

    const priceEl = document.getElementById('fbtBundlePriceDisplay');
    const origEl = document.getElementById('fbtOriginalPriceDisplay');
    const saveEl = document.getElementById('fbtSavingsDisplay');
    const countEl = document.getElementById('fbtCheckedPartsCount');

    if (priceEl) priceEl.textContent = '₹ ' + bundlePrice.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    if (origEl) origEl.textContent = '₹ ' + originalTotal.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    if (saveEl) saveEl.textContent = comboSavings.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    if (countEl) countEl.textContent = checkedCount;
}

function addFbtBundleToCart(btn) {
    const productIds = [machineId];
    document.querySelectorAll('.fbt-part-checkbox:checked').forEach(cb => {
        productIds.push(parseInt(cb.value));
    });

    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span>⏳ Adding ' + productIds.length + ' Items to Cart...</span>';

    const formData = new FormData();
    formData.append('action', 'add');
    productIds.forEach(id => formData.append('product_ids[]', id));

    fetch('<?= BASE_URL ?>/api/cart.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('.cart-badge-count').forEach(el => {
                el.textContent = data.cart_count;
                el.style.display = data.cart_count > 0 ? 'inline-flex' : 'none';
            });
            if (typeof showVarshaCartToast === 'function') {
                showVarshaCartToast(productIds.length + ' Combo Bundle Items Added');
            }
            btn.innerHTML = '<span>✔ All ' + productIds.length + ' Items Added to Cart!</span>';
            btn.style.background = '#059669';
            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                btn.style.background = '';
            }, 2500);
        } else {
            alert(data.message || 'Error adding combo bundle.');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    })
    .catch(err => {
        console.error(err);
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
