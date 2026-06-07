const productModal = document.getElementById("productModal");
const productForm = document.getElementById("productForm");
const productId = document.getElementById("productId");
const modalTitle = document.getElementById("modalTitle");
const productImage = document.getElementById("productImage");
const fileName = document.getElementById("fileName");
const imagePreview = document.getElementById("imagePreview");

let pendingFormData = null;
let pendingIsEdit = false;
let pendingDeleteId = null;
let pendingStatusData = null;

//  MODAL FUNCTIONS 
function openProductModal(id = null) {
    productForm.reset();
    productId.value = "";
    fileName.textContent = "";
    imagePreview.innerHTML = "";
    imagePreview.classList.remove("show");

    if (id) {
        modalTitle.textContent = "Edit Produk";
        loadProductData(id); // Di dalam sini nanti status otomatis berubah sesuai data produk yang diedit
    } else {
        modalTitle.textContent = "Tambah Produk Baru";
        productForm.action = '{{ route("admin.produk.store") }}';
        productForm.method = "POST";

        document.getElementById("productStatus").value = "2";
    }

    productModal.classList.add("show");
}

function closeProductModal() {
    productModal.classList.remove("show");
    // Reset form after animation
    setTimeout(() => {
        productForm.reset();
    }, 300);
}


// Add button to trigger modal
document
    .getElementById("btnAddProductModal")
    .addEventListener("click", function () {
        openProductModal();
    });

//CONFIRM MODAL FUNCTIONS 
function openConfirmModal(type) {
    document.getElementById(`confirm${type.charAt(0).toUpperCase() + type.slice(1)}Modal`).classList.add("active");
}

function closeConfirmModal(type) {
    document.getElementById(`confirm${type.charAt(0).toUpperCase() + type.slice(1)}Modal`).classList.remove("active");
}

function confirmSaveProduct() {
    closeConfirmModal('save');
    closeProductModal();
    submitProductForm(pendingFormData, false);
}

function confirmUpdateProduct() {
    closeConfirmModal('update');
    closeProductModal();
    submitProductForm(pendingFormData, true);
}

function confirmDeleteProduct() {
    closeConfirmModal('delete');
    submitDeleteProduct(pendingDeleteId);
}

function confirmStatusChange() {
    closeConfirmModal('status');
    if (pendingStatusData) {
        submitStatusChange(pendingStatusData);
    }
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

function showError(fieldId, message) {
    const errorElement = document.getElementById(fieldId + "Error");
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = "block";
    } else {
        showErrorModal(message);
    }
}

function clearErrors() {
    document.querySelectorAll(".errorMessage").forEach((el) => {
        el.textContent = "";
        el.style.display = "none";
    });
}

productImage.addEventListener("change", function (e) {
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

productForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    console.log("form submitted triggered!");
    clearErrors(); 

    const name = document.getElementById("productName").value;
    const description = document.getElementById("productDescription").value;
    const category = document.getElementById("productCategory").value;
    const price = document.getElementById("productPrice").value;
    const status = document.getElementById("productStatus").value;

    let hasError = false;
    if (!name) {
        showError("productName", "Nama produk wajib diisi");
        hasError = true;
    }
    if (!description) {
        showError("productDescription", "Deskripsi produk wajib diisi");
        hasError = true;
    }
    if (!category) {
        showError("productCategory", "Kategori produk wajib dipilih");
        hasError = true;
    }
    if (!price || isNaN(price) || price <= 0) {
        showError("productPrice", "Harga produk harus berupa angka positif");
        hasError = true;
    }
    if (status === "2") {
        showError("productStatus", "Status produk wajib dipilih");
        hasError = true;
    }

    if (hasError) {
        return; 
    }

    console.log("Client-side validation passed, preparing form data...");
    pendingFormData = new FormData(this);
    pendingIsEdit = productId.value;

    const statusValue = document.getElementById("productStatus").value;
    pendingFormData.set("is_active", statusValue);

        closeProductModal();

    if (pendingIsEdit) {
        openConfirmModal('update');
    } else {
        openConfirmModal('save');
    }
});

async function submitProductForm(formData, isEdit) {
    let url;

    if (isEdit) {
        url = `/admin/produk/${productId.value}`;
        formData.append("_method", "PUT");
    } else {
        url = `/admin/produk`;
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
            closeProductModal();
            showSuccessModal(data.message);
        } else if (response.status === 422 && data.errors) {
            let errorMessage = "Validasi gagal:\n";
            for (const [field, messages] of Object.entries(data.errors)) {
                errorMessage += `- ${field}: ${messages[0]}\n`;
            }
            showErrorModal(errorMessage);
        } else {
            showErrorModal(data.message || "Terjadi kesalahan saat menyimpan produk");
        }
    } catch (error) {
        console.error("Fetch error:", error);
        showErrorModal("Terjadi kesalahan saat menyimpan produk: " + error.message);
    }
}

function loadProductData(id) {
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (row) {
        const cells = row.querySelectorAll("td");

        document.getElementById("productName").value = cells[1]?.textContent?.trim() || "";
        document.getElementById("productPrice").value =
        cells[2]?.textContent?.replace(/[^\d]/g, "") || 0;
        document.getElementById("productDescription").value =
            cells[3]?.dataset?.descriptionFull?.trim() || "";

        const categoryText = cells[4]?.querySelector("span")?.textContent?.trim();
        document.getElementById("productCategory").value =
            (categoryText || "")
                .toLowerCase()
                .replace(/\s+/g, "_") || "fast_food";

        const statusText = cells[5]?.textContent?.trim().toLowerCase();
        const statusValue = statusText === "aktif" ? "1" : "0";
        document.getElementById("productStatus").value = statusValue;

        const thumbImg = row.querySelector("td.fotoCell img.productThumb");
        imagePreview.innerHTML = "";
        imagePreview.classList.remove("show");

        if (thumbImg && thumbImg.getAttribute("src")) {
            imagePreview.innerHTML = `<img src="${thumbImg.getAttribute("src")}" alt="Preview">`;
            imagePreview.classList.add("show");
        } else {
            imagePreview.innerHTML = "";
            imagePreview.classList.remove("show");
        }

        productId.value = id;
        productForm.action = `/admin/produk/${id}`;
    }}

function editProduct(id) {
    openProductModal(id);
}

async function deleteProduct(id) {
    pendingDeleteId = id;
    openConfirmModal('delete');
}

async function submitDeleteProduct(id) {
    try {
        const csrfToken = document.querySelector('input[name="_token"]')?.value;

        if (!csrfToken) {
            showErrorModal("Error: CSRF Token tidak ditemukan");
            return;
        }

        const response = await fetch(`/admin/produk/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Content-Type": "application/json",
            },
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
            showSuccessModal(data.message);
        } else {
            showErrorModal(data.message || "Terjadi kesalahan saat menghapus produk");
        }
    } catch (error) {
        console.error("Error:", error);
        showErrorModal("Terjadi kesalahan saat menghapus produk: " + error.message);
    }
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

        const response = await fetch(`/admin/produk/${data.productId}`, {
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

document.getElementById("searchInput").addEventListener("keyup", function (e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll("#productTableBody tr");

    rows.forEach((row) => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? "" : "none";
    });
});