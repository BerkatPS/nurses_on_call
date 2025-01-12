<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmergencyCall;
use Carbon\Carbon;

class EmergencyCallSeeder extends Seeder
{
    public function run()
    {
        EmergencyCall::create([
            'user_id' => 1,
            'assigned_nurse_id' => 1,
            'location' => 'Jakarta, Central Business District',
            'description' => 'Patient experiencing chest pain',
            'emergency_type' => 'medical',
            'status' => 'responded',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
        ]);

        EmergencyCall::create([
            'user_id' => 2,
            'assigned_nurse_id' => 2,
            'location' => 'Bandung, Residential Area',
            'description' => 'Minor injury after fall',
            'emergency_type' => 'accident',
            'status' => 'pending',
            'latitude' => -6.9175,
            'longitude' => 107.6191 ]);

        EmergencyCall::create([
            'user_id' => 1,
            'assigned_nurse_id' => 1,
            'location' => 'Jakarta, Central Business District',
            'description' => 'Patient experiencing chest pain',
            'emergency_type' => 'medical',
            'status' => 'responded',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
        ]);

        EmergencyCall::create([
            'user_id' => 2,
            'assigned_nurse_id' => 2,
            'location' => 'Bandung, Residential Area',
            'description' => 'Minor injury after fall',
            'emergency_type' => 'accident',
            'status' => 'pending',
            'latitude' => -6.9175,
            'longitude' => 107.6191,
        ]);
    }
}
