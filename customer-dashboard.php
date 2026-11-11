<?php
/**
 * Customer Account Portal & Orders Dashboard
 * Shree Varsha Engineering Works - Varsha Brand
 * Tracks user-wise products bought, bills/invoices, and Pending/Success/Failed orders
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$pdo = Database::getConnection();

$loggedInCustomer = $_SESSION['customer_user'] ?? null;
$phoneSearch = sanitizeString($_REQUEST['search_phone'] ?? '');

$customerUser = $loggedInCustomer;
$customerOrders = [];

try {
    if ($customerUser) {
        $cPhone = $customerUser['phone'] ?? '';
        $cEmail = $customerUser['email'] ?? '';
        $cId = intval($customerUser['id'] ?? 0);

        $stmt = $pdo->prepare("
            SELECT o.*, p.image_url, p.drilling_capacity, p.spindle_speeds, p.motor_power 
            FROM orders o 
            LEFT JOIN products p ON o.product_id = p.id 
            WHERE o.customer_id = :cid OR o.customer_phone = :ph OR (o.customer_email IS NOT NULL AND o.customer_email != '' AND o.customer_email = :em)
            ORDER BY o.id DESC
        ");
        $stmt->execute([
            ':cid' => $cId,
            ':ph'  => $cPhone,
            ':em'  => $cEmail
        ]);
        $customerOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif (!empty($phoneSearch)) {
        $cleanPh = preg_replace('/[^0-9]/', '', $phoneSearch);
        $stmt = $pdo->prepare("
            SELECT o.*, p.image_url, p.drilling_capacity, p.spindle_speeds, p.motor_power 
            FROM orders o 
            LEFT JOIN products p ON o.product_id = p.id 
            WHERE o.customer_phone LIKE :ph 
            ORDER BY o.id DESC
        ");
        $stmt->execute([':ph' => '%' . substr($cleanPh, -10)]);
        $customerOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}

// Compute Statistics
$totalUnitsBought = 0;
$successfulCount = 0;
$pendingCount = 0;
$failedCount = 0;
$totalSpent = 0.0;

foreach ($customerOrders as $ord) {
    $qty = intval($ord['quantity'] ?? 1);
    $status = strtolower($ord['payment_status'] ?? 'pending');
    $orderStatus = strtolower($ord['order_status'] ?? 'confirmed');

    if ($status === 'paid') {
        $successfulCount++;
        $totalUnitsBought += $qty;
        $totalSpent += floatval($ord['total_amount'] ?? 0);
    } elseif ($status === 'failed' || $status === 'cancelled' || $orderStatus === 'cancelled') {
        $failedCount++;
    } else {
        // Pending / Pending Verification / Pending COD
        $pendingCount++;
        $totalUnitsBought += $qty;
    }
}

$pageTitle = 'My Machinery Orders, Bills & Account | ' . BRAND_NAME . ' Bhavnagar';
$currentNav = 'account';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="section" style="padding: 40px 0 70px; background: #060911;">
    <div class="container" style="max-width: 1140px;">
        
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; margin-bottom: 25px;">
            <div>
                <span class="pill-badge" style="background: rgba(0, 229, 255, 0.15); border-color: #00e5ff; color: #00e5ff;">
                    👤 CUSTOMER ACCOUNT &amp; ORDERS PORTAL
                </span>
                <h1 style="font-family: var(--font-heading); font-size: 26px; color: #fff; margin-top: 8px;">
                    મારું એકાઉન્ટ, ખરીદેલ પ્રોડક્ટ્સ અને બીલ (My Orders &amp; Tax Bills)
                </h1>
                <p style="font-size: 13.5px; color: var(--text-secondary); max-width: 650px; margin-top: 4px;">
                    Track all your industrial drill machine bookings, download official GST Tax Invoices, and monitor real-time order status.
                </p>
            </div>

            <?php if ($customerUser): ?>
                <div style="background: rgba(15, 23, 42, 0.85); border: 1.5px solid var(--border-cyan); border-radius: 12px; padding: 12px 18px; display: flex; align-items: center; gap: 12px;">
                    <div style="width: 42px; height: 42px; border-radius: 50%; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); display: flex; align-items: center; justify-content: center; font-size: 18px; color: #060911; font-weight: 800;">
                        <?= strtoupper(substr($customerUser['full_name'] ?? 'U', 0, 1)) ?>
                    </div>
                    <div>
                        <div style="font-size: 14px; font-weight: 800; color: #fff;"><?= e($customerUser['full_name'] ?? 'Valued Customer') ?></div>
                        <div style="font-size: 11.5px; color: #94a3b8;"><?= e($customerUser['phone'] ?? '') ?> &bull; <?= e($customerUser['city'] ?? 'Bhavnagar') ?></div>
                    </div>
                    <a href="<?= BASE_URL ?>/logout-customer.php" class="btn btn-outline" style="padding: 5px 10px; font-size: 11px; border-color: #f87171; color: #f87171; text-decoration: none; margin-left: 8px;">
                        Sign Out
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!$customerUser && empty($customerOrders)): ?>
            <!-- Login / Mobile Search Banner if Guest -->
            <div style="background: rgba(15, 23, 42, 0.95); border: 2px dashed #38bdf8; border-radius: 14px; padding: 30px; text-align: center; margin-bottom: 30px;">
                <div style="font-size: 40px; margin-bottom: 10px;">🔒</div>
                <h2 style="font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 8px;">
                    Sign In to View All Your Purchases &amp; Bills
                </h2>
                <p style="font-size: 14px; color: #94a3b8; max-width: 520px; margin: 0 auto 20px; line-height: 1.5;">
                    Sign in with your registered mobile number to see all machines you bought, download certified GST tax invoices, and track live order progress.
                </p>
                <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                    <button type="button" onclick="openCustomerAuthModal('Sign in to view your orders and bills')" class="btn btn-primary" style="padding: 12px 24px; font-weight: 800; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color: #060911; border-color: #00e5ff;">
                        👤 Sign In / Register Account
                    </button>
                </div>

                <div style="margin: 24px auto 0; max-width: 440px; border-top: 1px dashed rgba(255,255,255,0.1); padding-top: 18px;">
                    <div style="font-size: 12px; color: #94a3b8; margin-bottom: 8px;">Or search by registered mobile number:</div>
                    <form method="GET" action="customer-dashboard.php" style="display: flex; gap: 8px;">
                        <input type="tel" name="search_phone" required placeholder="Enter 10-digit Mobile Number" value="<?= e($phoneSearch) ?>" class="form-control" style="font-size: 13.5px; height: 42px; background: #060911; color: #fff;">
                        <button type="submit" class="btn btn-primary" style="height: 42px; padding: 0 16px; font-weight: 700; white-space: nowrap;">
                            🔍 Lookup Orders
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- 4 Metric Cards: Total Products, Success Orders, Pending Orders, Failed Orders -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 30px;">
            <!-- 1. Total Products Bought -->
            <div style="background: rgba(15, 23, 42, 0.9); border: 1.5px solid rgba(0, 229, 255, 0.35); border-radius: 12px; padding: 18px; box-shadow: 0 8px 25px rgba(0,0,0,0.5);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <span style="font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">કુલ ખરીદેલ પ્રોડક્ટ્સ</span>
                    <span style="font-size: 20px;">📦</span>
                </div>
                <div style="font-size: 28px; font-weight: 900; color: #00e5ff; font-family: 'Space Grotesk', sans-serif;">
                    <?= $totalUnitsBought ?> <span style="font-size: 14px; font-weight: 700; color: #94a3b8;">Units</span>
                </div>
                <div style="font-size: 11.5px; color: #cbd5e1; margin-top: 4px;">
                    Total Drill Machines &amp; Spare Parts
                </div>
            </div>

            <!-- 2. Successful / Paid Orders -->
            <div style="background: rgba(15, 23, 42, 0.9); border: 1.5px solid rgba(16, 185, 129, 0.4); border-radius: 12px; padding: 18px; box-shadow: 0 8px 25px rgba(0,0,0,0.5);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <span style="font-size: 12px; font-weight: 800; color: #34d399; text-transform: uppercase; letter-spacing: 0.5px;">સફળ / પેઇડ ઓર્ડર</span>
                    <span style="font-size: 20px;">✔</span>
                </div>
                <div style="font-size: 28px; font-weight: 900; color: #34d399; font-family: 'Space Grotesk', sans-serif;">
                    <?= $successfulCount ?> <span style="font-size: 14px; font-weight: 700; color: #94a3b8;">Success</span>
                </div>
                <div style="font-size: 11.5px; color: #cbd5e1; margin-top: 4px;">
                    100% Verified &amp; Realized Payments
                </div>
            </div>

            <!-- 3. Pending Orders -->
            <div style="background: rgba(15, 23, 42, 0.9); border: 1.5px solid rgba(245, 158, 11, 0.4); border-radius: 12px; padding: 18px; box-shadow: 0 8px 25px rgba(0,0,0,0.5);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <span style="font-size: 12px; font-weight: 800; color: #fbbf24; text-transform: uppercase; letter-spacing: 0.5px;">પેન્ડિંગ ઓર્ડર</span>
                    <span style="font-size: 20px;">⏳</span>
                </div>
                <div style="font-size: 28px; font-weight: 900; color: #fbbf24; font-family: 'Space Grotesk', sans-serif;">
                    <?= $pendingCount ?> <span style="font-size: 14px; font-weight: 700; color: #94a3b8;">Pending</span>
                </div>
                <div style="font-size: 11.5px; color: #cbd5e1; margin-top: 4px;">
                    Bank Transfer / COD / Verification Awaited
                </div>
            </div>

            <!-- 4. Failed / Cancelled Orders -->
            <div style="background: rgba(15, 23, 42, 0.9); border: 1.5px solid rgba(239, 68, 68, 0.4); border-radius: 12px; padding: 18px; box-shadow: 0 8px 25px rgba(0,0,0,0.5);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <span style="font-size: 12px; font-weight: 800; color: #f87171; text-transform: uppercase; letter-spacing: 0.5px;">ફેઇલ / રદ્દ ઓર્ડર</span>
                    <span style="font-size: 20px;">❌</span>
                </div>
                <div style="font-size: 28px; font-weight: 900; color: #f87171; font-family: 'Space Grotesk', sans-serif;">
                    <?= $failedCount ?> <span style="font-size: 14px; font-weight: 700; color: #94a3b8;">Failed</span>
                </div>
                <div style="font-size: 11.5px; color: #cbd5e1; margin-top: 4px;">
                    Cancelled or Gateway Limit Exceeded
                </div>
            </div>
        </div>

        <!-- Orders & Official Bills Table -->
        <div style="background: rgba(15, 23, 42, 0.95); border: 1.5px solid var(--border-cyan); border-radius: 14px; padding: 22px; box-shadow: var(--shadow-card);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 10px;">
                <div style="font-size: 16px; font-weight: 800; color: #fff; display: flex; align-items: center; gap: 8px;">
                    <span>📄</span> All Orders, Purchases &amp; Official Tax Invoices (તમામ ઓર્ડર્સ અને બીલ)
                </div>
                <div style="display: flex; gap: 6px;">
                    <a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary" style="font-size: 12px; padding: 7px 14px; background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399; font-weight: 700; text-decoration: none;">
                        + New Machine Booking
                    </a>
                </div>
            </div>

            <?php if (empty($customerOrders)): ?>
                <div style="text-align: center; padding: 40px 20px; color: #94a3b8;">
                    <div style="font-size: 32px; margin-bottom: 10px;">📭</div>
                    <div style="font-size: 15px; font-weight: 700; color: #fff; margin-bottom: 4px;">No Orders Found</div>
                    <div style="font-size: 13px;">You have not placed any machinery orders yet, or your search query did not match.</div>
                    <a href="<?= BASE_URL ?>/machines.php" class="btn btn-outline" style="margin-top: 14px; display: inline-block; padding: 8px 18px; font-size: 12.5px; border-color: #00e5ff; color: #00e5ff; text-decoration: none;">
                        Explore 2026 Machine Catalog →
                    </a>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 13px; min-width: 820px;">
                        <thead>
                            <tr style="border-bottom: 1.5px solid rgba(255,255,255,0.12); text-align: left; color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                                <th style="padding: 10px 12px;">Order &amp; Date</th>
                                <th style="padding: 10px 12px;">Product / Machine</th>
                                <th style="padding: 10px 12px; text-align: center;">Qty</th>
                                <th style="padding: 10px 12px; text-align: right;">Total Amount</th>
                                <th style="padding: 10px 12px; text-align: center;">Payment Status</th>
                                <th style="padding: 10px 12px; text-align: center;">Progress</th>
                                <th style="padding: 10px 12px; text-align: right;">Bills &amp; Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customerOrders as $co): 
                                $pStat = strtolower($co['payment_status'] ?? 'pending');
                                $isPaid = ($pStat === 'paid');
                                $isFailed = in_array($pStat, ['failed', 'cancelled']);
                                $isPending = !$isPaid && !$isFailed;
                            ?>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.06); transition: background 0.15s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                                    
                                    <!-- Order # & Date -->
                                    <td style="padding: 14px 12px; vertical-align: middle;">
                                        <div style="font-weight: 800; color: #00e5ff; font-size: 13px;">
                                            <?= e($co['order_number']) ?>
                                        </div>
                                        <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">
                                            <?= date('d M Y, h:i A', strtotime($co['created_at'])) ?>
                                        </div>
                                        <div style="font-size: 10.5px; color: #64748b;">
                                            Invoice: #<?= e($co['invoice_number'] ?? 'PENDING') ?>
                                        </div>
                                    </td>

                                    <!-- Product / Machine -->
                                    <td style="padding: 14px 12px; vertical-align: middle;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <?php if (!empty($co['image_url'])): ?>
                                                <img src="<?= assetUrl($co['image_url']) ?>" alt="Product" style="width: 44px; height: 44px; object-fit: contain; background: #060911; border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; padding: 2px;">
                                            <?php endif; ?>
                                            <div>
                                                <div style="font-weight: 700; color: #fff; font-size: 13px;">
                                                    <?= e($co['product_name'] ?? 'Varsha Machine') ?>
                                                </div>
                                                <div style="font-size: 11px; color: #38bdf8; font-weight: 600;">
                                                    Model: <?= e($co['model_number'] ?? 'SVEW') ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Qty -->
                                    <td style="padding: 14px 12px; text-align: center; vertical-align: middle;">
                                        <span style="font-weight: 800; color: #fff; font-size: 13px;">
                                            <?= intval($co['quantity'] ?? 1) ?>
                                        </span>
                                    </td>

                                    <!-- Total Amount -->
                                    <td style="padding: 14px 12px; text-align: right; vertical-align: middle;">
                                        <div style="font-weight: 800; color: #34d399; font-size: 14px; font-family: 'Space Grotesk', sans-serif;">
                                            ₹ <?= number_format($co['total_amount'] ?? 0, 2) ?>
                                        </div>
                                        <div style="font-size: 10px; color: #94a3b8;">
                                            <?= (($co['payment_type'] ?? '') === 'booking_advance') ? 'Advance Token (₹25k)' : 'Full Payment' ?>
                                        </div>
                                    </td>

                                    <!-- Payment Status Badge -->
                                    <td style="padding: 14px 12px; text-align: center; vertical-align: middle;">
                                        <?php if ($isPaid): ?>
                                            <span style="display: inline-block; padding: 3px 9px; border-radius: 4px; font-size: 11px; font-weight: 800; background: #dcfce7; color: #15803d; border: 1px solid #16a34a;">
                                                ✔ SUCCESS (PAID)
                                            </span>
                                        <?php elseif ($isFailed): ?>
                                            <span style="display: inline-block; padding: 3px 9px; border-radius: 4px; font-size: 11px; font-weight: 800; background: #fee2e2; color: #b91c1c; border: 1px solid #ef4444;">
                                                ❌ FAILED / CANCELLED
                                            </span>
                                        <?php else: ?>
                                            <span style="display: inline-block; padding: 3px 9px; border-radius: 4px; font-size: 11px; font-weight: 800; background: #fef3c7; color: #b45309; border: 1px solid #f59e0b;">
                                                ⏳ PENDING
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Progress -->
                                    <td style="padding: 14px 12px; text-align: center; vertical-align: middle;">
                                        <span style="font-size: 11px; font-weight: 700; color: #38bdf8; text-transform: uppercase;">
                                            <?= e(str_replace('_', ' ', $co['order_status'] ?? 'Confirmed')) ?>
                                        </span>
                                    </td>

                                    <!-- Actions: View Bill, Track Order, E-Way Bill -->
                                    <td style="padding: 14px 12px; text-align: right; vertical-align: middle;">
                                        <div style="display: inline-flex; gap: 6px; align-items: center;">
                                            <?php if (!$isFailed): ?>
                                                <a href="<?= BASE_URL ?>/invoice.php?order_id=<?= $co['id'] ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 11px; font-weight: 700; text-decoration: none; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color: #060911; border-color: #00e5ff;">
                                                    📄 View Bill
                                                </a>
                                                <a href="<?= BASE_URL ?>/track-order.php?order_num=<?= urlencode($co['order_number']) ?>" class="btn btn-outline" style="padding: 5px 10px; font-size: 11px; font-weight: 700; border-color: #38bdf8; color: #38bdf8; text-decoration: none;">
                                                    🚚 Track
                                                </a>
                                                <a href="<?= BASE_URL ?>/eway_bill.php?order_id=<?= $co['id'] ?>" class="btn btn-outline" style="padding: 5px 10px; font-size: 11px; font-weight: 700; border-color: #fbbf24; color: #fbbf24; text-decoration: none;" title="GST E-Way Bill">
                                                    🏛️ E-Way
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= BASE_URL ?>/checkout.php?product_id=<?= $co['product_id'] ?>" class="btn btn-outline" style="padding: 5px 10px; font-size: 11px; font-weight: 700; border-color: #34d399; color: #34d399; text-decoration: none;">
                                                    🔄 Retry Order
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
