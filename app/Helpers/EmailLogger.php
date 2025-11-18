<?php

namespace App\Helpers;

use App\Models\EmailLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EmailLogger
{
    public static function logQueued(string $email, string $subject, string $mailableClass, ?int $orderId = null)
    {
        EmailLog::create([
            'recipient_email' => $email,
            'subject' => $subject,
            'mailable_class' => $mailableClass,
            'order_id' => $orderId,
            'status' => 'queued',
        ]);
    }

    public static function logSent(string $email, string $subject, string $mailableClass, ?int $orderId = null)
    {
        EmailLog::where([
            'recipient_email' => $email,
            'subject' => $subject,
            'mailable_class' => $mailableClass,
        ])->update([
            'status' => 'sent',
            'sent_at' => Carbon::now(),
        ]);
    }

    public static function logFailed(string $email, string $subject, string $mailableClass, string $error, ?int $orderId = null)
    {
        Log::error("Email gagal dikirim ke {$email}: {$error}");
        EmailLog::create([
            'recipient_email' => $email,
            'subject' => $subject,
            'mailable_class' => $mailableClass,
            'order_id' => $orderId,
            'status' => 'failed',
            'error_message' => $error,
        ]);
    }
}
