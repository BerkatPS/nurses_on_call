<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nurse;

class NurseSeeder extends Seeder
{
    public function run()
    {
        Nurse::create([
            'user_id' => 3, // Sesuaikan dengan ID user nurse
            'specializations' => 'Emergency Care, Critical Care',
            'certifications' => json_encode([
                'Emergency Nursing Certification',
                'Advanced Cardiac Life Support'
            ]),
            'current_location' => 'Jakarta',
            'availability_status' => 'available',
            'rating' => 4.5
        ]);

        Nurse::create([
            'user_id' => 4, // Sesuaikan dengan ID user nurse
            'specializations' => 'Home Care, Geriatric Care',
            'certifications' => json_encode([
                'Home Care Nursing Certification',
                'Geriatric Nursing Specialist'
            ]),
            'current_location' => 'Bandung',
            'availability_status' => 'on-call',
            'rating' => 4.2
        ]);
    }
}
