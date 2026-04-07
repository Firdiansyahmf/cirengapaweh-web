<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>@yield("title", "Cireng A'paweh")</title>
    <meta name="description" content="@yield("meta_description", "Cireng A'paweh menyajikan produk cireng salju, cireng kuah keju creamy, dan cireng kuah seblak isi ayam suwir.")" />
    <meta name="keywords"
        content="Cireng A'paweh, cireng Bandung, cireng salju, promo cireng, cireng kuah keju, cireng seblak, franchise cireng, mitra cireng" />
    <meta name="robots" content="index, follow" />
    <meta name="author" content="Cireng A'paweh" />

    <link rel="canonical" href="{{ url()->current() }}" />

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />

    <meta property="og:locale" content="id_ID" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Cireng A'paweh" />
    <meta property="og:title" content="@yield("title", "Cireng A'paweh")" />
    <meta property="og:description" content="Cireng A'paweh - Promo Cireng Salju, Kuah Keju, Seblak, dan Franchise" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ asset('assets/img/produk/Cireng Isi Ayam Suwir Kuah Keju Creamy.jpg') }}" />
    <meta property="og:image:alt" content="Produk cireng unggulan Cireng A'paweh" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="@yield("title", "Cireng A'paweh")" />
    <meta name="twitter:description"
        content="Cireng A'paweh - Promo Cireng Salju, Cireng Kuah Keju, Cireng Kuah Seblak, dan Franchise Cireng" />
    <meta name="twitter:image" content="{{ asset('assets/img/produk/Cireng Isi Ayam Suwir Kuah Keju Creamy.jpg') }}" />

    <link rel="stylesheet" href="{{ asset('css/global.css') }}" />

    @stack("styles")
</head>

<body>
    {{-- @include("components.navbar") --}}

    <main>
        @yield("content")
    </main>

    {{-- @include("components.footer") --}}

    @stack("scripts")
</body>

</html>
