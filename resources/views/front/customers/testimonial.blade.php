@extends('front.layouts.app')

@section('content')
@include('front.partials.breadcumb')

<div class="container-xl py-5">
    <div class="container">
        {{-- Notifikasi --}}
        @if(Session::has('error_message'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fa fa-exclamation-circle me-2"></i>{{ Session::get('error_message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(Session::has('success_message'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fa fa-check-circle me-2"></i>{{ Session::get('success_message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-0 gx-5">
            @include('front.customers.sidebar')
            <div class="col-12 col-md-8 mt-2 mt-md-0">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-header bg-primary text-white border-0 text-center position-relative" style="height: 36px;">
                        <div class="position-absolute top-50 start-50 translate-middle text-center">
                            <h6 class="fw-bold m-0 text-white"><i class="fa fa-comment-dots me-1"></i> Testimonial Saya</h6>
                        </div>
                    </div>

                    <div class="card-body bg-light p-4">
                        {{-- Form Tambah Testimonial --}}
                        <form method="POST" action="{{ url('testimonial') }}" class="mb-4">
                            @csrf
                            <label class="form-label fw-semibold">Tambahkan Testimonial Baru</label>
                            <div class="input-group">
                                <textarea name="description" class="form-control" rows="3" placeholder="Tulis testimonial Anda... 😊" required>{{ old('description') }}</textarea>
                            </div>
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa fa-paper-plane me-1"></i> Kirim
                                </button>
                            </div>
                        </form>

                        {{-- Daftar / Riwayat Testimonial --}}
                        <hr>
                        <h6 class="fw-bold text-secondary mb-3">
                            <i class="fa fa-history me-2"></i> Riwayat Testimonial
                        </h6>

                        @if($testimonials->isEmpty())
                            <div class="text-muted text-center py-4">
                                <i class="fa fa-inbox fa-2x mb-2"></i>
                                <p>Belum ada testimonial yang dikirim.</p>
                            </div>
                        @else
                            <div class="list-group">
                                @foreach($testimonials as $item)
                                    <div class="list-group-item bg-white border-0 mb-2 rounded shadow-sm">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <p class="mb-1">{{ $item->description }}</p>
                                                <small class="text-muted">
                                                    <i class="fa fa-calendar me-1"></i>
                                                    {{ $item->created_at->format('d M Y, H:i') }}
                                                </small>
                                            </div>
                                            <span class="badge 
                                                @if($item->status == 1) bg-success 
                                                @elseif($item->status == 0) bg-warning text-dark 
                                                @else bg-secondary 
                                                @endif
                                                rounded-pill">
                                                {{ $item->status == 1 ? 'Disetujui' : ($item->status == 0 ? 'Menunggu' : 'Ditolak') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="card-footer bg-white text-muted small text-center py-2 border-top">
                        <i class="fa fa-clock me-1"></i>
                        Terakhir diperbarui: 
                        {{ $getTestimonial?->updated_at ? $getTestimonial->updated_at->format('d M Y, H:i') : 'Belum pernah' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
