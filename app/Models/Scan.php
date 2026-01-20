<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    use HasFactory;

    // Champs qu'on peut remplir via create()
    protected $fillable = [
        'equipment_id',
        'scan_type',    // ajouter pour indiquer nmap
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

    // Relation vers l'équipement scanné
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    // Relation vers les vulnérabilités (plus tard)
    public function vulnerabilities()
    {
        return $this->hasMany(Vulnerability::class);
    }
}
