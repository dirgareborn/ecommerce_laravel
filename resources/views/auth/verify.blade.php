@extends('front.layouts.app')

@section('content')
<div class="container-xxl py-5">
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height:60vh;">
            <div class="col-lg-6 col-md-8">

                {{-- Card Verifikasi --}}
                <div class="card shadow wow fadeInUp" data-wow-delay="0.1s">
                    <div class="card-body p-4 p-sm-5 text-center">
                        <h3 class="text-primary fw-bold mb-3">{{ __('Verify Your Email Address') }}</h3>

                        @if (session('resent'))
                            <div class="alert alert-success text-start" role="alert">
                                {{ __('A fresh verification link has been sent to your email address.') }}
                            </div>
                        @endif

                        <p class="mb-3 text-muted">
                            {{ __('Before proceeding, please check your email for a verification link.') }}
                        </p>
                        <p class="mb-4 text-muted">
                            {{ __('If you did not receive the email') }}, 
                            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <button type="submit" class="btn btn-link p-0 m-0 align-baseline text-decoration-none fw-bold text-primary">
                                    {{ __('click here to request another') }}
                                </button>.
                            </form>
                        </p>

                        <a href="{{ url('/') }}" class="btn btn-primary btn-lg animated pulse">Kembali ke Beranda</a>
                    </div>
                </div>

                {{-- Footer --}}
                <p class="text-center text-muted mt-3 small">&copy; {{ date('Y') }} BPB UNM</p>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        new WOW().init();
    });
</script>
@endpush
