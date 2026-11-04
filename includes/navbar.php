<?php
// Main Glassmorphic Navigation Bar - Clean, Balanced, High-Precision Industrial Styling
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
}
.site-nav-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    max-width: 1460px;
    margin: 0 auto;
    padding: 0 20px;
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
    gap: 13px;
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
    gap: 10px;
    flex-shrink: 0;
}
.btn-nav-action {
    height: 38px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0 14px;
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
    border: 1px solid var(--border-subtle);
    color: #fff;
    padding: 8px 10px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 18px;
    align-items: center;
    justify-content: center;
}
@media (max-width: 1180px) {
    .btn-nav-mobile-toggle {
        display: inline-flex;
    }
    .nav-links-center {
        display: none;
    }
    .mobile-menu-open .nav-links-center {
        display: flex;
        flex-direction: column;
        position: absolute;
        top: 68px;
        left: 0;
        right: 0;
        background: rgba(9, 13, 22, 0.98);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        padding: 16px 20px 24px;
        border-bottom: 2px solid var(--accent-cyan);
        box-shadow: 0 25px 60px rgba(0,0,0,0.95);
        gap: 6px;
        z-index: 10002;
        max-height: calc(100vh - 75px);
        overflow-y: auto;
    }
    .mobile-menu-open .site-nav-link {
        font-size: 14.5px;
        padding: 10px 14px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
}
@media (max-width: 540px) {
    .btn-nav-action {
        height: 34px;
        padding: 0 10px;
        font-size: 11.5px;
        gap: 4px;
    }
    .brand-badge-wrap div span:last-child {
        display: none;
    }
    .brand-badge-wrap img {
        height: 36px;
        width: 36px;
    }
}
</style>

<nav class="site-navbar">
    <div class="site-nav-container">
        <!-- Logo & Brand Identity -->
        <a href="<?= BASE_URL ?>/index.php" class="brand-badge-wrap" title="<?= APP_NAME ?> - <?= BRAND_NAME ?>">
            <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="<?= BRAND_NAME ?>">
            <div style="display: flex; flex-direction: column; line-height: 1.15;">
                <span style="font-family: 'Space Grotesk', sans-serif; font-size: 16px; font-weight: 800; letter-spacing: 0.5px; color: #ffffff;">
                    VARSHA <span style="color: var(--accent-cyan);">BRAND</span>
                </span>
                <span style="font-size: 9.5px; font-weight: 700; color: #94a3b8; letter-spacing: 1px; text-transform: uppercase;">
                    BHAVNAGAR • SVEW
                </span>
            </div>
        </a>

        <!-- Desktop Navigation Menu (Ordered as specified by User) -->
        <ul class="nav-links-center">
            <li><a href="<?= BASE_URL ?>/index.php" class="site-nav-link <?= ($currentNav === 'home') ? 'active' : '' ?>">Home</a></li>
            <li><a href="<?= BASE_URL ?>/about.php" class="site-nav-link <?= ($currentNav === 'about') ? 'active' : '' ?>">About Us</a></li>
            <li><a href="<?= BASE_URL ?>/machines.php" class="site-nav-link <?= ($currentNav === 'machines') ? 'active' : '' ?>">All Machines</a></li>
            <li><a href="<?= BASE_URL ?>/catalog.php" class="site-nav-link <?= ($currentNav === 'catalog') ? 'active' : '' ?>">Catalog</a></li>
            <li>
                <a href="<?= BASE_URL ?>/simulator-6d.php" class="site-nav-link <?= ($currentNav === 'simulator') ? 'active' : '' ?>" style="color: var(--accent-cyan);">
                    <span style="font-size: 10.5px; background: rgba(0, 229, 255, 0.15); border: 1px solid #00e5ff; padding: 2px 6px; border-radius: 9999px; font-weight: 800; margin-right: 3px;">360°</span>
                    <span>12D View</span>
                </a>
            </li>
            <li><a href="<?= BASE_URL ?>/blog.php" class="site-nav-link <?= ($currentNav === 'blog') ? 'active' : '' ?>">Blog / Guides</a></li>
            <li><a href="<?= BASE_URL ?>/track-order.php" class="site-nav-link <?= ($currentNav === 'tracking') ? 'active' : '' ?>" style="color: #34d399;">🚚 Live Tracking</a></li>
            <li><a href="<?= BASE_URL ?>/faqs.php" class="site-nav-link <?= ($currentNav === 'faqs') ? 'active' : '' ?>">FAQs</a></li>
            <li><a href="<?= BASE_URL ?>/contact.php" class="site-nav-link <?= ($currentNav === 'contact') ? 'active' : '' ?>">Contact</a></li>
        </ul>

        <!-- Right Side CTA Actions -->
        <div class="nav-actions-right">
            <?php if (!empty($_SESSION['customer_user'])): ?>
                <div style="position: relative; display: inline-block;">
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
                <button type="button" onclick="openCustomerAuthModal('Please sign in or register to manage your orders.')" class="btn-nav-action btn-nav-auth">
                    <span>👤 Sign In</span>
                </button>
            <?php endif; ?>

            <a href="<?= BASE_URL ?>/checkout.php" class="btn-nav-action btn-nav-book" title="Online Machine Booking">
                <span>💳 Book Online</span>
            </a>

            <a href="<?= BASE_URL ?>/admin/index.php" class="btn-nav-action btn-nav-admin" title="Admin Control Center">
                <span>🔒 Admin</span>
            </a>

            <button type="button" class="btn-nav-mobile-toggle" onclick="document.body.classList.toggle('mobile-menu-open')" aria-label="Toggle Menu" title="Menu">
                <span>☰</span>
            </button>
        </div>
    </div>
</nav>

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
            document.body.classList.remove('mobile-menu-open');
        });
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.site-nav-container')) {
            document.body.classList.remove('mobile-menu-open');
        }
    });
});
</script>
