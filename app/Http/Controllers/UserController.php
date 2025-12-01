<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog; // Import AuditLog model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display the login form view (used for the '/' and '/login' GET routes)
     */
    public function showLoginForm()
    {
        return view('home');
    }

    /**
     * Display the form to register a new user (Manager/Staff)
     */
    public function showRegisterForm()
    {
        return view('register_user');
    }
    
    /**
     * Handle the registration of a new user
     */
    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:Manager,Staff'],
        ]);

        $incomingFields['password'] = bcrypt($incomingFields['password']);
        
        $user = User::create($incomingFields);

        return redirect()->route('dashboard')->with('success', 'New user ' . $user->username . ' created successfully!');
    }

    /**
     * Handle user login authentication
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // --- MANUAL AUDIT LOG: LOGIN ---
            AuditLog::create([
                'user_id'        => Auth::id(),
                'event'          => 'login',
                'auditable_type' => User::class,
                'auditable_id'   => Auth::id(),
                'old_values'     => null,
                'new_values'     => ['ip' => $request->ip()],
                'url'            => $request->fullUrl(),
                'ip_address'     => $request->ip(),
                'user_agent'     => $request->userAgent(),
            ]);
            // -------------------------------

            return redirect()->intended('dashboard');
        }

        throw ValidationException::withMessages([
            'username' => __('The provided credentials do not match our records.'),
        ]);
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        // --- MANUAL AUDIT LOG: LOGOUT ---
        if (Auth::check()) {
            AuditLog::create([
                'user_id'        => Auth::id(),
                'event'          => 'logout',
                'auditable_type' => User::class,
                'auditable_id'   => Auth::id(),
                'old_values'     => null,
                'new_values'     => null,
                'url'            => $request->fullUrl(),
                'ip_address'     => $request->ip(),
                'user_agent'     => $request->userAgent(),
            ]);
        }
        // --------------------------------

        Auth::logout();
        return redirect('/');
    }
}