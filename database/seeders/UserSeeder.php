<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first(); 
        $consultantRole = Role::where('name', 'Consultant')->first();

        if (!$adminRole) {
            $this->command->error("Erreur : Impossible de trouver le rôle 'Admin' dans la base de données.");
            return;
        }

        DB::table('users')->insert([
            [
                'name' => 'Consultant Test',
                'email' => 'consultant@siam.com',
                'password' => Hash::make('consultant'),
                'role_id' => $consultantRole->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin Test',
                'email' => 'admin@siam.com',
                'password' => Hash::make('admin'),
                'role_id' => $adminRole->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}