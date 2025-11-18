@extends('front.layouts.app')

@section('content')
@include('front.partials.breadcumb')

<div class="container-xl py-5">
    <div class="container">
        {{-- Notifikasi --}}
        @if(Session::has('error_message'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fa fa-exclamation-circle me-2"></i> {{ Session::get('error_message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(Session::has('success_message'))
            <div class="alert alert-primary alert-dismissible fade show shadow-sm" role="alert">
                <i class="fa fa-check-circle me-2"></i> {{ Session::get('success_message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-0 gx-5">
            {{-- Sidebar --}}
            @include('front.customers.sidebar')

            {{-- Profil --}}
            <div class="col-12 col-md-8 mt-2 mt-md-0">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-header bg-primary text-white border-0 text-center position-relative" style="height: 36px;">
                            @php
                                $user = Auth::guard('web')->user();
                                $imagePath = !empty($user->image)
                                    ? url('front/images/customers/'.$user->image)
                                    : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=198754&color=fff';
                            @endphp
                            <div class="position-absolute top-50 start-50 translate-middle text-center">
                            <h6 class="fw-bold m-0 text-white"><i class="bi bi-person-circle me-2"></i> Informasi Pribadi</h6>
                        </div>>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="card-body bg-light text-center pt-5">
                              <form method="POST" action="{{ route('update.profil') }}" enctype="multipart/form-data" class="text-start">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ $user->name ?? '' }}" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Email</label>
                                    <input type="email" value="{{ $user->email }}" class="form-control" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Nomor Handphone</label>
                                    <input type="text" name="mobile" value="{{ $user->mobile ?? '' }}" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Kode Pos</label>
                                    <input type="text" name="pincode" value="{{ $user->pincode ?? '' }}" class="form-control">
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Alamat</label>
                                    <textarea name="address" class="form-control" rows="2">{{ $user->address ?? '' }}</textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Foto Profil</label>
                                    <input type="file" name="image" class="form-control">
                                    @if(!empty($user->image))
                                        <small class="d-block mt-2">
                                            <a href="{{ $imagePath }}" target="_blank" class="text-decoration-none text-success">
                                                <i class="fa fa-eye me-1"></i> Lihat Foto Saat Ini
                                            </a>
                                        </small>
                                        <input type="hidden" name="current_image" value="{{ $user->image }}">
                                    @endif
                                </div>

                                <div class="col-12 text-end mt-3">
                                    <button type="submit" class="btn btn-success px-4">
                                        <i class="fa fa-save me-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Footer --}}
                    <div class="card-footer bg-white text-muted small text-center py-2 border-top">
                        <i class="fa fa-clock me-1"></i>
                        Terakhir diperbarui: {{ $user->updated_at ? $user->updated_at->format('d M Y, H:i') : 'Belum pernah' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
