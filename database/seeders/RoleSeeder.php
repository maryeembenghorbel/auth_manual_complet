<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Rôles nécessaires à l'application
        $roles = [
            ['name' => 'Admin'],
            ['name' => 'Technicien'],
            ['name' => 'Magasinier'], // ✅ ajouté
            ['name' => 'Analyste'],
            ['name' => 'Viewer'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}

