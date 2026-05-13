@extends('layouts.admin')

@section('title', 'Manajemen Promo - Cireng A\'paweh')

@section('content')
    <div>
        <div class="pageHeader">
            <div class="headerContent">
                <h2>Manajemen Promo</h2>
                <p>Kelola promo produk cireng A'paweh</p>
            </div>
            <div class="headerSearchBox">
                <span class="material-symbols-outlined">search</span>
                <input type="text" class="searchBox" placeholder="Cari Promo">
            </div>
            <button class="btnAddPromo">
                <span class="material-symbols-outlined">add</span>
                <span>Tambah Promo</span>
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="statsCardsGrid">
            <div class="statCard">
                <div class="statIcon" style="background-color: var(--fdn-yellow-light);">
                    <span class="material-symbols-outlined" style="color: var(--fdn-yellow-dark); font-size: 64px;">local_offer</span>
                </div>
                <div class="statContent">
                    <h3 class="statLabel">Total Promo</h3>
                    <p class="statValue">67</p>
                </div>
            </div>

            <div class="statCard">
                <div class="statIcon" style="background-color: #c8e6c9;">
                    <span class="material-symbols-outlined" style="color: #2e7d32; font-size: 64px;">check_circle</span>
                </div>
                <div class="statContent">
                    <h3 class="statLabel">Aktif</h3>
                    <p class="statValue">67</p>
                </div>
            </div>

            <div class="statCard">
                <div class="statIcon" style="background-color: var(--fdn-red-light);">
                    <span class="material-symbols-outlined" style="color: var(--fdn-red-normal); font-size: 64px;">cancel</span>
                </div>
                <div class="statContent">
                    <h3 class="statLabel">Expired</h3>
                    <p class="statValue">3</p>
                </div>
            </div>
        </div>

        <div class="tableWrapper">
            <table class="promoTable">
                <thead>
                    <tr>
                        <th>Thumbnail</th>
                        <th>Judul Promo</th>
                        <th>Durasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fotoCell"><img
                                src="{{ asset('assets/img/produk/Cireng Isi Ayam Suwir Kuah Keju Creamy.jpg') }}"
                                alt="Paket Cheesy Chicken" class="promoThumb"></td>
                        <td class="judulPromo">Paket Cheesy Chicken</td>
                        <td class="durasi">2 Des - 1 Jan</td>
                        <td><span class="badge badgeAktif">Aktif</span></td>
                        <td class="aksiCell">
                            <button class="btnIcon btnEdit" title="Edit">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            <button class="btnIcon btnDelete" title="Hapus">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="fotoCell"><img
                                src="{{ asset('assets/img/produk/Cireng Isi Ayam Suwir Kuah Keju Creamy.jpg') }}"
                                alt="Paket Cheesy Chicken" class="promoThumb"></td>
                        <td class="judulPromo">Paket buy 2 get 1</td>
                        <td class="durasi">1 Jan - 30 Jan</td>
                        <td><span class="badge badgeExpired">Expired</span></td>
                        <td class="aksiCell">
                            <button class="btnIcon btnEdit" title="Edit">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            <button class="btnIcon btnDelete" title="Hapus">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="fotoCell"><img
                                src="{{ asset('assets/img/produk/Cireng Isi Ayam Suwir Kuah Keju Creamy.jpg') }}"
                                alt="Paket Cheesy Chicken" class="promoThumb"></td>
                        <td class="judulPromo">Paket Cheesy Chicken</td>
                        <td class="durasi">2 Des - 1 Jan</td>
                        <td><span class="badge badgeAktif">Aktif</span></td>
                        <td class="aksiCell">
                            <button class="btnIcon btnEdit" title="Edit">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            <button class="btnIcon btnDelete" title="Hapus">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="fotoCell"><img
                                src="{{ asset('assets/img/produk/Cireng Isi Ayam Suwir Kuah Keju Creamy.jpg') }}"
                                alt="Paket Cheesy Chicken" class="promoThumb"></td>
                        <td class="judulPromo">Paket Cheesy Chicken</td>
                        <td class="durasi">2 Des - 1 Jan</td>
                        <td><span class="badge badgeAktif">Aktif</span></td>
                        <td class="aksiCell">
                            <button class="btnIcon btnEdit" title="Edit">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            <button class="btnIcon btnDelete" title="Hapus">
                                <span class="material-symbols-outlined">delete</span>
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
    </div>
@endsection