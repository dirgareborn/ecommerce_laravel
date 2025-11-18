<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\AccountBank;
use Illuminate\Support\Facades\Mail;


class SendOrderEmailCommand extends Command
{
    protected $signature = 'orders:send-emails';
    protected $description = 'Mengirim email notifikasi untuk order baru atau yang belum dikirim emailnya';

    public function handle()
    {
        // Ambil order yang belum dikirim email (misal status waiting)
        $orders = Order::where('order_status', 'waiting')
            ->whereNull('email_sent_at')
            ->with(['orders_products', 'users'])
            ->get();

        // $orders = Order::where('order_status', 'confirmed')
        // ->whereDate('start_date', '=', now()->addDay()->toDateString())
        // ->get();

        if ($orders->isEmpty()) {
            $this->info('Tidak ada order yang perlu dikirim email.');
            return Command::SUCCESS;
        }

        foreach ($orders as $order) {
            $email = $order->users->email;
            $banks = \App\Models\AccountBank::where('status', 1)->get();

            // Kirim email
            try {
                Mail::send(
                    'emails.order',
                    ['email' => $email, 'order_id' => $order->id, 'orderDetails' => $order, 'banks' => $banks],
                    function ($message) use ($email, $order) {
                        $message->to($email)->subject('Pesanan #' . $order->id . ' - Mallbisnisunm.com');
                    }
                );

                // Tandai sudah dikirim
                $order->update(['email_sent_at' => now()]);

                $this->info("Email berhasil dikirim ke: {$email} (Order ID: {$order->id})");
            } catch (\Exception $e) {
                $this->error("Gagal mengirim email ke {$email}: " . $e->getMessage());
            }
        }

        return Command::SUCCESS;
    }
}