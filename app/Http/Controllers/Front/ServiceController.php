<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Unit;
use App\Services\ServicePriceService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Listing layanan/aset berdasarkan unit atau search
     */
    public function listing()
    {
        $slug = Route::current()->parameter('slug');
        $page_title = Str::title(str_replace('-', ' ', $slug));

        if ($slug) {
            // Ambil unit berdasarkan slug
            $unit = Unit::where(['slug' => $slug, 'is_active' => 1])->firstOrFail();

            $services = Service::with('slides')
                ->where('unit_id', $unit->id)
                ->active()
                ->orderByDesc('id')
                ->simplePaginate(12);

            return view('front.services.listing', compact('unit', 'services', 'page_title'));
        }

        // Search global
        if ($query = request()->get('query')) {
            $unit = null;
            $services = Service::with(['slides', 'unit'])
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%$query%")
                        ->orWhere('facility', 'like', "%$query%")
                        ->orWhere('description', 'like', "%$query%");
                })
                ->active()
                ->get();

            return view('front.services.listing', compact('unit', 'services', 'page_title'));
        }

        abort(404);
    }

    /**
     * Detail layanan/aset
     */
    public function show($unitSlug, $serviceSlug)
    {
        $service = Service::with(['slides', 'unit.parent', 'ratings'])
            ->whereSlug($serviceSlug)
            ->firstOrFail();
        // dd($service);
        $canReview = false;

        if (auth()->check()) {
            $canReview = \App\Models\BookingService::whereHas('booking', function ($q) {
                $q->where('user_id', auth()->id())
                    ->where('booking_status', 'completed'); // booking selesai
            })
                ->where('service_id', $service->id)
                ->exists();
        }

        $average_rating = $service->ratings()->avg('rating') ?? 0;
        $total_reviews = $service->ratings()->count();
        $average_percentage = $average_rating ? round($average_rating / 5 * 100) : 0;
        // edit
        $reviews = $service->ratings()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Breakdown 1–5
        $rating_breakdown = [];
        for ($i = 5; $i >= 1; $i--) {
            $rating_breakdown[$i] = $service->ratings()->where('rating', $i)->count();
        }

        // Persentase breakdown
        $rating_percentage = [];
        foreach ($rating_breakdown as $rating => $count) {
            $rating_percentage[$rating] = $total_reviews > 0
                ? round(($count / $total_reviews) * 100)
                : 0;
        }

        $userReview = auth()->check()
        ? $service->ratings()->where('user_id', auth()->id())->first()
        : null;
        //  end edit

        $customerType = auth()->check() ? auth()->user()->customer_type : 'umum';

        $priceInfo = ServicePriceService::getPrice($service->id, $customerType);

        $userHasReviewed = auth()->check()
            ? $service->ratings()->where('user_id', auth()->id())->exists()
            : false;

        $page_title = Str::title(str_replace('-', ' ', $serviceSlug));

        return view('front.services.showNew', compact(
            'page_title',
            'service',
            'priceInfo',
            'customerType',
            'average_rating',
            'total_reviews',
            'average_percentage',
            'userHasReviewed',

            'rating_breakdown',
            'rating_percentage',
            'reviews',
            'userReview',
            'canReview'
        ));
    }
}
