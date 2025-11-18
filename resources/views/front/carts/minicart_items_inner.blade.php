@php
use App\Models\Product;
use App\Models\Category;
use App\Services\ProductPriceService;
@endphp

@if(isset($cartItems) && $cartItems->count() > 0)

    @foreach($cartItems as $item)
        @php
            $product = $item->product ?? null;
            if (!$product) continue;
            $priceInfo = ProductPriceService::getPrice($product, $item->customer_type ?? null);
            $getCategoryUrl = Category::where('id', $product->category_id)->value('url');
            $productUrl = $getCategoryUrl ? url('/' . $getCategoryUrl . '/' . $product->url) : '#';
        @endphp
        <li class="dropdown-item d-flex justify-content-between align-items-start">
            <div class="item-info">
                <small><a href="{{ $productUrl }}">{{ $product->product_name }}</a></small><br>
                <small>@currency($priceInfo['final_price'] * $item->qty)</small>
            </div>
            <div>
                <a href="javascript:void(0)" 
                   class="deleteCartItem text-danger" 
                   data-cartid="{{ $item->id }}" 
                   title="Hapus Produk">
                    <i class="fas fa-trash"></i>
                </a>
            </div>
        </li>

        @if(!$loop->last)
            <li class="divider"></li>
        @endif
    @endforeach
    <li class="divider"></li>
    <li class="dropdown-item text-center">
        <a href="{{ url('cart') }}">Lihat Keranjang</a>
    </li>
@else
    <li class="dropdown-item text-center text-muted">Keranjang kosong</li>

@endif