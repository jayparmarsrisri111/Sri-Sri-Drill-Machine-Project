<?php
// Main Glassmorphic Navigation Bar - Clean, Balanced, High-Precision Industrial Styling
// 100% Mobile & Tablet Optimized (Zero Horizontal Overflow)
?>
<style>
.site-navbar {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: rgba(6, 10, 20, 0.96);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-bottom: 1px solid rgba(0, 229, 255, 0.25);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8);
    height: 68px;
    display: flex;
    align-items: center;
    width: 100%;
}
.site-nav-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    max-width: 1460px;
    margin: 0 auto;
    padding: 0 16px;
    gap: 10px;
}
.brand-badge-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    flex-shrink: 0;
}
.brand-badge-wrap img {
    height: 40px;
    width: 40px;
    object-fit: contain;
    border-radius: 50%;
    border: 1.5px solid var(--accent-cyan);
    filter: drop-shadow(0 0 10px rgba(0, 229, 255, 0.4));
    transition: transform 0.2s ease;
}
.brand-badge-wrap:hover img {
    transform: scale(1.05);
}
.nav-links-center {
    display: flex;
    align-items: center;
    gap: 11px;
    list-style: none;
    margin: 0;
    padding: 0;
}
.site-nav-link {
    font-family: var(--font-heading);
    font-size: 12.8px;
    font-weight: 600;
    color: #cbd5e1;
    text-decoration: none;
    padding: 6px 2px;
    position: relative;
    transition: color 0.2s ease;
    white-space: nowrap;
}
.site-nav-link:hover,
.site-nav-link.active {
    color: #00e5ff;
}
.site-nav-link.active::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0;
    right: 0;
    height: 2px;
    background: #00e5ff;
    box-shadow: 0 0 8px #00e5ff;
    border-radius: 2px;
}
.nav-actions-right {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}
.btn-nav-action {
    height: 38px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0 12px;
    font-family: var(--font-heading);
    font-size: 12.5px;
    font-weight: 700;
    border-radius: 8px;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}
.btn-nav-auth {
    background: rgba(15, 23, 42, 0.7);
    border: 1px solid rgba(56, 189, 248, 0.4);
    color: #38bdf8;
}
.btn-nav-auth:hover {
    background: rgba(56, 189, 248, 0.15);
    border-color: #38bdf8;
    color: #fff;
}
.btn-nav-book {
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    border: 1px solid #34d399;
    color: #ffffff !important;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}
.btn-nav-book:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45);
}
.btn-nav-admin {
    background: rgba(15, 23, 42, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #94a3b8;
    padding: 0 10px;
}
.btn-nav-admin:hover {
    background: rgba(0, 229, 255, 0.1);
    border-color: var(--accent-cyan);
    color: var(--accent-cyan);
}
.btn-nav-mobile-toggle {
    display: none;
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid var(--border-cyan);
    color: var(--accent-cyan);
    height: 38px;
    width: 38px;
    padding: 0;
    border-radius: 8px;
    cursor: pointer;
    font-size: 20px;
    align-items: center;
    justify-content: center;
    line-height: 1;
}

/* Hide mobile-drawer action buttons completely on desktop/laptop */
.mobile-menu-actions,
.mobile-drawer-header {
    display: none !important;
}

/* Ensure floating widgets NEVER overlap mobile menu drawer */
body.mobile-menu-open #varshaChatbotLauncher,
body.mobile-menu-open .floating-contact-bar,
body.mobile-menu-open #varshaChatbotWindow {
    display: none !important;
    opacity: 0 !important;
    visibility: hidden !important;
    pointer-events: none !important;
}

.mobile-menu-backdrop {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(4, 7, 15, 0.75);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: 10001;
    opacity: 0;
    transition: opacity 0.25s ease;
}
body.mobile-menu-open .mobile-menu-backdrop {
    display: block;
    opacity: 1;
}

/* Tablet & Mobile Breakpoints */
@media (max-width: 1200px) {
    .btn-nav-mobile-toggle {
        display: inline-flex;
    }
    .nav-links-center {
        display: none;
    }
    .btn-nav-admin {
        display: inline-flex; /* Remain visible on tablet & mobile top bar */
        padding: 0 9px;
        font-size: 12px;
    }
    .mobile-menu-open .nav-links-center {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        width: 100%;
        position: absolute;
        top: 68px;
        left: 0;
        right: 0;
        background: rgba(6, 10, 20, 0.98);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        padding: 16px 18px 30px;
        border-bottom: 2px solid var(--accent-cyan);
        box-shadow: 0 25px 60px rgba(0,0,0,0.98);
        gap: 6px;
        z-index: 10002;
        max-height: calc(100vh - 72px);
        overflow-y: auto;
    }
    .mobile-menu-open .mobile-drawer-header {
        display: block !important;
        width: 100%;
    }
    .mobile-menu-open .nav-links-center > li {
        width: 100%;
        display: block;
    }
    .mobile-menu-open .site-nav-link {
        font-size: 14.5px;
        font-weight: 600;
        padding: 11px 16px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        box-sizing: border-box;
        background: rgba(15, 23, 42, 0.55);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-left: 3px solid transparent;
        color: #e2e8f0;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .mobile-menu-open .site-nav-link:hover,
    .mobile-menu-open .site-nav-link.active {
        background: linear-gradient(90deg, rgba(0, 229, 255, 0.14) 0%, rgba(15, 23, 42, 0.7) 100%);
        border-color: rgba(0, 229, 255, 0.35);
        border-left-color: #00e5ff;
        color: #00e5ff;
        box-shadow: 0 4px 15px rgba(0, 229, 255, 0.1);
    }
    .mobile-menu-open .site-nav-link.active::after {
        display: none;
    }
    .mobile-menu-open .mobile-menu-actions {
        display: flex !important;
        flex-direction: column;
        gap: 10px;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid rgba(255,255,255,0.1);
        width: 100%;
    }
}

@media (max-width: 768px) {
    .site-navbar {
        height: 62px;
    }
    .site-nav-container {
        padding: 0 12px;
    }
    .brand-badge-wrap img {
        height: 36px;
        width: 36px;
    }
    .brand-subtitle-tag {
        display: none;
    }
    .btn-top-auth {
        display: none; /* Moved into mobile drawer */
    }
    .btn-nav-action {
        height: 35px;
        padding: 0 8px;
        font-size: 12px;
    }
    .btn-top-app span.btn-text-full,
    .btn-nav-admin span.btn-text-full {
        display: none;
    }
    .mobile-menu-open .nav-links-center {
        top: 62px;
        max-height: calc(100vh - 64px);
    }
}

@media (max-width: 420px) {
    .brand-title-main {
        font-size: 14.5px !important;
    }
    .btn-top-app {
        padding: 0 7px;
    }
    .btn-top-book {
        padding: 0 9px;
        font-size: 11.5px;
    }
    .btn-top-book span.btn-text-extra {
        display: none;
    }
}
</style>

<nav class="site-navbar">
    <div class="site-nav-container">
        <!-- Logo & Brand Identity -->
        <a href="<?= BASE_URL ?>/index.php" class="brand-badge-wrap" title="<?= APP_NAME ?> - <?= BRAND_NAME ?>">
            <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="<?= BRAND_NAME ?>">
            <div style="display: flex; flex-direction: column; line-height: 1.15;">
                <span class="brand-title-main" style="font-family: 'Space Grotesk', sans-serif; font-size: 16px; font-weight: 800; letter-spacing: 0.5px; color: #ffffff;">
                    VARSHA <span style="color: var(--accent-cyan);">BRAND</span>
                </span>
                <span class="brand-subtitle-tag" style="font-size: 9.5px; font-weight: 700; color: #94a3b8; letter-spacing: 1px; text-transform: uppercase;">
                    BHAVNAGAR • SVEW
                </span>
            </div>
        </a>

        <!-- Desktop Navigation Menu (And Mobile Drawer on <= 1200px) -->
        <ul class="nav-links-center">
            <!-- Mobile Drawer Header -->
            <li class="mobile-drawer-header">
                <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 12px; margin-bottom: 6px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-family: 'Space Grotesk', sans-serif; font-size: 13px; font-weight: 800; color: #00e5ff; letter-spacing: 0.8px;">⚡ NAVIGATION MENU</span>
                    </div>
                    <button type="button" onclick="document.body.classList.remove('mobile-menu-open')" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #fff; width: 32px; height: 32px; border-radius: 8px; font-size: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center;" aria-label="Close Menu">&times;</button>
                </div>
            </li>

            <li><a href="<?= BASE_URL ?>/index.php" class="site-nav-link <?= ($currentNav === 'home') ? 'active' : '' ?>"><span>🏠 Home</span></a></li>
            <li><a href="<?= BASE_URL ?>/about.php" class="site-nav-link <?= ($currentNav === 'about') ? 'active' : '' ?>"><span>🏭 About Us</span></a></li>
            <li><a href="<?= BASE_URL ?>/machines.php" class="site-nav-link <?= ($currentNav === 'machines') ? 'active' : '' ?>"><span>⚙️ All Machines</span></a></li>
            <li><a href="<?= BASE_URL ?>/catalog.php" class="site-nav-link <?= ($currentNav === 'catalog') ? 'active' : '' ?>"><span>📖 Catalog &amp; PDF</span></a></li>
            <li>
                <a href="<?= BASE_URL ?>/simulator-6d.php" class="site-nav-link <?= ($currentNav === 'simulator') ? 'active' : '' ?>" style="color: var(--accent-cyan);">
                    <span>🔄 360° 12D View</span>
                    <span style="font-size: 10px; background: rgba(0, 229, 255, 0.15); border: 1px solid #00e5ff; padding: 2px 7px; border-radius: 9999px; font-weight: 800;">3D LAB</span>
                </a>
            </li>
            <li><a href="<?= BASE_URL ?>/blog.php" class="site-nav-link <?= ($currentNav === 'blog') ? 'active' : '' ?>"><span>📚 Blog / Guides</span></a></li>
            <li><a href="<?= BASE_URL ?>/track-order.php" class="site-nav-link <?= ($currentNav === 'tracking') ? 'active' : '' ?>" style="color: #34d399;"><span>🚚 Live Tracking</span><span style="display:inline-block; width:7px; height:7px; border-radius:50%; background:#10b981; box-shadow:0 0 6px #10b981;"></span></a></li>
            <li><a href="<?= BASE_URL ?>/faqs.php" class="site-nav-link <?= ($currentNav === 'faqs') ? 'active' : '' ?>"><span>💡 FAQs &amp; Help</span></a></li>
            <li><a href="<?= BASE_URL ?>/contact.php" class="site-nav-link <?= ($currentNav === 'contact') ? 'active' : '' ?>"><span>📍 Factory Contact</span></a></li>
            <li>
                <a href="#" onclick="openAppDownloadModal(); return false;" class="site-nav-link btn-download-app-trigger" style="color: #38bdf8; font-weight: 700;">
                    <span>📲 Download App (PWA)</span>
                </a>
            </li>

            <!-- Mobile Drawer Exclusive Action Buttons (Visible only when menu is open) -->
            <li class="mobile-menu-actions">
                <?php if (!empty($_SESSION['customer_user'])): ?>
                    <div style="background: rgba(15, 23, 42, 0.9); border: 1.5px solid #38bdf8; border-radius: 10px; padding: 12px 14px; box-shadow: 0 4px 20px rgba(0,0,0,0.5);">
                        <div style="font-size: 11px; color: #94a3b8;">Signed in as:</div>
                        <div style="font-weight: 700; color: #fff; font-size: 14px; margin-bottom: 8px;">👤 <?= e($_SESSION['customer_user']['full_name']) ?></div>
                        <div style="display: flex; gap: 8px;">
                            <a href="<?= BASE_URL ?>/track-order.php" class="btn btn-outline" style="flex: 1; padding: 7px 10px; font-size: 11.5px; border-color: #34d399; color: #34d399; text-align: center; text-decoration: none;">
                                🚚 Orders
                            </a>
                            <a href="<?= BASE_URL ?>/invoice.php" class="btn btn-outline" style="flex: 1; padding: 7px 10px; font-size: 11.5px; border-color: #cbd5e1; color: #cbd5e1; text-align: center; text-decoration: none;">
                                📄 Bills
                            </a>
                            <a href="<?= BASE_URL ?>/logout-customer.php" class="btn btn-outline" style="padding: 7px 10px; font-size: 11.5px; border-color: #f87171; color: #f87171; text-align: center; text-decoration: none;">
                                🚪 Out
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <button type="button" onclick="document.body.classList.remove('mobile-menu-open'); openCustomerAuthModal('Please sign in or register to manage your orders.');" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 13.5px; font-weight: 800; background: rgba(15, 23, 42, 0.95); border: 1.5px solid #38bdf8; color: #38bdf8; border-radius: 8px; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 15px rgba(56, 189, 248, 0.2);">
                        <span>👤 CUSTOMER SIGN IN / REGISTER</span>
                    </button>
                <?php endif; ?>

                <a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 13.5px; font-weight: 800; background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399; color: #fff; border-radius: 8px; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none;">
                    <span>💳 Book Machine Online (₹25,000 Advance)</span>
                </a>

                <button type="button" onclick="document.body.classList.remove('mobile-menu-open'); openAppDownloadModal();" class="btn btn-primary" style="width: 100%; padding: 11px; font-size: 13px; font-weight: 800; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color: #060911; border-color: #00e5ff; border-radius: 8px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <span>📲 Install Varsha App (Android/PC)</span>
                </button>

                <a href="<?= BASE_URL ?>/admin/index.php" class="btn btn-outline" style="width: 100%; padding: 11px; font-size: 13px; font-weight: 800; border-color: #f59e0b; color: #fbbf24; background: rgba(245, 158, 11, 0.12); border-radius: 8px; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none;">
                    <span>🔒 Factory Admin Portal (Management)</span>
                </a>
            </li>
        </ul>

        <!-- Right Side Quick Actions -->
        <div class="nav-actions-right">
            <!-- PWA App Download Button -->
            <button type="button" onclick="openAppDownloadModal()" class="btn-nav-action btn-top-app btn-download-app-trigger" title="Download Varsha Drill App for Android, PC &amp; iOS" style="border-color: #38bdf8; color: #38bdf8; background: rgba(56, 189, 248, 0.12); font-weight: 700;">
                <span>📲</span>
                <span class="btn-text-full">App</span>
            </button>

            <!-- Customer Sign In Button (Top bar) -->
            <?php if (!empty($_SESSION['customer_user'])): ?>
                <div class="btn-top-auth" style="position: relative; display: inline-block;">
                    <button type="button" id="btnCustomerDropdown" class="btn-nav-action btn-nav-auth" style="border-color: #38bdf8;">
                        <span>👤 <?= e(explode(' ', $_SESSION['customer_user']['full_name'])[0]) ?></span>
                        <span style="font-size: 9px; margin-left: 2px;">▼</span>
                    </button>
                    <div id="customerNavMenu" style="display: none; position: absolute; right: 0; top: 110%; background: #090d16; border: 1.5px solid var(--accent-cyan); border-radius: 10px; width: 210px; padding: 8px 0; box-shadow: 0 15px 40px rgba(0,0,0,0.9); z-index: 10005;">
                        <div style="padding: 8px 14px; font-size: 11.5px; color: #94a3b8; border-bottom: 1px solid rgba(255,255,255,0.08);">
                            Signed in as<br><strong style="color: #fff; font-size: 13px;"><?= e($_SESSION['customer_user']['full_name']) ?></strong>
                        </div>
                        <a href="<?= BASE_URL ?>/track-order.php" style="display: block; padding: 9px 14px; font-size: 12.5px; color: #34d399; text-decoration: none; font-weight: 600;">
                            🚚 Track My Orders
                        </a>
                        <a href="<?= BASE_URL ?>/invoice.php" style="display: block; padding: 9px 14px; font-size: 12.5px; color: #cbd5e1; text-decoration: none;">
                            📄 View My Tax Bills
                        </a>
                        <div style="border-top: 1px solid rgba(255,255,255,0.08); margin: 4px 0;"></div>
                        <a href="<?= BASE_URL ?>/logout-customer.php" style="display: block; padding: 9px 14px; font-size: 12px; color: #f87171; text-decoration: none; font-weight: 600;">
                            🚪 Sign Out
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <button type="button" onclick="openCustomerAuthModal('Please sign in or register to manage your orders.')" class="btn-nav-action btn-nav-auth btn-top-auth">
                    <span>👤 Sign In</span>
                </button>
            <?php endif; ?>

            <!-- Book Online CTA -->
            <a href="<?= BASE_URL ?>/checkout.php" class="btn-nav-action btn-nav-book btn-top-book" title="Online Machine Booking">
                <span>💳 Book<span class="btn-text-extra"> Online</span></span>
            </a>

            <!-- Admin Button (Accessible on both desktop and mobile top bar) -->
            <a href="<?= BASE_URL ?>/admin/index.php" class="btn-nav-action btn-nav-admin" title="Factory Admin Control Center">
                <span>🔒</span>
                <span class="btn-text-full">Admin</span>
            </a>

            <!-- Mobile Hamburger Toggle -->
            <button type="button" class="btn-nav-mobile-toggle" onclick="document.body.classList.toggle('mobile-menu-open')" aria-label="Toggle Menu" title="Menu">
                <span>☰</span>
            </button>
        </div>
    </div>
</nav>
<!-- Mobile Drawer Backdrop Overlay -->
<div class="mobile-menu-backdrop" onclick="document.body.classList.remove('mobile-menu-open')"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnUser = document.getElementById('btnCustomerDropdown');
    const menuUser = document.getElementById('customerNavMenu');
    if (btnUser && menuUser) {
        btnUser.addEventListener('click', function(e) {
            e.stopPropagation();
            menuUser.style.display = (menuUser.style.display === 'block') ? 'none' : 'block';
        });
        document.addEventListener('click', function() {
            menuUser.style.display = 'none';
        });
    }

    // Auto-close mobile menu on navigation link click or click outside
    document.querySelectorAll('.site-nav-link').forEach(link => {
        link.addEventListener('click', function() {
            if (!this.classList.contains('btn-download-app-trigger')) {
                document.body.classList.remove('mobile-menu-open');
            }
        });
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.site-nav-container')) {
            document.body.classList.remove('mobile-menu-open');
        }
    });
});
</script>