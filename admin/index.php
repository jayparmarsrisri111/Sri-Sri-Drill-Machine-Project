<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

// If already logged in, redirect to dashboard
if (!empty($_SESSION['admin_user'])) {
    header("Location: dashboard.php");
    exit;
}

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Brute-force protection: Max 5 attempts per 5 minutes
    if (!checkRateLimit('admin_login_attempt', 5, 300)) {
        $errorMessage = 'Too many failed login attempts. For security, please wait 5 minutes before trying again.';
    } else {
        $submittedToken = $_POST['csrf_token'] ?? '';
        if (!verifyCsrfToken($submittedToken)) {
            $errorMessage = 'Invalid security token. Please refresh the page.';
        } else {
            $username = sanitizeString($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $errorMessage = 'Please enter both username and password.';
            } else {
                try {
                    $pdo = Database::getConnection();
                    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = :u LIMIT 1");
                    $stmt->execute([':u' => $username]);
                    $user = $stmt->fetch();

                    if ($user && password_verify($password, $user['password_hash'])) {
                        // Prevent session fixation
                        session_regenerate_id(true);

                        $_SESSION['admin_user'] = [
                            'id' => $user['id'],
                            'username' => $user['username'],
                            'full_name' => $user['full_name'],
                            'email' => $user['email'],
                            'role' => $user['role'],
                            'login_time' => time()
                        ];
                        header("Location: dashboard.php");
                        exit;
                    } else {
                        $errorMessage = 'Invalid username or password. Please try again.';
                    }
                } catch (Exception $e) {
                    $errorMessage = 'Login Database Error: ' . $e->getMessage();
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | <?= APP_NAME ?></title>
    <link rel="icon" type="image/png" href="<?= assetUrl('assets/images/varsha-logo.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= assetUrl('assets/css/style.css') ?>">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #060911;
            background-image: radial-gradient(circle at 50% 50%, rgba(0, 229, 255, 0.08) 0%, transparent 60%);
        }
        .login-card {
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid rgba(0, 229, 255, 0.3);
            border-radius: var(--radius-lg);
            padding: 40px;
            max-width: 440px;
            width: 100%;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7), 0 0 35px rgba(0, 229, 255, 0.1);
            backdrop-filter: blur(16px);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div style="text-align: center; margin-bottom: 28px;">
            <a href="<?= BASE_URL ?>/index.php">
                <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand" style="height: 72px; width: 72px; border-radius: 50%; border: 2px solid var(--accent-cyan); box-shadow: 0 0 20px rgba(0,229,255,0.4); margin: 0 auto 16px; object-fit: contain;">
            </a>
            <h2 style="font-family: var(--font-heading); font-size: 22px; color: #fff;">
                VARSHA BRAND ADMIN
            </h2>
            <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
                Shree Varsha Engineering Works - Orders & Machine Management
            </p>
        </div>

        <?php if (!empty($errorMessage)): ?>
            <div style="padding: 12px 16px; background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; border-radius: 8px; color: #f87171; font-size: 13.5px; margin-bottom: 20px;">
                <?= e($errorMessage) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter username" value="admin" required autofocus>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" value="admin123" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 13px;">
                🔒 Sign In to Dashboard
            </button>
        </form>

        <div style="margin-top: 24px; padding: 14px; background: rgba(0, 229, 255, 0.05); border: 1px dashed rgba(0, 229, 255, 0.25); border-radius: 8px; font-size: 12.5px; color: #cbd5e1; text-align: center;">
            Default Credentials: <strong>admin</strong> / <strong>admin123</strong>
        </div>

        <div style="margin-top: 20px; text-align: center;">
            <a href="<?= BASE_URL ?>/index.php" style="font-size: 13px; color: var(--text-cyan);">
                ← Return to Website
            </a>
        </div>
    </div>
</body>
</html>
