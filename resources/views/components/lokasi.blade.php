<div class="lokasi flexCol" id="lokasi">
    <div class="wrapBreakpoint flexCol">
        <div class="breakpoint">

            <div class="sectionHeader">
                <h1 class="displayH1">
                    Beda <span class="primaryBrandRed">Lokasi</span>, Satu <span class="accentCheeseYellow">Cita
                        Rasa!</span>
                </h1>
                <h3 class="subH3">Bukan cuma soal pedas lumer, temukan juga komitmen kami di setiap sajiannya.</h3>
            </div>

            <!-- Konten -->
            <div class="flexRow">
                @forelse ($locations as $location)
                    @if ($location->is_active && (stripos($location->name, 'Pusat') !== false))
                        <div class="cardM shadow main">
                            <img class="locationIcon" src="{{ asset('assets/icon/lokasiCMain.svg') }}" alt="Lokasi">
                            <div class="flexCol">
                                <h4 class="subH4">{{ $location->name }}</h4>
                                <p class="bodyMain charcoalGrey">{{ $location->address }}</p>
                            </div>
                            <a class="btnCardM" href={{ $location->link }} target="_blank" rel="noopener">
                                <img class="btnIcon" src="{{ asset('assets/icon/tautanBlack.svg') }}" alt="Maps">
                                <div class="bodyMain">
                                    Lihat di Maps
                                </div>
                            </a>
                        </div>
                    @endif
                @empty
                @endforelse
                @forelse ($locations as $location)
                    @if ($location->is_active && (stripos($location->name, 'Pusat') === false))
                        <div class="cardM shadow primaryBrandRed">
                            <img class="locationIcon" src="{{ asset('assets/icon/lokasiC.svg') }}" alt="Lokasi">
                            <div class="flexCol">
                                <h4 class="subH4">{{ $location->name }}</h4>
                                <p class="bodyMain charcoalGrey">{{ $location->address }}</p>
                            </div>
                            <a class="btnCardM primaryBrandRed" href={{ $location->link }} target="_blank" rel="noopener">
                                <img class="btnIcon" src="{{ asset('assets/icon/tautanRed.svg') }}" alt="Maps">
                                <div class="bodyMain">
                                    Lihat di Maps
                                </div>
                            </a>
                        </div>
                    @endif
                @empty
                    <div class="menuPage">
                        <p class="bodyLg charcoalGrey empty">Wah, lokasi cireng A'paweh belum tersedia saat ini</p>
                    </div>  
                @endforelse
            </div> <!-- end konten -->

            <a class="btnPrimary" href="/tentang-kami">Kenali Kami Lebih Dekat</a>

        </div> <!-- end breakpoint -->
    </div> <!-- end wrapBreakpoint -->
</div> <!-- end lokasi -->
