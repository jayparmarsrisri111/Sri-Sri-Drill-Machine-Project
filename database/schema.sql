-- ======================================================
-- Shree Varsha Engineering Works - Database Schema
-- Precision Drill Machine Web Application
-- ======================================================

CREATE DATABASE IF NOT EXISTS `shree_varsha_drill_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `shree_varsha_drill_db`;

-- 1. Admin Users Table
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `role` VARCHAR(20) DEFAULT 'admin',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default administrator: admin / admin123
INSERT INTO `admin_users` (`username`, `password_hash`, `full_name`, `email`, `role`) VALUES
('admin', '$2y$12$B.vMBG436PXxG5iqIWaqK.yWXVI09tBmOYvCYXzxlQ8ZyiN0zxl22', 'Jitubhai Varsha (Admin)', 'jitubhaivarsh@gmail.com', 'admin');

-- 2. Categories Table
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT,
  `display_order` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `display_order`) VALUES
(1, 'Radial Drill Machines', 'radial-drill-machines', 'Heavy-duty geared and auto-feed radial drilling machinery for industrial workshops.', 1),
(2, 'Pillar Drill Machines', 'pillar-drill-machines', 'Precision upright column drill machines for accurate vertical hole cutting.', 2),
(3, 'Bench Drill Machines', 'bench-drill-machines', 'High speed compact bench top drilling machines for tool rooms and fabrication.', 3),
(4, 'Tapping & Special Purpose', 'tapping-special-purpose', 'Combined drill-tap machinery with automatic reverse spindle drives.', 4),
(5, 'Spindles & Quills (સ્પિન્ડલ અને ક્વિલ)', 'spindles-quills', 'Case-hardened 20MnCr5 ground Morse taper spindles and quill sleeves.', 5),
(6, 'Geared Head & Alloy Gears (ગીયર્સ અને હેડ)', 'geared-head-gears', 'Induction-hardened 8-speed and 12-speed transmission alloy gear assemblies.', 6),
(7, 'Cast Iron T-Slot Tables (ટી-સ્લોટ બોક્સ ટેબલ)', 'cast-iron-tables', 'Grade-25 heavy-duty machined and ground clamping box tables.', 7),
(8, 'Motors & Electrical Drives (મોટર્સ અને પેનલ)', 'motors-electricals', 'High-torque 3-phase flange induction motors and reverse starter switches.', 8),
(9, 'Precision Chucks & Sleeves (ડ્રિલ ચક અને સ્લીવ્ઝ)', 'chucks-sleeves', 'Keyless high-precision drill chucks and reduction Morse taper sleeves.', 9),
(10, 'Coolant Pumps & Lubrication Units (કૂલન્ટ સિસ્ટમ)', 'coolant-lubrication', 'Submerged continuous flood coolant pump assemblies and flexible metallic nozzles.', 10);

-- 3. Products / Drill Machines Table
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `model_number` VARCHAR(50) NOT NULL,
  `slug` VARCHAR(150) NOT NULL UNIQUE,
  `drilling_capacity` VARCHAR(100) NOT NULL,
  `spindle_travel` VARCHAR(100) DEFAULT NULL,
  `spindle_speeds` VARCHAR(100) DEFAULT NULL,
  `column_diameter` VARCHAR(100) DEFAULT NULL,
  `motor_power` VARCHAR(100) DEFAULT NULL,
  `working_table_size` VARCHAR(100) DEFAULT NULL,
  `drilling_radius` VARCHAR(100) DEFAULT NULL,
  `weight_approx` VARCHAR(100) DEFAULT NULL,
  `short_desc` TEXT,
  `full_desc` LONGTEXT,
  `key_features` TEXT,
  `image_url` VARCHAR(255) DEFAULT 'assets/images/machinery/radial-drill-40.svg',
  `price` DECIMAL(12,2) DEFAULT 0.00,
  `stock_quantity` INT DEFAULT 5,
  `is_featured` TINYINT(1) DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `products` (`id`, `category_id`, `name`, `model_number`, `slug`, `drilling_capacity`, `spindle_travel`, `spindle_speeds`, `column_diameter`, `motor_power`, `working_table_size`, `drilling_radius`, `weight_approx`, `short_desc`, `full_desc`, `key_features`, `image_url`, `is_featured`, `is_active`) VALUES
(1, 1, '40mm All-Geared Radial Drill Machine', 'SVEW-RD-40', '40mm-all-geared-radial-drill-machine', '40 mm (Steel) / 50 mm (C.I.)', '250 mm', '8 Speeds (50 - 1800 RPM)', '210 mm', '2.0 HP (Drill) + 1.0 HP (Arm Elevating)', '500 x 600 mm Box Table', '1050 mm Max / 350 mm Min', '1650 kg', 'Heavy duty all-geared head with heat treated alloy gears, auto feed mechanism, and precision ground spindle for continuous industrial production.', 'The SVEW-RD-40 is our flagship industrial radial drill machine engineered for precision engineering shops, heavy fabrication units, and automotive workshops. Constructed with Grade-25 high tensile casting with heavy internal ribbing, ensuring zero vibration and extreme drilling stability.', 'Hardened & ground alloy steel gears;Power operated arm elevating system;Automatic mechanical spindle feeds;Heavy rib box table with T-slots;Centralized lubrication pump;Emergency spindle brake', 'assets/images/machinery/radial-drill-40-real.jpg', 1, 1),

(2, 1, '50mm Heavy Duty Precision Radial Drill', 'SVEW-RD-50', '50mm-heavy-duty-precision-radial-drill', '50 mm (Steel) / 60 mm (C.I.)', '280 mm', '12 Speeds (35 - 1600 RPM)', '260 mm', '3.0 HP (Main) + 1.5 HP (Elevation)', '600 x 750 mm Box Table', '1350 mm Max / 400 mm Min', '2400 kg', 'High rigidity heavy-duty radial drill with 12-speed geared gearbox, motorized column locking, and deep boring precision.', 'The SVEW-RD-50 delivers immense torque and precision for heavy engineering, railway components, and pressure vessel manufacturing. Features an extra-wide column diameter with precision honed sleeve surfaces.', '12 Geared spindle speeds for large diameter hole saws;Hydro-mechanical column and arm clamping;Heavy duty oil bath transmission;Dual safety trip feed mechanism;Precision taper roller bearings', 'assets/images/machinery/radial-drill-50-real.jpg', 1, 1),

(3, 1, '65mm Extra Heavy Industrial Radial Drill', 'SVEW-RD-65', '65mm-extra-heavy-industrial-radial-drill', '65 mm (Steel) / 75 mm (C.I.)', '320 mm', '16 Speeds (25 - 1400 RPM)', '320 mm', '5.0 HP (Main) + 2.0 HP (Elevation)', '750 x 900 mm Heavy Box Table', '1600 mm Max / 450 mm Min', '3600 kg', 'Massive heavy capacity radial drilling machine designed for structural steel, shipyards, and heavy machinery fabricators.', 'Engineered for the most demanding hole drilling, reaming, and counterboring operations. The SVEW-RD-65 boasts reinforced arm cross-sections, vibration-damped foundation base, and hardened spindle nose (MT-5).', 'MT-5 Spindle Taper for large tooling;Massive 320mm thick ground column;Independent gear train for auto feeds;Integrated coolant tank with high flow pump;Work lamp and digital readout ready', 'assets/images/machinery/heavy-duty-radial-65-real.jpg', 1, 1),

(4, 2, '25mm Heavy Duty Pillar Drill Machine', 'SVEW-PD-25', '25mm-heavy-duty-pillar-drill-machine', '25 mm (Steel) / 32 mm (C.I.)', '160 mm', '8 Speeds (85 - 2400 RPM)', '92 mm', '1.0 HP / 0.75 kW (1440 RPM)', '350 x 350 mm Round/Square Table', '380 mm throat depth', '280 kg', 'Sturdy and reliable vertical pillar drill machine for tool rooms, educational institutes, and medium fabrication jobs.', 'The SVEW-PD-25 offers precision vertical alignment with ground column pipe, smooth rack-and-pinion table elevating gear, and balanced multi-V-belt drive for vibration-free quiet operations.', 'Ground seamless column pipe;360-degree swiveling and tilting worktable;Dynamically balanced pulley system;Heavy ribbed square base plate;Spindle quill with micrometer depth gauge', 'assets/images/machinery/pillar-drill-25-real.jpg', 1, 1),

(5, 2, '32mm Geared Column Pillar Drill Machine', 'SVEW-PD-32', '32mm-geared-column-pillar-drill-machine', '32 mm (Steel) / 38 mm (C.I.)', '190 mm', '8 Speeds (70 - 2100 RPM)', '115 mm', '1.5 HP / 1.1 kW', '420 x 420 mm Heavy Table', '420 mm throat depth', '460 kg', 'All-geared head upright pillar drill with mechanical power feed and integrated coolant trough.', 'Built for industrial fabrication shops requiring reliable drilling with minimum maintenance. Features an all-geared headstock running in oil bath with precision induction-hardened gears.', 'Hardened & ground gear head mechanism;Rack and pinion table elevation with worm drive;Mechanical depth stop for repetitive batch production;Heavy duty MT-3 spindle nose', 'assets/images/machinery/pillar-drill-32-real.jpg', 0, 1),

(6, 2, '40mm Extra Heavy Pillar Drilling Machine', 'SVEW-PD-40', '40mm-extra-heavy-pillar-drilling-machine', '40 mm (Steel) / 50 mm (C.I.)', '220 mm', '9 Speeds (55 - 1950 RPM)', '140 mm', '2.0 HP / 1.5 kW', '500 x 500 mm Rotatable Table', '480 mm throat depth', '720 kg', 'Super rigid heavy column drill designed for continuous production and heavy drilling loads.', 'The SVEW-PD-40 combines the compactness of a pillar drill with the power and stability of a radial machine. Ideal for large plate drilling, flange hole making, and reaming operations.', 'Heavy 140mm seamless solid wall column;Oil-bath gearbox with hardened alloy gears;Automatic spindle feed mechanism;Built-in heavy base coolant sump;Precision ground table surface', 'assets/images/machinery/pillar-drill-40-real.jpg', 0, 1),

(7, 3, '20mm High Speed Precision Bench Drill', 'SVEW-BD-20', '20mm-high-speed-precision-bench-drill', '20 mm (Steel) / 25 mm (C.I.)', '125 mm', '4/8 Speeds (150 - 3200 RPM)', '75 mm', '0.75 HP / 0.55 kW', '280 x 280 mm Precision Table', '260 mm throat depth', '140 kg', 'High-speed vibration-free bench drilling machine for tool rooms, die makers, and high accuracy component drilling.', 'Compact bench mounted drill machine engineered with micro-tolerance spindle assembly. Equipped with deep groove precision bearings and counterbalanced return spring for effortless sensitive drilling.', 'High speed spindle assembly up to 3200 RPM;Precision ground quill and spindle taper MT-2;Rigid cast iron bench base with mounting slots;Adjustable depth stop with millimeter scale', 'assets/images/machinery/bench-drill-20-real.jpg', 1, 1),

(8, 4, '25mm Auto-Reversing Drilling & Tapping Machine', 'SVEW-TP-25', '25mm-auto-reversing-drilling-tapping-machine', 'Drill 25mm / Tap M20 in Steel', '160 mm', '8 Speeds (90 - 1800 RPM)', '105 mm', '1.5 HP Dual Direction Motor', '380 x 380 mm Table', '360 mm throat depth', '380 kg', 'Dual purpose drilling and thread tapping machine with instant automatic spindle reverse upon reaching preset depth.', 'Features an intelligent micro-switch actuated auto-reverse mechanism. When tapping threads, the spindle automatically switches direction upon reaching depth, withdrawing the tap smoothly without thread damage.', 'Instant electrical/mechanical auto-reverse system;Dual foot pedal control for hands-free tapping;High torque low speed gearing for clean thread cutting;Built-in depth micrometer for repeat accuracy', 'assets/images/machinery/tapping-drill-25-real.jpg', 0, 1),
(101, 5, 'MT-4 Precision Alloy Spindle & Ground Quill Assembly', 'SVEW-PRT-SP4', 'mt-4-precision-alloy-spindle-quill-assembly', 'Fits 40mm & 50mm Radial Drills', '280 mm Travel', 'Tested up to 2500 RPM', 'N/A (Spindle)', 'N/A', 'Standard Bore', 'N/A', '18.5 kg', 'Induction-hardened 20MnCr5 alloy steel spindle with internal ground MT-4 taper and precision needle bearings.', 'Manufactured in-house at our Bhavnagar manufacturing plant with German CNC grinding standards. Heat treated to 58-62 HRC to ensure zero run-out and lifelong precision drilling without vibration under heavy torque.', 'Case-hardened 20MnCr5 steel;Internal MT-4 taper;Runout under 0.005mm;Pre-assembled with SKF/FAG bearings;Includes locknut & washer', 'assets/images/machinery/part-spindle-quill.jpg', 1, 1),
(102, 6, 'Complete 8-Speed Hardened Alloy Headstock Gear Set', 'SVEW-PRT-GR8', 'complete-8-speed-hardened-alloy-gear-set', 'Fits SVEW-RD-40 Radial Machine', 'N/A', '50 - 1800 RPM (8 Ratios)', 'N/A', 'Rated for 3 HP Torque', 'Gear Cluster', 'N/A', '24.0 kg', 'Set of 8 precision gear hobbed and ground spur/helical gears manufactured from EN-353 forged steel.', 'High endurance gear train for all-geared radial drilling heads. Every gear undergoes carburizing, quenching and tooth-flank profile grinding for whisper-quiet oil bath operations.', 'Forged EN-353 alloy steel;Tooth profile ground (DIN 6);Splined inner hubs;High load tooth contact;Direct replacement for Varsha machines', 'assets/images/machinery/part-gear-set.jpg', 1, 1),
(103, 7, 'Heavy Ribbed Cast Iron T-Slot Box Work Table (500x600mm)', 'SVEW-PRT-TBL50', 'heavy-ribbed-cast-iron-t-slot-box-table-500x600', 'Load Capacity 1500 kg', 'N/A', 'N/A', 'N/A', 'N/A', '500 x 600 x 400 mm', 'N/A', '175 kg', 'Solid Grade-25 cast iron heavy box table with precision planed and ground top and side T-slots for secure work clamping.', 'Seasoned gray cast iron box table with deep cross-ribbing to dampen heavy drilling shock waves. Ground flat within 0.02 mm across full surface with standard 18mm T-slots.', 'Grade-25 heavy casting;Ground top & side surfaces;3 Longitudinal T-slots (18mm);Heavy mounting bolt slots;Stress-relieved for zero distortion', 'assets/images/machinery/part-box-table.jpg', 0, 1),
(104, 8, '3.0 HP High-Torque 3-Phase Electric Induction Flange Motor', 'SVEW-PRT-MTR3', '3hp-high-torque-electric-induction-flange-motor', 'Main Drive Motor (415V / 50Hz)', 'N/A', '1440 RPM (Continuous Duty S1)', 'Flange B5 / B14', '3.0 HP (2.2 kW) Copper Wound', 'N/A', 'N/A', '32.0 kg', '100% pure electrolytic copper wound heavy duty drilling motor with Class-F insulation and IP55 protection.', 'Custom engineered industrial motor designed to withstand continuous drilling and cyclic stall torque without overheating. Dynamic balanced rotor ensures zero vibration transmitted to spindle head.', '100% Electrolytic copper winding;Cast iron body with cooling fins;IP-55 ingress protection;Dynamically balanced rotor;Certified for heavy duty radial machines', 'assets/images/machinery/part-induction-motor.jpg', 0, 1),
(105, 9, 'High Precision Keyless Drill Chuck (1-16mm) with MT-3/MT-4 Arbor', 'SVEW-PRT-CHK16', 'high-precision-keyless-drill-chuck-1-16mm', 'Clamping Range 1.0 - 16.0 mm', 'N/A', 'Rated up to 3500 RPM', 'N/A', 'N/A', 'MT-3 / MT-4 Arbor Included', 'N/A', '2.8 kg', 'Heavy-duty integrated self-tightening keyless drill chuck with hardened titanium-coated jaws for zero-slip bit grip.', 'Engineered for high-volume batch drilling. Self-tightening mechanism increases gripping force proportionally to drilling torque, preventing drill bits from slipping during aggressive feeding.', 'Self-tightening keyless design;Hardened jaws (HRC 60);Concentricity ≤ 0.04mm;Hardened Morse Taper arbor;Instant hand tightening', 'assets/images/machinery/part-drill-chuck.jpg', 1, 1),
(106, 10, 'High Flow Submerged Coolant Pump & Flexible Hose Kit', 'SVEW-PRT-CLP01', 'high-flow-submerged-coolant-pump-hose-kit', 'Flow Rate 45 Liters/min', 'N/A', '2800 RPM Pump Motor', 'Immersion Depth 120-180mm', '0.15 HP (110W) 415V', 'Includes 500mm Segment Hose', 'N/A', '5.2 kg', 'Self-priming centrifugal coolant pump assembly with metal magnetic base nozzle and valve for continuous flood lubrication.', 'Keeps drill bits and workpieces ice-cold during deep hole cutting. Stainless steel impeller resists abrasive swarf and cutting oil corrosion.', '45 L/min high flow delivery;Self-priming submerged design;Chemical & oil resistant seals;Adjustable segment coolant hose with shutoff tap;Fits standard base coolant tanks', 'assets/images/machinery/part-coolant-pump.jpg', 0, 1);

-- 4. Customer Inquiries Table
DROP TABLE IF EXISTS `inquiries`;
CREATE TABLE `inquiries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_name` VARCHAR(100) NOT NULL,
  `company_name` VARCHAR(100) DEFAULT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `machine_id` INT DEFAULT NULL,
  `machine_name` VARCHAR(150) DEFAULT NULL,
  `message` TEXT NOT NULL,
  `status` ENUM('new', 'contacted', 'quoted', 'closed') DEFAULT 'new',
  `admin_notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`machine_id`) REFERENCES `products`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `inquiries` (`customer_name`, `company_name`, `email`, `phone`, `machine_name`, `message`, `status`) VALUES
('Rajesh Patel', 'Patel Engineering Works', 'rajesh.patel@gmail.com', '+91 9898123456', '40mm All-Geared Radial Drill Machine', 'Please send price quotation and delivery timeline for 2 units of SVEW-RD-40.', 'new'),
('Amit Sharma', 'Precision Auto Components', 'amit.sharma@pac.in', '+91 9725098765', '50mm Heavy Duty Precision Radial Drill', 'Need technical catalog and power consumption details for heavy casting drilling.', 'contacted'),
('Kishorbhai Mehta', 'Mehta Machine Tools', 'kishor.mehta@yahoo.com', '+91 9825145678', '25mm Auto-Reversing Drilling & Tapping Machine', 'Interested in tapping machine for M16 to M20 thread cutting. Looking for dispatch to Ahmedabad.', 'new');

-- 5. Site Settings Table
DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE `site_settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(50) NOT NULL UNIQUE,
  `setting_value` TEXT NOT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('company_name', 'Shree Varsha Engineering Works'),
('tagline', 'Precision Drill Machine Manufacturers & Industrial Specialists'),
('phone_1', '+91 9824913555'),
('phone_2', '+91 9714611331'),
('email', 'jitubhaivarsh@gmail.com'),
('whatsapp', '919824913555'),
('address', 'Shed No. L/786, GIDC, Nr. Hadanagar, Bhavnagar - 36400003, Gujarat, India'),
('working_hours', 'Monday - Saturday: 8:30 AM - 7:30 PM (Sunday Closed)'),
('about_company', 'Shree Varsha Engineering Works is a premier manufacturer and exporter of heavy-duty Radial Drill Machines, Pillar Drilling Machines, Bench Drills, and Special Purpose Tapping Machinery. Based in the industrial hub of Bhavnagar, Gujarat, we have delivered over 1800+ high precision drill machines across India and globally with uncompromising build quality, hardened ground gears, and heavy cast iron stability.'),
('experience_years', '25+'),
('machines_built', '1800+'),
('happy_clients', '750+'),
('iso_certified', 'ISO 9001:2015 Certified Manufacturing Facility'),
('razorpay_key_id', 'rzp_test_SLvrGI5oOB3cDO'),
('razorpay_key_secret', 'fs37YWN1P1N8Gt5HkC8PVSUB'),
('gstin', '24AAACS1234F1Z5');

-- 6. Customer Orders & Razorpay Payments Table
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_number` VARCHAR(50) NOT NULL UNIQUE,
  `invoice_number` VARCHAR(50) NOT NULL UNIQUE,
  `product_id` INT DEFAULT NULL,
  `product_name` VARCHAR(150) NOT NULL,
  `model_number` VARCHAR(50) NOT NULL,
  `customer_name` VARCHAR(100) NOT NULL,
  `company_name` VARCHAR(150) DEFAULT NULL,
  `customer_email` VARCHAR(100) NOT NULL,
  `customer_phone` VARCHAR(20) NOT NULL,
  `customer_address` TEXT NOT NULL,
  `customer_city` VARCHAR(100) NOT NULL,
  `customer_pincode` VARCHAR(20) NOT NULL,
  `customer_gst` VARCHAR(30) DEFAULT NULL,
  `payment_type` ENUM('booking_advance', 'full_payment') DEFAULT 'booking_advance',
  `machine_price` DECIMAL(12,2) NOT NULL,
  `taxable_amount` DECIMAL(12,2) NOT NULL,
  `cgst_amount` DECIMAL(12,2) NOT NULL,
  `sgst_amount` DECIMAL(12,2) NOT NULL,
  `total_amount` DECIMAL(12,2) NOT NULL,
  `razorpay_order_id` VARCHAR(100) DEFAULT NULL,
  `razorpay_payment_id` VARCHAR(100) DEFAULT NULL,
  `razorpay_signature` VARCHAR(255) DEFAULT NULL,
  `payment_status` ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
  `order_status` ENUM('confirmed', 'manufacturing', 'dispatched', 'delivered') DEFAULT 'confirmed',
  `authorized_signatory` VARCHAR(100) DEFAULT 'Jay Parmar',
  `crypto_seal` VARCHAR(128) DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX (`payment_status`),
  INDEX (`order_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Customer Reviews & Feedback Table
DROP TABLE IF EXISTS `feedbacks`;
CREATE TABLE `feedbacks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_name` VARCHAR(150) NOT NULL,
  `company_name` VARCHAR(150) NULL,
  `phone` VARCHAR(50) NULL,
  `email` VARCHAR(150) NULL,
  `rating` INT NOT NULL DEFAULT 5,
  `product_name` VARCHAR(200) NULL,
  `feedback_title` VARCHAR(200) NULL,
  `comments` TEXT NOT NULL,
  `city` VARCHAR(100) NULL,
  `is_approved` TINYINT(1) DEFAULT 1,
  `is_featured` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
