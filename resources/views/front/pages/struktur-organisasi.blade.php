@extends('front.layouts.app')

@section('content')
@include('front.partials.breadcumb')

<!-- Content Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-0 text-center">
            <div class="col-lg-12 wow fadeIn" data-wow-delay="0.5s">
                @if(isset($strukturorganisasi))
                    <img class="img-fluid"
                         src="{{ asset('images/' . $strukturorganisasi->file_struktur_organisasi) }}"
                         alt="Struktur Organisasi"
                         style="max-width: 100%; height: auto;">
                @else
                    <h4 class="text-center text-danger mt-4">
                        <i class="fa fa-times"></i> Data tidak ditemukan.
                    </h4>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- Content End -->
@endsection
