/**
 * Shree Varsha Engineering Works
 * 6D Interactive Machine Digital Twin & WebGL Simulator
 * Features 6-Degrees-of-Freedom manipulation, Kinematic Arm Swivel,
 * Quill Feed, Dynamic Sparks, Audio Synthesis, X-Ray Gearbox, & Exploded View.
 */

class Machine6DViewer {
    constructor(containerId, options = {}) {
        this.container = document.getElementById(containerId);
        if (!this.container) return;

        this.options = Object.assign({
            autoRotate: false,
            enableSparks: true,
            theme: 'realistic', // 'realistic', 'hologram', 'xray'
            showTelemetry: true
        }, options);

        // Kinematic State
        this.armAngle = 0;          // Radians (-Math.PI to Math.PI)
        this.armHeight = 0;         // Vertical offset (-30 to +40)
        this.headPosition = 0;      // Along arm offset (-20 to +50)
        this.quillFeed = 0;         // Downward spindle travel (0 to 45)
        this.spindleSpeed = 0;      // RPM
        this.targetRPM = 650;
        this.isDrilling = false;
        this.explodedAmount = 0;    // 0 (assembled) to 1 (fully exploded)
        this.themeMode = 'realistic'; // realistic, hologram, xray
        this.soundEnabled = false;

        // Telemetry State
        this.telemetry = {
            rpm: 0,
            torque: 0,
            loadPercent: 0,
            vibration: 0.12,
            bearingTemp: 32.5,
            depthMM: 0
        };

        this.init();
    }

    init() {
        this.setupAudio();
        this.setupScene();
        this.buildMachine3D();
        this.setupEventListeners();
        this.setupParticleSystem();
        this.animate = this.animate.bind(this);
        requestAnimationFrame(this.animate);
    }

    // ==========================================
    // 1. Web Audio API Synthesizer (No MP3 files needed!)
    // ==========================================
    setupAudio() {
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            this.audioCtx = new AudioContext();
            
            // Motor Drone Oscillator
            this.motorOsc = this.audioCtx.createOscillator();
            this.motorGain = this.audioCtx.createGain();
            this.motorGain.gain.setValueAtTime(0, this.audioCtx.currentTime);
            this.motorOsc.type = 'sawtooth';
            this.motorOsc.frequency.setValueAtTime(60, this.audioCtx.currentTime);

            // Filter for motor drone
            this.motorFilter = this.audioCtx.createBiquadFilter();
            this.motorFilter.type = 'lowpass';
            this.motorFilter.frequency.setValueAtTime(180, this.audioCtx.currentTime);

            this.motorOsc.connect(this.motorFilter);
            this.motorFilter.connect(this.motorGain);
            this.motorGain.connect(this.audioCtx.destination);
            this.motorOsc.start();

            // Metal Cutting Noise Generator
            const bufferSize = this.audioCtx.sampleRate * 2;
            const noiseBuffer = this.audioCtx.createBuffer(1, bufferSize, this.audioCtx.sampleRate);
            const output = noiseBuffer.getChannelData(0);
            for (let i = 0; i < bufferSize; i++) {
                output[i] = Math.random() * 2 - 1;
            }

            this.noiseNode = this.audioCtx.createBufferSource();
            this.noiseNode.buffer = noiseBuffer;
            this.noiseNode.loop = true;

            this.cuttingFilter = this.audioCtx.createBiquadFilter();
            this.cuttingFilter.type = 'bandpass';
            this.cuttingFilter.frequency.setValueAtTime(1400, this.audioCtx.currentTime);
            this.cuttingFilter.Q.setValueAtTime(3.5, this.audioCtx.currentTime);

            this.cuttingGain = this.audioCtx.createGain();
            this.cuttingGain.gain.setValueAtTime(0, this.audioCtx.currentTime);

            this.noiseNode.connect(this.cuttingFilter);
            this.cuttingFilter.connect(this.cuttingGain);
            this.cuttingGain.connect(this.audioCtx.destination);
            this.noiseNode.start();
        } catch (e) {
            console.log("Web Audio not supported or waiting for user gesture:", e);
        }
    }

    toggleSound(enable) {
        this.soundEnabled = enable;
        if (!this.audioCtx) return;
        if (this.audioCtx.state === 'suspended') {
            this.audioCtx.resume();
        }
        if (!enable) {
            if (this.motorGain) this.motorGain.gain.setValueAtTime(0, this.audioCtx.currentTime);
            if (this.cuttingGain) this.cuttingGain.gain.setValueAtTime(0, this.audioCtx.currentTime);
        }
    }

    updateAudio(rpm, isCutting) {
        if (!this.soundEnabled || !this.audioCtx) return;
        const now = this.audioCtx.currentTime;

        if (rpm > 0) {
            const freq = 35 + (rpm / 1800) * 110;
            this.motorOsc.frequency.setTargetAtTime(freq, now, 0.1);
            this.motorGain.gain.setTargetAtTime(0.08, now, 0.1);
        } else {
            this.motorGain.gain.setTargetAtTime(0, now, 0.15);
        }

        if (isCutting) {
            this.cuttingGain.gain.setTargetAtTime(0.14, now, 0.05);
            this.cuttingFilter.frequency.setTargetAtTime(1200 + Math.random() * 400, now, 0.05);
        } else {
            this.cuttingGain.gain.setTargetAtTime(0, now, 0.1);
        }
    }

    // ==========================================
    // 2. Setup Three.js 3D Scene
    // ==========================================
    setupScene() {
        const width = this.container.clientWidth || 800;
        const height = this.container.clientHeight || 550;

        this.scene = new THREE.Scene();
        this.scene.background = new THREE.Color(0x060911);

        // Camera
        this.camera = new THREE.PerspectiveCamera(42, width / height, 1, 3000);
        this.camera.position.set(160, 130, 260);

        // Renderer
        this.renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        this.renderer.setSize(width, height);
        this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        this.renderer.shadowMap.enabled = true;
        this.renderer.shadowMap.type = THREE.PCFSoftShadowMap;
        this.container.appendChild(this.renderer.domElement);

        // Enhanced Studio Lighting for High-Fidelity Visibility (Crystal Clear in Mobile & Dark Screens)
        const ambientLight = new THREE.AmbientLight(0xffffff, 1.35);
        this.scene.add(ambientLight);

        this.keyLight = new THREE.DirectionalLight(0xffffff, 1.6);
        this.keyLight.position.set(200, 320, 180);
        this.keyLight.castShadow = true;
        this.keyLight.shadow.mapSize.width = 1024;
        this.keyLight.shadow.mapSize.height = 1024;
        this.scene.add(this.keyLight);

        // Frontal Studio Fill Light (Eliminates murky machine silhouettes)
        const frontFillLight = new THREE.DirectionalLight(0xe0f2fe, 1.1);
        frontFillLight.position.set(-60, 140, 240);
        this.scene.add(frontFillLight);

        // Cyan Fill Light (for technical glowing precision highlights)
        this.cyanLight = new THREE.PointLight(0x00f0ff, 1.8, 500);
        this.cyanLight.position.set(-160, 120, 160);
        this.scene.add(this.cyanLight);

        // Amber Rim Light (for warm metallurgical gear accents)
        this.amberLight = new THREE.PointLight(0xf59e0b, 1.2, 450);
        this.amberLight.position.set(160, -40, -140);
        this.scene.add(this.amberLight);

        // Circular Turntable Platform & High-Tech Ground Grid
        const gridHelper = new THREE.GridHelper(380, 38, 0x00e5ff, 0x1e293b);
        gridHelper.position.y = -60;
        this.scene.add(gridHelper);

        // Orbital & 6-DOF Touch Controls
        this.setup6DControls();
    }

    // ==========================================
    // 3. Touch & Mouse 6D Kinematic Controls
    // ==========================================
    setup6DControls() {
        this.isDragging = false;
        this.isPanning = false;
        this.previousMousePosition = { x: 0, y: 0 };
        const isMobileScreen = (this.container.clientWidth < 640);
        this.spherical = { 
            radius: isMobileScreen ? 360 : 340, 
            theta: Math.PI / 4, 
            phi: Math.PI / 2.7 
        };
        this.targetLookAt = new THREE.Vector3(0, isMobileScreen ? 38 : 40, 0);

        const dom = this.renderer.domElement;

        // Mouse Down
        dom.addEventListener('mousedown', (e) => {
            if (e.button === 0) this.isDragging = true;
            if (e.button === 2) this.isPanning = true;
            this.previousMousePosition = { x: e.clientX, y: e.clientY };
        });

        // Mouse Move
        window.addEventListener('mousemove', (e) => {
            if (!this.isDragging && !this.isPanning) return;
            const deltaX = e.clientX - this.previousMousePosition.x;
            const deltaY = e.clientY - this.previousMousePosition.y;

            if (this.isDragging) {
                // Orbit Rotation (Yaw & Pitch)
                this.spherical.theta -= deltaX * 0.008;
                this.spherical.phi -= deltaY * 0.008;
                this.spherical.phi = Math.max(0.05, Math.min(Math.PI / 2 + 0.2, this.spherical.phi));
            } else if (this.isPanning) {
                // 3D Pan Translation (X, Y)
                this.targetLookAt.x -= deltaX * 0.25;
                this.targetLookAt.y += deltaY * 0.25;
            }

            this.updateCameraPosition();
            this.previousMousePosition = { x: e.clientX, y: e.clientY };
        });

        window.addEventListener('mouseup', () => {
            this.isDragging = false;
            this.isPanning = false;
        });

        // Mouse Wheel Zoom
        dom.addEventListener('wheel', (e) => {
            if (this.options.enableZoom === false) return;
            // On desktop, allow page scroll unless Ctrl is pressed or zoomOnWheel is enabled
            if (!this.options.zoomOnWheel && !e.ctrlKey) return;
            e.preventDefault();
            this.spherical.radius += e.deltaY * 0.25;
            this.spherical.radius = Math.max(100, Math.min(650, this.spherical.radius));
            this.updateCameraPosition();
        }, { passive: false });

        // Touch Gestures for Smartphones / Tablets (Single touch orbit, pinch zoom)
        let initialDistance = 0;
        dom.addEventListener('touchstart', (e) => {
            if (e.touches.length === 1) {
                this.isDragging = true;
                this.previousMousePosition = { x: e.touches[0].clientX, y: e.touches[0].clientY };
            } else if (e.touches.length === 2) {
                this.isDragging = false;
                initialDistance = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
            }
        });

        dom.addEventListener('touchmove', (e) => {
            if (e.touches.length === 1 && this.isDragging) {
                const deltaX = e.touches[0].clientX - this.previousMousePosition.x;
                const deltaY = e.touches[0].clientY - this.previousMousePosition.y;
                this.spherical.theta -= deltaX * 0.009;
                this.spherical.phi -= deltaY * 0.009;
                this.spherical.phi = Math.max(0.05, Math.min(Math.PI / 2 + 0.2, this.spherical.phi));
                this.updateCameraPosition();
                this.previousMousePosition = { x: e.touches[0].clientX, y: e.touches[0].clientY };
            } else if (e.touches.length === 2) {
                const currentDistance = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                const diff = initialDistance - currentDistance;
                this.spherical.radius += diff * 0.5;
                this.spherical.radius = Math.max(100, Math.min(650, this.spherical.radius));
                initialDistance = currentDistance;
                this.updateCameraPosition();
            }
        });

        dom.addEventListener('touchend', () => {
            this.isDragging = false;
        });

        // Context menu prevent
        dom.addEventListener('contextmenu', e => e.preventDefault());

        this.updateCameraPosition();
    }

    updateCameraPosition() {
        this.camera.position.x = this.targetLookAt.x + this.spherical.radius * Math.sin(this.spherical.phi) * Math.sin(this.spherical.theta);
        this.camera.position.y = this.targetLookAt.y + this.spherical.radius * Math.cos(this.spherical.phi);
        this.camera.position.z = this.targetLookAt.z + this.spherical.radius * Math.sin(this.spherical.phi) * Math.cos(this.spherical.theta);
        this.camera.lookAt(this.targetLookAt);
    }

    // ==========================================
    // 4. Construct SVEW-RD-50 Procedural 3D Model
    // ==========================================
    buildMachine3D() {
        this.machineGroup = new THREE.Group();
        this.scene.add(this.machineGroup);

        // Premium Industrial Machinery Materials (High definition contrast)
        this.materials = {
            castIron: new THREE.MeshStandardMaterial({
                color: 0x1e3a8a, // Rich Industrial Precision Blue Enamel
                metalness: 0.65,
                roughness: 0.28
            }),
            darkBase: new THREE.MeshStandardMaterial({
                color: 0x1e293b, // Heavy FG-260 Cast Iron Base
                metalness: 0.75,
                roughness: 0.35
            }),
            groundSteel: new THREE.MeshStandardMaterial({
                color: 0xf8fafc, // Polished Hardened Chrome Column & Spindle
                metalness: 0.98,
                roughness: 0.12
            }),
            gearAlloy: new THREE.MeshStandardMaterial({
                color: 0xfbbf24, // High-visibility Amber-Gold 20MnCr5 Gears
                metalness: 0.92,
                roughness: 0.2
            }),
            brass: new THREE.MeshStandardMaterial({
                color: 0xf59e0b,
                metalness: 0.88,
                roughness: 0.25
            }),
            cyanGlow: new THREE.MeshStandardMaterial({
                color: 0x00f0ff,
                emissive: 0x00f0ff,
                emissiveIntensity: 0.7,
                metalness: 0.5,
                roughness: 0.2
            }),
            workpiece: new THREE.MeshStandardMaterial({
                color: 0x64748b,
                metalness: 0.85,
                roughness: 0.22
            })
        };

        // --- SUB-ASSEMBLY 1: HEAVY FOUNDATION BASE ---
        this.baseGroup = new THREE.Group();
        this.machineGroup.add(this.baseGroup);

        // Bottom plinth
        const baseGeom = new THREE.BoxGeometry(160, 16, 260);
        const baseMesh = new THREE.Mesh(baseGeom, this.materials.darkBase);
        baseMesh.position.set(40, -52, 0);
        baseMesh.receiveShadow = true;
        this.baseGroup.add(baseMesh);

        // Ground T-Slot Table Base
        const topTableGeom = new THREE.BoxGeometry(150, 6, 250);
        const topTable = new THREE.Mesh(topTableGeom, this.materials.groundSteel);
        topTable.position.set(40, -41, 0);
        this.baseGroup.add(topTable);

        // T-Slots channels on base
        for (let i = -60; i <= 60; i += 30) {
            const slotGeom = new THREE.BoxGeometry(140, 2, 6);
            const slot = new THREE.Mesh(slotGeom, this.materials.darkBase);
            slot.position.set(40, -38, i);
            this.baseGroup.add(slot);
        }

        // --- SUB-ASSEMBLY 2: REMOVABLE BOX TABLE & WORKPIECE ---
        this.tableGroup = new THREE.Group();
        this.machineGroup.add(this.tableGroup);

        const boxGeom = new THREE.BoxGeometry(70, 50, 70);
        const boxMesh = new THREE.Mesh(boxGeom, this.materials.castIron);
        boxMesh.position.set(50, -15, 60);
        this.tableGroup.add(boxMesh);

        const boxTopGeom = new THREE.BoxGeometry(74, 4, 74);
        const boxTop = new THREE.Mesh(boxTopGeom, this.materials.groundSteel);
        boxTop.position.set(50, 12, 60);
        this.tableGroup.add(boxTop);

        // Workpiece Metal Block on top of table
        const workGeom = new THREE.BoxGeometry(36, 18, 36);
        this.workpieceMesh = new THREE.Mesh(workGeom, this.materials.workpiece);
        this.workpieceMesh.position.set(50, 23, 60);
        this.tableGroup.add(this.workpieceMesh);

        // Workpiece clamp
        const clampGeom = new THREE.BoxGeometry(10, 8, 44);
        const clamp = new THREE.Mesh(clampGeom, this.materials.darkBase);
        clamp.position.set(30, 22, 60);
        this.tableGroup.add(clamp);

        // --- SUB-ASSEMBLY 3: HEAVY GROUND VERTICAL COLUMN ---
        this.columnGroup = new THREE.Group();
        this.machineGroup.add(this.columnGroup);

        // Column Base Flange
        const colBaseGeom = new THREE.CylinderGeometry(36, 42, 24, 32);
        const colBase = new THREE.Mesh(colBaseGeom, this.materials.darkBase);
        colBase.position.set(-30, -32, -60);
        this.columnGroup.add(colBase);

        // Main Cylindrical Column Pipe (Ground Finish)
        const colGeom = new THREE.CylinderGeometry(28, 28, 200, 32);
        this.columnMesh = new THREE.Mesh(colGeom, this.materials.groundSteel);
        this.columnMesh.position.set(-30, 70, -60);
        this.columnGroup.add(this.columnMesh);

        // Top Column Cap with Arm Elevating Motor
        const capGeom = new THREE.CylinderGeometry(30, 30, 16, 32);
        const colCap = new THREE.Mesh(capGeom, this.materials.castIron);
        colCap.position.set(-30, 178, -60);
        this.columnGroup.add(colCap);

        const eleMotorGeom = new THREE.CylinderGeometry(14, 14, 28, 24);
        const eleMotor = new THREE.Mesh(eleMotorGeom, this.materials.cyanGlow);
        eleMotor.position.set(-30, 200, -60);
        this.columnGroup.add(eleMotor);

        // Elevating Leadscrew shaft
        const screwGeom = new THREE.CylinderGeometry(3, 3, 190, 16);
        const screw = new THREE.Mesh(screwGeom, this.materials.gearAlloy);
        screw.position.set(-3, 70, -60);
        this.columnGroup.add(screw);

        // --- SUB-ASSEMBLY 4: RADIAL ARM (Swivels 360° & Elevates) ---
        // Pivot point sits on column axis (-30, 0, -60)
        this.armPivot = new THREE.Group();
        this.armPivot.position.set(-30, 80, -60);
        this.machineGroup.add(this.armPivot);

        // Arm Sleeve (Clamps onto column)
        const sleeveGeom = new THREE.CylinderGeometry(32, 32, 55, 32);
        this.sleeveMesh = new THREE.Mesh(sleeveGeom, this.materials.castIron);
        this.armPivot.add(this.sleeveMesh);

        // Horizontal Arm Beam (extends forward in +Z direction)
        const armBeamGeom = new THREE.BoxGeometry(32, 40, 190);
        this.armBeamMesh = new THREE.Mesh(armBeamGeom, this.materials.castIron);
        this.armBeamMesh.position.set(0, 5, 95);
        this.armPivot.add(this.armBeamMesh);

        // Dual Dovetail Guideways on Arm (Ground finish)
        const guideTopGeom = new THREE.BoxGeometry(26, 4, 185);
        const guideTop = new THREE.Mesh(guideTopGeom, this.materials.groundSteel);
        guideTop.position.set(0, 26, 95);
        this.armPivot.add(guideTop);

        const guideBottomGeom = new THREE.BoxGeometry(26, 4, 185);
        const guideBottom = new THREE.Mesh(guideBottomGeom, this.materials.groundSteel);
        guideBottom.position.set(0, -16, 95);
        this.armPivot.add(guideBottom);

        // --- SUB-ASSEMBLY 5: DRILL HEADSTOCK (Traverses along Arm) ---
        this.headstock = new THREE.Group();
        this.headstock.position.set(0, 0, 120); // Default position over table
        this.armPivot.add(this.headstock);

        // Main Gearbox Housing
        const headGeom = new THREE.BoxGeometry(42, 54, 48);
        this.headCasingMesh = new THREE.Mesh(headGeom, this.materials.castIron);
        this.headCasingMesh.position.set(0, 12, 0);
        this.headstock.add(this.headCasingMesh);

        // Top Main Spindle Motor
        const motorGeom = new THREE.CylinderGeometry(15, 15, 32, 24);
        const motor = new THREE.Mesh(motorGeom, this.materials.cyanGlow);
        motor.position.set(0, 55, 0);
        this.headstock.add(motor);

        // SVEW Brand Plate on Gearbox
        const plateGeom = new THREE.BoxGeometry(43, 14, 28);
        const plate = new THREE.Mesh(plateGeom, this.materials.brass);
        plate.position.set(0, 16, 0);
        this.headstock.add(plate);

        // Speed Selector Dial
        const dialGeom = new THREE.CylinderGeometry(7, 7, 6, 16);
        const dial = new THREE.Mesh(dialGeom, this.materials.groundSteel);
        dial.rotation.z = Math.PI / 2;
        dial.position.set(22, 24, 10);
        this.headstock.add(dial);

        // Star Feed Handwheel / Capstan Levers
        this.feedWheel = new THREE.Group();
        this.feedWheel.position.set(23, 0, 0);
        this.headstock.add(this.feedWheel);

        const hubGeom = new THREE.CylinderGeometry(6, 6, 6, 16);
        const hub = new THREE.Mesh(hubGeom, this.materials.darkBase);
        hub.rotation.z = Math.PI / 2;
        this.feedWheel.add(hub);

        for (let a = 0; a < 4; a++) {
            const spokeGeom = new THREE.CylinderGeometry(1.5, 1.5, 26, 8);
            const spoke = new THREE.Mesh(spokeGeom, this.materials.groundSteel);
            spoke.rotation.x = (a * Math.PI) / 2;
            spoke.position.y = 0;
            this.feedWheel.add(spoke);

            const knobGeom = new THREE.SphereGeometry(3, 12, 12);
            const knob = new THREE.Mesh(knobGeom, this.materials.brass);
            knob.position.set(0, Math.sin(a * Math.PI / 2) * 13, Math.cos(a * Math.PI / 2) * 13);
            this.feedWheel.add(knob);
        }

        // --- SUB-ASSEMBLY 6: INTERNAL ALLOY GEAR TRAIN (Visible in X-Ray) ---
        this.gearsGroup = new THREE.Group();
        this.headstock.add(this.gearsGroup);

        this.gearMeshes = [];
        const gearConfigs = [
            { r: 12, h: 6, x: 0, y: 30, z: -10, speed: 1.0 },
            { r: 8,  h: 6, x: 0, y: 30, z: 8,   speed: -1.5 },
            { r: 14, h: 6, x: 0, y: 15, z: 0,   speed: 0.8 },
            { r: 10, h: 6, x: 0, y: 2,  z: -6,  speed: -1.2 },
            { r: 16, h: 8, x: 0, y: -8, z: 4,   speed: 0.6 }
        ];

        gearConfigs.forEach(cfg => {
            const gGeom = new THREE.CylinderGeometry(cfg.r, cfg.r, cfg.h, 24);
            const gMesh = new THREE.Mesh(gGeom, this.materials.gearAlloy);
            gMesh.position.set(cfg.x, cfg.y, cfg.z);
            gMesh.rotation.z = Math.PI / 2;
            gMesh.userData.speed = cfg.speed;
            this.gearsGroup.add(gMesh);
            this.gearMeshes.push(gMesh);
        });

        // --- SUB-ASSEMBLY 7: SPINDLE QUILL & ROTATING DRILL BIT ---
        this.quillGroup = new THREE.Group();
        this.quillGroup.position.set(0, -15, 12);
        this.headstock.add(this.quillGroup);

        // Ground Spindle Quill Sleeve (feeds downward)
        const quillGeom = new THREE.CylinderGeometry(11, 11, 40, 24);
        const quill = new THREE.Mesh(quillGeom, this.materials.groundSteel);
        quill.position.set(0, -20, 0);
        this.quillGroup.add(quill);

        // Morse Taper Tool Chuck (Spins)
        this.spindleRotator = new THREE.Group();
        this.spindleRotator.position.set(0, -40, 0);
        this.quillGroup.add(this.spindleRotator);

        const chuckGeom = new THREE.CylinderGeometry(10, 8, 16, 20);
        const chuck = new THREE.Mesh(chuckGeom, this.materials.darkBase);
        this.spindleRotator.add(chuck);

        // Precision Drill Bit (Hardened Flutes)
        const bitGeom = new THREE.CylinderGeometry(4.5, 4.5, 36, 16);
        const bit = new THREE.Mesh(bitGeom, this.materials.gearAlloy);
        bit.position.set(0, -25, 0);
        this.spindleRotator.add(bit);

        // Cutting Chisel Tip
        const tipGeom = new THREE.ConeGeometry(4.5, 7, 16);
        const tip = new THREE.Mesh(tipGeom, this.materials.gearAlloy);
        tip.position.set(0, -46, 0);
        tip.rotation.x = Math.PI;
        this.spindleRotator.add(tip);

        // Cutting Contact Point (For sparks emitter)
        this.drillTipPosition = new THREE.Vector3();
    }

    // ==========================================
    // 5. Dynamic Particle System (Metal Cutting Sparks)
    // ==========================================
    setupParticleSystem() {
        const particleCount = 150;
        const geom = new THREE.BufferGeometry();
        const positions = new Float32Array(particleCount * 3);
        const velocities = new Float32Array(particleCount * 3);
        const lifetimes = new Float32Array(particleCount);

        for (let i = 0; i < particleCount; i++) {
            positions[i * 3 + 0] = 0;
            positions[i * 3 + 1] = -9999;
            positions[i * 3 + 2] = 0;
            velocities[i * 3 + 0] = (Math.random() - 0.5) * 4;
            velocities[i * 3 + 1] = Math.random() * 3 + 1;
            velocities[i * 3 + 2] = (Math.random() - 0.5) * 4;
            lifetimes[i] = 0;
        }

        geom.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        this.sparkVelocities = velocities;
        this.sparkLifetimes = lifetimes;

        const pMaterial = new THREE.PointsMaterial({
            color: 0xffaa00,
            size: 2.5,
            blending: THREE.AdditiveBlending,
            transparent: true,
            opacity: 0.9
        });

        this.sparkParticles = new THREE.Points(geom, pMaterial);
        this.scene.add(this.sparkParticles);
    }

    updateSparks(isEmitting) {
        if (!this.sparkParticles) return;
        const positions = this.sparkParticles.geometry.attributes.position.array;
        const v = this.sparkVelocities;
        const l = this.sparkLifetimes;
        const count = l.length;

        // Get world tip position
        const tipWorld = new THREE.Vector3(0, -47, 0);
        this.spindleRotator.localToWorld(tipWorld);

        for (let i = 0; i < count; i++) {
            l[i] -= 0.04;
            if (l[i] <= 0) {
                if (isEmitting) {
                    positions[i * 3 + 0] = tipWorld.x + (Math.random() - 0.5) * 3;
                    positions[i * 3 + 1] = tipWorld.y;
                    positions[i * 3 + 2] = tipWorld.z + (Math.random() - 0.5) * 3;
                    v[i * 3 + 0] = (Math.random() - 0.5) * 5;
                    v[i * 3 + 1] = Math.random() * 4 + 1.5;
                    v[i * 3 + 2] = (Math.random() - 0.5) * 5;
                    l[i] = Math.random() * 0.8 + 0.2;
                } else {
                    positions[i * 3 + 1] = -9999;
                }
            } else {
                positions[i * 3 + 0] += v[i * 3 + 0] * 0.4;
                positions[i * 3 + 1] += v[i * 3 + 1] * 0.4;
                positions[i * 3 + 2] += v[i * 3 + 2] * 0.4;
                v[i * 3 + 1] -= 0.15; // Gravity
            }
        }
        this.sparkParticles.geometry.attributes.position.needsUpdate = true;
    }

    // ==========================================
    // 6. Kinematic Controls (Sliders & Actions)
    // ==========================================
    setArmAngle(degrees) {
        this.armAngle = (degrees * Math.PI) / 180;
        this.armPivot.rotation.y = this.armAngle;
    }

    setArmHeight(val) {
        this.armHeight = val; // -20 to 50
        this.armPivot.position.y = 80 + this.armHeight;
    }

    setHeadPosition(val) {
        this.headPosition = val; // 50 to 160
        this.headstock.position.z = val;
    }

    setQuillFeed(val) {
        this.quillFeed = val; // 0 to 45
        this.quillGroup.position.y = -15 - this.quillFeed;
        if (this.feedWheel) {
            this.feedWheel.rotation.x = this.quillFeed * 0.15;
        }
    }

    setExplodedView(amount) {
        this.explodedAmount = amount; // 0 to 1
        // Smoothly disassemble sub-assemblies outwards
        this.columnGroup.position.x = -this.explodedAmount * 60;
        this.columnGroup.position.z = -60 - this.explodedAmount * 60;
        this.armPivot.position.y = 80 + this.armHeight + this.explodedAmount * 50;
        this.headstock.position.z = this.headPosition + this.explodedAmount * 70;
        this.tableGroup.position.x = 50 + this.explodedAmount * 50;
        this.tableGroup.position.z = 60 + this.explodedAmount * 50;
        this.quillGroup.position.y = -15 - this.quillFeed - this.explodedAmount * 40;
    }

    setVisualTheme(theme) {
        this.themeMode = theme;
        if (theme === 'hologram') {
            // Cyber Cyan Wireframe Hologram
            this.scene.background = new THREE.Color(0x020617);
            this.materials.castIron.wireframe = true;
            this.materials.castIron.color.setHex(0x00f0ff);
            this.materials.groundSteel.wireframe = true;
            this.materials.groundSteel.color.setHex(0x38bdf8);
            this.materials.darkBase.wireframe = true;
            this.materials.darkBase.color.setHex(0x1e3a8a);
        } else if (theme === 'xray') {
            // X-Ray Gearbox View (Transparent casing, glowing gears)
            this.scene.background = new THREE.Color(0x080c14);
            this.materials.castIron.wireframe = false;
            this.materials.castIron.transparent = true;
            this.materials.castIron.opacity = 0.25;
            this.materials.castIron.color.setHex(0x00e5ff);
            this.materials.groundSteel.transparent = false;
            this.materials.groundSteel.wireframe = false;
            this.materials.gearAlloy.color.setHex(0xf59e0b);
            this.materials.gearAlloy.emissive = new THREE.Color(0xf59e0b);
            this.materials.gearAlloy.emissiveIntensity = 0.5;
        } else {
            // Realistic Cast Iron
            this.scene.background = new THREE.Color(0x060911);
            this.materials.castIron.wireframe = false;
            this.materials.castIron.transparent = false;
            this.materials.castIron.opacity = 1.0;
            this.materials.castIron.color.setHex(0x172554);
            this.materials.groundSteel.wireframe = false;
            this.materials.groundSteel.color.setHex(0xe2e8f0);
            this.materials.darkBase.wireframe = false;
            this.materials.darkBase.color.setHex(0x0f172a);
            this.materials.gearAlloy.emissiveIntensity = 0;
        }
    }

    startDrillingSimulation(targetDepth = 35, rpm = 650) {
        if (this.isDrilling) return;
        this.isDrilling = true;
        this.targetRPM = rpm;
        let currentFeed = 0;
        let phase = 'down'; // 'down', 'dwell', 'retract'

        const step = () => {
            if (!this.isDrilling) return;

            if (phase === 'down') {
                currentFeed += 0.35;
                this.setQuillFeed(currentFeed);

                // Telemetry during cutting
                if (currentFeed >= 28) {
                    this.isCutting = true;
                    this.telemetry.loadPercent = Math.min(85, 45 + (currentFeed - 28) * 4);
                    this.telemetry.torque = 38.5 + Math.random() * 2.5;
                    this.telemetry.vibration = 0.38 + Math.random() * 0.12;
                    this.telemetry.depthMM = ((currentFeed - 28) * 1.8).toFixed(1);
                    this.telemetry.bearingTemp = (34.2 + (currentFeed - 28) * 0.2).toFixed(1);
                } else {
                    this.isCutting = false;
                    this.telemetry.loadPercent = 18;
                    this.telemetry.torque = 5.2;
                    this.telemetry.vibration = 0.12;
                }

                if (currentFeed >= targetDepth) {
                    phase = 'dwell';
                    setTimeout(() => { phase = 'retract'; }, 800);
                }
            } else if (phase === 'retract') {
                currentFeed -= 0.8;
                this.isCutting = false;
                this.telemetry.loadPercent = 20;
                this.setQuillFeed(currentFeed);
                if (currentFeed <= 0) {
                    this.setQuillFeed(0);
                    this.isDrilling = false;
                    this.isCutting = false;
                    this.telemetry.loadPercent = 0;
                    this.telemetry.torque = 0;
                    this.telemetry.vibration = 0.08;
                    this.updateTelemetryUI();
                    return;
                }
            }
            this.updateTelemetryUI();
            requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    }

    stopSimulation() {
        this.isDrilling = false;
        this.isCutting = false;
        this.setQuillFeed(0);
        this.updateTelemetryUI();
    }

    setDrilling(active) {
        if (active) {
            this.isDrilling = true;
            this.isCutting = true;
            this.targetRPM = 850;
            this.spindleSpeed = 850;
            this.setQuillFeed(28);
            this.telemetry.loadPercent = 75;
            this.telemetry.torque = 32.5;
            this.telemetry.vibration = 0.42;
            this.telemetry.depthMM = '18.5';
        } else {
            this.stopSimulation();
        }
        this.updateTelemetryUI();
    }

    // ==========================================
    // 7. Telemetry HUD Updates
    // ==========================================
    updateTelemetryUI() {
        const rpmElem = document.getElementById('hudRPM');
        const loadElem = document.getElementById('hudLoad');
        const torqueElem = document.getElementById('hudTorque');
        const vibElem = document.getElementById('hudVib');
        const tempElem = document.getElementById('hudTemp');
        const depthElem = document.getElementById('hudDepth');

        if (rpmElem) rpmElem.innerText = Math.round(this.spindleSpeed);
        if (loadElem) loadElem.innerText = Math.round(this.telemetry.loadPercent) + '%';
        if (torqueElem) torqueElem.innerText = this.telemetry.torque ? this.telemetry.torque.toFixed(1) + ' Nm' : '0.0 Nm';
        if (vibElem) vibElem.innerText = this.telemetry.vibration.toFixed(2) + ' mm/s';
        if (tempElem) tempElem.innerText = this.telemetry.bearingTemp + ' °C';
        if (depthElem) depthElem.innerText = this.telemetry.depthMM + ' mm';
    }

    setupEventListeners() {
        const resizeHandler = () => {
            if (!this.container) return;
            const width = this.container.clientWidth || 300;
            const height = this.container.clientHeight || 300;
            this.camera.aspect = width / height;
            this.camera.updateProjectionMatrix();
            this.renderer.setSize(width, height);
        };

        window.addEventListener('resize', resizeHandler);

        if (window.ResizeObserver && this.container) {
            const ro = new ResizeObserver(resizeHandler);
            ro.observe(this.container);
        }
    }

    // Rotate view by exact degrees (e.g. 30 degrees)
    rotateDegrees(deg = 30) {
        const rad = (deg * Math.PI) / 180;
        this.spherical.theta += rad;
        this.updateCameraPosition();
    }

    // ==========================================
    // 8. Main Render Animation Loop
    // ==========================================
    animate() {
        requestAnimationFrame(this.animate);

        // Smooth RPM ramp up
        if (this.isDrilling) {
            this.spindleSpeed += (this.targetRPM - this.spindleSpeed) * 0.1;
        } else {
            this.spindleSpeed += (0 - this.spindleSpeed) * 0.08;
        }

        // Rotate Spindle Drill Bit
        if (this.spindleRotator && this.spindleSpeed > 1) {
            this.spindleRotator.rotation.y += (this.spindleSpeed / 60) * 0.105;
        }

        // Rotate Internal Gears
        if (this.gearMeshes && this.spindleSpeed > 1) {
            this.gearMeshes.forEach(mesh => {
                mesh.rotation.y += (this.spindleSpeed / 60) * 0.08 * mesh.userData.speed;
            });
        }

        // Update Particle Sparks
        this.updateSparks(this.isCutting);

        // Audio Update
        this.updateAudio(this.spindleSpeed, this.isCutting);

        // Subtle Auto Rotation if enabled
        if (this.options.autoRotate && !this.isDragging && !this.isPanning) {
            this.spherical.theta += 0.002;
            this.updateCameraPosition();
        }

        this.renderer.render(this.scene, this.camera);
    }
}

window.Machine6DViewer = Machine6DViewer;
