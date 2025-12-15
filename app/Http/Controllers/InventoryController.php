<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\ReorderNotice; // Import ReorderNotice
use App\Models\AuditLog;      // Import AuditLog
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Added DB Facade
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
            DB::beginTransaction(); // Start Transaction

            $item = InventoryItem::create($validatedData);

            // --- REORDER LOGIC FOR NEW ITEMS ---
            $qty = (float) $item->Quantity;
            $level = (float) $item->ReorderLevel;

            if ($qty <= $level) {
                $notice = new ReorderNotice();
                $notice->ItemID = $item->ItemID;
                $notice->NoticeDate = Carbon::today('Asia/Manila')->toDateString();
                $notice->Status = 'Pending';
                $notice->Notes = 'Initial stock set below reorder level.';
                $notice->save();
            }
            // -----------------------------------

            // AUDIT LOG
            $log = new AuditLog();
            $log->user_id = Auth::id();
            $log->event = 'Created Item';
            $log->auditable_type = InventoryItem::class;
            $log->auditable_id = $item->ItemID;
            $log->url = request()->fullUrl();
            $log->ip_address = request()->ip();
            $log->user_agent = request()->userAgent();
            // FIX: Store details in 'new_values' array instead of non-existent columns
            $log->new_values = [
                'details' => "Added item: {$item->Name}",
                'module' => 'Inventory'
            ];
            $log->save();

            DB::commit(); 
            return redirect()->route('inventory.index')->with('success', 'Item "' . $validatedData['Name'] . '" created successfully!');
        } catch (\Exception $e) {
            DB::rollBack(); 
            Log::error("Inventory item creation failed: " . $e->getMessage());
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

            // --- REORDER LOGIC ---
            $newQty = (float) $inventory->Quantity;
            $reorderLevel = (float) $inventory->ReorderLevel;

            if ($newQty > $reorderLevel) {
                ReorderNotice::where('ItemID', $inventory->ItemID)
                             ->where('Status', 'Pending')
                             ->update([
                                 'Status' => 'Resolved',
                                 'Notes' => 'Auto-resolved: Stock replenished above reorder level.'
                             ]);
            } 
            elseif ($newQty <= $reorderLevel) {
                $existingNotice = ReorderNotice::where('ItemID', $inventory->ItemID)
                                               ->where('Status', 'Pending')
                                               ->exists();
                if (!$existingNotice) {
                    $notice = new ReorderNotice();
                    $notice->ItemID = $inventory->ItemID;
                    $notice->NoticeDate = Carbon::today('Asia/Manila')->toDateString();
                    $notice->Status = 'Pending';
                    $notice->Notes = 'Triggered by manual inventory update.';
                    $notice->save();
                }
            }
            // -----------------------------

            // AUDIT LOG
            $log = new AuditLog();
            $log->user_id = Auth::id();
            $log->event = 'Updated Item';
            $log->auditable_type = InventoryItem::class;
            $log->auditable_id = $inventory->ItemID;
            $log->url = request()->fullUrl();
            $log->ip_address = request()->ip();
            $log->user_agent = request()->userAgent();
            // FIX: Store details in 'new_values' array instead of non-existent columns
            $log->new_values = [
                'details' => "Updated item: {$inventory->Name}. New Stock: {$inventory->Quantity}",
                'module' => 'Inventory'
            ];
            $log->save();

            DB::commit(); 
            return redirect()->route('inventory.index')->with('success', 'Item "' . $inventory->Name . '" updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack(); 
            Log::error("Inventory item update failed: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Update Error: ' . $e->getMessage());
        }
    }

    public function destroy(InventoryItem $inventory)
    {
        try {
            DB::beginTransaction(); 

            $name = $inventory->Name;
            $id = $inventory->ItemID; 
            $inventory->delete();

            // AUDIT LOG
            $log = new AuditLog();
            $log->user_id = Auth::id();
            $log->event = 'Deleted Item';
            $log->auditable_type = InventoryItem::class;
            $log->auditable_id = $id;
            $log->url = request()->fullUrl();
            $log->ip_address = request()->ip();
            $log->user_agent = request()->userAgent();
            // FIX: Store details in 'new_values' array instead of non-existent columns
            $log->new_values = [
                'details' => "Deleted item: {$name}",
                'module' => 'Inventory'
            ];
            $log->save();

            DB::commit(); 
            return redirect()->route('inventory.index')->with('success', 'Item "' . $name . '" deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); 
            Log::error("Inventory item deletion failed: " . $e->getMessage());
            return redirect()->route('inventory.index')->with('error', 'Delete Error: ' . $e->getMessage());
        }
    }
}