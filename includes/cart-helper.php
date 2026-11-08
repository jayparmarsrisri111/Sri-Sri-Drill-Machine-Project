<?php
/**
 * Industrial E-Commerce Shopping Cart & Recommendation Engine
 * Shree Varsha Engineering Works - Varsha Brand
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE && php_sapi_name() !== 'cli') {
    @session_start();
}

/**
 * Returns or generates a persistent session identifier for cart storage
 */
function getCartSessionId(): string {
    if (!empty($_SESSION['cart_session_id'])) {
        return $_SESSION['cart_session_id'];
    }

    $cookieKey = 'varsha_cart_token';
    if (!empty($_COOKIE[$cookieKey]) && preg_match('/^[a-f0-9]{32,64}$/', $_COOKIE[$cookieKey])) {
        $_SESSION['cart_session_id'] = $_COOKIE[$cookieKey];
        return $_SESSION['cart_session_id'];
    }

    try {
        $newToken = bin2hex(random_bytes(16));
    } catch (Exception $e) {
        $newToken = md5(uniqid('cart_', true) . microtime(true));
    }

    $_SESSION['cart_session_id'] = $newToken;
    if (!headers_sent()) {
        setcookie($cookieKey, $newToken, time() + (86400 * 60), '/', '', false, true);
    }

    return $newToken;
}

/**
 * Syncs any guest cart items with the customer's account upon login
 */
function syncCustomerCart(PDO $pdo, int $customerId): void {
    if ($customerId <= 0) return;
    $sessionId = getCartSessionId();

    try {
        // Find items in guest session
        $stmt = $pdo->prepare("SELECT id, product_id, quantity FROM cart_items WHERE session_id = :sid");
        $stmt->execute([':sid' => $sessionId]);
        $guestItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($guestItems as $gi) {
            // Check if customer already has this item
            $chk = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE customer_id = :cid AND product_id = :pid");
            $chk->execute([':cid' => $customerId, ':pid' => $gi['product_id']]);
            $existing = $chk->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                // Update quantity and delete guest row
                $newQty = $existing['quantity'] + $gi['quantity'];
                $u = $pdo->prepare("UPDATE cart_items SET quantity = :qty, session_id = :sid WHERE id = :id");
                $u->execute([':qty' => $newQty, ':sid' => $sessionId, ':id' => $existing['id']]);

                $d = $pdo->prepare("DELETE FROM cart_items WHERE id = :id");
                $d->execute([':id' => $gi['id']]);
            } else {
                // Assign customer_id
                $u = $pdo->prepare("UPDATE cart_items SET customer_id = :cid WHERE id = :id");
                $u->execute([':cid' => $customerId, ':id' => $gi['id']]);
            }
        }
    } catch (Exception $e) {
        // Silently continue
    }
}

/**
 * Fetch all items in current user's cart
 */
function getCartItems(PDO $pdo): array {
    $sessionId = getCartSessionId();
    $customerId = !empty($_SESSION['customer_user']['id']) ? intval($_SESSION['customer_user']['id']) : null;

    try {
        if ($customerId) {
            $stmt = $pdo->prepare("
                SELECT ci.id as cart_item_id, ci.quantity, ci.created_at as item_added_at,
                       p.*, c.name as category_name, c.slug as category_slug
                FROM cart_items ci
                JOIN products p ON ci.product_id = p.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE (ci.customer_id = :cid OR ci.session_id = :sid)
                GROUP BY p.id
                ORDER BY ci.id DESC
            ");
            $stmt->execute([':cid' => $customerId, ':sid' => $sessionId]);
        } else {
            $stmt = $pdo->prepare("
                SELECT ci.id as cart_item_id, ci.quantity, ci.created_at as item_added_at,
                       p.*, c.name as category_name, c.slug as category_slug
                FROM cart_items ci
                JOIN products p ON ci.product_id = p.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE ci.session_id = :sid
                GROUP BY p.id
                ORDER BY ci.id DESC
            ");
            $stmt->execute([':sid' => $sessionId]);
        }

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculate item totals
        foreach ($items as &$item) {
            $unitPrice = floatval($item['price'] ?? 0);
            if ($unitPrice <= 0 && function_exists('getMachinePrice')) {
                $unitPrice = floatval(getMachinePrice($item['id']));
            }
            $item['unit_price'] = $unitPrice;
            $item['subtotal'] = $unitPrice * intval($item['quantity']);
            // Synthetic MRP for JioMart-style discount display (15% higher)
            $item['mrp_price'] = round($unitPrice * 1.15, -2);
            $item['mrp_subtotal'] = $item['mrp_price'] * intval($item['quantity']);
        }
        unset($item);

        return $items;
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Calculates complete cart summary (subtotal, taxes, savings, etc.)
 */
function getCartSummary(PDO $pdo): array {
    $items = getCartItems($pdo);
    $totalCount = count($items);
    $totalQty = 0;
    $taxableAmount = 0.0;
    $totalMrp = 0.0;
    $hasMachine = false;

    foreach ($items as $item) {
        $qty = intval($item['quantity']);
        $totalQty += $qty;
        $taxableAmount += $item['subtotal'];
        $totalMrp += $item['mrp_subtotal'];
        if (($item['product_type'] ?? 'machine') === 'machine' || $item['id'] < 100) {
            $hasMachine = true;
        }
    }

    $factoryDiscount = max(0, $totalMrp - $taxableAmount);
    $cgstAmount = round($taxableAmount * 0.09, 2);
    $sgstAmount = round($taxableAmount * 0.09, 2);
    $gstTotal = $cgstAmount + $sgstAmount;
    $grandTotal = $taxableAmount + $gstTotal;

    // Minimum Token Advance Calculation: ₹25,000 per machine, or 100% for spare parts
    $advanceAmount = 0.0;
    if ($hasMachine) {
        // ₹25,000 per machine in cart
        $machineCount = 0;
        foreach ($items as $item) {
            if (($item['product_type'] ?? 'machine') === 'machine' || $item['id'] < 100) {
                $machineCount += intval($item['quantity']);
            }
        }
        $advanceAmount = min($grandTotal, max(25000.0, 25000.0 * $machineCount));
    } else {
        $advanceAmount = $grandTotal; // 100% for parts
    }

    $_SESSION['cart_count'] = $totalQty;

    return [
        'items' => $items,
        'item_count' => $totalCount,
        'total_qty' => $totalQty,
        'total_mrp' => $totalMrp,
        'factory_discount' => $factoryDiscount,
        'taxable_amount' => $taxableAmount,
        'cgst_amount' => $cgstAmount,
        'sgst_amount' => $sgstAmount,
        'gst_total' => $gstTotal,
        'grand_total' => $grandTotal,
        'advance_amount' => $advanceAmount,
        'has_machine' => $hasMachine,
        'delivery_charge' => 0.0 // Free insured factory transit booking
    ];
}

/**
 * Add a single product or update quantity in cart
 */
function addToCart(PDO $pdo, int $productId, int $quantity = 1): array {
    if ($productId <= 0 || $quantity <= 0) {
        return ['success' => false, 'message' => 'Invalid product or quantity.'];
    }

    // Verify product exists
    $pStmt = $pdo->prepare("SELECT id, name, model_number, price, stock_quantity FROM products WHERE id = :id AND is_active = 1");
    $pStmt->execute([':id' => $productId]);
    $prod = $pStmt->fetch(PDO::FETCH_ASSOC);

    if (!$prod) {
        return ['success' => false, 'message' => 'Product not found or currently unavailable.'];
    }

    $sessionId = getCartSessionId();
    $customerId = !empty($_SESSION['customer_user']['id']) ? intval($_SESSION['customer_user']['id']) : null;
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $userAgent = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 250);

    try {
        // Check if item already in cart
        if ($customerId) {
            $chk = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE (customer_id = :cid OR session_id = :sid) AND product_id = :pid LIMIT 1");
            $chk->execute([':cid' => $customerId, ':sid' => $sessionId, ':pid' => $productId]);
        } else {
            $chk = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE session_id = :sid AND product_id = :pid LIMIT 1");
            $chk->execute([':sid' => $sessionId, ':pid' => $productId]);
        }
        $existing = $chk->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $newQty = $existing['quantity'] + $quantity;
            $u = $pdo->prepare("UPDATE cart_items SET quantity = :qty, customer_id = :cid, session_id = :sid, user_ip = :ip, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
            $u->execute([
                ':qty' => $newQty,
                ':cid' => $customerId,
                ':sid' => $sessionId,
                ':ip'  => $ip,
                ':id'  => $existing['id']
            ]);
        } else {
            $ins = $pdo->prepare("
                INSERT INTO cart_items (session_id, customer_id, product_id, quantity, user_ip, user_agent, created_at, updated_at)
                VALUES (:sid, :cid, :pid, :qty, :ip, :ua, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
            ");
            $ins->execute([
                ':sid' => $sessionId,
                ':cid' => $customerId,
                ':pid' => $productId,
                ':qty' => $quantity,
                ':ip'  => $ip,
                ':ua'  => $userAgent
            ]);
        }

        $summary = getCartSummary($pdo);

        return [
            'success' => true,
            'message' => 'Added "' . $prod['name'] . '" to cart successfully!',
            'product_name' => $prod['name'],
            'model_number' => $prod['model_number'],
            'cart_count' => $summary['total_qty'],
            'grand_total' => $summary['grand_total'],
            'summary' => $summary
        ];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}

/**
 * Update quantity of a cart item
 */
function updateCartQuantity(PDO $pdo, int $productId, int $quantity): array {
    if ($productId <= 0) {
        return ['success' => false, 'message' => 'Invalid product.'];
    }

    $sessionId = getCartSessionId();
    $customerId = !empty($_SESSION['customer_user']['id']) ? intval($_SESSION['customer_user']['id']) : null;

    try {
        if ($quantity <= 0) {
            return removeFromCart($pdo, $productId);
        }

        if ($customerId) {
            $u = $pdo->prepare("UPDATE cart_items SET quantity = :qty, updated_at = CURRENT_TIMESTAMP WHERE (customer_id = :cid OR session_id = :sid) AND product_id = :pid");
            $u->execute([':qty' => $quantity, ':cid' => $customerId, ':sid' => $sessionId, ':pid' => $productId]);
        } else {
            $u = $pdo->prepare("UPDATE cart_items SET quantity = :qty, updated_at = CURRENT_TIMESTAMP WHERE session_id = :sid AND product_id = :pid");
            $u->execute([':qty' => $quantity, ':sid' => $sessionId, ':pid' => $productId]);
        }

        $summary = getCartSummary($pdo);

        return [
            'success' => true,
            'message' => 'Quantity updated successfully.',
            'cart_count' => $summary['total_qty'],
            'summary' => $summary
        ];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Error updating quantity: ' . $e->getMessage()];
    }
}

/**
 * Remove an item from cart
 */
function removeFromCart(PDO $pdo, int $productId): array {
    if ($productId <= 0) {
        return ['success' => false, 'message' => 'Invalid product.'];
    }

    $sessionId = getCartSessionId();
    $customerId = !empty($_SESSION['customer_user']['id']) ? intval($_SESSION['customer_user']['id']) : null;

    try {
        if ($customerId) {
            $d = $pdo->prepare("DELETE FROM cart_items WHERE (customer_id = :cid OR session_id = :sid) AND product_id = :pid");
            $d->execute([':cid' => $customerId, ':sid' => $sessionId, ':pid' => $productId]);
        } else {
            $d = $pdo->prepare("DELETE FROM cart_items WHERE session_id = :sid AND product_id = :pid");
            $d->execute([':sid' => $sessionId, ':pid' => $productId]);
        }

        $summary = getCartSummary($pdo);

        return [
            'success' => true,
            'message' => 'Item removed from cart.',
            'cart_count' => $summary['total_qty'],
            'summary' => $summary
        ];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Error removing item: ' . $e->getMessage()];
    }
}

/**
 * Clear the entire cart for this user/session
 */
function clearCart(PDO $pdo): bool {
    $sessionId = getCartSessionId();
    $customerId = !empty($_SESSION['customer_user']['id']) ? intval($_SESSION['customer_user']['id']) : null;

    try {
        if ($customerId) {
            $d = $pdo->prepare("DELETE FROM cart_items WHERE customer_id = :cid OR session_id = :sid");
            $d->execute([':cid' => $customerId, ':sid' => $sessionId]);
        } else {
            $d = $pdo->prepare("DELETE FROM cart_items WHERE session_id = :sid");
            $d->execute([':sid' => $sessionId]);
        }
        $_SESSION['cart_count'] = 0;
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Amazon-Style Frequently Bought Together (મશીન સાથે વારંવાર ખરીદાતા ઓરિજિનલ સ્પેર પાર્ટ્સ)
 */
function getFrequentlyBoughtTogether(PDO $pdo, int $machineId): array {
    try {
        // Fetch machine
        $mStmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = :id");
        $mStmt->execute([':id' => $machineId]);
        $mainProduct = $mStmt->fetch(PDO::FETCH_ASSOC);

        if (!$mainProduct) return [];

        // Fetch complementary spare parts (Drill chuck, coolant pump, spindle quill, gear set)
        $partsStmt = $pdo->query("
            SELECT p.*, c.name as category_name 
            FROM products p 
            JOIN categories c ON p.category_id = c.id 
            WHERE (p.product_type = 'part' OR p.id >= 100) AND p.is_active = 1
            ORDER BY CASE 
                WHEN p.id = 105 THEN 1 -- Drill Chuck
                WHEN p.id = 106 THEN 2 -- Coolant Pump
                WHEN p.id = 101 THEN 3 -- Spindle Quill
                WHEN p.id = 104 THEN 4 -- Induction Motor
                ELSE 5 END
            LIMIT 3
        ");
        $suggestedParts = $partsStmt->fetchAll(PDO::FETCH_ASSOC);

        // Bundle calculations
        $totalOriginal = floatval($mainProduct['price']);
        foreach ($suggestedParts as $sp) {
            $totalOriginal += floatval($sp['price']);
        }

        // 5% Bundle combo discount
        $bundleDiscount = round($totalOriginal * 0.05, -1);
        $bundlePrice = $totalOriginal - $bundleDiscount;

        return [
            'main_product' => $mainProduct,
            'parts' => $suggestedParts,
            'total_original' => $totalOriginal,
            'bundle_discount' => $bundleDiscount,
            'bundle_price' => $bundlePrice
        ];
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Smart Price & Category Recommendation Engine
 * Finds products with similar pricing, complementary capacity, or top-rated machinery
 */
function getSmartRecommendations(PDO $pdo, int $excludeId = 0, float $referencePrice = 0, int $limit = 4): array {
    try {
        if ($referencePrice > 0) {
            // Price bracket: ± 40% or related products
            $minPrice = $referencePrice * 0.5;
            $maxPrice = $referencePrice * 1.6;

            $stmt = $pdo->prepare("
                SELECT p.*, c.name as category_name 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                WHERE p.id != :eid AND p.is_active = 1
                ORDER BY ABS(p.price - :ref_price) ASC
                LIMIT :lim
            ");
            $stmt->bindValue(':eid', $excludeId, PDO::PARAM_INT);
            $stmt->bindValue(':ref_price', $referencePrice, PDO::PARAM_STR);
            $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $stmt = $pdo->prepare("
                SELECT p.*, c.name as category_name 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                WHERE p.id != :eid AND p.is_active = 1
                ORDER BY p.is_featured DESC, p.id ASC
                LIMIT :lim
            ");
            $stmt->bindValue(':eid', $excludeId, PDO::PARAM_INT);
            $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    } catch (Exception $e) {
        return [];
    }
}
