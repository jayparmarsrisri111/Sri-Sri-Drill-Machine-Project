<?php
$pageTitle = '360° Interactive 12D Machine Viewer & AI SmartDrill™ | Shree Varsha Engineering Works';
$pageDesc = 'World-first 360° interactive drill machine turntable and AI machining parameter optimizer. Touch and rotate 360 degrees, inspect X-Ray alloy gears, simulate cutting sparks and audio.';
$currentNav = 'simulator';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Three.js (Local first with CDN fallback) -->
<script src="<?= assetUrl('assets/js/three.min.js') ?>"></script>
<script>
    if (typeof THREE === 'undefined') {
        document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"><\/script>');
    }
</script>
<script src="<?= assetUrl('assets/js/machine-6d-viewer.js') ?>"></script>
<script src="<?= assetUrl('assets/js/smart-drill-ai.js') ?>"></script>

<style>
    .twin-hero {
        background: linear-gradient(180deg, rgba(11, 17, 32, 0.9) 0%, #060911 100%);
        border-bottom: 1px solid var(--border-subtle);
        padding: 50px 0 25px;
    }
    .twin-container {
        display: grid;
        grid-template-columns: 1.45fr 1fr;
        gap: 28px;
        margin-top: 25px;
    }
    .viewer-card {
        background: #060911;
        border: 1px solid var(--border-cyan);
        border-radius: var(--radius-lg);
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8), 0 0 30px rgba(0, 229, 255, 0.15);
        display: flex;
        flex-direction: column;
    }
    #machineCanvasContainer {
        width: 100%;
        height: 520px;
        position: relative;
        cursor: grab;
    }
    #machineCanvasContainer:active {
        cursor: grabbing;
    }
    /* Telemetry HUD overlay on top of 3D Canvas */
    .hud-overlay {
        position: absolute;
        top: 16px;
        left: 16px;
        right: 16px;
        display: flex;
        justify-content: space-between;
        pointer-events: none;
        z-index: 10;
    }
    .hud-pill {
        background: rgba(6, 9, 17, 0.85);
        backdrop-filter: blur(10px);
        border: 1px solid var(--border-cyan);
        border-radius: 8px;
        padding: 8px 14px;
        font-family: var(--font-heading);
        font-size: 11.5px;
        color: #e2e8f0;
        pointer-events: auto;
    }
    .hud-pill .val {
        font-size: 14px;
        font-weight: 700;
        color: var(--accent-cyan);
    }
    .hud-pill .label {
        font-size: 10px;
        color: var(--text-muted);
        text-transform: uppercase;
        display: block;
    }
    /* Kinematic Controls Bar beneath 3D canvas */
    .controls-bar {
        background: rgba(11, 17, 32, 0.95);
        border-top: 1px solid var(--border-subtle);
        padding: 18px 20px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    .slider-row {
        display: grid;
        grid-template-columns: 120px 1fr 50px;
        align-items: center;
        gap: 12px;
        font-size: 12.5px;
    }
    .slider-row label {
        color: var(--text-secondary);
        font-weight: 500;
    }
    .slider-row input[type="range"] {
        accent-color: var(--accent-cyan);
        cursor: pointer;
    }
    .slider-row .slider-val {
        font-family: var(--font-heading);
        font-weight: 700;
        color: var(--accent-cyan);
        text-align: right;
    }
    .action-btn-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        padding-top: 10px;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
    }
    .btn-tool {
        padding: 7px 12px;
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid var(--border-subtle);
        border-radius: 6px;
        color: #e2e8f0;
        font-family: var(--font-heading);
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: var(--transition);
    }
    .btn-tool:hover, .btn-tool.active {
        background: rgba(0, 229, 255, 0.15);
        border-color: var(--accent-cyan);
        color: var(--accent-cyan);
    }
    .btn-tool-danger {
        border-color: rgba(239, 68, 68, 0.4);
        color: #f87171;
    }
    .btn-tool-danger:hover {
        background: rgba(239, 68, 68, 0.2);
    }
    /* AI SmartDrill Panel */
    .ai-panel {
        background: var(--bg-card);
        border: 1px solid var(--border-cyan);
        border-radius: var(--radius-lg);
        padding: 28px;
        box-shadow: var(--shadow-card);
        display: flex;
        flex-direction: column;
    }
    .ai-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        background: rgba(245, 158, 11, 0.15);
        border: 1px solid var(--accent-amber);
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
        color: #fbbf24;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }
    .ai-output-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin: 20px 0;
    }
    .ai-stat-box {
        background: rgba(6, 9, 17, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 8px;
        padding: 12px;
    }
    .ai-stat-box .title {
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .ai-stat-box .number {
        font-family: var(--font-heading);
        font-size: 19px;
        font-weight: 800;
        color: #ffffff;
    }
    .ai-stat-box .unit {
        font-size: 12px;
        color: var(--accent-cyan);
        font-weight: 600;
    }
    .ai-rec-box {
        background: linear-gradient(135deg, rgba(0, 229, 255, 0.1) 0%, rgba(37, 99, 235, 0.15) 100%);
        border: 1px solid var(--accent-cyan);
        border-radius: 10px;
        padding: 16px;
        margin: 18px 0;
    }
    @media (max-width: 1024px) {
        .twin-container {
            grid-template-columns: 1fr;
        }
        #machineCanvasContainer {
            height: 420px;
        }
    }
</style>

<!-- Page Header -->
<section class="twin-hero" style="padding: 55px 0 35px; background: radial-gradient(circle at 50% 20%, rgba(0, 229, 255, 0.1) 0%, rgba(6, 9, 17, 0.98) 75%); border-bottom: 1px solid var(--border-subtle);">
    <div class="container" style="text-align: center;">
        <div style="display: flex; align-items: center; justify-content: center; gap: 14px; margin-bottom: 16px; flex-wrap: wrap;">
            <div style="width: 58px; height: 58px; border-radius: 50%; border: 2px solid var(--accent-cyan); box-shadow: 0 0 22px rgba(0, 229, 255, 0.45); background: #060911; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha Brand Emblem" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div class="pill-badge" style="margin-bottom: 0; display: inline-flex; align-items: center; gap: 8px; border: 1.5px solid rgba(0, 229, 255, 0.4); background: rgba(15, 23, 42, 0.85); border-radius: 9999px; padding: 7px 18px; box-shadow: 0 0 20px rgba(0, 229, 255, 0.15);">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--accent-cyan); display: inline-block; box-shadow: 0 0 8px var(--accent-cyan);"></span>
                <span style="font-family: 'Space Grotesk', sans-serif; font-size: 12px; font-weight: 800; color: #ffffff; letter-spacing: 1px;">
                    WORLD-FIRST INNOVATION • <span style="color: var(--accent-cyan);">360° 12D TURNTABLE &amp; AI LAB</span>
                </span>
            </div>
        </div>
        <h1 class="section-title">360° Interactive 12D Turntable &amp; <span>AI-SmartDrill™</span></h1>
        <p class="section-desc" style="max-width: 760px; margin: 0 auto; color: #cbd5e1; font-size: 15px; line-height: 1.6;">
            Touch and rotate 360° with 30-degree precision stepping, inspect the internal alloy gear train in X-Ray mode, simulate live drilling with dynamic metal sparks &amp; audio drone, and optimize your machining cutting parameters with our proprietary AI engine.
        </p>
    </div>
</section>

<!-- Main Simulator Section -->
<section class="section" style="padding-top: 20px;">
    <div class="container">
        <div class="twin-container">
            <!-- LEFT COLUMN: 6D WEBGL 3D DIGITAL TWIN -->
            <div class="viewer-card">
                <!-- 3D WebGL Canvas -->
                <div id="machineCanvasContainer">
                    <!-- Live Telemetry HUD -->
                    <div class="hud-overlay">
                        <div class="hud-pill">
                            <span class="label">Spindle Tachometer</span>
                            <span class="val" id="hudRPM">0</span> <span style="font-size: 10px; color: var(--accent-cyan);">RPM</span>
                        </div>
                        <div class="hud-pill">
                            <span class="label">Spindle Torque</span>
                            <span class="val" id="hudTorque">0.0 Nm</span>
                        </div>
                        <div class="hud-pill">
                            <span class="label">Motor Load</span>
                            <span class="val" id="hudLoad">0%</span>
                        </div>
                        <div class="hud-pill">
                            <span class="label">Vibration FFT</span>
                            <span class="val" id="hudVib">0.12 mm/s</span>
                        </div>
                        <div class="hud-pill">
                            <span class="label">Cutting Depth</span>
                            <span class="val" id="hudDepth">0.0 mm</span>
                        </div>
                    </div>

                    <!-- Touch / Gesture Guide Hint -->
                    <div style="position: absolute; bottom: 12px; left: 16px; font-size: 11.5px; color: var(--text-muted); background: rgba(6, 9, 17, 0.7); padding: 4px 10px; border-radius: 4px; pointer-events: none;">
                        👆 Touch / Drag to Rotate • Pinch / Scroll to Zoom • Right-click to Pan
                    </div>
                </div>

                <!-- 6D Kinematic Controls Bar -->
                <div class="controls-bar">
                    <!-- Arm 360 Rotation -->
                    <div class="slider-row">
                        <label>360° Arm Swivel</label>
                        <input type="range" id="sliderArmAngle" min="-180" max="180" value="0">
                        <span class="slider-val" id="valArmAngle">0°</span>
                    </div>

                    <!-- Arm Height -->
                    <div class="slider-row">
                        <label>Arm Height</label>
                        <input type="range" id="sliderArmHeight" min="-25" max="45" value="0">
                        <span class="slider-val" id="valArmHeight">0mm</span>
                    </div>

                    <!-- Headstock Traverse -->
                    <div class="slider-row">
                        <label>Head Position</label>
                        <input type="range" id="sliderHeadPos" min="60" max="175" value="120">
                        <span class="slider-val" id="valHeadPos">120mm</span>
                    </div>

                    <!-- Manual Quill Feed -->
                    <div class="slider-row">
                        <label>Spindle Quill Feed</label>
                        <input type="range" id="sliderQuillFeed" min="0" max="42" value="0">
                        <span class="slider-val" id="valQuillFeed">0mm</span>
                    </div>

                    <!-- Exploded View Disassembly -->
                    <div class="slider-row">
                        <label style="color: var(--accent-amber);">Exploded View</label>
                        <input type="range" id="sliderExploded" min="0" max="100" value="0">
                        <span class="slider-val" id="valExploded" style="color: var(--accent-amber);">0%</span>
                    </div>

                    <!-- Visual Modes & Simulation Buttons -->
                    <div class="action-btn-row">
                        <button type="button" class="btn-tool active" id="btnThemeRealistic" title="Standard Cast Iron Look">
                            🏭 Cast Iron
                        </button>
                        <button type="button" class="btn-tool" id="btnThemeXRay" title="Inspect Internal Alloy Gear Train">
                            🔬 X-Ray Gearbox
                        </button>
                        <button type="button" class="btn-tool" id="btnThemeHologram" title="Cyber Holographic Wireframe">
                            ⚡ Cyber Matrix
                        </button>
                        <button type="button" class="btn-tool" id="btnToggleSound">
                            🔊 Sound: <span id="soundStatusText">OFF</span>
                        </button>
                        <button type="button" class="btn-tool" id="btnAutoRotate">
                            🔄 Auto-Orbit
                        </button>
                        <button type="button" class="btn btn-primary" id="btnRunSimulation" style="padding: 7px 18px; font-size: 12px; margin-left: auto;">
                            ⚡ Run Drilling Cycle
                        </button>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: SVEW AI-SMARTDRILL™ PHYSICS OPTIMIZER -->
            <div class="ai-panel">
                <div class="ai-badge">
                    ✦ PROPRIETARY METALLURGICAL AI ENGINE
                </div>
                <h3 style="font-family: var(--font-heading); font-size: 22px; color: #fff; margin-bottom: 6px;">
                    AI Machining Parameter Optimizer
                </h3>
                <p style="font-size: 13.5px; color: var(--text-muted); margin-bottom: 20px;">
                    Select your workpiece material and drilling dimensions. The AI calculates optimal cutting parameters and tests them live on the 6D digital twin!
                </p>

                <!-- Input Parameters -->
                <div class="form-group" style="margin-bottom: 14px;">
                    <label class="form-label">Workpiece Material</label>
                    <select id="aiMaterialSelect" class="form-control" style="background: rgba(6, 9, 17, 0.9);">
                        <option value="en8_ms">Mild & Carbon Steel (EN8 / IS 2062)</option>
                        <option value="en24_alloy">High Tensile Alloy Steel (EN24 / 4140)</option>
                        <option value="cast_iron_25">Seasoned Cast Iron (Grade 25 / FG260)</option>
                        <option value="ss_304">Stainless Steel (SS304 / SS316)</option>
                        <option value="hardox_500">Abrasion Resistant Steel (Hardox 450/500)</option>
                        <option value="aluminum_6061">Duralumin / Aluminum 6061-T6</option>
                        <option value="brass_c360">Free Machining Brass (C360)</option>
                    </select>
                </div>

                <div class="form-grid" style="margin-bottom: 14px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Hole Diameter (mm)</label>
                        <input type="number" id="aiHoleDia" class="form-control" value="40" min="5" max="65" step="1">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Hole Depth (mm)</label>
                        <input type="number" id="aiHoleDepth" class="form-control" value="50" min="10" max="350" step="5">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 18px;">
                    <label class="form-label">Drill Tool Material</label>
                    <div style="display: flex; gap: 16px;">
                        <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; color: #fff; cursor: pointer;">
                            <input type="radio" name="aiToolType" value="hss" checked style="accent-color: var(--accent-cyan);">
                            <span>Cobalt HSS (M35/M42)</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; color: #fff; cursor: pointer;">
                            <input type="radio" name="aiToolType" value="carbide" style="accent-color: var(--accent-cyan);">
                            <span>Indexable / Solid Carbide</span>
                        </label>
                    </div>
                </div>

                <!-- Live Calculated AI Results -->
                <div class="ai-output-grid">
                    <div class="ai-stat-box">
                        <div class="title">Optimal Spindle RPM</div>
                        <div class="number"><span id="aiRPM">220</span> <span class="unit">RPM</span></div>
                    </div>
                    <div class="ai-stat-box">
                        <div class="title">Feed & Penetration</div>
                        <div class="number"><span id="aiFeed">48</span> <span class="unit">mm/min</span></div>
                    </div>
                    <div class="ai-stat-box">
                        <div class="title">Cutting Cycle Time</div>
                        <div class="number"><span id="aiCycleTime">78</span> <span class="unit">Sec</span></div>
                    </div>
                    <div class="ai-stat-box">
                        <div class="title">Spindle Torque & Power</div>
                        <div class="number"><span id="aiPower">2.1</span> <span class="unit">HP</span></div>
                    </div>
                </div>

                <!-- AI Recommended Machine Banner -->
                <div class="ai-rec-box">
                    <div style="font-size: 11px; text-transform: uppercase; color: var(--accent-cyan); font-weight: 700; margin-bottom: 4px;">
                        AI Recommended Shree Varsha Model:
                    </div>
                    <div style="font-family: var(--font-heading); font-size: 17px; font-weight: 800; color: #fff;" id="aiMachineName">
                        SVEW-RD-40 (40mm All-Geared Radial Drill)
                    </div>
                    <div style="font-size: 12.5px; color: #cbd5e1; margin-top: 6px;" id="aiMachineDetails">
                        Torque Reserve: <strong style="color: #34d399;" id="aiTorqueReserve">+32%</strong> • Suitable for continuous heavy duty production.
                    </div>
                    <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 6px;" id="aiCoolantNote">
                        Recommended Coolant: Water Soluble Synthetic Emulsion (1:20 Ratio)
                    </div>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 5px;">
                    <button type="button" class="btn btn-primary" id="btnSyncToTwin" style="width: 100%;">
                        ⚡ Apply AI Parameters & Run 6D Simulation
                    </button>
                    <div style="display: flex; gap: 10px;">
                        <button type="button" class="btn btn-outline open-quote-modal" id="btnAiQuote" style="flex: 1; font-size: 12px;"
                                data-machine-name="AI Custom Job: 40mm in Mild Steel EN8">
                            Request Factory Quote
                        </button>
                        <button type="button" class="btn btn-outline" id="btnPrintCard" style="flex: 1; font-size: 12px;">
                            🖨️ Print AI Job Card
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Script initializing the 6D Viewer & AI Engine -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Initialize 6D Digital Twin Viewer
    const viewer = new Machine6DViewer('machineCanvasContainer', {
        autoRotate: false,
        theme: 'realistic',
        zoomOnWheel: true
    });

    // 2. Bind Kinematic Sliders
    const sliderArmAngle = document.getElementById('sliderArmAngle');
    const valArmAngle = document.getElementById('valArmAngle');
    sliderArmAngle?.addEventListener('input', function() {
        valArmAngle.innerText = this.value + '°';
        viewer.setArmAngle(parseFloat(this.value));
    });

    const sliderArmHeight = document.getElementById('sliderArmHeight');
    const valArmHeight = document.getElementById('valArmHeight');
    sliderArmHeight?.addEventListener('input', function() {
        valArmHeight.innerText = this.value + 'mm';
        viewer.setArmHeight(parseFloat(this.value));
    });

    const sliderHeadPos = document.getElementById('sliderHeadPos');
    const valHeadPos = document.getElementById('valHeadPos');
    sliderHeadPos?.addEventListener('input', function() {
        valHeadPos.innerText = this.value + 'mm';
        viewer.setHeadPosition(parseFloat(this.value));
    });

    const sliderQuillFeed = document.getElementById('sliderQuillFeed');
    const valQuillFeed = document.getElementById('valQuillFeed');
    sliderQuillFeed?.addEventListener('input', function() {
        valQuillFeed.innerText = this.value + 'mm';
        viewer.setQuillFeed(parseFloat(this.value));
    });

    const sliderExploded = document.getElementById('sliderExploded');
    const valExploded = document.getElementById('valExploded');
    sliderExploded?.addEventListener('input', function() {
        valExploded.innerText = this.value + '%';
        viewer.setExplodedView(parseFloat(this.value) / 100);
    });

    // 3. Theme Toggle Buttons
    const btnRealistic = document.getElementById('btnThemeRealistic');
    const btnXRay = document.getElementById('btnThemeXRay');
    const btnHologram = document.getElementById('btnThemeHologram');

    function setActiveThemeBtn(activeBtn) {
        [btnRealistic, btnXRay, btnHologram].forEach(b => b?.classList.remove('active'));
        activeBtn?.classList.add('active');
    }

    btnRealistic?.addEventListener('click', () => {
        viewer.setVisualTheme('realistic');
        setActiveThemeBtn(btnRealistic);
    });

    btnXRay?.addEventListener('click', () => {
        viewer.setVisualTheme('xray');
        setActiveThemeBtn(btnXRay);
    });

    btnHologram?.addEventListener('click', () => {
        viewer.setVisualTheme('hologram');
        setActiveThemeBtn(btnHologram);
    });

    // 4. Sound & Auto-Rotate Toggles
    const btnSound = document.getElementById('btnToggleSound');
    const soundText = document.getElementById('soundStatusText');
    let soundOn = false;
    btnSound?.addEventListener('click', () => {
        soundOn = !soundOn;
        viewer.toggleSound(soundOn);
        if (soundText) soundText.innerText = soundOn ? 'ON' : 'OFF';
        btnSound.classList.toggle('active', soundOn);
    });

    const btnAutoRotate = document.getElementById('btnAutoRotate');
    btnAutoRotate?.addEventListener('click', () => {
        viewer.options.autoRotate = !viewer.options.autoRotate;
        btnAutoRotate.classList.toggle('active', viewer.options.autoRotate);
    });

    // 5. Run Manual Simulation
    const btnRun = document.getElementById('btnRunSimulation');
    btnRun?.addEventListener('click', () => {
        viewer.startDrillingSimulation(38, 650);
    });

    // 6. AI SmartDrill Engine Integration
    const matSelect = document.getElementById('aiMaterialSelect');
    const holeDiaInput = document.getElementById('aiHoleDia');
    const holeDepthInput = document.getElementById('aiHoleDepth');
    const toolRadios = document.querySelectorAll('input[name="aiToolType"]');

    function recalculateAI() {
        const mat = matSelect.value;
        const dia = parseFloat(holeDiaInput.value) || 40;
        const depth = parseFloat(holeDepthInput.value) || 50;
        let tool = 'hss';
        toolRadios.forEach(r => { if (r.checked) tool = r.value; });

        const result = SmartDrillAI.calculate(mat, dia, depth, tool);

        document.getElementById('aiRPM').innerText = result.optimalRPM;
        document.getElementById('aiFeed').innerText = result.penetrationRate;
        document.getElementById('aiCycleTime').innerText = result.cycleTimeSeconds;
        document.getElementById('aiPower').innerText = result.powerHP + ' HP (' + result.torqueNm + ' Nm)';
        document.getElementById('aiMachineName').innerText = result.recommendedMachine.name + ' (' + result.recommendedMachine.model + ')';
        document.getElementById('aiTorqueReserve').innerText = '+' + result.torqueReserve + '%';
        document.getElementById('aiCoolantNote').innerText = 'Coolant Recommendation: ' + result.coolant;

        // Update quote button machine name
        const btnQuote = document.getElementById('btnAiQuote');
        if (btnQuote) {
            btnQuote.setAttribute('data-machine-name', result.recommendedMachine.name + ' for ' + dia + 'mm hole in ' + result.materialName);
        }

        return result;
    }

    [matSelect, holeDiaInput, holeDepthInput].forEach(elem => {
        elem?.addEventListener('input', recalculateAI);
    });
    toolRadios.forEach(r => r.addEventListener('change', recalculateAI));

    // Run initial calculation
    recalculateAI();

    // 7. Apply AI Parameters directly to 6D Twin
    const btnSync = document.getElementById('btnSyncToTwin');
    btnSync?.addEventListener('click', function() {
        const result = recalculateAI();
        // Set camera and run simulation with AI RPM!
        viewer.targetRPM = result.optimalRPM;
        viewer.startDrillingSimulation(40, result.optimalRPM);
    });

    // 8. Print Job Card
    const btnPrint = document.getElementById('btnPrintCard');
    btnPrint?.addEventListener('click', function() {
        window.print();
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
