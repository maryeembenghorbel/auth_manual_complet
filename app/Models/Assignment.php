<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = ['equipment_id','user_id','location','status'];

    public function equipment() {
        return $this->belongsTo(Equipment::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
