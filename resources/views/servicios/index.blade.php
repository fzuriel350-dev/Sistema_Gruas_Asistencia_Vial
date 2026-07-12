@extends('layouts.app')@section('title', 'Servicios')@section('content')<div class="max-w-7xl mx-auto">    @if (session('success'))        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">{{ session('success') }}</div>    @endif    <div class="card">
<div class="card-header">
<h3>Servicios</h3>
<div class="flex items-center gap-3">
<form method="GET" class="flex items-center gap-3">
<input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por folio, cliente, operador..." class="px-3 py-2 rounded-lg text-sm border border-gray-200 bg-gray-50 focus:outline-none focus:border-[var(--geg-yellow)] focus:bg-white transition-all w-56">
                    <select name="estado" class="px-3 py-2 rounded-lg text-sm border border-gray-200 bg-gray-50 focus:outline-none focus:border-[var(--geg-yellow)] focus:bg-white transition-all">
<option value="">Todos los estados</option>
<option value="asignado" @selected(request('estado') === 'asignado')>Asignado</option>
<option value="inicio_servicio" @selected(request('estado') === 'inicio_servicio')>Inicio Servicio</option>
<option value="en_sitio_origen" @selected(request('estado') === 'en_sitio_origen')>En Sitio Origen</option>
<option value="en_carga" @selected(request('estado') === 'en_carga')>En Carga</option>
<option value="en_transito" @selected(request('estado') === 'en_transito')>En Tránsito</option>
<option value="en_sitio_destino" @selected(request('estado') === 'en_sitio_destino')>En Sitio Destino</option>
<option value="finalizado" @selected(request('estado') === 'finalizado')>Finalizado</option>
<option value="cancelado" @selected(request('estado') === 'cancelado')>Cancelado</option>
</select>
<button type="submit" class="btn btn-sm btn-secondary">Buscar</button>
</form>            @if (auth()->user()->isAdmin() || auth()->user()->isCotizador())            <a href="{{ route('servicios.create') }}" class="btn btn-primary">+ Nuevo Servicio</a>            @endif        </div>
</div>
<div class="table-container">
<table>
<thead>
<tr>
<th>ID</th>
<th>Cliente</th>
<th>Cotización</th>
<th>Operador</th>
<th>Unidad</th>
<th>Oficina</th>
<th>Tipo</th>
<th>Estado</th>
<th>Inicio</th>
<th>Fin</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>                    @forelse ($servicios as $s)                    <tr>
<td><strong>#{{ $s->id }}</strong></td>
<td>{{ $s->cotizacion?->cliente?->nombre ?: '—' }}</td>
<td>{{ $s->cotizacion?->folio ?: '—' }}</td>
<td>{{ $s->operador?->empleado?->nombreCompleto() ?: '—' }}</td>
<td>{{ $s->unidad?->marca }} {{ $s->unidad?->placas ? '('.$s->unidad->placas.')' : '' }}</td>
<td>{{ $s->oficina?->nombre ?: '—' }}</td>
<td>{{ $s->tipoServicio?->nombre ?: '—' }}</td>
<td>
<span class="status @switch($s->estado)                                @case('asignado') status-pending @break                                @case('inicio_servicio') status-active @break                                @case('en_sitio_origen') status-active @break                                @case('en_carga') status-active @break                                @case('en_transito') status-active @break                                @case('en_sitio_destino') status-active @break                                @case('finalizado') status-success @break                                @case('cancelado') status-danger @break                            @endswitch">
<span class="status-dot">
</span>                                {{ str_replace('_', ' ', ucfirst($s->estado)) }}                            </span>
</td>
<td>{{ $s->fecha_inicio?->format('d/m/Y H:i') ?: '—' }}</td>
<td>{{ $s->fecha_fin?->format('d/m/Y H:i') ?: '—' }}</td>
<td>
<div class="flex items-center gap-2">
<a href="{{ route('servicios.show', $s) }}" class="btn btn-sm btn-secondary">Ver</a>                                @if ((auth()->user()->isAdmin() || auth()->user()->isCotizador()) && !in_array($s->estado, ['finalizado', 'cancelado']))                                <a href="{{ route('servicios.edit', $s) }}" class="btn btn-sm btn-primary">Editar</a>                                @endif                            </div>
</td>
</tr>                    @empty                    <tr>
<td colspan="11" class="text-center text-gray-500 py-8">No hay servicios registrados.</td>
</tr>                    @endforelse                </tbody>
</table>
</div>
<div class="px-5 py-3 border-t border-gray-100">
{{ $servicios->appends(request()->query())->links() }}
</div>
</div>
</div>@endsection