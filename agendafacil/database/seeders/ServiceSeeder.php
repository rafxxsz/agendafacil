<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Consulta de Avaliação', 'description' => 'Primeira consulta de avaliação geral.', 'duration_minutes' => 30, 'price' => 120.00],
            ['name' => 'Sessão de Fisioterapia', 'description' => 'Sessão individual de fisioterapia.', 'duration_minutes' => 60, 'price' => 180.00],
            ['name' => 'Limpeza Facial', 'description' => 'Limpeza de pele profunda.', 'duration_minutes' => 45, 'price' => 150.00],
            ['name' => 'Corte de Cabelo', 'description' => 'Corte e finalização.', 'duration_minutes' => 30, 'price' => 70.00],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['name' => $service['name']],
                $service + ['active' => true]
            );
        }
    }
}
