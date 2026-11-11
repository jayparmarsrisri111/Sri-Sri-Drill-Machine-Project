<?php
/**
 * Checkout & Online Machine Booking with Razorpay
 * Shree Varsha Engineering Works - Varsha Brand
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/cart-helper.php';

$pdo = Database::getConnection();

// Check if checking out from cart
$isFromCart = !empty($_GET['from_cart']);
$cartSummary = getCartSummary($pdo);
$cartItems = $cartSummary['items'] ?? [];
if ($isFromCart && empty($cartItems)) {
    $isFromCart = false;
}

// Fetch active products for dropdown
$products = [];
try {
    $stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.is_active = 1 ORDER BY c.display_order ASC, p.id ASC");
    $products = $stmt->fetchAll();
} catch (Exception $e) {
    // fallback
}

$selectedId = intval($_GET['product_id'] ?? ($_GET['part_id'] ?? ($_GET['machine_id'] ?? ($isFromCart ? ($cartItems[0]['id'] ?? 1) : ($products[0]['id'] ?? 1)))));
$initialQty = max(1, intval($_GET['qty'] ?? ($isFromCart ? ($cartItems[0]['quantity'] ?? 1) : 1)));
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

$rzpLogoUrl = assetUrl('assets/images/varsha-logo.png');
// In case of localhost testing where external Razorpay iframe cannot fetch local HTTP images, point to public mirror
if (strpos($rzpLogoUrl, 'localhost') !== false || strpos($rzpLogoUrl, '127.0.0.1') !== false) {
    $rzpLogoUrl = 'https://varsha-drill-machines.onrender.com/assets/images/varsha-logo.png';
}

$pageTitle = 'Online Machine Booking & Order Reservation | ' . BRAND_NAME . ' Bhavnagar';
$pageDesc = 'Book your Varsha Brand Industrial Drill Machine online with Razorpay secure instant booking advance or full invoice payment.';
$pageKeywords = 'book drill machine online, drill machine price India, Razorpay machinery token, Shree Varsha booking';
$canonicalUrl = 'https://varsha-drill-machines.onrender.com/checkout.php';
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
    .checkout-hero-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 10px;
    }
    .checkout-hero-logo {
        height: 48px;
        width: 48px;
        border-radius: 50%;
        border: 1.5px solid var(--accent-cyan);
        flex-shrink: 0;
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
            gap: 25px;
        }
    }
    @media (max-width: 768px) {
        .checkout-hero {
            padding: 18px 0 14px;
        }
        .checkout-hero-header {
            flex-direction: row;
            align-items: center;
            gap: 12px;
        }
        .checkout-hero-header h1 {
            font-size: 19px !important;
            line-height: 1.25 !important;
            margin-top: 4px !important;
        }
        .checkout-hero-header .pill-badge {
            font-size: 10px !important;
            padding: 2px 7px !important;
        }
        .checkout-grid {
            margin: 15px 0 35px !important;
            gap: 20px !important;
        }
        .checkout-card {
            padding: 16px !important;
        }
        .order-summary-card {
            padding: 16px !important;
            position: static !important;
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
        <div class="checkout-hero-header">
            <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand" class="checkout-hero-logo">
            <div style="flex: 1; min-width: 0;">
                <span class="pill-badge" style="background: rgba(16, 185, 129, 0.15); border-color: #10b981; color: #34d399; font-size: 11px;">
                    🔒 256-BIT SECURE RAZORPAY GATEWAY
                </span>
                <h1 style="font-family: var(--font-heading); font-size: 24px; color: #fff; margin-top: 4px; word-break: break-word;">
                    Online Machine Booking &amp; Official Tax Invoice
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

            <?php if ($isFromCart): ?>
                <div style="background: linear-gradient(90deg, rgba(5, 150, 105, 0.2) 0%, rgba(15, 23, 42, 0.9) 100%); border: 1.5px solid #10b981; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="font-size: 24px;">🛒</span>
                            <div>
                                <div style="font-size: 14.5px; font-weight: 800; color: #34d399;">
                                    Checking out Combined Cart Order (<?= $cartSummary['total_qty'] ?> Units)
                                </div>
                                <div style="font-size: 12px; color: #94a3b8;">
                                    Total Value: <strong>₹ <?= number_format($cartSummary['grand_total'], 2) ?></strong> (includes 18% GST &amp; Transit Freight)
                                </div>
                            </div>
                        </div>
                        <a href="<?= BASE_URL ?>/cart.php" class="btn btn-outline" style="font-size: 11.5px; padding: 6px 12px; border-color: var(--accent-cyan); color: var(--accent-cyan); text-decoration: none;">
                            ✏️ Edit Cart
                        </a>
                    </div>
                    <div style="margin-top: 10px; padding-top: 8px; border-top: 1px dashed rgba(255,255,255,0.1); font-size: 12px; color: #cbd5e1;">
                        <?php foreach ($cartItems as $ci): ?>
                            <span style="display: inline-block; background: rgba(0,0,0,0.4); padding: 3px 8px; border-radius: 4px; margin: 2px 4px 2px 0;">
                                <?= e($ci['name']) ?> (<?= $ci['quantity'] ?>x) - ₹ <?= number_format($ci['subtotal'], 0) ?>
                            </span>
                        <?php endforeach; ?>
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

            <!-- Quantity Selector with Live Stock Indicator -->
            <div class="form-group" style="margin-top: 18px; background: rgba(15, 23, 42, 0.85); border: 1.5px solid rgba(0, 229, 255, 0.3); border-radius: 10px; padding: 14px 18px;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div>
                        <label class="form-label" style="margin-bottom: 2px; font-weight: 800; color: #fff; font-size: 14px;">
                            Order Quantity (જથ્થો / Units) *
                        </label>
                        <div style="font-size: 12px; color: var(--text-muted);">
                            Available in factory stock: <strong id="checkoutStockCount" style="color: #34d399; font-weight: 800;"><?= intval($selectedMachine['stock_quantity'] ?? 5) ?> Units</strong>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <button type="button" id="btnQtyMinus" style="width: 38px; height: 38px; border-radius: 6px; background: #060911; border: 1.5px solid var(--accent-cyan); color: #fff; font-size: 19px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center;">−</button>
                        <input type="number" id="orderQuantity" name="quantity" class="form-control" style="width: 65px; height: 38px; text-align: center; font-size: 17px; font-weight: 800; color: #34d399; background: #060911; border: 1.5px solid #0284c7;" value="<?= $initialQty ?>" min="1" max="<?= max(1, intval($selectedMachine['stock_quantity'] ?? 5)) ?>">
                        <button type="button" id="btnQtyPlus" style="width: 38px; height: 38px; border-radius: 6px; background: #060911; border: 1.5px solid var(--accent-cyan); color: #fff; font-size: 19px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center;">+</button>
                    </div>
                </div>
            </div>

            <!-- Payment Type Selection (Supports Online Advance up to ₹90,000 or Full Payment) -->
            <div style="margin: 22px 0;">
                <label class="form-label" style="margin-bottom: 10px;">Choose Payment Option *</label>
                
                <label class="payment-option-label active" id="labelOptionAdvance">
                    <input type="radio" name="payment_type" id="payTypeAdvance" value="booking_advance" checked style="accent-color: var(--accent-cyan); width: 18px; height: 18px;">
                    <div style="flex: 1;">
                        <div style="font-weight: 700; color: #fff; font-size: 15px; display: flex; justify-content: space-between;">
                            <span>Standard Token Booking Advance</span>
                            <span style="color: #34d399;">₹ 25,000</span>
                        </div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 3px;">
                            Standard token to reserve manufacturing slot and chassis allocation today.
                        </div>
                    </div>
                </label>

                <label class="payment-option-label" id="labelOptionCustomAdvance">
                    <input type="radio" name="payment_type" id="payTypeCustomAdvance" value="custom_advance" style="accent-color: var(--accent-cyan); width: 18px; height: 18px;">
                    <div style="flex: 1;">
                        <div style="font-weight: 700; color: #fff; font-size: 15px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
                            <span>Custom Online Advance (Up to ₹ 4,00,000)</span>
                            <span style="color: #38bdf8; font-size: 16px; font-weight: 800;" id="customAdvanceDisplay">₹ 90,000</span>
                        </div>
                        <div style="font-size: 12px; color: var(--text-muted); margin: 4px 0 8px;">
                            Pay custom advance booking online (up to ₹4,00,000). Remaining balance due upon delivery.
                        </div>
                        <div id="customAdvanceBox" style="display: none; margin-top: 10px; padding: 10px 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(56, 189, 248, 0.3); border-radius: 8px;">
                            <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 8px;">
                                <button type="button" class="btn-advance-chip" data-val="25000" style="padding: 4px 10px; font-size: 11.5px; border-radius: 6px; background: rgba(0,229,255,0.1); border: 1px solid rgba(0,229,255,0.3); color: #00e5ff; cursor: pointer;">₹ 25,000</button>
                                <button type="button" class="btn-advance-chip" data-val="50000" style="padding: 4px 10px; font-size: 11.5px; border-radius: 6px; background: rgba(0,229,255,0.1); border: 1px solid rgba(0,229,255,0.3); color: #00e5ff; cursor: pointer;">₹ 50,000</button>
                                <button type="button" class="btn-advance-chip active-chip" data-val="90000" style="padding: 4px 10px; font-size: 11.5px; border-radius: 6px; background: #0284c7; border: 1px solid #38bdf8; color: #fff; cursor: pointer; font-weight: 700;">₹ 90,000</button>
                                <button type="button" class="btn-advance-chip" data-val="150000" style="padding: 4px 10px; font-size: 11.5px; border-radius: 6px; background: rgba(0,229,255,0.1); border: 1px solid rgba(0,229,255,0.3); color: #00e5ff; cursor: pointer;">₹ 1,50,000</button>
                                <button type="button" class="btn-advance-chip" data-val="250000" style="padding: 4px 10px; font-size: 11.5px; border-radius: 6px; background: rgba(0,229,255,0.1); border: 1px solid rgba(0,229,255,0.3); color: #00e5ff; cursor: pointer;">₹ 2,50,000</button>
                                <button type="button" class="btn-advance-chip" data-val="400000" style="padding: 4px 10px; font-size: 11.5px; border-radius: 6px; background: rgba(0,229,255,0.1); border: 1px solid rgba(0,229,255,0.3); color: #00e5ff; cursor: pointer;">₹ 4,00,000 (Max Advance)</button>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                <label style="font-size: 12px; color: #cbd5e1;">Or Enter Amount (₹):</label>
                                <input type="number" id="inputCustomAdvance" min="5000" max="400000" step="5000" value="90000" class="form-control" style="max-width: 170px; height: 34px; font-size: 14px; font-weight: 700; color: #34d399; padding: 4px 10px;">
                                <span style="font-size: 11px; color: #94a3b8;">(Min ₹5,000 - Max ₹4,00,000)</span>
                            </div>
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

                <!-- Step 3: Payment Method Dropdown & Instructions -->
                <div class="form-group" style="margin-top: 26px; background: rgba(15, 23, 42, 0.95); border: 1.5px solid #00e5ff; border-radius: 12px; padding: 18px 20px; box-shadow: 0 8px 25px rgba(0, 229, 255, 0.1);">
                    <label class="form-label" style="font-size: 15px; font-weight: 800; color: #fff; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                        <span>💳</span> Select Payment Method (ચુકવણીની રીત પસંદ કરો) *
                    </label>
                    <select id="paymentMethodSelect" class="form-control" style="font-size: 14.5px; font-weight: 700; height: 46px; border: 1.5px solid #00e5ff; background: #060911; color: #fff; cursor: pointer;">
                        <option value="online" selected>💳 Online Payment (Instant UPI, Debit / Credit Card, NetBanking via Razorpay)</option>
                        <option value="cod">💵 Cash on Delivery (COD / ડિલિવરી સમયે રોકડા ચુકવો)</option>
                        <option value="bank_transfer">🏛️ Direct Bank Transfer (NEFT / RTGS / IMPS - HDFC Bank Current A/C)</option>
                        <option value="cheque">📜 Cheque / Demand Draft (ખાતા પેયી એકાઉન્ટ પેયી ચેક / ડીડી)</option>
                    </select>

                    <!-- Dynamic helper panels for selected method -->
                    <div id="methodInfoOnline" class="method-info-box" style="margin-top: 14px; background: rgba(0, 229, 255, 0.08); border: 1px solid rgba(0, 229, 255, 0.35); border-radius: 8px; padding: 14px 16px;">
                        <div style="font-size: 13.5px; font-weight: 800; color: #38bdf8; display: flex; align-items: center; gap: 6px;">
                            <span>🔒</span> 256-Bit SSL Encrypted Razorpay Gateway
                        </div>
                        <div style="font-size: 12px; color: #cbd5e1; margin-top: 4px; line-height: 1.5;">
                            Pay securely with Google Pay, PhonePe, Paytm, BHIM UPI, RuPay, Visa, MasterCard, or NetBanking across 50+ banks. Instant token confirmation &amp; official GST Tax Invoice generated immediately upon successful payment.
                        </div>
                        <div style="margin-top: 10px; padding: 8px 12px; background: rgba(245, 158, 11, 0.15); border: 1px solid #f59e0b; border-radius: 6px; font-size: 11.5px; color: #fbbf24; line-height: 1.45;">
                            💡 <strong>બેંકિંગ લિમિટ માર્ગદર્શિકા (UPI Limit Notice):</strong> ભારતીય બેંકો અને UPI એપ્સમાં સિંગલ પેમેન્ટ પર ₹25,000 થી ₹1,00,000 ની લિમિટ હોય છે. જેથી ₹25,000 થી વધુ રકમનું UPI પેમેન્ટ બેંક તરફથી અટકી શકે છે. સરળ પેમેન્ટ માટે <strong>₹ 25,000 ટોકન એડવાન્સ</strong> પસંદ કરો અથવા <strong>ડેબિટ/ક્રેડિટ કાર્ડ / નેટબેંકિંગ</strong> અથવા <strong>ડાયરેક્ટ RTGS / NEFT</strong> પસંદ કરો.
                        </div>
                    </div>

                    <div id="methodInfoCod" class="method-info-box" style="display: none; margin-top: 14px; background: rgba(16, 185, 129, 0.1); border: 1.5px solid #10b981; border-radius: 8px; padding: 14px;">
                        <div style="font-size: 13.5px; font-weight: 800; color: #34d399; display: flex; align-items: center; gap: 6px;">
                            <span>💵</span> Cash on Delivery (COD) Selected
                        </div>
                        <div style="font-size: 12px; color: #cbd5e1; margin-top: 5px; line-height: 1.5;">
                            No advance payment required right now! Confirm your order directly. You can pay the total invoice amount in cash or cheque directly to the logistics transport driver when the drill machine consignment arrives at your factory.
                        </div>
                        <div style="margin-top: 6px; font-size: 11px; color: #94a3b8;">
                            ✔ Printed Official GST Invoice &amp; Transport Lorry Receipt (LR) will accompany the shipment crate.
                        </div>
                    </div>

                    <div id="methodInfoBank" class="method-info-box" style="display: none; margin-top: 14px; background: rgba(2, 132, 199, 0.12); border: 1.5px solid #0284c7; border-radius: 8px; padding: 14px;">
                        <div style="font-size: 13.5px; font-weight: 800; color: #38bdf8; display: flex; align-items: center; gap: 6px;">
                            <span>🏛️</span> Shree Varsha Current Account (HDFC / SBI Bank)
                        </div>
                        <div style="font-size: 12px; color: #cbd5e1; margin-top: 8px; line-height: 1.6; background: rgba(0,0,0,0.4); padding: 10px 14px; border-radius: 6px; font-family: monospace;">
                            <strong>A/C Name:</strong> SHREE VARSHA ENGINEERING WORKS<br>
                            <strong>Bank:</strong> HDFC Bank Ltd. (or State Bank of India)<br>
                            <strong>A/C No:</strong> 50200088992211 (Current Account)<br>
                            <strong>IFSC Code:</strong> HDFC0000123<br>
                            <strong>Branch:</strong> Waghawadi Road, Bhavnagar - 364002<br>
                            <strong>UPI ID:</strong> shreevarsha@okhdfcbank
                        </div>
                        
                        <!-- Required Payment Details Form for Direct Bank Transfer -->
                        <div style="margin-top: 14px; padding: 12px; background: rgba(15, 23, 42, 0.85); border: 1px solid rgba(56, 189, 248, 0.4); border-radius: 8px;">
                            <div style="font-size: 12.5px; font-weight: 800; color: #38bdf8; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                                <span>📝</span> જરૂરી ટ્રાન્સફર વિગતો ભરો (Enter Transfer Details):
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                <div>
                                    <label class="form-label" style="font-size: 11px; margin-bottom: 3px; color: #fff;">UTR / Transaction Ref No *</label>
                                    <input type="text" id="bankUtrInput" class="form-control" placeholder="e.g. UTR123456789012" style="font-size: 12.5px; height: 38px; text-transform: uppercase;">
                                </div>
                                <div>
                                    <label class="form-label" style="font-size: 11px; margin-bottom: 3px; color: #fff;">Sender Bank Name *</label>
                                    <input type="text" id="bankSenderInput" class="form-control" placeholder="e.g. State Bank of India" style="font-size: 12.5px; height: 38px;">
                                </div>
                                <div style="grid-column: span 2;">
                                    <label class="form-label" style="font-size: 11px; margin-bottom: 3px; color: #fff;">Transfer Date *</label>
                                    <input type="date" id="bankDateInput" class="form-control" value="<?= date('Y-m-d') ?>" style="font-size: 12.5px; height: 38px;">
                                </div>
                            </div>
                            <div style="font-size: 10.5px; color: #94a3b8; margin-top: 6px;">
                                * સુરક્ષા ચકાસણી માટે UTR નંબર અને તમારી બેંકનું નામ દાખલ કરવું ફરજિયાત છે.
                            </div>
                        </div>
                    </div>

                    <div id="methodInfoCheque" class="method-info-box" style="display: none; margin-top: 14px; background: rgba(168, 85, 247, 0.1); border: 1.5px solid #a855f7; border-radius: 8px; padding: 14px;">
                        <div style="font-size: 13.5px; font-weight: 800; color: #c084fc; display: flex; align-items: center; gap: 6px;">
                            <span>📜</span> Account Payee Cheque / Demand Draft (DD)
                        </div>
                        <div style="font-size: 12px; color: #cbd5e1; margin-top: 5px; line-height: 1.5;">
                            Issue cheque or DD in favor of <strong>"SHREE VARSHA ENGINEERING WORKS"</strong>, payable at Bhavnagar. Hand it over to the delivery partner on arrival or courier it to our factory address.
                        </div>

                        <!-- Required Payment Details Form for Cheque / DD -->
                        <div style="margin-top: 14px; padding: 12px; background: rgba(15, 23, 42, 0.85); border: 1px solid rgba(192, 132, 252, 0.4); border-radius: 8px;">
                            <div style="font-size: 12.5px; font-weight: 800; color: #c084fc; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                                <span>📝</span> જરૂરી ચેક વિગતો ભરો (Enter Cheque / DD Details):
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                <div>
                                    <label class="form-label" style="font-size: 11px; margin-bottom: 3px; color: #fff;">Cheque / DD Number *</label>
                                    <input type="text" id="chequeNumberInput" class="form-control" placeholder="e.g. 000452 (6 digits)" style="font-size: 12.5px; height: 38px;">
                                </div>
                                <div>
                                    <label class="form-label" style="font-size: 11px; margin-bottom: 3px; color: #fff;">Bank &amp; Branch Name *</label>
                                    <input type="text" id="chequeBankInput" class="form-control" placeholder="e.g. HDFC Bank, GIDC Branch" style="font-size: 12.5px; height: 38px;">
                                </div>
                                <div style="grid-column: span 2;">
                                    <label class="form-label" style="font-size: 11px; margin-bottom: 3px; color: #fff;">Cheque Issue Date *</label>
                                    <input type="date" id="chequeDateInput" class="form-control" value="<?= date('Y-m-d') ?>" style="font-size: 12.5px; height: 38px;">
                                </div>
                            </div>
                            <div style="font-size: 10.5px; color: #94a3b8; margin-top: 6px;">
                                * સુરક્ષા ચકાસણી માટે ચેક નંબર અને બેંકનું નામ દાખલ કરવું ફરજિયાત છે.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Non-blocking cancellation / error / status notice -->
                <div id="paymentAlertNotice" style="display: none; margin-top: 18px; padding: 12px 16px; border-radius: 10px; font-size: 13px; line-height: 1.5; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                    <div id="paymentAlertText" style="flex: 1;"></div>
                    <button type="button" onclick="document.getElementById('paymentAlertNotice').style.display='none'" style="background:none; border:none; color:#cbd5e1; font-size:18px; font-weight:bold; cursor:pointer;">&times;</button>
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
                    <!-- Action Buttons for Online vs COD / Bank Transfer / Cheque -->
                    <div style="margin-top: 25px; display: flex; flex-direction: column; gap: 12px;">
                        <!-- High Value Banking Notice (Appears for ₹5,00,000+ / ₹10,00,000+) -->
                        <div id="highValueNoticeBox" style="display: none; background: rgba(245, 158, 11, 0.12); border: 1.5px solid #f59e0b; border-radius: 10px; padding: 14px 16px; margin-bottom: 6px;">
                            <div style="font-size: 13.5px; font-weight: 800; color: #fbbf24; display: flex; align-items: center; gap: 8px;">
                                <span>⚠️</span> <span>₹10,00,000+ High-Value Payment Guidance (બેંકિંગ માર્ગદર્શિકા)</span>
                            </div>
                            <div style="font-size: 12px; color: #cbd5e1; margin-top: 6px; line-height: 1.6;">
                                As per RBI &amp; NPCI regulations, single online UPI/card transactions are capped at ₹1,00,000 - ₹5,00,000. An entire transaction of <strong id="highValueAmountSpan" style="color:#fff;">₹ 10,00,000</strong> cannot be processed in a single online card/UPI swipe.
                                <br>For industrial machinery, please choose an authorized option below:
                            </div>
                            <div style="margin-top: 10px; display: flex; gap: 8px; flex-wrap: wrap;">
                                <button type="button" onclick="window.switchToAdvancePlan()" class="btn btn-outline" style="padding: 7px 12px; font-size: 11.5px; border-color: #34d399; color: #34d399; font-weight: 700; background: rgba(16, 185, 129, 0.1);">
                                    👉 Option 1: Pay Token Advance (₹ 25,000) via Razorpay
                                </button>
                                <button type="button" onclick="window.switchToBankTransfer()" class="btn btn-primary" style="padding: 7px 12px; font-size: 11.5px; background: #0284c7; border-color: #38bdf8; color: #fff; font-weight: 700;">
                                    👉 Option 2: Direct RTGS / NEFT to Varsha Current A/C (No Limit)
                                </button>
                            </div>
                        </div>

                        <!-- Online Payment Trigger -->
                        <button type="button" id="btnPayRazorpay" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 16px; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color: #060911; border-color: #00e5ff; font-weight: 800; box-shadow: 0 10px 25px rgba(0, 229, 255, 0.35);">
                            🔒 Pay with Razorpay / UPI (Official Gateway)
                        </button>

                        <!-- Direct Confirmation Button for COD / Bank Transfer / Cheque -->
                        <button type="button" id="btnSubmitDirectOrder" class="btn btn-primary" style="display: none; width: 100%; padding: 15px; font-size: 16px; background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399; color: #fff; font-weight: 800; box-shadow: 0 10px 25px rgba(16, 185, 129, 0.35);">
                            📦 Confirm Cash on Delivery (COD) Order
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
                <span>Selected Quantity:</span>
                <span id="rowQuantity" style="color: var(--accent-cyan); font-weight: 700;">1 Unit</span>
            </div>
            <div class="price-row">
                <span>Billed Base Value:</span>
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

<!-- Razorpay Standard Checkout Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    const RAZORPAY_KEY = "<?= RAZORPAY_KEY_ID ?>";
    const BASE_URL = "<?= BASE_URL ?>";
    const isFromCart = <?= $isFromCart ? 'true' : 'false' ?>;
    const cartSummary = <?= json_encode($cartSummary) ?>;

    const machineSelect = document.getElementById('machineSelect');
    const payTypeAdvance = document.getElementById('payTypeAdvance');
    const payTypeCustomAdvance = document.getElementById('payTypeCustomAdvance');
    const payTypeFull = document.getElementById('payTypeFull');
    const labelOptionAdvance = document.getElementById('labelOptionAdvance');
    const labelOptionCustomAdvance = document.getElementById('labelOptionCustomAdvance');
    const labelOptionFull = document.getElementById('labelOptionFull');
    const customAdvanceBox = document.getElementById('customAdvanceBox');
    const customAdvanceDisplay = document.getElementById('customAdvanceDisplay');
    const inputCustomAdvance = document.getElementById('inputCustomAdvance');

    // Payment Method UI
    const paymentMethodSelect = document.getElementById('paymentMethodSelect');
    const btnSubmitDirectOrder = document.getElementById('btnSubmitDirectOrder');
    const paymentAlertNotice = document.getElementById('paymentAlertNotice');
    const paymentAlertText = document.getElementById('paymentAlertText');

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

    function resetPaymentButtonState(noticeMsg = null, isError = false) {
        if (btnPayRazorpay) {
            btnPayRazorpay.disabled = false;
        }
        if (btnSubmitDirectOrder) {
            btnSubmitDirectOrder.disabled = false;
        }
        recalculate();
        if (noticeMsg && paymentAlertNotice && paymentAlertText) {
            paymentAlertNotice.style.display = 'flex';
            paymentAlertNotice.style.background = isError ? 'rgba(239, 68, 68, 0.15)' : 'rgba(251, 191, 36, 0.15)';
            paymentAlertNotice.style.border = isError ? '1.5px solid #ef4444' : '1.5px solid #f59e0b';
            paymentAlertNotice.style.color = isError ? '#f87171' : '#fbbf24';
            paymentAlertText.innerHTML = noticeMsg;
        }
    }

    function handlePaymentMethodChange() {
        const method = paymentMethodSelect ? paymentMethodSelect.value : 'online';
        document.querySelectorAll('.method-info-box').forEach(el => el.style.display = 'none');

        if (method === 'cod') {
            const codBox = document.getElementById('methodInfoCod');
            if (codBox) codBox.style.display = 'block';
            if (btnPayRazorpay) btnPayRazorpay.style.display = 'none';
            if (btnSubmitDirectOrder) {
                btnSubmitDirectOrder.style.display = 'block';
                btnSubmitDirectOrder.innerHTML = '📦 Confirm Cash on Delivery (COD) Order (ડિલિવરી સમયે રોકડા)';
                btnSubmitDirectOrder.disabled = false;
            }
        } else if (method === 'bank_transfer') {
            const bankBox = document.getElementById('methodInfoBank');
            if (bankBox) bankBox.style.display = 'block';
            if (btnPayRazorpay) btnPayRazorpay.style.display = 'none';
            if (btnSubmitDirectOrder) {
                btnSubmitDirectOrder.style.display = 'block';
                btnSubmitDirectOrder.innerHTML = '🏛️ Confirm Direct Bank Transfer (NEFT/RTGS) Order';
                btnSubmitDirectOrder.disabled = false;
            }
        } else if (method === 'cheque') {
            const chqBox = document.getElementById('methodInfoCheque');
            if (chqBox) chqBox.style.display = 'block';
            if (btnPayRazorpay) btnPayRazorpay.style.display = 'none';
            if (btnSubmitDirectOrder) {
                btnSubmitDirectOrder.style.display = 'block';
                btnSubmitDirectOrder.innerHTML = '📜 Confirm Cheque / Demand Draft Order';
                btnSubmitDirectOrder.disabled = false;
            }
        } else {
            // Online
            const onlineBox = document.getElementById('methodInfoOnline');
            if (onlineBox) onlineBox.style.display = 'block';
            if (btnPayRazorpay) btnPayRazorpay.style.display = 'block';
            if (btnSubmitDirectOrder) btnSubmitDirectOrder.style.display = 'none';
        }
        recalculate();
    }

    if (paymentMethodSelect) {
        paymentMethodSelect.addEventListener('change', handlePaymentMethodChange);
    }

    if (btnSubmitDirectOrder) {
        btnSubmitDirectOrder.addEventListener('click', function(e) {
            e.preventDefault();
            const method = paymentMethodSelect ? paymentMethodSelect.value : 'cod';
            
            if (method === 'bank_transfer') {
                const utr = document.getElementById('bankUtrInput') ? document.getElementById('bankUtrInput').value.trim() : '';
                const sender = document.getElementById('bankSenderInput') ? document.getElementById('bankSenderInput').value.trim() : '';
                if (!utr) {
                    alert('⚠️ કૃપા કરીને બેંક ટ્રાન્સફરનો UTR / ટ્રાન્ઝેક્શન રેફરન્સ નંબર દાખલ કરો (Please enter UTR / Transaction Reference Number).');
                    if (document.getElementById('bankUtrInput')) document.getElementById('bankUtrInput').focus();
                    return;
                }
                if (!sender) {
                    alert('⚠️ કૃપા કરીને તમારી રવાનગી બેંકનું નામ દાખલ કરો (Please enter Remitting Bank Name).');
                    if (document.getElementById('bankSenderInput')) document.getElementById('bankSenderInput').focus();
                    return;
                }
            } else if (method === 'cheque') {
                const chqNum = document.getElementById('chequeNumberInput') ? document.getElementById('chequeNumberInput').value.trim() : '';
                const chqBank = document.getElementById('chequeBankInput') ? document.getElementById('chequeBankInput').value.trim() : '';
                if (!chqNum) {
                    alert('⚠️ કૃપા કરીને ચેક / ડીડી નંબર દાખલ કરો (Please enter 6-digit Cheque / DD Number).');
                    if (document.getElementById('chequeNumberInput')) document.getElementById('chequeNumberInput').focus();
                    return;
                }
                if (!chqBank) {
                    alert('⚠️ કૃપા કરીને બેંક અને બ્રાન્ચનું નામ દાખલ કરો (Please enter Bank & Branch Name).');
                    if (document.getElementById('chequeBankInput')) document.getElementById('chequeBankInput').focus();
                    return;
                }
            }

            btnSubmitDirectOrder.disabled = true;
            btnSubmitDirectOrder.innerHTML = '⏳ Confirming ' + method.toUpperCase() + ' Order...';
            submitOrderAndRedirect(null, null, null, method);
        });
    }

    function getSelectedPayableAmount(basePrice) {
        if (isFromCart && cartSummary && cartSummary.items && cartSummary.items.length > 0) {
            return cartSummary.grand_total;
        }
        const qtyInput = document.getElementById('orderQuantity');
        const qty = parseInt(qtyInput ? qtyInput.value : 1) || 1;
        const totalBase = basePrice * qty;
        const fullTotal = totalBase * 1.18;
        if (payTypeAdvance && payTypeAdvance.checked) {
            return 25000 * qty;
        } else if (payTypeCustomAdvance && payTypeCustomAdvance.checked) {
            let val = parseFloat(inputCustomAdvance.value) || (90000 * qty);
            if (val > (400000 * qty)) val = 400000 * qty;
            if (val < (5000 * qty)) val = 5000 * qty;
            return val;
        } else {
            return fullTotal;
        }
    }

    // Quick chip handlers for custom advance up to ₹4,00,000
    document.querySelectorAll('.btn-advance-chip').forEach(chip => {
        chip.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.btn-advance-chip').forEach(c => {
                c.style.background = 'rgba(0,229,255,0.1)';
                c.style.color = '#00e5ff';
                c.style.fontWeight = 'normal';
            });
            this.style.background = '#0284c7';
            this.style.color = '#fff';
            this.style.fontWeight = '700';
            const val = parseFloat(this.getAttribute('data-val')) || 90000;
            if (inputCustomAdvance) inputCustomAdvance.value = val;
            if (payTypeCustomAdvance) payTypeCustomAdvance.checked = true;
            recalculate();
        });
    });

    if (inputCustomAdvance) {
        inputCustomAdvance.addEventListener('input', function() {
            let val = parseFloat(this.value) || 0;
            if (val > 400000) this.value = 400000;
            if (payTypeCustomAdvance) payTypeCustomAdvance.checked = true;
            recalculate();
        });
    }

    const btnQtyMinus = document.getElementById('btnQtyMinus');
    const btnQtyPlus = document.getElementById('btnQtyPlus');
    const orderQuantity = document.getElementById('orderQuantity');

    if (btnQtyMinus) {
        btnQtyMinus.addEventListener('click', function() {
            let v = parseInt(orderQuantity.value) || 1;
            if (v > 1) {
                orderQuantity.value = v - 1;
                recalculate();
            }
        });
    }

    if (btnQtyPlus) {
        btnQtyPlus.addEventListener('click', function() {
            let v = parseInt(orderQuantity.value) || 1;
            const max = parseInt(orderQuantity.max) || 999;
            if (v < max) {
                orderQuantity.value = v + 1;
                recalculate();
            }
        });
    }

    if (orderQuantity) {
        orderQuantity.addEventListener('input', recalculate);
        orderQuantity.addEventListener('change', recalculate);
    }

    function recalculate() {
        let basePrice = 0;
        let name = '';
        let model = '';
        let img = '';
        let pType = 'machine';
        let qty = 1;
        let totalBasePrice = 0;
        let cgst = 0;
        let sgst = 0;
        let fullTotal = 0;

        if (isFromCart && cartSummary && cartSummary.items && cartSummary.items.length > 0) {
            qty = cartSummary.total_qty;
            totalBasePrice = cartSummary.subtotal;
            cgst = cartSummary.cgst;
            sgst = cartSummary.sgst;
            fullTotal = cartSummary.grand_total;
            name = 'Combined Cart Order (' + cartSummary.items.length + ' Items)';
            model = 'CART-' + qty + 'U';
            img = cartSummary.items[0].image_url ? (BASE_URL + '/' + cartSummary.items[0].image_url) : (BASE_URL + '/assets/images/machinery/radial-drill-40-real.jpg');
            pType = 'cart';

            const rowQuantity = document.getElementById('rowQuantity');
            if (rowQuantity) rowQuantity.textContent = qty + " Units (" + cartSummary.items.length + " Items)";
            
            const stockElem = document.getElementById('summaryStockQty');
            if (stockElem) {
                stockElem.textContent = "All Items Reserved in Cart";
                stockElem.style.color = '#34d399';
            }

            if (labelOptionAdvance) labelOptionAdvance.style.display = 'none';
            if (labelOptionCustomAdvance) labelOptionCustomAdvance.style.display = 'none';
            if (payTypeFull) payTypeFull.checked = true;
            const fullTitle = document.getElementById('fullPaymentTitle');
            if (fullTitle) fullTitle.textContent = "Full Cart Total (" + qty + " Items) + 18% GST";
            const fullDesc = document.getElementById('fullPaymentDesc');
            if (fullDesc) fullDesc.textContent = "Includes all selected machines, tooling and spare parts in your cart.";
        } else {
            const opt = machineSelect.options[machineSelect.selectedIndex];
            basePrice = parseFloat(opt.getAttribute('data-price')) || 0;
            name = opt.getAttribute('data-name');
            model = opt.getAttribute('data-model');
            img = opt.getAttribute('data-image');
            pType = opt.getAttribute('data-type') || 'machine';

            const stock = parseInt(opt.getAttribute('data-stock')) || 0;
            const stockElem = document.getElementById('summaryStockQty');
            const checkoutStockCount = document.getElementById('checkoutStockCount');
            if (stockElem) {
                stockElem.textContent = stock > 0 ? (stock + " Units in Factory Stock") : "Backorder / Custom Manufacturing (5-7 Days)";
                stockElem.style.color = stock > 0 ? '#34d399' : '#f87171';
            }
            if (checkoutStockCount) {
                checkoutStockCount.textContent = (stock > 0) ? (stock + " Units") : "0 Units (Custom Build)";
                checkoutStockCount.style.color = (stock > 0) ? '#34d399' : '#f87171';
            }

            const qtyInput = document.getElementById('orderQuantity');
            qty = parseInt(qtyInput ? qtyInput.value : 1) || 1;
            if (qty < 1) qty = 1;
            if (stock > 0) {
                qtyInput.max = stock;
                if (qty > stock) {
                    qty = stock;
                    qtyInput.value = qty;
                }
            }

            // If spare part, hide Advance Token and enforce 100% full payment
            if (pType === 'part') {
                if (labelOptionAdvance) labelOptionAdvance.style.display = 'none';
                if (labelOptionCustomAdvance) labelOptionCustomAdvance.style.display = 'none';
                if (payTypeFull) payTypeFull.checked = true;
                const fullTitle = document.getElementById('fullPaymentTitle');
                if (fullTitle) fullTitle.textContent = "Full Spare Part Price (" + qty + " Unit" + (qty > 1 ? "s" : "") + ") + 18% GST";
                const fullDesc = document.getElementById('fullPaymentDesc');
                if (fullDesc) fullDesc.textContent = "Immediate dispatch from Bhavnagar plant inventory via courier/transport.";
            } else {
                if (labelOptionAdvance) labelOptionAdvance.style.display = 'flex';
                if (labelOptionCustomAdvance) labelOptionCustomAdvance.style.display = 'flex';
                const fullTitle = document.getElementById('fullPaymentTitle');
                if (fullTitle) fullTitle.textContent = "Full Payment (" + qty + " Unit" + (qty > 1 ? "s" : "") + ") + 18% GST";
                const fullDesc = document.getElementById('fullPaymentDesc');
                if (fullDesc) fullDesc.textContent = "Complete invoice payment with 100% Tax Invoice, priority dispatch & free tooling set.";
            }

            totalBasePrice = basePrice * qty;
            cgst = totalBasePrice * 0.09;
            sgst = totalBasePrice * 0.09;
            fullTotal = totalBasePrice + cgst + sgst;

            const rowQuantity = document.getElementById('rowQuantity');
            if (rowQuantity) rowQuantity.textContent = qty + (qty > 1 ? " Units" : " Unit");
        }

        if (summaryMachineImg) summaryMachineImg.src = img;
        if (summaryModelTag) summaryModelTag.textContent = model;
        if (summaryMachineName) summaryMachineName.textContent = name;

        if (rowBasePrice) rowBasePrice.textContent = isFromCart ? formatINR(totalBasePrice) : (formatINR(totalBasePrice) + " (" + qty + " × " + formatINR(basePrice) + ")");
        if (rowCgst) rowCgst.textContent = formatINR(cgst);
        if (rowSgst) rowSgst.textContent = formatINR(sgst);
        if (rowFullTotal) rowFullTotal.textContent = formatINR(fullTotal);
        if (fullPriceLabel) fullPriceLabel.textContent = formatINR(fullTotal);

        let payableNowNum = fullTotal;
        let isAdvance = false;

        if (!isFromCart && payTypeAdvance && payTypeAdvance.checked && pType !== 'part') {
            payableNowNum = 25000 * qty;
            isAdvance = true;
            if (customAdvanceBox) customAdvanceBox.style.display = 'none';
            if (labelOptionAdvance) labelOptionAdvance.classList.add('active');
            if (labelOptionCustomAdvance) labelOptionCustomAdvance.classList.remove('active');
            if (labelOptionFull) labelOptionFull.classList.remove('active');
            if (paymentPlanNotice) paymentPlanNotice.textContent = "Token Advance ₹25,000 × " + qty + " = ₹" + payableNowNum.toLocaleString('en-IN') + " (Balance ₹" + Math.max(0, fullTotal - payableNowNum).toLocaleString('en-IN') + " on dispatch)";
            if (btnPayRazorpay) btnPayRazorpay.innerHTML = "🔒 Pay Booking Advance " + formatINR(payableNowNum) + " (Razorpay / UPI)";
        } else if (!isFromCart && payTypeCustomAdvance && payTypeCustomAdvance.checked && pType !== 'part') {
            let customVal = parseFloat(inputCustomAdvance.value) || (90000 * qty);
            if (customVal > (400000 * qty)) customVal = (400000 * qty);
            if (customVal < (5000 * qty)) customVal = (5000 * qty);
            payableNowNum = customVal;
            isAdvance = true;
            if (customAdvanceBox) customAdvanceBox.style.display = 'block';
            if (customAdvanceDisplay) customAdvanceDisplay.textContent = formatINR(payableNowNum);
            if (labelOptionAdvance) labelOptionAdvance.classList.remove('active');
            if (labelOptionCustomAdvance) labelOptionCustomAdvance.classList.add('active');
            if (labelOptionFull) labelOptionFull.classList.remove('active');
            if (paymentPlanNotice) paymentPlanNotice.textContent = "Custom Online Advance ₹" + payableNowNum.toLocaleString('en-IN') + " (Balance ₹" + Math.max(0, fullTotal - payableNowNum).toLocaleString('en-IN') + " on dispatch)";
            if (btnPayRazorpay) btnPayRazorpay.innerHTML = "🔒 Pay Online Advance " + formatINR(payableNowNum) + " (Razorpay / UPI)";
        } else {
            payableNowNum = fullTotal;
            isAdvance = false;
            if (customAdvanceBox) customAdvanceBox.style.display = 'none';
            if (labelOptionAdvance) labelOptionAdvance.classList.remove('active');
            if (labelOptionCustomAdvance) labelOptionCustomAdvance.classList.remove('active');
            if (labelOptionFull) labelOptionFull.classList.add('active');
            if (paymentPlanNotice) paymentPlanNotice.textContent = isFromCart ? "100% Cart Total Payment" : ((pType === 'part') ? ("100% Spare Part Payment for " + qty + " Unit(s) + 18% GST") : ("100% Full Machine Payment for " + qty + " Unit(s) + 18% GST"));
            if (btnPayRazorpay) btnPayRazorpay.innerHTML = "🔒 Pay Full Amount " + formatINR(fullTotal) + " (Razorpay / UPI)";
        }

        if (rowPayableNow) rowPayableNow.textContent = formatINR(payableNowNum);

        // Update Direct Order button text with active total
        if (btnSubmitDirectOrder) {
            const curMethod = paymentMethodSelect ? paymentMethodSelect.value : 'cod';
            if (curMethod === 'cod') {
                btnSubmitDirectOrder.innerHTML = "📦 Confirm Cash on Delivery (" + formatINR(fullTotal) + ") Order";
            } else if (curMethod === 'bank_transfer') {
                btnSubmitDirectOrder.innerHTML = "🏛️ Confirm Direct Bank Transfer (" + formatINR(fullTotal) + ") Order";
            } else if (curMethod === 'cheque') {
                btnSubmitDirectOrder.innerHTML = "📜 Confirm Cheque / DD (" + formatINR(fullTotal) + ") Order";
            }
        }

        // Display high-value banking guidance for amounts of ₹5,00,000 to ₹10,00,000+
        const highValueBox = document.getElementById('highValueNoticeBox');
        const highValueSpan = document.getElementById('highValueAmountSpan');
        if (highValueBox) {
            const curMethod = paymentMethodSelect ? paymentMethodSelect.value : 'online';
            if (payableNowNum >= 500000 && !isAdvance && curMethod === 'online') {
                highValueBox.style.display = 'block';
                if (highValueSpan) highValueSpan.textContent = formatINR(payableNowNum);
            } else {
                highValueBox.style.display = 'none';
            }
        }
    }

    if (machineSelect) machineSelect.addEventListener('change', recalculate);
    if (payTypeAdvance) payTypeAdvance.addEventListener('change', recalculate);
    if (payTypeCustomAdvance) payTypeCustomAdvance.addEventListener('change', recalculate);
    if (payTypeFull) payTypeFull.addEventListener('change', recalculate);
    handlePaymentMethodChange();
    recalculate();

    // Central Payment Verification and Bill Dispatcher
    function submitOrderAndRedirect(paymentId, orderId, signature, paymentMethodOverride) {
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

        const chosenMethod = paymentMethodOverride || (paymentMethodSelect ? paymentMethodSelect.value : 'online');

        const postData = new FormData();
        postData.append('csrf_token', csrfToken);
        postData.append('payment_method', chosenMethod);
        postData.append('razorpay_payment_id', paymentId || '');
        postData.append('razorpay_order_id', orderId || '');
        postData.append('razorpay_signature', signature || '');
        postData.append('customer_name', name);
        postData.append('company_name', company);
        postData.append('customer_phone', phone);
        postData.append('customer_email', email);
        postData.append('customer_address', address);
        postData.append('customer_city', city);
        postData.append('customer_pincode', pincode);
        postData.append('customer_gst', gst);
        postData.append('notes', notes);
        postData.append('from_cart', isFromCart ? "1" : "0");

        if (chosenMethod === 'bank_transfer') {
            const utr = document.getElementById('bankUtrInput') ? document.getElementById('bankUtrInput').value.trim() : '';
            const sender = document.getElementById('bankSenderInput') ? document.getElementById('bankSenderInput').value.trim() : '';
            const bDate = document.getElementById('bankDateInput') ? document.getElementById('bankDateInput').value : '';
            postData.append('bank_utr', utr);
            postData.append('bank_sender_name', sender);
            postData.append('bank_transfer_date', bDate);
        } else if (chosenMethod === 'cheque') {
            const chqNum = document.getElementById('chequeNumberInput') ? document.getElementById('chequeNumberInput').value.trim() : '';
            const chqBank = document.getElementById('chequeBankInput') ? document.getElementById('chequeBankInput').value.trim() : '';
            const chqDate = document.getElementById('chequeDateInput') ? document.getElementById('chequeDateInput').value : '';
            postData.append('cheque_number', chqNum);
            postData.append('cheque_bank_name', chqBank);
            postData.append('cheque_date', chqDate);
        }

        if (isFromCart && cartSummary && cartSummary.items && cartSummary.items.length > 0) {
            postData.append('machine_id', cartSummary.items[0].id || 1);
            postData.append('machine_name', 'Combined Cart Order (' + cartSummary.items.length + ' Items)');
            postData.append('model_number', 'CART-' + cartSummary.total_qty + 'U');
            postData.append('quantity', cartSummary.total_qty);
            postData.append('payment_type', 'full_payment');
            postData.append('payable_amount', cartSummary.grand_total);
            postData.append('base_price', cartSummary.subtotal);
            postData.append('unit_price', cartSummary.subtotal / cartSummary.total_qty);
        } else {
            const opt = machineSelect.options[machineSelect.selectedIndex];
            const machineId = opt.value;
            const machineName = opt.getAttribute('data-name');
            const modelNumber = opt.getAttribute('data-model');
            const basePrice = parseFloat(opt.getAttribute('data-price')) || 0;
            const qty = parseInt(document.getElementById('orderQuantity').value) || 1;
            const totalBasePrice = basePrice * qty;
            const isAdvance = !payTypeFull.checked;
            const payableAmount = getSelectedPayableAmount(basePrice);

            postData.append('machine_id', machineId);
            postData.append('machine_name', machineName);
            postData.append('model_number', modelNumber);
            postData.append('quantity', qty);
            postData.append('payment_type', isAdvance ? 'booking_advance' : 'full_payment');
            postData.append('payable_amount', payableAmount);
            postData.append('base_price', totalBasePrice);
            postData.append('unit_price', basePrice);
        }

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
                resetPaymentButtonState('⚠️ ' + (data.message || 'Error processing order. Please retry.'), true);
            }
        })
        .catch(err => {
            resetPaymentButtonState('⚠️ Network or verification error. Please check your connection and retry.', true);
        });
    }

    // High-Value Options Switchers (Solves 10 Lakh payment limit via Token Advance or RTGS Bank Transfer)
    window.switchToAdvancePlan = function() {
        if (payTypeAdvance) {
            payTypeAdvance.checked = true;
            recalculate();
            if (paymentMethodSelect) {
                paymentMethodSelect.value = 'online';
                handlePaymentMethodChange();
            }
        }
    };

    window.switchToBankTransfer = function() {
        if (paymentMethodSelect) {
            paymentMethodSelect.value = 'bank_transfer';
            handlePaymentMethodChange();
        }
    };

    // Razorpay Official Checkout with Cryptographic Server-Side Order Generation
    if (btnPayRazorpay) {
        btnPayRazorpay.addEventListener('click', async function(e) {
            e.preventDefault();

            // Check if Razorpay script is loaded
            if (typeof Razorpay === 'undefined') {
                resetPaymentButtonState('⚠️ <strong>Banking Gateway Offline:</strong> The Razorpay checkout script could not load. Please check your internet connection, disable any adblocker, or choose <strong>Direct Bank Transfer (NEFT/RTGS)</strong> above.', true);
                return;
            }

            let machineId = 1;
            let machineName = 'Varsha Drill Machine';
            let modelNumber = 'SVEW-RD-40';
            let basePrice = 285000;
            let payableAmount = 25000;
            let isAdvance = true;

            if (isFromCart && cartSummary && cartSummary.items && cartSummary.items.length > 0) {
                machineId = cartSummary.items[0].id || 1;
                machineName = 'Combined Cart Order (' + cartSummary.items.length + ' Items)';
                modelNumber = 'CART-' + cartSummary.total_qty + 'U';
                payableAmount = cartSummary.grand_total;
                isAdvance = false;
            } else {
                const opt = machineSelect.options[machineSelect.selectedIndex];
                machineId = opt.value;
                machineName = opt.getAttribute('data-name');
                modelNumber = opt.getAttribute('data-model');
                basePrice = parseFloat(opt.getAttribute('data-price')) || 0;
                payableAmount = getSelectedPayableAmount(basePrice);
                isAdvance = !payTypeFull.checked;
            }

            // High-Value Safety Check: Single transaction limit on Razorpay/UPI is ₹5,00,000 / ₹10,00,000
            if (payableAmount >= 500000 && !isAdvance) {
                const proceedChoice = confirm("⚠️ High-Value Banking Limit Notice:\n\nIndian banks & UPI cap single online gateway transactions to ₹1,00,000 - ₹5,00,000. Online processing of " + formatINR(payableAmount) + " in one swipe may be rejected by your bank.\n\n• Click OK to pay the Booking Token Advance (₹25,000 - ₹1,00,000) online via Razorpay.\n• Click CANCEL to switch to Direct RTGS / NEFT Bank Transfer (No limit).");
                if (proceedChoice) {
                    window.switchToAdvancePlan();
                    return;
                } else {
                    window.switchToBankTransfer();
                    return;
                }
            }

            const amountInPaise = Math.round(payableAmount * 100);

            const name = document.getElementById('custName').value.trim() || 'Ramesh Patel';
            const phone = document.getElementById('custPhone').value.trim() || '+91 9824913555';
            const email = document.getElementById('custEmail').value.trim() || 'jitubhaivarsh@gmail.com';
            const csrfToken = document.getElementById('csrfToken').value;

            btnPayRazorpay.disabled = true;
            btnPayRazorpay.textContent = "Connecting to Razorpay Banking Gateway...";

            let serverOrderId = null;
            try {
                const orderFormData = new FormData();
                orderFormData.append('csrf_token', csrfToken);
                orderFormData.append('amount', payableAmount);
                orderFormData.append('machine_id', machineId);
                orderFormData.append('machine_name', machineName);
                orderFormData.append('model_number', modelNumber);
                orderFormData.append('payment_type', isAdvance ? 'booking_advance' : 'full_payment');
                orderFormData.append('customer_name', name);
                orderFormData.append('customer_phone', phone);

                const orderRes = await fetch('api/create-razorpay-order.php', {
                    method: 'POST',
                    body: orderFormData
                });
                const orderData = await orderRes.json();
                if (orderData && orderData.success && orderData.order_id) {
                    serverOrderId = orderData.order_id;
                } else if (orderData && !orderData.success && orderData.is_limit_error) {
                    resetPaymentButtonState('⚠️ <strong>Banking Limit:</strong> ' + orderData.message, true);
                    return;
                }
            } catch (errOrder) {
                console.warn("Server order fetch failed, continuing with direct checkout:", errOrder);
            }

            const options = {
                "key": RAZORPAY_KEY,
                "amount": amountInPaise,
                "currency": "INR",
                "name": "VARSHA BRAND",
                "description": (isAdvance ? "Advance Booking (" + formatINR(payableAmount) + "): " : "Full Payment: ") + modelNumber + " (" + machineName + ")",
                "image": "<?= $rzpLogoUrl ?>",
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
                        resetPaymentButtonState('⚠️ <strong>Payment Interrupted / Closed:</strong> Gateway window closed without payment. No amount was deducted and NO bill has been generated. Bill is only issued after verified payment realization.', true);
                    }
                },
                "handler": function (response) {
                    if (!response || !response.razorpay_payment_id) {
                        resetPaymentButtonState('⚠️ <strong>Payment Error:</strong> Payment confirmation missing. Bill cannot be generated without authentic payment proof.', true);
                        return;
                    }
                    submitOrderAndRedirect(
                        response.razorpay_payment_id,
                        response.razorpay_order_id || serverOrderId,
                        response.razorpay_signature,
                        'online'
                    );
                }
            };

            // Attach server-created order_id if available
            if (serverOrderId && !serverOrderId.startsWith('order_svew_')) {
                options.order_id = serverOrderId;
            }

            try {
                const rzp = new Razorpay(options);
                rzp.on('payment.failed', function (resp){
                    const reason = (resp && resp.error && resp.error.description) ? resp.error.description : 'Transaction cancelled by bank / limit exceeded';
                    resetPaymentButtonState('❌ <strong>Payment Unsuccessful:</strong> ' + reason + '. No payment was processed and NO bill has been generated. Note: For payments above ₹25,000, please select <strong>Token Booking Advance (₹25,000)</strong> or <strong>Direct RTGS/NEFT Bank Transfer</strong>.', true);
                });
                });
                rzp.open();
            } catch (err) {
                console.error("Razorpay error:", err);
                resetPaymentButtonState('⚠️ <strong>Gateway Error:</strong> ' + (err.message || 'Could not launch payment gateway.') + '<br>For amounts exceeding limits (₹5,00,000 - ₹10,00,000), please choose <strong>Token Booking Advance (₹25,000)</strong> or <strong>Direct RTGS/NEFT Bank Transfer</strong> above.', true);
            }
        });
    }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
