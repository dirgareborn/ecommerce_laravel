@extends('front.layouts.app')

@section('content')
@include('front.partials.breadcumb')
<!-- Category Section -->
<div class="container-xxl py-4">
    <div class="container">
        <div class="text-center mx-auto mb-4 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 900px;">
            <h2 class="mb-3">Badan Pengembangan Bisnis Universitas Negeri Makassar</h2>
            <p class="mb-0">
                Badan Pengembangan Bisnis (BPB) BLU UNM merupakan badan yang secara khusus melakukan kegiatan bisnis dan transaksi keuangan non-akademik, mulai dari penciptaan, pengelolaan, hingga pengembangan bisnis dalam ruang lingkup BLU UNM Makassar serta kerja sama dengan berbagai pihak di dalam maupun luar negeri.
            </p>
        </div>

        <div class="row g-4 d-flex justify-content-center">
            @forelse($categories as $category)
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                    <a class="cat-item d-block bg-light text-center rounded p-3 " href="{{ url('kategori', $category->url) }}">
                        <div class="rounded p-4">
                        <div class="icon mb-3">
                            <img class="img-fluid" src="{{ asset('front/img/categories/' . $category->category_image) }}" alt="{{ $category->category_name }}">
                        </div>
                        <h6 class="mb-1">{{ $category->category_name }}</h6>
                        <span>{{ $category->products->count() }} Produk</span>
                    </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Belum ada kategori yang tersedia.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
