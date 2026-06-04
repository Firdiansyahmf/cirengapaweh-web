/**
 * Pengguna (User Management) - Admin CRUD Operations
 * Handles modal interactions, form submission, and data management
 */

const userModal = document.getElementById('userModal');
const userForm = document.getElementById('userForm');
const modalTitle = document.getElementById('modalTitle');
const submitBtn = document.getElementById('submitBtn');
const hiddenUserId = document.getElementById('hiddenUserId');
const passwordGroup = document.getElementById('passwordGroup');
const passwordInput = document.getElementById('password');
const searchInput = document.getElementById('searchInput');
const tableRows = document.querySelectorAll('.userRow');

/**
 * Open modal for adding new user
 */
function openUserModal() {
    // Reset form and title
    userForm.reset();
    hiddenUserId.value = '';
    modalTitle.textContent = 'Tambah Pengguna Baru';
    submitBtn.textContent = 'Simpan Pengguna';
    
    // Show password field for new user
    passwordGroup.style.display = 'block';
    passwordInput.setAttribute('required', 'required');
    
    // Clear any previous error classes or messages
    document.querySelectorAll('.formGroup').forEach(group => {
        group.classList.remove('error');
    });
    
    // Show modal
    userModal.classList.add('show');
}

/**
 * Close user modal
 */
function closeUserModal() {
    userModal.classList.remove('show');
    userForm.reset();
    passwordInput.removeAttribute('required');
}

/**
 * Edit existing user - populate form with user data
 */
function editUser(userId) {
    // Get user data from the table row
    const row = document.querySelector(`.userRow[data-id="${userId}"]`);
    if (!row) {
        alert('Pengguna tidak ditemukan');
        return;
    }
    
    // Extract data from row
    const name = row.querySelector('.namaPengguna').textContent;
    const email = row.querySelector('.emailPengguna').textContent;
    const role = row.querySelector('[data-role]').getAttribute('data-role');
    const isActive = row.querySelector('[data-active]').getAttribute('data-active') === '1';
    
    // Populate form
    document.getElementById('name').value = name;
    document.getElementById('email').value = email;
    document.getElementById('role').value = role;
    document.getElementById('isActive').checked = isActive;
    hiddenUserId.value = userId;
    
    // Hide password field for edit (optional)
    passwordGroup.style.display = 'block';
    const passwordLabel = passwordGroup.querySelector('label');
    if (!passwordLabel.textContent.includes('(opsional)')) {
        passwordLabel.textContent = 'Password (opsional)';
    }
    passwordInput.removeAttribute('required');
    passwordInput.value = ''; // Clear password field
    
    // Update modal title and button
    modalTitle.textContent = 'Edit Pengguna';
    submitBtn.textContent = 'Perbarui Pengguna';
    
    // Show modal
    userModal.classList.add('show');
}

/**
 * Delete user with confirmation
 */
function deleteUser(userId) {
    const row = document.querySelector(`.userRow[data-id="${userId}"]`);
    const userName = row.querySelector('.namaPengguna').textContent;
    
    if (!confirm(`Apakah Anda yakin ingin menghapus pengguna "${userName}"?`)) {
        return;
    }
    
    // Get CSRF token
    const token = document.querySelector('input[name="_token"]').value;
    
    fetch(`/admin/pengguna/${userId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove row from table with simple transition
            row.style.opacity = '0';
            setTimeout(() => {
                row.remove();
                // Check if table is empty
                const remainingRows = document.querySelectorAll('.userRow');
                if (remainingRows.length === 0) {
                    location.reload();
                }
            }, 150);
        } else {
            alert(data.message || 'Gagal menghapus pengguna');
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        alert('Terjadi kesalahan saat menghapus pengguna');
    });
}

/**
 * Submit user form (Add or Edit)
 */
userForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const role = document.getElementById('role').value;
    const isActive = document.getElementById('isActive').checked ? 1 : 0;
    const userId = hiddenUserId.value;
    
    // Basic validation
    if (!name || !email || !role) {
        alert('Mohon isi semua field yang wajib');
        return;
    }
    
    if (!userId && !password) {
        alert('Password wajib diisi untuk pengguna baru');
        return;
    }
    
    // Get CSRF token
    const token = document.querySelector('input[name="_token"]').value;
    
    // Prepare request data
    const requestData = {
        name: name,
        email: email,
        role: role,
        is_active: isActive
    };
    
    // Only include password if provided
    if (password) {
        requestData.password = password;
    }
    
    // Determine request method and URL
    let method = 'POST';
    let url = '/admin/pengguna';
    
    if (userId) {
        method = 'PUT';
        url = `/admin/pengguna/${userId}`;
    }
    
    // Send request
    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeUserModal();
            // Reload page to reflect changes
            location.reload();
        } else {
            if (data.errors) {
                // Display validation errors
                Object.keys(data.errors).forEach(field => {
                    const input = document.getElementById(field);
                    if (input) {
                        input.parentElement.classList.add('error');
                    }
                });
            }
            alert(data.message || 'Gagal menyimpan pengguna');
        }
    })
    .catch(error => {
        console.error('Submit error:', error);
        alert('Terjadi kesalahan saat menyimpan pengguna');
    });
});

/**
 * Search/filter users in table
 */
if (searchInput) {
    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('.userRow');
        
        tableRows.forEach(row => {
            const name = row.querySelector('.namaPengguna').textContent.toLowerCase();
            const email = row.querySelector('.emailPengguna').textContent.toLowerCase();
            
            if (name.includes(searchTerm) || email.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
}


/**
 * Close modal with Escape key
 */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && userModal.classList.contains('show')) {
        closeUserModal();
    }
});
