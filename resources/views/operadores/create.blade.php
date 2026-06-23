@extends('layouts.app')@section('title', 'Nuevo Operador')@section('content')<div class="max-w-2xl mx-auto">
<div class="card">
<div class="card-header">
<h3>Nuevo Operador</h3>
<a href="{{ route('operadores.index') }}" class="btn btn-sm btn-ghost">Volver</a>
</div>
<div class="card-body">
<form method="POST" action="{{ route('operadores.store') }}" class="form-grid">                @csrf                <div class="form-group">
<label for="empleado_id" >Empleado</label>
<select id="empleado_id" name="empleado_id" required>
<option value="">Seleccionar empleado...</option>                        @foreach ($empleados as $emp)                            <option value="{{ $emp->id }}" @selected(old('empleado_id') == $emp->id)>{{ $emp->nombreCompleto() }}</option>                        @endforeach                    </select>
<x-input-error :messages="$errors->get('empleado_id')" />
</div>
<div class="form-group">
<label for="licencia_tipo" >Tipo de Licencia</label>
<input id="licencia_tipo" name="licencia_tipo" type="text" value="{{ old('licencia_tipo') }}" required>
<x-input-error :messages="$errors->get('licencia_tipo')" />
</div>
<div class="form-group">
<label for="licencia_año_vencimiento" >Año de Vencimiento</label>
                    <input id="licencia_año_vencimiento" name="licencia_año_vencimiento" type="date" value="{{ old('licencia_año_vencimiento') }}" required>
<x-input-error :messages="$errors->get('licencia_año_vencimiento')" />
</div>
<div class="form-group">
<label class="flex items-center gap-2">
<input type="checkbox" name="disponible" value="1" @checked(old('disponible', true)) class="rounded border-gray-300">
<span class="text-sm font-medium text-gray-700">Disponible</span>
</label>
</div>
<div class="flex items-center gap-3 pt-2">
<button type="submit" class="btn btn-primary">Guardar</button>
<a href="{{ route('operadores.index') }}" class="btn btn-ghost">Cancelar</a>
</div>
</form>
</div>
</div>
</div>@endsection
