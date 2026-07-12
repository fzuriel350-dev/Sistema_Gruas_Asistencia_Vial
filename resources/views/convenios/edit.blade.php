@extends('layouts.app')@section('title', 'Editar Convenio')@section('content')<div class="max-w-2xl mx-auto">
<div class="card">
<div class="card-header">
<h3>Editar Convenio #{{ $convenio->id }}</h3>
<a href="{{ route('convenios.index') }}" class="btn btn-sm btn-ghost">Volver</a>
</div>
<div class="card-body">
<form method="POST" action="{{ route('convenios.update', $convenio) }}" class="form-grid">                @csrf @method('PUT')
<div class="form-group">
<label for="nombre">Nombre</label>
<input id="nombre" name="nombre" type="text" value="{{ old('nombre', $convenio->nombre) }}" required>
<x-input-error :messages="$errors->get('nombre')" />
</div>
<div class="form-group">
<label for="aseguradora_id">Aseguradora</label>
<select id="aseguradora_id" name="aseguradora_id" required x-data x-on:change="await $dispatch('cargar-tipos', {aseguradoraId: $event.target.value})">
<option value="">Seleccionar aseguradora...</option>                    @foreach ($aseguradoras as $a)                        <option value="{{ $a->id }}" @selected(old('aseguradora_id', $convenio->aseguradora_id) == $a->id)>{{ $a->nombre }}</option>                    @endforeach                </select>
<x-input-error :messages="$errors->get('aseguradora_id')" />
</div>
<div class="form-group" x-data="{tipos: @js($tiposServicio), cargando: false}" @cargar-tipos.window="cargando = true; fetch('/convenios/aseguradora/' + $event.detail.aseguradoraId + '/tipos').then(r => r.json()).then(data => { tipos = data; cargando = false; })">
<label for="tipo_servicio_id">Tipo de Servicio</label>
<select id="tipo_servicio_id" name="tipo_servicio_id" required :disabled="cargando">
<option value="">Seleccionar tipo...</option>
<template x-for="t in tipos" :key="t.id">
<option :value="t.id" x-text="t.nombre" :selected="t.id == '{{ old('tipo_servicio_id', $convenio->tipo_servicio_id) }}'"></option>
</template>
</select>
<x-input-error :messages="$errors->get('tipo_servicio_id')" />
</div>
<div class="form-group">
<label for="tipo">Alcance Geográfico</label>
<select id="tipo" name="tipo" required>
<option value="local" @selected(old('tipo', $convenio->tipo) === 'local')>Local</option>
<option value="foraneo" @selected(old('tipo', $convenio->tipo) === 'foraneo')>Foráneo</option>
</select>
<x-input-error :messages="$errors->get('tipo')" />
</div>
<div class="grid grid-cols-2 gap-4">
<div class="form-group">
<label for="costo_banderazo">Costo Banderazo ($)</label>
<input id="costo_banderazo" name="costo_banderazo" type="number" step="0.01" min="0" value="{{ old('costo_banderazo', $convenio->costo_banderazo) }}" required>
<x-input-error :messages="$errors->get('costo_banderazo')" />
</div>
<div class="form-group">
<label for="costo_km">Costo por km ($)</label>
<input id="costo_km" name="costo_km" type="number" step="0.01" min="0" value="{{ old('costo_km', $convenio->costo_km) }}" required>
<x-input-error :messages="$errors->get('costo_km')" />
</div>
<div class="form-group">
<label for="km_incluidos">Km incluidos</label>
<input id="km_incluidos" name="km_incluidos" type="number" step="0.01" min="0" value="{{ old('km_incluidos', $convenio->km_incluidos) }}" required>
<x-input-error :messages="$errors->get('km_incluidos')" />
</div>
<div class="form-group">
<label for="descuento">Descuento (%)</label>
<input id="descuento" name="descuento" type="number" step="0.01" min="0" max="100" value="{{ old('descuento', $convenio->descuento) }}" required>
<x-input-error :messages="$errors->get('descuento')" />
</div>
</div>
<div class="form-group">
<label for="cobertura">Cobertura</label>
<input id="cobertura" name="cobertura" type="text" value="{{ old('cobertura', $convenio->cobertura) }}" required>
<x-input-error :messages="$errors->get('cobertura')" />
</div>
<div class="form-group">
<label for="cubre_casetas_peaje">Cubre casetas/peaje</label>
<select id="cubre_casetas_peaje" name="cubre_casetas_peaje">
<option value="0" @selected(old('cubre_casetas_peaje', $convenio->cubre_casetas_peaje) == 0)>No</option>
<option value="1" @selected(old('cubre_casetas_peaje', $convenio->cubre_casetas_peaje) == 1)>Sí</option>
</select>
<x-input-error :messages="$errors->get('cubre_casetas_peaje')" />
</div>
<div class="flex items-center gap-3 pt-2">
<button type="submit" class="btn btn-primary">Actualizar</button>
<a href="{{ route('convenios.index') }}" class="btn btn-ghost">Cancelar</a>
</div>
</form>
</div>
</div>
</div>@endsection