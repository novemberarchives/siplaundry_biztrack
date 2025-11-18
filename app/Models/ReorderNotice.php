<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReorderNotice extends Model
{
    use HasFactory;

    protected $primaryKey = 'NoticeID';

    protected $fillable = [
        'ItemID',
        'NoticeDate',
        'Status',
        'ResolvedDate',
        'Notes',
    ];

    /**
     * Get the inventory item that needs reordering.
     */
    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'ItemID', 'ItemID');
    }
}