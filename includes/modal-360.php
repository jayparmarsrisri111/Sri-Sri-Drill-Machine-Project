<!-- 360° Interactive Industrial Machine Turntable Modal -->
<div id="modal360Viewer" class="quote-modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(3, 7, 18, 0.94); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); z-index: 10002; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: linear-gradient(180deg, #0b1120 0%, #060913 100%); border: 1.5px solid rgba(0, 229, 255, 0.4); border-radius: 20px; width: 100%; max-width: 680px; box-shadow: 0 30px 80px rgba(0,0,0,0.95), 0 0 40px rgba(0, 229, 255, 0.2); overflow: hidden; animation: modalPop 0.25s cubic-bezier(0.16, 1, 0.3, 1);">
        
        <!-- Header -->
        <div style="background: rgba(15, 23, 42, 0.95); padding: 16px 24px; border-bottom: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand" style="width: 42px; height: 42px; border-radius: 50%; border: 1.5px solid var(--accent-cyan); object-fit: cover; box-shadow: 0 0 14px rgba(0, 229, 255, 0.4); flex-shrink: 0;">
                <div>
                    <h3 id="modal360Title" style="font-family: 'Space Grotesk', sans-serif; font-size: 16px; font-weight: 800; color: #ffffff; margin: 0; letter-spacing: -0.3px;">
                        360° Interactive Machine Turntable
                    </h3>
                    <div style="font-size: 11.5px; color: #38bdf8; font-weight: 600; margin-top: 2px;">
                        Shree Varsha Engineering Works • Bhavnagar • 30° Precision Stepping
                    </div>
                </div>
            </div>
            <button type="button" id="btnModal360Close" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #94a3b8; font-size: 24px; width: 34px; height: 34px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; line-height: 1; transition: all 0.2s;" onmouseover="this.style.color='#fff'; this.style.background='rgba(239,68,68,0.2)'" onmouseout="this.style.color='#94a3b8'; this.style.background='rgba(255,255,255,0.05)'">&times;</button>
        </div>

        <!-- 360° Turntable Stage Container (100% Clean: No Text Overlays on Image) -->
        <div style="padding: 24px 24px 10px; text-align: center; position: relative; background: radial-gradient(circle at 50% 45%, rgba(0, 229, 255, 0.08) 0%, rgba(6, 10, 20, 0.95) 70%);">
            
            <!-- 3D Perspective Turntable Stage -->
            <div id="turntableStage" style="width: 100%; height: 380px; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; position: relative; perspective: 1200px; cursor: grab; user-select: none; overflow: visible;">
                
                <!-- Rotating Machine Wrapper with 3D Transform -->
                <div id="machineRotator" style="width: 100%; height: 320px; display: flex; align-items: center; justify-content: center; transform-style: preserve-3d; transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1); z-index: 2;">
                    <img id="modal360Img" src="<?= assetUrl('assets/images/machinery/radial-drill-real.jpg') ?>" alt="Drill Machine 360" style="max-height: 310px; max-width: 90%; object-fit: contain; pointer-events: none; transition: filter 0.2s ease; filter: drop-shadow(0 15px 25px rgba(0,0,0,0.85));">
                </div>

                <!-- Industrial Precision Turntable Pedestal Base -->
                <div style="position: relative; width: 320px; height: 50px; margin-top: -10px; z-index: 1;">
                    <!-- Metallic Circular Turntable Disc -->
                    <div id="turntableDisc" style="width: 320px; height: 42px; border-radius: 50%; background: radial-gradient(ellipse at center, #1e293b 0%, #0f172a 65%, #0284c7 95%, #00e5ff 100%); border: 2px solid rgba(0, 229, 255, 0.5); box-shadow: 0 15px 35px rgba(0, 0, 0, 0.9), 0 0 25px rgba(0, 229, 255, 0.2); transform: rotateX(68deg); transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);"></div>
                    <!-- Radial Glow Under Platform -->
                    <div style="position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 220px; height: 16px; border-radius: 50%; background: radial-gradient(ellipse at center, rgba(0, 229, 255, 0.45) 0%, transparent 75%); filter: blur(6px); pointer-events: none;"></div>
                </div>
            </div>

            <!-- Precision Rotation Angle & Status Bar (Cleanly placed below the turntable) -->
            <div style="display: flex; align-items: center; justify-content: center; gap: 16px; margin: 14px 0 16px;">
                <span style="font-size: 12px; color: #94a3b8; font-weight: 600;">Turntable Angle:</span>
                <span id="currentAngleText" style="font-family: 'Space Grotesk', monospace; font-size: 14px; font-weight: 800; color: #00e5ff; background: rgba(0, 229, 255, 0.1); border: 1px solid rgba(0, 229, 255, 0.3); padding: 2px 10px; border-radius: 6px;">
                    0°
                </span>
                <span style="font-size: 11px; color: #64748b;">(Drag horizontally or use 30° steps)</span>
            </div>

            <!-- Controls Toolbar -->
            <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap; padding-bottom: 8px;">
                <button type="button" id="btnRotateLeft30" class="btn btn-outline" style="border-color: rgba(56, 189, 248, 0.4); color: #38bdf8; font-size: 12.5px; padding: 8px 16px; border-radius: 8px; font-weight: 700;">
                    ◀ 30° Left
                </button>
                <button type="button" id="btnToggleAutoSpin" class="btn btn-primary" style="background: linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); font-size: 12.5px; padding: 8px 20px; font-weight: 800; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 229, 255, 0.3);">
                    🔄 Continuous 360° Spin
                </button>
                <button type="button" id="btnRotateRight30" class="btn btn-outline" style="border-color: rgba(56, 189, 248, 0.4); color: #38bdf8; font-size: 12.5px; padding: 8px 16px; border-radius: 8px; font-weight: 700;">
                    30° Right ▶
                </button>
                <button type="button" id="btnResetAngle" class="btn btn-outline" style="border-color: rgba(148, 163, 184, 0.3); color: #94a3b8; font-size: 12px; padding: 8px 12px; border-radius: 8px;">
                    ↺ Reset 0°
                </button>
            </div>
        </div>

        <!-- Clean Footer Action Bar -->
        <div style="background: #060911; padding: 14px 24px; border-top: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; font-size: 12px;">
            <div style="display: flex; align-items: center; gap: 8px; color: #94a3b8;">
                <span style="color: #10b981; font-size: 14px;">✔</span>
                <span>Factory Certified Heavy-Duty Machine</span>
            </div>
            <a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-color: #34d399; font-weight: 800; font-size: 12px; padding: 6px 14px; border-radius: 6px;">
                💳 Book Machine (Token ₹25,000)
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal360Viewer');
    const btnClose = document.getElementById('btnModal360Close');
    const modalTitle = document.getElementById('modal360Title');
    const modalImg = document.getElementById('modal360Img');
    const rotator = document.getElementById('machineRotator');
    const disc = document.getElementById('turntableDisc');
    const angleText = document.getElementById('currentAngleText');
    const stage = document.getElementById('turntableStage');

    let currentAngle = 0;
    let isAutoSpinning = false;
    let autoSpinInterval = null;

    function setAngle(deg, smooth = true) {
        currentAngle = ((deg % 360) + 360) % 360;
        if (angleText) {
            angleText.textContent = Math.round(currentAngle) + '°';
        }
        
        // Realistic 3D rotation & dynamic drop shadow / brightness
        if (rotator) {
            rotator.style.transition = smooth ? 'transform 0.28s cubic-bezier(0.16, 1, 0.3, 1)' : 'none';
            rotator.style.transform = `perspective(1000px) rotateY(${currentAngle}deg)`;
        }

        // Rotate the circular turntable platform synchronously
        if (disc) {
            disc.style.transition = smooth ? 'transform 0.28s cubic-bezier(0.16, 1, 0.3, 1)' : 'none';
            disc.style.transform = `rotateX(68deg) rotateZ(${currentAngle}deg)`;
        }

        // Dynamic shadow & lighting shift
        if (modalImg) {
            const rad = currentAngle * Math.PI / 180;
            const shadowX = Math.sin(rad) * 18;
            const brightness = 0.92 + Math.cos(rad) * 0.12;
            modalImg.style.filter = `drop-shadow(${shadowX}px 18px 24px rgba(0,0,0,0.85)) drop-shadow(0 0 18px rgba(0, 229, 255, 0.22)) brightness(${brightness})`;
        }
    }

    // Step 30 Degrees Left & Right
    const btnLeft30 = document.getElementById('btnRotateLeft30');
    if (btnLeft30) {
        btnLeft30.addEventListener('click', function() {
            stopAutoSpin();
            setAngle(currentAngle - 30);
        });
    }

    const btnRight30 = document.getElementById('btnRotateRight30');
    if (btnRight30) {
        btnRight30.addEventListener('click', function() {
            stopAutoSpin();
            setAngle(currentAngle + 30);
        });
    }

    const btnReset = document.getElementById('btnResetAngle');
    if (btnReset) {
        btnReset.addEventListener('click', function() {
            stopAutoSpin();
            setAngle(0);
        });
    }

    function stopAutoSpin() {
        if (autoSpinInterval) {
            clearInterval(autoSpinInterval);
            autoSpinInterval = null;
        }
        isAutoSpinning = false;
        const autoSpinBtn = document.getElementById('btnToggleAutoSpin');
        if (autoSpinBtn) {
            autoSpinBtn.innerHTML = "🔄 Continuous 360° Spin";
        }
    }

    const autoSpinBtn = document.getElementById('btnToggleAutoSpin');
    if (autoSpinBtn) {
        autoSpinBtn.addEventListener('click', function() {
            if (isAutoSpinning) {
                stopAutoSpin();
            } else {
                isAutoSpinning = true;
                this.innerHTML = "⏸ Pause Spin";
                autoSpinInterval = setInterval(function() {
                    setAngle(currentAngle + 3, false);
                }, 35);
            }
        });
    }

    // Touch & Mouse Drag Rotation
    let isDragging = false;
    let startX = 0;
    let startAngle = 0;

    if (stage) {
        stage.addEventListener('mousedown', function(e) {
            stopAutoSpin();
            isDragging = true;
            startX = e.clientX;
            startAngle = currentAngle;
            stage.style.cursor = 'grabbing';
        });

        window.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            const deltaX = e.clientX - startX;
            setAngle(startAngle + deltaX * 0.75, false);
        });

        window.addEventListener('mouseup', function() {
            if (isDragging) {
                isDragging = false;
                stage.style.cursor = 'grab';
            }
        });

        // Touch Events for Mobile
        stage.addEventListener('touchstart', function(e) {
            if (e.touches.length === 1) {
                stopAutoSpin();
                isDragging = true;
                startX = e.touches[0].clientX;
                startAngle = currentAngle;
            }
        }, { passive: true });

        window.addEventListener('touchmove', function(e) {
            if (!isDragging || e.touches.length !== 1) return;
            const deltaX = e.touches[0].clientX - startX;
            setAngle(startAngle + deltaX * 0.75, false);
        }, { passive: true });

        window.addEventListener('touchend', function() {
            isDragging = false;
        });
    }

    // Global event delegation for all 360 modal triggers (supports live search & filtered cards)
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.open-360-modal');
        if (!btn) return;
        e.preventDefault();
        const name = btn.getAttribute('data-machine-name') || 'Industrial Drill Machine';
        const img = btn.getAttribute('data-machine-img') || 'assets/images/machinery/radial-drill-real.jpg';
        if (modalTitle) modalTitle.textContent = "360° View: " + name;
        if (modalImg) modalImg.src = img;
        setAngle(0, false);
        modal.style.display = 'flex';
    });

    if (btnClose) {
        btnClose.addEventListener('click', function() {
            stopAutoSpin();
            modal.style.display = 'none';
        });
    }

    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            stopAutoSpin();
            modal.style.display = 'none';
        }
    });
});
</script>