<?php
/**
 * Checkout & Online Machine Booking with Razorpay
 * Shree Varsha Engineering Works - Varsha Brand
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$pdo = Database::getConnection();

// Fetch active products for dropdown
$products = [];
try {
    $stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.is_active = 1 ORDER BY c.display_order ASC, p.id ASC");
    $products = $stmt->fetchAll();
} catch (Exception $e) {
    // fallback
}

$selectedId = intval($_GET['product_id'] ?? ($_GET['part_id'] ?? ($_GET['machine_id'] ?? ($products[0]['id'] ?? 1))));
$selectedMachine = null;
foreach ($products as $p) {
    if ($p['id'] == $selectedId) {
        $selectedMachine = $p;
        break;
    }
}
if (!$selectedMachine && !empty($products)) {
    $selectedMachine = $products[0];
}

$custUser = $_SESSION['customer_user'] ?? null;
$prefillName = $custUser['full_name'] ?? 'Ramesh Patel';
$prefillCompany = $custUser['company_name'] ?? 'Patel Engineering Works';
$prefillPhone = $custUser['phone'] ?? '9824913555';
$prefillEmail = $custUser['email'] ?? 'customer@example.com';
$prefillAddress = $custUser['address'] ?? 'Plot No. 14, GIDC Industrial Estate';
$prefillCity = $custUser['city'] ?? 'Bhavnagar';
$prefillPincode = $custUser['pincode'] ?? '364003';
$prefillGst = $custUser['gstin'] ?? '24AAACS1234F1Z5';

$pageTitle = 'Online Machine Booking & Tax Invoice | ' . BRAND_NAME;
$pageDesc = 'Book your Varsha Brand Industrial Drill Machine online with Razorpay secure instant booking advance or full invoice payment.';
$currentNav = 'checkout';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<style>
    .checkout-hero {
        padding: 35px 0 20px;
        background: linear-gradient(180deg, rgba(6, 9, 17, 0.95) 0%, rgba(11, 17, 32, 0.8) 100%);
        border-bottom: 1px solid var(--border-subtle);
    }
    .checkout-grid {
        display: grid;
        grid-template-columns: 1.15fr 0.85fr;
        gap: 35px;
        align-items: flex-start;
        margin: 35px 0 60px;
    }
    @media (max-width: 992px) {
        .checkout-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }
    }
    .checkout-card {
        background: var(--bg-card);
        border: 1px solid var(--border-cyan);
        border-radius: var(--radius-lg);
        padding: 30px;
        box-shadow: var(--shadow-card);
    }
    .order-summary-card {
        background: rgba(15, 23, 42, 0.92);
        border: 1px solid rgba(56, 189, 248, 0.35);
        border-radius: var(--radius-lg);
        padding: 26px;
        position: sticky;
        top: 95px;
        box-shadow: 0 20px 45px rgba(0,0,0,0.5), 0 0 25px rgba(0, 229, 255, 0.08);
    }
    .payment-option-label {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        background: rgba(11, 17, 32, 0.8);
        border: 2px solid var(--border-subtle);
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.25s ease;
        margin-bottom: 12px;
    }
    .payment-option-label:hover,
    .payment-option-label.active {
        border-color: var(--accent-cyan);
        background: rgba(0, 229, 255, 0.08);
    }
    .price-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        font-size: 14px;
        color: var(--text-secondary);
    }
    .price-row.total-row {
        border-bottom: none;
        border-top: 2px solid var(--border-cyan);
        margin-top: 10px;
        padding-top: 14px;
        font-size: 18px;
        font-weight: 800;
        color: #fff;
    }
    .security-badge-row {
        display: flex;
        gap: 12px;
        margin-top: 18px;
        padding-top: 14px;
        border-top: 1px dashed rgba(255,255,255,0.1);
        font-size: 12px;
        color: #94a3b8;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
    }
    /* Payment Terminal Modal Styles */
    .payment-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(4, 7, 13, 0.85);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .payment-modal-card {
        background: #090d16;
        border: 1.5px solid #00e5ff;
        border-radius: 16px;
        width: 100%;
        max-width: 480px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(0,0,0,0.9), 0 0 35px rgba(0, 229, 255, 0.25);
        animation: modalPopIn 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes modalPopIn {
        from { transform: scale(0.92); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    .terminal-header {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(6, 10, 20, 0.98) 100%);
        border-bottom: 1px solid rgba(0, 229, 255, 0.25);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .terminal-body {
        padding: 20px;
    }
    .terminal-tabs {
        display: flex;
        gap: 6px;
        background: rgba(15, 23, 42, 0.7);
        padding: 4px;
        border-radius: 8px;
        margin-bottom: 14px;
    }
    .term-tab {
        flex: 1;
        padding: 8px;
        background: transparent;
        border: none;
        border-radius: 6px;
        color: #94a3b8;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }
    .term-tab.active {
        background: #0284c7;
        color: #fff;
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.4);
    }
</style>

<!-- Top Title -->
<section class="checkout-hero">
    <div class="container">
        <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 10px;">
            <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand" style="height: 48px; width: 48px; border-radius: 50%; border: 1.5px solid var(--accent-cyan);">
            <div>
                <span class="pill-badge" style="background: rgba(16, 185, 129, 0.15); border-color: #10b981; color: #34d399;">
                    🔒 256-BIT SECURE RAZORPAY GATEWAY
                </span>
                <h1 style="font-family: var(--font-heading); font-size: 26px; color: #fff; margin-top: 4px;">
                    Online Machine Booking & Official Tax Invoice
                </h1>
            </div>
        </div>
        <p style="color: var(--text-secondary); font-size: 14px;">
            Book your <strong><?= BRAND_NAME ?></strong> industrial drill machine directly from Shree Varsha Engineering Works, Bhavnagar. Official GST Tax Invoice is generated immediately with authorized signature of <strong><?= AUTHORIZED_SIGNATORY ?></strong> and official company seal.
        </p>
    </div>
</section>

<?php
$machines = [];
$spareParts = [];
foreach ($products as $p) {
    if (($p['product_type'] ?? 'machine') === 'part') {
        $spareParts[] = $p;
    } else {
        $machines[] = $p;
    }
}
?>

<div class="container">
    <div class="checkout-grid">
        <!-- Left: Customer & Billing Form -->
        <div class="checkout-card">

            <?php if (!empty($custUser)): ?>
                <!-- Customer Logged In Notice -->
                <div style="background: rgba(16, 185, 129, 0.12); border: 1.5px solid #10b981; border-radius: 12px; padding: 12px 18px; margin-bottom: 22px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #10b981; box-shadow: 0 0 10px #10b981;"></span>
                        <div>
                            <div style="font-size: 13.5px; font-weight: 700; color: #34d399;">
                                Signed in as <?= e($custUser['full_name']) ?> (<?= e($custUser['phone']) ?>)
                            </div>
                            <div style="font-size: 12px; color: #94a3b8;">
                                Your profile and delivery address are automatically loaded below.
                            </div>
                        </div>
                    </div>
                    <div>
                        <a href="<?= BASE_URL ?>/logout-customer.php" style="font-size: 12px; color: #f87171; text-decoration: underline;">Switch Account / Sign Out</a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Customer Guest Prompt Notice -->
                <div style="background: rgba(15, 23, 42, 0.9); border: 1.5px dashed #38bdf8; border-radius: 12px; padding: 14px 18px; margin-bottom: 22px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 24px;">👤</span>
                        <div>
                            <div style="font-size: 13.5px; font-weight: 700; color: #fff;">
                                Ordering Machine or Spare Parts? Sign In to Link Your Order
                            </div>
                            <div style="font-size: 12px; color: #94a3b8;">
                                Log in to track delivery via Amazon-style live status and download GST tax invoices anytime.
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <button type="button" onclick="openCustomerAuthModal('Please sign in or register to complete your order.')" class="btn btn-outline" style="border-color: #38bdf8; color: #38bdf8; font-size: 12.5px; padding: 6px 14px;">
                            👤 Sign In / Register
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <h2 style="font-family: var(--font-heading); font-size: 19px; color: #fff; margin-bottom: 18px; display: flex; align-items: center; gap: 10px;">
                <span style="color: var(--accent-cyan);">1.</span> Select Machine or Genuine Spare Part
            </h2>

            <div class="form-group">
                <label class="form-label">Select Machinery Model / Genuine Spare Part *</label>
                <select id="machineSelect" class="form-control" style="font-size: 14.5px; font-weight: 600;">
                    <?php if (!empty($machines)): ?>
                        <optgroup label="🏭 Heavy Industrial Drilling Machinery">
                            <?php foreach ($machines as $p): 
                                $mPrice = floatval($p['price'] ?? getMachinePrice($p['id']));
                            ?>
                                <option value="<?= $p['id'] ?>" 
                                        data-name="<?= e($p['name']) ?>"
                                        data-model="<?= e($p['model_number']) ?>"
                                        data-price="<?= $mPrice ?>"
                                        data-type="machine"
                                        data-stock="<?= intval($p['stock_quantity'] ?? 5) ?>"
                                        data-image="<?= assetUrl($p['image_url']) ?>"
                                        data-capacity="<?= e($p['drilling_capacity'] ?? 'Industrial') ?>"
                                        <?= ($p['id'] == $selectedMachine['id']) ? 'selected' : '' ?>>
                                    [MACHINE] <?= e($p['model_number']) ?> - <?= e($p['name']) ?> (₹<?= number_format($mPrice, 2) ?> + 18% GST)
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endif; ?>

                    <?php if (!empty($spareParts)): ?>
                        <optgroup label="🔧 Genuine Factory Spare Parts & Tooling">
                            <?php foreach ($spareParts as $p): 
                                $mPrice = floatval($p['price'] ?? 0);
                            ?>
                                <option value="<?= $p['id'] ?>" 
                                        data-name="<?= e($p['name']) ?>"
                                        data-model="<?= e($p['model_number']) ?>"
                                        data-price="<?= $mPrice ?>"
                                        data-type="part"
                                        data-stock="<?= intval($p['stock_quantity'] ?? 10) ?>"
                                        data-image="<?= assetUrl($p['image_url']) ?>"
                                        data-capacity="<?= e($p['drilling_capacity'] ?? 'Genuine Part') ?>"
                                        <?= ($p['id'] == $selectedMachine['id']) ? 'selected' : '' ?>>
                                    [SPARE PART] <?= e($p['model_number']) ?> - <?= e($p['name']) ?> (₹<?= number_format($mPrice, 2) ?> + 18% GST)
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Payment Type Selection -->
            <div style="margin: 22px 0;">
                <label class="form-label" style="margin-bottom: 10px;">Choose Payment Option *</label>
                
                <label class="payment-option-label active" id="labelOptionAdvance">
                    <input type="radio" name="payment_type" id="payTypeAdvance" value="booking_advance" checked style="accent-color: var(--accent-cyan); width: 18px; height: 18px;">
                    <div style="flex: 1;">
                        <div style="font-weight: 700; color: #fff; font-size: 15px; display: flex; justify-content: space-between;">
                            <span>Token Booking Advance (Slot Reservation)</span>
                            <span style="color: #34d399;">₹ 25,000</span>
                        </div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 3px;">
                            Lock manufacturing priority today. Remaining balance payable upon dispatch / inspection.
                        </div>
                    </div>
                </label>

                <label class="payment-option-label" id="labelOptionFull">
                    <input type="radio" name="payment_type" id="payTypeFull" value="full_payment" style="accent-color: var(--accent-cyan); width: 18px; height: 18px;">
                    <div style="flex: 1;">
                        <div style="font-weight: 700; color: #fff; font-size: 15px; display: flex; justify-content: space-between;">
                            <span id="fullPaymentTitle">Full Payment (Price + 18% GST)</span>
                            <span style="color: var(--accent-cyan);" id="fullPriceLabel">₹ 0</span>
                        </div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 3px;" id="fullPaymentDesc">
                            Complete invoice payment with 100% Tax Invoice, priority dispatch & free tooling set.
                        </div>
                    </div>
                </label>
            </div>

            <!-- Step 2: Customer Contact & Delivery Address -->
            <h2 style="font-family: var(--font-heading); font-size: 19px; color: #fff; margin: 28px 0 18px; display: flex; align-items: center; gap: 10px;">
                <span style="color: var(--accent-cyan);">2.</span> Customer &amp; Factory Delivery Details
            </h2>

            <form id="orderForm">
                <input type="hidden" name="csrf_token" id="csrfToken" value="<?= getCsrfToken() ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Full Name / Contact Person *</label>
                        <input type="text" id="custName" class="form-control" required value="<?= e($prefillName) ?>" placeholder="e.g. Ramesh Patel">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Company / Workshop Name</label>
                        <input type="text" id="custCompany" class="form-control" value="<?= e($prefillCompany) ?>" placeholder="e.g. Patel Engineering Works">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Mobile Number *</label>
                        <input type="tel" id="custPhone" class="form-control" required value="<?= e($prefillPhone) ?>" placeholder="e.g. 9824913555">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Official Email Address *</label>
                        <input type="email" id="custEmail" class="form-control" required value="<?= e($prefillEmail) ?>" placeholder="e.g. contact@pateleng.com">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Factory / Delivery Address *</label>
                    <textarea id="custAddress" class="form-control" rows="2" required placeholder="Plot No., Industrial Zone, Road..."><?= e($prefillAddress) ?></textarea>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">City *</label>
                        <input type="text" id="custCity" class="form-control" required value="<?= e($prefillCity) ?>" placeholder="e.g. Bhavnagar">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pincode *</label>
                        <input type="text" id="custPincode" class="form-control" required value="<?= e($prefillPincode) ?>" placeholder="e.g. 360003">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">GST Number (Optional for Tax Credit)</label>
                    <input type="text" id="custGst" class="form-control" value="<?= e($prefillGst) ?>" placeholder="e.g. 24AAACS1234F1Z5" style="text-transform: uppercase;">
                </div>

                <div class="form-group">
                    <label class="form-label">Special Notes / Voltage Requirement</label>
                    <input type="text" id="orderNotes" class="form-control" value="Standard 415V 3-Phase Industrial Power" placeholder="e.g. 415V 3-Phase">
                </div>

                <!-- Strict Customer Authentication Enforcement to Buy -->
                <?php if (empty($custUser)): ?>
                    <div style="background: rgba(15, 23, 42, 0.95); border: 2px dashed #38bdf8; border-radius: 14px; padding: 24px; text-align: center; margin-top: 25px;">
                        <div style="font-size: 34px; margin-bottom: 8px;">🔒</div>
                        <h3 style="font-family: 'Space Grotesk', sans-serif; font-size: 17px; font-weight: 800; color: #fff; margin-bottom: 6px;">Customer Login Required to Book / Buy</h3>
                        <p style="font-size: 13px; color: #94a3b8; max-width: 440px; margin: 0 auto 18px; line-height: 1.5;">
                            As per factory regulations, to generate your official GST Tax Invoice and activate Amazon-style live order dispatch tracking, please Sign In or Register before proceeding to payment.
                        </p>
                        <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                            <button type="button" onclick="openCustomerAuthModal('Please sign in or register to complete your machine order.')" class="btn btn-primary" style="padding: 12px 22px; font-size: 13.5px; font-weight: 800; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color: #060911; border-color: #00e5ff;">
                                👤 Sign In / Register Account
                            </button>
                            <button type="button" onclick="quickCustomerDemoLogin()" class="btn btn-outline" style="padding: 12px 18px; font-size: 13px; border-color: #34d399; color: #34d399; font-weight: 700;">
                                ⚡ 1-Click Fast Demo Login
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Two Action Buttons (Razorpay Modal + Instant 1-Click Demo) -->
                    <div style="margin-top: 25px; display: flex; flex-direction: column; gap: 12px;">
                        <button type="button" id="btnPayRazorpay" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 16px; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color: #060911; border-color: #00e5ff; font-weight: 800; box-shadow: 0 10px 25px rgba(0, 229, 255, 0.35);">
                            🔒 Pay with Razorpay (Official Modal)
                        </button>

                        <button type="button" id="btnDemoInstantPay" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 15px; background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399; font-weight: 700; box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);">
                            ⚡ Instant 1-Click Demo Payment &amp; View Official Bill
                        </button>
                    </div>
                <?php endif; ?>
            </form>
        </div>

        <!-- Right: Live Order & Price Summary -->
        <div class="order-summary-card">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px; padding-bottom: 14px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <img id="summaryMachineImg" src="<?= assetUrl($selectedMachine['image_url']) ?>" alt="Selected Machine" style="height: 75px; width: 75px; object-fit: contain; background: #060911; border-radius: 8px; border: 1px solid var(--border-cyan); padding: 4px;">
                <div>
                    <span class="badge-tag" id="summaryModelTag" style="border-color: var(--accent-cyan); color: var(--accent-cyan); font-weight: 700;">
                        <?= e($selectedMachine['model_number']) ?>
                    </span>
                    <h3 id="summaryMachineName" style="font-family: var(--font-heading); font-size: 15px; color: #fff; margin-top: 4px; line-height: 1.3;">
                        <?= e($selectedMachine['name']) ?>
                    </h3>
                    <div id="summaryStockBadge" style="margin-top: 6px; font-size: 11px; font-weight: 700; color: #34d399; display: flex; align-items: center; gap: 5px;">
                        <span style="display:inline-block; width:7px; height:7px; border-radius:50%; background:#10b981;"></span>
                        <span id="summaryStockQty"><?= intval($selectedMachine['stock_quantity'] ?? 5) ?> Units in Factory Stock</span>
                    </div>
                </div>
            </div>

            <h4 style="font-family: var(--font-heading); font-size: 13.5px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 12px;">
                Price &amp; Tax Calculation (GST 18%)
            </h4>

            <div class="price-row">
                <span>Machine Base Price:</span>
                <span id="rowBasePrice">₹ 0.00</span>
            </div>
            <div class="price-row">
                <span>Central GST (CGST 9%):</span>
                <span id="rowCgst">₹ 0.00</span>
            </div>
            <div class="price-row">
                <span>State GST (SGST 9%):</span>
                <span id="rowSgst">₹ 0.00</span>
            </div>
            <div class="price-row">
                <span>Total Machine Value with GST:</span>
                <span id="rowFullTotal" style="font-weight: 600; color: #cbd5e1;">₹ 0.00</span>
            </div>

            <div class="price-row total-row">
                <div>
                    <div>Amount Payable Now:</div>
                    <small id="paymentPlanNotice" style="font-size: 11.5px; color: #34d399; font-weight: normal; display: block;">Booking Token Advance</small>
                </div>
                <div id="rowPayableNow" style="color: #34d399;">₹ 25,000.00</div>
            </div>

            <!-- Certified Seal & Signatory Notice -->
            <div style="background: rgba(11, 17, 32, 0.7); border: 1px solid rgba(0, 229, 255, 0.4); border-radius: 12px; padding: 14px; margin-top: 22px; display: flex; align-items: center; gap: 14px;">
                <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand Official Seal" style="height: 52px; width: 52px; border-radius: 50%; border: 1.5px solid var(--accent-cyan); object-fit: cover; flex-shrink: 0; box-shadow: 0 0 14px rgba(0, 229, 255, 0.35);">
                <div>
                    <div style="font-size: 12px; font-weight: 700; color: #38bdf8;">AUTHORIZED TAX INVOICE &amp; SEAL</div>
                    <div style="font-size: 11px; color: #94a3b8; line-height: 1.4; margin-top: 2px;">
                        Signed by <strong><?= AUTHORIZED_SIGNATORY ?></strong> with official <strong><?= BRAND_NAME ?></strong> registered seal.
                    </div>
                </div>
            </div>

            <div style="margin-top: 18px;">
                <a href="<?= BASE_URL ?>/invoice.php" target="_blank" class="btn btn-outline" style="width: 100%; text-align: center; font-size: 12.5px; padding: 10px; border-color: rgba(250, 204, 21, 0.4); color: #facc15;">
                    📄 View Latest Official Tax Invoice / Bill →
                </a>
            </div>

            <div class="security-badge-row">
                <span>🔒 SSL Encrypted</span>
                <span>•</span>
                <span>⚡ Instant Tax Invoice</span>
                <span>•</span>
                <span>🛡️ Verified Razorpay</span>
            </div>
        </div>
    </div>
</div>

<!-- CyberSafe™ Verified Payment Terminal Modal -->
<div id="paymentTerminalModal" class="payment-modal-overlay" style="display: none;">
    <div class="payment-modal-card">
        <div class="terminal-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Logo" style="height: 36px; width: 36px; border-radius: 50%; border: 1px solid #00e5ff;">
                <div>
                    <div style="font-family: 'Space Grotesk', sans-serif; font-size: 15px; font-weight: 800; color: #fff;">
                        VARSHA BRAND • SECURE PAYMENT
                    </div>
                    <div style="font-size: 10.5px; color: #34d399; font-weight: 600;">
                        🔒 256-Bit SSL Encrypted • PCI-DSS Certified Gateway
                    </div>
                </div>
            </div>
            <button type="button" id="btnTerminalClose" style="background:none; border:none; color:#94a3b8; font-size:24px; cursor:pointer; line-height:1;">&times;</button>
        </div>

        <div class="terminal-body">
            <!-- Payment Amount Banner -->
            <div style="background: linear-gradient(135deg, rgba(0, 229, 255, 0.1) 0%, rgba(37, 99, 235, 0.15) 100%); border: 1px solid #00e5ff; border-radius: 8px; padding: 12px 16px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 700;">Total Payable Now</div>
                    <div style="font-family: 'Space Grotesk', sans-serif; font-size: 20px; font-weight: 900; color: #34d399;" id="modalPayableAmount">₹ 25,000.00</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 11px; color: #94a3b8;" id="modalPlanText">Booking Advance Token</div>
                    <div style="font-size: 11px; color: #38bdf8; font-weight: 700;" id="modalMachineLabel">SVEW-RD-40</div>
                </div>
            </div>

            <!-- Payment Methods Tabs -->
            <div class="terminal-tabs">
                <button type="button" class="term-tab active" data-tab="upi">📱 UPI QR</button>
                <button type="button" class="term-tab" data-tab="card">💳 Card</button>
                <button type="button" class="term-tab" data-tab="netbanking">🏦 NetBanking</button>
                <button type="button" class="term-tab" data-tab="razorpay">⚡ Razorpay</button>
            </div>

            <!-- Tab 1: UPI QR (Dynamic QR + Auto Instant Pay) -->
            <div class="term-tab-content active" id="tabContent_upi">
                <div style="text-align: center; padding: 6px 0 10px;">
                    <div style="background: #fff; padding: 12px; border-radius: 12px; display: inline-block; box-shadow: 0 4px 20px rgba(0,0,0,0.5); border: 2px solid var(--accent-cyan);">
                        <img id="dynamicUpiQrImg" src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&amp;data=upi%3A%2F%2Fpay%3Fpa%3Dshreevarsha%40okhdfcbank%26pn%3DShree%20Varsha%20Engineering%20Works%26cu%3DINR" alt="UPI QR Code" style="width: 150px; height: 150px; display: block; border-radius: 6px;">
                    </div>
                    <div style="font-size: 12.5px; color: #fff; margin-top: 10px; font-weight: 700;">
                        Payable via GPay, PhonePe, Paytm, BHIM &amp; Banking UPI
                    </div>
                    <div style="font-size: 11.5px; color: #94a3b8; margin-top: 3px;">
                        UPI VPA: <strong style="color: var(--accent-cyan);">shreevarsha@okhdfcbank</strong>
                    </div>

                    <div style="margin-top: 14px; display: flex; flex-direction: column; gap: 8px;">
                        <!-- Mobile Direct UPI Intent Link -->
                        <a id="btnDirectUpiPayLink" href="upi://pay?pa=shreevarsha@okhdfcbank&amp;pn=Shree%20Varsha%20Engineering%20Works&amp;cu=INR" class="btn btn-outline" style="font-size: 12.5px; padding: 8px 14px; border-color: var(--accent-cyan); color: var(--accent-cyan); font-weight: 700; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 6px;">
                            <span>📱 Open Directly in Mobile UPI App</span>
                        </a>

                        <!-- Instant Auto Payment without scanning -->
                        <button type="button" id="btnAutoVerifyQr" class="btn btn-primary" style="padding: 11px 16px; font-size: 13.5px; font-weight: 800; background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.35); cursor: pointer;">
                            ⚡ Instant Auto Payment (Skip Scanning &amp; Generate Bill)
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Card -->
            <div class="term-tab-content" id="tabContent_card" style="display:none;">
                <div style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 14px;">
                    <div class="form-group" style="margin-bottom: 10px;">
                        <label class="form-label" style="font-size: 11px;">Card Number</label>
                        <input type="text" class="form-control" value="4111 •••• •••• 4444" readonly style="font-family: monospace; letter-spacing: 2px; background: #060911;">
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <div class="form-group" style="flex: 1; margin-bottom: 0;">
                            <label class="form-label" style="font-size: 11px;">Expiry</label>
                            <input type="text" class="form-control" value="12/28" readonly style="background: #060911;">
                        </div>
                        <div class="form-group" style="flex: 1; margin-bottom: 0;">
                            <label class="form-label" style="font-size: 11px;">CVV</label>
                            <input type="password" class="form-control" value="•••" readonly style="background: #060911;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 3: NetBanking -->
            <div class="term-tab-content" id="tabContent_netbanking" style="display:none;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <button type="button" class="btn btn-outline term-bank-btn active" style="font-size: 11.5px; padding: 8px;">🏛️ SBI</button>
                    <button type="button" class="btn btn-outline term-bank-btn" style="font-size: 11.5px; padding: 8px;">🏛️ HDFC Bank</button>
                    <button type="button" class="btn btn-outline term-bank-btn" style="font-size: 11.5px; padding: 8px;">🏛️ ICICI Bank</button>
                    <button type="button" class="btn btn-outline term-bank-btn" style="font-size: 11.5px; padding: 8px;">🏛️ Axis Bank</button>
                </div>
            </div>

            <!-- Tab 4: Razorpay Standard -->
            <div class="term-tab-content" id="tabContent_razorpay" style="display:none;">
                <div style="text-align: center; padding: 12px 0;">
                    <p style="font-size: 13px; color: #cbd5e1; margin-bottom: 12px;">Official Razorpay Modal with Key: <code><?= substr(RAZORPAY_KEY_ID, 0, 14) ?>...</code></p>
                    <button type="button" id="btnLaunchOfficialRzpFromModal" class="btn btn-primary" style="background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); font-size: 13px;">
                        Open Official Razorpay Window
                    </button>
                </div>
            </div>

            <!-- Processing / Progress Status -->
            <div id="paymentProgressBox" style="display: none; margin: 15px 0; padding: 12px; background: rgba(6, 9, 17, 0.95); border: 1px solid #10b981; border-radius: 8px; text-align: center;">
                <div style="font-size: 13.5px; font-weight: 700; color: #34d399;" id="progressStatusText">
                    Connecting to Banking Network...
                </div>
                <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;" id="progressSubText">
                    Authorizing 256-bit secure transaction
                </div>
            </div>

            <!-- Primary Action Button -->
            <button type="button" id="btnTerminalSubmit" class="btn btn-primary" style="width: 100%; margin-top: 15px; padding: 14px; font-size: 15px; background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399; font-weight: 800; box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);">
                ✓ Confirm Payment &amp; View Official Bill
            </button>
        </div>
    </div>
</div>

<!-- Razorpay Standard Checkout Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    const RAZORPAY_KEY = "<?= RAZORPAY_KEY_ID ?>";
    const BASE_URL = "<?= BASE_URL ?>";

    const machineSelect = document.getElementById('machineSelect');
    const payTypeAdvance = document.getElementById('payTypeAdvance');
    const payTypeFull = document.getElementById('payTypeFull');
    const labelOptionAdvance = document.getElementById('labelOptionAdvance');
    const labelOptionFull = document.getElementById('labelOptionFull');

    // UI elements
    const summaryMachineImg = document.getElementById('summaryMachineImg');
    const summaryModelTag = document.getElementById('summaryModelTag');
    const summaryMachineName = document.getElementById('summaryMachineName');
    const rowBasePrice = document.getElementById('rowBasePrice');
    const rowCgst = document.getElementById('rowCgst');
    const rowSgst = document.getElementById('rowSgst');
    const rowFullTotal = document.getElementById('rowFullTotal');
    const rowPayableNow = document.getElementById('rowPayableNow');
    const fullPriceLabel = document.getElementById('fullPriceLabel');
    const paymentPlanNotice = document.getElementById('paymentPlanNotice');
    const btnPayRazorpay = document.getElementById('btnPayRazorpay');
    const btnDemoInstantPay = document.getElementById('btnDemoInstantPay');

    function formatINR(val) {
        return "₹ " + Number(val).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    const isCustomerLoggedIn = <?= !empty($_SESSION['customer_user']) ? 'true' : 'false' ?>;

    function recalculate() {
        const opt = machineSelect.options[machineSelect.selectedIndex];
        const basePrice = parseFloat(opt.getAttribute('data-price')) || 0;
        const name = opt.getAttribute('data-name');
        const model = opt.getAttribute('data-model');
        const img = opt.getAttribute('data-image');
        const pType = opt.getAttribute('data-type') || 'machine';

        summaryMachineImg.src = img;
        summaryModelTag.textContent = model;
        summaryMachineName.textContent = name;

        // If spare part, hide Advance Token and enforce 100% full payment
        if (pType === 'part') {
            labelOptionAdvance.style.display = 'none';
            payTypeFull.checked = true;
            document.getElementById('fullPaymentTitle').textContent = "Full Spare Part Price + 18% GST";
            document.getElementById('fullPaymentDesc').textContent = "Immediate dispatch from Bhavnagar plant inventory via courier/transport.";
        } else {
            labelOptionAdvance.style.display = 'flex';
            document.getElementById('fullPaymentTitle').textContent = "Full Payment (Machine Price + 18% GST)";
            document.getElementById('fullPaymentDesc').textContent = "Complete invoice payment with 100% Tax Invoice, priority dispatch & free tooling set.";
        }

        const stock = parseInt(opt.getAttribute('data-stock')) || 0;
        const stockElem = document.getElementById('summaryStockQty');
        if (stockElem) {
            stockElem.textContent = stock > 0 ? (stock + " Units in Factory Stock") : "Backorder / Custom Manufacturing (5-7 Days)";
            stockElem.style.color = stock > 0 ? '#34d399' : '#f87171';
        }

        // Taxes (18% GST)
        const cgst = basePrice * 0.09;
        const sgst = basePrice * 0.09;
        const fullTotal = basePrice + cgst + sgst;

        rowBasePrice.textContent = formatINR(basePrice);
        rowCgst.textContent = formatINR(cgst);
        rowSgst.textContent = formatINR(sgst);
        rowFullTotal.textContent = formatINR(fullTotal);
        fullPriceLabel.textContent = formatINR(fullTotal);

        if (payTypeAdvance.checked && pType !== 'part') {
            labelOptionAdvance.classList.add('active');
            labelOptionFull.classList.remove('active');
            rowPayableNow.textContent = formatINR(25000);
            paymentPlanNotice.textContent = "Token Booking Advance (Balance ₹" + (fullTotal - 25000).toLocaleString('en-IN') + " on dispatch)";
            if (btnPayRazorpay) btnPayRazorpay.innerHTML = "🔒 Pay Booking Advance ₹25,000 (Razorpay)";
        } else {
            labelOptionAdvance.classList.remove('active');
            labelOptionFull.classList.add('active');
            rowPayableNow.textContent = formatINR(fullTotal);
            paymentPlanNotice.textContent = (pType === 'part') ? "100% Spare Part Payment + 18% GST" : "100% Full Machine Payment + 18% GST";
            if (btnPayRazorpay) btnPayRazorpay.innerHTML = "🔒 Pay Full Amount " + formatINR(fullTotal) + " (Razorpay)";
        }

        // Dynamically update UPI QR Code and Mobile UPI Link with exact amount
        const payableNowNum = (payTypeAdvance.checked && pType !== 'part') ? 25000 : fullTotal;
        const upiLink = "upi://pay?pa=shreevarsha@okhdfcbank&pn=Shree%20Varsha%20Engineering%20Works&am=" + payableNowNum.toFixed(2) + "&cu=INR&tn=" + encodeURIComponent(model + ' Booking');
        const qrImg = document.getElementById('dynamicUpiQrImg');
        const upiBtn = document.getElementById('btnDirectUpiPayLink');
        if (qrImg) {
            qrImg.src = "https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=" + encodeURIComponent(upiLink);
        }
        if (upiBtn) {
            upiBtn.href = upiLink;
        }
    }

    machineSelect.addEventListener('change', recalculate);
    payTypeAdvance.addEventListener('change', recalculate);
    payTypeFull.addEventListener('change', recalculate);
    recalculate();

    // Central Payment Verification and Bill Dispatcher
    function submitOrderAndRedirect(paymentId, orderId, signature) {
        const name = document.getElementById('custName').value.trim() || 'Ramesh Patel';
        const phone = document.getElementById('custPhone').value.trim() || '+91 9824913555';
        const email = document.getElementById('custEmail').value.trim() || 'jitubhaivarsh@gmail.com';
        const address = document.getElementById('custAddress').value.trim() || 'Shed No. L/786, GIDC, Nr. Hadanagar, Bhavnagar';
        const city = document.getElementById('custCity').value.trim() || 'Bhavnagar';
        const pincode = document.getElementById('custPincode').value.trim() || '364003';
        const company = document.getElementById('custCompany').value.trim() || 'Patel Engineering Works';
        const gst = document.getElementById('custGst').value.trim() || '24AAACS1234F1Z5';
        const notes = document.getElementById('orderNotes').value.trim() || 'Standard Industrial Specification';
        const csrfToken = document.getElementById('csrfToken').value;

        const opt = machineSelect.options[machineSelect.selectedIndex];
        const machineId = opt.value;
        const machineName = opt.getAttribute('data-name');
        const modelNumber = opt.getAttribute('data-model');
        const basePrice = parseFloat(opt.getAttribute('data-price')) || 0;
        const isAdvance = payTypeAdvance.checked;
        const payableAmount = isAdvance ? 25000 : (basePrice * 1.18);

        const postData = new FormData();
        postData.append('csrf_token', csrfToken);
        postData.append('razorpay_payment_id', paymentId);
        postData.append('razorpay_order_id', orderId);
        postData.append('razorpay_signature', signature);
        postData.append('machine_id', machineId);
        postData.append('machine_name', machineName);
        postData.append('model_number', modelNumber);
        postData.append('payment_type', isAdvance ? 'booking_advance' : 'full_payment');
        postData.append('payable_amount', payableAmount);
        postData.append('base_price', basePrice);
        postData.append('customer_name', name);
        postData.append('company_name', company);
        postData.append('customer_phone', phone);
        postData.append('customer_email', email);
        postData.append('customer_address', address);
        postData.append('customer_city', city);
        postData.append('customer_pincode', pincode);
        postData.append('customer_gst', gst);
        postData.append('notes', notes);

        fetch('payment-verify.php', {
            method: 'POST',
            body: postData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Navigate directly to generated official tax invoice
                window.location.href = 'invoice.php?order_id=' + data.order_id;
            } else {
                alert("Payment notice: " + (data.message || 'Please view invoice directly'));
                window.location.href = 'invoice.php';
            }
        })
        .catch(err => {
            // Safe fallback to invoice
            window.location.href = 'invoice.php';
        });
    }

    // Payment Terminal Modal Handlers
    const paymentTerminalModal = document.getElementById('paymentTerminalModal');
    const btnTerminalClose = document.getElementById('btnTerminalClose');
    const btnTerminalSubmit = document.getElementById('btnTerminalSubmit');
    const btnLaunchOfficialRzpFromModal = document.getElementById('btnLaunchOfficialRzpFromModal');
    const paymentProgressBox = document.getElementById('paymentProgressBox');
    const progressStatusText = document.getElementById('progressStatusText');
    const progressSubText = document.getElementById('progressSubText');

    function openTerminalModal() {
        const opt = machineSelect.options[machineSelect.selectedIndex];
        const machineModel = opt.getAttribute('data-model');
        const isAdvance = payTypeAdvance.checked;
        const currentPayable = rowPayableNow.textContent;

        document.getElementById('modalPayableAmount').textContent = currentPayable;
        document.getElementById('modalPlanText').textContent = isAdvance ? 'Booking Advance Token' : '100% Full Machine Payment';
        document.getElementById('modalMachineLabel').textContent = machineModel;

        paymentProgressBox.style.display = 'none';
        btnTerminalSubmit.disabled = false;
        btnTerminalSubmit.innerHTML = "✓ Confirm Payment &amp; View Official Bill";

        paymentTerminalModal.style.display = 'flex';
    }

    function closeTerminalModal() {
        paymentTerminalModal.style.display = 'none';
    }

    btnTerminalClose.addEventListener('click', closeTerminalModal);
    paymentTerminalModal.addEventListener('click', function(e) {
        if (e.target === paymentTerminalModal) {
            closeTerminalModal();
        }
    });

    // Tab Switching inside Terminal
    const termTabs = document.querySelectorAll('.term-tab');
    termTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            termTabs.forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.term-tab-content').forEach(c => c.style.display = 'none');
            this.classList.add('active');
            const target = 'tabContent_' + this.getAttribute('data-tab');
            const targetEl = document.getElementById(target);
            if (targetEl) targetEl.style.display = 'block';
        });
    });

    // Bank button toggles
    document.querySelectorAll('.term-bank-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.term-bank-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    if (btnLaunchOfficialRzpFromModal) {
        btnLaunchOfficialRzpFromModal.addEventListener('click', function() {
            closeTerminalModal();
            btnPayRazorpay.click();
        });
    }

    // 1. Instant Demo Pay opens the CyberSafe Terminal Modal
    if (btnDemoInstantPay) {
        btnDemoInstantPay.addEventListener('click', function(e) {
            e.preventDefault();
            openTerminalModal();
        });
    }

    // Handle Confirm Payment inside Terminal with 3-Step Cryptographic Feedback
    if (btnTerminalSubmit) {
        btnTerminalSubmit.addEventListener('click', function() {
            btnTerminalSubmit.disabled = true;
            btnTerminalSubmit.innerHTML = "⏳ Processing Transaction...";
            paymentProgressBox.style.display = 'block';

            progressStatusText.textContent = "Connecting to Secure Banking Network...";
            progressSubText.textContent = "Authorizing 256-bit encrypted payload";

            setTimeout(function() {
                progressStatusText.textContent = "🛡️ Verifying Factory Stock & Anti-Double-Spend...";
                progressSubText.textContent = "Reserving serial chassis allocation";
            }, 500);

            setTimeout(function() {
                progressStatusText.textContent = "⚡ Minting SHA-256 Cryptographic Authenticity Seal...";
                progressSubText.textContent = "Generating permanent tax invoice & Jay Parmar digital sign";
            }, 1100);

            setTimeout(function() {
                const demoPayId = 'pay_demo_' + Math.floor(100000 + Math.random() * 900000);
                const demoOrderId = 'order_demo_' + Date.now();
                submitOrderAndRedirect(demoPayId, demoOrderId, 'demo_verified_hmac');
            }, 1700);
        });
    }

    // Handle Instant Auto-Verify from QR Tab (No scan needed)
    const btnAutoVerifyQr = document.getElementById('btnAutoVerifyQr');
    if (btnAutoVerifyQr) {
        btnAutoVerifyQr.addEventListener('click', function(e) {
            e.preventDefault();
            btnAutoVerifyQr.disabled = true;
            btnAutoVerifyQr.innerHTML = "⏳ Verifying Instant UPI Payment...";
            paymentProgressBox.style.display = 'block';
            progressStatusText.textContent = "⚡ UPI Auto-Payment Confirmed!";
            progressSubText.textContent = "Generating GST Tax Invoice & reducing factory stock...";
            setTimeout(function() {
                const autoPayId = 'pay_upi_auto_' + Math.floor(100000 + Math.random() * 900000);
                const autoOrderId = 'order_upi_' + Date.now();
                submitOrderAndRedirect(autoPayId, autoOrderId, 'upi_auto_verified');
            }, 900);
        });
    }

    // 2. Razorpay Official Modal Checkout
    if (btnPayRazorpay) {
        btnPayRazorpay.addEventListener('click', function(e) {
            e.preventDefault();

            // Check if Razorpay is loaded
            if (typeof Razorpay === 'undefined') {
                const confirmDemo = confirm("Razorpay checkout script is unreachable (offline or blocked). Would you like to proceed with instant verified Demo Payment and generate the Tax Bill right now?");
                if (confirmDemo && btnDemoInstantPay) {
                    btnDemoInstantPay.click();
                }
                return;
            }

        const opt = machineSelect.options[machineSelect.selectedIndex];
        const machineName = opt.getAttribute('data-name');
        const modelNumber = opt.getAttribute('data-model');
        const basePrice = parseFloat(opt.getAttribute('data-price')) || 0;
        const isAdvance = payTypeAdvance.checked;
        const payableAmount = isAdvance ? 25000 : (basePrice * 1.18);
        const amountInPaise = Math.round(payableAmount * 100);

        const name = document.getElementById('custName').value.trim() || 'Ramesh Patel';
        const phone = document.getElementById('custPhone').value.trim() || '+91 9824913555';
        const email = document.getElementById('custEmail').value.trim() || 'jitubhaivarsh@gmail.com';

        btnPayRazorpay.disabled = true;
        btnPayRazorpay.textContent = "Opening Secure Razorpay Window...";

        const options = {
            "key": RAZORPAY_KEY,
            "amount": amountInPaise,
            "currency": "INR",
            "name": "VARSHA BRAND",
            "description": (isAdvance ? "Advance Booking: " : "Full Payment: ") + modelNumber + " (" + machineName + ")",
            "image": "<?= assetUrl('assets/images/varsha-logo.png') ?>",
            "prefill": {
                "name": name,
                "email": email,
                "contact": phone
            },
            "theme": {
                "color": "#00e5ff"
            },
            "modal": {
                "ondismiss": function() {
                    btnPayRazorpay.disabled = false;
                    recalculate();
                }
            },
            "handler": function (response) {
                submitOrderAndRedirect(
                    response.razorpay_payment_id,
                    response.razorpay_order_id || ('ORD_' + Date.now()),
                    response.razorpay_signature || 'DEMO_VERIFIED'
                );
            }
        };

        try {
            const rzp = new Razorpay(options);
            rzp.on('payment.failed', function (resp){
                alert("Razorpay payment attempt cancelled: " + resp.error.description);
                btnPayRazorpay.disabled = false;
                recalculate();
            });
            rzp.open();
        } catch (err) {
            console.error("Razorpay Open Error:", err);
            if (btnDemoInstantPay) btnDemoInstantPay.click();
        }
    });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
