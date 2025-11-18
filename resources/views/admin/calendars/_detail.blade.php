<div>
    <p><b>Order ID:</b> {{ $order->id }}</p>
    <p><b>Pemesan:</b> {{ $order->users->name ?? '-' }}</p>
    <p><b>Status Order:</b> {{ ucfirst($order->order_status) }}</p>
    <p><b>Payment Status:</b> {{ ucfirst($order->payment_status) }}</p>
    <p><b>Diverifikasi Oleh:</b> {{ $order->verifier->name ?? '-' }}</p>

    <h6>Produk:</h6>
    <ul>
        @foreach($order->orders_products as $item)
            <li>{{ $item->product->product_name }} (Qty: {{ $item->qty }})</li>
        @endforeach
    </ul>

    @if($order->payment_proof)
        <p><b>Bukti Pembayaran:</b></p>
        <img src="{{ Storage::url($order->payment_proof) }}" class="img-fluid" alt="Payment Proof">
    @endif

    <p><b>Notes:</b> {{ $order->notes ?? '-' }}</p>
</div>
