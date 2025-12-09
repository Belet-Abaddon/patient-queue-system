<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Doctor;
use App\Models\DoctorSchedule;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'doctor_id' => Doctor::factory(),
            'schedule_id' => DoctorSchedule::factory(),
            'queue_number' => $this->faker->numberBetween(1, 50),
            'alert_before' => $this->faker->numberBetween(3, 5),
            'alert_sent' => 0, // default 0 = not sent
            'status' => 'pending', // default status
        ];
    }
}
