<x-app-layout>    @if ($role === 'cliente')        {{-- Dashboard Cliente --}}        <div class="welcome-banner rounded-xl p-5 lg:p-7 mb-6 relative overflow-hidden">
<div class="absolute right-[-40px] top-[-40px] w-[180px] h-[180px] rounded-full" style="background: radial-gradient(circle, rgba(255,213,0,0.08) 0%, transparent 70%);">
</div>
<div class="absolute left-0 bottom-0 w-full h-[3px]" style="background: linear-gradient(90deg, var(--geg-yellow), transparent);">
</div>
<div>
<h2 class="text-xl font-bold text-white mb-1">Hola, <span style="color: var(--geg-yellow);">{{ Auth::user()->name }}</span>
</h2>
<p class="text-[13.5px] text-white/60">Bienvenido a tu panel de servicios</p>
</div>
<div class="flex gap-2.5 shrink-0 flex-wrap">
<a href="#" class="btn btn-primary">Solicitar servicio</a>
</div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
<div class="stat-card">
<div class="stat-icon" style="background: var(--geg-yellow-light); color: var(--geg-yellow-dark);">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
</svg>
</div>
<div class="stat-info">
<div class="stat-value">{{ $mis_cotizaciones }}</div>
<div class="stat-label">Mis cotizaciones</div>
</div>
</div>
<div class="stat-card">
<div class="stat-icon" style="background: #d1fae5; color: var(--geg-success);">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
</svg>
</div>
<div class="stat-info">
<div class="stat-value">{{ $mis_servicios }}</div>
<div class="stat-label">Servicios solicitados</div>
</div>
</div>
</div>
<div class="card">
<div class="card-header">
<h3>Mis últimos servicios</h3>
<a href="#" class="btn btn-sm btn-ghost-btn">Ver historial</a>
</div>
<div class="card-body text-center py-10 text-gray-500">
<svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
</svg>
<p class="text-sm">Aún no tienes servicios registrados.</p>
<a href="#" class="btn btn-primary mt-4">Solicitar servicio</a>
</div>
</div>    @elseif ($role === 'operador')        {{-- Dashboard Operador --}}        <div class="welcome-banner rounded-xl p-5 lg:p-7 mb-6 relative overflow-hidden">
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
<a href="#" class="btn btn-primary">+ Nueva cotización</a>
<a href="#" class="btn btn-ghost">Ver reportes</a>
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
<h3>Mapa de servicios</h3>
<span class="text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1.5" style="background: #fee2e2; color: var(--geg-danger);">
<span class="w-1.5 h-1.5 rounded-full bg-red-600" style="animation: pulse-dot 1.5s ease-in-out infinite;">
</span>                        En vivo                    </span>
</div>
<div class="map-placeholder h-[220px] rounded-none" style="border-radius: 0 0 12px 12px;">
<div class="map-grid">
</div>
<div class="relative z-10 text-center">
<svg class="w-8 h-8 mx-auto mb-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
</svg>
<div class="text-sm font-semibold text-gray-600">4 servicios activos en CDMX</div>
<div class="text-xs text-gray-500 mt-1">3 grúas en ruta · 1 en espera</div>
</div>
</div>
</div>
</div>    @endif    @push('scripts')    <style>        @keyframes pulse-dot {            0%, 100% { opacity: 1; }            50% { opacity: 0.3; }        }        .nav-active::before {            content: '';            position: absolute;            left: -10px;            top: 50%;            transform: translateY(-50%);            width: 3px;            height: 20px;            background: var(--geg-yellow);            border-radius: 0 3px 3px 0;        }        .nav-item {            position: relative;        }    </style>    @endpush</x-app-layout>
