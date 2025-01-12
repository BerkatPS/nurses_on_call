<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nurse_id',
        'service_id',
        'status',
        'location',
        'start_time',
        'end_time',
        'total_amount',
        'emergency_level',
        'notes'
    ];

    protected $dates = [
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'total_amount' => 'float',
        'emergency_level' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function nurse()
    {
        return $this->belongsTo(Nurse::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Scope
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('created_at', now()->month);
    }
}
