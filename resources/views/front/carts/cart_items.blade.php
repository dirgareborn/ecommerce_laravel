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
         use App\Services\ProductPriceService;
         $total_normal   = 0; 
         $total_discount = 0; 
         $grand_total    = 0; 


         @endphp

         @forelse($cartItems as $item)
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
         <div class="card-body">
            <div class="row d-flex justify-content-between align-items-center">
                <div class="col-md-2">
                    <img src="{{ is_product($item->product->product_image) }}" width="300" class="img-fluid rounded-3">
                </div>
                <div class="col-md-3">
                    <p class="lead fw-normal mb-2">{{ $item->product->product_name }}</p>
                    <small class="text-muted">
                        Kategori: {{ $item->product->category->category_name }}
                    </small>
                </div>
                <div class="col-md-3">
                    <p>Harga Satuan: @currency($priceInfo['product_price'])</p>
                    <p>Diskon: -@currency($priceInfo['discount'])</p>
                    <p><strong>Total Item: @currency($finalTotal)</strong></p>
                </div>
                <div class="col-md-2 text-end">
                    <a href="javascript:void(0)" class="btn btn-danger confirmDelete" record="cart-item" recordid="{{ $item->id }}">
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
<!-- <div class="col-md-6">
    <div class="card rounded-0">
        <div class="card-body p-4 d-flex flex-row">
          @if(count($cartItems))
          @php
          $couponAmount = Session::get('couponAmount', 0);
          $grandTotal   = Session::get('grandTotal', $total_normal);
          @endphp
          <div class="form-outline flex-fill">
            <h6 class="form-label">
                Total Harga : <strong>@currency($total_normal)</strong>
            </h6>

            <div>
                Total Diskon : 
                <strong class="couponAmount">- @currency($total_discount)</strong>
            </div>

            <div>
                Grand Total: 
                <strong class="grandTotal">@currency($grand_total)</strong>
            </div>
        </div>
        @endif
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
</div> -->
<!-- Summary -->
<div class="col-md-6">
    <div class="card rounded-0">
        <div id="cartSummary"> {{-- 🔥 Tambahkan ID ini --}}
            <div class="card-body p-4 d-flex flex-row">
                @php
                    $couponAmount = Session::get('couponAmount', 0);
                    $grandTotal   = Session::get('grandTotal', $total_normal);
                @endphp

                <div class="form-outline flex-fill">
                    <h6 class="form-label">Total Harga Normal: @currency($total_normal)</h6>

                    <div>
                        Diskon Produk: <strong>@currency($total_discount ?? 0)</strong>
                    </div>

                    <div>
                        Subsidi (Kupon): 
                        <strong class="couponAmount">@currency($couponAmount)</strong>
                    </div>

                    <div>
                        <strong>Grand Total:</strong> 
                        <span class="grandTotal">@currency($grandTotal)</span>
                    </div>
                </div>

                @auth
                    <form name="checkout" id="checkout" action="{{ url('checkout') }}" method="get">
                        <button type="submit" class="btn btn-warning btn-block float-end p-2">Checkout</button>
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
</div>