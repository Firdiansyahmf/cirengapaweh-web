/**
 * Chat Management - Admin Chat System
 * Handles session selection, messaging, and session management
 */

let currentSessionId = null;
let currentSessionStatus = 'open';

/**
 * Select a chat session to view
 */
function selectSession(sessionId) {
    currentSessionId = sessionId;
    
    // Update active state in list
    document.querySelectorAll('.sessionItem').forEach(item => {
        item.classList.remove('active');
    });
    document.querySelector(`.sessionItem[data-session-id="${sessionId}"]`).classList.add('active');

    // Show chat content
    document.getElementById('chatEmpty').style.display = 'none';
    document.getElementById('chatContent').style.display = 'flex';

    // Load session details and messages
    loadSessionDetails(sessionId);
}

/**
 * Load session details and messages from server
 */
function loadSessionDetails(sessionId) {
    fetch(`/admin/chat/${sessionId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const session = data.session;
            const messages = data.messages;

            // Update header
            document.getElementById('chatSessionName').textContent = `Session #${session.id}`;
            document.getElementById('chatSessionPhone').textContent = session.customer_phone || '-';

            // Update status badge
            currentSessionStatus = session.status;
            const badge = document.getElementById('sessionStatusBadge');
            if (session.status === 'open') {
                badge.textContent = 'Dibuka';
                badge.className = 'statusBadge statusOpen';
                document.getElementById('toggleText').textContent = 'Tutup Session';
                document.getElementById('toggleIcon').textContent = 'lock_open';
            } else {
                badge.textContent = 'Ditutup';
                badge.className = 'statusBadge statusClosed';
                document.getElementById('toggleText').textContent = 'Buka Session';
                document.getElementById('toggleIcon').textContent = 'lock';
            }

            // Load messages
            loadMessages(messages);
        }
    })
    .catch(error => {
        console.error('Error loading session:', error);
        alert('Gagal memuat session chat');
    });
}

/**
 * Load and display messages
 */
function loadMessages(messages) {
    const messagesArea = document.getElementById('messagesArea');
    messagesArea.innerHTML = '';

    if (messages.length === 0) {
        messagesArea.innerHTML = `
            <div style="flex: 1; display: flex; align-items: center; justify-content: center; color: #999;">
                <p>Belum ada pesan dalam session ini</p>
            </div>
        `;
        return;
    }

    messages.forEach(msg => {
        const messageGroup = document.createElement('div');
        messageGroup.className = `messageGroup ${msg.sender_type === 'admin' ? 'admin' : 'customer'}`;

        const messageEl = document.createElement('div');
        messageEl.className = 'message';
        messageEl.textContent = msg.message;

        const timeEl = document.createElement('div');
        timeEl.className = 'messageTime';
        const date = new Date(msg.created_at);
        timeEl.textContent = date.toLocaleTimeString('id-ID', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });

        messageGroup.appendChild(messageEl);
        messageGroup.appendChild(timeEl);
        messagesArea.appendChild(messageGroup);
    });

    // Scroll to bottom
    messagesArea.scrollTop = messagesArea.scrollHeight;
}

/**
 * Send message in current session
 */
function sendMessage(event) {
    event.preventDefault();

    if (!currentSessionId) {
        alert('Pilih session terlebih dahulu');
        return;
    }

    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();

    if (!message) {
        return;
    }

    // Disable button
    const btnSend = document.getElementById('btnSend');
    btnSend.disabled = true;

    fetch(`/admin/chat/${currentSessionId}/send`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageInput.value = '';
            messageInput.style.height = 'auto';
            // Reload messages to show the new one
            loadSessionDetails(currentSessionId);
        } else {
            alert(data.message || 'Gagal mengirim pesan');
        }
    })
    .catch(error => {
        console.error('Error sending message:', error);
        alert('Terjadi kesalahan saat mengirim pesan');
    })
    .finally(() => {
        btnSend.disabled = false;
    });
}

/**
 * Toggle session status (open/close)
 */
function toggleSessionStatus() {
    if (!currentSessionId) return;

    const newStatus = currentSessionStatus === 'open' ? 'closed' : 'open';
    const endpoint = currentSessionStatus === 'open' ? 'close' : 'reopen';

    fetch(`/admin/chat/${currentSessionId}/${endpoint}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentSessionStatus = newStatus;
            
            // Update UI
            const badge = document.getElementById('sessionStatusBadge');
            if (newStatus === 'open') {
                badge.textContent = 'Dibuka';
                badge.className = 'statusBadge statusOpen';
                document.getElementById('toggleText').textContent = 'Tutup Session';
                document.getElementById('toggleIcon').textContent = 'lock_open';
            } else {
                badge.textContent = 'Ditutup';
                badge.className = 'statusBadge statusClosed';
                document.getElementById('toggleText').textContent = 'Buka Session';
                document.getElementById('toggleIcon').textContent = 'lock';
            }

            // Update session list item
            const sessionItem = document.querySelector(`.sessionItem[data-session-id="${currentSessionId}"]`);
            if (sessionItem) {
                sessionItem.setAttribute('data-status', newStatus);
                const statusBadge = sessionItem.querySelector('.statusBadge');
                if (newStatus === 'open') {
                    statusBadge.textContent = 'Dibuka';
                    statusBadge.className = 'statusBadge statusOpen';
                } else {
                    statusBadge.textContent = 'Ditutup';
                    statusBadge.className = 'statusBadge statusClosed';
                }
            }
        }
    })
    .catch(error => {
        console.error('Error toggling session:', error);
    });
}

/**
 * Search sessions
 */
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchSession');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const sessionItems = document.querySelectorAll('.sessionItem');

            sessionItems.forEach(item => {
                const userName = item.querySelector('.userName').textContent.toLowerCase();
                const phone = item.querySelector('.sessionPhone').textContent.toLowerCase();

                if (userName.includes(searchTerm) || phone.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // Tab filtering
    const tabBtns = document.querySelectorAll('.tabBtn');
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const status = this.getAttribute('data-status');
            
            // Update active tab
            tabBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Filter sessions
            const sessionItems = document.querySelectorAll('.sessionItem');
            sessionItems.forEach(item => {
                const itemStatus = item.getAttribute('data-status');
                
                if (status === 'all' || itemStatus === status) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Auto-resize textarea
    const messageInput = document.getElementById('messageInput');
    if (messageInput) {
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        });
    }

    // Close action menu when clicking outside
    document.addEventListener('click', function(e) {
        const actionMenu = document.getElementById('actionMenu');
        if (actionMenu && !e.target.closest('.btnIcon') && !e.target.closest('.actionMenu')) {
            actionMenu.style.display = 'none';
        }
    });
});
