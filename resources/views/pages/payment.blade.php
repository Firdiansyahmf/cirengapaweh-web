@extends('layouts.app')

@section('title', "Pembayaran - Cireng A'Paweh")

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/payment.css') }}">
@endpush

@section('content')

    <div class="payment flexRow">
        <div class="breakpoint">
            <div class="paymentCard">
                <div class="timerBadge bodyLg charcoalGrey">
                    <b><span>Bayar dalam: </span> <span>00:14:59</span></b>
                </div>
                <div class="qrContainer">
                    <img src="{{ asset('assets/img/qr-dummy.png') }}" alt="QRIS Code" class="qrCode">
                </div>
                <div class="bodyMain charcoalGrey">
                    <span>Order ID </span> <span>#CA0000000000052</span>
                </div>
                <div class="paymentTotal">
                    <span class="subH4">Total Pembayaran</span>
                    <div class="paymentDetail primaryBrandRed">
                        <span class="subH3">Rp22.000</span>
                        <button class="btnDetail caption primaryBrandRed" popovertarget="detailPopover">Lihat Detail</button>
                    </div>
                    <div popover class="detailPopover" id="detailPopover">
                        <span class="subH4 primaryBrandRed">Ringkasan Pembayaran</span>
                        <div class="summaryDetail">
                            <div class="detail">
                                <span class="caption">Total Harga {{-- ({{ $quantity }} Barang) --}}</span>
                                <span class="bodyMain">Rp{{-- {{ number_format($price * $quantity, 0, ',', '.') }} --}}</span>
                            </div>
                            <div class="detail">
                                <span class="caption">Total Ongkos Kirim</span>
                                <span class="bodyMain">Rp6.000</span>
                            </div>
                            <div class="detail">
                                <span class="caption">Biaya Admin</span>
                                <span class="bodyMain">Rp1.000</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="paymentGuide charcoalGrey">
                    <span class="subH4 primaryBrandRed">Cara Bayar</span>
                    <ol class="bodyMain">
                        <li>Buka aplikasi <b>e-wallet</b> kamu yang mendukung pembayaran <b>QRIS</b>.</li>
                        <li><b>Download</b> atau <b>pindai QRIS</b> pada layar.</li>
                        <li>Konfirmasi pembayaran pada aplikasi e-wallet kamu.</li>
                        <li>Pembayaran berhasil.</li>
                    </ol>
                </div>
                <div class="paymentButtons">
                    <button href="{{ url('/') }}" class="btnOutline">Download QRIS</button>
                    <button href="{{ url('/') }}" class="btnPrimary">Cek Status Pembayaran</button>
                </div>
            </div> {{-- end paymentCard --}}
        </div> {{-- end breakpoint --}}
    </div> {{-- end payment --}}

@endsection

@push('scripts')
    <script src="{{ asset('') }}"></script>
@endpush
