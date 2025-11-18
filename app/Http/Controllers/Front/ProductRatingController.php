<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductRating;
use Illuminate\Support\Facades\Auth;

class ProductRatingController extends Controller
{

       public function rate(Request $request, Product $product)
    {
          if(!auth()->check()){
        return response()->json(['success' => false, 'message' => 'Login required'], 403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:255'
        ]);

            // Check if user already rated
            if($product->ratings()->where('user_id', auth()->id())->exists()){
                return response()->json(['success'=>false,'message'=>'Anda sudah memberikan rating.']);
            }
             $product->ratings()->create([
        'user_id' => auth()->id(),
        'rating' => $request->rating,
        'review' => $request->review,
    ]);

    $average_rating = $product->ratings()->avg('rating');
    $total_reviews = $product->ratings()->count();

        return response()->json([
            'success' => true,
            'average_rating' => $average_rating,
            'total_reviews' => $total_reviews
        ]);
    }
}

