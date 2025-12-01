<?php

use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\ReorderNotice;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReorderNoticeController;
use App\Http\Controllers\TransactionDetailController;
use App\Http\Controllers\InventoryUsageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AuditLogController;

// --- INITIAL BOOTSTRAP ROUTES ---
Route::get('/users/create', [UserController::class, 'showRegisterForm'])->name('users.create');
Route::post('/users', [UserController::class, 'register'])->name('users.store');

// --- GUEST ROUTES ---
Route::get('/', [UserController::class, 'showLoginForm']); 
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);

// --- AUTHENTICATED ROUTES ---
Route::middleware(['auth'])->group(function () {

    // --- 1. ROUTES FOR ALL AUTHENTICATED USERS (STAFF & MANAGERS) ---
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Customer Management (Full Access for Staff)
    Route::resource('customers', CustomerController::class)->only([
        'index', 'create', 'store', 'edit', 'update'
    ]);

    // Transaction Management (Full Access for Staff)
    Route::resource('transactions', TransactionController::class)->only([
        'index', 'create', 'store', 'show', 'edit', 'update'
    ]);
    Route::put('/transaction-details/{detail}/status', [TransactionDetailController::class, 'updateStatus'])
         ->name('transaction-details.updateStatus');

    // Services (READ ONLY for Staff)
    Route::resource('services', ServiceController::class)->only(['index']);

    // Inventory (READ ONLY for Staff)
    Route::resource('inventory', InventoryController::class)->only(['index']);

    // Logout
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');


    // --- 2. MANAGER-ONLY ROUTES ---
    Route::middleware(['role:Manager'])->group(function () {
        
        // Services (Write Access)
        Route::resource('services', ServiceController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);

        // Inventory (Write Access)
        Route::resource('inventory', InventoryController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);

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
             
        // Analytics
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/{date}', [AnalyticsController::class, 'show'])->name('analytics.show');

        // Audit Logs
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        
    });

});