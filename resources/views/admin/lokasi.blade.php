@extends('layouts.admin')

@section('title', 'Manajemen Lokasi - Cireng A\'paweh')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/modal-confirmation.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/lokasi.css') }}" />
    <div>
        <div class="pageHeader">
            <div class="headerContent">
                <h2>Manajemen Lokasi</h2>
                <p>Kelola cabang cireng A'paweh</p>
            </div>
            <div class="headerSearchBox">
                <span class="material-symbols-outlined">search</span>
                <input type="text" class="searchBox" placeholder="Cari Cabang" id="searchInput">
            </div>
            <button class="btnAddLocation" id="btnAddLocationModal">
                <span class="material-symbols-outlined">add</span>
                <span>Tambah Lokasi</span>
            </button>
        </div>

        <div class="tableWrapper">
            <table class="locationTable">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama Cabang</th>
                        <th>Alamat</th>
                        <th>Jam Operasional</th>
                        <th class="centerAlign info">Status</th>
                        <th class="centerAlign info">Aksi</th>
                    </tr>
                </thead>
                <tbody id="locationTableBody">
                    @forelse($locations as $location)
                        <tr data-id="{{ $location->id }}">
                            <td class="fotoCell">
                                @if($location->image)
                                    <img src="{{ asset('storage/' . $location->image) }}" alt="{{ $location->name }}" class="locationThumb">
                                @else
                                    <img src="{{ asset('assets/img/placeholder.jpg') }}" alt="Placeholder" class="locationThumb">
                                @endif
                            </td>
                            <td class="namaLokasi">{{ $location->name }}</td>
                            <td class="alamat" title="{{ $location->address }}"><span class="alamatTruncate">{{ Str::limit($location->address, 40) }}</span></td>
                            <td class="jamOperasional">
                                <span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle;">schedule</span>
                                {{ $location->operating_hours }}
                            </td>
                            <td>
                                @if($location->is_active)
                                    <span class="badge badgeAktif">Aktif</span>
                                @else
                                    <span class="badge badgeNonaktif">Nonaktif</span>
                                @endif
                            </td>
                            <td class="centerAlign">
                                <div class="aksiCell">
                                    <button class="btnIcon btnEdit" title="Edit" onclick="editLocation({{ $location->id }})">
                                        <span class="material-symbols-outlined">edit</span>
                                    </button>
                                    <button class="btnIcon btnDelete" title="Hapus" onclick="deleteLocation({{ $location->id }})">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">Tidak ada data lokasi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($locations->hasPages())
            <div class="paginationWrapper">
                @if($locations->onFirstPage())
                    <button class="paginationBtn paginationPrev" disabled>
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                @else
                    <a href="{{ $locations->previousPageUrl() }}" class="paginationBtn paginationPrev">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </a>
                @endif
                
                <div class="paginationNumbers">
                    @foreach($locations->getUrlRange(1, $locations->lastPage()) as $page => $url)
                        @if($page == $locations->currentPage())
                            <button class="paginationNumber active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="paginationNumber">{{ $page }}</a>
                        @endif
                    @endforeach
                </div>
                
                @if($locations->hasMorePages())
                    <a href="{{ $locations->nextPageUrl() }}" class="paginationBtn paginationNext">
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

    <!-- Modal Form Lokasi -->
    <div id="locationModal" class="modal">
        <div class="modalContent">
            <div class="modalHeader">
                <h3 id="modalTitle">Tambah Lokasi</h3>
                <button class="closeModal" onclick="closeLocationModal()">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="locationForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="locationId" name="location_id">
                
                <div class="formGroup">
                    <label for="name">Nama Cabang *</label>
                    <input type="text" id="name" name="name" placeholder="Masukkan nama cabang" required>
                    <span class="errorMessage" id="nameError"></span>
                </div>

                <div class="formGroup">
                    <label for="address">Alamat *</label>
                    <div class="addressContainer">
                        <textarea id="address" name="address" placeholder="Masukkan alamat" required></textarea>
                    </div>
                    <span class="errorMessage" id="addressError"></span>
                </div>

                <div class="formGroup">
                    <label for="mapLink">Link Map *</label>
                    <div class="linkContainer">
                        <input type="text" name="mapLink" id="mapLink" placeholder="Masukkan link map Google Maps">
                    </div>
                    <span class="errorMessage" id="mapLinkError"></span>
                </div>

                <div class="formRow">
                    <div class="formGroup">
                        <label for="open_time">Jam Buka *</label>
                        <input type="time" id="open_time" name="open_time" required>
                        <span class="errorMessage" id="open_timeError"></span>
                    </div>
                    <div class="formGroup">
                        <label for="close_time">Jam Tutup *</label>
                        <input type="time" id="close_time" name="close_time" required>
                        <span class="errorMessage" id="close_timeError"></span>
                    </div>
                </div>

                <div class="formGroup">
                    <label for="image">Foto Cabang</label>
                    <div class="fileInputWrapper">
                        <input type="file" id="image" name="image" accept="image/*">
                        <label for="image" class="fileInputLabel">
                            <span class="material-symbols-outlined">upload</span>
                            <span>Pilih Foto</span>
                        </label>
                        <span id="fileName" class="fileName"></span>
                    </div>
                    <div id="imagePreview" class="imagePreview"></div>
                    <span class="errorMessage" id="imageError"></span>
                </div>

                <div class="formGroup">
                    <label for="locationStatus">
                        Status
                        <span class="tooltipContainer">
                            <span class="tooltipTrigger material-symbols-outlined">info</span>
                            <span class="tooltipText">
                                <strong>Aktif:</strong> Lokasi ditampilkan di katalog
                                <br><strong>Draft:</strong> Lokasi disembunyikan dari katalog
                            </span>
                        </span>
                    </label>
                    <select id="locationStatus" name="is_active" required>
                        <option value="2" disabled >Pilih Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Draft</option>
                    </select>
                    <span class="errorMessage" id="statusError"></span>
                </div>

                <div class="formActions">
                    <button type="button" class="btnCancel" onclick="closeLocationModal()">Batal</button>
                    <button type="submit" class="btnSubmit">Simpan Lokasi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Confirmation -->
    <x-modal-confirmation
        id="confirmSaveModal"
        type="save"
        title="Simpan Lokasi?"
        message="Apakah Anda yakin ingin menyimpan lokasi baru ini?"
        confirmAction="confirmSaveLocation()"
        btnText="Simpan"
    />

    <x-modal-confirmation
        id="confirmUpdateModal"
        type="update"
        title="Perbarui Lokasi?"
        message="Apakah Anda yakin ingin memperbarui data lokasi ini?"
        confirmAction="confirmUpdateLocation()"
        btnText="Perbarui"
    />

    <x-modal-confirmation
        id="confirmDeleteModal"
        type="delete"
        title="Hapus Lokasi?"
        message="Tindakan ini tidak bisa dibatalkan. Yakin ingin menghapus lokasi?"
        confirmAction="confirmDeleteLocation()"
        btnColor="btnDanger"
        btnText="Hapus"
    />

    <!-- Modal Success -->
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

    <!-- Modal Error -->
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

    <script src="{{ asset('js/admin/lokasi.js') }}"></script>

@endsection