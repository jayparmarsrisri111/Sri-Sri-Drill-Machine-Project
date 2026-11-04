<?php
/**
 * Industrial Drill Machine Technical Catalog & Authentic Datasheets
 * Shree Varsha Engineering Works - Varsha Brand (Since 1998, Bhavnagar)
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$pdo = Database::getConnection();

// Fetch all products
$products = [];
try {
    $products = $pdo->query("
        SELECT p.*, c.name as category_name, c.slug as category_slug 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        WHERE p.is_active = 1 
        ORDER BY c.display_order ASC, p.id ASC
    ")->fetchAll();
} catch (Exception $e) {
    //
}

$pageTitle = 'Industrial Drill Machine Catalog & Datasheets 2026 | ' . BRAND_NAME;
$pageDesc = 'Authentic technical specification datasheets for Varsha Brand Radial Drills, Pillar Drills, Bench Drills and Industrial Machinery. Manufactured in Bhavnagar, Gujarat.';
$currentNav = 'catalog';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Helper function to return full 15-point specification array for authentic datasheet layout (Image 4 format)
function getMachineDatasheetSpecs($prod) {
    $model = strtoupper($prod['model_number'] ?? '');
    
    if (strpos($model, 'RD-40') !== false) {
        return [
            ['no' => 1,  'spec' => 'Drill capacity in steel',                     'val' => '40 mm'],
            ['no' => 2,  'spec' => 'No. of spindle speed / range',               'val' => '8 Speeds (50 - 1500 RPM)'],
            ['no' => 3,  'spec' => 'Spindle nose taper',                         'val' => 'MT-4'],
            ['no' => 4,  'spec' => 'Spindle center of column (Arm Radius)',       'val' => '1000 mm'],
            ['no' => 5,  'spec' => 'Spindle travel',                             'val' => '250 mm'],
            ['no' => 6,  'spec' => 'Maximum distance between spindle to table',  'val' => '950 mm'],
            ['no' => 7,  'spec' => 'Maximum distance between spindle to base',   'val' => '1250 mm'],
            ['no' => 8,  'spec' => 'Working surface of table',                   'val' => '600 x 500 x 380 mm'],
            ['no' => 9,  'spec' => 'Working surface of base',                    'val' => '1250 x 750 mm'],
            ['no' => 10, 'spec' => 'Column diameter',                            'val' => '210 mm'],
            ['no' => 11, 'spec' => 'Base of Machine',                            'val' => '1750 x 850 x 200 mm'],
            ['no' => 12, 'spec' => 'Overall dimension of M/c',                   'val' => '1850 x 950 x 2250 mm'],
            ['no' => 13, 'spec' => 'Motor Speed',                                'val' => '1440 RPM'],
            ['no' => 14, 'spec' => 'Motor 3 Phase Induction A.C.',                'val' => '3.0 H.P. (Elevating: 1.5 H.P.)'],
            ['no' => 15, 'spec' => 'Weight of Machine (Approx)',                 'val' => '1850 Kg']
        ];
    } elseif (strpos($model, 'RD-50') !== false) {
        return [
            ['no' => 1,  'spec' => 'Drill capacity in steel',                     'val' => '50 mm'],
            ['no' => 2,  'spec' => 'No. of spindle speed / range',               'val' => '8 Speeds (40 - 1450 RPM)'],
            ['no' => 3,  'spec' => 'Spindle nose taper',                         'val' => 'MT-5'],
            ['no' => 4,  'spec' => 'Spindle center of column (Arm Radius)',       'val' => '1250 mm'],
            ['no' => 5,  'spec' => 'Spindle travel',                             'val' => '280 mm'],
            ['no' => 6,  'spec' => 'Maximum distance between spindle to table',  'val' => '1100 mm'],
            ['no' => 7,  'spec' => 'Maximum distance between spindle to base',   'val' => '1350 mm'],
            ['no' => 8,  'spec' => 'Working surface of table',                   'val' => '700 x 550 x 420 mm'],
            ['no' => 9,  'spec' => 'Working surface of base',                    'val' => '1450 x 850 mm'],
            ['no' => 10, 'spec' => 'Column diameter',                            'val' => '250 mm'],
            ['no' => 11, 'spec' => 'Base of Machine',                            'val' => '1950 x 900 x 220 mm'],
            ['no' => 12, 'spec' => 'Overall dimension of M/c',                   'val' => '2050 x 1050 x 2450 mm'],
            ['no' => 13, 'spec' => 'Motor Speed',                                'val' => '1440 RPM'],
            ['no' => 14, 'spec' => 'Motor 3 Phase Induction A.C.',                'val' => '5.0 H.P. (Elevating: 2.0 H.P.)'],
            ['no' => 15, 'spec' => 'Weight of Machine (Approx)',                 'val' => '2600 Kg']
        ];
    } elseif (strpos($model, 'RD-65') !== false) {
        return [
            ['no' => 1,  'spec' => 'Drill capacity in steel',                     'val' => '65 mm'],
            ['no' => 2,  'spec' => 'No. of spindle speed / range',               'val' => '12 Speeds (25 - 1200 RPM)'],
            ['no' => 3,  'spec' => 'Spindle nose taper',                         'val' => 'MT-5'],
            ['no' => 4,  'spec' => 'Spindle center of column (Arm Radius)',       'val' => '1600 mm'],
            ['no' => 5,  'spec' => 'Spindle travel',                             'val' => '320 mm'],
            ['no' => 6,  'spec' => 'Maximum distance between spindle to table',  'val' => '1250 mm'],
            ['no' => 7,  'spec' => 'Maximum distance between spindle to base',   'val' => '1550 mm'],
            ['no' => 8,  'spec' => 'Working surface of table',                   'val' => '800 x 650 x 450 mm'],
            ['no' => 9,  'spec' => 'Working surface of base',                    'val' => '1850 x 950 mm'],
            ['no' => 10, 'spec' => 'Column diameter',                            'val' => '300 mm'],
            ['no' => 11, 'spec' => 'Base of Machine',                            'val' => '2300 x 1050 x 250 mm'],
            ['no' => 12, 'spec' => 'Overall dimension of M/c',                   'val' => '2450 x 1200 x 2750 mm'],
            ['no' => 13, 'spec' => 'Motor Speed',                                'val' => '1440 RPM'],
            ['no' => 14, 'spec' => 'Motor 3 Phase Induction A.C.',                'val' => '7.5 H.P. (Elevating: 2.0 H.P.)'],
            ['no' => 15, 'spec' => 'Weight of Machine (Approx)',                 'val' => '3800 Kg']
        ];
    } elseif (strpos($model, 'PD-32') !== false || strpos($model, 'PILLAR') !== false) {
        return [
            ['no' => 1,  'spec' => 'Drill capacity in steel',                     'val' => '32 mm'],
            ['no' => 2,  'spec' => 'No. of spindle speed / range',               'val' => '8 Speeds (80 - 2800 RPM)'],
            ['no' => 3,  'spec' => 'Spindle nose taper',                         'val' => 'MT-3'],
            ['no' => 4,  'spec' => 'Spindle center of column',                   'val' => '300 mm'],
            ['no' => 5,  'spec' => 'Spindle travel',                             'val' => '160 mm'],
            ['no' => 6,  'spec' => 'Maximum distance between spindle to table',  'val' => '680 mm'],
            ['no' => 7,  'spec' => 'Maximum distance between spindle to base',   'val' => '1050 mm'],
            ['no' => 8,  'spec' => 'Working surface of table',                   'val' => '380 x 380 mm'],
            ['no' => 9,  'spec' => 'Working surface of base',                    'val' => '450 x 350 mm'],
            ['no' => 10, 'spec' => 'Column diameter',                            'val' => '102 mm'],
            ['no' => 11, 'spec' => 'Base of Machine',                            'val' => '650 x 420 mm'],
            ['no' => 12, 'spec' => 'Overall dimension of M/c',                   'val' => '850 x 550 x 1750 mm'],
            ['no' => 13, 'spec' => 'Motor Speed',                                'val' => '1440 RPM'],
            ['no' => 14, 'spec' => 'Motor 3 Phase Induction A.C.',                'val' => '2.0 H.P.'],
            ['no' => 15, 'spec' => 'Weight of Machine (Approx)',                 'val' => '320 Kg']
        ];
    } elseif (strpos($model, 'BD-20') !== false || strpos($model, 'BENCH') !== false || strpos($model, '20') !== false) {
        return [
            ['no' => 1,  'spec' => 'Drill capacity in steel',                     'val' => '20 mm'],
            ['no' => 2,  'spec' => 'No. of spindle speed',                       'val' => '82-154-260-460 / 617-1100-1880-3360'],
            ['no' => 3,  'spec' => 'Spindle nose taper',                         'val' => 'MT-2'],
            ['no' => 4,  'spec' => 'Spindle center of column',                   'val' => '205 mm'],
            ['no' => 5,  'spec' => 'Spindle travel',                             'val' => '130 mm'],
            ['no' => 6,  'spec' => 'Maximum distance between spindle to table',  'val' => '570 mm'],
            ['no' => 7,  'spec' => 'Maximum distance between spindle to base',   'val' => '820 mm'],
            ['no' => 8,  'spec' => 'Working surface of table',                   'val' => '268 x 275 mm'],
            ['no' => 9,  'spec' => 'Working surface of base',                    'val' => '245 x 275 mm'],
            ['no' => 10, 'spec' => 'Column diameter',                            'val' => '73.8 mm'],
            ['no' => 11, 'spec' => 'Base of Machine',                            'val' => '480 x 315 mm'],
            ['no' => 12, 'spec' => 'Overall dimension of M/c',                   'val' => '1280 x 760 x 400 mm'],
            ['no' => 13, 'spec' => 'Motor Speed',                                'val' => '1440 RPM'],
            ['no' => 14, 'spec' => 'Motor 3 Phase Induction A.C.',                'val' => '1.0 H.P.'],
            ['no' => 15, 'spec' => 'Weight of Machine (Approx)',                 'val' => '100 Kg']
        ];
    } else {
        // Generic part or machine
        return [
            ['no' => 1,  'spec' => 'Item Category / Application',               'val' => $prod['category_name']],
            ['no' => 2,  'spec' => 'Part Model / Specification',                'val' => $prod['model_number']],
            ['no' => 3,  'spec' => 'Material Grade',                             'val' => 'EN-8 / 20MnCr5 Alloy Forged'],
            ['no' => 4,  'spec' => 'Heat Treatment Hardness',                   'val' => '58 - 62 HRC Case Hardened'],
            ['no' => 5,  'spec' => 'Spindle Compatibility',                     'val' => 'Standard MT-2, MT-3, MT-4, MT-5'],
            ['no' => 6,  'spec' => 'Run-Out Accuracy',                           'val' => '< 0.008 mm Micro Ground'],
            ['no' => 7,  'spec' => 'Lubrication Requirement',                   'val' => 'Industrial EP-90 Gear Oil'],
            ['no' => 8,  'spec' => 'Approx Net Weight',                         'val' => $prod['weight_approx'] ?? '12 Kg']
        ];
    }
}
?>

<style>
/* ==========================================================================
   AUTHENTIC INDUSTRIAL DATASHEET SPECIFICATION STYLES (Image 4 Format)
   ========================================================================== */
.catalog-hero-strip {
    padding: 35px 0 25px;
    background: radial-gradient(circle at 50% 20%, rgba(0, 229, 255, 0.12) 0%, rgba(6, 9, 17, 0.98) 70%);
    border-bottom: 1px solid var(--border-subtle);
    text-align: center;
}

.datasheets-wrapper {
    display: flex;
    flex-direction: column;
    gap: 50px;
    margin: 40px 0 60px;
}

/* Individual Authentic Industrial Specification Sheet */
.industrial-datasheet-card {
    background: #fbf9f4; /* Authentic industrial cream/off-white parchment tone */
    border: 3px solid #1e293b;
    border-radius: 6px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6);
    overflow: hidden;
    color: #111827;
    position: relative;
    page-break-after: always;
    break-inside: avoid;
}

/* Top Header Bar */
.datasheet-header {
    padding: 20px 28px 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #e5e7eb;
    background: #fff;
}

.datasheet-title-group h2 {
    font-family: 'Space Grotesk', 'Arial Black', sans-serif;
    font-size: 38px;
    font-weight: 900;
    color: #991b1b; /* Authentic industrial dark red */
    line-height: 1.0;
    margin: 0;
    letter-spacing: -0.5px;
}

.datasheet-title-group .sub-type {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 20px;
    font-weight: 700;
    color: #1f2937;
    margin-top: 3px;
}

/* Official Manufacturer Premium Industrial Badge */
.datasheet-emblem {
    display: inline-flex !important;
    flex-direction: row !important;
    align-items: center !important;
    gap: 14px !important;
    border: 1.5px solid #dc2626 !important;
    border-radius: 12px !important;
    padding: 8px 18px 8px 10px !important;
    background: #ffffff !important;
    text-align: left !important;
    box-shadow: 0 4px 16px rgba(220, 38, 38, 0.1) !important;
    white-space: nowrap !important;
    flex-shrink: 0 !important;
}
.datasheet-emblem .emblem-logo-wrap {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #060911;
    border: 2px solid #dc2626;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.datasheet-emblem .emblem-logo-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.datasheet-emblem .brand-text {
    font-family: 'Space Grotesk', sans-serif !important;
    font-size: 19px !important;
    font-weight: 900 !important;
    color: #b91c1c !important;
    letter-spacing: 1.5px !important;
    line-height: 1.15 !important;
    margin: 0 !important;
}
.datasheet-emblem .city-text {
    font-size: 10.5px !important;
    font-weight: 800 !important;
    color: #1e293b !important;
    letter-spacing: 2px !important;
    line-height: 1.2 !important;
    margin-top: 1px !important;
}
.datasheet-emblem .since-text {
    font-size: 10px !important;
    font-weight: 600 !important;
    color: #64748b !important;
    margin-top: 2px !important;
    letter-spacing: 0.3px !important;
}

/* 2-Column Main Content (Specs on Left, Machine Photo on Right) */
.datasheet-body-grid {
    display: grid;
    grid-template-columns: 1.25fr 0.95fr;
    gap: 25px;
    padding: 24px 28px;
    align-items: center;
}

@media (max-width: 900px) {
    .datasheet-body-grid {
        grid-template-columns: 1fr;
    }
}

/* Numbered Specification Table (Matching Image 4) */
.datasheet-spec-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12.5px;
    border: 2px solid #111827;
    background: #fff;
}
.datasheet-spec-table th {
    background: #111827;
    color: #ffffff;
    font-family: 'Space Grotesk', sans-serif;
    font-weight: 800;
    font-size: 13px;
    padding: 7px 10px;
    text-align: left;
    border: 1px solid #111827;
}
.datasheet-spec-table td {
    padding: 5.5px 10px;
    border: 1px solid #9ca3af;
    color: #111827;
    vertical-align: middle;
}
.datasheet-spec-table tr:nth-child(even) {
    background: #f9fafb;
}
.datasheet-spec-table .col-no {
    width: 38px;
    font-weight: 800;
    text-align: center;
}
.datasheet-spec-table .col-spec {
    font-weight: 600;
}
.datasheet-spec-table .col-val {
    font-weight: 700;
    text-align: right;
    color: #111827;
    white-space: nowrap;
}

/* Right Machine Photograph Presentation */
.datasheet-photo-wrap {
    text-align: center;
    padding: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
}
.datasheet-photo-wrap img {
    max-height: 480px;
    width: 100%;
    object-fit: contain;
    filter: drop-shadow(0 15px 25px rgba(0,0,0,0.35));
    transition: transform 0.3s ease;
}
.datasheet-photo-wrap:hover img {
    transform: scale(1.02);
}

/* Price & Warranty Pill */
.datasheet-meta-strip {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 28px;
    background: #f3f4f6;
    border-top: 1px solid #e5e7eb;
}

/* Solid Crimson Manufacturer Footer Bar (Image 4 exact) */
.datasheet-footer-bar {
    background: #991b1b; /* Solid dark red / crimson */
    color: #ffffff;
    padding: 12px 28px;
    font-family: 'Space Grotesk', sans-serif;
    font-size: 15px;
    font-weight: 700;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}
.datasheet-footer-bar strong {
    font-size: 16px;
    letter-spacing: 0.5px;
}

/* Actions Toolbar beneath each datasheet */
.datasheet-actions-bar {
    background: #090d16;
    border: 1px solid rgba(0, 229, 255, 0.3);
    border-radius: 8px;
    padding: 12px 20px;
    margin-top: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

/* Print CSS */
@media print {
    .site-header, .navbar, .site-footer, .floating-contact-bar, .catalog-hero-strip, .filter-tabs, .datasheet-actions-bar, #catalogSearchInput {
        display: none !important;
    }
    body {
        background: #fff !important;
        color: #000 !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    .industrial-datasheet-card {
        border: 2px solid #000 !important;
        box-shadow: none !important;
        margin-bottom: 20px !important;
        page-break-after: always !important;
    }
    .datasheet-footer-bar {
        background: #000 !important;
        color: #fff !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>

<!-- Catalog Header Strip (Aligned with Industrial Design System) -->
<section class="catalog-hero-strip" style="padding: 55px 0 35px; background: radial-gradient(circle at 50% 20%, rgba(0, 229, 255, 0.1) 0%, rgba(6, 9, 17, 0.98) 75%); border-bottom: 1px solid var(--border-subtle); text-align: center;">
    <div class="container" style="text-align: center;">
        <div style="display: flex; align-items: center; justify-content: center; gap: 14px; margin-bottom: 16px; flex-wrap: wrap;">
            <div style="width: 58px; height: 58px; border-radius: 50%; border: 2px solid var(--accent-cyan); box-shadow: 0 0 22px rgba(0, 229, 255, 0.45); background: #060911; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand Emblem" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div class="pill-badge" style="margin-bottom: 0; display: inline-flex; align-items: center; gap: 8px; border: 1.5px solid rgba(0, 229, 255, 0.4); background: rgba(15, 23, 42, 0.85); border-radius: 9999px; padding: 7px 18px; box-shadow: 0 0 20px rgba(0, 229, 255, 0.15);">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--accent-cyan); display: inline-block; box-shadow: 0 0 8px var(--accent-cyan);"></span>
                <span style="font-family: 'Space Grotesk', sans-serif; font-size: 12px; font-weight: 800; color: #ffffff; letter-spacing: 1px;">
                    OFFICIAL 2026 CATALOG • <span style="color: var(--accent-cyan);">TECHNICAL DATASHEETS</span>
                </span>
            </div>
        </div>

        <h1 class="section-title">Industrial Drill Machine <span>Technical Datasheets</span></h1>
        <p class="section-desc" style="max-width: 740px; margin: 0 auto; color: #cbd5e1; font-size: 15px; line-height: 1.6;">
            Authentic engineering specification sheets for high-torque All-Geared Radial Drills, Pillar Drills, Bench Drills, and Spares. Manufactured at Bhavnagar, Gujarat.
        </p>

        <div style="display: flex; gap: 12px; justify-content: center; margin-top: 20px; flex-wrap: wrap;">
            <button onclick="window.print()" class="btn btn-primary" style="background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); font-weight: 800;">
                🖨️ Print / Download Complete Datasheets (PDF)
            </button>
            <a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399; font-weight: 800;">
                💳 Book Machine Online (₹25,000 Token Advance)
            </a>
            <a href="<?= getWhatsAppLink(COMPANY_WHATSAPP, 'Hello Shree Varsha Engineering Works Bhavnagar, please send me your official machinery catalog & pricelist.') ?>" target="_blank" class="btn btn-whatsapp" style="font-weight: 700;">
                💬 WhatsApp PDF Catalog
            </a>
        </div>
    </div>
</section>

<!-- Datasheets Section -->
<section class="section" style="padding-top: 30px;">
    <div class="container">
        <!-- Search and Quick Category Filter -->
        <div style="max-width: 650px; margin: 0 auto 24px; text-align: center;">
            <input type="text" id="catalogSearchInput" class="form-control" 
                   placeholder="🔍 Search datasheets by model (e.g. RD-40, RD-50, PD-32, 20 PFR) or capacity..." 
                   style="padding: 12px 20px; font-size: 14px; border-radius: 9999px; background: rgba(15, 23, 42, 0.95); border-color: var(--accent-cyan); box-shadow: 0 4px 20px rgba(0, 229, 255, 0.15);">
        </div>

        <div class="filter-tabs" style="margin-bottom: 30px; justify-content: center;">
            <button class="filter-btn active catalog-filter-btn" data-type="all">
                All Datasheets (<?= count($products) ?>)
            </button>
            <button class="filter-btn catalog-filter-btn" data-type="radial">
                Radial Drills (40mm - 65mm)
            </button>
            <button class="filter-btn catalog-filter-btn" data-type="pillar">
                Pillar &amp; Bench Drills
            </button>
            <button class="filter-btn catalog-filter-btn" data-type="part" style="border-color: #f59e0b; color: #fbbf24;">
                ⚙️ Genuine Spare Parts
            </button>
        </div>

        <!-- Authentic Datasheets Stack -->
        <div class="datasheets-wrapper" id="datasheetsList">
            <?php foreach ($products as $prod): 
                $isPart = (($prod['product_type'] ?? 'machine') === 'part');
                $specs = getMachineDatasheetSpecs($prod);
                $categorySlug = $prod['category_slug'] ?? '';
                
                // Categorize for filtering
                $filterGroup = 'radial';
                if ($isPart) {
                    $filterGroup = 'part';
                } elseif (strpos($prod['model_number'], 'PD') !== false || strpos($prod['model_number'], 'BD') !== false || strpos($categorySlug, 'pillar') !== false || strpos($categorySlug, 'bench') !== false) {
                    $filterGroup = 'pillar';
                }
            ?>
                <div class="datasheet-item" data-group="<?= $filterGroup ?>" data-search="<?= strtolower(e($prod['name'] . ' ' . $prod['model_number'] . ' ' . $prod['drilling_capacity'] . ' ' . $prod['category_name'])) ?>">
                    <!-- Industrial Datasheet Card (Image 4 Format) -->
                    <div class="industrial-datasheet-card">
                        <!-- Top Header Bar -->
                        <div class="datasheet-header">
                            <div class="datasheet-title-group">
                                <h2><?= e($prod['model_number']) ?></h2>
                                <div class="sub-type">
                                    <?= e($prod['name']) ?>
                                </div>
                            </div>
                            
                            <!-- Official Manufacturer Emblem with Brand Logo -->
                            <div class="datasheet-emblem">
                                <div class="emblem-logo-wrap">
                                    <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand Logo">
                                </div>
                                <div>
                                    <div class="brand-text">VARSHA</div>
                                    <div class="city-text">BHAVNAGAR ®</div>
                                    <div class="since-text">Since 1998 • Regd. Mfr.</div>
                                </div>
                            </div>
                        </div>

                        <!-- 2-Column Content: Left Specs Table + Right Clean Photo -->
                        <div class="datasheet-body-grid">
                            <!-- Left: Numbered 1 to 15 Specs Table -->
                            <div>
                                <table class="datasheet-spec-table">
                                    <thead>
                                        <tr>
                                            <th class="col-no">No.</th>
                                            <th class="col-spec">Specification</th>
                                            <th class="col-val" style="text-align: right;">Capacity / Metrics</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($specs as $s): ?>
                                            <tr>
                                                <td class="col-no"><?= $s['no'] ?>.</td>
                                                <td class="col-spec"><?= e($s['spec']) ?></td>
                                                <td class="col-val"><?= e($s['val']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Right: Machine Photo -->
                            <div class="datasheet-photo-wrap">
                                <img src="<?= assetUrl($prod['image_url']) ?>" alt="<?= e($prod['name']) ?>">
                                <div style="margin-top: 10px; font-size: 11px; font-weight: 700; color: #4b5563;">
                                    High-Tensile Graded Cast Iron Construction • Precision Hardened &amp; Ground Alloy Spindle
                                </div>
                            </div>
                        </div>

                        <!-- Price & Stock Strip -->
                        <div class="datasheet-meta-strip">
                            <div>
                                <span style="font-size: 11px; text-transform: uppercase; color: #6b7280; font-weight: 700;">Ex-Factory Base Price:</span>
                                <strong style="font-size: 18px; color: #047857; margin-left: 6px;">₹ <?= number_format($prod['price'] ?? getMachinePrice($prod['id']), 2) ?></strong>
                                <small style="color: #6b7280; font-size: 11.5px;">(+ 18% GST)</small>
                            </div>
                            <div>
                                <span style="font-size: 11.5px; font-weight: 700; color: #047857; background: rgba(16, 185, 129, 0.15); padding: 4px 10px; border-radius: 9999px; border: 1px solid #10b981;">
                                    ✔ Factory Ready: <?= intval($prod['stock_quantity'] ?? 5) ?> Units in Stock
                                </span>
                            </div>
                        </div>

                        <!-- Solid Crimson Manufacturer Footer Bar -->
                        <div class="datasheet-footer-bar">
                            <div>
                                Mfg. By: <strong>SHREE VARSHA ENGINEERING WORKS</strong>, <?= COMPANY_ADDRESS ?>
                            </div>
                            <div>
                                GSTIN: <strong><?= COMPANY_GSTIN ?></strong> | Ph: <strong><?= COMPANY_PHONE_1 ?></strong>
                            </div>
                        </div>
                    </div>

                    <!-- Action Bar Beneath Each Datasheet -->
                    <div class="datasheet-actions-bar">
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <button type="button" class="btn-360-turntable open-360-modal" 
                                    data-machine-name="<?= e($prod['name']) ?> (<?= e($prod['model_number']) ?>)" 
                                    data-machine-img="<?= assetUrl($prod['image_url']) ?>" 
                                    style="height: 38px; padding: 0 16px; font-size: 11.5px;">
                                <span class="turntable-spin-icon">🔄</span>
                                <span>360° Machine View</span>
                                <span class="turntable-step-pill">30° STEP</span>
                            </button>
                            <a href="<?= BASE_URL ?>/machine-detail.php?id=<?= $prod['id'] ?>" class="btn btn-outline" style="font-size: 12.5px;">
                                🔍 In-Depth Details &amp; Video
                            </a>
                        </div>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <button onclick="window.print()" class="btn btn-outline" style="font-size: 12.5px;" title="Print this specification sheet">
                                🖨️ Print Datasheet
                            </button>
                            <a href="<?= BASE_URL ?>/checkout.php?product_id=<?= $prod['id'] ?>" class="btn btn-primary" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399; font-weight: 800; font-size: 13px;">
                                💳 <?= $isPart ? 'Buy Spare Part' : 'Book Machine Online (Token ₹25,000)' ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('catalogSearchInput');
    const filterBtns = document.querySelectorAll('.catalog-filter-btn');
    const items = document.querySelectorAll('#datasheetsList .datasheet-item');

    let currentGroup = 'all';

    function runFilter() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';

        items.forEach(item => {
            const group = item.getAttribute('data-group');
            const searchStr = item.getAttribute('data-search') || '';

            const matchesGroup = (currentGroup === 'all' || group === currentGroup);
            const matchesQuery = (!query || searchStr.includes(query));

            if (matchesGroup && matchesQuery) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentGroup = this.getAttribute('data-type');
            runFilter();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', runFilter);
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
