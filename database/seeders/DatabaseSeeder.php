<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\QueueHistory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1 staff user
        $staff = User::factory()->create([
            'name' => 'Staff User',
            'email' => 'staff@example.com',
            'role' => 1,
        ]);

        // 1 normal user
        $normalUser = User::factory()->create([
            'name' => 'Normal User',
            'email' => 'user@example.com',
            'role' => 2,
        ]);

        // 3 doctors
        $doctors = Doctor::factory()->count(3)->create();

        // 2 schedules per doctor
        foreach ($doctors as $doctor) {
            DoctorSchedule::factory()->count(2)->create([
                'doctor_id' => $doctor->id,
            ]);
        }

        // 5 patients linked to doctors
        $patients = Patient::factory()->count(5)->create([
            'doctor_id' => $doctors->random()->id,
        ]);

        // 5 appointments linked to normal user and doctors
        foreach ($doctors as $doctor) {
            Appointment::factory()->create([
                'user_id' => $normalUser->id,
                'doctor_id' => $doctor->id,
                'schedule_id' => DoctorSchedule::where('doctor_id', $doctor->id)->inRandomOrder()->first()->id,
            ]);
        }

        // 5 queue histories linked to patients and doctors
        foreach ($patients as $patient) {
            QueueHistory::factory()->create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctors->random()->id,
            ]);
        }
    }
}
