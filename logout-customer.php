<?php
/**
 * Customer Sign Out Handler
 * Shree Varsha Engineering Works - Varsha Brand
 */

require_once __DIR__ . '/config/config.php';

unset($_SESSION['customer_user']);
$_SESSION['flash_message'] = [
    'type' => 'success',
    'text' => 'You have been signed out successfully.'
];

$returnUrl = $_GET['return'] ?? (BASE_URL . '/index.php');
header("Location: " . $returnUrl);
exit;
