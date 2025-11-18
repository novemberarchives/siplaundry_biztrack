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
     * Display the dashboard with fresh, up-to-date stats.
     */
    public function index()
    {
        // Define your local timezone
        $localTimezone = 'Asia/Manila';

        // Fetch data for dashboard cards
        $lowStockCount = ReorderNotice::where('Status', 'Pending')->count();
        
        $pendingOrderCount = TransactionDetail::whereIn('Status', ['Pending', 'Washing', 'Folding'])
                                            ->count();
        
        // --- FIXED: Use the local timezone ---
        $todaysRevenue = Transaction::where('PaymentStatus', 'Paid')
                                    ->where('DatePaid', Carbon::today($localTimezone)->toDateString())
                                    ->sum('TotalAmount');

        // Return the view and pass the fresh data to it
        return view('dashboard', [
            'currentModule' => 'Dashboard',
            'lowStockCount' => $lowStockCount,
            'pendingOrderCount' => $pendingOrderCount,
            'todaysRevenue' => $todaysRevenue
        ]);
    }
}