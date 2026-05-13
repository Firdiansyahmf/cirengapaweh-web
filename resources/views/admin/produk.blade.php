@extends('layouts.admin')

@section('title', 'Manajemen Produk - Cireng A\'paweh')

@section('content')
    <div>
        <div class="pageHeader">
            <div class="headerContent">
                <h2>Manajemen Produk</h2>
                <p>Kelola tipe produk Cireng A'paweh</p>
            </div>
            <div class="headerSearchBox">
                <span class="material-symbols-outlined">search</span>
                <input type="text" class="searchBox" placeholder="Cari Produk">
            </div>
            <button class="btnAddProduct">
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
                        <th>Deskripsi</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fotoCell"><img
                                src="{{ asset('assets/img/produk/Cireng Isi Ayam Suwir Kuah Keju Creamy.jpg') }}"
                                alt="Cireng Kuah Keju Juara" class="productThumb"></td>
                        <td class="namaProduk">Cireng Kuah Keju Juara</td>
                        <td class="deskripsi">Halo Jajaners! A'Paweh sudah siapiu perpajuaan kenyalnya acl pilihan dengan
                            siraman kuah keju lumer yang rahasia. Gak cuma pedas, tapi gurihnya bikin kamu gak bisa
                            berhenti.</td>
                        <td><span class="badge badgeCategory">Fast Food</span></td>
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
                                src="{{ asset('assets/img/produk/Frozen Cireng Isi Ayam Suwir Kuah Keju.jpg') }}"
                                alt="Frozen Cireng Isi Ayam Suwir" class="productThumb"></td>
                        <td class="namaProduk">Frozen Cireng Isi Ayam Suwir</td>
                        <td class="deskripsi">Stok camilan andalan di rumah! Tingga! goreng dadakan, nikmati cireng kenyal
                            isian ayam suwir gurih yang fresh kapan aja kamu mau.</td>
                        <td><span class="badge badgeCategory badgeYellow">Frozen Food</span></td>
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
                                alt="Cireng Kuah Keju Juara" class="productThumb"></td>
                        <td class="namaProduk">Cireng Kuah Keju Juara</td>
                        <td class="deskripsi">Halo Jajaners! A'Paweh sudah siapiu perpajuaan kenyalnya acl pilihan dengan
                            siraman kuah keju lumer yang rahasia. Gak cuma pedas, tapi gurihnya bikin kamu gak bisa
                            berhenti.</td>
                        <td><span class="badge badgeCategory">Fast Food</span></td>
                        <td><span class="badge badgeNonaktif">Nonaktif</span></td>
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
