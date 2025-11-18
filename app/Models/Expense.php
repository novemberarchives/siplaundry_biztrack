<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $primaryKey = 'ExpenseID';

    protected $fillable = [
        'ItemID',
        'Date',
        'QuantityPurchased',
        'TotalCost',
        'Remarks',
    ];

    /**
     * Get the inventory item that was purchased.
     */
    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'ItemID', 'ItemID');
    }
}