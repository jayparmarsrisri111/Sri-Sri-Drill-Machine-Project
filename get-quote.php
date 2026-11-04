<?php
/**
 * Ajax Endpoint for Customer Quotation / Inquiries
 * Shree Varsha Engineering Works
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

// 1. Rate Limit Check (Prevent spam bots)
if (!checkRateLimit('inquiry_submission', 5, 60)) {
    echo json_encode(['status' => 'error', 'message' => 'Too many submissions. Please wait a minute before sending another inquiry, or call us directly.']);
    exit;
}

// 2. CSRF Token Verification
$submittedToken = $_POST['csrf_token'] ?? '';
if (!verifyCsrfToken($submittedToken)) {
    echo json_encode(['status' => 'error', 'message' => 'Security token invalid or expired. Please refresh the page and try again.']);
    exit;
}

$customerName = sanitizeString($_POST['customer_name'] ?? '');
$companyName  = sanitizeString($_POST['company_name'] ?? '');
$phone         = sanitizeString($_POST['phone'] ?? '');
$email         = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL) ? trim($_POST['email']) : '';
$machineName  = sanitizeString($_POST['machine_name'] ?? 'General Drill Machine Inquiry');
$machineId    = !empty($_POST['machine_id']) ? intval($_POST['machine_id']) : null;
$messageText  = sanitizeString($_POST['message'] ?? 'Please provide price quotation and technical specifications.');
$openWhatsApp = !empty($_POST['open_whatsapp']);

if (empty($customerName) || empty($phone)) {
    echo json_encode(['status' => 'error', 'message' => 'Please provide your Name and Mobile Number.']);
    exit;
}

try {
    $pdo = Database::getConnection();

    $stmt = $pdo->prepare("
        INSERT INTO inquiries 
        (customer_name, company_name, phone, email, machine_id, machine_name, message, status, created_at)
        VALUES (:customer_name, :company_name, :phone, :email, :machine_id, :machine_name, :message, 'new', CURRENT_TIMESTAMP)
    ");

    $stmt->execute([
        ':customer_name' => $customerName,
        ':company_name'  => $companyName,
        ':phone'         => $phone,
        ':email'         => $email,
        ':machine_id'    => $machineId,
        ':machine_name'  => $machineName,
        ':message'       => $messageText
    ]);

    // Prepare WhatsApp Message
    $waText = "Hello Shree Varsha Engineering Works,\n"
            . "I would like to get a quotation.\n\n"
            . "📌 Machine: " . $machineName . "\n"
            . "👤 Name: " . $customerName . ($companyName ? " (" . $companyName . ")" : "") . "\n"
            . "📞 Phone: " . $phone . "\n"
            . "✉️ Email: " . $email . "\n"
            . "📝 Requirement: " . $messageText;

    $whatsAppUrl = getWhatsAppLink(COMPANY_WHATSAPP, $waText);

    echo json_encode([
        'status' => 'success',
        'message' => 'Thank you ' . $customerName . '! Your quotation request has been received by Shree Varsha Engineering Works. Our technical team will call you shortly.',
        'whatsapp_url' => $openWhatsApp ? $whatsAppUrl : null
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to save inquiry: ' . $e->getMessage()
    ]);
}
