<?php
/**
 * Global Configuration File
 * Shree Varsha Engineering Works - Drill Machine Web Application
 */

// Error reporting
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Security engine & session
require_once __DIR__ . '/security.php';

// Site URL helper (Supports Localhost, XAMPP, Railway, Render and SSL proxies)
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
    || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
$protocol = $isHttps ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$rawScript = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
if (php_sapi_name() === 'cli' || preg_match('/^[a-zA-Z]:/', $rawScript)) {
    $basePath = '/Sri Sri Drill Machine Project';
} else {
    $scriptDir = dirname($rawScript);
    $basePath = preg_replace('#/(admin|includes|config|database).*$#', '', $scriptDir);
}
$baseUrl = rtrim($protocol . $host . $basePath, '/');
define('BASE_URL', $baseUrl);

// Dynamic Site Settings Cache
class SiteSettings {
    private static ?array $settings = null;

    public static function getAll(): array {
        if (self::$settings !== null) {
            return self::$settings;
        }

        self::$settings = [];
        try {
            require_once __DIR__ . '/database.php';
            $pdo = Database::getConnection();
            $rows = $pdo->query("SELECT setting_key, setting_value FROM site_settings")->fetchAll();
            foreach ($rows as $r) {
                self::$settings[$r['setting_key']] = $r['setting_value'];
            }
        } catch (Exception $e) {
            // Fallback defaults if DB connection not ready
        }
        return self::$settings;
    }

    public static function get(string $key, string $default = ''): string {
        $all = self::getAll();
        return $all[$key] ?? $default;
    }
}

function getSetting(string $key, string $default = ''): string {
    return SiteSettings::get($key, $default);
}

// Global dynamically-computed constants with DB priority (Updates in real-time from Admin Company Settings)
define('APP_NAME', getSetting('company_name', 'Shree Varsha Engineering Works'));
define('APP_TAGLINE', getSetting('tagline', 'Precision Drilling Machine Specialists & Heavy Duty Machinery'));
define('COMPANY_PHONE_1', getSetting('phone_1', '+91 9824913555'));
define('COMPANY_PHONE_1_CLEAN', preg_replace('/[^0-9]/', '', COMPANY_PHONE_1));
define('COMPANY_PHONE_2', getSetting('phone_2', '+91 9714611331'));
define('COMPANY_PHONE_2_CLEAN', preg_replace('/[^0-9]/', '', COMPANY_PHONE_2));
define('COMPANY_EMAIL', getSetting('email', 'jitubhaivarsh@gmail.com'));
define('COMPANY_WHATSAPP', getSetting('whatsapp', '919824913555'));
define('COMPANY_CONTACT_PERSON', getSetting('contact_person', 'Jitendrabhai Parmar'));
define('COMPANY_ADDRESS', getSetting('address', 'Shed No. L/786, GIDC, Nr. Hadanagar, Bhavnagar - 364003, Gujarat, India'));
define('COMPANY_CITY', getSetting('city', 'Bhavnagar'));
define('COMPANY_STATE', getSetting('state', 'Gujarat'));
define('COMPANY_PINCODE', getSetting('pincode', '364003'));

// Brand and Signatory constants
define('BRAND_NAME', getSetting('brand_name', 'VARSHA BRAND'));
define('AUTHORIZED_SIGNATORY', getSetting('authorized_signatory', 'Jay Parmar'));
define('COMPANY_GSTIN', getSetting('gstin', '24AJHPP0087E1ZD'));

// Production Experience & Factory Stats (Configurable from Admin Settings)
define('EXPERIENCE_YEARS', getSetting('experience_years', '25+'));
define('MACHINES_BUILT', getSetting('machines_built', '1800+'));
define('HAPPY_CLIENTS', getSetting('happy_clients', '100%'));

// Banking & UPI Payment Gateway Settings
define('COMPANY_BANK_NAME', getSetting('bank_name', 'State Bank of India'));
define('COMPANY_BANK_ACC', getSetting('bank_account_no', '384910284729'));
define('COMPANY_BANK_IFSC', getSetting('bank_ifsc', 'SBIN0001234'));
define('COMPANY_UPI_ID', getSetting('upi_id', '9824913555@okbizaxis'));

// Standard machine catalog pricing helper (with new models)
function getMachinePrice(int $id): float {
    static $prices = [
        1 => 285000.00,
        2 => 395000.00,
        3 => 575000.00,
        4 => 85000.00,
        5 => 135000.00,
        6 => 195000.00,
        7 => 48000.00,
        8 => 125000.00,
        9 => 23500.00,
        10 => 73000.00,
        11 => 25000.00,
        12 => 48500.00,
        101 => 14500.00,
        102 => 22000.00,
        103 => 18500.00,
        104 => 12500.00,
        105 => 3800.00,
        106 => 4200.00,
    ];
    return $prices[$id] ?? 25000.00;
}

// Razorpay Credentials (Test / Live credentials from user provided rzp-key.csv)
define('RAZORPAY_KEY_ID', getSetting('razorpay_key_id', 'rzp_test_SLvrGI5oOB3cDO'));
define('RAZORPAY_KEY_SECRET', getSetting('razorpay_key_secret', 'fs37YWN1P1N8Gt5HkC8PVSUB'));
define('RAZORPAY_CURRENCY', 'INR');

// Helper function for asset URLs
function assetUrl($path) {
    return BASE_URL . '/' . ltrim($path, '/');
}

// Helper function to safely escape HTML
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// Helper for WhatsApp redirect link with custom message
function getWhatsAppLink($phone = null, $text = '') {
    if (empty($phone)) {
        $phone = COMPANY_WHATSAPP;
    }
    if (empty($text)) {
        $text = "Hello " . APP_NAME . ", I am interested in your Drill Machines. Please share specifications and quotation.";
    }
    return "https://wa.me/" . preg_replace('/[^0-9]/', '', $phone) . "?text=" . urlencode($text);
}
