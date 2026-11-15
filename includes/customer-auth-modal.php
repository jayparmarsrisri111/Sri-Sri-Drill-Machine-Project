<!-- Customer Login & Registration Modal -->
<div id="customerAuthModal" class="quote-modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(4, 7, 13, 0.9); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); z-index: 10005; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #090d16; border: 1.5px solid var(--accent-cyan); border-radius: 18px; width: 100%; max-width: 480px; box-shadow: 0 25px 70px rgba(0,0,0,0.95), 0 0 35px rgba(0, 229, 255, 0.25); overflow: hidden; animation: authPop 0.25s ease;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(6, 10, 20, 0.98) 100%); padding: 18px 22px; border-bottom: 1px solid rgba(0, 229, 255, 0.25); display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand" style="height: 38px; width: 38px; border-radius: 50%; border: 1.5px solid var(--accent-cyan);">
                <div>
                    <h3 id="authModalHeaderTitle" style="font-family: 'Space Grotesk', sans-serif; font-size: 16px; font-weight: 800; color: #fff; margin: 0;">Customer Portal • Sign In</h3>
                    <div style="font-size: 11px; color: var(--accent-cyan); font-weight: 600;">Shree Varsha Engineering Works</div>
                </div>
            </div>
            <button type="button" id="btnAuthModalClose" style="background:none; border:none; color:#94a3b8; font-size:26px; cursor:pointer; line-height:1;">&times;</button>
        </div>

        <!-- Navigation Tabs -->
        <div style="display: flex; background: rgba(11, 17, 32, 0.9); border-bottom: 1px solid rgba(255,255,255,0.08);">
            <button type="button" class="auth-tab-btn active" id="tabBtnLogin" style="flex: 1; padding: 12px; background: none; border: none; border-bottom: 2px solid var(--accent-cyan); color: #fff; font-weight: 700; font-size: 13.5px; cursor: pointer;">
                🔑 Sign In (Login)
            </button>
            <button type="button" class="auth-tab-btn" id="tabBtnRegister" style="flex: 1; padding: 12px; background: none; border: none; border-bottom: 2px solid transparent; color: #94a3b8; font-weight: 700; font-size: 13.5px; cursor: pointer;">
                📝 New Registration
            </button>
        </div>

        <div style="padding: 22px;">
            <div id="authNoticeMsg" style="display: none; padding: 10px 14px; border-radius: 8px; font-size: 12.5px; margin-bottom: 16px; text-align: center;"></div>

            <!-- Tab 1: Login Form -->
            <form id="formCustomerLogin">
                <input type="hidden" name="action" id="loginActionType" value="login">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">

                <!-- Login Mode Switch: Password vs OTP -->
                <div style="display: flex; gap: 8px; margin-bottom: 14px; background: rgba(15, 23, 42, 0.7); padding: 4px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.08);">
                    <button type="button" id="btnModePassword" style="flex: 1; padding: 6px; font-size: 12px; font-weight: 700; border-radius: 6px; border: none; background: var(--accent-cyan); color: #060911; cursor: pointer;">
                        🔑 Password Login
                    </button>
                    <button type="button" id="btnModeOtp" style="flex: 1; padding: 6px; font-size: 12px; font-weight: 700; border-radius: 6px; border: none; background: transparent; color: #94a3b8; cursor: pointer;">
                        📲 Email / SMS OTP
                    </button>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label class="form-label" style="font-size: 12px;">Mobile Number or Email *</label>
                    <input type="text" name="identifier" id="loginIdentifier" class="form-control" placeholder="e.g. 9825112345 or email" required style="font-size: 13px; padding: 10px 14px;">
                </div>

                <!-- Password Field (Active in Password Mode) -->
                <div id="loginPasswordGroup" class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-size: 12px;">Password *</label>
                    <input type="password" name="password" id="loginPassword" class="form-control" placeholder="••••••••" style="font-size: 13px; padding: 10px 14px;">
                </div>

                <!-- OTP Field Block (Active in OTP Mode) -->
                <div id="loginOtpGroup" style="display: none; margin-bottom: 16px;">
                    <div style="display: flex; gap: 8px; margin-bottom: 10px;">
                        <button type="button" id="btnSendLoginOtp" class="btn" style="flex: 1; height: 38px; font-size: 12px; font-weight: 700; background: rgba(56, 189, 248, 0.18); border: 1px solid #38bdf8; color: #38bdf8; border-radius: 8px; cursor: pointer;">
                            📤 Send OTP (Email / SMS)
                        </button>
                    </div>
                    <div id="otpInputRow" style="display: none;">
                        <label class="form-label" style="font-size: 12px; color: #38bdf8;">Enter 6-Digit OTP *</label>
                        <input type="text" id="loginOtpInput" name="otp" class="form-control" placeholder="6-digit code (e.g. 123456)" maxlength="6" style="font-size: 16px; font-weight: 800; letter-spacing: 4px; text-align: center; height: 42px; border-color: #00e5ff;">
                        <div id="otpDemoHint" style="display: none; font-size: 11px; color: #34d399; margin-top: 6px; text-align: center;"></div>
                    </div>
                </div>

                <button type="submit" id="btnSubmitLogin" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 14px; font-weight: 800; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%);">
                    Sign In to Continue
                </button>

                <div style="margin: 16px 0 12px; text-align: center; position: relative;">
                    <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.1); margin: 0;">
                    <span style="position: absolute; top: -10px; left: 50%; transform: translateX(-50%); background: #090d16; padding: 0 10px; font-size: 11px; color: #64748b;">OR DEMO LOGIN</span>
                </div>

                <button type="button" id="btnFastDemoLogin" class="btn btn-outline" style="width: 100%; padding: 10px; font-size: 12.5px; border-color: #34d399; color: #34d399; font-weight: 700;">
                    ⚡ 1-Click Demo Login (Kishorbhai Parmar)
                </button>
            </form>

            <!-- Tab 2: Registration Form -->
            <form id="formCustomerRegister" style="display: none;">
                <input type="hidden" name="action" value="register">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 11.5px;">Full Name *</label>
                        <input type="text" name="full_name" class="form-control" placeholder="e.g. Rajesh Patel" required style="font-size: 12.5px; padding: 8px 10px;">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 11.5px;">Company / Workshop</label>
                        <input type="text" name="company_name" class="form-control" placeholder="e.g. Patel Engineering" style="font-size: 12.5px; padding: 8px 10px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 11.5px;">Mobile Number *</label>
                        <input type="tel" name="phone" id="regPhone" class="form-control" placeholder="e.g. 9898123456" required style="font-size: 12.5px; padding: 8px 10px;">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 11.5px;">Email Address *</label>
                        <input type="email" name="email" id="regEmail" class="form-control" placeholder="e.g. rajesh@gmail.com" required style="font-size: 12.5px; padding: 8px 10px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 11.5px;">GSTIN (Optional)</label>
                        <input type="text" name="gstin" class="form-control" placeholder="24AAACS1234F1Z5" style="font-size: 12.5px; padding: 8px 10px;">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" style="font-size: 11.5px;">City &amp; State</label>
                        <input type="text" name="city" class="form-control" placeholder="e.g. Bhavnagar, Gujarat" value="Bhavnagar" style="font-size: 12.5px; padding: 8px 10px;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 10px;">
                    <label class="form-label" style="font-size: 11.5px;">Factory / Workshop Address</label>
                    <input type="text" name="address" class="form-control" placeholder="e.g. Shed No. 12, G.I.D.C." style="font-size: 12.5px; padding: 8px 10px;">
                </div>

                <div class="form-group" style="margin-bottom: 12px;">
                    <label class="form-label" style="font-size: 11.5px;">Set Account Password *</label>
                    <input type="password" name="password" class="form-control" placeholder="Min. 4 characters" required style="font-size: 12.5px; padding: 8px 10px;">
                </div>

                <!-- OTP Verification on Sign Up -->
                <div style="margin-bottom: 14px; padding: 10px; background: rgba(15, 23, 42, 0.7); border: 1px dashed rgba(52, 211, 153, 0.4); border-radius: 8px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <span style="font-size: 11.5px; color: #34d399; font-weight: 700;">🔐 Email/SMS Verification</span>
                        <button type="button" id="btnSendRegOtp" style="background: rgba(52, 211, 153, 0.15); border: 1px solid #34d399; color: #34d399; font-size: 10.5px; font-weight: 800; padding: 3px 8px; border-radius: 4px; cursor: pointer;">
                            Send OTP
                        </button>
                    </div>
                    <input type="text" id="regOtpInput" name="reg_otp" class="form-control" placeholder="Enter 6-digit OTP (Optional if test)" maxlength="6" style="font-size: 13px; text-align: center; height: 34px;">
                    <div id="regOtpHint" style="display: none; font-size: 10.5px; color: #34d399; margin-top: 4px; text-align: center;"></div>
                </div>

                <button type="submit" id="btnSubmitRegister" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 14px; font-weight: 800; background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399;">
                    Create Account &amp; Proceed
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const authModal = document.getElementById('customerAuthModal');
    const btnClose = document.getElementById('btnAuthModalClose');
    const tabLogin = document.getElementById('tabBtnLogin');
    const tabRegister = document.getElementById('tabBtnRegister');
    const formLogin = document.getElementById('formCustomerLogin');
    const formRegister = document.getElementById('formCustomerRegister');
    const authNotice = document.getElementById('authNoticeMsg');
    const btnDemo = document.getElementById('btnFastDemoLogin');

    // Tab switching
    if (tabLogin && tabRegister) {
        tabLogin.addEventListener('click', function() {
            tabLogin.classList.add('active');
            tabLogin.style.borderBottomColor = 'var(--accent-cyan)';
            tabLogin.style.color = '#fff';
            tabRegister.classList.remove('active');
            tabRegister.style.borderBottomColor = 'transparent';
            tabRegister.style.color = '#94a3b8';
            formLogin.style.display = 'block';
            formRegister.style.display = 'none';
            if (authNotice) authNotice.style.display = 'none';
        });

        tabRegister.addEventListener('click', function() {
            tabRegister.classList.add('active');
            tabRegister.style.borderBottomColor = '#10b981';
            tabRegister.style.color = '#fff';
            tabLogin.classList.remove('active');
            tabLogin.style.borderBottomColor = 'transparent';
            tabLogin.style.color = '#94a3b8';
            formLogin.style.display = 'none';
            formRegister.style.display = 'block';
            if (authNotice) authNotice.style.display = 'none';
        });
    }

    window.openCustomerAuthModal = function(noticeText = '') {
        if (authModal) {
            authModal.style.display = 'flex';
            if (noticeText && authNotice) {
                authNotice.style.display = 'block';
                authNotice.style.background = 'rgba(0, 229, 255, 0.15)';
                authNotice.style.border = '1px solid #00e5ff';
                authNotice.style.color = '#00e5ff';
                authNotice.textContent = noticeText;
            }
        }
    };

    if (btnClose) {
        btnClose.addEventListener('click', function() {
            authModal.style.display = 'none';
        });
    }

    if (authModal) {
        authModal.addEventListener('click', function(e) {
            if (e.target === authModal) authModal.style.display = 'none';
        });
    }

    // OTP vs Password Mode Switching
    const btnModePassword = document.getElementById('btnModePassword');
    const btnModeOtp = document.getElementById('btnModeOtp');
    const loginPasswordGroup = document.getElementById('loginPasswordGroup');
    const loginOtpGroup = document.getElementById('loginOtpGroup');
    const loginActionType = document.getElementById('loginActionType');
    const loginPassword = document.getElementById('loginPassword');
    const btnSendLoginOtp = document.getElementById('btnSendLoginOtp');
    const otpInputRow = document.getElementById('otpInputRow');
    const loginOtpInput = document.getElementById('loginOtpInput');
    const otpDemoHint = document.getElementById('otpDemoHint');
    const loginIdentifier = document.getElementById('loginIdentifier');

    if (btnModePassword && btnModeOtp) {
        btnModePassword.addEventListener('click', function() {
            btnModePassword.style.background = 'var(--accent-cyan)';
            btnModePassword.style.color = '#060911';
            btnModeOtp.style.background = 'transparent';
            btnModeOtp.style.color = '#94a3b8';
            loginPasswordGroup.style.display = 'block';
            loginOtpGroup.style.display = 'none';
            loginActionType.value = 'login';
            if (loginPassword) loginPassword.required = true;
            if (loginOtpInput) loginOtpInput.required = false;
        });

        btnModeOtp.addEventListener('click', function() {
            btnModeOtp.style.background = 'var(--accent-cyan)';
            btnModeOtp.style.color = '#060911';
            btnModePassword.style.background = 'transparent';
            btnModePassword.style.color = '#94a3b8';
            loginPasswordGroup.style.display = 'none';
            loginOtpGroup.style.display = 'block';
            loginActionType.value = 'verify_otp';
            if (loginPassword) loginPassword.required = false;
            if (loginOtpInput) loginOtpInput.required = true;
        });
    }

    // Send Login OTP via SMTP
    if (btnSendLoginOtp) {
        btnSendLoginOtp.addEventListener('click', function() {
            const idVal = loginIdentifier ? loginIdentifier.value.trim() : '';
            if (!idVal) {
                alert('Please enter your Mobile Number or Email first.');
                if (loginIdentifier) loginIdentifier.focus();
                return;
            }

            btnSendLoginOtp.disabled = true;
            btnSendLoginOtp.textContent = 'Sending OTP via SMTP...';

            const fd = new FormData();
            fd.append('action', 'send_otp');
            fd.append('identifier', idVal);
            fd.append('purpose', 'login');
            fd.append('csrf_token', '<?= getCsrfToken() ?>');

            fetch('<?= BASE_URL ?>/auth-customer.php', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        btnSendLoginOtp.textContent = '✔ OTP Dispatched!';
                        btnSendLoginOtp.style.borderColor = '#10b981';
                        btnSendLoginOtp.style.color = '#34d399';
                        if (otpInputRow) otpInputRow.style.display = 'block';
                        if (loginOtpInput) {
                            loginOtpInput.focus();
                            if (data.demo_otp) {
                                loginOtpInput.value = data.demo_otp;
                            }
                        }
                        if (otpDemoHint) {
                            otpDemoHint.style.display = 'block';
                            otpDemoHint.innerHTML = '✔ OTP sent to email! Demo OTP: <strong>' + (data.demo_otp || '123456') + '</strong>';
                        }
                        authNotice.style.display = 'block';
                        authNotice.style.background = 'rgba(16, 185, 129, 0.15)';
                        authNotice.style.border = '1px solid #10b981';
                        authNotice.style.color = '#34d399';
                        authNotice.textContent = data.message;
                    } else {
                        alert(data.message);
                        btnSendLoginOtp.disabled = false;
                        btnSendLoginOtp.textContent = '📤 Send OTP (Email / SMS)';
                    }
                })
                .catch(err => {
                    alert('Network error while dispatching OTP.');
                    btnSendLoginOtp.disabled = false;
                    btnSendLoginOtp.textContent = '📤 Send OTP (Email / SMS)';
                });
        });
    }

    // Send Registration OTP via SMTP
    const btnSendRegOtp = document.getElementById('btnSendRegOtp');
    const regPhone = document.getElementById('regPhone');
    const regEmail = document.getElementById('regEmail');
    const regOtpInput = document.getElementById('regOtpInput');
    const regOtpHint = document.getElementById('regOtpHint');

    if (btnSendRegOtp) {
        btnSendRegOtp.addEventListener('click', function() {
            const ph = regPhone ? regPhone.value.trim() : '';
            const em = regEmail ? regEmail.value.trim() : '';
            if (!ph && !em) {
                alert('Please enter Mobile Number and Email to receive OTP.');
                return;
            }

            btnSendRegOtp.disabled = true;
            btnSendRegOtp.textContent = 'Sending...';

            const fd = new FormData();
            fd.append('action', 'send_otp');
            fd.append('phone', ph);
            fd.append('email', em);
            fd.append('purpose', 'register');
            fd.append('csrf_token', '<?= getCsrfToken() ?>');

            fetch('<?= BASE_URL ?>/auth-customer.php', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        btnSendRegOtp.textContent = '✔ Sent';
                        if (regOtpInput && data.demo_otp) {
                            regOtpInput.value = data.demo_otp;
                        }
                        if (regOtpHint) {
                            regOtpHint.style.display = 'block';
                            regOtpHint.innerHTML = '✔ OTP: <strong>' + (data.demo_otp || '123456') + '</strong>';
                        }
                    } else {
                        alert(data.message);
                        btnSendRegOtp.disabled = false;
                        btnSendRegOtp.textContent = 'Send OTP';
                    }
                })
                .catch(() => {
                    btnSendRegOtp.disabled = false;
                    btnSendRegOtp.textContent = 'Send OTP';
                });
        });
    }

    // Global Fast 1-Click Demo Login Function
    window.quickCustomerDemoLogin = function() {
        if (btnDemo) {
            btnDemo.click();
            return;
        }
        const fd = new FormData();
        fd.append('action', 'demo_login');
        fd.append('csrf_token', '<?= getCsrfToken() ?>');
        fetch('<?= BASE_URL ?>/auth-customer.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => { window.location.reload(); })
            .catch(() => { window.location.reload(); });
    };

    // Fast 1-Click Demo Login
    if (btnDemo) {
        btnDemo.addEventListener('click', function() {
            btnDemo.disabled = true;
            btnDemo.textContent = 'Logging in as Demo Customer...';
            const fd = new FormData();
            fd.append('action', 'demo_login');
            fd.append('csrf_token', '<?= getCsrfToken() ?>');

            fetch('<?= BASE_URL ?>/auth-customer.php', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message);
                        btnDemo.disabled = false;
                        btnDemo.textContent = '⚡ 1-Click Demo Login';
                    }
                })
                .catch(() => { window.location.reload(); });
        });
    }

    // Handle Login Submit
    if (formLogin) {
        formLogin.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitLogin');
            btn.disabled = true;
            btn.textContent = 'Verifying Credentials...';

            fetch('<?= BASE_URL ?>/auth-customer.php', {
                method: 'POST',
                body: new FormData(formLogin)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    authNotice.style.display = 'block';
                    authNotice.style.background = 'rgba(16, 185, 129, 0.2)';
                    authNotice.style.border = '1px solid #10b981';
                    authNotice.style.color = '#34d399';
                    authNotice.textContent = data.message;
                    setTimeout(function() { window.location.reload(); }, 800);
                } else {
                    authNotice.style.display = 'block';
                    authNotice.style.background = 'rgba(239, 68, 68, 0.2)';
                    authNotice.style.border = '1px solid #ef4444';
                    authNotice.style.color = '#f87171';
                    authNotice.textContent = data.message;
                    btn.disabled = false;
                    btn.textContent = 'Sign In to Continue';
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.textContent = 'Sign In to Continue';
            });
        });
    }

    // Handle Register Submit
    if (formRegister) {
        formRegister.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitRegister');
            btn.disabled = true;
            btn.textContent = 'Creating Account...';

            fetch('<?= BASE_URL ?>/auth-customer.php', {
                method: 'POST',
                body: new FormData(formRegister)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    authNotice.style.display = 'block';
                    authNotice.style.background = 'rgba(16, 185, 129, 0.2)';
                    authNotice.style.border = '1px solid #10b981';
                    authNotice.style.color = '#34d399';
                    authNotice.textContent = data.message;
                    setTimeout(function() { window.location.reload(); }, 800);
                } else {
                    authNotice.style.display = 'block';
                    authNotice.style.background = 'rgba(239, 68, 68, 0.2)';
                    authNotice.style.border = '1px solid #ef4444';
                    authNotice.style.color = '#f87171';
                    authNotice.textContent = data.message;
                    btn.disabled = false;
                    btn.textContent = 'Create Account & Proceed';
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.textContent = 'Create Account & Proceed';
            });
        });
    }
});
</script>
