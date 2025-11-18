<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\InventoryItem;
use App\Models\ReorderNotice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the expenses.
     */
    public function index()
    {
        $expenses = Expense::with('item')->orderBy('Date', 'desc')->get();
        return view('expenses.index', [
            'expenses' => $expenses,
            'currentModule' => 'Expenses'
        ]);
    }

    /**
     * Show the form for creating a new expense.
     */
    public function create(Request $request) // <-- 1. ADD Request $request
    {
        // We pass the inventory items from here to be used in the dropdown
        $inventoryItems = InventoryItem::orderBy('Name')->get();
        // Get all PENDING notices
        $pendingNotices = ReorderNotice::where('Status', 'Pending')
                                        ->with('item') // Eager-load item info
                                        ->get();
        
        // --- 2. NEW: Check if a specific notice is being resolved ---
        $selectedNoticeID = $request->query('notice_id', null);
        $selectedItemID = null;

        if ($selectedNoticeID) {
            $selectedNotice = $pendingNotices->firstWhere('NoticeID', $selectedNoticeID);
            if ($selectedNotice) {
                $selectedItemID = $selectedNotice->ItemID;
            }
        }
        // --- END NEW ---
        
        return view('expenses.create', [
            'inventoryItems' => $inventoryItems,
            'pendingNotices' => $pendingNotices,
            'selectedNoticeID' => $selectedNoticeID, // <-- 3. PASS TO VIEW
            'selectedItemID' => $selectedItemID,     // <-- 4. PASS TO VIEW
            'currentModule' => 'Expenses'
        ]);
    }

    /**
     * Store a newly created expense in storage and update inventory.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ItemID' => 'required|integer|exists:inventory_items,ItemID',
            'Date' => 'required|date',
            'QuantityPurchased' => 'required|numeric|min:0.01',
            'TotalCost' => 'required|numeric|min:0',
            'Remarks' => 'nullable|string',
            'NoticeID' => 'nullable|integer|exists:reorder_notices,NoticeID', // <-- 1. ADD VALIDATION
        ]);

        try {
            DB::beginTransaction();

            // 1. Log the expense
            $expense = Expense::create($validatedData);

            // 2. Find the inventory item
            $item = InventoryItem::find($validatedData['ItemID']);

            // 3. Add the purchased quantity to the current stock
            // This is the auto-restock logic:
            if ($item) {
                $item->increment('Quantity', $validatedData['QuantityPurchased']);
            } else {
                // If item not found, fail the transaction
                throw new \Exception('Inventory item not found.');
            }

            // --- 4. MODIFIED: AUTO-RESOLVE REORDER NOTICES ---
            if (!empty($validatedData['NoticeID'])) {
                // Case 1: A specific notice was linked from the form
                $notice = ReorderNotice::find($validatedData['NoticeID']);
                if ($notice) {
                    $notice->update([
                        'Status' => 'Resolved',
                        'ResolvedDate' => $validatedData['Date']
                    ]);
                }
            } else {
                // Case 2 (Fallback): No notice was linked, find any pending notices for this item
                $notices = ReorderNotice::where('ItemID', $item->ItemID)
                                        ->where('Status', 'Pending')
                                        ->get();

                if ($notices->isNotEmpty()) {
                    foreach ($notices as $notice) {
                        $notice->update([
                            'Status' => 'Resolved',
                            'ResolvedDate' => $validatedData['Date']
                        ]);
                    }
                }
            }
            // --- END OF MODIFIED LOGIC ---

            DB::commit();

            return redirect()->route('expenses.index')->with('success', 'Expense logged and stock updated for ' . $item->Name . '!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Expense logging failed: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error logging expense. Please try again.');
        }
    }
}