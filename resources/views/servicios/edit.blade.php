@extends('layouts.app')@section('title', 'Editar Servicio')@section('content')<div class="max-w-2xl mx-auto">
<div class="card">
<div class="card-header">
<h3>Editar Servicio #{{ $servicio->id }}</h3>
<a href="{{ route('servicios.index') }}" class="btn btn-sm btn-ghost">Volver</a>
</div>
<div class="card-body">
<form method="POST" action="{{ route('servicios.update', $servicio) }}" class="form-grid" x-data="{ tipo: {{ old('tipo_servicio_id', $servicio->tipo_servicio_id) }}, mostrarDesc: {{ old('descripcion', $servicio->descripcion) ? 'true' : 'false' } }">                @csrf @method('PUT')
<div class="form-group">
<label for="operador_id">Operador</label>
<select id="operador_id" name="operador_id" required>                        @foreach ($operadores as $op)                            <option value="{{ $op->id }}" @selected(old('operador_id', $servicio->operador_id) == $op->id)>{{ $op->empleado?->nombreCompleto() }}</option>                        @endforeach                    </select>
<x-input-error :messages="$errors->get('operador_id')" />
</div>
<div class="form-group">
<label for="unidad_id">Unidad</label>
<select id="unidad_id" name="unidad_id" required>                        @foreach ($unidades as $u)                            <option value="{{ $u->id }}" @selected(old('unidad_id', $servicio->unidad_id) == $u->id)>{{ $u->marca }} — {{ $u->placas }}</option>                        @endforeach                    </select>
<x-input-error :messages="$errors->get('unidad_id')" />
</div>
<div class="form-group">
<label>Tipo de Servicio</label>
<input type="hidden" name="tipo_servicio_id" x-bind:value="tipo">
<div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mt-1">                        @foreach ($tiposServicio as $ts)                            <button type="button" @@click="tipo = {{ $ts->id }}; mostrarDesc = false"                                class="flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 text-sm font-medium transition-all duration-150"                                x-bind:class="tipo === {{ $ts->id }} ? 'border-[#FFD500] bg-[#FFF8DC] text-[#1a1a2e] shadow-sm' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300 hover:bg-gray-50'">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
</svg>
<span>{{ $ts->nombre }}</span>                                @if ($ts->descripcion)                                    <span class="text-[10px] text-gray-400 text-center leading-tight">{{ $ts->descripcion }}</span>                                @endif                            </button>                        @endforeach                    </div>
<x-input-error :messages="$errors->get('tipo_servicio_id')" />
<button type="button" @@click="mostrarDesc = !mostrarDesc" class="text-xs text-gray-500 hover:text-gray-700 mt-1.5 underline underline-offset-2">
<span x-show="!mostrarDesc">+ Especificar problema</span>
<span x-show="mostrarDesc">- Ocultar descripción</span>
</button>
<div x-show="mostrarDesc" x-collapse class="mt-2">
<textarea id="descripcion" name="descripcion" rows="2" class="form-input" placeholder="Describe el problema del vehículo...">{{ old('descripcion', $servicio->descripcion) }}</textarea>
<x-input-error :messages="$errors->get('descripcion')" />
</div>
</div>
<div class="form-group">
<label for="estado">Estado</label>
<select id="estado" name="estado" required>
<option value="asignado" @selected(old('estado', $servicio->estado) === 'asignado')>Asignado</option>
<option value="en_proceso" @selected(old('estado', $servicio->estado) === 'en_proceso')>En Proceso</option>
<option value="finalizado" @selected(old('estado', $servicio->estado) === 'finalizado')>Finalizado</option>
<option value="cancelado" @selected(old('estado', $servicio->estado) === 'cancelado')>Cancelado</option>
</select>
<x-input-error :messages="$errors->get('estado')" />
</div>
<div class="form-group">
<label for="fecha_inicio">Fecha de Inicio</label>
<input id="fecha_inicio" name="fecha_inicio" type="datetime-local" value="{{ old('fecha_inicio', $servicio->fecha_inicio?->format('Y-m-d\TH:i')) }}" required>
<x-input-error :messages="$errors->get('fecha_inicio')" />
</div>
<div class="form-group">
<label for="fecha_fin">Fecha de Fin</label>
<input id="fecha_fin" name="fecha_fin" type="datetime-local" value="{{ old('fecha_fin', $servicio->fecha_fin?->format('Y-m-d\TH:i')) }}">
<x-input-error :messages="$errors->get('fecha_fin')" />
</div>
<div class="flex items-center gap-3 pt-2">
<button type="submit" class="btn btn-primary">Actualizar</button>
<a href="{{ route('servicios.index') }}" class="btn btn-ghost">Cancelar</a>
</div>
</form>
</div>
</div>
</div>@push('scripts')<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>@endpush@endsection
