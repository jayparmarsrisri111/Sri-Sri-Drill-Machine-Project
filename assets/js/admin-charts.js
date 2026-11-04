/**
 * Standalone Offline High-DPI Canvas Chart Engine
 * Shree Varsha Engineering Works - Varsha Brand Admin Panel
 * Works 100% offline without any external CDNs or libraries.
 */

(function() {
    function setupCanvas(canvas) {
        const dpr = window.devicePixelRatio || 1;
        const rect = canvas.getBoundingClientRect();
        const w = (rect.width > 20) ? rect.width : (canvas.parentElement ? canvas.parentElement.clientWidth : 450);
        const h = (rect.height > 20) ? rect.height : (canvas.parentElement ? canvas.parentElement.clientHeight : 250);
        canvas.width = w * dpr;
        canvas.height = h * dpr;
        const ctx = canvas.getContext('2d');
        ctx.scale(dpr, dpr);
        return { ctx, width: w, height: h };
    }

    // 1. Render Sales & Benefit/Loss Multi-Bar & Line Graph
    window.renderSalesProfitGraph = function(canvasId, dynamicConfig) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        const { ctx, width, height } = setupCanvas(canvas);

        const labels = (dynamicConfig && dynamicConfig.labels && dynamicConfig.labels.length) ? dynamicConfig.labels : ['May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'];
        const salesData = (dynamicConfig && dynamicConfig.sales && dynamicConfig.sales.length) ? dynamicConfig.sales : [11.5, 14.2, 12.8, 16.5, 13.9, 15.7];
        const costData  = (dynamicConfig && dynamicConfig.costs && dynamicConfig.costs.length) ? dynamicConfig.costs : [7.4, 9.1, 8.2, 10.6, 8.9, 10.1];
        const profitData = (dynamicConfig && dynamicConfig.profits && dynamicConfig.profits.length) ? dynamicConfig.profits : [4.1, 5.1, 4.6, 5.9, 5.0, 5.6];

        const padding = { top: 35, right: 30, bottom: 40, left: 55 };
        const chartW = width - padding.left - padding.right;
        const chartH = height - padding.top - padding.bottom;
        const maxVal = Math.max(20, Math.ceil(Math.max(...salesData) * 1.25));

        // Background
        ctx.clearRect(0, 0, width, height);

        // Draw Y-axis gridlines & labels
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.06)';
        ctx.fillStyle = '#94a3b8';
        ctx.font = '11px Inter, sans-serif';
        ctx.textAlign = 'right';

        const ySteps = 5;
        for (let i = 0; i <= ySteps; i++) {
            const val = (maxVal / ySteps) * i;
            const y = padding.top + chartH - (i / ySteps) * chartH;
            ctx.beginPath();
            ctx.moveTo(padding.left, y);
            ctx.lineTo(width - padding.right, y);
            ctx.stroke();
            ctx.fillText('₹ ' + val + 'L', padding.left - 10, y + 4);
        }

        // Draw Bars
        const stepX = chartW / labels.length;
        const barW = Math.min(stepX * 0.28, 20);

        for (let i = 0; i < labels.length; i++) {
            const centerX = padding.left + stepX * i + stepX / 2;

            // X-axis Label
            ctx.fillStyle = '#cbd5e1';
            ctx.font = '600 12px Inter, sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(labels[i], centerX, height - 15);

            // Bar 1: Gross Sales (Cyan gradient)
            const sVal = salesData[i];
            const sH = (sVal / maxVal) * chartH;
            const sX = centerX - barW - 2;
            const sY = padding.top + chartH - sH;

            const gradS = ctx.createLinearGradient(0, sY, 0, sY + sH);
            gradS.addColorStop(0, '#00e5ff');
            gradS.addColorStop(1, 'rgba(0, 229, 255, 0.25)');
            ctx.fillStyle = gradS;
            roundRect(ctx, sX, sY, barW, sH, 4, true);

            // Bar 2: Production Cost (Red/Coral gradient)
            const cVal = costData[i];
            const cH = (cVal / maxVal) * chartH;
            const cX = centerX + 2;
            const cY = padding.top + chartH - cH;

            const gradC = ctx.createLinearGradient(0, cY, 0, cY + cH);
            gradC.addColorStop(0, '#f87171');
            gradC.addColorStop(1, 'rgba(239, 68, 68, 0.25)');
            ctx.fillStyle = gradC;
            roundRect(ctx, cX, cY, barW, cH, 4, true);
        }

        // Line & Area for Net Profit (Emerald Green)
        ctx.beginPath();
        const pts = [];
        for (let i = 0; i < labels.length; i++) {
            const centerX = padding.left + stepX * i + stepX / 2;
            const pVal = profitData[i];
            const pY = padding.top + chartH - (pVal / maxVal) * chartH;
            pts.push({ x: centerX, y: pY, val: pVal });
        }

        // Draw Area under line
        ctx.beginPath();
        ctx.moveTo(pts[0].x, pts[0].y);
        for (let i = 1; i < pts.length; i++) {
            ctx.lineTo(pts[i].x, pts[i].y);
        }
        ctx.lineTo(pts[pts.length - 1].x, padding.top + chartH);
        ctx.lineTo(pts[0].x, padding.top + chartH);
        ctx.closePath();
        const gradA = ctx.createLinearGradient(0, padding.top, 0, padding.top + chartH);
        gradA.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
        gradA.addColorStop(1, 'rgba(16, 185, 129, 0.0)');
        ctx.fillStyle = gradA;
        ctx.fill();

        // Draw Line
        ctx.beginPath();
        ctx.moveTo(pts[0].x, pts[0].y);
        for (let i = 1; i < pts.length; i++) {
            ctx.lineTo(pts[i].x, pts[i].y);
        }
        ctx.strokeStyle = '#10b981';
        ctx.lineWidth = 3;
        ctx.shadowColor = 'rgba(16, 185, 129, 0.5)';
        ctx.shadowBlur = 8;
        ctx.stroke();
        ctx.shadowBlur = 0; // reset

        // Draw Points with glowing circles
        for (let i = 0; i < pts.length; i++) {
            ctx.beginPath();
            ctx.arc(pts[i].x, pts[i].y, 4.5, 0, Math.PI * 2);
            ctx.fillStyle = '#34d399';
            ctx.fill();
            ctx.strokeStyle = '#060911';
            ctx.lineWidth = 2;
            ctx.stroke();

            // Label above point
            ctx.fillStyle = '#34d399';
            ctx.font = '700 10.5px Inter, sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('₹' + pts[i].val + 'L', pts[i].x, pts[i].y - 8);
        }

        // Legend at top
        drawLegend(ctx, padding.left, 15, [
            { label: 'Gross Sales', color: '#00e5ff' },
            { label: 'Production Cost', color: '#f87171' },
            { label: 'Net Profit Margin', color: '#10b981', isLine: true }
        ]);
    };

    // 2. Render Product Demand Doughnut Graph
    window.renderProductShareGraph = function(canvasId, dynamicData) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        const { ctx, width, height } = setupCanvas(canvas);

        const data = (dynamicData && dynamicData.length) ? dynamicData : [
            { label: '40mm Radial', value: 42, color: '#00e5ff' },
            { label: '50mm Heavy', value: 28, color: '#10b981' },
            { label: '65mm Heavy', value: 16, color: '#f59e0b' },
            { label: 'Pillar Drills', value: 10, color: '#8b5cf6' },
            { label: 'Spare Parts', value: 4, color: '#ec4899' }
        ];

        ctx.clearRect(0, 0, width, height);

        const centerX = width / 2;
        const centerY = height / 2;
        const outerR = Math.min(centerX, centerY) - 15;
        const innerR = outerR * 0.65;

        const total = data.reduce((acc, d) => acc + d.value, 0) || 1;
        let startAngle = -Math.PI / 2;

        for (let i = 0; i < data.length; i++) {
            const sliceAngle = (data[i].value / total) * Math.PI * 2;
            const endAngle = startAngle + sliceAngle;

            ctx.beginPath();
            ctx.arc(centerX, centerY, outerR, startAngle, endAngle);
            ctx.arc(centerX, centerY, innerR, endAngle, startAngle, true);
            ctx.closePath();

            ctx.fillStyle = data[i].color;
            ctx.shadowColor = data[i].color;
            ctx.shadowBlur = 4;
            ctx.fill();
            ctx.shadowBlur = 0;

            ctx.strokeStyle = '#060911';
            ctx.lineWidth = 3;
            ctx.stroke();

            startAngle = endAngle;
        }

        // Center Text
        ctx.fillStyle = '#ffffff';
        ctx.font = '800 20px "Space Grotesk", sans-serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('100%', centerX, centerY - 6);

        ctx.fillStyle = '#94a3b8';
        ctx.font = '600 11px Inter, sans-serif';
        ctx.fillText('Order Share', centerX, centerY + 14);
    };

    // Helper: Rounded Rectangle
    function roundRect(ctx, x, y, width, height, radius, fill, stroke) {
        if (typeof stroke === 'undefined') stroke = false;
        if (typeof radius === 'undefined') radius = 5;
        if (typeof radius === 'number') {
            radius = { tl: radius, tr: radius, br: 0, bl: 0 };
        }
        ctx.beginPath();
        ctx.moveTo(x + radius.tl, y);
        ctx.lineTo(x + width - radius.tr, y);
        ctx.quadraticCurveTo(x + width, y, x + width, y + radius.tr);
        ctx.lineTo(x + width, y + height - radius.br);
        ctx.quadraticCurveTo(x + width, y + height, x + width - radius.br, y + height);
        ctx.lineTo(x + radius.bl, y + height);
        ctx.quadraticCurveTo(x, y + height, x, y + height - radius.bl);
        ctx.lineTo(x, y + radius.tl);
        ctx.quadraticCurveTo(x, y, x + radius.tl, y);
        ctx.closePath();
        if (fill) ctx.fill();
        if (stroke) ctx.stroke();
    }

    // Helper: Draw Legend
    function drawLegend(ctx, x, y, items) {
        let curX = x;
        ctx.font = '500 11.5px Inter, sans-serif';
        ctx.textBaseline = 'middle';

        items.forEach(item => {
            if (item.isLine) {
                ctx.strokeStyle = item.color;
                ctx.lineWidth = 3;
                ctx.beginPath();
                ctx.moveTo(curX, y);
                ctx.lineTo(curX + 16, y);
                ctx.stroke();
                ctx.beginPath();
                ctx.arc(curX + 8, y, 3, 0, Math.PI * 2);
                ctx.fillStyle = item.color;
                ctx.fill();
            } else {
                ctx.fillStyle = item.color;
                roundRect(ctx, curX, y - 5, 12, 10, 2, true);
            }
            curX += 18;
            ctx.fillStyle = '#cbd5e1';
            ctx.textAlign = 'left';
            ctx.fillText(item.label, curX, y);
            curX += ctx.measureText(item.label).width + 16;
        });
    }

    // Auto-render with dynamic config caching
    let cachedSalesConfig = null;
    let cachedShareConfig = null;

    window.initAdminCharts = function(salesConfig, shareConfig) {
        if (salesConfig) cachedSalesConfig = salesConfig;
        if (shareConfig) cachedShareConfig = shareConfig;

        window.renderSalesProfitGraph('salesProfitChart', cachedSalesConfig);
        window.renderProductShareGraph('productShareChart', cachedShareConfig);
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() { window.initAdminCharts(); });
    } else {
        window.initAdminCharts();
    }

    window.addEventListener('resize', function() {
        if (window.renderSalesProfitGraph && window.renderProductShareGraph) {
            window.renderSalesProfitGraph('salesProfitChart', cachedSalesConfig);
            window.renderProductShareGraph('productShareChart', cachedShareConfig);
        }
    });
})();
