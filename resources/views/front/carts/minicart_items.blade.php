@php
use App\Models\Product;
use App\Models\Category;
$totalCartItems = isset($cartItems) ? $cartItems->count() : 0;
@endphp
    @if(isset($cartItems) && $cartItems->count() > 0)
        <ul class="dropdown-menu rounded-0 m-0" role="menu">
            @php $total_price = 0; @endphp
            @foreach($cartItems as $item)
                @php
                    // Pastikan relasi product tersedia
                    $product = $item->product ?? null;
                    if (!$product) continue;

                    $getAttributePrice = Product::getAttributePrice($item->product_id, $item->customer_type ?? 'umum');
                    $getCategoryUrl = Category::where('id', $product->category_id)->value('url');
                    $productUrl = $getCategoryUrl ? url('/' . $getCategoryUrl . '/' . $product->url) : '#';
                    $total_price += $getAttributePrice['final_price'] * $item->qty;
                @endphp

                <li class="dropdown-item d-flex justify-content-between align-items-start">
                    <div class="item-info">
                        <small>
                            <a href="{{ $productUrl }}">{{ $product->product_name }}</a>
                        </small><br>
                        <small>@currency($getAttributePrice['final_price'] * $item->qty)</small>
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
        </ul>
    @endif
</div>
