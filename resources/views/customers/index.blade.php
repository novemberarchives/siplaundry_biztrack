@extends('layouts.app')

@section('title', 'Customer Management | Sip Laundry')

@section('content')
    <!-- Header Strip -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Customers</h1>
            <p class="text-sm md:text-base text-gray-500 dark:text-gray-400 font-medium">Manage your client database.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <!-- Search Form -->
            <form action="{{ route('customers.index') }}" method="GET" class="w-full sm:w-auto sm:flex-1 md:w-64">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ $search ?? '' }}" class="block w-full pl-10 {{ !empty($search) ? 'pr-10' : 'pr-3' }} py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl leading-5 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm shadow-sm" placeholder="Search customers...">
                    
                    @if(!empty($search))
                        <a href="{{ route('customers.index') }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" title="Clear Search">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </div>
            </form>

            <!-- Add New Customer Button -->
            <a href="{{ route('customers.create') }}" 
               class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-0.5 transition-all duration-200 active:scale-95 active:translate-y-0 focus:outline-none focus:ring-4 focus:ring-blue-500/20 whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                Add Customer
            </a>
        </div>
    </header>

    <!-- Customer Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-[2rem] md:rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-4 md:p-6">
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700 align-middle">
                <thead>
                    <tr>
                        <th scope="col" class="pl-4 pr-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-20 whitespace-nowrap">
                            ID
                        </th>
                        <th scope="col" class="px-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="hidden sm:table-cell px-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Contact
                        </th>
                        <th scope="col" class="hidden md:table-cell px-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="hidden lg:table-cell px-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                            Joined
                        </th>
                        <th scope="col" class="relative pl-3 pr-4 py-4 w-24">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($customers as $customer)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                            
                            <!-- ID -->
                            <td class="pl-4 pr-3 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-bold">
                                    #{{ $customer->CustomerID }}
                                </span>
                            </td>

                            <!-- Name -->
                            <td class="px-3 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="hidden xs:flex w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex-shrink-0 items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-xs">
                                        {{ substr($customer->Name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white leading-tight">
                                        {{ $customer->Name }}
                                    </span>
                                </div>
                            </td>

                            <!-- Contact (Hidden Small Mobile) -->
                            <td class="hidden sm:table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-medium">
                                {{ $customer->ContactNumber }}
                            </td>

                            <!-- Email (Hidden Mobile/Tablet) -->
                            <td class="hidden md:table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $customer->Email ?? '-' }}
                            </td>

                            <!-- Joined (Hidden Laptop) -->
                            <td class="hidden lg:table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($customer->DateCreated)->format('M d, Y') }}
                            </td>

                            <!-- Actions -->
                            <td class="pl-3 pr-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('customers.edit', $customer->CustomerID) }}" class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors inline-block p-1" title="Edit Customer">
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
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">No customers matching '{{ $search }}' found.</p>
                                    <a href="{{ route('customers.index') }}" class="text-xs text-blue-600 hover:underline mt-2">Clear Search</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
@endsection