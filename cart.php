<?php
/**
 * JioMart-Style Industrial Shopping Cart
 * Shree Varsha Engineering Works - Varsha Brand
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/cart-helper.php';

$pdo = Database::getConnection();

// Handle direct non-AJAX actions if any
if (isset($_GET['action'])) {
    $act = $_GET['action'];
    $pId = intval($_GET['product_id'] ?? 0);
    $qty = intval($_GET['qty'] ?? 1);

    if ($act === 'add' && $pId > 0) {
        addToCart($pdo, $pId, $qty);
        header("Location: cart.php");
        exit;
    } elseif ($act === 'remove' && $pId > 0) {
        removeFromCart($pdo, $pId);
        header("Location: cart.php");
        exit;
    } elseif ($act === 'clear') {
        clearCart($pdo);
        header("Location: cart.php");
        exit;
    }
}

$cartSummary = getCartSummary($pdo);
$cartItems = $cartSummary['items'];
$custUser = $_SESSION['customer_user'] ?? null;

// Determine recommended products based on cart contents or top machinery
$firstItemId = !empty($cartItems[0]['id']) ? intval($cartItems[0]['id']) : 1;
$firstItemPrice = !empty($cartItems[0]['price']) ? floatval($cartItems[0]['price']) : 285000;
$recommendedProducts = getSmartRecommendations($pdo, $firstItemId, $firstItemPrice, 4);

$pageTitle = 'Industrial Shopping Cart (' . $cartSummary['total_qty'] . ' Items) | ' . BRAND_NAME;
$pageDesc = 'View and manage your industrial drill machine orders, genuine factory spare parts, and tooling accessories in your Varsha Brand cart.';
$currentNav = 'cart';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<style>
/* ==========================================================================
   JIOMART-INSPIRED INDUSTRIAL CART STYLING (100% RESPONSIVE)
   ========================================================================== */
.cart-page-wrapper {
    padding: 35px 0 90px;
    background: radial-gradient(circle at 50% 0%, rgba(0, 229, 255, 0.06) 0%, rgba(6, 9, 17, 0.98) 70%);
    min-height: 80vh;
}

.cart-header-strip {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    padding-bottom: 20px;
    margin-bottom: 24px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

.cart-heading-title {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 26px;
    font-weight: 800;
    color: #ffffff;
    display: flex;
    align-items: center;
    gap: 12px;
}

.cart-delivery-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(15, 23, 42, 0.85);
    border: 1px solid rgba(56, 189, 248, 0.35);
    padding: 8px 16px;
    border-radius: 9999px;
    font-size: 13px;
    color: #cbd5e1;
}

.cart-layout-grid {
    display: grid;
    grid-template-columns: 1.25fr 0.75fr;
    gap: 30px;
    align-items: flex-start;
}

@media (max-width: 1024px) {
    .cart-layout-grid {
        grid-template-columns: 1fr;
        gap: 25px;
    }
}

/* Benefit Banner (JioMart Style) */
.cart-benefit-banner {
    background: linear-gradient(90deg, rgba(5, 150, 105, 0.15) 0%, rgba(15, 23, 42, 0.8) 100%);
    border: 1px solid rgba(16, 185, 129, 0.35);
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

/* Cart Item Card */
.cart-item-card {
    background: rgba(11, 17, 32, 0.9);
    border: 1px solid rgba(0, 229, 255, 0.2);
    border-radius: 14px;
    padding: 20px;
    margin-bottom: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
    display: grid;
    grid-template-columns: 130px 1fr auto;
    gap: 20px;
    align-items: center;
    transition: border-color 0.25s, transform 0.25s;
    position: relative;
}

.cart-item-card:hover {
    border-color: rgba(0, 229, 255, 0.45);
    transform: translateY(-2px);
}

.cart-item-img-box {
    width: 130px;
    height: 120px;
    border-radius: 10px;
    background: #060911;
    border: 1px solid rgba(255, 255, 255, 0.08);
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.cart-item-img-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.3s ease;
}

.cart-item-card:hover .cart-item-img-box img {
    transform: scale(1.06);
}

.cart-item-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.cart-item-category {
    font-size: 11px;
    text-transform: uppercase;
    font-weight: 700;
    color: #94a3b8;
    letter-spacing: 0.5px;
}

.cart-item-title {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 17px;
    font-weight: 700;
    color: #ffffff;
    line-height: 1.3;
    margin: 0;
    text-decoration: none;
    transition: color 0.2s;
}

.cart-item-title:hover {
    color: var(--accent-cyan);
}

.cart-item-model-pill {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    color: var(--accent-cyan);
    background: rgba(0, 229, 255, 0.1);
    border: 1px solid rgba(0, 229, 255, 0.3);
    padding: 2px 8px;
    border-radius: 4px;
    margin-right: 8px;
}

.cart-item-stock-tag {
    font-size: 12px;
    font-weight: 700;
    color: #34d399;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    margin-top: 4px;
}

.cart-item-price-box {
    margin-top: 6px;
    display: flex;
    align-items: baseline;
    gap: 10px;
    flex-wrap: wrap;
}

.cart-unit-price {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 19px;
    font-weight: 900;
    color: #34d399;
}

.cart-mrp-price {
    font-size: 13px;
    color: #94a3b8;
    text-decoration: line-through;
}

.cart-save-pill {
    font-size: 11px;
    font-weight: 800;
    background: rgba(16, 185, 129, 0.18);
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.4);
    padding: 2px 7px;
    border-radius: 4px;
}

/* Quantity Stepper & Actions */
.cart-item-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 14px;
}

.cart-stepper-wrap {
    display: inline-flex;
    align-items: center;
    background: #060911;
    border: 1.5px solid var(--border-cyan);
    border-radius: 8px;
    overflow: hidden;
}

.cart-stepper-btn {
    background: transparent;
    border: none;
    color: #ffffff;
    width: 34px;
    height: 34px;
    font-size: 16px;
    font-weight: 800;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s, color 0.2s;
}

.cart-stepper-btn:hover {
    background: rgba(0, 229, 255, 0.2);
    color: var(--accent-cyan);
}

.cart-stepper-val {
    width: 40px;
    text-align: center;
    font-family: 'Space Grotesk', sans-serif;
    font-size: 15px;
    font-weight: 800;
    color: #38bdf8;
    border-left: 1px solid rgba(255, 255, 255, 0.1);
    border-right: 1px solid rgba(255, 255, 255, 0.1);
    height: 34px;
    line-height: 34px;
}

.cart-item-subtotal-val {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 18px;
    font-weight: 800;
    color: #ffffff;
    text-align: right;
}

.cart-btn-remove {
    background: transparent;
    border: none;
    color: #f87171;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 6px;
    transition: background 0.2s, color 0.2s;
}

.cart-btn-remove:hover {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
}

/* Bill Details / Price Summary Card */
.cart-bill-card {
    background: linear-gradient(145deg, rgba(15, 23, 42, 0.95) 0%, rgba(9, 13, 24, 0.98) 100%);
    border: 1.5px solid rgba(0, 229, 255, 0.3);
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6), 0 0 25px rgba(0, 229, 255, 0.08);
    position: sticky;
    top: 90px;
}

.bill-card-title {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 18px;
    font-weight: 800;
    color: #ffffff;
    padding-bottom: 14px;
    margin-bottom: 16px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.bill-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    font-size: 13.5px;
    color: #cbd5e1;
}

.bill-row.savings {
    color: #34d399;
    font-weight: 700;
}

.bill-divider {
    height: 1px;
    background: rgba(255, 255, 255, 0.1);
    margin: 16px 0;
}

.bill-row.grand-total {
    font-size: 19px;
    font-weight: 900;
    color: #ffffff;
    font-family: 'Space Grotesk', sans-serif;
    padding-top: 4px;
}

/* Coupon Box */
.coupon-box {
    margin: 16px 0;
    padding: 12px 14px;
    background: rgba(6, 9, 17, 0.8);
    border: 1px dashed rgba(0, 229, 255, 0.4);
    border-radius: 10px;
}

.coupon-input-wrap {
    display: flex;
    gap: 8px;
    margin-top: 8px;
}

.coupon-input {
    flex: 1;
    background: #0f172a;
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 6px;
    padding: 8px 12px;
    color: #fff;
    font-size: 13px;
    font-family: 'Space Grotesk', monospace;
    text-transform: uppercase;
}

.coupon-btn {
    background: rgba(0, 229, 255, 0.15);
    border: 1px solid var(--accent-cyan);
    color: var(--accent-cyan);
    font-weight: 700;
    font-size: 12px;
    padding: 0 14px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}

.coupon-btn:hover {
    background: var(--accent-cyan);
    color: #060911;
}

/* Payment Mode Selector */
.payment-mode-box {
    background: rgba(6, 9, 17, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    padding: 14px;
    margin: 18px 0;
}

.payment-mode-label {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    cursor: pointer;
    margin-bottom: 10px;
    font-size: 13px;
}

.payment-mode-label:last-child {
    margin-bottom: 0;
}

.btn-proceed-checkout {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 15px;
    font-family: 'Space Grotesk', sans-serif;
    font-size: 16px;
    font-weight: 800;
    color: #ffffff;
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    border: 1.5px solid #34d399;
    border-radius: 10px;
    text-decoration: none;
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.35);
    cursor: pointer;
    transition: all 0.25s ease;
}

.btn-proceed-checkout:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(16, 185, 129, 0.5);
    color: #ffffff;
}

/* Empty Cart State */
.empty-cart-card {
    background: rgba(11, 17, 32, 0.9);
    border: 1.5px dashed rgba(0, 229, 255, 0.3);
    border-radius: 16px;
    padding: 60px 20px;
    text-align: center;
    max-width: 650px;
    margin: 30px auto;
}

.empty-cart-icon-circle {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: rgba(0, 229, 255, 0.1);
    border: 2px solid var(--accent-cyan);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 38px;
    margin: 0 auto 20px;
    box-shadow: 0 0 30px rgba(0, 229, 255, 0.2);
}

/* Mobile Sticky Bottom Floating Bar (JioMart Style) */
.cart-mobile-sticky-bar {
    display: none;
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(6, 10, 20, 0.98);
    backdrop-filter: blur(12px);
    border-top: 1.5px solid var(--accent-cyan);
    padding: 12px 16px;
    z-index: 9999;
    box-shadow: 0 -8px 25px rgba(0, 0, 0, 0.8);
    align-items: center;
    justify-content: space-between;
}

@media (max-width: 768px) {
    .cart-mobile-sticky-bar {
        display: flex;
    }
    .cart-item-card {
        grid-template-columns: 90px 1fr;
        gap: 14px;
        padding: 14px;
    }
    .cart-item-img-box {
        width: 90px;
        height: 90px;
    }
    .cart-item-actions {
        grid-column: 1 / -1;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        padding-top: 10px;
        margin-top: 6px;
    }
    .cart-item-title {
        font-size: 15px;
    }
    .cart-unit-price {
        font-size: 16px;
    }
}
</style>

<div class="cart-page-wrapper">
    <div class="container">
        
        <!-- Header Strip -->
        <div class="cart-header-strip">
            <div>
                <div style="font-size: 12px; font-weight: 700; color: #38bdf8; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 4px;">
                    INDUSTRIAL ORDER RESERVATION
                </div>
                <h1 class="cart-heading-title">
                    <span>🛒 My Cart</span>
                    <span style="font-size: 15px; font-weight: 700; color: #38bdf8; background: rgba(56, 189, 248, 0.15); border: 1px solid rgba(56, 189, 248, 0.3); padding: 2px 10px; border-radius: 9999px;">
                        <span id="headerCartCount"><?= $cartSummary['total_qty'] ?></span> Items
                    </span>
                </h1>
            </div>

            <!-- Customer Dispatch Address Bar -->
            <div class="cart-delivery-badge">
                <span style="color: #34d399;">📍 Deliver to:</span>
                <strong style="color: #fff;">
                    <?= !empty($custUser['city']) ? e($custUser['city']) : 'Bhavnagar / All India' ?>
                    <?= !empty($custUser['pincode']) ? ' (' . e($custUser['pincode']) . ')' : '' ?>
                </strong>
                <?php if (empty($custUser)): ?>
                    <button type="button" onclick="openCustomerAuthModal('Sign in to save your factory dispatch address.')" style="background: none; border: none; color: var(--accent-cyan); font-size: 12px; font-weight: 700; cursor: pointer; text-decoration: underline; margin-left: 4px;">
                        Sign In
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <?php if (empty($cartItems)): ?>
            <!-- 1. EMPTY CART STATE -->
            <div class="empty-cart-card">
                <div class="empty-cart-icon-circle">🛒</div>
                <h2 style="font-family: 'Space Grotesk', sans-serif; font-size: 24px; font-weight: 800; color: #fff; margin-bottom: 8px;">
                    તમારું કાર્ટ ખાલી છે (Your Cart is Empty)
                </h2>
                <p style="color: #94a3b8; font-size: 14.5px; max-width: 480px; margin: 0 auto 24px; line-height: 1.5;">
                    તમે હજુ સુધી કોઈ ડ્રિલ મશીન કે સ્પેર પાર્ટ્સ કાર્ટમાં ઉમેર્યા નથી. હેવી-ડ્યુટી રેડિયલ ડ્રિલ અથવા ઓરિજિનલ પાર્ટ્સ જોવા માટે નીચે ક્લિક કરો.
                </p>
                <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                    <a href="<?= BASE_URL ?>/machines.php" class="btn btn-primary" style="padding: 12px 24px; font-weight: 700; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color: #060911;">
                        ⚙️ Browse All Drill Machines
                    </a>
                    <a href="<?= BASE_URL ?>/catalog.php" class="btn btn-outline" style="padding: 12px 24px; font-weight: 700; border-color: var(--accent-cyan); color: var(--accent-cyan);">
                        📖 View Technical Catalog
                    </a>
                </div>
            </div>

        <?php else: ?>
            <!-- 2. ACTIVE CART WITH ITEMS -->
            <div class="cart-layout-grid">
                
                <!-- Left: Items List -->
                <div>
                    <!-- Benefit Strip -->
                    <div class="cart-benefit-banner">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="font-size: 22px;">🛡️</span>
                            <div>
                                <div style="font-weight: 700; color: #34d399; font-size: 13.5px;">Direct Factory Warranty &amp; Insured Road Transit</div>
                                <div style="color: #94a3b8; font-size: 11.5px;">Bhavnagar Works • 1-Year Comprehensive Warranty • 100% Genuine Spare Parts</div>
                            </div>
                        </div>
                        <a href="<?= BASE_URL ?>/machines.php" style="color: var(--accent-cyan); font-size: 12px; font-weight: 700; text-decoration: none;">
                            + Add More Machines
                        </a>
                    </div>

                    <!-- Items Container -->
                    <div id="cartItemsContainer">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="cart-item-card" id="cartItemRow_<?= $item['id'] ?>" data-product-id="<?= $item['id'] ?>">
                                <!-- Image -->
                                <div class="cart-item-img-box">
                                    <a href="<?= BASE_URL ?>/machine-detail.php?id=<?= $item['id'] ?>">
                                        <img src="<?= assetUrl($item['image_url']) ?>" alt="<?= e($item['name']) ?>">
                                    </a>
                                </div>

                                <!-- Details -->
                                <div class="cart-item-info">
                                    <div class="cart-item-category"><?= e($item['category_name']) ?></div>
                                    <a href="<?= BASE_URL ?>/machine-detail.php?id=<?= $item['id'] ?>" class="cart-item-title">
                                        <?= e($item['name']) ?>
                                    </a>
                                    
                                    <div style="margin-top: 4px;">
                                        <span class="cart-item-model-pill"><?= e($item['model_number']) ?></span>
                                        <?php if (!empty($item['drilling_capacity'])): ?>
                                            <span style="font-size: 11px; color: #94a3b8;"><?= e($item['drilling_capacity']) ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="cart-item-stock-tag">
                                        <span style="display:inline-block; width:7px; height:7px; border-radius:50%; background:#10b981;"></span>
                                        <span>In Factory Stock (Ready for Dispatch)</span>
                                    </div>

                                    <div class="cart-item-price-box">
                                        <span class="cart-unit-price">₹ <?= number_format($item['unit_price'], 2) ?></span>
                                        <span class="cart-mrp-price">₹ <?= number_format($item['mrp_price'], 2) ?></span>
                                        <span class="cart-save-pill">Save 15% Factory Direct</span>
                                    </div>
                                </div>

                                <!-- Quantity Stepper & Subtotal -->
                                <div class="cart-item-actions">
                                    <div class="cart-item-subtotal-val" id="itemSubtotal_<?= $item['id'] ?>">
                                        ₹ <?= number_format($item['subtotal'], 2) ?>
                                    </div>

                                    <div class="cart-stepper-wrap">
                                        <button type="button" class="cart-stepper-btn" onclick="updateItemQty(<?= $item['id'] ?>, -1)" title="Decrease Quantity">−</button>
                                        <div class="cart-stepper-val" id="itemQtyDisplay_<?= $item['id'] ?>"><?= $item['quantity'] ?></div>
                                        <button type="button" class="cart-stepper-btn" onclick="updateItemQty(<?= $item['id'] ?>, 1)" title="Increase Quantity">+</button>
                                    </div>

                                    <button type="button" class="cart-btn-remove" onclick="removeItem(<?= $item['id'] ?>, '<?= addslashes(e($item['name'])) ?>')">
                                        <span>🗑 Remove</span>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Clear Cart & Continue Shopping Strip -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px; padding-top: 14px; border-top: 1px solid rgba(255,255,255,0.08);">
                        <a href="<?= BASE_URL ?>/machines.php" style="color: #38bdf8; font-size: 13px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                            <span>← Continue Shopping (અન્ય મશીનો જુઓ)</span>
                        </a>
                        <button type="button" onclick="clearEntireCart()" style="background: none; border: none; color: #f87171; font-size: 12px; font-weight: 600; cursor: pointer;">
                            Clear All Items
                        </button>
                    </div>
                </div>

                <!-- Right: Price Details / Bill Summary (Sticky) -->
                <div>
                    <div class="cart-bill-card">
                        <div class="bill-card-title">
                            <span>PRICE DETAILS (બિલ વિગત)</span>
                            <span style="font-size: 12px; color: #94a3b8; font-weight: 600;">HSN: 8459</span>
                        </div>

                        <!-- Price Breakdown Rows -->
                        <div class="bill-row">
                            <span>Total MRP Value (<span id="summaryTotalUnits"><?= $cartSummary['total_qty'] ?></span> Items)</span>
                            <span id="summaryTotalMrp">₹ <?= number_format($cartSummary['total_mrp'], 2) ?></span>
                        </div>

                        <div class="bill-row savings">
                            <span>Factory Direct Savings (15% OFF)</span>
                            <span id="summaryDiscount">− ₹ <?= number_format($cartSummary['factory_discount'], 2) ?></span>
                        </div>

                        <div class="bill-row">
                            <span>Taxable Base Value</span>
                            <span id="summaryTaxable" style="font-weight: 700; color: #fff;">₹ <?= number_format($cartSummary['taxable_amount'], 2) ?></span>
                        </div>

                        <div class="bill-row">
                            <span>CGST (9%)</span>
                            <span id="summaryCgst">₹ <?= number_format($cartSummary['cgst_amount'], 2) ?></span>
                        </div>

                        <div class="bill-row">
                            <span>SGST (9%)</span>
                            <span id="summarySgst">₹ <?= number_format($cartSummary['sgst_amount'], 2) ?></span>
                        </div>

                        <div class="bill-row">
                            <span>Transit Insurance &amp; Freight</span>
                            <span style="color: #34d399; font-weight: 700;">FREE (Included)</span>
                        </div>

                        <!-- Extra Coupon Code Row if applied -->
                        <div class="bill-row savings" id="couponDiscountRow" style="display: none;">
                            <span>Promo Discount (<span id="couponCodeLabel">VARSHA2026</span>)</span>
                            <span id="couponDiscountAmount">− ₹ 5,000.00</span>
                        </div>

                        <div class="bill-divider"></div>

                        <!-- Grand Total -->
                        <div class="bill-row grand-total">
                            <span>Total Amount Payable</span>
                            <span id="summaryGrandTotal" style="color: #34d399;">₹ <?= number_format($cartSummary['grand_total'], 2) ?></span>
                        </div>
                        <div style="font-size: 11px; color: #94a3b8; text-align: right; margin-top: -6px; margin-bottom: 12px;">
                            (Includes 18% GST with Official Tax Invoice)
                        </div>

                        <!-- Coupon Box -->
                        <div class="coupon-box">
                            <div style="font-size: 12px; font-weight: 700; color: #38bdf8; display: flex; align-items: center; justify-content: space-between;">
                                <span>🎟️ Have a Promotional Coupon?</span>
                                <span style="font-size: 10px; color: #34d399; font-weight: 800;">CODE: VARSHA2026</span>
                            </div>
                            <div class="coupon-input-wrap">
                                <input type="text" id="couponInput" class="coupon-input" placeholder="e.g. VARSHA2026" value="VARSHA2026">
                                <button type="button" class="coupon-btn" onclick="applyCouponCode()">Apply</button>
                            </div>
                            <div id="couponStatusMsg" style="font-size: 11px; color: #34d399; margin-top: 5px;"></div>
                        </div>

                        <!-- Payment Plan Selector -->
                        <div class="payment-mode-box">
                            <div style="font-size: 12px; font-weight: 800; color: #fff; margin-bottom: 8px;">
                                💳 SELECT PAYMENT OPTION:
                            </div>

                            <?php if ($cartSummary['has_machine']): ?>
                                <label class="payment-mode-label">
                                    <input type="radio" name="cart_payment_plan" id="planAdvance" value="advance" checked onchange="updatePaymentPlanDisplay()">
                                    <div>
                                        <strong style="color: #facc15;">Online Booking Advance Token: ₹ <?= number_format($cartSummary['advance_amount'], 2) ?></strong>
                                        <div style="font-size: 11px; color: #94a3b8;">Reserve serial chassis now. Balance payable upon factory dispatch.</div>
                                    </div>
                                </label>
                            <?php endif; ?>

                            <label class="payment-mode-label">
                                <input type="radio" name="cart_payment_plan" id="planFull" value="full" <?= !$cartSummary['has_machine'] ? 'checked' : '' ?> onchange="updatePaymentPlanDisplay()">
                                <div>
                                    <strong style="color: #34d399;">100% Full Payment: <span id="lblFullPaymentAmt">₹ <?= number_format($cartSummary['grand_total'], 2) ?></span></strong>
                                    <div style="font-size: 11px; color: #94a3b8;">Instant clearance &amp; fast-track wooden box crating.</div>
                                </div>
                            </label>
                        </div>

                        <!-- Proceed Button -->
                        <a id="btnProceedToCheckout" href="<?= BASE_URL ?>/checkout.php?from_cart=1&plan=advance" class="btn-proceed-checkout">
                            <span>🔒 PROCEED TO BUY / CHECKOUT</span>
                            <span style="font-size: 18px;">➔</span>
                        </a>

                        <!-- Security Badges -->
                        <div style="margin-top: 18px; padding-top: 14px; border-top: 1px solid rgba(255,255,255,0.08); display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 11px; color: #94a3b8;">
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span style="color: #34d399;">✔</span> 100% Razorpay Secure
                            </div>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span style="color: #34d399;">✔</span> GST Tax Input Credit
                            </div>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span style="color: #34d399;">✔</span> 1-Year SVEW Warranty
                            </div>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span style="color: #34d399;">✔</span> Bhavnagar Dispatch
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- SMART RECOMMENDATIONS SECTION (Price & Product Based) -->
        <?php if (!empty($recommendedProducts)): ?>
            <div style="margin-top: 60px; padding-top: 35px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
                    <div>
                        <div style="font-size: 11px; font-weight: 800; color: var(--accent-cyan); text-transform: uppercase; letter-spacing: 1px;">
                            RECOMMENDED INDUSTRIAL EQUIPMENT
                        </div>
                        <h2 style="font-family: 'Space Grotesk', sans-serif; font-size: 22px; font-weight: 800; color: #fff; margin: 4px 0 0;">
                            આ બજેટ અને કેટેગરીમાં અન્ય ભલામણ કરેલ પ્રોડક્ટ્સ
                        </h2>
                    </div>
                    <a href="<?= BASE_URL ?>/machines.php" style="color: #38bdf8; font-size: 13px; font-weight: 700; text-decoration: none;">
                        View All Range →
                    </a>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px;">
                    <?php foreach ($recommendedProducts as $rec): ?>
                        <div style="background: rgba(11, 17, 32, 0.9); border: 1px solid rgba(0, 229, 255, 0.2); border-radius: 12px; padding: 14px; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.25s;" onmouseover="this.style.borderColor='var(--accent-cyan)'" onmouseout="this.style.borderColor='rgba(0, 229, 255, 0.2)'">
                            <div>
                                <div style="height: 150px; border-radius: 8px; overflow: hidden; background: #060911; margin-bottom: 12px;">
                                    <a href="<?= BASE_URL ?>/machine-detail.php?id=<?= $rec['id'] ?>">
                                        <img src="<?= assetUrl($rec['image_url']) ?>" alt="<?= e($rec['name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    </a>
                                </div>
                                <div style="font-size: 10.5px; font-weight: 700; color: var(--accent-cyan); text-transform: uppercase;">
                                    <?= e($rec['category_name']) ?>
                                </div>
                                <a href="<?= BASE_URL ?>/machine-detail.php?id=<?= $rec['id'] ?>" style="font-family: 'Space Grotesk', sans-serif; font-size: 14.5px; font-weight: 700; color: #fff; text-decoration: none; display: block; margin: 4px 0 6px; line-height: 1.3;">
                                    <?= e($rec['name']) ?>
                                </a>
                                <div style="font-size: 12px; color: #94a3b8; margin-bottom: 8px;">
                                    Model: <?= e($rec['model_number']) ?>
                                </div>
                            </div>

                            <div>
                                <div style="font-family: 'Space Grotesk', sans-serif; font-size: 17px; font-weight: 800; color: #34d399; margin-bottom: 10px;">
                                    ₹ <?= number_format($rec['price'], 2) ?>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                    <button type="button" class="btn btn-outline" onclick="window.addToCart(<?= $rec['id'] ?>, 1, this)" style="padding: 7px; font-size: 11.5px; font-weight: 700; border-color: var(--accent-cyan); color: var(--accent-cyan);">
                                        🛒 Add to Cart
                                    </button>
                                    <a href="<?= BASE_URL ?>/machine-detail.php?id=<?= $rec['id'] ?>" class="btn btn-primary" style="padding: 7px; font-size: 11.5px; font-weight: 700; text-align: center; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color: #060911;">
                                        View Specs
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<!-- Mobile Sticky Bottom Floating Bar (JioMart Style) -->
<?php if (!empty($cartItems)): ?>
    <div class="cart-mobile-sticky-bar">
        <div>
            <div style="font-size: 10px; color: #94a3b8; text-transform: uppercase; font-weight: 700;">Total Payable</div>
            <div id="mobileBarTotal" style="font-family: 'Space Grotesk', sans-serif; font-size: 18px; font-weight: 900; color: #34d399;">
                ₹ <?= number_format($cartSummary['grand_total'], 2) ?>
            </div>
        </div>
        <a id="btnMobileProceed" href="<?= BASE_URL ?>/checkout.php?from_cart=1&plan=advance" class="btn btn-primary" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399; font-weight: 800; font-size: 14px; padding: 11px 20px;">
            Proceed to Buy ➔
        </a>
    </div>
<?php endif; ?>

<script>
// Dynamic Cart Actions & AJAX Updates
let currentGrandTotal = <?= floatval($cartSummary['grand_total']) ?>;
let currentAdvanceTotal = <?= floatval($cartSummary['advance_amount']) ?>;
let hasMachineInCart = <?= $cartSummary['has_machine'] ? 'true' : 'false' ?>;
let appliedCouponDiscount = 0;

function formatMoney(amount) {
    return '₹ ' + parseFloat(amount).toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function updatePaymentPlanDisplay() {
    const planAdvance = document.getElementById('planAdvance');
    const isAdvance = planAdvance && planAdvance.checked;
    const btnProceed = document.getElementById('btnProceedToCheckout');
    const btnMobile = document.getElementById('btnMobileProceed');
    const planParam = isAdvance ? 'advance' : 'full';

    const url = '<?= BASE_URL ?>/checkout.php?from_cart=1&plan=' + planParam + (appliedCouponDiscount > 0 ? '&coupon=VARSHA2026' : '');
    if (btnProceed) btnProceed.href = url;
    if (btnMobile) btnMobile.href = url;
}

function applyCouponCode() {
    const input = document.getElementById('couponInput');
    const msg = document.getElementById('couponStatusMsg');
    const row = document.getElementById('couponDiscountRow');
    const discountEl = document.getElementById('couponDiscountAmount');
    const grandTotalEl = document.getElementById('summaryGrandTotal');
    const mobileTotalEl = document.getElementById('mobileBarTotal');
    const lblFull = document.getElementById('lblFullPaymentAmt');

    if (!input) return;
    const code = input.value.trim().toUpperCase();

    if (code === 'VARSHA2026' || code === 'FIRST5000') {
        appliedCouponDiscount = 5000.00;
        if (msg) msg.textContent = '✔ Coupon ' + code + ' Applied! Extra ₹5,000 Factory Discount.';
        if (row) row.style.display = 'flex';
        if (discountEl) discountEl.textContent = '− ' + formatMoney(appliedCouponDiscount);

        const newTotal = Math.max(0, currentGrandTotal - appliedCouponDiscount);
        if (grandTotalEl) grandTotalEl.textContent = formatMoney(newTotal);
        if (mobileTotalEl) mobileTotalEl.textContent = formatMoney(newTotal);
        if (lblFull) lblFull.textContent = formatMoney(newTotal);
        updatePaymentPlanDisplay();
    } else {
        alert('Invalid coupon code. Try VARSHA2026 for ₹5,000 off.');
    }
}

// Update Item Quantity (+/-) via AJAX
function updateItemQty(productId, delta) {
    const qtyDisplay = document.getElementById('itemQtyDisplay_' + productId);
    if (!qtyDisplay) return;

    let currentQty = parseInt(qtyDisplay.textContent) || 1;
    let newQty = currentQty + delta;

    if (newQty <= 0) {
        removeItem(productId);
        return;
    }

    qtyDisplay.textContent = '...';

    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('product_id', productId);
    formData.append('quantity', newQty);

    fetch('api/cart.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && data.summary) {
            qtyDisplay.textContent = newQty;
            reflectCartSummary(data.summary, productId);
        } else {
            qtyDisplay.textContent = currentQty;
            alert(data.message || 'Error updating quantity');
        }
    })
    .catch(err => {
        qtyDisplay.textContent = currentQty;
        console.error(err);
    });
}

// Remove Item from Cart
function removeItem(productId, productName) {
    if (!confirm('Are you sure you want to remove this item from your cart?')) {
        return;
    }

    const row = document.getElementById('cartItemRow_' + productId);
    if (row) {
        row.style.opacity = '0.3';
    }

    const formData = new FormData();
    formData.append('action', 'remove');
    formData.append('product_id', productId);

    fetch('api/cart.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            if (row) row.remove();
            if (data.summary.total_qty === 0) {
                window.location.reload();
            } else {
                reflectCartSummary(data.summary);
            }
        }
    })
    .catch(err => console.error(err));
}

// Clear Entire Cart
function clearEntireCart() {
    if (!confirm('Are you sure you want to clear your entire cart?')) {
        return;
    }

    const formData = new FormData();
    formData.append('action', 'clear');

    fetch('api/cart.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        window.location.reload();
    });
}

// Reflect Updated Cart Totals on UI
function reflectCartSummary(summary, activeProductId) {
    currentGrandTotal = summary.grand_total;
    currentAdvanceTotal = summary.advance_amount;
    hasMachineInCart = summary.has_machine;

    // Update Header Counts
    const headerCount = document.getElementById('headerCartCount');
    if (headerCount) headerCount.textContent = summary.total_qty;

    // Update Nav Badges
    document.querySelectorAll('.cart-badge-count').forEach(el => {
        el.textContent = summary.total_qty;
        el.style.display = summary.total_qty > 0 ? 'inline-flex' : 'none';
    });

    // Update Bill Details
    const unitsEl = document.getElementById('summaryTotalUnits');
    const mrpEl = document.getElementById('summaryTotalMrp');
    const discountEl = document.getElementById('summaryDiscount');
    const taxableEl = document.getElementById('summaryTaxable');
    const cgstEl = document.getElementById('summaryCgst');
    const sgstEl = document.getElementById('summarySgst');
    const grandTotalEl = document.getElementById('summaryGrandTotal');
    const mobileTotalEl = document.getElementById('mobileBarTotal');
    const lblFull = document.getElementById('lblFullPaymentAmt');

    if (unitsEl) unitsEl.textContent = summary.total_qty;
    if (mrpEl) mrpEl.textContent = formatMoney(summary.total_mrp);
    if (discountEl) discountEl.textContent = '− ' + formatMoney(summary.factory_discount);
    if (taxableEl) taxableEl.textContent = formatMoney(summary.taxable_amount);
    if (cgstEl) cgstEl.textContent = formatMoney(summary.cgst_amount);
    if (sgstEl) sgstEl.textContent = formatMoney(summary.sgst_amount);

    const adjustedGrandTotal = Math.max(0, summary.grand_total - appliedCouponDiscount);
    if (grandTotalEl) grandTotalEl.textContent = formatMoney(adjustedGrandTotal);
    if (mobileTotalEl) mobileTotalEl.textContent = formatMoney(adjustedGrandTotal);
    if (lblFull) lblFull.textContent = formatMoney(adjustedGrandTotal);

    // Update item specific subtotal if provided
    if (activeProductId && summary.items) {
        const itemObj = summary.items.find(i => i.id == activeProductId);
        if (itemObj) {
            const subEl = document.getElementById('itemSubtotal_' + activeProductId);
            if (subEl) subEl.textContent = formatMoney(itemObj.subtotal);
        }
    }

    updatePaymentPlanDisplay();
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
