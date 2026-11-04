<?php
/**
 * Security Hardening Engine
 * Shree Varsha Engineering Works
 */

if (session_status() === PHP_SESSION_NONE && php_sapi_name() !== 'cli') {
    // Hardened session configuration
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_samesite', 'Lax');
    @session_start();
}

// 1. HTTP Security Headers
function applySecurityHeaders(): void {
    if (!headers_sent()) {
        header("X-Frame-Options: SAMEORIGIN");
        header("X-Content-Type-Options: nosniff");
        header("X-XSS-Protection: 1; mode=block");
        header("Referrer-Policy: strict-origin-when-cross-origin");
        // CSP allows inline styles, fonts, Razorpay checkout, and images
        header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval' https: data: blob:; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://checkout.razorpay.com https://cdnjs.cloudflare.com; frame-src 'self' https://api.razorpay.com https://checkout.razorpay.com; connect-src 'self' https://api.razorpay.com https://lumberjack.razorpay.com https://*.razorpay.com; img-src 'self' data: blob: https:;");
    }
}

// 2. CSRF Token Protection
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

// 3. Rate Limiting / Brute-Force Prevention
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

// 4. Strict Sanitization
function sanitizeString(?string $data): string {
    if ($data === null) return '';
    return htmlspecialchars(trim(strip_tags($data)), ENT_QUOTES, 'UTF-8');
}

function sanitizeNumber($data, int $default = 0): int {
    return filter_var($data, FILTER_VALIDATE_INT) !== false ? (int)$data : $default;
}

// Apply headers on include
applySecurityHeaders();
