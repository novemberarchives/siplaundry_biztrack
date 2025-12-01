@extends('layouts.app')

@section('title', 'Add New Service | Sip Laundry')

@section('content')
    <!-- Main Container (Flex for side-by-side layout on desktop) -->
    <div class="w-full max-w-lg mx-auto space-y-6">
        
        <!-- Header & Back Link (Side on Desktop) -->
        <div class="flex items-center justify-between px-2">
            <a href="{{ route('services.index') }}" class="group flex items-center gap-2 text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-gray-100 dark:border-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span class="hidden lg:block">Back</span>
                <span class="lg:hidden">Back to List</span>
            </a>
        </div>

        <!-- Main Bento Card -->
        <div class="flex-1 w-full max-w-lg bg-white dark:bg-gray-800 p-6 md:p-8 rounded-[2rem] shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700">

            <header class="text-center mb-8">
                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 rounded-2xl flex items-center justify-center mx-auto mb-3 text-blue-600 dark:text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                <h1 class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Add Service
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">Define a new service.</p>
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

            <!-- Service Create Form -->
            <form method="POST" action="{{ route('services.store') }}" class="space-y-4">
                @csrf

                <!-- Service Name -->
                <div class="space-y-1">
                    <label for="Name" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Service Name</label>
                    <input
                        type="text"
                        id="Name"
                        name="Name"
                        value="{{ old('Name') }}"
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
                <div class="grid grid-cols-2 gap-4">
                    <!-- BasePrice -->
                    <div class="space-y-1">
                        <label for="BasePrice" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Base Price (₱)</label>
                        <input
                            type="number"
                            step="0.01"
                            id="BasePrice"
                            name="BasePrice"
                            value="{{ old('BasePrice') }}"
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
                            value="{{ old('Unit') }}"
                            required
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none placeholder-gray-400"
                            placeholder="e.g. kg"
                        >
                        @error('Unit')
                            <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Minimum Quantity -->
                <div class="space-y-1">
                    <label for="MinQuantity" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Minimum Qty <span class="normal-case font-normal opacity-50">(Optional)</span></label>
                    <input
                        type="number"
                        step="0.01"
                        id="MinQuantity"
                        name="MinQuantity"
                        value="{{ old('MinQuantity') }}"
                        class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none placeholder-gray-400"
                        placeholder="e.g. 3.0"
                    >
                    @error('MinQuantity')
                        <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Description -->
                <div class="space-y-1">
                    <label for="Description" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Description <span class="normal-case font-normal opacity-50">(Optional)</span></label>
                    <textarea
                        id="Description"
                        name="Description"
                        rows="3"
                        class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none placeholder-gray-400"
                        placeholder="Briefly describe this service..."
                    >{{ old('Description') }}</textarea>
                    @error('Description')
                        <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button
                        type="submit"
                        class="w-full py-3.5 px-6 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-600/30 transition-all transform hover:scale-[1.02] active:scale-[0.98]"
                    >
                        Create Service
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection