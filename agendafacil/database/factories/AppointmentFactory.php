<?php

namespace Database\Factories;

use App\Models\Professional;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        $start = Carbon::tomorrow()->setTime(10, 0);

        return [
            'user_id' => User::factory(),
            'service_id' => Service::factory(),
            'professional_id' => Professional::factory(),
            'start_at' => $start,
            'end_at' => $start->copy()->addMinutes(30),
            'status' => 'agendado',
        ];
    }

    public function cancelled(): static
    {
        return $this->state(fn () => ['status' => 'cancelado']);
    }

    public function past(): static
    {
        $start = Carbon::yesterday()->setTime(10, 0);

        return $this->state(fn () => [
            'start_at' => $start,
            'end_at' => $start->copy()->addMinutes(30),
        ]);
    }
}
