@extends('layouts.app')@section('title', 'Editar Unidad')@section('content')<div class="max-w-2xl mx-auto">
<div class="card">
<div class="card-header">
<h3>Editar Unidad</h3>
<a href="{{ route('unidades.index') }}" class="btn btn-sm btn-ghost">Volver</a>
</div>
<div class="card-body">
<form method="POST" action="{{ route('unidades.update', $unidad) }}" class="form-grid">                @csrf @method('PUT')                <div class="form-group">
<label for="marca" >Marca</label>
<input id="marca" name="marca" type="text" value="{{ old('marca', $unidad->marca) }}" required>
<x-input-error :messages="$errors->get('marca')" />
</div>
<div class="form-group">
<label for="tipo" >Tipo</label>
<input id="tipo" name="tipo" type="text" value="{{ old('tipo', $unidad->tipo) }}" required>
<x-input-error :messages="$errors->get('tipo')" />
</div>
<div class="form-group">
<label for="ano" >Año</label>
<input id="ano" name="ano" type="number" value="{{ old('ano', $unidad->año) }}" required>
<x-input-error :messages="$errors->get('ano')" />
</div>
<div class="form-group">
<label for="placas" >Placas</label>
<input id="placas" name="placas" type="text" value="{{ old('placas', $unidad->placas) }}" required>
<x-input-error :messages="$errors->get('placas')" />
</div>
<div class="form-group">
<label for="numero_serie" >Número de Serie</label>
<input id="numero_serie" name="numero_serie" type="text" value="{{ old('numero_serie', $unidad->numero_serie) }}">
<x-input-error :messages="$errors->get('numero_serie')" />
</div>
<div class="form-group">
<label for="seguro_vencimiento" >Vencimiento del Seguro</label>
<input id="seguro_vencimiento" name="seguro_vencimiento" type="date" value="{{ old('seguro_vencimiento', $unidad->seguro_vencimiento?->format('Y-m-d')) }}">
<x-input-error :messages="$errors->get('seguro_vencimiento')" />
</div>
<div class="form-group">
<label for="operador_id" >Operador Asignado</label>
<select id="operador_id" name="operador_id" >
<option value="">Sin asignar</option>                        @foreach ($operadores as $op)                            <option value="{{ $op->id }}" @selected(old('operador_id', $unidad->operador_id) == $op->id)>{{ $op->empleado?->nombreCompleto() }}</option>                        @endforeach                    </select>
<x-input-error :messages="$errors->get('operador_id')" />
</div>
<div class="flex items-center gap-3 pt-2">
<button type="submit" class="btn btn-primary">Actualizar</button>
<a href="{{ route('unidades.index') }}" class="btn btn-ghost">Cancelar</a>
</div>
</form>
</div>
</div>
</div>@endsection
