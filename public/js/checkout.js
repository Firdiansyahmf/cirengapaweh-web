document.addEventListener("DOMContentLoaded", function () {
    const payBtn = document.getElementById("payNow");
    const paymentInputs = document.querySelectorAll(
        'input[name="payment_method"]',
    );

    const refreshButtonState = () => {
        const selected = document.querySelector(
            'input[name="payment_method"]:checked',
        );
        if (payBtn) {
            payBtn.textContent = selected
                ? `Bayar dengan ${selected.value.toUpperCase()}`
                : "Bayar Sekarang";
        }
    };

    paymentInputs.forEach((input) => {
        input.addEventListener("change", refreshButtonState);
    });

    refreshButtonState();

    if (payBtn) {
        payBtn.addEventListener("click", function (e) {
            const method = document.querySelector(
                'input[name="payment_method"]:checked',
            );
            const selected = method ? method.value : "none";

            const form = document.getElementById("checkoutForm");
            if (form && form.checkValidity()) {
                payBtn.disabled = true;
                payBtn.innerText = "Memproses...";
                payBtn.style.opacity = "0.7";
                form.submit();
            }
        });
    }

    const whatsappInput = document.getElementById("inputWa");

    if (whatsappInput) {
        whatsappInput.addEventListener("input", function () {
            this.value = this.value.replace(/[^0-9]/g, "");
        });
    }

    const addressInput = document.getElementById("inputAddress");
    const addressCounter = document.getElementById("addressCount");

    if (addressInput && addressCounter) {
        addressInput.addEventListener("input", function () {
            addressCounter.innerText = this.value.length + "/200";
        });
    }

    const postalInput = document.getElementById("inputPostal");
    const dropdown = document.getElementById("postalDropdown");

    let debounceTimer;
    let lastFetchedPostalCode = "";

    if (postalInput && dropdown) {
        postalInput.addEventListener("input", function  () {
            const query = this.value.trim();

            clearTimeout(debounceTimer);

            if (query.length < 3) {
                dropdown.innerHTML = "";
                dropdown.style.display = "none";
                resetShippingCost();
                return;
            }

            if (/^\d{5}$/.test(query)) {
                fetchShippingCost(query);
            } else {
                resetShippingCost();
            }

            debounceTimer = setTimeout(() => {
                fetch(`/v1/maps/areas?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        renderDropdown(data.areas || []);
                    })
                    .catch(err => {
                        console.error("Error fetching area dari API Biteship", err);
                    });
            }, 300);
        });
    }

    function fetchShippingCost(postalCode) {
        if (postalCode === lastFetchedPostalCode) return;
        lastFetchedPostalCode = postalCode;

        const shippingCostEl = document.getElementById("shippingCost");
        const totalBillEl = document.getElementById("totalBill");
        const payBtn = document.getElementById("payNow");

        if (shippingCostEl) {
            shippingCostEl.innerText = "Memuat...";
        }
        if (payBtn) {
            payBtn.disabled = true;
            refreshButtonState();
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
            || document.querySelector('input[name="_token"]')?.value;

        fetch("/v1/rates/couriers", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({ postal_code: postalCode })
        })
        .then(response => {
            const contentType = response.headers.get("content-type");
            if (!response.ok) {
                if (contentType && contentType.includes("application/json")) {
                    return response.json().then(err => { throw err; });
                } else {
                    throw new Error("Server error (" + response.status + ")");
                }
            }
            if (contentType && contentType.includes("application/json")) {
                return response.json();
            } else {
                throw new Error("Response server bukan JSON");
            }
        })
        .then(data => {
            if (data.success) {
                const cost = data.shipping_cost;
                if (shippingCostEl) {
                    shippingCostEl.innerText = formatRupiah(cost);
                }
                if (totalBillEl) {
                    const subtotal = parseInt(totalBillEl.getAttribute("data-subtotal") || "0");
                    const admin = parseInt(totalBillEl.getAttribute("data-admin") || "0");
                    const total = subtotal + admin + cost;
                    totalBillEl.innerText = formatRupiah(total);
                }
                if (payBtn) {
                    payBtn.disabled = false;
                    refreshButtonState();
                }
            } else {
                throw new Error(data.message || "Gagal mendapatkan estimasi");
            }
        })
        .catch(err => {
            console.error("Shipping estimate error:", err);
            if (shippingCostEl) {
                shippingCostEl.innerText = "Tidak tersedia";
            }
            alert(err.message || "Gagal mendapatkan estimasi ongkos kirim. Silakan pilih kode pos lain.");
            resetShippingCost();
        });
    }

    function resetShippingCost() {
        lastFetchedPostalCode = "";
        const shippingCostEl = document.getElementById("shippingCost");
        const totalBillEl = document.getElementById("totalBill");
        const payBtn = document.getElementById("payNow");

        if (shippingCostEl) {
            shippingCostEl.innerText = "Rp-";
        }
        if (totalBillEl) {
            totalBillEl.innerText = "Rp-";
        }
        if (payBtn) {
            payBtn.disabled = true;
            refreshButtonState();
        }
    }

    function formatRupiah(amount) {
        return "Rp" + new Intl.NumberFormat("id-ID").format(amount);
    }

    function renderDropdown(areas) {
        dropdown.innerHTML = "";

        if (areas.length === 0) {
            dropdown.style.display = "none";
            return;
        }

        areas.forEach(area =>  {
            const postalMatch = area.name.match(/\d{5}$/);
            const postalCode = area.postal_code || (postalMatch ? postalMatch[0] : '');

            const clearName = area.name.replace(/\.\s*\d{5}$/, '');

            const item = document.createElement("div");
            item.className = "autocompleteItem";

            item.innerText = `${postalCode} - ${clearName}` /* 40272 - Bandung, Jawa Barat */

            item.addEventListener("click", function () {
                postalInput.value = postalCode;

                dropdown.innerHTML = "";
                dropdown.style.display = "none";
                
                fetchShippingCost(postalCode);
            });

            dropdown.appendChild(item);
        });

        dropdown.style.display = "block";
    }

    document.addEventListener("click", function (e) {
        if (postalInput && dropdown && !postalInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.innerHTML = "";
            dropdown.style.display = "none";
        }
    });
    const nameInput = document.getElementById("inputName");
    const nameCounter = document.getElementById("nameCount");

    if (nameInput && nameCounter) {
        nameInput.addEventListener("input", function () {
            nameCounter.innerText = this.value.length + "/50";
        });
    }
});
