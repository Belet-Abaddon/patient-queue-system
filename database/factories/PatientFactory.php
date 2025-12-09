<?php

namespace Database\Factories;
use app\Models\Doctor;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['waiting', 'serving', 'done'];
        return [
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'status' => fake()->randomElement($statuses),
            'queue_number' => $this->faker->numberBetween(1, 50),
            'doctor_id' => Doctor::factory(),
        ];
    }
}
