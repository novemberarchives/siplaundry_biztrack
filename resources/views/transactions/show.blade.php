@extends('layouts.app')

@section('title', 'Transaction #' . $transaction->TransactionID . ' | Sip Laundry')

@section('content')
    <!-- Header Strip -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Transaction #{{ $transaction->TransactionID }}
                </h1>
                <!-- Payment Badge -->
                @if($transaction->PaymentStatus == 'Paid')
                    <span class="px-3 py-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold border border-green-200 dark:border-green-800 uppercase tracking-wider">
                        Paid
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 text-xs font-bold border border-yellow-200 dark:border-yellow-800 uppercase tracking-wider">
                        Unpaid
                    </span>
                @endif
            </div>
            <p class="text-gray-500 dark:text-gray-400 font-medium mt-1">
                Created on {{ \Carbon\Carbon::parse($transaction->DateCreated)->format('F d, Y') }} 
                by <span class="text-blue-600 dark:text-blue-400">{{ $transaction->user->fullname ?? 'N/A' }}</span>
            </p>
        </div>
        
        <!-- Back Button -->
        <a href="{{ route('transactions.index') }}" class="group flex items-center gap-2 text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
            <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-gray-100 dark:border-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </div>
            <span class="hidden md:inline">Back to List</span>
        </a>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- LEFT COLUMN: Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Bento Card -->
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Order Details</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-1/3">Service</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Qty/Weight</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Price</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Subtotal</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @php
                                $statuses = ['Pending', 'Washing', 'Folding', 'Ready for Pickup', 'Completed'];
                            @endphp
                            
                            @forelse ($transaction->transactionDetails as $detail)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors align-top">
                                    <td class="px-4 py-4">
                                        <span class="text-sm font-bold text-gray-900 dark:text-white break-words">
                                            {{ $detail->service->Name ?? 'Service Not Found' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 font-medium">
                                        {{ $detail->Weight ? $detail->Weight . ' kg' : $detail->Quantity . ' ' . ($detail->service->Unit ?? 'item') }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        ₱{{ number_format($detail->PricePerUnit, 2) }}
                                    </td>
                                    <td class="px-4 py-4 text-sm font-bold text-gray-900 dark:text-white">
                                        ₱{{ number_format($detail->Subtotal, 2) }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <!-- Status Update Form (Styled) -->
                                        <form action="{{ route('transaction-details.updateStatus', $detail->TransactionDetailID) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            
                                            <div class="relative w-full min-w-[120px]">
                                                <select name="Status" onchange="this.form.submit()" 
                                                    class="block w-full pl-3 pr-8 py-1.5 text-xs font-bold rounded-lg border-0 ring-1 ring-inset focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6 cursor-pointer appearance-none transition-all
                                                    {{ $detail->Status == 'Completed' ? 'bg-green-50 text-gray-600 ring-gray-200/20 dark:bg-gray-700 dark:text-gray-300' : 
                                                       ($detail->Status == 'Pending' ? 'bg-gray-50 text-gray-600 ring-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:ring-gray-600' : 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-gray-700 dark:text-gray-300') }}">
                                                    @foreach ($statuses as $status)
                                                        <option value="{{ $status }}" {{ $detail->Status == $status ? 'selected' : '' }}>
                                                            {{ $status }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-400 dark:text-gray-500 italic">
                                        This transaction has no detail items.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Customer & Payment -->
        <div class="space-y-6">
            
            <!-- Customer Details Card -->
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Customer</h2>
                    <a href="{{ route('customers.edit', $transaction->CustomerID) }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">Edit Profile</a>
                </div>
                
                <div class="flex items-center gap-4 mb-6">
                    <!-- Avatar -->
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-extrabold text-lg">
                        {{ substr($transaction->customer->Name ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 dark:text-white text-lg">{{ $transaction->customer->Name ?? 'Unknown' }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $transaction->customer->ContactNumber ?? '-' }}</p>
                    </div>
                </div>
                
                <div class="space-y-3">
                    @if($transaction->customer->Email)
                        <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
                            <div class="w-6 h-6 rounded-lg bg-gray-50 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            {{ $transaction->customer->Email }}
                        </div>
                    @endif

                    @if($transaction->customer->Address)
                        <div class="flex items-start gap-3 text-sm text-gray-600 dark:text-gray-300">
                            <div class="w-6 h-6 rounded-lg bg-gray-50 dark:bg-gray-700 flex items-center justify-center text-gray-400 flex-shrink-0">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            {{ $transaction->customer->Address }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Summary Card -->
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Payment</h2>
                
                <!-- Total Display -->
                <div class="text-right mb-6">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Amount</p>
                    <p class="text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">₱{{ number_format($transaction->TotalAmount, 2) }}</p>
                </div>
                
                <!-- Status Row -->
                <div class="flex justify-between items-center py-4 border-t border-gray-100 dark:border-gray-700">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</span>
                    @if($transaction->PaymentStatus == 'Paid')
                        <div class="text-right">
                            <span class="text-green-600 dark:text-green-400 font-bold text-sm">Paid</span>
                            <p class="text-[10px] text-gray-400 mt-0.5">
                                {{ $transaction->DatePaid ? \Carbon\Carbon::parse($transaction->DatePaid)->format('M d, Y') : '' }}
                            </p>
                        </div>
                    @else
                        <span class="text-yellow-600 dark:text-yellow-400 font-bold text-sm">Unpaid</span>
                    @endif
                </div>

                <!-- Notes -->
                @if($transaction->Notes)
                <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/10 rounded-2xl border border-yellow-100 dark:border-yellow-900/30">
                    <p class="text-xs font-bold text-yellow-800 dark:text-yellow-500 uppercase tracking-wider mb-1">Notes</p>
                    <p class="text-sm text-yellow-900 dark:text-yellow-200 italic">{{ $transaction->Notes }}</p>
                </div>
                @endif

                <!-- Update Button -->
                <a href="{{ route('transactions.edit', $transaction->TransactionID) }}" class="mt-6 flex items-center justify-center w-full py-3.5 bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-200 text-white dark:text-gray-900 font-bold rounded-2xl shadow-lg shadow-gray-900/20 dark:shadow-none transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                    Update Payment / Notes
                </a>
            </div>
        </div>
    </div>
@endsection