<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    /**
     * Display a listing of the inventory items.
     */
    public function index()
    {
        $items = InventoryItem::all();
        
        return view('inventory.index', [
            'items' => $items,
            'currentModule' => 'Inventory'
        ]);
    }

    /**
     * Show the form for creating a new inventory item.
     */
    public function create()
    {
        return view('inventory.create', ['currentModule' => 'Inventory']);
    }

    /**
     * Store a newly created inventory item in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Name' => 'required|string|unique:inventory_items|max:255',
            'Category' => 'nullable|string|max:255',
            'Unit' => 'required|string|max:50', // <-- ADD THIS
            'UnitPrice' => 'required|numeric|min:0',
            'Quantity' => 'required|numeric|min:0',
            'ReorderLevel' => 'required|numeric|min:0',
        ]);

        try {
            InventoryItem::create($validatedData);
            return redirect()->route('inventory.index')->with('success', 'Item "' . $validatedData['Name'] . '" created successfully!');
        } catch (\Exception $e) {
            Log::error("Inventory item creation failed: " . $e->getMessage());
            
            // --- DEBUG CHANGE ---
            // Temporarily send the real error message back to the form.
            $errorMessage = $e->getMessage();
            return redirect()->back()->withInput()->with('error', 'Item creation failed. DEBUG: ' . $errorMessage);
            // --- END DEBUG CHANGE ---
        }
    }

    
    // * Show the form for editing the specified inventory item.

    public function edit(InventoryItem $inventory)
    {
        // Route model binding automatically finds the item by its primary key
        return view('inventory.edit', [
            'item' => $inventory,
            'currentModule' => 'Inventory'
        ]);
    }

    /**
     * Update the specified inventory item in storage.
     */
    public function update(Request $request, InventoryItem $inventory)
    {
        $validatedData = $request->validate([
            'Name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('inventory_items')->ignore($inventory->ItemID, 'ItemID')
            ],
            'Category' => 'nullable|string|max:255',
            'Unit' => 'required|string|max:50', // <-- ADD THIS
            'UnitPrice' => 'required|numeric|min:0',
            'Quantity' => 'required|numeric|min:0',
            'ReorderLevel' => 'required|numeric|min:0',
        ]);

        try {
            $inventory->update($validatedData);
            return redirect()->route('inventory.index')->with('success', 'Item "' . $inventory->Name . '" updated successfully!');
        } catch (\Exception $e) {
            Log::error("Inventory item update failed: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Item update failed. Please try again.');
        }
    }

    /**
     * Remove the specified inventory item from storage.
     */
    public function destroy(InventoryItem $inventory)
    {
        try {
            $inventory->delete();
            return redirect()->route('inventory.index')->with('success', 'Item "' . $inventory->Name . '" deleted successfully.');
        } catch (\Exception $e) {
            Log::error("Inventory item deletion failed: " . $e->getMessage());
            // Catch foreign key constraint errors
            return redirect()->route('inventory.index')->with('error', 'Cannot delete item. It may be linked to usage or expense records.');
        }
    }
}