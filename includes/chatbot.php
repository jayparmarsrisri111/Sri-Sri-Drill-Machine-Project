<?php
/**
 * Varsha AI SmartDrill™ Industrial Assistant & Interactive Chatbot
 * Shree Varsha Engineering Works - Varsha Brand
 */
?>
<!-- Floating Chatbot Widget Button -->
<div id="varshaChatbotLauncher" class="chatbot-launcher" title="Chat with Varsha AI Machinery Engineer">
    <div class="launcher-pulse"></div>
    <div class="launcher-icon">
        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
        </svg>
    </div>
    <span class="launcher-badge" id="chatbotUnreadBadge">1</span>
</div>

<!-- Chatbot Glassmorphic Window Container -->
<div id="varshaChatbotWindow" class="chatbot-window">
    <!-- Header -->
    <div class="chatbot-header">
        <div class="bot-profile">
            <div class="bot-avatar-wrap">
                <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha AI" class="bot-avatar-img">
                <span class="online-indicator"></span>
            </div>
            <div>
                <div class="bot-name">Varsha AI Engineer</div>
                <div class="bot-status">
                    <span class="pulse-dot"></span> Online • Factory AI Assistant
                </div>
            </div>
        </div>
        <div class="chatbot-header-actions">
            <button type="button" id="btnChatSoundToggle" class="bot-tool-btn" title="Toggle Sound">🔊</button>
            <button type="button" id="btnChatClear" class="bot-tool-btn" title="Clear Chat">🗑️</button>
            <button type="button" id="btnChatClose" class="bot-tool-btn close-btn" title="Minimize Chat">&times;</button>
        </div>
    </div>

    <!-- Message Body -->
    <div id="chatbotMessages" class="chatbot-messages">
        <!-- Welcome Message -->
        <div class="chat-msg bot-msg">
            <div class="msg-avatar">
                <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="AI">
            </div>
            <div class="msg-bubble">
                <p><strong>નમસ્તે! Welcome to Shree Varsha Engineering Works (Varsha Brand)!</strong></p>
                <p>I am your 24/7 AI Industrial Machinery Advisor. How can I assist you with our heavy-duty Drill Machines today?</p>
                <div class="msg-time"><?= date('h:i A') ?></div>
            </div>
        </div>
    </div>

    <!-- Quick Suggestion Action Chips -->
    <div class="chatbot-chips-bar" id="chatbotChips">
        <button type="button" class="chip-btn" data-query="40mm Radial Drill Price">💰 40mm Radial Price</button>
        <button type="button" class="chip-btn" data-query="How to book drill machine online?">💳 How to Book Online?</button>
        <button type="button" class="chip-btn" data-query="Where is my order live tracking?">🚚 Track My Order</button>
        <button type="button" class="chip-btn" data-query="I want to buy machine spare parts">⚙️ Buy Spare Parts</button>
        <button type="button" class="chip-btn" data-query="Download 2026 Catalog PDF">📖 Download Catalog (PDF)</button>
        <button type="button" class="chip-btn" data-query="What is the Token Advance amount?">🪙 Advance Token (₹25k)</button>
        <button type="button" class="chip-btn" data-query="Connect with Jay Parmar on WhatsApp">💬 WhatsApp Engineer</button>
    </div>

    <!-- Input Bar -->
    <form id="chatbotInputForm" class="chatbot-input-area">
        <input type="text" id="chatbotInput" placeholder="Type your question in Gujarati, Hindi or English..." autocomplete="off">
        <button type="submit" id="btnChatSend" title="Send Message">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
            </svg>
        </button>
    </form>
</div>

<style>
/* Chatbot Floating Launcher (Bottom-Left to avoid overlap with WhatsApp/Call) */
.chatbot-launcher {
    position: fixed;
    bottom: 28px;
    left: 28px;
    width: 62px;
    height: 62px;
    background: linear-gradient(135deg, #00e5ff 0%, #2563eb 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #060911;
    cursor: pointer;
    z-index: 9999;
    box-shadow: 0 10px 30px rgba(0, 229, 255, 0.45), 0 0 20px rgba(0, 229, 255, 0.3);
    transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.chatbot-launcher:hover {
    transform: scale(1.08) translateY(-3px);
}
.launcher-pulse {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: 2px solid #00e5ff;
    animation: launcherPulseAnim 2.2s infinite;
    pointer-events: none;
}
@keyframes launcherPulseAnim {
    0% { transform: scale(1); opacity: 0.9; }
    50% { transform: scale(1.35); opacity: 0.2; }
    100% { transform: scale(1.6); opacity: 0; }
}
.launcher-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #ef4444;
    color: #fff;
    font-size: 11px;
    font-weight: 800;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #060911;
}

/* Chatbot Glassmorphic Window */
.chatbot-window {
    position: fixed;
    bottom: 100px;
    left: 28px;
    width: 380px;
    max-width: calc(100vw - 32px);
    height: 580px;
    max-height: calc(100vh - 120px);
    background: rgba(6, 10, 20, 0.96);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1.5px solid rgba(0, 229, 255, 0.35);
    border-radius: 18px;
    display: none;
    flex-direction: column;
    overflow: hidden;
    z-index: 10000;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.8), 0 0 35px rgba(0, 229, 255, 0.15);
    animation: chatbotSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.chatbot-window.active {
    display: flex;
}
@keyframes chatbotSlideUp {
    from { opacity: 0; transform: translateY(20px) scale(0.96); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

/* Header */
.chatbot-header {
    background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(6, 10, 20, 0.98) 100%);
    border-bottom: 1px solid rgba(0, 229, 255, 0.25);
    padding: 14px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.bot-profile {
    display: flex;
    align-items: center;
    gap: 12px;
}
.bot-avatar-wrap {
    position: relative;
}
.bot-avatar-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 1.5px solid #00e5ff;
    object-fit: contain;
    background: #0f172a;
}
.online-indicator {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 10px;
    height: 10px;
    background: #10b981;
    border-radius: 50%;
    border: 2px solid #060911;
}
.bot-name {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 15px;
    font-weight: 800;
    color: #fff;
    letter-spacing: 0.5px;
}
.bot-status {
    font-size: 11px;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 2px;
}
.pulse-dot {
    width: 6px;
    height: 6px;
    background: #10b981;
    border-radius: 50%;
    box-shadow: 0 0 6px #10b981;
}
.chatbot-header-actions {
    display: flex;
    align-items: center;
    gap: 6px;
}
.bot-tool-btn {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #cbd5e1;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s;
}
.bot-tool-btn:hover {
    background: rgba(0, 229, 255, 0.2);
    color: #00e5ff;
    border-color: #00e5ff;
}
.bot-tool-btn.close-btn {
    font-size: 18px;
    line-height: 1;
}

/* Chat Messages */
.chatbot-messages {
    flex: 1;
    padding: 16px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 14px;
    scroll-behavior: smooth;
}
.chatbot-messages::-webkit-scrollbar {
    width: 5px;
}
.chatbot-messages::-webkit-scrollbar-thumb {
    background: rgba(0, 229, 255, 0.3);
    border-radius: 4px;
}

/* Message Styles */
.chat-msg {
    display: flex;
    gap: 10px;
    max-width: 90%;
}
.chat-msg.bot-msg {
    align-self: flex-start;
}
.chat-msg.user-msg {
    align-self: flex-end;
    flex-direction: row-reverse;
}
.msg-avatar img {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 1px solid #00e5ff;
    object-fit: contain;
    background: #0f172a;
}
.msg-bubble {
    background: rgba(15, 23, 42, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    border-top-left-radius: 2px;
    padding: 11px 14px;
    font-size: 13px;
    line-height: 1.5;
    color: #e2e8f0;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}
.user-msg .msg-bubble {
    background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%);
    border-color: #38bdf8;
    color: #fff;
    border-top-left-radius: 12px;
    border-top-right-radius: 2px;
}
.msg-bubble p {
    margin: 0 0 6px 0;
}
.msg-bubble p:last-child {
    margin-bottom: 0;
}
.msg-time {
    font-size: 9.5px;
    color: #94a3b8;
    margin-top: 5px;
    text-align: right;
}
.user-msg .msg-time {
    color: rgba(255,255,255,0.7);
}

/* Rich Card Inside Chat */
.chat-rich-card {
    background: #060911;
    border: 1px solid #00e5ff;
    border-radius: 8px;
    padding: 10px;
    margin-top: 8px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.chat-rich-card img {
    width: 100%;
    height: 110px;
    object-fit: contain;
    background: #090d16;
    border-radius: 6px;
    padding: 4px;
}
.chat-card-title {
    font-weight: 800;
    font-size: 13px;
    color: #fff;
}
.chat-card-spec {
    font-size: 11px;
    color: #38bdf8;
}
.chat-card-price {
    font-size: 13px;
    font-weight: 800;
    color: #34d399;
}
.chat-card-actions {
    display: flex;
    gap: 6px;
}
.chat-card-actions .btn {
    padding: 5px 10px;
    font-size: 11px;
    flex: 1;
    text-align: center;
}

/* Suggestion Chips */
.chatbot-chips-bar {
    padding: 8px 14px;
    display: flex;
    gap: 6px;
    overflow-x: auto;
    white-space: nowrap;
    background: rgba(11, 17, 32, 0.9);
    border-top: 1px solid rgba(255, 255, 255, 0.06);
}
.chatbot-chips-bar::-webkit-scrollbar {
    display: none;
}
.chip-btn {
    background: rgba(0, 229, 255, 0.08);
    border: 1px solid rgba(0, 229, 255, 0.35);
    color: #38bdf8;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 9999px;
    cursor: pointer;
    transition: all 0.2s;
    flex-shrink: 0;
}
.chip-btn:hover {
    background: rgba(0, 229, 255, 0.2);
    border-color: #00e5ff;
    color: #fff;
}

/* Input Bar */
.chatbot-input-area {
    padding: 12px 14px;
    background: #090d16;
    border-top: 1px solid rgba(0, 229, 255, 0.25);
    display: flex;
    align-items: center;
    gap: 8px;
}
.chatbot-input-area input {
    flex: 1;
    background: rgba(15, 23, 42, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 9999px;
    padding: 10px 16px;
    color: #fff;
    font-size: 13px;
    outline: none;
    transition: border 0.2s;
}
.chatbot-input-area input:focus {
    border-color: #00e5ff;
    box-shadow: 0 0 10px rgba(0, 229, 255, 0.2);
}
.chatbot-input-area button {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: linear-gradient(135deg, #00e5ff 0%, #2563eb 100%);
    border: none;
    color: #060911;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s;
    flex-shrink: 0;
}
.chatbot-input-area button:hover {
    transform: scale(1.06);
}

@media (max-width: 480px) {
    .chatbot-launcher {
        bottom: 18px;
        left: 18px;
        width: 52px;
        height: 52px;
    }
    .chatbot-window {
        bottom: 80px;
        left: 10px;
        right: 10px;
        width: auto;
        height: calc(100vh - 100px);
        max-height: 560px;
        border-radius: 14px;
    }
}
</style>

<!-- Chatbot Intelligent Frontend Engine -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const launcher = document.getElementById('varshaChatbotLauncher');
    const chatWindow = document.getElementById('varshaChatbotWindow');
    const closeBtn = document.getElementById('btnChatClose');
    const clearBtn = document.getElementById('btnChatClear');
    const soundBtn = document.getElementById('btnChatSoundToggle');
    const inputForm = document.getElementById('chatbotInputForm');
    const chatInput = document.getElementById('chatbotInput');
    const messagesBox = document.getElementById('chatbotMessages');
    const unreadBadge = document.getElementById('chatbotUnreadBadge');
    const chips = document.querySelectorAll('.chip-btn');

    let soundOn = true;
    let audioCtx = null;

    function playBeep(freq = 600, duration = 0.08) {
        if (!soundOn) return;
        try {
            if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            if (audioCtx.state === 'suspended') audioCtx.resume();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
            gain.gain.setValueAtTime(0.04, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + duration);
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start();
            osc.stop(audioCtx.currentTime + duration);
        } catch(e) {}
    }

    // Toggle Chat Window
    launcher.addEventListener('click', function () {
        chatWindow.classList.toggle('active');
        if (chatWindow.classList.contains('active')) {
            if (unreadBadge) unreadBadge.style.display = 'none';
            chatInput.focus();
            playBeep(800, 0.06);
        }
    });

    closeBtn.addEventListener('click', function () {
        chatWindow.classList.remove('active');
    });

    clearBtn.addEventListener('click', function () {
        messagesBox.innerHTML = `
            <div class="chat-msg bot-msg">
                <div class="msg-avatar">
                    <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="AI">
                </div>
                <div class="msg-bubble">
                    <p>Conversation history cleared. How can I help you regarding Varsha Brand Drill Machines?</p>
                    <div class="msg-time">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
                </div>
            </div>
        `;
    });

    soundBtn.addEventListener('click', function () {
        soundOn = !soundOn;
        soundBtn.textContent = soundOn ? '🔊' : '🔇';
    });

    function appendMessage(text, isUser = false, richCardHtml = '') {
        const timeStr = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        const msgDiv = document.createElement('div');
        msgDiv.className = 'chat-msg ' + (isUser ? 'user-msg' : 'bot-msg');

        if (isUser) {
            msgDiv.innerHTML = `
                <div class="msg-bubble">
                    <p>${text}</p>
                    <div class="msg-time">${timeStr}</div>
                </div>
            `;
        } else {
            msgDiv.innerHTML = `
                <div class="msg-avatar">
                    <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="AI">
                </div>
                <div class="msg-bubble">
                    ${text}
                    ${richCardHtml}
                    <div class="msg-time">${timeStr}</div>
                </div>
            `;
        }

        messagesBox.appendChild(msgDiv);
        messagesBox.scrollTop = messagesBox.scrollHeight;
        if (!isUser) playBeep(520, 0.1);
    }

    // Natural Language Engineering Knowledgebase
    function getBotResponse(rawText) {
        const q = rawText.toLowerCase().trim();

        // 1. 40mm Radial Drill Price & Specs
        if (q.includes('40mm') || q.includes('rd-40') || q.includes('rd 40') || q.includes('all geared radial')) {
            const rich = `
                <div class="chat-rich-card">
                    <img src="<?= assetUrl('assets/images/machinery/radial-drill-real.jpg') ?>" alt="RD-40 Real Photo">
                    <div class="chat-card-title">SVEW-RD-40 All-Geared Radial Drill</div>
                    <div class="chat-card-spec">Capacity: 40mm Steel / 50mm C.I. • 8 Speeds (50-1800 RPM)</div>
                    <div class="chat-card-price">Ex-Factory: ₹ 2,85,000 + 18% GST (Token: ₹25,000)</div>
                    <div class="chat-card-actions">
                        <a href="<?= BASE_URL ?>/checkout.php?product_id=1" class="btn btn-primary" style="background:#10b981; font-size:11px;">💳 Book Now</a>
                        <a href="<?= BASE_URL ?>/machine-detail.php?id=1" class="btn btn-outline" style="font-size:11px;">Full Specs</a>
                    </div>
                </div>
            `;
            return {
                text: `<p><strong>SVEW-RD-40 All-Geared Radial Drill</strong> is our most popular industrial machine! Hardened & ground alloy steel gears, power elevation column, 1650 kg heavy vibration-free Grade-25 casting. Factory price: <strong>₹ 2,85,000</strong> (+18% GST). Token advance: ₹ 25,000.</p>`,
                rich: rich
            };
        }

        // 1.1 Spare Parts & Tooling Inquiries
        if (q.includes('part') || q.includes('spares') || q.includes('spindle') || q.includes('gear') || q.includes('chuck') || q.includes('pump') || q.includes('table') || q.includes('accessories')) {
            const rich = `
                <div class="chat-rich-card">
                    <div class="chat-card-title">⚙️ Genuine Varsha Factory Spare Parts</div>
                    <div class="chat-card-spec">• MT-4 Alloy Spindle Sleeve: ₹ 14,500<br>• 8-Speed Headstock Gear Set: ₹ 22,000<br>• T-Slot Box Table (500x600): ₹ 18,500<br>• 1-16mm Keyless Chuck MT-4: ₹ 3,800<br>• Coolant Pump & Hose Kit: ₹ 4,200</div>
                    <div class="chat-card-actions" style="margin-top:6px;">
                        <a href="<?= BASE_URL ?>/catalog.php" class="btn btn-outline" style="font-size:11px; flex:1;">Browse All Parts</a>
                        <a href="<?= BASE_URL ?>/checkout.php?product_id=101" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981;">Order Parts Online</a>
                    </div>
                </div>
            `;
            return {
                text: `<p>હા, અમે તમામ <strong>Varsha Brand Radial & Pillar Drill Machines</strong> ના ૧૦૦% ઓરિજિનલ સ્પેરપાર્ટ્સ (Spindle, Gears, Worm Wheel, Coolant Pump, Chucks) સ્ટોક રાખીએ છીએ. તમે ઓનલાઇન પણ ઓર્ડર કરી શકો છો.</p>`,
                rich: rich
            };
        }

        // 1.2 Live Order & Delivery Tracking Inquiries
        if (q.includes('track') || q.includes('tracking') || q.includes('order status') || q.includes('where is my order') || q.includes('delivery status') || q.includes('amazon')) {
            return {
                text: `<p><strong>🚚 Amazon-Style Live Order & Logistics Tracking:</strong></p>
                       <p>તમે તમારા ઓર્ડર નંબર (e.g. SVEW-ORD-...) અથવા મોબાઈલ નંબર નાખીને ફેક્ટરી અસેમ્બલી, QC ટેસ્ટિંગ, વુડન પેકિંગ અને VRL Logistics નું લાઈવ ટ્રેકિંગ જોઈ શકો છો!</p>`,
                rich: `<div style="margin-top:8px;"><a href="<?= BASE_URL ?>/track-order.php" class="btn btn-primary" style="background:linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color:#060911; font-weight:800; font-size:12px; display:block; text-align:center;">🚚 Open Live Order Tracking</a></div>`
            };
        }

        // 1.3 Gujarati Language Inquiries
        if (q.includes('કેમ') || q.includes('ભાવ') || q.includes('કિંમત') || q.includes('મશીન') || q.includes('ઓર્ડર') || q.includes('નમસ્તે') || q.includes('ડ્રિલ') || q.includes('ક્યાં') || q.includes('સરનામું')) {
            return {
                text: `<p><strong>નમસ્તે! શ્રી વર્ષા એન્જિનિયરિંગ વર્ક્સ (ભાવનગર) માં આપનું સ્વાગત છે.</strong></p>
                       <p>અમારા ઓલ-ગીયર્ડ રેડિયલ અને પિલર ડ્રિલ મશીનોના વાસ્તવિક ફેક્ટરી ભાવો:<br>
                       • <strong>40mm રેડિયલ ડ્રિલ:</strong> ₹ 2,85,000 + 18% GST (ટોકન એડવાન્સ: ₹25,000)<br>
                       • <strong>50mm હેવી ડ્યુટી:</strong> ₹ 3,95,000 + 18% GST<br>
                       • <strong>65mm એક્સ્ટ્રા હેવી:</strong> ₹ 5,75,000 + 18% GST<br>
                       • <strong>25mm પિલર ડ્રિલ:</strong> ₹ 85,000 + 18% GST</p>
                       <p><strong>ફેક્ટરી સરનામું:</strong> Shed No. L/786, GIDC, Nr. Hadanagar, Bhavnagar - 36400003, Gujarat.<br>
                       <strong>GSTIN:</strong> 24AJHPP0087E1ZD<br>
                       તમે ઓનલાઇન Razorpay થી ટોકન બુકિંગ કરી શકો છો અને તરત જ અધિકૃત ટેક્સ ઇન્વોઇસ &amp; E-Way Bill મેળવી શકો છો.</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:8px;">
                            <a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary" style="background:#10b981; font-size:11px; flex:1;">💳 ઓનલાઇન બુક કરો</a>
                            <a href="<?= BASE_URL ?>/catalog.php" class="btn btn-outline" style="font-size:11px; flex:1;">📖 કેટલોગ જુઓ</a>
                       </div>`
            };
        }

        // 2. 50mm Radial Drill
        if (q.includes('50mm') || q.includes('rd-50') || q.includes('rd 50')) {
            const rich = `
                <div class="chat-rich-card">
                    <img src="<?= assetUrl('assets/images/machinery/radial-drill-50.svg') ?>" alt="RD-50">
                    <div class="chat-card-title">SVEW-RD-50 Heavy Duty Radial Drill</div>
                    <div class="chat-card-spec">Capacity: 50mm Steel / 60mm C.I. • 12 Speeds</div>
                    <div class="chat-card-price">Price: ₹ 3,95,000 + 18% GST</div>
                    <div class="chat-card-actions">
                        <a href="<?= BASE_URL ?>/checkout.php?product_id=2" class="btn btn-primary" style="background:#10b981; font-size:11px;">💳 Book Now</a>
                        <a href="<?= BASE_URL ?>/machine-detail.php?id=2" class="btn btn-outline" style="font-size:11px;">Specs</a>
                    </div>
                </div>
            `;
            return {
                text: `<p><strong>SVEW-RD-50</strong> delivers immense torque for heavy steel fabrication, valve manufacturing, and railway jobs. Manufactured at our Bhavnagar plant.</p>`,
                rich: rich
            };
        }

        // 3. 65mm Radial Drill
        if (q.includes('65mm') || q.includes('rd-65') || q.includes('extra heavy')) {
            return {
                text: `<p><strong>SVEW-RD-65 Extra Heavy Radial Drill:</strong></p>
                       <p>• Drilling Capacity: 65 mm Steel / 75 mm Cast Iron<br>
                          • MT-5 Spindle Taper with 5 HP Main Motor<br>
                          • Machine Weight: Approx 3,600 kg<br>
                          • Price: ₹ 5,75,000 (+18% GST)<br>
                          • Factory Stock: Limited batch ready at Bhavnagar Works.</p>`,
                rich: `<div style="margin-top:6px;"><a href="<?= BASE_URL ?>/checkout.php?product_id=3" class="btn btn-primary" style="font-size:11px; padding:5px 12px; background:#10b981;">💳 Book SVEW-RD-65</a></div>`
            };
        }

        // 4. Pillar & Bench Drill Machines
        if (q.includes('pillar') || q.includes('vertical') || q.includes('column') || q.includes('25mm') || q.includes('32mm') || q.includes('bench')) {
            return {
                text: `<p>We manufacture 4 Precision Upright & Bench models at Bhavnagar:</p>
                       <p>1. <strong>SVEW-BD-20 (20mm Bench):</strong> ₹ 48,000<br>
                          2. <strong>SVEW-PD-25 (25mm Pillar):</strong> ₹ 85,000<br>
                          3. <strong>SVEW-PD-32 (32mm Geared Column):</strong> ₹ 1,35,000<br>
                          4. <strong>SVEW-TP-25 (Drill-Tap Combo):</strong> ₹ 1,25,000</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:6px;">
                            <a href="<?= BASE_URL ?>/machines.php?category=pillar-drill-machines" class="btn btn-outline" style="font-size:11px; flex:1;">View Pillar Drills</a>
                            <a href="<?= BASE_URL ?>/catalog.php" class="btn btn-primary" style="font-size:11px; flex:1;">Download Catalog</a>
                       </div>`
            };
        }

        // 5. Booking & Advance Token
        if (q.includes('book') || q.includes('advance') || q.includes('token') || q.includes('payment') || q.includes('pay') || q.includes('bhav') || q.includes('price')) {
            return {
                text: `<p><strong>Online Machine Booking is 100% Secure via Razorpay:</strong></p>
                       <p>• <strong>Token Advance:</strong> Just ₹ 25,000 to lock manufacturing serial number.<br>
                          • Remaining balance payable upon dispatch / inspection.<br>
                          • Official GST Tax Invoice with authorized signature of Jay Parmar &amp; Varsha Brand seal is generated instantly!</p>`,
                rich: `<div style="margin-top:8px;"><a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary" style="background:#10b981; font-size:12px; display:block; text-align:center;">💳 Go to Online Booking & Pay</a></div>`
            };
        }

        // 6. Catalog Download
        if (q.includes('catalog') || q.includes('pdf') || q.includes('brochure') || q.includes('download')) {
            return {
                text: `<p>You can view and print our <strong>Official 2026 Technical Catalog & Master Comparison Matrix (PDF)</strong> right now!</p>`,
                rich: `<div style="margin-top:8px;"><a href="<?= BASE_URL ?>/catalog.php" class="btn btn-primary" style="font-size:12px; display:block; text-align:center;">📖 Open Official 2026 Catalog (PDF)</a></div>`
            };
        }

        // 6.1 360° View & 12D Simulator
        if (q.includes('360') || q.includes('12d') || q.includes('simulator') || q.includes('turntable') || q.includes('3d') || q.includes('rotate')) {
            return {
                text: `<p><strong>🔄 360° Interactive 12D Industrial Turntable:</strong></p>
                       <p>Experience our drill machines in 360° rotatable view with 30° precision stepping, vibration analysis, and full inspection tools!</p>`,
                rich: `<div style="margin-top:8px;"><a href="<?= BASE_URL ?>/simulator-6d.php" class="btn btn-primary" style="background:linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color:#060911; font-weight:800; font-size:12px; display:block; text-align:center;">🔄 Launch 360° 12D Viewer</a></div>`
            };
        }

        // 6.2 GST, E-Way Bill & Tax Invoice
        if (q.includes('gst') || q.includes('gstin') || q.includes('tax') || q.includes('eway') || q.includes('e-way') || q.includes('hsn') || q.includes('invoice') || q.includes('bill')) {
            return {
                text: `<p><strong>Official Tax &amp; GST Details:</strong></p>
                       <p>• <strong>Company GSTIN:</strong> <?= COMPANY_GSTIN ?><br>
                          • <strong>HSN Code:</strong> 8459 (Machine-tools for drilling)<br>
                          • <strong>GST Tax Rate:</strong> 18% (9% CGST + 9% SGST or 18% IGST)<br>
                          • <strong>E-Way Bill:</strong> Official Form GST EWB-01 is generated for every dispatch under Rule 138.</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:8px;">
                            <a href="<?= BASE_URL ?>/invoice.php" class="btn btn-outline" style="font-size:11px; flex:1;">📄 View Sample Tax Bill</a>
                            <a href="<?= BASE_URL ?>/track-order.php" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981;">🚚 Track Order &amp; E-Way</a>
                       </div>`
            };
        }

        // 7. Delivery / Transport / Shipping / Location
        if (q.includes('delivery') || q.includes('transport') || q.includes('dispatch') || q.includes('freight') || q.includes('address') || q.includes('location') || q.includes('bhavnagar') || q.includes('hadanagar') || q.includes('where') || q.includes('factory')) {
            return {
                text: `<p><strong>Factory Works &amp; Transportation Details:</strong></p>
                       <p>📍 <strong>Factory Address:</strong> <?= COMPANY_ADDRESS ?><br>
                          🚚 <strong>Logistics Partners:</strong> VRL Logistics Heavy Freight, TCI Freight, ARC.<br>
                          ⏱️ <strong>Transit Time:</strong> Gujarat (24-48 hrs), Maharashtra (48-72 hrs), All-India (3-5 days).<br>
                          📦 Industrial wooden crating with anti-rust shock-dampers included.</p>`,
                rich: `<div style="margin-top:8px;"><a href="<?= BASE_URL ?>/contact.php" class="btn btn-outline" style="font-size:11px; display:block; text-align:center;">📍 Open Factory Map &amp; Directions</a></div>`
            };
        }

        // 8. Warranty & Quality
        if (q.includes('warranty') || q.includes('guarantee') || q.includes('testing') || q.includes('iso') || q.includes('quality')) {
            return {
                text: `<p><strong>Quality Assurance & Warranty:</strong></p>
                       <p>• <strong>12 Months Complete Manufacturer Warranty</strong> on casting and gears.<br>
                          • ISO 9001:2015 certified manufacturing facility in Bhavnagar.<br>
                          • 100% pre-dispatch run test & dial gauge accuracy alignment at Bhavnagar Works.</p>`,
                rich: ''
            };
        }

        // 9. Contact / WhatsApp / Phone
        if (q.includes('contact') || q.includes('phone') || q.includes('mobile') || q.includes('number') || q.includes('whatsapp') || q.includes('call') || q.includes('jitubhai') || q.includes('jitendra') || q.includes('jay')) {
            return {
                text: `<p><strong>Factory Direct Contact:</strong></p>
                       <p>👤 <strong>Jitendrabhai Parmar (Technical Director):</strong> +91 9824913555<br>
                          👤 <strong>Jay Parmar (Production Engineer):</strong> +91 9714611331<br>
                          ✉️ <strong>Official Email:</strong> <?= COMPANY_EMAIL ?><br>
                          🏢 <strong>Works:</strong> <?= COMPANY_ADDRESS ?></p>`,
                rich: `<div style="margin-top:8px;"><a href="<?= getWhatsAppLink(COMPANY_WHATSAPP, 'Hello Shree Varsha Engineering Works, I am chatting on your website and would like to speak directly with an engineer.') ?>" target="_blank" class="btn btn-whatsapp" style="font-size:12px; display:block; text-align:center;">💬 Chat on WhatsApp</a></div>`
            };
        }

        // Default Smart Response
        return {
            text: `<p>Thank you for asking! I can help you with:</p>
                   <p>• Drill machine selection based on hole size (20mm to 65mm)<br>
                      • Ex-factory price quotations and advance booking<br>
                      • 2026 Technical Catalog PDF download<br>
                      • Dispatch and freight timeline to your workshop</p>`,
            rich: `<div style="display:flex; gap:6px; margin-top:8px;">
                        <a href="<?= BASE_URL ?>/catalog.php" class="btn btn-outline" style="font-size:11px; flex:1;">📖 View Catalog</a>
                        <a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary" style="background:#10b981; font-size:11px; flex:1;">💳 Book Online</a>
                   </div>`
        };
    }

    // Handle Form Submit
    inputForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const query = chatInput.value.trim();
        if (!query) return;

        appendMessage(query, true);
        chatInput.value = '';

        // Show typing indicator
        const typingDiv = document.createElement('div');
        typingDiv.className = 'chat-msg bot-msg';
        typingDiv.id = 'botTyping';
        typingDiv.innerHTML = `
            <div class="msg-avatar"><img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="AI"></div>
            <div class="msg-bubble" style="color:#00e5ff; font-style:italic; font-size:12px;">Varsha AI is analyzing engineering specs...</div>
        `;
        messagesBox.appendChild(typingDiv);
        messagesBox.scrollTop = messagesBox.scrollHeight;

        setTimeout(function () {
            const typingElem = document.getElementById('botTyping');
            if (typingElem) typingElem.remove();

            const resp = getBotResponse(query);
            appendMessage(resp.text, false, resp.rich);
        }, 500);
    });

    // Chip Click Handler
    chips.forEach(chip => {
        chip.addEventListener('click', function () {
            const q = this.getAttribute('data-query');
            chatInput.value = q;
            inputForm.dispatchEvent(new Event('submit'));
        });
    });
});
</script>
