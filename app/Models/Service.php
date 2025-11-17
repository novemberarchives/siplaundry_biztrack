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
        'MinQuantity', // <-- ADDDED THIS for min quantity spec
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
     * Define the relationship to InventoryUsage (1:M).
     * This will be used later.
     */
    public function inventoryUsages()
    {
        // A Service consumes inventory items (e.g., detergent)
        return $this->hasMany(InventoryUsage::class, 'ServiceID', 'ServiceID');
    }
}