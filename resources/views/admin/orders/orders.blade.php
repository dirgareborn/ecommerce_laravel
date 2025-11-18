@extends('admin.layout.app')
@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
@endpush
@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card clearfix">
          <div class="card-header clearfix">
            <a href="#" class="btn btn-sm btn-flat btn-info float-right" data-toggle="modal" data-target="#addOrderModal">
              <i class="fas fa-plus"></i> 
            </a>
            <h3 class="card-title">Daftar Orderan</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            @include('admin.partials.alert')
            <table id="cmspages" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Tanggal Order</th>
                  <th>Nama</th>
                  <th>Produk Order</th>
                  <th>Status Order</th>
                  <th>Metode Pembayaran</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($orders as  $order)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ date("j F Y", strtotime($order['created_at'])); }}</td>
                  <td>{{ $order['users']['name'] ?? '-' }}</td>
                  <td>
                   @foreach( $order['orders_products'] as $product)
                   <p>{{ $product['product_name'] }} </p>
                   <small> Tanggal Pemakaian :	{{ format_date($product['start_date']) }} </small>
                   @endforeach
                 </td>
                 <td>{{ $order['order_status'] }}</td>
                 <td>{{ $order['payment_method'] }}</td>
                 <td>
                  <a  href="{{ url('admin/order-details/'.$order['id']) }}" class=""> <i class="fas fa-eye text-info"></i></a>
                </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->

  @include('admin.calendars.index')

</section>
<!-- /.content -->

<!-- Modal Tambah Order -->
<div class="modal fade" id="addOrderModal" tabindex="-1" role="dialog" aria-labelledby="addOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="addOrderModalLabel">Tambah Order</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <form action="{{ url('admin/add-order') }}" method="POST">
        @csrf
        <div class="modal-body">

          <div class="modal-body">

            <!-- Pilih Produk / Aset -->
            <div class="mb-3">
              <label class="form-label">Pilih Produk / Aset</label>
              <select name="product_id" id="product_id" class="form-control" required>
                <option value="">-- Pilih Produk --</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                @endforeach
              </select>
            </div>

            <!-- Tanggal & Cek Ketersediaan -->
            <div class="row g-3 align-items-end mb-3">
              <div class="col-md-4">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" id="start_date" name="start_date" class="form-control">
              </div>
              <div class="col-md-4">
                <label for="end_date" class="form-label">Tanggal Selesai</label>
                <input type="date" id="end_date" name="end_date" class="form-control">
              </div>
              <div class="col-md-4 d-grid">
                <button type="button" id="checkAvailability" class="btn btn-info">
                  Cek Ketersediaan
                </button>
                <div id="availabilityResult" class="mt-2"></div>
              </div>
            </div>

            <!-- Harga -->
            <div class="row g-3 mb-3">
              <div class="col-md-4">
                <label class="form-label">Tipe Harga</label>
                <select name="price_type" id="price_type" class="form-control">
                  <option value="normal">Normal</option>
                  <option value="manual">Manual</option>
                </select>
                <small id="price_info" class="text-primary">Harga dihitung otomatis</small>
              </div>
              <div class="col-md-4">
                <label class="form-label">Harga Manual</label>
                <input type="number" name="manual_price" id="manual_price" class="form-control" placeholder="Masukkan harga manual" disabled>
                <small class="text-danger d-none" id="manual_price_error">Harga manual wajib diisi jika pilih Manual</small>
              </div>
              <div class="col-md-4">
                <label class="form-label">Total Harga</label>
                <input type="number" name="grand_total" id="grand_total" class="form-control" readonly>
              </div>
            </div>

            <!-- Customer -->
            <div class="mb-3">
              <label class="form-label">Customer</label>
              <div class="row g-2 align-items-center">
                <div class="col-auto d-flex align-items-center">
                  <div class="form-check mb-0">
                    <input type="checkbox" class="form-check-input" id="new_customer_toggle">
                    <label class="form-check-label" for="new_customer_toggle">Customer Baru</label>
                  </div>
                </div>
                <div class="col-md-3">
                  <select name="user_id" id="select_customer" class="form-control">
                    <option value="">-- Pilih Customer --</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" data-type="{{ $user->customer_type }}">
                      {{ $user->name }} ({{ $user->email }})
                    </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-3">
                  <input type="text" id="new_customer_name" name="new_customer_name" class="form-control" placeholder="Nama Customer Baru" style="display:none;">
                </div>
                <div class="col-md-3">
                  <select name="customer_type" id="customer_type" class="form-control" style="display:none;">
                    <option value="umum">Umum</option>
                    <option value="civitas">Civitas</option>
                    <option value="mahasiswa">Mahasiswa</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Metode Pembayaran -->
            <div class="mb-3">
              <label class="form-label">Metode Pembayaran</label>
              <select name="payment_method" class="form-control" required>
                <option value="Transfer">Transfer</option>
                <option value="Tunai">Tunai</option>
              </select>
            </div>

            <!-- Estimasi Harga -->
            <div class="mb-3">
              <label class="form-label">Estimasi Harga</label>
              <div id="price_display" class="alert alert-info text-dark fw-bold">
                Harga akan dihitung otomatis berdasarkan tanggal & tipe customer
              </div>
            </div>

          </div>


        </div>

        <div class="modal-footer">
          <!-- <button type="submit" class="btn btn-primary btn-flat">Simpan</button> -->
          <button type="submit" id="btnSubmit" class="btn btn-primary btn-flat" disabled>Simpan</button>
          <button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Batal</button>
        </div>

      </form>
    </div>
  </div>
</div>

@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
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

document.addEventListener('DOMContentLoaded', function () {
  let calendarEl = document.getElementById('calendar');

  let calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    events: {
      url: "{{ route('calendar.events') }}",
      method: 'GET'
    },
    displayEventTime: false,
    eventClick: function(info) {
      alert("Order ID: " + info.event.id);
    }
  });

  calendar.render();

    // Filter berdasarkan produk
  document.getElementById('filterProduct').addEventListener('change', function () {
    let product_id = this.value;
    calendar.removeAllEvents();
    calendar.addEventSource({
      url: "{{ route('calendar.events') }}",
      method: 'GET',
      extraParams: {
        product_id: product_id
      }
    });
    calendar.refetchEvents();
  });
});
</script>
@include('admin.partials._swalDeleteConfirm')
@endpush
