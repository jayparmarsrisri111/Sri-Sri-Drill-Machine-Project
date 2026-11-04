# Shree Varsha Engineering Works - Industrial Drill Machine Web Application

A full-featured, 100% responsive, high-performance web application designed for **Shree Varsha Engineering Works** (Drill Machine Manufacturers, Rajkot, Gujarat).

Designed with a high-tech industrial aesthetic inspired by modern engineering standards (`Space Grotesk` + `Inter`, dark cyber slate theme `#060911`, glassmorphism, electric cyan accents, and precision SVG vector machinery illustrations).

---

## 🏢 Company Contact Information
- **Company Name:** Shree Varsha Engineering Works (Dynamic from DB)
- **Proprietor / Key Contact:** Jitubhai Varsha
- **Primary Phone:** `+91 9824913555`
- **Secondary Phone:** `+91 9714611331`
- **Official Email:** `jitubhaivarsh@gmail.com`
- **WhatsApp:** `+91 9824913555`
- **Factory Address:** Plot No. 42, G.I.D.C. Industrial Estate, Shapar - Aji Industrial Zone, Rajkot - 360003, Gujarat, India

---

## ⚡ Key Highlights & Features

### 1. 100% Fully Responsive Design (Mobile, Tablet, Desktop, 4K)
- **Fluid Layout:** Flawlessly adapts from 320px mobile screens to large ultra-wide monitors.
- **Mobile Navigation Drawer:** Smooth slide-down menu with auto-close on link tap, touch backdrop, and integrated quote action buttons.
- **Touch-Friendly Controls:** 16px form inputs to prevent mobile browser auto-zoom, easy-to-tap buttons, and horizontally swipeable category filter tabs.
- **Responsive 3D Canvas:** Integrated `ResizeObserver` automatically recalibrates the 3D scene when rotating between mobile portrait and landscape.

### 2. 100% Dynamic Engine (Database-Driven)
- **Centralized Settings:** Any phone number, email, address, or working hours changed in the Admin Panel immediately updates across the entire site (Navbar, Footer, Contact Page, WhatsApp links) in real-time.
- **Live Search & Filter:** Instant real-time search on `machines.php` without page reloads.
- **Dynamic Machinery Catalog:** Model specifications, images, and category filtering dynamically queried from database.

### 3. 🌟 6D Interactive Machine Digital Twin (`simulator-6d.php`)
- **6-DOF Control:** Full touch and mouse support for Pitch, Yaw, Roll, Pan, and Zoom.
- **Kinematic Machining Motions:**
  - 360° Arm Swivel around column
  - Motorized Arm Height elevation up/down
  - Headstock traverse across radial arm
  - Spindle Quill downfeed into workpiece
- **X-Ray Gearbox Mode:** Transparent casing reveals 5 internal alloy steel gears rotating in real-time.
- **Exploded 3D View:** Smooth 0-100% slider disassembles all sub-assemblies in 3D space.
- **Dynamic Cutting Sparks:** Incandescent particle sparks emitted when drill bit contacts workpiece.
- **Synthesized Web Audio API:** Browser-synthesized electric motor drone and high-frequency metal cutting resonance without downloading audio files!
- **Live Telemetry HUD:** Spindle Tachometer (RPM), Torque (Nm), Motor Load %, Vibration FFT (mm/s), Cutting Depth (mm).

### 4. 🧠 World-First SVEW AI-SmartDrill™ Physics Optimizer
- **Metallurgical Intelligence:** Calculates optimal Spindle RPM ($N = \frac{1000 \cdot V_c}{\pi \cdot D}$), Feed Rate, Penetration Speed, Cutting Torque, Motor Power, and Cycle Time for 7 industrial materials.
- **Automatic Machine Matcher:** Recommends the exact Shree Varsha drill model with Torque Safety Reserve %.
- **1-Click Sync to 6D Twin:** Sends the AI parameters directly into the 3D viewer to run the exact simulation live.
- **Printable Production Job Card:** Generates production sheets for shop floor operators.

### 5. 🛡️ Enterprise Security Suite
- **CSRF Protection:** Cryptographic 32-byte tokens on all forms.
- **Intelligent Rate Limiter:** Protects against quote spam and brute-force login attacks (5 attempts / 5 mins).
- **Session Hardening:** `session_regenerate_id(true)`, `HttpOnly`, `SameSite=Lax`.
- **Security HTTP Headers:** `X-Frame-Options: SAMEORIGIN`, `X-Content-Type-Options: nosniff`, `X-XSS-Protection`, `CSP`.
- **SQL Injection Prevention:** 100% PDO Prepared Statements.

---

## 🚀 How to Run the Web Application

### Option A: Using XAMPP / WAMP / Laragon
1. Copy this folder into `D:\xampp\htdocs\Sri Sri Drill Machine Project\` (or `C:\xampp\htdocs\...`).
2. Start Apache and MySQL from your XAMPP Control Panel.
3. Open `http://localhost/Sri%20Sri%20Drill%20Machine%20Project/database/setup.php` to run the 1-click installer.
4. Visit `http://localhost/Sri%20Sri%20Drill%20Machine%20Project/`

### Option B: One-Click Run (Currently Active)
Double-click `run_server.bat` in the root folder. The server runs automatically on `http://localhost:8080/`.

---

## 🔐 Admin Panel Access
- URL: `http://localhost:8080/admin/index.php`
- Username: `admin`
- Password: `admin123`
