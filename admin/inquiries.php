<?php
$adminTitle = 'Customer Inquiries & Quotes';
$activeTab = 'inquiries';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$pdo = Database::getConnection();

// Handle status update if submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $inqId = intval($_POST['inquiry_id'] ?? 0);
    $newStatus = trim($_POST['status'] ?? 'new');
    $adminNotes = trim($_POST['admin_notes'] ?? '');

    try {
        $updateStmt = $pdo->prepare("UPDATE inquiries SET status = :s, admin_notes = :n WHERE id = :id");
        $updateStmt->execute([':s' => $newStatus, ':n' => $adminNotes, ':id' => $inqId]);
        $_SESSION['flash_message'] = ['type' => 'success', 'text' => "Inquiry #{$inqId} status updated to " . strtoupper($newStatus)];
    } catch (Exception $e) {
        $_SESSION['flash_message'] = ['type' => 'danger', 'text' => $e->getMessage()];
    }
}

// Handle delete
if (isset($_GET['delete_id'])) {
    $delId = intval($_GET['delete_id']);
    try {
        $delStmt = $pdo->prepare("DELETE FROM inquiries WHERE id = :id");
        $delStmt->execute([':id' => $delId]);
        $_SESSION['flash_message'] = ['type' => 'success', 'text' => "Inquiry #{$delId} removed successfully."];
    } catch (Exception $e) {
        $_SESSION['flash_message'] = ['type' => 'danger', 'text' => $e->getMessage()];
    }
}

$statusFilter = $_GET['status'] ?? 'all';
$sql = "SELECT * FROM inquiries";
if ($statusFilter !== 'all') {
    $sql .= " WHERE status = " . $pdo->quote($statusFilter);
}
$sql .= " ORDER BY id DESC";

$inquiries = $pdo->query($sql)->fetchAll();

$flashMessage = $_SESSION['flash_message'] ?? null;
unset($_SESSION['flash_message']);
?>

<div class="admin-main">
    <header class="admin-topbar">
        <div style="display: flex; align-items: center; gap: 12px;">
            <button type="button" id="adminSidebarToggle" class="btn-sm btn-cyan" style="padding: 6px 10px; cursor: pointer;">☰</button>
            <h1 class="topbar-title">Customer Quotations & Leads</h1>
        </div>
        <div class="topbar-actions">
            <a href="inquiries.php" class="btn-sm <?= ($statusFilter === 'all') ? 'btn-cyan' : 'btn-outline' ?>">All</a>
            <a href="inquiries.php?status=new" class="btn-sm <?= ($statusFilter === 'new') ? 'btn-cyan' : 'btn-outline' ?>">New Leads</a>
            <a href="inquiries.php?status=contacted" class="btn-sm <?= ($statusFilter === 'contacted') ? 'btn-cyan' : 'btn-outline' ?>">Contacted</a>
            <a href="inquiries.php?status=quoted" class="btn-sm <?= ($statusFilter === 'quoted') ? 'btn-cyan' : 'btn-outline' ?>">Quoted</a>
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
                <h3 class="card-title">Received Inquiries (<?= count($inquiries) ?>)</h3>
                <div style="width: 280px;">
                    <input type="text" id="tableSearch" class="admin-form-control" placeholder="🔍 Search by customer name, phone...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID & Date</th>
                            <th>Customer & Company</th>
                            <th>Contact Info</th>
                            <th>Machine Requirement</th>
                            <th>Message</th>
                            <th>Status & Follow-up</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($inquiries)): ?>
                            <?php foreach ($inquiries as $inq): ?>
                                <tr id="inq-<?= $inq['id'] ?>">
                                    <td style="font-size: 12.5px; color: var(--text-muted);">
                                        <strong>#<?= $inq['id'] ?></strong><br>
                                        <?= date('d M Y', strtotime($inq['created_at'])) ?><br>
                                        <?= date('h:i A', strtotime($inq['created_at'])) ?>
                                    </td>
                                    <td>
                                        <strong style="color: #fff; font-size: 14.5px;"><?= e($inq['customer_name']) ?></strong>
                                        <?php if (!empty($inq['company_name'])): ?>
                                            <div style="font-size: 12.5px; color: var(--accent-cyan);"><?= e($inq['company_name']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div><a href="tel:<?= e($inq['phone']) ?>" style="color: #38bdf8; font-weight: 600;"><?= e($inq['phone']) ?></a></div>
                                        <div style="font-size: 12px; color: var(--text-muted);"><?= e($inq['email']) ?></div>
                                    </td>
                                    <td>
                                        <span style="color: #e2e8f0; font-weight: 600;"><?= e($inq['machine_name'] ?? 'General Inquiry') ?></span>
                                    </td>
                                    <td style="max-width: 250px;">
                                        <div style="font-size: 13px; color: #cbd5e1; line-height: 1.4;">
                                            <?= nl2br(e($inq['message'])) ?>
                                        </div>
                                        <?php if (!empty($inq['admin_notes'])): ?>
                                            <div style="margin-top: 6px; font-size: 11.5px; color: #fbbf24; background: rgba(245, 158, 11, 0.1); padding: 4px 6px; border-radius: 4px;">
                                                <strong>Note:</strong> <?= e($inq['admin_notes']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form method="POST" style="display: flex; flex-direction: column; gap: 6px;">
                                            <input type="hidden" name="inquiry_id" value="<?= $inq['id'] ?>">
                                            <select name="status" class="admin-form-control" style="font-size: 12px; padding: 4px 8px;" onchange="this.form.submit()">
                                                <option value="new" <?= ($inq['status'] === 'new') ? 'selected' : '' ?>>NEW</option>
                                                <option value="contacted" <?= ($inq['status'] === 'contacted') ? 'selected' : '' ?>>CONTACTED</option>
                                                <option value="quoted" <?= ($inq['status'] === 'quoted') ? 'selected' : '' ?>>QUOTED</option>
                                                <option value="closed" <?= ($inq['status'] === 'closed') ? 'selected' : '' ?>>CLOSED</option>
                                            </select>
                                            <input type="hidden" name="update_status" value="1">
                                        </form>
                                    </td>
                                    <td>
                                        <div style="display: flex; flex-direction: column; gap: 6px;">
                                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $inq['phone']) ?>?text=<?= urlencode("Hello " . $inq['customer_name'] . ", Jitendrabhai Parmar here from Shree Varsha Engineering Works regarding your requirement for " . $inq['machine_name'] . ".") ?>" target="_blank" class="btn-sm btn-whatsapp-sm" style="text-align: center;">
                                                WhatsApp
                                            </a>
                                            <a href="inquiries.php?delete_id=<?= $inq['id'] ?>" class="btn-sm btn-danger confirm-delete" style="text-align: center;">
                                                Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                    No customer inquiries matching criteria.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
