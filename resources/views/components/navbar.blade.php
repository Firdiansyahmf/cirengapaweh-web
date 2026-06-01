<nav class="navbar navbarCustomCireng">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('assets/img/logo/logoOfficial.svg') }}" alt="Logo">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span>
                <img src="{{ asset('assets/icon/humbergerMenu.svg') }}" alt="Humberger Menu">
            </span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ Request::is('/') ? '#hero' : '/#hero' }}">Beranda</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ Request::is('/') ? '#promo' : '/#promo' }}">Promo</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ Request::is('/') ? '#menu' : '/#menu' }}">Menu</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ Request::is('/') ? '#reels' : '/#reels' }}">Video</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ Request::is('/') ? '#mitra' : '/#mitra' }}">Kemitraan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('tentang-kami') ? 'active' : '' }}" href="/tentang-kami">Tentang Kami</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
