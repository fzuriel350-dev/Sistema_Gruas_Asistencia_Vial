@extends('layouts.app')@section('title', 'Convenio #'.$convenio->id)@section('content')<div class="max-w-3xl mx-auto">
<div class="card">
<div class="card-header">
<h3>Convenio #{{ $convenio->id }}</h3>
<div class="flex items-center gap-2">
<a href="{{ route('convenios.index') }}" class="btn btn-sm btn-ghost">Volver</a>            @if (auth()->user()->isAdmin())            <a href="{{ route('convenios.edit', $convenio) }}" class="btn btn-sm btn-primary">Editar</a>            @endif        </div>
</div>
<div class="card-body">
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div>
<p class="text-xs text-gray-500 uppercase font-semibold">Nombre</p>
<p class="font-medium">{{ $convenio->nombre }}</p>
</div>
<div>
<p class="text-xs text-gray-500 uppercase font-semibold">Cliente</p>
<p class="font-medium">{{ $convenio->cliente?->nombre ?: '—' }}</p>
</div>
<div>
<p class="text-xs text-gray-500 uppercase font-semibold">Aseguradora</p>
<p class="font-medium">{{ $convenio->aseguradora?->nombre ?: '—' }}</p>
</div>
<div>
<p class="text-xs text-gray-500 uppercase font-semibold">Tipo</p>
<p class="font-medium">{{ ucfirst($convenio->tipo) }}</p>
</div>
<div>
<p class="text-xs text-gray-500 uppercase font-semibold">Costo Banderazo</p>
<p class="font-medium">${{ number_format($convenio->costo_banderazo, 2) }}</p>
</div>
<div>
<p class="text-xs text-gray-500 uppercase font-semibold">Costo por km</p>
<p class="font-medium">${{ number_format($convenio->costo_km, 2) }}</p>
</div>
<div>
<p class="text-xs text-gray-500 uppercase font-semibold">Km incluidos</p>
<p class="font-medium">{{ $convenio->km_incluidos }}</p>
</div>
<div>
<p class="text-xs text-gray-500 uppercase font-semibold">Descuento</p>
<p class="font-medium">{{ $convenio->descuento }}%</p>
</div>
<div>
<p class="text-xs text-gray-500 uppercase font-semibold">Cobertura</p>
<p class="font-medium">{{ $convenio->cobertura }}</p>
</div>
</div>
</div>
</div>
</div>@endsection