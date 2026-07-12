@extends('layouts.app')@section('title', $cliente->nombre)@section('content')<div class="max-w-5xl mx-auto">
<div class="flex items-center justify-between mb-6">
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-xl bg-[color:var(--geg-yellow)]/20 flex items-center justify-center text-xl font-bold text-[#0F0F0F]">                {{ substr($cliente->nombre, 0, 1) }}            </div>
<div>
<h2 class="text-2xl font-extrabold tracking-tight">{{ $cliente->nombre }}</h2>
<p class="text-sm text-gray-500">{{ $cliente->telefono ? 'Tel. ' . $cliente->telefono : '' }}</p>
</div>
</div>
<a href="{{ route('clientes.index') }}" class="btn btn-secondary">← Volver</a>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
<div class="stat-card">
<div class="stat-icon bg-amber-50">📋</div>
<div>
<div class="stat-value">{{ $cliente->cotizaciones->count() }}</div>
<div class="stat-label">Cotizaciones</div>
</div>
</div>
<div class="stat-card">
<div class="stat-icon bg-blue-50">🚛</div>
<div>
<div class="stat-value">{{ $cliente->cotizaciones->where('estatus', 'aprobado')->count() }}</div>
<div class="stat-label">Aprobadas</div>
</div>
</div>
<div class="stat-card">
<div class="stat-icon bg-emerald-50">🏷️</div>
<div>
<div class="stat-value text-sm font-bold truncate">{{ $cliente->contacto ?? 'Sin contacto' }}</div>
<div class="stat-label">Contacto</div>
</div>
</div>
</div>
<div class="card">
<div class="card-header">
<h3>Historial de cotizaciones</h3>
</div>
<div class="table-container">
<table>
<thead>
<tr>
<th>Folio</th>
<th>Origen</th>
<th>Destino</th>
<th>Total</th>
<th>Fecha</th>
<th>Estado</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>                    @forelse ($cliente->cotizaciones as $cot)                    <tr>
<td>
<strong>{{ $cot->folio }}</strong>
</td>
<td class="max-w-[140px] truncate">{{ $cot->origen }}</td>
<td class="max-w-[140px] truncate">{{ $cot->destino }}</td>
<td>${{ number_format($cot->costo_total, 2) }}</td>
<td>{{ $cot->created_at->format('d/m/Y') }}</td>
<td>
<span class="status @switch($cot->estatus)                                @case('borrador') status-pending @break                                @case('pendiente') status-pending @break                                @case('aprobado') status-success @break                                @case('rechazado') status-danger @break                            @endswitch">
<span class="status-dot">
</span>                                {{ ucfirst($cot->estatus) }}                            </span>
</td>
<td>
<a href="{{ route('cotizaciones.show', $cot) }}" class="btn btn-sm btn-secondary">Ver</a>
</td>
</tr>                    @empty                    <tr>
<td colspan="7" class="text-center text-gray-500 py-8">Sin cotizaciones registradas.</td>
</tr>                    @endforelse                </tbody>
</table>
</div>
</div>
<div class="card">
<div class="card-header">
<h3>Datos del cliente</h3>
</div>
<div class="card-body">
<div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                    <div>
<span class="text-gray-500">ID</span>
<p class="font-semibold mt-0.5">{{ $cliente->id }}</p>
</div>
<div>
<span class="text-gray-500">Nombre</span>
<p class="font-semibold mt-0.5">{{ $cliente->nombre }}</p>
</div>
<div>
<span class="text-gray-500">Empresa</span>
<p class="font-semibold mt-0.5">{{ $cliente->empresa ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Teléfono</span>
<p class="font-semibold mt-0.5">{{ $cliente->telefono ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Email</span>
<p class="font-semibold mt-0.5">{{ $cliente->email ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Aseguradora</span>
<p class="font-semibold mt-0.5">{{ $cliente->aseguradora?->nombre ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Póliza / Cobertura</span>
<p class="font-semibold mt-0.5">{{ $cliente->numero_poliza ? $cliente->numero_poliza . ' — ' . ($cliente->tipo_cobertura_poliza ?? '') : '—' }}</p>
</div>
<div>
<span class="text-gray-500">Contacto</span>
<p class="font-semibold mt-0.5">{{ $cliente->contacto ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Dirección</span>
<p class="font-semibold mt-0.5">{{ $cliente->direccion ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Creado</span>
<p class="font-semibold mt-0.5">{{ $cliente->created_at->format('d/m/Y H:i') }}</p>
</div>
<div>
<span class="text-gray-500">Actualizado</span>
<p class="font-semibold mt-0.5">{{ $cliente->updated_at->format('d/m/Y H:i') }}</p>
</div>
@if ($cliente->trashed())
<div>
<span class="text-gray-500">Eliminado</span>
<p class="font-semibold mt-0.5 text-red-600">{{ $cliente->deleted_at->format('d/m/Y H:i') }}</p>
</div>
@endif
</div>
</div>
</div>
</div>@endsection
