@extends('layouts.app')

@section('title', "Checkout - Cireng A'Paweh")

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/checkout.css') }}">
@endpush

@section('content')
    <div class="checkout-page">
        <div class="checkout-shell">
            <div class="checkout-hero">
                <div>
                    <button href="{{ url('/') }}" class="btnOutline">Kembali ke Beranda</button>
                    <br><br>
                    <p class="subH3 charcoalGrey">Checkout</p>
                </div>
            </div>

            <div class="checkout-grid">
                <div class="left-column">
                    @include('components.checkout.address-card')

                    <div class="order-card card-panel">
                        <p class="subH4 charcoalGrey">Detail Pemesanan</p>
                        @include('components.checkout.order-item', ['product' => $product, 'quantity' => $quantity, 'price' => $price])
                    </div>
                </div>

                <div class="right-column">
                    <div class="payment-card card-panel">
                        <p class="subH4 charcoalGrey">Metode Pembayaran</p>
                        @include('components.checkout.payment-method-row', ['id' => 'qris', 'label' => 'QRIS', 'checked' => true])
                        @include('components.checkout.payment-method-row', ['id' => 'gopay', 'label' => 'GoPay'])
                        @include('components.checkout.payment-method-row', ['id' => 'dana', 'label' => 'DANA'])
                        @include('components.checkout.payment-method-row', ['id' => 'shopeepay', 'label' => 'ShopeePay'])

                        @include('components.checkout.payment-summary', ['total' => $total])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/checkout.js') }}"></script>
@endpush
