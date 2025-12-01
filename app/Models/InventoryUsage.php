<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class InventoryUsage extends Model
{
    use HasFactory, Auditable;

    // Explicitly define the table name since "InventoryUsage" is not standard
    protected $table = 'inventory_usage';

    protected $primaryKey = 'UsageID';

    protected $fillable = [
        'ServiceID',
        'ItemID',
        'QuantityUsed',
    ];

    // --- Relationships ---

    public function service()
    {
        return $this->belongsTo(Service::class, 'ServiceID', 'ServiceID');
    }

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'ItemID', 'ItemID');
    }
}