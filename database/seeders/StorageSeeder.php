<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StorageLocation;
use App\Models\Equipment;
use Illuminate\Support\Facades\DB;

class StorageSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        StorageLocation::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $rows = 6;
        $cols = 10;

        for ($r = 1; $r <= $rows; $r++) {

            //On saute la rangee 3 pour creer une allée de passage
            if ($r == 3) continue; 

            for ($c = 1; $c <= $cols; $c++) {

                $location = StorageLocation::create([
                    'name' => "R" . $r . "-P" . $c, 
                    'grid_row_index' => $r,
                    'grid_column_index' => $c,
                ]);
            }
        }

        $this->command->info('Grille de stockage créée !');


        $equipments = Equipment::all();
        $locations = StorageLocation::all(); 

        if($equipments->count() > 0 && $locations->count() > 0) {
            foreach($equipments->take(10) as $eq) { 
                $randomLoc = $locations->random();

                $eq->storage_location_id = $randomLoc->id;
                $eq->save();

                $locations = $locations->except($randomLoc->id);
            }
            $this->command->info('10 équipements ont été placés aléatoirement.');
        }
    }
}