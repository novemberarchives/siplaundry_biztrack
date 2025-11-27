<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sip Laundry Manager')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        .h-screen-minus-header {
            height: calc(100vh - 4rem);
        }
        .bg-indigo-600 { background-color: #4f46e5; }
        
        /* NEW: CSS for the fade-out transition */
        .toast-fade-out {
            transition: opacity 0.5s ease-out;
            opacity: 0;
        }
    </style>
</head>
<body class="antialiased">

    <!-- Global Success/Error Notification Area -->
    @if (session('success'))
        <!-- Added ID for JavaScript to find -->
        <div id="toast-notification" class="fixed top-4 right-4 z-50 p-4 bg-green-500 text-white rounded-lg shadow-xl transition-opacity duration-500" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <!-- Added ID for JavaScript to find -->
        <div id="toast-notification" class="fixed top-4 right-4 z-50 p-4 bg-red-500 text-white rounded-lg shadow-xl transition-opacity duration-500" role="alert">
            {{ session('error') }}
        </div>
    @endif
    
    <!-- Header/Navigation Bar -->
    <header class="h-16 bg-white shadow-md flex items-center justify-between px-4 sm:px-6 z-10">
        <!-- Logo/Title -->
        <div class="flex items-center gap-3">
            <!-- Replace 'src' with your actual logo path, e.g., asset('images/logo.png') -->
            <img src="{{ asset('images/logo-siplaundry.png') }}" alt="Logo" class="h-9 w-9 rounded-lg shadow-sm">
            <div class="text-3xl font-bold text-indigo-600 tracking-wide">
                Sip Laundry BizTrack
            </div>
        </div>

        <!-- User Info and Module -->
        <div class="flex items-center space-x-4">
            <span class="text-gray-700 font-medium hidden sm:inline">
                Welcome, {{ Auth::user()->fullname ?? Auth::user()->username }} ({{ Auth::user()->role }})
            </span>
            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-sm font-semibold rounded-full">
                {{ $currentModule ?? 'Dashboard' }}
            </span>
            
            <!-- Logout Button -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="p-2 text-gray-500 hover:text-red-500 transition duration-150" title="Logout">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>
    </header>

    <!-- Main Layout Container (Sidebar + Content) -->
    <div class="flex h-screen-minus-header">
        
        <!-- Sidebar Navigation -->
        <nav class="w-64 bg-gray-800 text-white p-4 shadow-xl flex-shrink-0 hidden md:block">
            <ul class="space-y-2">
                <!-- Helper function to determine if a link is active -->
                @php
                    $activeClass = 'bg-indigo-600 hover:bg-indigo-700 text-white';
                    $inactiveClass = 'hover:bg-gray-700 text-gray-300';
                    $currentModule = $currentModule ?? 'Dashboard';
                @endphp
                
                <!-- Dashboard Link -->
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg transition duration-150 
                        {{ $currentModule == 'Dashboard' ? $activeClass : $inactiveClass }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                </li>
                
                <!-- Customer Module -->
                <li>
                    <a href="{{ route('customers.index') }}" class="flex items-center p-3 rounded-lg transition duration-150 
                        {{ $currentModule == 'Customers' ? $activeClass : $inactiveClass }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-4m-1.294-2.294a.5.5 0 00.354-.146l2-2a.5.5 0 000-.708l-2-2a.5.5 0 00-.708 0l-2 2a.5.5 0 000 .708zM12 12V3m0 0a.5.5 0 00-.5-.5H5.5a.5.5 0 00-.5.5v9a.5.5 0 00.5.5H12z"></path></svg>
                        Customers
                    </a>
                </li>
                
                <!-- Transactions Module -->
                <li>
                    <a href="{{ route('transactions.create') }}" class="flex items-center p-3 rounded-lg transition duration-150 
                        {{ $currentModule == 'Transactions' ? $activeClass : $inactiveClass }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m-2-4h4"></path></svg>
                        New Transaction
                    </a>
                </li>
                <!-- Transaction List Link -->
                <li>
                    <a href="{{ route('transactions.index') }}" class="flex items-center p-3 rounded-lg transition duration-150 
                        {{ $currentModule == 'Transactions' ? $activeClass : $inactiveClass }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M3 7l9 5 9-5M3 7V5a2 2 0 012-2h14a2 2 0 012 2v2"></path></svg>
                        Transaction List
                    </a>
                </li>

                <!-- Services List Link -->
                <li>
                    <a href="{{ route('services.index') }}" class="flex items-center p-3 rounded-lg transition duration-150 
                        {{ $currentModule == 'Services' ? $activeClass : $inactiveClass }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10h16V7a2 2 0 00-2-2H6a2 2 0 00-2 2zm16 0h-4M4 7h4m0 0V5a2 2 0 012-2h4a2 2 0 012 2v2m-4 0h4m-4 0a.5.5 0 00-.5.5v2.5a.5.5 0 00.5.5h4a.5.5 0 00.5-.5v-2.5a.5.5 0 00-.5-.5h-4z"></path></svg>
                        Services
                    </a>
                </li>

                <!-- NEW: Inventory List Link -->
                <li>
                    <a href="{{ route('inventory.index') }}" class="flex items-center p-3 rounded-lg transition duration-150 
                        {{ $currentModule == 'Inventory' ? $activeClass : $inactiveClass }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Inventory
                    </a>
                </li>
                
                <!-- Expenses/Reorder (Manager Only) -->
                @if (Auth::user()->role === 'Manager')
                <li class="pt-4 border-t border-gray-700">
                    <span class="text-xs font-semibold uppercase text-gray-500 block mb-1 px-3">Administration</span>

                    <!-- NEW LINK: Analytics -->
                    <a href="{{ route('analytics.index') }}" class="flex items-center p-3 rounded-lg transition duration-150 
                        {{ $currentModule == 'Analytics' ? $activeClass : $inactiveClass }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m-2-4h4"></path></svg>
                        Analytics
                    </a>

                    <a href="{{ route('expenses.index') }}" class="flex items-center p-3 rounded-lg transition duration-150 {{ $inactiveClass }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-3.1 0-5.5 1.5-5.5 4s2.4 4 5.5 4 5.5-1.5 5.5-4-2.4-4-5.5-4z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v2m0 16v2m7.4-7.4l-1.4-1.4M4.6 4.6L6 6m7.4-7.4l1.4 1.4M4.6 19.4L6 18"></path></svg>
                        Expenses
                    </a>

                    <a href="{{ route('reorder-notices.index') }}" class="flex items-center p-3 rounded-lg transition duration-150 
                        {{ $currentModule == 'Reorder Notices' ? $activeClass : $inactiveClass }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Reorder Notices
                    </a>

                    <a href="{{ route('users.create') }}" class="flex items-center p-3 rounded-lg transition duration-150 {{ $inactiveClass }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        Add Staff Account
                    </a>
                </li>


                

                @endif
            </ul>
        </nav>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto p-6">
            
            <!-- This is where the content from other pages will be injected -->
            @yield('content')

        </main>
    </div>

    <!-- NEW: Auto-hide Toast Notification Script -->
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const toast = document.getElementById('toast-notification');
            
            if (toast) {
                // 1. Wait 4 seconds (4000 milliseconds)
                setTimeout(() => {
                    // 2. Add the fade-out class
                    toast.classList.add('toast-fade-out');
                    
                    // 3. After the fade-out animation (0.5s), remove the element
                    setTimeout(() => {
                        toast.remove();
                    }, 500); // 500ms matches the CSS transition
                    
                }, 3000);
            }
        });
    </script>
</body>
</html>