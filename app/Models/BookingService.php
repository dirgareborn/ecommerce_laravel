<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingService extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'user_id', redundant
        'booking_id',
        // 'booking_date',
        'customer_type',
        'service_id',
        'name',
        'price',
        'start_date',
        'end_date',
        'qty',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    // OPTIONAL jika user_id dipertahankan
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
