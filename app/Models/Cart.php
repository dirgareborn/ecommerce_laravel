<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Product;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'session_id',
    'product_id',
    'customer_type',
    'start_date',
                'end_date',
    'qty',
];

    public function product(){
    return $this->belongsTo(Product::class,'product_id')->with('images','category','attributes');
}

    public function priceForCustomer(): array
    {
        $customerType = $this->customer_type ?? 'umum';
        return \App\Services\ProductPricingService::getPrice($this->product, $customerType);
    }

    public static function productBooked($product_id,$start,$end){
        $productBooked = Cart::where(['product_id'=>$product_id])
		                     ->whereRaw("start_date <=  $start")
							 ->whereRaw("end_date >=  $end")->first();
        return $productBooked;
    }
}
