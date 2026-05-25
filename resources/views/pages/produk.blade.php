@extends("layouts.app")

@section("title", "Detail Produk - Cireng A'paweh")

@push("styles")
    <link rel="stylesheet" href="{{ asset('css/page/produk.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/detail.css') }}">
@endpush

@section("content")

    <div class="detail flexRow">
        <div class="breakpoint">
            
            <div class="box1 flexRow">
                <div class="productVisual">
                    <img src="{{ asset('assets/img/produk/Cireng Salju Kuah Keju Creamy.jpg') }}" alt="Cireng Kuah Keju">
                </div>

                <div class="flexCol">
                    <div class="productTitle flexCol">
                        <h1 class="displayH1 charcoalGrey">
                            Spesial Buat Kamu: <br>
                            <span class="primaryBrandRed">Cireng Kuah Keju Juara!</span>
                        </h1>
                        <h2 class="displayH2">
                            Rp<span>15.000</span>
                        </h2>
                    </div>

                    <div class="sellerMsg">
                        <div class="sellerQuote flexRow">
                            <img src="{{ asset('assets/icon/kutip.svg') }}" alt="Quote" class="charcoalGrey">
                            <h4 class="subH4">Pesan dari A'Paweh</h4>
                        </div>
                        <span class="bodyLg">
                            Halo Jajaners! A'Paweh udah siapin perpaduan kenyalnya aci pilihan dengan siraman kuah keju
                            lumer yang rahasia. Gak cuma pedas, tapi gurihnya bikin kamu gak bisa berhenti.
                        </span>
                    </div>

                    <div class="socialProof">
                        <img src="{{ asset('assets/icon/fire.svg') }}" alt="Fire" class="fire-icon">
                        <span class="bodyLg">Terjual Ribuan Porsi Tiap Hari di Kampus!</span>
                    </div>

                    <div class="productBuy flexCol">
                        <span class="subH4">Atur Jumlah Pembelian</span>
                        <form method="POST" action="{{ url('/checkout') }}">
                            @csrf
                            <input type="hidden" name="product_name" value="Cireng Kuah Keju Juara!">
                            <input type="hidden" name="price" value="15000">
                            <div class="flexCol">
                                <div class="quantityInput">
                                    <button type="button"><img src="{{ asset('assets/icon/minus.svg') }}" alt="minus"></button>
                                    <input name="quantity" type="number" class="subH3 primaryBrandRed" value="1" min="1" max="99">
                                    <button type="button"><img src="{{ asset('assets/icon/plus.svg') }}" alt="plus"></button>
                                </div>
                                <div class="subtotal flexRow bodyMain">
                                    Subtotal <span class="bodyLg"><b>Rp<span>15.000</span></b></span>
                                </div>
                                <button type="submit" class="btnPrimary">Beli Langsung</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> {{-- end box1 --}}

        </div> {{-- end breakpoint --}}
    </div> {{-- end detail --}}

@endsection

@push("scripts")
    <script src="{{ asset('js/detail.js') }}"></script>
@endpush