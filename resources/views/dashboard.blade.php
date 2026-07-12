<x-app-layout>    @if ($role === 'operador')        {{-- Dashboard Operador --}}        <div class="welcome-banner rounded-xl p-5 lg:p-7 mb-6 relative overflow-hidden">
<div class="absolute right-[-40px] top-[-40px] w-[180px] h-[180px] rounded-full" style="background: radial-gradient(circle, rgba(255,213,0,0.08) 0%, transparent 70%);">
</div>
<div class="absolute left-0 bottom-0 w-full h-[3px]" style="background: linear-gradient(90deg, var(--geg-yellow), transparent);">
</div>
<div>
<h2 class="text-xl font-bold text-white mb-1">Bienvenido, <span style="color: var(--geg-yellow);">{{ Auth::user()->name }}</span>
</h2>
<p class="text-[13.5px] text-white/60">Tienes <strong class="text-white/90">{{ $servicios_asignados }} servicios asignados</strong> hoy</p>
</div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
<div class="stat-card">
<div class="stat-icon" style="background: #d1fae5; color: var(--geg-success);">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
</svg>
</div>
<div class="stat-info">
<div class="stat-value">{{ $servicios_asignados }}</div>
<div class="stat-label">Asignados hoy</div>
</div>
</div>
<div class="stat-card">
<div class="stat-icon" style="background: #fef3c7; color: var(--geg-yellow-dark);">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
</div>
<div class="stat-info">
<div class="stat-value">{{ $servicios_hoy }}</div>
<div class="stat-label">Completados hoy</div>
</div>
</div>
</div>
<div class="card">
<div class="card-header">
<h3>Próximos servicios</h3>
</div>
<div class="card-body text-center py-10 text-gray-500">
<svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
</svg>
<p class="text-sm">No tienes servicios pendientes.</p>
</div>
</div>    @elseif ($role === 'cliente')        {{-- Dashboard Cliente --}}        <div class="welcome-banner rounded-xl p-6 lg:p-8 mb-6 relative overflow-hidden">
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
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
<div class="stat-card">
<div class="stat-icon" style="background: linear-gradient(135deg, var(--geg-yellow-light), color-mix(in srgb, var(--geg-yellow) 30%, white)); color: var(--geg-yellow-dark);">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
</svg>
</div>
<div class="stat-info">
<div class="stat-value">{{ $cotizacionesPendientes }}</div>
<div class="stat-label">Cotizaciones pendientes</div>
</div>
</div>
<div class="stat-card">
<div class="stat-icon" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #059669;">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
</svg>
</div>
<div class="stat-info">
<div class="stat-value">{{ $serviciosActivos }}</div>
<div class="stat-label">Servicios activos</div>
</div>
</div>
<div class="stat-card">
<div class="stat-icon" style="background: linear-gradient(135deg, #ede9fe, #ddd6fe); color: #7c3aed;">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
</div>
<div class="stat-info">
<div class="stat-value">{{ $serviciosFinalizados }}</div>
<div class="stat-label">Servicios finalizados</div>
</div>
</div>
</div>
@if ($servicioActivo)
<div class="card mb-5">
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
<a href="{{ route('clientes.servicio-show', $servicioActivo) }}" class="btn btn-primary text-sm">Ver detalle</a>
</div>
</div>
</div>
@endif
<div class="card">
<div class="card-header"><h3>Accesos rápidos</h3></div>
<div class="card-body">
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
<a href="{{ route('clientes.cotizaciones') }}" class="group flex items-center gap-4 p-5 rounded-xl border-2 border-gray-100 hover:border-[var(--geg-yellow)] hover:bg-[var(--geg-yellow-light)] transition-all duration-200">
<div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, var(--geg-yellow-light), color-mix(in srgb, var(--geg-yellow) 30%, white));">
<svg class="w-6 h-6" style="color: var(--geg-yellow-dark);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
</svg>
</div>
<div>
<div class="font-bold text-sm text-gray-800 group-hover:text-[var(--geg-yellow-dark)] transition-colors">Mis cotizaciones</div>
<div class="text-xs text-gray-500 mt-0.5">Ver y gestionar cotizaciones</div>
</div>
</a>
<a href="{{ route('clientes.servicios') }}" class="group flex items-center gap-4 p-5 rounded-xl border-2 border-gray-100 hover:border-[var(--geg-yellow)] hover:bg-[var(--geg-yellow-light)] transition-all duration-200">
<div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0);">
<svg class="w-6 h-6" style="color: #059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
</svg>
</div>
<div>
<div class="font-bold text-sm text-gray-800 group-hover:text-[var(--geg-yellow-dark)] transition-colors">Mis servicios</div>
<div class="text-xs text-gray-500 mt-0.5">Dar seguimiento a tus servicios</div>
</div>
</a>
<a href="{{ route('clientes.notificaciones') }}" class="group flex items-center gap-4 p-5 rounded-xl border-2 border-gray-100 hover:border-[var(--geg-yellow)] hover:bg-[var(--geg-yellow-light)] transition-all duration-200">
<div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
<svg class="w-6 h-6" style="color: #D97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
</svg>
</div>
<div>
<div class="font-bold text-sm text-gray-800 group-hover:text-[var(--geg-yellow-dark)] transition-colors">Notificaciones</div>
<div class="text-xs text-gray-500 mt-0.5">Ver tus notificaciones</div>
</div>
</a>
</div>
</div>
</div>    @else        {{-- Dashboard Admin / Cotizador --}}        <div class="welcome-banner rounded-xl p-5 lg:p-7 flex items-center justify-between gap-5 mb-6 relative overflow-hidden">
<div class="absolute right-[-40px] top-[-40px] w-[180px] h-[180px] rounded-full" style="background: radial-gradient(circle, rgba(255,213,0,0.08) 0%, transparent 70%);">
</div>
<div class="absolute left-0 bottom-0 w-full h-[3px]" style="background: linear-gradient(90deg, var(--geg-yellow), transparent);">
</div>
<div>
<h2 class="text-xl font-bold text-white mb-1">Bienvenido de nuevo, <span style="color: var(--geg-yellow);">{{ Auth::user()->name }}</span>
</h2>
<p class="text-[13.5px] text-white/60">Hoy tienes <strong class="text-white/90">{{ $stats['servicios_activos'] }} servicios activos</strong> y <strong class="text-white/90">{{ $stats['cotizaciones_pendientes'] }} cotizaciones pendientes</strong> por revisar.</p>
</div>
<div class="flex gap-2.5 shrink-0 flex-wrap">
<a href="{{ route('cotizaciones.index') }}" class="btn btn-primary">Ir a cotizaciones</a>
</div>
</div>        {{-- Stats --}}        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
<div class="stat-card">
<div class="stat-icon" style="background: var(--geg-yellow-light); color: var(--geg-yellow-dark);">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
</svg>
</div>
<div class="stat-info">
<div class="stat-value">{{ $stats['cotizaciones_pendientes'] }}</div>
<div class="stat-label">Cotizaciones pendientes</div>
<div class="stat-trend up">+3 esta semana</div>
</div>
</div>
<div class="stat-card">
<div class="stat-icon" style="background: #d1fae5; color: var(--geg-success);">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
</svg>
</div>
<div class="stat-info">
<div class="stat-value">{{ $stats['servicios_activos'] }}</div>
<div class="stat-label">Servicios activos</div>
<div class="stat-trend up">+2 vs ayer</div>
</div>
</div>
<div class="stat-card">
<div class="stat-icon" style="background: #fef3c7; color: var(--geg-yellow-dark);">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
</svg>
</div>
<div class="stat-info">
<div class="stat-value">{{ $stats['operadores_disponibles'] }}</div>
<div class="stat-label">Operadores disponibles</div>
<div class="stat-trend down">{{ $stats['operadores_ocupados'] }} ocupados</div>
</div>
</div>
<div class="stat-card">
<div class="stat-icon" style="background: #ede9fe; color: #7c3aed;">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
</div>
<div class="stat-info">
<div class="stat-value">${{ number_format($stats['ingresos_mes']) }}</div>
<div class="stat-label">Ingresos del mes</div>
<div class="stat-trend up">+12.5% vs mayo</div>
</div>
</div>
</div>        {{-- Main Grid --}}        <div class="grid grid-cols-1 lg:grid-cols-[1.6fr_1fr] gap-5 mb-5">
<div class="card">
<div class="card-header">
<h3>Servicios por día</h3>
<div class="flex items-center gap-2.5">
<span class="text-xs font-semibold px-3 py-1 rounded-full" style="background: var(--geg-yellow-light); color: var(--geg-yellow-dark);">Esta semana</span>
<a href="#" class="btn btn-sm btn-ghost-btn">Ver detalle</a>
</div>
</div>
<div class="card-body">
<div class="flex items-end gap-3 h-[180px] pt-4">                        @foreach ($dias as $day)                            <div class="flex-1 flex flex-col items-center gap-1.5 h-full justify-end">
<span class="text-[11px] font-bold text-gray-800 order-first">{{ $day['value'] }}</span>
<div class="chart-bar" style="height: {{ $day['height'] }}%;">
</div>
<span class="text-[11px] text-gray-500 font-medium">{{ $day['label'] }}</span>
</div>                        @endforeach                    </div>
</div>
</div>
<div class="card">
<div class="card-header">
<h3>Actividad reciente</h3>
<a href="#" class="btn btn-sm btn-ghost-btn">Ver todo</a>
</div>
<div class="card-body py-2">                    @foreach ($actividades as $activity)                        <div class="flex items-start gap-3 px-4 py-2.5 rounded-lg hover:bg-gray-50 transition-colors">
<div class="activity-dot activity-dot-{{ $activity['dot'] }}">
</div>
<div class="flex-1 min-w-0">
<div class="text-xs text-gray-800 leading-tight">{!! $activity['text'] !!}</div>
<div class="text-[11px] text-gray-500 mt-0.5">{{ $activity['time'] }}</div>
</div>
</div>                    @endforeach                </div>
</div>
</div>        {{-- Bottom Grid --}}        <div class="grid grid-cols-1 lg:grid-cols-[1.6fr_1fr] gap-5">
<div class="card">
<div class="card-header">
<h3>Servicios recientes</h3>
<a href="#" class="btn btn-sm btn-ghost-btn">Ver todos</a>
</div>
<div class="overflow-x-auto">
<table class="w-full">
<thead>
<tr>
<th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Folio</th>
<th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Cliente</th>
<th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Origen</th>
<th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Destino</th>
<th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border-b border-gray-100 bg-gray-50">Estado</th>
</tr>
</thead>
<tbody>                            @foreach ($servicios as $svc)                                <tr class="hover:bg-gray-50">
<td class="px-5 py-3 text-sm border-b border-gray-50">
<strong>{{ $svc['folio'] }}</strong>
</td>
<td class="px-5 py-3 text-sm border-b border-gray-50">{{ $svc['cliente'] }}</td>
<td class="px-5 py-3 text-sm border-b border-gray-50">{{ $svc['origen'] }}</td>
<td class="px-5 py-3 text-sm border-b border-gray-50">{{ $svc['destino'] }}</td>
<td class="px-5 py-3 text-sm border-b border-gray-50">
<span class="status status-{{ $svc['class'] }}">
<span class="status-dot">
</span> {{ $svc['estado'] }}</span>
</td>
</tr>                            @endforeach                        </tbody>
</table>
</div>
</div>
<div class="card">
<div class="card-header">
<h3>Nuevos clientes registrados</h3>
<a href="#" class="btn btn-sm btn-ghost-btn">Ver todos</a>
</div>
<div class="card-body py-2">                    @forelse ($nuevosClientes as $cliente)                        <div class="flex items-start gap-3 px-4 py-2.5 rounded-lg hover:bg-gray-50 transition-colors">
<div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold shrink-0">
{{ substr($cliente['name'], 0, 2) }}
</div>
<div class="flex-1 min-w-0">
<div class="text-xs text-gray-800 font-medium">{{ $cliente['name'] }}</div>
<div class="text-[11px] text-gray-500">{{ $cliente['email'] }}</div>
</div>
<div class="text-[11px] text-gray-400 shrink-0">{{ $cliente['time'] }}</div>
</div>                    @empty                        <div class="text-center py-8 text-gray-400 text-sm">Sin registros recientes</div>                    @endforelse                </div>
</div>
</div>    @endif    @push('scripts')    <style>        @keyframes pulse-dot {            0%, 100% { opacity: 1; }            50% { opacity: 0.3; }        }        .nav-active::before {            content: '';            position: absolute;            left: -10px;            top: 50%;            transform: translateY(-50%);            width: 3px;            height: 20px;            background: var(--geg-yellow);            border-radius: 0 3px 3px 0;        }        .nav-item {            position: relative;        }    </style>    @endpush</x-app-layout>
