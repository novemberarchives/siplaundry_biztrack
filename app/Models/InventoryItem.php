<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'ItemID';

    protected $fillable = [
        'Name',
        'Category',
        'Unit', // <-- ADD THIS
        'UnitPrice',
        'Quantity',
        'ReorderLevel',
    ];

    // --- Relationships ---

    /**
     * Define the relationship to InventoryUsage (M:M pivot).
     */
    public function inventoryUsages()
    {
        // An Item is "used by" many Services
        return $this->hasMany(InventoryUsage::class, 'ItemID', 'ItemID');
    }

    /**
     * Get the services that use this inventory item.
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'inventory_usage', 'ItemID', 'ServiceID')
                    ->withPivot('QuantityUsed');
    }

    /**
     * Get the expense records for this item.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'ItemID', 'ItemID');
    }

    /**
     * Get the reorder notices for this item.
     */
    public function reorderNotices()
    {
        return $this->hasMany(ReorderNotice::class, 'ItemID', 'ItemID');
    }
}