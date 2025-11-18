<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\AccountBank;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $banks;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->banks = AccountBank::where('status', 1)->get();
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Invoice Pesanan #' . $this->order->id . ' - Mallbisnisunm.com')
                    ->markdown('emails.order')
                    ->with([
                        'order' => $this->order,
                        'banks' => $this->banks,
                    ]);
    }
}
