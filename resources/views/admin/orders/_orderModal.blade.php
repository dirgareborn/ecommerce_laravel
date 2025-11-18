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