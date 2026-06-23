@extends('layouts.app')@section('title', 'Clientes')@section('content')<div class="max-w-7xl mx-auto">    @if (session('success'))        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">            {{ session('success') }}        </div>    @endif    <div class="card">
<div class="card-header">
<h3>Catálogo de clientes</h3>
<div class="flex items-center gap-3">
<a href="{{ route('clientes.create') }}" class="btn btn-primary">+ Nuevo Cliente</a>
<form method="GET" class="flex items-center gap-3">
<input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar cliente..."                        class="px-3 py-2 rounded-lg text-sm border border-gray-200 bg-gray-50 focus:outline-none focus:border-[#FFD500] focus:bg-white transition-all w-48">
<button type="submit" class="btn btn-sm btn-secondary">Buscar</button>
</form>
</div>
</div>
<div class="table-container">
<table>
<thead>
                    <tr>
<th>Nombre</th>
<th>Empresa</th>
<th>Teléfono</th>
<th>Contacto</th>
<th>Dirección</th>
<th>Cotizaciones</th>
<th>Última actividad</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>                    @forelse ($clientes as $c)                    <tr>
<td>
<strong>{{ $c->nombre }}</strong>
</td>
<td>{{ $c->empresa ?? '—' }}</td>
<td>{{ $c->telefono ?? '—' }}</td>
<td>{{ $c->contacto ?? '—' }}</td>
<td class="max-w-[160px] truncate">{{ $c->direccion ?? '—' }}</td>
<td>{{ $c->servicios_count }}</td>
<td>{{ $c->ultimo_servicio ? $c->ultimo_servicio->format('d/m/Y') : '—' }}</td>
<td>
<div class="flex items-center gap-2">
<a href="{{ route('clientes.edit', $c) }}" class="btn btn-sm btn-primary">Editar</a>
<a href="{{ route('clientes.show', $c) }}" class="btn btn-sm btn-secondary">Historial</a>
<form method="POST" action="{{ route('clientes.destroy', $c) }}" data-confirm="¿Eliminar este cliente?">                                    @csrf @method('DELETE')                                    <button type="submit" class="btn btn-sm btn-secondary">Eliminar</button>
</form>
</div>
</td>
</tr>                    @empty                    <tr>
<td colspan="8" class="text-center text-gray-500 py-8">No hay clientes registrados.</td>
</tr>                    @endforelse                </tbody>
</table>
</div>
<div class="px-5 py-3 border-t border-gray-100">
{{ $clientes->appends(request()->query())->links() }}
</div>
</div>
</div>@endsection
