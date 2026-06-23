@extends('layouts.app')@section('title', $cotizacione->folio)@section('content')<div class="max-w-5xl mx-auto">    @if (session('success'))        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">            {{ session('success') }}        </div>    @endif    <div class="flex items-center justify-between mb-6">
<div class="flex items-baseline gap-3">
<h2 class="text-2xl font-extrabold tracking-tight">{{ $cotizacione->folio }}</h2>
<span class="status @switch($cotizacione->estatus)                @case('borrador') status-pending @break                @case('pendiente') status-pending @break                @case('aprobado') status-success @break                @case('rechazado') status-danger @break            @endswitch">
<span class="status-dot">
</span>                {{ ucfirst($cotizacione->estatus) }}            </span>
</div>
<div class="flex items-center gap-3">
<a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary">← Volver</a>            @if (auth()->user()->isEmpleado() && in_array($cotizacione->estatus, ['borrador', 'pendiente']))            <a href="{{ route('cotizaciones.edit', $cotizacione) }}" class="btn btn-primary">Editar</a>            @endif        </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-[1.5fr_1fr] gap-5">
<div class="space-y-5">
<div class="card">
<div class="card-header">
<h3>Datos del Cliente</h3>
</div>
<div class="card-body">
<div class="grid grid-cols-2 gap-4 text-sm">
<div>
<span class="text-gray-500">Nombre</span>
<p class="font-semibold">{{ $cotizacione->cliente?->nombre }}</p>
</div>
<div>
<span class="text-gray-500">Teléfono</span>
<p class="font-semibold">{{ $cotizacione->cliente?->telefono ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Aseguradora</span>
<p class="font-semibold">{{ $cotizacione->aseguradora?->nombre }}</p>
</div>
<div>
<span class="text-gray-500">No. Póliza</span>
<p class="font-semibold">{{ $cotizacione->no_poliza ?? '—' }}</p>
</div>
</div>
</div>
</div>
<div class="card">
<div class="card-header">
<h3>Datos del Vehículo</h3>
</div>
<div class="card-body">
<div class="grid grid-cols-2 gap-4 text-sm">
<div>
<span class="text-gray-500">Marca</span>
<p class="font-semibold">{{ $cotizacione->marca }}</p>
</div>
<div>
<span class="text-gray-500">Modelo</span>
<p class="font-semibold">{{ $cotizacione->modelo }}</p>
</div>
<div>
<span class="text-gray-500">Color</span>
<p class="font-semibold">{{ $cotizacione->color ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Placas</span>
<p class="font-semibold">{{ $cotizacione->placas }}</p>
</div>
</div>
</div>
</div>
<div class="card">
<div class="card-header">
<h3>Ubicación y Ruta</h3>
</div>
<div class="card-body">
<div class="grid grid-cols-2 gap-4 text-sm mb-4">
<div>
<span class="text-gray-500">Origen</span>
<p class="font-semibold">{{ $cotizacione->origen }}</p>
</div>
<div>
<span class="text-gray-500">Destino</span>
<p class="font-semibold">{{ $cotizacione->destino }}</p>
</div>
<div>
<span class="text-gray-500">Distancia</span>
<p class="font-semibold">{{ number_format($cotizacione->distancia_km, 1) }} km</p>
</div>
<div>
<span class="text-gray-500">Tiempo estimado</span>
<p class="font-semibold">{{ $cotizacione->tiempo_estimado }} min</p>
</div>
<div>
<span class="text-gray-500">Tipo de servicio</span>
<p class="font-semibold capitalize">{{ $cotizacione->tipoServicio?->nombre ?? str_replace('_', ' ', $cotizacione->tipo_servicio ?? '') }}</p>
</div>
<div>
<span class="text-gray-500">Tipo de ruta</span>
<p class="font-semibold capitalize">{{ $cotizacione->tipo_ruta ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Peaje</span>
<p class="font-semibold">{{ $cotizacione->con_peaje ? 'Sí (' . $cotizacione->num_casetas . ' caseta(s))' : 'No' }}</p>
</div>
</div>
</div>
</div>            @if ($cotizacione->notas)            <div class="card">
<div class="card-header">
<h3>Notas</h3>
</div>
<div class="card-body">
<p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $cotizacione->notas }}</p>
</div>
</div>            @endif            <div class="text-xs text-gray-400 flex gap-4">
<span>Creado por: {{ $cotizacione->creador?->name }}</span>
<span>{{ $cotizacione->created_at->format('d/m/Y H:i') }}</span>
</div>
</div>
<div class="space-y-5">
<div class="card">
<div class="card-header">
<h3>Resumen de costos</h3>
</div>
<div class="card-body">
<div class="cost-summary">
<div class="cost-row">
<span>Banderazo</span>
<span>${{ number_format($cotizacione->costo_banderazo, 2) }}</span>
</div>
<div class="cost-row">
<span>Kilometraje ({{ number_format($cotizacione->distancia_km, 1) }} km × ${{ number_format($cotizacione->costo_km, 2) }}/km)</span>
<span>${{ number_format($cotizacione->costo_kilometraje, 2) }}</span>
</div>
<div class="cost-row">
<span>Casetas</span>
<span>${{ number_format($cotizacione->costo_casetas, 2) }}</span>
</div>
<div class="cost-row">
<span>Extras</span>
<span>${{ number_format($cotizacione->extras, 2) }}</span>
</div>                        @if ($cotizacione->descuento_monto > 0)                        <div class="cost-row" style="color: var(--geg-success);">
<span>Descuento ({{ number_format($cotizacione->descuento_porcentaje, 0) }}%)</span>
<span>-${{ number_format($cotizacione->descuento_monto, 2) }}</span>
</div>                        @endif                        <div class="cost-row">
<span>IVA (16%)</span>
<span>${{ number_format($cotizacione->iva, 2) }}</span>
</div>
<div class="cost-row total">
<span>Total estimado</span>
<span>${{ number_format($cotizacione->costo_total, 2) }}</span>
</div>
</div>
<div class="mt-4 text-xs text-gray-500">
<strong>Cobertura de seguro:</strong>
<div class="flex gap-2 mt-1">
<span class="status @switch($cotizacione->cobertura)                                @case('total') status-success @break                                @case('parcial') status-pending @break                                @default status-danger @break                            @endswitch">
<span class="status-dot">
</span>                                {{ ucfirst(str_replace('_', ' ', $cotizacione->cobertura)) }}                            </span>
</div>
</div>
</div>
</div>            @if ($cotizacione->convenio)            <div class="card">
<div class="card-header">
<h3>Convenio aplicable</h3>
</div>
<div class="card-body">
<div class="flex items-center gap-3 p-3 rounded-lg border" style="background: #f0fdf4; border-color: #bbf7d0;">
<div class="text-2xl">✅</div>
<div>
<div class="font-semibold text-sm">{{ $cotizacione->convenio->nombre }}</div>
<div class="text-xs text-gray-500">Descuento: {{ number_format($cotizacione->descuento_porcentaje, 0) }}%</div>
</div>
</div>
</div>
</div>            @endif        </div>
</div>
</div>@endsection
