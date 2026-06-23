@extends('layouts.app')@section('title', 'Aseguradoras')@section('content')<div class="max-w-7xl mx-auto">    @if (session('success'))        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">            {{ session('success') }}        </div>    @endif    <div class="card">
<div class="card-header">
<h3>Aseguradoras</h3>
<a href="{{ route('aseguradoras.create') }}" class="btn btn-primary">+ Nueva Aseguradora</a>
</div>
<div class="table-container">
<table>
<thead>
<tr>
<th>Nombre</th>
<th>Teléfono</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>                    @forelse ($aseguradoras as $a)                    <tr>
<td>
<strong>{{ $a->nombre }}</strong>
</td>
<td>{{ $a->telefono ?: '—' }}</td>
<td>
<div class="flex items-center gap-2">
<a href="{{ route('aseguradoras.show', $a) }}" class="btn btn-sm btn-ghost">Ver</a>
<a href="{{ route('aseguradoras.edit', $a) }}" class="btn btn-sm btn-primary">Editar</a>
<form method="POST" action="{{ route('aseguradoras.destroy', $a) }}" data-confirm="¿Eliminar esta aseguradora?">                                    @csrf @method('DELETE')                                    <button type="submit" class="btn btn-sm btn-secondary">Eliminar</button>
</form>
</div>
</td>
</tr>                    @empty                    <tr>
<td colspan="3" class="text-center text-gray-500 py-8">No hay aseguradoras registradas.</td>
</tr>                    @endforelse                </tbody>
</table>
</div>
<div class="px-5 py-3 border-t border-gray-100">
{{ $aseguradoras->links() }}
</div>
</div>
</div>@endsection
