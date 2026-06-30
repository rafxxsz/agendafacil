<?php

namespace Database\Factories;

use App\Models\Professional;
use Illuminate\Database\Eloquent\Factories\Factory;

class AvailabilityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'professional_id' => Professional::factory(),
            'weekday' => fake()->numberBetween(1, 5),
            'start_time' => '09:00',
            'end_time' => '18:00',
        ];
    }
}
