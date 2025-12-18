<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueueHistory extends Model
{
    /** @use HasFactory<\Database\Factories\QueueHistoryFactory> */
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'call_time',
        'status',
    ];
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    public function scopeToday($query)
    {
        return $query->whereDate('call_time', today());
    }
}
