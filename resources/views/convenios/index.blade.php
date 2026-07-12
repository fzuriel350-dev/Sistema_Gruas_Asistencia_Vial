@extends('layouts.app')@section('title', 'Convenios')@section('content')<div class="max-w-7xl mx-auto">    @if (session('success'))        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">{{ session('success') }}</div>    @endif    <div class="card">
<div class="card-header">
<h3>Convenios</h3>            @if (auth()->user()->isAdmin())            <a href="{{ route('convenios.create') }}" class="btn btn-primary">+ Nuevo Convenio</a>            @endif        </div>
<div class="table-container">
<table>
<thead>
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Aseguradora</th>
<th>Tipo Servicio</th>
<th>Alcance</th>
<th>Banderazo</th>
<th>Costo/km</th>
<th>Km incl.</th>
<th>Casetas</th>
<th>Descuento</th>
<th>Cobertura</th>
<th>Creado</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>                    @forelse ($convenios as $c)                    <tr>
<td><strong>#{{ $c->id }}</strong></td>
<td>{{ $c->nombre }}</td>
<td>{{ $c->aseguradora?->nombre ?: '—' }}</td>
<td>{{ $c->tipoServicio?->nombre ?: '—' }}</td>
<td>{{ ucfirst($c->tipo) }}</td>
<td>${{ number_format($c->costo_banderazo, 2) }}</td>
<td>${{ number_format($c->costo_km, 2) }}</td>
<td>{{ $c->km_incluidos }}</td>
<td><span class="{{ $c->cubre_casetas_peaje ? 'text-emerald-600' : 'text-red-500' }} font-semibold">{{ $c->cubre_casetas_peaje ? 'Sí' : 'No' }}</span></td>
<td>{{ $c->descuento }}%</td>
<td>{{ $c->cobertura }}</td>
<td>{{ $c->created_at->format('d/m/Y') }}</td>
<td>
<div class="flex items-center gap-2">
<a href="{{ route('convenios.show', $c) }}" class="btn btn-sm btn-secondary">Ver</a>                                @if (auth()->user()->isAdmin())                                <a href="{{ route('convenios.edit', $c) }}" class="btn btn-sm btn-primary">Editar</a>                                <form method="POST" action="{{ route('convenios.destroy', $c) }}" class="inline" data-confirm="¿Eliminar este convenio?">                                    @csrf @method('DELETE')                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>                                </form>                                @endif                            </div>
</td>
</tr>                    @empty                    <tr>
<td colspan="13" class="text-center text-gray-500 py-8">No hay convenios registrados.</td>
</tr>                    @endforelse                </tbody>
</table>
</div>
<div class="px-5 py-3 border-t border-gray-100">
{{ $convenios->appends(request()->query())->links() }}
</div>
</div>
</div>@endsection