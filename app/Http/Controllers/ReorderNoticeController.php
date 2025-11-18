<?php

namespace App\Http\Controllers;

use App\Models\ReorderNotice;
use Illuminate\Http\Request;
use Carbon\Carbon; // <-- 1. ADD THIS

class ReorderNoticeController extends Controller
{
    /**
     * Display a listing of the reorder notices.
     */
    public function index()
    {
        // Fetch notices, newest first, and eager-load the item info
        $notices = ReorderNotice::with('item')
                                ->orderBy('Status', 'asc') // Show Pending first
                                ->orderBy('NoticeDate', 'desc')
                                ->get();
        
        return view('reorder-notices.index', [
            'notices' => $notices,
            'currentModule' => 'Reorder Notices'
        ]);
    }

}