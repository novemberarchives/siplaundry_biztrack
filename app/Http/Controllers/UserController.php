<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display the login form view (used for the '/' and '/login' GET routes).
     */
    public function showLoginForm()
    {
        // This returns the home view, which contains the @auth / @else logic for login.
        return view('home');
    }

    /**
     * Display the form to register a new user (Manager/Staff).
     */
    public function showRegisterForm()
    {
        // Corrected view name to match the file: resources/views/register_user.blade.php
        return view('register_user');
    }
    
    /**
     * Handle the registration of a new user.
     */
    public function register(Request $request)
    {
        // 1. Validate fields against the new schema: fullname, username, password, and role.
        $incomingFields = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:Manager,Staff'], // Enforce enum values
        ]);

        // 2. Hash the password before saving
        $incomingFields['password'] = bcrypt($incomingFields['password']);
        
        // 3. Create the user
        try {
            $user = User::create($incomingFields);

            // Redirect to the dashboard with a success message
            return redirect()->route('dashboard')->with('success', 'New user ' . $user->username . ' created successfully!');
        } catch (\Exception $e) {
            // Log the error (optional) and redirect back with a failure message
            // This catches database errors that prevent insertion.
            \Log::error("User creation failed: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Account creation failed. A required field might be missing or already exists.');
        }
    }

    /**
     * Handle user login authentication (Updated to use 'username').
     */
    public function login(Request $request)
    {
        // 1. Validate the incoming request data
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
        
        // 2. Attempt authentication using only the 'username' column
        // Note: Auth::attempt automatically uses the default hashing driver.
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Authentication successful. Redirect the user to their dashboard.
            return redirect()->intended('dashboard');
        }

        // 3. Authentication failed
        throw ValidationException::withMessages([
            'username' => __('The provided credentials do not match our records.'),
        ]);
    }

    /**
     * Handle user logout.
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}