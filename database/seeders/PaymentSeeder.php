<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        Payment::create([
            'booking_id' => 1,
            'amount' => 750000,
            'status' => 'completed',
            'method' => 'credit_card',
            'transaction_id' => 'TX123456789',
        ]);

        Payment::create([
            'booking_id' => 2,
            'amount' => 500000,
            'status' => 'pending',
            'method' => 'bank_transfer',
            'transaction_id' => null,
        ]);
    }
}
