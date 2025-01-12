<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nurse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specializations',
        'certifications',
        'current_location',
        'availability_status',
        'rating'
    ];

    protected $casts = [
        'certifications' => 'array',
        'rating' => 'float'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function emergencyCalls()
    {
        return $this->hasMany(EmergencyCall::class, 'assigned_nurse_id');
    }

    // Scope
    public function scopeAvailable($query)
    {
        return $query->where('availability_status', 'available');
    }

    public function scopeBySpecialization($query, $specialization)
    {
        return $query->where('specializations', 'like', "%{$specialization}%");
    }
}
