<?php

namespace Database\Factories;
use App\Models\Patient;
use App\Models\Doctor;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QueueHistory>
 */
class QueueHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['served', 'done'];
        return [
            'patient_id' => Patient::factory(),
            'doctor_id' => Doctor::factory(),
            'call_time' => $this->faker->dateTimeThisMonth(),
            'status' => $this->faker->randomElement($statuses),
        ];
    }
}
