<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    /**
     * Define the primary key name to match the database schema.
     */
    protected $primaryKey = 'ServiceID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'Name',
        'Description',
        'BasePrice',
        'Unit',
        'MinQuantity', // <-- ADD THIS
    ];

    /**
     * Define the relationship to TransactionDetail (1:M).
     * This will be used later.
     */
    public function transactionDetails()
    {
        // A Service can be part of many TransactionDetails
        return $this->hasMany(TransactionDetail::class, 'ServiceID', 'ServiceID');
    }
    
    /**
     * Define the relationship to InventoryUsage (M:M pivot).
     */
    public function inventoryUsages()
    {
        // A Service "uses" many InventoryItems
        return $this->hasMany(InventoryUsage::class, 'ServiceID', 'ServiceID');
    }

    /**
     * Get the inventory items that this service uses.
     */
    public function items()
    {
        return $this->belongsToMany(InventoryItem::class, 'inventory_usage', 'ServiceID', 'ItemID')
                    ->withPivot('QuantityUsed');
    }
}