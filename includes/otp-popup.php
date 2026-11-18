<?php
/**
 * Universal Responsive OTP Pop-Up Menu Component
 * Shree Varsha Engineering Works - Varsha Brand
 * 
 * Displays dynamically generated 6-digit verification PINs in a dedicated
 * industrial glassmorphic pop-up modal for Admin 2FA, Customer Login,
 * Customer Sign Up, and Password Reset.
 */
?>
<!-- Universal OTP Notification Pop-Up Modal -->
<div id="varshaOtpPopupModal" class="otp-popup-overlay" style="display: none;">
    <div class="otp-popup-card" role="dialog" aria-modal="true" aria-labelledby="otpPopupTitle">
        <!-- Top Glow Accent -->
        <div class="otp-popup-glow"></div>

        <!-- Header -->
        <div class="otp-popup-header">
            <div class="otp-popup-brand">
                <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand" class="otp-popup-logo">
                <div>
                    <h3 id="otpPopupTitle" class="otp-popup-title">🔐 SECURITY VERIFICATION PIN</h3>
                    <div id="otpPopupSubtitle" class="otp-popup-subtitle">Live One-Time Password (OTP)</div>
                </div>
            </div>
            <button type="button" id="btnOtpPopupClose" class="otp-popup-close-btn" title="Close" aria-label="Close">&times;</button>
        </div>

        <!-- Body -->
        <div class="otp-popup-body">
            <!-- Context / Recipient Info -->
            <div class="otp-popup-meta">
                <div class="otp-meta-row">
                    <span class="otp-meta-label">Action:</span>
                    <span id="otpPopupPurpose" class="otp-meta-val badge-purpose">Customer Login 2FA</span>
                </div>
                <div class="otp-meta-row" id="otpRecipientRow">
                    <span class="otp-meta-label">Dispatched To:</span>
                    <span id="otpPopupRecipient" class="otp-meta-val">Registered Mobile &amp; Email</span>
                </div>
            </div>

            <!-- Big Glowing Digits Display -->
            <div class="otp-digits-wrapper">
                <div class="otp-digits-label">YOUR 6-DIGIT VERIFICATION CODE</div>
                <div id="otpDigitsContainer" class="otp-digits-container">
                    <span class="otp-digit">0</span>
                    <span class="otp-digit">0</span>
                    <span class="otp-digit">0</span>
                    <span class="otp-digit">0</span>
                    <span class="otp-digit">0</span>
                    <span class="otp-digit">0</span>
                </div>
                <div class="otp-digits-notice">
                    <span>⏱️ Valid for 10 minutes</span>
                    <span>•</span>
                    <span style="color: #34d399;">🔒 100% Unique &amp; Dynamic</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="otp-popup-actions">
                <button type="button" id="btnOtpPopupCopy" class="btn-otp-action btn-copy">
                    <span id="otpCopyIcon">📋</span> <span id="otpCopyText">Copy Code</span>
                </button>
                <button type="button" id="btnOtpPopupFill" class="btn-otp-action btn-fill">
                    ⚡ Auto-Fill in Input
                </button>
            </div>
            
            <div style="text-align: center; margin-top: 10px;">
                <button type="button" id="btnOtpPopupDismiss" class="otp-btn-dismiss">
                    ✕ Dismiss &amp; Enter Manually
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* OTP Pop-Up Menu Styles (Fully Responsive) */
.otp-popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(3, 7, 18, 0.88);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    z-index: 100050;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
    box-sizing: border-box;
}

.otp-popup-card {
    background: #090e1a;
    border: 1.5px solid #00e5ff;
    border-radius: 18px;
    width: 100%;
    max-width: 440px;
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.95), 0 0 40px rgba(0, 229, 255, 0.28);
    overflow: hidden;
    position: relative;
    animation: otpModalPopIn 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    box-sizing: border-box;
}

@keyframes otpModalPopIn {
    from { opacity: 0; transform: scale(0.92) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.otp-popup-glow {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #00e5ff, #38bdf8, #10b981, #00e5ff);
    background-size: 200% 100%;
    animation: otpGlowMove 3s linear infinite;
}

@keyframes otpGlowMove {
    0% { background-position: 0% 50%; }
    100% { background-position: 200% 50%; }
}

.otp-popup-header {
    background: linear-gradient(135deg, rgba(15, 23, 42, 0.96) 0%, rgba(6, 10, 20, 0.98) 100%);
    border-bottom: 1px solid rgba(0, 229, 255, 0.25);
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.otp-popup-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
    flex: 1;
}

.otp-popup-logo {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: 1.5px solid #00e5ff;
    object-fit: contain;
    background: #060911;
    flex-shrink: 0;
    box-shadow: 0 0 12px rgba(0, 229, 255, 0.35);
}

.otp-popup-title {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 14px;
    font-weight: 800;
    color: #fff;
    margin: 0;
    letter-spacing: 0.5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.otp-popup-subtitle {
    font-size: 11px;
    color: #38bdf8;
    font-weight: 600;
    letter-spacing: 0.3px;
}

.otp-popup-close-btn {
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 26px;
    cursor: pointer;
    line-height: 1;
    padding: 0 4px;
    transition: color 0.2s;
    flex-shrink: 0;
}
.otp-popup-close-btn:hover {
    color: #00e5ff;
}

.otp-popup-body {
    padding: 20px 22px;
}

.otp-popup-meta {
    background: rgba(15, 23, 42, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    padding: 10px 14px;
    margin-bottom: 18px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.otp-meta-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 12px;
    gap: 8px;
}

.otp-meta-label {
    color: #94a3b8;
    font-weight: 600;
    flex-shrink: 0;
}

.otp-meta-val {
    color: #fff;
    font-weight: 700;
    text-align: right;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.badge-purpose {
    background: rgba(0, 229, 255, 0.15);
    border: 1px solid rgba(0, 229, 255, 0.4);
    color: #00e5ff;
    padding: 2px 8px;
    border-radius: 6px;
    font-size: 11px;
}

.otp-digits-wrapper {
    background: rgba(6, 10, 20, 0.85);
    border: 1.5px dashed rgba(0, 229, 255, 0.4);
    border-radius: 14px;
    padding: 18px 14px;
    text-align: center;
    margin-bottom: 18px;
}

.otp-digits-label {
    font-size: 10.5px;
    font-weight: 800;
    color: #94a3b8;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    margin-bottom: 12px;
}

.otp-digits-container {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-bottom: 12px;
}

.otp-digit {
    width: 44px;
    height: 52px;
    background: rgba(15, 23, 42, 0.95);
    border: 1.5px solid #00e5ff;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Space Grotesk', monospace, sans-serif;
    font-size: 26px;
    font-weight: 900;
    color: #ffffff;
    box-shadow: 0 4px 15px rgba(0, 229, 255, 0.25), inset 0 0 10px rgba(0, 229, 255, 0.1);
    transition: all 0.2s ease;
}

.otp-digits-notice {
    font-size: 11px;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.otp-popup-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.btn-otp-action {
    padding: 11px 14px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 800;
    cursor: pointer;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.2s ease;
}

.btn-otp-action.btn-copy {
    background: rgba(255, 255, 255, 0.08);
    border: 1.5px solid rgba(255, 255, 255, 0.25);
    color: #fff;
}
.btn-otp-action.btn-copy:hover {
    background: rgba(255, 255, 255, 0.15);
    border-color: #fff;
}

.btn-otp-action.btn-fill {
    background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%);
    color: #060911;
    border: 1.5px solid #00e5ff;
    box-shadow: 0 4px 15px rgba(0, 229, 255, 0.35);
}
.btn-otp-action.btn-fill:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(0, 229, 255, 0.5);
}

.otp-btn-dismiss {
    background: none;
    border: none;
    color: #64748b;
    font-size: 11.5px;
    cursor: pointer;
    padding: 6px;
    transition: color 0.2s;
    text-decoration: underline;
}
.otp-btn-dismiss:hover {
    color: #94a3b8;
}

/* Mobile & Tablet Responsiveness */
@media (max-width: 480px) {
    .otp-popup-card {
        max-width: calc(100vw - 24px);
        border-radius: 14px;
    }
    .otp-popup-header {
        padding: 12px 14px;
    }
    .otp-popup-title {
        font-size: 12.5px;
    }
    .otp-popup-subtitle {
        font-size: 10px;
    }
    .otp-popup-body {
        padding: 14px 16px;
    }
    .otp-digits-wrapper {
        padding: 14px 8px;
    }
    .otp-digit {
        width: 36px;
        height: 44px;
        font-size: 21px;
        border-radius: 7px;
    }
    .otp-digits-container {
        gap: 5px;
    }
    .otp-popup-actions {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    .btn-otp-action {
        padding: 10px;
        font-size: 12.5px;
    }
}
</style>

<script>
// Universal OTP Pop-Up Menu Controller
(function() {
    let currentOtp = '';
    let currentTargetInputId = '';

    window.showVarshaOtpPopup = function(options) {
        options = options || {};
        currentOtp = String(options.otp || '000000').trim();
        currentTargetInputId = options.targetInputId || '';

        const modal = document.getElementById('varshaOtpPopupModal');
        const titleEl = document.getElementById('otpPopupTitle');
        const purposeEl = document.getElementById('otpPopupPurpose');
        const recipientEl = document.getElementById('otpPopupRecipient');
        const recipientRow = document.getElementById('otpRecipientRow');
        const digitsBox = document.getElementById('otpDigitsContainer');

        if (titleEl && options.title) titleEl.textContent = options.title;
        if (purposeEl) purposeEl.textContent = options.purpose || 'Verification Code';
        
        if (recipientEl && options.recipient) {
            recipientEl.textContent = options.recipient;
            if (recipientRow) recipientRow.style.display = 'flex';
        } else if (recipientRow && !options.recipient) {
            recipientRow.style.display = 'none';
        }

        // Render 6 digits
        if (digitsBox) {
            digitsBox.innerHTML = '';
            for (let i = 0; i < 6; i++) {
                const char = currentOtp[i] || '0';
                const span = document.createElement('span');
                span.className = 'otp-digit';
                span.textContent = char;
                digitsBox.appendChild(span);
            }
        }

        // Reset copy button
        const copyText = document.getElementById('otpCopyText');
        const copyIcon = document.getElementById('otpCopyIcon');
        if (copyText) copyText.textContent = 'Copy Code';
        if (copyIcon) copyIcon.textContent = '📋';

        if (modal) {
            modal.style.display = 'flex';
        }
    };

    window.closeVarshaOtpPopup = function() {
        const modal = document.getElementById('varshaOtpPopupModal');
        if (modal) modal.style.display = 'none';
    };

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('varshaOtpPopupModal');
        const btnClose = document.getElementById('btnOtpPopupClose');
        const btnDismiss = document.getElementById('btnOtpPopupDismiss');
        const btnCopy = document.getElementById('btnOtpPopupCopy');
        const btnFill = document.getElementById('btnOtpPopupFill');

        function getTargetOtpInput() {
            if (currentTargetInputId) {
                const el = document.getElementById(currentTargetInputId);
                if (el) return el;
            }
            return document.getElementById('adminOtpInput') ||
                   document.getElementById('loginOtpInput') ||
                   document.getElementById('regOtpInput') ||
                   document.getElementById('forgotOtpInput') ||
                   document.querySelector('input[name="otp"]');
        }

        function showOtpCopyToast(msg) {
            let toast = document.getElementById('varshaOtpCopyToast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'varshaOtpCopyToast';
                toast.setAttribute('style', 'position: fixed; bottom: 28px; left: 50%; transform: translateX(-50%) translateY(30px); background: rgba(15, 23, 42, 0.96); border: 1.5px solid #00e5ff; color: #fff; padding: 12px 22px; border-radius: 12px; font-family: "Space Grotesk", -apple-system, sans-serif; font-size: 13.5px; font-weight: 700; box-shadow: 0 12px 35px rgba(0,0,0,0.85), 0 0 20px rgba(0,229,255,0.35); z-index: 100060; display: flex; align-items: center; gap: 10px; opacity: 0; transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1); pointer-events: none; max-width: 90vw; text-align: center; box-sizing: border-box;');
                document.body.appendChild(toast);
            }
            toast.innerHTML = '<span style="font-size: 16px;">📋</span><span>' + msg + '</span>';
            requestAnimationFrame(function() {
                toast.style.opacity = '1';
                toast.style.transform = 'translateX(-50%) translateY(0)';
            });
            clearTimeout(toast._timer);
            toast._timer = setTimeout(function() {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(-50%) translateY(30px)';
            }, 3200);
        }

        function fallbackClipboardCopy(text) {
            try {
                const ta = document.createElement('textarea');
                ta.value = text;
                ta.setAttribute('readonly', '');
                ta.style.position = 'fixed';
                ta.style.left = '-9999px';
                ta.style.top = '0';
                ta.style.opacity = '0';
                document.body.appendChild(ta);
                ta.focus();
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
            } catch (e) {
                console.warn('Fallback clipboard copy failed:', e);
            }
        }

        if (btnClose) btnClose.addEventListener('click', window.closeVarshaOtpPopup);
        if (btnDismiss) {
            btnDismiss.addEventListener('click', function() {
                const input = getTargetOtpInput();
                if (input) input.focus();
                window.closeVarshaOtpPopup();
            });
        }

        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) window.closeVarshaOtpPopup();
            });
        }

        // Copy Code to Clipboard and automatically transition to OTP fill-up page
        if (btnCopy) {
            btnCopy.addEventListener('click', function() {
                if (!currentOtp) {
                    window.closeVarshaOtpPopup();
                    return;
                }

                function onCopyComplete() {
                    const input = getTargetOtpInput();
                    if (input) {
                        input.focus();
                    }
                    window.closeVarshaOtpPopup();
                    showOtpCopyToast('OTP code copied to clipboard! Paste in the input.');
                    if (typeof showNotice === 'function') {
                        try {
                            showNotice('📋 OTP Code copied to clipboard! (૧૦૦% કૉપિ થઈ ગયો છે)', 'success');
                        } catch(e) {}
                    }
                }

                if (navigator.clipboard && window.isSecureContext && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(currentOtp).then(function() {
                        onCopyComplete();
                    }).catch(function() {
                        fallbackClipboardCopy(currentOtp);
                        onCopyComplete();
                    });
                } else {
                    fallbackClipboardCopy(currentOtp);
                    onCopyComplete();
                }
            });
        }

        // Auto-Fill in Input field and transition to OTP fill-up page
        if (btnFill) {
            btnFill.addEventListener('click', function() {
                const input = getTargetOtpInput();
                if (input) {
                    input.value = currentOtp;
                    input.focus();
                    // Trigger input event
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                }
                window.closeVarshaOtpPopup();
            });
        }

        // Close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal && modal.style.display === 'flex') {
                window.closeVarshaOtpPopup();
            }
        });
    });
})();
</script>
