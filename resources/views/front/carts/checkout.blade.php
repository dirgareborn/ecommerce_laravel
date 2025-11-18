@extends('front.layouts.app')
@section('content')
@php
use App\Services\ProductPriceService;
$total_normal   = 0; 
$total_discount = 0; 
$grand_total    = 0; 
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
                    @if($cartItems->isNotEmpty())
                    <ul class="list-group mb-3">
                        @foreach($cartItems as $item)
                        @php
                        $priceInfo = ProductPriceService::getPrice($item->product_id, $item->customer_type ?? null);
                        $qty = $item->qty;

                        // harga per item dikalikan qty
                        $normalTotal   = $priceInfo['product_price'] * $qty;
                        $discountTotal = $priceInfo['discount'] * $qty;
                        $finalTotal    = $priceInfo['final_price'] * $qty;

                        // total keseluruhan
                        $total_normal   += $normalTotal;
                        $total_discount += $discountTotal;
                        $grand_total    += $finalTotal;
                        @endphp

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                {{ $item->product->product_name }}
                                <br>
                                <small>{{ $item->qty }} x @currency($priceInfo['product_price']) = @currency($normalTotal)</small>
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
                           @php
                           $couponAmount = Session::get('couponAmount', 0);
                           $grandTotal   = Session::get('grandTotal', $total_normal);
                           @endphp
                           <span>Total:</span>
                           <span>@currency($total_normal)</span>
                       </div>

                       <div class="d-flex justify-content-between">
                        <span>Total Diskon Produk : </span>
                        <span>@currency($total_discount) </span>
                        </div>
                        <div class="d-flex justify-content-between">
                        <span>Subsidi (Kupon) : </span>
                        <span>@currency($couponAmount) </span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between fw-bold">
                        <span>Grand Total:</span>
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