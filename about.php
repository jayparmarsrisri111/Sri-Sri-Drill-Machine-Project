<?php
$pageTitle = 'About Us | Shree Varsha Engineering Works - Bhavnagar, Gujarat';
$pageDesc = 'Learn about Shree Varsha Engineering Works (Varsha Brand), premier industrial drill machine manufacturers with 25+ years experience in Bhavnagar, Gujarat.';
$pageKeywords = 'about Shree Varsha Engineering Works, Varsha Brand drill machine, Jitendrabhai Parmar, Jay Parmar, Bhavnagar machine manufacturer, drill machine history';
$canonicalUrl = 'https://varsha-drill-machines.onrender.com/about.php';
$currentNav = 'about';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- About Hero Banner (Aligned with Industrial Precision Design System) -->
<section style="padding: 60px 0 40px; background: radial-gradient(circle at 50% 15%, rgba(0, 229, 255, 0.12) 0%, rgba(6, 9, 17, 0.98) 75%); border-bottom: 1px solid var(--border-subtle); position: relative; overflow: hidden;">
    <div style="position: absolute; inset: 0; background: linear-gradient(rgba(0, 229, 255, 0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(0, 229, 255, 0.03) 1px, transparent 1px); background-size: 40px 40px; pointer-events: none;"></div>
    <div class="container" style="text-align: center; position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; justify-content: center; gap: 14px; margin-bottom: 18px; flex-wrap: wrap;">
            <div style="width: 58px; height: 58px; border-radius: 50%; border: 2px solid var(--accent-cyan); box-shadow: 0 0 24px rgba(0, 229, 255, 0.45); background: #060911; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand Emblem" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <!-- Guaranteed Zero Dots on the Left of ESTABLISHED -->
            <div class="about-est-badge" style="display: inline-flex; align-items: center; border: 1.5px solid rgba(0, 229, 255, 0.45); background: rgba(15, 23, 42, 0.9); border-radius: 9999px; padding: 7px 20px; box-shadow: 0 0 20px rgba(0, 229, 255, 0.2);">
                <span style="font-family: 'Space Grotesk', sans-serif; font-size: 12.5px; font-weight: 800; color: #ffffff; letter-spacing: 1.2px; text-transform: uppercase;">
                    ESTABLISHED 1998 &bull; <span style="color: var(--accent-cyan);">BHAVNAGAR, GUJARAT</span>
                </span>
            </div>
        </div>
        <style>
            /* Strict Zero Bullets / Dots on ESTABLISHED Badge */
            .about-est-badge::before,
            .about-est-badge::after {
                display: none !important;
                content: none !important;
                width: 0 !important;
                height: 0 !important;
            }
            .about-metric-card {
                background: linear-gradient(145deg, rgba(15, 23, 42, 0.9) 0%, rgba(10, 16, 31, 0.95) 100%);
                border: 1px solid rgba(0, 229, 255, 0.25);
                padding: 20px;
                border-radius: 12px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.4);
                transition: transform 0.25s ease, border-color 0.25s ease;
            }
            .about-metric-card:hover {
                transform: translateY(-3px);
                border-color: #00e5ff;
            }
            @media (max-width: 860px) {
                .responsive-two-col {
                    grid-template-columns: 1fr !important;
                    gap: 32px !important;
                }
            }
        </style>
        <h1 class="section-title" style="font-size: 38px; line-height: 1.2; margin-bottom: 12px;">
            <?= getSetting('experience_years', '25+') ?> Years of <span style="color: var(--accent-cyan);">Industrial Excellence</span>
        </h1>
        <p class="section-desc" style="max-width: 740px; margin: 0 auto 28px; color: #cbd5e1; font-size: 15.5px; line-height: 1.65;">
            <?= APP_NAME ?> (VARSHA BRAND) is recognized across India for manufacturing heavy-duty, high-rigidity Radial and Pillar Drill Machines engineered for extreme industrial continuous production.
        </p>

        <!-- Quick Top Trust Stats Bar -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; max-width: 920px; margin: 0 auto;">
            <div class="about-metric-card">
                <div style="font-family: 'Space Grotesk', sans-serif; font-size: 26px; font-weight: 800; color: #00e5ff;">1,800+</div>
                <div style="font-size: 11.5px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 3px;">Machines Operating</div>
            </div>
            <div class="about-metric-card">
                <div style="font-family: 'Space Grotesk', sans-serif; font-size: 26px; font-weight: 800; color: #fbbf24;">25+ Yrs</div>
                <div style="font-size: 11.5px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 3px;">Foundry Metallurgy</div>
            </div>
            <div class="about-metric-card">
                <div style="font-family: 'Space Grotesk', sans-serif; font-size: 26px; font-weight: 800; color: #34d399;">&lt;0.015mm</div>
                <div style="font-size: 11.5px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 3px;">Spindle Runout Precision</div>
            </div>
            <div class="about-metric-card">
                <div style="font-family: 'Space Grotesk', sans-serif; font-size: 26px; font-weight: 800; color: #a855f7;">100%</div>
                <div style="font-size: 11.5px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 3px;">Dial-Gauge Inspected</div>
            </div>
        </div>
    </div>
</section>

<!-- Company History & Foundation -->
<section class="section" style="padding: 60px 0;">
    <div class="container">
        <div class="responsive-two-col" style="display: grid; grid-template-columns: 1.15fr 0.85fr; gap: 48px; align-items: center;">
            <div>
                <div class="pill-badge" style="margin-bottom: 12px;">OUR HERITAGE &amp; FOUNDATION</div>
                <h2 style="font-family: var(--font-heading); font-size: 32px; font-weight: 800; color: #fff; margin-bottom: 18px; line-height: 1.3;">
                    Pioneering High Precision <span style="color: var(--accent-cyan);">Drill Machine Manufacturing</span> in Bhavnagar
                </h2>
                <p style="font-size: 15px; color: var(--text-secondary); line-height: 1.7; margin-bottom: 16px;">
                    Founded under the visionary leadership of <strong><?= COMPANY_CONTACT_PERSON ?></strong>, <strong><?= APP_NAME ?></strong> began with a single mission: to build drill machines that match European benchmarks in structural stability, gear longevity, and drilling accuracy, while delivering outstanding durability to Indian workshops and fabricators.
                </p>
                <p style="font-size: 15px; color: var(--text-secondary); line-height: 1.7; margin-bottom: 24px;">
                    Over two and a half decades, we have continuously upgraded our foundry metallurgy, machining infrastructure, and quality control systems. Today, more than <strong><?= getSetting('machines_built', '1,800+') ?></strong> <?= BRAND_NAME ?> drill machines operate continuously in steel fabrication units, automotive ancillary plants, railway component workshops, and heavy engineering facilities across India.
                </p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(0, 229, 255, 0.2); padding: 16px; border-radius: 10px;">
                        <div style="color: var(--accent-cyan); font-weight: 800; font-size: 14px; margin-bottom: 4px;">Grade-25 Castings</div>
                        <p style="font-size: 12.5px; color: #94a3b8; margin: 0; line-height: 1.4;">Naturally stress-relieved for 6+ months to ensure permanent dimensional stability.</p>
                    </div>
                    <div style="background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(245, 158, 11, 0.2); padding: 16px; border-radius: 10px;">
                        <div style="color: #fbbf24; font-weight: 800; font-size: 14px; margin-bottom: 4px;">20MnCr5 Alloy Gears</div>
                        <p style="font-size: 12.5px; color: #94a3b8; margin: 0; line-height: 1.4;">Case-hardened to 58-62 HRC for decades of smooth, silent power transmission.</p>
                    </div>
                </div>
            </div>

            <!-- Quality Pillars Card -->
            <div style="background: linear-gradient(160deg, rgba(15, 23, 42, 0.95) 0%, rgba(8, 13, 26, 0.98) 100%); border: 1.5px solid rgba(0, 229, 255, 0.35); border-radius: 16px; padding: 32px 28px; box-shadow: 0 20px 45px rgba(0,0,0,0.5);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <h3 style="font-family: var(--font-heading); font-size: 19px; font-weight: 800; color: #fff; margin: 0; display: flex; align-items: center; gap: 8px;">
                        <span>🛡️</span>
                        <span>Quality Commitment</span>
                    </h3>
                    <span style="font-size: 11px; background: rgba(0, 229, 255, 0.15); border: 1px solid #00e5ff; color: #00e5ff; padding: 2px 8px; border-radius: 9999px; font-weight: 800;">
                        ISO 9001:2015
                    </span>
                </div>

                <div style="display: flex; flex-direction: column; gap: 14px;">
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <span style="color: #00e5ff; font-size: 16px; flex-shrink: 0; margin-top: 1px;">✔</span>
                        <div>
                            <strong style="color: #fff; font-size: 13.5px; display: block;">Dynamic Balancing &amp; Runout Tests</strong>
                            <p style="color: #94a3b8; font-size: 12.5px; margin: 2px 0 0; line-height: 1.4;">Dial indicator tested on every spindle quill to ensure runout strictly under 0.015mm.</p>
                        </div>
                    </div>
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <span style="color: #00e5ff; font-size: 16px; flex-shrink: 0; margin-top: 1px;">✔</span>
                        <div>
                            <strong style="color: #fff; font-size: 13.5px; display: block;">High Rigid Column &amp; Arm Assembly</strong>
                            <p style="color: #94a3b8; font-size: 12.5px; margin: 2px 0 0; line-height: 1.4;">Heavy-ribbed structure prevents deflection even under maximum feed thrust and heavy drilling.</p>
                        </div>
                    </div>
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <span style="color: #00e5ff; font-size: 16px; flex-shrink: 0; margin-top: 1px;">✔</span>
                        <div>
                            <strong style="color: #fff; font-size: 13.5px; display: block;">Pan-India Spares &amp; Support</strong>
                            <p style="color: #94a3b8; font-size: 12.5px; margin: 2px 0 0; line-height: 1.4;">Lifetime availability of spare gears, elevating screws, spindle quills, and precision bearings.</p>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <span style="font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; display: block;">Factory Desk Contact</span>
                        <strong style="color: #fff; font-size: 14.5px;"><?= COMPANY_CONTACT_PERSON ?></strong>
                    </div>
                    <a href="tel:<?= COMPANY_PHONE_1_CLEAN ?>" class="btn btn-primary" style="padding: 7px 16px; font-size: 12px; font-weight: 700;">
                        📞 <?= COMPANY_PHONE_1 ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     EXECUTIVE LEADERSHIP & DIRECTORS SECTION (World-Class Redesign)
     ========================================================================= -->
<section class="section" style="background: radial-gradient(circle at 50% 15%, rgba(0, 229, 255, 0.08) 0%, rgba(6, 10, 20, 0.98) 70%); border-top: 1px solid var(--border-subtle); border-bottom: 1px solid var(--border-subtle); padding: 70px 0;">
    <div class="container">
        <div class="section-title-wrap" style="text-align: center; margin-bottom: 45px;">
            <div class="pill-badge" style="background: rgba(0, 229, 255, 0.12); border-color: #00e5ff; color: #00e5ff;">
                EXECUTIVE LEADERSHIP &amp; MANAGEMENT
            </div>
            <h2 class="section-title" style="font-size: 32px; margin-top: 8px;">
                Meet Our <span>Founders &amp; Directors</span>
            </h2>
            <p class="section-desc" style="max-width: 700px; margin: 0 auto; color: #cbd5e1; font-size: 15px; line-height: 1.6;">
                Leading Shree Varsha Engineering Works with over 25 years of industrial foundry metallurgy, heavy gear cutting precision, and machine-tool engineering excellence.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(330px, 1fr)); gap: 32px; max-width: 1080px; margin: 0 auto; align-items: stretch;">

            <!-- Jitendrabhai Parmar (Founder & CEO) -->
            <div style="background: linear-gradient(155deg, rgba(17, 24, 39, 0.98) 0%, rgba(9, 14, 26, 0.98) 100%); border: 1.5px solid rgba(245, 158, 11, 0.5); border-radius: 16px; padding: 28px 26px; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7), 0 0 35px rgba(245, 158, 11, 0.14); position: relative; display: flex; flex-direction: column; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                
                <!-- Clean Executive Top Bar -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid rgba(245, 158, 11, 0.25);">
                    <span style="display: inline-flex; align-items: center; gap: 6px; background: rgba(245, 158, 11, 0.15); border: 1px solid #f59e0b; color: #fbbf24; font-weight: 800; font-size: 11px; padding: 4px 12px; border-radius: 9999px; text-transform: uppercase; letter-spacing: 0.8px;">
                        ★ FOUNDER &amp; CEO
                    </span>
                    <span style="font-size: 11px; color: #94a3b8; font-weight: 600;">25+ Yrs Industry Veteran</span>
                </div>

                <!-- Executive Avatar & Identity -->
                <div style="display: flex; align-items: center; gap: 18px; margin-bottom: 18px;">
                    <div style="position: relative; flex-shrink: 0;">
                        <div style="width: 78px; height: 78px; border-radius: 50%; background: #070b14; border: 2.5px solid #f59e0b; padding: 3px; box-shadow: 0 0 20px rgba(245, 158, 11, 0.35); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Jitendrabhai Parmar - CEO" style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                        <span style="position: absolute; bottom: 2px; right: 2px; width: 14px; height: 14px; border-radius: 50%; background: #10b981; border: 2.5px solid #0f172a; box-shadow: 0 0 8px #10b981;" title="Director Active"></span>
                    </div>
                    <div>
                        <h3 style="font-family: var(--font-heading); font-size: 22px; font-weight: 800; color: #ffffff; margin: 0 0 3px 0; letter-spacing: 0.3px;">Jitendrabhai Parmar</h3>
                        <div style="font-size: 13.5px; font-weight: 700; color: #fbbf24; margin-bottom: 2px;">Chief Executive Officer (CEO)</div>
                        <div style="font-size: 12px; color: #94a3b8; font-weight: 500;">Founder &amp; Heavy Metallurgy Director</div>
                    </div>
                </div>

                <!-- Metric Highlights Pills -->
                <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 16px;">
                    <span style="font-size: 10.5px; font-weight: 700; background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3); color: #fcd34d; padding: 3px 8px; border-radius: 6px;">🏆 25+ Yrs Metallurgy</span>
                    <span style="font-size: 10.5px; font-weight: 700; background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.12); color: #cbd5e1; padding: 3px 8px; border-radius: 6px;">⚙️ Heavy Boring Mastery</span>
                    <span style="font-size: 10.5px; font-weight: 700; background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.12); color: #cbd5e1; padding: 3px 8px; border-radius: 6px;">🔩 Seasoned Grade-25</span>
                </div>

                <!-- Bio -->
                <p style="color: #cbd5e1; font-size: 13.5px; line-height: 1.65; margin-bottom: 20px; flex: 1;">
                    Visionary founder of Shree Varsha Engineering Works with <strong>25+ years</strong> of hands-on mastery in seasoned Grade-25 cast iron metallurgy, heavy boring machines, and industrial drill press engineering across Bhavnagar's machine-tool cluster.
                </p>

                <!-- Contact Info Box -->
                <div style="background: rgba(6, 10, 20, 0.85); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 10px; padding: 13px 16px; display: flex; flex-direction: column; gap: 9px; font-size: 13px; margin-bottom: 18px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 6px;">
                        <span style="color: #94a3b8; display: inline-flex; align-items: center; gap: 6px; font-size: 12px;">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="#34d399"><path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.02-.24 11.72 11.72 0 003.68.59 1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h3.5a1 1 0 011 1 11.72 11.72 0 00.59 3.68 1 1 0 01-.24 1.02l-2.23 2.09z"/></svg>
                            Direct Phone:
                        </span>
                        <a href="tel:+919824913555" style="color: #34d399; font-weight: 800; text-decoration: none; font-family: 'Space Grotesk', monospace; font-size: 13.5px;">+91 98249 13555</a>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 6px;">
                        <span style="color: #94a3b8; display: inline-flex; align-items: center; gap: 6px; font-size: 12px;">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="#38bdf8"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                            Email Address:
                        </span>
                        <a href="mailto:varshaengineeringworks@gmail.com" style="color: #38bdf8; font-weight: 600; text-decoration: none; word-break: break-all; font-size: 12px;">varshaengineeringworks@gmail.com</a>
                    </div>
                </div>

                <!-- WhatsApp CTA Button -->
                <div>
                    <a href="<?= getWhatsAppLink('919824913555', 'Hello Jitendrabhai Parmar (CEO), I am inquiring about Varsha Brand drill machines.') ?>" target="_blank" class="btn" style="width: 100%; justify-content: center; font-size: 13.5px; font-weight: 800; background: linear-gradient(135deg, #25D366 0%, #128C7E 100%); color: #ffffff; border: 1px solid #34d399; border-radius: 8px; padding: 12px; text-decoration: none; display: flex; align-items: center; gap: 9px; box-shadow: 0 6px 20px rgba(37, 211, 102, 0.35); transition: all 0.25s ease;">
                        <svg viewBox="0 0 24 24" width="19" height="19" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/></svg>
                        <span>WhatsApp with Jitendrabhai (CEO)</span>
                    </a>
                </div>
            </div>

            <!-- Jay Parmar (Co-CEO & Production Head) -->
            <div style="background: linear-gradient(155deg, rgba(17, 24, 39, 0.98) 0%, rgba(9, 14, 26, 0.98) 100%); border: 1.5px solid rgba(0, 229, 255, 0.5); border-radius: 16px; padding: 28px 26px; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7), 0 0 35px rgba(0, 229, 255, 0.16); position: relative; display: flex; flex-direction: column; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                
                <!-- Clean Executive Top Bar -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid rgba(0, 229, 255, 0.25);">
                    <span style="display: inline-flex; align-items: center; gap: 6px; background: rgba(0, 229, 255, 0.15); border: 1px solid #00e5ff; color: #00e5ff; font-weight: 800; font-size: 11px; padding: 4px 12px; border-radius: 9999px; text-transform: uppercase; letter-spacing: 0.8px;">
                        ★ CO-CEO &amp; PRODUCTION HEAD
                    </span>
                    <span style="font-size: 11px; color: #94a3b8; font-weight: 600;">Authorized Signatory</span>
                </div>

                <!-- Executive Avatar & Identity -->
                <div style="display: flex; align-items: center; gap: 18px; margin-bottom: 18px;">
                    <div style="position: relative; flex-shrink: 0;">
                        <div style="width: 78px; height: 78px; border-radius: 50%; background: #070b14; border: 2.5px solid var(--accent-cyan); padding: 3px; box-shadow: 0 0 20px rgba(0, 229, 255, 0.35); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Jay Parmar - Co-CEO" style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                        <span style="position: absolute; bottom: 2px; right: 2px; width: 14px; height: 14px; border-radius: 50%; background: #00e5ff; border: 2.5px solid #0f172a; box-shadow: 0 0 8px #00e5ff;" title="Operations Head Active"></span>
                    </div>
                    <div>
                        <h3 style="font-family: var(--font-heading); font-size: 22px; font-weight: 800; color: #ffffff; margin: 0 0 3px 0; letter-spacing: 0.3px;">Jay Parmar</h3>
                        <div style="font-size: 13.5px; font-weight: 700; color: var(--accent-cyan); margin-bottom: 2px;">Co-CEO &amp; Production Engineer</div>
                        <div style="font-size: 12px; color: #94a3b8; font-weight: 500;">Authorized Signatory &amp; Operations Head</div>
                    </div>
                </div>

                <!-- Metric Highlights Pills -->
                <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 16px;">
                    <span style="font-size: 10.5px; font-weight: 700; background: rgba(0, 229, 255, 0.12); border: 1px solid rgba(0, 229, 255, 0.3); color: #38bdf8; padding: 3px 8px; border-radius: 6px;">🎯 &lt;0.015mm Runout QC</span>
                    <span style="font-size: 10.5px; font-weight: 700; background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.12); color: #cbd5e1; padding: 3px 8px; border-radius: 6px;">⚡ 20MnCr5 Alloy Gears</span>
                    <span style="font-size: 10.5px; font-weight: 700; background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.12); color: #cbd5e1; padding: 3px 8px; border-radius: 6px;">📑 Certified Signatory</span>
                </div>

                <!-- Bio -->
                <p style="color: #cbd5e1; font-size: 13.5px; line-height: 1.65; margin-bottom: 20px; flex: 1;">
                    Specialized in precision spindle runout calibration (&lt;0.015mm), 20MnCr5 hardened alloy gear tooth profiling, 12D digital machinery simulations, and managing client consignments &amp; verified GST tax documentation.
                </p>

                <!-- Contact Info Box -->
                <div style="background: rgba(6, 10, 20, 0.85); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 10px; padding: 13px 16px; display: flex; flex-direction: column; gap: 9px; font-size: 13px; margin-bottom: 18px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 6px;">
                        <span style="color: #94a3b8; display: inline-flex; align-items: center; gap: 6px; font-size: 12px;">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="#34d399"><path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.02-.24 11.72 11.72 0 003.68.59 1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h3.5a1 1 0 011 1 11.72 11.72 0 00.59 3.68 1 1 0 01-.24 1.02l-2.23 2.09z"/></svg>
                            Direct Phone:
                        </span>
                        <a href="tel:+919714611331" style="color: #34d399; font-weight: 800; text-decoration: none; font-family: 'Space Grotesk', monospace; font-size: 13.5px;">+91 97146 11331</a>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 6px;">
                        <span style="color: #94a3b8; display: inline-flex; align-items: center; gap: 6px; font-size: 12px;">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="#38bdf8"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                            Email Address:
                        </span>
                        <a href="mailto:jayparmar@varshamachines.com" style="color: #38bdf8; font-weight: 600; text-decoration: none; word-break: break-all; font-size: 12px;">jayparmar@varshamachines.com</a>
                    </div>
                </div>

                <!-- WhatsApp CTA Button -->
                <div>
                    <a href="<?= getWhatsAppLink('919714611331', 'Hello Jay Parmar (Co-CEO), I am inquiring about Varsha Brand drill machines.') ?>" target="_blank" class="btn" style="width: 100%; justify-content: center; font-size: 13.5px; font-weight: 800; background: linear-gradient(135deg, #25D366 0%, #128C7E 100%); color: #ffffff; border: 1px solid #34d399; border-radius: 8px; padding: 12px; text-decoration: none; display: flex; align-items: center; gap: 9px; box-shadow: 0 6px 20px rgba(37, 211, 102, 0.35); transition: all 0.25s ease;">
                        <svg viewBox="0 0 24 24" width="19" height="19" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/></svg>
                        <span>WhatsApp with Jay Parmar (Co-CEO)</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Manufacturing Facility Highlights -->
<section class="section" style="background: rgba(11, 17, 32, 0.4); padding: 60px 0;">
    <div class="container">
        <div class="section-title-wrap" style="text-align: center; margin-bottom: 40px;">
            <div class="pill-badge">INFRASTRUCTURE</div>
            <h2 class="section-title">Our Modern <span>Manufacturing Facility</span></h2>
            <p class="section-desc" style="max-width: 680px; margin: 0 auto; color: #cbd5e1;">
                Located in the prominent industrial zone of GIDC Hadanagar, Bhavnagar, Gujarat, our facility houses specialized heavy-duty machine tools for high accuracy manufacturing.
            </p>
        </div>

        <div class="features-grid">
            <div class="feature-box" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                <div class="feature-icon" style="margin: 0 auto 16px; display: flex; align-items: center; justify-content: center;">
                    <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14H7v-2h5v2zm5-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                </div>
                <h3 class="feature-title">Heavy Boring &amp; Plano Millers</h3>
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
                <h3 class="feature-title">Cylindrical &amp; Surface Grinding</h3>
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
<section class="section" style="text-align: center; padding: 50px 0 70px;">
    <div class="container">
        <div class="pill-badge" style="background: rgba(0, 229, 255, 0.12); border-color: #00e5ff; color: #00e5ff;">
            PLAN A FACTORY VISIT
        </div>
        <h2 class="section-title" style="margin-top: 8px;">Experience Our <span>Machines in Action</span></h2>
        <p class="section-desc" style="max-width: 600px; margin: 0 auto 30px; color: #cbd5e1;">
            We cordially invite industrial clients, distributors, and workshop owners to visit our Bhavnagar manufacturing facility for live cutting trials and live demonstrations.
        </p>
        <div style="display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;">
            <a href="<?= BASE_URL ?>/contact.php" class="btn btn-primary" style="padding: 11px 24px; font-weight: 800;">Schedule Factory Visit</a>
            <a href="tel:<?= COMPANY_PHONE_1_CLEAN ?>" class="btn btn-outline" style="padding: 11px 24px; font-weight: 700;">📞 Call: <?= COMPANY_PHONE_1 ?></a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
