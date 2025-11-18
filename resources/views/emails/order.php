@component('mail::message')
# Invoice Pesanan #{{ $order->id }}

Halo **{{ $order->users->name }}**,  
Terima kasih telah melakukan pemesanan di **Mallbisnisunm.com**.

@component('mail::table')
| Produk | Qty | Harga Satuan | Total |
|:--|:--:|--:|--:|
@foreach($order->orders_products as $item)
| {{ $item->product_name }} | {{ $item->qty }} | Rp{{ number_format($item->product_price,0,',','.') }} | Rp{{ number_format($item->product_price * $item->qty,0,',','.') }} |
@endforeach
@endcomponent

**Total Pembayaran:** Rp{{ number_format($order->grand_total,0,',','.') }}

Silakan lakukan pembayaran ke salah satu rekening berikut:

@foreach($banks as $bank)
- **{{ $bank->bank_name }}** a/n {{ $bank->account_name }} ({{ $bank->account_number }})
@endforeach

Terima kasih,  
**Mallbisnisunm.com**
@endcomponent
