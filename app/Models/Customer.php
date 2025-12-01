<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Customer extends Model
{
    use HasFactory, Auditable;

    /**
     * Disable automatic timestamps since we are using DateCreated only
     */
    public $timestamps = false;
    
    /**
     * Define the primary key name to match the database schema
     */
    protected $primaryKey = 'CustomerID';

    /**
     * The attributes that are mass assignable
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'Name',
        'ContactNumber',
        'Address',
        'Email',
        'DateCreated',
    ];

    /**
     * Relationship to the Transaction model (1:M)
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'CustomerID', 'CustomerID');
    }
}