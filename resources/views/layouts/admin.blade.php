<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', "Admin Dashboard - Cireng A'paweh")</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('assets/img/logo/logoOfficial.svg') }}?v=1" type="image/x-icon" />

    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/style.css') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link
        href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Poppins:wght@400;500;600;700&family=Fredoka:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    @stack('styles')
</head>

<body>
    <header class="header">
        <div class="headerLeft">
            <h1 class="headerTitle">Cireng A'paweh Admin Dashboard</h1>
        </div>
        <div class="headerRight">
            <div class="profileSection">
                <div class="profileInfo">
                    <span class="profileName">{{ auth()->user()->name }}</span>
                    <span class="profileRole">
                        @if(auth()->user()->role === 'superadmin')
                            Super Admin
                        @else
                            Staff
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <aside class="sidebar">
            <nav class="navbar">
                <a href="{{ url('admin/dashboard') }}"
                    class="navItem {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">home</span>
                    <span class="navText">Beranda</span>
                </a>
                <a href="{{ url('admin/produk') }}" class="navItem {{ request()->is('admin/produk') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">cookie</span>
                    <span class="navText">Manajemen Produk</span>
                </a>
                <a href="{{ url('admin/lokasi') }}" class="navItem {{ request()->is('admin/lokasi') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">location_on</span>
                    <span class="navText">Manajemen Lokasi</span>
                </a>
                <a href="{{ url('admin/promo') }}" class="navItem {{ request()->is('admin/promo') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">local_offer</span>
                    <span class="navText">Promo</span>
                </a>
                <a href="{{ url('admin/pemesanan') }}" class="navItem {{ request()->is('admin/pemesanan') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">receipt_long</span>
                    <span class="navText">Pemesanan</span>
                </a>
                <a href="{{ url('admin/chat') }}" class="navItem {{ request()->is('admin/chat') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">chat</span>
                    <span class="navText">Chat</span>
                </a>
            </nav>

            <nav class="navbarBottom">
                <a href="{{ url('admin/pengguna') }}"
                    class="navItem {{ request()->is('admin/pengguna') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">person</span>
                    <span class="navText">Pengguna</span>
                </a>
                <form method="POST" action="{{ route('admin.logout') }}" style="width: 100%;">
                    @csrf
                    <button type="submit" class="navItem logout" style="width: 100%; text-align: left; border: none; background: none; cursor: pointer;">
                        <span class="material-symbols-outlined">logout</span>
                        <span class="navText">Keluar</span>
                    </button>
                </form>
            </nav>
        </aside>

        <main class="mainContent">
            <div class="contentArea">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="{{ asset('js/admin/script.js') }}"></script>
    @stack('scripts')
</body>

</html>
