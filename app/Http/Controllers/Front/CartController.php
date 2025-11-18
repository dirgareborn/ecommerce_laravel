<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route; 
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\Models\Coupon;
use App\Models\OrderProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\AccountBank;
use Illuminate\Support\Str;
use Midtrans\Snap;
use App\Notifications\NewBookingNotification;
use Log;
use App\Services\ProductPriceService;
use App\Helpers\Carts;

class CartController extends Controller
{
    // ✅ Show Cart Page
   public function index()
{
    // Reset session untuk kupon dan total
    Session::forget(['couponAmount', 'grandTotal', 'couponCode']);

    // Ambil atau buat session_id
    $sessionId = Session::get('session_id', Session::getId());
    Session::put('session_id', $sessionId);

    // Ambil user id jika login, else 0
    $userId = Auth::check() ? Auth::id() : 0;

    // Hitung jumlah item di keranjang
    $cartCount = Cart::where('session_id', $sessionId)->count();

    // Update user_id untuk semua item jika ada di keranjang
    if ($cartCount > 0) {
        Cart::where('session_id', $sessionId)->update(['user_id' => $userId]);
    }
    // Ambil cart items dengan relasi product
    $cartItems = Cart::with(['product', 'product.category'])
        ->where('session_id', $sessionId)
        ->get();
    // // Ambil customer type jika login
    $customerType = Auth::check() ? Auth::user()->customer_type : null;
        
    // Ambil collection of Product
    $products = $cartItems->pluck('product')->filter(); // remove null products

    // Hitung harga semua product
    $priceInfo = ProductPriceService::getPrices($products, $customerType);

    // Ambil item keranjang beserta relasinya
    $cartItems = Cart::with(['product', 'product.category'])
        ->where('session_id', $sessionId)
        ->get();

    // // Ambil semua product_id untuk hitung harga
    // $productIds = $cartItems->pluck('product_id')->toArray();

    // // Ambil customer type jika login
    // $customerType = Auth::check() ? Auth::user()->customer_type : null;

    // // Hitung harga
    // $priceInfo = ProductPriceService::getPrice($productIds, $customerType);

    // Pesan sukses jika ada item
    $message = $cartCount > 0 ? 'Keranjang berhasil diperbaharui' : null;

    return view('front.carts.cart', compact('cartItems', 'message', 'priceInfo'));
}


    // ✅ Add to Cart
    public function addToCart(Request $request)
    {
        if ($request->isMethod('post')) {
            Session::forget(['couponAmount', 'couponCode']);
            $data = $request->all();

            $start = Carbon::parse($data['start'])->format('Y/m/d');
            $end = Carbon::parse($data['end'])->format('Y/m/d');

            $isBooked = Cart::where('product_id', $data['product_id'])
                ->whereDate('start_date', '<=', $start)
                ->whereDate('end_date', '>=', $end)
                ->first();

            if ($isBooked) {
                return response()->json(['status' => false, 'message' => "Produk tidak tersedia di tanggal $start"]);
            }

            $productStatus = Product::productStatus($data['product_id']);
            if ($productStatus == 0) {
                return response()->json(['status' => false, 'message' => 'Produk Tidak Tersedia']);
            }

            $session_id = Session::get('session_id') ?? Session::getId();
            Session::put('session_id', $session_id);

            $user_id = Auth::check() ? Auth::id() : 0;
            $customer_type = Auth::user()->customer_type ?? 'umum';
            // dd($customer_type);
            $qty = convert_date_to_qty($start, $end);

            $exists = Cart::where('product_id', $data['product_id'])
                ->whereDate('start_date', '<=', $start)
                ->whereDate('end_date', '>=', $end)
                ->exists();

            if ($exists) {
                return response()->json(['status' => false, 'message' => 'Produk sudah ada di keranjang']);
            }

            Cart::create([
                'session_id' => $session_id,
                'user_id' => $user_id,
                'product_id' => $data['product_id'],
                'start_date' => $start,
                'end_date' => $end,
                'customer_type' => $customer_type,
                'qty' => $qty,
            ]);
            
            // $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
            // $miniCartView = View::make('front.carts.minicart_items')->render();

            // return response()->json(['status' => true, 'message' => 'Produk berhasil ditambahkan']);

            return response()->json([
            'status' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang!',
            // 'totalCartItems' => $totalCartItems,
            // // 'view' => $cartView,
            // 'minicartview' => $miniCartView,
        ]);
        }
    }

    public function applyCoupon(Request $request)
{
    $code = trim($request->input('code'));
    $user = auth()->user();

    // 🧾 Validasi kupon
    $coupon = Coupon::where('coupon_code', $code)->first();
    if (!$coupon) {
        return response()->json([
            'status' => false,
            'message' => 'Kode kupon tidak ditemukan.',
        ]);
    }

    if ($coupon->status == 0) {
        return response()->json([
            'status' => false,
            'message' => 'Kupon ini sudah tidak aktif.',
        ]);
    }

    // 🧮 Ambil keranjang
    $cartItems = getCartItems();

    if ($cartItems->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => 'Keranjang Anda kosong.',
        ]);
    }

    $customerType = $user->customer_type ?? 'umum';

    // 🔢 Hitung harga semua produk
    $priceData = ProductPriceService::getPrices($cartItems->pluck('product_id'), $customerType);

    $totalNormal   = 0;
    $totalDiscount = 0;
    $grandTotal    = 0;

    foreach ($cartItems as $item) {
        $price = $priceData[$item->product_id];
        $qty   = $item->qty;

        $totalNormal   += $price['product_price'] * $qty;
        $totalDiscount += $price['discount'] * $qty;
        $grandTotal    += $price['final_price'] * $qty;
    }

    // 💰 Hitung kupon
    $couponAmount = 0;
    if ($coupon->amount_type === 'percent') {
        $couponAmount = $grandTotal * ($coupon->amount / 100);
    } elseif ($coupon->amount_type === 'nominal') {
        $couponAmount = $coupon->amount;
    }

    // Jangan biarkan total negatif
    $grandTotal = max(0, $grandTotal - $couponAmount);

    // 💾 Simpan di session
    Session::put('couponCode', $code);
    Session::put('couponAmount', $couponAmount);
    Session::put('grandTotal', $grandTotal);

    // 🔁 Render ulang tampilan keranjang (optional partial)
    $view = view('front.carts.cart_items', compact('cartItems', 'priceData'))->render();
    
    
    return response()->json([
        'status' => true,
        'message' => 'Kupon berhasil diterapkan!',
        'couponAmount' => $couponAmount,
        'grandTotal' => $grandTotal,
        'couponAmountFormatted' => $this->formatCurrency($couponAmount),
        'grandTotalFormatted' => $this->formatCurrency($grandTotal),
        'totalCartItems' => $cartItems->count(),
        'view' => $view,
    ]);
}
/**
 * Format angka ke format rupiah (helper lokal)
 */
private function formatCurrency($value)
{
    return 'Rp ' . number_format($value, 0, ',', '.');
}

    // ✅ Delete Cart Item
    public function delete($id)
    {
        Cart::where('id', $id)->delete();
        return redirect()->back()->with('success_message', 'Item berhasil dihapus');
    }

    // ✅ Checkout
    public function checkout(Request $request)
{
    $getCartItems = cartItems();

    if ($getCartItems->isEmpty()) {
        return redirect('cart')->with('error_message', 'Keranjang masih kosong, booking sebelum melakukan checkout!');
    }

    $snap_token = null;

    if ($request->isMethod('post')) {
        $data = $request->all();

        if (empty($data['payment_method'])) {
            return redirect()->back()->with('error_message', 'Silahkan pilih metode pembayaran');
        }

        if (!isset($data['agree'])) {
            return redirect()->back()->with('error_message', 'Silahkan setujui untuk melanjutkan!');
        }

        // Tentukan status order & payment
        $payment_method = $data['payment_method'];
        $order_status = 'waiting';

        // Hitung total harga menggunakan ProductPriceService
        $total_price = 0;
        foreach ($getCartItems as $item) {
            $priceInfo = ProductPriceService::getPrice(
                $item['product_id'],
                $item['customer_type'] ?? 'umum'
            );
            // dd($priceInfo);
            // $total_price += $priceInfo['final_price'] * $item['qty'];
            $total_price += ($priceInfo['final_price'] ?? 0) * $item['qty'];

        }
        // dd($total_price);
        $couponAmount = Session::get('couponAmount', 0);
        $grand_total = $total_price - $couponAmount;
        Session::put('grand_total', $grand_total);
        
        // Buat Order
        $order = Order::create([
            'user_id' => Auth::id(),
            'payment_method' => $payment_method,
            'grand_total' => $grand_total,
            'coupon_code' => Session::get('couponCode'),
            'coupon_amount' => $couponAmount,
            'order_status' => $order_status,
        ]);

        $order_id = $order->id;

        // Simpan detail produk ke OrderProduct
        foreach ($getCartItems as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            $priceInfo = \App\Services\ProductPriceService::getPrice(
                $item['product_id'],
                $item['customer_type'] ?? 'umum'
            );

            OrderProduct::create([
                'order_id' => $order_id,
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'product_name' => $product->product_name,
                'product_price' => $priceInfo['final_price'],
                'customer_type' => $item['customer_type'] ?? 'umum',
                'start_date' => $item['start_date'],
                'end_date' => $item['end_date'],
                'qty' => $item['qty'],
            ]);
        }

        Session::put('order_id', $order_id);

        // Payment setup
        if (in_array($payment_method, ['cash', 'transfer'])) {
            $orderDetails = Order::with(['orders_products', 'users'])->find($order_id);
            $banks = AccountBank::where('status', 1)->get();
            $email = Auth::user()->email;

            // Kirim email
            Mail::send('emails.order', compact('email', 'order_id', 'orderDetails', 'banks'), function ($message) use ($email) {
                $message->to($email)->subject('Pesanan - Mallbisnisunm.com');
            });

            // Generate snap token jika pakai Midtrans
            $params = [
                'transaction_details' => [
                    'order_id' => $order_id,
                    'gross_amount' => $grand_total,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => $email,
                    'phone' => '081234567890', // bisa diganti dynamic
                ],
            ];

            $snap_token = Snap::getSnapToken($params);
            session(['snap_token' => $snap_token]);
        }

        return redirect('/order-success');
    }

    return view('front.carts.checkout', compact('snap_token', 'getCartItems'));
}

    }
