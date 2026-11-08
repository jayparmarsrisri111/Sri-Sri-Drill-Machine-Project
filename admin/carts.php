<?php
/**
 * Admin - User-wise Live Shopping Carts Management
 * Shree Varsha Engineering Works - Varsha Brand
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

if (empty($_SESSION['admin_user'])) {
    header("Location: index.php");
    exit;
}

$adminUser = $_SESSION['admin_user'];
$activeTab = 'carts';
$adminTitle = 'Customer Carts & Live Shopping Sessions';

$pdo = Database::getConnection();
$message = '';
$messageType = 'success';

// Handle Admin Clear / Delete Actions
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action'])) {
    $act = $_POST['action'];
    if ($act === 'delete_user_cart') {
        $sid = trim($_POST['session_id'] ?? '');
        $cid = intval($_POST['customer_id'] ?? 0);
        if ($cid > 0) {
            $del = $pdo->prepare("DELETE FROM cart_items WHERE customer_id = :cid");
            $del->execute([':cid' => $cid]);
            $message = "Customer #{$cid} cart cleared successfully.";
        } elseif (!empty($sid)) {
            $del = $pdo->prepare("DELETE FROM cart_items WHERE session_id = :sid");
            $del->execute([':sid' => $sid]);
            $message = "Guest session cart cleared successfully.";
        }
    } elseif ($act === 'delete_single_item') {
        $itemId = intval($_POST['item_id'] ?? 0);
        if ($itemId > 0) {
            $del = $pdo->prepare("DELETE FROM cart_items WHERE id = :id");
            $del->execute([':id' => $itemId]);
            $message = "Cart item removed successfully.";
        }
    }
}

// Search & Status Filter
$search = trim($_GET['q'] ?? '');
$statusFilter = trim($_GET['status'] ?? 'all'); // 'all', 'active', 'abandoned'

// Fetch all cart items joined with customer and product data
$rawItems = [];
try {
    $stmt = $pdo->query("
        SELECT ci.*, 
               p.name as product_name, p.model_number, p.price, p.image_url, p.product_type, p.stock_quantity,
               c.name as category_name,
               cust.full_name as customer_name, cust.company_name, cust.phone as customer_phone, 
               cust.email as customer_email, cust.city as customer_city, cust.gstin as customer_gst
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN customers cust ON ci.customer_id = cust.id
        ORDER BY ci.updated_at DESC, ci.id DESC
    ");
    $rawItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    //
}

// Group items by user key (customer_id if available, otherwise session_id)
$groupedCarts = [];
$totalCartValue = 0;
$totalItemsInCarts = 0;
$activeCartsCount = 0;
$abandonedCartsCount = 0;

$now = time();

foreach ($rawItems as $item) {
    $userKey = !empty($item['customer_id']) ? ('cust_' . $item['customer_id']) : ('sess_' . $item['session_id']);

    if (!isset($groupedCarts[$userKey])) {
        // Calculate status: active if updated within last 2 hours (7200 seconds)
        $updatedTime = strtotime($item['updated_at'] ?? $item['created_at']);
        $timeDiff = $now - $updatedTime;
        $isActive = ($timeDiff <= 7200);

        if ($isActive) {
            $activeCartsCount++;
        } else {
            $abandonedCartsCount++;
        }

        $groupedCarts[$userKey] = [
            'user_key'        => $userKey,
            'customer_id'     => $item['customer_id'],
            'session_id'      => $item['session_id'],
            'user_ip'         => $item['user_ip'] ?? '127.0.0.1',
            'user_agent'      => $item['user_agent'] ?? '',
            'customer_name'   => $item['customer_name'] ?? 'Guest Industrial Buyer',
            'company_name'    => $item['company_name'] ?? '',
            'customer_phone'  => $item['customer_phone'] ?? '',
            'customer_email'  => $item['customer_email'] ?? '',
            'customer_city'   => $item['customer_city'] ?? 'Bhavnagar Region',
            'customer_gst'    => $item['customer_gst'] ?? '',
            'is_registered'   => !empty($item['customer_id']),
            'is_active'       => $isActive,
            'last_updated'    => $item['updated_at'] ?? $item['created_at'],
            'first_added'     => $item['created_at'],
            'items'           => [],
            'cart_total'      => 0.0,
            'total_units'     => 0
        ];
    }

    $unitPrice = floatval($item['price'] ?? 0);
    $qty = intval($item['quantity']);
    $itemSubtotal = $unitPrice * $qty;

    $item['unit_price'] = $unitPrice;
    $item['subtotal'] = $itemSubtotal;

    $groupedCarts[$userKey]['items'][] = $item;
    $groupedCarts[$userKey]['cart_total'] += $itemSubtotal;
    $groupedCarts[$userKey]['total_units'] += $qty;

    $totalCartValue += $itemSubtotal;
    $totalItemsInCarts += $qty;
}

// Filter carts if search or statusFilter applied
$filteredCarts = [];
foreach ($groupedCarts as $key => $cart) {
    if ($statusFilter === 'active' && !$cart['is_active']) continue;
    if ($statusFilter === 'abandoned' && $cart['is_active']) continue;

    if (!empty($search)) {
        $searchLower = strtolower($search);
        $match = false;
        if (stripos($cart['customer_name'], $searchLower) !== false) $match = true;
        if (stripos($cart['company_name'], $searchLower) !== false) $match = true;
        if (stripos($cart['customer_phone'], $searchLower) !== false) $match = true;
        if (stripos($cart['customer_email'], $searchLower) !== false) $match = true;
        if (stripos($cart['customer_city'], $searchLower) !== false) $match = true;
        if (stripos($cart['session_id'], $searchLower) !== false) $match = true;

        foreach ($cart['items'] as $it) {
            if (stripos($it['product_name'], $searchLower) !== false) $match = true;
            if (stripos($it['model_number'], $searchLower) !== false) $match = true;
        }

        if (!$match) continue;
    }

    $filteredCarts[$key] = $cart;
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<div class="admin-content-area" style="padding: 25px 30px; background: #060911; min-height: 100vh;">

    <!-- Top Breadcrumb & Title -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; margin-bottom: 24px;">
        <div>
            <div style="font-size: 11px; text-transform: uppercase; font-weight: 800; color: var(--accent-cyan); letter-spacing: 1px;">
                CUSTOMER CART MONITORING
            </div>
            <h1 style="font-family: 'Space Grotesk', sans-serif; font-size: 26px; font-weight: 800; color: #ffffff; margin: 4px 0 0; display: flex; align-items: center; gap: 10px;">
                <span>🛒 Live Customer Carts (યુઝર વાઇઝ કાર્ટ ડેશબોર્ડ)</span>
            </h1>
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="<?= BASE_URL ?>/cart.php" target="_blank" class="btn btn-outline" style="border-color: #f59e0b; color: #fbbf24; font-size: 12.5px; font-weight: 700; text-decoration: none;">
                🛒 Open Frontend Cart
            </a>
            <a href="<?= BASE_URL ?>/admin/orders.php" class="btn btn-primary" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399; font-size: 12.5px; font-weight: 700; text-decoration: none;">
                💳 View Converted Orders
            </a>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <div style="background: rgba(16, 185, 129, 0.15); border: 1.5px solid #10b981; color: #34d399; padding: 12px 18px; border-radius: 8px; margin-bottom: 22px; font-weight: 700;">
            ✔ <?= e($message) ?>
        </div>
    <?php endif; ?>

    <!-- 4 High-Impact Stat Cards -->
    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 26px;">
        <!-- Card 1: Total Carts -->
        <div style="background: #0f172a; border: 1px solid rgba(0, 229, 255, 0.25); border-radius: 12px; padding: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.5);">
            <div style="font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 700;">Active Sessions In Cart</div>
            <div style="font-family: 'Space Grotesk', sans-serif; font-size: 28px; font-weight: 900; color: #00e5ff; margin-top: 6px;">
                <?= count($groupedCarts) ?> Carts
            </div>
            <div style="font-size: 11.5px; color: #38bdf8; margin-top: 4px;">
                Total <?= $totalItemsInCarts ?> machine units &amp; spare parts
            </div>
        </div>

        <!-- Card 2: Potential Cart Revenue -->
        <div style="background: #0f172a; border: 1px solid rgba(52, 211, 153, 0.3); border-radius: 12px; padding: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.5);">
            <div style="font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 700;">Potential Cart Revenue</div>
            <div style="font-family: 'Space Grotesk', sans-serif; font-size: 26px; font-weight: 900; color: #34d399; margin-top: 6px;">
                ₹ <?= number_format($totalCartValue, 2) ?>
            </div>
            <div style="font-size: 11.5px; color: #94a3b8; margin-top: 4px;">
                Pipeline machinery orders in progress
            </div>
        </div>

        <!-- Card 3: Active Live Carts -->
        <div style="background: #0f172a; border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 12px; padding: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.5);">
            <div style="font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 700;">Active Carts (&lt; 2 Hours)</div>
            <div style="font-family: 'Space Grotesk', sans-serif; font-size: 28px; font-weight: 900; color: #fbbf24; margin-top: 6px;">
                <?= $activeCartsCount ?> Active
            </div>
            <div style="font-size: 11.5px; color: #facc15; margin-top: 4px;">
                🟢 Customers currently exploring products
            </div>
        </div>

        <!-- Card 4: Abandoned Carts for Follow-up -->
        <div style="background: #0f172a; border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 12px; padding: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.5);">
            <div style="font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 700;">Abandoned Carts (&gt; 2 Hours)</div>
            <div style="font-family: 'Space Grotesk', sans-serif; font-size: 28px; font-weight: 900; color: #f87171; margin-top: 6px;">
                <?= $abandonedCartsCount ?> Carts
            </div>
            <div style="font-size: 11.5px; color: #fca5a5; margin-top: 4px;">
                💬 Ready for WhatsApp / Call follow-up
            </div>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div style="background: #0b1120; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
        <!-- Filter Tabs -->
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="?status=all<?= !empty($search) ? '&q=' . urlencode($search) : '' ?>" 
               style="padding: 7px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 700; text-decoration: none; <?= ($statusFilter === 'all') ? 'background: #00e5ff; color: #060911;' : 'background: rgba(255,255,255,0.06); color: #cbd5e1;' ?>">
                All Carts (<?= count($groupedCarts) ?>)
            </a>
            <a href="?status=active<?= !empty($search) ? '&q=' . urlencode($search) : '' ?>" 
               style="padding: 7px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 700; text-decoration: none; <?= ($statusFilter === 'active') ? 'background: #fbbf24; color: #060911;' : 'background: rgba(255,255,255,0.06); color: #cbd5e1;' ?>">
                🟢 Active Live (<?= $activeCartsCount ?>)
            </a>
            <a href="?status=abandoned<?= !empty($search) ? '&q=' . urlencode($search) : '' ?>" 
               style="padding: 7px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 700; text-decoration: none; <?= ($statusFilter === 'abandoned') ? 'background: #ef4444; color: #ffffff;' : 'background: rgba(255,255,255,0.06); color: #cbd5e1;' ?>">
                🟡 Abandoned (<?= $abandonedCartsCount ?>)
            </a>
        </div>

        <!-- Search Input -->
        <form method="GET" action="carts.php" style="display: flex; gap: 8px; flex: 1; max-width: 420px;">
            <input type="hidden" name="status" value="<?= e($statusFilter) ?>">
            <input type="text" name="q" value="<?= e($search) ?>" placeholder="🔍 Search customer name, phone, city, machine..." class="form-control" style="background: #060911; border-color: rgba(0, 229, 255, 0.3); color: #fff; font-size: 13px; padding: 8px 12px; border-radius: 6px; width: 100%;">
            <button type="submit" class="btn btn-outline" style="border-color: var(--accent-cyan); color: var(--accent-cyan); padding: 0 14px; font-size: 12px; font-weight: 700;">
                Search
            </button>
            <?php if (!empty($search)): ?>
                <a href="carts.php?status=<?= e($statusFilter) ?>" class="btn btn-outline" style="border-color: #ef4444; color: #ef4444; padding: 0 10px; font-size: 12px;">✕</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- User-wise Cart Display Cards -->
    <?php if (empty($filteredCarts)): ?>
        <div style="background: #0f172a; border: 1.5px dashed rgba(255,255,255,0.15); border-radius: 14px; padding: 60px 20px; text-align: center;">
            <div style="font-size: 40px; margin-bottom: 12px;">🛒</div>
            <h3 style="font-family: 'Space Grotesk', sans-serif; font-size: 20px; font-weight: 800; color: #ffffff; margin-bottom: 6px;">
                No Customer Carts Found
            </h3>
            <p style="color: #94a3b8; font-size: 14px; max-width: 420px; margin: 0 auto;">
                Currently no customers have items pending in their shopping carts matching this filter.
            </p>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <?php foreach ($filteredCarts as $cKey => $cart): ?>
                <div style="background: #0b1120; border: 1.5px solid <?= $cart['is_active'] ? 'rgba(0, 229, 255, 0.4)' : 'rgba(255, 255, 255, 0.12)' ?>; border-radius: 14px; padding: 22px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                    
                    <!-- Top User Info Bar -->
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 14px; padding-bottom: 16px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); margin-bottom: 16px;">
                        <div>
                            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                <h3 style="font-family: 'Space Grotesk', sans-serif; font-size: 18px; font-weight: 800; color: #ffffff; margin: 0;">
                                    <?= e($cart['customer_name']) ?>
                                </h3>

                                <?php if ($cart['is_registered']): ?>
                                    <span style="font-size: 10.5px; font-weight: 800; background: rgba(56, 189, 248, 0.15); border: 1px solid #38bdf8; color: #38bdf8; padding: 2px 8px; border-radius: 4px;">
                                        👤 REGISTERED BUYER
                                    </span>
                                <?php else: ?>
                                    <span style="font-size: 10.5px; font-weight: 800; background: rgba(245, 158, 11, 0.15); border: 1px solid #f59e0b; color: #fbbf24; padding: 2px 8px; border-radius: 4px;">
                                        🌐 GUEST VISITOR
                                    </span>
                                <?php endif; ?>

                                <?php if ($cart['is_active']): ?>
                                    <span style="font-size: 10.5px; font-weight: 800; background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; color: #34d399; padding: 2px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 5px;">
                                        <span style="display:inline-block; width:6px; height:6px; border-radius:50%; background:#10b981;"></span>
                                        LIVE ACTIVE NOW
                                    </span>
                                <?php else: ?>
                                    <span style="font-size: 10.5px; font-weight: 800; background: rgba(239, 68, 68, 0.12); border: 1px solid #ef4444; color: #f87171; padding: 2px 8px; border-radius: 4px;">
                                        ABANDONED CART
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Contact details row -->
                            <div style="font-size: 12.5px; color: #cbd5e1; margin-top: 6px; display: flex; gap: 16px; flex-wrap: wrap;">
                                <?php if (!empty($cart['company_name'])): ?>
                                    <span>🏭 <strong>Company:</strong> <?= e($cart['company_name']) ?></span>
                                <?php endif; ?>
                                <?php if (!empty($cart['customer_phone'])): ?>
                                    <span>📞 <strong>Mobile:</strong> <a href="tel:<?= e($cart['customer_phone']) ?>" style="color: #38bdf8; text-decoration: none;"><?= e($cart['customer_phone']) ?></a></span>
                                <?php endif; ?>
                                <?php if (!empty($cart['customer_email'])): ?>
                                    <span>✉️ <strong>Email:</strong> <?= e($cart['customer_email']) ?></span>
                                <?php endif; ?>
                                <span>📍 <strong>Location:</strong> <?= e($cart['customer_city']) ?> (IP: <?= e($cart['user_ip']) ?>)</span>
                                <span>⏱️ <strong>Last Active:</strong> <?= date('d-M-Y h:i A', strtotime($cart['last_updated'])) ?></span>
                            </div>
                        </div>

                        <!-- User Total & Action Buttons -->
                        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <div style="text-align: right; margin-right: 6px;">
                                <div style="font-size: 10px; text-transform: uppercase; color: #94a3b8; font-weight: 700;">Cart Total Value</div>
                                <div style="font-family: 'Space Grotesk', sans-serif; font-size: 20px; font-weight: 900; color: #34d399;">
                                    ₹ <?= number_format($cart['cart_total'], 2) ?>
                                </div>
                                <div style="font-size: 10.5px; color: #38bdf8;"><?= $cart['total_units'] ?> Units Selected</div>
                            </div>

                            <!-- WhatsApp Close-the-Deal Button -->
                            <?php 
                                $waPhone = !empty($cart['customer_phone']) ? preg_replace('/[^0-9]/', '', $cart['customer_phone']) : COMPANY_WHATSAPP;
                                $firstItemName = !empty($cart['items'][0]['product_name']) ? $cart['items'][0]['product_name'] : 'Varsha Drill Machine';
                                $waMessage = "Hello " . $cart['customer_name'] . ", greetings from Shree Varsha Engineering Works, Bhavnagar! We noticed you added {$firstItemName} to your cart. Are you looking for technical advice or a special factory discount quotation? We are happy to help!";
                            ?>
                            <a href="https://wa.me/<?= (strlen($waPhone) === 10 ? '91' . $waPhone : $waPhone) ?>?text=<?= urlencode($waMessage) ?>" target="_blank" class="btn btn-outline" style="border-color: #25d366; color: #25d366; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; padding: 7px 12px; text-decoration: none;">
                                <span>💬 WhatsApp Followup</span>
                            </a>

                            <!-- Clear Cart Form -->
                            <form method="POST" action="carts.php" onsubmit="return confirm('Clear this user\'s entire cart?');" style="margin: 0;">
                                <input type="hidden" name="action" value="delete_user_cart">
                                <input type="hidden" name="session_id" value="<?= e($cart['session_id']) ?>">
                                <input type="hidden" name="customer_id" value="<?= intval($cart['customer_id'] ?? 0) ?>">
                                <button type="submit" class="btn btn-outline" style="border-color: #ef4444; color: #ef4444; font-size: 12px; padding: 7px 10px;" title="Clear Cart">
                                    🗑 Clear
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Items Table for this user -->
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                            <thead>
                                <tr style="background: rgba(15, 23, 42, 0.7); border-bottom: 1px solid rgba(255,255,255,0.08); text-align: left; color: #94a3b8; font-size: 11px; text-transform: uppercase;">
                                    <th style="padding: 10px 12px; width: 60px;">Image</th>
                                    <th style="padding: 10px 12px;">Product &amp; Specification</th>
                                    <th style="padding: 10px 12px;">Category / Model</th>
                                    <th style="padding: 10px 12px; text-align: center;">Qty</th>
                                    <th style="padding: 10px 12px; text-align: right;">Unit Price (Ex-Bhavnagar)</th>
                                    <th style="padding: 10px 12px; text-align: right;">Subtotal</th>
                                    <th style="padding: 10px 12px; text-align: center; width: 60px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart['items'] as $cItem): ?>
                                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                                        <td style="padding: 10px 12px;">
                                            <div style="width: 50px; height: 46px; border-radius: 6px; overflow: hidden; background: #060911; border: 1px solid rgba(255,255,255,0.08);">
                                                <img src="<?= assetUrl($cItem['image_url']) ?>" alt="<?= e($cItem['product_name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                            </div>
                                        </td>
                                        <td style="padding: 10px 12px;">
                                            <a href="<?= BASE_URL ?>/machine-detail.php?id=<?= $cItem['product_id'] ?>" target="_blank" style="color: #ffffff; font-weight: 700; text-decoration: none;">
                                                <?= e($cItem['product_name']) ?>
                                            </a>
                                            <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">
                                                Factory Stock: <?= intval($cItem['stock_quantity'] ?? 5) ?> units ready
                                            </div>
                                        </td>
                                        <td style="padding: 10px 12px;">
                                            <span style="font-size: 10.5px; font-weight: 800; background: rgba(0,229,255,0.1); border: 1px solid var(--accent-cyan); color: var(--accent-cyan); padding: 2px 6px; border-radius: 4px;">
                                                <?= e($cItem['model_number']) ?>
                                            </span>
                                            <div style="font-size: 11px; color: #94a3b8; margin-top: 3px;">
                                                <?= e($cItem['category_name']) ?>
                                            </div>
                                        </td>
                                        <td style="padding: 10px 12px; text-align: center;">
                                            <span style="font-family: 'Space Grotesk', sans-serif; font-size: 14px; font-weight: 800; color: #38bdf8; background: #060911; padding: 4px 10px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.1);">
                                                <?= $cItem['quantity'] ?>
                                            </span>
                                        </td>
                                        <td style="padding: 10px 12px; text-align: right; color: #cbd5e1; font-family: 'Space Grotesk', sans-serif;">
                                            ₹ <?= number_format($cItem['unit_price'], 2) ?>
                                        </td>
                                        <td style="padding: 10px 12px; text-align: right; color: #34d399; font-weight: 800; font-family: 'Space Grotesk', sans-serif;">
                                            ₹ <?= number_format($cItem['subtotal'], 2) ?>
                                        </td>
                                        <td style="padding: 10px 12px; text-align: center;">
                                            <form method="POST" action="carts.php" onsubmit="return confirm('Remove this item from user cart?');" style="margin: 0;">
                                                <input type="hidden" name="action" value="delete_single_item">
                                                <input type="hidden" name="item_id" value="<?= $cItem['id'] ?>">
                                                <button type="submit" style="background: none; border: none; color: #f87171; cursor: pointer; font-size: 14px;" title="Remove this item">
                                                    &times;
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
