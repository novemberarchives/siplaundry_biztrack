<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable; 

class ReorderNotice extends Model
{
    use HasFactory, Auditable;

    protected $primaryKey = 'NoticeID';

    protected $fillable = [
        'ItemID',
        'NoticeDate',
        'Status',
        'ResolvedDate',
        'Notes',
    ];

    // --- Relationships ---

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'ItemID', 'ItemID');
    }
}