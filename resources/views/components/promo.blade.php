<div class="promoContain flexCol">
    <div class="promo">

        <img id="gedungSate" src="{{ asset('assets/img/dekorasi/gedungSate.svg') }}" alt="Gedung Sate">

        <div class="breakpoint" id="promo">

            <div class="sectionHeader cleanWhite">
                <h1 class="displayH1">Promo <span class="accentCheeseYellow">Spesial</span> Khusus Hari Ini!</h1>
                <h3 class="subH3">Yuk, ambil promonya sekarang buat nemenin waktu bersantai kamu!</h3>
                <h3 class="subH3">
                    <div class="accentCheeseYellow">Geser ke kanan</div> untuk ambil promonya sekarang buat nemenin
                    waktu bersantai kamu!
                </h3>
            </div>

            <div class="flexRow">
                @forelse($promos as $promo)
                    @foreach ($promo->products as $product)
                        @if ($product->is_active)
                            @php
                                $discountAmount = ($product->price * $promo->discount_percentage) / 100;
                                $finalPrice = $product->price - $discountAmount;
                            @endphp

                            <div class="cardContain">
                                <article class="cardC shadow">
                                    <img class="shadowLight"
                                        src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/400x600.png' }}"
                                        alt="{{ $product->name }}"
                                        onerror="this.src='https://placehold.co/400x600.png'">

                                    <h3 class="subH3">{{ $product->name }}</h3>
                                    <p class="bodyMain">
                                        {{ \Illuminate\Support\Str::limit($product->description, 40) }}
                                    </p>

                                    <div class="rp">
                                        <div class="subH4 charcoalGrey">
                                            <span>
                                                Diskon {{ $promo->discount_percentage }}%
                                            </span>
                                            <div>
                                                <span class="subH4 charcoalGrey coret">
                                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                                </span>
                                                <strong class="subH4 primaryBrandRed">
                                                    Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                                </strong>
                                            </div>
                                        </div>

                                        <form method="GET" action="{{ url('/produk') }}" style="width: 100%;">
                                            <input type="hidden" name="id" value="{{ $product->id }}">
                                            <input type="hidden" name="promo_id" value="{{ $promo->id }}">
                                            <button type="submit" class="btnPrimary" style="width: 100%;">Ambil Promo</button>
                                        </form>
                                    </div>
                                </article>
                            </div> <!-- end card contain -->
                        @endif
                    @endforeach
                @empty
                    <div class="kosong">
                        <p class="bodyLg cleanWhite empty">Wah, belum ada promo spesial hari ini dari A'paweh</p>
                    </div>
                @endforelse
            </div> <!-- end flexRow -->

        </div> <!-- end breakpoint -->

    </div> <!-- end promo -->
</div> <!-- end promoContain -->

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const slider = document.querySelector(".promo .flexRow");
        let isDown = false;
        let startX;
        let scrollLeft;
        slider.addEventListener("mousedown", (e) => {
            isDown = true;
            slider.classList.add("active");
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });
        slider.addEventListener("mouseleave", () => {
            isDown = false;
        });
        slider.addEventListener("mouseup", () => {
            isDown = false;
        });
        slider.addEventListener("mousemove", (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2;
            slider.scrollLeft = scrollLeft - walk;
        });
    });
</script>
