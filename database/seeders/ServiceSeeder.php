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

        Service::create([
            'type' => 'checkup',
            'name' => 'Health Checkup',
            'description' => 'Comprehensive health checkup and assessment',
            'base_price' => 300000,
        ]);

        Service::create([
            'type' => 'general',
            'name' => 'General Illness Treatment',
            'description' => 'Treatment for common illnesses and conditions',
            'base_price' => 400000,
        ]);

        Service::create([
            'type' => 'pediatric',
            'name' => 'Pediatric Care',
            'description' => 'Specialized care for children and infants',
            'base_price' => 600000,
        ]);

        Service::create([
            'type' => 'geriatric',
            'name' => 'Geriatric Care',
            'description' => 'Comprehensive care for elderly patients',
            'base_price' => 550000,
        ]);
    }
}
