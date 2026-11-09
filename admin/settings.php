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
        'company_name'         => sanitizeString($_POST['company_name'] ?? ''),
        'brand_name'           => sanitizeString($_POST['brand_name'] ?? 'VARSHA BRAND'),
        'tagline'              => sanitizeString($_POST['tagline'] ?? ''),
        'gstin'                => strtoupper(sanitizeString($_POST['gstin'] ?? '')),
        'contact_person'       => sanitizeString($_POST['contact_person'] ?? ''),
        'authorized_signatory' => sanitizeString($_POST['authorized_signatory'] ?? ''),
        'phone_1'              => sanitizeString($_POST['phone_1'] ?? ''),
        'phone_2'              => sanitizeString($_POST['phone_2'] ?? ''),
        'email'                => sanitizeString($_POST['email'] ?? ''),
        'whatsapp'             => preg_replace('/[^0-9]/', '', $_POST['whatsapp'] ?? ''),
        'address'              => sanitizeString($_POST['address'] ?? ''),
        'city'                 => sanitizeString($_POST['city'] ?? 'Bhavnagar'),
        'state'                => sanitizeString($_POST['state'] ?? 'Gujarat'),
        'pincode'              => sanitizeString($_POST['pincode'] ?? '364003'),
        'working_hours'        => sanitizeString($_POST['working_hours'] ?? ''),
        'bank_name'            => sanitizeString($_POST['bank_name'] ?? ''),
        'bank_account_no'      => sanitizeString($_POST['bank_account_no'] ?? ''),
        'bank_ifsc'            => strtoupper(sanitizeString($_POST['bank_ifsc'] ?? '')),
        'upi_id'               => sanitizeString($_POST['upi_id'] ?? ''),
        'razorpay_key_id'      => sanitizeString($_POST['razorpay_key_id'] ?? ''),
        'razorpay_key_secret'  => sanitizeString($_POST['razorpay_key_secret'] ?? ''),
        'experience_years'     => sanitizeString($_POST['experience_years'] ?? '25+'),
        'machines_built'       => sanitizeString($_POST['machines_built'] ?? '1800+'),
        'happy_clients'        => sanitizeString($_POST['happy_clients'] ?? '1200+'),
        'about_company'        => sanitizeString($_POST['about_company'] ?? ''),
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

        $successMessage = 'Company & Website settings updated successfully! All changes are now live across the entire website.';
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

                <h3 class="card-title" style="margin-bottom: 20px;">Company Legal &amp; Brand Identity</h3>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Company Legal Name (રજીસ્ટર્ડ કંપનીનું નામ)</label>
                        <input type="text" name="company_name" class="admin-form-control" value="<?= e($settings['company_name'] ?? 'Shree Varsha Engineering Works') ?>" required>
                    </div>
                    <div class="admin-form-group">
                        <label>Brand Name (બ્રાન્ડ નામ)</label>
                        <input type="text" name="brand_name" class="admin-form-control" value="<?= e($settings['brand_name'] ?? 'VARSHA BRAND') ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Official Tagline (કંપની ટેગલાઈન / સૂત્ર)</label>
                        <input type="text" name="tagline" class="admin-form-control" value="<?= e($settings['tagline'] ?? 'Precision Drill Machine Manufacturers & Industrial Specialists') ?>">
                    </div>
                    <div class="admin-form-group">
                        <label>Official GSTIN Number (જીએસટી નંબર)</label>
                        <input type="text" name="gstin" class="admin-form-control" value="<?= e($settings['gstin'] ?? '24AJHPP0087E1ZD') ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Contact Person (સંપર્ક વ્યક્તિ)</label>
                        <input type="text" name="contact_person" class="admin-form-control" value="<?= e($settings['contact_person'] ?? 'Jitendrabhai Parmar') ?>" required>
                    </div>
                    <div class="admin-form-group">
                        <label>Authorized Signatory (સહી કરનાર સત્તાધિકારી)</label>
                        <input type="text" name="authorized_signatory" class="admin-form-control" value="<?= e($settings['authorized_signatory'] ?? 'Jay Parmar') ?>" required>
                    </div>
                </div>

                <h3 class="card-title" style="margin: 30px 0 20px; padding-top: 20px; border-top: 1px solid var(--admin-border);">
                    Direct Phone Numbers &amp; Digital Contact Details
                </h3>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Primary Phone Number (મુખ્ય મોબાઈલ)</label>
                        <input type="text" name="phone_1" class="admin-form-control" value="<?= e($settings['phone_1'] ?? '+91 9824913555') ?>" required>
                    </div>
                    <div class="admin-form-group">
                        <label>Secondary Phone Number (બીજો ફોન નંબર)</label>
                        <input type="text" name="phone_2" class="admin-form-control" value="<?= e($settings['phone_2'] ?? '+91 9714611331') ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Official Contact Email (ઇમેઇલ)</label>
                        <input type="email" name="email" class="admin-form-control" value="<?= e($settings['email'] ?? 'jitubhaivarsh@gmail.com') ?>" required>
                    </div>
                    <div class="admin-form-group">
                        <label>WhatsApp Number (વોટ્સએપ નંબર - 10/12 અંક)</label>
                        <input type="text" name="whatsapp" class="admin-form-control" value="<?= e($settings['whatsapp'] ?? '919824913555') ?>" required>
                    </div>
                </div>

                <h3 class="card-title" style="margin: 30px 0 20px; padding-top: 20px; border-top: 1px solid var(--admin-border);">
                    Factory Location &amp; Working Hours
                </h3>

                <div class="admin-form-group">
                    <label>Factory &amp; Works Full Address (ફેક્ટરી સરનામું)</label>
                    <textarea name="address" class="admin-form-control" rows="2" required><?= e($settings['address'] ?? 'Shed No. L/786, GIDC, Nr. Hadanagar, Bhavnagar - 364003, Gujarat, India') ?></textarea>
                </div>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>City (શહેર)</label>
                        <input type="text" name="city" class="admin-form-control" value="<?= e($settings['city'] ?? 'Bhavnagar') ?>" required>
                    </div>
                    <div class="admin-form-group">
                        <label>State (રાજ્ય)</label>
                        <input type="text" name="state" class="admin-form-control" value="<?= e($settings['state'] ?? 'Gujarat') ?>" required>
                    </div>
                    <div class="admin-form-group">
                        <label>Pincode (પિનકોડ)</label>
                        <input type="text" name="pincode" class="admin-form-control" value="<?= e($settings['pincode'] ?? '364003') ?>" required>
                    </div>
                </div>

                <div class="admin-form-group">
                    <label>Factory Working Hours (કામના કલાકો)</label>
                    <input type="text" name="working_hours" class="admin-form-control" value="<?= e($settings['working_hours'] ?? 'Monday - Saturday: 8:30 AM to 7:30 PM (Sunday Closed)') ?>">
                </div>

                <h3 class="card-title" style="margin: 30px 0 20px; padding-top: 20px; border-top: 1px solid var(--admin-border);">
                    Bank Account &amp; UPI Payment Details (Official Invoices &amp; Checkout)
                </h3>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Bank Name &amp; Branch (બેંકનું નામ અને બ્રાન્ચ)</label>
                        <input type="text" name="bank_name" class="admin-form-control" value="<?= e($settings['bank_name'] ?? 'State Bank of India (Industrial Finance Branch)') ?>">
                    </div>
                    <div class="admin-form-group">
                        <label>Bank Account Number (ખાતા નંબર)</label>
                        <input type="text" name="bank_account_no" class="admin-form-control" value="<?= e($settings['bank_account_no'] ?? '384910284729') ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Bank IFSC Code (આઈએફએસસી કોડ)</label>
                        <input type="text" name="bank_ifsc" class="admin-form-control" value="<?= e($settings['bank_ifsc'] ?? 'SBIN0001234') ?>">
                    </div>
                    <div class="admin-form-group">
                        <label>UPI VPA / QR ID (યુપીઆઈ આઈડી)</label>
                        <input type="text" name="upi_id" class="admin-form-control" value="<?= e($settings['upi_id'] ?? '9824913555@okbizaxis') ?>">
                    </div>
                </div>

                <h3 class="card-title" style="margin: 30px 0 20px; padding-top: 20px; border-top: 1px solid var(--admin-border);">
                    Razorpay Online Gateway Credentials (Live / Test API Keys)
                </h3>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Razorpay Key ID</label>
                        <input type="text" name="razorpay_key_id" class="admin-form-control" value="<?= e($settings['razorpay_key_id'] ?? 'rzp_test_SLvrGI5oOB3cDO') ?>">
                    </div>
                    <div class="admin-form-group">
                        <label>Razorpay Key Secret</label>
                        <input type="password" name="razorpay_key_secret" class="admin-form-control" value="<?= e($settings['razorpay_key_secret'] ?? 'fs37YWN1P1N8Gt5HkC8PVSUB') ?>">
                    </div>
                </div>

                <h3 class="card-title" style="margin: 30px 0 20px; padding-top: 20px; border-top: 1px solid var(--admin-border);">
                    Homepage Statistics &amp; Manufacturing Legacy
                </h3>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Years of Experience (અનુભવનાં વર્ષો)</label>
                        <input type="text" name="experience_years" class="admin-form-control" value="<?= e($settings['experience_years'] ?? '25+') ?>">
                    </div>
                    <div class="admin-form-group">
                        <label>Machines Built Count (બનેલા મશીનો)</label>
                        <input type="text" name="machines_built" class="admin-form-control" value="<?= e($settings['machines_built'] ?? '1800+') ?>">
                    </div>
                    <div class="admin-form-group">
                        <label>Happy Clients Count (ગ્રાહકોની સંખ્યા)</label>
                        <input type="text" name="happy_clients" class="admin-form-control" value="<?= e($settings['happy_clients'] ?? '1200+') ?>">
                    </div>
                </div>

                <div class="admin-form-group">
                    <label>About Company Summary (કંપની પરિચય વિગત)</label>
                    <textarea name="about_company" class="admin-form-control" rows="4"><?= e($settings['about_company'] ?? '') ?></textarea>
                </div>
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 12px 28px;">
                    ⚡ Save Company Settings
                </button>
            </form>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
