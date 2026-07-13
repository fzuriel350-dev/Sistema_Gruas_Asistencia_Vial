<!DOCTYPE html>
<html lang="{{ $empresa && $empresa->idioma ? str_replace('_', '-', $empresa->idioma) : str_replace('_', '-', app()->getLocale()) }}" @if($empresa && $empresa->modo_oscuro) class="dark" @endif>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', config('app.name', 'Sistema de Grúas')) — {{ $empresa->nombre ?? 'Sistema de Grúas' }}</title>
<link rel="icon" type="image/x-icon" href="{{ $empresa && $empresa->favicon ? Storage::url($empresa->favicon) : asset('favicon.ico') }}">
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
@vite(['resources/css/app.css', 'resources/js/app.js'])
@if($empresa && $empresa->tipografia && $empresa->tipografia !== 'Inter')
<style>body,.font-sans{font-family:'{{ $empresa->tipografia }}',sans-serif!important;}</style>
@endif
</head>
<body class="font-sans antialiased text-gray-900" style="background: linear-gradient(135deg, #000000, #1a1a1a, #0F0F0F);">
<div class="min-h-screen flex items-center justify-center p-5">
<div class="w-full max-w-[460px] bg-white rounded-2xl overflow-hidden shadow-[0_20px_60px_rgba(0,0,0,0.3)]">
    {{-- Header --}}
    <div class="px-7 pt-10 pb-7 text-center" style="background: linear-gradient(135deg, {{ $empresa->color ?? '#FFD500' }}, {{ $empresa->color_secundario ?? $empresa->color ?? '#E6A000' }});">
        <div class="w-12 h-12 mx-auto mb-2.5 flex items-center justify-center">
            @if ($empresa && $empresa->logo)
                <img src="{{ Storage::url($empresa->logo) }}" alt="{{ $empresa->nombre }}" class="w-12 h-12 object-contain rounded-lg">
            @else
                <svg class="w-12 h-12 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            @endif
        </div>
        <h1 class="text-2xl font-bold text-black">{{ $empresa->nombre ?? 'Sistema de Grúas' }}</h1>
        <p class="text-sm text-black/80 mt-1">Inicia sesión para continuar</p>
    </div>
    {{-- Body --}}
    <div class="p-7">
        {{ $slot }}
    </div>
</div>
</div>
</body>
</html>