<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';

// Auth Guard
if (empty($_SESSION['admin_user'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    try {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $_SESSION['flash_message'] = [
            'type' => 'success',
            'text' => 'Drill machine deleted successfully from catalog.'
        ];
    } catch (Exception $e) {
        $_SESSION['flash_message'] = [
            'type' => 'danger',
            'text' => 'Failed to delete machine: ' . $e->getMessage()
        ];
    }
}

header("Location: machines.php");
exit;
