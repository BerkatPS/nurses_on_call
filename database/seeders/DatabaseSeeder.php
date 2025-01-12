<?php
// DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            NurseSeeder::class,
            ServiceSeeder::class,
            BookingSeeder::class,
            ReviewSeeder::class,
            EmergencyCallSeeder::class,
            PaymentSeeder::class
        ]);
    }
}
