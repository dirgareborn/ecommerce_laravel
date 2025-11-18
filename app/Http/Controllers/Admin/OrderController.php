<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProduct;
use App\Models\ProductAttribute;
use App\Models\User;
use Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
class OrderController extends Controller
{
	public function index(Request $request)
	{
	    // Query dasar
		$query = Order::with(['orders_products.product', 'users'])
		->orderByDesc('id');

	    // Filter berdasarkan status (optional)
		if ($request->filled('status')) {
			$query->where('order_status', $request->status);
		}

	    // Filter pencarian berdasarkan kode order atau nama user
		if ($request->filled('search')) {
			$search = $request->search;
			$query->where(function($q) use ($search) {
				$q->where('invoice_number', 'LIKE', "%{$search}%")
				->orWhereHas('users', fn($u) => $u->where('name', 'LIKE', "%{$search}%"));
			});
		}

	    // Pagination
		$orders = $query->paginate(10)->withQueryString();

	    // Data tambahan untuk filter dropdown
		$users = User::orderBy('name')->get(['id', 'name']);
		$products = Product::orderBy('product_name')->get(['id', 'product_name']);

		return view('admin.orders.index', compact('orders', 'users', 'products'));
	}

	public function edit($id)
	{
		$order = Order::with(['orders_products.product', 'users'])->findOrFail($id);
		$statuses = ['waiting', 'approved', 'completed', 'rejected', 'cancelled'];

		return view('admin.orders.edit', compact('order', 'statuses'));
	}

	public function orderDetails($id)
	{
		$orderDetails = Order::with('orders_products','users')->where('id',$id)->get()->toArray();
			// dd($orderDetails);
		return view('admin.orders.order_details', compact('orderDetails'));

	}
	public function showPaymentProof($id)
	{
		$order = Order::findOrFail($id);

		if (!$order->payment_proof) {
			abort(404, 'Bukti pembayaran tidak ditemukan.');
		}

		    // Path lengkap ke file di storage privat
		$path = 'private/payment_proofs/' . $order->payment_proof;

		    // Pastikan file ada
		if (!Storage::exists($path)) {
			abort(404, 'File bukti pembayaran tidak ditemukan.');
		}

		    // 🔐 Validasi hanya admin yang boleh mengakses
		if (!auth('admin')->user()) {
			abort(403, 'Akses ditolak.');
		}

		    // Stream file agar bisa dilihat di browser (jika gambar/PDF)
		return response()->file(storage_path('app/' . $path));
	}

	public function update(Request $request, $id)
	{
		$order = Order::findOrFail($id);

		$request->validate([
			'payment_status' => 'required|string|in:unpaid,waiting,paid,failed,refunded',
			'payment_verifier_note' => 'nullable|string|max:500',
		]);

		$newPaymentStatus = $request->payment_status;
		$order->payment_status = $newPaymentStatus;
		$order->payment_verifier_note = $request->payment_verifier_note;

		     // Mapping otomatis order_status berdasarkan payment_status
		$statusMap = [
			'unpaid'   => 'waiting',
			'waiting'  => 'waiting',
			'paid'     => 'approved',
			'failed'   => 'rejected',
			'refunded' => 'cancelled',
		];

		$order->order_status = $statusMap[$newPaymentStatus] ?? $order->order_status;

		    // Jika admin yang men-approve atau menolak, simpan admin id + waktu + note
		    $adminId = auth('admin')->id() ?: null; // gunakan guard admin
		    if (in_array($newPaymentStatus, ['unpaid','paid','failed','refunded'])) {
		    	$order->verified_by_admin_id = $adminId;
		    	$order->payment_verified_at = Carbon::now();
		    	$order->payment_verifier_note = $request->payment_verifier_note ?? null;
		    } else {
		        // jika status kembali ke pending / cancelled: opsional - nullify atau biarkan
		    	$order->verified_by_admin_id = null;
		    	$order->payment_verified_at = null;
		    	$order->payment_verifier_note = null;
		    }

		    $order->save();

		        // Catat log aktivitas
		    activity('order-verification')
		    ->causedBy(auth('admin')->user())
		    ->performedOn($order)
		    ->withProperties([
		    	'payment_status' => $request->payment_status,
		    	'order_status' => $order->order_status,
		    	'verified_by' => auth('admin')->user()->name,
		    	'notes' => $request->payment_verifier_note,
		    ])
		    ->log('Admin memverifikasi pembayaran');

		    return redirect()
		    ->route('admin.orders.index')
		    ->with('success', 'Order berhasil diperbarui.');
		}

		public function export()
		{
			$fileName = 'orders_' . now()->format('Ymd_His') . '.xlsx';
			return Excel::download(new OrdersExport, $fileName);
		}

		public function exportPdf()
		{
		    $orders = Order::with(['orders_products', 'users'])->orderBy('id', 'DESC')->get();

		    $pdf = PDF::loadView('admin.orders.export_pdf', compact('orders'))
		              ->setPaper('a4', 'landscape');

		    return $pdf->download('orders.pdf');
		}

		public function checkAvailability(Request $request)
		{
			$start_date = $request->start_date;
			$end_date = $request->end_date;
			$product_id = $request->product_id;

	    // Validasi input
			if(!$start_date || !$end_date || !$product_id){
				return response()->json([
					'status' => false,
					'message' => 'Lengkapi tanggal dan produk terlebih dahulu'
				]);
			}

	    // Cari order yang tanggalnya nabrak
			$exists = OrderProduct::where('product_id', $product_id)
			->where(function ($query) use ($start_date, $end_date) {
				$query->whereBetween('start_date', [$start_date, $end_date])
				->orWhereBetween('end_date', [$start_date, $end_date])
				->orWhereRaw('? BETWEEN start_date AND end_date', [$start_date])
				->orWhereRaw('? BETWEEN start_date AND end_date', [$end_date]);
			})
			->exists();
			// dd($exists);
			if($exists){
				return response()->json([
					'status' => false,
					'message' => 'Jadwal Tidak Tersedia ❌'
				]);
			}

			return response()->json([
				'status' => true,
				'message' => 'Jadwal Tersedia ✅'
			]);
		}

		public function getPrice(Request $request)
		{
			$request->validate([
				"product_id" => "required",
				"customer_type" => "required",
				"start_date" => "required",
				"end_date" => "required"
			]);

			$qty = convert_date_to_qty($request->start_date, $request->end_date);

			$unitPrice = ProductAttribute::where("product_id", $request->product_id)
			->where("customer_type", $request->customer_type)
			->value("price");

	    // dd($unitPrice);
			return response()->json([
				"status" => true,
				"product_price" => $unitPrice,
				"qty" => $qty,
				"total" => $unitPrice * $qty
			]);
		}

		public function store(Request $request)
		{

			$request->validate([
				'product_id' => 'required',
				'customer_type' => 'required',
				'start_date' => 'required|date',
				'end_date' => 'required|date|after_or_equal:start_date',
			]);

	    DB::beginTransaction(); // mulai transaction

	    try {
	        // Create user baru jika perlu
	    	if ($request->filled('new_customer_name')) {
	    		$name = $request->new_customer_name;
	    		$customerType = $request->customer_type;
	    		$email = \Str::slug($name) . '@gmail.com';
	    		$password = bcrypt('123456');

	    		$user = User::create([
	    			'name' => $name,
	    			'email' => $email,
	    			'address' => 'Makassar',
	    			'pincode' => 0,
	    			'password' => $password,
	    			'customer_type' => $customerType,
	    			'status' => 1
	    		]);

	    		$userId = $user->id;
	    	} else {
	    		$userId = $request->user_id;
	    		$customerType = $request->customer_type;
	    	}

	    	$qty = convert_date_to_qty($request->start_date, $request->end_date);
	    	$product = Product::findOrFail($request->product_id);

	    	$order = Order::create([
	    		'user_id' => $userId,
	    		'order_status' => 'approved',
	    		'payment_status' => 'paid',
	    		'payment_method' => $request->payment_method,
	    		'grand_total' => $request->grand_total,
	    	]);

	    	OrderProduct::create([
	    		'order_id' => $order->id,
	    		'user_id' => $userId,
	    		'product_id' => $product->id,
	    		'product_name' => $product->product_name,
	    		'product_price' => $product->product_price,
	    		'customer_type' => $request->customer_type,
	    		'start_date' => $request->start_date,
	    		'end_date' => $request->end_date,
	    		'qty' => $qty,
	    		'order_date' => Carbon::now()->toDateString(),
	    	]);

	        DB::commit(); // commit transaction kalau semua berhasil
	        return redirect()->back()->with('success_message','Order berhasil dibuat');

	    } catch (\Exception $e) {
	        DB::rollBack(); // batalkan semua insert jika ada error
	        return redirect()->back()->with('error_message','Terjadi kesalahan: '.$e->getMessage());
	    }
	}

	public function show($id)
	{
		$orders = Order::with(['orders_products.product', 'users'])
		->findOrFail($id);

        // Format data agar sesuai dengan blade kamu
		$order = [
			'id' => $orders->invoice_number,
			'created_at' => $orders->created_at,
			'users' => [
				'name' => $orders->users->name ?? '-',
				'email' => $orders->users->email ?? '-',
			],
			'orders_products' => $orders->orders_products->map(function($item) {
				return [
					'product_name' => $item->product->product_name ?? '-',
					'start_date'   => $item->start_date,
					'end_date'     => $item->end_date,
					'qty'          => $item->qty,
				];
			}),
			'grand_total'    => $orders->grand_total,
			'payment_method' => $orders->payment_method,
			'payment_status' => $orders->payment_status,
		];

        // dd($orders);
		return view('admin.orders._details', compact('order'));
	}


}
