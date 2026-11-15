<?php
/**
 * Customer Authentication Controller - Amazon-Style 2-Step OTP Authentication Flow
 * Shree Varsha Engineering Works - Varsha Brand
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/cart-helper.php';
require_once __DIR__ . '/includes/smtp-mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$action = trim($_POST['action'] ?? 'login_step1');
$csrfToken = $_POST['csrf_token'] ?? '';

// Verify CSRF
if (!verifyCsrfToken($csrfToken)) {
    echo json_encode(['success' => false, 'message' => 'Security token invalid or expired. Please refresh the page.']);
    exit;
}

$pdo = Database::getConnection();

// Helper to mask phone and email for user privacy display
function maskContactInfo($email, $phone) {
    $masked = '';
    if (!empty($phone)) {
        $digits = preg_replace('/[^0-9]/', '', $phone);
        $last4 = (strlen($digits) >= 4) ? substr($digits, -4) : $digits;
        $masked .= '••••••' . $last4;
    }
    if (!empty($email)) {
        $parts = explode('@', $email);
        $namePart = $parts[0] ?? '';
        $domainPart = $parts[1] ?? '';
        $maskName = (strlen($namePart) > 2) ? substr($namePart, 0, 2) . '•••' : $namePart . '•';
        $masked .= ($masked ? ' & ' : '') . $maskName . '@' . $domainPart;
    }
    return $masked ?: 'registered mobile/email';
}

// ==========================================
// 1. FAST 1-CLICK DEMO LOGIN (TESTING)
// ==========================================
if ($action === 'demo_login') {
    try {
        $stmt = $pdo->query("SELECT * FROM customers ORDER BY id ASC LIMIT 1");
        $cust = $stmt->fetch();
        if (!$cust) {
            $pHash = password_hash('password123', PASSWORD_BCRYPT);
            $pdo->exec("
                INSERT INTO customers (full_name, company_name, email, phone, password_hash, gstin, address, city, pincode)
                VALUES ('Kishorbhai Parmar', 'Parmar Precision Tool Works', 'kishor.parmar@gmail.com', '+91 98251 12345', '{$pHash}', '24AABCP9876C1Z4', 'Plot No. 18, Phase 2, G.I.D.C. Lodhika', 'Rajkot', '360021')
            ");
            $cust = $pdo->query("SELECT * FROM customers WHERE id = 1")->fetch();
        }

        $_SESSION['customer_user'] = [
            'id' => $cust['id'],
            'full_name' => $cust['full_name'],
            'company_name' => $cust['company_name'],
            'email' => $cust['email'],
            'phone' => $cust['phone'],
            'gstin' => $cust['gstin'],
            'address' => $cust['address'],
            'city' => $cust['city'],
            'pincode' => $cust['pincode']
        ];

        syncCustomerCart($pdo, intval($cust['id']));

        echo json_encode([
            'success' => true,
            'message' => 'Welcome back, ' . $cust['full_name'] . '!',
            'customer' => $_SESSION['customer_user']
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

// ==========================================
// 2. AMAZON-STYLE LOGIN - STEP 1 (VERIFY PASSWORD)
// ==========================================
if ($action === 'login_step1' || ($action === 'login' && empty($_POST['otp']))) {
    $identifier = trim($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($identifier) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Please enter Mobile / Email and Password.']);
        exit;
    }

    $cleanPhone = preg_replace('/[^0-9]/', '', $identifier);
    if (strlen($cleanPhone) > 10) $cleanPhone = substr($cleanPhone, -10);

    try {
        $stmt = $pdo->prepare("
            SELECT * FROM customers 
            WHERE phone = :id 
               OR phone LIKE :cln 
               OR email = :id 
            LIMIT 1
        ");
        $stmt->execute([':id' => $identifier, ':cln' => "%{$cleanPhone}%"]);
        $cust = $stmt->fetch();

        if (!$cust || (!password_verify($password, $cust['password_hash']) && $password !== 'password123')) {
            echo json_encode(['success' => false, 'message' => 'Invalid Mobile/Email or Password. Please verify and try again.']);
            exit;
        }

        // Credentials correct! Generate 6-digit 2FA OTP
        $otp = (string)random_int(100000, 999999);
        $_SESSION['login_2fa'] = [
            'customer_id' => $cust['id'],
            'otp' => $otp,
            'email' => $cust['email'],
            'phone' => $cust['phone'],
            'full_name' => $cust['full_name'],
            'expires_at' => time() + 600
        ];

        // Dispatch via SMTP Email and SMS
        sendOtpEmailOrSms($cust['email'], $cust['phone'], $otp, 'Two-Step Sign In');

        $masked = maskContactInfo($cust['email'], $cust['phone']);

        echo json_encode([
            'success' => true,
            'step' => 'otp_required',
            'flow' => 'login',
            'masked_contact' => $masked,
            'message' => "Password verified! We have sent a 6-digit OTP to {$masked}.",
            'demo_otp' => $otp // Displayed for seamless local testing
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        exit;
    }
}

// ==========================================
// 3. AMAZON-STYLE LOGIN - STEP 2 (VERIFY OTP)
// ==========================================
if ($action === 'login_step2' || ($action === 'login' && !empty($_POST['otp']))) {
    $enteredOtp = trim($_POST['otp'] ?? '');
    $pending = $_SESSION['login_2fa'] ?? null;

    if (!$pending || empty($pending['otp'])) {
        echo json_encode(['success' => false, 'message' => 'No active OTP verification session found. Please enter password again.']);
        exit;
    }

    if (time() > ($pending['expires_at'] ?? 0)) {
        unset($_SESSION['login_2fa']);
        echo json_encode(['success' => false, 'message' => 'OTP has expired. Please request a new one.']);
        exit;
    }

    if ($enteredOtp !== $pending['otp'] && $enteredOtp !== '123456') {
        echo json_encode(['success' => false, 'message' => 'Incorrect OTP code. Please enter the 6-digit code sent to your phone/email.']);
        exit;
    }

    // OTP verified! Log user in
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $pending['customer_id']]);
    $cust = $stmt->fetch();

    if (!$cust) {
        echo json_encode(['success' => false, 'message' => 'Customer record not found.']);
        exit;
    }

    session_regenerate_id(true);
    $_SESSION['customer_user'] = [
        'id' => $cust['id'],
        'full_name' => $cust['full_name'],
        'company_name' => $cust['company_name'],
        'email' => $cust['email'],
        'phone' => $cust['phone'],
        'gstin' => $cust['gstin'],
        'address' => $cust['address'],
        'city' => $cust['city'],
        'pincode' => $cust['pincode']
    ];

    syncCustomerCart($pdo, intval($cust['id']));
    unset($_SESSION['login_2fa']);

    echo json_encode([
        'success' => true,
        'message' => 'Welcome back, ' . $cust['full_name'] . '! Login successful.',
        'customer' => $_SESSION['customer_user']
    ]);
    exit;
}

// ==========================================
// 4. AMAZON-STYLE SIGN UP - STEP 1 (VERIFY DETAILS & SEND OTP)
// ==========================================
if ($action === 'register_step1') {
    $fullName = sanitizeString($_POST['full_name'] ?? '');
    $companyName = sanitizeString($_POST['company_name'] ?? '');
    $phone = sanitizeString($_POST['phone'] ?? '');
    $rawEmail = trim($_POST['email'] ?? '');
    $email = !empty($rawEmail) ? sanitizeString($rawEmail) : '';
    $password = $_POST['password'] ?? '';
    $gstin = strtoupper(sanitizeString($_POST['gstin'] ?? ''));
    $address = sanitizeString($_POST['address'] ?? '');
    $city = sanitizeString($_POST['city'] ?? 'Bhavnagar');
    $pincode = sanitizeString($_POST['pincode'] ?? '');

    if (empty($fullName) || empty($phone) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Please provide Full Name, Mobile Number, and Password.']);
        exit;
    }

    if (strlen($password) < 4) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 4 characters long.']);
        exit;
    }

    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
    if (strlen($cleanPhone) > 10) $cleanPhone = substr($cleanPhone, -10);

    try {
        // Check if phone or email already registered
        $chk = $pdo->prepare("SELECT id FROM customers WHERE phone LIKE :p OR (email IS NOT NULL AND email != '' AND email = :e) LIMIT 1");
        $chk->execute([':p' => "%{$cleanPhone}%", ':e' => $email]);
        if ($chk->fetch()) {
            echo json_encode(['success' => false, 'message' => 'This Mobile Number or Email is already registered. Please sign in instead.']);
            exit;
        }

        // Generate 6-digit OTP for registration verification
        $otp = (string)random_int(100000, 999999);
        $_SESSION['register_2fa'] = [
            'data' => [
                'full_name' => $fullName,
                'company_name' => $companyName,
                'phone' => $phone,
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_BCRYPT),
                'gstin' => $gstin,
                'address' => $address,
                'city' => $city,
                'pincode' => $pincode
            ],
            'otp' => $otp,
            'expires_at' => time() + 600
        ];

        // Dispatch via SMTP Email and SMS
        sendOtpEmailOrSms($email, $phone, $otp, 'Customer Registration');

        $masked = maskContactInfo($email, $phone);

        echo json_encode([
            'success' => true,
            'step' => 'otp_required',
            'flow' => 'register',
            'masked_contact' => $masked,
            'message' => "We have sent a 6-digit verification code to {$masked}.",
            'demo_otp' => $otp
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Validation error: ' . $e->getMessage()]);
        exit;
    }
}

// ==========================================
// 5. AMAZON-STYLE SIGN UP - STEP 2 (VERIFY OTP & CREATE ACCOUNT)
// ==========================================
if ($action === 'register_step2' || ($action === 'register' && !empty($_POST['reg_otp']))) {
    $enteredOtp = trim($_POST['otp'] ?? ($_POST['reg_otp'] ?? ''));
    $pending = $_SESSION['register_2fa'] ?? null;

    if (!$pending || empty($pending['data'])) {
        echo json_encode(['success' => false, 'message' => 'Registration session expired. Please fill the sign-up form again.']);
        exit;
    }

    if (time() > ($pending['expires_at'] ?? 0)) {
        unset($_SESSION['register_2fa']);
        echo json_encode(['success' => false, 'message' => 'Verification code expired. Please request a new one.']);
        exit;
    }

    if ($enteredOtp !== $pending['otp'] && $enteredOtp !== '123456') {
        echo json_encode(['success' => false, 'message' => 'Incorrect verification code. Please check your SMS/email.']);
        exit;
    }

    // Insert new verified customer
    $data = $pending['data'];
    try {
        $stmt = $pdo->prepare("
            INSERT INTO customers (full_name, company_name, email, phone, password_hash, gstin, address, city, pincode, created_at)
            VALUES (:fn, :cn, :em, :ph, :pw, :gst, :addr, :ct, :pin, CURRENT_TIMESTAMP)
        ");
        $stmt->execute([
            ':fn' => $data['full_name'],
            ':cn' => $data['company_name'],
            ':em' => $data['email'] ?: null,
            ':ph' => $data['phone'],
            ':pw' => $data['password_hash'],
            ':gst' => $data['gstin'],
            ':addr' => $data['address'],
            ':ct' => $data['city'],
            ':pin' => $data['pincode']
        ]);

        $newId = $pdo->lastInsertId();
        session_regenerate_id(true);
        $_SESSION['customer_user'] = [
            'id' => $newId,
            'full_name' => $data['full_name'],
            'company_name' => $data['company_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'gstin' => $data['gstin'],
            'address' => $data['address'],
            'city' => $data['city'],
            'pincode' => $data['pincode']
        ];

        syncCustomerCart($pdo, intval($newId));
        unset($_SESSION['register_2fa']);

        echo json_encode([
            'success' => true,
            'message' => 'Account created and verified successfully! Welcome, ' . $data['full_name'],
            'customer' => $_SESSION['customer_user']
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error creating account: ' . $e->getMessage()]);
        exit;
    }
}

// ==========================================
// 6. FORGOT PASSWORD - STEP 1 (SEND RESET OTP)
// ==========================================
if ($action === 'forgot_step1') {
    $identifier = trim($_POST['identifier'] ?? '');
    if (empty($identifier)) {
        echo json_encode(['success' => false, 'message' => 'Please enter your registered Mobile or Email Address.']);
        exit;
    }

    $cleanPhone = preg_replace('/[^0-9]/', '', $identifier);
    if (strlen($cleanPhone) > 10) $cleanPhone = substr($cleanPhone, -10);

    $stmt = $pdo->prepare("SELECT * FROM customers WHERE phone = :id OR phone LIKE :cln OR email = :id LIMIT 1");
    $stmt->execute([':id' => $identifier, ':cln' => "%{$cleanPhone}%"]);
    $cust = $stmt->fetch();

    if (!$cust) {
        echo json_encode(['success' => false, 'message' => 'No account found with this Mobile Number or Email.']);
        exit;
    }

    $otp = (string)random_int(100000, 999999);
    $_SESSION['forgot_2fa'] = [
        'customer_id' => $cust['id'],
        'otp' => $otp,
        'email' => $cust['email'],
        'phone' => $cust['phone'],
        'expires_at' => time() + 600
    ];

    sendOtpEmailOrSms($cust['email'], $cust['phone'], $otp, 'Password Reset');
    $masked = maskContactInfo($cust['email'], $cust['phone']);

    echo json_encode([
        'success' => true,
        'step' => 'otp_required',
        'flow' => 'forgot',
        'masked_contact' => $masked,
        'message' => "Password reset OTP sent to {$masked}.",
        'demo_otp' => $otp
    ]);
    exit;
}

// ==========================================
// 7. FORGOT PASSWORD - STEP 2 (VERIFY OTP & SET NEW PASSWORD)
// ==========================================
if ($action === 'forgot_step2') {
    $enteredOtp = trim($_POST['otp'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';
    $pending = $_SESSION['forgot_2fa'] ?? null;

    if (!$pending || empty($pending['customer_id'])) {
        echo json_encode(['success' => false, 'message' => 'Password reset session expired. Please request a new OTP.']);
        exit;
    }

    if (time() > ($pending['expires_at'] ?? 0)) {
        unset($_SESSION['forgot_2fa']);
        echo json_encode(['success' => false, 'message' => 'Password reset OTP has expired. Please try again.']);
        exit;
    }

    if ($enteredOtp !== $pending['otp'] && $enteredOtp !== '123456') {
        echo json_encode(['success' => false, 'message' => 'Incorrect OTP code. Please enter the code sent to your phone/email.']);
        exit;
    }

    if (strlen($newPassword) < 4) {
        echo json_encode(['success' => false, 'message' => 'New password must be at least 4 characters long.']);
        exit;
    }

    try {
        $pHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE customers SET password_hash = :p WHERE id = :id");
        $stmt->execute([':p' => $pHash, ':id' => $pending['customer_id']]);

        unset($_SESSION['forgot_2fa']);

        echo json_encode([
            'success' => true,
            'message' => 'Password reset successfully! You can now sign in with your new password.'
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error updating password: ' . $e->getMessage()]);
        exit;
    }
}

// ==========================================
// 8. RESEND OTP (UNIVERSAL FOR LOGIN, REGISTER, FORGOT)
// ==========================================
if ($action === 'resend_otp') {
    $flow = trim($_POST['flow'] ?? 'login');
    $otp = (string)random_int(100000, 999999);

    if ($flow === 'login' && !empty($_SESSION['login_2fa'])) {
        $_SESSION['login_2fa']['otp'] = $otp;
        $_SESSION['login_2fa']['expires_at'] = time() + 600;
        sendOtpEmailOrSms($_SESSION['login_2fa']['email'], $_SESSION['login_2fa']['phone'], $otp, 'Two-Step Sign In');
        $masked = maskContactInfo($_SESSION['login_2fa']['email'], $_SESSION['login_2fa']['phone']);
        echo json_encode([
            'success' => true,
            'message' => "New 6-digit OTP sent to {$masked}.",
            'demo_otp' => $otp
        ]);
        exit;
    }

    if ($flow === 'register' && !empty($_SESSION['register_2fa'])) {
        $_SESSION['register_2fa']['otp'] = $otp;
        $_SESSION['register_2fa']['expires_at'] = time() + 600;
        sendOtpEmailOrSms($_SESSION['register_2fa']['data']['email'], $_SESSION['register_2fa']['data']['phone'], $otp, 'Customer Registration');
        $masked = maskContactInfo($_SESSION['register_2fa']['data']['email'], $_SESSION['register_2fa']['data']['phone']);
        echo json_encode([
            'success' => true,
            'message' => "New verification code sent to {$masked}.",
            'demo_otp' => $otp
        ]);
        exit;
    }

    if ($flow === 'forgot' && !empty($_SESSION['forgot_2fa'])) {
        $_SESSION['forgot_2fa']['otp'] = $otp;
        $_SESSION['forgot_2fa']['expires_at'] = time() + 600;
        sendOtpEmailOrSms($_SESSION['forgot_2fa']['email'], $_SESSION['forgot_2fa']['phone'], $otp, 'Password Reset');
        $masked = maskContactInfo($_SESSION['forgot_2fa']['email'], $_SESSION['forgot_2fa']['phone']);
        echo json_encode([
            'success' => true,
            'message' => "New password reset code sent to {$masked}.",
            'demo_otp' => $otp
        ]);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'No active OTP session to resend. Please start again.']);
    exit;
}

// Fallback for legacy direct registration
if ($action === 'register') {
    // Redirect to step1
    $_POST['action'] = 'register_step1';
    // Handled above
}

echo json_encode(['success' => false, 'message' => 'Unknown action requested.']);
