<?php

namespace Database\Seeders;

use App\Models\Professional;
use Illuminate\Database\Seeder;

class ProfessionalSeeder extends Seeder
{
    public function run(): void
    {
        $professionals = [
            ['name' => 'Dra. Ana Lima', 'email' => 'ana@agenda.test'],
            ['name' => 'Dr. Bruno Reis', 'email' => 'bruno@agenda.test'],
            ['name' => 'Carla Mendes', 'email' => 'carla@agenda.test'],
        ];

        foreach ($professionals as $professional) {
            Professional::updateOrCreate(
                ['email' => $professional['email']],
                $professional + ['active' => true]
            );
        }
    }
}
