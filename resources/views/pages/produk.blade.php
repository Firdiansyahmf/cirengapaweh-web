@extends("layouts.app")

@section("title", "Detail Produk - Cireng A'paweh")

@push("styles")
    <link rel="stylesheet" href="{{ asset('css/page/produk.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/detail.css') }}">
@endpush

@section("content")

    @include("components.detail")

@endsection

@push("scripts")
    <script src="{{ asset('js/detail.js') }}"></script>
@endpush