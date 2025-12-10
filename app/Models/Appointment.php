<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'doctor_id',
        'schedule_id',
        'queue_number',
        'alert_before',
        'alert_sent',
        'appstatus',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    public function schedule()
    {
        return $this->belongsTo(DoctorSchedule::class, 'schedule_id');
    }
}
