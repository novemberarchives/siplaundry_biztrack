<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sip Laundry Manager')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font: Plus Jakarta Sans-->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'media', // Uses system settings
            theme: {
                fontFamily: {
                    sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                },
                extend: {
                    colors: {
                        gray: {
                            50: '#F9FAFB',
                            100: '#F3F4F6',
                            200: '#E5E7EB',
                            800: '#1F2937',
                            900: '#111827',
                        }
                    }
                }
            },
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        
        /* Custom thin scrollbar for sidebar */
        .custom-scrollbar {
            scrollbar-width: thin; /* Firefox */
            scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5); /* Gray-400 with opacity */
            border-radius: 20px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(107, 114, 128, 0.8); /* Darker on hover */
        }
        
        /* Main content scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }

        /* Fade out animation for toasts */
        .toast-fade-out {
            opacity: 0;
            transform: translateY(-20px);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }
    </style>
</head>
<!-- Body: Light/Dark background -->
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 h-screen overflow-hidden flex transition-colors duration-300">

    <!-- Global Toast Notifications -->
    @if (session('success'))
        <div id="toast-notification" class="fixed top-6 right-6 z-[60] px-6 py-4 bg-green-500 text-white rounded-2xl shadow-xl shadow-green-500/20 flex items-center gap-3 transition-all duration-500 font-medium">
            <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div id="toast-notification" class="fixed top-6 right-6 z-[60] px-6 py-4 bg-red-500 text-white rounded-2xl shadow-xl shadow-red-500/20 flex items-center gap-3 transition-all duration-500 font-medium">
            <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- MOBILE OVERLAY BACKDROP -->
    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm hidden lg:hidden transition-opacity"></div>

    <!-- SIDEBAR -->
    <!-- Changed: Added fixed positioning for mobile, transform classes for sliding -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 h-screen p-4 flex flex-col gap-4 flex-shrink-0 transition-transform duration-300 -translate-x-full lg:translate-x-0 lg:static lg:flex bg-gray-50 dark:bg-gray-900 lg:bg-transparent">
        
        <!-- Logo Area -->
        <div class="h-20 bg-white dark:bg-gray-800 rounded-3xl flex items-center px-6 shadow-sm border border-gray-100 dark:border-gray-700 justify-between lg:justify-start">
            <div class="flex items-center">
                    <img src="{{ asset('images/logo-siplaundry.png') }}" alt="Logo" class="w-10 h-10 mx-auto rounded-lg">
                <div class="ml-3">
                    <h1 class="font-bold text-xl leading-tight text-gray-900 dark:text-white">Sip Laundry</h1>
                    <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">BizTrack</p>
                </div>
            </div>

            <!-- Close Button (Mobile Only) -->
            <button onclick="toggleSidebar()" class="lg:hidden p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 p-4 flex flex-col overflow-hidden">
            
            <div class="flex-1 flex flex-col gap-2 overflow-y-auto custom-scrollbar pr-2">
                
                <div class="px-4 mt-2 mb-2 text-xs font-extrabold text-gray-400 uppercase tracking-wider">Operational</div>

                @php
                    $activeClass = 'bg-blue-600 text-white shadow-md shadow-blue-600/30';
                    $inactiveClass = 'text-gray-500 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400';
                    $currentModule = $currentModule ?? 'Dashboard';
                @endphp

                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition-all duration-200 {{ $currentModule == 'Dashboard' ? $activeClass : $inactiveClass }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Overview
                </a>

                <!-- Transactions -->
                <a href="{{ route('transactions.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition-all duration-200 {{ $currentModule == 'Transactions' ? $activeClass : $inactiveClass }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Transactions
                </a>

                <!-- Customers -->
                <a href="{{ route('customers.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition-all duration-200 {{ $currentModule == 'Customers' ? $activeClass : $inactiveClass }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Customers
                </a>

                <!-- Services -->
                <a href="{{ route('services.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition-all duration-200 {{ $currentModule == 'Services' ? $activeClass : $inactiveClass }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    Services
                </a>

                <!-- Inventory -->
                <a href="{{ route('inventory.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition-all duration-200 {{ $currentModule == 'Inventory' ? $activeClass : $inactiveClass }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    Inventory
                </a>

                <!-- Reorder Notices (Staff & Manager) -->
                <a href="{{ route('reorder-notices.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition-all duration-200 {{ $currentModule == 'Reorder Notices' ? $activeClass : $inactiveClass }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    Alerts
                </a>

                <!-- Manager Section -->
                @if (Auth::user()->role === 'Manager')
                    <div class="px-4 mt-6 mb-2 text-xs font-extrabold text-gray-400 uppercase tracking-wider">Manager</div>

                    <!-- Analytics -->
                    <a href="{{ route('analytics.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition-all duration-200 {{ $currentModule == 'Analytics' ? $activeClass : $inactiveClass }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m-2-4h4"></path></svg>
                        Analytics
                    </a>

                    <!-- Expenses -->
                    <a href="{{ route('expenses.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition-all duration-200 {{ $currentModule == 'Expenses' ? $activeClass : $inactiveClass }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Expenses
                    </a>

                    <!-- Audit Logs -->
                    <a href="{{ route('audit-logs.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition-all duration-200 {{ $currentModule == 'Audit Logs' ? $activeClass : $inactiveClass }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Audit Logs
                    </a>

                    <!-- Add Staff -->
                    <a href="{{ route('users.create') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl font-semibold transition-all duration-200 {{ $inactiveClass }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        Add Staff
                    </a>
                @endif
            </div>
        </nav>

        <!-- User Profile Pill (Bottom of Sidebar) -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-2 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div class="flex items-center gap-3 pl-2">
                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-sm">
                    {{ substr(Auth::user()->username, 0, 2) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-gray-900 dark:text-white truncate w-24">
                        {{ Auth::user()->fullname ?? Auth::user()->username }}
                    </p>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                        {{ Auth::user()->role }}
                    </p>
                </div>
            </div>
            
            <!-- Mini Logout Button -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="p-2.5 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all" title="Logout">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT WRAPPER -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <!-- MOBILE HEADER (Visible only on lg and smaller) -->
        <header class="lg:hidden h-16 bg-white dark:bg-gray-800 flex items-center justify-between px-4 border-b border-gray-100 dark:border-gray-700 flex-shrink-0 z-30">
            <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-siplaundry.png') }}" alt="Logo" class="w-10 h-10 mx-auto">
                <span class="font-bold text-2xl text-gray-900 dark:text-white tracking-tight">Sip Laundry</span>
            </div>
            
            <button onclick="toggleSidebar()" class="p-2 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto p-4 lg:p-6 lg:pl-2">
            <!-- Content gets injected here -->
            @yield('content')
        </main>
    </div>

    <!-- Auto-hide Toast Notification Script -->
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const toast = document.getElementById('toast-notification');
            if (toast) {
                setTimeout(() => {
                    toast.classList.add('toast-fade-out');
                    setTimeout(() => { toast.remove(); }, 500);
                }, 3000);
            }
        });

        // Sidebar Toggle Logic
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            // Toggle translate class
            if (sidebar.classList.contains('-translate-x-full')) {
                // Open
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                // Close
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }
    </script>
</body>
</html>