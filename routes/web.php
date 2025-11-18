<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReorderNoticeController;
use App\Http\Controllers\InventoryUsageController;
use App\Http\Controllers\TransactionDetailController;

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

    // --- 1. ROUTES FOR ALL AUTHENTICATED USERS (STAFF & MANAGERS) ---
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Customer Management
    Route::resource('customers', CustomerController::class)->only([ // Fixed typo: RouteName:: to Route::
        'index', 'create', 'store', 'edit', 'update'
    ]);

    // Services (Viewing services is OK for staff to create transactions)
    Route::resource('services', ServiceController::class);

    // Transaction Management
    Route::resource('transactions', TransactionController::class)->only([
        'index', 'create', 'store', 'show', 'edit', 'update'
    ]);

    // Transaction Detail (Item Status) Update
    Route::put('/transaction-details/{detail}/status', [TransactionDetailController::class, 'updateStatus'])
         ->name('transaction-details.updateStatus');

    // Logout
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');


    // --- 2. MANAGER-ONLY ROUTES ---
    // These routes are only accessible to users with the 'Manager' role.
    Route::middleware(['role:Manager'])->group(function () {
        
        // Inventory
        Route::resource('inventory', InventoryController::class);

        // Inventory Usage Management
        Route::post('/services/{service}/inventory-usage', [InventoryUsageController::class, 'store'])
             ->name('inventory-usage.store');
        Route::delete('/inventory-usage/{usage}', [InventoryUsageController::class, 'destroy'])
             ->name('inventory-usage.destroy');
        
        // Expense Management
        Route::resource('expenses', ExpenseController::class)->only(['index', 'create', 'store']);

        // Reorder Notices
        Route::get('/reorder-notices', [ReorderNoticeController::class, 'index'])
             ->name('reorder-notices.index');
        
    });

});