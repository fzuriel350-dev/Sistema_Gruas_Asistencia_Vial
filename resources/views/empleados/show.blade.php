@extends('layouts.app')@section('title', $empleado->nombreCompleto())@section('content')<div class="max-w-3xl mx-auto">
<div class="flex items-center justify-between mb-6">
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-xl bg-[#FFD500]/20 flex items-center justify-center text-xl font-bold text-[#0F0F0F]">{{ substr($empleado->nombre, 0, 1) }}</div>
<div>
<h2 class="text-2xl font-extrabold tracking-tight">{{ $empleado->nombreCompleto() }}</h2>
<p class="text-sm text-gray-500">{{ $empleado->usuario?->email ?: '' }}</p>
</div>
</div>
<a href="{{ route('empleados.index') }}" class="btn btn-secondary">← Volver</a>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
<div class="stat-card">
<div class="stat-icon bg-amber-50">👤</div>
<div>
<div class="stat-value">{{ $empleado->usuario?->role ?: '—' }}</div>
<div class="stat-label">Rol</div>
</div>
</div>
<div class="stat-card">
<div class="stat-icon bg-blue-50">🚛</div>
<div>
<div class="stat-value">{{ $empleado->operador ? 'Sí' : 'No' }}</div>
<div class="stat-label">Es operador</div>
</div>
</div>
<div class="stat-card">
<div class="stat-icon bg-emerald-50">📞</div>
<div>
<div class="stat-value text-sm">{{ $empleado->telefono ?: '—' }}</div>
<div class="stat-label">Teléfono</div>
</div>
</div>
</div>
<div class="card">
<div class="card-header"><h3>Datos del empleado</h3></div>
<div class="card-body">
<div class="grid grid-cols-2 gap-4 text-sm">
<div>
<span class="text-gray-500">Nombre</span>
<p class="font-semibold mt-0.5">{{ $empleado->nombre }}</p>
</div>
<div>
<span class="text-gray-500">Apellido paterno</span>
<p class="font-semibold mt-0.5">{{ $empleado->apellido_paterno }}</p>
</div>
<div>
<span class="text-gray-500">Apellido materno</span>
<p class="font-semibold mt-0.5">{{ $empleado->apellido_materno ?: '—' }}</p>
</div>
<div>
<span class="text-gray-500">Teléfono</span>
<p class="font-semibold mt-0.5">{{ $empleado->telefono ?: '—' }}</p>
</div>
<div>
<span class="text-gray-500">Dirección</span>
<p class="font-semibold mt-0.5">{{ $empleado->direccion ?: '—' }}</p>
</div>
<div>
<span class="text-gray-500">Email</span>
<p class="font-semibold mt-0.5">{{ $empleado->usuario?->email ?: '—' }}</p>
</div>
<div>
<span class="text-gray-500">Rol</span>
<p class="font-semibold mt-0.5">{{ $empleado->usuario?->role ?: '—' }}</p>
</div>
<div>
<span class="text-gray-500">Registrado</span>
<p class="font-semibold mt-0.5">{{ $empleado->created_at->format('d/m/Y') }}</p>
</div>
</div>
</div>
</div>
<div class="flex items-center gap-3">
<a href="{{ route('empleados.edit', $empleado) }}" class="btn btn-primary">Editar</a>
<form method="POST" action="{{ route('empleados.destroy', $empleado) }}" data-confirm="¿Eliminar este empleado? También se eliminará su usuario.">@csrf @method('DELETE')<button type="submit" class="btn btn-secondary">Eliminar</button></form>
</div>
</div>@endsection