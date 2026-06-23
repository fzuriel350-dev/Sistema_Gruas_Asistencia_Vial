@extends('layouts.app')@section('title', 'Nuevo Tipo de Servicio')@section('content')<div class="max-w-2xl mx-auto">
<div class="card">
<div class="card-header">
<h3>Nuevo Tipo de Servicio</h3>
<a href="{{ route('tipos-servicio.index') }}" class="btn btn-sm btn-ghost">Volver</a>
</div>
<div class="card-body">
<form method="POST" action="{{ route('tipos-servicio.store') }}" class="form-grid">                @csrf                <div class="form-group">
<label for="nombre" >Nombre</label>
<input id="nombre" name="nombre" type="text" value="{{ old('nombre') }}" required>
<x-input-error :messages="$errors->get('nombre')" />
</div>
<div class="form-group">
<label for="descripcion" >Descripción</label>
<textarea id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
<x-input-error :messages="$errors->get('descripcion')" />
</div>
<div class="flex items-center gap-3 pt-2">
<button type="submit" class="btn btn-primary">Guardar</button>
<a href="{{ route('tipos-servicio.index') }}" class="btn btn-ghost">Cancelar</a>
</div>
</form>
</div>
</div>
</div>@endsection
