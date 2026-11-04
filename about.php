<?php
$pageTitle = 'About Us | Shree Varsha Engineering Works - Bhavnagar, Gujarat';
$pageDesc = 'Learn about Shree Varsha Engineering Works, premier drill machine manufacturers with 25+ years experience in Bhavnagar, Gujarat.';
$currentNav = 'about';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- About Hero Banner (Aligned with Industrial Design System) -->
<section style="padding: 55px 0 35px; background: radial-gradient(circle at 50% 20%, rgba(0, 229, 255, 0.1) 0%, rgba(6, 9, 17, 0.98) 75%); border-bottom: 1px solid var(--border-subtle);">
    <div class="container" style="text-align: center;">
        <div style="display: flex; align-items: center; justify-content: center; gap: 14px; margin-bottom: 16px; flex-wrap: wrap;">
            <div style="width: 58px; height: 58px; border-radius: 50%; border: 2px solid var(--accent-cyan); box-shadow: 0 0 22px rgba(0, 229, 255, 0.45); background: #060911; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand Emblem" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div class="pill-badge" style="margin-bottom: 0; display: inline-flex; align-items: center; gap: 8px; border: 1.5px solid rgba(0, 229, 255, 0.4); background: rgba(15, 23, 42, 0.85); border-radius: 9999px; padding: 7px 18px; box-shadow: 0 0 20px rgba(0, 229, 255, 0.15);">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--accent-cyan); display: inline-block; box-shadow: 0 0 8px var(--accent-cyan);"></span>
                <span style="font-family: 'Space Grotesk', sans-serif; font-size: 12px; font-weight: 800; color: #ffffff; letter-spacing: 1px;">
                    ESTABLISHED 1998 • <span style="color: var(--accent-cyan);">BHAVNAGAR, GUJARAT</span>
                </span>
            </div>
        </div>
        <h1 class="section-title">25+ Years of <span>Industrial Excellence</span></h1>
        <p class="section-desc" style="max-width: 720px; margin: 0 auto; color: #cbd5e1; font-size: 15px; line-height: 1.6;">
            Shree Varsha Engineering Works is recognized across India for manufacturing heavy-duty, high-rigidity Radial and Pillar Drill Machines built to perform under extreme production cycles.
        </p>
    </div>
</section>

<!-- Company History & Overview -->
<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 50px; align-items: center;">
            <div>
                <div class="pill-badge">OUR HERITAGE &amp; FOUNDATION</div>
                <h2 style="font-family: var(--font-heading); font-size: 32px; font-weight: 800; color: #fff; margin-bottom: 18px; line-height: 1.3;">
                    Pioneering High Precision <span style="color: var(--accent-cyan);">Drill Machine Manufacturing</span> in Bhavnagar
                </h2>
                <p style="font-size: 15.5px; color: var(--text-secondary); line-height: 1.7; margin-bottom: 16px;">
                    Founded under the visionary leadership of <strong>Jitendrabhai Parmar</strong>, <strong>Shree Varsha Engineering Works</strong> began with a single mission: to build drill machines that match global European benchmarks in structural stability, gear longevity, and drilling accuracy, while delivering outstanding value to Indian workshops and fabricators.
                </p>
                <p style="font-size: 15.5px; color: var(--text-secondary); line-height: 1.7; margin-bottom: 24px;">
                    Over two and a half decades, we have continuously upgraded our foundry metallurgy, machining infrastructure, and quality control systems. Today, more than <strong>1,800+</strong> Shree Varsha drill machines operate continuously in steel fabrication units, automotive ancillary plants, railway component workshops, and heavy engineering facilities across India.
                </p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 24px;">
                    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); padding: 18px; border-radius: 8px;">
                        <h4 style="font-family: var(--font-heading); color: var(--accent-cyan); font-size: 22px; font-weight: 700;">1,800+</h4>
                        <p style="font-size: 13px; color: var(--text-muted); text-transform: uppercase;">Machines Delivered</p>
                    </div>
                    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); padding: 18px; border-radius: 8px;">
                        <h4 style="font-family: var(--font-heading); color: var(--accent-amber); font-size: 22px; font-weight: 700;">100%</h4>
                        <p style="font-size: 13px; color: var(--text-muted); text-transform: uppercase;">Run-Tested Before Dispatch</p>
                    </div>
                </div>
            </div>

            <!-- Visual & Certifications -->
            <div style="background: var(--bg-card); border: 1px solid var(--border-cyan); border-radius: var(--radius-lg); padding: 36px; box-shadow: var(--shadow-card);">
                <h3 style="font-family: var(--font-heading); font-size: 20px; font-weight: 700; color: #fff; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="var(--accent-cyan)"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
                    Our Quality Commitment
                </h3>
                
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <span style="color: var(--accent-cyan); font-size: 18px;">✔</span>
                        <div>
                            <strong style="color: #fff; font-size: 14px;">Seasoned Grade-25 Castings:</strong>
                            <p style="color: var(--text-secondary); font-size: 13px;">Naturally stress-relieved for 6+ months to ensure permanent dimensional stability and zero column warpage.</p>
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <span style="color: var(--accent-cyan); font-size: 18px;">✔</span>
                        <div>
                            <strong style="color: #fff; font-size: 14px;">Alloy Steel Hardened Gears:</strong>
                            <p style="color: var(--text-secondary); font-size: 13px;">High tensile Nickel-Chrome-Moly alloy gears case hardened to 58-62 HRC for decades of smooth silent running.</p>
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <span style="color: var(--accent-cyan); font-size: 18px;">✔</span>
                        <div>
                            <strong style="color: #fff; font-size: 14px;">Dynamic Balancing & Runout Tests:</strong>
                            <p style="color: var(--text-secondary); font-size: 13px;">Every spindle quill undergoes dynamic balance testing with dial indicators to guarantee runout within 0.015mm.</p>
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <span style="color: var(--accent-cyan); font-size: 18px;">✔</span>
                        <div>
                            <strong style="color: #fff; font-size: 14px;">Pan-India Spares & Technical Support:</strong>
                            <p style="color: var(--text-secondary); font-size: 13px;">Guaranteed availability of gears, elevating screws, spindle quills, and bearings for the lifetime of your machine.</p>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 28px; padding-top: 20px; border-top: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span style="font-size: 12px; color: var(--text-muted); display: block;">Contact Person</span>
                        <strong style="color: #fff; font-size: 15px;">Jitendrabhai Parmar</strong>
                    </div>
                    <a href="tel:<?= COMPANY_PHONE_1_CLEAN ?>" class="btn btn-primary" style="padding: 8px 16px; font-size: 12px;">
                        <?= COMPANY_PHONE_1 ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Manufacturing Facility Highlights -->
<section class="section" style="background: rgba(11, 17, 32, 0.4);">
    <div class="container">
        <div class="section-title-wrap">
            <div class="pill-badge">INFRASTRUCTURE</div>
            <h2 class="section-title">Our Modern <span>Manufacturing Facility</span></h2>
            <p class="section-desc">
                Located in the prominent industrial zone of GIDC Hadanagar, Bhavnagar, Gujarat, our facility houses specialized machine tools for high accuracy manufacturing.
            </p>
        </div>

        <div class="features-grid">
            <div class="feature-box" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                <div class="feature-icon" style="margin: 0 auto 16px; display: flex; align-items: center; justify-content: center;">
                    <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14H7v-2h5v2zm5-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                </div>
                <h3 class="feature-title">Heavy Boring & Plano Millers</h3>
                <p class="feature-desc">Large horizontal and vertical boring machines ensuring absolute perpendicularity between column mounting and spindle axes.</p>
            </div>

            <div class="feature-box" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                <div class="feature-icon" style="margin: 0 auto 16px; display: flex; align-items: center; justify-content: center;">
                    <svg viewBox="0 0 24 24"><path d="M12 2L1 21h22L12 2zm0 3.99L19.53 19H4.47L12 5.99zM11 16h2v2h-2zm0-6h2v4h-2z"/></svg>
                </div>
                <h3 class="feature-title">Precision Gear Hobbing</h3>
                <p class="feature-desc">High precision gear hobbing and tooth chamfering machines capable of cutting heavy modulus gear teeth with supreme profile accuracy.</p>
            </div>

            <div class="feature-box" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                <div class="feature-icon" style="margin: 0 auto 16px; display: flex; align-items: center; justify-content: center;">
                    <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
                </div>
                <h3 class="feature-title">Cylindrical & Surface Grinding</h3>
                <p class="feature-desc">Dedicated cylindrical grinding bays for mirror finish column sleeves, spindle quills, and ground box table surfaces.</p>
            </div>

            <div class="feature-box" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                <div class="feature-icon" style="margin: 0 auto 16px; display: flex; align-items: center; justify-content: center;">
                    <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                </div>
                <h3 class="feature-title">Full Load Testing Bay</h3>
                <p class="feature-desc">Every drill machine is run on full electrical and mechanical loads for 6 continuous hours to inspect thermal rise, noise, and vibration.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="section" style="text-align: center;">
    <div class="container">
        <div class="pill-badge">PLAN A FACTORY VISIT</div>
        <h2 class="section-title">Experience Our <span>Machines in Action</span></h2>
        <p class="section-desc" style="max-width: 600px; margin: 0 auto 30px;">
            We cordially invite industrial clients, distributors, and workshop owners to visit our Bhavnagar manufacturing facility for live cutting trials and live demonstrations.
        </p>
        <div style="display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;">
            <a href="<?= BASE_URL ?>/contact.php" class="btn btn-primary">Schedule Factory Visit</a>
            <a href="tel:<?= COMPANY_PHONE_1_CLEAN ?>" class="btn btn-outline">Call: <?= COMPANY_PHONE_1 ?></a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
