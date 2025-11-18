<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\AccountBank;
use App\Models\OrderProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Services\ProductPriceService;

class OrderController extends Controller
{
	public function orders()
	{
		$orders = Order::with('orders_products')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
		return view('front.customers.order_list')->with(compact('orders'));
	}

	public function orderSuccess()
	{
		if (Session::has('order_id')) {
			Cart::where('user_id', Auth::user()->id)->delete();
			$orders = Order::with(['orders_products','users'])->where('user_id', Auth::user()->id)->where('id', Session::get('order_id'))->get()->toArray();
			$banks = AccountBank::where('status', 1)->get()->toArray();
			$snap_token = session('snap_token');
			// dd($snap_token);
			return view('front.orders.order_success')->with(compact('orders', 'banks','snap_token'));
		} else {
			return redirect('/cart');
		}
	}

	 /**
     * Upload bukti pembayaran (customer side)
     */
	 public function uploadProof(Request $request)
	 {
	 	$request->validate([
	 		'order_id' => 'required|exists:orders,id',
	 		'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
	 	]);

	 	$order = Order::findOrFail($request->order_id);
        // Pastikan hanya pemilik pesanan yang bisa upload
	 	if ($order->user_id !== Auth::id()) {
	 		return response()->json(['message' => 'Akses ditolak.'], 403);
	 	}

        // Simpan file ke storage privat
	 	if ($request->hasFile('payment_proof')) {
	 		$file = $request->file('payment_proof');
	 		$orderId = $order->id;
	 		$userId = Auth::id();
	 		$timestamp = now()->format('YmdHis');
	 		$ext = $file->getClientOriginalExtension();


	 		$fileName = now()->year .'/'. uniqid() . '.' . $file->getClientOriginalExtension();
	 		$path =  $file->storeAs('private/payment_proofs/' , $fileName);
	 	}
	 	

        // Simpan metadata
	 	$order->update([
	 		'payment_proof' => $fileName,
	 		'payment_proof_uploaded_at' => now(),
	 		'payment_proof_uploaded_by' => Auth::user()->name,
	 		'payment_proof_meta' => json_encode([
	 			'original_filename' => $file->getClientOriginalName(),
	 			'size_kb' => round($file->getSize() / 1024, 2),
	 			'mime_type' => $file->getMimeType(),
	 			'ip_upload' => $request->ip(),
	 		]),
	 	]);

	 	Log::info('Payment proof uploaded', [
	 		'order_id' => $order->id,
	 		'user_id' => Auth::id(),
	 		'file' => $path,
	 		'time' => now(),
	 		'ip' => $request->ip(),
	 	]);

	 	return response()->json([
	 		'success' => true,
	 		'message' => 'Bukti pembayaran berhasil diunggah. Tunggu konfirmasi admin.',
	 	]);
	 }

    /**
     * Lihat bukti pembayaran (admin / user terkait)
     */
    public function viewProof(Order $order)
    {
        // Pastikan hanya admin atau pemilik order yang bisa melihat
    	if (Auth::id() !== $order->user_id && !Auth::user()->hasRole('admin')) {
    		abort(403);
    	}

    	if (!$order->payment_proof || !Storage::exists($order->payment_proof)) {
    		abort(404);
    	}

    	Log::info('Payment proof viewed', [
    		'order_id' => $order->id,
    		'user_id' => Auth::id(),
    		'time' => now(),
    		'ip' => request()->ip(),
    	]);

    	return response()->file(storage_path('app/' . $order->payment_proof));
    }

    public function downloadPDFs($id)
    {
		// dd('hy');
    	$orders = Order::with(['orders_products','users'])->where('user_id', Auth::user()->id)->where('id', $id)->get();
		// dd($orders);
    	$banks = AccountBank::where('status', 1)->get()->toArray();
    	$data = ['orders' => $orders,'banks'=> $banks];
    	$pdf = PDF::loadView('front.orders.pdf', $data);
    	return $pdf->stream();
    }

public function generatePDF($orderId)
    {
        // Ambil order + products + users (eager load)
        $order = Order::with(['orders_products.product', 'users'])->findOrFail($orderId);

        // Hitung totals & price info
        $subtotal = 0;
        $totalDiscount = 0;
        $grandTotal = 0;

        $products = [];
        foreach ($order->orders_products as $op) {
            $productModel = $op->product;
            $priceInfo = ProductPriceService::getPrice($productModel, $order->users->customer_type ?? 'umum');

            $lineTotal = $priceInfo['final_price'] * $op->qty;

            $subtotal += $priceInfo['product_price'] * $op->qty;
            $totalDiscount += $priceInfo['discount'] * $op->qty;
            $grandTotal += $lineTotal;

            $products[] = [
                'name' => $op->product_name,
                'start_date' => $op->start_date,
                'end_date' => $op->end_date,
                'price' => $priceInfo['product_price'],
                'discount' => $priceInfo['discount'],
                'final_price' => $priceInfo['final_price'],
                'qty' => $op->qty,
                'line_total' => $lineTotal,
            ];
        }

        $couponAmount = $order->coupon_amount ?? 0;

        // Ambil bank info & konversi ke base64
        $banks = AccountBank::all()->map(function($bank){
            $path = public_path('front/images/banks/' . $bank->bank_icon);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            return [
                'account_name' => $bank->account_name,
                'account_number' => $bank->account_number,
                'icon_base64' => $base64,
            ];
        });

        $logoPath = public_path('logo_100x100.webp');
        $logoBase64 = 'data:image/webp;base64,' . base64_encode(file_get_contents($logoPath));

        $pdf = PDF::loadView('front.orders.pdf', [
            'order' => $order,
            'products' => $products,
            'subtotal' => $subtotal,
            'totalDiscount' => $totalDiscount,
            'grandTotal' => $grandTotal,
            'couponAmount' => $couponAmount,
            'banks' => $banks,
            'logoBase64' => $logoBase64,
        ]);
           // 🛡️ Proteksi PDF
    	$pdf->getDomPDF()->get_canvas()->get_cpdf()->setEncryption(
        '',     // password untuk membuka (kosong = bebas dibuka)
        null,   // owner password (boleh kosong)
        ['print'], // hanya boleh dicetak, tidak bisa edit/copy
        0       // encryption level (0=128-bit)
    );
        return $pdf->stream("Invoice_{$order->invoice_number}.pdf");
    }
    
    public function generate_PDF($id)
    {
		// dd('hy');
    	$orders = Order::with(['orders_products','users'])->where('user_id', Auth::user()->id)->where('id',$id)->get();
    	$banks = AccountBank::where('status', 1)->get()->toArray();
    	$data = ['orders' => $orders,'banks'=> $banks];
    	$pdf = PDF::loadView('front.orders.pdf', $data);
    	// dd($orders);
    	return $pdf->download('INVOICE.pdf');
    }
}
