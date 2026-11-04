<?php
$adminTitle = 'Company & Site Settings';
$activeTab = 'settings';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$pdo = Database::getConnection();
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settingsToUpdate = [
        'company_name'     => $_POST['company_name'] ?? '',
        'tagline'          => $_POST['tagline'] ?? '',
        'phone_1'          => $_POST['phone_1'] ?? '',
        'phone_2'          => $_POST['phone_2'] ?? '',
        'email'            => $_POST['email'] ?? '',
        'whatsapp'         => $_POST['whatsapp'] ?? '',
        'address'          => $_POST['address'] ?? '',
        'working_hours'    => $_POST['working_hours'] ?? '',
        'experience_years' => $_POST['experience_years'] ?? '',
        'machines_built'   => $_POST['machines_built'] ?? '',
        'happy_clients'    => $_POST['happy_clients'] ?? '',
        'about_company'    => $_POST['about_company'] ?? '',
    ];

    try {
        $driver = Database::getDriverType();
        if ($driver === 'sqlite') {
            $stmt = $pdo->prepare("
                INSERT INTO site_settings (setting_key, setting_value, updated_at)
                VALUES (:k, :v, CURRENT_TIMESTAMP)
                ON CONFLICT(setting_key) DO UPDATE SET setting_value = :v, updated_at = CURRENT_TIMESTAMP
            ");
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO site_settings (setting_key, setting_value, updated_at) 
                VALUES (:k, :v, CURRENT_TIMESTAMP)
                ON DUPLICATE KEY UPDATE setting_value = :v, updated_at = CURRENT_TIMESTAMP
            ");
        }

        foreach ($settingsToUpdate as $key => $val) {
            $stmt->execute([':k' => $key, ':v' => $val]);
        }

        $successMessage = 'Company settings and contact details updated successfully!';
    } catch (Exception $e) {
        $errorMessage = 'Failed to update settings: ' . $e->getMessage();
    }
}

// Fetch current settings
$settings = [];
$rows = $pdo->query("SELECT setting_key, setting_value FROM site_settings")->fetchAll();
foreach ($rows as $r) {
    $settings[$r['setting_key']] = $r['setting_value'];
}
?>

<div class="admin-main">
    <header class="admin-topbar">
        <div style="display: flex; align-items: center; gap: 12px;">
            <button type="button" id="adminSidebarToggle" class="btn-sm btn-cyan" style="padding: 6px 10px; cursor: pointer;">☰</button>
            <h1 class="topbar-title">Company Profile & Contact Settings</h1>
        </div>
        <div class="topbar-actions">
            <a href="<?= BASE_URL ?>/index.php" target="_blank" class="btn-sm btn-cyan">
                View Changes on Website →
            </a>
        </div>
    </header>

    <main class="admin-body">
        <?php if (!empty($successMessage)): ?>
            <div class="admin-alert alert-success">
                <?= e($successMessage) ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($errorMessage)): ?>
            <div class="admin-alert alert-danger">
                <?= e($errorMessage) ?>
            </div>
        <?php endif; ?>

        <div class="content-card">
            <form method="POST">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px; padding: 14px 18px; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--accent-cyan); border-radius: 10px;">
                    <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand Logo" style="height: 52px; width: 52px; border-radius: 50%; border: 1.5px solid var(--accent-cyan); object-fit: contain;">
                    <div>
                        <div style="font-weight: 800; color: #fff; font-size: 14px;">Active Brand Logo: VARSHA BRAND</div>
                        <div style="font-size: 11.5px; color: #94a3b8;">High-resolution metallic gear emblem active across entire web-app, bills, and catalog.</div>
                    </div>
                </div>

                <h3 class="card-title" style="margin-bottom: 20px;">Company Identity</h3>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Company Legal Name</label>
                        <input type="text" name="company_name" class="admin-form-control" value="<?= e($settings['company_name'] ?? 'Shree Varsha Engineering Works') ?>" required>
                    </div>
                    <div class="admin-form-group">
                        <label>Official Tagline</label>
                        <input type="text" name="tagline" class="admin-form-control" value="<?= e($settings['tagline'] ?? 'Precision Drill Machine Manufacturers & Industrial Specialists') ?>">
                    </div>
                </div>

                <h3 class="card-title" style="margin: 30px 0 20px; padding-top: 20px; border-top: 1px solid var(--admin-border);">
                    Direct Phone Numbers & Email (User Specified)
                </h3>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Primary Phone Number (Jitendrabhai Parmar)</label>
                        <input type="text" name="phone_1" class="admin-form-control" value="<?= e($settings['phone_1'] ?? '+91 9824913555') ?>" required>
                    </div>
                    <div class="admin-form-group">
                        <label>Secondary Phone Number</label>
                        <input type="text" name="phone_2" class="admin-form-control" value="<?= e($settings['phone_2'] ?? '+91 9714611331') ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Official Contact Email</label>
                        <input type="email" name="email" class="admin-form-control" value="<?= e($settings['email'] ?? 'jitubhaivarsh@gmail.com') ?>" required>
                    </div>
                    <div class="admin-form-group">
                        <label>WhatsApp Number (Without + or spaces)</label>
                        <input type="text" name="whatsapp" class="admin-form-control" value="<?= e($settings['whatsapp'] ?? '919824913555') ?>" required>
                    </div>
                </div>

                <h3 class="card-title" style="margin: 30px 0 20px; padding-top: 20px; border-top: 1px solid var(--admin-border);">
                    Location & Working Hours
                </h3>

                <div class="admin-form-group">
                    <label>Factory & Works Full Address</label>
                    <textarea name="address" class="admin-form-control" rows="2"><?= e($settings['address'] ?? 'Shed No. L/786, GIDC, Nr. Hadanagar, Bhavnagar - 36400003, Gujarat, India') ?></textarea>
                </div>

                <div class="admin-form-group">
                    <label>Working Hours</label>
                    <input type="text" name="working_hours" class="admin-form-control" value="<?= e($settings['working_hours'] ?? 'Monday - Saturday: 8:30 AM to 7:30 PM (Sunday Closed)') ?>">
                </div>

                <h3 class="card-title" style="margin: 30px 0 20px; padding-top: 20px; border-top: 1px solid var(--admin-border);">
                    Homepage Statistics & Story
                </h3>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Years of Experience</label>
                        <input type="text" name="experience_years" class="admin-form-control" value="<?= e($settings['experience_years'] ?? '25+') ?>">
                    </div>
                    <div class="admin-form-group">
                        <label>Machines Built Count</label>
                        <input type="text" name="machines_built" class="admin-form-control" value="<?= e($settings['machines_built'] ?? '1800+') ?>">
                    </div>
                </div>

                <div class="admin-form-group">
                    <label>About Company Summary</label>
                    <textarea name="about_company" class="admin-form-control" rows="4"><?= e($settings['about_company'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 12px 28px;">
                    ⚡ Save Company Settings
                </button>
            </form>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
