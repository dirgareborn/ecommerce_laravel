<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function createTransaction(Request $request)
    {
        $params = [
            'transaction_details' => [
                'order_id' => rand(),
                'gross_amount' => 10000, // nominal pembayaran
            ],
            'customer_details' => [
                'first_name' => 'Budi',
                'email' => 'budi@example.com',
                'phone' => '081234567890',
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('payment.checkout', compact('snapToken'));
    }
    public function notification(Request $request)
{
    $notif = new \Midtrans\Notification();

    $transaction = $notif->transaction_status;
    $type = $notif->payment_type;
    $order_id = $notif->order_id;
    $fraud = $notif->fraud_status;

    if ($transaction == 'capture') {
        if ($type == 'credit_card'){
            if($fraud == 'challenge'){
                // challenge
            } else {
                // success
            }
        }
    } else if ($transaction == 'settlement') {
        // pembayaran sukses
    } else if($transaction == 'pending'){
        // menunggu pembayaran
    } else if ($transaction == 'deny') {
        // pembayaran ditolak
    } else if ($transaction == 'expire') {
        // pembayaran kadaluarsa
    } else if ($transaction == 'cancel') {
        // pembayaran dibatalkan
    }
}

}
