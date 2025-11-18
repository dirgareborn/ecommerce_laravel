<div class="hero-parallax-slider">
    @forelse($homeSliderBanners as $key => $banner)
        <div class="bg-layer {{ $key == 0 ? 'show' : '' }}"
             style="background-image: url('{{ isset($banner['image']) && file_exists(public_path('front/images/banners/' . $banner['image']))
                ? asset('front/images/banners/' . $banner['image'])
                : asset('front/img/default-banner.jpg') }}');">
            <div class="hero-overlay"></div>
            <div class="hero-content text-center">
                <h1 class="display-4 fw-bold text-white hero-title">{{ $banner['title'] ?? 'Selamat Datang' }}</h1>
                @if(!empty($banner['alt']))
                    <h2 class="fs-5 fw-bold text-white hero-subtitle py-2">{{ $banner['alt'] }}</h2>
                @endif
                @if(!empty($banner['button_text']))
                    <a href="{{ $banner['button_url'] ?? '#' }}" class="btn btn-primary btn-lg px-5 py-3 mt-3">
                        {{ $banner['button_text'] }}
                    </a>
                @endif
            </div>
        </div>
    @empty
        <!-- Default slide -->
        <div class="bg-layer show" style="background-image: url('{{ asset('front/img/carousel-1.jpg') }}')">
            <div class="hero-overlay"></div>
            <div class="hero-content text-center">
                <h1 class="display-4 fw-bold text-white">Selamat Datang</h1>
                <p class="fs-5 text-white">Kapasitas 2000 undangan & parkiran terluas di Makassar</p>
            </div>
        </div>
    @endforelse
</div>
