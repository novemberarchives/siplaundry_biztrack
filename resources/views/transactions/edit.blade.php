@extends('layouts.app')

@section('title', 'Update Transaction #' . $transaction->TransactionID)

@section('content')
    <!-- Main Container (Flex for side-by-side layout on desktop) -->
    <div class="flex flex-col lg:flex-row justify-center items-start gap-6 max-w-4xl mx-auto">
        
        <!-- Header & Back Link (Side on Desktop) -->
        <div class="w-full lg:w-auto lg:sticky lg:top-6 flex lg:flex-col items-center lg:items-end gap-2 lg:mt-10">
            <a href="{{ route('transactions.show', $transaction->TransactionID) }}" class="group flex items-center gap-2 text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                <span class="hidden lg:block">Back</span>
                <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-gray-100 dark:border-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span class="lg:hidden">Back to Details</span>
            </a>
        </div>

        <!-- Main Bento Card -->
        <div class="flex-1 w-full max-w-lg bg-white dark:bg-gray-800 p-6 md:p-8 rounded-[2rem] shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700">

            <header class="text-center mb-8">
                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 rounded-2xl flex items-center justify-center mx-auto mb-3 text-blue-600 dark:text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h1 class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Update Transaction
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">Order #{{ $transaction->TransactionID }}</p>
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

            <!-- Transaction Edit Form -->
            <form method="POST" action="{{ route('transactions.update', $transaction->TransactionID) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Payment Status -->
                <div class="space-y-1">
                    <label for="PaymentStatus" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Payment Status</label>
                    <div class="relative">
                        <select id="PaymentStatus" name="PaymentStatus" class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium appearance-none outline-none cursor-pointer">
                            <option value="Unpaid" {{ old('PaymentStatus', $transaction->PaymentStatus) == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="Paid" {{ old('PaymentStatus', $transaction->PaymentStatus) == 'Paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('PaymentStatus')
                        <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date Paid -->
                <div class="space-y-1">
                    <label for="DatePaid" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Date Paid <span class="normal-case font-normal opacity-50">(Optional)</span></label>
                    <input
                        type="date"
                        id="DatePaid"
                        name="DatePaid"
                        value="{{ old('DatePaid', $transaction->DatePaid) }}"
                        class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none"
                    >
                    <p class="text-[10px] text-gray-400 font-medium ml-1">Leave empty to use today's date when marking as Paid.</p>
                    @error('DatePaid')
                        <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="space-y-1">
                    <label for="Notes" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Notes <span class="normal-case font-normal opacity-50">(Optional)</span></label>
                    <textarea
                        id="Notes"
                        name="Notes"
                        rows="3"
                        class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none placeholder-gray-400"
                        placeholder="e.g., Customer requests extra softener."
                    >{{ old('Notes', $transaction->Notes) }}</textarea>
                    @error('Notes')
                        <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button
                        type="submit"
                        class="w-full py-3.5 px-6 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-600/30 transition-all transform hover:scale-[1.02] active:scale-[0.98]"
                    >
                        Update Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection