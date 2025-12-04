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

    public function index(Request $request)
    {
        
        $sortField = $request->query('sort', 'TransactionID'); // ID default
        $sortDirection = $request->query('direction', 'desc'); // desc default

        // allowed sort arrays, anti sql injection
        $allowedSorts = ['TransactionID', 'DateCreated', 'TotalAmount', 'PaymentStatus'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'TransactionID';
        }

        $transactions = Transaction::with(['customer', 'user', 'transactionDetails']) // Eager load details for status
                                    ->orderBy($sortField, $sortDirection)
                                    ->get();
        
        return view('transactions.index', [
            'transactions' => $transactions,
            'currentModule' => 'Transactions',
            'currentSort' => $sortField,
            'currentDirection' => $sortDirection
        ]);
    }

    
    public function create()
    {
        $customers = Customer::all();
        $services = Service::all();

        return view('transactions.create', [
            'customers' => $customers,
            'services' => $services,
            'currentModule' => 'Transactions'
        ]);
    }

    public function store(Request $request)
    {
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

        try {
            DB::beginTransaction();

            $transaction = Transaction::create([
                'CustomerID' => $validatedData['CustomerID'],
                'UserID' => Auth::id(),
                'DateCreated' => Carbon::today('Asia/Manila')->toDateString(),
                'TotalAmount' => $validatedData['TotalAmount'],
                'PaymentStatus' => $validatedData['PaymentStatus'],
                'Notes' => $validatedData['Notes'],
                // Set DatePaid if initially Paid
                'DatePaid' => ($validatedData['PaymentStatus'] === 'Paid') ? Carbon::today('Asia/Manila')->toDateString() : null,
            ]);

            foreach ($validatedData['cart_items'] as $item) {
                TransactionDetail::create([
                    'TransactionID' => $transaction->TransactionID,
                    'ServiceID' => $item['service_id'],
                    'Quantity' => ($item['unit'] !== 'kg') ? $item['quantity'] : null,
                    'Weight' => ($item['unit'] === 'kg') ? $item['quantity'] : null,
                    'PricePerUnit' => $item['price_per_unit'],
                    'Subtotal' => $item['subtotal'],
                    'Status' => 'Pending',
                ]);
            }

            DB::commit();
            return redirect()->route('dashboard')->with('success', 'Transaction #' . $transaction->TransactionID . ' created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Transaction creation failed: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error creating transaction. Please try again.');
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['customer', 'user', 'transactionDetails.service']);
        return view('transactions.show', [
            'transaction' => $transaction,
            'currentModule' => 'Transactions'
        ]);
    }

    public function edit(Transaction $transaction)
    {
        return view('transactions.edit', [
            'transaction' => $transaction,
            'currentModule' => 'Transactions'
        ]);
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validatedData = $request->validate([
            'PaymentStatus' => 'required|string|in:Unpaid,Paid',
            'DatePaid' => 'nullable|date',
            'Notes' => 'nullable|string',
        ]);

        // default: current timestamp
        if ($validatedData['PaymentStatus'] == 'Paid' && empty($validatedData['DatePaid'])) {
            $validatedData['DatePaid'] = Carbon::today('Asia/Manila')->toDateString();
        }
        // clear date if unpaid
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

    public function markAsCompleted(Transaction $transaction)
    {
        try {
            DB::beginTransaction();

            // mark all individual service items as Completed
            $transaction->transactionDetails()->update(['Status' => 'Completed']);

            // ensure Payment is Paid upon collection
            if ($transaction->PaymentStatus !== 'Paid') {
                $transaction->update([
                    'PaymentStatus' => 'Paid',
                    'DatePaid' => Carbon::today('Asia/Manila')->toDateString()
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Order #' . $transaction->TransactionID . ' marked as collected & completed!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Completion failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error completing order.');
        }
    }
}