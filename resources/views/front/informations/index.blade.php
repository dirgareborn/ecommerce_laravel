@extends('front.layouts.app')

@section('content')
@include('front.partials.breadcumb')
<div class="container py-5">
    <h2 class="mb-4 text-center">Pengetahuan Tanpa Informasi Hanyalah Potensi</h2>
    <div class="row g-4">
        @foreach($informations as $info)
        <div class="col-md-4">
            <div class="card h-100">
                <!-- <img src="{{ is_img(asset('front/images/information/'.$info->image)) }}" class="card-img-top" alt="{{ $info->title }}"> -->
                <img src="{{ first_image_or_default($info->content, asset('front/img/no-image.webp')) }}" 
     class="card-img-top" alt="{{ $info->title }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $info->title }}</h5>
                    <p class="card-text text-truncate">{!! Str::limit(strip_tags($info->content), 150) !!}</p>
                    <a href="{{ route('informasi.show', $info->slug) }}" class="btn btn-navy btn-sm">Selengkapnya</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $informations->links('vendor.pagination.custom') }}
    </div>
</div>
@endsection
