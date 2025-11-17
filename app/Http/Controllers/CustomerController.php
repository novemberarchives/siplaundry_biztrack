<?php

namespace App\Http\Controllers;

use App\Models\Customer; // Import Customer Model
use Illuminate\Http\Request;
use Carbon\Carbon; // For handling DateCreated
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource (Customers).
     */
    public function index()
    {
        // 1. Fetch all customers from the database
        $customers = Customer::all(); 
        
        // 2. Pass the customers to the new index view
        return view('customers.index', [
            'customers' => $customers,
            'currentModule' => 'Customers'
        ]);
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        // CORRECTED: This now points to 'resources/views/customers/create.blade.php'
        return view('customers.create', ['currentModule' => 'Customers']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Name' => 'required|string|max:255',
            'ContactNumber' => 'required|string|unique:customers|max:20', // Enforce uniqueness
            'Address' => 'nullable|string|max:255',
            'Email' => 'nullable|email|unique:customers|max:255', // Enforce unique email if provided
        ]);
        
        // Add DateCreated manually since $timestamps is false
        $validatedData['DateCreated'] = Carbon::today()->toDateString();
        
        try {
            Customer::create($validatedData);

            return redirect()->route('customers.index')->with('success', 'Customer ' . $validatedData['Name'] . ' successfully registered!');
        } catch (\Exception $e) {
            \Log::error("Customer creation failed: " . $e->getMessage());
            // This message covers unique constraints and other database errors
            return redirect()->back()->withInput()->with('error', 'Customer registration failed. Contact number or Email might already be in use.');
        }
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer)
    {
        // $customer is automatically fetched by Laravel through "Route Model Binding"
        return view('customers.edit', [
            'customer' => $customer,
            'currentModule' => 'Customers'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validatedData = $request->validate([
            'Name' => 'required|string|max:255',
            'ContactNumber' => [
                'required',
                'string',
                'max:20',
                Rule::unique('customers')->ignore($customer->CustomerID, 'CustomerID') // Ignore self
            ],
            'Address' => 'nullable|string|max:255',
            'Email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('customers')->ignore($customer->CustomerID, 'CustomerID') // Ignore self
            ],
        ]);
        
        try {
            $customer->update($validatedData);

            return redirect()->route('customers.index')->with('success', 'Customer ' . $customer->Name . ' successfully updated!');
        } catch (\Exception $e) {
            \Log::error("Customer update failed: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Customer update failed. Contact number or Email might already be in use by another customer.');
        }
    }
}