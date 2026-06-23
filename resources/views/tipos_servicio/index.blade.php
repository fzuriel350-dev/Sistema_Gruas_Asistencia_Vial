@extends('layouts.app')@section('title', 'Tipos de Servicio')@section('content')<div class="max-w-7xl mx-auto">    @if (session('success'))        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">{{ session('success') }}</div>    @endif    <div class="card">
<div class="card-header">
<h3>Tipos de Servicio</h3>
<a href="{{ route('tipos-servicio.create') }}" class="btn btn-primary">+ Nuevo Tipo</a>
</div>
<div class="table-container">
<table>
<thead>
<tr>
<th>Nombre</th>
<th>Descripción</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>                    @forelse ($tipos as $t)                    <tr>
<td>
<strong>{{ $t->nombre }}</strong>
</td>
<td>{{ $t->descripcion ?: '—' }}</td>
<td>
<div class="flex items-center gap-2">
<a href="{{ route('tipos-servicio.show', $t) }}" class="btn btn-sm btn-ghost">Ver</a>
<a href="{{ route('tipos-servicio.edit', $t) }}" class="btn btn-sm btn-primary">Editar</a>
<form method="POST" action="{{ route('tipos-servicio.destroy', $t) }}" data-confirm="¿Eliminar este tipo de servicio?">                                    @csrf @method('DELETE')                                    <button type="submit" class="btn btn-sm btn-secondary">Eliminar</button>
</form>
</div>
</td>
</tr>                    @empty                    <tr>
<td colspan="3" class="text-center text-gray-500 py-8">No hay tipos de servicio registrados.</td>
</tr>                    @endforelse                </tbody>
</table>
</div>
<div class="px-5 py-3 border-t border-gray-100">
{{ $tipos->links() }}
</div>
</div>
</div>@endsection
