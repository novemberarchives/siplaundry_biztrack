<?php

namespace App\Http\Controllers;

use App\Models\ReorderNotice;
use Illuminate\Http\Request;

class ReorderNoticeController extends Controller
{
    /**
     * Display a listing of the reorder notices.
     */
    public function index()
    {
        // Fetch all notices with their associated items
        // Order by: Pending items first, then by newest date
        $notices = ReorderNotice::with('item')
                                ->orderByRaw("CASE WHEN Status = 'Pending' THEN 1 ELSE 2 END")
                                ->orderBy('NoticeDate', 'desc')
                                ->get();

        return view('reorder-notices.index', [
            'notices' => $notices,
            'currentModule' => 'Reorder Notices'
        ]);
    }
}