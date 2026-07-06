@extends('layouts.app')@section('title', 'Servicio #'.$servicio->id)@section('content')<div class="max-w-7xl mx-auto">
<div class="flex items-center justify-between mb-6">
<div class="flex items-baseline gap-3">
<h2 class="text-2xl font-extrabold tracking-tight">Servicio #{{ $servicio->id }}</h2>
<span class="status @switch($servicio->estado) @case('asignado') status-pending @break @case('inicio_servicio') status-active @break @case('en_sitio_origen') status-active @break @case('en_carga') status-active @break @case('en_transito') status-active @break @case('en_sitio_destino') status-active @break @case('finalizado') status-success @break @case('cancelado') status-danger @break @endswitch">
<span class="status-dot"></span>
{{ str_replace('_', ' ', ucfirst($servicio->estado)) }}</span>
</div>
<div class="flex items-center gap-2">
<a href="{{ route('servicios.index') }}" class="btn btn-secondary">← Volver</a>            @if ((auth()->user()->isAdmin() || auth()->user()->isCotizador()) && !in_array($servicio->estado, ['finalizado', 'cancelado']))            <a href="{{ route('servicios.edit', $servicio) }}" class="btn btn-primary">Editar</a>            @endif        </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-[1.5fr_1fr] gap-5">
<div class="space-y-5">
<div class="card">
<div class="card-header">
<h3>Datos del Servicio</h3>
</div>
<div class="card-body">
<div class="grid grid-cols-2 gap-4 text-sm">
<div>
<span class="text-gray-500">Cliente</span>
<p class="font-semibold">{{ $servicio->cotizacion?->cliente?->nombre ?: '—' }}</p>
</div>
<div>
<span class="text-gray-500">Cotización</span>
<p class="font-semibold">{{ $servicio->cotizacion?->folio ?: '—' }}</p>
</div>
<div>
<span class="text-gray-500">Operador</span>
<p class="font-semibold">{{ $servicio->operador?->empleado?->nombreCompleto() ?: '—' }}</p>
</div>
<div>
<span class="text-gray-500">Unidad</span>
<p class="font-semibold">{{ $servicio->unidad?->marca }} {{ $servicio->unidad?->placas ? '('.$servicio->unidad->placas.')' : '—' }}</p>
</div>
<div>
<span class="text-gray-500">Oficina</span>
<p class="font-semibold">{{ $servicio->oficina?->nombre ?: '—' }}</p>
</div>
<div>
<span class="text-gray-500">Tipo de Servicio</span>
<p class="font-semibold">{{ $servicio->tipoServicio?->nombre ?: '—' }}</p>
</div>
<div>
<span class="text-gray-500">Inicio</span>
<p class="font-semibold">{{ $servicio->fecha_inicio?->format('d/m/Y H:i') ?: '—' }}</p>
</div>
<div>
<span class="text-gray-500">Fin</span>
<p class="font-semibold">{{ $servicio->fecha_fin?->format('d/m/Y H:i') ?: '—' }}</p>
</div>
@if ($servicio->descripcion)
<div class="col-span-2">
<span class="text-gray-500">Descripción</span>
<p class="font-semibold text-gray-700">{{ $servicio->descripcion }}</p>
</div>
@endif
</div>
</div>
</div>
@if ($servicio->observaciones)
<div class="card">
<div class="card-header">
<h3>Observaciones</h3>
</div>
<div class="card-body">
<p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $servicio->observaciones }}</p>
</div>
</div>
@endif
</div>
<div class="space-y-5">
<div class="card">
<div class="card-header">
<h3>Kilometraje</h3>
</div>
<div class="card-body">
<div class="grid grid-cols-2 gap-4 text-sm">
<div>
<span class="text-gray-500">Kms salida</span>
<p class="font-semibold">{{ $servicio->kms_salida ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Kms llegada cliente</span>
<p class="font-semibold">{{ $servicio->kms_llegada_cliente ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Kms término servicio</span>
<p class="font-semibold">{{ $servicio->kms_termino_servicio ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Kms regreso base</span>
<p class="font-semibold">{{ $servicio->kms_regreso_base ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Kms cobrados reales</span>
<p class="font-semibold">{{ $servicio->kms_cobrados_reales ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Costo final real</span>
<p class="font-semibold">${{ $servicio->costo_final_real ? number_format($servicio->costo_final_real, 2) : '—' }}</p>
</div>
</div>
</div>
</div>
</div>
</div>
</div>@endsection