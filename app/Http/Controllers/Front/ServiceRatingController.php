<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceRating;
use Illuminate\Http\Request;

class ServiceRatingController extends Controller
{
    public function rate(Request $request, Service $service)
    {
        if (! auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Login required'], 403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:255',
        ]);

        if ($service->ratings()->where('user_id', auth()->id())->exists()) {
            return response()->json(['success' => false, 'message' => 'Anda sudah memberikan rating.']);
        }
        $service->ratings()->create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        $average_rating = $service->ratings()->avg('rating');
        $total_reviews = $service->ratings()->count();

        return response()->json([
            'success' => true,
            'average_rating' => $average_rating,
            'total_reviews' => $total_reviews,
        ]);
    }

    public function store(Request $request, $service_id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
        ]);

        ServiceRating::updateOrCreate(
            [
                'service_id' => $service_id,
                'user_id' => auth()->id(),
            ],
            [
                'rating' => $request->rating,
                'review' => $request->review,
            ]
        );

        return back()->with('success', 'Ulasan berhasil ditambahkan.');
    }

    public function update(Request $request, $service_id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
        ]);

        $review = ServiceRating::where('service_id', $service_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $review->update($request->only('rating', 'review'));

        return back()->with('success', 'Ulasan berhasil diperbarui.');
    }

    public function delete($service_id)
    {
        $review = ServiceRating::where('service_id', $service_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $review->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
