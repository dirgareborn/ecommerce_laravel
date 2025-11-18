@foreach($orders as $order)
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Invoice #{{ $order->invoice_number ?? 'INV-' . $order['id'] }}</title>

  <style>
    * { font-family: DejaVu Sans, Verdana, Arial, sans-serif; box-sizing: border-box; }

    body {
      font-size: 12px;
      color: #333;
      line-height: 1.5;
      padding: 35px 45px 100px 45px;
      position: relative;
    }

    /* ======== HEADER ======== */
    .header {
      margin-top:-60px;
      display: flex;
      align-items: center;
      justify-content: flex-start;
      margin-bottom: 10px;
      padding-bottom: 10px;
    }
    .garis{
      color; #aaa; /* Garis pemisah tipis */
    }
    .header-left { width: 150px; text-align: left; }
    .header-left img { width: 100px;
      float:left;
      bottom:20; }

      .header-right {
        flex: 1;
        text-align: center;
        margin-left: -55px;
      }

      .header-right h3 { font-size: 13px; margin: 0; color: #222; }
      .header-right h4 { font-size: 15px; margin: 0; color: #333; }
      .header-right p { font-size: 11px; color: #555; margin: 2px 0 0; }

      /* ======== INFO SECTION ======== */
      .info-section { 
        display: flex; 
        justify-content: flex-start; 
        margin-top: 15px; 
        margin-bottom: 20px; }
        .customer-info { 
          width: 40%; 
          text-align: left; 
          font-size: 12px;
          float: left; 
          display: flex;

        }
        .invoice-info { 
          width: 70%; 
          text-align: right; 
          font-size: 12px; 
          line-height: 1.4; 
          margin-left: auto; /* dorong ke kanan */
          float: right;      /* menempel ke kanan */
          right: 0;  /* pastikan menempel ke margin kanan */
        }
        .info-section::after {
          content: "";
          display: table;
          clear: both;
        }
        /* ======== TABLE ======== */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; vertical-align: top; }
        thead { background: #f2f2f2; }
        th { font-weight: bold; text-align: left; }
        tfoot td { font-weight: bold; background: #fafafa; }
        .text-right { text-align: right; }
        .gray { background: #e9ecef; }

        /* ======== BANK INFO ======== */
        .bank-info { margin-top: 15px; }
        .bank-item { margin-bottom: 10px; }

        /* ======== CATATAN ======== */
        .notes { font-size: 10.5px; color: #555; margin-top: 20px; border-top: 1px dashed #ccc; padding-top: 10px; }

        /* ======== WATERMARK ======== */
        body::after {
          content: "BPB UNM";
          position: fixed;
          top: 40%;
          left: 25%;
          font-size: 90px;
          color: rgba(180, 180, 180, 0.12);
          transform: rotate(-30deg);
          white-space: nowrap;
          z-index: -1;
        }

        /* ======== WATERMARK UNPAID VERTICAL ======== */
        .watermark-unpaid {
          position: fixed;
          top: -60px;
          right: -140px;
          font-size: 28px; /* lebih kecil */
          font-weight: bold;
          color: #fff;
          background-color: rgba(255, 0, 0, 0.85);
          border: 1.5px dashed whitesmoke; /* border tipis */
          padding: 5px 60px; /* padding lebih kecil */
          transform: rotate(45deg);
          pointer-events: none;
          z-index: 0;
          margin: 0;
          text-align: center;
          box-sizing: border-box;
        }

        /* ======== FOOTER ======== */
        .footer {
          /*background: rgba(200,200,200,0.1); */
          padding: 15px;
          text-align: center;
          color: #444;
          position: fixed;
          bottom: 0;
          left: 0;
          right: 0;
          height: 80px;
          border-bottom: 2px solid orange;
        }

        .footer p {
          margin: 3px 0;
          font-size: 11px;
          line-height: 1.4;
        }

      </style>
    </head>
    <body>

      {{-- ======== WATERMARK UNPAID ======== --}}
      <div class="watermark-unpaid">UNPAID</div>

      {{-- ================= HEADER ================= --}}
      <div class="header">
        <div class="header-left">
          <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('logo_100x100.webp'))) }}" alt="Logo">
        </div>
        <div class="header-right">
          <h3>Badan Pengembangan Bisnis</h3>
          <h4>Universitas Negeri Makassar</h4>
          <p>Jl. A.P. Pettarani, Makassar, Sulawesi Selatan, Indonesia 90222</p>
        </div>
      </div>
      <hr class="garis">
      {{-- ================= CUSTOMER & INVOICE INFO ================= --}}
      <div class="info-section">
        <div class="customer-info">
          <strong>Kepada:</strong><br>
          Bapak {{ $order['users']['name'] }}<br>
          <small> E-mail    : </small><small>{{ $order['users']['email'] ?? '' }}</small><br>
          <small> No. Telp : </small><small>{{ $order['users']['phone'] ?? '' }}</small>
        </div>

        <div class="invoice-info">
          <strong>INVOICE</strong><br>
          ID: #{{ $order['invoice_number'] ?? '036-14' . $order['id'] }}<br>
          Tanggal: {{ format_date($order['created_at']) }}<br>
          Status: <strong>{{ ucfirst($order['order_status']) }}</strong>
        </div>
      </div>

      {{-- ================= TABLE ITEM ================= --}}
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Deskripsi</th>
            <th>Tanggal Pemakaian</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          @php
          $subtotal = 0;
          $totalDiskon = 0;
          $grandTotal = 0;
          $couponAmount = $order['coupon_amount'] ?? Session::get('couponAmount', 0);
          @endphp

          @foreach($order['orders_products'] as $key => $product)
          @php
          $productModel = App\Models\Product::find($product['product_id']);
          $priceInfo = App\Services\ProductPriceService::getPrice($productModel, $order['users']['customer_type'] ?? 'umum');
          $lineTotal = $priceInfo['final_price'] * $product['qty'];
          $subtotal += $priceInfo['product_price'] * $product['qty'];
          $totalDiskon += $priceInfo['discount'] * $product['qty'];
          $grandTotal += $lineTotal;
          @endphp
          <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $product['product_name'] }}</td>
            <td>{{ $product['start_date'] }} - {{ $product['end_date'] }}</td>
            <td>@currency($priceInfo['product_price'])</td>
            <td>{{ $product['qty'] }}</td>
            <td>@currency($lineTotal)</td>
          </tr>
          @endforeach
        </tbody>

        <tfoot>
          <tr>
            <td colspan="5" class="text-right">Subtotal</td>
            <td>@currency($subtotal)</td>
          </tr>
          <tr>
            <td colspan="5" class="text-right">Subsidi (Diskon Produk)</td>
            <td>@currency($totalDiskon)</td>
          </tr>
          @if($couponAmount > 0)
          <tr>
            <td colspan="5" class="text-right">Diskon Kupon</td>
            <td>-@currency($couponAmount)</td>
          </tr>
          @endif
          <tr class="gray">
            <td colspan="5" class="text-right">Grand Total</td>
            <td>@currency(max(0, $grandTotal - $couponAmount))</td>
          </tr>
        </tfoot>
      </table>

      {{-- ================= BANK INFO ================= --}}
      <div class="bank-info">
        <small>Silakan selesaikan pembayaran ke salah satu rekening berikut:</small><br><br>
        @foreach($banks as $bank)
        @php
        $path = public_path('front/images/banks/' . $bank['bank_icon']);
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        @endphp
        <div class="bank-item">
          <img src="{{ $base64 }}" width="35">
          <span> a.n <strong>{{ $bank['account_name'] }}</strong></span><br>
          <small>No. Rekening: {{ $bank['account_number'] }}</small>
        </div>
        @endforeach
      </div>

      {{-- ================= CATATAN PENTING ================= --}}
      <div class="notes">
        <strong>Catatan Penting:</strong><br>
        - Simpan bukti pembayaran Anda sebagai arsip resmi.<br>
        - Transaksi ini tunduk pada ketentuan hukum dan peraturan Universitas Negeri Makassar.<br>
        - Segala bentuk kecurangan, pemalsuan data, atau penyalahgunaan akan ditindak sesuai hukum yang berlaku.<br>
        - Pembayaran dianggap sah apabila telah diverifikasi oleh pihak Badan Pengembangan Bisnis UNM.<br>
        - Mohon tidak membagikan data invoice kepada pihak yang tidak berkepentingan.
      </div>

      {{-- ================= FOOTER ================= --}}
      <div class="footer">
        <p>
          Terima kasih atas kepercayaan Anda / <em>Thank you for your trust.</em> 🙏<br>
          Dokumen ini dicetak secara otomatis dan sah tanpa tanda tangan.<br>
          Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </p>
      </div>

    </body>
    </html>
    @endforeach
