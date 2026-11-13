<?php
$pageTitle = 'About Us | Shree Varsha Engineering Works - Bhavnagar, Gujarat';
$pageDesc = 'Learn about Shree Varsha Engineering Works (Varsha Brand), premier industrial drill machine manufacturers with 25+ years experience in Bhavnagar, Gujarat.';
$pageKeywords = 'about Shree Varsha Engineering Works, Varsha Brand drill machine, Jitendrabhai Parmar, Bhavnagar machine manufacturer, drill machine history';
$canonicalUrl = 'https://varsha-drill-machines.onrender.com/about.php';
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
        <h1 class="section-title"><?= getSetting('experience_years', '25+') ?> Years of <span>Industrial Excellence</span></h1>
        <p class="section-desc" style="max-width: 720px; margin: 0 auto; color: #cbd5e1; font-size: 15px; line-height: 1.6;">
            <?= APP_NAME ?> is recognized across India for manufacturing heavy-duty, high-rigidity Radial and Pillar Drill Machines built to perform under extreme production cycles.
        </p>
    </div>
</section>

<!-- Company History & Overview -->
<section class="section">
    <div class="container">
        <div class="responsive-two-col" style="display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 50px; align-items: center;">
            <div>
                <div class="pill-badge">OUR HERITAGE &amp; FOUNDATION</div>
                <h2 style="font-family: var(--font-heading); font-size: 32px; font-weight: 800; color: #fff; margin-bottom: 18px; line-height: 1.3;">
                    Pioneering High Precision <span style="color: var(--accent-cyan);">Drill Machine Manufacturing</span> in Bhavnagar
                </h2>
                <p style="font-size: 15.5px; color: var(--text-secondary); line-height: 1.7; margin-bottom: 16px;">
                    Founded under the visionary leadership of <strong><?= COMPANY_CONTACT_PERSON ?></strong>, <strong><?= APP_NAME ?></strong> began with a single mission: to build drill machines that match global European benchmarks in structural stability, gear longevity, and drilling accuracy, while delivering outstanding value to Indian workshops and fabricators.
                </p>
                <p style="font-size: 15.5px; color: var(--text-secondary); line-height: 1.7; margin-bottom: 24px;">
                    Over two and a half decades, we have continuously upgraded our foundry metallurgy, machining infrastructure, and quality control systems. Today, more than <strong><?= getSetting('machines_built', '1,800+') ?></strong> <?= BRAND_NAME ?> drill machines operate continuously in steel fabrication units, automotive ancillary plants, railway component workshops, and heavy engineering facilities across India.
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
                        <strong style="color: #fff; font-size: 15px;"><?= COMPANY_CONTACT_PERSON ?></strong>
                    </div>
                    <a href="tel:<?= COMPANY_PHONE_1_CLEAN ?>" class="btn btn-primary" style="padding: 8px 16px; font-size: 12px;">
                        <?= COMPANY_PHONE_1 ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Executive Leadership & Directors Section -->
<section class="section" style="background: radial-gradient(circle at 50% 10%, rgba(0, 229, 255, 0.08) 0%, rgba(6, 10, 20, 0.98) 70%); border-top: 1px solid var(--border-subtle); border-bottom: 1px solid var(--border-subtle); padding: 60px 0;">
    <div class="container">
        <div class="section-title-wrap" style="text-align: center; margin-bottom: 45px;">
            <div class="pill-badge">EXECUTIVE LEADERSHIP &amp; MANAGEMENT</div>
            <h2 class="section-title">Meet Our <span>Founders &amp; Directors</span></h2>
            <p class="section-desc" style="max-width: 680px; margin: 0 auto; color: #cbd5e1; font-size: 15px; line-height: 1.6;">
                Leading Shree Varsha Engineering Works with over 25 years of industrial foundry metallurgy, heavy gear cutting precision, and machine-tool engineering excellence.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 32px; max-width: 1060px; margin: 0 auto;">
            <!-- Jitendrabhai Parmar (Founder & CEO) -->
            <div style="background: linear-gradient(145deg, rgba(15, 23, 42, 0.95) 0%, rgba(10, 15, 29, 0.98) 100%); border: 1.5px solid rgba(245, 158, 11, 0.45); border-radius: 16px; padding: 32px 26px; box-shadow: 0 20px 45px rgba(0, 0, 0, 0.6), 0 0 25px rgba(245, 158, 11, 0.12); position: relative; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.3s ease, border-color 0.3s ease;">
                <div style="position: absolute; top: 0; right: 0; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #000; font-weight: 800; font-size: 11px; padding: 5px 16px; border-bottom-left-radius: 12px; text-transform: uppercase; letter-spacing: 0.8px; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);">
                    ★ FOUNDER &amp; CEO
                </div>
                <div style="display: flex; align-items: center; gap: 18px; margin-bottom: 22px;">
                    <div style="position: relative; flex-shrink: 0;">
                        <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Jitendrabhai Parmar - CEO" style="width: 86px; height: 86px; border-radius: 50%; object-fit: contain; background: #070b14; border: 3px solid #f59e0b; padding: 4px; box-shadow: 0 0 25px rgba(245, 158, 11, 0.4);">
                        <span style="position: absolute; bottom: 3px; right: 3px; width: 14px; height: 14px; border-radius: 50%; background: #10b981; border: 2.5px solid #0f172a; box-shadow: 0 0 8px #10b981;"></span>
                    </div>
                    <div>
                        <h3 style="font-family: var(--font-heading); font-size: 21px; font-weight: 800; color: #ffffff; margin: 0 0 4px 0; letter-spacing: 0.3px;">Jitendrabhai Parmar</h3>
                        <div style="font-size: 14px; font-weight: 700; color: #fbbf24; margin-bottom: 3px;">Chief Executive Officer (CEO)</div>
                        <div style="font-size: 12px; color: #94a3b8; font-weight: 500;">Founder &amp; Heavy Metallurgy Director</div>
                    </div>
                </div>
                <p style="color: #cbd5e1; font-size: 13.5px; line-height: 1.65; margin-bottom: 22px; flex: 1;">
                    Visionary founder of Shree Varsha Engineering Works with <strong>25+ years</strong> of hands-on mastery in seasoned Grade-25 cast iron metallurgy, heavy boring machines, and industrial drill press engineering across Bhavnagar's machine-tool cluster.
                </p>
                <div style="background: rgba(6, 10, 20, 0.75); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 10px; padding: 14px 16px; display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 6px;">
                        <span style="color: #94a3b8; display: inline-flex; align-items: center; gap: 6px;">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="#34d399"><path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.02-.24 11.72 11.72 0 003.68.59 1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h3.5a1 1 0 011 1 11.72 11.72 0 00.59 3.68 1 1 0 01-.24 1.02l-2.23 2.09z"/></svg>
                            Direct Phone:
                        </span>
                        <a href="tel:+919824913555" style="color: #34d399; font-weight: 700; text-decoration: none; font-family: 'Space Grotesk', monospace; font-size: 13.5px;">+91 98249 13555</a>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 6px;">
                        <span style="color: #94a3b8; display: inline-flex; align-items: center; gap: 6px;">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="#38bdf8"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                            Email Address:
                        </span>
                        <a href="mailto:varshaengineeringworks@gmail.com" style="color: #38bdf8; font-weight: 600; text-decoration: none; word-break: break-all; font-size: 12.5px;">varshaengineeringworks@gmail.com</a>
                    </div>
                </div>
                <div style="margin-top: 18px;">
                    <a href="<?= getWhatsAppLink('919824913555', 'Hello Jitendrabhai Parmar (CEO), I am inquiring about Varsha Brand drill machines.') ?>" target="_blank" class="btn" style="width: 100%; justify-content: center; font-size: 13px; font-weight: 700; background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: #ffffff; border: 1px solid #34d399; border-radius: 8px; padding: 11px; text-decoration: none; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/></svg>
                        <span>WhatsApp with Jitendrabhai (CEO)</span>
                    </a>
                </div>
            </div>

            <!-- Jay Parmar (Co-CEO & Production Head) -->
            <div style="background: linear-gradient(145deg, rgba(15, 23, 42, 0.95) 0%, rgba(10, 15, 29, 0.98) 100%); border: 1.5px solid rgba(0, 229, 255, 0.45); border-radius: 16px; padding: 32px 26px; box-shadow: 0 20px 45px rgba(0, 0, 0, 0.6), 0 0 25px rgba(0, 229, 255, 0.15); position: relative; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.3s ease, border-color 0.3s ease;">
                <div style="position: absolute; top: 0; right: 0; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color: #060911; font-weight: 800; font-size: 11px; padding: 5px 16px; border-bottom-left-radius: 12px; text-transform: uppercase; letter-spacing: 0.8px; box-shadow: 0 4px 12px rgba(0, 229, 255, 0.3);">
                    ★ CO-CEO &amp; PRODUCTION HEAD
                </div>
                <div style="display: flex; align-items: center; gap: 18px; margin-bottom: 22px;">
                    <div style="position: relative; flex-shrink: 0;">
                        <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Jay Parmar - Co-CEO" style="width: 86px; height: 86px; border-radius: 50%; object-fit: contain; background: #070b14; border: 3px solid var(--accent-cyan); padding: 4px; box-shadow: 0 0 25px rgba(0, 229, 255, 0.4);">
                        <span style="position: absolute; bottom: 3px; right: 3px; width: 14px; height: 14px; border-radius: 50%; background: #00e5ff; border: 2.5px solid #0f172a; box-shadow: 0 0 8px #00e5ff;"></span>
                    </div>
                    <div>
                        <h3 style="font-family: var(--font-heading); font-size: 21px; font-weight: 800; color: #ffffff; margin: 0 0 4px 0; letter-spacing: 0.3px;">Jay Parmar</h3>
                        <div style="font-size: 14px; font-weight: 700; color: var(--accent-cyan); margin-bottom: 3px;">Co-CEO &amp; Production Engineer</div>
                        <div style="font-size: 12px; color: #94a3b8; font-weight: 500;">Authorized Signatory &amp; Operations Head</div>
                    </div>
                </div>
                <p style="color: #cbd5e1; font-size: 13.5px; line-height: 1.65; margin-bottom: 22px; flex: 1;">
                    Specialized in precision spindle runout calibration (&lt;0.015mm), 20MnCr5 hardened alloy gear tooth profiling, 12D digital machinery simulations, and managing client consignments &amp; verified GST tax documentation.
                </p>
                <div style="background: rgba(6, 10, 20, 0.75); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 10px; padding: 14px 16px; display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 6px;">
                        <span style="color: #94a3b8; display: inline-flex; align-items: center; gap: 6px;">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="#34d399"><path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.02-.24 11.72 11.72 0 003.68.59 1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h3.5a1 1 0 011 1 11.72 11.72 0 00.59 3.68 1 1 0 01-.24 1.02l-2.23 2.09z"/></svg>
                            Direct Phone:
                        </span>
                        <a href="tel:+919714611331" style="color: #34d399; font-weight: 700; text-decoration: none; font-family: 'Space Grotesk', monospace; font-size: 13.5px;">+91 97146 11331</a>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 6px;">
                        <span style="color: #94a3b8; display: inline-flex; align-items: center; gap: 6px;">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="#38bdf8"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                            Email Address:
                        </span>
                        <a href="mailto:jayparmar@varshamachines.com" style="color: #38bdf8; font-weight: 600; text-decoration: none; word-break: break-all; font-size: 12.5px;">jayparmar@varshamachines.com</a>
                    </div>
                </div>
                <div style="margin-top: 18px;">
                    <a href="<?= getWhatsAppLink('919714611331', 'Hello Jay Parmar (Co-CEO), I am inquiring about Varsha Brand drill machines.') ?>" target="_blank" class="btn" style="width: 100%; justify-content: center; font-size: 13px; font-weight: 700; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color: #060911; border: 1px solid #00e5ff; border-radius: 8px; padding: 11px; text-decoration: none; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(0, 229, 255, 0.3);">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/></svg>
                        <span>WhatsApp with Jay Parmar (Co-CEO)</span>
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
