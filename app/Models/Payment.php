<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'amount',
        'status',
        'method',
        'transaction_id',
        'payment_gateway',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'float',
        'paid_at' => 'datetime'
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Scope
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('method', $method);
    }
}
