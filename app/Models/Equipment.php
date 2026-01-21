<?php
namespace App\Models;



use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class Equipment extends Model
{
    use HasFactory;

     protected $table = 'equipment';

    protected $fillable = [
        'name','brand','model','type','serial_number','state',
        'supplier','quantity','price','purchase_date','warranty','image','storage_location_id','ip_address'
    ];
   

    protected $casts = [
        'purchase_date' => 'date',
        'warranty'      => 'date',
    ];
        public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
    public function assignments()
{
    return $this->hasMany(Assignment::class);
}

    public function storageLocation()
    {
        return $this->belongsTo(StorageLocation::class);
    }

    public function scans()
{
    return $this->hasMany(\App\Models\Scan::class);
}

}

