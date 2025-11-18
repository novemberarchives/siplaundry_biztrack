@extends('layouts.app')

@section('title', 'Add Inventory Item | Sip Laundry')
@section('content')
    <!-- Card Container -->
    <div class="w-full max-w-lg mx-auto bg-white p-8 md:p-10 rounded-xl shadow-2xl border border-indigo-100">

        <header class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">
                Add New Inventory Item
            </h1>
            <p class="text-gray-500 mt-2">Add a new stock item like detergent or hangers.</p>
        </header>
        
        <!-- Back to Inventory Index -->
        <div class="mb-6 text-center">
             <a href="{{ route('inventory.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                ← Back to Inventory List
            </a>
        </div>

        <!-- Error/Success Messages -->
        @if (session('error'))
            <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-300" role="alert">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
             <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-300" role="alert">
                Please correct the form errors below.
            </div>
        @endif


        <!-- Inventory Create Form -->
        <form method="POST" action="{{ route('inventory.store') }}" class="space-y-6">
            @csrf

            <!-- Item Name (Required, Unique) -->
            <div>
                <label for="Name" class="block text-sm font-medium text-gray-700 mb-1">Item Name</label>
                <input
                    type="text"
                    id="Name"
                    name="Name"
                    value="{{ old('Name') }}"
                    required
                    autofocus
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="e.g., Detergent Powder"
                >
                @error('Name')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category & Unit (Side-by-side) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="Category" class="block text-sm font-medium text-gray-700 mb-1">Category (Optional)</label>
                    <input
                        type="text"
                        id="Category"
                        name="Category"
                        value="{{ old('Category') }}"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="e.g., Chemicals, Packaging"
                    >
                    @error('Category')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="Unit" class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                    <input
                        type="text"
                        id="Unit"
                        name="Unit"
                        value="{{ old('Unit') }}"
                        required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="e.g., kg, L, pcs"
                    >
                    @error('Unit')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row for Price, Quantity, Reorder -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Unit Price -->
                <div>
                    <label for="UnitPrice" class="block text-sm font-medium text-gray-700 mb-1">Unit Price (₱)</label>
                    <input
                        type="number"
                        step="0.01"
                        id="UnitPrice"
                        name="UnitPrice"
                        value="{{ old('UnitPrice') }}"
                        required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="0.00"
                    >
                    @error('UnitPrice')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Current Quantity -->
                <div>
                    <label for="Quantity" class="block text-sm font-medium text-gray-700 mb-1">Current Stock</label>
                    <input
                        type="number"
                        step="0.01"
                        id="Quantity"
                        name="Quantity"
                        value="{{ old('Quantity', 0) }}"
                        required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="0.00"
                    >
                    @error('Quantity')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reorder Level -->
                <div>
                    <label for="ReorderLevel" class="block text-sm font-medium text-gray-700 mb-1">Reorder Level</label>
                    <input
                        type="number"
                        step="0.01"
                        id="ReorderLevel"
                        name="ReorderLevel"
                        value="{{ old('ReorderLevel', 10) }}"
                        required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="10.00"
                    >
                    @error('ReorderLevel')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <button
                    type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-md text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200"
                >
                    Add Item to Inventory
                </button>
            </div>
        </form>
    </div>
@endsection