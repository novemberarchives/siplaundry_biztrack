<?php

namespace App\Http\Controllers;

use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            $detail->Status = $validated['Status'];
            $detail->save();

            // 3. Redirect back to the transaction detail page
            return redirect()->route('transactions.show', $detail->TransactionID)
                             ->with('success', 'Item status updated to "' . $validated['Status'] . '".');

        } catch (\Exception $e) {
            \Log::error("Item status update failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating item status.');
        }
    }
}