@extends('layouts.app')@section('title', 'Operador: ' . ($operador->empleado?->nombreCompleto() ?? ''))@section('content')<div class="max-w-3xl mx-auto">
<div class="flex items-center justify-between mb-6">
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-xl bg-[#FFD500]/20 flex items-center justify-center text-xl font-bold text-[#0F0F0F]">{{ substr($operador->empleado?->nombre ?? 'O', 0, 1) }}</div>
<div>
<h2 class="text-2xl font-extrabold tracking-tight">{{ $operador->empleado?->nombreCompleto() }}</h2>
<p class="text-sm text-gray-500">Licencia {{ $operador->licencia_tipo }}</p>
</div>
</div>
<a href="{{ route('operadores.index') }}" class="btn btn-secondary">← Volver</a>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
<div class="stat-card">
<div class="stat-icon bg-amber-50">🪪</div>
<div>
<div class="stat-value">{{ $operador->licencia_tipo }}</div>
<div class="stat-label">Tipo de licencia</div>
</div>
</div>
<div class="stat-card">
<div class="stat-icon bg-blue-50">🚛</div>
<div>
<div class="stat-value">{{ $operador->unidades->count() }}</div>
<div class="stat-label">Unidades asignadas</div>
</div>
</div>
<div class="stat-card">
<div class="stat-icon {{ $operador->disponible ? 'bg-emerald-50' : 'bg-red-50' }}">✓</div>
<div>
<div class="stat-value">{{ $operador->disponible ? 'Disponible' : 'Ocupado' }}</div>
<div class="stat-label">Estado</div>
</div>
</div>
</div>
<div class="card">
<div class="card-header"><h3>Datos del operador</h3></div>
<div class="card-body">
<div class="grid grid-cols-2 gap-4 text-sm">
<div>
<span class="text-gray-500">Nombre completo</span>
<p class="font-semibold mt-0.5">{{ $operador->empleado?->nombreCompleto() ?: '—' }}</p>
</div>
<div>
<span class="text-gray-500">Tipo de licencia</span>
<p class="font-semibold mt-0.5">{{ $operador->licencia_tipo }}</p>
</div>
<div>
<span class="text-gray-500">Vencimiento de licencia</span>
<p class="font-semibold mt-0.5">{{ $operador->licencia_año_vencimiento?->format('d/m/Y') ?: '—' }}</p>
</div>
<div>
<span class="text-gray-500">Disponible</span>
<p class="font-semibold mt-0.5">{{ $operador->disponible ? 'Sí' : 'No' }}</p>
</div>
<div>
<span class="text-gray-500">Registrado</span>
<p class="font-semibold mt-0.5">{{ $operador->created_at->format('d/m/Y') }}</p>
</div>
</div>
</div>
</div>
@if ($operador->unidades->count())<div class="card mt-5">
<div class="card-header"><h3>Unidades asignadas</h3></div>
<div class="table-container">
<table>
<thead><tr><th>Marca</th><th>Tipo</th><th>Año</th><th>Placas</th><th>Acciones</th></tr></thead>
<tbody>@foreach ($operador->unidades as $u)<tr>
<td><strong>{{ $u->marca }}</strong></td>
<td>{{ $u->tipo }}</td>
<td>{{ $u->año }}</td>
<td>{{ $u->placas }}</td>
<td><a href="{{ route('unidades.show', $u) }}" class="btn btn-sm btn-secondary">Ver</a></td>
</tr>@endforeach</tbody>
</table>
</div>
</div>@endif
<div class="flex items-center gap-3 mt-5">
<a href="{{ route('operadores.edit', $operador) }}" class="btn btn-primary">Editar</a>
<form method="POST" action="{{ route('operadores.destroy', $operador) }}" data-confirm="¿Eliminar este operador?">@csrf @method('DELETE')<button type="submit" class="btn btn-secondary">Eliminar</button></form>
</div>
</div>@endsection