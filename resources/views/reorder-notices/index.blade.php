@extends('layouts.app')

@section('title', 'Reorder Notices | Sip Laundry')

@section('content')
    <!-- Header Strip -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Reorder Notices</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Alerts for low inventory levels.</p>
        </div>
        
        <!-- Back to Inventory Button -->
        <a href="{{ route('inventory.index') }}" 
           class="group flex items-center gap-2 text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
            <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-gray-100 dark:border-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </div>
            <span class="hidden md:inline">Back to Inventory</span>
        </a>
    </header>

    <!-- Reorder Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Item Name
                        </th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Stock Level
                        </th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Reorder Level
                        </th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Date Noticed
                        </th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Date Resolved
                        </th>
                        <th scope="col" class="relative px-4 py-4">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($notices as $notice)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($notice->Status == 'Pending')
                                    <span class="px-3 py-1 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 text-xs font-bold border border-yellow-200 dark:border-yellow-800 uppercase tracking-wider animate-pulse">
                                        Pending
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-bold border border-green-200 dark:border-green-800 uppercase tracking-wider">
                                        Resolved
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <!-- Icon Avatar -->
                                    <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex-shrink-0 flex items-center justify-center text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    </div>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white leading-tight">
                                        {{ $notice->item->Name ?? 'Item not found' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold {{ ($notice->item->Quantity ?? 0) <= ($notice->item->ReorderLevel ?? 0) ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                    {{ $notice->item->Quantity ?? 'N/A' }} {{ $notice->item->Unit ?? '' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-medium">
                                {{ $notice->item->ReorderLevel ?? 'N/A' }} {{ $notice->item->Unit ?? '' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-medium">
                                {{ \Carbon\Carbon::parse($notice->NoticeDate)->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $notice->ResolvedDate ? \Carbon\Carbon::parse($notice->ResolvedDate)->format('M d, Y') : '-' }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($notice->Status == 'Pending')
                                    <a href="{{ route('expenses.create', ['notice_id' => $notice->NoticeID]) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 font-bold underline decoration-2 decoration-blue-200 hover:decoration-blue-600 transition-all">
                                        Log Purchase
                                    </a>
                                @else
                                    <span class="text-gray-300 dark:text-gray-600 italic text-xs">Resolved</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 bg-green-50 dark:bg-green-900/20 rounded-full flex items-center justify-center mb-3 text-green-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">All stocks are healthy.</p>
                                    <p class="text-xs text-gray-400 mt-1">No reorder notices found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
@endsection