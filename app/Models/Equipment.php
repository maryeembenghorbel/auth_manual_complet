<?php
namespace App\Models;



use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- corriger l'import

class Equipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name','brand','model','type','serial_number','state',
        'supplier','quantity','price','purchase_date','warranty','image',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty'      => 'date',
    ];
}

