@extends('layouts.app')

@section('title', 'Add New Staff | Sip Laundry')

@section('content')
    <!-- Main Container -->
    <div class="w-full max-w-lg mx-auto space-y-6">
        
        <!-- Header & Back Link (Positioned on top) -->
        <div class="flex items-center justify-between px-2">
            <a href="{{ route('dashboard') }}" class="group flex items-center gap-2 text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-gray-100 dark:border-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                Back to Dashboard
            </a>
        </div>

        <!-- Main Bento Card -->
        <div class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-[2rem] shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700">

            <header class="text-center mb-8">
                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 rounded-2xl flex items-center justify-center mx-auto mb-3 text-blue-600 dark:text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <h1 class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Add Staff Account
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">Create access for a new employee.</p>
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

            <!-- Registration Form -->
            <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
                @csrf

                <!-- Full Name -->
                <div class="space-y-1">
                    <label for="fullname" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Full Name</label>
                    <input
                        type="text"
                        id="fullname"
                        name="fullname"
                        value="{{ old('fullname') }}"
                        required
                        autofocus
                        class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none placeholder-gray-400"
                        placeholder="e.g. Jane Doe"
                    >
                    @error('fullname')
                        <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username -->
                <div class="space-y-1">
                    <label for="username" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Username (Login ID)</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        required
                        class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none placeholder-gray-400"
                        placeholder="Must be unique"
                    >
                    @error('username')
                        <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role Selection -->
                <div class="space-y-1">
                    <label for="role" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Role</label>
                    <div class="relative">
                        <select
                            id="role"
                            name="role"
                            required
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none appearance-none cursor-pointer"
                        >
                            <option value="" disabled selected>Select a role...</option>
                            <option value="Staff" {{ old('role') == 'Staff' ? 'selected' : '' }}>Staff</option>
                            <option value="Manager" {{ old('role') == 'Manager' ? 'selected' : '' }}>Manager</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('role')
                        <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-1">
                    <label for="password" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider ml-1">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all text-sm font-medium outline-none placeholder-gray-400"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="text-xs text-red-500 font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button
                        type="submit"
                        class="w-full py-3.5 px-6 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-600/30 transition-all transform hover:scale-[1.02] active:scale-[0.98]"
                    >
                        Create User Account
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection