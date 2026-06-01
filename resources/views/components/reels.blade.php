<div class="reels flexCol" id="reels">
    <div class="wrapBreakpoint flexCol">
        <div class="breakpoint">

            <div class="sectionHeader">
                <h1 class="displayH1">
                    Awas<span class="primaryBrandRed"> Ngiler </span>Lihat<span class="primaryBrandRed"> Video Ini!</span>
                </h1>
                <h3 class="subH3">Temukan inspirasi cara asyik nikmatin cireng, racikan kuah pedas, dan berbagai
                    keseruan di balik layar A'paweh.</h3>
            </div>

            <div class="corouselWrap">
                <button id="LButtonReels" class="btnSlider LButtonReels">
                    <img src="{{ asset('assets/icon/LButton.svg') }}" alt="Prev">
                </button>

                <div class="flexRow" id="reelsScrollContain">
                    <div class="phoneContain">
                        <img src="{{ asset('assets/icon/btnMute.svg') }}" alt="Muted" class="btnAudio"
                            id="btnMute">
                        <img class="phoneFrameImg" src="{{ asset('assets/img/dekorasi/phoneFrame.svg') }}"
                            alt="Frame HP">
                        <div class="phone">
                            <video loop playsinline muted src="{{ asset('assets/video/cireng/Iklan.mp4') }}"></video>
                        </div>
                    </div>

                    <div class="phoneContain">
                        <img src="{{ asset('assets/icon/btnMute.svg') }}" alt="Muted" class="btnAudio"
                            id="btnMute">
                        <img class="phoneFrameImg" src="{{ asset('assets/img/dekorasi/phoneFrame.svg') }}"
                            alt="Frame HP">
                        <div class="phone">
                            <video loop playsinline muted src="{{ asset('assets/video/cireng/Cireng Kuah Keju.mp4') }}"></video>
                        </div>
                    </div>

                    <div class="phoneContain">
                        <img src="{{ asset('assets/icon/btnMute.svg') }}" alt="Muted" class="btnAudio"
                            id="btnMute">
                        <img class="phoneFrameImg" src="{{ asset('assets/img/dekorasi/phoneFrame.svg') }}"
                            alt="Frame HP">
                        <div class="phone">
                            <video loop playsinline muted src="{{ asset('assets/video/cireng/Best Seller.mp4') }}"></video>
                        </div>
                    </div>

                    <div class="phoneContain">
                        <img src="{{ asset('assets/icon/btnMute.svg') }}" alt="Muted" class="btnAudio"
                            id="btnMute">
                        <img class="phoneFrameImg" src="{{ asset('assets/img/dekorasi/phoneFrame.svg') }}"
                            alt="Frame HP">
                        <div class="phone">
                            <video loop playsinline muted src="{{ asset('assets/video/cireng/Kebonaki.mp4') }}"></video>
                        </div>
                    </div>

                    <div class="phoneContain">
                        <img src="{{ asset('assets/icon/btnMute.svg') }}" alt="Muted" class="btnAudio"
                            id="btnMute">
                        <img class="phoneFrameImg" src="{{ asset('assets/img/dekorasi/phoneFrame.svg') }}"
                            alt="Frame HP">
                        <div class="phone">
                            <video loop playsinline muted src="{{ asset('assets/video/cireng/Review.mp4') }}"></video>
                        </div>
                    </div>
                </div> <!-- end flexRow -->

                <button id="RButtonReels" class="btnSlider RButtonReels">
                    <img src="{{ asset('assets/icon/RButton.svg') }}" alt="Next">
                </button>
            </div> <!-- end corouselWrap -->

        </div> <!-- end breakpoint -->
    </div> <!-- end wrapBreakpoint -->
</div> <!-- end reels -->

@push('scripts')
    <script src="{{ asset('js/components/reels.js') }}"></script>
@endpush
