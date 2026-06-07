let allSessions = [];
let currentFilter = 'all';
let activeSessionId = null;
let activeSessionStatus = 'open';
let sessionPollInterval = null;
let messagePollInterval = null;

document.addEventListener("DOMContentLoaded", () => {
    fetchSessions();
    sessionPollInterval = setInterval(fetchSessions, 3000);
});

function filterSessions(filter, btnElement) {
    currentFilter = filter;
    document.querySelectorAll('.chatTab').forEach(el => el.classList.remove('active'));
    btnElement.classList.add('active');
    renderSessions();
}

function fetchSessions() {
    fetch('/admin/chat-sync/sessions', {
        headers: {
            'Accept': 'application/json'
        }
    })
        .then(async res => {
            if (!res.ok) {
                const errText = await res.text();
                throw new Error("HTTP " + res.status + " - " + errText);
            }
            return res.json();
        })
        .then(data => {
            allSessions = data;
            renderSessions();
        })
        .catch(err => {
            document.getElementById('sessionListAdmin').innerHTML =
                `<div style="color:red; font-size:12px; padding:15px; background:#ffebeb; border-radius:8px;"><b>GAGAL MEMUAT DATA:</b><br>${err.message.substring(0, 100)}...</div>`;
        });
}

function renderSessions() {
    const listContainer = document.getElementById("sessionListAdmin");
    listContainer.innerHTML = '';

    let filtered = allSessions;
    if (currentFilter === 'open') filtered = allSessions.filter(s => s.status === 'open');
    if (currentFilter === 'closed') filtered = allSessions.filter(s => s.status === 'closed');

    if (filtered.length === 0) {
        listContainer.innerHTML =
            `<p style="text-align: center; margin-top: 20px; color: #888; font-size: 14px;">Tidak ada obrolan.</p>`;
        return;
    }

    filtered.forEach(session => {
        const time = new Date(session.updated_at).toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });
        const isActive = session.id === activeSessionId ? 'active' : '';
        const badgeClass = session.status === 'open' ? 'open' : 'closed';
        const badgeText = session.status === 'open' ? 'Dibuka' : 'Ditutup';
        // customer name dinamis
        const custName = session.customer_name ? session.customer_name : `User #${session.id}`;
        const safeName = custName.replace(/'/g, "\\'");
        const html = `
                <div class="sessionItem ${isActive}" onclick="openChatRoom(${session.id}, '${session.status}', '${safeName}')">
                    <div class="sessionItemTop">
                        <span class="sessionName">${custName}</span>
                        <span class="sessionTime">${time}</span>
                    </div>
                    <div class="statusBadge ${badgeClass}">${badgeText}</div>
                </div>
            `;
        listContainer.insertAdjacentHTML('beforeend', html);
    });
}

function openChatRoom(id, status, name) {
    activeSessionId = id;
    activeSessionStatus = status;
    document.getElementById("chatEmptyState").style.display = "none";
    document.getElementById("chatRoomArea").style.display = "flex";
    document.getElementById("activeRoomTitle").innerText = name;
    if (status === 'closed') {
        document.getElementById("roomFooterAdmin").style.display = "none";
        document.getElementById("closedNoticeAdmin").style.display = "block";
        document.getElementById("btnCloseSession").style.display = "none";
    } else {
        document.getElementById("roomFooterAdmin").style.display = "flex";
        document.getElementById("closedNoticeAdmin").style.display = "none";
        document.getElementById("btnCloseSession").style.display = "block";
    }
    renderSessions();
    fetchMessages();
    clearInterval(messagePollInterval);
    messagePollInterval = setInterval(fetchMessages, 2000);
}

function fetchMessages() {
    if (!activeSessionId) return;
    fetch(`/admin/chat-sync/${activeSessionId}/messages`)
        .then(res => res.json())
        .then(messages => {
            const msgContainer = document.getElementById("roomMessagesAdmin");
            // scroll plg bawah?
            const isScrolledToBottom = msgContainer.scrollHeight - msgContainer.clientHeight <= msgContainer
                .scrollTop + 50;
            msgContainer.innerHTML = '';
            messages.forEach(msg => {
                const time = new Date(msg.created_at).toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                const alignClass = msg.sender_type === 'admin' ? 'admin' : 'customer';

                msgContainer.insertAdjacentHTML('beforeend', `
                    <div class="msgRow ${alignClass}">
                        <div class="msgBubble">${msg.message}</div>
                        <div class="msgTime">${time}</div>
                    </div>
                `);
            });
            if (isScrolledToBottom) {
                msgContainer.scrollTop = msgContainer.scrollHeight;
            }
        });
}

function handleAdminEnter(e) {
    if (e.key === 'Enter') sendAdminMessage();
}

function sendAdminMessage() {
    const input = document.getElementById("adminChatInput");
    const text = input.value.trim();
    if (!text || !activeSessionId || activeSessionStatus === 'closed') return;
    input.value = "";
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const token = csrfMeta ? csrfMeta.getAttribute('content') : '';
    fetch(`/admin/chat-sync/${activeSessionId}/send`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({
            message: text
        })
    })
        .then(() => fetchMessages());
}

function forceCloseSession() {
    if (!confirm('Yakin ingin menutup sesi obrolan ini? Kustomer tidak akan bisa membalas lagi.')) return;
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const token = csrfMeta ? csrfMeta.getAttribute('content') : '';
    fetch(`/chat-api/${activeSessionId}/close`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token
        }
    })
        .then(() => {
            activeSessionStatus = 'closed';
            openChatRoom(activeSessionId, 'closed', document.getElementById("activeRoomTitle").innerText);
            fetchSessions();
        });
}