<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmergencyCall extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assigned_nurse_id',
        'location',
        'description',
        'emergency_type',
        'status',
        'latitude',
        'longitude',
        'response_time'
    ];

    protected $dates = [
        'response_time'
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedNurse()
    {
        return $this->belongsTo(Nurse::class, 'assigned_nurse_id');
    }

    // Scope
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByEmergencyType($query, $type)
    {
        return $query->where('emergency_type', $type);
    }
}
