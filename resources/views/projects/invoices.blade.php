<x-app-layout>
    <style>
        .invoice-border {
            border-color: #e2e8f0 !important; /* slate-200 for light mode */
        }
        .dark .invoice-border {
            border-color: #1e293b !important; /* dark slate for dark mode */
        }
    </style>
    <div x-data="{ 
        showModal: false, 
        search: '', 
        selectedInvoices: [],
        selectAll: false,
        tax_rate: 18, 
        items: [{ desc: '', price: 0, qty: 1 }],
        toggleSelectAll() {
            if (this.selectAll) {
                this.selectedInvoices = ['PAY-001', 'PAY-002', 'PAY-003'];
            } else {
                this.selectedInvoices = [];
            }
        },
        addItem() { this.items.push({ desc: '', price: 0, qty: 1 }) },
        removeItem(index) { this.items.splice(index, 1) },
        get subtotal() {
            return this.items.reduce((sum, item) => sum + (item.price * item.qty), 0)
        }
    }" class="px-8 py-10 min-h-screen bg-transparent">

        <div class="max-w-[1600px] mx-auto">
            <!-- Header -->
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">Invoices</h1>
                    <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm font-medium">Manage all your project billing and PDF generation.</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none text-slate-400 dark:text-slate-500 group-focus-within:text-indigo-500 transition-colors" style="padding-left: 1.25rem;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input x-model="search" type="text" placeholder="Search invoices..." 
                               class="bg-white dark:bg-slate-900 border-none rounded-xl pr-4 py-3 text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 outline-none transition-all w-64 md:w-80 shadow-sm"
                               style="padding-left: 3.75rem;">
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="bg-white dark:bg-slate-900 border invoice-border rounded-3xl overflow-hidden shadow-xl dark:shadow-2xl">
                
                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest bg-slate-50 dark:bg-slate-800 border-b invoice-border">

                                <th class="px-6 py-6">Project Id</th>
                                <th class="px-6 py-6 text-center">Invoice</th>
                                <th class="px-6 py-6 text-right">Base Amt</th>
                                <th class="px-6 py-6 text-right">GST</th>
                                <th class="px-6 py-6 text-right">Amount</th>
                                <th class="px-6 py-6">Mode</th>
                                <th class="px-6 py-6">Date</th>
                                <th class="px-6 py-6">Status</th>
                                <th class="px-6 py-6 text-center">Invoice</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-400">
                            @foreach($invoices as $invoice)
                                @php
                                    $payments = $invoice->payments;
                                    // Fallback for older records where the relationship might not be linked in DB yet
                                    if ($payments->isEmpty() && !empty($invoice->data['payment_ids'])) {
                                        $payments = \App\Models\Payment::whereIn('id', $invoice->data['payment_ids'])->get();
                                    }
                                    
                                    $firstPay = $payments->first();
                                    $project = $firstPay?->project;
                                    $projectId = $invoice->data['project_id'] ?? ($project ? $project->id : '—');
                                    $currency = $project->project_currency ?? '₹';
                                    $modes = $payments->pluck('mode')->unique();
                                    $modeStr = $modes->count() > 1 ? 'Mixed' : ($modes->first() ?? '—');
                                @endphp
                                <tr x-show="!search || '{{ strtolower($invoice->invoice_number) }}'.includes(search.toLowerCase())" 
                                class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all border-b last:border-0 group invoice-border">
                                <td class="px-6 py-6">
                                    <span class="text-sm font-bold text-slate-500">#{{ $projectId }}</span>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <span class="text-sm font-bold text-indigo-400 underline underline-offset-4 decoration-transparent group-hover:decoration-indigo-400 transition-all">{{ $invoice->invoice_number }}</span>
                                </td>
                                <td class="px-6 py-6 text-right">
                                    <span class="text-sm font-bold text-slate-500 group-hover:text-slate-200 transition-colors">{{ $currency }}{{ number_format($invoice->base_amount) }}</span>
                                </td>
                                <td class="px-6 py-6 text-right">
                                    <span class="text-sm font-medium text-slate-500 group-hover:text-slate-200 transition-colors">{{ $currency }}{{ number_format($invoice->tax_amount) }}</span>
                                </td>
                                <td class="px-6 py-6 text-right">
                                    <span class="text-sm font-black text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-200 transition-colors">{{ $currency }}{{ number_format($invoice->total_amount) }}</span>
                                </td>
                                <td class="px-6 py-6">
                                    <span class="text-sm font-medium text-slate-500 group-hover:text-slate-300 transition-colors">{{ $modeStr }}</span>
                                </td>
                                <td class="px-6 py-6">
                                    <span class="text-sm font-medium text-slate-500 group-hover:text-slate-300 transition-colors">{{ $invoice->invoice_date ? $invoice->invoice_date->format('d M Y') : '—' }}</span>
                                </td>
                                <td class="px-6 py-6">
                                    <span class="inline-flex px-2 py-0.5 rounded-md text-[9px] font-black border uppercase tracking-widest {{ strtoupper($invoice->status) == 'PAID' ? 'bg-emerald-900/30 text-emerald-500 border-emerald-500/20' : 'bg-orange-900/30 text-orange-400 border-orange-500/20' }}">
                                        {{ $invoice->status ?? 'GENERATED' }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <a href="{{ route('projects.invoices.download', $invoice->id) }}" 
                                       class="p-2 inline-flex text-indigo-400 hover:text-white hover:bg-indigo-600/20 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                    </a>
                                </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
