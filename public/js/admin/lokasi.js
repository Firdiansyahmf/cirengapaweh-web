const locationModal = document.getElementById("locationModal");
const locationForm = document.getElementById("locationForm");
const locationId = document.getElementById("locationId");
const modalTitle = document.getElementById("modalTitle");
const image = document.getElementById("image");
const fileName = document.getElementById("fileName");
const imagePreview = document.getElementById("imagePreview");
const searchInput = document.getElementById("searchInput");

// Map variables
const mapModal = document.getElementById("mapModal");
const googleMap = document.getElementById("googleMap");
const mapSearchInput = document.getElementById("mapSearchInput");
const mapSearchBtn = document.getElementById("mapSearchBtn");
let map;
let marker;
let selectedPlace = null;

const baseUrl = "/admin/lokasi";
const storageBaseUrl = "/storage";
const csrfToken = document.querySelector('input[name="_token"]')?.value;

// Open Modal untuk Tambah Lokasi
document.getElementById("btnAddLocationModal").addEventListener("click", function () {
    openLocationModal();
});

function openLocationModal(id = null) {
    locationForm.reset();
    locationId.value = "";
    fileName.textContent = "";
    imagePreview.innerHTML = "";
    clearErrors();

    if (id) {
        modalTitle.textContent = "Edit Lokasi";
        loadLocationData(id);
    } else {
        modalTitle.textContent = "Tambah Lokasi Baru";
        document.getElementById("is_active").checked = false;
    }

    locationModal.classList.add("show");
}

function closeLocationModal() {
    locationModal.classList.remove("show");
    locationForm.reset();
    clearErrors();
}

// Close modal when clicking outside
locationModal.addEventListener("click", function (event) {
    if (event.target === locationModal) {
        closeLocationModal();
    }
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

        if (data.operating_hours) {
            const [openTime, closeTime] = data.operating_hours.split("-");
            document.getElementById("open_time").value = openTime.trim();
            document.getElementById("close_time").value = closeTime.trim();
        }

        document.getElementById("is_active").checked = data.is_active;

        if (data.image) {
            const imgPath = `${storageBaseUrl}/${data.image}`;
            imagePreview.innerHTML = `<img src="${imgPath}" alt="Preview">`;
            imagePreview.classList.add("show");
        }
    } catch (error) {
        console.error("Error loading location data:", error);
        alert("Gagal memuat data lokasi");
    }
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

// Initialize Leaflet Map
function initMap() {
    const defaultLocation = [-6.1751, 106.8650];

    // Create map with OpenStreetMap tiles
    map = L.map(googleMap).setView(defaultLocation, 12);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(map);

    // Click on map to select location
    map.on('click', function (e) {
        selectMapLocation(e.latlng.lat, e.latlng.lng);
    });

    mapSearchBtn.addEventListener("click", searchLocation);
    
    mapSearchInput.addEventListener("keypress", function(e) {
        if (e.key === 'Enter') {
            searchLocation();
            e.preventDefault();
        }
    });
}

function selectMapLocation(lat, lng, address = null) {
    if (marker) {
        map.removeLayer(marker);
    }

    marker = L.marker([lat, lng]).addTo(map);
    map.setView([lat, lng], 15);

    selectedPlace = {
        lat: lat,
        lng: lng,
        address: address || `${lat.toFixed(4)}, ${lng.toFixed(4)}`,
    };

    if (!address) {
        reverseGeocode(lat, lng);
    }
}

// Reverse geocoding - get address from coordinates
function reverseGeocode(lat, lng) {
    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.address) {
                selectedPlace.address = data.display_name || `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
            }
        })
        .catch(error => {
            console.error('Geocoding error:', error);
        });
}

// Forward geocoding - search location by name
function searchLocation() {
    const query = mapSearchInput.value.trim();
    if (!query) {
        alert('Masukkan nama lokasi atau alamat');
        return;
    }

    // Add country filter to Indonesia (more accurate results)
    const url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query + ', Indonesia')}&format=json&limit=1`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            // Jika ditemukan dengan filter Indonesia, gunakan hasil itu
            if (data && data.length > 0) {
                const result = data[0];
                const lat = parseFloat(result.lat);
                const lng = parseFloat(result.lon);
                selectMapLocation(lat, lng, result.display_name);
                return { found: true }; // Return nilai untuk menghindari alert
            } else {
                const fallbackUrl = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&limit=1`;
                return fetch(fallbackUrl).then(r => r.json());
            }
        })
        .then(data => {
            if (data && data.found === true) {
                return;
            }
            
            if (data && data.length > 0) {
                const result = data[0];
                const lat = parseFloat(result.lat);
                const lng = parseFloat(result.lon);
                selectMapLocation(lat, lng, result.display_name);
            } else {
                alert('Lokasi tidak ditemukan. Coba nama lain atau klik langsung di peta.');
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            alert('Error saat mencari lokasi');
        });
}

function openMapModal() {
    mapModal.classList.add("show");
    setTimeout(() => {
        if (!map) {
            initMap();
        } else {
            // Invalidate map size jika sudah ada
            map.invalidateSize();
        }
    }, 100);
}

function closeMapModal() {
    mapModal.classList.remove("show");
    selectedPlace = null;
    if (marker) {
        map.removeLayer(marker);
    }
}

function confirmMapLocation() {
    if (selectedPlace && selectedPlace.address) {
        document.getElementById("address").value = selectedPlace.address;
        closeMapModal();
    } else {
        alert("Pilih lokasi terlebih dahulu");
    }
}

// Close map modal when clicking outside
mapModal.addEventListener("click", function (event) {
    if (event.target === mapModal) {
        closeMapModal();
    }
});

// Form submission
locationForm.addEventListener("submit", async function (e) {
    e.preventDefault();
    clearErrors();

    const openTime = document.getElementById("open_time").value;
    const closeTime = document.getElementById("close_time").value;

    // Client-side validation for time
    if (!validateTime(openTime, closeTime)) {
        return;
    }

    const formData = new FormData(this);
    const isEdit = locationId.value;
    let url;

    // Ensure is_active value is properly sent (0 or 1)
    if (!formData.has("is_active")) {
        formData.set("is_active", "0");
    } else {
        formData.set("is_active", "1");
    }

    if (isEdit) {
        url = `${baseUrl}/${locationId.value}`;
        formData.append("_method", "PUT");
    } else {
        url = baseUrl;
    }

    try {
        const response = await fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            body: formData,
        });

        // Check if response is OK (status 200-299)
        if (!response.ok) {
            console.error("HTTP Error:", response.status, response.statusText);
            const errorText = await response.text();
            console.error("Response body:", errorText);
            alert(`Error: ${response.status} ${response.statusText}`);
            return;
        }

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            location.reload();
        } else if (data.errors) {
            displayErrors(data.errors);
        } else {
            alert(`Error: ${data.message}`);
        }
    } catch (error) {
        console.error("Error:", error);
        alert("Terjadi kesalahan saat menyimpan data: " + error.message);
    }
});

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
    if (confirm("Apakah Anda yakin ingin menghapus lokasi ini?")) {
        fetch(`${baseUrl}/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                Accept: "application/json",
            },
        })
            .then((response) => {
                if (!response.ok) {
                    console.error("HTTP Error:", response.status);
                    throw new Error(`HTTP Error: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(`Error: ${data.message}`);
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("Terjadi kesalahan saat menghapus lokasi: " + error.message);
            });
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
