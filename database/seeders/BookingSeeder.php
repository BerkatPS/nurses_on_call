<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run()
    {
        Booking::create([
            'user_id' => 1,
            'nurse_id' => 1,
            'service_id' => 1,
            'status' => 'confirmed',
            'location' => 'Jakarta',
            'start_time' => Carbon::now()->addDays(2),
            'end_time' => Carbon::now()->addDays(2)->addHours(2),
            'total_amount' => 750000,
            'emergency_level' => 3,
            'notes' => 'Emergency medical support needed'
        ]);

        Booking::create([
            'user_id' => 2,
            'nurse_id' => 2,
            'service_id' => 2,
            'status' => 'pending',
            'location' => 'Bandung',
            'start_time' => Carbon::now()->addDays(5),
            'end_time' => Carbon::now()->addDays(5)->addHours(3),
            'total_amount' => 500000,
            'emergency_level' => 2,
            'notes' => 'Home care for elderly patient'
        ]);
    }
}
