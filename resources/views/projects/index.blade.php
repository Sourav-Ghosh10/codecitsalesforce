<x-app-layout>
    <div x-data="{ 
        showPaymentModal: false, 
        selectedProject: null,
        paymentInput: 0,
        paymentDate: '{{ date('Y-m-d') }}',
        paymentMethod: '',
        openPayment(p) {
            this.selectedProject = p;
            this.paymentInput = 0;
            this.paymentMethod = '';
            this.showPaymentModal = true;
        },
        get gstAmount() {
            let amt = parseFloat(this.paymentInput) || 0;
            let rate = parseFloat(this.selectedProject?.tax_rate) || 0;
            if (rate === 0) return '0.00';
            let gst = amt - (amt / (1 + (rate / 100)));
            return gst.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        get actualAmount() {
            let amt = parseFloat(this.paymentInput) || 0;
            let rate = parseFloat(this.selectedProject?.tax_rate) || 0;
            let base = amt / (1 + (rate / 100));
            return base.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }">
    {{-- Page Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Projects</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Manage all client projects, budgets, and payments
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.create') }}"
               class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition-colors shadow-lg shadow-indigo-600/25 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Project
            </a>
        </div>
    </div>

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-gray-100 dark:border-slate-700 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Total Projects</div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-gray-100 dark:border-slate-700 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['active'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Active</div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-gray-100 dark:border-slate-700 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['completed'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Completed</div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-gray-100 dark:border-slate-700 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['revenue'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Total Revenue</div>
        </div>
    </div>

    {{-- Filter + Table --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">

        {{-- Filter Bar --}}
        <form action="{{ route('projects.index') }}" method="GET" class="p-5 border-b border-gray-100 dark:border-slate-700 flex flex-col sm:flex-row items-start sm:items-center gap-3">
            @if($statusFilter)
                <input type="hidden" name="status" value="{{ $statusFilter }}">
            @endif
            <div class="relative flex-1 w-full sm:max-w-xs">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ $search ?? '' }}" 
                    x-on:input.debounce.500ms="$el.closest('form').submit()"
                    onfocus="var val=this.value; this.value=''; this.value=val;"
                    {{ $search ? 'autofocus' : '' }}
                    placeholder="Search projects, clients…"
                    class="w-full pl-10 pr-4 py-2 bg-gray-50 dark:bg-slate-700 border-0 rounded-xl text-sm text-gray-700 dark:text-gray-300 placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-indigo-500">
            </div>

            {{-- Status Filters --}}
            <div class="flex gap-2 flex-wrap">
                @foreach(['all' => 'All', 'active' => 'Active', 'completed' => 'Completed', 'on hold' => 'On Hold', 'draft' => 'Draft'] as $val => $label)
                    <a href="{{ route('projects.index', array_merge(request()->query(), ['status' => $val])) }}"
                       class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors
                              {{ ($statusFilter ?? 'all') === $val
                                  ? 'bg-indigo-600 text-white'
                                  : 'bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:text-indigo-600' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-700/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Project</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Base Amt</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($projects as $project)
                        @php
                            $statusColors = [
                                'Active'    => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400',
                                'Completed' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                                'On Hold'   => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400',
                                'Draft'     => 'bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-400',
                            ];
                            $sc = $statusColors[$project->status] ?? $statusColors['Draft'];
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-6 py-4 text-xs text-gray-400 dark:text-slate-500 font-mono">
                                {{ str_pad($project->id, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('projects.show', $project->id) }}"
                                   class="text-sm font-semibold text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    {{ $project->project_name }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-700 dark:text-gray-300">{{ $project->client_name }}</div>
                                <div class="text-xs text-gray-400 dark:text-slate-500">{{ $project->client_email }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $project->customer_company }}</td>
                            <td class="px-6 py-4 text-right text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $project->project_currency ?? '₹' }}{{ number_format($project->calculated_base_amount ?? 0, 2) }}
                            </td>
                             <td class="px-6 py-4 text-right text-sm font-bold text-gray-900 dark:text-white">
                                {{ $project->project_currency ?? '₹' }}{{ number_format($project->calculated_total_amount ?? 0, 2) }}
                                @php 
                                    $totalAmt = $project->calculated_total_amount ?? 0;
                                    $paidAmt = $project->calculated_paid_amount ?? 0;
                                @endphp
                                @if($totalAmt > 0 && $paidAmt >= $totalAmt)
                                    <div class="text-[10px] text-emerald-500 font-medium flex items-center justify-end gap-1 mt-0.5">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        Fully Paid
                                    </div>
                                @elseif($totalAmt > 0)
                                    <div class="text-[10px] text-amber-500 font-medium flex items-center justify-end gap-1 mt-0.5">
                                        {{ $project->project_currency ?? '₹' }}{{ number_format($totalAmt - $paidAmt, 2) }} Pending
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-tight {{ $sc }}">
                                    {{ $project->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-xs text-gray-500 dark:text-gray-400">
                                {{ $project->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                     <button type="button" @click="openPayment({{ json_encode($project) }})"
                                        class="p-1.5 text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-colors" title="Manage Payments">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </button>
                                    <a href="{{ route('projects.show', $project->id) }}"
                                       class="p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="View">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('projects.edit', $project->id) }}"
                                       class="p-1.5 text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-16 text-center">
                                <div class="w-14 h-14 bg-gray-100 dark:bg-slate-700/50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-700 dark:text-white mb-1">No projects found</p>
                                <p class="text-xs text-gray-400 dark:text-slate-500">Try a different filter or create a new project.</p>
                                <a href="{{ route('projects.create') }}"
                                   class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition-colors">
                                    + New Project
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    {{-- Payment Modal --}}
    @include('projects.partials.payment-modal')
    </div>
</x-app-layout>
