<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipment;
use Illuminate\Support\Facades\Schema; 

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        Equipment::truncate();

        Schema::enableForeignKeyConstraints();

        Equipment::insert([
            [
                'name'          => 'PC Bureau HP',
                'brand'         => 'HP',
                'model'         => 'EliteDesk 800 G5',
                'type'          => 'PC',
                'serial_number' => 'HP-PC-001',
                'state'         => 'En service',
                'supplier'      => 'HP Partner',
                'quantity'      => 10,
                'price'         => 8500,
                'purchase_date' => '2024-01-15',
                'warranty'      => '2027-01-15',
                'image'         => 'pchp.jpg',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Écran Dell 24"',
                'brand'         => 'Dell',
                'model'         => 'P2419H',
                'type'          => 'Écran',
                'serial_number' => 'DELL-SCREEN-01',
                'state'         => 'Neuf',
                'supplier'      => 'Dell Maroc',
                'quantity'      => 25,
                'price'         => 1800,
                'purchase_date' => '2024-03-10',
                'warranty'      => '2026-03-10',
                'image'         => 'dellmonitor.jpg',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Switch Cisco 24 ports',
                'brand'         => 'Cisco',
                'model'         => 'Catalyst 2960',
                'type'          => 'Switch',
                'serial_number' => 'CISC-SW-001',
                'state'         => 'En service',
                'supplier'      => 'Cisco Partner',
                'quantity'      => 3,
                'price'         => 12000,
                'purchase_date' => '2023-09-01',
                'warranty'      => '2026-09-01',
                'image'         => 'switch2960.jpg',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Routeur Fortinet',
                'brand'         => 'Fortinet',
                'model'         => 'FG-60F',
                'type'          => 'Routeur',
                'serial_number' => 'FORTI-RT-01',
                'state'         => 'En service',
                'supplier'      => 'Fortinet Partner',
                'quantity'      => 1,
                'price'         => 15000,
                'purchase_date' => '2024-05-20',
                'warranty'      => '2027-05-20',
                'image'         => 'fortinetrouter.jpg',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],

            [
                'name' => 'Switch Cisco 2960',
                'brand' => 'Cisco',
                'model' => '2960',
                'type' => 'switch',
                'serial_number' => 'SC12345678',
                'state' => 'fonctionnel',
                'supplier' => 'Tech Distributors',
                'quantity' => 155,
                'price' => 3000,
                'purchase_date' => '2023-05-10',
                'warranty' => '2024-05-10',
                'image' => 'switch2960.jpg',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}