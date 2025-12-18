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
        'degree',
        'specialist',
        'email',
        'phone',
        'status'
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
}
