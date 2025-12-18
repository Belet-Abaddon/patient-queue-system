<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    /** @use HasFactory<\Database\Factories\PatientFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'phone',
        'wait_status',
        'queue_number',
        'doctor_id',
        'status',
    ];
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    public function queueHistories()
    {
        return $this->hasMany(QueueHistory::class, 'patient_id');
    }
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    public function scopeWaiting($query)
    {
        return $query->where('wait_status', 'waiting');
    }
    public function scopeServing($query)
    {
        return $query->where('wait_status', 'serving');
    }
    public function scopeDone($query)
    {
        return $query->where('wait_status', 'done');
    }
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}
