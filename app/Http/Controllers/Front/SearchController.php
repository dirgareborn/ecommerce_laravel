<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Document;
use App\Models\Category;
use App\Models\Information;

class SearchController extends Controller
{
 
    public function index(Request $request)
    {
        $q = trim($request->input('q'));

        if (!$q) {
            return view('front.search', ['query' => '', 'results' => []]);
        }

        // Pecah kata menjadi beberapa
        $keywords = preg_split('/\s+/', $q);

        $results = [];

        // ==================== PRODUK ====================
        $products = Product::where('product_name', 'LIKE', "%$q%")
        ->select('product_name as title', 'url', 'product_image as image')
        ->limit(10)
        ->get()
        ->map(function($item) use ($keywords) {
            return [
                'type'  => 'Produk',
                'title' => $item->title,
                'url'   => url('produk/'.$item->url),
                'image' => $item->image,
            ];
        });

        // ==================== DOKUMEN ====================
        $documents = Document::where('title', 'LIKE', "%$q%")
        ->select('title', 'doc_path')
        ->limit(10)
        ->get()
        ->map(function($item) use ($keywords) {
            return [
                'type'  => 'Dokumen',
                'title' => $item->title,
                'url'   => route('dokumen.preview', $item->doc_path),
                'image' => null,
            ];
        });

        // ==================== KATEGORI ====================
        $categories = Category::where('category_name', 'LIKE', "%$q%")
        ->select('category_name as title', 'url', 'category_image')
        ->limit(10)
        ->get()
        ->map(function($item) use ($keywords) {
            return [
                'type'  => 'Kategori',
                'title' => $item->title,
                'url'   => url('kategori/'.$item->url),
                'image' => $item->category_image,
            ];
        });

        // ==================== INFORMASI ====================
        $informations = Information::where('title', 'LIKE', "%$q%")
        ->orWhere('content', 'LIKE', "%$q%")
        ->select('title', 'content', 'slug')
        ->limit(10)
        ->get()
        ->map(function($item) use ($keywords) {
            return [
                'type'  => 'Informasi',
                'title' => $item->title,
                'url'   => url('informasi/'.$item->slug),
                'image' => null,
            ];
        });

        // ========== Gabungkan semua =============
        $results = collect(array_merge(
            $products->toArray(),
            $documents->toArray(),
            $informations->toArray(),
            $categories->toArray()
        ));
        return view('front.search', [
            'query'   => $q,
            'results' => $results
        ]);
    }
}
