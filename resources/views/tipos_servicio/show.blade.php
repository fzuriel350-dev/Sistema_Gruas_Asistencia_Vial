@extends('layouts.app')@section('title', $tiposServicio->nombre)@section('content')<div class="max-w-3xl mx-auto">
<div class="flex items-center justify-between mb-6">
<div>
<h2 class="text-2xl font-extrabold tracking-tight">{{ $tiposServicio->nombre }}</h2>
</div>
<a href="{{ route('tipos-servicio.index') }}" class="btn btn-secondary">← Volver</a>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
<div class="stat-card">
<div class="stat-icon bg-amber-50">📋</div>
<div>
<div class="stat-value">{{ $tiposServicio->cotizaciones_count }}</div>
<div class="stat-label">Cotizaciones</div>
</div>
</div>
<div class="stat-card">
<div class="stat-icon bg-blue-50">⚙️</div>
<div>
<div class="stat-value">{{ $tiposServicio->servicios_configurados_count }}</div>
<div class="stat-label">Servicios configurados</div>
</div>
</div>
</div>
<div class="card">
<div class="card-header"><h3>Datos del tipo de servicio</h3></div>
<div class="card-body">
<div class="grid grid-cols-2 gap-4 text-sm">
<div>
<span class="text-gray-500">Nombre</span>
<p class="font-semibold mt-0.5">{{ $tiposServicio->nombre }}</p>
</div>
<div>
<span class="text-gray-500">Descripción</span>
<p class="font-semibold mt-0.5">{{ $tiposServicio->descripcion ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Registrado</span>
<p class="font-semibold mt-0.5">{{ $tiposServicio->created_at->format('d/m/Y') }}</p>
</div>
</div>
</div>
</div>
<div class="flex items-center gap-3">
<a href="{{ route('tipos-servicio.edit', $tiposServicio) }}" class="btn btn-primary">Editar</a>
<form method="POST" action="{{ route('tipos-servicio.destroy', $tiposServicio) }}" data-confirm="¿Eliminar este tipo de servicio?">@csrf @method('DELETE')<button type="submit" class="btn btn-secondary">Eliminar</button></form>
</div>
</div>@endsection