<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageLocation extends Model
{
    protected $fillable = ['name', 'grid_row_index', 'grid_column_index'];

    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'storage_location_id');    }

    public function isOccupied()
    {
        return $this->equipment()->exists();
    }
}