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
@include('front.partials.breadcumb')

<!-- Product Grid Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-4">
            @foreach($categoryProducts as $product)
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border border-light shadow-sm position-relative overflow-hidden rounded-3">
                    <!-- Product Image -->
                    @php
                        $imgPath = isset($product['images'][0]['image']) && !empty($product['images'][0]['image']) 
                                   ? 'front/images/products/'.$product['product_image'] 
                                   : 'front/img/property-1.jpg';
                    @endphp
                    <div class="overflow-hidden position-relative" style="min-height: 220px;">
                        <a href="{{ url('kategori/'.$product['category']['url'].'/'.$product['url']) }}">
                            <img src="{{ asset($imgPath) }}" class="card-img-top transition w-100 h-100" style="object-fit: cover;">
                        </a>
                        <!-- Badge -->
                        <span class="badge bg-primary position-absolute top-0 start-0 m-3 py-1 px-2">Di Sewakan</span>
                        <span class="badge bg-white text-primary position-absolute bottom-0 start-0 m-3 py-1 px-2 rounded-top">
                            {{ $product['category']['category_name'] }}
                        </span>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body d-flex flex-column p-3">
                        <h5 class="text-primary mb-2 fw-bold">@currency($product['product_price'])</h5>
                        <a href="{{ url('kategori/'.$product['category']['url'].'/'.$product['url']) }}" class="h6 d-block mb-2 text-decoration-none text-dark">
                            {{ $product['product_name'] }}
                        </a>
                        <p class="mb-3 text-muted small">
                            <i class="fas fa-map-marker-alt text-primary me-1"></i>
                            <a target="_blank" href="{{ $product['locations']['maps'] }}" class="text-muted text-decoration-none">
                                {{ $product['locations']['name'] }}
                            </a>
                        </p>
                        <a href="{{ url('kategori/'.$product['category']['url'].'/'.$product['url']) }}" class="btn btn-outline-primary mt-auto">Sewa Sekarang</a>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Browse More -->
            <div class="col-12 text-center mt-4">
                <a href="#" class="btn btn-lg btn-primary px-5 py-3">Browse More Properties</a>
            </div>
        </div>
    </div>
</div>
<!-- Product Grid End -->

<!-- Custom Styles for hover and card -->


@endsection
