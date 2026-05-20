@extends('layouts.admin')

@section('title', 'Manajemen Lokasi - Cireng A\'paweh')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}" />
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
                        <th>Status</th>
                        <th>Aksi</th>
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
                            <td class="aksiCell">
                                <button class="btnIcon btnEdit" title="Edit" onclick="editLocation({{ $location->id }})">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <button class="btnIcon btnDelete" title="Hapus" onclick="deleteLocation({{ $location->id }})">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
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
                        <textarea id="address" name="address" placeholder="Masukkan alamat atau cari di peta" required></textarea>
                        <button type="button" class="btnMapSearch" onclick="openMapModal()">
                            <span class="material-symbols-outlined">location_on</span>
                            <span>Cari di Peta</span>
                        </button>
                    </div>
                    <span class="errorMessage" id="addressError"></span>
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

                <div class="formGroup formCheckbox">
                    <input type="checkbox" id="is_active" name="is_active" value="1">
                    <label for="is_active">Aktifkan Lokasi</label>
                </div>

                <div class="formActions">
                    <button type="button" class="btnCancel" onclick="closeLocationModal()">Batal</button>
                    <button type="submit" class="btnSubmit">Simpan Lokasi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Map Modal untuk Google Maps -->
    <div id="mapModal" class="modal">
        <div class="mapModalContent">
            <div class="modalHeader">
                <h3>Pilih Lokasi di Peta</h3>
                <button class="closeModal" onclick="closeMapModal()">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="mapSearch">
                <input type="text" id="mapSearchInput" placeholder="Cari alamat...">
                <button type="button" id="mapSearchBtn">
                    <span class="material-symbols-outlined">search</span>
                </button>
            </div>
            <div id="googleMap" style="width: 100%; height: 400px; border-radius: 8px;"></div>
            <div class="mapActions">
                <button type="button" class="btnCancel" onclick="closeMapModal()">Batal</button>
                <button type="button" class="btnSubmit" onclick="confirmMapLocation()">Gunakan Lokasi Ini</button>
            </div>
        </div>
    </div>

    <!-- Leaflet Map Library (Free & Open Source) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    
    <script src="{{ asset('js/admin/lokasi.js') }}"></script>

@endsection