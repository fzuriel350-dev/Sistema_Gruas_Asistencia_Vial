@extends('layouts.app')@section('title', 'Operadores')@section('content')<div class="max-w-7xl mx-auto">    @if (session('success'))        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">{{ session('success') }}</div>    @endif    <div class="card">
<div class="card-header">
<h3>Operadores</h3>
<a href="{{ route('operadores.create') }}" class="btn btn-primary">+ Nuevo Operador</a>
</div>
<div class="table-container">
<table>
<thead>
<tr>
<th>Nombre</th>
<th>Licencia</th>
<th>Vencimiento</th>
<th>Unidades</th>
<th>Disponible</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>                    @forelse ($operadores as $o)                    <tr>
<td>
<strong>{{ $o->empleado?->nombreCompleto() }}</strong>
</td>
<td>{{ $o->licencia_tipo }}</td>
<td>{{ $o->licencia_año_vencimiento?->format('d/m/Y') ?: '—' }}</td>
<td>{{ $o->unidades->count() }}</td>
<td>                            @if ($o->disponible)                                <span class="status status-success">
<span class="status-dot">
</span> Disponible</span>                            @else                                <span class="status status-pending">
<span class="status-dot">
</span> Ocupado</span>                            @endif                        </td>
<td>
<div class="flex items-center gap-2">
<a href="{{ route('operadores.show', $o) }}" class="btn btn-sm btn-ghost">Ver</a>
<a href="{{ route('operadores.edit', $o) }}" class="btn btn-sm btn-primary">Editar</a>
<form method="POST" action="{{ route('operadores.destroy', $o) }}" data-confirm="¿Eliminar este operador?">                                    @csrf @method('DELETE')                                    <button type="submit" class="btn btn-sm btn-secondary">Eliminar</button>
</form>
</div>
</td>
</tr>                    @empty                    <tr>
<td colspan="6" class="text-center text-gray-500 py-8">No hay operadores registrados.</td>
</tr>                    @endforelse                </tbody>
</table>
</div>
<div class="px-5 py-3 border-t border-gray-100">
{{ $operadores->links() }}
</div>
</div>
</div>@endsection
