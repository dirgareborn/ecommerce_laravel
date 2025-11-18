@extends('admin.layout.app')

@section('content')
<section class="content">
<div class="card border-0 shadow-sm">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i> Edit Order #{{ $order->id }}</h5>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-4">
                <!-- Informasi User -->
                <div class="col-md-6">
                    <table class="table table-sm mb-0">
                        <tr>
                            <th width="40%">Nama Pemesan</th>
                            <td>{{ $order->users->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $order->users->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pesanan</th>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Status Order</th>
                            <td><span class="badge bg-info text-dark">{{ ucfirst($order->order_status) }}</span></td>
                        </tr>
                    </table>

                    <label for="payment_status" class="form-label fw-bold">Status Pembayaran</label>
                    <select name="payment_status" id="payment_status" class="form-select form-select-sm">
                        <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                    <label for="payment_verifier_note" class="form-label fw-bold">Catatan Admin</label>
                    <textarea name="payment_verifier_note" id="payment_verifier_note" rows="2" class="form-control form-control-sm">{{ old('payment_verifier_note', $order->payment_verifier_note) }}</textarea>
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>

                </form>
            </div>

            <!-- Bukti Pembayaran -->
            <div class="col-md-6">
                <h6 class="fw-bold mb-2">Bukti Pembayaran</h6>
                @if($order->payment_proof)
                <a href="{{ route('admin.orders.paymentProof', $order->id) }}" target="_blank" class="d-inline-block">
                    <img src="{{ route('admin.orders.paymentProof', $order->id) }}" 
                    alt="Bukti Pembayaran" 
                    class="img-thumbnail shadow-sm" 
                    style="max-height: 200px;">
                </a>
                @else
                <p class="text-muted fst-italic">Belum ada bukti pembayaran diunggah.</p>
                @endif

                <label class="form-label fw-bold">Verifikasi Pembayaran</label>
                @if($order->verified_by_admin_id && $order->payment_verified_at)
                <p class="mb-1">
                    <strong>Verifikator:</strong>
                    {{ $order->verifier->name ?? '— (admin dihapus)' }}
                </p>
                <p class="mb-1">
                    <strong>Waktu Verifikasi:</strong>
                    {{ \Carbon\Carbon::parse($order->payment_verified_at)->format('d M Y H:i') }}
                </p>
                @if($order->payment_verifier_note)
                <p class="mb-0"><strong>Catatan Verifikator:</strong> {{ $order->payment_verifier_note }}</p>
                @endif
                @else
                <p class="text-muted mb-0">Belum diverifikasi oleh admin.</p>
                @endif
            </div>
        </div>
<hr>
<h6 class="mt-4">Riwayat Verifikasi Pembayaran</h6>
<table class="table table-sm table-bordered">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Admin</th>
            <th>Aktivitas</th>
            <th>Waktu</th>
            <th>Detail</th>
        </tr>
    </thead>
    <tbody>
        @forelse(\Spatie\Activitylog\Models\Activity::where('subject_type', \App\Models\Order::class)
            ->where('subject_id', $order->id)
            ->orderByDesc('created_at')
            ->get() as $i => $log)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $log->causer?->name ?? 'System' }}</td>
                <td>{{ $log->description }}</td>
                <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                <td>
                    <small class="text-muted">
                        @foreach($log->properties ?? [] as $key => $value)
                            <div><b>{{ ucfirst($key) }}:</b> {{ $value }}</div>
                        @endforeach
                    </small>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">Belum ada riwayat aktivitas.</td>
            </tr>
        @endforelse
    </tbody>
</table>
</section>
@endsection
