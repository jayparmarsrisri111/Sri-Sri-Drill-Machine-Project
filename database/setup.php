<?php
/**
 * Database Setup & Installer Script
 * Shree Varsha Engineering Works
 */

require_once __DIR__ . '/../config/config.php';

$message = '';
$status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['install_mysql'])) {
    $host = $_POST['db_host'] ?? 'localhost';
    $port = $_POST['db_port'] ?? '3306';
    $name = $_POST['db_name'] ?? 'shree_varsha_drill_db';
    $user = $_POST['db_user'] ?? 'root';
    $pass = $_POST['db_pass'] ?? '';

    try {
        // Connect to MySQL server without database name first
        $pdo = new PDO("mysql:host=$host;port=$port", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        
        // Read schema
        $sql = file_get_contents(__DIR__ . '/schema.sql');
        $pdo->exec($sql);
        
        $message = "MySQL Database `{$name}` created and populated successfully! You can now log into the admin panel.";
        $status = 'success';
    } catch (PDOException $e) {
        $message = "MySQL Connection/Installation Failed: " . $e->getMessage();
        $status = 'danger';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup - Shree Varsha Engineering Works</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #090d16;
            --card-bg: rgba(17, 24, 39, 0.85);
            --border-color: rgba(56, 189, 248, 0.2);
            --accent-cyan: #00e5ff;
            --accent-blue: #2563eb;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(0, 229, 255, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(37, 99, 235, 0.1) 0%, transparent 40%);
        }
        .setup-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 36px;
            max-width: 580px;
            width: 100%;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.5), 0 0 30px rgba(0, 229, 255, 0.05);
            backdrop-filter: blur(12px);
        }
        h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 24px;
            margin-bottom: 8px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .subtitle {
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 24px;
        }
        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .alert-success { background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; color: #34d399; }
        .alert-danger { background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #f87171; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: 13px; font-weight: 500; color: #cbd5e1; margin-bottom: 6px; }
        input {
            width: 100%;
            padding: 12px 14px;
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
            transition: all 0.2s;
        }
        input:focus {
            outline: none;
            border-color: var(--accent-cyan);
            box-shadow: 0 0 0 3px rgba(0, 229, 255, 0.15);
        }
        .btn {
            display: inline-block;
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-blue));
            color: #050b14;
            font-weight: 700;
            font-size: 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: opacity 0.2s, transform 0.2s;
        }
        .btn:hover { opacity: 0.95; transform: translateY(-1px); }
        .badge-info {
            background: rgba(56, 189, 248, 0.1);
            border: 1px solid rgba(56, 189, 248, 0.25);
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            color: #7dd3fc;
            margin-bottom: 22px;
            line-height: 1.5;
        }
        .btn-outline {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #e2e8f0;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="setup-card">
        <h1>⚙️ Database Setup</h1>
        <p class="subtitle">Shree Varsha Engineering Works - Web Application</p>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $status ?>">
                <?= e($message) ?>
            </div>
        <?php endif; ?>

        <div class="badge-info">
            <strong>Automatic SQLite Fallback:</strong> The web app already has a ready-to-use pre-configured SQLite database active, so you can preview it immediately! To deploy on a full MySQL / XAMPP server, enter your credentials below and click Install.
        </div>

        <form method="POST">
            <div class="form-group">
                <label>Database Host</label>
                <input type="text" name="db_host" value="localhost" required>
            </div>
            <div class="form-group">
                <label>Database Port</label>
                <input type="text" name="db_port" value="3306" required>
            </div>
            <div class="form-group">
                <label>Database Name</label>
                <input type="text" name="db_name" value="shree_varsha_drill_db" required>
            </div>
            <div class="form-group">
                <label>MySQL Username</label>
                <input type="text" name="db_user" value="root" required>
            </div>
            <div class="form-group">
                <label>MySQL Password</label>
                <input type="password" name="db_pass" value="" placeholder="(empty for default XAMPP)">
            </div>
            <button type="submit" name="install_mysql" class="btn">⚡ Install MySQL Database & Tables</button>
            <a href="../index.php" class="btn btn-outline">← Back to Website Homepage</a>
        </form>
    </div>
</body>
</html>
