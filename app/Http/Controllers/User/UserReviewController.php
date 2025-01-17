<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Nurse;
use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserReviewController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Dapatkan review yang sudah dibuat
        $reviews = Review::where('user_id', $user->id)
            ->with(['booking.service', 'user'])
            ->latest()
            ->get();

        // Dapatkan booking yang belum direview
        $reviewableBookings = Booking::where('user_id', $user->id)
            ->whereDoesntHave('review')
            ->whereIn('status', ['completed', 'success'])
            ->with('service')
            ->get();

        return view('users.reviews.index', [
            'reviews' => $reviews,
            'reviewableBookings' => $reviewableBookings
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id|unique:reviews,booking_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $booking = Booking::findOrFail($request->booking_id);

            // Pastikan booking sudah selesai dan belum direview
            if ($booking->status !== 'completed' || $booking->review()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking tidak valid untuk direview'
                ], 400);
            }

            $review = Review::create([
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);

            // Update nurse rating
            $this->updateNurseRating($booking->nurse_id);

            return response()->json([
                'success' => true,
                'message' => 'Review berhasil disimpan',
                'data' => $review
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Review Creation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat review: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function updateNurseRating($nurseId)
    {
        $nurse = Nurse::findOrFail($nurseId);
        $reviews = Review::whereHas('booking', function($query) use ($nurseId) {
            $query->where('nurse_id', $nurseId);
        })->get();

        $averageRating = $reviews->avg('rating');
        $nurse->rating = round($averageRating, 2);
        $nurse->save();
    }


    public function destroy($id)
    {
        try {
            $review = Review::findOrFail($id);

            // Pastikan hanya pemilik review yang bisa menghapus
            if ($review->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin menghapus review ini.'
                ], 403);
            }

            // Hapus review
            $review->delete();

            return response()->json([
                'success' => true,
                'message' => 'Review berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus review.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dapatkan detail review
     */
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
