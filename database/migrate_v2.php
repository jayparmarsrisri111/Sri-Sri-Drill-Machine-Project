<?php
/**
 * Database Migration v2
 * Shree Varsha Engineering Works - Varsha Brand
 * Adds Spare Parts, Feedback Table, Categories, and Enhanced Product Schema
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$pdo = Database::getConnection();
$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
echo "Running migration on [{$driver}]...\n";

// 1. Add `product_type` column to `products` if missing
try {
    if ($driver === 'sqlite') {
        $cols = $pdo->query("PRAGMA table_info(products)")->fetchAll(PDO::FETCH_ASSOC);
        $hasProductType = false;
        foreach ($cols as $c) {
            if ($c['name'] === 'product_type') {
                $hasProductType = true;
                break;
            }
        }
        if (!$hasProductType) {
            $pdo->exec("ALTER TABLE products ADD COLUMN product_type TEXT DEFAULT 'machine'");
            echo "Added `product_type` column to products table (SQLite).\n";
        }
    } else {
        $pdo->exec("ALTER TABLE products ADD COLUMN product_type VARCHAR(50) DEFAULT 'machine'");
        echo "Added `product_type` column to products table (MySQL).\n";
    }
} catch (Exception $e) {
    // Column might already exist
    echo "Note on product_type: " . $e->getMessage() . "\n";
}

// 2. Add `category_type` to `categories` if missing
try {
    if ($driver === 'sqlite') {
        $cols = $pdo->query("PRAGMA table_info(categories)")->fetchAll(PDO::FETCH_ASSOC);
        $hasCatType = false;
        foreach ($cols as $c) {
            if ($c['name'] === 'category_type') {
                $hasCatType = true;
                break;
            }
        }
        if (!$hasCatType) {
            $pdo->exec("ALTER TABLE categories ADD COLUMN category_type TEXT DEFAULT 'machine'");
            echo "Added `category_type` to categories (SQLite).\n";
        }
    } else {
        $pdo->exec("ALTER TABLE categories ADD COLUMN category_type VARCHAR(50) DEFAULT 'machine'");
        echo "Added `category_type` to categories (MySQL).\n";
    }
} catch (Exception $e) {
    echo "Note on category_type: " . $e->getMessage() . "\n";
}

// 3. Create `feedbacks` table
if ($driver === 'sqlite') {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS feedbacks (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            customer_name TEXT NOT NULL,
            company_name TEXT,
            phone TEXT,
            email TEXT,
            rating INTEGER NOT NULL DEFAULT 5,
            product_name TEXT,
            feedback_title TEXT,
            comments TEXT NOT NULL,
            city TEXT,
            is_approved INTEGER DEFAULT 1,
            is_featured INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
} else {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS feedbacks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            customer_name VARCHAR(150) NOT NULL,
            company_name VARCHAR(150) NULL,
            phone VARCHAR(50) NULL,
            email VARCHAR(150) NULL,
            rating INT NOT NULL DEFAULT 5,
            product_name VARCHAR(200) NULL,
            feedback_title VARCHAR(200) NULL,
            comments TEXT NOT NULL,
            city VARCHAR(100) NULL,
            is_approved TINYINT(1) DEFAULT 1,
            is_featured TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
}
echo "Feedbacks table verified.\n";

// 4. Insert or Update Categories for both Machines & Spare Parts
$newCategories = [
    // Machine Categories
    ['id' => 1, 'name' => 'Radial Drill Machines', 'slug' => 'radial-drill-machines', 'desc' => 'Heavy-duty geared and auto-feed radial drilling machinery from 40mm to 65mm capacity.', 'order' => 1, 'type' => 'machine'],
    ['id' => 2, 'name' => 'Pillar Drill Machines', 'slug' => 'pillar-drill-machines', 'desc' => 'Precision upright column drill machines from 25mm to 40mm capacity.', 'order' => 2, 'type' => 'machine'],
    ['id' => 3, 'name' => 'Bench Drill Machines', 'slug' => 'bench-drill-machines', 'desc' => 'High speed compact bench top drilling machines for tool rooms.', 'order' => 3, 'type' => 'machine'],
    ['id' => 4, 'name' => 'Tapping & Special Purpose', 'slug' => 'tapping-special-purpose', 'desc' => 'Combined drill-tap machinery with automatic reverse spindle drives.', 'order' => 4, 'type' => 'machine'],
    // Spare Parts Categories
    ['id' => 5, 'name' => 'Spindles & Quills (સ્પિન્ડલ અને ક્વિલ)', 'slug' => 'spindles-quills', 'desc' => 'Precision induction-hardened alloy steel spindles (MT-3, MT-4, MT-5) and ground quills.', 'order' => 5, 'type' => 'part'],
    ['id' => 6, 'name' => 'Geared Head & Alloy Gears (ગીયર્સ અને હેડ)', 'slug' => 'geared-head-gears', 'desc' => 'Case-hardened and teeth-ground alloy steel gears (EN-353) and speed transmission sets.', 'order' => 6, 'type' => 'part'],
    ['id' => 7, 'name' => 'Cast Iron T-Slot Tables (ટી-સ્લોટ બોક્સ ટેબલ)', 'slug' => 'cast-iron-tables', 'desc' => 'Grade-25 heavy ribbed cast iron precision ground box and tilting work tables.', 'order' => 7, 'type' => 'part'],
    ['id' => 8, 'name' => 'Motors & Electrical Drives (મોટર્સ અને પેનલ)', 'slug' => 'motors-electricals', 'desc' => 'High-torque 415V dual speed flange/foot motors, reversing switches, and control panels.', 'order' => 8, 'type' => 'part'],
    ['id' => 9, 'name' => 'Precision Chucks & Sleeves (ડ્રિલ ચક અને સ્લીવ્ઝ)', 'slug' => 'chucks-sleeves', 'desc' => 'Industrial keyless and keyed drill chucks, Morse taper reduction sleeves and arbors.', 'order' => 9, 'type' => 'part'],
    ['id' => 10, 'name' => 'Coolant Pumps & Lubrication Units (કૂલન્ટ સિસ્ટમ)', 'slug' => 'coolant-lubrication', 'desc' => 'Heavy flow centrifugal coolant pumps, centralized oil distribution units, and filters.', 'order' => 10, 'type' => 'part']
];

foreach ($newCategories as $cat) {
    try {
        $check = $pdo->prepare("SELECT id FROM categories WHERE id = :id");
        $check->execute([':id' => $cat['id']]);
        if ($check->fetch()) {
            $up = $pdo->prepare("UPDATE categories SET name = :n, slug = :s, description = :d, display_order = :o, category_type = :t WHERE id = :id");
            $up->execute([':n' => $cat['name'], ':s' => $cat['slug'], ':d' => $cat['desc'], ':o' => $cat['order'], ':t' => $cat['type'], ':id' => $cat['id']]);
        } else {
            $ins = $pdo->prepare("INSERT INTO categories (id, name, slug, description, display_order, category_type) VALUES (:id, :n, :s, :d, :o, :t)");
            $ins->execute([':id' => $cat['id'], ':n' => $cat['name'], ':s' => $cat['slug'], ':d' => $cat['desc'], ':o' => $cat['order'], ':t' => $cat['type']]);
        }
    } catch (Exception $e) {
        echo "Cat {$cat['id']} warning: " . $e->getMessage() . "\n";
    }
}
echo "Categories updated.\n";

// Update existing machines to have product_type = 'machine'
$pdo->exec("UPDATE products SET product_type = 'machine' WHERE product_type IS NULL OR product_type = ''");

// 5. Insert Genuine Spare Parts into `products`
$spareParts = [
    [
        'id' => 101,
        'category_id' => 5,
        'name' => 'MT-4 Precision Alloy Spindle & Ground Quill Assembly',
        'model_number' => 'SVEW-PRT-SP4',
        'slug' => 'mt-4-precision-alloy-spindle-quill-assembly',
        'drilling_capacity' => 'Fits 40mm & 50mm Radial Drills',
        'spindle_speeds' => 'Tested up to 2500 RPM',
        'spindle_travel' => '280 mm Travel',
        'column_diameter' => 'N/A (Spindle)',
        'motor_power' => 'N/A',
        'working_table_size' => 'Standard Bore',
        'drilling_radius' => 'N/A',
        'weight_approx' => '18.5 kg',
        'short_desc' => 'Induction-hardened 20MnCr5 alloy steel spindle with internal ground MT-4 taper and precision needle bearings.',
        'full_desc' => 'Manufactured in-house at our Rajkot facility with German CNC grinding standards. Heat treated to 58-62 HRC to ensure zero run-out and lifelong precision drilling without vibration under heavy torque.',
        'key_features' => 'Case-hardened 20MnCr5 steel;Internal MT-4 taper;Runout under 0.005mm;Pre-assembled with SKF/FAG bearings;Includes locknut & washer',
        'image_url' => 'assets/images/varsha-logo.png',
        'price' => 14500.00,
        'stock_quantity' => 12,
        'is_featured' => 1,
        'is_active' => 1,
        'product_type' => 'part'
    ],
    [
        'id' => 102,
        'category_id' => 6,
        'name' => 'Complete 8-Speed Hardened Alloy Headstock Gear Set',
        'model_number' => 'SVEW-PRT-GR8',
        'slug' => 'complete-8-speed-hardened-alloy-gear-set',
        'drilling_capacity' => 'Fits SVEW-RD-40 Radial Machine',
        'spindle_speeds' => '50 - 1800 RPM (8 Ratios)',
        'spindle_travel' => 'N/A',
        'column_diameter' => 'N/A',
        'motor_power' => 'Rated for 3 HP Torque',
        'working_table_size' => 'Gear Cluster',
        'drilling_radius' => 'N/A',
        'weight_approx' => '24.0 kg',
        'short_desc' => 'Set of 8 precision gear hobbed and ground spur/helical gears manufactured from EN-353 forged steel.',
        'full_desc' => 'High endurance gear train for all-geared radial drilling heads. Every gear undergoes carburizing, quenching and tooth-flank profile grinding for whisper-quiet oil bath operations.',
        'key_features' => 'Forged EN-353 alloy steel;Tooth profile ground (DIN 6);Splined inner hubs;High load tooth contact;Direct replacement for Varsha machines',
        'image_url' => 'assets/images/varsha-logo.png',
        'price' => 22000.00,
        'stock_quantity' => 8,
        'is_featured' => 1,
        'is_active' => 1,
        'product_type' => 'part'
    ],
    [
        'id' => 103,
        'category_id' => 7,
        'name' => 'Heavy Ribbed Cast Iron T-Slot Box Work Table (500x600mm)',
        'model_number' => 'SVEW-PRT-TBL50',
        'slug' => 'heavy-ribbed-cast-iron-t-slot-box-table-500x600',
        'drilling_capacity' => 'Load Capacity 1500 kg',
        'spindle_speeds' => 'N/A',
        'spindle_travel' => 'N/A',
        'column_diameter' => 'N/A',
        'motor_power' => 'N/A',
        'working_table_size' => '500 x 600 x 400 mm',
        'drilling_radius' => 'N/A',
        'weight_approx' => '175 kg',
        'short_desc' => 'Solid Grade-25 cast iron heavy box table with precision planed and ground top and side T-slots for secure work clamping.',
        'full_desc' => 'Seasoned gray cast iron box table with deep cross-ribbing to dampen heavy drilling shock waves. Ground flat within 0.02 mm across full surface with standard 18mm T-slots.',
        'key_features' => 'Grade-25 heavy casting;Ground top & side surfaces;3 Longitudinal T-slots (18mm);Heavy mounting bolt slots;Stress-relieved for zero distortion',
        'image_url' => 'assets/images/machinery/radial-drill-real.jpg',
        'price' => 18500.00,
        'stock_quantity' => 6,
        'is_featured' => 0,
        'is_active' => 1,
        'product_type' => 'part'
    ],
    [
        'id' => 104,
        'category_id' => 8,
        'name' => '3.0 HP High-Torque 3-Phase Electric Induction Flange Motor',
        'model_number' => 'SVEW-PRT-MTR3',
        'slug' => '3hp-high-torque-electric-induction-flange-motor',
        'drilling_capacity' => 'Main Drive Motor (415V / 50Hz)',
        'spindle_speeds' => '1440 RPM (Continuous Duty S1)',
        'spindle_travel' => 'N/A',
        'column_diameter' => 'Flange B5 / B14',
        'motor_power' => '3.0 HP (2.2 kW) Copper Wound',
        'working_table_size' => 'N/A',
        'drilling_radius' => 'N/A',
        'weight_approx' => '32.0 kg',
        'short_desc' => '100% pure electrolytic copper wound heavy duty drilling motor with Class-F insulation and IP55 protection.',
        'full_desc' => 'Custom engineered industrial motor designed to withstand continuous drilling and cyclic stall torque without overheating. Dynamic balanced rotor ensures zero vibration transmitted to spindle head.',
        'key_features' => '100% Electrolytic copper winding;Cast iron body with cooling fins;IP-55 ingress protection;Dynamically balanced rotor;Certified for heavy duty radial machines',
        'image_url' => 'assets/images/varsha-logo.png',
        'price' => 12500.00,
        'stock_quantity' => 10,
        'is_featured' => 0,
        'is_active' => 1,
        'product_type' => 'part'
    ],
    [
        'id' => 105,
        'category_id' => 9,
        'name' => 'High Precision Keyless Drill Chuck (1-16mm) with MT-3/MT-4 Arbor',
        'model_number' => 'SVEW-PRT-CHK16',
        'slug' => 'high-precision-keyless-drill-chuck-1-16mm',
        'drilling_capacity' => 'Clamping Range 1.0 - 16.0 mm',
        'spindle_speeds' => 'Rated up to 3500 RPM',
        'spindle_travel' => 'N/A',
        'column_diameter' => 'N/A',
        'motor_power' => 'N/A',
        'working_table_size' => 'MT-3 / MT-4 Arbor Included',
        'drilling_radius' => 'N/A',
        'weight_approx' => '2.8 kg',
        'short_desc' => 'Heavy-duty integrated self-tightening keyless drill chuck with hardened titanium-coated jaws for zero-slip bit grip.',
        'full_desc' => 'Engineered for high-volume batch drilling. Self-tightening mechanism increases gripping force proportionally to drilling torque, preventing drill bits from slipping during aggressive feeding.',
        'key_features' => 'Self-tightening keyless design;Hardened jaws (HRC 60);Concentricity ≤ 0.04mm;Hardened Morse Taper arbor;Instant hand tightening',
        'image_url' => 'assets/images/varsha-logo.png',
        'price' => 3800.00,
        'stock_quantity' => 25,
        'is_featured' => 1,
        'is_active' => 1,
        'product_type' => 'part'
    ],
    [
        'id' => 106,
        'category_id' => 10,
        'name' => 'High Flow Submerged Coolant Pump & Flexible Hose Kit',
        'model_number' => 'SVEW-PRT-CLP01',
        'slug' => 'high-flow-submerged-coolant-pump-hose-kit',
        'drilling_capacity' => 'Flow Rate 45 Liters/min',
        'spindle_speeds' => '2800 RPM Pump Motor',
        'spindle_travel' => 'N/A',
        'column_diameter' => 'Immersion Depth 120-180mm',
        'motor_power' => '0.15 HP (110W) 415V',
        'working_table_size' => 'Includes 500mm Segment Hose',
        'drilling_radius' => 'N/A',
        'weight_approx' => '5.2 kg',
        'short_desc' => 'Self-priming centrifugal coolant pump assembly with metal magnetic base nozzle and valve for continuous flood lubrication.',
        'full_desc' => 'Keeps drill bits and workpieces ice-cold during deep hole cutting. Stainless steel impeller resists abrasive swarf and cutting oil corrosion.',
        'key_features' => '45 L/min high flow delivery;Self-priming submerged design;Chemical & oil resistant seals;Adjustable segment coolant hose with shutoff tap;Fits standard base coolant tanks',
        'image_url' => 'assets/images/varsha-logo.png',
        'price' => 4200.00,
        'stock_quantity' => 15,
        'is_featured' => 0,
        'is_active' => 1,
        'product_type' => 'part'
    ]
];

foreach ($spareParts as $sp) {
    try {
        $check = $pdo->prepare("SELECT id FROM products WHERE id = :id");
        $check->execute([':id' => $sp['id']]);
        if ($check->fetch()) {
            $up = $pdo->prepare("
                UPDATE products SET 
                    category_id = :cid, name = :n, model_number = :m, slug = :s,
                    drilling_capacity = :dc, spindle_travel = :st, spindle_speeds = :ss,
                    column_diameter = :cd, motor_power = :mp, working_table_size = :wts,
                    drilling_radius = :dr, weight_approx = :wa, short_desc = :sd,
                    full_desc = :fd, key_features = :kf, image_url = :img, price = :pr,
                    stock_quantity = :sq, is_featured = :feat, is_active = :act, product_type = :pt
                WHERE id = :id
            ");
            $params = [
                ':cid' => $sp['category_id'], ':n' => $sp['name'], ':m' => $sp['model_number'], ':s' => $sp['slug'],
                ':dc' => $sp['drilling_capacity'], ':st' => $sp['spindle_travel'], ':ss' => $sp['spindle_speeds'],
                ':cd' => $sp['column_diameter'], ':mp' => $sp['motor_power'], ':wts' => $sp['working_table_size'],
                ':dr' => $sp['drilling_radius'], ':wa' => $sp['weight_approx'], ':sd' => $sp['short_desc'],
                ':fd' => $sp['full_desc'], ':kf' => $sp['key_features'], ':img' => $sp['image_url'], ':pr' => $sp['price'],
                ':sq' => $sp['stock_quantity'], ':feat' => $sp['is_featured'], ':act' => $sp['is_active'], ':pt' => $sp['product_type'],
                ':id' => $sp['id']
            ];
            $up->execute($params);
        } else {
            $ins = $pdo->prepare("
                INSERT INTO products (
                    id, category_id, name, model_number, slug, drilling_capacity, spindle_travel, spindle_speeds,
                    column_diameter, motor_power, working_table_size, drilling_radius, weight_approx, short_desc,
                    full_desc, key_features, image_url, price, stock_quantity, is_featured, is_active, product_type
                ) VALUES (
                    :id, :cid, :n, :m, :s, :dc, :st, :ss, :cd, :mp, :wts, :dr, :wa, :sd, :fd, :kf, :img, :pr, :sq, :feat, :act, :pt
                )
            ");
            $params = [
                ':id' => $sp['id'], ':cid' => $sp['category_id'], ':n' => $sp['name'], ':m' => $sp['model_number'], ':s' => $sp['slug'],
                ':dc' => $sp['drilling_capacity'], ':st' => $sp['spindle_travel'], ':ss' => $sp['spindle_speeds'],
                ':cd' => $sp['column_diameter'], ':mp' => $sp['motor_power'], ':wts' => $sp['working_table_size'],
                ':dr' => $sp['drilling_radius'], ':wa' => $sp['weight_approx'], ':sd' => $sp['short_desc'],
                ':fd' => $sp['full_desc'], ':kf' => $sp['key_features'], ':img' => $sp['image_url'], ':pr' => $sp['price'],
                ':sq' => $sp['stock_quantity'], ':feat' => $sp['is_featured'], ':act' => $sp['is_active'], ':pt' => $sp['product_type']
            ];
            $ins->execute($params);
        }
    } catch (Exception $e) {
        echo "Part {$sp['id']} error: " . $e->getMessage() . "\n";
    }
}
echo "Spare parts seeded.\n";

// 6. Seed Initial Customer Feedbacks
$initialFeedbacks = [
    [
        'name' => 'Kishorbhai Parmar',
        'company' => 'Parmar Precision Tool Works',
        'phone' => '+91 98251 12345',
        'email' => 'kishor.parmar@gmail.com',
        'rating' => 5,
        'product' => '40mm All-Geared Radial Drill (SVEW-RD-40)',
        'title' => 'Extraordinary drilling accuracy and zero vibration!',
        'comments' => 'અમે છેલ્લા 6 મહિનાથી વાર્ષા બ્રાન્ડનું 40mm રેડિયલ ડ્રિલ મશીન વાપરીએ છીએ. કાસ્ટિંગ અને ગીયર્સ ખૂબ જ મજબૂત છે અને 40mm ડ્રિલિંગમાં કોઈ વાઇબ્રેશન નથી આવતું. જીતુભાઈ અને જયભાઈની સર્વિસ પણ ઉત્તમ છે.',
        'city' => 'Rajkot, Gujarat'
    ],
    [
        'name' => 'Mukeshbhai Patel',
        'company' => 'Patel Heavy Fabrication & Valves',
        'phone' => '+91 98982 34567',
        'email' => 'mukesh.patel@patelfab.com',
        'rating' => 5,
        'product' => '50mm Heavy Duty Radial Drill (SVEW-RD-50)',
        'title' => 'Best heavy duty radial machine in Rajkot engineering market',
        'comments' => '50mm SVEW-RD-50 મશીન અમારા વાલ્વ બોડી પ્રોડક્શન માટે ખૂબ જ સચોટ પરિણામ આપે છે. પાવર એલિવેટિંગ અને ઓટો ફીડ મિકેનિઝમ ખૂબ જ સ્મૂધ ચાલે છે. સંપૂર્ણ પૈસા વસૂલ મશીનરી છે.',
        'city' => 'Ahmedabad, Gujarat'
    ],
    [
        'name' => 'Rajeev Kulkarni',
        'company' => 'Kulkarni Auto Press & Dies',
        'phone' => '+91 94220 56789',
        'email' => 'r.kulkarni@autopress.co.in',
        'rating' => 5,
        'product' => '32mm Geared Column Pillar Drill (SVEW-PD-32)',
        'title' => 'Reliable and durable pillar machine for tool room',
        'comments' => 'We ordered SVEW-PD-32 for our Pune tool room. The hardened gears and table rack pinion work flawlessly. Online booking advance was fast and invoice was received immediately with Jay Parmar signature and Varsha Brand seal.',
        'city' => 'Pune, Maharashtra'
    ]
];

foreach ($initialFeedbacks as $fb) {
    try {
        $check = $pdo->prepare("SELECT id FROM feedbacks WHERE customer_name = :n AND comments = :c");
        $check->execute([':n' => $fb['name'], ':c' => $fb['comments']]);
        if (!$check->fetch()) {
            $ins = $pdo->prepare("
                INSERT INTO feedbacks (customer_name, company_name, phone, email, rating, product_name, feedback_title, comments, city, is_approved, is_featured)
                VALUES (:n, :cmp, :ph, :em, :rt, :p, :t, :c, :ct, 1, 1)
            ");
            $ins->execute([
                ':n' => $fb['name'], ':cmp' => $fb['company'], ':ph' => $fb['phone'], ':em' => $fb['email'],
                ':rt' => $fb['rating'], ':p' => $fb['product'], ':t' => $fb['title'], ':c' => $fb['comments'],
                ':ct' => $fb['city']
            ]);
        }
    } catch (Exception $e) {
        echo "Feedback error: " . $e->getMessage() . "\n";
    }
}
echo "Feedbacks seeded successfully.\n";

echo "MIGRATION V2 COMPLETED SUCCESSFULLY!\n";
