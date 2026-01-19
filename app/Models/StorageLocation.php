<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageLocation extends Model
{
    protected $fillable = ['name', 'grid_row_index', 'grid_column_index'];

    public function equipment()
    {
        return $this->hasOne(Equipment::class);
    }

    public function isOccupied()
    {
        return $this->equipment !== null;
    }
}