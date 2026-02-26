@props(['active', 'href' => '#'])

@php
$baseClasses = 'w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group';

if ($active ?? false) {
    $classes = $baseClasses . ' bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400';
} else {
    $classes = $baseClasses . ' text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-700/50';
}
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
