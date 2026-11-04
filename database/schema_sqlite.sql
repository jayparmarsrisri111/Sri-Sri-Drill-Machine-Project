-- SQLite Compatible Schema for Shree Varsha Engineering Works
CREATE TABLE IF NOT EXISTS admin_users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username TEXT NOT NULL UNIQUE,
  password_hash TEXT NOT NULL,
  full_name TEXT NOT NULL,
  email TEXT NOT NULL,
  role TEXT DEFAULT 'admin',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

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
);

INSERT OR IGNORE INTO admin_users (id, username, password_hash, full_name, email, role) VALUES
(1, 'admin', '$2y$12$B.vMBG436PXxG5iqIWaqK.yWXVI09tBmOYvCYXzxlQ8ZyiN0zxl22', 'Jitubhai Varsha (Admin)', 'jitubhaivarsh@gmail.com', 'admin');

CREATE TABLE IF NOT EXISTS categories (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  slug TEXT NOT NULL UNIQUE,
  description TEXT,
  display_order INTEGER DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT OR IGNORE INTO categories (id, name, slug, description, display_order) VALUES
(1, 'Radial Drill Machines', 'radial-drill-machines', 'Heavy-duty geared and auto-feed radial drilling machinery for industrial workshops.', 1),
(2, 'Pillar Drill Machines', 'pillar-drill-machines', 'Precision upright column drill machines for accurate vertical hole cutting.', 2),
(3, 'Bench Drill Machines', 'bench-drill-machines', 'High speed compact bench top drilling machines for tool rooms and fabrication.', 3),
(4, 'Tapping & Special Purpose', 'tapping-special-purpose', 'Combined drill-tap machinery with automatic reverse spindle drives.', 4);

CREATE TABLE IF NOT EXISTS products (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  category_id INTEGER NOT NULL,
  name TEXT NOT NULL,
  model_number TEXT NOT NULL,
  slug TEXT NOT NULL UNIQUE,
  drilling_capacity TEXT NOT NULL,
  spindle_travel TEXT,
  spindle_speeds TEXT,
  column_diameter TEXT,
  motor_power TEXT,
  working_table_size TEXT,
  drilling_radius TEXT,
  weight_approx TEXT,
  short_desc TEXT,
  full_desc TEXT,
  key_features TEXT,
  image_url TEXT DEFAULT 'assets/images/machinery/radial-drill-40.svg',
  price REAL DEFAULT 0,
  stock_quantity INTEGER DEFAULT 5,
  is_featured INTEGER DEFAULT 0,
  is_active INTEGER DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

INSERT OR IGNORE INTO products (id, category_id, name, model_number, slug, drilling_capacity, spindle_travel, spindle_speeds, column_diameter, motor_power, working_table_size, drilling_radius, weight_approx, short_desc, full_desc, key_features, image_url, is_featured, is_active) VALUES
(1, 1, '40mm All-Geared Radial Drill Machine', 'SVEW-RD-40', '40mm-all-geared-radial-drill-machine', '40 mm (Steel) / 50 mm (C.I.)', '250 mm', '8 Speeds (50 - 1800 RPM)', '210 mm', '2.0 HP (Drill) + 1.0 HP (Arm Elevating)', '500 x 600 mm Box Table', '1050 mm Max / 350 mm Min', '1650 kg', 'Heavy duty all-geared head with heat treated alloy gears, auto feed mechanism, and precision ground spindle for continuous industrial production.', 'The SVEW-RD-40 is our flagship industrial radial drill machine engineered for precision engineering shops, heavy fabrication units, and automotive workshops. Constructed with Grade-25 high tensile casting with heavy internal ribbing, ensuring zero vibration and extreme drilling stability.', 'Hardened & ground alloy steel gears;Power operated arm elevating system;Automatic mechanical spindle feeds;Heavy rib box table with T-slots;Centralized lubrication pump;Emergency spindle brake', 'assets/images/machinery/radial-drill-40-real.jpg', 1, 1),

(2, 1, '50mm Heavy Duty Precision Radial Drill', 'SVEW-RD-50', '50mm-heavy-duty-precision-radial-drill', '50 mm (Steel) / 60 mm (C.I.)', '280 mm', '12 Speeds (35 - 1600 RPM)', '260 mm', '3.0 HP (Main) + 1.5 HP (Elevation)', '600 x 750 mm Box Table', '1350 mm Max / 400 mm Min', '2400 kg', 'High rigidity heavy-duty radial drill with 12-speed geared gearbox, motorized column locking, and deep boring precision.', 'The SVEW-RD-50 delivers immense torque and precision for heavy engineering, railway components, and pressure vessel manufacturing. Features an extra-wide column diameter with precision honed sleeve surfaces.', '12 Geared spindle speeds for large diameter hole saws;Hydro-mechanical column and arm clamping;Heavy duty oil bath transmission;Dual safety trip feed mechanism;Precision taper roller bearings', 'assets/images/machinery/radial-drill-50-real.jpg', 1, 1),

(3, 1, '65mm Extra Heavy Industrial Radial Drill', 'SVEW-RD-65', '65mm-extra-heavy-industrial-radial-drill', '65 mm (Steel) / 75 mm (C.I.)', '320 mm', '16 Speeds (25 - 1400 RPM)', '320 mm', '5.0 HP (Main) + 2.0 HP (Elevation)', '750 x 900 mm Heavy Box Table', '1600 mm Max / 450 mm Min', '3600 kg', 'Massive heavy capacity radial drilling machine designed for structural steel, shipyards, and heavy machinery fabricators.', 'Engineered for the most demanding hole drilling, reaming, and counterboring operations. The SVEW-RD-65 boasts reinforced arm cross-sections, vibration-damped foundation base, and hardened spindle nose (MT-5).', 'MT-5 Spindle Taper for large tooling;Massive 320mm thick ground column;Independent gear train for auto feeds;Integrated coolant tank with high flow pump;Work lamp and digital readout ready', 'assets/images/machinery/heavy-duty-radial-65-real.jpg', 1, 1),

(4, 2, '25mm Heavy Duty Pillar Drill Machine', 'SVEW-PD-25', '25mm-heavy-duty-pillar-drill-machine', '25 mm (Steel) / 32 mm (C.I.)', '160 mm', '8 Speeds (85 - 2400 RPM)', '92 mm', '1.0 HP / 0.75 kW (1440 RPM)', '350 x 350 mm Round/Square Table', '380 mm throat depth', '280 kg', 'Sturdy and reliable vertical pillar drill machine for tool rooms, educational institutes, and medium fabrication jobs.', 'The SVEW-PD-25 offers precision vertical alignment with ground column pipe, smooth rack-and-pinion table elevating gear, and balanced multi-V-belt drive for vibration-free quiet operations.', 'Ground seamless column pipe;360-degree swiveling and tilting worktable;Dynamically balanced pulley system;Heavy ribbed square base plate;Spindle quill with micrometer depth gauge', 'assets/images/machinery/pillar-drill-25-real.jpg', 1, 1),

(5, 2, '32mm Geared Column Pillar Drill Machine', 'SVEW-PD-32', '32mm-geared-column-pillar-drill-machine', '32 mm (Steel) / 38 mm (C.I.)', '190 mm', '8 Speeds (70 - 2100 RPM)', '115 mm', '1.5 HP / 1.1 kW', '420 x 420 mm Heavy Table', '420 mm throat depth', '460 kg', 'All-geared head upright pillar drill with mechanical power feed and integrated coolant trough.', 'Built for industrial fabrication shops requiring reliable drilling with minimum maintenance. Features an all-geared headstock running in oil bath with precision induction-hardened gears.', 'Hardened & ground gear head mechanism;Rack and pinion table elevation with worm drive;Mechanical depth stop for repetitive batch production;Heavy duty MT-3 spindle nose', 'assets/images/machinery/pillar-drill-32-real.jpg', 0, 1),

(6, 2, '40mm Extra Heavy Pillar Drilling Machine', 'SVEW-PD-40', '40mm-extra-heavy-pillar-drilling-machine', '40 mm (Steel) / 50 mm (C.I.)', '220 mm', '9 Speeds (55 - 1950 RPM)', '140 mm', '2.0 HP / 1.5 kW', '500 x 500 mm Rotatable Table', '480 mm throat depth', '720 kg', 'Super rigid heavy column drill designed for continuous production and heavy drilling loads.', 'The SVEW-PD-40 combines the compactness of a pillar drill with the power and stability of a radial machine. Ideal for large plate drilling, flange hole making, and reaming operations.', 'Heavy 140mm seamless solid wall column;Oil-bath gearbox with hardened alloy gears;Automatic spindle feed mechanism;Built-in heavy base coolant sump;Precision ground table surface', 'assets/images/machinery/pillar-drill-40-real.jpg', 0, 1),

(7, 3, '20mm High Speed Precision Bench Drill', 'SVEW-BD-20', '20mm-high-speed-precision-bench-drill', '20 mm (Steel) / 25 mm (C.I.)', '125 mm', '4/8 Speeds (150 - 3200 RPM)', '75 mm', '0.75 HP / 0.55 kW', '280 x 280 mm Precision Table', '260 mm throat depth', '140 kg', 'High-speed vibration-free bench drilling machine for tool rooms, die makers, and high accuracy component drilling.', 'Compact bench mounted drill machine engineered with micro-tolerance spindle assembly. Equipped with deep groove precision bearings and counterbalanced return spring for effortless sensitive drilling.', 'High speed spindle assembly up to 3200 RPM;Precision ground quill and spindle taper MT-2;Rigid cast iron bench base with mounting slots;Adjustable depth stop with millimeter scale', 'assets/images/machinery/bench-drill-20-real.jpg', 1, 1),

(8, 4, '25mm Auto-Reversing Drilling & Tapping Machine', 'SVEW-TP-25', '25mm-auto-reversing-drilling-tapping-machine', 'Drill 25mm / Tap M20 in Steel', '160 mm', '8 Speeds (90 - 1800 RPM)', '105 mm', '1.5 HP Dual Direction Motor', '380 x 380 mm Table', '360 mm throat depth', '380 kg', 'Dual purpose drilling and thread tapping machine with instant automatic spindle reverse upon reaching preset depth.', 'Features an intelligent micro-switch actuated auto-reverse mechanism. When tapping threads, the spindle automatically switches direction upon reaching depth, withdrawing the tap smoothly without thread damage.', 'Instant electrical/mechanical auto-reverse system;Dual foot pedal control for hands-free tapping;High torque low speed gearing for clean thread cutting;Built-in depth micrometer for repeat accuracy', 'assets/images/machinery/tapping-drill-25-real.jpg', 0, 1);

CREATE TABLE IF NOT EXISTS inquiries (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  customer_name TEXT NOT NULL,
  company_name TEXT,
  email TEXT NOT NULL,
  phone TEXT NOT NULL,
  machine_id INTEGER,
  machine_name TEXT,
  message TEXT NOT NULL,
  status TEXT DEFAULT 'new',
  admin_notes TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (machine_id) REFERENCES products(id)
);

INSERT OR IGNORE INTO inquiries (id, customer_name, company_name, email, phone, machine_name, message, status) VALUES
(1, 'Rajesh Patel', 'Patel Engineering Works', 'rajesh.patel@gmail.com', '+91 9898123456', '40mm All-Geared Radial Drill Machine', 'Please send price quotation and delivery timeline for 2 units of SVEW-RD-40.', 'new'),
(2, 'Amit Sharma', 'Precision Auto Components', 'amit.sharma@pac.in', '+91 9725098765', '50mm Heavy Duty Precision Radial Drill', 'Need technical catalog and power consumption details for heavy casting drilling.', 'contacted'),
(3, 'Kishorbhai Mehta', 'Mehta Machine Tools', 'kishor.mehta@yahoo.com', '+91 9825145678', '25mm Auto-Reversing Drilling & Tapping Machine', 'Interested in tapping machine for M16 to M20 thread cutting. Looking for dispatch to Ahmedabad.', 'new');

CREATE TABLE IF NOT EXISTS site_settings (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  setting_key TEXT NOT NULL UNIQUE,
  setting_value TEXT NOT NULL,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT OR IGNORE INTO site_settings (id, setting_key, setting_value) VALUES
(1, 'company_name', 'Shree Varsha Engineering Works'),
(2, 'tagline', 'Precision Drill Machine Manufacturers & Industrial Specialists'),
(3, 'phone_1', '+91 9824913555'),
(4, 'phone_2', '+91 9714611331'),
(5, 'email', 'jitubhaivarsh@gmail.com'),
(6, 'whatsapp', '919824913555'),
(7, 'address', 'Plot No. 42, G.I.D.C. Industrial Estate, Shapar - Aji Industrial Zone, Rajkot - 360003, Gujarat, India'),
(8, 'working_hours', 'Monday - Saturday: 8:30 AM - 7:30 PM (Sunday Closed)'),
(9, 'about_company', 'Shree Varsha Engineering Works is a premier manufacturer and exporter of heavy-duty Radial Drill Machines, Pillar Drilling Machines, Bench Drills, and Special Purpose Tapping Machinery. Based in the industrial hub of Rajkot, Gujarat, we have delivered over 1800+ high precision drill machines across India and globally with uncompromising build quality, hardened ground gears, and heavy cast iron stability.'),
(10, 'experience_years', '25+'),
(11, 'machines_built', '1800+'),
(12, 'happy_clients', '750+'),
(13, 'iso_certified', 'ISO 9001:2015 Certified Manufacturing Facility'),
(14, 'razorpay_key_id', 'rzp_test_SLvrGI5oOB3cDO'),
(15, 'razorpay_key_secret', 'fs37YWN1P1N8Gt5HkC8PVSUB'),
(16, 'gstin', '24AAACS1234F1Z5');

CREATE TABLE IF NOT EXISTS orders (
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
  order_status TEXT DEFAULT 'confirmed',
  authorized_signatory TEXT DEFAULT 'Jay Parmar',
  crypto_seal TEXT DEFAULT NULL,
  notes TEXT DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

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
);

