/**
 * Tracking Modal Management
 * Pattern: Similar to modal-confirmation
 * - openTrackingModal(deliveryId): Fetch and show modal
 * - closeTrackingModal(): Hide modal
 * - Overlay click to close functionality
 */

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed; top: 20px; right: 20px; z-index: 9999;
        padding: 12px 20px; border-radius: 6px; font-size: 14px;
        animation: slideInRight 0.3s ease;
        ${type === 'error' ? 'background: #f23d3d; color: white;' : type === 'success' ? 'background: #4caf50; color: white;' : 'background: #ffc107; color: black;'}
    `;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

function openTrackingModal(deliveryId) {
    const modal = document.getElementById('trackingModal');
    if (!modal) return;
    
    modal.setAttribute('data-delivery-id', deliveryId);
    fetch(`/api/tracking/${deliveryId}`)
        .then(r => {
            if (!r.ok) {
                throw new Error(`HTTP ${r.status}: ${r.statusText}`);
            }
            return r.json();
        })
        .then(data => {
            if (data.success && data.data) {
                populateTrackingModal(data.data);
                modal.classList.add('active');
            } else {
                showNotification(data.message || 'Gagal memuat tracking', 'error');
            }
        })
        .catch(e => {
            showNotification('Error: ' + e.message, 'error');
            console.error('Tracking load error:', e);
        });
}

function closeTrackingModal() {
    document.getElementById('trackingModal').classList.remove('active');
}

function populateTrackingModal(delivery) {
    if (!delivery) {
        document.getElementById('trackingContent').innerHTML = '<p class="trackingEmptyState">Data tracking tidak tersedia</p>';
        return;
    }
    
    const resiHtml = delivery.tracking_number && delivery.tracking_number !== '-' ? `
        <button class="trackingCopyBtn" onclick="copyToClipboard('${delivery.tracking_number}'); return false;" title="Salin resi">
            <span class="material-symbols-outlined">content_copy</span>
        </button>
    ` : '';
    
    const estimatedHtml = delivery.estimated_delivery ? `
        <div class="trackingEstimated">
            <label>Estimasi Tiba</label>
            <p>${delivery.estimated_delivery}</p>
        </div>
    ` : '';

    const html = `
        <div class="trackingInfoCard">
            <div class="trackingInfoGrid">
                <div class="trackingInfoItem">
                    <label>No Invoice</label>
                    <p>${delivery.invoice_number || '-'}</p>
                </div>
                <div class="trackingInfoItem">
                    <label>Pelanggan</label>
                    <p>${delivery.customer_name || '-'}</p>
                </div>
                <div class="trackingInfoItem">
                    <label>Pesanan</label>
                    <p>${delivery.order_items || '-'}</p>
                </div>
                <div class="trackingInfoItem">
                    <label>Kurir</label>
                    <p>${delivery.courier_company?.toUpperCase() || 'N/A'}</p>
                </div>
            </div>
            <div class="trackingInfoDivider">
                <label>Nomor Resi</label>
                <div class="trackingResiSection">
                    <p class="trackingResiNumber">${delivery.tracking_number || '-'}</p>
                    ${resiHtml}
                </div>
            </div>
        </div>
        ${estimatedHtml}
        <div class="trackingTimelineSection">
            <h4>
                <span class="material-symbols-outlined trackingTimelineIcon">local_shipping</span>
                Riwayat Pengiriman
            </h4>
            <div id="trackingTimeline" class="trackingTimeline"></div>
        </div>
    `;
    
    document.getElementById('trackingContent').innerHTML = html;
    buildTimeline(delivery);
}

function buildTimeline(delivery) {
    const timeline = document.getElementById('trackingTimeline');
    const statuses = delivery.tracking_history || [];
    
    if (!statuses || statuses.length === 0) {
        timeline.innerHTML = '<p class="trackingEmptyState">Tidak ada riwayat pengiriman</p>';
        return;
    }
    
    timeline.innerHTML = statuses.map((status, idx) => {
        const isLast = idx === statuses.length - 1;
        const classes = ['trackingTimelineItem'];
        if (status.completed) classes.push('completed');
        if (status.active) classes.push('active');
        
        const connectorHtml = !isLast ? '<div class="trackingTimelineConnector"></div>' : '';
        
        return `
            <div class="${classes.join(' ')}">
                ${connectorHtml}
                <p class="trackingTimelineText">${status.name || '-'}</p>
                <p class="trackingTimelineTime">${status.date || '-'}</p>
            </div>
        `;
    }).join('');
}

function refreshTrackingStatus() {
    const modal = document.getElementById('trackingModal');
    if (!modal) return;
    
    showNotification('Memperbarui status pengiriman...', 'info');
    const deliveryId = modal.getAttribute('data-delivery-id');
    
    if (!deliveryId) {
        showNotification('ID pengiriman tidak ditemukan', 'error');
        return;
    }
    
    fetch(`/api/tracking/${deliveryId}`)
        .then(r => {
            if (!r.ok) throw new Error(`HTTP ${r.status}`);
            return r.json();
        })
        .then(data => {
            if (data.success && data.data) {
                populateTrackingModal(data.data);
                showNotification('Status telah diperbarui', 'success');
            } else {
                showNotification(data.message || 'Gagal memperbarui status', 'error');
            }
        })
        .catch(e => {
            showNotification('Error memperbarui status: ' + e.message, 'error');
            console.error('Refresh tracking error:', e);
        });
}

function copyToClipboard(text) {
    if (!text) return;
    navigator.clipboard.writeText(text).then(() => {
        showNotification('Nomor resi disalin!', 'success');
    }).catch(() => {
        showNotification('Gagal menyalin resi', 'error');
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('trackingModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) closeTrackingModal();
        });
    }
});

// ====== TEST HELPER (Development Only) ======
// Use in browser console to manually advance delivery status for testing
// Example: testAdvanceDeliveryStatus('requesting_pickup')
function testAdvanceDeliveryStatus(newStatus) {
    const modal = document.getElementById('trackingModal');
    const deliveryId = modal?.getAttribute('data-delivery-id');
    
    if (!deliveryId) {
        console.error('No delivery ID found. Open tracking modal first.');
        return;
    }

    const validStatuses = [
        'requesting_pickup',    // Step 1
        'on_pickup',           // Step 2
        'picked_up',           // Step 2
        'on_delivery',         // Step 3
        'delivered',           // Step 4
    ];

    if (!validStatuses.includes(newStatus)) {
        console.error(`Invalid status. Use one of: ${validStatuses.join(', ')}`);
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }

    fetch(`/admin/delivery/${deliveryId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ status: newStatus }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            console.log(`✓ Delivery status updated to: ${newStatus}`);
            refreshTrackingStatus(); // Refresh modal
        } else {
            console.error('Failed:', data.message);
        }
    })
    .catch(e => console.error('Error:', e.message));
}
