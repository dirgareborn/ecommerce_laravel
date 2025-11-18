<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use App\Models\ProductsImage;
use App\Models\ProductAttribute;
use Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Services\ProductPriceService;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name', 
        'discount_type',
        'product_discount',
        'product_price',
        'product_facility',
        'category_id',
        'location_id',
        'status',
        'url'
    ];

    protected $casts = [
        'product_facility'  => 'json',
    ];

    protected static function boot() {
        parent::boot();

        static::saving(function ($product) {
            $product->url = Str::slug($product->product_name);
        });
    }
   public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }

    public function averageRating(): float
    {
        return round($this->ratings()->avg('rating') ?? 0, 1);
    }

    public function totalRatings(): int
    {
        return $this->ratings()->count();
    }
    
    public function locations(): BelongsTo
    {
        return $this->belongsTo(Location::class,'location_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id')->with('parentcategory');
    }

    public function images(): hasMany
    {
        return $this->hasMany(ProductsImage::class);
    }
    public function attributes(): hasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    
    /** ==============================
     * PRICE LOGIC
     * ============================== */
    public function priceFor(string $customerType): array
    {
        return ProductPriceService::calculate($this, $customerType);
    }

    public function finalPriceFor(string $customerType): float
    {
        return ProductPriceService::finalPrice($this, $customerType);
    }

    public static function getAttributePrice_old($product_id,$cusType){
        $attributePrice = ProductAttribute::where(['product_id'=>$product_id,'customer_type'=>$cusType])
        ->first()->toArray();
        // Product Discount
        $productDetails = Product::select(['product_discount','category_id'])
        ->where('id',$product_id)->first()->toArray();
        // Category Discount
        $categoryDetails = Category::select(['category_discount'])
        ->where('id',$productDetails['category_id'])->first()->toArray();

        if($productDetails['product_discount']>0){
            $discount = $attributePrice['price']*$productDetails['product_discount']/100;
            $discount_percent = $productDetails['product_discount'];
            $final_price = $attributePrice['price']-$discount;
        }else if($categoryDetails['category_discount']>0){
            $discount = $attributePrice['price']*$categoryDetails['category_discount']/100;
            $discount_percent = $productDetails['category_discount'];
            $final_price = $attributePrice['price']-$discount;
        }else{
            $discount = 0;
            $discount_percent = 0;
            $final_price = $attributePrice['price'];
        }
        return array('product_price'=>$attributePrice['price'],'final_price'=>$final_price,'discount'=>$discount,'discount_percent'=>$discount_percent);
    }
    public static function productStatus($product_id){
        $productStatus = Product::select('status')->where('id',$product_id)->first();
        return $productStatus->status;
    }
    public static function getProductDetails($product_id){
      $getProductDetails = Product::where('id',$product_id)->first()->toArray();
      return $getProductDetails;
  }
  public static function getAttributeDetails($product_id,$cusType){
      $getAttributeDetails = ProductAttribute::where(['product_id'=>$product_id,'customer_type'=>$cusType])
      ->first()->toArray();
      return $getAttributeDetails;
  }

  public static function getProductActive(){
      $getProductActive = Product::where('status',1)->get()->toArray();
      return $getProductActive;
  }

}
