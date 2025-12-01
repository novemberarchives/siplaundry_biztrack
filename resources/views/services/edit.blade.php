@extends('layouts.app')

@section('title', 'Edit Service | Sip Laundry')

@section('content')
    <!-- Main Container (Flex for side-by-side layout on desktop) -->
    <div class="flex flex-col lg:flex-row justify-center items-start gap-6 max-w-4xl mx-auto">
        
        <!-- Header & Back Link (Side on Desktop) -->
        <div class="w-full lg:w-auto lg:sticky lg:top-6 flex lg:flex-col items-center lg:items-end gap-2 lg:mt-10">
            <a href="{{ route('services.index') }}" class="group flex items-center gap-2 text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                <span class="hidden lg:block">Back</span>
                <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-gray-100 dark:border-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span class="lg:hidden">Back to List</span>
            </a>
        </div>

        <!-- Main Content Column (Stacked Cards) -->
        <div class="flex-1 w-full max-w-lg space-y-8">

            <!-- CARD 1: Edit Service Details -->
            <div class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-[2rem] shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700">

                <header class="text-center mb-8">
                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 rounded-2xl flex items-center justify-center mx-auto mb-3 text-blue-600 dark:text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <h1 class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        Edit Service
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">Update details for <span class="text-blue-600 dark:text-blue-400">{{ $service->Name }}</span>.</p>
                </header>

                <!-- Error Messages -->
                @if (session('error'))
                    <div class="p-3 mb-4 text-xs text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-100 dark:border-red-900/30 font-bold flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                     <div class="p-3 mb-4 text-xs text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-100 dark:border-red-900/30 font-bold">
                        <p>Please check the inputs below.</p>
                    </div>
                @endif

                <!-- Service Edit Form -->
                <form method="POST" action="{{ route('services.update', $service->ServiceID) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Service Name -->
                    <div class="space-y-1">
                        <label for="Name" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Service Name</label>
                        <input
                            type="text"
                            id="Name"
                            name="Name"
                            value="{{ old('Name', $service->Name) }}"
                            required
                            autofocus
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none placeholder-gray-400"
                            placeholder="e.g. Wash & Fold"
                        >
                        @error('Name')
                            <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Base Price & Unit (Grid) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- BasePrice -->
                        <div class="space-y-1">
                            <label for="BasePrice" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Base Price (₱)</label>
                            <input
                                type="number"
                                step="0.01"
                                id="BasePrice"
                                name="BasePrice"
                                value="{{ old('BasePrice', $service->BasePrice) }}"
                                required
                                class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none placeholder-gray-400"
                                placeholder="0.00"
                            >
                            @error('BasePrice')
                                <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Unit -->
                        <div class="space-y-1">
                            <label for="Unit" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Unit</label>
                            <input
                                type="text"
                                id="Unit"
                                name="Unit"
                                value="{{ old('Unit', $service->Unit) }}"
                                required
                                class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none placeholder-gray-400"
                                placeholder="e.g. kg"
                            >
                            @error('Unit')
                                <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Minimum Quantity -->
                        <div class="space-y-1">
                            <label for="MinQuantity" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Min Qty <span class="normal-case font-normal opacity-50">(Opt)</span></label>
                            <input
                                type="number"
                                step="0.01"
                                id="MinQuantity"
                                name="MinQuantity"
                                value="{{ old('MinQuantity', $service->MinQuantity) }}"
                                class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none placeholder-gray-400"
                                placeholder="e.g. 3.0"
                            >
                            @error('MinQuantity')
                                <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="space-y-1">
                        <label for="Description" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Description <span class="normal-case font-normal opacity-50">(Optional)</span></label>
                        <textarea
                            id="Description"
                            name="Description"
                            rows="3"
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none placeholder-gray-400"
                            placeholder="Briefly describe what this service includes."
                        >{{ old('Description', $service->Description) }}</textarea>
                        @error('Description')
                            <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4">
                        <button
                            type="submit"
                            class="w-full py-3.5 px-6 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-600/30 transition-all transform hover:scale-[1.02] active:scale-[0.98]"
                        >
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- CARD 2: Manage Inventory Usage -->
            <div class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-[2rem] shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700">
                <h2 class="text-lg font-extrabold text-gray-900 dark:text-white mb-2">
                    Inventory Rules
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-6">
                    Define how much inventory (e.g. detergent) is used per 1 <span class="text-blue-600 dark:text-blue-400 font-bold">{{ $service->Unit }}</span> of this service.
                </p>

                <!-- List of current items used -->
                <div class="space-y-3 mb-8">
                    @forelse ($service->inventoryUsages as $usage)
                        <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-700/30 rounded-2xl border border-gray-100 dark:border-gray-700">
                            <div>
                                <p class="font-bold text-sm text-gray-900 dark:text-white">{{ $usage->item->Name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                                    <span class="text-blue-600 dark:text-blue-400">{{ $usage->QuantityUsed }}</span> {{ $usage->item->Unit }} used
                                </p>
                            </div>
                            <form action="{{ route('inventory-usage.destroy', $usage->UsageID) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-xl bg-white dark:bg-gray-800 text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 border border-gray-200 dark:border-gray-600 transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-6 bg-gray-50 dark:bg-gray-700/30 rounded-2xl border border-dashed border-gray-200 dark:border-gray-600">
                            <p class="text-xs text-gray-400 dark:text-gray-500 font-medium">No inventory linked yet.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Form to add a new item -->
                <form method="POST" action="{{ route('inventory-usage.store', $service->ServiceID) }}" class="space-y-4 pt-6 border-t border-gray-100 dark:border-gray-700">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Item Select -->
                        <div class="md:col-span-2 space-y-1">
                            <label for="ItemID" class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Add Item</label>
                            <div class="relative">
                                <select id="ItemID" name="ItemID" required class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none appearance-none cursor-pointer">
                                    <option value="" disabled selected>Select...</option>
                                    @php
                                        $existingItemIDs = $service->inventoryUsages->pluck('ItemID');
                                        $inventoryItems = \App\Models\InventoryItem::whereNotIn('ItemID', $existingItemIDs)->get();
                                    @endphp
                                    @foreach ($inventoryItems as $item)
                                        <option value="{{ $item->ItemID }}">{{ $item->Name }} (Stock: {{ $item->Quantity }} {{ $item->Unit }})</option>
                                    @endforeach
                                </select>
                                <!-- Dropdown Icon -->
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Qty Used -->
                        <div class="space-y-1">
                            <label for="QuantityUsed" class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Qty Used</label>
                            <input
                                type="number"
                                step="0.0001"
                                id="QuantityUsed"
                                name="QuantityUsed"
                                required
                                class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none placeholder-gray-400"
                                placeholder="0.00"
                            >
                        </div>
                    </div>
                    
                    <div>
                        <button
                            type="submit"
                            class="w-full py-3 px-4 bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-bold rounded-xl shadow-md transition-all transform hover:scale-[1.02] active:scale-[0.98] text-sm"
                        >
                            + Add Rule
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection