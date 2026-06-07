@Extends ('layouts.app')

@section('title', "404 Not Found - Cireng A'Paweh")
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/404.css') }}">
@endpush

@section('content')
    @include('components.404')
@endsection

