<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingService;
use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class BookingController extends Controller
{
    // Daftar booking
    public function index(Request $request)
    {
        $query = Booking::with(['bookingservices.service', 'user'])
            ->orderByDesc('id');

        if ($request->filled('status')) {
            $query->where('booking_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'LIKE', "%{$search}%"));
            });
        }

        $bookings = $query->paginate(10)->withQueryString();

        $users = User::orderBy('name')->get(['id', 'name']);
        $services = Service::orderBy('name')->get(['id', 'name']);

        return view('admin.bookings.index', compact('bookings', 'users', 'services'));
    }

    // Lihat detail booking
    public function show($id)
    {
        $booking = Booking::with(['bookingservices.service', 'user', 'payments'])->findOrFail($id);

        return view('admin.bookings.show', compact('booking'));
    }

    // Form edit pembayaran/verifikasi
    public function edit($id)
    {
        $booking = Booking::with(['bookingservices.service', 'user', 'payments'])->findOrFail($id);
        $statuses = ['waiting', 'approved', 'completed', 'rejected', 'cancelled'];

        return view('admin.bookings.edit', compact('booking', 'statuses'));
    }

    // Update pembayaran/verifikasi
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'payment_status' => 'required|in:unpaid,pending,paid,failed,refunded',
            'payment_verifier_note' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Ambil payment terbaru
            $payment = $booking->payments()->latest()->first();

            if (! $payment) {
                $payment = new Payment(['booking_id' => $booking->id]);
            }

            $payment->update([
                'payment_status' => $request->payment_status,
                'verified_by_admin_id' => auth('admin')->id(),
                'payment_verified_at' => Carbon::now(),
                'payment_verifier_note' => $request->payment_verifier_note,
            ]);

            // Map booking_status otomatis
            $statusMap = [
                'unpaid' => 'waiting',
                'pending' => 'waiting',
                'paid' => 'approved',
                'failed' => 'rejected',
                'refunded' => 'cancelled',
            ];

            $booking->booking_status = $statusMap[$request->payment_status] ?? $booking->booking_status;
            $booking->save();

            DB::commit();

            return redirect()->route('admin.bookings.index')->with('success', 'Booking diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Cek ketersediaan service
    public function checkAvailability(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $service_id = $request->service_id;

        if (! $start_date || ! $end_date || ! $service_id) {
            return response()->json([
                'status' => false,
                'message' => 'Lengkapi tanggal dan layanan terlebih dahulu',
            ]);
        }

        $exists = BookingService::where('service_id', $service_id)
            ->where(function ($q) use ($start_date, $end_date) {
                $q->whereBetween('start_date', [$start_date, $end_date])
                    ->orWhereBetween('end_date', [$start_date, $end_date])
                    ->orWhereRaw('? BETWEEN start_date AND end_date', [$start_date])
                    ->orWhereRaw('? BETWEEN start_date AND end_date', [$end_date]);
            })->exists();

        return response()->json([
            'status' => ! $exists,
            'message' => $exists ? 'Jadwal tidak tersedia ❌' : 'Jadwal tersedia ✅',
        ]);
    }

    // Ambil harga service
    public function getPrice(Request $request)
    {
        $request->validate([
            'service_id' => 'required',
            'customer_type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $qty = convert_date_to_qty($request->start_date, $request->end_date);

        $unitPrice = \App\Models\ServicesPrice::where('service_id', $request->service_id)
            ->where('customer_type', $request->customer_type)
            ->value('price');

        return response()->json([
            'status' => true,
            'service_price' => $unitPrice,
            'qty' => $qty,
            'total' => $unitPrice * $qty,
        ]);
    }

    // Buat booking baru via admin/internal
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required',
            'customer_type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'user_id' => 'nullable|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $userId = $request->user_id;

            // Jika admin input internal booking tanpa user
            if (! $userId) {
                $userId = auth('admin')->id(); // optional assign admin user
            }

            $qty = convert_date_to_qty($request->start_date, $request->end_date);
            $service = Service::findOrFail($request->service_id);

            // Buat booking
            $booking = Booking::create([
                'user_id' => $userId,
                'invoice_number' => Booking::generateInvoiceNumber(),
                'total_amount' => $service->default_price * $qty,
                'booking_status' => 'approved',
                'is_internal' => true,
            ]);

            // Buat booking service
            BookingService::create([
                'booking_id' => $booking->id,
                'service_id' => $service->id,
                'name' => $service->name,
                'price' => $service->default_price,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'qty' => $qty,
                'customer_type' => $request->customer_type,
            ]);

            // Buat payment otomatis untuk internal
            Payment::create([
                'booking_id' => $booking->id,
                'user_id' => $userId,
                'payment_status' => 'paid',
                'amount_paid' => $service->default_price * $qty,
                'payment_method' => 'transfer',
                'verified_by_admin_id' => auth('admin')->id(),
                'payment_verified_at' => Carbon::now(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Booking berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Export Excel
    public function export()
    {
        $fileName = 'bookings_'.now()->format('Ymd_His').'.xlsx';

        return Excel::download(new \App\Exports\BookingsExport, $fileName);
    }

    // Export PDF
    public function exportPdf()
    {
        $bookings = Booking::with(['bookingservices.service', 'user', 'payments'])->orderByDesc('id')->get();
        $pdf = PDF::loadView('admin.bookings.export_pdf', compact('bookings'))->setPaper('a4', 'landscape');

        return $pdf->download('bookings.pdf');
    }
}
