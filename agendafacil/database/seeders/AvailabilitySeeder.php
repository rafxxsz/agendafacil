<?php

namespace Database\Seeders;

use App\Models\Availability;
use App\Models\Professional;
use Illuminate\Database\Seeder;

class AvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        // Segunda (1) a sexta (5)
        $weekdays = [1, 2, 3, 4, 5];

        $windows = [
            ['start_time' => '09:00', 'end_time' => '12:00'],
            ['start_time' => '13:00', 'end_time' => '18:00'],
        ];

        foreach (Professional::all() as $professional) {
            foreach ($weekdays as $weekday) {
                foreach ($windows as $window) {
                    Availability::updateOrCreate(
                        [
                            'professional_id' => $professional->id,
                            'weekday' => $weekday,
                            'start_time' => $window['start_time'],
                        ],
                        ['end_time' => $window['end_time']]
                    );
                }
            }
        }
    }
}
