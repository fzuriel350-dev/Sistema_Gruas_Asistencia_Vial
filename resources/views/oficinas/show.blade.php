@extends('layouts.app')@section('title', $oficina->nombre)@section('content')<div class="max-w-3xl mx-auto">
<div class="flex items-center justify-between mb-6">
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-xl bg-[color:var(--geg-yellow)]/20 flex items-center justify-center text-xl font-bold text-[#0F0F0F]">{{ substr($oficina->nombre, 0, 1) }}</div>
<div>
<h2 class="text-2xl font-extrabold tracking-tight">{{ $oficina->nombre }}</h2>
<p class="text-sm text-gray-500">{{ $oficina->ciudad ?? '—' }}</p>
</div>
</div>
<a href="{{ route('oficinas.index') }}" class="btn btn-secondary">← Volver</a>
</div>
<div class="card">
<div class="card-header"><h3>Datos de la oficina</h3></div>
<div class="card-body">
<div class="grid grid-cols-2 gap-4 text-sm">
<div>
<span class="text-gray-500">ID</span>
<p class="font-semibold mt-0.5">{{ $oficina->id }}</p>
</div>
<div>
<span class="text-gray-500">Nombre</span>
<p class="font-semibold mt-0.5">{{ $oficina->nombre }}</p>
</div>
<div>
<span class="text-gray-500">Dirección</span>
<p class="font-semibold mt-0.5">{{ $oficina->direccion ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Ciudad</span>
<p class="font-semibold mt-0.5">{{ $oficina->ciudad ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Estado</span>
<p class="font-semibold mt-0.5">{{ $oficina->estado ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Teléfono</span>
<p class="font-semibold mt-0.5">{{ $oficina->telefono ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Encargado</span>
<p class="font-semibold mt-0.5">{{ $oficina->encargado ?? '—' }}</p>
</div>
<div>
<span class="text-gray-500">Creado</span>
<p class="font-semibold mt-0.5">{{ $oficina->created_at->format('d/m/Y H:i') }}</p>
</div>
<div>
<span class="text-gray-500">Actualizado</span>
<p class="font-semibold mt-0.5">{{ $oficina->updated_at->format('d/m/Y H:i') }}</p>
</div>
@if ($oficina->trashed())
<div>
<span class="text-gray-500">Eliminado</span>
<p class="font-semibold mt-0.5">{{ $oficina->deleted_at->format('d/m/Y H:i') }}</p>
</div>
@endif
</div>
</div>
</div>
@can('admin')<div class="flex items-center gap-3 mt-5">
<a href="{{ route('oficinas.edit', $oficina) }}" class="btn btn-primary">Editar</a>
<form method="POST" action="{{ route('oficinas.destroy', $oficina) }}" data-confirm="¿Eliminar esta oficina?">@csrf @method('DELETE')<button type="submit" class="btn btn-secondary">Eliminar</button></form>
</div>@endcan
</div>@endsection
