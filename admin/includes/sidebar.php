<!-- Admin Left Sidebar -->
<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-header" style="justify-content: space-between; padding: 18px 20px;">
        <a href="<?= BASE_URL ?>/admin/dashboard.php" style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
            <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand" style="height: 40px; width: 40px; border-radius: 50%; border: 1.5px solid var(--accent-cyan); object-fit: contain;">
            <div>
                <span style="font-family: 'Space Grotesk', sans-serif; font-weight: 800; font-size: 1.05rem; color: #fff; letter-spacing: 0.5px; display: block; line-height: 1.1;">VARSHA <span style="color: var(--accent-cyan);">ADMIN</span></span>
                <span style="font-size: 0.65rem; color: #94a3b8; text-transform: uppercase;">Control Center</span>
            </div>
        </a>
        <button type="button" id="adminSidebarClose" class="admin-sidebar-close-btn" aria-label="Close Sidebar">
            &times;
        </button>
    </div>

    <!-- Admin Panel Multi-Language Selector (English, Gujarati, Hindi) -->
    <?php 
        // Suppress dropdown button inside sidebar since we render the 3 pills below
        $renderLangDropdown = false;
        require_once __DIR__ . '/../../includes/lang-selector.php'; 
    ?>
    <div class="notranslate" style="padding: 10px 12px; margin: 4px 12px 14px; background: rgba(15, 23, 42, 0.9); border: 1px solid rgba(0, 229, 255, 0.3); border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 7px;">
            <span style="font-size: 11px; font-weight: 800; color: #38bdf8; text-transform: uppercase; letter-spacing: 0.5px;">
                🌐 LANGUAGE / ભાષા
            </span>
            <span style="font-size: 10px; color: #94a3b8; font-weight: 600;">
                <?= strtoupper($currentLang) ?>
            </span>
        </div>
        <div style="display: flex; gap: 5px; justify-content: space-between;">
            <button type="button" onclick="changeSiteLanguage('en')" class="notranslate" style="flex: 1; padding: 7px 4px; font-size: 11px; font-weight: 700; border-radius: 6px; border: 1.5px solid <?= ($currentLang === 'en') ? '#10b981' : 'rgba(255,255,255,0.15)' ?>; background: <?= ($currentLang === 'en') ? 'rgba(16, 185, 129, 0.25)' : 'rgba(255,255,255,0.05)' ?>; color: <?= ($currentLang === 'en') ? '#34d399' : '#cbd5e1' ?>; cursor: pointer; text-align: center; transition: all 0.2s;">
                English
            </button>
            <button type="button" onclick="changeSiteLanguage('gu')" class="notranslate" style="flex: 1.2; padding: 7px 4px; font-size: 11px; font-weight: 700; border-radius: 6px; border: 1.5px solid <?= ($currentLang === 'gu') ? '#10b981' : 'rgba(255,255,255,0.15)' ?>; background: <?= ($currentLang === 'gu') ? 'rgba(16, 185, 129, 0.25)' : 'rgba(255,255,255,0.05)' ?>; color: <?= ($currentLang === 'gu') ? '#34d399' : '#cbd5e1' ?>; cursor: pointer; text-align: center; transition: all 0.2s;">
                ગુજરાતી
            </button>
            <button type="button" onclick="changeSiteLanguage('hi')" class="notranslate" style="flex: 1; padding: 7px 4px; font-size: 11px; font-weight: 700; border-radius: 6px; border: 1.5px solid <?= ($currentLang === 'hi') ? '#10b981' : 'rgba(255,255,255,0.15)' ?>; background: <?= ($currentLang === 'hi') ? 'rgba(16, 185, 129, 0.25)' : 'rgba(255,255,255,0.05)' ?>; color: <?= ($currentLang === 'hi') ? '#34d399' : '#cbd5e1' ?>; cursor: pointer; text-align: center; transition: all 0.2s;">
                हिन्दी
            </button>
        </div>
    </div>

    <ul class="sidebar-nav">
        <li>
            <a href="<?= BASE_URL ?>/admin/dashboard.php" class="sidebar-link <?= ($activeTab === 'dashboard') ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/orders.php" class="sidebar-link <?= ($activeTab === 'orders') ? 'active' : '' ?>" style="color: #4ade80;">
                <svg viewBox="0 0 24 24"><path d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z"/></svg>
                <span>Orders &amp; Bills</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/carts.php" class="sidebar-link <?= ($activeTab === 'carts') ? 'active' : '' ?>" style="color: #fbbf24;">
                <svg viewBox="0 0 24 24"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                <span>Live Customer Carts (કાર્ટ્સ)</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/eway-bill.php" class="sidebar-link <?= ($activeTab === 'eway-bill') ? 'active' : '' ?>" style="color: #38bdf8;">
                <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                <span>GST E-Way Bills (All Customers)</span>
            </a>
        </li>
        <li>
            <a href="https://ewaybillgst.gov.in/login.aspx" target="_blank" class="sidebar-link" style="color: #fbbf24; background: rgba(245, 158, 11, 0.08); border-left: 2px solid #f59e0b;" title="Official Government GST E-Way Bill Login Portal">
                <svg viewBox="0 0 24 24"><path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/></svg>
                <span>Govt E-Way Login ↗</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/machines.php" class="sidebar-link <?= ($activeTab === 'machines') ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24"><path d="M12 2L1 21h22L12 2zm0 3.99L19.53 19H4.47L12 5.99zM11 16h2v2h-2zm0-6h2v4h-2z"/></svg>
                <span>Drill Machines</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/machine-add.php" class="sidebar-link <?= ($activeTab === 'machine-add') ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                <span>Add Machine</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/inquiries.php" class="sidebar-link <?= ($activeTab === 'inquiries') ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                <span>Customer Inquiries</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/customers.php" class="sidebar-link <?= ($activeTab === 'customers') ? 'active' : '' ?>" style="color: #38bdf8;">
                <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                <span>Customers &amp; Buyers</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/feedbacks.php" class="sidebar-link <?= ($activeTab === 'feedbacks') ? 'active' : '' ?>" style="color: #fbbf24;">
                <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                <span>Customer Feedbacks</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/admin/settings.php" class="sidebar-link <?= ($activeTab === 'settings') ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/></svg>
                <span>Company Settings</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer" style="padding: 16px; border-top: 1px solid rgba(255, 255, 255, 0.08); background: rgba(6, 9, 17, 0.9);">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
            <div style="width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 800; color: #060911;">
                <?= strtoupper(substr(is_array($adminUser) ? ($adminUser['username'] ?? 'A') : 'A', 0, 1)) ?>
            </div>
            <div style="line-height: 1.2;">
                <div style="font-size: 12.5px; font-weight: 700; color: #ffffff;">
                    <?= e(is_array($adminUser) ? ($adminUser['username'] ?? 'admin') : $adminUser) ?>
                </div>
                <div style="font-size: 10px; color: #34d399; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                    <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #10b981;"></span>
                    <span>Admin Session Online</span>
                </div>
            </div>
        </div>
        
        <a href="<?= BASE_URL ?>/admin/logout.php" 
           style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; height: 38px; background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(185, 28, 28, 0.3) 100%); border: 1.5px solid rgba(239, 68, 68, 0.45); color: #fca5a5; border-radius: 8px; font-size: 12.5px; font-weight: 700; text-decoration: none; transition: all 0.25s ease;"
           onmouseover="this.style.background='linear-gradient(135deg, #ef4444 0%, #b91c1c 100%)'; this.style.color='#ffffff'; this.style.boxShadow='0 4px 18px rgba(239, 68, 68, 0.5)';"
           onmouseout="this.style.background='linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(185, 28, 28, 0.3) 100%)'; this.style.color='#fca5a5'; this.style.boxShadow='none';">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
            <span>Sign Out Session</span>
        </a>
    </div>
</aside>
<!-- Mobile & Tablet Sidebar Backdrop Overlay -->
<div id="adminSidebarBackdrop" class="admin-sidebar-backdrop"></div>
