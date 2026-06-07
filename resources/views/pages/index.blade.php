@extends("layouts.app")

@section("title", "Beranda - Cireng A'paweh")

@push("styles")
    <link rel="stylesheet" href="{{ asset('css/page/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/promo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/reels.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/mitra.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/lokasi.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/ctaWA.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/chatbot.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/rollback.css') }}">
@endpush

@section("content")

    @include("components.hero")
    @include("components.promo")
    @include("components.menu")
    @include("components.reels")
    @include("components.mitra")
    @include("components.lokasi")
    @include("components.ctaWA")
    @include("components.chatbot")
    @include("components.rollback")
@endsection
