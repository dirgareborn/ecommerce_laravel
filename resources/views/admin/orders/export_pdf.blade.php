<!DOCTYPE html>
<html>
<head>
    <title>Daftar Order</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h3>Daftar Order</h3>
    <table>
        <thead>
            <tr>
                <th>ID Order</th>
                <th>Tanggal Order</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Kategori Pengguna</th>
                <th>Produk Order</th>
                <th>Harga</th>
                <th>Metode Pembayaran</th>
                <th>Status Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ format_date($order->created_at) }}</td>
                <td>{{ $order->users->name }}</td>
                <td>{{ $order->users->email }}</td>
                <td>{{ $order->users->customer_type }}</td>
                <td>
                    @foreach($order->orders_products as $product)
                        {{ $product->product_name }}<br>
                        <small>{{ format_date($product->start_date) }} - {{ format_date($product->end_date) }}</small>
                        <br>
                    @endforeach
                </td>
                <td>{{ number_format($order->grand_total, 0, ',', '.') }}</td>
                <td>{{ $order->payment_method }}</td>
                <td>{{ $order->payment_status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
