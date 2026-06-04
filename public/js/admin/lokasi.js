const locationModal = document.getElementById("locationModal");
const locationForm = document.getElementById("locationForm");
const locationId = document.getElementById("locationId");
const modalTitle = document.getElementById("modalTitle");
const image = document.getElementById("image");
const fileName = document.getElementById("fileName");
const imagePreview = document.getElementById("imagePreview");
const searchInput = document.getElementById("searchInput");
const locationStatusSelect = document.getElementById("locationStatus");

// Pending variables for confirmation
let pendingFormData = null;
let pendingIsEdit = false;
let pendingDeleteId = null;

const baseUrl = "/admin/lokasi";
const storageBaseUrl = "/storage";
const csrfToken = document.querySelector('input[name="_token"]')?.value;

// ========== MODAL FUNCTIONS ==========
function openLocationModal(id = null) {
    locationForm.reset();
    locationId.value = "";
    fileName.textContent = "";
    imagePreview.innerHTML = "";
    imagePreview.classList.remove("show");
    clearErrors();

    if (id) {
        modalTitle.textContent = "Edit Lokasi";
        loadLocationData(id);
    } else {
        modalTitle.textContent = "Tambah Lokasi Baru";
        locationStatusSelect.value = "2";
    }

    locationModal.classList.add("show");
}

function closeLocationModal() {
    locationModal.classList.remove("show");
    // Reset form after animation
    setTimeout(() => {
        locationForm.reset();
    }, 300);
}


// Add button to trigger modal
document.getElementById("btnAddLocationModal").addEventListener("click", function () {
    openLocationModal();
});

// Load location data untuk edit
async function loadLocationData(id) {
    try {
        const response = await fetch(`${baseUrl}/${id}`, {
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
        });

        if (!response.ok) {
            throw new Error("Failed to load location data");
        }

        const data = await response.json();
        locationId.value = data.id;
        document.getElementById("name").value = data.name;
        document.getElementById("address").value = data.address;
        document.getElementById("mapLink").value = data.link;

        if (data.operating_hours) {
            const [openTime, closeTime] = data.operating_hours.split("-");
            document.getElementById("open_time").value = openTime.trim();
            document.getElementById("close_time").value = closeTime.trim();
        }

        locationStatusSelect.value = data.is_active ? "1" : "0";

        if (data.image) {
            const imgPath = `${storageBaseUrl}/${data.image}`;
            imagePreview.innerHTML = `<img src="${imgPath}" alt="Preview">`;
            imagePreview.classList.add("show");
        }
    } catch (error) {
        console.error("Error loading location data:", error);
        showErrorModal("Gagal memuat data lokasi");
    }
}

// ========== CONFIRMATION MODAL FUNCTIONS ==========
function openConfirmModal(type) {
    document.getElementById(`confirm${type.charAt(0).toUpperCase() + type.slice(1)}Modal`).classList.add("active");
}

function closeConfirmModal(type) {
    document.getElementById(`confirm${type.charAt(0).toUpperCase() + type.slice(1)}Modal`).classList.remove("active");
}

function confirmSaveLocation() {
    closeConfirmModal('save');
    closeLocationModal();
    submitLocationForm(pendingFormData, false);
}

function confirmUpdateLocation() {
    closeConfirmModal('update');
    closeLocationModal();
    submitLocationForm(pendingFormData, true);
}

function confirmDeleteLocation() {
    closeConfirmModal('delete');
    submitDeleteLocation(pendingDeleteId);
}

// ========== SUCCESS/ERROR MODALS ==========
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


// File input preview
image.addEventListener("change", function (e) {
    const file = e.target.files[0];
    if (file) {
        fileName.textContent = file.name;
        const reader = new FileReader();
        reader.onload = function (e) {
            imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            imagePreview.classList.add("show");
        };
        reader.readAsDataURL(file);
    }
});

// ========== FORM SUBMISSION FUNCTIONS ==========
locationForm.addEventListener("submit", async function (e) {
    e.preventDefault();
    clearErrors();

    const openTime = document.getElementById("open_time").value;
    const closeTime = document.getElementById("close_time").value;

    // Client-side validation for time
    if (!validateTime(openTime, closeTime)) {
        return;
    }

    pendingFormData = new FormData(this);
    pendingIsEdit = locationId.value;
    console.log(Object.fromEntries(pendingFormData));

    if (pendingIsEdit) {
        openConfirmModal('update');
    } else {
        openConfirmModal('save');
    }
});

async function submitLocationForm(formData, isEdit) {
    try {
        let url;
        if (isEdit) {
            url = `${baseUrl}/${locationId.value}`;
            formData.append("_method", "PUT");
        } else {
            url = baseUrl;
        }

        const response = await fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            body: formData,
        });

        if (!response.ok) {
            const errorText = await response.text();
            console.error("HTTP Error:", response.status, errorText);
            showErrorModal(`Error: ${response.status} ${response.statusText}`);
            return;
        }

        const data = await response.json();

        if (data.success) {
            showSuccessModal(data.message || (isEdit ? "Lokasi berhasil diperbarui!" : "Lokasi berhasil disimpan!"));
        } else if (data.errors) {
            displayErrors(data.errors);
            showErrorModal("Terdapat kesalahan pada form. Silakan periksa kembali.");
        } else {
            showErrorModal(data.message || "Terjadi kesalahan saat memproses data");
        }
    } catch (error) {
        console.error("Error:", error);
        showErrorModal("Terjadi kesalahan saat menyimpan data: " + error.message);
    }
}

async function submitDeleteLocation(id) {
    try {
        const response = await fetch(`${baseUrl}/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                Accept: "application/json",
            },
        });

        if (!response.ok) {
            console.error("HTTP Error:", response.status);
            showErrorModal(`Error: ${response.status} ${response.statusText}`);
            return;
        }

        const data = await response.json();

        if (data.success) {
            showSuccessModal(data.message || "Lokasi berhasil dihapus!");
        } else {
            showErrorModal(data.message || "Terjadi kesalahan saat menghapus lokasi");
        }
    } catch (error) {
        console.error("Error:", error);
        showErrorModal("Terjadi kesalahan saat menghapus lokasi: " + error.message);
    }
}

function validateTime(openTime, closeTime) {
    // Check format HH:MM
    const timeRegex = /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/;

    if (!timeRegex.test(openTime)) {
        setError("open_timeError", "Format jam buka tidak valid (HH:MM)");
        return false;
    }

    if (!timeRegex.test(closeTime)) {
        setError("close_timeError", "Format jam tutup tidak valid (HH:MM)");
        return false;
    }

    // Compare times
    const [openHour, openMin] = openTime.split(":").map(Number);
    const [closeHour, closeMin] = closeTime.split(":").map(Number);

    const openTotalMin = openHour * 60 + openMin;
    const closeTotalMin = closeHour * 60 + closeMin;

    if (openTotalMin >= closeTotalMin) {
        setError("close_timeError", "Jam tutup harus lebih besar dari jam buka");
        return false;
    }

    return true;
}

function displayErrors(errors) {
    Object.keys(errors).forEach((field) => {
        const errorElement = document.getElementById(field + "Error");
        if (errorElement) {
            errorElement.textContent = errors[field][0];
        }
    });
}

function setError(fieldId, message) {
    const errorElement = document.getElementById(fieldId);
    if (errorElement) {
        errorElement.textContent = message;
    }
}

function clearErrors() {
    document.querySelectorAll(".errorMessage").forEach((el) => {
        el.textContent = "";
    });
}

// Edit Location
function editLocation(id) {
    openLocationModal(id);
}

// Delete Location
function deleteLocation(id) {
    pendingDeleteId = id;
    openConfirmModal('delete');
}


async function submitStatusChange(data) {
    try {
        const csrfToken = document.querySelector('input[name="_token"]')?.value;

        if (!csrfToken) {
            showErrorModal("Error: CSRF Token tidak ditemukan");
            data.element.value = data.element.dataset.currentStatus;
            return;
        }

        const isActive = data.status === "aktif" ? 1 : 0;

        const response = await fetch(`/admin/lokasi/${data.locationId}`, {
            method: "PATCH",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify({
                is_active: isActive
            })
        });

        let respData;
        try {
            respData = await response.json();
        } catch (parseError) {
            console.error("Error parsing JSON:", parseError);
            showErrorModal("Error: Respons server tidak valid");
            data.element.value = data.element.dataset.currentStatus;
            return;
        }

        if (response.ok && respData.success) {
            data.element.dataset.currentStatus = data.status;
            showSuccessModal(respData.message);
        } else {
            showErrorModal(respData.message || "Terjadi kesalahan saat mengubah status");
            data.element.value = data.element.dataset.currentStatus;
        }
    } catch (error) {
        console.error("Error:", error);
        showErrorModal("Terjadi kesalahan saat mengubah status: " + error.message);
        data.element.value = data.element.dataset.currentStatus;
    }
}

// Search functionality
searchInput.addEventListener("keyup", function () {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll("#locationTableBody tr");

    rows.forEach((row) => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? "" : "none";
    });
});
