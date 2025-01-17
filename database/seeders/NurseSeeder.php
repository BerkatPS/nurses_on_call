<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nurse;

class NurseSeeder extends Seeder
{
    public function run()
    {
        Nurse::create([
            'user_id' => 3, // Kim San
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
            'user_id' => 4, // Isna Salbia
            'specializations' => 'Home Care, Geriatric Care',
            'certifications' => json_encode([
                'Home Care Nursing Certification',
                'Geriatric Nursing Specialist'
            ]),
            'current_location' => 'Bandung',
            'availability_status' => 'on-call',
            'rating' => 4.2
        ]);

        Nurse::create([
            'user_id' => 5, // Brian Wahjudi
            'specializations' => 'Pediatric Care, General Nursing',
            'certifications' => json_encode([
                'Pediatric Advanced Life Support',
                'Basic Life Support'
            ]),
            'current_location' => 'Jakarta',
            'availability_status' => 'available',
            'rating' => 4.8
        ]);

        Nurse::create([
            'user_id' => 6, // Febita Famelia
            'specializations' => 'Geriatric Care, Home Care',
            'certifications' => json_encode([
                'Geriatric Nursing Specialist',
                'Home Care Nursing Certification'
            ]),
            'current_location' => 'Bandung',
            'availability_status' => 'available',
            'rating' => 4.6
        ]);

        Nurse::create([
            'user_id' => 7, // Saphira Eva
            'specializations' => 'Emergency Care, Critical Care',
            'certifications' => json_encode([
                'Emergency Nursing Certification',
                'Advanced Cardiac Life Support'
            ]),
            'current_location' => 'Jakarta',
            'availability_status' => 'on-call',
            'rating' => 4.7
        ]);
    }
}
