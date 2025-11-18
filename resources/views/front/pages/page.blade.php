@extends('front.layouts.app')

@section('content')
<!-- Header Start -->
@foreach($cmspageDetail as $page)
<div class="container-fluid bg-white p-0">
    <div class="row g-0 align-items-center flex-column-reverse flex-md-row" style="min-height: 300px;">
        <div class="col-md-6 p-5 mt-lg-3 d-flex flex-column justify-content-center">
            <h1 class="display-6 animated fadeIn mb-3">{{ $page['title'] }}</h1>
            <nav aria-label="breadcrumb" class="animated fadeIn">
                <ol class="breadcrumb text-uppercase mb-0">
                    @for($i = 1; $i <= count(Request::segments()); $i++)
                        <li class="breadcrumb-item">
                            <a href="{{ URL::to(implode('/', array_slice(Request::segments(), 0, $i, true))) }}">
                                {{ strtoupper(Request::segment($i)) }}
                            </a>
                        </li>
                    @endfor
                </ol>
            </nav>
        </div>

        <div class="col-md-6 animated fadeIn" style="height: 300px; overflow: hidden;">
            <img class="img-fluid w-100 h-100"
                 src="{{ asset('front/img/header.jpg') }}"
                 alt="Header Image"
                 style="object-fit: cover;">
        </div>
    </div>
</div>

@include('front.partials.search')
<!-- Header End -->

<!-- Content -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-6">
            <div class="col-lg-12 wow fadeIn" data-wow-delay="0.5s">
                <h3 class="text-primary mb-3 text-center">{{ $page['title'] }}</h3>
                <p>{!! $page['description'] !!}</p>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
