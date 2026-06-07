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
                payBtn.classList.add('disabled');
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
            payBtn.classList.add('disabled');
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
                    const discount = parseInt(totalBillEl.getAttribute("data-discount") || "0");
                    const total = subtotal + admin + cost - discount;
                    totalBillEl.innerText = formatRupiah(total);
                }
                if (payBtn) {
                    payBtn.disabled = false;
                    payBtn.classList.remove('disabled');
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
            const subtotal = parseInt(totalBillEl.getAttribute("data-subtotal") || "0");
            const admin = parseInt(totalBillEl.getAttribute("data-admin") || "0");
            const discount = parseInt(totalBillEl.getAttribute("data-discount") || "0");
            totalBillEl.innerText = formatRupiah(subtotal + admin - discount);
        }
        if (payBtn) {
            payBtn.disabled = true;
            payBtn.classList.add('disabled');
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

            item.innerText = `${postalCode} - ${clearName}`

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

    // Promo Code Handler
    const inputPromo = document.getElementById("inputPromo");
    const btnPromo = document.getElementById("btnPromo");
    
    if (btnPromo && inputPromo) {
        btnPromo.addEventListener("click", function () {
            const promoCode = inputPromo.value.trim();
            if (!promoCode) {
                alert("Silakan masukkan kode promo terlebih dahulu.");
                return;
            }
            
            // disable temporarily to prevent double click
            btnPromo.disabled = true;
            btnPromo.innerText = "Memproses...";
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                || document.querySelector('input[name="_token"]')?.value;
                
            fetch("/checkout/promo/validate", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({ promo_code: promoCode })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert("promo berhasil dipakai");
                    
                    // add disabled classes and attributes
                    inputPromo.classList.add("disabled");
                    inputPromo.readOnly = true;
                    btnPromo.classList.add("disabled");
                    btnPromo.disabled = true;
                    btnPromo.innerText = "Terpakai";
                    
                    // add new html in ringkasan
                    const summaryDetail = document.querySelector(".summaryDetail");
                    if (summaryDetail) {
                        let promoRow = document.getElementById("promoDiscountRow");
                        if (!promoRow) {
                            promoRow = document.createElement("div");
                            promoRow.className = "flexRow";
                            promoRow.id = "promoDiscountRow";
                            if (summaryDetail.children.length > 0) {
                                if (summaryDetail.children.length > 1) {
                                    summaryDetail.insertBefore(promoRow, summaryDetail.children[1]);
                                } else {
                                    summaryDetail.appendChild(promoRow);
                                }
                            } else {
                                summaryDetail.appendChild(promoRow);
                            }
                        }
                        promoRow.innerHTML = `
                            <span class="caption">Potongan Harga (${data.promo_code})</span>
                            <span class="bodyMain primaryBrandRed" id="promoDiscountValue">-${formatRupiah(data.discount_amount)}</span>
                        `;
                    }
                    
                    // update total tagihan
                    const totalBillEl = document.getElementById("totalBill");
                    if (totalBillEl) {
                        totalBillEl.setAttribute("data-discount", data.discount_amount);
                        const subtotal = parseInt(totalBillEl.getAttribute("data-subtotal") || "0");
                        const admin = parseInt(totalBillEl.getAttribute("data-admin") || "0");
                        const shippingCostEl = document.getElementById("shippingCost");
                        let shippingCost = 0;
                        if (shippingCostEl) {
                            const text = shippingCostEl.innerText.replace(/[^0-9]/g, "");
                            if (text) {
                                shippingCost = parseInt(text) || 0;
                            }
                        }
                        const total = subtotal + admin + shippingCost - data.discount_amount;
                        totalBillEl.innerText = formatRupiah(total);
                    }
                } else {
                    throw new Error(data.message || "Promo tidak ada atau kadaluarsa");
                }
            })
            .catch(err => {
                console.error("Promo validation error:", err);
                alert(err.message || "promo tidak ada atau kadaluarsa");
                btnPromo.disabled = false;
                btnPromo.innerText = "Gunakan";
            });
        });
    }
});
