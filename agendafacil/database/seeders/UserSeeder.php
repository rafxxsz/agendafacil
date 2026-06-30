<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@agenda.test'],
            [
                'name' => 'Administrador',
                'phone' => '(11) 90000-0001',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'cliente@agenda.test'],
            [
                'name' => 'Cliente Teste',
                'phone' => '(11) 90000-0002',
                'password' => Hash::make('password'),
                'role' => 'cliente',
            ]
        );

        User::updateOrCreate(
            ['email' => 'maria@agenda.test'],
            [
                'name' => 'Maria Souza',
                'phone' => '(11) 90000-0003',
                'password' => Hash::make('password'),
                'role' => 'cliente',
            ]
        );
    }
}
