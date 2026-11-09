<?php
/**
 * Universal Multi-Language Selector Component (English, Gujarati, Hindi)
 * Shree Varsha Engineering Works - Varsha Brand
 * Supports instant full website & admin panel translation
 */

if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config/config.php';
}

// Determine active language from cookie / session
$currentLang = $_COOKIE['site_lang'] ?? 'en';
if (!in_array($currentLang, ['en', 'gu', 'hi'])) {
    if (!empty($_COOKIE['googtrans'])) {
        if (strpos($_COOKIE['googtrans'], '/gu') !== false) $currentLang = 'gu';
        elseif (strpos($_COOKIE['googtrans'], '/hi') !== false) $currentLang = 'hi';
        else $currentLang = 'en';
    } else {
        $currentLang = 'en';
    }
}

$langLabels = [
    'en' => ['flag' => '🇬🇧', 'name' => 'English', 'native' => 'English', 'code' => 'EN'],
    'gu' => ['flag' => '🇮🇳', 'name' => 'Gujarati', 'native' => 'ગુજરાતી', 'code' => 'GU'],
    'hi' => ['flag' => '🇮🇳', 'name' => 'Hindi', 'native' => 'हिन्दी', 'code' => 'HI']
];

$activeLangMeta = $langLabels[$currentLang] ?? $langLabels['en'];
$dropdownId = 'langSelect_' . substr(md5(uniqid((string)rand(), true)), 0, 8);
?>

<div class="site-lang-dropdown-wrap notranslate" id="<?= $dropdownId ?>" style="position: relative; display: inline-block;">
    <button type="button" class="btn-site-lang-toggle" onclick="toggleSiteLangMenu('<?= $dropdownId ?>')" title="Change Language / ભાષા બદલો / भाषा बदलें" aria-label="Language Selector">
        <span style="font-size: 14px;">🌐</span>
        <span class="active-lang-text" style="font-size: 12px; font-weight: 700;"><?= $activeLangMeta['flag'] ?> <?= $activeLangMeta['native'] ?></span>
        <span style="font-size: 9px; opacity: 0.8; margin-left: 2px;">▼</span>
    </button>

    <div class="site-lang-menu" id="<?= $dropdownId ?>_menu">
        <div style="padding: 7px 12px; font-size: 10px; font-weight: 800; text-transform: uppercase; color: var(--accent-cyan, #00e5ff); letter-spacing: 0.8px; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: space-between;">
            <span>🌐 SELECT LANGUAGE</span>
            <span style="font-size: 9px; color: #94a3b8;">ભાષા પસંદ કરો</span>
        </div>
        
        <button type="button" class="lang-opt-item <?= ($currentLang === 'en') ? 'active' : '' ?>" onclick="changeSiteLanguage('en')">
            <span style="font-size: 16px;">🇬🇧</span>
            <div style="text-align: left; line-height: 1.2;">
                <div style="font-weight: 700; color: #ffffff; font-size: 13px;">English</div>
                <div style="font-size: 10px; color: #94a3b8;">Default International</div>
            </div>
            <?php if ($currentLang === 'en'): ?><span style="color: #34d399; font-weight: 800; margin-left: auto;">✔</span><?php endif; ?>
        </button>

        <button type="button" class="lang-opt-item <?= ($currentLang === 'gu') ? 'active' : '' ?>" onclick="changeSiteLanguage('gu')">
            <span style="font-size: 16px;">🇮🇳</span>
            <div style="text-align: left; line-height: 1.2;">
                <div style="font-weight: 700; color: #ffffff; font-size: 13px;">ગુજરાતી (Gujarati)</div>
                <div style="font-size: 10px; color: #38bdf8;">માતૃભાષા / ભાવનગર</div>
            </div>
            <?php if ($currentLang === 'gu'): ?><span style="color: #34d399; font-weight: 800; margin-left: auto;">✔</span><?php endif; ?>
        </button>

        <button type="button" class="lang-opt-item <?= ($currentLang === 'hi') ? 'active' : '' ?>" onclick="changeSiteLanguage('hi')">
            <span style="font-size: 16px;">🇮🇳</span>
            <div style="text-align: left; line-height: 1.2;">
                <div style="font-weight: 700; color: #ffffff; font-size: 13px;">हिन्दी (Hindi)</div>
                <div style="font-size: 10px; color: #fbbf24;">राष्ट्रभाषा / भारत</div>
            </div>
            <?php if ($currentLang === 'hi'): ?><span style="color: #34d399; font-weight: 800; margin-left: auto;">✔</span><?php endif; ?>
        </button>
    </div>
</div>

<?php if (!defined('SITE_LANG_ASSETS_LOADED')): define('SITE_LANG_ASSETS_LOADED', true); ?>
<!-- Hidden Google Translate Mount Point -->
<div id="google_translate_element" style="display: none !important;"></div>

<style>
/* Universal Glassmorphic Language Selector */
.btn-site-lang-toggle {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 38px;
    padding: 0 11px;
    background: rgba(15, 23, 42, 0.88);
    border: 1.5px solid rgba(0, 229, 255, 0.45);
    color: #ffffff;
    border-radius: 8px;
    cursor: pointer;
    font-family: 'Space Grotesk', -apple-system, sans-serif;
    transition: all 0.2s ease;
    white-space: nowrap;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
}
.btn-site-lang-toggle:hover {
    background: rgba(0, 229, 255, 0.16);
    border-color: #00e5ff;
    box-shadow: 0 0 15px rgba(0, 229, 255, 0.35);
    transform: translateY(-1px);
}
.site-lang-menu {
    display: none;
    position: absolute;
    top: calc(100% + 6px);
    right: 0;
    min-width: 220px;
    background: #090e1c;
    border: 1.5px solid rgba(0, 229, 255, 0.55);
    border-radius: 10px;
    padding: 6px 0;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.95), 0 0 20px rgba(0, 229, 255, 0.18);
    z-index: 10009;
    animation: langMenuFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
.site-lang-menu.show {
    display: block !important;
}
@keyframes langMenuFadeIn {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}
.lang-opt-item {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    background: none;
    border: none;
    cursor: pointer;
    transition: background 0.18s ease;
    text-decoration: none;
    font-family: inherit;
    border-left: 3px solid transparent;
}
.lang-opt-item:hover {
    background: rgba(0, 229, 255, 0.14);
    border-left-color: #00e5ff;
}
.lang-opt-item.active {
    background: rgba(16, 185, 129, 0.14);
    border-left-color: #10b981;
}

/* Hide Google Translate top toolbar banner, header iframe, and tooltips */
.goog-te-banner-frame.skiptranslate,
.goog-te-banner-frame,
iframe.goog-te-banner-frame {
    display: none !important;
    visibility: hidden !important;
    height: 0 !important;
}
body {
    top: 0px !important;
    position: static !important;
}
#goog-gt-tt,
.goog-te-balloon-frame,
.goog-tooltip,
.goog-tooltip:hover {
    display: none !important;
    visibility: hidden !important;
}
.goog-text-highlight {
    background-color: transparent !important;
    border: none !important;
    box-shadow: none !important;
}
.skiptranslate {
    display: inline !important;
}
.skiptranslate iframe {
    display: none !important;
}
#google_translate_element {
    display: none !important;
}
</style>

<script type="text/javascript">
// Google Translate Element Initialization
function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'en',
        includedLanguages: 'en,gu,hi',
        autoDisplay: false,
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE
    }, 'google_translate_element');
}

// Toggle Specific Language Menu
function toggleSiteLangMenu(dropdownId) {
    const menu = document.getElementById(dropdownId + '_menu');
    if (!menu) return;
    const isShown = menu.classList.contains('show');
    closeAllSiteLangMenus();
    if (!isShown) {
        menu.classList.add('show');
    }
}

// Close All Language Menus
function closeAllSiteLangMenus() {
    document.querySelectorAll('.site-lang-menu').forEach(m => m.classList.remove('show'));
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.site-lang-dropdown-wrap')) {
        closeAllSiteLangMenus();
    }
});

// Central Language Switcher (Full Website in Gujarati, Hindi, or English)
function changeSiteLanguage(targetLang) {
    closeAllSiteLangMenus();

    const domain = window.location.hostname;
    // Set root cookies
    document.cookie = "site_lang=" + targetLang + "; path=/; max-age=31536000";
    
    if (targetLang === 'en') {
        // Clear translation cookies for default English
        document.cookie = "googtrans=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
        document.cookie = "googtrans=; domain=" + domain + "; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
        document.cookie = "googtrans=/en/en; path=/; max-age=31536000";
        document.cookie = "googtrans=/en/en; domain=" + domain + "; path=/; max-age=31536000";
    } else {
        document.cookie = "googtrans=/en/" + targetLang + "; path=/; max-age=31536000";
        document.cookie = "googtrans=/en/" + targetLang + "; domain=" + domain + "; path=/; max-age=31536000";
    }

    try {
        localStorage.setItem('site_selected_language', targetLang);
    } catch(e) {}

    // Trigger Google Translate select element if initialized
    const combo = document.querySelector('.goog-te-combo');
    if (combo) {
        combo.value = targetLang;
        combo.dispatchEvent(new Event('change'));
    }
    
    setTimeout(function() {
        window.location.reload();
    }, 120);
}
</script>
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" async defer></script>
<?php endif; ?>
