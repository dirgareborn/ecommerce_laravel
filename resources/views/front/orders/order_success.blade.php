<?php use App\Models\Product; ?>
@extends('front.layouts.app')
@push('style')
<link rel="stylesheet" href="{{ url('admin/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endpush
@section('content')
<!-- Property List Start -->
<div class="container-xxl py-5 ">
    <div class="container">
		@include('admin.partials.alert')
    @include('front.orders.invoice')
    </div>
</div>
<!-- Property List End -->
@endsection
@php $snap_token = session('snap_token'); @endphp
@push('scripts')

<script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            snap.pay('{{ $snap_token }}', {
                onSuccess: function(result){ console.log(result); },
                onPending: function(result){ console.log(result); },
                onError: function(result){ console.log(result); },
                onClose: function(){ alert('Anda menutup popup tanpa menyelesaikan pembayaran'); }
            });
        };
    </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.12.2/sweetalert2.min.js" integrity="sha512-JWPRTDebuCWNZTZP+EGSgPnO1zH4iie+/gEhIsuotQ2PCNxNiMfNLl97zPNjDVuLi9UWOj82DEtZFJnuOdiwZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush