@extends('layouts.app')@section('title', 'Nuevo Convenio')@section('content')<div class="max-w-2xl mx-auto">
<div class="card">
<div class="card-header">
<h3>Nuevo Convenio</h3>
<a href="{{ route('convenios.index') }}" class="btn btn-sm btn-ghost">Volver</a>
</div>
<div class="card-body">
<form method="POST" action="{{ route('convenios.store') }}" class="form-grid">                @csrf
<div class="form-group">
<label for="nombre">Nombre</label>
<input id="nombre" name="nombre" type="text" value="{{ old('nombre') }}" required>
<x-input-error :messages="$errors->get('nombre')" />
</div>
<div class="form-group">
<label for="aseguradora_id">Aseguradora</label>
<select id="aseguradora_id" name="aseguradora_id" required>
<option value="">Seleccionar aseguradora...</option>                    @foreach ($aseguradoras as $a)                        <option value="{{ $a->id }}" @selected(old('aseguradora_id') == $a->id)>{{ $a->nombre }}</option>                    @endforeach                </select>
<x-input-error :messages="$errors->get('aseguradora_id')" />
</div>
<div class="form-group">
<label for="tipo">Tipo</label>
<select id="tipo" name="tipo" required>
<option value="local" @selected(old('tipo') === 'local')>Local</option>
<option value="foraneo" @selected(old('tipo') === 'foraneo')>Foráneo</option>
</select>
<x-input-error :messages="$errors->get('tipo')" />
</div>
<div class="grid grid-cols-2 gap-4">
<div class="form-group">
<label for="costo_banderazo">Costo Banderazo ($)</label>
<input id="costo_banderazo" name="costo_banderazo" type="number" step="0.01" min="0" value="{{ old('costo_banderazo') }}" required>
<x-input-error :messages="$errors->get('costo_banderazo')" />
</div>
<div class="form-group">
<label for="costo_km">Costo por km ($)</label>
<input id="costo_km" name="costo_km" type="number" step="0.01" min="0" value="{{ old('costo_km') }}" required>
<x-input-error :messages="$errors->get('costo_km')" />
</div>
<div class="form-group">
<label for="km_incluidos">Km incluidos</label>
<input id="km_incluidos" name="km_incluidos" type="number" step="0.01" min="0" value="{{ old('km_incluidos', 0) }}" required>
<x-input-error :messages="$errors->get('km_incluidos')" />
</div>
<div class="form-group">
<label for="descuento">Descuento (%)</label>
<input id="descuento" name="descuento" type="number" step="0.01" min="0" max="100" value="{{ old('descuento', 0) }}" required>
<x-input-error :messages="$errors->get('descuento')" />
</div>
</div>
<div class="form-group">
<label for="cobertura">Cobertura</label>
<input id="cobertura" name="cobertura" type="text" value="{{ old('cobertura', 'sin_cobertura') }}" required>
<x-input-error :messages="$errors->get('cobertura')" />
</div>
<div class="form-group">
<label for="cubre_casetas_peaje">Cubre casetas/peaje</label>
<select id="cubre_casetas_peaje" name="cubre_casetas_peaje">
<option value="0" @selected(!old('cubre_casetas_peaje'))>No</option>
<option value="1" @selected(old('cubre_casetas_peaje'))>Sí</option>
</select>
<x-input-error :messages="$errors->get('cubre_casetas_peaje')" />
</div>
<div class="flex items-center gap-3 pt-2">
<button type="submit" class="btn btn-primary">Crear Convenio</button>
<a href="{{ route('convenios.index') }}" class="btn btn-ghost">Cancelar</a>
</div>
</form>
</div>
</div>
</div>@endsection