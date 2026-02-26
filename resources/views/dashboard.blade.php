<x-app-layout>
    <!-- Dashboard Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Welcome back, {{ Auth::user()->name }}! 
                @if(Auth::user()->isAdmin())
                    <span class="text-indigo-600 dark:text-indigo-400">(Administrator)</span>
                @elseif(Auth::user()->isManager())
                    <span class="text-purple-600 dark:text-purple-400">(Manager)</span>
                @else
                    <span class="text-emerald-600 dark:text-emerald-400">(Agent)</span>
                @endif
            </p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <input type="date" class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            @can('create', App\Models\Client::class)
            <button class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium transition-colors shadow-lg shadow-indigo-600/25">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Client
                </span>
            </button>
            @endcan
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Clients Card -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <span class="px-2.5 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-semibold rounded-full">+{{ $stats['clientsGrowth'] ?? 0 }}%</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['totalClients'] ?? 0) }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total Clients</p>
            <div class="mt-4 h-1.5 bg-gray-100 dark:bg-slate-700 rounded-full overflow-hidden">
                <div class="h-full bg-indigo-600 rounded-full" style="width: 75%"></div>
            </div>
        </div>

        <!-- Active Leads Card -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <span class="px-2.5 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-semibold rounded-full">+{{ $stats['leadsGrowth'] ?? 0 }}%</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['activeLeads'] ?? 0) }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Active Leads</p>
            <div class="mt-4 h-1.5 bg-gray-100 dark:bg-slate-700 rounded-full overflow-hidden">
                <div class="h-full bg-amber-500 rounded-full" style="width: 60%"></div>
            </div>
        </div>

        <!-- Revenue Card -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="px-2.5 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-semibold rounded-full">+{{ $stats['revenueGrowth'] ?? 0 }}%</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($stats['totalRevenue'] ?? 0) }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total Revenue</p>
            <div class="mt-4 h-1.5 bg-gray-100 dark:bg-slate-700 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full" style="width: 85%"></div>
            </div>
        </div>

        <!-- Pending Tasks Card -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-rose-100 dark:bg-rose-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <span class="px-2.5 py-1 bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 text-xs font-semibold rounded-full">-3</span>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['pendingTasks'] ?? 0 }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pending Tasks</p>
            <div class="mt-4 h-1.5 bg-gray-100 dark:bg-slate-700 rounded-full overflow-hidden">
                <div class="h-full bg-rose-500 rounded-full" style="width: 35%"></div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Charts & Tables -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Revenue Chart -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Revenue Overview</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Monthly performance breakdown</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="px-3 py-1.5 text-xs font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors">Week</button>
                        <button class="px-3 py-1.5 text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg">Month</button>
                        <button class="px-3 py-1.5 text-xs font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors">Year</button>
                    </div>
                </div>
                <!-- Simple Bar Chart Representation -->
                <div class="flex items-end justify-between h-48 gap-3">
                    @php
                        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                        $values = [45, 62, 55, 78, 65, 90];
                    @endphp
                    @foreach($months as $index => $month)
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-full bg-indigo-100 dark:bg-indigo-900/30 rounded-t-lg relative group cursor-pointer transition-all hover:bg-indigo-200 dark:hover:bg-indigo-900/50" style="height: {{ $values[$index] * 2 }}px; min-height: 20px;">
                                <div class="absolute bottom-0 left-0 right-0 bg-indigo-600 rounded-t-lg h-0 group-hover:h-full transition-all duration-300" style="height: {{ $values[$index] * 2 }}px;"></div>
                                <div class="absolute -top-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-gray-900 dark:bg-slate-700 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">${{ number_format($values[$index] * 1000) }}</div>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ $month }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Clients Table -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Clients</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Latest client additions</p>
                    </div>
                    <a href="{{ route('clients.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-slate-700/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Company</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Value</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold">JD</div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">John Doe</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">john@techcorp.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">TechCorp Solutions</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">Active</span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">$12,500</td>
                                <td class="px-6 py-4 text-right">
                                    <button class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white text-sm font-bold">AS</div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">Alice Smith</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">alice@designhub.io</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">Design Hub</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400">Pending</span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">$8,200</td>
                                <td class="px-6 py-4 text-right">
                                    <button class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar Widgets -->
        <div class="space-y-8">
            <!-- Tasks Widget -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tasks</h3>
                    <button class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                </div>
                <div class="space-y-4">
                    <!-- Task 1 -->
                    <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors cursor-pointer group">
                        <div class="relative mt-0.5">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-5 h-5 border-2 border-gray-300 dark:border-slate-600 rounded-full peer-checked:bg-indigo-600 peer-checked:border-indigo-600 transition-colors cursor-pointer"></div>
                            <svg class="absolute top-0.5 left-0.5 w-4 h-4 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Follow up with John Doe</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Due today • 10:00 AM</p>
                        </div>
                        <span class="px-2 py-0.5 bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 text-[10px] font-semibold rounded-full">Urgent</span>
                    </div>
                    <!-- Task 2 -->
                    <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors cursor-pointer group">
                        <div class="relative mt-0.5">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-5 h-5 border-2 border-gray-300 dark:border-slate-600 rounded-full peer-checked:bg-indigo-600 peer-checked:border-indigo-600 transition-colors cursor-pointer"></div>
                            <svg class="absolute top-0.5 left-0.5 w-4 h-4 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Prepare quarterly report</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Due tomorrow</p>
                        </div>
                    </div>
                </div>
                <button class="w-full mt-4 py-2.5 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-colors">View All Tasks</button>
            </div>

            <!-- Upcoming Events -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Upcoming</h3>
                <div class="space-y-4">
                    <!-- Event 1 -->
                    <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors cursor-pointer">
                        <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex flex-col items-center justify-center">
                            <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">24</span>
                            <span class="text-[10px] font-medium text-indigo-500 dark:text-indigo-400 uppercase">Feb</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">Client Strategy Session</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">10:00 AM • Zoom Meeting</p>
                        </div>
                    </div>
                    <!-- Event 2 -->
                    <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors cursor-pointer">
                        <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex flex-col items-center justify-center">
                            <span class="text-xs font-bold text-amber-600 dark:text-amber-400">25</span>
                            <span class="text-[10px] font-medium text-amber-500 dark:text-amber-400 uppercase">Feb</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">Contract Review</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">2:30 PM • Legal Team</p>
                        </div>
                    </div>
                </div>
                <button class="w-full mt-4 py-2.5 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-colors">View Calendar</button>
            </div>

            <!-- Quick Actions -->
            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl p-6 text-white">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-3">
                    <button class="flex flex-col items-center gap-2 p-4 bg-white/10 hover:bg-white/20 rounded-xl transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        <span class="text-xs font-medium">Add Client</span>
                    </button>
                    <button class="flex flex-col items-center gap-2 p-4 bg-white/10 hover:bg-white/20 rounded-xl transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span class="text-xs font-medium">Log Call</span>
                    </button>
                    <button class="flex flex-col items-center gap-2 p-4 bg-white/10 hover:bg-white/20 rounded-xl transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-xs font-medium">Create Task</span>
                    </button>
                    <button class="flex flex-col items-center gap-2 p-4 bg-white/10 hover:bg-white/20 rounded-xl transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span class="text-xs font-medium">Send Email</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
