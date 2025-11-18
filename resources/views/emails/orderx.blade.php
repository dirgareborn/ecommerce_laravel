<?php
use App\Models\Product; 

?>
@php $total_price = 0 ;
use App\Services\ProductPriceService;
@endphp
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>INVOICE ID : #036-14{{ $orderDetails['id'] }}</title>

<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
    }
    table{
        font-size: x-small;
    }
    tfoot tr td{
        font-weight: bold;
        font-size: x-small;
    }
    .gray {
        background-color: lightgray;
    }
</style>

</head>
<body>

<table width="100%">
    <tr>
        <td align="center">
            <img src="./logo.webp" alt="" width="100"/>
            <h3>Badan Pengembangan Bisnis <br> Universitas Negeri Makassar</h3>
        </td>
    </tr>
</table>

<table width="100%">
    <tr>
        <td align="left">
            <pre>
Kepada     : {{ $orderDetails['users']['name'] }}
Telepon    : {{ $orderDetails['users']['mobile'] ?? '-' }}
Alamat     : {{ $orderDetails['users']['address'] ?? '-' }}
Kode Pos   : {{ $orderDetails['users']['pincode'] ?? '-' }}
            </pre>
        </td>
        <td valign="top"></td>
        <td align="">
            <pre>
INVOICE
ID : #036-14{{ $orderDetails['id'] }}
Tanggal Pemesanan : {{ format_date($orderDetails['created_at']) }}
Status  :  {{ $orderDetails['order_status'] }}
            </pre>
        </td>
    </tr>
</table>

<br/>

<table width="100%">
    <thead style="background-color: lightgray;">
        <tr>
            <th>#</th>
            <th>Deskripsi</th>
            <th>Tanggal Penggunaan</th>
            <th>Harga</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orderDetails['orders_products'] as $key => $product)
            @php

                $priceInfo = ProductPriceService::getPrice($product['product_id'], $product['customer_type']);
            @endphp
            <tr>
                <th scope="row">{{ $key + 1 }}</th>
                <td>{{ $product['product_name'] }}</td>
                <td align="left">{{ format_date($product['start_date']) }} - {{ format_date($product['end_date']) }}</td>
                <td align="left">@currency($priceInfo['final_price'])</td>
                <td align="right">@currency($priceInfo['final_price'] * $product['qty'])</td>
            </tr>
            @php
                $total_price += $priceInfo['final_price'] * $product['qty'];
            @endphp
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <td colspan="3"></td>
            <td align="right">Subtotal </td>
            <td align="right">@currency($total_price)</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td align="right">Subsidi (Diskon) </td>
            <td align="right">
                <strong class="couponAmount">
                    @currency($orderDetails['coupon_amount'] ?? 0)
                </strong>
            </td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td align="right">Grand Total </td>
            <td align="right" class="gray">@currency($orderDetails['grand_total'] ?? $total_price)</td>
        </tr>
    </tfoot>
</table>

<table>
    <small>Silahkan menyelesaikan Pembayaran dengan akun pembayaran berikut</small><br><br>
    @foreach($banks as $bank)
        @php
            $path = "front/images/banks/" . $bank['bank_icon'];
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        @endphp
        <img src="{{ $base64 }}" width="35px">
        <small> a.n {{ $bank['account_name'] }} </small><br>
        <small>No. Rekening {{ $bank['account_number'] }} </small><br><br>
    @endforeach
</table>

</body>
</html>
