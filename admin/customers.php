<?php
/**
 * Admin - Customer Management & Buyers Directory
 * Shree Varsha Engineering Works - Varsha Brand
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

if (empty($_SESSION['admin_user'])) {
    header("Location: index.php");
    exit;
}

$adminUser = $_SESSION['admin_user'];
$activeTab = 'customers';
$adminTitle = 'Customer Directory & Buyers';

$pdo = Database::getConnection();

// Search / Filter
$search = sanitizeString($_GET['q'] ?? '');

// Guarantee customers table exists
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `customers` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `full_name` VARCHAR(100) NOT NULL,
        `company_name` VARCHAR(150) DEFAULT NULL,
        `email` VARCHAR(100) DEFAULT NULL,
        `phone` VARCHAR(30) NOT NULL,
        `password_hash` VARCHAR(255) NOT NULL,
        `gstin` VARCHAR(30) DEFAULT NULL,
        `address` TEXT DEFAULT NULL,
        `city` VARCHAR(100) DEFAULT 'Bhavnagar',
        `pincode` VARCHAR(20) DEFAULT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
} catch (Exception $e) {
    // If SQLite or already exists
}

$customers = [];
try {
    $query = "
        SELECT c.*, 
               COUNT(o.id) as total_orders, 
               COALESCE(SUM(o.total_amount), 0) as total_spent,
               MAX(o.created_at) as last_order_date
        FROM customers c
        LEFT JOIN orders o ON c.phone = o.customer_phone OR (c.email IS NOT NULL AND c.email != '' AND c.email = o.customer_email)
    ";

    if (!empty($search)) {
        $query .= " WHERE c.full_name LIKE :search OR c.company_name LIKE :search OR c.phone LIKE :search OR c.email LIKE :search OR c.city LIKE :search OR c.gstin LIKE :search ";
    }

    $query .= " GROUP BY c.id ORDER BY c.id DESC ";

    $stmt = $pdo->prepare($query);
    if (!empty($search)) {
        $stmt->execute([':search' => "%{$search}%"]);
    } else {
        $stmt->execute();
    }
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Fallback directly to orders table if customers empty or error
}

// Fallback: If customers table is empty, extract buyers directly from orders table
if (empty($customers)) {
    try {
        $oStmt = $pdo->query("
            SELECT id, customer_name as full_name, company_name, customer_email as email, customer_phone as phone,
                   customer_gst as gstin, customer_address as address, customer_city as city, customer_pincode as pincode,
                   COUNT(id) as total_orders, SUM(total_amount) as total_spent, created_at, created_at as last_order_date
            FROM orders
            GROUP BY customer_phone
            ORDER BY id DESC
        ");
        $customers = $oStmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $oe) {
        $customers = [];
    }
}

$orderBuyers = $customers;

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<div class="admin-main">
    <header class="admin-topbar">
        <div style="display: flex; align-items: center; gap: 12px;">
            <button type="button" id="adminSidebarToggle" class="btn-sm btn-cyan" style="padding: 6px 10px; cursor: pointer;">☰</button>
            <div>
                <h1 class="topbar-title">Registered Customers &amp; Machinery Buyers</h1>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 3px;">
                    Directory of verified client workshops, contact persons, GST numbers, and order histories.
                </p>
            </div>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="<?= BASE_URL ?>/admin/orders.php" class="btn btn-outline" style="border-color: #38bdf8; color: #38bdf8;">
                📦 View All Orders
            </a>
            <a href="<?= BASE_URL ?>/checkout.php" target="_blank" class="btn btn-primary">
                + New Machine Booking
            </a>
        </div>
    </header>

    <main class="admin-body">
        <!-- Stats Summary Grid -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-info">
                    <h4>Total Registered Buyers</h4>
                    <div class="metric-num"><?= count($customers) ?></div>
                </div>
                <div class="metric-icon icon-cyan">
                    <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-info">
                    <h4>Active Order Clients</h4>
                    <div class="metric-num" style="color: #34d399;"><?= count($orderBuyers) ?></div>
                </div>
                <div class="metric-icon icon-emerald">
                    <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-info">
                    <h4>Major Hubs</h4>
                    <div class="metric-num" style="font-size: 19px; color: #f59e0b;">Bhavnagar, Ahmedabad, Surat</div>
                </div>
                <div class="metric-icon icon-amber">
                    <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                </div>
            </div>
        </div>

        <!-- Search Bar Card -->
        <div class="admin-card" style="padding: 16px 20px; margin-bottom: 24px;">
            <form method="GET" action="customers.php" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 260px;">
                    <input type="text" name="q" value="<?= e($search) ?>" placeholder="🔍 Search by customer name, company, mobile, GSTIN, city..." class="admin-form-control" style="padding: 9px 14px; font-size: 13.5px;">
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 9px 18px;">
                    Search Directory
                </button>
                <?php if (!empty($search)): ?>
                    <a href="customers.php" class="btn btn-outline" style="padding: 9px 14px;">Clear Filter</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Customers Table -->
        <div class="admin-card">
            <h2 style="font-family: var(--font-heading); font-size: 18px; color: #fff; margin-bottom: 16px;">
                All Registered Customer Profiles
            </h2>

            <div class="table-responsive">
                <table class="tech-table">
                    <thead>
                        <tr>
                            <th>Customer / Company</th>
                            <th>Mobile Phone &amp; Email</th>
                            <th>GSTIN &amp; Location</th>
                            <th>Address</th>
                            <th>Bookings / Spend</th>
                            <th>Registered Date</th>
                            <th>Direct Connect</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($customers)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 30px; color: var(--text-muted);">
                                    No registered customers matching your search query.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($customers as $c): ?>
                                <tr>
                                    <td>
                                        <div style="font-weight: 700; color: #fff; font-size: 14.5px;">
                                            <?= e($c['full_name']) ?>
                                        </div>
                                        <?php if (!empty($c['company_name'])): ?>
                                            <div style="font-size: 12.5px; color: var(--accent-cyan); font-weight: 600; margin-top: 2px;">
                                                🏢 <?= e($c['company_name']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="font-weight: 600; color: #f8fafc; font-size: 13.5px;">
                                            📞 <?= e($c['phone']) ?>
                                        </div>
                                        <?php if (!empty($c['email'])): ?>
                                            <div style="font-size: 12px; color: #94a3b8; margin-top: 2px;">
                                                ✉️ <?= e($c['email']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($c['gstin'])): ?>
                                            <span class="badge-tag" style="border-color: #38bdf8; color: #38bdf8;">
                                                GST: <?= e($c['gstin']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span style="font-size: 11px; color: #64748b;">No GST</span>
                                        <?php endif; ?>
                                        <div style="font-size: 12px; color: #cbd5e1; margin-top: 4px;">
                                            📍 <?= e($c['city'] ?? 'Gujarat') ?>
                                        </div>
                                    </td>
                                    <td style="max-width: 220px; font-size: 12px; color: #94a3b8; line-height: 1.4;">
                                        <?= e($c['address'] ?? 'Factory Site, GIDC Area') ?>
                                        <?php if (!empty($c['pincode'])): ?>
                                            - <?= e($c['pincode']) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="font-size: 12.5px; color: #34d399; font-weight: 700;">
                                            <?= intval($c['total_orders']) ?> Machine Orders
                                        </div>
                                        <?php if ($c['total_spent'] > 0): ?>
                                            <div style="font-size: 11.5px; color: var(--accent-cyan); font-weight: 600;">
                                                ₹ <?= number_format($c['total_spent'], 2) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td style="font-size: 12px; color: #94a3b8;">
                                        <?= date('d M Y', strtotime($c['created_at'])) ?>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 6px;">
                                            <a href="tel:<?= preg_replace('/[^0-9]/', '', $c['phone']) ?>" class="btn btn-outline" style="padding: 4px 8px; font-size: 11.5px; border-color: #38bdf8; color: #38bdf8;" title="Call">
                                                📞 Call
                                            </a>
                                            <a href="https://wa.me/91<?= preg_replace('/[^0-9]/', '', $c['phone']) ?>?text=<?= urlencode('Hello ' . $c['full_name'] . ', regarding your drill machine inquiries at Shree Varsha Engineering Works Bhavnagar.') ?>" target="_blank" class="btn btn-outline" style="padding: 4px 8px; font-size: 11.5px; border-color: #25d366; color: #25d366;" title="WhatsApp">
                                                💬 Chat
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
