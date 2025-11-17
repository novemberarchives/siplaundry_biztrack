<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * This table does not use created_at/updated_at
     */
    public $timestamps = false;

    /**
     * Define the primary key name
     */
    protected $primaryKey = 'TransactionID';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'CustomerID',
        'UserID',
        'DateCreated',
        'DatePaid',
        'TotalAmount',
        'PaymentStatus',
        'Notes',
    ];

    /**
     * Get the customer that owns the transaction.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID', 'CustomerID');
    }

    /**
     * Get the user (staff) that processed the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'id');
    }

    /**
     * Get the details (line items) for the transaction.
     */
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'TransactionID', 'TransactionID');
    }
}