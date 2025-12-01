@extends('layouts.app')

@section('title', 'Customer Management | Sip Laundry')

@section('content')
    <!-- Header Strip -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Customers</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Manage customer database</p>
        </div>
        
        <!-- Add New Customer Button -->
        <a href="{{ route('customers.create') }}" 
           class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-0.5 transition-all duration-200 active:scale-95 active:translate-y-0 focus:outline-none focus:ring-4 focus:ring-blue-500/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
            Add Customer
        </a>
    </header>

    <!-- Customer Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8">
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Contact
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Joined
                        </th>
                        <th scope="col" class="relative px-6 py-4">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($customers as $customer)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-bold">
                                    #{{ $customer->CustomerID }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <!-- Initials Avatar -->
                                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-xs">
                                        {{ substr($customer->Name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ $customer->Name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-medium">
                                {{ $customer->ContactNumber }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $customer->Email ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($customer->DateCreated)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('customers.edit', $customer->CustomerID) }}" class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    <span class="sr-only">Edit</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-4m-1.294-2.294a.5.5 0 00.354-.146l2-2a.5.5 0 000-.708l-2-2a.5.5 0 00-.708 0l-2 2a.5.5 0 000 .708zM12 12V3m0 0a.5.5 0 00-.5-.5H5.5a.5.5 0 00-.5.5v9a.5.5 0 00.5.5H12z"></path></svg>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">No customers found.</p>
                                    <p class="text-xs text-gray-400 mt-1">Add a new customer to get started.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
@endsection