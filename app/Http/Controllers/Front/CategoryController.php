<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CategoryController extends Controller
{
    use HasFactory;
    
    public function category()
    {
		
        $page_title = 'KATEGORI';
         $categories = Category::with('subcategories')->orderBy('category_name')->get();
        // dd($layanan);
        return view('front.pages.categories', compact('page_title','categories'));
    }


}