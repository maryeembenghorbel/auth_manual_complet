<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'scan_type',    
        'status',
        'started_at',
        'ended_at',
        'file_path',
        'result',
        'error_message',
    ];

     protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function vulnerabilities()
    {
        return $this->hasMany(Vulnerability::class);
    }
}