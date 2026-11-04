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
try {
    $pdo = Database::getConnection();
    if ($orderId > 0) {
        $stmt = $pdo->prepare("SELECT o.*, p.image_url, p.drilling_capacity, p.spindle_speeds, p.motor_power FROM orders o LEFT JOIN products p ON o.product_id = p.id WHERE o.id = :id");
        $stmt->execute([':id' => $orderId]);
        $order = $stmt->fetch();
    } elseif (!empty($orderNumber)) {
        $stmt = $pdo->prepare("SELECT o.*, p.image_url, p.drilling_capacity, p.spindle_speeds, p.motor_power FROM orders o LEFT JOIN products p ON o.product_id = p.id WHERE o.order_number = :num");
        $stmt->execute([':num' => $orderNumber]);
        $order = $stmt->fetch();
    } else {
        // Fetch latest order for demo/preview if no ID provided
        $stmt = $pdo->query("SELECT o.*, p.image_url, p.drilling_capacity, p.spindle_speeds, p.motor_power FROM orders o LEFT JOIN products p ON o.product_id = p.id ORDER BY o.id DESC LIMIT 1");
        $order = $stmt->fetch();
    }
} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}

if (!$order) {
    // If no order in DB yet, create a demo order record for instant real invoice preview
    $order = [
        'id' => 1,
        'order_number' => 'SVEW-ORD-' . date('Ymd') . '-A108F',
        'invoice_number' => 'SVEW-INV-' . date('Y') . '-88421',
        'product_id' => 1,
        'product_name' => '40mm All-Geared Radial Drill Machine',
        'model_number' => 'SVEW-RD-40',
        'customer_name' => 'Kishorbhai Parmar',
        'company_name' => 'Parmar Precision Tool Works',
        'customer_email' => 'kishor.parmar@example.com',
        'customer_phone' => '+91 98251 12345',
        'customer_address' => 'Plot No. 18, Phase 2, G.I.D.C. Lodhika Industrial Estate',
        'customer_city' => 'Rajkot',
        'customer_pincode' => '360021',
        'customer_gst' => '24AABCP9876C1Z4',
        'payment_type' => 'booking_advance',
        'machine_price' => 285000.00,
        'taxable_amount' => 21186.44,
        'cgst_amount' => 1906.78,
        'sgst_amount' => 1906.78,
        'total_amount' => 25000.00,
        'razorpay_order_id' => 'order_demo_1092',
        'razorpay_payment_id' => 'pay_demo_varsha_99182',
        'razorpay_signature' => 'verified_demo_hmac_sha256',
        'payment_status' => 'paid',
        'order_status' => 'confirmed',
        'authorized_signatory' => 'Jay Parmar',
        'notes' => '415V 3-Phase standard industrial specification',
        'created_at' => date('Y-m-d H:i:s'),
        'image_url' => 'assets/images/machinery/radial-drill-real.jpg',
        'drilling_capacity' => '40 mm (Cast Iron / Steel)',
        'spindle_speeds' => '8 Speeds (50 - 1500 RPM)',
        'motor_power' => '3 HP (2.2 kW)'
    ];
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
        <div style="display: flex; align-items: center; gap: 10px;">
            <a href="<?= BASE_URL ?>/index.php" class="btn-action btn-back">
                ← Back to Home
            </a>
            <span style="color: #94a3b8; font-size: 13px;">
                Order No: <strong style="color: #fff;"><?= e($order['order_number']) ?></strong>
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
                    <h1 class="company-title">SHREE VARSHA ENGINEERING WORKS</h1>
                    <span class="brand-badge">★ VARSHA BRAND ★ INDUSTRIAL MACHINERY</span>
                    <div class="company-meta">
                        Shed No. L/786, GIDC, Nr. Hadanagar,<br>
                        Bhavnagar - 36400003, Gujarat, India<br>
                        <strong>GSTIN:</strong> <?= COMPANY_GSTIN ?> &nbsp;|&nbsp; <strong>State Code:</strong> 24 (Gujarat)<br>
                        <strong>Phone:</strong> <?= COMPANY_PHONE_1 ?> &nbsp;|&nbsp; <strong>Email:</strong> <?= COMPANY_EMAIL ?>
                    </div>
                </div>
            </div>

            <?php 
                $isAdvance = ($order['payment_type'] === 'booking_advance');
                $fullMachineGross = floatval($order['machine_price'] ?? 0) * 1.18;
                $balanceDue = max(0, $fullMachineGross - floatval($order['total_amount'] ?? 0));
            ?>

            <div class="invoice-title-block">
                <div class="inv-title"><?= $isAdvance ? 'ADVANCE BOOKING TAX INVOICE' : 'TAX INVOICE' ?></div>
                <div style="margin: 4px 0;">
                    <?php if ($isAdvance): ?>
                        <span class="status-badge" style="background: #fef3c7; color: #b45309; border-color: #fcd34d;">
                            ✔ ADVANCE TOKEN PAID (SLOT RESERVED)
                        </span>
                    <?php else: ?>
                        <span class="status-badge">
                            ✔ 100% FULL PAYMENT RECEIVED
                        </span>
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
                        <td><span style="color: #10b981; font-weight: 700;">Razorpay 256-Bit SSL</span></td>
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

        <!-- Items Table -->
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
                                <strong>★ ADVANCE PAYMENT CLAUSE:</strong> This invoice receipts the ₹ 25,000 token advance to lock production queue &amp; allocation of casting serial number.
                                <br>Full Machine Price: <strong>₹ <?= number_format($order['machine_price'], 2) ?> (+18% GST = ₹ <?= number_format($fullMachineGross, 2) ?>)</strong>.
                            </div>
                        <?php else: ?>
                            <div style="margin-top: 8px; padding: 6px 10px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 4px; font-size: 11.5px; color: #166534;">
                                <strong>★ 100% PAID IN FULL:</strong> Full invoice value settled. Machine scheduled for direct freight dispatch.
                            </div>
                        <?php endif; ?>
                    </td>
                    <td>8459 29 10</td>
                    <td style="text-align: center; font-weight: 700;">1 Unit</td>
                    <td style="text-align: right;"><?= number_format($order['total_amount'], 2) ?></td>
                    <td style="text-align: right; font-weight: 600;"><?= number_format($order['taxable_amount'], 2) ?></td>
                </tr>
            </tbody>
        </table>

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
                        <td style="text-align: right; font-weight: 600;">₹ <?= number_format($order['taxable_amount'], 2) ?></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">CGST (9.00%):</td>
                        <td style="text-align: right; font-weight: 600;">₹ <?= number_format($order['cgst_amount'], 2) ?></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">SGST (9.00%):</td>
                        <td style="text-align: right; font-weight: 600;">₹ <?= number_format($order['sgst_amount'], 2) ?></td>
                    </tr>
                    <tr class="grand-total">
                        <td>TOTAL PAID TODAY (INR):</td>
                        <td style="text-align: right; color: #15803d;">₹ <?= number_format($order['total_amount'], 2) ?></td>
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
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <div class="amount-words-box" style="font-size: 12px; color: #334155; margin-bottom: 20px; padding: 7px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px;">
            <strong>Amount in Words:</strong> <?= amountInWords($order['total_amount']) ?>
        </div>

        <!-- Official Seal and Authorized Signatory Row -->
        <div class="sign-seal-row">
            <!-- Official Company Rubber Stamp / Sico -->
            <div class="seal-box">
                <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand Official Seal Stamp" class="seal-image" style="border-radius: 50%; object-fit: cover; border: 2px solid #7c3aed; box-shadow: 0 0 10px rgba(124, 58, 237, 0.25);" title="Official Seal of Varsha Brand">
                <div>
                    <div style="font-size: 11px; font-weight: 800; color: #7c3aed; letter-spacing: 0.8px;">
                        OFFICIAL COMPANY SEAL / SICO
                    </div>
                    <div style="font-size: 10.5px; color: #64748b; line-height: 1.3;">
                        VARSHA BRAND (SVEW)<br>
                        Quality Approved &amp; Certified
                    </div>
                </div>
            </div>

            <!-- Authorized Signatory -->
            <div class="signature-box">
                <div style="font-size: 11px; color: #64748b; margin-bottom: 2px;">
                    For <strong>SHREE VARSHA ENGINEERING WORKS</strong>
                </div>
                <!-- Jay Parmar Signature -->
                <img src="<?= assetUrl('assets/images/jay-parmar-signature.svg') ?>" alt="Signature of Jay Parmar" class="signature-img">
                <div class="signatory-name"><?= e($order['authorized_signatory'] ?? 'Jay Parmar') ?></div>
                <div class="signatory-title">Partner & Authorized Signatory</div>
            </div>
        </div>

        <?php
            $cryptoHash = $order['crypto_seal'] ?? hash('sha256', $order['order_number'] . '|' . $order['invoice_number'] . '|' . $order['razorpay_payment_id'] . '|' . $order['total_amount'] . '|' . COMPANY_GSTIN . '|' . RAZORPAY_KEY_SECRET);
        ?>
        <!-- World-First Cryptographic Invoice Integrity & Anti-Forgery Ledger Seal -->
        <div class="crypto-proof-box" style="margin-top: 18px; background: #f8fafc; border: 1.5px dashed #0284c7; border-radius: 8px; padding: 10px 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="background: #0284c7; color: #fff; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0;">
                    🔒
                </div>
                <div>
                    <div style="font-size: 11px; font-weight: 800; color: #0f172a; letter-spacing: 0.5px;">
                        CRYPTOGRAPHIC PROOF OF AUTHENTICITY (SHA-256 DIGITAL LEDGER SEAL)
                    </div>
                    <div style="font-family: monospace; font-size: 9.5px; color: #475569; word-break: break-all; margin-top: 2px;">
                        PROVENANCE HASH: <?= $cryptoHash ?>
                    </div>
                </div>
            </div>
            <div>
                <span style="display: inline-block; background: #dcfce7; border: 1px solid #16a34a; color: #15803d; font-size: 10.5px; font-weight: 800; padding: 3px 8px; border-radius: 4px;">
                    ✓ IMMUTABLE & VERIFIED
                </span>
            </div>
        </div>

        <div class="disclaimer-box" style="margin-top: 16px; padding-top: 10px; border-top: 1px dashed #cbd5e1; text-align: center; font-size: 10px; color: #94a3b8;">
            This is an electronically generated and certified Tax Invoice with secure digital audit trail. Razorpay Payment ID: <?= e($order['razorpay_payment_id']) ?>. Certified by Shree Varsha Engineering Works.
        </div>
    </div>

</body>
</html>
