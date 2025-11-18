@extends('front.layouts.app')

@section('content')
@include('front.partials.breadcumb')

<!-- Property List Start -->
<div class="container-xxl py-4">
    <div class="container">
        <div class="row g-0 gx-5 align-items-end">
            <div class="col-lg-6">
                <div class="text-start mx-auto mb-4 wow slideInLeft" data-wow-delay="0.1s">
                    <h2 class="mb-2">{{ $page_title }}</h2>
                    <p class="mb-0">{{ $page_description }}</p>
                </div>
            </div>
            <div class="col-lg-6 text-start text-lg-end wow slideInRight" data-wow-delay="0.1s">
                <ul class="nav nav-pills d-inline-flex justify-content-end mb-4">
                    <li class="nav-item me-2">
                        <a class="btn btn-outline-primary active" data-bs-toggle="pill" href="#tab-1">Featured</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="btn btn-outline-primary" data-bs-toggle="pill" href="#tab-2">For Sell</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-primary" data-bs-toggle="pill" href="#tab-3">For Rent</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content">
            <div id="tab-1" class="tab-pane fade show p-0 active">
                <div class="row g-4">
                    @if(count($layanans) > 0)
                        @foreach($layanans as $layanan)
                            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                                <div class="property-item rounded overflow-hidden">
                                    <div class="position-relative overflow-hidden">
                                        <a href="">
                                            <img class="img-fluid" src="{{ asset('front/images/products/' . $layanan->product_image) }}" alt="">
                                        </a>
                                        <div class="bg-primary rounded text-white position-absolute start-0 top-0 m-3 py-1 px-3">For Rent</div>
                                        <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-3 pt-1 px-3">
                                            {{ $layanan->product_name }}
                                        </div>
                                    </div>
                                    <div class="p-3 pb-0">
                                        <h6 class="text-primary mb-2">@currency($layanan->product_price)</h6>
                                        <a class="d-block h6 mb-1" href="{{ route('layanan.kategori.detail',['kategori' => $kategoriLayanan->url, 'produk' => $layanan->url]) }}">
                                            {{ $layanan->product_name }}
                                        </a>
                                        <p class="small mb-3"><i class="fa fa-map-marker-alt text-primary me-2"></i>{{ $layanan->locations->name }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <h5 class="text-center"><span class="fa fa-times"></span> Data tidak ditemukan.</h5>
                    @endif
                </div>
                {{ $layanans->links() }}
            </div>
        </div>
    </div>
</div>
<!-- Property List End -->
@endsection
