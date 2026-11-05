<?php
// Main Glassmorphic Navigation Bar - Clean, Balanced, High-Precision Industrial Styling
// 100% Mobile & Tablet Optimized (Zero Horizontal Overflow, Standalone Slide Drawer)
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
    background: rgba(15, 23, 42, 0.85);
    border: 1.5px solid var(--accent-cyan);
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
    transition: all 0.2s ease;
}
.btn-nav-mobile-toggle:hover {
    background: rgba(0, 229, 255, 0.15);
    transform: scale(1.05);
}

/* ==========================================================================
   DEDICATED STANDALONE MOBILE DRAWER & BACKDROP (100% Guaranteed Non-Blank)
   ========================================================================== */
.site-mobile-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(4, 7, 15, 0.85);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: 10002;
    opacity: 0;
    transition: opacity 0.25s ease;
}
body.mobile-menu-open .site-mobile-backdrop {
    display: block;
    opacity: 1;
}

.site-mobile-drawer {
    display: none;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    width: 330px;
    max-width: 88vw;
    height: 100vh;
    height: 100dvh;
    background: linear-gradient(180deg, #090e1c 0%, #050811 100%);
    border-left: 2px solid var(--accent-cyan);
    box-shadow: -15px 0 50px rgba(0, 0, 0, 0.95);
    z-index: 10005;
    transform: translateX(105%);
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    overflow-y: auto;
    flex-direction: column;
    padding: 16px 18px 30px;
    box-sizing: border-box;
}
body.mobile-menu-open .site-mobile-drawer {
    display: flex !important;
    transform: translateX(0) !important;
}

/* Hide floating launchers when mobile drawer is open to eliminate overlap */
body.mobile-menu-open #varshaChatbotLauncher,
body.mobile-menu-open .floating-contact-bar,
body.mobile-menu-open #varshaChatbotWindow {
    display: none !important;
    opacity: 0 !important;
    visibility: hidden !important;
    pointer-events: none !important;
}

.drawer-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 14px;
    margin-bottom: 12px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}
.drawer-title {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 14px;
    font-weight: 800;
    color: #00e5ff;
    letter-spacing: 0.8px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.drawer-close-btn {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #fff;
    width: 34px;
    height: 34px;
    border-radius: 8px;
    font-size: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}
.drawer-close-btn:hover {
    background: rgba(239, 68, 68, 0.2);
    border-color: #ef4444;
    color: #ef4444;
}

.drawer-nav-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 6px;
    flex: 1;
}
.drawer-nav-item a {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    border-radius: 8px;
    font-family: var(--font-heading);
    font-size: 14px;
    font-weight: 600;
    color: #e2e8f0;
    text-decoration: none;
    background: rgba(15, 23, 42, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-left: 3px solid transparent;
    transition: all 0.2s ease;
}
.drawer-nav-item a:hover,
.drawer-nav-item a.active {
    background: linear-gradient(90deg, rgba(0, 229, 255, 0.14) 0%, rgba(15, 23, 42, 0.7) 100%);
    border-color: rgba(0, 229, 255, 0.35);
    border-left-color: #00e5ff;
    color: #00e5ff;
}

.drawer-actions-box {
    margin-top: 18px;
    padding-top: 16px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    flex-direction: column;
    gap: 10px;
}

/* Tablet & Mobile Breakpoints */
@media (max-width: 1200px) {
    .btn-nav-mobile-toggle {
        display: inline-flex;
    }
    .nav-links-center {
        display: none !important;
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
        display: none;
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

        <!-- Desktop Navigation Menu (Visible on > 1200px) -->
        <ul class="nav-links-center">
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

            <!-- Mobile Hamburger Toggle (3 Lines) -->
            <button type="button" id="btnSiteMobileToggle" class="btn-nav-mobile-toggle" onclick="toggleSiteMobileDrawer()" aria-label="Toggle Menu" title="Menu">
                <span>☰</span>
            </button>
        </div>
    </div>
</nav>

<!-- =========================================================================
     STANDALONE FIXED MOBILE DRAWER (Guaranteed Always Visible & Richly Styled)
     ========================================================================= -->
<div id="siteMobileBackdrop" class="site-mobile-backdrop" onclick="closeSiteMobileDrawer()"></div>

<aside id="siteMobileDrawer" class="site-mobile-drawer" aria-label="Mobile Navigation Drawer">
    <!-- Drawer Top Header -->
    <div class="drawer-header">
        <div class="drawer-title">
            <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Logo" style="width: 28px; height: 28px; border-radius: 50%; border: 1px solid var(--accent-cyan); object-fit: cover;">
            <span>⚡ VARSHA MENU</span>
        </div>
        <button type="button" class="drawer-close-btn" onclick="closeSiteMobileDrawer()" aria-label="Close Navigation Menu">&times;</button>
    </div>

    <!-- Drawer Navigation Links -->
    <ul class="drawer-nav-list">
        <li class="drawer-nav-item"><a href="<?= BASE_URL ?>/index.php" class="<?= ($currentNav === 'home') ? 'active' : '' ?>"><span>🏠 Home</span><span>➔</span></a></li>
        <li class="drawer-nav-item"><a href="<?= BASE_URL ?>/about.php" class="<?= ($currentNav === 'about') ? 'active' : '' ?>"><span>🏭 About Us</span><span>➔</span></a></li>
        <li class="drawer-nav-item"><a href="<?= BASE_URL ?>/machines.php" class="<?= ($currentNav === 'machines') ? 'active' : '' ?>"><span>⚙️ All Machines</span><span>➔</span></a></li>
        <li class="drawer-nav-item"><a href="<?= BASE_URL ?>/catalog.php" class="<?= ($currentNav === 'catalog') ? 'active' : '' ?>"><span>📖 Catalog &amp; PDF</span><span>➔</span></a></li>
        <li class="drawer-nav-item"><a href="<?= BASE_URL ?>/simulator-6d.php" class="<?= ($currentNav === 'simulator') ? 'active' : '' ?>" style="color: var(--accent-cyan);"><span>🔄 360° 12D View</span><span style="font-size:10px; background:rgba(0,229,255,0.2); border:1px solid #00e5ff; padding:2px 6px; border-radius:6px;">3D LAB</span></a></li>
        <li class="drawer-nav-item"><a href="<?= BASE_URL ?>/blog.php" class="<?= ($currentNav === 'blog') ? 'active' : '' ?>"><span>📚 Blog / Guides</span><span>➔</span></a></li>
        <li class="drawer-nav-item"><a href="<?= BASE_URL ?>/track-order.php" class="<?= ($currentNav === 'tracking') ? 'active' : '' ?>" style="color: #34d399;"><span>🚚 Live Tracking</span><span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#10b981;"></span></a></li>
        <li class="drawer-nav-item"><a href="<?= BASE_URL ?>/faqs.php" class="<?= ($currentNav === 'faqs') ? 'active' : '' ?>"><span>💡 FAQs &amp; Help</span><span>➔</span></a></li>
        <li class="drawer-nav-item"><a href="<?= BASE_URL ?>/contact.php" class="<?= ($currentNav === 'contact') ? 'active' : '' ?>"><span>📍 Factory Contact</span><span>➔</span></a></li>
        <li class="drawer-nav-item"><a href="#" onclick="closeSiteMobileDrawer(); openAppDownloadModal(); return false;" style="color: #38bdf8; font-weight: 700;"><span>📲 Download App (PWA)</span><span>➔</span></a></li>
    </ul>

    <!-- Drawer Action Buttons -->
    <div class="drawer-actions-box">
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
            <button type="button" onclick="closeSiteMobileDrawer(); openCustomerAuthModal('Please sign in or register to manage your orders.');" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 13.5px; font-weight: 800; background: rgba(15, 23, 42, 0.95); border: 1.5px solid #38bdf8; color: #38bdf8; border-radius: 8px; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 15px rgba(56, 189, 248, 0.2);">
                <span>👤 CUSTOMER SIGN IN / REGISTER</span>
            </button>
        <?php endif; ?>

        <a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 13.5px; font-weight: 800; background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399; color: #fff; border-radius: 8px; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none;">
            <span>💳 Book Machine Online (₹25,000 Advance)</span>
        </a>

        <a href="<?= BASE_URL ?>/admin/index.php" class="btn btn-outline" style="width: 100%; padding: 11px; font-size: 13px; font-weight: 800; border-color: #f59e0b; color: #fbbf24; background: rgba(245, 158, 11, 0.12); border-radius: 8px; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none;">
            <span>🔒 Factory Admin Portal</span>
        </a>
    </div>
</aside>

<script>
function toggleSiteMobileDrawer() {
    document.body.classList.toggle('mobile-menu-open');
}
function closeSiteMobileDrawer() {
    document.body.classList.remove('mobile-menu-open');
}

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

    // Auto-close on link navigation
    document.querySelectorAll('.drawer-nav-item a').forEach(link => {
        link.addEventListener('click', function() {
            closeSiteMobileDrawer();
        });
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSiteMobileDrawer();
        }
    });
});
</script>
