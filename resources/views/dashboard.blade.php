@extends('layouts.app')

@section('title', 'Dashboard | Sip Laundry Manager')

@section('content')
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Welcome to the Dashboard</h1>
            
    <!-- Dashboard Content Card -->
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Quick Overview</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Stat Card 1 -->
            <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200">
                <p class="text-sm text-indigo-500 font-medium">Orders Pending Pickup</p>
                <p class="text-3xl font-bold text-indigo-700 mt-1">12</p>
            </div>
            <!-- Stat Card 2 -->
            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                <p class="text-sm text-yellow-500 font-medium">Low Stock Alerts</p>
                <p class="text-3xl font-bold text-yellow-700 mt-1">3</p>
            </div>
            <!-- Stat Card 3 -->
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <p class="text-sm text-green-500 font-medium">Today's Revenue</p>
                <p class="text-3xl font-bold text-green-700 mt-1">$450.00</p>
            </div>
        </div>

        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-700 mb-3">Next Steps</h3>
            <ul class="list-disc list-inside space-y-2 text-gray-600">
                <li>Start a **New Transaction** for incoming customer laundry.</li>
                <li>Check **Low Stock Alerts** in the Inventory section.</li>
                <li>Review and resolve any pending **Reorder Notices**.</li>
            </ul>
        </div>

    </div>
@endsection