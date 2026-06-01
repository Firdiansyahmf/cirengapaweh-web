@extends('layouts.app')

@section('title', "Checkout - Cireng A'Paweh")

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/checkout.css') }}">
@endpush

@section('content')

    <div class="checkout flexRow">
        <div class="breakpoint">
            <div class="box1 flexCol">
                <div class="header">
                    <button href="{{ url('/') }}" class="btnOutline">Kembali</button>
                    <span class="subH3 charcoalGrey">Checkout</span>
                </div>
                <div class="box2 flexRow">
                    <div class="leftCol flexCol">
                        <div class="card"> {{-- start addressCard --}}
                            <span class="subH4 charcoalGrey">Alamat Pengiriman</span>
                            <span class="bodyMain primaryBrandRed"><b><span>Rumah Saya</span> <span> · </span>  <span>Sanuk Akal</span></b></span>
                            <div class="flexRow">
                                <div class="cardBody flexCol">
                                    <span class="bodyMain charcoalGrey">Jl. Riung Hegar II no.11 RT.09/RW.10, Kelurahan Cisaranteun Kidul, Kecamatan Gedebage, Kota Bandung, Kode 40295, Gedebage, Kota Bandung, Jawa Barat</span>
                                    <span class="bodyMain charcoalGrey">62819556291099</span>
                                </div>
                                <a href="#" class="bodyMain primaryBrandRed"><b>Ganti</b></a>
                            </div>
                        </div> {{-- end addressCard --}}
                        <div class="card"> {{-- start detailCard --}}
                            <span class="subH4 charcoalGrey">Detail Pemesanan</span>
                            <div class="itemInfo">
                                <div class="productImg">
                                    <img src="{{ asset('assets/img/produk/Cireng Salju Kuah Keju Creamy.jpg') }}" alt="{{ $product }}">
                                </div>
                                <div class="itemDetail bodyMain">
                                    <div class="itemTitle">
                                        <span class="primaryBrandRed"><b>{{ $product }}</b></span>
                                        <div class="charcoalGrey">{{ $quantity }} x Rp{{ number_format($price, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="charcoalGrey"><b>Rp{{ number_format($price * $quantity, 0, ',', '.') }}</b></div>
                                </div>
                            </div>
                        </div> {{-- end detailCard --}}
                    </div> {{-- end leftCol --}}
                    <div class="card"> {{-- start paymentCard --}}
                        <span class="subH4 charcoalGrey">Metode Pembayaran</span>
                        <div class="scrollable">
                            <div class="paymentList">
                                @include('components.paymentMethod', ['id' => 'qris', 'label' => 'QRIS', 'checked' => true])
                                @include('components.paymentMethod', ['id' => 'gopay', 'label' => 'GoPay'])
                                @include('components.paymentMethod', ['id' => 'dana', 'label' => 'DANA'])
                                @include('components.paymentMethod', ['id' => 'shopeepay', 'label' => 'ShopeePay'])
                            </div>
                            <div class="paymentSummary">
                                <span class="subH4 charcoalGray">Ringkasan Pembayaran</span>
                                <div class="summaryDetail">
                                    <div class="flexRow">
                                        <span class="caption">Total Harga ({{ $quantity }} Barang)</span>
                                        <span class="bodyMain">Rp{{ number_format($price * $quantity, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flexRow">
                                        <span class="caption">Total Ongkos Kirim</span>
                                        <span class="bodyMain">Rp6.000</span>
                                    </div>
                                    <div class="flexRow">
                                        <span class="caption">Biaya Admin</span>
                                        <span class="bodyMain">Rp1.000</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="payment">
                            <div class="flexRow">
                                <span class="bodyMain charcoalGrey"><b>Total Tagihan</b></span>
                                <strong class="bodyMain charcoalGrey"><b>Rp{{ number_format(($price * $quantity) + 6000 + 1000, 0, ',', '.') }}</b></strong>
                            </div>
                            <button id="payNow" class="btnPrimary">Bayar Sekarang</button>
                            <span class="caption">Pembayaran akan diproses setelah kamu menekan tombol di atas.</span>
                        </div>
                    </div> {{-- end paymentCard --}}
                </div> {{-- end box2 --}}
            </div> {{-- end box1 --}}

        </div> {{-- end breakpoint --}}
    </div> {{-- end checkout --}}

@endsection

@push('scripts')
    <script src="{{ asset('js/checkout.js') }}"></script>
@endpush
