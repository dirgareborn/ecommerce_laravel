<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminsRole;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\OrderProduct;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
     // public function dashboard(){
     //    return view('admin.dashboard');
     // }
    public function dashboard(){
      Session::put('page', 'dashboard');

        // === Statistik dasar ===
        $categoryCount = Category::count();
        $productCount  = Product::count();
        $adminCount    = Admin::count();
        $customerCount = User::count();

        // === PIE CHART ===
        $pie = Product::select('categories.category_name', DB::raw('COUNT(*) AS total'))
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->groupBy('categories.category_name')
            ->get()
            ->map(fn($row) => [
                'name' => $row->category_name,
                'y'    => (int) $row->total,
            ])
            ->toArray();

        // === LINE CHART === (jumlah penyewaan per bulan)
        $lineRows = OrderProduct::select(
                DB::raw('MAX(orders.created_at) AS created_at'),
                DB::raw('SUM(order_products.qty) AS total')
            )
            ->join('orders', 'orders.id', '=', 'order_products.order_id')
            ->whereIn('orders.order_status', ['approved', 'completed'])
            ->groupByRaw('YEAR(orders.created_at), MONTH(orders.created_at)')
            ->orderBy(DB::raw('MAX(orders.created_at)'))
            ->get();

        $line = [
            'categories' => $lineRows->pluck('created_at')->map(fn($d) => date('M-Y', strtotime($d))),
            'data'       => $lineRows->pluck('total')->map(fn($v) => (int) $v),
        ];

        // === COLUMN CHART === (per kategori per tahun)
        $columnRows = OrderProduct::select(
                'categories.category_name',
                DB::raw('YEAR(orders.created_at) AS year'),
                DB::raw('SUM(order_products.qty) AS total'),
                DB::raw('MAX(orders.created_at) AS latest')
            )
            ->join('orders', 'orders.id', '=', 'order_products.order_id')
            ->whereIn('orders.order_status', ['approved', 'completed'])
            ->join('products', 'products.id', '=', 'order_products.product_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->groupBy('categories.category_name', 'year')
            ->orderBy(DB::raw('MAX(orders.created_at)'))
            ->get();

        $column = [
            'categories' => [],
            'series'     => [],
        ];

        foreach ($columnRows as $row) {
            $column['categories'][$row->year] = $row->year;
            $column['series'][$row->category_name]['name'] = $row->category_name;
            $column['series'][$row->category_name]['data'][$row->year] = (int) $row->total;
        }

        foreach ($column['series'] as &$serie) {
            $serie['data'] = array_values($serie['data']);
        }

        $column['categories'] = array_values($column['categories']);
        $column['series']     = array_values($column['series']);

        return view('admin.dashboard', compact(
            'categoryCount',
            'productCount',
            'adminCount',
            'customerCount',
            'pie',
            'line',
            'column'
        ));
    }
}