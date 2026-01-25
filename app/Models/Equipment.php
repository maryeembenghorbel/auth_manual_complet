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
        'supplier','quantity','price','purchase_date','warranty','image','storage_location_id','ip_address','status',
        'risk_level',
        'critical_cve_count',
        'critical_score_cumul',
        'last_critical_scan_at',
    ];
   

    protected $casts = [
        'purchase_date' => 'date',
        'warranty'      => 'date',
        'last_critical_scan_at' => 'datetime',  
        'critical_score_cumul' => 'decimal:2',
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

