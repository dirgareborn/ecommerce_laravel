@extends('front.layouts.app')

@push('style')
@endpush

@section('content')
@include('front.partials.breadcumb')

<div class="container-xl py-5">
    <div class="container">
        {{-- Notifikasi --}}
        @if(Session::has('error_message'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fa fa-exclamation-circle me-2"></i>
                {{ Session::get('error_message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(Session::has('success_message'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fa fa-check-circle me-2"></i>
                {{ Session::get('success_message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-0 gx-5">
            {{-- Sidebar --}}
            @include('front.customers.sidebar')

            {{-- Konten Utama --}}
            <div class="col-12 col-md-8 mt-2 mt-md-0">
                <div class="card border-0 shadow-sm overflow-hidden">
                    {{-- Header --}}
                    <div class="card-header bg-primary text-white border-0 text-center position-relative" style="height: 36px;">
                        <div class="position-absolute top-50 start-50 translate-middle text-center">
                            <h6 class="fw-bold m-0 text-white"><i class="bi bi-key-fill"></i> Perbaharui Kata Sandi</h6>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="card-body bg-light">
                        <form class="row g-3" action="{{ url('update-password') }}" method="post">
                            @csrf
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Kata Sandi Lama</label>
                                <input type="password" class="form-control" id="current_pwd" name="current_pwd">
                                <small class="text-danger" id="verifyCurrentPwd"></small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Kata Sandi Baru</label>
                                <input type="password" class="form-control" name="new_pwd" id="new_pwd">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" class="form-control" name="confirm_pwd" id="confirm_pwd">
                            </div>

                            <div class="col-12 text-end mt-3">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa fa-save me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Footer --}}
                    <div class="card-footer bg-white text-muted small text-center py-2 border-top">
                        <i class="fa fa-info-circle me-1"></i>
                        Pastikan kata sandi baru berbeda dari yang lama.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
