<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;


    protected $fillable = [
        'parent_id',
        'category_name',
        'url',
        'category_image',
        'status',
    ];

    public function parentcategory(){
        return $this->hasOne('App\Models\Category','id','parent_id')->select('id','category_name','url')->where('status',1);
    }

    public function subcategories(){
        return $this->hasMany('App\Models\Category','parent_id')->where('status',1);
    }

    public static function getCategories(){
        return Category::with(['subcategories' => function($query){
            $query->with('subcategories');
        }])
        ->whereNull('parent_id')     // ⬅ ini solusi
        ->where('status', 1)
        ->get()
        ->toArray();
    }

    public static function categoryDetails($url){
        $categoryDetails = Category::select('id','parent_id','category_name','url')
        ->with('subcategories')
        ->where('url',$url)
        ->first()
        ->toArray();
        // echo "<pre>"; print_r($categoryDetails);die;
        $catIds = array();
        $catIds[] = $categoryDetails['id'];
        foreach($categoryDetails['subcategories'] as $subcat){
            $catIds[] = $subcat['id'];
        }
        if($categoryDetails['parent_id']==Null){
            // Only show main category
            $breadcrumbs = "";
        }else{
            $parentCategory = Category::select('category_name','url')->where('id',$categoryDetails['parent_id'])->first()->toArray();
        }
        return array('catIds'=>$catIds,'categoryDetails'=>$categoryDetails);
    }

    public function products(): hasMany
    {
        return $this->hasMany(Product::class);
    }

    protected static function boot() {
        parent::boot();

        static::creating(function ($category) {
            $category->url = Str::slug($category->category_name, '-');
        });
        static::updating(function ($category) {
            $category->url = Str::slug($category->category_name, '-');
        });
    }
}
