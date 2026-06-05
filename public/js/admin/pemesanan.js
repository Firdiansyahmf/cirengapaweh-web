let currentOrderId = '';
let currentActionType = '';

// Open confirmation modal
function openConfirmModal(actionType, orderId) {
    currentOrderId = orderId;
    currentActionType = actionType;

    if (actionType === 'proses') {
        document.getElementById('promoOrderId').textContent = orderId;
        document.getElementById('confirmProsesPesananModal').classList.add('active');
    } else if (actionType === 'batalkan') {
        document.getElementById('batalkanOrderId').textContent = orderId;
        document.getElementById('confirmBatalkanPesananModal').classList.add('active');
    } else if (actionType === 'kirim') {
        document.getElementById('kirimOrderId').textContent = orderId;
        document.getElementById('confirmKirimPesananModal').classList.add('active');
    }
}

// Close confirmation modal
function closeConfirmModal(actionType) {
    if (actionType === 'proses') {
        document.getElementById('confirmProsesPesananModal').classList.remove('active');
    } else if (actionType === 'batalkan') {
        document.getElementById('confirmBatalkanPesananModal').classList.remove('active');
    } else if (actionType === 'kirim') {
        document.getElementById('confirmKirimPesananModal').classList.remove('active');
    }
}

// Confirm actions
function confirmProsesPesanan() {
    closeConfirmModal('proses');
    alert('Pesanan ' + currentOrderId + ' berhasil diproses');
}

function confirmBatalkanPesanan() {
    closeConfirmModal('batalkan');
    alert('Pesanan ' + currentOrderId + ' berhasil dibatalkan');
}

function confirmKirimPesanan() {
    closeConfirmModal('kirim');
    alert('Pesanan ' + currentOrderId + ' berhasil dikirim ke Biteship API');
}

// Invoice modal functions
function openInvoiceModal(orderId) {
    document.getElementById('invoiceOrderId').textContent = orderId;
    document.getElementById('invoiceDetailModal').classList.add('active');
}

function closeInvoiceModal() {
    document.getElementById('invoiceDetailModal').classList.remove('active');
}

// Close modal when clicking overlay
document.addEventListener('click', function(event) {
    const modals = document.querySelectorAll('.modalOverlay');
    modals.forEach(modal => {
        if (event.target === modal) {
            modal.classList.remove('active');
        }
    });
});