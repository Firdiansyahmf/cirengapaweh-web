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
                    <a href="javascript:history.back()" class="btnOutline">Kembali</a>
                    <span class="subH3 charcoalGrey">Checkout</span>
                </div>
                @if(session('error'))
                    <div class="errorAlert">
                        {{ session('error') }}
                    </div>
                @endif

                <form class="box2 flexRow" method="POST" action="{{ route('checkout.process') }}" id="checkoutForm">
                    @csrf
                    <div class="leftCol flexCol">
                        <div class="card addressCard"> {{-- start addressCard --}}
                            <span class="subH4 charcoalGrey">Detail Pengiriman</span>
                            <div class="inputGroup">
                                <label class="bodyMain charcoalGrey">No. WhatsApp <span class="primaryBrandRed">*</span></label>
                                <div class="withLabel">
                                    <input type="tel" name="whatsapp" id="inputWa" placeholder="08XXXXXXXXXX" required pattern="[0-9]{8,15}" class="bodyMain"/>
                                    <span class="caption"><i>*Nomor ini akan dihubungi jika ada kendala</i></span>
                                </div>
                            </div>
                            <div class="inputGroup">
                                <label class="bodyMain charcoalGrey">Email</label>
                                <input type="email" name="customer_email" placeholder="contoh@email.com" class="bodyMain"/>
                            </div>
                            <div class="inputAddress">
                                <div class="inputGroup">
                                    <label class="bodyMain charcoalGrey">Alamat Lengkap<span class="primaryBrandRed">*</span></label>
                                    <div class="withLabel">
                                        <input type="text" id="inputAddress" name="shipping_address" placeholder="Isian Alamat" required maxlength="200" class="bodyMain"/>
                                        <span class="caption textRight" id="addressCount">0/200</span>
                                    </div>
                                </div>
                                <div class="inputGroup inputPostal">
                                    <label class="bodyMain charcoalGrey">Kode Pos<span class="primaryBrandRed">*</span></label>
                                    <input type="text" id="inputPostal" name="postal_code" inputmode="numeric" pattern="[0-9]*" placeholder="Kode Pos" required maxlength="5" class="bodyMain"/>
                                </div>
                            </div>
                            <div class="inputGroup">
                                <label class="bodyMain charcoalGrey">Nama Penerima <span class="primaryBrandRed">*</span></label>
                                <div class="withLabel">
                                    <input type="text" id="inputName" name="customer_name" placeholder="Nama" required maxlength="50" class="bodyMain"/>
                                    <span class="caption textRight" id="nameCount">0/50</span>
                                </div>
                            </div>
                        </div> {{-- end addressCard --}}
                        <div class="card"> {{-- start detailCard --}}
                            <span class="subH4 charcoalGrey">Detail Pemesanan</span>
                            <div class="itemInfo">
                                <div class="productImg">
                                    <img src="{{ $productModel && $productModel->image ? asset('storage/' . $productModel->image) : 'https://placehold.co/84x84.png' }}" alt="{{ $product }}" onerror="this.src='https://placehold.co/84x84.png'">
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
                    <div class="rightCol flexCol">
                        <div class="card"> {{-- start couponCard --}}
                            <span class="subH4 charcoalGrey">Kode Promo</span>
                            <div class="inputGroup inputPromo">
                                <input type="text" name="promo" placeholder="Kode Promo" class="bodyMain"/>
                                <button type="button" class="btnOutline">Gunakan</button>
                            </div>
                        </div> {{-- end couponCard --}}
                        <div class="card paymentCard"> {{-- start paymentCard --}}
                            <span class="subH4 charcoalGrey">Metode Pembayaran</span>
                            <div class="scrollableContainer">
                                <div class="scrollable">
                                    <div class="paymentList">
                                        @include('components.paymentMethod', ['id' => 'qris', 'label' => 'QRIS', 'checked' => true])

                                        {{-- Bank Virtual Account --}}
                                        <div class="vaHeading">Bank Virtual Account</div>
                                        @include('components.paymentMethod', ['id' => 'bca', 'label' => 'BCA Virtual Account'])
                                        @include('components.paymentMethod', ['id' => 'bni', 'label' => 'BNI Virtual Account'])
                                        @include('components.paymentMethod', ['id' => 'bri', 'label' => 'BRI Virtual Account'])
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
                            </div>
                            <div class="payment">
                                <div class="flexRow">
                                    <span class="bodyMain charcoalGrey"><b>Total Tagihan</b></span>
                                    <strong class="bodyMain charcoalGrey"><b>Rp{{ number_format(($price * $quantity) + 6000 + 1000, 0, ',', '.') }}</b></strong>
                                </div>
                                <button type="submit" id="payNow" class="btnPrimary">Bayar Sekarang</button>
                                <span class="caption">Pembayaran akan diproses setelah kamu menekan tombol di atas.</span>
                            </div>
                        </div> {{-- end paymentCard --}}
                    </div>
                </form> {{-- end box2 --}}
            </div> {{-- end box1 --}}

        </div> {{-- end breakpoint --}}
    </div> {{-- end checkout --}}

@endsection

@push('scripts')
    <script src="{{ asset('js/checkout.js') }}"></script>
@endpush
