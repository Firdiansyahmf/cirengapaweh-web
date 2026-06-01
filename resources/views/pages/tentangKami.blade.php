@extends('layouts.app')

@section('title', "Tentang Kami - Cireng A'paweh")

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/page/tentangKami.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/heroTentangKami.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/visiMisi.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/ctaMedsos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/lokasi.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/ctaWA.css') }}">
@endpush

@section('content')

    @include('components.heroTentangKami')
    @include('components.visiMisi')
    @include('components.ctaMedsos')
    @include('components.lokasi')
    @include('components.ctaWA')

@endsection
