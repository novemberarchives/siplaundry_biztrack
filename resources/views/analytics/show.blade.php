@extends('layouts.app')

@section('title', 'Daily Analytics: ' . $date->format('M d, Y'))

@section('content')
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                {{ $date->format('F d, Y') }}
            </h1>
            <p class="text-gray-500 text-sm mt-1">Daily Financial Breakdown</p>
        </div>
        <a href="{{ route('analytics.index', ['date' => $date->format('Y-m-d')]) }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
            ← Back to Calendar
        </a>
    </div>

    <!-- Daily Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-green-50 p-6 rounded-xl border border-green-200">
            <p class="text-sm text-green-600 font-medium uppercase">Revenue</p>
            <p class="text-3xl font-bold text-green-700 mt-1">₱{{ number_format($totalRevenue, 2) }}</p>
        </div>
        <div class="bg-red-50 p-6 rounded-xl border border-red-200">
            <p class="text-sm text-red-600 font-medium uppercase">Expenses</p>
            <p class="text-3xl font-bold text-red-700 mt-1">₱{{ number_format($totalExpenses, 2) }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-sm text-gray-500 font-medium uppercase">Net Profit</p>
            <p class="text-3xl font-bold {{ $netProfit >= 0 ? 'text-gray-800' : 'text-red-600' }}">
                ₱{{ number_format($netProfit, 2) }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Transactions List -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h2 class="font-semibold text-gray-700">Transactions (Income)</h2>
                <span class="text-xs font-bold bg-green-100 text-green-800 px-2 py-1 rounded-full">
                    {{ $transactions->count() }} Orders
                </span>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse ($transactions as $transaction)
                    <li class="px-6 py-4 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    #{{ $transaction->TransactionID }} - {{ $transaction->customer->Name }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    Processed by {{ $transaction->user->fullname ?? 'Unknown' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-green-600">
                                    +₱{{ number_format($transaction->TotalAmount, 2) }}
                                </p>
                                <a href="{{ route('transactions.show', $transaction->TransactionID) }}" class="text-xs text-indigo-600 hover:underline">View</a>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-6 py-8 text-center text-sm text-gray-500 italic">
                        No paid transactions for this date.
                    </li>
                @endforelse
            </ul>
        </div>

        <!-- Expenses List -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h2 class="font-semibold text-gray-700">Expenses (Costs)</h2>
                <span class="text-xs font-bold bg-red-100 text-red-800 px-2 py-1 rounded-full">
                    {{ $expenses->count() }} Items
                </span>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse ($expenses as $expense)
                    <li class="px-6 py-4 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $expense->item->Name ?? 'Unknown Item' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $expense->QuantityPurchased }} {{ $expense->item->Unit ?? '' }} purchased
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-red-600">
                                    -₱{{ number_format($expense->TotalCost, 2) }}
                                </p>
                            </div>
                        </div>
                        @if($expense->Remarks)
                            <p class="mt-1 text-xs text-gray-500 bg-gray-50 p-1 rounded border border-gray-100">
                                Note: {{ $expense->Remarks }}
                            </p>
                        @endif
                    </li>
                @empty
                    <li class="px-6 py-8 text-center text-sm text-gray-500 italic">
                        No expenses logged for this date.
                    </li>
                @endforelse
            </ul>
        </div>

    </div>
@endsection