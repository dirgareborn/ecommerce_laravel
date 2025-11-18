@extends('front.layouts.app')

@section('content')
<div class="container-xxl py-5">
    <div class="container text-center">
        {{-- Error Image / Icon --}}
        <div class="wow fadeInUp mb-4" data-wow-delay="0.1s">
            <i class="fas fa-exclamation-triangle text-danger" style="font-size: 80px;"></i>
        </div>

        {{-- Error Code --}}
        <h1 class="display-1 fw-bold text-primary wow fadeInUp" data-wow-delay="0.2s">404</h1>

        {{-- Error Message --}}
        <h3 class="mb-4 wow fadeInUp" data-wow-delay="0.3s">Oops! Halaman Tidak Ditemukan</h3>
        <p class="mb-4 wow fadeInUp text-muted" data-wow-delay="0.4s">
            Maaf, halaman yang Anda cari tidak ada atau sudah dipindahkan.  
            Silakan kembali ke beranda atau gunakan navigasi untuk menemukan halaman yang diinginkan.
        </p>

        {{-- Back to Home Button --}}
        <a href="{{ url('/') }}" class="btn btn-primary btn-lg wow fadeInUp" data-wow-delay="0.5s">
            Kembali ke Beranda
        </a>

    
    </div>
</div>
@endsection
