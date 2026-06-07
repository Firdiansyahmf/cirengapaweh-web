const userModal = document.getElementById("userModal");
const userForm = document.getElementById("userForm");
const userId = document.getElementById("userId");
const modalTitle = document.getElementById("modalTitle");
const submitBtn = document.getElementById("submitBtn");
const passwordVerifyModal = document.getElementById("passwordVerifyModal");
const passwordVerifyForm = document.getElementById("passwordVerifyForm");

let pendingFormData = null;
let pendingIsEdit = false;
let pendingDeleteId = null;
let currentEditingUserId = null;
let verifiedPassword = null;

function openUserModal(id = null) {
    userForm.reset();
    userId.value = "";
    verifiedPassword = null;
    
    const isEditMode = !!id;
    const isEditingSelf = id && id == getCurrentUserId();
    
    document.getElementById("isEditMode").value = isEditMode ? "true" : "false";
    document.getElementById("isEditingSelf").value = isEditingSelf ? "true" : "false";

    if (isEditMode) {
        modalTitle.textContent = "Edit Pengguna";
        loadUserData(id);
        
        // Hide add password section, show edit password section
        document.getElementById("addPasswordSection").style.display = "none";
        document.getElementById("editPasswordSection").style.display = "block";

        document.getElementById("userPassword").disabled = true;
        document.getElementById("userPasswordConfirm").disabled = true;
        document.getElementById("userPasswordEdit").disabled = false;
        document.getElementById("userPasswordEditConfirm").disabled = false;
        
        // If editing self, disable role and is_active
        if (isEditingSelf) {
            document.getElementById("userRole").disabled = true;
            document.getElementById("userIsActive").disabled = true;
        } else {
            document.getElementById("userRole").disabled = false;
            document.getElementById("userIsActive").disabled = false;
        }
    } else {
        modalTitle.textContent = "Tambah Pengguna Baru";
        document.getElementById("userRole").disabled = false;
        document.getElementById("userIsActive").disabled = false;

        document.getElementById("userIsActive").checked = true;
        
        // Show add password section, hide edit password section
        document.getElementById("addPasswordSection").style.display = "block";
        document.getElementById("editPasswordSection").style.display = "none";
        
        // Clear and require both password fields
        document.getElementById("userPassword").value = "";
        document.getElementById("userPasswordConfirm").value = "";

        document.getElementById("userPassword").disabled = false;
        document.getElementById("userPasswordConfirm").disabled = false;
        document.getElementById("userPasswordEdit").disabled = true;
        document.getElementById("userPasswordEditConfirm").disabled = true;

    }

    userModal.classList.add("show");
}

function closeUserModal() {
    userModal.classList.remove("show");
    userForm.reset();
    verifiedPassword = null;
    setTimeout(() => {
        userForm.reset();
    }, 300);
}

function closePasswordVerifyModal() {
    passwordVerifyModal.classList.remove("show");
    passwordVerifyForm.reset();
}

// Close modals when clicking outside
userModal.addEventListener("mousedown", function (event) {
    if (event.target === userModal) {
        closeUserModal();
    }
});

passwordVerifyModal.addEventListener("mousedown", function (event) {
    if (event.target === passwordVerifyModal) {
        closePasswordVerifyModal();
    }
});

// Add button to trigger modal
document.getElementById("btnAddUserModal").addEventListener("click", function () {
    openUserModal();
});

function openConfirmModal(type) {
    document.getElementById(`confirm${type.charAt(0).toUpperCase() + type.slice(1)}Modal`).classList.add("active");
}

function closeConfirmModal(type) {
    document.getElementById(`confirm${type.charAt(0).toUpperCase() + type.slice(1)}Modal`).classList.remove("active");
}

function confirmSaveUser() {
    closeConfirmModal('save');
    closeUserModal();
    submitUserForm(pendingFormData, false);
}

function confirmUpdateUser() {
    closeConfirmModal('update');
    closeUserModal();
    submitUserForm(pendingFormData, true);
}

function confirmDeleteUser() {
    closeConfirmModal('delete');
    submitDeleteUser(pendingDeleteId);
}

function showSuccessModal(message) {
    document.getElementById("successMessage").textContent = message;
    document.getElementById("successModal").classList.add("active");
}

function closeSuccessModal() {
    document.getElementById("successModal").classList.remove("active");
    location.reload();
}

function showErrorModal(message) {
    document.getElementById("errorMessage").textContent = message;
    document.getElementById("errorModal").classList.add("active");
}

function closeErrorModal() {
    document.getElementById("errorModal").classList.remove("active");
}

// Close modals when clicking outside
["confirmSaveModal", "confirmUpdateModal", "confirmDeleteModal", "successModal", "errorModal"].forEach(modalId => {
    const modal = document.getElementById(modalId);
    modal?.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.classList.remove("active");
        }
    });
});

function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + "Icon");
    
    if (field.type === "password") {
        field.type = "text";
        icon.textContent = "visibility_off";
    } else {
        field.type = "password";
        icon.textContent = "visibility";
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const editPasswordField = document.getElementById("userPasswordEdit");
    const editPasswordConfirmSection = document.getElementById("editPasswordConfirmSection");
    const editPasswordConfirmField = document.getElementById("userPasswordEditConfirm");
    
    editPasswordField?.addEventListener("input", function() {
        if (this.value.trim()) {
            editPasswordConfirmSection.style.display = "block";
            editPasswordConfirmField.required = true;
        } else {
            editPasswordConfirmSection.style.display = "none";
            editPasswordConfirmField.required = false;
            editPasswordConfirmField.value = "";
        }
    });
});

function loadUserData(id) {
    const row = document.querySelector(`.userRow[data-id="${id}"]`);
    if (!row) {
        showErrorModal("Pengguna tidak ditemukan");
        return;
    }

    const name = row.getAttribute("data-name");
    const email = row.getAttribute("data-email");
    const role = row.getAttribute("data-role");
    const isActive = row.getAttribute("data-active");

    document.getElementById("userName").value = name;
    document.getElementById("userEmail").value = email;
    document.getElementById("userRole").value = role;
    document.getElementById("userIsActive").checked = isActive === "1" || isActive === true;
    
    document.getElementById("userPasswordEdit").value = "";
    document.getElementById("userPasswordEditConfirm").value = "";
    document.getElementById("editPasswordConfirmSection").style.display = "none";
    
    userId.value = id;
    currentEditingUserId = id;
}

userForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const isEditMode = document.getElementById("isEditMode").value === "true";
    const isEditingSelf = document.getElementById("isEditingSelf").value === "true";

    const name = document.getElementById("userName").value.trim();
    const email = document.getElementById("userEmail").value.trim();

    if (!name) {
        showErrorModal("Nama harus diisi");
        return;
    }

    if (!email) {
        showErrorModal("Alamat email harus diisi");
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showErrorModal("Format email tidak valid");
        return;
    }

    if (!isEditMode) {
        const role = document.getElementById("userRole").value;
        const password = document.getElementById("userPassword").value;
        const passwordConfirm = document.getElementById("userPasswordConfirm").value;

        if (!role) {
            showErrorModal("Atur role terlebih dahulu");
            return;
        }

        if (!password) {
            showErrorModal("Password harus diisi");
            return;
        }

        if (password.length < 6) {
            showErrorModal("Password minimal harus 6 karakter");
            return;
        }

        if (password !== passwordConfirm) {
            showErrorModal("Password tidak cocok");
            return;
        }
    } else {
        // Edit mode
        if (!isEditingSelf) {
            const role = document.getElementById("userRole").value;
            if (!role) {
                showErrorModal("Atur role terlebih dahulu");
                return;
            }
        }

        const password = document.getElementById("userPasswordEdit").value;
        const passwordConfirm = document.getElementById("userPasswordEditConfirm").value;

        if (password) {
            if (password.length < 6) {
                showErrorModal("Password minimal harus 6 karakter");
                return;
            }

            if (password !== passwordConfirm) {
                showErrorModal("Password tidak cocok");
                return;
            }
        }
    }

    pendingFormData = new FormData(this);
    
    // Ensure we handle password fields correctly in FormData
    if (isEditMode) {
        const password = document.getElementById("userPasswordEdit").value;
        if (!password) {
            pendingFormData.delete('password');
        }
    }

    pendingFormData.set('is_active', document.getElementById("userIsActive").checked ? 1 : 0);
    console.log(Object.fromEntries(pendingFormData));

    pendingIsEdit = isEditMode;

    if (isEditMode) {
        openConfirmModal('update');
    } else {
        openConfirmModal('save');
    }
});

passwordVerifyForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const password = document.getElementById("verifyPassword").value;
    const csrfToken = document.querySelector('input[name="_token"]')?.value;
    const targetUserId = currentEditingUserId;

    if (!csrfToken) {
        showErrorModal("CSRF token tidak ditemukan");
        return;
    }

    if (!password) {
        showErrorModal("Masukkan password terlebih dahulu");
        return;
    }

    try {
        const response = await fetch(`/admin/pengguna/verify-password/${targetUserId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ password: password })
        });

        const data = await response.json();

        if (!response.ok) {
            showErrorModal(data.message || "Password tidak cocok");
            document.getElementById("verifyPassword").value = "";
            return;
        }

        // Password verified - store flag and proceed to edit form
        verifiedPassword = password;
        closePasswordVerifyModal();
        
        // Load the user data into the form
        openUserModal(targetUserId);

    } catch (error) {
        console.error("Error:", error);
        showErrorModal("Terjadi kesalahan saat verifikasi password");
    }
});

async function submitUserForm(formData, isEdit) {
    let url;

    if (isEdit) {
        url = `/admin/pengguna/${userId.value}`;
        formData.append("_method", "PUT");
    } else {
        url = `/admin/pengguna`;
    }

    if (verifiedPassword) {
        formData.append("password_verified", "true");
        formData.append("current_password", verifiedPassword);
    }

    try {
        const csrfToken = document.querySelector('input[name="_token"]')?.value;

        if (!csrfToken) {
            showErrorModal("Error: CSRF Token tidak ditemukan");
            return;
        }

        const response = await fetch(url, {
            method: "POST",
            headers: {
                'Accept': 'application/json',
                "X-CSRF-TOKEN": csrfToken,
            },
            body: formData,
        });

        let data;
        try {
            data = await response.json();
        } catch (parseError) {
            console.error("Error parsing JSON:", parseError);
            showErrorModal("Error: Respons server tidak valid");
            return;
        }

        if (response.ok && data.success) {
            closeUserModal();
            showSuccessModal(data.message);
        } else if (response.status === 422 && data.errors) {
            // Validation errors
            let errorMessage = "Validasi gagal:\n";
            for (const [field, messages] of Object.entries(data.errors)) {
                errorMessage += `- ${field}: ${messages[0]}\n`;
            }
            showErrorModal(errorMessage);
        } else {
            showErrorModal(data.message || "Terjadi kesalahan saat menyimpan pengguna");
        }
    } catch (error) {
        console.error("Error submitting form:", error);
        showErrorModal("Terjadi kesalahan: " + error.message);
    }
}

function deleteUser(userId) {
    const row = document.querySelector(`.userRow[data-id="${userId}"]`);
    const userName = row?.getAttribute("data-name") || "Pengguna";

    pendingDeleteId = userId;
    openConfirmModal('delete');
}

async function submitDeleteUser(userId) {
    const csrfToken = document.querySelector('input[name="_token"]')?.value;

    if (!csrfToken) {
        showErrorModal("Error: CSRF Token tidak ditemukan");
        return;
    }

    try {
        const response = await fetch(`/admin/pengguna/${userId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json",
            }
        });

        const data = await response.json();

        if (response.ok && data.success) {
            const row = document.querySelector(`.userRow[data-id="${userId}"]`);
            if (row) {
                row.style.opacity = "0";
                setTimeout(() => {
                    row.remove();
                    const remainingRows = document.querySelectorAll(".userRow");
                    if (remainingRows.length === 0) {
                        location.reload();
                    } else {
                        showSuccessModal(data.message);
                    }
                }, 150);
            }
        } else {
            showErrorModal(data.message || "Gagal menghapus pengguna");
        }
    } catch (error) {
        console.error("Error deleting user:", error);
        showErrorModal("Terjadi kesalahan saat menghapus pengguna");
    }
}

function editUser(userId) {
    const row = document.querySelector(`.userRow[data-id="${userId}"]`);
    if (!row) {
        showErrorModal("Pengguna tidak ditemukan");
        return;
    }

    const userRole = row.getAttribute("data-role");
    const currentUserIdVal = getCurrentUserId();
    const isEditingSelf = userId == currentUserIdVal;
    const isTargetSuperAdmin = userRole === "superadmin";

    currentEditingUserId = userId;

    // If editing another superadmin, show password verification modal first
    if (isTargetSuperAdmin && !isEditingSelf) {
        document.getElementById("verifyPassword").value = "";
        passwordVerifyModal.classList.add("show");
        return;
    }

    // For other users or self-edit, open edit modal directly
    openUserModal(userId);
}

function getCurrentUserId() {
    // Get from the page - you may need to add a hidden input with current user ID to the blade template
    const currentUserIdElement = document.getElementById("currentUserId");
    return currentUserIdElement ? parseInt(currentUserIdElement.value) : null;
}

//  SEARCH FUNCTIONALITY 
document.getElementById("searchInput").addEventListener("keyup", function (e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll("#userTableBody tr");

    rows.forEach((row) => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? "" : "none";
    });
});