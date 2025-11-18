@extends('front.layouts.app')

@section('content')
<div class="container-xxl py-5">
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height:70vh;">
            <div class="col-lg-5 col-md-7">

                {{-- Card Register --}}
                <div class="card border-0 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card-body p-4 p-sm-5">
                        <h3 class="text-center mb-4 text-primary fw-bold">Silahkan Registrasi</h3>
                        <p class="text-center text-muted mb-4">Buat akun baru untuk mengakses layanan BPB.</p>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            {{-- Name --}}
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" placeholder="Nama Lengkap" required autofocus>
                                <label for="name">Nama Lengkap</label>
                                @error('name')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" placeholder="Email" required>
                                <label for="email">Email</label>
                                @error('email')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" placeholder="Password" required>
                                <label for="password">Password</label>
                                @error('password')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Password Confirmation --}}
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password_confirmation" 
                                       name="password_confirmation" placeholder="Konfirmasi Password" required>
                                <label for="password_confirmation">Konfirmasi Password</label>
                            </div>

                            {{-- Submit --}}
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg animated pulse">Register</button>
                            </div>

                            {{-- Links --}}
                            <div class="text-center">
                                <p class="small text-muted">
                                    Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none">Login di sini</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Animasi wow.js
    $(document).ready(function(){
        new WOW().init();
    });
</script>
@endpush
