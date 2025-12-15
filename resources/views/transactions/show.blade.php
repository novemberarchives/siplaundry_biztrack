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
        
        <!-- Action Buttons Group -->
        <div class="flex flex-wrap items-center gap-3">
            
            <!-- SMS Button Logic -->
            @php
                $phoneRaw = preg_replace('/[^0-9]/', '', $transaction->customer->ContactNumber);
                $smsBody = "Hi " . $transaction->customer->Name . ", Sip Laundry Order #" . $transaction->TransactionID . " received. Amount: P" . number_format($transaction->TotalAmount, 2) . ".";
                
                if($transaction->PaymentStatus == 'Paid' || $transaction->transactionDetails->where('Status', 'Ready for Pickup')->count() > 0) {
                    $smsBody = "Hi " . $transaction->customer->Name . ", Order #" . $transaction->TransactionID . " is READY for pickup! Balance: P0.00. Pls claim 8am-8pm. Thanks!";
                }
            @endphp
            
            @if($transaction->customer->ContactNumber)
            <a href="sms:+{{ $phoneRaw }}?body={{ urlencode($smsBody) }}" 
               class="flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-green-500 hover:bg-green-600 text-white font-bold text-sm shadow-md transition-all border border-green-400"
               target="_blank"
               title="Text Customer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                <span class="hidden sm:inline">Text Customer</span>
            </a>
            @endif

            <!-- Print Button -->
            <a href="{{ route('transactions.print', $transaction->TransactionID) }}" 
               target="_blank"
               class="flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold text-sm shadow-md hover:bg-gray-700 dark:hover:bg-gray-200 transition-all border border-gray-700 dark:border-gray-200"
               title="Print Receipt">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span class="hidden sm:inline">Print</span>
            </a>

            <!-- Back Button -->
            <a href="{{ route('transactions.index') }}" class="group flex items-center gap-2 text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors ml-2">
                <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-gray-100 dark:border-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span class="hidden md:inline">Back to List</span>
            </a>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- LEFT COLUMN: Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Bento Card -->
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Order Details</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-1/4">Service</th>
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
                                                    {{ $detail->Status == 'Completed' ? 'bg-green-50 text-green-600 ring-gray-200/20 dark:bg-gray-700 dark:text-gray-300' : 
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
                                            
                                            <!-- NEW: Text Item Ready Button -->
                                            @if($detail->Status == 'Ready for Pickup' && $transaction->customer->ContactNumber)
                                                @php
                                                    $itemPhone = preg_replace('/[^0-9]/', '', $transaction->customer->ContactNumber);
                                                    $itemMsg = "Hi " . $transaction->customer->Name . ", your item " . $detail->service->Name . " (Order #" . $transaction->TransactionID . ") is READY for pickup! - Sip Laundry";
                                                @endphp
                                                <a href="sms:+{{ $itemPhone }}?body={{ urlencode($itemMsg) }}" 
                                                   class="text-green-500 hover:text-green-600 dark:text-green-400 dark:hover:text-green-300 transition-colors p-1"
                                                   title="Text Customer about this item">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                                </a>
                                            @endif
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
            <!-- Customer & Payment Cards (Same as before) -->
            <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Customer</h2>
                    <a href="{{ route('customers.edit', $transaction->CustomerID) }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">Edit Profile</a>
                </div>
                
                <div class="flex items-center gap-4 mb-6">
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
                <div class="text-right mb-6">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Amount</p>
                    <p class="text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">₱{{ number_format($transaction->TotalAmount, 2) }}</p>
                </div>
                <div class="flex justify-between items-center py-4 border-t border-gray-100 dark:border-gray-700">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</span>
                    @if($transaction->PaymentStatus == 'Paid')
                        <div class="text-right">
                            <span class="text-green-600 dark:text-green-400 font-bold text-sm">Paid</span>
                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $transaction->DatePaid ? \Carbon\Carbon::parse($transaction->DatePaid)->format('M d, Y') : '' }}</p>
                        </div>
                    @else
                        <span class="text-yellow-600 dark:text-yellow-400 font-bold text-sm">Unpaid</span>
                    @endif
                </div>
                @if($transaction->Notes)
                <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/10 rounded-2xl border border-yellow-100 dark:border-yellow-900/30">
                    <p class="text-xs font-bold text-yellow-800 dark:text-yellow-500 uppercase tracking-wider mb-1">Notes</p>
                    <p class="text-sm text-yellow-900 dark:text-yellow-200 italic">{{ $transaction->Notes }}</p>
                </div>
                @endif
                <a href="{{ route('transactions.edit', $transaction->TransactionID) }}" class="mt-6 flex items-center justify-center w-full py-3.5 bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-200 text-white dark:text-gray-900 font-bold rounded-2xl shadow-lg shadow-gray-900/20 dark:shadow-none transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                    Update Payment / Notes
                </a>
            </div>
        </div>
    </div>

    <!-- SUCCESS MODAL: Prompt to Print/Text after creation -->
    @if(session('newly_created'))
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 transition-opacity" id="successModal">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl max-w-sm w-full p-8 text-center space-y-6 transform scale-100 transition-transform">
            
            <div class="w-20 h-20 bg-green-100 dark:bg-green-900/30 text-green-500 rounded-full flex items-center justify-center mx-auto shadow-lg shadow-green-200 dark:shadow-none">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            
            <div>
                <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white">Order Created!</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-2">Transaction #{{ $transaction->TransactionID }} has been successfully saved.</p>
            </div>
            
            <div class="space-y-3">
                <!-- Print Receipt -->
                <a href="{{ route('transactions.print', $transaction->TransactionID) }}" target="_blank" onclick="document.getElementById('successModal').remove()"
                   class="flex items-center justify-center gap-3 w-full py-3.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-2xl font-bold text-lg hover:scale-[1.02] active:scale-95 transition-all shadow-lg hover:shadow-xl">
                   <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                   Print Receipt Now
                </a>
                
                <!-- SMS -->
                @if($transaction->customer->ContactNumber)
                    @php
                        $phoneRaw = preg_replace('/[^0-9]/', '', $transaction->customer->ContactNumber);
                        $smsBody = "Hi " . $transaction->customer->Name . ", Sip Laundry Order #" . $transaction->TransactionID . " received. Amount: P" . number_format($transaction->TotalAmount, 2) . ".";
                    @endphp
                    <a href="sms:+{{ $phoneRaw }}?body={{ urlencode($smsBody) }}" onclick="document.getElementById('successModal').remove()"
                       class="flex items-center justify-center gap-3 w-full py-3.5 bg-green-500 hover:bg-green-600 text-white rounded-2xl font-bold text-lg hover:scale-[1.02] active:scale-95 transition-all shadow-lg hover:shadow-xl shadow-green-500/30">
                       <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                       Text Customer
                    </a>
                @endif
            </div>
            
            <button onclick="document.getElementById('successModal').remove()" class="text-sm font-bold text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                Skip & Close
            </button>
        </div>
    </div>
    @endif
    <!-- End Modal -->
@endsection