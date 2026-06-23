@extends('layouts.app')@section('title', 'Cotizaciones')@section('content')<div class="max-w-7xl mx-auto">    @if (session('success'))        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">            {{ session('success') }}        </div>    @endif    <div class="card">
<div class="card-header">
<h3>Todas las cotizaciones</h3>
<div class="flex items-center gap-3">
<form method="GET" class="flex items-center gap-3">
<input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar folio, cliente, origen, destino..." class="px-3 py-2 rounded-lg text-sm border border-gray-200 bg-gray-50 focus:outline-none focus:border-[#FFD500] focus:bg-white transition-all w-48">
<select name="aseguradora_id" class="px-3 py-2 rounded-lg text-sm border border-gray-200 bg-gray-50 focus:outline-none focus:border-[#FFD500] focus:bg-white transition-all">
<option value="">Todas las aseguradoras</option>                        @foreach ($aseguradoras as $a)                        <option value="{{ $a->id }}" @selected(request('aseguradora_id') == $a->id)>{{ $a->nombre }}</option>                        @endforeach                    </select>
<select name="estatus" class="px-3 py-2 rounded-lg text-sm border border-gray-200 bg-gray-50 focus:outline-none focus:border-[#FFD500] focus:bg-white transition-all">
<option value="">Todos los estados</option>
<option value="borrador" @selected(request('estatus') === 'borrador')>Borrador</option>
<option value="pendiente" @selected(request('estatus') === 'pendiente')>Pendiente</option>
<option value="aprobado" @selected(request('estatus') === 'aprobado')>Aprobado</option>
<option value="rechazado" @selected(request('estatus') === 'rechazado')>Rechazado</option>
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
<th>Origen</th>
<th>Destino</th>
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
<td>{{ $cot->cliente?->nombre }}</td>
<td>{{ $cot->aseguradora?->nombre }}</td>
<td class="max-w-[140px] truncate">{{ $cot->origen }}</td>
<td class="max-w-[140px] truncate">{{ $cot->destino }}</td>
<td>${{ number_format($cot->costo_total, 2) }}</td>
<td>{{ $cot->created_at->format('d/m/Y') }}</td>
<td>
<span class="status @switch($cot->estatus)                                @case('borrador') status-pending @break                                @case('pendiente') status-pending @break                                @case('aprobado') status-success @break                                @case('rechazado') status-danger @break                            @endswitch">
<span class="status-dot">
</span>                                {{ ucfirst($cot->estatus) }}                            </span>
</td>
<td>
<div class="flex items-center gap-2">
<a href="{{ route('cotizaciones.show', $cot) }}" class="btn btn-sm btn-secondary">Ver</a>                                @if (auth()->user()->isEmpleado() && in_array($cot->estatus, ['borrador', 'pendiente']))                                <a href="{{ route('cotizaciones.edit', $cot) }}" class="btn btn-sm btn-primary">Editar</a>                                @endif                            </div>
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
