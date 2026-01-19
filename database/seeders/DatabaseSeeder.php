<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder; 
use Database\Seeders\RoleSeeder;
use Database\Seeders\StorageSeeder;
use Database\Seeders\EquipmentSeeder;

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