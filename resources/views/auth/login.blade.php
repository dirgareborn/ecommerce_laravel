@extends('front.layouts.app')

@section('content')
<div class="container-xxl py-5">
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height:70vh;">
            <div class="col-lg-5 col-md-7">

                {{-- Card Login --}}
                <div class="card border-0 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card-body p-4 p-sm-5">
                        <h3 class="text-center mb-4 text-primary fw-bold">Silahkan Login </h3>
                        <p class="text-center text-muted mb-4">Masukkan akun Anda untuk melanjutkan.</p>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            {{-- Email --}}
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" placeholder="Email" required autofocus>
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

                            {{-- Remember --}}
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>

                            {{-- Submit --}}
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg animated pulse">Login</button>
                            </div>

                            {{-- Links --}}
                            <div class="text-center">
                                <a href="{{ route('password.request') }}" class="text-decoration-none">Lupa Password?</a>
                            </div>
                              {{-- Links --}}
                            <div class="text-center">
                                <p class="small text-muted">
                                    Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none">Daftar Disini</a>
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
    // Tambahkan animasi saat login muncul
    $(document).ready(function(){
        new WOW().init();
    });
</script>
@endpush
