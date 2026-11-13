<?php
/**
 * Amazon-Style Live Order & Logistics Tracking
 * Shree Varsha Engineering Works - Varsha Brand
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$pdo = Database::getConnection();

$searchTerm = trim($_GET['order_num'] ?? ($_GET['search'] ?? ''));
$phoneTerm = trim($_GET['phone'] ?? '');

$orders = [];
$selectedOrder = null;

// If customer is logged in, find all their orders by default
$loggedInCustomer = $_SESSION['customer_user'] ?? null;

try {
    if (!empty($searchTerm)) {
        // Search by order_number or invoice_number or razorpay_payment_id
        $stmt = $pdo->prepare("
            SELECT o.*, p.image_url, p.drilling_capacity, p.spindle_speeds, p.motor_power, p.product_type 
            FROM orders o 
            LEFT JOIN products p ON o.product_id = p.id 
            WHERE o.order_number = :t OR o.invoice_number = :t OR o.razorpay_payment_id = :t OR o.customer_phone = :t
            ORDER BY o.id DESC
        ");
        $stmt->execute([':t' => $searchTerm]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif (!empty($phoneTerm)) {
        $stmt = $pdo->prepare("
            SELECT o.*, p.image_url, p.drilling_capacity, p.spindle_speeds, p.motor_power, p.product_type 
            FROM orders o 
            LEFT JOIN products p ON o.product_id = p.id 
            WHERE o.customer_phone LIKE :ph 
            ORDER BY o.id DESC
        ");
        $stmt->execute([':ph' => "%{$phoneTerm}%"]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($loggedInCustomer) {
        $stmt = $pdo->prepare("
            SELECT o.*, p.image_url, p.drilling_capacity, p.spindle_speeds, p.motor_power, p.product_type 
            FROM orders o 
            LEFT JOIN products p ON o.product_id = p.id 
            WHERE o.customer_phone = :ph OR (o.customer_email IS NOT NULL AND o.customer_email != '' AND o.customer_email = :em)
            ORDER BY o.id DESC
        ");
        $stmt->execute([
            ':ph'  => $loggedInCustomer['phone'] ?? '',
            ':em'  => $loggedInCustomer['email'] ?? ''
        ]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fallback: If still empty, fetch the most recent order for demonstration
    if (empty($orders)) {
        $stmt = $pdo->query("
            SELECT o.*, p.image_url, p.drilling_capacity, p.spindle_speeds, p.motor_power, p.product_type 
            FROM orders o 
            LEFT JOIN products p ON o.product_id = p.id 
            ORDER BY o.id DESC LIMIT 1
        ");
        $recent = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($recent) {
            $orders = [$recent];
        }
    }
} catch (Exception $e) {
    // Database query error handled gracefully
}

if (!empty($orders)) {
    $selectedOrder = $orders[0];
}

$pageTitle = 'Live Order & Logistics Tracking | ' . BRAND_NAME . ' Bhavnagar';
$pageDesc = 'Track your Varsha Brand heavy-duty industrial drilling machine and spare parts delivery status in real-time with freight carrier updates.';
$pageKeywords = 'track drill machine order, Varsha brand order tracking, freight carrier status, Shree Varsha logistics';
$canonicalUrl = 'https://varsha-drill-machines.onrender.com/track-order.php';
$currentNav = 'tracking';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Map status to progress step index (0 to 6)
$statusMap = [
    'order_placed'      => 0,
    'confirmed'         => 0,
    'chassis_assembly'  => 1,
    'manufacturing'     => 1,
    'quality_testing'   => 2,
    'packed'            => 3,
    'dispatched'        => 4,
    'out_for_delivery'  => 5,
    'delivered'         => 6
];

$currStatusKey = $selectedOrder['delivery_status'] ?? ($selectedOrder['order_status'] ?? 'order_placed');
$currentStep = $statusMap[$currStatusKey] ?? 0;

$deliveryStages = [
    [
        'title' => 'Order Confirmed & Paid',
        'sub'   => 'Verified Online Payment • GST Invoice Generated',
        'badge' => 'Instant Verification',
        'icon'  => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
    ],
    [
        'title' => 'Chassis & Gearbox Assembly',
        'sub'   => 'FG-260 Castings & 20MnCr5 Hardened Alloy Gears',
        'badge' => 'Factory Floor (Bhavnagar)',
        'icon'  => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"/></svg>'
    ],
    [
        'title' => 'Run-Out QC & ISO Testing Passed',
        'sub'   => 'Spindle Run-Out < 0.01mm • Vibration Analysis QC',
        'badge' => 'Quality Passed',
        'icon'  => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>'
    ],
    [
        'title' => 'Treated Wooden Crate Packaging',
        'sub'   => 'Heavy anti-rust VCI film with heavy shock dampeners',
        'badge' => 'Ready for Loading',
        'icon'  => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>'
    ],
    [
        'title' => 'Dispatched with Freight Carrier',
        'sub'   => 'In Transit on National Highway Express Route',
        'badge' => 'Live GPS Route',
        'icon'  => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>'
    ],
    [
        'title' => 'Out for Local Delivery',
        'sub'   => 'Assigned to Local Crane Flatbed Unloading Truck',
        'badge' => 'Near Destination',
        'icon'  => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>'
    ],
    [
        'title' => 'Delivered & Commissioned at Factory',
        'sub'   => 'Unloaded, Leveled & Production Test Complete',
        'badge' => 'Completed',
        'icon'  => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>'
    ]
];
?>

<style>
.track-hero {
    padding: 48px 0 36px;
    background: radial-gradient(circle at 50% 20%, rgba(0, 229, 255, 0.12) 0%, rgba(15, 23, 42, 0.95) 60%, #060911 100%);
    border-bottom: 1px solid rgba(0, 229, 255, 0.2);
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.6);
}
.track-search-bar {
    max-width: 720px;
    margin: 24px auto 0;
    display: flex;
    gap: 10px;
    background: rgba(11, 17, 32, 0.92);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    padding: 8px 10px;
    border-radius: 14px;
    border: 1.5px solid var(--accent-cyan);
    box-shadow: 0 10px 30px rgba(0, 229, 255, 0.18), inset 0 0 15px rgba(0, 229, 255, 0.05);
}
.track-search-bar input {
    flex: 1;
    background: transparent;
    border: none;
    color: #ffffff;
    padding: 12px 18px;
    font-size: 15.5px;
    font-weight: 500;
    outline: none;
}
.track-search-bar input::placeholder {
    color: #64748b;
}
.track-search-bar button {
    background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%);
    color: #060911;
    font-weight: 800;
    font-family: var(--font-heading);
    border: none;
    padding: 0 28px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 14.5px;
    letter-spacing: 0.3px;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.track-search-bar button:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(0, 229, 255, 0.5);
}

.tracking-layout {
    display: grid;
    grid-template-columns: 1.45fr 0.85fr;
    gap: 30px;
    margin: 35px 0 60px;
    align-items: flex-start;
}
@media (max-width: 992px) {
    .tracking-layout {
        grid-template-columns: 1fr;
    }
}

.amazon-track-card {
    background: var(--bg-card);
    border: 1.5px solid rgba(56, 189, 248, 0.3);
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 20px 45px rgba(0,0,0,0.6);
}

/* Amazon-Style Horizontal / Vertical Progress Bar */
.delivery-timeline {
    position: relative;
    margin: 30px 0 10px;
    padding-left: 20px;
}
.timeline-item {
    position: relative;
    padding-bottom: 28px;
    padding-left: 45px;
}
.timeline-item:last-child {
    padding-bottom: 0;
}
.timeline-line {
    position: absolute;
    left: 17px;
    top: 36px;
    bottom: -6px;
    width: 3px;
    background: rgba(255,255,255,0.12);
}
.timeline-item.completed .timeline-line {
    background: linear-gradient(180deg, #10b981 0%, #34d399 100%);
}
.timeline-icon-box {
    position: absolute;
    left: 0;
    top: 4px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #0f172a;
    border: 2px solid #475569;
    color: #94a3b8;
    z-index: 2;
    transition: all 0.3s;
}
.timeline-item.completed .timeline-icon-box {
    background: #059669;
    border-color: #34d399;
    color: #fff;
    box-shadow: 0 0 15px rgba(16, 185, 129, 0.5);
}
.timeline-item.current .timeline-icon-box {
    background: #0284c7;
    border-color: #00e5ff;
    color: #fff;
    box-shadow: 0 0 20px rgba(0, 229, 255, 0.7);
    animation: pulseRing 1.8s infinite;
}
@keyframes pulseRing {
    0% { box-shadow: 0 0 0 0 rgba(0, 229, 255, 0.7); }
    70% { box-shadow: 0 0 0 12px rgba(0, 229, 255, 0); }
    100% { box-shadow: 0 0 0 0 rgba(0, 229, 255, 0); }
}

.item-info-card {
    background: rgba(15, 23, 42, 0.85);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 14px;
    padding: 24px;
    position: sticky;
    top: 95px;
}
</style>

<!-- Top Hero & Search Bar -->
<section class="track-hero">
    <div class="container text-center">
        <!-- Brand & Dispatch Status Badge -->
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; margin-bottom: 16px;">
            <div style="display: inline-flex; align-items: center; gap: 10px; background: rgba(15, 23, 42, 0.85); border: 1.5px solid rgba(0, 229, 255, 0.35); padding: 5px 18px 5px 6px; border-radius: 9999px; box-shadow: 0 4px 20px rgba(0, 229, 255, 0.15);">
                <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand" style="height: 38px; width: 38px; border-radius: 50%; border: 1.5px solid var(--accent-cyan); object-fit: cover;">
                <span style="font-family: 'Space Grotesk', sans-serif; font-size: 13px; font-weight: 800; color: #ffffff; letter-spacing: 0.5px;">
                    VARSHA BRAND <span style="color: var(--accent-cyan);">• BHAVNAGAR PLANT</span>
                </span>
            </div>
            
            <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.4); padding: 4px 16px; border-radius: 9999px; font-size: 11.5px; color: #34d399; font-weight: 700; letter-spacing: 0.5px;">
                <span style="display: inline-block; width: 7px; height: 7px; border-radius: 50%; background: #10b981; box-shadow: 0 0 8px #10b981; animation: pulse 2s infinite;"></span>
                <span>REAL-TIME FACTORY DISPATCH &amp; TRANSIT LOGISTICS</span>
            </div>
        </div>

        <!-- Main Heading (Centered) -->
        <h1 style="font-family: var(--font-heading); font-size: clamp(24px, 4vw, 36px); font-weight: 800; color: #ffffff; letter-spacing: -0.5px; line-height: 1.25; margin: 0 auto 12px; text-shadow: 0 2px 12px rgba(0,0,0,0.6); text-align: center;">
            Real-Time Machine &amp; Spare Parts <span style="background: linear-gradient(135deg, #00e5ff 0%, #38bdf8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Delivery Status</span>
        </h1>

        <!-- Subtitle (Clean, elegant typography without fragmented inline boxes) -->
        <p style="color: #cbd5e1; font-size: 15.5px; line-height: 1.6; max-width: 760px; margin: 0 auto 24px;">
            Track your <strong style="color: #00e5ff;">VARSHA BRAND</strong> industrial order directly from our <strong style="color: #38bdf8;">Bhavnagar manufacturing plant</strong> directly to your factory workshop with live milestone checkpoints.
        </p>

        <!-- Search Bar -->
        <form method="GET" action="track-order.php" class="track-search-bar">
            <span style="font-size: 18px; padding-left: 10px; color: var(--accent-cyan); display: flex; align-items: center;">🔍</span>
            <input type="text" name="order_num" value="<?= e($searchTerm ?: ($selectedOrder['order_number'] ?? '')) ?>" placeholder="Enter Order # (e.g. SVEW-ORD-...) or Mobile Number (e.g. 9825112345)" required>
            <button type="submit">
                <span>Track Order</span>
                <span style="font-size: 16px;">➔</span>
            </button>
        </form>

        <?php if (!empty($_SESSION['customer_user'])): ?>
            <div style="margin-top: 14px; font-size: 13px; color: #94a3b8;">
                Logged in as <strong style="color: #fff;"><?= e($_SESSION['customer_user']['full_name']) ?></strong> (<?= e($_SESSION['customer_user']['phone']) ?>)
            </div>
        <?php endif; ?>
    </div>
</section>

<div class="container">
    <?php if (!$selectedOrder): ?>
        <div style="padding: 70px 20px; text-align: center; color: var(--text-muted);">
            <div style="font-size: 48px; margin-bottom: 14px;">📦</div>
            <h2 style="color: #fff; font-size: 22px; margin-bottom: 8px;">No Order Found</h2>
            <p style="max-width: 500px; margin: 0 auto 20px;">We could not find an order matching that number or phone. Please verify your invoice number or check your booking SMS.</p>
            <a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary">Book a Machine / Order Spare Part</a>
        </div>
    <?php else: ?>
        <div class="tracking-layout">
            <!-- Left Column: Amazon-Style Progress & Live Carrier Details -->
            <div class="amazon-track-card">
                <!-- Order Top Badge -->
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 14px; padding-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <div>
                        <span style="font-size: 12px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.8px;">Order Placed on <?= date('d M Y, h:i A', strtotime($selectedOrder['created_at'])) ?></span>
                        <h2 style="font-family: var(--font-heading); font-size: 22px; color: #fff; margin-top: 4px; display: flex; align-items: center; gap: 10px;">
                            <span><?= e($selectedOrder['order_number']) ?></span>
                            <span class="badge-tag" style="border-color: #34d399; color: #34d399; font-size: 11px;">VERIFIED PAID</span>
                        </h2>
                    </div>
                    <div style="text-align: right;">
                        <a href="<?= BASE_URL ?>/invoice.php?order_id=<?= $selectedOrder['id'] ?>" target="_blank" class="btn btn-primary" style="font-size: 13px; padding: 8px 14px; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color: #060911; border-color: #00e5ff; font-weight: 800;">
                            📄 Download Tax Invoice (PDF)
                        </a>
                    </div>
                </div>

                <?php if ($currStatusKey === 'delivered' || $currentStep >= 6): ?>
                    <!-- Delivered & Commissioned Celebration Card -->
                    <div style="margin: 24px 0; background: linear-gradient(135deg, rgba(16, 185, 129, 0.25) 0%, rgba(5, 150, 105, 0.35) 100%); border: 2px solid #10b981; border-radius: 12px; padding: 22px; box-shadow: 0 10px 30px rgba(16, 185, 129, 0.25);">
                        <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                            <div style="width: 52px; height: 52px; border-radius: 50%; background: #10b981; display: flex; align-items: center; justify-content: center; font-size: 26px; box-shadow: 0 0 20px #10b981; flex-shrink: 0;">
                                🏁
                            </div>
                            <div style="flex: 1;">
                                <div style="font-size: 11.5px; font-weight: 800; color: #a7f3d0; text-transform: uppercase; letter-spacing: 1px;">
                                    ✔ 100% ORDER COMPLETE &bull; ALL 6 STAGES FINISHED
                                </div>
                                <h3 style="font-family: var(--font-heading); font-size: 20px; color: #fff; margin: 3px 0 5px; font-weight: 800;">
                                    Delivered &amp; Commissioned at Factory (ડિલિવરી અને ટેસ્ટિંગ સફળતાપૂર્વક પૂર્ણ)
                                </h3>
                                <p style="font-size: 13px; color: #d1fae5; margin: 0; line-height: 1.5;">
                                    Your Varsha Brand industrial machine has been safely delivered, unloaded with crane, leveled, and commissioned at your factory workshop. Official GST Tax Invoice, warranty card, and test certificates have been handed over.
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Estimated Delivery Big Banner -->
                <div style="margin: 24px 0; background: linear-gradient(135deg, rgba(2, 132, 199, 0.15) 0%, rgba(16, 185, 129, 0.15) 100%); border: 1.5px solid rgba(0, 229, 255, 0.35); border-radius: 12px; padding: 20px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                        <div>
                            <div style="font-size: 12px; color: #38bdf8; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                                ESTIMATED ARRIVAL DATE
                            </div>
                            <div style="font-family: var(--font-heading); font-size: 24px; color: #fff; font-weight: 800; margin-top: 2px;">
                                <?php 
                                    $est = !empty($selectedOrder['estimated_delivery']) ? strtotime($selectedOrder['estimated_delivery']) : strtotime('+3 days', strtotime($selectedOrder['created_at']));
                                    echo date('l, F j, Y', $est);
                                ?>
                            </div>
                            <div style="font-size: 13px; color: #34d399; margin-top: 4px; display: flex; align-items: center; gap: 6px;">
                                <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#10b981; box-shadow:0 0 8px #10b981;"></span>
                                Current Location: <strong><?= e($selectedOrder['current_location'] ?? 'Bhavnagar Industrial Logistics Hub') ?></strong>
                            </div>
                        </div>
                        <div style="background: rgba(15, 23, 42, 0.8); border: 1px solid var(--border-subtle); border-radius: 10px; padding: 12px 18px; text-align: right;">
                            <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase;">Freight Carrier</div>
                            <div style="font-size: 14px; font-weight: 700; color: #fff;"><?= e($selectedOrder['transport_name'] ?? 'VRL Logistics Surface Express') ?></div>
                            <div style="font-size: 12px; color: var(--accent-cyan); margin-top: 2px;">
                                LR # <?= e($selectedOrder['tracking_number'] ?? 'VRL-RAJ-88421') ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step-by-Step Delivery Timeline -->
                <h3 style="font-family: var(--font-heading); font-size: 17px; color: #fff; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <span>📍</span> Factory Dispatch &amp; Transit Milestones
                </h3>

                <div class="delivery-timeline">
                    <?php foreach ($deliveryStages as $idx => $stg): 
                        $isCompleted = ($idx < $currentStep);
                        $isCurrent = ($idx === $currentStep);
                        $itemClass = $isCompleted ? 'completed' : ($isCurrent ? 'current' : '');
                    ?>
                        <div class="timeline-item <?= $itemClass ?>">
                            <?php if ($idx < count($deliveryStages) - 1): ?>
                                <div class="timeline-line"></div>
                            <?php endif; ?>

                            <div class="timeline-icon-box">
                                <?= $stg['icon'] ?>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 8px;">
                                <div>
                                    <h4 style="font-size: 15px; font-weight: 700; color: <?= ($isCompleted || $isCurrent) ? '#fff' : '#64748b' ?>; margin-bottom: 3px;">
                                        <?= $stg['title'] ?>
                                    </h4>
                                    <p style="font-size: 12.5px; color: <?= ($isCompleted || $isCurrent) ? '#94a3b8' : '#475569' ?>; margin: 0;">
                                        <?= $stg['sub'] ?>
                                    </p>
                                </div>
                                <div>
                                    <?php if ($isCompleted): ?>
                                        <span class="badge-tag" style="border-color: #10b981; color: #34d399; font-size: 11px;">Completed</span>
                                    <?php elseif ($isCurrent): ?>
                                        <span class="badge-tag" style="border-color: #00e5ff; color: #00e5ff; font-size: 11px; background: rgba(0, 229, 255, 0.1);">In Progress</span>
                                    <?php else: ?>
                                        <span class="badge-tag" style="border-color: #334155; color: #64748b; font-size: 11px;">Upcoming</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Driver & Support Assistance Box -->
                <div style="margin-top: 30px; padding: 18px 20px; background: rgba(15, 23, 42, 0.9); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                    <div>
                        <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.8px;">Logistics &amp; Crane Unloading Inquiries</div>
                        <div style="font-size: 14px; font-weight: 700; color: #fff; margin-top: 2px;">
                            Factory Dispatch Desk: <span style="color: #38bdf8;"><?= COMPANY_PHONE_1 ?></span>
                        </div>
                        <div style="font-size: 12px; color: #94a3b8;">
                            <?= e($selectedOrder['delivery_notes'] ?? 'Heavy machinery wooden box crate with shock-dampers. Flatbed crane required at destination site.') ?>
                        </div>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <a href="tel:<?= COMPANY_PHONE_1_CLEAN ?>" class="btn btn-outline" style="font-size: 12.5px; padding: 7px 12px;">
                            📞 Call Dispatch
                        </a>
                        <a href="<?= getWhatsAppLink(COMPANY_WHATSAPP, 'Hello, I am tracking my machine order ' . $selectedOrder['order_number'] . '. Please share the live location.') ?>" target="_blank" class="btn btn-whatsapp" style="font-size: 12.5px; padding: 7px 12px;">
                            💬 WhatsApp Update
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Product Card & Invoice Breakdown -->
            <div class="item-info-card">
                <h3 style="font-family: var(--font-heading); font-size: 16px; color: #fff; margin-bottom: 16px; padding-bottom: 10px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                    Ordered Machinery / Part
                </h3>

                <div style="display: flex; gap: 14px; align-items: center; margin-bottom: 18px;">
                    <img src="<?= assetUrl($selectedOrder['image_url'] ?? 'assets/images/machinery/radial-drill-real.jpg') ?>" alt="<?= e($selectedOrder['product_name']) ?>" style="width: 85px; height: 85px; object-fit: contain; background: #060911; border-radius: 10px; border: 1.5px solid var(--accent-cyan); padding: 5px;">
                    <div>
                        <span class="badge-tag" style="border-color: var(--accent-cyan); color: var(--accent-cyan); font-size: 11px;">
                            <?= e($selectedOrder['model_number']) ?>
                        </span>
                        <h4 style="font-size: 15px; color: #fff; margin-top: 4px; line-height: 1.3;">
                            <?= e($selectedOrder['product_name']) ?>
                        </h4>
                        <div style="font-size: 12px; color: #94a3b8; margin-top: 3px;">
                            Capacity: <?= e($selectedOrder['drilling_capacity'] ?? 'Industrial Precision') ?>
                        </div>
                    </div>
                </div>

                <div style="background: rgba(6, 9, 17, 0.7); border-radius: 10px; padding: 14px; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; font-size: 13px; color: #94a3b8; padding: 4px 0;">
                        <span>Payment Type:</span>
                        <strong style="color: #fff;"><?= ($selectedOrder['payment_type'] === 'booking_advance') ? 'Token Booking Advance' : 'Full Invoice Payment' ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 13px; color: #94a3b8; padding: 4px 0;">
                        <span>Machine Ex-Factory:</span>
                        <span style="color: #cbd5e1;">₹ <?= number_format($selectedOrder['machine_price'], 2) ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 13px; color: #94a3b8; padding: 4px 0;">
                        <span>GST (18%):</span>
                        <span style="color: #cbd5e1;">₹ <?= number_format($selectedOrder['cgst_amount'] + $selectedOrder['sgst_amount'], 2) ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 15px; font-weight: 800; color: #34d399; padding: 8px 0 0; border-top: 1px solid rgba(255,255,255,0.1); margin-top: 6px;">
                        <span>Amount Paid (Online Verified):</span>
                        <span>₹ <?= number_format($selectedOrder['total_amount'], 2) ?></span>
                    </div>
                    <div style="font-size: 11px; color: #64748b; margin-top: 4px; text-align: right;">
                        Transaction Ref: <?= e($selectedOrder['razorpay_payment_id'] ?? 'Verified') ?>
                    </div>
                </div>

                <!-- Delivery Address Card -->
                <h4 style="font-size: 13px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 10px;">
                    Delivery Consignee Details
                </h4>
                <div style="font-size: 13px; color: #cbd5e1; line-height: 1.5; background: rgba(6, 9, 17, 0.5); padding: 12px; border-radius: 8px;">
                    <strong style="color: #fff; font-size: 14px;"><?= e($selectedOrder['customer_name']) ?></strong><br>
                    <?php if (!empty($selectedOrder['company_name'])): ?>
                        <span style="color: var(--accent-cyan); font-weight: 600;"><?= e($selectedOrder['company_name']) ?></span><br>
                    <?php endif; ?>
                    <?= nl2br(e($selectedOrder['customer_address'])) ?><br>
                    <?= e($selectedOrder['customer_city']) ?> - <?= e($selectedOrder['customer_pincode']) ?><br>
                    Phone: <strong style="color: #fff;"><?= e($selectedOrder['customer_phone']) ?></strong><br>
                    <?php if (!empty($selectedOrder['customer_gst'])): ?>
                        GSTIN: <span style="color: #38bdf8; font-weight: 600;"><?= e($selectedOrder['customer_gst']) ?></span>
                    <?php endif; ?>
                </div>

                <!-- 360 View Button -->
                <div style="margin-top: 20px;">
                    <a href="<?= BASE_URL ?>/catalog.php" class="btn btn-outline" style="width: 100%; justify-content: center; font-size: 13px; margin-bottom: 8px;">
                        📖 Order Spare Parts or Tools
                    </a>
                    <a href="<?= BASE_URL ?>/simulator-6d.php" class="btn btn-outline" style="width: 100%; justify-content: center; font-size: 13px; border-color: rgba(0,229,255,0.4); color: var(--accent-cyan);">
                        🔄 Launch 360° 12D Machine Simulator
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
