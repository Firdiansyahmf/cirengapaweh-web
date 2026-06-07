<footer class="footer flexCol" id="footer">
    <div class="breakpoint cleanWhite">

        <div class="box1 flexRow">

            <div class="flexCol">
                <div class="flexRow">

                    <img id="logo" src="{{ asset('assets/img/logo/logo.svg') }}" alt="Cireng A'paweh">

                    <div
                    class="flexCol">
                        <h2 class="displayH2 accentCheeseYellow">Cireng A'paweh</h2>
                        <div class="caption">Cemilan Sunda Modern</div>
                    </div>

                </div>

                <div class="caption">
                    Melestarikan cita rasa autentik Sunda melalui inovasi camilan lumer yang praktis dan kekinian.
                </div>

            </div>

            <div id="responsiveMenu">
                <div class="flexCol aMenu">
                    <h2 class="displayH2 accentCheeseYellow">Jelajahi</h2>

                    <a class="caption" href="{{ Request::is('/') ? '#hero' : '/#hero' }}">Beranda</a>
                    <a class="caption" href="{{ Request::is('/') ? '#promo' : '/#promo' }}">Promo</a>
                    <a class="caption" href="{{ Request::is('/') ? '#menu' : '/#menu' }}">Menu</a>
                    <a class="caption" href="{{ Request::is('/') ? '#reels' : '/#reels' }}">Video</a>
                    <a class="caption" href="{{ Request::is('/') ? '#mitra' : '/#mitra' }}">Kemitraan</a>
                    <a class="caption" href="/tentang-kami">Tentang Kami</a>
                    <a class="caption" href="/cek-order">Cek Order</a>
                </div>

                <div class="flexCol aMenu">
                    <h2 class="displayH2 accentCheeseYellow">Hubungi Kami</h2>

                    <a class="caption" href="https://wa.me/6281944327907">
                        <img src="{{ asset('assets/icon/fTelepon.svg') }}" alt="Nomor Telp">+62 819-4432-7907</a>

                    <a class="caption" href="mailto:cirengapaweh@gmail.com"><img src="{{ asset('assets/icon/fMail.svg') }}" alt="Email">cirengapaweh@gmail.com</a>

                    <a class="caption" href="https://maps.app.goo.gl/UrTHxx5Lu5f27SbK9" target="_blank" rel="noopener "><img src="{{ asset('assets/icon/fLokasi.svg') }}" alt="Lokasi">Ruko Gate 2 Tel-U (Pusat)</a>
                </div>
            </div>

        </div> <!-- end box1 -->

        <div class="box2 flexRow">

            <div class="caption"><small>&copy; {{ date('Y') }} Cireng A'Paweh. All rights reserved</small></div>

            <div class="medsos flexRow">

                <a href="https://www.instagram.com/cirengapawe/" target="_blank" rel="noopener "><img src="{{ asset('assets/icon/instagram.svg') }}" alt="Instagram"></a>

                <a href="https://www.tiktok.com/@cirengaapweh" target="_blank" rel="noopener "><img src="{{ asset('assets/icon/tiktok.svg') }}" alt="TikTok"></a>

                <a href="https://wa.me/6281944327907" target="_blank" rel="noopener "><img src="{{ asset('assets/icon/whatsApp.svg') }}" alt="WhatsApp"></a>

            </div> <!-- end medsos -->

        </div> <!-- end box2 -->

    </div> <!-- end breakpoint -->
</footer> <!-- end ctaWA -->
