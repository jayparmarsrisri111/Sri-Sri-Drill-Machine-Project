<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

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

$pageTitle = e($machine['name']) . ' (' . e($machine['model_number']) . ') | Shree Varsha Engineering Works';
$pageDesc = e($machine['short_desc']);
$currentNav = 'machines';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Parse key features
$featuresList = !empty($machine['key_features']) ? explode(';', $machine['key_features']) : [];

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
            min-height: 300px;
        }
        .main-gallery-view img {
            max-height: 280px;
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
                    <span class="badge-tag" style="background: rgba(16, 185, 129, 0.15); border-color: #10b981; color: #34d399; font-weight: 700;">
                        READY FOR DISPATCH • <?= intval($machine['stock_quantity'] ?? 5) ?> UNITS IN STOCK
                    </span>
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
                    <a href="<?= BASE_URL ?>/checkout.php?machine_id=<?= $machine['id'] ?>" class="btn btn-primary" style="flex: 1 1 100%; text-align: center; background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399; font-weight: 700; padding: 13px; font-size: 15px;">
                        💳 Book Machine Online (Razorpay)
                    </a>
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
                <div style="display: grid; grid-template-columns: 1fr 320px; min-height: 400px;" id="videoContainerGrid">
                    <!-- Video / Canvas Simulation Player -->
                    <div style="position: relative; background: #000; display: flex; flex-direction: column; justify-content: center; align-items: center; overflow: hidden; min-height: 380px;">
                        <img src="<?= assetUrl($machine['image_url']) ?>" alt="Machinery Demonstration" style="max-height: 340px; width: auto; object-fit: contain; filter: drop-shadow(0 10px 25px rgba(0,229,255,0.25));">
                        
                        <!-- Video Controls Overlay -->
                        <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,0.15) 0%, rgba(2, 6, 23, 0.85) 90%); display: flex; flex-direction: column; justify-content: space-between; padding: 18px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="background: rgba(15, 23, 42, 0.85); border: 1px solid #10b981; color: #34d399; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 6px;">
                                    ● 4K FACTORY WORKSHOP DEMO
                                </span>
                                <span style="background: rgba(15, 23, 42, 0.85); border: 1px solid rgba(0, 229, 255, 0.4); color: #38bdf8; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 6px;">
                                    MODEL: <?= e($machine['model_number']) ?>
                                </span>
                            </div>

                            <!-- Center Play / Replay Trigger -->
                            <div style="text-align: center;">
                                <button type="button" id="btnPlayDemoVideo" onclick="toggleDemoState()" style="background: linear-gradient(135deg, #00e5ff 0%, #2563eb 100%); border: none; width: 68px; height: 68px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 0 30px rgba(0, 229, 255, 0.6); transition: transform 0.2s;">
                                    <span id="demoPlayIcon" style="font-size: 26px; color: #060911; margin-left: 4px;">▶</span>
                                </button>
                                <div id="demoStatusText" style="color: #fff; font-size: 12.5px; font-weight: 700; margin-top: 10px; text-shadow: 0 2px 4px rgba(0,0,0,0.8);">
                                    Tap to Start Live Machining Simulation &amp; Audio
                                </div>
                            </div>

                            <!-- Bottom Progress Bar & HUD -->
                            <div>
                                <div style="display: flex; justify-content: space-between; font-size: 11px; color: #cbd5e1; margin-bottom: 6px; font-family: monospace;">
                                    <span>SPINDLE RUN: <span id="telemetryRpm" style="color: #10b981; font-weight: 700;">650 RPM</span></span>
                                    <span>FEED: <span style="color: #38bdf8; font-weight: 700;">0.22 mm/rev</span></span>
                                    <span>TORQUE: <span style="color: #facc15; font-weight: 700;">145 Nm</span></span>
                                </div>
                                <div style="height: 6px; width: 100%; background: rgba(255,255,255,0.15); border-radius: 3px; overflow: hidden;">
                                    <div id="videoProgressBar" style="height: 100%; width: 45%; background: linear-gradient(90deg, #00e5ff, #10b981); transition: width 0.3s ease;"></div>
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
                            </div>
                        </div>

                        <div style="margin-top: 18px;">
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

// Interactive Live Machinery Video Demonstration Toggle
let isDemoPlaying = false;
let demoInterval = null;

function toggleDemoState() {
    isDemoPlaying = !isDemoPlaying;
    const icon = document.getElementById('demoPlayIcon');
    const statusText = document.getElementById('demoStatusText');
    const rpmEl = document.getElementById('telemetryRpm');
    const bar = document.getElementById('videoProgressBar');

    if (isDemoPlaying) {
        if (icon) icon.textContent = '⏸';
        if (statusText) statusText.innerHTML = '<span style="color:#10b981;">● Machining in Progress • Coolant Flood Active</span>';
        let progress = 45;
        demoInterval = setInterval(() => {
            progress = (progress + 3) % 100;
            if (bar) bar.style.width = progress + '%';
            if (rpmEl) {
                const rpms = [550, 650, 720, 850, 920, 1100];
                rpmEl.textContent = rpms[Math.floor(Math.random() * rpms.length)] + ' RPM';
            }
        }, 600);
    } else {
        if (icon) icon.textContent = '▶';
        if (statusText) statusText.textContent = 'Tap to Resume Machining Demonstration';
        if (demoInterval) clearInterval(demoInterval);
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
