@extends('layouts.admin')

@section('title', 'Manajemen Pesanan - Cireng A\'paweh')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/pemesanan.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/modal-confirmation.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/tracking.css') }}" />
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
                            <th class="action">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesananBaru as $order)
                            <tr>
                                <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td class="idPesanan">{{ $order->invoice_number }}</td>
                                <td class="namaPelanggan">{{ $order->customer_name }}</td>
                                <td class="pesanan">
                                    @foreach($order->items as $item)
                                        {{ $item->quantity }}x {{ $item->product->name }}@if(!$loop->last), @endif
                                    @endforeach
                                </td>
                                <td class="totalHarga"><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                                <td>
                                    @if($order->payment && $order->payment->status === 'settlement')
                                        <span class="badge badgePaymentStatus badgePaymentCompleted">Lunas</span>
                                    @else
                                        <span class="badge badgePaymentStatus badgePaymentPending">Menunggu Pembayaran</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="aksiWrapper">
                                        @if($order->payment && $order->payment->status === 'settlement')
                                            <button class="btnAction btnProsesPesanan" onclick="openConfirmModal('proses', '{{ $order->id }}', '{{ $order->invoice_number }}')">Proses Pesanan</button>
                                        @else
                                            <button class="btnAction btnProsesPesanan" disabled>Proses Pesanan</button>
                                        @endif
                                        <button class="btnAction btnBatalkanPesanan" onclick="openConfirmModal('batalkan', '{{ $order->id }}', '{{ $order->invoice_number }}')">Batalkan Pesanan</button>
                                        <button class="btnIcon btnViewInvoice" title="Lihat Invoice" onclick="openInvoiceModal('{{ $order->id }}', '{{ $order->invoice_number }}')">
                                            <span class="material-symbols-outlined">visibility</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="emptyState">
                                    <span class="material-symbols-outlined emptyStateIcon">inbox</span>
                                    <p>Tidak ada pesanan baru</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="paginationWrapper">
                @if($pesananBaru->onFirstPage())
                    <button class="paginationBtn paginationPrev" disabled>
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                @else
                    <a href="{{ $pesananBaru->previousPageUrl() }}&page_baru={{ $pesananBaru->currentPage() - 1 }}" class="paginationBtn paginationPrev">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </a>
                @endif

                <div class="paginationNumbers">
                    @foreach($pesananBaru->getUrlRange(1, $pesananBaru->lastPage()) as $page => $url)
                        @if($page == $pesananBaru->currentPage())
                            <button class="paginationNumber active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}&page_baru={{ $page }}" class="paginationNumber">{{ $page }}</a>
                        @endif
                    @endforeach
                </div>

                @if($pesananBaru->hasMorePages())
                    <a href="{{ $pesananBaru->nextPageUrl() }}&page_baru={{ $pesananBaru->currentPage() + 1 }}" class="paginationBtn paginationNext">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                @else
                    <button class="paginationBtn paginationNext" disabled>
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                @endif
            </div>

            <div class="paginationInfo">
                <p>Menampilkan {{ $pesananBaru->count() }} dari {{ $pesananBaru->total() }} pesanan</p>
            </div>
        </div>

        <!-- Tab Content: Perlu Dikirim (Packing) -->
        <div id="tab-perlu-dikirim" class="tabContent">
            <div class="tableWrapper">
                <table class="orderTable" border="1">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>ID Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Pesanan</th>
                            <th>Harga</th>
                            <th class="action">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($perluDikirim as $order)
                            <tr>
                                <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td class="idPesanan">{{ $order->invoice_number }}</td>
                                <td class="namaPelanggan">{{ $order->customer_name }}</td>
                                <td class="pesanan">
                                    @foreach($order->items as $item)
                                        {{ $item->quantity }}x {{ $item->product->name }}@if(!$loop->last), @endif
                                    @endforeach
                                </td>
                                <td class="totalHarga"><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                                <td>
                                    <div class="aksiWrapper">
                                        <button class="btnAction btnKirimPesanan" onclick="openConfirmModal('kirim', '{{ $order->id }}', '{{ $order->invoice_number }}')">Kirim Pesanan</button>
                                        <button class="btnAction btnBatalkanPesanan" onclick="openConfirmModal('batalkan', '{{ $order->id }}', '{{ $order->invoice_number }}')">Batalkan Pesanan</button>
                                        <button class="btnIcon btnViewInvoice" title="Lihat Invoice" onclick="openInvoiceModal('{{ $order->id }}', '{{ $order->invoice_number }}')">
                                            <span class="material-symbols-outlined">visibility</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="emptyState">
                                    <span class="material-symbols-outlined emptyStateIcon">local_shipping</span>
                                    <p>Tidak ada pesanan yang perlu dikirim</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="paginationWrapper">
                @if($perluDikirim->onFirstPage())
                    <button class="paginationBtn paginationPrev" disabled>
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                @else
                    <a href="{{ $perluDikirim->previousPageUrl() }}&page_dikirim={{ $perluDikirim->currentPage() - 1 }}" class="paginationBtn paginationPrev">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </a>
                @endif

                <div class="paginationNumbers">
                    @foreach($perluDikirim->getUrlRange(1, $perluDikirim->lastPage()) as $page => $url)
                        @if($page == $perluDikirim->currentPage())
                            <button class="paginationNumber active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}&page_dikirim={{ $page }}" class="paginationNumber">{{ $page }}</a>
                        @endif
                    @endforeach
                </div>

                @if($perluDikirim->hasMorePages())
                    <a href="{{ $perluDikirim->nextPageUrl() }}&page_dikirim={{ $perluDikirim->currentPage() + 1 }}" class="paginationBtn paginationNext">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                @else
                    <button class="paginationBtn paginationNext" disabled>
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                @endif
            </div>

            <div class="paginationInfo">
                <p>Menampilkan {{ $perluDikirim->count() }} dari {{ $perluDikirim->total() }} pesanan</p>
            </div>
        </div>

        <!-- Tab Content: Sedang Dikirim -->
        <div id="tab-sedang-dikirim" class="tabContent">
            <div class="tableWrapper">
                <table class="orderTable" border="1">
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
                        @forelse($sedangDikirim as $order)
                            <tr data-delivery-id="{{ $order->delivery ? $order->delivery->id : '' }}" data-delivery-status="{{ $order->delivery ? $order->delivery->status : '' }}">
                                <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td class="idPesanan">{{ $order->invoice_number }}</td>
                                <td class="namaPelanggan">{{ $order->customer_name }}</td>
                                <td class="pesanan">
                                    @foreach($order->items as $item)
                                        {{ $item->quantity }}x {{ $item->product->name }}@if(!$loop->last), @endif
                                    @endforeach
                                </td>
                                <td class="totalHarga"><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                                <td>
                                    @if($order->delivery)
                                        @if($order->delivery->status === 'on_delivery')
                                            <span class="badge badgeShippingStatus badgeShippingDalam">Dalam Perjalanan</span>
                                        @elseif($order->delivery->status === 'picked_up')
                                            <span class="badge badgeShippingStatus badgeShippingDalam">Diambil Kurir</span>
                                        @elseif($order->delivery->status === 'delivered')
                                            <span class="badge badgeShippingStatus badgeShippingSelesai">Sampai</span>
                                        @else
                                            <span class="badge badgeShippingStatus badgeShippingDalam">{{ $order->delivery->status }}</span>
                                        @endif
                                    @else
                                        <span class="badge badgeShippingStatus badgeShippingSampai">-</span>
                                    @endif
                                </td>
                                <td class="aksiCell">
                                    <div class="aksiWrapper">
                                        <button class="btnAction btnLacakPaket" 
                                            @if($order->delivery && $order->delivery->tracking_number)
                                                onclick="openTrackingModal({{ $order->delivery->id }}, '{{ $order->invoice_number }}')"
                                            @else
                                                disabled
                                            @endif>
                                            Lacak Paket
                                        </button>
                                        <button class="btnIcon btnViewInvoice" title="Lihat Detail" onclick="openInvoiceModal('{{ $order->id }}', '{{ $order->invoice_number }}')">
                                            <span class="material-symbols-outlined">visibility</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="emptyState">
                                    <span class="material-symbols-outlined emptyStateIcon">directions_car</span>
                                    <p>Tidak ada pesanan yang sedang dikirim</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="paginationWrapper">
                @if($sedangDikirim->onFirstPage())
                    <button class="paginationBtn paginationPrev" disabled>
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                @else
                    <a href="{{ $sedangDikirim->previousPageUrl() }}&page_sedang={{ $sedangDikirim->currentPage() - 1 }}" class="paginationBtn paginationPrev">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </a>
                @endif

                <div class="paginationNumbers">
                    @foreach($sedangDikirim->getUrlRange(1, $sedangDikirim->lastPage()) as $page => $url)
                        @if($page == $sedangDikirim->currentPage())
                            <button class="paginationNumber active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}&page_sedang={{ $page }}" class="paginationNumber">{{ $page }}</a>
                        @endif
                    @endforeach
                </div>

                @if($sedangDikirim->hasMorePages())
                    <a href="{{ $sedangDikirim->nextPageUrl() }}&page_sedang={{ $sedangDikirim->currentPage() + 1 }}" class="paginationBtn paginationNext">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                @else
                    <button class="paginationBtn paginationNext" disabled>
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                @endif
            </div>

            <div class="paginationInfo">
                <p>Menampilkan {{ $sedangDikirim->count() }} dari {{ $sedangDikirim->total() }} pesanan</p>
            </div>
        </div>

        <!-- Tab Content: Selesai -->
        <div id="tab-selesai" class="tabContent">
            <div class="tableWrapper">
                <table class="orderTable" border="1">
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
                        @forelse($selesai as $order)
                            <tr>
                                <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td class="idPesanan">{{ $order->invoice_number }}</td>
                                <td class="namaPelanggan">{{ $order->customer_name }}</td>
                                <td class="pesanan">
                                    @foreach($order->items as $item)
                                        {{ $item->quantity }}x {{ $item->product->name }}@if(!$loop->last), @endif
                                    @endforeach
                                </td>
                                <td class="totalHarga"><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                                <td>
                                    <span class="badge badgeShippingStatus badgeShippingSelesai">Selesai</span>
                                </td>
                                <td class="aksiCell">
                                    <button class="btnIcon btnViewInvoice" title="Lihat Detail" onclick="openInvoiceModal('{{ $order->id }}', '{{ $order->invoice_number }}')">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="emptyState">
                                    <span class="material-symbols-outlined emptyStateIcon">done_all</span>
                                    <p>Tidak ada pesanan yang selesai</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="paginationWrapper">
                @if($selesai->onFirstPage())
                    <button class="paginationBtn paginationPrev" disabled>
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                @else
                    <a href="{{ $selesai->previousPageUrl() }}&page_selesai={{ $selesai->currentPage() - 1 }}" class="paginationBtn paginationPrev">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </a>
                @endif

                <div class="paginationNumbers">
                    @foreach($selesai->getUrlRange(1, $selesai->lastPage()) as $page => $url)
                        @if($page == $selesai->currentPage())
                            <button class="paginationNumber active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}&page_selesai={{ $page }}" class="paginationNumber">{{ $page }}</a>
                        @endif
                    @endforeach
                </div>

                @if($selesai->hasMorePages())
                    <a href="{{ $selesai->nextPageUrl() }}&page_selesai={{ $selesai->currentPage() + 1 }}" class="paginationBtn paginationNext">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                @else
                    <button class="paginationBtn paginationNext" disabled>
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                @endif
            </div>

            <div class="paginationInfo">
                <p>Menampilkan {{ $selesai->count() }} dari {{ $selesai->total() }} pesanan</p>
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
    @include('components.tracking-modal')
    <script src="{{ asset('js/tracking.js') }}"></script>
@endsection
