@extends('layouts.app')

@section('title', 'Transaction List | Sip Laundry')

@section('content')
    <!-- Header Strip -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Transactions</h1>
            <p class="text-sm md:text-base text-gray-500 dark:text-gray-400 font-medium">View and manage order history</p>
        </div>
        
        <!-- New Transaction Button -->
        <a href="{{ route('transactions.create') }}" 
           class="w-full md:w-auto flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-0.5 transition-all duration-200 active:scale-95 active:translate-y-0 focus:outline-none focus:ring-4 focus:ring-blue-500/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
            New Order
        </a>
    </header>

    <!-- Transaction Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-[2rem] md:rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-4 md:p-6">
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700 align-middle">
                <thead>
                    <tr>
                        <!-- Sortable Header: Transaction ID -->
                        <th scope="col" class="pl-4 pr-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">
                            <a href="{{ route('transactions.index', ['sort' => 'TransactionID', 'direction' => $currentSort == 'TransactionID' && $currentDirection == 'asc' ? 'desc' : 'asc']) }}" class="group flex items-center gap-1 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                ID
                                <span class="text-[10px]">
                                    @if($currentSort == 'TransactionID')
                                        @if($currentDirection == 'asc') ▲ @else ▼ @endif
                                    @else
                                        <span class="text-gray-300 dark:text-gray-600 group-hover:text-blue-400">▼</span>
                                    @endif
                                </span>
                            </a>
                        </th>
                        
                        <!-- Hidden on Mobile -->
                        <th scope="col" class="hidden md:table-cell px-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Date
                        </th>

                        <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Customer
                        </th>

                        <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Total
                        </th>

                        <!-- Hidden on Small Mobile -->
                        <th scope="col" class="hidden sm:table-cell px-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Payment
                        </th>

                        <!-- Hidden on Tablet -->
                        <th scope="col" class="hidden lg:table-cell px-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Progress
                        </th>

                        <!-- Hidden on Laptop -->
                        <th scope="col" class="hidden xl:table-cell px-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Staff
                        </th>

                        <th scope="col" class="relative pl-3 pr-4 py-4">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($transactions as $transaction)
                        <tr onclick="window.location='{{ route('transactions.show', $transaction->TransactionID) }}'" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group cursor-pointer">
                            
                            <!-- ID -->
                            <td class="pl-4 pr-3 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-bold">
                                    #{{ $transaction->TransactionID }}
                                </span>
                            </td>

                            <!-- Date (Hidden Mobile) -->
                            <td class="hidden md:table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-medium">
                                {{ \Carbon\Carbon::parse($transaction->DateCreated)->format('M d') }}
                            </td>

                            <!-- Customer -->
                            <td class="px-3 py-4 whitespace-nowrap">
                                <div class="font-bold text-sm text-gray-900 dark:text-white max-w-[120px] md:max-w-none truncate">
                                    {{ $transaction->customer->Name ?? 'Unknown' }}
                                </div>
                            </td>

                            <!-- Total -->
                            <td class="px-3 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                ₱{{ number_format($transaction->TotalAmount, 2) }}
                            </td>

                            <!-- Payment (Hidden Small Mobile) -->
                            <td class="hidden sm:table-cell px-3 py-4 whitespace-nowrap">
                                @if($transaction->PaymentStatus == 'Paid')
                                    <span class="px-2.5 py-0.5 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-[10px] font-bold border border-green-200 dark:border-green-800 uppercase tracking-wide">
                                        Paid
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 text-[10px] font-bold border border-yellow-200 dark:border-yellow-800 uppercase tracking-wide">
                                        Unpaid
                                    </span>
                                @endif
                            </td>

                            <!-- Progress (Hidden Tablet) -->
                            <td class="hidden lg:table-cell px-3 py-4 whitespace-nowrap">
                                @php 
                                    $total = $transaction->transactionDetails->count();
                                    $finished = $transaction->transactionDetails->whereIn('Status', ['Completed', 'Ready for Pickup'])->count();
                                @endphp

                                @if($total > 0)
                                    @if($finished == $total)
                                        <span class="flex items-center gap-1 text-blue-600 dark:text-blue-400 text-xs font-bold">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path></svg>
                                            Ready
                                        </span>
                                    @else
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-gray-500 dark:text-gray-400">{{ $finished }}/{{ $total }}</span>
                                            <div class="w-12 h-1 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                                <div class="h-full bg-blue-500 rounded-full" style="width: {{ ($finished / $total) * 100 }}%"></div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-gray-400 dark:text-gray-600 text-xs">-</span>
                                @endif
                            </td>

                            <!-- Staff (Hidden Laptop) -->
                            <td class="hidden xl:table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $transaction->user->fullname ?? '-' }}
                            </td>

                            <!-- Actions -->
                            <td class="pl-3 pr-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex justify-end">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">No transactions found.</p>
                                    <p class="text-xs text-gray-400 mt-1">Create a new order to get started.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
@endsection