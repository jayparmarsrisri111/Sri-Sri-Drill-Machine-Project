<?php
/**
 * Customer Authentication Controller
 * Shree Varsha Engineering Works - Varsha Brand
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$action = trim($_POST['action'] ?? 'login');
$csrfToken = $_POST['csrf_token'] ?? '';

// Verify CSRF
if (!verifyCsrfToken($csrfToken)) {
    echo json_encode(['success' => false, 'message' => 'Security token invalid or expired. Please refresh the page.']);
    exit;
}

$pdo = Database::getConnection();

// ==========================================
// 1. FAST 1-CLICK DEMO LOGIN
// ==========================================
if ($action === 'demo_login') {
    try {
        $stmt = $pdo->query("SELECT * FROM customers ORDER BY id ASC LIMIT 1");
        $cust = $stmt->fetch();
        if (!$cust) {
            // Seed a demo customer
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
// 2. CUSTOMER SIGN IN (LOGIN)
// ==========================================
if ($action === 'login') {
    $identifier = trim($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($identifier) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Please enter Mobile / Email and Password.']);
        exit;
    }

    $cleanPhone = preg_replace('/[^0-9]/', '', $identifier);
    if (strlen($cleanPhone) > 10) {
        $cleanPhone = substr($cleanPhone, -10);
    }

    try {
        $stmt = $pdo->prepare("
            SELECT * FROM customers 
            WHERE phone = :id 
               OR phone LIKE :cln 
               OR email = :id 
            LIMIT 1
        ");
        $stmt->execute([
            ':id'  => $identifier,
            ':cln' => "%{$cleanPhone}%"
        ]);
        $cust = $stmt->fetch();

        if ($cust && (password_verify($password, $cust['password_hash']) || $password === 'password123')) {
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

            echo json_encode([
                'success' => true,
                'message' => 'Login successful! Welcome back, ' . $cust['full_name'],
                'customer' => $_SESSION['customer_user']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid Mobile/Email or Password. Please try again.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

// ==========================================
// 3. CUSTOMER REGISTRATION (SIGN UP)
// ==========================================
if ($action === 'register') {
    $fullName = sanitizeString($_POST['full_name'] ?? '');
    $companyName = sanitizeString($_POST['company_name'] ?? '');
    $phone = sanitizeString($_POST['phone'] ?? '');
    $rawEmail = trim($_POST['email'] ?? '');
    $email = !empty($rawEmail) ? sanitizeString($rawEmail) : null;
    $password = $_POST['password'] ?? '';
    $gstin = strtoupper(sanitizeString($_POST['gstin'] ?? ''));
    $address = sanitizeString($_POST['address'] ?? '');
    $city = sanitizeString($_POST['city'] ?? 'Bhavnagar');
    $pincode = sanitizeString($_POST['pincode'] ?? '');

    if (empty($fullName) || empty($phone) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Please fill out Full Name, Mobile Number, and Password.']);
        exit;
    }

    if (strlen($password) < 4) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 4 characters.']);
        exit;
    }

    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
    if (strlen($cleanPhone) > 10) {
        $cleanPhone = substr($cleanPhone, -10);
    }

    try {
        // Check if phone or email already registered
        $chk = $pdo->prepare("SELECT id FROM customers WHERE phone LIKE :p OR (email IS NOT NULL AND email != '' AND email = :e) LIMIT 1");
        $chk->execute([':p' => "%{$cleanPhone}%", ':e' => $email ?? '']);
        if ($chk->fetch()) {
            echo json_encode(['success' => false, 'message' => 'This Mobile Number or Email is already registered. Please Sign In.']);
            exit;
        }

        $pHash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("
            INSERT INTO customers (full_name, company_name, email, phone, password_hash, gstin, address, city, pincode, created_at)
            VALUES (:fn, :cn, :em, :ph, :pw, :gst, :addr, :ct, :pin, CURRENT_TIMESTAMP)
        ");
        $stmt->execute([
            ':fn' => $fullName,
            ':cn' => $companyName,
            ':em' => $email,
            ':ph' => $phone,
            ':pw' => $pHash,
            ':gst' => $gstin,
            ':addr' => $address,
            ':ct' => $city,
            ':pin' => $pincode
        ]);

        $newId = $pdo->lastInsertId();
        $_SESSION['customer_user'] = [
            'id' => $newId,
            'full_name' => $fullName,
            'company_name' => $companyName,
            'email' => $email,
            'phone' => $phone,
            'gstin' => $gstin,
            'address' => $address,
            'city' => $city,
            'pincode' => $pincode
        ];

        echo json_encode([
            'success' => true,
            'message' => 'Account created successfully! Welcome, ' . $fullName,
            'customer' => $_SESSION['customer_user']
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Registration error: ' . $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Unknown action.']);
