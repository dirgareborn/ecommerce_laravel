<?php use App\Models\Product; ?>
@extends('front.layouts.app')
@push('style')
<link rel="stylesheet" href="{{ url('admin/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endpush
@section('content')
<!-- Property List Start -->
<!-- Header Start -->
<div class="container-fluid bg-white p-0">
    <div class="row g-0 align-items-center flex-column-reverse flex-md-row" style="min-height: 50px;">
  
    </div>
</div>
<!-- Header End -->
<div class="container-xxl py-5">
    <div class="container" id="appendCartItems">
		@include('admin.partials.alert')
        @include('front.carts.cart_items')
    </div>
</div>
<!-- Property List End -->
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.12.2/sweetalert2.min.js" integrity="sha512-JWPRTDebuCWNZTZP+EGSgPnO1zH4iie+/gEhIsuotQ2PCNxNiMfNLl97zPNjDVuLi9UWOj82DEtZFJnuOdiwZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush