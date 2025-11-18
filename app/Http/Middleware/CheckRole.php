<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles (e.g., 'Manager', 'Staff')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Get the authenticated user
        $user = Auth::user();

        // Check if the user's role is in the allowed list
        foreach ($roles as $role) {
            if ($user->role === $role) {
                // If they have the role, let them proceed
                return $next($request);
            }
        }

        // If their role is not in the list, send them back to the dashboard
        return redirect()->route('dashboard')->with('error', 'You do not have permission to access that page.');
    }
}