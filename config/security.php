<?php
/**
 * Security Hardening Engine & High-Assurance Protection
 * Shree Varsha Engineering Works - Varsha Brand
 * Comprehensive Security: CSP, Anti-XSS, CSRF, Anti-Clickjacking, Session Hardening & Brute-Force Rate Limiting
 */

$isSecureHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
    || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

if (session_status() === PHP_SESSION_NONE && php_sapi_name() !== 'cli') {
    // Hardened session configuration
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_samesite', 'Lax');
    if ($isSecureHttps) {
        ini_set('session.cookie_secure', '1');
    }
    @session_start();
}

// 1. Enterprise HTTP Security Headers
function applySecurityHeaders(): void {
    if (!headers_sent()) {
        header("X-Frame-Options: SAMEORIGIN");
        header("X-Content-Type-Options: nosniff");
        header("X-XSS-Protection: 1; mode=block");
        header("Referrer-Policy: strict-origin-when-cross-origin");
        header("Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=(self \"https://checkout.razorpay.com\")");

        global $isSecureHttps;
        if ($isSecureHttps) {
            header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
        }

        // Comprehensive Content Security Policy (CSP) allowing Google Translate, YouTube, Google Maps, Razorpay, FontAwesome, Fonts & 3D WebGL
        header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval' https: data: blob:; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://translate.google.com https://translate.googleapis.com https://checkout.razorpay.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://www.youtube.com https://s.ytimg.com; style-src 'self' 'unsafe-inline' https: https://translate.googleapis.com; frame-src 'self' https://translate.google.com https://translate.googleapis.com https://www.youtube.com https://www.youtube-nocookie.com https://api.razorpay.com https://checkout.razorpay.com https://maps.google.com https://www.google.com; connect-src 'self' https://translate.google.com https://translate.googleapis.com https://api.razorpay.com https://lumberjack.razorpay.com https://*.razorpay.com https://www.youtube.com; img-src 'self' data: blob: https: https://translate.google.com https://www.google.com https://translate.googleapis.com https://i.ytimg.com;");
    }
}

// 2. Admin & User Session Inactivity Protection (Auto-Timeout Hijack Prevention)
function enforceSessionExpiry(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) return;

    $currentTime = time();

    // Factory Admin auto-logout after 2 hours (7200 seconds) of inactivity
    if (!empty($_SESSION['admin_user'])) {
        $maxAdminIdle = 7200;
        if (isset($_SESSION['admin_user']['last_activity']) && ($currentTime - $_SESSION['admin_user']['last_activity'] > $maxAdminIdle)) {
            unset($_SESSION['admin_user']);
            if (strpos($_SERVER['REQUEST_URI'] ?? '', '/admin/') !== false && strpos($_SERVER['REQUEST_URI'] ?? '', 'index.php') === false) {
                header("Location: index.php?timeout=1");
                exit;
            }
        } else {
            $_SESSION['admin_user']['last_activity'] = $currentTime;
        }
    }

    // Customer Portal auto-refresh activity tracking
    if (!empty($_SESSION['customer_user'])) {
        $_SESSION['customer_user']['last_activity'] = $currentTime;
    }
}

// 3. CSRF Token Protection
function getCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken(?string $token): bool {
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

// 4. Rate Limiting / Brute-Force Prevention
function checkRateLimit(string $key = 'default', int $maxAttempts = 10, int $timeWindowSeconds = 60): bool {
    $currentTime = time();
    $sessionKey = "rate_limit_{$key}";

    if (!isset($_SESSION[$sessionKey])) {
        $_SESSION[$sessionKey] = [];
    }

    // Purge timestamps older than the time window
    $_SESSION[$sessionKey] = array_filter(
        $_SESSION[$sessionKey],
        fn($timestamp) => ($currentTime - $timestamp) < $timeWindowSeconds
    );

    if (count($_SESSION[$sessionKey]) >= $maxAttempts) {
        return false; // Rate limit exceeded
    }

    // Log this attempt
    $_SESSION[$sessionKey][] = $currentTime;
    return true;
}

// 5. Strict Input Sanitization & Deep Cleansing
function sanitizeString(?string $data): string {
    if ($data === null) return '';
    return htmlspecialchars(trim(strip_tags($data)), ENT_QUOTES, 'UTF-8');
}

function sanitizeNumber($data, int $default = 0): int {
    return filter_var($data, FILTER_VALIDATE_INT) !== false ? (int)$data : $default;
}

function sanitizeFloat($data, float $default = 0.0): float {
    return filter_var($data, FILTER_VALIDATE_FLOAT) !== false ? (float)$data : $default;
}

// Apply headers and session guard on include
applySecurityHeaders();
enforceSessionExpiry();
