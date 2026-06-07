@extends("layouts.app")

@section("title", "Cek Pesanan - Cireng A'paweh")

@push("styles")
    <link rel="stylesheet" href="{{ asset('css/page/checkOrder.css') }}">
@endpush

@section("content")

    <div class="checkOrder">
        <div class="breakpoint">

            <div class="box1 animated">
                <div class="header">
                    <span class="displayH1 primaryBrandRed">Cek Orderan Kamu Dengan Mudah</span>
                    <span class="subH4 charcoalGrey">Lihat detail orderan kamu menggunakan nomor invoice.</span>
                </div>
                <div class="card animated">
                    <span class="bodyMain charcoalGrey">Masukkan nomor invoice orderan kamu di sini</span>
                    <input type="text" id="no_invoice" class="inputInvoice bodyMain" placeholder="No. Invoice">
                    <button class="btnPrimary">Cari Orderan</button>
                </div>
            </div>

        </div> {{-- end breakpoint --}}
    </div> {{-- end cekOrder --}}

@endsection

@push('scripts')

    <script>
        ScrollReveal().reveal('.animated', {
            delay: 200,
            distance: '30px',
            duration: 800,
            easing: 'ease-out',
            origin: 'bottom',
            interval: 100
        });
    </script>
@endpush
