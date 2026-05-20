<div class="order-item">
    <div class="item-image">
        <img src="{{ asset('assets/img/produk/Cireng Salju Kuah Keju Creamy.jpg') }}" alt="{{ $product }}">
    </div>
    <div class="item-info">
        <p class="bodyMain primaryBrandRed"><b>{{ $product }}   </b></p>
        <div class="bodyMain charcoalGrey"><b>{{ $quantity }} x Rp{{ number_format($price, 0, ',', '.') }}</b></div>
    </div>
    <div class="bodyMain charcoalGrey">Rp{{ number_format($price * $quantity, 0, ',', '.') }}</div>
</div>
