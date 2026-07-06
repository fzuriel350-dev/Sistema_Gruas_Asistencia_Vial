@extends('layouts.app')@section('title', 'Servicios Configurados')@section('content')<div class="max-w-7xl mx-auto">    @if (session('success'))        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">{{ session('success') }}</div>    @endif    <div class="card">
<div class="card-header">
<h3>Servicios Configurados</h3>
@can('admin')<a href="{{ route('servicios-configurados.create') }}" class="btn btn-primary">+ Nuevo Servicio Configurado</a>@endcan
</div>
<div class="table-container">
<table>
<thead>
<tr>
<th>Nombre</th>
<th>Cliente</th>
<th>Tipo Servicio</th>
<th>Tipo</th>
<th>Banderazo</th>
<th>Km</th>
<th>Activo</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>                    @forelse ($configurados as $sc)                    <tr>
<td><strong>{{ $sc->nombre }}</strong></td>
<td>{{ $sc->cliente?->nombre ?? '—' }}</td>
<td>{{ $sc->tipoServicio?->nombre ?? '—' }}</td>
<td>{{ ucfirst($sc->tipo) }}</td>
<td>${{ number_format($sc->costo_banderazo, 2) }}</td>
<td>${{ number_format($sc->costo_km, 2) }}</td>
<td>
<span class="px-2.5 py-1 rounded-full text-xs font-semibold
@if($sc->activo) bg-emerald-100 text-emerald-800
@else bg-gray-100 text-gray-600 @endif">
{{ $sc->activo ? 'Sí' : 'No' }}
</span>
</td>
<td>
<div class="flex items-center gap-2">
<a href="{{ route('servicios-configurados.show', $sc) }}" class="btn btn-sm btn-secondary">Ver</a>
@can('admin')<a href="{{ route('servicios-configurados.edit', $sc) }}" class="btn btn-sm btn-primary">Editar</a>@endcan
@can('admin')<form method="POST" action="{{ route('servicios-configurados.destroy', $sc) }}" data-confirm="¿Eliminar este servicio configurado?">                                    @csrf @method('DELETE')                                    <button type="submit" class="btn btn-sm btn-secondary">Eliminar</button>
</form>@endcan
</div>
</td>
</tr>                    @empty                    <tr>
<td colspan="8" class="text-center text-gray-500 py-8">No hay servicios configurados.</td>
</tr>                    @endforelse                </tbody>
</table>
</div>
<div class="px-5 py-3 border-t border-gray-100">
{{ $configurados->links() }}
</div>
</div>
</div>@endsection
