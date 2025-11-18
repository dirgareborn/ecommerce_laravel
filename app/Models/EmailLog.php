<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = [
        'recipient_email',
        'subject',
        'mailable_class',
        'order_id',
        'status',
        'error_message',
        'sent_at',
    ];
}
