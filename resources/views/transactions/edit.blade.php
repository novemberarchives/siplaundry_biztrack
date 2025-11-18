@extends('layouts.app')

@section('title', 'Update Transaction #' . $transaction->TransactionID)

@section('content')
    <!-- Card Container -->
    <div class="w-full max-w-lg mx-auto bg-white p-8 md:p-10 rounded-xl shadow-2xl border border-indigo-100">

        <header class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">
                Update Transaction #{{ $transaction->TransactionID }}
            </h1>
            <p class="text-gray-500 mt-2">Update payment status and notes.</p>
        </header>
        
        <!-- Back to Transaction Detail -->
        <div class="mb-6 text-center">
             <a href="{{ route('transactions.show', $transaction->TransactionID) }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                ← Back to Transaction Details
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

        <!-- Transaction Edit Form -->
        <form method="POST" action="{{ route('transactions.update', $transaction->TransactionID) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Payment Status -->
            <div>
                <label for="PaymentStatus" class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                <select id="PaymentStatus" name="PaymentStatus" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="Unpaid" {{ old('PaymentStatus', $transaction->PaymentStatus) == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="Paid" {{ old('PaymentStatus', $transaction->PaymentStatus) == 'Paid' ? 'selected' : '' }}>Paid</option>
                </select>
                @error('PaymentStatus')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date Paid -->
            <div>
                <label for="DatePaid" class="block text-sm font-medium text-gray-700 mb-1">Date Paid (Optional)</label>
                <input
                    type="date"
                    id="DatePaid"
                    name="DatePaid"
                    value="{{ old('DatePaid', $transaction->DatePaid) }}"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                >
                <p class="text-xs text-gray-500 mt-1">If empty, will default to today when "Paid" is selected.</p>
                @error('DatePaid')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="Notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                <textarea id="Notes" name="Notes" rows="3" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., Customer requests extra softener.">{{ old('Notes', $transaction->Notes) }}</textarea>
                @error('Notes')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button
                    type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-md text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200"
                >
                    Update Transaction
                </button>
            </div>
        </form>
    </div>
@endsection