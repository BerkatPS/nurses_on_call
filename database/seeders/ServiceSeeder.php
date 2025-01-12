<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        Service::create([
            'type' => 'emergency',
            'name' => 'Emergency Medical Response',
            'description' => 'Rapid medical assistance for critical situations',
            'base_price' => 750000,
        ]);

        Service::create([
            'type' => 'homecare',
            'name' => 'Home Nursing Care',
            'description' => 'Professional nursing care at home',
            'base_price' => 500000,
        ]);
    }
}
