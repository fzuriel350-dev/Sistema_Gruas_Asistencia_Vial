@extends('layouts.app')
@section('title', 'Mi Panel')
@section('content')
<div class="max-w-7xl mx-auto">
    @if (session('success'))
        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 animate-fade-in">
            {{ session('success') }}
        </div>
    @endif

    <div class="welcome-banner rounded-xl p-6 lg:p-8 mb-6 relative overflow-hidden">
        <div class="absolute right-[-40px] top-[-40px] w-[220px] h-[220px] rounded-full" style="background: radial-gradient(circle, rgba(255,213,0,0.1) 0%, transparent 70%);"></div>
        <div class="absolute left-[60%] bottom-[-60px] w-[300px] h-[300px] rounded-full" style="background: radial-gradient(circle, rgba(255,213,0,0.05) 0%, transparent 70%);"></div>
        <div class="absolute left-0 bottom-0 w-full h-[3px]" style="background: linear-gradient(90deg, var(--geg-yellow), transparent);"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white mb-1">Hola, <span style="color: var(--geg-yellow);">{{ Auth::user()->name }}</span></h2>
                <p class="text-sm text-white/50">Bienvenido a tu panel de control</p>
            </div>
            <div class="flex items-center gap-4 text-white/40 text-xs">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ now()->format('d/m/Y') }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <div class="stat-card animate-fade-in-up">
            <div class="stat-icon" style="background: linear-gradient(135deg, #FFF3B0, #FFE066); color: #B39500;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $pendientes }}</div>
                <div class="stat-label">Cotizaciones pendientes</div>
            </div>
        </div>
        <div class="stat-card animate-fade-in-up" style="animation-delay: 0.1s;">
            <div class="stat-icon" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #059669;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $aprobadas }}</div>
                <div class="stat-label">Cotizaciones aprobadas</div>
            </div>
        </div>
        <div class="stat-card animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fef3c7, #fde68a); color: #D97706;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $activos }}</div>
                <div class="stat-label">Servicios activos</div>
            </div>
        </div>
        <div class="stat-card animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ede9fe, #ddd6fe); color: #7c3aed;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $finalizados }}</div>
                <div class="stat-label">Servicios finalizados</div>
            </div>
        </div>
    </div>

    @if ($activos > 0)
    <div class="card animate-fade-in-up mb-5" style="animation-delay: 0.15s;">
        <div class="card-header">
            <h3>Servicio en curso</h3>
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse-soft"></span>
                Activo
            </span>
        </div>
        <div class="card-body">
            <div class="flex items-center justify-between p-4 rounded-xl" style="background: linear-gradient(135deg, #f0fdf4, #ecfdf5);">
                <div>
                    <p class="text-sm text-gray-500 mb-0.5">Folio</p>
                    <p class="font-bold text-gray-900">#{{ $servicioActivo->cotizacion->folio ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-0.5">Estado</p>
                    <p class="font-semibold text-emerald-700 capitalize">{{ str_replace('_', ' ', $servicioActivo->estado ?? '—') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-0.5">Operador</p>
                    <p class="font-semibold text-gray-900">{{ $servicioActivo->operador->empleado->nombre ?? '—' }}</p>
                </div>
                <a href="{{ route('clientes.servicio-show', $servicioActivo) }}" class="btn btn-primary text-sm">
                    Ver detalle
                </a>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-[1.6fr_1fr] gap-5 mb-5">
        <div class="card animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="card-header">
                <h3>Servicios por mes</h3>
                <span class="text-xs font-semibold px-3 py-1 rounded-full" style="background: var(--geg-yellow-light); color: var(--geg-yellow-dark);">{{ now()->year }}</span>
            </div>
            <div class="card-body">
                @php $hasData = $serviciosPorMes->sum() > 0; @endphp
                @if ($hasData)
                <div class="flex items-end gap-3 h-[180px] pt-4">
                    @foreach (range(1, 12) as $m)
                        @php
                            $total = $serviciosPorMes->get(str_pad($m, 2, '0', STR_PAD_LEFT), 0);
                            $max = $serviciosPorMes->max() ?: 1;
                            $height = max(round(($total / $max) * 100), $total > 0 ? 8 : 0);
                            $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                        @endphp
                        <div class="flex-1 flex flex-col items-center gap-1.5 h-full justify-end">
                            <span class="text-[11px] font-bold {{ $total > 0 ? 'text-gray-800' : 'text-gray-300' }} order-first">{{ $total }}</span>
                            <div class="chart-bar {{ $total === 0 ? 'opacity-20' : '' }}" style="height: {{ $height }}%;"></div>
                            <span class="text-[11px] text-gray-500 font-medium">{{ $meses[$m - 1] }}</span>
                        </div>
                    @endforeach
                </div>
                @else
                <div class="flex flex-col items-center justify-center h-[180px] text-gray-400">
                    <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <p class="text-sm">Aún no hay datos este año</p>
                </div>
                @endif
            </div>
        </div>
        <div class="card animate-fade-in-up" style="animation-delay: 0.25s;">
            <div class="card-header">
                <h3>Actividad reciente</h3>
            </div>
            <div class="card-body py-2">
                @forelse ($actividades as $activity)
                    <div class="flex items-start gap-3 px-4 py-2.5 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="activity-dot activity-dot-{{ $activity['dot'] }}"></div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs text-gray-800 leading-tight">{!! $activity['text'] !!}</div>
                            <div class="text-[11px] text-gray-500 mt-0.5">{{ $activity['time'] }}</div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center py-10 text-gray-400">
                        <svg class="w-10 h-10 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm">Aún no tienes actividad.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="card animate-fade-in-up" style="animation-delay: 0.3s;">
        <div class="card-header">
            <h3>Accesos rápidos</h3>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('clientes.cotizaciones') }}" class="group flex items-center gap-4 p-5 rounded-xl border-2 border-gray-100 hover:border-[#FFD500] hover:bg-[#FFFDF0] transition-all duration-200">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #FFF3B0, #FFE066);">
                        <svg class="w-6 h-6" style="color: #B39500;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-bold text-sm text-gray-800 group-hover:text-[#B39500] transition-colors">Mis cotizaciones</div>
                        <div class="text-xs text-gray-500 mt-0.5">Ver y gestionar cotizaciones</div>
                    </div>
                </a>
                <a href="{{ route('clientes.servicios') }}" class="group flex items-center gap-4 p-5 rounded-xl border-2 border-gray-100 hover:border-[#FFD500] hover:bg-[#FFFDF0] transition-all duration-200">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0);">
                        <svg class="w-6 h-6" style="color: #059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-bold text-sm text-gray-800 group-hover:text-[#B39500] transition-colors">Mis servicios</div>
                        <div class="text-xs text-gray-500 mt-0.5">Dar seguimiento a tus servicios</div>
                    </div>
                </a>
                <a href="{{ route('clientes.notificaciones') }}" class="group flex items-center gap-4 p-5 rounded-xl border-2 border-gray-100 hover:border-[#FFD500] hover:bg-[#FFFDF0] transition-all duration-200">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
                        <svg class="w-6 h-6" style="color: #D97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-bold text-sm text-gray-800 group-hover:text-[#B39500] transition-colors">Notificaciones</div>
                        <div class="text-xs text-gray-500 mt-0.5">Ver tus notificaciones</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
