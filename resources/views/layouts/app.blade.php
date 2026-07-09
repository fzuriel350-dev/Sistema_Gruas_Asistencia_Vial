<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if($empresa && $empresa->modo_oscuro) class="dark" @endif>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', config('app.name', 'Sistema de Grúas')) — Sistema de Grúas</title>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />    @vite(['resources/css/app.css', 'resources/js/app.js'])</head>
<body class="font-sans antialiased" style="background:var(--geg-bg);color:var(--geg-text)">
<div class="flex min-h-screen" x-data="{ sidebarOpen: false }">        {{-- Sidebar --}}        <aside class="fixed lg:sticky top-0 left-0 z-30 h-screen w-[260px] bg-gradient-to-b from-[#111] to-[#0a0a0a] text-white flex flex-col shrink-0 transition-transform duration-200 -translate-x-full lg:translate-x-0"               :class="{ 'translate-x-0': sidebarOpen }">            {{-- Overlay móvil --}}            <div class="fixed inset-0 bg-black/50 lg:hidden" x-show="sidebarOpen" @@click="sidebarOpen = false" style="display: none;">
</div>            {{-- Brand --}}            <div class="flex items-center gap-3.5 px-5 pt-5 pb-4">
<div class="w-[42px] h-[42px] rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, var(--geg-yellow), var(--geg-yellow-dark));">
<svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
</svg>
</div>
<div>
<h2 class="text-[15px] font-bold leading-tight">Grúas & Equipos</h2>
<small class="text-[10px] opacity-45 tracking-wide">@switch(auth()->user()->role) @case('admin') Panel de Administración @break @case('cotizador') Panel de Cotización @break @case('operador') Panel de Operador @break @case('cliente') Panel de Cliente @break @endswitch</small>
</div>
</div>            {{-- Accent bar --}}            <div class="h-[3px] shrink-0" style="background: linear-gradient(90deg, var(--geg-yellow), var(--geg-yellow-dark));">
</div>            {{-- Nav --}}            <nav class="flex-1 px-2.5 py-3 overflow-y-auto">                @php $role = auth()->user()->role; $isAdmin = $role === 'admin'; $isCotizador = $role === 'cotizador'; $isOperador = $role === 'operador'; $isCliente = $role === 'cliente'; $isEmpleado = $isAdmin || $isCotizador; @endphp                <div class="mb-0.5">
<div class="text-[10px] uppercase tracking-widest text-white/30 px-3 pt-2.5 pb-1.5 font-semibold">Principal</div>
<a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('dashboard') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
</svg>
<span>Dashboard</span>
</a>
</div>                @if ($isAdmin || $isCotizador)                <div class="mb-0.5">
<div class="text-[10px] uppercase tracking-widest text-white/30 px-3 pt-2.5 pb-1.5 font-semibold">Operación</div>
<a href="{{ route('cotizaciones.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('cotizaciones.*') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
</svg>
<span>Cotizaciones</span>
</a>
<a href="{{ route('servicios.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('servicios.*') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
</svg>
<span>Servicios</span>
@if ($serviciosActivos > 0)<span class="ml-auto bg-red-600 text-white text-[10px] px-2 py-0.5 rounded-full font-semibold">{{ $serviciosActivos }}</span>@endif
</a>
</div>                @endif                @if ($isAdmin || $isCotizador)                <div class="mb-0.5">
<div class="text-[10px] uppercase tracking-widest text-white/30 px-3 pt-2.5 pb-1.5 font-semibold">Catálogos</div>
<a href="{{ route('clientes.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('clientes.*') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
</svg>
<span>Clientes</span>
</a>
<a href="{{ route('aseguradoras.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('aseguradoras.*') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
</svg>
<span>Aseguradoras</span>
</a>
<a href="{{ route('tipos-servicio.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('tipos-servicio.*') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
</svg>
<span>Tipos de Servicio</span>
</a>
<a href="{{ route('convenios.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('convenios.*') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
</svg>
<span>Convenios</span>
</a>
<a href="{{ route('servicios-configurados.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('servicios-configurados.*') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
</svg>
<span>Servicios Configurados</span>
</a>
</div>                @endif                @if ($isAdmin)                <div class="mb-0.5">
<div class="text-[10px] uppercase tracking-widest text-white/30 px-3 pt-2.5 pb-1.5 font-semibold">Administración</div>
<a href="{{ route('usuarios.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('usuarios.*') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292V4.354zM15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m5-9.949a4 4 0 110 5.292V4.354z" />
</svg>
<span>Usuarios</span>
</a>
<a href="{{ route('empleados.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('empleados.*') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
</svg>
<span>Empleados</span>
</a>
<a href="{{ route('operadores.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('operadores.*') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
</svg>
<span>Operadores</span>
</a>
<a href="{{ route('unidades.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('unidades.*') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
</svg>
<span>Unidades</span>
</a>
<a href="{{ route('oficinas.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('oficinas.*') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
</svg>
<span>Oficinas</span>
</a>
<a href="{{ route('cargas-diesel.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('cargas-diesel.*') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
</svg>
<span>Cargas Diesel</span>
</a>
<a href="{{ route('bitacora-tiempos.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('bitacora-tiempos.*') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
<span>Bitácora Tiempos</span>
</a>
<a href="{{ route('autorizaciones-cancelacion.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('autorizaciones-cancelacion.*') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
</svg>
<span>Cancelaciones</span>
</a>
<a href="{{ route('facturas.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('facturas.*') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
</svg>
<span>Facturas</span>
</a>
<a href="{{ route('notificaciones.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('notificaciones.*') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
</svg>
<span>Notificaciones</span>
</a>
<a href="{{ route('configuracion.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('configuracion.*') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
</svg>
<span>Configuración</span>
</a>
</div>                @endif                @if ($isOperador)                <div class="mb-0.5">
<div class="text-[10px] uppercase tracking-widest text-white/30 px-3 pt-2.5 pb-1.5 font-semibold">Mis servicios</div>
<a href="{{ route('servicios.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('servicios.*') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
</svg>
<span>Servicios activos</span>
</a>
</div>                @endif                @if ($isCliente)
<div class="mb-0.5">
<div class="text-[10px] uppercase tracking-widest text-white/30 px-3 pt-2.5 pb-1.5 font-semibold">Servicios</div>
<a href="{{ route('clientes.cotizaciones') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('clientes.cotizaciones') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
</svg>
<span>Mis cotizaciones</span>
</a>
<a href="{{ route('clientes.servicios') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('clientes.servicios*') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
</svg>
<span>Mis servicios</span>
@if ($serviciosActivos > 0)<span class="ml-auto bg-red-600 text-white text-[10px] px-2 py-0.5 rounded-full font-semibold">{{ $serviciosActivos }}</span>@endif
</a>
<a href="{{ route('clientes.notificaciones') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('clientes.notificaciones*') ? 'text-[#FFD500] nav-active' : 'text-white/55 hover:text-white/85 hover:bg-white/5' }}">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
</svg>
<span>Notificaciones</span>
</a>
</div>                @endif            </nav>            {{-- Footer --}}            <div class="px-2.5 py-3 border-t border-white/5">
<a href="{{ $isCliente ? route('clientes.perfil') : route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-white/40 hover:text-white/70 transition-all duration-150">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
</svg>
<span>Mi Perfil</span>
</a>
<form method="POST" action="{{ route('logout') }}">                    @csrf                    <a href="{{ route('logout') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-white/40 hover:text-white/70 transition-all duration-150"                       onclick="event.preventDefault(); this.closest('form').submit();">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
</svg>
<span>Cerrar sesión</span>
</a>
</form>
</div>
</aside>        {{-- Main Content --}}        <div class="flex-1 flex flex-col min-w-0">            {{-- Topbar --}}            <header style="background:var(--geg-bg-card);border-color:var(--geg-border-light)" class="px-5 lg:px-7 py-3.5 flex items-center gap-5 border-b shadow-sm sticky top-0 z-20">                {{-- Mobile toggle --}}                <button class="lg:hidden p-2 -ml-2 rounded-lg hover:bg-gray-100" @@click="sidebarOpen = !sidebarOpen">
<svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
</svg>
</button>
<div class="flex items-baseline gap-3 flex-1">
<h1 class="text-xl font-bold text-gray-900 tracking-tight">@yield('title', 'Dashboard')</h1>
<span class="text-xs text-gray-500 font-medium hidden sm:inline">{{ now()->locale('es')->isoFormat('D [de] MMMM, YYYY') }}</span>
</div>
@if ($isCliente)
<form method="GET" action="{{ route('clientes.cotizaciones') }}" class="relative hidden sm:block">
<svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
</svg>
<input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar cotización..." class="pl-9 pr-3.5 py-2 rounded-lg text-sm border border-gray-200 bg-gray-50 focus:bg-white focus:outline-none w-48 lg:w-60 transition-all">
</form>
@else
<form method="GET" action="{{ route('clientes.index') }}" class="relative hidden sm:block">
<svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
</svg>
<input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar clientes..." class="pl-9 pr-3.5 py-2 rounded-lg text-sm border border-gray-200 bg-gray-50 focus:bg-white focus:outline-none w-48 lg:w-60 transition-all">
</form>
@endif
<div class="flex items-center gap-4">
<a href="{{ route('notificaciones.index') }}" class="relative w-9 h-9 rounded-xl border border-gray-200 bg-gray-50 flex items-center justify-center text-base hover:bg-[#FFF3B0] hover:border-[#FFD500] transition-all">
<svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
</svg>
<span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-600 rounded-full border-2 border-white">
</span>
</a>
<div class="flex items-center gap-2.5 cursor-pointer px-2 py-1.5 rounded-xl hover:bg-gray-50">
<div class="text-right hidden sm:block">
<div class="text-xs font-semibold text-gray-900">{{ Auth::user()->name }}</div>
<div class="text-[10.5px] text-gray-500 font-medium capitalize">{{ Auth::user()->role }}</div>
</div>
<div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold text-black" style="background: linear-gradient(135deg, var(--geg-yellow), var(--geg-yellow-dark));">                            {{ substr(Auth::user()->name, 0, 2) }}                        </div>
</div>
</div>
</header>            {{-- Page Content --}}            <main class="flex-1 overflow-y-auto">
<div class="p-5 lg:p-7 animate-fade-in-up">                    {{ $slot ?? '' }}                    @yield('content')                </div>
</main>
</div>
</div>    @stack('scripts')</body>
</html>
