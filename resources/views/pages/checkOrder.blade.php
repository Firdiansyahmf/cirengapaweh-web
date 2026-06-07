@extends("layouts.app")

@section("title", "Cek Pesanan - Cireng A'paweh")

@push("styles")
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/page/checkOrder.css') }}">
@endpush

@section("content")

    <div class="checkOrder">
        <div class="breakpoint">

            <div class="box1">
                <div class="header">
                    <span class="displayH1 primaryBrandRed">Cek Orderan Kamu Dengan Mudah</span>
                    <span class="subH4 charcoalGrey">Lihat detail orderan kamu menggunakan nomor invoice.</span>
                </div>
                <div class="card">
                    <span class="bodyMain charcoalGrey">Masukkan nomor invoice orderan kamu di sini</span>
                    <input type="text" id="no_invoice" class="inputInvoice bodyMain" placeholder="No. Invoice">
                    <button class="btnPrimary" id="btn_search">Cari Orderan</button>
                </div>
            </div>

        </div> {{-- end breakpoint --}}
    </div> {{-- end cekOrder --}}

    <!-- ====== INVOICE DETAIL MODAL ====== -->
    <div id="invoiceDetailModal" class="modalOverlay">
        <div class="modalDialog">
            <div class="modalHeader">
                <h3>Detail Pesanan & Invoice</h3>
                <button class="modalClose" onclick="closeInvoiceModal()">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="modalBody invoiceBody">
                <div class="invoiceContainer">
                    <!-- Header -->
                    <div style="text-align: center; margin-bottom: 24px; border-bottom: 2px solid var(--fdn-grey-light); padding-bottom: 16px;">
                        <h2 style="font-size: var(--fs-h3); color: var(--fdn-grey-darker); margin-bottom: 4px;">CIRENG A'PAWEH</h2>
                        <p style="font-size: var(--fs-caption); color: var(--charcoal-grey); margin: 0;">Jln. Merdeka No. 123, Bandung | 08123456789</p>
                    </div>

                    <!-- Order Info -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                        <div>
                            <p style="font-size: 12px; color: var(--fdn-white-dark-hover); margin-bottom: 4px;">NO. PESANAN</p>
                            <p id="invoiceOrderId" style="font-size: var(--fs-body-main); font-weight: var(--fw-semibold); color: var(--fdn-grey-darker); margin: 0;">ORD-20230501-001</p>
                        </div>
                        <div>
                            <p style="font-size: 12px; color: var(--fdn-white-dark-hover); margin-bottom: 4px;">TANGGAL PESANAN</p>
                            <p id="invoiceOrderDate" style="font-size: var(--fs-body-main); font-weight: var(--fw-semibold); color: var(--fdn-grey-darker); margin: 0;">12 Okt 2023, 10:30</p>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div style="background-color: var(--fdn-grey-light); padding: 16px; border-radius: 8px; margin-bottom: 24px;">
                        <p style="font-size: 12px; color: var(--fdn-white-dark-hover); margin-bottom: 8px;">INFORMASI PELANGGAN</p>
                        <p id="invoiceCustomerName" style="font-size: var(--fs-body-main); font-weight: var(--fw-semibold); color: var(--fdn-grey-darker); margin: 0 0 8px 0;">Andi Wijaya</p>
                        <p id="invoiceCustomerPhone" style="font-size: var(--fs-caption); color: var(--charcoal-grey); margin: 0;">+62 8123456789</p>
                        <p id="invoiceCustomerAddress" style="font-size: var(--fs-caption); color: var(--charcoal-grey); margin: 0;">Jln. Sudirman No. 45, Bandung, Jawa Barat</p>
                    </div>

                    <!-- Items -->
                    <div style="margin-bottom: 24px;">
                        <p style="font-size: 12px; color: var(--fdn-white-dark-hover); margin-bottom: 12px; font-weight: var(--fw-semibold);">DETAIL PESANAN</p>
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="border-bottom: 2px solid var(--fdn-grey-light);">
                                    <th style="text-align: left; padding: 8px 0; font-size: 12px; font-weight: var(--fw-semibold); color: var(--fdn-grey-dark);">Produk</th>
                                    <th style="text-align: center; padding: 8px 0; font-size: 12px; font-weight: var(--fw-semibold); color: var(--fdn-grey-dark);">Qty</th>
                                    <th style="text-align: right; padding: 8px 0; font-size: 12px; font-weight: var(--fw-semibold); color: var(--fdn-grey-dark);">Harga</th>
                                </tr>
                            </thead>
                            <tbody id="invoiceItems">
                                <tr style="border-bottom: 1px solid var(--fdn-grey-light-hover);">
                                    <td style="padding: 12px 0; font-size: var(--fs-caption); color: var(--charcoal-grey);">Cireng Rujak</td>
                                    <td style="text-align: center; padding: 12px 0; font-size: var(--fs-caption); color: var(--charcoal-grey);">3</td>
                                    <td style="text-align: right; padding: 12px 0; font-size: var(--fs-caption); color: var(--charcoal-grey);">Rp 30.000</td>
                                </tr>
                                <tr style="border-bottom: 1px solid var(--fdn-grey-light-hover);">
                                    <td style="padding: 12px 0; font-size: var(--fs-caption); color: var(--charcoal-grey);">Es Teh</td>
                                    <td style="text-align: center; padding: 12px 0; font-size: var(--fs-caption); color: var(--charcoal-grey);">1</td>
                                    <td style="text-align: right; padding: 12px 0; font-size: var(--fs-caption); color: var(--charcoal-grey);">Rp 5.000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary -->
                    <div style="background-color: var(--fdn-yellow-light); padding: 16px; border-radius: 8px; margin-bottom: 24px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="font-size: var(--fs-caption); color: var(--charcoal-grey);">Subtotal</span>
                            <span id="invoiceSubtotal" style="font-size: var(--fs-caption); font-weight: var(--fw-semibold); color: var(--fdn-grey-darker);">Rp 35.000</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="font-size: var(--fs-caption); color: var(--charcoal-grey);">Ongkir</span>
                            <span id="invoiceShipping" style="font-size: var(--fs-caption); font-weight: var(--fw-semibold); color: var(--fdn-grey-darker);">Rp 10.000</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-top: 2px solid var(--fdn-yellow-normal); padding-top: 8px;">
                            <span style="font-size: var(--fs-body-main); font-weight: var(--fw-semibold); color: var(--fdn-grey-darker);">Total</span>
                            <span id="invoiceTotal" style="font-size: var(--fs-body-main); font-weight: var(--fw-semibold); color: var(--fdn-red-normal);">Rp 45.000</span>
                        </div>
                    </div>

                    <!-- Status -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 24px;">
                        <div>
                            <p style="font-size: 12px; color: var(--fdn-white-dark-hover); margin-bottom: 4px;">STATUS PEMBAYARAN</p>
                            <span id="invoicePaymentStatus" class="badge badgePaymentStatus badgePaymentCompleted">Lunas</span>
                        </div>
                        <div>
                            <p style="font-size: 12px; color: var(--fdn-white-dark-hover); margin-bottom: 4px;">STATUS PENGIRIMAN</p>
                            <span id="invoiceShippingStatus" class="badge badgeShippingStatus badgeShippingDalam">Dalam Perjalanan</span>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div style="background-color: var(--fdn-grey-light); padding: 12px; border-radius: 8px; margin-bottom: 24px;">
                        <p style="font-size: 12px; color: var(--fdn-white-dark-hover); margin-bottom: 4px;">CATATAN</p>
                        <p id="invoiceNotes" style="font-size: var(--fs-caption); color: var(--charcoal-grey); margin: 0;">-</p>
                    </div>
                </div>
            </div>
            <div class="modalFooter">
                <button class="btnCancel" onclick="closeInvoiceModal()" style="flex: 1;">Tutup</button>
            </div>
        </div>
    </div>

@push("scripts")
    <script>
        function searchOrder() {
            const invoiceInput = document.getElementById('no_invoice');
            const invoiceNumber = invoiceInput.value.trim();
            if (!invoiceNumber) {
                alert('Silakan masukkan nomor invoice.');
                return;
            }

            const btnSearch = document.getElementById('btn_search');
            const originalBtnText = btnSearch.textContent;
            btnSearch.textContent = 'Mencari...';
            btnSearch.disabled = true;

            fetch(`/orders/search/${encodeURIComponent(invoiceNumber)}`)
                .then(response => {
                    if (!response.ok) {
                        if (response.status === 404) {
                            throw new Error('Pesanan tidak ditemukan. Periksa kembali nomor invoice Anda.');
                        }
                        throw new Error('Gagal mencari pesanan. Silakan coba beberapa saat lagi.');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.data) {
                        populateInvoiceModal(data.data);
                        document.getElementById('invoiceDetailModal').classList.add('active');
                    } else {
                        throw new Error(data.message || 'Pesanan tidak ditemukan.');
                    }
                })
                .catch(error => {
                    alert(error.message);
                })
                .finally(() => {
                    btnSearch.textContent = originalBtnText;
                    btnSearch.disabled = false;
                });
        }

        function populateInvoiceModal(order) {
            document.getElementById('invoiceOrderId').textContent = order.invoice_number;
            document.getElementById('invoiceOrderDate').textContent = new Date(order.created_at).toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            document.getElementById('invoiceCustomerName').textContent = order.customer_name;
            document.getElementById('invoiceCustomerPhone').textContent = order.customer_phone;
            document.getElementById('invoiceCustomerAddress').textContent = order.shipping_address;

            const itemsHtml = order.items.map(item => `
                <tr style="border-bottom: 1px solid var(--fdn-grey-light-hover);">
                    <td style="padding: 12px 0; font-size: var(--fs-caption); color: var(--charcoal-grey);">${item.product.name}</td>
                    <td style="text-align: center; padding: 12px 0; font-size: var(--fs-caption); color: var(--charcoal-grey);">${item.quantity}</td>
                    <td style="text-align: right; padding: 12px 0; font-size: var(--fs-caption); color: var(--charcoal-grey);">Rp ${(item.unit_price).toLocaleString('id-ID')}</td>
                </tr>
            `).join('');
            document.getElementById('invoiceItems').innerHTML = itemsHtml;

            document.getElementById('invoiceSubtotal').textContent = `Rp ${order.subtotal_amount.toLocaleString('id-ID')}`;
            document.getElementById('invoiceShipping').textContent = `Rp ${order.shipping_cost.toLocaleString('id-ID')}`;
            document.getElementById('invoiceTotal').textContent = `Rp ${order.total_amount.toLocaleString('id-ID')}`;

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
            
            // Populate delivery notes if available
            document.getElementById('invoiceNotes').textContent = (order.delivery && order.delivery.notes) || '-';
        }

        function closeInvoiceModal() {
            document.getElementById('invoiceDetailModal').classList.remove('active');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const btnSearch = document.getElementById('btn_search');
            const inputInvoice = document.getElementById('no_invoice');
            
            if (btnSearch) {
                btnSearch.addEventListener('click', searchOrder);
            }
            if (inputInvoice) {
                inputInvoice.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        searchOrder();
                    }
                });
            }

            const overlay = document.getElementById('invoiceDetailModal');
            if (overlay) {
                overlay.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeInvoiceModal();
                    }
                });
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeInvoiceModal();
                }
            });
        });
    </script>
@endpush

@endsection
