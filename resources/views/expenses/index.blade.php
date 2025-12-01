@extends('layouts.app')

@section('title', 'Expense Management | Sip Laundry')

@section('content')
    <!-- Header Strip -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Expenses</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Track operational costs and inventory purchases</p>
        </div>
        
        <!-- Add New Expense Button -->
        <a href="{{ route('expenses.create') }}" 
           class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-0.5 transition-all duration-200 active:scale-95 active:translate-y-0 focus:outline-none focus:ring-4 focus:ring-blue-500/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
            Log Expense
        </a>
    </header>

    <!-- Expense Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-32">
                            Date
                        </th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Item Purchased
                        </th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Quantity
                        </th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Total Cost
                        </th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Remarks
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($expenses as $expense)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-medium">
                                {{ \Carbon\Carbon::parse($expense->Date)->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-red-50 dark:bg-red-900/20 flex-shrink-0 flex items-center justify-center text-red-500 dark:text-red-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    </div>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ $expense->item->Name ?? 'Item not found' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-medium">
                                +{{ $expense->QuantityPurchased }} {{ $expense->item->Unit ?? '' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                ₱{{ number_format($expense->TotalCost, 2) }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                {{ $expense->Remarks ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">No expenses logged yet.</p>
                                    <p class="text-xs text-gray-400 mt-1">Record purchases to track costs.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
@endsection