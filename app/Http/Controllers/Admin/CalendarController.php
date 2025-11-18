<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('product_name')->get(['id', 'product_name']);
        return view('admin.calendars.index', compact('products'));
    }

    public function events(Request $request)
    {
        $query = OrderProduct::with(['order.users', 'product'])
            ->whereHas('order', fn($q) =>
                $q->whereIn('order_status', ['approved', 'completed'])
            );

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $events = $query->get()->map(function ($item) {
            $start = $item->start_date ?? now()->format('Y-m-d');
            $end = $item->end_date ? Carbon::parse($item->end_date)->addDay()->format('Y-m-d') : $start;
            $userName = $item->order->users->name ?? 'Unknown';

            return [
                'id'    => $item->order->id,
                'title' => $item->product->product_name . ' (' . $userName . ')',
                'start' => $start,
                'end'   => $end,
                'color' => match($item->order->order_status) {
                    'approved'  => '#28a745',
                    'completed' => '#007bff',
                    default     => '#6c757d',
                },
            ];
        });

        return response()->json($events);
    }

    public function detailAjax($id)
    {
        $order = Order::with(['orders_products.product', 'users', 'verifier'])->findOrFail($id);
        return view('admin.calendars._detail', compact('order'));
    }
}
