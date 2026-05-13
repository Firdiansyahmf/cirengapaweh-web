@extends('layouts.admin')

@section('title', 'Manajemen Lokasi - Cireng A\'paweh')

@section('content')
    <div>
        <div class="pageHeader">
            <div class="headerContent">
                <h2>Manajemen Lokasi</h2>
                <p>Kelola cabang cireng A'paweh</p>
            </div>
            <div class="headerSearchBox">
                <span class="material-symbols-outlined">search</span>
                <input type="text" class="searchBox" placeholder="Cari Cabang">
            </div>
            <button class="btnAddLocation">
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
                <tbody>
                    <tr>
                        <td class="fotoCell"><img 
                                src="{{ asset('assets/img/lokasi/Tel U.jpg') }}"
                                alt="Pusat Tel U" class="locationThumb"></td>
                        <td class="namaLokasi">Pusat Tel U</td>
                        <td class="alamat">Telkom University, Sukapura, Kec. Bojongsoang</td>
                        <td class="jamOperasional">
                            <span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle;">schedule</span>
                            08:00-17:00
                        </td>
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
                                src="{{ asset('assets/img/lokasi/Tel U.jpg') }}"
                                alt="Pusat Tel U" class="locationThumb"></td>
                        <td class="namaLokasi">Pusat Tel U</td>
                        <td class="alamat">Telkom University, Sukapura, Kec. Bojongsoang</td>
                        <td class="jamOperasional">
                            <span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle;">schedule</span>
                            08:00-17:00
                        </td>
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
                    <tr>
                        <td class="fotoCell"><img 
                                src="{{ asset('assets/img/lokasi/Tel U.jpg') }}"
                                alt="Pusat Tel U" class="locationThumb"></td>
                        <td class="namaLokasi">Pusat Tel U</td>
                        <td class="alamat">Telkom University, Sukapura, Kec. Bojongsoang</td>
                        <td class="jamOperasional">
                            <span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle;">schedule</span>
                            08:00-17:00
                        </td>
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