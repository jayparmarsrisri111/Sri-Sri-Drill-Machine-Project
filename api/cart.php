<?php
/**
 * Cart AJAX API Endpoint
 * Shree Varsha Engineering Works - Varsha Brand
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/cart-helper.php';

$pdo = Database::getConnection();

$action = $_REQUEST['action'] ?? 'get';

// 1. GET CART DATA
if ($action === 'get') {
    $summary = getCartSummary($pdo);
    echo json_encode([
        'success' => true,
        'cart_count' => $summary['total_qty'],
        'item_count' => $summary['item_count'],
        'summary' => $summary
    ]);
    exit;
}

// 2. ADD TO CART (Single product or Multiple bundle items)
if ($action === 'add') {
    // Check if adding bundle (array of product IDs)
    if (!empty($_POST['product_ids']) && is_array($_POST['product_ids'])) {
        $addedNames = [];
        $lastResult = null;
        foreach ($_POST['product_ids'] as $pId) {
            $pId = intval($pId);
            if ($pId > 0) {
                $res = addToCart($pdo, $pId, 1);
                if ($res['success']) {
                    $addedNames[] = $res['product_name'] ?? ('Item #' . $pId);
                    $lastResult = $res;
                }
            }
        }

        $summary = getCartSummary($pdo);
        echo json_encode([
            'success' => true,
            'message' => count($addedNames) . ' items added to cart successfully!',
            'added_items' => $addedNames,
            'cart_count' => $summary['total_qty'],
            'summary' => $summary
        ]);
        exit;
    }

    $productId = intval($_POST['product_id'] ?? ($_GET['product_id'] ?? 0));
    $quantity = max(1, intval($_POST['quantity'] ?? ($_GET['quantity'] ?? 1)));

    if ($productId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Please select a valid product.']);
        exit;
    }

    $res = addToCart($pdo, $productId, $quantity);
    echo json_encode($res);
    exit;
}

// 3. UPDATE QUANTITY
if ($action === 'update') {
    $productId = intval($_POST['product_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 1);

    if ($productId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product.']);
        exit;
    }

    $res = updateCartQuantity($pdo, $productId, $quantity);
    echo json_encode($res);
    exit;
}

// 4. REMOVE ITEM
if ($action === 'remove') {
    $productId = intval($_POST['product_id'] ?? 0);

    if ($productId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product.']);
        exit;
    }

    $res = removeFromCart($pdo, $productId);
    echo json_encode($res);
    exit;
}

// 5. CLEAR ENTIRE CART
if ($action === 'clear') {
    clearCart($pdo);
    $summary = getCartSummary($pdo);
    echo json_encode([
        'success' => true,
        'message' => 'Cart has been cleared.',
        'cart_count' => 0,
        'summary' => $summary
    ]);
    exit;
}

// Default fallback
echo json_encode(['success' => false, 'message' => 'Unknown action.']);
exit;
