@extends('layouts.app')

@section('title', 'Revenue Analytics | Sip Laundry')

@section('content')
    <!-- Page Header & Controls -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-3xl font-bold text-gray-900">
            Revenue Calendar
        </h1>
        
        <!-- Month Navigation -->
        <div class="flex items-center bg-white rounded-lg shadow-sm border border-gray-200 p-1">
            <a href="{{ route('analytics.index', ['date' => $currentDate->copy()->subMonth()->format('Y-m-d')]) }}" 
               class="p-2 hover:bg-gray-100 rounded-md text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <span class="px-4 font-semibold text-lg text-gray-800 min-w-[150px] text-center">
                {{ $currentDate->format('F Y') }}
            </span>
            <a href="{{ route('analytics.index', ['date' => $currentDate->copy()->addMonth()->format('Y-m-d')]) }}" 
               class="p-2 hover:bg-gray-100 rounded-md text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </div>

    <!-- Monthly Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-indigo-600 rounded-xl shadow-lg p-5 text-white">
            <p class="text-indigo-100 text-sm font-medium mb-1">Net Profit</p>
            <p class="text-2xl font-bold">₱{{ number_format($monthlyProfit, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow border border-gray-200 p-5">
            <p class="text-gray-500 text-sm font-medium mb-1">Total Revenue</p>
            <p class="text-2xl font-bold text-green-600">₱{{ number_format($monthlyRevenue, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow border border-gray-200 p-5">
            <p class="text-gray-500 text-sm font-medium mb-1">Total Expenses</p>
            <p class="text-2xl font-bold text-red-500">₱{{ number_format($monthlyExpenses, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow border border-gray-200 p-5">
            <p class="text-gray-500 text-sm font-medium mb-1">Total Transactions</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalTransactions }}</p>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <!-- Days of Week Header -->
        <div class="grid grid-cols-7 border-b border-gray-200 bg-gray-50">
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    {{ $day }}
                </div>
            @endforeach
        </div>

        <!-- Calendar Days -->
        <div class="grid grid-cols-7 auto-rows-[100px] border-l border-t border-gray-200">
            <!-- Empty cells for days before start of month -->
            @for ($i = 0; $i < $startDayOfWeek; $i++)
                <div class="bg-gray-50/50 border-b border-r border-gray-200"></div>
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

                <!-- Make the whole cell a link -->
                <a href="{{ route('analytics.show', $dateString) }}" class="relative group p-2 border-b border-r border-gray-200 transition hover:bg-blue-50 {{ $isToday ? 'bg-blue-50 ring-1 ring-inset ring-blue-200' : '' }} flex flex-col justify-between">
                    
                    <!-- Date Header -->
                    <div class="flex justify-between items-start">
                        <span class="text-sm font-medium {{ $isToday ? 'text-blue-600 font-bold' : 'text-gray-400' }}">
                            {{ $day }}
                        </span>
                        @if($dayTxCount > 0)
                            <span class="px-1.5 py-0.5 rounded text-[10px] bg-gray-100 text-gray-600 font-semibold border border-gray-200">
                                {{ $dayTxCount }} Transactions
                            </span>
                        @endif
                    </div>

                    <!-- Financials Display -->
                    <div class="flex flex-col items-end space-y-1 text-xs">
                        @if ($dayRevenue > 0)
                            <div class="text-green-600 font-semibold flex justify-between w-full">
                                <span class="text-[9px] text-gray-400 uppercase mr-1">Rev</span>
                                ₱{{ number_format($dayRevenue, 0) }}
                            </div>
                        @endif
                        
                        @if ($dayExpense > 0)
                            <div class="text-red-500 font-medium flex justify-between w-full">
                                <span class="text-[9px] text-gray-400 uppercase mr-1">Exp</span>
                                (₱{{ number_format($dayExpense, 0) }})
                            </div>
                        @endif

                        @if ($dayRevenue > 0 || $dayExpense > 0)
                            <div class="border-t border-gray-200 w-full pt-1 mt-1 text-right font-bold {{ $dayProfit >= 0 ? 'text-gray-800' : 'text-red-600' }}">
                                ₱{{ number_format($dayProfit, 0) }}
                            </div>
                        @else
                            <div class="h-full flex items-center justify-center text-gray-300 text-lg">-</div>
                        @endif
                    </div>
                </a>
            @endfor

            <!-- Fill remaining grid cells -->
            @php
                $remainingCells = (7 - (($startDayOfWeek + $daysInMonth) % 7)) % 7;
            @endphp
            @for ($i = 0; $i < $remainingCells; $i++)
                <div class="bg-gray-50/50 border-b border-r border-gray-200"></div>
            @endfor
        </div>
    </div>
@endsection