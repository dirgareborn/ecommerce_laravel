@extends('front.layouts.app')

@section('content')
@include('front.partials.breadcumb')

<!-- Page Content -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-6">
            <div class="col-lg-12 wow fadeIn" data-wow-delay="0.5s">
                @if(isset($visimisi))
                    <h3 class="text-primary mb-3 bg-title-navy">VISI</h3>
                    <p>{!! $visimisi->visi !!}</p>

                    <h3 class="text-primary mb-3 bg-title-navy">MISI</h3>
                    <p>{!! $visimisi->misi !!}</p>
                @else
                    <h4 class="text-center text-danger">
                        <i class="fa fa-times"></i> Data tidak ditemukan.
                    </h4>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
