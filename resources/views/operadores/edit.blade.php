@extends('layouts.app')@section('title', 'Editar Operador')@section('content')<div class="max-w-2xl mx-auto">
<div class="card">
<div class="card-header">
<h3>Editar Operador</h3>
<a href="{{ route('operadores.index') }}" class="btn btn-sm btn-ghost">Volver</a>
</div>
<div class="card-body">
<form method="POST" action="{{ route('operadores.update', $operador) }}" class="form-grid">                @csrf @method('PUT')                <div class="form-group">
<label for="empleado_id" >Empleado</label>
<select id="empleado_id" name="empleado_id" required>                        @foreach ($empleados as $emp)                            <option value="{{ $emp->id }}" @selected(old('empleado_id', $operador->empleado_id) == $emp->id)>{{ $emp->nombreCompleto() }}</option>                        @endforeach                    </select>
<x-input-error :messages="$errors->get('empleado_id')" />
</div>
<div class="form-group">
<label for="licencia_tipo" >Tipo de Licencia</label>
<input id="licencia_tipo" name="licencia_tipo" type="text" value="{{ old('licencia_tipo', $operador->licencia_tipo) }}" required>
<x-input-error :messages="$errors->get('licencia_tipo')" />
</div>
<div class="form-group">
<label for="licencia_año_vencimiento" >Año de Vencimiento</label>
                    <input id="licencia_año_vencimiento" name="licencia_año_vencimiento" type="date" value="{{ old('licencia_año_vencimiento', $operador->licencia_año_vencimiento?->format('Y-m-d')) }}" required>
<x-input-error :messages="$errors->get('licencia_año_vencimiento')" />
</div>
<div class="form-group">
<label class="flex items-center gap-2">
<input type="checkbox" name="disponible" value="1" @checked(old('disponible', $operador->disponible)) class="rounded border-gray-300">
<span class="text-sm font-medium text-gray-700">Disponible</span>
</label>
</div>
<div class="flex items-center gap-3 pt-2">
<button type="submit" class="btn btn-primary">Actualizar</button>
<a href="{{ route('operadores.index') }}" class="btn btn-ghost">Cancelar</a>
</div>
</form>
</div>
</div>
</div>@endsection
