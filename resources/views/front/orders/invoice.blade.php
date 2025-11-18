<?php
use App\Services\ProductPriceService;
use App\Models\Product;
?>

@push('style')
<style>
  /* ============================
     🧾 INVOICE TABLE STYLES
  ============================ */
  .invoice-table th,
  .invoice-table td {
    font-size: 0.95rem;
    white-space: nowrap;
    vertical-align: middle;
  }

  /* Tablet & Mobile (≤768px) */
  @media (max-width: 768px) {
    .invoice-table th,
    .invoice-table td {
      font-size: 0.8rem;
      padding: 0.4rem;
    }

    /* Sembunyikan kolom non-esensial */
    .invoice-table .hide-mobile {
      display: none !important;
    }

    /* Tanggal bisa wrap agar tidak kepanjangan */
    .invoice-table th:nth-child(3),
    .invoice-table td:nth-child(3) {
      white-space: normal;
    }
  }

  /* Mobile kecil (≤480px) */
  @media (max-width: 480px) {
    .invoice-table th,
    .invoice-table td {
      font-size: 0.7rem;
      padding: 0.25rem;
    }
  }

  /* ============================
     💳 INVOICE FOOTER & BUTTON
  ============================ */
  .invoice-footer {
    margin-top: 1.5rem;
    align-items: center;
  }

  /* Tombol Bayar */
  #pay-button {
    border: none;
    font-size: 1rem;
    font-weight: 600;
    color: #fff;
    background-color: #60bdf3;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.5rem 1.25rem;
    border-radius: 8px;
    transition: background-color 0.3s ease, transform 0.2s ease;
  }

  #pay-button:hover {
    background-color: #4bb4ec;
    transform: translateY(-1px);
  }

  #pay-button i {
    font-size: 1.1rem;
  }

  /* Responsif Footer */
  @media (max-width: 768px) {
    .invoice-footer {
      flex-direction: column;
      text-align: center !important;
      gap: 0.75rem;
    }

    .invoice-footer #pay-button {
      width: 100%;
    }
  }
</style>

@endpush

@foreach($orders as $order)
@php
$subtotal = 0;
$totalDiscount = 0;
$couponAmount = $order['coupon_amount'] ?? 0; // dari tabel orders
$grandTotal = 0;
@endphp

<div class="card mb-4 shadow-sm border-0">
  <div class="card-body">
    <div class="container mb-5 mt-3">

      {{-- Header --}}
      <div class="row d-flex align-items-baseline">
        <div class="col-xl-9">
          <p style="color:#7e8d9f;font-size:20px;">
            Invoice >> <strong>ID: #{{ $order['invoice_number'] }}</strong>
          </p>
        </div>
        <div class="col-xl-3 text-end">
          <a class="btn btn-light" target="_blank" href="{{ url('invoice', $order['id']) }}">
            <i class="far fa-file-pdf text-danger"></i> Export PDF
          </a>
        </div>
        <hr>
      </div>

      {{-- Informasi --}}
      <div class="row mb-3">
        <div class="col-xl-8">
          <ul class="list-unstyled">
            <li class="text-muted">
              To: <span style="color:#5d9fc5;">{{ $order['users']['name'] }}</span>
            </li>
            <li class="text-muted">{{ $order['users']['email'] ?? '' }}</li>
          </ul>
        </div>
        <div class="col-xl-4">
          <p class="text-muted fw-bold mb-1">Invoice Details</p>
          <ul class="list-unstyled">
            <li><span class="fw-bold">ID:</span> #{{ $order['invoice_number'] }}</li>
            <li><span class="fw-bold">Tanggal:</span> {{ format_date($order['created_at']) }}</li>
            <li><span class="fw-bold">Status:</span> 
              <span class="badge bg-warning text-black fw-bold">{{ $order['order_status'] }}</span>
            </li>
          </ul>
        </div>
      </div>

      <div class="row justify-content-center">
        <div class="table-responsive">
          <table class="table table-striped table-borderless align-middle invoice-table">
            <thead style="background-color:#84B0CA;" class="text-white">
              <tr>
                <th>#</th>
                <th>Deskripsi</th>
                <th>Tanggal Pemakaian</th>
                <th class="hide-mobile">Harga Normal</th>
                <th class="hide-mobile">Diskon</th>
                <th class="hide-mobile">Harga Akhir</th>
                <th>Qty</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              @php
              $subtotal = 0;
              $totalDiscount = 0;
              $grandTotal = 0;
              @endphp

              @foreach($order['orders_products'] as $key => $product)
              @php
              $productModel = App\Models\Product::find($product['product_id']);
              $priceInfo = App\Services\ProductPriceService::getPrice($productModel, $order['users']['customer_type'] ?? 'umum');

              $normal = $priceInfo['product_price'] * $product['qty'];
              $discount = $priceInfo['discount'] * $product['qty'];
              $final = $priceInfo['final_price'] * $product['qty'];

              $subtotal += $normal;
              $totalDiscount += $discount;
              $grandTotal += $final;
              @endphp
              <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $product['product_name'] }}</td>
                <td>{{ $product['start_date'] }} - {{ $product['end_date'] }}</td>
                <td class="hide-mobile">@currency($priceInfo['product_price'])</td>
                <td class="hide-mobile">@currency($priceInfo['discount'])</td>
                <td class="hide-mobile">@currency($priceInfo['final_price'])</td>
                <td>{{ $product['qty'] }}</td>
                <td>@currency($final)</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      {{-- Total & Pembayaran --}}
      <div class="row">
        <div class="col-xl-8">
          <p class="ms-3">Silakan lakukan pembayaran ke rekening berikut:</p>
          @foreach($banks as $bank)
          <div class="mb-2">
            <img src="{{ asset('front/images/banks/'.$bank['bank_icon']) }}" width="45" class="me-2">
            <div><small>a.n {{ $bank['account_name'] }}</small></div>
            <div><small>No. Rekening {{ $bank['account_number'] }}</small></div>
          </div>
          @endforeach
        </div>

        <div class="col-xl-4">
          <ul class="list-unstyled">
            <li class="d-flex justify-content-between text-muted">
              <span>Total Harga Normal:</span>
              <strong>@currency($subtotal)</strong>
            </li>
            <li class="d-flex justify-content-between text-muted">
              <span>Total Diskon Produk:</span>
              <strong class="text-danger">- @currency($totalDiscount)</strong>
            </li>
            @if($couponAmount > 0)
            <li class="d-flex justify-content-between text-muted">
              <span>Potongan Kupon:</span>
              <strong class="text-danger">- @currency($couponAmount)</strong>
            </li>
            @endif
            <li class="d-flex justify-content-between mt-2 border-top pt-2">
              <span class="fw-bold text-black">Grand Total:</span>
              <strong class="text-success fs-5">@currency(max(0, $grandTotal - $couponAmount))</strong>
            </li>
          </ul>
        </div>
      </div>

      <hr>

      <div class="row align-items-center invoice-footer">
        <div class="col-xl-10 col-lg-9 col-md-8 col-sm-12 mb-2 mb-sm-0 text-center text-sm-start">
          <p class="mb-0 fw-medium text-muted">
            Terima kasih atas kepercayaan Anda 🙏
          </p>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 text-center text-sm-end">
<button 
      type="button" 
      id="pay-button" 
      class="btn btn-info text-white btn-flat"
      style="background-color:#60bdf3;"
    >
      <i class="fas fa-credit-card"></i>
      <span>Bayar</span>
    </button>
      </div>
    </div>

  </div>
</div>
</div>
@endforeach
