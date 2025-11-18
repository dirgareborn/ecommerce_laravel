<?php

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Order;


if (! function_exists('cartItems')) {
    /**
     * Ambil cart items per user/session
     * Gunakan cache sebentar 1 menit agar tidak berulang
     */
    function cartItems() {
        $user_id = Auth::check() ? Auth::id() : 0;
        $session_id = Session::get('session_id') ?? Session::getId();
        Session::put('session_id', $session_id);

        $cacheKey = $user_id ? "cart_items_user_{$user_id}" : "cart_items_session_{$session_id}";

        return \Illuminate\Support\Facades\Cache::remember($cacheKey, 60, function() use($user_id, $session_id) {
            $query = Cart::with(['product', 'product.category']);
            if ($user_id) {
                $query->where('user_id', $user_id);
            } else {
                $query->where('session_id', $session_id);
            }
            return $query->get();
        });
    }
}

if (! function_exists('totalCartItems')) {
    /**
     * Hitung total cart items dari collection yang sudah ada
     */
    function totalCartItems() {
        return cartItems()->count();
    }
}

function getCartItems(){
        $getCartItems = cartItems();
    return $getCartItems;
}
function getCartItemsDashboard(){
    $getCartItemsDashboard = Cart::with('product')->get()->toArray();
    return $getCartItemsDashboard;
}

if (! function_exists('user_pending_orders')) {
    function user_pending_orders()
    {
        $user = auth()->user();
        if(!$user) return 0;
        return Order::where('user_id', $user->id)
            ->whereIn('order_status', ['waiting','approved','rejected','completed','cancelled'])
            ->count();
    }
}

