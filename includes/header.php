<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

// Default page title and meta
$pageTitle = $pageTitle ?? APP_NAME . ' | Industrial Drill Machine Manufacturers';
$pageDesc = $pageDesc ?? 'Shree Varsha Engineering Works is a leading manufacturer of Radial Drill Machines, Pillar Drill Machines, and Heavy Duty Drilling Machinery in Bhavnagar, Gujarat.';
$currentNav = $currentNav ?? 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($pageDesc) ?>">
    <meta name="keywords" content="radial drill machine, pillar drill, bench drill, geared radial drill, Shree Varsha Engineering Works, drill machine Bhavnagar, Gujarat machine tools">
    <meta name="author" content="Shree Varsha Engineering Works">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?= e($pageTitle) ?>">
    <meta property="og:description" content="<?= e($pageDesc) ?>">
    <meta property="og:type" content="website">
    <meta property="og:image" content="<?= assetUrl('assets/images/varsha-logo.png') ?>">
    <meta property="og:url" content="<?= BASE_URL ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= assetUrl('assets/images/varsha-logo.png') ?>">

    <!-- PWA Progressive Web App Manifest & Mobile App Configuration -->
    <link rel="manifest" href="<?= BASE_URL ?>/manifest.json">
    <meta name="theme-color" content="#00e5ff">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Varsha Drill">
    <link rel="apple-touch-icon" href="<?= assetUrl('assets/images/varsha-logo.png') ?>">

    <!-- Google Fonts: Space Grotesk + Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Stylesheets with Cache Busting -->
    <link rel="stylesheet" href="<?= assetUrl('assets/css/style.css') ?>?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= assetUrl('assets/css/responsive.css') ?>?v=<?= time() ?>">
</head>
<body>
