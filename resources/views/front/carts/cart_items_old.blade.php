<div class="row d-flex justify-content-center align-items-center h-100">
    <div class="col-11">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-normal mb-0">Keranjang Belanja</h3>
            <div>
                <p class="mb-0">
                    <span class="text-muted">Sort by:</span>
                    <a href="#!" class="text-body">Harga <i class="fas fa-angle-down mt-1"></i></a>
                </p>
            </div>
        </div>
        <div class="card rounded-0 mb-2">
         @php 
         use App\Models\Product;
         use App\Services\ProductPriceService;
         $total_price = 0; 
         @endphp

         @forelse($cartItems as $item)
         @php
         $priceInfo = ProductPriceService::getPrice($item->product_id, $item->customer_type ?? null);
         $itemTotal = $priceInfo['final_price'] * $item->qty;
         $total_price += $itemTotal;
         @endphp
         <div class="card-body">
            <div class="row d-flex justify-content-between align-items-center">
                <div class="col-md-2 col-lg-2 col-xl-2">
                    <img 
                    src="{{ is_product($item->product->product_image) }}" 
                    width="300" 
                    class="img-fluid rounded-3" 
                    alt="{{ $item->product->product_name }}"
                    >
                </div>

                <div class="col-md-2 col-lg-2 col-xl-2">
                    <p class="lead fw-normal mb-2">{{ $item->product->product_name }}</p>
                    <small>
                        <span class="text-muted">
                            Kategori: {{ $item->product->category->category_name }}
                        </span>
                    </small>
                </div>

                <div class="col-md-2 col-lg-2 col-xl-2">
                    <p class="lead fw-normal">{{ format_date($item->start_date) }}</p>
                </div>

                <div class="col-md-2 col-lg-2 col-xl-2 offset-lg-2">
                    <p class="mb-0 price">
                        Harga: <span>@currency($itemTotal)</span>
                    </p>
                    @if($priceInfo['discount'] > 0)
                    <small>
                        Diskon {{ $priceInfo['discount_percent'] }}% <br> dari 
                        <del>@currency($priceInfo['product_price'] * $item->qty)</del>
                    </small>
                    @endif
                </div>

                <div class="col-md-2 col-lg-2 col-xl-2 text-center">
                    <a 
                    class="confirmDelete btn btn-danger" 
                    name="{{ $item->product->product_name }}" 
                    href="javascript:void(0)" 
                    record="cart-item" 
                    recordid="{{ $item->id }}" 
                    title="Hapus Produk"
                    >
                    Hapus
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="card-body text-center py-5">
        <p class="lead mb-0">Keranjang Anda kosong.</p>
    </div>
    @endforelse
</div>

<!-- Coupon & Total Section -->
<div class="row">
    <div class="col-md-6">
        <div class="alert alert-danger" style="display: none;"></div>
        <div class="alert alert-success" style="display: none;"></div>

        <form action="javascript:;" id="couponForm">
            <div class="input-group">
                <input 
                type="text" 
                class="couponCode form-control" 
                id="code" 
                name="coupon_code" 
                placeholder="Kode Subsidi / Diskon"
                >
                <button 
                type="submit" 
                id="ApplyCoupon" 
                class="btn btn-primary"
                @if(Auth::check()) data-user="1" @endif
                >
                Pakai
            </button>
        </div>
    </form>

    <br>
    <small class="text-muted">
        Jika ada kendala transaksi, silakan klik 
        <a href="https://api.whatsapp.com/send?phone=6285343704359" class="btn btn-xs btn-link">
            <strong>Link ini</strong>
        </a> 
        untuk terhubung dengan admin.
    </small>
</div>

<!-- Summary -->
<div class="col-md-6">
    <div class="card rounded-0">
        <div class="card-body p-4 d-flex flex-row">
            @php
            $couponAmount = Session::get('couponAmount', 0);
            $grandTotal   = max(0, $total_price - $couponAmount);
            @endphp

            <div class="form-outline flex-fill">
                <h6 class="form-label">
                    Total Harga Semua Pesanan: <strong>@currency($total_price)</strong>
                </h6>

                <div>
                    Subsidi (Diskon) {{ $priceInfo['discount_type'] ?? ''}} : 
                    <strong class="couponAmount">{{ $priceInfo['discount'] ?? ''}}</strong>
                </div>

                <div>
                    Grand Total: 
                    <strong class="grandTotal">@currency($grandTotal)</strong>
                </div>
            </div>

            @auth
            <form name="checkout" id="checkout" action="{{ url('checkout') }}" method="get">
                <button type="submit" class="btn btn-warning btn-block float-end p-2">
                    Checkout
                </button>
            </form>
            @else
            <small class="text-muted">
                Silakan 
                <a href="{{ route('login') }}" class="btn btn-xs btn-link">
                    <strong>Log in</strong>
                </a> 
                untuk melakukan Checkout.
            </small>
            @endauth
        </div>
    </div>
</div>

</div>
</div>
</div>