<!-- Varsha Brand PWA Native Mobile & Desktop App Download Modal -->
<div id="appDownloadModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(4, 7, 13, 0.94); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); z-index: 100050; align-items: center; justify-content: center; padding: 16px; overflow-y: auto;">
    <div style="max-width: 540px; width: 100%; max-height: 90vh; overflow-y: auto; padding: 26px 24px; background: #0b1120; border: 2px solid var(--accent-cyan); box-shadow: 0 25px 70px rgba(0,0,0,0.9), 0 0 35px rgba(0,229,255,0.3); border-radius: 18px; position: relative;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 18px; border-bottom: 1px solid rgba(0, 229, 255, 0.2); padding-bottom: 14px;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand" style="height: 52px; width: 52px; border-radius: 50%; border: 2px solid var(--accent-cyan); box-shadow: 0 0 15px rgba(0,229,255,0.4); object-fit: cover; flex-shrink: 0;">
                <div>
                    <h3 style="font-family: 'Space Grotesk', sans-serif; font-size: 19px; font-weight: 800; color: #fff; margin: 0; line-height: 1.2;">
                        Varsha Drill Machine App
                    </h3>
                    <div style="font-size: 12px; color: #34d399; font-weight: 700; margin-top: 3px;">
                        ★ Official Factory Web App (Android, PC &amp; iOS)
                    </div>
                </div>
            </div>
            <button type="button" onclick="closeAppDownloadModal()" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #cbd5e1; font-size: 22px; cursor: pointer; line-height: 1; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='rgba(239,68,68,0.2)'; this.style.color='#f87171'" onmouseout="this.style.background='rgba(255,255,255,0.08)'; this.style.color='#cbd5e1'">&times;</button>
        </div>

        <p style="font-size: 13.5px; color: #cbd5e1; line-height: 1.6; margin-bottom: 18px;">
            ઇન્ટરનેટ વગર પણ ઝડપથી મશીનો જોવા, 360° સિમ્યુલેટર ચલાવવા, ઓનલાઇન બુકિંગ કરવા અને લાઇવ ડિલિવરી ટ્રેક કરવા માટે <strong>વરષા બ્રાન્ડ એપ</strong> તમારા મોબાઇલ કે કમ્પ્યુટરમાં ઇન્સ્ટોલ કરો:
        </p>

        <!-- Direct Instant Install / Download Button -->
        <button type="button" id="btnNativeInstallApp" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 15px; font-weight: 800; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color: #060911; border-color: #00e5ff; box-shadow: 0 8px 25px rgba(0,229,255,0.35); margin-bottom: 14px; display: flex; align-items: center; justify-content: center; gap: 10px; cursor: pointer;">
            <span style="font-size: 18px;">📲</span>
            <span id="installBtnText">Install App Now (ઇન્સ્ટોલ કરો)</span>
        </button>

        <!-- Direct Web App Launcher Download -->
        <div style="display: flex; gap: 10px; margin-bottom: 18px;">
            <a href="<?= BASE_URL ?>/index.php?source=pwa" id="btnDirectPwaLaunch" class="btn btn-outline" style="flex: 1; padding: 10px 14px; font-size: 12.5px; border-color: #38bdf8; color: #38bdf8; display: flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none; font-weight: 700;">
                <span>🚀 Launch Fullscreen App</span>
            </a>
            <a href="<?= BASE_URL ?>/manifest.json" download="varsha-drill-app.webmanifest" class="btn btn-outline" style="flex: 1; padding: 10px 14px; font-size: 12.5px; border-color: #34d399; color: #34d399; display: flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none; font-weight: 700;">
                <span>⬇️ Save App Config</span>
            </a>
        </div>

        <!-- Device Wise Easy Guides -->
        <div style="background: rgba(15, 23, 42, 0.9); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 16px;">
            <div style="font-size: 12px; font-weight: 800; color: #38bdf8; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                <span>💡</span>
                <span>ઇન્સ્ટોલ કરવાની સરળ રીતો (Quick Install Guide):</span>
            </div>

            <!-- Android Guide -->
            <div style="display: flex; gap: 12px; align-items: flex-start; margin-bottom: 12px; padding: 8px 10px; background: rgba(255,255,255,0.03); border-radius: 8px;">
                <span style="font-size: 22px; flex-shrink: 0;">🤖</span>
                <div style="font-size: 12.5px; color: #e2e8f0; line-height: 1.45;">
                    <strong style="color: #34d399;">Android (Chrome/Edge):</strong> ઉપર આપેલા બટન પર ક્લિક કરો. અથવા બ્રાઉઝરના ઉપર જમણી બાજુના <strong>3 ટપકાં (⋮)</strong> પર ક્લિક કરીને <strong>"Install App"</strong> અથવા <strong>"Add to Home screen" (હોમ સ્ક્રીન પર ઉમેરો)</strong> દબાવો.
                </div>
            </div>

            <!-- Windows PC Guide -->
            <div style="display: flex; gap: 12px; align-items: flex-start; margin-bottom: 12px; padding: 8px 10px; background: rgba(255,255,255,0.03); border-radius: 8px;">
                <span style="font-size: 22px; flex-shrink: 0;">💻</span>
                <div style="font-size: 12.5px; color: #e2e8f0; line-height: 1.45;">
                    <strong style="color: #38bdf8;">Windows / PC (Chrome/Edge):</strong> બ્રાઉઝરના URL એડ્રેસ બારમાં ઉપર દેખાતા <strong>(⊕ Install)</strong> અથવા <strong>App Available</strong> આઇકોન પર ક્લિક કરો.
                </div>
            </div>

            <!-- iPhone iOS Guide -->
            <div style="display: flex; gap: 12px; align-items: flex-start; padding: 8px 10px; background: rgba(255,255,255,0.03); border-radius: 8px;">
                <span style="font-size: 22px; flex-shrink: 0;">🍏</span>
                <div style="font-size: 12.5px; color: #e2e8f0; line-height: 1.45;">
                    <strong style="color: #facc15;">iPhone / iPad (Safari):</strong> નીચે શેર બટન <strong>(⎙ Share)</strong> દબાવી <strong>"Add to Home Screen"</strong> પર ક્લિક કરો.
                </div>
            </div>
        </div>

        <div style="margin-top: 16px; text-align: center; font-size: 11.5px; color: #94a3b8; display: flex; align-items: center; justify-content: center; gap: 8px;">
            <span>🔒</span>
            <span>100% Safe &amp; Verified Factory App • Direct Installation • Fast &amp; Offline Ready</span>
        </div>
    </div>
</div>

<script>
let deferredAppPrompt = null;

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredAppPrompt = e;
    
    // Highlight all App download buttons on the site
    document.querySelectorAll('.btn-download-app-trigger').forEach(btn => {
        btn.classList.add('has-install-prompt');
    });

    const installBtnText = document.getElementById('installBtnText');
    if (installBtnText) {
        installBtnText.textContent = "⚡ One-Click Native Install (હવે ઇન્સ્ટોલ કરો)";
    }
});

window.addEventListener('appinstalled', () => {
    deferredAppPrompt = null;
    closeAppDownloadModal();
    alert("🎉 Varsha Drill Machine App successfully installed!");
});

function openAppDownloadModal() {
    const modal = document.getElementById('appDownloadModal');
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}
window.openAppDownloadModal = openAppDownloadModal;

function closeAppDownloadModal() {
    const modal = document.getElementById('appDownloadModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}
window.closeAppDownloadModal = closeAppDownloadModal;

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('appDownloadModal');
    const installBtn = document.getElementById('btnNativeInstallApp');

    // Attach click listener to all download triggers across the site
    document.querySelectorAll('.btn-download-app-trigger').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            openAppDownloadModal();
        });
    });

    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeAppDownloadModal();
            }
        });
    }

    if (installBtn) {
        installBtn.addEventListener('click', async function() {
            if (deferredAppPrompt) {
                deferredAppPrompt.prompt();
                const { outcome } = await deferredAppPrompt.userChoice;
                if (outcome === 'accepted') {
                    closeAppDownloadModal();
                }
                deferredAppPrompt = null;
            } else {
                // If native prompt is not supported or already triggered
                const userAgent = navigator.userAgent || '';
                const isIOS = /iPad|iPhone|iPod/.test(userAgent) && !window.MSStream;
                if (isIOS) {
                    alert("iPhone / iPad માં ઇન્સ્ટોલ કરવા માટે:\n\nનીચે આપેલા Safari શેર બટન (⎙ Share) પર ક્લિક કરો અને 'Add to Home Screen' પસંદ કરો.");
                } else {
                    alert("તમારા બ્રાઉઝરના ઉપરના મેનૂ (3 ટપકાં ⋮) માં જઈને 'Install App' અથવા 'Add to Home Screen' પર ક્લિક કરો.\n\nઆનાથી Varsha Brand App તમારા ફોન કે કમ્પ્યુટરની હોમ સ્ક્રીન પર સેવ થઈ જશે!");
                }
            }
        });
    }
});
</script>