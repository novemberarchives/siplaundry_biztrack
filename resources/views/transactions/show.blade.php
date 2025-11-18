@extends('layouts.app')

@section('title', 'Transaction #' . $transaction->TransactionID . ' | Sip Laundry')

@section('content')
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Transaction #{{ $transaction->TransactionID }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Created on {{ \Carbon\Carbon::parse($transaction->DateCreated)->format('F d, Y') }}
                by {{ $transaction->user->fullname ?? 'N/A' }}
            </p>
        </div>
        <a href="{{ route('transactions.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
            ← Back to Transaction List
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content (Left Side) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Transaction Details (Line Items) -->
            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Order Details</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty/Weight</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price/Unit</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                // Define the statuses here to be used in the loop
                                $statuses = ['Pending', 'Washing', 'Folding', 'Ready for Pickup', 'Completed'];
                            @endphp
                            
                            @forelse ($transaction->transactionDetails as $detail)
                                <tr>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $detail->service->Name ?? 'Service Not Found' }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $detail->Weight ? $detail->Weight . ' kg' : $detail->Quantity . ' ' . ($detail->service->Unit ?? 'item') }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ₱{{ number_format($detail->PricePerUnit, 2) }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                        ₱{{ number_format($detail->Subtotal, 2) }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm">
                                        
                                        <!-- === UPDATED STATUS UPDATE FORM === -->
                                        <form action="{{ route('transaction-details.updateStatus', $detail->TransactionDetailID) }}" method="POST" class="flex items-center space-x-2">
                                            @csrf
                                            @method('PUT')
                                            
                                            <select name="Status" class="block w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1 pl-2 pr-8">
                                                @foreach ($statuses as $status)
                                                    <option value="{{ $status }}" {{ $detail->Status == $status ? 'selected' : '' }}>
                                                        {{ $status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            
                                            <button type="submit" class="px-2 py-1 text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition duration-150">
                                                Update
                                            </button>
                                        </form>
                                        <!-- === END UPDATED FORM === -->

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        This transaction has no detail items.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar (Right Side) -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Customer Information -->
            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Customer Details</h2>
                <div class="space-y-2">
                    <p class="font-bold text-lg text-gray-800">{{ $transaction->customer->Name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">{{ $transaction->customer->ContactNumber ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">{{ $transaction->customer->Email ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">{{ $transaction->customer->Address ?? 'N/A' }}</p>
                </div>
                <a href="{{ route('customers.edit', $transaction->CustomerID) }}" class="mt-4 inline-block text-sm text-indigo-600 hover:text-indigo-900 font-medium">Edit Customer</a>
            </div>

            <!-- Payment Summary -->
            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Payment</h2>
                
                <div class="text-right mb-4">
                    <p class="text-sm text-gray-500">Total Amount</p>
                    <p class="text-4xl font-bold text-gray-900">₱{{ number_format($transaction->TotalAmount, 2) }}</p>
                </div>
                
                <div class="space-y-2">
                    <p class="text-sm font-medium text-gray-600">Payment Status:</p>
                    @if($transaction->PaymentStatus == 'Paid')
                        <span class="px-3 py-1 text-base leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Paid
                        </span>
                        <p class="text-xs text-gray-500 mt-1">
                            On: {{ $transaction->DatePaid ? \Carbon\Carbon::parse($transaction->DatePaid)->format('M d, Y') : 'N/A' }}
                        </p>
                    @else
                        <span class="px-3 py-1 text-base leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Unpaid
                        </span>
                    @endif
                </div>

                <div class="mt-4 border-t pt-4">
                     <p class="text-sm font-medium text-gray-600">Notes:</p>
                     <p class="text-sm text-gray-500 italic">{{ $transaction->Notes ?? 'No notes for this transaction.' }}</p>
                </div>

                <a href="{{ route('transactions.edit', $transaction->TransactionID) }}" class="mt-6 w-full flex justify-center py-2 px-4 border border-indigo-600 rounded-lg shadow-sm text-base font-medium text-indigo-600 bg-white hover:bg-indigo-50 transition duration-200">
                    Update Payment / Notes
                </a>
            </div>
        </div>
    </div>
@endsection