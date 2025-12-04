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
    public function create(Request $request) 
    {
        
        $inventoryItems = InventoryItem::orderBy('Name')->get();
        // get all PENDING notices
        $pendingNotices = ReorderNotice::where('Status', 'Pending')
                                       ->with('item') // Eager-load item info
                                       ->get();
        

        $selectedNoticeID = $request->query('notice_id', null);
        $selectedItemID = null;

        if ($selectedNoticeID) {
            $selectedNotice = $pendingNotices->firstWhere('NoticeID', $selectedNoticeID);
            if ($selectedNotice) {
                $selectedItemID = $selectedNotice->ItemID;
            }
        }
        
        return view('expenses.create', [
            'inventoryItems' => $inventoryItems,
            'pendingNotices' => $pendingNotices,
            'selectedNoticeID' => $selectedNoticeID, 
            'selectedItemID' => $selectedItemID,     
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
            'NoticeID' => 'nullable|integer|exists:reorder_notices,NoticeID',
        ]);

        try {
            DB::beginTransaction();

            $expense = Expense::create($validatedData);

            $item = InventoryItem::find($validatedData['ItemID']);

            if ($item) {
                $item->increment('Quantity', $validatedData['QuantityPurchased']);
                $item->refresh(); // Reload to get the updated Quantity for comparison
            } else {
                // if not valid, fail the transaction
                throw new \Exception('Inventory item not found.');
            }

            // --- 4. MODIFIED: AUTO-RESOLVE REORDER NOTICES ---
            // only resolve notices if the stock level is above reorder lvl
            if ($item->Quantity > $item->ReorderLevel) {
                
                if (!empty($validatedData['NoticeID'])) {
                    // specific notice was linked from the form
                    $notice = ReorderNotice::find($validatedData['NoticeID']);
                    if ($notice) {
                        $notice->update([
                            'Status' => 'Resolved',
                            'ResolvedDate' => $validatedData['Date']
                        ]);
                    }
                } else {
      
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
            }

            DB::commit();

            return redirect()->route('expenses.index')->with('success', 'Expense logged and stock updated for ' . $item->Name . '!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Expense logging failed: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error logging expense. Please try again.');
        }
    }
}