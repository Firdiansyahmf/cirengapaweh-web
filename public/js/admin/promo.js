const promoModal = document.getElementById("promoModal");
const promoForm = document.getElementById("promoForm");
const promoId = document.getElementById("promoId");
const modalTitle = document.getElementById("modalTitle");

let pendingFormData = null;
let pendingIsEdit = false;
let pendingDeleteId = null;

function openPromoModal(id = null) {
    promoForm.reset();
    promoId.value = "";

    if (id) {
        modalTitle.textContent = "Edit Promo";
        loadPromoData(id);
    } else {
        modalTitle.textContent = "Tambah Promo";
        document.getElementById("promoStatus").value = "";
        promoModal.classList.add("show");
    }
}

function closePromoModal() {
    promoModal.classList.remove("show");
    setTimeout(() => {
        promoForm.reset();
    }, 300);
}


document.getElementById("btnAddPromoModal").addEventListener("click", function () {
    openPromoModal();
});

function openConfirmModal(type) {
    document.getElementById(`confirm${type.charAt(0).toUpperCase() + type.slice(1)}Modal`).classList.add("active");
}

function closeConfirmModal(type) {
    document.getElementById(`confirm${type.charAt(0).toUpperCase() + type.slice(1)}Modal`).classList.remove("active");
}

function confirmSavePromo() {
    closeConfirmModal('save');
    closePromoModal();
    submitPromoForm(pendingFormData, false);
}

function confirmUpdatePromo() {
    closeConfirmModal('update');
    closePromoModal();
    submitPromoForm(pendingFormData, true);
}

function confirmDeletePromo() {
    closeConfirmModal('delete');
    submitDeletePromo(pendingDeleteId);
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

promoForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    console.log("Form submitted triggered");

    clearErrors();

    const title = document.getElementById("promoTitle").value;
    const promoCode = document.getElementById("promoCode").value;
    const discount_percentage = document.getElementById("promoDiscount").value;
    const maxUsage = document.getElementById("promoMaxUsage").value;
    const description = document.getElementById("promoDescription").value;
    const start_date = document.getElementById("promoStartDate").value;
    const end_date = document.getElementById("promoEndDate").value;
    const status = document.getElementById("promoStatus").value;
    const formData = new FormData(this);
    const productIds = tomSelectInstance ? tomSelectInstance.getValue() : [];
    
    
    if (!title) {
        showError("promoTitle", "Judul promo wajib diisi");
        return;
    }

    const promoType = document.getElementById("promoType").value;
    if (!promoType) {
        showError("promoType", "Tipe promo wajib dipilih");
        return;
    }

    if (promoCode && promoCode.length < 5) {
        showErrorModal("Kode promo harus minimal 5 karakter");
        return;
    }

    if (!maxUsage || maxUsage <= 0) {
        showErrorModal("Maksimal penggunaan harus lebih dari 0");
        return;
    }

    if (!discount_percentage || discount_percentage <= 0 || discount_percentage > 100) {
        showErrorModal("Persentase diskon harus antara 1 dan 100");
        return;
    }

    if (start_date && end_date) {
        const startDate = new Date(start_date);
        const endDate = new Date(end_date);

        if (endDate <= startDate) {
            showErrorModal("Tanggal berakhir harus setelah tanggal mulai");
            return;
        }
    } else {
        showErrorModal("Tanggal mulai dan tanggal berakhir wajib diisi");
        return;
    }

    if (productIds.length === 0) {
        showErrorModal("Pilih minimal satu produk");
        return;
    }

    if (!status) {
        showError("promoStatus", "Status promo wajib dipilih");
        return;
    }

    console.log("All validations passed, preparing to submit form");
    console.log("Form data:", formData);
    pendingFormData = new FormData(this);
    pendingFormData.set("product_ids", JSON.stringify(productIds));
    pendingIsEdit = promoId.value;

    closePromoModal();

    if (pendingIsEdit) {
        openConfirmModal('update');
    } else {
        openConfirmModal('save');
    }
});

async function submitPromoForm(formData, isEdit) {
    let url;

    const data = {
        title: formData.get("title"),
        promo_code: formData.get("promo_code") || null,
        description: formData.get("description") || null,
        promo_type: formData.get("promo_type"),
        discount_percentage: parseInt(formData.get("discount_percentage")),
        max_usage: parseInt(formData.get("max_usage")),
        start_date: formData.get("start_date"),
        end_date: formData.get("end_date"),
        is_active: formData.get("is_active") === "1",
        product_ids: JSON.parse(formData.get("product_ids")),
    };

    if (isEdit) {
        url = `/admin/promo/${promoId.value}`;
    } else {
        url = `/admin/promo`;
    }

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        if (!csrfToken) {
            showErrorModal("Error: CSRF Token tidak ditemukan");
            return;
        }

        const response = await fetch(url, {
            method: isEdit ? "PUT" : "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        });

        let respData;
        try {
            respData = await response.json();
        } catch (parseError) {
            console.error("Error parsing JSON:", parseError);
            showErrorModal("Error: Respons server tidak valid");
            return;
        }

        if (response.ok && respData.success) {
            closePromoModal();
            showSuccessModal(respData.message);
        } else if (response.status === 422 && respData.errors) {
            let errorMessage = "Validasi gagal:\n";
            for (const [field, messages] of Object.entries(respData.errors)) {
                errorMessage += `- ${field}: ${messages[0]}\n`;
            }
            showErrorModal(errorMessage);
        } else {
            showErrorModal(respData.message || "Terjadi kesalahan saat menyimpan promo");
        }
    } catch (error) {
        console.error("Fetch error:", error);
        showErrorModal("Terjadi kesalahan saat menyimpan promo: " + error.message);
    }
}

function loadPromoData(id) {
    fetch(`/admin/promo/${id}/edit`)
        .then(r => r.json())
        .then(promo => {
            document.getElementById("promoTitle").value = promo.title || '';
            document.getElementById("promoCode").value = promo.promo_code || '';
            document.getElementById("promoDescription").value = promo.description || '';
            document.getElementById("promoType").value = promo.promo_type || 'otomatis';
            document.getElementById("promoDiscount").value = promo.discount_percentage || 0;
            document.getElementById("promoMaxUsage").value = promo.max_usage || 100;

            let startDateValue = '';
            let endDateValue = '';

            if (promo.start_date) {
                if (typeof promo.start_date === 'string') {
                    startDateValue = promo.start_date.split(' ')[0];
                } else {
                    startDateValue = promo.start_date;
                }
            }

            if (promo.end_date) {
                if (typeof promo.end_date === 'string') {
                    endDateValue = promo.end_date.split(' ')[0]; 
                } else {
                    endDateValue = promo.end_date;
                }
            }

            document.getElementById("promoStartDate").value = startDateValue;
            document.getElementById("promoEndDate").value = endDateValue;
            document.getElementById("promoStatus").value = promo.is_active ? "1" : "0";

            promoId.value = id;

            const productIds = promo.products.map(p => p.id.toString());
            if (tomSelectInstance) {
                tomSelectInstance.setValue(productIds);
            }

            promoModal.classList.add("show");
        })
        .catch(error => {
            console.error('Error loading promo data:', error);
            showErrorModal('Gagal memuat data promo');
        });
}

function editPromo(id) {
    openPromoModal(id);
}

async function deletePromo(id) {
    pendingDeleteId = id;
    openConfirmModal('delete');
}

async function submitDeletePromo(id) {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        if (!csrfToken) {
            showErrorModal("Error: CSRF Token tidak ditemukan");
            return;
        }

        const response = await fetch(`/admin/promo/${id}`, {
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
            showErrorModal(data.message || "Terjadi kesalahan saat menghapus promo");
        }
    } catch (error) {
        console.error("Error:", error);
        showErrorModal("Terjadi kesalahan saat menghapus promo: " + error.message);
    }
}

document.getElementById("searchInput").addEventListener("keyup", function (e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll("#promoTableBody tr");

    rows.forEach((row) => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? "" : "none";
    });
});

let tomSelectInstance = null;

function loadProductsToSelect() {
    fetch('/admin/promo/get-products')
        .then(response => response.json())
        .then(products => {
            const productSelect = document.getElementById("product_ids");
            productSelect.innerHTML = '';

            products.forEach(product => {
                const option = document.createElement('option');
                option.value = product.id;
                option.textContent = product.name;
                productSelect.appendChild(option);
            });

            initTomSelect();
        })
        .catch(error => console.error('Error loading products:', error));
}

function initTomSelect() {
    if (tomSelectInstance) {
        tomSelectInstance.destroy();
    }

    tomSelectInstance = new TomSelect('#product_ids', {
        placeholder: 'Cari dan pilih produk...',
        searchField: ['text'],
        maxItems: null,
        closeAfterSelect: false,
    });
}

document.addEventListener('DOMContentLoaded', () => {
    loadProductsToSelect();

    const startDateInput = document.getElementById("promoStartDate");
    const endDateInput = document.getElementById("promoEndDate");

    const validateDates = () => {
        if (startDateInput.value && endDateInput.value) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            if (endDate <= startDate) {
                endDateInput.setCustomValidity("Tanggal berakhir harus setelah tanggal mulai");
                endDateInput.classList.add("invalid");
            } else {
                endDateInput.setCustomValidity("");
                endDateInput.classList.remove("invalid");
            }
        }
    };

    startDateInput.addEventListener("change", validateDates);
    endDateInput.addEventListener("change", validateDates);
});
