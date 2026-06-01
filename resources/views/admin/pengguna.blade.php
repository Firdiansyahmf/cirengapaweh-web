@extends('layouts.admin')

@section('title', 'Manajemen Pengguna - Cireng A\'paweh')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/pengguna.css') }}" />

    <div>
        <div class="pageHeader">
            <div class="headerContent">
                <h2>Manajemen Pengguna</h2>
                <p>Kelola akun admin dan staff</p>
            </div>
            <div class="headerSearchBox">
                <span class="material-symbols-outlined">search</span>
                <input type="text" class="searchBox" placeholder="Cari Pengguna" id="searchInput">
            </div>
            <button class="btnAddUser" onclick="openUserModal()">
                <span class="material-symbols-outlined">add</span>
                <span>Tambah Pengguna</span>
            </button>
        </div>

        <div class="tableWrapper">
            <table class="userTable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    @forelse($users as $user)
                        <tr class="userRow" data-id="{{ $user->id }}">
                            <td class="namaPengguna">{{ $user->name }}</td>
                            <td class="emailPengguna">{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'superadmin')
                                    <span class="badge badgeRole badgeSuperAdmin" data-role="superadmin">Super Admin</span>
                                @else
                                    <span class="badge badgeRole badgeStaff" data-role="staff">Staff</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge badgeStatus badgeAktif" data-active="1">Aktif</span>
                                @else
                                    <span class="badge badgeStatus badgeNonaktif" data-active="0">Nonaktif</span>
                                @endif
                            </td>
                            <td class="aksiCell">
                                <button class="btnIcon btnEdit" title="Edit" onclick="editUser({{ $user->id }})">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                @if($user->id !== auth()->id())
                                    <button class="btnIcon btnDelete" title="Hapus" onclick="deleteUser({{ $user->id }})">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px;">
                                <p style="color: var(--fdn-grey-normal);">Tidak ada pengguna</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="paginationWrapper">
                <div class="pagination">
                    @if ($users->onFirstPage())
                        <span class="paginationBtn disabled">← Sebelumnya</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" class="paginationBtn">← Sebelumnya</a>
                    @endif

                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        @if ($page == $users->currentPage())
                            <span class="paginationBtn active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="paginationBtn">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" class="paginationBtn">Berikutnya →</a>
                    @else
                        <span class="paginationBtn disabled">Berikutnya →</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Add/Edit User -->
    <div class="modal" id="userModal">
        <div class="modalContent">
            <div class="modalHeader">
                <h3 id="modalTitle">Tambah Pengguna Baru</h3>
                <button type="button" class="closeBtn" onclick="closeUserModal()">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form class="userForm" id="userForm">
                @csrf
                <input type="hidden" id="hiddenUserId" name="user_id" value="">

                <div class="formGroup">
                    <label for="name">Nama *</label>
                    <input type="text" id="name" name="name" placeholder="Masukkan nama pengguna" required>
                </div>

                <div class="formGroup">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email" required>
                </div>

                <div class="formGroup" id="passwordGroup">
                    <label for="password">Password <span id="passwordNote"></span></label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password">
                </div>

                <div class="formGroup">
                    <label for="role">Role *</label>
                    <select id="role" name="role" required>
                        <option value="">Pilih Role</option>
                        <option value="superadmin">Super Admin</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>

                <div class="checkboxGroup">
                    <input type="checkbox" id="isActive" name="is_active" class="checkbox" checked>
                    <label for="isActive" class="checkboxLabel">Aktif</label>
                </div>

                <div class="formActions">
                    <button type="button" class="btnCancel" onclick="closeUserModal()">Batal</button>
                    <button type="submit" id="submitBtn" class="btnSave">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/admin/pengguna.js') }}"></script>
@endsection