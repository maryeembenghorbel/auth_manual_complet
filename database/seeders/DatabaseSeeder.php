<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([EquipmentSeeder::class]);
        $roleAdminId = DB::table('roles')->insertGetId([
            'name' => 'admin', 
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $roleConsultantId = DB::table('roles')->insertGetId([
            'name' => 'consultant', 
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        

        DB::table('users')->insert([
            'name' => 'Consultant Test',
            'email' => 'consultant@siam.com',
            'password' => Hash::make('consultant'),
            'role_id' => $roleConsultantId, 
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Admin Test',
            'email' => 'admin@siam.com',
            'password' => Hash::make('admin'),
            'role_id' => $roleAdminId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    
    }
}