@extends('layouts.admin')

@section('title', 'Dashboard Utama - Cireng A\'paweh')

@section('content')
    <div id="beranda" class="pageActive">
        <div class="berandaHeader">
            <div class="berandaTitle">
                <h2>Halo, Cahya!</h2>
                <p>Berikut adalah ringkasan website Cireng A'paweh</p>
            </div>
        </div>

        <div class="statsCardsGrid">
            <div class="statCard">
                <div class="statCardContent">
                    <div class="statIcon" style="background: linear-gradient(135deg, #F23D3D 0%, #DA3737 100%);">
                        <span class="material-symbols-outlined">cookie</span>
                    </div>
                    <div class="statInfo">
                        <h3 class="statLabel">Produk</h3>
                        <p class="statNumber">{{ $totalProducts }}</p>
                    </div>
                </div>
            </div>

            <div class="statCard">
                <div class="statCardContent">
                    <div class="statIcon" style="background: linear-gradient(135deg, #FFCA28 0%, #E6B624 100%);">
                        <span class="material-symbols-outlined">location_on</span>
                    </div>
                    <div class="statInfo">
                        <h3 class="statLabel">Lokasi</h3>
                        <p class="statNumber">{{ $totalLocations }}</p>
                    </div>
                </div>
            </div>

            <div class="statCard">
                <div class="statCardContent">
                    <div class="statIcon" style="background: linear-gradient(135deg, #F23D3D 0%, #DA3737 100%);">
                        <span class="material-symbols-outlined">local_offer</span>
                    </div>
                    <div class="statInfo">
                        <h3 class="statLabel">Promo Aktif</h3>
                        <p class="statNumber">{{ $activePromos }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboardContentGrid">
            <div class="chartSection">
                <h3 class="chartTitle">Kategori Produk</h3>
                <div class="chartContainer">
                    @if($productCategories->count() > 0)
                        <svg viewBox="0 0 600 300" class="barChart" xmlns="http://www.w3.org/2000/svg">
                            <line x1="60" y1="250" x2="60" y2="30" stroke="#ccc" stroke-width="2"/>
                            <line x1="60" y1="250" x2="550" y2="250" stroke="#ccc" stroke-width="2"/>

                            @php
                                $maxCount = $productCategories->max('total') ?? 1;
                                $barWidth = 80;
                                $spacing = 120;
                                $startX = 100;
                                $colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', '#98D8C8', '#F7DC6F'];
                            @endphp

                            {{-- Y-axis labels --}}
                            @for($i = 0; $i <= $maxCount; $i++)
                                <text x="45" y="{{ 250 - ($i / $maxCount) * 220 }}" font-size="12" fill="#666" text-anchor="end">{{ $i }}</text>
                            @endfor

                            {{-- Bars and labels --}}
                            @foreach($productCategories as $index => $category)
                                @php
                                    $barHeight = ($category->total / $maxCount) * 220;
                                    $xPos = $startX + ($index * $spacing);
                                @endphp
                                <rect x="{{ $xPos }}" y="{{ 250 - $barHeight }}" width="{{ $barWidth }}" height="{{ $barHeight }}" fill="{{ $colors[$index % count($colors)] }}" rx="4"/>
                                <text x="{{ $xPos + $barWidth/2 }}" y="275" font-size="14" fill="#333" text-anchor="middle" font-weight="500">{{ $category->category }}</text>
                            @endforeach
                        </svg>
                    @else
                        <p style="text-align: center; padding: 40px; color: #999;">Tidak ada data kategori produk</p>
                    @endif
                </div>
            </div>

            <div class="activitySection">
                <h3 class="activityTitle">Aktivitas Terakhir</h3>
                <p class="activitySubtitle">Log sistem</p>
                <div class="activityList">
                    @forelse($recentActivities as $activity)
                        <div class="activityItem">
                            <div class="activityDot"></div>
                            <div class="activityContent">
                                <p class="activityText">{{ $activity['text'] }}</p>
                                <p class="activityTime">{{ $activity['created_at']->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p style="text-align: center; padding: 20px; color: #999;">Tidak ada aktivitas</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="promoAktifSection">
            <h3 class="promoSectionTitle">Promo Aktif</h3>
            <div class="promoCardsGrid">
                @forelse($activePromosList as $promo)
                    <div class="promoCard">
                        <div class="promoCardHeader" style="background: linear-gradient(135deg, #F23D3D 0%, #DA3737 100%);">
                            <span class="promoBadge">Aktif</span>
                        </div>
                        <div class="promoCardBody">
                            <h4 class="promoCardTitle">{{ $promo->title }}</h4>
                            <p class="promoCardDate">• Periode: {{ $promo->start_date->format('d M Y') }} – {{ $promo->end_date->format('d M Y') }}</p>
                            <button class="promoDetailLink" onclick="openPromoModal({{ $promo->id }})">Lihat Detail Promo</button>
                        </div>
                    </div>
                @empty
                    <p style="text-align: center; padding: 20px; color: #999; grid-column: 1/-1;">Tidak ada promo aktif</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Modal Detail Promo --}}
    <div id="promoModalOverlay" class="promoModalOverlay" onclick="closePromoModal()"></div>
    <div id="promoModal" class="promoModal">
        <div class="promoModalContent">
            <div class="promoModalHeader">
                <h2 id="promoModalTitle">Detail Promo</h2>
                <button class="promoModalCloseBtn" onclick="closePromoModal()">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="promoModalBody">
                <div class="promoDetailSection">
                    <div class="detailRow">
                        <span class="detailLabel">Kode Promo:</span>
                        <span class="detailValue" id="promoCode">-</span>
                    </div>
                    <div class="detailRow">
                        <span class="detailLabel">Tipe Promo:</span>
                        <span class="detailValue" id="promoType">-</span>
                    </div>
                    <div class="detailRow">
                        <span class="detailLabel">Diskon:</span>
                        <span class="detailValue" id="promoDiscount">-</span>
                    </div>
                    <div class="detailRow">
                        <span class="detailLabel">Periode:</span>
                        <span class="detailValue" id="promoPeriod">-</span>
                    </div>
                    <div class="detailRow">
                        <span class="detailLabel">Penggunaan:</span>
                        <span class="detailValue" id="promoUsage">-</span>
                    </div>
                </div>

                <div class="promoDescriptionSection">
                    <h3>Deskripsi</h3>
                    <p id="promoDescription">-</p>
                </div>

                <div class="promoProductsSection">
                    <h3>Produk Terkait</h3>
                    <div id="promoProductsList" class="productsList">
                        <p style="text-align: center; color: #999;">Loading...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .promoModalOverlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 999;
            animation: fadeIn 0.3s ease-in-out;
        }

        .promoModal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.9);
            background: white;
            border-radius: 12px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            display: none;
            z-index: 1000;
            animation: modalSlideIn 0.3s ease-in-out;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .promoModal.active {
            display: block;
        }

        .promoModalOverlay.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }

        .promoModalContent {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .promoModalHeader {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px;
            border-bottom: 1px solid #eee;
        }

        .promoModalHeader h2 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }

        .promoModalCloseBtn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 24px;
            color: #666;
            display: flex;
            align-items: center;
            padding: 0;
            transition: color 0.2s;
        }

        .promoModalCloseBtn:hover {
            color: #F23D3D;
        }

        .promoModalBody {
            padding: 24px;
            flex: 1;
            overflow-y: auto;
        }

        .promoDetailSection {
            margin-bottom: 24px;
            padding: 16px;
            background: #f9f9f9;
            border-radius: 8px;
        }

        .detailRow {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .detailRow:last-child {
            border-bottom: none;
        }

        .detailLabel {
            font-weight: 600;
            color: #666;
        }

        .detailValue {
            color: #333;
            font-weight: 500;
        }

        .promoDescriptionSection {
            margin-bottom: 24px;
        }

        .promoDescriptionSection h3 {
            margin: 0 0 12px 0;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            color: #666;
        }

        .promoDescriptionSection p {
            margin: 0;
            color: #555;
            line-height: 1.6;
        }

        .promoProductsSection {
            margin-bottom: 24px;
        }

        .promoProductsSection h3 {
            margin: 0 0 16px 0;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            color: #666;
        }

        .productsList {
            display: grid;
            gap: 12px;
        }

        .productItem {
            display: flex;
            gap: 12px;
            padding: 12px;
            border: 1px solid #eee;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .productItem:hover {
            background: #f9f9f9;
        }

        .productImage {
            width: 60px;
            height: 60px;
            border-radius: 6px;
            object-fit: cover;
            background: #f0f0f0;
        }

        .productInfo {
            flex: 1;
        }

        .productName {
            margin: 0 0 4px 0;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .productPrice {
            margin: 0;
            color: #F23D3D;
            font-weight: 600;
            font-size: 14px;
        }

        .promoDetailLink {
            background: none;
            border: none;
            color: #F23D3D;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            padding: 0;
            margin: 0;
            transition: color 0.2s;
        }

        .promoDetailLink:hover {
            color: #DA3737;
            text-decoration: underline;
        }
    </style>

    <script>
        function openPromoModal(promoId) {
            const modal = document.getElementById('promoModal');
            const overlay = document.getElementById('promoModalOverlay');

            fetch(`/admin/promo/${promoId}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('promoModalTitle').textContent = data.title;
                document.getElementById('promoCode').textContent = data.promo_code;
                document.getElementById('promoType').textContent = data.promo_type;
                document.getElementById('promoDiscount').textContent = data.discount_percentage + '%';
                document.getElementById('promoPeriod').textContent =
                    new Date(data.start_date).toLocaleDateString('id-ID') + ' – ' +
                    new Date(data.end_date).toLocaleDateString('id-ID');
                document.getElementById('promoUsage').textContent = data.used_count + ' / ' + data.max_usage + ' penggunaan';
                document.getElementById('promoDescription').textContent = data.description || '-';

                // Load products
                const productsList = document.getElementById('promoProductsList');
                if (data.products && data.products.length > 0) {
                    productsList.innerHTML = data.products.map(product => `
                        <div class="productItem">
                            <img src="/storage/${product.image}" alt="${product.name}" class="productImage" onerror="this.src='https://via.placeholder.com/60?text=No+Image'">
                            <div class="productInfo">
                                <p class="productName">${product.name}</p>
                                <p class="productPrice">Rp ${new Intl.NumberFormat('id-ID').format(product.price)}</p>
                            </div>
                        </div>
                    `).join('');
                } else {
                    productsList.innerHTML = '<p style="text-align: center; color: #999;">Tidak ada produk terkait</p>';
                }

                modal.classList.add('active');
                overlay.classList.add('active');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat detail promo');
            });
        }

        function closePromoModal() {
            const modal = document.getElementById('promoModal');
            const overlay = document.getElementById('promoModalOverlay');
            modal.classList.remove('active');
            overlay.classList.remove('active');
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closePromoModal();
            }
        });
    </script>
@endsection
