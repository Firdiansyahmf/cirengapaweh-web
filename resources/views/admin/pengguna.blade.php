@extends('layouts.admin')

@section('title', 'Manajemen Pengguna - Cireng A\'paweh')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/modal-confirmation.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/pengguna.css') }}" />

    <div>
        {{-- Hidden current user ID for JavaScript --}}
        <input type="hidden" id="currentUserId" value="{{ auth()->id() }}">

        <div class="pageHeader">
            <div class="headerContent">
                <h2>Manajemen Pengguna</h2>
                <p>Kelola akun admin dan staff</p>
            </div>
            <div class="headerSearchBox">
                <span class="material-symbols-outlined">search</span>
                <input type="text" class="searchBox" placeholder="Cari Pengguna" id="searchInput">
            </div>
            <button class="btnAddUser" id="btnAddUserModal">
                <span class="material-symbols-outlined">add</span>
                <span>Tambah Pengguna</span>
            </button>
        </div>

        <div class="tableWrapper">
            <table class="userTable" border="1">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th class="centerAlign">Role</th>
                        <th class="centerAlign">Status</th>
                        <th class="centerAlign">Aksi</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    @forelse($users as $user)
                        <tr class="userRow" data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-role="{{ $user->role }}" data-active="{{ $user->is_active ? '1' : '0' }}">
                            <td class="namaPengguna">{{ $user->name }}</td>
                            <td class="emailPengguna">{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'superadmin')
                                    <span class="badge badgeRole badgeSuperAdmin">Super Admin</span>
                                @else
                                    <span class="badge badgeRole badgeStaff">Staff</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge badgeStatus badgeAktif">Aktif</span>
                                @else
                                    <span class="badge badgeStatus badgeNonaktif">Nonaktif</span>
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
                                @else
                                    <div class="btnIcon btnDeleteDisable" title="Hapus">
                                        <span class="material-symbols-outlined">delete</span>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px;">
                                <p style="color: var(--charcoal-grey);">Belum ada pengguna. Klik "Tambah Pengguna" untuk menambahkan pengguna baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="paginationWrapper">
                @if ($users->onFirstPage())
                    <button class="paginationBtn paginationPrev" disabled>
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                @else
                    <a href="{{ $users->previousPageUrl() }}" class="paginationBtn paginationPrev">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </a>
                @endif

                <div class="paginationNumbers">
                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        @if ($page == $users->currentPage())
                            <button class="paginationNumber active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="paginationNumber">{{ $page }}</a>
                        @endif
                    @endforeach
                </div>

                @if ($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="paginationBtn paginationNext">
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

    {{-- Modal Password Verification --}}
    <div id="passwordVerifyModal" class="modal">
        <div class="modalContent">
            <div class="modalHeader">
                <h3>Verifikasi Password</h3>
                <button type="button" class="closeBtn" onclick="closePasswordVerifyModal()">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form id="passwordVerifyForm">
                <div class="formGroup">
                    <label for="verifyPassword">Masukkan password untuk <b>{{ auth()->user()->name }}</b>*</label>
                    <div class="passwordInputWrapper">
                        <input 
                            type="password" 
                            id="verifyPassword" 
                            name="password" 
                            placeholder="Password pengguna"
                        
                        >
                        <button type="button" class="togglePasswordBtn" onclick="togglePasswordVisibility('verifyPassword')">
                            <span class="material-symbols-outlined">visibility</span>
                        </button>
                    </div>
                </div>

                <div class="formActions">
                    <button type="button" class="btnCancel" onclick="closePasswordVerifyModal()">Batal</button>
                    <button type="submit" class="btnSubmit">Verifikasi</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Form User --}}
    <div id="userModal" class="modal">
        <div class="modalContent">
            <div class="modalHeader">
                <h3 id="modalTitle">Tambah Pengguna Baru</h3>
                <button class="closeModal" onclick="closeUserModal()">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form id="userForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="userId" name="user_id" value="">
                <input type="hidden" id="isEditMode" value="false">
                <input type="hidden" id="isEditingSelf" value="false">

                <div class="formGroup">
                    <label for="userName">Nama *</label>
                    <input type="text" id="userName" name="name" placeholder="Masukkan nama pengguna">
                </div>

                <div class="formGroup">
                    <label for="userEmail">Email *</label>
                    <input type="email" id="userEmail" name="email" placeholder="Masukkan email">
                </div>

                {{-- Password fields for add mode --}}
                <div id="addPasswordSection">
                    <div class="formGroup">
                        <label for="userPassword">Password *</label>
                        <div class="passwordInputWrapper">
                            <input type="password" id="userPassword" name="password" placeholder="Masukkan password">
                            <button type="button" class="togglePasswordBtn" onclick="togglePasswordVisibility('userPassword')">
                                <span class="material-symbols-outlined" id="userPasswordIcon">visibility</span>
                            </button>
                        </div>
                    </div>

                    <div class="formGroup">
                        <label for="userPasswordConfirm">Konfirmasi Password *</label>
                        <div class="passwordInputWrapper">
                            <input type="password" id="userPasswordConfirm" name="password_confirm" placeholder="Konfirmasi password">
                            <button type="button" class="togglePasswordBtn" onclick="togglePasswordVisibility('userPasswordConfirm')">
                                <span class="material-symbols-outlined" id="userPasswordConfirmIcon">visibility</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Password field for edit mode --}}
                <div id="editPasswordSection" style="display: none;">
                    <div class="formGroup">
                        <label for="userPasswordEdit">Password <span style="color: var(--charcoal-grey); font-weight: normal;">(opsional)</span></label>
                        <div class="passwordInputWrapper">
                            <input type="password" id="userPasswordEdit" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                            <button type="button" class="togglePasswordBtn" onclick="togglePasswordVisibility('userPasswordEdit')">
                                <span class="material-symbols-outlined" id="userPasswordEditIcon">visibility</span>
                            </button>
                        </div>
                    </div>

                    <div class="formGroup" id="editPasswordConfirmSection" style="display: none;">
                        <label for="userPasswordEditConfirm">Konfirmasi Password *</label>
                        <div class="passwordInputWrapper">
                            <input type="password" id="userPasswordEditConfirm" name="password_confirm" placeholder="Konfirmasi password baru">
                            <button type="button" class="togglePasswordBtn" onclick="togglePasswordVisibility('userPasswordEditConfirm')">
                                <span class="material-symbols-outlined" id="userPasswordEditConfirmIcon">visibility</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="formGroup">
                    <label for="userRole">Role *</label>
                    <select id="userRole" name="role">
                        <option value="">Pilih Role</option>
                        <option value="superadmin">Super Admin</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>

                <div class="checkboxGroup" id="statusCheckboxGroup">
                    <input type="checkbox" id="userIsActive" name="is_active" class="checkbox">
                    <label for="userIsActive" class="checkboxLabel">Aktif</label>
                </div>

                <div class="formActions">
                    <button type="button" class="btnCancel" onclick="closeUserModal()">Batal</button>
                    <button type="submit" id="submitBtn" class="btnSubmit">Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>

    <x-modal-confirmation 
        id="confirmSaveModal" 
        type="save" 
        title="Simpan Pengguna?" 
        message="Apakah Anda yakin ingin menyimpan pengguna baru ini?" 
        confirmAction="confirmSaveUser()" 
        btnText="Simpan" 
    />

    <x-modal-confirmation 
        id="confirmUpdateModal" 
        type="update" 
        title="Perbarui Pengguna?" 
        message="Apakah Anda yakin ingin memperbarui data pengguna ini?" 
        confirmAction="confirmUpdateUser()" 
        btnText="Perbarui" 
    />

    <x-modal-confirmation 
        id="confirmDeleteModal" 
        type="delete" 
        title="Hapus Pengguna?" 
        message="Tindakan ini tidak bisa dibatalkan. Yakin ingin menghapus pengguna?" 
        confirmAction="confirmDeleteUser()" 
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

    <script src="{{ asset('js/admin/pengguna.js') }}"></script>
@endsection
