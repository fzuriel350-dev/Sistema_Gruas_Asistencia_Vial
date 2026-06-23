@extends('layouts.app')@section('title', 'Unidades')@section('content')<div class="max-w-7xl mx-auto">    @if (session('success'))        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">{{ session('success') }}</div>    @endif    <div class="card">
<div class="card-header">
<h3>Unidades</h3>
<a href="{{ route('unidades.create') }}" class="btn btn-primary">+ Nueva Unidad</a>
</div>
<div class="table-container">
<table>
<thead>
<tr>
<th>Marca</th>
<th>Tipo</th>
<th>Año</th>
<th>Placas</th>
<th>Operador</th>
<th>Seguro Vence</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>                    @forelse ($unidades as $u)                    <tr>
<td>
<strong>{{ $u->marca }}</strong>
</td>
<td>{{ $u->tipo }}</td>
<td>{{ $u->año }}</td>
<td>{{ $u->placas }}</td>
<td>{{ $u->operador?->empleado?->nombreCompleto() ?: 'Sin asignar' }}</td>
<td>{{ $u->seguro_vencimiento?->format('d/m/Y') ?: '—' }}</td>
<td>
<div class="flex items-center gap-2">
<a href="{{ route('unidades.show', $u) }}" class="btn btn-sm btn-ghost">Ver</a>
<a href="{{ route('unidades.edit', $u) }}" class="btn btn-sm btn-primary">Editar</a>
<form method="POST" action="{{ route('unidades.destroy', $u) }}" data-confirm="¿Eliminar esta unidad?">                                    @csrf @method('DELETE')                                    <button type="submit" class="btn btn-sm btn-secondary">Eliminar</button>
</form>
</div>
</td>
</tr>                    @empty                    <tr>
<td colspan="7" class="text-center text-gray-500 py-8">No hay unidades registradas.</td>
</tr>                    @endforelse                </tbody>
</table>
</div>
<div class="px-5 py-3 border-t border-gray-100">
{{ $unidades->links() }}
</div>
</div>
</div>@endsection
