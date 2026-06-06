@extends('layouts.app')

@section('title', "Pembayaran Berhasil - Cireng A'Paweh")

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/paymentSuccess.css') }}">
@endpush

@section('content')

    <div class="paymentSuccess flexRow">
        <div class="breakpoint">
            <div class="paymentCard">
                <div class="svgContainer">
                    <img src="{{ asset('assets/icon/success.svg') }}" alt="success" class="successSvg">
                </div>
                <div class="header">
                    <span class="subH3 primaryBrandRed">Pembayaran Berhasil!</span>
                    <span class="bodyMain charcoalGrey">Terima kasih sudah memesan!</span>
                </div>
                <div class="orderIdBadge bodyLg charcoalGrey">
                    <span>#CA0000000000052</span>
                </div>
                <hr>
                <div class="paymentSummary">
                    <div class="paymentDetail">
                        <div class="detail">
                            <span class="caption">Tanggal / Hari</span>
                            <span class="bodyMain">24-01-2026, 09:33</span>
                        </div>
                        <div class="detail">
                            <span class="caption">Metode Pembayaran</span>
                            <span class="bodyMain">QRIS</span>
                        </div>
                        <div class="detail">
                            <span class="caption">Nama Pembeli</span>
                            <span class="bodyMain">Sanuk Akal</span>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="detail">
                    <span class="caption"><b>Total Harga (2{{-- {{ $quantity }} --}} Barang)</b></span>
                    <span class="bodyMain"><b>Rp{{-- {{ number_format($price * $quantity, 0, ',', '.') }} --}}37.000</b></span>
                </div>
                <hr>
                <div class="paymentButtons">
                    <a href="{{ url('/') }}" class="btnPrimary">Kembali ke Beranda</a>
                </div>
            </div> {{-- end paymentCard --}}
        </div> {{-- end breakpoint --}}
    </div> {{-- end payment --}}

@endsection

@push('scripts')
    <script src="{{ asset('') }}"></script>
@endpush
