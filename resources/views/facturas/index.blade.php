@extends('layouts.app')@section('title', 'Facturas')@section('content')<div class="max-w-7xl mx-auto">    @if (session('success'))        <div class="mb-5 px-5 py-3.5 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">{{ session('success') }}</div>    @endif    <div class="card">
<div class="card-header">
<h3>Facturas</h3>
</div>
<div class="table-container">
<table>
<thead>
<tr>
<th>Folio</th>
<th>Cliente</th>
<th>Servicio</th>
<th>Subtotal</th>
<th>IVA</th>
<th>Total</th>
<th>Estatus</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>                    @forelse ($facturas as $f)                    <tr>
<td><strong>{{ $f->folio_factura }}</strong></td>
<td>{{ $f->cliente?->nombre ?? '—' }}</td>
<td>#{{ $f->servicio?->id }}</td>
<td>${{ number_format($f->subtotal, 2) }}</td>
<td>${{ number_format($f->iva, 2) }}</td>
<td><strong>${{ number_format($f->total, 2) }}</strong></td>
<td>
<span class="px-2.5 py-1 rounded-full text-xs font-semibold
@if($f->estatus === 'vigente') bg-emerald-100 text-emerald-800
@else bg-red-100 text-red-800 @endif">
{{ ucfirst($f->estatus) }}
</span>
</td>
<td>
<div class="flex items-center gap-2">
<a href="{{ route('facturas.show', $f) }}" class="btn btn-sm btn-ghost">Ver</a>
@can('admin')<form method="POST" action="{{ route('facturas.destroy', $f) }}" data-confirm="¿Eliminar esta factura?">                                    @csrf @method('DELETE')                                    <button type="submit" class="btn btn-sm btn-secondary">Eliminar</button></form>@endcan
</div>
</td>
</tr>                    @empty                    <tr>
<td colspan="8" class="text-center text-gray-500 py-8">No hay facturas registradas.</td>
</tr>                    @endforelse                </tbody>
</table>
</div>
<div class="px-5 py-3 border-t border-gray-100">
{{ $facturas->links() }}
</div>
</div>
</div>@endsection
