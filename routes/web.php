<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;

// --- INITIAL BOOTSTRAP ROUTES (TEMPORARILY UNPROTECTED) ---

// User Management is UNPROTECTED so you can create the very first Manager account.
Route::get('/users/create', [UserController::class, 'showRegisterForm'])->name('users.create');

// POST: Handles the form submission and data insertion
Route::post('/users', [UserController::class, 'register'])->name('users.store');


// --- GUEST ROUTES ---

// Route to display the login form. Uses showLoginForm which returns view('home').
Route::get('/', [UserController::class, 'showLoginForm']); 
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');

// Route to process the login form submission
Route::post('/login', [UserController::class, 'login']);


// --- AUTHENTICATED ROUTES ---
Route::middleware(['auth'])->group(function () {
    // 1. Dashboard (Landing Page after Login)
    Route::get('/dashboard', function () {
        return view('dashboard', ['currentModule' => 'Dashboard']);
    })->name('dashboard');

    // 2. Customer Management Module (NOW INCLUDES EDIT/UPDATE)
    Route::resource('customers', CustomerController::class)->only([
        'index', 'create', 'store', 'edit', 'update'
    ]);
    
    // 3. Logout
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});