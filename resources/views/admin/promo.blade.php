@extends('layouts.admin')

@section('title', 'Manajemen Promo - Cireng A\'paweh')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/modal-confirmation.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/promo.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet"/>

    <div>
        <div class="pageHeader">
            <div class="headerContent">
                <h2>Manajemen Promo</h2>
                <p>Kelola promo produk Cireng A'paweh</p>
            </div>
            <div class="headerSearchBox">
                <span class="material-symbols-outlined">search</span>
                <input type="text" class="searchBox" placeholder="Cari Promo" id="searchInput">
            </div>
            <button class="btnAddPromo" id="btnAddPromoModal">
                <span class="material-symbols-outlined">add</span>
                <span>Tambah Promo</span>
            </button>
        </div>

                <div class="statsCardsGrid">
            <div class="statCard">
                <div class="statIcon">
                    <span class="material-symbols-outlined" style="color: var(--fdn-yellow-dark); font-size: 64px;">local_offer</span>
                </div>
                <div class="statContent">
                    <h3 class="statLabel">Total Promo</h3>
                    <p class="statValue">{{ $totalPromo }}</p>
                </div>
            </div>

            <div class="statCard">
                <div class="statIcon" >
                    <span class="material-symbols-outlined" style="color: #2e7d32; font-size: 64px;">check_circle</span>
                </div>
                <div class="statContent">
                    <h3 class="statLabel">Aktif</h3>
                    <p class="statValue">{{ $activePromo }}</p>
                </div>
            </div>

            <div class="statCard">
                <div class="statIcon" >
                    <span class="material-symbols-outlined" style="color: var(--fdn-red-normal); font-size: 64px;">cancel</span>
                </div>
                <div class="statContent">
                    <h3 class="statLabel">Expired</h3>
                    <p class="statValue">{{ $expiredPromo }}</p>
                </div>
            </div>
        </div>

        <div class="tableWrapper">
            <table class="promoTable">
                <thead>
                    <tr>
                        <th>Nama Promo</th>
                        <th>Produk Terkait</th>
                        <th>Kode Promo</th>
                        <th class="centerAlign info">Tipe</th>
                        <th>Diskon (%)</th>
                        <th>Kuota</th>
                        <th>Periode</th>
                        <th class="centerAlign info">Status</th>
                        <th class="centerAlign info">Aksi</th>
                    </tr>
                </thead>
                <tbody id="promoTableBody">
                    @forelse($promos as $promo)
                        <tr data-id="{{ $promo->id }}"
                            data-description="{{ $promo->description }}"
                            data-promo-type="{{ $promo->promo_type }}"
                            data-discount="{{ $promo->discount_percentage }}"
                            data-start-date="{{ $promo->start_date->format('Y-m-d') }}"
                            data-end-date="{{ $promo->end_date->format('Y-m-d') }}">
                            <td class="namaPromo">{{ $promo->title }}</td>
                            <td class="produkTerkait">
                                @php
                                    $products = $promo->products;
                                    $displayProducts = $products->take(2);
                                    $remainingCount = $products->count() - 2;
                                @endphp
                                @forelse($displayProducts as $product)
                                    <span class="produkTag">{{ $product->name }}</span>
                                @empty
                                    <span style="color: var(--fdn-grey-normal);">-</span>
                                @endforelse
                                @if($remainingCount > 0)
                                    <span class="produkTag produkMore" title="{{ $products->skip(2)->pluck('name')->join(', ') }}">+{{ $remainingCount }} lainnya</span>
                                @endif
                            </td>
                            <td>{{ $promo->promo_code ?? '-' }}</td>
                            <td>
                                @if($promo->promo_type === 'otomatis')
                                    <span class="badge" style="background-color: #e3f2fd; color: #1976d2;">Otomatis</span>
                                @else
                                    <span class="badge" style="background-color: #f3e5f5; color: #7b1fa2;">Kode</span>
                                @endif
                            </td>
                            <td class="discountPromo">{{ $promo->discount_percentage }}%</td>
                            <td class="kuotaLabel">{{ $promo->used_count ?? 0 }} / {{ $promo->max_usage }}</td>
                            <td class="periodePromo">{{ $promo->start_date->format('d M') }} - {{ $promo->end_date->format('d M') }}</td>
                            <td>
                                @php
                                    $now = now();
                                    $status = '';
                                    if (!$promo->is_active) {
                                        $status = 'Nonaktif';
                                    } elseif ($now->isBefore($promo->start_date)) {
                                        $status = 'Draft';
                                    } elseif ($now->isAfter($promo->end_date)) {
                                        $status = 'Expired';
                                    } else {
                                        $status = 'Aktif';
                                    }
                                @endphp
                                @if($status === 'Aktif')
                                    <span class="badge badgeAktif">{{ $status }}</span>
                                @elseif($status === 'Draft')
                                    <span class="badge badgeDraft">{{ $status }}</span>
                                @elseif($status === 'Expired')
                                    <span class="badge badgeExpired">{{ $status }}</span>
                                @else
                                    <span class="badge badgeNonaktif">{{ $status }}</span>
                                @endif
                            </td>
                            <td class="aksiCell">
                                <button class="btnIcon btnEdit" title="Edit" onclick="editPromo({{ $promo->id }})">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <button class="btnIcon btnDelete" title="Hapus" onclick="deletePromo({{ $promo->id }})">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 40px;">
                                <p style="color: var(--charcoal-grey);">Belum ada promo. Klik "Tambah Promo" untuk menambahkan promo baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($promos->hasPages())
            <div class="paginationWrapper">
                @if ($promos->onFirstPage())
                    <button class="paginationBtn paginationPrev" disabled>
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                @else
                    <a href="{{ $promos->previousPageUrl() }}" class="paginationBtn paginationPrev">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </a>
                @endif

                <div class="paginationNumbers">
                    @foreach ($promos->getUrlRange(1, $promos->lastPage()) as $page => $url)
                        @if ($page == $promos->currentPage())
                            <button class="paginationNumber active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="paginationNumber">{{ $page }}</a>
                        @endif
                    @endforeach
                </div>

                @if ($promos->hasMorePages())
                    <a href="{{ $promos->nextPageUrl() }}" class="paginationBtn paginationNext">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                @else
                    <button class="paginationBtn paginationNext" disabled>
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                @endif
            </div>
        @endif
    </div>

    {{-- Modal Form Promo --}}
    <div id="promoModal" class="modal">
        <div class="modalContent">
            <div class="modalHeader">
                <h3 id="modalTitle">Tambah Promo</h3>
                <button class="closeModal" onclick="closePromoModal()">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form id="promoForm">
                @csrf
                <input type="hidden" id="promoId" name="promo_id" value="">

                <div class="formGroup">
                    <label for="promoTitle">Nama Promo *</label>
                    <input type="text" id="promoTitle" name="title" placeholder="Masukkan nama promo" required>
                </div>

                    <div class="formRow">
                        <div class="formGroup">
                            <label for="promoCode">Kode Promo</label>
                            <input type="text" id="promoCode" name="promo_code" placeholder="Masukkan Kode Promo">
                        </div>
                        <div class="formGroup">
                            <label for="promoDiscount">Persentase Diskon (%) *</label>
                            <input type="number" id="promoDiscount" name="discount_percentage" min="1" max="100" placeholder="0" required>
                        </div>
                    </div>

                <div class="formGroup">
                    <label for="promoDescription">Deskripsi</label>
                    <textarea id="promoDescription" name="description" placeholder="Deskripsi promo..."></textarea>
                </div>

                <div class="formGroup">
                    <label for="product_ids">Pilih Produk *</label>
                    <select id="product_ids" name="product_ids" multiple required>
                    </select>
                </div>

                <div class="formRow">
                    <div class="formGroup">
                        <label for="promoType">Tipe Promo *</label>
                        <select id="promoType" name="promo_type" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="otomatis">Otomatis</option>
                            <option value="kode">Kode Promo</option>
                        </select>
                    </div>
                    <div class="formGroup">
                        <label for="promoMaxUsage">Kuota Promo *</label>
                        <input type="number" id="promoMaxUsage" name="max_usage" min="1" value="100" placeholder="0" required>
                    </div>
                </div>

                <div class="formRow">
                    <div class="formGroup">
                        <label for="promoStartDate">Tanggal Mulai *</label>
                        <input type="date" id="promoStartDate" name="start_date" required>
                    </div>
                    <div class="formGroup">
                        <label for="promoEndDate">Tanggal Berakhir *</label>
                        <input type="date" id="promoEndDate" name="end_date" required>
                    </div>
                </div>

                <div class="formGroup">
                    <label for="promoStatus">
                        Status Promo
                        <span class="tooltipContainer">
                            <span class="tooltipTrigger material-symbols-outlined">info</span>
                            <span class="tooltipText">
                                <strong>Aktif:</strong> Promo dapat digunakan (is_active=1 dan sesuai periode)
                                <br><strong>Draft:</strong> Promo belum mulai (is_active=1 tapi belum sampai start_date)
                                <br><strong>Nonaktif:</strong> Promo tidak dapat digunakan (is_active=0)
                            </span>
                        </span>
                    </label>
                    <select id="promoStatus" name="is_active" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="1">Aktif / Draft</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

                <div class="formActions">
                    <button type="button" class="btnCancel" onclick="closePromoModal()">Batal</button>
                    <button type="submit" class="btnSubmit">Simpan Promo</button>
                </div>
            </form>
        </div>
    </div>

    <x-modal-confirmation
        id="confirmSaveModal"
        type="save"
        title="Simpan Promo?"
        message="Apakah Anda yakin ingin menyimpan promo baru ini?"
        confirmAction="confirmSavePromo()"
        btnText="Simpan"
    />

    <x-modal-confirmation
        id="confirmUpdateModal"
        type="update"
        title="Perbarui Promo?"
        message="Apakah Anda yakin ingin memperbarui data promo ini?"
        confirmAction="confirmUpdatePromo()"
        btnText="Perbarui"
    />

    <x-modal-confirmation
        id="confirmDeleteModal"
        type="delete"
        title="Hapus Promo?"
        message="Tindakan ini tidak bisa dibatalkan. Yakin ingin menghapus promo?"
        confirmAction="confirmDeletePromo()"
        btnColor="btnDanger"
        btnText="Hapus"
    />

    {{-- Modal Success --}}
    <div id="successModal" class="modalOverlay">
        <div class="modalDialog small">
            <div class="modalHeader">
                <h3>Berhasil</h3>
                <button class="modalClose" onclick="closeSuccessModal()">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="modalBody">
                <div style="text-align: center;">
                    <span class="material-symbols-outlined" style="font-size: 48px; color: var(--fdn-red-normal);">check_circle</span>
                    <p id="successMessage" style="margin-top: 16px; font-weight: var(--fw-medium);">Operasi berhasil dilakukan</p>
                </div>
            </div>
            <div class="modalFooter">
                <button class="btnPrimary" onclick="closeSuccessModal()" style="flex: 1;">OK</button>
            </div>
        </div>
    </div>

    {{-- Modal Error --}}
    <div id="errorModal" class="modalOverlay">
        <div class="modalDialog small">
            <div class="modalHeader">
                <h3>Terjadi Kesalahan</h3>
                <button class="modalClose" onclick="closeErrorModal()">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="modalBody">
                <div style="text-align: center;">
                    <span class="material-symbols-outlined" style="font-size: 48px; color: var(--fdn-red-dark);">error</span>
                    <p id="errorMessage" style="margin-top: 16px; font-weight: var(--fw-medium);">Terjadi kesalahan saat memproses</p>
                </div>
            </div>
            <div class="modalFooter">
                <button class="btnCancel" onclick="closeErrorModal()" style="flex: 1;">Tutup</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="{{ asset('js/admin/promo.js') }}"></script>

@endsection
