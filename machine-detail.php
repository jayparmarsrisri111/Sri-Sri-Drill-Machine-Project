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

$pageTitle = e($machine['name']) . ' (' . e($machine['model_number']) . ') | Shree Varsha Engineering Works';
$pageDesc = e($machine['short_desc']);
$currentNav = 'machines';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Parse key features
$featuresList = !empty($machine['key_features']) ? explode(';', $machine['key_features']) : [];
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
        grid-template-columns: 1fr 1fr;
        gap: 45px;
        align-items: flex-start;
    }
    .machine-visual-box {
        background: var(--bg-card);
        border: 1px solid var(--border-cyan);
        border-radius: var(--radius-lg);
        padding: 35px 25px;
        text-align: center;
        box-shadow: var(--shadow-card);
        position: sticky;
        top: 95px;
    }
    @media (max-width: 992px) {
        .machine-detail-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }
        .machine-visual-box {
            position: static;
        }
    }
</style>

<!-- Machine Detail Overview -->
<section class="section">
    <div class="container">
        <div class="machine-detail-grid">
            <!-- Left: Machine Visual Showcase -->
            <div class="machine-visual-box">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <span class="pill-badge"><?= e($machine['category_name']) ?></span>
                    <span class="badge-tag" style="background: rgba(16, 185, 129, 0.15); border-color: #10b981; color: #34d399; font-weight: 700;">
                        READY FOR DISPATCH • <?= intval($machine['stock_quantity'] ?? 5) ?> UNITS IN STOCK
                    </span>
                </div>
                <img src="<?= assetUrl($machine['image_url']) ?>" alt="<?= e($machine['name']) ?>" style="max-height: 420px; width: 100%; object-fit: contain; margin: 0 auto; filter: drop-shadow(0 15px 30px rgba(0,0,0,0.6));">
                
                <div style="margin-top: 26px; display: flex; gap: 12px; flex-wrap: wrap;">
                    <button type="button" class="btn-360-turntable open-360-modal" data-machine-name="<?= e($machine['name']) ?> (<?= e($machine['model_number']) ?>)" data-machine-img="<?= assetUrl($machine['image_url']) ?>" style="flex: 1 1 100%; height: 44px; font-size: 13.5px;">
                        <span class="turntable-spin-icon">🔄</span>
                        <span>360° Interactive 12D Machine View</span>
                        <span class="turntable-step-pill">30° STEP</span>
                    </button>
                    <a href="<?= BASE_URL ?>/checkout.php?machine_id=<?= $machine['id'] ?>" class="btn btn-primary" style="flex: 1 1 100%; text-align: center; background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399; font-weight: 700; padding: 14px; font-size: 15px;">
                        💳 Book Machine Online (Razorpay)
                    </a>
                    <button type="button" class="btn btn-outline open-quote-modal" 
                            style="flex: 1 1 180px;"
                            data-machine-id="<?= $machine['id'] ?>"
                            data-machine-name="<?= e($machine['name']) ?> (<?= e($machine['model_number']) ?>)">
                        ⚡ Factory Quotation
                    </button>
                    <a href="<?= getWhatsAppLink(COMPANY_WHATSAPP, 'Hello ' . APP_NAME . ', I am looking for details on ' . $machine['name'] . ' (' . $machine['model_number'] . '). Please share price and catalog.') ?>" target="_blank" class="btn btn-whatsapp" style="flex: 1 1 180px;">
                        WhatsApp Inquiry
                    </a>
                </div>
            </div>

            <!-- Right: Technical Details & Specifications -->
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                    <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand" style="height: 32px; width: 32px; border-radius: 50%; border: 1.5px solid var(--accent-cyan); object-fit: cover;">
                    <span style="font-family: var(--font-heading); font-size: 13px; color: var(--accent-cyan); font-weight: 700; letter-spacing: 1px; text-transform: uppercase;">
                        VARSHA BRAND • MODEL: <?= e($machine['model_number']) ?>
                    </span>
                </div>
                <h1 style="font-family: var(--font-heading); font-size: 32px; font-weight: 800; color: #fff; margin: 8px 0 16px; line-height: 1.25;">
                    <?= e($machine['name']) ?>
                </h1>

                <!-- Prominent Price Breakdown Banner -->
                <div style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(6, 10, 20, 0.98) 100%); border: 1.5px solid var(--accent-cyan); border-radius: 12px; padding: 18px 22px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; box-shadow: 0 10px 25px rgba(0, 229, 255, 0.15);">
                    <div>
                        <div style="font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 700; letter-spacing: 0.8px;">Direct Factory Ex-Price</div>
                        <div style="font-family: 'Space Grotesk', sans-serif; font-size: 28px; font-weight: 900; color: #34d399; line-height: 1.1; margin-top: 2px;">
                            ₹ <?= number_format($machine['price'] ?? getMachinePrice($machine['id']), 2) ?>
                        </div>
                        <div style="font-size: 11.5px; color: #38bdf8; margin-top: 4px;">
                            + 18% GST (CGST 9% + SGST 9%) • HSN Code 8459 29 10
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
                
                <p style="font-size: 15.5px; color: var(--text-secondary); line-height: 1.7; margin-bottom: 24px;">
                    <?= e($machine['full_desc'] ?? $machine['short_desc']) ?>
                </p>

                <!-- Key Advantages / Features -->
                <?php if (!empty($featuresList)): ?>
                <div style="background: rgba(11, 17, 32, 0.6); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 20px; margin-bottom: 30px;">
                    <h3 style="font-family: var(--font-heading); font-size: 16px; font-weight: 700; color: #fff; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                        <span style="color: var(--accent-cyan);">✦</span> Key Engineering Highlights
                    </h3>
                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 8px;">
                        <?php foreach ($featuresList as $feat): ?>
                            <?php if (trim($feat)): ?>
                            <li style="display: flex; align-items: flex-start; gap: 8px; font-size: 14px; color: #cbd5e1;">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="var(--accent-cyan)" style="flex-shrink: 0; margin-top: 3px;"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                <span><?= e(trim($feat)) ?></span>
                            </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Complete Specifications Table -->
                <h3 style="font-family: var(--font-heading); font-size: 18px; font-weight: 700; color: #fff; margin-bottom: 14px;">
                    Complete Technical Specifications
                </h3>

                <div class="table-responsive" style="margin-bottom: 30px;">
                    <table class="tech-table">
                        <tbody>
                            <tr>
                                <th style="width: 40%;">Parameter</th>
                                <th>Specification Value</th>
                            </tr>
                            <tr>
                                <td><strong>Drilling Capacity</strong></td>
                                <td style="color: var(--accent-cyan); font-weight: 700;"><?= e($machine['drilling_capacity']) ?></td>
                            </tr>
                            <?php if (!empty($machine['spindle_speeds'])): ?>
                            <tr>
                                <td>Spindle Speeds & RPM</td>
                                <td><?= e($machine['spindle_speeds']) ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if (!empty($machine['spindle_travel'])): ?>
                            <tr>
                                <td>Spindle Quill Travel</td>
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
                                <td><?= e($machine['motor_power']) ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if (!empty($machine['working_table_size'])): ?>
                            <tr>
                                <td>Working Table Size</td>
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
                        </tbody>
                    </table>
                </div>

                <!-- Direct Inquiry Form on Detail Page -->
                <div class="inquiry-form-card" style="margin-top: 30px;">
                    <h3 style="font-family: var(--font-heading); font-size: 18px; color: #fff; margin-bottom: 6px;">
                        Quick Quotation for <?= e($machine['model_number']) ?>
                    </h3>
                    <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 18px;">
                        Enter your contact details to receive direct factory pricing and delivery timeline.
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

                        <div style="margin-bottom: 16px;">
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
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
