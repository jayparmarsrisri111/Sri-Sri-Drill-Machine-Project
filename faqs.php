<?php
/**
 * Frequently Asked Questions (FAQs) Page
 * Shree Varsha Engineering Works - Varsha Brand
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Frequently Asked Questions (FAQs) | ' . BRAND_NAME . ' Bhavnagar';
$pageDesc = 'Get answers to questions about booking Varsha Brand drill machines, ₹25,000 advance token payments, Amazon-style tracking, GST invoices, and warranty.';
$currentNav = 'faqs';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$faqGroups = [
    [
        'category' => 'booking_payments',
        'category_name' => 'Online Booking & Payments',
        'icon' => '💳',
        'faqs' => [
            [
                'q' => 'How does the ₹25,000 Advance Token Booking work on your website?',
                'a' => 'You can reserve any Varsha Brand drill machine directly by paying a standard <strong>₹25,000 advance token amount</strong> via Razorpay (UPI, Credit/Debit Card, Net Banking, or NEFT/RTGS). Once your payment succeeds, your production slot is immediately reserved at our Bhavnagar plant, and an official Computerized GST Tax Invoice with an Order ID and Invoice Number is generated automatically.'
            ],
            [
                'q' => 'Is online payment secure? What payment methods are accepted?',
                'a' => 'Yes, 100% secure. All payments are processed through <strong>Razorpay’s PCI-DSS Level-1 certified bank-grade payment gateway</strong> with 256-bit SSL encryption. We accept all major UPI apps (Google Pay, PhonePe, Paytm, BHIM), Net Banking across 50+ Indian banks, Visa/MasterCard/RuPay cards, and direct IMPS/NEFT/RTGS transfers.'
            ],
            [
                'q' => 'When is the remaining balance payable after paying the token amount?',
                'a' => 'The remaining balance (Factory Ex-Price + GST minus the ₹25,000 advance paid) is payable after machine assembly, quality inspection run-testing, and before dispatch, or against the Transport Lorry Receipt (LR) copy depending on your mutually agreed credit terms.'
            ],
            [
                'q' => 'Do you provide a formal GST Tax Invoice with the purchase?',
                'a' => 'Yes, absolutely. Shree Varsha Engineering Works is a registered manufacturer under GSTIN: <strong>' . COMPANY_GSTIN . '</strong>. Every machine booking produces an authentic GST Tax Invoice showing full breakdown of machine ex-price, 18% GST (CGST+SGST for Gujarat or IGST for other states), advance token paid, and balance due.'
            ]
        ]
    ],
    [
        'category' => 'tracking_delivery',
        'category_name' => 'Amazon-Style Order & Logistics Tracking',
        'icon' => '🚚',
        'faqs' => [
            [
                'q' => 'How can I track my machine delivery status in real-time?',
                'a' => 'You can visit our <a href="' . BASE_URL . '/track-order.php" style="color:#00e5ff; font-weight:700;">Live Order Tracking</a> page at any time and enter your Order Number (e.g. SVEW-ORD-...) or registered Mobile Number. You will see real-time updates matching Amazon-style logistics from chassis assembly and VCI wooden crating to freight carrier dispatch and local flatbed arrival.'
            ],
            [
                'q' => 'How are heavy drill machines packaged for long-distance transport?',
                'a' => 'Every machine is coated with heavy anti-rust oil, wrapped in sealed VCI (Vapour Corrosion Inhibitor) waterproof film, strapped with heavy tensile steel bands, and anchored inside heavy fumigated wooden crates with rubber shock dampeners to guarantee zero transit scratches or moisture damage.'
            ],
            [
                'q' => 'Which transport carriers do you partner with for pan-India delivery?',
                'a' => 'We partner with India’s leading industrial logistics carriers including VRL Logistics, TCI Freight, Safexpress, ARC, and specialized flatbed open trailers for rapid, insured delivery to your industrial workshop door.'
            ]
        ]
    ],
    [
        'category' => 'specs_quality',
        'category_name' => 'Machine Specifications & Customization',
        'icon' => '⚙️',
        'faqs' => [
            [
                'q' => 'What is the difference between All-Geared Radial Drills and Belt-Driven Drills?',
                'a' => 'All-Geared Radial Drill Machines (such as our SVEW-RD-40, RD-50, and RD-65) transmit power directly through case-hardened alloy steel gears bathed in an oil bath. This provides constant high torque even at low RPMs, zero belt slippage during deep drilling, automatic power feeds, and the ability to drill up to 65mm in solid steel.'
            ],
            [
                'q' => 'Can you customize the drill machine for specific workshop requirements?',
                'a' => 'Yes. As original machine tool manufacturers in Bhavnagar, we can customize spindle taper (MT-3, MT-4, MT-5), arm radius extensions, specialized tilting or universal box tables, coolant pump systems, and dual-speed multi-voltage electric motors.'
            ],
            [
                'q' => 'What casting quality do you use for machine columns, arms, and bases?',
                'a' => 'We exclusively use <strong>Grade-25 Grey Cast Iron</strong> naturally seasoned for 6 months. This relieves internal casting stresses and guarantees high vibrational damping so your drill bit maintains extreme perpendicularity without chatter.'
            ]
        ]
    ],
    [
        'category' => 'warranty_service',
        'category_name' => 'Warranty, Service & Spare Parts Guarantee',
        'icon' => '🛡️',
        'faqs' => [
            [
                'q' => 'What warranty is provided on Varsha Brand drill machines?',
                'a' => 'All Varsha Brand industrial drill machines come with a <strong>1-Year Comprehensive Manufacturer Warranty</strong> against any manufacturing defects in castings, gears, spindle quills, or elevating mechanisms.'
            ],
            [
                'q' => 'How easily can I procure replacement spare parts in the future?',
                'a' => 'As the original manufacturer, we maintain lifetime inventory of all replacement components (pinions, helical gears, elevating screws, spindle sleeves, brass nuts, and clutch plates) with express same-day courier dispatch directly from our Bhavnagar factory warehouse.'
            ],
            [
                'q' => 'Do you provide operator training and commissioning assistance?',
                'a' => 'Yes. Every machine is shipped with comprehensive installation manuals, electrical wiring schematics, and lubrication charts. Our technical engineering team provides video-call guided commissioning and can dispatch technicians on-site across India on request.'
            ]
        ]
    ],
    [
        'category' => 'factory_visit',
        'category_name' => 'Factory Visits & Live Demonstrations',
        'icon' => '🏭',
        'faqs' => [
            [
                'q' => 'Can I visit your Bhavnagar manufacturing facility before purchasing?',
                'a' => 'Yes! We warmly welcome industrial buyers, fabricators, and machinery dealers to visit our manufacturing plant at <strong>Shed No. L/786, GIDC, Nr. Hadanagar, Bhavnagar - 36400003, Gujarat</strong> to inspect our foundry, gear hobbing bays, and witness live cutting trials.'
            ],
            [
                'q' => 'Who should I contact to schedule a factory visit?',
                'a' => 'You can speak directly with our Technical Director, <strong>Jitendrabhai Parmar</strong> at <strong>+91 9824913555</strong> or our Production Engineer, <strong>Jay Parmar</strong> at <strong>+91 9714611331</strong> to arrange a convenient time and trial setup.'
            ]
        ]
    ]
];
?>

<style>
.faqs-hero {
    padding: 60px 0 45px;
    background: radial-gradient(circle at 50% 20%, rgba(0, 229, 255, 0.12) 0%, rgba(15, 23, 42, 0.95) 60%, #060911 100%);
    border-bottom: 1px solid rgba(0, 229, 255, 0.2);
    text-align: center;
}
.faq-search-box {
    max-width: 640px;
    margin: 25px auto 0;
    position: relative;
}
.faq-search-box input {
    width: 100%;
    background: rgba(11, 17, 32, 0.9);
    border: 1.5px solid var(--accent-cyan);
    border-radius: 12px;
    padding: 14px 20px 14px 48px;
    color: #fff;
    font-size: 15px;
    outline: none;
    box-shadow: 0 8px 25px rgba(0, 229, 255, 0.15);
}
.faq-search-box input::placeholder {
    color: #64748b;
}
.faq-search-icon {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 18px;
    color: var(--accent-cyan);
}
.faq-nav-pills {
    display: flex;
    justify-content: center;
    gap: 8px;
    flex-wrap: wrap;
    margin: 35px 0 30px;
}
.faq-pill {
    background: rgba(15, 23, 42, 0.85);
    border: 1px solid var(--border-subtle);
    color: #94a3b8;
    padding: 9px 18px;
    border-radius: 9999px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.faq-pill:hover,
.faq-pill.active {
    background: rgba(0, 229, 255, 0.15);
    border-color: var(--accent-cyan);
    color: #fff;
    box-shadow: 0 0 15px rgba(0, 229, 255, 0.3);
}

.faq-group-card {
    background: var(--bg-card);
    border: 1px solid var(--border-subtle);
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 28px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
}
.faq-group-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 22px;
    padding-bottom: 14px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}
.faq-group-title {
    font-family: var(--font-heading);
    font-size: 20px;
    font-weight: 800;
    color: #ffffff;
    margin: 0;
}

.faq-item {
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 10px;
    margin-bottom: 12px;
    background: rgba(11, 17, 32, 0.5);
    transition: all 0.2s ease;
    overflow: hidden;
}
.faq-item.open {
    border-color: rgba(0, 229, 255, 0.4);
    background: rgba(15, 23, 42, 0.9);
}
.faq-question {
    padding: 16px 20px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    user-select: none;
}
.faq-q-text {
    font-family: var(--font-heading);
    font-size: 15.5px;
    font-weight: 700;
    color: #e2e8f0;
    line-height: 1.4;
    transition: color 0.2s;
}
.faq-item.open .faq-q-text {
    color: #00e5ff;
}
.faq-chevron {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    color: #38bdf8;
    transition: transform 0.25s ease;
    flex-shrink: 0;
    margin-left: 14px;
}
.faq-item.open .faq-chevron {
    transform: rotate(180deg);
    background: var(--accent-cyan);
    color: #060911;
}
.faq-answer {
    display: none;
    padding: 0 20px 20px;
    font-size: 14px;
    color: #cbd5e1;
    line-height: 1.7;
    border-top: 1px solid rgba(255,255,255,0.04);
    padding-top: 14px;
}
.faq-item.open .faq-answer {
    display: block;
}
</style>

<!-- Hero Banner -->
<section class="faqs-hero">
    <div class="container text-center">
        <div style="display: inline-flex; align-items: center; gap: 12px; margin-bottom: 14px;">
            <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand" style="height: 58px; width: 58px; border-radius: 50%; border: 2.5px solid var(--accent-cyan); box-shadow: 0 0 20px rgba(0, 229, 255, 0.45);">
            <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(0, 229, 255, 0.12); border: 1px solid var(--accent-cyan); padding: 5px 14px; border-radius: 9999px; font-size: 12px; color: var(--accent-cyan); font-weight: 700;">
                <span>💡</span> HELP &amp; KNOWLEDGE BASE
            </div>
        </div>
        <h1 style="font-family: var(--font-heading); font-size: 36px; font-weight: 800; color: #fff; margin-bottom: 12px; letter-spacing: -0.5px;">
            Frequently Asked <span style="background: linear-gradient(135deg, #00e5ff 0%, #38bdf8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Questions (FAQs)</span>
        </h1>
        <p style="color: #cbd5e1; font-size: 16px; max-width: 720px; margin: 0 auto; line-height: 1.6;">
            Everything you need to know about purchasing Varsha Brand drill machines, advance token payments, Amazon-style delivery tracking, GST tax invoicing, and warranty support.
        </p>

        <!-- Live FAQ Search -->
        <div class="faq-search-box">
            <span class="faq-search-icon">🔍</span>
            <input type="text" id="faqSearchInput" placeholder="Type keywords e.g. token, tracking, payment, warranty, GST..." oninput="searchFaqs()">
        </div>

        <!-- Filter Categories -->
        <div class="faq-nav-pills">
            <button type="button" class="faq-pill active" onclick="filterFaqCategory('all')">All FAQs</button>
            <button type="button" class="faq-pill" onclick="filterFaqCategory('booking_payments')">💳 Online Booking &amp; Payments</button>
            <button type="button" class="faq-pill" onclick="filterFaqCategory('tracking_delivery')">🚚 Delivery Tracking</button>
            <button type="button" class="faq-pill" onclick="filterFaqCategory('specs_quality')">⚙️ Specifications &amp; Quality</button>
            <button type="button" class="faq-pill" onclick="filterFaqCategory('warranty_service')">🛡️ Warranty &amp; Spares</button>
            <button type="button" class="faq-pill" onclick="filterFaqCategory('factory_visit')">🏭 Factory Visits</button>
        </div>
    </div>
</section>

<!-- FAQ Accordions Container -->
<div class="container" style="margin-bottom: 70px;">
    <div id="faqsList">
        <?php foreach ($faqGroups as $grp): ?>
            <div class="faq-group-card" data-category="<?= $grp['category'] ?>">
                <div class="faq-group-header">
                    <span style="font-size: 26px;"><?= $grp['icon'] ?></span>
                    <h2 class="faq-group-title"><?= $grp['category_name'] ?></h2>
                </div>

                <div class="faq-accordion-list">
                    <?php foreach ($grp['faqs'] as $idx => $f): ?>
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFaq(this)">
                                <span class="faq-q-text"><?= $f['q'] ?></span>
                                <span class="faq-chevron">▼</span>
                            </div>
                            <div class="faq-answer">
                                <?= $f['a'] ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Still Have Questions CTA -->
    <div style="background: linear-gradient(135deg, rgba(2, 132, 199, 0.15) 0%, rgba(16, 185, 129, 0.15) 100%); border: 1.5px solid var(--accent-cyan); border-radius: 16px; padding: 36px; text-align: center; margin-top: 40px;">
        <h3 style="font-family: var(--font-heading); font-size: 24px; color: #fff; font-weight: 800; margin-bottom: 10px;">
            Still Have Questions Not Answered Here?
        </h3>
        <p style="color: #cbd5e1; font-size: 15px; max-width: 600px; margin: 0 auto 22px;">
            Our founding directors and customer engineers are always available to discuss technical specs, pricing, and freight delivery schedules.
        </p>
        <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
            <a href="tel:<?= COMPANY_PHONE_1_CLEAN ?>" class="btn btn-primary" style="font-weight: 700;">
                📞 Call Jitendrabhai Parmar: <?= COMPANY_PHONE_1 ?>
            </a>
            <a href="<?= getWhatsAppLink(COMPANY_WHATSAPP, 'Hello Shree Varsha Engineering Works, I have questions regarding your drill machines and order process.') ?>" target="_blank" class="btn btn-whatsapp" style="font-weight: 700;">
                💬 WhatsApp Chat
            </a>
            <a href="<?= BASE_URL ?>/contact.php" class="btn btn-outline" style="border-color: #38bdf8; color: #38bdf8; font-weight: 700;">
                ✉️ Send Message
            </a>
        </div>
    </div>
</div>

<script>
function toggleFaq(el) {
    const item = el.closest('.faq-item');
    item.classList.toggle('open');
}

function filterFaqCategory(category) {
    // Update active pill
    const pills = document.querySelectorAll('.faq-pill');
    pills.forEach(p => p.classList.remove('active'));
    event.currentTarget.classList.add('active');

    // Filter group cards
    const groups = document.querySelectorAll('.faq-group-card');
    groups.forEach(grp => {
        if (category === 'all' || grp.getAttribute('data-category') === category) {
            grp.style.display = 'block';
        } else {
            grp.style.display = 'none';
        }
    });
}

function searchFaqs() {
    const query = document.getElementById('faqSearchInput').value.toLowerCase().trim();
    const items = document.querySelectorAll('.faq-item');
    const groups = document.querySelectorAll('.faq-group-card');

    if (!query) {
        // Reset display
        groups.forEach(g => g.style.display = 'block');
        items.forEach(i => {
            i.style.display = 'block';
            i.classList.remove('open');
        });
        return;
    }

    groups.forEach(grp => {
        let hasMatch = false;
        const grpItems = grp.querySelectorAll('.faq-item');
        grpItems.forEach(item => {
            const q = item.querySelector('.faq-q-text').textContent.toLowerCase();
            const a = item.querySelector('.faq-answer').textContent.toLowerCase();
            if (q.includes(query) || a.includes(query)) {
                item.style.display = 'block';
                item.classList.add('open'); // auto-open on search hit
                hasMatch = true;
            } else {
                item.style.display = 'none';
            }
        });
        grp.style.display = hasMatch ? 'block' : 'none';
    });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
