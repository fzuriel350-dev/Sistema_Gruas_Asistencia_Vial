@extends('layouts.app')@section('title', $unidad->marca . ' ' . $unidad->placas)@section('content')<div class="max-w-3xl mx-auto">
<div class="flex items-center justify-between mb-6">
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-xl bg-[#FFD500]/20 flex items-center justify-center text-xl font-bold text-[#0F0F0F]">{{ substr($unidad->marca, 0, 1) }}</div>
<div>
<h2 class="text-2xl font-extrabold tracking-tight">{{ $unidad->marca }} {{ $unidad->tipo }}</h2>
<p class="text-sm text-gray-500">{{ $unidad->placas }}</p>
</div>
</div>
<a href="{{ route('unidades.index') }}" class="btn btn-secondary">← Volver</a>
</div>
<div class="card">
<div class="card-header"><h3>Datos de la unidad</h3></div>
<div class="card-body">
<div class="grid grid-cols-2 gap-4 text-sm">
<div>
<span class="text-gray-500">Marca</span>
<p class="font-semibold mt-0.5">{{ $unidad->marca }}</p>
</div>
<div>
<span class="text-gray-500">Tipo</span>
<p class="font-semibold mt-0.5">{{ $unidad->tipo }}</p>
</div>
<div>
<span class="text-gray-500">Año</span>
<p class="font-semibold mt-0.5">{{ $unidad->año }}</p>
</div>
<div>
<span class="text-gray-500">Placas</span>
<p class="font-semibold mt-0.5">{{ $unidad->placas }}</p>
</div>
<div>
<span class="text-gray-500">Número de serie</span>
<p class="font-semibold mt-0.5">{{ $unidad->numero_serie ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Vencimiento de seguro</span>
<p class="font-semibold mt-0.5">{{ $unidad->seguro_vencimiento?->format('d/m/Y') ?: '—' }}</p>
</div>
<div>
<span class="text-gray-500">Operador asignado</span>
<p class="font-semibold mt-0.5">{{ $unidad->operador?->empleado?->nombreCompleto() ?: 'Sin asignar' }}</p>
</div>
<div>
<span class="text-gray-500">Registrado</span>
<p class="font-semibold mt-0.5">{{ $unidad->created_at->format('d/m/Y') }}</p>
</div>
</div>
</div>
</div>
<div class="flex items-center gap-3 mt-5">
<a href="{{ route('unidades.edit', $unidad) }}" class="btn btn-primary">Editar</a>
<form method="POST" action="{{ route('unidades.destroy', $unidad) }}" data-confirm="¿Eliminar esta unidad?">@csrf @method('DELETE')<button type="submit" class="btn btn-secondary">Eliminar</button></form>
</div>
</div>@endsection