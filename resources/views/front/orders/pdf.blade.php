<!doctype html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Invoice #{{ $order->invoice_number }}</title>
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

<div class="watermark-unpaid">UNPAID</div>

<div class="header">
    <div class="header-left">
        <img src="{{ $logoBase64 }}" alt="Logo">
    </div>
    <div class="header-right">
        <h3>Badan Pengembangan Bisnis</h3>
        <h4>Universitas Negeri Makassar</h4>
        <p>Jl. A.P. Pettarani, Makassar, Sulawesi Selatan, Indonesia 90222</p>
    </div>
</div>

<hr>

<div class="info-section">
    <div class="customer-info">
        <strong>Kepada:</strong><br>
        {{ $order->users->name ?? '-' }}<br>
        <small>Email: {{ $order->users->email ?? '-' }}</small><br>
        <small>Telp: {{ $order->users->phone ?? '-' }}</small>
    </div>
    <div class="invoice-info">
        <strong>INVOICE</strong><br>
        ID: #{{ $order->invoice_number }}<br>
        Tanggal: {{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d F Y') }}<br>
        Status: <strong>{{ ucfirst($order->order_status) }}</strong>
    </div>
</div>

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
        @foreach($products as $key => $p)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $p['name'] }}</td>
            <td>{{ $p['start_date'] }} - {{ $p['end_date'] }}</td>
            <td>@currency($p['price'])</td>
            <td>{{ $p['qty'] }}</td>
            <td>@currency($p['line_total'])</td>
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
            <td>@currency($totalDiscount)</td>
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

<div class="bank-info">
    <small>Silakan selesaikan pembayaran ke salah satu rekening berikut:</small><br>
    @foreach($banks as $bank)
        <div class="bank-item">
            <img src="{{ $bank['icon_base64'] }}" width="35">
            <span> a.n <strong>{{ $bank['account_name'] }}</strong></span><br>
            <small>No. Rekening: {{ $bank['account_number'] }}</small>
        </div>
    @endforeach
</div>

<div class="notes">
    <strong>Catatan Penting:</strong><br>
    - Simpan bukti pembayaran sebagai arsip resmi.<br>
    - Transaksi tunduk ketentuan hukum UNM.<br>
    - Segala penyalahgunaan akan ditindak sesuai hukum.<br>
    - Pembayaran sah jika diverifikasi BPB UNM.<br>
    - Mohon jangan membagikan invoice ke pihak lain.
</div>

<div class="footer">
    Terima kasih atas kepercayaan Anda 🙏<br>
    Dokumen ini dicetak otomatis dan sah tanpa tanda tangan.<br>
    Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
</div>

</body>
</html>
