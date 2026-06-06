// ... (Baris awal sampai fungsi scrollToBottom tetap sama) ...
let liveChatSessionId = null;
let liveChatIntervalCheck = null;
let countdownTimer = null;
let timeLeft = 300;
let lastAdminMessageId = 0;

function enableChatUI() {
    document.getElementById("chatbotFooter").style.display = "flex";
    document.getElementById("botDisclaimer").style.display = "none"; // <-- TAMBAHKAN INI (Sembunyikan Disclaimer)
    document.getElementById("chatInput").focus();
    startCountdown();
    clearInterval(liveChatIntervalCheck);
    liveChatIntervalCheck = setInterval(fetchIncomingMessages, 3000);
}

document.addEventListener("DOMContentLoaded", () => {
    const timeNow = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    const savedSessionId = localStorage.getItem('activeChatSessionId');
    if (savedSessionId) {
        liveChatSessionId = savedSessionId;
        enableChatUI();
    }
});

function toggleChatbot() {
    document.getElementById("chatbotBox").classList.toggle("show");
    let botIntroTime = document.getElementById("botIntroTime");
    if (botIntroTime && botIntroTime.innerText === "00:00") {
        let currentTime = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        botIntroTime.innerText = currentTime;
    }
    scrollToBottom();
}

function scrollToBottom() {
    const chatBody = document.getElementById("chatbotBody");
    chatBody.scrollTop = chatBody.scrollHeight;
}

function triggerBotResponse(option) {
    const timeNow = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    const optionsGroup = document.getElementById("initialOptions");
    if (optionsGroup) optionsGroup.remove();

    let userText = "";
    if (option === 'menu') userText = "Mau liat Menu Cireng yang paling laris!";
    if (option === 'stok') userText = "Cek stok cireng hari ini";
    if (option === 'lokasi') userText = "Lokasi Cabang A'paweh";
    if (option === 'mitra') userText = "Cara jadi Mitra A'paweh";
    appendChatBubble(userText, 'user', timeNow);

    setTimeout(() => {
        let botResponseHTML = "";
        if (option === 'menu') botResponseHTML = "Berikut adalah menu cireng terlaris kami yang pasti menggugah selera! Silakan cek di halaman beranda.";
        else if (option === 'stok') botResponseHTML = "Stok hari ini aman terkendali! Langsung checkout aja sebelum kehabisan.";
        else if (option === 'lokasi') botResponseHTML = "Pusat kami ada di Gedebage, Bandung. Cek map di beranda ya.";
        else if (option === 'mitra') botResponseHTML = "Wah mau jadi mitra? Gampang banget, modal mulai ratusan ribu aja. Hubungi WA kami ya.";

        appendBotResponseAndReset(botResponseHTML, timeNow);
    }, 600);
}

// PERUBAHAN: Tambah .caption & .charcoalGrey ke generated bubble
function appendChatBubble(text, sender, time) {
    const chatBody = document.getElementById("chatbotBody");
    const row = document.createElement("div");
    row.className = `chatRow ${sender}`;

    let html = '';
    if (sender === 'bot') {
        html += `<img src="/assets/icon/cipa/chatbotChat.svg" alt="Cipa" class="botAvatar">`;
        html += `<div class="chatBubble bot caption charcoalGrey">${text} <span class="chatTime">${time}</span></div>`;
    } else {
        html += `<div class="chatBubble user caption charcoalGrey">${text} <span class="chatTime">${time}</span></div>`;
    }
    row.innerHTML = html;
    chatBody.appendChild(row);
    scrollToBottom();
}

function appendBotResponseAndReset(responseText, timeNow) {
    appendChatBubble(responseText, 'bot', timeNow);
    document.getElementById("chatConfirmPanel").style.display = "block";
    scrollToBottom();
}

// PERUBAHAN: Tambah .caption ke generated buttons
function handleConfirm(choice) {
    document.getElementById("chatConfirmPanel").style.display = "none";
    const timeNow = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

    if (choice === 'ya') {
        const chatBody = document.getElementById("chatbotBody");
        const newOptions = document.createElement("div");
        newOptions.className = "chatbotOptions";
        newOptions.id = "initialOptions";
        newOptions.innerHTML = `
            <button class="caption charcoalGrey" onclick="triggerBotResponse('menu')">Mau liat Menu Cireng yang paling laris!</button>
            <button class="caption charcoalGrey" onclick="triggerBotResponse('stok')">Cek stok cireng hari ini</button>
            <button class="caption charcoalGrey" onclick="triggerBotResponse('lokasi')">Lokasi Cabang A'paweh</button>
            <button class="caption charcoalGrey" onclick="triggerBotResponse('mitra')">Cara jadi Mitra A'paweh</button>
            <button class="caption highlight" onclick="initiateLiveChat()"><img class="imgCs" src="/assets/icon/cipa/cs.png" alt="CS"> Chat dengan Admin</button>
        `;
        chatBody.appendChild(newOptions);
        scrollToBottom();
    } else {
        appendChatBubble("Baik, terima kasih. Semoga harimu menyenangkan!", 'bot', timeNow);
    }
}

function initiateLiveChat() {
    const chatBody = document.getElementById("chatbotBody");
    chatBody.innerHTML = '';
    document.getElementById("chatConfirmPanel").style.display = "none";

    const notice = document.createElement("div");
    // Gunakan class dari global CSS
    notice.className = "caption primaryBrandRed textCenter fw-semibold my-small";
    notice.innerText = "Anda Terhubung Admin";
    chatBody.appendChild(notice);

    fetch('/chat-api/create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            liveChatSessionId = data.session_id;
            localStorage.setItem('activeChatSessionId', liveChatSessionId);
            enableChatUI();
        }
    });
}

function handleEnterKey(e) { if (e.key === 'Enter') sendUserLiveMessage(); }

function sendUserLiveMessage() {
    const chatInput = document.getElementById("chatInput");
    const messageText = chatInput.value.trim();
    const timeNow = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

    if (messageText === "") return;
    appendChatBubble(messageText, 'user', timeNow);
    chatInput.value = "";
    resetCountdown();
    updateTimerUIPosition();

    fetch(`/chat-api/${liveChatSessionId}/send`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' },
        body: JSON.stringify({ message: messageText, sender: 'customer' })
    });
}

function fetchIncomingMessages() {
    if (!liveChatSessionId) return;

    fetch(`/chat-api/${liveChatSessionId}/messages?last_id=${lastAdminMessageId}&t=${new Date().getTime()}`)
        .then(res => res.json())
        .then(data => {
            if (data.session_status === 'closed') {
                endChatSession("Sesi obrolan telah ditutup oleh Admin.");
                return;
            }
            if (data.new_messages && data.new_messages.length > 0) {
                data.new_messages.forEach(msg => {
                    if (msg.id > lastAdminMessageId) {
                        lastAdminMessageId = parseInt(msg.id);
                        if (msg.sender_type === 'admin') {
                            const msgTime = msg.created_at ? new Date(msg.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) : '00:00';
                            appendChatBubble(msg.message, 'bot', msgTime);
                        }
                    }
                });
            }
        });
}

function startCountdown() {
    clearInterval(countdownTimer);
    timeLeft = 300;
    countdownTimer = setInterval(() => {
        timeLeft--;
        if (timeLeft <= 0) {
            endChatSession("Sesi berakhir (5 Menit tidak ada interaksi).");
        } else {
            const timerSpan = document.getElementById("liveTimerText");
            if (timerSpan) {
                let m = Math.floor(timeLeft / 60).toString().padStart(2, '0');
                let s = (timeLeft % 60).toString().padStart(2, '0');
                timerSpan.innerText = `${m}:${s}`;
            }
        }
    }, 1000);
}

function resetCountdown() { timeLeft = 300; }

function updateTimerUIPosition() {
    const oldTimer = document.getElementById("activeTimerWrap");
    if (oldTimer) oldTimer.remove();
    const chatBody = document.getElementById("chatbotBody");
    const timerDiv = document.createElement("div");
    timerDiv.className = "chatTimerWrap fdnGreyDark";
    timerDiv.id = "activeTimerWrap";
    timerDiv.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg> Chat berakhir dalam <span id="liveTimerText">05:00</span>`;
    chatBody.appendChild(timerDiv);
    scrollToBottom();
}

function endChatSession(reason) {
    clearInterval(countdownTimer);
    clearInterval(liveChatIntervalCheck);
    const chatBody = document.getElementById("chatbotBody");

    document.getElementById("chatbotFooter").style.display = "none";
    document.getElementById("chatConfirmPanel").style.display = "none";

    const notice = document.createElement("div");
    // Gunakan class dari global CSS
    notice.className = "caption primaryBrandRed textCenter fw-semibold my-small";
    notice.innerText = reason;
    chatBody.appendChild(notice);
    scrollToBottom();

    if (liveChatSessionId) {
        fetch(`/chat-api/${liveChatSessionId}/close`, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } });
    }

    localStorage.removeItem('activeChatSessionId');
    liveChatSessionId = null;
    lastAdminMessageId = 0;

    // Reset view
    setTimeout(() => {
        const timeNow = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        document.getElementById("botDisclaimer").style.display = "flex"; // <-- TAMBAHKAN INI (Munculkan Disclaimer lagi)
        chatBody.innerHTML = `
            <div class="chatDate">Hari Ini</div>
            <div class="chatRow bot">
                <img src="/assets/icon/cipa/chatbotChat.svg" alt="Cipa" class="botAvatar">
                <div class="chatBubble bot caption charcoalGrey">
                    Halow balow, selamat datang. Saya Cipa, asisten otomatis Cireng A'paweh yang siap membantu kamu. Apa yang bisa saya bantu?
                    <span class="chatTime" id="botIntroTime">${timeNow}</span>
                </div>
            </div>
            <div class="chatbotOptions" id="initialOptions">
                <button class="caption charcoalGrey" onclick="triggerBotResponse('menu')">Mau liat Menu Cireng yang paling laris!</button>
                <button class="caption charcoalGrey" onclick="triggerBotResponse('stok')">Cek stok cireng hari ini</button>
                <button class="caption charcoalGrey" onclick="triggerBotResponse('lokasi')">Lokasi Cabang A'paweh</button>
                <button class="caption charcoalGrey" onclick="triggerBotResponse('mitra')">Cara jadi Mitra A'paweh</button>
                <button class="caption highlight" onclick="initiateLiveChat()"><img class="imgCs" src="/assets/icon/cipa/cs.png" alt="CS"> Chat dengan Admin</button>
            </div>
        `;
        scrollToBottom();
    }, 3500);
}
