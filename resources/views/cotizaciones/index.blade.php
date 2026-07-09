@extends('layouts.app')@section('title', 'Cotizaciones')@section('content')<div class="max-w-7xl mx-auto">    @if (session('success'))        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">            {{ session('success') }}        </div>    @endif    <div class="card">
<div class="card-header">
<h3>Todas las cotizaciones</h3>
<div class="flex items-center gap-3">
<form method="GET" class="flex items-center gap-3">
<input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar folio, cliente..." class="px-3 py-2 rounded-lg text-sm border border-gray-200 bg-gray-50 focus:outline-none focus:border-[#FFD500] focus:bg-white transition-all w-44">
<select name="aseguradora_id" class="px-3 py-2 rounded-lg text-sm border border-gray-200 bg-gray-50 focus:outline-none focus:border-[#FFD500] focus:bg-white transition-all">
<option value="">Todas las aseguradoras</option>                        @foreach ($aseguradoras as $a)                        <option value="{{ $a->id }}" @selected(request('aseguradora_id') == $a->id)>{{ $a->nombre }}</option>                        @endforeach                    </select>
<select name="tipo_servicio_id" class="px-3 py-2 rounded-lg text-sm border border-gray-200 bg-gray-50 focus:outline-none focus:border-[#FFD500] focus:bg-white transition-all">
<option value="">Todos los servicios</option>                        @foreach ($tiposServicio as $ts)                        <option value="{{ $ts->id }}" @selected(request('tipo_servicio_id') == $ts->id)>{{ $ts->nombre }}</option>                        @endforeach                    </select>
<select name="estatus" class="px-3 py-2 rounded-lg text-sm border border-gray-200 bg-gray-50 focus:outline-none focus:border-[#FFD500] focus:bg-white transition-all">
<option value="">Todos los estados</option>
<option value="pendiente" @selected(request('estatus') === 'pendiente')>Pendiente</option>
<option value="aprobado" @selected(request('estatus') === 'aprobado')>Aprobado</option>
<option value="rechazado" @selected(request('estatus') === 'rechazado')>Rechazado</option>
</select>
<select name="orden" class="px-3 py-2 rounded-lg text-sm border border-gray-200 bg-gray-50 focus:outline-none focus:border-[#FFD500] focus:bg-white transition-all">
<option value="creacion_desc" @selected(request('orden', 'creacion_desc') === 'creacion_desc')>Más recientes</option>
<option value="creacion_asc" @selected(request('orden') === 'creacion_asc')>Más antiguos</option>
<option value="cliente_asc" @selected(request('orden') === 'cliente_asc')>Cliente A-Z</option>
<option value="cliente_desc" @selected(request('orden') === 'cliente_desc')>Cliente Z-A</option>
<option value="total_asc" @selected(request('orden') === 'total_asc')>Total menor</option>
<option value="total_desc" @selected(request('orden') === 'total_desc')>Total mayor</option>
</select>
<button type="submit" class="btn btn-secondary btn-sm">Filtrar</button>
</form>                @if (auth()->user()->isEmpleado())                <a href="{{ route('cotizaciones.create') }}" class="btn btn-primary">+ Nueva Cotización</a>                @endif            </div>
</div>
<div class="table-container">
<table>
<thead>
<tr>
<th>Folio</th>
<th>Cliente</th>
<th>Aseguradora</th>
<th>Tipo Servicio</th>
<th>Distancia</th>
<th>Total</th>
<th>Fecha</th>
<th>Estado</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>                    @forelse ($cotizaciones as $cot)                    <tr>
<td>
<strong>{{ $cot->folio }}</strong>
</td>
<td>{{ $cot->cliente?->nombre ?? '—' }}</td>
<td>{{ $cot->aseguradora?->nombre }}</td>
<td>{{ $cot->tipoServicio?->nombre }}</td>
<td>{{ number_format($cot->distancia_km, 1) }} km</td>
<td>${{ number_format($cot->costo_total, 2) }}</td>
<td>{{ $cot->created_at->format('d/m/Y') }}</td>
<td>
<span class="status @switch($cot->estatus)                                @case('pendiente') status-pending @break                                @case('aprobado') status-success @break                                @case('rechazado') status-danger @break                            @endswitch">
<span class="status-dot">
</span>                                {{ ucfirst($cot->estatus) }}                            </span>
</td>
<td>
<div class="flex items-center gap-2">
<a href="{{ route('cotizaciones.show', $cot) }}" class="btn btn-sm btn-secondary">Ver</a>                                @if (auth()->user()->isEmpleado() && $cot->estatus === 'pendiente')                                <a href="{{ route('cotizaciones.edit', $cot) }}" class="btn btn-sm btn-primary">Editar</a>                                @endif                            </div>
</td>
</tr>                    @empty                    <tr>
<td colspan="9" class="text-center text-gray-500 py-8">No hay cotizaciones registradas.</td>
</tr>                    @endforelse                </tbody>
</table>
</div>
<div class="px-5 py-3 border-t border-gray-100">
{{ $cotizaciones->appends(request()->query())->links() }}
</div>
</div>
</div>@endsection