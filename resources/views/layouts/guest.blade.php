<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SHOO E-Commerce') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Teachers:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center bg-[#584C08] p-4">
            
            <div class="mb-6 text-center scale-90">
                <a href="/" class="flex items-center gap-2 text-white">
                    <span class="text-3xl font-bold tracking-wider">SHOO</span>
                    <span class="text-xl font-light">E-Commerce</span>
                </a>
            </div>

            <div class="w-full sm:max-w-[380px] px-7 py-8 bg-[#FFFAE0] shadow-[0_20px_50px_rgba(0,0,0,0.5)] rounded-[28px] overflow-hidden transition-all">
                {{ $slot }}
            </div>
            
        </div>
    </body>
</html>