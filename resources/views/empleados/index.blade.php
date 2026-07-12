@extends('layouts.app')@section('title', 'Empleados')@section('content')<div class="max-w-7xl mx-auto">    @if (session('success'))        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">{{ session('success') }}</div>    @endif    <div class="card">
<div class="card-header">
<h3>Empleados</h3>
<a href="{{ route('empleados.create') }}" class="btn btn-primary">+ Nuevo Empleado</a>
</div>
<div class="table-container">
<table>
<thead>
<tr>
<th>#</th>
<th>Nombre</th>
<th>Teléfono</th>
<th>Email</th>
<th>Oficina</th>
<th>Puesto</th>
<th>Dirección</th>
<th>Rol</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>                    @forelse ($empleados as $e)                    <tr>
<td class="text-gray-400 text-xs">{{ $e->id }}</td>
<td>
<strong>{{ $e->nombreCompleto() }}</strong>
</td>
<td>{{ $e->telefono ?: '—' }}</td>
<td>{{ $e->usuario?->email ?: '—' }}</td>
<td>{{ $e->oficina?->nombre ?: '—' }}</td>
<td>{{ $e->puesto ?: '—' }}</td>
<td class="max-w-[200px] truncate text-sm">{{ $e->direccion ?: '—' }}</td>
<td>
<span class="status status-pending">
<span class="status-dot">
</span> {{ $e->usuario?->role ?: '—' }}</span>
</td>
<td>
<div class="flex items-center gap-2">
<a href="{{ route('empleados.show', $e) }}" class="btn btn-sm btn-ghost">Ver</a>
<a href="{{ route('empleados.edit', $e) }}" class="btn btn-sm btn-primary">Editar</a>
<form method="POST" action="{{ route('empleados.destroy', $e) }}" data-confirm="¿Eliminar este empleado? También se eliminará su usuario.">                                    @csrf @method('DELETE')                                    <button type="submit" class="btn btn-sm btn-secondary">Eliminar</button>
</form>
</div>
</td>
</tr>                    @empty                    <tr>
<td colspan="9" class="text-center text-gray-500 py-8">No hay empleados registrados.</td>
</tr>                    @endforelse                </tbody>
</table>
</div>
<div class="px-5 py-3 border-t border-gray-100">
{{ $empleados->links() }}
</div>
</div>
</div>@endsection
