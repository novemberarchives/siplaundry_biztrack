@extends('layouts.app')

@section('title', 'Edit Customer | Sip Laundry')

@section('content')
    <!-- Card Container -->
    <div class="w-full max-w-lg mx-auto space-y-6">
        
        <!-- Back Link (Positioned on top) -->
        <div class="flex items-center justify-between px-2">
            <a href="{{ route('customers.index') }}" class="group flex items-center gap-2 text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-gray-100 dark:border-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                Back to List
            </a>
        </div>

        <!-- Main Bento Card (Compact Vertical Height) -->
        <div class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-[2rem] shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700">

            <header class="text-center mb-8">
                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 rounded-2xl flex items-center justify-center mx-auto mb-3 text-blue-600 dark:text-blue-400">
                    <!-- Pencil Icon for Edit -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <h1 class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Edit Customer
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">Update details for <span class="text-blue-600 dark:text-blue-400">{{ $customer->Name }}</span>.</p>
            </header>

            <!-- Error Messages -->
            @if (session('error'))
                <div class="p-3 mb-4 text-xs text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-100 dark:border-red-900/30 font-bold flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                 <div class="p-3 mb-4 text-xs text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-100 dark:border-red-900/30 font-bold">
                    <p>Please check the inputs below.</p>
                </div>
            @endif

            <!-- Customer Edit Form -->
            <form method="POST" action="{{ route('customers.update', $customer->CustomerID) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="space-y-1">
                    <label for="Name" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Full Name</label>
                    <input
                        type="text"
                        id="Name"
                        name="Name"
                        value="{{ old('Name', $customer->Name) }}"
                        required
                        autofocus
                        class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none placeholder-gray-400"
                        placeholder="e.g. Juan Dela Cruz"
                    >
                    @error('Name')
                        <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Number -->
                <div class="space-y-1">
                    <label for="ContactNumber" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Contact Number</label>
                    <input
                        type="tel"
                        id="ContactNumber"
                        name="ContactNumber"
                        value="{{ old('ContactNumber', $customer->ContactNumber) }}"
                        required
                        pattern="^[0-9]+$"
                        title="Please enter only digits (0-9)."
                        class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none placeholder-gray-400"
                        placeholder="e.g. 09171234567"
                    >
                    @error('ContactNumber')
                        <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Address -->
                <div class="space-y-1">
                    <label for="Address" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Address <span class="normal-case font-normal opacity-50">(Optional)</span></label>
                    <input
                        type="text"
                        id="Address"
                        name="Address"
                        value="{{ old('Address', $customer->Address) }}"
                        class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none placeholder-gray-400"
                        placeholder="House No., Street, City"
                    >
                    @error('Address')
                        <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email -->
                <div class="space-y-1">
                    <label for="Email" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Email <span class="normal-case font-normal opacity-50">(Optional)</span></label>
                    <input
                        type="email"
                        id="Email"
                        name="Email"
                        value="{{ old('Email', $customer->Email) }}"
                        class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none placeholder-gray-400"
                        placeholder="client@example.com"
                    >
                    @error('Email')
                        <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button
                        type="submit"
                        class="w-full py-3.5 px-6 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-600/30 transition-all transform hover:scale-[1.02] active:scale-[0.98]"
                    >
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection