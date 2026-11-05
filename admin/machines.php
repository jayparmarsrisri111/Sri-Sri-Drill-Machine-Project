<?php
$adminTitle = 'Manage Drill Machines';
$activeTab = 'machines';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$flashMessage = $_SESSION['flash_message'] ?? null;
unset($_SESSION['flash_message']);

$pdo = Database::getConnection();

// Handle quick stock quantity update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_stock') {
    $mId = intval($_POST['machine_id'] ?? 0);
    $qty = max(0, intval($_POST['stock_quantity'] ?? 0));
    if ($mId > 0) {
        $up = $pdo->prepare("UPDATE products SET stock_quantity = :qty WHERE id = :id");
        $up->execute([':qty' => $qty, ':id' => $mId]);
        $_SESSION['flash_message'] = [
            'type' => 'success',
            'text' => "Stock quantity for Machine #{$mId} updated to {$qty} units!"
        ];
        header("Location: machines.php");
        exit;
    }
}

$machines = [];
try {
    $stmt = $pdo->query("
        SELECT p.*, c.name as category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        ORDER BY p.id DESC
    ");
    $machines = $stmt->fetchAll();
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<div class="admin-main">
    <header class="admin-topbar">
        <div style="display: flex; align-items: center; gap: 12px;">
            <button type="button" id="adminSidebarToggle" class="btn-sm btn-cyan" style="padding: 6px 10px; cursor: pointer;">☰</button>
            <h1 class="topbar-title">Manage Drill Machines Catalog</h1>
        </div>
        <div class="topbar-actions">
            <a href="machine-add.php" class="btn-sm btn-cyan">
                + Add New Machine
            </a>
        </div>
    </header>

    <main class="admin-body">
        <?php if (!empty($flashMessage)): ?>
            <div class="admin-alert alert-<?= $flashMessage['type'] ?>">
                <?= e($flashMessage['text']) ?>
            </div>
        <?php endif; ?>

        <div class="content-card">
            <div class="card-header-flex">
                <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                    <h3 class="card-title">All Products &amp; Parts (<?= count($machines) ?>)</h3>
                    <div style="display: flex; gap: 6px;">
                        <button type="button" class="btn-sm btn-cyan filter-type-btn active" data-filter="all">All (<?= count($machines) ?>)</button>
                        <button type="button" class="btn-sm btn-cyan filter-type-btn" data-filter="machine">Machines</button>
                        <button type="button" class="btn-sm btn-cyan filter-type-btn" data-filter="part">Spare Parts</button>
                    </div>
                </div>
                <div style="width: 280px;">
                    <input type="text" id="tableSearch" class="admin-form-control" placeholder="🔍 Search machine by model or name...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Type</th>
                            <th>Model / Part #</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Price (₹)</th>
                            <th style="width: 140px;">Factory Stock (Qty)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($machines)): ?>
                            <?php foreach ($machines as $m): 
                                $isPart = (($m['product_type'] ?? 'machine') === 'part');
                            ?>
                                <tr class="prod-row" data-type="<?= $isPart ? 'part' : 'machine' ?>">
                                    <td>
                                        <img src="<?= assetUrl($m['image_url']) ?>" alt="<?= e($m['name']) ?>" onerror="this.src='<?= assetUrl('assets/images/machinery/radial-drill-real.jpg') ?>'" style="width: 52px; height: 52px; object-fit: cover; background: #060911; border-radius: 8px; border: 1.5px solid <?= $isPart ? '#f59e0b' : 'var(--accent-cyan)' ?>; box-shadow: 0 2px 8px rgba(0,0,0,0.5);">
                                    </td>
                                    <td>
                                        <?php if ($isPart): ?>
                                            <span style="font-size: 10px; background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid #f59e0b; padding: 2px 6px; border-radius: 4px; font-weight: 700;">PART</span>
                                        <?php else: ?>
                                            <span style="font-size: 10px; background: rgba(0, 229, 255, 0.15); color: #00e5ff; border: 1px solid #00e5ff; padding: 2px 6px; border-radius: 4px; font-weight: 700;">MACHINE</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong style="color: var(--accent-cyan); font-family: var(--font-heading);"><?= e($m['model_number']) ?></strong>
                                    </td>
                                    <td>
                                        <strong style="color: #fff;"><?= e($m['name']) ?></strong>
                                        <?php if ($m['is_featured']): ?>
                                            <span style="font-size: 10px; background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid #f59e0b; padding: 1px 6px; border-radius: 4px; margin-left: 6px;">Featured</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= e($m['category_name']) ?></td>
                                    <td><strong style="color: #34d399;">₹ <?= number_format($m['price'] ?? 0, 0) ?></strong></td>
                                    <td>
                                        <form method="POST" action="machines.php" style="display: flex; align-items: center; gap: 6px;">
                                            <input type="hidden" name="action" value="update_stock">
                                            <input type="hidden" name="machine_id" value="<?= $m['id'] ?>">
                                            <input type="number" name="stock_quantity" value="<?= intval($m['stock_quantity'] ?? 5) ?>" min="0" max="999" style="width: 60px; padding: 5px 6px; background: #060911; border: 1.5px solid #0284c7; color: #34d399; border-radius: 4px; font-weight: 800; font-size: 13px; text-align: center;">
                                            <button type="submit" class="btn-sm btn-cyan" style="padding: 5px 8px; font-size: 11px; font-weight: 700;" title="Save Quantity">
                                                Save
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <?php if ($m['is_active']): ?>
                                            <span class="status-pill status-closed">Active</span>
                                        <?php else: ?>
                                            <span class="status-pill status-new" style="color: #94a3b8; border-color: #64748b;">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 8px;">
                                            <a href="machine-edit.php?id=<?= $m['id'] ?>" class="btn-sm btn-cyan" title="Edit Machine">
                                                Edit
                                            </a>
                                            <a href="../machine-detail.php?id=<?= $m['id'] ?>" target="_blank" class="btn-sm btn-success" title="View Machine Page">
                                                View
                                            </a>
                                            <a href="machine-delete.php?id=<?= $m['id'] ?>" class="btn-sm btn-danger confirm-delete" title="Delete Machine">
                                                Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                    No products or spare parts available. Click "+ Add New Machine" to add one!
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
    // Filter by product classification (Machine vs Part)
    document.querySelectorAll('.filter-type-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-type-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const filter = this.getAttribute('data-filter');
            document.querySelectorAll('.prod-row').forEach(row => {
                if (filter === 'all' || row.getAttribute('data-type') === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    // Table search filter
    const searchInput = document.getElementById('tableSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const q = this.value.toLowerCase().trim();
            document.querySelectorAll('.prod-row').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(q) ? '' : 'none';
            });
        });
    }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
