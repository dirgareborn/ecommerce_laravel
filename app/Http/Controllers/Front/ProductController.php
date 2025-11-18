<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductPriceService;

class ProductController extends Controller
{
    public function listing()
    {
            // Ambil slug dari URL
    $slug = Route::current()->parameter('slug');

    $page_title = Str::title(str_replace('-', ' ', $slug));

    if ($slug) {
        // Cek apakah category ada
        $category = Category::where(['url' => $slug, 'status' => 1])->first();

        if (!$category) {
            abort(404);
        }

        $categoryDetails = Category::categoryDetails($slug);
        $categoryProducts = Product::with('images')
            ->whereIn('category_id', $categoryDetails['catIds'])
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->simplePaginate();

        return view('front.products.listing', compact('categoryDetails', 'categoryProducts', 'page_title'));
    }

    // Jika ada search query
    if ($query = request()->get('query')) {
        $categoryDetails['category_name'] = $query;
        $categoryProducts = Product::with(['images','category'])
            ->where(function($q) use ($query){
                $q->where('product_name','like',"%$query%")
                  ->orWhere('product_description','like',"%$query%");
            })
            ->where('status',1)
            ->get();

        return view('front.products.listing', compact('categoryDetails','categoryProducts','page_title'));
    }

    abort(404);
    }

    public function show($category, $url)
    {
        // Ambil produk dengan relasi
        $product = Product::with(['images', 'attributes', 'category', 'ratings'])->whereUrl($url)->firstOrFail();

        $average_rating = $product->ratings()->avg('rating') ?? 0;
        $total_reviews = $product->ratings()->count();

        // persentase 0-100 untuk vote
        $average_percentage = $average_rating ? round($average_rating / 5 * 100) : 0;

        // Ambil tipe customer dari user login
        $customerType = auth()->user()->customer_type ?? 'umum';

        // Hitung harga dengan service
        $priceInfo = ProductPriceService::calculate($product, $customerType);

        $userHasReviewed = auth()->check() 
        ? $product->ratings()->where('user_id', auth()->id())->exists() 
        : false;
        $title = Str::title(str_replace('-', ' ', $url));

        return view('front.products.show', [
            'page_title' => $title,
            'product' => $product,
            'priceInfo' => $priceInfo,
            'customerType' => $customerType,
            'average_rating'=> $average_rating, 
            'total_reviews' => $total_reviews,
            'average_percentage' => $average_percentage,
            'userHasReviewed'=> $userHasReviewed
        ]);
    }
}
