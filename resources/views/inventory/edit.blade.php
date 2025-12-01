@extends('layouts.app')

@section('title', 'Edit Inventory Item | Sip Laundry')

@section('content')
    <!-- Card Container -->
    <div class="w-full max-w-lg mx-auto space-y-6">
        
        <!-- Back Link (Positioned on top) -->
        <div class="flex items-center justify-between px-2">
            <a href="{{ route('inventory.index') }}" class="group flex items-center gap-2 text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-gray-100 dark:border-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                Back to List
            </a>
        </div>

        <!-- Main Bento Card -->
        <div class="bg-white dark:bg-gray-800 p-8 md:p-10 rounded-[2.5rem] shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700">

            <header class="text-center mb-10">
                <div class="w-14 h-14 bg-blue-50 dark:bg-blue-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4 text-blue-600 dark:text-blue-400">
                    <!-- Pencil/Edit Icon -->
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Edit Inventory Item
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-2">Update details for <span class="text-blue-600 dark:text-blue-400">{{ $item->Name }}</span>.</p>
            </header>

            <!-- Error Messages -->
            @if (session('error'))
                <div class="p-4 mb-6 text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 rounded-2xl border border-red-100 dark:border-red-900/30 font-bold flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                 <div class="p-4 mb-6 text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 rounded-2xl border border-red-100 dark:border-red-900/30 font-bold">
                    <p>Please check the inputs below.</p>
                </div>
            @endif

            <!-- Inventory Edit Form -->
            <form method="POST" action="{{ route('inventory.update', $item->ItemID) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Item Name -->
                <div class="space-y-2">
                    <label for="Name" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Item Name</label>
                    <input
                        type="text"
                        id="Name"
                        name="Name"
                        value="{{ old('Name', $item->Name) }}"
                        required
                        autofocus
                        class="block w-full px-5 py-3.5 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-2xl focus:ring-0 transition-all font-medium outline-none placeholder-gray-400"
                        placeholder="e.g., Detergent Powder"
                    >
                    @error('Name')
                        <p class="text-sm text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category & Unit (Grid) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label for="Category" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Category <span class="normal-case font-normal opacity-50">(Optional)</span></label>
                        <input
                            type="text"
                            id="Category"
                            name="Category"
                            value="{{ old('Category', $item->Category) }}"
                            class="block w-full px-5 py-3.5 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-2xl focus:ring-0 transition-all font-medium outline-none placeholder-gray-400"
                            placeholder="e.g. Cleaning"
                        >
                        @error('Category')
                            <p class="text-sm text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="Unit" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Unit</label>
                        <input
                            type="text"
                            id="Unit"
                            name="Unit"
                            value="{{ old('Unit', $item->Unit) }}"
                            required
                            class="block w-full px-5 py-3.5 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-2xl focus:ring-0 transition-all font-medium outline-none placeholder-gray-400"
                            placeholder="e.g., kg, L, pcs"
                        >
                        @error('Unit')
                            <p class="text-sm text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Row for Price, Quantity, Reorder -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- Unit Price -->
                    <div class="space-y-2">
                        <label for="UnitPrice" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Unit Price (₱)</label>
                        <input
                            type="number"
                            step="0.01"
                            id="UnitPrice"
                            name="UnitPrice"
                            value="{{ old('UnitPrice', $item->UnitPrice) }}"
                            required
                            class="block w-full px-5 py-3.5 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-2xl focus:ring-0 transition-all font-medium outline-none placeholder-gray-400"
                            placeholder="0.00"
                        >
                        @error('UnitPrice')
                            <p class="text-sm text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Current Quantity -->
                    <div class="space-y-2">
                        <label for="Quantity" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Stock</label>
                        <input
                            type="number"
                            step="0.01"
                            id="Quantity"
                            name="Quantity"
                            value="{{ old('Quantity', $item->Quantity) }}"
                            required
                            class="block w-full px-5 py-3.5 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-2xl focus:ring-0 transition-all font-medium outline-none placeholder-gray-400"
                            placeholder="0.00"
                        >
                        @error('Quantity')
                            <p class="text-sm text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reorder Level -->
                    <div class="space-y-2">
                        <label for="ReorderLevel" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Reorder At</label>
                        <input
                            type="number"
                            step="0.01"
                            id="ReorderLevel"
                            name="ReorderLevel"
                            value="{{ old('ReorderLevel', $item->ReorderLevel) }}"
                            required
                            class="block w-full px-5 py-3.5 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-2xl focus:ring-0 transition-all font-medium outline-none placeholder-gray-400"
                            placeholder="10.00"
                        >
                        @error('ReorderLevel')
                            <p class="text-sm text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-6">
                    <button
                        type="submit"
                        class="w-full py-4 px-6 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-600/30 transition-all transform hover:scale-[1.02] active:scale-[0.98] text-lg"
                    >
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection