@extends('layouts.app')@section('title', 'Servicio #'.$servicio->id)@section('content')<div class="max-w-4xl mx-auto">
<div class="card">
<div class="card-header">
<h3>Servicio #{{ $servicio->id }}</h3>
<div class="flex items-center gap-2">
<a href="{{ route('servicios.index') }}" class="btn btn-sm btn-ghost">Volver</a>                @if (auth()->user()->isEmpleado() && $servicio->estado !== 'finalizado' && $servicio->estado !== 'cancelado')                <a href="{{ route('servicios.edit', $servicio) }}" class="btn btn-sm btn-primary">Editar</a>                @endif            </div>
</div>
<div class="card-body">
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div>
<p class="text-xs text-gray-500 uppercase font-semibold">Cliente</p>
<p class="font-medium">{{ $servicio->cotizacion?->cliente?->nombre ?: '—' }}</p>
</div>
<div>
<p class="text-xs text-gray-500 uppercase font-semibold">Cotización</p>
<p class="font-medium">{{ $servicio->cotizacion?->folio ?: '—' }}</p>
</div>
<div>
<p class="text-xs text-gray-500 uppercase font-semibold">Operador</p>
<p class="font-medium">{{ $servicio->operador?->empleado?->nombreCompleto() ?: '—' }}</p>
</div>
<div>
<p class="text-xs text-gray-500 uppercase font-semibold">Unidad</p>
<p class="font-medium">{{ $servicio->unidad?->marca }} {{ $servicio->unidad?->placas ? '('.$servicio->unidad->placas.')' : '' }}</p>
</div>
<div>
<p class="text-xs text-gray-500 uppercase font-semibold">Estado</p>
<p>
<span class="status @switch($servicio->estado) @case('asignado') status-pending @break @case('en_proceso') status-active @break @case('finalizado') status-success @break @case('cancelado') status-danger @break @endswitch">
<span class="status-dot">
</span> {{ str_replace('_', ' ', ucfirst($servicio->estado)) }}</span>
</p>
</div>
                <div>
<p class="text-xs text-gray-500 uppercase font-semibold">Tipo</p>
<p class="font-medium">{{ $servicio->tipoServicio?->nombre ?: '—' }}</p>
</div>                @if ($servicio->descripcion)                <div class="md:col-span-2">
<p class="text-xs text-gray-500 uppercase font-semibold">Descripción</p>
<p class="font-medium text-gray-700">{{ $servicio->descripcion }}</p>
</div>                @endif
<div>
<p class="text-xs text-gray-500 uppercase font-semibold">Inicio</p>
<p class="font-medium">{{ $servicio->fecha_inicio?->format('d/m/Y H:i') ?: '—' }}</p>
</div>
<div>
<p class="text-xs text-gray-500 uppercase font-semibold">Fin</p>
<p class="font-medium">{{ $servicio->fecha_fin?->format('d/m/Y H:i') ?: '—' }}</p>
</div>
</div>
</div>
</div>
</div>@endsection
