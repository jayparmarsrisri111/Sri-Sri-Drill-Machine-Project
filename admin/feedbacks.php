<?php
$adminTitle = 'Customer Ratings & Feedbacks';
$activeTab = 'feedbacks';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$pdo = Database::getConnection();
$flashMessage = $_SESSION['flash_message'] ?? null;
unset($_SESSION['flash_message']);

// Actions: Toggle Approve, Toggle Feature, Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $fbId = intval($_POST['feedback_id'] ?? 0);
    $action = $_POST['action'];

    if ($fbId > 0) {
        if ($action === 'toggle_approve') {
            $pdo->exec("UPDATE feedbacks SET is_approved = CASE WHEN is_approved = 1 THEN 0 ELSE 1 END WHERE id = {$fbId}");
            $_SESSION['flash_message'] = ['type' => 'success', 'text' => "Feedback #{$fbId} status updated!"];
        } elseif ($action === 'toggle_feature') {
            $pdo->exec("UPDATE feedbacks SET is_featured = CASE WHEN is_featured = 1 THEN 0 ELSE 1 END WHERE id = {$fbId}");
            $_SESSION['flash_message'] = ['type' => 'success', 'text' => "Feedback #{$fbId} featured visibility updated!"];
        } elseif ($action === 'delete') {
            $pdo->exec("DELETE FROM feedbacks WHERE id = {$fbId}");
            $_SESSION['flash_message'] = ['type' => 'success', 'text' => "Feedback #{$fbId} deleted permanently."];
        }
        header("Location: feedbacks.php");
        exit;
    }
}

$feedbacks = [];
try {
    $feedbacks = $pdo->query("SELECT * FROM feedbacks ORDER BY id DESC")->fetchAll();
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<div class="admin-main">
    <header class="admin-topbar">
        <div style="display: flex; align-items: center; gap: 12px;">
            <button type="button" id="adminSidebarToggle" class="btn-sm btn-cyan" style="padding: 6px 10px; cursor: pointer;">☰</button>
            <h1 class="topbar-title">Customer Ratings &amp; Factory Reviews</h1>
        </div>
        <div class="topbar-actions">
            <a href="<?= BASE_URL ?>/index.php#testimonialsSection" target="_blank" class="btn-sm btn-cyan">
                🌐 View on Website
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
                <h3 class="card-title">All Customer Reviews (<?= count($feedbacks) ?>)</h3>
                <div style="font-size: 13px; color: #94a3b8;">
                    Approved reviews are displayed on the public homepage and about page.
                </div>
            </div>

            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Rating</th>
                            <th>Customer &amp; Company</th>
                            <th>Machine / Part</th>
                            <th>Review Title &amp; Feedback</th>
                            <th>Approval Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($feedbacks)): ?>
                            <?php foreach ($feedbacks as $fb): ?>
                                <tr>
                                    <td style="font-size: 12px; color: #94a3b8; white-space: nowrap;">
                                        <?= date('d-M-Y', strtotime($fb['created_at'])) ?><br>
                                        <small><?= date('h:i A', strtotime($fb['created_at'])) ?></small>
                                    </td>
                                    <td>
                                        <div style="color: #fbbf24; font-size: 14px; white-space: nowrap;">
                                            <?= str_repeat('★', intval($fb['rating'])) . str_repeat('☆', 5 - intval($fb['rating'])) ?>
                                        </div>
                                        <small style="color: #94a3b8; font-size: 11px;"><?= $fb['rating'] ?> / 5 Stars</small>
                                    </td>
                                    <td>
                                        <strong style="color: #fff; font-size: 13.5px;"><?= e($fb['customer_name']) ?></strong>
                                        <?php if (!empty($fb['company_name'])): ?>
                                            <div style="font-size: 12px; color: #38bdf8;"><?= e($fb['company_name']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($fb['city'])): ?>
                                            <div style="font-size: 11px; color: #94a3b8;">📍 <?= e($fb['city']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($fb['phone'])): ?>
                                            <div style="font-size: 11px; color: #94a3b8;"><a href="tel:<?= e($fb['phone']) ?>" style="color:#94a3b8;"><?= e($fb['phone']) ?></a></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge-tag" style="border-color: #38bdf8; color: #38bdf8; font-size: 11px;">
                                            <?= e($fb['product_name'] ?? 'General Machinery') ?>
                                        </span>
                                    </td>
                                    <td style="max-width: 320px;">
                                        <?php if (!empty($fb['feedback_title'])): ?>
                                            <div style="font-weight: 700; color: #fff; font-size: 13px; margin-bottom: 4px;">
                                                <?= e($fb['feedback_title']) ?>
                                            </div>
                                        <?php endif; ?>
                                        <div style="font-size: 12.5px; color: #cbd5e1; line-height: 1.4;">
                                            <?= nl2br(e($fb['comments'])) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <form method="POST" action="feedbacks.php" style="display:inline-block;">
                                            <input type="hidden" name="action" value="toggle_approve">
                                            <input type="hidden" name="feedback_id" value="<?= $fb['id'] ?>">
                                            <button type="submit" class="status-pill <?= $fb['is_approved'] ? 'status-closed' : 'status-new' ?>" style="cursor: pointer; border: 1px solid; background: transparent;">
                                                <?= $fb['is_approved'] ? '✔ Published' : '⏳ Hidden' ?>
                                            </button>
                                        </form>
                                        <div style="margin-top: 4px;">
                                            <form method="POST" action="feedbacks.php" style="display:inline-block;">
                                                <input type="hidden" name="action" value="toggle_feature">
                                                <input type="hidden" name="feedback_id" value="<?= $fb['id'] ?>">
                                                <button type="submit" style="font-size: 10px; background: none; border: none; color: <?= $fb['is_featured'] ? '#fbbf24' : '#64748b' ?>; cursor: pointer; text-decoration: underline;">
                                                    <?= $fb['is_featured'] ? '★ Top Featured' : '☆ Regular' ?>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td>
                                        <form method="POST" action="feedbacks.php" onsubmit="return confirm('Delete this feedback permanently?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="feedback_id" value="<?= $fb['id'] ?>">
                                            <button type="submit" class="btn-sm btn-danger" style="padding: 4px 8px; font-size: 11px;">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                    No customer reviews recorded yet.
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