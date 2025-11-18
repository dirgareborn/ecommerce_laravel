@extends('front.layouts.app')

@section('content')
@include('front.partials.breadcumb')

<div class="container-xl py-5">
    <div class="container">
        @if(session('error_message'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <strong><i class="bi bi-exclamation-triangle me-1"></i> Error:</strong> {{ session('error_message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('success_message'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <strong><i class="bi bi-check-circle me-1"></i> Success:</strong> {{ session('success_message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-0 gx-5">
            @include('front.customers.sidebar')
            <div class="col-12 col-md-8 mt-2 mt-md-0">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                   <div class="card-header bg-primary text-white border-0 text-center position-relative" style="height: 36px;">
                        <div class="position-absolute top-50 start-50 translate-middle text-center">
                            <h6 class="fw-bold text-white mb-0">
                                <i class="bi bi-bag-check me-2"></i> Daftar Pesanan
                            </h6>
                        </div>
                    </div>
                    <div class="card-body px-3 px-md-4 py-4">
                        @include('front.customers._list_item')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==========================
     MODAL UPLOAD BUKTI PEMBAYARAN
========================== -->
<div class="modal fade" id="uploadProofModal" tabindex="-1" aria-labelledby="uploadProofModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="uploadProofModalLabel">Upload Bukti Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="uploadProofForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="order_id" id="order_id">
                    <div class="mb-3">
                        <label for="payment_proof" class="form-label">Pilih Gambar (JPG, PNG, max 2MB)</label>
                        <input type="file" class="form-control" name="payment_proof" id="payment_proof" accept="image/*" required>
                    </div>
                    <div id="previewImage" class="text-center"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ==========================
     MODAL LIHAT BUKTI PEMBAYARAN
========================== -->
<div class="modal fade" id="viewProofModal" tabindex="-1" aria-labelledby="viewProofModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title text-white" id="viewProofModalLabel" >Bukti Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div id="proofImageContainer">
                    <div class="text-muted">Memuat bukti pembayaran...</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ url('front/js/order.js') }}"></script>
@endpush
