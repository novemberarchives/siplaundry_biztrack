@extends('layouts.app')

@section('title', 'Reorder Notices | Sip Laundry')

@section('content')
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">
            Reorder Notices
        </h1>
        <a href="{{ route('inventory.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
            ← Back to Inventory
        </a>
    </div>

    <!-- Reorder Table Card -->
    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
        
        <!-- Table Container -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Item Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Stock Level
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Reorder Level
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date Noticed
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date Resolved
                        </th>
                        <!-- NEW ACTIONS COLUMN -->
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($notices as $notice)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($notice->Status == 'Pending')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Resolved
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $notice->item->Name ?? 'Item not found' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $notice->item->Quantity <= $notice->item->ReorderLevel ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $notice->item->Quantity ?? 'N/A' }} {{ $notice->item->Unit ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $notice->item->ReorderLevel ?? 'N/A' }} {{ $notice->item->Unit ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($notice->NoticeDate)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $notice->ResolvedDate ? \Carbon\Carbon::parse($notice->ResolvedDate)->format('M d, Y') : 'N/A' }}
                            </td>
                            <!-- NEW ACTIONS CELL -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($notice->Status == 'Pending')
                                    <!-- CHANGED FROM A FORM TO A LINK -->
                                    <a href="{{ route('expenses.create', ['notice_id' => $notice->NoticeID]) }}" class="text-indigo-600 hover:text-indigo-900">
                                        <p class="underline">Log Purchase</p>
                                    </a>
                                @else
                                    <span class="text-gray-400">Resolved</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                No reorder notices found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
@endsection