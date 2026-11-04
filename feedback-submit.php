<?php
/**
 * Customer Feedback Submission Handler
 * Shree Varsha Engineering Works - Varsha Brand
 */

header('Content-Type: application/json');

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$csrfToken = $_POST['csrf_token'] ?? '';
if (!verifyCsrfToken($csrfToken)) {
    echo json_encode(['success' => false, 'message' => 'Security token invalid or expired. Please refresh the page.']);
    exit;
}

$name = sanitizeString($_POST['customer_name'] ?? '');
$company = sanitizeString($_POST['company_name'] ?? '');
$phone = sanitizeString($_POST['phone'] ?? '');
$email = sanitizeString($_POST['email'] ?? '');
$rating = intval($_POST['rating'] ?? 5);
if ($rating < 1 || $rating > 5) $rating = 5;

$productName = sanitizeString($_POST['product_name'] ?? '');
$title = sanitizeString($_POST['feedback_title'] ?? '');
$comments = sanitizeString($_POST['comments'] ?? '');
$city = sanitizeString($_POST['city'] ?? 'Gujarat');

if (empty($name) || empty($comments)) {
    echo json_encode(['success' => false, 'message' => 'Please provide your Name and Review Comments.']);
    exit;
}

try {
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare("
        INSERT INTO feedbacks (customer_name, company_name, phone, email, rating, product_name, feedback_title, comments, city, is_approved, is_featured)
        VALUES (:n, :cmp, :ph, :em, :rt, :p, :t, :c, :ct, 1, 1)
    ");
    $stmt->execute([
        ':n' => $name,
        ':cmp' => $company,
        ':ph' => $phone,
        ':em' => $email,
        ':rt' => $rating,
        ':p' => $productName,
        ':t' => $title,
        ':c' => $comments,
        ':ct' => $city
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'ધન્યવાદ! Thank you for your valuable feedback. Your review has been submitted and published successfully.'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error recording review: ' . $e->getMessage()
    ]);
}
