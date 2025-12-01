<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    /**
     * pk name
     */
    protected $primaryKey = 'TransactionDetailID';

    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'TransactionID',
        'ServiceID',
        'Quantity',
        'Weight',
        'PricePerUnit',
        'Subtotal',
        'Status',
        'StartDate',
        'EndDate',
    ];

    /**
     * Get the master transaction that this detail belongs to.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'TransactionID', 'TransactionID');
    }

    /**
     * Get the service associated with this detail.
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'ServiceID', 'ServiceID');
    }
}