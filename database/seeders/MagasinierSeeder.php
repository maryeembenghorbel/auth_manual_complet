<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class MagasinierSeeder extends Seeder
{
    public function run()
    {
        // Créer ou récupérer le rôle Magasinier
        $role = Role::updateOrCreate(
            ['name' => 'Magasinier'],
            ['name' => 'Magasinier']
        );

        // Créer l'utilisateur Magasinier
        User::updateOrCreate(
            ['email' => 'magasinier@test.com'],
            [
                'name' => 'Magasinier Test',
                'password' => Hash::make('password123'),
                'role_id' => $role->id,
            ]
        );
    }
}
