<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\InventoryItem;   
use App\Models\InventoryUsage;  
use App\Models\ReorderNotice;   
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionController extends Controller
{

    public function index(Request $request)
    {
        
        $sortField = $request->query('sort', 'TransactionID'); 
        $sortDirection = $request->query('direction', 'desc'); 

        $allowedSorts = ['TransactionID', 'DateCreated', 'TotalAmount', 'PaymentStatus'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'TransactionID';
        }

        $transactions = Transaction::with(['customer', 'user', 'transactionDetails']) 
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
            
            // CHANGED: Redirect to Show page with 'newly_created' flag instead of Dashboard
            return redirect()->route('transactions.show', $transaction->TransactionID)
                             ->with('success', 'Transaction #' . $transaction->TransactionID . ' created successfully!')
                             ->with('newly_created', true);

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

        if ($validatedData['PaymentStatus'] == 'Paid' && empty($validatedData['DatePaid'])) {
            $validatedData['DatePaid'] = Carbon::today('Asia/Manila')->toDateString();
        }
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

    /**
     * Display the thermal print view for a transaction.
     */
    public function print(Transaction $transaction)
    {
        $transaction->load(['customer', 'transactionDetails.service']);
        return view('transactions.print', [
            'transaction' => $transaction
        ]);
    }

    public function markAsCompleted(Transaction $transaction)
    {
        try {
            DB::beginTransaction();

            $details = $transaction->transactionDetails;

            foreach ($details as $detail) {
                if ($detail->Status !== 'Completed') {
                    $detail->update(['Status' => 'Completed']);

                    $usageRules = InventoryUsage::where('ServiceID', $detail->ServiceID)->get();

                    if ($usageRules->isNotEmpty()) {
                        $orderQuantity = $detail->Weight ?? $detail->Quantity;

                        foreach ($usageRules as $rule) {
                            $inventoryItem = InventoryItem::find($rule->ItemID);

                            if ($inventoryItem) {
                                $totalToDeduct = $orderQuantity * $rule->QuantityUsed;
                                $discreteUnits = ['pcs', 'pc', 'item', 'pair', 'hanger', 'bag', 'bags'];
                                $itemUnit = strtolower($inventoryItem->Unit);

                                if (in_array($itemUnit, $discreteUnits)) {
                                    $totalToDeduct = ceil($totalToDeduct);
                                }

                                $inventoryItem->decrement('Quantity', $totalToDeduct);
                                
                                if ($inventoryItem->Quantity <= $inventoryItem->ReorderLevel) {
                                    $existingNotice = ReorderNotice::where('ItemID', $inventoryItem->ItemID)
                                                                   ->where('Status', 'Pending')
                                                                   ->exists();
                                    
                                    if (!$existingNotice) {
                                        ReorderNotice::create([
                                            'ItemID' => $inventoryItem->ItemID,
                                            'NoticeDate' => Carbon::today('Asia/Manila')->toDateString(),
                                            'Status' => 'Pending',
                                            'Notes' => 'Triggered by Transaction #' . $transaction->TransactionID
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if ($transaction->PaymentStatus !== 'Paid') {
                $transaction->update([
                    'PaymentStatus' => 'Paid',
                    'DatePaid' => Carbon::today('Asia/Manila')->toDateString()
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Order #' . $transaction->TransactionID . ' marked as collected!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Completion failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error completing order: ' . $e->getMessage());
        }
    }
}