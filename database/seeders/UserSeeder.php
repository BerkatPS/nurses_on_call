<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // User biasa
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+6281234567890',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'address' => 'Jl. Contoh No. 1',
            'emergency_contacts' => json_encode([
                'name' => 'Jane Doe',
                'phone' => '+6281234567891'
            ]),
            'verified' => true
        ]);

        User::create([
            'name' => 'Emma Smith',
            'email' => 'emma@example.com',
            'phone' => '+6281234567892',
            'password' => Hash::make('password456'),
            'role' => 'user',
            'address' => 'Jl. Contoh No. 2',
            'emergency_contacts' => json_encode([
                'name' => 'Michael Smith',
                'phone' => '+6281234567893'
            ]),
            'verified' => true
        ]);

        // Nurse
        User::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah@example.com',
            'phone' => '+6281234567894',
            'password' => Hash::make('nurse123'),
            'role' => 'nurse',
            'address' => 'Jl. Perawat No. 1',
            'verified' => true
        ]);

        User::create([
            'name' => 'Michael Brown',
            'email' => 'michael@example.com',
            'phone' => '+6281234567895',
            'password' => Hash::make('nurse456'),
            'role' => 'nurse',
            'address' => 'Jl. Perawat No. 2',
            'verified' => true
        ]);
    }
}
