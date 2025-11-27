<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sip Laundry Login</title>
    <!--  Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- custom font 'Inter' -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* global custom font*/
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6; 
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

    <!-- Authenticated State: Welcome Back Screen-->
    @auth
        
        <div class="w-full max-w-md bg-white p-8 md:p-10 rounded-xl shadow-2xl border border-gray-200 text-center">

            <!-- Logo -->
            <img src="https://placehold.co/100x100/4f46e5/ffffff?text=LOGO" alt="Logo"
                class="w-20 md:w-24 mx-auto mb-6 rounded-lg">

            <!-- Welcome Text -->
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                Welcome Back, {{ Auth::user()->fullname ?? Auth::user()->username }}!
            </h1>
            <p class="text-gray-500 mb-8">You are already logged in.</p>

            <!-- Dashboard Button -->
            <form action="{{ route('dashboard') }}" method="GET" class="mb-4">
                @csrf
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
                    Go to Dashboard
                </button>
            </form>

            <!-- Logout Button -->
            <form action="/logout" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-100 transition duration-200">
                    Logout
                </button>
            </form>
        </div>

    <!-- Guest State: Login Form -->
    @else

        <!-- Login Container-->
        <div class="w-full max-w-md bg-white p-8 md:p-10 rounded-xl shadow-2xl border border-gray-200">

            <header class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 tracking-tight">
                    Login to Laundry Manager
                </h1>
                <p class="text-gray-500 mt-2">Staff and Manager Access Only</p>
            </header>

            <!-- Laravel Form -->
            <form method="POST" action="/login" class="space-y-6">
                @csrf

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        required
                        autofocus
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out sm:text-sm"
                        placeholder="Enter your username"
                    >
                    
                     @error('username')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out sm:text-sm"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button
                        type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-md text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 ease-in-out transform hover:scale-[1.01]"
                    >
                        Sign In
                    </button>
                </div>
            </form>
        </div>

    @endauth

</body>
</html>