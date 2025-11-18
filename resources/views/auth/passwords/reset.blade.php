@extends('front.layouts.app')

@section('content')
<div class="container-fluid header bg-primary py-5 mb-5">
    <div class="container text-center text-white wow fadeIn" data-wow-delay="0.1s">
        <h1 class="display-5 fw-bold mb-2">Reset Password</h1>
        <p class="mb-0">Masukkan email dan password baru untuk akun Anda</p>
    </div>
</div>

<div class="container-xxl py-5">
    <div class="container">
        {{-- Breadcrumb --}}
        <div class="row mb-4">
            <div class="col-md-12">
                <nav aria-label="breadcrumb" class="animated fadeIn">
                    <ol class="breadcrumb text-uppercase">
                        @for($i = 1; $i <= count(Request::segments()); $i++)
                            <li class="breadcrumb-item">
                                <a href="{{ URL::to(implode('/', array_slice(Request::segments(), 0, $i, true))) }}">
                                    {{ strtoupper(Request::segment($i)) }}
                                </a>
                            </li>
                        @endfor
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Reset Password Card --}}
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card shadow-lg rounded-3 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="card-body p-4 p-sm-5">
                        <h3 class="text-primary fw-bold mb-4 text-center">Reset Password</h3>

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div class="mb-4">
                                <label for="password-confirm" class="form-label">Confirm Password</label>
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password">
                            </div>

                            {{-- Submit --}}
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Reset Password
                                </button>
                            </div>
                        </form>

                        <p class="text-center text-muted mt-4 small">&copy; {{ date('Y') }} BPB UNM</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
