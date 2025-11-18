<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;
	
	protected $fillable = [
		'user_id',
        'invoice_number',
		'coupon_code' ,
		'coupon_amount',
		'order_status',
		'payment_status',
		'payment_method',
		'payment_proof',
		'payment_proof_uploaded_at',
		'payment_proof_uploaded_by',
		'payment_proof_meta',
		'grand_total',
        'paid_at',
        'notes'
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
        $prefix = 'INV-' . Carbon::now()->format('Ymd') . '-';
        $last = self::whereDate('created_at', Carbon::today())
                    ->orderBy('id', 'desc')
                    ->first();

        $lastNumber = $last ? intval(substr($last->invoice_number, -4)) + 1 : 1;

        return $prefix . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
    }

	public function orders_products(){
		return $this->hasMany(OrderProduct::class,'order_id');
	}
	
	public function users(){
		return $this->hasOne(User::class,'id','user_id');
		// return $this->belongsTo(User::class);
	}

	public function verifier()
	{
	    return $this->belongsTo(\App\Models\Admin::class, 'verified_by_admin_id');
	}


}