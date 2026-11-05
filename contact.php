<?php
$pageTitle = 'Contact Us | Shree Varsha Engineering Works - Bhavnagar';
$pageDesc = 'Contact Shree Varsha Engineering Works. Call +91 9824913555 / +91 9714611331 or email jitubhaivarsh@gmail.com for drill machine quotation and factory visits at Bhavnagar, Gujarat.';
$currentNav = 'contact';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Contact Header -->
<section style="padding: 60px 0 45px; background: radial-gradient(circle at 50% 20%, rgba(0, 229, 255, 0.12) 0%, rgba(15, 23, 42, 0.95) 60%, #060911 100%); border-bottom: 1px solid rgba(0, 229, 255, 0.2);">
    <div class="container" style="text-align: center;">
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; margin-bottom: 16px;">
            <div style="display: inline-flex; align-items: center; gap: 12px; background: rgba(15, 23, 42, 0.85); border: 1.5px solid rgba(0, 229, 255, 0.4); padding: 6px 20px 6px 8px; border-radius: 9999px; box-shadow: 0 0 25px rgba(0, 229, 255, 0.2);">
                <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand" style="height: 42px; width: 42px; border-radius: 50%; border: 1.5px solid var(--accent-cyan); object-fit: cover;">
                <span style="font-family: 'Space Grotesk', sans-serif; font-size: 13px; font-weight: 800; color: #ffffff; letter-spacing: 0.8px;">
                    GET IN TOUCH WITH OUR FACTORY • <span style="color: var(--accent-cyan);">BHAVNAGAR PLANT</span>
                </span>
            </div>
        </div>
        <h1 class="section-title">Contact <span>Shree Varsha Engineering</span></h1>
        <p class="section-desc" style="max-width: 720px; margin: 0 auto; color: #cbd5e1; font-size: 15.5px; line-height: 1.6;">
            Reach out directly to <strong>Jitendrabhai Parmar</strong> for technical queries, price quotations, machine customization, or spare parts dispatch.
        </p>
    </div>
</section>

<!-- Main Contact Section -->
<section class="section">
    <div class="container">
        <div class="contact-grid">
            <!-- Left Info Panel -->
            <div class="contact-info-panel">
                <span class="pill-badge" style="margin-bottom: 12px;">DIRECT FACTORY ASSISTANCE</span>
                <h2 style="font-family: var(--font-heading); font-size: 26px; color: #fff; margin-bottom: 10px;">
                    <?= APP_NAME ?>
                </h2>
                <p style="color: var(--text-secondary); font-size: 14.5px; line-height: 1.6; margin-bottom: 24px;">
                    We are located in Bhavnagar, Gujarat — known for heavy engineering casting, machine tools, and manufacturing excellence. Feel free to call us or visit our factory works.
                </p>

                <div class="contact-card-list">
                    <div class="contact-channel">
                        <div class="channel-icon">
                            <svg viewBox="0 0 24 24"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                        </div>
                        <div class="channel-content">
                            <h4>Phone & WhatsApp</h4>
                            <p><a href="tel:<?= COMPANY_PHONE_1_CLEAN ?>"><?= COMPANY_PHONE_1 ?></a> (Jitendrabhai Parmar)</p>
                            <p><a href="tel:<?= COMPANY_PHONE_2_CLEAN ?>"><?= COMPANY_PHONE_2 ?></a> (Jay Parmar)</p>
                        </div>
                    </div>

                    <div class="contact-channel">
                        <div class="channel-icon">
                            <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        </div>
                        <div class="channel-content">
                            <h4>Email Address</h4>
                            <p><a href="mailto:<?= COMPANY_EMAIL ?>"><?= COMPANY_EMAIL ?></a></p>
                        </div>
                    </div>

                    <div class="contact-channel">
                        <div class="channel-icon">
                            <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        </div>
                        <div class="channel-content">
                            <h4>Factory &amp; Works Address</h4>
                            <p><?= COMPANY_ADDRESS ?></p>
                            <div style="font-size: 12px; color: #38bdf8; font-weight: 700; margin-top: 4px;">GSTIN: <?= COMPANY_GSTIN ?></div>
                        </div>
                    </div>

                    <div class="contact-channel">
                        <div class="channel-icon">
                            <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                        </div>
                        <div class="channel-content">
                            <h4>Working Hours</h4>
                            <p>Monday - Saturday: 8:30 AM to 8:00 PM</p>
                            <p style="color: var(--text-muted); font-size: 13px;">Sunday: Prior Appointment Only</p>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 30px; display: flex; gap: 12px; flex-wrap: wrap;">
                    <a href="<?= getWhatsAppLink(COMPANY_WHATSAPP, 'Hello Shree Varsha Engineering Works, I would like to get in touch regarding your drill machines.') ?>" target="_blank" class="btn btn-whatsapp" style="flex: 1;">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/></svg>
                        WhatsApp
                    </a>
                    <a href="tel:<?= COMPANY_PHONE_1_CLEAN ?>" class="btn btn-outline" style="flex: 1;">
                        Call <?= COMPANY_PHONE_1 ?>
                    </a>
                </div>
            </div>

            <!-- Right: Interactive Inquiry Form -->
            <div class="inquiry-form-card">
                <h3 style="font-family: var(--font-heading); font-size: 22px; color: #fff; margin-bottom: 6px;">
                    Request Instant Quotation / Send Message
                </h3>
                <p style="font-size: 13.5px; color: var(--text-muted); margin-bottom: 22px;">
                    Fill out the form below. Our technical engineering team will review your specifications and contact you immediately.
                </p>

                <form action="<?= BASE_URL ?>/get-quote.php" method="POST" class="inquiry-form">
                    <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">

                    <div class="form-group">
                        <label class="form-label">Your Full Name *</label>
                        <input type="text" name="customer_name" class="form-control" placeholder="e.g. Ramesh Patel" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Company / Workshop Name</label>
                            <input type="text" name="company_name" class="form-control" placeholder="e.g. Patel Engineering Works">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone / Mobile Number *</label>
                            <input type="tel" name="phone" class="form-control" placeholder="e.g. +91 98980 12345" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control" placeholder="e.g. info@patelengg.com" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Machine Model of Interest</label>
                            <select name="machine_id" class="form-control">
                                <option value="">-- Select Drill Machine --</option>
                                <option value="1">SVEW-RD-40 (40mm All-Geared Radial Drill)</option>
                                <option value="2">SVEW-RD-50 (50mm Heavy Duty Radial Drill)</option>
                                <option value="3">SVEW-RD-65 (65mm Extra Heavy Radial Drill)</option>
                                <option value="4">SVEW-PD-25 (25mm Pillar Drill Machine)</option>
                                <option value="5">SVEW-PD-32 (32mm Geared Pillar Drill)</option>
                                <option value="7">SVEW-BD-20 (20mm High Speed Bench Drill)</option>
                                <option value="8">SVEW-TP-25 (25mm Auto-Reverse Tapping Machine)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Detailed Requirement / Questions *</label>
                        <textarea name="message" class="form-control" rows="4" placeholder="Please specify your drilling capacity, work piece material, spindle requirements, and your delivery city..." required></textarea>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #cbd5e1; cursor: pointer;">
                            <input type="checkbox" name="open_whatsapp" value="1" checked style="accent-color: var(--accent-cyan);">
                            <span>Connect me on WhatsApp with Jitendrabhai Parmar for instant response</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        ⚡ Submit Factory Inquiry
                    </button>
                </form>
            </div>
        </div>

        <!-- Live Interactive Factory Map (Google Maps Bhavnagar Hub) - Matching Live Location Reference -->
        <style>
        .live-location-container {
            margin-top: 45px;
            border-radius: var(--radius-lg);
            overflow: hidden;
            border: 1.5px solid var(--border-cyan);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.6);
            background: #0b1120;
        }
        .live-location-header {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.98) 0%, rgba(6, 10, 20, 0.98) 100%);
            padding: 20px 24px;
            border-bottom: 1px solid rgba(0, 229, 255, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 14px;
        }
        .map-floating-card {
            position: absolute;
            bottom: 20px;
            left: 20px;
            background: rgba(11, 17, 32, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1.5px solid var(--accent-cyan);
            border-radius: 12px;
            padding: 16px 20px;
            max-width: 360px;
            box-shadow: 0 12px 36px rgba(0,0,0,0.85);
            z-index: 10;
        }
        .map-view-toggle-btn {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #cbd5e1;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }
        .map-view-toggle-btn.active {
            background: rgba(0, 229, 255, 0.2);
            border-color: var(--accent-cyan);
            color: var(--accent-cyan);
        }
        @media (max-width: 768px) {
            .live-location-container {
                margin-top: 30px;
            }
            .live-location-header {
                padding: 16px 18px;
            }
            #factoryLiveMap {
                height: 380px !important;
            }
            #googleMapIframe {
                height: 380px !important;
            }
            .map-floating-card {
                position: relative !important;
                bottom: auto !important;
                left: auto !important;
                max-width: 100% !important;
                margin: 12px !important;
                border-radius: 10px !important;
                padding: 14px 16px !important;
            }
        }
        </style>

        <div class="live-location-container">
            <div class="live-location-header">
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                        <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #10b981; box-shadow: 0 0 12px #10b981; animation: pulse 1.8s infinite;"></span>
                        <h2 style="font-family: var(--font-heading); font-size: 24px; font-weight: 800; color: #fff; margin: 0; letter-spacing: 0.5px;">
                            Live Location <span style="font-size: 16px; color: var(--accent-cyan); font-weight: 600;">(લાઈવ લોકેશન)</span>
                        </h2>
                    </div>
                    <span style="font-size: 13.5px; color: #94a3b8; display: block;">
                        📍 Shed No. L/786, GIDC, Nr. Hadanagar, Bhavnagar - 36400003, Gujarat, India
                    </span>
                </div>

                <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                    <button type="button" id="btnSatelliteMode" class="map-view-toggle-btn active" onclick="setMapMode('k')">
                        🛰️ Satellite View
                    </button>
                    <button type="button" id="btnRoadmapMode" class="map-view-toggle-btn" onclick="setMapMode('m')">
                        🗺️ Road Map
                    </button>
                    <a href="https://www.google.com/maps/search/?api=1&query=Shed+No.+L%2F786%2C+GIDC%2C+Nr.+Hadanagar%2C+Bhavnagar+-+36400003%2C+Gujarat" target="_blank" class="btn btn-outline" style="font-size: 12px; padding: 6px 14px; border-color: #38bdf8; color: #38bdf8; font-weight: 700;">
                        ↗ Open Fullscreen
                    </a>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=21.7645,72.1519" target="_blank" class="btn btn-primary" style="font-size: 12px; padding: 6px 16px; font-weight: 700;">
                        🚗 Get Directions
                    </a>
                </div>
            </div>

            <!-- 100% Guaranteed Live Google Maps & Satellite Directions Centered on Bhavnagar -->
            <div id="factoryLiveMap" style="width: 100%; height: 460px; background: #0b1120; position: relative; z-index: 1; border-radius: 0 0 12px 12px; overflow: hidden;">
                <iframe 
                    id="googleMapIframe"
                    src="https://maps.google.com/maps?q=21.7645,72.1519+(Shree+Varsha+Engineering+Works+Hadanagar+GIDC+Bhavnagar)&t=k&z=15&ie=UTF8&iwloc=B&output=embed" 
                    width="100%" 
                    height="460" 
                    style="border: 0; display: block;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
                
                <!-- Info Card on Map (Floats on Desktop, Neatly Sits Below on Mobile) -->
                <div class="map-floating-card">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                        <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #10b981; box-shadow: 0 0 8px #10b981;"></span>
                        <span style="font-family: 'Space Grotesk', sans-serif; font-size: 13.5px; font-weight: 800; color: #fff;">
                            SHREE VARSHA ENGINEERING WORKS
                        </span>
                    </div>
                    <div style="font-size: 11.5px; color: #38bdf8; font-weight: 700; margin: 2px 0 6px;">
                        VARSHA BRAND Drill Machine Manufacturing Works
                    </div>
                    <div style="font-size: 12px; color: #cbd5e1; line-height: 1.45;">
                        Shed No. L/786, GIDC, Nr. Hadanagar, Bhavnagar - 36400003, Gujarat
                    </div>
                    <div style="font-size: 12px; color: #34d399; font-weight: 700; margin-top: 8px; display: flex; align-items: center; justify-content: space-between;">
                        <a href="tel:<?= COMPANY_PHONE_1_CLEAN ?>" style="color: #34d399; text-decoration: none;">📞 +91 9824913555</a>
                        <a href="https://www.google.com/maps/search/?api=1&query=Shed+No.+L%2F786%2C+GIDC%2C+Nr.+Hadanagar%2C+Bhavnagar+-+36400003%2C+Gujarat" target="_blank" style="color: var(--accent-cyan); text-decoration: none; font-size: 11px;">Navigate ➔</a>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function setMapMode(mode) {
            const iframe = document.getElementById('googleMapIframe');
            const btnSat = document.getElementById('btnSatelliteMode');
            const btnRoad = document.getElementById('btnRoadmapMode');
            if (iframe) {
                iframe.src = `https://maps.google.com/maps?q=21.7645,72.1519+(Shree+Varsha+Engineering+Works+Hadanagar+GIDC+Bhavnagar)&t=${mode}&z=15&ie=UTF8&iwloc=B&output=embed`;
            }
            if (mode === 'k') {
                btnSat.classList.add('active');
                btnRoad.classList.remove('active');
            } else {
                btnRoad.classList.add('active');
                btnSat.classList.remove('active');
            }
        }
        </script>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
