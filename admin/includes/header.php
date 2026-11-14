<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';

// Auth Guard
if (empty($_SESSION['admin_user'])) {
    header("Location: " . BASE_URL . "/admin/index.php");
    exit;
}

$adminUser = $_SESSION['admin_user'];
$activeTab = $activeTab ?? 'dashboard';
$currentLang = $_COOKIE['site_lang'] ?? 'en';
if (!in_array($currentLang, ['en', 'gu', 'hi'])) $currentLang = 'en';
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($currentLang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($adminTitle ?? 'Admin Dashboard') ?> | <?= APP_NAME ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= assetUrl('assets/images/varsha-logo.png') ?>">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <!-- Admin Stylesheet with Automatic Cache Busting -->
    <link rel="stylesheet" href="<?= assetUrl('assets/css/admin.css') ?>?v=<?= time() ?>">
</head>
<body>
