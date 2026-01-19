<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,       
            StorageSeeder::class,    
            
       
            UserSeeder::class,       
            EquipmentSeeder::class,  
        ]);
    }
}