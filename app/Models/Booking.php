<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_number',
        'coupon_code',
        'coupon_amount',
        'booking_status',
        'total_amount',
        'is_internal',
        'booking_date',
        // opsional, ini pindahan dari tabel bookingservice
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            $payment->invoice_number = self::generateInvoiceNumber();
        });
    }

    public static function generateInvoiceNumber(): string
    {
        // Format: INV-YYYYMMDD-0001
        $prefix = 'INV-'.Carbon::now()->format('Ymd').'-';
        $last = self::whereDate('created_at', Carbon::today())
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = $last ? intval(substr($last->invoice_number, -4)) + 1 : 1;

        return $prefix.str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
    }

    public function bookingservices()
    {
        return $this->hasMany(BookingService::class, 'booking_id');
    }

    public function services()
    {
        return $this->hasManyThrough(
            Service::class,
            BookingService::class,
            'booking_id',   // FK di BookingService
            'id',           // PK di Service
            'id',           // PK di Booking
            'service_id'    // FK di BookingService ke Service
        );
    }

    public function user()
    {
        // return $this->hasOne(User::class, 'id', 'user_id');
        return $this->belongsTo(User::class);
    }

    public function verifier()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'verified_by_admin_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
