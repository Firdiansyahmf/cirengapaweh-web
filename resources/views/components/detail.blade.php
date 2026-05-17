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
                <div class="quantity">
                    <button><img src="{{ asset('assets/icon/minus.svg') }}" alt="minus"></button>
                    {{-- <p style="width: 32px; text-align: center;" class="subH3 primaryBrandRed">1</p> --}}
                    <input type="number" class="subH3 primaryBrandRed" value="1" min="1" max="99">
                    <button><img src="{{ asset('assets/icon/plus.svg') }}" alt="plus"></button>
                </div>
                <div class="subtotal">
                    Subtotal <p class="bodyLg"><b>Rp<span>15.000</span></b></p>
                </div>
                <button class="btnPrimary">Beli Langsung</button>
            </div>
        </div>

    </div>
</div>