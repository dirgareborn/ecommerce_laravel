@extends('front.layouts.app')

@section('content')


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

        {{-- Password Reset Request Card --}}
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card shadow-lg rounded-3 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="card-body p-4 p-sm-5">
                        <h3 class="text-primary fw-bold mb-4 text-center">{{ __('Send Reset Link') }}</h3>

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            {{-- Submit --}}
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    {{ __('Send Password Reset Link') }}
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
