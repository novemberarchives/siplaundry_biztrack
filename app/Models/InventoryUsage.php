<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryUsage extends Model
{
    use HasFactory;

    // Explicitly define the table name since "InventoryUsage" is not standard
    protected $table = 'inventory_usage';

    protected $primaryKey = 'UsageID';

    protected $fillable = [
        'ServiceID',
        'ItemID',
        'QuantityUsed',
    ];

    /**
     * Get the service associated with this usage rule.
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'ServiceID', 'ServiceID');
    }

    /**
     * Get the inventory item associated with this usage rule.
     */
    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'ItemID', 'ItemID');
    }
}