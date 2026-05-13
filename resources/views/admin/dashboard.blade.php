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
                        <p class="statNumber">67</p>
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
                        <p class="statNumber">7</p>
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
                        <p class="statNumber">3</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboardContentGrid">
            <div class="chartSection">
                <h3 class="chartTitle">Kategori Produk</h3>
                <div class="chartContainer">
                    <svg viewBox="0 0 600 300" class="barChart" xmlns="http://www.w3.org/2000/svg">
                        <line x1="60" y1="250" x2="60" y2="30" stroke="#ccc" stroke-width="2"/>
                        <line x1="60" y1="250" x2="550" y2="250" stroke="#ccc" stroke-width="2"/>

                        <text x="45" y="255" font-size="12" fill="#666" text-anchor="end">0</text>
                        <text x="45" y="205" font-size="12" fill="#666" text-anchor="end">1</text>
                        <text x="45" y="155" font-size="12" fill="#666" text-anchor="end">2</text>
                        <text x="45" y="105" font-size="12" fill="#666" text-anchor="end">3</text>
                        <text x="45" y="55" font-size="12" fill="#666" text-anchor="end">4</text>
                        <text x="45" y="5" font-size="12" fill="#666" text-anchor="end">5</text>
                        <text x="45" y="-45" font-size="12" fill="#666" text-anchor="end">6</text>

                        <rect x="150" y="80" width="80" height="170" fill="#FF6B6B" rx="4"/>
                        <rect x="350" y="150" width="80" height="100" fill="#FF6B6B" rx="4"/>

                        <text x="190" y="275" font-size="14" fill="#333" text-anchor="middle" font-weight="500">Fast Food</text>
                        <text x="390" y="275" font-size="14" fill="#333" text-anchor="middle" font-weight="500">Frozen Food</text>
                    </svg>
                </div>
            </div>

            <div class="activitySection">
                <h3 class="activityTitle">Aktivitas Terakhir</h3>
                <p class="activitySubtitle">Log sistem</p>
                <div class="activityList">
                    <div class="activityItem">
                        <div class="activityDot"></div>
                        <div class="activityContent">
                            <p class="activityText">Menambahkan produk baru "Cireng Isi Ayam Pedas"</p>
                            <p class="activityTime">2 MENIT YANG LALU</p>
                        </div>
                    </div>
                    </div>
            </div>
        </div>

        <div class="promoAktifSection">
            <h3 class="promoSectionTitle">Promo Aktif</h3>
            <div class="promoCardsGrid">
                <div class="promoCard">
                    <div class="promoCardHeader" style="background: linear-gradient(135deg, #F23D3D 0%, #DA3737 100%);">
                        <span class="promoBadge">Aktif</span>
                    </div>
                    <div class="promoCardBody">
                        <h4 class="promoCardTitle">Paket Cheesy Chicken</h4>
                        <p class="promoCardDate">• Periode: 1 – 30 Jan 2024</p>
                        <a href="#" class="promoDetailLink">Lihat Detail Promo</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
