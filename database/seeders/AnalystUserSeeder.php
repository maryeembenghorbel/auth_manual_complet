<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AnalystUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'analyst@example.com'], // email unique
            [
                'name' => 'Analyst Test',
                'password' => Hash::make('Analyst1234'), // mot de passe sécurisé
                'role_id' => 2, // Assure-toi que le rôle Analyst existe en id=2
            ]
        );
    }
}
