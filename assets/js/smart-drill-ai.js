/**
 * SVEW AI-SmartDrill™ Physics & Metallurgical Optimizer
 * World-First Industrial AI Machining Intelligence Engine
 * Shree Varsha Engineering Works
 */

const SmartDrillAI = {
    // Metallurgical Materials Database
    materials: {
        'en8_ms': {
            name: 'Mild & Carbon Steel (EN8 / IS2062)',
            vc_hss: 28,      // m/min
            vc_carbide: 85,
            kc: 1950,        // Specific cutting force N/mm2
            coolant: 'Water Soluble Synthetic Emulsion (1:20 Ratio)',
            chip_type: 'Continuous spiral curled chips',
            hardness: '160 - 200 BHN'
        },
        'en24_alloy': {
            name: 'High Tensile Alloy Steel (EN24 / 4140)',
            vc_hss: 20,
            vc_carbide: 65,
            kc: 2450,
            coolant: 'High Pressure Sulfurized EP Cutting Oil',
            chip_type: 'Tightly sheared discontinuous chips',
            hardness: '260 - 320 BHN'
        },
        'cast_iron_25': {
            name: 'Seasoned Cast Iron (Grade 25 / FG260)',
            vc_hss: 24,
            vc_carbide: 75,
            kc: 1150,
            coolant: 'Dry with Air Jet or Synthetic Micro-Mist',
            chip_type: 'Segmented granular powder chips',
            hardness: '180 - 220 BHN'
        },
        'ss_304': {
            name: 'Stainless Steel (SS304 / SS316)',
            vc_hss: 16,
            vc_carbide: 55,
            kc: 2650,
            coolant: 'Heavy Chlorinated Neat Neat Oil',
            chip_type: 'Tough work-hardening continuous ribbon',
            hardness: '180 - 230 BHN'
        },
        'hardox_500': {
            name: 'Abrasion Resistant Steel (Hardox 450/500)',
            vc_hss: 10,
            vc_carbide: 38,
            kc: 3400,
            coolant: 'Flood Coolant with Extreme Pressure Additives',
            chip_type: 'Short brittle chips with high thermal load',
            hardness: '450 - 520 BHN'
        },
        'aluminum_6061': {
            name: 'Duralumin / Aluminum 6061-T6',
            vc_hss: 75,
            vc_carbide: 220,
            kc: 750,
            coolant: 'Light Alcohol / Kerosene / Soluble Oil',
            chip_type: 'High speed continuous ductile curls',
            hardness: '90 - 105 BHN'
        },
        'brass_c360': {
            name: 'Free Machining Brass (C360)',
            vc_hss: 55,
            vc_carbide: 160,
            kc: 850,
            coolant: 'Dry or Light Mineral Oil',
            chip_type: 'Small segmented needle chips',
            hardness: '120 - 150 BHN'
        }
    },

    // SVEW Machine Database
    machines: [
        { model: 'SVEW-BD-20', name: '20mm High Speed Precision Bench Drill', maxCap: 20, maxPowerHP: 0.75, maxTorque: 25 },
        { model: 'SVEW-PD-25', name: '25mm Heavy Duty Pillar Drill Machine', maxCap: 25, maxPowerHP: 1.0, maxTorque: 45 },
        { model: 'SVEW-PD-32', name: '32mm Geared Column Pillar Drill Machine', maxCap: 32, maxPowerHP: 1.5, maxTorque: 80 },
        { model: 'SVEW-RD-40', name: '40mm All-Geared Radial Drill Machine', maxCap: 40, maxPowerHP: 2.0, maxTorque: 140 },
        { model: 'SVEW-RD-50', name: '50mm Heavy Duty Precision Radial Drill', maxCap: 50, maxPowerHP: 3.0, maxTorque: 240 },
        { model: 'SVEW-RD-65', name: '65mm Extra Heavy Industrial Radial Drill', maxCap: 65, maxPowerHP: 5.0, maxTorque: 420 }
    ],

    // Available Standard Gearbox RPM Steps across machines
    gearboxSpeeds: [35, 50, 70, 90, 120, 160, 220, 310, 450, 630, 850, 1150, 1600, 2200],

    calculate(materialKey, diameterMM, depthMM, toolType = 'hss') {
        const mat = this.materials[materialKey] || this.materials['en8_ms'];
        const D = Math.max(3, Math.min(65, parseFloat(diameterMM) || 25));
        const L = Math.max(5, parseFloat(depthMM) || 40);

        // Cutting Speed Vc
        const Vc = toolType === 'carbide' ? mat.vc_carbide : mat.vc_hss;

        // Theoretical Spindle RPM = (1000 * Vc) / (PI * D)
        const theoreticalRPM = (1000 * Vc) / (Math.PI * D);

        // Find nearest actual industrial gearbox speed step
        let optimalRPM = this.gearboxSpeeds[0];
        let minDiff = Infinity;
        for (const speed of this.gearboxSpeeds) {
            const diff = Math.abs(speed - theoreticalRPM);
            if (diff < minDiff) {
                minDiff = diff;
                optimalRPM = speed;
            }
        }

        // Feed Rate f (mm/rev) based on diameter and material hardness
        let feedPerRev = 0.05 + (D / 65) * 0.28;
        if (mat.kc > 2500) feedPerRev *= 0.75; // Tougher material lower feed
        if (mat.kc < 1000) feedPerRev *= 1.35; // Softer material higher feed
        feedPerRev = Math.round(feedPerRev * 100) / 100;

        // Penetration Rate vf = N * f (mm/min)
        const penetrationRate = Math.round(optimalRPM * feedPerRev);

        // Cycle Time = (L + 0.3*D) / vf (minutes)
        const totalTravel = L + (0.3 * D); // including point apex lead
        const cycleTimeSeconds = Math.max(1, Math.round((totalTravel / penetrationRate) * 60));

        // Material Removal Rate MRR (cm3/min) = (PI * D^2 / 4) * vf / 1000
        const mrr = Math.round(((Math.PI * Math.pow(D, 2)) / 4) * penetrationRate / 1000 * 10) / 10;

        // Cutting Torque Mc (Nm) = (kc * D^2 * f) / 8000
        const torqueNm = Math.round((mat.kc * Math.pow(D, 2) * feedPerRev) / 8000 * 10) / 10;

        // Spindle Motor Power P (kW) = (Mc * N) / 9550
        const powerKW = Math.round(((torqueNm * optimalRPM) / 9550) * 10) / 10;
        const powerHP = Math.round((powerKW * 1.341) * 10) / 10;

        // Axial Thrust Force Ff (N)
        const thrustForceN = Math.round(0.48 * mat.kc * D * feedPerRev);

        // Match Recommended Shree Varsha Drill Machine
        let recommendedMachine = this.machines[this.machines.length - 1];
        for (const m of this.machines) {
            if (D <= m.maxCap && powerHP <= m.maxPowerHP * 1.15) {
                recommendedMachine = m;
                break;
            }
        }

        // Safety Reserve %
        const torqueReserve = Math.round(((recommendedMachine.maxTorque - torqueNm) / recommendedMachine.maxTorque) * 100);

        return {
            diameter: D,
            depth: L,
            materialName: mat.name,
            hardness: mat.hardness,
            coolant: mat.coolant,
            chipType: mat.chip_type,
            optimalRPM,
            cuttingSpeedVc: Vc,
            feedPerRev,
            penetrationRate,
            cycleTimeSeconds,
            mrr,
            torqueNm,
            powerKW,
            powerHP,
            thrustForceN,
            recommendedMachine,
            torqueReserve: Math.max(5, torqueReserve)
        };
    }
};

window.SmartDrillAI = SmartDrillAI;
