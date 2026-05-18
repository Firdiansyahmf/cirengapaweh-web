@extends("layouts.app")

@section("title", "Beranda - Cireng A'paweh")

@push("styles")
    <link rel="stylesheet" href="{{ asset('css/page/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/promo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/ctaWA.css') }}">
@endpush

@section("content")

    @include("components.hero")
    @include("components.promo")
    @include("components.ctaWA")

@endsection
