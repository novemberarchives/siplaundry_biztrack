<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable; // audtlog trait

class InventoryItem extends Model
{
    use HasFactory, Auditable; // use trait

    protected $primaryKey = 'ItemID';

    protected $fillable = [
        'Name',
        'Category',
        'Unit',
        'UnitPrice',
        'Quantity',
        'ReorderLevel',
    ];

    // --- Relationships ---

    /**
     * define relationship to InventoryUsage (M:M pivot)
     */
    public function inventoryUsages()
    {
        // An Item is "used by" many Services
        return $this->hasMany(InventoryUsage::class, 'ItemID', 'ItemID');
    }

    /**
     * get services that use this inventory item
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'inventory_usage', 'ItemID', 'ServiceID')
                    ->withPivot('QuantityUsed');
    }

    /**
     * get expense records for this item
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'ItemID', 'ItemID');
    }

    /**
     * get reorder notices for this item
     */
    public function reorderNotices()
    {
        return $this->hasMany(ReorderNotice::class, 'ItemID', 'ItemID');
    }
}