@extends('layouts.app')@section('title', 'Nueva Aseguradora')@section('content')<div class="max-w-2xl mx-auto">
<div class="card">
<div class="card-header">
<h3>Nueva Aseguradora</h3>
<a href="{{ route('aseguradoras.index') }}" class="btn btn-sm btn-ghost">Volver</a>
</div>
<div class="card-body">
<form method="POST" action="{{ route('aseguradoras.store') }}" class="form-grid">                @csrf                <div class="form-group">
<label for="nombre" >Nombre</label>
<input id="nombre" name="nombre" type="text" value="{{ old('nombre') }}" required>
<x-input-error :messages="$errors->get('nombre')" />
</div>
<div class="form-group">
<label for="telefono" >Teléfono</label>
<input id="telefono" name="telefono" type="text" value="{{ old('telefono') }}">
<x-input-error :messages="$errors->get('telefono')" />
</div>
<div class="form-group">
<label>Tipos de Servicio que Cubre</label>
<div class="grid grid-cols-2 gap-2 mt-1">
@foreach ($tiposServicio as $ts)
<label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 hover:border-[var(--geg-yellow)] cursor-pointer transition-colors">
<input type="checkbox" name="tipos_servicio[]" value="{{ $ts->id }}" class="rounded border-gray-300 text-[var(--geg-yellow)] focus:ring-[var(--geg-yellow)]" @checked(in_array($ts->id, old('tipos_servicio', [])))>
<span class="text-sm font-medium">{{ $ts->nombre }}</span>
</label>
@endforeach
</div>
<x-input-error :messages="$errors->get('tipos_servicio')" />
</div>
<div class="flex items-center gap-3 pt-2">
<button type="submit" class="btn btn-primary">Guardar</button>
<a href="{{ route('aseguradoras.index') }}" class="btn btn-ghost">Cancelar</a>
</div>
</form>
</div>
</div>
</div>@endsection
