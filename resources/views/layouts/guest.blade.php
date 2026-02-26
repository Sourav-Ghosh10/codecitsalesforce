<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CRM') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-100 antialiased selection:bg-indigo-500 selection:text-white">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#020617] relative overflow-hidden">
        <!-- Decorative Background Elements -->
        <div class="absolute top-0 -left-4 w-96 h-96 bg-indigo-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 animate-pulse"></div>
        <div class="absolute bottom-0 -right-4 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 animate-pulse delay-700"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-indigo-500/5 rounded-full blur-[120px]"></div>

        <div class="z-10 animate-fade-in mb-8">
            <a href="/" class="flex flex-col items-center gap-4">
                <div class="w-100 h-20 bg-white/5 backdrop-blur-xl rounded-2xl flex items-center justify-center p-4 border    shadow-2xl">
                    <img src="{{ asset('assets/img/logo_white.png') }}" alt="{{ config('app.name', 'CRM') }}" class="w-full h-full object-contain">
                </div>
                <!-- <div class="text-center">
                    <span class="text-3xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white via-indigo-200 to-gray-400">
                        {{ config('app.name', 'CRM') }}
                    </span>
                    <span class="block text-[10px] font-bold text-indigo-400 uppercase tracking-[0.2em] mt-1">Management System</span>
                </div> -->
            </a>
        </div>

        <div class="w-full sm:max-w-md px-8 py-10 bg-white/5 backdrop-blur-2xl rounded-3xl border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.3)] z-10 animate-fade-in" style="animation-delay: 0.1s;">
            {{ $slot }}
        </div>

        <div class="z-10 mt-8 text-center animate-fade-in" style="animation-delay: 0.2s;">
            <p class="text-xs text-slate-500">© {{ date('Y') }} {{ config('app.name', 'CRM') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>