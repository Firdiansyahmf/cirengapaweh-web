@extends('layouts.app')

@section('title', "Tentang Kami - Cireng A'paweh")

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/page/tentangKami.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/heroTentangKami.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/visiMisi.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/ctaMedsos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/lokasi.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/ctaWA.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/rollback.css') }}">

@endpush

@section('content')

    @include('components.heroTentangKami')
    @include('components.visiMisi')
    @include('components.ctaMedsos')
    @include('components.lokasi')
    @include('components.ctaWA')
    @include('components.rollback')

    <button class="rollbackToggle" id="rollbackToggle" type="button" aria-label="Kembali ke atas"
        onclick="window.scrollTo({ top: 0, behavior: 'smooth' });">
        <img src="{{ asset('assets/icon/Rollback.svg') }}" alt="Scroll to top">
    </button>

    <script src="{{ asset('js/components/chatbot.js') }}"></script>

@endsection
