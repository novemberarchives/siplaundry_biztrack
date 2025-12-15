@extends('layouts.app')

@section('title', 'Service Management | Sip Laundry')

@section('content')
    <!-- Header Strip -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Services</h1>
            <p class="text-sm md:text-base text-gray-500 dark:text-gray-400 font-medium">Manage service menu and pricing</p>
        </div>
        
        <!-- Add New Service Button (Manager Only) -->
        @if(Auth::user()->role === 'Manager')
        <a href="{{ route('services.create') }}" 
           class="w-full md:w-auto flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-0.5 transition-all duration-200 active:scale-95 active:translate-y-0 focus:outline-none focus:ring-4 focus:ring-blue-500/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
            Add Service
        </a>
        @endif
    </header>

    <!-- Service Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-[2rem] md:rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-4 md:p-6">
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700 align-middle">
                <thead>
                    <tr>
                        <th scope="col" class="pl-4 pr-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-16 whitespace-nowrap">
                            ID
                        </th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Base Price
                        </th>
                        <th scope="col" class="hidden sm:table-cell px-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Unit
                        </th>
                        <th scope="col" class="hidden md:table-cell px-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Minimum
                        </th>
                        <th scope="col" class="hidden lg:table-cell px-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Description
                        </th>
                        @if(Auth::user()->role === 'Manager')
                        <th scope="col" class="relative pl-3 pr-4 py-4 w-24">
                            <span class="sr-only">Actions</span>
                        </th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($services as $service)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                            <!-- ID -->
                            <td class="pl-4 pr-3 py-4 whitespace-nowrap">
                                <span class="px-0.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-bold">
                                    #{{ $service->ServiceID }}
                                </span>
                            </td>
                            
                            <!-- Name -->
                            <td class="px-3 py-4">
                                <div class="flex items-center gap-3 min-w-[100px]">
                                    <div class="hidden sm:flex w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex-shrink-0 items-center justify-center text-blue-600 dark:text-blue-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    </div>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white leading-tight">
                                        {{ $service->Name }}
                                    </span>
                                </div>
                            </td>

                            <!-- Base Price -->
                            <td class="px-2 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900 dark:text-white">
                                    ₱{{ number_format($service->BasePrice, 2) }}
                                    <!-- Mobile-only unit display -->
                                    <span class="sm:hidden text-xs font-normal text-gray-500 dark:text-gray-400">/ {{ $service->Unit }}</span>
                                </div>
                            </td>
                            
                            <!-- Unit (Hidden Mobile) -->
                            <td class="hidden sm:table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <span class="px-2 py-1 rounded-md bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-600 text-xs font-medium">
                                    {{ $service->Unit }}
                                </span>
                            </td>
                            
                            <!-- Minimum (Hidden Small Tablet) -->
                            <td class="hidden md:table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $service->MinQuantity ? $service->MinQuantity . ' ' . $service->Unit : '-' }}
                            </td>
                            
                            <!-- Description (Hidden Tablet/Laptop) -->
                            <td class="hidden lg:table-cell px-3 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                {{ $service->Description ?? '-' }}
                            </td>
                            
                            <!-- Actions -->
                            @if(Auth::user()->role === 'Manager')
                            <td class="pl-3 pr-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('services.edit', $service->ServiceID) }}" class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    <form action="{{ route('services.destroy', $service->ServiceID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">No services found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
@endsection