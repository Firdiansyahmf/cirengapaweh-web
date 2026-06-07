@extends('layouts.admin')

@section('title', 'Manajemen Produk - Cireng A\'paweh')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/modal-confirmation.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/produk.css') }}" />
    <div>
        <div class="pageHeader">
            <div class="headerContent">
                <h2>Manajemen Produk</h2>
                <p>Kelola tipe produk Cireng A'paweh</p>
            </div>
            <div class="headerSearchBox">
                <span class="material-symbols-outlined">search</span>
                <input type="text" class="searchBox" placeholder="Cari Produk" id="searchInput">
            </div>
            <button class="btnAddProduct" id="btnAddProductModal">
                <span class="material-symbols-outlined">add</span>
                <span>Tambah Produk</span>
            </button>
        </div>

        <div class="tableWrapper">
            <table class="productTable">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Deskripsi</th>
                        <th class="centerAlign info">Kategori</th>
                        <th class="centerAlign info">Status</th>
                        <th class="centerAlign info">Aksi</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    @forelse($products as $product)
                        <tr data-id="{{ $product->id }}">
                            <td class="fotoCell">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="productThumb">
                                @else
                                    <img src="{{ asset('assets/img/placeholder.jpg') }}" alt="Placeholder" class="productThumb">
                                @endif
                            </td>
                            <td class="namaProduk">{{ $product->name }}</td>
                            <td class="hargaProduk"><strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong></td>
                            <td class="deskripsi">{{ Str::limit($product->description, 50) }}</td>
                            <td class="centerAlign">
                                @if($product->category === 'fast_food')
                                    <span class="badge badgeCategory">Fast Food</span>
                                @else
                                    <span class="badge badgeCategory badgeYellow">Frozen Food</span>
                                @endif
                            </td>
                            <td>
                                @if($product->is_active)
                                    <span class="badge badgeAktif">Aktif</span>
                                @else
                                    <span class="badge badgeNonaktif">Draft</span>
                                @endif
                            </td>
                            <td class="centerAlign">
                                <div class="aksiCell">
                                    <button class="btnIcon btnEdit" title="Edit" onclick="editProduct({{ $product->id }})">
                                        <span class="material-symbols-outlined">edit</span>
                                    </button>   
                                    <button class="btnIcon btnDelete" title="Hapus" onclick="deleteProduct({{ $product->id }})">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="kosong">
                                <p style="color: var(--charcoal-grey);">Belum ada produk. Klik "Tambah Produk" untuk menambahkan produk baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
            <div class="paginationWrapper">
                @if ($products->onFirstPage())
                    <button class="paginationBtn paginationPrev" disabled>
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                @else
                    <a href="{{ $products->previousPageUrl() }}" class="paginationBtn paginationPrev">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </a>
                @endif

                <div class="paginationNumbers">
                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        @if ($page == $products->currentPage())
                            <button class="paginationNumber active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="paginationNumber">{{ $page }}</a>
                        @endif
                    @endforeach
                </div>

                @if ($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="paginationBtn paginationNext">
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

    {{-- Modal Form Produk --}}
    <div id="productModal" class="modal">
        <div class="modalContent">
            <div class="modalHeader">
                <h3 id="modalTitle">Tambah Produk Baru</h3>
                <button class="closeModal" onclick="closeProductModal()">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form id="productForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="productId" name="product_id" value="">

                <div class="formGroup">
                    <label for="productName">Nama Produk *</label>
                    <input type="text" id="productName" name="name" placeholder="Masukkan nama produk" required>
                </div>

                <div class="formRow">
                    <div class="formGroup">
                        <label for="productPrice">Harga (Rp) *</label>
                        <input type="number" id="productPrice" name="price" placeholder="0" min="0" required>
                    </div>

                    <div class="formGroup">
                        <label for="productCategory">Kategori *</label>
                        <select id="productCategory" name="category" required>
                            <option value="">Pilih Kategori</option>
                            <option value="fast_food">Fast Food</option>
                            <option value="frozen_food">Frozen Food</option>
                        </select>
                    </div>
                </div>

                <div class="formGroup">
                    <label for="productDescription">Deskripsi</label>
                    <textarea id="productDescription" name="description" placeholder="Masukkan deskripsi produk" rows="4"></textarea>
                </div>

                <div class="formGroup">
                    <label for="productImage">Foto Produk</label>
                    <div class="fileInputWrapper">
                        <input type="file" id="productImage" name="image" accept="image/*">
                        <label for="productImage" class="fileInputLabel">
                            <span class="material-symbols-outlined">upload</span>
                            <span>Pilih Foto</span>
                        </label>
                        <span id="fileName" class="fileName"></span>
                    </div>
                    <div id="imagePreview" class="imagePreview"></div>
                </div>

                <div class="formGroup">
                    <label for="productStatus">
                        Status
                        <span class="tooltipContainer">
                            <span class="tooltipTrigger material-symbols-outlined">info</span>
                            <span class="tooltipText">
                                <strong>Aktif:</strong> Produk ditampilkan di katalog
                                <br><strong>Draft:</strong> Produk disembunyikan dari katalog
                            </span>
                        </span>
                    </label>
                    <select id="productStatus" name="is_active" required>
                        <option value="2" disabled >Pilih Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Draft</option>
                    </select>
                </div>

                <div class="formActions">
                    <button type="button" class="btnCancel" onclick="closeProductModal()">Batal</button>
                    <button type="submit" class="btnSubmit">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>

 <x-modal-confirmation 
    id="confirmSaveModal" 
    type="save" 
    title="Simpan Produk?" 
    message="Apakah Anda yakin ingin menyimpan produk baru ini?" 
    confirmAction="confirmSaveProduct()" 
    btnText="Simpan" 
/>

<x-modal-confirmation 
    id="confirmUpdateModal" 
    type="update" 
    title="Perbarui Produk?" 
    message="Apakah Anda yakin ingin memperbarui data produk ini?" 
    confirmAction="confirmUpdateProduct()" 
    btnText="Perbarui" 
/>

<x-modal-confirmation 
    id="confirmDeleteModal" 
    type="delete" 
    title="Hapus Produk?" 
    message="Tindakan ini tidak bisa dibatalkan. Yakin ingin menghapus produk?" 
    confirmAction="confirmDeleteProduct()" 
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

        <script src="{{ asset('js/admin/produk.js') }}"></script>

@endsection
