@extends('admin.layout.app')

@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
<style>
  #calendar { max-width: 100%; margin: 0 auto; }
  .fc-toolbar-title { font-size: 1.25rem; font-weight: 600; }
</style>
@endpush

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Kalender Penggunaan Aset</h3>
      </div>
      <div class="card-body">
        <select id="filterProduct" class="form-control mb-3" style="width: 250px;">
          <option value="">Semua Produk</option>
          @foreach($products as $product)
            <option value="{{ $product->id }}">{{ $product->product_name }}</option>
          @endforeach
        </select>
        <div id="calendar"></div>
      </div>
    </div>
  </div>
</section>

<!-- Modal Detail Order -->
<div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="orderDetailLabel">Detail Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="orderDetailContent">
        <div class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat data...</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  let calendarEl = document.getElementById('calendar');
  let calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    events: {
      url: "{{ route('admin.calendar.events') }}",
      method: 'GET',
      extraParams: function() {
        return { product_id: document.getElementById('filterProduct').value }
      }
    },
    displayEventTime: false,
    eventClick: function(info) {
      const orderId = info.event.id;
      const modal = new bootstrap.Modal(document.getElementById('orderDetailModal'));
      const content = document.getElementById('orderDetailContent');

      content.innerHTML = `<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat data...</div>`;
      modal.show();

      $.ajax({
        url: `/admin/orders/${orderId}/detail`,
        method: 'GET',
        success: function(res) {
          content.innerHTML = res;
        },
        error: function() {
          content.innerHTML = `<div class="text-danger text-center">Gagal memuat data.</div>`;
        }
      });
    }
  });

  calendar.render();

  // Filter produk
  document.getElementById('filterProduct').addEventListener('change', function () {
    calendar.refetchEvents();
  });
});
</script>
@endpush