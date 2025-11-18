@extends('layouts.app')
@section('title', 'Edit Service | Sip Laundry')
@section('content')
    <!-- Card Container -->
    <div class="w-full max-w-lg mx-auto space-y-6">
        <div class="bg-white p-8 md:p-10 rounded-xl shadow-2xl border border-indigo-100">

            <header class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 tracking-tight">
                    Edit Service
                </h1>
                <p class="text-gray-500 mt-2">Update details for {{ $service->Name }}.</p>
            </header>
            
            <!-- Back to Services Index -->
            <div class="mb-6 text-center">
                 <a href="{{ route('services.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                    ← Back to Service List
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


            <!-- Service Edit Form -->
            <form method="POST" action="{{ route('services.update', $service->ServiceID) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Service Name (Required, Unique) -->
                <div>
                    <label for="Name" class="block text-sm font-medium text-gray-700 mb-1">Service Name</label>
                    <input
                        type="text"
                        id="Name"
                        name="Name"
                        value="{{ old('Name', $service->Name) }}"
                        required
                        autofocus
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out sm:text-sm"
                        placeholder="e.g., Wash & Fold"
                    >
                    @error('Name')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Base Price, Unit, Min Qty -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- BasePrice (Required) -->
                    <div>
                        <label for="BasePrice" class="block text-sm font-medium text-gray-700 mb-1">Base Price (₱)</label>
                        <input
                            type="number"
                            step="0.01"
                            id="BasePrice"
                            name="BasePrice"
                            value="{{ old('BasePrice', $service->BasePrice) }}"
                            required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out sm:text-sm"
                            placeholder="e.g., 1.50"
                        >
                        @error('BasePrice')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Unit (Required) -->
                    <div>
                        <label for="Unit" class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                        <input
                            type="text"
                            id="Unit"
                            name="Unit"
                            value="{{ old('Unit', $service->Unit) }}"
                            required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out sm:text-sm"
                            placeholder="e.g., kg, item, load"
                        >
                        @error('Unit')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Minimum Quantity -->
                    <div>
                        <label for="MinQuantity" class="block text-sm font-medium text-gray-700 mb-1">Min. Qty (Opt.)</label>
                        <input
                            type="number"
                            step="0.01"
                            id="MinQuantity"
                            name="MinQuantity"
                            value="{{ old('MinQuantity', $service->MinQuantity) }}"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="e.g., 3.0"
                        >
                        @error('MinQuantity')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Description (Optional) -->
                <div>
                    <label for="Description" class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                    <textarea
                        id="Description"
                        name="Description"
                        rows="3"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out sm:text-sm"
                        placeholder="Briefly describe what this service includes."
                    >{{ old('Description', $service->Description) }}</textarea>
                    @error('Description')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button
                        type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-md text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 ease-in-out transform hover:scale-[1.01]"
                    >
                        Update Service
                    </button>
                </div>
            </form>
        </div>

        <!-- ********************************************** -->
        <!-- NEW: MANAGE INVENTORY USAGE -->
        <!-- ********************************************** -->
        <div class="w-full max-w-lg mx-auto bg-white p-8 md:p-10 rounded-xl shadow-2xl border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-3">
                Manage Inventory Usage
            </h2>
            <p class="text-sm text-gray-500 mb-4">
                <!-- MODIFIED TEXT -->
                Define how much inventory this service ({{ $service->Name }}) consumes **per 1 {{ $service->Unit }}**.
            </p>

            <!-- List of current items used -->
            <div class="space-y-2 mb-6">
                @forelse ($service->inventoryUsages as $usage)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border">
                        <div>
                            <p class="font-medium text-gray-800">{{ $usage->item->Name }}</p>
                            <p class="text-sm text-gray-600">
                                {{ $usage->QuantityUsed }} {{ $usage->item->Unit }} / per {{ $service->Unit }}
                            </p>
                        </div>
                        <form action="{{ route('inventory-usage.destroy', $usage->UsageID) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Remove</button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 italic text-center">No inventory items are linked to this service yet.</p>
                @endforelse
            </div>

            <!-- Form to add a new item -->
            <form method="POST" action="{{ route('inventory-usage.store', $service->ServiceID) }}" class="space-y-4 border-t pt-6">
                @csrf
                <h3 class="font-medium text-gray-700">Add New Item Rule:</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label for="ItemID" class="block text-sm font-medium text-gray-700">Item</label>
                        <select id="ItemID" name="ItemID" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="" disabled selected>Select an item...</option>
                            @php
                                // Get all inventory items. In a larger app, you'd pass this from the controller.
                                // We also eager-load the items this service *already* uses to filter them out.
                                $existingItemIDs = $service->inventoryUsages->pluck('ItemID');
                                $inventoryItems = \App\Models\InventoryItem::whereNotIn('ItemID', $existingItemIDs)->get();
                            @endphp
                            @foreach ($inventoryItems as $item)
                                <option value="{{ $item->ItemID }}">{{ $item->Name }} (Stock: {{ $item->Quantity }} {{ $item->Unit }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <!-- MODIFIED LABEL -->
                        <label for="QuantityUsed" class="block text-sm font-medium text-gray-700">Qty Used</label>
                        <input
                            type="number"
                            step="0.0001"
                            id="QuantityUsed"
                            name="QuantityUsed"
                            required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="e.g., 0.05"
                        >
                    </div>
                </div>
                <div>
                    <button
                        type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none"
                    >
                        Add Item to Service
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection