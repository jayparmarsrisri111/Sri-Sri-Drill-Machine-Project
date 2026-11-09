<?php
/**
 * Database Connection Configuration
 * Shree Varsha Engineering Works
 * Supports standard MySQL PDO with automatic SQLite fallback for instant zero-config testing
 */

// Database Credentials for MySQL
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_PORT')) define('DB_PORT', '3306');
if (!defined('DB_NAME')) define('DB_NAME', 'shree_varsha_drill_db');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');

require_once __DIR__ . '/config.php';

class Database {
    private static ?PDO $instance = null;
    private static string $driverType = 'mysql';

    public static function getConnection(): PDO {
        if (self::$instance !== null) {
            return self::$instance;
        }

        // Try MySQL First
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_TIMEOUT => 2, // Quick timeout if MySQL daemon not running
            ];
            self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            self::$driverType = 'mysql';

            // Guarantee MySQL schema tables and columns are up to date
            self::ensureMysqlSchema(self::$instance);

            return self::$instance;
        } catch (PDOException $e) {
            // If MySQL is unavailable (e.g., database not created or MySQL offline),
            // fallback gracefully to SQLite local database file
            $sqlitePath = dirname(__DIR__) . '/database/drill_machines.sqlite';
            try {
                $dsn = "sqlite:" . $sqlitePath;
                self::$instance = new PDO($dsn, null, null, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
                self::$driverType = 'sqlite';

                // Check if tables exist in SQLite, if not initialize them
                self::ensureSqliteSchema(self::$instance);

                return self::$instance;
            } catch (PDOException $sqle) {
                die("Database Connection Error: " . $sqle->getMessage());
            }
        }
    }

    public static function getDriverType(): string {
        return self::$driverType;
    }

    private static function ensureMysqlSchema(PDO $pdo): void {
        try {
            // 1. Ensure customers table exists
            $pdo->exec("CREATE TABLE IF NOT EXISTS `customers` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `full_name` VARCHAR(100) NOT NULL,
                `company_name` VARCHAR(150) DEFAULT NULL,
                `email` VARCHAR(100) DEFAULT NULL UNIQUE,
                `phone` VARCHAR(30) NOT NULL,
                `password_hash` VARCHAR(255) NOT NULL,
                `gstin` VARCHAR(30) DEFAULT NULL,
                `address` TEXT DEFAULT NULL,
                `city` VARCHAR(100) DEFAULT 'Bhavnagar',
                `pincode` VARCHAR(20) DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX (`phone`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            // Seed demo customers if empty
            $cCount = $pdo->query("SELECT COUNT(*) FROM `customers`")->fetchColumn();
            if ($cCount == 0) {
                $hash = password_hash('password123', PASSWORD_DEFAULT);
                $pdo->exec("INSERT INTO `customers` (`full_name`, `company_name`, `email`, `phone`, `password_hash`, `gstin`, `address`, `city`) VALUES
                    ('Kishorbhai Parmar', 'Parmar Precision Tool Works', 'customer@gmail.com', '9825112345', '{$hash}', '24AJHPP0087E1ZD', 'Shed No. L/786, GIDC, Nr. Hadanagar, Bhavnagar', 'Bhavnagar'),
                    ('Ramesh Patel', 'Patel Engineering Works', 'ramesh@patelengg.com', '9824913555', '{$hash}', '24AAACS1234F1Z5', 'Plot 14, GIDC Industrial Estate', 'Bhavnagar'),
                    ('Bhavikbhai Patel', 'Patel CNC & Machinery', 'bhavik@patelmachinery.com', '9898012345', '{$hash}', '24BCDPS5678G1Z2', 'Survey 44, National Highway', 'Ahmedabad');");
            }

            // 2. Ensure categories.category_type exists
            $cols = $pdo->query("SHOW COLUMNS FROM `categories` LIKE 'category_type'")->fetchAll();
            if (empty($cols)) {
                $pdo->exec("ALTER TABLE `categories` ADD COLUMN `category_type` VARCHAR(50) DEFAULT 'machine'");
            }

            // 3. Ensure products.product_type exists
            $pcols = $pdo->query("SHOW COLUMNS FROM `products` LIKE 'product_type'")->fetchAll();
            if (empty($pcols)) {
                $pdo->exec("ALTER TABLE `products` ADD COLUMN `product_type` VARCHAR(50) DEFAULT 'machine'");
            }

            // 4. Ensure orders logistics & customer columns exist
            $cidCol = $pdo->query("SHOW COLUMNS FROM `orders` LIKE 'customer_id'")->fetchAll();
            if (empty($cidCol)) {
                $pdo->exec("ALTER TABLE `orders` ADD COLUMN `customer_id` INT DEFAULT NULL");
            }

            $ordCols = $pdo->query("SHOW COLUMNS FROM `orders` LIKE 'delivery_status'")->fetchAll();
            if (empty($ordCols)) {
                $pdo->exec("ALTER TABLE `orders` 
                    ADD COLUMN `delivery_status` VARCHAR(50) DEFAULT 'order_placed',
                    ADD COLUMN `transport_name` VARCHAR(150) DEFAULT 'VRL Logistics',
                    ADD COLUMN `tracking_number` VARCHAR(100) DEFAULT 'VRL-RAJ-884210',
                    ADD COLUMN `estimated_delivery` DATE DEFAULT NULL,
                    ADD COLUMN `current_location` VARCHAR(150) DEFAULT 'Bhavnagar Factory Warehouse',
                    ADD COLUMN `delivery_notes` TEXT DEFAULT NULL");
            }

            // Ensure orders.quantity exists
            $qtyCol = $pdo->query("SHOW COLUMNS FROM `orders` LIKE 'quantity'")->fetchAll();
            if (empty($qtyCol)) {
                $pdo->exec("ALTER TABLE `orders` ADD COLUMN `quantity` INT DEFAULT 1");
            }

            // Ensure orders.payment_method exists (online, cod, bank_transfer, cheque)
            $pmCol = $pdo->query("SHOW COLUMNS FROM `orders` LIKE 'payment_method'")->fetchAll();
            if (empty($pmCol)) {
                $pdo->exec("ALTER TABLE `orders` ADD COLUMN `payment_method` VARCHAR(50) DEFAULT 'online'");
            }

            // Ensure cart_items table exists
            $pdo->exec("CREATE TABLE IF NOT EXISTS `cart_items` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `session_id` VARCHAR(100) NOT NULL,
                `customer_id` INT DEFAULT NULL,
                `product_id` INT NOT NULL,
                `quantity` INT NOT NULL DEFAULT 1,
                `user_ip` VARCHAR(45) DEFAULT NULL,
                `user_agent` TEXT DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX (`session_id`),
                INDEX (`customer_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            // Ensure order_items table exists
            $pdo->exec("CREATE TABLE IF NOT EXISTS `order_items` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `order_id` INT NOT NULL,
                `product_id` INT NOT NULL,
                `product_name` VARCHAR(255) NOT NULL,
                `model_number` VARCHAR(100) NOT NULL,
                `quantity` INT NOT NULL DEFAULT 1,
                `unit_price` DECIMAL(12,2) NOT NULL,
                `total_price` DECIMAL(12,2) NOT NULL,
                `image_url` VARCHAR(255) DEFAULT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX (`order_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            // 5. Ensure real machine photos instead of legacy vector SVGs
            self::ensureRealMachineImages($pdo);
        } catch (Exception $e) {
            // Silently continue if permissions restrict ALTER
        }
    }

    private static function ensureSqliteSchema(PDO $pdo): void {
        $check = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='products'")->fetch();
        if (!$check) {
            $schemaFile = dirname(__DIR__) . '/database/schema_sqlite.sql';
            if (file_exists($schemaFile)) {
                $sql = file_get_contents($schemaFile);
                $pdo->exec($sql);
            }
        }

        // Always guarantee orders table exists in SQLite
        $orderCheck = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='orders'")->fetch();
        if (!$orderCheck) {
            $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                order_number TEXT NOT NULL UNIQUE,
                invoice_number TEXT NOT NULL UNIQUE,
                product_id INTEGER DEFAULT NULL,
                product_name TEXT NOT NULL,
                model_number TEXT NOT NULL,
                customer_name TEXT NOT NULL,
                company_name TEXT DEFAULT NULL,
                customer_email TEXT NOT NULL,
                customer_phone TEXT NOT NULL,
                customer_address TEXT NOT NULL,
                customer_city TEXT NOT NULL,
                customer_pincode TEXT NOT NULL,
                customer_gst TEXT DEFAULT NULL,
                payment_type TEXT DEFAULT 'booking_advance',
                machine_price REAL NOT NULL,
                taxable_amount REAL NOT NULL,
                cgst_amount REAL NOT NULL,
                sgst_amount REAL NOT NULL,
                total_amount REAL NOT NULL,
                razorpay_order_id TEXT DEFAULT NULL,
                razorpay_payment_id TEXT DEFAULT NULL,
                razorpay_signature TEXT DEFAULT NULL,
                payment_status TEXT DEFAULT 'pending',
                payment_method TEXT DEFAULT 'online',
                order_status TEXT DEFAULT 'confirmed',
                authorized_signatory TEXT DEFAULT 'Jay Parmar',
                crypto_seal TEXT DEFAULT NULL,
                notes TEXT DEFAULT NULL,
                quantity INTEGER DEFAULT 1,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )");
        } else {
            try {
                $cols = $pdo->query("PRAGMA table_info(orders)")->fetchAll(PDO::FETCH_COLUMN, 1);
                if (!in_array('quantity', $cols)) {
                    $pdo->exec("ALTER TABLE orders ADD COLUMN quantity INTEGER DEFAULT 1");
                }
                if (!in_array('payment_method', $cols)) {
                    $pdo->exec("ALTER TABLE orders ADD COLUMN payment_method TEXT DEFAULT 'online'");
                }
            } catch (Exception $ex) {}
        }

        // Guarantee cart_items and order_items tables exist in SQLite
        try {
            $pdo->exec("CREATE TABLE IF NOT EXISTS cart_items (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                session_id TEXT NOT NULL,
                customer_id INTEGER DEFAULT NULL,
                product_id INTEGER NOT NULL,
                quantity INTEGER NOT NULL DEFAULT 1,
                user_ip TEXT DEFAULT NULL,
                user_agent TEXT DEFAULT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )");

            $pdo->exec("CREATE TABLE IF NOT EXISTS order_items (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                order_id INTEGER NOT NULL,
                product_id INTEGER NOT NULL,
                product_name TEXT NOT NULL,
                model_number TEXT NOT NULL,
                quantity INTEGER NOT NULL DEFAULT 1,
                unit_price REAL NOT NULL,
                total_price REAL NOT NULL,
                image_url TEXT DEFAULT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )");

            // Guarantee new catalog models 9, 10, 11, 12 exist in products table
            $countNew = $pdo->query("SELECT COUNT(*) FROM products WHERE id IN (9, 10, 11, 12)")->fetchColumn();
            if ($countNew < 4) {
                $pdo->exec("INSERT OR IGNORE INTO products (id, category_id, name, model_number, slug, drilling_capacity, spindle_travel, spindle_speeds, column_diameter, motor_power, working_table_size, drilling_radius, weight_approx, short_desc, full_desc, key_features, image_url, is_featured, is_active, price) VALUES
                (9, 3, 'Bench Heavy Duty Drill Machine (13mm - 20mm Capacity)', 'SVEW-BD-13HD', 'bench-heavy-duty-drill-machine-13mm', '13 mm (1/2 inch) to 20 mm Steel', '80 mm Spindle Travel', '4 Speeds (1440 RPM Motor)', 'Solid Steel Pillar', '0.5 HP / 1 HP Electric Motor (220-240V, 50-60 Hz)', 'Color Coated MS Base & Table', '260 mm throat clearance', '95 kg', 'Heavy duty industrial bench drill machine with MS body, solid spindle taper, and precision manual feed for factory engineering workshops.', 'Manufactured with high-precision engineering standards from Bhavnagar, Gujarat. Built with a rugged MS body, color-coated anti-corrosion finish, and vibration-free single phase/three phase power compatibility.', '13mm to 20mm steel drilling capacity;0.5 HP to 1.0 HP electric motor;Solid spindle taper;80mm spindle travel;Color coated durable finish;Made in India Bhavnagar excellence', 'assets/images/machinery/bench-drill-heavy-duty-23500.jpg', 1, 1, 23500),
                (10, 2, '25mm Heavy Duty Column Pillar Drill Machine (25X385)', 'SVEW-PD-25X385', '25mm-heavy-duty-column-pillar-drill-machine-25x385', '25 mm (1 inch) in Steel & Cast Iron', '158 mm Spindle Travel', '8 Spindle Speeds', '75 mm Ground Cast Iron Pillar', '1 HP / 2 HP Three Phase Induction Motor', '385 x 385 mm Precision Cast Table', '385 mm throat distance', '290 kg', 'Heavy duty vertical pillar drill press with 25mm drilling capacity, MT-3 spindle taper, 158mm travel, and 8-speed gearbox.', 'Engineered for industrial batch production, fabricators, and automotive workshops. Model 25X385 features a heavy 75mm ground column, 158mm stroke depth, and M20 tapping capability.', '25mm drilling capacity in steel;MT-3 Spindle Taper with 158mm travel;8-Speed multi-step pulley transmission;75mm precision ground column;20mm tapping capacity;100% heavy duty CI body', 'assets/images/machinery/pillar-drill-25-heavy-73000.jpg', 1, 1, 73000),
                (11, 2, 'Precision Heavy Duty Industrial Drill Press (20mm - 25mm)', 'SVEW-DP-25HD', 'precision-heavy-duty-industrial-drill-press-20-25mm', '20 mm to 25 mm Drilling Capacity', '140 mm Spindle Travel', '6/8 Speeds Multi-Drive', '85 mm Machined Heavy Column', '1.0 HP Heavy Induction Motor (Three Phase / Single Phase)', 'Adjustable Heavy Slotted Machined Table', '350 mm throat reach', '210 kg', 'Industrial high-rigidity radial and column drill press with dual feed controls and vibration-resistant ribbed base plate.', 'High endurance machine designed for continuous production and reaming in industrial manufacturing. Delivers exceptional stability and minimal runout under heavy load.', '20mm - 25mm capacity;Balanced pulley drive;Heavy ribbed ground table;Fine depth dial stop;Built-in tool storage slots', 'assets/images/machinery/precision-drill-press-25000.jpg', 1, 1, 25000),
                (12, 4, 'Automatic Drilling & Tapping Machine 25mm (Compound Coordinate Table)', 'SVEW-TP-25HD', 'automatic-drilling-tapping-machine-25mm-compound-table', '25 mm Drilling / M20 Tapping', '165 mm Spindle Travel', '8 Speeds with Auto Reverse', '110 mm Solid Alloy Pillar', '2.0 HP Reversible Motor with Electronic Foot Pedal', 'Compound X-Y Coordinate Slide Table (450 x 220 mm)', '400 mm throat clearance', '420 kg', 'High accuracy drilling and tapping machine equipped with 2-axis compound sliding coordinate table for precision hole patterns and die-making.', 'Integrated cross-sliding table allows precise coordinate hole positioning without unclamping the workpiece. Features auto-reversing spindle on preset depth limit for smooth threading.', 'Compound X-Y coordinate sliding table;Auto-reversing tapping cycle;Dual direction high-torque motor;Micrometer depth limit switch;Heavy workshop construction', 'assets/images/machinery/varsha-factory-pillar-panel.jpg', 1, 1, 48500)");
            }
        } catch (Exception $e) {}

        self::ensureRealMachineImages($pdo);
    }

    private static function ensureRealMachineImages(PDO $pdo): void {
        try {
            $realImages = [
                1 => 'assets/images/machinery/radial-drill-40-real.jpg',
                2 => 'assets/images/machinery/radial-drill-50-real.jpg',
                3 => 'assets/images/machinery/heavy-duty-radial-65-real.jpg',
                4 => 'assets/images/machinery/pillar-drill-25-real.jpg',
                5 => 'assets/images/machinery/pillar-drill-32-real.jpg',
                6 => 'assets/images/machinery/pillar-drill-40-real.jpg',
                7 => 'assets/images/machinery/bench-drill-20-real.jpg',
                8 => 'assets/images/machinery/tapping-drill-25-real.jpg',
                9 => 'assets/images/machinery/bench-drill-heavy-duty-23500.jpg',
                10 => 'assets/images/machinery/pillar-drill-25-heavy-73000.jpg',
                11 => 'assets/images/machinery/precision-drill-press-25000.jpg',
                12 => 'assets/images/machinery/varsha-factory-pillar-panel.jpg',
                101 => 'assets/images/machinery/part-spindle-quill.jpg',
                102 => 'assets/images/machinery/part-gear-set.jpg',
                103 => 'assets/images/machinery/part-box-table.jpg',
                104 => 'assets/images/machinery/part-induction-motor.jpg',
                105 => 'assets/images/machinery/part-drill-chuck.jpg',
                106 => 'assets/images/machinery/part-coolant-pump.jpg'
            ];
            foreach ($realImages as $id => $path) {
                $pdo->exec("UPDATE products SET image_url = '{$path}' WHERE id = {$id} AND (image_url LIKE '%.svg' OR image_url LIKE '%varsha-logo%' OR image_url IS NULL OR image_url = '')");
            }
        } catch (Exception $e) {
            // Ignore if products table not yet available
        }
    }
}
