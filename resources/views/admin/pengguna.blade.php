@extends('layouts.admin')

@section('title', 'Manajemen Admin - Cireng A\'paweh')

@section('content')
    <div>
        <div class="pageHeader">
            <div class="headerContent">
                <h2>Manajemen Admin</h2>
                <p>Kelola akses admin dashboard untuk user lain</p>
            </div>
            <div class="headerSearchBox">
                <span class="material-symbols-outlined">search</span>
                <input type="text" class="searchBox" placeholder="Cari admin">
            </div>
            <button class="btnAddAdmin">
                <span class="material-symbols-outlined">add</span>
                <span>Tambah Admin</span>
            </button>
        </div>

        <div class="tableWrapper">
            <table class="adminTable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="namaAdmin">Cahya Abdul Aziz</td>
                        <td class="emailAdmin">cahyaaziz2@upi.edu</td>
                        <td><span class="badge badgeAdministrator">Administrator</span></td>
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
                        <td class="namaAdmin">Cahya Abdul Aziz</td>
                        <td class="emailAdmin">cahyaaziz2@upi.edu</td>
                        <td><span class="badge badgeStaff">Staff</span></td>
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
                        <td class="namaAdmin">Cahya Abdul Aziz</td>
                        <td class="emailAdmin">cahyaaziz2@upi.edu</td>
                        <td><span class="badge badgeAdministrator">Administrator</span></td>
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
                        <td class="namaAdmin">Cahya Abdul Aziz</td>
                        <td class="emailAdmin">cahyaaziz2@upi.edu</td>
                        <td><span class="badge badgeStaff">Staff</span></td>
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
                        <td class="namaAdmin">Cahya Abdul Aziz</td>
                        <td class="emailAdmin">cahyaaziz2@upi.edu</td>
                        <td><span class="badge badgeAdministrator">Administrator</span></td>
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
                        <td class="namaAdmin">Cahya Abdul Aziz</td>
                        <td class="emailAdmin">cahyaaziz2@upi.edu</td>
                        <td><span class="badge badgeAdministrator">Administrator</span></td>
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
                        <td class="namaAdmin">Cahya Abdul Aziz</td>
                        <td class="emailAdmin">cahyaaziz2@upi.edu</td>
                        <td><span class="badge badgeAdministrator">Administrator</span></td>
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