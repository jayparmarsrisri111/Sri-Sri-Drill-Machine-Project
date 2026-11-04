<?php
/**
 * Engineering Blog & Technical Resources
 * Shree Varsha Engineering Works - Varsha Brand
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Engineering Blog & Drilling Resources | ' . BRAND_NAME . ' Bhavnagar';
$pageDesc = 'Technical guides, spindle speed charts, maintenance schedules, and machine selection tips from Shree Varsha Engineering Works.';
$currentNav = 'blog';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$articles = [
    [
        'id' => 1,
        'category' => 'machinery',
        'badge' => 'MACHINERY SELECTION',
        'badge_color' => '#00e5ff',
        'read_time' => '6 min read',
        'date' => 'October 28, 2026',
        'title' => 'How to Choose the Right Radial Drill Machine (40mm vs 50mm vs 65mm)',
        'excerpt' => 'A comprehensive engineering guide on column diameter sizing, arm reach, spindle torque, and heavy-duty fabrication requirements to choose the optimal drill machine for your workshop.',
        'author' => 'Jitendrabhai Parmar (Technical Director)',
        'icon' => '⚙️',
        'tags' => ['Radial Drills', 'Fabrication', 'Machine Sizing']
    ],
    [
        'id' => 2,
        'category' => 'speed_feeds',
        'badge' => 'CUTTING SPEEDS & FEEDS',
        'badge_color' => '#38bdf8',
        'read_time' => '8 min read',
        'date' => 'October 15, 2026',
        'title' => 'Optimal Spindle Speeds & Feed Rates for Mild Steel, EN8 & Cast Iron',
        'excerpt' => 'Complete speed chart and calculation formulas (RPM = Vc × 1000 / π × D) for high speed steel (HSS) and carbide tooling across varying drill bit diameters.',
        'author' => 'Jay Parmar (Production Head)',
        'icon' => '📊',
        'tags' => ['RPM Chart', 'Feed Rates', 'HSS Tools']
    ],
    [
        'id' => 3,
        'category' => 'metallurgy',
        'badge' => 'METALLURGY & DESIGN',
        'badge_color' => '#f59e0b',
        'read_time' => '5 min read',
        'date' => 'September 30, 2026',
        'title' => 'Why Seasoned Grade-25 Castings Prevent Machine Vibration & Chattering',
        'excerpt' => 'Understand the metallurgical significance of natural stress-relieving for 6+ months, pearlite grain structure, and high damping capacity in heavy drill machine castings.',
        'author' => 'SVEW Metallurgy Lab',
        'icon' => '🔬',
        'tags' => ['Grade 25 Casting', 'Stress Relief', 'Damping']
    ],
    [
        'id' => 4,
        'category' => 'maintenance',
        'badge' => 'PREVENTIVE MAINTENANCE',
        'badge_color' => '#10b981',
        'read_time' => '7 min read',
        'date' => 'September 12, 2026',
        'title' => 'Preventive Maintenance Checklist for All-Geared Radial Drill Machines',
        'excerpt' => 'Step-by-step daily, monthly, and annual maintenance protocols: gear oil change (ISO VG 68), spindle quill play adjustment, elevating lead screw inspection, and electrical brake checks.',
        'author' => 'Service & Support Dept.',
        'icon' => '🛠️',
        'tags' => ['Maintenance', 'Oil Replacement', 'Spindle Care']
    ],
    [
        'id' => 5,
        'category' => 'machinery',
        'badge' => 'THREADING & TAPPING',
        'badge_color' => '#ec4899',
        'read_time' => '6 min read',
        'date' => 'August 24, 2026',
        'title' => 'Selecting Tapping Attachments: Auto-Reverse Chucks vs Reversible Motors',
        'excerpt' => 'Comparison of mechanical auto-reverse tapping attachments versus electrical dual-switch motor reversal for high-volume blind hole and through-hole threading.',
        'author' => 'Tooling Division',
        'icon' => '🔩',
        'tags' => ['Tapping', 'Threading', 'Machine Tooling']
    ],
    [
        'id' => 6,
        'category' => 'maintenance',
        'badge' => 'INSTALLATION & COMMISSIONING',
        'badge_color' => '#a855f7',
        'read_time' => '5 min read',
        'date' => 'August 08, 2026',
        'title' => 'Foundation Bolting & Spirit Level Alignment for Zero Spindle Deflection',
        'excerpt' => 'How to prepare reinforced concrete machine foundations, position anti-vibration leveling mounts, and perform precision spirit level calibration to DIN 8625 standards.',
        'author' => 'Commissioning Team',
        'icon' => '📐',
        'tags' => ['Foundation', 'DIN 8625', 'Alignment']
    ]
];
?>

<style>
.blog-hero {
    padding: 60px 0 45px;
    background: radial-gradient(circle at 50% 20%, rgba(0, 229, 255, 0.12) 0%, rgba(15, 23, 42, 0.95) 60%, #060911 100%);
    border-bottom: 1px solid rgba(0, 229, 255, 0.2);
    text-align: center;
}
.blog-filter-bar {
    display: flex;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
    margin: 35px 0 20px;
}
.btn-filter {
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid var(--border-subtle);
    color: #94a3b8;
    padding: 8px 18px;
    border-radius: 9999px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}
.btn-filter:hover,
.btn-filter.active {
    background: rgba(0, 229, 255, 0.15);
    border-color: var(--accent-cyan);
    color: #fff;
    box-shadow: 0 0 15px rgba(0, 229, 255, 0.3);
}
.blog-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
    margin: 30px 0 60px;
}
@media (max-width: 992px) {
    .blog-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 640px) {
    .blog-grid {
        grid-template-columns: 1fr;
    }
}
.article-card {
    background: var(--bg-card);
    border: 1px solid var(--border-subtle);
    border-radius: 14px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
}
.article-card:hover {
    transform: translateY(-5px);
    border-color: var(--accent-cyan);
    box-shadow: 0 15px 35px rgba(0, 229, 255, 0.18);
}
.article-header {
    padding: 24px 24px 16px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    position: relative;
}
.article-body {
    padding: 20px 24px;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.article-title {
    font-family: var(--font-heading);
    font-size: 18px;
    font-weight: 700;
    color: #ffffff;
    line-height: 1.4;
    margin-bottom: 12px;
}
.article-desc {
    color: var(--text-secondary);
    font-size: 13.5px;
    line-height: 1.6;
    margin-bottom: 20px;
    flex: 1;
}
.article-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 14px;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    font-size: 12px;
    color: #94a3b8;
}
.reference-table-wrap {
    background: var(--bg-card);
    border: 1.5px solid var(--border-cyan);
    border-radius: 16px;
    padding: 32px;
    margin: 50px 0;
    box-shadow: var(--shadow-card);
}
.calc-box {
    background: rgba(15, 23, 42, 0.85);
    border: 1px solid var(--border-subtle);
    border-radius: 12px;
    padding: 24px;
}
</style>

<!-- Hero Section -->
<section class="blog-hero">
    <div class="container text-center">
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; margin-bottom: 16px;">
            <div style="display: inline-flex; align-items: center; gap: 12px; background: rgba(15, 23, 42, 0.85); border: 1.5px solid rgba(0, 229, 255, 0.4); padding: 6px 20px 6px 8px; border-radius: 9999px; box-shadow: 0 0 25px rgba(0, 229, 255, 0.2);">
                <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand" style="height: 42px; width: 42px; border-radius: 50%; border: 1.5px solid var(--accent-cyan); object-fit: cover;">
                <span style="font-family: 'Space Grotesk', sans-serif; font-size: 13px; font-weight: 800; color: #ffffff; letter-spacing: 0.8px;">
                    ENGINEERING KNOWLEDGE • <span style="color: var(--accent-cyan);">TECHNICAL GUIDES</span>
                </span>
            </div>
        </div>
        <h1 style="font-family: var(--font-heading); font-size: 36px; font-weight: 800; color: #fff; margin-bottom: 12px; letter-spacing: -0.5px;">
            Industrial Drilling Resources &amp; <span style="background: linear-gradient(135deg, #00e5ff 0%, #38bdf8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Technical Blog</span>
        </h1>
        <p style="color: #cbd5e1; font-size: 16px; max-width: 760px; margin: 0 auto; line-height: 1.6;">
            Practical machining insights, spindle speed calculations, preventive maintenance schedules, and machine selection guidelines directly from <strong>Shree Varsha Engineering Works</strong>, Bhavnagar.
        </p>

        <!-- Category Filters -->
        <div class="blog-filter-bar">
            <button type="button" class="btn-filter active" onclick="filterArticles('all')">All Articles (<?= count($articles) ?>)</button>
            <button type="button" class="btn-filter" onclick="filterArticles('machinery')">Machine Selection</button>
            <button type="button" class="btn-filter" onclick="filterArticles('speed_feeds')">Speeds &amp; Feeds</button>
            <button type="button" class="btn-filter" onclick="filterArticles('maintenance')">Maintenance &amp; Upkeep</button>
            <button type="button" class="btn-filter" onclick="filterArticles('metallurgy')">Castings &amp; Metallurgy</button>
        </div>
    </div>
</section>

<!-- Blog Articles Grid -->
<div class="container">
    <div class="blog-grid" id="articlesContainer">
        <?php foreach ($articles as $art): ?>
            <div class="article-card" data-category="<?= $art['category'] ?>">
                <div class="article-header">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <span style="font-size: 11px; font-weight: 800; letter-spacing: 0.8px; padding: 4px 10px; border-radius: 6px; background: rgba(255,255,255,0.06); color: <?= $art['badge_color'] ?>; border: 1px solid <?= $art['badge_color'] ?>40;">
                            <?= $art['badge'] ?>
                        </span>
                        <span style="font-size: 12px; color: #64748b;"><?= $art['read_time'] ?></span>
                    </div>
                    <div style="font-size: 38px; margin: 10px auto 8px; text-align: center; display: flex; align-items: center; justify-content: center;"><?= $art['icon'] ?></div>
                    <h3 class="article-title"><?= $art['title'] ?></h3>
                </div>
                <div class="article-body">
                    <p class="article-desc"><?= $art['excerpt'] ?></p>
                    <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 14px;">
                        <?php foreach ($art['tags'] as $tg): ?>
                            <span style="font-size: 11px; color: #94a3b8; background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255,255,255,0.08); padding: 2px 8px; border-radius: 4px;">#<?= $tg ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="article-footer">
                        <span>✍️ <?= $art['author'] ?></span>
                        <span><?= $art['date'] ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Quick Engineering Reference Table -->
    <div class="reference-table-wrap">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 20px; margin-bottom: 24px;">
            <div>
                <div class="pill-badge" style="margin-bottom: 8px;">ENGINEERING FORMULAS &amp; DATA</div>
                <h2 style="font-family: var(--font-heading); font-size: 24px; font-weight: 800; color: #fff;">
                    Spindle Speed (RPM) Quick Calculation Formula
                </h2>
                <p style="color: #94a3b8; font-size: 14px; margin-top: 4px;">
                    Standard industrial formula for calculating spindle RPM on all-geared drill machines:
                </p>
            </div>
            <a href="<?= BASE_URL ?>/catalog.php" class="btn btn-outline" style="border-color: #38bdf8; color: #38bdf8; font-size: 13px;">
                📖 Download Complete Catalog &amp; Charts
            </a>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1.3fr; gap: 24px; align-items: center;">
            <div class="calc-box">
                <div style="font-size: 12px; color: #38bdf8; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">
                    FORMULA FOR SPINDLE REVOLUTIONS:
                </div>
                <div style="font-family: 'Space Grotesk', monospace; font-size: 22px; color: #fff; font-weight: 800; background: rgba(0,0,0,0.5); padding: 14px; border-radius: 8px; border: 1px solid var(--accent-cyan); text-align: center; margin-bottom: 12px;">
                    RPM = (Vc × 1000) / (π × D)
                </div>
                <div style="font-size: 12.5px; color: #cbd5e1; line-height: 1.6;">
                    <div><strong>Vc</strong> = Recommended Cutting Speed in meters per minute (m/min)</div>
                    <div><strong>D</strong> = Drill bit diameter in millimeters (mm)</div>
                    <div><strong>π</strong> = 3.14159 constant</div>
                </div>
            </div>

            <!-- Table of Common Cutting Speeds for HSS Drill Bits -->
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13px; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 1.5px solid var(--accent-cyan); color: #fff;">
                            <th style="padding: 10px;">Workpiece Material</th>
                            <th style="padding: 10px;">Cutting Speed Vc (m/min)</th>
                            <th style="padding: 10px;">Ø 25mm Drill RPM</th>
                            <th style="padding: 10px;">Recommended Coolant</th>
                        </tr>
                    </thead>
                    <tbody style="color: #cbd5e1;">
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                            <td style="padding: 10px; font-weight: 700; color: #fff;">Mild Steel (IS 2062)</td>
                            <td style="padding: 10px;">25 – 30 m/min</td>
                            <td style="padding: 10px; color: #38bdf8; font-weight: 700;">320 – 380 RPM</td>
                            <td style="padding: 10px;">Soluble Oil 1:20</td>
                        </tr>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                            <td style="padding: 10px; font-weight: 700; color: #fff;">Alloy Steel (EN8 / EN9)</td>
                            <td style="padding: 10px;">18 – 22 m/min</td>
                            <td style="padding: 10px; color: #38bdf8; font-weight: 700;">230 – 280 RPM</td>
                            <td style="padding: 10px;">EP Soluble Cutting Oil</td>
                        </tr>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                            <td style="padding: 10px; font-weight: 700; color: #fff;">Grey Cast Iron (FG 200)</td>
                            <td style="padding: 10px;">20 – 25 m/min</td>
                            <td style="padding: 10px; color: #38bdf8; font-weight: 700;">255 – 320 RPM</td>
                            <td style="padding: 10px;">Dry / Compressed Air</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; font-weight: 700; color: #fff;">Stainless Steel (SS 304)</td>
                            <td style="padding: 10px;">12 – 16 m/min</td>
                            <td style="padding: 10px; color: #38bdf8; font-weight: 700;">150 – 200 RPM</td>
                            <td style="padding: 10px;">Neat Cutting Oil with Sulphur</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Contact & Consultation CTA -->
    <div style="background: linear-gradient(135deg, rgba(2, 132, 199, 0.15) 0%, rgba(16, 185, 129, 0.15) 100%); border: 1.5px solid var(--accent-cyan); border-radius: 16px; padding: 36px; text-align: center; margin-bottom: 60px;">
        <h3 style="font-family: var(--font-heading); font-size: 26px; color: #fff; font-weight: 800; margin-bottom: 10px;">
            Need Technical Advice for Your Machining Project?
        </h3>
        <p style="color: #cbd5e1; font-size: 15px; max-width: 650px; margin: 0 auto 24px;">
            Speak directly with our senior manufacturing engineers in Bhavnagar for custom arm lengths, special speed configurations, and tooling recommendations.
        </p>
        <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
            <a href="tel:<?= COMPANY_PHONE_1_CLEAN ?>" class="btn btn-primary" style="font-weight: 700;">
                📞 Call Jitendrabhai Parmar: <?= COMPANY_PHONE_1 ?>
            </a>
            <a href="<?= BASE_URL ?>/contact.php" class="btn btn-outline" style="border-color: #38bdf8; color: #38bdf8; font-weight: 700;">
                ✉️ Send Technical Inquiry
            </a>
        </div>
    </div>
</div>

<script>
function filterArticles(category) {
    // Update active button
    const buttons = document.querySelectorAll('.btn-filter');
    buttons.forEach(btn => btn.classList.remove('active'));
    event.currentTarget.classList.add('active');

    // Filter article cards
    const cards = document.querySelectorAll('.article-card');
    cards.forEach(card => {
        if (category === 'all' || card.getAttribute('data-category') === category) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
