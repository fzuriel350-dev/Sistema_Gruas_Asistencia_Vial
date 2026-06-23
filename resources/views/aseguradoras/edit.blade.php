@extends('layouts.app')@section('title', 'Editar Aseguradora')@section('content')<div class="max-w-2xl mx-auto">
<div class="card">
<div class="card-header">
<h3>Editar Aseguradora</h3>
<a href="{{ route('aseguradoras.index') }}" class="btn btn-sm btn-ghost">Volver</a>
</div>
<div class="card-body">
<form method="POST" action="{{ route('aseguradoras.update', $aseguradora) }}" class="form-grid">                @csrf @method('PUT')                <div class="form-group">
<label for="nombre" >Nombre</label>
<input id="nombre" name="nombre" type="text" value="{{ old('nombre', $aseguradora->nombre) }}" required>
<x-input-error :messages="$errors->get('nombre')" />
</div>
<div class="form-group">
<label for="telefono" >Teléfono</label>
<input id="telefono" name="telefono" type="text" value="{{ old('telefono', $aseguradora->telefono) }}">
<x-input-error :messages="$errors->get('telefono')" />
</div>
<div class="flex items-center gap-3 pt-2">
<button type="submit" class="btn btn-primary">Actualizar</button>
<a href="{{ route('aseguradoras.index') }}" class="btn btn-ghost">Cancelar</a>
</div>
</form>
</div>
</div>
</div>@endsection
