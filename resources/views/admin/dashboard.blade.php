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
        </div>

        <div class="dashboardContentGrid">
        </div>

        <div class="promoAktifSection">
        </div>
    </div>
@endsection
