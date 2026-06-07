<div class="menu flexCol" id="menu">
    <div class="wrapBreakpoint flexCol">
        <div class="breakpoint">

            <div class="sectionHeader">
                <h1 class="displayH1">
                    Daftar <span class="primaryBrandRed">Menu</span> Cireng<span class="accentCheeseYellow">
                        A'paweh</span>
                </h1>
                <h3 class="subH3">Pilih sensasi pedas lumer favoritmu! Tersedia menu matang siap santap dan frozen food
                    praktis.</h3>
                <h3 class="subH3">
                    <div class="primaryBrandRed">Geser ke kanan</div> dan pilih sensasi pedas lumer favoritmu! Tersedia
                    menu matang siap santap dan <i>frozen food</i> praktis.
                </h3>
            </div>

            <div class="menuCategory flexRow animated">
                <button class="btnPrimary" data-category="semua">Semua</button>
                <button class="btnSoft" data-category="fastfood">Fastfood</button>
                <button class="btnSoft" data-category="frozenfood">Frozen Food</button>
            </div>

            <div class="corouselWrap animated">
                <button id="LButton" class="btnSlider LButton">
                    <img src="{{ asset('assets/icon/LButton.svg') }}" alt="Prev">
                </button>

                <div class="flexRow" id="menuScrollContain">
                    @php $chunks = $products->chunk(6); @endphp
                    <!-- page corousel -->
                    @forelse($chunks as $chunk)
                        <div class="menuPage">
                            @foreach ($chunk as $product)
                                @php
                                    $dataCategory = str_replace('_', '', strtolower($product->category));
                                @endphp

                                <div class="cardContain" data-category="{{ $dataCategory }}">
                                    <article class="cardC shadow">
                                        <img class="shadowLight"
                                            src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.net/400x600.png' }}"
                                            alt="{{ $product->name }}"
                                            onerror="this.src='https://placehold.net/400x600.png'">

                                        <h3 class="subH3">{{ $product->name }}</h3>
                                        <p class="bodyMain">
                                            {{ \Illuminate\Support\Str::limit($product->description, 80) }}
                                        </p>

                                        <div class="rp">
                                            <strong class="subH4 primaryBrandRed">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </strong>

                                            <form method="GET" action="{{ url('/produk') }}">
                                                <input type="hidden" name="id" value="{{ $product->id }}">
                                                <button type="submit" class="btnPrimary">Lihat Produk</button>
                                            </form>
                                        </div>
                                    </article>
                                </div> <!-- end card contain -->
                            @endforeach
                        </div> <!-- end menu page -->
                    @empty
                        <div class="menuPage">
                            <p class="bodyLg charcoalGrey empty">Wah, belum ada produk A'paweh yang tersedia</p>
                        </div>
                    @endforelse
                    <!-- end page corousel -->
                </div> <!-- end flexRow#menuScrollContain -->

                <button id="RButton" class="btnSlider RButton">
                    <img src="{{ asset('assets/icon/RButton.svg') }}" alt="Next">
                </button>
            </div> <!-- end corouselWrap -->

        </div> <!-- end breakpoint -->
    </div> <!-- end wrapBreakpoint -->
</div> <!-- end menu -->

@push('scripts')
    <script src="{{ asset('js/components/menu.js') }}"></script>
@endpush
