<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NurseReviewController extends Controller
{
    public function index()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Ambil ID perawat yang sedang login
        $nurseId = $user->nurse->id;

        // Ambil semua booking yang terkait dengan nurse_id
        $bookings = Booking::where('nurse_id', $nurseId)->with('review')->get();

        // Ambil semua review yang terkait dengan booking
        $reviews = Review::whereIn('booking_id', $bookings->pluck('id'))->with('booking')->get();

        // Kirim data ke view
        return view('nurses.reviews.index', compact('bookings', 'reviews'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'booking_id' => $request->booking_id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json(['success' => true, 'message' => 'Review berhasil ditambahkan.']);
    }
    public function show($id)
    {
        try {
            $review = Review::with(['booking.service', 'user'])
                ->findOrFail($id);

            // Pastikan hanya pemilik review yang bisa melihat detail
            if ($review->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin melihat review ini.'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'review' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail review.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
