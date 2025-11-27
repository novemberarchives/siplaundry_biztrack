<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'TransactionID';

    protected $fillable = [
        'CustomerID',
        'UserID',
        'DateCreated',
        'DatePaid',
        'TotalAmount',
        'PaymentStatus',
        'Notes',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID', 'CustomerID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'TransactionID', 'TransactionID');
    }

    // --- NEW: Aggregate Status Accessor ---
    public function getAggregateStatusAttribute()
    {
        // Get all statuses from details
        $statuses = $this->transactionDetails->pluck('Status')->toArray();

        if (empty($statuses)) {
            return 'Empty';
        }

        // Check conditions
        if (in_array('Washing', $statuses) || in_array('Folding', $statuses)) {
            return 'Processing';
        }

        // If all are Pending
        if (count(array_unique($statuses)) === 1 && end($statuses) === 'Pending') {
            return 'Pending';
        }

        // If all are Completed or Ready
        $finished = array_filter($statuses, fn($s) => $s === 'Completed' || $s === 'Ready for Pickup');
        if (count($finished) === count($statuses)) {
            return 'Ready';
        }

        // Default fallback
        return 'Processing';
    }
}