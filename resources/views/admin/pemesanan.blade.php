@extends('layouts.admin')

@section('title', 'Manajemen Pesanan - Cireng A\'paweh')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/pemesanan.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/modal-confirmation.css') }}" />
    <div>
        <div class="pageHeader">
            <div class="headerContent">
                <h2>Manajemen Pesanan</h2>
                <p>Kelola pesanan pelanggan Cireng A'paweh</p>
            </div>
            <div class="headerSearchBox">
                <span class="material-symbols-outlined">search</span>
                <input type="text" class="searchBox" placeholder="Cari ID Pesanan atau Nama...">
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="tabsContainer">
            <div class="tabsList">
                <button class="tabButton tabButton-active" data-tab="tab-pesanan-baru">
                    <span class="material-symbols-outlined">add_circle</span>
                    <span>1. Pesanan Baru</span>
                </button>
                <button class="tabButton" data-tab="tab-perlu-dikirim">
                    <span class="material-symbols-outlined">local_shipping</span>
                    <span>2. Perlu Dikirim (Packing)</span>
                </button>
                <button class="tabButton" data-tab="tab-sedang-dikirim">
                    <span class="material-symbols-outlined">directions_car</span>
                    <span>3. Sedang Dikirim</span>
                </button>
                <button class="tabButton" data-tab="tab-selesai">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span>4. Selesai</span>
                </button>
            </div>
        </div>

        <!-- Tab Content: Pesanan Baru -->
        <div id="tab-pesanan-baru" class="tabContent tabContent-active">
            <div class="tableWrapper">
                <table class="orderTable">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>ID Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Pesanan</th>
                            <th>Harga</th>
                            <th>Status Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>12 Okt 2023, 10:30</td>
                            <td class="idPesanan">ORD-20230501-001</td>
                            <td class="namaPelanggan">Andi Wijaya</td>
                            <td class="pesanan">3x Cireng Rujak, 1x Es Teh</td>
                            <td class="totalHarga"><strong>Rp 45.000</strong></td>
                            <td><span class="badge badgePaymentStatus badgePaymentCompleted">Lunas</span></td>
                            <td class="aksiCell">
                                <button class="btnAction btnProsesPesanan" onclick="openConfirmModal('proses', 'ORD-20230501-001')">Proses Pesanan</button>
                                <button class="btnAction btnBatalkanPesanan" onclick="openConfirmModal('batalkan', 'ORD-20230501-001')">Batalkan Pesanan</button>
                                <button class="btnIcon btnViewInvoice" title="Lihat Invoice" onclick="openInvoiceModal('ORD-20230501-001')">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>12 Okt 2023, 11:15</td>
                            <td class="idPesanan">ORD-20230501-002</td>
                            <td class="namaPelanggan">Siti Aminah</td>
                            <td class="pesanan">2x Cireng Keju, 2x Ciliok Kuah</td>
                            <td class="totalHarga"><strong>Rp 62.000</strong></td>
                            <td><span class="badge badgePaymentStatus badgePaymentPending">Menunggu Pembayaran</span></td>
                            <td class="aksiCell">
                                <button class="btnAction btnProsesPesanan" disabled>Proses Pesanan</button>
                                <button class="btnAction btnBatalkanPesanan" onclick="openConfirmModal('batalkan', 'ORD-20230501-002')">Batalkan Pesanan</button>
                                <button class="btnIcon btnViewInvoice" title="Lihat Invoice" onclick="openInvoiceModal('ORD-20230501-002')">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>12 Okt 2023, 11:45</td>
                            <td class="idPesanan">ORD-20230501-003</td>
                            <td class="namaPelanggan">Budi Santoso</td>
                            <td class="pesanan">5x Cireng Rujak (Family Pack)</td>
                            <td class="totalHarga"><strong>Rp 125.000</strong></td>
                            <td><span class="badge badgePaymentStatus badgePaymentCompleted">Lunas</span></td>
                            <td class="aksiCell">
                                <button class="btnAction btnProsesPesanan" onclick="openConfirmModal('proses', 'ORD-20230501-003')">Proses Pesanan</button>
                                <button class="btnAction btnBatalkanPesanan" onclick="openConfirmModal('batalkan', 'ORD-20230501-003')">Batalkan Pesanan</button>
                                <button class="btnIcon btnViewInvoice" title="Lihat Invoice" onclick="openInvoiceModal('ORD-20230501-003')">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>12 Okt 2023, 12:05</td>
                            <td class="idPesanan">ORD-20230501-004</td>
                            <td class="namaPelanggan">Dewi Lestari</td>
                            <td class="pesanan">1x Cireng Campur, 1x Kopi Tubruk</td>
                            <td class="totalHarga"><strong>Rp 38.500</strong></td>
                            <td><span class="badge badgePaymentStatus badgePaymentCompleted">Lunas</span></td>
                            <td class="aksiCell">
                                <button class="btnAction btnProsesPesanan" onclick="openConfirmModal('proses', 'ORD-20230501-004')">Proses Pesanan</button>
                                <button class="btnAction btnBatalkanPesanan" onclick="openConfirmModal('batalkan', 'ORD-20230501-004')">Batalkan Pesanan</button>
                                <button class="btnIcon btnViewInvoice" title="Lihat Invoice" onclick="openInvoiceModal('ORD-20230501-004')">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="paginationWrapper">
                <button class="paginationBtn paginationPrev">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <div class="paginationNumbers">
                    <button class="paginationNumber active">1</button>
                    <button class="paginationNumber">2</button>
                    <button class="paginationNumber">3</button>
                </div>
                <button class="paginationBtn paginationNext">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>

            <div class="paginationInfo">
                <p>Menampilkan 4 dari 24 pesanan</p>
            </div>
        </div>

        <!-- Tab Content: Perlu Dikirim (Packing) -->
        <div id="tab-perlu-dikirim" class="tabContent">
            <div class="tableWrapper">
                <table class="orderTable">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>ID Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Pesanan</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>13 Okt 2023, 08:30</td>
                            <td class="idPesanan">ORD-20230501-005</td>
                            <td class="namaPelanggan">Rina Wijaya</td>
                            <td class="pesanan">4x Cireng Rujak Pedas</td>
                            <td class="totalHarga"><strong>Rp 65.000</strong></td>
                            <td class="aksiCell">
                                <button class="btnAction btnKirimPesanan" onclick="openConfirmModal('kirim', 'ORD-20230501-005')">Kirim Pesanan</button>
                                <button class="btnAction btnBatalkanPesanan" onclick="openConfirmModal('batalkan', 'ORD-20230501-005')">Batalkan Pesanan</button>
                                <button class="btnIcon btnViewInvoice" title="Lihat Invoice" onclick="openInvoiceModal('ORD-20230501-005')">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>13 Okt 2023, 09:15</td>
                            <td class="idPesanan">ORD-20230501-006</td>
                            <td class="namaPelanggan">Bambang Heru</td>
                            <td class="pesanan">2x Cireng Keju Melt, 1x Es Jeruk</td>
                            <td class="totalHarga"><strong>Rp 50.000</strong></td>
                            <td class="aksiCell">
                                <button class="btnAction btnKirimPesanan" onclick="openConfirmModal('kirim', 'ORD-20230501-006')">Kirim Pesanan</button>
                                <button class="btnAction btnBatalkanPesanan" onclick="openConfirmModal('batalkan', 'ORD-20230501-006')">Batalkan Pesanan</button>
                                <button class="btnIcon btnViewInvoice" title="Lihat Invoice" onclick="openInvoiceModal('ORD-20230501-006')">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>13 Okt 2023, 10:00</td>
                            <td class="idPesanan">ORD-20230501-007</td>
                            <td class="namaPelanggan">Siska Pratama</td>
                            <td class="pesanan">10x Cireng Campur (Bulk)</td>
                            <td class="totalHarga"><strong>Rp 150.000</strong></td>
                            <td class="aksiCell">
                                <button class="btnAction btnKirimPesanan" onclick="openConfirmModal('kirim', 'ORD-20230501-007')">Kirim Pesanan</button>
                                <button class="btnAction btnBatalkanPesanan" onclick="openConfirmModal('batalkan', 'ORD-20230501-007')">Batalkan Pesanan</button>
                                <button class="btnIcon btnViewInvoice" title="Lihat Invoice" onclick="openInvoiceModal('ORD-20230501-007')">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>13 Okt 2023, 11:45</td>
                            <td class="idPesanan">ORD-20230501-008</td>
                            <td class="namaPelanggan">Fajar Nugraha</td>
                            <td class="pesanan">3x Cireng Rujak, 2x Ciliok</td>
                            <td class="totalHarga"><strong>Rp 75.000</strong></td>
                            <td class="aksiCell">
                                <button class="btnAction btnKirimPesanan" onclick="openConfirmModal('kirim', 'ORD-20230501-008')">Kirim Pesanan</button>
                                <button class="btnAction btnBatalkanPesanan" onclick="openConfirmModal('batalkan', 'ORD-20230501-008')">Batalkan Pesanan</button>
                                <button class="btnIcon btnViewInvoice" title="Lihat Invoice" onclick="openInvoiceModal('ORD-20230501-008')">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="paginationWrapper">
                <button class="paginationBtn paginationPrev">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <div class="paginationNumbers">
                    <button class="paginationNumber active">1</button>
                    <button class="paginationNumber">2</button>
                    <button class="paginationNumber">3</button>
                </div>
                <button class="paginationBtn paginationNext">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>

            <div class="paginationInfo">
                <p>Menampilkan 4 dari 24 pesanan</p>
            </div>
        </div>

        <!-- Tab Content: Sedang Dikirim -->
        <div id="tab-sedang-dikirim" class="tabContent">
            <div class="tableWrapper">
                <table class="orderTable">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>ID Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Pesanan</th>
                            <th>Harga</th>
                            <th>Status Pengiriman</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>14 Okt 2023, 10:20</td>
                            <td class="idPesanan">ORD-20230501-009</td>
                            <td class="namaPelanggan">Andi Pratama</td>
                            <td class="pesanan">2x Cireng Rujak</td>
                            <td class="totalHarga"><strong>Rp 30.000</strong></td>
                            <td>
                                <span class="badge badgeShippingStatus badgeShippingDalam">Dalam Perjalanan</span>
                            </td>
                            <td class="aksiCell">
                                <button class="btnAction btnLacakPaket">Lacak Paket</button>
                                <button class="btnIcon btnViewInvoice" title="Lihat Detail" onclick="openInvoiceModal('ORD-20230501-009')">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>14 Okt 2023, 11:45</td>
                            <td class="idPesanan">ORD-20230501-010</td>
                            <td class="namaPelanggan">Siti Aminah</td>
                            <td class="pesanan">5x Cireng Keju Melt</td>
                            <td class="totalHarga"><strong>Rp 75.000</strong></td>
                            <td>
                                <span class="badge badgeShippingStatus badgeShippingSampai">Sampai</span>
                            </td>
                            <td class="aksiCell">
                                <button class="btnAction btnLacakPaket">Lacak Paket</button>
                                <button class="btnIcon btnViewInvoice" title="Lihat Detail" onclick="openInvoiceModal('ORD-20230501-010')">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>14 Okt 2023, 13:10</td>
                            <td class="idPesanan">ORD-20230501-011</td>
                            <td class="namaPelanggan">Budi Santoso</td>
                            <td class="pesanan">1x Paket Komplit Family</td>
                            <td class="totalHarga"><strong>Rp 95.000</strong></td>
                            <td>
                                <span class="badge badgeShippingStatus badgeShippingDalam">Dalam Perjalanan</span>
                            </td>
                            <td class="aksiCell">
                                <button class="btnAction btnLacakPaket">Lacak Paket</button>
                                <button class="btnIcon btnViewInvoice" title="Lihat Detail" onclick="openInvoiceModal('ORD-20230501-011')">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>14 Okt 2023, 14:05</td>
                            <td class="idPesanan">ORD-20230501-012</td>
                            <td class="namaPelanggan">Dewi Lestari</td>
                            <td class="pesanan">3x Cireng Original</td>
                            <td class="totalHarga"><strong>Rp 50.000</strong></td>
                            <td>
                                <span class="badge badgeShippingStatus badgeShippingSelesai">Selesai</span>
                            </td>
                            <td class="aksiCell">
                                <button class="btnAction btnLacakPaket">Lacak Paket</button>
                                <button class="btnIcon btnViewInvoice" title="Lihat Detail" onclick="openInvoiceModal('ORD-20230501-012')">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="paginationWrapper">
                <button class="paginationBtn paginationPrev">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <div class="paginationNumbers">
                    <button class="paginationNumber active">1</button>
                    <button class="paginationNumber">2</button>
                    <button class="paginationNumber">3</button>
                </div>
                <button class="paginationBtn paginationNext">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>

            <div class="paginationInfo">
                <p>Menampilkan 4 dari 18 pesanan</p>
            </div>
        </div>

        <!-- Tab Content: Selesai -->
        <div id="tab-selesai" class="tabContent">
            <div class="tableWrapper">
                <table class="orderTable">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>ID Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Pesanan</th>
                            <th>Harga</th>
                            <th>Status Pengiriman</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>15 Okt 2023, 09:15</td>
                            <td class="idPesanan">ORD-20230501-013</td>
                            <td class="namaPelanggan">Budi Raharjo</td>
                            <td class="pesanan">3x Cireng Keju</td>
                            <td class="totalHarga"><strong>Rp 45.000</strong></td>
                            <td>
                                <span class="badge badgeShippingStatus badgeShippingSelesai">Selesai</span>
                            </td>
                            <td class="aksiCell">
                                <button class="btnAction btnLihatDetail" onclick="openInvoiceModal('ORD-20230501-013')">Lihat Detail</button>
                            </td>
                        </tr>
                        <tr>
                            <td>15 Okt 2023, 14:30</td>
                            <td class="idPesanan">ORD-20230501-014</td>
                            <td class="namaPelanggan">Ani Wijaya</td>
                            <td class="pesanan">2x Cireng Rujak</td>
                            <td class="totalHarga"><strong>Rp 30.000</strong></td>
                            <td>
                                <span class="badge badgeShippingStatus badgeShippingSelesai">Selesai</span>
                            </td>
                            <td class="aksiCell">
                                <button class="btnAction btnLihatDetail" onclick="openInvoiceModal('ORD-20230501-014')">Lihat Detail</button>
                            </td>
                        </tr>
                        <tr>
                            <td>16 Okt 2023, 10:05</td>
                            <td class="idPesanan">ORD-20230501-015</td>
                            <td class="namaPelanggan">Dedi Kurniawan</td>
                            <td class="pesanan">5x Cireng Original</td>
                            <td class="totalHarga"><strong>Rp 60.000</strong></td>
                            <td>
                                <span class="badge badgeShippingStatus badgeShippingSelesai">Selesai</span>
                            </td>
                            <td class="aksiCell">
                                <button class="btnAction btnLihatDetail" onclick="openInvoiceModal('ORD-20230501-015')">Lihat Detail</button>
                            </td>
                        </tr>
                        <tr>
                            <td>16 Okt 2023, 11:20</td>
                            <td class="idPesanan">ORD-20230501-016</td>
                            <td class="namaPelanggan">Linda Sari</td>
                            <td class="pesanan">1x Paket Komplit Family</td>
                            <td class="totalHarga"><strong>Rp 35.000</strong></td>
                            <td>
                                <span class="badge badgeShippingStatus badgeShippingSelesai">Selesai</span>
                            </td>
                            <td class="aksiCell">
                                <button class="btnAction btnLihatDetail" onclick="openInvoiceModal('ORD-20230501-016')">Lihat Detail</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="paginationWrapper">
                <button class="paginationBtn paginationPrev">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <div class="paginationNumbers">
                    <button class="paginationNumber active">1</button>
                    <button class="paginationNumber">2</button>
                    <button class="paginationNumber">3</button>
                </div>
                <button class="paginationBtn paginationNext">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>

            <div class="paginationInfo">
                <p>Menampilkan 4 dari 120 pesanan</p>
            </div>
        </div>
    </div>

    <!-- ====== CONFIRMATION MODALS ====== -->
    {{-- Modal Proses Pesanan --}}
    <div id="confirmProsesPesananModal" class="modalOverlay">
        <div class="modalDialog small">
            <div class="modalHeader">
                <h3>Proses Pesanan</h3>
                <button class="modalClose" onclick="closeConfirmModal('proses')">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="modalBody">
                <p>Apakah Anda yakin ingin memproses pesanan <strong id="promoOrderId">ORD-XXXX</strong>?</p>
            </div>
            <div class="modalFooter">
                <button class="btnCancel" onclick="closeConfirmModal('proses')">Batal</button>
                <button class="btnPrimary" onclick="confirmProsesPesanan()">Proses</button>
            </div>
        </div>
    </div>

    {{-- Modal Batalkan Pesanan --}}
    <div id="confirmBatalkanPesananModal" class="modalOverlay">
        <div class="modalDialog small">
            <div class="modalHeader">
                <h3>Batalkan Pesanan</h3>
                <button class="modalClose" onclick="closeConfirmModal('batalkan')">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="modalBody">
                <p>Apakah Anda yakin ingin membatalkan pesanan <strong id="batalkanOrderId">ORD-XXXX</strong>?</p>
            </div>
            <div class="modalFooter">
                <button class="btnCancel" onclick="closeConfirmModal('batalkan')">Tidak</button>
                <button class="btnPrimary" onclick="confirmBatalkanPesanan()">Batalkan Pesanan</button>
            </div>
        </div>
    </div>

    {{-- Modal Kirim Pesanan --}}
    <div id="confirmKirimPesananModal" class="modalOverlay">
        <div class="modalDialog small">
            <div class="modalHeader">
                <h3>Kirim Pesanan</h3>
                <button class="modalClose" onclick="closeConfirmModal('kirim')">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="modalBody">
                <p>Apakah Anda yakin ingin mengirim pesanan <strong id="kirimOrderId">ORD-XXXX</strong> melalui Biteship API?</p>
            </div>
            <div class="modalFooter">
                <button class="btnCancel" onclick="closeConfirmModal('kirim')">Batal</button>
                <button class="btnPrimary" onclick="confirmKirimPesanan()">Kirim</button>
            </div>
        </div>
    </div>

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
                        <p id="invoiceNotes" style="font-size: var(--fs-caption); color: var(--charcoal-grey); margin: 0;">Mohon segera dikemas dan dikirim</p>
                    </div>
                </div>
            </div>
            <div class="modalFooter">
                <button class="btnCancel" onclick="closeInvoiceModal()" style="flex: 1;">Tutup</button>
                <button class="btnPrimary" style="flex: 1;">Cetak Invoice</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/admin/pemesanan.js') }}"></script>
@endsection
