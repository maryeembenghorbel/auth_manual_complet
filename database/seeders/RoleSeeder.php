<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Liste des rôles prédéfinis pour ton application SIAM
        $roles = [
            ['name' => 'Admin'],
            ['name' => 'Technicien'],
            ['name' => 'Magasinier'],
            ['name' => 'Analyste'],
            ['name' => 'Viewer'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
