document.addEventListener("DOMContentLoaded", () => {
    const searchBar = document.getElementById("searchBar");
    const searchDropdown = document.getElementById("searchDropdown");
    const btnSearchHero = document.getElementById("btnSearchHero");
    let selectedProductUrl = null;
    let debounceTimer;

    searchBar.addEventListener("input", function () {
        clearTimeout(debounceTimer);
        const keyword = this.value.trim();
        selectedProductUrl = null;
        if (keyword.length === 0) {
            searchDropdown.style.display = "none";
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`/api/search-products?keyword=${encodeURIComponent(keyword)}`)
                .then(response => response.json())
                .then(data => {
                    searchDropdown.innerHTML = "";
                    searchDropdown.style.display = "flex";

                    if (data.length === 0) {
                        searchDropdown.innerHTML = `<div class="searchDropdownEmpty">Tidak ada produk ditemukan</div>`;
                    } else {
                        data.forEach(product => {
                            const item = document.createElement("div");
                            item.className = "searchDropdownItem";
                            item.innerText = product.name;
                            item.addEventListener("click", () => {
                                searchBar.value = product.name;
                                selectedProductUrl = `/produk?id= .${product.id}`;
                                searchDropdown.style.display = "none";
                            });
                            searchDropdown.appendChild(item);
                        });
                    }
                })
                .catch(err => console.error("Error fetching products:", err));
        }, 300);
    });

    // logic hide tooltip user not focus
    document.addEventListener("click", (e) => {
        if (!searchBar.contains(e.target) && !searchDropdown.contains(e.target)) {
            searchDropdown.style.display = "none";
        }
    });

    // btn
    btnSearchHero.addEventListener("click", () => {
        if (selectedProductUrl) {
            window.location.href = selectedProductUrl;
        } else {
            searchBar.focus();
            searchBar.style.border = "2px solid var(--primary-brand-red)";
            searchBar.value = "";
            searchBar.placeholder = "Pilih produk dari daftar!";
            setTimeout(() => {
                searchBar.style.border = "";
                searchBar.placeholder = "Cari menu favoritmu di sini...";
            }, 1500);
        }
    });

    // logic key: enter
    searchBar.addEventListener("keypress", (e) => {
        if (e.key === "Enter") {
            btnSearchHero.click();
        }
    });
});
