@extends('layouts.app')

@section('title', 'Operations Dashboard | Sip Laundry')

@section('content')
    <!-- Header & Quick Actions -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-3xl font-bold text-gray-900">Orders</h1>
        <div class="flex gap-2">
            <a href="{{ route('transactions.create') }}" class="flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Transaction
            </a>
        </div>
    </div>

    <!-- Alert Section (Only shows if there are issues) -->
    @if($lowStockCount > 0)
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 flex justify-between items-center shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0 text-red-500">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        <span class="font-bold">Attention:</span> You have {{ $lowStockCount }} low stock alert(s).
                    </p>
                </div>
            </div>
            <a href="{{ route('reorder-notices.index') }}" class="text-sm font-medium text-red-600 hover:text-red-500 hover:underline">View Alerts →</a>
        </div>
    @endif

    <!-- Main Operations Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- LEFT: Current Work Queue (Takes up 2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        Current Work Queue
                    </h2>
                    <span class="text-xs font-medium bg-blue-100 text-blue-800 px-2.5 py-0.5 rounded-full">Next 10 Jobs</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($activeJobs as $job)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $job->transaction->TransactionID }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $job->transaction->customer->Name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $job->service->Name }} 
                                        <span class="text-gray-400 text-xs">({{ $job->Weight ? $job->Weight . 'kg' : $job->Quantity . 'pcs' }})</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($job->Status == 'Pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Pending</span>
                                        @elseif($job->Status == 'Washing')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 animate-pulse">Washing</span>
                                        @elseif($job->Status == 'Folding')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800 animate-pulse">Folding</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('transactions.show', $job->TransactionID) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">Update</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500 italic">
                                        No active jobs.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- RIGHT: Ready for Pickup & Today's Stats -->
        <div class="space-y-6">
            
            <!-- Ready for Pickup Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-green-50 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        Ready for Pickup
                    </h2>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($readyJobs as $job)
                        <div class="p-4 hover:bg-gray-50 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-gray-800 text-sm">{{ $job->transaction->customer->Name }}</p>
                                    <p class="text-xs text-gray-500">Order #{{ $job->transaction->TransactionID }} • {{ $job->service->Name }}</p>
                                </div>
                                <a href="{{ route('transactions.show', $job->TransactionID) }}" class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded hover:bg-green-200">
                                    View
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-sm text-gray-500 italic">
                            No items waiting for pickup.
                        </div>
                    @endforelse
                </div>
                @if($readyJobs->isNotEmpty())
                    <div class="bg-gray-50 px-6 py-2 text-center border-t border-gray-200">
                        <a href="{{ route('transactions.index') }}" class="text-xs text-gray-500 hover:text-gray-700">View all transactions</a>
                    </div>
                @endif
            </div>

            <!-- Simple Revenue Stat -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Today's Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($todaysRevenue, 2) }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

        </div>
    </div>
@endsection