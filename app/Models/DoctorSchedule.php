<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    /** @use HasFactory<\Database\Factories\DoctorScheduleFactory> */
    use HasFactory;
    protected $fillable = [
        'doctor_id',
        'start_time',
        'end_time',
        'shift_type',
        'notes',
        'status',
        'day'
    ];
    protected $casts = [
        'start_time' => 'string',
        'end_time' => 'string',
    ];
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'schedule_id');
    }
}
