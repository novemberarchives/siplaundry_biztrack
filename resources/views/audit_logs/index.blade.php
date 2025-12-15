@extends('layouts.app')

@section('title', 'Audit Logs | Sip Laundry')

@section('content')
    <!-- Header Strip -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Audit Logs</h1>
            <p class="text-sm md:text-base text-gray-500 dark:text-gray-400 font-medium">System activity history</p>
        </div>
    </header>

    <!-- Logs Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-[2rem] md:rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 p-4 md:p-6">
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700 align-middle">
                <thead>
                    <tr>
                        <th scope="col" class="pl-4 pr-3 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider w-24 whitespace-nowrap">Time</th>
                        <th scope="col" class="px-3 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-1.5 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Action</th>
                        <th scope="col" class="hidden md:table-cell px-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Target</th>
                        <th scope="col" class="px-1 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider min-w-[200px]">Changes</th>
                        <th scope="col" class="hidden lg:table-cell px-3 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            
                            <!-- Timestamp -->
                            <td class="pl-2 pr-2 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('M d') }}
                                    </span>
                                    <span class="text-[10px] text-gray-500 font-mono">
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }}
                                    </span>
                                </div>
                            </td>

                            <!-- User -->
                            <td class="px-3 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/30 flex-shrink-0 flex items-center justify-center text-[10px] font-bold text-blue-600 dark:text-blue-400">
                                        {{ substr($log->user->username ?? 'S', 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white max-w-[80px] truncate">
                                        {{ $log->user->username ?? 'System' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Event Type -->
                            <td class="px-1 py-4 text-center whitespace-nowrap">
                                @php
                                    $colorClass = match(strtolower($log->event)) {
                                        'created', 'created item' => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800',
                                        'updated', 'updated item', 'login' => 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800',
                                        'deleted', 'deleted item', 'logout' => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800',
                                        default   => 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600',
                                    };
                                @endphp
                                <span class="px-1 py-1 rounded-md text-[10px] font-bold border uppercase tracking-wide {{ $colorClass }}">
                                    {{ $log->event }}
                                </span>
                            </td>

                            <!-- Target Model (Hidden Mobile) -->
                            <td class="hidden md:table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                <div class="flex flex-col">
                                    <span class="font-mono text-xs text-gray-400">{{ class_basename($log->auditable_type) }}</span>
                                    <span class="font-bold text-xs">#{{ $log->auditable_id }}</span>
                                </div>
                            </td>

                            <!-- Change Details (JSON) -->
                            <td class="px-1 py-4 text-xs">
                                <div class="max-w-xs md:max-w-sm overflow-hidden">
                                    @if(is_array($log->new_values))
                                        <!-- Handle custom 'details' field we added earlier -->
                                        @if(isset($log->new_values['details']))
                                            <span class="text-gray-600 dark:text-gray-300">{{ $log->new_values['details'] }}</span>
                                        @else
                                            <!-- Standard Diff -->
                                            @foreach($log->new_values as $key => $newVal)
                                                @if(isset($log->old_values[$key]) && $log->old_values[$key] !== $newVal)
                                                    <div class="mb-1">
                                                        <span class="font-bold text-gray-500">{{ $key }}:</span> 
                                                        <span class="text-red-400 line-through mr-1 decoration-red-400/50">{{ is_array($log->old_values[$key]) ? 'Array' : Str::limit($log->old_values[$key], 20) }}</span>
                                                        <span class="text-green-600 font-bold">-> {{ is_array($newVal) ? 'Array' : Str::limit($newVal, 20) }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @elseif($log->event === 'created')
                                         <span class="text-gray-400 italic">New Record Created</span>
                                    @else
                                         <span class="text-gray-400 italic">No details recorded</span>
                                    @endif
                                </div>
                            </td>

                            <!-- IP Address (Hidden Tablet/Mobile) -->
                            <td class="hidden lg:table-cell px-3 py-4 whitespace-nowrap text-xs text-gray-400 font-mono">
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