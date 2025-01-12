<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'address',
        'emergency_contacts',
        'verified'
    ];

    protected $hidden = [
        'password',
        'emergency_contacts'
    ];

    protected $casts = [
        'emergency_contacts' => 'array',
        'verified' => 'boolean'
    ];

    // Relationships
    public function nurse()
    {
        return $this->hasOne(Nurse::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function emergencyCalls()
    {
        return $this->hasMany(EmergencyCall::class);
    }

    // Scope
    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
}
