@extends('layouts.app')@section('title', 'Factura ' . $factura->folio_factura)@section('content')<div class="max-w-3xl mx-auto">
<div class="flex items-center justify-between mb-6">
<div>
<h2 class="text-2xl font-extrabold tracking-tight">Factura {{ $factura->folio_factura }}</h2>
<p class="text-sm text-gray-500">
<span class="px-2.5 py-1 rounded-full text-xs font-semibold
@if($factura->estatus === 'vigente') bg-emerald-100 text-emerald-800
@else bg-red-100 text-red-800 @endif">
{{ ucfirst($factura->estatus) }}
</span>
</p>
</div>
<a href="{{ route('facturas.index') }}" class="btn btn-secondary">← Volver</a>
</div>
<div class="card">
<div class="card-header"><h3>Datos fiscales</h3></div>
<div class="card-body">
<div class="grid grid-cols-2 gap-4 text-sm">
<div>
<span class="text-gray-500">Folio</span>
<p class="font-semibold mt-0.5">{{ $factura->folio_factura }}</p>
</div>
<div>
<span class="text-gray-500">UUID Fiscal</span>
<p class="font-semibold mt-0.5">{{ $factura->uuid_fiscal ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Cliente</span>
<p class="font-semibold mt-0.5">{{ $factura->cliente?->nombre ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Servicio</span>
<p class="font-semibold mt-0.5">#{{ $factura->servicio?->id }} — {{ $factura->servicio?->cotizacion?->folio ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Tipo de servicio</span>
<p class="font-semibold mt-0.5">{{ $factura->servicio?->tipoServicio?->nombre ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Subtotal</span>
<p class="font-semibold mt-0.5">${{ number_format($factura->subtotal, 2) }}</p>
</div>
<div>
<span class="text-gray-500">IVA</span>
<p class="font-semibold mt-0.5">${{ number_format($factura->iva, 2) }}</p>
</div>
<div>
<span class="text-gray-500">Total</span>
<p class="font-semibold mt-0.5 text-lg">${{ number_format($factura->total, 2) }}</p>
</div>
<div>
<span class="text-gray-500">Fecha de emisión</span>
<p class="font-semibold mt-0.5">{{ $factura->created_at->format('d/m/Y H:i') }}</p>
</div>
</div>
<div class="mt-5 flex items-center gap-3 pt-4 border-t border-gray-100">
<a href="{{ route('facturas.descargar-pdf', $factura) }}" class="btn btn-primary" style="background:#2563eb;" target="_blank">Descargar PDF</a>
</div>
</div>
</div>
@can('admin')<div class="flex items-center gap-3 mt-5">
<form method="POST" action="{{ route('facturas.destroy', $factura) }}" data-confirm="¿Eliminar esta factura?">@csrf @method('DELETE')<button type="submit" class="btn btn-secondary">Eliminar</button></form>
</div>@endcan
</div>@endsection
