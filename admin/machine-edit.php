<?php
$adminTitle = 'Edit Machine / Part';
$activeTab = 'machines';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$pdo = Database::getConnection();
$id = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute([':id' => $id]);
$machine = $stmt->fetch();

if (!$machine) {
    header("Location: machines.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY category_type ASC, display_order ASC")->fetchAll();
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $modelNumber = trim($_POST['model_number'] ?? '');
    $categoryId = intval($_POST['category_id'] ?? 1);
    $productType = trim($_POST['product_type'] ?? ($machine['product_type'] ?? 'machine'));
    $price = floatval($_POST['price'] ?? ($machine['price'] ?? 0));
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
    $imageUrl = trim($_POST['image_url'] ?? $machine['image_url']);
    $isFeatured = !empty($_POST['is_featured']) ? 1 : 0;
    $isActive = !empty($_POST['is_active']) ? 1 : 0;

    // Handle Image File Upload if a new file is provided
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
                $errorMessage = 'Failed to move uploaded image.';
            }
        } else {
            $errorMessage = 'Invalid image format. Allowed formats: JPG, PNG, WEBP, SVG.';
        }
    }

    if (empty($errorMessage)) {
        if (empty($name) || empty($modelNumber) || empty($drillingCapacity)) {
            $errorMessage = 'Please fill out Product Name, Model Number, and Capacity/Specification.';
        } else {
            try {
                $updateStmt = $pdo->prepare("
                    UPDATE products SET
                        category_id = :category_id,
                        name = :name,
                        model_number = :model_number,
                        price = :price,
                        stock_quantity = :stock_quantity,
                        product_type = :product_type,
                        drilling_capacity = :drilling_capacity,
                        spindle_speeds = :spindle_speeds,
                        spindle_travel = :spindle_travel,
                        column_diameter = :column_diameter,
                        motor_power = :motor_power,
                        working_table_size = :working_table_size,
                        drilling_radius = :drilling_radius,
                        weight_approx = :weight_approx,
                        short_desc = :short_desc,
                        full_desc = :full_desc,
                        key_features = :key_features,
                        image_url = :image_url,
                        is_featured = :is_featured,
                        is_active = :is_active
                    WHERE id = :id
                ");

                $updateStmt->execute([
                    ':category_id' => $categoryId,
                    ':name' => $name,
                    ':model_number' => $modelNumber,
                    ':price' => $price,
                    ':stock_quantity' => $stockQuantity,
                    ':product_type' => $productType,
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
                    ':is_featured' => $isFeatured,
                    ':is_active' => $isActive,
                    ':id' => $id
                ]);

                $_SESSION['flash_message'] = [
                    'type' => 'success',
                    'text' => "Product `{$name}` details and image updated successfully!"
                ];
                header("Location: machines.php");
                exit;
            } catch (Exception $e) {
                $errorMessage = "Update failed: " . $e->getMessage();
            }
        }
    }
}
?>

<div class="admin-main">
    <header class="admin-topbar">
        <div style="display: flex; align-items: center; gap: 12px;">
            <button type="button" id="adminSidebarToggle" class="btn-sm btn-cyan" style="padding: 6px 10px; cursor: pointer;">☰</button>
            <h1 class="topbar-title">Edit Item: <?= e($machine['model_number']) ?></h1>
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
                <h3 class="card-title" style="margin-bottom: 20px;">Basic Information & Stock</h3>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Item Classification *</label>
                        <select name="product_type" class="admin-form-control" required>
                            <option value="machine" <?= (($machine['product_type'] ?? 'machine') === 'machine') ? 'selected' : '' ?>>Industrial Drill Machine (મશીનરી)</option>
                            <option value="part" <?= (($machine['product_type'] ?? 'machine') === 'part') ? 'selected' : '' ?>>Genuine Spare Part & Tooling (સ્પેરપાર્ટ)</option>
                        </select>
                    </div>
                    <div class="admin-form-group">
                        <label>Category *</label>
                        <select name="category_id" class="admin-form-control" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($machine['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                    [<?= strtoupper($cat['category_type'] ?? 'machine') ?>] <?= e($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Machine / Part Name *</label>
                        <input type="text" name="name" class="admin-form-control" value="<?= e($machine['name']) ?>" required>
                    </div>
                    <div class="admin-form-group">
                        <label>Model / Part Number *</label>
                        <input type="text" name="model_number" class="admin-form-control" value="<?= e($machine['model_number']) ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Base Price (₹ INR) *</label>
                        <input type="number" step="100" name="price" class="admin-form-control" value="<?= floatval($machine['price'] ?? 0) ?>" required>
                    </div>
                    <div class="admin-form-group">
                        <label>Factory Stock Available (Quantity) *</label>
                        <input type="number" name="stock_quantity" class="admin-form-control" value="<?= intval($machine['stock_quantity'] ?? 5) ?>" min="0" max="999" required>
                    </div>
                </div>

                <!-- Image Upload & Preview Section -->
                <h3 class="card-title" style="margin: 30px 0 20px; padding-top: 20px; border-top: 1px solid var(--admin-border);">
                    📸 Real Photo / Image Management
                </h3>

                <div style="background: rgba(11, 17, 32, 0.7); border: 1.5px dashed var(--accent-cyan); border-radius: 12px; padding: 22px; margin-bottom: 25px;">
                    <div style="display: grid; grid-template-columns: 140px 1fr; gap: 24px; align-items: center;">
                        <div style="text-align: center;">
                            <img id="imageLivePreview" src="<?= assetUrl($machine['image_url']) ?>" alt="Preview" style="width: 120px; height: 120px; object-fit: contain; background: #060911; border: 2px solid var(--accent-cyan); border-radius: 10px; padding: 6px; box-shadow: 0 0 20px rgba(0,229,255,0.2);">
                            <div style="font-size: 11px; color: #94a3b8; margin-top: 6px;">Current Image</div>
                        </div>

                        <div>
                            <div class="admin-form-group" style="margin-bottom: 14px;">
                                <label style="color: #34d399; font-weight: 700;">Upload New Real Photo</label>
                                <input type="file" name="image_file" id="imageFileInput" accept="image/*" class="admin-form-control" style="padding: 8px;">
                                <small style="color: #94a3b8; font-size: 11.5px; display: block; margin-top: 4px;">Choose a new photo to replace current image.</small>
                            </div>

                            <div class="admin-form-group" style="margin-bottom: 10px;">
                                <label style="color: var(--accent-cyan);">Image URL or Local Relative Path</label>
                                <input type="text" name="image_url" id="imageUrlInput" class="admin-form-control" value="<?= e($machine['image_url']) ?>">
                            </div>

                            <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-top: 10px;">
                                <button type="button" class="btn-sm btn-cyan pick-img-btn" data-src="assets/images/machinery/radial-drill-real.jpg">40mm Radial</button>
                                <button type="button" class="btn-sm btn-cyan pick-img-btn" data-src="assets/images/machinery/heavy_duty_radial_65_real.jpg">65mm Radial</button>
                                <button type="button" class="btn-sm btn-cyan pick-img-btn" data-src="assets/images/machinery/pillar_drill_real.jpg">Pillar Drill</button>
                                <button type="button" class="btn-sm btn-cyan pick-img-btn" data-src="assets/images/machinery/bench_drill_real.jpg">Bench Drill</button>
                                <button type="button" class="btn-sm btn-cyan pick-img-btn" data-src="assets/images/machinery/tapping_drill_real.jpg">Tapping Machine</button>
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
                        <input type="text" name="drilling_capacity" class="admin-form-control" value="<?= e($machine['drilling_capacity']) ?>" required>
                    </div>
                    <div class="admin-form-group">
                        <label>Spindle Speeds & RPM</label>
                        <input type="text" name="spindle_speeds" class="admin-form-control" value="<?= e($machine['spindle_speeds']) ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Spindle Travel</label>
                        <input type="text" name="spindle_travel" class="admin-form-control" value="<?= e($machine['spindle_travel']) ?>">
                    </div>
                    <div class="admin-form-group">
                        <label>Column Diameter</label>
                        <input type="text" name="column_diameter" class="admin-form-control" value="<?= e($machine['column_diameter']) ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Motor Power</label>
                        <input type="text" name="motor_power" class="admin-form-control" value="<?= e($machine['motor_power']) ?>">
                    </div>
                    <div class="admin-form-group">
                        <label>Working Table Size</label>
                        <input type="text" name="working_table_size" class="admin-form-control" value="<?= e($machine['working_table_size']) ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="admin-form-group">
                        <label>Drilling Radius (Max/Min)</label>
                        <input type="text" name="drilling_radius" class="admin-form-control" value="<?= e($machine['drilling_radius']) ?>">
                    </div>
                    <div class="admin-form-group">
                        <label>Approximate Weight</label>
                        <input type="text" name="weight_approx" class="admin-form-control" value="<?= e($machine['weight_approx']) ?>">
                    </div>
                </div>

                <div class="admin-form-group" style="margin-top: 20px;">
                    <label>Short Summary</label>
                    <textarea name="short_desc" class="admin-form-control" rows="2"><?= e($machine['short_desc']) ?></textarea>
                </div>

                <div class="admin-form-group">
                    <label>Full Detailed Technical Description</label>
                    <textarea name="full_desc" class="admin-form-control" rows="4"><?= e($machine['full_desc']) ?></textarea>
                </div>

                <div class="admin-form-group">
                    <label>Key Features (Separated by semicolon `;`)</label>
                    <textarea name="key_features" class="admin-form-control" rows="3"><?= e($machine['key_features']) ?></textarea>
                </div>

                <div style="display: flex; gap: 30px; margin: 24px 0; flex-wrap: wrap;">
                    <label style="display: flex; align-items: center; gap: 8px; color: #fff; cursor: pointer;">
                        <input type="checkbox" name="is_featured" value="1" <?= $machine['is_featured'] ? 'checked' : '' ?> style="accent-color: var(--accent-cyan);">
                        <span>Featured Product (Shows on homepage)</span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 8px; color: #fff; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" <?= $machine['is_active'] ? 'checked' : '' ?> style="accent-color: var(--accent-cyan);">
                        <span>Published Active</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 12px 28px; font-size: 15px; font-weight: 700;">
                    ⚡ Save Changes & Update Live
                </button>
            </form>
        </div>
    </main>
</div>

<script>
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

    document.querySelectorAll('.pick-img-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const src = this.getAttribute('data-src');
            imgInput.value = src;
            preview.src = "<?= BASE_URL ?>/" + src;
        });
    });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>