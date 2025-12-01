@extends('layouts.app')

@section('title', 'Daily Analytics: ' . $date->format('M d, Y'))

@section('content')
    <!-- Header Strip -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                {{ $date->format('F d, Y') }}
            </h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium mt-1">Daily Financial Breakdown</p>
        </div>
        
        <!-- Back Button -->
        <a href="{{ route('analytics.index', ['date' => $date->format('Y-m-d')]) }}" class="group flex items-center gap-2 text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
            <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-gray-100 dark:border-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </div>
            <span class="hidden md:inline">Back to Calendar</span>
        </a>
    </header>

    <!-- Daily Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Revenue -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between h-40 hover:shadow-md transition group">
            <div class="flex justify-between items-start">
                <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wide">Revenue</p>
                <div class="p-2 bg-green-50 dark:bg-green-900/20 rounded-xl text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold text-green-600 dark:text-green-400 tracking-tight">₱{{ number_format($totalRevenue, 2) }}</p>
        </div>

        <!-- Expenses -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between h-40 hover:shadow-md transition group">
            <div class="flex justify-between items-start">
                <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wide">Expenses</p>
                <div class="p-2 bg-red-50 dark:bg-red-900/20 rounded-xl text-red-500 dark:text-red-400 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold text-red-500 dark:text-red-400 tracking-tight">₱{{ number_format($totalExpenses, 2) }}</p>
        </div>

        <!-- Net Profit -->
        <div class="bg-indigo-600 p-6 rounded-[2rem] shadow-lg shadow-indigo-200 dark:shadow-none text-white flex flex-col justify-between h-40 hover:scale-[1.02] transition">
            <div class="flex justify-between items-start">
                <p class="text-indigo-100 text-sm font-bold uppercase tracking-wide">Net Profit</p>
                <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m-2-4h4"></path></svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold tracking-tight {{ $netProfit < 0 ? 'text-red-200' : '' }}">
                ₱{{ number_format($netProfit, 2) }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Transactions List -->
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden h-fit">
            <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Transactions</h2>
                <span class="text-xs font-bold bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 px-3 py-1 rounded-full uppercase tracking-wide">
                    {{ $transactions->count() }} Orders
                </span>
            </div>
            
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($transactions as $transaction)
                    <div class="px-8 py-5 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors flex justify-between items-center group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-500 dark:text-gray-400 group-hover:bg-green-100 dark:group-hover:bg-green-900/30 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">
                                #{{ $transaction->TransactionID }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $transaction->customer->Name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    By {{ $transaction->user->fullname ?? 'Unknown' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-green-600 dark:text-green-400">
                                +₱{{ number_format($transaction->TotalAmount, 2) }}
                            </p>
                            <a href="{{ route('transactions.show', $transaction->TransactionID) }}" class="text-xs font-bold text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">View</a>
                        </div>
                    </div>
                @empty
                    <div class="px-8 py-12 text-center">
                        <p class="text-sm text-gray-400 dark:text-gray-500 font-medium italic">No paid transactions for this date.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Expenses List -->
        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden h-fit">
            <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Expenses</h2>
                <span class="text-xs font-bold bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-3 py-1 rounded-full uppercase tracking-wide">
                    {{ $expenses->count() }} Items
                </span>
            </div>
            
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($expenses as $expense)
                    <div class="px-8 py-5 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group">
                        <div class="flex justify-between items-center mb-1">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 dark:text-gray-500 group-hover:bg-red-100 dark:group-hover:bg-red-900/30 group-hover:text-red-500 dark:group-hover:text-red-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ $expense->item->Name ?? 'Unknown Item' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $expense->QuantityPurchased }} {{ $expense->item->Unit ?? '' }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-red-500 dark:text-red-400">
                                    -₱{{ number_format($expense->TotalCost, 2) }}
                                </p>
                            </div>
                        </div>
                        @if($expense->Remarks)
                            <div class="mt-2 ml-14 p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-100 dark:border-gray-600">
                                <p class="text-xs text-gray-500 dark:text-gray-400 italic">
                                    "{{ $expense->Remarks }}"
                                </p>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="px-8 py-12 text-center">
                        <p class="text-sm text-gray-400 dark:text-gray-500 font-medium italic">No expenses logged for this date.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
@endsection     