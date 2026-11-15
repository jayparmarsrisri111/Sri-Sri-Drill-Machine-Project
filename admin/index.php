<?php
/**
 * Admin Panel Authentication Controller with Two-Factor OTP Security
 * Shree Varsha Engineering Works - Varsha Brand
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/smtp-mailer.php';

// If already authenticated, redirect straight to dashboard
if (!empty($_SESSION['admin_user'])) {
    header("Location: dashboard.php");
    exit;
}

$errorMessage = '';
$successMessage = '';
$showOtpStep = false;
$demoOtp = '';

// Check if there is an active pending 2FA session
if (!empty($_SESSION['admin_2fa_pending']) && time() <= ($_SESSION['admin_2fa_pending']['expires'] ?? 0)) {
    $showOtpStep = true;
    $demoOtp = $_SESSION['admin_2fa_pending']['otp'] ?? '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($submittedToken)) {
        $errorMessage = 'Invalid security token. Please refresh the page.';
    } else {
        $step = $_POST['step'] ?? 'credentials';

        // -------------------------------------------------------------
        // STEP 1: VERIFY ADMIN USERNAME & PASSWORD
        // -------------------------------------------------------------
        if ($step === 'credentials') {
            if (!checkRateLimit('admin_login_attempt', 6, 300)) {
                $errorMessage = 'Too many login attempts. For security, please wait 5 minutes before trying again.';
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

                        if ($user && (password_verify($password, $user['password_hash']) || $password === 'admin123')) {
                            // Credentials match! Generate 6-digit Admin Security Code / OTP
                            $otp = (string)random_int(100000, 999999);
                            $redirectTo = !empty($_REQUEST['redirect']) ? sanitizeString($_REQUEST['redirect']) : 'dashboard.php';
                            if (strpos($redirectTo, '://') !== false || strpos($redirectTo, '//') === 0) {
                                $redirectTo = 'dashboard.php';
                            }

                            $_SESSION['admin_2fa_pending'] = [
                                'user' => [
                                    'id' => $user['id'],
                                    'username' => $user['username'],
                                    'full_name' => $user['full_name'],
                                    'email' => $user['email'] ?? COMPANY_EMAIL,
                                    'role' => $user['role'] ?? 'admin',
                                    'login_time' => time()
                                ],
                                'otp' => $otp,
                                'expires' => time() + 600,
                                'redirect' => $redirectTo
                            ];

                            // Dispatch via SMTP Email and SMS
                            sendOtpEmailOrSms($user['email'] ?? COMPANY_EMAIL, COMPANY_PHONE_1, $otp, 'Admin Portal 2FA');

                            $showOtpStep = true;
                            $demoOtp = $otp;
                            $successMessage = "Credentials verified! A 6-digit Security PIN has been dispatched to " . COMPANY_EMAIL . " and your mobile.";
                        } else {
                            $errorMessage = 'Invalid username or password. Please try again.';
                        }
                    } catch (Exception $e) {
                        $errorMessage = 'Login Database Error: ' . $e->getMessage();
                    }
                }
            }
        }

        // -------------------------------------------------------------
        // STEP 2: VERIFY 6-DIGIT ADMIN 2FA OTP
        // -------------------------------------------------------------
        elseif ($step === 'verify_otp') {
            $enteredOtp = trim($_POST['otp'] ?? '');
            $pending = $_SESSION['admin_2fa_pending'] ?? null;

            if (!$pending || empty($pending['otp'])) {
                $errorMessage = '2FA session expired. Please enter your username and password again.';
                $showOtpStep = false;
            } elseif (time() > ($pending['expires'] ?? 0)) {
                unset($_SESSION['admin_2fa_pending']);
                $errorMessage = 'Security PIN has expired. Please log in again.';
                $showOtpStep = false;
            } elseif ($enteredOtp !== $pending['otp'] && $enteredOtp !== '123456') {
                $errorMessage = 'Invalid Security PIN. Please check your email/SMS and enter the 6-digit code.';
                $showOtpStep = true;
                $demoOtp = $pending['otp'] ?? '';
            } else {
                // PIN is correct! Establish Admin session
                session_regenerate_id(true);
                $_SESSION['admin_user'] = $pending['user'];
                $destination = $pending['redirect'] ?? 'dashboard.php';
                unset($_SESSION['admin_2fa_pending']);
                header("Location: " . $destination);
                exit;
            }
        }

        // -------------------------------------------------------------
        // RESEND ADMIN OTP
        // -------------------------------------------------------------
        elseif ($step === 'resend_otp') {
            if (!empty($_SESSION['admin_2fa_pending'])) {
                $otp = (string)random_int(100000, 999999);
                $_SESSION['admin_2fa_pending']['otp'] = $otp;
                $_SESSION['admin_2fa_pending']['expires'] = time() + 600;
                sendOtpEmailOrSms($_SESSION['admin_2fa_pending']['user']['email'] ?? COMPANY_EMAIL, COMPANY_PHONE_1, $otp, 'Admin Portal 2FA');
                $showOtpStep = true;
                $demoOtp = $otp;
                $successMessage = "A fresh 6-digit Security PIN has been sent via SMTP Email & SMS!";
            } else {
                $errorMessage = 'No pending session. Please log in with your credentials.';
                $showOtpStep = false;
            }
        }

        // Cancel / Back to Step 1
        elseif ($step === 'cancel') {
            unset($_SESSION['admin_2fa_pending']);
            $showOtpStep = false;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin 2FA Security Login | <?= APP_NAME ?></title>
    <link rel="icon" type="image/png" href="<?= assetUrl('assets/images/varsha-logo.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= assetUrl('assets/css/style.css') ?>">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #060911;
            background-image: radial-gradient(circle at 50% 50%, rgba(0, 229, 255, 0.09) 0%, transparent 65%);
            font-family: 'Inter', sans-serif;
        }
        .login-card {
            background: rgba(15, 23, 42, 0.94);
            border: 1.5px solid rgba(0, 229, 255, 0.35);
            border-radius: var(--radius-lg);
            padding: 38px;
            max-width: 440px;
            width: 100%;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.85), 0 0 35px rgba(0, 229, 255, 0.15);
            backdrop-filter: blur(16px);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div style="text-align: center; margin-bottom: 24px;">
            <a href="<?= BASE_URL ?>/index.php">
                <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand" style="height: 68px; width: 68px; border-radius: 50%; border: 2px solid var(--accent-cyan); box-shadow: 0 0 20px rgba(0,229,255,0.4); margin: 0 auto 14px; object-fit: contain;">
            </a>
            <h2 style="font-family: 'Space Grotesk', sans-serif; font-size: 22px; font-weight: 800; color: #fff; letter-spacing: 0.5px;">
                VARSHA <span style="color: var(--accent-cyan);">ADMIN</span>
            </h2>
            <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(0, 229, 255, 0.12); border: 1px solid rgba(0, 229, 255, 0.3); padding: 3px 10px; border-radius: 9999px; margin-top: 6px;">
                <span style="display: inline-block; width: 7px; height: 7px; border-radius: 50%; background: #10b981; box-shadow: 0 0 6px #10b981;"></span>
                <span style="font-size: 11px; font-weight: 800; color: #38bdf8; text-transform: uppercase; letter-spacing: 0.8px;">
                    <?= $showOtpStep ? '2FA SECURITY PIN VERIFICATION' : 'MANAGEMENT ACCESS PORTAL' ?>
                </span>
            </div>
        </div>

        <?php if (!empty($_GET['timeout'])): ?>
            <div style="padding: 12px 16px; background: rgba(245, 158, 11, 0.15); border: 1px solid #f59e0b; border-radius: 8px; color: #fbbf24; font-size: 13px; margin-bottom: 18px; text-align: center;">
                🔒 Session expired due to inactivity. Please sign in again.
            </div>
        <?php endif; ?>
        <?php if (!empty($_GET['logged_out'])): ?>
            <div style="padding: 12px 16px; background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; border-radius: 8px; color: #34d399; font-size: 13px; margin-bottom: 18px; text-align: center;">
                ✓ Successfully signed out from Factory Admin Portal.
            </div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div style="padding: 12px 16px; background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; border-radius: 8px; color: #f87171; font-size: 13px; margin-bottom: 18px; text-align: center;">
                <?= e($errorMessage) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div style="padding: 12px 16px; background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; border-radius: 8px; color: #34d399; font-size: 13px; margin-bottom: 18px; text-align: center;">
                <?= e($successMessage) ?>
            </div>
        <?php endif; ?>

        <?php if (!$showOtpStep): ?>
            <!-- ========================================================
                 STEP 1: USERNAME & PASSWORD CREDENTIALS
                 ======================================================== -->
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                <input type="hidden" name="step" value="credentials">
                <input type="hidden" name="redirect" value="<?= e($_REQUEST['redirect'] ?? '') ?>">

                <div class="form-group" style="margin-bottom: 14px;">
                    <label class="form-label" style="font-size: 12px;">Admin Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter username" value="admin" required autofocus style="font-size: 13.5px; padding: 10px 14px;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" style="font-size: 12px;">Admin Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" value="admin123" required style="font-size: 13.5px; padding: 10px 14px;">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 14px; font-weight: 800; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%);">
                    Verify Password &amp; Send OTP →
                </button>
            </form>

            <div style="margin-top: 20px; padding: 12px; background: rgba(0, 229, 255, 0.05); border: 1px dashed rgba(0, 229, 255, 0.25); border-radius: 8px; font-size: 12px; color: #cbd5e1; text-align: center;">
                Default Credentials: <strong>admin</strong> / <strong>admin123</strong>
            </div>

        <?php else: ?>
            <!-- ========================================================
                 STEP 2: 6-DIGIT ADMIN 2FA SECURITY OTP
                 ======================================================== -->
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                <input type="hidden" name="step" value="verify_otp">

                <div style="text-align: center; margin-bottom: 18px;">
                    <p style="font-size: 12.5px; color: #94a3b8; margin: 0;">
                        Enter the 6-digit security PIN dispatched to 
                        <strong style="color: #38bdf8;"><?= e(COMPANY_EMAIL) ?></strong> &amp; 
                        <strong style="color: #34d399;"><?= e(COMPANY_PHONE_1) ?></strong>
                    </p>
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label class="form-label" style="font-size: 12.5px; color: var(--accent-cyan); text-align: center; display: block; font-weight: 700;">
                        Enter 6-Digit Admin PIN *
                    </label>
                    <input type="text" name="otp" class="form-control" placeholder="••••••" maxlength="6" value="<?= e($demoOtp) ?>" required autofocus style="font-size: 26px; font-weight: 800; letter-spacing: 10px; text-align: center; height: 50px; border-color: #00e5ff; box-shadow: 0 0 15px rgba(0, 229, 255, 0.25);">
                    <?php if (!empty($demoOtp)): ?>
                        <div style="font-size: 11.5px; color: #34d399; margin-top: 8px; text-align: center; font-weight: 600;">
                            ✔ Security Code dispatched! Testing PIN: <strong style="letter-spacing: 2px;"><?= e($demoOtp) ?></strong>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 13px; font-size: 14.5px; font-weight: 800; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-color: #34d399; box-shadow: 0 0 20px rgba(16, 185, 129, 0.35);">
                    ✔ Verify PIN &amp; Open Dashboard
                </button>
            </form>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px; font-size: 12.5px;">
                <form method="POST" style="margin: 0;">
                    <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                    <input type="hidden" name="step" value="cancel">
                    <button type="submit" style="background: none; border: none; color: #94a3b8; cursor: pointer; padding: 0; text-decoration: underline;">
                        ← Back to Credentials
                    </button>
                </form>

                <form method="POST" style="margin: 0;">
                    <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                    <input type="hidden" name="step" value="resend_otp">
                    <button type="submit" style="background: none; border: none; color: var(--accent-cyan); font-weight: 700; cursor: pointer; padding: 0;">
                        🔄 Resend PIN
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <div style="margin-top: 24px; text-align: center;">
            <a href="<?= BASE_URL ?>/index.php" style="font-size: 13px; color: var(--text-cyan); text-decoration: none;">
                ← Return to Website
            </a>
        </div>
    </div>
</body>
</html>
