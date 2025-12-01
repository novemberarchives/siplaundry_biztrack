@extends('layouts.app')

@section('title', 'Audit Logs | Sip Laundry')

@section('content')
    <!-- Header Strip -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Audit Logs</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium">System activity history</p>
        </div>
    </header>

    <!-- Logs Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-32">Time</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">User</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Action</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Target</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Changes</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <!-- Timestamp -->
                            <td class="px-4 py-4 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400 font-mono">
                                {{ \Carbon\Carbon::parse($log->created_at)->format('M d, H:i') }}
                            </td>

                            <!-- User -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-[10px] font-bold text-blue-600 dark:text-blue-400">
                                        {{ substr($log->user->username ?? 'S', 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ $log->user->username ?? 'System' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Event Type -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                @php
                                    $colorClass = match($log->event) {
                                        'created' => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800',
                                        'updated' => 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800',
                                        'deleted' => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800',
                                        default   => 'bg-gray-100 text-gray-700 border-gray-200',
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded-md text-xs font-bold border uppercase tracking-wide {{ $colorClass }}">
                                    {{ $log->event }}
                                </span>
                            </td>

                            <!-- Target Model -->
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                <span class="font-mono text-xs text-gray-400">{{ class_basename($log->auditable_type) }}</span>
                                <span class="ml-1 font-bold">#{{ $log->auditable_id }}</span>
                            </td>

                            <!-- Change Details (JSON) -->
                            <td class="px-4 py-4 text-xs">
                                <div class="max-w-xs overflow-hidden">
                                    @if($log->event === 'updated')
                                        <!-- Show simplified diff -->
                                        @foreach($log->new_values as $key => $newVal)
                                            @if(isset($log->old_values[$key]) && $log->old_values[$key] !== $newVal)
                                                <div class="mb-1">
                                                    <span class="font-bold text-gray-500">{{ $key }}:</span> 
                                                    <span class="text-red-400 line-through mr-1">{{ is_array($log->old_values[$key]) ? 'Array' : $log->old_values[$key] }}</span>
                                                    <span class="text-green-600 font-bold">-> {{ is_array($newVal) ? 'Array' : $newVal }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    @elseif($log->event === 'created')
                                         <span class="text-gray-400">New Record</span>
                                    @else
                                         <span class="text-gray-400">Record Deleted</span>
                                    @endif
                                </div>
                            </td>

                            <!-- IP Address -->
                            <td class="px-4 py-4 whitespace-nowrap text-xs text-gray-400 font-mono">
                                {{ $log->ip_address ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <p class="text-gray-500 dark:text-gray-400 font-medium">No activity logs found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $logs->links() }}
        </div>
        
    </div>
@endsection