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

<?php if (!isset($renderLangDropdown) || $renderLangDropdown !== false): ?>
<div class="site-lang-dropdown-wrap notranslate" id="<?= $dropdownId ?>" style="position: relative; display: inline-block;">
    <button type="button" class="btn-site-lang-toggle" onclick="toggleSiteLangMenu('<?= $dropdownId ?>')" title="Change Language / ભાષા બદલો / भाषा बदलें" aria-label="Language Selector">
        <span style="font-size: 14px;">🌐</span>
        <span class="active-lang-text active-lang-desktop" style="font-size: 12px; font-weight: 700;"><?= $activeLangMeta['flag'] ?> <?= $activeLangMeta['native'] ?></span>
        <span class="active-lang-text active-lang-mobile" style="display: none; font-size: 11px; font-weight: 800;"><?= $activeLangMeta['code'] ?></span>
        <span style="font-size: 9px; opacity: 0.8; margin-left: 1px;">▼</span>
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
<?php endif; ?>

<?php if (!defined('SITE_LANG_ASSETS_LOADED')): define('SITE_LANG_ASSETS_LOADED', true); ?>
<!-- Google Translate Mount Point (Kept in DOM offscreen so Google Translate engine mounts and translates cleanly) -->
<div id="google_translate_element" style="position: absolute !important; left: -9999px !important; top: -9999px !important; width: 1px !important; height: 1px !important; overflow: hidden !important; opacity: 0 !important; pointer-events: none !important;"></div>

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

/* Safely hide ONLY Google Translate top toolbar banner, header iframe, and tooltips */
.goog-te-banner-frame.skiptranslate,
.goog-te-banner-frame,
iframe.goog-te-banner-frame,
.VIpgJd-ZVi9od-OR95微-waSda,
.VIpgJd-ZVi9od-aZ2wEe-wOHMyf,
.VIpgJd-ZVi9od-aZ2wEe-OiiCO,
.VIpgJd-ZVi9od-xl07Ob-lTBxed {
    display: none !important;
    visibility: hidden !important;
    height: 0 !important;
    opacity: 0 !important;
    pointer-events: none !important;
}
html, body {
    top: 0px !important;
    position: static !important;
}
body.translated-ltr,
body.translated-rtl {
    top: 0px !important;
    position: static !important;
}
#goog-gt-tt,
.goog-te-balloon-frame,
.goog-tooltip,
.goog-tooltip:hover {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    pointer-events: none !important;
}
.goog-text-highlight {
    background-color: transparent !important;
    border: none !important;
    box-shadow: none !important;
}
font.skiptranslate, span.skiptranslate, font, font > font, font font {
    display: inline !important;
    font-family: inherit !important;
    color: inherit !important;
    background: transparent !important;
}
.site-nav-link font,
.btn-nav-action font,
.sidebar-link font,
.btn font {
    display: inline-flex !important;
    align-items: center !important;
}
.sidebar-link svg {
    min-width: 18px !important;
    flex-shrink: 0 !important;
}
/* Currency & Rupee (₹) Symbol Anti-Stacking Rule */
.currency-val, .price-val, .rupee-amount, .price-nowrap {
    white-space: nowrap !important;
    display: inline-flex !important;
    align-items: baseline !important;
    gap: 3px !important;
}
#google_translate_element {
    position: absolute !important;
    left: -9999px !important;
    top: -9999px !important;
    width: 1px !important;
    height: 1px !important;
    overflow: hidden !important;
    opacity: 0 !important;
    pointer-events: none !important;
}
@media (max-width: 768px) {
    .active-lang-desktop {
        display: none !important;
    }
    .active-lang-mobile {
        display: inline !important;
    }
    .btn-site-lang-toggle {
        height: 34px !important;
        padding: 0 6px !important;
        gap: 3px !important;
        font-size: 11px !important;
    }
    .site-lang-menu {
        position: fixed !important;
        top: 62px !important;
        right: 12px !important;
        left: auto !important;
        width: 250px !important;
        max-width: calc(100vw - 24px) !important;
        z-index: 100000 !important;
    }
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

// Central Language Switcher (Full Website & Admin Panel in Gujarati, Hindi, or English)
function setAllTransCookies(targetLang) {
    const domain = window.location.hostname;
    // 1. Site Lang Preference Cookie
    document.cookie = "site_lang=" + targetLang + "; path=/; max-age=31536000; SameSite=Lax";
    
    // 2. Clear previous Google Translate cookies across root and domain
    const deleteExp = "=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
    document.cookie = "googtrans" + deleteExp;
    document.cookie = "googtrans=" + deleteExp + " domain=" + domain + ";";
    
    // 3. Set standard Google Translate cookie: /en/targetLang & /auto/targetLang
    const transVal = (targetLang === 'en') ? '/en/en' : ('/en/' + targetLang);
    const autoVal = (targetLang === 'en') ? '/auto/en' : ('/auto/' + targetLang);
    document.cookie = "googtrans=" + transVal + "; path=/; max-age=31536000; SameSite=Lax";
    document.cookie = "googtrans=" + autoVal + "; path=/; max-age=31536000; SameSite=Lax";
    
    if (domain && domain !== 'localhost' && domain !== '127.0.0.1') {
        document.cookie = "googtrans=" + transVal + "; domain=" + domain + "; path=/; max-age=31536000; SameSite=Lax";
        document.cookie = "googtrans=" + autoVal + "; domain=" + domain + "; path=/; max-age=31536000; SameSite=Lax";
    }
}

// Comprehensive Instant UI Translation Dictionary (Gujarati & Hindi)
const UI_TRANSLATIONS = {
    'gu': {
        // Navigation & Topbar
        'Home': '🏠 હોમ',
        'About': '🏭 અમારા વિશે',
        'About Us': 'અમારા વિશે',
        'Machines': '⚙️ મશીનો',
        'All Machines': 'બધા મશીનો',
        'Catalog': '📖 કેટલોગ',
        'Catalog & PDF': 'કેટલોગ અને PDF',
        'Live Tracking': '🚚 લાઇવ ટ્રેકિંગ',
        'Contact': '📍 સંપર્ક',
        'Factory Contact': 'ફેક્ટરી સંપર્ક',
        'Sign In': '👤 સાઇન ઇન',
        'Sign Out': 'સાઇન આઉટ',
        'Cart': 'કાર્ટ',
        'My Cart': 'મારું કાર્ટ',
        'Book Online': '💳 ઓનલાઇન બુકિંગ',
        'Admin': 'એડમિન',
        'Search...': 'મશીન શોધો...',
        'VARSHA MENU': 'વર્ષા મેનૂ',

        // Hero & Branding
        'Industrial Heavy-Duty Radial & Pillar Drill Machines': 'ઔદ્યોગિક હેવી-ડ્યુટી રેડિયલ અને પિલર ડ્રિલ મશીનો',
        'Engineered for Absolute Precision': 'સંપૂર્ણ ચોકસાઈ અને મજબૂતી સાથે નિર્મિત',
        '2026 Machine Catalog': '૨૦૨૬ મશીન કેટલોગ',
        'Book Machine Online': 'ઓનલાઇન મશીન બુક કરો',
        '360° 12D View & AI': '૩૬૦° ૧૨D વ્યૂ અને AI',
        'WhatsApp Chat': 'વોટ્સએપ ચેટ',
        'BHAVNAGAR • SVEW': 'ભાવનગર • SVEW',
        'ISO 9001:2015 CERTIFIED': 'ISO 9001:2015 પ્રમાણિત',

        // About Page & Executive Cards
        'ESTABLISHED 1998 • BHAVNAGAR, GUJARAT': 'સ્થાપના ૧૯૯૮ • ભાવનગર, ગુજરાત',
        'Years of Industrial Excellence': 'વર્ષોની ઔદ્યોગિક શ્રેષ્ઠતા',
        'Machines Operating': 'મશીનો કાર્યરત',
        'Foundry Metallurgy': 'કાસ્ટિંગ ધાતુવિજ્ઞાન',
        'Spindle Runout Precision': 'સ્પિન્ડલ રનઆઉટ ચોકસાઈ',
        'Dial-Gauge Inspected': 'ડાયલ ગેજ દ્વારા ચકાસાયેલ',
        'OUR HERITAGE & FOUNDATION': 'અમારો વારસો અને પાયો',
        'Pioneering High Precision Drill Machine Manufacturing in Bhavnagar': 'ભાવનગરમાં ઉચ્ચ ચોકસાઈવાળા ડ્રિલ મશીનોનું અગ્રણી ઉત્પાદન',
        'Grade-25 Castings': 'ગ્રેડ-૨૫ કાસ્ટિંગ્સ',
        '20MnCr5 Alloy Gears': '૨૦MnCr૫ એલોય ગિયર્સ',
        'Quality Commitment': 'ગુણવત્તા પ્રત્યે પ્રતિબદ્ધતા',
        'Dynamic Balancing & Runout Tests': 'ડાયનેમિક બેલેન્સિંગ અને રનઆઉટ ટેસ્ટ',
        'High Rigid Column & Arm Assembly': 'અત્યંત મજબૂત કૉલમ અને આર્મ એસેમ્બલી',
        'Pan-India Spares & Support': 'સમગ્ર ભારતમાં સ્પેરપાર્ટ્સ અને સપોર્ટ',
        'Factory Desk Contact': 'ફેક્ટરી ડેસ્ક સંપર્ક',
        'EXECUTIVE LEADERSHIP & MANAGEMENT': 'કાર્યકારી નેતૃત્વ અને સંચાલન',
        'Meet Our Founders & Directors': 'અમારા સ્થાપક અને ડિરેક્ટર્સ',
        'Chief Executive Officer (CEO)': 'મુખ્ય કાર્યકારી અધિકારી (CEO)',
        'Founder & Heavy Metallurgy Director': 'સ્થાપક અને હેવી મેટલર્જી ડિરેક્ટર',
        'Co-CEO & Production Engineer': 'સહ-સીઈઓ અને પ્રોડક્શન એન્જિનિયર',
        'Authorized Signatory & Operations Head': 'અધિકૃત હસ્તાક્ષરકર્તા અને ઓપરેશન્સ હેડ',
        'Direct Phone:': 'ડાયરેક્ટ ફોન:',
        'Email Address:': 'ઈમેઇલ સરનામું:',
        'WhatsApp with Jitendrabhai (CEO)': 'જીતેન્દ્રભાઈ (CEO) સાથે વોટ્સએપ',
        'WhatsApp with Jay Parmar (Co-CEO)': 'જય પરમાર (સહ-CEO) સાથે વોટ્સએપ',
        'Our Modern Manufacturing Facility': 'અમારું આધુનિક ઉત્પાદન એકમ (ફેક્ટરી)',
        'Heavy Boring & Plano Millers': 'હેવી બોરિંગ અને પ્લાનો મિલર્સ',
        'Precision Gear Hobbing': 'પ્રિસિઝન ગિયર હોબિંગ',
        'Cylindrical & Surface Grinding': 'સિલિન્ડ્રિકલ અને સરફેસ ગ્રાઇન્ડિંગ',
        'Full Load Testing Bay': 'ફુલ લોડ ટેસ્ટિંગ બે',
        'PLAN A FACTORY VISIT': 'ફેક્ટરી મુલાકાતનું આયોજન કરો',
        'Experience Our Machines in Action': 'અમારા મશીનોનું જીવંત પ્રદર્શન જુઓ',
        'Schedule Factory Visit': 'ફેક્ટરી મુલાકાત બુક કરો',

        // Machine Specifications & Catalog
        'Drilling Capacity in Steel': 'સ્ટીલમાં ડ્રિલિંગ ક્ષમતા',
        'Drilling Capacity in Cast Iron': 'કાસ્ટ આયર્નમાં ડ્રિલિંગ ક્ષમતા',
        'Spindle Travel': 'સ્પિન્ડલ ટ્રાવેલ',
        'Spindle Speeds': 'સ્પિન્ડલ સ્પીડ્સ',
        'Spindle Feed Rates': 'સ્પિન્ડલ ફીડ રેટ્સ',
        'Column Diameter': 'કૉલમ વ્યાસ',
        'Spindle Morse Taper': 'સ્પિન્ડલ મોર્સ ટેપર',
        'Main Drive Motor': 'મુખ્ય ડ્રાઇવ મોટર',
        'Elevating Motor': 'એલિવેટિંગ મોટર',
        'Working Surface of Base Plate': 'બેઝ પ્લેટનું વર્કિંગ સરફેસ',
        'Working Surface of Box Table': 'બોક્સ ટેબલનું વર્કિંગ સરફેસ',
        'Net Weight (Approx)': 'ચોખ્ખું વજન (આશરે)',
        'Technical Specifications': 'તકનીકી વિશિષ્ટતાઓ',
        'Key Engineering Highlights': 'મુખ્ય એન્જિનિયરિંગ વિશેષતાઓ',
        'Standard Accessories': 'સાથે મળતી એક્સેસરીઝ',
        'Optional Accessories': 'વૈકલ્પિક એક્સેસરીઝ',
        'Book Machine Now': 'હમણાં મશીન બુક કરો',
        'Add to Industrial Cart': 'કાર્ટમાં ઉમેરો',
        'Download Technical PDF': 'ટેકનિકલ PDF ડાઉનલોડ કરો',
        'Inquire on WhatsApp': 'વોટ્સએપ પર પૂછપરછ કરો',

        // Cart & Checkout
        'Order Summary': 'ઓર્ડર સારાંશ',
        'Machine Subtotal': 'મશીન સબટોટલ',
        'GST Tax (18%)': 'જીએસટી ટેક્સ (૧૮%)',
        'Total Payable Amount': 'કુલ ચૂકવવાપાત્ર રકમ',
        'Select Payment Method': 'પેમેન્ટ પદ્ધતિ પસંદ કરો',
        'Direct Bank Transfer (NEFT / RTGS / IMPS)': 'ડાયરેક્ટ બેંક ટ્રાન્સફર (NEFT / RTGS / IMPS)',
        'Cheque / Demand Draft Deposit': 'ચેક / ડિમાન્ડ ડ્રાફ્ટ ડિપોઝિટ',
        'Cash on Factory Dispatch (COD)': 'કેશ ઓન ડિલિવરી (COD)',
        'Payment Date / Cheque Date': 'પેમેન્ટ તારીખ / ચેક તારીખ',
        'Transaction UTR / Reference No.': 'ટ્રાન્ઝેક્શન UTR / રેફરન્સ નંબર',
        'Place Booking Order': 'ઓર્ડર બુક કરો',
        'Invoice / Tax Bill': 'ઇન્વોઇસ / ટેક્સ બિલ',

        // Orders, Tracking & Statuses
        'Order Placed & Paid': 'ઓર્ડર નોંધાયેલ અને પેઇડ',
        'Assembly in Progress': 'એસેમ્બલી પ્રગતિમાં છે',
        'QC Testing Passed': 'ક્વોલિટી ટેસ્ટિંગ પાસ',
        'Wooden Crate Packed': 'લાકડાના બોક્સમાં પેક થયેલ',
        'Dispatched / In Transit': 'ડિસ્પેચ થયેલ / રસ્તામાં છે',
        'Out for Delivery': 'ડિલિવરી માટે નીકળેલ છે',
        'Successfully Delivered': 'સફળતાપૂર્વક પહોંચાડી દીધેલ છે',
        'Delivered & Commissioned at Factory': 'ફેક્ટરીમાં ડિલિવર અને કમિશન થયેલ (સંપૂર્ણ)',
        'Fully Commissioned': 'સંપૂર્ણ કમિશન થયેલ ✔',
        'Mark Delivered': '🏁 ડિલિવરી પૂર્ણ માર્ક કરો',
        'Pending': '⏳ પેન્ડિંગ',
        'Real-Time Delivery Status': 'રિયલ-ટાઇમ ડિલિવરી સ્થિતિ',

        // Admin Panel & Navigation
        'Overview Dashboard': 'ડેશબોર્ડ ઝાંખી',
        'Orders & Bills': 'ઓર્ડર્સ અને બિલ',
        'Live Customer Carts (કાર્ટ્સ)': 'લાઇવ ગ્રાહક કાર્ટ્સ',
        'GST E-Way Bills (All Customers)': 'જીએસટી ઇ-વે બિલ',
        'Govt E-Way Login ↗': 'સરકારી ઇ-વે પોર્ટલ ↗',
        'Drill Machines': 'ડ્રિલ મશીનો',
        'Add Machine': 'નવું મશીન ઉમેરો',
        'Customer Inquiries': 'ગ્રાહક પૂછપરછ',
        'Customers & Buyers': 'ગ્રાહકો અને ખરીદદારો',
        'Customer Feedbacks': 'ગ્રાહક પ્રતિસાદ',
        'Company Settings': 'કંપની સેટિંગ્સ',
        'Sign Out Session': 'સાઇન આઉટ સત્ર',
        'Total Orders Placed': 'કુલ નોંધાયેલ ઓર્ડર્સ',
        'Paid / Confirmed': 'પેઇડ / કન્ફર્મ થયેલ',
        'Total Payments Collected': 'કુલ જમા થયેલ પેમેન્ટ',
        'Authorized Signatory': 'અધિકૃત હસ્તાક્ષરકર્તા',
        'Freight Logistics': 'ટ્રાન્સપોર્ટ લોજિસ્ટિક્સ',
        'View Live Website': 'લાઈવ વેબસાઇટ જુઓ',
        'Actions': 'ક્રિયાઓ',
        'Edit': 'ફેરફાર કરો',
        'Delete': 'ડિલીટ કરો',
        'View Bill': 'બિલ જુઓ',

        // Checkout & Machinery Booking
        'Select Machine or Genuine Spare Part': 'મશીન અથવા જેન્યુઇન સ્પેર પાર્ટ પસંદ કરો',
        'Customer & Factory Delivery Details': 'ગ્રાહક અને ફેક્ટરી ડિલિવરી વિગતો',
        'Customer Contact & Delivery Address': 'ગ્રાહક સંપર્ક અને ડિલિવરી સરનામું',
        'Select Machinery Model / Genuine Spare Part': 'મશીનરી મોડેલ / જેન્યુઇન સ્પેર પાર્ટ પસંદ કરો',
        'Standard Token Booking Advance': 'સ્ટાન્ડર્ડ ટોકન બુકિંગ એડવાન્સ',
        'Standard token to reserve manufacturing slot and chassis allocation today.': 'ઉત્પાદન સ્લોટ અને ચેસીસ અલોકેશન રિઝર્વ કરવા માટે પ્રમાણિત ટોકન એડવાન્સ.',
        'Custom Online Advance': 'કસ્ટમ ઓનલાઇન એડવાન્સ',
        'Pay custom advance booking online': 'કસ્ટમ એડવાન્સ બુકિંગ ઓનલાઇન ચૂકવો',
        'Remaining balance due upon delivery.': 'બાકીની રકમ ડિલિવરી વખતે ચૂકવવાની રહેશે.',
        'Max Advance': 'મહત્તમ એડવાન્સ',
        'Or Enter Amount': 'અથવા રકમ દાખલ કરો',
        'Full Payment (Price + 18% GST)': 'સંપૂર્ણ ચુકવણી (કિંમત + ૧૮% GST)',
        'Complete invoice payment with 100% Tax Invoice, priority dispatch & free tooling set.': '૧૦૦% ટેક્સ ઇન્વોઇસ, પ્રાયોરિટી ડિસ્પેચ અને ફ્રી ટૂલિંગ સેટ સાથે સંપૂર્ણ પેમેન્ટ.',
        'Ordering Machine or Spare Parts? Sign In to Link Your Order': 'મશીન અથવા સ્પેરપાર્ટ્સ ઓર્ડર કરી રહ્યા છો? ઓર્ડર લિંક કરવા સાઇન ઇન કરો',
        'Log in to track delivery via real-time live status and download GST tax invoices anytime.': 'રીયલ-ટાઇમ લાઇવ સ્ટેટસ ટ્રેક કરવા અને GST ટેક્સ ઇન્વોઇસ ડાઉનલોડ કરવા લોગ ઇન કરો.',
        'Sign In / Register': '👤 સાઇન ઇન / રજીસ્ટર',
        'Full Name *': 'પૂરું નામ *',
        'Company / Factory Name *': 'કંપની / ફેક્ટરીનું નામ *',
        'Mobile / WhatsApp Number *': 'મોબાઇલ / વોટ્સએપ નંબર *',
        'Email Address': 'ઇમેઇલ સરનામું',
        'Delivery Factory Address *': 'ડિલિવરી ફેક્ટરીનું સરનામું *',
        'City / District *': 'શહેર / જિલ્લો *',
        'Pincode *': 'પિનકોડ *',
        'GSTIN Number (Optional)': 'GSTIN નંબર (વૈકલ્પિક)',
        'Purchase Order (PO) / Delivery Notes': 'પર્ચેઝ ઓર્ડર (PO) / ડિલિવરી નોટ્સ',
        'Order Quantity': 'ઓર્ડર જથ્થો (નંગ)',
        'Units in Factory Stock': 'યુનિટ ફેક્ટરી સ્ટોકમાં ઉપલબ્ધ',
        'Pay Booking Advance': '🔒 બુકિંગ એડવાન્સ ચૂકવો',
        'Pay Online Advance': '🔒 ઓનલાઇન એડવાન્સ ચૂકવો',
        'Pay Full Amount': '🔒 સંપૂર્ણ રકમ ચૂકવો',
        'Confirm Cash on Delivery': '📦 કેશ ઓન ડિલિવરી ઓર્ડર કન્ફર્મ કરો',
        'Confirm Direct Bank Transfer': '🏛️ ડાયરેક્ટ બેંક ટ્રાન્સફર ઓર્ડર કન્ફર્મ કરો',

        // Admin & E-Way Bill
        'GST E-Way Bill Consignment Registry': 'જીએસટી ઇ-વે બિલ કન્સાઇનમેન્ટ રજિસ્ટ્રી',
        'Customer-wise GST E-Way Bills': 'ગ્રાહક વાઇઝ જીએસટી ઇ-વે બિલ',
        'Generate, print, or verify 12-digit GST E-Way Bills for heavy machinery dispatches across India under HSN Code 8459.': 'HSN કોડ ૮૪૫૯ હેઠળ સમગ્ર ભારતમાં હેવી મશીનરી ડિસ્પેચ માટે ૧૨-અંકના જીએસટી ઇ-વે બિલ જનરેટ, પ્રિન્ટ અથવા વેરિફાય કરો.',
        'Official Government Portal Direct Login': 'સત્તાવાર સરકારી પોર્ટલ ડાયરેક્ટ લોગિન',
        'Total Consignments': 'કુલ કન્સાઇનમેન્ટ્સ',
        'Total Consignment Value': 'કુલ કન્સાઇનમેન્ટ મૂલ્ય',
        'HSN Code': 'HSN કોડ',
        'Amount Paid': 'ચૂકવેલ રકમ',
        'Order / Invoice #': 'ઓર્ડર / ઇન્વોઇસ #',
        'Customer': 'ગ્રાહક',
        'Machine Model': 'મશીન મોડેલ',
        'Status': 'સ્થિતિ',
        'Order / Invoice': 'ઓર્ડર / ઇન્વોઇસ',
        'Customer & Company': 'ગ્રાહક અને કંપની',
        'GSTIN & Destination': 'GSTIN અને ગંતવ્ય',
        'E-Way Bill': 'ઇ-વે બિલ',
        'Live Customer Carts': 'લાઇવ ગ્રાહક કાર્ટ્સ',
        'Customer Orders & Live Delivery Tracking': 'ગ્રાહક ઓર્ડર્સ અને લાઇવ ડિલિવરી ટ્રેકિંગ',
        'Track machine bookings, verified online payments, tax invoices, and manage live delivery milestones.': 'મશીન બુકિંગ, વેરિફાઇડ ઓનલાઇન પેમેન્ટ્સ, ટેક્સ ઇન્વોઇસ અને લાઇવ ડિલિવરી માઇલસ્ટોન્સ મેનેજ કરો.',
        'Registered Customers & Machinery Buyers': 'નોંધાયેલા ગ્રાહકો અને મશીનરી ખરીદદારો',
        'Directory of verified client workshops, contact persons, GST numbers, and order histories.': 'ચકાસાયેલ ક્લાયન્ટ વર્કશોપ, સંપર્ક વ્યક્તિઓ, GST નંબરો અને ઓર્ડર ઇતિહાસની ડિરેક્ટરી.',
        'Manage Drill Machines Catalog': 'ડ્રિલ મશીનો કેટલોગ મેનેજ કરો',
        'Customer Quotations & Leads': 'ગ્રાહક ક્વોટેશન્સ અને લીડ્સ',
        'Customer Ratings & Factory Reviews': 'ગ્રાહક રેટિંગ્સ અને ફેક્ટરી સમીક્ષાઓ',
        'Company Profile & Contact Settings': 'કંપની પ્રોફાઇલ અને સંપર્ક સેટિંગ્સ',
        'Ready for Transit': 'ટ્રાન્ઝિટ માટે તૈયાર ✔',
        'SUCCESS / PAID': 'પેઇડ / સફળ ✔',
        'FAILURE / CANCELLED': 'નિષ્ફળ / રદ ✖',
        'PENDING': 'પેન્ડિંગ ⏳',
        'View Live Website': 'લાઈવ વેબસાઇટ જુઓ',
        'Add Drill Machine': 'નવું ડ્રિલ મશીન ઉમેરો',

        // Video Player & Machine Detail
        'Open YouTube Video': 'યૂટ્યૂબ વિડીયો જુઓ',
        'Video Mode': 'વિડીયો મોડ',
        '3D Spindle Simulator': '૩D સ્પિન્ડલ સિમ્યુલેટર',
        'Real Photo': 'અસલ ફોટો',
        'Live Machine Metrics': 'લાઇવ મશીન મેટ્રિક્સ',
        'Play Live Workshop Video': 'લાઇવ વર્કશોપ વિડીયો ચલાવો',
        'Live Factory Video Call': 'લાઇવ ફેક્ટરી વિડીયો કૉલ',
        'Open 360° 12D Lab View': '૩૬૦° ૧૨D લેબ વ્યૂ જુઓ',
        'Runout Accuracy': 'રનઆઉટ ચોકસાઈ',
        'Coolant Circulation': 'કૂલન્ટ પરિભ્રમણ',
        'Vibration Index': 'વાઇબ્રેશન ઇન્ડેક્સ',
        'Dynamic Factory Inventory': 'ડાયનેમિક ફેક્ટરી સ્ટોક',
        'Units Ready in Stock': 'યુનિટ સ્ટોકમાં તૈયાર',

        // Invoice & Proforma Bill
        'Proforma Tax Invoice': 'પ્રોફોર્મા ટેક્સ ઇન્વોઇસ',
        'Tax Invoice': 'ટેક્સ ઇન્વોઇસ',
        'Official Certified Industrial Tax Document': 'સત્તાવાર પ્રમાણિત ઔદ્યોગિક ટેક્સ દસ્તાવેજ',
        'Bill To (Buyer / Consignee)': 'બિલ ટુ (ગ્રાહક / ખરીદનાર)',
        'Dispatch & Consignment Details': 'ડિસ્પેચ અને કન્સાઇનમેન્ટ વિગતો',
        'Machinery Description & Technical Specifications': 'મશીનરી વર્ણન અને તકનીકી વિશિષ્ટતાઓ',
        'Taxable Value': 'કરપાત્ર મૂલ્ય',
        'Total Billed': 'કુલ બિલ રકમ',
        'Balance Due': 'બાકી ચૂકવવાપાત્ર',
        'PAID IN FULL': 'સંપૂર્ણ ચૂકવેલ ✔',
        'Company Bank Account': 'કંપની બેંક ખાતું',
        'Amount in Words': 'શબ્દોમાં રકમ',
        'Standard Manufacturing & Warranty Terms': 'ઉત્પાદન અને વોરંટી શરતો'
    },
    'hi': {
        // Navigation & Topbar
        'Home': '🏠 होम',
        'About': '🏭 हमारे बारे में',
        'About Us': 'हमारे बारे में',
        'Machines': '⚙️ मशीनें',
        'All Machines': 'सभी मशीनें',
        'Catalog': '📖 कैटलॉग',
        'Catalog & PDF': 'कैटलॉग और PDF',
        'Live Tracking': '🚚 लाइव ट्रैकिंग',
        'Contact': '📍 संपर्क',
        'Factory Contact': 'फैक्ट्री संपर्क',
        'Sign In': '👤 साइन इन',
        'Sign Out': 'साइन आउट',
        'Cart': 'कार्ट',
        'My Cart': 'मेरी कार्ट',
        'Book Online': '💳 ऑनलाइन बुकिंग',
        'Admin': 'एडमिन',
        'Search...': 'मशीन खोजें...',
        'VARSHA MENU': 'वर्षा मेनू',

        // Hero & Branding
        'Industrial Heavy-Duty Radial & Pillar Drill Machines': 'औद्योगिक भारी-भरकम रेडियल और पिलर ड्रिल मशीनें',
        'Engineered for Absolute Precision': 'सर्वोच्च परिशुद्धता और मजबूती के साथ निर्मित',
        '2026 Machine Catalog': '२०२६ मशीन कैटलॉग',
        'Book Machine Online': 'ऑनलाइन मशीन बुक करें',
        '360° 12D View & AI': '३६०° १२D व्यू और AI',
        'WhatsApp Chat': 'व्हाट्सएप चैट',
        'BHAVNAGAR • SVEW': 'भावनगर • SVEW',
        'ISO 9001:2015 CERTIFIED': 'ISO 9001:2015 प्रमाणित',

        // About Page & Executive Cards
        'ESTABLISHED 1998 • BHAVNAGAR, GUJARAT': 'स्थापना १९९८ • भावनगर, गुजरात',
        'Years of Industrial Excellence': 'वर्षों की औद्योगिक उत्कृष्टता',
        'Machines Operating': 'मशीनें कार्यरत',
        'Foundry Metallurgy': 'फाउंड्री धातुविज्ञान',
        'Spindle Runout Precision': 'स्पिंडल रनआउट परिशुद्धता',
        'Dial-Gauge Inspected': 'डायल गेज द्वारा परीक्षित',
        'OUR HERITAGE & FOUNDATION': 'हमारी विरासत और आधारशिला',
        'Pioneering High Precision Drill Machine Manufacturing in Bhavnagar': 'भावनगर में उच्च परिशुद्धता ड्रिल मशीन विनिर्माण में अग्रणी',
        'Grade-25 Castings': 'ग्रेड-२५ कास्टिंग्स',
        '20MnCr5 Alloy Gears': '२०MnCr५ अलॉय गियर्स',
        'Quality Commitment': 'गुणवत्ता के प्रति प्रतिबद्धता',
        'Dynamic Balancing & Runout Tests': 'डायनामिक बैलेंसिंग और रनआउट टेस्ट',
        'High Rigid Column & Arm Assembly': 'अति-मजबूत कॉलम और आर्म असेंबली',
        'Pan-India Spares & Support': 'अखिल भारतीय स्पेयर पार्ट्स और सपोर्ट',
        'Factory Desk Contact': 'फैक्ट्री डेस्क संपर्क',
        'EXECUTIVE LEADERSHIP & MANAGEMENT': 'कार्यकारी नेतृत्व और प्रबंधन',
        'Meet Our Founders & Directors': 'हमारे संस्थापक और निदेशक',
        'Chief Executive Officer (CEO)': 'मुख्य कार्यकारी अधिकारी (CEO)',
        'Founder & Heavy Metallurgy Director': 'संस्थापक एवं हैवी मेटलर्जी निदेशक',
        'Co-CEO & Production Engineer': 'सह-सीईओ एवं प्रोडक्शन इंजीनियर',
        'Authorized Signatory & Operations Head': 'अधिकृत हस्ताक्षरकर्ता एवं ऑपरेशंस हेड',
        'Direct Phone:': 'सीधा फोन:',
        'Email Address:': 'ईमेल पता:',
        'WhatsApp with Jitendrabhai (CEO)': 'जितेंद्रभाई (CEO) से व्हाट्सएप करें',
        'WhatsApp with Jay Parmar (Co-CEO)': 'जय परमार (सह-CEO) से व्हाट्सएप करें',
        'Our Modern Manufacturing Facility': 'हमारी आधुनिक निर्माण सुविधा (फैक्ट्री)',
        'Heavy Boring & Plano Millers': 'हैवी बोरिंग और प्लानो मिलर्स',
        'Precision Gear Hobbing': 'प्रिसिजन गियर हॉबिंग',
        'Cylindrical & Surface Grinding': 'सिलिंड्रिकल और सरफेस ग्राइंडिंग',
        'Full Load Testing Bay': 'फुल लोड टेस्टिंग बे',
        'PLAN A FACTORY VISIT': 'फैक्ट्री विजिट की योजना बनाएं',
        'Experience Our Machines in Action': 'हमारी मशीनों का लाइव प्रदर्शन देखें',
        'Schedule Factory Visit': 'फैक्ट्री विजिट बुक करें',

        // Machine Specifications & Catalog
        'Drilling Capacity in Steel': 'स्टील में ड्रिलिंग क्षमता',
        'Drilling Capacity in Cast Iron': 'कास्ट आयरन में ड्रिलिंग क्षमता',
        'Spindle Travel': 'स्पिंडल ट्रैवल',
        'Spindle Speeds': 'स्पिंडल स्पीड्स',
        'Spindle Feed Rates': 'स्पिंडल फीड दरें',
        'Column Diameter': 'कॉलम व्यास',
        'Spindle Morse Taper': 'स्पिंडल मोर्स टेपर',
        'Main Drive Motor': 'मुख्य ड्राइव मोटर',
        'Elevating Motor': 'एलिवेटिंग मोटर',
        'Working Surface of Base Plate': 'बेस प्लेट की वर्किंग सतह',
        'Working Surface of Box Table': 'बॉक्स टेबल की वर्किंग सतह',
        'Net Weight (Approx)': 'कुल वजन (लगभग)',
        'Technical Specifications': 'तकनीकी विशिष्टताएं',
        'Key Engineering Highlights': 'मुख्य इंजीनियरिंग विशेषताएं',
        'Standard Accessories': 'मानक सहायक उपकरण',
        'Optional Accessories': 'वैकल्पिक सहायक उपकरण',
        'Book Machine Now': 'अभी मशीन बुक करें',
        'Add to Industrial Cart': 'कार्ट में जोड़ें',
        'Download Technical PDF': 'तकनीकी PDF डाउनलोड करें',
        'Inquire on WhatsApp': 'व्हाट्सएप पर पूछताछ करें',

        // Cart & Checkout
        'Order Summary': 'ऑर्डर सारांश',
        'Machine Subtotal': 'मशीन सबटोटल',
        'GST Tax (18%)': 'जीएसटी कर (१८%)',
        'Total Payable Amount': 'कुल देय राशि',
        'Select Payment Method': 'भुगतान विधि चुनें',
        'Direct Bank Transfer (NEFT / RTGS / IMPS)': 'सीधा बैंक ट्रांसफर (NEFT / RTGS / IMPS)',
        'Cheque / Demand Draft Deposit': 'चेक / डिमांड ड्राफ्ट जमा',
        'Cash on Factory Dispatch (COD)': 'कैश ऑन डिलीवरी (COD)',
        'Payment Date / Cheque Date': 'भुगतान तिथि / चेक तिथि',
        'Transaction UTR / Reference No.': 'ट्रांजेक्शन UTR / संदर्भ संख्या',
        'Place Booking Order': 'ऑर्डर बुक करें',
        'Invoice / Tax Bill': 'चालान / टैक्स बिल',

        // Orders, Tracking & Statuses
        'Order Placed & Paid': 'ऑर्डर दर्ज और भुगतान सफल',
        'Assembly in Progress': 'असेंबली प्रगति पर है',
        'QC Testing Passed': 'गुणवत्ता परीक्षण सफल',
        'Wooden Crate Packed': 'लकड़ी के बक्से में पैक',
        'Dispatched / In Transit': 'भेजा गया / रास्ते में',
        'Out for Delivery': 'डिलीवरी के लिए निकला है',
        'Successfully Delivered': 'सफलतापूर्वक वितरित किया गया',
        'Delivered & Commissioned at Factory': 'फैक्ट्री में डिलीवर एवं चालू (पूर्ण)',
        'Fully Commissioned': 'पूर्णतः चालू किया गया ✔',
        'Mark Delivered': '🏁 डिलीवरी पूर्ण मार्क करें',
        'Pending': '⏳ लंबित',
        'Real-Time Delivery Status': 'रीयल-टाइम डिलीवरी स्थिति',

        // Admin Panel & Navigation
        'Overview Dashboard': 'डैशबोर्ड अवलोकन',
        'Orders & Bills': 'ऑर्डर्स और बिल',
        'Live Customer Carts (કાર્ટ્સ)': 'लाइव कस्टमर कार्ट्स',
        'GST E-Way Bills (All Customers)': 'जीएसटी ई-वे बिल',
        'Govt E-Way Login ↗': 'सरकारी ई-वे लॉगिन ↗',
        'Drill Machines': 'ड्रिल मशीनें',
        'Add Machine': 'नई मशीन जोड़ें',
        'Customer Inquiries': 'ग्राहक पूछताछ',
        'Customers & Buyers': 'ग्राहक और खरीदार',
        'Customer Feedbacks': 'ग्राहक फीडबैक',
        'Company Settings': 'कंपनी सेटिंग्स',
        'Sign Out Session': 'साइन आउट सत्र',
        'Total Orders Placed': 'कुल प्राप्त ऑर्डर्स',
        'Paid / Confirmed': 'भुगतान सफल / कन्फर्म',
        'Total Payments Collected': 'कुल एकत्र भुगतान',
        'Authorized Signatory': 'अधिकृत हस्ताक्षरकर्ता',
        'Freight Logistics': 'परिवहन लॉजिस्टिक्स',
        'View Live Website': 'लाइव वेबसाइट देखें',
        'Actions': 'क्रियाएं',
        'Edit': 'संपादित करें',
        'Delete': 'हटाएं',
        'View Bill': 'बिल देखें',

        // Checkout & Machinery Booking
        'Select Machine or Genuine Spare Part': 'मशीन या असली स्पेयर पार्ट चुनें',
        'Customer & Factory Delivery Details': 'ग्राहक और फैक्ट्री डिलीवरी विवरण',
        'Customer Contact & Delivery Address': 'ग्राहक संपर्क और डिलीवरी पता',
        'Select Machinery Model / Genuine Spare Part': 'मशीनरी मॉडल / असली स्पेयर पार्ट चुनें',
        'Standard Token Booking Advance': 'मानक टोकन बुकिंग अग्रिम',
        'Standard token to reserve manufacturing slot and chassis allocation today.': 'विनिर्माण स्लॉट और चेसिस आवंटन सुरक्षित करने के लिए मानक टोकन।',
        'Custom Online Advance': 'कस्टम ऑनलाइन अग्रिम',
        'Pay custom advance booking online': 'कस्टम अग्रिम बुकिंग ऑनलाइन भुगतान करें',
        'Remaining balance due upon delivery.': 'शेष राशि डिलीवरी पर देय होगी।',
        'Max Advance': 'अधिकतम अग्रिम',
        'Or Enter Amount': 'या राशि दर्ज करें',
        'Full Payment (Price + 18% GST)': 'पूर्ण भुगतान (कीमत + १८% GST)',
        'Complete invoice payment with 100% Tax Invoice, priority dispatch & free tooling set.': '१००% टैक्स इनवॉइस, प्राथमिकता डिस्पैच और मुफ्त टूलिंग सेट के साथ पूर्ण भुगतान।',
        'Ordering Machine or Spare Parts? Sign In to Link Your Order': 'मशीन या स्पेयर पार्ट्स ऑर्डर कर रहे हैं? ऑर्डर लिंक करने के लिए साइन इन करें',
        'Log in to track delivery via real-time live status and download GST tax invoices anytime.': 'रीयल-टाइम डिलीवरी ट्रैक करने और जीएसटी इनवॉइस डाउनलोड करने के लिए लॉगिन करें।',
        'Sign In / Register': '👤 साइन इन / रजिस्टर',
        'Full Name *': 'पूरा नाम *',
        'Company / Factory Name *': 'कंपनी / फैक्ट्री का नाम *',
        'Mobile / WhatsApp Number *': 'मोबाइल / व्हाट्सएप नंबर *',
        'Email Address': 'ईमेल पता',
        'Delivery Factory Address *': 'डिलीवरी फैक्ट्री का पता *',
        'City / District *': 'शहर / जिला *',
        'Pincode *': 'पिनकोड *',
        'GSTIN Number (Optional)': 'GSTIN नंबर (वैकल्पिक)',
        'Purchase Order (PO) / Delivery Notes': 'परचेज ऑर्डर (PO) / डिलीवरी नोट्स',
        'Order Quantity': 'ऑर्डर मात्रा',
        'Units in Factory Stock': 'यूनिट फैक्ट्री स्टॉक में उपलब्ध',
        'Pay Booking Advance': '🔒 बुकिंग अग्रिम भुगतान करें',
        'Pay Online Advance': '🔒 ऑनलाइन अग्रिम भुगतान करें',
        'Pay Full Amount': '🔒 पूरी राशि भुगतान करें',
        'Confirm Cash on Delivery': '📦 कैश ऑन डिलीवरी ऑर्डर कन्फर्म करें',
        'Confirm Direct Bank Transfer': '🏛️ डायरेक्ट बैंक ट्रांसफर ऑर्डर कन्फर्म करें',

        // Admin & E-Way Bill
        'GST E-Way Bill Consignment Registry': 'जीएसटी ई-वे बिल कंसाइनमेंट रजिस्ट्री',
        'Customer-wise GST E-Way Bills': 'ग्राहक अनुसार जीएसटी ई-वे बिल',
        'Generate, print, or verify 12-digit GST E-Way Bills for heavy machinery dispatches across India under HSN Code 8459.': 'HSN कोड ८४५૯ के तहत पूरे भारत में भारी मशीनरी प्रेषण के लिए १२-अंकीय जीएसटी ई-वे बिल जनरेट, प्रिंट या सत्यापित करें।',
        'Official Government Portal Direct Login': 'आधिकारिक सरकारी पोर्टल डायरेक्ट लॉगिन',
        'Total Consignments': 'कुल कंसाइनमेंट',
        'Total Consignment Value': 'कुल कंसाइनमेंट मूल्य',
        'HSN Code': 'HSN कोड',
        'Amount Paid': 'भुगतान की गई राशि',
        'Order / Invoice #': 'ऑर्डर / इनवॉइस #',
        'Customer': 'ग्राहक',
        'Machine Model': 'मशीन मॉडल',
        'Status': 'स्थिति',
        'Order / Invoice': 'ऑर्डर / इनवॉइस',
        'Customer & Company': 'ग्राहक और कंपनी',
        'GSTIN & Destination': 'GSTIN और गंतव्य',
        'E-Way Bill': 'ई-वे बिल',
        'Live Customer Carts': 'लाइव कस्टमर कार्ट्स',
        'Customer Orders & Live Delivery Tracking': 'ग्राहक ऑर्डर्स और लाइव डिलीवरी ट्रैकिंग',
        'Track machine bookings, verified online payments, tax invoices, and manage live delivery milestones.': 'मशीन बुकिंग, सत्यापित ऑनलाइन भुगतान, टैक्स इनवॉइस और डिलीवरी ट्रैक करें।',
        'Registered Customers & Machinery Buyers': 'पंजीकृत ग्राहक और मशीनरी खरीदार',
        'Directory of verified client workshops, contact persons, GST numbers, and order histories.': 'सत्यापित ग्राहक वर्कशॉप, संपर्क व्यक्ति, जीएसटी नंबर और ऑर्डर इतिहास।',
        'Manage Drill Machines Catalog': 'ड्रिल मशीन कैटलॉग प्रबंधित करें',
        'Customer Quotations & Leads': 'ग्राहक कोटेशन और लीड्स',
        'Customer Ratings & Factory Reviews': 'ग्राहक रेटिंग और फैक्ट्री समीक्षाएं',
        'Company Profile & Contact Settings': 'कंपनी प्रोफाइल और संपर्क सेटिंग्स',
        'Ready for Transit': 'ट्रांजिट के लिए तैयार ✔',
        'SUCCESS / PAID': 'सफल / भुगतान पूर्ण ✔',
        'FAILURE / CANCELLED': 'विफल / रद्द ✖',
        'PENDING': 'लंबित ⏳',
        'View Live Website': 'लाइव वेबसाइट देखें',
        'Add Drill Machine': 'नई ड्रिल मशीन जोड़ें',

        // Video Player & Machine Detail
        'Open YouTube Video': 'यूट्यूब वीडियो देखें',
        'Video Mode': 'वीडियो मोड',
        '3D Spindle Simulator': '३D स्पिंडल सिम्युलेटर',
        'Real Photo': 'असली फोटो',
        'Live Machine Metrics': 'लाइव मशीन मेट्रिक्स',
        'Play Live Workshop Video': 'लाइव वर्कशॉप वीडियो चलाएं',
        'Live Factory Video Call': 'लाइव फैक्ट्री वीडियो कॉल',
        'Open 360° 12D Lab View': '३६०° १२D लैब व्यू खोलें',
        'Runout Accuracy': 'रनआउट सटीकता',
        'Coolant Circulation': 'कूलेंट परिसंचरण',
        'Vibration Index': 'वाइब्रेशन इंडेक्स',
        'Dynamic Factory Inventory': 'डायनामिक फैक्ट्री इन्वेंटरी',
        'Units Ready in Stock': 'यूनिट स्टॉक में तैयार',

        // Invoice & Proforma Bill
        'Proforma Tax Invoice': 'प्रोफॉर्मा टैक्स इनवॉइस',
        'Tax Invoice': 'टैक्स इनवॉइस',
        'Official Certified Industrial Tax Document': 'आधिकारिक प्रमाणित औद्योगिक टैक्स दस्तावेज़',
        'Bill To (Buyer / Consignee)': 'बिल टू (क्रेता / कंसाइनी)',
        'Dispatch & Consignment Details': 'डिस्पैच एवं कंसाइनमेंट विवरण',
        'Machinery Description & Technical Specifications': 'मशीनरी विवरण और तकनीकी विनिर्देश',
        'Taxable Value': 'कर योग्य मूल्य',
        'Total Billed': 'कुल बिल राशि',
        'Balance Due': 'शेष देय राशि',
        'PAID IN FULL': 'पूर्ण भुगतान प्राप्त ✔',
        'Company Bank Account': 'कंपनी बैंक खाता',
        'Amount in Words': 'शब्दों में राशि',
        'Standard Manufacturing & Warranty Terms': 'मानक विनिर्माण एवं वारंटी शर्तें'
    }
};

// Sorted key arrays (longest phrases first) for precision text matching
const UI_DICT_KEYS = {
    'gu': Object.keys(UI_TRANSLATIONS['gu']).sort((a, b) => b.length - a.length),
    'hi': Object.keys(UI_TRANSLATIONS['hi']).sort((a, b) => b.length - a.length)
};

// Full Recursive DOM Tree Translator (Converts text nodes accurately without corrupting HTML)
function escapeRegex(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

function translateFullDomTree(targetLang) {
    if (!targetLang || targetLang === 'en' || !UI_TRANSLATIONS[targetLang]) return;
    const dict = UI_TRANSLATIONS[targetLang];
    const keys = UI_DICT_KEYS[targetLang];

    // 1. Recursive TreeWalker over text nodes
    try {
        const walker = document.createTreeWalker(
            document.body,
            NodeFilter.SHOW_TEXT,
            {
                acceptNode: function(node) {
                    if (!node.nodeValue || !node.nodeValue.trim()) return NodeFilter.FILTER_REJECT;
                    const parent = node.parentElement;
                    if (!parent) return NodeFilter.FILTER_REJECT;
                    const tag = parent.tagName.toLowerCase();
                    if (tag === 'script' || tag === 'style' || tag === 'noscript' || tag === 'textarea' || tag === 'code' || tag === 'pre') {
                        return NodeFilter.FILTER_REJECT;
                    }
                    if (parent.closest('.notranslate') || parent.closest('#varshaChatbotWindow') || parent.closest('#google_translate_element') || parent.closest('.price-nowrap') || parent.closest('.currency-val')) {
                        return NodeFilter.FILTER_REJECT;
                    }
                    return NodeFilter.FILTER_ACCEPT;
                }
            }
        );

        const nodesToReplace = [];
        while (walker.nextNode()) {
            nodesToReplace.push(walker.currentNode);
        }

        nodesToReplace.forEach(node => {
            let val = node.nodeValue;
            const trimmed = val.trim();
            if (dict[trimmed]) {
                node.nodeValue = val.replace(trimmed, dict[trimmed]);
            } else {
                for (let i = 0; i < keys.length; i++) {
                    const k = keys[i];
                    if (k.length >= 4 && val.includes(k)) {
                        try {
                            const reg = new RegExp('(^|\\s|[.,;:!?"\'()])' + escapeRegex(k) + '(?=$|\\s|[.,;:!?"\'()])', 'g');
                            val = val.replace(reg, '$1' + dict[k]);
                        } catch(e) {
                            val = val.split(k).join(dict[k]);
                        }
                    }
                }
                node.nodeValue = val;
            }
        });
    } catch(err) {
        console.warn('DOM translation pass note:', err);
    }

    // 2. Also translate input placeholders & button values
    document.querySelectorAll('input[placeholder], textarea[placeholder]').forEach(el => {
        const ph = el.getAttribute('placeholder');
        if (ph && dict[ph.trim()]) {
            el.setAttribute('placeholder', dict[ph.trim()]);
        }
    });

    document.querySelectorAll('input[type="submit"], input[type="button"]').forEach(el => {
        const v = el.value;
        if (v && dict[v.trim()]) {
            el.value = dict[v.trim()];
        }
    });
}

function changeSiteLanguage(targetLang) {
    closeAllSiteLangMenus();

    setAllTransCookies(targetLang);

    try {
        localStorage.setItem('site_selected_language', targetLang);
    } catch(e) {}

    // If English requested, clear Google cookies and reload so original markup cleanly displays
    if (targetLang === 'en') {
        const domain = window.location.hostname;
        const deleteExp = "=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
        document.cookie = "googtrans" + deleteExp;
        document.cookie = "googtrans=" + deleteExp + " domain=" + domain + ";";
        document.cookie = "site_lang=en; path=/; max-age=31536000; SameSite=Lax";
        window.location.reload();
        return;
    }

    // 1. Instant full-tree in-memory DOM translation
    translateFullDomTree(targetLang);

    // 2. Trigger Google Translate combo if present
    const combo = document.querySelector('.goog-te-combo');
    if (combo) {
        combo.value = targetLang;
        combo.dispatchEvent(new Event('change'));
    }

    // 3. Update active UI button labels immediately
    const meta = {
        'gu': { flag: '🇮🇳', native: 'ગુજરાતી', code: 'GU' },
        'hi': { flag: '🇮🇳', native: 'हिन्दी', code: 'HI' },
        'en': { flag: '🇬🇧', native: 'English', code: 'EN' }
    }[targetLang];

    if (meta) {
        document.querySelectorAll('.active-lang-desktop').forEach(el => {
            el.textContent = meta.flag + ' ' + meta.native;
        });
        document.querySelectorAll('.active-lang-mobile').forEach(el => {
            el.textContent = meta.code;
        });
    }

    // Reload smoothly so full page and server-side components translate 100% seamlessly
    setTimeout(() => {
        window.location.reload();
    }, 280);
}

// Auto-Sync on Page Load across both Main Website and Admin Panel
document.addEventListener('DOMContentLoaded', function() {
    let saved = 'en';
    try {
        saved = localStorage.getItem('site_selected_language') || '<?= $currentLang ?>';
    } catch(e) {
        saved = '<?= $currentLang ?>';
    }

    if (saved && saved !== 'en') {
        setAllTransCookies(saved);
        // Instant full DOM conversion
        translateFullDomTree(saved);

        // Continuous DOM observer so dynamic elements (like cart/checkout/tables) stay translated
        if (window.MutationObserver) {
            let debounceTimer = null;
            const observer = new MutationObserver(function(mutations) {
                // Avoid infinite loops by checking if mutations are from translation font/text
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    translateFullDomTree(saved);
                }, 120);
            });
            observer.observe(document.body, { childList: true, subtree: true });
        }

        // Sync with Google Translate combo when it loads
        let checks = 0;
        const syncInt = setInterval(function() {
            checks++;
            const combo = document.querySelector('.goog-te-combo');
            if (combo) {
                if (combo.value !== saved) {
                    combo.value = saved;
                    combo.dispatchEvent(new Event('change'));
                }
                clearInterval(syncInt);
            }
            if (checks > 50) clearInterval(syncInt);
        }, 120);
    }
});
</script>
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" async defer></script>
<?php endif; ?>
