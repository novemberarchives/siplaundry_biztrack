<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User | Sip Laundry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #eef2ff; /* Light, calming background */
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

    <!-- Card Container -->
    <div class="w-full max-w-lg bg-white p-8 md:p-10 rounded-xl shadow-2xl border border-indigo-100">

        <header class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">
                👤 Add New Staff/Manager
            </h1>
            <p class="text-gray-500 mt-2">Create an internal access account for a new employee.</p>
        </header>
        
        <!-- Dashboard Link (New) -->
        <div class="mb-6 text-center">
             <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                ← Back to Dashboard
            </a>
        </div>


        <!-- Display Session Error Message (from catch block) -->
        @if (session('error'))
            <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-300" role="alert">
                {{ session('error') }}
            </div>
        @endif
        <!-- Display Validation Errors if redirected back -->
        @if ($errors->any())
             <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-300" role="alert">
                Please correct the errors below.
            </div>
        @endif


        <!-- Registration Form -->
        <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
            @csrf

            <!-- Full Name -->
            <div>
                <label for="fullname" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input
                    type="text"
                    id="fullname"
                    name="fullname"
                    value="{{ old('fullname') }}"
                    required
                    autofocus
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out sm:text-sm"
                    placeholder="E.g., Jane Doe"
                >
                @error('fullname')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username (Login ID)</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="{{ old('username') }}"
                    required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out sm:text-sm"
                    placeholder="Must be unique"
                >
                @error('username')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role Selection -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select
                    id="role"
                    name="role"
                    required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out sm:text-sm bg-white"
                >
                    <option value="" disabled selected>Select a role...</option>
                    <option value="Staff" {{ old('role') == 'Staff' ? 'selected' : '' }}>Staff</option>
                    <option value="Manager" {{ old('role') == 'Manager' ? 'selected' : '' }}>Manager</option>
                </select>
                @error('role')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
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
                    Create User Account
                </button>
            </div>
        </form>
    </div>

</body>
</html>