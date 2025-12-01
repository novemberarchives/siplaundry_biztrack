<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\ReorderNotice; // Import ReorderNotice
use App\Models\AuditLog;      // Import AuditLog
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InventoryController extends Controller
{
    public function index()
    {
        $items = InventoryItem::all();
        return view('inventory.index', [
            'items' => $items,
            'currentModule' => 'Inventory'
        ]);
    }

    public function create()
    {
        return view('inventory.create', ['currentModule' => 'Inventory']);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Name' => 'required|string|unique:inventory_items|max:255',
            'Category' => 'nullable|string|max:255',
            'Unit' => 'required|string|max:50',
            'UnitPrice' => 'required|numeric|min:0',
            'Quantity' => 'required|numeric|min:0',
            'ReorderLevel' => 'required|numeric|min:0',
        ]);

        try {
            $item = InventoryItem::create($validatedData);

            // AUDIT LOG
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'Created Item',
                'details' => "Added item: {$item->Name}",
                'module' => 'Inventory'
            ]);

            return redirect()->route('inventory.index')->with('success', 'Item "' . $validatedData['Name'] . '" created successfully!');
        } catch (\Exception $e) {
            Log::error("Inventory item creation failed: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Item creation failed. Please try again.');
        }
    }

    public function edit(InventoryItem $inventory)
    {
        return view('inventory.edit', [
            'item' => $inventory,
            'currentModule' => 'Inventory'
        ]);
    }

    public function update(Request $request, InventoryItem $inventory)
    {
        $validatedData = $request->validate([
            'Name' => ['required', 'string', 'max:255', Rule::unique('inventory_items')->ignore($inventory->ItemID, 'ItemID')],
            'Category' => 'nullable|string|max:255',
            'Unit' => 'required|string|max:50',
            'UnitPrice' => 'required|numeric|min:0',
            'Quantity' => 'required|numeric|min:0',
            'ReorderLevel' => 'required|numeric|min:0',
        ]);

        try {
            $inventory->update($validatedData);

            // --- REVISION: Check Reorder Level on Manual Update ---
            if ($inventory->Quantity <= $inventory->ReorderLevel) {
                // Check if notice exists
                $existingNotice = ReorderNotice::where('ItemID', $inventory->ItemID)
                                               ->where('Status', 'Pending')
                                               ->exists();
                if (!$existingNotice) {
                    ReorderNotice::create([
                        'ItemID' => $inventory->ItemID,
                        'NoticeDate' => Carbon::today('Asia/Manila')->toDateString(),
                        'Status' => 'Pending',
                        'Notes' => 'Triggered by manual inventory update.'
                    ]);
                }
            }
            // ------------------------------------------------------

            // AUDIT LOG
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'Updated Item',
                'details' => "Updated item: {$inventory->Name}. New Stock: {$inventory->Quantity}",
                'module' => 'Inventory'
            ]);

            return redirect()->route('inventory.index')->with('success', 'Item "' . $inventory->Name . '" updated successfully!');
        } catch (\Exception $e) {
            Log::error("Inventory item update failed: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Item update failed. Please try again.');
        }
    }

    public function destroy(InventoryItem $inventory)
    {
        try {
            $name = $inventory->Name;
            $inventory->delete();

            // AUDIT LOG
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'Deleted Item',
                'details' => "Deleted item: {$name}",
                'module' => 'Inventory'
            ]);

            return redirect()->route('inventory.index')->with('success', 'Item "' . $name . '" deleted successfully.');
        } catch (\Exception $e) {
            Log::error("Inventory item deletion failed: " . $e->getMessage());
            return redirect()->route('inventory.index')->with('error', 'Cannot delete item. It may be linked to usage or expense records.');
        }
    }
}