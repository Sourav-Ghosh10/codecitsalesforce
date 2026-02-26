<x-app-layout>
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('clients.show', $client) }}" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Call History</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Calls for {{ $client->full_name }}</p>
            </div>
        </div>
        <a href="{{ route('clients.call-logs.create', $client) }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Call Record
        </a>
    </div>

    <!-- Call Logs Timeline -->
    <div class="space-y-4">
        @forelse($callLogs as $callLog)
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-4">
                        <!-- Direction Icon -->
                        <div class="w-12 h-12 rounded-2xl {{ $callLog->call_direction === 'Incoming' ? 'bg-green-100 dark:bg-green-900/30' : 'bg-blue-100 dark:bg-blue-900/30' }} flex items-center justify-center">
                            @if($callLog->call_direction === 'Incoming')
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            @endif
                        </div>

                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ $callLog->call_record_number }}</span>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $callLog->direction_color }}">
                                    {{ $callLog->call_direction }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $callLog->result_color }}">
                                    {{ $callLog->call_result }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-3">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Phone Number</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $callLog->phone_number }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Dialer Platform</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $callLog->dialer_platform }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Staff Member</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $callLog->staffMember->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Duration</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $callLog->formatted_duration }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400 mb-2">
                                <span>{{ $callLog->call_start_time->format('M d, Y h:i A') }}</span>
                                <span>→</span>
                                <span>{{ $callLog->call_end_time->format('h:i A') }}</span>
                            </div>

                            @if($callLog->notes)
                                <div class="mt-3 p-3 bg-gray-50 dark:bg-slate-700/50 rounded-lg">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $callLog->notes }}</p>
                                </div>
                            @endif

                            @if($callLog->next_follow_up_date)
                                <div class="mt-3 flex items-center gap-2 text-sm text-indigo-600 dark:text-indigo-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>Follow-up: {{ $callLog->next_follow_up_date->format('M d, Y') }}</span>
                                </div>
                            @endif

                            @if($callLog->admin_edit_reason)
                                <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                                    <p class="text-xs text-amber-700 dark:text-amber-400">
                                        <strong>Edit Reason:</strong> {{ $callLog->admin_edit_reason }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        @if(auth()->user()->isManagement())
                            <a href="{{ route('call-logs.edit', $callLog) }}" class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        @endif
                        <a href="{{ route('call-logs.show', $callLog) }}" class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-12 shadow-sm border border-gray-100 dark:border-slate-700 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Call Records</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">There are no call records for this client yet.</p>
                <a href="{{ route('clients.call-logs.create', $client) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add First Call Record
                </a>
            </div>
        @endforelse
    </div>

    @if($callLogs->count() > 0)
        <div class="mt-6 text-sm text-gray-500 dark:text-gray-400 text-center">
            Showing {{ $callLogs->count() }} call record(s) in chronological order
        </div>
    @endif
</x-app-layout>
