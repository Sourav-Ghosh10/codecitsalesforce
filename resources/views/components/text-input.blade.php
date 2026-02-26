@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'premium-input block mt-1 w-full border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm bg-slate-900/50 text-white']) }}>