@extends('layouts.app')@section('title', 'Editar Cliente')@section('content')<div class="max-w-2xl mx-auto">
<div class="card">
<div class="card-header">
<h3>Editar Cliente</h3>
<a href="{{ route('clientes.index') }}" class="btn btn-sm btn-ghost">Volver</a>
</div>
<div class="card-body">
<form method="POST" action="{{ route('clientes.update', $cliente) }}" class="form-grid">                @csrf @method('PUT')                <div class="form-group">
<label for="nombre">Nombre</label>
<input id="nombre" name="nombre" type="text" value="{{ old('nombre', $cliente->nombre) }}" required>
<x-input-error :messages="$errors->get('nombre')" />
</div>
<div class="form-group">
<label for="empresa">Empresa</label>
<input id="empresa" name="empresa" type="text" value="{{ old('empresa', $cliente->empresa) }}">
<x-input-error :messages="$errors->get('empresa')" />
</div>
<div class="form-group">
<label for="telefono">Teléfono</label>
<input id="telefono" name="telefono" type="text" value="{{ old('telefono', $cliente->telefono) }}">
<x-input-error :messages="$errors->get('telefono')" />
</div>
<div class="form-group">
<label for="contacto">Contacto</label>
<input id="contacto" name="contacto" type="text" value="{{ old('contacto', $cliente->contacto) }}">
<x-input-error :messages="$errors->get('contacto')" />
</div>
<div class="form-group">
<label for="direccion">Dirección</label>
<textarea id="direccion" name="direccion" rows="3">{{ old('direccion', $cliente->direccion) }}</textarea>
<x-input-error :messages="$errors->get('direccion')" />
</div>
<div class="flex items-center gap-3 pt-2">
<button type="submit" class="btn btn-primary">Actualizar</button>
<a href="{{ route('clientes.index') }}" class="btn btn-ghost">Cancelar</a>
</div>
</form>
</div>
</div>
</div>@endsection
