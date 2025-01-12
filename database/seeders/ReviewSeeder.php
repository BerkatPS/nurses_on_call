<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        Review::create([
            'booking_id' => 1,
            'user_id' => 1,
            'rating' => 5,
            'comment' => 'Excellent emergency service, very professional!'
        ]);

        Review::create([
            'booking_id' => 2,
            'user_id' => 2,
            'rating' => 4,
            'comment' => 'Good home care service, could be improved'
        ]);
    }
}
