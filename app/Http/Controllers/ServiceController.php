<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log; // Added for clarity

class ServiceController extends Controller
{
    /**
     * Display a listing of the services.
     */
    public function index()
    {
        $services = Service::all();
        return view('services.index', [
            'services' => $services,
            'currentModule' => 'Services'
        ]);
    }

    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        return view('services.create', ['currentModule' => 'Services']);
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Name' => 'required|string|unique:services|max:255',
            'Description' => 'nullable|string',
            'BasePrice' => 'required|numeric|min:0',
            'Unit' => 'required|string|max:50', // e.g., "kg", "item"
            'MinQuantity' => 'nullable|numeric|min:0', // <-- ADDED THIS
        ]);

        try {
            Service::create($validatedData);
            return redirect()->route('services.index')->with('success', 'Service "' . $validatedData['Name'] . '" created successfully!');
        } catch (\Exception $e) {
            Log::error("Service creation failed: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Service creation failed. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Service $service)
    {
        return view('services.edit', [
            'service' => $service,
            'currentModule' => 'Services'
        ]);
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, Service $service)
    {
        $validatedData = $request->validate([
            'Name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('services')->ignore($service->ServiceID, 'ServiceID')
            ],
            'Description' => 'nullable|string',
            'BasePrice' => 'required|numeric|min:0',
            'Unit' => 'required|string|max:50',
            'MinQuantity' => 'nullable|numeric|min:0', // <-- ADD THIS
        ]);

        try {
            $service->update($validatedData);
            return redirect()->route('services.index')->with('success', 'Service "' . $service->Name . '" updated successfully!');
        } catch (\Exception $e) {
            Log::error("Service update failed: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Service update failed. Please try again.');
        }
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy(Service $service)
    {
        try {
            $service->delete();
            return redirect()->route('services.index')->with('success', 'Service "' . $service->Name . '" deleted successfully.');
        } catch (\Exception $e) {
            // FIXED: Removed the duplicate Log::error call
            Log::error("Service deletion failed: " . $e->getMessage());
            // Catch foreign key constraint errors
            return redirect()->route('services.index')->with('error', 'Cannot delete service. It is already linked to existing transactions.');
        }
    }
}