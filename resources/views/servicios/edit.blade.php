@extends('layouts.app')@section('title', 'Editar Servicio #'.$servicio->id)@section('content')<div class="max-w-7xl mx-auto">
<div class="card">
<div class="card-header">
<h3>Editar Servicio #{{ $servicio->id }}</h3>
<a href="{{ route('servicios.index') }}" class="btn btn-sm btn-ghost">Volver</a>
</div>
<div class="card-body">
<form method="POST" action="{{ route('servicios.update', $servicio) }}" x-data="{ tipo: @json(old('tipo_servicio_id') ?? $servicio->tipo_servicio_id), mostrarDesc: @json((bool) (old('descripcion') ?? $servicio->descripcion)) }">                @csrf @method('PATCH')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
<div class="space-y-5">
<div class="card p-0 border-0 shadow-none">
<div class="card-header bg-transparent px-0 pt-0">
<h3>Asignación</h3>
</div>
<div class="card-body px-0 pb-0">
<div class="form-grid">
<div class="form-group">
<label>Operador</label>
<select name="operador_id" required>                        @foreach ($operadores as $op)                            <option value="{{ $op->id }}" @selected(old('operador_id', $servicio->operador_id) == $op->id)>{{ $op->empleado?->nombreCompleto() }}</option>                        @endforeach                    </select>
<x-input-error :messages="$errors->get('operador_id')" />
</div>
<div class="form-group">
<label>Unidad</label>
<select name="unidad_id" required>                        @foreach ($unidades as $u)                            <option value="{{ $u->id }}" @selected(old('unidad_id', $servicio->unidad_id) == $u->id)>{{ $u->marca }} — {{ $u->placas }}</option>                        @endforeach                    </select>
<x-input-error :messages="$errors->get('unidad_id')" />
</div>
<div class="form-group">
<label>Oficina</label>
<select name="oficina_id">                        @foreach ($oficinas as $of)                            <option value="{{ $of->id }}" @selected(old('oficina_id', $servicio->oficina_id) == $of->id)>{{ $of->nombre }}</option>                        @endforeach                    </select>
<x-input-error :messages="$errors->get('oficina_id')" />
</div>
</div>
</div>
</div>
<div class="card p-0 border-0 shadow-none">
<div class="card-header bg-transparent px-0 pt-0">
<h3>Detalles del Servicio</h3>
</div>
<div class="card-body px-0 pb-0">
<div class="form-grid">
<div class="form-group full-width">
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
<div x-show="mostrarDesc" class="mt-2">
<textarea name="descripcion" rows="2" class="form-input" placeholder="Describe el problema del vehículo...">{{ old('descripcion', $servicio->descripcion) }}</textarea>
<x-input-error :messages="$errors->get('descripcion')" />
</div>
</div>
<div class="form-group">
<label>Estado</label>
                    <select name="estado" required>
<option value="asignado" @selected(old('estado', $servicio->estado) === 'asignado')>Asignado</option>
<option value="inicio_servicio" @selected(old('estado', $servicio->estado) === 'inicio_servicio')>Inicio Servicio</option>
<option value="en_sitio_origen" @selected(old('estado', $servicio->estado) === 'en_sitio_origen')>En Sitio Origen</option>
<option value="en_carga" @selected(old('estado', $servicio->estado) === 'en_carga')>En Carga</option>
<option value="en_transito" @selected(old('estado', $servicio->estado) === 'en_transito')>En Tránsito</option>
<option value="en_sitio_destino" @selected(old('estado', $servicio->estado) === 'en_sitio_destino')>En Sitio Destino</option>
<option value="finalizado" @selected(old('estado', $servicio->estado) === 'finalizado')>Finalizado</option>
<option value="cancelado" @selected(old('estado', $servicio->estado) === 'cancelado')>Cancelado</option>
</select>
</div>
<div class="form-group">
<label>Inicio</label>
<input name="fecha_inicio" type="datetime-local" value="{{ old('fecha_inicio', $servicio->fecha_inicio?->format('Y-m-d\TH:i')) }}" required>
</div>
<div class="form-group">
<label>Fin</label>
<input name="fecha_fin" type="datetime-local" value="{{ old('fecha_fin', $servicio->fecha_fin?->format('Y-m-d\TH:i')) }}">
</div>
</div>
</div>
</div>
</div>
<div class="space-y-5">
<div class="card p-0 border-0 shadow-none">
<div class="card-header bg-transparent px-0 pt-0">
<h3>Kilometraje</h3>
</div>
<div class="card-body px-0 pb-0">
<div class="form-grid">
<div class="form-group">
<label>Kms salida</label>
<input type="number" name="kms_salida" value="{{ old('kms_salida', $servicio->kms_salida) }}" min="0">
</div>
<div class="form-group">
<label>Kms llegada cliente</label>
<input type="number" name="kms_llegada_cliente" value="{{ old('kms_llegada_cliente', $servicio->kms_llegada_cliente) }}" min="0">
</div>
<div class="form-group">
<label>Kms término servicio</label>
<input type="number" name="kms_termino_servicio" value="{{ old('kms_termino_servicio', $servicio->kms_termino_servicio) }}" min="0">
</div>
<div class="form-group">
<label>Kms regreso base</label>
<input type="number" name="kms_regreso_base" value="{{ old('kms_regreso_base', $servicio->kms_regreso_base) }}" min="0">
</div>
<div class="form-group">
<label>Kms cobrados reales</label>
<input type="number" name="kms_cobrados_reales" value="{{ old('kms_cobrados_reales', $servicio->kms_cobrados_reales) }}" min="0">
</div>
<div class="form-group">
<label>Costo final real ($)</label>
<input type="number" step="0.01" name="costo_final_real" value="{{ old('costo_final_real', $servicio->costo_final_real) }}" min="0">
</div>
</div>
</div>
</div>
<div class="card p-0 border-0 shadow-none">
<div class="card-header bg-transparent px-0 pt-0">
<h3>Observaciones</h3>
</div>
<div class="card-body px-0 pb-0">
<div class="form-group">
<textarea name="observaciones" rows="3" placeholder="Notas adicionales...">{{ old('observaciones', $servicio->observaciones) }}</textarea>
</div>
</div>
</div>
</div>
</div>
<div class="flex items-center gap-3 pt-4">
<button type="submit" class="btn btn-primary">Actualizar</button>
<a href="{{ route('servicios.index') }}" class="btn btn-ghost">Cancelar</a>
</div>
</form>
</div>
</div>
</div>@endsection