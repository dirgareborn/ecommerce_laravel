<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'payment_status' => 'required|string|in:unpaid,pending,paid,failed,refunded',
            'payment_verifier_note' => 'nullable|string|max:500',
        ]);

        $payment->payment_status = $request->payment_status;
        $payment->payment_verifier_note = $request->payment_verifier_note;

        // Mapping otomatis booking_status jika perlu
        $statusMap = [
            'unpaid' => 'waiting',
            'pending' => 'waiting',
            'paid' => 'approved',
            'failed' => 'rejected',
            'refunded' => 'cancelled',
        ];
        $payment->booking->booking_status = $statusMap[$request->payment_status] ?? $payment->booking->booking_status;

        // Simpan info admin yang memverifikasi
        $payment->verified_by_admin_id = Auth::guard('admin')->id();
        $payment->payment_verified_at = Carbon::now();

        $payment->save();
        $payment->booking->save();

        activity('payment-verification')
            ->causedBy(Auth::guard('admin')->user())
            ->performedOn($payment)
            ->withProperties([
                'payment_status' => $request->payment_status,
                'booking_status' => $payment->booking->booking_status,
                'notes' => $request->payment_verifier_note,
            ])
            ->log('Admin memverifikasi pembayaran');

        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function showProof($id)
    {
        $payment = Payment::findOrFail($id);

        if (! $payment->payment_proof || ! \Storage::exists('private/payment_proofs/'.$payment->payment_proof)) {
            abort(404, 'Bukti pembayaran tidak ditemukan.');
        }

        return response()->file(storage_path('app/private/payment_proofs/'.$payment->payment_proof));
    }
}
