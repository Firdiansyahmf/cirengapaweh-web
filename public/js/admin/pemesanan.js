let currentOrderId = '';
let currentActionType = '';

// Open confirmation modal
function openConfirmModal(actionType, orderId, invoiceNumber) {
    currentOrderId = orderId;
    currentActionType = actionType;

    if (actionType === 'proses') {
        document.getElementById('promoOrderId').textContent = invoiceNumber;
        document.getElementById('confirmProsesPesananModal').classList.add('active');
    } else if (actionType === 'batalkan') {
        document.getElementById('batalkanOrderId').textContent = invoiceNumber;
        document.getElementById('confirmBatalkanPesananModal').classList.add('active');
    } else if (actionType === 'kirim') {
        document.getElementById('kirimOrderId').textContent = invoiceNumber;
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

// Confirm Proses Pesanan - Move from paid to shipping with Biteship
function confirmProsesPesanan() {
    closeConfirmModal('proses');
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) {
        showNotification('CSRF token tidak ditemukan', 'error');
        console.error('CSRF token missing');
        return;
    }

    fetch(`/admin/pemesanan/${currentOrderId}/process`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify({}),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification('Pesanan berhasil diproses dan dikirim ke Biteship', 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showNotification(data.message || 'Gagal memproses pesanan', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat memproses pesanan: ' + error.message, 'error');
    });
}

// Confirm Batalkan Pesanan - Cancel order
function confirmBatalkanPesanan() {
    closeConfirmModal('batalkan');
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) {
        showNotification('CSRF token tidak ditemukan', 'error');
        console.error('CSRF token missing');
        return;
    }

    fetch(`/admin/pemesanan/${currentOrderId}/cancel`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify({}),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification('Pesanan berhasil dibatalkan', 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showNotification(data.message || 'Gagal membatalkan pesanan', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat membatalkan pesanan: ' + error.message, 'error');
    });
}

// Confirm Kirim Pesanan - Same as proses (move to shipping)
function confirmKirimPesanan() {
    closeConfirmModal('kirim');
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) {
        showNotification('CSRF token tidak ditemukan', 'error');
        console.error('CSRF token missing');
        return;
    }

    fetch(`/admin/pemesanan/${currentOrderId}/process`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify({}),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification('Pesanan berhasil dikirim melalui Biteship API', 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showNotification(data.message || 'Gagal mengirim pesanan', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat mengirim pesanan: ' + error.message, 'error');
    });
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px 20px;
        background-color: ${type === 'success' ? 'var(--primary-brand-red)' : type === 'error' ? '#dc3545' : '#0d6efd'};
        color: white;
        border-radius: 8px;
        z-index: 9999;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        font-size: 14px;
        max-width: 400px;
        word-wrap: break-word;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Invoice modal functions
function openInvoiceModal(orderId, invoiceNumber) {
    // Fetch order data
    fetch(`/orders/${orderId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            populateInvoiceModal(data.data);
            document.getElementById('invoiceDetailModal').classList.add('active');
        } else {
            showNotification('Gagal memuat detail pesanan', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat memuat invoice', 'error');
    });
}

function populateInvoiceModal(order) {
    // Set basic info
    document.getElementById('invoiceOrderId').textContent = order.invoice_number;
    document.getElementById('invoiceOrderDate').textContent = new Date(order.created_at).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

    // Set customer info
    document.getElementById('invoiceCustomerName').textContent = order.customer_name;
    document.getElementById('invoiceCustomerPhone').textContent = order.customer_phone;
    document.getElementById('invoiceCustomerAddress').textContent = order.shipping_address;

    // Set items
    const itemsHtml = order.items.map(item => `
        <tr style="border-bottom: 1px solid var(--fdn-grey-light-hover);">
            <td style="padding: 12px 0; font-size: var(--fs-caption); color: var(--charcoal-grey);">${item.product.name}</td>
            <td style="text-align: center; padding: 12px 0; font-size: var(--fs-caption); color: var(--charcoal-grey);">${item.quantity}</td>
            <td style="text-align: right; padding: 12px 0; font-size: var(--fs-caption); color: var(--charcoal-grey);">Rp ${(item.unit_price).toLocaleString('id-ID')}</td>
        </tr>
    `).join('');
    document.getElementById('invoiceItems').innerHTML = itemsHtml;

    // Set amounts
    document.getElementById('invoiceSubtotal').textContent = `Rp ${order.subtotal_amount.toLocaleString('id-ID')}`;
    document.getElementById('invoiceShipping').textContent = `Rp ${order.shipping_cost.toLocaleString('id-ID')}`;
    document.getElementById('invoiceTotal').textContent = `Rp ${order.total_amount.toLocaleString('id-ID')}`;

    // Set status
    const paymentStatus = order.payment?.status === 'settlement' ? 'Lunas' : 'Menunggu Pembayaran';
    const paymentClass = order.payment?.status === 'settlement' ? 'badgePaymentCompleted' : 'badgePaymentPending';
    document.getElementById('invoicePaymentStatus').innerHTML = `<span class="badge badgePaymentStatus ${paymentClass}">${paymentStatus}</span>`;

    const shippingStatusMap = {
        'on_delivery': { text: 'Dalam Perjalanan', class: 'badgeShippingDalam' },
        'picked_up': { text: 'Diambil Kurir', class: 'badgeShippingDalam' },
        'delivered': { text: 'Sampai', class: 'badgeShippingSelesai' },
        'preparing': { text: 'Persiapan', class: 'badgeShippingDalam' },
    };
    const shippingStatus = shippingStatusMap[order.delivery?.status] || { text: '-', class: 'badgeShippingSampai' };
    document.getElementById('invoiceShippingStatus').innerHTML = `<span class="badge badgeShippingStatus ${shippingStatus.class}">${shippingStatus.text}</span>`;
}

function closeInvoiceModal() {
    document.getElementById('invoiceDetailModal').classList.remove('active');
}

// Tab switching functionality
document.querySelectorAll('.tabButton').forEach(button => {
    button.addEventListener('click', function() {
        const tabId = this.getAttribute('data-tab');
        
        // Remove active class from all buttons and contents
        document.querySelectorAll('.tabButton').forEach(btn => btn.classList.remove('tabButton-active'));
        document.querySelectorAll('.tabContent').forEach(content => content.classList.remove('tabContent-active'));
        
        // Add active class to clicked button and corresponding content
        this.classList.add('tabButton-active');
        document.getElementById(tabId).classList.add('tabContent-active');
    });
});

// Close modals on overlay click
document.querySelectorAll('.modalOverlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) {
            const modalId = this.id;
            if (modalId === 'confirmProsesPesananModal') closeConfirmModal('proses');
            else if (modalId === 'confirmBatalkanPesananModal') closeConfirmModal('batalkan');
            else if (modalId === 'confirmKirimPesananModal') closeConfirmModal('kirim');
            else if (modalId === 'invoiceDetailModal') closeInvoiceModal();
        }
    });
});