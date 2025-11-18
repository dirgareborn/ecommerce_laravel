@extends('admin.layout.app')

@section('title', 'Manajemen Order')

@section('content')
<section class="content">
    <div class="container-fluid py-4">
        <div class="card clearfix border-0 shadow-sm">
            <div class="card-header bg-light py-3">
                <div class="row align-items-center">
                    <!-- Kiri: Judul & Tombol -->
                    <div class="col-md-6 d-flex align-items-center gap-2">
                        <div class="btn-group mt-2">
                            <a href="#" class="btn btn-sm btn-info mb-2" title="Tambah Kegiatan Internal" data-toggle="modal" data-target="#addOrderModal">
                            <i class="fas fa-plus me-1"></i>
                            </a>
                            <a href="{{ route('admin.orders.export') }}" title="Export Format Excel" target="_blank" class="btn btn-success btn-sm mb-2">
                              <i class="fas fa-file-excel"></i>
                          </a>
                          <a href="{{ route('admin.orders.exportPdf') }}" title="Export Format PDF" class="btn btn-sm btn-danger mb-2">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                    </div>
                        
                    </div>

                    <!-- Kanan: Filter -->
                    <div class="col-md-6">
                        <form method="GET" class="d-flex justify-content-end align-items-center gap-2">
                            <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Cari nama / kode order..." value="{{ request('search') }}" style="max-width: 180px;">

                            <select name="status" class="form-select form-select-sm" style="max-width: 150px;">
                                <option value="">Semua Status</option>
                                <option value="waiting" {{ request('status')=='waiting'?'selected':'' }}>Waiting</option>
                                <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option>
                                <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Completed</option>
                                <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option>
                                <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option>
                            </select>

                            <button class="btn btn-primary btn-sm">
                                <i class="fas fa-search me-1"></i> Filter
                            </button>
                        </form>
                </div>
            </div>
        </div>


        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>#</th>
                            <th>Kode Order</th>
                            <th>Pemesan</th>
                            <th>Tanggal</th>
                            <th>Status Pembayaran</th>
                            <th>Status Order</th>
                            <th>Diverifikasi Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $order->invoice_number }}</td>
                            <td>{{ $order->users->name ?? '-' }}</td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>

                            {{-- Payment status badge --}}
                            <td class="text-center">
                                @php
                                $paymentColor = match($order->payment_status) {
                                    'paid' => 'success',
                                    'unpaid' => 'secondary',
                                    'rejected' => 'danger',
                                    'cancelled' => 'dark',
                                    default => 'warning'
                                };
                                @endphp
                                <span class="badge bg-{{ $paymentColor }}">{{ ucfirst($order->payment_status) }}</span>
                            </td>

                            {{-- Order status badge --}}
                            <td class="text-center">
                                @php
                                $statusColor = match($order->order_status) {
                                    'approved' => 'primary',
                                    'completed' => 'success',
                                    'waiting' => 'warning',
                                    'rejected' => 'danger',
                                    'cancelled' => 'dark',
                                    default => 'secondary'
                                };
                                @endphp
                                <span class="badge bg-{{ $statusColor }}">{{ ucfirst($order->order_status) }}</span>
                            </td>

                            {{-- Verifier info --}}
                            <td class="text-center">
                                @if ($order->verifier)
                                <span class="badge bg-info">
                                    <i class="bi bi-person-check"></i> {{ $order->verifier->name }}
                                </span><br>
                                <small class="text-muted">{{ $order->updated_at->format('d M Y H:i') }}</small>
                                @else
                                <span class="text-muted">Belum diverifikasi</span>
                                @endif
                            </td>

                            {{-- Action --}}
                            <td class="text-center">
                                <a href="{{ route('admin.orders.edit', $order->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                   <i class="bi bi-pencil-square"></i> Edit
                               </a>
                               <a  href="{{ url('admin/order-details/'.$order['id']) }}" class=""> <i class="fas fa-eye text-info"></i></a>
                           </td>
                       </tr>
                       @endforeach
                   </tbody>
               </table>


           </div>
       </div>

       @if(method_exists($orders, 'links'))
       <div class="card-footer">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
</div>
@include('admin.orders._orderModal')
</section>
@endsection
@push('scripts')
<script>
  $(document).ready(function () {
    // Toggle new customer mode
    document.getElementById('new_customer_toggle').addEventListener('change', function () {
      const isNew = this.checked;
      document.getElementById('select_customer').style.display = isNew ? 'none' : 'block';
      document.getElementById('new_customer_name').style.display = isNew ? 'block' : 'none';
      document.getElementById('customer_type').style.display = isNew ? 'block' : 'none';
  });

    // Ketika user existing dipilih
    $('#select_customer').change(function () {
      var selectedType = $(this).find(':selected').data('type');
      if(selectedType){
        $('#customer_type_group').hide();
        $('#customer_type').val(selectedType);
            updateHarga(); // auto hitung harga
        }
    });

    // Bila admin ubah tipe customer (untuk new customer)
    $('#customer_type').change(updateHarga);

    // Bila admin ganti produk
    $('[name="product_id"]').change(updateHarga);

    function updateHarga(){
      var productId = $('[name="product_id"]').val();
      var customerType = $('#customer_type').val();
      var startDate = $('#start_date').val();
      var endDate   = $('#end_date').val();

      if(!productId || !customerType || !startDate || !endDate){
        $('#price_display').html(
          'Lengkapi produk, tipe customer, dan tanggal dulu'
          );
        return;
    }

    $('#price_display').html('Menghitung harga...'); // Loading text

    $.ajax({
      url : "{{ url('admin/get-price') }}",
      type: "POST",
      data:{
        _token: "{{ csrf_token() }}",
        product_id: productId,
        customer_type: customerType,
        start_date: startDate,
        end_date: endDate
    },
    success:function(res){
        let total       = parseFloat(res.total) || 0;
        $('#price_display').html('Rp '+res.product_price+' x '+res.qty+' hari = <b>Rp '+res.total+'</b>');
        $('#grand_total').val(total);
    }
});
}



function resetAvailability() {
    document.getElementById('availabilityResult').innerHTML = "";
    document.getElementById('btnSubmit').setAttribute('disabled', true);
}

    // Cek Ketersediaan Jadwal

$('#checkAvailability').click(function () {

    var startDate = $('#start_date').val();
    var endDate = $('#end_date').val();
    var productId = $('#product_id').val();

     // Validasi awal
    if (!startDate) {
        $('#availabilityResult').html('<span class="text-warning fw-bold">Tanggal mulai harus diisi terlebih dahulu</span>');
        return;
    }

    if (!endDate) {
        $('#availabilityResult').html('<span class="text-warning fw-bold">Tanggal selesai harus diisi</span>');
        return;
    }

    if (!productId) {
        $('#availabilityResult').html('<span class="text-warning fw-bold">Pilih produk terlebih dahulu</span>');
        return;
    }

    if (new Date(startDate) > new Date(endDate)) {
      $('#availabilityResult').html('<span class="text-danger fw-bold">Tanggal mulai tidak boleh lebih dari tanggal selesai</span>');
      return;
  }

  $.ajax({
      url: "{{ url('admin/check-availability') }}",
      type: "POST",
      data: {
        _token: "{{ csrf_token() }}",
        start_date: startDate,
        end_date: endDate,
        product_id: productId
    },
    success: function(response) {
        if(response.status){
          $('#availabilityResult')
          .html('<span class="text-success fw-bold">'+response.message+'</span>');
          $('#btnSubmit').prop('disabled', false);
      } else {
          $('#availabilityResult')
          .html('<span class="text-danger fw-bold">'+response.message+'</span>');
          $('#btnSubmit').prop('disabled', true);
      }
  }
});
});


let hargaNormal = {{ $product->product_price ?? 0 }};
let qty = 0;

function calculateGrandTotal() {
    const type = $('#price_type').val();
    const manual = parseFloat($('#manual_price').val()) || 0;

    if (type === 'normal') {
      $('#manual_price').prop('disabled', true);
      $('#manual_price_error').addClass('d-none');
      $('#grand_total').val(qty * hargaNormal);
      $('#price_info').text('Harga dihitung dari qty × harga normal 💰');
  }

  if (type === 'manual') {
      $('#manual_price').prop('disabled', false);
      if (!manual) {
        $('#manual_price_error').removeClass('d-none');
    } else {
        $('#manual_price_error').addClass('d-none');
    }
    $('#grand_total').val(manual);
    $('#price_info').text('Harga manual diinput user ✍️');
}
}

function updateQty() {
    let start = new Date($('#start_date').val());
    let end = new Date($('#end_date').val());

    if (start && end && end >= start) {
      let diff = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
      qty = diff;
      calculateGrandTotal();
  }
}

$('#start_date, #end_date, #manual_price, #price_type').on('change keyup', function () {
    updateQty();
});

$('#price_type').on('change', calculateGrandTotal);
$('#customer_type').on('change', calculateGrandTotal);
$('#manual_price').on('input', calculateGrandTotal);
$('#start_date, #end_date').on('change', updateQty);


  // Validasi sebelum submit
$('form').on('submit', function(e) {
    if ($('#price_type').val() === 'manual' && !$('#manual_price').val()) {
      e.preventDefault();
      $('#manual_price_error').removeClass('d-none');
  }
});

});
</script>
@include('admin.partials._swalDeleteConfirm')
@endpush
