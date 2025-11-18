@extends('layouts.app')

@section('title', 'Add New Customer | Sip Laundry')

@section('content')
    <!-- Card Container -->
    <div class="w-full max-w-lg mx-auto bg-white p-8 md:p-10 rounded-xl shadow-2xl border border-indigo-100">

        <header class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">
                New Customer Registration
            </h1>
            <p class="text-gray-500 mt-2">Enter details for a new customer bringing in laundry.</p>
        </header>
        
        <!-- Back to Customers Index (Updated Link) -->
        <div class="mb-6 text-center">
             <a href="{{ route('customers.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                ← Back to Customer List
            </a>
        </div>

        <!-- Error/Success Messages -->
        @if (session('error'))
            <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-300" role="alert">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
             <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-300" role="alert">
                Please correct the form errors below.
            </div>
        @endif


        <!-- Customer Registration Form -->
        <form method="POST" action="{{ route('customers.store') }}" class="space-y-6">
            @csrf

            <!-- Name (Required) -->
            <div>
                <label for="Name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input
                    type="text"
                    id="Name"
                    name="Name"
                    value="{{ old('Name') }}"
                    required
                    autofocus
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out sm:text-sm"
                    placeholder="Customer Name"
                >
                @error('Name')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- ContactNumber (Required & Unique) -->
            <div>
                <label for="ContactNumber" class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                <input
                    type="text"
                    id="ContactNumber"
                    name="ContactNumber"
                    value="{{ old('ContactNumber') }}"
                    required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out sm:text-sm"
                    placeholder="e.g., +639123456789"
                >
                @error('ContactNumber')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Address (Optional) -->
            <div>
                <label for="Address" class="block text-sm font-medium text-gray-700 mb-1">Address (Optional)</label>
                <input
                    type="text"
                    id="Address"
                    name="Address"
                    value="{{ old('Address') }}"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out sm:text-sm"
                    placeholder="Street, City"
                >
                @error('Address')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</row>
                @enderror
            </div>
            
            <!-- Email (Optional, Unique) -->
            <div>
                <label for="Email" class="block text-sm font-medium text-gray-700 mb-1">Email (Optional)</label>
                <input
                    type="email"
                    id="Email"
                    name="Email"
                    value="{{ old('Email') }}"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out sm:text-sm"
                    placeholder="email@example.com"
                >
                @error('Email')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</row>
                @enderror
            </div>

            <div>
                <button
                    type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-md text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 ease-in-out transform hover:scale-[1.01]"
                >
                    Register Customer
                </button>
            </div>
        </form>
    </div>
@endsection