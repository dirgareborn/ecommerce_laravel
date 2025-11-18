<div class="container-fluid header bg-white p-0">
    <div class="row g-0 align-items-center flex-column-reverse flex-md-row" style="min-height: 100vh;">

        <!-- Kolom Kiri: Teks -->
        <div class="col-12 col-md-6 d-flex flex-column justify-content-center p-5 order-2 order-md-1"
            style="min-height: 50vh;">

            <h1 class="display-5 fw-bold mb-4 animated fadeIn">
                Ayo Menikah <span class="text-primary">Di Gedung UNM</span>
            </h1>
            <h3>Dengan Harga Bersahabat</h3>
            <p class="fs-5 text-muted mb-4 animated fadeIn">
                Kami menyediakan gedung pernikahan berkapasitas 2000 undangan dengan parkiran terluas di Kota
                Makassar.
            </p>

            <a href="{{ url('/kategori') }}" class="btn btn-primary py-3 px-5 animated fadeIn">Booking</a>
        </div>

        <!-- Kolom Kanan: Slider -->
        <div class="col-12 col-md-6 position-relative order-1 order-md-2" style="min-height: 50vh;">
            <div class="owl-carousel header-carousel h-100">
                @forelse($homeSliderBanners as $key => $banner)
                    <div class="owl-carousel-item position-relative w-100 h-100 {{ $key == 0 ? 'active' : '' }}">
                        <img class="w-100 h-100"
                            src="{{ isset($banner['image']) && file_exists(public_path('front/images/banners/' . $banner['image']))
                                ? asset('front/images/banners/' . $banner['image'])
                                : asset('front/img/default-banner.jpg') }}"
                            alt="Slide {{ $key + 1 }}" style="object-fit: cover;">
                    </div>
                @empty
                    <div class="owl-carousel-item position-relative w-100 h-100 active">
                        <img class="w-100 h-100" src="{{ asset('front/img/carousel-1.jpg') }}" alt="Default Slide 1"
                            style="object-fit: cover;">
                    </div>
                    <div class="owl-carousel-item position-relative w-100 h-100">
                        <img class="w-100 h-100" src="{{ asset('front/img/carousel-2.jpg') }}" alt="Default Slide 2"
                            style="object-fit: cover;">
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
