<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of the transactions.
     */
    public function index()
    {
        // 1. Fetch all transactions, and eager-load the related Customer and User
        //    to prevent N+1 query problems in the view.
        $transactions = Transaction::with(['customer', 'user'])
                                    ->orderBy('DateCreated', 'desc') // Show newest first
                                    ->get();
        
        // 2. Pass the transactions to the new index view
        return view('transactions.index', [
            'transactions' => $transactions,
            'currentModule' => 'Transactions'
        ]);
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        // Fetch all customers and services to pass to the view.
        // We will use these for the customer search and service dropdowns.
        $customers = Customer::all();
        $services = Service::all();

        return view('transactions.create', [
            'customers' => $customers,
            'services' => $services,
            'currentModule' => 'Transactions'
        ]);
    }

    /**
     * Store a newly created transaction and its details in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming data
        $validatedData = $request->validate([
            'CustomerID' => 'required|integer|exists:customers,CustomerID',
            'TotalAmount' => 'required|numeric|min:0',
            'PaymentStatus' => 'required|string|in:Unpaid,Paid',
            'Notes' => 'nullable|string',
            'cart_items' => 'required|array|min:1',
            'cart_items.*.service_id' => 'required|integer|exists:services,ServiceID',
            'cart_items.*.quantity' => 'required|numeric|min:0.1',
            'cart_items.*.unit' => 'required|string',
            'cart_items.*.price_per_unit' => 'required|numeric',
            'cart_items.*.subtotal' => 'required|numeric',
        ]);

        // 2. Use a Database Transaction
        // This ensures that if one part fails (e.g., a detail fails),
        // the whole transaction is rolled back.
        try {
            DB::beginTransaction();

            // 3. Create the Master Transaction
            $transaction = Transaction::create([
                'CustomerID' => $validatedData['CustomerID'],
                'UserID' => Auth::id(), // Get the logged-in staff member's ID
                'DateCreated' => Carbon::today()->toDateString(),
                'TotalAmount' => $validatedData['TotalAmount'],
                'PaymentStatus' => $validatedData['PaymentStatus'],
                'Notes' => $validatedData['Notes'],
            ]);

            // 4. Loop through cart items and create TransactionDetails
            foreach ($validatedData['cart_items'] as $item) {
                TransactionDetail::create([
                    'TransactionID' => $transaction->TransactionID,
                    'ServiceID' => $item['service_id'],
                    'Quantity' => ($item['unit'] !== 'kg') ? $item['quantity'] : null,
                    'Weight' => ($item['unit'] === 'kg') ? $item['quantity'] : null,
                    'PricePerUnit' => $item['price_per_unit'],
                    'Subtotal' => $item['subtotal'],
                    'Status' => 'Pending', // Default status
                ]);
            }

            // 5. If everything is successful, commit to the database
            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Transaction #' . $transaction->TransactionID . ' created successfully!');

        } catch (\Exception $e) {
            // 6. If anything failed, roll back all database changes
            DB::rollBack();
            \Log::error("Transaction creation failed: " . $e->getMessage());
            
            return redirect()->back()->withInput()->with('error', 'Error creating transaction. Please try again.');
        }
    }

    /**
     * Display the specified transaction details.
     */
    public function show(Transaction $transaction)
    {
        // Eager-load all related data for the detail view
        $transaction->load(['customer', 'user', 'transactionDetails.service']);

        return view('transactions.show', [
            'transaction' => $transaction,
            'currentModule' => 'Transactions'
        ]);
    }

    /**
     * Show the form for editing the transaction (e.g., updating payment status).
     */
    public function edit(Transaction $transaction)
    {
        return view('transactions.edit', [
            'transaction' => $transaction,
            'currentModule' => 'Transactions'
        ]);
    }

    /**
     * Update the specified transaction in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $validatedData = $request->validate([
            'PaymentStatus' => 'required|string|in:Unpaid,Paid',
            'DatePaid' => 'nullable|date',
            'Notes' => 'nullable|string',
        ]);

        // Automatically set DatePaid if status is "Paid" and DatePaid is not set
        if ($validatedData['PaymentStatus'] == 'Paid' && empty($validatedData['DatePaid'])) {
            $validatedData['DatePaid'] = Carbon::today()->toDateString();
        }
        // Clear DatePaid if status is reset to "Unpaid"
        if ($validatedData['PaymentStatus'] == 'Unpaid') {
            $validatedData['DatePaid'] = null;
        }

        try {
            $transaction->update($validatedData);
            return redirect()->route('transactions.index')->with('success', 'Transaction #' . $transaction->TransactionID . ' updated.');
        } catch (\Exception $e) {
            \Log::error("Transaction update failed: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error updating transaction.');
        }
    }
}