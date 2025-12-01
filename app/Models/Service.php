<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Service extends Model
{
    use HasFactory, Auditable;

    /**
     * pk name
     */
    protected $primaryKey = 'ServiceID';

    protected $fillable = [
        'Name',
        'Description',
        'BasePrice',
        'Unit',
        'MinQuantity',
    ];

    /**
     * define relationship to TransactionDetail (1:M)
     */
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'ServiceID', 'ServiceID');
    }
    
    /**
     * define the relationship to InventoryUsage (M:M pivot)
     */
    public function inventoryUsages()
    {
        return $this->hasMany(InventoryUsage::class, 'ServiceID', 'ServiceID');
    }

    /**
     * get inventory items that this service uses
     */
    public function items()
    {
        return $this->belongsToMany(InventoryItem::class, 'inventory_usage', 'ServiceID', 'ItemID')
                    ->withPivot('QuantityUsed');
    }
}