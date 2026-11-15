<!-- Customer Login & Registration Modal - Amazon-Style 2-Step OTP Authentication Flow -->
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

        <!-- Navigation Tabs (Only visible when not on OTP step) -->
        <div id="authNavTabs" style="display: flex; background: rgba(11, 17, 32, 0.9); border-bottom: 1px solid rgba(255,255,255,0.08);">
            <button type="button" class="auth-tab-btn active" id="tabBtnLogin" style="flex: 1; padding: 12px; background: none; border: none; border-bottom: 2px solid var(--accent-cyan); color: #fff; font-weight: 700; font-size: 13.5px; cursor: pointer;">
                🔑 Sign In (Login)
            </button>
            <button type="button" class="auth-tab-btn" id="tabBtnRegister" style="flex: 1; padding: 12px; background: none; border: none; border-bottom: 2px solid transparent; color: #94a3b8; font-weight: 700; font-size: 13.5px; cursor: pointer;">
                📝 New Registration
            </button>
        </div>

        <div style="padding: 22px;">
            <!-- Dynamic Status / Alert Banner -->
            <div id="authNoticeMsg" style="display: none; padding: 10px 14px; border-radius: 8px; font-size: 12.5px; margin-bottom: 16px; text-align: center;"></div>

            <!-- ========================================================
                 FLOW 1: CUSTOMER LOGIN (AMAZON-STYLE 2-STEP)
                 ======================================================== -->
            <!-- Step 1: Enter Password -->
            <form id="formLoginStep1">
                <input type="hidden" name="action" value="login_step1">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">

                <div class="form-group" style="margin-bottom: 14px;">
                    <label class="form-label" style="font-size: 12px;">Mobile Number or Email *</label>
                    <input type="text" name="identifier" id="loginIdentifier" class="form-control" placeholder="e.g. 9825112345 or email@domain.com" required style="font-size: 13px; padding: 10px 14px;">
                </div>

                <div class="form-group" style="margin-bottom: 10px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <label class="form-label" style="font-size: 12px; margin: 0;">Password *</label>
                        <a href="javascript:void(0)" id="linkForgotPassword" style="font-size: 11.5px; color: var(--accent-cyan); text-decoration: none;">Forgot password?</a>
                    </div>
                    <input type="password" name="password" id="loginPassword" class="form-control" placeholder="••••••••" required style="font-size: 13px; padding: 10px 14px;">
                </div>

                <button type="submit" id="btnSubmitLoginStep1" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 14px; font-weight: 800; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); margin-top: 10px;">
                    Verify Password &amp; Continue →
                </button>

                <div style="margin: 16px 0 12px; text-align: center; position: relative;">
                    <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.1); margin: 0;">
                    <span style="position: absolute; top: -10px; left: 50%; transform: translateX(-50%); background: #090d16; padding: 0 10px; font-size: 11px; color: #64748b;">OR FAST TESTING</span>
                </div>

                <button type="button" id="btnFastDemoLogin" class="btn btn-outline" style="width: 100%; padding: 10px; font-size: 12.5px; border-color: #34d399; color: #34d399; font-weight: 700;">
                    ⚡ 1-Click Demo Login (Kishorbhai Parmar)
                </button>
            </form>

            <!-- Step 2: Enter 2FA OTP -->
            <form id="formLoginStep2" style="display: none;">
                <input type="hidden" name="action" value="login_step2">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">

                <div style="text-align: center; margin-bottom: 16px;">
                    <div style="font-size: 26px; margin-bottom: 4px;">🔐</div>
                    <h4 style="color: #fff; font-size: 16px; font-weight: 800; margin: 0 0 4px 0;">Two-Step Verification</h4>
                    <p id="loginOtpSubtitle" style="font-size: 12px; color: #94a3b8; margin: 0;">
                        Enter the 6-digit OTP code sent to your registered mobile and email.
                    </p>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label class="form-label" style="font-size: 12px; color: var(--accent-cyan); text-align: center; display: block;">Enter 6-Digit OTP *</label>
                    <input type="text" name="otp" id="loginOtpInput" class="form-control" placeholder="••••••" maxlength="6" required style="font-size: 22px; font-weight: 800; letter-spacing: 8px; text-align: center; height: 48px; border-color: #00e5ff;">
                    <div id="loginOtpDemoHint" style="display: none; font-size: 11px; color: #34d399; margin-top: 6px; text-align: center;"></div>
                </div>

                <button type="submit" id="btnSubmitLoginStep2" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 14px; font-weight: 800; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-color: #34d399;">
                    ✔ Verify OTP &amp; Sign In
                </button>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 14px; font-size: 12px;">
                    <a href="javascript:void(0)" id="btnBackToPassword" style="color: #94a3b8; text-decoration: none;">← Back to Password</a>
                    <button type="button" id="btnResendLoginOtp" style="background: none; border: none; color: var(--accent-cyan); font-weight: 700; cursor: pointer; padding: 0;">
                        Resend OTP (<span id="loginCountdown">60</span>s)
                    </button>
                </div>
            </form>

            <!-- ========================================================
                 FLOW 2: CUSTOMER SIGN UP (AMAZON-STYLE 2-STEP)
                 ======================================================== -->
            <!-- Step 1: Details Form -->
            <form id="formRegisterStep1" style="display: none;">
                <input type="hidden" name="action" value="register_step1">
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

                <div class="form-group" style="margin-bottom: 14px;">
                    <label class="form-label" style="font-size: 11.5px;">Set Account Password *</label>
                    <input type="password" name="password" class="form-control" placeholder="Min. 4 characters" required style="font-size: 12.5px; padding: 8px 10px;">
                </div>

                <button type="submit" id="btnSubmitRegisterStep1" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 14px; font-weight: 800; background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399;">
                    Continue &amp; Verify Mobile/Email →
                </button>
            </form>

            <!-- Step 2: Register OTP Verification -->
            <form id="formRegisterStep2" style="display: none;">
                <input type="hidden" name="action" value="register_step2">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">

                <div style="text-align: center; margin-bottom: 16px;">
                    <div style="font-size: 26px; margin-bottom: 4px;">📲</div>
                    <h4 style="color: #fff; font-size: 16px; font-weight: 800; margin: 0 0 4px 0;">Verify Mobile &amp; Email</h4>
                    <p id="regOtpSubtitle" style="font-size: 12px; color: #94a3b8; margin: 0;">
                        Enter the 6-digit verification code sent to your phone and email.
                    </p>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label class="form-label" style="font-size: 12px; color: #34d399; text-align: center; display: block;">Enter 6-Digit Verification Code *</label>
                    <input type="text" name="otp" id="regOtpInput" class="form-control" placeholder="••••••" maxlength="6" required style="font-size: 22px; font-weight: 800; letter-spacing: 8px; text-align: center; height: 48px; border-color: #10b981;">
                    <div id="regOtpDemoHint" style="display: none; font-size: 11px; color: #34d399; margin-top: 6px; text-align: center;"></div>
                </div>

                <button type="submit" id="btnSubmitRegisterStep2" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 14px; font-weight: 800; background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399;">
                    ✔ Confirm &amp; Create Account
                </button>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 14px; font-size: 12px;">
                    <a href="javascript:void(0)" id="btnBackToRegisterStep1" style="color: #94a3b8; text-decoration: none;">← Edit Details</a>
                    <button type="button" id="btnResendRegOtp" style="background: none; border: none; color: #34d399; font-weight: 700; cursor: pointer; padding: 0;">
                        Resend Code (<span id="regCountdown">60</span>s)
                    </button>
                </div>
            </form>

            <!-- ========================================================
                 FLOW 3: FORGOT PASSWORD (2-STEP OTP RESET)
                 ======================================================== -->
            <!-- Forgot Step 1: Identifier -->
            <form id="formForgotStep1" style="display: none;">
                <input type="hidden" name="action" value="forgot_step1">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">

                <div style="text-align: center; margin-bottom: 16px;">
                    <div style="font-size: 26px; margin-bottom: 4px;">🔑</div>
                    <h4 style="color: #fff; font-size: 16px; font-weight: 800; margin: 0 0 4px 0;">Reset Your Password</h4>
                    <p style="font-size: 12px; color: #94a3b8; margin: 0;">
                        Enter your registered Mobile Number or Email to receive a reset OTP.
                    </p>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-size: 12px;">Mobile Number or Email *</label>
                    <input type="text" name="identifier" id="forgotIdentifier" class="form-control" placeholder="e.g. 9825112345 or email@domain.com" required style="font-size: 13px; padding: 10px 14px;">
                </div>

                <button type="submit" id="btnSubmitForgotStep1" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 14px; font-weight: 800; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-color: #f59e0b;">
                    Send Password Reset OTP →
                </button>

                <div style="text-align: center; margin-top: 14px;">
                    <a href="javascript:void(0)" id="btnBackToLoginFromForgot" style="font-size: 12.5px; color: #94a3b8; text-decoration: none;">← Back to Sign In</a>
                </div>
            </form>

            <!-- Forgot Step 2: Enter OTP & New Password -->
            <form id="formForgotStep2" style="display: none;">
                <input type="hidden" name="action" value="forgot_step2">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">

                <div style="text-align: center; margin-bottom: 16px;">
                    <div style="font-size: 26px; margin-bottom: 4px;">🔐</div>
                    <h4 style="color: #fff; font-size: 16px; font-weight: 800; margin: 0 0 4px 0;">Enter Reset Code</h4>
                    <p id="forgotOtpSubtitle" style="font-size: 12px; color: #94a3b8; margin: 0;">
                        Enter the OTP sent to your phone/email and set your new password.
                    </p>
                </div>

                <div class="form-group" style="margin-bottom: 12px;">
                    <label class="form-label" style="font-size: 12px; color: #fbbf24; text-align: center; display: block;">Enter 6-Digit OTP *</label>
                    <input type="text" name="otp" id="forgotOtpInput" class="form-control" placeholder="••••••" maxlength="6" required style="font-size: 22px; font-weight: 800; letter-spacing: 8px; text-align: center; height: 46px; border-color: #f59e0b;">
                    <div id="forgotOtpDemoHint" style="display: none; font-size: 11px; color: #34d399; margin-top: 4px; text-align: center;"></div>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label class="form-label" style="font-size: 12px;">Set New Password *</label>
                    <input type="password" name="new_password" class="form-control" placeholder="Min. 4 characters" required style="font-size: 13px; padding: 10px 14px;">
                </div>

                <button type="submit" id="btnSubmitForgotStep2" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 14px; font-weight: 800; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-color: #f59e0b;">
                    ✔ Update Password &amp; Sign In
                </button>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 14px; font-size: 12px;">
                    <a href="javascript:void(0)" id="btnBackToForgotStep1" style="color: #94a3b8; text-decoration: none;">← Change Contact</a>
                    <button type="button" id="btnResendForgotOtp" style="background: none; border: none; color: #fbbf24; font-weight: 700; cursor: pointer; padding: 0;">
                        Resend Code (<span id="forgotCountdown">60</span>s)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const authModal = document.getElementById('customerAuthModal');
    const btnClose = document.getElementById('btnAuthModalClose');
    const navTabs = document.getElementById('authNavTabs');
    const tabLogin = document.getElementById('tabBtnLogin');
    const tabRegister = document.getElementById('tabBtnRegister');
    const authNotice = document.getElementById('authNoticeMsg');

    const formLoginStep1 = document.getElementById('formLoginStep1');
    const formLoginStep2 = document.getElementById('formLoginStep2');
    const formRegisterStep1 = document.getElementById('formRegisterStep1');
    const formRegisterStep2 = document.getElementById('formRegisterStep2');
    const formForgotStep1 = document.getElementById('formForgotStep1');
    const formForgotStep2 = document.getElementById('formForgotStep2');

    let loginTimer = null, regTimer = null, forgotTimer = null;

    function hideAllForms() {
        formLoginStep1.style.display = 'none';
        formLoginStep2.style.display = 'none';
        formRegisterStep1.style.display = 'none';
        formRegisterStep2.style.display = 'none';
        formForgotStep1.style.display = 'none';
        formForgotStep2.style.display = 'none';
    }

    function showNotice(text, type = 'info') {
        if (!authNotice) return;
        authNotice.style.display = 'block';
        if (type === 'success') {
            authNotice.style.background = 'rgba(16, 185, 129, 0.15)';
            authNotice.style.border = '1px solid #10b981';
            authNotice.style.color = '#34d399';
        } else if (type === 'error') {
            authNotice.style.background = 'rgba(239, 68, 68, 0.15)';
            authNotice.style.border = '1px solid #ef4444';
            authNotice.style.color = '#f87171';
        } else {
            authNotice.style.background = 'rgba(0, 229, 255, 0.15)';
            authNotice.style.border = '1px solid #00e5ff';
            authNotice.style.color = '#00e5ff';
        }
        authNotice.textContent = text;
    }

    function startCooldown(btnElem, spanElem, duration = 60) {
        btnElem.disabled = true;
        btnElem.style.opacity = '0.5';
        let remaining = duration;
        spanElem.textContent = remaining;

        const interval = setInterval(function() {
            remaining--;
            spanElem.textContent = remaining;
            if (remaining <= 0) {
                clearInterval(interval);
                btnElem.disabled = false;
                btnElem.style.opacity = '1';
                btnElem.innerHTML = 'Resend Code';
            }
        }, 1000);
        return interval;
    }

    // Modal Opening & Closing
    window.openCustomerAuthModal = function(noticeText = '') {
        if (authModal) {
            authModal.style.display = 'flex';
            hideAllForms();
            formLoginStep1.style.display = 'block';
            if (navTabs) navTabs.style.display = 'flex';
            tabLogin.click();
            if (noticeText) showNotice(noticeText, 'info');
            else if (authNotice) authNotice.style.display = 'none';
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

    // Tabs switching
    if (tabLogin && tabRegister) {
        tabLogin.addEventListener('click', function() {
            tabLogin.classList.add('active');
            tabLogin.style.borderBottomColor = 'var(--accent-cyan)';
            tabLogin.style.color = '#fff';
            tabRegister.classList.remove('active');
            tabRegister.style.borderBottomColor = 'transparent';
            tabRegister.style.color = '#94a3b8';
            hideAllForms();
            formLoginStep1.style.display = 'block';
            if (authNotice) authNotice.style.display = 'none';
        });

        tabRegister.addEventListener('click', function() {
            tabRegister.classList.add('active');
            tabRegister.style.borderBottomColor = '#10b981';
            tabRegister.style.color = '#fff';
            tabLogin.classList.remove('active');
            tabLogin.style.borderBottomColor = 'transparent';
            tabLogin.style.color = '#94a3b8';
            hideAllForms();
            formRegisterStep1.style.display = 'block';
            if (authNotice) authNotice.style.display = 'none';
        });
    }

    // 1-Click Fast Demo Login
    const btnDemo = document.getElementById('btnFastDemoLogin');
    if (btnDemo) {
        btnDemo.addEventListener('click', function() {
            btnDemo.disabled = true;
            btnDemo.textContent = 'Signing in Kishorbhai...';
            const fd = new FormData();
            fd.append('action', 'demo_login');
            fd.append('csrf_token', '<?= getCsrfToken() ?>');

            fetch('<?= BASE_URL ?>/auth-customer.php', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        showNotice('✔ ' + d.message, 'success');
                        setTimeout(() => location.reload(), 700);
                    } else {
                        btnDemo.disabled = false;
                        btnDemo.textContent = '⚡ 1-Click Demo Login (Kishorbhai Parmar)';
                        showNotice(d.message, 'error');
                    }
                })
                .catch(() => {
                    btnDemo.disabled = false;
                    btnDemo.textContent = '⚡ 1-Click Demo Login (Kishorbhai Parmar)';
                });
        });
    }

    // -------------------------------------------------------------
    // FLOW 1: LOGIN (STEP 1: PASSWORD -> STEP 2: OTP)
    // -------------------------------------------------------------
    formLoginStep1.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmitLoginStep1');
        btn.disabled = true;
        btn.textContent = 'Verifying Password...';

        const fd = new FormData(formLoginStep1);
        fetch('<?= BASE_URL ?>/auth-customer.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false;
                btn.textContent = 'Verify Password & Continue →';
                if (data.success && data.step === 'otp_required') {
                    // Password is correct! Amazon-style transition to Step 2 OTP
                    formLoginStep1.style.display = 'none';
                    if (navTabs) navTabs.style.display = 'none';
                    formLoginStep2.style.display = 'block';

                    const subtitle = document.getElementById('loginOtpSubtitle');
                    if (subtitle && data.masked_contact) {
                        subtitle.innerHTML = 'Enter the 6-digit OTP code sent to <strong style="color:#00e5ff;">' + data.masked_contact + '</strong>';
                    }

                    const otpInput = document.getElementById('loginOtpInput');
                    if (otpInput) {
                        otpInput.value = data.demo_otp || '';
                        otpInput.focus();
                    }

                    const hint = document.getElementById('loginOtpDemoHint');
                    if (hint && data.demo_otp) {
                        hint.style.display = 'block';
                        hint.innerHTML = '✔ OTP dispatched! Testing PIN: <strong>' + data.demo_otp + '</strong>';
                    }

                    showNotice(data.message, 'success');
                    loginTimer = startCooldown(document.getElementById('btnResendLoginOtp'), document.getElementById('loginCountdown'));
                } else {
                    showNotice(data.message || 'Invalid Mobile/Email or Password.', 'error');
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.textContent = 'Verify Password & Continue →';
                showNotice('Server connection error. Please try again.', 'error');
            });
    });

    formLoginStep2.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmitLoginStep2');
        btn.disabled = true;
        btn.textContent = 'Verifying OTP...';

        const fd = new FormData(formLoginStep2);
        fetch('<?= BASE_URL ?>/auth-customer.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showNotice('✔ ' + data.message, 'success');
                    setTimeout(() => location.reload(), 700);
                } else {
                    btn.disabled = false;
                    btn.textContent = '✔ Verify OTP & Sign In';
                    showNotice(data.message || 'Incorrect OTP code.', 'error');
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.textContent = '✔ Verify OTP & Sign In';
                showNotice('Server connection error.', 'error');
            });
    });

    document.getElementById('btnBackToPassword').addEventListener('click', function() {
        formLoginStep2.style.display = 'none';
        formLoginStep1.style.display = 'block';
        if (navTabs) navTabs.style.display = 'flex';
        if (authNotice) authNotice.style.display = 'none';
    });

    document.getElementById('btnResendLoginOtp').addEventListener('click', function() {
        const fd = new FormData();
        fd.append('action', 'resend_otp');
        fd.append('flow', 'login');
        fd.append('csrf_token', '<?= getCsrfToken() ?>');

        fetch('<?= BASE_URL ?>/auth-customer.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    showNotice(d.message, 'success');
                    const otpInput = document.getElementById('loginOtpInput');
                    if (otpInput && d.demo_otp) otpInput.value = d.demo_otp;
                    loginTimer = startCooldown(document.getElementById('btnResendLoginOtp'), document.getElementById('loginCountdown'));
                } else {
                    showNotice(d.message, 'error');
                }
            });
    });

    // -------------------------------------------------------------
    // FLOW 2: SIGN UP (STEP 1: DETAILS -> STEP 2: OTP)
    // -------------------------------------------------------------
    formRegisterStep1.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmitRegisterStep1');
        btn.disabled = true;
        btn.textContent = 'Validating & Sending OTP...';

        const fd = new FormData(formRegisterStep1);
        fetch('<?= BASE_URL ?>/auth-customer.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false;
                btn.textContent = 'Continue & Verify Mobile/Email →';
                if (data.success && data.step === 'otp_required') {
                    formRegisterStep1.style.display = 'none';
                    if (navTabs) navTabs.style.display = 'none';
                    formRegisterStep2.style.display = 'block';

                    const subtitle = document.getElementById('regOtpSubtitle');
                    if (subtitle && data.masked_contact) {
                        subtitle.innerHTML = 'Enter the 6-digit code sent to <strong style="color:#10b981;">' + data.masked_contact + '</strong>';
                    }

                    const otpInput = document.getElementById('regOtpInput');
                    if (otpInput) {
                        otpInput.value = data.demo_otp || '';
                        otpInput.focus();
                    }

                    const hint = document.getElementById('regOtpDemoHint');
                    if (hint && data.demo_otp) {
                        hint.style.display = 'block';
                        hint.innerHTML = '✔ OTP code dispatched! Testing PIN: <strong>' + data.demo_otp + '</strong>';
                    }

                    showNotice(data.message, 'success');
                    regTimer = startCooldown(document.getElementById('btnResendRegOtp'), document.getElementById('regCountdown'));
                } else {
                    showNotice(data.message || 'Validation error.', 'error');
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.textContent = 'Continue & Verify Mobile/Email →';
                showNotice('Server connection error.', 'error');
            });
    });

    formRegisterStep2.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmitRegisterStep2');
        btn.disabled = true;
        btn.textContent = 'Activating Account...';

        const fd = new FormData(formRegisterStep2);
        fetch('<?= BASE_URL ?>/auth-customer.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showNotice('✔ ' + data.message, 'success');
                    setTimeout(() => location.reload(), 700);
                } else {
                    btn.disabled = false;
                    btn.textContent = '✔ Confirm & Create Account';
                    showNotice(data.message || 'Incorrect verification code.', 'error');
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.textContent = '✔ Confirm & Create Account';
                showNotice('Server connection error.', 'error');
            });
    });

    document.getElementById('btnBackToRegisterStep1').addEventListener('click', function() {
        formRegisterStep2.style.display = 'none';
        formRegisterStep1.style.display = 'block';
        if (navTabs) navTabs.style.display = 'flex';
        if (authNotice) authNotice.style.display = 'none';
    });

    document.getElementById('btnResendRegOtp').addEventListener('click', function() {
        const fd = new FormData();
        fd.append('action', 'resend_otp');
        fd.append('flow', 'register');
        fd.append('csrf_token', '<?= getCsrfToken() ?>');

        fetch('<?= BASE_URL ?>/auth-customer.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    showNotice(d.message, 'success');
                    const otpInput = document.getElementById('regOtpInput');
                    if (otpInput && d.demo_otp) otpInput.value = d.demo_otp;
                    regTimer = startCooldown(document.getElementById('btnResendRegOtp'), document.getElementById('regCountdown'));
                } else {
                    showNotice(d.message, 'error');
                }
            });
    });

    // -------------------------------------------------------------
    // FLOW 3: FORGOT PASSWORD (STEP 1: CONTACT -> STEP 2: OTP + NEW PASS)
    // -------------------------------------------------------------
    document.getElementById('linkForgotPassword').addEventListener('click', function() {
        hideAllForms();
        if (navTabs) navTabs.style.display = 'none';
        formForgotStep1.style.display = 'block';
        if (authNotice) authNotice.style.display = 'none';
        const loginId = document.getElementById('loginIdentifier');
        const forgotId = document.getElementById('forgotIdentifier');
        if (loginId && forgotId && loginId.value) forgotId.value = loginId.value;
    });

    document.getElementById('btnBackToLoginFromForgot').addEventListener('click', function() {
        hideAllForms();
        if (navTabs) navTabs.style.display = 'flex';
        formLoginStep1.style.display = 'block';
        if (authNotice) authNotice.style.display = 'none';
    });

    formForgotStep1.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmitForgotStep1');
        btn.disabled = true;
        btn.textContent = 'Sending Reset OTP...';

        const fd = new FormData(formForgotStep1);
        fetch('<?= BASE_URL ?>/auth-customer.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false;
                btn.textContent = 'Send Password Reset OTP →';
                if (data.success && data.step === 'otp_required') {
                    formForgotStep1.style.display = 'none';
                    formForgotStep2.style.display = 'block';

                    const subtitle = document.getElementById('forgotOtpSubtitle');
                    if (subtitle && data.masked_contact) {
                        subtitle.innerHTML = 'Enter the reset code sent to <strong style="color:#fbbf24;">' + data.masked_contact + '</strong> and choose a new password.';
                    }

                    const otpInput = document.getElementById('forgotOtpInput');
                    if (otpInput) {
                        otpInput.value = data.demo_otp || '';
                        otpInput.focus();
                    }

                    const hint = document.getElementById('forgotOtpDemoHint');
                    if (hint && data.demo_otp) {
                        hint.style.display = 'block';
                        hint.innerHTML = '✔ Reset PIN dispatched! Testing PIN: <strong>' + data.demo_otp + '</strong>';
                    }

                    showNotice(data.message, 'success');
                    forgotTimer = startCooldown(document.getElementById('btnResendForgotOtp'), document.getElementById('forgotCountdown'));
                } else {
                    showNotice(data.message || 'Account not found.', 'error');
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.textContent = 'Send Password Reset OTP →';
                showNotice('Server connection error.', 'error');
            });
    });

    formForgotStep2.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmitForgotStep2');
        btn.disabled = true;
        btn.textContent = 'Updating Password...';

        const fd = new FormData(formForgotStep2);
        fetch('<?= BASE_URL ?>/auth-customer.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false;
                btn.textContent = '✔ Update Password & Sign In';
                if (data.success) {
                    showNotice('✔ ' + data.message, 'success');
                    setTimeout(() => {
                        hideAllForms();
                        if (navTabs) navTabs.style.display = 'flex';
                        formLoginStep1.style.display = 'block';
                        tabLogin.click();
                    }, 1200);
                } else {
                    showNotice(data.message || 'Error updating password.', 'error');
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.textContent = '✔ Update Password & Sign In';
                showNotice('Server connection error.', 'error');
            });
    });

    document.getElementById('btnBackToForgotStep1').addEventListener('click', function() {
        formForgotStep2.style.display = 'none';
        formForgotStep1.style.display = 'block';
        if (authNotice) authNotice.style.display = 'none';
    });

    document.getElementById('btnResendForgotOtp').addEventListener('click', function() {
        const fd = new FormData();
        fd.append('action', 'resend_otp');
        fd.append('flow', 'forgot');
        fd.append('csrf_token', '<?= getCsrfToken() ?>');

        fetch('<?= BASE_URL ?>/auth-customer.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    showNotice(d.message, 'success');
                    const otpInput = document.getElementById('forgotOtpInput');
                    if (otpInput && d.demo_otp) otpInput.value = d.demo_otp;
                    forgotTimer = startCooldown(document.getElementById('btnResendForgotOtp'), document.getElementById('forgotCountdown'));
                } else {
                    showNotice(d.message, 'error');
                }
            });
    });
});
</script>
