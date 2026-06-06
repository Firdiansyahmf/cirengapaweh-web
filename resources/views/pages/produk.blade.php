@extends('layouts.app')

@section('title', "{$product->name} - Cireng A'paweh")

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/page/produk.css') }}">
@endpush

@section('content')

    <div class="detail flexRow">
        <div class="breakpoint">
            <a href="{{ url('/') }}" class="btnOutline kembali">Kembali</a> <!-- sama anaqi -->
            <div class="box1 flexRow">
                <div class="productVisual">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/800x800.png' }}"
                        alt="{{ $product->name }}" onerror="this.src='https://placehold.co/800x800.png'">
                </div>

                <div class="flexCol">
                    <div class="productTitle flexCol">
                        <h1 class="displayH1 charcoalGrey">
                            Spesial Buat Kamu: <br>
                            <span class="primaryBrandRed">{{ $product->name }}</span>
                        </h1>

                        @if ($activePromo)
                            <div class="promoWrapper">
                                <span class="discountBadge">
                                    Diskon {{ $activePromo->discount_percentage }}%
                                </span>
                                <span class="originalPrice">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            </div>
                            <h2 class="displayH2 primaryBrandRed">
                                Rp<span id="priceDisplay">{{ number_format($finalPrice, 0, ',', '.') }}</span>
                            </h2>
                        @else
                            <h2 class="displayH2 charcoalGrey">
                                Rp<span id="priceDisplay">{{ number_format($product->price, 0, ',', '.') }}</span>
                            </h2>
                        @endif
                    </div>

                    <div class="sellerMsg">
                        <div class="sellerQuote flexRow">
                            <img src="{{ asset('assets/icon/kutip.svg') }}" alt="Quote" class="charcoalGrey">
                            <h4 class="subH4">Deskripsi Produk</h4>
                        </div>
                        <span class="bodyLg descriptionText">
                            {{ $product->description }}
                        </span>
                    </div>

                    <div class="productBuy flexCol">
                        <span class="subH4">Atur Jumlah Pembelian</span>
                        <form method="POST" action="{{ url('/checkout') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="product_name" value="{{ $product->name }}">
                            <input type="hidden" name="product_image" value="{{ $product->image }}">
                            <input type="hidden" name="price" id="rawPrice" value="{{ $finalPrice }}">

                            <div class="flexCol">
                                <div class="quantityInput">
                                    <button type="button" id="btnMinus"><img src="{{ asset('assets/icon/minus.svg') }}"
                                            alt="minus"></button>
                                    <input name="quantity" id="qtyInput" type="number" class="subH3 primaryBrandRed"
                                        value="1" min="1" max="99" readonly>
                                    <button type="button" id="btnPlus"><img src="{{ asset('assets/icon/plus.svg') }}"
                                            alt="plus"></button>
                                </div>
                                <div class="subtotal flexRow bodyMain">
                                    Subtotal
                                    <span class="bodyLg"><b>Rp<span
                                                id="subtotalDisplay">{{ number_format($finalPrice, 0, ',', '.') }}</span></b></span>
                                </div>
                                <button type="submit" class="btnPrimary">Beli Langsung</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> <!-- end box1 -->

        </div> <!-- end breakpoint -->
    </div> <!-- end detail -->

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const qtyInput = document.getElementById('qtyInput');
            const btnMinus = document.getElementById('btnMinus');
            const btnPlus = document.getElementById('btnPlus');
            const subtotalDisplay = document.getElementById('subtotalDisplay');
            const rawPrice = parseInt(document.getElementById('rawPrice').value);

            function updateSubtotal() {
                const currentQty = parseInt(qtyInput.value);
                const subtotal = rawPrice * currentQty;
                subtotalDisplay.innerText = new Intl.NumberFormat('id-ID').format(subtotal);
            }

            btnMinus.addEventListener('click', () => {
                let val = parseInt(qtyInput.value);
                if (val > 1) {
                    qtyInput.value = val - 1;
                    updateSubtotal();
                }
            });

            btnPlus.addEventListener('click', () => {
                let val = parseInt(qtyInput.value);
                if (val < 99) {
                    qtyInput.value = val + 1;
                    updateSubtotal();
                }
            });
        });
    </script>
@endpush
