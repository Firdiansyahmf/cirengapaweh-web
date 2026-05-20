@extends("layouts.app")

@section("title", "Detail Produk - Cireng A'paweh")

@push("styles")
    <link rel="stylesheet" href="{{ asset('css/page/produk.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/detail.css') }}">
@endpush

@section("content")

    {{-- @include("components.detail") --}}

    <div class="detail">
        <div class="breakpoint">

            <div class="product-visual">
                <img src="{{ asset('assets/img/produk/Cireng Salju Kuah Keju Creamy.jpg') }}" alt="Cireng Kuah Keju" class="main-product-img">
            </div>

            <div class="product-info-content">
                <div class="displayH1 charcoalGrey">
                    Spesial Buat Kamu: <br>
                    <span class="primaryBrandRed">Cireng Kuah Keju Juara!</span>
                </div>

                <div class="displayH2">
                    Rp<span>15.000</span>
                </div>

                <div class="seller-message">
                    <div class="quote-box">
                        <div class="quote">
                            <img src="{{ asset('assets/icon/kutip.svg') }}" alt="Quote" class="charcoalGrey">
                        </div>
                        <span class="subH4">Pesan dari A'Paweh</span>
                    </div>
                    <p class="bodyLg">
                        Halo Jajaners! A'Paweh udah siapin perpaduan kenyalnya aci pilihan dengan siraman kuah keju
                        lumer yang rahasia. Gak cuma pedas, tapi gurihnya bikin kamu gak bisa berhenti.
                    </p>
                </div>

                <div class="social-proof-box">
                    <img src="{{ asset('assets/icon/fire.svg') }}" alt="Fire" class="fire-icon">
                    <p class="bodyLg">Terjual Ribuan Porsi Tiap Hari di Kampus!</p>
                </div>

                <div class="product-buy">
                    <p class="subH4">Atur Jumlah Pembelian</p>
                    <form method="POST" action="{{ url('/checkout') }}">
                        @csrf
                        <input type="hidden" name="product_name" value="Cireng Kuah Keju Juara!">
                        <input type="hidden" name="price" value="15000">
                        <div class="quantity">
                            <button type="button"><img src="{{ asset('assets/icon/minus.svg') }}" alt="minus"></button>
                            {{-- quantity input needs a name so it posts --}}
                            <input name="quantity" type="number" class="subH3 primaryBrandRed" value="1" min="1" max="99">
                            <button type="button"><img src="{{ asset('assets/icon/plus.svg') }}" alt="plus"></button>
                        </div>
                        <div class="subtotal">
                            Subtotal <p class="bodyLg"><b>Rp<span>15.000</span></b></p>
                        </div>
                        <button type="submit" class="btnPrimary">Beli Langsung</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection

@push("scripts")
    <script src="{{ asset('js/detail.js') }}"></script>
@endpush