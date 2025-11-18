@extends('layouts.app')

@section('title', 'Add New Expense | Sip Laundry')

@section('content')
    <!-- Card Container -->
    <div class="w-full max-w-lg mx-auto bg-white p-8 md:p-10 rounded-xl shadow-2xl border border-indigo-100">

        <header class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">
                Log New Expense
            </h1>
            <p class="text-gray-500 mt-2">Log a new inventory purchase to automatically restock.</p>
        </header>
        
        <!-- Back to Expense List -->
        <div class="mb-6 text-center">
             <a href="{{ route('expenses.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                ← Back to Expense List
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


        <!-- Expense Create Form -->
        <form method="POST" action="{{ route('expenses.store') }}" class="space-y-6">
            @csrf

            <!-- Item Purchased (Required) -->
            <div>
                <label for="ItemID" class="block text-sm font-medium text-gray-700 mb-1">Item Purchased</label>
                <select id="ItemID" name="ItemID" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="" disabled selected>Select an inventory item...</option>
                    @foreach ($inventoryItems as $item)
                        <option value="{{ $item->ItemID }}" {{ old('ItemID') == $item->ItemID ? 'selected' : '' }}>
                            {{ $item->Name }} (Current Stock: {{ $item->Quantity }} {{ $item->Unit }})
                        </option>
                    @endforeach
                </select>
                @error('ItemID')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- NEW: Link to Reorder Notice (Optional) -->
            <div>
                <label for="NoticeID" class="block text-sm font-medium text-gray-700 mb-1">Link to Reorder Notice (Optional)</label>
                <select id="NoticeID" name="NoticeID" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50" disabled>
                    <option value="" selected>None (or select an item first)</option>
                    @foreach ($pendingNotices as $notice)
                        <option value="{{ $notice->NoticeID }}" data-item-id="{{ $notice->ItemID }}" class="hidden">
                            Notice #{{ $notice->NoticeID }} - {{ $notice->item->Name }} (Noticed: {{ \Carbon\Carbon::parse($notice->NoticeDate)->format('M d') }})
                        </option>
                    @endforeach
                </select>
                @error('NoticeID')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>


            <!-- Row for Date, Quantity, Cost -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Date -->
                <div>
                    <label for="Date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input
                        type="date"
                        id="Date"
                        name="Date"
                        value="{{ old('Date', date('Y-m-d')) }}"
                        required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                    @error('Date')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Quantity Purchased -->
                <div>
                    <label for="QuantityPurchased" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                    <input
                        type="number"
                        step="0.01"
                        id="QuantityPurchased"
                        name="QuantityPurchased"
                        value="{{ old('QuantityPurchased') }}"
                        required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="0.00"
                    >
                    @error('QuantityPurchased')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Total Cost -->
                <div>
                    <label for="TotalCost" class="block text-sm font-medium text-gray-700 mb-1">Total Cost (₱)</label>
                    <input
                        type="number"
                        step="0.01"
                        id="TotalCost"
                        name="TotalCost"
                        value="{{ old('TotalCost') }}"
                        required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="0.00"
                    >
                    @error('TotalCost')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Remarks (Optional) -->
            <div>
                <label for="Remarks" class="block text-sm font-medium text-gray-700 mb-1">Remarks (Optional)</label>
                <textarea
                    id="Remarks"
                    name="Remarks"
                    rows="3"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="e.g., Supplier name, receipt number"
                >{{ old('Remarks') }}</textarea>
                @error('Remarks')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button
                    type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-md text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Log Expense and Restock
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const itemSelect = document.getElementById('ItemID');
            const noticeSelect = document.getElementById('NoticeID');
            const noticeOptions = noticeSelect.querySelectorAll('option');

            // --- 1. EXISTING JAVASCRIPT ---
            itemSelect.addEventListener('change', function () {
                const selectedItemID = this.value;

                // Disable and reset notice select
                noticeSelect.disabled = true;
                noticeSelect.value = "";
                noticeSelect.classList.add('bg-gray-50');

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
                        noticeSelect.classList.remove('bg-gray-50');
                    }
                }
            });

            // --- 2. NEW JAVASCRIPT TO PRE-SELECT ---
            const selectedItemID = '{{ old('ItemID', $selectedItemID ?? null) }}';
            const selectedNoticeID = '{{ old('NoticeID', $selectedNoticeID ?? null) }}';

            if (selectedItemID) {
                // Pre-select the "Item Purchased" dropdown
                itemSelect.value = selectedItemID;
                
                // Manually trigger the 'change' event to run the logic above
                // This will filter and enable the "Link to Notice" dropdown
                itemSelect.dispatchEvent(new Event('change'));

                // Now that the "Link to Notice" dropdown is enabled and filtered,
                // select the specific notice we came from.
                if (selectedNoticeID) {
                    noticeSelect.value = selectedNoticeID;
                }
            }
            // --- END NEW JAVASCRIPT ---

        });
    </script>
@endsection