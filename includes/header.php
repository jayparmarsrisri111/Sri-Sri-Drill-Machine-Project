<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

// Canonical base domain
$canonicalBase = 'https://varsha-drill-machines.onrender.com';

// Dynamic Canonical URL calculation
if (empty($canonicalUrl)) {
    $scriptName = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
    if ($scriptName === 'index.php' || $scriptName === '') {
        $canonicalUrl = $canonicalBase . '/';
    } else {
        $cleanParams = [];
        if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
            $cleanParams['id'] = intval($_GET['id']);
        }
        if (!empty($_GET['category']) && $_GET['category'] !== 'all') {
            $cleanParams['category'] = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['category']);
        }
        $queryString = !empty($cleanParams) ? '?' . http_build_query($cleanParams) : '';
        $canonicalUrl = $canonicalBase . '/' . $scriptName . $queryString;
    }
}

// Default page title and meta
$pageTitle = $pageTitle ?? 'Radial Drill Machine Manufacturer in Bhavnagar, Gujarat | ' . APP_NAME;
$pageDesc = $pageDesc ?? 'Shree Varsha Engineering Works (Varsha Brand) is a leading manufacturer of Heavy-Duty Radial Drill Machines (40mm, 50mm, 65mm), Pillar Drills, and Bench Drills in Bhavnagar, Gujarat. Call +91 9824913555.';
$pageKeywords = $pageKeywords ?? 'radial drill machine, heavy duty radial drill, 40mm radial drill machine, 50mm radial drill, 65mm geared radial drill machine, pillar drill machine, bench drill, drill machine manufacturer Bhavnagar, Gujarat machine tools, Shree Varsha Engineering Works, Varsha Brand drill machine, industrial drilling machinery India, drill machine price';
$pageRobots = $pageRobots ?? 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1';
$ogType = $ogType ?? 'website';
$ogImage = $ogImage ?? $canonicalBase . '/assets/images/machinery/radial-drill-40-real.jpg';
if (!preg_match('/^https?:\/\//', $ogImage)) {
    $ogImage = $canonicalBase . '/' . ltrim($ogImage, '/');
}
$currentNav = $currentNav ?? 'home';

// Breadcrumb structured list
$breadcrumbItems = [
    [
        '@type' => 'ListItem',
        'position' => 1,
        'name' => 'Home',
        'item' => $canonicalBase . '/'
    ]
];

$pageScript = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
if ($pageScript === 'machines.php') {
    $breadcrumbItems[] = [
        '@type' => 'ListItem',
        'position' => 2,
        'name' => 'Drill Machines',
        'item' => $canonicalBase . '/machines.php'
    ];
    if (!empty($_GET['category']) && $_GET['category'] !== 'all') {
        $catName = ucwords(str_replace('-', ' ', $_GET['category']));
        $breadcrumbItems[] = [
            '@type' => 'ListItem',
            'position' => 3,
            'name' => $catName,
            'item' => $canonicalUrl
        ];
    }
} elseif ($pageScript === 'machine-detail.php') {
    $breadcrumbItems[] = [
        '@type' => 'ListItem',
        'position' => 2,
        'name' => 'Drill Machines',
        'item' => $canonicalBase . '/machines.php'
    ];
    if (!empty($machine['name'])) {
        $breadcrumbItems[] = [
            '@type' => 'ListItem',
            'position' => 3,
            'name' => $machine['name'],
            'item' => $canonicalUrl
        ];
    }
} elseif ($pageScript === 'catalog.php') {
    $breadcrumbItems[] = [
        '@type' => 'ListItem',
        'position' => 2,
        'name' => 'Technical Catalog & Datasheets',
        'item' => $canonicalBase . '/catalog.php'
    ];
} elseif ($pageScript === 'about.php') {
    $breadcrumbItems[] = [
        '@type' => 'ListItem',
        'position' => 2,
        'name' => 'About Us',
        'item' => $canonicalBase . '/about.php'
    ];
} elseif ($pageScript === 'contact.php') {
    $breadcrumbItems[] = [
        '@type' => 'ListItem',
        'position' => 2,
        'name' => 'Contact Us',
        'item' => $canonicalBase . '/contact.php'
    ];
} elseif ($pageScript === 'blog.php') {
    $breadcrumbItems[] = [
        '@type' => 'ListItem',
        'position' => 2,
        'name' => 'Engineering Blog',
        'item' => $canonicalBase . '/blog.php'
    ];
} elseif ($pageScript === 'faqs.php') {
    $breadcrumbItems[] = [
        '@type' => 'ListItem',
        'position' => 2,
        'name' => 'FAQs',
        'item' => $canonicalBase . '/faqs.php'
    ];
} elseif ($pageScript === 'simulator-6d.php') {
    $breadcrumbItems[] = [
        '@type' => 'ListItem',
        'position' => 2,
        'name' => '360° Interactive 12D View',
        'item' => $canonicalBase . '/simulator-6d.php'
    ];
} elseif ($pageScript === 'track-order.php') {
    $breadcrumbItems[] = [
        '@type' => 'ListItem',
        'position' => 2,
        'name' => 'Live Order Tracking',
        'item' => $canonicalBase . '/track-order.php'
    ];
} elseif ($pageScript === 'checkout.php') {
    $breadcrumbItems[] = [
        '@type' => 'ListItem',
        'position' => 2,
        'name' => 'Machine Booking',
        'item' => $canonicalBase . '/checkout.php'
    ];
}

// Comprehensive Global Schema.org graph
$globalSchema = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => ['LocalBusiness', 'Manufacturer'],
            '@id' => $canonicalBase . '/#organization',
            'name' => 'Shree Varsha Engineering Works',
            'alternateName' => ['Varsha Brand Drill Machines', 'Shree Varsha Engineering Works Bhavnagar'],
            'url' => $canonicalBase . '/',
            'logo' => [
                '@type' => 'ImageObject',
                '@id' => $canonicalBase . '/#logo',
                'url' => $canonicalBase . '/assets/images/varsha-logo.png',
                'caption' => 'Shree Varsha Engineering Works Logo'
            ],
            'image' => $canonicalBase . '/assets/images/machinery/radial-drill-40-real.jpg',
            'description' => 'Leading manufacturer of heavy-duty Radial Drill Machines (40mm, 50mm, 65mm), Pillar Drill Machines, and High Speed Bench Drills in Bhavnagar, Gujarat, India.',
            'telephone' => '+91-9824913555',
            'email' => 'jitubhaivarsh@gmail.com',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Shed No. L/786, GIDC, Nr. Hadanagar',
                'addressLocality' => 'Bhavnagar',
                'addressRegion' => 'Gujarat',
                'postalCode' => '364003',
                'addressCountry' => 'IN'
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => 21.7645,
                'longitude' => 72.1519
            ],
            'openingHoursSpecification' => [
                [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                    'opens' => '08:30',
                    'closes' => '20:00'
                ]
            ],
            'priceRange' => '₹48,000 - ₹5,75,000',
            'currenciesAccepted' => 'INR',
            'paymentAccepted' => 'Cash, Credit Card, Debit Card, Net Banking, UPI, NEFT, RTGS, Razorpay',
            'founder' => [
                '@type' => 'Person',
                'name' => 'Jitendrabhai Parmar'
            ],
            'foundingDate' => '1998',
            'vatID' => '24AJHPP0087E1ZD',
            'taxID' => '24AJHPP0087E1ZD',
            'areaServed' => [
                [
                    '@type' => 'Country',
                    'name' => 'India'
                ]
            ],
            'knowsAbout' => [
                'Radial Drill Machines',
                'Pillar Drill Machines',
                'Bench Drilling Machines',
                'Auto-Reversing Tapping Machinery',
                'Industrial Machine Tool Manufacturing'
            ]
        ],
        [
            '@type' => 'WebSite',
            '@id' => $canonicalBase . '/#website',
            'url' => $canonicalBase . '/',
            'name' => 'Shree Varsha Engineering Works',
            'description' => 'Precision Industrial Drill Machine Manufacturers in Bhavnagar, Gujarat',
            'publisher' => [
                '@id' => $canonicalBase . '/#organization'
            ],
            'inLanguage' => 'en-US',
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => $canonicalBase . '/machines.php?category={search_term_string}'
                ],
                'query-input' => 'required name=search_term_string'
            ]
        ],
        [
            '@type' => 'BreadcrumbList',
            '@id' => $canonicalUrl . '#breadcrumb',
            'itemListElement' => $breadcrumbItems
        ]
    ]
];

// Page-specific schema injection
if (!empty($pageSchema)) {
    if (isset($pageSchema['@graph'])) {
        foreach ($pageSchema['@graph'] as $item) {
            $globalSchema['@graph'][] = $item;
        }
    } else {
        $temp = $pageSchema;
        unset($temp['@context']);
        $globalSchema['@graph'][] = $temp;
    }
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($_COOKIE['site_lang'] ?? 'en') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($pageDesc) ?>">
    <meta name="keywords" content="<?= e($pageKeywords) ?>">
    <meta name="author" content="Shree Varsha Engineering Works">
    <meta name="copyright" content="Shree Varsha Engineering Works">
    
    <!-- Search Engine Directives & Canonical -->
    <meta name="robots" content="<?= e($pageRobots) ?>">
    <meta name="googlebot" content="<?= e($pageRobots) ?>">
    <meta name="bingbot" content="<?= e($pageRobots) ?>">
    <link rel="canonical" href="<?= e($canonicalUrl) ?>">

    <!-- Local SEO Geo-Tags (Bhavnagar, Gujarat, India) -->
    <meta name="geo.region" content="IN-GJ">
    <meta name="geo.placename" content="Bhavnagar, Gujarat, India">
    <meta name="geo.position" content="21.7645;72.1519">
    <meta name="ICBM" content="21.7645, 72.1519">
    <meta name="format-detection" content="telephone=yes">
    <meta name="rating" content="General">

    <!-- Open Graph Meta Tags (Facebook, WhatsApp, LinkedIn) -->
    <meta property="og:site_name" content="Shree Varsha Engineering Works (Varsha Brand)">
    <meta property="og:title" content="<?= e($pageTitle) ?>">
    <meta property="og:description" content="<?= e($pageDesc) ?>">
    <meta property="og:type" content="<?= e($ogType) ?>">
    <meta property="og:url" content="<?= e($canonicalUrl) ?>">
    <meta property="og:image" content="<?= e($ogImage) ?>">
    <meta property="og:image:secure_url" content="<?= e($ogImage) ?>">
    <meta property="og:image:alt" content="<?= e($pageTitle) ?>">
    <meta property="og:locale" content="en_IN">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($pageTitle) ?>">
    <meta name="twitter:description" content="<?= e($pageDesc) ?>">
    <meta name="twitter:image" content="<?= e($ogImage) ?>">
    <meta name="twitter:image:alt" content="<?= e($pageTitle) ?>">

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

    <!-- DNS Prefetch & Preconnect for Faster Core Web Vitals (LCP/FID) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Stylesheets with Cache Busting -->
    <link rel="stylesheet" href="<?= assetUrl('assets/css/style.css') ?>?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= assetUrl('assets/css/responsive.css') ?>?v=<?= time() ?>">

    <!-- Schema.org JSON-LD Structured Data for Google Rich Snippets -->
    <script type="application/ld+json">
<?= json_encode($globalSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
    </script>
</head>
<body>
