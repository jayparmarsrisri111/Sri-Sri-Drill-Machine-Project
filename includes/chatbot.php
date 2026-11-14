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
        <img src="<?= assetUrl('assets/images/varsha-logo.png') ?>" alt="Varsha AI" class="launcher-logo-img">
        <span class="launcher-ai-chip">AI</span>
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
                <div class="bot-name" style="display: flex; align-items: center; gap: 6px;">
                    <span>Varsha AI Engineer</span>
                    <span style="font-size: 9.5px; background: rgba(0, 229, 255, 0.2); border: 1px solid #00e5ff; color: #00e5ff; padding: 1px 6px; border-radius: 9999px; font-weight: 800;">OFFICIAL</span>
                </div>
                <div class="bot-status">
                    <span class="pulse-dot"></span> Online • 24/7 Factory Assistant
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
.launcher-icon {
    position: relative;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.launcher-logo-img {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: 2px solid #ffffff;
    object-fit: contain;
    background: #060911;
    box-shadow: 0 4px 12px rgba(0,0,0,0.5);
}
.launcher-ai-chip {
    position: absolute;
    bottom: -3px;
    right: -3px;
    background: #00e5ff;
    color: #060911;
    font-size: 9px;
    font-weight: 900;
    padding: 1px 4px;
    border-radius: 4px;
    letter-spacing: 0.5px;
    border: 1.5px solid #060911;
    line-height: 1.1;
    box-shadow: 0 2px 6px rgba(0, 229, 255, 0.6);
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

    // Natural Language Engineering Knowledgebase (Precision Multi-Lingual AI Assistant)
    function getBotResponse(rawText) {
        const q = rawText.toLowerCase().trim();
        const isGujarati = /[\u0A80-\u0AFF]/.test(rawText) || q.includes('chhe') || q.includes('nathi') || q.includes('ketla') || q.includes('kem') || q.includes('bhav') || q.includes('saru');
        const isHindi = /[\u0900-\u097F]/.test(rawText) || q.includes('kya') || q.includes('kaise') || q.includes('kitna') || q.includes('chahiye');

        // 0. Polite Greetings & Welcome
        if (/\b(hi|hello|hey|namaste|kem cho|kya hal|pranam)\b/i.test(q) || q.includes('કેમ છો') || q.includes('નમસ્તે') || q.includes('જય શ્રી કૃષ્ણ') || q.includes('નમસ્કાર')) {
            if (isGujarati) {
                return {
                    text: `<p><strong>નમસ્તે! શ્રી વર્ષા એન્જિનિયરિંગ વર્ક્સ (ભાવનગર) માં તમારું સ્વાગત છે.</strong></p>
                           <p>હું વર્ષા બ્રાન્ડ ઔદ્યોગિક ડ્રિલ મશીન સહાયક છું. આજે હું તમને કઈ રીતે મદદ કરી શકું?<br>
                           • <strong>મશીન મોડલ અને ભાવ</strong> જાણવા માટે <em>"40mm Radial Price"</em> લખો.<br>
                           • <strong>ઓનલાઇન બુકિંગ</strong> માટે <em>"Book Online"</em> લખો.<br>
                           • <strong>ઓર્ડર ટ્રેકિંગ / બિલ</strong> માટે <em>"Track Order"</em> લખો.<br>
                           • <strong>સ્પેર પાર્ટ્સ</strong> માટે <em>"Spare Parts"</em> લખો.</p>`,
                    rich: `<div style="display:flex; gap:6px; margin-top:6px;">
                                <a href="<?= BASE_URL ?>/machines.php" class="btn btn-outline" style="font-size:11px; flex:1;">📖 મશીન કેટલોગ</a>
                                <a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981;">💳 ઓનલાઇન બુકિંગ</a>
                           </div>`
                };
            }
            return {
                text: `<p><strong>Namaste! Welcome to Shree Varsha Engineering Works (Varsha Brand).</strong></p>
                       <p>I am your 24/7 AI Machinery Assistant. How can I assist you today?<br>
                       • Ask for <strong>Machine Prices</strong> (e.g. 40mm, 50mm, 65mm Radial or Pillar)<br>
                       • Ask about <strong>Online Booking & ₹25,000 Advance Token</strong><br>
                       • Inquire about <strong>Spare Parts &amp; High-Definition 4K Zoom</strong><br>
                       • Track your <strong>Live Delivery & GST Tax Invoices</strong></p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:6px;">
                            <a href="<?= BASE_URL ?>/machines.php" class="btn btn-outline" style="font-size:11px; flex:1;">📖 Machine Catalog</a>
                            <a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981;">💳 Book Machine</a>
                       </div>`
            };
        }

        // 0.5. Company Name, Firm Details & Factory Background
        const isCompanyQuery = /\b(company|firm|factory|business|brand|compnay|comny|karkhanu|karhana)\b/i.test(q) || 
                               q.includes('company name') || q.includes('compnay name') || q.includes('comny name') ||
                               q.includes('કંપની') || q.includes('ફેક્ટરી') || q.includes('બ્રાન્ડ') || q.includes('નામ શું') || 
                               q.includes('naam') || q.includes('name') || q.includes('shree varsha') || q.includes('varsha brand');

        if (isCompanyQuery) {
            if (isGujarati) {
                return {
                    text: `<p><strong>🏭 કંપની પરિચય (Company Overview):</strong></p>
                           <p>• <strong>કંપનીનું નામ:</strong> શ્રી વર્ષા એન્જિનિયરિંગ વર્ક્સ (Shree Varsha Engineering Works)<br>
                           • <strong>બ્રાન્ડ નેમ:</strong> VARSHA BRAND (વર્ષા બ્રાન્ડ - રજિસ્ટર્ડ ટ્રેડમાર્ક)<br>
                           • <strong>ફેક્ટરી સરનામું:</strong> શેડ નં. L/786, GIDC ઔદ્યોગિક વસાહત, હાદાનગર પાસે, ભાવનગર - 364003 (ગુજરાત, ભારત).<br>
                           • <strong>અનુભવ:</strong> ૨૫+ વર્ષથી હેવી ડ્યુટી રેડિયલ અને પિલર ડ્રિલ મશીન મેન્યુફેક્ચરિંગ.<br>
                           • <strong>GSTIN:</strong> <?= COMPANY_GSTIN ?><br>
                           • <strong>સર્ટિફિકેશન:</strong> ISO 9001:2015 ગુણવત્તા પ્રમાણિત.<br>
                           • <strong>મુખ્ય વડા:</strong> શ્રી જીતેન્દ્રભાઈ પરમાર (Founder &amp; CEO, +91 98249 13555) અને શ્રી જય પરમાર (Co-CEO &amp; Production Engineer, +91 97146 11331).</p>`,
                    rich: `<div style="display:flex; gap:6px; margin-top:6px;">
                                <a href="<?= BASE_URL ?>/about.php" class="btn btn-outline" style="font-size:11px; flex:1;">🏭 કંપની વિશે વધુ વાંચો</a>
                                <a href="<?= BASE_URL ?>/machines.php" class="btn btn-primary" style="font-size:11px; flex:1; background:#0284c7;">📖 મશીન કેટલોગ જુઓ</a>
                           </div>`
                };
            }
            return {
                text: `<p><strong>🏭 Company Name &amp; Profile:</strong></p>
                       <p>• <strong>Company Name:</strong> Shree Varsha Engineering Works<br>
                       • <strong>Official Brand:</strong> VARSHA BRAND (Registered Industrial Trademark)<br>
                       • <strong>Manufacturing Plant:</strong> Shed No. L/786, GIDC Industrial Estate, Nr. Hadanagar, Bhavnagar - 364003, Gujarat, India.<br>
                       • <strong>Experience:</strong> 25+ Years of Heavy-Duty Radial &amp; Pillar Drill Machine Manufacturing.<br>
                       • <strong>GSTIN:</strong> <?= COMPANY_GSTIN ?><br>
                       • <strong>Certification:</strong> ISO 9001:2015 Precision Certified.<br>
                       • <strong>Executive Leadership:</strong> Jitendrabhai Parmar (Founder &amp; CEO, +91 98249 13555) and Jay Parmar (Co-CEO &amp; Production Engineer, +91 97146 11331).</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:6px;">
                            <a href="<?= BASE_URL ?>/about.php" class="btn btn-outline" style="font-size:11px; flex:1;">🏭 Full Company Story</a>
                            <a href="<?= BASE_URL ?>/machines.php" class="btn btn-primary" style="font-size:11px; flex:1; background:#0284c7;">📖 Browse Machines</a>
                       </div>`
            };
        }

        // 0.8. Contact Numbers, Phone, Mobile & WhatsApp
        if (q.includes('phone') || q.includes('mobile') || q.includes('call') || q.includes('contact') || q.includes('whatsapp') || q.includes('email') || q.includes('mail') || q.includes('સંપર્ક') || q.includes('ફોન') || q.includes('નંબર') || q.includes('વાત કરવી') || q.includes('help')) {
            if (isGujarati) {
                return {
                    text: `<p><strong>📞 ફેક્ટરી સીધો સંપર્ક (Direct Factory Contact):</strong></p>
                           <p>• <strong>મુખ્ય ફોન નં.:</strong> <a href="tel:<?= COMPANY_PHONE_1_CLEAN ?>" style="color:#00e5ff; font-weight:700;"><?= COMPANY_PHONE_1 ?></a> (જીતેન્દ્રભાઈ પરમાર - CEO)<br>
                           • <strong>પ્રોડક્શન &amp; એન્જિનિયરિંગ:</strong> <a href="tel:<?= COMPANY_PHONE_2_CLEAN ?>" style="color:#38bdf8; font-weight:700;"><?= COMPANY_PHONE_2 ?></a> (જય પરમાર - Co-CEO)<br>
                           • <strong>વોટ્સએપ હેલ્પલાઇન:</strong> +91 98249 13555<br>
                           • <strong>ઇમેઇલ:</strong> <?= COMPANY_EMAIL ?> / jayparmar@varshamachines.com<br>
                           • <strong>ફેક્ટરી સમય:</strong> સોમવાર થી શનિવાર (સવારે ૯:૦૦ થી સાંજે ૭:૦૦)</p>`,
                    rich: `<div style="display:flex; gap:6px; margin-top:6px;">
                                <a href="tel:<?= COMPANY_PHONE_1_CLEAN ?>" class="btn btn-primary" style="font-size:11px; flex:1; background:#059669;">📞 ડાયરેક્ટ કોલ</a>
                                <a href="<?= getWhatsAppLink(COMPANY_WHATSAPP, 'નમસ્તે શ્રી વર્ષા એન્જિનિયરિંગ વર્ક્સ, મારે મશીન વિશે વાત કરવી છે.') ?>" target="_blank" class="btn btn-whatsapp" style="font-size:11px; flex:1;">💬 વોટ્સએપ ચેટ</a>
                           </div>`
                };
            }
            return {
                text: `<p><strong>📞 Factory Direct Contact &amp; Support:</strong></p>
                       <p>• <strong>Primary Phone:</strong> <a href="tel:<?= COMPANY_PHONE_1_CLEAN ?>" style="color:#00e5ff; font-weight:700;"><?= COMPANY_PHONE_1 ?></a> (Jitendrabhai Parmar - CEO)<br>
                       • <strong>Engineering &amp; Dispatch:</strong> <a href="tel:<?= COMPANY_PHONE_2_CLEAN ?>" style="color:#38bdf8; font-weight:700;"><?= COMPANY_PHONE_2 ?></a> (Jay Parmar - Co-CEO)<br>
                       • <strong>WhatsApp:</strong> +91 98249 13555<br>
                       • <strong>Email:</strong> <?= COMPANY_EMAIL ?><br>
                       • <strong>Factory Hours:</strong> Monday - Saturday, 9:00 AM - 7:00 PM</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:6px;">
                            <a href="tel:<?= COMPANY_PHONE_1_CLEAN ?>" class="btn btn-primary" style="font-size:11px; flex:1; background:#059669;">📞 Call Factory</a>
                            <a href="<?= getWhatsAppLink(COMPANY_WHATSAPP, 'Hello Shree Varsha Engineering Works, I would like to inquire about your Drill Machines.') ?>" target="_blank" class="btn btn-whatsapp" style="font-size:11px; flex:1;">💬 WhatsApp Chat</a>
                       </div>`
            };
        }

        // 0.9. 360° View & 12D Interactive Digital Twin Simulator
        if (q.includes('360') || q.includes('12d') || q.includes('simulator') || q.includes('3d') || q.includes('degree') || q.includes('turntable') || q.includes('સ્પાર્ક') || q.includes('ડિજિટલ ટ્વિન')) {
            return {
                text: `<p><strong>🔄 12D Digital Twin &amp; 360° Interactive Simulator Lab:</strong></p>
                       <p>• <strong>Touch &amp; Drag 360°:</strong> Rotate the machine from any angle in full 3D interactive view.<br>
                       • <strong>12D Cutting Sparks:</strong> Live kinetic simulation of heavy-duty drilling with dynamic sparks.<br>
                       • <strong>Sound Drone:</strong> Acoustic simulation of gear-driven motor and spindle speeds.<br>
                       • <strong>Real Photo Switch:</strong> Toggle between 3D virtual twin and high-resolution real factory machine photo.</p>`,
                rich: `<div style="margin-top:6px;"><a href="<?= BASE_URL ?>/simulator-6d.php" class="btn btn-primary" style="font-size:11px; display:block; text-align:center; background:#0284c7;">🔄 Open 360° 12D Simulator Lab</a></div>`
            };
        }

        // 0.95. Cart & Shopping Basket
        if (q.includes('cart') || q.includes('કાર્ટ') || q.includes('basket') || q.includes('bag') || q.includes('add to cart')) {
            return {
                text: `<p><strong>🛒 Industrial Cart &amp; Direct Ordering:</strong></p>
                       <p>• You can add complete drilling machines, parts (spindle quills, alloy gear sets, box tables, coolant pumps, drill chucks) directly to your cart.<br>
                       • Real-time quantity updater, instant GST breakdown (18%), and multi-item booking checkout.</p>`,
                rich: `<div style="margin-top:6px;"><a href="<?= BASE_URL ?>/cart.php" class="btn btn-primary" style="font-size:11px; display:block; text-align:center; background:#10b981;">🛒 View My Cart</a></div>`
            };
        }

        // 0.96. Factory Admin Portal
        if (q.includes('admin') || q.includes('એડમિન') || q.includes('control center') || q.includes('admin panel')) {
            return {
                text: `<p><strong>🔒 Factory Admin Control Center:</strong></p>
                       <p>• <strong>Authorized Portal:</strong> Managed exclusively by factory administration.<br>
                       • <strong>Features:</strong> Live order monitoring, 7-stage delivery status tracking, customer shopping cart tracker, Government GST E-Way bills, inventory management, and company configuration.</p>`,
                rich: `<div style="margin-top:6px;"><a href="<?= BASE_URL ?>/admin/index.php" class="btn btn-outline" style="font-size:11px; display:block; text-align:center; border-color:#f59e0b; color:#fbbf24;">🔒 Admin Portal Login</a></div>`
            };
        }

        // 0.97. Delivered & Commissioned at Factory Milestone
        if (q.includes('commissioned') || q.includes('કમિશનિંગ') || q.includes('delivered & commissioned') || q.includes('મશીન મળી ગયું') || q.includes('ડિલિવરી મળી ગઈ')) {
            return {
                text: `<p><strong>🏁 Delivered &amp; Commissioned at Factory (૧૦૦% સફળ ડિલિવરી):</strong></p>
                       <p>• <strong>Final Milestone:</strong> This 7th stage signifies that your machinery consignment has arrived at your factory workshop, has been safely unloaded with crane assistance, precision-leveled, and production test-run has been completed successfully.<br>
                       • In our Live Tracking system, this step displays as <strong>Completed (સફળતાપૂર્વક પૂર્ણ)</strong> with a verified certificate banner.</p>`,
                rich: `<div style="margin-top:6px;"><a href="<?= BASE_URL ?>/track-order.php" class="btn btn-primary" style="font-size:11px; display:block; text-align:center; background:#059669;">🚚 View Live Order Milestones</a></div>`
            };
        }

        // 1. Owners, Leadership & Company Founders (Exact word matching to prevent false triggers)
        const isOwnerQuery = /\b(owner|founder|director|maalik|ceo)\b/i.test(q) || 
                             q.includes('માલિક') || q.includes('ડાયરેક્ટર') || q.includes('કોણ છે માલિક') || 
                             q.includes('jay parmar') || q.includes('jitubhai parmar') || q.includes('jitendra parmar') ||
                             q.includes('જીતેન્દ્રભાઈ') || q.includes('જય પરમાર');

        if (isOwnerQuery) {
            if (isGujarati) {
                return {
                    text: `<p><strong>શ્રી વર્ષા એન્જિનિયરિંગ વર્ક્સ (ભાવનગર) ની એક્ઝિક્યુટિવ લીડરશિપ:</strong></p>
                           <p>👤 <strong>શ્રી જીતેન્દ્રભાઈ પરમાર</strong> - Founder &amp; CEO (મુખ્ય કાર્યકારી અધિકારી, ૨૫+ વર્ષનો અનુભવ).<br>
                              📞 ફોન: <strong>+91 98249 13555</strong> | ✉️ varshaengineeringworks@gmail.com<br><br>
                              👤 <strong>શ્રી જય પરમાર</strong> - Co-CEO &amp; Production Engineer (અધિકૃત હસ્તાક્ષરકર્તા - Authorized Signatory).<br>
                              📞 ફોન: <strong>+91 97146 11331</strong> | ✉️ jayparmar@varshamachines.com<br><br>
                              🏭 ફેક્ટરી: શેડ L/786, GIDC, હાદાનગર પાસે, ભાવનગર - 364003 (ગુજરાત).</p>`,
                    rich: `<div style="display:flex; gap:6px; margin-top:6px;">
                                <a href="<?= BASE_URL ?>/about.php" class="btn btn-outline" style="font-size:11px; flex:1;">🏭 સંપૂર્ણ લીડરશિપ જુઓ</a>
                                <a href="<?= getWhatsAppLink('919714611331', 'નમસ્તે જયભાઈ (Co-CEO) & જીતેન્દ્રભાઈ (CEO), હું વર્ષા બ્રાન્ડ ડ્રિલ મશીન બાબતે વાત કરવા માંગુ છું.') ?>" target="_blank" class="btn btn-whatsapp" style="font-size:11px; flex:1;">💬 ડાયરેક્ટ વોટ્સએપ</a>
                           </div>`
                };
            }
            return {
                text: `<p><strong>Shree Varsha Engineering Works Executive Leadership:</strong></p>
                       <p>👤 <strong>Jitendrabhai Parmar</strong> - Founder &amp; CEO (Chief Executive Officer, 25+ Years Experience)<br>
                          📞 Phone: <strong>+91 98249 13555</strong> | ✉️ varshaengineeringworks@gmail.com<br><br>
                          👤 <strong>Jay Parmar</strong> - Co-CEO &amp; Production Engineer (Authorized Signatory)<br>
                          📞 Phone: <strong>+91 97146 11331</strong> | ✉️ jayparmar@varshamachines.com<br><br>
                          🏭 Factory: Shed L/786, GIDC, Nr. Hadanagar, Bhavnagar - 364003, Gujarat.</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:6px;">
                            <a href="<?= BASE_URL ?>/about.php" class="btn btn-outline" style="font-size:11px; flex:1;">🏭 View Full Leadership</a>
                            <a href="<?= getWhatsAppLink('919714611331', 'Hello Jay Parmar (Co-CEO) & Jitendrabhai (CEO), I would like to inquire about Varsha Brand drill machines.') ?>" target="_blank" class="btn btn-whatsapp" style="font-size:11px; flex:1;">💬 Direct WhatsApp</a>
                       </div>`
            };
        }

        // 2. Warranty, Guarantee & Quality Assurance
        if (q.includes('warranty') || q.includes('guarantee') || q.includes('વોરંટી') || q.includes('ગેરેન્ટી') || q.includes('quality') || q.includes('ટેસ્ટિંગ') || q.includes('ખરાબી') || q.includes('service')) {
            return {
                text: `<p><strong>🛡️ Official Manufacturer Warranty &amp; Quality Guarantee:</strong></p>
                       <p>• <strong>12 Months (1 Year) Comprehensive Warranty</strong> on casting frame, hardened gear train, and spindle assembly.<br>
                          • Grade-25 heavy-duty cast iron body with vibration-free stability.<br>
                          • Every machine undergoes a 100% pre-dispatch dial-gauge alignment &amp; torque run-test at our Bhavnagar plant.<br>
                          • Genuine spare parts and engineer service support available directly from factory.</p>`,
                rich: `<div style="margin-top:6px;"><a href="<?= BASE_URL ?>/catalog.php" class="btn btn-outline" style="font-size:11px; display:block; text-align:center;">📖 View Quality Certifications</a></div>`
            };
        }

        // 3. Delivery, Shipping Timeline & Transport Carriers
        if (q.includes('delivery') || q.includes('dispatch') || q.includes('transport') || q.includes('freight') || q.includes('shipping') || q.includes('કેટલા દિવસ') || q.includes('ક્યારે મળશે') || q.includes('ડિલિવરી') || q.includes('ડિસ્પેચ') || q.includes('kitne din')) {
            return {
                text: `<p><strong>🚚 Dispatch &amp; Doorstep Logistics Timeline:</strong></p>
                       <p>• <strong>Gujarat:</strong> 24 to 48 hours (૧ થી ૨ દિવસ)<br>
                          • <strong>Maharashtra &amp; MP:</strong> 48 to 72 hours (૨ થી ૩ દિવસ)<br>
                          • <strong>Rest of India:</strong> 3 to 5 business days<br>
                          • <strong>Logistics Partners:</strong> VRL Logistics Heavy Freight, TCI Freight, ARC.<br>
                          • Consignment packed in reinforced wooden crate with anti-rust oil wrap and official LR / E-Way Bill.</p>`,
                rich: `<div style="margin-top:6px;"><a href="<?= BASE_URL ?>/track-order.php" class="btn btn-primary" style="background:linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color:#060911; font-weight:800; font-size:11px; display:block; text-align:center;">🚚 Real-Time Live Order Tracking</a></div>`
            };
        }

        // 4. Online Payment Limit (₹25,000+ UPI Limit & Bank Transfer Solution)
        if (q.includes('25000') || q.includes('૨૫૦૦૦') || q.includes('exceed') || q.includes('limit') || q.includes('લિમિટ') || q.includes('fail') || q.includes('payment problem') || q.includes('pay nahi') || q.includes('નથી થતું') || q.includes('10 lakh') || q.includes('1000000')) {
            return {
                text: `<p><strong>💡 ₹25,000+ Payment Limit Solution (પેમેન્ટ લિમિટ માર્ગદર્શિકા):</strong></p>
                       <p>RBI અને NPCI ના નિયમ મુજબ ગૂગલ પે, ફોનપે કે UPI માં સિંગલ ટ્રાન્ઝેક્શન પર ₹25,000 થી ₹1,00,000 ની લિમિટ હોય છે. આથી ₹1,32,750 કે વધુ રકમનું UPI પેમેન્ટ "Amount exceeds maximum amount allowed" બતાવી શકે છે.<br><br>
                       <strong>બેસ્ટ સોલ્યુશન:</strong><br>
                       ૧. <strong>ટોકન એડવાન્સ:</strong> માત્ર <strong>₹ 25,000</strong> ઓનલાઇન ભરીને મશીનનો સ્લોટ બુક કરો (બાકી રકમ ડિસ્પેચ સમયે આપવી).<br>
                       ૨. <strong>ડાયરેક્ટ RTGS / NEFT:</strong> અમારા HDFC / SBI Current Account માં કોઈ પણ લિમિટ વગર અનલિમિટેડ બેંક ટ્રાન્સફર કરો.<br>
                       ૩. <strong>ડેબિટ / ક્રેડિટ કાર્ડ અથવા નેટબેંકિંગ:</strong> મોટાભાગની બેંકોમાં નેટબેંકિંગથી પૂરેપૂરું પેમેન્ટ થઈ શકે છે.</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:8px;">
                            <a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981;">🪙 ₹25,000 ટોકનથી બુક કરો</a>
                            <a href="<?= BASE_URL ?>/contact.php" class="btn btn-outline" style="font-size:11px; flex:1;">🏛️ બેંક વિગતો જુઓ</a>
                       </div>`
            };
        }

        // 5. Payment Methods & Company Bank Account Details
        if (q.includes('bank') || q.includes('rtgs') || q.includes('neft') || q.includes('imps') || q.includes('ifsc') || q.includes('account number') || q.includes('બેંક') || q.includes('એકાઉન્ટ') || q.includes('cheque') || q.includes('ચેક') || q.includes('cod')) {
            return {
                text: `<p><strong>🏛️ Company Bank Current Account Details (For RTGS / NEFT / Cheque):</strong></p>
                       <p>• <strong>Beneficiary:</strong> SHREE VARSHA ENGINEERING WORKS<br>
                          • <strong>Bank Name:</strong> HDFC Bank Ltd. (or State Bank of India)<br>
                          • <strong>Current A/C No:</strong> 50200088992211<br>
                          • <strong>IFSC Code:</strong> HDFC0000123 (Waghawadi Road Branch, Bhavnagar)<br>
                          • <strong>UPI ID:</strong> shreevarsha@okhdfcbank<br>
                          • <strong>Payment Modes Accepted:</strong> Instant UPI, Cards, NetBanking, Cash on Delivery (COD), Direct Bank Wire (RTGS/NEFT), Account Payee Cheque / DD.</p>`,
                rich: `<div style="margin-top:6px;"><a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary" style="font-size:11px; display:block; text-align:center; background:#0284c7;">💳 Proceed to Checkout &amp; Pay</a></div>`
            };
        }

        // 6. E-Way Bill & Government Portal
        if (q.includes('eway') || q.includes('e-way') || q.includes('ઇ-વે') || q.includes('ઈ-વે') || q.includes('પરિવહન') || q.includes('ewb') || q.includes('portal') || q.includes('પોર્ટલ')) {
            return {
                text: `<p><strong>🏛️ Government GST E-Way Bill (FORM GST EWB-01):</strong></p>
                       <p>• <strong>Supplier GSTIN:</strong> <?= COMPANY_GSTIN ?><br>
                          • <strong>HSN Code:</strong> 8459 (Industrial Drilling Machinery)<br>
                          • <strong>Govt E-Way Clearance:</strong> Complies with Rule 138 of CGST Rules 2017 with verified 12-digit E-Way Bill Number and dynamic QR code.<br>
                          • You can inspect customer-wise E-Way bills directly or open the official government portal login.</p>`,
                rich: `<div style="display:flex; flex-direction:column; gap:6px; margin-top:8px;">
                            <a href="<?= BASE_URL ?>/eway_bill.php" class="btn btn-primary" style="font-size:11px; text-align:center; background:#0284c7;">🚚 View GST E-Way Bill Details</a>
                            <a href="https://ewaybillgst.gov.in/login.aspx" target="_blank" class="btn btn-outline" style="font-size:11px; text-align:center; border-color:#fbbf24; color:#fbbf24;">🏛️ Open Govt E-Way Portal Login ↗</a>
                       </div>`
            };
        }

        // 6B. Official GST Tax Invoice & Customer Bills
        if (q.includes('tax invoice') || q.includes('bill') || q.includes('બીલ') || q.includes('ઇન્વોઇસ') || q.includes('રસીદ') || q.includes('invoice') || q.includes('receipt')) {
            return {
                text: `<p><strong>📄 Official GST Tax Invoice &amp; Certified Bill:</strong></p>
                       <p>• <strong>Instant Generation:</strong> Tax Invoice is generated immediately upon successful payment with verified cryptographic seal.<br>
                          • <strong>Authorized Signatory:</strong> Bears official digital signature of Jay Parmar &amp; company seal.<br>
                          • <strong>Find Your Past Bills:</strong> Enter your registered mobile number on the invoice page or log into your Customer Dashboard to view all orders.</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:8px;">
                            <a href="<?= BASE_URL ?>/invoice.php" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981;">📄 Search / View Invoices</a>
                            <a href="<?= BASE_URL ?>/customer-dashboard.php" class="btn btn-outline" style="font-size:11px; flex:1; border-color:#00e5ff; color:#00e5ff;">📊 Customer Dashboard</a>
                       </div>`
            };
        }

        // 7. Spare Parts & Tooling Accessories
        if (q.includes('part') || q.includes('spares') || q.includes('spindle') || q.includes('gear') || q.includes('chuck') || q.includes('pump') || q.includes('table') || q.includes('સ્પેર') || q.includes('પાર્ટ') || q.includes('સ્પિન્ડલ')) {
            return {
                text: `<p><strong>⚙️ 100% Genuine Factory Spare Parts:</strong></p>
                       <p>• <strong>MT-4 Precision Alloy Spindle Quill:</strong> ₹ 14,500<br>
                          • <strong>8-Speed Headstock Alloy Gear Set:</strong> ₹ 22,000<br>
                          • <strong>T-Slot Box Table (500x600mm):</strong> ₹ 18,500<br>
                          • <strong>Keyless Drill Chuck (1-16mm) with MT Arbor:</strong> ₹ 3,800<br>
                          • <strong>High Flow Coolant Pump &amp; Hose Kit:</strong> ₹ 4,200<br>
                          You can click any spare part on our website to see its enlarged zoomed photo and technical fitment.</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:6px;">
                            <a href="<?= BASE_URL ?>/machines.php?category=spindles-quills" class="btn btn-outline" style="font-size:11px; flex:1;">Browse All Parts</a>
                            <a href="<?= BASE_URL ?>/machine-detail.php?id=101" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981;">View Parts &amp; Zoom</a>
                       </div>`
            };
        }

        // 8. Specific Machine Models
        // 40mm Radial
        if (q.includes('40mm') || q.includes('rd-40') || q.includes('rd 40') || (q.includes('radial') && q.includes('40'))) {
            return {
                text: `<p><strong>SVEW-RD-40 All-Geared Radial Drill (40mm):</strong></p>
                       <p>• Drilling Capacity: 40mm Steel / 50mm Cast Iron<br>
                          • Spindle Speeds: 8 Speeds (50 to 1800 RPM) • MT-4 Taper<br>
                          • Column Diameter: 165mm • Weight: Approx 1,650 kg<br>
                          • Factory Ex-Price: <strong>₹ 2,85,000</strong> (+18% GST)<br>
                          • Token Advance: <strong>₹ 25,000</strong> to lock manufacturing queue.</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:6px;">
                            <a href="<?= BASE_URL ?>/checkout.php?product_id=1" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981;">💳 Book SVEW-RD-40</a>
                            <a href="<?= BASE_URL ?>/machine-detail.php?id=1" class="btn btn-outline" style="font-size:11px; flex:1;">Full 12D Specs</a>
                       </div>`
            };
        }

        // 50mm Radial
        if (q.includes('50mm') || q.includes('rd-50') || q.includes('rd 50') || (q.includes('radial') && q.includes('50'))) {
            return {
                text: `<p><strong>SVEW-RD-50 Heavy Duty Radial Drill (50mm):</strong></p>
                       <p>• Capacity: 50mm Steel / 60mm Cast Iron • MT-5 Spindle Taper<br>
                          • Spindle Speeds: 12 Speeds • Column Diameter: 215mm<br>
                          • Weight: Approx 2,400 kg heavy casting<br>
                          • Factory Ex-Price: <strong>₹ 3,95,000</strong> (+18% GST)<br>
                          • Token Advance: <strong>₹ 25,000</strong></p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:6px;">
                            <a href="<?= BASE_URL ?>/checkout.php?product_id=2" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981;">💳 Book SVEW-RD-50</a>
                            <a href="<?= BASE_URL ?>/machine-detail.php?id=2" class="btn btn-outline" style="font-size:11px; flex:1;">Specs &amp; Photos</a>
                       </div>`
            };
        }

        // 65mm Radial
        if (q.includes('65mm') || q.includes('rd-65') || q.includes('rd 65') || (q.includes('radial') && q.includes('65'))) {
            return {
                text: `<p><strong>SVEW-RD-65 Extra Heavy Industrial Radial Drill (65mm):</strong></p>
                       <p>• Capacity: 65mm Steel / 75mm Cast Iron • MT-5 Spindle<br>
                          • Motor: 5.0 HP Main Motor + 1.5 HP Arm Elevation Motor<br>
                          • Weight: Approx 3,600 kg vibration-free mass<br>
                          • Factory Ex-Price: <strong>₹ 5,75,000</strong> (+18% GST)</p>`,
                rich: `<div style="margin-top:6px;"><a href="<?= BASE_URL ?>/checkout.php?product_id=3" class="btn btn-primary" style="font-size:11px; display:block; text-align:center; background:#10b981;">💳 Book SVEW-RD-65</a></div>`
            };
        }

        // Pillar & Bench Drills
        if (q.includes('pillar') || q.includes('bench') || q.includes('પિલર') || q.includes('બેન્ચ') || q.includes('25mm') || q.includes('32mm') || q.includes('20mm')) {
            return {
                text: `<p><strong>Varsha Brand Precision Pillar &amp; Bench Drill Models:</strong></p>
                       <p>• <strong>SVEW-BD-20 (20mm Bench Drill):</strong> ₹ 48,000<br>
                          • <strong>SVEW-PD-25 (25mm Pillar Drill):</strong> ₹ 85,000<br>
                          • <strong>SVEW-PD-32 (32mm Geared Column Pillar):</strong> ₹ 1,35,000<br>
                          • <strong>SVEW-PD-40 (40mm Extra Heavy Pillar):</strong> ₹ 1,95,000<br>
                          • <strong>SVEW-TP-25 (Drilling &amp; Tapping Machine):</strong> ₹ 1,25,000</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:6px;">
                            <a href="<?= BASE_URL ?>/machines.php" class="btn btn-outline" style="font-size:11px; flex:1;">View All Models</a>
                            <a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981;">Book Online</a>
                       </div>`
            };
        }

        // 9. Factory Location & Driving Directions
        if (q.includes('address') || q.includes('location') || q.includes('map') || q.includes('bhavnagar') || q.includes('ક્યાં') || q.includes('સરનામું') || q.includes('પ્લાન્ટ') || q.includes('factory')) {
            return {
                text: `<p><strong>📍 Factory Location &amp; Works Address:</strong></p>
                       <p><strong>SHREE VARSHA ENGINEERING WORKS (VARSHA BRAND)</strong><br>
                          Shed No. L/786, GIDC, Nr. Hadanagar, Bhavnagar - 364003, Gujarat, India.<br>
                          GSTIN: <strong><?= COMPANY_GSTIN ?></strong><br>
                          Visiting Hours: Monday to Saturday, 9:00 AM to 7:00 PM.</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:6px;">
                            <a href="<?= BASE_URL ?>/contact.php#factoryLiveMap" class="btn btn-primary" style="font-size:11px; flex:1; background:linear-gradient(135deg, #00e5ff 0%, #2563eb 100%); color:#060911; font-weight:800;">🛰️ Satellite Map</a>
                            <a href="https://www.google.com/maps/dir/?api=1&destination=21.7645,72.1519" target="_blank" class="btn btn-outline" style="font-size:11px; flex:1;">🚗 Driving Route</a>
                       </div>`
            };
        }

        // 10. General Pricing, Quotes & Catalog
        if (q.includes('price') || q.includes('cost') || q.includes('rate') || q.includes('ભાવ') || q.includes('કિંમત') || q.includes('quote') || q.includes('catalog') || q.includes('pdf')) {
            return {
                text: `<p><strong>📖 Factory Direct Pricing &amp; 2026 Catalog:</strong></p>
                       <p>We supply directly from our Bhavnagar manufacturing unit without middlemen margins. Every machine includes industrial wooden crating, pre-dispatch testing report, and official GST Tax Invoice.</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:6px;">
                            <a href="<?= BASE_URL ?>/catalog.php" class="btn btn-primary" style="font-size:11px; flex:1; background:#0284c7;">📖 2026 Master Catalog PDF</a>
                            <a href="<?= BASE_URL ?>/checkout.php" class="btn btn-outline" style="font-size:11px; flex:1;">💳 Online Booking Form</a>
                       </div>`
            };
        }

        // 11. Multilingual Fallback
        if (isGujarati) {
            return {
                text: `<p><strong>શ્રી વર્ષા એન્જિનિયરિંગ વર્ક્સ AI સહાયક:</strong></p>
                       <p>હું તમને નીચેની બાબતોમાં તરત જ મદદ કરી શકું છું:<br>
                       • <strong>મશીન પસંદગી:</strong> ૨૦ મીમી થી ૬૫ મીમી રેડિયલ અને પિલર ડ્રિલ મશીનો.<br>
                       • <strong>ભાવ અને બુકિંગ:</strong> માત્ર ₹૨૫,૦૦૦ ટોકન એડવાન્સ ભરીને ઓર્ડર કન્ફર્મ કરો.<br>
                       • <strong>સ્પેર પાર્ટ્સ:</strong> ઓરિજિનલ સ્પિન્ડલ, ગિયર સેટ, બોક્સ ટેબલના ફોટો અને ભાવ.<br>
                       • <strong>ટ્રેકિંગ અને બીલ:</strong> પાકું જીએસટી ટેક્સ ઇન્વોઇસ અને ઈ-વે બિલ ડાઉનલોડ.</p>`,
                rich: `<div style="display:flex; flex-wrap:wrap; gap:6px; margin-top:8px;">
                            <a href="<?= BASE_URL ?>/machines.php" class="btn btn-outline" style="font-size:11px; flex:1;">📖 મશીનો જુઓ</a>
                            <a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981;">💳 ₹25,000 થી બુક કરો</a>
                            <a href="<?= getWhatsAppLink(COMPANY_WHATSAPP, 'નમસ્તે, મારે ડ્રિલ મશીન વિશે પૂછવું છે.') ?>" target="_blank" class="btn btn-whatsapp" style="font-size:11px; flex:1;">💬 વોટ્સએપ</a>
                       </div>`
            };
        }

        return {
            text: `<p>Thank you for inquiring with <strong>Shree Varsha Engineering Works (Varsha Brand)</strong>! How can I assist your workshop today?</p>
                   <p>• <strong>Machinery Selection:</strong> 20mm to 65mm Heavy Radial &amp; Pillar Drills<br>
                      • <strong>Online Booking:</strong> Reserve production slot with ₹25,000 Token Advance<br>
                      • <strong>Genuine Spares:</strong> Spindles, Gear Sets, T-Slot Box Tables &amp; Chucks<br>
                      • <strong>Official Bills &amp; E-Way:</strong> Instant GST Tax Invoices with certified seal</p>`,
            rich: `<div style="display:flex; flex-wrap:wrap; gap:6px; margin-top:8px;">
                        <a href="<?= BASE_URL ?>/machines.php" class="btn btn-outline" style="font-size:11px; flex:1;">📖 View Machines</a>
                        <a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981;">💳 Book Machine</a>
                        <a href="<?= BASE_URL ?>/catalog.php" class="btn btn-outline" style="font-size:11px; flex:1;">📄 Download Catalog</a>
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
