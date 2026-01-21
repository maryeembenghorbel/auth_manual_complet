<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipment;
use App\Models\StorageLocation; 
use Illuminate\Support\Facades\Schema;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Equipment::truncate();
        Schema::enableForeignKeyConstraints();

        $locations = StorageLocation::all();

        if ($locations->isEmpty()) {
            $this->command->warn("Attention : Aucun emplacement trouvé. L'équipement sera créé sans emplacement.");
        }

        $equipmentList = [
            [
                'name'          => 'PC Bureau HP',
                'brand'         => 'HP',
                'model'         => 'EliteDesk 800 G5',
                'ip_address'    => '192.168.10.10',
                'type'          => 'PC',
                'serial_number' => 'HP-PC-001',
                'state'         => 'En service',
                'supplier'      => 'HP Partner',
                'quantity'      => 10,
                'price'         => 8500,
                'purchase_date' => '2024-01-15',
                'warranty'      => '2027-01-15',
                'image'         => 'pchp.jpg',
            ],
            [
                'name'          => 'Écran Dell 24"',
                'brand'         => 'Dell',
                'model'         => 'P2419H',
                'ip_address'    => null,
                'type'          => 'Écran',
                'serial_number' => 'DELL-SCREEN-01',
                'state'         => 'Neuf',
                'supplier'      => 'Dell Maroc',
                'quantity'      => 25,
                'price'         => 1800,
                'purchase_date' => '2024-03-10',
                'warranty'      => '2026-03-10',
                'image'         => 'dellmonitor.jpg',
            ],
            [
                'name'          => 'Switch Cisco 24 ports',
                'brand'         => 'Cisco',
                'model'         => 'Catalyst 2960',
                'ip_address'    => null,
                'type'          => 'Switch',
                'serial_number' => 'CISC-SW-001',
                'state'         => 'En service',
                'supplier'      => 'Cisco Partner',
                'quantity'      => 3,
                'price'         => 12000,
                'purchase_date' => '2023-09-01',
                'warranty'      => '2026-09-01',
                'image'         => 'switch2960.jpg',
            ],
            [
                'name'          => 'Routeur Fortinet',
                'brand'         => 'Fortinet',
                'model'         => 'FG-60F',
                'ip_address'    => null,
                'type'          => 'Routeur',
                'serial_number' => 'FORTI-RT-01',
                'state'         => 'En service',
                'supplier'      => 'Fortinet Partner',
                'quantity'      => 1,
                'price'         => 15000,
                'purchase_date' => '2024-05-20',
                'warranty'      => '2027-05-20',
                'image'         => 'fortinetrouter.jpg',
            ],
            [
                'name'          => 'Switch Cisco 2960 (Vieux)',
                'brand'         => 'Cisco',
                'model'         => '2960',
                'ip_address'    => null,
                'type'          => 'switch',
                'serial_number' => 'SC12345678',
                'state'         => 'fonctionnel',
                'supplier'      => 'Tech Distributors',
                'quantity'      => 155,
                'price'         => 3000,
                'purchase_date' => '2023-05-10',
                'warranty'      => '2024-05-10',
                'image'         => 'switch2960.jpg',
            ],
            [
                'name'          => 'Laptop Dell Latitude 5420',
                'brand'         => 'Dell',
                'model'         => 'Latitude 5420',
                'ip_address'    => '192.168.10.14',
                'type'          => 'Laptop',
                'serial_number' => 'dell-lat5420-002',
                'state'         => 'En service',
                'supplier'      => 'Microchoix',
                'quantity'      => 78,
                'price'         => 3200,
                'purchase_date' => '2024-02-25',
                'warranty'      => '2026-02-25',
                'image'         => 'delllatitude.jpg',
            ]
        ];

        foreach ($equipmentList as $item) {
                if ($locations->isNotEmpty()) {
                    $item['storage_location_id'] = $locations->random()->id;
                }

            Equipment::create($item);
        }
    }
}