<?php

namespace App\Http\Controllers;

use App\Models\TransactionDetail;
use App\Models\InventoryItem;
use App\Models\InventoryUsage;
use App\Models\ReorderNotice;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TransactionDetailController extends Controller
{
    /**
     * Update the status of a specific transaction detail
     */
    public function updateStatus(Request $request, TransactionDetail $detail)
    {
        // the allowed statuses
        $allowedStatuses = [
            'Pending',
            'Washing',
            'Folding',
            'Ready for Pickup',
            'Completed',
        ];

        // 1. Validate the incoming request
        $validated = $request->validate([
            'Status' => [
                'required',
                'string',
                Rule::in($allowedStatuses)
            ],
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $detail->Status;
            $newStatus = $validated['Status'];

            // Only block 'Completed' status if the transaction is Unpaid.
            // 'Ready for Pickup', 'Washing', etc. allow the process to continue.
            if ($newStatus === 'Completed') {
                // Ensure we load the transaction relationship
                $detail->load('transaction');
                
                if ($detail->transaction && $detail->transaction->PaymentStatus !== 'Paid') {
                    return redirect()->back()->with('error', 'Cannot mark as Completed. The transaction must be PAID first.');
                }
            }

            // Update the status
            $detail->Status = $newStatus;
            $detail->save();

            // --- LOGIC: Auto-Deduct Inventory ---
            //  deduct stock when status is set to 'Completed' AND it was not 'Completed'
            if ($newStatus === 'Completed' && $oldStatus !== 'Completed') {
                
                $usageRules = InventoryUsage::where('ServiceID', $detail->ServiceID)->get();

                if ($usageRules->isNotEmpty()) {
                    // Get the order quantity
                    $orderQuantity = $detail->Weight ?? $detail->Quantity;

                    foreach ($usageRules as $rule) {
                        $inventoryItem = InventoryItem::find($rule->ItemID);

                        if ($inventoryItem) {
                            // Calculate total to deduct
                            $totalToDeduct = $orderQuantity * $rule->QuantityUsed;

                            // Discrete Unit Handling
                            $discreteUnits = ['pcs', 'pc', 'item', 'pair', 'hanger', 'bag', 'bags'];
                            $itemUnit = strtolower($inventoryItem->Unit);

                            if (in_array($itemUnit, $discreteUnits)) {
                                $totalToDeduct = ceil($totalToDeduct);
                            }

                            // Deduct from stock
                            $inventoryItem->decrement('Quantity', $totalToDeduct);
                            
                            // Check Reorder Level
                            if ($inventoryItem->Quantity <= $inventoryItem->ReorderLevel) {
                                $this->createReorderNotice($inventoryItem->ItemID, $detail->TransactionID);
                            }
                        }
                    }
                }
            }

            // --- AUDIT LOG ---
            AuditLog::create([
                'user_id' => Auth::id(),
                'event' => 'updated',
                'auditable_type' => TransactionDetail::class,
                'auditable_id' => $detail->TransactionDetailID,
                'old_values' => ['Status' => $oldStatus],
                'new_values' => ['Status' => $newStatus],
                'url' => $request->fullUrl(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('transactions.show', $detail->TransactionID)
                             ->with('success', 'Item status updated to "' . $validated['Status'] . '".');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Item status update failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating item status.');
        }
    }

    /**
     * function to create a reorder notice if one isnt already pending
     */
    private function createReorderNotice($itemID, $transactionID)
    {
        $existingNotice = ReorderNotice::where('ItemID', $itemID)
                                       ->where('Status', 'Pending')
                                       ->exists();
        
        if (!$existingNotice) {
            ReorderNotice::create([
                'ItemID' => $itemID,
                'NoticeDate' => Carbon::today('Asia/Manila')->toDateString(),
                'Status' => 'Pending',
                'Notes' => 'Triggered by Transaction #' . $transactionID
            ]);
        }
    }
}