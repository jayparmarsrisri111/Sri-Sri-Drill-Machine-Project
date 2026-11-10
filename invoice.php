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
                <div style="background: rgba(15, 23, 42, 0.95); border: 1.5px solid rgba(56, 189, 248, 0.4); border-radius: 14px; padding: 22px; margin-bottom: 30px;">
                    <div style="font-size: 16px; font-weight: 800; color: #38bdf8; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                        <span>👤</span> <span>Your Verified Machinery Orders &amp; Tax Bills</span>
                    </div>
                    <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 13px; min-width: 600px;">
                            <thead>
                                <tr style="border-bottom: 1.5px solid rgba(255,255,255,0.1); text-align: left; color: #94a3b8; font-size: 11px; text-transform: uppercase;">
                                    <th style="padding: 8px 10px;">Order No</th>
                                    <th style="padding: 8px 10px;">Machine Model</th>
                                    <th style="padding: 8px 10px;">Date</th>
                                    <th style="padding: 8px 10px; text-align: right;">Amount</th>
                                    <th style="padding: 8px 10px; text-align: center;">Payment</th>
                                    <th style="padding: 8px 10px; text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($customerOrders as $co): 
                                    $isCoPaid = ($co['payment_status'] === 'paid');
                                ?>
                                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                                        <td style="padding: 12px 10px; font-weight: 700; color: #00e5ff;">
                                            <?= e($co['order_number']) ?>
                                        </td>
                                        <td style="padding: 12px 10px; color: #fff;">
                                            <?= e($co['model_number']) ?> - <?= e($co['product_name']) ?>
                                        </td>
                                        <td style="padding: 12px 10px; color: #94a3b8; font-size: 12px;">
                                            <?= date('d-M-Y', strtotime($co['created_at'])) ?>
                                        </td>
                                        <td style="padding: 12px 10px; text-align: right; font-weight: 700; color: #34d399;">
                                            ₹ <?= number_format($co['total_amount'], 2) ?>
                                        </td>
                                        <td style="padding: 12px 10px; text-align: center;">
                                            <span style="display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 800; <?= $isCoPaid ? 'background:#dcfce7; color:#15803d;' : 'background:#fef3c7; color:#b45309;' ?>">
                                                <?= $isCoPaid ? '✔ PAID' : '⏳ PENDING' ?>
                                            </span>
                                        </td>
                                        <td style="padding: 12px 10px; text-align: right;">
                                            <a href="invoice.php?order_id=<?= $co['id'] ?>" class="btn btn-outline" style="padding: 5px 12px; font-size: 11.5px; border-color: #00e5ff; color: #00e5ff; font-weight: 700; text-decoration: none;">
                                                📄 View Bill →
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
            --text: #1e293b;
            --border: #cbd5e1;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: #090d16;
            color: #1e293b;
            min-height: 100vh;
            padding: 30px 15px;
        }

        /* Action Toolbar (Screen only) */
        .invoice-actions-bar {
            max-width: 860px;
            margin: 0 auto 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid rgba(56, 189, 248, 0.3);
            border-radius: 12px;
            padding: 14px 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        }
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            font-size: 13.5px;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-print {
            background: linear-gradient(135deg, #00e5ff 0%, #2563eb 100%);
            color: #fff;
        }
        .btn-print:hover {
            box-shadow: 0 0 15px rgba(0, 229, 255, 0.5);
        }
        .btn-whatsapp-share {
            background: #25D366;
            color: #fff;
        }
        .btn-back {
            background: rgba(255, 255, 255, 0.1);
            color: #cbd5e1;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        /* The Printable White Tax Invoice Container */
        .invoice-paper {
            max-width: 860px;
            margin: 0 auto;
            background: #ffffff;
            color: #1e293b;
            padding: 40px 48px;
            border-radius: 8px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.6);
            position: relative;
            line-height: 1.45;
        }

        /* Header */
        .inv-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .company-branding {
            display: flex;
            align-items: center;
            gap: 18px;
        }
        .company-logo-img {
            width: 82px;
            height: 82px;
            object-fit: contain;
            border-radius: 50%;
            border: 2px solid #0284c7;
        }
        .company-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.5px;
        }
        .brand-badge {
            display: inline-block;
            background: #0f172a;
            color: #00e5ff;
            font-size: 11px;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 4px;
            letter-spacing: 1px;
            margin-top: 2px;
        }
        .company-meta {
            font-size: 12px;
            color: #475569;
            margin-top: 5px;
            line-height: 1.4;
        }
        .invoice-title-block {
            text-align: right;
        }
        .inv-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 24px;
            font-weight: 900;
            color: #0284c7;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .inv-meta-table {
            font-size: 12px;
            margin-top: 8px;
            text-align: right;
        }
        .inv-meta-table td {
            padding: 2px 0 2px 10px;
        }
        .status-badge {
            display: inline-block;
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
            font-weight: 800;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        /* Parties Grid (Bill To & Ship To) */
        .parties-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 16px 20px;
            margin-bottom: 24px;
            font-size: 12.5px;
        }
        .party-heading {
            font-size: 11px;
            font-weight: 800;
            color: #0284c7;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
        }

        /* Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
            margin-bottom: 20px;
        }
        .items-table th {
            background: #0f172a;
            color: #ffffff;
            font-weight: 700;
            text-align: left;
            padding: 10px 12px;
            font-size: 11.5px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .items-table tr:nth-child(even) td {
            background: #fcfdfe;
        }

        /* Totals Area */
        .totals-section {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 24px;
            margin-bottom: 28px;
        }
        .terms-box {
            font-size: 11px;
            color: #64748b;
            line-height: 1.45;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 14px;
        }
        .totals-table {
            width: 100%;
            font-size: 12.5px;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #f1f5f9;
        }
        .totals-table tr.grand-total td {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
            border-top: 2px solid #0f172a;
            border-bottom: 2px solid #0f172a;
            background: #f0fdf4;
            padding: 8px;
        }

        /* Sign & Seal Row */
        .sign-seal-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 35px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        .seal-box {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .seal-image {
            width: 110px;
            height: 110px;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }
        .signature-box {
            text-align: center;
            width: 250px;
        }
        .signature-img {
            height: 65px;
            width: auto;
            margin-bottom: 4px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .signatory-name {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.5px;
        }
        .signatory-title {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }

        /* ==========================================================================
           MOBILE & TABLET SCREEN RESPONSIVENESS (Flawless UX on all smartphones)
           ========================================================================== */
        @media screen and (max-width: 768px) {
            body {
                padding: 10px 8px 80px 8px;
            }
            .invoice-actions-bar {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
                padding: 12px 14px;
            }
            .invoice-actions-bar > div {
                flex-direction: column;
                width: 100%;
                gap: 8px;
            }
            .btn-action {
                width: 100%;
                justify-content: center;
                text-align: center;
                padding: 10px 14px;
            }
            .invoice-paper {
                width: 100% !important;
                max-width: 100% !important;
                min-height: auto !important;
                padding: 16px 12px !important;
                border-radius: 8px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            }
            .inv-header {
                flex-direction: column;
                gap: 14px;
                align-items: flex-start;
                border-bottom: 1.5px solid #0f172a;
                padding-bottom: 14px;
                margin-bottom: 14px;
            }
            .company-branding {
                flex-direction: row;
                align-items: center;
                gap: 12px;
                width: 100%;
            }
            .company-logo-img {
                width: 58px;
                height: 58px;
                flex-shrink: 0;
                background: #ffffff;
                padding: 2px;
                border: 2px solid #0284c7;
                border-radius: 50%;
            }
            .company-title {
                font-size: 16.5px;
                line-height: 1.25;
            }
            .invoice-title-block {
                text-align: left;
                width: 100%;
                background: #f8fafc;
                padding: 10px 12px;
                border-radius: 6px;
                border-left: 4px solid #0284c7;
            }
            .inv-meta-table {
                text-align: left;
                width: 100%;
                margin-top: 6px;
            }
            .inv-meta-table td {
                padding: 2px 4px;
            }
            .parties-grid {
                grid-template-columns: 1fr;
                gap: 14px;
                padding: 12px;
                margin-bottom: 16px;
            }
            .items-table-wrapper {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                margin-bottom: 18px;
                border: 1px solid #e2e8f0;
                border-radius: 6px;
            }
            .items-table {
                min-width: 580px;
                margin-bottom: 0;
            }
            .totals-section {
                grid-template-columns: 1fr;
                gap: 16px;
                margin-bottom: 18px;
            }
            .sign-seal-row {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 20px;
                margin-top: 20px;
                padding-top: 16px;
            }
            .seal-box {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 8px;
            }
            .signature-box {
                text-align: center;
                width: 100%;
            }
            .signature-box .signature-line {
                margin: 6px auto;
            }
            .crypto-proof-box {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
        }

        /* ==========================================================================
           STRICT 1-PAGE A4 PRINT & PDF STYLES (Guaranteed Single Page Output)
           ========================================================================== */
        @page {
            size: A4 portrait;
            margin: 4mm 6mm;
        }

        @media print {
            html, body {
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
                height: 100% !important;
                width: 100% !important;
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
                padding: 8px 12px !important;
                margin: 0 auto !important;
                max-width: 100% !important;
                width: 100% !important;
                box-sizing: border-box !important;
                page-break-after: avoid !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            .inv-header {
                padding-bottom: 6px !important;
                margin-bottom: 6px !important;
                border-bottom: 1.5px solid #0f172a !important;
            }
            .company-branding {
                gap: 10px !important;
            }
            .company-logo-img {
                width: 48px !important;
                height: 48px !important;
            }
            .company-title {
                font-size: 15px !important;
                line-height: 1.15 !important;
            }
            .brand-badge {
                font-size: 9.5px !important;
                padding: 1px 6px !important;
            }
            .company-meta {
                font-size: 9px !important;
                margin-top: 2px !important;
                line-height: 1.25 !important;
            }
            .inv-title {
                font-size: 15px !important;
                letter-spacing: 0.5px !important;
            }
            .status-badge {
                font-size: 9px !important;
                padding: 1px 6px !important;
            }
            .inv-meta-table {
                font-size: 9.5px !important;
                margin-top: 2px !important;
            }
            .inv-meta-table td {
                padding: 1px 0 1px 4px !important;
            }
            .parties-grid {
                padding: 6px 10px !important;
                margin-bottom: 6px !important;
                gap: 12px !important;
                font-size: 9.5px !important;
                line-height: 1.3 !important;
            }
            .party-heading {
                font-size: 9px !important;
                margin-bottom: 2px !important;
                padding-bottom: 2px !important;
            }
            .items-table {
                font-size: 9.5px !important;
                margin-bottom: 6px !important;
            }
            .items-table th {
                padding: 4px 6px !important;
                font-size: 9px !important;
            }
            .items-table td {
                padding: 4px 6px !important;
                line-height: 1.25 !important;
            }
            .totals-section {
                gap: 10px !important;
                margin-bottom: 6px !important;
            }
            .terms-box {
                font-size: 8.5px !important;
                padding: 4px 6px !important;
                line-height: 1.25 !important;
            }
            .totals-table {
                font-size: 9.5px !important;
            }
            .totals-table td {
                padding: 2px 4px !important;
            }
            .totals-table tr.grand-total td {
                font-size: 11px !important;
                padding: 4px 6px !important;
            }
            .amount-words-box {
                font-size: 9.5px !important;
                padding: 3px 6px !important;
                margin-bottom: 6px !important;
            }
            .sign-seal-row {
                margin-top: 4px !important;
                padding-top: 4px !important;
            }
            .seal-image {
                width: 52px !important;
                height: 52px !important;
            }
            .signature-box {
                width: 180px !important;
            }
            .signature-img {
                height: 34px !important;
                margin-bottom: 2px !important;
            }
            .signatory-name {
                font-size: 10.5px !important;
            }
            .signatory-title {
                font-size: 8.5px !important;
            }
            .crypto-proof-box {
                margin-top: 6px !important;
                padding: 4px 8px !important;
            }
            .crypto-proof-box div {
                font-size: 8.5px !important;
            }
            .disclaimer-box {
                margin-top: 4px !important;
                padding-top: 2px !important;
                font-size: 8px !important;
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
                $waText = "Hello " . APP_NAME . ", Here is my order confirmation and invoice:\nInvoice No: " . $order['invoice_number'] . "\nModel: " . $order['model_number'] . "\nAmount: Rs. " . number_format($order['total_amount'], 2) . "\nRazorpay Payment ID: " . $order['razorpay_payment_id'];
            ?>
            <a href="<?= getWhatsAppLink(COMPANY_WHATSAPP, $waText) ?>" target="_blank" class="btn-action btn-whatsapp-share">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/></svg>
                Share on WhatsApp
            </a>
        </div>
    </div>

    <!-- Official Paper Tax Invoice Container -->
    <div class="invoice-paper">
        <!-- Header -->
        <div class="inv-header">
            <div class="company-branding">
                <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand" class="company-logo-img">
                <div>
                    <h1 class="company-title"><?= APP_NAME ?></h1>
                    <span class="brand-badge">★ <?= BRAND_NAME ?> ★ INDUSTRIAL MACHINERY</span>
                    <div class="company-meta">
                        <?= COMPANY_ADDRESS ?><br>
                        <strong>GSTIN:</strong> <?= COMPANY_GSTIN ?> &nbsp;|&nbsp; <strong>State Code:</strong> 24 (Gujarat)<br>
                        <strong>Phone:</strong> <?= COMPANY_PHONE_1 ?> &nbsp;|&nbsp; <strong>Email:</strong> <?= COMPANY_EMAIL ?>
                    </div>
                </div>
            </div>

            <?php 
                $isPaid = (isset($order['payment_status']) && strtolower($order['payment_status']) === 'paid');
                $orderType = $order['payment_type'] ?? 'booking_advance';
                $isAdvance = ($orderType === 'booking_advance');

                $machineBasePrice = floatval($order['machine_price'] ?? 285000.00);
                if ($machineBasePrice <= 0) $machineBasePrice = 285000.00;
                $fullMachineGross = round($machineBasePrice * 1.18, 2);

                if ($isAdvance) {
                    $paidAmount = floatval($order['total_amount'] ?? 25000.00);
                    if ($paidAmount <= 0) {
                        $paidAmount = 25000.00;
                    }
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
                $payMethodLabel = 'Razorpay 256-Bit SSL';
                $payMethodColor = '#10b981';
                if ($pm === 'cod') {
                    $payMethodLabel = 'Cash on Delivery (COD)';
                    $payMethodColor = '#d97706';
                } elseif ($pm === 'bank_transfer') {
                    $payMethodLabel = 'Direct Bank Transfer (NEFT/RTGS)';
                    $payMethodColor = '#0284c7';
                } elseif ($pm === 'cheque') {
                    $payMethodLabel = 'Account Payee Cheque / DD';
                    $payMethodColor = '#7e22ce';
                }

                // Determine official title based on true payment verification status
                if ($isPaid) {
                    $docTitle = $isAdvance ? 'ADVANCE BOOKING TAX INVOICE' : 'TAX INVOICE (100% PAID)';
                } else {
                    if ($pm === 'cod') {
                        $docTitle = 'PROFORMA INVOICE (CASH ON DELIVERY ORDER)';
                    } elseif ($pm === 'bank_transfer') {
                        $docTitle = 'PROFORMA INVOICE / RTGS-NEFT PAYMENT ADVICE';
                    } else {
                        $docTitle = 'PROFORMA INVOICE (PAYMENT PENDING)';
                    }
                }
            ?>

            <div class="invoice-title-block">
                <div class="inv-title"><?= $docTitle ?></div>
                <div style="margin: 4px 0;">
                    <?php if ($isPaid): ?>
                        <?php if ($isAdvance): ?>
                            <span class="status-badge" style="background: #dcfce7; color: #15803d; border-color: #86efac;">
                                ✔ ADVANCE TOKEN PAID &amp; VERIFIED • ₹ <?= number_format($paidAmount, 2) ?>
                            </span>
                        <?php else: ?>
                            <span class="status-badge" style="background: #dcfce7; color: #15803d; border-color: #86efac;">
                                ✔ 100% FULL PAYMENT RECEIVED (NIL BALANCE) • ₹ <?= number_format($paidAmount, 2) ?>
                            </span>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if ($pm === 'cod'): ?>
                            <span class="status-badge" style="background: #fef3c7; color: #b45309; border-color: #fcd34d;">
                                ⏳ CASH ON DELIVERY BOOKED • ₹ <?= number_format($paidAmount, 2) ?> (DUE AT DISPATCH/DELIVERY)
                            </span>
                        <?php elseif ($pm === 'bank_transfer'): ?>
                            <span class="status-badge" style="background: #e0f2fe; color: #0369a1; border-color: #7dd3fc;">
                                ⏳ RTGS / NEFT PAYMENT PENDING • ₹ <?= number_format($paidAmount, 2) ?> (VERIFICATION AWAITED)
                            </span>
                        <?php else: ?>
                            <span class="status-badge" style="background: #fee2e2; color: #991b1b; border-color: #fca5a5;">
                                ⏳ PAYMENT PENDING / NOT YET REALIZED • ₹ <?= number_format($paidAmount, 2) ?>
                            </span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <table class="inv-meta-table">
                    <tr>
                        <td style="color: #64748b; font-weight: 600;">Invoice No:</td>
                        <td><span style="font-size: 13.5px; font-weight: 900; color: #0284c7; background: #e0f2fe; padding: 2px 7px; border-radius: 4px; border: 1px solid #7dd3fc;"><?= e($order['invoice_number']) ?></span></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-weight: 600;">Invoice Date:</td>
                        <td><strong style="color: #0f172a;"><?= date('d-M-Y', strtotime($order['created_at'])) ?></strong></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-weight: 600;">Time of Supply:</td>
                        <td><strong style="color: #0f172a;"><?= date('h:i:s A', strtotime($order['created_at'])) ?> (IST)</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-weight: 600;">Order Ref:</td>
                        <td><strong style="color: #0f172a;"><?= e($order['order_number']) ?></strong></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-weight: 600;">Payment Method:</td>
                        <td><span style="color: <?= $payMethodColor ?>; font-weight: 800;"><?= e($payMethodLabel) ?></span></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-weight: 600;">Transaction ID:</td>
                        <td style="font-family: monospace; font-size: 11.5px; font-weight: 700; color: #334155;"><?= e($order['razorpay_payment_id']) ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Customer & Dispatch Details -->
        <div class="parties-grid">
            <div>
                <div class="party-heading">BILL TO (CUSTOMER DETAILS)</div>
                <div style="font-weight: 700; font-size: 13.5px; color: #0f172a; margin-bottom: 2px;">
                    <?= e($order['customer_name']) ?>
                </div>
                <?php if (!empty($order['company_name'])): ?>
                    <div style="font-weight: 600; color: #334155; margin-bottom: 3px;">
                        <?= e($order['company_name']) ?>
                    </div>
                <?php endif; ?>
                <div style="color: #475569; line-height: 1.4;">
                    <?= nl2br(e($order['customer_address'])) ?><br>
                    <?= e($order['customer_city']) ?> - <?= e($order['customer_pincode']) ?>
                </div>
                <div style="margin-top: 6px; color: #334155;">
                    <strong>Phone:</strong> <?= e($order['customer_phone']) ?><br>
                    <strong>Email:</strong> <?= e($order['customer_email']) ?><br>
                    <?php if (!empty($order['customer_gst'])): ?>
                        <strong>GSTIN:</strong> <?= e($order['customer_gst']) ?>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <div class="party-heading">DISPATCH &amp; MANUFACTURING DETAILS</div>
                <div style="color: #475569; line-height: 1.45;">
                    <strong>Dispatch Location:</strong> Factory Works, Shed L/786, GIDC, Bhavnagar<br>
                    <strong>Transport Mode:</strong> Road Freight / Surface Transport<br>
                    <strong>Manufacturing Status:</strong> <span style="color: #0284c7; font-weight: 700; text-transform: uppercase;"><?= e($order['order_status']) ?></span><br>
                    <strong>Payment Category:</strong> 
                    <?= $isAdvance ? '<span style="color:#b45309; font-weight:700;">Booking Advance Token (Slot Reserved)</span>' : '<span style="color:#15803d; font-weight:700;">100% Full Payment</span>' ?><br>
                    <?php if (!empty($order['notes'])): ?>
                        <div style="margin-top: 5px; padding: 6px 8px; background: #fff; border: 1px dashed #cbd5e1; border-radius: 4px; font-size: 11.5px;">
                            <strong>Customer Note:</strong> <?= e($order['notes']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if (!$isPaid): ?>
            <!-- Proforma Notice & Bank Transfer Instructions (Shown when payment is not yet completed/verified) -->
            <div style="margin: 14px 0 16px; padding: 12px 16px; background: #fffbeb; border: 1.5px solid #f59e0b; border-radius: 8px; color: #78350f;">
                <div style="display: flex; align-items: center; gap: 8px; font-weight: 800; font-size: 13.5px; margin-bottom: 6px; color: #b45309;">
                    <span>⚠️</span>
                    <span>PROFORMA INVOICE &bull; ચુકવણી બાકી (PAYMENT AWAITED / UNPAID)</span>
                </div>
                <div style="font-size: 11.5px; line-height: 1.5; color: #92400e;">
                    This document is an <strong>Official Proforma Invoice &amp; Order Booking Confirmation</strong>. 
                    Final Certified GST Tax Invoice with official red rubber seal and machinery dispatch clearance will be issued upon realization of payment.
                    <?php if ($pm === 'bank_transfer' || $pm === 'online'): ?>
                        <div style="margin-top: 8px; padding: 10px; background: #ffffff; border: 1px solid #fde68a; border-radius: 6px; color: #1e293b;">
                            <strong style="color: #0f172a; font-size: 12px; display: block; margin-bottom: 4px;">
                                🏦 Direct RTGS / NEFT / IMPS Company Bank Details (for Wire Transfer):
                            </strong>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 8px; font-size: 11.5px;">
                                <div><strong>Beneficiary:</strong> SHREE VARSHA ENGINEERING WORKS</div>
                                <div><strong>Bank Name:</strong> State Bank of India (SBI)</div>
                                <div><strong>Current A/C No:</strong> 39820010005432</div>
                                <div><strong>IFSC Code:</strong> SBIN0001980 (Chitra Branch, Bhavnagar)</div>
                            </div>
                            <div style="font-size: 10.5px; color: #64748b; margin-top: 4px;">
                                * After RTGS/NEFT transfer, please share transaction reference (UTR) on WhatsApp: <strong><?= COMPANY_PHONE_1 ?></strong> for instant payment clearance.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Items Table (Responsive Scroll Wrapper for Mobile Screens) -->
        <div class="items-table-wrapper">
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 45%;">Machinery Description &amp; Technical Specs</th>
                        <th style="width: 12%;">HSN / SAC</th>
                        <th style="width: 8%; text-align: center;">Qty</th>
                        <th style="width: 15%; text-align: right;">Billed Amount (₹)</th>
                        <th style="width: 15%; text-align: right;">Taxable Value (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orderItems) && count($orderItems) > 1): ?>
                        <?php foreach ($orderItems as $idx => $it): ?>
                            <tr>
                                <td><?= $idx + 1 ?></td>
                                <td>
                                    <strong style="color: #0f172a; font-size: 13px;">
                                        <?= e($it['product_name']) ?>
                                    </strong>
                                    <div style="color: #0284c7; font-weight: 700; font-size: 11.5px; margin: 2px 0;">
                                        MODEL: <?= e($it['model_number']) ?> &nbsp;|&nbsp; BRAND: <?= BRAND_NAME ?>
                                    </div>
                                    <div style="font-size: 11px; color: #64748b;">
                                        Genuine Industrial Specification • Grade-25 Precision Cast / Alloy
                                    </div>
                                </td>
                                <td>8459 29 10</td>
                                <td style="text-align: center; font-weight: 700; color: #0284c7;">
                                    <?= intval($it['quantity']) ?> Unit<?= (intval($it['quantity']) > 1) ? 's' : '' ?>
                                </td>
                                <td style="text-align: right;">
                                    <?= number_format(floatval($it['total_price']) * 1.18, 2) ?>
                                </td>
                                <td style="text-align: right; font-weight: 600;">
                                    <?= number_format(floatval($it['total_price']), 2) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td>1</td>
                            <td>
                                <strong style="color: #0f172a; font-size: 13px;">
                                    <?= e($order['product_name']) ?>
                                </strong>
                                <div style="color: #0284c7; font-weight: 700; font-size: 11.5px; margin: 2px 0;">
                                    MODEL: <?= e($order['model_number']) ?> &nbsp;|&nbsp; BRAND: <?= BRAND_NAME ?>
                                </div>
                                <div style="font-size: 11px; color: #64748b; line-height: 1.35;">
                                    • Drilling Capacity: <?= e($order['drilling_capacity'] ?? 'Heavy Duty') ?><br>
                                    • Motor Drive: <?= e($order['motor_power'] ?? 'Standard 3-Phase') ?><br>
                                    • Grade-25 Cast Iron Hardened Column &amp; Arm Assembly
                                </div>

                                <?php if ($isAdvance): ?>
                                    <div style="margin-top: 8px; padding: 6px 10px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 4px; font-size: 11.5px; color: #92400e;">
                                        <strong>★ ADVANCE PAYMENT CLAUSE:</strong> This invoice receipts the ₹ <?= number_format($paidAmount, 2) ?> token advance (for <?= $invQty ?> unit<?= ($invQty > 1) ? 's' : '' ?>) to lock production queue &amp; allocation of casting serial numbers.
                                        <br>Full Billed Value: <strong>₹ <?= number_format($order['machine_price'], 2) ?> (+18% GST = ₹ <?= number_format($fullMachineGross, 2) ?>)</strong>.
                                    </div>
                                <?php else: ?>
                                    <div style="margin-top: 8px; padding: 6px 10px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 4px; font-size: 11.5px; color: #166534;">
                                        <strong>★ 100% PAID IN FULL:</strong> Full invoice value settled. Machine scheduled for direct freight dispatch.
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>8459 29 10</td>
                            <?php $invQty = max(1, intval($order['quantity'] ?? 1)); ?>
                            <td style="text-align: center; font-weight: 700; color: #0284c7;"><?= $invQty ?> Unit<?= ($invQty > 1) ? 's' : '' ?></td>
                            <td style="text-align: right;"><?= number_format($paidAmount, 2) ?></td>
                            <td style="text-align: right; font-weight: 600;"><?= number_format($taxableAmount, 2) ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Totals & Terms Grid -->
        <div class="totals-section">
            <div class="terms-box">
                <strong style="color: #0f172a; display: block; margin-bottom: 4px;">Terms &amp; Conditions:</strong>
                1. <strong>Warranty:</strong> 12 Months manufacturer warranty against mechanical casting &amp; gear defects from date of dispatch.<br>
                2. <strong>Testing:</strong> Machine pre-inspected and dial-gauge accuracy test run completed before dispatch.<br>
                3. <strong>Freight &amp; Unloading:</strong> Transit insurance and transport charges payable by consignee at destination.<br>
                4. <strong>Jurisdiction:</strong> Subject to Bhavnagar (Gujarat) Jurisdiction only.
            </div>

            <div>
                <table class="totals-table">
                    <tr>
                        <td style="color: #64748b;">Taxable Value (This Invoice):</td>
                        <td style="text-align: right; font-weight: 600;">₹ <?= number_format($taxableAmount, 2) ?></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">CGST (9.00%):</td>
                        <td style="text-align: right; font-weight: 600;">₹ <?= number_format($cgstAmount, 2) ?></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">SGST (9.00%):</td>
                        <td style="text-align: right; font-weight: 600;">₹ <?= number_format($sgstAmount, 2) ?></td>
                    </tr>
                    <tr class="grand-total">
                        <td>TOTAL PAID TODAY (INR):</td>
                        <td style="text-align: right; color: #15803d;">₹ <?= number_format($paidAmount, 2) ?></td>
                    </tr>
                    <?php if ($isAdvance): ?>
                    <tr>
                        <td style="color: #475569; font-size: 11.5px; padding-top: 8px;">Total Machine Value (with 18% GST):</td>
                        <td style="text-align: right; font-weight: 700; color: #0284c7; padding-top: 8px;">₹ <?= number_format($fullMachineGross, 2) ?></td>
                    </tr>
                    <tr style="background: #fef2f2; border: 1.5px solid #f87171;">
                        <td style="color: #b91c1c; font-weight: 800; font-size: 12px; padding: 6px 8px;">PENDING BALANCE DUE ON DISPATCH:</td>
                        <td style="text-align: right; font-weight: 900; color: #b91c1c; font-size: 14px; padding: 6px 8px;">₹ <?= number_format($balanceDue, 2) ?></td>
                    </tr>
                    <?php else: ?>
                    <tr style="background: #f0fdf4; border: 1.5px solid #86efac;">
                        <td style="color: #15803d; font-weight: 800; font-size: 12px; padding: 6px 8px;">BALANCE DUE (REMAINING):</td>
                        <td style="text-align: right; font-weight: 900; color: #15803d; font-size: 13.5px; padding: 6px 8px;">₹ 0.00 (PAID IN FULL - NIL BALANCE)</td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <div class="amount-words-box" style="font-size: 12px; color: #334155; margin-bottom: 20px; padding: 7px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px;">
            <strong>Amount in Words:</strong> <?= amountInWords($paidAmount) ?>
        </div>

        <!-- Official Seal and Authorized Signatory Row -->
        <div class="sign-seal-row">
            <!-- Official Company Rubber Stamp / Sico (શિકો) -->
            <div class="seal-box" style="<?= !$isPaid ? 'opacity: 0.85; border-color: #f59e0b;' : '' ?>">
                <img src="<?= assetUrl('assets/images/varsha-brand-seal.svg') ?>" alt="Varsha Brand Official Stamp (શિકો)" class="seal-image" title="Official Rubber Stamp (શિકો) - Shree Varsha Engineering Works" style="<?= !$isPaid ? 'filter: grayscale(40%);' : '' ?>">
                <div>
                    <div style="font-size: 11.5px; font-weight: 800; color: <?= $isPaid ? '#7c3aed' : '#b45309' ?>; letter-spacing: 0.8px;">
                        <?= $isPaid ? 'OFFICIAL FACTORY STAMP / SICO (શિકો)' : 'PROFORMA ORDER STAMP / SICO (શિકો)' ?>
                    </div>
                    <div style="font-size: 10.5px; color: #64748b; line-height: 1.35; margin-top: 2px;">
                        <strong>VARSHA BRAND (SVEW)</strong><br>
                        Bhavnagar Factory Unit • ISO 9001:2015<br>
                        <?= $isPaid ? '<span style="color:#16a34a; font-weight:700;">Payment Realized &amp; Verified</span>' : '<span style="color:#d97706; font-weight:700;">Subject to Payment Realization</span>' ?>
                    </div>
                </div>
            </div>

            <!-- Authorized Signatory -->
            <div class="signature-box">
                <div style="font-size: 11px; color: #64748b; margin-bottom: 2px;">
                    For <strong>SHREE VARSHA ENGINEERING WORKS</strong>
                </div>
                <!-- Jay Parmar Signature -->
                <img src="<?= assetUrl('assets/images/jay-parmar-signature.svg') ?>" alt="Signature of Jay Parmar" class="signature-img" style="<?= !$isPaid ? 'opacity: 0.75;' : '' ?>">
                <div class="signatory-name"><?= e($order['authorized_signatory'] ?? 'Jay Parmar') ?></div>
                <div class="signatory-title"><?= $isPaid ? 'Partner &amp; Authorized Signatory' : 'Authorized Signatory (Proforma / Acknowledgment)' ?></div>
            </div>
        </div>

        <?php
            $cryptoHash = $order['crypto_seal'] ?? hash('sha256', $order['order_number'] . '|' . $order['invoice_number'] . '|' . ($order['razorpay_payment_id'] ?? 'UNPAID') . '|' . $order['total_amount'] . '|' . COMPANY_GSTIN . '|' . RAZORPAY_KEY_SECRET);
        ?>
        <!-- World-First Cryptographic Invoice Integrity & Anti-Forgery Ledger Seal -->
        <div class="crypto-proof-box" style="margin-top: 18px; background: #f8fafc; border: 1.5px dashed <?= $isPaid ? '#0284c7' : '#f59e0b' ?>; border-radius: 8px; padding: 10px 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="background: <?= $isPaid ? '#0284c7' : '#f59e0b' ?>; color: #fff; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0;">
                    <?= $isPaid ? '🔒' : '⏳' ?>
                </div>
                <div>
                    <div style="font-size: 11px; font-weight: 800; color: #0f172a; letter-spacing: 0.5px;">
                        <?= $isPaid ? 'CRYPTOGRAPHIC PROOF OF AUTHENTICITY (SHA-256 DIGITAL LEDGER SEAL)' : 'PROFORMA ORDER VERIFICATION LOG (PAYMENT AUDIT PENDING)' ?>
                    </div>
                    <div style="font-family: monospace; font-size: 9.5px; color: #475569; word-break: break-all; margin-top: 2px;">
                        PROVENANCE HASH: <?= $cryptoHash ?>
                    </div>
                </div>
            </div>
            <div>
                <?php if ($isPaid): ?>
                    <span style="display: inline-block; background: #dcfce7; border: 1px solid #16a34a; color: #15803d; font-size: 10.5px; font-weight: 800; padding: 3px 8px; border-radius: 4px;">
                        ✓ IMMUTABLE &amp; VERIFIED
                    </span>
                <?php else: ?>
                    <span style="display: inline-block; background: #fef3c7; border: 1px solid #d97706; color: #b45309; font-size: 10.5px; font-weight: 800; padding: 3px 8px; border-radius: 4px;">
                        ⏳ PAYMENT VERIFICATION AWAITED
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div class="disclaimer-box" style="margin-top: 16px; padding-top: 10px; border-top: 1px dashed #cbd5e1; text-align: center; font-size: 10px; color: #94a3b8;">
            <?= $isPaid ? 'This is an electronically generated and certified Tax Invoice with secure digital audit trail. Razorpay Payment ID: ' . e($order['razorpay_payment_id']) . '. Certified by Shree Varsha Engineering Works.' : 'This is a computer generated Proforma Invoice / Order Acknowledgement. Official GST Tax Invoice is generated post settlement of payment.' ?>
        </div>
    </div>

</body>
</html>
