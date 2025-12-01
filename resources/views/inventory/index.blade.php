@extends('layouts.app')

@section('title', 'Inventory Management | Sip Laundry')

@section('content')
    <!-- Header Strip -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Inventory</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Track inventory levels and reorder points</p>
        </div>
        
        <!-- Add New Item Button (Manager Only) -->
        @if(Auth::user()->role === 'Manager')
        <a href="{{ route('inventory.create') }}" 
           class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-0.5 transition-all duration-200 active:scale-95 active:translate-y-0 focus:outline-none focus:ring-4 focus:ring-blue-500/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
            Add Item
        </a>
        @endif
    </header>

    <!-- Inventory Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-100 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-20">
                            ID
                        </th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Item Name
                        </th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Category
                        </th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Stock Level
                        </th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Reorder Point
                        </th>
                        <th scope="col" class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Unit Cost
                        </th>
                        @if(Auth::user()->role === 'Manager')
                        <th scope="col" class="relative px-4 py-4 w-24">
                            <span class="sr-only">Actions</span>
                        </th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($items as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-bold">
                                    #{{ $item->ItemID }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3 min-w-[150px]">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex-shrink-0 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    </div>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white leading-tight">
                                        {{ $item->Name }}
                                    </span>
                                </div>
                            </td>
                            <!-- Text allowed to wrap -->
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                <span class="px-2 py-1 rounded-md bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-600 text-xs font-medium inline-block">
                                    {{ $item->Category ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-sm font-bold {{ $item->Quantity <= $item->ReorderLevel ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                        {{ $item->Quantity }} {{ $item->Unit }}
                                    </span>
                                    @if($item->Quantity <= $item->ReorderLevel)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800 uppercase tracking-wide whitespace-nowrap">
                                            Low
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 font-medium">
                                {{ $item->ReorderLevel }} {{ $item->Unit }}
                            </td>
                            <td class="px-4 py-4 text-sm font-bold text-gray-900 dark:text-white">
                                ₱{{ number_format($item->UnitPrice, 2) }} <span class="text-gray-400 font-normal text-xs">/{{ $item->Unit }}</span>
                            </td>
                            
                            @if(Auth::user()->role === 'Manager')
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium flex items-center justify-end gap-3">
                                <a href="{{ route('inventory.edit', $item->ItemID) }}" class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('inventory.destroy', $item->ItemID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">No inventory items found.</p>
                                    @if(Auth::user()->role === 'Manager')
                                        <p class="text-xs text-gray-400 mt-1">Add an item to start tracking stock.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
@endsection