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
        'status',
        'queue_number',
        'doctor_id',
    ];
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    public function queueHistories()
    {
        return $this->hasMany(QueueHistory::class, 'patient_id');
    }
}
