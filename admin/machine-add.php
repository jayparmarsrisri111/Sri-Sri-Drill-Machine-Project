<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

if (empty($_SESSION['admin_user'])) {
    header("Location: index.php");
    exit;
}

$adminTitle = 'Add New Machine / Part';
$activeTab = 'machine-add';
$errorMessage = '';
$pdo = Database::getConnection();

// Fetch categories safely for dropdown
$categories = [];
try {
    $categories = $pdo->query("SELECT * FROM categories ORDER BY display_order ASC")->fetchAll();
} catch (Exception $e) {
    //
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $modelNumber = trim($_POST['model_number'] ?? '');
    $categoryId = intval($_POST['category_id'] ?? 1);
    $productType = trim($_POST['product_type'] ?? 'machine');
    $price = floatval($_POST['price'] ?? 0);
    $stockQuantity = max(0, intval($_POST['stock_quantity'] ?? 5));
    $drillingCapacity = trim($_POST['drilling_capacity'] ?? '');
    $spindleSpeeds = trim($_POST['spindle_speeds'] ?? '');
    $spindleTravel = trim($_POST['spindle_travel'] ?? '');
    $columnDiameter = trim($_POST['column_diameter'] ?? '');
    $motorPower = trim($_POST['motor_power'] ?? '');
    $tableSize = trim($_POST['working_table_size'] ?? '');
    $drillingRadius = trim($_POST['drilling_radius'] ?? '');
    $weightApprox = trim($_POST['weight_approx'] ?? '');
    $shortDesc = trim($_POST['short_desc'] ?? '');
    $fullDesc = trim($_POST['full_desc'] ?? '');
    $keyFeatures = trim($_POST['key_features'] ?? '');
    $imageUrl = trim($_POST['image_url'] ?? 'assets/images/machinery/radial-drill-real.jpg');
    $isFeatured = !empty($_POST['is_featured']) ? 1 : 0;
    $isActive = !empty($_POST['is_active']) ? 1 : 0;

    // Handle Image File Upload
    if (!empty($_FILES['image_file']['name']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
        $fileInfo = pathinfo($_FILES['image_file']['name']);
        $ext = strtolower($fileInfo['extension'] ?? '');

        if (in_array($ext, $allowedExtensions)) {
            $uploadDir = __DIR__ . '/../assets/images/machinery/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $safeFileName = 'product_' . preg_replace('/[^a-z0-9_-]/i', '_', strtolower($modelNumber)) . '_' . time() . '.' . $ext;
            $destPath = $uploadDir . $safeFileName;

            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $destPath)) {
                $imageUrl = 'assets/images/machinery/' . $safeFileName;
            } else {
                $errorMessage = 'Failed to move uploaded image to storage directory.';
            }
        } else {
            $errorMessage = 'Invalid image format. Allowed formats: JPG, PNG, WEBP, SVG.';
        }
    }

    // Generate slug
    $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $name . '-' . $modelNumber));

    if (empty($errorMessage)) {
        if (empty($name) || empty($modelNumber) || empty($drillingCapacity)) {
            $errorMessage = 'Please fill out Product Name, Model Number, and Capacity/Specification.';
        } else {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO products 
                    (category_id, name, model_number, slug, drilling_capacity, spindle_speeds, spindle_travel, column_diameter, motor_power, working_table_size, drilling_radius, weight_approx, short_desc, full_desc, key_features, image_url, price, stock_quantity, product_type, is_featured, is_active, created_at)
                    VALUES 
                    (:category_id, :name, :model_number, :slug, :drilling_capacity, :spindle_speeds, :spindle_travel, :column_diameter, :motor_power, :working_table_size, :drilling_radius, :weight_approx, :short_desc, :full_desc, :key_features, :image_url, :price, :stock_quantity, :product_type, :is_featured, :is_active, CURRENT_TIMESTAMP)
                ");

                $stmt->execute([
                    ':category_id' => $categoryId,
                    ':name' => $name,
                    ':model_number' => $modelNumber,
                    ':slug' => $slug,
                    ':drilling_capacity' => $drillingCapacity,
                    ':spindle_speeds' => $spindleSpeeds,
                    ':spindle_travel' => $spindleTravel,
                    ':column_diameter' => $columnDiameter,
                    ':motor_power' => $motorPower,
                    ':working_table_size' => $tableSize,
                    ':drilling_radius' => $drillingRadius,
                    ':weight_approx' => $weightApprox,
                    ':short_desc' => $shortDesc,
                    ':full_desc' => $fullDesc,
                    ':key_features' => $keyFeatures,
                    ':image_url' => $imageUrl,
                    ':price' => $price,
                    ':stock_quantity' => $stockQuantity,
                    ':product_type' => $productType,
                    ':is_featured' => $isFeatured,
                    ':is_active' => $isActive
                ]);

                $_SESSION['flash_message'] = [
                    'type' => 'success',
                    'text' => "Product `{$name}` added successfully with live stock and image!"
                ];
                header("Location: machines.php");
                exit;
            } catch (Exception $e) {
                $errorMessage = "Error adding product: " . $e->getMessage();
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<div class="admin-main">
    <header class="admin-topbar">
        <div style="display: flex; align-items: center; gap: 12px;">
            <button type="button" id="adminSidebarToggle" class="btn-sm btn-cyan" style="padding: 6px 10px; cursor: pointer;">☰</button>
            <h1 class="topbar-title">Add New Machine or Genuine Spare Part</h1>
        </div>
        <div class="topbar-actions">
            <a href="machines.php" class="btn-sm btn-cyan">
                ← Back to All Machinery
            </a>
        </div>
    </header>

    <main class="admin-body">
        <?php if (!empty($errorMessage)): ?>
            <div class="admin-alert alert-danger">
                <?= e($errorMessage) ?>
            </div>
        <?php endif; ?>

        <div class="content-card">
            <form method="POST" enctype="multipart/form-data">
                <h3 class="card-title" style="margin-bottom: 20px;">Product Identity, Type & Category</h3>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Item Classification *</label>
                        <select name="product_type" id="productTypeSelect" class="admin-form-control" required>
                            <option value="machine">Industrial Drill Machine (મશીનરી)</option>
                            <option value="part">Genuine Spare Part & Tooling (સ્પેરપાર્ટ / એક્સેસરી)</option>
                        </select>
                    </div>
                    <div class="admin-form-group">
                        <label>Category *</label>
                        <select name="category_id" class="admin-form-control" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>">
                                    [<?= strtoupper($cat['category_type'] ?? 'machine') ?>] <?= e($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Product / Model Name *</label>
                        <input type="text" name="name" class="admin-form-control" placeholder="e.g. 50mm Heavy Duty All-Geared Radial Drill" required>
                    </div>
                    <div class="admin-form-group">
                        <label>Model / Part Number *</label>
                        <input type="text" name="model_number" class="admin-form-control" placeholder="e.g. SVEW-RD-50 or SVEW-PRT-SP4" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Machine / Part Price (₹ INR) *</label>
                        <input type="number" step="100" name="price" class="admin-form-control" placeholder="e.g. 285000" value="285000" required>
                    </div>
                    <div class="admin-form-group">
                        <label>Available Factory Stock (Quantity) *</label>
                        <input type="number" name="stock_quantity" class="admin-form-control" min="0" max="999" value="5" required>
                    </div>
                </div>

                <!-- Product Image Upload Section -->
                <h3 class="card-title" style="margin: 30px 0 20px; padding-top: 20px; border-top: 1px solid var(--admin-border);">
                    📸 Real Machine / Part Image (ફોટો અપલોડ અને સિલેક્શન)
                </h3>

                <div style="background: rgba(11, 17, 32, 0.7); border: 1.5px dashed var(--accent-cyan); border-radius: 12px; padding: 22px; margin-bottom: 25px;">
                    <div style="display: grid; grid-template-columns: 140px 1fr; gap: 24px; align-items: center;">
                        <div style="text-align: center;">
                            <img id="imageLivePreview" src="<?= assetUrl('assets/images/machinery/radial-drill-real.jpg') ?>" alt="Preview" style="width: 120px; height: 120px; object-fit: contain; background: #060911; border: 2px solid var(--accent-cyan); border-radius: 10px; padding: 6px; box-shadow: 0 0 20px rgba(0,229,255,0.2);">
                            <div style="font-size: 11px; color: #94a3b8; margin-top: 6px;">Live Preview</div>
                        </div>

                        <div>
                            <div class="admin-form-group" style="margin-bottom: 14px;">
                                <label style="color: #34d399; font-weight: 700;">Option 1: Upload Real Photo from Computer / Phone</label>
                                <input type="file" name="image_file" id="imageFileInput" accept="image/*" class="admin-form-control" style="padding: 8px;">
                                <small style="color: #94a3b8; font-size: 11.5px; display: block; margin-top: 4px;">Supports JPG, PNG, WEBP, SVG. Auto-resizes for ultra-fast catalog display.</small>
                            </div>

                            <div class="admin-form-group" style="margin-bottom: 10px;">
                                <label style="color: var(--accent-cyan);">Option 2: Select From Existing Factory Photos / Path</label>
                                <input type="text" name="image_url" id="imageUrlInput" class="admin-form-control" value="assets/images/machinery/radial-drill-real.jpg">
                            </div>

                            <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-top: 10px;">
                                <button type="button" class="btn-sm btn-cyan pick-img-btn" data-src="assets/images/machinery/radial-drill-real.jpg">40mm Radial Photo</button>
                                <button type="button" class="btn-sm btn-cyan pick-img-btn" data-src="assets/images/machinery/heavy_duty_radial_65_real.jpg">65mm Radial Photo</button>
                                <button type="button" class="btn-sm btn-cyan pick-img-btn" data-src="assets/images/machinery/pillar_drill_real.jpg">Pillar Drill Photo</button>
                                <button type="button" class="btn-sm btn-cyan pick-img-btn" data-src="assets/images/machinery/bench_drill_real.jpg">Bench Drill Photo</button>
                                <button type="button" class="btn-sm btn-cyan pick-img-btn" data-src="assets/images/machinery/tapping_drill_real.jpg">Tapping Machine Photo</button>
                                <button type="button" class="btn-sm btn-cyan pick-img-btn" data-src="assets/images/varsha-logo.png">Varsha Emblem / Parts</button>
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="card-title" style="margin: 30px 0 20px; padding-top: 20px; border-top: 1px solid var(--admin-border);">
                    Technical Specifications
                </h3>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Drilling Capacity / Compatibility *</label>
                        <input type="text" name="drilling_capacity" class="admin-form-control" placeholder="e.g. 50 mm (Steel) / 60 mm (Cast Iron)" required>
                    </div>
                    <div class="admin-form-group">
                        <label>Spindle Speeds & RPM</label>
                        <input type="text" name="spindle_speeds" class="admin-form-control" placeholder="e.g. 12 Speeds (35 - 1600 RPM)">
                    </div>
                </div>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Spindle Travel</label>
                        <input type="text" name="spindle_travel" class="admin-form-control" placeholder="e.g. 280 mm">
                    </div>
                    <div class="admin-form-group">
                        <label>Column Diameter</label>
                        <input type="text" name="column_diameter" class="admin-form-control" placeholder="e.g. 260 mm">
                    </div>
                </div>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Electric Motor Power</label>
                        <input type="text" name="motor_power" class="admin-form-control" placeholder="e.g. 3.0 HP Main + 1.5 HP Elevating">
                    </div>
                    <div class="admin-form-group">
                        <label>Working Table Size</label>
                        <input type="text" name="working_table_size" class="admin-form-control" placeholder="e.g. 600 x 750 mm Box Table">
                    </div>
                </div>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Drilling Radius (Max/Min)</label>
                        <input type="text" name="drilling_radius" class="admin-form-control" placeholder="e.g. 1350 mm Max / 400 mm Min">
                    </div>
                    <div class="admin-form-group">
                        <label>Approximate Weight</label>
                        <input type="text" name="weight_approx" class="admin-form-control" placeholder="e.g. 2400 kg">
                    </div>
                </div>

                <h3 class="card-title" style="margin: 30px 0 20px; padding-top: 20px; border-top: 1px solid var(--admin-border);">
                    Descriptions & Highlights
                </h3>

                <div class="admin-form-group">
                    <label>Short Summary (Shown on catalog card)</label>
                    <textarea name="short_desc" class="admin-form-control" rows="2" placeholder="Brief summary of the machine/part capabilities..."></textarea>
                </div>

                <div class="admin-form-group">
                    <label>Full Detailed Technical Description</label>
                    <textarea name="full_desc" class="admin-form-control" rows="4" placeholder="Detailed engineering description, casting grade, gear steel, lubrication system..."></textarea>
                </div>

                <div class="admin-form-group">
                    <label>Key Features & Highlights (Separate with semicolons `;`)</label>
                    <textarea name="key_features" class="admin-form-control" rows="3" placeholder="Hardened & ground alloy steel gears;Power arm elevating system;Automatic mechanical spindle feeds;Heavy rib box table with T-slots"></textarea>
                </div>

                <div style="display: flex; gap: 30px; margin: 24px 0; flex-wrap: wrap;">
                    <label style="display: flex; align-items: center; gap: 8px; color: #fff; cursor: pointer;">
                        <input type="checkbox" name="is_featured" value="1" style="accent-color: var(--accent-cyan);">
                        <span>Mark as Featured Product (Shows on top of homepage)</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 8px; color: #fff; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" checked style="accent-color: var(--accent-cyan);">
                        <span>Publish Active on Catalog & Store</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 12px 28px; font-size: 15px; font-weight: 700;">
                    ⚡ Save & Publish Machine / Part
                </button>
            </form>
        </div>
    </main>
</div>

<script>
    // Live image preview logic
    const imgInput = document.getElementById('imageUrlInput');
    const fileInput = document.getElementById('imageFileInput');
    const preview = document.getElementById('imageLivePreview');

    if (imgInput && preview) {
        imgInput.addEventListener('input', function() {
            if (this.value.trim()) {
                preview.src = "<?= BASE_URL ?>/" + this.value.trim().replace(/^\//, '');
            }
        });
    }

    if (fileInput && preview) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    // Quick pick preset buttons
    document.querySelectorAll('.pick-img-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const src = this.getAttribute('data-src');
            imgInput.value = src;
            preview.src = "<?= BASE_URL ?>/" + src;
        });
    });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>