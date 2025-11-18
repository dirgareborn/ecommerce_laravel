@extends('front.layouts.app')
@push('style')
    <style>
        .card img.transition {
            transition: transform 0.4s ease, filter 0.4s ease;
        }

        .card:hover img.transition {
            transform: scale(1.05);
            filter: brightness(0.95);
        }

        .card-body h5 {
            font-size: 1rem;
        }

        .card-body h6 {
            font-size: 0.95rem;
        }
    </style>
@endpush
@section('content')
    <!-- Property List Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-0 gx-5 align-items-end">
                <!-- <div class="col-lg-6"> -->
                <div class="text-start mx-auto mb-5 wow slideInLeft" data-wow-delay="0.1s">
                    <h1 class="mb-3">LAYANAN PENUNJANG AKADEMIK</h1>
                    <!-- <p>Eirmod sed ipsum dolor sit rebum labore magna erat. Tempor ut dolore lorem kasd vero ipsum sit eirmod sit diam justo sed rebum.</p> -->
                </div>
                <!-- </div> -->
            </div>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane fade show p-0 active">
                    <div class="row g-4">
                        @foreach ($products as $product)
                            <div class="col-lg-3 col-md-6">
                                <div
                                    class="card h-100 border border-light shadow-sm position-relative overflow-hidden rounded-3">
                                    <!-- Product Image -->
                                    <div class="overflow-hidden position-relative" style="min-height: 220px;">
                                        <a
                                            href="{{ url('kategori/' . $product['category']['url'] . '/' . $product['url']) }}">
                                            <img src="{{ is_product($product['product_image']) }}"
                                                class="card-img-top transition w-100 h-100" style="object-fit: cover;">
                                        </a>
                                        <!-- Badge -->
                                        <span class="badge bg-primary position-absolute top-0 start-0 m-3 py-1 px-2">Di
                                            Sewakan</span>
                                        <span
                                            class="badge bg-white text-primary position-absolute bottom-0 start-0 m-3 py-1 px-2 rounded-top">
                                            {{ $product['category']['category_name'] }}
                                        </span>
                                    </div>

                                    <!-- Card Body -->
                                    <div class="card-body d-flex flex-column p-3">
                                        <h5 class="text-primary mb-2 fw-bold">@currency($product['product_price'])</h5>
                                        <a href="{{ url('kategori/' . $product['category']['url'] . '/' . $product['url']) }}"
                                            class="h6 d-block mb-2 text-decoration-none text-dark">
                                            {{ $product['product_name'] }}
                                        </a>
                                        <p class="mb-3 text-muted small">
                                            <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                            <a target="_blank" href="{{ $product['locations']['maps'] }}"
                                                class="text-muted text-decoration-none">
                                                {{ $product['locations']['name'] }}
                                            </a>
                                        </p>
                                        <a href="{{ url('kategori/' . $product['category']['url'] . '/' . $product['url']) }}"
                                            class="btn btn-outline-primary mt-auto">Sewa Sekarang</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <h2 class="mb-4 text-center bg-title-orange">Pengetahuan Tanpa Informasi Hanyalah Potensi</h2>
        <div class="row g-4">
            @foreach ($informations as $info)
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="{{ first_image_or_default($info->content, asset('front/img/no-image.webp')) }}"
                            class="card-img-top" alt="{{ $info->title }}" style="max-height: 365px;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $info->title }}</h5>
                            <p class="card-text text-truncate">{!! Str::limit(strip_tags($info->content), 150) !!}</p>
                            <a href="{{ route('informasi.show', $info->slug) }}"
                                class="btn btn-sm btn-navy">Selengkapnya</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $informations->links('vendor.pagination.custom') }}
        </div>
    </div>

    <!-- Property List End -->
@endsection
