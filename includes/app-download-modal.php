<!-- Varsha Brand PWA Native Mobile & Desktop App Download Modal -->
<div id="appDownloadModal" class="customer-auth-overlay" style="display: none; align-items: center; justify-content: center; z-index: 10001;">
    <div class="customer-auth-card" style="max-width: 520px; width: 92%; padding: 28px; background: #0b1120; border: 2px solid var(--accent-cyan); box-shadow: 0 20px 60px rgba(0,0,0,0.8), 0 0 30px rgba(0,229,255,0.25); border-radius: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand" style="height: 54px; width: 54px; border-radius: 50%; border: 2px solid var(--accent-cyan); box-shadow: 0 0 15px rgba(0,229,255,0.35); object-fit: cover;">
                <div>
                    <h3 style="font-family: 'Space Grotesk', sans-serif; font-size: 19px; font-weight: 800; color: #fff; margin: 0; line-height: 1.2;">
                        Varsha Drill Machine App
                    </h3>
                    <div style="font-size: 11.5px; color: #34d399; font-weight: 700; margin-top: 3px;">
                        ★ Official Factory Web App (Android, PC &amp; iOS)
                    </div>
                </div>
            </div>
            <button type="button" id="closeAppModalBtn" style="background: transparent; border: none; color: #94a3b8; font-size: 26px; cursor: pointer; line-height: 1;">&times;</button>
        </div>

        <p style="font-size: 13.5px; color: #cbd5e1; line-height: 1.6; margin-bottom: 20px;">
            ઇન્ટરનેટ વગર પણ ઝડપથી મશીનો જોવા, 360° સિમ્યુલેટર ચલાવવા, ઓનલાઇન બુકિંગ કરવા અને લાઇવ ડિલિવરી ટ્રેક કરવા માટે <strong>વરષા બ્રાન્ડ એપ</strong> તમારા મોબાઇલ કે કમ્પ્યુટરમાં ઇન્સ્ટોલ કરો:
        </p>

        <!-- Direct Instant Install Button (Triggers Native PWA Prompt) -->
        <button type="button" id="btnNativeInstallApp" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 15px; font-weight: 800; background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color: #060911; border-color: #00e5ff; box-shadow: 0 8px 25px rgba(0,229,255,0.35); margin-bottom: 20px; display: flex; align-items: center; justify-content: center; gap: 10px;">
            <span style="font-size: 18px;">📲</span>
            <span>Install App on this Device (ઇન્સ્ટોલ કરો)</span>
        </button>

        <!-- Device Wise Easy Guides -->
        <div style="background: rgba(15, 23, 42, 0.85); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 16px;">
            <div style="font-size: 12px; font-weight: 800; color: #38bdf8; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 12px;">
                ઇન્સ્ટોલ કરવાની સરળ રીતો (Quick Install Guide):
            </div>

            <!-- Android Guide -->
            <div style="display: flex; gap: 12px; align-items: flex-start; margin-bottom: 12px;">
                <span style="font-size: 20px;">🤖</span>
                <div style="font-size: 12.5px; color: #e2e8f0; line-height: 1.45;">
                    <strong style="color: #34d399;">Android (Chrome/Edge):</strong> બ્રાઉઝરના ઉપર જમણી બાજુના <strong>3 ટપકાં (⋮)</strong> પર ક્લિક કરો અને <strong>"Install App"</strong> અથવા <strong>"Add to Home screen" (હોમ સ્ક્રીન પર ઉમેરો)</strong> દબાવો.
                </div>
            </div>

            <!-- Windows PC Guide -->
            <div style="display: flex; gap: 12px; align-items: flex-start; margin-bottom: 12px;">
                <span style="font-size: 20px;">💻</span>
                <div style="font-size: 12.5px; color: #e2e8f0; line-height: 1.45;">
                    <strong style="color: #38bdf8;">Windows / PC (Chrome/Edge):</strong> બ્રાઉઝરના URL એડ્રેસ બારમાં ઉપર દેખાતા <strong>(⊕ Install)</strong> આઇકોન પર ક્લિક કરો.
                </div>
            </div>

            <!-- iPhone iOS Guide -->
            <div style="display: flex; gap: 12px; align-items: flex-start;">
                <span style="font-size: 20px;">🍏</span>
                <div style="font-size: 12.5px; color: #e2e8f0; line-height: 1.45;">
                    <strong style="color: #facc15;">iPhone / iPad (Safari):</strong> નીચે શેર બટન <strong>(⎙ Share)</strong> દબાવી <strong>"Add to Home Screen"</strong> પર ક્લિક કરો.
                </div>
            </div>
        </div>

        <div style="margin-top: 18px; text-align: center; font-size: 11.5px; color: #94a3b8;">
            🔒 100% Safe &amp; Verified • No Google Play Store Login Required • Fast &amp; Lightweight
        </div>
    </div>
</div>

<script>
let deferredAppPrompt = null;

window.addEventListener('beforeinstallprompt', (e) => {
    // Prevent the mini-infobar from appearing on mobile
    e.preventDefault();
    deferredAppPrompt = e;
    
    // Highlight all App download buttons on the site
    document.querySelectorAll('.btn-download-app-trigger').forEach(btn => {
        btn.classList.add('has-install-prompt');
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('appDownloadModal');
    const closeBtn = document.getElementById('closeAppModalBtn');
    const installBtn = document.getElementById('btnNativeInstallApp');

    // Open modal triggers
    document.querySelectorAll('.btn-download-app-trigger').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if (modal) modal.style.display = 'flex';
        });
    });

    if (closeBtn && modal) {
        closeBtn.addEventListener('click', () => modal.style.display = 'none');
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.style.display = 'none';
        });
    }

    if (installBtn) {
        installBtn.addEventListener('click', async () => {
            if (deferredAppPrompt) {
                deferredAppPrompt.prompt();
                const { outcome } = await deferredAppPrompt.userChoice;
                if (outcome === 'accepted') {
                    if (modal) modal.style.display = 'none';
                }
                deferredAppPrompt = null;
            } else {
                alert("તમારા બ્રાઉઝરના ઉપરના મેનૂ (3 ટપકાં ⋮) માં જઈને 'Install App' અથવા 'Add to Home Screen' પર ક્લિક કરો.");
            }
        });
    }
});
</script>
