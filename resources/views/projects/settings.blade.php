<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Invoice Settings</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Configure your company identity and default invoice
            parameters</p>
    </div>

    {{-- ── Flash Messages ── --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="flex items-center gap-3 px-5 py-4 mb-2 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 text-emerald-700 dark:text-emerald-400 shadow-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
            <button type="button" @click="show = false" class="ml-auto text-emerald-500 hover:text-emerald-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="flex items-start gap-3 px-5 py-4 mb-2 rounded-2xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-400 shadow-sm">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
            </svg>
            <ul class="text-sm font-medium space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('projects.settings.save') }}" method="POST" class="space-y-8">
        @csrf

        {{-- ── Company Profile ── --}}
        <div
            class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center gap-2.5">
                <div class="w-2 h-2 rounded-full bg-indigo-600"></div>
                <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Company Details
                </h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-7">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">Company
                        Name</label>
                    <input type="text" name="company_name" value="{{ $config->company_name }}"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">Company
                        Address</label>
                    <textarea name="company_address" rows="3"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none">{{ $config->company_address }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">Company
                        GST Number</label>
                    <input type="text" name="company_gst" value="{{ $config->company_gst }}"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">Company
                        Mobile</label>
                    <input type="text" name="company_mobile" value="{{ $config->company_mobile }}"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">Company
                        Email</label>
                    <input type="email" name="company_email" value="{{ $config->company_email }}"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">Company
                        Website</label>
                    <input type="text" name="company_website" value="{{ $config->company_website }}"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">UPI ID
                        (for QR Code)</label>
                    <input type="text" name="upi_id" value="{{ $config->upi_id }}"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        placeholder="e.g. yourname@bank">
                </div>
            </div>
        </div>

        {{-- ── Bank Details ── --}}
        <div
            class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center gap-2.5">
                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Bank Details
                </h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">Bank
                        Name</label>
                    <input type="text" name="bank_name" value="{{ $config->bank_name }}"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">Account
                        Number</label>
                    <input type="text" name="bank_account" value="{{ $config->bank_account }}"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">IFSC
                        Code</label>
                    <input type="text" name="bank_ifsc" value="{{ $config->bank_ifsc }}"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">Branch</label>
                    <input type="text" name="bank_branch" value="{{ $config->bank_branch }}"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>
            </div>
        </div>

        {{-- ── Invoice Configuration ── --}}
        <div
            class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center gap-2.5">
                <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Invoice
                    Configuration</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-7">
                <div x-data="{ 
                    currencies: {{ json_encode($config->available_currencies) }} 
                }" class="space-y-3">
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">Available
                        Currencies</label>

                    <div class="space-y-3">
                        <template x-for="(currency, index) in currencies" :key="index">
                            <div class="flex items-center gap-3 animate-in fade-in slide-in-from-top-1 duration-200">
                                <div class="flex-1">
                                    <input type="text" :name="'currencies['+index+'][symbol]'" x-model="currency.symbol"
                                        placeholder="Symbol (e.g. $)"
                                        class="w-full px-4 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                </div>
                                <div class="flex-1">
                                    <input type="text" :name="'currencies['+index+'][name]'" x-model="currency.name"
                                        placeholder="Name (e.g. USD)"
                                        class="w-full px-4 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                </div>
                                <button type="button" @click="currencies.splice(index, 1)"
                                    x-show="currencies.length > 1"
                                    class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <button type="button" @click="currencies.push({symbol: '', name: ''})"
                        class="mt-2 inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-700 rounded-xl text-xs font-bold transition-all shadow-sm transform active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Add More
                    </button>
                    <p class="text-[10px] text-gray-400 mt-2 italic">Add currency symbols and names for your invoices.
                    </p>
                </div>

                <div x-data="{ 
                    taxes: {{ json_encode($config->available_taxes) }} 
                }" class="space-y-3">
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">Available
                        Tax Options</label>

                    <div class="space-y-3">
                        <template x-for="(tax, index) in taxes" :key="index">
                            <div class="flex items-center gap-3 animate-in fade-in slide-in-from-top-1 duration-200">
                                <div class="flex-[2]">
                                    <input type="text" :name="'taxes['+index+'][name]'" x-model="tax.name"
                                        placeholder="Tax Name (e.g. GST)"
                                        class="w-full px-4 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                </div>
                                <div class="flex-1 relative">
                                    <input type="number" :name="'taxes['+index+'][rate]'" x-model="tax.rate" step="0.01"
                                        placeholder="Rate"
                                        class="w-full px-4 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all pr-8">
                                    <span
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-bold">%</span>
                                </div>
                                <button type="button" @click="taxes.splice(index, 1)" x-show="taxes.length > 1"
                                    class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <button type="button" @click="taxes.push({name: '', rate: ''})"
                        class="mt-2 inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-700 rounded-xl text-xs font-bold transition-all shadow-sm transform active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Add More
                    </button>
                    <p class="text-[10px] text-gray-400 mt-2 italic">Define tax labels and percentages (e.g. GST, 18%).
                        These will be available when creating invoices.</p>
                </div>

                {{-- Currency Conversion Rates --}}
                <div x-data="{ 
                    rates: {{ json_encode($config->currency_conversion_rates ?? [['currency' => 'USD', 'rate' => 83.5]]) }} 
                }" class="md:col-span-2 space-y-3 pt-6 border-t border-gray-100 dark:border-slate-700">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">
                        Currency Conversion Rates (to INR ₹)
                    </label>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <template x-for="(rate, index) in rates" :key="index">
                            <div class="flex items-center gap-3 animate-in fade-in slide-in-from-top-1 duration-200">
                                <div class="flex-1">
                                    <select :name="'rates['+index+'][currency]'" x-model="rate.currency"
                                        class="w-full px-4 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                        @foreach($config->available_currencies as $curr)
                                            <option value="{{ $curr['name'] }}">{{ $curr['name'] }} ({{ $curr['symbol'] }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex-1 relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none" style="padding-left: 16px;">
                                        <span class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">1 Unit = ₹</span>
                                    </div>
                                    <input type="number" :name="'rates['+index+'][rate]'" x-model="rate.rate" step="0.001"
                                        placeholder="0.00"
                                        style="padding-left: 95px;"
                                        class="w-full pr-4 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                </div>
                                <button type="button" @click="rates.splice(index, 1)" x-show="rates.length > 0"
                                    class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <button type="button" @click="rates.push({currency: '', rate: ''})"
                        class="mt-2 inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-700 rounded-xl text-xs font-bold transition-all shadow-sm transform active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Add Conversion Rate
                    </button>
                    <p class="text-[10px] text-gray-400 mt-2 italic">Set how much 1 unit of each currency is worth in INR (e.g. 1 USD = 83.50 INR). This is used for Total Revenue calculation.</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">Default
                        Notes</label>
                    <textarea name="default_notes" rows="2"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none">{{ $config->default_notes }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase">Default
                        Terms & Conditions</label>
                    <textarea name="default_terms" rows="3"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none">{{ $config->default_terms }}</textarea>
                </div>
            </div>
        </div>

        {{-- ── Form Actions ── --}}
        <div class="flex items-center justify-end">
            <button type="submit"
                class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-600/25 flex items-center gap-2 transform active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Save Changes
            </button>
        </div>
    </form>
</x-app-layout>