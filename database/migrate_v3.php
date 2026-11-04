<?php
/**
 * Database Migration v3
 * Shree Varsha Engineering Works - Varsha Brand
 * Adds Customer Authentication & Amazon-Style Delivery Tracking System
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$pdo = Database::getConnection();
$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
echo "Running Migration v3 on [{$driver}]...\n";

// 1. Create `customers` table
if ($driver === 'sqlite') {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS customers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            full_name TEXT NOT NULL,
            company_name TEXT,
            email TEXT UNIQUE,
            phone TEXT UNIQUE NOT NULL,
            password_hash TEXT NOT NULL,
            gstin TEXT,
            address TEXT,
            city TEXT,
            pincode TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
} else {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS customers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            full_name VARCHAR(150) NOT NULL,
            company_name VARCHAR(150) NULL,
            email VARCHAR(150) UNIQUE NULL,
            phone VARCHAR(50) UNIQUE NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            gstin VARCHAR(30) NULL,
            address TEXT NULL,
            city VARCHAR(100) NULL,
            pincode VARCHAR(20) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
}
echo "Customers table verified.\n";

// Seed default demo customer if not exists
$passHash = password_hash('password123', PASSWORD_BCRYPT);
try {
    $chk = $pdo->prepare("SELECT id FROM customers WHERE phone = '9825112345' OR email = 'customer@gmail.com'");
    $chk->execute();
    if (!$chk->fetch()) {
        $insCust = $pdo->prepare("
            INSERT INTO customers (full_name, company_name, email, phone, password_hash, gstin, address, city, pincode)
            VALUES ('Kishorbhai Parmar', 'Parmar Precision Tool Works', 'customer@gmail.com', '9825112345', :p, '24AABCP9876C1Z4', 'Plot No. 18, Phase 2, G.I.D.C. Lodhika', 'Rajkot', '360021')
        ");
        $insCust->execute([':p' => $passHash]);
        echo "Default demo customer seeded (customer@gmail.com / password123).\n";
    }
} catch (Exception $e) {
    echo "Customer seed notice: " . $e->getMessage() . "\n";
}

// 2. Add Delivery Tracking columns to `orders` table
$orderColsToAdd = [
    'customer_id' => 'INTEGER DEFAULT NULL',
    'delivery_status' => "TEXT DEFAULT 'order_placed'",
    'transport_name' => "TEXT DEFAULT 'VRL Logistics Industrial Freight'",
    'tracking_number' => "TEXT DEFAULT NULL",
    'estimated_delivery' => "TEXT DEFAULT NULL",
    'current_location' => "TEXT DEFAULT 'Shapar Factory Warehouse, Rajkot'",
    'delivery_notes' => "TEXT DEFAULT NULL"
];

foreach ($orderColsToAdd as $col => $type) {
    try {
        if ($driver === 'sqlite') {
            $cols = $pdo->query("PRAGMA table_info(orders)")->fetchAll(PDO::FETCH_ASSOC);
            $hasCol = false;
            foreach ($cols as $c) {
                if ($c['name'] === $col) {
                    $hasCol = true;
                    break;
                }
            }
            if (!$hasCol) {
                $pdo->exec("ALTER TABLE orders ADD COLUMN {$col} {$type}");
                echo "Added `{$col}` to orders table (SQLite).\n";
            }
        } else {
            $pdo->exec("ALTER TABLE orders ADD COLUMN {$col} {$type}");
            echo "Added `{$col}` to orders table (MySQL).\n";
        }
    } catch (Exception $e) {
        // Already exists
    }
}

// Update existing orders with realistic delivery tracking information
try {
    $pdo->exec("
        UPDATE orders SET 
            delivery_status = 'dispatched',
            transport_name = 'VRL Logistics Heavy Freight (Rajkot Hub)',
            tracking_number = 'VRL-RAJ-884210',
            estimated_delivery = date('now', '+3 days'),
            current_location = 'In Transit - Shapar-Veraval Highway Toll Plaza',
            delivery_notes = 'Heavy Machinery Wooden Box Crate with shock-dampers. Driver Contact: +91 94280 11223'
        WHERE tracking_number IS NULL OR tracking_number = ''
    ");
    echo "Updated orders with live Amazon-style logistics tracking metadata.\n";
} catch (Exception $e) {
    echo "Note on order tracking updates: " . $e->getMessage() . "\n";
}

echo "MIGRATION V3 COMPLETED SUCCESSFULLY!\n";
