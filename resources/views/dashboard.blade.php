@extends('layouts.app')

@section('title', 'Operations Dashboard | Sip Laundry')

@section('content')
    <!-- Header Strip -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Dashboard</h1>
            <p class="text-sm md:text-base text-gray-500 dark:text-gray-400 font-medium">Overview for {{ \Carbon\Carbon::now('Asia/Manila')->format('l, F j') }}</p>
        </div>
        
        <div class="flex flex-wrap gap-3 w-full md:w-auto">
            <!-- Alert Pill -->
            @if(isset($lowStockCount) && $lowStockCount > 0)
            <a href="{{ route('reorder-notices.index') }}" class="flex-1 md:flex-none bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 px-4 py-2.5 rounded-xl flex items-center justify-center gap-2 text-sm font-bold hover:bg-red-200 dark:hover:bg-red-900/50 transition-all animate-pulse border border-red-200 dark:border-red-800">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                {{ $lowStockCount }} Alerts
            </a>
            @endif

            <!-- New Transaction Button (Enhanced UX) -->
            <a href="{{ route('transactions.create') }}" 
               class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-0.5 transition-all duration-200 active:scale-95 active:translate-y-0 focus:outline-none focus:ring-4 focus:ring-blue-500/20 whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                New Order
            </a>
        </div>
    </header>

    <!-- Top Grid: KPI Cards (Adjusted to 3 columns) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6">

        <!-- WIDGET 1: Daily Revenue (Green Bento Style) -->
        <div class="bg-white dark:bg-gray-800 p-5 md:p-6 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between h-40 md:h-48 hover:shadow-md transition group">
            <div class="flex justify-between items-start">
                <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-2xl text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Today</span>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">₱{{ number_format($todaysRevenue ?? 0, 2) }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">Total Revenue</p>
            </div>
        </div>

        <!-- WIDGET 2: Active Jobs (Blue/Indigo Bento Style) -->
        <div class="bg-indigo-600 p-5 md:p-6 rounded-[2rem] shadow-lg shadow-indigo-200 dark:shadow-none text-white flex flex-col justify-between h-40 md:h-48 hover:scale-[1.02] transition">
            <div class="flex justify-between items-start">
                <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
            </div>
            <div>
                <h2 class="text-4xl font-extrabold">{{ $activeJobs->count() ?? 0 }}</h2>
                <p class="text-sm text-indigo-100 mt-1 font-medium">Jobs In Progress</p>
            </div>
        </div>

        <!-- WIDGET 3: Ready for Pickup Count (Simple White Card) -->
        <div class="bg-white dark:bg-gray-800 p-5 md:p-6 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between h-40 md:h-48 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-2xl text-blue-600 dark:text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                </div>
            </div>
            <div>
                <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white">{{ $readyJobs->count() ?? 0 }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">Ready for Pickup</p>
            </div>
        </div>

    </div>

    <!-- Main Content Grid: Work Queue & Pickup List -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LEFT: Work Queue (Bento List Style - Spans 2 Columns) -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-[2rem] md:rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-5 md:p-8 min-h-[400px]">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white">Current Work Queue</h3>
                <span class="text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-3 py-1 rounded-full uppercase tracking-wide">Priority</span>
            </div>

            <div class="space-y-3">
                @forelse($activeJobs as $job)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-2xl transition border border-transparent hover:border-gray-100 dark:hover:border-gray-600 group cursor-pointer gap-3 sm:gap-0"
                         onclick="window.location='{{ route('transactions.show', $job->TransactionID) }}'">
                        
                        <div class="flex items-center gap-4">
                            <!-- ID Badge -->
                            <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 flex-shrink-0 flex items-center justify-center font-bold text-sm group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                #{{ $job->transaction->TransactionID }}
                            </div>
                            
                            <!-- Info -->
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">{{ $job->transaction->customer->Name }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                                    {{ $job->service->Name }} • <span class="text-gray-400">{{ $job->Weight ? $job->Weight . 'kg' : $job->Quantity . 'pcs' }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="flex items-center justify-between sm:justify-end gap-4 w-full sm:w-auto pl-16 sm:pl-0">
                            @if($job->Status == 'Pending')
                                <span class="px-4 py-2 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-bold uppercase tracking-wider">Pending</span>
                            @elseif($job->Status == 'Washing')
                                <span class="px-4 py-2 rounded-xl bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-bold uppercase tracking-wider animate-pulse">Washing</span>
                            @elseif($job->Status == 'Folding')
                                <span class="px-4 py-2 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-xs font-bold uppercase tracking-wider animate-pulse">Folding</span>
                            @endif
                            
                            <!-- Arrow Icon -->
                            <div class="text-gray-300 dark:text-gray-600 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-64 text-center">
                        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-700/50 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">All caught up! No active jobs.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- RIGHT: Ready for Pickup List (Replaces "Staff on Duty") -->
        <div class="bg-white dark:bg-gray-800 rounded-[2rem] md:rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-5 md:p-8 h-fit">
            <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white mb-6">Ready for Pickup</h3>
            
            <div class="space-y-4">
                @forelse($readyJobs as $job)
                    <div class="p-5 bg-green-50 dark:bg-green-900/10 border border-green-100 dark:border-green-900/30 rounded-2xl hover:shadow-sm transition group">
                        
                        <div class="flex justify-between items-start mb-2" onclick="window.location='{{ route('transactions.show', $job->TransactionID) }}'" style="cursor: pointer;">
                            <span class="text-xs font-bold bg-white dark:bg-gray-800 text-green-600 dark:text-green-400 px-2 py-1 rounded-lg shadow-sm">
                                #{{ $job->transaction->TransactionID }}
                            </span>
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-ping"></div>
                        </div>
                        
                        <div onclick="window.location='{{ route('transactions.show', $job->TransactionID) }}'" style="cursor: pointer;">
                            <h4 class="font-bold text-gray-900 dark:text-white">{{ $job->transaction->customer->Name }}</h4>
                            <p class="text-xs text-green-700 dark:text-green-300 mt-1 font-medium">{{ $job->service->Name }}</p>
                        </div>
                        
                        <!-- Actions: Text & Mark Collected -->
                        <div class="mt-4 pt-3 border-t border-green-200 dark:border-green-900/30 flex gap-2">
                            <!-- SMS Button -->
                            @if($job->transaction->customer->ContactNumber)
                                @php
                                    $phoneRaw = preg_replace('/[^0-9]/', '', $job->transaction->customer->ContactNumber);
                                    $smsBody = "Hi " . $job->transaction->customer->Name . ", your item " . $job->service->Name . " (Order #" . $job->transaction->TransactionID . ") is READY for pickup! - Sip Laundry";
                                @endphp
                                <a href="sms:+{{ $phoneRaw }}?body={{ urlencode($smsBody) }}" 
                                   class="flex items-center justify-center w-10 h-10 bg-green-200 dark:bg-green-800 text-green-700 dark:text-green-200 rounded-xl hover:bg-green-300 dark:hover:bg-green-700 transition-colors"
                                   title="Text Customer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                </a>
                            @endif

                            <!-- Mark Collected Button -->
                            <form action="{{ route('transactions.complete', $job->transaction->TransactionID) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PUT')
                                <button type="submit" 
                                        onclick="return confirm('Confirm that Order #{{ $job->transaction->TransactionID }} is paid and has been handed to the customer?')"
                                        class="w-full flex items-center justify-center gap-2 py-2 bg-white dark:bg-gray-700 text-green-600 dark:text-green-400 rounded-xl text-xs font-bold shadow-sm border border-green-100 dark:border-gray-600 hover:bg-green-600 hover:text-white dark:hover:bg-green-500 dark:hover:text-white transition-all duration-200 h-10">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Mark Collected
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center">
                        <p class="text-sm text-gray-400 dark:text-gray-500 font-medium">No items waiting.</p>
                    </div>
                @endforelse
            </div>
            
            @if($readyJobs->isNotEmpty())
                <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700 text-center">
                    <a href="{{ route('transactions.index') }}" class="text-sm font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">View All Transactions</a>
                </div>
            @endif
        </div>

    </div>
@endsection