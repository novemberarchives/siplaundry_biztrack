<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Transaction extends Model
{
    use HasFactory, Auditable;

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

    // Staff member who processed the order
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'TransactionID', 'TransactionID');
    }

    public function getAggregateStatusAttribute()
    {
        $statuses = $this->transactionDetails->pluck('Status')->toArray();

        if (empty($statuses)) {
            return 'Empty';
        }

        // If any item is in progress, the whole order is processing
        if (in_array('Washing', $statuses) || in_array('Folding', $statuses)) {
            return 'Processing';
        }

        // Pending state check
        if (count(array_unique($statuses)) === 1 && end($statuses) === 'Pending') {
            return 'Pending';
        }

        // Check if everything is finished
        $finished = array_filter($statuses, fn($s) => $s === 'Completed' || $s === 'Ready for Pickup');
        if (count($finished) === count($statuses)) {
            return 'Ready';
        }

        return 'Processing';
    }
}