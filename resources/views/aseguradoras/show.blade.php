@extends('layouts.app')@section('title', $aseguradora->nombre)@section('content')<div class="max-w-3xl mx-auto">
<div class="flex items-center justify-between mb-6">
<div>
<h2 class="text-2xl font-extrabold tracking-tight">{{ $aseguradora->nombre }}</h2>
</div>
<a href="{{ route('aseguradoras.index') }}" class="btn btn-secondary">← Volver</a>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
<div class="stat-card">
<div class="stat-icon bg-amber-50">📋</div>
<div>
<div class="stat-value">{{ $aseguradora->convenios_count }}</div>
<div class="stat-label">Convenios</div>
</div>
</div>
<div class="stat-card">
<div class="stat-icon bg-blue-50">🚛</div>
<div>
<div class="stat-value">{{ $aseguradora->cotizaciones_count }}</div>
<div class="stat-label">Cotizaciones</div>
</div>
</div>
<a href="{{ route('convenios.create', ['aseguradora_id' => $aseguradora->id]) }}" class="stat-card hover:border-[#FFD500] transition-colors cursor-pointer">
<div class="stat-icon bg-emerald-50">＋</div>
<div>
<div class="stat-value text-sm">Nuevo convenio</div>
<div class="stat-label">Agregar</div>
</div>
</a>
</div>
<div class="card">
<div class="card-header"><h3>Datos de la aseguradora</h3></div>
<div class="card-body">
<div class="grid grid-cols-2 gap-4 text-sm">
<div>
<span class="text-gray-500">Nombre</span>
<p class="font-semibold mt-0.5">{{ $aseguradora->nombre }}</p>
</div>
<div>
<span class="text-gray-500">Teléfono</span>
<p class="font-semibold mt-0.5">{{ $aseguradora->telefono ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Registrado</span>
<p class="font-semibold mt-0.5">{{ $aseguradora->created_at->format('d/m/Y') }}</p>
</div>
</div>
</div>
</div>
<div class="flex items-center gap-3">
<a href="{{ route('aseguradoras.edit', $aseguradora) }}" class="btn btn-primary">Editar</a>
<form method="POST" action="{{ route('aseguradoras.destroy', $aseguradora) }}" data-confirm="¿Eliminar esta aseguradora?">@csrf @method('DELETE')<button type="submit" class="btn btn-secondary">Eliminar</button></form>
</div>
</div>@endsection