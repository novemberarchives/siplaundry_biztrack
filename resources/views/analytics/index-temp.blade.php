@extends('layouts.app')

@section('title', 'Revenue Analytics | Sip Laundry')

@section('content')
    <!-- Header Strip (Reduced bottom margin) -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">Analytics</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Financial performance overview.</p>
        </div>
        
        <!-- Month Navigation (Compact) -->
        <div class="flex items-center bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-1">
            <a href="{{ route('analytics.index', ['date' => $currentDate->copy()->subMonth()->format('Y-m-d')]) }}" 
               class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg text-gray-500 dark:text-gray-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            
            <span class="px-4 font-bold text-sm text-gray-800 dark:text-white min-w-[140px] text-center">
                {{ $currentDate->format('F Y') }}
            </span>
            
            <a href="{{ route('analytics.index', ['date' => $currentDate->copy()->addMonth()->format('Y-m-d')]) }}" 
               class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg text-gray-500 dark:text-gray-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </header>

    <!-- Monthly Summary Cards (Reduced Height) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        <!-- Net Profit -->
        <div class="bg-indigo-600 p-4 rounded-2xl shadow-md shadow-indigo-200 dark:shadow-none text-white flex flex-col justify-between h-28 hover:scale-[1.01] transition">
            <div class="flex justify-between items-start">
                <p class="text-indigo-100 text-xs font-bold uppercase tracking-wide">Net Profit</p>
                <div class="p-1.5 bg-white/20 rounded-lg backdrop-blur-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold tracking-tight">₱{{ number_format($monthlyProfit, 2) }}</p>
        </div>

        <!-- Revenue -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between h-28 hover:shadow-md transition group">
            <div class="flex justify-between items-start">
                <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wide">Revenue</p>
                <div class="p-1.5 bg-green-50 dark:bg-green-900/20 rounded-lg text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-green-600 dark:text-green-400 tracking-tight">₱{{ number_format($monthlyRevenue, 2) }}</p>
        </div>

        <!-- Expenses -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between h-28 hover:shadow-md transition group">
            <div class="flex justify-between items-start">
                <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wide">Expenses</p>
                <div class="p-1.5 bg-red-50 dark:bg-red-900/20 rounded-lg text-red-500 dark:text-red-400 group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-red-500 dark:text-red-400 tracking-tight">₱{{ number_format($monthlyExpenses, 2) }}</p>
        </div>

        <!-- Transactions -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between h-28 hover:shadow-md transition group">
            <div class="flex justify-between items-start">
                <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wide">Transactions</p>
                <div class="p-1.5 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $totalTransactions }}</p>
        </div>
    </div>

    <!-- Calendar Grid (Compact) -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <!-- Days of Week Header -->
        <div class="grid grid-cols-7 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="py-2 text-center text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    {{ $day }}
                </div>
            @endforeach
        </div>

        <!-- Calendar Days (Rows set to 85px) -->
        <div class="grid grid-cols-7 auto-rows-[95px] divide-x divide-y divide-gray-100 dark:divide-gray-700 border-l border-t border-gray-100 dark:border-gray-700">
            <!-- Empty cells -->
            @for ($i = 0; $i < $startDayOfWeek; $i++)
                <div class="bg-gray-50/30 dark:bg-gray-800/50"></div>
            @endfor

            <!-- Actual Days -->
            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $dateString = $currentDate->copy()->day($day)->format('Y-m-d');
                    $dayRevenue = $dailyRevenues[$dateString]->total ?? 0;
                    $dayTxCount = $dailyRevenues[$dateString]->count ?? 0;
                    $dayExpense = $dailyExpenses[$dateString]->total ?? 0;
                    $dayProfit = $dayRevenue - $dayExpense;
                    $isToday = \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d') === $dateString;
                @endphp

                <a href="{{ route('analytics.show', $dateString) }}" 
                   class="relative group p-2 transition-all hover:bg-blue-50/50 dark:hover:bg-blue-900/10 flex flex-col justify-between {{ $isToday ? 'bg-blue-50/30 dark:bg-blue-900/20 ring-inset ring-2 ring-blue-500' : '' }}">
                    
                    <!-- Date Header -->
                    <div class="flex justify-between items-start">
                        <span class="text-xs font-bold {{ $isToday ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500' }}">
                            {{ $day }}
                        </span>
                        @if($dayTxCount > 0)
                            <span class="px-1 py-0.5 rounded text-[9px] bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold leading-none">
                                {{ $dayTxCount }}
                            </span>
                        @endif
                    </div>

                    <!-- Financials (Compact Text) -->
                    <div class="flex flex-col items-end space-y-0.5">
                        @if ($dayRevenue > 0)
                            <div class="text-green-600 dark:text-green-400 font-bold text-[10px]">
                                +{{ number_format($dayRevenue, 0) }}
                            </div>
                        @endif
                        
                        @if ($dayExpense > 0)
                            <div class="text-red-500 dark:text-red-400 font-medium text-[9px]">
                                -{{ number_format($dayExpense, 0) }}
                            </div>
                        @endif

                        @if ($dayRevenue > 0 || $dayExpense > 0)
                            <div class="w-full pt-0.5 mt-0.5 border-t border-gray-100 dark:border-gray-700 text-right font-extrabold text-[10px] {{ $dayProfit >= 0 ? 'text-gray-900 dark:text-white' : 'text-red-600 dark:text-red-400' }}">
                                ₱{{ number_format($dayProfit, 0) }}
                            </div>
                        @endif
                    </div>
                </a>
            @endfor

            <!-- Fill remaining -->
            @php $remainingCells = (7 - (($startDayOfWeek + $daysInMonth) % 7)) % 7; @endphp
            @for ($i = 0; $i < $remainingCells; $i++)
                <div class="bg-gray-50/30 dark:bg-gray-800/50"></div>
            @endfor
        </div>
    </div>
@endsection