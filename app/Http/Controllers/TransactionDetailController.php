<?php

namespace App\Http\Controllers;

use App\Models\TransactionDetail;
use App\Models\InventoryItem;
use App\Models\InventoryUsage;
use App\Models\ReorderNotice;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionDetailController extends Controller
{
    /**
     * Update the status of a specific transaction detail (line item).
     */
    public function updateStatus(Request $request, TransactionDetail $detail)
    {
        // Define the allowed statuses for the laundry process
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
                Rule::in($allowedStatuses) // Ensure status is one of the allowed values
            ],
        ]);

        // 2. Update the status
        try {
            DB::beginTransaction(); // Use a transaction to ensure stock and status update together

            $oldStatus = $detail->Status; // Get status *before* saving
            $newStatus = $validated['Status'];

            $detail->Status = $newStatus;
            $detail->save();

            // --- 3. NEW: AUTO-DEDUCT INVENTORY LOGIC ---
            // We deduct stock when status is set to 'Completed'
            // AND it was not 'Completed' before (to prevent deducting twice).
            if ($newStatus === 'Completed' && $oldStatus !== 'Completed') {
                
                // Find all usage rules for this service (e.g., "Wash & Fold")
                $usageRules = InventoryUsage::where('ServiceID', $detail->ServiceID)->get();

                if ($usageRules->isNotEmpty()) {
                    // Get the order quantity (e.g., 5.2 kg)
                    $orderQuantity = $detail->Weight ?? $detail->Quantity;

                    foreach ($usageRules as $rule) {
                        // Find the inventory item (e.g., "Detergent")
                        $inventoryItem = InventoryItem::find($rule->ItemID);

                        if ($inventoryItem) {
                            // Calculate total to deduct (e.g., 5.2kg * 0.2 bags/kg = 1.04)
                            $totalToDeduct = $orderQuantity * $rule->QuantityUsed;

                            // --- 4. NEW: DISCRETE UNIT HANDLING ---
                            // Define units that must be whole numbers
                            $discreteUnits = ['pcs', 'pc', 'item', 'pair', 'hanger', 'bag', 'bags'];
                            $itemUnit = strtolower($inventoryItem->Unit);

                            // If the item's unit is discrete, round UP to the nearest whole number.
                            if (in_array($itemUnit, $discreteUnits)) {
                                $totalToDeduct = ceil($totalToDeduct);
                            }
                            // --- END OF NEW LOGIC ---

                            // Deduct from stock (safe decrement)
                            $inventoryItem->decrement('Quantity', $totalToDeduct);
                            
                            // --- 5. NEW: CHECK REORDER LEVEL ---
                            $newItemQuantity = $inventoryItem->Quantity; // Get the new quantity
                            
                            // Check if stock is low AND if a notice for this item isn't already pending
                            if ($newItemQuantity <= $inventoryItem->ReorderLevel) {
                                $this->createReorderNotice($inventoryItem->ItemID);
                            }
                            // --- END OF NEW LOGIC ---
                        }
                    }
                }
            }
            // --- END: AUTO-DEDUCT INVENTORY LOGIC ---

            DB::commit(); // Commit all changes

            // 4. Redirect back to the transaction detail page
            return redirect()->route('transactions.show', $detail->TransactionID)
                             ->with('success', 'Item status updated to "' . $validated['Status'] . '".');

        } catch (\Exception $e) {
            DB::rollBack(); // Roll back if any part fails
            \Log::error("Item status update failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating item status.');
        }
    }

    /**
     * Helper function to create a reorder notice if one isn't already pending.
     */
    private function createReorderNotice($itemID)
    {
        // Check if a "Pending" notice for this item already exists
        $existingNotice = ReorderNotice::where('ItemID', $itemID)
                                       ->where('Status', 'Pending')
                                       ->exists(); // .exists() is faster
        
        // If no pending notice exists, create one
        if (!$existingNotice) {
            ReorderNotice::create([
                'ItemID' => $itemID,
                'NoticeDate' => Carbon::today()->toDateString(),
                'Status' => 'Pending',
            ]);
            
            // (Future Step: We could also send an email notification to the manager here)
        }
    }
}