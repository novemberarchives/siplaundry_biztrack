<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Expense; // <-- Import Expense Model
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display the Revenue Calendar.
     */
    public function index(Request $request)
    {
        // 1. Determine the month/year to view
        $localTimezone = 'Asia/Manila';
        
        $date = $request->has('date') 
                ? Carbon::parse($request->query('date')) 
                : Carbon::now($localTimezone);

        // 2. Get start and end of the month
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        // 3. Fetch Revenue Data grouped by Day
        // Includes COUNT of transactions and SUM of revenue
        $dailyRevenues = Transaction::where('PaymentStatus', 'Paid')
            ->whereBetween('DatePaid', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
            ->selectRaw('DATE(DatePaid) as date, SUM(TotalAmount) as total, COUNT(*) as count')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        // 4. Fetch Expenses Data grouped by Day
        $dailyExpenses = Expense::whereBetween('Date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
            ->selectRaw('Date as date, SUM(TotalCost) as total')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        // 5. Calculate Monthly Totals
        $monthlyRevenue = $dailyRevenues->sum('total');
        $monthlyExpenses = $dailyExpenses->sum('total');
        $monthlyProfit = $monthlyRevenue - $monthlyExpenses;
        $totalTransactions = $dailyRevenues->sum('count');

        // 6. Prepare Calendar Grid Logic
        $startDayOfWeek = $startOfMonth->dayOfWeek; 
        $daysInMonth = $startOfMonth->daysInMonth;

        return view('analytics.index', [
            'currentDate' => $date,
            'dailyRevenues' => $dailyRevenues,
            'dailyExpenses' => $dailyExpenses,
            'monthlyRevenue' => $monthlyRevenue,
            'monthlyExpenses' => $monthlyExpenses,
            'monthlyProfit' => $monthlyProfit,
            'totalTransactions' => $totalTransactions,
            'startDayOfWeek' => $startDayOfWeek,
            'daysInMonth' => $daysInMonth,
            'currentModule' => 'Analytics'
        ]);
    }

    /**
     * Display detailed analytics for a specific day.
     */
    public function show($date)
    {
        $date = Carbon::parse($date);

        // Fetch detailed lists for that day
        $transactions = Transaction::where('PaymentStatus', 'Paid')
            ->whereDate('DatePaid', $date->format('Y-m-d'))
            ->with('customer') // Eager load customer
            ->get();

        $expenses = Expense::whereDate('Date', $date->format('Y-m-d'))
            ->with('item') // Eager load inventory item
            ->get();

        // Calculate totals for the day
        $totalRevenue = $transactions->sum('TotalAmount');
        $totalExpenses = $expenses->sum('TotalCost');
        $netProfit = $totalRevenue - $totalExpenses;

        return view('analytics.show', [
            'date' => $date,
            'transactions' => $transactions,
            'expenses' => $expenses,
            'totalRevenue' => $totalRevenue,
            'totalExpenses' => $totalExpenses,
            'netProfit' => $netProfit,
            'currentModule' => 'Analytics'
        ]);
    }
}