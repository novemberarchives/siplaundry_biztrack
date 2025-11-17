<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sip Laundry Login</title>
    <!-- Load Tailwind CSS for simple centering and responsiveness -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Load custom font 'Inter' -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Apply the custom font globally */
        body {
            font-family: 'Inter', sans-serif;
            /* Using a dark, clean background to make the glassmorphic effect pop */
            background: #eef2ff; 
        }
    </style>
</head>
<!-- Use flex utilities to center content vertically and horizontally on the screen -->
<body class="flex items-center justify-center min-h-screen p-4">

    <!-- Authenticated State: Welcome Back Screen (Glassmorphism) -->
    @auth
        <!-- Clean White Card -->
        <div class="w-full max-w-md bg-white p-8 md:p-10 rounded-xl shadow-2xl border border-gray-200 text-center">

            <!-- Logo -->
            <img src="https://placehold.co/100x100/4f46e5/ffffff?text=LOGO" alt="Logo"
                class="w-20 md:w-24 mx-auto mb-6 rounded-lg">

            <!-- Welcome Text -->
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                Welcome Back, {{ Auth::user()->fullname ?? Auth::user()->username }}!
            </h1>
            <p class="text-gray-500 mb-8">You are already logged in.</p>

            <!-- Dashboard Button (Primary Style) -->
            <form action="{{ route('dashboard') }}" method="GET" class="mb-4">
                @csrf
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
                    Go to Dashboard
                </button>
            </form>

            <!-- Logout Button (Secondary Style) -->
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

        <!-- Login Container: Now styled to match the register page card -->
        <div class="w-full max-w-sm bg-white p-8 md:p-10 rounded-xl shadow-2xl border border-indigo-100">

            <header class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 tracking-tight">
                    Login to Laundry Manager
                </h1>
                <p class="text-gray-500 mt-2">Staff and Manager Access Only</p>
            </header>

            <!-- Laravel Form -->
            <form method="POST" action="/login" class="space-y-6">
                <!-- Blade placeholder for CSRF token -->
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
                    <!-- Blade placeholder for validation errors -->
                    <!-- The authentication failure message is tied to this field -->
                    <!-- @error('username')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror -->
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
                    <!-- Blade placeholder for validation errors -->
                    <!-- @error('password')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror -->
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
            
            <!-- --- New Staff Registration Button (Fixed to A-Tag) --- -->
            <form action="{{ route('users.create') }}" method="GET">
                @csrf
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <!-- Links to the User Creation Form via the named route users.create -->
                    <button class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-100 transition duration-200 ease-in-out">
                        Register New Account
                    </button>
                </div>
            </form>

        </div>

    @endauth

</body>
</html>