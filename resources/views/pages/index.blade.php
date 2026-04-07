@extends('layouts.app')

@section('title', 'Beranda - Cireng A\'paweh')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/page/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/hero.css') }}">
@endpush

@section('content')

    @include('components.hero')
    {{-- @include('components.promo') --}}
    {{-- @include('components.menu') --}}

@endsection
