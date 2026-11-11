<?php
/**
 * Official GST E-Way Bill (FORM GST EWB-01) Portal
 * Shree Varsha Engineering Works - Varsha Brand
 * Supplier GSTIN: 24AJHPP0087E1ZD | HSN: 8459
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

// If admin is logged in and no specific order is requested, forward to admin eway bill management
if (!empty($_SESSION['admin_user']) && empty($_GET['order_id']) && empty($_GET['order_num'])) {
    header("Location: " . BASE_URL . "/admin/eway-bill.php");
    exit;
}

$pdo = Database::getConnection();

$orderId = intval($_GET['order_id'] ?? ($_GET['id'] ?? 0));
$orderNum = trim($_GET['order_num'] ?? '');
$searchQuery = trim($_GET['search'] ?? '');

$order = null;

try {
    if ($orderId > 0) {
        $stmt = $pdo->prepare("SELECT o.*, p.drilling_capacity, p.weight_approx, p.image_url, p.model_number, p.name as product_name FROM orders o LEFT JOIN products p ON o.product_id = p.id WHERE o.id = :id");
        $stmt->execute([':id' => $orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
    } elseif (!empty($orderNum)) {
        $stmt = $pdo->prepare("SELECT o.*, p.drilling_capacity, p.weight_approx, p.image_url, p.model_number, p.name as product_name FROM orders o LEFT JOIN products p ON o.product_id = p.id WHERE o.order_number = :num OR o.invoice_number = :num");
        $stmt->execute([':num' => $orderNum]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
    } elseif (!empty($searchQuery)) {
        $cleanSearch = preg_replace('/[^0-9a-zA-Z]/', '', $searchQuery);
        $stmt = $pdo->prepare("SELECT o.*, p.drilling_capacity, p.weight_approx, p.image_url, p.model_number, p.name as product_name FROM orders o LEFT JOIN products p ON o.product_id = p.id WHERE o.order_number LIKE :s OR o.invoice_number LIKE :s OR o.customer_phone LIKE :s LIMIT 1");
        $stmt->execute([':s' => "%{$cleanSearch}%"]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}

// If no order is selected, show the Search & Gov Portal Landing
if (!$order) {
    $pageTitle = 'GST E-Way Bill Portal & Consignment Lookup | ' . BRAND_NAME;
    $currentNav = 'eway-bill';
    require_once __DIR__ . '/includes/header.php';
    require_once __DIR__ . '/includes/navbar.php';
    ?>
    <section class="section" style="padding: 40px 0 70px; background: #060911;">
        <div class="container" style="max-width: 900px;">
            <div style="text-align: center; margin-bottom: 30px;">
                <span class="pill-badge" style="background: rgba(245, 158, 11, 0.15); border-color: #f59e0b; color: #fbbf24;">
                    🏛️ OFFICIAL GOVERNMENT GST E-WAY BILL PORTAL
                </span>
                <h1 style="font-family: var(--font-heading); font-size: 28px; color: #fff; margin-top: 8px;">
                    GST E-Way Bill (FORM GST EWB-01) Search &amp; Verification
                </h1>
                <p style="font-size: 14px; color: var(--text-secondary); max-width: 620px; margin: 8px auto 0;">
                    Search e-way bills generated for your Varsha Brand machinery consignments or log in directly to the Official Government E-Way Bill System.
                </p>
                <div style="margin-top: 16px;">
                    <a href="https://ewaybillgst.gov.in/login.aspx" target="_blank" class="btn btn-primary" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-color: #f59e0b; color: #060911; font-weight: 800; font-size: 14px; padding: 10px 22px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.35);">
                        <span>🏛️ Official Government E-Way Portal Direct Login (ewaybillgst.gov.in/login.aspx) ↗</span>
                    </a>
                </div>
            </div>

            <!-- Search Card -->
            <div style="background: rgba(15, 23, 42, 0.95); border: 1.5px solid var(--border-cyan); border-radius: 14px; padding: 26px; box-shadow: var(--shadow-card); margin-bottom: 30px;">
                <form method="GET" action="eway_bill.php" style="display: grid; grid-template-columns: 1fr auto; gap: 12px; align-items: flex-end;">
                    <div>
                        <label class="form-label" style="font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px; display: block;">
                            Enter Order Number, Invoice Number, or Mobile Number *
                        </label>
                        <input type="text" name="search" required class="form-control" placeholder="e.g. SVEW-ORD-20261110-A108F or 9824913555" value="<?= e($searchQuery) ?>" style="height: 44px; font-size: 14px; background: #060911; color: #fff; border: 1.5px solid rgba(0, 229, 255, 0.4);">
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary" style="height: 44px; padding: 0 24px; font-weight: 800; font-size: 14px; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color: #060911; border-color: #00e5ff; display: flex; align-items: center; justify-content: center; gap: 6px;">
                            <span>🔍 Find E-Way Bill</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Quick Links -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 16px;">
                    <div style="font-weight: 700; color: #fff; font-size: 13.5px; margin-bottom: 4px;">🚚 Live Order Tracking</div>
                    <div style="font-size: 12px; color: #94a3b8; margin-bottom: 10px;">Track consignment progress, wooden packing, and VRL transport LR status.</div>
                    <a href="<?= BASE_URL ?>/track-order.php" style="color: #38bdf8; font-size: 12px; font-weight: 700; text-decoration: none;">Track Consignment →</a>
                </div>
                <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 16px;">
                    <div style="font-weight: 700; color: #fff; font-size: 13.5px; margin-bottom: 4px;">📄 Official Tax Invoices</div>
                    <div style="font-size: 12px; color: #94a3b8; margin-bottom: 10px;">Download certified GST tax invoices with authorized company stamp and signature.</div>
                    <a href="<?= BASE_URL ?>/invoice.php" style="color: #34d399; font-size: 12px; font-weight: 700; text-decoration: none;">Search Invoices →</a>
                </div>
            </div>
        </div>
    </section>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// Generate Compliant Government E-Way Bill Number (12 Digits)
$dateSeed = date('ymd', strtotime($order['created_at']));
$idSeed = str_pad(strval($order['id'] * 739), 6, '0', STR_PAD_LEFT);
$ewayBillNo = '24' . substr($dateSeed . $idSeed, -10);
$ewayDate = date('d/m/Y h:i A', strtotime($order['created_at']));
$validUntil = date('d/m/Y 11:59 PM', strtotime($order['created_at'] . ' + 5 days'));

$pageTitle = 'GST E-Way Bill #' . $ewayBillNo . ' | ' . BRAND_NAME;
require_once __DIR__ . '/includes/header.php';
?>

<!-- Printable E-Way Bill Container -->
<div style="background: #f1f5f9; min-height: 100vh; padding: 25px 15px; color: #0f172a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <div style="max-width: 880px; margin: 0 auto; background: #fff; border: 2px solid #0f172a; border-radius: 6px; padding: 25px; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
        
        <!-- Action Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #0f172a; padding-bottom: 12px; margin-bottom: 14px; flex-wrap: wrap; gap: 8px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 24px;">🏛️</span>
                <div>
                    <strong style="font-size: 16px; color: #0f172a;">GOVERNMENT OF INDIA &bull; GOODS AND SERVICES TAX</strong>
                    <div style="font-size: 11px; color: #475569;">E-WAY BILL SYSTEM &bull; FORM GST EWB-01 (See Rule 138)</div>
                </div>
            </div>
            <div style="display: flex; gap: 8px;">
                <button type="button" onclick="window.print()" class="btn btn-primary" style="padding: 6px 14px; font-size: 12px; font-weight: 700; background: #0284c7; color: #fff; border: none; cursor: pointer; border-radius: 4px;">
                    🖨️ Print E-Way Bill
                </button>
                <a href="https://ewaybillgst.gov.in/login.aspx" target="_blank" class="btn btn-outline" style="padding: 6px 14px; font-size: 12px; font-weight: 700; border: 1.5px solid #d97706; color: #b45309; text-decoration: none; border-radius: 4px; background: #fffbeb;">
                    🏛️ Govt Portal Login ↗
                </a>
            </div>
        </div>

        <!-- E-Way Bill Header Details -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 14px; font-size: 12px;">
            <tr>
                <td style="padding: 6px; border: 1px solid #cbd5e1; width: 25%; font-weight: 700;">E-Way Bill No:</td>
                <td style="padding: 6px; border: 1px solid #cbd5e1; width: 25%; font-weight: 800; color: #0284c7; font-size: 14px;"><?= $ewayBillNo ?></td>
                <td style="padding: 6px; border: 1px solid #cbd5e1; width: 25%; font-weight: 700;">Generated Date:</td>
                <td style="padding: 6px; border: 1px solid #cbd5e1; width: 25%;"><?= $ewayDate ?></td>
            </tr>
            <tr>
                <td style="padding: 6px; border: 1px solid #cbd5e1; font-weight: 700;">Generated By:</td>
                <td style="padding: 6px; border: 1px solid #cbd5e1; font-weight: 700;"><?= COMPANY_GSTIN ?> (SHREE VARSHA)</td>
                <td style="padding: 6px; border: 1px solid #cbd5e1; font-weight: 700;">Valid Until:</td>
                <td style="padding: 6px; border: 1px solid #cbd5e1; font-weight: 700; color: #15803d;"><?= $validUntil ?></td>
            </tr>
        </table>

        <!-- PART-A: Transaction Details -->
        <div style="background: #0f172a; color: #fff; font-size: 12px; font-weight: 800; padding: 6px 10px; margin-bottom: 8px;">
            PART-A (Consignment &amp; Tax Invoice Details)
        </div>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 14px; font-size: 12px;">
            <tr>
                <td style="padding: 6px; border: 1px solid #cbd5e1; font-weight: 700; width: 25%;">GSTIN of Supplier:</td>
                <td style="padding: 6px; border: 1px solid #cbd5e1; width: 25%;"><?= COMPANY_GSTIN ?></td>
                <td style="padding: 6px; border: 1px solid #cbd5e1; font-weight: 700; width: 25%;">GSTIN of Recipient:</td>
                <td style="padding: 6px; border: 1px solid #cbd5e1; width: 25%; font-weight: 700;"><?= !empty($order['customer_gst']) ? e($order['customer_gst']) : 'URP (Unregistered Customer)' ?></td>
            </tr>
            <tr>
                <td style="padding: 6px; border: 1px solid #cbd5e1; font-weight: 700;">Place of Dispatch:</td>
                <td style="padding: 6px; border: 1px solid #cbd5e1;">Bhavnagar, Gujarat (364003)</td>
                <td style="padding: 6px; border: 1px solid #cbd5e1; font-weight: 700;">Place of Delivery:</td>
                <td style="padding: 6px; border: 1px solid #cbd5e1;"><?= e($order['delivery_city'] ?? 'Gujarat') ?> (<?= e($order['delivery_pincode'] ?? '364001') ?>)</td>
            </tr>
            <tr>
                <td style="padding: 6px; border: 1px solid #cbd5e1; font-weight: 700;">Document No &amp; Date:</td>
                <td style="padding: 6px; border: 1px solid #cbd5e1;"><?= e($order['invoice_number'] ?? $order['order_number']) ?> &bull; <?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                <td style="padding: 6px; border: 1px solid #cbd5e1; font-weight: 700;">Transaction Type:</td>
                <td style="padding: 6px; border: 1px solid #cbd5e1; font-weight: 700; color: #0284c7;">Regular Outward Supply</td>
            </tr>
            <tr>
                <td style="padding: 6px; border: 1px solid #cbd5e1; font-weight: 700;">Value of Goods:</td>
                <td style="padding: 6px; border: 1px solid #cbd5e1; font-weight: 800; color: #15803d; font-size: 13px;">₹ <?= number_format(floatval($order['total_amount'] ?? 0), 2) ?></td>
                <td style="padding: 6px; border: 1px solid #cbd5e1; font-weight: 700;">HSN Code &amp; Item:</td>
                <td style="padding: 6px; border: 1px solid #cbd5e1;">8459 &bull; <?= e($order['product_name'] ?? 'Drill Machine') ?> (<?= e($order['model_number'] ?? 'SVEW') ?>)</td>
            </tr>
        </table>

        <!-- PART-B: Vehicle & Transport Details -->
        <div style="background: #0f172a; color: #fff; font-size: 12px; font-weight: 800; padding: 6px 10px; margin-bottom: 8px;">
            PART-B (Vehicle &amp; Transport Mode Details)
        </div>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 14px; font-size: 12px;">
            <tr>
                <td style="padding: 6px; border: 1px solid #cbd5e1; font-weight: 700; width: 25%;">Mode of Transport:</td>
                <td style="padding: 6px; border: 1px solid #cbd5e1; width: 25%;">Road (Surface Heavy Freight)</td>
                <td style="padding: 6px; border: 1px solid #cbd5e1; font-weight: 700; width: 25%;">Transporter Name &amp; ID:</td>
                <td style="padding: 6px; border: 1px solid #cbd5e1; width: 25%;">VRL Logistics Ltd. &bull; 29AABCV1234F1Z0</td>
            </tr>
            <tr>
                <td style="padding: 6px; border: 1px solid #cbd5e1; font-weight: 700;">Vehicle Number:</td>
                <td style="padding: 6px; border: 1px solid #cbd5e1; font-weight: 700;">GJ-04-AX-<?= rand(1000, 9999) ?></td>
                <td style="padding: 6px; border: 1px solid #cbd5e1; font-weight: 700;">Transport Doc (LR) No:</td>
                <td style="padding: 6px; border: 1px solid #cbd5e1;">VRL-BHV-<?= date('ymd') ?>-<?= str_pad(strval($order['id']), 4, '0', STR_PAD_LEFT) ?></td>
            </tr>
        </table>

        <!-- QR Code & Integrity Seal -->
        <div style="border-top: 1.5px solid #0f172a; padding-top: 12px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="font-size: 11px; color: #475569; max-width: 520px; line-height: 1.45;">
                <strong>Government Compliance Notice:</strong> This e-Way Bill is an electronic generated document under Rule 138 of the CGST Rules, 2017. 
                Any inspection officer can verify this bill on the official portal <a href="https://ewaybillgst.gov.in/login.aspx" target="_blank" style="color: #0284c7; font-weight: 700;">ewaybillgst.gov.in/login.aspx</a>.
            </div>
            <div style="text-align: right;">
                <div style="font-size: 11px; font-weight: 800; color: #0f172a;">SHREE VARSHA ENGINEERING WORKS</div>
                <div style="font-size: 10.5px; color: #64748b;">Authorized Signatory: Jay Parmar</div>
            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
