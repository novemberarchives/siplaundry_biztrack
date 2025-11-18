<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryUsage;
use App\Models\Service;
use Illuminate\Http\Request;

class InventoryUsageController extends Controller
{
    /**
     * Add a new inventory usage rule to a service.
     */
    public function store(Request $request, Service $service)
    {
        $validated = $request->validate([
            'ItemID' => 'required|integer|exists:inventory_items,ItemID',
            'QuantityUsed' => 'required|numeric|min:0.0001',
        ]);

        try {
            // Check if this item is already added to prevent duplicates
            $existing = InventoryUsage::where('ServiceID', $service->ServiceID)
                                      ->where('ItemID', $validated['ItemID'])
                                      ->first();

            if ($existing) {
                return redirect()->back()->with('error', 'That item is already added to this service.');
            }

            // Create the new usage rule
            InventoryUsage::create([
                'ServiceID' => $service->ServiceID,
                'ItemID' => $validated['ItemID'],
                'QuantityUsed' => $validated['QuantityUsed'],
            ]);

            return redirect()->route('services.edit', $service->ServiceID)
                             ->with('success', 'Inventory item added to service.');

        } catch (\Exception $e) {
            \Log::error("Failed to add inventory usage: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error adding item. Please try again.');
        }
    }

    /**
     * Remove an inventory usage rule from a service.
     */
    public function destroy(InventoryUsage $usage)
    {
        try {
            // We use the $usage object found by Route Model Binding
            $usage->delete();
            return redirect()->route('services.edit', $usage->ServiceID)
                             ->with('success', 'Inventory item removed from service.');

        } catch (\Exception $e) {
            \Log::error("Failed to remove inventory usage: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error removing item. Please try again.');
        }
    }
}