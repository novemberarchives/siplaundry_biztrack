<?php

namespace App\Http\Controllers;

use App\Models\ReorderNotice;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the operational dashboard.
     */
    public function index()
    {
        $localTimezone = 'Asia/Manila';

        // 1. Work Queue: Items that need processing (Pending, Washing, Folding)
        // Ordered by ID (Oldest first) so staff follow First-In-First-Out
        $activeJobs = TransactionDetail::whereIn('Status', ['Pending', 'Washing', 'Folding'])
            ->with(['service', 'transaction.customer'])
            ->orderBy('TransactionID', 'asc')
            ->limit(10)
            ->get();

        // 2. Ready for Pickup: Items processed but not yet "Completed" (Handed over)
        $readyJobs = TransactionDetail::where('Status', 'Ready for Pickup')
            ->with(['service', 'transaction.customer'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // 3. Quick Stats
        $lowStockCount = ReorderNotice::where('Status', 'Pending')->count();
        
        $todaysRevenue = Transaction::where('PaymentStatus', 'Paid')
                                    ->where('DatePaid', Carbon::today($localTimezone)->toDateString())
                                    ->sum('TotalAmount');

        return view('dashboard', [
            'currentModule' => 'Dashboard',
            'activeJobs' => $activeJobs,
            'readyJobs' => $readyJobs,
            'lowStockCount' => $lowStockCount,
            'todaysRevenue' => $todaysRevenue
        ]);
    }
}