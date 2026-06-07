@php extract(app(\App\Http\Controllers\PaymentController::class)->getPaymentData()); @endphp
@extends('layouts.app')

@section('title', "Pembayaran - Cireng A'Paweh")

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/payment.css') }}">
@endpush

@section('content')

    <div class="payment flexRow">
        <div class="breakpoint">

            <div class="paymentCard">
                @if(session('error'))
                    <div class="errorAlert">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="timerBadge bodyLg charcoalGrey" id="timerContainer">
                    @if($payment->status === 'pending' && $timeRemaining > 0)
                        <b><span>Bayar dalam: </span> <span id="countdownTimer">--:--:--</span></b>
                    @elseif($payment->status === 'settlement')
                        <b class="textSuccess"><span>Pembayaran Berhasil!</span></b>
                    @else
                        <b class="textDanger"><span>Waktu pembayaran telah habis atau dibatalkan</span></b>
                    @endif
                </div>

                {{-- section ewallet --}}
                @if($payment->payment_type === 'qris')
                    <div class="qrContainer">
                        @if($payment->qr_code_url)
                            <img src="{{ $payment->qr_code_url }}" alt="QRIS Code" class="qrCode">
                            <span class="caption qrCaption">Pindai QRIS ini dengan aplikasi e-wallet kamu.</span>
                        @else
                            <div class="textDanger qrError">Gagal memuat QR Code. Silahkan hubungi CS.</div>
                        @endif
                    </div>
                @endif

                {{-- section virtual account bank --}}
                @if(in_array($payment->payment_type, ['bca', 'bni', 'bri', 'permata', 'cimb', 'mandiri']))
                    <div class="vaContainer">
                        <div class="vaGroup">
                            <strong>Bank Penerima:</strong> <span class="vaBankName">{{ $payment->bank }}</span>
                        </div>

                        @if($payment->bank === 'mandiri')
                            <div class="vaGroup">
                                <strong>Kode Biller:</strong> <span class="vaCode">{{ $payment->biller_code }}</span>
                                <button class="copyBtn" onclick="copyToClipboard('{{ $payment->biller_code }}')">Salin</button>
                            </div>
                            <div class="vaGroup">
                                <strong>Nomor Virtual Account / Bill Key:</strong> <span id="vaNumber" class="vaCode">{{ $payment->va_number }}</span>
                                <button class="copyBtn" onclick="copyToClipboard('{{ $payment->va_number }}')">Salin</button>
                            </div>
                        @else
                            <div class="vaGroup">
                                <strong>Nomor Virtual Account (VA):</strong> <span id="vaNumber" class="vaCode">{{ $payment->va_number }}</span>
                                <button class="copyBtn" onclick="copyToClipboard('{{ $payment->va_number }}')">Salin</button>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="bodyMain charcoalGrey orderIdContainer">
                    <span><b>Order ID:</b> </span> <span>#{{ $order->invoice_number }}</span>
                </div>

                <div class="paymentTotal">
                    <span class="subH4">Total Pembayaran</span>
                    <div class="paymentDetail primaryBrandRed">
                        <span class="subH3">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        <button class="btnDetail caption primaryBrandRed" popovertarget="detailPopover">Lihat Detail</button>
                    </div>
                    <div popover class="detailPopover" id="detailPopover">
                        <span class="subH4 primaryBrandRed popoverTitle">Ringkasan Pembayaran</span>
                        <div class="summaryDetail">
                            <div class="detail">
                                <span class="caption">Harga Produk ({{ $orderItem->quantity }} Barang)</span>
                                <span class="bodyMain">Rp{{ number_format($orderItem->unit_price * $orderItem->quantity, 0, ',', '.') }}</span>
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

                    @if($payment->payment_type === 'qris')
                        <ol class="bodyMain guideList">
                            <li>Buka aplikasi mobile banking atau e-wallet (Gojek, Shopee, DANA, OVO, LinkAja, dll.) yang mendukung pembayaran QRIS.</li>
                            <li>Pilih fitur <b>Pindai / Scan QR</b> pada aplikasi tersebut.</li>
                            <li>Arahkan kamera ke kode QR yang tertera di atas, atau download gambar QRIS dan unggah ke aplikasi e-wallet.</li>
                            <li>Periksa nominal tagihan yang muncul, pastikan sesuai dengan total pembayaran.</li>
                            <li>Konfirmasi pembayaran dan masukkan PIN Anda.</li>
                            <li>Pembayaran selesai.</li>
                        </ol>
                    @elseif($payment->payment_type === 'mandiri')
                        <ol class="bodyMain guideList">
                            <li>Buka aplikasi <b>Livin' by Mandiri</b> atau pergi ke ATM Mandiri terdekat.</li>
                            <li>Pilih menu <b>Bayar / Pembayaran</b>, lalu pilih <b>Multi Payment / e-Commerce</b>.</li>
                            <li>Masukkan Kode Biller: <b>{{ $payment->biller_code }}</b>.</li>
                            <li>Masukkan Bill Key / Nomor Virtual Account: <b>{{ $payment->va_number }}</b>.</li>
                            <li>Masukkan jumlah tagihan secara tepat: <b>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</b>.</li>
                            <li>Periksa detail pembayaran, lalu konfirmasi dengan PIN Anda.</li>
                        </ol>
                    @else
                        <ol class="bodyMain guideList">
                            <li>Buka aplikasi Mobile Banking pilihan Anda (m-BCA, BNI Mobile, BRImo, dll.) atau gunakan ATM terdekat.</li>
                            <li>Pilih menu <b>Transfer</b>, kemudian pilih <b>Virtual Account / Transfer Virtual Account</b>.</li>
                            <li>Masukkan Nomor Virtual Account: <b>{{ $payment->va_number }}</b>.</li>
                            <li>Jumlah transfer akan terisi otomatis sesuai dengan nominal tagihan Anda. Jika tidak, masukkan secara manual: <b>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</b>.</li>
                            <li>Pastikan nama penerima/merchant tertera dengan benar, lalu konfirmasi pembayaran menggunakan PIN Anda.</li>
                        </ol>
                    @endif
                </div>

                <div class="paymentButtons">
                    @if($payment->payment_type === 'qris' && $payment->qr_code_url)
                        <a href="{{ $payment->qr_code_url }}" download="qris-cireng-apaweh.png" target="_blank" class="btnOutline">Unduh QRIS</a>
                    @endif
                    <button onclick="checkPaymentStatus()" class="btnPrimary">Cek Status Pembayaran</button>
                </div>
                <form id="paymentSuccessForm" action="{{ url('/payment/success') }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="invoice_number" value="{{ $order->invoice_number }}">
                </form>
            </div> {{-- end paymentCard --}}
            
        </div> {{-- end breakpoint --}}
    </div> {{-- end payment --}}

@endsection

@push('scripts')
    <script>
        window.paymentConfig = {
            timeRemaining: {{ $timeRemaining }},
            statusUrl: "{{ url('/payment?check_status=1') }}",
            successUrl: "{{ url('/payment/success') }}",
            isPending: {{ $payment->status === 'pending' ? 'true' : 'false' }}
        };
    </script>
    <script src="{{ asset('js/payment.js') }}"></script>
@endpush
