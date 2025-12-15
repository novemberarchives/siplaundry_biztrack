<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sip Laundry Login</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'media', // Uses system settings. Change to 'class' for manual toggle.
            theme: {
                fontFamily: {
                    sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<!-- Body: Light Gray / Dark Gray -->
<body class="flex items-center justify-center min-h-screen p-4 bg-gray-50 dark:bg-gray-900 transition-colors duration-300">

    <!-- Authenticated State: Welcome Back Screen -->
    @auth
        <!-- Bento Card: White / Dark Gray -->
        <div class="w-full max-w-md bg-white dark:bg-gray-800 p-8 md:p-10 rounded-3xl shadow-2xl shadow-gray-200/50 dark:shadow-none border border-white dark:border-gray-700 text-center transition-all duration-300">

            <!-- Logo Area -->
            <div class="w-24 h-24 bg-blue-50 dark:bg-blue-900/20 rounded-2xl mx-auto mb-6 flex items-center justify-center">
                <!-- Replace with your actual logo asset if available -->
                <img src="{{ asset('images/logo-siplaundry.png') }}" alt="Logo" class="w-20 rounded-xl">
            </div>

            <!-- Welcome Text -->
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 tracking-tight">
                Welcome Back!
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mb-8 font-medium">
                Logged in as <span class="text-blue-600 dark:text-blue-400">{{ Auth::user()->fullname ?? Auth::user()->username }}</span>
            </p>

            <!-- Dashboard Button (Primary) -->
            <form action="{{ route('dashboard') }}" method="GET" class="mb-4">
                <button type="submit"
                    class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                    Go to Dashboard
                </button>
            </form>

            <!-- Logout Button (Secondary) -->
            <form action="/logout" method="POST">
                @csrf
                <button type="submit"
                    class="w-full py-3.5 px-4 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold rounded-xl transition-all">
                    Logout
                </button>
            </form>
        </div>

    <!-- Guest State: Login Form -->
    @else

        <!-- Login Container -->
        <div class="w-full max-w-md bg-white dark:bg-gray-800 p-8 md:p-10 rounded-3xl shadow-2xl shadow-gray-200/50 dark:shadow-none border border-white dark:border-gray-700 transition-all duration-300">
            
            <header class="text-center mb-8">
                <img src="{{ asset('images/logo-siplaundry.png') }}" alt="Logo" class="w-20 md:w-24 mx-auto mb-6 rounded-lg">

                <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                    Laundry Manager
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm font-medium">Staff and Manager Access</p>
            </header>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="username" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 ml-1">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        required
                        autofocus
                        class="block w-full px-4 py-3.5 bg-gray-50 dark:bg-gray-700 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all font-medium outline-none"
                        placeholder="Enter your username"
                    >
                    @error('username')
                        <p class="text-sm text-red-500 mt-2 font-medium ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 ml-1">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="block w-full px-4 py-3.5 bg-gray-50 dark:bg-gray-700 border-2 border-transparent focus:border-blue-500 dark:focus:border-blue-400 focus:bg-white dark:focus:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:ring-0 transition-all font-medium outline-none"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="text-sm text-red-500 mt-2 font-medium ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 transition-all transform hover:scale-[1.02] active:scale-[0.98]"
                    >
                        Sign In
                    </button>
                </div>
            </form>
            
            {{-- <!-- Register Button (Secondary) -->
            <form action="{{ route('users.create') }}" method="GET">
                <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                    <button type="submit" class="w-full py-3.5 px-4 bg-white dark:bg-transparent border-2 border-gray-100 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 text-gray-600 dark:text-gray-300 font-bold rounded-xl transition-all">
                        Register New Account
                    </button>
                </div> --}}
            </form>

        </div>

    @endauth

</body>
</html>