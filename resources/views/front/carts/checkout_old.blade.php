@extends('front.layouts.app')
@section('content')
@php
use App\Services\ProductPriceService;
@endphp
@push('style')
<link rel="stylesheet" href="{{ url('admin/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endpush
@section('content')
<div class="container">
    <main>
        <div class="container-fluid py-5 text-center">
            <p class="lead">
            Sebelum Bapak/Ibu Melakukan pembayaran, Pastikan Jadwal Penggunaan  sudah benar! </p>
        </div>

        <div class="row g-5">
            @include('admin.partials.alert') 
            <div class="col-md-12 col-lg-12 order-md-last">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-primary">Keranjang Anda</span>
                    <span class="badge bg-primary rounded-pill"> {{ $totalCartItems ?? ''}} </span>
                </h4>
                <div class="checkout-cart">
                    @if($getCartItems->isNotEmpty())
                    <ul class="list-group mb-3">
                        @php $totalPrice = 0; @endphp
                        @foreach($getCartItems as $item)
                        @php
                        $product = $item->product;
                        if (!$product) continue;

                        $priceInfo = ProductPriceService::getPrice($product, $item->customer_type ?? 'umum');
                        $subtotal = $priceInfo['final_price'] * $item->qty;
                        $totalPrice += $subtotal;
                        @endphp

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ url($product->category->url.'/'.$product->url) }}">
                                    {{ $product->product_name }}
                                </a>
                                <br>
                                <small>{{ $item->qty }} x @currency($priceInfo['final_price']) = @currency($subtotal)</small>
                            </div>
                            <div>
                                <a href="javascript:void(0)" class="text-danger deleteCartItem" data-cartid="{{ $item->id }}" title="Hapus Produk">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </li>
                        @endforeach
                    </ul>

                    <div class="checkout-summary mt-3">
                        <div class="d-flex justify-content-between">
                            <span>Total:</span>
                            <span>@currency($totalPrice)</span>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span>Subsidi / Diskon:</span>
                            @php
                            $couponAmount = session('couponAmount', 0);
                            @endphp
                            <span>@currency($couponAmount)</span>
                        </div>

                        <div class="d-flex justify-content-between fw-bold">
                            <span>Grand Total:</span>
                            @php
                            $grandTotal = $totalPrice - $couponAmount;
                            @endphp
                            <span>@currency($grandTotal)</span>
                        </div>
                    </div>

                    <!-- Payment Options -->
                    <div class="checkout-payment mt-4">
                        <form method="POST" action="{{ route('checkout') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran:</label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="">Pilih metode pembayaran</option>
                                    <option value="cash">Cash</option>
                                    <option value="transfer">Transfer</option>
                                </select>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="agree" id="agree" required>
                                <label class="form-check-label" for="agree">
                                    Saya setuju dengan syarat dan ketentuan
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                Checkout
                            </button>
                        </form>
                    </div>

                    @else
                    <p class="text-center text-muted">Keranjang kosong</p>
                    @endif
                </div>
            </div>
        </main>

    </div>
    @endsection
    @push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.12.2/sweetalert2.min.js" integrity="sha512-JWPRTDebuCWNZTZP+EGSgPnO1zH4iie+/gEhIsuotQ2PCNxNiMfNLl97zPNjDVuLi9UWOj82DEtZFJnuOdiwZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush