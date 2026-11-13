<?php
/**
 * Admin Orders & Amazon-Style Logistics Management
 * Shree Varsha Engineering Works - Varsha Brand
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

if (empty($_SESSION['admin_user'])) {
    header("Location: index.php");
    exit;
}

$adminUser = $_SESSION['admin_user'];
$activeTab = 'orders';

$pdo = Database::getConnection();
$message = '';

// Handle Status & Logistics Updates (Pending, Failure, Success / Dispatched / Delivered)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['quick_status_change'])) {
    $orderId = intval($_POST['order_id'] ?? 0);
    $quickStatus = trim($_POST['quick_status'] ?? '');
    
    $validQuick = [
        'pending'   => [
            'order_status'    => 'pending', 
            'payment_status'  => 'pending',
            'delivery_status' => 'pending', 
            'label'           => '⏳ Pending (પેન્ડિંગ)'
        ],
        'success'   => [
            'order_status'    => 'confirmed', 
            'payment_status'  => 'paid',
            'delivery_status' => 'order_placed', 
            'label'           => '✅ Success / Paid (સફળ પેઇડ)'
        ],
        'confirmed' => [
            'order_status'    => 'confirmed', 
            'payment_status'  => 'paid',
            'delivery_status' => 'order_placed', 
            'label'           => '✅ Confirmed & Paid'
        ],
        'dispatched'=> [
            'order_status'    => 'dispatched', 
            'payment_status'  => 'paid',
            'delivery_status' => 'dispatched', 
            'label'           => '🚚 Dispatched (રવાના / ઇન ટ્રાન્ઝિટ)'
        ],
        'delivered' => [
            'order_status'    => 'delivered', 
            'payment_status'  => 'paid',
            'delivery_status' => 'delivered', 
            'label'           => '🏁 Successfully Delivered (ડિલિવરી મળી ગઈ - ૧૦૦% પૂર્ણ)'
        ],
        'failure'   => [
            'order_status'    => 'cancelled', 
            'payment_status'  => 'failed',
            'delivery_status' => 'rejected', 
            'label'           => '❌ Failure / Cancelled (ફેઇલ / રદ)'
        ],
        'rejected'  => [
            'order_status'    => 'cancelled', 
            'payment_status'  => 'failed',
            'delivery_status' => 'rejected', 
            'label'           => '❌ Rejected / Cancelled (ફેઇલ / રદ)'
        ]
    ];

    if ($orderId > 0 && isset($validQuick[$quickStatus])) {
        $info = $validQuick[$quickStatus];
        $stmt = $pdo->prepare("UPDATE `orders` SET `order_status` = :ord_st, `payment_status` = :pay_st, `delivery_status` = :del_st WHERE `id` = :id");
        $stmt->execute([
            ':ord_st' => $info['order_status'],
            ':pay_st' => $info['payment_status'],
            ':del_st' => $info['delivery_status'],
            ':id'     => $orderId
        ]);
        $message = "Order #{$orderId} marked as '{$info['label']}' successfully!";
    }
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['update_order_logistics'])) {
    $orderId = intval($_POST['order_id'] ?? 0);
    $deliveryStatus = sanitizeString($_POST['delivery_status'] ?? 'order_placed');
    $transportName = sanitizeString($_POST['transport_name'] ?? '');
    $trackingNumber = sanitizeString($_POST['tracking_number'] ?? '');
    $estimatedDelivery = sanitizeString($_POST['estimated_delivery'] ?? '');
    $currentLocation = sanitizeString($_POST['current_location'] ?? '');
    $deliveryNotes = sanitizeString($_POST['delivery_notes'] ?? '');

    // Map delivery_status to legacy order_status
    $statusMap = [
        'pending'          => 'pending',
        'order_placed'     => 'confirmed',
        'chassis_assembly' => 'manufacturing',
        'quality_testing'  => 'manufacturing',
        'packed'           => 'manufacturing',
        'dispatched'       => 'dispatched',
        'out_for_delivery' => 'dispatched',
        'delivered'        => 'delivered',
        'rejected'         => 'cancelled'
    ];
    $orderStatus = $statusMap[$deliveryStatus] ?? 'confirmed';

    if ($orderId > 0) {
        $stmt = $pdo->prepare("
            UPDATE `orders` SET 
                `delivery_status` = :del_st,
                `order_status` = :ord_st,
                `transport_name` = :trans_name,
                `tracking_number` = :track_no,
                `estimated_delivery` = :est_del,
                `current_location` = :loc,
                `delivery_notes` = :notes
            WHERE `id` = :id
        ");
        $stmt->execute([
            ':del_st'     => $deliveryStatus,
            ':ord_st'     => $orderStatus,
            ':trans_name' => $transportName,
            ':track_no'   => $trackingNumber,
            ':est_del'    => $estimatedDelivery,
            ':loc'        => $currentLocation,
            ':notes'      => $deliveryNotes,
            ':id'         => $orderId
        ]);
        $message = "Order #{$orderId} logistics tracking updated successfully!";
    }
}

// Fetch all orders
$orders = [];
try {
    $stmt = $pdo->query("SELECT * FROM `orders` ORDER BY id DESC");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    //
}

// Stats
$totalRevenue = 0;
$paidOrdersCount = 0;
foreach ($orders as $o) {
    if (($o['payment_status'] ?? '') === 'paid') {
        $totalRevenue += floatval($o['total_amount'] ?? 0);
        $paidOrdersCount++;
    }
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<style>
.stats-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)) !important;
    gap: 20px !important;
    margin-bottom: 30px !important;
}
.stat-card {
    background: #0f172a !important;
    border: 1px solid rgba(0, 229, 255, 0.25) !important;
    border-radius: 12px !important;
    padding: 20px 22px !important;
    box-shadow: 0 10px 25px rgba(0,0,0,0.5) !important;
}
.stat-card-title {
    font-size: 12px !important;
    font-weight: 600 !important;
    color: #94a3b8 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    margin-bottom: 8px !important;
}
.stat-card-value {
    font-family: 'Space Grotesk', sans-serif !important;
    font-size: 26px !important;
    font-weight: 700 !important;
    color: #fff !important;
}
.admin-card {
    background: #0f172a !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    border-radius: 12px !important;
    padding: 24px !important;
    margin-bottom: 24px !important;
    box-shadow: 0 10px 30px rgba(0,0,0,0.4) !important;
}
.tech-table {
    width: 100% !important;
    border-collapse: collapse !important;
    font-size: 13.5px !important;
}
.tech-table th {
    text-align: left !important;
    padding: 12px 14px !important;
    background: rgba(11, 17, 32, 0.9) !important;
    font-family: 'Space Grotesk', sans-serif !important;
    font-size: 11.5px !important;
    font-weight: 700 !important;
    color: #00e5ff !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    border-bottom: 1px solid rgba(255,255,255,0.12) !important;
}
.tech-table td {
    padding: 14px !important;
    border-bottom: 1px solid rgba(255,255,255,0.08) !important;
    color: #cbd5e1 !important;
    vertical-align: middle !important;
}
.tech-table tr:hover td {
    background: rgba(255, 255, 255, 0.03) !important;
}
</style>

<div class="admin-main">
    <header class="admin-topbar">
        <div style="display: flex; align-items: center; gap: 12px;">
            <button type="button" id="adminSidebarToggle" class="btn-sm btn-cyan" style="padding: 6px 10px; cursor: pointer;">☰</button>
            <div>
                <h1 class="topbar-title">Customer Orders &amp; Live Delivery Tracking</h1>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 3px;">
                    Track machine bookings, verified online payments, tax invoices, and manage live delivery milestones.
                </p>
            </div>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="<?= BASE_URL ?>/track-order.php" target="_blank" class="btn btn-outline" style="font-size: 13px; border-color: #34d399; color: #34d399;">
                🚚 Live Tracking Page
            </a>
            <a href="<?= BASE_URL ?>/checkout.php" target="_blank" class="btn btn-primary" style="font-size: 13px;">
                ⚡ New Machine Booking
            </a>
        </div>
    </header>

    <main class="admin-body">

        <?php if (!empty($message)): ?>
            <div style="padding: 12px 18px; background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; border-radius: 8px; color: #34d399; font-size: 14px; margin-bottom: 20px;">
                ✔ <?= e($message) ?>
            </div>
        <?php endif; ?>

        <!-- Stat Cards -->
        <div class="stats-grid" style="margin-bottom: 30px;">
            <div class="stat-card">
                <div class="stat-card-title">Total Orders Placed</div>
                <div class="stat-card-value"><?= count($orders) ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-card-title">Paid / Confirmed</div>
                <div class="stat-card-value" style="color: #34d399;"><?= $paidOrdersCount ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-card-title">Total Payments Collected</div>
                <div class="stat-card-value" style="color: var(--accent-cyan);">
                    ₹ <?= number_format($totalRevenue, 2) ?>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card-title">Authorized Signatory</div>
                <div class="stat-card-value" style="font-size: 19px; color: #38bdf8;"><?= AUTHORIZED_SIGNATORY ?></div>
            </div>
        </div>

        <!-- Orders Table Card -->
        <div class="admin-card">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 16px;">
                <h2 style="font-family: var(--font-heading); font-size: 18px; color: #fff; margin: 0;">
                    All Orders &amp; Logistics Tracking
                </h2>
                <!-- Orders Instant Search Bar -->
                <div style="position: relative; width: 100%; max-width: 420px;">
                    <input type="text" id="orderSearchInput" class="admin-form-control" placeholder="🔍 Search by name, phone, invoice, city, machine..." style="width: 100%; padding: 8px 14px 8px 34px; font-size: 12.5px; border-radius: 8px; border: 1.5px solid rgba(0, 229, 255, 0.4); background: rgba(15, 23, 42, 0.9); color: #fff; box-sizing: border-box;">
                    <span style="position: absolute; left: 11px; top: 50%; transform: translateY(-50%); font-size: 13px; opacity: 0.7; pointer-events: none;">🔍</span>
                </div>
            </div>

            <?php if (empty($orders)): ?>
                <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                    <p>No customer orders recorded yet.</p>
                    <a href="<?= BASE_URL ?>/checkout.php" target="_blank" class="btn btn-primary" style="margin-top: 15px;">
                        Place First Booking Online
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="tech-table">
                        <thead>
                            <tr>
                                <th>Order / Invoice #</th>
                                <th>Customer &amp; Location</th>
                                <th>Machine / Part</th>
                                <th>Type &amp; Amount</th>
                                <th>Real-Time Delivery Status</th>
                                <th>Freight Logistics</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $ord): 
                                $delStatus = $ord['delivery_status'] ?? ($ord['order_status'] ?? 'order_placed');
                                $statusBadges = [
                                    'pending'           => ['label' => '⏳ Pending Verification', 'color' => '#fbbf24', 'bg' => 'rgba(251,191,36,0.15)'],
                                    'order_placed'      => ['label' => '✅ Order Placed & Paid', 'color' => '#38bdf8', 'bg' => 'rgba(56,189,248,0.15)'],
                                    'chassis_assembly'  => ['label' => '⚙️ Assembly in Progress', 'color' => '#818cf8', 'bg' => 'rgba(129,140,248,0.15)'],
                                    'quality_testing'   => ['label' => '🔬 QC Testing Passed', 'color' => '#a855f7', 'bg' => 'rgba(168,85,247,0.15)'],
                                    'packed'            => ['label' => '📦 Wooden Crate Packed', 'color' => '#f97316', 'bg' => 'rgba(249,115,22,0.15)'],
                                    'dispatched'        => ['label' => '🚚 Dispatched / In Transit', 'color' => '#06b6d4', 'bg' => 'rgba(6,182,212,0.15)'],
                                    'out_for_delivery'  => ['label' => '🚛 Out for Delivery', 'color' => '#eab308', 'bg' => 'rgba(234,179,8,0.15)'],
                                    'delivered'         => ['label' => '🏁 Successfully Delivered', 'color' => '#10b981', 'bg' => 'rgba(16,185,129,0.18)'],
                                    'rejected'          => ['label' => '❌ Rejected / Cancelled', 'color' => '#ef4444', 'bg' => 'rgba(239,68,68,0.15)'],
                                ];
                                $curBadge = $statusBadges[$delStatus] ?? ['label' => ucfirst($delStatus), 'color' => '#94a3b8', 'bg' => 'rgba(255,255,255,0.1)'];
                            ?>
                                <tr>
                                    <td>
                                        <strong style="color: #fff;"><?= e($ord['order_number']) ?></strong><br>
                                        <small style="color: var(--accent-cyan); font-weight: 600;"><?= e($ord['invoice_number']) ?></small><br>
                                        <small style="color: var(--text-muted);"><?= date('d M Y, h:i A', strtotime($ord['created_at'])) ?></small>
                                    </td>
                                    <td>
                                        <strong style="color: #fff;"><?= e($ord['customer_name']) ?></strong>
                                        <?php if (!empty($ord['company_name'])): ?>
                                            <div style="font-size: 12px; color: #94a3b8;"><?= e($ord['company_name']) ?></div>
                                        <?php endif; ?>
                                        <div style="font-size: 12px; color: #cbd5e1;"><?= e($ord['customer_phone']) ?></div>
                                        <div style="font-size: 11px; color: #64748b;"><?= e($ord['customer_city']) ?></div>
                                    </td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                            <span class="badge-tag" style="border-color: var(--accent-cyan); color: var(--accent-cyan);">
                                                <?= e($ord['model_number']) ?>
                                            </span>
                                            <span style="background: rgba(0, 229, 255, 0.15); border: 1px solid var(--accent-cyan); color: #38bdf8; font-size: 11px; font-weight: 800; padding: 2px 7px; border-radius: 4px;">
                                                Qty: <?= intval($ord['quantity'] ?? 1) ?>
                                            </span>
                                        </div>
                                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 3px;">
                                            <?= e($ord['product_name']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($ord['payment_type'] === 'booking_advance'): ?>
                                            <span style="color: #34d399; font-weight: 700; font-size: 11.5px; display: block;">Token Advance</span>
                                        <?php else: ?>
                                            <span style="color: #38bdf8; font-weight: 700; font-size: 11.5px; display: block;">Full Payment</span>
                                        <?php endif; ?>
                                        <strong style="color: #34d399; font-size: 15px; white-space: nowrap !important; display: inline-flex; align-items: center; gap: 3px;">
                                            <span>₹</span><span><?= number_format($ord['total_amount'], 2) ?></span>
                                        </strong>
                                        <div style="margin-top: 4px;">
                                            <?php if (($ord['payment_status'] ?? '') === 'paid'): ?>
                                                <span style="font-size: 9.5px; background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid #10b981; padding: 2px 6px; border-radius: 4px; font-weight: 800; display: inline-block;">
                                                    ✅ SUCCESS / PAID
                                                </span>
                                            <?php elseif (($ord['payment_status'] ?? '') === 'failed'): ?>
                                                <span style="font-size: 9.5px; background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid #ef4444; padding: 2px 6px; border-radius: 4px; font-weight: 800; display: inline-block;">
                                                    ❌ FAILURE / CANCELLED
                                                </span>
                                            <?php else: ?>
                                                <span style="font-size: 9.5px; background: rgba(251, 191, 36, 0.2); color: #fbbf24; border: 1px solid #f59e0b; padding: 2px 6px; border-radius: 4px; font-weight: 800; display: inline-block;">
                                                    ⏳ PENDING
                                                </span>
                                            <?php endif; ?>

                                            <!-- Payment Method Badge -->
                                            <?php
                                                $pmMethod = $ord['payment_method'] ?? 'online';
                                                if ($pmMethod === 'cod') {
                                                    echo '<span style="font-size: 9px; background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid #f59e0b; padding: 2px 6px; border-radius: 4px; font-weight: 700; display: inline-block; margin-top: 3px;">💵 Cash on Delivery</span>';
                                                } elseif ($pmMethod === 'bank_transfer') {
                                                    echo '<span style="font-size: 9px; background: rgba(2, 132, 199, 0.15); color: #38bdf8; border: 1px solid #0284c7; padding: 2px 6px; border-radius: 4px; font-weight: 700; display: inline-block; margin-top: 3px;">🏛️ Bank Transfer</span>';
                                                } elseif ($pmMethod === 'cheque') {
                                                    echo '<span style="font-size: 9px; background: rgba(168, 85, 247, 0.15); color: #c084fc; border: 1px solid #a855f7; padding: 2px 6px; border-radius: 4px; font-weight: 700; display: inline-block; margin-top: 3px;">📜 Cheque/DD</span>';
                                                } else {
                                                    echo '<span style="font-size: 9px; background: rgba(0, 229, 255, 0.1); color: #00e5ff; border: 1px solid rgba(0, 229, 255, 0.4); padding: 2px 6px; border-radius: 4px; font-weight: 700; display: inline-block; margin-top: 3px;">💳 Online / UPI</span>';
                                                }
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="display: inline-block; padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 700; color: <?= $curBadge['color'] ?>; background: <?= $curBadge['bg'] ?>; border: 1px solid <?= $curBadge['color'] ?>;">
                                            <?= $curBadge['label'] ?>
                                        </span>
                                        <?php if (!empty($ord['current_location'])): ?>
                                            <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">
                                                📍 <?= e($ord['current_location']) ?>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Direct 1-Click Status Buttons (Pending, Success, Failure) -->
                                        <div style="display: flex; gap: 4px; margin-top: 8px; flex-wrap: wrap;">
                                            <form method="POST" style="margin: 0;">
                                                <input type="hidden" name="quick_status_change" value="1">
                                                <input type="hidden" name="order_id" value="<?= $ord['id'] ?>">
                                                <input type="hidden" name="quick_status" value="pending">
                                                <button type="submit" title="Mark Pending (પેન્ડિંગ)" style="background: <?= ($ord['order_status'] === 'pending') ? '#fbbf24' : 'rgba(251,191,36,0.15)' ?>; color: <?= ($ord['order_status'] === 'pending') ? '#060911' : '#fbbf24' ?>; border: 1px solid #fbbf24; border-radius: 4px; font-size: 10px; font-weight: 800; padding: 3px 6px; cursor: pointer;">
                                                    ⏳ Pending
                                                </button>
                                            </form>

                                            <form method="POST" style="margin: 0;">
                                                <input type="hidden" name="quick_status_change" value="1">
                                                <input type="hidden" name="order_id" value="<?= $ord['id'] ?>">
                                                <input type="hidden" name="quick_status" value="success">
                                                <button type="submit" title="Mark Success / Paid (સક્સેસ)" style="background: <?= ($ord['payment_status'] === 'paid' && $ord['order_status'] !== 'cancelled') ? '#10b981' : 'rgba(16,185,129,0.15)' ?>; color: <?= ($ord['payment_status'] === 'paid' && $ord['order_status'] !== 'cancelled') ? '#060911' : '#34d399' ?>; border: 1px solid #10b981; border-radius: 4px; font-size: 10px; font-weight: 800; padding: 3px 6px; cursor: pointer;">
                                                    ✅ Success
                                                </button>
                                            </form>

                                            <form method="POST" style="margin: 0;">
                                                <input type="hidden" name="quick_status_change" value="1">
                                                <input type="hidden" name="order_id" value="<?= $ord['id'] ?>">
                                                <input type="hidden" name="quick_status" value="failure">
                                                <button type="submit" title="Mark Failure / Cancelled (ફેઇલ)" style="background: <?= ($ord['order_status'] === 'cancelled' || $ord['payment_status'] === 'failed') ? '#ef4444' : 'rgba(239,68,68,0.15)' ?>; color: <?= ($ord['order_status'] === 'cancelled' || $ord['payment_status'] === 'failed') ? '#fff' : '#f87171' ?>; border: 1px solid #ef4444; border-radius: 4px; font-size: 10px; font-weight: 800; padding: 3px 6px; cursor: pointer;">
                                                    ❌ Failure
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Granular Logistics Selector Dropdown -->
                                        <form method="POST" style="margin-top: 6px;">
                                            <input type="hidden" name="quick_status_change" value="1">
                                            <input type="hidden" name="order_id" value="<?= $ord['id'] ?>">
                                            <select name="quick_status" onchange="this.form.submit()" style="font-size: 10.5px; padding: 4px 6px; background: #060911; color: #38bdf8; border: 1px solid rgba(56,189,248,0.4); border-radius: 6px; cursor: pointer; width: 100%;">
                                                <option value="" disabled selected>⚡ Change Milestone...</option>
                                                <option value="pending" <?= ($delStatus === 'pending') ? 'selected' : '' ?>>⏳ Pending Verification</option>
                                                <option value="success" <?= ($delStatus === 'order_placed') ? 'selected' : '' ?>>✅ Payment Success / Placed</option>
                                                <option value="dispatched" <?= ($delStatus === 'dispatched') ? 'selected' : '' ?>>🚚 Dispatched (In Transit)</option>
                                                <option value="delivered" <?= ($delStatus === 'delivered') ? 'selected' : '' ?>>🏁 Successfully Delivered</option>
                                                <option value="failure" <?= ($delStatus === 'rejected') ? 'selected' : '' ?>>❌ Failure / Cancelled</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <div style="font-size: 12px; color: #fff; font-weight: 600;">
                                            <?= e($ord['transport_name'] ?? 'VRL Logistics') ?>
                                        </div>
                                        <div style="font-size: 11px; color: var(--accent-cyan);">
                                            LR: <?= e($ord['tracking_number'] ?? 'VRL-RAJ-884210') ?>
                                        </div>
                                        <?php if (!empty($ord['estimated_delivery'])): ?>
                                            <div style="font-size: 11px; color: #34d399; margin-top: 2px;">
                                                Est: <?= date('d M Y', strtotime($ord['estimated_delivery'])) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="display: flex; flex-direction: column; gap: 5px;">
                                            <a href="eway-bill.php?order_id=<?= $ord['id'] ?>" target="_blank" class="btn btn-primary" style="font-size: 11.5px; padding: 5px 8px; text-align: center; background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399; color: #fff; font-weight: 700;">
                                                📋 GST E-Way Bill
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline" 
                                                    style="font-size: 11.5px; padding: 4px 8px; border-color: #38bdf8; color: #38bdf8;"
                                                    onclick='openLogisticsModal(<?= json_encode($ord, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)'>
                                                🚚 Edit Logistics
                                            </button>
                                            <a href="<?= BASE_URL ?>/invoice.php?order_id=<?= $ord['id'] ?>" target="_blank" class="btn btn-outline" style="font-size: 11.5px; padding: 4px 8px; text-align: center; border-color: #fbbf24; color: #fbbf24;">
                                                📄 Tax Bill
                                            </a>
                                            <a href="<?= BASE_URL ?>/track-order.php?order_num=<?= urlencode($ord['order_number']) ?>" target="_blank" class="btn btn-outline" style="font-size: 11.5px; padding: 4px 8px; border-color: #34d399; color: #34d399; text-align: center;">
                                                👁 Customer View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- Modal: Update Logistics Information -->
<div id="logisticsModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 10000; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #090d16; border: 1.5px solid var(--accent-cyan); border-radius: 14px; max-width: 520px; width: 100%; overflow: hidden; box-shadow: 0 25px 60px rgba(0,0,0,0.9);">
        <div style="padding: 16px 20px; background: rgba(15, 23, 42, 0.95); border-bottom: 1px solid rgba(0, 229, 255, 0.25); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-family: var(--font-heading); font-size: 16px; color: #fff; margin: 0; display: flex; align-items: center; gap: 8px;">
                <span>🚚</span> Update Logistics Tracking
            </h3>
            <button type="button" onclick="closeLogisticsModal()" style="background: transparent; border: none; color: #94a3b8; font-size: 20px; cursor: pointer;">&times;</button>
        </div>

        <form method="POST" action="orders.php" style="padding: 20px;">
            <input type="hidden" name="update_order_logistics" value="1">
            <input type="hidden" name="order_id" id="modalOrderId" value="">

            <div style="font-size: 13px; color: #94a3b8; margin-bottom: 14px;">
                Updating Order: <strong style="color: #fff;" id="modalOrderNumber"></strong> (<span id="modalCustomerName" style="color: #38bdf8;"></span>)
            </div>

            <div class="form-group" style="margin-bottom: 12px;">
                <label class="form-label">Delivery Progress Stage *</label>
                <select name="delivery_status" id="modalDeliveryStatus" class="form-control" required style="font-weight: 600;">
                    <option value="pending">⏳ Awaiting Payment / Pending Verification</option>
                    <option value="order_placed">1. Order Placed &amp; Payment Verified</option>
                    <option value="chassis_assembly">2. Chassis &amp; Headstock Assembly in Progress</option>
                    <option value="quality_testing">3. Run-Out QC Passed (< 0.01mm Verified)</option>
                    <option value="packed">4. Export Wooden Crate Packed &amp; Sealed</option>
                    <option value="dispatched">5. Dispatched with Freight Carrier (In Transit)</option>
                    <option value="out_for_delivery">6. Out for Local Destination Delivery</option>
                    <option value="delivered">7. Delivered (ડિલિવરી મળી ગઈ - All Stages Complete 100%)</option>
                    <option value="rejected">❌ Rejected / Order Cancelled</option>
                </select>
            </div>

            <div class="form-grid" style="margin-bottom: 12px;">
                <div class="form-group">
                    <label class="form-label">Freight Carrier Name</label>
                    <input type="text" name="transport_name" id="modalTransportName" class="form-control" placeholder="e.g. VRL Logistics / TCI Freight">
                </div>
                <div class="form-group">
                    <label class="form-label">LR / Docket Tracking #</label>
                    <input type="text" name="tracking_number" id="modalTrackingNumber" class="form-control" placeholder="e.g. VRL-RAJ-884210">
                </div>
            </div>

            <div class="form-grid" style="margin-bottom: 12px;">
                <div class="form-group">
                    <label class="form-label">Estimated Delivery Date</label>
                    <input type="date" name="estimated_delivery" id="modalEstimatedDelivery" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Current Transit Location</label>
                    <input type="text" name="current_location" id="modalCurrentLocation" class="form-control" placeholder="e.g. Ahmedabad Logistics Terminal">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label">Driver / Special Dispatch Notes</label>
                <input type="text" name="delivery_notes" id="modalDeliveryNotes" class="form-control" placeholder="e.g. Heavy crate with shock dampers. Driver: +91 94280 11223">
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeLogisticsModal()" class="btn btn-outline" style="padding: 8px 16px;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="padding: 8px 18px; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color: #060911; font-weight: 800; border-color: #00e5ff;">
                    💾 Save Logistics Updates
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openLogisticsModal(order) {
    document.getElementById('modalOrderId').value = order.id;
    document.getElementById('modalOrderNumber').textContent = order.order_number;
    document.getElementById('modalCustomerName').textContent = order.customer_name;
    document.getElementById('modalDeliveryStatus').value = order.delivery_status || 'order_placed';
    document.getElementById('modalTransportName').value = order.transport_name || 'VRL Logistics Heavy Freight';
    document.getElementById('modalTrackingNumber').value = order.tracking_number || '';
    document.getElementById('modalEstimatedDelivery').value = order.estimated_delivery || '';
    document.getElementById('modalCurrentLocation').value = order.current_location || 'Bhavnagar Factory Warehouse';
    document.getElementById('modalDeliveryNotes').value = order.delivery_notes || '';
    
    const modal = document.getElementById('logisticsModal');
    modal.style.display = 'flex';
}

function closeLogisticsModal() {
    document.getElementById('logisticsModal').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('orderSearchInput');
    if (input) {
        input.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.tech-table tbody tr');
            rows.forEach(r => {
                const text = r.innerText.toLowerCase();
                r.style.display = (!query || text.includes(query)) ? '' : 'none';
            });
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
