<x-app-layout>
    <div x-data="{ 
        showPaymentModal: false, 
        selectedProject: {{ json_encode($project) }},
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
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="text-xs text-gray-400 dark:text-slate-500 mb-1.5 flex items-center gap-1.5">
                <a href="{{ route('projects.index') }}"
                    class="hover:text-indigo-600 dark:hover:text-indigo-400">Projects</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('projects.show', $project->id) }}"
                    class="hover:text-indigo-600 dark:hover:text-indigo-400">{{ $project->project_name }}</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span>Edit</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Project</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update client and financial details for <span
                    class="font-semibold text-gray-700 dark:text-gray-300">{{ $project->project_name }}</span></p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" @click="openPayment(selectedProject)"
                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-indigo-600/25 flex items-center gap-2 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Manage Payment
            </button>
            <a href="{{ route('projects.show', $project->id) }}"
                class="px-4 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-700 rounded-xl text-sm font-semibold transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Cancel
            </a>
        </div>
    </div>

    @php
        $taxRateVal = old('tax_rate', $project->tax_rate);
        // Find the matching tax name from settings
        $matchedTax = collect($settings->available_taxes)->firstWhere('rate', $taxRateVal);
        $taxTypeName = $matchedTax ? $matchedTax['name'] : ($taxRateVal == 0 ? 'No Tax' : 'GST');
        $suppCharges = $project->projectAmounts
            ->filter(fn($a) => !str_contains($a->description, '(Base Amount)') && $a->description !== $project->project_name && !str_starts_with($a->description, 'Payment via'))
            ->map(fn($a) => [
                'description' => $a->description,
                'project_amount' => $a->project_amount,
                'project_gst' => $a->project_gst,
                'total_amount' => $a->total_amount,
            ])
            ->values();
    @endphp
    <form action="{{ route('projects.update', $project->id) }}" method="POST" x-data="{
        baseAmount: '{{ old('base_amount', $project->base_amount) }}',
        taxRate: '{{ $taxRateVal }}',
        taxType: '{{ $taxTypeName }}',
        currencySymbol: '{{ $project->project_currency }}',
        showSupplementary: false,
        supplementaryCharges: {{ Illuminate\Support\Js::from($suppCharges) }},
        suppDesc: '',
        suppBase: '',
        get totalAmount() {
            let base = parseFloat(this.baseAmount) || 0;
            let tax  = parseFloat(this.taxRate) || 0;
            return (base + base * tax / 100).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        get suppTotalVal() {
            let base = parseFloat(this.suppBase) || 0;
            let tax  = parseFloat(this.taxRate) || 0;
            return (base * (1 + tax / 100)).toFixed(2);
        },
        addCharge() {
            if (this.suppDesc && this.suppBase) {
                let base  = parseFloat(this.suppBase) || 0;
                let tax   = parseFloat(this.taxRate) || 0;
                let total = base * (1 + tax / 100);
                let gst   = total - base;
                this.supplementaryCharges.push({
                    description:    this.suppDesc,
                    project_amount: base.toFixed(2),
                    project_gst:    gst.toFixed(2),
                    total_amount:   total.toFixed(2)
                });
                this.suppDesc = '';
                this.suppBase = '';
                this.showSupplementary = false;
            }
        },
        removeCharge(index) {
            this.supplementaryCharges.splice(index, 1);
        }
    }">
        @csrf
        @method('PATCH')

        <div class="space-y-8">

            {{-- ── Section 1: Client Information ── --}}
            <div
                class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center gap-2.5">
                    <div class="w-2 h-2 rounded-full bg-indigo-600"></div>
                    <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Client
                        Information</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-7">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">
                            Client Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="client_name" value="{{ old('client_name', $project->client_name) }}"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow">
                        @error('client_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">Client
                            Email</label>
                        <input type="email" name="client_email"
                            value="{{ old('client_email', $project->client_email) }}"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">Phone
                            Number</label>
                        <input type="text" name="client_phone" value="{{ old('client_phone', $project->client_phone) }}"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">Alternate
                            Phone</label>
                        <input type="text" name="customer_alt_phone"
                            value="{{ old('customer_alt_phone', $project->customer_alt_phone) }}"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">Company
                            Name</label>
                        <input type="text" name="customer_company"
                            value="{{ old('customer_company', $project->customer_company) }}"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">GST
                            Number</label>
                        <input type="text" name="client_gst" value="{{ old('client_gst', $project->client_gst) }}"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-mono text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">Client
                            Address</label>
                        <textarea name="client_address" rows="2"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow resize-none">{{ old('client_address', $project->client_address) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ── Section 2: Project Details ── --}}
            <div
                class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center gap-2.5">
                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                    <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Project
                        Details</h2>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-7">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">
                            Project Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="project_name" value="{{ old('project_name', $project->project_name) }}"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-shadow">
                        @error('project_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">Currency</label>
                        <select name="project_currency" @change="currencySymbol = $event.target.value"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            @foreach($settings->available_currencies as $currency)
                                @php $val = $currency['symbol'];
                                $label = $currency['name'] . ' (' . $currency['symbol'] . ')'; @endphp
                                <option value="{{ $val }}" {{ old('project_currency', $project->project_currency) === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            @foreach(['Draft', 'Active', 'On Hold', 'Completed'] as $statusOption)
                                <option value="{{ $statusOption }}" {{ old('status', $project->status) === $statusOption ? 'selected' : '' }}>
                                    {{ $statusOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- ── Section 2.5: Tax Calculation Method ── --}}
            <div
                class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center gap-2.5 text-indigo-600 dark:text-indigo-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <h2 class="text-xs font-bold uppercase tracking-wider">Tax Calculation Method</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Dynamic taxes from codec_settings (read-only / locked) --}}
                        @foreach($settings->available_taxes as $tax)
                            <button type="button"
                                class="relative p-6 border-2 rounded-2xl transition-all duration-300 flex flex-col items-center gap-2 overflow-hidden opacity-90 cursor-default"
                                :class="taxRate == {{ $tax['rate'] }} && taxType == '{{ $tax['name'] }}'
                                        ? 'border-indigo-600 dark:border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300'
                                        : 'border-gray-100 dark:border-slate-700 text-gray-400 dark:text-slate-500 bg-gray-50/50 dark:bg-slate-900/50'">
                                <div x-show="taxRate == {{ $tax['rate'] }} && taxType == '{{ $tax['name'] }}'"
                                    class="absolute top-0 right-0 w-8 h-8 bg-indigo-600 dark:bg-indigo-500 rounded-bl-2xl flex items-center justify-center text-white">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="text-sm font-black uppercase tracking-widest">{{ $tax['name'] }}
                                    ({{ $tax['rate'] }}%)</span>
                                <span class="text-[10px] font-bold opacity-60 uppercase">Locked</span>
                            </button>
                        @endforeach
                        {{-- No Tax card --}}
                        <button type="button"
                            class="relative p-6 border-2 rounded-2xl transition-all duration-300 flex flex-col items-center gap-2 overflow-hidden opacity-90 cursor-default"
                            :class="taxRate == 0
                                ? 'border-indigo-600 dark:border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300'
                                : 'border-gray-100 dark:border-slate-700 text-gray-400 dark:text-slate-500 bg-gray-50/50 dark:bg-slate-900/50'">
                            <div x-show="taxRate == 0"
                                class="absolute top-0 right-0 w-8 h-8 bg-indigo-600 dark:bg-indigo-500 rounded-bl-2xl flex items-center justify-center text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span class="text-sm font-black uppercase tracking-widest">No Tax (0%)</span>
                            <span class="text-[10px] font-bold opacity-60 uppercase">Locked</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ── Section 3: Financial Details ── --}}
            <div
                class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center gap-2.5">
                    <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                    <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Financial
                        Details</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-7">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">
                            Base Amount (excl. tax) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span
                                class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500 font-semibold text-sm"
                                x-text="currencySymbol"></span>
                            <input type="number" name="base_amount" x-model="baseAmount" step="0.01" readonly
                                class="w-full pl-8 pr-4 py-2.5 bg-gray-100/50 dark:bg-slate-800/50 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-500 dark:text-gray-400 cursor-not-allowed">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">
                            <span x-text="taxType"></span> Rate (%) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="tax_rate" x-model="taxRate" step="0.01" readonly
                                class="w-full px-4 py-2.5 bg-gray-100/50 dark:bg-slate-800/50 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-500 dark:text-gray-400 cursor-not-allowed">
                            <span
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500 text-sm font-semibold">%</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">
                            Total Amount <span
                                class="text-emerald-600 dark:text-emerald-400 font-normal normal-case">(auto-calculated)</span>
                        </label>
                        <div
                            class="w-full px-4 py-2.5 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-xl text-sm font-bold text-indigo-700 dark:text-indigo-300 flex items-center gap-1">
                            <span x-text="currencySymbol"></span>
                            <span x-text="totalAmount"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Supplementary Charges Manager ── --}}
            <div class="mt-8">
                {{-- Toggle Button --}}
                <div class="mb-6">
                    <button type="button" x-show="!showSupplementary" @click="showSupplementary = true"
                        class="inline-flex items-center gap-2 px-6 py-2 border-2 border-indigo-600 dark:border-indigo-500 rounded-full text-indigo-600 dark:text-indigo-400 text-xs font-black uppercase tracking-widest hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all active:scale-95 shadow-sm">
                        <span>+ Add Supplementary Charge</span>
                    </button>
                </div>

                <div class="space-y-3 mb-6">
                    <template x-for="(charge, index) in supplementaryCharges" :key="index">
                        <div
                            class="flex items-center justify-between p-4 bg-gray-50/50 dark:bg-slate-700 rounded-2xl border border-gray-200/50 dark:border-slate-600 group hover:shadow-md transition-all">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-lg">
                                    <span x-text="currencySymbol"></span>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900 dark:text-white"
                                        x-text="charge.description"></div>
                                    <div class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Base: <span x-text="currencySymbol"></span><span x-text="charge.project_amount || charge.base_amount"></span> +
                                        <span x-text="taxType"></span>: <span x-text="currencySymbol"></span><span
                                            x-text="charge.project_gst || charge.gst_amount"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-6">
                                <div class="text-right">
                                    <div class="text-sm font-black text-indigo-600 dark:text-indigo-400"><span x-text="currencySymbol"></span><span
                                            x-text="charge.total_amount"></span></div>
                                    <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Total
                                        Amount</div>
                                </div>
                                <button type="button" @click="removeCharge(index)"
                                    class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                            <input type="hidden" :name="'supplementary_charges['+index+'][description]'"
                                :value="charge.description">
                            <input type="hidden" :name="'supplementary_charges['+index+'][base_amount]'"
                                :value="charge.project_amount || charge.base_amount">
                            <input type="hidden" :name="'supplementary_charges['+index+'][gst_amount]'"
                                :value="charge.project_gst || charge.gst_amount">
                            <input type="hidden" :name="'supplementary_charges['+index+'][total_amount]'"
                                :value="charge.total_amount">
                        </div>
                    </template>
                </div>

                {{-- ── Entry Form ── --}}
                <div x-show="showSupplementary" x-transition:enter="transition ease-out duration-300"
                    class="bg-white dark:bg-slate-800 rounded-2xl border-2 border-dashed border-indigo-200 dark:border-indigo-900/50 overflow-hidden mb-8 shadow-lg">
                    <div
                        class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                            <h2
                                class="text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-widest">
                                Add New Supplementary Charge</h2>
                        </div>
                        <button type="button" @click="showSupplementary = false; suppDesc = ''; suppBase = '';"
                            class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-6">
                            <div class="md:col-span-1">
                                <label
                                    class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Description:</label>
                                <input type="text" x-model="suppDesc" placeholder="e.g. Server Hosting"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-shadow outline-none">
                            </div>
                            <div>
                                <label
                                    class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Base
                                    Amount (<span x-text="currencySymbol"></span>):</label>
                                <input type="number" x-model="suppBase" step="0.01" placeholder="0.00"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-shadow outline-none">
                            </div>
                            <div>
                                <label
                                    class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Total
                                    (Incl. <span x-text="taxType"></span>):</label>
                                <div
                                    class="w-full px-4 py-3 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-xl text-sm font-bold text-indigo-700 dark:text-indigo-300 flex items-center gap-1">
                                    <span x-text="currencySymbol"></span>
                                    <span x-text="suppTotalVal"></span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="addCharge()"
                                class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] font-bold rounded-lg transition-all shadow-sm active:scale-95">
                                Confirm & Add
                            </button>
                            <button type="button" @click="showSupplementary = false; suppDesc = ''; suppBase = '';"
                                class="px-6 py-2.5 bg-white dark:bg-slate-800 hover:bg-gray-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-[11px] font-bold rounded-lg border border-gray-200 dark:border-slate-600 transition-all active:scale-95">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Total Project Cost (from codec_project_amount) ── --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="relative px-6 py-4 flex items-center">
                    {{-- Left: title + badge --}}
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></div>
                        <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Total Project Cost</h2>
                        <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-2.5 py-0.5 rounded-full uppercase tracking-wider">From Database</span>
                    </div>
                    {{-- Center: pill absolutely centered in full row --}}
                    <div class="absolute left-1/2 -translate-x-1/2 flex items-center gap-2 px-5 py-2 bg-emerald-50 dark:bg-emerald-900/20 border-2 border-emerald-200 dark:border-emerald-700 rounded-xl">
                        <svg class="w-4 h-4 text-emerald-500 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-lg font-black text-emerald-700 dark:text-emerald-300 tracking-tight">
                            {{ $project->project_currency }}{{ number_format($totalProjectCost, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- ── Section 5: Service Renewal & Subscription ── --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden"
                x-data="{ hasRenewal: {{ old('has_renewal', false) ? 'true' : 'false' }} }">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Service
                        Renewal & Subscription</h2>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-12">
                        {{-- Renewal Toggle & Status --}}
                        <div
                            class="flex items-center justify-between p-4 bg-gray-50/50 dark:bg-slate-800/50 rounded-2xl border border-gray-100 dark:border-slate-700/50 shadow-sm transition-colors">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">Enable
                                    Renewal:</label>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="has_renewal" x-model="hasRenewal"
                                        class="w-5 h-5 text-indigo-600 bg-white dark:bg-slate-900 border-gray-300 dark:border-slate-700 rounded-lg focus:ring-indigo-500 transition-all">
                                    <span
                                        class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-200 transition-colors">Check
                                        this if the project is a recurring service.</span>
                                </label>
                            </div>
                            <div class="text-right" x-data="{ renewalStatus: 'Active' }">
                                <label
                                    class="block text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Current
                                    Status:</label>
                                <select name="renewal_status" x-model="renewalStatus"
                                    :class="renewalStatus === 'Active' ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 border-emerald-200/50 dark:border-emerald-500/30' : 'bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 border-red-200/50 dark:border-red-500/30'"
                                    class="appearance-none px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border transition-all cursor-pointer outline-none focus:ring-0">
                                    <option value="Active">● Active</option>
                                    <option value="Inactive">● Inactive</option>
                                </select>
                            </div>
                        </div>

                        {{-- Duration Dropdown --}}
                        <div
                            class="p-4 bg-gray-50/50 dark:bg-slate-800/50 rounded-2xl border border-gray-100 dark:border-slate-700/50 shadow-sm transition-colors">
                            <label
                                class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1.5 uppercase tracking-wide">Duration
                                (Months):</label>
                            <select name="renewal_duration"
                                class="w-full px-4 py-2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-shadow">
                                <option value="1">1 Month</option>
                                <option value="3">3 Months (Quarterly)</option>
                                <option value="6">6 Months (Half-Yearly)</option>
                                <option value="12">12 Months (Yearly)</option>
                                <option value="24">24 Months</option>
                            </select>
                        </div>

                        {{-- Dates: Start & Next --}}
                        <div class="grid grid-cols-2 gap-6">
                            <div class="relative group">
                                <label
                                    class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Service
                                    Start Date:</label>
                                <div class="relative cursor-pointer" @click="$refs.serviceStart.showPicker()">
                                    <input type="date" name="service_start_date" x-ref="serviceStart"
                                        class="w-full pl-4 pr-10 py-2.5 bg-gray-50/50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-shadow transition-colors"
                                        style="color-scheme: dark;">
                                    <svg class="w-4 h-4 text-gray-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none group-focus-within:text-indigo-500 transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="relative group">
                                <label
                                    class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Next
                                    Renewal Date:</label>
                                <div class="relative cursor-pointer" @click="$refs.nextRenewal.showPicker()">
                                    <input type="date" name="next_renewal_date" x-ref="nextRenewal"
                                        class="w-full pl-4 pr-10 py-2.5 bg-gray-50/50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-indigo-600 dark:text-indigo-400 font-bold focus:ring-2 focus:ring-indigo-500 transition-shadow transition-colors"
                                        style="color-scheme: dark;">
                                    <svg class="w-4 h-4 text-indigo-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none group-focus-within:text-indigo-600 transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Reminders & Frequency --}}
                        <div class="grid grid-cols-2 gap-6">
                            <div class="relative group">
                                <label
                                    class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Renewal
                                    Reminder Date (7 Days Prior):</label>
                                <div class="relative cursor-pointer" @click="$refs.reminderDate.showPicker()">
                                    <input type="date" name="renewal_reminder_date" x-ref="reminderDate"
                                        class="w-full pl-4 pr-10 py-2.5 bg-gray-50/50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-red-600 dark:text-red-400 font-bold focus:ring-2 focus:ring-red-500 transition-shadow transition-colors"
                                        style="color-scheme: dark;">
                                    <svg class="w-4 h-4 text-red-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none group-focus-within:text-red-600 transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Payment
                                    Frequency:</label>
                                <select name="payment_frequency"
                                    class="w-full px-4 py-2.5 bg-gray-50/50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-shadow">
                                    <option value="One-time">One-time Payment</option>
                                    <option value="Installments">Pay in Installments</option>
                                    <option value="Milestones">Milestone Based</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-3 mt-8">
                <a href="{{ route('projects.show', $project->id) }}"
                    class="px-5 py-2.5 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition-colors shadow-lg shadow-indigo-600/25 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Update Project
                </button>
            </div>
        </div>
    </form>
    @include('projects.partials.payment-modal')
    </div>
</x-app-layout>