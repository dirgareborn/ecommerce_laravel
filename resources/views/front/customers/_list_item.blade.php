
@forelse($orders as $order)
<div class="card mb-3 border-0 shadow-sm rounded-4 wow fadeInUp" data-wow-delay="0.1s">
    <div class="card-body py-3 px-4">
        <div class="row align-items-center">
            <!-- Order Info -->
            <div class="col-md-4 mb-3 mb-md-0">
                <h6 class="fw-semibold mb-1 text-dark">
                    <i class="bi bi-receipt-cutoff text-primary me-1"></i> Order #{{ $order->id }}
                </h6>
                <small class="text-muted">
                    Dipesan: {{ format_date($order->created_at) }}
                </small>
            </div>

            <!-- Payment Info -->
            <div class="col-md-5">
                <p class="mb-1">
                    <span class="text-muted"><i class="bi bi-cash-stack me-1"></i>Total:</span>
                    <span class="fw-semibold text-dark">@currency($order->grand_total)</span>
                </p>
                <p class="mb-1">
                    <span class="text-muted"><i class="bi bi-credit-card me-1"></i>Metode:</span>
                    {{ $order->payment_method }}
                </p>

                @php
                    $statusColor = match($order->order_status) {
                        'waiting'   => 'text-warning',   // menunggu proses
                        'approved'  => 'text-info',      // disetujui
                        'completed' => 'text-success',   // selesai
                        'rejected'  => 'text-danger',    // ditolak
                        'cancelled' => 'text-secondary', // dibatalkan
                        default     => 'text-muted',
                    };

                    $statusIcon = match($order->order_status) {
                        'waiting'   => 'bi bi-hourglass-split',
                        'approved'  => 'bi bi-check-circle',
                        'completed' => 'bi bi-check-circle-fill',
                        'rejected'  => 'bi bi-x-circle-fill',
                        'cancelled' => 'bi bi-slash-circle',
                        default     => 'bi bi-question-circle',
                    };
                @endphp


                <small class="{{ $statusColor }}">
                    Status: {{ $order->order_status }}
                    <i class="{{ $statusIcon }} ms-1"></i>
                </small>
            </div>
            <!-- Actions -->
            <div class="col-md-3 col-lg-3 text-center text-md-end d-flex justify-content-md-end justify-content-center gap-2">
                <a href="javascript:void(0)" 
                class="btn btn-outline-primary btn-sm viewOrderDetails"
                data-orderid="{{ $order->id }}" title="Lihat Detail">
                <i class="fa fa-eye"></i>
            </a>

            <a href="{{ route('order.invoice', $order->id) }}" 
             target="_blank" 
             class="btn btn-outline-danger btn-sm" title="Unduh Invoice">
             <i class="fa fa-file-pdf"></i>
         </a>

         @if($order->payment_proof)
         <button class="btn btn-outline-info btn-sm viewProofBtn" 
         data-bs-toggle="modal" data-bs-target="#viewProofModal"
         data-proof="{{ route('payment.proof.show', $order->id) }}"> 
         <i class="fa fa-image" title="Lihat Bukti Pembayaran"></i> 
     </button>
     @elseif($order->order_status === 'waiting')
     <button class="btn btn-outline-success btn-sm uploadProofBtn"
     data-orderid="{{ $order->id }}"
     data-bs-toggle="modal"
     data-bs-target="#uploadProofModal" >
     <i class="fa fa-upload" title="Upload Bukti"></i>
 </button>
 @endif
</div>

</div>
</div>
</div>
@empty
<div class="alert alert-info text-center rounded-3 shadow-sm">
    <i class="bi bi-info-circle me-2"></i> Belum ada pesanan yang tercatat.
</div>
@endforelse
