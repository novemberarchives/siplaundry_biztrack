<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\ReorderNotice; 
use App\Models\AuditLog;    
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
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
                'event' => 'Created Item', 
                'auditable_type' => InventoryItem::class, 
                'auditable_id' => $item->ItemID,        
                'details' => "Added item: {$item->Name}",
                'module' => 'Inventory'
            ]);

 
            return redirect()->route('inventory.index')->with('success', 'Item "' . $validatedData['Name'] . '" created successfully!');
        } catch (\Exception $e) {
 
            Log::error("Inventory item creation failed: " . $e->getMessage());
            // show specific error message
            return redirect()->back()->withInput()->with('error', 'Creation failed: ' . $e->getMessage());
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
            DB::beginTransaction(); 

            $inventory->update($validatedData);

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

            // AUDIT LOG
            AuditLog::create([
                'user_id' => Auth::id(),
                'event' => 'Updated Item',
                'auditable_type' => InventoryItem::class, 
                'auditable_id' => $inventory->ItemID,     
                'details' => "Updated item: {$inventory->Name}. New Stock: {$inventory->Quantity}",
                'module' => 'Inventory'
            ]);

            DB::commit(); // Commit 
            return redirect()->route('inventory.index')->with('success', 'Item "' . $inventory->Name . '" updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback 
            Log::error("Inventory item update failed: " . $e->getMessage());
            
            // return error message
            return redirect()->back()->withInput()->with('error', 'Update Error: ' . $e->getMessage());
        }
    }

    public function destroy(InventoryItem $inventory)
    {
        try {
            DB::beginTransaction(); // start transaction

            $name = $inventory->Name;
            $id = $inventory->ItemID; // ID before deletion
            $inventory->delete();

            // AUDIT LOG
            AuditLog::create([
                'user_id' => Auth::id(),
                'event' => 'Deleted Item',
                'auditable_type' => InventoryItem::class, 
                'auditable_id' => $id,                   
                'details' => "Deleted item: {$name}",
                'module' => 'Inventory'
            ]);

            DB::commit(); // Commit
            return redirect()->route('inventory.index')->with('success', 'Item "' . $name . '" deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback 
            Log::error("Inventory item deletion failed: " . $e->getMessage());
            return redirect()->route('inventory.index')->with('error', 'Delete Error: ' . $e->getMessage());
        }
    }
}