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
            <div class="bot-info">
                <div class="bot-name">
                    <span class="bot-name-text">Varsha AI Engineer</span>
                    <span class="bot-badge-official">OFFICIAL</span>
                </div>
                <div class="bot-status">
                    <span class="pulse-dot"></span> <span class="bot-status-text">Online • 24/7 Factory Assistant</span>
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
    padding: 12px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    min-height: 56px;
    box-sizing: border-box;
}
.bot-profile {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
    flex: 1;
    overflow: hidden;
}
.bot-avatar-wrap {
    position: relative;
    flex-shrink: 0;
}
.bot-avatar-img {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: 1.5px solid #00e5ff;
    object-fit: contain;
    background: #0f172a;
    display: block;
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
.bot-info {
    min-width: 0;
    flex: 1;
    overflow: hidden;
}
.bot-name {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 13.5px;
    font-weight: 800;
    color: #fff;
    letter-spacing: 0.3px;
    display: flex;
    align-items: center;
    gap: 5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.bot-name-text {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.bot-badge-official {
    font-size: 8.5px;
    background: rgba(0, 229, 255, 0.2);
    border: 1px solid #00e5ff;
    color: #00e5ff;
    padding: 1px 5px;
    border-radius: 9999px;
    font-weight: 800;
    flex-shrink: 0;
    letter-spacing: 0.5px;
}
.bot-status {
    font-size: 10.5px;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.bot-status-text {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.pulse-dot {
    width: 6px;
    height: 6px;
    background: #10b981;
    border-radius: 50%;
    box-shadow: 0 0 6px #10b981;
    flex-shrink: 0;
}
.chatbot-header-actions {
    display: flex;
    align-items: center;
    gap: 5px;
    flex-shrink: 0;
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
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s;
    flex-shrink: 0;
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
    align-items: flex-start !important;
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
.msg-avatar {
    width: 32px !important;
    height: 32px !important;
    min-width: 32px !important;
    min-height: 32px !important;
    max-width: 32px !important;
    max-height: 32px !important;
    flex: 0 0 32px !important;
    flex-shrink: 0 !important;
    align-self: flex-start !important;
    border-radius: 50% !important;
    border: 1.5px solid #00e5ff !important;
    background: #0f172a !important;
    overflow: hidden !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    box-shadow: 0 0 10px rgba(0, 229, 255, 0.45) !important;
    margin-top: 2px !important;
}
.msg-avatar img {
    width: 100% !important;
    height: 100% !important;
    border-radius: 50% !important;
    border: none !important;
    object-fit: cover !important;
    display: block !important;
    background: transparent !important;
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

@media (max-width: 768px) {
    .chatbot-header {
        padding: 10px 12px;
        gap: 6px;
    }
    .bot-name {
        font-size: 13px;
    }
    .bot-status {
        font-size: 10px;
    }
    .bot-tool-btn {
        width: 26px;
        height: 26px;
        font-size: 11px;
    }
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
        left: 8px;
        right: 8px;
        width: auto;
        height: calc(100vh - 100px);
        max-height: 560px;
        border-radius: 14px;
    }
    .chatbot-header {
        padding: 8px 10px;
        gap: 6px;
        min-height: 50px;
    }
    .bot-profile {
        gap: 8px;
    }
    .bot-avatar-img {
        width: 32px;
        height: 32px;
    }
    .bot-name {
        font-size: 12px;
        gap: 4px;
    }
    .bot-badge-official {
        font-size: 7.5px;
        padding: 1px 4px;
    }
    .bot-status {
        font-size: 9.5px;
    }
    .chatbot-header-actions {
        gap: 3px;
    }
    .bot-tool-btn {
        width: 25px;
        height: 25px;
        font-size: 11px;
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

        // 0.98. Live Order Tracking & Dispatch Status
        const isTrackingQuery = q.includes('track') || q.includes('tracking') || q.includes('status') || 
                                q.includes('ટ્રેક') || q.includes('ટ્રેકિંગ') || q.includes('ઓર્ડર ક્યાં') || 
                                q.includes('order status') || q.includes('where is my order') || 
                                q.includes('live track') || q.includes('મારો ઓર્ડર');

        if (isTrackingQuery) {
            if (isGujarati) {
                return {
                    text: `<p><strong>🚚 લાઇવ ઓર્ડર ટ્રેકિંગ અને ડિસ્પેચ સ્ટેટસ (Live Order Tracking):</strong></p>
                           <p>તમે તમારા મશીનનો ઓર્ડર ૨૪/૭ લાઈવ ટ્રેક કરી શકો છો:<br>
                           • <strong>કેવી રીતે ટ્રેક કરવું:</strong> અમારા ટ્રેકિંગ પેજ પર તમારો <strong>મોબાઇલ નંબર</strong> અથવા <strong>ઓર્ડર નંબર</strong> (દા.ત. SVEW-ORD-...) દાખલ કરો.<br>
                           • <strong>૭ સ્ટેજ ટ્રેકિંગ:</strong> ઓર્ડર કન્ફર્મ -> વુડન ક્રેટ પેકિંગ -> VRL/TCI ફ્રેઇટ ડિસ્પેચ -> રસ્તા પર પરિવહન -> તમારા વર્કશોપ પર ડિલિવરી.<br>
                           • અધિકૃત GST ઇ-વે બિલ અને ટ્રાન્સપોર્ટ LR કોપી તરત જ દેખાશે.</p>`,
                    rich: `<div style="display:flex; gap:6px; margin-top:8px;">
                                <a href="<?= BASE_URL ?>/track-order.php" class="btn btn-primary" style="font-size:11px; flex:1; background:linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color:#060911; font-weight:800; text-align:center;">🚚 લાઇવ ઓર્ડર ટ્રેક કરો</a>
                                <a href="<?= BASE_URL ?>/customer-dashboard.php" class="btn btn-outline" style="font-size:11px; flex:1; text-align:center; border-color:#00e5ff; color:#00e5ff;">📊 કસ્ટમર ડેશબોર્ડ</a>
                           </div>`
                };
            }
            return {
                text: `<p><strong>🚚 Real-Time Live Order &amp; Consignment Tracking:</strong></p>
                       <p>You can track your Varsha Brand drill machine consignment 24/7 across all 7 factory logistics stages:<br>
                       • <strong>How to Track:</strong> Simply enter your <strong>10-digit registered mobile number</strong> or <strong>Order ID</strong> (e.g. SVEW-ORD-...) on our tracking portal.<br>
                       • <strong>7 Real-Time Milestones:</strong> Order Confirmed → VCI Protective Wrap &amp; Wooden Crating → Transport Loading → Highway In-Transit → Doorstep Unloading &amp; Commissioning.<br>
                       • View transporter name, vehicle number, transport LR docket, and official GST E-Way Bill.</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:8px;">
                            <a href="<?= BASE_URL ?>/track-order.php" class="btn btn-primary" style="font-size:11px; flex:1; background:linear-gradient(135deg, #0284c7 0%, #00e5ff 100%); color:#060911; font-weight:800; text-align:center;">🚚 Live Order Tracking</a>
                            <a href="<?= BASE_URL ?>/customer-dashboard.php" class="btn btn-outline" style="font-size:11px; flex:1; text-align:center; border-color:#00e5ff; color:#00e5ff;">📊 View All Orders</a>
                       </div>`
            };
        }

        // 0.99. How to Book Online & Purchasing Guide
        const isBookingQuery = q.includes('how to book') || q.includes('book online') || q.includes('how to order') || 
                               q.includes('booking') || q.includes('બુકિંગ') || q.includes('ઓર્ડર કરવો') || 
                               q.includes('કેવી રીતે બુક') || q.includes('ખરીદવું') || q.includes('order kaise') || 
                               q.includes('buy drill') || q.includes('buy machine') || (q.includes('book') && !q.includes('facebook'));

        if (isBookingQuery) {
            if (isGujarati) {
                return {
                    text: `<p><strong>💳 વર્ષા બ્રાન્ડ ડ્રિલ મશીન ઓનલાઇન બુકિંગ ગાઇડ (3 Simple Steps):</strong></p>
                           <p>૧. <strong>મોડલ પસંદ કરો:</strong> ૨૦મીમી થી ૬૫મીમી રેડિયલ અથવા પિલર ડ્રિલ મશીન પસંદ કરો.<br>
                           ૨. <strong>ટોકન એડવાન્સ ભરો:</strong> માત્ર <strong>₹ 25,000</strong> ટોકન એડવાન્સ (અથવા ૧૦૦% પેમેન્ટ) UPI, કાર્ડ્સ કે નેટબેંકિંગથી ઓનલાઇન ભરીને પ્રોડક્શન સ્લોટ લોક કરો.<br>
                           ૩. <strong>ઇન્સ્ટન્ટ જીએસટી બિલ:</strong> પેમેન્ટ થતાં જ સત્તાવાર GST Tax Invoice નંબર જનરેટ થશે અને મશીનનું હેવી વુડન બોક્સ પેકિંગ શરૂ થઈ જશે.<br>
                           • બાકી રકમ ફેક્ટરીમાંથી મશીન ડિસ્પેચ થાય ત્યારે ટ્રાન્સપોર્ટ LR કોપી સામે ચૂકવવાની રહેશે.</p>`,
                    rich: `<div style="display:flex; gap:6px; margin-top:8px;">
                                <a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981; font-weight:800; text-align:center;">💳 ₹25,000 ટોકનથી બુક કરો</a>
                                <a href="<?= BASE_URL ?>/machines.php" class="btn btn-outline" style="font-size:11px; flex:1; text-align:center;">📖 મશીનો જુઓ</a>
                           </div>`
                };
            }
            return {
                text: `<p><strong>💳 Step-by-Step Online Machine Booking Guide:</strong></p>
                       <p>Booking a precision drill machine direct from our Bhavnagar factory is quick &amp; 100% secure:<br>
                       • <strong>Step 1:</strong> Select your preferred model (20mm Bench, 25/32/40mm Pillar, or 40/50/65mm Radial).<br>
                       • <strong>Step 2:</strong> Pay the standard <strong>₹25,000 Advance Token</strong> via instant UPI, Debit/Credit Card, NetBanking, or RTGS to reserve your factory build queue.<br>
                       • <strong>Step 3:</strong> Receive your instant official GST Tax Invoice and unique Order Tracking ID.<br>
                       • <strong>Balance Payment:</strong> The remaining balance is payable upon pre-dispatch inspection against the Transport Lorry Receipt (LR) copy.</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:8px;">
                            <a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981; font-weight:800; text-align:center;">💳 Book Machine Online (₹25k)</a>
                            <a href="<?= BASE_URL ?>/machines.php" class="btn btn-outline" style="font-size:11px; flex:1; text-align:center;">📖 View All Models</a>
                       </div>`
            };
        }

        // 0.991. Advance Token (₹25,000) Policy & Clarification
        const isAdvanceTokenQuery = q.includes('token') || q.includes('advance') || q.includes('ટોકન') || 
                                    q.includes('૨૫૦૦૦') || q.includes('25000') || q.includes('advance payment') || 
                                    q.includes('kitna advance') || q.includes('advans');

        if (isAdvanceTokenQuery) {
            if (isGujarati) {
                return {
                    text: `<p><strong>🪙 ₹25,000 ટોકન એડવાન્સ સિસ્ટમ (Token Advance Explained):</strong></p>
                           <p>• <strong>શા માટે ₹25,000 ટોકન?</strong> મશીનની પૂરી કિંમત (દા.ત. ₹૨.૮૫ લાખ) મોટી હોવાથી UPI અને બેંકિંગ લિમિટ નડતી હોય છે. આથી અમે માત્ર <strong>₹ 25,000</strong> ના ન્યૂનતમ ટોકનથી ઓર્ડર કન્ફર્મ કરીએ છીએ.<br>
                           • <strong>લાભ:</strong> ટોકન ભરતા જ ભાવ લોક થઈ જાય છે, મશીન રિઝર્વ થાય છે અને સત્તાવાર GST Tax Invoice જનરેટ થાય છે.<br>
                           • <strong>બાકી રકમ:</strong> બાકીનું પેમેન્ટ મશીન ટેસ્ટ-રન થઈને ટ્રાન્સપોર્ટમાં મુકાય ત્યારે LR બિલ્ટી સામે ચૂકવવાનું રહેશે.</p>`,
                    rich: `<div style="display:flex; gap:6px; margin-top:8px;">
                                <a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981; font-weight:800; text-align:center;">🪙 ₹25,000 ટોકનથી બુક કરો</a>
                                <a href="<?= BASE_URL ?>/contact.php" class="btn btn-outline" style="font-size:11px; flex:1; text-align:center;">🏛️ બેંક RTGS વિગતો</a>
                           </div>`
                };
            }
            return {
                text: `<p><strong>🪙 What is the ₹25,000 Token Advance?</strong></p>
                       <p>• <strong>Production Queue Lock:</strong> Instead of paying ₹2 to ₹5 Lakhs upfront, you only pay <strong>₹25,000</strong> to start factory scheduling, pre-dispatch testing, and wooden crating.<br>
                       • <strong>Overcomes UPI Limits:</strong> Eliminates "Transaction Limit Exceeded" errors common on Google Pay / PhonePe for high-value machinery.<br>
                       • <strong>Balance Payment:</strong> Balance amount is payable when machine is tested and loaded into transport against official LR (Lorry Receipt).<br>
                       • Spare parts require 100% full payment as they dispatch the very same day.</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:8px;">
                            <a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981; font-weight:800; text-align:center;">🪙 Book with ₹25,000 Token</a>
                            <a href="<?= BASE_URL ?>/faqs.php" class="btn btn-outline" style="font-size:11px; flex:1; text-align:center;">❓ Read FAQs</a>
                       </div>`
            };
        }

        // 0.992. Machine Selection & Recommendation Guide (Which machine should I buy?)
        const isRecommendQuery = q.includes('recommend') || q.includes('which machine') || q.includes('buying guide') || 
                                 q.includes('કયું મશીન') || q.includes('પસંદગી') || q.includes('સિલેક્શન') || 
                                 q.includes('સૌથી સારું') || q.includes('guide') || q.includes('કાણું પાડવા') || 
                                 q.includes('કપાસિયા') || q.includes('ફેબ્રિકેશન') || q.includes('લેથ') || 
                                 q.includes('kon sa machine') || q.includes('best machine');

        if (isRecommendQuery) {
            if (isGujarati) {
                return {
                    text: `<p><strong>🎯 તમારી જરૂરિયાત મુજબ બેસ્ટ ડ્રિલ મશીન પસંદગી ગાઇડ:</strong></p>
                           <p>• <strong>૧ થી ૨૦ મીમી કાણાં / લાઇટ વર્ક:</strong> <strong>SVEW-BD-20</strong> (૨૦મીમી બેન્ચ ડ્રિલ - ₹48,000)<br>
                           • <strong>૧ થી ૨૫ મીમી સામાન્ય ફેબ્રિકેશન &amp; ગેરેજ:</strong> <strong>SVEW-PD-25</strong> (૨૫મીમી પિલર ડ્રિલ - ₹85,000)<br>
                           • <strong>૧ થી ૩૨ મીમી ગિયર ડ્રાઇવ હેવી વર્ક:</strong> <strong>SVEW-PD-32</strong> (૩૨મીમી ગિયર્ડ પિલર - ₹1,35,000)<br>
                           • <strong>ઇન્ટરનલ થ્રેડિંગ (M6 થી M24 આંટા પાડવા):</strong> <strong>SVEW-TP-25</strong> (ટેપિંગ મશીન - ₹1,25,000)<br>
                           • <strong>મોટા જોબ / ફ્લેંજ ફેબ્રિકેશન (૪૦મીમી સુધી):</strong> <strong>SVEW-RD-40</strong> (૪૦મીમી ઓલ-ગિયર્ડ રેડિયલ - ₹2,85,000)<br>
                           • <strong>હેવી ઇન્ડસ્ટ્રીયલ સ્ટ્રક્ચરલ (૫૦મીમી):</strong> <strong>SVEW-RD-50</strong> (૫૦મીમી હેવી રેડિયલ - ₹3,95,000)<br>
                           • <strong>અલ્ટ્રા હેવી / શિપયાર્ડ (૬૫મીમી):</strong> <strong>SVEW-RD-65</strong> (૬૫મીમી રેડિયલ - ₹5,75,000)</p>`,
                    rich: `<div style="display:flex; gap:6px; margin-top:8px;">
                                <a href="<?= BASE_URL ?>/machines.php" class="btn btn-primary" style="font-size:11px; flex:1; background:#0284c7; text-align:center;">📖 તમામ મોડલ સરખાવો</a>
                                <a href="<?= BASE_URL ?>/simulator-6d.php" class="btn btn-outline" style="font-size:11px; flex:1; text-align:center;">⚡ AI પેરામીટર કેલ્ક્યુલેટર</a>
                           </div>`
                };
            }
            return {
                text: `<p><strong>🎯 Engineering Recommendation: Choosing the Right Drill Machine:</strong></p>
                       <p>• <strong>Light Fabrication &amp; Tool Rooms (Up to 20mm):</strong> <strong>SVEW-BD-20</strong> (20mm High Speed Bench - ₹48,000)<br>
                       • <strong>Automotive Workshops &amp; General Fabrication (Up to 25mm):</strong> <strong>SVEW-PD-25</strong> (25mm Pillar - ₹85,000)<br>
                       • <strong>Heavy Production &amp; Agricultural Implements (Up to 32mm):</strong> <strong>SVEW-PD-32</strong> (32mm Geared Column Pillar - ₹1,35,000)<br>
                       • <strong>Thread Tapping (M6 to M24 Internal Threads):</strong> <strong>SVEW-TP-25</strong> (Auto-Reversing Tapping - ₹1,25,000)<br>
                       • <strong>Flange Drilling &amp; Structural Jobs (Up to 40mm):</strong> <strong>SVEW-RD-40</strong> (40mm All-Geared Radial - ₹2,85,000)<br>
                       • <strong>Heavy Plate &amp; Pressure Vessels (Up to 50mm):</strong> <strong>SVEW-RD-50</strong> (50mm Heavy Radial - ₹3,95,000)<br>
                       • <strong>Shipyards &amp; Ultra-Heavy Machinery (Up to 65mm):</strong> <strong>SVEW-RD-65</strong> (65mm Extra Heavy - ₹5,75,000)</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:8px;">
                            <a href="<?= BASE_URL ?>/machines.php" class="btn btn-primary" style="font-size:11px; flex:1; background:#0284c7; text-align:center;">📖 Browse All Machines</a>
                            <a href="<?= BASE_URL ?>/simulator-6d.php" class="btn btn-outline" style="font-size:11px; flex:1; text-align:center;">⚡ 360° AI Simulator Lab</a>
                       </div>`
            };
        }

        // 0.993. Discounts, Offers & Factory-Direct Pricing
        const isDiscountQuery = q.includes('discount') || q.includes('offer') || q.includes('ઓફર') || 
                                q.includes('ડિસ્કાઉન્ટ') || q.includes('છૂટ') || q.includes('best rate') || 
                                q.includes('best price') || q.includes('sastu') || q.includes('kam bhav') || 
                                q.includes('deal') || q.includes('bargain');

        if (isDiscountQuery) {
            if (isGujarati) {
                return {
                    text: `<p><strong>💰 ફેક્ટરી ડાયરેક્ટ ભાવ અને ડિસ્કાઉન્ટ પોલિસી:</strong></p>
                           <p>• <strong>ઝીરો વચેટિયા કમિશન:</strong> અમે ભાવનગરમાં સીધા ઉત્પાદક છીએ, આથી કોઈ ડીલર કે ડિસ્ટ્રીબ્યુટર વગર સીધો ફેક્ટરી ભાવ મળે છે (બજાર કરતાં ૨૦-૨૫% ની બચત).<br>
                           • <strong>કોમ્બો બંડલ ઓફર:</strong> મશીન સાથે સ્પેર પાર્ટ્સ (સ્પિન્ડલ, બોક્સ ટેબલ, કૂલન્ટ પંપ) ખરીદવા પર વધારાનું <strong>5% સ્પેશિયલ કોમ્બો ડિસ્કાઉન્ટ</strong> મળે છે.<br>
                           • <strong>ફ્રી ઇન્ડસ્ટ્રીયલ પેકિંગ:</strong> દરેક મશીન પર ભારે વુડન બોક્સ ક્રેટિંગ અને રસ્ટ-પ્રુફ ઓઇલ કોટિંગ સંપૂર્ણ ફ્રી મળે છે.</p>`,
                    rich: `<div style="display:flex; gap:6px; margin-top:8px;">
                                <a href="<?= BASE_URL ?>/machines.php" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981; text-align:center;">💰 તમામ મોડલના ભાવ જુઓ</a>
                                <a href="<?= BASE_URL ?>/catalog.php" class="btn btn-outline" style="font-size:11px; flex:1; text-align:center;">📖 કેટલોગ ડાઉનલોડ કરો</a>
                           </div>`
                };
            }
            return {
                text: `<p><strong>💰 Direct Factory Pricing &amp; Value Guarantee:</strong></p>
                       <p>• <strong>0% Middleman Margin:</strong> You buy directly from Shree Varsha Engineering Works factory in Bhavnagar, saving 20-25% over third-party dealer markups.<br>
                       • <strong>Combo Bundle Savings:</strong> Save <strong>5% extra</strong> on essential tooling &amp; spare accessories (Box Tables, Coolant Pumps, Keyless Chucks) when bundled with any drill machine.<br>
                       • <strong>Free Reinforced Packaging:</strong> Heavy fumigated wooden box crating and VCI moisture-proof wrap included free of charge with all orders.</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:8px;">
                            <a href="<?= BASE_URL ?>/machines.php" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981; text-align:center;">💰 View Machine Prices</a>
                            <a href="<?= BASE_URL ?>/catalog.php" class="btn btn-outline" style="font-size:11px; flex:1; text-align:center;">📖 2026 Price Catalog</a>
                       </div>`
            };
        }

        // 0.994. Electric Motor, Horsepower & Power Supply
        const isMotorQuery = q.includes('motor') || q.includes('hp') || q.includes('મોટર') || 
                             q.includes('હોર્સપાવર') || q.includes('electric') || q.includes('voltage') || 
                             q.includes('phase') || q.includes('power') || q.includes('વીજળી');

        if (isMotorQuery) {
            return {
                text: `<p><strong>⚡ ઇલેક્ટ્રિક મોટર અને પાવર સ્પેસિફિકેશન (Electric Motors &amp; Power):</strong></p>
                       <p>અમારા તમામ મશીનોમાં ૧૦૦% પ્યોર ઇલેક્ટ્રોલિટીક કોપર વાઇન્ડિંગ વાળી હેવી ડ્યુટી 415V, 3-Phase ઇન્ડક્શન મોટર્સ વપરાય છે:<br>
                       • <strong>SVEW-BD-20 (20mm Bench):</strong> 0.75 HP (0.55 kW)<br>
                       • <strong>SVEW-PD-25 (25mm Pillar):</strong> 1.0 HP (0.75 kW)<br>
                       • <strong>SVEW-PD-32 (32mm Geared Pillar):</strong> 1.5 HP (1.1 kW)<br>
                       • <strong>SVEW-PD-40 (40mm Heavy Pillar):</strong> 2.0 HP (1.5 kW)<br>
                       • <strong>SVEW-TP-25 (Tapping Machine):</strong> 1.5 HP Auto-Reversing Special Motor<br>
                       • <strong>SVEW-RD-40 (40mm Radial):</strong> 2.0 HP ડ્રિલ મોટર + 1.0 HP આર્મ એલિવેશન મોટર<br>
                       • <strong>SVEW-RD-50 (50mm Radial):</strong> 3.0 HP ડ્રિલ મોટર + 1.5 HP આર્મ એલિવેશન મોટર<br>
                       • <strong>SVEW-RD-65 (65mm Radial):</strong> 5.0 HP હેવી ડ્રાઇવ મોટર + 2.0 HP આર્મ એલિવેશન મોટર</p>`,
                rich: `<div style="margin-top:6px;"><a href="<?= BASE_URL ?>/catalog.php" class="btn btn-outline" style="font-size:11px; display:block; text-align:center;">📖 ટેકનિકલ ડેટાશીટ જુઓ</a></div>`
            };
        }

        // 0.995. Spindle Speeds, RPM, Feed Rate & Transmission
        const isSpeedRpmQuery = q.includes('rpm') || q.includes('speed') || q.includes('feed') || 
                                q.includes('ગતિ') || q.includes('સ્પીડ') || q.includes('gearbox') || 
                                q.includes('ગિયર') || q.includes('ચક્કર');

        if (isSpeedRpmQuery) {
            return {
                text: `<p><strong>⚙️ સ્પિન્ડલ સ્પીડ રેન્જ અને ઓટોમેટિક ફીડ (Spindle Speeds &amp; RPM):</strong></p>
                       <p>• <strong>રેડિયલ ડ્રિલ્સ (RD-40 / RD-50 / RD-65):</strong> ૮ થી ૧૬ ઓલ-ગિયર્ડ સ્પીડ સ્ટેપ્સ (૨૫ થી ૧૮૦૦ RPM) જે ઓઇલ બાથ ગિયરબોક્સમાં ચાલે છે.<br>
                       • <strong>ઓટો ફીડ મેકેનિઝમ:</strong> મશીનમાં હેવી ડ્યુટી મિકેનિકલ ઓટોમેટિક સ્પીન્ડલ ફીડ સિસ્ટમ (૦.૦૫ થી ૦.૩૫ mm/rev) છે, જેથી ડીપ હોલ ડ્રિલિંગ આપોઆપ સરળતાથી થાય છે.<br>
                       • <strong>સ્પિન્ડલ ટેપર:</strong> Morse Taper MT-3 (25mm/32mm), MT-4 (40mm), અને MT-5 (50mm/65mm) જેથી મોટા ડ્રિલ બિટ સીધા ફિટ થાય છે.<br>
                       • ચોક્કસ સ્પીડ ગણતરી માટે અમારું 12D AI ટૂલ વાપરો.</p>`,
                rich: `<div style="margin-top:6px;"><a href="<?= BASE_URL ?>/simulator-6d.php" class="btn btn-primary" style="font-size:11px; display:block; text-align:center; background:#0284c7;">⚡ લાઈવ RPM &amp; ફીડ કેલ્ક્યુલેટ કરો</a></div>`
            };
        }

        // 0.996. Lubrication, Gear Oil & Maintenance Guidelines
        const isOilQuery = q.includes('oil') || q.includes('lubricat') || q.includes('ઓઇલ') || 
                           q.includes('તેલ') || q.includes('મેન્ટેનન્સ') || q.includes('maintenance') || 
                           q.includes('grease');

        if (isOilQuery) {
            return {
                text: `<p><strong>🛢️ ગિયર ઓઇલ અને મેન્ટેનન્સ માર્ગદર્શિકા (Lubrication Guide):</strong></p>
                       <p>• <strong>ગિયરબોક્સ ઓઇલ:</strong> ઓલ-ગિયર્ડ હેડસ્ટોક માટે <strong>ISO VG 68 અથવા 90 ગ્રેડ ઇન્ડસ્ટ્રીયલ ગિયર ઓઇલ</strong> વાપરવું.<br>
                       • <strong>ઓઇલ બદલવાની અવધિ:</strong> શરૂઆતમાં પ્રથમ 500 કલાક રન પછી ઓઇલ બદલવું, ત્યારબાદ દર 12 મહિને ઓઇલ બદલવું.<br>
                       • <strong>ગ્રીસિંગ:</strong> એલિવેટિંગ સ્ક્રૂ, કોલમ સ્લીવ અને આર્મ રેક પર દર અઠવાડિયે ઉચ્ચ-ગુણવત્તાવાળું લિથિયમ બેઝ ગ્રીસ લગાવવું.<br>
                       • ઓઇલ લેવલ તપાસવા માટે હેડસ્ટોક પર પારદર્શક ઓઇલ ગેજ ગ્લાસ આપેલો છે.</p>`,
                rich: `<div style="margin-top:6px;"><a href="<?= BASE_URL ?>/catalog.php" class="btn btn-outline" style="font-size:11px; display:block; text-align:center;">📖 મેન્ટેનન્સ મેન્યુઅલ PDF</a></div>`
            };
        }

        // 0.997. Courtesy & Thank You
        if (/\b(thank|thanks|dhanyawad|shukriya|aabhar)\b/i.test(q) || q.includes('આભાર') || q.includes('ધન્યવાદ') || q.includes('થેન્ક્સ') || q.includes('shukriya')) {
            return {
                text: `<p><strong>🙏 આપનો ખૂબ ખૂબ આભાર! (You are most welcome!)</strong></p>
                       <p>શ્રી વર્ષા એન્જિનિયરિંગ વર્ક્સ સાથે જોડાવા બદલ આનંદ થયો. જો આપને મશીન સિલેક્શન, ફેક્ટરી વિઝિટ કે વિશેષ ઓર્ડર બાબતે કોઈ પણ પ્રશ્ન હોય, તો અમારા પ્રોડક્શન એન્જિનિયરનો સીધો સંપર્ક કરી શકો છો:</p>
                       <p>📞 <strong>+91 98249 13555</strong> (જીતેન્દ્રભાઈ પરમાર - CEO)<br>
                       📞 <strong>+91 97146 11331</strong> (જય પરમાર - Co-CEO)</p>`,
                rich: `<div style="margin-top:6px;"><a href="<?= BASE_URL ?>/checkout.php" class="btn btn-primary" style="font-size:11px; display:block; text-align:center; background:#10b981;">💳 ઓનલાઇન બુકિંગ પોર્ટલ</a></div>`
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

        // 20mm Bench Drill
        if (q.includes('bd-20') || q.includes('bd 20') || q.includes('bench') || q.includes('બેન્ચ') || (q.includes('20mm') && !q.includes('radial'))) {
            return {
                text: `<p><strong>SVEW-BD-20 Precision Bench Drill Machine (20mm):</strong></p>
                       <p>• Drilling Capacity: 20mm Steel / 25mm Cast Iron<br>
                          • Spindle Speeds: 4 Speeds (500 to 2800 RPM) • MT-2 Taper<br>
                          • Motor: 0.75 HP (0.55 kW) 415V / 230V High Torque Induction Motor<br>
                          • Factory Ex-Price: <strong>₹ 48,000</strong> (+18% GST)<br>
                          • Precision ground column with smooth depth-stop dial for repetitive tool room batch drilling.</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:6px;">
                            <a href="<?= BASE_URL ?>/checkout.php?product_id=7" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981; font-weight:800; text-align:center;">💳 Book SVEW-BD-20</a>
                            <a href="<?= BASE_URL ?>/machine-detail.php?id=7" class="btn btn-outline" style="font-size:11px; flex:1; text-align:center;">Specs &amp; Photos</a>
                       </div>`
            };
        }

        // 25mm Pillar Drill
        if (q.includes('pd-25') || q.includes('pd 25') || (q.includes('25mm') && !q.includes('tap') && !q.includes('ટેપ'))) {
            return {
                text: `<p><strong>SVEW-PD-25 Heavy Duty Pillar Drill Machine (25mm):</strong></p>
                       <p>• Drilling Capacity: 25mm Steel / 32mm Cast Iron<br>
                          • Spindle Speeds: 8 Speeds (85 to 2400 RPM) • MT-3 Taper<br>
                          • Column Diameter: 92mm solid ground pipe • Weight: Approx 280 kg<br>
                          • Motor: 1.0 HP (0.75 kW) 1440 RPM continuous duty<br>
                          • Factory Ex-Price: <strong>₹ 85,000</strong> (+18% GST)<br>
                          • 360° swiveling &amp; tilting heavy worktable with rack-pinion elevation.</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:6px;">
                            <a href="<?= BASE_URL ?>/checkout.php?product_id=4" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981; font-weight:800; text-align:center;">💳 Book SVEW-PD-25</a>
                            <a href="<?= BASE_URL ?>/machine-detail.php?id=4" class="btn btn-outline" style="font-size:11px; flex:1; text-align:center;">Full Specs</a>
                       </div>`
            };
        }

        // 32mm Geared Column Pillar Drill
        if (q.includes('pd-32') || q.includes('pd 32') || q.includes('32mm')) {
            return {
                text: `<p><strong>SVEW-PD-32 Geared Column Pillar Drill Machine (32mm):</strong></p>
                       <p>• Drilling Capacity: 32mm Steel / 38mm Cast Iron<br>
                          • Spindle Speeds: 8 Speeds (70 to 2100 RPM) • MT-3 Spindle Nose<br>
                          • Column Diameter: 115mm • Weight: Approx 460 kg mass<br>
                          • Motor: 1.5 HP (1.1 kW) Copper Wound Motor<br>
                          • Factory Ex-Price: <strong>₹ 1,35,000</strong> (+18% GST)<br>
                          • Oil-bath gear drive with automatic spindle feeds for fabrication shops.</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:6px;">
                            <a href="<?= BASE_URL ?>/checkout.php?product_id=5" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981; font-weight:800; text-align:center;">💳 Book SVEW-PD-32</a>
                            <a href="<?= BASE_URL ?>/machine-detail.php?id=5" class="btn btn-outline" style="font-size:11px; flex:1; text-align:center;">Full Specs</a>
                       </div>`
            };
        }

        // 40mm Heavy Duty Pillar Drill
        if (q.includes('pd-40') || q.includes('pd 40') || (q.includes('40mm') && q.includes('pillar'))) {
            return {
                text: `<p><strong>SVEW-PD-40 Extra Heavy Upright Pillar Drill (40mm):</strong></p>
                       <p>• Drilling Capacity: 40mm Steel / 45mm Cast Iron • MT-4 Spindle<br>
                          • Spindle Speeds: 8 Speeds (60 to 1800 RPM) • Column: 140mm<br>
                          • Motor: 2.0 HP (1.5 kW) Heavy Duty Motor • Weight: Approx 650 kg<br>
                          • Factory Ex-Price: <strong>₹ 1,95,000</strong> (+18% GST)</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:6px;">
                            <a href="<?= BASE_URL ?>/checkout.php?product_id=6" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981; font-weight:800; text-align:center;">💳 Book SVEW-PD-40</a>
                            <a href="<?= BASE_URL ?>/machine-detail.php?id=6" class="btn btn-outline" style="font-size:11px; flex:1; text-align:center;">Full Specs</a>
                       </div>`
            };
        }

        // 25mm Auto-Reversing Drilling & Tapping Machine
        if (q.includes('tp-25') || q.includes('tp 25') || q.includes('tapping') || q.includes('ટેપિંગ') || q.includes('ટાંકા') || q.includes('આંટા')) {
            return {
                text: `<p><strong>SVEW-TP-25 Auto-Reversing Drilling &amp; Tapping Machine (25mm):</strong></p>
                       <p>• Drilling Capacity: 25mm • Tapping Capacity: M6 to M24 threads<br>
                          • Auto-Reversing Spindle Clutch for instantaneous tap withdrawal<br>
                          • Motor: 1.5 HP dual-direction reversing motor<br>
                          • Factory Ex-Price: <strong>₹ 1,25,000</strong> (+18% GST)<br>
                          • Ideal for valve bodies, flange threading, and high-volume automotive tapping.</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:6px;">
                            <a href="<?= BASE_URL ?>/checkout.php?product_id=8" class="btn btn-primary" style="font-size:11px; flex:1; background:#10b981; font-weight:800; text-align:center;">💳 Book SVEW-TP-25</a>
                            <a href="<?= BASE_URL ?>/machine-detail.php?id=8" class="btn btn-outline" style="font-size:11px; flex:1; text-align:center;">Full Specs</a>
                       </div>`
            };
        }

        // General Pillar Drills Overview
        if (q.includes('pillar') || q.includes('પિલર')) {
            return {
                text: `<p><strong>Varsha Brand Precision Pillar Drill Lineup:</strong></p>
                       <p>• <strong>SVEW-PD-25 (25mm Pillar Drill):</strong> ₹ 85,000<br>
                          • <strong>SVEW-PD-32 (32mm Geared Column Pillar):</strong> ₹ 1,35,000<br>
                          • <strong>SVEW-PD-40 (40mm Extra Heavy Pillar):</strong> ₹ 1,95,000<br>
                          • <strong>SVEW-TP-25 (Auto-Reversing Tapping):</strong> ₹ 1,25,000</p>`,
                rich: `<div style="display:flex; gap:6px; margin-top:6px;">
                            <a href="<?= BASE_URL ?>/machines.php?category=pillar-drills" class="btn btn-outline" style="font-size:11px; flex:1;">View All Pillar Models</a>
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
