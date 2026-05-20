const productModal = document.getElementById("productModal");
const productForm = document.getElementById("productForm");
const productId = document.getElementById("productId");
const modalTitle = document.getElementById("modalTitle");
const productImage = document.getElementById("productImage");
const fileName = document.getElementById("fileName");
const imagePreview = document.getElementById("imagePreview");

// Open Modal untuk Tambah Produk
document
    .getElementById("btnAddProductModal")
    .addEventListener("click", function () {
        openProductModal();
    });

function openProductModal(id = null) {
    productForm.reset();
    productId.value = "";
    fileName.textContent = "";
    imagePreview.innerHTML = "";
    imagePreview.classList.remove("show");

    if (id) {
        modalTitle.textContent = "Edit Produk";
        loadProductData(id);
    } else {
        modalTitle.textContent = "Tambah Produk Baru";
        productForm.action = '{{ route("produk.store") }}';
        productForm.method = "POST";
    }

    productModal.classList.add("show");
}

function closeProductModal() {
    productModal.classList.remove("show");
    productForm.reset();
}

// Close modal when clicking outside
productModal.addEventListener("click", function (event) {
    if (event.target === productModal) {
        closeProductModal();
    }
});

// File input preview
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

// Form submission
productForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const isEdit = productId.value;
    let url;

    // Ensure is_active value is properly sent (0 or 1)
    if (!formData.has("is_active")) {
        formData.append("is_active", "0");
    } else {
        formData.set("is_active", "1");
    }

    if (isEdit) {
        url = `/admin/produk/${productId.value}`;
        formData.append("_method", "PUT");
    } else {
        url = `/admin/produk`;
    }

    try {
        const csrfToken = document.querySelector('input[name="_token"]')?.value;

        if (!csrfToken) {
            alert("Error: CSRF Token tidak ditemukan");
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
            console.error("Response status:", response.status);
            console.error("Response text:", await response.text());
            alert("Error: Respons server tidak valid");
            return;
        }

        if (response.ok && data.success) {
            alert(data.message);
            closeProductModal();
            location.reload();
        } else if (response.status === 422 && data.errors) {
            // Validation errors
            let errorMessage = "Validasi gagal:\n";
            for (const [field, messages] of Object.entries(data.errors)) {
                errorMessage += `- ${field}: ${messages[0]}\n`;
            }
            alert(errorMessage);
        } else {
            alert(
                "Error: " +
                    (data.message || "Terjadi kesalahan saat menyimpan produk"),
            );
        }
    } catch (error) {
        console.error("Fetch error:", error);
        alert("Terjadi kesalahan saat menyimpan produk: " + error.message);
    }
});

function loadProductData(id) {
    // Untuk edit, kita bisa menggunakan data yang sudah ada di tabel
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (row) {
        const cells = row.querySelectorAll("td");
        document.getElementById("productName").value = cells[1].textContent;
        document.getElementById("productPrice").value =
            cells[2].textContent.replace(/[^\d]/g, "");
        document.getElementById("productDescription").value =
            cells[3].textContent;
        document.getElementById("productCategory").value = cells[4]
            .querySelector(".badge")
            .textContent.includes("Fast")
            ? "fast_food"
            : "frozen_food";
        document.getElementById("productActive").checked = cells[5]
            .querySelector(".badge")
            .textContent.includes("Aktif");

        productId.value = id;
        productForm.action = `/admin/produk/${id}`;
    }
}

function editProduct(id) {
    openProductModal(id);
}

async function deleteProduct(id) {
    if (confirm("Apakah Anda yakin ingin menghapus produk ini?")) {
        try {
            const csrfToken = document.querySelector(
                'input[name="_token"]',
            )?.value;

            if (!csrfToken) {
                alert("Error: CSRF Token tidak ditemukan");
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
                alert("Error: Respons server tidak valid");
                return;
            }

            if (response.ok && data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(
                    "Error: " +
                        (data.message ||
                            "Terjadi kesalahan saat menghapus produk"),
                );
            }
        } catch (error) {
            console.error("Error:", error);
            alert("Terjadi kesalahan saat menghapus produk: " + error.message);
        }
    }
}

// Search functionality
document.getElementById("searchInput").addEventListener("keyup", function (e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll("#productTableBody tr");

    rows.forEach((row) => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? "" : "none";
    });
});
