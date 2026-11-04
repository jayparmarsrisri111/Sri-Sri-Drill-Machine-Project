<?php
// Footer Include
?>
<!-- Site Footer -->
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <!-- Brand Column -->
            <div class="footer-brand">
                <a href="<?= BASE_URL ?>/index.php" style="display:flex; align-items:center; gap:12px; margin-bottom:15px; text-decoration:none;">
                    <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="<?= BRAND_NAME ?>" style="height:52px; width:52px; object-fit:contain; border-radius:50%; border:2px solid rgba(0, 229, 255, 0.6); filter:drop-shadow(0 0 12px rgba(0, 229, 255, 0.4)); flex-shrink:0;">
                    <div style="display:flex; flex-direction:column; line-height:1.2;">
                        <span style="font-family:'Space Grotesk', sans-serif; font-size:18px; font-weight:800; color:#fff; letter-spacing:0.8px;">VARSHA BRAND</span>
                        <span style="font-size:11px; font-weight:700; color:#00e5ff; letter-spacing:1px;">SHREE VARSHA ENGINEERING WORKS</span>
                    </div>
                </a>
                <p>
                    <strong><?= APP_NAME ?> (<?= BRAND_NAME ?>)</strong> is a premier manufacturer of heavy-duty Radial Drill Machines, Pillar Drilling Machines, Bench Drills, and Precision Tapping Machinery. Engineered with high-tensile casting, hardened gears, and vibration-free industrial precision.
                </p>
                <div style="margin-top: 18px; display: flex; gap: 8px; flex-wrap: wrap;">
                    <span class="badge-tag">ISO 9001:2015</span>
                    <span class="badge-tag" style="border-color: var(--border-cyan); color: var(--accent-cyan);">BHAVNAGAR, GUJARAT</span>
                    <span class="badge-tag" style="border-color: #10b981; color: #34d399;">GSTIN: <?= COMPANY_GSTIN ?></span>
                </div>
            </div>

            <!-- Drill Machinery Quick Links -->
            <div>
                <h4 class="footer-heading">Our Drill Machines</h4>
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL ?>/machines.php?category=radial-drill-machines">Radial Drill Machines (40-65mm)</a></li>
                    <li><a href="<?= BASE_URL ?>/machines.php?category=pillar-drill-machines">Pillar Drill Machines (25-40mm)</a></li>
                    <li><a href="<?= BASE_URL ?>/machines.php?category=bench-drill-machines">Bench Drill Machines (20mm)</a></li>
                    <li><a href="<?= BASE_URL ?>/machines.php?category=tapping-special-purpose">Drilling & Tapping Machines</a></li>
                    <li><a href="<?= BASE_URL ?>/catalog.php">Official 2026 Catalog (PDF)</a></li>
                </ul>
            </div>

            <!-- Quick Navigation -->
            <div>
                <h4 class="footer-heading">Quick Links</h4>
                <ul class="footer-links" style="display: flex; flex-direction: column; gap: 8px;">
                    <li><a href="<?= BASE_URL ?>/index.php" style="display: inline-flex; align-items: center; gap: 8px;"><span style="font-size: 13px;">🏠</span><span>Home Overview</span></a></li>
                    <li><a href="<?= BASE_URL ?>/about.php" style="display: inline-flex; align-items: center; gap: 8px;"><span style="font-size: 13px;">🏭</span><span>About Varsha Brand</span></a></li>
                    <li><a href="<?= BASE_URL ?>/machines.php" style="display: inline-flex; align-items: center; gap: 8px;"><span style="font-size: 13px;">⚙️</span><span>All Drill Machines</span></a></li>
                    <li><a href="<?= BASE_URL ?>/catalog.php" style="display: inline-flex; align-items: center; gap: 8px; color: #38bdf8; font-weight: 600;"><span style="font-size: 13px;">📖</span><span>Technical Catalog &amp; PDF</span></a></li>
                    <li><a href="<?= BASE_URL ?>/simulator-6d.php" style="display: inline-flex; align-items: center; gap: 8px; color: var(--accent-cyan); font-weight: 600;"><span style="font-size: 13px;">🔄</span><span>360° Interactive 12D View</span></a></li>
                    <li><a href="<?= BASE_URL ?>/blog.php" style="display: inline-flex; align-items: center; gap: 8px;"><span style="font-size: 13px;">📚</span><span>Engineering Blog &amp; Guides</span></a></li>
                    <li><a href="<?= BASE_URL ?>/track-order.php" style="display: inline-flex; align-items: center; gap: 8px; color: #34d399; font-weight: 600;"><span style="font-size: 13px;">🚚</span><span>Live Order &amp; Delivery Tracking</span></a></li>
                    <li><a href="<?= BASE_URL ?>/faqs.php" style="display: inline-flex; align-items: center; gap: 8px;"><span style="font-size: 13px;">💡</span><span>Help &amp; FAQs</span></a></li>
                    <li><a href="<?= BASE_URL ?>/checkout.php" style="display: inline-flex; align-items: center; gap: 8px; color: #4ade80; font-weight: 600;"><span style="font-size: 13px;">💳</span><span>Online Machine Booking</span></a></li>
                    <li><a href="<?= BASE_URL ?>/invoice.php" style="display: inline-flex; align-items: center; gap: 8px; color: #facc15; font-weight: 600;"><span style="font-size: 13px;">📄</span><span>View Tax Invoice / Bill</span></a></li>
                    <li><a href="<?= BASE_URL ?>/contact.php" style="display: inline-flex; align-items: center; gap: 8px;"><span style="font-size: 13px;">📍</span><span>Factory Contact &amp; Location</span></a></li>
                </ul>
            </div>

            <!-- Contact & Factory Details -->
            <div>
                <h4 class="footer-heading">Factory Works &amp; Office</h4>
                <div class="footer-contact-list">
                    <div class="footer-contact-item" style="align-items: flex-start;">
                        <svg viewBox="0 0 24 24" style="flex-shrink: 0; margin-top: 3px; fill: var(--accent-cyan); width: 18px; height: 18px;"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        <div>
                            <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode(COMPANY_ADDRESS) ?>" target="_blank" rel="noopener noreferrer" style="color: #cbd5e1; text-decoration: none; display: inline; line-height: 1.5; transition: color 0.2s;" onmouseover="this.style.color='#00e5ff'" onmouseout="this.style.color='#cbd5e1'">
                                <span><?= COMPANY_ADDRESS ?></span>
                                <span style="display: inline-flex; align-items: center; gap: 4px; color: var(--accent-cyan); font-size: 11px; font-weight: 700; background: rgba(0,229,255,0.1); border: 1px solid rgba(0,229,255,0.3); padding: 2px 7px; border-radius: 6px; margin-left: 6px; white-space: nowrap;">📍 Open Map ↗</span>
                            </a>
                        </div>
                    </div>
                    <div class="footer-contact-item" style="align-items: flex-start;">
                        <svg viewBox="0 0 24 24" style="flex-shrink: 0; margin-top: 3px; fill: #34d399; width: 18px; height: 18px;"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <div>
                                <span style="color: #94a3b8; font-size: 12.5px;">Jitendrabhai Parmar: </span>
                                <a href="tel:<?= COMPANY_PHONE_1_CLEAN ?>" style="color: #ffffff; font-weight: 700; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#34d399'" onmouseout="this.style.color='#ffffff'">
                                    <?= COMPANY_PHONE_1 ?>
                                </a>
                            </div>
                            <div>
                                <span style="color: #94a3b8; font-size: 12.5px;">Jay Parmar: </span>
                                <a href="tel:<?= COMPANY_PHONE_2_CLEAN ?>" style="color: #ffffff; font-weight: 700; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#38bdf8'" onmouseout="this.style.color='#ffffff'">
                                    <?= COMPANY_PHONE_2 ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="footer-contact-item" style="align-items: center;">
                        <svg viewBox="0 0 24 24" style="flex-shrink: 0; fill: #38bdf8; width: 18px; height: 18px;"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        <a href="mailto:<?= COMPANY_EMAIL ?>" style="color: #cbd5e1; text-decoration: none; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='#38bdf8'" onmouseout="this.style.color='#cbd5e1'">
                            <?= COMPANY_EMAIL ?>
                        </a>
                    </div>
                    <div class="footer-contact-item" style="font-size: 12px; color: #94a3b8;">
                        <span style="color: #34d399; font-size: 14px;">●</span>
                        <span>Working Hours: Mon - Sat: 8:30 AM - 8:00 PM</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div>
                &copy; <?= date('Y') ?> <strong><?= APP_NAME ?> (<?= BRAND_NAME ?>)</strong>. All Rights Reserved. GSTIN: <strong style="color: #38bdf8;"><?= COMPANY_GSTIN ?></strong>
            </div>
            <div>
                Authorized Signatory: <strong style="color: #fff;"><?= AUTHORIZED_SIGNATORY ?></strong> | Factory: <strong>Bhavnagar, Gujarat</strong>
            </div>
        </div>
    </div>
</footer>

<!-- Floating WhatsApp & Direct Call Buttons -->
<div class="floating-contact-bar">
    <a href="<?= getWhatsAppLink(COMPANY_WHATSAPP, 'Hello Shree Varsha Engineering Works, I would like to get quotation and catalogs for your Drill Machines.') ?>" target="_blank" class="floating-btn floating-whatsapp" data-tooltip="Chat on WhatsApp" aria-label="WhatsApp">
        <svg viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/></svg>
    </a>
    <a href="tel:<?= COMPANY_PHONE_1_CLEAN ?>" class="floating-btn floating-phone" data-tooltip="Call <?= COMPANY_PHONE_1 ?>" aria-label="Call Now">
        <svg viewBox="0 0 24 24"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
    </a>
</div>

<!-- Quote Modal -->
<?php require_once __DIR__ . '/quote-modal.php'; ?>

<!-- Customer Feedback Modal -->
<?php require_once __DIR__ . '/feedback-modal.php'; ?>

<!-- 360° Interactive 12D Machine Turntable Modal -->
<?php require_once __DIR__ . '/modal-360.php'; ?>

<!-- Customer Authentication Modal -->
<?php require_once __DIR__ . '/customer-auth-modal.php'; ?>

<!-- AI SmartDrill Chatbot Assistant -->
<?php require_once __DIR__ . '/chatbot.php'; ?>

<!-- Main Script -->
<script src="<?= assetUrl('assets/js/main.js') ?>"></script>
</body>
</html>
