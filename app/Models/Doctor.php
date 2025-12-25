<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    /** @use HasFactory<\Database\Factories\DoctorFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'specialization',
        'email',
        'phone',
        'license',
        'room',
        'bio',
        'status',
        'degree'
    ];
    protected $casts = [
        'status' => 'string',
    ];
    public function patients()
    {
        return $this->hasMany(Patient::class, 'doctor_id');
    }
    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class, 'doctor_id');
    }
    public function queueHistories()
    {
        return $this->hasMany(QueueHistory::class, 'doctor_id');
    }
    public function getAvatarAttribute()
    {
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=3b82f6&color=fff";
    }
}
