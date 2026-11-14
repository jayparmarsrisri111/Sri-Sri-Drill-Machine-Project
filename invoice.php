<?php
/**
 * Official Tax Invoice & Order Bill
 * Shree Varsha Engineering Works - Varsha Brand
 * Authorized Signatory: Jay Parmar | Seal: Varsha Brand
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$orderId = intval($_GET['order_id'] ?? 0);
$orderNumber = sanitizeString($_GET['order_num'] ?? '');

$order = null;
$customerOrders = [];
$lookupError = '';

try {
    $pdo = Database::getConnection();

    // Check if customer is searching via POST or GET lookup form
    $searchNum = sanitizeString($_REQUEST['search_order_num'] ?? '');
    $searchPhone = sanitizeString($_REQUEST['search_phone'] ?? '');

    if (!empty($searchNum)) {
        if (!empty($searchPhone)) {
            $stmt = $pdo->prepare("SELECT o.*, p.image_url, p.drilling_capacity, p.spindle_speeds, p.motor_power FROM orders o LEFT JOIN products p ON o.product_id = p.id WHERE (o.order_number = :num OR o.invoice_number = :num) AND (o.customer_phone LIKE :ph) LIMIT 1");
            $cleanPh = preg_replace('/[^0-9]/', '', $searchPhone);
            $stmt->execute([':num' => $searchNum, ':ph' => '%' . substr($cleanPh, -10)]);
            $order = $stmt->fetch();
            if (!$order) {
                $lookupError = 'No matching order found for this Order Number and Mobile Number combination.';
            }
        } else {
            $stmt = $pdo->prepare("SELECT o.*, p.image_url, p.drilling_capacity, p.spindle_speeds, p.motor_power FROM orders o LEFT JOIN products p ON o.product_id = p.id WHERE o.order_number = :num OR o.invoice_number = :num LIMIT 1");
            $stmt->execute([':num' => $searchNum]);
            $order = $stmt->fetch();
        }
    } elseif ($orderId > 0) {
        $stmt = $pdo->prepare("SELECT o.*, p.image_url, p.drilling_capacity, p.spindle_speeds, p.motor_power FROM orders o LEFT JOIN products p ON o.product_id = p.id WHERE o.id = :id");
        $stmt->execute([':id' => $orderId]);
        $order = $stmt->fetch();
    } elseif (!empty($orderNumber)) {
        $stmt = $pdo->prepare("SELECT o.*, p.image_url, p.drilling_capacity, p.spindle_speeds, p.motor_power FROM orders o LEFT JOIN products p ON o.product_id = p.id WHERE o.order_number = :num OR o.invoice_number = :num");
        $stmt->execute([':num' => $orderNumber]);
        $order = $stmt->fetch();
    }

    if ($order && !empty($order['id'])) {
        $oiStmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = :oid ORDER BY id ASC");
        $oiStmt->execute([':oid' => $order['id']]);
        $orderItems = $oiStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // If no specific order selected, check if customer is signed in to list their orders
    if (!$order && !empty($_SESSION['customer_user']['id'])) {
        $cStmt = $pdo->prepare("SELECT o.*, p.image_url FROM orders o LEFT JOIN products p ON o.product_id = p.id WHERE o.customer_id = :cid ORDER BY o.id DESC");
        $cStmt->execute([':cid' => intval($_SESSION['customer_user']['id'])]);
        $customerOrders = $cStmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}

if (!$order) {
    // If no order found or no ID provided, render the Secure Invoice Lookup Portal
    $pageTitle = 'Tax Invoice & Bill Search | ' . APP_NAME;
    $currentNav = 'invoice';
    require_once __DIR__ . '/includes/header.php';
    require_once __DIR__ . '/includes/navbar.php';
    ?>
    <section class="section" style="padding: 40px 0 70px;">
        <div class="container" style="max-width: 980px;">
            <div style="text-align: center; margin-bottom: 30px;">
                <span class="pill-badge" style="background: rgba(0, 229, 255, 0.15); border-color: #00e5ff; color: #00e5ff;">
                    📄 OFFICIAL FACTORY TAX BILLS
                </span>
                <h1 style="font-family: var(--font-heading); font-size: 28px; color: #fff; margin-top: 8px;">
                    Find &amp; Download Your Tax Invoice / Bill
                </h1>
                <p style="font-size: 14px; color: var(--text-secondary); max-width: 620px; margin: 8px auto 0;">
                    Enter your Order Number and registered Mobile Number to securely view and print your certified GST Tax Invoice or Proforma Bill.
                </p>
            </div>

            <?php if (!empty($lookupError)): ?>
                <div style="background: rgba(239, 68, 68, 0.15); border: 1.5px solid #ef4444; color: #f87171; border-radius: 10px; padding: 14px 18px; margin-bottom: 22px; font-size: 13.5px; display: flex; align-items: center; gap: 10px;">
                    <span>⚠️</span>
                    <span><?= e($lookupError) ?></span>
                </div>
            <?php endif; ?>

            <!-- Search Card -->
            <div style="background: var(--bg-card); border: 1.5px solid var(--border-cyan); border-radius: 14px; padding: 26px; box-shadow: var(--shadow-card); margin-bottom: 30px;">
                <form method="GET" action="invoice.php" style="display: grid; grid-template-columns: 1.2fr 1fr auto; gap: 14px; align-items: flex-end;">
                    <div>
                        <label class="form-label" style="font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px; display: block;">
                            Order Number or Invoice Number *
                        </label>
                        <input type="text" name="search_order_num" required class="form-control" placeholder="e.g. SVEW-ORD-20261110-A108F" value="<?= e($_REQUEST['search_order_num'] ?? '') ?>" style="height: 44px; font-size: 14px; background: #060911; color: #fff; border: 1.5px solid rgba(0, 229, 255, 0.4);">
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 6px; display: block;">
                            Registered Mobile Number
                        </label>
                        <input type="tel" name="search_phone" class="form-control" placeholder="e.g. 9824913555" value="<?= e($_REQUEST['search_phone'] ?? '') ?>" style="height: 44px; font-size: 14px; background: #060911; color: #fff; border: 1.5px solid rgba(0, 229, 255, 0.4);">
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary" style="height: 44px; padding: 0 22px; font-weight: 800; font-size: 14px; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color: #060911; border-color: #00e5ff; display: flex; align-items: center; justify-content: center; gap: 6px; white-space: nowrap;">
                            <span>🔍 Search Bill</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Customer Orders List (If Signed In) -->
            <?php if (!empty($customerOrders)): ?>
                <div class="verified-orders-card" style="background: linear-gradient(180deg, rgba(15, 23, 42, 0.95) 0%, rgba(10, 16, 31, 0.95) 100%); border: 1.5px solid rgba(0, 229, 255, 0.35); border-radius: 14px; padding: 22px; margin-bottom: 30px; box-shadow: 0 14px 40px rgba(0, 0, 0, 0.45);">
                    <div style="margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="display: flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 50%; background: rgba(0, 229, 255, 0.15); border: 1px solid #00e5ff; color: #00e5ff; font-size: 16px;">👤</span>
                            <div>
                                <h3 style="margin: 0; font-size: 16.5px; font-weight: 800; color: #fff; font-family: 'Space Grotesk', sans-serif;">
                                    Your Verified Machinery Orders &amp; Tax Bills
                                </h3>
                                <div style="font-size: 12px; color: #94a3b8;">
                                    Linked to your registered account (<?= e($custUser['phone'] ?? '') ?>)
                                </div>
                            </div>
                        </div>
                        <a href="customer-dashboard.php" class="btn" style="background: linear-gradient(135deg, rgba(2, 132, 199, 0.25) 0%, rgba(0, 229, 255, 0.25) 100%); border: 1.5px solid #00e5ff; color: #00e5ff; font-size: 12px; padding: 7px 16px; border-radius: 8px; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 15px rgba(0, 229, 255, 0.2);">
                            <span>📊 Open Full Customer Dashboard →</span>
                        </a>
                    </div>

                    <!-- Responsive Table Container -->
                    <div style="overflow-x: auto; -webkit-overflow-scrolling: touch; border-radius: 10px; border: 1px solid rgba(255,255,255,0.08);">
                        <table style="width: 100%; border-collapse: collapse; font-size: 13.5px; min-width: 640px; background: rgba(6, 9, 17, 0.6);">
                            <thead>
                                <tr style="background: rgba(255, 255, 255, 0.04); border-bottom: 1.5px solid rgba(0, 229, 255, 0.25); text-align: left; color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 0.6px;">
                                    <th style="padding: 12px 14px;">Order No</th>
                                    <th style="padding: 12px 14px;">Machine Model</th>
                                    <th style="padding: 12px 14px;">Date</th>
                                    <th style="padding: 12px 14px; text-align: right; min-width: 140px; white-space: nowrap !important;">Amount</th>
                                    <th style="padding: 12px 14px; text-align: center;">Payment</th>
                                    <th style="padding: 12px 14px; text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($customerOrders as $co): 
                                    $isCoPaid = ($co['payment_status'] === 'paid');
                                ?>
                                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.06); transition: background 0.2s;">
                                        <td style="padding: 14px; font-weight: 800; color: #00e5ff; font-family: monospace; font-size: 13px;">
                                            <?= e($co['order_number']) ?>
                                        </td>
                                        <td style="padding: 14px; color: #fff;">
                                            <div style="font-weight: 700; color: #fff;"><?= e($co['model_number']) ?></div>
                                            <div style="font-size: 12px; color: #94a3b8;"><?= e($co['product_name']) ?></div>
                                        </td>
                                        <td style="padding: 14px; color: #cbd5e1; font-size: 12.5px; white-space: nowrap;">
                                            <?= date('d-M-Y', strtotime($co['created_at'])) ?>
                                        </td>
                                        <td style="padding: 14px; text-align: right; font-weight: 800; color: #34d399; font-size: 14.5px; white-space: nowrap !important;">
                                            <span style="white-space: nowrap !important; display: inline-flex; align-items: center; justify-content: flex-end; gap: 3px;"><span>₹</span><span><?= number_format($co['total_amount'], 2) ?></span></span>
                                        </td>
                                        <td style="padding: 14px; text-align: center;">
                                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 800; letter-spacing: 0.4px; <?= $isCoPaid ? 'background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; color: #34d399;' : 'background: rgba(245, 158, 11, 0.15); border: 1px solid #f59e0b; color: #fbbf24;' ?>">
                                                <?= $isCoPaid ? '✔ PAID' : '⏳ PENDING' ?>
                                            </span>
                                        </td>
                                        <td style="padding: 14px; text-align: right; white-space: nowrap;">
                                            <a href="invoice.php?order_id=<?= $co['id'] ?>" class="btn" style="padding: 6px 14px; font-size: 12px; background: rgba(0, 229, 255, 0.12); border: 1px solid #00e5ff; color: #00e5ff; font-weight: 800; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; box-shadow: 0 2px 8px rgba(0, 229, 255, 0.15);">
                                                <span>📄 View Bill →</span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php elseif (empty($_SESSION['customer_user'])): ?>
                <div style="text-align: center; background: rgba(15, 23, 42, 0.6); border: 1px dashed rgba(255,255,255,0.15); border-radius: 12px; padding: 22px;">
                    <div style="font-size: 13.5px; color: #cbd5e1; margin-bottom: 10px;">
                        Have an existing customer account? Sign in to view all your past machine orders &amp; tax invoices automatically.
                    </div>
                    <button type="button" onclick="openCustomerAuthModal('Sign in to view your orders and tax invoices.')" class="btn btn-outline" style="border-color: #38bdf8; color: #38bdf8; font-size: 12.5px; padding: 8px 18px; font-weight: 700;">
                        👤 Customer Sign In / Register
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// Convert amount to words in Indian Currency Format
function amountInWords(float $number): string {
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(
        0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five',
        6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine', 10 => 'Ten',
        11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
        16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen',
        20 => 'Twenty', 30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty',
        60 => 'Sixty', 70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety'
    );
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : '';
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : '';
            $str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred
                : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal > 0) ? " and " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return 'Rupees ' . trim(preg_replace('/\s+/', ' ', $Rupees)) . $paise . ' Only';
}

$pageTitle = 'Tax Invoice #' . $order['invoice_number'] . ' | ' . APP_NAME;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($pageTitle) ?></title>
    <link rel="icon" type="image/png" href="<?= assetUrl('assets/images/varsha-logo.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0284c7;
            --accent-cyan: #00e5ff;
            --dark: #0f172a;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border-line: #cbd5e1;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: #070b14;
            color: #0f172a;
            min-height: 100vh;
            padding: 24px 12px;
            line-height: 1.45;
        }

        /* Screen Action Toolbar */
        .invoice-actions-bar {
            max-width: 880px;
            margin: 0 auto 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            background: rgba(15, 23, 42, 0.95);
            border: 1.5px solid rgba(0, 229, 255, 0.35);
            border-radius: 12px;
            padding: 12px 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.6);
        }
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-print {
            background: linear-gradient(135deg, #00e5ff 0%, #0284c7 100%);
            color: #060911;
            font-weight: 800;
        }
        .btn-print:hover {
            box-shadow: 0 0 18px rgba(0, 229, 255, 0.55);
            transform: translateY(-1px);
        }
        .btn-whatsapp-share {
            background: #25D366;
            color: #ffffff;
        }
        .btn-whatsapp-share:hover {
            background: #1eb956;
            transform: translateY(-1px);
        }
        .btn-back {
            background: rgba(255, 255, 255, 0.1);
            color: #cbd5e1;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .btn-back:hover {
            background: rgba(255, 255, 255, 0.18);
            color: #fff;
        }

        /* The Printable White Master Invoice Container */
        .invoice-paper {
            max-width: 880px;
            margin: 0 auto;
            background: #ffffff;
            color: #0f172a;
            padding: 34px 38px;
            border-radius: 8px;
            border: 1.5px solid #0f172a;
            box-shadow: 0 25px 60px rgba(0,0,0,0.6);
            position: relative;
        }

        /* Header Grid */
        .inv-header {
            display: grid;
            grid-template-columns: 1.25fr 1fr;
            gap: 24px;
            align-items: start;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 16px;
            margin-bottom: 16px;
        }
        .company-branding {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .company-logo-img {
            width: 76px;
            height: 76px;
            object-fit: contain;
            border-radius: 50%;
            border: 2px solid #0284c7;
            padding: 2px;
            background: #ffffff;
            flex-shrink: 0;
        }
        .company-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 21px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.3px;
            line-height: 1.2;
        }
        .brand-badge {
            display: inline-block;
            background: #0f172a;
            color: #00e5ff;
            font-size: 10.5px;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 4px;
            letter-spacing: 0.8px;
            margin: 3px 0;
        }
        .company-meta {
            font-size: 11.5px;
            color: #475569;
            line-height: 1.4;
        }

        /* Title & Meta Block */
        .invoice-title-block {
            text-align: right;
        }
        .inv-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 23px;
            font-weight: 900;
            color: #0284c7;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            line-height: 1.15;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-weight: 800;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .inv-meta-table {
            font-size: 12px;
            margin-top: 6px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .inv-meta-table td {
            padding: 2px 0 2px 8px;
            text-align: right;
        }

        /* Parties Grid (Consignee & Dispatch) */
        .parties-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .party-card {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 6px;
            padding: 13px 16px;
            font-size: 12px;
            line-height: 1.45;
        }
        .party-heading {
            font-size: 11px;
            font-weight: 800;
            color: #0284c7;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 6px;
            padding-bottom: 4px;
            border-bottom: 1.5px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Items Table */
        .items-table-wrapper {
            margin-bottom: 20px;
            border: 1.5px solid #0f172a;
            border-radius: 4px;
            overflow: hidden;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
        }
        .items-table th {
            background: #0f172a;
            color: #ffffff;
            font-weight: 800;
            text-align: left;
            padding: 9px 12px;
            font-size: 11px;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            font-family: 'Space Grotesk', sans-serif;
            border: none;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
            color: #1e293b;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }

        /* Lower Details Grid (Bank/Terms on left, Calculations on right) */
        .totals-section {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 22px;
            margin-bottom: 20px;
            align-items: start;
        }
        .bank-terms-box {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .bank-info-card {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
            font-size: 11.5px;
            line-height: 1.4;
        }
        .amount-words-card {
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            border-radius: 6px;
            padding: 9px 13px;
            font-size: 11.5px;
            color: #166534;
            line-height: 1.4;
        }
        .terms-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 9px 13px;
            font-size: 10.5px;
            color: #64748b;
            line-height: 1.4;
        }

        /* Financial Calculation Table */
        .totals-table-card {
            background: #ffffff;
            border: 1.5px solid #0f172a;
            border-radius: 6px;
            overflow: hidden;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .totals-table td {
            padding: 7px 10px;
            border-bottom: 1px solid #f1f5f9;
        }
        .totals-table tr.grand-total td {
            font-size: 14.5px;
            font-weight: 800;
            color: #0f172a;
            background: #f0fdf4;
            border-top: 2px solid #0f172a;
            border-bottom: 2px solid #0f172a;
            padding: 9px 10px;
            font-family: 'Space Grotesk', sans-serif;
        }

        /* Sign & Seal Row */
        .sign-seal-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding-top: 16px;
            border-top: 1.5px solid #e2e8f0;
            margin-top: 16px;
            gap: 20px;
        }
        .signature-box {
            text-align: center;
            min-width: 220px;
        }
        .signature-line {
            height: 1px;
            background: #cbd5e1;
            width: 180px;
            margin: 3px auto 4px;
        }

        /* Mobile Screens */
        @media screen and (max-width: 768px) {
            body { padding: 10px 8px 60px; }
            .invoice-actions-bar {
                flex-direction: column;
                align-items: stretch;
                padding: 12px;
            }
            .invoice-actions-bar > div {
                flex-direction: column;
                width: 100%;
                gap: 8px;
            }
            .btn-action {
                width: 100%;
                justify-content: center;
            }
            .invoice-paper {
                padding: 18px 14px;
                border-radius: 6px;
            }
            .inv-header {
                grid-template-columns: 1fr;
                gap: 14px;
            }
            .invoice-title-block {
                text-align: left;
            }
            .inv-meta-table {
                margin-left: 0;
            }
            .inv-meta-table td {
                text-align: left;
                padding: 2px 8px 2px 0;
            }
            .parties-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .items-table-wrapper {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            .items-table {
                min-width: 580px;
            }
            .totals-section {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .sign-seal-row {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 16px;
            }
        }

        /* Strict Single-Page A4 Print Configuration */
        @page {
            size: A4 portrait;
            margin: 5mm 7mm;
        }
        @media print {
            html, body {
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
                color: #0f172a !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .invoice-actions-bar {
                display: none !important;
            }
            .invoice-paper {
                box-shadow: none !important;
                border: 1.5px solid #0f172a !important;
                border-radius: 4px !important;
                padding: 10px 14px !important;
                margin: 0 auto !important;
                width: 100% !important;
                max-width: 100% !important;
                page-break-after: avoid !important;
                page-break-inside: avoid !important;
            }
            .inv-header {
                padding-bottom: 8px !important;
                margin-bottom: 8px !important;
            }
            .company-logo-img {
                width: 52px !important;
                height: 52px !important;
            }
            .company-title {
                font-size: 16px !important;
            }
            .inv-title {
                font-size: 17px !important;
            }
            .party-card {
                padding: 7px 10px !important;
                font-size: 9.5px !important;
                line-height: 1.35 !important;
            }
            .items-table th {
                padding: 5px 8px !important;
                font-size: 9px !important;
            }
            .items-table td {
                padding: 5px 8px !important;
                font-size: 9.5px !important;
                line-height: 1.3 !important;
            }
            .bank-info-card, .amount-words-card, .terms-card {
                padding: 5px 8px !important;
                font-size: 9px !important;
                line-height: 1.3 !important;
            }
            .totals-table td {
                padding: 3px 6px !important;
                font-size: 9.5px !important;
            }
            .totals-table tr.grand-total td {
                font-size: 11.5px !important;
                padding: 5px 6px !important;
            }
            .sign-seal-row {
                margin-top: 6px !important;
                padding-top: 6px !important;
            }
        }
    </style>
</head>
<body>

    <!-- Action Toolbar (hidden on print) -->
    <div class="invoice-actions-bar">
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <a href="<?= BASE_URL ?>/index.php" class="btn-action btn-back">
                ← Back to Home
            </a>
            <span style="color: #94a3b8; font-size: 13px;">
                Order No: <strong style="color: #fff;"><?= e($order['order_number']) ?></strong>
            </span>
            <span style="font-size: 12px; font-weight: 700; padding: 4px 10px; border-radius: 6px; <?= ($order['payment_status'] === 'paid') ? 'background: #166534; color: #86efac; border: 1px solid #22c55e;' : 'background: #854d0e; color: #fef08a; border: 1px solid #eab308;' ?>">
                <?= ($order['payment_status'] === 'paid') ? '✔ PAYMENT VERIFIED &amp; COMPLETED' : '⏳ PAYMENT STATUS: PENDING / PROFORMA' ?>
            </span>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn-action btn-print">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
                🖨️ Print / Download PDF Invoice
            </button>
            <?php
                $waText = "Hello " . APP_NAME . ", Here is my order confirmation and invoice:\nInvoice No: " . $order['invoice_number'] . "\nModel: " . $order['model_number'] . "\nAmount: Rs. " . number_format($order['total_amount'], 2) . "\nTransaction Ref: " . $order['razorpay_payment_id'];
            ?>
            <a href="<?= getWhatsAppLink(COMPANY_WHATSAPP, $waText) ?>" target="_blank" class="btn-action btn-whatsapp-share">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/></svg>
                Share on WhatsApp
            </a>
        </div>
    </div>

    <!-- Official High-Precision Industrial Tax Invoice Paper -->
    <div class="invoice-paper">
        <?php 
            $isPaid = (isset($order['payment_status']) && strtolower($order['payment_status']) === 'paid');
            $orderType = $order['payment_type'] ?? 'booking_advance';
            $isAdvance = ($orderType === 'booking_advance');

            $machineBasePrice = floatval($order['machine_price'] ?? 285000.00);
            if ($machineBasePrice <= 0) $machineBasePrice = 285000.00;
            $fullMachineGross = round($machineBasePrice * 1.18, 2);

            if ($isAdvance) {
                $paidAmount = floatval($order['total_amount'] ?? 25000.00);
                if ($paidAmount <= 0) $paidAmount = 25000.00;
                $taxableAmount = round($paidAmount / 1.18, 2);
                $cgstAmount = round(($paidAmount - $taxableAmount) / 2, 2);
                $sgstAmount = round($paidAmount - $taxableAmount - $cgstAmount, 2);
                $balanceDue = max(0, $fullMachineGross - $paidAmount);
            } else {
                $taxableAmount = $machineBasePrice;
                $cgstAmount = round($taxableAmount * 0.09, 2);
                $sgstAmount = round($taxableAmount * 0.09, 2);
                $paidAmount = floatval($order['total_amount'] ?? ($taxableAmount + $cgstAmount + $sgstAmount));
                $balanceDue = 0.00;
            }

            $pm = $order['payment_method'] ?? 'online';
            $payMethodLabel = 'Instant UPI / 256-Bit SSL';
            if ($pm === 'cod') $payMethodLabel = 'Cash on Delivery (COD)';
            elseif ($pm === 'bank_transfer') $payMethodLabel = 'Direct Bank Transfer (NEFT/RTGS)';
            elseif ($pm === 'cheque') $payMethodLabel = 'Account Payee Cheque / DD';

            if ($isPaid) {
                $docTitle = $isAdvance ? 'ADVANCE BOOKING TAX INVOICE' : 'ORIGINAL TAX INVOICE';
            } else {
                $docTitle = ($pm === 'cod') ? 'PROFORMA INVOICE (COD BOOKING)' : 'PROFORMA TAX INVOICE';
            }
        ?>

        <!-- Header -->
        <div class="inv-header">
            <div class="company-branding">
                <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand" class="company-logo-img">
                <div>
                    <h1 class="company-title"><?= APP_NAME ?></h1>
                    <span class="brand-badge">★ <?= BRAND_NAME ?> ★ INDUSTRIAL DRILLING MACHINERY</span>
                    <div class="company-meta">
                        <?= COMPANY_ADDRESS ?><br>
                        <strong>GSTIN:</strong> <?= COMPANY_GSTIN ?> &bull; <strong>State:</strong> Gujarat (Code: 24)<br>
                        <strong>Direct:</strong> <?= COMPANY_PHONE_1 ?> &bull; <strong>Email:</strong> <?= COMPANY_EMAIL ?>
                    </div>
                </div>
            </div>

            <div class="invoice-title-block">
                <div class="inv-title"><?= $docTitle ?></div>
                <div style="margin: 4px 0;">
                    <?php if ($isPaid): ?>
                        <span class="status-badge" style="background: #dcfce7; color: #15803d; border: 1px solid #86efac;">
                            ✔ <?= $isAdvance ? 'TOKEN ADVANCE PAID &amp; CONFIRMED' : '100% PAID IN FULL (NIL BALANCE)' ?>
                        </span>
                    <?php else: ?>
                        <span class="status-badge" style="background: #fef3c7; color: #b45309; border: 1px solid #fcd34d;">
                            ⏳ <?= ($pm === 'cod') ? 'COD BOOKED &bull; PAYMENT DUE AT DISPATCH' : 'PAYMENT PENDING / UNDER VERIFICATION' ?>
                        </span>
                    <?php endif; ?>
                </div>
                <table class="inv-meta-table">
                    <tr>
                        <td style="color: #64748b; font-weight: 600;">Invoice No:</td>
                        <td><strong style="color: #0284c7; background: #e0f2fe; padding: 2px 7px; border-radius: 4px; font-family: monospace; font-size: 13px; border: 1px solid #bae6fd;"><?= e($order['invoice_number']) ?></strong></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-weight: 600;">Invoice Date:</td>
                        <td><strong style="color: #0f172a;"><?= date('d-M-Y', strtotime($order['created_at'])) ?></strong></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-weight: 600;">Time of Supply:</td>
                        <td><span style="color: #475569;"><?= date('h:i:s A', strtotime($order['created_at'])) ?> IST</span></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-weight: 600;">Order Reference:</td>
                        <td><span style="font-family: monospace; font-weight: 700; color: #0f172a;"><?= e($order['order_number']) ?></span></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-weight: 600;">Payment Mode:</td>
                        <td><strong style="color: #0284c7;"><?= e($payMethodLabel) ?></strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Buyer & Dispatch Details Grid -->
        <div class="parties-grid">
            <div class="party-card">
                <div class="party-heading">
                    <span>🏢</span>
                    <span>BILL TO (BUYER / CONSIGNEE)</span>
                </div>
                <div style="font-weight: 800; font-size: 14px; color: #0f172a; margin-bottom: 2px;">
                    <?= e($order['customer_name']) ?>
                </div>
                <?php if (!empty($order['company_name'])): ?>
                    <div style="font-weight: 700; color: #0284c7; margin-bottom: 3px;">
                        <?= e($order['company_name']) ?>
                    </div>
                <?php endif; ?>
                <div style="color: #475569; margin-bottom: 6px;">
                    <?= nl2br(e($order['customer_address'])) ?><br>
                    <strong>Destination:</strong> <?= e($order['customer_city']) ?> - <?= e($order['customer_pincode']) ?>
                </div>
                <div style="color: #334155;">
                    <strong>Phone:</strong> <?= e($order['customer_phone']) ?> &bull; <strong>Email:</strong> <?= e($order['customer_email']) ?><br>
                    <?php if (!empty($order['customer_gst'])): ?>
                        <strong>Buyer GSTIN:</strong> <span style="font-family: monospace; font-weight: 700;"><?= e($order['customer_gst']) ?></span>
                    <?php else: ?>
                        <strong>Buyer Category:</strong> Industrial End-User / Fabrication Workshop
                    <?php endif; ?>
                </div>
            </div>

            <div class="party-card">
                <div class="party-heading">
                    <span>🏭</span>
                    <span>DISPATCH &amp; CONSIGNMENT DETAILS</span>
                </div>
                <div style="color: #475569;">
                    <strong>Factory Origin:</strong> Works L/786, GIDC Industrial Area, Bhavnagar<br>
                    <strong>Transport Mode:</strong> Road Freight / Heavy Machinery Carrier<br>
                    <strong>Carrier Logistics:</strong> <?= e($order['transport_name'] ?? 'VRL Logistics / Surface Transport') ?><br>
                    <strong>Consignment Tracking:</strong> <span style="font-family: monospace; font-weight: 700; color: #0284c7;"><?= e($order['tracking_number'] ?? 'VRL-RAJ-884210') ?></span><br>
                    <strong>Supply Terms:</strong> Certified Ex-Factory Bhavnagar / Insured Transit
                </div>
                <?php if (!empty($order['notes'])): ?>
                    <div style="margin-top: 6px; padding: 6px 8px; background: #fff; border: 1px dashed #cbd5e1; border-radius: 4px; font-size: 11px; color: #334155;">
                        <strong>Customer Reference / Notes:</strong> <?= e($order['notes']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Items Table -->
        <div class="items-table-wrapper">
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 48%;">Machinery Description &amp; Technical Specifications</th>
                        <th style="width: 12%;">HSN / SAC</th>
                        <th style="width: 8%; text-align: center;">Qty</th>
                        <th style="width: 13%; text-align: right;">Unit Rate (₹)</th>
                        <th style="width: 14%; text-align: right;">Taxable Value (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orderItems) && count($orderItems) > 1): ?>
                        <?php foreach ($orderItems as $idx => $it): ?>
                            <tr>
                                <td><?= $idx + 1 ?></td>
                                <td>
                                    <strong style="font-size: 13px; color: #0f172a;"><?= e($it['product_name']) ?></strong>
                                    <div style="color: #0284c7; font-weight: 700; font-size: 11.5px; margin: 2px 0;">
                                        MODEL: <?= e($it['model_number']) ?> &bull; BRAND: <?= BRAND_NAME ?>
                                    </div>
                                    <div style="font-size: 11px; color: #64748b;">
                                        Heavy-Duty Industrial Specification &bull; Grade-25 Seasoned Castings &bull; Alloy Steel Gears
                                    </div>
                                </td>
                                <td style="font-family: monospace; font-size: 12px; font-weight: 600;">8459 29 10</td>
                                <td style="text-align: center; font-weight: 800; color: #0284c7;"><?= intval($it['quantity']) ?></td>
                                <td style="text-align: right;"><?= number_format(floatval($it['total_price']) / max(1, intval($it['quantity'])), 2) ?></td>
                                <td style="text-align: right; font-weight: 700;"><?= number_format(floatval($it['total_price']), 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php $invQty = max(1, intval($order['quantity'] ?? 1)); ?>
                        <tr>
                            <td>1</td>
                            <td>
                                <strong style="font-size: 13.5px; color: #0f172a;"><?= e($order['product_name']) ?></strong>
                                <div style="color: #0284c7; font-weight: 800; font-size: 12px; margin: 2px 0;">
                                    MODEL: <?= e($order['model_number']) ?> &bull; BRAND: <?= BRAND_NAME ?> (INDUSTRIAL GRADE)
                                </div>
                                <div style="font-size: 11px; color: #475569; line-height: 1.4;">
                                    &bull; <strong>Drilling Capacity:</strong> <?= e($order['drilling_capacity'] ?? '40mm in Steel / Heavy Cast') ?><br>
                                    &bull; <strong>Motor Drive:</strong> <?= e($order['motor_power'] ?? 'Standard 3-Phase High Torque') ?><br>
                                    &bull; <strong>Metallurgy:</strong> Seasoned Grade-25 Cast Iron Column &amp; Arm, Case-Hardened Alloy Gears
                                </div>
                                <?php if ($isAdvance): ?>
                                    <div style="margin-top: 6px; padding: 5px 8px; background: #fefce8; border: 1px solid #fef08a; border-radius: 4px; font-size: 11px; color: #854d0e;">
                                        <strong>★ Token Advance Settlement:</strong> Receipts ₹ <?= number_format($paidAmount, 2) ?> booking token for production slot &amp; casting reservation. Balance payable on dispatch.
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="font-family: monospace; font-size: 12px; font-weight: 600;">8459 29 10</td>
                            <td style="text-align: center; font-weight: 800; color: #0284c7;"><?= $invQty ?></td>
                            <td style="text-align: right; font-weight: 600;"><?= number_format($taxableAmount / $invQty, 2) ?></td>
                            <td style="text-align: right; font-weight: 800; color: #0f172a;"><?= number_format($taxableAmount, 2) ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Lower Details Grid (Bank / Terms & Financial Summary) -->
        <div class="totals-section">
            <div class="bank-terms-box">
                <!-- Company Bank Wire Instructions -->
                <div class="bank-info-card">
                    <strong style="color: #0284c7; display: block; margin-bottom: 4px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                        🏦 Company Bank Account (for RTGS / NEFT / IMPS):
                    </strong>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4px; color: #334155;">
                        <div><strong>Bank:</strong> State Bank of India (SBI)</div>
                        <div><strong>Account Name:</strong> SHREE VARSHA ENGG WORKS</div>
                        <div><strong>Current A/C:</strong> 39820010005432</div>
                        <div><strong>IFSC Code:</strong> SBIN0001980 (Chitra, Bhavnagar)</div>
                    </div>
                </div>

                <!-- Amount in Words -->
                <div class="amount-words-card">
                    <strong>Amount in Words:</strong> <?= amountInWords($paidAmount) ?>
                </div>

                <!-- Terms & Conditions -->
                <div class="terms-card">
                    <strong style="color: #0f172a; display: block; margin-bottom: 3px;">Standard Manufacturing &amp; Warranty Terms:</strong>
                    1. <strong>12 Months Warranty:</strong> Comprehensive coverage against mechanical casting &amp; gear defects.<br>
                    2. <strong>Testing:</strong> Machine pre-inspected with dial-indicator runout test (&lt;0.015mm) before crating.<br>
                    3. <strong>Jurisdiction:</strong> Disputes subject to Bhavnagar (Gujarat) Court Jurisdiction only.
                </div>
            </div>

            <!-- Calculation Totals Box -->
            <div class="totals-table-card">
                <table class="totals-table">
                    <tr>
                        <td style="color: #64748b; font-weight: 600;">Taxable Value:</td>
                        <td style="text-align: right; font-weight: 700; color: #0f172a; white-space: nowrap;">₹ <?= number_format($taxableAmount, 2) ?></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-weight: 600;">CGST (9.00%):</td>
                        <td style="text-align: right; font-weight: 700; color: #0f172a; white-space: nowrap;">₹ <?= number_format($cgstAmount, 2) ?></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-weight: 600;">SGST (9.00%):</td>
                        <td style="text-align: right; font-weight: 700; color: #0f172a; white-space: nowrap;">₹ <?= number_format($sgstAmount, 2) ?></td>
                    </tr>
                    <tr class="grand-total">
                        <td>TOTAL BILLED (INR):</td>
                        <td style="text-align: right; color: #166534; white-space: nowrap;">₹ <?= number_format($paidAmount, 2) ?></td>
                    </tr>
                    <?php if ($isAdvance): ?>
                        <tr>
                            <td style="color: #64748b; font-size: 11px;">Total Machine Gross (+18% GST):</td>
                            <td style="text-align: right; font-weight: 700; color: #0284c7; white-space: nowrap;">₹ <?= number_format($fullMachineGross, 2) ?></td>
                        </tr>
                        <tr style="background: #fef2f2;">
                            <td style="color: #991b1b; font-weight: 800; font-size: 11.5px;">PENDING BALANCE DUE:</td>
                            <td style="text-align: right; font-weight: 900; color: #991b1b; font-size: 13.5px; white-space: nowrap;">₹ <?= number_format($balanceDue, 2) ?></td>
                        </tr>
                    <?php else: ?>
                        <tr style="background: #f0fdf4;">
                            <td style="color: #166534; font-weight: 800; font-size: 11.5px;">BALANCE DUE:</td>
                            <td style="text-align: right; font-weight: 900; color: #166534; font-size: 13px; white-space: nowrap;">₹ 0.00 (PAID IN FULL)</td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <!-- Official Seal & Authorized Signatory Block -->
        <div class="sign-seal-row">
            <div style="flex: 1; max-width: 480px; font-size: 11px; color: #64748b; line-height: 1.45;">
                <strong style="color: #0f172a; font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 2px;">
                    ✔ Official Certified Industrial Tax Document
                </strong>
                This document certifies that the drilling machinery and components listed herein conform to certified Bhavnagar machine-tool metallurgical standards.
                <br>Manufactured by: <strong>Shree Varsha Engineering Works</strong>, Shed L/786, GIDC, Bhavnagar - 364003 (Gujarat).
            </div>

            <div class="signature-box">
                <div style="font-size: 11px; color: #475569; margin-bottom: 2px; font-weight: 700;">
                    For <strong>SHREE VARSHA ENGINEERING WORKS</strong>
                </div>
                <div style="position: relative; height: 75px; display: flex; align-items: center; justify-content: center; margin: 2px 0;">
                    <!-- Company Rubber Stamp Seal (Positioned under signature) -->
                    <img src="<?= assetUrl('assets/images/varsha-brand-seal.svg') ?>" alt="Varsha Brand Official Seal" style="position: absolute; width: 72px; height: 72px; opacity: <?= $isPaid ? '0.85' : '0.65' ?>; pointer-events: none; transform: rotate(-6deg);">
                    <!-- Signature Overlaid Over the Seal -->
                    <img src="<?= assetUrl('assets/images/jay-parmar-signature.svg') ?>" alt="Signature of Jay Parmar" class="signature-img" style="position: relative; z-index: 2; height: 52px; <?= !$isPaid ? 'opacity: 0.85;' : '' ?>">
                </div>
                <div class="signature-line"></div>
                <div style="font-size: 12.5px; font-weight: 800; color: #0f172a; line-height: 1.2;"><?= e($order['authorized_signatory'] ?? 'Jay Parmar') ?></div>
                <div style="font-size: 10.5px; color: #64748b;"><?= $isPaid ? 'Partner &amp; Authorized Signatory' : 'Authorized Signatory (Proforma)' ?></div>
            </div>
        </div>

        <div style="margin-top: 14px; padding-top: 8px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 10px; color: #94a3b8;">
            <?= $isPaid ? 'This is an officially certified Computerized GST Tax Invoice with authorized digital seal. Certified by Shree Varsha Engineering Works, Bhavnagar.' : 'This is an Official Proforma Invoice / Order Acknowledgement. Certified GST Tax Invoice will be issued post payment settlement.' ?>
        </div>
    </div>

</body>
</html>
