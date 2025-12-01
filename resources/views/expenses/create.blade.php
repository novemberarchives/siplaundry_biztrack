@extends('layouts.app')

@section('title', 'Add New Expense | Sip Laundry')

@section('content')
    <!-- Main Container -->
    <div class="w-full max-w-lg mx-auto space-y-6">
        
        <!-- Back Link (Positioned on top) -->
        <div class="flex items-center justify-between px-2">
            <a href="{{ route('expenses.index') }}" class="group flex items-center gap-2 text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
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
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Log Expense
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-2">Record purchase & restock inventory.</p>
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

            <!-- Expense Create Form -->
            <form method="POST" action="{{ route('expenses.store') }}" class="space-y-6">
                @csrf

                <!-- Item Purchased (Required) -->
                <div class="space-y-2">
                    <label for="ItemID" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Item Purchased</label>
                    <div class="relative">
                        <select id="ItemID" name="ItemID" required class="block w-full px-5 py-3.5 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-2xl focus:ring-0 transition-all font-medium outline-none appearance-none cursor-pointer">
                            <option value="" disabled selected>Select an inventory item...</option>
                            @foreach ($inventoryItems as $item)
                                <option value="{{ $item->ItemID }}" {{ old('ItemID') == $item->ItemID ? 'selected' : '' }}>
                                    {{ $item->Name }} (Stock: {{ $item->Quantity }} {{ $item->Unit }})
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('ItemID')
                        <p class="text-sm text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Link to Reorder Notice (Optional) -->
                <div class="space-y-2">
                    <label for="NoticeID" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Link to Alert <span class="normal-case font-normal opacity-50">(Optional)</span></label>
                    <div class="relative">
                        <select id="NoticeID" name="NoticeID" class="block w-full px-5 py-3.5 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-2xl focus:ring-0 transition-all font-medium outline-none appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            <option value="" selected>None (or select an item first)</option>
                            @foreach ($pendingNotices as $notice)
                                <option value="{{ $notice->NoticeID }}" data-item-id="{{ $notice->ItemID }}" class="hidden">
                                    Alert #{{ $notice->NoticeID }} - {{ $notice->item->Name }} ({{ \Carbon\Carbon::parse($notice->NoticeDate)->format('M d') }})
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('NoticeID')
                        <p class="text-sm text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Row for Date, Quantity, Cost -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- Date -->
                    <div class="space-y-2">
                        <label for="Date" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Date</label>
                        <input
                            type="date"
                            id="Date"
                            name="Date"
                            value="{{ old('Date', date('Y-m-d')) }}"
                            required
                            class="block w-full px-5 py-3.5 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-2xl focus:ring-0 transition-all font-medium outline-none"
                        >
                        @error('Date')
                            <p class="text-sm text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Quantity Purchased -->
                    <div class="space-y-2">
                        <label for="QuantityPurchased" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Qty Added</label>
                        <input
                            type="number"
                            step="0.01"
                            id="QuantityPurchased"
                            name="QuantityPurchased"
                            value="{{ old('QuantityPurchased') }}"
                            required
                            class="block w-full px-5 py-3.5 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-2xl focus:ring-0 transition-all font-medium outline-none placeholder-gray-400"
                            placeholder="0.00"
                        >
                        @error('QuantityPurchased')
                            <p class="text-sm text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total Cost -->
                    <div class="space-y-2">
                        <label for="TotalCost" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Total Cost</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 font-bold">₱</span>
                            </div>
                            <input
                                type="number"
                                step="0.01"
                                id="TotalCost"
                                name="TotalCost"
                                value="{{ old('TotalCost') }}"
                                required
                                class="block w-full pl-10 pr-5 py-3.5 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-2xl focus:ring-0 transition-all font-medium outline-none placeholder-gray-400"
                                placeholder="0.00"
                            >
                        </div>
                        @error('TotalCost')
                            <p class="text-sm text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Remarks -->
                <div class="space-y-2">
                    <label for="Remarks" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Remarks <span class="normal-case font-normal opacity-50">(Optional)</span></label>
                    <textarea
                        id="Remarks"
                        name="Remarks"
                        rows="3"
                        class="block w-full px-5 py-3.5 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-2xl focus:ring-0 transition-all font-medium outline-none placeholder-gray-400"
                        placeholder="Supplier name, receipt no., etc."
                    >{{ old('Remarks') }}</textarea>
                    @error('Remarks')
                        <p class="text-sm text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-6">
                    <button
                        type="submit"
                        class="w-full py-4 px-6 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-600/30 transition-all transform hover:scale-[1.02] active:scale-[0.98] text-lg"
                    >
                        Log Expense & Restock
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const itemSelect = document.getElementById('ItemID');
            const noticeSelect = document.getElementById('NoticeID');
            const noticeOptions = noticeSelect.querySelectorAll('option');

            itemSelect.addEventListener('change', function () {
                const selectedItemID = this.value;

                // Disable and reset notice select
                noticeSelect.disabled = true;
                noticeSelect.value = "";
                // Visual feedback for disabled state handled by CSS opacity

                // Hide all options
                noticeOptions.forEach(option => {
                    if (option.value) { // Don't hide the "None" option
                        option.classList.add('hidden');
                    }
                });

                if (selectedItemID) {
                    let hasMatchingNotices = false;
                    // Show only options that match the selected item
                    noticeOptions.forEach(option => {
                        if (option.getAttribute('data-item-id') === selectedItemID) {
                            option.classList.remove('hidden');
                            hasMatchingNotices = true;
                        }
                    });

                    // If we found matching notices, enable the dropdown
                    if (hasMatchingNotices) {
                        noticeSelect.disabled = false;
                    }
                }
            });

            // Pre-selection logic
            const selectedItemID = '{{ old('ItemID', $selectedItemID ?? null) }}';
            const selectedNoticeID = '{{ old('NoticeID', $selectedNoticeID ?? null) }}';

            if (selectedItemID) {
                itemSelect.value = selectedItemID;
                itemSelect.dispatchEvent(new Event('change'));
                if (selectedNoticeID) {
                    noticeSelect.value = selectedNoticeID;
                }
            }
        });
    </script>
@endsection