<x-app-layout>
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('clients.call-logs.index', $client) }}" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Add Call Record</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Recording call for {{ $client->full_name }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form -->
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('clients.call-logs.store', $client) }}" class="space-y-6">
                @csrf

                <!-- Phone Number -->
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number Used</label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $client->phone) }}" 
                        class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        required>
                    @error('phone_number')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dialer Platform -->
                <div>
                    <label for="dialer_platform" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dialer Platform</label>
                    <select name="dialer_platform" id="dialer_platform" 
                        class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        required>
                        <option value="">Select Platform</option>
                        @foreach(App\Models\CallLog::getDialerPlatforms() as $value => $label)
                            <option value="{{ $value }}" {{ old('dialer_platform') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('dialer_platform')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Call Direction -->
                <div>
                    <label for="call_direction" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Call Direction</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex items-center justify-center p-4 border-2 border-gray-200 dark:border-slate-600 rounded-xl cursor-pointer hover:border-indigo-500 dark:hover:border-indigo-400 transition-colors has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 dark:has-[:checked]:bg-indigo-900/20">
                            <input type="radio" name="call_direction" value="Incoming" class="sr-only" {{ old('call_direction') == 'Incoming' ? 'checked' : '' }}>
                            <div class="text-center">
                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Incoming</span>
                            </div>
                        </label>
                        <label class="relative flex items-center justify-center p-4 border-2 border-gray-200 dark:border-slate-600 rounded-xl cursor-pointer hover:border-indigo-500 dark:hover:border-indigo-400 transition-colors has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 dark:has-[:checked]:bg-indigo-900/20">
                            <input type="radio" name="call_direction" value="Outgoing" class="sr-only" {{ old('call_direction', 'Outgoing') == 'Outgoing' ? 'checked' : '' }}>
                            <div class="text-center">
                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Outgoing</span>
                            </div>
                        </label>
                    </div>
                    @error('call_direction')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Call Time -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="call_start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Call Start Time</label>
                        <input type="datetime-local" name="call_start_time" id="call_start_time" value="{{ old('call_start_time') }}" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            required>
                        @error('call_start_time')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="call_end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Call End Time</label>
                        <input type="datetime-local" name="call_end_time" id="call_end_time" value="{{ old('call_end_time') }}" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            required>
                        @error('call_end_time')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Call Result -->
                <div>
                    <label for="call_result" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Call Result</label>
                    <select name="call_result" id="call_result" 
                        class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        required>
                        <option value="">Select Result</option>
                        @foreach(App\Models\CallLog::getCallResults() as $value => $label)
                            <option value="{{ $value }}" {{ old('call_result') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('call_result')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="4" 
                        class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Add any notes about this call...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Next Follow-up Date -->
                <div>
                    <label for="next_follow_up_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Next Follow-up Date</label>
                    <input type="date" name="next_follow_up_date" id="next_follow_up_date" value="{{ old('next_follow_up_date') }}" 
                        class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('next_follow_up_date')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex items-center gap-4">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition-colors">
                        Save Call Record
                    </button>
                    <a href="{{ route('clients.call-logs.index', $client) }}" class="px-6 py-3 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Client Information</h3>
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-lg font-bold">
                        {{ strtoupper(substr($client->full_name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $client->full_name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $client->customer_number }}</p>
                    </div>
                </div>
                <div class="space-y-3 pt-4 border-t border-gray-100 dark:border-slate-700">
                    <div class="flex items-center gap-3 text-sm">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">{{ $client->phone }}</span>
                    </div>
                    @if($client->email)
                        <div class="flex items-center gap-3 text-sm">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-gray-700 dark:text-gray-300">{{ $client->email }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl p-6 mt-6 border border-indigo-100 dark:border-indigo-800">
                <h3 class="text-sm font-semibold text-indigo-800 dark:text-indigo-300 mb-2">Recording Call Details</h3>
                <p class="text-sm text-indigo-700 dark:text-indigo-400">
                    This record will be saved with your user account and timestamp. You can add multiple call records for the same customer on different days.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
